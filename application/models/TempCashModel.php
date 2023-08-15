<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class TempCashModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    protected $table = 'CASHFLOW_TEMP';

    public function ShowData() {
        $this->fillable = ["CT.TEMPCODE", "CT.TEMPNAME", "CT.TEMPPARENT", "CTD.TEMPNAME AS TEMPPARENTNAME", "CT.TEMPGROUP", "CT.TEMPLEVEL", '"DECODE"'." (CT.TEMPGROUP, 1, 'Cash In', 'Cash Out')AS TEMPGROUPNAME", "CT.TEMPTYPE", '"DECODE"'." (CT.TEMPTYPE, 1, 'Detail', 'Header') AS TEMPTYPENAME", "CT.ISACTIVE"];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table CT")
                        ->join("$this->table CTD", 'CTD.TEMPCODE = CT.TEMPPARENT', 'left')
                        ->where(["CT.ISACTIVE" => 1])
                        ->order_by('CT.TEMPCODE')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($ID) {
        $this->fillable = ['*'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['TEMPCODE' => $ID])->get()->row();
        $this->db->close();
        return $result;
    }

    public function ShowParent() {
        $this->fillable = ['TEMPCODE', 'TEMPNAME'];
        $result = $this->db->select($this->fillable)
                            ->from($this->table)
                            ->where(["ISACTIVE" => 1])
                            ->order_by("TEMPCODE")->get()->result_array();
        $this->db->close();

        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            if ($Data['TEMPPARENT']) {
                $cek = $this->db->query("SELECT * FROM $this->table WHERE TEMPCODE = ?", [$Data['TEMPPARENT']])->row();
                $Data['TEMPLEVEL'] = $cek->TEMPLEVEL;

            } else {
                $Data['TEMPLEVEL'] = -1;

            }
            
            $dt = [
                'TEMPNAME' => $Data['TEMPNAME'],
                'TEMPPARENT' => $Data['TEMPPARENT'],
                'TEMPGROUP' => $Data['TEMPGROUP'],
                'TEMPLEVEL' => $Data['TEMPLEVEL']+1,
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];

            if ($Data['ACTION'] == 'ADD') {
                $Data['TEMPCODE'] = $Data['TEMPCODE'];
                $dt['TEMPCODE'] = $Data['TEMPCODE'];
                $dt['FCENTRY'] = $Data['USERNAME'];
                $dt['CREATED_LOC'] = $Location;
                $result =  $this->db->set($dt)->insert($this->table);

            } elseif ($Data['ACTION'] == 'EDIT') {
                $result = $this->db->set($dt)
                        ->set('LASTUPDATE', "SYSDATE", false)
                        ->where(['TEMPCODE' => $Data['TEMPCODE']])
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
            $result = FALSE;
            $result = $this->db->delete($this->table, ['TEMPCODE' => $Data['TEMPCODE']]);
            if ($result) {
                $result = TRUE;
            }
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