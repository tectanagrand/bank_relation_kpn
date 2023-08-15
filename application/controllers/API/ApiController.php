<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ApiController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->datasend = [];
        $this->load->model("API/ApiModel", "ApiModel");
    }

    public function fetchDataCFTrans() {
        try {
            $param = $this->input->post();
            if (empty($param['URL']) || $param['URL'] == '') {
                $list = [];
            } else {
                $list = $this->ApiModel->fetchDataCFTrans($param['URL']);
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

    public function fetchRAW() {
        try {
            $param = $this->input->post();
            $list = $this->ApiModel->fetchRAW($param['URL']);
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

    public function saveDataCFTrans() {
        try {
            $param = $this->input->post();
            $param["DATA"] = json_decode($param["DATA"], TRUE);
            $list = $this->ApiModel->saveDataCFTrans($param, $this->GetIpAddress());
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