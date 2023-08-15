<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class CashModel extends CI_Model {
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
    

    public function getTotalInterco($param){
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
                                                   CASE
                                                      WHEN interco.currency = 'IDR'
                                                      THEN
                                                         (interco.amount * 1)
                                                      ELSE
                                                         (interco.amount * kurs.rate)
                                                   END
                                                      AS amountpayment,
                                                   INTERCO.COMPANYSOURCE,
                                                   'FINANCE' DEPARTMENT,
                                                   SW.*
                                              FROM (SELECT INTERCO.banksource,
                                                           INTERCO.daterelease,
                                                           INTERCO.sourceamount AS amount,
                                                           INTERCO.RATE,
                                                           INTERCO.companysource,
                                                           ba.currency
                                                         FROM INTERCOLOANS INTERCO
                                                              INNER JOIN
                                                              (SELECT company, fccode, currency
                                                                 FROM bank
                                                                WHERE isuseformonthlyforecast = 'Y')
                                                              ba
                                                                 ON (    INTERCO.banksource =
                                                                            ba.fccode
                                                                     AND INTERCO.companysource =
                                                                            ba.company)
                                                        WHERE     TO_CHAR (INTERCO.daterelease,
                                                                           'MM') = '$MONTH2'
                                                              AND TO_CHAR (INTERCO.daterelease,
                                                                           'YYYY') = '$YEAR') INTERCO
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
                                                                             'YYYY'))
                                                      LEFT JOIN
                                                      (SELECT b.fccode,
                                                              a.curscode,
                                                              a.cursyear,
                                                              a.cursmonth,
                                                              a.rate
                                                         FROM (SELECT curscode,
                                                                      cursyear,
                                                                      cursmonth,
                                                                      rate
                                                                 FROM curs) a
                                                              INNER JOIN
                                                              (SELECT fccode, currency FROM bank) b
                                                                 ON a.curscode = b.currency
                                                        WHERE a.cursyear = $YEAR AND a.cursmonth = $MONTH)
                                                      kurs
                                                         ON (    INTERCO.banksource = kurs.fccode
                                                             AND TO_NUMBER (
                                                                    TO_CHAR (INTERCO.daterelease,
                                                                             'yyyy')) =
                                                                    kurs.cursyear
                                                             AND TO_NUMBER (
                                                                    TO_CHAR (INTERCO.daterelease,
                                                                             'mm')) = kurs.cursmonth)
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
                                             CASE
                                                WHEN intercos.currency = 'IDR'
                                                THEN
                                                   (intercos.amount * 1)
                                                ELSE
                                                   (intercos.amount * kurs.rate)
                                             END
                                                AS amountpayment,
                                             INTERCOS.COMPANYTARGET,
                                             'FINANCE' DEPARTMENT,
                                             SW.*
                                        FROM (SELECT INTERCOS.banktarget,
                                                     INTERCOS.daterelease,
                                                     INTERCOS.amount AS amount,
                                                     INTERCOS.companytarget,
                                                     ba.currency
                                                FROM INTERCOLOANS INTERCOS
                                                     INNER JOIN
                                                     (SELECT company, fccode, currency
                                                        FROM bank
                                                       WHERE isuseformonthlyforecast = 'Y')
                                                     ba
                                                        ON (    INTERCOS.banktarget =
                                                                   ba.fccode
                                                            AND INTERCOS.companytarget =
                                                                   ba.company)
                                               WHERE     TO_CHAR (intercos.daterelease,
                                                                  'MM') = '$MONTH2'
                                                     AND TO_CHAR (intercos.daterelease,
                                                                  'YYYY') = '$YEAR')
                                             INTERCOS
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
                                                                    'YYYY'))
                                             LEFT JOIN
                                             (SELECT b.fccode,
                                                     a.curscode,
                                                     a.cursyear,
                                                     a.cursmonth,
                                                     a.rate
                                                FROM (SELECT curscode,
                                                             cursyear,
                                                             cursmonth,
                                                             rate
                                                        FROM curs) a
                                                     INNER JOIN
                                                     (SELECT fccode, currency FROM bank) b
                                                        ON a.curscode = b.currency
                                               WHERE a.cursyear = $YEAR AND a.cursmonth = $MONTH)
                                             kurs
                                                ON (    intercos.banktarget = kurs.fccode
                                                    AND TO_NUMBER (
                                                           TO_CHAR (intercos.daterelease,
                                                                    'yyyy')) =
                                                           kurs.cursyear
                                                    AND TO_NUMBER (
                                                           TO_CHAR (intercos.daterelease,
                                                                    'mm')) = kurs.cursmonth)
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

    public function getInOutInterco($param){
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
                                 CASE
                                    WHEN intercos.currency = 'IDR'
                                    THEN
                                       (intercos.amount * 1)
                                    ELSE
                                       (intercos.amount * kurs.rate)
                                 END
                                    AS amountpayment,
                                 INTERCOS.COMPANYTARGET,
                                 'FINANCE' DEPARTMENT,
                                 SW.*
                            FROM (SELECT INTERCOS.banktarget,
                               INTERCOS.daterelease,
                               INTERCOS.amount as amount,
                               INTERCOS.companytarget,
                               ba.currency
                          FROM INTERCOLOANS INTERCOS
                          INNER JOIN
                                         (SELECT company, fccode, currency
                                            FROM bank
                                           WHERE isuseformonthlyforecast = 'Y')
                                         ba
                                            ON (    INTERCOS.banktarget =
                                                       ba.fccode
                                                AND INTERCOS.companytarget =
                                                       ba.company)
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
                                 LEFT JOIN
                                 (SELECT b.fccode,
                                         a.curscode,
                                         a.cursyear,
                                         a.cursmonth,
                                         a.rate
                                    FROM (SELECT curscode,
                                                 cursyear,
                                                 cursmonth,
                                                 rate
                                            FROM curs) a
                                         INNER JOIN
                                         (SELECT fccode, currency FROM bank) b
                                            ON a.curscode = b.currency
                                   WHERE a.cursyear = $YEAR AND a.cursmonth = $MONTH)
                                 kurs
                                    ON (    intercos.banktarget = kurs.fccode
                                        AND TO_NUMBER (
                                               TO_CHAR (intercos.daterelease,
                                                        'yyyy')) =
                                               kurs.cursyear
                                        AND TO_NUMBER (
                                               TO_CHAR (intercos.daterelease,
                                                        'mm')) = kurs.cursmonth)
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
                         CASE
                                    WHEN interco.currency = 'IDR'
                                    THEN
                                       (interco.amount * 1)
                                    ELSE
                                       (interco.amount * kurs.rate)
                                 END
                                    AS amountpayment,
                         INTERCO.COMPANYSOURCE,
                         'FINANCE' DEPARTMENT,
                         SW.*
                    FROM (SELECT INTERCO.banksource,
                               INTERCO.daterelease,
                               INTERCO.sourceamount as amount,
                               INTERCO.RATE,
                               INTERCO.companysource,
                               ba.currency
                          FROM INTERCOLOANS INTERCO
                          INNER JOIN
                                         (SELECT company, fccode, currency
                                            FROM bank
                                           WHERE isuseformonthlyforecast = 'Y')
                                         ba
                                            ON (    INTERCO.banksource =
                                                       ba.fccode
                                                AND INTERCO.companysource =
                                                       ba.company)
                         WHERE     TO_CHAR (INTERCO.daterelease, 'MM') = '$MONTH2'
                               AND TO_CHAR (INTERCO.daterelease, 'YYYY') = '$YEAR') INTERCO
                         INNER JOIN SETTING_WEEK SW
                            ON (    TO_CHAR (INTERCO.DATERELEASE, 'DD') >=
                                       SW.DATEFROM
                                AND TO_CHAR (INTERCO.DATERELEASE, 'DD') <=
                                       SW.DATEUNTIL
                                AND SW.MONTH =
                                       TO_CHAR (INTERCO.DATERELEASE, 'MM')
                                AND SW.YEAR =
                                       TO_CHAR (INTERCO.DATERELEASE, 'YYYY'))
                        LEFT JOIN
                                 (SELECT b.fccode,
                                         a.curscode,
                                         a.cursyear,
                                         a.cursmonth,
                                         a.rate
                                    FROM (SELECT curscode,
                                                 cursyear,
                                                 cursmonth,
                                                 rate
                                            FROM curs) a
                                         INNER JOIN
                                         (SELECT fccode, currency FROM bank) b
                                            ON a.curscode = b.currency
                                   WHERE a.cursyear = $YEAR AND a.cursmonth = $MONTH)
                                 kurs
                                    ON (    INTERCO.banksource = kurs.fccode
                                        AND TO_NUMBER (
                                               TO_CHAR (INTERCO.daterelease,
                                                        'yyyy')) =
                                               kurs.cursyear
                                        AND TO_NUMBER (
                                               TO_CHAR (INTERCO.daterelease,
                                                        'mm')) = kurs.cursmonth)
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
         seq_financegroup,
         sum(propw1) as propw1,
         sum(propw2) as propw2,
         sum(propw3) as propw3,
         sum(propw4) as propw4,
         sum(propw5) as propw5,
         sum(wactual1) as wactual1,
         sum(wactual2) as wactual2,
         sum(wactual3) as wactual3,
         sum(wactual4) as wactual4,
         sum(wactual5) as wactual5,
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
                 FROM (  SELECT cashflowtype,PRD.cftype,
                        STAGE1.financegroup,
                        STAGE1.seq_financegroup,
                        FCAT.FCNAME AS FORECAST_CATEGORY,
                        NVL (SUM (ROUND(PROPW1/1000000,2)), 0) PROPW1,
                        NVL (SUM (ROUND(PROPW2/1000000,2)), 0) PROPW2,
                        NVL (SUM (ROUND(PROPW3/1000000,2)), 0) PROPW3,
                        NVL (SUM (ROUND(PROPW4/1000000,2)), 0) PROPW4,
                        NVL (SUM (ROUND(PROPW5/1000000,2)), 0) PROPW5,
                        case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual1/1000000,2)),0))*-1
                             else nvl(sum(round(wactual1/1000000,2)),0)
                             end as wactual1,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual2/1000000,2)),0))*-1
                             else nvl(sum(round(wactual2/1000000,2)),0)
                             end as wactual2,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual3/1000000,2)),0))*-1
                             else nvl(sum(round(wactual3/1000000,2)),0)
                             end as wactual3,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual4/1000000,2)),0))*-1
                             else nvl(sum(round(wactual4/1000000,2)),0)
                             end as wactual4,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual5/1000000,2)),0))*-1
                             else nvl(sum(round(wactual5/1000000,2)),0)
                             end as wactual5
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
                             FROM (SELECT cashflowtype,
                                       forecast_category,
                                       department,
                                       groups,
                                       materialgroup,
                                       month,
                                       year,
                                       SUM (propw1) AS propw1,
                                       SUM (propw2) AS propw2,
                                       SUM (propw3) AS propw3,
                                       SUM (propw4) AS propw4,
                                       SUM (propw5) AS propw5,
                                       SUM (wactual1) AS wactual1,
                                       SUM (wactual2) AS wactual2,
                                       SUM (wactual3) AS wactual3,
                                       SUM (wactual4) AS wactual4,
                                       SUM (wactual5) AS wactual5
                                  FROM ( SELECT DISTINCT CASE D.CASHFLOWTYPE
                                             WHEN 1 THEN 'CASH OUT'
                                             ELSE 'CASH IN'
                                          END
                                             AS CASHFLOWTYPE,
                                          DC.FORECAST_CATEGORY,
                                          DC.DEPARTMENT,
                                          '' AS voucherno,
                                          ff.docnumber,
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
                                     FROM (SELECT cf.company,
                                                       CASE
                                                          WHEN cf.doctype IN ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
                                                          THEN
                                                             cf.businessunit
                                                          ELSE
                                                             docs.businessunit
                                                       END
                                                          AS businessunit,
                                                       CASE
                                                          WHEN cf.doctype IN ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
                                                          THEN
                                                             cf.docnumber
                                                          ELSE
                                                             cf.docref
                                                       END
                                                          AS docnumber,
                                                       CASE
                                                          WHEN cf.doctype IN ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
                                                          THEN
                                                             cf.doctype
                                                          ELSE
                                                             docs.doctype
                                                       END
                                                          AS doctype,
                                                       ff.*,
                                                       CASE
                                                          WHEN cf.doctype IN ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
                                                          THEN
                                                             cf.material
                                                          ELSE
                                                             docs.material
                                                       END
                                                          AS material
                                                  FROM (SELECT department,
                                                               year,
                                                               month,
                                                               week,
                                                               cftransid,
                                                                case
                                                                 when (locks > 0 and amountadjs = 0) then amountrequest  
                                                                 when (locks = 0 and amountadjs = 0) then 0    
                                                                 else amountadjs
                                                                 end as amountadjs
                                                          FROM forecast_fix
                                                         WHERE     year = '$YEAR'
                                                               AND month = '$MONTH') ff
                                                       INNER JOIN
                                                       (SELECT DISTINCT
                                                               b.id,
                                                               b.company,
                                                               b.docnumber,
                                                               b.docref,
                                                               b.doctype,
                                                               a.material,
                                                               b.businessunit
                                                          FROM cf_transaction_det a
                                                               RIGHT JOIN
                                                               (SELECT id,
                                                                       company,
                                                                       businessunit,
                                                                       doctype,
                                                                       docnumber,
                                                                       docref
                                                                  FROM cf_transaction)
                                                               b
                                                                  ON (a.id = b.id)
                                                         WHERE b.company LIKE
                                                                  '%$COMPANY%') cf
                                                          ON cf.id = ff.cftransid
                                                       LEFT JOIN
                                                       (SELECT DISTINCT
                                                               b.id,
                                                               b.doctype,
                                                               b.company,
                                                               b.docnumber,
                                                               a.material,
                                                               b.businessunit
                                                          FROM cf_transaction_det a
                                                               INNER JOIN
                                                               (SELECT id,
                                                                       doctype,
                                                                       company,
                                                                       businessunit,
                                                                       docnumber
                                                                  FROM cf_transaction)
                                                               b
                                                                  ON (a.id = b.id)
                                                         WHERE b.company LIKE
                                                                  '%$COMPANY%')
                                                       docs
                                                          ON (    cf.company =
                                                                     docs.company
                                                              AND cf.docref =
                                                                     docs.docnumber))
                                               ff
                                               INNER JOIN
                                               (SELECT material, materialgroup
                                                  FROM material_groupitem) mgi
                                                  ON mgi.material = ff.material
                                               INNER JOIN
                                               (SELECT id, fccode, fcname
                                                  FROM material_group) mg
                                                  ON mg.id = mgi.materialgroup
                                               INNER JOIN
                                               (SELECT forecast_category,
                                                       department,
                                                       fctype,
                                                       materialgroup
                                                  FROM department_category) dc
                                                  ON (    dc.department =
                                                             ff.department
                                                      AND mg.fccode =
                                                             dc.materialgroup)
                                               INNER JOIN
                                               (SELECT id,
                                                       fccode,
                                                       fctype,
                                                       companygroup,
                                                       company_subgroup
                                                  FROM businessunit
                                                 WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                       AND company_subgroup LIKE
                                                              '%$COMPANYSUBGROUP%') bu
                                                  ON (    bu.fctype = dc.fctype
                                                      AND ff.businessunit = bu.id)
                                          INNER JOIN DOCTYPE D ON D.FCCODE = FF.DOCTYPE
                                    WHERE     FF.YEAR = '$YEAR'
                                          AND FF.MONTH = '$MONTH'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND FF.COMPANY LIKE '%$COMPANY%' )
                              GROUP BY cashflowtype,
                                       forecast_category,
                                       department,
                                       groups,
                                       materialgroup,
                                       month,
                                       year
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
                                          INNER JOIN (select fccode,cashflowtype from doctype where fccode in ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')) D ON D.FCCODE = CF.DOCTYPE
                                    WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
                                          AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND CF.COMPANY LIKE '%$COMPANY%'
                                          AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
                                          AND CF.DOCTYPE in ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
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
                                          INNER JOIN (select fccode,cashflowtype from doctype where fccode not in ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')) D ON D.FCCODE = CF.DOCTYPE
                                    WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
                                          AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
                                          AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                          AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                          AND CF.COMPANY LIKE '%$COMPANY%'
                                          AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
                                          AND CF.DOCTYPE not in ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
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
               GROUP BY cashflowtype, PRD.cftype,
                        STAGE1.seq_financegroup,
                        STAGE1.financegroup,
                        FCAT.FCNAME
               ) group by
cftype,
financegroup,
forecast_category ,seq_financegroup order by seq_financegroup ASC");
      // var_dump($this->db->last_query());exit();
      return $query->result();
    }

    public function ShowDatann($param){
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
       sum(propw1) as propw1,
         sum(propw2) as propw2,
         sum(propw3) as propw3,
         sum(propw4) as propw4,
         sum(propw5) as propw5,
         sum(wactual1) as wactual1,
         sum(wactual2) as wactual2,
         sum(wactual3) as wactual3,
         sum(wactual4) as wactual4,
         sum(wactual5) as wactual5,
         case cftype
             when 'CASH OUT' then sum(propw1)-sum(wactual1)
             else sum(wactual1)-sum(propw1)
             end as wavar1,
         case cftype
             when 'CASH OUT' then sum(propw2)-sum(wactual2)
             else sum(wactual2)-sum(propw2)
             end as wavar2,
         case cftype
             when 'CASH OUT' then sum(propw3)-sum(wactual3)
             else sum(wactual3)-sum(propw3)
             end
             as wavar3,
         case cftype
             when 'CASH OUT' then sum(propw4)-sum(wactual4)
             else sum(wactual4)-sum(propw4)
             end as wavar4,
         case cftype
             when 'CASH OUT' then sum(propw5)-sum(wactual5)
             else sum(wactual5)-sum(propw5)
             end as wavar5
                 FROM (  SELECT cashflowtype, PRD.cftype,
                        STAGE1.financegroup,
                        FCAT.FCNAME AS FORECAST_CATEGORY,
                        nvl(sum(round(propw1/1000000,2)),0) propw1,
                         nvl(sum(round(propw2/1000000,2)),0) propw2,
                         nvl(sum(round(propw3/1000000,2)),0) propw3,
                         nvl(sum(round(propw4/1000000,2)),0) propw4,
                         nvl(sum(round(propw5/1000000,2)),0) propw5,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual1/1000000,2)),0))*-1
                             else nvl(sum(round(wactual1/1000000,2)),0)
                             end as wactual1,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual2/1000000,2)),0))*-1
                             else nvl(sum(round(wactual2/1000000,2)),0)
                             end as wactual2,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual3/1000000,2)),0))*-1
                             else nvl(sum(round(wactual3/1000000,2)),0)
                             end as wactual3,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual4/1000000,2)),0))*-1
                             else nvl(sum(round(wactual4/1000000,2)),0)
                             end as wactual4,
                         case
                             when cashflowtype = 'CASH IN' and cftype = 'CASH OUT'
                             then (nvl(sum(round(wactual5/1000000,2)),0))*-1
                             else nvl(sum(round(wactual5/1000000,2)),0)
                             end as wactual5
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
                                          INNER JOIN (select fccode,cashflowtype from doctype where fccode in ('INV_AP_SPC','PDO','INV_AR_SPC')) D ON D.FCCODE = CF.DOCTYPE
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
                                          INNER JOIN (select fccode,cashflowtype from doctype where fccode not in ('INV_AP_SPC','PDO','INV_AR_SPC')) D ON D.FCCODE = CF.DOCTYPE
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
               GROUP BY cashflowtype,
                        PRD.cftype,
                        STAGE1.seq_financegroup,
                        STAGE1.financegroup,
                        FCAT.FCNAME
               ) group by cashflowtype,
