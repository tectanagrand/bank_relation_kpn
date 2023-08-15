<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OpenForecastController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->datasend = [];
        $this->load->model("Cashflow/OpenForecastModel", "OpenForecastModel");
    }

    public function ShowData() {
        try {
            $param = $_POST;
            $list=[];
            if (($param['YEAR'] != "" && $param['YEAR'] != NULL)) {
            $list = $this->OpenForecastModel->ShowData($param);
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

    public function Save()
    {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->OpenForecastModel->Save($param, $this->GetIpAddress());
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
