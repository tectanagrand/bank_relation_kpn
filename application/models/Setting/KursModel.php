<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class KursModel extends BaseModel {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    public function ShowData($param)
    {
        $SQL="SELECT CD.DETAILID AS CURSCODE, CD.DETAILNAME AS CURSNAME, NVL(C.RATE, 1) AS RATE
                FROM BMCODEDETAIL CD
                LEFT JOIN CURS C
                     ON C.CURSCODE = CD.DETAILID
                     AND C.CURSYEAR = ?
                     AND C.CURSMONTH = ?
               WHERE CD.MASTERID = '000001' AND FLAG_ACTIVE ='1' ORDER BY CD.DETAILID ASC";
        $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH']])->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }
    
    public function kursHistory($params) {
        $CURSDATE = $params['CURSDATE'];

        $this->fillable = ['CURS.CURSCODE', 'BMC.DETAILID', 'BMC.DETAILNAME', 'BMC.REMARK', 'TO_CHAR(CURS.CURSDATE, '."'".'MM/DD/YYYY'."'".') AS CURSDATE', 'DECODE(CURS.RATEBUY, NULL, 0, CURS.RATEBUY) AS RATEBUY',
        'DECODE(CURS.RATESELL, NULL, 0, CURS.RATESELL) AS RATESELL'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->BMCODEDETAIL BMC")
                        ->join("$this->CURS_HISTORY CURS", "CURS.CURSCODE = BMC.DETAILID AND TO_NUMBER(TO_CHAR(CURS.CURSDATE, 'yyyymmdd')) = $CURSDATE", 'left')
                        ->where("BMC.MASTERID = '000001'")
                        ->get()->result();
        $this->db->close();
        return $result;
    }

    public function currency() {
        $this->fillable = ['DETAILID', 'DETAILNAME', 'REMARK'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->BMCODEDETAIL BMC")
                        ->where("MASTERID = '000001'")
                        ->get()->result();
        $this->db->close();
        return $result;
    }

    public function saveKurs($Data) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $dt = [
                'RATEBUY' => $Data['RATEBUY'],
                'RATESELL' => $Data['RATESELL'],
                'RATESELL' => $Data['RATESELL'],
                'UPDATED_BY' => $Data['USERNAME']
            ];

            $cek = $this->db->query("SELECT * FROM $this->CURS_HISTORY WHERE CURSCODE = ? AND CURSDATE = ?", [$Data['CURSCODE'], $Data['CURSDATE']])->num_rows();

            if ($cek > 0) {
                $result = $this->db->set('UPDATED_AT', "SYSDATE", false)
                                    ->set('CURSDATE', "TO_DATE('" . $Data['CURSDATE2'] . "','mm/dd/yyyy')", false);
                
                $result = $result->set($dt)
                        ->where('CURSCODE', $Data['CURSCODE'])
                        ->where('CURSDATE', $Data['CURSDATE'])
                        ->update($this->CURS_HISTORY);

            } else {
                // $getLatestID = $this->db->query("SELECT MAX(DETAILID) AS DETAILID FROM $this->BMCODEDETAIL WHERE MASTERID = '000001'")->row();
                $dt['CURSCODE'] = $Data['CURSCODE'];
                $dt['CREATED_BY'] = $Data['USERNAME'];

                $result = $this->db->set('UPDATED_AT', "SYSDATE", false)
                                    ->set('CREATED_AT', "SYSDATE", false)
                                    ->set('CURSDATE', "TO_DATE('" . $Data['CURSDATE2'] . "','mm/dd/yyyy')", false);
        
                $result = $result->set($dt)->insert($this->CURS_HISTORY);
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

    public function Save($Data, $Location)
    {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $dt = [
                'CURSCODE' => $Data['CURSCODE'],
                'CURSYEAR' => $Data['YEAR'],
                'CURSMONTH' => $Data['MONTH'],
                'RATE' => $Data['RATE'],
                'UPDATED_BY' => $Data['USERNAME'],
                'UPDATED_LOC' => $Location,
                'CREATED_LOC' => $Location
            ];

            $cek = $this->db->query("SELECT * FROM $this->CURS WHERE CURSCODE = ? AND CURSYEAR = ? AND CURSMONTH = ?", [$Data['CURSCODE'], $Data['YEAR'], $Data['MONTH']])->num_rows();

            if ($cek > 0) {
                $result = $this->db->set('UPDATED_AT', "SYSDATE", false);
                
                $result = $result->set($dt)
                        ->where('CURSCODE', $Data['CURSCODE'])
                        ->where('CURSYEAR', $Data['YEAR'])
                        ->where('CURSMONTH', $Data['MONTH'])
                        ->update($this->CURS);

            } else {

                $dt['CURSCODE'] = $Data['CURSCODE'];
                $dt['CURSYEAR'] = $Data['YEAR'];
                $dt['CURSMONTH'] = $Data['MONTH'];
                $dt['CREATED_BY'] = $Data['USERNAME'];

                $result = $this->db->set('UPDATED_AT', "SYSDATE", false)
                                    ->set('CREATED_AT', "SYSDATE", false);
        
                $result = $result->set($dt)->insert($this->CURS);
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

    public function saveCurrency($Data) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $dt = [
                'DETAILNAME' => $Data['DETAILNAME'],
                'MASTERID' => '000001',
                'REMARK' => $Data['REMARK'],
                'UPDATED_BY' => $Data['USERNAME']
            ];

            if (isset($Data['DETAILID']) && $Data['DETAILID'] != '' && $Data['DETAILID'] != null) {
                $result = $this->db->set('UPDATED_AT', "SYSDATE", false);
                
                $result = $result->set($dt)
                        ->where('MASTERID', '000001')
                        ->where('DETAILID', $Data['DETAILID'])
                        ->update($this->BMCODEDETAIL);

            } else {
                $getLatestID = $this->db->query("SELECT MAX(DETAILID) AS DETAILID FROM $this->BMCODEDETAIL WHERE MASTERID = '000001'")->row();

                $dt['MASTERID'] = '000001';
                $dt['DETAILID'] = $getLatestID->DETAILID + 1;
                $dt['CREATED_BY'] = $Data['USERNAME'];

                $result = $this->db->set('UPDATED_AT', "SYSDATE", false)
                                    ->set('CREATED_AT', "SYSDATE", false);
        
                $result = $result->set($dt)->insert($this->BMCODEDETAIL);
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

    public function deleteCurrency($Data) {
        try {
            $this->db->trans_begin();
            $result = FALSE;

            $result = $this->db->delete($this->BMCODEDETAIL, ['DETAILID' => $Data['DETAILID'], 'MASTERID' => '000001']);

            if ($result) {
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