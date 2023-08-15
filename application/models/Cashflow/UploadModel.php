<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function Save_PO($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $res = FALSE;
            date_default_timezone_set("Asia/Jakarta");
            $UPLOAD_REF = date("Ymd_His") . "_" . $Data['FILENAME'];
            foreach ($Data['DATA'] as $value) {
                $ADENDUM = 'FALSE';
                if ($value['datadetail'][0]['ISADENDUM'] == "1") {
                    $ADENDUM = 'TRUE';
                }
                $dt = [
                    'EXTSYS' => $value['datadetail'][0]['EXTSYSTEM'],
                    'DOCTYPE' => $value['datadetail'][0]['DOCTYPE'],
                    'COMPANY' => $value['COMPANY'],
                    'BUSINESSUNIT' => $value['BUSINESSUNIT'],
                    'DEPARTMENT' => $value['DEPARTMENT'],
                    'DOCNUMBER' => $value['DOCNUMBER'],
                    'DOCREF' => $value['datadetail'][0]['DOCREF'],
                    'VENDOR' => $value['datadetail'][0]['VENDOR'],
                    'TRANS_LOC' => $value['datadetail'][0]['TRANS_LOC'],
                    'PAYTERM' => $value['datadetail'][0]['PAYTERM'],
                    'VENDOR' => $value['datadetail'][0]['VENDOR'],
                    'AMOUNT_INCLUDE_VAT' => abs($value['AMOUNT_INCLUDE_VAT']),
                    'AMOUNT_PPH' => $value['AMOUNT_PPH'],
                    'ISADENDUM' => $ADENDUM,
                    'CURRENCY' => $value['datadetail'][0]['CURRENCY'],
                    'RATE' => $value['datadetail'][0]['RATE'],
                    'INVOICEVENDORNO' => $value['datadetail'][0]['INVOICEVENDORNO'],
                    'ISACTIVE' => 'TRUE',
                    'FCENTRY' => $Data['USERNAME'],
                    'FCEDIT' => $Data['USERNAME'],
                    'FCIP' => $Location,
                    'UPLOAD_REF' => $UPLOAD_REF,
                    'VAT' => 0
                ];
                if($dt['AMOUNT_INCLUDE_VAT'] == null || $dt['AMOUNT_INCLUDE_VAT'] == 0){
                                    throw new Exception('Amount Tidak Boleh Kosong.');        
                                }
                // $cek = $this->db->select('*')
                //                 ->from($this->CF_TRANSACTION)
                //                 ->where([
                //                     'COMPANY' => $dt['COMPANY'],
                //                     'DOCNUMBER' => $dt['DOCNUMBER']
                //                 ])->get()->result();
                // $siqil = "SELECT COMPANYNAME FROM COMPANY WHERE ID = '".$dt['COMPANY']."'";
                // $result = $this->db->query($siqil)->row()->COMPANYNAME;

                // if (count($cek) > 0) {
                //     throw new Exception('COMPANY '.$result.' DOCNUMBER '.$dt['DOCNUMBER'].' Already Exists !!!');
                // }

                 $cek = $this->db->select('*')
                                ->from($this->CF_TRANSACTION)
                                ->where([
                                    'COMPANY' => $dt['COMPANY'],
                                    'DOCNUMBER' => $dt['DOCNUMBER'],
                                    'DOCREF' => $dt['DOCREF'],
                                    'ISACTIVE' => 'TRUE'
                                ])->get()->result();
                if (count($cek) > 0) {
                    throw new Exception('COMPANY: '.$dt['COMPANY'].' DOCNUMBER: '.$dt['DOCNUMBER'].' DOCREF: '.$dt['DOCREF'].' Already Exists !!!');
                }

                // $cekLagi = $this->db->select('*')
                //                 ->from($this->CF_TRANSACTION)
                //                 ->where([
                //                     'COMPANY' => $dt['COMPANY'],
                //                     'DOCNUMBER' => $dt['DOCNUMBER'],
                //                     'DOCREF' => $dt['DOCREF'],
                //                     'ISACTIVE' => 'TRUE'
                //                 ])->get()->result();

                // if (count($cekLagi) > 0) {
                //     throw new Exception('COMPANY '.$result.' DOCNUMBER '.$dt['DOCNUMBER'].' Already Exists !!!');
                // }


                $res = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                if ($value['datadetail'][0]['DOCDATE'] == NULL || $value['datadetail'][0]['DOCDATE'] == '') {
                    $res = $res->set('DOCDATE', 'NULL', false);
                } else {
                    $res = $res->set('DOCDATE', "TO_DATE('" . $value['datadetail'][0]['DOCDATE'] . "','mm/dd/yyyy')", false);
                }
                if ($value['datadetail'][0]['BASELINEDATE'] == NULL || $value['datadetail'][0]['BASELINEDATE'] == '') {
                    $res = $res->set('BASELINEDATE', 'NULL', false);
                } else {
                    $res = $res->set('BASELINEDATE', "TO_DATE('" . $value['datadetail'][0]['BASELINEDATE'] . "','mm/dd/yyyy')", false);
                }
                if ($value['datadetail'][0]['DUEDATE'] == NULL || $value['datadetail'][0]['DUEDATE'] == '') {
                    $res = $res->set('DUEDATE', 'NULL', false);
                } else {
                    $res = $res->set('DUEDATE', "TO_DATE('" . $value['datadetail'][0]['DUEDATE'] . "','mm/dd/yyyy')", false);
                }
                $dt['ID'] = $this->uuid->v4();
                $res = $res->set($dt)->insert($this->CF_TRANSACTION);
                if ($res) {
                    foreach ($value['datadetail'] as $val) {
                        $dat = [
                            'ID' => $dt['ID'],
                            'MATERIAL' => $val['MATERIAL'],
                            'REMARKS' => $val['REMARKS'],
                            'AMOUNT_INCLUDE_VAT' => $val['AMOUNT_INCLUDE_VAT'],
                            'AMOUNT_PPH' => $val['AMOUNT_PPH'],
                            'ISACTIVE' => 'TRUE',
                            'FCENTRY' => $Data['USERNAME'],
                            'FCEDIT' => $Data['USERNAME'],
                            'FCIP' => $Location
                        ];
                        $resd = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        $resd = $resd->set($dat)->insert($this->CF_TRANSACTION_DET);
                        if (!$resd) {
                            throw new Exception('Saving Detail Error !!!');
                        }
                    }
                } else {
                    throw new Exception('Saving Error !!!');
                }
            }
            if ($res) {
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

    public function Save_POHana($Data, $Location)
    {
        // echo "<pre>";
        // var_dump($Data);exit();
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $res = FALSE;
            date_default_timezone_set("Asia/Jakarta");
            $UPLOAD_REF = date("Ymd_His") . "_" . $Data['FILENAME'];
            foreach ($Data['DATA'] as $value) {
                $ADENDUM = 'FALSE';
                if ($value['datadetail'][0]['ISADENDUM'] == "1") {
                    $ADENDUM = 'TRUE';
                }
                $dt = [
                    'EXTSYS' => $value['datadetail'][0]['EXTSYSTEM'],
                    'DOCTYPE' => $value['datadetail'][0]['DOCTYPE'],
                    'COMPANY' => $value['COMPANY'],
                    'BUSINESSUNIT' => $value['BUSINESSUNIT'],
                    'DEPARTMENT' => $value['DEPARTMENT'],
                    'DOCNUMBER' => $value['DOCNUMBER'],
                    'DOCREF' => $value['datadetail'][0]['DOCREF'],
                    'VENDOR' => $value['datadetail'][0]['VENDOR'],
                    'TRANS_LOC' => $value['datadetail'][0]['TRANS_LOC'],
                    'PAYTERM' => $value['datadetail'][0]['PAYTERM'],
                    'VENDOR' => $value['datadetail'][0]['VENDOR'],
                    'AMOUNT_INCLUDE_VAT' => abs($value['AMOUNT_INCLUDE_VAT']),
                    'AMOUNT_PPH' => $value['AMOUNT_PPH'],
                    'ISADENDUM' => $ADENDUM,
                    'CURRENCY' => $value['datadetail'][0]['CURRENCY'],
                    'RATE' => $value['datadetail'][0]['RATE'],
                    'INVOICEVENDORNO' => $value['datadetail'][0]['INVOICEVENDORNO'],
                    'ISACTIVE' => 'TRUE',
                    'FCENTRY' => $Data['USERNAME'],
                    'FCEDIT' => $Data['USERNAME'],
                    'FCIP' => $Location,
                    'UPLOAD_REF' => $UPLOAD_REF,
                    'VAT' => 0
                ];

                $cek = $this->db->select('*')
                    ->from($this->CF_TRANSACTION)
                    ->where([
                        'COMPANY' => $dt['COMPANY'],
                        'DOCNUMBER' => $dt['DOCNUMBER'],
                        'DOCREF' => $dt['DOCREF'],
                        'ISACTIVE' => 'TRUE'
                    ])->get()->result();
                if (count($cek) > 0) {
                    throw new Exception('Some Data Already Exists !!!');
                }

                $res = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                if ($value['datadetail'][0]['DOCDATE'] == NULL || $value['datadetail'][0]['DOCDATE'] == '') {
                    $res = $res->set('DOCDATE', 'NULL', false);
                } else {
                    $res = $res->set('DOCDATE', "TO_DATE('" . $value['datadetail'][0]['DOCDATE'] . "','mm/dd/yyyy')", false);
                }
                if ($value['datadetail'][0]['BASELINEDATE'] == NULL || $value['datadetail'][0]['BASELINEDATE'] == '') {
                    $res = $res->set('BASELINEDATE', 'NULL', false);
                } else {
                    $res = $res->set('BASELINEDATE', "TO_DATE('" . $value['datadetail'][0]['BASELINEDATE'] . "','mm/dd/yyyy')", false);
                }
                if ($value['datadetail'][0]['DUEDATE'] == NULL || $value['datadetail'][0]['DUEDATE'] == '') {
                    $res = $res->set('DUEDATE', 'NULL', false);
                } else {
                    $res = $res->set('DUEDATE', "TO_DATE('" . $value['datadetail'][0]['DUEDATE'] . "','mm/dd/yyyy')", false);
                }
                $dt['ID'] = $this->uuid->v4();
                $res = $res->set($dt)->insert($this->CF_TRANSACTION);
                if ($res) {
                    foreach ($value['datadetail'] as $val) {
                        $dat = [
                            'ID' => $dt['ID'],
                            'MATERIAL' => $val['MATERIAL'],
                            'REMARKS' => $val['REMARKS'],
                            'AMOUNT_INCLUDE_VAT' => $val['AMOUNT_INCLUDE_VAT'],
                            'AMOUNT_PPH' => $val['AMOUNT_PPH'],
                            'ISACTIVE' => 'TRUE',
                            'FCENTRY' => $Data['USERNAME'],
                            'FCEDIT' => $Data['USERNAME'],
                            'FCIP' => $Location
                        ];
                        $resd = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        $resd = $resd->set($dat)->insert($this->CF_TRANSACTION_DET);
                        if (!$resd) {
                            throw new Exception('Saving Detail Error !!!');
                        }
                    }
                } else {
                    throw new Exception('Saving Error !!!');
                }
            }
            if ($res) {
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

    public function DataUpload($param) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
                $file = $_FILES['uploads'];
                $inputFileName = $file['tmp_name'];
                $inputFileType = IOFactory::identify($inputFileName);
                $reader = IOFactory::createReader($inputFileType);
                $spreadsheet = $reader->load($inputFileName);
//                $spreadsheet = IOFactory::load($file['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, TRUE, TRUE, TRUE);
//                var_dump($sheetData);
                $UUID = $param['UUID'];
                $EXTSYSTEM = $param['EXTSYSTEM'];
                $DOCTYPE = $param['DOCTYPE'];
                $USERNAME = $param['USERNAME'];
                $idx = 0;
                // echo '<pre>';
                // var_dump($sheetData);exit;
                foreach ($sheetData as $value) {

                    if ($idx > 0) {
                        if (
                                ($value['A'] == NULL || $value['A'] == '') &&
                                ($value['B'] == NULL || $value['B'] == '') &&
                                ($value['C'] == NULL || $value['C'] == '') &&
                                ($value['D'] == NULL || $value['D'] == '') &&
                                ($value['F'] == NULL || $value['F'] == '') &&
                                ($value['G'] == NULL || $value['G'] == '') &&
                                ($value['H'] == NULL || $value['H'] == '') &&
                                ($value['I'] == NULL || $value['I'] == '') &&
                                ($value['J'] == NULL || $value['J'] == '') &&
                                ($value['K'] == NULL || $value['K'] == '') &&
                                ($value['L'] == NULL || $value['L'] == '') &&
                                ($value['M'] == NULL || $value['M'] == '') &&
                                ($value['M'] == NULL || $value['N'] == '') &&
                                ($value['M'] == NULL || $value['O'] == '') &&
                                ($value['M'] == NULL || $value['P'] == '') &&
                                ($value['N'] == NULL || $value['Q'] == '')
                        ) {
                            
                        } else {
                            $rownya = $idx + 1;
                            if($value['E'] == null || $value['E'] == ''){
                                throw new Exception("Docnumber row $rownya cant empty");
                            }

                            // if($value['D'] != $value['I'] ){
                            //     throw new Exception("Tanggal tidak sama pada Row $rownya");
                            // }


                            if (!isset($value['N'])) {
                                $value['N'] = "0";
                            }
                            if (!isset($value['O'])) {
                                $value['O'] = "0";
                            }
                            if (!isset($value['P'])) {
                                $value['P'] = "IDR";
                            }
                            if (!isset($value['Q'])) {
                                $value['Q'] = "1";
                            }

                            $status = 0;
                            $dt = [
                                'UUID' => $UUID,
                                'ID' => $idx,
                                'COMPANY' => '',
                                'COMPANYCODE' => strval($value['A']),
                                'BUSINESSUNIT' => '',
                                'BUSINESSUNITCODE' => strval($value['B']),
                                'DEPARTMENT' => '',
                                'DEPARTMENTCODE' => strval($value['C']),
                                'DOCDATE' => strval($value['D']),
                                'DOCNUMBER' => strval($value['E']),
                                'DOCREF' => strval($value['F']),
                                'VENDORCODE' => trim(strval($value['G'])),
                                'TRANS_LOC' => strval($value['H']),
                                'BASELINEDATE' => strval($value['I']),
                                'PAYTERM' => trim(strval($value['J'])),
                                'MATERIALCODE' => trim(strval($value['K'])),
                                'REMARKS' => strval($value['L']),
                                'AMOUNT_INCLUDE_VAT' => strval(trim(str_replace(",", "", $value['M']))),
                                'AMOUNT_PPH' => strval(trim(str_replace(",", "", $value['N']))),
                                'STATUSH' => 0,
                                'STATUSD' => $status,
                                'MESSAGEH' => '',
                                'MESSAGED' => '',
                                'EXTSYSTEM' => $EXTSYSTEM,
                                'DOCTYPE' => $DOCTYPE,
                                'CURRENCY' => $value['P'],
                                'ISADENDUM' => $value['O'],
                                'RATE' => $value['Q'],
                                'INVOICEVENDORNO' => $value['R']
                            ];
                            if ($DOCTYPE == 'INV') {
                                if ($dt["DOCREF"] == NULL || $dt["DOCREF"] == '') {
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Doc Ref can't be empty !!";
                                }
                            }
                            if ($DOCTYPE == 'SPO') {
                                if ($dt["DOCNUMBER"] == NULL || $dt["DOCNUMBER"] == '') {
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "DocNumber can't be empty !!";
                                }
                            }
                            if ($dt["DOCDATE"] != NULL || $dt["DOCDATE"] != '') {
                                $date = explode('/', $dt['DOCDATE']);
                                $bln1 = substr("0" . $date[0], -2);
                                $tgl1 = substr("0" . $date[1], -2);
                                $thn1 = $date[2];
                                $dt["DOCDATE"] = $bln1 . "/" . $tgl1 . "/" . $thn1;
                                if (!is_numeric($thn1) || !is_numeric($tgl1) || !is_numeric($bln1) || $bln1 > 12) {
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Format Date Not Valid !!!";
                                }
                            }
                            if ($dt["AMOUNT_INCLUDE_VAT"] != NULL && $dt["AMOUNT_INCLUDE_VAT"] != '') {
                                $number = $dt["AMOUNT_INCLUDE_VAT"];
                                if (!is_numeric($number)) {
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Format Amount Include Vat Not Valid !!!";
                                }
                            }
                            if ($dt["AMOUNT_PPH"] != NULL && $dt["AMOUNT_PPH"] != '') {
                                $number = $dt["AMOUNT_PPH"];
                                if (!is_numeric($number)) {
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Format Amount PPH Not Valid !!!";
                                }
                            }
                            if ($dt["PAYTERM"] != NULL && $dt["PAYTERM"] != '') {
                                if (!is_numeric($dt["PAYTERM"])) {
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Format Payterm Not Valid !!!";
                                }
                            }
                            if ($dt["BASELINEDATE"] != NULL && $dt["BASELINEDATE"] != '') {
                                $date = explode('/', $dt['BASELINEDATE']);
                                $bln1 = substr("0" . $date[0], -2);
                                $tgl1 = substr("0" . $date[1], -2);
                                $thn1 = $date[2];
                                $dt["BASELINEDATE"] = $bln1 . "/" . $tgl1 . "/" . $thn1;
                                if (!is_numeric($thn1) || !is_numeric($tgl1) || !is_numeric($bln1) || $bln1 > 12) {
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Format Date Not Valid !!!";
                                }
                                if ($dt["PAYTERM"] != NULL && $dt["PAYTERM"] != '') {
                                    if (is_numeric($dt["PAYTERM"])) {
                                        $dt['DUEDATE'] = strval(date('m/d/Y', strtotime($dt['PAYTERM'] . " days", strtotime($thn1 . $bln1 . $tgl1))));
                                    }
                                }
                            }
                            if($dt["AMOUNT_INCLUDE_VAT"] == null || $dt["AMOUNT_INCLUDE_VAT"] == 0){
                                throw new Exception('Amount Tidak Boleh Kosong.');        
                            }
                            
//                        array_push($data, $dt);
                            $data[] = $dt;
//                        $result = $this->db->insert($this->TEMP_UPLOAD_PO, $dt);
                        }
                    }
                    $idx++;
                }
                $result = $this->db->insert_batch($this->TEMP_UPLOAD_PO, $data);
                if ($result) {
                    $this->CheckValidation($EXTSYSTEM, $UUID, $USERNAME);
                    // $this->CheckValidationBaru($EXTSYSTEM, $UUID, $USERNAME,$dt['DOCNUMBER']);
//                  Update Error Di Header PO
//                     $SQL = "                             
// UPDATE $this->TEMP_UPLOAD_PO UPO
//    SET (STATUSH, MESSAGEH) = (SELECT DECODE(TP.JML, 1, (SELECT DISTINCT TEU.STATUSD
//                                                           FROM $this->TEMP_UPLOAD_PO TEU
//                                                          WHERE TEU.UUID = TP.UUID 
//                                                            AND (TEU.COMPANYCODE = TP.COMPANYCODE OR TEU.COMPANYCODE IS NULL)
//                                                            AND (TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE OR TEU.BUSINESSUNITCODE IS NULL)
//                                                            AND (TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE OR TEU.DEPARTMENTCODE IS NULL)
//                                                            AND TEU.DOCNUMBER = TP.DOCNUMBER), 1) AS STATUSH, 
//                                      DECODE(TP.JML, 1, (SELECT DISTINCT TEU.MESSAGED
//                                                           FROM $this->TEMP_UPLOAD_PO TEU
//                                                          WHERE TEU.UUID = TP.UUID 
//                                                            AND (TEU.COMPANYCODE = TP.COMPANYCODE OR TEU.COMPANYCODE IS NULL)
//                                                            AND (TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE OR TEU.BUSINESSUNITCODE IS NULL)
//                                                            AND (TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE OR TEU.DEPARTMENTCODE IS NULL)
//                                                            AND TEU.DOCNUMBER = TP.DOCNUMBER), 'Please, Check Data Doc Date until Payterm must be the same, and there are no errors lined up !!') AS MESSAGEH
//                                 FROM (SELECT TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, COUNT(*) AS JML
//                                         FROM (SELECT DISTINCT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, DOCDATE, VENDOR, DOCTYPE, DOCREF, TRANS_LOC,
//                                                      BASELINEDATE, PAYTERM, MESSAGED
//                                                 FROM $this->TEMP_UPLOAD_PO
//                                                WHERE UUID = ?) TUP
//                                        GROUP BY TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER) TP 
//                                WHERE (TP.COMPANYCODE = UPO.COMPANYCODE OR TP.COMPANYCODE IS NULL)
//                                  AND (TP.BUSINESSUNITCODE = UPO.BUSINESSUNITCODE OR TP.BUSINESSUNITCODE IS NULL)
//                                  AND (TP.DEPARTMENTCODE = UPO.DEPARTMENTCODE OR TP.DEPARTMENTCODE IS NULL)
//                                  AND TP.DOCNUMBER = UPO.DOCNUMBER
//                                  AND TP.UUID = UPO.UUID)
//  WHERE UPO.UUID = ?";
//                     $result = $this->db->query($SQL, [$UUID, $UUID]);
                    
//                  Select Header PO and Get Detail PO
                    $SQL = "
SELECT DISTINCT TUP.COMPANY, TUP.COMPANYCODE, TUP.BUSINESSUNIT, TUP.BUSINESSUNITCODE, TUP.DEPARTMENT, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, 
       TUP.STATUSH, TUP.MESSAGEH, TP.JML, TP.AMOUNT_INCLUDE_VAT, TP.AMOUNT_PPH
  FROM $this->TEMP_UPLOAD_PO TUP
 INNER JOIN (SELECT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, COUNT(*) AS JML, SUM(AMOUNT_INCLUDE_VAT) AS AMOUNT_INCLUDE_VAT, SUM(AMOUNT_PPH) AS AMOUNT_PPH
               FROM $this->TEMP_UPLOAD_PO 
              WHERE UUID = ?
              GROUP BY UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER) TP
         ON TP.UUID = TUP.UUID
        AND (TP.COMPANYCODE = TUP.COMPANYCODE OR TP.COMPANYCODE IS NULL)
        AND (TP.BUSINESSUNITCODE = TUP.BUSINESSUNITCODE OR TP.BUSINESSUNITCODE IS NULL)
        AND (TP.DEPARTMENTCODE = TUP.DEPARTMENTCODE OR TP.DEPARTMENTCODE IS NULL)
        AND TP.DOCNUMBER = TUP.DOCNUMBER
 WHERE TUP.UUID = ?";
                    $data = $this->db->query($SQL, [$UUID, $UUID])->result();
                    $SQL = "SELECT * FROM TEMP_UPLOAD_PO 
                             WHERE UUID = ?
                               AND (COMPANYCODE = ? OR COMPANYCODE IS NULL)
                               AND (BUSINESSUNITCODE = ? OR BUSINESSUNITCODE IS NULL)
                               AND (DEPARTMENTCODE = ? OR DEPARTMENTCODE IS NULL)
                               AND DOCNUMBER = ?";
                    foreach ($data as $values) {
                        $DtParam = [$UUID, $values->COMPANYCODE, $values->BUSINESSUNITCODE, $values->DEPARTMENTCODE, $values->DOCNUMBER];
                        $values->datadetail = $this->db->query($SQL, $DtParam)->result();
                    }

                    $SQL = "DELETE FROM $this->TEMP_UPLOAD_PO WHERE UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $data
                ];
            } else {
                throw new Exception('Upload Failed !!');
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

    public function UploadPO1($param) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $data = [];
            $UUID = $param['UUID'];
            $EXTSYSTEM = $param['EXTSYSTEM'];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
                $file = $_FILES['uploads'];
                $spreadsheet = IOFactory::load($file['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->removeRow(1)->toArray(null, true, true, true);
                foreach ($sheetData as $value) {
                    if (
                            $value['A'] == NULL && $value['B'] == NULL && $value['C'] == NULL && $value['D'] == NULL && $value['E'] == NULL && $value['F'] == NULL &&
                            $value['G'] == NULL && $value['H'] == NULL && $value['I'] == NULL && $value['J'] == NULL && $value['K'] == NULL && $value['L'] == NULL &&
                            $value['M'] == NULL && $value['N'] == NULL && $value['O'] == NULL && $value['P'] == NULL && $value['Q'] == NULL && $value['R'] == NULL &&
                            $value['S'] == NULL && $value['T'] == NULL && $value['U'] == NULL
                    ) {
                        
                    } else {
                        $status = 0;
                        $dt = [
                            'UUID' => $param['UUID'],
                            'STATUS' => $status,
                            'PURCHDOC' => $value['A'],
                            'ITEM1' => $value['B'],
                            'VENDORCODE' => $value['C'],
                            'MATGROUP' => $value['D'],
                            'MAT' => $value['E'],
                            'POR' => $value['F'],
                            'PGP' => $value['G'],
                            'PLANT' => $value['H'],
                            'SLOC' => $value['I'],
                            'PODATE' => $value['J'],
                            'POQTY' => $value['K'],
                            'UOM' => $value['L'],
                            'PRICE' => $value['M'],
                            'CCY' => $value['N'],
                            'CONDTYPE' => $value['O'],
                            'AMOUNT' => $value['P'],
                            'TRANSPORTER' => $value['Q'],
                            'PRNO' => $value['R'],
                            'ITEM2' => $value['S'],
                            'RLS' => $value['T'],
                            'EXCRATE' => $value['U']
                        ];
                        $dt['COMPANYCODE'] = substr($dt['PLANT'], 0, 2);
                        $dt['BUSINESSUNITCODE'] = substr($dt['PLANT'], 0, 2) . substr($dt['SLOC'], 2, 2);
                        $dt['DOCDATE'] = substr($dt['PODATE'], 3, 2) . "/" . substr($dt['PODATE'], 0, 2) . "/" . substr($dt['PODATE'], -4);
                        $dt['DEPARTMENTCODE'] = $param['DEPARTMENT'];
                        array_push($data, $dt);
                    }
                }
                $result = $this->db->insert_batch($this->TEMPPO1, $data);
                if ($result) {
//                    Update Field Company and Pesan Error
                    $SQL = "UPDATE $this->TEMPPO1 TUP
                               SET TUP.COMPANY = (SELECT c.ID
                                                    FROM $this->COMPANY C 
                                                   INNER JOIN $this->COMPANY_EXTSYS CE
                                                           ON CE.COMPANY = C.ID
                                                          AND CE.EXTSYSTEM = ?
                                                   WHERE C.ISACTIVE = 1 
                                                     AND CE.EXTSYSCOMPANYCODE = TUP.COMPANYCODE)
                             WHERE TUP.STATUS <> 1
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);

                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.STATUS = 1,
                                   TUP.MESSAGE = 'Company Not Found !!!'
                             WHERE TUP.STATUS <> 1 
                               AND TUP.COMPANY IS NULL
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

//                  Update Field Business Unit and Pesan Error
                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.BUSINESSUNIT = (SELECT B.ID
                                                         FROM $this->BUSINESSUNIT B
                                                        INNER JOIN $this->BUSINESSUNIT_EXTSYS BE
                                                                ON BE.BUSINESSUNIT = B.ID
                                                               AND BE.EXTSYSTEM = ?
                                                        WHERE B.ISACTIVE = 'TRUE'
                                                          AND B.COMPANY = TUP.COMPANY
                                                          AND BE.EXTSYSBUSINESSUNITCODE = TUP.BUSINESSUNITCODE)
                             WHERE TUP.STATUS <> 1
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.STATUS = 1,
                                   TUP.MESSAGE = 'Business Unit Not Found !!!'
                             WHERE TUP.STATUS <> 1 
                               AND TUP.BUSINESSUNIT IS NULL
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

//                  Update Field Department and Pesan Error
                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.DEPARTMENT = (SELECT DEPARTMENT
                                                       FROM $this->COMPANY_DEPART
                                                      WHERE COMPANY = TUP.COMPANY
                                                        AND DEPARTMENT = TUP.DEPARTMENTCODE)
                             WHERE TUP.STATUS <> 1
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);
                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.STATUS = 1,
                                   TUP.MESSAGE = 'Department Not Found !!!'
                             WHERE TUP.STATUS <> 1 
                               AND TUP.DEPARTMENT IS NULL
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

//                    Update Field Material
                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.MATERIAL = (SELECT ID
                                                       FROM $this->MATERIAL 
                                                      WHERE FCCODE = TUP.MAT
                                                        AND EXTSYSTEM = ?
                                                        AND ISACTIVE = 'TRUE')
                             WHERE TUP.STATUS <> 1
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.STATUS = 1,
                                   TUP.MESSAGE = 'Material Not Found !!!'
                             WHERE TUP.STATUS <> 1 
                               AND TUP.MATERIAL IS NULL
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

//                    Update Field Vendor
                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.VENDOR = TUP.VENDORCODE
                             WHERE TUP.STATUS <> 1
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);
                    /*                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                      SET TUP.VENDOR = (SELECT ID
                      FROM $this->SUPPLIER
                      WHERE FCCODE = TUP.VENDORCODE
                      AND COMPANY = TUP.COMPANY
                      AND BUSINESSUNIT = TUP.BUSINESSUNIT
                      AND ISACTIVE = 'TRUE')
                      WHERE TUP.STATUS <> 1
                      AND TUP.UUID = ?";
                      $result = $this->db->query($SQL, [$UUID]);
                      $SQL = "UPDATE $this->TEMPPO1 TUP
                      SET TUP.STATUS = 1,
                      TUP.MESSAGE = 'Vendor Not Found !!!'
                      WHERE TUP.STATUS <> 1
                      AND TUP.VENDOR IS NULL
                      AND TUP.UUID = ?";
                      $result = $this->db->query($SQL, [$UUID]); */

//                    Get Data PO 1
                    $SQL = "SELECT * 
                              FROM $this->TEMPPO1 TUP 
                             WHERE TUP.UUID = ?";
                    $data = $this->db->query($SQL, [$UUID])->result();

                    $SQL = "DELETE FROM $this->TEMPPO1 WHERE UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $data
                ];
            } else {
                throw new Exception('Upload Failed !!');
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

    public function UploadPO2($param) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $data = [];
            $UUID = $param['UUID'];
            $EXTSYSTEM = $param['EXTSYSTEM'];
            $Dt_PO1 = json_decode($param['Dt_PO1']);
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
                $file = $_FILES['uploads'];
                $spreadsheet = IOFactory::load($file['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->removeRow(1)->toArray(null, true, true, true);
                foreach ($sheetData as $value) {
                    if (
                            $value['A'] == NULL && $value['B'] == NULL && $value['C'] == NULL && $value['D'] == NULL && $value['E'] == NULL && $value['F'] == NULL &&
                            $value['G'] == NULL && $value['H'] == NULL && $value['I'] == NULL && $value['J'] == NULL && $value['K'] == NULL && $value['K'] == NULL &&
                            $value['L'] == NULL && $value['M'] == NULL && $value['N'] == NULL && $value['O'] == NULL && $value['P'] == NULL
                    ) {
                        
                    } else {
                        $status = 0;
                        $dt = [
                            'UUID' => $param['UUID'],
                            'STATUS' => $status,
                            'PONO' => explode('/', $value['A'])[0],
                            'POLINENO' => $value['A'],
                            'PODATE' => $value['B'],
                            'PLAN' => $value['C'],
                            'SUPPLIER' => $value['D'],
                            'MAT' => $value['E'],
                            'MAT_DESC' => $value['F'],
                            'PO_QTY' => $value['G'],
                            'PO_VALUE' => $value['J'],
                            'RLS' => $value['K'],
                            'GR_QTTY' => $value['L'],
                            'INV_AMOUNT' => $value['M'],
                            'OS_AMOUNT' => $value['N'],
                            'ZTERMS' => $value['O'],
                            'DUEDATESAP' => $value['P']
                        ];
                        if ($dt['DUEDATESAP'] != NULL && $dt['DUEDATESAP'] != '') {
                            $dt['DUEDATE'] = substr($dt['DUEDATESAP'], 3, 2) . "/" . substr($dt['DUEDATESAP'], 0, 2) . "/" . substr($dt['DUEDATESAP'], -4);
                        }
                        if ($dt['ZTERMS'] != NULL && $dt['ZTERMS'] != '') {
                            $dt['PAYTERM'] = substr($dt['ZTERMS'], 1, strlen($dt['ZTERMS']) - 2);
                        }
                        if (($dt['DUEDATESAP'] != NULL && $dt['DUEDATESAP'] != '') && ($dt['ZTERMS'] != NULL && $dt['ZTERMS'] != '')) {
                            $bln = substr($dt['DUEDATE'], 0, 2);
                            $tgl = substr($dt['DUEDATE'], 3, 2);
                            $thn = substr($dt['DUEDATE'], -4);
                            $dt['BASELINEDATE'] = date('m/d/Y', strtotime("-" . $dt['PAYTERM'] . " days", strtotime($thn . $bln . $tgl)));
                        }
                        array_push($data, $dt);
                    }
                }
                $result = $this->db->insert_batch($this->TEMPPO2, $data);
                $result1 = $this->db->insert_batch($this->TEMPPO1, $Dt_PO1);
                if ($result) {
//                    Update Field Material
                    $SQL = "UPDATE $this->TEMPPO2 TUP 
                               SET TUP.MATERIAL = (SELECT ID
                                                       FROM $this->MATERIAL 
                                                      WHERE FCCODE = TUP.MAT
                                                        AND EXTSYSTEM = ?
                                                        AND ISACTIVE = 'TRUE')
                             WHERE TUP.STATUS <> 1
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
                    $SQL = "UPDATE $this->TEMPPO2 TUP 
                               SET TUP.STATUS = 1,
                                   TUP.MESSAGE = 'Material Not Found !!!'
                             WHERE TUP.STATUS <> 1 
                               AND TUP.MATERIAL IS NULL
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

//                    Check Data PO2 Vs PO1
                    $SQL = "UPDATE $this->TEMPPO2 TUP 
                               SET TUP.STATUS = 1,
                                   TUP.MESSAGE = 'Data Not Found in PO 1 !!!'
                             WHERE TUP.STATUS <> 1 
                               AND CONCAT(TUP.PONO, TUP.MATERIAL) IN (SELECT CONCAT(PO2.PONO, PO2.MATERIAL)
                                                                        FROM $this->TEMPPO2 PO2
                                                                        LEFT JOIN $this->TEMPPO1 PO1
                                                                               ON PO1.PURCHDOC = PO2.PONO
                                                                              AND PO1.MATERIAL = PO2.MATERIAL
                                                                              AND PO1.UUID = PO2.UUID
                                                                       WHERE PO1.PURCHDOC IS NULL
                                                                         AND PO2.UUID = ?)
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID, $UUID]);

//                    Check PO1 Vs PO2
                    $SQL = "UPDATE $this->TEMPPO1 TUP 
                               SET TUP.STATUS = 1,
                                   TUP.MESSAGE = 'Data Not Found in PO 2 !!!'
                             WHERE TUP.STATUS <> 1 
                               AND CONCAT(TUP.PURCHDOC, TUP.MATERIAL) IN (SELECT CONCAT(PO1.PURCHDOC, PO1.MATERIAL)
                                                                        FROM $this->TEMPPO1 PO1
                                                                        LEFT JOIN $this->TEMPPO2 PO2
                                                                               ON PO2.PONO = PO1.PURCHDOC
                                                                              AND PO2.MATERIAL = PO1.MATERIAL
                                                                              AND PO2.UUID = PO1.UUID
                                                                       WHERE PO2.PONO IS NULL
                                                                         AND PO1.UUID = ?)
                               AND TUP.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID, $UUID]);

//                    insert data to Temp_Upload_PO
                    $SQL = "INSERT INTO $this->TEMP_UPLOAD_PO (UUID, COMPANY, COMPANYCODE, BUSINESSUNIT, BUSINESSUNITCODE, DEPARTMENT, DEPARTMENTCODE, DOCNUMBER, DOCDATE, VENDOR, 
                                   REMARKS, MATERIAL, AMOUNT_INCLUDE_VAT, AMOUNT_PPH, STATUSH, STATUSD, MESSAGEH, MESSAGED, EXTSYSTEM, MATERIALCODE, 
                                   DOCTYPE, DOCREF, TRANS_LOC, BASELINEDATE, PAYTERM, DUEDATE)
                            SELECT PO1.UUID, PO1.COMPANY, PO1.COMPANYCODE, PO1.BUSINESSUNIT, PO1.BUSINESSUNITCODE, PO1.DEPARTMENT, PO1.DEPARTMENTCODE, PO1.PURCHDOC, PO1.DOCDATE, PO1.VENDOR,
                                   '', PO1.MATERIAL, PO2.PO_VALUE, 0, 0, 0, '', '', 'SAP', PO1.MAT, 'PO', '', '', PO2.BASELINEDATE, PO2.PAYTERM, PO2.DUEDATE
                              FROM $this->TEMPPO1 PO1
                             INNER JOIN $this->TEMPPO2 PO2
                                     ON PO2.PONO = PO1.PURCHDOC
                                    AND PO2.MATERIAL = PO1.MATERIAL
                                    AND PO2.UUID = PO1.UUID
                             WHERE PO1.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

//                    Get Data PO 2
                    $SQL = "SELECT * 
                              FROM $this->TEMPPO2 TUP 
                             WHERE TUP.UUID = ?";
                    $data = $this->db->query($SQL, [$UUID])->result();

//                    Get Data PO1 Vs PO2
                    $SQL = "SELECT * 
                              FROM $this->TEMPPO1 TUP 
                             WHERE TUP.UUID = ?";
                    $DtPO1VsPO2 = $this->db->query($SQL, [$UUID])->result();

//                    Get Data Temp_Upload_PO
                    $SQL = "SELECT * 
                              FROM $this->TEMP_UPLOAD_PO TUP 
                             WHERE TUP.UUID = ?";
                    $DtTempPO = $this->db->query($SQL, [$UUID])->result();

                    $SQL = "DELETE FROM $this->TEMPPO2 WHERE UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

                    $SQL = "DELETE FROM $this->TEMPPO1 WHERE UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

                    $SQL = "DELETE FROM $this->TEMP_UPLOAD_PO WHERE UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => [
                        'Dt_PO2' => $data,
                        'Dt_PO1VsPO2' => $DtPO1VsPO2,
                        'Dt_TempPO' => $DtTempPO
                    ]
                ];
            } else {
                throw new Exception('Upload Failed !!');
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

    public function SetUploadPO($param) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $data = $param['DATA'];
            $UUID = $param['UUID'];
            $result = $this->db->insert_batch($this->TEMP_UPLOAD_PO, $data);
            if ($result) {
//                  Update Error Di Header PO $this->TEMP_UPLOAD_PO
                $SQL = "                             
UPDATE $this->TEMP_UPLOAD_PO UPO
   SET (STATUSH, MESSAGEH) = (SELECT DECODE(TP.JML, 1, (SELECT DISTINCT TEU.STATUSD
                                                          FROM $this->TEMP_UPLOAD_PO TEU
                                                         WHERE TEU.UUID = TP.UUID 
                                                           AND TEU.COMPANYCODE = TP.COMPANYCODE
                                                           AND TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE
                                                           AND TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE
                                                           AND TEU.DOCNUMBER = TP.DOCNUMBER), 1) AS STATUSH, 
                                     DECODE(TP.JML, 1, (SELECT DISTINCT TEU.MESSAGED
                                                          FROM $this->TEMP_UPLOAD_PO TEU
                                                         WHERE TEU.UUID = TP.UUID 
                                                           AND TEU.COMPANYCODE = TP.COMPANYCODE
                                                           AND TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE
                                                           AND TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE
                                                           AND TEU.DOCNUMBER = TP.DOCNUMBER), 'Please, Check Data Doc Date until Payterm must be the same, and there are no errors lined up !!') AS MESSAGEH
                                FROM (SELECT TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, COUNT(*) AS JML
                                        FROM (SELECT DISTINCT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, DOCDATE, VENDOR, DOCTYPE, DOCREF, TRANS_LOC,
                                                     BASELINEDATE, PAYTERM, DUEDATE
                                                FROM $this->TEMP_UPLOAD_PO
                                               WHERE UUID = ?) TUP
                                       GROUP BY TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER) TP 
                               WHERE TP.COMPANYCODE = UPO.COMPANYCODE
                                 AND TP.BUSINESSUNITCODE = UPO.BUSINESSUNITCODE
                                 AND TP.DEPARTMENTCODE = UPO.DEPARTMENTCODE
                                 AND TP.DOCNUMBER = UPO.DOCNUMBER
                                 AND TP.UUID = UPO.UUID)
 WHERE UPO.UUID = ?";
                $result = $this->db->query($SQL, [$UUID, $UUID]);

//                  Select Header PO and Get Detail PO
                $SQL = "
SELECT DISTINCT TUP.COMPANY, TUP.COMPANYCODE, TUP.BUSINESSUNIT, TUP.BUSINESSUNITCODE, TUP.DEPARTMENT, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, 
       TUP.STATUSH, TUP.MESSAGEH, TP.JML, TP.AMOUNT_INCLUDE_VAT, TP.AMOUNT_PPH
  FROM $this->TEMP_UPLOAD_PO TUP
 INNER JOIN (SELECT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, COUNT(*) AS JML, SUM(AMOUNT_INCLUDE_VAT) AS AMOUNT_INCLUDE_VAT, SUM(AMOUNT_PPH) AS AMOUNT_PPH
               FROM $this->TEMP_UPLOAD_PO 
              WHERE UUID = ?
              GROUP BY UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER) TP
         ON TP.UUID = TUP.UUID
        AND TP.COMPANYCODE = TUP.COMPANYCODE
        AND TP.BUSINESSUNITCODE = TUP.BUSINESSUNITCODE
        AND TP.DEPARTMENTCODE = TUP.DEPARTMENTCODE
        AND TP.DOCNUMBER = TUP.DOCNUMBER
 WHERE TUP.UUID = ?";
                $data = $this->db->query($SQL, [$UUID, $UUID])->result();
                $SQL = "SELECT * FROM TEMP_UPLOAD_PO 
                             WHERE UUID = ?
                               AND COMPANYCODE = ?
                               AND BUSINESSUNITCODE = ?
                               AND DEPARTMENTCODE = ?
                               AND DOCNUMBER = ?";
                foreach ($data as $values) {
                    $DtParam = [$UUID, $values->COMPANYCODE, $values->BUSINESSUNITCODE, $values->DEPARTMENTCODE, $values->DOCNUMBER];
                    $values->datadetail = $this->db->query($SQL, $DtParam)->result();
                }

                $SQL = "DELETE FROM $this->TEMP_UPLOAD_PO WHERE UUID = ?";
                $result = $this->db->query($SQL, [$UUID]);
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $data
                ];
            } else {
                throw new Exception('Upload Failed !!');
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

    public function UploadPO5($param) {

        try {
            $this->db->trans_begin();
            $result = FALSE;
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
                $file = $_FILES['uploads'];
                $inputFileName = $file['tmp_name'];
                $inputFileType = IOFactory::identify($inputFileName);
                $reader = IOFactory::createReader($inputFileType);
                $spreadsheet = $reader->load($inputFileName);
//                $spreadsheet = IOFactory::load($file['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                // echo "<pre>";
                // var_dump($sheetData);exit();
                $UUID = $param['UUID'];
                $EXTSYSTEM = $param['EXTSYSTEM'];
                $USERNAME = $param['USERNAME'];
                $DEPARTMENT = $param['DEPARTMENT'];
                $idx = 1;
                $idx1 = 0;
                $DOCTYPE = '';
                $dt = [];
                
                foreach ($sheetData as $value) {
                     
                    if ($idx1 > 0) {
                        if (
                                $value['A'] == "Summary of Daily Purchase / Service Order" || $value['A'] == NULL && $value['B'] == NULL && $value['C'] == NULL && $value['D'] == NULL && $value['E'] == NULL && $value['F'] == NULL &&
                                $value['G'] == NULL && $value['H'] == NULL && $value['I'] == NULL && $value['J'] == NULL && $value['K'] == NULL && $value['L'] == NULL &&
                                $value['M'] == NULL && $value['N'] == NULL && $value['O'] == NULL && $value['P'] == NULL && $value['Q'] == NULL && $value['R'] == NULL &&
                                $value['S'] == NULL && $value['T'] == NULL && $value['U'] == NULL && $value['V'] == NULL && $value['W'] == NULL && $value['X'] == NULL &&
                                $value['Y'] == NULL && $value['Z'] == NULL && $value['AA'] == NULL && $value['AB'] == NULL && $value['AC'] == NULL && $value['AD'] == NULL &&
                                $value['AE'] == NULL && $value['AF'] == NULL && $value['AG'] == NULL && $value['AH'] == NULL && $value['AI'] == NULL && $value['AJ'] == NULL &&
                                $value['AK'] == NULL && $value['AL'] == NULL && $value['AM'] == NULL && $value['AN'] == NULL && $value['AO'] == NULL && $value['AP'] == NULL &&
                                $value['AQ'] == NULL && $value['AR'] == NULL && $value['AS'] == NULL && $value['AT'] == NULL && $value['AU'] == NULL
                        ) {
                            continue;
                        } else {
                            
                            if (($value['D'] == NULL || $value['D'] == '') && ($value['AO'] != NULL && $value['AO'] != '' && $value['AP'] != NULL && $value['AP'] != '')) {
                                if ($DOCTYPE == '8' || $DOCTYPE == '9') {
                                    $dt["ID"] = $idx;
                                    $dt["MATERIALCODE"] = $value['AO'];
                                    array_push($data, $dt);
                                    $idx++;
                                }
                            } else {
                                if (($DOCTYPE == '8' || $DOCTYPE == '9') && $dt['ID'] == $idx) {
                                    array_push($data, $dt);
                                    $idx++;
                                }
                                $status = 0;

                                // $xlsBU        = substr($value['I'], 0, 2) . substr($value['J'], 2, 2);
                                // $qGetIdBu     = "SELECT ID FROM BUSINESSUNIT WHERE FCCODE = '$xlsBU'";
                                // $getIdBu      = $this->db->query($qGetIdBu)->row();
                                // // $getIdBu      = $getIdBu->ID;

                                // if($getIdBu == NULL){
                                //     $IDBU = 'BUSINESSUNIT NOT FOUND';
                                //     $valJ = $IDBU;
                                // }else{
                                //     $IDBU = $getIdBu->ID;
                                //     $valJ = substr($value['I'], 0, 2) . substr($value['J'], 2, 2);
                                // }
                                $valA = $value['A'];
                                $VAT = trim(str_replace(",", "", strval($value['AD'])));
                                if($VAT == null || $VAT == 0){
                                    throw new Exception(" Row No $valA Amount Tidak Boleh Kosong.");        
                                }

                                $dt = [
                                    'UUID' => $UUID,
                                    'ID' => $idx,
                                    'COMPANY' => '',
                                    'COMPANYCODE' => substr($value['I'], 0, 2),
                                    'BUSINESSUNIT' => '',
                                    'BUSINESSUNITCODE' => substr($value['I'], 0, 2) . substr($value['J'], 2, 2),
                                    'DEPARTMENT' => '',
                                    'DEPARTMENTCODE' => $DEPARTMENT,
                                    'DOCTYPE' => strval($value['C']),
                                    'DOCDATE' => strval($value['H']),
                                    'DOCNUMBER' => strval($value['E']),
                                    'DOCREF' => '',
                                    'VENDORCODE' => trim(str_replace(",", "", strval($value['M']))),
                                    'TRANS_LOC' => '',
                                    'BASELINEDATE' => '', //$value['AE']
                                    'PAYTERM' => '', //$value['AL']
                                    'DUEDATE' => '',
                                    'MATERIALCODE' => strval($value['P']),
                                    'REMARKS' => strval($value['Q']),
                                    'AMOUNT_INCLUDE_VAT' => trim(str_replace(",", "", strval($value['AD']))),
                                    'AMOUNT_PPH' => 0,
                                    'STATUSH' => 0,
                                    'STATUSD' => $status,
                                    'MESSAGEH' => '',
                                    'MESSAGED' => '',
                                    'EXTSYSTEM' => $EXTSYSTEM,
                                    'CURRENCY' => $value['W'],
                                    'ISADENDUM' => '0',
                                    'RATE' => '1'
                                ];

                                $DOCTYPE = substr($dt['DOCTYPE'], 2, 1);
                                if ($DOCTYPE == '2') {
                                    $dt['DOCTYPE'] = "PO";
                                } elseif ($DOCTYPE == '7') {
                                    $dt['DOCTYPE'] = "STO";
                                } elseif ($DOCTYPE == '8' || $DOCTYPE == '9') {
                                    $dt['DOCTYPE'] = "SPO";
                                } else {
                                    $dt['DOCTYPE'] = "";
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Doc Type Not Found !!";
                                }

                                if ($DOCTYPE == '8' || $DOCTYPE == '9') {
                                    if ($dt['BASELINEDATE'] != NULL && $dt['BASELINEDATE'] != '') {
                                        $date = explode('.', $dt['BASELINEDATE']);
                                        $bln2 = substr("0" . $date[1], -2);
                                        $tgl2 = substr("0" . $date[0], -2);
                                        $thn2 = $date[2];
                                        $dt['BASELINEDATE'] = $bln2 . "/" . $tgl2 . "/" . $thn2;
                                    }
                                } else {
                                    $dt['BASELINEDATE'] = "";
                                }
                                if ($dt['PAYTERM'] != NULL && $dt['PAYTERM'] != '') {
                                    $dt['PAYTERM'] = substr($dt['PAYTERM'], 1, strlen($dt['PAYTERM']) - 2);
                                    if (!is_numeric($dt['PAYTERM'])) {
                                        $dt['PAYTERM'] = "";
                                    }
                                }
                                if ($dt['BASELINEDATE'] != '' && $dt['PAYTERM'] != '') {
                                    $dt['DUEDATE'] = date('m/d/Y', strtotime($dt['PAYTERM'] . " days", strtotime($thn2 . $bln2 . $tgl2)));
                                }

                                if ($dt['DOCDATE'] != NULL && $dt['DOCDATE'] != '') {
                                    $date = explode(".", $dt['DOCDATE']);
                                    $bln1 = substr("0" . $date[1], -2);
                                    $tgl1 = substr("0" . $date[0], -2);
                                    $thn1 = $date[2];
                                    $dt['DOCDATE'] = $bln1 . "/" . $tgl1 . "/" . $thn1;
                                }

                                if ($DOCTYPE != '8' && $DOCTYPE != '9') {
                                    array_push($data, $dt);
                                    $idx++;
                                }
                            }
                        }
                    }
                    $idx1++;
                }
                $result = $this->db->insert_batch($this->TEMP_UPLOAD_PO, $data);
                // var_dump($this->db->error());exit();
                if ($result) {
                    $this->CheckValidation($EXTSYSTEM, $UUID, $USERNAME);
//                  Update Error Di Header PO
                    $SQL = "                             
UPDATE $this->TEMP_UPLOAD_PO UPO
    SET (STATUSH, MESSAGEH) = (SELECT DECODE(TP.JML, 1, (SELECT DISTINCT TEU.STATUSD
                                                           FROM $this->TEMP_UPLOAD_PO TEU
                                                          WHERE TEU.UUID = TP.UUID 
                                                            AND TEU.COMPANYCODE = TP.COMPANYCODE
                                                            AND TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE
                                                            AND TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE
                                                            AND TEU.DOCNUMBER = TP.DOCNUMBER), 1) AS STATUSH, 
                                     DECODE(TP.JML, 1, (SELECT DISTINCT TEU.MESSAGED
                                                          FROM $this->TEMP_UPLOAD_PO TEU
                                                         WHERE TEU.UUID = TP.UUID 
                                                           AND TEU.COMPANYCODE = TP.COMPANYCODE
                                                           AND TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE
                                                           AND TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE
                                                           AND TEU.DOCNUMBER = TP.DOCNUMBER), 'Please, Check Data Doc Date until Payterm must be the same, and there are no errors lined up !!') AS MESSAGEH
                                FROM (SELECT TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, COUNT(*) AS JML
                                        FROM (SELECT DISTINCT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, DOCDATE, VENDOR, DOCTYPE, DOCREF, TRANS_LOC,
                                                     BASELINEDATE, PAYTERM, MESSAGED
                                                FROM $this->TEMP_UPLOAD_PO
                                               WHERE UUID = ?) TUP
                                       GROUP BY TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER) TP 
                               WHERE TP.COMPANYCODE = UPO.COMPANYCODE
                                 AND TP.BUSINESSUNITCODE = UPO.BUSINESSUNITCODE
                                 AND TP.DEPARTMENTCODE = UPO.DEPARTMENTCODE
                                 AND TP.DOCNUMBER = UPO.DOCNUMBER
                                 AND TP.UUID = UPO.UUID)
 WHERE UPO.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID, $UUID]);

//                  Select Header PO and Get Detail PO
                    $SQL = "
SELECT DISTINCT TUP.COMPANY, TUP.COMPANYCODE, TUP.BUSINESSUNIT, TUP.BUSINESSUNITCODE, TUP.DEPARTMENT, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, TUP.DOCTYPE,
       TUP.STATUSH, TUP.MESSAGEH, TP.JML, TP.AMOUNT_INCLUDE_VAT, TP.AMOUNT_PPH
  FROM $this->TEMP_UPLOAD_PO TUP
 INNER JOIN (SELECT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, COUNT(*) AS JML, SUM(AMOUNT_INCLUDE_VAT) AS AMOUNT_INCLUDE_VAT, SUM(AMOUNT_PPH) AS AMOUNT_PPH
               FROM $this->TEMP_UPLOAD_PO 
              WHERE UUID = ?
              GROUP BY UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER) TP
         ON TP.UUID = TUP.UUID
        AND TP.COMPANYCODE = TUP.COMPANYCODE
        AND TP.BUSINESSUNITCODE = TUP.BUSINESSUNITCODE
        AND TP.DEPARTMENTCODE = TUP.DEPARTMENTCODE
        AND TP.DOCNUMBER = TUP.DOCNUMBER
 WHERE TUP.UUID = ?";
                    $data = $this->db->query($SQL, [$UUID, $UUID])->result();
                    $SQL = "SELECT * FROM TEMP_UPLOAD_PO 
                             WHERE UUID = ?
                               AND COMPANYCODE = ?
                               AND BUSINESSUNITCODE = ?
                               AND DEPARTMENTCODE = ?
                               AND DOCNUMBER = ?";
                    foreach ($data as $values) {
                        $DtParam = [$UUID, $values->COMPANYCODE, $values->BUSINESSUNITCODE, $values->DEPARTMENTCODE, $values->DOCNUMBER];
                        $values->datadetail = $this->db->query($SQL, $DtParam)->result();
                    }

                    $SQL = "DELETE FROM $this->TEMP_UPLOAD_PO WHERE UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $data
                ];
            } else {
                throw new Exception('Upload Failed !!');
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

    public function UploadPO5Hana($param)
    {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
                $file = $_FILES['uploads'];
                $inputFileName = $file['tmp_name'];
                $inputFileType = IOFactory::identify($inputFileName);
                $reader = IOFactory::createReader($inputFileType);
                $spreadsheet = $reader->load($inputFileName);
                //                $spreadsheet = IOFactory::load($file['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $UUID = $param['UUID'];
                $EXTSYSTEM = $param['EXTSYSTEM'];
                $USERNAME = $param['USERNAME'];
                $DEPARTMENT = $param['DEPARTMENT'];
                $idx = 1;
                $idx1 = 0;
                $DOCTYPE = '';
                $dt = [];
                foreach ($sheetData as $value) {
                    // echo "<pre>";
                    // var_dump($sheetData);exit;
                    if ($idx1 > 0) {
                        if (
                            $value['A'] == NULL && $value['B'] == NULL && $value['C'] == NULL && $value['D'] == NULL && $value['E'] == NULL && $value['F'] == NULL &&
                            $value['G'] == NULL && $value['H'] == NULL && $value['I'] == NULL && $value['J'] == NULL && $value['K'] == NULL && $value['L'] == NULL &&
                            $value['M'] == NULL && $value['N'] == NULL && $value['O'] == NULL && $value['P'] == NULL && $value['Q'] == NULL && $value['R'] == NULL &&
                            $value['S'] == NULL && $value['T'] == NULL && $value['U'] == NULL && $value['V'] == NULL && $value['W'] == NULL && $value['X'] == NULL &&
                            $value['Y'] == NULL && $value['Z'] == NULL && $value['AA'] == NULL && $value['AB'] == NULL && $value['AC'] == NULL && $value['AD'] == NULL &&
                            $value['AE'] == NULL && $value['AF'] == NULL && $value['AG'] == NULL && $value['AH'] == NULL && $value['AI'] == NULL && $value['AJ'] == NULL &&
                            $value['AK'] == NULL && $value['AL'] == NULL && $value['AM'] == NULL && $value['AN'] == NULL && $value['AO'] == NULL && $value['AP'] == NULL &&
                            $value['AQ'] == NULL && $value['AR'] == NULL && $value['AS'] == NULL && $value['AT'] == NULL && $value['AU'] == NULL
                        ) {
                        } else {
                            if (($value['D'] == NULL || $value['D'] == '') && ($value['AO'] != NULL && $value['AO'] != '' && $value['AP'] != NULL && $value['AP'] != '')) {
                                if ($DOCTYPE == '8' || $DOCTYPE == '9') {
                                    $dt["ID"] = $idx;
                                    $dt["MATERIALCODE"] = $value['AO'];
                                    array_push($data, $dt);
                                    $idx++;
                                }
                            } else {
                                if (($DOCTYPE == '8' || $DOCTYPE == '9') && $dt['ID'] == $idx) {
                                    array_push($data, $dt);
                                    $idx++;
                                }
                                $status = 0;

                                // $com = substr($value['I'], 0, 2);
                                // $qGetCompany = "SELECT c.ID ID
                                //         FROM COMPANY C 
                                //        INNER JOIN COMPANY_EXTSYS CE
                                //                ON CE.COMPANY = C.ID
                                //               AND CE.EXTSYSTEM = 'SAPHANA'
                                //        WHERE C.ISACTIVE = 1 
                                //          AND CE.EXTSYSCOMPANYCODE = '$com'";
                                // $getIdComp      = $this->db->query($qGetCompany)->row()->ID;

                                // if($getIdComp == NULL){
                                //         throw new Exception("Company $com not Found.");
                                // }

                                // $xlsBU        = $value['I'];
                                // $qGetIdBu     = "SELECT B.ID ID FROM BUSINESSUNIT B
                                //            INNER JOIN BUSINESSUNIT_EXTSYS BE
                                //                    ON BE.BUSINESSUNIT = B.ID
                                //                   AND BE.EXTSYSTEM = 'SAPHANA'
                                //            WHERE B.ISACTIVE = 'TRUE'
                                //              AND B.COMPANY = '$getIdComp'
                                //              AND BE.EXTSYSBUSINESSUNITCODE = 'TH01'";
                                // $getIdBu      = $this->db->query($qGetIdBu)->row()->ID;
                                // // $getIdBu      = $getIdBu->ID;
                                // // var_dump($this->db->last_query());exit;
                                // if($getIdBu == NULL){
                                //         throw new Exception("Business Unit $xlsBU not Found.");
                                // }

                                $dt = [
                                    'UUID' => $UUID,
                                    'ID' => $idx,
                                    'COMPANY' => '',
                                    'COMPANYCODE' => substr($value['I'], 0, 2),
                                    'BUSINESSUNIT' => '',
                                    'BUSINESSUNITCODE' => $value['I'],
                                    'DEPARTMENT' => '',
                                    'DEPARTMENTCODE' => $DEPARTMENT,
                                    'DOCTYPE' => strval($value['C']),
                                    'DOCDATE' => strval($value['H']),
                                    'DOCNUMBER' => strval($value['E']),
                                    'DOCREF' => '',
                                    'VENDORCODE' => trim(str_replace(",", "", strval($value['M']))),
                                    'TRANS_LOC' => '',
                                    'BASELINEDATE' => '', //$value['AE']
                                    'PAYTERM' => '', //$value['AL']
                                    'DUEDATE' => '',
                                    'MATERIALCODE' => strval($value['P']),
                                    'REMARKS' => strval($value['Q']),
                                    'AMOUNT_INCLUDE_VAT' => trim(str_replace(",", "", strval($value['AD']))),
                                    'AMOUNT_PPH' => 0,
                                    'STATUSH' => 0,
                                    'STATUSD' => $status,
                                    'MESSAGEH' => '',
                                    'MESSAGED' => '',
                                    'EXTSYSTEM' => $EXTSYSTEM,
                                    'CURRENCY' => $value['W'],
                                    'ISADENDUM' => '0',
                                    'RATE' => '1'
                                ];
                                $DOCTYPE = substr($dt['DOCTYPE'], 2, 1);
                                if ($DOCTYPE == '1' || $DOCTYPE == '2'|| $DOCTYPE == '9') {
                                    $dt['DOCTYPE'] = "PO";
                                } elseif ($DOCTYPE == '6'|| $DOCTYPE == '7') {
                                    $dt['DOCTYPE'] = "STO";
                                } elseif ($DOCTYPE == '8') {
                                    $dt['DOCTYPE'] = "SPO";
                                } else {
                                    $dt['DOCTYPE'] = "";
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Doc Type Not Found !!";
                                }

                                if ($DOCTYPE == '8' || $DOCTYPE == '9') {
                                    if ($dt['BASELINEDATE'] != NULL && $dt['BASELINEDATE'] != '') {
                                        $date = explode('.', $dt['BASELINEDATE']);
                                        $bln2 = substr("0" . $date[1], -2);
                                        $tgl2 = substr("0" . $date[0], -2);
                                        $thn2 = $date[2];
                                        $dt['BASELINEDATE'] = $bln2 . "/" . $tgl2 . "/" . $thn2;
                                    }
                                } else {
                                    $dt['BASELINEDATE'] = "";
                                }
                                if ($dt['PAYTERM'] != NULL && $dt['PAYTERM'] != '') {
                                    $dt['PAYTERM'] = substr($dt['PAYTERM'], 1, strlen($dt['PAYTERM']) - 2);
                                    if (!is_numeric($dt['PAYTERM'])) {
                                        $dt['PAYTERM'] = "";
                                    }
                                }
                                if ($dt['BASELINEDATE'] != '' && $dt['PAYTERM'] != '') {
                                    $dt['DUEDATE'] = date('m/d/Y', strtotime($dt['PAYTERM'] . " days", strtotime($thn2 . $bln2 . $tgl2)));
                                }

                                if ($dt['DOCDATE'] != NULL && $dt['DOCDATE'] != '') {
                                    $date = explode(".", $dt['DOCDATE']);
                                    $bln1 = substr("0" . $date[1], -2);
                                    $tgl1 = substr("0" . $date[0], -2);
                                    $thn1 = $date[2];
                                    $dt['DOCDATE'] = $bln1 . "/" . $tgl1 . "/" . $thn1;
                                }

                                if ($DOCTYPE != '8' && $DOCTYPE != '9') {
                                    array_push($data, $dt);
                                    $idx++;
                                }
                            }
                        }
                    }
                    $idx1++;
                }
                $result = $this->db->insert_batch($this->TEMP_UPLOAD_PO, $data);
                if ($result) {
                    $this->CheckValidationHana($EXTSYSTEM, $UUID, $USERNAME);
                    //                  Update Error Di Header PO
                    $SQL = "                             
UPDATE $this->TEMP_UPLOAD_PO UPO
    SET (STATUSH, MESSAGEH) = (SELECT DECODE(TP.JML, 1, (SELECT DISTINCT TEU.STATUSD
                                                           FROM $this->TEMP_UPLOAD_PO TEU
                                                          WHERE TEU.UUID = TP.UUID 
                                                            AND TEU.COMPANYCODE = TP.COMPANYCODE
                                                            AND TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE
                                                            AND TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE
                                                            AND TEU.DOCNUMBER = TP.DOCNUMBER), 1) AS STATUSH, 
                                     DECODE(TP.JML, 1, (SELECT DISTINCT TEU.MESSAGED
                                                          FROM $this->TEMP_UPLOAD_PO TEU
                                                         WHERE TEU.UUID = TP.UUID 
                                                           AND TEU.COMPANYCODE = TP.COMPANYCODE
                                                           AND TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE
                                                           AND TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE
                                                           AND TEU.DOCNUMBER = TP.DOCNUMBER), 'Please, Check Data Doc Date until Payterm must be the same, and there are no errors lined up !!') AS MESSAGEH
                                FROM (SELECT TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, COUNT(*) AS JML
                                        FROM (SELECT DISTINCT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, DOCDATE, VENDOR, DOCTYPE, DOCREF, TRANS_LOC,
                                                     BASELINEDATE, PAYTERM, MESSAGED
                                                FROM $this->TEMP_UPLOAD_PO
                                               WHERE UUID = ?) TUP
                                       GROUP BY TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER) TP 
                               WHERE TP.COMPANYCODE = UPO.COMPANYCODE
                                 AND TP.BUSINESSUNITCODE = UPO.BUSINESSUNITCODE
                                 AND TP.DEPARTMENTCODE = UPO.DEPARTMENTCODE
                                 AND TP.DOCNUMBER = UPO.DOCNUMBER
                                 AND TP.UUID = UPO.UUID)
 WHERE UPO.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID, $UUID]);

                    //                  Select Header PO and Get Detail PO
                    $SQL = "
SELECT DISTINCT TUP.COMPANY, TUP.COMPANYCODE, TUP.BUSINESSUNIT, TUP.BUSINESSUNITCODE, TUP.DEPARTMENT, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, TUP.DOCTYPE,
       TUP.STATUSH, TUP.MESSAGEH, TP.JML, TP.AMOUNT_INCLUDE_VAT, TP.AMOUNT_PPH
  FROM $this->TEMP_UPLOAD_PO TUP
 INNER JOIN (SELECT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, COUNT(*) AS JML, SUM(AMOUNT_INCLUDE_VAT) AS AMOUNT_INCLUDE_VAT, SUM(AMOUNT_PPH) AS AMOUNT_PPH
               FROM $this->TEMP_UPLOAD_PO 
              WHERE UUID = ?
              GROUP BY UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER) TP
         ON TP.UUID = TUP.UUID
        AND TP.COMPANYCODE = TUP.COMPANYCODE
        AND TP.BUSINESSUNITCODE = TUP.BUSINESSUNITCODE
        AND TP.DEPARTMENTCODE = TUP.DEPARTMENTCODE
        AND TP.DOCNUMBER = TUP.DOCNUMBER
 WHERE TUP.UUID = ?";
                    $data = $this->db->query($SQL, [$UUID, $UUID])->result();
                    $SQL = "SELECT * FROM TEMP_UPLOAD_PO 
                             WHERE UUID = ?
                               AND COMPANYCODE = ?
                               AND BUSINESSUNITCODE = ?
                               AND DEPARTMENTCODE = ?
                               AND DOCNUMBER = ?";
                    foreach ($data as $values) {
                        $DtParam = [$UUID, $values->COMPANYCODE, $values->BUSINESSUNITCODE, $values->DEPARTMENTCODE, $values->DOCNUMBER];
                        $values->datadetail = $this->db->query($SQL, $DtParam)->result();
                    }

                    $SQL = "DELETE FROM $this->TEMP_UPLOAD_PO WHERE UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $data
                ];
            } else {
                throw new Exception('Upload Failed !!');
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

    public function CheckValidationBaru($EXTSYSTEM, $UUID, $USERNAME,$DOCNUMBER) {
//        Update Field Company and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP
                   SET TUP.COMPANY = (SELECT c.ID
                                        FROM $this->COMPANY C 
                                       INNER JOIN $this->COMPANY_EXTSYS CE
                                               ON CE.COMPANY = C.ID
                                              AND CE.EXTSYSTEM = ?
                                       WHERE C.ISACTIVE = 1 
                                         AND CE.EXTSYSCOMPANYCODE = TUP.COMPANYCODE)
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Company Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.COMPANY IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Business Unit and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.BUSINESSUNIT = (SELECT B.ID
                                            FROM $this->BUSINESSUNIT B
                                           INNER JOIN $this->BUSINESSUNIT_EXTSYS BE
                                                   ON BE.BUSINESSUNIT = B.ID
                                                  AND BE.EXTSYSTEM = ?
                                           WHERE B.ISACTIVE = 'TRUE'
                                             AND B.COMPANY = TUP.COMPANY
                                             AND BE.EXTSYSBUSINESSUNITCODE = TUP.BUSINESSUNITCODE)
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Business Unit Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.BUSINESSUNIT IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Department and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.DEPARTMENT = (SELECT DEPARTMENT
                                           FROM $this->USER_DEPART
                                          WHERE FCCODE = ?
                                            AND DEPARTMENT = TUP.DEPARTMENTCODE)
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$USERNAME, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Department Not Granted !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.DEPARTMENT IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Vendor and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TP 
                   SET TP.VENDORCODE = (SELECT TUP.VENDORCODE
                                          FROM (SELECT TUP.COMPANYCODE, TUP.DOCNUMBER, MAX(TUP.VENDORCODE) AS VENDORCODE, COUNT(*) AS JML
                                                  FROM (SELECT DISTINCT TUP.COMPANYCODE, TUP.DOCNUMBER, TUP.VENDORCODE
                                                          FROM $this->TEMP_UPLOAD_PO TUP
                                                         WHERE TUP.UUID = ?
                                                           AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR')) TUP
                                                         GROUP BY TUP.COMPANYCODE, TUP.DOCNUMBER) TUP
                                                 WHERE TUP.COMPANYCODE = TP.COMPANYCODE
                                                   AND TUP.DOCNUMBER = TP.DOCNUMBER)
                 WHERE TP.STATUSD <> 1
                   AND (TP.DOCTYPE <> 'INV' AND TP.DOCTYPE <> 'INV_AR')
                   AND TP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.VENDOR = (SELECT ID
                                       FROM $this->SUPPLIER
                                      WHERE FCCODE = TUP.VENDORCODE
                                        AND ISACTIVE = 'TRUE')
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP
                      SET TUP.STATUSD = 1,
                      TUP.MESSAGED = 'Vendor Not Found !!!'
                      WHERE TUP.STATUSD <> 1
                      AND TUP.VENDOR IS NULL
                      AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Material and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.MATERIAL = (SELECT ID
                                         FROM $this->MATERIAL 
                                        WHERE FCCODE = TUP.MATERIALCODE
                                          AND EXTSYSTEM = ?
                                          AND ISACTIVE = 'TRUE')
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                  SET TUP.STATUSD = 1,
                      TUP.MESSAGED = 'Material Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.MATERIAL IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET (TUP.STATUSD, TUP.MESSAGED) = (SELECT DECODE(TP.JML, 0, 1, 0) AS STATUSD, DECODE(TP.JML, 0, 'Material Group Not Found !!!', '') AS MESSAGED
                                                        FROM (SELECT TUP.EXTSYSTEM, TUP.MATERIAL, COUNT(MGI.MATERIAL) AS JML
                                                                FROM $this->TEMP_UPLOAD_PO TUP
                                                                LEFT JOIN MATERIAL_GROUPITEM MGI
                                                                       ON MGI.MATERIAL = TUP.MATERIAL
                                                                      AND MGI.EXTSYSTEM = TUP.EXTSYSTEM
                                                               WHERE TUP.UUID = ?
                                                                 AND TUP.MATERIAL IS NOT NULL
                                                               GROUP BY TUP.EXTSYSTEM, TUP.MATERIAL) TP
                                                       WHERE TP.EXTSYSTEM = TUP.EXTSYSTEM
                                                         AND TP.MATERIAL = TUP.MATERIAL)
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.MATERIAL IS NOT NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID, $UUID]);

