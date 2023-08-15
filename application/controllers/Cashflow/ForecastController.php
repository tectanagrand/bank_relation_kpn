<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ForecastController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model("Cashflow/ForecastModel", "ForecastModel");
    }

    public function set_session(){
        $POST = $this->input->post();
        $this->session->set_userdata('status_fc', $POST['cekFC']);
        if($POST['cekFC'] == 1){
            echo json_encode(1);    
        }
        else{
            echo json_encode(0);
        }
    }

    public function GetWeek() {
        $param = $this->input->post();
        try {
            if ($param['PERIOD'] == '' || $param['PERIOD'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->GetWeek($param);
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

    public function showLogForecast(){
        try {
            $param = $this->input->post();
            if ($param['YEAR'] == '' || $param['YEAR'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->showLogForecast($param);
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

    public function SaveForecastTemp() {
        $param = $this->input->post();
        try {
            $param["DtForecast"] = json_decode($param["DtForecast"], TRUE);
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ForecastModel->SaveForecastTemp($param, $this->GetIpAddress());
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

    public function showNegatifAmount(){
        try {
            $param = $this->input->post();
            $list = $this->ForecastModel->showNegatifAmount($param);
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

    public function DtOutstanding() {
        try {
            $param = $_POST;
            if ($param['YEAR'] == '' || $param['YEAR'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->DtOutstanding($param);
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

    public function DtOutstandingNew() {
        try {
            $param = $_POST;
            if ($param['YEAR'] == '' || $param['YEAR'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->DtOutstandingNew($param);
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

    //Region Addition For Forecast Without Invoice

    public function DtOutstandingNoInvNew() {
        try {
            $param = $_POST;
            if ($param['YEAR'] == '' || $param['YEAR'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->DtOutstandingNoInvNew($param);
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

    public function DtOutstandingNoInv() {
        try {
            $param = $_POST;
            if ($param['YEAR'] == '' || $param['YEAR'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->DtOutstandingNoInv($param);
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
	
	public function AddDummyInv(){
		try {
            $param = $_POST;
            if ($param['ID'] == '' || $param['ID'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->AddDummyInv($param);
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

    public function AddDummyInvNew(){
        try {
            $param = $_POST;
            if ($param['ID'] == '' || $param['ID'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->AddDummyInvNew($param);
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

    public function ShowDtForecastNew() {
        try {
            $param = $this->input->post();
            if ($param['YEAR'] == '' || $param['YEAR'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->ShowDtForecastNew($param);
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

    public function ShowDtForecast() {
        try {
            $param = $this->input->post();
            if ($param['YEAR'] == '' || $param['YEAR'] == NULL) {
                $list = [];
            } else {
                $list = $this->ForecastModel->ShowDtForecast($param);
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

    public function SaveForecast() {
        $param = $this->input->post();
        try {
            $param["DtForecast"] = json_decode($param["DtForecast"], TRUE);
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
//            var_dump($param["DtForecast"]);
            $return = $this->ForecastModel->SaveForecast($param, $this->GetIpAddress());
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

    public function SaveRA() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ForecastModel->SaveRA($param, $this->GetIpAddress());
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

    public function DtRevAppDana() {
        try {
            $param = $this->input->post();
            $list = $this->ForecastModel->DtRevAppDana($param);
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

    public function SaveRevApp() {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ForecastModel->SaveRevApp($param, $this->GetIpAddress());
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

    public function CekValidation() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ForecastModel->CekValidation($param, $this->GetIpAddress());
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

    public function CancelValidation() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ForecastModel->CancelValidation($param, $this->GetIpAddress());
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

    public function WeekHeader(){
        $param= $this->input->post();
        $SQL = "SELECT * FROM SETTING_WEEK WHERE YEAR = '".$param['YEAR']."' AND MONTH = '".$param['MONTH']."' ";
        $result = $this->db->query($SQL)->result();
        $this->db->close();
        echo json_encode($result);
    }

}