cftype,
financegroup,
forecast_category ORDER BY CFTYPE, forecast_category");
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

    public function getSurplus($param){
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
                         ((ACTUAL1 - PROP1) + (ACTUAL2 - PROP2) + (ACTUAL3 - PROP3) + (ACTUAL4 - PROP4) + (ACTUAL5 - PROP5) ) totalVar from (SELECT SUM (CASE cftype WHEN 'CASH OUT' THEN PROP1 * -1 ELSE PROP1 END)
                PROP1,
             SUM (CASE cftype WHEN 'CASH OUT' THEN PROP2 * -1 ELSE PROP2 END)
                PROP2,
             SUM (CASE cftype WHEN 'CASH OUT' THEN PROP3 * -1 ELSE PROP3 END)
                PROP3,
             SUM (CASE cftype WHEN 'CASH OUT' THEN PROP4 * -1 ELSE PROP4 END)
                PROP4,
             SUM (CASE cftype WHEN 'CASH OUT' THEN PROP5 * -1 ELSE PROP5 END)
                PROP5,
             SUM (CASE cftype WHEN 'CASH OUT' THEN ACTUAL1 * -1 ELSE ACTUAL1 END)
                ACTUAL1,
             SUM (CASE cftype WHEN 'CASH OUT' THEN ACTUAL2 * -1 ELSE ACTUAL2 END)
                ACTUAL2,
             SUM (CASE cftype WHEN 'CASH OUT' THEN ACTUAL3 * -1 ELSE ACTUAL3 END)
                ACTUAL3,
             SUM (CASE cftype WHEN 'CASH OUT' THEN ACTUAL4 * -1 ELSE ACTUAL4 END)
                ACTUAL4,
             SUM (CASE cftype WHEN 'CASH OUT' THEN ACTUAL5 * -1 ELSE ACTUAL5 END)
                ACTUAL5,
             SUM (ACTUAL1 + ACTUAL2 + ACTUAL3 + ACTUAL4 + ACTUAL5) totalAct,
             SUM (ACTUAL1 - PROP1) AS VAR1,
             SUM (ACTUAL2 - PROP2) AS VAR2,
             SUM (ACTUAL3 - PROP3) AS VAR3,
             SUM (ACTUAL4 - PROP4) AS VAR4,
             SUM (ACTUAL5 - PROP5) AS VAR5,
             SUM (VAR1 + VAR2 + VAR3 + VAR4 + VAR5) totalVar
                    FROM (SELECT CFTYPE,PROP1,
                     PROP2,
                     PROP3,
                     PROP4,
                     PROP5,
                     (PROP1 + PROP2 + PROP3 + PROP4 + PROP5) totalProp,
                     ACTUAL1,
                     ACTUAL2,
                     ACTUAL3,
                     ACTUAL4,
                     ACTUAL5,
                     (ACTUAL1 + ACTUAL2 + ACTUAL3 + ACTUAL4 + ACTUAL5) totalAct,
                     ACTUAL1 - PROP1 AS VAR1,
                     ACTUAL2 - PROP2 AS VAR2,
                     ACTUAL3 - PROP3 AS VAR3,
                     ACTUAL4 - PROP4 AS VAR4,
                     ACTUAL5 - PROP5 AS VAR5,
                     (VAR1 + VAR2 + VAR3 + VAR4 + VAR5) totalVar
                            FROM ( SELECT CFTYPE, FINANCEGROUP,
                            FORECAST_CATEGORY,
                            SUM (propw1) AS prop1,
                               SUM (propw2) AS prop2,
                               SUM (propw3) AS prop3,
                               SUM (propw4) AS prop4,
                               SUM (propw5) AS prop5,
                               SUM (wactual1) AS actual1,
                               SUM (wactual2) AS actual2,
                               SUM (wactual3) AS actual3,
                               SUM (wactual4) AS actual4,
                               SUM (wactual5) AS actual5,
                               CASE cftype
                                  WHEN 'CASH OUT' THEN SUM (propw1) - SUM (wactual1)
                                  ELSE SUM (wactual1) - SUM (propw1)
                               END
                                  AS var1,
                               CASE cftype
                                  WHEN 'CASH OUT' THEN SUM (propw2) - SUM (wactual2)
                                  ELSE SUM (wactual2) - SUM (propw2)
                               END
                                  AS var2,
                               CASE cftype
                                  WHEN 'CASH OUT' THEN SUM (propw3) - SUM (wactual3)
                                  ELSE SUM (wactual3) - SUM (propw3)
                               END
                                  AS var3,
                               CASE cftype
                                  WHEN 'CASH OUT' THEN SUM (propw4) - SUM (wactual4)
                                  ELSE SUM (wactual4) - SUM (propw4)
                               END
                                  AS var4,
                               CASE cftype
                                  WHEN 'CASH OUT' THEN SUM (propw5) - SUM (wactual5)
                                  ELSE SUM (wactual5) - SUM (propw5)
                               END
                                  AS var5
                                      FROM (  SELECT cashflowtype, PRD.cftype,
                                             STAGE1.financegroup,
                                             FCAT.FCNAME AS FORECAST_CATEGORY,
                                             NVL (SUM (ROUND (propw1 / 1000000, 2)), 0)
                                                  propw1,
                                               NVL (SUM (ROUND (propw2 / 1000000, 2)), 0)
                                                  propw2,
                                               NVL (SUM (ROUND (propw3 / 1000000, 2)), 0)
                                                  propw3,
                                               NVL (SUM (ROUND (propw4 / 1000000, 2)), 0)
                                                  propw4,
                                               NVL (SUM (ROUND (propw5 / 1000000, 2)), 0)
                                                  propw5,
                                               CASE
                                                  WHEN      cashflowtype = 'CASH IN'
                                                       AND PRD.cftype = 'CASH OUT'
                                                  THEN
                                                       (NVL (
                                                           SUM (
                                                              ROUND (wactual1 / 1000000, 2)),
                                                           0))
                                                     * -1
                                                  ELSE
                                                     NVL (
                                                        SUM (ROUND (wactual1 / 1000000, 2)),
                                                        0)
                                               END
                                                  AS wactual1,
                                               CASE
                                                  WHEN     cashflowtype = 'CASH IN'
                                                       AND PRD.cftype = 'CASH OUT'
                                                  THEN
                                                       (NVL (
                                                           SUM (
                                                              ROUND (wactual2 / 1000000, 2)),
                                                           0))
                                                     * -1
                                                  ELSE
                                                     NVL (
                                                        SUM (ROUND (wactual2 / 1000000, 2)),
                                                        0)
                                               END
                                                  AS wactual2,
                                               CASE
                                                  WHEN     cashflowtype = 'CASH IN'
                                                       AND PRD.cftype = 'CASH OUT'
                                                  THEN
                                                       (NVL (
                                                           SUM (
                                                              ROUND (wactual3 / 1000000, 2)),
                                                           0))
                                                     * -1
                                                  ELSE
                                                     NVL (
                                                        SUM (ROUND (wactual3 / 1000000, 2)),
                                                        0)
                                               END
                                                  AS wactual3,
                                               CASE
                                                  WHEN     cashflowtype = 'CASH IN'
                                                       AND PRD.cftype = 'CASH OUT'
                                                  THEN
                                                       (NVL (
                                                           SUM (
                                                              ROUND (wactual4 / 1000000, 2)),
                                                           0))
                                                     * -1
                                                  ELSE
                                                     NVL (
                                                        SUM (ROUND (wactual4 / 1000000, 2)),
                                                        0)
                                               END
                                                  AS wactual4,
                                               CASE
                                                  WHEN     cashflowtype = 'CASH IN'
                                                       AND PRD.cftype = 'CASH OUT'
                                                  THEN
                                                       (NVL (
                                                           SUM (
                                                              ROUND (wactual5 / 1000000, 2)),
                                                           0))
                                                     * -1
                                                  ELSE
                                                     NVL (
                                                        SUM (ROUND (wactual5 / 1000000, 2)),
                                                        0)
                                               END
                                                  AS wactual5
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
                                             INNER JOIN (SELECT FCCODE,FCNAME
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
                                   FROM (SELECT cashflowtype,
                                       forecast_category,
                                       department,
                                       groups,
                                       materialgroup,
                                       month,
                                       year,
                                       SUM (propw1) AS propw1,
                                       SUM (propw2) AS propw2,
                                       SUM (propw3) AS propw3,
                                       SUM (propw4) AS propw4,
                                       SUM (propw5) AS propw5,
                                       SUM (wactual1) AS wactual1,
                                       SUM (wactual2) AS wactual2,
                                       SUM (wactual3) AS wactual3,
                                       SUM (wactual4) AS wactual4,
                                       SUM (wactual5) AS wactual5
                                  FROM (SELECT DISTINCT
                                               CASE d.cashflowtype
                                                  WHEN 1 THEN 'CASH OUT'
                                                  ELSE 'CASH IN'
                                               END
                                                  AS cashflowtype,
                                               dc.forecast_category,
                                               dc.department,
                                               '' AS voucherno,
                                               ff.docnumber,
                                               mg.fcname AS groups,
                                               mg.fccode materialgroup,
                                               ff.month,
                                               ff.year,
                                               CASE
                                                  WHEN ff.week = 'W1' THEN amountadjs
                                                  ELSE 0
                                               END
                                                  AS propw1,
                                               CASE
                                                  WHEN ff.week = 'W2' THEN amountadjs
                                                  ELSE 0
                                               END
                                                  AS propw2,
                                               CASE
                                                  WHEN ff.week = 'W3' THEN amountadjs
                                                  ELSE 0
                                               END
                                                  AS propw3,
                                               CASE
                                                  WHEN ff.week = 'W4' THEN amountadjs
                                                  ELSE 0
                                               END
                                                  AS propw4,
                                               CASE
                                                  WHEN ff.week = 'W5' THEN amountadjs
                                                  ELSE 0
                                               END
                                                  AS propw5,
                                               0 wactual1,
                                               0 wactual2,
                                               0 wactual3,
                                               0 wactual4,
                                               0 wactual5
                                          FROM (SELECT cf.company,
                                                       CASE
                                                          WHEN cf.doctype IN ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
                                                          THEN
                                                             cf.businessunit
                                                          ELSE
                                                             docs.businessunit
                                                       END
                                                          AS businessunit,
                                                       CASE
                                                          WHEN cf.doctype IN ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
                                                          THEN
                                                             cf.docnumber
                                                          ELSE
                                                             cf.docref
                                                       END
                                                          AS docnumber,
                                                       CASE
                                                          WHEN cf.doctype IN ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
                                                          THEN
                                                             cf.doctype
                                                          ELSE
                                                             docs.doctype
                                                       END
                                                          AS doctype,
                                                       ff.*,
                                                       CASE
                                                          WHEN cf.doctype IN ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
                                                          THEN
                                                             cf.material
                                                          ELSE
                                                             docs.material
                                                       END
                                                          AS material
                                                  FROM (SELECT department,
                                                               year,
                                                               month,
                                                               week,
                                                               cftransid,
                                                                case
                                                                 when (locks > 0 and amountadjs = 0) then amountrequest  
                                                                 when (locks = 0 and amountadjs = 0) then 0  
                                                                 else amountadjs
                                                                 end as amountadjs
                                                          FROM forecast_fix
                                                         WHERE     year = '$YEAR'
                                                               AND month = '$MONTH') ff
                                                       INNER JOIN
                                                       (SELECT DISTINCT
                                                               b.id,
                                                               b.company,
                                                               b.docnumber,
                                                               b.docref,
                                                               b.doctype,
                                                               a.material,
                                                               b.businessunit
                                                          FROM cf_transaction_det a
                                                               RIGHT JOIN
                                                               (SELECT id,
                                                                       company,
                                                                       businessunit,
                                                                       doctype,
                                                                       docnumber,
                                                                       docref
                                                                  FROM cf_transaction)
                                                               b
                                                                  ON (a.id = b.id)
                                                         WHERE b.company LIKE '%$COMPANY%') cf
                                                          ON cf.id = ff.cftransid
                                                       LEFT JOIN
                                                       (SELECT DISTINCT
                                                               b.id,
                                                               b.doctype,
                                                               b.company,
                                                               b.docnumber,
                                                               a.material,
                                                               b.businessunit
                                                          FROM cf_transaction_det a
                                                               INNER JOIN
                                                               (SELECT id,
                                                                       doctype,
                                                                       company,
                                                                       businessunit,
                                                                       docnumber
                                                                  FROM cf_transaction)
                                                               b
                                                                  ON (a.id = b.id)
                                                         WHERE b.company LIKE '%$COMPANY%')
                                                       docs
                                                          ON (    cf.company = docs.company
                                                              AND cf.docref = docs.docnumber)) ff
                                               INNER JOIN
                                               (SELECT material, materialgroup
                                                  FROM material_groupitem) mgi
                                                  ON mgi.material = ff.material
                                               INNER JOIN
                                               (SELECT id, fccode, fcname
                                                  FROM material_group) mg
                                                  ON mg.id = mgi.materialgroup
                                               INNER JOIN
                                               (SELECT forecast_category,
                                                       department,
                                                       fctype,
                                                       materialgroup
                                                  FROM department_category) dc
                                                  ON (    dc.department =
                                                             ff.department
                                                      AND mg.fccode =
                                                             dc.materialgroup)
                                               INNER JOIN
                                               (SELECT id,
                                                       fccode,
                                                       fctype,
                                                       companygroup,
                                                       company_subgroup
                                                  FROM businessunit
                                                 WHERE     companygroup LIKE '%$COMPANYGROUP%'
                                                       AND company_subgroup LIKE '%$COMPANYSUBGROUP%') bu
                                                  ON (    bu.fctype = dc.fctype
                                                      AND ff.businessunit = bu.id)
                                               INNER JOIN doctype d
                                                  ON d.fccode = ff.doctype
                                         WHERE     ff.year = '$YEAR'
                                               AND ff.month = '$MONTH'
                                               AND bu.companygroup LIKE '%$COMPANYGROUP%'
                                               AND bu.company_subgroup LIKE '%$COMPANYSUBGROUP%'
                                               AND ff.company LIKE '%$COMPANY%'
                                               )
                                                GROUP BY cashflowtype,
                                                         forecast_category,
                                                         department,
                                                         groups,
                                                         materialgroup,
                                                         month,
                                                         year
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
                                                                                'Y')
                                                                     ba
                                                                        ON (p.bankcode =
                                                                               ba.fccode)
                                                               WHERE     TO_CHAR (
                                                                            p.daterelease,
                                                                            'MM') =
                                                                            '$MONTH2'
                                                                     AND TO_CHAR (
                                                                            p.daterelease,
                                                                            'YYYY') =
                                                                            '$YEAR') PAY
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
                                                INNER JOIN (select fccode,cashflowtype from doctype where fccode in ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')) D ON D.FCCODE = CF.DOCTYPE
                                          WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
                                                AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
                                                AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                                AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                                AND CF.COMPANY LIKE '%$COMPANY%'
                                                AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
                                                AND CF.DOCTYPE in ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
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
                                                   FROM (SELECT p.paymentid,
                                                                     p.voucherno,
                                                                     p.daterelease,
                                                                     p.amountbank
                                                                        AS amount,
                                                                     p.cftransid
                                                                FROM payment p
                                                                     INNER JOIN
                                                                     (SELECT fccode
                                                                        FROM bank
                                                                       WHERE isuseformonthlyforecast =
                                                                                'Y')
                                                                     ba
                                                                        ON (p.bankcode =
                                                                               ba.fccode)
                                                               WHERE     TO_CHAR (
                                                                            p.daterelease,
                                                                            'MM') =
                                                                            '$MONTH2'
                                                                     AND TO_CHAR (
                                                                            p.daterelease,
                                                                            'YYYY') =
                                                                            '$YEAR'
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
                                                INNER JOIN (select fccode,cashflowtype from doctype where fccode not in ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')) D ON D.FCCODE = CF.DOCTYPE
                                          WHERE     TO_NUMBER(TO_CHAR (P.DATERELEASE, 'MM')) = '$MONTH2'
                                                AND TO_NUMBER(TO_CHAR (P.DATERELEASE, 'YYYY')) = '$YEAR'
                                                AND BU.COMPANYGROUP LIKE '%$COMPANYGROUP%'
                                                AND BU.COMPANY_SUBGROUP LIKE '%$COMPANYSUBGROUP%'
                                                AND CF.COMPANY LIKE '%$COMPANY%'
                                                AND CF.DOCNUMBER NOT LIKE '%TMPINV%'
                                                AND CF.DOCTYPE not in ('INV_AP_SPC', 'PDO', 'INV_AR_SPC','LEASING','LOAN')
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
                                                           inner join bank ba on (poth.bankcode = ba.fccode and poth.company = ba.company)                        
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
                                    GROUP BY cashflowtype,
                                             PRD.cftype,
                                             STAGE1.seq_financegroup,
                                             STAGE1.financegroup,
                                             FCAT.FCNAME
                                    ORDER BY CFTYPE ) 
                      GROUP BY cftype, financegroup, forecast_category)
                           WHERE FINANCEGROUP NOT IN ('OUT', 'IN')))");
         // var_dump($this->db->last_query());exit();
         return $query->row();
    }


    public function ShowDataNew($param) {
        $MONTH = $param['MONTH'];
        $YEAR = $param['YEAR'];
        if($MONTH < 10){
            $MONTH2 = "0".$MONTH;
        }else{
            $MONTH2 = $MONTH;
        }
        $query = $this->db->query("SELECT PRD.cftype,
         STAGE1.financegroup,
         STAGE1.forecast_category,
         NVL (SUM (PROPW1), 0) PROPW1,
         NVL (SUM (PROPW2), 0) PROPW2,
         NVL (SUM (PROPW3), 0) PROPW3,
         NVL (SUM (PROPW4), 0) PROPW4,
         NVL (SUM (PROPW5), 0) PROPW5,
         NVL (SUM (WACTUAL1), 0) WACTUAL1,
         NVL (SUM (WACTUAL2), 0) WACTUAL2,
         NVL (SUM (WACTUAL3), 0) WACTUAL3,
         NVL (SUM (WACTUAL4), 0) WACTUAL4,
         NVL (SUM (WACTUAL5), 0) WACTUAL5,
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
                      FROM FORECAST_FIX FF
                           INNER JOIN CF_TRANSACTION CF ON CF.ID = FF.CFTRANSID
                           INNER JOIN CF_TRANSACTION_DET CFTD ON CFTD.ID = CF.ID
                           INNER JOIN MATERIAL_GROUPITEM MGI
                              ON MGI.MATERIAL = CFTD.MATERIAL
                           INNER JOIN MATERIAL_GROUP MG
                              ON MG.ID = MGI.MATERIALGROUP
                           INNER JOIN DEPARTMENT_CATEGORY DC
                              ON (    DC.DEPARTMENT = CF.DEPARTMENT
                                  AND MG.FCCODE = DC.MATERIALGROUP)
                           INNER JOIN BUSINESSUNIT BU ON BU.FCTYPE = DC.FCTYPE
                           INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                     WHERE FF.YEAR = '$YEAR' AND FF.MONTH = '$MONTH' AND BU.COMPANYGROUP = 'PLT'
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
                           TO_CHAR (P.DATERELEASE, 'MM') MONTH,
                           TO_CHAR (P.DATERELEASE, 'YYYY') YEAR,
                           0 PROPW1,
                           0 PROPW2,
                           0 PROPW3,
                           0 PROPW4,
                           0 PROPW5,
                           CASE
                              WHEN TO_CHAR (P.DATERELEASE, 'W') = 1 THEN AMOUNT
                              ELSE 0
                           END
                              AS WACTUAL1,
                           CASE
                              WHEN TO_CHAR (P.DATERELEASE, 'W') = 2 THEN AMOUNT
                              ELSE 0
                           END
                              AS WACTUAL2,
                           CASE
                              WHEN TO_CHAR (P.DATERELEASE, 'W') = 3 THEN AMOUNT
                              ELSE 0
                           END
                              AS WACTUAL3,
                           CASE
                              WHEN TO_CHAR (P.DATERELEASE, 'W') = 4 THEN AMOUNT
                              ELSE 0
                           END
                              AS WACTUAL4,
                           CASE
                              WHEN TO_CHAR (P.DATERELEASE, 'W') = 5 THEN AMOUNT
                              ELSE 0
                           END
                              AS WACTUAL5
                      FROM (SELECT PAY.*, SW.*
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
                           INNER JOIN CF_TRANSACTION CF ON CF.ID = P.CFTRANSID
                           INNER JOIN CF_TRANSACTION_DET CFTD ON CFTD.ID = CF.ID
                           INNER JOIN MATERIAL_GROUPITEM MGI
                              ON MGI.MATERIAL = CFTD.MATERIAL
                           INNER JOIN MATERIAL_GROUP MG
                              ON MG.ID = MGI.MATERIALGROUP
                           INNER JOIN DEPARTMENT_CATEGORY DC
                              ON (    DC.DEPARTMENT = CF.DEPARTMENT
                                  AND MG.FCCODE = DC.MATERIALGROUP)
                           INNER JOIN BUSINESSUNIT BU ON BU.FCTYPE = DC.FCTYPE
                           INNER JOIN DOCTYPE D ON D.FCCODE = CF.DOCTYPE
                     WHERE     TO_CHAR (P.DATERELEASE, 'MM') = '$MONTH2'
                           AND TO_CHAR (P.DATERELEASE, 'YYYY') = '$YEAR' AND BU.COMPANYGROUP = 'PLT')
          GROUP BY CASHFLOWTYPE,
                   FORECAST_CATEGORY,
                   GROUPS,
                   MATERIALGROUP) result1
            ON (    stage1.materialgroup = result1.materialgroup
                AND stage1.FORECAST_CATEGORY = result1.FORECAST_CATEGORY)
      GROUP BY PRD.cftype,
         STAGE1.seq_financegroup,
         STAGE1.financegroup,
         STAGE1.forecast_category
         ORDER BY CFTYPE");
        
        // var_dump($this->db->last_query());exit();
        return $query->result();
    }

    public function ShowDataOri($TYPE) {
				$query = $this->db->query("select * from (
select 1  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'CPO' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 2  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'PK' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 3  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'PKS' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 4  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'FFB' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 5  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'OLEIN' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 6  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'Stearin' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 7  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'PFAD' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 8  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'CPKO/PKO/PKE' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 9  NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'Sugar' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 10 NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'Biodiesel' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 11 NOX, 'CASH IN' DOCTYPENAME, 'Sales Revenue ' CATEGORY, 'Others ( MiKo, Scrap, etc)' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 12 NOX, 'CASH IN' DOCTYPENAME, 'Funding ' CATEGORY, 'Bank Loan' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 13 NOX, 'CASH IN' DOCTYPENAME, 'Funding ' CATEGORY, 'Shareholder Loan / Interco' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 14 NOX, 'CASH IN' DOCTYPENAME, 'Others ' CATEGORY, 'VAT Refunds' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 15 NOX, 'CASH IN' DOCTYPENAME, 'Others ' CATEGORY, 'Forex Gains' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all 
select 16 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'Mobilization and Delivery' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 17 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'Levy ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 18 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'FFB Purchase ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 19 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'Plasma ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 20 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'CPO/Olein Underperformance ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 21 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'CPO/Olein Purchase ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 22 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'CPO : EUP Interco ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 23 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'PK Purchase ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 24 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'Sugar Purchase ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 25 NOX, 'CASH OUT' DOCTYPENAME, 'Marketing ' CATEGORY, 'PKS Purchase ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 26 NOX, 'CASH OUT' DOCTYPENAME, 'Financial Expenses ' CATEGORY, 'Bank Repayment - Term Loan ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 27 NOX, 'CASH OUT' DOCTYPENAME, 'Financial Expenses ' CATEGORY, 'Bank Repayment : Working Capital ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 28 NOX, 'CASH OUT' DOCTYPENAME, 'Financial Expenses ' CATEGORY, 'Bank Interest' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 29 NOX, 'CASH OUT' DOCTYPENAME, 'Financial Expenses ' CATEGORY, 'Capex Leasing' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 30 NOX, 'CASH OUT' DOCTYPENAME, 'Financial Expenses ' CATEGORY, 'Others' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 31 NOX, 'CASH OUT' DOCTYPENAME, 'Benefit and Compensation ' CATEGORY, 'Estate and RO' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 32 NOX, 'CASH OUT' DOCTYPENAME, 'Benefit and Compensation ' CATEGORY, 'POM' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 33 NOX, 'CASH OUT' DOCTYPENAME, 'Benefit and Compensation ' CATEGORY, 'HO (Incl. APB) ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 34 NOX, 'CASH OUT' DOCTYPENAME, 'Cash Expenses' CATEGORY, 'Estate and RO' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 35 NOX, 'CASH OUT' DOCTYPENAME, 'Cash Expenses' CATEGORY, 'POM' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 36 NOX, 'CASH OUT' DOCTYPENAME, 'Estate - Contractor' CATEGORY, 'Capex/Contractor by HO' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 37 NOX, 'CASH OUT' DOCTYPENAME, 'Estate - Contractor' CATEGORY, 'Civil Engineering ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 38 NOX, 'CASH OUT' DOCTYPENAME, 'Estate - Contractor' CATEGORY, 'Local contrator + supplier' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 39 NOX, 'CASH OUT' DOCTYPENAME, 'Estate - Contractor' CATEGORY, 'Others' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 40 NOX, 'CASH OUT' DOCTYPENAME, 'RO - Contractor' CATEGORY, 'Capex/Contractor by HO' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 41 NOX, 'CASH OUT' DOCTYPENAME, 'RO - Contractor' CATEGORY, 'Civil Engineering ' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 42 NOX, 'CASH OUT' DOCTYPENAME, 'RO - Contractor' CATEGORY, 'Local contrator + supplier' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 43 NOX, 'CASH OUT' DOCTYPENAME, 'RO - Contractor' CATEGORY, 'Others' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 44 NOX, 'CASH OUT' DOCTYPENAME, 'Estate - Purchasing' CATEGORY, 'Fertilizer and Agrochemical' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 45 NOX, 'CASH OUT' DOCTYPENAME, 'Estate - Purchasing' CATEGORY, 'Fuel and Lubricant' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 46 NOX, 'CASH OUT' DOCTYPENAME, 'Estate - Purchasing' CATEGORY, 'Logistic Transport' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 47 NOX, 'CASH OUT' DOCTYPENAME, 'Estate - Purchasing' CATEGORY, 'Others (Capex, SPart, Consumable Estate)' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 48 NOX, 'CASH OUT' DOCTYPENAME, 'POM' CATEGORY, 'Contractor and Supplier (all project)' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 49 NOX, 'CASH OUT' DOCTYPENAME, 'POM' CATEGORY, 'R and M + Consumable' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 50 NOX, 'CASH OUT' DOCTYPENAME, 'HRD and POD' CATEGORY, 'HRD (GA)' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 51 NOX, 'CASH OUT' DOCTYPENAME, 'HRD and POD' CATEGORY, 'POD' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 52 NOX, 'CASH OUT' DOCTYPENAME, 'SSL' CATEGORY, 'GRTT' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 53 NOX, 'CASH OUT' DOCTYPENAME, 'SSL' CATEGORY, 'Legal License' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 54 NOX, 'CASH OUT' DOCTYPENAME, 'SSL' CATEGORY, 'PP' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 55 NOX, 'CASH OUT' DOCTYPENAME, 'Others' CATEGORY, 'Tax (VAT, WHT, CIT, etc.)' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 56 NOX, 'CASH OUT' DOCTYPENAME, 'Others' CATEGORY, 'Professional Fees' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 57 NOX, 'CASH OUT' DOCTYPENAME, 'Others' CATEGORY, 'Forex Loses' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 58 NOX, 'CASH OUT' DOCTYPENAME, 'Others' CATEGORY, 'Others (Off Rental, etc.)' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 59 NOX, 'CASH OUT' DOCTYPENAME, 'Others' CATEGORY, 'Devidend' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 60 NOX, 'CASH OUT' DOCTYPENAME, 'Interco' CATEGORY, 'In' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual union all
select 61 NOX, 'CASH OUT' DOCTYPENAME, 'Interco' CATEGORY, 'Out' GROUPS, 2021 YEAR,  02 MONTH, 0 PROPW1, 0 PROPW2, 0 PROPW3, 0 PROPW4,0 PROPW5, 0 WACTUAL1, 0 WACTUAL2, 0 WACTUAL3, 0 WACTUAL4, 0 WACTUAL5, 0 WAVAR1, 0 WAVAR2, 0 WAVAR3, 0 WAVAR4, 0 WAVAR5 from dual
) ORDER BY NOX ASC");
/*
		$query = $this->db->query(" Select
									DOCTYPENAME,
									FCNAME CATEGORY,
									INITCAP(groups)groups,
									YEAR, MONTH,
									SUM(FORC_AMT_W1)PROPW1,
									SUM(FORC_AMT_W2)PROPW2,
									SUM(FORC_AMT_W3)PROPW3,
									SUM(FORC_AMT_W4)PROPW4,
									SUM(FORC_AMT_W5)PROPW5,
									0 WACTUAL1,
									0 WACTUAL2,
									0 WACTUAL3,
									0 WACTUAL4,
									0 WACTUAL5,
									0 WAVAR1,
									0 WAVAR2,
									0 WAVAR3,
									0 WAVAR4,
									0 WAVAR5
									from (
									Select
									DECODE (DOCTYPE.CASHFLOWTYPE,0,'CASH IN','CASH OUT')DOCTYPENAME,
									foregroup.FCNAME,
									matgroup.fcname as groups,
									DEPARTMENT, W4.DOCTYPE, DOCREF, YEAR, MONTH,
									W1, W2, W3, W4, W5,
									W4.MATERIAL,
									ITEM_AMOUNT_W4 - FORC_AMT_W4 ITEM_AMOUNT_W5,
									SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W5,
									DECODE(SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,(ITEM_AMOUNT_W4 - FORC_AMT_W4) / SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100) PERCENT_W5,
									FORC_AMT_W1,
									FORC_AMT_W2,
									FORC_AMT_W3,
									FORC_AMT_W4,
									DECODE(SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W4 - FORC_AMT_W4) / SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W4) FORC_AMT_W5
									from (
									Select
									DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
									W1, W2, W3, W4, W5,
									MATERIAL,
									ITEM_AMOUNT_W3 - FORC_AMT_W3 ITEM_AMOUNT_W4,
									SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W4,
									(ITEM_AMOUNT_W3 - FORC_AMT_W3) / SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W4,
									FORC_AMT_W1,
									FORC_AMT_W2,
									FORC_AMT_W3,
									DECODE(SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W3 - FORC_AMT_W3) / SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W4) FORC_AMT_W4
									from (
									Select
									DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
									W1, W2, W3, W4, W5,
									MATERIAL,
									ITEM_AMOUNT_W2 - FORC_AMT_W2 ITEM_AMOUNT_W3,
									SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W3,
									(ITEM_AMOUNT_W2 - FORC_AMT_W2) / SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W3,
									FORC_AMT_W1,
									FORC_AMT_W2,
									DECODE((SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100),0,0,((ITEM_AMOUNT_W2 - FORC_AMT_W2) / SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W3) FORC_AMT_W3
									from (
									Select
									DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
									W1, W2, W3, W4, W5,
									MATERIAL,
									ITEM_AMOUNT_W1 - FORC_AMT_W1 ITEM_AMOUNT_W2,
									SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W2,
									(ITEM_AMOUNT_W1 - FORC_AMT_W1) / SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W2,
									FORC_AMT_W1,
									DECODE(SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W1 - FORC_AMT_W1) / SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W2) FORC_AMT_W2
									from
									(
									Select
									DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
									W1, W2, W3, W4, W5,
									NOTINVDET.MATERIAL,
									NOTINVDET.AMOUNT_INCLUDE_VAT ITEM_AMOUNT_W1,
									SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W1,
									NOTINVDET.AMOUNT_INCLUDE_VAT / SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W1,
									DECODE(SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,(NOTINVDET.AMOUNT_INCLUDE_VAT / SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100)/100*W1) FORC_AMT_W1
									from
									(
									select 
									FX.DEPARTMENT,
									NVL(NOTINV.ID,INV.ID) NOTINVID,
									NVL(NOTINV.DOCTYPE,INV.DOCTYPE) DOCTYPE,
									INV.DOCREF,
									FX.YEAR,
									FX.MONTH,
									SUM(DECODE(FX.WEEK,'W1',FX.AMOUNTADJS,0)) W1,
									SUM(DECODE(FX.WEEK,'W2',FX.AMOUNTADJS,0)) W2,
									SUM(DECODE(FX.WEEK,'W3',FX.AMOUNTADJS,0)) W3,
									SUM(DECODE(FX.WEEK,'W4',FX.AMOUNTADJS,0)) W4,
									SUM(DECODE(FX.WEEK,'W5',FX.AMOUNTADJS,0)) W5
									from 
									FORECAST_FIX FX
									INNER JOIN CF_TRANSACTION INV ON FX.CFTRANSID = INV.ID
									LEFT JOIN CF_TRANSACTION NOTINV ON INV.DOCREF = NOTINV.DOCNUMBER and NOTINV.DOCTYPE !='INV'
									Where FX.YEAR = 2020 and FX.MONTH = 5
									GROUP BY FX.DEPARTMENT, NVL(NOTINV.ID,INV.ID), NVL(NOTINV.DOCTYPE,INV.DOCTYPE), INV.DOCREF, FX.YEAR, FX.MONTH
									) HDR01
									INNER JOIN CF_TRANSACTION_DET NOTINVDET ON HDR01.NOTINVID = NOTINVDET.ID
									)W1
									)W2
									)W3
									)W4
									inner join material_groupitem mat on W4.material = mat.material
									inner join material_group matgroup on mat.materialgroup = matgroup.id
									inner join forecast_category foregroup on matgroup.forecast_category = foregroup.fccode
									inner join doctype on W4.doctype = doctype.fccode
									) Finalx
									GROUP BY DOCTYPENAME, FCNAME, groups, YEAR, MONTH
									ORDER BY DOCTYPENAME, FCNAME, groups, YEAR, MONTH");
									*/
									
		return $query->result();
    }
}