//        Check Doc Ref Ready For INV or INV_AR
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO UPO
                   SET (UPO.STATUSD, UPO.MESSAGED) = (SELECT DECODE(CT.JML, 0, 1, 0) AS STATUSD, DECODE(CT.JML, 0, 'Doc Ref Not Found !!', '') AS MESSAGED
                                                        FROM (SELECT COUNT(*) AS JML
                                                                FROM $this->CF_TRANSACTION CTR
                                                               WHERE CTR.DOCNUMBER = UPO.DOCREF
                                                                 AND CTR.COMPANY = UPO.COMPANY) CT)
                 WHERE UPO.STATUSD <> 1
                   AND (UPO.DOCTYPE = 'INV' OR UPO.DOCTYPE = 'INV_AR')
                   AND UPO.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Cek Docnumber Same
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'DOCNUMBER $DOCNUMBER Already Exists !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, TUP.DOCNUMBER) IN (SELECT CONCAT(CFT.COMPANY, CFT.DOCNUMBER)
                                                                FROM $this->CF_TRANSACTION CFT
                                                               WHERE ISACTIVE = 'TRUE'
                                                                 AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR'))
                   AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'DOCNUMBER $DOCNUMBER Already Exists !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, CONCAT(TUP.DOCNUMBER, TUP.DOCREF)) IN (SELECT CONCAT(CFT.COMPANY, CONCAT(CFT.DOCNUMBER, CFT.DOCREF))
                                                                                    FROM $this->CF_TRANSACTION CFT
                                                                                   WHERE ISACTIVE = 'TRUE'
                                                                                     AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR'))
                   AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        // $SQL = "SELECT DOCNUMBER FROM CF_TRANSACTION WHERE DOCNUMBER = '".$DOCNUMBER."'";
        // $result = $this->db->query($SQL)->row();
        // if($result != null){
        //     $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
        //            SET TUP.STATUSD = 1,
        //                TUP.MESSAGED = 'Doc Number Already Exists !!!'
        //          WHERE TUP.STATUSD <> 1 
        //            AND TUP.UUID = ?";
        //     $result = $this->db->query($SQL, [$UUID]);
        // }
        
    }

    public function CheckValidation($EXTSYSTEM, $UUID, $USERNAME) {
//        Update Field Company and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP
                   SET TUP.COMPANY = (SELECT c.ID
                                        FROM $this->COMPANY C 
                                       INNER JOIN $this->COMPANY_EXTSYS CE
                                               ON CE.COMPANY = C.ID
                                              AND CE.EXTSYSTEM = ?
                                       WHERE C.ISACTIVE = 1 
                                         AND CE.EXTSYSCOMPANYCODE = TUP.COMPANYCODE)
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Company Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.COMPANY IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Business Unit and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.BUSINESSUNIT = (SELECT B.ID
                                            FROM $this->BUSINESSUNIT B
                                           INNER JOIN $this->BUSINESSUNIT_EXTSYS BE
                                                   ON BE.BUSINESSUNIT = B.ID
                                                  AND BE.EXTSYSTEM = ?
                                           WHERE B.ISACTIVE = 'TRUE'
                                             AND B.COMPANY = TUP.COMPANY
                                             AND BE.EXTSYSBUSINESSUNITCODE = TUP.BUSINESSUNITCODE)
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Business Unit Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.BUSINESSUNIT IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Docnumber cant empty, check ur document!!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.DOCNUMBER IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Docnumber cant empty, check ur document!!!'
                 WHERE TUP.STATUSD <> 1 
                   AND (TUP.DOCNUMBER IS NULL OR TUP.DOCNUMBER = '')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Department and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.DEPARTMENT = (SELECT DEPARTMENT
                                           FROM $this->USER_DEPART
                                          WHERE FCCODE = ?
                                            AND DEPARTMENT = TUP.DEPARTMENTCODE)
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$USERNAME, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Department Not Granted !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.DEPARTMENT IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Vendor and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TP 
                   SET TP.VENDORCODE = (SELECT TUP.VENDORCODE
                                          FROM (SELECT TUP.COMPANYCODE, TUP.DOCNUMBER, MAX(TUP.VENDORCODE) AS VENDORCODE, COUNT(*) AS JML
                                                  FROM (SELECT DISTINCT TUP.COMPANYCODE, TUP.DOCNUMBER, TUP.VENDORCODE
                                                          FROM $this->TEMP_UPLOAD_PO TUP
                                                         WHERE TUP.UUID = ?
                                                           AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR')) TUP
                                                         GROUP BY TUP.COMPANYCODE, TUP.DOCNUMBER) TUP
                                                 WHERE TUP.COMPANYCODE = TP.COMPANYCODE
                                                   AND TUP.DOCNUMBER = TP.DOCNUMBER)
                 WHERE TP.STATUSD <> 1
                   AND (TP.DOCTYPE <> 'INV' AND TP.DOCTYPE <> 'INV_AR')
                   AND TP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.VENDOR = (SELECT ID
                                       FROM $this->SUPPLIER
                                      WHERE FCCODE = TUP.VENDORCODE
                                        AND ISACTIVE = 'TRUE')
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP
                      SET TUP.STATUSD = 1,
                      TUP.MESSAGED = 'Vendor Not Found !!!'
                      WHERE TUP.STATUSD <> 1
                      AND TUP.VENDOR IS NULL
                      AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Material and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.MATERIAL = (SELECT ID
                                         FROM $this->MATERIAL 
                                        WHERE FCCODE = TUP.MATERIALCODE
                                          AND EXTSYSTEM = ?
                                          AND ISACTIVE = 'TRUE')
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                  SET TUP.STATUSD = 1,
                      TUP.MESSAGED = 'Material Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.MATERIAL IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET (TUP.STATUSD, TUP.MESSAGED) = (SELECT DECODE(TP.JML, 0, 1, 0) AS STATUSD, DECODE(TP.JML, 0, 'Material Group Not Found !!!', '') AS MESSAGED
                                                        FROM (SELECT TUP.EXTSYSTEM, TUP.MATERIAL, COUNT(MGI.MATERIAL) AS JML
                                                                FROM $this->TEMP_UPLOAD_PO TUP
                                                                LEFT JOIN MATERIAL_GROUPITEM MGI
                                                                       ON MGI.MATERIAL = TUP.MATERIAL
                                                                      AND MGI.EXTSYSTEM = TUP.EXTSYSTEM
                                                               WHERE TUP.UUID = ?
                                                                 AND TUP.MATERIAL IS NOT NULL
                                                               GROUP BY TUP.EXTSYSTEM, TUP.MATERIAL) TP
                                                       WHERE TP.EXTSYSTEM = TUP.EXTSYSTEM
                                                         AND TP.MATERIAL = TUP.MATERIAL)
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.MATERIAL IS NOT NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID, $UUID]);

