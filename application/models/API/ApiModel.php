<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ApiModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function fetchDataCFTrans($param) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $data = [];
            $EXTSYSTEM = 'TIPTOP';
            $USERNAME = 'CEMINDOAPI';
            $UUID = $this->uuid->v4();
            $getFirstFourDN = null;
            $DOCTYPE = 'PO';
            $idx = 0;

            // $make_call = file_get_contents("http://10.10.10.94:81/assets/datapocement2.json");
            $parse = parse_url($param);
            parse_str($parse['query'], $reqParam);
            
            $endpoin = $parse['scheme'].'://'.$parse['host'].$parse['path'];

            $make_call = $this->callAPI('POST', $endpoin, $parse['query']);
            $response = json_decode($make_call, true);

            if ((isset($response['status']) && $response['status'] == false) || !$response) {
                throw new Exception($response['message'] ?? 'Connection Failure');
            }

            foreach ($response as $value){
                $IS_ADDENDUM = $value['IS_ADDENDUM'] ?? '';
                $BASELINEDATE = isset($value['BASELINE_DATE'])
                                ? date("m/d/Y", strtotime(str_replace('/', '-', $value['DOC_DATE'])))
                                : '';
                $ITEMCODE = $value['ITEM_CODE'] ?? '';

                if ($reqParam['pid'] === 'AP' || $reqParam['pid'] === 'ap') {
                    $getFirstFourDN = substr($value['DOC_NUMBER'], 0, 4);
                    $getFirstThreeDN = substr($value['DOC_NUMBER'], 0, 3);
                    $getFirstTwoDN = substr($value['DOC_REF'], 0, 2);
                    $ITEMCODE = 'INV_'.$getFirstTwoDN;

                    $BASELINEDATE = isset($value['BASELINE_DATE'])
                                ? date("m/d/Y", strtotime($value['DOC_DATE']))
                                : '';

                    $except = ['AP4', 'AP2', 'ap4', 'ap2'];

                    if (!in_array($getFirstThreeDN, $except)) {
                        $DOCTYPE = 'INV';
                    }

                    if ($getFirstThreeDN === 'AP2' || $getFirstThreeDN === 'ap2') {
                        $DOCTYPE = 'INV_AP_SPC';
                        $ITEMCODE = 'INV_AP20';
                    }

                    if (isset($value['DOC_REF_ADDENDUM'])) {
                        $IS_ADDENDUM = 'TRUE';
                    }
                    
                }

                if ($getFirstFourDN !== 'AP40') {
                    $dt = [
                        'UUID' => $UUID,
                        'ID' => $idx,
                        'COMPANY' => '',
                        'COMPANYCODE' => $value['COMPANY'] ?? '',
                        'BUSINESSUNIT' => '',
                        'BUSINESSUNITCODE' => $value['BA'] ?? '',
                        'DEPARTMENT' => '',
                        'DEPARTMENTCODE' => $value['DEPARTMENT'] ?? '',
                        'DOCDATE' => $value['DOC_DATE'] ? date("m/d/Y", strtotime(str_replace('/', '-', $value['DOC_DATE']))) : '',
                        'DOCNUMBER' => $value['DOC_NUMBER'] ?? '',
                        'DOCREF' => $value['DOC_REF'] ?? '',
                        'TRANS_LOC' => $value['DOC_REF_ADDENDUM'] ?? '',
                        'VENDORCODE' => $value['THIRD_PARTIES_CODE'] ?? '',
                        'BASELINEDATE' => $BASELINEDATE,
                        'PAYTERM' => $value['PAYMENT_TERM'] ?? '',
                        'MATERIALCODE' => $ITEMCODE,
                        'REMARKS' => $value['REMARKS'] ?? '',
                        'AMOUNT_INCLUDE_VAT' => $value['AMOUNT'] ?? '',
                        'AMOUNT_PPH' => $value['PPH'] ?? '',
                        'ISADENDUM' => $IS_ADDENDUM,
                        'CURRENCY' => $value['CURRENCY'] ?? '',
                        'RATE' => $value['RATE'] ?? '',
                        'MESSAGEH' => '',
                        'MESSAGED' => '',
                        'STATUSH' => 0,
                        'STATUSD' => 0,
                        'EXTSYSTEM' => 'TIPTOP',
                        'DOCTYPE' => $DOCTYPE
                    ];
                    
                    $data[] = $dt;
                    $idx++;
                }
                
            }
            
            $result = $this->db->insert_batch($this->TEMP_UPLOAD_PO, $data);

            //Result
            //Update Field Company and Pesan Error
            if ($result) {
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

                //Update Field Business Unit and Pesan Error
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

                //Update Field Department and Pesan Error
                $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                            SET TUP.DEPARTMENT = (SELECT FCCODE
                                                    FROM $this->DEPARTMENT
                                                    WHERE FCCODE = TUP.DEPARTMENTCODE)
                            WHERE TUP.STATUSD <> 1
                            AND TUP.UUID = ?";
                $result = $this->db->query($SQL, $UUID);

                $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                            SET TUP.STATUSD = 1,
                                TUP.MESSAGED = 'Department Not Found !!!'
                            WHERE TUP.STATUSD <> 1 
                            AND TUP.DEPARTMENT IS NULL
                            AND TUP.UUID = ?";
                $result = $this->db->query($SQL, [$UUID]);

                //Update Field Material
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

                //Update Field Vendor
                $SQL = "UPDATE $this->TEMP_UPLOAD_PO TP 
                            SET TP.VENDORCODE = (SELECT TUP.VENDORCODE
                                                    FROM (SELECT TUP.COMPANYCODE, TUP.DOCNUMBER, MAX(TUP.VENDORCODE) AS VENDORCODE, COUNT(*) AS JML
                                                            FROM (SELECT DISTINCT TUP.COMPANYCODE, TUP.DOCNUMBER, TUP.VENDORCODE
                                                                    FROM $this->TEMP_UPLOAD_PO TUP
                                                                    WHERE TUP.UUID = ?) TUP
                                                            GROUP BY TUP.COMPANYCODE, TUP.DOCNUMBER) TUP
                                                    WHERE TUP.COMPANYCODE = TP.COMPANYCODE
                                                    AND TUP.DOCNUMBER = TP.DOCNUMBER)
                            WHERE TP.STATUSD <> 1
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

                //Cek Docnumber Same
                $SQL = "UPDATE $this->TEMP_UPLOAD_PO TUP 
                            SET TUP.STATUSD = 1,
                                TUP.MESSAGED = 'Some Data Already Exists !!!'
                            WHERE TUP.STATUSD <> 1
                            AND CONCAT(TUP.COMPANY, TUP.DOCNUMBER) IN (SELECT CONCAT(CFT.COMPANY, CFT.DOCNUMBER)
                                                                        FROM CF_TRANSACTION CFT
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
                                                                        FROM CF_TRANSACTION CFT
                                                                        WHERE ISACTIVE = 'TRUE'
                                                                            AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR'))
                            AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR')
                            AND TUP.UUID = ?";
                $result = $this->db->query($SQL, [$UUID]);

                //Cari Doc Ref
                if ($reqParam['pid'] === 'AP' || $reqParam['pid'] === 'ap') {
                    //normal inv
                    $SQL = "UPDATE $this->TEMP_UPLOAD_PO UPO
                               SET (UPO.STATUSD, UPO.MESSAGED) = (SELECT DECODE(CT.JML, 0, 1, 0) AS STATUSD, DECODE(CT.JML, 0, 'Doc Ref Not Found !!', '') AS MESSAGED
                                                                    FROM (SELECT COUNT(*) AS JML
                                                                            FROM $this->CF_TRANSACTION CTR
                                                                           WHERE CTR.DOCNUMBER = UPO.DOCREF
                                                                           AND CTR.COMPANY = UPO.COMPANY) CT)
                             WHERE UPO.STATUSD <> 1
                               AND UPO.MATERIALCODE <> 'INV_AP20'
                               AND UPO.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);

                    //addendum inv
                    $SQL = "UPDATE $this->TEMP_UPLOAD_PO UPO
                               SET (UPO.STATUSD, UPO.MESSAGED) = (SELECT DECODE(CT.JML, 0, 1, 0) AS STATUSD, DECODE(CT.JML, 0, 'Doc Ref addendum Not Found !!', '') AS MESSAGED
                                                                    FROM (SELECT COUNT(*) AS JML
                                                                            FROM $this->CF_TRANSACTION CTR
                                                                           WHERE CTR.DOCNUMBER = UPO.DOCREF
                                                                             AND CTR.COMPANY = UPO.COMPANY) CT)
                             WHERE UPO.STATUSD <> 1 
                               AND UPO.MATERIALCODE <> 'INV_AP20'
                               AND UPO.UUID = ?";
                    $result = $this->db->query($SQL, [$UUID]);
                }

                //Update Error Di Header PO
                $SQL = "UPDATE $this->TEMP_UPLOAD_PO UPO
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

                //Select Header PO and Get Detail PO
                $SQL = "SELECT DISTINCT TUP.COMPANY, TUP.COMPANYCODE, TUP.BUSINESSUNIT, TUP.BUSINESSUNITCODE, TUP.DEPARTMENT, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, TUP.STATUSH, TUP.MESSAGEH, TUP.CURRENCY, TUP.ISADENDUM, TUP.RATE, TP.JML, TP.AMOUNT_INCLUDE_VAT, TP.AMOUNT_PPH, TUP.DOCTYPE
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

                //Clear Temporary Table
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

    public function saveDataCFTrans($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $res = FALSE;
            $USERNAME = 'CEMINDOAPI';

            foreach ($Data['DATA'] as $value) {

                $ADENDUM = '';
                if (isset($value['ISADENDUM']) && $value['ISADENDUM'] == 0) $ADENDUM = 'FALSE';
                if (isset($value['ISADENDUM']) && $value['ISADENDUM'] == 1) $ADENDUM = 'TRUE';

                $dt = [
                    'EXTSYS' => $value['datadetail'][0]['EXTSYSTEM'],
                    'DOCTYPE' => $value['datadetail'][0]['DOCTYPE'],
                    'COMPANY' => $value['COMPANY'],
                    'BUSINESSUNIT' => $value['BUSINESSUNIT'],
                    'DEPARTMENT' => $value['DEPARTMENT'],
                    'DOCNUMBER' => $value['DOCNUMBER'],
                    'DOCREF' => $value['datadetail'][0]['DOCREF'],
                    'VENDOR' => $value['datadetail'][0]['VENDOR'],
                    // 'DOC_REF_ADDENDUM' => $value['datadetail'][0]['DOC_REF_ADDENDUM'],
                    'PAYTERM' => $value['datadetail'][0]['PAYTERM'],
                    'AMOUNT_INCLUDE_VAT' => $value['AMOUNT_INCLUDE_VAT'],
                    'AMOUNT_PPH' => $value['AMOUNT_PPH'],
                    'ISADENDUM' => $ADENDUM,
                    'CURRENCY' => $value['CURRENCY'],
                    'RATE' => $value['RATE'],
                    'ISACTIVE' => 'TRUE',
                    'FCENTRY' => $USERNAME,
                    'FCEDIT' => $USERNAME,
                    'UPLOAD_REF' => $USERNAME,
                    'FCIP' => $Location,
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

                $validasi = "SELECT * FROM $this->CF_TRANSACTION WHERE COMPANY = ? AND DOCNUMBER = ?";
                $CekLagi = $this->db->query($validasi, $dt['COMPANY'], $dt['DOCNUMBER']])->result();
                if (count($CekLagi) > 0) {
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
                            'FCENTRY' => $USERNAME,
                            'FCEDIT' => $USERNAME,
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

    public function fetchRAW($param) {
        $parse = parse_url($param);
        $endpoin = $parse['scheme'].'://'.$parse['host'].$parse['path'];
        $req = substr($param, strpos($param, "?") + 1);
        $make_call = $this->callAPI('POST', $endpoin, $req);
        $response = json_decode($make_call, true);

        if ((isset($response['status']) && $response['status'] == false) || !$response) {
            return 'Connection Failure';
        }

        return $response;
    }

    private function callAPI($method, $endPoint, $data){
        $curl = curl_init();

        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
              break;
           default:
              if ($data)
                 $endPoint = sprintf("%s?%s", $endPoint, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $endPoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           "Content-Type: application/x-www-form-urlencoded"
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);

        if (!$result) return false;
        
        return $result;
    }

}
