<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ForecastAuthModel extends BaseModel {

    var $column_order = array(null, 'FORECAST_HIST.FCBA', 'FORECAST_HIST.DOCNUMBER', 'FORECAST_HIST.DOCDATE', 'FORECAST_HIST.CURRENTAUTHORIZELEVEL', 'FORECAST_HIST.LASTUPDATE', null); //set column field database for datatable orderable
	var $table = 'FORECAST_HIST';
	var $column_search = array('FORECAST_HIST.FCBA', 'FORECAST_HIST.DOCNUMBER', 'FORECAST_HIST.DOCDATE', 'FORECAST_HIST.CURRENTAUTHORIZELEVEL','FORECAST_HIST.LASTUPDATE'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('FORECAST_HIST.DOCNUMBER, FORECAST_HIST.CURRENTAUTHORIZELEVEL' => 'desc');
	
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
    }
	
	private function _get_datatables_query()
    {
		
		$this->db->select('FORECAST_HIST.FCBA, FORECAST_HIST.DOCNUMBER, TO_CHAR(FORECAST_HIST.DOCDATE,\'mm-dd-yyyy\')DOCDATE, FORECAST_HIST.CURRENTAUTHORIZELEVEL,
						  TO_CHAR(FORECAST_HIST.LASTUPDATE,\'mm/dd/yyyy HH:MM PM\')LASTUPDATE,
						  max(FORECAST_HIST.CURRENTAUTHORIZELEVEL) over (partition by FORECAST_HIST.FCBA, FORECAST_HIST.DOCNUMBER, FORECAST_HIST.DOCDATE) MAXLVL,
						  FORECAST_FIX.STATE, AUTHORIZESTRUCTURE.FCUSERCODE');
		$this->db->from('FORECAST_HIST');
		$this->db->join('FORECAST_FIX',
						'FORECAST_HIST.FCBA = FORECAST_FIX.FCBA 
						 AND FORECAST_HIST.DOCNUMBER = FORECAST_FIX.DOCNUMBER 
						 AND FORECAST_HIST.DOCDATE = FORECAST_FIX.DOCDATE','INNER');
		$this->db->join('AUTHORIZESTRUCTURE',
						'FORECAST_HIST.FCBA = AUTHORIZESTRUCTURE.FCBA 
						 AND AUTHORIZESTRUCTURE.AUTHORIZETEMPLATECODE = \'FINGERPRINT_BENGKULU\'
						 AND FORECAST_HIST.CURRENTAUTHORIZELEVEL = AUTHORIZESTRUCTURE.AUTHORIZELEVELCODE','INNER');
		$this->db->where('FORECAST_HIST.FCBA', $this->session->userdata('fcba'));
		//$this->db->order_by("FORECAST_HIST.FCBA, FORECAST_HIST.DOCNUMBER, FORECAST_HIST.DOCDATE, FORECAST_HIST.CURRENTAUTHORIZELEVEL desc");
        $i = 0;
		
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
	
	function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
	
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
	
	public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}