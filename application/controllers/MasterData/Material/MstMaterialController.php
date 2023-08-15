<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class MstMaterialController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->datasend = [];
        $this->load->model('MaterialModel');
    }

    public function isShow() {
        try {
            $param = $_POST;
            if($this->session->userdata('DEPARTMENT') == 'FINANCE' || $this->session->userdata('DEPARTMENT') == 'IT'){
                $this->db->set('LASTUPDATE', "SYSDATE", false);
                $this->db->set('FCEDIT', $param['FCEDIT']);
                $this->db->set('IS_SHOW',$param['SHOW']);
                $this->db->where('ID',$param['ID']);
                $list = $this->db->update('MATERIAL');
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

    public function ShowData()
    {
        try {
            $param = $_POST;
            //            print_r($param['order'][0]['column']);
            $list = $this->MaterialModel->ShowData($param);
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

    public function ajaxLiveSearch()
    {
        $param = $this->input->post();
        try {
            $list = $this->MaterialModel->ajaxLiveSearch($param);
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

    public function ListDataUpload()
    {
        $param = $this->input->post();
        try {
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
                $file = $_FILES['uploads'];
                $spreadsheet = IOFactory::load($file['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->removeRow(1)->toArray(null, true, true, true);
                $SQLC = "SELECT * FROM KPNCORP.MATERIAL M WHERE M.EXTSYSTEM=? AND M.FCCODE=?";
                $SQLItemType = "SELECT * FROM KPNCORP.MATERIAL_TYPE MT WHERE MT.FCCODE=?";
                // $SQLGroupItem = "SELECT * FROM KPNCORP.MATERIAL_GROUP MG WHERE MG.FCCODE=?";
                $EXTSYSTEM = strtoupper($param['EXTSYSTEM']);
                $idx = 1;
                foreach ($sheetData as $value) {
                    if (
                        $value['A'] == NULL &&
                        $value['B'] == NULL &&
                        $value['C'] == NULL &&
                        $value['D'] == NULL &&
                        $value['E'] == NULL &&
                        $value['F'] == NULL &&
                        $value['G'] == NULL
                    ) {
                    } else {
                        $status = 0;
                        $dt = [
                            'CODEITEM' => $value['B'],
                            'NAMAITEM' => $value['C'],
                            'DESCRIPTION' => $value['D'],
                            'PARTNO' => $value['E'],
                            'ITEMTYPE' => $value['F'],
                            'GROUPITEM' => $value['G'],
                            'STATUS' => 0,
                            'EXTSYSTEM' => $EXTSYSTEM,
                            'MESSAGE' => ''
                        ];

                        $cek = $this->db->query($SQLC, [$param['EXTSYSTEM'], $dt['CODEITEM']])->result();
                        $gettype = $this->db->query($SQLItemType, [$dt['ITEMTYPE']])->row();
                        $dt['ITEMTYPENAME'] = $gettype->FCNAME;

                        if (count($cek) > 0) {
                            $dt['STATUS'] = 1;
                            $dt['MESSAGE'] = "Material have available !!!";
                        } 
                        // else {
                        //     $cek = $this->db->query($SQLGroupItem, [strval($dt['GROUPITEM'])])->result();
                        //     if (count($cek) <= 0) {
                        //         $dt['STATUS'] = 1;
                        //         $dt['MESSAGE'] = "Group Item Not Found !!!";
                        //     }   
                        // }

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

    public function Save()
    {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->MaterialModel->Save($param, $this->GetIpAddress());
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

    public function SaveUpload()
    {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->MaterialModel->SaveUpload($param, $this->GetIpAddress());
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

    public function Delete()
    {
        try {
            $param = $this->input->post();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->MaterialModel->Delete($param, $this->GetIpAddress());
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
