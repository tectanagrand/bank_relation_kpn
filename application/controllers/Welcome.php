<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
    {
    	parent::__construct();
		$this->load->model('Welcome_model');
		$this->load->library('session');

		
		if($this->session->userdata('status') <> 'login')
		{
			redirect(site_url("login"));
		}
		$this->load->helper('url');
		//var_dump($this->session->userdata('FCBA'));
		//exit;
		/*
		
		if($this->session->userdata('status') != "login"){
			redirect(site_url("login"));
		}else{		
			$this->load->model('Sidebar_model');
		}
		*/
		if($this->session->userdata('status') != "login"){
			redirect(site_url("login"));
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		$data_session['content'] = 'home/welcome';
		$data_session['sidebar'] = 'sidebar_view';
		$data = array();
		/*
		foreach($this->Welcome_model->cur_production() as $header) {
			$data[] = $header->QTY_PROD;
			$data[] = $header->CPO;
			$data[] = $header->BJR;
        }
		
		$data_session['service']=$data;
		*/
		$this->load->view('template',$data_session);
	}

	public function chart_estate_prod()
	{
		// $datachart = array();
		// foreach($this->Welcome_model->cur_production_chart() as $headerchart) {
		// 	$datachart[] = $headerchart;
  //       }
		// echo json_encode($datachart);
	}
	
	public function chart_estate_prod_fcba($fcba)
	{
		// $datachart = array();
		// foreach($this->Welcome_model->cur_production_chart_fcba($fcba) as $headerchart) {
		// 	$datachart[] = $headerchart;
  //       }
		// echo json_encode($datachart);
	}
	
	public function chart_estate_prod_det($fcba, $division)
	{
		// $datachart = array();
		// foreach($this->Welcome_model->cur_production_chart_det($fcba, $division) as $headerchart) {
		// 	$datachart[] = $headerchart;
  //       }
		// echo json_encode($datachart);
	}
	
	public function chart_cpo_prod()
	{
		// $datachart = array();
		// foreach($this->Welcome_model->cur_cpo_chart() as $headerchart) {
		// 	$datachart[] = $headerchart;
  //       }
		// echo json_encode($datachart);
	}
}