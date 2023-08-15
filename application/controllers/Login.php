<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->library(array('user_agent'));
    }

    public function index() {
        ini_set('display_errors', 'On');
        if ($this->session->userdata('status') <> 'login') {
            $this->load->view('login_view');
        } else {
            redirect(site_url("/"));
        }
    }

    function aksi_login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $where = array(
            'FCCODE' => $username,
            'FCPASSWORD' => md5($password)
        );
        $cek = $this->Login_model->cek_login("USERS_TAB", $where)->row();

        if (empty($cek)) {
            $this->session->set_flashdata('error', 'Username or Password Wrong');
            redirect('login');
        } else {
            // if($username == "ERPKPN" ||  $cek->FCCODE == "ERPKPN"){
                if ($username == $cek->FCCODE && md5($password) == $cek->FCPASSWORD) {
                 $this->set_session($cek);
                     redirect(site_url('/MstRoleAccess'));
                 }else{
                     redirect(site_url('/'));    
                 }
            //$this->session->set_flashdata('error', 'UNDER MAINTENANCE EXPORTING DATABASE, PLEASE COMEBACK LATER!');
             // } else {
             //    $this->session->set_flashdata('error', 'WEBSITE PINDAH KE 172.27.7.193 ');
             //    redirect('login');
             // }
        }
    }

    function set_session($cek) {
        $userdata = $this->Login_model->get_userdata($cek->FCCODE);

        $data_session = array(
            'FCCODE' => $cek->FCCODE,
            'username' => $cek->FCCODE,
            'status' => "login",
            'fullname' => $cek->FULLNAME,
            'fcba' => $userdata['FCCODE'],
            'DEPARTMENT' => $cek->DEPARTMENT,
            'status_fc'  => 0
        );
        //var_dump($userdata['FCCODE']);
        //exit;
        $this->session->set_userdata($data_session);
    }

    function logout() {
        $this->session->sess_destroy();
        redirect(site_url('login'));
    }

}

?>