<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Controller for codeigniter crud using ajax application.
class ExtSystem extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ExtSystem_model', 'extsystem');
        //$this->load->model('Sidebar_model');
    }

    public function index() {
        $this->load->helper(array('url', 'file'));
        $data['content_js'] = 'applicationmaster/extsystem/extsystem_js';
        $data['content'] = 'applicationmaster/extsystem/extsystem_view';
        $data['sidebar'] = 'sidebar_view';
        //$data['list_sidebar'] = $this->Sidebar_model->list_sidebar();		
        $this->load->view('template', $data);
    }

    public function ajax_list() {
        $list = $this->extsystem->get_datatables();
        $check = "";
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $extsystem) {
            $no++;
            $row = array();
            $row[] = $extsystem->FCCODE;
            $row[] = $extsystem->FCNAME;
            $row[] = $extsystem->DESCRIPTION;

            if ($extsystem->ISACTIVE == 'TRUE') {
                $row[] = "<span class='label label-green' style='color:white'>Active</span>";
            } else {
                $row[] = "<span class='label label-danger' style='color:white'>Not Active</span>";
            }

            $row[] = '<a href="' . site_url('ExtSystem?type=edit&fccode=' . $extsystem->FCCODE . '') . '" title="Edit" ><i class="fa fa-edit" aria-hidden="true"></i></a>
						  &nbsp; <a  href="javascript:void(0)" title="Delete" onclick="delete_index(' . "'" . $extsystem->FCCODE . "'" . ')"> <i class="fa fa-trash" aria-hidden="true"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->extsystem->count_all(),
            "recordsFiltered" => $this->extsystem->count_filtered(),
            "data" => $data,
        );

        //output to json format		
        echo json_encode($output);
    }

    public function ajax_add() {
        $this->_validate();
        $data = array(
            'FCCODE' => $this->input->post('fccode'),
            'FCNAME' => $this->input->post('fcname'),
            'DESCRIPTION' => $this->input->post('description'),
            'ISACTIVE' => $this->input->post('isactive') == 'on' ? 'TRUE' : 'FALSE',
            'IP' => $this->input->ip_address(),
        );

        //var_dump($data);
        $insert = $this->extsystem->insert($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update() {
        //$this->_validate();
        $data = array(
            'FCNAME' => $this->input->post('fcname'),
            'DESCRIPTION' => $this->input->post('description'),
            'ISACTIVE' => $this->input->post('isactive'),
            'IP' => $this->input->ip_address(),
        );

        $insert = $this->extsystem->update($this->input->post('fccode'), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_edit($fccode) {
        $data = $this->extsystem->edit($fccode);

        echo json_encode($data);
    }

    public function ajax_delete_index($fccode) {
        $this->extsystem->delete_index($fccode);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('fccode') == '') {
            $data['inputerror'][] = 'Ext. System Code ';
            $data['error_string'][] = 'Ext. System Code is required';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}

?>