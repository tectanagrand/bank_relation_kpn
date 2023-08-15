<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadDocController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model("Cashflow/UploadModel", "UploadModel");
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
    }

    public function DataUpload() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->DataUpload($param);
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

    public function Save_PO() {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);
//            var_dump($param["DATA"]);
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->Save_PO($param, $this->GetIpAddress());
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

    public function Save_POHana()
    {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);
            //            var_dump($param["DATA"]);
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->Save_POHana($param, $this->GetIpAddress());
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

    public function UploadPO1() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->UploadPO1($param);
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

    public function UploadPO2() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->UploadPO2($param);
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

    public function SetUploadPO() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->SetUploadPO($param);
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

    public function UploadPO5() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->UploadPO5($param);
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

    public function UploadPO5Hana()
    {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->UploadPO5Hana($param);
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

    public function UploadPaymentReceive() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->UploadPaymentReceive($param,$this->GetIpAddress());
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

    public function UploadPaymentInterco() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->UploadPaymentInterco($param,$this->GetIpAddress());
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

    public function clearPaymentReceive(){
        $param = $this->input->post();
        try {
            
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            if($param['DOCTYPE'] == 'PAYMENT_OTHERS'){
                 $this->db->where('FCENTRY',$this->session->userdata('FCCODE')."-U");
            // $return = $this->db->delete('TEMP_UPLOAD_FORECAST');    
                $return = $this->db->delete('TEMP_UPLOAD_PAYMENT_OTHERS');
            }
            if($param['DOCTYPE'] == 'INTERCO'){
                $this->db->where('FCENTRY',$this->session->userdata('FCCODE')."-U");
                $return = $this->db->delete('TEMP_UPLOAD_INTERCOLOANS');
            }
            else{
                $this->db->where('FCENTRY',$this->session->userdata('FCCODE')."-U");
                $return = $this->db->delete('TEMP_UPLOAD_PAYMENT');    
            }
            
            if ($return == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return
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

    public function saveUpPaymentReceive() {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);

            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->saveUpPaymentReceive($param, $this->GetIpAddress());
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

    public function saveUpPaymentInterco() {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);

            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->saveUpPaymentInterco($param, $this->GetIpAddress());
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

    public function Upload_Pph() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->upload_PPH($param,$this->GetIpAddress());
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

    public function saveUp_PPH() {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);

            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->saveUp_PPH($param, $this->GetIpAddress());
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

    public function uploadForecast() {
        
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->uploadForecast($param,$this->GetIpAddress());
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

    public function saveUpForecast() {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);

            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->UploadModel->saveUpForecast($param, $this->GetIpAddress());
            // ini_set('display_errors', 'On');
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

    public function clearForecast(){
        $param = $this->input->post();
        try {
            
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
                
            $this->db->where('FCENTRY',$this->session->userdata('FCCODE'));
            $return = $this->db->delete('TEMP_UPLOAD_FORECAST');    
            
            if ($return == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return
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

    public function clearPaymentInterco(){
        $param = $this->input->post();
        try {
            
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
                
            $this->db->where('FCENTRY',$this->session->userdata('FCCODE')."-U");
            $return = $this->db->delete('TEMP_UPLOAD_INTERCOLOANS');    
            
            if ($return == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return
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
