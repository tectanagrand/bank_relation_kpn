<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class PaymentModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }

    public function cekBeforeDel($param) {
        $ID             = $param['ID'];
        $AMOUNT_TOTAL   = $param['AMOUNT_TOTAL'];

        $q = "SELECT * FROM PAYMENT WHERE CFTRANSID = '$ID' AND AMOUNTBANK = '$AMOUNT_TOTAL'";
        $q = $this->db->query($q)->num_rows();

        $q2 = "SELECT * FROM FORECAST_FIX WHERE CFTRANSID = '$ID'";
        $q2 = $this->db->query($q2)->num_rows();

        // var_dump(gettype($q).gettype($q2));exit;
        if($q > 0 && $q2 > 0){
            $data = [
                'payment' => 1,
                'forecast' => 1
            ];
        }
        else if($q > 0 && $q2 == 0){
            $data = [
                'payment' => 1,
                'forecast' => 0
            ];
        }
        else if($q == 0 && $q2 > 0){
            $data = [
                'payment' => 0,
                'forecast' => 1
            ];
        }else{
            $data = [
                'payment' => 0,
                'forecast' => 0
            ];
        }
        $this->db->close();
        return $data;
    }

    public function ShowDataForecast($param) {
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        $SQL = "(SELECT CFT.*, DT.CASHFLOWTYPE, FF.ID AS FORECASTID, FF.AMOUNTADJS AS AMOUNTFORECAST, (FF.AMOUNTADJS - NVL(PAY.AMOUNTPAID, 0)) AS AMOUNTOUTSTANDING,
                        PAY.AMOUNTPAID, FF.YEAR, FF.MONTH, FF.WEEK, FF.PRIORITY, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME,
                        S.FCNAME AS VENDORNAME, '100001' AS DATABY, 'Forecast' AS DATABYNAME
                   FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR,
                                TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY,CFT.INVOICEVENDORNO
                           FROM $this->CF_TRANSACTION CFT
                          INNER JOIN $this->CF_TRANSACTION CFPO
                                  ON CFPO.DOCNUMBER = CFT.DOCREF
                                 AND CFPO.COMPANY = CFT.COMPANY
                          WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                          UNION ALL
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY,CFT.INVOICEVENDORNO
                           FROM $this->CF_TRANSACTION CFT
                          WHERE CFT.DOCTYPE = 'PDO') CFT
                  LEFT JOIN $this->DEPARTMENT DP
                          ON DP.FCCODE = CFT.DEPARTMENT
                  INNER JOIN $this->FORECAST_FIX FF
                          ON FF.CFTRANSID = CFT.ID
                         AND FF.ISACTIVE = 2
                  INNER JOIN $this->DOCTYPE DT
                          ON DT.FCCODE = CFT.DOCTYPE
                   LEFT JOIN (SELECT P.FORECASTID, SUM(P.AMOUNT) AS AMOUNTPAID
                                FROM $this->PAYMENT P
                               GROUP BY P.FORECASTID) PAY
                          ON PAY.FORECASTID = FF.ID
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
                  WHERE (FF.AMOUNTADJS - NVL(PAY.AMOUNTPAID, 0)) > 0
                    AND FF.YEAR = ?
                    AND FF.MONTH = ?";
        $ParamW = [$param['USERNAME'], $param['YEAR'], $param['MONTH']];
        if ($param["WEEK"] != "" && $param["WEEK"] != NULL) {
            $SQL .= " AND FF.WEEK = ?";
            array_push($ParamW, $param["WEEK"]);
        }
        if ($param["DEPARTMENT"] != "" && $param["DEPARTMENT"] != NULL) {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            array_push($ParamW, $param["DEPARTMENT"]);
        }
        if ($param["CASHFLOWTYPE"] != "" && $param["CASHFLOWTYPE"] != NULL) {
            $SQL .= " AND DT.CASHFLOWTYPE = ?";
            array_push($ParamW, $param["CASHFLOWTYPE"]);
        }
        if ($param["COMPANY"] != "" && $param["COMPANY"] != NULL) {
            $SQL .= " AND CFT.COMPANY = ?";
            array_push($ParamW, $param["COMPANY"]);
        }
        if ($param["COMPANYGROUP"] != "" && $param["COMPANYGROUP"] != NULL) {
            $SQL .= " AND BS.COMPANYGROUP = ?";
            array_push($ParamW, $param["COMPANYGROUP"]);
        }
        if ($param["COMPANYSUBGROUP"] != "" && $param["COMPANYSUBGROUP"] != NULL) {
            $SQL .= " AND BS.COMPANY_SUBGROUP = ?";
            array_push($ParamW, $param["COMPANYSUBGROUP"]);
        }
        $SQL .= ")";
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            $SQLO = " ORDER BY FC.YEAR, FC.MONTH, FC.WEEK, FC.PRIORITY, FC.DEPARTMENT, FC.COMPANYNAME, FC.DOCREF";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $SQL FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY", $ParamW)->result();
        // var_dump($this->db->last_query());exit;
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $SQL FC $SQLW", $ParamW)->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $SQL FC", $ParamW)->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    public function DtBankCompany($param) {
        $SQL = "SELECT B.FCCODE, B.FCNAME, B.BANKACCOUNT, B.CURRENCY, B.ISDEFAULT
                  FROM $this->BANK B	
                 WHERE B.COMPANY = ?
                   AND B.ACTIVATION = 'Y'
                 ORDER BY B.ISDEFAULT DESC, B.COMPANYGROUP, B.COMPANY, B.FCNAME";
        $result = $this->db->query($SQL, $param['COMPANY'])->result();
        $this->db->close();
        return $result;
    }

    public function DtOsPayment($param) {
        $DATABY = "%" . $param["DATABY"] . "%";
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        if ($param['DTPAY'] == '' || $param['DTPAY'] == NULL) {
            $param['DTPAY'] = "''";
        }
        $SQL = "(SELECT CFT.*, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME,
                        S.FCNAME AS VENDORNAME
                   FROM (SELECT CFT.*, DT.CASHFLOWTYPE, FF.ID AS FORECASTID, (FF.AMOUNTADJS - NVL(PAY.AMOUNTPAID, 0)) AS AMOUNTFORECAST, (FF.AMOUNTADJS - NVL(PAY.AMOUNTPAID, 0)) AS AMOUNTOS,
                                FF.YEAR, FF.MONTH, FF.WEEK, FF.PRIORITY, '100001' AS DATABY, 'Forecast' AS DATABYNAME
                           FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR,
                                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                        CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                                   FROM $this->CF_TRANSACTION CFT
                                  INNER JOIN $this->CF_TRANSACTION CFPO
                                          ON CFPO.DOCNUMBER = CFT.DOCREF
                                         AND CFPO.COMPANY = CFT.COMPANY
                                  WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                    AND CFT.COMPANY = ?
                                  UNION ALL
                                 SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                        CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                                   FROM $this->CF_TRANSACTION CFT
                                  WHERE CFT.DOCTYPE = 'PDO'
                                    AND CFT.COMPANY = ?) CFT
                          INNER JOIN $this->FORECAST_FIX FF
                                  ON FF.CFTRANSID = CFT.ID
                                 AND FF.ISACTIVE = 2
                          INNER JOIN $this->DOCTYPE DT
                                  ON DT.FCCODE = CFT.DOCTYPE
                           LEFT JOIN (SELECT P.FORECASTID, SUM(P.AMOUNT) AS AMOUNTPAID
                                        FROM $this->PAYMENT P
                                       GROUP BY P.FORECASTID) PAY
                                  ON PAY.FORECASTID = FF.ID
                          INNER JOIN $this->USER_DEPART UD
                                  ON UD.FCCODE = ?
                                 AND UD.DEPARTMENT = CFT.DEPARTMENT
                          WHERE (FF.AMOUNTADJS - NVL(PAY.AMOUNTPAID, 0)) > 0
                            AND DT.CASHFLOWTYPE = ?
                          UNION ALL
                         SELECT CFT.*, DT.CASHFLOWTYPE, NULL AS FORECASTID, (CFT.AMOUNTINVOICE - NVL(FF.AMOUNTFORECAST, 0) - NVL(PAY.AMOUNTPAID, 0)) AS AMOUNTFORECAST, (CFT.AMOUNTINVOICE - NVL(FF.AMOUNTFORECAST, 0) - NVL(PAY.AMOUNTPAID, 0)) AS AMOUNTOS,
                                NULL, NULL, NULL, NULL, '100002' AS DATABY, 'Non Forecast' AS DATABYNAME
                           FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR,
                                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                        CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                                   FROM $this->CF_TRANSACTION CFT
                                  INNER JOIN $this->CF_TRANSACTION CFPO
                                          ON CFPO.DOCNUMBER = CFT.DOCREF
                                         AND CFPO.COMPANY = CFT.COMPANY
                                  WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                    AND CFT.COMPANY = ?
                                  UNION ALL
                                 SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                        CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                                   FROM $this->CF_TRANSACTION CFT
                                  WHERE CFT.DOCTYPE = 'PDO'
                                    AND CFT.COMPANY = ?) CFT
                           LEFT JOIN (SELECT FF.CFTRANSID, SUM(DECODE(FF.ISACTIVE, 0,  FF.AMOUNTREQUEST, FF.AMOUNTADJS)) AS AMOUNTFORECAST
                                        FROM $this->FORECAST_FIX FF
                                       GROUP BY FF.CFTRANSID) FF
                                  ON FF.CFTRANSID = CFT.ID
                          INNER JOIN DOCTYPE DT
                                  ON DT.FCCODE = CFT.DOCTYPE
                           LEFT JOIN (SELECT P.CFTRANSID, SUM(P.AMOUNT) AS AMOUNTPAID
                                        FROM $this->PAYMENT P
                                       WHERE P.FORECASTID IS NULL
                                       GROUP BY P.CFTRANSID) PAY
                                  ON PAY.CFTRANSID = CFT.ID
                          INNER JOIN $this->USER_DEPART UD
                                  ON UD.FCCODE = ?
                                 AND UD.DEPARTMENT = CFT.DEPARTMENT
                          WHERE (CFT.AMOUNTINVOICE - NVL(FF.AMOUNTFORECAST, 0) - NVL(PAY.AMOUNTPAID, 0)) > 0
                            AND DT.CASHFLOWTYPE = ?) CFT
                  INNER JOIN $this->COMPANY C 
                          ON C.ID = CFT.COMPANY
                  INNER JOIN $this->BUSINESSUNIT BS
                          ON BS.ID = CFT.BUSINESSUNIT
                         AND BS.COMPANY = CFT.COMPANY
                   LEFT JOIN $this->SUPPLIER S
                          ON S.ID = CFT.VENDOR
                   LEFT JOIN (SELECT CFT.ID, FF.ID AS FORECASTID
                                FROM $this->CF_TRANSACTION CFT
                               INNER JOIN $this->FORECAST_FIX FF
                                       ON FF.CFTRANSID = CFT.ID
                               WHERE CONCAT(CFT.ID, FF.ID) IN (" . $param['DTPAY'] . ")
                               UNION ALL
                              SELECT CFT.ID, NULL AS FORECASTID
                                FROM $this->CF_TRANSACTION CFT
                                LEFT JOIN (SELECT FF.CFTRANSID, SUM(DECODE(FF.ISACTIVE, 0,  FF.AMOUNTREQUEST, FF.AMOUNTADJS)) AS AMOUNTFORECAST
                                             FROM $this->FORECAST_FIX FF
                                            GROUP BY FF.CFTRANSID) FF
                                       ON FF.CFTRANSID = CFT.ID
                               WHERE (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0) - NVL(FF.AMOUNTFORECAST, 0)) > 0
                                 AND CFT.ID IN (" . $param['DTPAY'] . ")) PD
                          ON CONCAT(PD.ID, PD.FORECASTID) = CONCAT(CFT.ID, CFT.FORECASTID)
                  WHERE PD.ID IS NULL
                    AND CFT.DATABY LIKE ?)";
        $ParamW = [
            $param['COMPANY'], $param['COMPANY'], $param['USERNAME'], $param['CASHFLOWTYPE'],
            $param['COMPANY'], $param['COMPANY'], $param['USERNAME'], $param['CASHFLOWTYPE'],
            $DATABY
        ];
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            $SQLO = " ORDER BY FC.COMPANYNAME, FC.DOCREF";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $SQL FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY", $ParamW)->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $SQL FC $SQLW", $ParamW)->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $SQL FC", $ParamW)->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }


    public function DtOsPayment1($param) {
        $DATABY = "%" . $param["DATABY"] . "%";
        if ($param['DTPAY'] == '' || $param['DTPAY'] == NULL) {
            $param['DTPAY'] = "''";
        }
        $DATEFROM = $param['DATEFROM'];
        $DATETO   = $param['DATETO'];
        $COMPANY  = $param['COMPANY'];
        $USERNAME = $param['USERNAME'];
        $CASHFLOWTYPE = $param['CASHFLOWTYPE'];
        $CURRENCY = $param['CURRENCY'];

        $SQL = "SELECT DISTINCT CFT.*,
                                  C.COMPANYCODE,
                                  C.COMPANYNAME,
                                  BS.FCCODE AS BUSINESSUNITCODE,
                                  BS.FCNAME AS BUSINESSUNITNAME,
                                  S.FCNAME AS VENDORNAME
                    FROM (SELECT CFT.*,
                                 DT.CASHFLOWTYPE,
                                 FF.ID AS FORECASTID,
                                 (FF.AMOUNTADJS - NVL (PAY.AMOUNTPAID, 0)) AS AMOUNTFORECAST,
                                 CASE
                                    WHEN cft.AMOUNTINVOICE = NVL (PAY.AMOUNTPAID, 0) THEN 0
                                    ELSE (FF.AMOUNTADJS - NVL (PAY.AMOUNTPAID, 0))
                                 END
                                    AS AMOUNTOS,
                                 FF.YEAR,
                                 FF.MONTH,
                                 FF.WEEK,
                                 FF.PRIORITY,
                                 '100001' AS DATABY,
                                 'Forecast' AS DATABYNAME
                            FROM (SELECT CFT.ID,
                                         CFT.COMPANY,
                                         CFT.BUSINESSUNIT,
                                         CFPO.DEPARTMENT,
                                         CFT.DOCNUMBER,
                                         CFT.DOCREF,
                                         CFT.VENDOR,
                                         TO_CHAR (CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,
                                         CFT.DUEDATE FILTERDATE,
                                         (CFT.AMOUNT_INCLUDE_VAT - NVL (CFT.AMOUNT_PPH, 0))
                                            AS AMOUNTINVOICE,
                                         CFT.DOCTYPE,
                                         TO_CHAR (CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,
                                         CFT.CURRENCY,
                                         CFT.INVOICEVENDORNO
                                    FROM CF_TRANSACTION CFT
                                         INNER JOIN CF_TRANSACTION CFPO
                                            ON     CFPO.DOCNUMBER = CFT.DOCREF
                                               AND CFPO.COMPANY = CFT.COMPANY
                                   WHERE     (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                         AND CFT.COMPANY = '$COMPANY'
                                         AND    CFT.company
                                             || cft.docref
                                             || cft.id
                                             || TO_CHAR (cft.DOCDATE, 'yyyy') NOT IN
                                                (SELECT DISTINCT
                                                           CFT.COMPANY
                                                        || CFT.DOCREF
                                                        || cft.id
                                                        || TO_CHAR (CFT.DOCDATE, 'yyyy')
                                                           DOCDATE
                                                   FROM FORECAST_FIX FF
                                                        INNER JOIN CF_TRANSACTION CFT
                                                           ON CFT.ID = FF.CFTRANSID
                                                  WHERE     CFT.DOCNUMBER LIKE 'TMPINV%'
                                                        AND CFT.COMPANY = '$COMPANY')
                                  UNION ALL
                                  SELECT CFT.ID,
                                         CFT.COMPANY,
                                         CFT.BUSINESSUNIT,
                                         CFT.DEPARTMENT,
                                         CFT.DOCREF,
                                         CFT.DOCNUMBER,
                                         CFT.VENDOR,
                                         TO_CHAR (CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,
                                         CFT.DUEDATE FILTERDATE,
                                         (CFT.AMOUNT_INCLUDE_VAT - NVL (CFT.AMOUNT_PPH, 0))
                                            AS AMOUNTINVOICE,
                                         CFT.DOCTYPE,
                                         TO_CHAR (CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,
                                         CFT.CURRENCY,
                                         CFT.INVOICEVENDORNO
                                    FROM CF_TRANSACTION CFT
                                   WHERE     CFT.DOCTYPE IN ('PDO', 'INV_AP_SPC','INV_AR_SPC')
                                         AND CFT.COMPANY = '$COMPANY') CFT
                                 INNER JOIN FORECAST_FIX FF
                                    ON FF.CFTRANSID = CFT.ID AND FF.ISACTIVE = 2
                                 INNER JOIN (SELECT ID, COMPANY, CURRENCY
                                               FROM CF_TRANSACTION
                                              WHERE COMPANY = '$COMPANY') cftt
                                    ON (cftt.id = ff.cftransid)
                                 INNER JOIN DOCTYPE DT ON DT.FCCODE = CFT.DOCTYPE
                                 LEFT JOIN (  SELECT P.FORECASTID, SUM (P.AMOUNT) AS AMOUNTPAID
                                                FROM PAYMENT P
                                            GROUP BY P.FORECASTID) PAY
                                    ON PAY.FORECASTID = FF.ID
                                 INNER JOIN USER_DEPART UD
                                    ON UD.FCCODE = '$USERNAME' AND UD.DEPARTMENT = CFT.DEPARTMENT
                           WHERE     (FF.AMOUNTADJS - NVL (PAY.AMOUNTPAID, 0)) > 0
                                 AND DT.CASHFLOWTYPE = '1'
                          UNION ALL
                          SELECT CFT.*,
                                 DT.CASHFLOWTYPE,
                                 NULL AS FORECASTID,
                                 (  CFT.AMOUNTINVOICE
                                  - NVL (FF.AMOUNTFORECAST, 0)
                                  - NVL (PAY.AMOUNTPAID, 0))
                                    AS AMOUNTFORECAST,
                                 (  CFT.AMOUNTINVOICE
                                  - NVL (FF.AMOUNTFORECAST, 0)
                                  - NVL (PAY.AMOUNTPAID, 0))
                                    AS AMOUNTOS,
                                 0 AS YEARX,
                                 0 AS MONTHX,
                                 '' AS WEEKX,
                                 0 AS PRIORITY,
                                 '100002' AS DATABY,
                                 'Non Forecast' AS DATABYNAME
                            FROM (SELECT CFT.ID,
                                         CFT.COMPANY,
                                         CFT.BUSINESSUNIT,
                                         CFPO.DEPARTMENT,
                                         CFT.DOCNUMBER,
                                         CFT.DOCREF,
                                         CFT.VENDOR,
                                         TO_CHAR (CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,
                                         CFT.DUEDATE FILTERDATE,
                                         (CFT.AMOUNT_INCLUDE_VAT - NVL (CFT.AMOUNT_PPH, 0))
                                            AS AMOUNTINVOICE,
                                         CFT.DOCTYPE,
                                         TO_CHAR (CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,
                                         CFT.CURRENCY,
                                         CFT.INVOICEVENDORNO
                                    FROM CF_TRANSACTION CFT
                                         INNER JOIN CF_TRANSACTION CFPO
                                            ON     CFPO.DOCNUMBER = CFT.DOCREF
                                               AND CFPO.COMPANY = CFT.COMPANY
                                   WHERE     (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                         AND CFT.COMPANY = '$COMPANY' AND    CFT.company
                                             || cft.docref
                                             || cft.id
                                             || TO_CHAR (cft.DOCDATE, 'yyyy') NOT IN
                                                (SELECT DISTINCT
                                                           CFT.COMPANY
                                                        || CFT.DOCREF
                                                        || cft.id
                                                        || TO_CHAR (CFT.DOCDATE, 'yyyy')
                                                           DOCDATE
                                                   FROM FORECAST_FIX FF
                                                        INNER JOIN CF_TRANSACTION CFT
                                                           ON CFT.ID = FF.CFTRANSID
                                                  WHERE     CFT.DOCNUMBER LIKE 'TMPINV%'
                                                        AND CFT.COMPANY = '$COMPANY')
                                  UNION ALL
                                  SELECT CFT.ID,
                                         CFT.COMPANY,
                                         CFT.BUSINESSUNIT,
                                         CFT.DEPARTMENT,
                                         CFT.DOCREF,
                                         CFT.DOCNUMBER,
                                         CFT.VENDOR,
                                         TO_CHAR (CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,
                                         CFT.DUEDATE FILTERDATE,
                                         (CFT.AMOUNT_INCLUDE_VAT - NVL (CFT.AMOUNT_PPH, 0))
                                            AS AMOUNTINVOICE,
                                         CFT.DOCTYPE,
                                         TO_CHAR (CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,
                                         CFT.CURRENCY,
                                         CFT.INVOICEVENDORNO
                                    FROM CF_TRANSACTION CFT
                                   WHERE     CFT.DOCTYPE IN ('PDO', 'INV_AP_SPC','INV_AR_SPC')
                                         AND CFT.COMPANY = '$COMPANY') CFT
                                 LEFT JOIN
                                 (  SELECT FF.CFTRANSID,
                                           NVL (SUM (FF.AMOUNTADJS), 0) AS AMOUNTFORECAST
                                      FROM FORECAST_FIX FF
                                           INNER JOIN (SELECT ID, COMPANY, CURRENCY
                                                         FROM CF_TRANSACTION
                                                        WHERE COMPANY = '$COMPANY') cftt
                                              ON (cftt.id = ff.cftransid)
                                  GROUP BY FF.CFTRANSID) FF
                                    ON FF.CFTRANSID = CFT.ID
                                 INNER JOIN DOCTYPE DT ON DT.FCCODE = CFT.DOCTYPE
                                 LEFT JOIN (  SELECT P.CFTRANSID, SUM (P.AMOUNT) AS AMOUNTPAID
                                                FROM PAYMENT P
                                               WHERE P.FORECASTID IS NULL
                                            GROUP BY P.CFTRANSID) PAY
                                    ON PAY.CFTRANSID = CFT.ID
                                 INNER JOIN USER_DEPART UD ON
                                                              UD.DEPARTMENT = CFT.DEPARTMENT
                           WHERE     (  CFT.AMOUNTINVOICE
                                      - NVL (FF.AMOUNTFORECAST, 0)
                                      - NVL (PAY.AMOUNTPAID, 0)) > 0
                                 AND DT.CASHFLOWTYPE = '1'
                         ) CFT
                         INNER JOIN COMPANY C ON C.ID = CFT.COMPANY
                         INNER JOIN BUSINESSUNIT BS
                            ON BS.ID = CFT.BUSINESSUNIT AND BS.COMPANY = CFT.COMPANY
                         LEFT JOIN SUPPLIER S ON S.ID = CFT.VENDOR
                         LEFT JOIN
                         (SELECT CFT.ID, FF.ID AS FORECASTID
                            FROM CF_TRANSACTION CFT
                                 INNER JOIN FORECAST_FIX FF ON FF.CFTRANSID = CFT.ID
                                 INNER JOIN (SELECT ID, COMPANY, CURRENCY
                                               FROM CF_TRANSACTION
                                              WHERE COMPANY = '$COMPANY') cftt
                                    ON (cftt.id = ff.cftransid)
                           WHERE CONCAT (CFT.ID, FF.ID) IN (" . $param['DTPAY'] . ")
                          UNION ALL
                          SELECT CFT.ID, NULL AS FORECASTID
                            FROM CF_TRANSACTION CFT
                                 LEFT JOIN
                                 (  SELECT FF.CFTRANSID,
                                           SUM (
                                              DECODE (FF.ISACTIVE,
                                                      0, FF.AMOUNTREQUEST,
                                                      FF.AMOUNTADJS))
                                              AS AMOUNTFORECAST
                                      FROM FORECAST_FIX FF
                                           INNER JOIN
                                           (SELECT ID, COMPANY, CURRENCY
                                              FROM CF_TRANSACTION
                                             WHERE COMPANY = '$COMPANY') cftt
                                              ON (cftt.id = ff.cftransid)
                                  GROUP BY FF.CFTRANSID) FF
                                    ON FF.CFTRANSID = CFT.ID
                           WHERE     (  CFT.AMOUNT_INCLUDE_VAT
                                      - NVL (CFT.AMOUNT_PPH, 0)
                                      - NVL (FF.AMOUNTFORECAST, 0)) > 0
                                 AND CFT.ID IN (" . $param['DTPAY'] . ")) PD
                            ON CONCAT (PD.ID, PD.FORECASTID) = CONCAT (CFT.ID, CFT.FORECASTID)
                   WHERE     CFT.DATABY LIKE '$DATABY'
                         AND CFT.CURRENCY = '$CURRENCY'
                         AND CFT.DOCNUMBER NOT LIKE 'TMPINV%'
                         AND CFT.AMOUNTOS > 0
                         AND TO_CHAR(CFT.FILTERDATE,'YYYY/MM') BETWEEN '$DATEFROM' AND '$DATETO'
                ORDER BY C.COMPANYNAME, CFT.DOCREF";
        // $ParamW = [
        //     $param['COMPANY'], $param['COMPANY'], $param['COMPANY'], $param['COMPANY'], $param['USERNAME'], $param['CASHFLOWTYPE'],
        //     $param['COMPANY'], $param['COMPANY'], $param['COMPANY'], $param['USERNAME'], $param['CASHFLOWTYPE'],
        //     $DATABY, $param['CURRENCY']
        // ];
                // var_dump($SQL);exit();
        $result = $this->db->query($SQL)->result();
        
        $this->db->close();
        return $result;
    }

    public function DtOsPayment1old($param) {
        $DATABY = "%" . $param["DATABY"] . "%";
        if ($param['DTPAY'] == '' || $param['DTPAY'] == NULL) {
            $param['DTPAY'] = "''";
        }
        $COMPANY = $param['COMPANY'];
        $USERNAME = $param['USERNAME'];
        $CASHFLOWTYPE = $param['CASHFLOWTYPE'];
        $CURRENCY = $param['CURRENCY'];

        $SQL = "SELECT CFT.*, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME,
                        S.FCNAME AS VENDORNAME
                   FROM (SELECT CFT.*, DT.CASHFLOWTYPE, FF.ID AS FORECASTID, (FF.AMOUNTADJS - NVL(PAY.AMOUNTPAID, 0)) AS AMOUNTFORECAST,  case
                    when cft.AMOUNTINVOICE = NVL (PAY.AMOUNTPAID, 0) then 0
                    else (FF.AMOUNTADJS - NVL (PAY.AMOUNTPAID, 0))
                    end AS AMOUNTOS,
                                FF.YEAR, FF.MONTH, FF.WEEK, FF.PRIORITY, '100001' AS DATABY, 'Forecast' AS DATABYNAME
                           FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR,
                                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                        CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY,CFT.INVOICEVENDORNO
                                   FROM $this->CF_TRANSACTION CFT
                                  INNER JOIN $this->CF_TRANSACTION CFPO
                                          ON CFPO.DOCNUMBER = CFT.DOCREF
                                         AND CFPO.COMPANY = CFT.COMPANY
                                  WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                    AND CFT.COMPANY = '$COMPANY' AND CFT.company || cft.docref || TO_CHAR (cft.DOCDATE, 'yyyy') NOT IN
                        (SELECT DISTINCT
                                   CFT.COMPANY
                                || CFT.DOCREF
                                || TO_CHAR (CFT.DOCDATE, 'yyyy')
                                   DOCDATE
                           FROM FORECAST_FIX FF
                                INNER JOIN CF_TRANSACTION CFT
                                   ON CFT.ID = FF.CFTRANSID
                          WHERE CFT.DOCNUMBER LIKE 'TMPINV%' AND CFT.COMPANY = '$COMPANY')
                                  UNION ALL
                                 SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                        CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY,CFT.INVOICEVENDORNO
                                   FROM $this->CF_TRANSACTION CFT
                                  WHERE CFT.DOCTYPE IN ('PDO','INV_AP_SPC','INV_AR_SPC')
                                    AND CFT.COMPANY = '$COMPANY') CFT
                          INNER JOIN $this->FORECAST_FIX FF
                                  ON FF.CFTRANSID = CFT.ID
                                 AND FF.ISACTIVE = 2
                          INNER JOIN
                         (SELECT ID, COMPANY, CURRENCY
                            FROM CF_TRANSACTION
                           WHERE COMPANY = '$COMPANY' ) cftt
                            ON (cftt.id = ff.cftransid)
                          INNER JOIN $this->DOCTYPE DT
                                  ON DT.FCCODE = CFT.DOCTYPE
                           LEFT JOIN (SELECT P.FORECASTID, SUM(P.AMOUNT) AS AMOUNTPAID
                                        FROM $this->PAYMENT P
                                       GROUP BY P.FORECASTID) PAY
                                  ON PAY.FORECASTID = FF.ID
                          INNER JOIN $this->USER_DEPART UD
                                  ON UD.FCCODE = '$USERNAME'
                                 AND UD.DEPARTMENT = CFT.DEPARTMENT
                          WHERE (FF.AMOUNTADJS - NVL(PAY.AMOUNTPAID, 0)) > 0
                            AND DT.CASHFLOWTYPE = '$CASHFLOWTYPE'
                          UNION ALL
                         SELECT CFT.*, DT.CASHFLOWTYPE, NULL AS FORECASTID, (CFT.AMOUNTINVOICE - NVL(FF.AMOUNTFORECAST, 0) - NVL(PAY.AMOUNTPAID, 0)) AS AMOUNTFORECAST, (CFT.AMOUNTINVOICE - NVL(FF.AMOUNTFORECAST, 0) - NVL(PAY.AMOUNTPAID, 0)) AS AMOUNTOS,
                                NULL, NULL, NULL, NULL, '100002' AS DATABY, 'Non Forecast' AS DATABYNAME
                           FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR,
                                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                        CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY,CFT.INVOICEVENDORNO
                                   FROM $this->CF_TRANSACTION CFT
                                  INNER JOIN $this->CF_TRANSACTION CFPO
                                          ON CFPO.DOCNUMBER = CFT.DOCREF
                                         AND CFPO.COMPANY = CFT.COMPANY
                                  WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                    AND CFT.COMPANY = '$COMPANY'
                                  UNION ALL
                                 SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                        CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY,CFT.INVOICEVENDORNO
                                   FROM $this->CF_TRANSACTION CFT
                                  WHERE CFT.DOCTYPE IN ('PDO','INV_AP_SPC','INV_AR_SPC')
                                    AND CFT.COMPANY = '$COMPANY') CFT
                           LEFT JOIN (SELECT FF.CFTRANSID, SUM(DECODE(FF.ISACTIVE, 0,  FF.AMOUNTREQUEST, FF.AMOUNTADJS)) AS AMOUNTFORECAST
                                        FROM $this->FORECAST_FIX FF
                                        INNER JOIN
                                       (SELECT ID, COMPANY, CURRENCY
                                          FROM CF_TRANSACTION
                                         WHERE COMPANY = '$COMPANY')
                                       cftt
                                          ON (cftt.id = ff.cftransid)
                                       GROUP BY FF.CFTRANSID) FF
                                  ON FF.CFTRANSID = CFT.ID
                          INNER JOIN DOCTYPE DT
                                  ON DT.FCCODE = CFT.DOCTYPE
                           LEFT JOIN (SELECT P.CFTRANSID, SUM(P.AMOUNT) AS AMOUNTPAID
                                        FROM $this->PAYMENT P
                                       WHERE P.FORECASTID IS NULL
                                       GROUP BY P.CFTRANSID) PAY
                                  ON PAY.CFTRANSID = CFT.ID
                          INNER JOIN $this->USER_DEPART UD
                                  ON UD.FCCODE = '$USERNAME'
                                 AND UD.DEPARTMENT = CFT.DEPARTMENT
                          WHERE (CFT.AMOUNTINVOICE - NVL(FF.AMOUNTFORECAST, 0) - NVL(PAY.AMOUNTPAID, 0)) > 0
                            AND DT.CASHFLOWTYPE = '$CASHFLOWTYPE') CFT
                  INNER JOIN $this->COMPANY C 
                          ON C.ID = CFT.COMPANY
                  INNER JOIN $this->BUSINESSUNIT BS
                          ON BS.ID = CFT.BUSINESSUNIT
                         AND BS.COMPANY = CFT.COMPANY
                   LEFT JOIN $this->SUPPLIER S
                          ON S.ID = CFT.VENDOR
                   LEFT JOIN (SELECT CFT.ID, FF.ID AS FORECASTID
                                FROM $this->CF_TRANSACTION CFT
                               INNER JOIN $this->FORECAST_FIX FF
                                       ON FF.CFTRANSID = CFT.ID
                            INNER JOIN
                             (SELECT ID, COMPANY, CURRENCY
                                FROM CF_TRANSACTION
                               WHERE COMPANY = '$COMPANY' )
                             cftt
                                ON (cftt.id = ff.cftransid)
                               WHERE CONCAT(CFT.ID, FF.ID) IN (" . $param['DTPAY'] . ")
                               UNION ALL
                              SELECT CFT.ID, NULL AS FORECASTID
                                FROM $this->CF_TRANSACTION CFT
                                LEFT JOIN (SELECT FF.CFTRANSID, SUM(DECODE(FF.ISACTIVE, 0,  FF.AMOUNTREQUEST, FF.AMOUNTADJS)) AS AMOUNTFORECAST
                                             FROM $this->FORECAST_FIX FF
                                             INNER JOIN
                                               (SELECT ID, COMPANY, CURRENCY
                                                  FROM CF_TRANSACTION
                                                 WHERE     COMPANY = '$COMPANY' ) cftt
                                                  ON (cftt.id = ff.cftransid)
                                            GROUP BY FF.CFTRANSID) FF
                                       ON FF.CFTRANSID = CFT.ID
                               WHERE (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0) - NVL(FF.AMOUNTFORECAST, 0)) > 0
                                 AND CFT.ID IN (" . $param['DTPAY'] . ")) PD
                          ON CONCAT(PD.ID, PD.FORECASTID) = CONCAT(CFT.ID, CFT.FORECASTID)
                  WHERE CFT.DATABY LIKE '$DATABY'
                    AND CFT.CURRENCY = '$CURRENCY' 
                  ORDER BY C.COMPANYNAME, CFT.DOCREF";
        // $ParamW = [
        //     $param['COMPANY'], $param['COMPANY'], $param['COMPANY'], $param['COMPANY'], $param['USERNAME'], $param['CASHFLOWTYPE'],
        //     $param['COMPANY'], $param['COMPANY'], $param['COMPANY'], $param['USERNAME'], $param['CASHFLOWTYPE'],
        //     $DATABY, $param['CURRENCY']
        // ];
        $result = $this->db->query($SQL)->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function SavePayment($Data, $Location) {
        // echo "<pre>";
        // print_r ($Data);
        // echo "</pre>";exit;
        $this->db->select('COMPANYCODE');
        $getCompany = $this->db->get_where('COMPANY',[ 'ID' => $Data['COMPANY']])->row()->COMPANYCODE;
        $this->db->select('COMPANYCODE');
        $getUser = $this->db->get_where('USER_COMPANY_TAB',[ 'USERCODE' => $Data['USERNAME']])->num_rows();
        // var_dump($getUser);exit;
        if($getUser > 0){
            $getUserCompany = $this->db->get_where('USER_COMPANY_TAB',[ 'USERCODE' => $Data['USERNAME'], 'COMPANYCODE' => $getCompany])->num_rows();
            if($getUserCompany == 0){
                throw new Exception("Company tidak terdaftar di user");
            }
        }

        try {
            $this->db->trans_begin();
            $result = FALSE;
            $dt = [
                "CASHFLOWTYPE" => $Data['CASHFLOWTYPE'],
                "BANKCODE" => $Data['BANKCODE'],
                "VOUCHERNO" => $Data['VOUCHERNO'],
                "NOCEKGIRO" => $Data['NOCEKGIRO'],
//                "DATERELEASE = TO_DATE('" . $Data['DATERELEASE'] . "','mm/dd/yyyy')" => NULL,
                "ISACTIVE" => "1",
                "FCENTRY" => $Data['USERNAME'],
                "FCEDIT" => $Data['USERNAME'],
//                "LASTUPDATE = SYSDATE" => NULL,
                "FCIP" => $Location
            ];
//            Cek Period
            $SQL = "SELECT CURRENTACCOUNTINGYEAR, CURRENTACCOUNTINGPERIOD
                      FROM PAYMENT_PERIODCONTROL 
                     WHERE COMPANY = ?";
            $cekPeriod = $this->db->query($SQL, [$Data["COMPANY"]])->result();
            $PERIOD = "";
            $YEAR = "";
            $MONTH = "";
            if (count($cekPeriod) > 0) {
                foreach ($cekPeriod as $value) {
                    $PERIOD = $value->CURRENTACCOUNTINGYEAR . substr("0" . $value->CURRENTACCOUNTINGPERIOD, -2);
                    $YEAR = $value->CURRENTACCOUNTINGYEAR;
                    $MONTH = $value->CURRENTACCOUNTINGPERIOD;
                }
                $PERIODT = explode('/', $Data["DATERELEASE"]);
                $PERIODV = $PERIODT[2] . $PERIODT[0];
                // if (intval($PERIOD) > intval($PERIODV)) {
                //     throw new Exception("Period is Over !!!");
                // }
            } else {
                throw new Exception("Period Balance Not Set !!!");
            }
//            Cek Amount Bank
            if ($Data['CASHFLOWTYPE'] == "1") {
               // $AMOUNTNOW = $this->CekBankAmount($PERIOD, $YEAR, $MONTH, $Data["BANKCODE"]);
                //if (floatval($AMOUNTNOW) < floatval($Data["AMOUNTPAID"])) {
                 //   throw new Exception("Insufficient Balance!!!");
                //}
            }
            $Dat = [];
            foreach ($Data["DtPaid"] as $values) {
                $dt["FORECASTID"] = $values["FORECASTID"];
                $dt["CFTRANSID"] = $values["ID"];
                $dt["AMOUNT"] = $values["AMOUNTPAID"];
                $dt["RATE"] = $Data["RATE"];
                $dt["AMOUNTBANK"] = $Data["RATE"] * $values["AMOUNTPAID"];//$values["AMOUNTCONVERSI"];
                $dt["REMARK"] = $values["REMARK"];
                $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                ->set('DATERELEASE', "TO_DATE('" . $Data['DATERELEASE'] . "','mm/dd/yyyy')", false)
                                ->set($dt)->insert($this->PAYMENT);
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

    public function ShowDataPaidval($param) {
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        if($param['MONTH'] < 10){
            $param['MONTH'] = '0'.$param['MONTH'];
        }

        $MONTH = $param['MONTH'];
        $YEAR  = $param['YEAR'];

        $SQL = "(SELECT PY.PAYMENTID, PY.FORECASTID, PY.CFTRANSID, TO_CHAR(PY.DATERELEASE, 'MM/DD/YYYY') AS DATERELEASE, PY.BANKCODE, PY.VOUCHERNO, PY.NOCEKGIRO, PY.CASHFLOWTYPE, PY.REMARK, PY.AMOUNT AS AMOUNTPAY,
                       CFT.DOCNUMBER, CFT.DOCREF,CFT.INVOICEVENDORNO, CFT.AMOUNTINVOICE, CFT.COMPANY, CFT.COMPANYCODE, CFT.COMPANYNAME, CFT.BUSINESSUNIT, CFT.BUSINESSUNITCODE, CFT.BUSINESSUNITNAME, 
                       CFT.VENDOR, CFT.VENDORNAME, CFT.DEPARTMENT, B.FCNAME AS BANKNAME, B.BANKACCOUNT, CASE WHEN PPC.CURRENTACCOUNTINGYEAR = '$YEAR' AND PPC.CURRENTACCOUNTINGPERIOD = '$MONTH' THEN 1 ELSE 0 END AS STATUS
                  FROM $this->PAYMENT PY
                 INNER JOIN (SELECT CFT.*, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME
                               FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR,
                                            TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                            CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,CFT.INVOICEVENDORNO
                                       FROM $this->CF_TRANSACTION CFT
                                      INNER JOIN $this->CF_TRANSACTION CFPO
                                              ON CFPO.DOCNUMBER = CFT.DOCREF
                                             AND CFPO.COMPANY = CFT.COMPANY
                                      WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                      UNION ALL
                                     SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                            TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                            CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,CFT.INVOICEVENDORNO
                                       FROM $this->CF_TRANSACTION CFT
                                      WHERE CFT.DOCTYPE in('PDO','INV_AP_SPC')) CFT 
                              INNER JOIN $this->COMPANY C 
                                      ON C.ID = CFT.COMPANY
                              INNER JOIN $this->BUSINESSUNIT BS
                                      ON BS.ID = CFT.BUSINESSUNIT
                                     AND BS.COMPANY = CFT.COMPANY
                               LEFT JOIN $this->SUPPLIER S
                                      ON S.ID = CFT.VENDOR
                            ) CFT
                         ON CFT.ID = PY.CFTRANSID
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = CFT.DEPARTMENT
                 INNER JOIN BANK B
                         ON B.FCCODE = PY.BANKCODE
                INNER JOIN PAYMENT_PERIODCONTROL PPC ON PPC.COMPANY = CFT.COMPANY
                 WHERE TO_CHAR (PY.DATERELEASE, 'YYYY') = '".$param['YEAR']."' AND TO_CHAR (PY.DATERELEASE, 'MM') = '".$param['MONTH']."'";
        $ParamW = [$param['USERNAME']];
        if ($param["DEPARTMENT"] != "" && $param["DEPARTMENT"] != NULL) {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            array_push($ParamW, $param["DEPARTMENT"]);
        }
        if ($param["CASHFLOWTYPE"] != "" && $param["CASHFLOWTYPE"] != NULL) {
            $SQL .= " AND PY.CASHFLOWTYPE = ?";
            array_push($ParamW, $param["CASHFLOWTYPE"]);
        }
        $SQL .= "  ORDER BY PY.DATERELEASE, PY.VOUCHERNO, CFT.DEPARTMENT, CFT.COMPANYNAME, CFT.DOCNUMBER)";
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
//            $SQLO = " ORDER BY FC.DATERELEASE, FC.VOUCHERNO, FC.DEPARTMENT, FC.COMPANYNAME, FC.DOCNUMBER";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $SQL FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY", $ParamW)->result();
        // var_dump($this->db->last_query());exit;
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $SQL FC $SQLW", $ParamW)->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $SQL FC", $ParamW)->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    public function ShowDataPaid($param) {
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        if($param['MONTH'] < 10){
            $param['MONTH'] = '0'.$param['MONTH'];
        }

        $SQL = "(SELECT PY.PAYMENTID, PY.FORECASTID, PY.CFTRANSID, TO_CHAR(PY.DATERELEASE, 'MM/DD/YYYY') AS DATERELEASE, PY.BANKCODE, PY.VOUCHERNO, PY.NOCEKGIRO, PY.CASHFLOWTYPE, PY.REMARK, PY.AMOUNT AS AMOUNTPAY,
                       CFT.DOCNUMBER, CFT.DOCREF,CFT.INVOICEVENDORNO, CFT.AMOUNTINVOICE, CFT.COMPANY, CFT.COMPANYCODE, CFT.COMPANYNAME, CFT.BUSINESSUNIT, CFT.BUSINESSUNITCODE, CFT.BUSINESSUNITNAME, 
                       CFT.VENDOR, CFT.VENDORNAME, CFT.DEPARTMENT, B.FCNAME AS BANKNAME, B.BANKACCOUNT
                  FROM $this->PAYMENT PY
                 INNER JOIN (SELECT CFT.*, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME
                               FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR,
                                            TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                            CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,CFT.INVOICEVENDORNO
                                       FROM $this->CF_TRANSACTION CFT
                                      INNER JOIN $this->CF_TRANSACTION CFPO
                                              ON CFPO.DOCNUMBER = CFT.DOCREF
                                             AND CFPO.COMPANY = CFT.COMPANY
                                      WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                      UNION ALL
                                     SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                            TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINVOICE, 
                                            CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,CFT.INVOICEVENDORNO
                                       FROM $this->CF_TRANSACTION CFT
                                      WHERE CFT.DOCTYPE in('PDO','INV_AP_SPC')) CFT 
                              INNER JOIN $this->COMPANY C 
                                      ON C.ID = CFT.COMPANY
                              INNER JOIN $this->BUSINESSUNIT BS
                                      ON BS.ID = CFT.BUSINESSUNIT
                                     AND BS.COMPANY = CFT.COMPANY
                               LEFT JOIN $this->SUPPLIER S
                                      ON S.ID = CFT.VENDOR
                            ) CFT
                         ON CFT.ID = PY.CFTRANSID
                 INNER JOIN BANK B
                         ON B.FCCODE = PY.BANKCODE
                WHERE TO_CHAR(PY.DATERELEASE, 'YYYYMMDD') BETWEEN ? AND ?";
                $ParamW = [$param['DATEFROM'], $param['DATETO']];
        // INNER JOIN $this->USER_DEPART UD ON UD.FCCODE = ? AND UD.DEPARTMENT = CFT.DEPARTMENT $ParamW = [$param['USERNAME'], $param['DATEFROM'], $param['DATETO']];
        if ($param["DEPARTMENT"] != "" && $param["DEPARTMENT"] != NULL) {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            array_push($ParamW, $param["DEPARTMENT"]);
        }
        if ($param["CASHFLOWTYPE"] != "" && $param["CASHFLOWTYPE"] != NULL) {
            $SQL .= " AND PY.CASHFLOWTYPE = ?";
            array_push($ParamW, $param["CASHFLOWTYPE"]);
        }
        $SQL .= "  ORDER BY PY.DATERELEASE, PY.VOUCHERNO, CFT.DEPARTMENT, CFT.COMPANYNAME, CFT.DOCNUMBER)";
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
//            $SQLO = " ORDER BY FC.DATERELEASE, FC.VOUCHERNO, FC.DEPARTMENT, FC.COMPANYNAME, FC.DOCNUMBER";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $SQL FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY", $ParamW)->result();
        // var_dump($this->db->last_query());exit;
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $SQL FC $SQLW", $ParamW)->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $SQL FC", $ParamW)->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    public function EditPayment($Data, $Location) {

        $this->db->select('COMPANYCODE');
        $getCompany = $this->db->get_where('COMPANY',[ 'ID' => $Data['COMPANY']])->row()->COMPANYCODE;
        $this->db->select('COMPANYCODE');
        $getUser = $this->db->get_where('USER_COMPANY_TAB',[ 'USERCODE' => $Data['USERNAME']])->num_rows();
        if($getUser > 0){
            $getUserCompany = $this->db->get_where('USER_COMPANY_TAB',[ 'USERCODE' => $Data['USERNAME'], 'COMPANYCODE' => $getCompany])->num_rows();
            if($getUserCompany == 0){
                throw new Exception("Company tidak terdaftar di user");
            }
        }

        try {
            $this->db->trans_begin();
            $result = FALSE;
//            Cek Period
            $SQL = "SELECT CURRENTACCOUNTINGYEAR, CURRENTACCOUNTINGPERIOD
                      FROM PAYMENT_PERIODCONTROL 
                     WHERE COMPANY = ?";
            $cekPeriod = $this->db->query($SQL, [$Data["COMPANY"]])->result();
            $PERIOD = "";
            $YEAR = "";
            $MONTH = "";
            if (count($cekPeriod) > 0) {
                foreach ($cekPeriod as $value) {
                    $PERIOD = $value->CURRENTACCOUNTINGYEAR . substr("0" . $value->CURRENTACCOUNTINGPERIOD, -2);
                    $YEAR = $value->CURRENTACCOUNTINGYEAR;
                    $MONTH = $value->CURRENTACCOUNTINGPERIOD;
                }
                $PERIODT = explode('/', $Data["DATERELEASE"]);
                $PERIODV = $PERIODT[2] . $PERIODT[0];
                // if (intval($PERIOD) > intval($PERIODV)) {
                //     throw new Exception("Period is Over !!!");
                // }
            } else {
                throw new Exception("Period Balance Not Set !!!");
            }
//            Cek Amount Bank
            if ($Data['CASHFLOWTYPE'] == "1") {
                //$AMOUNTNOW = $this->CekBankAmount($PERIOD, $YEAR, $MONTH, $Data["BANKCODE"]);
                //if ((floatval($AMOUNTNOW) + floatval($Data["AMOUNTPAIDOLD"])) < floatval($Data["AMOUNTPAID"])) {
                //    throw new Exception("Insufficient Balance!!!");
                //}
            }
            $dt = [
                "BANKCODE" => $Data['BANKCODE'],
                "VOUCHERNO" => $Data['VOUCHERNO'],
                "NOCEKGIRO" => $Data['NOCEKGIRO'],
                "REMARK" => $Data['REMARK'],
                "AMOUNT" => $Data['AMOUNTPAID'],
                "AMOUNTBANK" => $Data['AMOUNTPAID'],
                "FCEDIT" => $Data['USERNAME'],
                "FCIP" => $Location
            ];
            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('DATERELEASE', "TO_DATE('" . $Data['DATERELEASE'] . "','mm/dd/yyyy')", false)
                    ->set($dt)->where(['PAYMENTID' => $Data['PAYMENTID']])
                    ->update($this->PAYMENT);
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

    public function DeletePayment($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
//            Cek Period
            $SQL = "SELECT CURRENTACCOUNTINGYEAR, CURRENTACCOUNTINGPERIOD
                      FROM PAYMENT_PERIODCONTROL 
                     WHERE COMPANY = ?";
            $cekPeriod = $this->db->query($SQL, [$Data["COMPANY"]])->result();
            $PERIOD = "";
            $YEAR = "";
            $MONTH = "";
            if (count($cekPeriod) > 0) {
                foreach ($cekPeriod as $value) {
                    $PERIOD = $value->CURRENTACCOUNTINGYEAR . substr("0" . $value->CURRENTACCOUNTINGPERIOD, -2);
                    $YEAR = $value->CURRENTACCOUNTINGYEAR;
                    $MONTH = $value->CURRENTACCOUNTINGPERIOD;
                }
                $PERIODT = explode('/', $Data["DATERELEASE"]);
                $PERIODV = $PERIODT[2] . $PERIODT[0];
                // if (intval($PERIOD) > intval($PERIODV)) {
                //     throw new Exception("Period is Over !!!");
                // }
            } else {
                throw new Exception("Period Balance Not Set !!!");
            }
            $result = $this->db->delete($this->PAYMENT, ['PAYMENTID' => $Data['PAYMENTID']]);
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

    public function Export($Data) {
        try {
            $SQL = "SELECT CFN.ID, CFN.DEPARTMENT, DP.FCNAME AS DEPARTMENTNAME, CFN.COMPANY, C.COMPANYCODE, C.COMPANYNAME, CFN.BUSINESSUNIT, BS.FCCODE AS BUSINESSUNITCODE, 
                           BS.FCNAME AS BUSINESSUNITNAME, CFN.DOCTYPE, CFN.DOCNUMBER, TO_CHAR(CFN.DOCDATE,'MM/DD/YYYY') AS DOCDATE, CFN.VENDOR, S.FCNAME AS VENDORNAME, CFN.CURRENCY, 
                           CFN.AMOUNT_INCLUDE_VAT, CFN.AMOUNT_PPH, CFN.VAT, CFN.TOTAL_BAYAR
                      FROM $this->CF_TRANSACTION CFN
                      LEFT JOIN $this->DEPARTMENT DP
                             ON DP.FCCODE = CFN.DEPARTMENT
                    INNER JOIN $this->COMPANY C 
                            ON C.ID = CFN.COMPANY
                    INNER JOIN $this->BUSINESSUNIT BS
                            ON BS.ID = CFN.BUSINESSUNIT
                           AND BS.COMPANY = CFN.COMPANY
                     LEFT JOIN $this->SUPPLIER S
                            ON S.ID = CFN.VENDOR
                    INNER JOIN $this->USER_DEPART UD
                            ON UD.FCCODE = ?
                           AND UD.DEPARTMENT = CFN.DEPARTMENT
                    WHERE (CFN.DOCTYPE <> 'INV' AND CFN.DOCTYPE <> 'INV_AR')
                      AND CFN.ISACTIVE = 'TRUE'
                      AND TO_CHAR(CFN.DOCDATE, 'YYYYMMDD') BETWEEN ? AND ?";
            $ParamW = [$Data["USERNAME"], $Data["DOCDATEFROM"], $Data["DOCDATETO"]];
            if ($Data["DEPARTMENT"] == "ALL") {
                $FDepartment = "Department : All Department";
            } else {
                $FDepartment = "Department : " . $Data["DEPARTMENT"];
                $SQL .= " AND CFN.DEPARTMENT = ?";
                array_push($ParamW, $Data["DEPARTMENT"]);
            }
            $SQL .= " ORDER BY CFN.DEPARTMENT, CFN.DOCTYPE, CFN.DOCDATE, C.COMPANYNAME, CFN.DOCNUMBER, BS.FCNAME";
            $result = $this->db->query($SQL, $ParamW)->result();

            $FRange = "Document Date Range : " . Carbon::parse($Data["DOCDATEFROM"])->format('d-M-Y') . " s/d " . Carbon::parse($Data["DOCDATETO"])->format('d-M-Y');
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("IT GAMA")
                    ->setLastModifiedBy("IT GAMA")
                    ->setTitle("Report Entry Data")
                    ->setSubject("Report Entry Data")
                    ->setDescription("Data Document in System $FDepartment, $FRange, $GExport")
                    ->setKeywords("Report Entry Data")
                    ->setCategory("Report Entry Data");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Data');
            $i = 1;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

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
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FRange);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $GExport);
            $i++;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':M' . ($i + 1))->applyFromArray([
                'font' => $StyleBold, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
            ]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'NO');
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':A' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'DEPARMNET');
            $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':B' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'DOC TYPE');
            $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':C' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'COMPANY');
            $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':D' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'BUSINESS UNIT');
            $objPHPExcel->getActiveSheet()->mergeCells('E' . $i . ':E' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'DOC NUMBER');
            $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':F' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'DOC DATE');
            $objPHPExcel->getActiveSheet()->mergeCells('G' . $i . ':G' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'CURRENCY');
            $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':H' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'VENDOR');
            $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':I' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'AMOUNT');
            $objPHPExcel->getActiveSheet()->mergeCells('J' . $i . ':M' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'INVOICE');
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'FORECASH');
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'PAID');
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'OS');
            $i++;

            if (count($result) > 0) {
                $iDtAwal = $i;
                $No = 1;
                foreach ($result as $values) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $No);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $values->DEPARTMENTNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $values->DOCTYPE);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $values->COMPANYNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $values->BUSINESSUNITNAME);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, $values->DOCNUMBER, DataType::TYPE_STRING);
                    $DtDate = explode('/', $values->DOCDATE);
                    $XlsTime = gmmktime(0, 0, 0, intval($DtDate[0]), intval($DtDate[1]), intval($DtDate[2]));
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, Date::PHPToExcel($XlsTime));
                    $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $values->CURRENCY);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $values->VENDORNAME);
                    $ASOURCE = $values->AMOUNT_INCLUDE_VAT;
                    if ($values->VAT == '1') {
                        $ASOURCE = $values->AMOUNT_INCLUDE_VAT * 100 / 110;
                    }
                    $APPN = $values->AMOUNT_INCLUDE_VAT - $ASOURCE;
                    $ATOTAL = $values->AMOUNT_INCLUDE_VAT - $values->AMOUNT_PPH;
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $ASOURCE);
                    $objPHPExcel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $APPN);
                    $objPHPExcel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $values->AMOUNT_PPH);
                    $objPHPExcel->getActiveSheet()->getStyle('L' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, "=J" . $i . " + K" . $i . " - L" . $i);
                    $objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $i++;
                    $No++;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A' . $iDtAwal . ':M' . ($i - 1))->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                $objPHPExcel->getActiveSheet()->getStyle('G' . $iDtAwal . ':G' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "No Data");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':M' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':M' . $i)->applyFromArray([
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


    public function SearchOtherVoucher($param) {

        $MONTH = $param['MONTH'];
        $YEAR  = $param['YEAR'];
        $COMPANY = $param['COMPANY'];

        if($MONTH < 10){
            $MONTH = '0'.$MONTH;
        }

        $WHERE = '';
        // if($COMPANY != null){
        //     $WHERE .= " AND PO.COMPANY = '$COMPANY'";
        // INNER JOIN PAYMENT_PERIODCONTROL PPC ON PPC.COMPANY = PO.COMPANY
        // , CASE WHEN PPC.CURRENTACCOUNTINGYEAR = '$YEAR' AND PPC.CURRENTACCOUNTINGPERIOD = '$MONTH' THEN 1 ELSE 0 END AS STATUS
        // }

        $SQL = "SELECT PO.PAYMENTID, PO.BANKCODE, PO.VOUCHERNO, PO.NOCEKGIRO,
                                    PO.REMARKS, PO.AMOUNT, PO.ISACTIVE, PO.FCENTRY, PO.FCEDIT, PO.FCIP,
                                    PO.LASTUPDATE, TO_CHAR(PO.DATERELEASE,'MM/DD/YYYY') DATERELEASE,
                                    PO.COMPANY, C.COMPANYNAME, PO.VENDOR, PO.CASHFLOWTYPE, PO.TRANSTYPE, DECODE(PO.CASHFLOWTYPE,0,'Receive','Payment') CASHFLOWTYPENAME, SP.FCNAME VENDORNAME, 
                                    BK.FCNAME AS BANKNAME, BK.BANKACCOUNT,  BK.CURRENCY, PO.EXTSYS, MATERIAL.FCCODE MATERIALCODE, MATERIAL.FCNAME MATERIALNAME, PO.MATERIAL AS POMATERIALID
                                    FROM
                                    PAYMENT_OTHER PO
                                    LEFT JOIN SUPPLIER SP ON  SP.ID = PO.VENDOR
                                    INNER JOIN BANK BK ON BK.FCCODE = PO.BANKCODE
                                    INNER JOIN COMPANY C ON C.ID = PO.COMPANY
                                    INNER JOIN MATERIAL ON PO.MATERIAL = MATERIAL.ID 
                                     WHERE TO_CHAR(PO.DATERELEASE,'mm') = '$MONTH' AND TO_CHAR(PO.DATERELEASE,'yyyy') = '$YEAR'".$WHERE;
        $query = $this->db->query($SQL);
        // var_dump($this->db->last_query());exit;
        return $query->result();
    }


    // public function SearchOtherVoucher($param) {

    //     $q  = "SELECT PO.PAYMENTID, PO.BANKCODE, PO.VOUCHERNO, PO.NOCEKGIRO, PO.REMARKS, PO.AMOUNT, PO.ISACTIVE, PO.FCENTRY, PO.FCEDIT, PO.FCIP, PO.LASTUPDATE, ";
    //     $q .=" TO_CHAR(PO.DATERELEASE,'MM/DD/YYYY') DATERELEASE, PO.COMPANY, C.COMPANYNAME, PO.VENDOR, PO.CASHFLOWTYPE, PO.TRANSTYPE, DECODE(PO.CASHFLOWTYPE,0,'Receive','Payment') CASHFLOWTYPENAME, SP.FCNAME VENDORNAME, BK.FCNAME AS BANKNAME, BK.BANKACCOUNT,  BK.CURRENCY, PO.EXTSYS, MATERIAL.FCCODE MATERIALCODE, MATERIAL.FCNAME MATERIALNAME, PO.MATERIAL AS POMATERIALID FROM PAYMENT_OTHER PO INNER JOIN BANK BK ON BK.FCCODE = PO.BANKCODE INNER JOIN COMPANY C ON C.ID = PO.COMPANY INNER JOIN MATERIAL ON PO.MATERIAL = MATERIAL.ID LEFT JOIN SUPPLIER SP ON SP.ID = PO.VENDOR ";
    //     $query = $this->db->query($q)->result();
    //     // var_dump($this->db->last_query());exit;
    //     return $query;
    // }

    public function SaveOtherPayment($Data, $Location) {



        $this->db->select('COMPANYCODE');
        $getCompany = $this->db->get_where('COMPANY',[ 'ID' => $Data['COMPANY']])->row()->COMPANYCODE;
        $this->db->select('COMPANYCODE');
        $getUser = $this->db->get_where('USER_COMPANY_TAB',[ 'USERCODE' => $Data['USERNAME']])->num_rows();
        if($getUser > 0){
            $getUserCompany = $this->db->get_where('USER_COMPANY_TAB',[ 'USERCODE' => $Data['USERNAME'], 'COMPANYCODE' => $getCompany])->num_rows();
            if($getUserCompany == 0){
                throw new Exception("Company tidak terdaftar di user");
            }
        }
        
        try {
            $this->db->trans_begin();
            $result = FALSE;

            if($Data['ACTION'] == 'ADD'){

                $checkVoc = "SELECT COMPANY, VOUCHERNO FROM PAYMENT_OTHER WHERE VOUCHERNO = '".$Data['VOUCHERNO']."' AND COMPANY = '".$Data['COMPANY']."'";
                $checkVoc = $this->db->query($checkVoc)->num_rows();
                if($checkVoc > 0){
                    throw new Exception("Duplicate VoucherNo");
                }
            }

            $dt = [
                'BANKCODE' => $Data['BANKCODE'],
                // 'VOUCHERNO' => $Data['VOUCHERNO'],
                'NOCEKGIRO' => $Data['NOCEKGIRO'],
                'REMARKS' => $Data['REMARK'],
                'AMOUNT' => $Data['AMOUNT'],
                'COMPANY' => $Data['COMPANY'],
                'CASHFLOWTYPE' => $Data['CASHFLOWTYPE'],
                'VENDOR' => $Data['VENDOR'],
                'EXTSYS' => $Data['EXTSYSTEM'],
                'MATERIAL' => $Data['MATERIALCODE'],
                // 'TRANSTYPE' => $Data['TRANSTYPE'],
                'ISACTIVE' => 1,
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];

            // Cek Period
            $SQL = "SELECT CURRENTACCOUNTINGYEAR, CURRENTACCOUNTINGPERIOD
                      FROM PAYMENT_PERIODCONTROL 
                     WHERE COMPANY = ?";
            $cekPeriod = $this->db->query($SQL, [$Data["COMPANY"]])->result();
            $PERIOD = "";
            $YEAR = "";
            $MONTH = "";
            if (count($cekPeriod) > 0) {
                foreach ($cekPeriod as $value) {
                    $PERIOD = $value->CURRENTACCOUNTINGYEAR . substr("0" . $value->CURRENTACCOUNTINGPERIOD, -2);
                    $YEAR = $value->CURRENTACCOUNTINGYEAR;
                    $MONTH = $value->CURRENTACCOUNTINGPERIOD;
                }
                $PERIODT = explode('/', $Data["DATERELEASE"]);
                $PERIODV = $PERIODT[2] . $PERIODT[0];
                // if (intval($PERIOD) > intval($PERIODV)) {
                //     throw new Exception("Period is Over !!!");
                // }
            } else {
                throw new Exception("Period Balance Not Set !!!");
            }

            if($Data['VENDOR'] == '' || $Data['VENDOR'] == null){
                throw new Exception("Vendor Cant Null");
                
            }

            // Cek Amount Bank
            if ($Data['CASHFLOWTYPE'] == "1") {
                //$AMOUNTNOW = $this->CekBankAmount($PERIOD, $YEAR, $MONTH, $Data["BANKCODE"]);
                //if (floatval($AMOUNTNOW) < floatval($Data["AMOUNT"])) {
                //    throw new Exception("Insufficient Balance!!!");
                //}
            }

            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                    ->set('DATERELEASE', "TO_DATE('" . $Data['DATERELEASE'] . "','mm/dd/yyyy')", false);

            if ($Data['PAYMENTID'] != '' && $Data['PAYMENTID'] != null) {
                // $checkVoc = "SELECT COMPANY, VOUCHERNO FROM PAYMENT_OTHER WHERE PAYMENTID = '".$Data['PAYMENTID']."'";
                // $checkVoc = $this->db->query($checkVoc)->row()->VOUCHERNO;

                // $checkVocLagi = "SELECT COMPANY, VOUCHERNO FROM PAYMENT_OTHER WHERE VOUCHERNO = '".$Data['VOUCHERNO']."' AND COMPANY = '".$Data['COMPANY']."'";
                // $checkVocLagi = $this->db->query($checkVocLagi)->num_rows();
                

                // if($checkVoc == $Data['VOUCHERNO']){
                //     throw new Exception("Duplicate VoucherNo");
                // }
                // if($checkVocLagi > 0){
                //     throw new Exception("Duplicate VoucherNo");
                // }
                // else{
                    $dt['VOUCHERNO'] = $Data['VOUCHERNO'];
                    $result = $result->set($dt)
                        ->where(['PAYMENTID' => $Data['PAYMENTID']])
                        ->update($this->PAYMENT_OTHER);    
                // }

                
            } else {
                $dt['FCENTRY'] = $Data['USERNAME'];
                $dt['VOUCHERNO'] = $Data['VOUCHERNO'];
                $result = $result->set($dt)->insert($this->PAYMENT_OTHER);
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

    public function DeleteOtherPayment($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = $this->db->delete($this->PAYMENT_OTHER, ['PAYMENTID' => $Data['PAYMENTID']]);
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

    public function ShowDataPeriod() {
        $this->fillable = ['C.COMPANYNAME',"P.CURRENTDATE","P.CURRENTACCOUNTINGYEAR" ,'P.CURRENTACCOUNTINGPERIOD', 'P.CLOSEACCOUNTINGYEAR', 'P.CLOSEACCOUNTINGPERIOD'];
        $result = $this->db->select($this->fillable)
                        ->from("PAYMENT_PERIODCONTROL P")
                        ->join("COMPANY C", 'C.ID = P.COMPANY', 'left')
                        ->order_by('C.COMPANYNAME ASC')->get()->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function saveUpdatePeriod($param,$location){
        try {
            $this->db->trans_begin();
            $result     = FALSE;

            $COMPANY    = $param['COMPANY'];
            $YEAR       = $param['YEAR'];
            $MONTH      = $param['MONTH'];

            $WHERE = " WHERE SYSTEM = 'BANK' ";
            if($COMPANY != '0'){
                $WHERE .= " AND COMPANY = '".$COMPANY."'";
            }
            // if($MONTH == '1'){
            //     $minMonth = 12;
            //     $minYear  = $YEAR - 1;
            // }else{
            //     $minMonth = $MONTH - 1;
            //     $minYear  = $YEAR;
            // }

            $q = "update PAYMENT_PERIODCONTROL set lastperiodposted = (select last_day(add_months((to_date('$MONTH/$YEAR','mm/yyyy')),-1)) from dual), currentdate = (select trunc(add_months((to_date('$MONTH/$YEAR','mm/yyyy')),-0),'month') from dual), currentaccountingyear = (select to_number('$YEAR') as from dual), currentaccountingperiod = (select to_number('$MONTH') as from dual), closeaccountingyear = ( select case when (select to_number('$MONTH') as from dual) -1 = 0 then (select to_number('$YEAR') as from dual)-1 else (select to_number('$YEAR') as from dual) end as closeaccountingyear from dual ), closeaccountingperiod = ( select case when (select to_number('$MONTH') as from dual) -1 = 0 then 12 else (select to_number('$MONTH') as from dual)-1 end as closeaccountingperiod from dual ) ". $WHERE;
            // $q = "UPDATE PERIODCONTROL SET CURRENTACCOUNTINGYEAR = '".$YEAR."', CURRENTACCOUNTINGPERIOD = '".$MONTH."', CLOSEACCOUNTINGYEAR = '".$minYear."', CLOSEACCOUNTINGPERIOD = '".$minMonth."'".$WHERE;
            // var_dump($q);exit();
            $result1 = $this->db->query($q);

            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
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

    public function saveClosing($data,$address){

        try {
            $this->db->trans_begin();
            $result     = FALSE;

            $COMPANY    = $data['COMPANY'];
            $USERNAME   = $data['USERNAME'];

            $q = "SELECT 
                  a.company, 
                  a.bankcode, 
                  a.period_year, 
                  a.period_month, 
                  a.opening_balance_monthly, 
                  a.debet, 
                  a.credit, 
                  a.saldo, 
                  '', 
                  a.company as company_next, 
                  a.bankcode as bankcode_next, 
                  a.saldo AS opening_balance_monthly_next, 
                  CASE WHEN a.period_month = 12 THEN a.period_year + 1 ELSE a.period_year END AS period_year_next, 
                  CASE WHEN a.period_month = 12 THEN 1 ELSE a.period_month + 1 END AS period_month_next, 
                  0 AS debet_next, 
                  0 AS credit_next, 
                  a.saldo AS ending_balance_monthly_next, 
                  a.currency as currency_next
                FROM 
                  (
                    SELECT 
                      tb_monthly.company, 
                      tb_monthly.bankcode, 
                      tb_monthly.period_year, 
                      tb_monthly.period_month, 
                      tb_monthly.opening_balance_monthly AS opening_balance_monthly, 
                      NVL (pv3.debet, 0) AS debet, 
                      NVL (pv3.credit, 0) AS credit, 
                      (
                        tb_monthly.opening_balance_monthly + NVL (pv3.debet, 0) - NVL (pv3.credit, 0)
                      ) AS saldo, 
                      tb_monthly.currency 
                    FROM 
                      (
                        SELECT 
                          bankcode, 
                          year, 
                          month, 
                          SUM (debet) AS debet, 
                          SUM (credit) AS credit 
                        FROM 
                          (
                            SELECT 
                              bankcode, 
                              TO_NUMBER (
                                TO_CHAR (daterelease, 'YYYY')
                              ) AS year, 
                              TO_NUMBER (
                                TO_CHAR (daterelease, 'MM')
                              ) AS month, 
                              SUM (debet) AS debet, 
                              SUM (credit) AS credit 
                            FROM 
                              (
                                SELECT 
                                  pv.bankcode, 
                                  pv.daterelease, 
                                  pv.cashflowtype, 
                                  pv.debet, 
                                  pv.credit 
                                FROM 
                                  (
                                    SELECT 
                                      payment.bankcode, 
                                      payment.daterelease, 
                                      payment.cashflowtype, 
                                      MAX (payment.remark) AS remark, 
                                      NVL (
                                        SUM (payment.amountbank), 
                                        0
                                      ) AS debet, 
                                      0 AS credit 
                                    FROM 
                                      payment 
                                    WHERE 
                                      payment.cashflowtype = '0' 
                                    GROUP BY 
                                      payment.bankcode, 
                                      payment.daterelease, 
                                      payment.cashflowtype 
                                    UNION ALL 
                                    SELECT 
                                      payment.bankcode, 
                                      payment.daterelease, 
                                      payment.cashflowtype, 
                                      MAX (payment.remark) AS remark, 
                                      0 AS debet, 
                                      NVL (
                                        SUM (payment.amountbank), 
                                        0
                                      ) AS credit 
                                    FROM 
                                      payment 
                                    WHERE 
                                      payment.cashflowtype = '1' 
                                    GROUP BY 
                                      payment.bankcode, 
                                      payment.daterelease, 
                                      payment.cashflowtype
                                  ) pv 
                                UNION ALL 
                                SELECT 
                                  payment_other.bankcode, 
                                  payment_other.daterelease, 
                                  payment_other.cashflowtype, 
                                  NVL (
                                    SUM (payment_other.amount), 
                                    0
                                  ) AS debet, 
                                  0 AS credit 
                                FROM 
                                  payment_other 
                                WHERE 
                                  payment_other.cashflowtype = '0' 
                                GROUP BY 
                                  payment_other.bankcode, 
                                  payment_other.daterelease, 
                                  payment_other.cashflowtype 
                                UNION ALL 
                                SELECT 
                                  payment_other.bankcode, 
                                  payment_other.daterelease, 
                                  payment_other.cashflowtype, 
                                  0 AS debet, 
                                  NVL (
                                    SUM (payment_other.amount), 
                                    0
                                  ) AS credit 
                                FROM 
                                  payment_other 
                                WHERE 
                                  payment_other.cashflowtype = '1' 
                                GROUP BY 
                                  payment_other.bankcode, 
                                  payment_other.daterelease, 
                                  payment_other.cashflowtype
                              ) pv2 
                            GROUP BY 
                              bankcode, 
                              TO_NUMBER (
                                TO_CHAR (daterelease, 'YYYY')
                              ), 
                              TO_NUMBER (
                                TO_CHAR (daterelease, 'MM')
                              ) 
                            UNION ALL 
                            SELECT 
                              bankcode, 
                              TO_NUMBER (
                                TO_CHAR (daterelease, 'YYYY')
                              ) AS year, 
                              TO_NUMBER (
                                TO_CHAR (daterelease, 'MM')
                              ) AS month, 
                              SUM (debet) AS debet, 
                              SUM (credit) AS credit 
                            FROM 
                              (
                                SELECT 
                                  intercoloans.banktarget AS bankcode, 
                                  intercoloans.daterelease, 
                                  0 AS cashflowtype, 
                                  NVL (
                                    SUM (intercoloans.amount), 
                                    0
                                  ) AS debet, 
                                  0 AS credit 
                                FROM 
                                  intercoloans 
                                GROUP BY 
                                  intercoloans.banktarget, 
                                  intercoloans.daterelease, 
                                  intercoloans.nocekgiro, 
                                  0 
                                UNION ALL 
                                SELECT 
                                  intercoloans.banksource AS bankcode, 
                                  intercoloans.daterelease, 
                                  1 AS cashflowtype, 
                                  0 AS debet, 
                                  NVL (
                                    SUM (intercoloans.sourceamount), 
                                    0
                                  ) AS credit 
                                FROM 
                                  intercoloans 
                                GROUP BY 
                                  intercoloans.banksource, 
                                  intercoloans.daterelease, 
                                  1, 
                                  ''
                              )
                            GROUP BY 
                              bankcode, 
                              TO_NUMBER (
                                TO_CHAR (daterelease, 'YYYY')
                              ), 
                              TO_NUMBER (
                                TO_CHAR (daterelease, 'MM')
                              )
                          ) 
                        GROUP BY 
                          bankcode, 
                          year, 
                          month
                      ) pv3 
                      RIGHT JOIN (
                        SELECT 
                          company, 
                          bankcode, 
                          period_year, 
                          period_month, 
                          NVL (
                            SUM (opening_balance), 
                            0
                          ) AS opening_balance_monthly, 
                          currency 
                        FROM 
                          bankbalance 
                        GROUP BY 
                          company, 
                          bankcode, 
                          period_year, 
                          period_month, 
                          currency
                      ) tb_monthly ON (
                        tb_monthly.bankcode = pv3.bankcode 
                        AND tb_monthly.period_month = pv3.month 
                        AND tb_monthly.period_year = pv3.year
                      ) 
                    WHERE 
                      tb_monthly.company = '$COMPANY'
                  ) a 
                  LEFT JOIN payment_periodcontrol b ON (
                    a.company = b.company 
                    AND a.period_year = b.currentaccountingyear 
                    AND a.period_month = b.currentaccountingperiod
                  )";
            
            $result1 = $this->db->query($q)->result();
            // echo "<pre>";
            // var_dump($q);exit();
            if ($result1) {
                $getPeriod = "SELECT * from payment_periodcontrol where company = '$COMPANY'";
                $getPeriod = $this->db->query($getPeriod)->row();

                $YEAR  = $getPeriod->CURRENTACCOUNTINGYEAR;
                $MONTH = $getPeriod->CURRENTACCOUNTINGPERIOD;

                if($MONTH == 12){
                    $YEAR  = $YEAR + 1;
                    $MONTH = 1;
                }else{
                    $MONTH = $MONTH + 1;
                }
                
                $deleteBankBalance = "DELETE FROM BANKBALANCE WHERE COMPANY = '$COMPANY' AND PERIOD_YEAR = '$YEAR' AND PERIOD_MONTH = '$MONTH'";
                $this->db->query($deleteBankBalance);
                $result = true;

                foreach($result1 as $row){

                    $getValPeriod = $this->getValPeriod($COMPANY,$row->BANKCODE,$getPeriod->CURRENTACCOUNTINGYEAR,$getPeriod->CURRENTACCOUNTINGPERIOD);

                    // var_dump($getValPeriod);exit;

                    $update = array(
                        'CASHIN' => $getValPeriod->DEBET,
                        'CASHOUT' => $getValPeriod->CREDIT,
                        'ENDING_BALANCE' => $getValPeriod->SALDO
                    );
                    
                    $where = array(
                        'BANKCODE'     => $getValPeriod->BANKCODE,
                        'PERIOD_YEAR'  => $getPeriod->CURRENTACCOUNTINGYEAR,
                        'PERIOD_MONTH' => $getPeriod->CURRENTACCOUNTINGPERIOD,
                        'COMPANY'      => $COMPANY
                    );
                    $this->db->where($where);
                    $this->db->update('BANKBALANCE',$update);

                    $YEAR_NEXT  = $row->PERIOD_YEAR_NEXT;
                    $MONTH_NEXT = $row->PERIOD_MONTH_NEXT;
                    $BANK_CODE   = $row->BANKCODE;

                    $qcekDupe = "SELECT * FROM BANKBALANCE WHERE PERIOD_YEAR = '$YEAR_NEXT' AND PERIOD_MONTH = '$MONTH_NEXT' AND BANKCODE = '$BANK_CODE'";
                    $qcekDupe = $this->db->query($qcekDupe)->num_rows();
                    // var_dump($this->db->last_query());exit;
                    if($qcekDupe > 0){
                        $ins = array(
                            'PERIOD_YEAR'       => $row->PERIOD_YEAR_NEXT,
                            'PERIOD_MONTH'      => $row->PERIOD_MONTH_NEXT,
                            'BANKCODE'          => $row->BANKCODE_NEXT,
                            'OPENING_BALANCE'   => $row->OPENING_BALANCE_MONTHLY_NEXT,
                            'ENDING_BALANCE'    => $row->ENDING_BALANCE_MONTHLY_NEXT,
                            'FCEDIT'            => $USERNAME,
                            'FCIP'              => $address,
                            'COMPANY'           => $COMPANY,
                            'LASTTIME'          => date("H:i"),
                            'CURRENCY'          => $row->CURRENCY_NEXT
                        );
                        $where2 = array(
                            'BANKCODE'     => $row->BANKCODE,
                            'PERIOD_YEAR'  => $row->PERIOD_YEAR_NEXT,
                            'PERIOD_MONTH' => $row->PERIOD_MONTH_NEXT,
                            'COMPANY'      => $COMPANY
                        );
                        $this->db->where($where2);
                        $res = $this->db->update('BANKBALANCE',$ins);

                    }else{
                        $insert = array(
                            'PERIOD_YEAR'       => $row->PERIOD_YEAR_NEXT,
                            'PERIOD_MONTH'      => $row->PERIOD_MONTH_NEXT,
                            'BANKCODE'          => $row->BANKCODE_NEXT,
                            'OPENING_BALANCE'   => $row->OPENING_BALANCE_MONTHLY_NEXT,
                            'CASHIN'           => 0,
                            'CASHOUT'          => 0,
                            'ENDING_BALANCE'    => $row->ENDING_BALANCE_MONTHLY_NEXT,
                            'FCENTRY'           => $USERNAME,
                            'FCEDIT'            => $USERNAME,
                            'FCIP'              => $address,
                            'COMPANY'           => $COMPANY,
                            'LASTTIME'          => date("H:i"),
                            'CURRENCY'          => $row->CURRENCY_NEXT
                        );

                        $res = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set($insert)
                                ->insert('BANKBALANCE');
                    }
                    

                    $getYear = $YEAR;
                    $getMonth = $MONTH;
                }

                if($res){
                    $qClosingPeriod = "update PAYMENT_PERIODCONTROL set lastperiodposted = (select last_day(add_months((to_date('$getMonth/$getYear','mm/yyyy')),-1)) from dual), currentdate = (select trunc(add_months((to_date('$getMonth/$getYear','mm/yyyy')),-0),'month') from dual), currentaccountingyear = (select to_number('$getYear') as from dual), currentaccountingperiod = (select to_number('$getMonth') as from dual), closeaccountingyear = ( select case when (select to_number('$getMonth') as from dual) -1 = 0 then (select to_number('$getYear') as from dual)-1 else (select to_number('$getYear') as from dual) end as closeaccountingyear from dual ), closeaccountingperiod = ( select case when (select to_number('$getMonth') as from dual) -1 = 0 then 12 else (select to_number('$getMonth') as from dual)-1 end as closeaccountingperiod from dual ) WHERE SYSTEM = 'BANK' AND COMPANY = '$COMPANY'";

                    $run = $this->db->query($qClosingPeriod);
                    if($run){
                        $result = true;
                    }else{
                        $this->db->trans_rollback();
                        $return = [
                            'STATUS' => FALSE,
                            'MESSAGE' => $ex->getMessage()
                        ];
                    }
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
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

    public function getValPeriod($company,$bankcode,$year,$month){
        $q = "SELECT a.company, a.bankcode, a.period_year, 
                                      a.period_month, 
                                      a.opening_balance_monthly, 
                                      a.debet, 
                                      a.credit, 
                                      a.saldo, 
                                      '', 
                                      a.company as company_next, 
                                      a.bankcode as bankcode_next, 
                                      a.saldo AS opening_balance_monthly_next, 
                                      CASE WHEN a.period_month = 12 THEN a.period_year + 1 ELSE a.period_year END AS period_year_next, 
                                      CASE WHEN a.period_month = 12 THEN 1 ELSE a.period_month + 1 END AS period_month_next, 
                                      0 AS debet_next, 
                                      0 AS credit_next, 
                                      a.saldo AS ending_balance_monthly_next, 
                                      a.currency as currency_next
                                    FROM 
                                      (
                                        SELECT 
                                          tb_monthly.company, 
                                          tb_monthly.bankcode, 
                                          tb_monthly.period_year, 
                                          tb_monthly.period_month, 
                                          tb_monthly.opening_balance_monthly AS opening_balance_monthly, 
                                          NVL (pv3.debet, 0) AS debet, 
                                          NVL (pv3.credit, 0) AS credit, 
                                          (
                                            tb_monthly.opening_balance_monthly + NVL (pv3.debet, 0) - NVL (pv3.credit, 0)
                                          ) AS saldo, 
                                          tb_monthly.currency 
                                        FROM 
                                          (
                                            SELECT 
                                              bankcode, 
                                              year, 
                                              month, 
                                              SUM (debet) AS debet, 
                                              SUM (credit) AS credit 
                                            FROM 
                                              (
                                                SELECT 
                                                  bankcode, 
                                                  TO_NUMBER (
                                                    TO_CHAR (daterelease, 'YYYY')
                                                  ) AS year, 
                                                  TO_NUMBER (
                                                    TO_CHAR (daterelease, 'MM')
                                                  ) AS month, 
                                                  SUM (debet) AS debet, 
                                                  SUM (credit) AS credit 
                                                FROM 
                                                  (
                                                    SELECT 
                                                      pv.bankcode, 
                                                      pv.daterelease, 
                                                      pv.cashflowtype, 
                                                      pv.debet, 
                                                      pv.credit 
                                                    FROM 
                                                      (
                                                        SELECT 
                                                          payment.bankcode, 
                                                          payment.daterelease, 
                                                          payment.cashflowtype, 
                                                          MAX (payment.remark) AS remark, 
                                                          NVL (
                                                            SUM (payment.amountbank), 
                                                            0
                                                          ) AS debet, 
                                                          0 AS credit 
                                                        FROM 
                                                          payment 
                                                        WHERE 
                                                          payment.cashflowtype = '0' 
                                                        GROUP BY 
                                                          payment.bankcode, 
                                                          payment.daterelease, 
                                                          payment.cashflowtype 
                                                        UNION ALL 
                                                        SELECT 
                                                          payment.bankcode, 
                                                          payment.daterelease, 
                                                          payment.cashflowtype, 
                                                          MAX (payment.remark) AS remark, 
                                                          0 AS debet, 
                                                          NVL (
                                                            SUM (payment.amountbank), 
                                                            0
                                                          ) AS credit 
                                                        FROM 
                                                          payment 
                                                        WHERE 
                                                          payment.cashflowtype = '1' 
                                                        GROUP BY 
                                                          payment.bankcode, 
                                                          payment.daterelease, 
                                                          payment.cashflowtype
                                                      ) pv 
                                                    UNION ALL 
                                                    SELECT 
                                                      payment_other.bankcode, 
                                                      payment_other.daterelease, 
                                                      payment_other.cashflowtype, 
                                                      NVL (
                                                        SUM (payment_other.amount), 
                                                        0
                                                      ) AS debet, 
                                                      0 AS credit 
                                                    FROM 
                                                      payment_other 
                                                    WHERE 
                                                      payment_other.cashflowtype = '0' 
                                                    GROUP BY 
                                                      payment_other.bankcode, 
                                                      payment_other.daterelease, 
                                                      payment_other.cashflowtype 
                                                    UNION ALL 
                                                    SELECT 
                                                      payment_other.bankcode, 
                                                      payment_other.daterelease, 
                                                      payment_other.cashflowtype, 
                                                      0 AS debet, 
                                                      NVL (
                                                        SUM (payment_other.amount), 
                                                        0
                                                      ) AS credit 
                                                    FROM 
                                                      payment_other 
                                                    WHERE 
                                                      payment_other.cashflowtype = '1' 
                                                    GROUP BY 
                                                      payment_other.bankcode, 
                                                      payment_other.daterelease, 
                                                      payment_other.cashflowtype
                                                  ) pv2 
                                                GROUP BY 
                                                  bankcode, 
                                                  TO_NUMBER (
                                                    TO_CHAR (daterelease, 'YYYY')
                                                  ), 
                                                  TO_NUMBER (
                                                    TO_CHAR (daterelease, 'MM')
                                                  ) 
                                                UNION ALL 
                                                SELECT 
                                                  bankcode, 
                                                  TO_NUMBER (
                                                    TO_CHAR (daterelease, 'YYYY')
                                                  ) AS year, 
                                                  TO_NUMBER (
                                                    TO_CHAR (daterelease, 'MM')
                                                  ) AS month, 
                                                  SUM (debet) AS debet, 
                                                  SUM (credit) AS credit 
                                                FROM 
                                                  (
                                                    SELECT 
                                                      intercoloans.banktarget AS bankcode, 
                                                      intercoloans.daterelease, 
                                                      0 AS cashflowtype, 
                                                      NVL (
                                                        SUM (intercoloans.amount), 
                                                        0
                                                      ) AS debet, 
                                                      0 AS credit 
                                                    FROM 
                                                      intercoloans 
                                                    GROUP BY 
                                                      intercoloans.banktarget, 
                                                      intercoloans.daterelease, 
                                                      intercoloans.nocekgiro, 
                                                      0 
                                                    UNION ALL 
                                                    SELECT 
                                                      intercoloans.banksource AS bankcode, 
                                                      intercoloans.daterelease, 
                                                      1 AS cashflowtype, 
                                                      0 AS debet, 
                                                      NVL (
                                                        SUM (intercoloans.sourceamount), 
                                                        0
                                                      ) AS credit 
                                                    FROM 
                                                      intercoloans 
                                                    GROUP BY 
                                                      intercoloans.banksource, 
                                                      intercoloans.daterelease, 
                                                      1, 
                                                      ''
                                                  )
                                                GROUP BY 
                                                  bankcode, 
                                                  TO_NUMBER (
                                                    TO_CHAR (daterelease, 'YYYY')
                                                  ), 
                                                  TO_NUMBER (
                                                    TO_CHAR (daterelease, 'MM')
                                                  )
                                              ) 
                                            GROUP BY 
                                              bankcode, 
                                              year, 
                                              month
                                          ) pv3 
                                          RIGHT JOIN (
                                            SELECT 
                                              company, 
                                              bankcode, 
                                              period_year, 
                                              period_month, 
                                              NVL (
                                                SUM (opening_balance), 
                                                0
                                              ) AS opening_balance_monthly, 
                                              currency 
                                            FROM 
                                              bankbalance 
                                            GROUP BY 
                                              company, 
                                              bankcode, 
                                              period_year, 
                                              period_month, 
                                              currency
                                          ) tb_monthly ON (
                                            tb_monthly.bankcode = pv3.bankcode 
                                            AND tb_monthly.period_month = pv3.month 
                                            AND tb_monthly.period_year = pv3.year
                                          ) 
                                        WHERE 
                                          tb_monthly.company = '$company'
                                      ) a 
                                      LEFT JOIN payment_periodcontrol b ON (
                                        a.company = b.company 
                                        AND a.period_year = b.currentaccountingyear 
                                        AND a.period_month = b.currentaccountingperiod
                                      ) where bankcode = '$bankcode' AND period_year = '$year' AND period_month = '$month'";
        return $this->db->query($q)->row();

    }
}
