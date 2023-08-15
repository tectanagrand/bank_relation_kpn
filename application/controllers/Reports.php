<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends BaseController {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('status') <> 'login') {
            redirect(site_url("login"));
            die();
        }
        $this->load->model("MasterData/PermissionModel", "PermissionModel");
        $this->datasend['SESSION'] = $this->session;
        $this->datasend['DtUser2'] = $this->PermissionModel->GetDtUser($this->datasend['SESSION']->FCCODE);
        $this->datasend['ROLEACCESS'] = $this->datasend['DtUser2']->USERGROUPID;
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
    }

    public function OSnAging() {
        $this->datasend['formid'] = '58';   
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Report/ReportOSnAging';
        $this->load->view('template', $this->datasend);
    }
    
}
