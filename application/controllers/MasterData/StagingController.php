<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StagingController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->datasend = [];
        $this->load->model('StagingModel');
	}

	public function ShowStagingData(){
        $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->StagingModel->ShowStagingData($param);
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

    public function saveStaging(){
        $param = $this->input->post();
        // echo "<pre>";
        // var_dump($param);exit;
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->StagingModel->saveStaging($param, $this->GetIpAddress());
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

    public function ShowStagingDataSTO(){
        $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->StagingModel->ShowStagingDataSTO($param);
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

    public function saveStagingSTO(){
        $param = $this->input->post();
        // echo "<pre>";
        // var_dump($param);exit;
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->StagingModel->saveStagingSTO($param, $this->GetIpAddress());
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

/* End of file StagingController.php */
/* Location: ./application/controllers/StagingController.php */