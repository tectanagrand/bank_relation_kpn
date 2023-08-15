<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class MstVendorController extends BaseController {

    protected $COMPANY = 'COMPANY';
    protected $COMPANY_EXTSYS = 'COMPANY_EXTSYS';
    protected $COMPANY_DEPART = 'COMPANY_DEPART';
    protected $BUSINESSUNIT = 'BUSINESSUNIT';
    protected $BUSINESSUNIT_EXTSYS = 'BUSINESSUNIT_EXTSYS';
    protected $table = 'SUPPLIER';

    public function __construct() {
        parent::__construct();
        $this->datasend = [];
        $this->load->model('VendorModel');
    }

    public function ShowData() {
        try {
            $param = $_POST;
            $list = $this->VendorModel->ShowData($param);
            $this->resource = array(
                'status' => 200,
                'data' => [
                    "draw" => $_POST["draw"],
                    "recordsTotal" => $list["recordsTotal"],
                    "recordsFiltered" => $list["recordsFiltered"],
                    "data" => $list["data"]
                ]
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function isShow() {
        try {
            $param = $_POST;
            if($this->session->userdata('DEPARTMENT') == 'FINANCE' || $this->session->userdata('DEPARTMENT') == 'IT'){
                $this->db->set('LASTUPDATE', "SYSDATE", false);
                $this->db->set('FCEDIT', $param['FCEDIT']);
                $this->db->set('IS_SHOW',$param['SHOW']);
                $this->db->where('ID',$param['ID']);
                $list = $this->db->update('SUPPLIER');
                $this->resource = array(
                    'status' => 200,
                    'data' => 'OK'
                );
            }else{
                $this->resource = array(
                    'status' => 500,
                    'data' => 'Not Ok'
                );    
            }
            
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function GetDataAjax() {
        try {
            $params = $this->input->post();
            $list = $this->VendorModel->GetDataAjax($params);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function ListDataUpload() {
        $param = $this->input->post();
        try {
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
                $file = $_FILES['uploads'];
                $spreadsheet = IOFactory::load($file['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->removeRow(1)->toArray(null, true, true, true);
                $SQLC = "SELECT C.ID
                          FROM $this->COMPANY C
                         INNER JOIN $this->COMPANY_EXTSYS CE
                                 ON CE.COMPANY = C.ID
                         WHERE CE.EXTSYSTEM = ?
                           AND CE.EXTSYSCOMPANYCODE = ?";
                $SQLB = "SELECT B.ID
                           FROM $this->BUSINESSUNIT B
                          INNER JOIN $this->BUSINESSUNIT_EXTSYS BE
                                  ON BE.BUSINESSUNIT = B.ID
                          WHERE B.COMPANY = ?
                            AND BE.EXTSYSTEM = ?
                            AND BE.EXTSYSBUSINESSUNITCODE = ?";
                
                $SQLS = "SELECT * 
                           FROM $this->table S
                          WHERE S.FCCODE = ?";
                          //WHERE S.FCCODE = ?
                          
                $EXTSYSTEM = strtoupper($param['EXTSYSTEM']);
                //$SQLItemType = "SELECT * FROM KPNCORP.MATERIAL_TYPE MT WHERE MT.FCCODE=?";
                $idx = 1;
                foreach ($sheetData as $value) {
                    if (
                            $value['A'] == NULL &&
                            $value['B'] == NULL &&
                            $value['C'] == NULL &&
                            $value['D'] == NULL &&
                            $value['E'] == NULL &&
                            $value['F'] == NULL &&
                            $value['G'] == NULL &&
                            $value['H'] == NULL &&
                            $value['I'] == NULL &&
                            $value['J'] == NULL &&
                            $value['K'] == NULL
                    ) {
                        
                    } else {
                        $status = 0;
                        $dt = [
                            'CODESUPPLIER' => (string)$value['B'],
                            'NAMASUPPLIER' => $value['C'],
//                            'CODECOMPANY' => $value['D'],
//                            'CODEBUSINESSUNIT' => $value['E'],
                            'ADDRESS' => $value['F'],
                            'CITY' => $value['G'],
                            'BANKNAME' => $value['H'],
                            'BANKACCOUNT' => $value['I'],
                            'EMAIL' => $value['J'],
                            'DESCRIPTION' => $value['K'],
                            'STATUS' => 0,
                            'EXTSYSTEM' => $EXTSYSTEM,
                            'MESSAGE' => ''
                        ];

                        /*$cek = $this->db->query($SQLC, [$param['EXTSYSTEM'], $dt['CODECOMPANY']])->result();
                        if (count($cek) <= 0) {
                            $dt['STATUS'] = 1;
                            $dt['MESSAGE'] = "Company Not Found !!!";
                        } else {
                            foreach ($cek as $values) {
                                $dt['COMPANY'] = $values->ID;
                            }
                            $cek = $this->db->query($SQLB, [$dt['COMPANY'], $param['EXTSYSTEM'], $dt['CODEBUSINESSUNIT']])->result();
                            if (count($cek) <= 0) {
                                $dt['STATUS'] = 1;
                                $dt['MESSAGE'] = "Business Unit Not Found !!!";
                            } else {
                                foreach ($cek as $values) {
                                    $dt['BUSINESSUNIT'] = $values->ID;
                                }
                            }
                        }*/
                        
                        $cek = $this->db->query($SQLS, $dt['CODESUPPLIER'])->result();
                        if (count($cek) > 0){
                            $dt['STATUS'] = 1;
                            $dt['MESSAGE'] = "Some Data Already Exists !!!";
                        }
                        array_push($data, $dt);
                    }
                }
            }
            $this->resource = array(
                'status' => 200,
                'data' => $data
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function Save() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->VendorModel->Save($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SaveUpload() {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->VendorModel->SaveUpload($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function Delete() {
        try {
            $param = $this->input->post();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->VendorModel->Delete($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

}
