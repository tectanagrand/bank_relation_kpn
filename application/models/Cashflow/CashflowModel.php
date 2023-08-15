<?php

defined('BASEPATH') or exit('No direct script access allowed');

class CashflowModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }

    public function ShowData($param) {
        $dateFromOrigin = $param['DATEFROM'];
        $monthoNLY = date("m", strtotime($dateFromOrigin));
        $year = date("Y", strtotime($dateFromOrigin));
        $dateOnly = date("md", strtotime($dateFromOrigin));

        $fromto = $monthoNLY.'01';

        $SQL = "select
                    rownum,
                    bankname,
                    paymenttype,
                    daterelease,
                    voucherno,
                    nocekgiro,
                    vendorname,
                    remark,
                    docnumber,
                    (saldo_awal_per_hari+nvl(((sum(debet-credit) over (order by rownum))+credit-debet),0)) as opening,
                    debet,
                    credit,
                    (saldo_awal_per_hari+nvl(((sum(debet-credit) over (order by rownum))+credit-debet),0))+debet-credit as ending,
                    amountbank
                    from (
                        select
                        case
                            when bankname_pv is null then bankname_tb
                            when bankname_tb is null then bankname_pv
                            else bankname_tb
                            end as bankname,
                        case
                            when cashflowtype = 1 then 'Payment'
                            else 'Receive'
                            end as paymenttype,
                        daterelease,
                        voucherno,
                        nocekgiro,
                        vendorname,
                        remark,
                        docnumber,
                        saldo_awal_per_hari,
                        debet,
                        credit,
                        amountbank
                        from (
                            select 
                            pv3.voucherno,
                            tb_monthly.bankcode as bankcode_tb,
                            pv3.bankcode as bankcode_pv,
                            tb_monthly.bankname as bankname_tb,
                            pv3.bankname as bankname_pv,
                            pv3.daterelease,
                            pv3.nocekgiro,
                            pv3.cashflowtype,
                            pv3.vendorname,
                            pv3.remark,
                            pv3.docnumber,
                            nvl(total_pv_daily.amount_yesterday_pv,0) as amount_yesterday_pv,
                            case
                                when nvl(total_pv_daily.amount_yesterday_pv,0) = 0 then nvl(tb_monthly.opening_balance_monthly,0)
                                else nvl(tb_monthly.opening_balance_monthly,0)+nvl(total_pv_daily.amount_yesterday_pv,0)
                                end as saldo_awal_per_hari,
                            pv3.debet,
                            pv3.credit,
                            pv3.amountbank
                            from (
                                select bb.bankcode,bank.fcname as bankname, bb.period_year,bb.period_month,nvl (sum (bb.opening_balance), 0) as opening_balance_monthly
                                from bankbalance bb
                                inner join bank on (bb.bankcode = bank.fccode)                 
                                where bb.bankcode = '".$param['BANKCODE']."'
                                and bb.period_month = '".$monthoNLY."'
                                and bb.period_year = '".$year."'
                                group by bb.bankcode,bank.fcname,bb.period_year,bb.period_month
                            ) tb_monthly
                            full join (
                                select 
                                voucherno,
                                bankcode,
                                bankname,
                                daterelease,
                                nocekgiro,
                                cashflowtype,
                                vendor,
                                vendorname,
                                docnumber,
                                remark,      
                                debet,
                                credit,
                                amountbank
                                from (
                                    select
                                    pv2.voucherno,
                                    pv2.bankcode,
                                    bank.fcname as bankname,
                                    pv2.daterelease,
                                    pv2.nocekgiro,
                                    pv2.cashflowtype,
                                    pv2.vendor,
                                    supplier.fcname as vendorname,
                                    pv2.docnumber,
                                    pv2.remark,      
                                    pv2.debet,
                                    pv2.credit,
                                    pv2.amountbank
                                    from (
                                        select
                                        pv.voucherno,
                                        pv.bankcode,
                                        pv.daterelease,
                                        pv.nocekgiro,
                                        pv.cashflowtype,
                                        cf_transaction.vendor,
                                        cf_transaction.docnumber,
                                        pv.remark,      
                                        pv.debet,
                                        pv.credit,
                                        pv.amountbank
                                        from (        
                                            select 
                                            payment.voucherno,
                                            payment.bankcode,
                                            payment.daterelease,
                                            payment.nocekgiro,
                                            payment.cashflowtype,
                                            payment.cftransid,
                                            max(payment.remark) as remark,   
                                            nvl (sum (payment.amountbank), 0) as debet,
                                            0 as credit,
                                            0 as amountbank
                                            from payment                                 
                                            where payment.cashflowtype = '0'
                                            and to_char(payment.daterelease,'yyyy') = '".$year."'
                                            group by 
                                            payment.voucherno,
                                            payment.bankcode,
                                            payment.daterelease,
                                            payment.nocekgiro,
                                            payment.cashflowtype,
                                            payment.cftransid        
                                            union all
                                            select 
                                            payment.voucherno,
                                            payment.bankcode,
                                            payment.daterelease,
                                            payment.nocekgiro,
                                            payment.cashflowtype,
                                            payment.cftransid,
                                            max(payment.remark) as remark,              
                                            0 as debet,
                                            nvl (sum (payment.amountbank), 0) as credit,
                                            MAX (payment.amountbank) as amountbank
                                            from payment                                
                                            where payment.cashflowtype = '1'
                                            and to_char(payment.daterelease,'yyyy') = '".$year."'
                                            group by 
                                            payment.voucherno,
                                            payment.bankcode,
                                            payment.daterelease,
                                            payment.nocekgiro,
                                            payment.cashflowtype,
                                            payment.cftransid   
                                        ) pv  
                                        inner join cf_transaction on (
                                        pv.cftransid = cf_transaction.id) 
                                        union all
                                        select
                                        payment_other.voucherno,        
                                        payment_other.bankcode,
                                        payment_other.daterelease,
                                        payment_other.nocekgiro,
                                        payment_other.cashflowtype,       
                                        payment_other.vendor,
                                        '' as docnumber,
                                        max(payment_other.remarks) as remark,        
                                        nvl (sum (payment_other.amount), 0) as debet,
                                        0 as credit,
                                        0 as amountbank
                                        from payment_other
                                        where payment_other.cashflowtype = '0'            
                                        and to_char(payment_other.daterelease,'yyyy') = '".$year."'
                                        group by
                                        payment_other.voucherno,        
                                        payment_other.bankcode,
                                        payment_other.daterelease,
                                        payment_other.nocekgiro,
                                        payment_other.cashflowtype,       
                                        payment_other.vendor,
                                        ''
                                        union all
                                        select
                                        payment_other.voucherno,        
                                        payment_other.bankcode,
                                        payment_other.daterelease,
                                        payment_other.nocekgiro,
                                        payment_other.cashflowtype,       
                                        payment_other.vendor,
                                        '' as docnumber,
                                        max(payment_other.remarks) as remark,        
                                        0 as debet,
                                        nvl (sum (payment_other.amount), 0) as credit,
                                        0 as amountbank
                                        from payment_other
                                        where payment_other.cashflowtype = '1'
                                        and to_char(payment_other.daterelease,'yyyy') = '".$year."'
                                        group by
                                        payment_other.voucherno,        
                                        payment_other.bankcode,
                                        payment_other.daterelease,
                                        payment_other.nocekgiro,
                                        payment_other.cashflowtype,       
                                        payment_other.vendor,
                                        ''
                                    ) pv2
                                    left join supplier on (
                                    pv2.vendor = supplier.id)        
                                    inner join bank on (
                                    pv2.bankcode = bank.fccode)     
                                    union all
                                    select 
                                    voucherno,
                                    bankcode,
                                    bank.fcname as bankname,
                                    daterelease,
                                    nocekgiro,
                                    cashflowtype,
                                    vendor,
                                    com.companycode||'-'||ba2.fcname||'-'||ba2.bankaccount as vendorname,
                                    docnumber,
                                    remark,
                                    debet,
                                    credit,
                                    amountbank        
                                    from (        
                                        select
                                        intercoloans.voucherno,
                                        intercoloans.banktarget as bankcode,
                                        intercoloans.daterelease,
                                        intercoloans.nocekgiro,
                                        0 as cashflowtype,
                                        intercoloans.banksource as vendor,
                                        '' as docnumber,
                                        max(intercoloans.remarks) as remark,
                                        nvl (sum (intercoloans.amount), 0) as debet,
                                        0 as credit,
                                        0 as amountbank
                                        from intercoloans
                                        where to_char(intercoloans.daterelease,'yyyy') = '".$year."'        
                                        group by
                                        intercoloans.voucherno,
                                        intercoloans.banktarget,
                                        intercoloans.daterelease,
                                        intercoloans.nocekgiro,
                                        0,
                                        intercoloans.banksource,
                                        ''
                                        union all
                                        select
                                        intercoloans.voucherno,
                                        intercoloans.banksource as bankcode,
                                        intercoloans.daterelease,
                                        intercoloans.nocekgiro,
                                        1 as cashflowtype,
                                        intercoloans.banktarget as vendor,
                                        '' as docnumber,
                                        max(intercoloans.remarks) as remark,
                                        0 as debet,
                                        nvl (sum (intercoloans.sourceamount), 0) as credit,
                                        0 as amountbank
                                        from intercoloans     
                                        where to_char(intercoloans.daterelease,'yyyy') = '".$year."'                      
                                        group by
                                        intercoloans.voucherno,
                                        intercoloans.banksource,
                                        intercoloans.daterelease,
                                        intercoloans.nocekgiro,
                                        1,
                                        intercoloans.banktarget,
                                        ''
                                    ) interco
                                    inner join bank on (
                                    interco.bankcode = bank.fccode)  
                                    inner join bank ba2 on (
                                    interco.vendor = ba2.fccode)
                                    inner join company com on (
                                    ba2.company = com.id)       
                                ) 
                                where
                                daterelease >= to_date ('".$param["DATEFROM"]."', 'MM/DD/YYYY')
                                and daterelease <= to_date ('".$param["DATETO"]."', 'MM/DD/YYYY') 
                                and bankcode = '".$param['BANKCODE']."'
                            ) pv3
                            on (
                            tb_monthly.bankcode = pv3.bankcode)
                            left join (
                                select 
                                bankcode,
                                sum(amount_yesterday_pv) as amount_yesterday_pv
                                from (
                                    select 
                                    payment.bankcode,
                                    payment.daterelease,
                                    payment.cashflowtype,
                                    case
                                        when payment.cashflowtype = '1' then nvl (sum (payment.amountbank), 0) * -1
                                        else nvl (sum (payment.amountbank), 0)
                                        end as amount_yesterday_pv
                                    from payment       
                                    where payment.cashflowtype in ( '0','1')
                                    and to_char(payment.daterelease,'yyyy') = '".$year."'
                                    group by 
                                    payment.bankcode,
                                    payment.daterelease,
                                    payment.cashflowtype
                                    union all
                                    select
                                    payment_other.bankcode,
                                    payment_other.daterelease,
                                    payment_other.cashflowtype,
                                    case
                                        when payment_other.cashflowtype = '1' then nvl (sum (payment_other.amount), 0) * -1
                                        else nvl (sum (payment_other.amount), 0)
                                        end as amount_yesterday_pv
                                    from payment_other
                                    where payment_other.cashflowtype in ( '0','1')
                                    and to_char(payment_other.daterelease,'yyyy') = '".$year."'
                                    group by
                                    payment_other.bankcode,
                                    payment_other.daterelease,
                                    payment_other.cashflowtype
                                    union all
                                    select 
                                    bankcode,
                                    daterelease,
                                    cashflowtype,
                                    case
                                        when cashflowtype = '1' then nvl (sum (amount), 0) * -1
                                        else nvl (sum (amount), 0)
                                        end as amount_yesterday_pv    
                                    from (        
                                        select
                                        intercoloans.banktarget as bankcode,
                                        intercoloans.daterelease,
                                        0 as cashflowtype,
                                        nvl (sum (intercoloans.amount), 0) as amount
                                        from intercoloans        
                                        group by
                                        intercoloans.banktarget,
                                        intercoloans.daterelease,
                                        0
                                        union all
                                        select
                                        intercoloans.banksource as bankcode,
                                        intercoloans.daterelease,
                                        1 as cashflowtype,
                                        nvl (sum (intercoloans.sourceamount), 0) as amount
                                        from intercoloans        
                                        group by
                                        intercoloans.banksource,
                                        intercoloans.daterelease,
                                        1
                                    )
                                    where to_char(daterelease,'yyyy') = '".$year."'
                                    group by
                                    bankcode,
                                    daterelease,
                                    cashflowtype
                                )
                                where 
                                to_char(daterelease,'yyyy') = '".$year."'
                                and to_number(to_char(daterelease,'mmdd')) >= $fromto
                                and to_number(to_char(daterelease,'mmdd')) <  $dateOnly
                                and bankcode = '".$param['BANKCODE']."'
                                group by
                                bankcode
                            ) total_pv_daily on (
                            tb_monthly.bankcode = total_pv_daily.bankcode or
                            pv3.bankcode = total_pv_daily.bankcode)
                        )  
                        order by daterelease
                    )";


        $query = $this->db->query($SQL)->result();
        // var_dump($this->db->last_query());exit;
        if (count($query) < 1) {
            $SQL = "select
                        bank.fcname as bankname,
                        '' as paymenttype,
                        '' as daterelease,
                        '' as voucherno,
                        '' as nocekgiro,
                        '' as vendorname,
                        '' as remark,
                        '' as docnumber,
                        tb.opening_balance_monthly as opening,
                        0 as debet,
                        0 as credit,
                        tb.opening_balance_monthly as ending
                        from (
                            select bankcode,period_year,period_month,nvl (sum (opening_balance), 0) as opening_balance_monthly
                            from bankbalance
                            group by bankcode,period_year,period_month
                        ) tb
                        inner join bank on (
                        tb.bankcode = bank.fccode)  
                        where 
                        bankcode = ?
                        and period_year = ?
                        and period_month = ?";

            $query = $this->db->query($SQL, [$param['BANKCODE'], $year, $monthoNLY])->result();
        }

        //harus dalam bulan yang sama
        //$result = $this->db->query($SQL, [$param["DATEFROM"], $param["DATEFROM"], $param["DATEFROM"], $param["DATEFROM"], $param["DATEFROM"], $param["DATETO"], $param["BANKCODE"]])->result();
        //$this->db->close();
        // var_dump($this->db->last_query());exit();
        return $query;
    }

    public function getDataBank($param) {
        $this->fillable = ['FCCODE', 'FCNAME', 'BANKACCOUNT'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->BANK")
                        ->like('FCCODE', $param)
                        ->or_like('FCNAME', $param)
                        ->or_like('BANKACCOUNT', $param)
                        ->order_by('FCNAME')->get()->result();
        $this->db->close();
        return $result;
    }

    public function getOpBal($param){
        $dateFromOrigin = $param['DATEFROM'];
        $monthONLY  = date("m", strtotime($dateFromOrigin));
        $year       = date("Y", strtotime($dateFromOrigin));
        $BANKCODE   = $param['BANKCODE'];

        $q = "SELECT a.company,
               a.bankcode,
               a.period_year,
               a.period_month,
               a.opening_balance_monthly,
               a.debet,
               a.credit,
               a.saldo,
               '',
               a.company AS company_next,
               a.bankcode AS bankcode_next,
               a.saldo AS opening_balance_monthly_next,
               CASE
                  WHEN a.period_month = 12 THEN a.period_year + 1
                  ELSE a.period_year
               END
                  AS period_year_next,
               CASE WHEN a.period_month = 12 THEN 1 ELSE a.period_month + 1 END
                  AS period_month_next,
               0 AS debet_next,
               0 AS credit_next,
               a.saldo AS ending_balance_monthly_next,
               a.currency AS currency_next
          FROM (SELECT tb_monthly.company,
                       tb_monthly.bankcode,
                       tb_monthly.period_year,
                       tb_monthly.period_month,
                       tb_monthly.opening_balance_monthly AS opening_balance_monthly,
                       NVL (pv3.debet, 0) AS debet,
                       NVL (pv3.credit, 0) AS credit,
                       (  tb_monthly.opening_balance_monthly
                        + NVL (pv3.debet, 0)
                        - NVL (pv3.credit, 0))
                          AS saldo,
                       tb_monthly.currency
                  FROM (  SELECT bankcode,
                                 year,
                                 month,
                                 SUM (debet) AS debet,
                                 SUM (credit) AS credit
                            FROM (  SELECT bankcode,
                                           TO_NUMBER (TO_CHAR (daterelease, 'YYYY'))
                                              AS year,
                                           TO_NUMBER (TO_CHAR (daterelease, 'MM'))
                                              AS month,
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
                                                             NVL (SUM (payment.amountbank),
                                                                  0)
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
                                                             NVL (SUM (payment.amountbank),
                                                                  0)
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
                                                     NVL (SUM (payment_other.amount), 0)
                                                        AS debet,
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
                                                     NVL (SUM (payment_other.amount), 0)
                                                        AS credit
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
                                           TO_NUMBER (TO_CHAR (daterelease, 'YYYY'))
                                              AS year,
                                           TO_NUMBER (TO_CHAR (daterelease, 'MM'))
                                              AS month,
                                           SUM (debet) AS debet,
                                           SUM (credit) AS credit
                                      FROM (  SELECT intercoloans.banktarget AS bankcode,
                                                     intercoloans.daterelease,
                                                     0 AS cashflowtype,
                                                     NVL (SUM (intercoloans.amount), 0)
                                                        AS debet,
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
                                                     NVL (SUM (intercoloans.sourceamount),
                                                          0)
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
                       (  SELECT company,
                                 bankcode,
                                 period_year,
                                 period_month,
                                 NVL (SUM (opening_balance), 0)
                                    AS opening_balance_monthly,
                                 currency
                            FROM bankbalance
                        GROUP BY company,
                                 bankcode,
                                 period_year,
                                 period_month,
                                 currency) tb_monthly
                          ON (    tb_monthly.bankcode = pv3.bankcode
                              AND tb_monthly.period_month = pv3.month
                              AND tb_monthly.period_year = pv3.year)
                 ) a
               LEFT JOIN payment_periodcontrol b
                  ON (    a.company = b.company
                      AND a.period_year = b.currentaccountingyear
                      AND a.period_month = b.currentaccountingperiod) where a.period_year = '$year' and a.period_month = '$monthONLY' and a.bankcode = '$BANKCODE'";
        $res = $this->db->query($q);

        return $res->row();
    }

}