<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class MaterialModel extends BaseModel {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    protected $table = 'MATERIAL';
    protected $tableES = 'EXTSYSTEM';
    protected $fillable;

    public function loadMaterial($q,$EXTSYS){
        $query = "SELECT ID,FCCODE as TEXT, FCNAME FROM $this->table WHERE ISACTIVE = 'TRUE' AND EXTSYSTEM = '$EXTSYS' AND (FCCODE LIKE '%$q%' OR FCNAME LIKE '%$q%')";
        // var_dump($query);
        $res = $this->db->query($query);
        
        return $res->result(); 
    }

    public function ShowData($param) {
        $Lenght = $param['length'];
        $Start = $param['start'];
        $Columns = $param['columns'];
        $Search = $param['search'];
        $Order = $param['order'];
        $EXTSYSTEM = $param['EXTSYSTEM'];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        $SQL = "(SELECT M.ID, M.EXTSYSTEM, M.FCCODE, M.FCNAME, M.DESCRIPTION, M.ISACTIVE, ES.FCNAME AS EXTSYSTEMNAME
                  FROM $this->table M
                 INNER JOIN $this->tableES ES 
                         ON ES.FCCODE = M.EXTSYSTEM
                 WHERE M.EXTSYSTEM like '%$EXTSYSTEM%')";
        $result = $this->db->select(["*"])->from("$SQL FC");
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $result = $result->or_like("UPPER(FC." . $values["data"] . ")", $Search['value'], 'both');
                }
            }
        }
        if ($OrderField == "" || $OrderField == NULL) {
            $result = $result->order_by('FC.FCNAME');
        } else {
            $result = $result->order_by($OrderField, $Order[0]["dir"]);
        }
        $result = $result->limit($Lenght, $Start)->get()->result();

        $CountFil = $this->db->select(["COUNT(*) AS JML"])->from("$SQL FC");
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $CountFil = $CountFil->or_like("UPPER(FC." . $values["data"] . ")", $Search['value'], 'both');
                }
            }
        }
        $CountFil = $CountFil->get()->result();

        $CountAll = $this->db->select("COUNT(*) AS JML")
                        ->from("$this->table M")
                        ->join("$this->tableES ES", 'ES.FCCODE = M.EXTSYSTEM', 'inner')
                        ->like("M.EXTSYSTEM", $EXTSYSTEM, 'both')
                        ->order_by('M.FCNAME')->get()->result();

        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    public function GetData($ID) {
        $this->fillable = ['M.ID', 'M.EXTSYSTEM', 'M.FCCODE', 'M.FCNAME', 'M.DESCRIPTION', 'M.ISACTIVE', 'ES.FCNAME AS EXTSYSTEMNAME'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table M")
                        ->join("$this->tableES ES", 'ES.FCCODE = M.EXTSYSTEM', 'inner')
                        ->where(['M.ID' => $ID])
                        ->order_by('M.FCNAME')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {
        $this->fillable = ['ID', 'FCNAME', 'EXTSYSTEM'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['ISACTIVE' => 'TRUE'])
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function ajaxLiveSearch($Data) {
        $this->fillable = ['ID', 'FCNAME', 'EXTSYSTEM', 'FCCODE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['ISACTIVE' => 'TRUE'])
                        ->where(['EXTSYSTEM' => $Data['extsystem']])
                        ->group_start()
                        ->like("FCNAME", strtoupper($Data['keywords']), 'both')
                        ->or_like("FCCODE", strtoupper($Data['keywords']), 'both')
                        ->or_like("REPLACE(FCCODE, '.')", strtoupper($Data['keywords']), 'both')
                        ->group_end()
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->table WHERE FCCODE = ? AND EXTSYSTEM = ?";
            $Cek = $this->db->query($SQL, [$Data['FCCODE'], $Data['EXTSYSTEM']]);
            if ($Cek->num_rows() > 0 && $Data['ACTION'] == 'ADD') {
                throw new Exception('Data Already Exists !!');
            } elseif ($Cek->num_rows() > 1 && $Data['ACTION'] == 'EDIT') {
                throw new Exception('Data Already Exists !!');
            }
            $dt = [
                'FCCODE' => strtoupper($Data['FCCODE']),
                'EXTSYSTEM' => $Data['EXTSYSTEM'],
                'FCNAME' => $Data['FCNAME'],
                'DESCRIPTION' => $Data['DESCRIPTION'],
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['ACTION'] == 'ADD') {
                $Data['ID'] = $this->uuid->v4();
                $dt['ID'] = $Data['ID'];
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result = $result->set($dt)->insert($this->table);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result = $result->set($dt)
                        ->where(['ID' => $Data['ID']])
                        ->update($this->table);
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function SaveUpload($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            foreach ($Data['DATA'] as $value) {
                $dt = [
                    'FCCODE' => $value['CODEITEM'],
                    'FCNAME' => $value['NAMAITEM'],
                    'DESCRIPTION' => $value['DESCRIPTION'],
                    'PARTNO' => $value['PARTNO'],
                    'ITEMTYPE' => $value['ITEMTYPE'],
                    'EXTSYSTEM' => $value['EXTSYSTEM'],
                    'FCEDIT' => $Data['USERNAME'],
                    'FCENTRY' => $Data['USERNAME'],
                    'FCIP' => $Location,
                    'ISACTIVE' => 'TRUE'
                ];
                $cek = $this->db->select('*')
                                ->from($this->MATERIAL)
                                ->where([
                                    'FCCODE' => $dt['FCCODE'],
                                    'EXTSYSTEM' => $dt['EXTSYSTEM']
                                ])->get()->result();
                if (count($cek) > 0) {
                    throw new Exception('Some Data Already Exists !!!');
                }
                $dt['ID'] = $this->uuid->v4();
                $result = $this->db
                        ->set('LASTUPDATE', "SYSDATE", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                        ->set($dt)->insert($this->MATERIAL);

            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Upload !!'
                ];
            } else {
                throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function Delete($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = $this->db->delete($this->table, ['ID' => $Data['ID']]);
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Deleted !!'
                ];
            } else {
                throw new Exception('Deleted Failed !!');
            }
        } catch (\Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

}
