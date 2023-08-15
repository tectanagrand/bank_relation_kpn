<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class MaterialSubGroupModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    protected $table = 'MATERIAL_SUBGROUP';
    protected $fillable;

    public function ShowData() {
        $this->fillable = ['M.FCCODE', 'M.FCNAME', 'M.DESCRIPTION', 'M.ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table M")
                        ->order_by('M.FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($FCCODE) {
        $this->fillable = ['M.FCCODE', 'M.FCNAME', 'M.DESCRIPTION', 'M.ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table M")
                        ->where(['M.FCCODE' => $FCCODE])
                        ->order_by('M.FCNAME')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {
        $this->fillable = ['M.FCCODE', 'M.FCNAME', 'M.DESCRIPTION', 'M.ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table M")
                        ->where(['M.ISACTIVE' => 'TRUE'])
                        ->order_by('M.FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->table WHERE FCCODE = ?";
            $Cek = $this->db->query($SQL, [$Data['FCCODE']]);
            if ($Cek->num_rows() > 0 && $Data['ACTION'] == 'ADD') {
                throw new Exception('Data Already Exists !!');
            } elseif ($Cek->num_rows() > 1 && $Data['ACTION'] == 'EDIT') {
                throw new Exception('Data Already Exists !!');
            }
            $dt = [
                'FCNAME' => $Data['FCNAME'],
                'DESCRIPTION' => $Data['DESCRIPTION'],
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['ACTION'] == 'ADD') {
                $dt['FCCODE'] = strtoupper($Data['FCCODE']);
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result = $result->set($dt)->insert($this->table);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result = $result->set($dt)
                        ->where(['FCCODE' => strtoupper($Data['FCCODE'])])
                        ->update($this->table);
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
            $result = $this->db->delete($this->table, ['FCCODE' => $Data['FCCODE']]);
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Deleted !!'
                ];
            } else {
                throw new Exception('Deleted Failed !!');
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