//        Check Doc Ref Ready For INV or INV_AR
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO UPO
                   SET (UPO.STATUSD, UPO.MESSAGED) = (SELECT DECODE(CT.JML, 0, 1, 0) AS STATUSD, DECODE(CT.JML, 0, 'Doc Ref Not Found !!', '') AS MESSAGED
                                                        FROM (SELECT COUNT(*) AS JML
                                                                FROM $this->CF_TRANSACTION CTR
                                                               WHERE CTR.DOCNUMBER = UPO.DOCREF
                                                                 AND CTR.COMPANY = UPO.COMPANY) CT)
                 WHERE UPO.STATUSD <> 1
                   AND (UPO.DOCTYPE = 'INV' OR UPO.DOCTYPE = 'INV_AR')
                   AND UPO.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Cek Docnumber Same
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Some Data Already Exists !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, TUP.DOCNUMBER) IN (
                   SELECT CONCAT(CFT.COMPANY, CFT.DOCNUMBER)
                            FROM $this->CF_TRANSACTION CFT
                                WHERE ISACTIVE = 'TRUE'
                                    AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR'))
                   AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        // var_dump($this->db->last_query());exit();
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Some Data Already Exists !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, CONCAT(TUP.DOCNUMBER, TUP.DOCREF)) IN (
                   SELECT CONCAT(CFT.COMPANY, CONCAT(CFT.DOCNUMBER, CFT.DOCREF))
                        FROM $this->CF_TRANSACTION CFT
                            WHERE ISACTIVE = 'TRUE'
                                AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR'))
                   AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
    }

    public function CheckValidationHana($EXTSYSTEM, $UUID, $USERNAME)
    {
        //        Update Field Company and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP
                   SET TUP.COMPANY = (SELECT c.ID
                                        FROM $this->COMPANY C 
                                       INNER JOIN $this->COMPANY_EXTSYS CE
                                               ON CE.COMPANY = C.ID
                                              AND CE.EXTSYSTEM = ?
                                       WHERE C.ISACTIVE = 1 
                                         AND CE.EXTSYSCOMPANYCODE = TUP.COMPANYCODE)
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Company Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.COMPANY IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        //        Update Field Business Unit and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.BUSINESSUNIT = (SELECT B.ID
                                            FROM $this->BUSINESSUNIT B
                                           INNER JOIN $this->BUSINESSUNIT_EXTSYS BE
                                                   ON BE.BUSINESSUNIT = B.ID
                                                  AND BE.EXTSYSTEM = ?
                                           WHERE B.ISACTIVE = 'TRUE'
                                             AND B.COMPANY = TUP.COMPANY
                                             AND BE.EXTSYSBUSINESSUNITCODE = TUP.BUSINESSUNITCODE)
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Business Unit Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.BUSINESSUNIT IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        //        Update Field Department and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.DEPARTMENT = (SELECT DEPARTMENT
                                           FROM $this->USER_DEPART
                                          WHERE FCCODE = ?
                                            AND DEPARTMENT = TUP.DEPARTMENTCODE)
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$USERNAME, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Department Not Granted !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.DEPARTMENT IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        //        Update Field Vendor and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TP 
                   SET TP.VENDORCODE = (SELECT TUP.VENDORCODE
                                          FROM (SELECT TUP.COMPANYCODE, TUP.DOCNUMBER, MAX(TUP.VENDORCODE) AS VENDORCODE, COUNT(*) AS JML
                                                  FROM (SELECT DISTINCT TUP.COMPANYCODE, TUP.DOCNUMBER, TUP.VENDORCODE
                                                          FROM $this->TEMP_UPLOAD_PO TUP
                                                         WHERE TUP.UUID = ?
                                                           AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR')) TUP
                                                         GROUP BY TUP.COMPANYCODE, TUP.DOCNUMBER) TUP
                                                 WHERE TUP.COMPANYCODE = TP.COMPANYCODE
                                                   AND TUP.DOCNUMBER = TP.DOCNUMBER)
                 WHERE TP.STATUSD <> 1
                   AND (TP.DOCTYPE <> 'INV' AND TP.DOCTYPE <> 'INV_AR')
                   AND TP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.VENDOR = (SELECT ID
                                       FROM $this->SUPPLIER
                                      WHERE FCCODE = TUP.VENDORCODE
                                        AND ISACTIVE = 'TRUE')
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP
                      SET TUP.STATUSD = 1,
                      TUP.MESSAGED = 'Vendor Not Found !!!'
                      WHERE TUP.STATUSD <> 1
                      AND TUP.VENDOR IS NULL
                      AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        //        Update Field Material and Pesan Error
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.MATERIAL = (SELECT ID
                                         FROM $this->MATERIAL 
                                        WHERE FCCODE = TUP.MATERIALCODE
                                          AND EXTSYSTEM = ?
                                          AND ISACTIVE = 'TRUE')
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                  SET TUP.STATUSD = 1,
                      TUP.MESSAGED = 'Material Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.MATERIAL IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET (TUP.STATUSD, TUP.MESSAGED) = (SELECT DECODE(TP.JML, 0, 1, 0) AS STATUSD, DECODE(TP.JML, 0, 'Material Group Not Found !!!', '') AS MESSAGED
                                                        FROM (SELECT TUP.EXTSYSTEM, TUP.MATERIAL, COUNT(MGI.MATERIAL) AS JML
                                                                FROM $this->TEMP_UPLOAD_PO TUP
                                                                LEFT JOIN MATERIAL_GROUPITEM MGI
                                                                       ON MGI.MATERIAL = TUP.MATERIAL
                                                                      AND MGI.EXTSYSTEM = TUP.EXTSYSTEM
                                                               WHERE TUP.UUID = ?
                                                                 AND TUP.MATERIAL IS NOT NULL
                                                               GROUP BY TUP.EXTSYSTEM, TUP.MATERIAL) TP
                                                       WHERE TP.EXTSYSTEM = TUP.EXTSYSTEM
                                                         AND TP.MATERIAL = TUP.MATERIAL)
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.MATERIAL IS NOT NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID, $UUID]);

        //        Check Doc Ref Ready For INV or INV_AR
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO UPO
                   SET (UPO.STATUSD, UPO.MESSAGED) = (SELECT DECODE(CT.JML, 0, 1, 0) AS STATUSD, DECODE(CT.JML, 0, 'Doc Ref Not Found !!', '') AS MESSAGED
                                                        FROM (SELECT COUNT(*) AS JML
                                                                FROM $this->CF_TRANSACTION CTR
                                                               WHERE CTR.DOCNUMBER = UPO.DOCREF
                                                                 AND CTR.COMPANY = UPO.COMPANY) CT)
                 WHERE UPO.STATUSD <> 1
                   AND (UPO.DOCTYPE = 'INV' OR UPO.DOCTYPE = 'INV_AR')
                   AND UPO.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        //        Cek Docnumber Same
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Some Data Already Exists !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, TUP.DOCNUMBER) IN (SELECT CONCAT(CFT.COMPANY, CFT.DOCNUMBER)
                                                                FROM $this->CF_TRANSACTION CFT
                                                               WHERE ISACTIVE = 'TRUE'
                                                                 AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR'))
                   AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Some Data Already Exists !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, CONCAT(TUP.DOCNUMBER, TUP.DOCREF)) IN (SELECT CONCAT(CFT.COMPANY, CONCAT(CFT.DOCNUMBER, CFT.DOCREF))
                                                                                    FROM $this->CF_TRANSACTION CFT
                                                                                   WHERE ISACTIVE = 'TRUE'
                                                                                     AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR'))
                   AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
    }

    public function UploadPaymentReceive($param,$location) {
        try
        {
            if($param['DOCTYPE'] == 'PAYMENT_OTHERS'){
                $this->db->where('FCENTRY',$param['USERNAME']."-U");
                $this->db->delete($this->TEMP_UPLOAD_PAYMENT_OTHERS);    
                $this->db->close();
            }else{
                $this->db->where('FCENTRY',$param['USERNAME']."-U");
                $this->db->delete($this->TEMP_UPLOAD_PAYMENT);    
                $this->db->close();
            }
            $this->db->trans_begin();
            $result = FALSE;
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
              $file = $_FILES['uploads'];
              $inputFileName = $file['tmp_name'];
              $inputFileType = IOFactory::identify($inputFileName);
              $reader = IOFactory::createReader($inputFileType);
              $spreadsheet = $reader->load($inputFileName);
              $sheet = $spreadsheet->getSheet(0);
              $highestRow = $sheet->getHighestRow();
              $highestColumn = $sheet->getHighestColumn();

              $USERNAME     = $param['USERNAME'];
              $CASHFLOWTYPE = $param['CASHFLOWTYPE'];
              $EXTSYSTEM    = $param['EXTSYSTEM'];
              $DOCTYPE      = $param['DOCTYPE'];

              if($DOCTYPE == "PAYMENT_OTHERS"){
                for( $row = 2; $row <= $highestRow; $row++ ){
                      $hadError = false;
                      $getBANK       = null;
                      
                      $ERROR_MESSAGE = array();
                      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);

                      $COMPANYCODE  = $rowData[0][0];
                      $VENDOR       = $rowData[0][1];
                      $NO_INVOICE  = $rowData[0][2];
                      $NO_PO       = $rowData[0][3];
                      $BANKACCOUNT = $rowData[0][4];
                      $PAID_DATE   = $rowData[0][5];
                      $CURRENCY    = $rowData[0][6];
                      $AMOUNT      = $rowData[0][7];
                      $RATE        = $rowData[0][8];
                      $VOUCHERNO   = $rowData[0][9];
                      $NOCEKGIRO   = $rowData[0][10];
                      $REMARKS     = $rowData[0][11];
                      $MATERIAL    = $rowData[0][12];

                      // var_dump($AMOUNT);exit;

                      $isNotZeroAll   = true;
                    if($COMPANYCODE == null && $VENDOR == null && $NO_PO == null && $NO_INVOICE == null && $BANKACCOUNT == null && $PAID_DATE == null && $AMOUNT == null && $RATE == null && $VOUCHERNO == null && $NOCEKGIRO == null && $REMARKS == null && $MATERIAL == null ){
                            $isNotZeroAll = false;
                    }  
                      
                    if($isNotZeroAll){
                        $this->db->where('FCCODE',"$VENDOR");
                        $getVendor = $this->db->get('SUPPLIER')->row();

                        if($getVendor == NULL){
                            $hadError        = true;
                            $ERROR_MESSAGE[] = "VENDOR CODE NOT FOUND"; 
                        }else{
                            $getVendor = $getVendor->ID;
                        }

                        if($RATE == '' || $RATE == null){
                            $hadError        = true;
                            $ERROR_MESSAGE[] = "RATE CANNOT NULL"; 
                        }

                        $where = array('EXTSYSCOMPANYCODE' => $COMPANYCODE, 'EXTSYSTEM' => $EXTSYSTEM);
                        $this->db->where($where);
                        $getCompanycode = $this->db->get('COMPANY_EXTSYS')->row();
                        if($getCompanycode == null ){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "COMPANY ON EXTSYSTEM NOT FOUND";
                        }
                        else{
                            $getCompanycode = $getCompanycode->COMPANY;
                        }

                        $this->db->select('*');
                        $this->db->where('FCCODE',"$BANKACCOUNT");
                        $getBankCode = $this->db->get('BANK')->row();
                          
                          // $getBANK = '';
                        if($getBankCode == NULL){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "BANK ACCOUNT NOT FOUND";
                        }
                        else{
                            $getBANK = $getBankCode->FCCODE;
                            // if($getBankCode->COMPANY != $getIdTrans->COMPANY){
                            //     $hadError = true;
                            //     $ERROR_MESSAGE[] = "BANK ACCOUNT NOT SAME";
                            //   }else{
                            //     $getBANK = $getBankCode->FCCODE;
                            //   }
                        }

                        // $UNIX_DATE = ($PAID_DATE - 25569) * 86400;
                        // $paidDate  = gmdate("m-d-Y", $UNIX_DATE);

                        $getDate = explode('/', $PAID_DATE);
                        $year    = $getDate[2];
                        $month   = $getDate[0];

                        $getSisa = $this->getSisa($year,$month,$getBANK);
                        // var_dump($getSisa);exit;
                        $CONVERSI = $AMOUNT * $RATE;
                        $amountConversi = (double)$CONVERSI;
                        //validasi
                        // if($getSisa->SISA == null){
                        //     $hadError = true;
                        //     $ERROR_MESSAGE[] = "Bank Account doesn't exist in the Bank Balance of Year and Month entered";
                        // }
                        // elseif($getSisa->SISA <= $amountConversi){
                        //     $hadError = true;
                        //     $ERROR_MESSAGE[] = "Saldo Bank Account tidak mencukupi atas payment yang dibayarkan.";
                        // }

                        $this->db->where('FCCODE',"$MATERIAL");
                        $getMaterial = $this->db->get('MATERIAL')->row();

                        if($getMaterial == NULL){
                            $hadError        = true;
                            $ERROR_MESSAGE[] = "MATERIAL CODE NOT FOUND"; 
                        }else{
                            $getMaterial = $getMaterial->ID;
                        }
                        
                        //check duplicate
                        // $UNIX_DATE = ($PAID_DATE - 25569) * 86400;
                        $date = strtotime($PAID_DATE);
                        $new_date = date('m/d/Y', $date);

                        $look = "select * from payment_other where voucherno = '$VOUCHERNO' and company = '$getCompanycode' and bankcode = '$getBANK' and to_char(daterelease,'mm/dd/yyyy') = '$new_date'";
                        
                        $getlook = $this->db->query($look);
                        if($getlook->num_rows() > 0){
                            $hadError        = true;
                            $ERROR_MESSAGE[] = "DUPLICATE VOUCHERNO WITH SAME MONTH AND BANKCODE"; 
                        }

                        $dataR = array(
                                  'COMPANY'  => $getCompanycode,
                                  'VENDOR'   => $getVendor,
                                  'MATERIAL' => $getMaterial,
                                  'BANKCODE' => $getBANK,
                                  'EXTSYS'   => $EXTSYSTEM,                                  
                                  'AMOUNT' => $AMOUNT * $RATE,
                                  'RATE' => $RATE,
                                  'VOUCHERNO' => $VOUCHERNO,
                                  'NOCEKGIRO' => $NOCEKGIRO,
                                  'REMARK' => $REMARKS,
                                  'ISACTIVE' => 1,
                                  "FCENTRY" => $USERNAME."-U",
                                  "FCEDIT" => $USERNAME."-U",
                                  "FCIP" => $location,
                                  "CASHFLOWTYPE" => $param['CASHFLOWTYPE'],
                                  "UUID" => $this->uuid->v4()
                              );
                       
                        $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                // ->set("DATERELEASE","SYSDATE",false)
                                ->set($dataR)->insert($this->TEMP_UPLOAD_PAYMENT_OTHERS);
                        $thisUUID = $dataR['UUID'];
                        $q = "UPDATE TEMP_UPLOAD_PAYMENT_OTHERS SET DATERELEASE = TO_DATE('".$PAID_DATE."','MM-DD-YYYY') WHERE UUID = '$thisUUID'";
                        $this->db->query($q);
                        
                        if($hadError){
                            $ermsg = implode(',', $ERROR_MESSAGE);
                            $updateErr = array('ERROR_MESSAGE' => $ermsg);
                            //var_dump($updateErr);exit();
                            $this->db->set($updateErr);
                            $this->db->where('UUID',$thisUUID);
                            $this->db->update('TEMP_UPLOAD_PAYMENT_OTHERS');
                        }
                    }//end not zero
                }//end for
                $qGet = "SELECT tup.*, supp.FCNAME as VENDORNAME, C.COMPANYNAME, M.FCNAME AS MATERIALNAME FROM TEMP_UPLOAD_PAYMENT_OTHERS tup LEFT JOIN SUPPLIER supp ON supp.ID = tup.VENDOR LEFT JOIN COMPANY C ON C.ID = tup.COMPANY LEFT JOIN MATERIAL M ON M.ID = tup.MATERIAL";
                $res   = $this->db->query($qGet)->result();
              }else{
                for( $row = 2; $row <= $highestRow; $row++ ){
                      $hadError = false;
                      $getFORECASTid = null;
                      $getBANK       = null;
                      $getCFTRANSID  = null;
                      
                      $ERROR_MESSAGE = array();
                      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);

                      $COMPANYCODE  = $rowData[0][0];
                      $NO_INVOICE  = $rowData[0][1];
                      $NO_PO       = $rowData[0][2];
                      $BANKACCOUNT = $rowData[0][3];
                      $PAID_DATE   = $rowData[0][4];
                      $CURRENCY    = $rowData[0][5];
                      $AMOUNT      = $rowData[0][6];
                      $RATE        = $rowData[0][7];
                      // $CONVERSI    = $rowData[0][8];
                      $VOUCHERNO   = $rowData[0][8];
                      $NOCEKGIRO   = $rowData[0][9];
                      $REMARKS     = $rowData[0][10];

                      $isNotZeroAll   = true;
                    if($COMPANYCODE == null && $NO_PO == null && $NO_INVOICE == null && $BANKACCOUNT == null && $PAID_DATE == null && $AMOUNT == null && $RATE == null && $VOUCHERNO == null && $NOCEKGIRO == null && $REMARKS == null ){
                            $isNotZeroAll = false;
                    }  
                      
                    if($isNotZeroAll){
                        $where = array('EXTSYSCOMPANYCODE' => $COMPANYCODE, 'EXTSYSTEM' => $EXTSYSTEM);
                        $this->db->where($where);
                        $getCompanycode = $this->db->get('COMPANY_EXTSYS')->row();
                        if($getCompanycode == null ){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "COMPANY ON EXTSYSTEM NOT FOUND";
                        }
                        else{
                            $getCompanycode = $getCompanycode->COMPANY;
                        }

                        // var_dump($getCompanycode);exit;

                        if($DOCTYPE === 'INV'){
                                // $this->db->where('EXTSYS', "$EXTSYSTEM");
                                $this->db->where('COMPANY', "$getCompanycode");
                                $this->db->where('DOCNUMBER', "$NO_INVOICE");
                                $this->db->where('DOCREF', "$NO_PO");
                                $getIdTrans = $this->db->get('CF_TRANSACTION')->row();
                                // var_dump($this->db->last_query());exit();
                                if($getIdTrans == null ){
                                    $hadError = true;
                                    $ERROR_MESSAGE[] = "$NO_INVOICE / $NO_PO CFTRANSACTION NOT FOUND";
                                    // throw new Exception("$NO_INVOICE / $NO_PO ON CFTRANSACTION NOT FOUND");
                                }else{
                                    $getCFTRANSID = $getIdTrans->ID;
                                }   
                        }
                        if($DOCTYPE === "INV_AP_SPC"){
                            // $this->db->where('EXTSYS', "$EXTSYSTEM");
                            $this->db->where('COMPANY', "$getCompanycode");
                            $this->db->where('DOCNUMBER', "$NO_INVOICE");
                            $getIdTrans = $this->db->get('CF_TRANSACTION')->row();
                            if($getIdTrans == null ){
                                $hadError = true;
                                $ERROR_MESSAGE[] = "$NO_INVOICE / $NO_PO CFTRANSACTION NOT FOUND";
                                // throw new Exception("$NO_INVOICE / $NO_PO ON CFTRANSACTION NOT FOUND");
                            }else{
                                $getCFTRANSID = $getIdTrans->ID;
                            }      
                        }

                        

                        $cekPayment = "SELECT CFTRANSID FROM PAYMENT WHERE CFTRANSID = '$getCFTRANSID'";
                        $cekPayment = $this->db->query($cekPayment);
                        if($cekPayment->num_rows() > 0){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "Data sudah dibayar.";
                        }

                        $this->db->select('*');
                        $this->db->where('FCCODE',"$BANKACCOUNT");
                        $getBankCode = $this->db->get('BANK')->row();
                          
                          // $getBANK = '';
                        if($getBankCode == NULL){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "BANK ACCOUNT NOT FOUND";
                        }
                        else{
                            $getBANK = $getBankCode->FCCODE;
                            // if($getBankCode->COMPANY != $getIdTrans->COMPANY){
                            //     $hadError = true;
                            //     $ERROR_MESSAGE[] = "BANK ACCOUNT NOT SAME";
                            //   }else{
                            //     $getBANK = $getBankCode->FCCODE;
                            //   }
                        }

                        $this->db->select('ID');
                        $this->db->where('CFTRANSID',"$getCFTRANSID");
                        $getFORECAST = $this->db->get('FORECAST_FIX')->row();

                        if($getFORECAST == null){
                            $getFORECASTid = NULL;
                        }else{
                            $getFORECASTid = $getFORECAST->ID;
                        }

                        // $UNIX_DATE = ($PAID_DATE - 25569) * 86400;
                        // $paidDate  = gmdate("m-d-Y", $UNIX_DATE);

                        $getDate = explode('/', $PAID_DATE);
                        $year    = $getDate[2];
                        $month   = $getDate[0];

                        $getSisa = $this->getSisa($year,$month,$getBANK);
                        // var_dump($year.$month.$getBANK);exit;
                        $CONVERSI = $AMOUNT * $RATE;
                        $amountConversi = (double)$CONVERSI;
                        //all validasi
                        // if($getSisa->SISA == NULL){
                        //     $hadError = true;
                        //     $ERROR_MESSAGE[] = "Bank Account doesn't exist in the Bank Balance of Year and Month entered";
                        // }
                        // elseif($getSisa->SISA <= $amountConversi){
                        //     $hadError = true;
                        //     $ERROR_MESSAGE[] = "Saldo Bank Account tidak mencukupi atas payment yang dibayarkan.";
                        // }

                        $date = strtotime($PAID_DATE);
                        $new_date = date('m/d/Y', $date);

                        $look = "select * from payment where voucherno = '$VOUCHERNO' and bankcode = '$getBANK' and to_char(daterelease,'mm/dd/yyyy') = '$new_date'";
                        
                        $getlook = $this->db->query($look);
                        if($getlook->num_rows() > 0){
                            $hadError        = true;
                            $ERROR_MESSAGE[] = "DUPLICATE VOUCHERNO WITH SAME MONTH AND BANKCODE"; 
                        }


                        $dataR = array(
                                  'FORECASTID'   => $getFORECASTid,
                                  'BANKCODE' => $getBANK,
                                  // 'CURRENCY' => $CURRENCY,
                                  'AMOUNT' => $AMOUNT,
                                  'RATE' => $RATE,
                                  'AMOUNTBANK' => $CONVERSI,
                                  'VOUCHERNO' => $VOUCHERNO,
                                  'NOCEKGIRO' => $NOCEKGIRO,
                                  'REMARK' => $REMARKS,
                                  'ISACTIVE' => 1,
                                  "FCENTRY" => $USERNAME."-U",
                                  "FCEDIT" => $USERNAME."-U",
                                  "FCIP" => $location,
                                  "CASHFLOWTYPE" => $param['CASHFLOWTYPE'],
                                  "CFTRANSID" => $getCFTRANSID,
                                  "UUID" => $this->uuid->v4()
                              );
                        
                        // var_dump($paidDate);exit();
                        $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set("DATERELEASE","SYSDATE",false)
                                ->set($dataR)->insert($this->TEMP_UPLOAD_PAYMENT);
                        $thisUUID = $dataR['UUID'];
                        $q = "UPDATE TEMP_UPLOAD_PAYMENT SET DATERELEASE = TO_DATE('".$PAID_DATE."','MM-DD-YYYY') WHERE UUID = '$thisUUID'";
                        $this->db->query($q);
                        
                        if($hadError){
                            $ermsg = implode(',', $ERROR_MESSAGE);
                            $updateErr = array('ERROR_MESSAGE' => $ermsg);
                            //var_dump($updateErr);exit();
                            $this->db->set($updateErr);
                            $this->db->where('UUID',$thisUUID);
                            $this->db->update('TEMP_UPLOAD_PAYMENT');
                        }
                    }//end not zero
                }//end for
                $qGet = "SELECT tup.*, cf.VENDOR, cf.DOCREF, cf.DOCNUMBER, supp.FCNAME as VENDORNAME, C.COMPANYNAME, M.FCNAME AS MATERIALNAME FROM TEMP_UPLOAD_PAYMENT tup LEFT JOIN CF_TRANSACTION cf ON cf.ID = tup.CFTRANSID LEFT JOIN CF_TRANSACTION_DET CFTD ON CFTD.ID = CF.ID LEFT JOIN SUPPLIER supp ON supp.ID = cf.VENDOR LEFT JOIN COMPANY C ON C.ID = CF.COMPANY LEFT JOIN MATERIAL M ON M.ID = CFTD.MATERIAL";
                $res   = $this->db->query($qGet)->result();
              }
                // $getTb = $this->db->get($this->TEMP_UPLOAD_PAYMENT)->result();
            }//end else
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $res
                ];
            }
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
      $this->db->close();
      return $return;
    }

    public function saveUpPaymentReceive($param,$location){
        try {
            $this->db->trans_begin();
            $USERNAME     = $param['USERNAME'];
            date_default_timezone_set("Asia/Jakarta");
            $DOCTYPE      = $param['DOCTYPE'];

            if($DOCTYPE == "PAYMENT_OTHERS"){
                $getDataTemp = $this->db->get($this->TEMP_UPLOAD_PAYMENT_OTHERS)->result();    
                if($getDataTemp){
                    foreach($getDataTemp as $r){

                        $TEMPPAYMENTID           = $r->PAYMENTID;
                        $DATERELEASE             = $r->DATERELEASE;
                        $BANKCODE                = $r->BANKCODE;
                        $VOUCHERNO               = $r->VOUCHERNO;
                        $NOCEKGIRO               = $r->NOCEKGIRO;
                        $AMOUNT                  = $r->AMOUNT;
                        $ISACTIVE                = $r->ISACTIVE;
                        $CASHFLOWTYPE            = $r->CASHFLOWTYPE;
                        $REMARK                  = $r->REMARK;
                        $COMPANY                 = $r->COMPANY;
                        $VENDOR                  = $r->VENDOR;
                        $MATERIAL                = $r->MATERIAL;
                        $EXTSYS                  = $r->EXTSYS;
                        
                        $data = array(
                            'COMPANY'    => $COMPANY,
                            'VENDOR'     => $VENDOR,
                            'MATERIAL'   => $MATERIAL,
                            'EXTSYS'     => $EXTSYS,
                            'DATERELEASE'=> $DATERELEASE,
                            'BANKCODE'   => $BANKCODE,
                            'VOUCHERNO'  => $VOUCHERNO,
                            'NOCEKGIRO'  => $NOCEKGIRO,
                            'AMOUNT'     => $AMOUNT,
                            'ISACTIVE'   => $ISACTIVE,
                            'FCENTRY'    => $USERNAME."-U",
                            'FCEDIT'     => $USERNAME."-U",
                            'FCIP'       => $location,
                            'CASHFLOWTYPE' => $CASHFLOWTYPE,
                            'REMARKS'     => $REMARK
                        );

                        $getDate = explode('/', date('d/m/Y',strtotime($DATERELEASE)));
                        $year    = $getDate[2];
                        $month   = $getDate[1];
                        $getSisa = $this->getSisa($year,$month,$BANKCODE);
                        // var_dump($year);exit;
                        // all validasi
                        // if($getSisa->SISA == null){
                        //     throw new Exception("Bank Account doesn't exist in the Bank Balance of Year and Month entered");
                        // }
                        // elseif($getSisa->SISA <= $AMOUNT){
                        //     throw new Exception("Saldo Bank Account tidak mencukupi atas payment yang dibayarkan.");
                        // }

                        $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set($data)->insert($this->PAYMENT_OTHER);
                        //delete temp data after save
                        $this->db->where('PAYMENTID',$TEMPPAYMENTID);
                        $this->db->delete($this->TEMP_UPLOAD_PAYMENT_OTHERS);
                    }
                }
                else{
                    throw new Exception('Data Save Failed !!');
                }
            }else{
                $getDataTemp = $this->db->get($this->TEMP_UPLOAD_PAYMENT)->result();    
                if($getDataTemp){
                    foreach($getDataTemp as $r){

                        $TEMPPAYMENTID           = $r->PAYMENTID;
                        $FORECASTID              = $r->FORECASTID;
                        $DATERELEASE             = $r->DATERELEASE;
                        $BANKCODE                = $r->BANKCODE;
                        $VOUCHERNO               = $r->VOUCHERNO;
                        $NOCEKGIRO               = $r->NOCEKGIRO;
                        $AMOUNT                  = $r->AMOUNT;
                        $ISACTIVE                = $r->ISACTIVE;
                        $CFTRANSID               = $r->CFTRANSID;
                        $CASHFLOWTYPE            = $r->CASHFLOWTYPE;
                        $REMARK                  = $r->REMARK;
                        $RATE                    = $r->RATE;
                        $AMOUNTBANK              = $r->AMOUNTBANK;
                        
                        $data = array(
                            'FORECASTID' => $FORECASTID,
                            'DATERELEASE'=> $DATERELEASE,
                            'BANKCODE'   => $BANKCODE,
                            'VOUCHERNO'  => $VOUCHERNO,
                            'NOCEKGIRO'  => $NOCEKGIRO,
                            'AMOUNT'     => $AMOUNT,
                            'ISACTIVE'   => $ISACTIVE,
                            'FCENTRY'    => $USERNAME."-U",
                            'FCEDIT'     => $USERNAME."-U",
                            'FCIP'       => $location,
                            'CFTRANSID'  => $CFTRANSID,
                            'CASHFLOWTYPE' => $CASHFLOWTYPE,
                            'REMARK'     => $REMARK,
                            'RATE'       => $RATE,
                            'AMOUNTBANK' => $AMOUNTBANK
                        );

                        $getDate = explode('/', date('d/m/Y',strtotime($DATERELEASE)));
                        $year    = $getDate[2];
                        $month   = $getDate[1];
                        $getSisa = $this->getSisa($year,$month,$BANKCODE);
                        // var_dump($getSisa);exit;
                        // all validasi
                        // if($getSisa->SISA == null){
                        //     throw new Exception("Bank Account doesn't exist in the Bank Balance of Year and Month entered");
                        // }
                        // elseif($getSisa->SISA <= $AMOUNTBANK){
                        //     throw new Exception("Saldo Bank Account tidak mencukupi atas payment yang dibayarkan.");
                        // }

                        $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set($data)->insert($this->PAYMENT);
                        //delete temp data after save
                        $this->db->where('PAYMENTID',$TEMPPAYMENTID);
                        $this->db->delete($this->TEMP_UPLOAD_PAYMENT);
                    }
                }
                else{
                    throw new Exception('Data Save Failed !!');
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
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function UploadPaymentInterco($param,$location) {
        try
        {
            $this->db->where('FCENTRY',$param['USERNAME']."-U");
            $this->db->delete($this->TEMP_UPLOAD_INTERCOLOANS);    
            $this->db->close();
            $this->db->trans_begin();
            
            $result = FALSE;
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
              $file = $_FILES['uploads'];
              $inputFileName = $file['tmp_name'];
              $inputFileType = IOFactory::identify($inputFileName);
              $reader = IOFactory::createReader($inputFileType);
              $spreadsheet = $reader->load($inputFileName);
              $sheet = $spreadsheet->getSheet(0);
              $highestRow = $sheet->getHighestRow();
              $highestColumn = $sheet->getHighestColumn();

              $USERNAME     = $param['USERNAME'];
              $DOCTYPE      = $param['DOCTYPE'];

                for( $row = 2; $row <= $highestRow; $row++ ){
                      $hadError = false;
                      $getBANK       = null;
                      
                      $ERROR_MESSAGE = array();
                      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);

                      $COMPANYSOURCE    = $rowData[0][0];
                      $BANKSOURCE       = $rowData[0][1];
                      $COMPANYTARGET    = $rowData[0][2];
                      $BANKTARGET       = $rowData[0][3];
                      $PAID_DATE        = $rowData[0][4];
                      $CURRENCYSOURCE   = $rowData[0][5];
                      $SOURCEAMOUNT     = $rowData[0][6];
                      $CURRENCYTARGET   = $rowData[0][7];
                      // $AMOUNT           = $rowData[0][8];
                      $RATE             = $rowData[0][8];
                      $VOUCHERNO        = $rowData[0][9];
                      $NOCEKGIRO        = $rowData[0][10];
                      $REMARKS          = $rowData[0][11];

                      $isNotZeroAll   = true;
                    if($COMPANYSOURCE == null && $BANKSOURCE == null && $COMPANYTARGET == null && $BANKTARGET == null && $PAID_DATE == null && $CURRENCYSOURCE == null && $SOURCEAMOUNT == null && $CURRENCYTARGET == null && $RATE == null && $NOCEKGIRO == null && $REMARKS == null && $VOUCHERNO == null ){
                            $isNotZeroAll = false;
                    }

                              
                    if($isNotZeroAll){

                        $where = array('EXTSYSCOMPANYCODE' => $COMPANYSOURCE);
                        $this->db->where($where);
                        $getCompanySource = $this->db->get('COMPANY_EXTSYS')->row();
                        if($getCompanySource == null ){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "COMPANY SOURCE NOT FOUND";
                        }
                        else{
                            $getCompanySource = $getCompanySource->COMPANY;
                        }

                        $where = array('EXTSYSCOMPANYCODE' => $COMPANYTARGET);
                        $this->db->where($where);
                        $getCompanyTarget = $this->db->get('COMPANY_EXTSYS')->row();
                        if($getCompanyTarget == null ){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "COMPANY TARGET NOT FOUND";
                        }
                        else{
                            $getCompanyTarget = $getCompanyTarget->COMPANY;
                        }

                        $this->db->select('*');
                        $this->db->where('FCCODE',"$BANKSOURCE");
                        $getBankSource = $this->db->get('BANK')->row();
                          
                        if($getBankSource == NULL){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "BANK SOURCE NOT FOUND";
                        }
                        else{
                            $getBankSource = $getBankSource->FCCODE;
                        }

                        $this->db->select('*');
                        $this->db->where('FCCODE',"$BANKTARGET");
                        $getBankTarget = $this->db->get('BANK')->row();
                          
                        if($getBankTarget == NULL){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "BANK TARGET NOT FOUND";
                        }
                        else{
                            $getBankTarget = $getBankTarget->FCCODE;
                        }

                        // $cekYear = Date('Y');
                        // $LIKE = "'%IL/".$getCompanySource->COMPANYCODE."%'";
                        // $q  = "SELECT CASE
                        //       WHEN (NUMMAX <= 10000 AND NUMMAX >= 1000)
                        //       THEN
                        //          '0' || TO_CHAR (NUMMAX)
                        //       WHEN (NUMMAX <= 1000 AND NUMMAX >= 100)
                        //       THEN
                        //          '00' || TO_CHAR (NUMMAX)
                        //       WHEN (NUMMAX <= 100 AND NUMMAX >= 10)
                        //       THEN
                        //          '000' || TO_CHAR (NUMMAX)
                        //       WHEN (NUMMAX <= 10 AND NUMMAX >= 0)
                        //       THEN
                        //          '0000' || TO_CHAR (NUMMAX)
                        //       ELSE
                        //          TO_CHAR (NUMMAX) END AS noF ";
                        // $q  .= " FROM (SELECT TO_NUMBER (maxno) + 1 NUMMAX FROM (SELECT NVL (MAX (SUBSTR (VOUCHERNO, -5)), 0) maxno FROM INTERCOLOANS where voucherno like $LIKE AND TO_CHAR(LASTUPDATE,'YYYY') = '$cekYear' ))";
                        // $genNumber  = $this->db->query($q)->row()->NOF;

                        // // var_dump($this->db->last_query());exit;

                        // if($VOUCHERNO == null || ''){
                        //     $genVoucher = 'IL/'.$getCompanySource->COMPANYCODE.'/'.Date('Y').'/'.$genNumber;      
                        // }else{
                        //     $genVoucher = $VOUCHERNO;
                        // }

                        // $date = strtotime($PAID_DATE);
                        // $new_date = date('m/d/Y', $date);

                        // $look = "select * from INTERCOLOANS where voucherno = '$VOUCHERNO' and COMPANYSOURCE = '$getCompanySource' and BANKSOURCE = '$getBankSource' and to_char(daterelease,'mm/dd/yyyy') = '$new_date'";
                        
                        // $getlook = $this->db->query($look);
                        // if($getlook->num_rows() > 0){
                        //     $hadError        = true;
                        //     $ERROR_MESSAGE[] = "DUPLICATE VOUCHERNO WITH SAME MONTH"; 
                        // }
                        
                        
                        $dataR = array(
                                  'COMPANYSOURCE'  => $getCompanySource,
                                  'BANKSOURCE'   => $getBankSource,
                                  'COMPANYTARGET' => $getCompanyTarget,
                                  'BANKTARGET' => $getBankTarget,
                                  'SOURCEAMOUNT' => $SOURCEAMOUNT,
                                  'RATE' => $RATE,
                                  'AMOUNT' => $SOURCEAMOUNT * $RATE,
                                  'VOUCHERNO' => $VOUCHERNO,
                                  'NOCEKGIRO' => $NOCEKGIRO,
                                  'REMARKS' => $REMARKS,
                                  'ISACTIVE' => 1,
                                  "FCENTRY" => $USERNAME."-U",
                                  "FCEDIT" => $USERNAME."-U",
                                  "FCIP" => $location,
                                  "UUID" => $this->uuid->v4()
                              );

                        $getDate = explode('/', $PAID_DATE);
                        $year    = $getDate[2];
                        $month   = $getDate[0];

                        $getSisa = $this->getSisa($year,$month,$getBankSource);
                        // var_dump($q2);exit;
                        $CONVERSI = $SOURCEAMOUNT * $RATE;
                        $amountConversi = (double)$CONVERSI;
                        // all validasi
                        // if($getSisa == null){
                        //     $hadError = true;
                        //     $ERROR_MESSAGE[] = "Bank Account doesn't exist in the Bank Balance of Year and Month entered";
                        // }
                        // elseif($getSisa->SISA <= $amountConversi){
                        //     $hadError = true;
                        //     $ERROR_MESSAGE[] = "Saldo Bank Account tidak mencukupi atas payment yang dibayarkan.";
                        // }
                       
                        $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set("DATERELEASE","SYSDATE",false)
                                ->set($dataR)->insert($this->TEMP_UPLOAD_INTERCOLOANS);
                        $thisUUID = $dataR['UUID'];
                        $q = "UPDATE TEMP_UPLOAD_INTERCOLOANS SET DATERELEASE = TO_DATE('".$PAID_DATE."','MM-DD-YYYY') WHERE UUID = '$thisUUID'";
                        $this->db->query($q);
                        
                        if($hadError){
                            $ermsg = implode(',', $ERROR_MESSAGE);
                            $updateErr = array('ERROR_MESSAGE' => $ermsg);
                            //var_dump($updateErr);exit();
                            $this->db->set($updateErr);
                            $this->db->where('UUID',$thisUUID);
                            $this->db->update('TEMP_UPLOAD_INTERCOLOANS');
                        }
                    }//end not zero
                }//end for
                $qGet = "SELECT tup.*,
                       (     SELECT EXTSYSCOMPANYCODE
                               FROM COMPANY_EXTSYS
                              WHERE COMPANY = tup.COMPANYSOURCE
                        FETCH FIRST 1 ROWS ONLY)
                          AS COMPANYSOURCENAME,
                       (     SELECT EXTSYSCOMPANYCODE
                               FROM COMPANY_EXTSYS
                              WHERE COMPANY = tup.COMPANYTARGET
                        FETCH FIRST 1 ROWS ONLY)
                          AS COMPANYTARGETNAME FROM TEMP_UPLOAD_INTERCOLOANS tup ";
                $res   = $this->db->query($qGet)->result();
              
                // $getTb = $this->db->get($this->TEMP_UPLOAD_PAYMENT)->result();
            }//end else
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $res
                ];
            }
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
      $this->db->close();
      return $return;
    }

    public function saveUpPaymentInterco($param,$location){
        try {
            $this->db->trans_begin();
            $USERNAME     = $param['USERNAME'];
            date_default_timezone_set("Asia/Jakarta");
            $DOCTYPE      = $param['DOCTYPE'];

            $getDataTemp = $this->db->get($this->TEMP_UPLOAD_INTERCOLOANS)->result();    
            if($getDataTemp){
                foreach($getDataTemp as $r){

                    $TEMPINTERCOID           = $r->INTERCOID;
                    $DATERELEASE             = $r->DATERELEASE;
                    $COMPANYSOURCE           = $r->COMPANYSOURCE;
                    $BANKSOURCE              = $r->BANKSOURCE;
                    $COMPANYTARGET           = $r->COMPANYTARGET;
                    $BANKTARGET              = $r->BANKTARGET;
                    $VOUCHERNO               = $r->VOUCHERNO;
                    $NOCEKGIRO               = $r->NOCEKGIRO;
                    $SOURCEAMOUNT            = $r->SOURCEAMOUNT;
                    $ISACTIVE                = $r->ISACTIVE;
                    $RATE                    = $r->RATE;
                    $AMOUNT                  = $r->AMOUNT;
                    $REMARKS                 = $r->REMARKS;

                    $getCompCode = $this->db->query("SELECT COMPANYCODE FROM COMPANY WHERE ID = '$COMPANYSOURCE'");
                    $getCompCode = $getCompCode->row()->COMPANYCODE;
                    // var_dump($getCompCode);exit;
                    $cekYear = Date('Y');
                    $LIKE = "'%IL/".$getCompCode."%'";
                    $q  = "SELECT CASE
                          WHEN (NUMMAX <= 10000 AND NUMMAX >= 1000)
                          THEN
                             '0' || TO_CHAR (NUMMAX)
                          WHEN (NUMMAX <= 1000 AND NUMMAX >= 100)
                          THEN
                             '00' || TO_CHAR (NUMMAX)
                          WHEN (NUMMAX <= 100 AND NUMMAX >= 10)
                          THEN
                             '000' || TO_CHAR (NUMMAX)
                          WHEN (NUMMAX <= 10 AND NUMMAX >= 0)
                          THEN
                             '0000' || TO_CHAR (NUMMAX)
                          ELSE
                             TO_CHAR (NUMMAX) END AS noF ";
                    $q  .= " FROM (SELECT TO_NUMBER (maxno) + 1 NUMMAX FROM (SELECT NVL (MAX (SUBSTR (VOUCHERNO, -5)), 0) maxno FROM INTERCOLOANS where voucherno like $LIKE AND TO_CHAR(LASTUPDATE,'YYYY') = '$cekYear' ))";
                    $genNumber  = $this->db->query($q)->row()->NOF;

                    // var_dump($this->db->last_query());exit;

                    if($VOUCHERNO == null || $VOUCHERNO == ''){
                        $genVoucher = 'IL/'.$getCompCode.'/'.Date('Y').'/'.$genNumber;      
                    }else{
                        $genVoucher = $VOUCHERNO;
                    }

                    $dates = strtotime($DATERELEASE);
                    $new_date = date('m/d/Y', $dates);

                    $look = "select * from INTERCOLOANS where voucherno = '$VOUCHERNO' and COMPANYSOURCE = '$COMPANYSOURCE' and BANKSOURCE = '$BANKSOURCE' and to_char(daterelease,'mm/dd/yyyy') = '$new_date'";
                    
                    $getlook = $this->db->query($look);
                    if($getlook->num_rows() > 0){
                        throw new Exception('Duplicate Voucher no $VOUCHERNO , Please Contact Your Administrator');
                    }
                    
                    $data = array(
                        'COMPANYSOURCE'    => $COMPANYSOURCE,
                        'BANKSOURCE'     => $BANKSOURCE,
                        'COMPANYTARGET'   => $COMPANYTARGET,
                        'BANKTARGET'     => $BANKTARGET,
                        'DATERELEASE'=> $DATERELEASE,
                        'VOUCHERNO'  => $genVoucher,
                        'NOCEKGIRO'  => $NOCEKGIRO,
                        'SOURCEAMOUNT'     => $SOURCEAMOUNT,
                        'RATE'       => $RATE,
                        'AMOUNT'     => $AMOUNT,
                        'ISACTIVE'   => $ISACTIVE,
                        'FCENTRY'    => $USERNAME."-U",
                        'FCEDIT'     => $USERNAME."-U",
                        'FCIP'       => $location,
                        'REMARKS'     => $REMARKS
                    );

                    $getDate = explode('/', date('d/m/Y',strtotime($DATERELEASE)));
                    $year    = $getDate[2];
                    $month   = $getDate[1];
                    $getSisa = $this->getSisa($year,$month,$BANKSOURCE);
                    // var_dump($getSisa);exit;
                    // all validasi
                    // if($getSisa->SISA == null){
                    //     throw new Exception("Bank Account doesn't exist in the Bank Balance of Year and Month entered");
                    // }
                    // elseif($getSisa->SISA <= $AMOUNT){
                    //     throw new Exception("Saldo Bank Account tidak mencukupi atas payment yang dibayarkan.");
                    // }
                        
                    $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                            ->set($data)->insert('INTERCOLOANS');
                    //delete temp data after save
                    $this->db->where('INTERCOID',$TEMPINTERCOID);
                    $this->db->delete($this->TEMP_UPLOAD_INTERCOLOANS);
                }
            }
            else{
                throw new Exception('Data Save Failed !!');
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
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function getSisa($year,$month,$bankcode){
        if($month < 10){
            $month = "0".$month;
        }
        // $month2 = $month.'01';
        $q = " SELECT tb_monthly.bankcode,
               tb_monthly.period_year,
               tb_monthly.period_month,
               tb_monthly.opening_balance_monthly AS opening_balance_monthly,
               NVL (pv3.debet, 0) AS debet,
               NVL (pv3.credit, 0) AS credit,
               (  tb_monthly.opening_balance_monthly
                + NVL (pv3.debet, 0)
                - NVL (pv3.credit, 0))
                  AS SISA
                  FROM (  SELECT bankcode,
                                 year,
                                 month,
                                 SUM (debet) AS debet,
                                 SUM (credit) AS credit
                            FROM (  SELECT bankcode,
                                           TO_NUMBER (TO_CHAR (daterelease, 'YYYY')) AS year,
                                           TO_NUMBER (TO_CHAR (daterelease, 'MM')) AS month,
                                           SUM (debet) AS debet,
                                           SUM (credit) AS credit
                                      FROM (SELECT pv.bankcode,
                                                   pv.daterelease,
                                                   pv.cashflowtype,
                                                   pv.debet,
                                                   pv.credit
                                              FROM (  SELECT payment.bankcode,
                                                             payment.daterelease,
                                                             payment.cashflowtype,
                                                             MAX (payment.remark) AS remark,
                                                             NVL (SUM (payment.amountbank), 0)
                                                                AS debet,
                                                             0 AS credit
                                                        FROM payment
                                                       WHERE payment.cashflowtype = '0'
                                                    GROUP BY payment.bankcode,
                                                             payment.daterelease,
                                                             payment.cashflowtype
                                                    UNION ALL
                                                      SELECT payment.bankcode,
                                                             payment.daterelease,
                                                             payment.cashflowtype,
                                                             MAX (payment.remark) AS remark,
                                                             0 AS debet,
                                                             NVL (SUM (payment.amountbank), 0)
                                                                AS credit
                                                        FROM payment
                                                       WHERE payment.cashflowtype = '1'
                                                    GROUP BY payment.bankcode,
                                                             payment.daterelease,
                                                             payment.cashflowtype) pv
                                            UNION ALL
                                              SELECT payment_other.bankcode,
                                                     payment_other.daterelease,
                                                     payment_other.cashflowtype,
                                                     NVL (SUM (payment_other.amount), 0) AS debet,
                                                     0 AS credit
                                                FROM payment_other
                                               WHERE payment_other.cashflowtype = '0'
                                            GROUP BY payment_other.bankcode,
                                                     payment_other.daterelease,
                                                     payment_other.cashflowtype
                                            UNION ALL
                                              SELECT payment_other.bankcode,
                                                     payment_other.daterelease,
                                                     payment_other.cashflowtype,
                                                     0 AS debet,
                                                     NVL (SUM (payment_other.amount), 0) AS credit
                                                FROM payment_other
                                               WHERE payment_other.cashflowtype = '1'
                                            GROUP BY payment_other.bankcode,
                                                     payment_other.daterelease,
                                                     payment_other.cashflowtype) pv2
                                  GROUP BY bankcode,
                                           TO_NUMBER (TO_CHAR (daterelease, 'YYYY')),
                                           TO_NUMBER (TO_CHAR (daterelease, 'MM'))
                                  UNION ALL
                                    SELECT bankcode,
                                           TO_NUMBER (TO_CHAR (daterelease, 'YYYY')) AS year,
                                           TO_NUMBER (TO_CHAR (daterelease, 'MM')) AS month,
                                           SUM (debet) AS debet,
                                           SUM (credit) AS credit
                                      FROM (  SELECT intercoloans.banktarget AS bankcode,
                                                     intercoloans.daterelease,
                                                     0 AS cashflowtype,
                                                     NVL (SUM (intercoloans.amount), 0) AS debet,
                                                     0 AS credit
                                                FROM intercoloans
                                            GROUP BY intercoloans.banktarget,
                                                     intercoloans.daterelease,
                                                     intercoloans.nocekgiro,
                                                     0
                                            UNION ALL
                                              SELECT intercoloans.banksource AS bankcode,
                                                     intercoloans.daterelease,
                                                     1 AS cashflowtype,
                                                     0 AS debet,
                                                     NVL (SUM (intercoloans.sourceamount), 0)
                                                        AS credit
                                                FROM intercoloans
                                            GROUP BY intercoloans.banksource,
                                                     intercoloans.daterelease,
                                                     1,
                                                     '')
                                  GROUP BY bankcode,
                                           TO_NUMBER (TO_CHAR (daterelease, 'YYYY')),
                                           TO_NUMBER (TO_CHAR (daterelease, 'MM')))
                        GROUP BY bankcode, year, month) pv3
                       RIGHT JOIN
                       (  SELECT bankcode,
                                 period_year,
                                 period_month,
                                 NVL (SUM (opening_balance), 0) AS opening_balance_monthly
                            FROM bankbalance
                        GROUP BY bankcode, period_year, period_month) tb_monthly
                          ON (    tb_monthly.bankcode = pv3.bankcode
                              AND tb_monthly.period_month = pv3.month
                              AND tb_monthly.period_year = pv3.year)
                 WHERE     tb_monthly.period_year = '$year'
                       AND tb_monthly.period_month = '$month'
                       AND tb_monthly.bankcode = '".$bankcode."' ";
        // return $q;
        return $this->db->query($q)->row();

    }


    public function upload_PPH($param,$location) {
        try
        {   
            $USERNAME     = $param['USERNAME'];
            $this->db->where('USERNAME',$USERNAME);
            $this->db->truncate('TEMP_UPLOAD_PPH');    
            $this->db->close();
            $this->db->trans_begin();
            
            $result = FALSE;
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
              $file = $_FILES['uploads'];
              $inputFileName = $file['tmp_name'];
              $inputFileType = IOFactory::identify($inputFileName);
              $reader = IOFactory::createReader($inputFileType);
              $spreadsheet = $reader->load($inputFileName);
              $sheet = $spreadsheet->getActiveSheet();
              $highestRow = $sheet->getHighestRow();
              $highestColumn = $sheet->getHighestColumn();

                for( $row = 2; $row <= $highestRow; $row++ ){
                      $hadError = false;
                      
                      $ERROR_MESSAGE = array();
                      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);

                      $COMPANY          = $rowData[0][0];
                      $DOCNUMBER        = $rowData[0][1];
                      $DOCREF           = $rowData[0][2];
                      $PPH              = $rowData[0][3];

                      $isNotZeroAll   = true;
                    if($COMPANY == null && $DOCNUMBER == null && $DOCREF == null && $PPH == null){
                            $isNotZeroAll = false;
                    }  
                      
                    if($isNotZeroAll){

                        $where = array('EXTSYSCOMPANYCODE' => $COMPANY);
                        $this->db->where($where);
                        $this->db->limit(1);
                        $getCompanySource = $this->db->get('COMPANY_EXTSYS')->row();
                        if($getCompanySource == null ){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "COMPANY NOT FOUND";
                        }
                        else{
                            $getCompanySource = $getCompanySource->COMPANY;
                        }

                        $getTrans = $this->db->get_where('CF_TRANSACTION', ['COMPANY' => "$getCompanySource", 'DOCNUMBER' => "$DOCNUMBER", 'DOCREF' => "$DOCREF"],1)->row();

                        // echo "<pre>";
                        // var_dump($getTrans);exit;

                        if($getTrans == NULL){
                            $hadError = true;
                            $ERROR_MESSAGE[] = "TRANSACTION NOT FOUND";
                        }
                        
                        $setData = array(
                                'COMPANY' => $getCompanySource,
                                'DOCNUMBER' => $DOCNUMBER,
                                'DOCREF' => $DOCREF,
                                'PPH' => $PPH,
                                'UUID' => $this->uuid->v4(),
                                'USERNAME' => $USERNAME
                            );
                        $result = $this->db->set($setData)->insert('TEMP_UPLOAD_PPH');
                        $thisUUID = $setData['UUID'];
                        
                        if($hadError){
                            $ermsg = implode(',', $ERROR_MESSAGE);
                            $updateErr = array('ERROR_MSG' => $ermsg);
                            //var_dump($updateErr);exit();
                            $this->db->set($updateErr);
                            $this->db->where('UUID',$thisUUID);
                            $this->db->update('TEMP_UPLOAD_PPH');
                        }
                    }//end not zero
                }//end for
                $qGet = "SELECT tup.* FROM TEMP_UPLOAD_PPH tup ";
                $res   = $this->db->query($qGet)->result();
              
                // $getTb = $this->db->get($this->TEMP_UPLOAD_PAYMENT)->result();
            }//end else
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $res
                ];
            }
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
          $this->db->close();
          return $return;
    }

    public function saveUp_PPH($param,$location){
        
        try {
            $this->db->trans_begin();
            $USERNAME     = $this->session->userdata('username');
            date_default_timezone_set("Asia/Jakarta");
            $this->db->where('USERNAME',$USERNAME);
            // $this->db->where('ERROR_MSG',null);
            $getDataTemp = $this->db->get('TEMP_UPLOAD_PPH')->result();    

            // echo "<pre>";
            //     var_dump($this->db->last_query());
            //     echo "</pre>";exit;
            if($getDataTemp){
                foreach($getDataTemp as $r){

                    $TEMPID           = $r->ID;
                    $DOCNUMBER        = $r->DOCNUMBER;
                    $COMPANY          = $r->COMPANY;
                    $DOCREF           = $r->DOCREF;
                    $PPH              = $r->PPH;

                    $getTrans = $this->db->get_where('CF_TRANSACTION', ['COMPANY' => "$COMPANY", 'DOCNUMBER' => "$DOCNUMBER", 'DOCREF' => "$DOCREF"],1)->row();
                    
                    $dataR = array(
                                  'AMOUNT_PPH'  => $PPH,
                                  'TOTAL_BAYAR'   => $getTrans->AMOUNT_INCLUDE_VAT-$PPH,
                                  "FCEDIT" => $USERNAME
                              );
                    $this->db->where('ID',$getTrans->ID);
                    $this->db->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                    $result = $this->db->set("LASTUPDATE", "SYSDATE", false)->set($dataR)->update($this->CF_TRANSACTION);

                    if($result){
                        $this->db->where('ID',$getTrans->ID);
                        $this->db->set('AMOUNT_PPH',$PPH);
                        $this->db->set("LASTUPDATE", "SYSDATE", false);
                        $this->db->set("FCEDIT",$USERNAME);
                        $this->db->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        $this->db->update($this->CF_TRANSACTION_DET);
                        $result = true;
                    }
                }
            }
            else{
                throw new Exception('Data Save Failed !!');
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
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function uploadForecast($param,$location) {
        try
        {   
            $USERNAME     = $param['USERNAME'];
            $exts         = $param['EXTSYSTEM'];
            $this->db->where('FCENTRY',$USERNAME);
            $this->db->delete('TEMP_UPLOAD_FORECAST');    
            $this->db->close();
            $this->db->trans_begin();
            
            $result = FALSE;
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
              $file = $_FILES['uploads'];
              $inputFileName = $file['tmp_name'];
              $inputFileType = IOFactory::identify($inputFileName);
              $reader = IOFactory::createReader($inputFileType);
              $spreadsheet = $reader->load($inputFileName);
              $sheet = $spreadsheet->getActiveSheet();
              $highestRow = $sheet->getHighestRow();
              $highestColumn = $sheet->getHighestColumn();

                for( $row = 2; $row <= $highestRow; $row++ ){
                      $hasError = false;
                      
                      $ERROR_MESSAGE = array();
                      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);

                      $COMPANY          = $rowData[0][0];
                      $BU               = $rowData[0][1];
                      $DEPARTMENT       = $rowData[0][2];
                      $DOCDATE          = $rowData[0][3];
                      $DOCNUMBER        = $rowData[0][4];
                      $DOCREF           = $rowData[0][5];
                      $VENDOR           = $rowData[0][6];
                      $TRANS_LOC        = $rowData[0][7];
                      $BASELINEDATE     = $rowData[0][8];
                      $PAYTERM          = $rowData[0][9];
                      $ITEMCODE         = $rowData[0][10];
                      $REMARK           = $rowData[0][11];
                      $AMOUNT           = $rowData[0][12];
                      $PPH              = $rowData[0][13];
                      $ISADD            = $rowData[0][14];
                      $CURRENCY         = $rowData[0][15];
                      $RATE             = $rowData[0][16];
                      $INVOICEVENDORNO  = $rowData[0][17];
                      $YEAR             = $rowData[0][18];
                      $MONTH            = $rowData[0][19];
                      $WEEK             = $rowData[0][20];
                      $PRIORITY         = $rowData[0][21];

                    //   var_dump($ISADD);exit;

                      $isNotZeroAll   = true;
                    if($COMPANY == null && $DOCNUMBER == null && $DOCREF == null && $PPH == null && $BU == NULL && $DEPARTMENT == NULL && $DOCDATE == null && $VENDOR == NULL && $TRANSLOC == NULL && $BASEDATE == NULL && $PAYTERM == NULL && $ITEMCODE == NULL && $REMARK == NULL && $AMOUNT == NULL && $CURRENCY == NULL && $ISADD == NULL && $RATE == NULL && $INVOICEVENDORNO == NULL && $YEAR == NULL && $MONTH == NULL && $WEEK == NULL && $PRIORITY == NULL){
                            $isNotZeroAll = false;
                    }  
                      
                    if($isNotZeroAll){
                        

                        $checkCompany = $this->checkCompany($COMPANY);
                        
                        if($checkCompany === 0 ){
                            $hasError = true;
                            $ERROR_MESSAGE[] = "COMPANY NOT FOUND";
                        }
                        else{
                            $checkCompany = $checkCompany;
                        }
                        // var_dump($checkCompany);exit;

                        $checkBusinessUnit  = $this->checkBusinessUnit($BU,$exts);
                        if($checkBusinessUnit === 0 ){
                            $hasError = true;
                            $ERROR_MESSAGE[] = "BUSINESSUNIT NOT FOUND";
                        }
                        else{
                            $checkBusinessUnit = $checkBusinessUnit;
                        }

                        $checkDepartment    = $this->checkDepartment($DEPARTMENT);
                        if($checkDepartment === 0 ){
                            $hasError = true;
                            $ERROR_MESSAGE[] = "DEPT NOT FOUND";
                        }
                        else{
                            $checkDepartment = $checkDepartment;
                        }

                        $checkVendor        = $this->checkVendor($VENDOR);
                        if($checkVendor === 0 ){
                            $hasError = true;
                            $ERROR_MESSAGE[] = "VENDOR NOT FOUND";
                        }
                        else{
                            $checkVendor = $checkVendor;
                        }

                        $checkMaterial      = $this->checkMaterial($ITEMCODE,$exts);
                        if($checkMaterial === 0 ){
                            $hasError = true;
                            $ERROR_MESSAGE[] = "MATERIAL NOT FOUND";
                        }
                        else{
                            $checkMaterial = $checkMaterial;
                        }

                        $cekDuplicate = $this->checkDuplicate($checkCompany,$DOCNUMBER,$DOCREF);
                        // var_dump($this->db->last_query());exit;
                        if($cekDuplicate === 0 ){
                            $hasError = true;
                            $ERROR_MESSAGE[] = "Company $COMPANY and Docnumber $DOCNUMBER ALREADY EXISTS";
                        }                        

                        $UNIX_DATE = ($DOCDATE - 25569) * 86400;
                        $DOCDATE  = gmdate("m-d-Y", $UNIX_DATE);

                        $UNIX_DATE = ($BASELINEDATE - 25569) * 86400;
                        $BASELINEDATE  = gmdate("m-d-Y", $UNIX_DATE);
                        
                        $setData = array(
                                'ID'      => $this->uuid->v4(),
                                'COMPANY'          => $checkCompany,
                                'BUSINESSUNIT'               => $checkBusinessUnit,
                                'DEPARTMENT'       => $checkDepartment,
                                'DOCNUMBER'        => $DOCNUMBER,
                                'DOCREF'           => $DOCREF,
                                'VENDOR'           => $checkVendor,
                                'TRANS_LOC'        => $TRANS_LOC,
                                'PAYTERM'         => $PAYTERM,
                                'ITEMCODE'        => $checkMaterial,
                                'REMARK'          => $REMARK,
                                'AMOUNT_INCLUDE_VAT'          => $AMOUNT,
                                'AMOUNT_PPH'             => $PPH,
                                'CURRENCY'        => $CURRENCY,
                                'ISADENDUM'           => $ISADD,
                                'RATE'            => $RATE,
                                'INVOICEVENDORNO' => $INVOICEVENDORNO,
                                'YEAR'            => $YEAR,
                                'MONTH'           => $MONTH,
                                'WEEK'            => 'W'.$WEEK,
                                'PRIORITY'        => $PRIORITY,
                                'FCENTRY'         => $USERNAME,
                                'FCEDIT'          => $USERNAME,
                                'FCIP'            => $location
                            );
                        $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set('DOCDATE',"TO_DATE('" . $DOCDATE . "','mm/dd/yyyy')",false)
                                ->set('BASELINEDATE',"TO_DATE('" . $BASELINEDATE . "','mm/dd/yyyy')",false)
                                ->set($setData)->insert('TEMP_UPLOAD_FORECAST');
                        $thisUUID = $setData['ID'];
                        
                        if($hasError){
                            $ermsg = implode(',', $ERROR_MESSAGE);
                            $updateErr = array('ERROR_MSG' => $ermsg);
                            //var_dump($updateErr);exit();
                            $this->db->set($updateErr);
                            $this->db->where('ID',$thisUUID);
                            $this->db->update('TEMP_UPLOAD_FORECAST');
                        }
                    }//end not zero
                }//end for
                $qGet = "SELECT tup.* FROM TEMP_UPLOAD_FORECAST tup ";
                $res   = $this->db->query($qGet)->result();
              
                // $getTb = $this->db->get($this->TEMP_UPLOAD_PAYMENT)->result();
            }//end else
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $res
                ];
            }
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
          $this->db->close();
          return $return;
    }

    public function saveUpForecast($param,$location){
        
        try {
            $this->db->trans_begin();
            $USERNAME     = $this->session->userdata('username');
            $DOCTYPE      = $param['DOCTYPE'];
            date_default_timezone_set("Asia/Jakarta");
            $this->db->where('FCENTRY',$USERNAME);
            // $this->db->where('ERROR_MSG',null);
            $getDataTemp = $this->db->get('TEMP_UPLOAD_FORECAST')->result();    

            // echo "<pre>";
            //     var_dump($this->db->last_query());
            //     echo "</pre>";exit;
            if($getDataTemp){
                foreach($getDataTemp as $r){

                    //i know i should go through insert data to cftrans 
                    //than create another variable then insert to cftrans 
                    $TEMPID             = $r->ID;
                    $COMPANY            = $r->COMPANY;
                    $BUSINESSUNIT       = $r->BUSINESSUNIT;
                    $DEPARTMENT         = $r->DEPARTMENT;
                    $DOCNUMBER          = $r->DOCNUMBER;
                    $DOCDATE            = $r->DOCDATE;
                    $DOCREF             = $r->DOCREF;
                    $VENDOR             = $r->VENDOR;
                    $TRANS_LOC          = $r->TRANS_LOC;
                    $BASELINEDATE       = $r->BASELINEDATE;
                    $PAYTERM            = $r->PAYTERM;
                    $ITEMCODE           = $r->ITEMCODE;
                    $REMARK             = $r->REMARK;
                    $AMOUNT_INCLUDE_VAT = $r->AMOUNT_INCLUDE_VAT;
                    $AMOUNT_PPH         = $r->AMOUNT_PPH;
                    $CURRENCY           = $r->CURRENCY;
                    $ISADENDUM          = $r->ISADENDUM;
                    $RATE               = $r->RATE;
                    $INVOICEVENDORNO    = $r->INVOICEVENDORNO;
                    $YEAR               = $r->YEAR;
                    $MONTH              = $r->MONTH;
                    $WEEK               = $r->WEEK;
                    $PRIORITY           = $r->PRIORITY;
                    $FCENTRY            = $r->FCENTRY;
                    $FCEDIT             = $r->FCEDIT;
                    $FCIP               = $r->FCIP;

                    // if($CURRENCY != 'IDR'){
                    //         $whereCurs = array('CURSYEAR' => $YEAR, 'CURSMONTH' => $MONTH, 'CURSCODE' => $CURRENCY);
                    //         $this->db->where($whereCurs);
                    //         $getCurs = $this->db->get('CURS')->row();
                    //         if($getCurs == NULL){
                    //             $AMOUNT_INCLUDE_VAT = $AMOUNT_INCLUDE_VAT * 1;                                
                    //         }else{
                    //             $AMOUNT_INCLUDE_VAT = $AMOUNT_INCLUDE_VAT * $getCurs->RATE;                                
                    //         }
                    //     }else{
                    //         $AMOUNT_INCLUDE_VAT = $AMOUNT_INCLUDE_VAT;
                    //     }
                    
                    $cf = array(
                                  'EXTSYS'      => $param['EXTSYSTEM'],
                                  'COMPANY'     => $COMPANY,
                                  'BUSINESSUNIT'=> $BUSINESSUNIT,
                                  'DEPARTMENT'  => $DEPARTMENT,
                                  'DOCTYPE'     => $DOCTYPE,
                                  'DOCNUMBER'   => $DOCNUMBER,
                                  'DOCDATE'     => $DOCDATE,
                                  'DOCREF'      => $DOCREF,
                                  'VENDOR'      => $VENDOR,
                                  'TRANS_LOC'   => $TRANS_LOC,
                                  'BASELINEDATE'=> $BASELINEDATE,
                                  'PAYTERM'     => $PAYTERM,
                                  'DUEDATE'     => $BASELINEDATE,
                                  'REMARK'      => $REMARK,
                                  'AMOUNT_INCLUDE_VAT' => $AMOUNT_INCLUDE_VAT * $RATE,
                                  'AMOUNT_PPH'  => $AMOUNT_PPH,
                                  'FCENTRY'     => 'U-'.$USERNAME,
                                  "FCEDIT"      => $USERNAME,
                                  'FCIP'        => $FCIP,
                                  'CURRENCY'    => $CURRENCY,
                                  'ISADENDUM'   => $ISADENDUM,
                                  'RATE'        => $RATE,
                                  'INVOICEVENDORNO' => $INVOICEVENDORNO,
                                  'VAT'         => 10,
                                  'TOTAL_BAYAR' => $AMOUNT_INCLUDE_VAT,
                                  'AMOUNT_PPN'  => (($AMOUNT_INCLUDE_VAT/(1+(10/100)))*10/100)
                              );
                    $resultcf = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    // ->set('DOCDATE', "TO_DATE('" .$formatDD. "','mm-dd-yyyy')", false)
                    // ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                    // $Data["ID"] = $this->uuid->v4();
                    $cf["ID"]       = $this->uuid->v4();
                    $cf["ISACTIVE"] = "TRUE";
                    $resultcf = $resultcf->set($cf)->insert($this->CF_TRANSACTION);

                    $where = array('ID' => $BUSINESSUNIT);
                    $this->db->where($where);
                    $getBU = $this->db->get('BUSINESSUNIT')->row();
                    

                    if($resultcf){
                        $cf_det = [
                            "ID" => $cf["ID"],
                            'MATERIAL' => $ITEMCODE,
                            'REMARKS' => $REMARK,
                            'AMOUNT_INCLUDE_VAT' => $cf['AMOUNT_INCLUDE_VAT'],
                            'AMOUNT_PPH' => $AMOUNT_PPH,
                            "ISACTIVE" => "TRUE",
                            "FCENTRY" => $USERNAME,
                            "FCEDIT" => $USERNAME,
                            "FCIP" => $location
                        ];
                        $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                        ->set($cf_det)->insert($this->CF_TRANSACTION_DET);

                        if($result2){
                            // $result2 = true;
                            $forecast = array(
                                'DEPARTMENT' => $DEPARTMENT,
                                'CFTRANSID'  => $cf["ID"],
                                'YEAR'       => $YEAR,
                                'MONTH'      => $MONTH,
                                'WEEK'       => $WEEK,
                                'AMOUNTREQUEST' => $cf['AMOUNT_INCLUDE_VAT'],
                                'AMOUNTADJS' => 0,
                                'ISACTIVE'   => 1,
                                'FCENTRY' => 'U-'.$USERNAME,
                                'FCEDIT' => $USERNAME,
                                "FCIP" => $location,
                                "PRIORITY" => $PRIORITY,
                                "LOCKS" => 1,
                                "STATE" => 0,
                                "INVOICEVENDORNO" => "",
                                'COMPANYGROUP' => $getBU->COMPANYGROUP,
                                'COMPANYSUBGROUP' => $getBU->COMPANY_SUBGROUP,
                            );
                            $result3 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                        ->set($forecast)->insert('FORECAST_FIX_TEMP');
                            $this->db->where('ID',$TEMPID);
                            $this->db->delete('TEMP_UPLOAD_FORECAST');
                        }
                    }
                    ini_set('display_errors', 'On');
                }//end foreach
            }
            else{
                ini_set('display_errors', 'On');
                throw new Exception('Data Save Failed !!');
            }
        
            if ($resultcf && $result2 && $result3) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Data Save Failed !!');
            }
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function checkDuplicate($COMPANY,$DOCNUMBER,$DOCREF){
        $cek = $this->db->select('*')
                ->from($this->CF_TRANSACTION)
                ->where([
                    'COMPANY' => $COMPANY,
                    'DOCNUMBER' => $DOCNUMBER,
                    'DOCREF' => $DOCREF,
                    'ISACTIVE' => 'TRUE'
                ])->get()->result();
        if (count($cek) > 0) {
            $get = 0;   
        }else{
            $get = 1;
        }
        return $get;
    }

    public function checkCompany($CODE){

        $where = array('EXTSYSCOMPANYCODE' => $CODE);
        $this->db->where($where);
        $get = $this->db->get('COMPANY_EXTSYS')->row();
        
        if($get == null ){
            $get = 0;
        }
        else{
            $get = $get->COMPANY;
        }
        return $get;
    }

    public function checkBusinessUnit($CODE,$exts){
        $this->db->limit(1);
        $where = array('EXTSYSBUSINESSUNITCODE' => $CODE,'EXTSYSTEM' => $exts);
        $this->db->where($where);
        $get = $this->db->get('BUSINESSUNIT_EXTSYS')->row();
        // var_dump($this->db->last_query());exit;
        if($get == null ){
            $get = 0;
        }
        else{
            $get = $get->BUSINESSUNIT;
        }
        return $get;
    }

    public function checkDepartment($CODE){
        $this->db->limit(1);
        $where = array('FCCODE' => $CODE);
        $this->db->where($where);
        $get = $this->db->get('DEPARTMENT')->row();
        if($get == null ){
            $get = 0;
        }
        else{
            $get = $get->FCCODE;
        }
        return $get;
    }

    public function checkVendor($CODE){
        $this->db->limit(1);
        $this->db->where('FCCODE',"$CODE");
        $getVendor = $this->db->get('SUPPLIER')->row();

        if($getVendor == NULL){
            $getVendor= 0; 
        }else{
            $getVendor = $getVendor->ID;
        }
        return $getVendor;
    }

    public function checkMaterial($CODE,$exts){
        $this->db->limit(1);
        $this->db->where('FCCODE',"$CODE");
        $this->db->where('EXTSYSTEM',"$exts");
        $getMaterial = $this->db->get('MATERIAL')->row();

        if($getMaterial == NULL){
            $getMaterial = 0; 
        }else{
            $getMaterial = $getMaterial->ID;
        }
        return $getMaterial;
    }

    // public function UploadPaymentReceive($param,$location) {
    //     try
    //     {
    //         $this->db->trans_begin();
    //         $this->db->from($this->TEMP_UPLOAD_PAYMENT);
    //         $this->db->truncate();
    //         $result = FALSE;
    //         $data = [];
    //         if (!isset($_FILES['uploads'])) {
    //             throw new Exception('No files uploaded!!');
    //         } else {
    //           $file = $_FILES['uploads'];
    //           $inputFileName = $file['tmp_name'];
    //           $inputFileType = IOFactory::identify($inputFileName);
    //           $reader = IOFactory::createReader($inputFileType);
    //           $spreadsheet = $reader->load($inputFileName);
    //           $sheet = $spreadsheet->getSheet(0);
    //           $highestRow = $sheet->getHighestRow();
    //           $highestColumn = $sheet->getHighestColumn();

    //           $USERNAME     = $param['USERNAME'];
    //           $CASHFLOWTYPE = $param['CASHFLOWTYPE'];

              
              
    //             for( $row = 2; $row <= $highestRow; $row++ ){
    //                   $hadError = false;
    //                   $getFORECASTid = null;
    //                   $getBANK       = null;
    //                   $getCFTRANSID  = null;
                      
    //                   $ERROR_MESSAGE = array();
    //                   $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);

    //                   $VENDORCODE  = $rowData[0][0];
    //                   $NO_PO       = $rowData[0][1];
    //                   $NO_INVOICE  = $rowData[0][2];
    //                   // $CURRENCY    = $rowData[0][1];
    //                   $BANKACCOUNT = $rowData[0][3];
    //                   $PAID_DATE   = $rowData[0][4];
    //                   $AMOUNT      = $rowData[0][5];
    //                   $RATE        = $rowData[0][6];
    //                   $CONVERSI    = $rowData[0][7];
    //                   $VOUCHERNO   = $rowData[0][8];
    //                   $NOCEKGIRO   = $rowData[0][9];
    //                   $REMARKS     = $rowData[0][10];

    //                   $isNotZeroAll   = true;
    //                   if($VENDORCODE == null && $NO_PO == null && $NO_INVOICE == null && $BANKACCOUNT == null && $PAID_DATE == null && $AMOUNT == null && $RATE == null && $CONVERSI == null && $VOUCHERNO == null && $NOCEKGIRO == null && $REMARKS == null ){
    //                     $isNotZeroAll = false;
    //                   }
    //                   else if($VENDORCODE == null && $NO_PO == null && $NO_INVOICE == null && $BANKACCOUNT == null && $PAID_DATE == null && $AMOUNT == null && $RATE == null && $VOUCHERNO == null && $NOCEKGIRO == null && $REMARKS == null){
    //                         $hadError        = true;
    //                         $ERROR_MESSAGE[] = "Column Cannot Null"; 
    //                   }
    //                   if($isNotZeroAll){
    //                     $this->db->where("FCCODE","$VENDORCODE");
    //                     $getVendor = $this->db->get('SUPPLIER')->row();
    //                     // $q = "SELECT * FROM SUPPLIER WHERE FCCODE = '".$VENDORCODE."'";
    //                     // $getVendor = $this->db->query($q)->row();

    //                   if($getVendor == NULL){
    //                     $hadError        = true;
    //                     $ERROR_MESSAGE[] = "VENDOR CODE NOT FOUND"; 
    //                   }
    //                   else{
    //                     // $getCFTRANSID = '';
    //                     $vendorCode = $getVendor->ID;
    //                     $this->db->select('*');
    //                     $this->db->where('VENDOR',"$vendorCode");
    //                     $getIdTrans = $this->db->get('CF_TRANSACTION')->row();

    //                     if($getIdTrans->VENDOR != null){
    //                         if($NO_PO != null && $NO_INVOICE != null ){
    //                             $this->db->where('DOCREF', "$NO_PO");
    //                             $this->db->where('DOCNUMBER', "$NO_INVOICE");
    //                             $getIdTrans = $this->db->get('CF_TRANSACTION')->row();
    //                             $getCFTRANSID = $getIdTrans->ID;    
    //                         }
    //                         if($NO_PO == null && $NO_INVOICE != null ){
    //                             $this->db->where('DOCNUMBER', "$NO_INVOICE");
    //                             $getIdTrans = $this->db->get('CF_TRANSACTION')->row();
    //                             if($getIdTrans == null ){
    //                                 $hadError = true;
    //                                 $ERROR_MESSAGE[] = "NO INVOICE ON TRANSACTION NOT FOUND";
    //                             }
    //                             else{
    //                                 $getCFTRANSID = $getIdTrans->ID;
    //                             }
    //                         }
    //                     }
    //                     else{
    //                         $hadError = true;
    //                         $ERROR_MESSAGE[] = "VENDOR CODE ON TRANSACTION NOT FOUND";
    //                     }

    //                     // var_dump($this->db->last_query());exit();
    //                       $this->db->select('*');
    //                       $this->db->where('FCCODE',"$BANKACCOUNT");
    //                       $getBankCode = $this->db->get('BANK')->row();
                          
    //                       // $getBANK = '';
    //                       if($getBankCode == NULL){
    //                         $hadError = true;
    //                         $ERROR_MESSAGE[] = "BANK ACCOUNT NOT FOUND";
    //                       }
    //                       else{
    //                         $getBANK = $getBankCode->FCCODE;
    //                         // if($getBankCode->COMPANY != $getIdTrans->COMPANY){
    //                         //     $hadError = true;
    //                         //     $ERROR_MESSAGE[] = "BANK ACCOUNT NOT SAME";
    //                         //   }else{
    //                         //     $getBANK = $getBankCode->FCCODE;
    //                         //   }
    //                       }

    //                       $this->db->select('ID');
    //                       $this->db->where('CFTRANSID',"$getCFTRANSID");
    //                       $getFORECAST = $this->db->get('FORECAST_FIX')->row();

    //                       if($getFORECAST == null){
    //                         $getFORECASTid = NULL;
    //                       }else{
    //                         $getFORECASTid = $getFORECAST->ID;
    //                       }

    //                       $UNIX_DATE = ($PAID_DATE - 25569) * 86400;
    //                       $paidDate  = gmdate("m-d-Y", $UNIX_DATE);

    //                       // $getDate = explode('/', $PAID_DATE);
    //                       $year    = $getDate[2];
    //                       $month   = $getDate[0];

    //                       $q2 = "SELECT b.bankcode,c.bankaccount,b.period_year,b.period_month,b.opening_balance,NVL (a.amount, 0) AS amount,b.opening_balance - NVL (a.amount, 0) AS sisa FROM (  SELECT bankcode,TO_CHAR (daterelease, 'yyyy') AS period_year,TO_CHAR (daterelease, 'mm') AS period_month,SUM (amount) AS amount FROM payment GROUP BY bankcode,TO_CHAR (daterelease, 'yyyy'),TO_CHAR (daterelease, 'mm')) a ";
    //                       $q2.= "RIGHT JOIN (SELECT period_year,period_month,bankcode,opening_balance FROM bankbalance) b ON (    a.bankcode = b.bankcode AND a.period_year = b.period_year AND a.period_month = b.period_month) ";
    //                       $q2.= "INNER JOIN (SELECT fccode, bankaccount FROM bank) c ON (a.bankcode = c.fccode) ";
    //                       $q2.=" WHERE b.period_year = '$year' AND b.period_month = '$month' AND b.bankcode = '$getBANK' ORDER BY b.bankcode, c.bankaccount, b.period_year, b.period_month";
    //                       $getSisa = $this->db->query($q2)->row();

    //                       $amountConversi = (double)$CONVERSI;
    //                       if($getSisa == null){
    //                         $hadError = true;
    //                         $ERROR_MESSAGE[] = "Bank Account doesn't exist in the Bank Balance of Year and Month entered";
    //                       }elseif($getSisa->SISA <= $amountConversi){
    //                         $hadError = true;
    //                         $ERROR_MESSAGE[] = "Saldo Bank Account tidak mencukupi atas payment yang dibayarkan.";
    //                       }
    //                   }//end else vendor


    //                     $dataR = array(
    //                               'FORECASTID'   => $getFORECASTid,
    //                               'BANKCODE' => $getBANK,
    //                               // 'CURRENCY' => $CURRENCY,
    //                               'AMOUNT' => $AMOUNT,
    //                               'RATE' => $RATE,
    //                               'AMOUNTBANK' => $CONVERSI,
    //                               'VOUCHERNO' => $VOUCHERNO,
    //                               'NOCEKGIRO' => $NOCEKGIRO,
    //                               'REMARK' => $REMARKS,
    //                               'ISACTIVE' => 1,
    //                               "FCENTRY" => $USERNAME,
    //                               "FCEDIT" => $USERNAME,
    //                               "FCIP" => $location,
    //                               "CASHFLOWTYPE" => $param['CASHFLOWTYPE'],
    //                               "CFTRANSID" => $getCFTRANSID,
    //                               "UUID" => $this->uuid->v4()
    //                           );
                        
    //                     // var_dump($paidDate);exit();
    //                     $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
    //                             ->set("DATERELEASE","SYSDATE",false)
    //                             ->set($dataR)->insert($this->TEMP_UPLOAD_PAYMENT);
    //                     $thisUUID = $dataR['UUID'];
    //                     $q = "UPDATE TEMP_UPLOAD_PAYMENT SET DATERELEASE = TO_DATE('".$paidDate."','MM-DD-YYYY') WHERE UUID = '$thisUUID'";
    //                     $this->db->query($q);
                        
    //                     if($hadError){
    //                         $ermsg = implode(',', $ERROR_MESSAGE);
    //                         $updateErr = array('ERROR_MESSAGE' => $ermsg);
    //                         //var_dump($updateErr);exit();
    //                         $this->db->set($updateErr);
    //                         $this->db->where('UUID',$thisUUID);
    //                         $this->db->update('TEMP_UPLOAD_PAYMENT');
    //                     }
    //                   }//end not zero
    //             }//end for
    //             $qGet = "SELECT tup.*, cf.VENDOR, cf.DOCREF, cf.DOCNUMBER, supp.FCNAME as FCCODE FROM TEMP_UPLOAD_PAYMENT tup LEFT JOIN CF_TRANSACTION cf ON cf.ID = tup.CFTRANSID LEFT JOIN SUPPLIER supp ON supp.ID = cf.VENDOR";
    //             $res   = $this->db->query($qGet)->result();
    //             // $getTb = $this->db->get($this->TEMP_UPLOAD_PAYMENT)->result();
    //         }//end else
    //         if ($result) {
    //             $this->db->trans_commit();
    //             $return = [
    //                 'STATUS' => TRUE,
    //                 'MESSAGE' => $res
    //             ];
    //         }
    //     } 
    //     catch (Exception $ex) {
    //         $this->db->trans_rollback();
    //         $return = [
    //             'STATUS' => FALSE,
    //             'MESSAGE' => $ex->getMessage()
    //         ];
    //     }
    //   $this->db->close();
    //   return $return;
    // }

    // public function saveUpPaymentReceive($param,$location){
    //     try {
    //         $this->db->trans_begin();
    //         $USERNAME     = $param['USERNAME'];
    //         date_default_timezone_set("Asia/Jakarta");
    //         $getDataTemp = $this->db->get($this->TEMP_UPLOAD_PAYMENT)->result();
                
    //             if($getDataTemp){
    //                 foreach($getDataTemp as $r){

    //                     $TEMPPAYMENTID           = $r->PAYMENTID;
    //                     $FORECASTID              = $r->FORECASTID;
    //                     $DATERELEASE             = $r->DATERELEASE;
    //                     $BANKCODE                = $r->BANKCODE;
    //                     $VOUCHERNO               = $r->VOUCHERNO;
    //                     $NOCEKGIRO               = $r->NOCEKGIRO;
    //                     $AMOUNT                  = $r->AMOUNT;
    //                     $ISACTIVE                = $r->ISACTIVE;
    //                     $CFTRANSID               = $r->CFTRANSID;
    //                     $CASHFLOWTYPE            = $r->CASHFLOWTYPE;
    //                     $REMARK                  = $r->REMARK;
    //                     $RATE                    = $r->RATE;
    //                     $AMOUNTBANK              = $r->AMOUNTBANK;
                        
    //                     $data = array(
    //                         'FORECASTID' => $FORECASTID,
    //                         'DATERELEASE'=> $DATERELEASE,
    //                         'BANKCODE'   => $BANKCODE,
    //                         'VOUCHERNO'  => $VOUCHERNO,
    //                         'NOCEKGIRO'  => $NOCEKGIRO,
    //                         'AMOUNT'     => $AMOUNT,
    //                         'ISACTIVE'   => $ISACTIVE,
    //                         'FCENTRY'    => $USERNAME,
    //                         'FCEDIT'     => $USERNAME,
    //                         'FCIP'       => $location,
    //                         'CFTRANSID'  => $CFTRANSID,
    //                         'CASHFLOWTYPE' => $CASHFLOWTYPE,
    //                         'REMARK'     => $REMARK,
    //                         'RATE'       => $RATE,
    //                         'AMOUNTBANK' => $AMOUNTBANK
    //                     );
    //                     $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
    //                             ->set($data)->insert($this->PAYMENT);
    //                     //delete temp data after save
    //                     $this->db->where('PAYMENTID',$TEMPPAYMENTID);
    //                     $this->db->delete($this->TEMP_UPLOAD_PAYMENT);
    //                 }
    //             }
    //             else{
    //                 throw new Exception('Data Save Failed !!');
    //             }
    //         if ($result) {
    //             $this->db->trans_commit();
    //             $return = [
    //                 'STATUS' => TRUE,
    //                 'MESSAGE' => 'Data has been Successfully Saved !!'
    //             ];
    //         } else {
    //             throw new Exception('Data Save Failed !!');
    //         }
    //     } 
    //     catch (Exception $ex) {
    //         $this->db->trans_rollback();
    //         $return = [
    //             'STATUS' => FALSE,
    //             'MESSAGE' => $ex->getMessage()
    //         ];
    //     }
    //     $this->db->close();
    //     return $return;
    // }

}
