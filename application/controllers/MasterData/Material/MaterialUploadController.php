<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class MaterialUploadController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('MaterialUploadModel');
    }

    public function ListDataUpload() {
        $param = $this->input->post();
        try {
            $this->db->trans_begin();
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
                $file = $_FILES['uploads'];
                $spreadsheet = IOFactory::load($file['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->removeRow(1)->toArray(null, true, true, true);
                $SQLC = "SELECT * FROM KPNCORP.MATERIAL M WHERE M.EXTSYSTEM=? AND M.FCCODE=?";
                $SQLItemType = "SELECT * FROM KPNCORP.MATERIAL_TYPE MT WHERE MT.FCCODE=?";
                $EXTSYSTEM = strtoupper($param['EXTSYSTEM']);
                $idx = 1;
                foreach ($sheetData as $value) {
                    if (
                            $value['A'] == NULL &&
                            $value['B'] == NULL &&
                            $value['C'] == NULL &&
                            $value['D'] == NULL &&
                            $value['E'] == NULL &&
                            $value['F'] == NULL 
                    ) {
                        
                    } else {
                        $status = 0;
                        $dt = [
                            'CODEITEM' => $value['B'],
                            'NAMAITEM' => $value['C'],
                            'DESCRIPTION' => $value['D'],
                            'PARTNO' => $value['E'],
                            'ITEMTYPE' => $value['F'],
                            'STATUS' => 0,
                            'EXTSYSTEM' => $EXTSYSTEM,
                            'MESSAGE' => ''
                        ];
                        $gettype = $this->db->query($SQLItemType, [$dt['ITEMTYPE']])->row();
                        $dt['ITEMTYPENAME'] = $gettype->FCNAME;

                        $cek = $this->db->query($SQLC, [$param['EXTSYSTEM'], $dt['CODEITEM']])->result();

                        //var_dump(count($cek));
                        if (count($cek) > 0) {
                            $dt['STATUS'] = 1;
                            $dt['MESSAGE'] = "Material have available !!!";
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
            $return = $this->MaterialUploadModel->Save($param, $this->GetIpAddress());
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
