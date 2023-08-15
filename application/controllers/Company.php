<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Controller for codeigniter crud using ajax application.
class Company extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Company_model', 'company');
        //$this->load->model('Sidebar_model');
    }

    public function index() {
        $this->load->helper(array('url', 'file'));
        $data['content_js'] = 'applicationmaster/company/company_js';
        $data['content'] = 'applicationmaster/company/company_view';
        $data['sidebar'] = 'sidebar_view';
        //$data['list_sidebar'] = $this->Sidebar_model->list_sidebar();		
        $this->load->view('template', $data);
    }

    public function ajax_list() {
        $list = $this->company->get_datatables();
        $check = "";
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $company) {
            $no++;
            $row = array();
            $row[] = $company->COMPANYCODE;
            $row[] = $company->COMPANYNAME;
            $row[] = $company->COMPANYNO;

            $row[] = '<a href="' . site_url('Company?type=edit&fccode=' . $company->COMPANYCODE . '') . '" title="Edit" ><i class="fa fa-edit" aria-hidden="true"></i></a>
						  &nbsp; <a  href="javascript:void(0)" title="Delete" onclick="delete_index(' . "'" . $company->COMPANYCODE . "'" . ')"> <i class="fa fa-trash" aria-hidden="true"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->company->count_all(),
            "recordsFiltered" => $this->company->count_filtered(),
            "data" => $data,
        );

        //output to json format		
        echo json_encode($output);
    }

    public function ajax_add() {
        $this->_validate();
        $data = array(
            'COMPANYCODE' => $this->input->post('fccode'),
            'COMPANYNAME' => $this->input->post('fcname'),
            'COMPANYNO' => $this->input->post('companyno'),
            'IP' => $this->input->ip_address(),
        );

        //var_dump($data);
        $insert = $this->company->insert($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update() {
        //$this->_validate();
        $data = array(
            'COMPANYNAME' => $this->input->post('fcname'),
            'COMPANYNO' => $this->input->post('companyno'),
            'IP' => $this->input->ip_address(),
        );

        $insert = $this->company->update($this->input->post('fccode'), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_edit($fccode) {
        $data = $this->company->edit($fccode);

        echo json_encode($data);
    }

    public function ajax_delete_index($fccode) {
        $this->company->delete_index($fccode);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('fccode') == '') {
            $data['inputerror'][] = 'Company Code ';
            $data['error_string'][] = 'Company Code is required';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}

?>