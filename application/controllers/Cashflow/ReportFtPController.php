<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ReportFtPController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Cashflow/ReportFtPModel", "ReportFtPModel");
    }

    public function ShowData()
    {
        $param = $this->input->post();
        try {
            $list = [];
            if (($param['YEAR'] != "" && $param['YEAR'] != NULL) || (isset($param['YEAR']) && isset($param['YEAR']))) {
                $list = $this->ReportFtPModel->ShowData($param);
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

    public function getSubGroup()
    {
        $FCCODE = $this->input->post('FCCODE');
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $this->db->select('*');
            $this->db->where('FCCODE_GROUP', $FCCODE);
            $res = $this->db->get('COMPANY_SUBGROUP')->result();
            if ($res) {

                $this->resource = [
                    'status' => 200,
                    'data' => $res,
                    'data_2' => ''
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage(),
                'data_2' => $ex->getMessage()
            );
        }
        $response = $this->BuildResponse_new($this->resource['status'], $this->resource['data']);

        echo json_encode($response);
    }

    public function getCompany()
    {
        $FCCODE = $this->input->post('FCCODE');
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $this->db->select('ID,COMPANYCODE,COMPANYNAME');
            $this->db->where('COMPANY_SUBGROUP', $FCCODE);
            $res = $this->db->get('COMPANY')->result();
            if ($res) {
                $this->resource = [
                    'status' => 200,
                    'data' => $res,
                    'data_2' => ''
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage(),
                'data_2' => $ex->getMessage()
            );
        }
        $response = $this->BuildResponse_new($this->resource['status'], $this->resource['data']);

        echo json_encode($response);
    }
}
