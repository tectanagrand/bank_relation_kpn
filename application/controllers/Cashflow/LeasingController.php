<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeasingController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("MasterData/General/CompanyModel","BusinessUnitModel","VendorModel","LeasingModel","MaterialModel"));
	}

    public function uploadFile() {
        
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->LeasingModel->uploadFile($param,$this->GetIpAddress());
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

    public function DeleteFile(){
        $param = $this->input->post();
        try {
            $path_to_file = ROOT.$param['FILENAME'];
            // var_dump($path_to_file);exit;
            if ( file_exists($path_to_file) ){
                if( unlink($path_to_file) ) {
                    $this->db->where('ID',$param['ID']);
                    $isDeleted = $this->db->delete('LEASINGFILE');
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

    public function Save() {
        $param = $this->input->post();
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            // $getDocNumber = $param['DOCNUMBER'];
            // $cekQ         = "SELECT DOCNUMBER FROM LEASINGMASTER WHERE DOCNUMBER = '".$getDocNumber."'";
            // $res          = $this->db->query($cekQ)->result();
            // if($res != null){
            //     throw new Exception('DOCNUMBER Already Exists');
            // }
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->LeasingModel->Save($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['IDS']
                ];
                $getData = $this->db->get_where('LEASINGMASTER',['UUID'=>$return['IDS']])->row();
                $this->LeasingModel->saveLeasingReport($getData->DOCNUMBER,$getData->COMPANY);
            } else {
                $this->resource = [
                    'status' => 500,
                    'data' => $return['MESSAGE']
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

    public function viewDetail(){
        $UUID = $this->input->post('ID');
        $q    = "SELECT L.*, C.COMPANYNAME, S.FCNAME AS VENDORNAME, B.FCNAME AS BUNAME, M.FCCODE || ' - ' || M.FCNAME AS ITEM_NAME FROM LEASINGMASTER L LEFT JOIN COMPANY C ON C.ID = L.COMPANY LEFT JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN BUSINESSUNIT B ON B.ID = L.BUSINESSUNIT LEFT JOIN MATERIAL M ON M.ID = L.ITEM_CODE
            WHERE L.UUID = '$UUID'";
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        echo json_encode($result);
    }

    public function viewDetailCompletion(){
        $UUID = $this->input->post('ID');
        $DOCNUMBER = $this->input->post('DOCNUMBER');
        $q    = "SELECT L.PENALTY_PERCENTAGE,
       MIN(LT.REMAIN_BASIC_AMOUNT_LEASING) REMAIN_BASIC_AMOUNT_LEASING,
       (SELECT DENDA
          FROM DENDA_LEASING
         WHERE DOCNUMBER = '$DOCNUMBER')
          DENDA FROM LEASINGMASTER L LEFT JOIN LEASINGTRANSACTION LT ON LT.DOCNUMBER = L.DOCNUMBER 
            WHERE L.UUID = '$UUID' GROUP BY PENALTY_PERCENTAGE";
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        echo json_encode($result);
    }


    public function ShowFileData() {
        $param = $this->input->post();
        try {
            $list = $this->LeasingModel->ShowFileData($param);
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
        try {
            $list = $this->LeasingModel->ShowData();
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

    public function ShowDataPeriod() {
        try {
            $list = $this->LeasingModel->ShowDataPeriod();
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
            $return = $this->LeasingModel->saveUpdatePeriod($param, $this->GetIpAddress());
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

    public function ShowDataTransaction() {
        $param = $this->input->post();
        try {
            $list = $this->LeasingModel->ShowDataTransaction($param);
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

    public function ReportDataTransaction() {
        $param = $this->input->post();
        try {
            $list = $this->LeasingModel->ReportDataTransaction($param);
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

    public function saveLeasingTransaction() {
        $param = $this->input->post();
        
        try {
            // if($param['DUEDATE'] == null || $param['DUEDATE'] == ''){
            //     throw new Exception('Data Bulan dan Tahun Kosong');
            // }
            if($param['YEAR'] == null || $param['MONTH'] == null){
                throw new Exception('Data Bulan dan Tahun Kosong');
            }
            // if($param['DUEDATE'] < Date('Y-m-d')){
            //     throw new Exception('Due Date Error');
            // }
            // $param["DtForecast"] = json_decode($param["DtForecast"], TRUE);
            // echo "<pre>";
            // var_dump($param);exit();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->LeasingModel->saveLeasingTransaction($param, $this->GetIpAddress());
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

    public function payAllLeasingTransaction() {
        $param = $this->input->post();
        
        try {
            if($param['YEAR'] == null || $param['MONTH'] == null){
                throw new Exception('Data Bulan dan Tahun Kosong');
            }
            // if($param['INTEREST_PERCENTAGE'] == null){
            //     throw new Exception('Interest Percentage Kosong');
            // }
            // if($param['DUEDATE'] < Date('Y-m-d')){
            //     throw new Exception('Due Date Error');
            // }
            $param["DtLeasing"] = json_decode($param["DtLeasing"], TRUE);
            // echo "<pre>";
            // var_dump($param);exit();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->LeasingModel->payAllLeasingTransaction($param, $this->GetIpAddress());
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

    public function ShowDataCompletion() {
        $param = $this->input->post();
        // var_dump($param);exit();
        try {
            $list = $this->LeasingModel->ShowDataCompletion($param);
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

    public function saveLeasingCompletion() {
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
            $return = $this->LeasingModel->saveLeasingCompletion($param, $this->GetIpAddress());
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

    public function showDeleteTransaction() {
        $param = $this->input->post();
        // var_dump($param);exit();
        try {
            $list = $this->LeasingModel->showDeleteTransaction($param);
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

    public function DeleteLeasingTransaction() {
        $param = $this->input->post();
        // var_dump($param);exit();
        try {
            $list = $this->LeasingModel->DeleteLeasingTransaction($param);
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

    public function showDeleteForecast(){
        try {
            $param = $this->input->post();
            if ($param['YEAR'] == '' || $param['YEAR'] == NULL) {
                $list = [];
            } else {
                $list = $this->LeasingModel->showDeleteForecast($param);
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

    public function DeleteForecastTransaction() {
        $param = $this->input->post();
        // var_dump($param);exit();
        try {
            $list = $this->LeasingModel->DeleteForecastTransaction($param);
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

    public function DeleteMaster() {
        $param = $this->input->post();
        // var_dump($param);exit();
        try {
            $list = $this->LeasingModel->DeleteMaster($param);
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

    public function exportTransaction() {
        $post = $this->input->get();
        try {
            $result = $this->LeasingModel->ExportTransaction($post);
            ini_set('display_errors', 'On');
            if ($result["STATUS"]) {
                $NameFile = 'Report Leasing Details';
                
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
    }

    public function exportNewReport() {
        $post = $this->input->get();
        try {
            $result = $this->LeasingModel->exportNewReport($post);
            ini_set('display_errors', 'On');
            if ($result["STATUS"]) {
                $NameFile = 'Report Leasing '.Date('d_m_Y');
                
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
    }

    public function showReportLeasing() {
        $param = $this->input->post();
        try {
            $list = $this->LeasingModel->showReportLeasing($param);
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

	public function getCompany(){
        $q = $this->input->get_post('q', true);
        $company = $this->CompanyModel->loadCompany(strtoupper($q));
     	echo json_encode($company);
    }
    public function getBusinessUnit(){
        $q = $this->input->get_post('COMPANY', true);
        $bu = $this->BusinessUnitModel->loadBusinessUnit($q);
     	echo json_encode($bu);
    }
    public function getVendor(){
        $q = $this->input->get_post('q', true);
        $bu = $this->VendorModel->loadVendor(strtoupper($q));
        echo json_encode($bu);
    }
    public function getMaterialCode(){
        $q      = $this->input->get_post('q', true);
        $EXTSYS = $this->input->get_post('EXTSYS', true);
        $bu = $this->MaterialModel->loadMaterial($q,$EXTSYS);
        echo json_encode($bu);
    }


}

/* End of file LeasingController.php */
/* Location: ./application/controllers/Cashflow/LeasingController.php */