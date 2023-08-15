<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReportBankBalanceController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model("ReportBankBalanceModel", "ReportBankBalanceModel");
    }

    public function ShowData() {
        $param = $this->input->post();
        try {
            $list = [];
            if (($param['YEAR'] != "" && $param['YEAR'] != NULL) || (isset($param['YEAR']) && isset($param['YEAR']))) {
                $list = $this->ReportBankBalanceModel->ShowData($param);
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

}