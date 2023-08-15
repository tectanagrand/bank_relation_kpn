<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CashflowController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cashflow/CashflowModel', 'CashflowModel');
    }

    public function ShowData() {
        $param = $this->input->post();
        try {
            if ($param['DATEFROM'] || $param['DATEFROM'] != '' || $param['DATETO'] || $param['DATETO'] != '') {
                $list = $this->CashflowModel->ShowData($param);
            } else {
                $list = [];
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

    public function getDataBank() {
        $param = $this->input->post();
        try {
            $list = $this->CashflowModel->getDataBank($param['dataKeywords']);
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

    public function getOpBal(){
        $param = $this->input->post();
        try {
            $list = $this->CashflowModel->getOpBal($param);
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