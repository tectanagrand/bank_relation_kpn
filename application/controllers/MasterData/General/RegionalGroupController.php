<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RegionalGroupController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->datasend = [];
        $this->load->model("MasterData/General/RegionalGroupModel", "RegionalGroupModel");
    }

    public function ShowData() {
        try {
            $list = $this->RegionalGroupModel->ShowData();
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
            $return = $this->RegionalGroupModel->Save($param, $this->GetIpAddress());
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

    public function Delete() {
        try {
            $param = $this->input->post();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->RegionalGroupModel->Delete($param, $this->GetIpAddress());
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