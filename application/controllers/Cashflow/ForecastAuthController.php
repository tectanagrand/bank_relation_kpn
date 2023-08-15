<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ForecastAuthController extends BaseController {

   public function __construct()
    {
        parent::__construct();
        $this->load->model('Cashflow/ForecastAuthModel','forecastauth');
		//$this->load->model('Sidebar_model');
    }
	
	public function ajax_list()
    {	
        $list = $this->forecastauth->get_datatables();
		$check = "";
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $forecastauth) {
            $no++;
            $row = array();
			$row[] = $forecastauth->FCBA;
			$row[] = $forecastauth->DOCNUMBER;
			$row[] = $forecastauth->DOCDATE;
			$row[] = "<span class='label label-info' style='color:white'>".$forecastauth->FCUSERCODE."</span>";
			$row[] = $forecastauth->LASTUPDATE;
			if ($forecastauth->MAXLVL == $forecastauth->CURRENTAUTHORIZELEVEL AND $forecastauth->STATE != '2'){
				if ($forecastauth->FCUSERCODE == $this->session->userdata('username')){
					$row[] = '<a class="btn btn-success btn-icon btn-circle btn-sm" href="'.site_url('attendancesubmission?type=edit&fcba='.$forecastauth->FCBA.'&docnumber='.$forecastauth->DOCNUMBER.'&docdate='.$forecastauth->DOCDATE.'&state=1&authlevel='.$forecastauth->CURRENTAUTHORIZELEVEL.'').'" title="Edit" ><i class="fa fa-edit" aria-hidden="true"></i></a>';
				}else{
					$row[] = "<span class='label label-warning' style='color:white'>On Progress</span>";
				}
			}
			else
			{
				$row[] = "<span class='label label-green' style='color:white'>Passed</span>";
			}
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->forecastauth->count_all(),
                        "recordsFiltered" => $this->forecastauth->count_filtered(),
                        "data" => $data,
                );
		
        //output to json format		
        echo json_encode($output);		
    }

}