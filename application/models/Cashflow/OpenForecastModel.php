<?php

defined('BASEPATH') or exit('No direct script access allowed');

class OpenForecastModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }
    public function ShowData($param)
    {
        $SQL="SELECT F.DEPARTMENT, F.USERNAME, F.FLAG_ACTIVE, CASE WHEN F.CASHFLOWTYPE = '1' Then 'Cash Out' Else 'Cash In' End CT, F.CASHFLOWTYPE, F.YEAR, F.MONTH, F.COMPANYGROUP, F.COMPANYSUBGROUP
                FROM FORECAST_VALIDATION F
               WHERE F.FLAG_ACTIVE = '1'
               AND F.YEAR = ?
               AND F.MONTH = ?";
        $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH']])->result();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location)
    {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $dt = [
                'DEPARTMENT' => $Data['DEPARTMENT'],
                'YEAR' => $Data['YEAR'],
                'MONTH' => $Data['MONTH'],
                'CASHFLOWTYPE' => $Data['CASHFLOWTYPE'], 
                'UPDATED_BY' => $Data['USERNAME'],
                'UPDATED_LOC' => $Location,
                'CREATED_LOC' => $Location
            ];

            $cek = $this->db->query("SELECT * FROM $this->FORECAST_VALIDATION WHERE DEPARTMENT = ? AND YEAR = ? AND MONTH = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?", [$Data['DEPARTMENT'], $Data['YEAR'], $Data['MONTH'], $Data['CASHFLOWTYPE'],$Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']])->num_rows();

            if ($cek > 0) {
                $result = $this->db->set('FLAG_ACTIVE', '0', false)
                                   ->set('KEYSAVE', 'null', false);
                
                $result = $result->set($dt)
                        ->where('DEPARTMENT', $Data['DEPARTMENT'])
                        ->where('YEAR', $Data['YEAR'])
                        ->where('MONTH', $Data['MONTH'])
                        ->where('USERNAME', $Data['USERNAME'])
                        ->where('CASHFLOWTYPE', $Data['CASHFLOWTYPE'])
                        ->where('COMPANYGROUP', $Data['COMPANYGROUP'])
                        ->where('COMPANYSUBGROUP', $Data['COMPANYSUBGROUP'])
                        ->update($this->FORECAST_VALIDATION);

            } else {

                $dt['DEPARTMENT'] = $Data['DEPARTMENT'];
                $dt['YEAR'] = $Data['YEAR'];
                $dt['MONTH'] = $Data['MONTH'];
                $dt['CREATED_BY'] = $Data['USERNAME'];
                $dt['COMPANYGROUP'] = $Data['COMPANYGROUP'];
                $dt['COMPANYSUBGROUP'] = $Data['COMPANYSUBGROUP'];

                $result = $this->db->set('UPDATED_AT', "SYSDATE", false)
                                    ->set('CREATED_AT', "SYSDATE", false);
        
                $result = $result->set($dt)->insert($this->FORECAST_VALIDATION);
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