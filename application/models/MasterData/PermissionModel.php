<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class PermissionModel extends BaseModel {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    public function ShowData() {
        $this->fillable = ['USERGROUPID', 'USERGROUPNAME', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->USER_GROUP)
                        ->order_by('USERGROUPNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function GetData($USERGROUPID) {
        $this->fillable = ['USERGROUPID', 'USERGROUPNAME', 'ISACTIVE'];
        $result = $this->db->select($this->fillable)
                        ->from($this->USER_GROUP)
                        ->where(['USERGROUPID' => $USERGROUPID])
                        ->order_by('USERGROUPNAME')->get()->row();
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

    public function GetListAccess($USERGROUPID) {
        $this->fillable = 'mu.MENUCODE, mu.MENUNAME, mu.MENUPARENT, mp.MENUNAME  AS MENUPARENTNAME, NVL(ra.FVIEW, 0) AS VIEWS, 
                           NVL(ra.FADD, 0) AS ADDS, NVL(ra.FEDIT, 0) AS EDITS, NVL(ra.FDELETE, 0) AS DELETES';
        $SQL = "SELECT $this->fillable
                  FROM $this->MSTMENU mu
                  LEFT JOIN $this->MSTMENU mp 
                         ON mp.MENUCODE = mu.MENUPARENT 
                  LEFT JOIN $this->MENU_ACCESS ra 
                         ON ra.MENUCODE = mu.MENUCODE AND ra.USERGROUPID = ? 
                 WHERE mu.ISACTIVE = 1 
                   AND mu.MENUTYPE = 1
                 ORDER BY CASE mu.MENUPARENT WHEN 0 THEN mu.IDX ELSE mp.IDX END, mu.MENUPARENT, mu.IDX";
        $query = $this->db->query($SQL, [$USERGROUPID])->result();
        $this->db->close();
        return $query;
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->USER_GROUP WHERE USERGROUPID <> ? AND USERGROUPNAME = ?";
            $Cek = $this->db->query($SQL, [$Data['USERGROUPID'], $Data['USERGROUPNAME']]);
            if ($Cek->num_rows() > 0) {
                throw new Exception('Data Already Exists !!');
            }
            $dt = [
                'USERGROUPNAME' => $Data['USERGROUPNAME'],
                'ISACTIVE' => $Data['ISACTIVE'],
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false);

            if ($Data['ACTION'] == 'ADD') {
                $query = $this->db->query("SELECT MAX(USERGROUPID)+1 as ID FROM $this->USER_GROUP")->row();
                $id = $query->ID;
                $dt['USERGROUPID'] = $id;
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result1 = $result1->set($dt)->insert($this->USER_GROUP);
                $Data['USERGROUPID'] = $id;
//                $Data['USERGROUPID'] = $this->db->insert_id();
            } elseif ($Data['ACTION'] == 'EDIT') {
                $result1 = $result1->set($dt)
                        ->where(['USERGROUPID' => $Data['USERGROUPID']])
                        ->update($this->USER_GROUP);
            }
            $dtsub = [
                'FVIEW' => 0,
                'FADD' => 0,
                'FEDIT' => 0,
                'FDELETE' => 0,
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];
            $cek = $this->db->query("SELECT * FROM $this->MENU_ACCESS WHERE USERGROUPID = ?", [$Data['USERGROUPID']])->num_rows();
            if ($cek > 0) {
//                $updatesub = $this->db->update($this->MENU_ACCESS, $dtsub, ['USERGROUPID' => $Data['USERGROUPID']]);
                $updatesub = $this->db->set($dtsub)->set('LASTUPDATE', "SYSDATE", false)
                        ->where(['USERGROUPID' => $Data['USERGROUPID']])
                        ->update($this->MENU_ACCESS);
            } else {
                $updatesub = 1;
            }

            if ($updatesub) {
                $datsub = [];
                foreach ($Data['DATA'] as $dt) {
                    if ($dt['MENUCODE'] != NULL) {
                        $datadetail = [
                            'FVIEW' => $dt['VIEWS'],
                            'FADD' => $dt['ADDS'],
                            'FEDIT' => $dt['EDITS'],
                            'FDELETE' => $dt['DELETES'],
                            'FCEDIT' => $Data['USERNAME'],
                            'FCIP' => $Location
                        ];
                        $resdetail = FALSE;
                        $cek = $this->db->query("SELECT * FROM $this->MENU_ACCESS WHERE USERGROUPID = ? AND MENUCODE = ?", [$Data['USERGROUPID'], $dt['MENUCODE']]);
                        if ($cek->num_rows() > 0) {
//                            $resdetail = $this->db->update($this->MENU_ACCESS, $datadetail, ['USERGROUPID' => $Data['USERGROUPID'], 'MENUCODE' => $dt['MENUCODE']]);
                            $resdetail = $this->db->set($datadetail)->set('LASTUPDATE', "SYSDATE", false)
                                    ->where(['USERGROUPID' => $Data['USERGROUPID'], 'MENUCODE' => $dt['MENUCODE']])
                                    ->update($this->MENU_ACCESS);
                        } else {
                            $datadetail['USERGROUPID'] = $Data['USERGROUPID'];
                            $datadetail['MENUCODE'] = $dt['MENUCODE'];
                            $datadetail['FCENTRY'] = $Data['USERNAME'];
                            $resdetail = $this->db->set($datadetail)->set('LASTUPDATE', "SYSDATE", false)->insert($this->MENU_ACCESS);
                        }
                        if ($resdetail) {
                            if ($dt['MENUPARENT'] != 0 && $dt['VIEWS'] == 1) {
                                array_push($datsub, $dt['MENUPARENT']);
                            }
                        } else {
                            throw new Exception("Data Save Failed !!");
                        }
                    }
                }
                $result2 = $resdetail;
                if (count($datsub) > 0) {
                    $result2 = $this->SaveParent($datsub, $Data['USERGROUPID'], $Data['USERNAME'], $Location);
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
