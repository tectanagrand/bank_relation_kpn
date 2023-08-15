<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class MaterialGroupModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    protected $table = 'MATERIAL_GROUP';
    protected $tableMS = 'MATERIAL_SUBGROUP';
    protected $tableMGI = 'MATERIAL_GROUPITEM';
    protected $tableM = 'MATERIAL';
    protected $tableES = 'EXTSYSTEM';
    protected $tableFC = 'FORECAST_CATEGORY';
    protected $fillable;

    public function ShowData() {
        $this->fillable = ['MG.ID', 'MG.FCCODE', 'MG.FCNAME', 'MG.SUBGROUP1', 'MG.SUBGROUP2', 'MG.DESCRIPTION', 'MG.ISACTIVE', 'MS1.FCNAME AS SUBGROUP1NAME',
            'MS2.FCNAME AS SUBGROUP2NAME'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table MG")
                        ->join("$this->tableMS MS1", 'MS1.FCCODE = MG.SUBGROUP1', 'left')
                        ->join("$this->tableMS MS2", 'MS2.FCCODE = MG.SUBGROUP2', 'left')
                        ->order_by('MG.FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($ID) {
        $this->fillable = ['MG.ID', 'MG.FCCODE', 'MG.FCNAME', 'MG.SUBGROUP1', 'MG.SUBGROUP2', 'MG.DESCRIPTION', 'MG.ISACTIVE', 'FC.FCCODE AS FORECAST_CATEGORY'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table MG")
                        ->join("$this->tableFC FC", 'FC.FCCODE = MG.FORECAST_CATEGORY', 'left')
                        ->where(['MG.ID' => $ID])
                        ->order_by('MG.FCNAME')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {
        $this->fillable = ['MG.ID', 'MG.FCCODE', 'MG.FCNAME', 'FC.FCCODE AS FORECAST_CATEGORY'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table MG")
                        ->join("$this->tableFC FC", 'FC.FCCODE = MG.FORECAST_CATEGORY', 'left')
                        ->where(['MG.ISACTIVE' => 'TRUE'])
                        ->order_by('MG.FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetListMaterial($param) {
        $Lenght = $param['length'];
        $Start = $param['start'];
        $Columns = $param['columns'];
        $Search = $param['search'];
        $Order = $param['order'];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        $SQL = "(SELECT M.ID, M.EXTSYSTEM, M.FCCODE, M.FCNAME, M.DESCRIPTION, M.ISACTIVE, ES.FCNAME AS EXTSYSTEMNAME
        FROM $this->tableM M
        INNER JOIN $this->tableES ES 
               ON ES.FCCODE = M.EXTSYSTEM
        LEFT JOIN $this->tableMGI MGI
               ON MGI.EXTSYSTEM = M.EXTSYSTEM
              AND MGI.MATERIAL = M.ID
        WHERE M.ISACTIVE = 'TRUE'
              AND MGI.MATERIALGROUP = '".strval($param['ID'])."')";
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

        $this->fillable = 'COUNT(*) AS JML';
        $SQL = "SELECT $this->fillable
                  FROM $this->tableM M
                 INNER JOIN $this->tableES ES
                         ON ES.FCCODE = M.EXTSYSTEM
                  LEFT JOIN $this->tableMGI MGI
                         ON MGI.EXTSYSTEM = M.EXTSYSTEM
                        AND MGI.MATERIAL = M.ID
                 WHERE M.ISACTIVE = 'TRUE'
                   AND MGI.MATERIALGROUP = ?
                 ORDER BY M.FCNAME";
        $CountAll = $this->db->query($SQL, [strval($param['ID'])])->result();


        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];

        $this->db->close();
        return $return;
    }

    public function GetNotselectedMaterial($ID) {
        $this->fillable = 'M.EXTSYSTEM, M.ID, M.FCCODE, M.FCNAME, M.DESCRIPTION, ES.FCNAME AS EXTSYSTEMNAME';
        $SQL = "SELECT $this->fillable
                  FROM $this->tableM M
                 INNER JOIN $this->tableES ES
                         ON ES.FCCODE = M.EXTSYSTEM
                  LEFT JOIN $this->tableMGI MGI
                         ON MGI.EXTSYSTEM = M.EXTSYSTEM
                        AND MGI.MATERIAL = M.ID
                 WHERE M.ISACTIVE = 'TRUE'
                    AND MGI.MATERIALGROUP IS NULL
                 ORDER BY M.ISACTIVE";
        $query = $this->db->query($SQL)->result();
        $this->db->close();
        return $query;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $dt = [
                'FCCODE' => strtoupper($Data['FCCODE']),
                'FCNAME' => $Data['FCNAME'],
                'FORECAST_CATEGORY' => $Data['FORECAST_CATEGORY'],
                'DESCRIPTION' => $Data['DESCRIPTION'],
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['ACTION'] == 'ADD') {
                $Data['ID'] = $this->uuid->v4();
                $dt['ID'] = $Data['ID'];
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result1 = $result1->set($dt)->insert($this->table);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result1 = $result1->set($dt)
                        ->where(['ID' => $Data['ID']])
                        ->update($this->table);
            }
            
            if ($result1) {
                $result = TRUE;
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

    public function saveMaterial($Data, $Location) {
        try {
            $result = false;
            if (count($Data['DATA']['EXTSYSTEM']) > 0 && $Data['DATA']['EXTSYSTEM'] != 0) {
                foreach ($Data['DATA']['EXTSYSTEM'] as $key => $dt) {
                    $datadetail = [
                        'MATERIALGROUP' => $Data['ID'],
                        'EXTSYSTEM' => $dt,
                        'MATERIAL' => $Data['DATA']['MATERIAL'][$key],
                        'FCENTRY' => $Data['USERNAME'],
                        'FCEDIT' => $Data['USERNAME'],
                        'FCIP' => $Location
                    ];
                    $result = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                            ->set($datadetail)->insert($this->tableMGI);
                }
                if ($result) {
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => 'Data has been Successfully Saved !!'
                    ];
                } else {
                    throw new Exception("Data Save Failed !!");
                }
            } else {
                throw new Exception("Data Save Failed !!");
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
            $result1 = $this->db->delete($this->tableMGI, ['MATERIALGROUP' => $Data['ID']]);
            $result2 = $this->db->delete($this->table, ['ID' => $Data['ID']]);
            if ($result1 && $result2) {
                $result = TRUE;
            }
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

    public function DeleteGroupItem($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result1 = $this->db->delete($this->tableMGI, ['MATERIAL' => $Data['ID']]);
            if ($result1) {
                $result = TRUE;
            }
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
    
    public function ShowMaterial($ID) {
        $this->fillable = ['M.FCNAME as FCNAME', 'M.FCCODE as FCCODE', 'MGI.EXTSYSTEM'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->tableMGI MGI")
                        ->join("$this->tableM M", 'M.ID = MGI.MATERIAL', 'left')
                        ->where(['MGI.MATERIALGROUP' => $ID])
                        ->order_by('MGI.LASTUPDATE')->get()->result();
        $this->db->close();

        return $result;
    }

}
