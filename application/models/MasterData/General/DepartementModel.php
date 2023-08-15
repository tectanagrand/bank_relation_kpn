<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DepartementModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function ShowData() {
        $this->fillable = ['FCCODE', 'FCNAME', 'DESCRIPTION', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->DEPARTMENT)
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function ShowDataCat() {
        $this->fillable = ['DEPARTMENT', 'MATERIALGROUP', 'FORECAST_CATEGORY'];
        $result = $this->db->select($this->fillable)
                        ->from($this->DEPARTMENT_CATEGORY)
                        ->order_by('DEPARTMENT')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetActiveDepartement($IDU) {
        $this->fillable = "DPT.FCCODE, DPT.FCNAME, DECODE(UDPT.DEPARTMENT, NULL, 0, 1) AS ISACTIVE";
        $SQL = "SELECT $this->fillable
                  FROM $this->DEPARTMENT DPT
                  LEFT JOIN $this->USER_DEPART UDPT 
                         ON UDPT.DEPARTMENT = DPT.FCCODE
                        AND UDPT.FCCODE = ?
                  WHERE DPT.ISACTIVE = 'TRUE' 
                 ORDER BY DPT.FCNAME";
        $result = $this->db->query($SQL, [$IDU])->result();
        $this->db->close();
        return $result;
    }

    public function GetData($FCCODE) {
        $this->fillable = ['FCCODE', 'FCNAME', 'DESCRIPTION', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->DEPARTMENT)
                        ->where(['FCCODE' => $FCCODE])
                        ->order_by('FCNAME')->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataCat($DEPARTMENT, $MATERIALGROUP, $FORECAST_CATEGORY) {
        $this->fillable = ['DEPARTMENT', 'MATERIALGROUP', 'FORECAST_CATEGORY'];
        $result = $this->db->select($this->fillable)
                        ->from($this->DEPARTMENT_CATEGORY)
                        ->where(['DEPARTMENT' => $DEPARTMENT, 'MATERIALGROUP' => $MATERIALGROUP, 'FORECAST_CATEGORY' => $FORECAST_CATEGORY])->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {
        $this->fillable = ['FCCODE', 'FCNAME'];
        $result = $this->db->select($this->fillable)
                        ->from($this->DEPARTMENT)
                        ->where(['ISACTIVE' => 'TRUE'])
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->DEPARTMENT WHERE FCCODE = ?";
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
                $result = $result->set($dt)->insert($this->DEPARTMENT);
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result = $result->set($dt)
                        ->where(['FCCODE' => strtoupper($Data['FCCODE'])])
                        ->update($this->DEPARTMENT);
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

    public function SaveCat($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;

            $SQL = "SELECT * FROM $this->DEPARTMENT_CATEGORY WHERE DEPARTMENT = ? AND MATERIALGROUP = ? AND FORECAST_CATEGORY = ?";
            $Cek = $this->db->query($SQL, [$Data['DEPARTMENT'], $Data['MATERIALGROUP'], $Data['FORECAST_CATEGORY']]);

            if ($Cek->num_rows() > 0 && $Data['ACTION'] == 'ADD') {
                throw new Exception('Data Already Exists !!');

            } elseif ($Cek->num_rows() > 1 && $Data['ACTION'] == 'EDIT') {
                throw new Exception('Data Already Exists !!');
            }

            $dt = [
                'DEPARTMENT' => $Data['DEPARTMENT'],
                'MATERIALGROUP' => $Data['MATERIALGROUP'],
                'FORECAST_CATEGORY' => $Data['FORECAST_CATEGORY'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];

            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);

            if ($Data['ACTION'] == 'ADD') {
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result = $result->set($dt)->insert($this->DEPARTMENT_CATEGORY);

            } elseif ($Data['ACTION'] == 'EDIT') {
                $result = $result->set($dt)
                        ->where(['DEPARTMENT' => $Data['OLD_DEPARTMENT'], 'MATERIALGROUP' => $Data['OLD_MATERIALGROUP'], 'FORECAST_CATEGORY' => $Data['OLD_FORECAST_CATEGORY']])
                        ->update($this->DEPARTMENT_CATEGORY);
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
            $result = $this->db->delete($this->DEPARTMENT, ['FCCODE' => $Data['FCCODE']]);
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

    public function DeleteCat($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = $this->db->delete($this->DEPARTMENT_CATEGORY, ['DEPARTMENT' => $Data['DEPARTMENT'], 'MATERIALGROUP' => $Data['MATERIALGROUP'], 'FORECAST_CATEGORY' => $Data['FORECAST_CATEGORY']]);

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
