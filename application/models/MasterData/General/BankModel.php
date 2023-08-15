<?php

defined('BASEPATH') or exit('No direct script access allowed');

class BankModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function ShowData() {
        $this->fillable = ['B.FCCODE', 'B.FCNAME', 'B.BANKACCOUNT', 'B.CURRENCY', 'B.ADDRESS', 'B.CITY', 'B.STATE', 'B.REMARKS', 'B.ACTIVATION', 'B.COMPANY', 'B.COMPANYGROUP','B.ISUSEFORMONTHLYFORECAST' ,'C.COMPANYNAME AS COMPANYNAME', 'CG.FCNAME AS COMPANYGROUPNAME'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->BANK B")
                        ->join("$this->COMPANY C", "C.ID = B.COMPANY", "left")
                        ->join("$this->COMPANY_GROUP CG", "CG.FCCODE = B.COMPANYGROUP", "left")
                        ->order_by('B.FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($ID) {
        $this->fillable = ['B.FCCODE', 'B.FCNAME', 'B.BANKACCOUNT', 'B.CURRENCY', 'B.ADDRESS', 'B.CITY', 'B.STATE', 'B.REMARKS', 'B.ACTIVATION', 'B.COMPANY', 'B.COMPANYGROUP','B.ISUSEFORMONTHLYFORECAST' ,'C.COMPANYNAME AS COMPANYNAME', 'CG.FCNAME AS COMPANYGROUPNAME'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->BANK B")
                        ->join("$this->COMPANY C", "C.ID = B.COMPANY", "left")
                        ->join("$this->COMPANY_GROUP CG", "CG.FCCODE = B.COMPANYGROUP", "left")
                        ->where(['B.FCCODE' => $ID])
                        ->order_by('B.FCNAME')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {
        $this->fillable = ['FCCODE', 'FCNAME', 'BANKACCOUNT', 'CURRENCY'];
        $result = $this->db->select($this->fillable)
                        ->from($this->BANK)
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetBankBaseCompany($companyID) {
        $SQL = "SELECT  FCCODE, FCNAME, BANKACCOUNT
                FROM $this->BANK
                WHERE COMPANY = ?
                ORDER BY FCNAME";
        $result = $this->db->query($SQL, $companyID)->result();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->BANK WHERE FCCODE = ?";
            $Cek = $this->db->query($SQL, [$Data['FCCODE']]);
            if ($Cek->num_rows() > 0 && $Data['ACTION'] == 'ADD') {
                throw new Exception('Data Already Exists !!');
            } elseif ($Cek->num_rows() > 1 && $Data['ACTION'] == 'EDIT') {
                throw new Exception('Data Already Exists !!');
            }
            $dt = [
                'FCNAME' => $Data['FCNAME'],
                'BANKACCOUNT' => $Data['BANKACCOUNT'],
                'CURRENCY' => $Data['CURRENCY'],
                'ADDRESS' => $Data['ADDRESS'],
                'CITY' => $Data['CITY'],
                'STATE' => $Data['STATE'],
                'REMARKS' => (isset($Data['REMARKS'])) ? $Data['REMARKS'] : NULL,
                'ACTIVATION' => $Data['ACTIVATION'],
                'COMPANY' => $Data['COMPANY'],
                'COMPANYGROUP' => $Data['COMPANYGROUP'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location,
                'ISUSEFORMONTHLYFORECAST' => $Data['ISUSEFORMONTHLYFORECAST']
            ];
            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['ACTION'] == 'ADD') {
                $dt['FCCODE'] = strtoupper($Data['FCCODE']);
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result = $result->set($dt)->insert($this->BANK);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result = $result->set($dt)
                        ->where(['FCCODE' => strtoupper($Data['FCCODE'])])
                        ->update($this->BANK);
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
            $result = $this->db->delete($this->BANK, ['FCCODE' => $Data['FCCODE']]);
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
