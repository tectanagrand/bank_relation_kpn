<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-12-27 01:00:01 --> Severity: Notice --> Undefined index: HTTP_HOST /var/www/cashflow/application/config/config.php 26
ERROR - 2022-12-27 01:00:01 --> Severity: Warning --> oci_execute(): ORA-01789: query block has incorrect number of result columns /var/www/cashflow/system/database/drivers/oci8/oci8_driver.php 286
ERROR - 2022-12-27 01:00:01 --> Query error: ORA-01789: query block has incorrect number of result columns - Invalid query: SELECT a.DUEDATE,
                     a.DOCNUMBER,
                     C.COMPANYCODE,
                     S.FCNAME,
                     B.FCNAME AS BUNAME,
                     M.FCNAME AS ITEM_NAME,
                     L.TRANSACTIONMETHOD_BY,
                     L.TOTAL_MONTH,
                     L.INTEREST_PERCENTAGE,
                     L.DENDA_PERCENTAGE,
                     L.AMOUNT_AFTER_CONV,
                     a.DATERELEASE,
                     a.lebih_hari,
                     a.monthtobe,
                     a.BASIC_AMOUNT_MONTHLY,
                     a.INTEREST_AMOUNT_MONTHLY,
                     a.AMOUNT_MONTHLY_LEASING,
                     a.REMAIN_BASIC_AMOUNT_LEASING
                FROM (  SELECT DUEDATE,
                               DOCNUMBER,
                               MONTHTOBE,
                               MAX (daterelease) AS daterelease,
                               MAX (lebih_hari) AS lebih_hari,
                               MAX (BASIC_AMOUNT_MONTHLY) BASIC_AMOUNT_MONTHLY,
                               MAX (INTEREST_AMOUNT_MONTHLY) INTEREST_AMOUNT_MONTHLY,
                               MAX (AMOUNT_MONTHLY_LEASING) AMOUNT_MONTHLY_LEASING,
                               MAX (REMAIN_BASIC_AMOUNT_LEASING) REMAIN_BASIC_AMOUNT_LEASING
                          FROM (SELECT lt.*,
                                       TO_CHAR (py.daterelease) AS daterelease,
                                       PY.DATERELEASE - LT.DUEDATE AS LEBIH_HARI
                                  FROM leasingtransaction lt
                                       INNER JOIN PAYMENT PY ON PY.CFTRANSID = LT.GID
                                UNION
                                SELECT lr.*, '' AS daterelease, 0 AS lebih_hari
                                  FROM leasingreport lr)
                      GROUP BY DUEDATE, DOCNUMBER, MONTHTOBE) a
                     INNER JOIN
                     (SELECT DOCNUMBER,
                             COMPANY,
                             TRANSACTIONMETHOD_BY,
                             TOTAL_MONTH,
                             AMOUNT_AFTER_CONV,
                             INTEREST_PERCENTAGE,
                             DENDA_PERCENTAGE,
                             VENDOR,
                             ITEM_CODE,
                             BUSINESSUNIT
                        FROM LEASINGMASTER) L
                        ON L.DOCNUMBER = a.docnumber
                     INNER JOIN (SELECT COMPANYCODE, ID FROM COMPANY) C ON C.ID = L.COMPANY
                     INNER JOIN SUPPLIER S ON S.ID = L.VENDOR
                     INNER JOIN MATERIAL M ON M.ID = L.ITEM_CODE
                     INNER JOIN BUSINESSUNIT B ON B.ID = L.BUSINESSUNIT
                     WHERE to_char(a.DUEDATE,'mmyyyy') = '122022'
            ORDER BY TO_NUMBER (a.monthtobe) ASC
