<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('MAX_EXECUTION_TIME', '12000'); 
ini_set('max_input_time', '12000'); 

class CronKMKController extends CI_Controller {
    
    public function __construct()
        {
            parent::__construct();
            $this->load->model(array("MasterData/General/CompanyModel","BusinessUnitModel","VendorModel","MaterialModel"));
        }

    public function SetCOAManual() {
        ini_set('display_errors', 'On');
        // result
        echo "<pre>";
        // echo json_encode($dataJson['d']['results'],true);
        // var_dump('test'); exit;
        $this->db->trans_begin();
        try {
            $dateStart = Date('2023-07-01');
            $onTrack = true ;
            $today = new DateTime();
            $this->db->truncate('FAGLFLEXA_REC');
            while($onTrack) {
                    $i_dateStart = new DateTime($dateStart) ;
                    $i_dateEnd = new DateTime($dateStart) ;
                    $i_dateEnd = $i_dateEnd->modify('+6 days');
                    // var_dump($i_dateEnd); exit ;
                    $nextday = $i_dateEnd > $today ? $today : $i_dateEnd ;
                    $dateStart = $i_dateStart->format('Y-m-d') ;
                    $dateEnd = $nextday->format('Y-m-d');
                    var_dump($dateEnd ,$dateStart); 
                    $PERIODFROM = $dateStart.'T'.'00:00:00' ;
                    $PERIODTO = $dateEnd.'T'.'00:00:00' ;
                    $onTrack = $nextday < $today ? true : false ;
                    $dateStart = new DateTime($dateEnd);
                    $dateStart = $nextday->modify('+1 days')->format('Y-m-d');
                    // ini_set('memory_limit', '512M');
                    // $username = '62227217124';
                    // $password = 'KpnS4p#01';
                    // $where = "";
                    $username = 'KPN-IT-INV';
                    $password = 'Kpn@2022';
                    // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    // $url = "http://erpdev-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(AccountNumber%20eq%20%270010010081%27)&$"."format=json";
                    var_dump($url); 
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //timeout after 30 seconds
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
                    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                    $result = curl_exec ($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
                    $timeconnect = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
                    // echo $ch ;
                    // var_dump($timeconnect); 
                    if(curl_errno($ch))
                    {
                        echo 'Curl error: ' . curl_error($ch). curl_errno($ch);
                        throw new Exception(curl_error($ch)) ;
                    }
                    curl_close ($ch);
                    // echo $status_code ;
                    // print_r($result);
                    // var_dump($result); 
                    $dataJson = json_decode($result, true);
                    // print_r($dataJson);
                    // exit;
                    // var_dump($dataJson); 
                    // $result = $this->DlapModel->DataUpload($dataJson['d']['results']);
                    // exit;
                    $inputdt = $this->DataInputFAGLFLEXA($dataJson['d']['results'], $newData);
                    // var_dump($dataJson);
                    if($inputdt['STATUS'] == FALSE) {
                        throw new Exception($inputdt['MESSAGE']) ;
                    }
                    else {
                        $result = true ;
                    }
                    // var_dump($result);
            }
            if ($result) {
                $this->db->trans_commit();
                $result = [
                    'status' => 200,
                    'data' => $result
                ];
            } else {
                $this->db->trans_rollback();
                $result = [
                    'status' => 500,
                    'data' => $result
                ];
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $result = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        // echo "<pre>";
        // exit ;
        print_r($result);
        $this->db->close();
        return $result ;
    }

    public function SaveDataCOA() {
        ini_set('display_errors', 'On');
        // result
        echo "<pre>";
        // echo json_encode($dataJson['d']['results'],true);
        // var_dump('test'); exit;
        $this->db->trans_begin();
        try {
            $return = [];
            // $PERIODFROM = '2020-01-01T00:00:00' ;
            // $PERIODTO = '2020-04-01T00:00:00';
            $lastdataRKq = "SELECT TO_CHAR(MAX(POSTING_DATE), 'yyyy-fmmm-fmdd') AS MAX_DATE, TO_CHAR(MIN(POSTING_DATE), 'yyyy-fmmm-fmdd') AS MIN_DATE  FROM FAGLFLEXA_REC FREC ";
            $lastdataRK = $this->db->query($lastdataRKq)->row();
            $newData = true ;
            $lastDay = $lastdataRK->MAX_DATE ;
            // $lastDay = $lastdataRK->MAX_DATE ? $lastdataRK->MAX_DATE : Date('2021-1-1') ;
            if($lastdataRK->MAX_DATE == null) {
                $lastDay = new DateTime() ;
                $lastDay = $lastDay->modify('-30 days') ;
                // var_dump($lastDay); exit;
                $today = new DateTime();
                $onTrack = $lastDay <= $today ? true : false ;
            }
            else {
                $maxDate = new DateTime($lastdataRK->MAX_DATE) ;
                $minDate = new DateTime($lastdataRK->MIN_DATE) ;
                $lastDay = $maxDate ;
                $today = new DateTime();
                // var_dump($today); exit;
                $differ = intval(date_diff($maxDate, $today)->format('%d'));
                // var_dump($differ); exit;
                if($differ == 0) {
                    $result = 'Data is Up to date' ;
                    $onTrack = false ;
                }
                else {
                    $i = 1 ;
                    $differ = intval(date_diff($maxDate, $minDate)->format('%d'));
                    if($differ != 30) {
                        while($i <= $differ) {
                            $res_mindate = $minDate->format('Y-m-d');
                            $q = "DELETE FROM FAGLFLEXA_REC WHERE POSTING_DATE = TO_DATE('{$res_mindate}', 'yyyy-mm-dd')" ;
                            $deleteData = $this->db->query($q);
                            if(!$deleteData) {
                                throw new Exception ('Failed') ;
                            }
                            $minDate = $minDate->modify('+1 days');
                            $i++;
                        }
                    }    
                    $onTrack = $lastDay <= $today ? true : false ;
                }
            }

            $dateStart = $lastDay->format('Y-m-d');
            while($onTrack) {
                    $i_dateStart = new DateTime($dateStart) ;
                    $i_dateEnd = new DateTime($dateStart) ;
                    $i_dateEnd = $i_dateEnd->modify('+6 days');
                    // var_dump($i_dateEnd); exit ;
                    $nextday = $i_dateEnd > $today ? $today : $i_dateEnd ;
                    $dateStart = $i_dateStart->format('Y-m-d') ;
                    $dateEnd = $nextday->format('Y-m-d');
                    $PERIODFROM = $dateStart.'T'.'00:00:00' ;
                    $PERIODTO = $dateEnd.'T'.'00:00:00' ;
                    var_dump("Date Range: $dateStart - $dateEnd"); 
                    $onTrack = $nextday < $today ? true : false ;
                    $dateStart = new DateTime($dateEnd);
                    $dateStart = $nextday->modify('+1 days')->format('Y-m-d');
                    // ini_set('memory_limit', '512M');
                    // $username = '62227217124';
                    // $password = 'KpnS4p#01';
                    // $where = "";
                    $username = 'KPN-IT-INV';
                    $password = 'Kpn@2022';
                    // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    // $url = "http://erpdev-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(AccountNumber%20eq%20%270010010081%27)&$"."format=json";
                    // var_dump($url); exit;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //timeout after 30 seconds
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
                    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                    $result = curl_exec ($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
                    $timeconnect = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
                    // echo $ch ;
                    // var_dump($timeconnect); 
                    if(curl_errno($ch))
                    {
                        echo 'Curl error: ' . curl_error($ch). curl_errno($ch);
                        throw new Exception(curl_error($ch)) ;
                    }
                    curl_close ($ch);
                    // echo $status_code ;
                    // print_r($result);
                    // var_dump($result); 
                    $dataJson = json_decode($result, true);
                    // print_r($dataJson);
                    // exit;
                    // var_dump($dataJson); 
                    // $result = $this->DlapModel->DataUpload($dataJson['d']['results']);
                    // exit;
                    $inputdt = $this->DataInputFAGLFLEXA($dataJson['d']['results'], $newData);
                    // var_dump($dataJson);
                    if($inputdt['STATUS'] == FALSE) {
                        throw new Exception($inputdt['MESSAGE']) ;
                    }
                    else {
                        $result = true ;
                    }
                    // var_dump($result);
            }
            if ($result) {
                $this->db->trans_commit();
                $result = [
                    'status' => 200,
                    'data' => $result
                ];
            } else {
                $this->db->trans_rollback();
                $result = [
                    'status' => 500,
                    'data' => $result
                ];
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $result = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        // echo "<pre>";
        // exit ;
        print_r($result);
        $this->db->close();
        return $result ;

    }

    public function DataInputFAGLFLEXA($param, &$newData) {
        try {
            // var_dump($param); exit;
            $result = FALSE ;
            $data = [];
            $UUID       = $this->uuid->v4();
            $EXTSYSTEM  = 'SAPHANA';
            $DOCTYPE    = 'FAGLFLEXA';
            $USERNAME   = "ERPKPN";
            $idx        = 0;
            $exist = $this->db->select('*')->where('CREATED_BY','NEWAPI_RK')->from('TEMP_FAGLFLEXA')->get()->result(); 
            if($exist != null) {
                $this->db->where('CREATED_BY','NEWAPI_RK');
                $this->db->delete('TEMP_FAGLFLEXA');
            }
            $counter = 0 ;
            foreach ($param as $value) {
                $status = 0 ;
                $skipAddDate = false ;
                $dt = [
                    'ID'    => $idx,
                    'YEAR'  => $value['Year'],
                    'COMPANYCODE'=> $value['CompanyCode'],
                    'ACCOUNT_NUMBER' => $value['AccountNumber'],
                    'AMOUNT_TSL' => $value['AmountTsl'],
                    'AMOUNT_HSL' => $value['AmountHsl'],
                    'DEBITCREDIT_IND' => $value['DebitcreditInd'],
                    'CURRENCY' => $value['Currency'],
                    'CREATED_BY' => 'NEWAPI_RK'
                ] ;
                if ($value['PostdateShow'] != NULL || $value['PostdateShow'] != '') {
                    $tahun  = substr($value['PostdateShow'],0,4);
                    $bulan  = substr($value['PostdateShow'],4,2);
                    $hari   = substr($value['PostdateShow'],6,2);

                    $POSTING_DATE = $bulan . "/" . $hari . "/" . $tahun;
                    if($value['PostingDate'] == '' || $value['PostingDate'] == null ) {
                        $skipAddDate = true ;
                        $counter++;
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "POSTING_DATE|EMP";
                    }
                    if (!is_numeric($tahun) || !is_numeric($hari) || !is_numeric($bulan) || $bulan > 12) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "POSTING_DATE|INVAL";
                    }
                }
                if ($dt['AMOUNT_TSL'] != NULL && $dt['AMOUNT_TSL'] != '') {
                    if(!is_numeric($dt['AMOUNT_TSL'])) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "AMOUNT_TSL|INVAL" ;
                    }
                }
                if ($dt['AMOUNT_HSL'] != NULL && $dt['AMOUNT_HSL'] != '') {
                    if(!is_numeric($dt['AMOUNT_HSL'])) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "AMOUNT_HSL|INVAL" ;
                    }
                }
                if ($dt['CURRENCY'] == NULL || $dt['CURRENCY'] == '') {
                    $dt['STATUSD'] = 1;
                    $dt['MESSAGED'] = "CURRENCY|EMP" ;
                }
                if ($dt['DEBITCREDIT_IND'] == NULL || $dt['DEBITCREDIT_IND'] == '') {
                    $dt['STATUSD'] = 1;
                    $dt['MESSAGED'] = "DEBITCREDIT_IND|EMP" ;
                }
                $data[] = $dt ;
                // var_dump($dt); exit; 
                // $this->db->flush_cashe();
                if(!$skipAddDate) {
                    $result = $this->db->set('POSTING_DATE', "TO_DATE('".$POSTING_DATE."', 'MM/DD/YYYY')", false);
                    $skipAddDate = false ;
                }
                $result = $this->db->insert('TEMP_FAGLFLEXA', $dt) ;
                $idx++;
                if(!$result) {
                    break ;
                }
            }
            // var_dump($counter);exit;
            $this->db->flush_cache();
            if($result) {
                // $this->CheckValidationFAGLFLEXA($EXTSYSTEM, $UUID, $USERNAME);
                $this->db->trans_commit();
                //input to FAGLFLEXA_REC;
                $insertRec = $this->RecInputDataFAGLFLEXA($newData);
                if($insertRec['STATUS'] == true) {
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => 'Success'
                    ];
                }
                else {
                    throw new Exception ($insertRec['MESSAGE']);
                }
            }
            else {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Err'
                ];
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        return $return ;
    }

    public function CheckValidationFAGLFLEXA($EXTSYSTEM, $UUID, $USERNAME) {
        //        Update Field Company and Pesan Error
        $SQL = "UPDATE TEMP_FAGLFLEXA TUP
                    SET TUP.COMPANYCODE = (SELECT c.ID
                                        FROM COMPANY C 
                                        INNER JOIN COMPANY_EXTSYS CE
                                                ON CE.COMPANY = C.ID
                                            AND CE.EXTSYSTEM = ?
                                        WHERE C.ISACTIVE = 1 
                                        AND CE.EXTSYSCOMPANYCODE = TUP.COMPANYCODE)
                                        WHERE TUP.STATUSD <> 1 
                                            AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE TEMP_FAGLFLEXA TUP 
                    SET TUP.STATUSD = 1,
                        TUP.MESSAGED = 'Company Not Found !!!'
                WHERE TUP.STATUSD <> 1 
                    AND TUP.COMPANYCODE IS NULL
                    AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

    }

    public function RecInputDataFAGLFLEXA (&$newData) {
        try {
            $lookTempq = "SELECT tff.*, cex.COMPANY
                                FROM temp_faglflexa tff
                                    LEFT JOIN company_extsys cex
                                        ON     tff.COMPANYCODE = cex.EXTSYSCOMPANYCODE
                                        AND cex.extsystem = 'SAPHANA'";
            $lookTemp = $this->db->query($lookTempq)->result();
            $checkLatestTempq = "SELECT MAX(POSTING_DATE) AS MAX_DATE from ({$lookTempq})"; 
            $checkLatestTemp = $this->db->query($checkLatestTempq)->row() ;
            $checkLatestRec = $this->db->select('MAX(POSTING_DATE) AS MAX_DATE')->from('FAGLFLEXA_REC')->get()->row();
            $latestTemp = new DateTime($checkLatestTemp->MAX_DATE) ;
            $latestRec = new DateTime($checkLatestRec->MAX_DATE);
            if($latestTemp == $latestRec) {
                return [
                    'STATUS' => TRUE,
                ] ;
            }
            // var_dump($lookTemp);
            if($lookTemp == null) {
                return [
                    'STATUS' => TRUE,
                ] ;
            }
            foreach ($lookTemp as $item) {
                $dt = [
                    'YEAR' => $item->YEAR, 
                    'COMPANYCODE' => $item->COMPANY,
                    'ACCOUNT_NUMBER' => $item->ACCOUNT_NUMBER,
                    'AMOUNT_TSL' => $item->AMOUNT_TSL,
                    'AMOUNT_HSL' => $item->AMOUNT_HSL,
                    'DEBITCREDIT_IND' => $item->DEBITCREDIT_IND,
                    'CURRENCY' => $item->CURRENCY,
                    'ID' => $item->ID,
                    'STATUS' => $item->STATUSD,
                    'CREATED_BY' => $item->CREATED_BY,
                    'POSTING_DATE' => $item->POSTING_DATE
                ] ;
                    
                $insertdt = $this->db->set($dt)->insert('FAGLFLEXA_REC');
                if(!$insertdt) {
                    throw new Exception('Data Save Failed!!!') ;
                }
            }
            if($insertdt) {
                $this->db->trans_commit();
                return [
                    'STATUS' => TRUE,
                ] ;
            }
            else {
                $this->db->trans_rollback();
                return [
                    'STATUS' => false,
                    'MESSAGE' => 'Save Failed'
                ] ;
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            return [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ] ;
        }

    }

    //VENDOR
    public function SaveDataVendor() {
        $this->db->trans_begin();
        try {
            $return = [];
            // $PERIODFROM = '2020-01-01T00:00:00' ;
            // $PERIODTO = '2020-01-01T00:00:00';

            // $username = '62227217124';
            // $password = 'KpnS4p#01';
            $where = "";
            $username = 'KPN-IT-INV';
            $password = 'Kpn@2022';
            // $where = "";
            $lastdataRKq = "SELECT TO_CHAR(MAX(POSTING_DATE), 'yyyy-fmmm-fmdd') AS MAX_DATE, TO_CHAR(MIN(POSTING_DATE), 'yyyy-fmmm-fmdd') AS MIN_DATE FROM BSIKBSAK_REC FREC ";
            $lastdataRK = $this->db->query($lastdataRKq)->row();
            $newData = true ;
            $lastDay = $lastdataRK->MAX_DATE ;
            // $lastDay = $lastdataRK->MAX_DATE ? $lastdataRK->MAX_DATE : Date('2021-1-1') ;
            if($lastdataRK->MAX_DATE == null) {
                $lastDay = new DateTime() ;
                $lastDay = $lastDay->modify('-30 days') ;
                // var_dump($lastDay); exit;
                $today = new DateTime();
                $onTrack = $lastDay <= $today ? true : false ;
            }
            else {
                $maxDate = new DateTime($lastdataRK->MAX_DATE) ;
                $minDate = new DateTime($lastdataRK->MIN_DATE) ;
                $lastDay = $maxDate ;
                $today = new DateTime();
                // var_dump($today); exit;
                $differ = intval(date_diff($maxDate, $today)->format('%d'));
                // var_dump($differ); exit;
                if($differ == 0) {
                    $result = 'Data is Up to date' ;
                    $onTrack = false ;
                }
                else {
                    $i = 1 ;
                    $differ = intval(date_diff($maxDate, $minDate)->format('%d'));
                    if($differ != 30) {
                        while($i <= $differ) {
                            $res_mindate = $minDate->format('Y-m-d');
                            $q = "DELETE FROM FAGLFLEXA_REC WHERE POSTING_DATE = TO_DATE('{$res_mindate}', 'yyyy-mm-dd')" ;
                            $deleteData = $this->db->query($q);
                            if(!$deleteData) {
                                throw new Exception ('Failed') ;
                            }
                            $minDate = $minDate->modify('+1 days');
                            $i++;
                        }
                    }    
                    $onTrack = $lastDay <= $today ? true : false ;
                }
            }

            $dateStart = $lastDay->format('Y-m-d');
            while($onTrack) {
                    // var_dump($newData);
                    $i_dateStart = new DateTime($dateStart) ;
                    $i_dateEnd = new DateTime($dateStart) ;
                    $i_dateEnd = $i_dateEnd->modify('+6 days');
                    // var_dump($i_dateEnd); exit ;
                    $nextday = $i_dateEnd > $today ? $today : $i_dateEnd ;
                    $dateStart = $i_dateStart->format('Y-m-d') ;
                    $dateEnd = $nextday->format('Y-m-d');
                    $PERIODFROM = $dateStart.'T'.'00:00:00' ;
                    $PERIODTO = $dateEnd.'T'.'00:00:00' ;
                    $onTrack = $nextday < $today ? true : false ;
                    $dateStart = new DateTime($dateEnd);
                    $dateStart = $nextday->modify('+1 days')->format('Y-m-d');
                    ///sap/opu/odata/sap/ZGW_CASHFLOW_SRV/VENDBANKSet?$filter=(Fccode%20eq%20%27AT11000100%27)and(VendorBank%20eq%20%27IDR1%27)&$format=json
                    // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/VENDBANKSet?$"."filter=(Fccode%20eq%20%27AT11000100%27)and(VendorBank%20eq%20%27IDR1%27)&$"."format=json";
                    $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/VENDBANKSet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    // var_dump($url); exit;
                    // var_dump($url); exit;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 0); //timeout after 30 seconds
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
                    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                    $result = curl_exec ($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
                    if(curl_errno($ch))
                        {
                            echo 'Curl error: ' . curl_error($ch). curl_errno($ch);
                        }
                    curl_close ($ch);
                    // echo "<pre>";
                    // echo $ch ;
                    // print_r($result);
                    $dataJson = json_decode($result, true);
                    // var_dump($dataJson);
                    $inputdt = $this->DataInputBSIKBSAK($dataJson['d']['results'], $newData = false);
                    if($inputdt['STATUS'] == FALSE) {
                        throw new Exception($inputdt['MESSAGE']) ;
                    }
                    else {
                        $result = true ;
                    }
            }
            // exit;
            if ($result) {
                $result = [
                    'status' => 200,
                    'data' => $result
                ];
            } else {
                $result = [
                    'status' => 500,
                    'data' => $result
                ];
            }
        } catch (Exception $ex) {
            $result = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        echo "<pre>";
        print_r($result);
        $this->db->close();
        return $result ;
    }

    public function SaveDataVendorManual() {
        $this->db->trans_begin();
        echo "<pre>";
        try {
            $return = [];
            // $PERIODFROM = '2020-01-01T00:00:00' ;
            // $PERIODTO = '2020-01-01T00:00:00';

            // $username = '62227217124';
            // $password = 'KpnS4p#01';
            $where = "";
            $username = 'KPN-IT-INV';
            $password = 'Kpn@2022';
            // $where = "";
            $dateStart = Date('2023-07-01');
            $onTrack = true ;
            $today = new DateTime();
            $this->db->truncate('BSIKBSAK_REC');
            while($onTrack) {
                    // var_dump($newData);
                    $i_dateStart = new DateTime($dateStart) ;
                    $i_dateEnd = new DateTime($dateStart) ;
                    $i_dateEnd = $i_dateEnd->modify('+6 days');
                    // var_dump($i_dateEnd); exit ;
                    $nextday = $i_dateEnd > $today ? $today : $i_dateEnd ;
                    $dateStart = $i_dateStart->format('Y-m-d') ;
                    $dateEnd = $nextday->format('Y-m-d');
                    $PERIODFROM = $dateStart.'T'.'00:00:00' ;
                    $PERIODTO = $dateEnd.'T'.'00:00:00' ;
                    $onTrack = $nextday < $today ? true : false ;
                    $dateStart = new DateTime($dateEnd);
                    $dateStart = $nextday->modify('+1 days')->format('Y-m-d');
                    ///sap/opu/odata/sap/ZGW_CASHFLOW_SRV/VENDBANKSet?$filter=(Fccode%20eq%20%27AT11000100%27)and(VendorBank%20eq%20%27IDR1%27)&$format=json
                    // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/VENDBANKSet?$"."filter=(Fccode%20eq%20%27AT11000100%27)and(VendorBank%20eq%20%27IDR1%27)&$"."format=json";
                    $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/VENDBANKSet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
                    var_dump($url);
                    // var_dump($url); exit;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 0); //timeout after 30 seconds
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
                    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                    $result = curl_exec ($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
                    if(curl_errno($ch))
                        {
                            echo 'Curl error: ' . curl_error($ch). curl_errno($ch);
                        }
                    curl_close ($ch);
                    // echo "<pre>";
                    // echo $ch ;
                    // print_r($result);
                    $dataJson = json_decode($result, true);
                    // var_dump($dataJson);
                    if($dataJson['d']['results'] == null) {
                        $result = true ;
                        continue ;
                    }
                    $inputdt = $this->DataInputBSIKBSAK($dataJson['d']['results'], $newData);
                    // var_dump($inputdt); 
                    if($inputdt['STATUS'] == FALSE) {
                        throw new Exception($inputdt['MESSAGE']) ;
                    }
                    else {
                        $result = true ;
                    }
            }
            // exit;
            if ($result) {
                $result = [
                    'status' => 200,
                    'data' => $result
                ];
            } else {
                $result = [
                    'status' => 500,
                    'data' => $result
                ];
            }
        } catch (Exception $ex) {
            $result = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        echo "<pre>";
        print_r($result);
        $this->db->close();
        return $result ;
    }

    public function DataInputBSIKBSAK ($param, &$newData) {
        try {
            $this->db->trans_begin() ;
            $result = FALSE ;
            $data = [];
            $UUID = $this->uuid->v4();
            $EXTSYSTEM = 'SAPHANA';
            $DOCTYPE = 'BSIK&BSAK' ;
            $USERNAME = 'ERPKPN';
            $idx = 0 ;
            $exist = $this->db->select('*')->where('CREATED_BY','NEWAPI_RK')->from('TEMP_BSIKBSAK')->get()->result(); 
            if($exist != null) {
                $this->db->where('CREATED_BY','NEWAPI_RK');
                $this->db->delete('TEMP_BSIKBSAK');
            }
            $counter = 0 ;

            foreach($param as $value) {
                $status = 0 ;
                $skipAddDate = false ;
                $dt = [
                    'ID' => $idx,
                    'COMPANYCODE' => $value['CompanyCode'],
                    'FCCODE' => $value['Fccode'],
                    'CURRENCY' => $value['Currency'],
                    'AMOUNT_LOC' => $value['AmountLoc'],
                    'AMOUNT_DOC' => $value['AmountDoc'],
                    'VENDOR_BANK' => $value['VendorBank'],
                    'ACCOUNT_NUMBER' => $value['AccountNumber'],
                    'CREATED_BY' => 'NEWAPI_RK'
                ] ;

                if ($value['PostdateShow'] != NULL || $value['PostdateShow'] != '') {
                    $tahun  = substr($value['PostdateShow'],0,4);
                    $bulan  = substr($value['PostdateShow'],4,2);
                    $hari   = substr($value['PostdateShow'],6,2);

                    $POSTING_DATE = $bulan . "/" . $hari . "/" . $tahun;
                    if($value['PostingDate'] == '' || $value['PostingDate'] == null ) {
                        $skipAddDate = true ;
                        $counter++;
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Posting Date Is Empty !!!";
                    }
                    if (!is_numeric($tahun) || !is_numeric($hari) || !is_numeric($bulan) || $bulan > 12) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format Date Not Valid !!!";
                    }
                }
                if ($dt['AMOUNT_LOC'] != NULL && $dt['AMOUNT_LOC'] != '') {
                    if(!is_numeric($dt['AMOUNT_LOC'])) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format AMOUNT_LOC Not Valid !!!" ;
                    }
                }
                if ($dt['AMOUNT_DOC'] != NULL && $dt['AMOUNT_DOC'] != '') {
                    if(!is_numeric($dt['AMOUNT_DOC'])) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format AMOUNT_DOC Not Valid !!!" ;
                    }
                }
                if ($dt['CURRENCY'] == NULL || $dt['CURRENCY'] == '') {
                    $dt['STATUSD'] = 1;
                    $dt['MESSAGED'] = "Currency Cannot be Empty !!!" ;
                }
                if ($dt['VENDOR_BANK'] == NULL || $dt['VENDOR_BANK'] == '') {
                    $dt['STATUSD'] = 1;
                    $dt['MESSAGED'] = "VENDOR_BANK Cannot be Empty !!!" ;
                }

                $data[] = $dt ;
                // var_dump($dt); exit; 
                // $this->db->flush_cashe();
                if(!$skipAddDate) {
                    $result = $this->db->set('POSTING_DATE', "TO_DATE('".$POSTING_DATE."', 'MM/DD/YYYY')", false);
                    $skipAddDate = false ;
                }
                $result = $this->db->insert('TEMP_BSIKBSAK', $dt) ;
                $idx++;
                if(!$result) {
                    break ;
                }
            }
            $this->db->flush_cache();
            if($result) {
                $this->db->trans_commit();
                $vendorbank = $this->db->select('DISTINCT VENDOR_BANK', false)->from('TEMP_BSIKBSAK')->get()->result();
                // var_dump("VBANK :",  $vendorbank);
                $insertRec = $this->RecInputDataBSIKBSAK($newData);
                if($insertRec['STATUS'] == true) {
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => 'Success'
                    ];
                }
                else {
                    throw new Exception ($insertRec['MESSAGE']);
                }
            }
            else {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Err'
                ];
            }

        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        return $return ;
    }

    public function RecInputDataBSIKBSAK (&$newData) {
        try {
            // var_dump($newData);
            // $cleanTable = true ;
            // if($newData == true) {
            //     $cleanTable = $this->db->truncate('BSIKBSAK_REC');
            //     $newData = false ;
            // }
            // if(!$cleanTable) {
            //     throw new Exception('Table clean not successfull');
            // }
            $lookTempq = "SELECT bbs.*, cex.COMPANY
                                FROM temp_bsikbsak bbs
                                    LEFT JOIN company_extsys cex
                                        ON     bbs.COMPANYCODE = cex.EXTSYSCOMPANYCODE
                                        AND cex.extsystem = 'SAPHANA'";
            $lookTemp = $this->db->query($lookTempq)->result();
            // var_dump($lookTemp); exit;
            if($lookTemp == null) {
                return [
                    'STATUS' => TRUE,
                ] ;
            }
            foreach ($lookTemp as $item) {
                $dt = [
                    'ACCOUNT_NUMBER' => $item->ACCOUNT_NUMBER,
                    'COMPANYCODE' => $item->COMPANY,
                    'FCCODE' => $item->FCCODE,
                    'VENDOR_BANK' => $item->PART_BANK,
                    'AMOUNT_LOC' => $item->AMOUNT_LOC,
                    'AMOUNT_DOC' => $item->AMOUNT_DOC,
                    'CURRENCY' => $item->CURRENCY,
                    'ID' => $item->ID,
                    'STATUS' => $item->STATUSD,
                    'CREATED_BY' => $item->CREATED_BY,
                    'POSTING_DATE' => $item->POSTING_DATE
                ] ;
                    
                $insertdt = $this->db->set($dt)->insert('BSIKBSAK_REC');
                if(!$insertdt) {
                    throw new Exception('Data Save Failed!!!') ;
                }
            }
            if($insertdt) {
                $this->db->trans_commit();
                return [
                    'STATUS' => TRUE,
                ] ;
            }
            else {
                $this->db->trans_rollback();
                return [
                    'STATUS' => false,
                    'MESSAGE' => 'Save Failed'
                ] ;
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            return [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ] ;
        }

    }
    
}