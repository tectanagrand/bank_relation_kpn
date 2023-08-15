<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class UserCompanyModel extends BaseModel {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    public function ShowData() {
        $this->fillable = ['USERCODE','LASTUPDATE'];
        $result = $this->db->select($this->fillable)
                        ->from('USER_COMPANY_TAB')
                        ->group_by(['USERCODE','LASTUPDATE'])
                        ->order_by('LASTUPDATE DESC')->get()->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    public function GetData($USERCODE) {
        $this->fillable = ['UG.USERCODE','UT.FULLNAME'];
        $result = $this->db->select($this->fillable)
                        ->from("USERS_TAB UT")
                        ->join("USER_COMPANY_TAB UG", 'UG.USERCODE = UT.FCCODE', 'left')
                        ->where(['UG.USERCODE' => $USERCODE])->get()->row();
        $this->db->close();
        return $result;
    }

    public function GetDataActive() {
        $this->fillable = ['USERGROUPID', 'USERGROUPNAME', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->USER_GROUP)
                        ->where(['ISACTIVE' => 1])
                        ->order_by('USERGROUPNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetListAccess($USERCODE) {
        $this->fillable = "DPT.COMPANYCODE, DPT.COMPANYNAME, DECODE(UDPT.COMPANYCODE, NULL, 0, 1) AS ISACTIVE";
        $SQL = "SELECT $this->fillable
                  FROM $this->COMPANY DPT
                  LEFT JOIN USER_COMPANY_TAB UDPT 
                         ON UDPT.COMPANYCODE = DPT.COMPANYCODE
                        AND UDPT.USERCODE = ?
                  WHERE DPT.ISACTIVE = '1' 
                 ORDER BY DPT.COMPANYCODE";
        $result = $this->db->query($SQL, [$USERCODE])->result();
        $this->db->close();
        return $result;
    }

    public function Save($Data, $Location) {
        // var_dump($Data);exit;
        try {
            $this->db->trans_begin();
            $result = false;
            if (count($Data['USERDpt']) > 0 && $Data['USERDpt'] != 0) {
                $SQL = "SELECT * FROM USER_COMPANY_TAB WHERE USERCODE = ?";
                $Cek = $this->db->query($SQL, [strtoupper($Data['USERCODE'])]);
                if ($Data['ACTION'] == 'ADD') {
                    if ($Cek->num_rows() > 0) {
                        throw new Exception('Data Already Exists, Please Use Edit Menu !!');
                    }
                }
                if ($Data['ACTION'] == 'EDIT') {
                    $this->db->delete('USER_COMPANY_TAB', ['USERCODE' => $Data['USERCODE']]);
                }

                foreach ($Data['USERDpt'] as $value) {
                    $dataDpt = [
                        'USERCODE' => $Data['USERCODE'],
                        'COMPANYCODE' => $value['COMPANYCODEUser'],
                        'FCENTRY' => $Data['USERNAME'],
                        'FCEDIT' => $Data['USERNAME'],
                        'FCIP' => $Location
                    ];
                    $result = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                    ->set($dataDpt)->insert("USER_COMPANY_TAB");
                }
            } else {
                $result = TRUE;
            }
            if ($result) {

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

    public function SaveParent($DATA, $USERGROUPID, $USERNAME, $Location) {
        try {
            $result = TRUE;
            $dt1 = '';
            $dt2 = '';
            $dtParent = [];
            foreach ($DATA as $dt) {
                $dt2 = $dt;
                if ($dt1 != $dt2) {
                    $dt1 = $dt2;
                    $datadetail = [
                        'FVIEW' => 1,
                        'FADD' => 0,
                        'FEDIT' => 0,
                        'FDELETE' => 0,
                        'FCEDIT' => $USERNAME,
                        'FCIP' => $Location
                    ];
                    $resdetail = FALSE;
                    $cek = $this->db->query("SELECT * FROM $this->MENU_ACCESS WHERE USERGROUPID = ? AND MENUCODE = ?", [$USERGROUPID, $dt]);
                    if ($cek->num_rows() > 0) {
                        $resdetail = $this->db->set($datadetail)->set('LASTUPDATE', "SYSDATE", false)
                                ->where(['USERGROUPID' => $USERGROUPID, 'MENUCODE' => $dt])
                                ->update($this->MENU_ACCESS);
                    } else {
                        $datadetail['USERGROUPID'] = $USERGROUPID;
                        $datadetail['MENUCODE'] = $dt;
                        $datadetail['FCENTRY'] = $USERNAME;
                        $resdetail = $this->db->set($datadetail)->set('LASTUPDATE', "SYSDATE", false)->insert($this->MENU_ACCESS);
                    }
                    if ($resdetail) {
                        $menuParent = $this->db->query("SELECT * FROM $this->MSTMENU WHERE MENUCODE = ?", [$dt]);
                        foreach ($menuParent->result() as $mp) {
                            if ($mp->MENUPARENT != 0) {
                                array_push($dtParent, $mp->MENUPARENT);
                            }
                        }
                    } else {
                        throw new Exception("Data Save Failed !!");
                    }
                }
            }
            if (count($dtParent) > 0) {
                $result = $this->SaveParent($dtParent, $USERGROUPID, $USERNAME, $Location);
            }
            return $result;
        } catch (Exception $ex) {
            return FALSE;
        }
    }

    public function GetMenu($USERGROUPID) {
        try {
            $html = '';
            $SQL = "SELECT ma.MENUCODE, ma.MENUNAME, ma.MENUPARENT, ma.MUNELINK, ma.ICON 
                      FROM $this->MSTMENU ma 
                     INNER JOIN $this->MENU_ACCESS ua 
                             ON ua.MENUCODE = ma.MENUCODE AND ua.USERGROUPID = ? AND ua.FVIEW = 1
                     WHERE ma.ISACTIVE = 1 AND ma.MENUPARENT = ?
                     ORDER BY ma.IDX";
            $menu = $this->db->query($SQL, [$USERGROUPID, 0])->result();
            if (count($menu) > 0) {
                foreach ($menu as $men) {
                    $menusub = $this->db->query($SQL, [$USERGROUPID, $men->MENUCODE])->result();
                    if (count($menusub) > 0) {
                        $html .= '<li id="' . $men->MENUCODE . '" class="has-sub">
                                  <a href="javascript:;"><b class="caret"></b><i class="' . $men->ICON . '"></i><span>' . $men->MENUNAME . '</span></a>
                                  <ul id="' . $men->MENUCODE . '" class="sub-menu">';
                        $parent = $this->GetMenuNext($menusub, $SQL, $USERGROUPID);
                        $html .= $parent . '</ul></li>';
                    } else {
                        $html .= '<li id="' . $men->MENUCODE . '">
                                  <a href="' . site_url($men->MUNELINK) . '">
                                  <i class="' . $men->ICON . '"></i><span>' . $men->MENUNAME . '</span></a>
                                  </li>';
                    }
                }
            }
            return $html;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function GetMenuNext($menusub, $SQL, $USERGROUPID) {
        $parentHtml = '';
        foreach ($menusub as $menu) {
            $menusubdetail = $this->db->query($SQL, [$USERGROUPID, $menu->MENUCODE])->result();
            if (count($menusubdetail) > 0) {
                $parentHtml .= '<li id="' . $menu->MENUCODE . '" class="has-sub" data-target="' . $menu->MENUPARENT . '">
                                  <a href="javascript:;"><b class="caret"></b><i class="' . $menu->ICON . '"></i><span>' . $menu->MENUNAME . '</span></a>
                                  <ul id="' . $menu->MENUCODE . '" class="sub-menu">';
                $parent = $this->GetMenuNext($menusubdetail, $SQL, $USERGROUPID);
                $parentHtml .= $parent . '</ul></li>';
            } else {
                $parentHtml .= '<li id="' . $menu->MENUCODE . '" data-target="' . $menu->MENUPARENT . '">
                                <a href="' . site_url($menu->MUNELINK) . '">
                                <i class="' . $menu->ICON . '"></i><span>' . $menu->MENUNAME . '</span></a>
                                </li>';
            }
        }
        return $parentHtml;
    }

    public function GetAccessMenu($USERGROUPID, $FORMNO) {
        try {
            $data = [];
            if ($FORMNO != 0 && $FORMNO != 'HOME') {
                $data = [
                    'VIEWS' => 0,
                    'ADDS' => 0,
                    'EDITS' => 0,
                    'DELETES' => 0
                ];
                $access = $this->db->select(['USERGROUPID', 'MENUCODE', 'FVIEW', 'FADD', 'FEDIT', 'FDELETE'])
                                ->from("$this->MENU_ACCESS")->where(['USERGROUPID' => $USERGROUPID, 'MENUCODE' => $FORMNO])
                                ->get()->result();
                if (count($access) > 0) {
                    foreach ($access as $acc) {
                        $data = [
                            'VIEWS' => $acc->FVIEW,
                            'ADDS' => $acc->FADD,
                            'EDITS' => $acc->FEDIT,
                            'DELETES' => $acc->FDELETE
                        ];
                    }
                }
            } else {
                $data = [
                    'VIEWS' => 1,
                    'ADDS' => 1,
                    'EDITS' => 1,
                    'DELETES' => 1
                ];
            }
            return $data;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function Delete($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $result1 = $this->db->delete($this->MENU_ACCESS, ['USERGROUPID' => $Data['USERGROUPID']]);
            $result2 = $this->db->delete($this->USER_GROUP, ['USERGROUPID' => $Data['USERGROUPID']]);
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

    public function GetDepartement($USERNAME) {
        $this->fillable = ['DEPARTMENT'];
        $result = $this->db->select($this->fillable)
                        ->from($this->USER_DEPART)
                        ->where(['FCCODE' => $USERNAME])
                        ->get()->row();
        $DEPARTEMENT = $result->DEPARTMENT;
        return $DEPARTEMENT;
    }

    public function BMCodeDetail($MASTERID) {
        $this->fillable = ['DETAILID', 'DETAILNAME', 'REMARK'];
        $result = $this->db->select($this->fillable)
                        ->from($this->BMCODEDETAIL)
                        ->where(['MASTERID' => $MASTERID, 'FLAG_ACTIVE' => 1])
                        ->order_by('DETAILNAME')->get()->result();
        return $result;
    }

    public function GetDtUser($USERNAME) {
        $this->fillable = ['FCCODE', 'FULLNAME', 'USERGROUPID', 'USERACCESS'];
        $result = $this->db->select($this->fillable)
                        ->from($this->USERS_TAB)
                        ->where(['ISACTIVE' => 'TRUE', 'FCCODE' => $USERNAME])
                        ->get()->row();
        // print_r($result);exit;
        return $result;
    }

    public function GetDtDepart($USERNAME) {
        $this->fillable = ["UD.DEPARTMENT", "DP.FCNAME AS DEPARTEMENTNAME"];
        $result = $this->db->select($this->fillable)
                        ->from("$this->USER_DEPART UD")
                        ->join("$this->DEPARTMENT DP", 'DP.FCCODE = UD.DEPARTMENT', 'inner')
                        ->where(['UD.FCCODE' => $USERNAME])
                        ->order_by('DP.FCNAME')
                        ->get()->result();
        return $result;
    }

    public function FinalApprove($Template, $Username, $Department) {
        
        return $result;
    }

}
