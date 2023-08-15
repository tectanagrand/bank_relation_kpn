<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AllReport extends CI_Controller {

	public function __construct()
    {
    	parent::__construct();
		//$this->load->model('DashboardProduction_model');		
		//$this->load->model('Dashboardker_model','dashboardker');
		$this->load->library('session');

		/*
		if($this->session->userdata('status') <> 'login')
		{
			redirect(site_url("login"));
		}
		$this->load->helper('url');
		//var_dump($this->session->userdata('status'));
		//exit;
		
		
		if($this->session->userdata('status') != "login"){
			redirect(site_url("login"));
		}else{		
			$this->load->model('Sidebar_model');
		}
		*/
	}
	
	public function index()
	{
		$data_session['content'] = 'report/allreport_view';
		$data_session['sidebar'] = 'sidebar_view';
		/*$data_session['css'] = '<link href="<?php echo base_url()?>/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />';*/
		$this->load->view('template',$data_session);
	}
}