<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CashController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('CashModel');
    }

    public function ShowData() {
        $param = $this->input->post();
        try {
            $list = $this->CashModel->ShowData($param);
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

    public function getTotalInterco() {
        $param = $this->input->post();
        try {
            $list = $this->CashModel->getTotalInterco($param);
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

    public function getInOutInterco() {
        $param = $this->input->post();
        try {
            $list = $this->CashModel->getInOutInterco($param);
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

    public function ShowDataedited() {
        $param = $this->input->post();
        try {
            $list = $this->CashModelEdit->ShowDataedited($param);
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

    public function getSurplusedit() {
        $param = $this->input->post();
        try {
            $list = $this->CashModelEdit->getSurplusedit($param);
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

    public function getTotalIntercoedit() {
        $param = $this->input->post();
        try {
            $list = $this->CashModelEdit->getTotalIntercoedit($param);
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

    public function getInOutIntercoedit() {
        $param = $this->input->post();
        try {
            $list = $this->CashModelEdit->getInOutIntercoedit($param);
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

    // public function getOutInterco() {
    //     $param = $this->input->post();
    //     try {
    //         $list = $this->CashModel->getOutInterco($param);
    //         $this->resource = array(
    //             'status' => 200,
    //             'data' => $list
    //         );
            
    //     } catch (Exception $ex) {
    //         $this->resource = array(
    //             'status' => 500,
    //             'data' => $ex->getMessage()
    //         );
    //     }
    //     $this->SendResponse();
    // }

    public function getSurplus() {
        $param = $this->input->post();
        try {
            $list = $this->CashModel->getSurplus($param);
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

    public function getOpbal() {
        $param = $this->input->post();
        try {
            $list = $this->CashModel->getOpbal($param);
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


    public function submitOpbal(){
        $param = $this->input->post();
        try {
            $cash = $param['CASH'] == 'NaN' ? 0 : $param['CASH'];
            $data = array(
                'COMPANYGROUP'       => $param['COMPANYGROUP'],
                'COMPANYSUBGROUP'    => $param['COMPANYSUBGROUP'],
                'PERIOD_MONTH'       => $param['MONTH'],
                'PERIOD_YEAR'        => $param['YEAR'],
                'COMPANY'            => $param['COMPANY'],
                'ENDING_BALANCE'     => $cash
            );

            $submit = $this->db->insert('MONTHLYFORECAST_OPBAL',$data);
            
            $this->resource = array(
                'status' => 200,
                'data' => $submit
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getCompany() {
        $param = $this->input->post();
        try {
            $where = $param['SUBGROUP'];
            if($where == 'CEMENT'){
                $q  = "select ID,COMPANYCODE,COMPANYNAME from company c inner join company_subgroup csg on csg.fccode = c.company_subgroup where csg.fccode = 'CEMENT' order by companyname asc";
                $company = $this->db->query($q)->result();
            }else{
                $this->db->select('ID,COMPANYCODE,COMPANYNAME');
                $this->db->where('COMPANY_SUBGROUP',$where);
                $this->db->order_by('COMPANYNAME','ASC');    
                $company = $this->db->get('COMPANY')->result();
            }
            
            $this->resource = array(
                'status' => 200,
                'data' => $company
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function getSubgroup(){
        $param = $this->input->post();
        try {
            $where = $param['GROUP'];
            $q  = "SELECT FCCODE,FCCODE_GROUP FROM COMPANY_SUBGROUP WHERE FCCODE_GROUP = '$where'";
            $company = $this->db->query($q)->result();
            
            $this->resource = array(
                'status' => 200,
                'data' => $company
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