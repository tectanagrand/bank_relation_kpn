<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ForecastModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }

    public function GetWeek($param) {
        $MOUNT = strtoupper(explode(' ', $param['PERIOD'])[0]);
        $YEAR = explode(' ', $param['PERIOD'])[1];
        $SQL = "SELECT WEEK, CONCAT(WEEK, CONCAT(' (', CONCAT(DATEFROM, CONCAT('-', CONCAT(DATEUNTIL, ')'))))) AS WEEKKET, MONTH
                  FROM $this->SETTING_WEEK
                 WHERE MONTHNAME = ?
                   AND YEAR = ?";
        $result = $this->db->query($SQL, [$MOUNT, $YEAR])->result();
        $this->db->close();
        return $result;
    }

    public function showLogForecast($param){
        $param["CASHFLOWTYPE"] = "%" . $param["CASHFLOWTYPE"] . "%";
        $SQL = "SELECT DISTINCT CFT.*, DT.CASHFLOWTYPE, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,(  CFT.AMOUNT_INCLUDE_VAT
          - NVL (FO.AMOUNTINV, 0)
          + NVL (FF.AMOUNTINV, 0))
            AS AMOUNTOUTSTANDING,
            FF.FCENTRY,
            FF.FCEDIT
                  FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR' OR CFT.DOCTYPE = 'LEASING' OR CFT.DOCTYPE = 'INV_AR_SPC')
                         UNION ALL
                        SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN','LEASING','LOAN')
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AP_SPC' and CFT.EXTSYS ='TIPTOP') CFT
                 INNER JOIN (SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5,
                                    FF.FCENTRY,
                                    FF.FCEDIT
                               FROM $this->FORECAST_FIX FF
                              WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                              GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS, FF.FCENTRY, FF.FCEDIT
                              UNION ALL
                              SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5,
                                    FF.FCENTRY,
                                    FF.FCEDIT
                               FROM FORECAST_FIX_TEMP FF
                              WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                              GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS, FF.FCENTRY, FF.FCEDIT) FF
                         ON FF.CFTRANSID = CFT.ID
                  LEFT JOIN (SELECT CFTRANSID, SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                               FROM $this->FORECAST_FIX
                              GROUP BY CFTRANSID) FO
                         ON FO.CFTRANSID = CFT.ID
                 INNER JOIN $this->DOCTYPE DT
                         ON DT.FCCODE = CFT.DOCTYPE
                 INNER JOIN $this->COMPANY C
                         ON C.ID = CFT.COMPANY
                 INNER JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = CFT.DEPARTMENT 
                  LEFT JOIN CURS CR
                         ON CR.CURSCODE = CFT.CURRENCY
                        AND CR.CURSYEAR = ?
                        AND CR.CURSMONTH = ?
                 WHERE DT.CASHFLOWTYPE LIKE ?";

        $SQL1 = "SELECT FD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, SUM(FD.AMOUNTREQUEST) AS AMOUNTREQUEST, SUM(FD.AMOUNTADJS) AS AMOUNTADJS,
                        SUM(FD.AMOUNTREVISI) AS AMOUNTREVISI, SUM(FD.AMOUNTAPPROVE) AS AMOUNTAPPROVE, FD.LOCKS, FD.ISACTIVE
                   FROM $this->FORECASTDANA FD
                  INNER JOIN $this->USER_DEPART UD
                          ON UD.FCCODE = ?
                         AND UD.DEPARTMENT = FD.DEPARTMENT
                  WHERE FD.YEAR = ?
                    AND FD.MONTH = ? 
                    AND FD.CASHFLOWTYPE LIKE ?";
        $ARR1 = [$param['YEAR'], $param['MONTH'],$param['YEAR'], $param['MONTH'], $param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["CASHFLOWTYPE"]];
        if ($param["DEPARTMENT"] != NULL && $param["DEPARTMENT"] != '') {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            array_push($ARR1, $param["DEPARTMENT"]);
        }
        if ($param["USERACCESS"] == "100003") {
            $SQL .= " AND FF.LOCKS = 1";
        }
        $SQL .= " ORDER BY DT.CASHFLOWTYPE, CFT.DEPARTMENT,
                           CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER, CFT.INVOICEVENDORNO";

        $result = $this->db->query($SQL, $ARR1)->result();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    public function SaveForecastTemp($Data, $Location) {
        // echo "<pre>";
        // var_dump($Data);exit;
        try {
            if($Data['COMPANYGROUP'] === 0){
                $Data['COMPANYGROUP'] == null;
            }
            if($Data['COMPANYSUBGROUP'] === 0){
                $Data['COMPANYSUBGROUP'] = null;
            }
            $this->db->trans_begin();
            $result = FALSE;
//            Cek Pengguna Forecast
            $SQL = "SELECT * FROM $this->FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            $DTCek = $this->db->query($SQL, [
                        $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']
                    ])->result();
            if (count($DTCek) > 0) {
                foreach ($DTCek as $value) {
                    if ($value->FLAG_ACTIVE == "0") {
                        throw new Exception("Data Forecast has been Closed !!!");
                    } else {
                        if ($Data["KEYSAVE"] != $value->KEYSAVE) {
                            throw new Exception("Data Forecast is used by " . $value->USERNAME . " !!!");
                        }
                    }
                }
            } else {
                throw new Exception("Data Validation Forecast Not Found !!!");
            }
            //submit data
            if($Data['ACTION'] <> '0'){
                $insertFF = "INSERT INTO FORECAST_FIX SELECT FORECAST_FIX_SEQ.NEXTVAL as ID, FF.DEPARTMENT, FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.WEEK, FF.AMOUNTREQUEST, FF.AMOUNTADJS, FF.ISACTIVE, FF.FCENTRY, FF.FCEDIT, FF.FCIP, FF.LASTUPDATE, FF.PRIORITY, 1 LOCKS, FF.STATE, FF.INVOICEVENDORNO, FF.COMPANYGROUP, FF.COMPANYSUBGROUP FROM FORECAST_FIX_TEMP FF WHERE FF.DEPARTMENT = '".$Data["DEPARTMENT"]."' AND FF.YEAR = '".$Data["YEAR"]."' AND FF.MONTH = '".$Data["MONTH"]."' AND FF.COMPANYGROUP = '".$Data["COMPANYGROUP"]."' AND FF.COMPANYSUBGROUP = '".$Data["COMPANYSUBGROUP"]."'";
                $result1 = $this->db->query($insertFF);
                if($result1){
                    $del = "DELETE FORECAST_FIX_TEMP FF WHERE FF.DEPARTMENT = '".$Data["DEPARTMENT"]."' AND FF.YEAR = '".$Data["YEAR"]."' AND FF.MONTH = '".$Data["MONTH"]."' AND FF.COMPANYGROUP = '".$Data["COMPANYGROUP"]."' AND FF.COMPANYSUBGROUP = '".$Data["COMPANYSUBGROUP"]."'";
                    $this->db->query($del);
                }
                $SQL = "SELECT * FROM $this->FORECASTDANA WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
                $Cek = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'], $Data['DEPARTMENT'], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->num_rows();
                $dt = [
                    "LOCKS" => $Data['FCLOCK'],
                    "ISACTIVE" => $Data['FCSTATUS'],
                    "UPDATED_BY" => $Data['USERNAME'],
                    "UPDATED_LOC" => $Location
                ];
                if ($Data["CASHFLOWTYPE"] == '0') {
                    $dt["AMOUNTREQUEST"] = $Data['REQUESTINTOT'];
                    $dt["AMOUNTADJS"] = $Data['ADJSINTOT'];
                } elseif ($Data["CASHFLOWTYPE"] == '1') {
                    $dt["AMOUNTREQUEST"] = $Data['REQUESTOUTTOT'];
                    $dt["AMOUNTADJS"] = $Data['ADJSOUTTOT'];
                }
                $result2 = $this->db->set('UPDATED_AT', "SYSDATE", false);
                if ($Data['ACTION'] == '1') {
                    $dt["CSUBMIT"] = 1;
                } elseif ($Data['ACTION'] == '2') {
                    $result2 = $result2->set('CSUBMIT', "CREVISI + 1", false);
                }
                if ($Cek > 0) {
                    $result2 = $result2->set($dt)->where([
                                'DEPARTMENT' => $Data['DEPARTMENT'],
                                'YEAR' => $Data['YEAR'],
                                'MONTH' => $Data['MONTH'],
                                "COMPANYGROUP" => $Data["COMPANYGROUP"],
                                "COMPANYSUBGROUP" => $Data["COMPANYSUBGROUP"],
                                'CASHFLOWTYPE' => $Data['CASHFLOWTYPE']
                            ])->update($this->FORECASTDANA);
                } else {
                    $dt["DEPARTMENT"] = $Data['DEPARTMENT'];
                    $dt["YEAR"] = $Data['YEAR'];
                    $dt["MONTH"] = $Data['MONTH'];
                    $dt["COMPANYGROUP"] = $Data["COMPANYGROUP"];
                    $dt["COMPANYSUBGROUP"] = $Data["COMPANYSUBGROUP"];
                    $dt["CASHFLOWTYPE"] = $Data['CASHFLOWTYPE'];
                    $dt["CREATED_BY"] = $Data['USERNAME'];
                    $dt["CREATED_LOC"] = $Location;
                    $result2 = $result2->set('CREATED_AT', "SYSDATE", false)->set($dt)->insert($this->FORECASTDANA);
                }
                if ($result2) {
                
                    $this->db->set('UPDATED_AT', "SYSDATE", false)->set([
                        "DEPARTMENT" => $Data["DEPARTMENT"],
                        "CASHFLOWTYPE" => $Data["CASHFLOWTYPE"],
                        "COMPANYGROUP" => $Data["COMPANYGROUP"],
                        "COMPANYSUBGROUP" => $Data["COMPANYSUBGROUP"],
                        "YEAR" => $Data["YEAR"],
                        "MONTH" => $Data["MONTH"],
                        "USERNAME" => $Data["USERNAME"],
                        "REMARK" => "Close Edit Data Forecast",
                        "UPDATED_BY" => $Data["USERNAME"],
                        "UPDATED_LOC" => $Location
                    ])->insert($this->FORECAST_VALIDATION_HISTORY);
                    $dt = [
                        "USERNAME" => "",
                        "UPDATED_BY" => $Data["USERNAME"],
                        "UPDATED_LOC" => $Location,
                        "FLAG_ACTIVE" => 0,
                        "KEYSAVE" => ""
                    ];
                    $this->db->set('UPDATED_AT', "SYSDATE", false)->set($dt)->where([
                        'DEPARTMENT' => $Data['DEPARTMENT'],
                        'YEAR' => $Data['YEAR'],
                        'MONTH' => $Data['MONTH'],
                        "COMPANYGROUP" => $Data["COMPANYGROUP"],
                        "COMPANYSUBGROUP" => $Data["COMPANYSUBGROUP"],
                        'CASHFLOWTYPE' => $Data['CASHFLOWTYPE']
                    ])->update($this->FORECAST_VALIDATION);
                }
                if($result1){
                    $result = true;
                    $msg    = "Data has been Successfully Saved !!";
                }
            }//end if submit finance
            else{
                    $SQL = "UPDATE FORECAST_FIX_TEMP FF
                           SET FF.AMOUNTREQUEST = 0,
                               FF.AMOUNTADJS = 0,
                               FF.FCEDIT = ?,
                               FF.LASTUPDATE = SYSDATE,
                               FF.FCIP = ?
                         WHERE FF.YEAR = ?
                           AND FF.MONTH = ?
                           AND FF.COMPANYGROUP = ?
                           AND FF.COMPANYSUBGROUP = ? 
                           AND FF.CFTRANSID IN (SELECT CFT.ID
                                                  FROM (SELECT CFT.ID, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCTYPE 
                                                          FROM $this->CF_TRANSACTION CFT
                                                         INNER JOIN $this->CF_TRANSACTION CFPO
                                                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                                                AND CFPO.COMPANY = CFT.COMPANY
                                                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                         UNION ALL
                                                        SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN', 'LOAN', 'LEASING')
                                                         UNION ALL
                                                        SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE = 'INV_AP_SPC'
                                                         UNION ALL 
                                                         SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE = 'INV_AR_SPC') CFT
                                                 INNER JOIN $this->DOCTYPE DT
                                                         ON DT.FCCODE = CFT.DOCTYPE
                                                 INNER JOIN BUSINESSUNIT BS ON BS.ID = CFT.BUSINESSUNIT
                                                 WHERE CFT.DEPARTMENT = ?
                                                   AND DT.CASHFLOWTYPE = ?)";
                $DTU = $this->db->query($SQL, [$Data['USERNAME'], $Location, $Data['YEAR'], $Data['MONTH'], $Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"]]);

                //Insert Or Update Amount Forecast
                $dt = [];
                $SQLC = "SELECT * FROM FORECAST_FIX_TEMP WHERE YEAR = ? AND MONTH = ? AND CFTRANSID = ? AND WEEK = ?  AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
                foreach ($Data['DtForecast'] AS $VALUES) {
                    if ($VALUES["REQUESTW1"] > 0 || $VALUES["ADJSW1"] > 0) {
                        $dt = [
                            "AMOUNTREQUEST" => $VALUES['REQUESTW1'],
                            "AMOUNTADJS" => $VALUES['ADJSW1'],
                            "PRIORITY" => $VALUES['PRIORITYW1'],
                            "DEPARTMENT" => $VALUES['DEPARTMENT'],
                            "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                            "COMPANYGROUP" => $Data['COMPANYGROUP'],
                            "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                            "ISACTIVE" => $Data['FCSTATUS'],
                            "LOCKS" => $Data['FCLOCK'],
                            "FCIP" => $Location,
                            "FCEDIT" => $Data['USERNAME']
                        ];
                        $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W1', $Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->num_rows();
                        if ($CEK1 > 0) {
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                        "YEAR" => $Data['YEAR'],
                                        "MONTH" => $Data['MONTH'],
                                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                        "CFTRANSID" => $VALUES["ID"],
                                        "WEEK" => 'W1'
                                    ])->update($this->FORECAST_FIX_TEMP);
                        } else {
                            $dt["CFTRANSID"] = $VALUES['ID'];
                            $dt["YEAR"] = $Data['YEAR'];
                            $dt["MONTH"] = $Data['MONTH'];
                            $dt["WEEK"] = 'W1';
                            $dt["FCENTRY"] = $Data['USERNAME'];
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX_TEMP);
                        }
                    }
                    if ($VALUES["REQUESTW2"] > 0 || $VALUES["ADJSW2"] > 0) {
                        $dt = [
                            "AMOUNTREQUEST" => $VALUES['REQUESTW2'],
                            "AMOUNTADJS" => $VALUES['ADJSW2'],
                            "PRIORITY" => $VALUES['PRIORITYW2'],
                            "DEPARTMENT" => $VALUES['DEPARTMENT'],
                            "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                            "COMPANYGROUP" => $Data['COMPANYGROUP'],
                            "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                            "ISACTIVE" => $Data['FCSTATUS'],
                            "LOCKS" => $Data['FCLOCK'],
                            "FCIP" => $Location,
                            "FCEDIT" => $Data['USERNAME']
                        ];
                        $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W2',$Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->num_rows();
                        if ($CEK1 > 0) {
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                        "YEAR" => $Data['YEAR'],
                                        "MONTH" => $Data['MONTH'],
                                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                        "CFTRANSID" => $VALUES["ID"],
                                        "WEEK" => 'W2'
                                    ])->update($this->FORECAST_FIX_TEMP);
                        } else {
                            $dt["CFTRANSID"] = $VALUES['ID'];
                            $dt["YEAR"] = $Data['YEAR'];
                            $dt["MONTH"] = $Data['MONTH'];
                            $dt["WEEK"] = 'W2';
                            $dt["FCENTRY"] = $Data['USERNAME'];
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX_TEMP);
                        }
                    }
                    if ($VALUES["REQUESTW3"] > 0 || $VALUES["ADJSW3"] > 0) {
                        $dt = [
                            "AMOUNTREQUEST" => $VALUES['REQUESTW3'],
                            "AMOUNTADJS" => $VALUES['ADJSW3'],
                            "PRIORITY" => $VALUES['PRIORITYW3'],
                            "DEPARTMENT" => $VALUES['DEPARTMENT'],
                            "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                            "COMPANYGROUP" => $Data['COMPANYGROUP'],
                            "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                            "ISACTIVE" => $Data['FCSTATUS'],
                            "LOCKS" => $Data['FCLOCK'],
                            "FCIP" => $Location,
                            "FCEDIT" => $Data['USERNAME']
                        ];
                        $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W3',$Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->num_rows();
                        if ($CEK1 > 0) {
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                        "YEAR" => $Data['YEAR'],
                                        "MONTH" => $Data['MONTH'],
                                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                        "CFTRANSID" => $VALUES["ID"],
                                        "WEEK" => 'W3'
                                    ])->update($this->FORECAST_FIX_TEMP);
                        } else {
                            $dt["CFTRANSID"] = $VALUES['ID'];
                            $dt["YEAR"] = $Data['YEAR'];
                            $dt["MONTH"] = $Data['MONTH'];
                            $dt["WEEK"] = 'W3';
                            $dt["FCENTRY"] = $Data['USERNAME'];
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX_TEMP);
                        }
                    }
                    if ($VALUES["REQUESTW4"] > 0 || $VALUES["ADJSW4"] > 0) {
                        $dt = [
                            "AMOUNTREQUEST" => $VALUES['REQUESTW4'],
                            "AMOUNTADJS" => $VALUES['ADJSW4'],
                            "PRIORITY" => $VALUES['PRIORITYW4'],
                            "DEPARTMENT" => $VALUES['DEPARTMENT'],
                            "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                            "COMPANYGROUP" => $Data['COMPANYGROUP'],
                            "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                            "ISACTIVE" => $Data['FCSTATUS'],
                            "LOCKS" => $Data['FCLOCK'],
                            "FCIP" => $Location,
                            "FCEDIT" => $Data['USERNAME']
                        ];
                        $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W4',$Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']])->num_rows();
                        if ($CEK1 > 0) {
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                        "YEAR" => $Data['YEAR'],
                                        "MONTH" => $Data['MONTH'],
                                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                        "CFTRANSID" => $VALUES["ID"],
                                        "WEEK" => 'W4'
                                    ])->update($this->FORECAST_FIX_TEMP);
                        } else {
                            $dt["CFTRANSID"] = $VALUES['ID'];
                            $dt["YEAR"] = $Data['YEAR'];
                            $dt["MONTH"] = $Data['MONTH'];
                            $dt["WEEK"] = 'W4';
                            $dt["FCENTRY"] = $Data['USERNAME'];
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX_TEMP);
                        }
                    }
                    if ($VALUES["REQUESTW5"] > 0 || $VALUES["ADJSW5"] > 0) {
                        $dt = [
                            "AMOUNTREQUEST" => $VALUES['REQUESTW5'],
                            "AMOUNTADJS" => $VALUES['ADJSW5'],
                            "PRIORITY" => $VALUES['PRIORITYW5'],
                            "DEPARTMENT" => $VALUES['DEPARTMENT'],
                            "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                            "COMPANYGROUP" => $Data['COMPANYGROUP'],
                            "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                            "ISACTIVE" => $Data['FCSTATUS'],
                            "LOCKS" => $Data['FCLOCK'],
                            "FCIP" => $Location,
                            "FCEDIT" => $Data['USERNAME']
                        ];
                        $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W5',$Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']])->num_rows();
                        if ($CEK1 > 0) {
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                        "YEAR" => $Data['YEAR'],
                                        "MONTH" => $Data['MONTH'],
                                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                        "CFTRANSID" => $VALUES["ID"],
                                        "WEEK" => 'W5'
                                    ])->update($this->FORECAST_FIX_TEMP);
                        } else {
                            $dt["CFTRANSID"] = $VALUES['ID'];
                            $dt["YEAR"] = $Data['YEAR'];
                            $dt["MONTH"] = $Data['MONTH'];
                            $dt["WEEK"] = 'W5';
                            $dt["FCENTRY"] = $Data['USERNAME'];
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX_TEMP);
                        }
                    }
                }

                //Delete Amount Forecast 0
                $SQL = "DELETE FROM $this->FORECAST_FIX_TEMP FF
                         WHERE FF.YEAR = ?
                           AND FF.MONTH = ?
                           AND FF.COMPANYGROUP = ?
                           AND FF.COMPANYSUBGROUP = ? 
                           AND FF.CFTRANSID IN (SELECT CFT.ID
                                                  FROM (SELECT CFT.ID, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCTYPE
                                                          FROM $this->CF_TRANSACTION CFT
                                                         INNER JOIN $this->CF_TRANSACTION CFPO
                                                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                                                AND CFPO.COMPANY = CFT.COMPANY
                                                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                         UNION ALL
                                                        SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN', 'LOAN', 'LEASING')
                                                         UNION ALL
                                                        SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE = 'INV_AP_SPC'
                                                         UNION ALL
                                                        SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE = 'INV_AR_SPC'
                                                         ) CFT
                                                 INNER JOIN $this->DOCTYPE DT
                                                         ON DT.FCCODE = CFT.DOCTYPE
                                                 INNER JOIN BUSINESSUNIT BS ON BS.ID = CFT.BUSINESSUNIT
                                                 WHERE CFT.DEPARTMENT = ?
                                                   AND DT.CASHFLOWTYPE = ?)
                           AND FF.AMOUNTREQUEST = 0 
                           AND FF.AMOUNTADJS = 0";
                $DDT = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'],$Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"] ,$Data["DEPARTMENT"], $Data["CASHFLOWTYPE"]]);

                $getTemp = $this->db->get_where('FORECAST_FIX_TEMP',['YEAR' => $Data['YEAR'], 'MONTH' => $Data['MONTH'], 
                            'COMPANYGROUP' => $Data["COMPANYGROUP"], 'COMPANYSUBGROUP' => $Data["COMPANYSUBGROUP"],'DEPARTMENT' => $Data["DEPARTMENT"]])->result();

                if($getTemp){

                    // foreach($getTemp as $row){
                    //     // $YEAR  = $row->YEAR;
                    //     // $MONTH = $row->MONTH;
                    //     // $DEPT  = $row->DEPARTMENT;
                    //     // $COMPANY_GROUP = $row->COMPANYGROUP;
                    //     // $COMPANY_SUBGROUP = $row->COMPANYSUBGROUP;
                    //     $CASHFLOWTYPE     = $Data['CASHFLOWTYPE'];
                    //     $USERNAME         = $Data['USERNAME'];
                    //     // $DEPARTMENT       = $row->DEPARTMENT;
                    //     $CFTRANSID        = $row->CFTRANSID;
                    //     $WEEK             = $row->WEEK;
                    //     $AMOUNTREQUEST    = $row->AMOUNTREQUEST;
                    //     $AMOUNTADJS       = $row->AMOUNTADJS;
                    //     $CFTRANSID        = $row->CFTRANSID;
                        
                    // }

                    $setQuery = "DELETE FROM FORECAST_NEGATIF_AMOUNT FF
                         WHERE FF.YEAR = ?
                           AND FF.MONTH = ?
                           AND FF.COMPANYGROUP = ?
                           AND FF.COMPANYSUBGROUP = ? AND FF.DEPARTMENT = ?";
                    $this->db->query($setQuery, [$Data['YEAR'], $Data['MONTH'],$Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"] ,$Data["DEPARTMENT"]]);

                    $SQL = "DELETE FROM $this->FORECAST_FIX FF
                         WHERE FF.YEAR = ?
                           AND FF.MONTH = ?
                           AND FF.COMPANYGROUP = ?
                           AND FF.COMPANYSUBGROUP = ? 
                           AND FF.CFTRANSID IN (SELECT CFT.ID
                                                  FROM (SELECT CFT.ID, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCTYPE
                                                          FROM $this->CF_TRANSACTION CFT
                                                         INNER JOIN $this->CF_TRANSACTION CFPO
                                                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                                                AND CFPO.COMPANY = CFT.COMPANY
                                                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                         UNION ALL
                                                        SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN', 'LOAN', 'LEASING')
                                                         UNION ALL
                                                        SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE = 'INV_AP_SPC'
                                                         UNION ALL
                                                         SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                          FROM $this->CF_TRANSACTION CFT
                                                         WHERE CFT.DOCTYPE = 'INV_AR_SPC'
                                                         ) CFT
                                                 INNER JOIN $this->DOCTYPE DT
                                                         ON DT.FCCODE = CFT.DOCTYPE
                                                 INNER JOIN BUSINESSUNIT BS ON BS.ID = CFT.BUSINESSUNIT
                                                 WHERE CFT.DEPARTMENT = ?
                                                   AND DT.CASHFLOWTYPE = ?)";
                    $Deletefix = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'],$Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"] ,$Data["DEPARTMENT"], $Data["CASHFLOWTYPE"]]);

                    // $getDocref = "SELECT DOCREF FROM CF_TRANSACTION WHERE ID = '$CFTRANSID'";
                    // $getDocref = $this->db->query($getDocref)->row()->DOCREF;
                    
                    $insertNegatif = $this->negatifAmount($Data['YEAR'],$Data['MONTH'],$Data["DEPARTMENT"]);
                    // var_dump($this->db->last_query());exit; 
                    // if($AMOUNTADJS < $AMOUNTREQUEST){
                    // $getDocref = "SELECT DOCREF FROM CF_TRANSACTION WHERE ID = '$CFTRANSID'";
                    // $getDocref = $this->db->query($getDocref)->row()->DOCREF;
                    
                    // $insertNegatif = $this->negatifAmount($Data['YEAR'],$Data['MONTH'],$Data["DEPARTMENT"],$Data["COMPANYGROUP"],$Data["COMPANYSUBGROUP"],$CASHFLOWTYPE,$USERNAME);
                    // var_dump($this->db->last_query());exit;
                    // }elseif($AMOUNTADJS <= $AMOUNTREQUEST){
                        
                    // }

                    $cekSQL = "SELECT * FROM FORECAST_NEGATIF_AMOUNT WHERE YEAR = ? AND MONTH = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ? AND DEPARTMENT = ?";
                    $cekSQL = $this->db->query($cekSQL,[$Data['YEAR'], $Data['MONTH'],$Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"] ,$Data["DEPARTMENT"]])->num_rows();
                    

                    if ($cekSQL > 0) {
                        $msg = "Ada Data yg Error! Silakan Cek Menu Negatif Amount!";
                    }
                    else{
                        $msg = "Data has been Successfully Saved !!";
                    }

                    $cekVal = "SELECT * FROM $this->FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
                    $DTCekVal = $this->db->query($cekVal, [
                                    $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']
                                ])->num_rows();

                    if ($DTCekVal > 0) {

                            $dt = [
                                'DEPARTMENT' => $Data['DEPARTMENT'],
                                'YEAR' => $Data['YEAR'],
                                'MONTH' => $Data['MONTH'],
                                'CASHFLOWTYPE' => $Data['CASHFLOWTYPE'], 
                                'COMPANYGROUP' => $Data['COMPANYGROUP'],
                                'COMPANYSUBGROUP' => $Data['COMPANYSUBGROUP'],
                                'UPDATED_BY' => $Data['USERNAME'],
                                'UPDATED_LOC' => $Location,
                                'CREATED_LOC' => $Location
                            ];
                            $result = $this->db->set('FLAG_ACTIVE', '0', false)
                                               ->set('KEYSAVE', 'null', false);
                            
                            $result = $result->set($dt)
                                    ->where('DEPARTMENT', $Data['DEPARTMENT'])
                                    ->where('YEAR', $Data['YEAR'])
                                    ->where('MONTH', $Data['MONTH'])
                                    ->where('USERNAME', $Data['USERNAME'])
                                    ->where('CASHFLOWTYPE', $Data['CASHFLOWTYPE'])
                                    ->where('COMPANYGROUP', $Data['COMPANYGROUP'])
                                    ->where('COMPANYSUBGROUP', $Data['COMPANYSUBGROUP'])
                                    ->update($this->FORECAST_VALIDATION);

                    }
                }
                if($result1){
                    $result = TRUE;
                }

            }//end else

            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $msg
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

    public function negatifAmount($YEAR,$MONTH,$DEPT){
        $SQL = "INSERT INTO FORECAST_NEGATIF_AMOUNT 
                    SELECT *
                    FROM (SELECT '',neg.department,
               co.companycode,
               neg.docref,
               neg.year,
               neg.month,
               neg.amountrequest,
               neg.amountoutstanding,
               CASE
                  WHEN neg.isactive = 0
                  THEN
                     neg.amountoutstanding - neg.amountrequest
                  ELSE
                     neg.amountoutstanding - neg.amountadjs
               END
                  AS over,
               neg.companygroup,
               neg.companysubgroup,
               neg.amountadjs
          FROM (  SELECT ff.department,
                         ff.company,
                         ff.docref,
                         ff.year,
                         ff.month,
                         MAX (ff.amountrequest) amountrequest,
                         MAX (ff.amountadjs) amountadjs,
                         ff.companygroup,
                         ff.companysubgroup,
                         MAX (at.amountoutstanding) AS amountoutstanding,
                         ff.isactive
                    FROM (SELECT a.cftransid,
                                 a.department,
                                 b.company,
                                 c.doctype,
                                 b.docref,
                                 a.year,
                                 a.month,
                                 a.companygroup,
                                 a.companysubgroup,
                                 a.isactive,
                                 SUM (
                                    a.amountrequest)
                                 OVER (
                                    PARTITION BY    b.company
                                                 || c.doctype
                                                 || b.docref)
                                    AS amountrequest,
                                 SUM (
                                    a.amountadjs)
                                 OVER (
                                    PARTITION BY    b.company
                                                 || c.doctype
                                                 || b.docref)
                                    AS amountadjs
                            FROM (SELECT cftransid,
                                            department,
                                            year,
                                            month,
                                            companygroup,
                                            companysubgroup,
                                            amountrequest,
                                            amountadjs,
                                            isactive
                                       FROM forecast_fix_temp
                                      WHERE department = '".$DEPT."') a
                                    INNER JOIN
                                    (SELECT id, company, docref
                                       FROM cf_transaction) b
                                       ON (a.cftransid = b.id)
                                    INNER JOIN
                                    (SELECT company, docnumber, doctype
                                       FROM cf_transaction
                                      WHERE department = '".$DEPT."') c
                                    ON (    b.company = c.company
                                        AND b.docref = c.docnumber)
                           WHERE a.year = '$YEAR' AND a.month = '$MONTH') ff
                         LEFT JOIN
                         (SELECT DISTINCT
                                 id,
                                 department,
                                 company,
                                 doctype,
                                 docnumber_source,
                                 currency,
                                 NVL (
                                      (  (total_debet + total_debet2)
                                       - (total_credit + total_credit2))
                                    * -1,
                                    0),
                                 NVL (total_amountadjs, 0),
                                 CASE
                                    WHEN NVL (
                                              (  (total_debet + total_debet2)
                                               - (total_credit + total_credit2))
                                            * -1,
                                            0) > NVL (total_amountadjs, 0)
                                    THEN
                                       (  amount_source
                                        - NVL (total_inv, 0)
                                        - NVL (
                                               (  (total_debet + total_debet2)
                                                - (total_credit + total_credit2))
                                             * -1,
                                             0))
                                    ELSE
                                       (  amount_source
                                        - NVL (total_amountadjs, 0))
                                 END
                                    amountoutstanding
                            FROM (SELECT po.id,
                                         po.department,
                                         po.doctype,
                                         po.company,
                                         po.docnumber AS docnumber_source,
                                         inv.currency,
                                         MAX (
                                            po.amount_include_vat)
                                         OVER (
                                            PARTITION BY    po.company
                                                         || po.docnumber)
                                            AS amount_source,
                                         MAX (
                                            inv.amount_include_vat)
                                         OVER (
                                            PARTITION BY    po.company
                                                         || inv.docref)
                                            AS total_inv,
                                         SUM (
                                            fc.amountadjs)
                                         OVER (
                                            PARTITION BY    po.company
                                                         || po.docnumber)
                                            AS total_amountadjs,
                                         SUM (
                                            payment.debet)
                                         OVER (
                                            PARTITION BY    po.company
                                                         || po.docnumber)
                                            AS total_debet,
                                         SUM (
                                            payment.credit)
                                         OVER (
                                            PARTITION BY    po.company
                                                         || po.docnumber)
                                            AS total_credit,
                                         SUM (
                                            payment2.debet)
                                         OVER (
                                            PARTITION BY    po.company
                                                         || po.docnumber)
                                            AS total_debet2,
                                         SUM (
                                            payment2.credit)
                                         OVER (
                                            PARTITION BY    po.company
                                                         || po.docnumber)
                                            AS total_credit2
                                    FROM (SELECT id,
                                                 company,
                                                 department,
                                                 docnumber,
                                                 doctype,
                                                 amount_include_vat
                                            FROM cf_transaction
                                           WHERE     doctype NOT IN
                                                        ('INV', 'INV_AR')
                                                 AND department = '".$DEPT."'
                                         ) po
                                         LEFT JOIN
                                         (SELECT id,
                                                 company,
                                                 docnumber,
                                                 docref,
                                                 currency,
                                                 department,
                                                 SUM (
                                                    amount_include_vat)
                                                 OVER (
                                                    PARTITION BY    company
                                                                 || docref)
                                                    AS amount_include_vat,
                                                 SUM (
                                                    amount_pph)
                                                 OVER (
                                                    PARTITION BY    company
                                                                 || docref)
                                                    AS amount_pph
                                            FROM cf_transaction
                                           WHERE doctype IN ('INV', 'INV_AR') and department = '".$DEPT."'
                                         ) inv
                                            ON (    po.company = inv.company
                                                AND po.docnumber = inv.docref)
                                         LEFT JOIN
                                         (  SELECT cftransid,
                                                   SUM (amountrequest)
                                                      AS amountrequest,
                                                   SUM (amountadjs) AS amountadjs
                                              FROM forecast_fix
                                             WHERE department = '".$DEPT."'
                                          GROUP BY cftransid) fc
                                            ON (   fc.cftransid = po.id
                                                OR fc.cftransid = inv.id)
                                         LEFT JOIN
                                         (  SELECT payment.cftransid,
                                                   payment.voucherno,
                                                   payment.bankcode,
                                                   MAX (payment.remark) AS remark,
                                                   NVL (SUM (payment.amount), 0)
                                                      AS debet,
                                                   0 AS credit
                                              FROM payment
                                             WHERE payment.cashflowtype = '0'
                                          GROUP BY payment.voucherno,
                                                   payment.bankcode,
                                                   payment.cftransid
                                          UNION ALL
                                            SELECT payment.cftransid,
                                                   payment.voucherno,
                                                   payment.bankcode,
                                                   MAX (payment.remark) AS remark,
                                                   0 AS debet,
                                                   NVL (SUM (payment.amount), 0)
                                                      AS credit
                                              FROM payment
                                             WHERE payment.cashflowtype = '1'
                                          GROUP BY payment.voucherno,
                                                   payment.bankcode,
                                                   payment.cftransid) payment
                                            ON (payment.cftransid = inv.id)
                                         LEFT JOIN
                                         (  SELECT payment.cftransid,
                                                   payment.voucherno,
                                                   payment.bankcode,
                                                   MAX (payment.remark) AS remark,
                                                   NVL (SUM (payment.amount), 0)
                                                      AS debet,
                                                   0 AS credit
                                              FROM payment
                                             WHERE payment.cashflowtype = '0'
                                          GROUP BY payment.voucherno,
                                                   payment.bankcode,
                                                   payment.cftransid
                                          UNION ALL
                                            SELECT payment.cftransid,
                                                   payment.voucherno,
                                                   payment.bankcode,
                                                   MAX (payment.remark) AS remark,
                                                   0 AS debet,
                                                   NVL (SUM (payment.amount), 0)
                                                      AS credit
                                              FROM payment
                                             WHERE payment.cashflowtype = '1'
                                          GROUP BY payment.voucherno,
                                                   payment.bankcode,
                                                   payment.cftransid) payment2
                                            ON (payment.cftransid = po.id)
                                 )
                           WHERE department = '".$DEPT."') at
                            ON (    ff.docref = at.docnumber_source
                                AND at.company = ff.company
                                AND ff.doctype = at.doctype)
                GROUP BY ff.department,
                         ff.company,
                         ff.year,
                         ff.month,
                         ff.docref,
                         ff.companygroup,
                         ff.companysubgroup,
                         ff.isactive) neg
               INNER JOIN (SELECT id, companycode FROM company) co
                  ON (co.id = neg.company))
            WHERE department = '".$DEPT."' AND over < 0";
        $insert = $this->db->query($SQL);
        $this->db->close();
        return $insert;
    }

    public function showNegatifAmount($param) {
        $SUBGROUP = $param['COMPANYSUBGROUP'];
        $GROUP    = $param['COMPANYGROUP'];
        $DEPARTMENT   = $param['DEPARTMENT'];
        $MONTH        = $param['MONTH'];
        $YEAR         = $param['YEAR'];

        $WHERE = "WHERE FN.MONTH = '$MONTH' AND FN.YEAR = '$YEAR' ";
        if($DEPARTMENT != ''){
            $WHERE .= "AND FN.DEPARTMENT = '$DEPARTMENT' ";
        }
        if($GROUP != ''){
            $WHERE .= "AND FN.COMPANYGROUP = '$GROUP' ";
        }
        if($SUBGROUP != ''){
            $WHERE .= "AND FN.COMPANYSUBGROUP = '$SUBGROUP' ";
        }

        $SQL = "SELECT FN.*,C.COMPANYNAME FROM FORECAST_NEGATIF_AMOUNT FN LEFT JOIN COMPANY C ON C.ID = FN.COMPANY ".$WHERE;
        $return = $this->db->query($SQL)->result();
        // var_dump($this->db->last_query());exit;
        $this->db->close();
        return $return;
    }

    public function DtOutstanding($param) {
        $SQL = "SELECT CFT.*, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
					   CASE WHEN NVL(PAYMENT.AMOUNT, 0) >= NVL(FF.AMOUNTINV, 0) THEN
					   (CFT.AMOUNT_INCLUDE_VAT - NVL(PAYMENT.AMOUNT, 0))
                        ELSE
					   (CFT.AMOUNT_INCLUDE_VAT - NVL(FF.AMOUNTINV, 0))
                        END AMOUNTOUTSTANDING,
					   DT.CASHFLOWTYPE, 0 AS FLAG, NVL(CR.RATE, 1) AS RATE
                  FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR') AND CFT.company || cft.docref || TO_CHAR (cft.DOCDATE, 'yyyy') NOT IN
                        (SELECT DISTINCT
                                CFT.COMPANY || CFT.DOCREF || TO_CHAR (CFT.DOCDATE, 'yyyy')
                                   DOCDATE
                           FROM FORECAST_FIX FF INNER JOIN CF_TRANSACTION CFT ON CFT.ID = FF.CFTRANSID
                          WHERE CFT.DOCNUMBER LIKE '%TMPINV%')
                         UNION ALL
                        SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN', 'LOAN')
						 UNION ALL 
						 SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AP_SPC'
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AR_SPC'
						 ) CFT
                 INNER JOIN $this->DOCTYPE DT
                         ON DT.FCCODE = CFT.DOCTYPE
                  LEFT JOIN (SELECT CFTRANSID, SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                               FROM $this->FORECAST_FIX
                              GROUP BY CFTRANSID) FF
                         ON FF.CFTRANSID = CFT.ID
                  LEFT JOIN (SELECT DISTINCT CFTRANSID
                               FROM $this->FORECAST_FIX 
                              WHERE YEAR = ?
                                AND MONTH = ?) DTFF
                         ON DTFF.CFTRANSID = CFT.ID
				  LEFT JOIN PAYMENT ON CFT.ID = PAYMENT.CFTRANSID
                 INNER JOIN $this->COMPANY C
                         ON C.ID = CFT.COMPANY
                 INNER JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = CFT.DEPARTMENT 
                  LEFT JOIN CURS CR
                         ON CR.CURSCODE = CFT.CURRENCY
                        AND CR.CURSYEAR = ?
                        AND CR.CURSMONTH = ?";
        if ($param["POUTSTAND"] <> '') {
            $SQL .= " LEFT JOIN (SELECT ID FROM $this->CF_TRANSACTION 
                                 WHERE ID IN (" . $param["POUTSTAND"] . ")) DTE
                             ON DTE.ID = CFT.ID";
        }
        $SQL .= " WHERE (CFT.AMOUNT_INCLUDE_VAT - NVL(FF.AMOUNTINV, 0)) > 0 AND (CFT.AMOUNT_INCLUDE_VAT - NVL (PAYMENT.AMOUNT, 0)) > 0 
                    AND DTFF.CFTRANSID IS NULL
                    AND CFT.DEPARTMENT = ?
                    AND DT.CASHFLOWTYPE = ? ";
        $subgroup = $param['COMPANYSUBGROUP'];
        $group    = $param['COMPANYGROUP'];

        if($group != null && $group != ''){
          $SQL .= " AND BS.COMPANYGROUP = '$group' ";
        }
        if($subgroup != null && $subgroup != ''){
           $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
        }                    
        if ($param["POUTSTAND"] <> '') {
            $SQL .= " AND DTE.ID IS NULL";
        }
        $SQL .= " ORDER BY CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCNUMBER, CFT.INVOICEVENDORNO";
        $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["DEPARTMENT"], $param["CASHFLOWTYPE"]])->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function DtOutstandingNew($param) {
        $SQL = "SELECT CFT.*, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                       CASE WHEN NVL(PAYMENT.AMOUNT, 0) >= NVL(FF.AMOUNTINV, 0) THEN
                       (CFT.AMOUNT_INCLUDE_VAT - NVL(PAYMENT.AMOUNT, 0))
                        ELSE
                       (CFT.AMOUNT_INCLUDE_VAT - NVL(FF.AMOUNTINV, 0))
                        END AMOUNTOUTSTANDING,
                       DT.CASHFLOWTYPE, 0 AS FLAG, NVL(CR.RATE, 1) AS RATE
                  FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR') /*AND CFT.company || cft.docref || TO_CHAR (cft.DOCDATE, 'yyyy') NOT IN
                        (SELECT DISTINCT
                                CFT.COMPANY || CFT.DOCREF || TO_CHAR (CFT.DOCDATE, 'yyyy')
                                   DOCDATE
                           FROM FORECAST_FIX FF INNER JOIN CF_TRANSACTION CFT ON CFT.ID = FF.CFTRANSID
                          WHERE CFT.DOCNUMBER LIKE '%TMPINV%' AND CFT.DEPARTMENT = paramDEPARTMENT) */ AND CFT.ID IN (SELECT ID FROM CF_TRANSACTION WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR') MINUS SELECT CFTRANSID FROM FORECAST_FIX_TEMP
                          MINUS SELECT CFTRANSID FROM FORECAST_FIX)
                         UNION ALL
                        SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN', 'LOAN')
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AP_SPC'
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AR_SPC'
                         ) CFT
                 INNER JOIN $this->DOCTYPE DT
                         ON DT.FCCODE = CFT.DOCTYPE
                  LEFT JOIN (SELECT CFTRANSID, SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                               FROM $this->FORECAST_FIX WHERE DEPARTMENT = '".$param['DEPARTMENT']."'
                              GROUP BY CFTRANSID) FF
                         ON FF.CFTRANSID = CFT.ID
                  LEFT JOIN (SELECT DISTINCT CFTRANSID
                               FROM $this->FORECAST_FIX 
                              WHERE YEAR = ?
                                AND MONTH = ? AND DEPARTMENT = '".$param['DEPARTMENT']."') DTFF
                         ON DTFF.CFTRANSID = CFT.ID
                  LEFT JOIN PAYMENT ON CFT.ID = PAYMENT.CFTRANSID
                 INNER JOIN $this->COMPANY C
                         ON C.ID = CFT.COMPANY
                 INNER JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = CFT.DEPARTMENT 
                  LEFT JOIN CURS CR
                         ON CR.CURSCODE = CFT.CURRENCY
                        AND CR.CURSYEAR = ?
                        AND CR.CURSMONTH = ?";
        if ($param["POUTSTAND"] <> '') {
            $SQL .= " LEFT JOIN (SELECT ID FROM $this->CF_TRANSACTION 
                                 WHERE ID IN (" . $param["POUTSTAND"] . ")) DTE
                             ON DTE.ID = CFT.ID";
        }
        $SQL .= " WHERE (CFT.AMOUNT_INCLUDE_VAT - NVL(FF.AMOUNTINV, 0)) <> 0 AND (CFT.AMOUNT_INCLUDE_VAT - NVL (PAYMENT.AMOUNT, 0)) <> 0 
                    AND DTFF.CFTRANSID IS NULL
                    AND CFT.DEPARTMENT = ?
                    AND DT.CASHFLOWTYPE = ? ";
        $subgroup = $param['COMPANYSUBGROUP'];
        $group    = $param['COMPANYGROUP'];

        if($group != null && $group != ''){
          $SQL .= " AND BS.COMPANYGROUP = '$group' ";
        }
        if($subgroup != null && $subgroup != ''){
           $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
        }                    
        if ($param["POUTSTAND"] <> '') {
            $SQL .= " AND DTE.ID IS NULL";
        }
        $SQL .= " ORDER BY CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCNUMBER, CFT.INVOICEVENDORNO";
        $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["DEPARTMENT"], $param["CASHFLOWTYPE"]])->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function DtOutstandingNoInvNew($param) {
        $SQL = "select 
            ori.*,
            co.companycode,
            supplier.fcname as vendor, 
            businessunit.fccode as businessunit
            from (
                select 
                distinct
                id,
                department,
                company,
                businessunit as businessid,
                vendor as vendorid,
                doctype,
                duedate,
                docnumber_source,
                '' invno,
                currency,
                '0' as flag,
                case
                when isactive = 0 then (
                case
                    when ((nvl(total_debet,0)+nvl(total_debet2,0)) - ((nvl(total_credit,0)+nvl(total_credit2,0))*-1)) > 
                           (nvl(total_amountadjs,0)+nvl(total_amountrequest2,0))
                    then (nvl(amount_source,0) - nvl (total_inv, 0) - ((nvl(total_debet,0)+nvl(total_debet2,0)) - ((nvl(total_credit,0)+nvl(total_credit2,0))*-1)))       
                    else (nvl(amount_source,0)-(nvl(total_amountadjs,0)+nvl(total_amountrequest2,0)))
                    end)
                else (
                case
                    when ((nvl(total_debet,0)+nvl(total_debet2,0)) - ((nvl(total_credit,0)+nvl(total_credit2,0))*-1)) > 
                           (nvl(total_amountadjs,0)+nvl(total_amountadjs2,0))
                    then (nvl(amount_source,0) - nvl (total_inv, 0) - ((nvl(total_debet,0)+nvl(total_debet2,0)) - ((nvl(total_credit,0)+nvl(total_credit2,0))*-1)))       
                    else (nvl(amount_source,0)-(nvl(total_amountadjs,0)+nvl(total_amountadjs2,0)))               
                    end)
                end as amountoutstanding
                from (
                    select 
                    po.id,
                    po.department,
                    po.company,
                    po.doctype,
                    po.duedate,
                    po.docnumber as docnumber_source,
                    po.currency,
                    po.businessunit,
                    po.vendor,
                    max(fc2.isactive) over (partition by po.company||po.docnumber) as isactive,
                    max(po.amount_include_vat) over (partition by po.company||po.docnumber) as amount_source,
                    max(inv.amount_include_vat) over (partition by po.company||inv.docref) as total_inv,
                    sum(fc2.amountrequest) over (partition by po.company||po.docnumber) as total_amountrequest2,                                                  
                    sum(fc.amountadjs) over (partition by po.company||po.docnumber) as total_amountadjs,
                    sum(fc2.amountadjs) over (partition by po.company||po.docnumber) as total_amountadjs2,                              
                    sum(payment.debet) over (partition by po.company||po.docnumber) as total_debet,
                    sum(payment2.debet) over (partition by po.company||po.docnumber) as total_debet2,                          
                    sum(payment.credit) over (partition by po.company||po.docnumber) as total_credit,
                    sum(payment2.credit) over (partition by po.company||po.docnumber) as total_credit2                          
                    from (
                        select 
                        id,
                        company,
                        department,
                        businessunit,
                        docnumber,
                        doctype,
                        duedate,
                        currency,
                        vendor,
                        amount_include_vat
                        from cf_transaction
                        where doctype <> 'INV'
                        and department = '".$param['DEPARTMENT']."'
                    ) po
                    left join (
                        select 
                        id,
                        company,
                        docnumber,
                        docref,
                        doctype,
                        vendor,
                        sum(amount_include_vat) over (partition by company||docref) as amount_include_vat,
                        sum(amount_pph) over (partition by company||docref) as amount_pph
                        from cf_transaction
                        where doctype in ('INV', 'INV_AR')
                        and docref in (select docnumber from cf_transaction where department = '".$param['DEPARTMENT']."' )                                   
                    ) inv
                    on (    
                    po.company = inv.company and 
                    po.docnumber = inv.docref)
                    left join (  
                        select 
                        cftransid,
                        sum(amountrequest) as amountrequest,
                        sum(amountadjs) as amountadjs
                        from forecast_fix
                        where department = '".$param['DEPARTMENT']."'
                        group by 
                        cftransid
                    ) fc
                    on (fc.cftransid = po.id or fc.cftransid = inv.id)
                    left join (  
                        select 
                        cftransid,
                        isactive,
                        sum(amountrequest) as amountrequest,
                        sum(amountadjs) as amountadjs
                        from forecast_fix_temp
                        where department = '".$param['DEPARTMENT']."'
                        and year = '".$param['YEAR']."'
                        and month = '".$param['MONTH']."'
                        group by 
                        cftransid,
                        isactive
                    ) fc2
                    on (fc2.cftransid = po.id or fc2.cftransid = inv.id)                          
                    left join (  
                        select 
                        payment.cftransid,
                        nvl(sum(payment.amount),0) as debet,
                        0 as credit
                        from payment
                        where payment.cashflowtype = '0'
                        group by 
                        payment.cftransid
                        union all
                        select payment.cftransid,
                        0 as debet,
                        nvl(sum(payment.amount),0) as credit
                        from payment
                        where payment.cashflowtype = '1'
                        group by payment.cftransid
                    ) payment
                    on (payment.cftransid = inv.id)
                    left join (  
                        select 
                        payment.cftransid,
                        nvl(sum(payment.amount),0) as debet,
                        0 as credit
                        from payment
                        where payment.cashflowtype = '0'
                        group by payment.cftransid
                        union all
                        select payment.cftransid,
                        0 as debet,
                        nvl(sum(payment.amount),0) as credit
                        from payment
                        where payment.cashflowtype = '1'
                        group by 
                        payment.cftransid
                    ) payment2
                    on (payment.cftransid = po.id)                              
                )
                where department = '".$param['DEPARTMENT']."'
            ) ori
            inner join (
                select id,fccode from businessunit
            ) businessunit on ori.businessid = businessunit.id
            inner join (
                select id,fcname from supplier
            ) supplier on ori.vendorid = supplier.id
            inner join (
                select id, companycode, companyname from company
            ) co on (ori.company = co.id)       
            where ori.department = '".$param['DEPARTMENT']."'
            and ori.amountoutstanding <> 0";
        // var_dump($SQL);exit();
        $result = $this->db->query($SQL)->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        
        return $result;
    }

