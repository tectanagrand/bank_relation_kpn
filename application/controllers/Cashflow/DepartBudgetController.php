<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DepartBudgetController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->datasend = [];
        $this->load->model("Cashflow/DepartBudgetModel", "DepartBudgetModel");
    }

    public function ShowData() {
        try {
            $param = $_POST;

            if ($param['YEAR'] == 0) {
                $list = [];
            } else {
                $list = $this->DepartBudgetModel->ShowData($param);
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

    public function Save() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->DepartBudgetModel->Save($param, $this->GetIpAddress());
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

}
