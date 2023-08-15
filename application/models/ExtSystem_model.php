<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class ExtSystem_model extends CI_Model {

    var $column_order = array('EXTSYSTEM.FCCODE', 'EXTSYSTEM.FCNAME', 'EXTSYSTEM.DESCRIPTION', 'EXTSYSTEM.ISACTIVE', null);
    var $table = 'EXTSYSTEM';
    var $column_search = array('EXTSYSTEM.FCCODE', 'EXTSYSTEM.FCNAME', 'EXTSYSTEM.DESCRIPTION', 'EXTSYSTEM.ISACTIVE');
    var $order = array('EXTSYSTEM.FCCODE' => 'Asc'); // default order 

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    private function _get_datatables_query() {
        $this->db->select('EXTSYSTEM.FCCODE, EXTSYSTEM.FCNAME, EXTSYSTEM.DESCRIPTION, EXTSYSTEM.ISACTIVE');
        $this->db->from('EXTSYSTEM');
        $i = 0;

        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search

                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function insert($data) {
        $query = $this->db->query("INSERT INTO EXTSYSTEM (FCCODE, FCNAME, DESCRIPTION, ISACTIVE, FCENTRY, FCIP, LASTUPDATE, LASTTIME) 
		 VALUES ('" . $data["FCCODE"] . "','" . $data["FCNAME"] . "','" . $data["DESCRIPTION"] . "','" . $data["ISACTIVE"] . "','" . $this->session->userdata('username') . "','" . $data["IP"] . "',SYSDATE,to_char(sysdate,'HH:MI'))");
    }

    public function update($fccode, $data) {
        $isactive = "TRUE";

        if ($data["ISACTIVE"] == null) {
            $isactive = "FALSE";
        }

        $query = $this->db->query("UPDATE EXTSYSTEM SET FCNAME = '" . $data["FCNAME"] . "', DESCRIPTION = '" . $data["DESCRIPTION"] . "', ISACTIVE = '" . $isactive . "', 
		FCEDIT = '" . $this->session->userdata('username') . "', FCIP = '" . $data["IP"] . "', LASTUPDATE = sysdate, LASTTIME = to_char(sysdate,'HH:MI') WHERE FCCODE = '" . $fccode . "'");
    }

    public function edit($fccode) {
        $query = $this->db->query("SELECT * FROM EXTSYSTEM Where FCCODE = '" . $fccode . "'");

        return $query->row();
    }

    public function delete_index($fccode) {
        $this->db->where('EXTSYSTEM.FCCODE', $fccode);
        $this->db->delete($this->table);
    }

    public function controljob() {
        $query = $this->db->query("select FCCODE, FCNAME from Job where fcBA = '" . $this->session->userdata('fcba') . "' Order by FCCODE");
        return $query->result();
    }

}

?>