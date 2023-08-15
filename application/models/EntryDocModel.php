<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class EntryDocModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    protected $table = 'CASHFLOWSOURCE';

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $dt = [
                'COMPANY' => $Data['COMPANY'],
                'BUSINESSUNIT' => $Data['BUSINESSUNIT'],
                'DEPARTMENT' => $Data['DEPARTMENT'],
                'DOCTYPE' => $Data['DOCTYPE'],
                'DOCNUMBER' => $Data['DOCNUMBER'],
                'DOCREF' => $Data['DOCREF'],
                'MATERIAL' => $Data['MATERIAL'],
                'VENDOR' => $Data['VENDOR'],
                'TRANS_LOC' => $Data['TRANS_LOC'],
                'PAYTERM' => $Data['PAYTERM'],
                'AMOUNT_INCLUDE_VAT' => $Data['AMOUNT_INCLUDE_VAT'],
                'AMOUNT_PPH' => $Data['AMOUNT_PPH'],
                'ISACTIVE' => 'TRUE',
                'FCENTRY' => $Data['USERNAME'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $res = $this->db
                    ->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['DOCDATE'] == NULL || $Data['DOCDATE'] == '') {
                $res = $res->set('DOCDATE', 'NULL', false);
            } else {
                $res = $res->set('DOCDATE', "TO_DATE('" . $Data['DOCDATE'] . "','mm/dd/yyyy')", false);
            }
            if ($Data['BASELINEDATE'] == NULL || $Data['BASELINEDATE'] == '') {
                $res = $res->set('BASELINEDATE', 'NULL', false);
            } else {
                $res = $res->set('BASELINEDATE', "TO_DATE('" . $Data['BASELINEDATE'] . "','mm/dd/yyyy')", false);
            }
            $dt['ID'] = $this->uuid->v4();
            $res = $res->set($dt)->insert($this->table);
            
            if ($res) {
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
}