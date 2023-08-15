<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ReportFtPModel extends BaseModel
{

    function __construct()
    {
        parent::__construct();
    }

    public function ShowData($param)
    {
        // $param['DEPARTMENT'] = $param['DEPARTMENT'];

        $DEPARTMENT     = $param["DEPARTMENT"];
        $FCCODE         = $param['COMPANYGROUPNAME'];
        // $FCCODE_GROUP   = $param['COMPANYGROUPNAME'];
        $COMPANY_SUBGROUP = $param['COMPANYSUBGROUP'];
        $COMPANYID      = $param['COMPANY'];
        $YEAR           = $param['YEAR'];
        $MONTH          = $param['MONTH'];
        if($MONTH < 10){
            $MONTH2 = "0".$MONTH;
        }else{
            $MONTH2 = $MONTH;
        }

        if($FCCODE != null || $FCCODE != ''){
            $WHERE .= " AND BU.COMPANYGROUP = '" . $FCCODE . "'";
            $WHERE1 .= " AND CSG.FCCODE_GROUP = '" . $FCCODE . "'";
        }
        if($COMPANY_SUBGROUP != null || $COMPANY_SUBGROUP != ''){
            $WHERE .= " AND BU.COMPANY_SUBGROUP = '" . $COMPANY_SUBGROUP . "'";
            $WHERE1 .= " AND CSG.FCCODE = '" . $COMPANY_SUBGROUP . "'";
        }
        if($COMPANYID != null || $COMPANYID != ''){
            $WHERE .= " AND C.ID = '" . $COMPANYID . "'";
            $WHERE1 .= " AND C.ID = '" . $COMPANYID . "'";
        }
        if($DEPARTMENT != null || $DEPARTMENT != ''){
            $WHEREDEPT = " WHERE DEPARTMENT = '" . $DEPARTMENT . "' ";
        }

        $SQL = "SELECT DEPARTMENT,
                        BUSINESSUNITCODE,
                         DOCNUMBER,
                         DOCTYPE,
                         DOCDATE,
                         DOCREF,
                         DUEDATE,
                         COMPANYCODE,
                         FCNAME as VENDORNAME,
                         NVL (SUM (AMOUNTINV), 0) AMOUNTINV,
                         NVL (SUM (REQUESTW1), 0) AS REQUESTW1,
                         NVL (SUM (REQUESTW2), 0) AS REQUESTW2,
                         NVL (SUM (REQUESTW3), 0) AS REQUESTW3,
                         NVL (SUM (REQUESTW4), 0) AS REQUESTW4,
                         NVL (SUM (REQUESTW5), 0) AS REQUESTW5,
                         NVL (SUM (ADJSW1), 0) AS ADJSW1,
                         NVL (SUM (ADJSW2), 0) AS ADJSW2,
                         NVL (SUM (ADJSW3), 0) AS ADJSW3,
                         NVL (SUM (ADJSW4), 0) AS ADJSW4,
                         NVL (SUM (ADJSW5), 0) AS ADJSW5,
                         NVL (SUM (PAIDW1), 0) AS PAIDW1,
                         NVL (SUM (PAIDW2), 0) AS PAIDW2,
                         NVL (SUM (PAIDW3), 0) AS PAIDW3,
                         NVL (SUM (PAIDW4), 0) AS PAIDW4,
                         NVL (SUM (PAIDW5), 0) AS PAIDW5
                    FROM (SELECT DEPARTMENT,
                 BUSINESSUNITCODE,
                 DOCNUMBER,
                 DOCTYPE,
                 DOCDATE,
                 DOCREF,
                 DUEDATE,
                 COMPANYCODE,
                 FCNAME,
                 MONTH,
                 YEAR,
                 REQUESTW1,
                 REQUESTW2,
                 REQUESTW3,
                 REQUESTW4,
                 REQUESTW5,
                 ADJSW1,
                 ADJSW2,
                 ADJSW3,
                 ADJSW4,
                 ADJSW5,
                 PAIDW1,
                 PAIDW2,
                 PAIDW3,
                 PAIDW4,
                 PAIDW5,
                 AMOUNTINV FROM ( SELECT DEPARTMENT,
                 BU.FCCODE BUSINESSUNITCODE,
                 DOCNUMBER,
                 DOCTYPE,
                 DOCDATE,
                 DOCREF,
                 DUEDATE,
                 COMPANYCODE,
                 FCNAME,
                 MONTH,
                 YEAR,
                 REQUESTW1,
                 REQUESTW2,
                 REQUESTW3,
                 REQUESTW4,
                 REQUESTW5,
                 ADJSW1,
                 ADJSW2,
                 ADJSW3,
                 ADJSW4,
                 ADJSW5,
                 PAIDW1,
                 PAIDW2,
                 PAIDW3,
                 PAIDW4,
                 PAIDW5,
                 AMOUNTINV FROM (  SELECT DISTINCT
                                 CASE
                                    WHEN cf.doctype IN ('PDO', 'INV_AP_SPC', 'INV_AR_SPC','LEASING','LOAN')
                                    THEN
                                       cf.DEPARTMENT
                                    ELSE
                                       docs.DEPARTMENT
                                 END
                                    AS DEPARTMENT,
                                 CASE
                                    WHEN cf.doctype IN ('PDO', 'INV_AP_SPC', 'INV_AR_SPC','LEASING','LOAN')
                                    THEN
                                       cf.businessunit
                                    ELSE
                                       docs.businessunit
                                 END
                                    AS BUSINESSUNITCODE,
                                 CF.DOCNUMBER,
                                 CF.DOCTYPE,
                                 CF.DOCDATE,
                                 CF.DOCREF,
                                 CF.DUEDATE,
                                 C.COMPANYCODE,
                                 S.FCNAME,
                                 FF.MONTH,
                                 FF.YEAR,
                                 ROUND (DECODE (FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW1,
                                 ROUND (DECODE (FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW2,
                                 ROUND (DECODE (FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW3,
                                 ROUND (DECODE (FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW4,
                                 ROUND (DECODE (FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW5,
                                 ROUND (DECODE (FF.WEEK, 'W1', FF.AMOUNTADJS, 0), 2) AS ADJSW1,
                                 ROUND (DECODE (FF.WEEK, 'W2', FF.AMOUNTADJS, 0), 2) AS ADJSW2,
                                 ROUND (DECODE (FF.WEEK, 'W3', FF.AMOUNTADJS, 0), 2) AS ADJSW3,
                                 ROUND (DECODE (FF.WEEK, 'W4', FF.AMOUNTADJS, 0), 2) AS ADJSW4,
                                 ROUND (DECODE (FF.WEEK, 'W5', FF.AMOUNTADJS, 0), 2) AS ADJSW5,
                                 0 PAIDW1,
                                 0 PAIDW2,
                                 0 PAIDW3,
                                 0 PAIDW4,
                                 0 PAIDW5,
                                 (CF.AMOUNT_INCLUDE_VAT - NVL (CF.AMOUNT_PPH, 0)) AS AMOUNTINV
                                    FROM (SELECT * FROM FORECAST_FIX UNION ALL SELECT * FROM FORECAST_FIX_TEMP) FF
                                         INNER JOIN CF_TRANSACTION CF ON CF.ID = FF.CFTRANSID
                                         LEFT JOIN
                                           (SELECT b.company,
                                                   b.docnumber,
                                                   a.material,
                                                   b.businessunit,
                                                   b.department
                                              FROM cf_transaction_det a
                                                   INNER JOIN cf_transaction b ON (a.id = b.id))
                                           docs
                                              ON (    cf.company = docs.company
                                                  AND cf.docref = docs.docnumber)
                                         INNER JOIN COMPANY C ON C.ID = CF.COMPANY
                                         INNER JOIN COMPANY_SUBGROUP CSG
                                            ON CSG.FCCODE = C.COMPANY_SUBGROUP
                                         INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
                                         INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE                 
                                         INNER JOIN SUPPLIER S ON S.ID = CF.VENDOR
                                   WHERE     FF.YEAR = '".$YEAR."'
                                         AND FF.MONTH = '".$MONTH."' ".$WHERE1 ." ) FF INNER JOIN
                         (SELECT id,
                                 fccode,
                                 companygroup,
                                 company_subgroup
                            FROM businessunit) bu
                            ON (bu.id = ff.businessunitcode) )".$WHEREDEPT;
        $SQL .= " UNION ALL
          SELECT DEPARTMENT,
                 BUSINESSUNITCODE,
                 DOCNUMBER,
                 DOCTYPE,
                 DOCDATE,
                 DOCREF,
                 DUEDATE,
                 COMPANYCODE,
                 FCNAME,
                 MONTH,
                 YEAR,
                 REQUESTW1,
                 REQUESTW2,
                 REQUESTW3,
                 REQUESTW4,
                 REQUESTW5,
                 ADJSW1,
                 ADJSW2,
                 ADJSW3,
                 ADJSW4,
                 ADJSW5,
                 PAIDW1,
                 PAIDW2,
                 PAIDW3,
                 PAIDW4,
                 PAIDW5,
                 AMOUNTINV FROM (SELECT DISTINCT
                 docs.DEPARTMENT,
                 BU.FCCODE AS BUSINESSUNITCODE,
                 CF.DOCNUMBER,
                 CF.DOCTYPE,
                 CF.DOCDATE,
                 CF.DOCREF,
                 CF.DUEDATE,
                 C.COMPANYCODE,
                 S.FCNAME,
                 TO_NUMBER (TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
                 TO_NUMBER (TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
                 0 REQUESTW1,
                 0 REQUESTW2,
                 0 REQUESTW3,
                 0 REQUESTW4,
                 0 REQUESTW5,
                 0 ADJSW1,
                 0 ADJSW2,
                 0 ADJSW3,
                 0 ADJSW4,
                 0 ADJSW5,
                 ROUND (DECODE (P.WEEK, 'W1', P.AMOUNTPAYMENT, 0), 2) AS PAIDW1,
                 ROUND (DECODE (P.WEEK, 'W2', P.AMOUNTPAYMENT, 0), 2) AS PAIDW2,
                 ROUND (DECODE (P.WEEK, 'W3', P.AMOUNTPAYMENT, 0), 2) AS PAIDW3,
                 ROUND (DECODE (P.WEEK, 'W4', P.AMOUNTPAYMENT, 0), 2) AS PAIDW4,
                 ROUND (DECODE (P.WEEK, 'W5', P.AMOUNTPAYMENT, 0), 2) AS PAIDW5,
                 (CF.AMOUNT_INCLUDE_VAT - NVL (CF.AMOUNT_PPH, 0)) AS AMOUNTINV
            FROM (SELECT PAY.PAYMENTID,
                         PAY.DATERELEASE,
                         PAY.AMOUNT AMOUNTPAYMENT,
                         PAY.CFTRANSID,
                         (SELECT SW.WEEK
                            FROM SETTING_WEEK SW
                           WHERE     SW.YEAR =
                                        TO_NUMBER (
                                           TO_CHAR (PAY.DATERELEASE, 'yyyy'))
                                 AND SW.MONTH =
                                        TO_NUMBER (
                                           TO_CHAR (PAY.DATERELEASE, 'mm'))
                                 AND SW.DATEFROM <=
                                        TO_NUMBER (
                                           TO_CHAR (PAY.DATERELEASE, 'dd'))
                                 AND SW.DATEUNTIL >=
                                        TO_NUMBER (
                                           TO_CHAR (PAY.DATERELEASE, 'dd')))
                            AS WEEK,
                         SW.DATEFROM,
                         SW.DATEUNTIL,
                         SW.MONTH,
                         SW.YEAR
                    FROM PAYMENT PAY
                         INNER JOIN SETTING_WEEK SW
                            ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                       SW.DATEFROM
                                AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                       SW.DATEUNTIL
                                AND SW.MONTH = TO_CHAR (PAY.DATERELEASE, 'MM')
                                AND SW.YEAR = TO_CHAR (PAY.DATERELEASE, 'YYYY')))
                 P
                 INNER JOIN CF_TRANSACTION CF ON CF.ID = P.CFTRANSID
                 LEFT JOIN
                           (SELECT b.company,
                                   b.docnumber,
                                   a.material,
                                   b.businessunit,
                                   b.department
                              FROM cf_transaction_det a
                                   INNER JOIN cf_transaction b ON (a.id = b.id))
                           docs
                              ON (    cf.company = docs.company
                                  AND cf.docref = docs.docnumber)
                 INNER JOIN COMPANY C ON C.ID = CF.COMPANY
                 INNER JOIN
                 (SELECT id,
                         fccode,
                         companygroup,
                         company_subgroup
                    FROM businessunit) bu
                    ON (bu.id = docs.businessunit)
                 INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                 INNER JOIN SUPPLIER S ON S.ID = CF.VENDOR
           WHERE     TO_CHAR (P.DATERELEASE, 'MM') = '".$MONTH2."'
                 AND TO_CHAR (P.DATERELEASE, 'YYYY') = '".$YEAR."' ".$WHERE;
                 $SQL .= " AND CF.DOCNUMBER NOT LIKE '%TMPINV%') )".$WHEREDEPT;
                 $SQL .= " GROUP BY DEPARTMENT,BUSINESSUNITCODE,DOCNUMBER,DOCTYPE,DOCDATE,DOCREF,DUEDATE,COMPANYCODE,FCNAME";
        $result = $this->db->query($SQL)->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    // public function ShowData1($param)
    // {
    //     // $param['DEPARTMENT'] = $param['DEPARTMENT'];

    //     $DEPARTMENT     = $param["DEPARTMENT"];
    //     $FCCODE         = $param['COMPANYGROUPNAME'];
    //     $FCCODE_GROUP   = $param['COMPANYGROUPNAME'];
    //     $COMPANY_SUBGROUP = $param['COMPANYSUBGROUP'];
    //     $COMPANYID      = $param['COMPANY'];

        
    //     $WHERE = " WHERE (REQUESTW1 <> 0 OR REQUESTW2 <> 0 OR REQUESTW3 <> 0 OR REQUESTW4 <> 0 OR REQUESTW5 <> 0 OR ADJSW1 <> 0 OR ADJSW2 <> 0 OR ADJSW3 <> 0 OR ADJSW4 <> 0 OR ADJSW5 <> 0 ) ";
    //     if($FCCODE != null || $FCCODE != ''){
    //         $WHERE .= " AND CG.FCCODE = '" . $FCCODE . "'";
    //     }
    //     if($FCCODE_GROUP != null || $FCCODE_GROUP != ''){
    //         $WHERE .= " AND CSG.FCCODE_GROUP = '" . $FCCODE_GROUP . "'";
    //     }
    //     if($COMPANY_SUBGROUP != null || $COMPANY_SUBGROUP != ''){
    //         $WHERE .= " AND CO.COMPANY_SUBGROUP = '" . $COMPANY_SUBGROUP . "'";
    //     }
    //     if($COMPANYID != null || $COMPANYID != ''){
    //         $WHERE .= " AND CO.ID = '" . $COMPANYID . "'";
    //     }
    //     if($DEPARTMENT != null || $DEPARTMENT != ''){
    //         $WHERE .= " AND DATA2.DEPARTMENT = '" . $DEPARTMENT . "' ";
    //     }


    //     $SQL = "SELECT
    //             DATA1.FORECASTID, 
    //             DATA2.DEPARTMENT,
    //             CO.COMPANYCODE AS COMPANYCODE,
    //             BS.FCCODE AS BUSINESSUNITCODE,
    //             SUPP.FCCODE AS VENDORCODE,
    //             SUPP.FCNAME AS VENDORNAME,
    //             DATA2.DOCTYPE AS DOCTYPE,
    //             NVL (FF.REQUESTW1, 0) AS REQUESTW1,
    //             NVL (FF.REQUESTW2, 0) AS REQUESTW2,
    //             NVL (FF.REQUESTW3, 0) AS REQUESTW3,
    //             NVL (FF.REQUESTW4, 0) AS REQUESTW4,
    //             NVL (FF.REQUESTW5, 0) AS REQUESTW5,
    //             NVL (FF.ADJSW1, 0) AS ADJSW1,
    //             NVL (FF.ADJSW2, 0) AS ADJSW2,
    //             NVL (FF.ADJSW3, 0) AS ADJSW3,
    //             NVL (FF.ADJSW4, 0) AS ADJSW4,
    //             NVL (FF.ADJSW5, 0) AS ADJSW5,
    //             NVL (PY.PAIDW1, 0) AS PAIDW1,
    //             NVL (PY.PAIDW2, 0) AS PAIDW2,
    //             NVL (PY.PAIDW3, 0) AS PAIDW3,
    //             NVL (PY.PAIDW4, 0) AS PAIDW4,
    //             NVL (PY.PAIDW5, 0) AS PAIDW5,
    //             DATA2.DOCNUMBER AS DOCNUMBER,
    //             DATA1.DOCNUMBER AS DOCREF,
    //             TO_CHAR (DATA2.DOCDATE, 'YYYY/MM/DD') AS DOCDATE,
    //             TO_CHAR (DATA2.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,
    //             (DATA1.AMOUNT_INCLUDE_VAT - NVL (DATA1.AMOUNT_PPH, 0)) AS AMOUNTINV
    //             FROM (
    //                 SELECT
    //                 PO.ID,
    //                 PAYMENT.FORECASTID,
    //                 PAYMENT.VOUCHERNO,
    //                 PAYMENT.BANKCODE,
    //                 PAYMENT.DATERELEASE,
    //                 PO.COMPANY,
    //                 PO.DOCTYPE,
    //                 PO.DOCNUMBER,
    //                 PO.DOCREF,
    //                 PO.DOCDATE,
    //                 PO.VENDOR,
    //                 PO.AMOUNT_INCLUDE_VAT,
    //                 PO.AMOUNT_PPH,
    //                 PO.CURRENCY,
    //                 SUM(PAYMENT.AMOUNT) AS AMOUNT,
    //                 SUM(PAYMENT.AMOUNTBANK) AS AMOUNTBANK
    //                 FROM PAYMENT
    //                 INNER JOIN (
    //                     SELECT
    //                     ID,
    //                     DEPARTMENT,
    //                     DOCTYPE,
    //                     COMPANY,
    //                     DOCNUMBER,
    //                     DOCREF,
    //                     DOCDATE,
    //                     VENDOR,
    //                     AMOUNT_INCLUDE_VAT,
    //                     AMOUNT_PPH,
    //                     CURRENCY
    //                     FROM CF_TRANSACTION
    //                 ) PO
    //                 ON (
    //                 PAYMENT.CFTRANSID = PO.ID
    //                 )
    //                 GROUP BY
    //                 PO.ID,
    //                 PAYMENT.FORECASTID,
    //                 PAYMENT.VOUCHERNO,
    //                 PAYMENT.BANKCODE,
    //                 PAYMENT.DATERELEASE,
    //                 PO.COMPANY,
    //                 PO.DOCTYPE,
    //                 PO.DOCNUMBER,
    //                 PO.DOCREF,
    //                 PO.DOCDATE,
    //                 PO.VENDOR,
    //                 PO.AMOUNT_INCLUDE_VAT,
    //                 PO.AMOUNT_PPH,
    //                 PO.CURRENCY
    //             ) DATA1
    //             LEFT JOIN (
    //                 SELECT
    //                 ID,
    //                 DUEDATE,
    //                 DOCREF, 
    //                 COMPANY,
    //                 BUSINESSUNIT,
    //                 DOCTYPE,
    //                 DOCNUMBER,
    //                 DOCDATE,
    //                 DEPARTMENT,
    //                 AMOUNT_INCLUDE_VAT,
    //                 AMOUNT_PPH
    //                 FROM CF_TRANSACTION
    //             ) DATA2
    //             ON (
    //             DATA1.DOCREF = DATA2.DOCNUMBER)
    //             LEFT JOIN
    //                     (  SELECT FF.ID,
    //                             FF.YEAR,
    //                             FF.MONTH,
    //                             FF.ISACTIVE,
    //                             FF.LOCKS,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2)
    //                                 AS REQUESTW1,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2)
    //                                 AS REQUESTW2,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2)
    //                                 AS REQUESTW3,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2)
    //                                 AS REQUESTW4,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2)
    //                                 AS REQUESTW5,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2)
    //                                 AS ADJSW1,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2)
    //                                 AS ADJSW2,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2)
    //                                 AS ADJSW3,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2)
    //                                 AS ADJSW4,
    //                             ROUND (SUM (DECODE (FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2)
    //                                 AS ADJSW5
    //                         FROM FORECAST_FIX FF
    //                         WHERE FF.YEAR = '" . $param["YEAR"] . "' AND FF.MONTH = '" . $param["MONTH"] . "'
    //                     GROUP BY FF.ID,
    //                             FF.YEAR,
    //                             FF.MONTH,
    //                             FF.ISACTIVE,
    //                             FF.LOCKS) FF
    //                         ON FF.ID = DATA1.FORECASTID
    //             LEFT JOIN (SELECT FF.CFTRANSID,
    //                                     PY.PAIDW1,
    //                                     PY.PAIDW2,
    //                                     PY.PAIDW3,
    //                                     PY.PAIDW4,
    //                                     PY.PAIDW5
    //                                 FROM FORECAST_FIX FF
    //                                     INNER JOIN
    //                                     (  SELECT PY.FORECASTID,
    //                                                 ROUND (SUM (DECODE (PY.WEEK, 'W1', PY.AMOUNT, 0)),2)AS PAIDW1,
    //                                                 ROUND (SUM (DECODE (PY.WEEK, 'W2', PY.AMOUNT, 0)),2)AS PAIDW2,
    //                                                 ROUND (SUM (DECODE (PY.WEEK, 'W3', PY.AMOUNT, 0)),2)AS PAIDW3,
    //                                                 ROUND (SUM (DECODE (PY.WEEK, 'W4', PY.AMOUNT, 0)),2)AS PAIDW4,
    //                                                 ROUND (SUM (DECODE (PY.WEEK, 'W5', PY.AMOUNT, 0)),2)AS PAIDW5
    //                                             FROM (SELECT PY.FORECASTID,PY.AMOUNT,
    //                                                         (SELECT SW.WEEK
    //                                                             FROM SETTING_WEEK SW
    //                                                             WHERE SW.YEAR = TO_NUMBER (TO_CHAR (PY.DATERELEASE,'yyyy'))
    //                                                                 AND SW.MONTH =TO_NUMBER (TO_CHAR (PY.DATERELEASE,'mm'))
    //                                                                 AND SW.DATEFROM <=TO_NUMBER (TO_CHAR (PY.DATERELEASE,'dd'))
    //                                                                 AND SW.DATEUNTIL >=TO_NUMBER (TO_CHAR (PY.DATERELEASE,'dd')))
    //                                                             AS WEEK
    //                                                     FROM PAYMENT PY
    //                                                 WHERE TO_NUMBER (TO_CHAR (PY.DATERELEASE,'yyyy')) = '" . $param["YEAR"] . "'
    //                                                         AND TO_NUMBER (TO_CHAR (PY.DATERELEASE,'mm')) = '" . $param["MONTH"] . "') PY
    //                                         GROUP BY PY.FORECASTID) PY
    //                                         ON PY.FORECASTID = FF.ID) PY
    //                         ON PY.CFTRANSID = DATA1.ID
    //             LEFT JOIN (
    //                 SELECT ID, YEAR, MONTH, SUM(AMOUNTREQUEST) AS AMOUNTREQUEST, SUM(AMOUNTADJS) AS AMOUNTADJS FROM FORECAST_FIX
    //                 WHERE YEAR = '" . $param["YEAR"] . "' AND MONTH = '" . $param["MONTH"] . "'
    //                 GROUP BY ID,
    //                 YEAR, MONTH) FC
    //             ON (
    //             DATA1.FORECASTID = FC.ID) 
    //             INNER JOIN (
    //                 SELECT ID, COMPANYCODE, COMPANYNAME, COMPANY_SUBGROUP FROM COMPANY
    //             ) CO
    //             ON ( DATA1.COMPANY = CO.ID)
    //             INNER JOIN (
    //                 SELECT 
    //                 FCCODE,
    //                 BANKACCOUNT,
    //                 FCNAME 
    //                 FROM BANK
    //             ) BANK
    //             ON ( DATA1.BANKCODE = BANK.FCCODE)
    //             INNER JOIN (
    //                 SELECT 
    //                 ID,
    //                 FCCODE,
    //                 FCNAME
    //                 FROM SUPPLIER
    //             ) SUPP
    //             ON ( DATA1.VENDOR = SUPP.ID)
    //             INNER JOIN COMPANY_SUBGROUP CSG ON CSG.FCCODE = CO.COMPANY_SUBGROUP
    //             INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
    //             INNER JOIN BUSINESSUNIT BS
    //                         ON BS.ID = DATA2.BUSINESSUNIT AND BS.COMPANY = DATA2.COMPANY
    //             INNER JOIN USER_DEPART UD
    //                         ON UD.FCCODE = '" . $param["USERNAME"] . "' AND UD.DEPARTMENT = DATA2.DEPARTMENT ".$WHERE;
    //         $SQL .= " ORDER BY CO.COMPANYCODE, DATA2.DEPARTMENT, DATA1.DOCREF, FC.YEAR, FC.MONTH, DATA1.DOCNUMBER, DATA1.DATERELEASE";
    //     $result = $this->db->query($SQL)->result();
    //     // var_dump($this->db->last_query());exit();
    //     $this->db->close();
    //     return $result;
    // }

    // public function getDataCompanySubGroup($param)
    // {
    //     $Result = $this->db->select(['FCCODE', 'FCNAME'])
    //                         ->from("COMPANY_SUBGROUP")
    //                         ->order_by("FCNAME")->get()->result();
    //     $this->db->close();
    //     return $Result;
    // }

    public function Export($Data)
    {
        try {
        // echo "<pre>";
        // var_dump($Data);exit;
        $DEPARTMENT     = $Data["DEPARTMENT"];
        // $FCCODE         = $Data['COMPANYGROUPNAME'];
        $COMPANYGROUP   = $Data['GROUP'];
        $COMPANYSUBGROUP = $Data['SUBGROUP'];
        $COMPANYID      = $Data['COMPANY'];
        $YEAR           = $Data['YEAR'];
        $MONTH          = $Data['MONTH'];
        if($MONTH < 10){
            $MONTH2 = "0".$MONTH;
        }else{
            $MONTH2 = $MONTH;
        }

        // if($FCCODE != null || $FCCODE != ''){
        //     $WHERE .= " AND BU.COMPANYGROUP = '" . $FCCODE . "'";
        // }
        // if($FCCODE_GROUP != null || $FCCODE_GROUP != ''){
        //     $WHERE .= " AND BU.COMPANYGROUP = '" . $FCCODE_GROUP . "'";
        // }
        // if($COMPANY_SUBGROUP != null || $COMPANY_SUBGROUP != ''){
        //     $WHERE .= " AND BU.COMPANY_SUBGROUP = '" . $COMPANY_SUBGROUP . "'";
        // }
        // if($COMPANYID != "0" && $COMPANYID != null && $COMPANYID != ''){
        //     $WHERE .= " AND C.ID = '" . $COMPANYID . "'";
        // }
        // if($DEPARTMENT != "ALL"){
        //     $WHERE .= " AND docs.DEPARTMENT = '" . $DEPARTMENT . "' ";
        // }

        if($COMPANYGROUP != 'ALL' && $COMPANYGROUP != null && $COMPANYGROUP != ''){
            $WHERE .= " AND BU.COMPANYGROUP = '" . $COMPANYGROUP . "'";
            $WHERE1 .= " AND CSG.FCCODE_GROUP = '" . $COMPANYGROUP . "'";
        }
        if($COMPANYSUBGROUP != 'ALL' && $COMPANYSUBGROUP != null && $COMPANYSUBGROUP != ''){
            $WHERE .= " AND BU.COMPANY_SUBGROUP = '" . $COMPANYSUBGROUP . "'";
            $WHERE1 .= " AND CSG.FCCODE = '" . $COMPANYSUBGROUP . "'";
        }
        if($COMPANYID != null && $COMPANYID != '' && $COMPANYID != "0"){
            $WHERE .= " AND C.ID = '" . $COMPANYID . "'";
            $WHERE1 .= " AND C.ID = '" . $COMPANYID . "'";
        }
        if($DEPARTMENT != "ALL" && $DEPARTMENT != null && $DEPARTMENT != ''){
            $WHEREDEPT = " WHERE DEPARTMENT = '" . $DEPARTMENT . "' ";
        }

        $SQL = "SELECT DEPARTMENT,
                        BUSINESSUNITCODE,
                         DOCNUMBER,
                         DOCTYPE,
                         DOCDATE,
                         DOCREF,
                         DUEDATE,
                         COMPANYCODE,
                         FCNAME as VENDORNAME,
                         NVL (SUM (AMOUNTINV), 0) AMOUNTINV,
                         NVL (SUM (REQUESTW1), 0) AS REQUESTW1,
                         NVL (SUM (REQUESTW2), 0) AS REQUESTW2,
                         NVL (SUM (REQUESTW3), 0) AS REQUESTW3,
                         NVL (SUM (REQUESTW4), 0) AS REQUESTW4,
                         NVL (SUM (REQUESTW5), 0) AS REQUESTW5,
                         NVL (SUM (ADJSW1), 0) AS ADJSW1,
                         NVL (SUM (ADJSW2), 0) AS ADJSW2,
                         NVL (SUM (ADJSW3), 0) AS ADJSW3,
                         NVL (SUM (ADJSW4), 0) AS ADJSW4,
                         NVL (SUM (ADJSW5), 0) AS ADJSW5,
                         NVL (SUM (PAIDW1), 0) AS PAIDW1,
                         NVL (SUM (PAIDW2), 0) AS PAIDW2,
                         NVL (SUM (PAIDW3), 0) AS PAIDW3,
                         NVL (SUM (PAIDW4), 0) AS PAIDW4,
                         NVL (SUM (PAIDW5), 0) AS PAIDW5
                    FROM (SELECT DEPARTMENT,
                 BUSINESSUNITCODE,
                 DOCNUMBER,
                 DOCTYPE,
                 DOCDATE,
                 DOCREF,
                 DUEDATE,
                 COMPANYCODE,
                 FCNAME,
                 MONTH,
                 YEAR,
                 REQUESTW1,
                 REQUESTW2,
                 REQUESTW3,
                 REQUESTW4,
                 REQUESTW5,
                 ADJSW1,
                 ADJSW2,
                 ADJSW3,
                 ADJSW4,
                 ADJSW5,
                 PAIDW1,
                 PAIDW2,
                 PAIDW3,
                 PAIDW4,
                 PAIDW5,
                 AMOUNTINV FROM ( SELECT DISTINCT
                                 CASE
                                    WHEN cf.doctype IN ('PDO', 'INV_AP_SPC', 'INV_AR_SPC','LEASING','LOAN')
                                    THEN
                                       cf.DEPARTMENT
                                    ELSE
                                       docs.DEPARTMENT
                                 END
                                    AS DEPARTMENT,
                                 CASE
                                    WHEN cf.doctype IN ('PDO', 'INV_AP_SPC', 'INV_AR_SPC','LEASING','LOAN')
                                    THEN
                                       cf.businessunit
                                    ELSE
                                       docs.businessunit
                                 END
                                    AS BUSINESSUNITCODE,
                                 CF.DOCNUMBER,
                                 CF.DOCTYPE,
                                 CF.DOCDATE,
                                 CF.DOCREF,
                                 CF.DUEDATE,
                                 C.COMPANYCODE,
                                 S.FCNAME,
                                 FF.MONTH,
                                 FF.YEAR,
                                 ROUND (DECODE (FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW1,
                                 ROUND (DECODE (FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW2,
                                 ROUND (DECODE (FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW3,
                                 ROUND (DECODE (FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW4,
                                 ROUND (DECODE (FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0), 2)
                                    AS REQUESTW5,
                                 ROUND (DECODE (FF.WEEK, 'W1', FF.AMOUNTADJS, 0), 2) AS ADJSW1,
                                 ROUND (DECODE (FF.WEEK, 'W2', FF.AMOUNTADJS, 0), 2) AS ADJSW2,
                                 ROUND (DECODE (FF.WEEK, 'W3', FF.AMOUNTADJS, 0), 2) AS ADJSW3,
                                 ROUND (DECODE (FF.WEEK, 'W4', FF.AMOUNTADJS, 0), 2) AS ADJSW4,
                                 ROUND (DECODE (FF.WEEK, 'W5', FF.AMOUNTADJS, 0), 2) AS ADJSW5,
                                 0 PAIDW1,
                                 0 PAIDW2,
                                 0 PAIDW3,
                                 0 PAIDW4,
                                 0 PAIDW5,
                                 (CF.AMOUNT_INCLUDE_VAT - NVL (CF.AMOUNT_PPH, 0)) AS AMOUNTINV
                                    FROM (SELECT * FROM FORECAST_FIX UNION ALL SELECT * FROM FORECAST_FIX_TEMP) FF
                                         INNER JOIN CF_TRANSACTION CF ON CF.ID = FF.CFTRANSID
                                         LEFT JOIN
                                           (SELECT b.company,
                                                   b.docnumber,
                                                   a.material,
                                                   b.businessunit,
                                                   b.department
                                              FROM cf_transaction_det a
                                                   INNER JOIN cf_transaction b ON (a.id = b.id))
                                           docs
                                              ON (    cf.company = docs.company
                                                  AND cf.docref = docs.docnumber)
                                         INNER JOIN COMPANY C ON C.ID = CF.COMPANY
                                         INNER JOIN COMPANY_SUBGROUP CSG
                                            ON CSG.FCCODE = C.COMPANY_SUBGROUP
                                         INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
                                         INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE                 
                                         INNER JOIN SUPPLIER S ON S.ID = CF.VENDOR
                                   WHERE     FF.YEAR = '".$YEAR."'
                                         AND FF.MONTH = '".$MONTH."' ".$WHERE1 ." )".$WHEREDEPT;
        $SQL .= " UNION ALL
          SELECT DEPARTMENT,
                 BUSINESSUNITCODE,
                 DOCNUMBER,
                 DOCTYPE,
                 DOCDATE,
                 DOCREF,
                 DUEDATE,
                 COMPANYCODE,
                 FCNAME,
                 MONTH,
                 YEAR,
                 REQUESTW1,
                 REQUESTW2,
                 REQUESTW3,
                 REQUESTW4,
                 REQUESTW5,
                 ADJSW1,
                 ADJSW2,
                 ADJSW3,
                 ADJSW4,
                 ADJSW5,
                 PAIDW1,
                 PAIDW2,
                 PAIDW3,
                 PAIDW4,
                 PAIDW5,
                 AMOUNTINV FROM ( SELECT DISTINCT
                 docs.DEPARTMENT,
                 BU.FCCODE AS BUSINESSUNITCODE,
                 CF.DOCNUMBER,
                 CF.DOCTYPE,
                 CF.DOCDATE,
                 CF.DOCREF,
                 CF.DUEDATE,
                 C.COMPANYCODE,
                 S.FCNAME,
                 TO_NUMBER (TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
                 TO_NUMBER (TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
                 0 REQUESTW1,
                 0 REQUESTW2,
                 0 REQUESTW3,
                 0 REQUESTW4,
                 0 REQUESTW5,
                 0 ADJSW1,
                 0 ADJSW2,
                 0 ADJSW3,
                 0 ADJSW4,
                 0 ADJSW5,
                 ROUND (DECODE (P.WEEK, 'W1', P.AMOUNTPAYMENT, 0), 2) AS PAIDW1,
                 ROUND (DECODE (P.WEEK, 'W2', P.AMOUNTPAYMENT, 0), 2) AS PAIDW2,
                 ROUND (DECODE (P.WEEK, 'W3', P.AMOUNTPAYMENT, 0), 2) AS PAIDW3,
                 ROUND (DECODE (P.WEEK, 'W4', P.AMOUNTPAYMENT, 0), 2) AS PAIDW4,
                 ROUND (DECODE (P.WEEK, 'W5', P.AMOUNTPAYMENT, 0), 2) AS PAIDW5,
                 (CF.AMOUNT_INCLUDE_VAT - NVL (CF.AMOUNT_PPH, 0)) AS AMOUNTINV
            FROM (SELECT PAY.PAYMENTID,
                         PAY.DATERELEASE,
                         PAY.AMOUNT AMOUNTPAYMENT,
                         PAY.CFTRANSID,
                         (SELECT SW.WEEK
                            FROM SETTING_WEEK SW
                           WHERE     SW.YEAR =
                                        TO_NUMBER (
                                           TO_CHAR (PAY.DATERELEASE, 'yyyy'))
                                 AND SW.MONTH =
                                        TO_NUMBER (
                                           TO_CHAR (PAY.DATERELEASE, 'mm'))
                                 AND SW.DATEFROM <=
                                        TO_NUMBER (
                                           TO_CHAR (PAY.DATERELEASE, 'dd'))
                                 AND SW.DATEUNTIL >=
                                        TO_NUMBER (
                                           TO_CHAR (PAY.DATERELEASE, 'dd')))
                            AS WEEK,
                         SW.DATEFROM,
                         SW.DATEUNTIL,
                         SW.MONTH,
                         SW.YEAR
                    FROM PAYMENT PAY
                         INNER JOIN SETTING_WEEK SW
                            ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                       SW.DATEFROM
                                AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                       SW.DATEUNTIL
                                AND SW.MONTH = TO_CHAR (PAY.DATERELEASE, 'MM')
                                AND SW.YEAR = TO_CHAR (PAY.DATERELEASE, 'YYYY')))
                 P
                 INNER JOIN CF_TRANSACTION CF ON CF.ID = P.CFTRANSID
                 LEFT JOIN
                           (SELECT b.company,
                                   b.docnumber,
                                   a.material,
                                   b.businessunit,
                                   b.department
                              FROM cf_transaction_det a
                                   INNER JOIN cf_transaction b ON (a.id = b.id))
                           docs
                              ON (    cf.company = docs.company
                                  AND cf.docref = docs.docnumber)
                 INNER JOIN COMPANY C ON C.ID = CF.COMPANY
                 INNER JOIN COMPANY_SUBGROUP CSG
                    ON CSG.FCCODE = C.COMPANY_SUBGROUP
                 INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
                 INNER JOIN BUSINESSUNIT BU
                    ON     BU.ID = docs.BUSINESSUNIT
                       AND BU.COMPANY = CF.COMPANY
                 INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                 INNER JOIN SUPPLIER S ON S.ID = CF.VENDOR
           WHERE     TO_CHAR (P.DATERELEASE, 'MM') = '".$MONTH2."'
                 AND TO_CHAR (P.DATERELEASE, 'YYYY') = '".$YEAR."' ".$WHERE;
                 $SQL .= " AND CF.DOCNUMBER NOT LIKE '%TMPINV%') )".$WHEREDEPT; 
                 $SQL .= " GROUP BY DEPARTMENT,BUSINESSUNITCODE,DOCNUMBER,DOCTYPE,DOCDATE,DOCREF,DUEDATE,COMPANYCODE,FCNAME";
            // $ParamW = [$Data["USERNAME"], $Data["YEAR"], $Data["MONTH"]];
            // if ($Data["DEPARTMENT"] == "ALL") {
            //     $FDepartment = "Department : All Department";
            // } else {
            //     $FDepartment = "Department : " . $Data["DEPARTMENT"];
            // $SQL .= " AND NVL(CFPO.DEPARTMENT, CFT.DEPARTMENT) LIKE '" . $Data["DEPARTMENT"] . "'";
            // array_push($ParamW);
                // echo($Data["DEPARTMENT"]);
            // }
            // $SQL .= " ORDER BY CFT.DEPARTMENT, CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER";
            $result = $this->db->query($SQL)->result();
            //var_dump($this->db->last_query());exit();
            $FPeriod = "Period Forecash to Payment : " . Carbon::parse($Data["YEAR"] . substr("0" . $Data["MONTH"], -2) . "01")->format('Y-M');
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("IT GAMA")
                ->setLastModifiedBy("IT GAMA")
                ->setTitle("Report Forecash to Payment")
                ->setSubject("Report Forecash to Payment")
                ->setDescription("Data Document in System $FDepartment, $FPeriod, $GExport")
                ->setKeywords("Report Forecash to Payment")
                ->setCategory("Report Forecash to Payment");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Data');
            $i = 1;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(23);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);

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
            // $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FDepartment);
            // $i++;
            // $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FPeriod);
            // $i++;
            // $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $GExport);
            // $i++;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . ($i + 1))->applyFromArray([
                'font' => $StyleBold, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
            ]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'No');
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':A' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'Company');
            $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':B' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'Business Unit');
            $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':C' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'Department');
            $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':D' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'Doc Type');
            $objPHPExcel->getActiveSheet()->mergeCells('E' . $i . ':E' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'Doc Number');
            $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':F' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'Doc Date');
            $objPHPExcel->getActiveSheet()->mergeCells('G' . $i . ':G' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'Doc Reference');
            $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':H' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'Vendor');
            $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':I' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'Due Date');
            $objPHPExcel->getActiveSheet()->mergeCells('J' . $i . ':J' . ($i + 1));;
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'Amount Doc');
            $objPHPExcel->getActiveSheet()->mergeCells('K' . $i . ':K' . ($i + 1));;
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'Requested');
            $objPHPExcel->getActiveSheet()->mergeCells('L' . $i . ':P' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'Adjusted');
            $objPHPExcel->getActiveSheet()->mergeCells('Q' . $i . ':U' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'Paid');
            $objPHPExcel->getActiveSheet()->mergeCells('V' . $i . ':Z' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, 'W5');
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, 'W5');
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, 'W5');
            $i++;

            if (count($result) > 0) {
                $iDtAwal = $i;
                $No = 1;
                foreach ($result as $values) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $No);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $values->COMPANYCODE);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $values->BUSINESSUNITCODE);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $values->DEPARTMENT);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $values->DOCTYPE);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, $values->DOCNUMBER, DataType::TYPE_STRING);
                    if ($values->DOCDATE != NULL && $values->DOCDATE != "") {
                        $DtDate = explode('/', $values->DOCDATE);
                        $XlsTime = gmmktime(0, 0, 0, intval($DtDate[1]), intval($DtDate[2]), intval($DtDate[0]));
                        $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, Date::PHPToExcel($XlsTime));
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                    }
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $i, $values->DOCREF, DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $values->VENDORNAME);
                    if ($values->DUEDATE != NULL && $values->DUEDATE != "") {
                        $DtDate = explode('/', $values->DUEDATE);
                        $XlsTime = gmmktime(0, 0, 0, intval($DtDate[1]), intval($DtDate[2]), intval($DtDate[0]));
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, Date::PHPToExcel($XlsTime));
                        $objPHPExcel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $values->AMOUNTINV);
                    $objPHPExcel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $values->REQUESTW1);
                    $objPHPExcel->getActiveSheet()->getStyle('L' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $values->REQUESTW2);
                    $objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $values->REQUESTW3);
                    $objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $values->REQUESTW4);
                    $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $values->REQUESTW5);
                    $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $values->ADJSW1);
                    $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $values->ADJSW2);
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $values->ADJSW3);
                    $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $values->ADJSW4);
                    $objPHPExcel->getActiveSheet()->getStyle('T' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $values->ADJSW5);
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $values->PAIDW1);
                    $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $values->PAIDW2);
                    $objPHPExcel->getActiveSheet()->getStyle('W' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $values->PAIDW3);
                    $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $values->PAIDW4);
                    $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $values->PAIDW5);
                    $objPHPExcel->getActiveSheet()->getStyle('Z' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $i++;
                    $No++;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A' . $iDtAwal . ':Z' . ($i - 1))->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                // $objPHPExcel->getActiveSheet()->getStyle('G' . $iDtAwal . ':G' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "No Data");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':Z' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . $i)->applyFromArray([
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
}
