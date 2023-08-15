<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('MAX_EXECUTION_TIME', '6000'); 
ini_set('max_input_time', '6000'); 

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ODataController extends BaseController {
    
    public function __construct()
        {
            parent::__construct();
            $this->load->model(array("MasterData/General/CompanyModel","BusinessUnitModel","VendorModel","MaterialModel"));
        }

    public function SaveDataCOA() {
        ini_set('display_errors', 'On');
        // result
        // echo json_encode($dataJson['d']['results'],true);
        // var_dump('test'); exit;
        try {
            $return = [];
            $PERIODFROM = '2020-01-01T00:00:00' ;
            $PERIODTO = '2020-04-01T00:00:00';
            // ini_set('memory_limit', '512M');
            $username = '62227217124';
            $password = 'KpnS4p#01';
            $where = "";
            $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
            // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(AccountNumber%20eq%20%270010010081%27)&$"."format=json";
            // var_dump($url); exit;
            $urlen = urlencode($url);
            $urlen1 = urldecode($urlen);
            // var_dump($urlen1); exit;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$urlen1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0); //timeout after 30 seconds
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            // curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            $result = curl_exec ($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
            $timeconnect = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
            curl_close ($ch);
            echo "<pre>";
            // echo $ch ;
            // var_dump($timeconnect); 
            if(curl_errno($ch))
                {
                    echo 'Curl error: ' . curl_error($ch). curl_errno($ch);
                }
            // echo $status_code ;
            // print_r($result);
            $dataJson = json_decode($result, true);
            print_r($dataJson);
            // exit;
            var_dump($dataJson);
            // $result = $this->DlapModel->DataUpload($dataJson['d']['results']);
            // exit;
            $result = $this->DataInputFAGLFLEXA($dataJson['d']['results']);
        // var_dump($result);
            if ($result) {
                $result = [
                    'status' => 200,
                    'for-ke' => $i,
                    'data' => $result
                ];
            } else {
                $result = [
                    'status' => 500,
                    'for-ke' => $i,
                    'data' => $result
                ];
            }
        } catch (Exception $ex) {
            $result = array(
                'status' => 500,
                'for-ke' => $i,
                'data' => $ex->getMessage()
            );
        }
        echo "<pre>";
        print_r($result);

    }

    public function DataInputFAGLFLEXA($param) {
        try {
            // var_dump($param); exit;
            $this->db->trans_begin();
            $result = FALSE ;
            $data = [];
            $UUID       = $this->uuid->v4();
            $EXTSYSTEM  = 'SAPHANA';
            $DOCTYPE    = 'FAGLFLEXA';
            $USERNAME   = "ERPKPN";
            $idx        = 0;
            $this->db->where('CREATED_BY','NEWAPI_RK');
            $this->db->delete('TEMP_FAGLFLEXA');
            $counter = 0 ;
            foreach ($param as $value) {
                $status = 0 ;
                $skipAddDate = false ;
                $dt = [
                    'UUID' => $UUID,
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
                        $dt['MESSAGED'] = "Posting Date Is Empty !!!";
                    }
                    if (!is_numeric($tahun) || !is_numeric($hari) || !is_numeric($bulan) || $bulan > 12) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format Date Not Valid !!!";
                    }
                }
                if ($dt['AMOUNT_TSL'] != NULL && $dt['AMOUNT_TSL'] != '') {
                    if(!is_numeric($dt['AMOUNT_TSL'])) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format AMOUNT_TSL Not Valid !!!" ;
                    }
                }
                if ($dt['AMOUNT_HSL'] != NULL && $dt['AMOUNT_HSL'] != '') {
                    if(!is_numeric($dt['AMOUNT_HSL'])) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format AMOUNT_HSL Not Valid !!!" ;
                    }
                }
                if ($dt['CURRENCY'] == NULL || $dt['CURRENCY'] == '') {
                    $dt['STATUSD'] = 1;
                    $dt['MESSAGED'] = "Currency Cannot be Empty !!!" ;
                }
                if ($dt['DEBITCREDIT_IND'] == NULL || $dt['DEBITCREDIT_IND'] == '') {
                    $dt['STATUSD'] = 1;
                    $dt['MESSAGED'] = "DEBITCREDIT_IND Cannot be Empty !!!" ;
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
            $data = json_encode($data, TRUE);
            $data = json_decode($data, TRUE);
            if($result) {
                // $this->CheckValidationFAGLFLEXA($EXTSYSTEM, $UUID, $USERNAME);
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $data
                ];
            }
            else {
                $this->db->trans_rollback();
                echo $this->db->error();
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            echo $ex->getMessage();
            echo 'error';
        }
        $this->db->close();
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

    //VENDOR
    public function SaveDataVendor() {
        try {
            $return = [];
            $PERIODFROM = '2020-01-01T00:00:00' ;
            $PERIODTO = '2020-01-01T00:00:00';

            $username = '62227217124';
            $password = 'KpnS4p#01';
            $where = "";
            ///sap/opu/odata/sap/ZGW_CASHFLOW_SRV/VENDBANKSet?$filter=(Fccode%20eq%20%27AT11000100%27)and(VendorBank%20eq%20%27IDR1%27)&$format=json
            // $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/COASet?$"."filter=(PostingDate%20ge%20datetime%27".$PERIODFROM."%27)and(PostingDate%20le%20datetime%27".$PERIODTO."%27)&$"."format=json";
            $url = "http://172.22.2.99:8000//sap/opu/odata/sap/ZGW_CASHFLOW_SRV/VENDBANKSet?$"."filter=(Fccode%20eq%20%27AT11000100%27)and(VendorBank%20eq%20%27IDR1%27)&$"."format=json";
            // var_dump($url); exit;
            $urlen = urlencode($url);
            $urlen1 = urldecode($urlen);
            // var_dump($urlen1); exit;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$urlen1);
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
            echo "<pre>";
            echo $ch ;
            // print_r($result);
            $dataJson = json_decode($result, true);
            // print_r($dataJson);
            // var_dump($dataJson);
            // $result = $this->DlapModel->DataUpload($dataJson['d']['results']);
            // exit;
            $result = $this->DataInputBSIKBSAK($dataJson['d']['results']);
            var_dump($result);
            if ($result) {
                $result = [
                    'status' => 200,
                    'for-ke' => $i,
                    'data' => $result
                ];
            } else {
                $result = [
                    'status' => 500,
                    'for-ke' => $i,
                    'data' => $result
                ];
            }
        } catch (Exception $ex) {
            $result = array(
                'status' => 500,
                'for-ke' => $i,
                'data' => $ex->getMessage()
            );
        }
        echo "<pre>";
        print_r($result);
    }

    public function DataInputBSIKBSAK ($param) {
        try {
            $this->db->trans_begin() ;
            $result = FALSE ;
            $data = [];
            $UUID = $this->uuid->v4();
            $EXTSYSTEM = 'SAPHANA';
            $DOCTYPE = 'BSIK&BSAK' ;
            $USERNAME = 'ERPKPN';
            $idx = 0 ;

            $this->db->where('CREATED_BY', 'NEWAPI_RK') ;
            $this->db->delete('TEMP_BSIKBSAK');
            $counter = 0 ;

            foreach($param as $value) {
                $status = 0 ;
                $skipAddDate = false ;
                $dt = [
                    'UUID' => $UUID,
                    'ID' => $idx,
                    'COMPANYCODE' => $value['CompanyCode'],
                    'FCCODE' => $value['Fccode'],
                    'CURRENCY' => $value['Currency'],
                    'AMOUNT_LOC' => $value['AmountLoc'],
                    'AMOUNT_DOC' => $value['AmountDoc'],
                    'VENDOR_BANK' => $value['VendorBank'],
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
            $data = json_encode($data, TRUE);
            $data = json_decode($data, TRUE);
            if($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $data
                ];
            }
            else {
                $this->db->trans_rollback();
                echo $this->db->error();
            }

        } catch (Exception $ex) {
            $this->db->trans_rollback();
            echo $ex->getMessage();
        }
        $this->db->close();
        return $return ;
    }
    
}