<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadModelHana extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Save_PO($Data, $Location)
    {
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
                    'AMOUNT_INCLUDE_VAT' => $value['AMOUNT_INCLUDE_VAT'],
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

    public function Save_POHana($Data, $Location)
    {
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
                    'AMOUNT_INCLUDE_VAT' => $value['AMOUNT_INCLUDE_VAT'],
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

    public function DataUpload($param)
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
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, TRUE, TRUE, TRUE);
                //                var_dump($sheetData);
                $UUID = $param['UUID'];
                $EXTSYSTEM = $param['EXTSYSTEM'];
                $DOCTYPE = $param['DOCTYPE'];
                $USERNAME = $param['USERNAME'];
                $idx = 0;
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
                                'VENDORCODE' => strval($value['G']),
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
                                if ($dt["DOCREF"] == NULL && $dt["DOCREF"] == '') {
                                    $dt['STATUSD'] = 1;
                                    $dt['MESSAGED'] = "Doc Ref can't be empty !!";
                                }
                            }
                            if ($dt["DOCDATE"] != NULL && $dt["DOCDATE"] != '') {
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

                    //                  Update Error Di Header PO
                    $SQL = "                             
UPDATE $this->TEMP_UPLOAD_PO UPO
   SET (STATUSH, MESSAGEH) = (SELECT DECODE(TP.JML, 1, (SELECT DISTINCT TEU.STATUSD
                                                          FROM $this->TEMP_UPLOAD_PO TEU
                                                         WHERE TEU.UUID = TP.UUID 
                                                           AND (TEU.COMPANYCODE = TP.COMPANYCODE OR TEU.COMPANYCODE IS NULL)
                                                           AND (TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE OR TEU.BUSINESSUNITCODE IS NULL)
                                                           AND (TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE OR TEU.DEPARTMENTCODE IS NULL)
                                                           AND TEU.DOCNUMBER = TP.DOCNUMBER), 1) AS STATUSH, 
                                     DECODE(TP.JML, 1, (SELECT DISTINCT TEU.MESSAGED
                                                          FROM $this->TEMP_UPLOAD_PO TEU
                                                         WHERE TEU.UUID = TP.UUID 
                                                           AND (TEU.COMPANYCODE = TP.COMPANYCODE OR TEU.COMPANYCODE IS NULL)
                                                           AND (TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE OR TEU.BUSINESSUNITCODE IS NULL)
                                                           AND (TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE OR TEU.DEPARTMENTCODE IS NULL)
                                                           AND TEU.DOCNUMBER = TP.DOCNUMBER), 'Please, Check Data Doc Date until Payterm must be the same, and there are no errors lined up !!') AS MESSAGEH
                                FROM (SELECT TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, COUNT(*) AS JML
                                        FROM (SELECT DISTINCT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, DOCDATE, VENDOR, DOCTYPE, DOCREF, TRANS_LOC,
                                                     BASELINEDATE, PAYTERM, MESSAGED
                                                FROM $this->TEMP_UPLOAD_PO
                                               WHERE UUID = ?) TUP
                                       GROUP BY TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER) TP 
                               WHERE (TP.COMPANYCODE = UPO.COMPANYCODE OR TP.COMPANYCODE IS NULL)
                                 AND (TP.BUSINESSUNITCODE = UPO.BUSINESSUNITCODE OR TP.BUSINESSUNITCODE IS NULL)
                                 AND (TP.DEPARTMENTCODE = UPO.DEPARTMENTCODE OR TP.DEPARTMENTCODE IS NULL)
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

    public function UploadPO1($param)
    {
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

    public function UploadPO2($param)
    {
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

    public function SetUploadPO($param)
    {
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

    public function UploadPO5($param)
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
                                $xlsBU        = $value['I'];
                                $qGetIdBu     = "SELECT ID FROM BUSINESSUNIT WHERE FCCODE = '$xlsBU'";
                                $getIdBu      = $this->db->query($qGetIdBu)->row();
                                $getIdBu      = $getIdBu->ID;

                                if($getIdBu == NULL){
                                    $IDBU = 'BUSINESSUNIT NOT FOUND';
                                    $valJ = $IDBU;
                                }else{
                                    $IDBU = $getIdBu;
                                    $valJ = $value['I'];
                                }
                                $dt = [
                                    'UUID' => $UUID,
                                    'ID' => $idx,
                                    'COMPANY' => '',
                                    'COMPANYCODE' => substr($value['I'], 0, 2),
                                    'BUSINESSUNIT' => $IDBU,
                                    'BUSINESSUNITCODE' => $IDBU,
                                    // 'BUSINESSUNIT' => '',
                                    // 'BUSINESSUNITCODE' => substr($value['I'], 0, 2) . substr($value['J'], 2, 2),
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

    public function CheckValidation($EXTSYSTEM, $UUID, $USERNAME)
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
        // $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
        //            SET TUP.STATUSD = 1,
        //                TUP.MESSAGED = 'Business Unit Not Found !!!'
        //          WHERE TUP.STATUSD <> 1 
        //            AND TUP.BUSINESSUNIT IS NULL
        //            AND TUP.UUID = ?";
        // $result = $this->db->query($SQL, [$UUID]);

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
}
