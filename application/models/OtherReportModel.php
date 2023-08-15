<?php

defined('BASEPATH') or exit('No direct script access allowed');

class OtherReportModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }

    public function ReportOSnAging($param) {
        return [];
    }

    public function ReportPayment($param) {
        // var_dump($param);exit();
        if($param['DEPARTMENT'] == null || $param['DEPARTMENT'] == ''){
            $DEPARTMENT = '%'.$param['DEPARTMENT'].'%';
            $q = " AND asli.DEPARTMENT LIKE '$DEPARTMENT' ";
        }else{
            $DEPARTMENT = $param['DEPARTMENT'];
            $q = " AND asli.DEPARTMENT = '$DEPARTMENT' ";
        }
        
        $DATEFROM =  date("m/d/Y", strtotime($param['DATEFROM']));
        $DATETO =  date("m/d/Y", strtotime($param['DATETO']));

        $SQL = "SELECT asli.DEPARTMENT,
         asli.COMPANY,
         asli.VOUCHERNO,
         asli.CURRENCY,
         c.COMPANYNAME,
         asli.BUSINESSUNIT,
         b.FCNAME BUSINESSUNITNAME,
         asli.VENDOR,
         s.FCNAME SUPPLIERNAME,
         asli.DOCNUMBER,
         asli.APINV,
         SUM (asli.AMOUNT) AMOUNT,
         SUM (asli.PAYAMOUNT) PAYAMOUNT,
         asli.REMARK,
         asli.DATERELEASE,
         asli.INVOICEVENDORNO
    FROM (SELECT CFREF.DEPARTMENT,
                 CF.COMPANY,
                 PAY.VOUCHERNO,
                 CF.CURRENCY,
                 CF.BUSINESSUNIT,
                 CF.VENDOR,
                 CFREF.DOCNUMBER,
                 CF.DOCNUMBER APINV,
                 NVL (CF.TOTAL_BAYAR, CF.AMOUNT_INCLUDE_VAT) AMOUNT,
                 PAY.AMOUNT PAYAMOUNT,
                 PAY.REMARK,
                 PAY.DATERELEASE,
                 CF.INVOICEVENDORNO
            FROM PAYMENT PAY
                 INNER JOIN CF_TRANSACTION CF ON PAY.CFTRANSID = CF.ID
                 INNER JOIN CF_TRANSACTION CFREF ON CF.DOCREF = CFREF.DOCNUMBER
          UNION ALL
          SELECT (SELECT remark
                    FROM bmcodemaster
                   WHERE masterid = '000015')
                    AS department,
                 COMPANY,
                 VOUCHERNO,
                 '',
                 '',
                 VENDOR,
                 '',
                 '',
                 0,
                 AMOUNT PAYAMOUNT,
                 REMARKS,
                 DATERELEASE,
                 ''
            FROM PAYMENT_OTHER PO
           WHERE material IN
                    (SELECT DISTINCT material_2
                       FROM material_groupitem
                      WHERE materialgroup_2 IN
                               (SELECT ID
                                  FROM material_group
                                 WHERE fccode IN (SELECT detailid
                                                    FROM bmcodeDETAIL
                                                   WHERE MASTERID = '000005')))
          UNION ALL
          SELECT (SELECT remark
                    FROM bmcodemaster
                   WHERE masterid = '000016')
                    AS department,
                 COMPANY,
                 VOUCHERNO,
                 '',
                 '',
                 VENDOR,
                 '',
                 '',
                 0,
                 AMOUNT PAYAMOUNT,
                 REMARKS,
                 DATERELEASE,
                 ''
            FROM PAYMENT_OTHER PO
           WHERE material NOT IN
                    (SELECT DISTINCT material_2
                       FROM material_groupitem
                      WHERE materialgroup_2 IN
                               (SELECT ID
                                  FROM material_group
                                 WHERE fccode IN (SELECT detailid
                                                    FROM bmcodeDETAIL
                                                   WHERE MASTERID = '000005'))))
         asli
         INNER JOIN COMPANY c ON asli.COMPANY = c.ID
         LEFT JOIN BUSINESSUNIT b ON asli.BUSINESSUNIT = b.ID
         LEFT JOIN SUPPLIER s ON asli.VENDOR = s.ID
   WHERE     asli.DATERELEASE >= TO_DATE ('$DATEFROM', 'MM/DD/YYYY')
         AND asli.DATERELEASE <= TO_DATE ('$DATETO', 'MM/DD/YYYY') ".$q;

    $SQL .= " GROUP BY asli.DEPARTMENT,
         asli.COMPANY,
         asli.VOUCHERNO,
         asli.CURRENCY,
         c.companyname,
         asli.BUSINESSUNIT,
         b.fcname,
         asli.VENDOR,
         s.fcname,
         asli.DOCNUMBER,
         asli.APINV,
         asli.REMARK,
         asli.DATERELEASE,
         asli.INVOICEVENDORNO";
        
       //  $SQL = "SELECT CFREF.DEPARTMENT, CF.COMPANY, PAY.VOUCHERNO, CF.CURRENCY, COMPANY.COMPANYNAME, CF.BUSINESSUNIT, BUSINESSUNIT.FCNAME BUSINESSUNITNAME, CF.VENDOR,SUPPLIER.FCNAME SUPPLIERNAME, CFREF.DOCNUMBER, CF.DOCNUMBER APINV, NVL(CF.TOTAL_BAYAR,CF.AMOUNT_INCLUDE_VAT)AMOUNT, PAY.AMOUNT PAYAMOUNT, PAY.REMARK, PAY.DATERELEASE,CF.INVOICEVENDORNO
       //              FROM PAYMENT PAY
       //                  INNER JOIN CF_TRANSACTION CF ON PAY.CFTRANSID = CF.ID
       //                  INNER JOIN COMPANY ON CF.COMPANY = COMPANY.ID 
       //                  INNER JOIN BUSINESSUNIT ON CF.BUSINESSUNIT = BUSINESSUNIT.ID
       //                  INNER JOIN SUPPLIER ON CF.VENDOR = SUPPLIER.ID
       //                  INNER JOIN CF_TRANSACTION CFREF
       //                      ON CF.DOCREF = CFREF.DOCNUMBER ";
       //              $SQL .= $q;
       //              $SQL .= "WHERE PAY.DATERELEASE >= to_date (?, 'MM/DD/YYYY')
       //              AND PAY.DATERELEASE <= to_date (?, 'MM/DD/YYYY')";

       //  if ($param['DEPARTMENT'] === 'FINANCE') {
       //      $SQL .= "UNION ALL SELECT '' DEPARTMENT, '' COMPANY, PO.VOUCHERNO,
       // PO.NOCEKGIRO,COMPANY.COMPANYNAME, '' BUSINESSUNIT, '' BUSINESSUNITNAME,
       //      '' VENDOR, SUPPLIER.FCNAME SUPPLIERNAME, '' DOCNUMBER, '' APINV, 0 AMOUNT, PO.AMOUNT PAYAMOUNT, PO.REMARKS REMARK, PO.DATERELEASE
       //          FROM PAYMENT_OTHER PO
       //              INNER JOIN COMPANY ON PO.COMPANY = COMPANY.ID 
       //              INNER JOIN SUPPLIER ON PO.VENDOR = SUPPLIER.ID
       //          WHERE PO.DATERELEASE >= to_date ('$DATEFROM', 'MM/DD/YYYY')
       //          AND PO.DATERELEASE <= to_date ('$DATETO', 'MM/DD/YYYY')";
       //  }

        $result = $this->db->query($SQL)->result();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

}