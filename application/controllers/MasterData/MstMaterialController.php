<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MstMaterialController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->datasend = [];
        $this->load->model('MaterialModel');
    }

    public function ShowData() {
        try {
            $list = $this->MaterialModel->ShowData();
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

    public function isShow() {
        try {
            $param = $_POST;
            if($this->session->userdata('DEPARTMENT') == 'FINANCE' || $this->session->userdata('DEPARTMENT') == 'IT'){
                $this->db->set('LASTUPDATE', "SYSDATE", false);
                $this->db->set('FCEDIT', $param['FCEDIT']);
                $this->db->set('IS_SHOW',$param['SHOW']);
                $this->db->where('ID',$param['ID']);
                $list = $this->db->update('MATERIAL');
                $this->resource = array(
                    'status' => 200,
                    'data' => 'OK'
                );
            }else{
                $this->resource = array(
                    'status' => 500,
                    'data' => 'Not Ok'
                );    
            }
            
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
            $return = $this->MaterialModel->Save($param, $this->GetIpAddress());
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
            $return = $this->MaterialModel->Delete($param, $this->GetIpAddress());
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
