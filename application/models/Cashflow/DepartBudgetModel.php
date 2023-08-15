<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DepartBudgetModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function ShowData($param) {
        $month = $param['MONTH'];
        $year = $param['YEAR'];

        $this->fillable = ['D.FCCODE', 'D.FCNAME', 'NVL(DB.BUDGET, 0.00) AS BUDGET'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->DEPARTMENT D")
                        ->join("$this->DEPARTBUDGET DB", "DB.DEPARTMENT = D.FCCODE AND DB.YEAR = $year AND DB.MONTH = $month", "LEFT")
                        ->where(['D.ISACTIVE' => 'TRUE'])
                        ->or_where('DB.DEPARTMENT IS NULL')
                        ->order_by('D.FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;

            $SQL = "SELECT * FROM $this->DEPARTBUDGET WHERE DEPARTMENT = ? AND YEAR = ? AND MONTH = ?";
            $Cek = $this->db->query($SQL, [$Data['ID'], $Data['YEAR'], $Data['MONTH']]);

            $dt = [
                'DEPARTMENT' => $Data['ID'],
                'YEAR' => $Data['YEAR'],
                'MONTH' => $Data['MONTH'],
                'BUDGET' => $Data['BUDGET'],
                'FCEDIT' => $Data['USERNAME'],
                'ISACTIVE' => 1,
                'FCIP' => $Location
            ];

            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTUPDATE', "SYSDATE", false);

            if ($Cek->num_rows() > 0) {
                $result = $result->set($dt)
                        ->where(['DEPARTMENT' => $Data['ID'], 'YEAR' => $Data['YEAR'], 'MONTH' => $Data['MONTH']])
                        ->update($this->DEPARTBUDGET);
            } else {
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result = $result->set($dt)->insert($this->DEPARTBUDGET);
                
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

}
