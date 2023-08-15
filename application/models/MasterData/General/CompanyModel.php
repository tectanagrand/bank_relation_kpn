<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class CompanyModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function loadCompany($q){
        $query = "SELECT ID,COMPANYNAME as TEXT, COMPANYCODE FROM COMPANY WHERE COMPANYNAME LIKE '%".$q."%' OR COMPANYCODE LIKE '%".$q."%'";
        
        $res = $this->db->query($query);
        
        return $res->result(); 
    }


    public function ShowData() {
        // 'C.COMPANY_SUBGROUP',
        $this->fillable = ['C.ID', 'C.COMPANYCODE','C.COMPANY_SUBGROUP','C.COMPANYNAME', 'C.COMPANYNO', 'C.COMPANYTYPE', 'C.ISACTIVE', 'BD.DETAILNAME AS COMPANYTYPENAME'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->COMPANY C")
                        ->join("$this->BMCODEDETAIL BD", "BD.MASTERID = '000002' AND BD.DETAILID = C.COMPANYTYPE", 'left')
                        ->order_by('COMPANYCODE ASC')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($ID) {
        // 'COMPANY_SUBGROUP',
        $this->fillable = ['ID', 'COMPANYCODE', 'COMPANY_SUBGROUP', 'COMPANYNAME', 'COMPANYNO', 'COMPANYTYPE', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->COMPANY)
                        ->where(['ID' => $ID])
                        ->order_by('COMPANYCODE')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {

        $FCCODE = $this->session->userdata('FCCODE');
        
        $q = "select nvl(count(*),0) as hitung from user_company_tab where usercode = '$FCCODE'";
        $cekUser = $this->db->query($q)->row()->HITUNG;
        // var_dump($cekUser);exit;

        if($cekUser == 0){
            $this->fillable = ['ID', 'COMPANYCODE', 'COMPANYNAME', 'COMPANYNO', 'ISACTIVE'];
            $result = $this->db->select($this->fillable)
                            ->from($this->COMPANY)
                            ->where('ISACTIVE',1)
                            ->order_by('COMPANYCODE ASC')->get()->result();    
        }else{
            $q2 = "SELECT c.id, c.companycode,c.companyname,c.companyno, c.isactive FROM company c
                    INNER JOIN user_company_tab uct ON uct.companycode = c.companycode where uct.usercode = '$FCCODE' order by COMPANYNAME ASC";
            $result = $this->db->query($q2)->result();
        }
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    public function GetDataActiveAll() {
        $this->fillable = ['ID', 'COMPANYCODE', 'COMPANYNAME', 'COMPANYNO', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->COMPANY)
                        ->where(['ISACTIVE' => 1])
                        ->order_by('COMPANYCODE ASC')->get()->result();    
        $this->db->close();
        return $result;
    }

    public function GetListSystem($ID) {
        $this->fillable = "ES.FCCODE, ES.FCNAME, ES.DESCRIPTION, DECODE(CE.EXTSYSTEM, NULL, 0, 1) AS ISACTIVE, CE.EXTSYSCOMPANYCODE";
        $SQL = "SELECT $this->fillable
                  FROM $this->EXTSYSTEM ES
                  LEFT JOIN $this->COMPANY_EXTSYS CE 
                         ON CE.EXTSYSTEM = ES.FCCODE
                        AND CE.COMPANY = ?
                  WHERE ES.ISACTIVE = 'TRUE' 
                 ORDER BY ES.FCNAME";
        $query = $this->db->query($SQL, [$ID])->result();
        $this->db->close();
        return $query;
    }

    public function GetListDepartement($ID) {
        $this->fillable = 'ES.FCCODE, ES.FCNAME, ES.DESCRIPTION, DECODE(CE.DEPARTMENT, NULL, 0, 1) AS ISACTIVE';
        $SQL = "SELECT $this->fillable
                  FROM $this->DEPARTMENT ES
                  LEFT JOIN $this->COMPANY_DEPART CE 
                         ON CE.DEPARTMENT = ES.FCCODE
                        AND CE.COMPANY = ?
                  WHERE ES.ISACTIVE = 'TRUE' 
                 ORDER BY ES.FCNAME";
        $query = $this->db->query($SQL, [$ID])->result();
        $this->db->close();
        return $query;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->COMPANY WHERE ID <> ? AND COMPANYCODE = ?";
            $Cek = $this->db->query($SQL, [$Data['ID'], $Data['COMPANYCODE']]);
            if ($Cek->num_rows() > 0) {
                throw new Exception('Company Code Already Exists !!');
            }
            $dt = [
                'COMPANY_SUBGROUP' => $Data['COMPANY_SUBGROUP'],
                'COMPANYCODE' => strtoupper($Data['COMPANYCODE']),
                'COMPANYNAME' => strtoupper($Data['COMPANYNAME']),
                'COMPANYNO' => $Data['COMPANYNO'],
                'COMPANYTYPE' => $Data['COMPANYTYPE'],
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
                $result1 = $result1->set($dt)->insert($this->COMPANY);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result1 = $result1->set($dt)
                        ->where(['ID' => $Data['ID']])
                        ->update($this->COMPANY);
            }
            //EXTSYSTEM
            $cek = $this->db->query("SELECT * FROM $this->COMPANY_EXTSYS WHERE COMPANY = ?", [$Data['ID']])->num_rows();
            if ($cek > 0) {
                $updatesub = $this->db->delete($this->COMPANY_EXTSYS, ['COMPANY' => $Data['ID']]);
            } else {
                $updatesub = 1;
            }
            $result2 = FALSE;
            if ($updatesub) {
                if (count($Data['DATA']) > 0 && $Data['DATA'] != 0) {
                    foreach ($Data['DATA'] as $dt) {
                        $datadetail = [
                            'COMPANY' => $Data['ID'],
                            'EXTSYSTEM' => $dt['FCCODE'],
                            'EXTSYSCOMPANYCODE' => $dt['EXTSYSCOMPANYCODE'],
                            'FCENTRY' => $Data['USERNAME'],
                            'FCEDIT' => $Data['USERNAME'],
                            'FCIP' => $Location
                        ];
                        $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                        ->set($datadetail)->insert($this->COMPANY_EXTSYS);
                    }
                } else {
                    $result2 = TRUE;
                }
            } else {
                throw new Exception("Data Save Failed !!");
            }
            //DEPARTMENT
            $cek1 = $this->db->query("SELECT * FROM $this->COMPANY_DEPART WHERE COMPANY = ?", [$Data['ID']])->num_rows();
            if ($cek1 > 0) {
                $updatesub1 = $this->db->delete($this->COMPANY_DEPART, ['COMPANY' => $Data['ID']]);
            } else {
                $updatesub1 = 1;
            }
            $result3 = FALSE;
            if ($updatesub1) {
                if (count($Data['DATA1']) > 0 && $Data['DATA1'] != 0) {
                    foreach ($Data['DATA1'] as $dt) {
                        $datadetail = [
                            'COMPANY' => $Data['ID'],
                            'DEPARTMENT' => $dt['FCCODE'],
                            'FCENTRY' => $Data['USERNAME'],
                            'FCEDIT' => $Data['USERNAME'],
                            'FCIP' => $Location
                        ];
                        $result3 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                        ->set($datadetail)->insert($this->COMPANY_DEPART);
                    }
                } else {
                    $result3 = TRUE;
                }
            } else {
                throw new Exception("Data Save Failed !!");
            }

            if ($result1 && $result2 && $result3) {
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
            $result = FALSE;
            $result1 = $this->db->delete($this->COMPANY_EXTSYS, ['COMPANY' => $Data['ID']]);
            $result2 = $this->db->delete($this->COMPANY_DEPART, ['COMPANY' => $Data['ID']]);
            $result3 = $this->db->delete($this->COMPANY, ['ID' => $Data['ID']]);
            if ($result1 && $result2 && $result3) {
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

?>