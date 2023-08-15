<?php

defined('BASEPATH') or exit('No direct script access allowed');

//use Carbon\Carbon;

class VendorModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    protected $table = 'SUPPLIER';
    protected $tableC = 'COMPANY';
    protected $tableB = 'BUSINESSUNIT';
    protected $fillable;

    public function loadVendor($q){
        $query = "SELECT ID,FCNAME as TEXT,BIC, FCCODE FROM $this->table WHERE FCCODE LIKE '%VL%' AND ISACTIVE = 'TRUE' AND FCNAME LIKE '%$q%' ESCAPE '!'";
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
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        $SQL = "(SELECT S.*
                  FROM $this->table S)";
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
                ->from("$this->table S")
                ->order_by('S.FCNAME')->get()->result();

        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];

        $this->db->close();
        return $return;

    }

    public function GetData($ID) {
        $this->fillable = ['*'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['ID' => $ID])
                        ->order_by('ID')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataAjax($params) {
        $this->fillable = ['ID', 'FCNAME'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        // ->where(['ISACTIVE' => 'TRUE', 'BUSINESSUNIT' => $params['businessUnit']])
                        ->where(['ISACTIVE' => 'TRUE'])
                        ->like("FCNAME", strtoupper($params['keywords']), "both")
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $dt = [
                'FCCODE' => $Data['FCCODE'],
                'FCNAME' => strtoupper($Data['FCNAME']),
                'ADDRESS' => $Data['ADDRESS'],
                'CITY' => $Data['CITY'],
                'EMAIL' => $Data['EMAIL'],
                'BANKNAME' => $Data['BANKNAME'],
                'BANKACCOUNT' => $Data['BANKACCOUNT'],
                'DESCRIPTION' => (isset($Data['DESCRIPTION'])) ? $Data['DESCRIPTION'] : NULL,
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['ACTION'] == 'ADD') {
                $Data['ID'] = $this->uuid->v4();
                $dt['ID']  = $Data['ID'];
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

    public function SaveUpload($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            foreach ($Data['DATA'] as $value) {
                $dt = [
                    'FCCODE' => $value['CODESUPPLIER'],
                    'FCNAME' => $value['NAMASUPPLIER'],
                    'ADDRESS' => $value['ADDRESS'],
                    'CITY' => $value['CITY'],
                    'BANKNAME' => $value['BANKNAME'],
                    'BANKACCOUNT' => $value['BANKACCOUNT'],
                    'EMAIL' => $value['EMAIL'],
                    'DESCRIPTION' => (isset($Data['DESCRIPTION'])) ? $Data['DESCRIPTION'] : NULL,
                    'ISACTIVE' => 'TRUE'
                ];

                $cek = $this->db->select('*')
                                ->from($this->table)
                                ->where([
                                    'FCCODE' => $dt['FCCODE']
                                ])->get()->result();
                if (count($cek) > 0) {
                    throw new Exception('Some Data Already Exists !!!');
                }
                $dt['ID'] = $this->uuid->v4();
                $result = $this->db
                        ->set('LASTUPDATE', "SYSDATE", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                        ->set($dt)->insert($this->table);

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

    public function Delete($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $result1 = $this->db->delete($this->table, ['ID' => $Data['ID']]);
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

}
