<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class MstDeptPurchOrgModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }


    protected $table = 'DEPARTMENT_PURCHORG';
    protected $fillable;

    public function ShowData($param) {
        // $result = $this->db->select("*")
        //                 ->from("DEPARTMENT_PURCHORG")
        //                 ->order_by('LASTUPDATE DESC')->get()->result();
        // $this->db->close();
        // return $result;
        
        $BUSINESSUNIT = strtoupper($param['BUSINESSUNIT']);
        $PURCHORG     = strtoupper($param['PURCHORG']);

        // $Lenght = $param["length"];
        // $Start = $param["start"];
        // $Columns = $param["columns"];
        // $Search = $param["search"];
        // $Order = $param["order"];
        // $OrderField = $Columns[$Order[0]["column"]]["data"];

        $WHERE = "";
        if(!empty($BUSINESSUNIT) && !empty($PURCHORG)) {
            $WHERE = " WHERE BUSINESSUNIT = '$BUSINESSUNIT' AND PURCHORG = '$PURCHORG' ";
        }
        if(!empty($BUSINESSUNIT) && empty($PURCHORG)){
            $WHERE = " WHERE BUSINESSUNIT = '$BUSINESSUNIT'";
        }
        if(!empty($PURCHORG) && empty($BUSINESSUNIT)){
            $WHERE = " WHERE PURCHORG = '$PURCHORG'";
        }

        $query  = "SELECT * FROM DEPARTMENT_PURCHORG ";
        $query .= $WHERE . " ORDER BY LASTUPDATE DESC ";

        $return = $this->db->query($query)->result();
        // var_dump($this->db->last_query());exit();
        // $idx = 1;
        // $SQLW = "";
        // if ($Search["regex"] == 'true') {
        //     $Search['value'] = strtoupper($Search['value']);
        //     foreach ($Columns as $values) {
        //         if ($values["data"] != NULL && $values["data"] != '') {
        //             $FIELD = "FC." . $values["data"];
        //             $VAL = "%" . $Search["value"] . "%";
        //             if ($idx == 1) {
        //                 $SQLW .= " WHERE";
        //             } else {
        //                 $SQLW .= " OR";
        //             }
        //             $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
        //             $idx++;
        //         }
        //     }
        // }
        // $SQLO = "";
        // if ($OrderField == "" || $OrderField == NULL) {
        //     $SQLO = " ORDER BY LASTUPDATE DESC";
        // } else {
        //     $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        // }
        // $result = $this->db->query("SELECT * FROM $query FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        // $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $query FC $SQLW")->result();
        // $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $query FC")->result();
        // $return = [
        //     "data" => $result,
        //     "recordsTotal" => $CountAll[0]->JML,
        //     "recordsFiltered" => $CountFil[0]->JML
        // ];
        $this->db->close();
        return $return;

    }

    public function GetData($ID) {
        $q      = "SELECT P.ID, P.BUSINESSUNIT, P.PURCHORG, P.DEPARTMENT, BU.FCNAME FROM DEPARTMENT_PURCHORG P INNER JOIN BUSINESSUNIT_EXTSYS DBU ON DBU.EXTSYSBUSINESSUNITCODE = P.BUSINESSUNIT INNER JOIN BUSINESSUNIT BU ON BU.ID = DBU.BUSINESSUNIT WHERE P.ID = '$ID'";
        $result = $this->db->query($q)->row();
        $this->db->close();
        return $result;
    }

    public function ShowStagingData($param){
        $WHERE = "";
        $COMPANY      = $param['COMPANY'];
        $PLANT        = $param['PLANT'];
        $PURCHORG     = $param['PURCHORG'];

        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        $query  = "( select distinct a.company, a.businessunit as plant, a.department as purchorg, c.fccode as businessunit_csf, d.purchorg as purchorg_csf, d.department from cf_trans@dblink_staging a ";
        $query .= " left join businessunit_extsys b on ( a.businessunit = b.extsysbusinessunitcode and b.extsystem = 'SAPHANA') ";
        $query .= " left join businessunit c on ( b.businessunit = c.id) ";
        $query .= " left join department_purchorg d on ( c.fccode = d.businessunit and a.department = d.purchorg) ";
        $query .= " where a.isactive = 'TRUE' and d.department is null order by a.businessunit,a.department )";

        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            $SQLO = " ORDER BY DATE_RECEIPT DESC";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $query FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $query FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $query FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    public function saveStaging($param,$Location){
        // echo "<pre>";
        // var_dump($param);exit();
        // var_dump($param['dtPurch'] );exit; 
        try {
            foreach($param['dtPurch'] AS $key => $row) {
                $this->db->trans_begin();

                $result = FALSE;
                $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
                $USERNAME     = $this->session->userdata('username');

                $dt = [
                    'BUSINESSUNIT' => strtoupper($row['PLANT']),
                    'PURCHORG' => strtoupper($row['PURCHORG']),
                    'DEPARTMENT' => $row['DEPARTMENT'],
                    'FCEDIT' => $USERNAME,
                    'FCIP' => $Location
                ];

                $cekBU = "SELECT * FROM BUSINESSUNIT_EXTSYS WHERE EXTSYSBUSINESSUNITCODE = '".$row['PLANT']."' AND EXTSYSTEM = 'SAPHANA' ";
                // var_dump($cekBU);exit;
                $cekBU = $this->db->query($cekBU);

                if($cekBU->num_rows() < 1){
                    throw new Exception("Business Unit ".$row['PLANT']." Not Registered, Please Contact Your Administrator");
                }

                $cekDupe = "SELECT * FROM DEPARTMENT_PURCHORG WHERE BUSINESSUNIT = '".$row['PLANT']."' AND PURCHORG = '".$row['PURCHORG']."' AND DEPARTMENT = '".$row['DEPARTMENT']."'";
                // var_dump($cekBU);exit;
                $cekDupe = $this->db->query($cekDupe);

                if($cekDupe->num_rows() > 1){
                    throw new Exception("Business Unit ".$row['PLANT']." Already Registered");
                }

                // var_dump($dt);exit;
                $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                if ($param['ACTION'] == 'ADD') {
                    // $Data['ID'] = $this->uuid->v4();
                    $dt['ID'] = $this->uuid->v4();
                    $dt['FCENTRY'] = $USERNAME;
                    $result1 = $result1->set($dt)->insert($this->table);
                } elseif ($param['ACTION'] == 'EDIT') {
                    $result1 = $result1->set($dt)
                            ->where(['ID' => $param['ID']])
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

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $dt = [
                'BUSINESSUNIT' => strtoupper($Data['BUSINESSUNIT']),
                'PURCHORG' => strtoupper($Data['PURCHORG']),
                'DEPARTMENT' => $Data['DEPARTMENT'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];

            // var_dump($dt);exit;
            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['ACTION'] == 'ADD') {
                // $Data['ID'] = $this->uuid->v4();
                $dt['ID'] = $this->uuid->v4();
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

    public function Delete($Data, $Location) {
        try {
            $this->db->trans_begin();
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

    public function loadBU($q){
        $query = "SELECT DBU.EXTSYSBUSINESSUNITCODE|| ' - ' || BU.FCNAME as TEXT, DBU.EXTSYSBUSINESSUNITCODE as ID FROM BUSINESSUNIT_EXTSYS DBU INNER JOIN BUSINESSUNIT BU ON BU.ID = DBU.BUSINESSUNIT WHERE DBU.EXTSYSTEM = 'SAPHANA' AND DBU.EXTSYSBUSINESSUNITCODE LIKE '%".$q."%' OR BU.FCNAME LIKE '%".$q."%'";
        
        $res = $this->db->query($query);
        // var_dump($query);exit;
        return $res->result(); 
    }

    public function loadPurch($q){
        $query = "SELECT PURCHORG AS TEXT, PURCHORG AS ID FROM PURCHORG WHERE PURCHORG LIKE '%".$q."%'";
        
        $res = $this->db->query($query);
        
        return $res->result(); 
    }

}
