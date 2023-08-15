<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserCompanyController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->datasend = [];
        $this->load->model("MasterData/UserCompanyModel", "UserCompanyModel");
    }

    public function ShowData() {
        try {
            $list = $this->UserCompanyModel->ShowData();
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

    public function GetListAccess() {
        $param = json_decode(file_get_contents('php://input'), TRUE);
        try {
            $list = $this->UserCompanyModel->GetListAccess($param['USERCODE']);
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
            $return = $this->UserCompanyModel->Save($param, $this->GetIpAddress());
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
            $return = $this->UserCompanyModel->Delete($param, $this->GetIpAddress());
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

    public function getUserCode(){
        $q = $this->input->get_post('q', true);
        $query = "SELECT FCCODE,FULLNAME FROM USERS_TAB WHERE ISACTIVE = 'TRUE' AND FULLNAME LIKE '%".strtoupper($q)."%' ESCAPE '!'";
        $query = $this->db->query($query)->result();
        echo json_encode($query);
    }

}
