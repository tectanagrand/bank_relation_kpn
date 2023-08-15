<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class MstDeptMaterialSpoModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }


    protected $table = 'DEPARTMENT_MATERIAL_SPO';
    protected $fillable;

    public function ShowData() {
        $result = $this->db->select("*")
                        ->from("DEPARTMENT_MATERIAL_SPO")
                        ->order_by('DEPARTMENT ASC')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($ID) {
        $result = $this->db->select("*")->from("$this->table")->where(['ID' => $ID])->get()->row();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $dt = [
                'BUTYPE' => strtoupper($Data['BUTYPE']),
                'MATERIAL' => strtoupper($Data['MATERIAL']),
                'MATERIALGROUP' => strtoupper($Data['MATERIALGROUP']),
                'DEPARTMENT' => $Data['DEPARTMENT'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['ACTION'] == 'ADD') {
                // $Data['ID'] = $this->uuid->v4();
                $dt['ID'] = $this->uuid->v4();
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result1 = $result1->set($dt)->insert($this->table);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result1 = $result1->set($dt)
                        ->where(['ID' => $Data['ID']])
                        ->update($this->table);
            }
            
            if ($result1) {
                $result = TRUE;
            }
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

    public function Delete($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result1 = $this->db->delete($this->table, ['ID' => $Data['ID']]);
            
            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Deleted !!'
                ];
            } else {
                throw new Exception('Delete Failed !!');
            }
        } catch (\Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

}
