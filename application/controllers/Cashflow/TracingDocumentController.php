<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TracingDocumentController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model("Cashflow/TracingDocumentModel", "TracingDocumentModel");
    }

    public function ShowData() {
        $param = $this->input->post();
        try {
            // $param = $_POST;
            // $list = $this->TracingDocumentModel->TracingDocument($param);
            $list = [];
            if ($param['COMPANY'] != "") {
                $list = $this->TracingDocumentModel->TracingDocument($param);
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

    public function ShowDataDet() {
        $param = $this->input->post();
        try {
            // $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            // $list = $this->ElogModel->ShowSendData($param);
            $list = $this->TracingDocumentModel->TracingDocumentDet($param);
            $this->resource = array(
                'status' => 200,
                'data' => [
                    "draw" => $_POST["draw"],
                    "recordsTotal" => $list["recordsTotal"],
                    "recordsFiltered" => $list["recordsFiltered"],
                    "data" => $list["data"]
                ]
            );
            // $param = $_POST;
            // $list = $this->TracingDocumentModel->TracingDocument($param);
            // $list = [];
            // if ($param['COMPANY'] != "") {
                
            // }

            // $this->resource = array(
            //     'status' => 200,
            //     'data' => $list
            // );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

}