//Region Addition For Forecast Without Invoice
    public function DtOutstandingNoInv($param) {
        $SQL = "SELECT
				ID, COMPANY, BUSINESSUNIT, DOCTYPE, DOCNUMBER, VENDOR, DUEDATE, INVNO, CURRENCY, 
				MAX(AMOUNT) AMOUNT, MAX(FLAG)FLAG
				FROM
				(SELECT DISTINCT cfa.ID, COMPANY.COMPANYCODE COMPANY, BUSINESSUNIT.FCCODE BUSINESSUNIT, 
				cfa.DOCTYPE, hdr.DOCNUMBER, SUPPLIER.FCNAME VENDOR, cfa.DUEDATE, hdr.INVNO, cfa.CURRENCY, 
				(cfa.AMOUNT_INCLUDE_VAT - NVL(cfa.AMOUNT_PPH, 0)) AMOUNT, 0 AS FLAG from(
					select cf.DEPARTMENT, cf.DOCNUMBER, cfb.DOCNUMBER INVNO from CF_TRANSACTION cf 
					left join CF_TRANSACTION cfb on cf.docnumber = cfb.docref
					where 
					cf.Department = ? and cf.DOCTYPE IN('PO','SPO','SPK','STO','SO')
				) hdr
				inner join CF_TRANSACTION cfa on hdr.DOCNUMBER = cfa.DOCNUMBER and hdr.DEPARTMENT = cfa.DEPARTMENT
				inner join COMPANY on cfa.COMPANY = COMPANY.ID
				inner join BUSINESSUNIT on cfa.BUSINESSUNIT = BUSINESSUNIT.ID
				inner join SUPPLIER on cfa.VENDOR = SUPPLIER.id
				and cfa.Department = ?
				where hdr.INVNO is null
				union all
				SELECT cfa.ID,
			   COMPANY.COMPANYCODE COMPANY,
			   BUSINESSUNIT.FCCODE BUSINESSUNIT,
			   cfa.DOCTYPE,
			   hdr.DOCNUMBER,
			   SUPPLIER.FCNAME VENDOR,
			   cfa.DUEDATE,
			   hdr.INVNO,
			   cfa.CURRENCY,
			   (nvl(hdr.amountH,0)- NVL (cfa.AMOUNT_PPH, 0) - nvl(hdr.amountD,0)) AMOUNT,
			   0 AS FLAG
		  FROM (SELECT cf.DEPARTMENT, cf.DOCNUMBER, '' INVNO, cf.AMOUNT_INCLUDE_VAT amountH, 
				  (select nvl(sum(AMOUNT_INCLUDE_VAT),0) from CF_TRANSACTION where docref = cf.DOCNUMBER ) amountD
				  FROM CF_TRANSACTION cf 
				  WHERE
				 cf.Department = ? AND cf.DOCTYPE IN ('PO', 'SPO', 'SPK','STO')
				) hdr
			   INNER JOIN CF_TRANSACTION cfa ON hdr.DOCNUMBER = cfa.DOCNUMBER AND hdr.DEPARTMENT = cfa.DEPARTMENT
			   INNER JOIN COMPANY ON cfa.COMPANY = COMPANY.ID
			   INNER JOIN BUSINESSUNIT ON cfa.BUSINESSUNIT = BUSINESSUNIT.ID
			   INNER JOIN SUPPLIER ON cfa.VENDOR = SUPPLIER.id AND cfa.Department = ?
			   where 
			   (nvl(hdr.amountH,0)- NVL (cfa.AMOUNT_PPH, 0) - nvl(hdr.amountD,0)) > 1)
			   GROUP BY ID, COMPANY, BUSINESSUNIT, DOCTYPE, DOCNUMBER, VENDOR, DUEDATE, INVNO, CURRENCY";
		// var_dump($SQL);exit();
		$result = $this->db->query($SQL, [$param["DEPARTMENT"],$param["DEPARTMENT"],$param["DEPARTMENT"],$param["DEPARTMENT"]])->result();
        // var_dump($this->db->last_query());exit();
		$this->db->close();
		
        return $result;
    }
	
	public function AddDummyInv($param){
		 try {
            $this->db->trans_begin();
            $result = FALSE;
			
			$SQL =  "INSERT INTO CF_TRANSACTION
										select
										LOWER (SYS_GUID ()) ID,
										CFT.EXTSYS,
										CFT.COMPANY,
										CFT.BUSINESSUNIT,
										CFT.DEPARTMENT,
										'INV' DOCTYPE,
										'TMPINV-'|| CFT.DOCNUMBER DOCNUMBER,
										To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy') DOCDATE,
										CFT.DOCNUMBER DOCREF,
										CFT.VENDOR,
										CFT.TRANS_LOC,
										To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+30 BASELINEDATE,
										CFT.PAYTERM,
										To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+30 DUEDATE,
										'Dummy Invoice - by System !' REMARK,
										CFT.AMOUNT_INCLUDE_VAT - nvl(CFT.AMOUNT_PPH,0) - 
										(select nvl(sum(AMOUNT_INCLUDE_VAT),0) from CF_TRANSACTION where DOCREF = CFT.DOCNUMBER) AMNTINV,
										0 AMOUNT_PPH,
										CFT.ISACTIVE,
										CFT.FCENTRY,
										CFT.FCEDIT,
										CFT.FCIP,
										sysdate,
										CFT.LASTTIME,
										CFT.UPLOAD_REF,
										CFT.VAT,
										CFT.FAKTUR_PAJAK,
										CFT.TOTAL_BAYAR,
										CFT.CURRENCY,
										CFT.AMOUNT_PPN,
										CFT.ISADENDUM,
										CFT.RATE,
										CFT.INVOICEVENDORNO,
                    '' ITEM_STO
										from 
										CF_TRANSACTION CFT
										WHERE CFT.ID = ?";
			$result = $this->db->query($SQL, [$param['ID']]);
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Create Dummy Inv Failed !!');
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

    public function AddDummyInvNew($param){
         $AMOUNT = $param['AMOUNT'];
         try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $SQL =  "INSERT INTO CF_TRANSACTION
                                        select
                                        LOWER (SYS_GUID ()) ID,
                                        CFT.EXTSYS,
                                        CFT.COMPANY,
                                        CFT.BUSINESSUNIT,
                                        CFT.DEPARTMENT,
                                        CASE WHEN DOCTYPE = 'SO' THEN 'INV_AR' ELSE 'INV' END AS DOCTYPE,
                                        -- 'INV' DOCTYPE,
                                        'TMPINV-'|| CFT.DOCNUMBER DOCNUMBER,
                                        To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy') DOCDATE,
                                        CFT.DOCNUMBER DOCREF,
                                        CFT.VENDOR,
                                        CFT.TRANS_LOC,
                                        To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+30 BASELINEDATE,
                                        CFT.PAYTERM,
                                        To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+30 DUEDATE,
                                        'Dummy Invoice - by System !' REMARK,
                                        $AMOUNT AMNTINV,
                                        0 AMOUNT_PPH,
                                        CFT.ISACTIVE,
                                        CFT.FCENTRY,
                                        CFT.FCEDIT,
                                        CFT.FCIP,
                                        sysdate,
                                        CFT.LASTTIME,
                                        CFT.UPLOAD_REF,
                                        CFT.VAT,
                                        CFT.FAKTUR_PAJAK,
                                        ($AMOUNT - 0) AS TOTAL_BAYAR,
                                        CFT.CURRENCY,
                                        CASE WHEN CFT.VAT > 0 THEN ROUND(((($AMOUNT/((100+CFT.VAT)/100))*CFT.VAT)/100 ),0) ELSE 0 END AS TOTAL_PPN,
                                        CFT.ISADENDUM,
                                        CFT.RATE,
                                        CFT.INVOICEVENDORNO,
                                        '' ITEM_STO,
                                        CFT.PO_B2BSELLER,
                                        CFT.NEGO_NO
                                        from 
                                        CF_TRANSACTION CFT
                                        WHERE CFT.ID = ?";
            $result = $this->db->query($SQL, [$param['ID']]);
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Create Dummy Inv Failed !!');
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

    public function ShowDtForecastNew($param) {
        $subgroup = $param['COMPANYSUBGROUP'];
        $group    = $param['COMPANYGROUP'];
        $param["CASHFLOWTYPE"] = "%" . $param["CASHFLOWTYPE"] . "%";
        $SQL = "SELECT DISTINCT CFT.*, DT.CASHFLOWTYPE, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                       FF.REQUESTW1, FF.REQUESTW2, FF.REQUESTW3, FF.REQUESTW4, FF.REQUESTW5, ADJSW1, ADJSW2, ADJSW3, ADJSW4, ADJSW5, 
                       FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, FF.ISACTIVE, FF.LOCKS, 
            CASE
            WHEN NVL (FO.AMOUNTINV, 0) > 0 THEN 
            (CFT.AMOUNT_INCLUDE_VAT - NVL (FO.AMOUNTINV, 0) + NVL(FF.AMOUNTINV, 0))
            ELSE
            (CFT.AMOUNT_INCLUDE_VAT - NVL (FO.AMOUNTINV, 0) ) end AS AMOUNTOUTSTANDING,NVL(CR.RATE, 1) AS RATE
                  FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                         UNION ALL
                        SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN','LEASING','LOAN')
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AP_SPC'
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AR_SPC') CFT
                 INNER JOIN (SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5
                               FROM $this->FORECAST_FIX FF
                              WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                              GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS
                              UNION ALL
                              SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5
                               FROM $this->FORECAST_FIX_TEMP FF
                              WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                              GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS) FF
                         ON FF.CFTRANSID = CFT.ID
                  LEFT JOIN (SELECT CFTRANSID, 
                            SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                               FROM $this->FORECAST_FIX
                              GROUP BY CFTRANSID
                              /*UNION ALL
                              SELECT CFTRANSID,
                                SUM (DECODE (ISACTIVE,  0, AMOUNTREQUEST,  NULL, 0,  AMOUNTADJS)) AS AMOUNTINV
                                  FROM FORECAST_FIX_TEMP
                              GROUP BY CFTRANSID*/) FO
                         ON FO.CFTRANSID = CFT.ID
                 INNER JOIN $this->DOCTYPE DT
                         ON DT.FCCODE = CFT.DOCTYPE
                 INNER JOIN $this->COMPANY C
                         ON C.ID = CFT.COMPANY
                 INNER JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = CFT.DEPARTMENT 
                  LEFT JOIN CURS CR
                         ON CR.CURSCODE = CFT.CURRENCY
                        AND CR.CURSYEAR = ?
                        AND CR.CURSMONTH = ?
                 WHERE DT.CASHFLOWTYPE LIKE ?";

        $SQL1 = "SELECT FD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, SUM(FD.AMOUNTREQUEST) AS AMOUNTREQUEST, SUM(FD.AMOUNTADJS) AS AMOUNTADJS,
                        SUM(FD.AMOUNTREVISI) AS AMOUNTREVISI, SUM(FD.AMOUNTAPPROVE) AS AMOUNTAPPROVE, FD.LOCKS, FD.ISACTIVE
                   FROM $this->FORECASTDANA FD
                  INNER JOIN $this->USER_DEPART UD
                          ON UD.FCCODE = ?
                         AND UD.DEPARTMENT = FD.DEPARTMENT
                  WHERE FD.YEAR = ?
                    AND FD.MONTH = ? 
                    AND FD.CASHFLOWTYPE LIKE ?";
        $ARR1 = [$param['YEAR'], $param['MONTH'],$param['YEAR'], $param['MONTH'], $param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["CASHFLOWTYPE"]];
        $ARR2 = [$param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["CASHFLOWTYPE"]];
        if ($param["DEPARTMENT"] != NULL && $param["DEPARTMENT"] != '') {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            $SQL1 .= " AND FD.DEPARTMENT = ?";
            array_push($ARR1, $param["DEPARTMENT"]);
            array_push($ARR2, $param["DEPARTMENT"]);
        }
        if($group != null || $group != '' || $group != 0){
          $SQL .= " AND BS.COMPANYGROUP = '$group'";
          $SQL1 .= " AND FD.COMPANYGROUP = '$group'";
        }
        if($subgroup != null || $subgroup != '' || $subgroup != 0){
           $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
           $SQL1 .= " AND FD.COMPANYSUBGROUP = '$subgroup'";
        }
        if ($param["USERACCESS"] == "100003") {
            $SQL .= " AND FF.LOCKS = 1";
            $SQL1 .= " AND FD.LOCKS = 1";
        }
        $SQL .= " ORDER BY DT.CASHFLOWTYPE, CFT.DEPARTMENT, FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, 
                           CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER, CFT.INVOICEVENDORNO";
        $SQL1 .= " GROUP BY FD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, FD.LOCKS, FD.ISACTIVE 
                   ORDER BY FD.CASHFLOWTYPE, FD.DEPARTMENT, FD.YEAR, FD.MONTH";

        $result = $this->db->query($SQL, $ARR1)->result();
        // var_dump($this->db->last_query());exit;
        
        $DtRevisi = $this->db->query($SQL1, $ARR2)->result();
        
        if ($param["DEPARTMENT"] == "") {
            $BTNSUBMIT = 0;
        } else {
            $SQL = "SELECT * 
                      FROM AUTHORIZESTRUCTURE
                     WHERE AUTHORIZETEMPLATECODE = 'FORECAST'
                       AND AUTHORIZELEVELCODE = '900'
                       AND DEPARTMENT = ?
                       AND FCUSERCODE = ?";
            $BTNSUBMIT = $this->db->query($SQL, [$param['DEPARTMENT'], $param['USERNAME']])->num_rows();
        }

        $return = [
            "DtForecast" => $result,
            "DtRevisi" => $DtRevisi,
            "BTNSUBMIT" => $BTNSUBMIT
        ];
        $this->db->close();
        return $return;
    }

    public function ShowDtForecast($param) {
        $subgroup = $param['COMPANYSUBGROUP'];
        $group    = $param['COMPANYGROUP'];
        $param["CASHFLOWTYPE"] = "%" . $param["CASHFLOWTYPE"] . "%";
        $SQL = "SELECT DISTINCT CFT.*, DT.CASHFLOWTYPE, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                       FF.REQUESTW1, FF.REQUESTW2, FF.REQUESTW3, FF.REQUESTW4, FF.REQUESTW5, ADJSW1, ADJSW2, ADJSW3, ADJSW4, ADJSW5, 
                       FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, FF.ISACTIVE, FF.LOCKS, 
                       (CFT.AMOUNT_INCLUDE_VAT - NVL(FO.AMOUNTINV, 0) + NVL(FF.AMOUNTINV, 0)) AS AMOUNTOUTSTANDING, NVL(CR.RATE, 1) AS RATE
                  FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                         UNION ALL
                        SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN','LEASING', 'LOAN')
						 UNION ALL 
						 SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AP_SPC') CFT
                 INNER JOIN (SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5
                               FROM $this->FORECAST_FIX FF
                              WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                              GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS) FF
                         ON FF.CFTRANSID = CFT.ID
                  LEFT JOIN (SELECT CFTRANSID, SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                               FROM $this->FORECAST_FIX
                              GROUP BY CFTRANSID) FO
                         ON FO.CFTRANSID = CFT.ID
                 INNER JOIN $this->DOCTYPE DT
                         ON DT.FCCODE = CFT.DOCTYPE
                 INNER JOIN $this->COMPANY C
                         ON C.ID = CFT.COMPANY
                 INNER JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = CFT.DEPARTMENT 
                  LEFT JOIN CURS CR
                         ON CR.CURSCODE = CFT.CURRENCY
                        AND CR.CURSYEAR = ?
                        AND CR.CURSMONTH = ?
                 WHERE DT.CASHFLOWTYPE LIKE ?";

        $SQL1 = "SELECT FD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, SUM(FD.AMOUNTREQUEST) AS AMOUNTREQUEST, SUM(FD.AMOUNTADJS) AS AMOUNTADJS,
                        SUM(FD.AMOUNTREVISI) AS AMOUNTREVISI, SUM(FD.AMOUNTAPPROVE) AS AMOUNTAPPROVE, FD.LOCKS, FD.ISACTIVE
                   FROM $this->FORECASTDANA FD
                  INNER JOIN $this->USER_DEPART UD
                          ON UD.FCCODE = ?
                         AND UD.DEPARTMENT = FD.DEPARTMENT
                  WHERE FD.YEAR = ?
                    AND FD.MONTH = ? 
                    AND FD.CASHFLOWTYPE LIKE ?";
        $ARR1 = [$param['YEAR'], $param['MONTH'], $param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["CASHFLOWTYPE"]];
        $ARR2 = [$param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["CASHFLOWTYPE"]];
        if ($param["DEPARTMENT"] != NULL && $param["DEPARTMENT"] != '') {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            $SQL1 .= " AND FD.DEPARTMENT = ?";
            array_push($ARR1, $param["DEPARTMENT"]);
            array_push($ARR2, $param["DEPARTMENT"]);
        }
        if($group != null || $group != '' || $group != 0){
          $SQL .= " AND BS.COMPANYGROUP = '$group'";
          $SQL1 .= " AND FD.COMPANYGROUP = '$group'";
        }
        if($subgroup != null || $subgroup != '' || $subgroup != 0){
           $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
           $SQL1 .= " AND FD.COMPANYSUBGROUP = '$subgroup'";
        }
        if ($param["USERACCESS"] == "100003") {
            $SQL .= " AND FF.LOCKS = 1";
            $SQL1 .= " AND FD.LOCKS = 1";
        }
        $SQL .= " ORDER BY DT.CASHFLOWTYPE, CFT.DEPARTMENT, FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, 
                           CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER, CFT.INVOICEVENDORNO";
        $SQL1 .= " GROUP BY FD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, FD.LOCKS, FD.ISACTIVE 
                   ORDER BY FD.CASHFLOWTYPE, FD.DEPARTMENT, FD.YEAR, FD.MONTH";

        $result = $this->db->query($SQL, $ARR1)->result();
        // var_dump($this->db->last_query());exit;
        $DtRevisi = $this->db->query($SQL1, $ARR2)->result();
        
        if ($param["DEPARTMENT"] == "") {
            $BTNSUBMIT = 0;
        } else {
            $SQL = "SELECT * 
                      FROM AUTHORIZESTRUCTURE
                     WHERE AUTHORIZETEMPLATECODE = 'FORECAST'
                       AND AUTHORIZELEVELCODE = '900'
                       AND DEPARTMENT = ?
                       AND FCUSERCODE = ?";
            $BTNSUBMIT = $this->db->query($SQL, [$param['DEPARTMENT'], $param['USERNAME']])->num_rows();
        }

        $return = [
            "DtForecast" => $result,
            "DtRevisi" => $DtRevisi,
            "BTNSUBMIT" => $BTNSUBMIT
        ];
        $this->db->close();
        return $return;
    }

    public function SaveForecast($Data, $Location) {
        // var_dump($Data);exit;
        try {
            if($Data['COMPANYGROUP'] === 0){
                $Data['COMPANYGROUP'] = '';
            }
            if($Data['COMPANYSUBGROUP'] === 0){
                $Data['COMPANYSUBGROUP'] = '';
            }
            $this->db->trans_begin();
            $result = FALSE;
//            Cek Pengguna Forecast
            $SQL = "SELECT * FROM $this->FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            $DTCek = $this->db->query($SQL, [
                        $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']
                    ])->result();
            if (count($DTCek) > 0) {
                foreach ($DTCek as $value) {
                    if ($value->FLAG_ACTIVE == "0") {
                        throw new Exception("Data Forecast has been Closed !!!");
                    } else {
                        if ($Data["KEYSAVE"] != $value->KEYSAVE) {
                            throw new Exception("Data Forecast is used by" . $value->USERNAME . " !!!");
                        }
                    }
                }
            } else {
                throw new Exception("Data Validation Forecast Not Found !!!");
            }

//            Update Amount Forecast 0
            $SQL = "UPDATE $this->FORECAST_FIX FF
                       SET FF.AMOUNTREQUEST = 0,
                           FF.AMOUNTADJS = 0,
                           FF.FCEDIT = ?,
                           FF.LASTUPDATE = SYSDATE,
                           FF.FCIP = ?
                     WHERE FF.YEAR = ?
                       AND FF.MONTH = ?
                       AND FF.COMPANYGROUP = ?
                       AND FF.COMPANYSUBGROUP = ? 
                       AND FF.CFTRANSID IN (SELECT CFT.ID
                                              FROM (SELECT CFT.ID, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCTYPE 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     INNER JOIN $this->CF_TRANSACTION CFPO
                                                             ON CFPO.DOCNUMBER = CFT.DOCREF
                                                            AND CFPO.COMPANY = CFT.COMPANY
                                                     WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                     UNION ALL
                                                    SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE IN('PDO','PDO_IN', 'LOAN', 'LEASING')
													 UNION ALL
                                                    SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE = 'INV_AP_SPC') CFT
                                             INNER JOIN $this->DOCTYPE DT
                                                     ON DT.FCCODE = CFT.DOCTYPE
                                             INNER JOIN BUSINESSUNIT BS ON BS.ID = CFT.BUSINESSUNIT
                                             WHERE CFT.DEPARTMENT = ?
                                               AND DT.CASHFLOWTYPE = ?)";
            $DTU = $this->db->query($SQL, [$Data['USERNAME'], $Location, $Data['YEAR'], $Data['MONTH'], $Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"]]);
            // var_dump($Data);exit;
//            Insert Or Update Amount Forecast
            $dt = [];
            $SQLC = "SELECT * FROM $this->FORECAST_FIX WHERE YEAR = ? AND MONTH = ? AND CFTRANSID = ? AND WEEK = ?  AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            foreach ($Data['DtForecast'] AS $VALUES) {
                if ($VALUES["REQUESTW1"] > 0 || $VALUES["ADJSW1"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW1'],
                        "AMOUNTADJS" => $VALUES['ADJSW1'],
                        "PRIORITY" => $VALUES['PRIORITYW1'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W1', $Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                    "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W1'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W1';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
                if ($VALUES["REQUESTW2"] > 0 || $VALUES["ADJSW2"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW2'],
                        "AMOUNTADJS" => $VALUES['ADJSW2'],
                        "PRIORITY" => $VALUES['PRIORITYW2'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W2',$Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                    "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W2'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W2';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
                if ($VALUES["REQUESTW3"] > 0 || $VALUES["ADJSW3"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW3'],
                        "AMOUNTADJS" => $VALUES['ADJSW3'],
                        "PRIORITY" => $VALUES['PRIORITYW3'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W3',$Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                    "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W3'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W3';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
                if ($VALUES["REQUESTW4"] > 0 || $VALUES["ADJSW4"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW4'],
                        "AMOUNTADJS" => $VALUES['ADJSW4'],
                        "PRIORITY" => $VALUES['PRIORITYW4'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W4',$Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                    "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W4'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W4';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
                if ($VALUES["REQUESTW5"] > 0 || $VALUES["ADJSW5"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW5'],
                        "AMOUNTADJS" => $VALUES['ADJSW5'],
                        "PRIORITY" => $VALUES['PRIORITYW5'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "COMPANYGROUP" => $Data['COMPANYGROUP'],
                        "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W5',$Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "COMPANYGROUP" => $Data['COMPANYGROUP'],
                                    "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W5'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W5';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
            }
//            Delete Amount Forecast 0
            $SQL = "DELETE FROM $this->FORECAST_FIX FF
                     WHERE FF.YEAR = ?
                       AND FF.MONTH = ?
                       AND FF.COMPANYGROUP = ?
                       AND FF.COMPANYSUBGROUP = ? 
                       AND FF.CFTRANSID IN (SELECT CFT.ID
                                              FROM (SELECT CFT.ID, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCTYPE
                                                      FROM $this->CF_TRANSACTION CFT
                                                     INNER JOIN $this->CF_TRANSACTION CFPO
                                                             ON CFPO.DOCNUMBER = CFT.DOCREF
                                                            AND CFPO.COMPANY = CFT.COMPANY
                                                     WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                     UNION ALL
                                                    SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE IN('PDO','PDO_IN', 'LOAN', 'LEASING')
													 UNION ALL
                                                    SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE = 'INV_AP_SPC'
													 ) CFT
                                             INNER JOIN $this->DOCTYPE DT
                                                     ON DT.FCCODE = CFT.DOCTYPE
                                             INNER JOIN BUSINESSUNIT BS ON BS.ID = CFT.BUSINESSUNIT
                                             WHERE CFT.DEPARTMENT = ?
                                               AND DT.CASHFLOWTYPE = ?)
                       AND FF.AMOUNTREQUEST = 0 
                       AND FF.AMOUNTADJS = 0";
            $DDT = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'],$Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"]]);
//            Insert Or Update Data To Forecast Dana
            if ($Data['ACTION'] <> '0') {
                $SQL = "SELECT * FROM $this->FORECASTDANA WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
                $Cek = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'], $Data['DEPARTMENT'], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->num_rows();
                $dt = [
                    "LOCKS" => $Data['FCLOCK'],
                    "ISACTIVE" => $Data['FCSTATUS'],
                    "UPDATED_BY" => $Data['USERNAME'],
                    "UPDATED_LOC" => $Location
                ];
                if ($Data["CASHFLOWTYPE"] == '0') {
                    $dt["AMOUNTREQUEST"] = $Data['REQUESTINTOT'];
                    $dt["AMOUNTADJS"] = $Data['ADJSINTOT'];
                } elseif ($Data["CASHFLOWTYPE"] == '1') {
                    $dt["AMOUNTREQUEST"] = $Data['REQUESTOUTTOT'];
                    $dt["AMOUNTADJS"] = $Data['ADJSOUTTOT'];
                }
                $result2 = $this->db->set('UPDATED_AT', "SYSDATE", false);
                if ($Data['ACTION'] == '1') {
                    $dt["CSUBMIT"] = 1;
                } elseif ($Data['ACTION'] == '2') {
                    $result2 = $result2->set('CSUBMIT', "CREVISI + 1", false);
                }
                if ($Cek > 0) {
                    $result2 = $result2->set($dt)->where([
                                'DEPARTMENT' => $Data['DEPARTMENT'],
                                'YEAR' => $Data['YEAR'],
                                'MONTH' => $Data['MONTH'],
                                "COMPANYGROUP" => $Data["COMPANYGROUP"],
                                "COMPANYSUBGROUP" => $Data["COMPANYSUBGROUP"],
                                'CASHFLOWTYPE' => $Data['CASHFLOWTYPE']
                            ])->update($this->FORECASTDANA);
                } else {
                    $dt["DEPARTMENT"] = $Data['DEPARTMENT'];
                    $dt["YEAR"] = $Data['YEAR'];
                    $dt["MONTH"] = $Data['MONTH'];
                    $dt["COMPANYGROUP"] = $Data["COMPANYGROUP"];
                    $dt["COMPANYSUBGROUP"] = $Data["COMPANYSUBGROUP"];
                    $dt["CASHFLOWTYPE"] = $Data['CASHFLOWTYPE'];
                    $dt["CREATED_BY"] = $Data['USERNAME'];
                    $dt["CREATED_LOC"] = $Location;
                    $result2 = $result2->set('CREATED_AT', "SYSDATE", false)->set($dt)->insert($this->FORECASTDANA);
                }
            }
            if ($result1) {
                $result = TRUE;
                $this->db->set('UPDATED_AT', "SYSDATE", false)->set([
                    "DEPARTMENT" => $Data["DEPARTMENT"],
                    "CASHFLOWTYPE" => $Data["CASHFLOWTYPE"],
                    "COMPANYGROUP" => $Data["COMPANYGROUP"],
                    "COMPANYSUBGROUP" => $Data["COMPANYSUBGROUP"],
                    "YEAR" => $Data["YEAR"],
                    "MONTH" => $Data["MONTH"],
                    "USERNAME" => $Data["USERNAME"],
                    "REMARK" => "Close Edit Data Forecast",
                    "UPDATED_BY" => $Data["USERNAME"],
                    "UPDATED_LOC" => $Location
                ])->insert($this->FORECAST_VALIDATION_HISTORY);
                $dt = [
                    "USERNAME" => "",
                    "UPDATED_BY" => $Data["USERNAME"],
                    "UPDATED_LOC" => $Location,
                    "FLAG_ACTIVE" => 0,
                    "KEYSAVE" => ""
                ];
                $this->db->set('UPDATED_AT', "SYSDATE", false)->set($dt)->where([
                    'DEPARTMENT' => $Data['DEPARTMENT'],
                    'YEAR' => $Data['YEAR'],
                    'MONTH' => $Data['MONTH'],
                    "COMPANYGROUP" => $Data["COMPANYGROUP"],
                    "COMPANYSUBGROUP" => $Data["COMPANYSUBGROUP"],
                    'CASHFLOWTYPE' => $Data['CASHFLOWTYPE']
                ])->update($this->FORECAST_VALIDATION);
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

    public function SaveRA($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->FORECASTDANA WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ?";
            $Cek = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'], $Data['DEPARTMENT']])->result();
            if (count($Cek) > 0) {
                $CSubmit = 0;
                foreach ($Cek as $values) {
                    $CSubmit = $values->CSUBMIT;
                }
                if ($CSubmit == 1) {
                    $SQL = "UPDATE $this->FORECAST_FIX FF
                       SET FF.ISACTIVE = ?,
                           FF.AMOUNTADJS = FF.AMOUNTREQUEST,
                           FF.LOCKS = 0
                     WHERE FF.YEAR = ?
                       AND FF.MONTH = ?
                       AND FF.CFTRANSID IN (SELECT CFT.ID
                                              FROM (SELECT CFT.ID 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     INNER JOIN $this->CF_TRANSACTION CFPO
                                                             ON CFPO.DOCNUMBER = CFT.DOCREF
                                                            AND CFPO.COMPANY = CFT.COMPANY
                                                     WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                     UNION ALL
                                                    SELECT CFT.ID
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE IN('PDO','PDO_IN')) CFT
                                             WHERE CFT.DEPARTMENT = ?)";
                } else {
                    $SQL = "UPDATE $this->FORECAST_FIX FF
                       SET FF.ISACTIVE = ?,
                           FF.LOCKS = 0
                     WHERE FF.YEAR = ?
                       AND FF.MONTH = ?
                       AND FF.CFTRANSID IN (SELECT CFT.ID
                                              FROM (SELECT CFT.ID 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     INNER JOIN $this->CF_TRANSACTION CFPO
                                                             ON CFPO.DOCNUMBER = CFT.DOCREF
                                                            AND CFPO.COMPANY = CFT.COMPANY
                                                     WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                     UNION ALL
                                                    SELECT CFT.ID
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
													  UNION ALL
                                                    SELECT CFT.ID
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE = 'INV_AP_SPC') CFT
                                             WHERE CFT.DEPARTMENT = ?)";
                }
                $result1 = $this->db->query($SQL, [$Data['RASTATUS'], $Data['YEAR'], $Data['MONTH'], $Data['DEPARTMENT']]);
                $dt = [
                    "ISACTIVE" => $Data['RASTATUS'],
                    "FCIP" => $Location,
                    "FCEDIT" => $Data['USERNAME']
                ];
                $result = $this->db->set('LASTUPDATE', "SYSDATE", false);
                if ($Data['RASTATUS'] == '1') {
                    $dt["AMOUNTREVISI"] = $Data['AMOUNTREVISI'];
                    $result = $result->set('CREVISI', "CSUBMIT", false);
                }
                if ($Data['RASTATUS'] == '2') {
                    if ($CSubmit == 1) {
                        $dt["AMOUNTAPPROVE"] = $Data['REQUESTWTOTAL'];
                    } else {
                        $dt["AMOUNTAPPROVE"] = $Data['ADJSWTOTAL'];
                    }
                }
                $result = $result->set($dt)->where([
                            'DEPARTMENT' => $Data['DEPARTMENT'],
                            'YEAR' => $Data['YEAR'],
                            'MONTH' => $Data['MONTH']
                        ])
                        ->update($this->FORECASTDANA);
            } else {

                throw new Exception('Data Request Not Found !!!');
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

    public function DtRevAppDana($param) {

        $SQL = "";
        if($param['COMPANYGROUP'] != 0 && $param['COMPANYSUBGROUP'] != 0){
            $SQL .= " AND FD.COMPANYGROUP = ? ";
            $SQL .= " AND FD.COMPANYSUBGROUP = ? ";
        }

        $SQL = "SELECT UD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, FD.COMPANYGROUP, FD.COMPANYSUBGROUP, NVL(FD.AMOUNTREQUEST, 0) AS AMOUNTREQUEST, NVL(FD.AMOUNTADJS, 0) AS AMOUNTADJS,
                       DECODE(NVL(FD.ISACTIVE, 0), 2, 0, NVL(FD.AMOUNTREVISI, 0)) AS AMOUNTREVISI, NVL(FD.AMOUNTAPPROVE, 0) AS AMOUNTAPPROVE,
                       NVL(FD.ISACTIVE, 0) AS ISACTIVE, NVL(FD.LOCKS, 0) AS LOCKS, '' AS SREVAPP
                  FROM USER_DEPART UD
                  LEFT JOIN FORECASTDANA FD
                         ON FD.DEPARTMENT = UD.DEPARTMENT
                        AND FD.YEAR = ?
                        AND FD.MONTH = ? ".$SQL;

        $SQL .= " WHERE UD.FCCODE = ?";

        if($param['COMPANYGROUP'] != 0 && $param['COMPANYSUBGROUP'] != 0){
            $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param["USERNAME"],$param['COMPANYGROUP'], $param['COMPANYSUBGROUP']])->result();
        }
        else{
            $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param["USERNAME"]])->result();
        }
        
        // var_dump($this->db->last_query());exit();
        /* $SQL = "SELECT UD.DEPARTMENT, NVL(RA.AMOUNTREQUEST, 0) AS AMOUNTREQUEST, NVL(RA.AMOUNTADJS, 0) AS AMOUNTADJS,
          DECODE(NVL(RA.ISACTIVE, 0), 2, 0, NVL(FD.AMOUNTREVISI, 0)) AS AMOUNTREVISI, NVL(FD.AMOUNTAPPROVE, 0) AS AMOUNTAPPROVE,
          NVL(RA.ISACTIVE, 0) AS ISACTIVE, NVL(RA.LOCKS, 0) AS LOCKS
          FROM USER_DEPART UD
          LEFT JOIN FORECASTDANA FD
          ON FD.DEPARTMENT = UD.DEPARTMENT
          AND FD.YEAR = ?
          AND FD.MONTH = ?
          LEFT JOIN (SELECT CFPO.DEPARTMENT, SUM(FF.AMOUNTREQUEST) AS AMOUNTREQUEST, SUM(FF.AMOUNTADJS) AS AMOUNTADJS, MAX(FF.ISACTIVE) AS ISACTIVE, MAX(FF.LOCKS) AS LOCKS
          FROM CF_TRANSACTION CFT
          INNER JOIN FORECAST_FIX FF
          ON FF.CFTRANSID = CFT.ID
          AND FF.YEAR = ?
          AND FF.MONTH = ?
          INNER JOIN CF_TRANSACTION CFPO
          ON CFPO.DOCNUMBER = CFT.DOCREF
          WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'PDO' OR CFT.DOCTYPE = 'INV_AR')
          GROUP BY CFPO.DEPARTMENT) RA
          ON RA.DEPARTMENT = UD.DEPARTMENT
          WHERE UD.FCCODE = ?";
          $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param['YEAR'], $param['MONTH'], $param["USERNAME"]])->result(); */
        $this->db->close();
        return $result;
    }

    public function SaveRevApp($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->FORECASTDANA WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            foreach ($Data['DATA'] AS $values) {
                $Cek = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'], $values['DEPARTMENT'], $values['CASHFLOWTYPE'], $Data['COMPANYGROUP'], $Data['COMPANYSUBGROUP']])->result();
                if (count($Cek) > 0) {
                    $CSubmit = 0;
                    foreach ($Cek as $value) {
                        $CSubmit = $value->CSUBMIT;
                    }
                    if ($values['SREVAPP'] == '2') {
                        $LOCKS = 1;
                    } else {
                        $LOCKS = 0;
                    }
                    $SQL1 = "UPDATE $this->FORECAST_FIX FF
                                   SET FF.ISACTIVE = ?, ";
                    if ($CSubmit == 1) {
                        $SQL1 .= " FF.AMOUNTADJS = FF.AMOUNTREQUEST, ";
                    }
                    $SQL1 .= "          FF.LOCKS = ?
                                 WHERE FF.YEAR = ?
                                   AND FF.MONTH = ?
                                   AND FF.COMPANYGROUP = ?
                                   AND FF.COMPANYSUBGROUP = ? 
                                   AND FF.CFTRANSID IN (SELECT CFT.ID
                                                          FROM (SELECT CFT.ID, CFPO.DEPARTMENT, CFT.DOCTYPE 
                                                                  FROM $this->CF_TRANSACTION CFT
                                                                 INNER JOIN $this->CF_TRANSACTION CFPO
                                                                         ON CFPO.DOCNUMBER = CFT.DOCREF
                                                                        AND CFPO.COMPANY = CFT.COMPANY
                                                                 WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                                 UNION ALL
                                                                SELECT CFT.ID, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                                  FROM $this->CF_TRANSACTION CFT
                                                                 WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
																 UNION ALL
                                                                SELECT CFT.ID, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                                  FROM $this->CF_TRANSACTION CFT
                                                                 WHERE CFT.DOCTYPE = 'INV_AP_SPC') CFT
                                                         INNER JOIN $this->DOCTYPE DT
                                                                 ON DT.FCCODE = CFT.DOCTYPE
                                                         WHERE CFT.DEPARTMENT = ?
                                                           AND DT.CASHFLOWTYPE = ?)";
                    $result1 = $this->db->query($SQL1, [$values['SREVAPP'], $LOCKS, $Data['YEAR'], $Data['MONTH'],$Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP'], $values['DEPARTMENT'], $values['CASHFLOWTYPE']]);
                    // var_dump($this->db->last_query());exit;
                    $dt = [
                        "LOCKS" => $LOCKS,
                        "ISACTIVE" => $values['SREVAPP'],
                        "UPDATED_BY" => $Data['USERNAME'],
                        "UPDATED_LOC" => $Location
                    ];
                    $result = $this->db->set('UPDATED_AT', "SYSDATE", false);
                    if ($values['SREVAPP'] == '1') {
                        $dt["AMOUNTREVISI"] = $values['AMOUNTREVISI'];
                        $result = $result->set('CREVISI', "CSUBMIT", false);
                    } elseif ($values['SREVAPP'] == '2') {
                        if ($CSubmit == 1) {
                            $dt["AMOUNTAPPROVE"] = $values['AMOUNTREQUEST'];
                        } else {
                            $dt["AMOUNTAPPROVE"] = $values['AMOUNTADJS'];
                        }
                    }
                    $result = $result->set($dt)->where([
                                'DEPARTMENT' => $values['DEPARTMENT'],
                                'YEAR' => $Data['YEAR'],
                                'MONTH' => $Data['MONTH'],
                                'COMPANYGROUP' => $Data['COMPANYGROUP'],
                                'COMPANYSUBGROUP' => $Data['COMPANYSUBGROUP'],
                                'CASHFLOWTYPE' => $values['CASHFLOWTYPE']
                            ])->update($this->FORECASTDANA);
                } else {
                                    // var_dump($this->db->last_query());exit;
                    throw new Exception('Data Request Not Found !!!');
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Data Save Failed !!#');
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

    public function Export($Data) {
        try {
            $FCashType = "Cashflow Type : ";
            if ($Data["CASHFLOWTYPE"] == "ALL") {
                $CASHFLOWTYPE = "%%";
                $FCashType .= "All";
            } else {
                $CASHFLOWTYPE = "%" . $Data["CASHFLOWTYPE"] . "%";
                if ($Data["CASHFLOWTYPE"] == "0") {
                    $FCashType .= "Cash In";
                } elseif ($Data["CASHFLOWTYPE"] == "1") {
                    $FCashType .= "Cash Out";
                }
            }
            
            $subgroup = $Data['COMPANYSUBGROUP'];
            $group    = $Data['COMPANYGROUP'];
            $SQL = "SELECT DISTINCT CFT.*, CFT.DEPARTMENT AS DEPARTMENTNAME, DT.CASHFLOWTYPE, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                       FF.REQUESTW1, FF.REQUESTW2, FF.REQUESTW3, FF.REQUESTW4, FF.REQUESTW5, ADJSW1, ADJSW2, ADJSW3, ADJSW4, ADJSW5, 
                       FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, FF.ISACTIVE, FF.LOCKS, 
            CASE
            WHEN NVL (FO.AMOUNTINV, 0) > 0 THEN 
            (CFT.AMOUNT_INCLUDE_VAT - NVL (FO.AMOUNTINV, 0) + NVL(FF.AMOUNTINV, 0))
            ELSE
            (CFT.AMOUNT_INCLUDE_VAT - NVL (FO.AMOUNTINV, 0) ) end AS AMOUNTOUTSTANDING,NVL(CR.RATE, 1) AS RATE
                  FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR' OR CFT.DOCTYPE = 'LEASING')
                         UNION ALL
                        SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN','LEASING','LOAN')
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AP_SPC'
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AR_SPC') CFT
                 INNER JOIN (SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5
                               FROM $this->FORECAST_FIX FF
                              WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                              GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS
                              UNION ALL
                              SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5
                               FROM $this->FORECAST_FIX_TEMP FF
                              WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                              GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS) FF
                         ON FF.CFTRANSID = CFT.ID
                  LEFT JOIN (SELECT CFTRANSID, 
                            SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                               FROM $this->FORECAST_FIX
                              GROUP BY CFTRANSID
                              /*UNION ALL
                              SELECT CFTRANSID,
                                SUM (DECODE (ISACTIVE,  0, AMOUNTREQUEST,  NULL, 0,  AMOUNTADJS)) AS AMOUNTINV
                                  FROM FORECAST_FIX_TEMP
                              GROUP BY CFTRANSID*/) FO
                         ON FO.CFTRANSID = CFT.ID
                 INNER JOIN $this->DOCTYPE DT
                         ON DT.FCCODE = CFT.DOCTYPE
                 INNER JOIN $this->COMPANY C
                         ON C.ID = CFT.COMPANY
                 INNER JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = CFT.DEPARTMENT 
                  LEFT JOIN CURS CR
                         ON CR.CURSCODE = CFT.CURRENCY
                        AND CR.CURSYEAR = ?
                        AND CR.CURSMONTH = ?
                 WHERE DT.CASHFLOWTYPE LIKE ?";

            $ParamW = [$Data['YEAR'], $Data['MONTH'],$Data['YEAR'], $Data['MONTH'], $Data["USERNAME"], $Data['YEAR'], $Data['MONTH'], $CASHFLOWTYPE];
            // $ParamW = [$Data["YEAR"], $Data["MONTH"],$Data["YEAR"], $Data["MONTH"], $Data["USERNAME"], $CASHFLOWTYPE];
            if ($Data["DEPARTMENT"] == "ALL") {
                $FDepartment = "Department : All Department";
            } else {
                $FDepartment = "Department : " . $Data["DEPARTMENT"];
                $SQL .= " AND CFT.DEPARTMENT = ?";
                array_push($ParamW, $Data["DEPARTMENT"]);
            }
            if($group != null && $group != ''){
              $SQL .= " AND BS.COMPANYGROUP = '$group' ";
            }
            if($subgroup != null && $subgroup != ''){
               $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
            }
            $SQL .= " ORDER BY DT.CASHFLOWTYPE, CFT.DEPARTMENT, FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, 
                               CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER";
            $result = $this->db->query($SQL, $ParamW)->result();
            // var_dump($this->db->last_query());exit();
            $FPeriod = "Period Forecast : " . Carbon::parse($Data["YEAR"] . substr("0" . $Data["MONTH"], -2) . "01")->format('Y-M');
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');

            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("IT GAMA")
                    ->setLastModifiedBy("IT GAMA")
                    ->setTitle("Report Forecast")
                    ->setSubject("Report Forecast")
                    ->setDescription("Data Document in System $FDepartment, $FPeriod, $FCashType, $GExport")
                    ->setKeywords("Report Forecast")
                    ->setCategory("Report Forecast");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Data');
            $i = 1;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(9.5);

            $StyleDefault = [
                'bold' => FALSE,
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Calibri'
            ];
            $StyleBold = [
                'bold' => TRUE,
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Calibri'
            ];
            $StyleCenterAll = [
                'vertical' => 'center',
                'horizontal' => 'center'
            ];
            $StyleRight = [
                'vertical' => 'center',
                'horizontal' => 'right'
            ];
            $StyleBorder = [
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('argb' => '000000')
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => array('argb' => '000000')
                ]
            ];

            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "Data Document In System");
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                'font' => [
                    'bold' => TRUE,
                    'color' => array('rgb' => '000000'),
                    'size' => 14,
                    'name' => 'Calibri'
                ]
            ]);
            $i++;
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':A' . ($i + 2))->applyFromArray(['font' => $StyleDefault]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FDepartment);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FCashType);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $GExport);
            $i++;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . ($i + 2))->applyFromArray([
                'font' => $StyleBold, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
            ]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'NO');
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':A' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'TYPE');
            $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':B' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'DEPARMNET');
            $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':C' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'COMPANY');
            $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':D' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'BUSINESS UNIT');
            $objPHPExcel->getActiveSheet()->mergeCells('E' . $i . ':E' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'VENDOR');
            $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':F' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'INVOICE VENDOR NO');
            $objPHPExcel->getActiveSheet()->mergeCells('G' . $i . ':G' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'DOCUMENT');
            $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':M' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'WEEK');
            $objPHPExcel->getActiveSheet()->mergeCells('O' . $i . ':AC' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'TYPE');
            $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':H' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'NUMBER');
            $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':I' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'INVOICE');
            $objPHPExcel->getActiveSheet()->mergeCells('J' . $i . ':J' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'DUE DATE');
            $objPHPExcel->getActiveSheet()->mergeCells('K' . $i . ':K' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'CURRENCY');
            $objPHPExcel->getActiveSheet()->mergeCells('L' . $i . ':L' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'AMOUNT');
            $objPHPExcel->getActiveSheet()->mergeCells('M' . $i . ':N' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->mergeCells('O' . $i . ':Q' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->mergeCells('R' . $i . ':T' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->mergeCells('U' . $i . ':W' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->mergeCells('X' . $i . ':Z' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, 'W5');
            $objPHPExcel->getActiveSheet()->mergeCells('AA' . $i . ':AC' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'SOURCE');
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'OUTSTANDING');
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('AC' . $i, 'PRIORITY');
            $i++;

            if (count($result) > 0) {
                $iDtAwal = $i;
                $iLoop = 0;
                $icashin = $i;
                $No = 1;
                $CTYPE1 = "";
                foreach ($result as $values) {
                    if ($values->CASHFLOWTYPE == '0') {
                        $CTYPE = "CASH IN";
                    } else {
                        $CTYPE = "CASH OUT";
                    }
                    if ($iDtAwal == $i) {
                        $CTYPE1 = $CTYPE;
                        $iLoop = $i;
                    }
                    if ($CTYPE1 != $CTYPE) {
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $iLoop . ':AC' . ($i - 1))->applyFromArray([
                            'font' => $StyleDefault, 'borders' => $StyleBorder
                        ]);
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $iLoop . ':K' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);

                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "TOTAL $CTYPE1 :");
                        $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':N' . $i);
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=SUM(O$iLoop:O" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, "=SUM(P$iLoop:P" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, "=SUM(Q$iLoop:Q" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=SUM(R$iLoop:R" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, "=SUM(S$iLoop:S" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=SUM(U$iLoop:U" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, "=SUM(V$iLoop:V" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=SUM(X$iLoop:X" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, "=SUM(Y$iLoop:Y" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=SUM(AA$iLoop:AA" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, "=SUM(AB$iLoop:AB" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('AB' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . $i)->applyFromArray([
                            'font' => $StyleDefault, 'borders' => $StyleBorder
                        ]);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                            'font' => $StyleBold, 'alignment' => $StyleRight
                        ]);
                        $icashin = $i;
                        $CTYPE1 = $CTYPE;
                        $No = 1;
                        $i++;
                        $$iLoop = $i;
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $No);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $CTYPE);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $values->DEPARTMENTNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $values->COMPANYNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $values->BUSINESSUNITNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $values->VENDORNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $values->INVOICEVENDORNO);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $values->DOCTYPE);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $i, $values->DOCREF, DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $i, $values->DOCNUMBER, DataType::TYPE_STRING);
                    if ($values->DUEDATE != NULL && $values->DUEDATE != "") {
                        $DtDate = explode('/', $values->DUEDATE);
                        $XlsTime = gmmktime(0, 0, 0, intval($DtDate[1]), intval($DtDate[2]), intval($DtDate[0]));
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, Date::PHPToExcel($XlsTime));
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $values->CURRENCY);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $values->AMOUNT_INCLUDE_VAT);
                    $objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $values->AMOUNTOUTSTANDING);
                    $objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $values->REQUESTW1);
                    $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $values->ADJSW1);
                    $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $values->PRIORITYW1);
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $values->REQUESTW2);
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $values->ADJSW2);
                    $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $values->PRIORITYW2);
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $values->REQUESTW3);
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $values->ADJSW3);
                    $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $values->PRIORITYW3);
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $values->REQUESTW4);
                    $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $values->ADJSW4);
                    $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $values->PRIORITYW4);
                    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, $values->REQUESTW5);
                    $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, $values->ADJSW5);
                    $objPHPExcel->getActiveSheet()->getStyle('AB' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AC' . $i, $values->PRIORITYW5);
                    $i++;
                    $No++;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A' . $iLoop . ':AC' . ($i - 1))->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                $objPHPExcel->getActiveSheet()->getStyle('K' . $iLoop . ':K' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "TOTAL $CTYPE1 :");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':N' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=SUM(O$iLoop:O" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, "=SUM(P$iLoop:P" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, "=SUM(Q$iLoop:Q" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=SUM(R$iLoop:R" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, "=SUM(S$iLoop:S" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=SUM(U$iLoop:U" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, "=SUM(V$iLoop:V" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=SUM(X$iLoop:X" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, "=SUM(Y$iLoop:Y" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=SUM(AA$iLoop:AA" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, "=SUM(AB$iLoop:AB" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('AB' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . $i)->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                    'font' => $StyleBold, 'alignment' => $StyleRight
                ]);
                $i++;

                if ($icashin != $iDtAwal) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "GRAND TOTAL :");
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':N' . $i);
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=O$icashin - O" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue(':P' . $i, "=P$icashin - P" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=R$icashin - R" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, "=S$icashin - S" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=U$icashin - U" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, "=V$icashin - V" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, "=W$icashin - W" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('W' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=X$icashin - X" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, "=Y$icashin - Y" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=AA$icashin - AA" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, "=AB$icashin - AB" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('AB' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . $i)->applyFromArray([
                        'font' => $StyleDefault, 'borders' => $StyleBorder
                    ]);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                        'font' => $StyleBold, 'alignment' => $StyleRight
                    ]);
                }
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "No Data");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':AC' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . $i)->applyFromArray([
                    'font' => $StyleDefault, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
                ]);
                $i++;
            }
            $return = [
                'STATUS' => TRUE,
                'Data' => $objPHPExcel
            ];
        } catch (Exception $ex) {
            $return = [
                'STATUS' => FALSE,
                'Data' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function Exportbackup($Data) {
        try {
            $FCashType = "Cashflow Type : ";
            if ($Data["CASHFLOWTYPE"] == "ALL") {
                $CASHFLOWTYPE = "%%";
                $FCashType .= "All";
            } else {
                $CASHFLOWTYPE = "%" . $Data["CASHFLOWTYPE"] . "%";
                if ($Data["CASHFLOWTYPE"] == "0") {
                    $FCashType .= "Cash In";
                } elseif ($Data["CASHFLOWTYPE"] == "1") {
                    $FCashType .= "Cash Out";
                }
            }
            $subgroup = $Data['COMPANYSUBGROUP'];
            $group    = $Data['COMPANYGROUP'];
            $SQL = "SELECT DISTINCT CFT.*, DP.FCNAME AS DEPARTMENTNAME, DT.CASHFLOWTYPE, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, 
                           BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                           FF.REQUESTW1, FF.REQUESTW2, FF.REQUESTW3, FF.REQUESTW4, FF.REQUESTW5, ADJSW1, ADJSW2, ADJSW3, ADJSW4, ADJSW5, 
                           FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, FF.ISACTIVE, FF.LOCKS, 
                           (CFT.AMOUNT_INCLUDE_VAT - NVL(FO.AMOUNTINV, 0) + NVL(FF.AMOUNTINV, 0)) AS AMOUNTOUTSTANDING, NVL( CR.RATE, 1 ) AS RATE 
                      FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, 
                                   TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,CFT.INVOICEVENDORNO, CFT.CURRENCY,
                                   (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, 
                                   CFPO.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE
                              FROM $this->CF_TRANSACTION CFT
                             INNER JOIN $this->CF_TRANSACTION CFPO
                                     ON CFPO.DOCNUMBER = CFT.DOCREF
                                    AND CFPO.COMPANY = CFT.COMPANY
                                    AND CFPO.DOCTYPE <> 'PDO'
                             WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR' OR CFT.DOCTYPE = 'LEASING')
                             UNION ALL
                            SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                   TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,CFT.INVOICEVENDORNO, CFT.CURRENCY,
                                   (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, 
                                   TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE
                              FROM $this->CF_TRANSACTION CFT
                             WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
							 UNION ALL
                            SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                   TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,CFT.INVOICEVENDORNO, CFT.CURRENCY,
                                   (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, 
                                   TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE
                              FROM $this->CF_TRANSACTION CFT
                             WHERE CFT.DOCTYPE = 'INV_AP_SPC') CFT
                     INNER JOIN (SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                        ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                        ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                        MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                        MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                        MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                        MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                        MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5
                                   FROM $this->FORECAST_FIX FF
                                  WHERE FF.YEAR = ?
                                    AND FF.MONTH = ?
                                  GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS
                                  UNION ALL
                              SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5
                               FROM FORECAST_FIX_TEMP FF
                              WHERE FF.YEAR = ?
                                    AND FF.MONTH = ? GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS) FF
                             ON FF.CFTRANSID = CFT.ID
                      LEFT JOIN (SELECT CFTRANSID, SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                                   FROM $this->FORECAST_FIX
                                  GROUP BY CFTRANSID) FO
                             ON FO.CFTRANSID = CFT.ID
                      LEFT JOIN $this->DEPARTMENT DP
                             ON DP.FCCODE = CFT.DEPARTMENT
                     INNER JOIN $this->DOCTYPE DT
                             ON DT.FCCODE = CFT.DOCTYPE
                     INNER JOIN $this->COMPANY C
                             ON C.ID = CFT.COMPANY
                     INNER JOIN COMPANY_SUBGROUP CSG ON CSG.FCCODE = C.COMPANY_SUBGROUP
                     INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
                     INNER JOIN $this->BUSINESSUNIT BS
                             ON BS.ID = CFT.BUSINESSUNIT
                            AND BS.COMPANY = CFT.COMPANY
                      LEFT JOIN $this->SUPPLIER S
                             ON S.ID = CFT.VENDOR
                     INNER JOIN $this->USER_DEPART UD
                             ON UD.FCCODE = ?
                            AND UD.DEPARTMENT = CFT.DEPARTMENT 
                            LEFT JOIN CURS CR ON CR.CURSCODE = CFT.CURRENCY 
                AND CR.CURSYEAR = '".$Data["YEAR"]."' AND CR.CURSMONTH =  '".$Data["MONTH"]."' WHERE DT.CASHFLOWTYPE LIKE ?";
            $ParamW = [$Data["YEAR"], $Data["MONTH"],$Data["YEAR"], $Data["MONTH"], $Data["USERNAME"], $CASHFLOWTYPE];
            if ($Data["DEPARTMENT"] == "ALL") {
                $FDepartment = "Department : All Department";
            } else {
                $FDepartment = "Department : " . $Data["DEPARTMENT"];
                $SQL .= " AND CFT.DEPARTMENT = ?";
                array_push($ParamW, $Data["DEPARTMENT"]);
            }
            if($group != null && $group != ''){
              $SQL .= " AND BS.COMPANYGROUP = '$group' ";
            }
            if($subgroup != null && $subgroup != ''){
               $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
            }
            $SQL .= " ORDER BY DT.CASHFLOWTYPE, CFT.DEPARTMENT, FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, 
                               CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER";
            $result = $this->db->query($SQL, $ParamW)->result();
            var_dump($this->db->last_query());exit();
            $FPeriod = "Period Forecast : " . Carbon::parse($Data["YEAR"] . substr("0" . $Data["MONTH"], -2) . "01")->format('Y-M');
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');

            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("IT GAMA")
                    ->setLastModifiedBy("IT GAMA")
                    ->setTitle("Report Forecast")
                    ->setSubject("Report Forecast")
                    ->setDescription("Data Document in System $FDepartment, $FPeriod, $FCashType, $GExport")
                    ->setKeywords("Report Forecast")
                    ->setCategory("Report Forecast");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Data');
            $i = 1;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(9.5);

            $StyleDefault = [
                'bold' => FALSE,
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Calibri'
            ];
            $StyleBold = [
                'bold' => TRUE,
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Calibri'
            ];
            $StyleCenterAll = [
                'vertical' => 'center',
                'horizontal' => 'center'
            ];
            $StyleRight = [
                'vertical' => 'center',
                'horizontal' => 'right'
            ];
            $StyleBorder = [
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('argb' => '000000')
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => array('argb' => '000000')
                ]
            ];

            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "Data Document In System");
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                'font' => [
                    'bold' => TRUE,
                    'color' => array('rgb' => '000000'),
                    'size' => 14,
                    'name' => 'Calibri'
                ]
            ]);
            $i++;
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':A' . ($i + 2))->applyFromArray(['font' => $StyleDefault]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FDepartment);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FCashType);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $GExport);
            $i++;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . ($i + 2))->applyFromArray([
                'font' => $StyleBold, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
            ]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'NO');
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':A' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'TYPE');
            $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':B' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'DEPARMNET');
            $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':C' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'COMPANY');
            $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':D' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'BUSINESS UNIT');
            $objPHPExcel->getActiveSheet()->mergeCells('E' . $i . ':E' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'VENDOR');
            $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':F' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'INVOICE VENDOR NO');
            $objPHPExcel->getActiveSheet()->mergeCells('G' . $i . ':G' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'DOCUMENT');
            $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':M' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'WEEK');
            $objPHPExcel->getActiveSheet()->mergeCells('O' . $i . ':AC' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'TYPE');
            $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':H' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'NUMBER');
            $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':I' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'INVOICE');
            $objPHPExcel->getActiveSheet()->mergeCells('J' . $i . ':J' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'DUE DATE');
            $objPHPExcel->getActiveSheet()->mergeCells('K' . $i . ':K' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'CURRENCY');
            $objPHPExcel->getActiveSheet()->mergeCells('L' . $i . ':L' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'AMOUNT');
            $objPHPExcel->getActiveSheet()->mergeCells('M' . $i . ':N' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->mergeCells('O' . $i . ':Q' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->mergeCells('R' . $i . ':T' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->mergeCells('U' . $i . ':W' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->mergeCells('X' . $i . ':Z' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, 'W5');
            $objPHPExcel->getActiveSheet()->mergeCells('AA' . $i . ':AC' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'SOURCE');
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'OUTSTANDING');
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('AC' . $i, 'PRIORITY');
            $i++;

            if (count($result) > 0) {
                $iDtAwal = $i;
                $iLoop = 0;
                $icashin = $i;
                $No = 1;
                $CTYPE1 = "";
                foreach ($result as $values) {
                    if ($values->CASHFLOWTYPE == '0') {
                        $CTYPE = "CASH IN";
                    } else {
                        $CTYPE = "CASH OUT";
                    }
                    if ($iDtAwal == $i) {
                        $CTYPE1 = $CTYPE;
                        $iLoop = $i;
                    }
                    if ($CTYPE1 != $CTYPE) {
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $iLoop . ':AC' . ($i - 1))->applyFromArray([
                            'font' => $StyleDefault, 'borders' => $StyleBorder
                        ]);
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $iLoop . ':K' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);

                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "TOTAL $CTYPE1 :");
                        $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':N' . $i);
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=SUM(O$iLoop:O" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, "=SUM(P$iLoop:P" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, "=SUM(Q$iLoop:Q" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=SUM(R$iLoop:R" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, "=SUM(S$iLoop:S" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=SUM(U$iLoop:U" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, "=SUM(V$iLoop:V" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=SUM(X$iLoop:X" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, "=SUM(Y$iLoop:Y" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=SUM(AA$iLoop:AA" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, "=SUM(AB$iLoop:AB" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('AB' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . $i)->applyFromArray([
                            'font' => $StyleDefault, 'borders' => $StyleBorder
                        ]);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                            'font' => $StyleBold, 'alignment' => $StyleRight
                        ]);
                        $icashin = $i;
                        $CTYPE1 = $CTYPE;
                        $No = 1;
                        $i++;
                        $$iLoop = $i;
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $No);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $CTYPE);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $values->DEPARTMENTNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $values->COMPANYNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $values->BUSINESSUNITNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $values->VENDORNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $values->INVOICEVENDORNO);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $values->DOCTYPE);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $i, $values->DOCREF, DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $i, $values->DOCNUMBER, DataType::TYPE_STRING);
                    if ($values->DUEDATE != NULL && $values->DUEDATE != "") {
                        $DtDate = explode('/', $values->DUEDATE);
                        $XlsTime = gmmktime(0, 0, 0, intval($DtDate[1]), intval($DtDate[2]), intval($DtDate[0]));
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, Date::PHPToExcel($XlsTime));
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $values->CURRENCY);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $values->AMOUNT_INCLUDE_VAT);
                    $objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $values->AMOUNTOUTSTANDING);
                    $objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $values->REQUESTW1);
                    $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $values->ADJSW1);
                    $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $values->PRIORITYW1);
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $values->REQUESTW2);
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $values->ADJSW2);
                    $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $values->PRIORITYW2);
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $values->REQUESTW3);
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $values->ADJSW3);
                    $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $values->PRIORITYW3);
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $values->REQUESTW4);
                    $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $values->ADJSW4);
                    $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $values->PRIORITYW4);
                    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, $values->REQUESTW5);
                    $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, $values->ADJSW5);
                    $objPHPExcel->getActiveSheet()->getStyle('AB' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AC' . $i, $values->PRIORITYW5);
                    $i++;
                    $No++;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A' . $iLoop . ':AC' . ($i - 1))->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                $objPHPExcel->getActiveSheet()->getStyle('K' . $iLoop . ':K' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "TOTAL $CTYPE1 :");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':N' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=SUM(O$iLoop:O" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, "=SUM(P$iLoop:P" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, "=SUM(Q$iLoop:Q" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=SUM(R$iLoop:R" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, "=SUM(S$iLoop:S" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=SUM(U$iLoop:U" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, "=SUM(V$iLoop:V" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=SUM(X$iLoop:X" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, "=SUM(Y$iLoop:Y" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=SUM(AA$iLoop:AA" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, "=SUM(AB$iLoop:AB" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('AB' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . $i)->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                    'font' => $StyleBold, 'alignment' => $StyleRight
                ]);
                $i++;

                if ($icashin != $iDtAwal) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "GRAND TOTAL :");
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':N' . $i);
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=O$icashin - O" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue(':P' . $i, "=P$icashin - P" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=R$icashin - R" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, "=S$icashin - S" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=U$icashin - U" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, "=V$icashin - V" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, "=W$icashin - W" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('W' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=X$icashin - X" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, "=Y$icashin - Y" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=AA$icashin - AA" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, "=AB$icashin - AB" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('AB' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . $i)->applyFromArray([
                        'font' => $StyleDefault, 'borders' => $StyleBorder
                    ]);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                        'font' => $StyleBold, 'alignment' => $StyleRight
                    ]);
                }
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "No Data");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':AC' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AC' . $i)->applyFromArray([
                    'font' => $StyleDefault, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
                ]);
                $i++;
            }
            $return = [
                'STATUS' => TRUE,
                'Data' => $objPHPExcel
            ];
        } catch (Exception $ex) {
            $return = [
                'STATUS' => FALSE,
                'Data' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function CekValidation($Data, $Location) {

        try {
            if($Data['COMPANYGROUP'] === 0){
                $Data['COMPANYGROUP'] = '';
            }
            if($Data['COMPANYSUBGROUP'] === 0){
                $Data['COMPANYSUBGROUP'] = '';
            }
            $this->db->trans_begin();
            $result = FALSE;
            $Key = $Data["DEPARTMENT"] . Carbon::now('Asia/Jakarta')->format('YmdHis');
            $Key1 = "";
            $USERNAME = "";
            $FLAG_ACTIVE = 0;
            $SQL = "SELECT * FROM $this->FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            $DTCek = $this->db->query($SQL, [
                        $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']])->result();
            // var_dump($this->db->last_query());exit;
            if (count($DTCek) > 0) {
                foreach ($DTCek as $value) {
                    $FLAG_ACTIVE = $value->FLAG_ACTIVE;
                    $USERNAME = $value->USERNAME;
                    $Key1 = $value->KEYSAVE;
                }
                if ($FLAG_ACTIVE == '1') {
//                    Untuk Edit Forecast sama user bisa di buka
                    /*if ($USERNAME == $Data["USERNAME"]) {
                        $Key = $Key1;
                    } else {*/
					/*edit by iwa */
                    if ($this->session->userdata('username') != $USERNAME ){						
							throw new Exception("Data is used by $USERNAME !");						
						}
					/*end edit by iwa */
//                    }
                }
            }
            $dt = [
                "USERNAME" => $Data["USERNAME"],
                "UPDATED_BY" => $Data["USERNAME"],
                "UPDATED_LOC" => $Location,
                "FLAG_ACTIVE" => 1,
                "KEYSAVE" => $Key
            ];
            $result = $this->db->set('UPDATED_AT', "SYSDATE", false);
            if (count($DTCek) > 0) {
                $result = $result->set($dt)->where([
                            'DEPARTMENT' => $Data['DEPARTMENT'],
                            'YEAR' => $Data['YEAR'],
                            'MONTH' => $Data['MONTH'],
                            'CASHFLOWTYPE' => $Data['CASHFLOWTYPE'],
                            'COMPANYGROUP' => $Data['COMPANYGROUP'],
                            'COMPANYSUBGROUP' => $Data['COMPANYSUBGROUP']
                        ])->update($this->FORECAST_VALIDATION);
            } else {
                $dt["DEPARTMENT"] = $Data["DEPARTMENT"];
                $dt["CASHFLOWTYPE"] = $Data["CASHFLOWTYPE"];
                $dt["YEAR"] = $Data["YEAR"];
                $dt["MONTH"] = $Data["MONTH"];
                $dt["CREATED_BY"] = $Data["USERNAME"];
                $dt["CREATED_LOC"] = $Location;
                $dt["COMPANYGROUP"] = $Data["COMPANYGROUP"];
                $dt["COMPANYSUBGROUP"] = $Data["COMPANYSUBGROUP"];
                $result = $result->set('CREATED_AT', "SYSDATE", false)->set($dt)->insert($this->FORECAST_VALIDATION);
            }
            $this->db->set('UPDATED_AT', "SYSDATE", false)->set([
                "DEPARTMENT" => $Data["DEPARTMENT"],
                "CASHFLOWTYPE" => $Data["CASHFLOWTYPE"],
                "YEAR" => $Data["YEAR"],
                "MONTH" => $Data["MONTH"],
                "USERNAME" => $Data["USERNAME"],
                "COMPANYGROUP" => $Data['COMPANYGROUP'],
                "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                "REMARK" => "Open Edit Data Forecast",
                "UPDATED_BY" => $Data["USERNAME"],
                "UPDATED_LOC" => $Location
            ])->insert($this->FORECAST_VALIDATION_HISTORY);

            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $Key
                ];
            } else {
                throw new Exception('Get Data Failed !');
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

    public function CancelValidation($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $USERNAME = "";
            $FLAG_ACTIVE = 0;
            $KEYSAVE = "";
            $SQL = "SELECT * FROM $this->FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            $DTCek = $this->db->query($SQL, [
                        $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']])->result();
            if (count($DTCek) > 0) {
                foreach ($DTCek as $value) {
                    $FLAG_ACTIVE = $value->FLAG_ACTIVE;
                    $USERNAME = $value->USERNAME;
                    $KEYSAVE = $value->KEYSAVE;
                }
                $SQL1 = "DELETE FROM FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";

                $res = $this->db->query($SQL, [
                        $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']]);
            } else {
                throw new Exception("Data Not Found!!!");
            }
            if ($KEYSAVE == $Data["KEYSAVE"]) {
                $dt = [
                    "USERNAME" => "",
                    "UPDATED_BY" => $Data["USERNAME"],
                    "UPDATED_LOC" => $Location,
                    "FLAG_ACTIVE" => 0,
                    "KEYSAVE" => ""
                ];
                $result = $this->db->set('UPDATED_AT', "SYSDATE", false)->set($dt)->where([
                            'DEPARTMENT' => $Data['DEPARTMENT'],
                            'YEAR' => $Data['YEAR'],
                            'MONTH' => $Data['MONTH'],
                            'CASHFLOWTYPE' => $Data['CASHFLOWTYPE'],
                            "COMPANYGROUP" => $Data['COMPANYGROUP'],
                            "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                        ])->update($this->FORECAST_VALIDATION);
            } else {
                throw new Exception("Data has been Closed !!!");
            }
            $this->db->set('UPDATED_AT', "SYSDATE", false)->set([
                "DEPARTMENT" => $Data["DEPARTMENT"],
                "CASHFLOWTYPE" => $Data["CASHFLOWTYPE"],
                "YEAR" => $Data["YEAR"],
                "MONTH" => $Data["MONTH"],
                "USERNAME" => $Data["USERNAME"],
                "REMARK" => "Close Edit Data Forecast",
                "UPDATED_BY" => $Data["USERNAME"],
                "COMPANYGROUP" => $Data['COMPANYGROUP'],
                "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                "UPDATED_LOC" => $Location
            ])->insert($this->FORECAST_VALIDATION_HISTORY);

            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "Successfully Closed Edit Data Forecast !!"
                ];
            } else {
                throw new Exception('Get Data Failed !!!');
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

}
