<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class KmkController extends BaseController {

	public function __construct()
	{
		parent::__construct();
        try {
            $this->load->model(array("MasterData/General/CompanyModel","BusinessUnitModel","VendorModel","KMKModel","MaterialModel","ReportGenModel","PayReqKMKKIModel"));
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
	}

    public function getHistoryDoc() {
        $param = $this->input->post();

        try {
            $list = $this->KMKModel->getHistoryDoc($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getHistoryDocWA() {
        $param = $this->input->post();

        try {
            $list = $this->KMKModel->getHistoryDocWA($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function ShowDataPaymentRequest() {
        // $param = $this->input->post();
        try {
            // $this->reupdateAllReportKI();
            $param = $this->input->post();
            $list = $this->KMKModel->ShowDataPaymentRequest($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function loadBusinessUnit(){
        $q = $this->input->get_post('COMPANY', true);
        $query = "SELECT ID,FCCODE as TEXT, COMPANY FROM BUSINESSUNIT WHERE COMPANY = '$q' AND FCCODE LIKE '%HO%'";
        
        $res = $this->db->query($query)->result();
        echo json_encode($res);
        // return $res->result(); 
    }

    public function getHistoryDocKI() {
        $param = $this->input->post();

        try {
            $list = $this->KMKModel->getHistoryDocKI($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

	public function ShowData() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->ShowData($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getLastBalance(){ 
        $UUID = $this->input->post('UUID');
        $q   = "SELECT AMOUNT_BALANCE FROM FUNDS_WITHDRAW WHERE UUID = '".$UUID."' ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY";
        $result = $this->db->query($q)->row();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        echo json_encode($result);
    }
    public function getLastBalanceKI(){ 
        $UUID = $this->input->post('UUID');
        $q   = "SELECT FKI.UUID,
                        FKI.TRANCHE_NUMBER,
                        CASE 
                            WHEN FWDT.BALANCE IS NULL THEN (SELECT BALANCE FROM FUNDS_WD_KI_TRANCHE WHERE TRANCHE_NUMBER = FKI.TRANCHE_NUMBER AND UUID = FKI.UUID ORDER BY  IS_PAYMENT NULLS LAST, BATCHID DESC, COUNTER DESC FETCH FIRST 1 ROWS ONLY)
                            ELSE FWDT.BALANCE
                            END
                            AS BALANCE,
                        CASE 
                            WHEN FWDT.BATCHID IS NULL THEN (SELECT BATCHID FROM FUNDS_WD_KI_TRANCHE WHERE TRANCHE_NUMBER = FKI.TRANCHE_NUMBER AND UUID = FKI.UUID ORDER BY IS_PAYMENT NULLS LAST, BATCHID DESC, COUNTER DESC FETCH FIRST 1 ROWS ONLY)
                            ELSE FWDT.BATCHID
                            END
                            AS BATCHID,
                        FKI.LIMIT_TRANCHE,
                        FWDT.DDOWN_AMT
                FROM FUNDS_DETAIL_KI_TRANCHE FKI
                        LEFT JOIN (SELECT BATCHID,
                                        BALANCE,
                                        DDOWN_AMT,
                                        TRANCHE_NUMBER,
                                        UUID,
                                        IS_PAYMENT, 
                                        COUNTER
                                    FROM FUNDS_WD_KI_TRANCHE FKIT
                                        LEFT JOIN
                                        (       SELECT ID
                                                FROM FUNDS_WD_KI
                                                WHERE UUID = '".$UUID."' AND STATUS = 1
                                            ORDER BY ID DESC
                                        FETCH FIRST 1 ROWS ONLY ) FWD
                                            ON FWD.ID = FKIT.BATCHID 
                                    WHERE FWD.ID = FKIT.BATCHID
                                    ORDER BY COUNTER DESC NULLS LAST, IS_PAYMENT NULLS LAST
                                    FETCH FIRST 1 ROWS ONLY) FWDT
                        ON FKI.TRANCHE_NUMBER = FWDT.TRANCHE_NUMBER
                WHERE     
                        FKI.UUID = '".$UUID."'
                        AND FKI.ISACTIVE = '1'
                        AND FKI.IS_COMPLETE IS NULL";
        $result = $this->db->query($q)->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        echo json_encode($result);
    }

    public function getLimitTranche(){ 
        $UUID           = $this->input->post('UUID');
        $TRANCHE_NUMBER = $this->input->post('TRANCHE_NUMBER');
        $q              = "SELECT LIMIT_TRANCHE FROM FUNDS_DETAIL_KI_TRANCHE WHERE UUID = '".$UUID."' AND TRANCHE_NUMBER = '".$TRANCHE_NUMBER."' ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY";
        $result = $this->db->query($q)->row();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        echo json_encode($result);
    }

    public function ShowDataWithdraw() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->ShowDataWithdraw($param);
            $up = $this->KMKModel->updateBalanceAfterPaid();
            // var_dump($up) ;exit;
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    // withdraw data 0 1
    public function ShowWithdrawData() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->ShowWithdrawData($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function ShowWithdrawDataKI() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->ShowWithdrawDataKI($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveWD() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveWD($param, $this->GetIpAddress());
            // var_dump($return);exit;
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => array($return['IDS'],$return['BATCHID'],$return['MESSAGE']),
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }
    
    public function SaveWDKI() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveWDKI($param, $this->GetIpAddress());
            // var_dump($return);exit;
            if ($return['STATUS'] == true) {
                    // $parUp = [
                    //     'UUID' =>$param['UUID'],
                    //     'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER']
                    // ] ;
                    // $updateWD = $this->KMKModel->updateInstallmentByWD($parUp);
                    // $return['ISTUP'] = $updateWD['MESSAGE'] ;
                    $this->resource = [
                        'status' => 200,
                        'data' => [$return['MESSAGE'], $return['IDS']]
                    ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveWDKIDet() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveWDKIDet($param, $this->GetIpAddress());
            $parSav = [
                'PK_NUMBER' => $param['PK_NUMBER'],
                'UUID'      => $param['UUID']
            ];
            $return1 = $this->ReportGenModel->SaveReportKI($parSav, $this->GetIpAddress());
            // var_dump($return1); exit;
            if ($return['STATUS'] == TRUE) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function Save() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->Save($param, $this->GetIpAddress());
            // var_dump($return);exit;
            // if ($return) {
                if($return['STATUS'] == true){
                    $this->resource = [
                        'status' => 200,
                        'data' => $return['IDS']
                    ];
                }elseif($return['STATUS'] == false){
                    $this->resource = [
                        'status' => 504,
                        'data' => $return['MESSAGE']
                    ];
                }
             else {
                throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveWA() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveWA($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveRK() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveRK($param, $this->GetIpAddress());
            $parSav = [
                'PK_NUMBER' => $param['PK_NUMBER'],
                'CONTRACT_NUMBER' => $return['CONTRACT_NUMBER'],
                'UUID' => $param['UUID']
            ] ;
            $createTempReport = $this->ReportGenModel->SaveReportKMK($parSav, $this->GetIpAddress());
            // var_dump($createTempReport); exit;
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveKI() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $parSav1 = [
                'UUID' => $param['UUID'],
                'RATE' => $param['RATE']
            ];
            $parSav = [
                'PK_NUMBER' => $param['PK_NUMBER'],
                'UUID'      => $param['UUID']
            ];
            
            $return = $this->KMKModel->SaveKI($param, $this->GetIpAddress());
            // $checkCurrDiffq = "SELECT * FROM FUNDS_DETAIL_KI WHERE UUID = '".$param['UUID']."' AND ISACTIVE = 0 ORDER BY ID FETCH FIRST 1 ROWS ONLY" ;
            // $checkCurrDiff = $this->db->query($checkCurrDiffq)->row();
            // var_dump($checkCurrDiff); exit;
            // if($checkCurrDiff != null) {
            //     if ($checkCurrDiff->CURRENCY != $param['CURRENCY']) {
            //         $return1 = $this->KMKModel->updateLimitBalance($parSav1);
            //     }
            // }
            if($param['CTYPE'] == 'SINGLE') {
                $this->ReportGenModel->SaveReportKI($parSav, $this->GetIpAddress());
            }
            else if ($param['CTYPE'] == 'SYNDICATION') {
                // var_dump($param); exit;
                $this->ReportGenModel->SaveReportKI($parSav, $this->GetIpAddress());
                $this->ReportGenModel->SaveReportKI_SYD($parSav, $this->GetIpAddress());
                // var_dump($test); exit;
            }
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveKITranche() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveKITranche($param, $this->GetIpAddress());
            if($return['STATUS'] == TRUE) {
                $this->resource = [
                    'status' => 200,
                    'data' => [$return['MESSAGE']."\n".$return['MESSAGE2'], $return]
                ];
            } else {
                $this->resource = [
                    'status' => 500,
                    'data' => $return['MESSAGE']."\n".$return['MESSAGE2']
                ];
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function uploadWDFile() {
        
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->uploadWDFile($param,$this->GetIpAddress());
            // echo "<pre>";
            // var_dump($param);exit;
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function uploadFile() {
        
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->uploadFile($param,$this->GetIpAddress());
            // echo "<pre>";
            // var_dump($param);exit;
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function multiUploadFile() {
        
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->multiUploadFile($param,$this->GetIpAddress());
            // echo "<pre>";
            // var_dump($param);exit;
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }


    public function HeaderDetailWA() {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->HeaderDetailWA($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function HeaderDetailRK() {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->HeaderDetailRK($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function HeaderDetailKI() {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->HeaderDetailKI($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function WDDetailKI() {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->WDDetailKI($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteDetailWA(){
        $param = $this->input->post();
        try {
                $this->db->where('ID',$param['ID']);
                $isDeleted = $this->db->delete('FUNDS_DETAIL_WA');
                if($isDeleted){
                    $res = "Data Deleted";
                }else{
                    $res = "Delete Error";
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteDetailKI(){
        $param = $this->input->post();
        try {
                $this->db->where('ID',$param['ID']);
                $isDeleted = $this->db->delete('FUNDS_DETAIL_KI_TRANCHE');
                if($isDeleted){
                    $res = "Data Deleted";
                }else{
                    $res = "Delete Error";
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

     public function DeleteFundsDetKI(){
        $param = $this->input->post();
        try {
                $this->db->where('ID',$param['ID']);
                $isDeleted = $this->db->delete('FUNDS_WD_KI_DETAIL');
                //update data
                $latestDdownamt = $this->db->select('DDOWN_AMT, TRANCHE_NUMBER, BATCHID')
                ->from('FUNDS_WD_KI_TRANCHE') 
                ->where(array('TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'], 'BATCHID' => $param['IDHEADER']))->get()->row() ;
                // var_dump($this->db->last_query()); exit;
                // update data (plus or minus)
                $newDdownamt = intval($latestDdownamt->DDOWN_AMT) - intval($param['AMTMODALS']) ;
                $trancheUp = $this->db->set('CREATED_AT', 'SYSDATE', false)
                ->set('CREATED_BY', $param['USERNAME'])
                ->set('DDOWN_AMT', $newDdownamt)
                ->where(array('TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'], 'BATCHID' => $param['IDHEADER']))
                ->update('FUNDS_WD_KI_TRANCHE');
                //add drawdown val to header
                $totalDrawdownVal = $this->db->query('SELECT sum(DDOWN_AMT) as DDOWN_AMT FROM FUNDS_WD_KI_TRANCHE WHERE BATCHID = ?', $param['IDHEADER'])->row();
                // var_dump($totalDrawdownVal); exit;
                $headerUp = $this->db->set('DRAWDOWN_VALUE', $totalDrawdownVal->DDOWN_AMT)
                        ->set('CREATED_AT', 'SYSDATE', false)
                        ->set('CREATED_BY', $param['USERNAME'])
                        ->where('ID', $param['IDHEADER'])
                        ->update('FUNDS_WD_KI') ;
                // var_dump($this->db->last_query());exit;
                if($isDeleted && $trancheUp && $headerUp){
                    $path_to_file = ROOT.$param['FILENAME'];
                    if(file_exists($path_to_file)){
                        unlink($path_to_file);    
                    }
                    
                    $res = "Data Deleted";
                }else{
                    $res = "Delete Error";
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteDetailRK(){
        $param = $this->input->post();
        try {
                $this->db->where('ID',$param['ID']);
                $isDeleted = $this->db->delete('FUNDS_DETAIL_RK');
                if($isDeleted){
                    $res = "Data Deleted";
                }else{
                    $res = "Delete Error";
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function ShowFileData() {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->ShowFileData($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteFile(){
        $param = $this->input->post();
        try {
            $path_to_file = ROOT.$param['FILENAME'];
            // var_dump($path_to_file);exit;
            if ( file_exists($path_to_file) ){
                if( unlink($path_to_file) ) {
                    $this->db->where('ID',$param['ID']);
                    if($param['SUB_CREDIT_TYPE'] == "TL"){
                        $this->db->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE']);
                        $isDeleted = $this->db->delete('FUNDS_WD_FILE');
                    }
                    if($param['SUB_CREDIT_TYPE'] == "WA"){
                        $this->db->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE']);
                        $isDeleted = $this->db->delete('FUNDS_WD_FILE');
                    }
                    if($param['SUB_CREDIT_TYPE'] == "KI"){
                        $this->db->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE']);
                        $isDeleted = $this->db->delete('FUNDS_WD_FILE');
                    }
                    elseif($param['SUB_CREDIT_TYPE'] == '' || $param['SUB_CREDIT_TYPE'] == null){
                        $isDeleted = $this->db->delete('FUNDS_FILE');
                    }
                    
                    if($isDeleted){
                        $res = "Data Deleted";
                    }
                }else{
                    $res = "Delete Error";
                }
            }
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DtBankCompany() {
        try {
            $param = $_POST;
            $list = [];
            if ($param["COMPANY"] != NULL && $param["COMPANY"] != "") {
                $list = $this->KMKModel->DtBankCompany($param);
            }
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DtBankCompanyKI() {
        try {
            $param = $_POST;
            $list = [];
            if ($param["COMPANY"] != NULL && $param["COMPANY"] != "") {
                $list = $this->KMKModel->DtBankCompanyKI($param);
            }
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }


    public function SaveWDNDK() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            // var_dump($report) ; exit;
            $return = $this->KMKModel->SaveWDNonDiskonto($param, $this->GetIpAddress());
            $report = $this->ReportGenModel->SaveReportKMK($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this -> resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $e) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex-> getMessage()
            );
        }
        $this->SendResponse();
    }

    public function GetDataWDNonDiskonto() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataWDNonDiskonto($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteFileWD(){
        $param = $this->input->post();
        try {
            $path_to_file = ROOT.$param['FILENAME'];
            // var_dump($path_to_file);exit;
            if ( file_exists($path_to_file) ){
                if( unlink($path_to_file) ) {
                    $this->db->where('ID',$param['ID']);
                    $isDeleted = $this->db->delete('FUNDS_WD_FILE');
                    if($isDeleted){
                        $res = "Data Deleted";
                    }
                }else{
                    $res = "Delete Error";
                }
            }
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteWDNDK(){
        $param = $this->input->post();
        try {
                // UPDATE : delete data, will update FUNDS_WITHDRAW and WD_FUNDS_NDK
                $isAmtUpdated   = $this->KMKModel->DeleteWDNDK($param);
                //
                $this->db->where('ID',$param['ID']);
                $isDeleted = $this->db->delete('WD_FUNDS_NONDISKONTO');
                if($isDeleted && $isAmtUpdated){
                    $path_to_file = ROOT.$param['FILENAME'];
                    if(file_exists($path_to_file)){
                        unlink($path_to_file);    
                    }
                    
                    $res = "Data Deleted";
                }else{
                    $res = "Delete Error";
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteMaster(){
        $param = $this->input->post();
        $this->db->trans_begin();
        $q = "SELECT COUNT(*) AS COUNT FROM FUNDS_DETAIL_KI WHERE UUID = '".$param['UUID']."'" ;
        $countdata = intval($this->db->query($q)->row()->COUNT); 
        // var_dump($countdata) ; exit;
        try {
                if($countdata > 1) {
                    $this->db->where('UUID',$param['UUID']);
                    $isDeleted = $this->db->set('ISACTIVE', NULL)->update('FUNDS_MASTER');
                } else {
                    $checkapprove = $this->db->query("SELECT IS_ACC FROM FUNDS_DETAIL_KI WHERE UUID = '".$param['UUID']."' ORDER BY COUNTER DESC")->row()->IS_ACC;
                    $this->db->where('UUID',$param['UUID']);
                    if($checkapprove == null) {
                        $isDeleted = $this->db->delete('FUNDS_MASTER');
                    }
                    else if ($checkapprove != '1') {
                        $isDeleted = $this->db->set('ISACTIVE', NULL)->update('FUNDS_MASTER');
                    }
                    else {
                        $isDeleted = $this->db->set('ISACTIVE', NULL)->update('FUNDS_MASTER');
                    }
                }
                if($isDeleted){
                    $this->db->trans_commit();
                    $res = "Data Deleted";
                }else{
                    $this->db->trans_rollback();
                    $res = "Delete Error";
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteAR(){
        $param = $this->input->post();
        try {
                //update amount in FUNDS_WITHDRAW when data from FUNDS_WDDETAIL_SCFAR deleted
                $isAmtUpdated = $this->KMKModel->DeleteAR($param);
                //
                // var_dump($isAmtUpdated); exit;
                $this->db->where('ID',$param['ID']);
                $isDeleted = $this->db->delete('FUNDS_WDDETAIL_SCFAR');
                if($isDeleted && $isAmtUpdated){
                    $path_to_file = ROOT.$param['FILENAME'];
                    if(file_exists($path_to_file)){
                        unlink($path_to_file);    
                    }
                    
                    $res = "Data Deleted";
                }else{
                    $res = "Delete Error";
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function GetDataWDAR() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataWDAR($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function GetDataWDARDet() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataWDARDet($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveWDARDet() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveWDARDet($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this -> resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                $this->resource = array(
                    'status' => 500,
                    'data' => $return['MESSAGE']
                );
            }
        } catch (Exception $e) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex-> getMessage()
            );
        }
        $this->SendResponse();
    }

    public function GetDataWDAP() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataWDAP($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function GetDataWDAPDet() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataWDAPDet($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveWDAPDet() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveWDAPDet($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this -> resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                $this->resource = array(
                    'status' => 500,
                    'data' =>$return['MESSAGE']
                );
            }
        } catch (Exception $e) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex-> getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteAP(){
        $param = $this->input->post();
        try {
                //update amount in FUNDS_WITHDRAW when data from FUNDS_WDDETAIL_SCFAR deleted
                $isAmtUpdated = $this->KMKModel->DeleteAP($param);
                //
                // var_dump($isAmtUpdated); exit;
                $this->db->where('ID',$param['ID']);
                $isDeleted = $this->db->delete('FUNDS_WDDETAIL_SCFAP');
                if($isDeleted && $isAmtUpdated){
                    $path_to_file = ROOT.$param['FILENAME'];
                    if(file_exists($path_to_file)){
                        unlink($path_to_file);    
                    }
                    
                    $res = "Data Deleted";
                }else{
                    $res = "Delete Error";
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getVendorAP(){
        $param = $this->input->post();
        try{
            $list = $this->KMKModel->getVendorAP($param);
            $this->resource = array(
                'status' => 200,
                'data'   => $list
            );
        } catch (Exception $e) {
            $this->resource = array(
                'status'    => 500,
                'data'      => $e->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveWDAPAR() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveWDAPAR($param, $this->GetIpAddress());
            // var_dump($return);exit;
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => array($return['IDS'],$return['BATCHID']),
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    // WITHDRAW DATA
    public function SaveWDbyId() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->SaveWDbyId($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }
    // END WITHDRAW DATA

    // approval
    public function showApprovalContract() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->showApprovalContract($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function showApprovalWithdrawal() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->showApprovalWithdrawal($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function showApprovalWithdrawalKI() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->showApprovalWithdrawalKI($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function acceptContract(){
        $param = $this->input->post();
        // echo "<pre>";
        // var_dump($param);exit;
        try {
                $this->db->trans_begin();
                if($param['STATUS'] == '1'){
                    $this->db->set('IS_ACC','1');
                    $this->db->set('CREATED_BY',$param['USERNAME']);
                    $this->db->set('CREATED_AT', "SYSDATE", false);
                    $this->db->where(array('UUID'=>$param['UUID'], 'ISACTIVE' => 1));
                    $last = $this->db->update('FUNDS_MASTER');

                    if($param['SUB_CREDIT_TYPE'] == 'RK' || $param['SUB_CREDIT_TYPE'] == 'BD' || $param['SUB_CREDIT_TYPE'] == 'TL'){
                        $this->db->set('IS_ACC','1');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where('UUID',$param['UUID']);
                        $res = $this->db->update('FUNDS_DETAIL_RK');
                    }
                    if($param['SUB_CREDIT_TYPE'] == 'WA'){
                        $this->db->set('IS_ACC','1');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where('UUID',$param['UUID']);
                        $res = $this->db->update('FUNDS_DETAIL_WA');
                    }
                    if($param['SUB_CREDIT_TYPE'] == 'KI' ){
                        $this->db->set('IS_ACC','1');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where(array('UUID'=>$param['UUID'], 'ISACTIVE' => 1));
                        $res = $this->db->update('FUNDS_DETAIL_KI');
                        $this->db->set('IS_ACC','1');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where(array('UUID'=>$param['UUID'], 'ISACTIVE' => 1));
                        $res = $this->db->update('FUNDS_DETAIL_KI_TRANCHE');
                    }
                }else{
                    
                    $this->db->set('IS_ACC','2');
                    $this->db->set('CREATED_BY',$param['USERNAME']);
                    $this->db->set('CREATED_AT', "SYSDATE", false);
                    $this->db->where('UUID',$param['UUID']);
                    $last = $this->db->update('FUNDS_MASTER');
                    
                    if($param['SUB_CREDIT_TYPE'] == 'RK' || $param['SUB_CREDIT_TYPE'] == 'BD' || $param['SUB_CREDIT_TYPE'] == 'TL'){
                        $this->db->set('IS_ACC','2');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where('UUID',$param['UUID']);
                        $res = $this->db->update('FUNDS_DETAIL_RK');
                    }
                    if($param['SUB_CREDIT_TYPE'] == 'WA'){
                        $this->db->set('IS_ACC','2');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where('UUID',$param['UUID']);
                        $res = $this->db->update('FUNDS_DETAIL_WA');
                    }
                    if($param['SUB_CREDIT_TYPE'] == 'FINANCING' || $param['SUB_CREDIT_TYPE'] == 'REFINANCING'){
                        $this->db->set('IS_ACC','2');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where('UUID',$param['UUID']);
                        $res = $this->db->update('FUNDS_DETAIL_KI');
                        $this->db->set('IS_ACC','2');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where('UUID',$param['UUID']);
                        $res = $this->db->update('FUNDS_DETAIL_KI_TRANCHE');
                    }
                }

                if($last && $res){
                    $res = "Data Updated";
                    $this->db->trans_commit();
                }
                else{
                    $res = "Accept Error";
                    $this->db->trans_rollback();
                }
            
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function acceptWD() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->acceptWD($param,$this->GetIpAddress());
            $this->resource = array(
                'status' => 200,
                'data' => [$list, $resultUpdTrc]
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    //Get data WD NonDiskonto based on value date
    public function GetDataWDNDK() {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataWDNDK($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }
    // 
    //Modify from GetDataWDbyID
    public function GetDataWDNDKbyID() {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataWDNDKbyID($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }
    public function acceptWDKI() {
        $param = $this->input->post();
        try {
            if($param['STATUS'] == '1') {
                $tranches = $this->db->query("SELECT TRANCHE_NUMBER FROM FUNDS_DETAIL_KI_TRANCHE WHERE UUID = '{$param['UUID']}' AND ISACTIVE = '1'")->result();
                //         //update balance KI 
                // $q1 = " SELECT 
                //             UUID, TRANCHE_NUMBER, LIMIT_TRANCHE
                //         FROM FUNDS_DETAIL_KI_TRANCHE  
                //             WHERE UUID = ? " ;
                // $q2 = " SELECT
                //             UUID, DRAWDOWN_VALUE
                //         FROM FUNDS_WD_KI
                //             WHERE UUID = ? ";
                // $data_limit = $this->db->query($q1, array($param['UUID']))->result();
                // $total_ki_limit = 0;
                // $drawdown = $this->db->query($q2, array($param['UUID']))->result();
                // $total_drawdown = 0 ;
                // for ($index = 0 ; $index < count($data_limit) ; $index++) {
                //     $total_ki_limit += (int) $data_limit[$index]->LIMIT_TRANCHE ;
                // }
                // for ($index = 0 ; $index < count($drawdown) ; $index++) {
                //     $total_drawdown += (int) $drawdown[$index]->DRAWDOWN_VALUE ;
                // }
                // $updateBalance = $total_ki_limit - $total_drawdown ;
                // $dt = [
                //     'BALANCE'       => $updateBalance,
                //     'STATUS'        => 1,
                //     'CREATED_BY'    => $param['USERNAME']
                // ] ;
                // $last = $this->db->set('CREATED_AT','SYSDATE',false);
                // $last = $this->db->set($dt)->where("UUID = '".$param['UUID']."' AND ID = '".$param['ID']."'")->update('FUNDS_WD_KI');
                // // var_dump($this->db->last_query()); exit;
                // //
                try {
                    foreach($tranches as $tranche) {
                        $parUp = [
                            'UUID' => $param['UUID'],
                            'TRANCHE_NUMBER' => $tranche->TRANCHE_NUMBER
                        ] ;
                        // var_dump($parUp);
                        $updTrc = $this->KMKModel->updateInstallmentByWD($parUp) ;
                        // var_dump($updTrc) ; 
                        $resultUpdTrc[$tranche->TRANCHE_NUMBER] = $updTrc['MESSAGE'] ;
                    }
                    // var_dump($resultUpdTrc);
                    // exit ;
                    $list = $this->KMKModel->acceptWDKI($param,$this->GetIpAddress());
                    $this->resource = array(
                        'status' => 200,
                        'data' => [$list, $resultUpdTrc]
                    );
                } catch (Exception $ex) {
                    $this->resource = array(
                        'status' => 500,
                        'data' => $ex->getMessage()
                    );
                }
            } 
            else if ($param['STATUS'] == '2') {
                $this->db->set('STATUS','2');
                $this->db->set('CREATED_BY',$param['USERNAME']);
                $this->db->set('CREATED_AT', "SYSDATE", false);
                $this->db->where(array('UUID' => $param['UUID'], 'ID' => $param['ID']));
                $res = $this->db->update('FUNDS_WD_KI');
                // var_dump($res); exit;   
                if ($res) {
                    $this->resource = array(
                        'status' => 200,
                        'data' => [
                            'MESSAGE' => 'data declined'
                        ]
                    );
            }
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }
    //
    public function GetDataWDAPARbyID() {

        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataWDAPARbyID($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function savePaymentReq() {
        $param = $this->input->post();
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            if($param['COMPANY'] == null){
                throw new Exception('Filter Tidak Boleh Kosong');
            }
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PayReqKMKKIModel->savePaymentReqKI($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getDataLastBalanceRK () {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->getDataLastBalanceRK($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function saveBankKI () {
        $param = $this->input->post();
        // var_dump($param); exit;
        try {
            $return = $this->KMKModel->saveBankKI($param);
            if($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            }
            else {
                throw new Exception('error');
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function deleteBankKI() {
        $param = $this->input->post() ;
        try {
            $this->db->where('ID', $param['ID']);
            $isDeleted = $this->db->delete('BANK_KI');
            if($isDeleted) {
                $res = "Data Deleted" ;
                $this->resource = array(
                    'status' => 200,
                    'data' => array($res, $param['EL'])
                );
            }
            else {
                $msg = "Delete error" ;
                throw new Exception($msg);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getBankKI () {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->getBankKI($param) ;
            $this->resource = array (
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getVendorKI(){
        $q = $this->input->get_post('q', true);
        $bu = $this->KMKModel->loadVendorKI(strtoupper($q));
        echo json_encode($bu);
    }

    public function setVendorKI() {
        $param = $this->input->post();
        // var_dump($param); exit;
        try {
            $list = $this->KMKModel->dataVendorKI($param) ;
            $this->resource = array (
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getOWTrancheKI() {
        $param = $this->input->post();
        // var_dump($param); exit;
        try {
            $list = $this->KMKModel->getOWTrancheKI($param) ;
            $this->resource = array (
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getOWTrancheKIByID() {
        $param = $this->input->post();
        // var_dump($param); exit;
        try {
            $list = $this->KMKModel->getOWTrancheKIByID($param) ;
            $this->resource = array (
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getWDTranchesKI() {
        $param = $this->input->post();

        try{
            $list = $this->KMKModel->getWDTranchesKI($param) ;
            $this->resource = array (
                'status' => 200,
                'data' => $list
            ) ;
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function uploadInstallment() {
        $param = $this->input->post();
        // var_dump($param); exit;
        try{
            $list = $this->KMKModel->UploadInstallment($param) ;
            if($param['CONTRACT_TYPE'] == 'SYNDICATION') {
                $updatepay = $this->ReportGenModel->SaveReportKI($param, $this->GetIpAddress());
                $updatepay = $this->ReportGenModel->SaveReportKI_SYD($param, $this->GetIpAddress());
            } else {
                $updatepay = $this->ReportGenModel->SaveReportKI($param, $this->GetIpAddress());
            }
            // var_dump($updatepay);exit;
            if($list['STATUS'] == FALSE) {
                $this->resource = array (
                    'status' => 500,
                    'data' => $list['MESSAGE']
                ) ;    
            } else {
                $this->resource = array (
                    'status' => 200,
                    'data' => $list['MESSAGE']
                ) ;
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function uploadInstallmentidc() {
        $param = $this->input->post();
        // var_dump($param); exit;
        try{
            $list = $this->KMKModel->UploadInstallmentidc($param) ;
            if($param['CONTRACT_TYPE'] == 'SYNDICATION') {
                $updatepay = $this->ReportGenModel->SaveReportKI($param, $this->GetIpAddress());
                $updatepay = $this->ReportGenModel->SaveReportKI_SYD($param, $this->GetIpAddress());
            } else {
                $updatepay = $this->ReportGenModel->SaveReportKI($param, $this->GetIpAddress());
            }
            // var_dump($updatepay);exit;
            $this->resource = array (
                'status' => 200,
                'data' => $list
            ) ;
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getInstallment() {
        $param = $this->input->post();
        try {
            $q = "SELECT * FROM 
            (SELECT FKI.*, MAX(FKI.COUNTER) OVER(PARTITION BY TRANCHE_NUMBER, UUID) as late_date
              FROM FUNDS_KI_INSTALLMENT FKI
             WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}') FKIT
             WHERE COUNTER = late_date AND IS_ORIGINAL = (SELECT DISTINCT IS_ORIGINAL FROM FUNDS_KI_INSTALLMENT WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}' AND COUNTER = late_date AND IS_ORIGINAL IS NOT NULL ORDER BY IS_ORIGINAL ASC FETCH FIRST 1 ROWS ONLY)
             ORDER BY ID";
            $result = $this->db->query($q, array('UUID' => $param['UUID'], 'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER']))->result() ;
            $this->resource = array (
                'status' => 200,
                'data' => $result
            ) ;
        } catch (Exception $ex) {
            $this->resoucre = array (
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getInstallmentidc() {
        $param = $this->input->post();
        try {
            $q = "SELECT * FROM 
            (SELECT FKI.*, MAX(FKI.COUNTER) OVER(PARTITION BY TRANCHE_NUMBER, UUID) as late_date
              FROM FUNDS_KI_INSTALLMENT_IDC FKI
             WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}') FKIT
             WHERE COUNTER = late_date AND IS_ORIGINAL = (SELECT DISTINCT IS_ORIGINAL FROM FUNDS_KI_INSTALLMENT_IDC WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}' AND IS_ORIGINAL IS NOT NULL ORDER BY IS_ORIGINAL ASC FETCH FIRST 1 ROWS ONLY)
             ORDER BY ID";
            $result = $this->db->query($q, array('UUID' => $param['UUID'], 'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER']))->result() ;
            $this->resource = array (
                'status' => 200,
                'data' => $result
            ) ;
        } catch (Exception $ex) {
            $this->resoucre = array (
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    // public function ExportReportKI() {
    //     $param = $this->input->post();

    //     try {
    //         $list = $this->KMKModel->ExportReportKI($param) ;
    //         $this->resource = array(
    //             'status' => 200,
    //             'data' => $list 
    //         ) ;

    //     }
    //     catch (Exception $ex) {
    //         $this->resource = array (
    //             'status' => 500,
    //             'data' => $ex->getMessage()
    //         ) ;
    //     }

    //     $this->SendResponse();
    // }

    public function TestSaveReportKI() {
        // var_dump('test'); exit;
        $param = [
            'UUID' => '96cce366-d5d1-418b-83cd-9ba095b3c784',
            'PK_NUMBER' => '2017.11.19 - (71-75) Not. Djumini'
        ];
        // var_dump($param); exit;

        try {
            $list = $this->ReportGenModel->SaveReportKI($param, $this->GetIpAddress()) ;
            // var_dump($list); exit;
            $this->resource = array(
                'status' => 200,
                'data' => $list 
            ) ;

        }
        catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            ) ;
        }

        $this->SendResponse();
    }

    public function reupdateAllReportKI() {
        $this->benchmark->mark('code_start');
        $allq = "select fm.UUID , fm.PK_NUMBER from funds_master fm left join funds_detail_ki fd on fd.uuid = fm.uuid where fm.credit_type = 'KI' and fm.isactive = 1 and fd.isactive = 1" ;
        $all = $this->db->query($allq)->result();
        // echo "<pre>";
        // var_dump($all); 
        // exit;
        try {
            foreach($all as $item) {
                $param = [
                    'UUID' => $item->UUID,
                    'PK_NUMBER' => $item->PK_NUMBER
                ] ;
                    $list = $this->ReportGenModel->SaveReportKI($param, $this->GetIpAddress()) ;
                    if(!$list) {
                        throw new Exception('error');
                    }
                    // var_dump($list); exit;
            }
        } catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            ) ;
        }
        $this->benchmark->mark('code_end');
        if($list) {
            $this->resource = array(
                'status' => 200,
                'data' => ["Time :". $this->benchmark->elapsed_time('code_start', 'code_end') ]
            ) ;
        }
        $this->SendResponse();
    }

    public function TestSaveReportKMK() {
        ini_set('display_errors', 'On');
        $param = [
            'UUID' => 'bd5723a0-ad96-4a14-b813-48005f79d432',
            'PK_NUMBER' => 'TEST-REPORT-RKv1.0',
            'CONTRACT_NUMBER' => '2301THWLBBCAB126'
        ];
        // var_dump($param); exit;

        try {
            $list = $this->ReportGenModel->SaveReportKMK($param, $this->GetIpAddress()) ;
            // var_dump($list); exit;
            $this->resource = array(
                'status' => 200,
                'data' => $list 
            ) ;

        }
        catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            ) ;
        }

        $this->SendResponse();
    }

    public function SaveReportKI_SYD() {
        // var_dump('test'); exit;
        $param = array(
            "UUID" => "7bd28f38-f5c4-42a1-be67-9801e067022d",
            "PK_NUMBER" => "123TESTKI/002",
            "TRANCHE_NUMBER" => "Term Loan A"
        ) ;
        // var_dump($param); exit;

        try {
            $list = $this->ReportGenModel->SaveReportKI_SYD($param, $this->GetIpAddress()) ;
            $this->resource = array(
                'status' => 200,
                'data' => $list 
            ) ;

        }
        catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            ) ;
        }

        $this->SendResponse();
    }

    public function ExportReportKI() {
        $param = $this->input->get();
        $parsav = [
            'UUID' => $param['UUID'],
            'PK_NUMBER' => $param['PK_NUMBER']
        ] ;
      
        $result1 = $this->ReportGenModel->SaveReportKI($parsav, $this->GetIpAddress());
        $tranches = $this->db->query("SELECT TRANCHE_NUMBER FROM FUNDS_DETAIL_KI_TRANCHE WHERE UUID = '{$param['UUID']}' AND ISACTIVE = '1'")->result();
        foreach($tranches as $tranche) {
                $parUp = [
                    'UUID' => $param['UUID'],
                    'TRANCHE_NUMBER' => $tranche->TRANCHE_NUMBER
                ] ;
                $updTrc = $this->KMKModel->updateInstallmentByWD($parUp) ;
                // var_dump($updTrc) ;
            }
        // exit ;
        try {
                $result = $this->ReportGenModel->ExportReportKI($param, $this->GetIpAddress());
            // var_dump($param);exit;
            // var_dump($result);exit;
            ini_set('display_errors', 'On');
            if ($result["STATUS"]) {
                $NameFile = "Report KI ". Carbon::now('Asia/Jakarta')->format('d-M-Y')."-"."{$param['PK_NUMBER']}";
                
                $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result["Data"], 'Xlsx');
                ob_end_clean();
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $NameFile . '.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                
                // If you're serving to IE over SSL, then the following may be needed
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0
                
                try {
                    $objWriter->save('php://output');
                }
                catch (Exception $ex) {
                    throw new Exception($ex);
                }
                // var_dump($objWriter);exit;
                // exit;
            } else {
                throw new Exception($result['Data']);
            }
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        // $this->SendResponse();
    }

    public function ExportReportKMK() {
        $param = $this->input->get();
        // var_dump($param); exit;
        $parsav = [
            'UUID' => $param['UUID'],
            'PK_NUMBER' => $param['PK_NUMBER'],
            'CONTRACT_NUMBER' => $param['CONTRACT_NUMBER']
        ] ;
        // ini_set('display_errors', 'On');
        try { 
            $result1 = $this->ReportGenModel->SaveReportKMK($parsav, $this->GetIpAddress());
            // var_dump($result1); exit;
            $result = $this->ReportGenModel->ExportReportKMK($param, $this->GetIpAddress());
            // $newName = preg_replace("/[\.]/", "_", $param['PK_NUMBER']);
            // echo "<pre>";
            // var_dump($result); exit;
            if($result['STATUS']) {
                $NameFile = "Report KMK ". Carbon::now('Asia/Jakarta')->format('d-M-Y')."-"."{$param['PK_NUMBER']}" ;
                $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result['Data'], 'Xlsx');
                // echo "<pre>";
                // var_dump($objWrite); exit;
                
                ob_end_clean();
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $NameFile . '.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                
                // If you're serving to IE over SSL, then the following may be needed
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                try {
                    // ob_end_clean();
                    $objWriter->save('php://output');
                    // exit;
                    // var_dump($return); exit;
                }
                catch (Exception $ex) {
                    throw new Exception($ex);
                }
            }
            else {
                throw new Exception('err');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function ExportReportKMKRK() {
        $param = $this->input->get();
        try {
            $result = $this->ReportGenModel->getPayAndWDRK();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function ExportReportKI_SYD() {
        $param = $this->input->get();
        $parsav = [
            'UUID' => $param['UUID'],
            'PK_NUMBER' => $param['PK_NUMBER']
        ] ;
        $result1 = $this->ReportGenModel->SaveReportKI($parsav, $this->GetIpAddress());
        $result1 = $this->ReportGenModel->SaveReportKI_SYD($parsav, $this->GetIpAddress());
        // var_dump("test"); exit;
        // ini_set('display_errors', 'On');
        // $param = array(
        //     "UUID" => "7bd28f38-f5c4-42a1-be67-9801e067022d",
        //     "PK_NUMBER" => "123TESTKI/002",
        //     "TRANCHE_NUMBER" => "Term Loan A",
        //     "START_PERIOD" => "3/16/2023",
        //     "END_PERIOD" => "3/16/2024"
        // ) ;
        // var_dump($param); exit;
        try {
            $result = $this->ReportGenModel->ExportReportKI_SYD($param, $this->GetIpAddress());
            // var_dump($result);exit;
            // ini_set('display_errors', 'On');
            if ($result["STATUS"]) {
                $NameFile = "Report KI ". Carbon::now('Asia/Jakarta')->format('d-M-Y')."-"."{$param['PK_NUMBER']}";
                
                $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result["Data"], 'Xlsx');
                ob_end_clean();
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $NameFile . '.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0
                
                $objWriter->save('php://output');
                exit;
            } else {
                throw new Exception($result["Data"]);
            }
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        // $this->SendResponse();
    }

    public function ShowLatestInstallment() {
        try {
            $param = $this->input->post();
            $list = $this->PayReqKMKKIModel->ShowLatestInstallment($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function saveProcessPeriod(){
        
        $param = $this->input->post();
        try {
            if($param['YEAR'] == null || $param['MONTH'] == null){
                throw new Exception('Data Bulan dan Tahun Kosong');
            }
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->KMKModel->saveUpdatePeriod($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function ShowDataPeriod() {
        try {
            $list = $this->KMKModel->ShowDataPeriod();
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getOWTrancheBalanceKI() {
        $param = $this->input->post();
        // var_dump($param); exit;
        try {
            $list = $this->KMKModel->getOWTrancheBalanceKI($param) ;
            $this->resource = array (
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function updateBalanceAfterPaid() {
        // $param = $this->input->post();
        // var_dump($param); exit;
        try {
            $result = $this->KMKModel->updateBalanceAfterPaid() ;
            if($result['STATUS'] == TRUE) {
                $this->resource = array (
                    'status' => 200,
                    'data' => $result['MESSAGE']
                );
            }
            else if(isset($result['NOTPAID'])) {
                $this->resource = array (
                    'status' => 500,
                    'data' => array('MESSAGE' => $result['MESSAGE'], 'NOTPAID' => TRUE )
                );
            }
            else {
                $this->resource = array (
                    'status' => 500,
                    'data' => $result['MESSAGE']
                );
            }
        } catch (Exception $ex) {
            $this->resource = array (
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getTrancheNumberList() {
        $param = $this->input->post();
        try {
            $get = $this->KMKModel->getTrancheNumberList($param);
            $this->resource = array(
                'status' => 200,
                'data'  => $get
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data'  => $ex->getMessage()
            );
        }
        $this->SendResponse();

    }

    public function GetDataKI() {
        $param = $this->input->post();
        try {
            $list = $this->KMKModel->GetDataKI($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );

        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getReferenceContract(){
        $q = $this->input->post();
        $query = "select UUID, PK_NUMBER, COMPANY from funds_master where company = '".$q['COMPANY']."' and credit_type='".$q['CREDIT_TYPE']."'";
        
        $res = $this->db->query($query)->result();
        echo json_encode($res);
        // return $re
        // s->result(); 
    }

    public function savePaymentReqKMK() {
        $param = $this->input->post();
        try {
            // var_dump($createTempReport); exit;
            $result = $this->PayReqKMKKIModel->SavePaymentReqKMK($param, $this->GetIpAddress()) ;
            $parSav = [
                'PK_NUMBER' => $param['PK_NUMBER'],
                'CONTRACT_NUMBER' => $param['CONTRACT_NUMBER']
            ] ;
            $createTempReport = $this->ReportGenModel->SaveReportKMK($parSav, $this->GetIpAddress());
            // var_dump($createTempReport); exit;
            if($result['STATUS'] == TRUE) {
                $this->resource = [
                    'status' => 200,
                    'data' => [ 'MESSAGE' =>$result['MESSAGE'] ]
                ] ;
            }
            else {
                $this->resource = [
                    'status' => 500,
                    'data' =>[ 'MESSAGE' =>$result['MESSAGE'] ]
                ];
            }
        } catch (Exception $ex) {
            $this->resource = [
                'status' => 500,
                'data' => [ 'MESSAGE' => $ex->getMessage() ]
            ] ;
        }
        $this->SendResponse();
    }

    public function showInterestKMKByDate() {
        $param = $this->input->post();
        $result = $this->PayReqKMKKIModel->ShowPaymentDatabyDate($param);
        if($result['STATUS'] == TRUE) {
            $this->resource = array(
                'status' => 200,
                'data' => $result
            );
        } else {
            $this->resource = array(
                'status' => 500,
                'data' => 'Interest is null'
            );
        }
        $this->SendResponse();
    }

    public function updateAllInstallment() {
        try {
            $getAllDataq = "SELECT fm.uuid, ft.tranche_number
                                FROM funds_master fm
                                    LEFT JOIN funds_detail_ki_tranche ft
                                        ON ft.uuid = fm.uuid AND ft.isactive = 1
                            WHERE fm.isactive = '1' AND tranche_number IS NOT NULL" ;
            $getAllData = $this->db->query($getAllDataq)->result();
            $resultStat = [];
            $status = true ;
            foreach($getAllData as $item) {
                $parUp = [
                    'UUID' => $item->UUID,
                    'TRANCHE_NUMBER' => $item->TRANCHE_NUMBER 
                ] ;
                $upInst = $this->KMKModel->updateInstallmentByWD($parUp) ;
                $resultStat[$parUp['UUID']] = $parUp['TRANCHE_NUMBER'] ;
                $resultStat[$parUp['UUID']] .= " - ".$upInst['MESSAGE'] ;
                if($upInst['STATUS'] == false) {
                    $status = false ;
                }
            }
            if($status == false) {
                $this->resource = array(
                    'status' => 500,
                    'data' => $resultStat
                );
            }
            else {
                $this->resource = array(
                    'status' => 200,
                    'data' => $resultStat
                );
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function updateInstallmentByUUID () {
        try {
            $param = $this->input->get();
            $resultStat = [];
            $status = true ;
            $parUp = [
                'UUID' => $param['UUID'],
                'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'] 
            ] ;
            $upInst = $this->KMKModel->updateInstallmentByWD($parUp) ;
            // var_dump($upInst);exit ;
            $resultStat[$parUp['UUID']] = $parUp['TRANCHE_NUMBER'] ;
            $resultStat[$parUp['UUID']] .= " - ".$upInst['MESSAGE'] ;
            if($upInst['STATUS'] == false) {
                $status = false ;
            }
            if($status == false) {
                $this->resource = array(
                    'status' => 500,
                    'data' => $resultStat
                );
            }
            else {
                $this->resource = array(
                    'status' => 200,
                    'data' => $resultStat
                );
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function easyUpdatePeriodControl() {
        $param = $this->input->post();
        try {
            $result = $this->PayReqKMKKIModel->easyUpdatePeriodControl($param) ;
            if($result['STATUS'] == TRUE) {
                $this->resource = [
                    'status' => 200,
                    'data' => $result['MESSAGE']
                ] ;
            }
            else {
                throw new Exception('Failed to Save') ;
            }
        } catch (Exception $ex) {
            $this->resource = [
                'status' => 500,
                'data' => $ex->getMessage()
            ] ;
        }
        $this->SendResponse();   
    }

    public function resetTotalWD() {
        $this->db->trans_begin();
        try {
            $listUUIDq   = "SELECT DISTINCT UUID FROM FUNDS_WD_KI" ;
            $listUUID = $this->db->query($listUUIDq)->result();
            // var_dump($listUUID);
            foreach($listUUID as $uuid) {
                $sumWDTrancheq = "SELECT SUM(DDOWN_AMT) AS TOTALDDOWN, BATCHID, UUID FROM FUNDS_WD_KI_TRANCHE 
                                    WHERE UUID = '".$uuid->UUID."' AND IS_PAYMENT IS NULL AND BATCHID = 1 
                                    GROUP BY UUID, BATCHID  " ;
                $sumWDTranche = $this->db->query($sumWDTrancheq)->result();
                // var_dump($this->db->last_query()); exit;
                foreach($sumWDTranche as $item) {
                    // var_dump($item); exit;
                    $TOTALDDOWN = $item->TOTALDDOWN ;
                    $updatedata = $this->db->set(['DRAWDOWN_VALUE' => $TOTALDDOWN])->where(['UUID' => "$uuid->UUID", 'CTRWD' => 1, 'IS_PAYMENT IS NULL' => null])->update('FUNDS_WD_KI') ;
                    // var_dump($this->db->last_query()); exit;
                    if(!$updatedata) {
                        throw new Exception('Err');
                        break ;
                    }
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            $this->db->trans_rollback();
            return false ;
        }
        if($updatedata) {
            $this->db->trans_commit();
        }
        echo $updatedata ;
        return false ;
    }
    
    //Update 1.3
    public function DisableEnableTranche() {
        // ini_set('display_errors', 'On');
        $param = $this->input->post();
        // var_dump($param) ; exit;
        try {
            $result = $this->KMKModel->DisableEnableTranche($param) ;
            // var_dump($result); exit;
            if($result['STATUS'] == TRUE) {
                $this->resource = [
                    'status' => 200,
                    'data' => $result['MESSAGE']
                ] ;
            }
            else {
                $this->resource = [
                    'status' => 500,
                    'data' => $result['MESSAGE']
                ] ;
            }
        } catch (Exception $ex) {
            $this->resource = [
                'status' => 500,
                'data' => $ex->getMessage()
            ] ;
        }
        $this->SendResponse();
    }
    //^ ^ ^
    //Updatev1.4
    public function ShowPaymentDataHistRequest() {
        $param = $this->input->post();
        try {
            $param = $this->input->post();
            $list = $this->PayReqKMKKIModel->ShowPaymentDataHistRequest($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function UploadPaymentBillKMKKI() {
        $param = $this->input->post();
        try {
            $param = $this->input->post();
            $result = $this->PayReqKMKKIModel->UploadPaymentBillKMKKI($param);
            if($result['STATUS'] == TRUE) {
                $this->resource = array(
                    'status' => 200,
                    'data' => $result['MESSAGE']
                );
            }
            else {
                throw new Exception($result['MESSAGE']) ;
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }
    //^ ^ ^
}