<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class Login_model extends CI_Model{	
	function cek_login($table,$where){		
		return $this->db->get_where($table,$where);
	}
	
	function get_userdata($username){
		$query = $this->db->query("SELECT COMPANY.COMPANYCODE FCCODE,
								   COMPANY.COMPANYNAME FCNAME
							  FROM COMPANY
								   INNER JOIN USER_COMPANY_TAB ON COMPANY.COMPANYCODE = USER_COMPANY_TAB.COMPANYCODE AND USER_COMPANY_TAB.ISDEFAULT = 'TRUE'
							 WHERE USER_COMPANY_TAB.USERCODE = '".$username."'");
        return $query->row_array();
	}
}
?>