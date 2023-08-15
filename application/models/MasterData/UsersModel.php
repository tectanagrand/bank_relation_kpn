<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class UsersModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function ShowData() {
        $this->fillable = ['UT.FCCODE', 'UT.FCPASSWORD', 'UT.FULLNAME', 'UT.USERGROUPID', 'UG.USERGROUPNAME', 'UT.ISACTIVE', "TO_CHAR(UT.VALID_FROM, 'YYYY-MM-DD') AS VALID_FROM",
            "TO_CHAR(UT.VALID_UNTIL, 'YYYY-MM-DD') AS VALID_UNTIL", "UT.USERACCESS", "UA.DETAILNAME AS USERACCESSNAME"];
        $result = $this->db->select($this->fillable)
                        ->from("$this->USERS_TAB UT")
                        ->join("$this->USER_GROUP UG", 'UG.USERGROUPID = UT.USERGROUPID', 'left')
                        ->join("$this->BMCODEDETAIL UA", "UA.DETAILID = UT.USERACCESS AND UA.MASTERID = '000003'", 'left')
                        ->order_by('UT.LASTUPDATE DESC')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($FCCODE) {
        $this->fillable = ['UT.FCCODE', 'UT.FCPASSWORD', 'UT.FULLNAME', 'UT.USERGROUPID', 'UG.USERGROUPNAME', 'UT.ISACTIVE',"UT.DEPARTMENT", "TO_CHAR(UT.VALID_FROM, 'YYYY-MM-DD') AS VALID_FROM",
            "TO_CHAR(UT.VALID_UNTIL, 'YYYY-MM-DD') AS VALID_UNTIL", "UT.USERACCESS", "UA.DETAILNAME AS USERACCESSNAME"];
        $result = $this->db->select($this->fillable)
                        ->from("$this->USERS_TAB UT")
                        ->join("$this->USER_GROUP UG", 'UG.USERGROUPID = UT.USERGROUPID', 'left')
                        ->join("$this->BMCODEDETAIL UA", "UA.DETAILID = UT.USERACCESS AND UA.MASTERID = '000003'", 'left')
                        ->where(['UT.FCCODE' => $FCCODE])
                        ->order_by('UT.FCCODE')->get()->row();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $dt = [
                'FCPASSWORD' => $Data['FCPASSWORD'],
                'FULLNAME' => strtoupper($Data['FULLNAME']),
                'USERGROUPID' => $Data['USERGROUPID'],
                'USERACCESS' => $Data['USERACCESS'],
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAMEUPDATE'],
                'FCIP' => $Location
            ];
            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('VALID_FROM', "TO_DATE('" . $Data["VALID_FROM"] . "','YYYY-MM-DD')", false)
                    ->set('VALID_UNTIL', "TO_DATE('" . $Data["VALID_UNTIL"] . "','YYYY-MM-DD')", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);

            $SQL = "SELECT * FROM  $this->USERS_TAB  WHERE FCCODE = ?";
            $Cek = $this->db->query($SQL, [strtoupper($Data['FCCODE'])]);
            if ($Data['ACTION'] == 'ADD') {
                if ($Cek->num_rows() > 0) {
                    throw new Exception('Data Already Exists !!');
                }
                $dt['FCCODE'] = strtoupper($Data['FCCODE']);
                $dt['FCPASSWORD'] = md5($Data['FCPASSWORD']);
                $dt['FCENTRY'] = $Data['USERNAMEUPDATE'];
                $dt['DEPARTMENT'] = $Data['DEPT'];
                $Data['FCCODE'] = strtoupper($Data['FCCODE']);
                $result = $result->set($dt)->insert($this->USERS_TAB);
            } elseif ($Data['ACTION'] == 'EDIT') {
                if ($Cek->num_rows() <= 0) {
                    throw new Exception('Data Not Found !!');
                } else {
                    foreach ($Cek->result() as $values) {
                        if ($values->FCPASSWORD != $Data['FCPASSWORD']) {
                            $dt['FCPASSWORD'] = md5($Data['FCPASSWORD']);
                        }
                        if($values->DEPARTMENT != $Data['DEPT']){
                            $dt['DEPARTMENT'] = $Data['DEPT'];
                        }
                    }
                    $updateDpt = $this->db->delete($this->USER_DEPART, ['FCCODE' => $Data['FCCODE']]);
                }
                $result = $result->set($dt)
                        ->where(['FCCODE' => strtoupper($Data['FCCODE'])])
                        ->update($this->USERS_TAB);
            }
            if ($result) {
                $result2 = FALSE;
                if (count($Data['USERDpt']) > 0 && $Data['USERDpt'] != 0) {
                    foreach ($Data['USERDpt'] as $value) {
                        $dataDpt = [
                            'FCCODE' => $Data['FCCODE'],
                            'DEPARTMENT' => $value['FCCODEDpt'],
                            'FCENTRY' => $Data['USERNAMEUPDATE'],
                            'FCEDIT' => $Data['USERNAMEUPDATE'],
                            'FCIP' => $Location
                        ];
                        $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                        ->set($dataDpt)->insert($this->USER_DEPART);
                    }
                } else {
                    $result2 = TRUE;
                }
            } else {
                throw new Exception('Data Save Failed !!');
            }

            if ($result && $result2) {

                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
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
//            $result1 = $this->db->delete($this->USERS_TABCE, ['EXTSYSTEMCODE' => $Data['FCCODE']]);
            $result = $this->db->delete($this->USERS_TAB, ['FCCODE' => $Data['FCCODE']]);
//            if ($result1 && $result2) {
//                $result = TRUE;
//            }
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

    public function ChangePassword($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM " . $this->USERS_TAB . " WHERE FCCODE = ?";
            $Cek = $this->db->query($SQL, [$Data['USERNAME']]);
            if ($Cek->num_rows() <= 0) {
                throw new Exception('Data Not Found !!');
            }
            foreach ($Cek->result() as $values) {
                if ($values->FCPASSWORD != md5($Data['PASSWORD'])) {
                    throw new Exception('Sorry, Old Password is Wrong !!');
                } else {
                    $result = $this->db->set([
                                'FCPASSWORD' => md5($Data['NPASSWORD']),
                                'FCEDIT' => $Data['USERNAME'],
                                'FCIP' => $Location
                            ])->set('LASTUPDATE', "SYSDATE", false)
                            ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                            ->where(['FCCODE' => $Data['USERNAME']])
                            ->update($this->USERS_TAB);
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Password has been Successfully Updated !!'
                ];
            } else {
                throw new Exception('Password Change Failed !!');
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

    public function GetUComplaint($ID) {
        $this->load->database();
        $this->fillable = ['UC.*', 'C.CNAME'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->USERS_TABucomplaint UC")
                        ->join("$this->USERS_TABcomplaint C", 'C.CID = UC.CID AND C.FLAG_ACTIVE = 1', 'inner')
                        ->where([
                            'UC.ID' => $ID,
                            'UC.FLAG_ACTIVE' => 1
                        ])->order_by('C.CNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetDtComplaint($ID) {
        $SQL = "SELECT mc.CID, mc.CNAME
                  FROM $this->USERS_TABcomplaint mc
                  LEFT JOIN $this->USERS_TABucomplaint uc
                         ON uc.CID = mc.CID
                        AND uc.ID = ?
                        AND uc.FLAG_ACTIVE = 1
                 WHERE uc.ID IS NULL
                   AND mc.FLAG_ACTIVE = 1";
        $result = $this->db->query($SQL, [$ID])->result();
        $this->db->close();
        return $result;
    }

    public function AssignComplaint($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $dt = [
                'FLAG_ACTIVE' => 1,
                'UPDATED_BY' => $Data['USERNAMEUPDATE'],
                'UPDATED_AT' => Carbon::now('Asia/Jakarta'),
                'UPDATED_LOC' => $Location
            ];
            if ($Data['ACTION'] == "DELETE") {
                $dt['FLAG_ACTIVE'] = 0;
                $result = $this->db->set($dt)
                                ->where([
                                    'CID' => $Data['CID'],
                                    'ID' => $Data['ID']
                                ])->update($this->USERS_TABucomplaint);
            } elseif ($Data['ACTION'] == "ASSIGN") {
                foreach ($Data['CID'] as $values) {
                    $SQL = "SELECT * FROM " . $this->USERS_TABucomplaint . " WHERE CID = ? AND ID = ?";
                    $Cek = $this->db->query($SQL, [$values, $Data['ID']]);
                    if ($Cek->num_rows() > 0) {
                        $result = $this->db->set($dt)
                                        ->where([
                                            'CID' => $values,
                                            'ID' => $Data['ID']
                                        ])->update($this->USERS_TABucomplaint);
                    } else {
                        $dt['CID'] = $values;
                        $dt['ID'] = $Data['ID'];
                        $result = $this->db->set($dt)->insert($this->USERS_TABucomplaint);
                    }
                }
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

    public function GetRole($ID) {
        $SQL = "SELECT U.ROLECODE
                  FROM $this->USERS_TAB U
                 WHERE U.ID = ?
                   AND U.FLAG_ACTIVE = 1";
        $result = $this->db->query($SQL, [$ID])->result();
        $ROLECODE = 0;
        foreach ($result as $values) {
            $ROLECODE = intval($values->ROLECODE);
        }
        $this->db->close();
        return $ROLECODE;
    }

    public function GetDataUser($ID) {
        $this->fillable = ['U.*', 'R.DETAILNAME AS ROLENAME', 'MC.DETAILNAME AS COMPANYNAME', 'MB.BUSINESSNAME', 'MD.DETAILNAME AS DIVISIONNAME'];
        $result = $this->db->select($this->fillable)
                        ->from($this->USERS_TAB . ' U')
                        ->join("$this->USERS_TABBM R", 'U.ROLECODE = R.DETAILCODE AND R.MASTERID = 1', 'inner')
                        ->join("$this->USERS_TABBM MC", 'U.COMPANYCODE = MC.DETAILCODE AND MC.MASTERID = 11', 'left')
                        ->join("$this->USERS_TABB MB", 'U.COMPANYCODE = MB.COMPANYCODE AND U.BUSINESSCODE = MB.BUSINESSCODE', 'left')
                        ->join("$this->USERS_TABBM MD", 'U.DIVISIONCODE = MD.DETAILCODE AND MD.MASTERID = 12', 'left')
                        ->group_start()
                        ->where('U.FLAG_ACTIVE !=', 0)
                        ->where('U.FLAG_ACTIVE IS NOT NULL', null, false)
                        ->group_end()
                        ->where(['U.ID' => $ID])->order_by('U.FULLNAME')->get()->result();
        $this->db->close();
        return $result;
    }

}
