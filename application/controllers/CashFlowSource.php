<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Controller for codeigniter crud using ajax application.
class CashFlowSource extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('AttendanceSubmission_model','cashflowsource');
		
		//$this->load->model('Sidebar_model');
    }

	public function index()
    {
        $this->load->helper(array('url','file'));
		$data['content'] = 'applicationmodule/cashflow/cashflowsource_view';
		$data['sidebar'] = 'sidebar_view';
		//$data['list_sidebar'] = $this->Sidebar_model->list_sidebar();		
		$this->load->view('template',$data);
    }
	
	public function ajax_list_index()
    {	
        $list = $this->cashflowsource->get_datatables();
		$check = "";
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $cashflowsource) {
            $no++;
            $row = array();
			$row[] = $cashflowsource->FCBA;
			$row[] = $cashflowsource->DOCNUMBER;
			$row[] = $cashflowsource->DOCDATE; 
			
			if ($cashflowsource->STATE == '0'){
				$row[] = "<span class='label label-warning' style='color:white'>Planned</span>";
			}else if ($cashflowsource->STATE == '1'){
				$row[] = "<span class='label label-green' style='color:white'>On Progress</span>";
			}else if ($cashflowsource->STATE == '2'){
				$row[] = "<span class='label label-success' style='color:white'>Approved</span>";
			}else{
				$row[] = "<span class='label label-yellow' style='color:white'>Rejected</span>";
			}
			if ($cashflowsource->STATE == '0'){
				$row[] = '<a class="btn btn-success btn-icon btn-circle btn-sm" href="'.site_url('AttendanceSubmission?type=edit&fcba='.$cashflowsource->FCBA.'&docnumber='.$cashflowsource->DOCNUMBER.'&docdate='.$cashflowsource->DOCDATE.'&state='.$cashflowsource->STATE.'').'" title="Edit" ><i class="fa fa-edit" aria-hidden="true"></i></a>
						 <a  class="btn btn-danger btn-icon btn-circle btn-sm"  href="javascript:void(0)" title="Delete" onclick="delete_index('."'".$cashflowsource->DOCNUMBER."'".','."'".$cashflowsource->FCBA."'".')"> <i class="fa fa-trash" aria-hidden="true"></i></a>';
			}else{
				$row[] = '';
			}
			
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->cashflowsource->count_all(),
                        "recordsFiltered" => $this->cashflowsource->count_filtered(),
                        "data" => $data,
                );
		
        //output to json format		
        echo json_encode($output);		
    }
	
	public function ajax_list_detail($docnumber)
    {		
        $list = $this->cashflowsourcedet->get_datatables(base64_decode($docnumber));
		$check = "";
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $cashflowsourcedet) {
            $no++;
            $row = array();
			$row[] = $no;
			$row[] = $cashflowsourcedet->FCBA;
			$row[] = $cashflowsourcedet->EMPNO;
			$row[] = $cashflowsourcedet->EMPNAME; 
			$row[] = $cashflowsourcedet->TDATE; 
			$row[] = $cashflowsourcedet->TIMEIN;
			$row[] = $cashflowsourcedet->TIMEOUT;
			$row[] = $cashflowsourcedet->STARTTIME;
			$row[] = $cashflowsourcedet->ENDTIME;
			$row[] = $cashflowsourcedet->OVERTIME;
			
			if ($cashflowsourcedet->OVERTIME_TYPE == 'FIRST')
			{
				$row[] = "Before Time In";
			} else {
				$row[] = "After Time Out";
			}
			
			$row[] = $cashflowsourcedet->BA_REFERENCE;
			$row[] = '<a class="btn btn-success btn-icon btn-circle btn-sm" href="javascript:void(0)" onclick="edit_detail('."'".$cashflowsourcedet->DOCNUMBER."'".','."'".$cashflowsourcedet->FCBA."'".','."'".$cashflowsourcedet->EMPNO."'".','."'".$cashflowsourcedet->TDATE."'".')" title="Edit" ><i class="fa fa-edit" aria-hidden="true"></i></a>
					  <a  class="btn btn-danger btn-icon btn-circle btn-sm"  href="javascript:void(0)" title="Delete" onclick="delete_detail('."'".$cashflowsourcedet->DOCNUMBER."'".','."'".$cashflowsourcedet->FCBA."'".','."'".$cashflowsourcedet->EMPNO."'".','."'".$cashflowsourcedet->TDATE."'".')"> <i class="fa fa-trash" aria-hidden="true"></i></a>';
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->cashflowsourcedet->count_all(),
                        "recordsFiltered" => $this->cashflowsourcedet->count_filtered(base64_decode($docnumber)),
                        "data" => $data,
                );
		
        //output to json format		
        echo json_encode($output);		
    }
	
	public function ajax_list($fcba, $division, $attddate)
    {	
        $list = $this->attendancetab->get_datatables($fcba, $division, $attddate);
		$check = "";
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $presensi) {
            $no++;
            $row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$presensi->FCBA.'|'.$presensi->TDATE.'|'.$presensi->EMPNO.'">';
			$row[] = $presensi->FCBA;
			$row[] = $presensi->EMPNO;
			$row[] = $presensi->EMPNAME; 
			$row[] = $presensi->TDATE; 
			$row[] = $presensi->TIMEIN;
			$row[] = $presensi->TIMEOUT;
			$row[] = '';
			$row[] = '';
			
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->attendancetab->count_all(),
                        "recordsFiltered" => $this->attendancetab->count_filtered($fcba, $division, $attddate),
                        "data" => $data,
                );
		
        //output to json format		
        echo json_encode($output);		
    }
	
	public function ajax_get_docnumber()
    {
		$data = $this->cashflowsource->get_docnumber($this->session->userdata('fcba'));
		
		echo json_encode($data);
	}
	
	public function ajax_add()
    {
		$this->_validate();
        $data = array(
				'DOCNUMBER' => $this->input->post('documentnumber'),
                'DOCDATE' => $this->input->post('documentdate'),
            );
			
		$insert = $this->cashflowsource->insert($data);
        echo json_encode(array("status" => TRUE,"DOCNUMBER" => $this->input->post('documentnumber'), "DOCDATE" => $this->input->post('documentdate'), "FCBA" => $this->session->userdata('fcba')));
    }
	
	public function ajax_addnonfp()
    {
		$this->_validate();
        $data = array(
				'FCBA' => $this->session->userdata('fcba'),
				'DOCNUMBER' => $this->input->post('documentnumber'),
                'DOCDATE' => $this->input->post('documentdate'),
				'EMPNO' => $this->input->post('nonfpemployee'),
                'TDATE' => $this->input->post('nonfpdate'),
				'TYPEATTEND' => $this->input->post('nonfpattendancetype'),
				'STARTTIME' => $this->input->post('nonfpstarttime'),				
				'ENDTIME' => $this->input->post('nonfpendtime'),
            );
		$insert = $this->cashflowsource->addnonfp($data);
        echo json_encode(array("status" => TRUE));
    }

	public function ajax_authorize()
    {
			$data = array(
				'DOCNUMBER' => $this->input->post('documentnumber'),
                'DOCDATE' => $this->input->post('documentdate'),
            );
		
		$insert = $this->cashflowsource->authorize($data);
		
        echo json_encode(array("status" => TRUE));
    }
	
	public function ajax_update_detail()
	{
		$data = array(
				'OVERTIME' => $this->input->post('overtime'),
				'OVERTIME_TYPE' => $this->input->post('overtimetype'),
				'BA_REFERENCE' => $this->input->post('reason'),
			);
		//var_dump($data);
		//exit;
		$this->cashflowsource->update_detail(array('DOCNUMBER' => $this->input->post('docnumber'), 
														 'FCBA' => $this->input->post('fcba'), 
														 'TDATE' => $this->input->post('tdate'), 
														 'EMPNO' => $this->input->post('empno')), $data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_edit_detail($docnumber, $fcba, $empno, $tdate)
    {
		$data = $this->cashflowsource->get_detail_by_id(base64_decode($docnumber), $fcba, $empno, $tdate);
		
		echo json_encode($data);
    }
	
	public function ajax_approve($fcba, $docnumber, $docdate, $state, $authlevel)
    {
		$data = array(
				'FCBA' => $fcba,
                'DOCNUMBER' => base64_decode($docnumber),
				'DOCDATE' => $docdate,
				'STATE' => $state,
				'AUTHLEVEL' => $authlevel,
            );
		
		$insert = $this->cashflowsource->approve($data);
		
        echo json_encode(array("status" => TRUE));
    }
	
	public function ajax_addemployee()
    {
        $list_id = $this->input->post('id');
		
		foreach ($list_id as $id) {
         $this->cashflowsource->insert_detail($id);
        }		
        echo json_encode(array("status" => TRUE));
    }
	
	public function ajax_businessunit(){
 		$businessunit = $this->cashflowsource->businessunit();
 		echo json_encode($businessunit);
 	}
	
	public function ajax_division($businessunit){
 		$division = $this->cashflowsource->division($businessunit);
 		echo json_encode($division);
 	}
	
	public function ajax_employee($attddate){		
 		$employee = $this->cashflowsource->employee(base64_decode($attddate));
 		echo json_encode($employee);
 	}
	
	public function ajax_attendancetype(){
 		$attendancetype = $this->cashflowsource->attendancetype();
 		echo json_encode($attendancetype);
 	}
	
	public function ajax_delete_index($docnumber, $fcba)
    {
        $this->cashflowsource->delete_index(base64_decode($docnumber), $fcba);
        echo json_encode(array("status" => TRUE));
    }
	
	public function ajax_delete_detail($docnumber, $fcba, $empno, $tdate)
    {
        $this->cashflowsource->delete_detail(base64_decode($docnumber), $fcba, $empno, $tdate);
        echo json_encode(array("status" => TRUE));
    }
	
	private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('documentnumber') == '')
        {
            $data['inputerror'][] = 'Document Number ';
            $data['error_string'][] = 'Document Number  is required';
            $data['status'] = FALSE;
        }
		
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}
?>