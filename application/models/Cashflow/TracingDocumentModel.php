<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TracingDocumentModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }

    public function TracingDocument($param) {
        $COMPANY = $param["COMPANY"];
        $DOCNUMBER = $param["DOCNUMBER"];

        $SQL = "SELECT co.companycode,
       po.docnumber AS docnumber_source,
       po.docdate AS docdate_source,
       po.amount_include_vat AS amount_source,
       inv.docnumber AS docnumber_inv,
       inv.docdate AS docdate_inv,
       inv.amount_include_vat AS amount_inv,
       SUM (inv.amount_include_vat)
          OVER (PARTITION BY co.companycode || po.docnumber)
          AS total_inv,
       inv.amount_pph,
       SUM (inv.amount_pph)
          OVER (PARTITION BY co.companycode || po.docnumber)
          AS total_pph,
       NVL (fc.amountrequest, 0) AS amountrequest,
       SUM (fc.amountrequest)
          OVER (PARTITION BY co.companycode || po.docnumber)
          AS total_amountrequest,
       NVL (fc.amountadjs, 0) AS amountadjs,
       SUM (fc.amountadjs) OVER (PARTITION BY co.companycode || po.docnumber)
          AS total_amountadjs,
       payment.voucherno,
       bank.fcname AS bankname,
       bank.bankaccount,
       payment.daterelease,
       payment.nocekgiro,
       NVL (payment.debet, 0) AS debet,
       SUM (payment.debet) OVER (PARTITION BY co.companycode || po.docnumber)
          AS total_debet,
       NVL (payment.credit, 0) AS credit,
       SUM (payment.credit)
          OVER (PARTITION BY co.companycode || po.docnumber)
          AS total_credit
  FROM (SELECT id,
               company,
               docnumber,
               docdate,
               amount_include_vat
          FROM cf_transaction
         WHERE doctype <> 'INV' AND docnumber = '$DOCNUMBER') po
       LEFT JOIN
       (SELECT id,
               company,
               docnumber,
               docref,
               docdate,
               SUM (amount_include_vat) OVER (PARTITION BY company || docnumber)
                  AS amount_include_vat,
               SUM (amount_pph) OVER (PARTITION BY company || docnumber)
                  AS amount_pph
          FROM cf_transaction
         WHERE doctype IN ('INV', 'INV_AR') AND docref = '$DOCNUMBER') inv
          ON (po.company = inv.company AND po.docnumber = inv.docref)
       LEFT JOIN
       (  SELECT cftransid,
                 SUM (amountrequest) AS amountrequest,
                 SUM (amountadjs) AS amountadjs
            FROM forecast_fix
        GROUP BY cftransid) fc
          ON (fc.cftransid = po.id OR fc.cftransid = inv.id)
       LEFT JOIN (SELECT cftransid,
                         voucherno,
                         bankcode,
                         daterelease,
                         nocekgiro,
                         cashflowtype,
                         remark,
                         debet,
                         credit
                    FROM (  SELECT payment.cftransid,
                                   payment.voucherno,
                                   payment.bankcode,
                                   payment.daterelease,
                                   payment.nocekgiro,
                                   payment.cashflowtype,
                                   MAX (payment.remark) AS remark,
                                   NVL (SUM (payment.amount), 0) AS debet,
                                   0 AS credit
                              FROM payment
                             WHERE payment.cashflowtype = '0'
                          GROUP BY payment.voucherno,
                                   payment.bankcode,
                                   payment.daterelease,
                                   payment.nocekgiro,
                                   payment.cashflowtype,
                                   payment.cftransid
                          UNION ALL
                            SELECT payment.cftransid,
                                   payment.voucherno,
                                   payment.bankcode,
                                   payment.daterelease,
                                   payment.nocekgiro,
                                   payment.cashflowtype,
                                   MAX (payment.remark) AS remark,
                                   0 AS debet,
                                   NVL (SUM (payment.amount), 0) AS credit
                              FROM payment
                             WHERE payment.cashflowtype = '1'
                          GROUP BY payment.voucherno,
                                   payment.bankcode,
                                   payment.daterelease,
                                   payment.nocekgiro,
                                   payment.cashflowtype,
                                   payment.cftransid)
                 ) payment
          ON (payment.cftransid = inv.id OR payment.cftransid = po.id)
       INNER JOIN (SELECT id, companycode, companyname FROM company) co
          ON (po.company = co.id)
       FULL JOIN (SELECT fccode, bankaccount, fcname FROM bank) bank
          ON (payment.bankcode = bank.fccode)
 WHERE co.companycode = '$COMPANY' AND
         po.docnumber = '$DOCNUMBER'";
        // var_dump($SQL);exit();
        $result = $this->db->query($SQL)->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function TracingDocumentDet($param){
        // throw new Exception("lagi dites admin, comeback later");
        // exit;
        
        // $COMPANY        = $param['COMPANY'];
        // $COMPANYGROUP   = $param['COMPANYGROUP'];
        // $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
        $DOCNUMBER = $param["DOCNUMBER"];

        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        $q = '';
        // if($COMPANY != '' || null){
        //     $q .= " WHERE co.companycode = '$COMPANY'";
        // }
        // if($COMPANY == '' || null){
        //     $q .= " WHERE co.companycode LIKE '%$COMPANY%'";
        // }
        // if($COMPANYGROUP != '' || null){
        //     $q .= " AND bs.COMPANYGROUP = '$COMPANYGROUP' ";
        // }
        // if($COMPANYSUBGROUP != '' || null){
        //     $q .= " AND bs.COMPANY_SUBGROUP = '$COMPANYSUBGROUP'";
        // }

        if($DOCNUMBER != '' || null){
            $q .= " WHERE PO.DOCNUMBER = '$DOCNUMBER' OR INV.DOCNUMBER = '$DOCNUMBER' OR INV.DOCREF = '$DOCNUMBER'";
        }
        $query = "(select DISTINCT 
                    co.companycode,
                    po.department as department,
                    po.docnumber as docnumber_source,
                    po.docdate as docdate_source,
                    po.amount_include_vat as amount_source,
                    inv.docnumber as docnumber_inv,
                    inv.invoicevendorno as inv_vendorno,
                    inv.currency as inv_currency,
                    inv.duedate as inv_duedate,
                    inv.docdate as docdate_inv,
                    supplier.fcname as vendorname,
                    inv.amount_include_vat as amount_inv,
                    sum(inv.amount_include_vat) over (partition by co.companycode||po.docnumber) as total_inv,
                    inv.amount_pph,
                    sum(inv.amount_pph) over (partition by co.companycode||po.docnumber) as total_pph,
                    nvl(fc.amountrequest,0) as amountrequest,
                    fc.year,
                    fc.month,
                    fc.week,
                    sum(fc.amountrequest) over (partition by co.companycode||inv.docnumber||fc.year||fc.month) as total_amountrequest,
                    nvl(fc.amountadjs,0) as amountadjs,
                    sum(fc.amountadjs) over (partition by co.companycode||inv.docnumber||fc.year||fc.month) as total_amountadjs,
                    payment.voucherno,
                    bank.fcname as bankname,
                    bank.bankaccount,
                    payment.daterelease,
                    payment.nocekgiro,
                    nvl(payment.debet,0) as debet,
                    sum(payment.debet) over (partition by co.companycode||po.docnumber) as total_debet,
                    nvl(payment.credit,0) as credit,
                    sum(payment.credit) over (partition by co.companycode||po.docnumber) as total_credit
                    from (
                        select
                        id,
                        company,
                        department,
                        docnumber,
                        docdate,
                        amount_include_vat
                        from cf_transaction
                        where doctype <> 'INV'
                    ) po
                    left join (
                        select
                        id,
                        company,
                        docnumber,
                        docref,
                        currency,
                        duedate,
                        invoicevendorno,
                        docdate,
                        vendor,
                        sum (amount_include_vat) over (partition by company || docref || docnumber) as amount_include_vat,
                        sum (amount_pph) over (partition by company || docref || docnumber) as amount_pph              
                        from cf_transaction
                        where doctype in ( 'INV','INV_AR')
                    ) inv 
                    on (
                    po.company = inv.company and
                    po.docnumber = inv.docref)
                    left join (
                        select
                        cftransid,
                        year,
                        month,
                        week,
                        sum(amountrequest) as amountrequest,
                        sum(amountadjs) as amountadjs  
                        from forecast_fix
                        group by
                        cftransid,
                        year,
                        month,week
                    ) fc
                    on (
                    fc.cftransid = po.id or
                    fc.cftransid = inv.id)
                    left join (
                        select
                            payment.cftransid, 
                            payment.voucherno,
                            payment.bankcode,
                            payment.daterelease,
                            payment.nocekgiro,
                            payment.cashflowtype,
                            max(payment.remark) as remark,   
                            nvl (sum (payment.amount), 0) as debet,
                            0 as credit
                        from payment                                 
                        where payment.cashflowtype = '0'
                        group by 
                            payment.voucherno,
                            payment.bankcode,
                            payment.daterelease,
                            payment.nocekgiro,
                            payment.cashflowtype,
                            payment.cftransid        
                        union all
                        select 
                            payment.cftransid,
                            payment.voucherno,
                            payment.bankcode,
                            payment.daterelease,
                            payment.nocekgiro,
                            payment.cashflowtype,
                        max(payment.remark) as remark,              
                        0 as debet,
                        nvl (sum (payment.amount), 0) as credit
                        from payment                                
                        where payment.cashflowtype = '1'
                        group by 
                            payment.voucherno,
                            payment.bankcode,
                            payment.daterelease,
                            payment.nocekgiro,
                            payment.cashflowtype,
                            payment.cftransid   
                    ) payment
                    on (
                    payment.cftransid = inv.id or
                    payment.cftransid = po.id)
                    inner join (
                        select 
                        id,
                        companycode,
                        companyname 
                        from company
                    ) co
                    on ( po.company = co.id)
                    FULL join (
                        select 
                        fccode,
                        bankaccount,
                        fcname 
                        from bank
                    ) bank
                    on ( payment.bankcode = bank.fccode)
                    inner join (
                        select
                        id,
                        fccode,
                        fcname 
                        from supplier
                    ) supplier
                    on ( inv.vendor = supplier.id) INNER JOIN (SELECT company,fccode,companygroup,company_subgroup FROM BUSINESSUNIT) bs
          ON (po.company = bs.company) ".$q . ")";
          // var_dump($query);exit();
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
            $SQLO = " ORDER BY COMPANYCODE ASC";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $query FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $query FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $query FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

}