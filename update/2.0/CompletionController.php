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

class CompletionController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("MasterData/General/CompanyModel","BusinessUnitModel","VendorModel","Cashflow/CompletionModel","MaterialModel"));
	}
    
    public function ShowDataCompletion() {
        try {
            $param = $this->input->post();
            $list = $this->CompletionModel->ShowDataCompletion($param);
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

    public function GetIDFundsPayment() {
        try {
            $param = $this->input->post();
            $list = $this->CompletionModel->GetIDFundsPayment($param);
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

    public function SaveCompletion() {
        $param = $this->input->post();
        // echo "<pre>";
        // var_dump($param);exit();
        /*try {
            if($param['AMOUNT_PENALTY'] == null){
                throw new Exception('Filter Tidak Boleh Kosong');
            }
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->CompletionModel->SaveCompletion($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return
                ];
            } else {
                throw new Exception($return);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }*/
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->CompletionModel->SaveCompletion($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
            $this->resource = array(
                'status' => 200,
                'data' => $return['MESSAGE']
            );
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

    public function GetRemainingInterestByDate() {
        try {
            $param = $this->input->post();
            $list = $this->CompletionModel->GetRemainingInterestByDate($param);
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
}