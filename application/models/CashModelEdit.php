<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class CashModelEdit extends CI_Model {
    var $table = 'CASHFLOWSOURCE';
    var $tableDT = 'DOCTYPE';
    var $tableCom = 'COMPANY';
    var $tableBU = 'BUSINESSUNIT';
    var $tableDpt = 'DEPARTMENT';
    var $tableMtr = 'MATERIAL';

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    

    public function getSurplusedit($param){
        $COMPANY           = $param['COMPANY'];
        $COMPANYGROUP      = $param['COMPANYGROUP'];
        $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
        $MONTH             = $param['MONTH'];
        $YEAR              = $param['YEAR'];
        if($MONTH < 10){
            $MONTH2 = "0".$MONTH;
        }else{
            $MONTH2 = $MONTH;
        }

         $query = $this->db->query("SELECT PROP1,
                   PROP2,
                   PROP3,
                   PROP4,
                   PROP5,
                   (PROP1+PROP2+PROP3+PROP4+PROP5) totalProp,
                   ACTUAL1,
                   ACTUAL2,
                   ACTUAL3,
                   ACTUAL4,
                   ACTUAL5,
                   (ACTUAL1+ACTUAL2+ACTUAL3+ACTUAL4+ACTUAL5) totalAct,
                   ACTUAL1 - PROP1 AS VAR1,
                   ACTUAL2 - PROP2 AS VAR2,
                   ACTUAL3 - PROP3 AS VAR3,
                   ACTUAL4 - PROP4 AS VAR4,
                   ACTUAL5 - PROP5 AS VAR5,
                   ((ACTUAL1 - PROP1) + (ACTUAL2 - PROP2) + (ACTUAL3 - PROP3) + (ACTUAL4 - PROP4) + (ACTUAL5 - PROP5) ) totalVar
              FROM (SELECT SUM (
                              CASE WHEN CASHFLOWTYPE = 1 THEN PROPW1 * -1 ELSE PROPW1 END)
                              PROP1,
                           SUM (
                              CASE WHEN CASHFLOWTYPE = 1 THEN PROPW2 * -1 ELSE PROPW2 END)
                              PROP2,
                           SUM (
                              CASE WHEN CASHFLOWTYPE = 1 THEN PROPW3 * -1 ELSE PROPW3 END)
                              PROP3,
                           SUM (
                              CASE WHEN CASHFLOWTYPE = 1 THEN PROPW4 * -1 ELSE PROPW4 END)
                              PROP4,
                           SUM (
                              CASE WHEN CASHFLOWTYPE = 1 THEN PROPW5 * -1 ELSE PROPW5 END)
                              PROP5,
                           SUM (
                              CASE
                                 WHEN CASHFLOWTYPE = 1 THEN WACTUAL1 * -1
                                 ELSE WACTUAL1
                              END)
                              ACTUAL1,
                           SUM (
                              CASE
                                 WHEN CASHFLOWTYPE = 1 THEN WACTUAL2 * -1
                                 ELSE WACTUAL2
                              END)
                              ACTUAL2,
                           SUM (
                              CASE
                                 WHEN CASHFLOWTYPE = 1 THEN WACTUAL3 * -1
                                 ELSE WACTUAL3
                              END)
                              ACTUAL3,
                           SUM (
                              CASE
                                 WHEN CASHFLOWTYPE = 1 THEN WACTUAL4 * -1
                                 ELSE WACTUAL4
                              END)
                              ACTUAL4,
                           SUM (
                              CASE
                                 WHEN CASHFLOWTYPE = 1 THEN WACTUAL5 * -1
                                 ELSE WACTUAL5
                              END)
                              ACTUAL5
                      FROM ( SELECT CFTYPE, FINANCEGROUP,
                      FORECAST_CATEGORY,
                      CASHFLOWTYPE,
                      PROPW1,
                      PROPW2,
                      PROPW3,
                      PROPW4,
                      PROPW5,
                      WACTUAL1,
                      WACTUAL2,
                      WACTUAL3,
                      WACTUAL4,
                      WACTUAL5,
                      ROUND (WAVAR1 / 1000000, 2) WAVAR1,
                      ROUND (WAVAR2 / 1000000, 2) WAVAR2,
                      ROUND (WAVAR3 / 1000000, 2) WAVAR3,
                      ROUND (WAVAR4 / 1000000, 2) WAVAR4,
                      ROUND (WAVAR5 / 1000000, 2) WAVAR5
                                FROM (  SELECT PRD.cftype,
                                       STAGE1.financegroup,
                                       FCAT.FCNAME AS FORECAST_CATEGORY,
                                       FCAT.CASHFLOWTYPE,
                                       NVL (SUM (ROUND(PROPW1/1000000,2)), 0) PROPW1,
                                       NVL (SUM (ROUND(PROPW2/1000000,2)), 0) PROPW2,
                                       NVL (SUM (ROUND(PROPW3/1000000,2)), 0) PROPW3,
                                       NVL (SUM (ROUND(PROPW4/1000000,2)), 0) PROPW4,
                                       NVL (SUM (ROUND(PROPW5/1000000,2)), 0) PROPW5,
                                       NVL (SUM (ROUND(WACTUAL1/1000000,2)), 0) WACTUAL1,
                                       NVL (SUM (ROUND(WACTUAL2/1000000,2)), 0) WACTUAL2,
                                       NVL (SUM (ROUND(WACTUAL3/1000000,2)), 0) WACTUAL3,
                                       NVL (SUM (ROUND(WACTUAL4/1000000,2)), 0) WACTUAL4,
                                       NVL (SUM (ROUND(WACTUAL5/1000000,2)), 0) WACTUAL5,
                                       CASE cftype
                                          WHEN 'CASH OUT' THEN NVL (SUM (PROPW1) - SUM (WACTUAL1), 0)
                                          ELSE NVL (SUM (WACTUAL1) - SUM (PROPW1), 0)
                                       END
                                          AS WAVAR1,
                                       CASE cftype
                                          WHEN 'CASH OUT' THEN NVL (SUM (PROPW2) - SUM (WACTUAL2), 0)
                                          ELSE NVL (SUM (WACTUAL2) - SUM (PROPW2), 0)
                                       END
                                          AS WAVAR2,
                                       CASE cftype
                                          WHEN 'CASH OUT' THEN NVL (SUM (PROPW3) - SUM (WACTUAL3), 0)
                                          ELSE NVL (SUM (WACTUAL3) - SUM (PROPW3), 0)
                                       END
                                          AS WAVAR3,
                                       CASE cftype
                                          WHEN 'CASH OUT' THEN NVL (SUM (PROPW4) - SUM (WACTUAL4), 0)
                                          ELSE NVL (SUM (WACTUAL4) - SUM (PROPW4), 0)
                                       END
                                          AS WAVAR4,
                                       CASE cftype
                                          WHEN 'CASH OUT' THEN NVL (SUM (PROPW5) - SUM (WACTUAL5), 0)
                                          ELSE NVL (SUM (WACTUAL5) - SUM (PROPW5), 0)
                                       END
                                          AS WAVAR5
                                  FROM (    SELECT seq_financegroup,
                                                   financegroup,
                                                   seq_materialgroup,
                                                   materialgroup,
                                                   seq_forecast_category,
                                                   COMPANYGROUP,
                                                   LPAD ('', 5 * (LEVEL - 1), '') || TO_CHAR (forecast_category)
                                                      forecast_category
                                              FROM CFREPORT_FRAMEWORK CFT
                                             WHERE seq_financegroup <> -1 AND COMPANYGROUP = 'PLT'
                                        START WITH seq_materialgroup > 0
                                        CONNECT BY PRIOR seq_materialgroup = seq_financegroup
                                          GROUP BY seq_financegroup,
                                                   financegroup,
                                                   seq_materialgroup,
                                                   materialgroup,
                                                   seq_forecast_category,
                                                   COMPANYGROUP,
                                                      LPAD ('', 5 * (LEVEL - 1), '')
                                                   || TO_CHAR (forecast_category)
                                          ORDER BY seq_financegroup) STAGE1
                                       INNER JOIN (SELECT seq_forecast_category, forecast_category, cftype
                                                     FROM CFREPORT_FRAMEWORK_GROUP
                                                    WHERE forecast_category <> '-1') prd
                                          ON (    stage1.seq_forecast_category = prd.seq_forecast_category
                                              AND stage1.forecast_category = prd.forecast_category)
                                       INNER JOIN (SELECT FCCODE,FCNAME, CASHFLOWTYPE
                                                FROM FORECAST_CATEGORY
                                               WHERE FCCODE NOT IN
                                                        ('SURPLUS_DEFICIT',
                                                         'INTERCO_IN',
                                                         'INTERCO_OUT')) FCAT ON FCAT.FCCODE = prd.FORECAST_CATEGORY
                                       LEFT JOIN
                                       (  SELECT CASHFLOWTYPE,
                                                 FORECAST_CATEGORY,
                                                 GROUPS,
                                                 MATERIALGROUP,
                                                 SUM (PROPW1) PROPW1,
                                                 SUM (PROPW2) PROPW2,
                                                 SUM (PROPW3) PROPW3,
                                                 SUM (PROPW4) PROPW4,
                                                 SUM (PROPW5) PROPW5,
                                                 SUM (WACTUAL1) WACTUAL1,
                                                 SUM (WACTUAL2) WACTUAL2,
                                                 SUM (WACTUAL3) WACTUAL3,
                                                 SUM (WACTUAL4) WACTUAL4,
                                                 SUM (WACTUAL5) WACTUAL5
                                            FROM (SELECT CASE D.CASHFLOWTYPE
                                                            WHEN 1 THEN 'CASH OUT'
                                                            ELSE 'CASH IN'
                                                         END
                                                            AS CASHFLOWTYPE,
                                                         DC.FORECAST_CATEGORY,
                                                         DC.DEPARTMENT,
                                                         MG.FCNAME AS GROUPS,
                                                         MG.FCCODE MATERIALGROUP,
                                                         FF.MONTH,
                                                         FF.YEAR,
                                                         CASE WHEN FF.WEEK = 'W1' THEN AMOUNTADJS ELSE 0 END
                                                            AS PROPW1,
                                                         CASE WHEN FF.WEEK = 'W2' THEN AMOUNTADJS ELSE 0 END
                                                            AS PROPW2,
                                                         CASE WHEN FF.WEEK = 'W3' THEN AMOUNTADJS ELSE 0 END
                                                            AS PROPW3,
                                                         CASE WHEN FF.WEEK = 'W4' THEN AMOUNTADJS ELSE 0 END
                                                            AS PROPW4,
                                                         CASE WHEN FF.WEEK = 'W5' THEN AMOUNTADJS ELSE 0 END
                                                            AS PROPW5,
                                                         0 WACTUAL1,
                                                         0 WACTUAL2,
                                                         0 WACTUAL3,
                                                         0 WACTUAL4,
                                                         0 WACTUAL5
                                                    FROM (SELECT department,
                                                                    year,
                                                                    month,
                                                                    week,
                                                                    cftransid,
                                                                    amountadjs
                                                               FROM forecast_fix
                                                              WHERE     year = '$YEAR'
                                                                    AND month = '$MONTH2') ff
                                                            INNER JOIN (SELECT id,
                                                                               company,
                                                                               doctype,
                                                                               docref,
                                                                               extsys
                                                                          FROM cf_transaction
                                                                         WHERE company LIKE '%$COMPANY%') CF
                                                               ON CF.ID = FF.CFTRANSID
                                                            LEFT JOIN
                                                            (SELECT b.id,
                                                                    b.company,
                                                                    b.docnumber,
                                                                    a.material,
                                                                    b.businessunit,
                                                                    a.amount_include_vat,
                                                                    a.id AS aydi
                                                               FROM cf_transaction_det a
                                                                    INNER JOIN cf_transaction b ON (a.id = b.id) WHERE b.company LIKE '%$COMPANY%') docs
                                                               ON (    cf.company = docs.company
                                                                   AND cf.docref = docs.docnumber)
                                                            INNER JOIN
                                                            (SELECT material, materialgroup FROM material_groupitem) mgi
                                                               ON mgi.material = docs.material
                                                            INNER JOIN (SELECT id, fccode, fcname FROM material_group) mg
                                                               ON mg.id = mgi.materialgroup
                                                            INNER JOIN
                                                            (SELECT forecast_category,
                                                                    department,
                                                                    fctype,
                                                                    materialgroup
                                                               FROM department_category) dc
                                                               ON (    dc.department = ff.department
                                                                   AND mg.fccode = dc.materialgroup)
                                                            INNER JOIN
                                                            (SELECT id,
                                                                    fccode,
                                                                    fctype,
                                                                    companygroup,
                                                                    company_subgroup
                                                               FROM businessunit
                                                              WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                                    AND company_subgroup LIKE '%$COMPANYSUBGROUP%') bu
                                                               ON (bu.fctype = dc.fctype AND docs.businessunit = bu.id)
                                                            INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                                                            INNER JOIN
                                                     (SELECT id AS id_po, fccode AS MATERIALGROUP
                                                        FROM (  SELECT data1.id,
                                                                       data1.materialgroup,
                                                                       mg.fccode,
                                                                       SUM (data1.amount_include_vat)
                                                                          AS amount_include_vat,
                                                                       ROW_NUMBER ()
                                                                       OVER (
                                                                          PARTITION BY data1.id
                                                                          ORDER BY
                                                                             SUM (
                                                                                data1.amount_include_vat) ASC)
                                                                          AS rownumx
                                                                  FROM (SELECT a.id,
                                                                               CASE
                                                                                  WHEN b.doctype LIKE
                                                                                          '%SO%'
                                                                                  THEN
                                                                                     mgso.materialgroup
                                                                                  WHEN b.doctype LIKE
                                                                                          '%STO%'
                                                                                  THEN
                                                                                     mgsto.materialgroup_3_sto
                                                                                  ELSE
                                                                                     mgpo.materialgroup_2
                                                                               END
                                                                                  AS materialgroup,
                                                                               a.amount_include_vat
                                                                          FROM (SELECT id,
                                                                                       material,
                                                                                       amount_include_vat
                                                                                  FROM cf_transaction_Det)
                                                                               a
                                                                               INNER JOIN
                                                                               (SELECT id,
                                                                                       doctype,
                                                                                       extsys
                                                                                  FROM cf_transaction WHERE company LIKE '%$COMPANY%')
                                                                               b
                                                                                  ON (a.id = b.id)
                                                                               INNER JOIN
                                                                               (SELECT material,
                                                                                       materialgroup,
                                                                                       extsystem
                                                                                  FROM material_groupitem)
                                                                               mgso
                                                                                  ON (    a.material =
                                                                                             mgso.material
                                                                                      AND b.extsys =
                                                                                             mgso.extsystem)
                                                                               INNER JOIN
                                                                               (SELECT material_2,
                                                                                       materialgroup_2,
                                                                                       extsystem
                                                                                  FROM material_groupitem)
                                                                               mgpo
                                                                                  ON (    a.material =
                                                                                             mgpo.material_2
                                                                                      AND b.extsys =
                                                                                             mgpo.extsystem)
                                                                               INNER JOIN
                                                                               (SELECT material_3_sto,
                                                                                       materialgroup_3_sto,
                                                                                       extsystem
                                                                                  FROM material_groupitem)
                                                                               mgsto
                                                                                  ON (    a.material =
                                                                                             mgsto.material_3_sto
                                                                                      AND b.extsys =
                                                                                             mgsto.extsystem))
                                                                       data1
                                                                       INNER JOIN
                                                                       (SELECT id, fccode
                                                                          FROM material_group) mg
                                                                          ON (data1.materialgroup =
                                                                                 mg.id)
                                                              GROUP BY data1.id,
                                                                       data1.materialgroup,
                                                                       mg.fccode)
                                                       WHERE rownumx = 1) b
                                                        ON (    docs.id = b.id_po
                                                            AND MG.FCCODE = b.MATERIALGROUP)
                                                   WHERE     FF.YEAR = '$YEAR'
                                                         AND FF.MONTH = '$MONTH'
                                                         AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                                         AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                                         AND CF.COMPANY LIKE '%$COMPANY%'
                                                  UNION ALL
                                                  SELECT CASE D.CASHFLOWTYPE
                                                            WHEN 1 THEN 'CASH OUT'
                                                            ELSE 'CASH IN'
                                                         END
                                                            AS CASHFLOWTYPE,
                                                         DC.FORECAST_CATEGORY,
                                                         DC.DEPARTMENT,
                                                         MG.FCNAME AS GROUPS,
                                                         MG.FCCODE MATERIALGROUP,
                                                         TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
                                                         TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
                                                         0 PROPW1,
                                                         0 PROPW2,
                                                         0 PROPW3,
                                                         0 PROPW4,
                                                         0 PROPW5,
                                                         CASE
                                                            WHEN TO_CHAR (P.DATERELEASE, 'W') = 1
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL1,
                                                         CASE
                                                            WHEN TO_CHAR (P.DATERELEASE, 'W') = 2
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL2,
                                                         CASE
                                                            WHEN TO_CHAR (P.DATERELEASE, 'W') = 3
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL3,
                                                         CASE
                                                            WHEN TO_CHAR (P.DATERELEASE, 'W') = 4
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL4,
                                                         CASE
                                                            WHEN TO_CHAR (P.DATERELEASE, 'W') = 5
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL5
                                                    FROM (SELECT PAY.PAYMENTID,
                                                                 PAY.DATERELEASE,
                                                                 PAY.AMOUNT AMOUNTPAYMENT,
                                                                 PAY.CFTRANSID,
                                                                 SW.*
                                                            FROM PAYMENT PAY
                                                                 INNER JOIN SETTING_WEEK SW
                                                                    ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                                                               SW.DATEFROM
                                                                        AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                                                               SW.DATEUNTIL
                                                                        AND SW.MONTH =
                                                                               TO_CHAR (PAY.DATERELEASE, 'MM')
                                                                        AND SW.YEAR =
                                                                               TO_CHAR (PAY.DATERELEASE,
                                                                                        'YYYY'))) P
                                                                                        INNER JOIN (SELECT id,
                                                             company,
                                                             doctype,
                                                             docref,
                                                             docnumber,
                                                             extsys
                                                        FROM cf_transaction
                                                       WHERE company LIKE '%$COMPANY%') CF
                                             ON CF.ID = P.CFTRANSID
                                          LEFT JOIN
                                            (SELECT b.company,
                                                    b.docnumber,
                                                    a.material,
                                                    b.businessunit,
                                                    b.department
                                               FROM cf_transaction_det a
                                                    INNER JOIN
                                                    (SELECT id,
                                                            company,
                                                            businessunit,
                                                            department,
                                                            docnumber
                                                       FROM cf_transaction WHERE company LIKE '%$COMPANY%') b
                                                       ON (a.id = b.id)) docs
                                               ON (    cf.company = docs.company
                                                   AND cf.docref = docs.docnumber)
                                            INNER JOIN
                                            (SELECT material, materialgroup
                                               FROM material_groupitem) mgi
                                               ON mgi.material = docs.material
                                            INNER JOIN
                                            (SELECT id, fccode, fcname FROM material_group)
                                            mg
                                               ON mg.id = mgi.materialgroup
                                            INNER JOIN
                                            (SELECT forecast_category,
                                                    department,
                                                    fctype,
                                                    materialgroup
                                               FROM department_category) dc
                                               ON (    dc.department = docs.department
                                                   AND mg.fccode = dc.materialgroup)
                                            INNER JOIN
                                            (SELECT id,
                                                    fccode,
                                                    fctype,
                                                    companygroup,
                                                    company_subgroup
                                               FROM businessunit
                                              WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                    AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
                                            bu
                                               ON (    bu.fctype = dc.fctype
                                                   AND docs.businessunit = bu.id)
                                                         INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                                                   WHERE     TO_CHAR (P.DATERELEASE, 'MM') = '$MONTH2'
                                                         AND TO_CHAR (P.DATERELEASE, 'YYYY') = '$YEAR'
                                                         AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                                         AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                                         AND CF.COMPANY LIKE '%$COMPANY%'
                                                         AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
                                                  UNION ALL
                                                  SELECT DISTINCT CASE PO.CASHFLOWTYPE
                                                            WHEN 1 THEN 'CASH OUT'
                                                            ELSE 'CASH IN'
                                                         END
                                                            AS CASHFLOWTYPE,
                                                         DC.FORECAST_CATEGORY,
                                                         PO.DEPARTMENT,
                                                         MG.FCNAME GROUPS,
                                                         MG.FCCODE MATERIALGROUP,
                                                         TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'MM')) MONTH,
                                                         TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'YYYY')) YEAR,
                                                         0 PROPW1,
                                                         0 PROPW2,
                                                         0 PROPW3,
                                                         0 PROPW4,
                                                         0 PROPW5,
                                                         CASE
                                                            WHEN TO_CHAR (PO.DATERELEASE, 'W') = 1
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL1,
                                                         CASE
                                                            WHEN TO_CHAR (PO.DATERELEASE, 'W') = 2
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL2,
                                                         CASE
                                                            WHEN TO_CHAR (PO.DATERELEASE, 'W') = 3
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL3,
                                                         CASE
                                                            WHEN TO_CHAR (PO.DATERELEASE, 'W') = 4
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL4,
                                                         CASE
                                                            WHEN TO_CHAR (PO.DATERELEASE, 'W') = 5
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL5
                                                    FROM (SELECT PAY.PAYMENTID,
                                                                 PAY.DATERELEASE,
                                                                 'FINANCE' DEPARTMENT,
                                                                 PAY.AMOUNT AMOUNTPAYMENT,
                                                                 PAY.MATERIAL,
                                                                 PAY.COMPANY,
                                                                 PAY.CASHFLOWTYPE,
                                                                 SW.*
                                                            FROM PAYMENT_OTHER PAY
                                                                 INNER JOIN SETTING_WEEK SW
                                                                    ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                                                               SW.DATEFROM
                                                                        AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                                                               SW.DATEUNTIL
                                                                        AND SW.MONTH =
                                                                               TO_CHAR (PAY.DATERELEASE, 'MM')
                                                                        AND SW.YEAR =
                                                                               TO_CHAR (PAY.DATERELEASE,
                                                                                        'YYYY'))) PO
                                                                                        INNER JOIN
                                            (SELECT material, materialgroup
                                               FROM material_groupitem) mgi
                                               ON mgi.material = po.material
                                            INNER JOIN
                                            (SELECT id, fccode, fcname FROM material_group)
                                            mg
                                               ON mg.id = mgi.materialgroup
                                            INNER JOIN
                                            (SELECT forecast_category,
                                                    department,
                                                    fctype,
                                                    materialgroup
                                               FROM department_category) dc
                                               ON mg.fccode = dc.materialgroup
                                            INNER JOIN (SELECT id FROM company WHERE id LIKE '%$COMPANY%') c
                                               ON c.id = po.company
                                            INNER JOIN
                                            (SELECT id,
                                                    fccode,
                                                    fctype,
                                                    company,
                                                    companygroup,
                                                    company_subgroup
                                               FROM businessunit
                                              WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                    AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
                                            bu
                                               ON bu.company = po.company
                                                   WHERE     TO_CHAR (PO.DATERELEASE, 'MM') = '$MONTH2'
                                                         AND TO_CHAR (PO.DATERELEASE, 'YYYY') = '$YEAR'
                                                         AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                                         AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                                         AND C.ID LIKE '%$COMPANY%'
                                                  UNION ALL
                                                  SELECT 'CASH OUT' CASHFLOWTYPE,
                                                         'INTERCO_OUT' FORECAST_CATEGORY,
                                                         INTERCO.DEPARTMENT,
                                                         'INTERCO OUT' GROUPS,
                                                         'INTOU01' MATERIALGROUP,
                                                         TO_NUMBER(TO_CHAR (INTERCO.DATERELEASE, 'MM')) MONTH,
                                                         TO_NUMBER(TO_CHAR (INTERCO.DATERELEASE, 'YYYY')) YEAR,
                                                         0 PROPW1,
                                                         0 PROPW2,
                                                         0 PROPW3,
                                                         0 PROPW4,
                                                         0 PROPW5,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 1
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL1,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 2
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL2,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 3
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL3,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 4
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL4,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 5
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL5
                                                    FROM (SELECT INTERCO.DATERELEASE,
                                                                 INTERCO.AMOUNT AMOUNTPAYMENT,
                                                                 INTERCO.COMPANYTARGET,
                                                                 'FINANCE' DEPARTMENT,
                                                                 SW.*
                                                            FROM INTERCOLOANS INTERCO
                                                                 INNER JOIN SETTING_WEEK SW
                                                                    ON (    TO_CHAR (INTERCO.DATERELEASE, 'DD') >=
                                                                               SW.DATEFROM
                                                                        AND TO_CHAR (INTERCO.DATERELEASE, 'DD') <=
                                                                               SW.DATEUNTIL
                                                                        AND SW.MONTH =
                                                                               TO_CHAR (INTERCO.DATERELEASE,
                                                                                        'MM')
                                                                        AND SW.YEAR =
                                                                               TO_CHAR (INTERCO.DATERELEASE,
                                                                                        'YYYY'))) INTERCO
                                                         INNER JOIN (select id from COMPANY WHERE id LIKE '%$COMPANY%' ) C ON C.ID = INTERCO.COMPANYTARGET
                                                         INNER JOIN BUSINESSUNIT BU
                                                            ON BU.COMPANY = INTERCO.COMPANYTARGET
                                                   WHERE     TO_CHAR (INTERCO.DATERELEASE, 'MM') = '$MONTH2'
                                                         AND TO_CHAR (INTERCO.DATERELEASE, 'YYYY') = '$YEAR'
                                                         AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                                         AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                                         AND C.ID LIKE '%$COMPANY%'
                                                  UNION ALL
                                                  SELECT 'CASH IN' CASHFLOWTYPE,
                                                         'INTERCO_IN' FORECAST_CATEGORY,
                                                         INTERCOS.DEPARTMENT,
                                                         'INTERCO IN' GROUPS,
                                                         'INTIN01' MATERIALGROUP,
                                                         TO_NUMBER(TO_CHAR (INTERCOS.DATERELEASE, 'MM')) MONTH,
                                                         TO_NUMBER(TO_CHAR (INTERCOS.DATERELEASE, 'YYYY')) YEAR,
                                                         0 PROPW1,
                                                         0 PROPW2,
                                                         0 PROPW3,
                                                         0 PROPW4,
                                                         0 PROPW5,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 1
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL1,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 2
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL2,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 3
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL3,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 4
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL4,
                                                         CASE
                                                            WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 5
                                                            THEN
                                                               AMOUNTPAYMENT
                                                            ELSE
                                                               0
                                                         END
                                                            AS WACTUAL5
                                                    FROM (SELECT INTERCOS.DATERELEASE,
                                                                 INTERCOS.AMOUNT AMOUNTPAYMENT,
                                                                 INTERCOS.COMPANYSOURCE,
                                                                 'FINANCE' DEPARTMENT,
                                                                 SW.*
                                                            FROM INTERCOLOANS INTERCOS
                                                                 INNER JOIN SETTING_WEEK SW
                                                                    ON (    TO_CHAR (INTERCOS.DATERELEASE,
                                                                                     'DD') >= SW.DATEFROM
                                                                        AND TO_CHAR (INTERCOS.DATERELEASE,
                                                                                     'DD') <= SW.DATEUNTIL
                                                                        AND SW.MONTH =
                                                                               TO_CHAR (INTERCOS.DATERELEASE,
                                                                                        'MM')
                                                                        AND SW.YEAR =
                                                                               TO_CHAR (INTERCOS.DATERELEASE,
                                                                                        'YYYY'))) INTERCOS
                                                         INNER JOIN (select id from COMPANY WHERE id LIKE '%$COMPANY%' ) C ON C.ID = INTERCOS.COMPANYSOURCE
                                                         INNER JOIN BUSINESSUNIT BU
                                                            ON BU.COMPANY = INTERCOS.COMPANYSOURCE
                                                   WHERE     TO_CHAR (INTERCOS.DATERELEASE, 'MM') = '$MONTH2'
                                                         AND TO_CHAR (INTERCOS.DATERELEASE, 'YYYY') = '$YEAR'
                                                         AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                                         AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                                         AND C.ID LIKE '%$COMPANY%')
                                        GROUP BY CASHFLOWTYPE,
                                                 FORECAST_CATEGORY,
                                                 GROUPS,
                                                 MATERIALGROUP) result1
                                          ON (    stage1.materialgroup = result1.materialgroup
                                              AND stage1.FORECAST_CATEGORY = result1.FORECAST_CATEGORY)
                              GROUP BY PRD.cftype,
                                       STAGE1.seq_financegroup,
                                       STAGE1.financegroup,
                                       FCAT.FCNAME,
                                       FCAT.CASHFLOWTYPE
                              ORDER BY CFTYPE ) )
                     WHERE FINANCEGROUP NOT IN ('OUT', 'IN'))");
         // var_dump($this->db->last_query());exit();
         return $query->row();
    }

    public function getTotalIntercoedit($param){
         // var_dump($param);exit;
         $COMPANY           =  $param['COMPANY'];
         $COMPANYGROUP      = $param['COMPANYGROUP'];
         $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
         $MONTH             = $param['MONTH'];
         $YEAR              = $param['YEAR'];
         if($MONTH < 10){
            $MONTH2 = "0".$MONTH;
         }else{
            $MONTH2 = $MONTH;
         }

         $q = "SELECT SUM(PROPW1) PROP1,
             SUM(PROPW2) PROP2,
             SUM(PROPW3) PROP3,
             SUM(PROPW4) PROP4,
             SUM(PROPW5) PROP5,
             SUM (WACTUAL1) ACTUAL1,
             SUM (WACTUAL2) ACTUAL2,
             SUM (WACTUAL3) ACTUAL3,
             SUM (WACTUAL4) ACTUAL4,
             SUM (WACTUAL5) ACTUAL5,
             SUM(WAVAR1) VAR1,
             SUM(WAVAR2) VAR2,
             SUM(WAVAR3) VAR3,
             SUM(WAVAR4) VAR4,
             SUM(WAVAR5) VAR5
                  FROM (SELECT CFTYPE,
                     FINANCEGROUP,
                     FORECAST_CATEGORY,
                     PROPW1,
                     PROPW2,
                     PROPW3,
                     PROPW4,
                     PROPW5,
                     WACTUAL1,
                     WACTUAL2,
                     WACTUAL3,
                     WACTUAL4,
                     WACTUAL5,
                     ROUND (WAVAR1 / 1000000, 2) WAVAR1,
                     ROUND (WAVAR2 / 1000000, 2) WAVAR2,
                     ROUND (WAVAR3 / 1000000, 2) WAVAR3,
                     ROUND (WAVAR4 / 1000000, 2) WAVAR4,
                     ROUND (WAVAR5 / 1000000, 2) WAVAR5
                FROM (  SELECT PRD.cftype,
                               STAGE1.financegroup,
                               FCAT.FCNAME AS FORECAST_CATEGORY,
                               NVL (SUM (ROUND (PROPW1 / 1000000, 2)), 0) PROPW1,
                               NVL (SUM (ROUND (PROPW2 / 1000000, 2)), 0) PROPW2,
                               NVL (SUM (ROUND (PROPW3 / 1000000, 2)), 0) PROPW3,
                               NVL (SUM (ROUND (PROPW4 / 1000000, 2)), 0) PROPW4,
                               NVL (SUM (ROUND (PROPW5 / 1000000, 2)), 0) PROPW5,
                               NVL (SUM (ROUND (WACTUAL1 / 1000000, 2)), 0) WACTUAL1,
                               NVL (SUM (ROUND (WACTUAL2 / 1000000, 2)), 0) WACTUAL2,
                               NVL (SUM (ROUND (WACTUAL3 / 1000000, 2)), 0) WACTUAL3,
                               NVL (SUM (ROUND (WACTUAL4 / 1000000, 2)), 0) WACTUAL4,
                               NVL (SUM (ROUND (WACTUAL5 / 1000000, 2)), 0) WACTUAL5,
                               CASE cftype
                                  WHEN 'CASH OUT'
                                  THEN
                                     NVL (SUM (PROPW1) - SUM (WACTUAL1), 0)
                                  ELSE
                                     NVL (SUM (WACTUAL1) - SUM (PROPW1), 0)
                               END
                                  AS WAVAR1,
                               CASE cftype
                                  WHEN 'CASH OUT'
                                  THEN
                                     NVL (SUM (PROPW2) - SUM (WACTUAL2), 0)
                                  ELSE
                                     NVL (SUM (WACTUAL2) - SUM (PROPW2), 0)
                               END
                                  AS WAVAR2,
                               CASE cftype
                                  WHEN 'CASH OUT'
                                  THEN
                                     NVL (SUM (PROPW3) - SUM (WACTUAL3), 0)
                                  ELSE
                                     NVL (SUM (WACTUAL3) - SUM (PROPW3), 0)
                               END
                                  AS WAVAR3,
                               CASE cftype
                                  WHEN 'CASH OUT'
                                  THEN
                                     NVL (SUM (PROPW4) - SUM (WACTUAL4), 0)
                                  ELSE
                                     NVL (SUM (WACTUAL4) - SUM (PROPW4), 0)
                               END
                                  AS WAVAR4,
                               CASE cftype
                                  WHEN 'CASH OUT'
                                  THEN
                                     NVL (SUM (PROPW5) - SUM (WACTUAL5), 0)
                                  ELSE
                                     NVL (SUM (WACTUAL5) - SUM (PROPW5), 0)
                               END
                                  AS WAVAR5
                          FROM (    SELECT seq_financegroup,
                                           financegroup,
                                           seq_materialgroup,
                                           materialgroup,
                                           seq_forecast_category,
                                           COMPANYGROUP,
                                              LPAD ('', 5 * (LEVEL - 1), '')
                                           || TO_CHAR (forecast_category)
                                              forecast_category
                                      FROM CFREPORT_FRAMEWORK CFT
                                     WHERE     seq_financegroup <> -1
                                           AND COMPANYGROUP = 'PLT'
                                           AND FORECAST_CATEGORY = 'INTERCO'
                                START WITH seq_materialgroup > 0
                                CONNECT BY PRIOR seq_materialgroup = seq_financegroup
                                  GROUP BY seq_financegroup,
                                           financegroup,
                                           seq_materialgroup,
                                           materialgroup,
                                           seq_forecast_category,
                                           COMPANYGROUP,
                                              LPAD ('', 5 * (LEVEL - 1), '')
                                           || TO_CHAR (forecast_category)
                                  ORDER BY seq_financegroup) STAGE1
                               INNER JOIN
                               (SELECT seq_forecast_category,
                                       forecast_category,
                                       cftype
                                  FROM CFREPORT_FRAMEWORK_GROUP
                                 WHERE     forecast_category <> '-1'
                                       AND FORECAST_CATEGORY = 'INTERCO') prd
                                  ON (    stage1.seq_forecast_category =
                                             prd.seq_forecast_category
                                      AND stage1.forecast_category =
                                             prd.forecast_category)
                               INNER JOIN
                               (SELECT FCCODE, FCNAME FROM FORECAST_CATEGORY) FCAT
                                  ON FCAT.FCCODE = prd.FORECAST_CATEGORY
                               LEFT JOIN
                               (  SELECT CASHFLOWTYPE,
                                         FORECAST_CATEGORY,
                                         GROUPS,
                                         MATERIALGROUP,
                                         SUM (PROPW1) PROPW1,
                                         SUM (PROPW2) PROPW2,
                                         SUM (PROPW3) PROPW3,
                                         SUM (PROPW4) PROPW4,
                                         SUM (PROPW5) PROPW5,
                                         SUM (WACTUAL1) WACTUAL1,
                                         SUM (WACTUAL2) WACTUAL2,
                                         SUM (WACTUAL3) WACTUAL3,
                                         SUM (WACTUAL4) WACTUAL4,
                                         SUM (WACTUAL5) WACTUAL5
                                    FROM ( SELECT DISTINCT 'CASH OUT' CASHFLOWTYPE,
                                               'INTERCO' FORECAST_CATEGORY,
                                               INTERCO.DEPARTMENT,                 
                                               'INTERCO OUT' GROUPS,
                                               'INTOU01' MATERIALGROUP,
                                               TO_NUMBER (TO_CHAR (INTERCO.DATERELEASE, 'MM')) MONTH,
                                               TO_NUMBER (TO_CHAR (INTERCO.DATERELEASE, 'YYYY')) YEAR,
                                               0 PROPW1,
                                               0 PROPW2,
                                               0 PROPW3,
                                               0 PROPW4,
                                               0 PROPW5,
                                               CASE
                                                  WHEN to_number(substr(week,2,1)) = 1
                                                  THEN
                                                     AMOUNTPAYMENT * -1
                                                  ELSE
                                                     0
                                               END
                                                  AS WACTUAL1,
                                               CASE
                                                  WHEN to_number(substr(week,2,1)) = 2
                                                  THEN
                                                     AMOUNTPAYMENT * -1
                                                  ELSE
                                                     0
                                               END
                                                  AS WACTUAL2,
                                               CASE
                                                  WHEN to_number(substr(week,2,1)) = 3
                                                  THEN
                                                     AMOUNTPAYMENT * -1
                                                  ELSE
                                                     0
                                               END
                                                  AS WACTUAL3,
                                               CASE
                                                  WHEN to_number(substr(week,2,1)) = 4
                                                  THEN
                                                     AMOUNTPAYMENT * -1
                                                  ELSE
                                                     0
                                               END
                                                  AS WACTUAL4,
                                               CASE
                                                  WHEN to_number(substr(week,2,1)) = 5
                                                  THEN
                                                     AMOUNTPAYMENT * -1
                                                  ELSE
                                                     0
                                               END
                                                  AS WACTUAL5
                                          FROM (SELECT INTERCO.DATERELEASE,
                                                       INTERCO.amount * INTERCO.rate AS amountpayment,
                                                       INTERCO.COMPANYSOURCE,
                                                       'FINANCE' DEPARTMENT,
                                                       SW.*
                                                  FROM (SELECT INTERCO.banksource,
                                                             INTERCO.daterelease,
                                                             INTERCO.sourceamount as amount,
                                                             INTERCO.RATE,
                                                             INTERCO.companysource
                                                        FROM INTERCOLOANS INTERCO
                                                       WHERE     TO_CHAR (daterelease, 'MM') = '$MONTH2'
                                                             AND TO_CHAR (daterelease, 'YYYY') = '$YEAR') INTERCO
                                                       INNER JOIN SETTING_WEEK SW
                                                          ON (    TO_CHAR (INTERCO.DATERELEASE, 'DD') >=
                                                                     SW.DATEFROM
                                                              AND TO_CHAR (INTERCO.DATERELEASE, 'DD') <=
                                                                     SW.DATEUNTIL
                                                              AND SW.MONTH =
                                                                     TO_CHAR (INTERCO.DATERELEASE, 'MM')
                                                              AND SW.YEAR =
                                                                     TO_CHAR (INTERCO.DATERELEASE, 'YYYY'))
                                               )
                                               INTERCO
                                               INNER JOIN COMPANY C ON C.ID = INTERCO.COMPANYSOURCE
                                               INNER JOIN BUSINESSUNIT BU
                                                  ON BU.COMPANY = INTERCO.COMPANYSOURCE
                                         WHERE     TO_CHAR (INTERCO.DATERELEASE, 'MM') = '$MONTH2'
                                               AND TO_CHAR (INTERCO.DATERELEASE, 'YYYY') = '$YEAR'
                                               AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                               AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                               AND C.ID LIKE '%$COMPANY%' 
                                          UNION ALL
                                          SELECT DISTINCT 'CASH IN' CASHFLOWTYPE,
                                           'INTERCO' FORECAST_CATEGORY,
                                           INTERCOS.DEPARTMENT,
                                           'INTERCO IN' GROUPS,
                                           'INTIN01' MATERIALGROUP,
                                           TO_NUMBER (
                                              TO_CHAR (INTERCOS.DATERELEASE,
                                                       'MM'))
                                              MONTH,
                                           TO_NUMBER (
                                              TO_CHAR (INTERCOS.DATERELEASE,
                                                       'YYYY'))
                                              YEAR,
                                           0 PROPW1,
                                           0 PROPW2,
                                           0 PROPW3,
                                           0 PROPW4,
                                           0 PROPW5,
                                           CASE
                                              WHEN to_number(substr(week,2,1)) = 1
                                              THEN
                                                 AMOUNTPAYMENT
                                              ELSE
                                                 0
                                           END
                                              AS WACTUAL1,
                                           CASE
                                              WHEN to_number(substr(week,2,1)) = 2
                                              THEN
                                                 AMOUNTPAYMENT
                                              ELSE
                                                 0
                                           END
                                              AS WACTUAL2,
                                           CASE
                                              WHEN to_number(substr(week,2,1)) = 3
                                              THEN
                                                 AMOUNTPAYMENT
                                              ELSE
                                                 0
                                           END
                                              AS WACTUAL3,
                                           CASE
                                              WHEN to_number(substr(week,2,1)) = 4
                                              THEN
                                                 AMOUNTPAYMENT
                                              ELSE
                                                 0
                                           END
                                              AS WACTUAL4,
                                           CASE
                                              WHEN to_number(substr(week,2,1)) = 5
                                              THEN
                                                 AMOUNTPAYMENT
                                              ELSE
                                                 0
                                           END
                                              AS WACTUAL5
                                      FROM (SELECT INTERCOS.DATERELEASE,
                                                   INTERCOS.amount AS amountpayment,
                                                   INTERCOS.COMPANYTARGET,
                                                   'FINANCE' DEPARTMENT,
                                                   SW.*
                                              FROM (SELECT INTERCOS.banktarget,
                                                 INTERCOS.daterelease,
                                                 INTERCOS.amount as amount,
                                                 INTERCOS.companytarget
                                            FROM INTERCOLOANS INTERCOS
                                           WHERE     TO_CHAR (daterelease, 'MM') = '$MONTH2'
                                                 AND TO_CHAR (daterelease, 'YYYY') = '$YEAR') INTERCOS
                                                   INNER JOIN SETTING_WEEK SW
                                                      ON (    TO_CHAR (
                                                                 INTERCOS.DATERELEASE,
                                                                 'DD') >=
                                                                 SW.DATEFROM
                                                          AND TO_CHAR (
                                                                 INTERCOS.DATERELEASE,
                                                                 'DD') <=
                                                                 SW.DATEUNTIL
                                                          AND SW.MONTH =
                                                                 TO_CHAR (
                                                                    INTERCOS.DATERELEASE,
                                                                    'MM')
                                                          AND SW.YEAR =
                                                                 TO_CHAR (
                                                                    INTERCOS.DATERELEASE,
                                                                    'YYYY'))
                                                                    )
                                           INTERCOS
                                           INNER JOIN COMPANY C
                                              ON C.ID = INTERCOS.COMPANYTARGET
                                           INNER JOIN BUSINESSUNIT BU
                                              ON BU.COMPANY =
                                                    INTERCOS.COMPANYTARGET
                                     WHERE     TO_CHAR (INTERCOS.DATERELEASE, 'MM') = '$MONTH2'
                                          AND TO_CHAR (INTERCOS.DATERELEASE, 'YYYY') = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND C.ID LIKE '%$COMPANY%')
                                GROUP BY CASHFLOWTYPE,
                                         FORECAST_CATEGORY,
                                         GROUPS,
                                         MATERIALGROUP) result1
                                  ON (    stage1.materialgroup =
                                             result1.materialgroup
                                      AND stage1.FORECAST_CATEGORY =
                                             result1.FORECAST_CATEGORY)
                      GROUP BY PRD.cftype,
                               STAGE1.seq_financegroup,
                               STAGE1.financegroup,
                               FCAT.FCNAME
                      ORDER BY CFTYPE))";
         $res = $this->db->query($q)->row();
         // var_dump($this->db->last_query());
         return $res;
    }

    public function getInOutIntercoedit($param){
         $COMPANY           = $param['COMPANY'];
         $COMPANYGROUP      = $param['COMPANYGROUP'];
         $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
         $MONTH             = $param['MONTH'];
         $YEAR              = $param['YEAR'];

         if($MONTH < 10){
            $MONTH2 = "0".$MONTH;
         }else{
            $MONTH2 = $MONTH;
         }
         $q = "SELECT PROP1,
                PROP2,
                PROP3,
                PROP4,
                PROP5,
                ACTUAL1,
                ACTUAL2,
                ACTUAL3,
                ACTUAL4,
                ACTUAL5,
                ROUND (WAVAR1 / 1000000, 2) VAR1,
                ROUND (WAVAR2 / 1000000, 2) VAR2,
                ROUND (WAVAR3 / 1000000, 2) VAR3,
                ROUND (WAVAR4 / 1000000, 2) VAR4,
                ROUND (WAVAR5 / 1000000, 2) VAR5
           FROM ( SELECT 
                 NVL (SUM (ROUND (PROPW1 / 1000000, 2)), 0) PROP1,
                 NVL (SUM (ROUND (PROPW2 / 1000000, 2)), 0) PROP2,
                 NVL (SUM (ROUND (PROPW3 / 1000000, 2)), 0) PROP3,
                 NVL (SUM (ROUND (PROPW4 / 1000000, 2)), 0) PROP4,
                 NVL (SUM (ROUND (PROPW5 / 1000000, 2)), 0) PROP5,
                 NVL (SUM (ROUND (WACTUAL1 / 1000000, 2)), 0) ACTUAL1,
                 NVL (SUM (ROUND (WACTUAL2 / 1000000, 2)), 0) ACTUAL2,
                 NVL (SUM (ROUND (WACTUAL3 / 1000000, 2)), 0) ACTUAL3,
                 NVL (SUM (ROUND (WACTUAL4 / 1000000, 2)), 0) ACTUAL4,
                 NVL (SUM (ROUND (WACTUAL5 / 1000000, 2)), 0) ACTUAL5,
                 NVL (SUM (WACTUAL1) - SUM (PROPW1), 0) WAVAR1,
                 NVL (SUM (WACTUAL2) - SUM (PROPW2), 0) WAVAR2,
                 NVL (SUM (WACTUAL3) - SUM (PROPW3), 0) WAVAR3,
                 NVL (SUM (WACTUAL4) - SUM (PROPW4), 0) WAVAR4,
                 NVL (SUM (WACTUAL5) - SUM (PROPW5), 0) WAVAR5
            FROM (
                  SELECT DISTINCT 'CASH IN' CASHFLOWTYPE,
                         'INTERCO' FORECAST_CATEGORY,
                         INTERCOS.DEPARTMENT,
                         'INTERCO IN' GROUPS,
                         'INTIN01' MATERIALGROUP,
                         TO_NUMBER (
                            TO_CHAR (INTERCOS.DATERELEASE,
                                     'MM'))
                            MONTH,
                         TO_NUMBER (
                            TO_CHAR (INTERCOS.DATERELEASE,
                                     'YYYY'))
                            YEAR,
                         0 PROPW1,
                         0 PROPW2,
                         0 PROPW3,
                         0 PROPW4,
                         0 PROPW5,
                         CASE
                            WHEN to_number(substr(week,2,1)) = 1
                            THEN
                               AMOUNTPAYMENT
                            ELSE
                               0
                         END
                            AS WACTUAL1,
                         CASE
                            WHEN to_number(substr(week,2,1)) = 2
                            THEN
                               AMOUNTPAYMENT
                            ELSE
                               0
                         END
                            AS WACTUAL2,
                         CASE
                            WHEN to_number(substr(week,2,1)) = 3
                            THEN
                               AMOUNTPAYMENT
                            ELSE
                               0
                         END
                            AS WACTUAL3,
                         CASE
                            WHEN to_number(substr(week,2,1)) = 4
                            THEN
                               AMOUNTPAYMENT
                            ELSE
                               0
                         END
                            AS WACTUAL4,
                         CASE
                            WHEN to_number(substr(week,2,1)) = 5
                            THEN
                               AMOUNTPAYMENT
                            ELSE
                               0
                         END
                            AS WACTUAL5
                    FROM (SELECT INTERCOS.DATERELEASE,
                                 INTERCOS.amount AS amountpayment,
                                 INTERCOS.COMPANYTARGET,
                                 'FINANCE' DEPARTMENT,
                                 SW.*
                            FROM (SELECT INTERCOS.banktarget,
                               INTERCOS.daterelease,
                               INTERCOS.amount as amount,
                               INTERCOS.companytarget
                          FROM INTERCOLOANS INTERCOS
                         WHERE     TO_CHAR (daterelease, 'MM') = '$MONTH2'
                               AND TO_CHAR (daterelease, 'YYYY') = '$YEAR') INTERCOS
                                 INNER JOIN SETTING_WEEK SW
                                    ON (    TO_CHAR (
                                               INTERCOS.DATERELEASE,
                                               'DD') >=
                                               SW.DATEFROM
                                        AND TO_CHAR (
                                               INTERCOS.DATERELEASE,
                                               'DD') <=
                                               SW.DATEUNTIL
                                        AND SW.MONTH =
                                               TO_CHAR (
                                                  INTERCOS.DATERELEASE,
                                                  'MM')
                                        AND SW.YEAR =
                                               TO_CHAR (
                                                  INTERCOS.DATERELEASE,
                                                  'YYYY'))
                                                  )
                         INTERCOS
                         INNER JOIN COMPANY C
                            ON C.ID = INTERCOS.COMPANYTARGET
                         INNER JOIN BUSINESSUNIT BU
                            ON BU.COMPANY =
                                  INTERCOS.COMPANYTARGET
                   WHERE     TO_CHAR (INTERCOS.DATERELEASE, 'MM') = '$MONTH2'
                        AND TO_CHAR (INTERCOS.DATERELEASE, 'YYYY') = '$YEAR'
                        AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                        AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                        AND C.ID LIKE '%$COMPANY%')
              GROUP BY CASHFLOWTYPE,
                       FORECAST_CATEGORY,
                       GROUPS,
                       MATERIALGROUP ORDER BY CASHFLOWTYPE ASC )";
         //out
         $q2 = "SELECT PROP1,
                PROP2,
                PROP3,
                PROP4,
                PROP5,
                ACTUAL1,
                ACTUAL2,
                ACTUAL3,
                ACTUAL4,
                ACTUAL5,
                ROUND (WAVAR1 / 1000000, 2) VAR1,
                ROUND (WAVAR2 / 1000000, 2) VAR2,
                ROUND (WAVAR3 / 1000000, 2) VAR3,
                ROUND (WAVAR4 / 1000000, 2) VAR4,
                ROUND (WAVAR5 / 1000000, 2) VAR5
           FROM ( SELECT 
                 NVL (SUM (ROUND (PROPW1 / 1000000, 2)), 0) PROP1,
                 NVL (SUM (ROUND (PROPW2 / 1000000, 2)), 0) PROP2,
                 NVL (SUM (ROUND (PROPW3 / 1000000, 2)), 0) PROP3,
                 NVL (SUM (ROUND (PROPW4 / 1000000, 2)), 0) PROP4,
                 NVL (SUM (ROUND (PROPW5 / 1000000, 2)), 0) PROP5,
                 NVL (SUM (ROUND (WACTUAL1 / 1000000, 2)), 0) ACTUAL1,
                 NVL (SUM (ROUND (WACTUAL2 / 1000000, 2)), 0) ACTUAL2,
                 NVL (SUM (ROUND (WACTUAL3 / 1000000, 2)), 0) ACTUAL3,
                 NVL (SUM (ROUND (WACTUAL4 / 1000000, 2)), 0) ACTUAL4,
                 NVL (SUM (ROUND (WACTUAL5 / 1000000, 2)), 0) ACTUAL5,
                 NVL (SUM (PROPW1) - SUM (WACTUAL1), 0) WAVAR1,
                 NVL (SUM (PROPW2) - SUM (WACTUAL2), 0) WAVAR2,
                 NVL (SUM (PROPW3) - SUM (WACTUAL3), 0) WAVAR3,
                 NVL (SUM (PROPW4) - SUM (WACTUAL4), 0) WAVAR4,
                 NVL (SUM (PROPW5) - SUM (WACTUAL5), 0) WAVAR5
            FROM ( SELECT DISTINCT 'CASH OUT' CASHFLOWTYPE,
                 'INTERCO' FORECAST_CATEGORY,
                 INTERCO.DEPARTMENT,                 
                 'INTERCO OUT' GROUPS,
                 'INTOU01' MATERIALGROUP,
                 TO_NUMBER (TO_CHAR (INTERCO.DATERELEASE, 'MM')) MONTH,
                 TO_NUMBER (TO_CHAR (INTERCO.DATERELEASE, 'YYYY')) YEAR,
                 0 PROPW1,
                 0 PROPW2,
                 0 PROPW3,
                 0 PROPW4,
                 0 PROPW5,
                 CASE
                    WHEN to_number(substr(week,2,1)) = 1
                    THEN
                       AMOUNTPAYMENT * -1
                    ELSE
                       0
                 END
                    AS WACTUAL1,
                 CASE
                    WHEN to_number(substr(week,2,1)) = 2
                    THEN
                       AMOUNTPAYMENT * -1
                    ELSE
                       0
                 END
                    AS WACTUAL2,
                 CASE
                    WHEN to_number(substr(week,2,1)) = 3
                    THEN
                       AMOUNTPAYMENT * -1
                    ELSE
                       0
                 END
                    AS WACTUAL3,
                 CASE
                    WHEN to_number(substr(week,2,1)) = 4
                    THEN
                       AMOUNTPAYMENT * -1
                    ELSE
                       0
                 END
                    AS WACTUAL4,
                 CASE
                    WHEN to_number(substr(week,2,1)) = 5
                    THEN
                       AMOUNTPAYMENT * -1
                    ELSE
                       0
                 END
                    AS WACTUAL5
            FROM (SELECT INTERCO.DATERELEASE,
                         INTERCO.amount * INTERCO.rate AS amountpayment,
                         INTERCO.COMPANYSOURCE,
                         'FINANCE' DEPARTMENT,
                         SW.*
                    FROM (SELECT INTERCO.banksource,
                               INTERCO.daterelease,
                               INTERCO.sourceamount as amount,
                               INTERCO.RATE,
                               INTERCO.companysource
                          FROM INTERCOLOANS INTERCO
                         WHERE     TO_CHAR (daterelease, 'MM') = '$MONTH2'
                               AND TO_CHAR (daterelease, 'YYYY') = '$YEAR') INTERCO
                         INNER JOIN SETTING_WEEK SW
                            ON (    TO_CHAR (INTERCO.DATERELEASE, 'DD') >=
                                       SW.DATEFROM
                                AND TO_CHAR (INTERCO.DATERELEASE, 'DD') <=
                                       SW.DATEUNTIL
                                AND SW.MONTH =
                                       TO_CHAR (INTERCO.DATERELEASE, 'MM')
                                AND SW.YEAR =
                                       TO_CHAR (INTERCO.DATERELEASE, 'YYYY'))
                 )
                 INTERCO
                 INNER JOIN COMPANY C ON C.ID = INTERCO.COMPANYSOURCE
                 INNER JOIN BUSINESSUNIT BU
                    ON BU.COMPANY = INTERCO.COMPANYSOURCE
           WHERE     TO_CHAR (INTERCO.DATERELEASE, 'MM') = '$MONTH2'
                 AND TO_CHAR (INTERCO.DATERELEASE, 'YYYY') = '$YEAR'
                 AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                 AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                 AND C.ID LIKE '%$COMPANY%' ) GROUP BY CASHFLOWTYPE,
                       FORECAST_CATEGORY,
                       GROUPS,
                       MATERIALGROUP ORDER BY CASHFLOWTYPE ASC )";
         $res[0] = $this->db->query($q)->row();
         $res[1] = $this->db->query($q2)->row();
         
         return $res;

    }

    // public function ShowData($param){
    //     $COMPANY           =  $param['COMPANY'];
    //     $COMPANYGROUP      = $param['COMPANYGROUP'];
    //     $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
    //     $MONTH             = $param['MONTH'];
    //     $YEAR              = $param['YEAR'];
    //     if($MONTH < 10){
    //         $MONTH2 = "0".$MONTH;
    //     }else{
    //         $MONTH2 = $MONTH;
    //     }
    //   $query = $this->db->query(" SELECT CFTYPE, FINANCEGROUP,
    //    FORECAST_CATEGORY,
    //    PROPW1,
    //    PROPW2,
    //    PROPW3,
    //    PROPW4,
    //    PROPW5,
    //    WACTUAL1,
    //    WACTUAL2,
    //    WACTUAL3,
    //    WACTUAL4,
    //    WACTUAL5,
    //    ROUND (WAVAR1 / 1000000, 2) WAVAR1,
    //    ROUND (WAVAR2 / 1000000, 2) WAVAR2,
    //    ROUND (WAVAR3 / 1000000, 2) WAVAR3,
    //    ROUND (WAVAR4 / 1000000, 2) WAVAR4,
    //    ROUND (WAVAR5 / 1000000, 2) WAVAR5
    //              FROM (  SELECT PRD.cftype,
    //                     STAGE1.financegroup,
    //                     FCAT.FCNAME AS FORECAST_CATEGORY,
    //                     NVL (SUM (ROUND(PROPW1/1000000,2)), 0) PROPW1,
    //                     NVL (SUM (ROUND(PROPW2/1000000,2)), 0) PROPW2,
    //                     NVL (SUM (ROUND(PROPW3/1000000,2)), 0) PROPW3,
    //                     NVL (SUM (ROUND(PROPW4/1000000,2)), 0) PROPW4,
    //                     NVL (SUM (ROUND(PROPW5/1000000,2)), 0) PROPW5,
    //                     NVL (SUM (ROUND(WACTUAL1/1000000,2)), 0) WACTUAL1,
    //                     NVL (SUM (ROUND(WACTUAL2/1000000,2)), 0) WACTUAL2,
    //                     NVL (SUM (ROUND(WACTUAL3/1000000,2)), 0) WACTUAL3,
    //                     NVL (SUM (ROUND(WACTUAL4/1000000,2)), 0) WACTUAL4,
    //                     NVL (SUM (ROUND(WACTUAL5/1000000,2)), 0) WACTUAL5,
    //                     CASE cftype
    //                        WHEN 'CASH OUT' THEN NVL (SUM (PROPW1) - SUM (WACTUAL1), 0)
    //                        ELSE NVL (SUM (WACTUAL1) - SUM (PROPW1), 0)
    //                     END
    //                        AS WAVAR1,
    //                     CASE cftype
    //                        WHEN 'CASH OUT' THEN NVL (SUM (PROPW2) - SUM (WACTUAL2), 0)
    //                        ELSE NVL (SUM (WACTUAL2) - SUM (PROPW2), 0)
    //                     END
    //                        AS WAVAR2,
    //                     CASE cftype
    //                        WHEN 'CASH OUT' THEN NVL (SUM (PROPW3) - SUM (WACTUAL3), 0)
    //                        ELSE NVL (SUM (WACTUAL3) - SUM (PROPW3), 0)
    //                     END
    //                        AS WAVAR3,
    //                     CASE cftype
    //                        WHEN 'CASH OUT' THEN NVL (SUM (PROPW4) - SUM (WACTUAL4), 0)
    //                        ELSE NVL (SUM (WACTUAL4) - SUM (PROPW4), 0)
    //                     END
    //                        AS WAVAR4,
    //                     CASE cftype
    //                        WHEN 'CASH OUT' THEN NVL (SUM (PROPW5) - SUM (WACTUAL5), 0)
    //                        ELSE NVL (SUM (WACTUAL5) - SUM (PROPW5), 0)
    //                     END
    //                        AS WAVAR5
    //                FROM (    SELECT seq_financegroup,
    //                                 financegroup,
    //                                 seq_materialgroup,
    //                                 materialgroup,
    //                                 seq_forecast_category,
    //                                 COMPANYGROUP,
    //                                 LPAD ('', 5 * (LEVEL - 1), '') || TO_CHAR (forecast_category)
    //                                    forecast_category
    //                            FROM CFREPORT_FRAMEWORK CFT
    //                           WHERE seq_financegroup <> -1 AND COMPANYGROUP = 'PLT' AND FORECAST_CATEGORY <> 'INTERCO'
    //                      START WITH seq_materialgroup > 0
    //                      CONNECT BY PRIOR seq_materialgroup = seq_financegroup
    //                        GROUP BY seq_financegroup,
    //                                 financegroup,
    //                                 seq_materialgroup,
    //                                 materialgroup,
    //                                 seq_forecast_category,
    //                                 COMPANYGROUP,
    //                                    LPAD ('', 5 * (LEVEL - 1), '')
    //                                 || TO_CHAR (forecast_category)
    //                        ORDER BY seq_financegroup) STAGE1
    //                     INNER JOIN (SELECT seq_forecast_category, forecast_category, cftype
    //                                   FROM CFREPORT_FRAMEWORK_GROUP
    //                                  WHERE forecast_category <> '-1'  AND FORECAST_CATEGORY <> 'INTERCO') prd
    //                        ON (    stage1.seq_forecast_category = prd.seq_forecast_category
    //                            AND stage1.forecast_category = prd.forecast_category)
    //                     INNER JOIN (SELECT FCCODE,FCNAME FROM FORECAST_CATEGORY) FCAT ON FCAT.FCCODE = prd.FORECAST_CATEGORY
    //                     LEFT JOIN
    //                     (  SELECT CASHFLOWTYPE,
    //                               FORECAST_CATEGORY,
    //                               GROUPS,
    //                               MATERIALGROUP,
    //                               SUM (PROPW1) PROPW1,
    //                               SUM (PROPW2) PROPW2,
    //                               SUM (PROPW3) PROPW3,
    //                               SUM (PROPW4) PROPW4,
    //                               SUM (PROPW5) PROPW5,
    //                               SUM (WACTUAL1) WACTUAL1,
    //                               SUM (WACTUAL2) WACTUAL2,
    //                               SUM (WACTUAL3) WACTUAL3,
    //                               SUM (WACTUAL4) WACTUAL4,
    //                               SUM (WACTUAL5) WACTUAL5
    //                          FROM (SELECT CASE D.CASHFLOWTYPE
    //                                          WHEN 1 THEN 'CASH OUT'
    //                                          ELSE 'CASH IN'
    //                                       END
    //                                          AS CASHFLOWTYPE,
    //                                       DC.FORECAST_CATEGORY,
    //                                       DC.DEPARTMENT,
    //                                       MG.FCNAME AS GROUPS,
    //                                       MG.FCCODE MATERIALGROUP,
    //                                       FF.MONTH,
    //                                       FF.YEAR,
    //                                       CASE WHEN FF.WEEK = 'W1' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW1,
    //                                       CASE WHEN FF.WEEK = 'W2' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW2,
    //                                       CASE WHEN FF.WEEK = 'W3' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW3,
    //                                       CASE WHEN FF.WEEK = 'W4' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW4,
    //                                       CASE WHEN FF.WEEK = 'W5' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW5,
    //                                       0 WACTUAL1,
    //                                       0 WACTUAL2,
    //                                       0 WACTUAL3,
    //                                       0 WACTUAL4,
    //                                       0 WACTUAL5
    //                                  FROM (SELECT department,
    //                                               year,
    //                                               month,
    //                                               week,
    //                                               cftransid,
    //                                               amountadjs
    //                                          FROM forecast_fix
    //                                         WHERE     year = '$YEAR'
    //                                               AND month = '$MONTH2') ff
    //                                       INNER JOIN (SELECT id,
    //                                                          company,
    //                                                          doctype,
    //                                                          docref,
    //                                                          extsys
    //                                                     FROM cf_transaction
    //                                                    WHERE company LIKE '%$COMPANY%') CF
    //                                          ON CF.ID = FF.CFTRANSID
    //                                       LEFT JOIN
    //                                       (SELECT b.id,
    //                                               b.company,
    //                                               b.docnumber,
    //                                               a.material,
    //                                               b.businessunit,
    //                                               a.amount_include_vat,
    //                                               a.id AS aydi
    //                                          FROM cf_transaction_det a
    //                                               INNER JOIN cf_transaction b ON (a.id = b.id)) docs
    //                                          ON (    cf.company = docs.company
    //                                              AND cf.docref = docs.docnumber)
    //                                       INNER JOIN
    //                                       (SELECT material, materialgroup FROM material_groupitem) mgi
    //                                          ON mgi.material = docs.material
    //                                       INNER JOIN (SELECT id, fccode, fcname FROM material_group) mg
    //                                          ON mg.id = mgi.materialgroup
    //                                       INNER JOIN
    //                                       (SELECT forecast_category,
    //                                               department,
    //                                               fctype,
    //                                               materialgroup
    //                                          FROM department_category) dc
    //                                          ON (    dc.department = ff.department
    //                                              AND mg.fccode = dc.materialgroup)
    //                                       INNER JOIN
    //                                       (SELECT id,
    //                                               fccode,
    //                                               fctype,
    //                                               companygroup,
    //                                               company_subgroup
    //                                          FROM businessunit
    //                                         WHERE     companygroup LIKE '%$COMPANYGROUP%'
    //                                               AND company_subgroup LIKE '%$COMPANYSUBGROUP%') bu
    //                                          ON (bu.fctype = dc.fctype AND docs.businessunit = bu.id)
    //                                       INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
    //                                 WHERE     FF.YEAR = '$YEAR'
    //                                       AND FF.MONTH = '$MONTH'
    //                                       AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                       AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                       AND CF.COMPANY LIKE '%$COMPANY%'
    //                                UNION ALL
    //                                SELECT CASE D.CASHFLOWTYPE
    //                                          WHEN 1 THEN 'CASH OUT'
    //                                          ELSE 'CASH IN'
    //                                       END
    //                                          AS CASHFLOWTYPE,
    //                                       DC.FORECAST_CATEGORY,
    //                                       DC.DEPARTMENT,
    //                                       MG.FCNAME AS GROUPS,
    //                                       MG.FCCODE MATERIALGROUP,
    //                                       TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
    //                                       TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
    //                                       0 PROPW1,
    //                                       0 PROPW2,
    //                                       0 PROPW3,
    //                                       0 PROPW4,
    //                                       0 PROPW5,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 1
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL1,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 2
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL2,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 3
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL3,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 4
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL4,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 5
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL5
    //                                  FROM (SELECT PAY.PAYMENTID,
    //                                               PAY.DATERELEASE,
    //                                               PAY.AMOUNTBANK AMOUNTPAYMENT,
    //                                               PAY.CFTRANSID,
    //                                               SW.*
    //                                          FROM (SELECT p.bankcode,
    //                                                p.paymentid,
    //                                                p.voucherno,
    //                                                p.daterelease,
    //                                                p.amountbank,
    //                                                p.cftransid
    //                                           FROM payment p
    //                                                INNER JOIN
    //                                                (SELECT fccode
    //                                                   FROM bank
    //                                                  WHERE isuseformonthlyforecast =
    //                                                           'Y') ba
    //                                                   ON (p.bankcode = ba.fccode)
    //                                          WHERE     TO_CHAR (p.daterelease,
    //                                                             'MM') = '$MONTH2'
    //                                                AND TO_CHAR (p.daterelease,
    //                                                             'YYYY') = '$YEAR') PAY
    //                                               INNER JOIN SETTING_WEEK SW
    //                                                  ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
    //                                                             SW.DATEFROM
    //                                                      AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
    //                                                             SW.DATEUNTIL
    //                                                      AND SW.MONTH =
    //                                                             TO_CHAR (PAY.DATERELEASE, 'MM')
    //                                                      AND SW.YEAR =
    //                                                             TO_CHAR (PAY.DATERELEASE,
    //                                                                      'YYYY'))) P
    //                                       INNER JOIN (SELECT id,
    //                                                          company,
    //                                                          doctype,
    //                                                          docref,
    //                                                          docnumber,
    //                                                          extsys,
    //                                                          department,
    //                                                          businessunit
    //                                                     FROM cf_transaction
    //                                                    WHERE company LIKE '%$COMPANY%') CF
    //                                          ON CF.ID = P.CFTRANSID
    //                                       INNER JOIN
    //                                         (SELECT id,material FROM cf_transaction_det) docs
    //                                          ON ( cf.id = docs.id)
    //                                         INNER JOIN
    //                                         (SELECT material, materialgroup, extsystem
    //                                            FROM material_groupitem) mgi
    //                                            ON (mgi.material = docs.material AND cf.extsys = mgi.extsystem)
    //                                         INNER JOIN
    //                                         (SELECT id, fccode, fcname FROM material_group)
    //                                         mg
    //                                            ON mg.id = mgi.materialgroup
    //                                         INNER JOIN
    //                                         (SELECT forecast_category,
    //                                                 department,
    //                                                 fctype,
    //                                                 materialgroup
    //                                            FROM department_category) dc
    //                                            ON (    dc.department = cf.department
    //                                                AND mg.fccode = dc.materialgroup)
    //                                         INNER JOIN
    //                                         (SELECT id,
    //                                                 fccode,
    //                                                 fctype,
    //                                                 companygroup,
    //                                                 company_subgroup
    //                                            FROM businessunit
    //                                           WHERE     companygroup LIKE '%$COMPANYGROUP%'
    //                                                 AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
    //                                         bu
    //                                            ON (    bu.fctype = dc.fctype
    //                                                AND cf.businessunit = bu.id)
    //                                       INNER JOIN (select fccode,cashflowtype from doctype where fccode in ('INV_AP_SPC','PDO')) D ON D.FCCODE = CF.DOCTYPE
    //                                 WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
    //                                       AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
    //                                       AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                       AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                       AND CF.COMPANY LIKE '%$COMPANY%'
    //                                       AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
    //                                       AND CF.DOCTYPE in ('INV_AP_SPC','PDO')
    //                                UNION ALL
    //                                SELECT CASE D.CASHFLOWTYPE
    //                                          WHEN 1 THEN 'CASH OUT'
    //                                          ELSE 'CASH IN'
    //                                       END
    //                                          AS CASHFLOWTYPE,
    //                                       DC.FORECAST_CATEGORY,
    //                                       DC.DEPARTMENT,
    //                                       MG.FCNAME AS GROUPS,
    //                                       MG.FCCODE MATERIALGROUP,
    //                                       TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
    //                                       TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
    //                                       0 PROPW1,
    //                                       0 PROPW2,
    //                                       0 PROPW3,
    //                                       0 PROPW4,
    //                                       0 PROPW5,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 1
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL1,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 2
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL2,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 3
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL3,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 4
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL4,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 5
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL5
    //                                  FROM (SELECT PAY.PAYMENTID,
    //                                               PAY.DATERELEASE,
    //                                               PAY.AMOUNT AMOUNTPAYMENT,
    //                                               PAY.CFTRANSID,
    //                                               SW.*
    //                                          FROM (
    //                                                  select
    //                                                  p.paymentid,
    //                                                  p.voucherno,
    //                                                  p.daterelease,
    //                                                  p.amountbank as amount,
    //                                                  p.cftransid                                        
    //                                                  from payment p
    //                                                  inner join (
    //                                                     select fccode from bank where isuseformonthlyforecast = 'Y'
    //                                                 ) ba
    //                                                 on (
    //                                                 p.bankcode = ba.fccode) 
    //                                                  where to_char (p.daterelease, 'MM') = '$MONTH2'
    //                                                  and to_char (p.daterelease, 'YYYY') = '$YEAR'
    //                                              ) PAY
    //                                               INNER JOIN SETTING_WEEK SW
    //                                                  ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
    //                                                             SW.DATEFROM
    //                                                      AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
    //                                                             SW.DATEUNTIL
    //                                                      AND SW.MONTH =
    //                                                             TO_CHAR (PAY.DATERELEASE, 'MM')
    //                                                      AND SW.YEAR =
    //                                                             TO_CHAR (PAY.DATERELEASE,
    //                                                                      'YYYY'))) P
    //                                       INNER JOIN (SELECT id,
    //                                                          company,
    //                                                          doctype,
    //                                                          docref,
    //                                                          docnumber,
    //                                                          extsys
    //                                                     FROM cf_transaction
    //                                                    WHERE company LIKE '%$COMPANY%' and docnumber not like '%TMPINV%') CF
    //                                          ON CF.ID = P.CFTRANSID
    //                                       LEFT JOIN
    //                                         (SELECT distinct b.company,
    //                                                 b.docnumber,
    //                                                 a.material,
    //                                                 b.businessunit,
    //                                                 b.department
    //                                            FROM cf_transaction_det a
    //                                                 INNER JOIN
    //                                                 (SELECT id,
    //                                                         company,
    //                                                         businessunit,
    //                                                         department,
    //                                                         docnumber,
    //                                                         extsys
    //                                                    FROM cf_transaction WHERE company LIKE '%$COMPANY%') b
    //                                                    ON (a.id = b.id)) docs
    //                                            ON (    cf.company = docs.company
    //                                                AND cf.docref = docs.docnumber)
    //                                         INNER JOIN
    //                                         (SELECT material, materialgroup, extsystem
    //                                            FROM material_groupitem) mgi
    //                                            ON (mgi.material = docs.material AND cf.extsys = mgi.extsystem)
    //                                         INNER JOIN
    //                                         (SELECT id, fccode, fcname FROM material_group)
    //                                         mg
    //                                            ON mg.id = mgi.materialgroup
    //                                         INNER JOIN
    //                                         (SELECT forecast_category,
    //                                                 department,
    //                                                 fctype,
    //                                                 materialgroup
    //                                            FROM department_category) dc
    //                                            ON (    dc.department = docs.department
    //                                                AND mg.fccode = dc.materialgroup)
    //                                         INNER JOIN
    //                                         (SELECT id,
    //                                                 fccode,
    //                                                 fctype,
    //                                                 companygroup,
    //                                                 company_subgroup
    //                                            FROM businessunit
    //                                           WHERE     companygroup LIKE '%$COMPANYGROUP%'
    //                                                 AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
    //                                         bu
    //                                            ON (    bu.fctype = dc.fctype
    //                                                AND docs.businessunit = bu.id)
    //                                       INNER JOIN (select fccode,cashflowtype from doctype where fccode not in ('INV_AP_SPC','PDO')) D ON D.FCCODE = CF.DOCTYPE
    //                                 WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
    //                                       AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
    //                                       AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                       AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                       AND CF.COMPANY LIKE '%$COMPANY%'
    //                                       AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
    //                                       AND CF.DOCTYPE not in ('INV_AP_SPC','PDO')
    //                                UNION ALL
    //                                SELECT DISTINCT CASE PO.CASHFLOWTYPE
    //                                          WHEN 1 THEN 'CASH OUT'
    //                                          ELSE 'CASH IN'
    //                                       END
    //                                          AS CASHFLOWTYPE,
    //                                       DC.FORECAST_CATEGORY,
    //                                       PO.DEPARTMENT,
    //                                       MG.FCNAME GROUPS,
    //                                       MG.FCCODE MATERIALGROUP,
    //                                       TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'MM')) MONTH,
    //                                       TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'YYYY')) YEAR,
    //                                       0 PROPW1,
    //                                       0 PROPW2,
    //                                       0 PROPW3,
    //                                       0 PROPW4,
    //                                       0 PROPW5,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 1
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL1,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 2
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL2,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 3
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL3,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 4
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL4,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 5
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL5
    //                                  FROM (SELECT PAY.PAYMENTID,
    //                                               PAY.DATERELEASE,
    //                                               'FINANCE' DEPARTMENT,
    //                                               case
    //                                               when pay.currency = 'IDR' then (pay.amount*1) 
    //                                               else (pay.amount*kurs.rate) 
    //                                               end as amountpayment,
    //                                               PAY.MATERIAL,
    //                                               PAY.COMPANY,
    //                                               PAY.CASHFLOWTYPE,
    //                                               SW.*
    //                                          FROM (select
    //                                                  poth.bankcode,
    //                                                  ba.currency,
    //                                                  poth.paymentid,
    //                                                  poth.voucherno,
    //                                                  poth.daterelease,
    //                                                  poth.amount,
    //                                                  poth.material,
    //                                                  poth.company,
    //                                                  poth.cashflowtype
    //                                                  from payment_other poth
    //                                                  inner join (
    //                     select company,fccode,currency from bank where isuseformonthlyforecast = 'Y'
    //                 ) ba on (poth.bankcode = ba.fccode and poth.company = ba.company)                        
    //                                                  where to_char (daterelease, 'MM') = '$MONTH2'
    //                                                  and to_char (daterelease, 'YYYY') = '$YEAR'
    //                                              ) PAY
    //                                               INNER JOIN SETTING_WEEK SW
    //                                                  ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
    //                                                             SW.DATEFROM
    //                                                      AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
    //                                                             SW.DATEUNTIL
    //                                                      AND SW.MONTH =
    //                                                             TO_CHAR (PAY.DATERELEASE, 'MM')
    //                                                      AND SW.YEAR =
    //                                                             TO_CHAR (PAY.DATERELEASE,
    //                                                                      'YYYY'))
    //                                                 left join (
    //                                                  select
    //                                                  b.fccode,
    //                                                  a.curscode,
    //                                                  a.cursyear,
    //                                                  a.cursmonth,
    //                                                  a.rate
    //                                                  from ( 
    //                                                      select
    //                                                      curscode,
    //                                                      cursyear,
    //                                                      cursmonth,
    //                                                      rate
    //                                                      from
    //                                                      curs
    //                                                  ) a
    //                                                  inner join (
    //                                                      select
    //                                                      fccode,
    //                                                      currency
    //                                                      from
    //                                                      bank
    //                                                  ) b 
    //                                                  on a.curscode = b.currency 
    //                                                  where a.cursyear = '$YEAR'
    //                                                  and a.cursmonth = '$MONTH'
    //                                              ) kurs
    //                                              on (
    //                                              pay.bankcode = kurs.fccode and
    //                                              to_number(to_char(pay.daterelease,'yyyy')) = kurs.cursyear and
    //                                              to_number(to_char(pay.daterelease,'mm')) = kurs.cursmonth)
    //                                                                      ) PO
    //                                       INNER JOIN
    //                                         (SELECT material, materialgroup
    //                                            FROM material_groupitem) mgi
    //                                            ON mgi.material = po.material
    //                                         INNER JOIN
    //                                         (SELECT id, fccode, fcname FROM material_group)
    //                                         mg
    //                                            ON mg.id = mgi.materialgroup
    //                                         INNER JOIN
    //                                         (SELECT forecast_category,
    //                                                 department,
    //                                                 fctype,
    //                                                 materialgroup
    //                                            FROM department_category) dc
    //                                            ON mg.fccode = dc.materialgroup
    //                                         INNER JOIN (SELECT id FROM company) c
    //                                            ON c.id = po.company
    //                                         INNER JOIN
    //                                         (SELECT id,
    //                                                 fccode,
    //                                                 fctype,
    //                                                 company,
    //                                                 companygroup,
    //                                                 company_subgroup
    //                                            FROM businessunit
    //                                           WHERE     companygroup LIKE '%$COMPANYGROUP%'
    //                                                 AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
    //                                         bu
    //                                            ON bu.company = po.company
    //                                 WHERE     TO_CHAR (PO.DATERELEASE, 'MM') = '$MONTH2'
    //                                       AND TO_CHAR (PO.DATERELEASE, 'YYYY') = '$YEAR'
    //                                       AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                       AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                       AND C.ID LIKE '%$COMPANY%'
    //                                )
    //                      GROUP BY CASHFLOWTYPE,
    //                               FORECAST_CATEGORY,
    //                               GROUPS,
    //                               MATERIALGROUP) result1
    //                        ON (    stage1.materialgroup = result1.materialgroup
    //                            AND stage1.FORECAST_CATEGORY = result1.FORECAST_CATEGORY)
    //            GROUP BY PRD.cftype,
    //                     STAGE1.seq_financegroup,
    //                     STAGE1.financegroup,
    //                     FCAT.FCNAME
    //            ORDER BY CFTYPE )");
    //   // var_dump($this->db->last_query());exit();
    //   return $query->result();
    // }

    public function ShowData($param){
        $COMPANY           =  $param['COMPANY'];
        $COMPANYGROUP      = $param['COMPANYGROUP'];
        $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
        $MONTH             = $param['MONTH'];
        $YEAR              = $param['YEAR'];
        if($MONTH < 10){
            $MONTH2 = "0".$MONTH;
        }else{
            $MONTH2 = $MONTH;
        }
      $query = $this->db->query(" SELECT CFTYPE, FINANCEGROUP,
       FORECAST_CATEGORY,
       PROPW1,
       PROPW2,
       PROPW3,
       PROPW4,
       PROPW5,
       WACTUAL1,
       WACTUAL2,
       WACTUAL3,
       WACTUAL4,
       WACTUAL5,
       ROUND (WAVAR1 / 1000000, 2) WAVAR1,
       ROUND (WAVAR2 / 1000000, 2) WAVAR2,
       ROUND (WAVAR3 / 1000000, 2) WAVAR3,
       ROUND (WAVAR4 / 1000000, 2) WAVAR4,
       ROUND (WAVAR5 / 1000000, 2) WAVAR5
                 FROM (  SELECT PRD.cftype,
                        STAGE1.financegroup,
                        FCAT.FCNAME AS FORECAST_CATEGORY,
                        NVL (SUM (ROUND(PROPW1/1000000,2)), 0) PROPW1,
                        NVL (SUM (ROUND(PROPW2/1000000,2)), 0) PROPW2,
                        NVL (SUM (ROUND(PROPW3/1000000,2)), 0) PROPW3,
                        NVL (SUM (ROUND(PROPW4/1000000,2)), 0) PROPW4,
                        NVL (SUM (ROUND(PROPW5/1000000,2)), 0) PROPW5,
                        NVL (SUM (ROUND(WACTUAL1/1000000,2)), 0) WACTUAL1,
                        NVL (SUM (ROUND(WACTUAL2/1000000,2)), 0) WACTUAL2,
                        NVL (SUM (ROUND(WACTUAL3/1000000,2)), 0) WACTUAL3,
                        NVL (SUM (ROUND(WACTUAL4/1000000,2)), 0) WACTUAL4,
                        NVL (SUM (ROUND(WACTUAL5/1000000,2)), 0) WACTUAL5,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW1) - SUM (WACTUAL1), 0)
                           ELSE NVL (SUM (WACTUAL1) - SUM (PROPW1), 0)
                        END
                           AS WAVAR1,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW2) - SUM (WACTUAL2), 0)
                           ELSE NVL (SUM (WACTUAL2) - SUM (PROPW2), 0)
                        END
                           AS WAVAR2,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW3) - SUM (WACTUAL3), 0)
                           ELSE NVL (SUM (WACTUAL3) - SUM (PROPW3), 0)
                        END
                           AS WAVAR3,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW4) - SUM (WACTUAL4), 0)
                           ELSE NVL (SUM (WACTUAL4) - SUM (PROPW4), 0)
                        END
                           AS WAVAR4,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW5) - SUM (WACTUAL5), 0)
                           ELSE NVL (SUM (WACTUAL5) - SUM (PROPW5), 0)
                        END
                           AS WAVAR5
                   FROM (    SELECT seq_financegroup,
                                    financegroup,
                                    seq_materialgroup,
                                    materialgroup,
                                    seq_forecast_category,
                                    COMPANYGROUP,
                                    LPAD ('', 5 * (LEVEL - 1), '') || TO_CHAR (forecast_category)
                                       forecast_category
                               FROM CFREPORT_FRAMEWORK CFT
                              WHERE seq_financegroup <> -1 AND COMPANYGROUP = 'PLT' AND FORECAST_CATEGORY <> 'INTERCO'
                         START WITH seq_materialgroup > 0
                         CONNECT BY PRIOR seq_materialgroup = seq_financegroup
                           GROUP BY seq_financegroup,
                                    financegroup,
                                    seq_materialgroup,
                                    materialgroup,
                                    seq_forecast_category,
                                    COMPANYGROUP,
                                       LPAD ('', 5 * (LEVEL - 1), '')
                                    || TO_CHAR (forecast_category)
                           ORDER BY seq_financegroup) STAGE1
                        INNER JOIN (SELECT seq_forecast_category, forecast_category, cftype
                                      FROM CFREPORT_FRAMEWORK_GROUP
                                     WHERE forecast_category <> '-1'  AND FORECAST_CATEGORY <> 'INTERCO') prd
                           ON (    stage1.seq_forecast_category = prd.seq_forecast_category
                               AND stage1.forecast_category = prd.forecast_category)
                        INNER JOIN (SELECT FCCODE,FCNAME FROM FORECAST_CATEGORY) FCAT ON FCAT.FCCODE = prd.FORECAST_CATEGORY
                        LEFT JOIN
                        (  SELECT CASHFLOWTYPE,
                                  FORECAST_CATEGORY,
                                  GROUPS,
                                  MATERIALGROUP,
                                  SUM (PROPW1) PROPW1,
                                  SUM (PROPW2) PROPW2,
                                  SUM (PROPW3) PROPW3,
                                  SUM (PROPW4) PROPW4,
                                  SUM (PROPW5) PROPW5,
                                  SUM (WACTUAL1) WACTUAL1,
                                  SUM (WACTUAL2) WACTUAL2,
                                  SUM (WACTUAL3) WACTUAL3,
                                  SUM (WACTUAL4) WACTUAL4,
                                  SUM (WACTUAL5) WACTUAL5
                             FROM (SELECT CASE D.CASHFLOWTYPE
                                             WHEN 1 THEN 'CASH OUT'
                                             ELSE 'CASH IN'
                                          END
                                             AS CASHFLOWTYPE,
                                          DC.FORECAST_CATEGORY,
                                          DC.DEPARTMENT,
                                          MG.FCNAME AS GROUPS,
                                          MG.FCCODE MATERIALGROUP,
                                          FF.MONTH,
                                          FF.YEAR,
                                          CASE WHEN FF.WEEK = 'W1' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW1,
                                          CASE WHEN FF.WEEK = 'W2' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW2,
                                          CASE WHEN FF.WEEK = 'W3' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW3,
                                          CASE WHEN FF.WEEK = 'W4' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW4,
                                          CASE WHEN FF.WEEK = 'W5' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW5,
                                          0 WACTUAL1,
                                          0 WACTUAL2,
                                          0 WACTUAL3,
                                          0 WACTUAL4,
                                          0 WACTUAL5
                                     FROM (SELECT department,
                                                  year,
                                                  month,
                                                  week,
                                                  cftransid,
                                                  amountadjs
                                             FROM forecast_fix
                                            WHERE     year = '$YEAR'
                                                  AND month = '$MONTH2') ff
                                          INNER JOIN (SELECT id,
                                                             company,
                                                             doctype,
                                                             docref,
                                                             extsys
                                                        FROM cf_transaction
                                                       WHERE company LIKE '%$COMPANY%') CF
                                             ON CF.ID = FF.CFTRANSID
                                          LEFT JOIN
                                          (SELECT b.id,
                                                  b.company,
                                                  b.docnumber,
                                                  a.material,
                                                  b.businessunit,
                                                  a.amount_include_vat,
                                                  a.id AS aydi
                                             FROM cf_transaction_det a
                                                  INNER JOIN cf_transaction b ON (a.id = b.id)) docs
                                             ON (    cf.company = docs.company
                                                 AND cf.docref = docs.docnumber)
                                          INNER JOIN
                                          (SELECT material, materialgroup FROM material_groupitem) mgi
                                             ON mgi.material = docs.material
                                          INNER JOIN (SELECT id, fccode, fcname FROM material_group) mg
                                             ON mg.id = mgi.materialgroup
                                          INNER JOIN
                                          (SELECT forecast_category,
                                                  department,
                                                  fctype,
                                                  materialgroup
                                             FROM department_category) dc
                                             ON (    dc.department = ff.department
                                                 AND mg.fccode = dc.materialgroup)
                                          INNER JOIN
                                          (SELECT id,
                                                  fccode,
                                                  fctype,
                                                  companygroup,
                                                  company_subgroup
                                             FROM businessunit
                                            WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                  AND company_subgroup LIKE '%$COMPANYSUBGROUP%') bu
                                             ON (bu.fctype = dc.fctype AND docs.businessunit = bu.id)
                                          INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                                    WHERE     FF.YEAR = '$YEAR'
                                          AND FF.MONTH = '$MONTH'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND CF.COMPANY LIKE '%$COMPANY%'
                                   UNION ALL
                                   SELECT CASE D.CASHFLOWTYPE
                                             WHEN 1 THEN 'CASH OUT'
                                             ELSE 'CASH IN'
                                          END
                                             AS CASHFLOWTYPE,
                                          DC.FORECAST_CATEGORY,
                                          DC.DEPARTMENT,
                                          MG.FCNAME AS GROUPS,
                                          MG.FCCODE MATERIALGROUP,
                                          TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
                                          TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
                                          0 PROPW1,
                                          0 PROPW2,
                                          0 PROPW3,
                                          0 PROPW4,
                                          0 PROPW5,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 1
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL1,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 2
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL2,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 3
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL3,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 4
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL4,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 5
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL5
                                     FROM (SELECT PAY.PAYMENTID,
                                                  PAY.DATERELEASE,
                                                  PAY.AMOUNTBANK AMOUNTPAYMENT,
                                                  PAY.CFTRANSID,
                                                  SW.*
                                             FROM (SELECT p.bankcode,
                                                   p.paymentid,
                                                   p.voucherno,
                                                   p.daterelease,
                                                   p.amountbank,
                                                   p.cftransid
                                              FROM payment p
                                                   INNER JOIN
                                                   (SELECT fccode
                                                      FROM bank
                                                     WHERE isuseformonthlyforecast =
                                                              'Y') ba
                                                      ON (p.bankcode = ba.fccode)
                                             WHERE     TO_CHAR (p.daterelease,
                                                                'MM') = '$MONTH2'
                                                   AND TO_CHAR (p.daterelease,
                                                                'YYYY') = '$YEAR') PAY
                                                  INNER JOIN SETTING_WEEK SW
                                                     ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                                                SW.DATEFROM
                                                         AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                                                SW.DATEUNTIL
                                                         AND SW.MONTH =
                                                                TO_CHAR (PAY.DATERELEASE, 'MM')
                                                         AND SW.YEAR =
                                                                TO_CHAR (PAY.DATERELEASE,
                                                                         'YYYY'))) P
                                          INNER JOIN (SELECT id,
                                                             company,
                                                             doctype,
                                                             docref,
                                                             docnumber,
                                                             extsys,
                                                             department,
                                                             businessunit
                                                        FROM cf_transaction
                                                       WHERE company LIKE '%$COMPANY%') CF
                                             ON CF.ID = P.CFTRANSID
                                          INNER JOIN
                                            (SELECT id,material FROM cf_transaction_det) docs
                                             ON ( cf.id = docs.id)
                                            INNER JOIN
                                            (SELECT material, materialgroup, extsystem
                                               FROM material_groupitem) mgi
                                               ON (mgi.material = docs.material AND cf.extsys = mgi.extsystem)
                                            INNER JOIN
                                            (SELECT id, fccode, fcname FROM material_group)
                                            mg
                                               ON mg.id = mgi.materialgroup
                                            INNER JOIN
                                            (SELECT forecast_category,
                                                    department,
                                                    fctype,
                                                    materialgroup
                                               FROM department_category) dc
                                               ON (    dc.department = cf.department
                                                   AND mg.fccode = dc.materialgroup)
                                            INNER JOIN
                                            (SELECT id,
                                                    fccode,
                                                    fctype,
                                                    companygroup,
                                                    company_subgroup
                                               FROM businessunit
                                              WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                    AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
                                            bu
                                               ON (    bu.fctype = dc.fctype
                                                   AND cf.businessunit = bu.id)
                                          INNER JOIN (select fccode,cashflowtype from doctype where fccode in ('INV_AP_SPC','PDO')) D ON D.FCCODE = CF.DOCTYPE
                                    WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
                                          AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND CF.COMPANY LIKE '%$COMPANY%'
                                          AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
                                          AND CF.DOCTYPE in ('INV_AP_SPC','PDO')
                                   UNION ALL
                                   SELECT CASE D.CASHFLOWTYPE
                                             WHEN 1 THEN 'CASH OUT'
                                             ELSE 'CASH IN'
                                          END
                                             AS CASHFLOWTYPE,
                                          DC.FORECAST_CATEGORY,
                                          DC.DEPARTMENT,
                                          MG.FCNAME AS GROUPS,
                                          MG.FCCODE MATERIALGROUP,
                                          TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
                                          TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
                                          0 PROPW1,
                                          0 PROPW2,
                                          0 PROPW3,
                                          0 PROPW4,
                                          0 PROPW5,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 1
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL1,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 2
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL2,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 3
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL3,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 4
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL4,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 5
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL5
                                     FROM (SELECT PAY.PAYMENTID,
                                                  PAY.DATERELEASE,
                                                  PAY.AMOUNT AMOUNTPAYMENT,
                                                  PAY.CFTRANSID,
                                                  SW.*
                                             FROM (
                                                     select
                                                     p.paymentid,
                                                     p.voucherno,
                                                     p.daterelease,
                                                     p.amountbank as amount,
                                                     p.cftransid                                        
                                                     from payment p
                                                     inner join (
                                                        select fccode from bank where isuseformonthlyforecast = 'Y'
                                                    ) ba
                                                    on (
                                                    p.bankcode = ba.fccode) 
                                                     where to_char (p.daterelease, 'MM') = '$MONTH2'
                                                     and to_char (p.daterelease, 'YYYY') = '$YEAR'
                                                 ) PAY
                                                  INNER JOIN SETTING_WEEK SW
                                                     ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                                                SW.DATEFROM
                                                         AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                                                SW.DATEUNTIL
                                                         AND SW.MONTH =
                                                                TO_CHAR (PAY.DATERELEASE, 'MM')
                                                         AND SW.YEAR =
                                                                TO_CHAR (PAY.DATERELEASE,
                                                                         'YYYY'))) P
                                          INNER JOIN (SELECT id,
                                                             company,
                                                             doctype,
                                                             docref,
                                                             docnumber,
                                                             extsys
                                                        FROM cf_transaction
                                                       WHERE company LIKE '%$COMPANY%' and docnumber not like '%TMPINV%') CF
                                             ON CF.ID = P.CFTRANSID
                                          LEFT JOIN
                                            (SELECT distinct b.company,
                                                    b.docnumber,
                                                    a.material,
                                                    b.businessunit,
                                                    b.department
                                               FROM cf_transaction_det a
                                                    INNER JOIN
                                                    (SELECT id,
                                                            company,
                                                            businessunit,
                                                            department,
                                                            docnumber,
                                                            extsys
                                                       FROM cf_transaction WHERE company LIKE '%$COMPANY%') b
                                                       ON (a.id = b.id)) docs
                                               ON (    cf.company = docs.company
                                                   AND cf.docref = docs.docnumber)
                                            INNER JOIN
                                            (SELECT material, materialgroup, extsystem
                                               FROM material_groupitem) mgi
                                               ON (mgi.material = docs.material AND cf.extsys = mgi.extsystem)
                                            INNER JOIN
                                            (SELECT id, fccode, fcname FROM material_group)
                                            mg
                                               ON mg.id = mgi.materialgroup
                                            INNER JOIN
                                            (SELECT forecast_category,
                                                    department,
                                                    fctype,
                                                    materialgroup
                                               FROM department_category) dc
                                               ON (    dc.department = docs.department
                                                   AND mg.fccode = dc.materialgroup)
                                            INNER JOIN
                                            (SELECT id,
                                                    fccode,
                                                    fctype,
                                                    companygroup,
                                                    company_subgroup
                                               FROM businessunit
                                              WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                    AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
                                            bu
                                               ON (    bu.fctype = dc.fctype
                                                   AND docs.businessunit = bu.id)
                                          INNER JOIN (select fccode,cashflowtype from doctype where fccode not in ('INV_AP_SPC','PDO')) D ON D.FCCODE = CF.DOCTYPE
                                    WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
                                          AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND CF.COMPANY LIKE '%$COMPANY%'
                                          AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
                                          AND CF.DOCTYPE not in ('INV_AP_SPC','PDO')
                                   UNION ALL
                                   SELECT DISTINCT CASE PO.CASHFLOWTYPE
                                             WHEN 1 THEN 'CASH OUT'
                                             ELSE 'CASH IN'
                                          END
                                             AS CASHFLOWTYPE,
                                          DC.FORECAST_CATEGORY,
                                          PO.DEPARTMENT,
                                          MG.FCNAME GROUPS,
                                          MG.FCCODE MATERIALGROUP,
                                          TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'MM')) MONTH,
                                          TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'YYYY')) YEAR,
                                          0 PROPW1,
                                          0 PROPW2,
                                          0 PROPW3,
                                          0 PROPW4,
                                          0 PROPW5,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 1
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL1,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 2
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL2,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 3
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL3,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 4
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL4,
                                          CASE
                                             WHEN to_number(substr(week,2,1)) = 5
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL5
                                     FROM (SELECT PAY.PAYMENTID,
                                                  PAY.DATERELEASE,
                                                  'FINANCE' DEPARTMENT,
                                                  case
                                                  when pay.currency = 'IDR' then (pay.amount*1) 
                                                  else (pay.amount*kurs.rate) 
                                                  end as amountpayment,
                                                  PAY.MATERIAL,
                                                  PAY.COMPANY,
                                                  PAY.CASHFLOWTYPE,
                                                  SW.*
                                             FROM (select
                                                     poth.bankcode,
                                                     ba.currency,
                                                     poth.paymentid,
                                                     poth.voucherno,
                                                     poth.daterelease,
                                                     poth.amount,
                                                     poth.material,
                                                     poth.company,
                                                     poth.cashflowtype
                                                     from payment_other poth
                                                     inner join (
                        select company,fccode,currency from bank where isuseformonthlyforecast = 'Y'
                    ) ba on (poth.bankcode = ba.fccode and poth.company = ba.company)                        
                                                     where to_char (daterelease, 'MM') = '$MONTH2'
                                                     and to_char (daterelease, 'YYYY') = '$YEAR'
                                                 ) PAY
                                                  INNER JOIN SETTING_WEEK SW
                                                     ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                                                SW.DATEFROM
                                                         AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                                                SW.DATEUNTIL
                                                         AND SW.MONTH =
                                                                TO_CHAR (PAY.DATERELEASE, 'MM')
                                                         AND SW.YEAR =
                                                                TO_CHAR (PAY.DATERELEASE,
                                                                         'YYYY'))
                                                    left join (
                                                     select
                                                     b.fccode,
                                                     a.curscode,
                                                     a.cursyear,
                                                     a.cursmonth,
                                                     a.rate
                                                     from ( 
                                                         select
                                                         curscode,
                                                         cursyear,
                                                         cursmonth,
                                                         rate
                                                         from
                                                         curs
                                                     ) a
                                                     inner join (
                                                         select
                                                         fccode,
                                                         currency
                                                         from
                                                         bank
                                                     ) b 
                                                     on a.curscode = b.currency 
                                                     where a.cursyear = '$YEAR'
                                                     and a.cursmonth = '$MONTH'
                                                 ) kurs
                                                 on (
                                                 pay.bankcode = kurs.fccode and
                                                 to_number(to_char(pay.daterelease,'yyyy')) = kurs.cursyear and
                                                 to_number(to_char(pay.daterelease,'mm')) = kurs.cursmonth)
                                                                         ) PO
                                          INNER JOIN
                                            (SELECT material, materialgroup
                                               FROM material_groupitem) mgi
                                               ON mgi.material = po.material
                                            INNER JOIN
                                            (SELECT id, fccode, fcname FROM material_group)
                                            mg
                                               ON mg.id = mgi.materialgroup
                                            INNER JOIN
                                            (SELECT forecast_category,
                                                    department,
                                                    fctype,
                                                    materialgroup
                                               FROM department_category) dc
                                               ON mg.fccode = dc.materialgroup
                                            INNER JOIN (SELECT id FROM company) c
                                               ON c.id = po.company
                                            INNER JOIN
                                            (SELECT id,
                                                    fccode,
                                                    fctype,
                                                    company,
                                                    companygroup,
                                                    company_subgroup
                                               FROM businessunit
                                              WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                    AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
                                            bu
                                               ON bu.company = po.company
                                    WHERE     TO_CHAR (PO.DATERELEASE, 'MM') = '$MONTH2'
                                          AND TO_CHAR (PO.DATERELEASE, 'YYYY') = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND C.ID LIKE '%$COMPANY%'
                                   )
                         GROUP BY CASHFLOWTYPE,
                                  FORECAST_CATEGORY,
                                  GROUPS,
                                  MATERIALGROUP) result1
                           ON (    stage1.materialgroup = result1.materialgroup
                               AND stage1.FORECAST_CATEGORY = result1.FORECAST_CATEGORY)
               GROUP BY PRD.cftype,
                        STAGE1.seq_financegroup,
                        STAGE1.financegroup,
                        FCAT.FCNAME
               ORDER BY CFTYPE )");
      // var_dump($this->db->last_query());exit();
      return $query->result();
    }

    public function ShowDataedited($param){
        $COMPANY           =  $param['COMPANY'];
        $COMPANYGROUP      = $param['COMPANYGROUP'];
        $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
        $MONTH             = $param['MONTH'];
        $YEAR              = $param['YEAR'];
        if($MONTH < 10){
            $MONTH2 = "0".$MONTH;
        }else{
            $MONTH2 = $MONTH;
        }
      $query = $this->db->query(" SELECT CFTYPE, FINANCEGROUP,
       FORECAST_CATEGORY,
       PROPW1,
       PROPW2,
       PROPW3,
       PROPW4,
       PROPW5,
       WACTUAL1,
       WACTUAL2,
       WACTUAL3,
       WACTUAL4,
       WACTUAL5,
       ROUND (WAVAR1 / 1000000, 2) WAVAR1,
       ROUND (WAVAR2 / 1000000, 2) WAVAR2,
       ROUND (WAVAR3 / 1000000, 2) WAVAR3,
       ROUND (WAVAR4 / 1000000, 2) WAVAR4,
       ROUND (WAVAR5 / 1000000, 2) WAVAR5
                 FROM (  SELECT PRD.cftype,
                        STAGE1.financegroup,
                        FCAT.FCNAME AS FORECAST_CATEGORY,
                        NVL (SUM (ROUND(PROPW1/1000000,2)), 0) PROPW1,
                        NVL (SUM (ROUND(PROPW2/1000000,2)), 0) PROPW2,
                        NVL (SUM (ROUND(PROPW3/1000000,2)), 0) PROPW3,
                        NVL (SUM (ROUND(PROPW4/1000000,2)), 0) PROPW4,
                        NVL (SUM (ROUND(PROPW5/1000000,2)), 0) PROPW5,
                        NVL (SUM (ROUND(WACTUAL1/1000000,2)), 0) WACTUAL1,
                        NVL (SUM (ROUND(WACTUAL2/1000000,2)), 0) WACTUAL2,
                        NVL (SUM (ROUND(WACTUAL3/1000000,2)), 0) WACTUAL3,
                        NVL (SUM (ROUND(WACTUAL4/1000000,2)), 0) WACTUAL4,
                        NVL (SUM (ROUND(WACTUAL5/1000000,2)), 0) WACTUAL5,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW1) - SUM (WACTUAL1), 0)
                           ELSE NVL (SUM (WACTUAL1) - SUM (PROPW1), 0)
                        END
                           AS WAVAR1,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW2) - SUM (WACTUAL2), 0)
                           ELSE NVL (SUM (WACTUAL2) - SUM (PROPW2), 0)
                        END
                           AS WAVAR2,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW3) - SUM (WACTUAL3), 0)
                           ELSE NVL (SUM (WACTUAL3) - SUM (PROPW3), 0)
                        END
                           AS WAVAR3,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW4) - SUM (WACTUAL4), 0)
                           ELSE NVL (SUM (WACTUAL4) - SUM (PROPW4), 0)
                        END
                           AS WAVAR4,
                        CASE cftype
                           WHEN 'CASH OUT' THEN NVL (SUM (PROPW5) - SUM (WACTUAL5), 0)
                           ELSE NVL (SUM (WACTUAL5) - SUM (PROPW5), 0)
                        END
                           AS WAVAR5
                   FROM (    SELECT seq_financegroup,
                                    financegroup,
                                    seq_materialgroup,
                                    materialgroup,
                                    seq_forecast_category,
                                    COMPANYGROUP,
                                    LPAD ('', 5 * (LEVEL - 1), '') || TO_CHAR (forecast_category)
                                       forecast_category
                               FROM CFREPORT_FRAMEWORK CFT
                              WHERE seq_financegroup <> -1 AND COMPANYGROUP = 'PLT' AND FORECAST_CATEGORY <> 'INTERCO'
                         START WITH seq_materialgroup > 0
                         CONNECT BY PRIOR seq_materialgroup = seq_financegroup
                           GROUP BY seq_financegroup,
                                    financegroup,
                                    seq_materialgroup,
                                    materialgroup,
                                    seq_forecast_category,
                                    COMPANYGROUP,
                                       LPAD ('', 5 * (LEVEL - 1), '')
                                    || TO_CHAR (forecast_category)
                           ORDER BY seq_financegroup) STAGE1
                        INNER JOIN (SELECT seq_forecast_category, forecast_category, cftype
                                      FROM CFREPORT_FRAMEWORK_GROUP
                                     WHERE forecast_category <> '-1'  AND FORECAST_CATEGORY <> 'INTERCO') prd
                           ON (    stage1.seq_forecast_category = prd.seq_forecast_category
                               AND stage1.forecast_category = prd.forecast_category)
                        INNER JOIN (SELECT FCCODE,FCNAME FROM FORECAST_CATEGORY) FCAT ON FCAT.FCCODE = prd.FORECAST_CATEGORY
                        LEFT JOIN
                        (  SELECT CASHFLOWTYPE,
                                  FORECAST_CATEGORY,
                                  GROUPS,
                                  MATERIALGROUP,
                                  SUM (PROPW1) PROPW1,
                                  SUM (PROPW2) PROPW2,
                                  SUM (PROPW3) PROPW3,
                                  SUM (PROPW4) PROPW4,
                                  SUM (PROPW5) PROPW5,
                                  SUM (WACTUAL1) WACTUAL1,
                                  SUM (WACTUAL2) WACTUAL2,
                                  SUM (WACTUAL3) WACTUAL3,
                                  SUM (WACTUAL4) WACTUAL4,
                                  SUM (WACTUAL5) WACTUAL5
                             FROM (SELECT CASE D.CASHFLOWTYPE
                                             WHEN 1 THEN 'CASH OUT'
                                             ELSE 'CASH IN'
                                          END
                                             AS CASHFLOWTYPE,
                                          DC.FORECAST_CATEGORY,
                                          DC.DEPARTMENT,
                                          MG.FCNAME AS GROUPS,
                                          MG.FCCODE MATERIALGROUP,
                                          FF.MONTH,
                                          FF.YEAR,
                                          CASE WHEN FF.WEEK = 'W1' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW1,
                                          CASE WHEN FF.WEEK = 'W2' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW2,
                                          CASE WHEN FF.WEEK = 'W3' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW3,
                                          CASE WHEN FF.WEEK = 'W4' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW4,
                                          CASE WHEN FF.WEEK = 'W5' THEN AMOUNTADJS ELSE 0 END
                                             AS PROPW5,
                                          0 WACTUAL1,
                                          0 WACTUAL2,
                                          0 WACTUAL3,
                                          0 WACTUAL4,
                                          0 WACTUAL5
                                     FROM (SELECT department,
                                                  year,
                                                  month,
                                                  week,
                                                  cftransid,
                                                  amountadjs
                                             FROM forecast_fix
                                            WHERE     year = '$YEAR'
                                                  AND month = '$MONTH2') ff
                                          INNER JOIN (SELECT id,
                                                             company,
                                                             doctype,
                                                             docref,
                                                             extsys
                                                        FROM cf_transaction
                                                       WHERE company LIKE '%$COMPANY%') CF
                                             ON CF.ID = FF.CFTRANSID
                                          LEFT JOIN
                                          (SELECT b.id,
                                                  b.company,
                                                  b.docnumber,
                                                  a.material,
                                                  b.businessunit,
                                                  a.amount_include_vat,
                                                  a.id AS aydi
                                             FROM cf_transaction_det a
                                                  INNER JOIN cf_transaction b ON (a.id = b.id)) docs
                                             ON (    cf.company = docs.company
                                                 AND cf.docref = docs.docnumber)
                                          INNER JOIN
                                          (SELECT material, materialgroup FROM material_groupitem) mgi
                                             ON mgi.material = docs.material
                                          INNER JOIN (SELECT id, fccode, fcname FROM material_group) mg
                                             ON mg.id = mgi.materialgroup
                                          INNER JOIN
                                          (SELECT forecast_category,
                                                  department,
                                                  fctype,
                                                  materialgroup
                                             FROM department_category) dc
                                             ON (    dc.department = ff.department
                                                 AND mg.fccode = dc.materialgroup)
                                          INNER JOIN
                                          (SELECT id,
                                                  fccode,
                                                  fctype,
                                                  companygroup,
                                                  company_subgroup
                                             FROM businessunit
                                            WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                  AND company_subgroup LIKE '%$COMPANYSUBGROUP%') bu
                                             ON (bu.fctype = dc.fctype AND docs.businessunit = bu.id)
                                          INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                                          INNER JOIN
                                   (SELECT id AS id_po, fccode AS MATERIALGROUP
                                      FROM (  SELECT data1.id,
                                                     data1.materialgroup,
                                                     mg.fccode,
                                                     SUM (data1.amount_include_vat)
                                                        AS amount_include_vat,
                                                     ROW_NUMBER ()
                                                     OVER (
                                                        PARTITION BY data1.id
                                                        ORDER BY
                                                           SUM (
                                                              data1.amount_include_vat) ASC)
                                                        AS rownumx
                                                FROM (SELECT a.id,
                                                             CASE
                                                                WHEN b.doctype LIKE
                                                                        '%SO%'
                                                                THEN
                                                                   mgso.materialgroup
                                                                WHEN b.doctype LIKE
                                                                        '%STO%'
                                                                THEN
                                                                   mgsto.materialgroup_3_sto
                                                                ELSE
                                                                   mgpo.materialgroup_2
                                                             END
                                                                AS materialgroup,
                                                             a.amount_include_vat
                                                        FROM (SELECT id,
                                                                     material,
                                                                     amount_include_vat
                                                                FROM cf_transaction_Det)
                                                             a
                                                             INNER JOIN
                                                             (SELECT id,
                                                                     doctype,
                                                                     extsys
                                                                FROM cf_transaction WHERE company LIKE '%$COMPANY%')
                                                             b
                                                                ON (a.id = b.id)
                                                             INNER JOIN
                                                             (SELECT material,
                                                                     materialgroup,
                                                                     extsystem
                                                                FROM material_groupitem)
                                                             mgso
                                                                ON (    a.material =
                                                                           mgso.material
                                                                    AND b.extsys =
                                                                           mgso.extsystem)
                                                             INNER JOIN
                                                             (SELECT material_2,
                                                                     materialgroup_2,
                                                                     extsystem
                                                                FROM material_groupitem)
                                                             mgpo
                                                                ON (    a.material =
                                                                           mgpo.material_2
                                                                    AND b.extsys =
                                                                           mgpo.extsystem)
                                                             INNER JOIN
                                                             (SELECT material_3_sto,
                                                                     materialgroup_3_sto,
                                                                     extsystem
                                                                FROM material_groupitem)
                                                             mgsto
                                                                ON (    a.material =
                                                                           mgsto.material_3_sto
                                                                    AND b.extsys =
                                                                           mgsto.extsystem))
                                                     data1
                                                     INNER JOIN
                                                     (SELECT id, fccode
                                                        FROM material_group) mg
                                                        ON (data1.materialgroup =
                                                               mg.id)
                                            GROUP BY data1.id,
                                                     data1.materialgroup,
                                                     mg.fccode)
                                     WHERE rownumx = 1) b
                                      ON (    docs.id = b.id_po
                                          AND MG.FCCODE = b.MATERIALGROUP)
                                    WHERE     FF.YEAR = '$YEAR'
                                          AND FF.MONTH = '$MONTH'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND CF.COMPANY LIKE '%$COMPANY%'
                                   UNION ALL
                                   SELECT CASE D.CASHFLOWTYPE
                                             WHEN 1 THEN 'CASH OUT'
                                             ELSE 'CASH IN'
                                          END
                                             AS CASHFLOWTYPE,
                                          DC.FORECAST_CATEGORY,
                                          DC.DEPARTMENT,
                                          MG.FCNAME AS GROUPS,
                                          MG.FCCODE MATERIALGROUP,
                                          TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
                                          TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
                                          0 PROPW1,
                                          0 PROPW2,
                                          0 PROPW3,
                                          0 PROPW4,
                                          0 PROPW5,
                                          CASE
                                             WHEN TO_CHAR (P.DATERELEASE, 'W') = 1
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL1,
                                          CASE
                                             WHEN TO_CHAR (P.DATERELEASE, 'W') = 2
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL2,
                                          CASE
                                             WHEN TO_CHAR (P.DATERELEASE, 'W') = 3
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL3,
                                          CASE
                                             WHEN TO_CHAR (P.DATERELEASE, 'W') = 4
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL4,
                                          CASE
                                             WHEN TO_CHAR (P.DATERELEASE, 'W') = 5
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL5
                                     FROM (SELECT PAY.PAYMENTID,
                                                  PAY.DATERELEASE,
                                                  PAY.AMOUNT AMOUNTPAYMENT,
                                                  PAY.CFTRANSID,
                                                  SW.*
                                             FROM PAYMENT PAY
                                                  INNER JOIN SETTING_WEEK SW
                                                     ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                                                SW.DATEFROM
                                                         AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                                                SW.DATEUNTIL
                                                         AND SW.MONTH =
                                                                TO_CHAR (PAY.DATERELEASE, 'MM')
                                                         AND SW.YEAR =
                                                                TO_CHAR (PAY.DATERELEASE,
                                                                         'YYYY'))) P
                                          INNER JOIN (SELECT id,
                                                             company,
                                                             doctype,
                                                             docref,
                                                             docnumber,
                                                             extsys
                                                        FROM cf_transaction
                                                       WHERE company LIKE '%$COMPANY%') CF
                                             ON CF.ID = P.CFTRANSID
                                          LEFT JOIN
                                            (SELECT b.company,
                                                    b.docnumber,
                                                    a.material,
                                                    b.businessunit,
                                                    b.department
                                               FROM cf_transaction_det a
                                                    INNER JOIN
                                                    (SELECT id,
                                                            company,
                                                            businessunit,
                                                            department,
                                                            docnumber
                                                       FROM cf_transaction WHERE company LIKE '%$COMPANY%') b
                                                       ON (a.id = b.id)) docs
                                               ON (    cf.company = docs.company
                                                   AND cf.docref = docs.docnumber)
                                            INNER JOIN
                                            (SELECT material, materialgroup
                                               FROM material_groupitem) mgi
                                               ON mgi.material = docs.material
                                            INNER JOIN
                                            (SELECT id, fccode, fcname FROM material_group)
                                            mg
                                               ON mg.id = mgi.materialgroup
                                            INNER JOIN
                                            (SELECT forecast_category,
                                                    department,
                                                    fctype,
                                                    materialgroup
                                               FROM department_category) dc
                                               ON (    dc.department = docs.department
                                                   AND mg.fccode = dc.materialgroup)
                                            INNER JOIN
                                            (SELECT id,
                                                    fccode,
                                                    fctype,
                                                    companygroup,
                                                    company_subgroup
                                               FROM businessunit
                                              WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                    AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
                                            bu
                                               ON (    bu.fctype = dc.fctype
                                                   AND docs.businessunit = bu.id)
                                          INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                                    WHERE     TO_CHAR (P.DATERELEASE, 'MM') = '$MONTH2'
                                          AND TO_CHAR (P.DATERELEASE, 'YYYY') = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND CF.COMPANY LIKE '%$COMPANY%'
                                          AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
                                   UNION ALL
                                   SELECT DISTINCT CASE PO.CASHFLOWTYPE
                                             WHEN 1 THEN 'CASH OUT'
                                             ELSE 'CASH IN'
                                          END
                                             AS CASHFLOWTYPE,
                                          DC.FORECAST_CATEGORY,
                                          PO.DEPARTMENT,
                                          MG.FCNAME GROUPS,
                                          MG.FCCODE MATERIALGROUP,
                                          TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'MM')) MONTH,
                                          TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'YYYY')) YEAR,
                                          0 PROPW1,
                                          0 PROPW2,
                                          0 PROPW3,
                                          0 PROPW4,
                                          0 PROPW5,
                                          CASE
                                             WHEN TO_CHAR (PO.DATERELEASE, 'W') = 1
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL1,
                                          CASE
                                             WHEN TO_CHAR (PO.DATERELEASE, 'W') = 2
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL2,
                                          CASE
                                             WHEN TO_CHAR (PO.DATERELEASE, 'W') = 3
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL3,
                                          CASE
                                             WHEN TO_CHAR (PO.DATERELEASE, 'W') = 4
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL4,
                                          CASE
                                             WHEN TO_CHAR (PO.DATERELEASE, 'W') = 5
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL5
                                     FROM (SELECT PAY.PAYMENTID,
                                                  PAY.DATERELEASE,
                                                  'FINANCE' DEPARTMENT,
                                                  PAY.AMOUNT AMOUNTPAYMENT,
                                                  PAY.MATERIAL,
                                                  PAY.COMPANY,
                                                  PAY.CASHFLOWTYPE,
                                                  SW.*
                                             FROM PAYMENT_OTHER PAY
                                                  INNER JOIN SETTING_WEEK SW
                                                     ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
                                                                SW.DATEFROM
                                                         AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
                                                                SW.DATEUNTIL
                                                         AND SW.MONTH =
                                                                TO_CHAR (PAY.DATERELEASE, 'MM')
                                                         AND SW.YEAR =
                                                                TO_CHAR (PAY.DATERELEASE,
                                                                         'YYYY'))) PO
                                          INNER JOIN
                                            (SELECT material, materialgroup
                                               FROM material_groupitem) mgi
                                               ON mgi.material = po.material
                                            INNER JOIN
                                            (SELECT id, fccode, fcname FROM material_group)
                                            mg
                                               ON mg.id = mgi.materialgroup
                                            INNER JOIN
                                            (SELECT forecast_category,
                                                    department,
                                                    fctype,
                                                    materialgroup
                                               FROM department_category) dc
                                               ON mg.fccode = dc.materialgroup
                                            INNER JOIN (SELECT id FROM company) c
                                               ON c.id = po.company
                                            INNER JOIN
                                            (SELECT id,
                                                    fccode,
                                                    fctype,
                                                    company,
                                                    companygroup,
                                                    company_subgroup
                                               FROM businessunit
                                              WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                    AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
                                            bu
                                               ON bu.company = po.company
                                    WHERE     TO_CHAR (PO.DATERELEASE, 'MM') = '$MONTH2'
                                          AND TO_CHAR (PO.DATERELEASE, 'YYYY') = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND C.ID LIKE '%$COMPANY%'
                                   UNION ALL
                                   SELECT 'CASH OUT' CASHFLOWTYPE,
                                          'INTERCO' FORECAST_CATEGORY,
                                          INTERCO.DEPARTMENT,
                                          'INTERCO OUT' GROUPS,
                                          'INTOU01' MATERIALGROUP,
                                          TO_NUMBER(TO_CHAR (INTERCO.DATERELEASE, 'MM')) MONTH,
                                          TO_NUMBER(TO_CHAR (INTERCO.DATERELEASE, 'YYYY')) YEAR,
                                          0 PROPW1,
                                          0 PROPW2,
                                          0 PROPW3,
                                          0 PROPW4,
                                          0 PROPW5,
                                          CASE
                                             WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 1
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL1,
                                          CASE
                                             WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 2
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL2,
                                          CASE
                                             WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 3
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL3,
                                          CASE
                                             WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 4
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL4,
                                          CASE
                                             WHEN TO_CHAR (INTERCO.DATERELEASE, 'W') = 5
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL5
                                     FROM (SELECT INTERCO.DATERELEASE,
                                                  INTERCO.AMOUNT AMOUNTPAYMENT,
                                                  INTERCO.COMPANYTARGET,
                                                  'FINANCE' DEPARTMENT,
                                                  SW.*
                                             FROM INTERCOLOANS INTERCO
                                                  INNER JOIN SETTING_WEEK SW
                                                     ON (    TO_CHAR (INTERCO.DATERELEASE, 'DD') >=
                                                                SW.DATEFROM
                                                         AND TO_CHAR (INTERCO.DATERELEASE, 'DD') <=
                                                                SW.DATEUNTIL
                                                         AND SW.MONTH =
                                                                TO_CHAR (INTERCO.DATERELEASE,
                                                                         'MM')
                                                         AND SW.YEAR =
                                                                TO_CHAR (INTERCO.DATERELEASE,
                                                                         'YYYY'))) INTERCO
                                          INNER JOIN COMPANY C ON C.ID = INTERCO.COMPANYTARGET
                                          INNER JOIN BUSINESSUNIT BU
                                             ON BU.COMPANY = INTERCO.COMPANYTARGET
                                    WHERE     TO_CHAR (INTERCO.DATERELEASE, 'MM') = '$MONTH2'
                                          AND TO_CHAR (INTERCO.DATERELEASE, 'YYYY') = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND C.ID LIKE '%$COMPANY%'
                                   UNION ALL
                                   SELECT 'CASH IN' CASHFLOWTYPE,
                                          'INTERCO' FORECAST_CATEGORY,
                                          INTERCOS.DEPARTMENT,
                                          'INTERCO IN' GROUPS,
                                          'INTIN01' MATERIALGROUP,
                                          TO_NUMBER(TO_CHAR (INTERCOS.DATERELEASE, 'MM')) MONTH,
                                          TO_NUMBER(TO_CHAR (INTERCOS.DATERELEASE, 'YYYY')) YEAR,
                                          0 PROPW1,
                                          0 PROPW2,
                                          0 PROPW3,
                                          0 PROPW4,
                                          0 PROPW5,
                                          CASE
                                             WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 1
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL1,
                                          CASE
                                             WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 2
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL2,
                                          CASE
                                             WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 3
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL3,
                                          CASE
                                             WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 4
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL4,
                                          CASE
                                             WHEN TO_CHAR (INTERCOS.DATERELEASE, 'W') = 5
                                             THEN
                                                AMOUNTPAYMENT
                                             ELSE
                                                0
                                          END
                                             AS WACTUAL5
                                     FROM (SELECT INTERCOS.DATERELEASE,
                                                  INTERCOS.AMOUNT AMOUNTPAYMENT,
                                                  INTERCOS.COMPANYSOURCE,
                                                  'FINANCE' DEPARTMENT,
                                                  SW.*
                                             FROM INTERCOLOANS INTERCOS
                                                  INNER JOIN SETTING_WEEK SW
                                                     ON (    TO_CHAR (INTERCOS.DATERELEASE,
                                                                      'DD') >= SW.DATEFROM
                                                         AND TO_CHAR (INTERCOS.DATERELEASE,
                                                                      'DD') <= SW.DATEUNTIL
                                                         AND SW.MONTH =
                                                                TO_CHAR (INTERCOS.DATERELEASE,
                                                                         'MM')
                                                         AND SW.YEAR =
                                                                TO_CHAR (INTERCOS.DATERELEASE,
                                                                         'YYYY'))) INTERCOS
                                          INNER JOIN COMPANY C ON C.ID = INTERCOS.COMPANYSOURCE
                                          INNER JOIN BUSINESSUNIT BU
                                             ON BU.COMPANY = INTERCOS.COMPANYSOURCE
                                    WHERE     TO_CHAR (INTERCOS.DATERELEASE, 'MM') = '$MONTH2'
                                          AND TO_CHAR (INTERCOS.DATERELEASE, 'YYYY') = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND C.ID LIKE '%$COMPANY%')
                         GROUP BY CASHFLOWTYPE,
                                  FORECAST_CATEGORY,
                                  GROUPS,
                                  MATERIALGROUP) result1
                           ON (    stage1.materialgroup = result1.materialgroup
                               AND stage1.FORECAST_CATEGORY = result1.FORECAST_CATEGORY)
               GROUP BY PRD.cftype,
                        STAGE1.seq_financegroup,
                        STAGE1.financegroup,
                        FCAT.FCNAME
               ORDER BY CFTYPE )");
      // var_dump($this->db->last_query());exit();
      return $query->result();
    }

    public function getOpbal($param){
        $COMPANY           = $param['COMPANY'];
        $COMPANYGROUP      = $param['COMPANYGROUP'];
        $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
        $MONTH             = $param['MONTH'];
        $YEAR              = $param['YEAR'];

        if($MONTH == 1){
            $MONTH  = 12;
            $MONTH2 = 12;
            $YEAR   = $YEAR - 1;
        }else{
            $MONTH = $MONTH - 1;
            if($MONTH < 10){
               $MONTH2 = "0".$MONTH;
            }else{
               $MONTH2 = $MONTH;
            }
        }
      
      $q = "SELECT * FROM MONTHLYFORECAST_OPBAL WHERE COMPANY = '$COMPANY' AND COMPANYGROUP = '$COMPANYGROUP' AND COMPANYSUBGROUP = '$COMPANYSUBGROUP' AND PERIOD_YEAR = '$YEAR' AND PERIOD_MONTH = '$MONTH'";  
      $query = $this->db->query($q)->row();
      // var_dump($this->db->last_query());exit();
         
      if($query == null){
         $MONTH = $param['MONTH'];
         $q = "SELECT SUM(OPBAL) / 1000000 OPBAL FROM (SELECT CASE
                   WHEN bb.currency = 'IDR' THEN bb.opening_balance * 1
                   ELSE bb.opening_balance * kurs.rate
                END
                   AS OPBAL
           FROM bankbalance bb
                LEFT JOIN
                (SELECT b.fccode,
                        a.curscode,
                        a.cursyear,
                        a.cursmonth,
                        a.rate
                   FROM (SELECT curscode, cursyear, cursmonth, rate FROM curs) a
                        INNER JOIN (SELECT fccode, currency FROM bank WHERE ISUSEFORMONTHLYFORECAST = 'Y') b
                           ON a.curscode = b.currency
                  WHERE a.cursyear = $YEAR AND a.cursmonth = $MONTH) kurs
                   ON (    bb.bankcode = kurs.fccode
                       AND bb.period_year =
                              kurs.cursyear
                       AND bb.period_month =
                              kurs.cursmonth)
          WHERE     bb.company = '$COMPANY'
                AND bb.period_year = '$YEAR'
                AND bb.period_month = '$MONTH')";
         $query = $this->db->query($q)->row();
      }
      
      return $query;
    }

    // public function getSurplus($param){
    //     $COMPANY           = $param['COMPANY'];
    //     $COMPANYGROUP      = $param['COMPANYGROUP'];
    //     $COMPANYSUBGROUP   = $param['COMPANYSUBGROUP'];
    //     $MONTH             = $param['MONTH'];
    //     $YEAR              = $param['YEAR'];
    //     if($MONTH < 10){
    //         $MONTH2 = "0".$MONTH;
    //     }else{
    //         $MONTH2 = $MONTH;
    //     }

    //      $query = $this->db->query("SELECT PROP1,
    //                PROP2,
    //                PROP3,
    //                PROP4,
    //                PROP5,
    //                (PROP1+PROP2+PROP3+PROP4+PROP5) totalProp,
    //                ACTUAL1,
    //                ACTUAL2,
    //                ACTUAL3,
    //                ACTUAL4,
    //                ACTUAL5,
    //                (ACTUAL1+ACTUAL2+ACTUAL3+ACTUAL4+ACTUAL5) totalAct,
    //                ACTUAL1 - PROP1 AS VAR1,
    //                ACTUAL2 - PROP2 AS VAR2,
    //                ACTUAL3 - PROP3 AS VAR3,
    //                ACTUAL4 - PROP4 AS VAR4,
    //                ACTUAL5 - PROP5 AS VAR5,
    //                ((ACTUAL1 - PROP1) + (ACTUAL2 - PROP2) + (ACTUAL3 - PROP3) + (ACTUAL4 - PROP4) + (ACTUAL5 - PROP5) ) totalVar
    //           FROM (SELECT SUM (
    //                           CASE WHEN CASHFLOWTYPE = 1 THEN PROPW1 * -1 ELSE PROPW1 END)
    //                           PROP1,
    //                        SUM (
    //                           CASE WHEN CASHFLOWTYPE = 1 THEN PROPW2 * -1 ELSE PROPW2 END)
    //                           PROP2,
    //                        SUM (
    //                           CASE WHEN CASHFLOWTYPE = 1 THEN PROPW3 * -1 ELSE PROPW3 END)
    //                           PROP3,
    //                        SUM (
    //                           CASE WHEN CASHFLOWTYPE = 1 THEN PROPW4 * -1 ELSE PROPW4 END)
    //                           PROP4,
    //                        SUM (
    //                           CASE WHEN CASHFLOWTYPE = 1 THEN PROPW5 * -1 ELSE PROPW5 END)
    //                           PROP5,
    //                        SUM (
    //                           CASE
    //                              WHEN CASHFLOWTYPE = 1 THEN WACTUAL1 * -1
    //                              ELSE WACTUAL1
    //                           END)
    //                           ACTUAL1,
    //                        SUM (
    //                           CASE
    //                              WHEN CASHFLOWTYPE = 1 THEN WACTUAL2 * -1
    //                              ELSE WACTUAL2
    //                           END)
    //                           ACTUAL2,
    //                        SUM (
    //                           CASE
    //                              WHEN CASHFLOWTYPE = 1 THEN WACTUAL3 * -1
    //                              ELSE WACTUAL3
    //                           END)
    //                           ACTUAL3,
    //                        SUM (
    //                           CASE
    //                              WHEN CASHFLOWTYPE = 1 THEN WACTUAL4 * -1
    //                              ELSE WACTUAL4
    //                           END)
    //                           ACTUAL4,
    //                        SUM (
    //                           CASE
    //                              WHEN CASHFLOWTYPE = 1 THEN WACTUAL5 * -1
    //                              ELSE WACTUAL5
    //                           END)
    //                           ACTUAL5
    //                   FROM ( SELECT CFTYPE, FINANCEGROUP,
    //                   FORECAST_CATEGORY,
    //                   CASHFLOWTYPE,
    //                   PROPW1,
    //                   PROPW2,
    //                   PROPW3,
    //                   PROPW4,
    //                   PROPW5,
    //                   WACTUAL1,
    //                   WACTUAL2,
    //                   WACTUAL3,
    //                   WACTUAL4,
    //                   WACTUAL5,
    //                   ROUND (WAVAR1 / 1000000, 2) WAVAR1,
    //                   ROUND (WAVAR2 / 1000000, 2) WAVAR2,
    //                   ROUND (WAVAR3 / 1000000, 2) WAVAR3,
    //                   ROUND (WAVAR4 / 1000000, 2) WAVAR4,
    //                   ROUND (WAVAR5 / 1000000, 2) WAVAR5
    //                             FROM (  SELECT PRD.cftype,
    //                                    STAGE1.financegroup,
    //                                    FCAT.FCNAME AS FORECAST_CATEGORY,
    //                                    FCAT.CASHFLOWTYPE,
    //                                    NVL (SUM (ROUND(PROPW1/1000000,2)), 0) PROPW1,
    //                                    NVL (SUM (ROUND(PROPW2/1000000,2)), 0) PROPW2,
    //                                    NVL (SUM (ROUND(PROPW3/1000000,2)), 0) PROPW3,
    //                                    NVL (SUM (ROUND(PROPW4/1000000,2)), 0) PROPW4,
    //                                    NVL (SUM (ROUND(PROPW5/1000000,2)), 0) PROPW5,
    //                                    NVL (SUM (ROUND(WACTUAL1/1000000,2)), 0) WACTUAL1,
    //                                    NVL (SUM (ROUND(WACTUAL2/1000000,2)), 0) WACTUAL2,
    //                                    NVL (SUM (ROUND(WACTUAL3/1000000,2)), 0) WACTUAL3,
    //                                    NVL (SUM (ROUND(WACTUAL4/1000000,2)), 0) WACTUAL4,
    //                                    NVL (SUM (ROUND(WACTUAL5/1000000,2)), 0) WACTUAL5,
    //                                    CASE cftype
    //                                       WHEN 'CASH OUT' THEN NVL (SUM (PROPW1) - SUM (WACTUAL1), 0)
    //                                       ELSE NVL (SUM (WACTUAL1) - SUM (PROPW1), 0)
    //                                    END
    //                                       AS WAVAR1,
    //                                    CASE cftype
    //                                       WHEN 'CASH OUT' THEN NVL (SUM (PROPW2) - SUM (WACTUAL2), 0)
    //                                       ELSE NVL (SUM (WACTUAL2) - SUM (PROPW2), 0)
    //                                    END
    //                                       AS WAVAR2,
    //                                    CASE cftype
    //                                       WHEN 'CASH OUT' THEN NVL (SUM (PROPW3) - SUM (WACTUAL3), 0)
    //                                       ELSE NVL (SUM (WACTUAL3) - SUM (PROPW3), 0)
    //                                    END
    //                                       AS WAVAR3,
    //                                    CASE cftype
    //                                       WHEN 'CASH OUT' THEN NVL (SUM (PROPW4) - SUM (WACTUAL4), 0)
    //                                       ELSE NVL (SUM (WACTUAL4) - SUM (PROPW4), 0)
    //                                    END
    //                                       AS WAVAR4,
    //                                    CASE cftype
    //                                       WHEN 'CASH OUT' THEN NVL (SUM (PROPW5) - SUM (WACTUAL5), 0)
    //                                       ELSE NVL (SUM (WACTUAL5) - SUM (PROPW5), 0)
    //                                    END
    //                                       AS WAVAR5
    //                               FROM (    SELECT seq_financegroup,
    //                                                financegroup,
    //                                                seq_materialgroup,
    //                                                materialgroup,
    //                                                seq_forecast_category,
    //                                                COMPANYGROUP,
    //                                                LPAD ('', 5 * (LEVEL - 1), '') || TO_CHAR (forecast_category)
    //                                                   forecast_category
    //                                           FROM CFREPORT_FRAMEWORK CFT
    //                                          WHERE seq_financegroup <> -1 AND COMPANYGROUP = 'PLT'
    //                                     START WITH seq_materialgroup > 0
    //                                     CONNECT BY PRIOR seq_materialgroup = seq_financegroup
    //                                       GROUP BY seq_financegroup,
    //                                                financegroup,
    //                                                seq_materialgroup,
    //                                                materialgroup,
    //                                                seq_forecast_category,
    //                                                COMPANYGROUP,
    //                                                   LPAD ('', 5 * (LEVEL - 1), '')
    //                                                || TO_CHAR (forecast_category)
    //                                       ORDER BY seq_financegroup) STAGE1
    //                                    INNER JOIN (SELECT seq_forecast_category, forecast_category, cftype
    //                                                  FROM CFREPORT_FRAMEWORK_GROUP
    //                                                 WHERE forecast_category <> '-1') prd
    //                                       ON (    stage1.seq_forecast_category = prd.seq_forecast_category
    //                                           AND stage1.forecast_category = prd.forecast_category)
    //                                    INNER JOIN (SELECT FCCODE,FCNAME, CASHFLOWTYPE
    //                                             FROM FORECAST_CATEGORY
    //                                            WHERE FCCODE NOT IN
    //                                                     ('SURPLUS_DEFICIT',
    //                                                      'INTERCO_IN',
    //                                                      'INTERCO_OUT')) FCAT ON FCAT.FCCODE = prd.FORECAST_CATEGORY
    //                                    LEFT JOIN
    //                                    (  SELECT CASHFLOWTYPE,
    //                               FORECAST_CATEGORY,
    //                               GROUPS,
    //                               MATERIALGROUP,
    //                               SUM (PROPW1) PROPW1,
    //                               SUM (PROPW2) PROPW2,
    //                               SUM (PROPW3) PROPW3,
    //                               SUM (PROPW4) PROPW4,
    //                               SUM (PROPW5) PROPW5,
    //                               SUM (WACTUAL1) WACTUAL1,
    //                               SUM (WACTUAL2) WACTUAL2,
    //                               SUM (WACTUAL3) WACTUAL3,
    //                               SUM (WACTUAL4) WACTUAL4,
    //                               SUM (WACTUAL5) WACTUAL5
    //                          FROM (SELECT CASE D.CASHFLOWTYPE
    //                                          WHEN 1 THEN 'CASH OUT'
    //                                          ELSE 'CASH IN'
    //                                       END
    //                                          AS CASHFLOWTYPE,
    //                                       DC.FORECAST_CATEGORY,
    //                                       DC.DEPARTMENT,
    //                                       MG.FCNAME AS GROUPS,
    //                                       MG.FCCODE MATERIALGROUP,
    //                                       FF.MONTH,
    //                                       FF.YEAR,
    //                                       CASE WHEN FF.WEEK = 'W1' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW1,
    //                                       CASE WHEN FF.WEEK = 'W2' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW2,
    //                                       CASE WHEN FF.WEEK = 'W3' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW3,
    //                                       CASE WHEN FF.WEEK = 'W4' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW4,
    //                                       CASE WHEN FF.WEEK = 'W5' THEN AMOUNTADJS ELSE 0 END
    //                                          AS PROPW5,
    //                                       0 WACTUAL1,
    //                                       0 WACTUAL2,
    //                                       0 WACTUAL3,
    //                                       0 WACTUAL4,
    //                                       0 WACTUAL5
    //                                  FROM (SELECT department,
    //                                               year,
    //                                               month,
    //                                               week,
    //                                               cftransid,
    //                                               amountadjs
    //                                          FROM forecast_fix
    //                                         WHERE     year = '$YEAR'
    //                                               AND month = '$MONTH2') ff
    //                                       INNER JOIN (SELECT id,
    //                                                          company,
    //                                                          doctype,
    //                                                          docref,
    //                                                          extsys
    //                                                     FROM cf_transaction
    //                                                    WHERE company LIKE '%$COMPANY%') CF
    //                                          ON CF.ID = FF.CFTRANSID
    //                                       LEFT JOIN
    //                                       (SELECT b.id,
    //                                               b.company,
    //                                               b.docnumber,
    //                                               a.material,
    //                                               b.businessunit,
    //                                               a.amount_include_vat,
    //                                               a.id AS aydi
    //                                          FROM cf_transaction_det a
    //                                               INNER JOIN cf_transaction b ON (a.id = b.id)) docs
    //                                          ON (    cf.company = docs.company
    //                                              AND cf.docref = docs.docnumber)
    //                                       INNER JOIN
    //                                       (SELECT material, materialgroup FROM material_groupitem) mgi
    //                                          ON mgi.material = docs.material
    //                                       INNER JOIN (SELECT id, fccode, fcname FROM material_group) mg
    //                                          ON mg.id = mgi.materialgroup
    //                                       INNER JOIN
    //                                       (SELECT forecast_category,
    //                                               department,
    //                                               fctype,
    //                                               materialgroup
    //                                          FROM department_category) dc
    //                                          ON (    dc.department = ff.department
    //                                              AND mg.fccode = dc.materialgroup)
    //                                       INNER JOIN
    //                                       (SELECT id,
    //                                               fccode,
    //                                               fctype,
    //                                               companygroup,
    //                                               company_subgroup
    //                                          FROM businessunit
    //                                         WHERE     companygroup LIKE '%$COMPANYGROUP%'
    //                                               AND company_subgroup LIKE '%$COMPANYSUBGROUP%') bu
    //                                          ON (bu.fctype = dc.fctype AND docs.businessunit = bu.id)
    //                                       INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
    //                                 WHERE     FF.YEAR = '$YEAR'
    //                                       AND FF.MONTH = '$MONTH'
    //                                       AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                       AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                       AND CF.COMPANY LIKE '%$COMPANY%'
    //                                UNION ALL
    //                                SELECT CASE D.CASHFLOWTYPE
    //                                          WHEN 1 THEN 'CASH OUT'
    //                                          ELSE 'CASH IN'
    //                                       END
    //                                          AS CASHFLOWTYPE,
    //                                       DC.FORECAST_CATEGORY,
    //                                       DC.DEPARTMENT,
    //                                       MG.FCNAME AS GROUPS,
    //                                       MG.FCCODE MATERIALGROUP,
    //                                       TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
    //                                       TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
    //                                       0 PROPW1,
    //                                       0 PROPW2,
    //                                       0 PROPW3,
    //                                       0 PROPW4,
    //                                       0 PROPW5,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 1
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL1,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 2
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL2,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 3
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL3,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 4
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL4,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 5
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL5
    //                                  FROM (SELECT PAY.PAYMENTID,
    //                                               PAY.DATERELEASE,
    //                                               PAY.AMOUNTBANK AMOUNTPAYMENT,
    //                                               PAY.CFTRANSID,
    //                                               SW.*
    //                                          FROM PAYMENT PAY
    //                                               INNER JOIN SETTING_WEEK SW
    //                                                  ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
    //                                                             SW.DATEFROM
    //                                                      AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
    //                                                             SW.DATEUNTIL
    //                                                      AND SW.MONTH =
    //                                                             TO_CHAR (PAY.DATERELEASE, 'MM')
    //                                                      AND SW.YEAR =
    //                                                             TO_CHAR (PAY.DATERELEASE,
    //                                                                      'YYYY'))) P
    //                                       INNER JOIN (SELECT id,
    //                                                          company,
    //                                                          doctype,
    //                                                          docref,
    //                                                          docnumber,
    //                                                          extsys,
    //                                                          department,
    //                                                          businessunit
    //                                                     FROM cf_transaction
    //                                                    WHERE company LIKE '%$COMPANY%') CF
    //                                          ON CF.ID = P.CFTRANSID
    //                                       INNER JOIN
    //                                         (SELECT id,material FROM cf_transaction_det) docs
    //                                          ON ( cf.id = docs.id)
    //                                         INNER JOIN
    //                                         (SELECT material, materialgroup, extsystem
    //                                            FROM material_groupitem) mgi
    //                                            ON (mgi.material = docs.material AND cf.extsys = mgi.extsystem)
    //                                         INNER JOIN
    //                                         (SELECT id, fccode, fcname FROM material_group)
    //                                         mg
    //                                            ON mg.id = mgi.materialgroup
    //                                         INNER JOIN
    //                                         (SELECT forecast_category,
    //                                                 department,
    //                                                 fctype,
    //                                                 materialgroup
    //                                            FROM department_category) dc
    //                                            ON (    dc.department = cf.department
    //                                                AND mg.fccode = dc.materialgroup)
    //                                         INNER JOIN
    //                                         (SELECT id,
    //                                                 fccode,
    //                                                 fctype,
    //                                                 companygroup,
    //                                                 company_subgroup
    //                                            FROM businessunit
    //                                           WHERE     companygroup LIKE '%$COMPANYGROUP%'
    //                                                 AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
    //                                         bu
    //                                            ON (    bu.fctype = dc.fctype
    //                                                AND cf.businessunit = bu.id)
    //                                       INNER JOIN (select fccode,cashflowtype from doctype where fccode in ('INV_AP_SPC','PDO')) D ON D.FCCODE = CF.DOCTYPE
    //                                 WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
    //                                       AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
    //                                       AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                       AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                       AND CF.COMPANY LIKE '%$COMPANY%'
    //                                       AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
    //                                       AND CF.DOCTYPE in ('INV_AP_SPC','PDO')
    //                                UNION ALL
    //                                SELECT CASE D.CASHFLOWTYPE
    //                                          WHEN 1 THEN 'CASH OUT'
    //                                          ELSE 'CASH IN'
    //                                       END
    //                                          AS CASHFLOWTYPE,
    //                                       DC.FORECAST_CATEGORY,
    //                                       DC.DEPARTMENT,
    //                                       MG.FCNAME AS GROUPS,
    //                                       MG.FCCODE MATERIALGROUP,
    //                                       TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) MONTH,
    //                                       TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) YEAR,
    //                                       0 PROPW1,
    //                                       0 PROPW2,
    //                                       0 PROPW3,
    //                                       0 PROPW4,
    //                                       0 PROPW5,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 1
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL1,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 2
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL2,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 3
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL3,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 4
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL4,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 5
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL5
    //                                  FROM (SELECT PAY.PAYMENTID,
    //                                               PAY.DATERELEASE,
    //                                               PAY.AMOUNT AMOUNTPAYMENT,
    //                                               PAY.CFTRANSID,
    //                                               SW.*
    //                                          FROM (
    //                                                  select
    //                                                  paymentid,
    //                                                  voucherno,
    //                                                  daterelease,
    //                                                  amountbank as amount,
    //                                                  cftransid                                        
    //                                                  from payment
    //                                                  where to_char (daterelease, 'MM') = '$MONTH2'
    //                                                  and to_char (daterelease, 'YYYY') = '$YEAR'
    //                                              ) PAY
    //                                               INNER JOIN SETTING_WEEK SW
    //                                                  ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
    //                                                             SW.DATEFROM
    //                                                      AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
    //                                                             SW.DATEUNTIL
    //                                                      AND SW.MONTH =
    //                                                             TO_CHAR (PAY.DATERELEASE, 'MM')
    //                                                      AND SW.YEAR =
    //                                                             TO_CHAR (PAY.DATERELEASE,
    //                                                                      'YYYY'))) P
    //                                       INNER JOIN (SELECT id,
    //                                                          company,
    //                                                          doctype,
    //                                                          docref,
    //                                                          docnumber,
    //                                                          extsys
    //                                                     FROM cf_transaction
    //                                                    WHERE company LIKE '%$COMPANY%' and docnumber not like '%TMPINV%') CF
    //                                          ON CF.ID = P.CFTRANSID
    //                                       LEFT JOIN
    //                                         (SELECT distinct b.company,
    //                                                 b.docnumber,
    //                                                 a.material,
    //                                                 b.businessunit,
    //                                                 b.department
    //                                            FROM cf_transaction_det a
    //                                                 INNER JOIN
    //                                                 (SELECT id,
    //                                                         company,
    //                                                         businessunit,
    //                                                         department,
    //                                                         docnumber,
    //                                                         extsys
    //                                                    FROM cf_transaction WHERE company LIKE '%$COMPANY%') b
    //                                                    ON (a.id = b.id)) docs
    //                                            ON (    cf.company = docs.company
    //                                                AND cf.docref = docs.docnumber)
    //                                         INNER JOIN
    //                                         (SELECT material, materialgroup, extsystem
    //                                            FROM material_groupitem) mgi
    //                                            ON (mgi.material = docs.material AND cf.extsys = mgi.extsystem)
    //                                         INNER JOIN
    //                                         (SELECT id, fccode, fcname FROM material_group)
    //                                         mg
    //                                            ON mg.id = mgi.materialgroup
    //                                         INNER JOIN
    //                                         (SELECT forecast_category,
    //                                                 department,
    //                                                 fctype,
    //                                                 materialgroup
    //                                            FROM department_category) dc
    //                                            ON (    dc.department = docs.department
    //                                                AND mg.fccode = dc.materialgroup)
    //                                         INNER JOIN
    //                                         (SELECT id,
    //                                                 fccode,
    //                                                 fctype,
    //                                                 companygroup,
    //                                                 company_subgroup
    //                                            FROM businessunit
    //                                           WHERE     companygroup LIKE '%$COMPANYGROUP%'
    //                                                 AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
    //                                         bu
    //                                            ON (    bu.fctype = dc.fctype
    //                                                AND docs.businessunit = bu.id)
    //                                       INNER JOIN (select fccode,cashflowtype from doctype where fccode not in ('INV_AP_SPC','PDO')) D ON D.FCCODE = CF.DOCTYPE
    //                                 WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
    //                                       AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
    //                                       AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                       AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                       AND CF.COMPANY LIKE '%$COMPANY%'
    //                                       AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
    //                                       AND CF.DOCTYPE not in ('INV_AP_SPC','PDO')
    //                                UNION ALL
    //                                SELECT DISTINCT CASE PO.CASHFLOWTYPE
    //                                          WHEN 1 THEN 'CASH OUT'
    //                                          ELSE 'CASH IN'
    //                                       END
    //                                          AS CASHFLOWTYPE,
    //                                       DC.FORECAST_CATEGORY,
    //                                       PO.DEPARTMENT,
    //                                       MG.FCNAME GROUPS,
    //                                       MG.FCCODE MATERIALGROUP,
    //                                       TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'MM')) MONTH,
    //                                       TO_NUMBER(TO_CHAR (PO.DATERELEASE, 'YYYY')) YEAR,
    //                                       0 PROPW1,
    //                                       0 PROPW2,
    //                                       0 PROPW3,
    //                                       0 PROPW4,
    //                                       0 PROPW5,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 1
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL1,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 2
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL2,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 3
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL3,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 4
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL4,
    //                                       CASE
    //                                          WHEN to_number(substr(week,2,1)) = 5
    //                                          THEN
    //                                             AMOUNTPAYMENT
    //                                          ELSE
    //                                             0
    //                                       END
    //                                          AS WACTUAL5
    //                                  FROM (SELECT PAY.PAYMENTID,
    //                                               PAY.DATERELEASE,
    //                                               'FINANCE' DEPARTMENT,
    //                                               case
    //                                               when pay.currency = 'IDR' then (pay.amount*1) 
    //                                               else (pay.amount*kurs.rate) 
    //                                               end as amountpayment,
    //                                               PAY.MATERIAL,
    //                                               PAY.COMPANY,
    //                                               PAY.CASHFLOWTYPE,
    //                                               SW.*
    //                                          FROM (select
    //                                                  poth.bankcode,
    //                                                  ba.currency,
    //                                                  poth.paymentid,
    //                                                  poth.voucherno,
    //                                                  poth.daterelease,
    //                                                  poth.amount,
    //                                                  poth.material,
    //                                                  poth.company,
    //                                                  poth.cashflowtype
    //                                                  from payment_other poth
    //                                                  inner join bank ba on (poth.bankcode = ba.fccode and poth.company = ba.company)                        
    //                                                  where to_char (daterelease, 'MM') = '$MONTH2'
    //                                                  and to_char (daterelease, 'YYYY') = '$YEAR'
    //                                              ) PAY
    //                                               INNER JOIN SETTING_WEEK SW
    //                                                  ON (    TO_CHAR (PAY.DATERELEASE, 'DD') >=
    //                                                             SW.DATEFROM
    //                                                      AND TO_CHAR (PAY.DATERELEASE, 'DD') <=
    //                                                             SW.DATEUNTIL
    //                                                      AND SW.MONTH =
    //                                                             TO_CHAR (PAY.DATERELEASE, 'MM')
    //                                                      AND SW.YEAR =
    //                                                             TO_CHAR (PAY.DATERELEASE,
    //                                                                      'YYYY'))
    //                                                 left join (
    //                                                  select
    //                                                  b.fccode,
    //                                                  a.curscode,
    //                                                  a.cursyear,
    //                                                  a.cursmonth,
    //                                                  a.rate
    //                                                  from ( 
    //                                                      select
    //                                                      curscode,
    //                                                      cursyear,
    //                                                      cursmonth,
    //                                                      rate
    //                                                      from
    //                                                      curs
    //                                                  ) a
    //                                                  inner join (
    //                                                      select
    //                                                      fccode,
    //                                                      currency
    //                                                      from
    //                                                      bank
    //                                                  ) b 
    //                                                  on a.curscode = b.currency 
    //                                                  where a.cursyear = '$YEAR'
    //                                                  and a.cursmonth = '$MONTH'
    //                                              ) kurs
    //                                              on (
    //                                              pay.bankcode = kurs.fccode and
    //                                              to_number(to_char(pay.daterelease,'yyyy')) = kurs.cursyear and
    //                                              to_number(to_char(pay.daterelease,'mm')) = kurs.cursmonth)
    //                                                                      ) PO
    //                                       INNER JOIN
    //                                         (SELECT material, materialgroup
    //                                            FROM material_groupitem) mgi
    //                                            ON mgi.material = po.material
    //                                         INNER JOIN
    //                                         (SELECT id, fccode, fcname FROM material_group)
    //                                         mg
    //                                            ON mg.id = mgi.materialgroup
    //                                         INNER JOIN
    //                                         (SELECT forecast_category,
    //                                                 department,
    //                                                 fctype,
    //                                                 materialgroup
    //                                            FROM department_category) dc
    //                                            ON mg.fccode = dc.materialgroup
    //                                         INNER JOIN (SELECT id FROM company) c
    //                                            ON c.id = po.company
    //                                         INNER JOIN
    //                                         (SELECT id,
    //                                                 fccode,
    //                                                 fctype,
    //                                                 company,
    //                                                 companygroup,
    //                                                 company_subgroup
    //                                            FROM businessunit
    //                                           WHERE     companygroup LIKE '%$COMPANYGROUP%'
    //                                                 AND company_subgroup LIKE '%$COMPANYSUBGROUP%')
    //                                         bu
    //                                            ON bu.company = po.company
    //                                 WHERE     TO_CHAR (PO.DATERELEASE, 'MM') = '$MONTH2'
    //                                       AND TO_CHAR (PO.DATERELEASE, 'YYYY') = '$YEAR'
    //                                       AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                       AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                       AND C.ID LIKE '%$COMPANY%'
    //                                       UNION ALL
    //                                       SELECT DISTINCT 'CASH OUT' CASHFLOWTYPE,
    //                                              'INTERCO' FORECAST_CATEGORY,
    //                                              INTERCO.DEPARTMENT,
    //                                              'INTERCO OUT' GROUPS,
    //                                              'INTOU01' MATERIALGROUP,
    //                                              TO_NUMBER (
    //                                                 TO_CHAR (INTERCO.DATERELEASE, 'MM'))
    //                                                 MONTH,
    //                                              TO_NUMBER (
    //                                                 TO_CHAR (INTERCO.DATERELEASE,
    //                                                          'YYYY'))
    //                                                 YEAR,
    //                                              0 PROPW1,
    //                                              0 PROPW2,
    //                                              0 PROPW3,
    //                                              0 PROPW4,
    //                                              0 PROPW5,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCO.DATERELEASE,
    //                                                               'W') = 1
    //                                                 THEN
    //                                                    AMOUNTPAYMENT * -1
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL1,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCO.DATERELEASE,
    //                                                               'W') = 2
    //                                                 THEN
    //                                                    AMOUNTPAYMENT * -1
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL2,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCO.DATERELEASE,
    //                                                               'W') = 3
    //                                                 THEN
    //                                                    AMOUNTPAYMENT * -1
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL3,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCO.DATERELEASE,
    //                                                               'W') = 4
    //                                                 THEN
    //                                                    AMOUNTPAYMENT * -1
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL4,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCO.DATERELEASE,
    //                                                               'W') = 5
    //                                                 THEN
    //                                                    AMOUNTPAYMENT * -1
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL5
    //                                         FROM (SELECT INTERCO.DATERELEASE,
    //                                                      INTERCO.AMOUNT AMOUNTPAYMENT,
    //                                                      INTERCO.COMPANYTARGET,
    //                                                      'FINANCE' DEPARTMENT,
    //                                                      SW.*
    //                                                 FROM INTERCOLOANS INTERCO
    //                                                      INNER JOIN SETTING_WEEK SW
    //                                                         ON (    TO_CHAR (
    //                                                                    INTERCO.DATERELEASE,
    //                                                                    'DD') >=
    //                                                                    SW.DATEFROM
    //                                                             AND TO_CHAR (
    //                                                                    INTERCO.DATERELEASE,
    //                                                                    'DD') <=
    //                                                                    SW.DATEUNTIL
    //                                                             AND SW.MONTH =
    //                                                                    TO_CHAR (
    //                                                                       INTERCO.DATERELEASE,
    //                                                                       'MM')
    //                                                             AND SW.YEAR =
    //                                                                    TO_CHAR (
    //                                                                       INTERCO.DATERELEASE,
    //                                                                       'YYYY')))
    //                                              INTERCO
    //                                              INNER JOIN COMPANY C
    //                                                 ON C.ID = INTERCO.COMPANYTARGET
    //                                              INNER JOIN BUSINESSUNIT BU
    //                                                 ON BU.COMPANY =
    //                                                       INTERCO.COMPANYTARGET
    //                                        WHERE     TO_CHAR (INTERCO.DATERELEASE, 'MM') = '$MONTH2'
    //                                             AND TO_CHAR (INTERCO.DATERELEASE, 'YYYY') = '$YEAR'
    //                                             AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                             AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                             AND C.ID LIKE '%$COMPANY%'
    //                                       UNION ALL
    //                                       SELECT DISTINCT 'CASH IN' CASHFLOWTYPE,
    //                                              'INTERCO' FORECAST_CATEGORY,
    //                                              INTERCOS.DEPARTMENT,
    //                                              'INTERCO IN' GROUPS,
    //                                              'INTIN01' MATERIALGROUP,
    //                                              TO_NUMBER (
    //                                                 TO_CHAR (INTERCOS.DATERELEASE,
    //                                                          'MM'))
    //                                                 MONTH,
    //                                              TO_NUMBER (
    //                                                 TO_CHAR (INTERCOS.DATERELEASE,
    //                                                          'YYYY'))
    //                                                 YEAR,
    //                                              0 PROPW1,
    //                                              0 PROPW2,
    //                                              0 PROPW3,
    //                                              0 PROPW4,
    //                                              0 PROPW5,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCOS.DATERELEASE,
    //                                                               'W') = 1
    //                                                 THEN
    //                                                    AMOUNTPAYMENT
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL1,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCOS.DATERELEASE,
    //                                                               'W') = 2
    //                                                 THEN
    //                                                    AMOUNTPAYMENT
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL2,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCOS.DATERELEASE,
    //                                                               'W') = 3
    //                                                 THEN
    //                                                    AMOUNTPAYMENT
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL3,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCOS.DATERELEASE,
    //                                                               'W') = 4
    //                                                 THEN
    //                                                    AMOUNTPAYMENT
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL4,
    //                                              CASE
    //                                                 WHEN TO_CHAR (INTERCOS.DATERELEASE,
    //                                                               'W') = 5
    //                                                 THEN
    //                                                    AMOUNTPAYMENT
    //                                                 ELSE
    //                                                    0
    //                                              END
    //                                                 AS WACTUAL5
    //                                         FROM (SELECT INTERCOS.DATERELEASE,
    //                                                      INTERCOS.AMOUNT AMOUNTPAYMENT,
    //                                                      INTERCOS.COMPANYSOURCE,
    //                                                      'FINANCE' DEPARTMENT,
    //                                                      SW.*
    //                                                 FROM INTERCOLOANS INTERCOS
    //                                                      INNER JOIN SETTING_WEEK SW
    //                                                         ON (    TO_CHAR (
    //                                                                    INTERCOS.DATERELEASE,
    //                                                                    'DD') >=
    //                                                                    SW.DATEFROM
    //                                                             AND TO_CHAR (
    //                                                                    INTERCOS.DATERELEASE,
    //                                                                    'DD') <=
    //                                                                    SW.DATEUNTIL
    //                                                             AND SW.MONTH =
    //                                                                    TO_CHAR (
    //                                                                       INTERCOS.DATERELEASE,
    //                                                                       'MM')
    //                                                             AND SW.YEAR =
    //                                                                    TO_CHAR (
    //                                                                       INTERCOS.DATERELEASE,
    //                                                                       'YYYY')))
    //                                              INTERCOS
    //                                              INNER JOIN COMPANY C
    //                                                 ON C.ID = INTERCOS.COMPANYSOURCE
    //                                              INNER JOIN BUSINESSUNIT BU
    //                                                 ON BU.COMPANY =
    //                                                       INTERCOS.COMPANYSOURCE
    //                                        WHERE     TO_CHAR (INTERCOS.DATERELEASE, 'MM') = '$MONTH2'
    //                                             AND TO_CHAR (INTERCOS.DATERELEASE, 'YYYY') = '$YEAR'
    //                                             AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
    //                                             AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
    //                                             AND C.ID LIKE '%$COMPANY%'
    //                                )
    //                      GROUP BY CASHFLOWTYPE,
    //                               FORECAST_CATEGORY,
    //                               GROUPS,
    //                               MATERIALGROUP) result1
    //                                       ON (    stage1.materialgroup = result1.materialgroup
    //                                           AND stage1.FORECAST_CATEGORY = result1.FORECAST_CATEGORY)
    //                           GROUP BY PRD.cftype,
    //                                    STAGE1.seq_financegroup,
    //                                    STAGE1.financegroup,
    //                                    FCAT.FCNAME,
    //                                    FCAT.CASHFLOWTYPE
    //                           ORDER BY CFTYPE ) )
    //                  WHERE FINANCEGROUP NOT IN ('OUT', 'IN'))");
    //      // var_dump($this->db->last_query());exit();
    //      return $query->row();
    // }

}