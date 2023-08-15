<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class WeekModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    protected $table = 'SETTING_WEEK';
    protected $fillable;

    public function ShowData($param) {
        
        $YEAR = $param['YEAR'];

        if ($YEAR == null || $YEAR == '') {
            $this->fillable = ['YEAR','MONTH', 'MONTHNAME', 'WEEK', 'DATEFROM', 'DATEUNTIL'];
            $result = $this->db->select($this->fillable)
                           ->from($this->table)->get()->result();
        } else {
            $this->fillable = ['YEAR','MONTH', 'MONTHNAME', 'WEEK', 'DATEFROM', 'DATEUNTIL'];
            $result = $this->db->select($this->fillable)
                           ->from($this->table)
                           ->where(['YEAR' => $YEAR],'both')->get()->result();
        }
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            
            $dt = [
                'DATEFROM' => $Data['DATEFROM'],
                'DATEUNTIL' => $Data['DATEUNTIL']
            ];
                    
            $result = $this->db->set($dt)
                    ->where(['MONTH' => $Data['MONTH'], 'WEEK' => $Data['WEEK']])
                    ->update($this->table);
            
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function GetYear(){
        $this->fillable = ['YEAR'];
        $result = $this->db->select($this->fillable)
                        ->distinct($this->fillable)
                        ->from($this->table)
                        ->order_by('YEAR')->get()->result();
        $this->db->close();
        return $result;
    }

}
