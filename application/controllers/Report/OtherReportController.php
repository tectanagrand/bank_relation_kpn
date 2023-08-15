<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OtherReportController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model("OtherReportModel", "OtherReportModel");
    }

    public function ReportOSnAging() {
        $param = $this->input->post();
        try {
            $list = [];
            if ($param['DATEFROM'] && $param['DATEFROM'] != '' && $param['DATEFROM'] != 'Invalid date' && $param['DATETO'] && $param['DATETO'] != '' && $param['DATETO'] != 'Invalid date') {
                $list = $this->OtherReportModel->ReportOSnAging($param);
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

    public function ReportPayment() {
        $param = $this->input->post();
        try {
            $list = [];
            if ($param['DATEFROM'] && $param['DATEFROM'] != '' && $param['DATEFROM'] != 'Invalid date' && $param['DATETO'] && $param['DATETO'] != '' && $param['DATETO'] != 'Invalid date') {
                $list = $this->OtherReportModel->ReportPayment($param);
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