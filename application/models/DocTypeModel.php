<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class DocTypeModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    protected $table = 'DOCTYPE';
    protected $tableDT = 'DOCTEMPLATE';
    protected $tableR = 'DOCTYPE_REQ';
    protected $fillable;

    public function ShowData() {
        $this->fillable = ['FCCODE', 'FCNAME', 'DESCRIPTION', 'CASHFLOWTYPE', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($FCCODE) {
        $this->fillable = ['FCCODE', 'FCNAME', 'DESCRIPTION', 'CASHFLOWTYPE', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['FCCODE' => $FCCODE])
                        ->order_by('FCNAME')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {
        $this->fillable = ['FCCODE', 'FCNAME'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['ISACTIVE' => 'TRUE'])
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }
    
    public function GetDataActiveHead() {
        $this->fillable = ['FCCODE', 'FCNAME'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['ISACTIVE' => 'TRUE', "FCCODE != 'INV'" => NULL, "FCCODE != 'INV_AR'" => NULL])
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetListSystem($FCCODE) {
        $this->fillable = 'ES.FCCODE, ES.FCNAME, ES.DESCRIPTION, DECODE(CE.TEMPLCODE, NULL, 0, 1) AS ISACTIVE';
        $SQL = "SELECT $this->fillable
                  FROM $this->tableDT ES
                  LEFT JOIN $this->tableR CE 
                         ON CE.TEMPLCODE = ES.FCCODE
                        AND CE.DOCTYPECODE = ?
                  WHERE ES.ISACTIVE = 'TRUE' 
                 ORDER BY ES.FCNAME";
        $query = $this->db->query($SQL, [$FCCODE])->result();
        $this->db->close();
        return $query;
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
                'CASHFLOWTYPE' => $Data['CASHFLOWTYPE'],
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
            if ($Data['ACTION'] == 'ADD') {
                $dt['FCCODE'] = strtoupper($Data['FCCODE']);
                $dt['FCENTRY'] = $Data['USERNAME'];
                $Data['FCCODE'] = strtoupper($Data['FCCODE']);
                $result1 = $result1->set($dt)->insert($this->table);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result1 = $result1->set($dt)
                        ->where(['FCCODE' => strtoupper($Data['FCCODE'])])
                        ->update($this->table);
            }
            $cek = $this->db->query("SELECT * FROM $this->tableR WHERE DOCTYPECODE = ?", [$Data['FCCODE']])->num_rows();
            if ($cek > 0) {
                $updatesub = $this->db->delete($this->tableR, ['DOCTYPECODE' => $Data['FCCODE']]);
            } else {
                $updatesub = 1;
            }
            
            $result2 = FALSE;
            if ($updatesub) {
                if (array($Data['DATA']) && $Data['DATA'] != 0) {
                    foreach ($Data['DATA'] as $dt) {
                        $datadetail = [
                            'DOCTYPECODE' => $Data['FCCODE'],
                            'TEMPLCODE' => $dt['FCCODE'],
                            'FCENTRY' => $Data['USERNAME'],
                            'FCEDIT' => $Data['USERNAME'],
                            'FCIP' => $Location
                        ];
                        $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                        ->set($datadetail)->insert($this->tableR);
                    }
                } else {
                    $result2 = TRUE;
                }
            } else {
                throw new Exception("Data Save Failed !!");
            }

            if ($result1 && $result2) {
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
            $result = FALSE;
            $result1 = $this->db->delete($this->table, ['FCCODE' => $Data['FCCODE']]);
            $result2 = $this->db->delete($this->tableR, ['DOCTYPECODE' => $Data['FCCODE']]);
            if ($result1 && $result2) {
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
