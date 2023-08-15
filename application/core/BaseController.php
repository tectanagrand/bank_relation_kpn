<?php

defined('BASEPATH') or exit('No direct script access allowed');

class BaseController extends CI_Controller {

    protected $resource;
    protected $datasend;
    protected $allowed_http_methods = ['get', 'delete', 'post', 'put', 'options', 'patch', 'head'];

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->datasend = [];
    }

    protected function BuildResponse($status = 200, $data = array()) {
        try {
//            if (is_array($data)) {
//                $data = [$data];
//            }
            $size = 1;
            if ($data === NULL) {
                $size = 0;
            } else {
                if (is_array($data)) {
                    if (count($data) != count($data, 1)) {
                        $size = sizeof($data);
                    } else {
                        $size = count($data, 1);
                    }
                }
            }
            return array(
                'status' => $status,
                'result' => array(
                    'size' => $size,
                    'data' => $data
                )
            );
        } catch (Exception $ex) {
            return array(
                'status' => 500,
                'result' => array(
                    'message' => $ex->getMessage(),
                    'data' => $ex->getTraceAsString()
                )
            );
        }
    }

    protected function BuildResponse_new($status = 200, $data = array(), $data_2 = array()) {
        try {
//            if (is_array($data)) {
//                $data = [$data];
//            }
            $size = 1;
            if ($data === NULL) {
                $size = 0;
            } else {
                if (is_array($data)) {
                    if (count($data) != count($data, 1)) {
                        $size = sizeof($data);
                    } else {
                        $size = count($data, 1);
                    }
                }
                if (is_array($data_2)) {
                    if (count($data_2) != count($data_2, 1)) {
                        $size = sizeof($data_2);
                    } else {
                        $size = count($data_2, 1);
                    }
                }
            }
            return array(
                'status' => $status,
                'result' => array(
                    'size' => $size,
                    'data' => $data,
                    'data_2' => $data_2
                )
            );
        } catch (Exception $ex) {
            return array(
                'status' => 500,
                'result' => array(
                    'message' => $ex->getMessage(),
                    'data' => $ex->getTraceAsString(),
                    'data_2' => $ex->getTraceAsString()
                )
            );
        }
    }

    protected function SendResponse() {
        $data = $this->resource;
        $response = $this->BuildResponse($data['status'], $data['data']);
        echo json_encode($response);
    }

    public function GetIpAddress() {
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif (
                array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
        ) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {

            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        if (!isset($the_ip) || $the_ip == 0 || $the_ip == null || $the_ip == '')
            $the_ip = '127.0.0.1';

        return $the_ip;
    }

    public function GetMenuUser() {
        try {
            $html = '';
            $SQL = 'SELECT ma.MENUCODE, ma.MENUNAME, ma.MENUPARENT, ma.URL, ma.ICON 
                      FROM menus ma 
                     WHERE ma.FLAG_ACTIVE = 1 AND ma.MENUPARENT = ? AND ma.MENUTYPE = 99
                     ORDER BY ma.IDX';
            $menu = $this->db->query($SQL, [0])->result();
            if (count($menu) > 0) {
                foreach ($menu as $men) {
                    $menusub = $this->db->query($SQL, [$men->MENUCODE])->result();
                    if (count($menusub) > 0) {
                        $html .= '<li id="' . $men->MENUCODE . '" class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="' . $men->ICON . '"></i> ' . $men->MENUNAME . '</a>
                                  <ul id="' . $men->MENUCODE . '" class="dropdown-menu">';
                        $parent = $this->GetMenuUserNext($menusub, $SQL);
                        $html .= $parent . '</ul></li>';
                    } else {
                        $html .= '<li id="' . $men->MENUCODE . '" class="nav-item">
                                  <a href="' . site_url($men->URL) . '" class="nav-link"><i class="' . $men->ICON . '"></i> ' . $men->MENUNAME . '</a>
                                  </li>';
                    }
                }
            }
            return $html;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function GetMenuUserNext($menusub, $SQL) {
        $parentHtml = '';
        foreach ($menusub as $menu) {
            $menusubdetail = $this->db->query($SQL, [$menu->MENUCODE])->result();
            if (count($menusubdetail) > 0) {
                $parentHtml .= '<li id="' . $menu->MENUCODE . '" class="dropdown-submenu" data-target="' . $menu->MENUPARENT . '">
                                <a href="#" class="dropdown-item dropdown-toggle" data-toggle="dropdown"><i class="' . $menu->ICON . '"></i> ' . $menu->MENUNAME . '</a>
                                <ul id="' . $menu->MENUCODE . '" class="dropdown-menu">';
                $parent = $this->GetMenuUserNext($menusubdetail, $SQL);
                $parentHtml .= $parent . '</ul></li>';
            } else {
                $parentHtml .= '<li><a id="' . $menu->MENUCODE . '" href="' . site_url($menu->URL) . '" class="dropdown-item" data-target="' . $menu->MENUPARENT . '">
                                <i class="' . $menu->ICON . '"></i> ' . $menu->MENUNAME . '
                                </a></li>';
            }
        }
        return $parentHtml;
    }

    public function GetMenuAdmin($ROLECODE) {
        try {
            $html = '';
            $SQL = 'SELECT ma.MENUCODE, ma.MENUNAME, ma.MENUPARENT, ma.URL, ma.ICON 
                      FROM menus ma 
                     INNER JOIN menuaccess ua 
                             ON ua.MENUCODE = ma.MENUCODE AND ua.ROLECODE = ? AND ua.VIEWS = 1
                     WHERE ma.FLAG_ACTIVE = 1 AND ma.MENUPARENT = ?
                     ORDER BY ma.IDX';
            $menu = $this->db->query($SQL, [$ROLECODE, 0])->result();
            if (count($menu) > 0) {
                foreach ($menu as $men) {
                    $menusub = $this->db->query($SQL, [$ROLECODE, $men->MENUCODE])->result();
                    if (count($menusub) > 0) {
                        $html .= '<li id="' . $men->MENUCODE . '" class="nav-item dropdown">
                                  <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="' . $men->ICON . '"></i> ' . $men->MENUNAME . '</a>
                                  <ul id="' . $men->MENUCODE . '" class="dropdown-menu">';
                        $parent = $this->GetMenuAdminNext($menusub, $SQL, $ROLECODE);
                        $html .= $parent . '</ul></li>';
                    } else {
                        $html .= '<li id="' . $men->MENUCODE . '" class="nav-item">
                                  <a href="' . site_url($men->URL) . '" class="nav-link"><i class="' . $men->ICON . '"></i> ' . $men->MENUNAME . '</a>
                                  </li>';
                    }
                }
            }
            return $html;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function GetMenuAdminNext($menusub, $SQL, $ROLECODE) {
        $parentHtml = '';
        foreach ($menusub as $menu) {
            $menusubdetail = $this->db->query($SQL, [$ROLECODE, $menu->MENUCODE])->result();
            if (count($menusubdetail) > 0) {
                $parentHtml .= '<li id="' . $menu->MENUCODE . '" class="dropdown-submenu" data-target="' . $menu->MENUPARENT . '">
                                <a href="#" class="dropdown-item dropdown-toggle" data-toggle="dropdown"><i class="' . $menu->ICON . '"></i> ' . $menu->MENUNAME . '</a>
                                <ul id="' . $menu->MENUCODE . '" class="dropdown-menu">';
                $parent = $this->GetMenuUserNext($menusubdetail, $SQL, $ROLECODE);
                $parentHtml .= $parent . '</ul></li>';
            } else {
                $parentHtml .= '<li><a id="' . $menu->MENUCODE . '" href="' . site_url($menu->URL) . '" class="dropdown-item" data-target="' . $menu->MENUPARENT . '">
                                <i class="' . $menu->ICON . '"></i> ' . $menu->MENUNAME . '
                                </a></li>';
            }
        }
        return $parentHtml;
    }

    public function GetAccessMenu($ROLECODE, $FORMNO) {
        try {
            $data = [];
            if ($FORMNO != 0 && $FORMNO != 'HOME') {
                $data = [
                    'VIEWS' => 0,
                    'ADDS' => 0,
                    'EDITS' => 0,
                    'DELETES' => 0,
                    'PRINTS' => 0
                ];
                $access = $this->db->select(['ROLECODE', 'MENUCODE', 'VIEWS', 'ADDS', 'EDITS', 'DELETES', 'PRINTS'])
                                ->from('menuaccess')->where(['ROLECODE' => $ROLECODE, 'MENUCODE' => $FORMNO])
                                ->get()->result();
                if (count($access) > 0) {
                    foreach ($access as $acc) {
                        $data = [
                            'VIEWS' => $acc->VIEWS,
                            'ADDS' => $acc->ADDS,
                            'EDITS' => $acc->EDITS,
                            'DELETES' => $acc->DELETES,
                            'PRINTS' => $acc->PRINTS
                        ];
                    }
                }
            } else {
                $data = [
                    'VIEWS' => 1,
                    'ADDS' => 1,
                    'EDITS' => 1,
                    'DELETES' => 1,
                    'PRINTS' => 1,
                    'PRINTS' => 1
                ];
            }
            return $data;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

}
