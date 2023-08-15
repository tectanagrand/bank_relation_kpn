<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class BusinessUnitModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    protected $table = 'BUSINESSUNIT';
    protected $tableES = 'EXTSYSTEM';
    protected $tableCE = 'BUSINESSUNIT_EXTSYS';
    protected $tableC = 'COMPANY';
    // protected $tableCD = 'COMPANY_DEPART';
    protected $fillable;

    public function loadBusinessUnit($q){
        $query = "SELECT ID,FCCODE as TEXT, COMPANY FROM $this->table WHERE COMPANY = '$q'";
        
        $res = $this->db->query($query);
        
        return $res->result(); 
    }

    public function ShowData() {
        $this->fillable = [$this->tableC.'.COMPANYNAME AS COMPANY', $this->table.'.FCCODE', $this->table.'.FCNAME', $this->table.'.DESCRIPTION', $this->table.'.ISACTIVE', $this->table.'.ID'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->join($this->tableC, $this->tableC.'.ID = '.$this->table.'.COMPANY')
                        ->order_by($this->table.'.ID')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($COMPANYCODE) {
        $this->fillable = ['ID', 'COMPANY', 'FCCODE', 'FCNAME', 'DESCRIPTION', 'ISACTIVE', 'COMPANYGROUP', 'COMPANY_SUBGROUP', 'REGION', 'REGIONGROUP'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['ID' => $COMPANYCODE])
                        ->order_by('ID')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {
        $this->fillable = ['ID', 'FCNAME', 'FCCODE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['ISACTIVE' => 'TRUE'])
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetDataAjax($company) {
        $this->fillable = ['ID', 'FCNAME', 'FCCODE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->table)
                        ->where(['ISACTIVE' => 'TRUE', 'COMPANY' => $company])
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetListSystem($ID) {
        $this->fillable = "ES.FCCODE, ES.FCNAME, ES.DESCRIPTION, DECODE(CE.EXTSYSTEM, NULL, 0, 1) AS ISACTIVE, CE.EXTSYSBUSINESSUNITCODE";
        $SQL = "SELECT $this->fillable
                  FROM $this->tableES ES
                  LEFT JOIN $this->tableCE CE 
                         ON CE.EXTSYSTEM = ES.FCCODE
                        AND CE.BUSINESSUNIT = ?
                  WHERE ES.ISACTIVE = 'TRUE' 
                 ORDER BY ES.FCNAME";
        $query = $this->db->query($SQL, [$ID])->result();
        $this->db->close();
        return $query;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $dt = [
                'COMPANY' => $Data['COMPANY'],
                'COMPANYGROUP' => $Data['COMPANYGROUP'],
                'COMPANY_SUBGROUP' => $Data['COMPANY_SUBGROUP'],
                'REGION' => $Data['REGION'],
                'REGIONGROUP' => $Data['REGIONGROUP'],
                'FCCODE' => $Data['FCCODE'],
                'FCNAME' => $Data['FCNAME'],
                'DESCRIPTION' => (isset($Data['DESCRIPTION'])) ? $Data['DESCRIPTION'] : NULL,
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);

            if ($Data['ACTION'] == 'ADD') {
                $Data['ID'] = $this->uuid->v4();
                $dt['ID']  = $Data['ID'];
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result1 = $result1->set($dt)->insert($this->table);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result1 = $result1->set($dt)
                        ->where(['ID' => $Data['ID']])
                        ->update($this->table);
            }

            //EXTSYSTEM
            $cek = $this->db->query("SELECT * FROM $this->tableCE WHERE BUSINESSUNIT = ?", [$Data['ID']])->num_rows();
            if ($cek > 0) {
                $updatesub = $this->db->delete($this->tableCE, ['BUSINESSUNIT' => $Data['ID']]);
            } else {
                $updatesub = 1;
            }
            $result2 = FALSE;
            if ($updatesub) {
                if (count($Data['DATA']) > 0 && $Data['DATA'] != 0) {
                    foreach ($Data['DATA'] as $dt) {
                        $datadetail = [
                            'BUSINESSUNIT' => $Data['ID'],
                            'EXTSYSTEM' => $dt['FCCODE'],
                            'EXTSYSBUSINESSUNITCODE' => $dt['EXTSYSBUSINESSUNITCODE'],
                            'FCENTRY' => $Data['USERNAME'],
                            'FCEDIT' => $Data['USERNAME'],
                            'FCIP' => $Location
                        ];
                        $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                        ->set($datadetail)->insert($this->tableCE);
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
            $result1 = $this->db->delete($this->tableCE, ['BUSINESSUNIT' => $Data['ID']]);
            $result = $this->db->delete($this->table, ['ID' => $Data['ID']]);
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