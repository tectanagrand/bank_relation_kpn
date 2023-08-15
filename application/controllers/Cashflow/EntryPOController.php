<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EntryPOController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model("Cashflow/EntryPOModel", "EntryPOModel");
    }

    public function ShowData() {
        // echo "<pre>";
        // var_dump($_POST);exit();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->EntryPOModel->ShowData($param);
            $this->resource = array(
                'status' => 200,
                'data' => [
                    "draw" => $_POST["draw"],
                    "recordsTotal" => $list["recordsTotal"],
                    "recordsFiltered" => $list["recordsFiltered"],
                    "data" => $list["data"]
                ]
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function ShowDataDetail() {
        // echo "<pre>";
        // var_dump($_POST);exit();
        try {
            $param = $_POST;
            $list = $this->EntryPOModel->ShowDataDetail($param);
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

    public function ShowDataInvoice() {
        try {
            $param = $_POST;
            $list = $this->EntryPOModel->ShowDataInvoice($param);
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

    public function GetVendor() {
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->EntryPOModel->GetVendor($param);
            $this->resource = array(
                'status' => 200,
                'data' => [
                    "draw" => $_POST["draw"],
                    "recordsTotal" => $list["recordsTotal"],
                    "recordsFiltered" => $list["recordsFiltered"],
                    "data" => $list["data"]
                ]
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function GetItem() {
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->EntryPOModel->GetItem($param);
            $this->resource = array(
                'status' => 200,
                'data' => [
                    "draw" => $_POST["draw"],
                    "recordsTotal" => $list["recordsTotal"],
                    "recordsFiltered" => $list["recordsFiltered"],
                    "data" => $list["data"]
                ]
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function GetItemInvoice() {
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->EntryPOModel->GetItemInvoice($param);
            $this->resource = array(
                'status' => 200,
                'data' => [
                    "draw" => $_POST["draw"],
                    "recordsTotal" => $list["recordsTotal"],
                    "recordsFiltered" => $list["recordsFiltered"],
                    "data" => $list["data"]
                ]
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
            $return = $this->EntryPOModel->Save($param, $this->GetIpAddress());
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
            $return = $this->EntryPOModel->Delete($param, $this->GetIpAddress());
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
