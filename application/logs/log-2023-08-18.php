<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-08-18 04:32:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 666
ERROR - 2023-08-18 04:32:34 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 721
ERROR - 2023-08-18 04:32:34 --> Severity: Notice --> Undefined variable: bankKI C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 763
ERROR - 2023-08-18 04:33:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 666
ERROR - 2023-08-18 04:33:33 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 721
ERROR - 2023-08-18 04:33:33 --> Severity: Notice --> Undefined variable: bankKI C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 763
ERROR - 2023-08-18 04:34:10 --> Severity: Error --> Maximum execution time of 30 seconds exceeded C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 04:34:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 666
ERROR - 2023-08-18 04:34:29 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 721
ERROR - 2023-08-18 04:34:29 --> Severity: Notice --> Undefined variable: bankKI C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 763
ERROR - 2023-08-18 06:27:44 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 06:28:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 666
ERROR - 2023-08-18 06:28:08 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 721
ERROR - 2023-08-18 06:28:08 --> Severity: Notice --> Undefined variable: bankKI C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 763
ERROR - 2023-08-18 06:28:09 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:28:27 --> Severity: Warning --> oci_execute(): ORA-00933: SQL command not properly ended C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:28:27 --> Query error: ORA-00933: SQL command not properly ended - Invalid query: SELECT TO_CHAR(DOCDATE, 'fmmm/fmdd/yyyy') AS DOCDATE, 
                        TO_CHAR(MATURITY_DATE, 'fmmm/fmdd/yyyy') AS MATURITY_DATE,
                        TO_CHAR(MATURITY_DATE, 'yyyy/fmm/fdd') AS END_CTRCT,
                        INTEREST_PAYMENT_SCHEDULE_DATE,
                        INTEREST_PAYMENT_SCHEDULE, 
                        AMOUNT_LIMIT, 
                        INTEREST,
                        FM.PK_NUMBER,
                        FRK.CONTRACT_NUMBER,
                        FM.COMPANY 
                            FROM FUNDS_DETAIL_RK FRK 
                            LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FRK.UUID
                                WHERE UUID = 442b39bf-3650-4fac-af63-88d3604bd5d3)
ERROR - 2023-08-18 11:29:00 --> Severity: Warning --> oci_execute(): ORA-00918: column ambiguously defined C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:29:00 --> Query error: ORA-00918: column ambiguously defined - Invalid query: SELECT TO_CHAR(DOCDATE, 'fmmm/fmdd/yyyy') AS DOCDATE, 
                        TO_CHAR(MATURITY_DATE, 'fmmm/fmdd/yyyy') AS MATURITY_DATE,
                        TO_CHAR(MATURITY_DATE, 'yyyy/fmm/fdd') AS END_CTRCT,
                        INTEREST_PAYMENT_SCHEDULE_DATE,
                        INTEREST_PAYMENT_SCHEDULE, 
                        AMOUNT_LIMIT, 
                        INTEREST,
                        FM.PK_NUMBER,
                        FRK.CONTRACT_NUMBER,
                        FM.COMPANY 
                            FROM FUNDS_DETAIL_RK FRK 
                            LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FRK.UUID
                                WHERE UUID = '442b39bf-3650-4fac-af63-88d3604bd5d3'
ERROR - 2023-08-18 11:29:52 --> Severity: Warning --> oci_execute(): ORA-00918: column ambiguously defined C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:29:52 --> Query error: ORA-00918: column ambiguously defined - Invalid query: SELECT TO_CHAR(FM.DOCDATE, 'fmmm/fmdd/yyyy') AS DOCDATE, 
                        TO_CHAR(FRK.MATURITY_DATE, 'fmmm/fmdd/yyyy') AS MATURITY_DATE,
                        TO_CHAR(FRK.MATURITY_DATE, 'yyyy/fmm/fdd') AS END_CTRCT,
                        INTEREST_PAYMENT_SCHEDULE_DATE,
                        INTEREST_PAYMENT_SCHEDULE, 
                        AMOUNT_LIMIT, 
                        INTEREST,
                        FM.PK_NUMBER,
                        FRK.CONTRACT_NUMBER,
                        FM.COMPANY 
                            FROM FUNDS_DETAIL_RK FRK 
                            LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FRK.UUID
                                WHERE UUID = '442b39bf-3650-4fac-af63-88d3604bd5d3'
ERROR - 2023-08-18 11:30:12 --> Severity: Warning --> oci_execute(): ORA-00904: &quot;FM&quot;.&quot;DOCDATE&quot;: invalid identifier C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:30:12 --> Query error: ORA-00904: "FM"."DOCDATE": invalid identifier - Invalid query: SELECT TO_CHAR(FM.DOCDATE, 'fmmm/fmdd/yyyy') AS DOCDATE, 
                        TO_CHAR(FRK.MATURITY_DATE, 'fmmm/fmdd/yyyy') AS MATURITY_DATE,
                        TO_CHAR(FRK.MATURITY_DATE, 'yyyy/fmm/fdd') AS END_CTRCT,
                        INTEREST_PAYMENT_SCHEDULE_DATE,
                        INTEREST_PAYMENT_SCHEDULE, 
                        AMOUNT_LIMIT, 
                        INTEREST,
                        FM.PK_NUMBER,
                        FRK.CONTRACT_NUMBER,
                        FM.COMPANY 
                            FROM FUNDS_DETAIL_RK FRK 
                            LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FRK.UUID
                                WHERE FRK.UUID = '442b39bf-3650-4fac-af63-88d3604bd5d3'
ERROR - 2023-08-18 11:30:21 --> Severity: Warning --> oci_execute(): ORA-01821: date format not recognized C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:30:21 --> Query error: ORA-01821: date format not recognized - Invalid query: SELECT TO_CHAR(FRK.DOCDATE, 'fmmm/fmdd/yyyy') AS DOCDATE, 
                        TO_CHAR(FRK.MATURITY_DATE, 'fmmm/fmdd/yyyy') AS MATURITY_DATE,
                        TO_CHAR(FRK.MATURITY_DATE, 'yyyy/fmm/fdd') AS END_CTRCT,
                        INTEREST_PAYMENT_SCHEDULE_DATE,
                        INTEREST_PAYMENT_SCHEDULE, 
                        AMOUNT_LIMIT, 
                        INTEREST,
                        FM.PK_NUMBER,
                        FRK.CONTRACT_NUMBER,
                        FM.COMPANY 
                            FROM FUNDS_DETAIL_RK FRK 
                            LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FRK.UUID
                                WHERE FRK.UUID = '442b39bf-3650-4fac-af63-88d3604bd5d3'
ERROR - 2023-08-18 11:30:34 --> Severity: Warning --> oci_execute(): ORA-01821: date format not recognized C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:30:34 --> Query error: ORA-01821: date format not recognized - Invalid query: SELECT TO_CHAR(FRK.DOCDATE, 'fmmm/fmdd/yyyy') AS DOCDATE, 
                        TO_CHAR(FRK.MATURITY_DATE, 'fmmm/fmdd/yyyy') AS MATURITY_DATE,
                        TO_CHAR(FRK.MATURITY_DATE, 'yyyy/fmmm/fdd') AS END_CTRCT,
                        INTEREST_PAYMENT_SCHEDULE_DATE,
                        INTEREST_PAYMENT_SCHEDULE, 
                        AMOUNT_LIMIT, 
                        INTEREST,
                        FM.PK_NUMBER,
                        FRK.CONTRACT_NUMBER,
                        FM.COMPANY 
                            FROM FUNDS_DETAIL_RK FRK 
                            LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FRK.UUID
                                WHERE FRK.UUID = '442b39bf-3650-4fac-af63-88d3604bd5d3'
ERROR - 2023-08-18 11:30:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6063
ERROR - 2023-08-18 11:30:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:30:48 --> Severity: Notice --> Undefined variable: INSERT_TO_FP C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6087
ERROR - 2023-08-18 11:30:48 --> Severity: Warning --> oci_execute(): ORA-01722: invalid number C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:30:48 --> Query error: ORA-01722: invalid number - Invalid query: UPDATE "FUNDSPAYMENT" SET DOCDATE = TO_CHAR('11/16/2022', 'mm/dd/yyyy'), START_PERIOD = TO_DATE('2022/16/11', 'yyyy/mm/dd'), END_PERIOD = TO_DATE('2022/16/12', 'yyyy/mm/dd'), LASTUPDATE = SYSDATE, "CONTRACT_NUMBER" = '2301SIWLB143', "PK_NUMBER" = '024 - 16 Nop 2022 Not. R.F Limpele', "COMPANY" = 'C000000026', "CREDIT_TYPE" = 'KMK', "FCENTRY" = 'ERPKPN', "FCIP" = '127.0.0.1', "PERIOD_MONTH" = 16, "PERIOD_YEAR" = 2022, "PERIOD" = 1, "INTEREST" = 0, "GID" = 0, "UUID" = '442b39bf-3650-4fac-af63-88d3604bd5d3'
WHERE "GID" = 0
ERROR - 2023-08-18 11:33:02 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:33:02 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:33:02 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6091
ERROR - 2023-08-18 11:33:02 --> Severity: Warning --> oci_execute(): ORA-01722: invalid number C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:33:02 --> Query error: ORA-01722: invalid number - Invalid query: UPDATE "FUNDSPAYMENT" SET DOCDATE = TO_CHAR('11/16/2022', 'mm/dd/yyyy'), START_PERIOD = TO_DATE('2022/16/11', 'yyyy/mm/dd'), END_PERIOD = TO_DATE('2022/16/12', 'yyyy/mm/dd'), LASTUPDATE = SYSDATE, "CONTRACT_NUMBER" = '2301SIWLB143', "PK_NUMBER" = '024 - 16 Nop 2022 Not. R.F Limpele', "COMPANY" = 'C000000026', "CREDIT_TYPE" = 'KMK', "FCENTRY" = 'ERPKPN', "FCIP" = '127.0.0.1', "PERIOD_MONTH" = 16, "PERIOD_YEAR" = 2022, "PERIOD" = 1, "INTEREST" = 0, "GID" = 0, "UUID" = '442b39bf-3650-4fac-af63-88d3604bd5d3'
WHERE "GID" = 0
ERROR - 2023-08-18 11:34:50 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:34:50 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:34:50 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6081
ERROR - 2023-08-18 11:35:49 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:35:49 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:35:49 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6081
ERROR - 2023-08-18 11:37:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:37:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:37:41 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6106
ERROR - 2023-08-18 11:38:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:38:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:38:16 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6107
ERROR - 2023-08-18 11:38:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:38:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:38:19 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6107
ERROR - 2023-08-18 11:39:03 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:39:03 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:39:03 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6107
ERROR - 2023-08-18 11:39:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:39:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:39:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:39:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:39:27 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6093
ERROR - 2023-08-18 11:39:28 --> Severity: Warning --> oci_execute(): ORA-01722: invalid number C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:39:28 --> Query error: ORA-01722: invalid number - Invalid query: INSERT INTO "FUNDSPAYMENT" (DOCDATE, START_PERIOD, END_PERIOD, LASTUPDATE, "CONTRACT_NUMBER", "PK_NUMBER", "COMPANY", "CREDIT_TYPE", "FCENTRY", "FCIP", "PERIOD_MONTH", "PERIOD_YEAR", "PERIOD", "INTEREST", "GID", "UUID") VALUES (TO_CHAR('11/16/2022', 'mm/dd/yyyy'), TO_DATE('2022/16/11', 'yyyy/mm/dd'), TO_DATE('2022/16/12', 'yyyy/mm/dd'), SYSDATE, '2301SIWLB143', '024 - 16 Nop 2022 Not. R.F Limpele', 'C000000026', 'KMK', 'ERPKPN', '127.0.0.1', 16, 2022, 1, 0, 0, '442b39bf-3650-4fac-af63-88d3604bd5d3')
ERROR - 2023-08-18 11:40:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:40:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:40:09 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6093
ERROR - 2023-08-18 11:40:09 --> Severity: Warning --> oci_execute(): ORA-01843: not a valid month C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:40:09 --> Query error: ORA-01843: not a valid month - Invalid query: INSERT INTO "FUNDSPAYMENT" (DOCDATE, START_PERIOD, END_PERIOD, LASTUPDATE, "CONTRACT_NUMBER", "PK_NUMBER", "COMPANY", "CREDIT_TYPE", "FCENTRY", "FCIP", "PERIOD_MONTH", "PERIOD_YEAR", "PERIOD", "INTEREST", "GID", "UUID") VALUES (TO_DATE('11/16/2022', 'mm/dd/yyyy'), TO_DATE('2022/16/11', 'yyyy/mm/dd'), TO_DATE('2022/16/12', 'yyyy/mm/dd'), SYSDATE, '2301SIWLB143', '024 - 16 Nop 2022 Not. R.F Limpele', 'C000000026', 'KMK', 'ERPKPN', '127.0.0.1', 16, 2022, 1, 0, 0, '442b39bf-3650-4fac-af63-88d3604bd5d3')
ERROR - 2023-08-18 11:40:49 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:40:49 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:40:49 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6093
ERROR - 2023-08-18 11:40:49 --> Severity: Warning --> oci_execute(): ORA-01843: not a valid month C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:40:49 --> Query error: ORA-01843: not a valid month - Invalid query: INSERT INTO "FUNDSPAYMENT" (DOCDATE, START_PERIOD, END_PERIOD, LASTUPDATE, "CONTRACT_NUMBER", "PK_NUMBER", "COMPANY", "CREDIT_TYPE", "FCENTRY", "FCIP", "PERIOD_MONTH", "PERIOD_YEAR", "PERIOD", "INTEREST", "GID", "UUID") VALUES (TO_DATE('11/16/2022', 'mm/dd/yyyy'), TO_DATE('2022/16/11', 'yyyy/mm/dd'), TO_DATE('2022/16/12', 'yyyy/dd/mm'), SYSDATE, '2301SIWLB143', '024 - 16 Nop 2022 Not. R.F Limpele', 'C000000026', 'KMK', 'ERPKPN', '127.0.0.1', 16, 2022, 1, 0, 0, '442b39bf-3650-4fac-af63-88d3604bd5d3')
ERROR - 2023-08-18 11:41:06 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:06 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:06 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6093
ERROR - 2023-08-18 11:41:06 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:06 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:06 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:06 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:06 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:06 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:07 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:07 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:08 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:08 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:09 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:09 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:10 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:10 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:11 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:11 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:12 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:12 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:13 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:13 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:15 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:15 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:16 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:16 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6064
ERROR - 2023-08-18 11:41:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:41:36 --> Severity: Error --> Maximum execution time of 30 seconds exceeded C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 11:42:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:34 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6094
ERROR - 2023-08-18 11:42:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:42:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:42:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:17 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6094
ERROR - 2023-08-18 11:43:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:17 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:18 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:19 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:20 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:20 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:21 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:21 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:22 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:23 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:25 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:25 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:26 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:27 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:27 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:28 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:29 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:29 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:30 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:30 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:31 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:31 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:32 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:32 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:33 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:33 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:34 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:34 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:35 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:35 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:43:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:43:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:36 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6094
ERROR - 2023-08-18 11:45:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:36 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:36 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:37 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:37 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:38 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:39 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:39 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:40 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:40 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:41 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:42 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:43 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:44 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:45 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:45 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:46 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:47 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:45:48 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:45:48 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:46:24 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6065
ERROR - 2023-08-18 11:46:24 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:46:24 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6108
ERROR - 2023-08-18 11:46:54 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:46:54 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6067
ERROR - 2023-08-18 11:46:54 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6109
ERROR - 2023-08-18 11:47:14 --> Severity: Warning --> date_diff() expects parameter 1 to be DateTimeInterface, boolean given C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6066
ERROR - 2023-08-18 11:47:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6067
ERROR - 2023-08-18 11:47:14 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6109
ERROR - 2023-08-18 11:47:57 --> Severity: error --> Exception: Call to a member function format() on boolean C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6059
ERROR - 2023-08-18 11:49:18 --> Severity: error --> Exception: Call to a member function format() on boolean C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6059
ERROR - 2023-08-18 11:49:29 --> Severity: error --> Exception: Call to a member function format() on boolean C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6059
ERROR - 2023-08-18 11:49:52 --> Severity: error --> Exception: Call to a member function format() on boolean C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6059
ERROR - 2023-08-18 11:50:04 --> Severity: error --> Exception: Call to a member function format() on boolean C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6059
ERROR - 2023-08-18 11:50:31 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6110
ERROR - 2023-08-18 11:50:57 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6111
ERROR - 2023-08-18 11:51:27 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6110
ERROR - 2023-08-18 11:51:56 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6110
ERROR - 2023-08-18 11:52:10 --> Severity: Notice --> Undefined variable: EXIST C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6084
ERROR - 2023-08-18 11:52:43 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 6111
ERROR - 2023-08-18 12:01:02 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at C:\xampp\htdocs\bankrelation\application\models\KMKModel.php:6055) C:\xampp\htdocs\bankrelation\system\core\Common.php 570
ERROR - 2023-08-18 12:01:02 --> Severity: Error --> Maximum execution time of 30 seconds exceeded C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 09:05:12 --> Severity: Error --> Maximum execution time of 30 seconds exceeded C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 09:06:13 --> Severity: Error --> Maximum execution time of 30 seconds exceeded C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 09:08:34 --> Severity: Error --> Maximum execution time of 30 seconds exceeded C:\xampp\htdocs\bankrelation\system\core\Common.php 597
ERROR - 2023-08-18 09:10:47 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:10:48 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:11:04 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\controllers\Cashflow\KmkController.php 1046
ERROR - 2023-08-18 09:11:34 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:12:02 --> Severity: Error --> Maximum execution time of 30 seconds exceeded C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 09:12:38 --> Severity: Error --> Maximum execution time of 30 seconds exceeded C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 09:12:55 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:12:56 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:13:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:13:22 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:13:22 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:13:23 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:13:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:13:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:13:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:13:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:13:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:13:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:13:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 09:14:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:14:43 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:14:43 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:14:44 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:14:45 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:14:45 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:14:45 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:14:45 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:14:45 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:14:45 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:14:45 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 09:17:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:17:17 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:17:17 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:17:18 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:17:18 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:17:18 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:17:18 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:17:18 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:17:18 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:17:18 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:17:18 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 14:17:56 --> Severity: Notice --> Undefined index: AMOUNT_PER_MONTH C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 682
ERROR - 2023-08-18 14:17:56 --> Severity: Notice --> Undefined index: IDC_STATUS C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 692
ERROR - 2023-08-18 14:17:56 --> Severity: Notice --> Undefined index: CTYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 693
ERROR - 2023-08-18 14:17:56 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 719
ERROR - 2023-08-18 14:17:56 --> Severity: Notice --> Undefined variable: vendorID C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 732
ERROR - 2023-08-18 09:18:27 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:18:28 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:18:28 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:18:28 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:18:28 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:18:28 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:18:28 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:18:28 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 14:19:44 --> Severity: Notice --> Undefined index: ADDENDUM_DATE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 795
ERROR - 2023-08-18 14:19:44 --> Severity: Notice --> Undefined index: RATE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 807
ERROR - 2023-08-18 14:19:44 --> Severity: Notice --> Undefined index: ADD_REMARK C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 817
ERROR - 2023-08-18 14:19:44 --> Severity: Notice --> Undefined index: PAYMENT_BANK_ACCOUNT C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 818
ERROR - 2023-08-18 14:19:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 895
ERROR - 2023-08-18 14:19:46 --> Severity: Notice --> Undefined variable: code C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 897
ERROR - 2023-08-18 14:19:46 --> Severity: Notice --> Undefined variable: jenisIDC C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 897
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 83
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 83
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 83
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 106
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 116
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Undefined offset: 0 C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 118
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 118
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Undefined offset: 0 C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 120
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 120
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 137
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 138
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 139
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 140
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 141
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 156
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 157
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 158
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 159
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 161
ERROR - 2023-08-18 14:19:47 --> Severity: Notice --> Undefined variable: result C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 359
ERROR - 2023-08-18 09:20:18 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:20:19 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:31:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:31:38 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:31:38 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:31:39 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:31:40 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:31:40 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:31:40 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:31:40 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:31:40 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:31:40 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:31:40 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 09:31:56 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:31:57 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:34:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:34:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:34:19 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:34:19 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:34:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:34:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:34:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:34:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:34:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:34:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:34:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 09:35:02 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:35:53 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:36:06 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:36:07 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:37:55 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:37:55 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:40:36 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:40:36 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:41:23 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 146
ERROR - 2023-08-18 09:41:23 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:41:23 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:41:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:41:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:41:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:41:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:41:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:41:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:41:24 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 14:44:26 --> Severity: Notice --> Undefined index: AMOUNT_PER_MONTH C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 682
ERROR - 2023-08-18 14:44:26 --> Severity: Notice --> Undefined index: IDC_STATUS C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 692
ERROR - 2023-08-18 14:44:26 --> Severity: Notice --> Undefined index: CTYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 693
ERROR - 2023-08-18 14:44:26 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 719
ERROR - 2023-08-18 14:44:26 --> Severity: Notice --> Undefined variable: vendorID C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 732
ERROR - 2023-08-18 09:44:42 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 14:44:43 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:44:43 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:44:43 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:44:43 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:44:43 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:44:43 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:44:43 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 09:46:03 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:47:45 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\ApprovalContract.php 750
ERROR - 2023-08-18 09:47:45 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:49:18 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\Withdraw.php 727
ERROR - 2023-08-18 14:49:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 5304
ERROR - 2023-08-18 14:49:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 5307
ERROR - 2023-08-18 14:49:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 5310
ERROR - 2023-08-18 14:49:19 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 5319
ERROR - 2023-08-18 14:49:20 --> Severity: Notice --> Undefined variable: result1 C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 5447
ERROR - 2023-08-18 09:49:59 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 666
ERROR - 2023-08-18 09:49:59 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 721
ERROR - 2023-08-18 09:49:59 --> Severity: Notice --> Undefined variable: bankKI C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 763
ERROR - 2023-08-18 09:50:00 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 14:50:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:50:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:50:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:50:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:50:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:50:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:50:20 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 09:51:18 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 14:51:33 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 14:51:33 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 14:51:33 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 14:51:33 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 14:51:33 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 14:51:33 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 14:51:33 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 14:52:43 --> Severity: Notice --> Undefined index: ADDENDUM_DATE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 795
ERROR - 2023-08-18 14:52:43 --> Severity: Notice --> Undefined index: RATE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 807
ERROR - 2023-08-18 14:52:43 --> Severity: Notice --> Undefined index: ADD_REMARK C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 817
ERROR - 2023-08-18 14:52:43 --> Severity: Notice --> Undefined index: PAYMENT_BANK_ACCOUNT C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 818
ERROR - 2023-08-18 14:52:43 --> Severity: Notice --> Undefined variable: jenisIDC C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 897
ERROR - 2023-08-18 14:52:44 --> Severity: Notice --> Undefined offset: 0 C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 118
ERROR - 2023-08-18 14:52:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 118
ERROR - 2023-08-18 14:52:44 --> Severity: Notice --> Undefined offset: 0 C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 120
ERROR - 2023-08-18 14:52:44 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\ReportGenModel.php 120
ERROR - 2023-08-18 09:52:54 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:56:07 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:56:17 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\ApprovalContract.php 750
ERROR - 2023-08-18 09:56:18 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 09:56:42 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:57:09 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\Withdraw.php 727
ERROR - 2023-08-18 09:57:47 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMaster.php 1255
ERROR - 2023-08-18 09:59:34 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\Withdraw.php 727
ERROR - 2023-08-18 09:59:56 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\ApprovalWithdrawal.php 1044
ERROR - 2023-08-18 10:00:36 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\ApprovalWithdrawal.php 1044
ERROR - 2023-08-18 15:03:41 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 325
ERROR - 2023-08-18 15:03:42 --> Severity: Notice --> Undefined variable: resultUpdTrc C:\xampp\htdocs\bankrelation\application\controllers\Cashflow\KmkController.php 1507
ERROR - 2023-08-18 10:03:46 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 15:03:49 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 290
ERROR - 2023-08-18 15:03:49 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 291
ERROR - 2023-08-18 15:03:49 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 292
ERROR - 2023-08-18 15:03:49 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 292
ERROR - 2023-08-18 15:03:49 --> Severity: Warning --> oci_execute(): ORA-00001: unique constraint (KPNCORP.CF_TRANSACTION_U01) violated C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 15:03:49 --> Query error: ORA-00001: unique constraint (KPNCORP.CF_TRANSACTION_U01) violated - Invalid query: INSERT INTO "CF_TRANSACTION" (LASTUPDATE, DOCDATE, DUEDATE, LASTTIME, "DEPARTMENT", "COMPANY", "BUSINESSUNIT", "DOCNUMBER", "DOCTYPE", "VENDOR", "CURRENCY", "EXTSYS", "VAT", "RATE", "REMARK", "AMOUNT_INCLUDE_VAT", "TOTAL_BAYAR", "AMOUNT_PPH", "FCEDIT", "FCIP", "ID", "ISACTIVE", "FCENTRY") VALUES (SYSDATE, ADD_MONTHS(TO_DATE('18-08-2023','dd/mm/yyyy'),1), ADD_MONTHS(TO_DATE('18-08-2023','dd/mm/yyyy'),1), TO_CHAR(SYSDATE, 'HH24:MI'), 'BANK-RELATION', 'C000000003', 'B000000177', 'DUMMY_REV_FRCST_1', 'KMK', NULL, 'IDR', 'SAPHANA', '', 1, '', 0, 0, 0, 'ERPKPN', '127.0.0.1', 'fd63c325-6df2-464b-ac04-00eba8b54b26', 'TRUE', 'ERPKPN')
ERROR - 2023-08-18 15:09:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 290
ERROR - 2023-08-18 15:09:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 291
ERROR - 2023-08-18 15:09:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 292
ERROR - 2023-08-18 15:09:14 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 292
ERROR - 2023-08-18 15:09:14 --> Severity: Notice --> Undefined property: stdClass::$BANK C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 451
ERROR - 2023-08-18 15:09:14 --> Severity: Notice --> Undefined variable: resultUpdTrc C:\xampp\htdocs\bankrelation\application\controllers\Cashflow\KmkController.php 1507
ERROR - 2023-08-18 10:09:33 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\ApprovalWithdrawal.php 1044
ERROR - 2023-08-18 10:09:34 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 10:28:36 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 15:43:52 --> Severity: Warning --> oci_execute(): ORA-00904: &quot;FP&quot;.&quot;LAY_PY&quot;: invalid identifier C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 15:43:52 --> Query error: ORA-00904: "FP"."LAY_PY": invalid identifier - Invalid query: SELECT *
                    FROM (SELECT FM.UUID,
                                C.COMPANYCODE,
                                C.ID AS COMPANY,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA'
                                    THEN
                                    FDW.CONTRACT_NUMBER
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP'
                                    THEN
                                    FDW.CONTRACT_NUMBER
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR'
                                    THEN
                                    FDW.CONTRACT_NUMBER
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD'
                                    THEN
                                    FDR.CONTRACT_NUMBER
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK'
                                    THEN
                                    FDR.CONTRACT_NUMBER
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL'
                                    THEN
                                    FDR.CONTRACT_NUMBER
                                    ELSE
                                    FDK.CONTRACT_NUMBER
                                END
                                    AS CONTRACT_NUMBER,
                                FM.PK_NUMBER,
                                FM.CREDIT_TYPE,
                                -- FM.SUB_CREDIT_TYPE,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE IS NOT NULL
                                    THEN
                                    FDW.SUB_CREDIT_TYPE
                                    ELSE
                                    FM.SUB_CREDIT_TYPE
                                END
                                    AS SUB_CREDIT_TYPE,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.DOCDATE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.DOCDATE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.DOCDATE
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.DOCDATE
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.DOCDATE
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.DOCDATE
                                    ELSE FDK.DOCDATE
                                END
                                    AS DOCDATE,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.INTEREST
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.INTEREST
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.INTEREST
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.INTEREST
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.INTEREST
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.INTEREST
                                    ELSE FDK.INTEREST
                                END
                                    AS INTEREST_RATE,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.CURRENCY
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.CURRENCY
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.CURRENCY
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.CURRENCY
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CURRENCY
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CURRENCY
                                    ELSE FDK.CURRENCY
                                END
                                    AS CURRENCY,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA'
                                    THEN
                                    FDW.AMOUNT_LIMIT
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP'
                                    THEN
                                    FDW.AMOUNT_LIMIT
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR'
                                    THEN
                                    FDW.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD'
                                    THEN
                                    FDR.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK'
                                    THEN
                                    FDR.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL'
                                    THEN
                                    FDR.AMOUNT_LIMIT
                                    ELSE
                                    FDK.AMOUNT_LIMIT
                                END
                                    AS AMOUNT_LIMIT,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.TOTALWD
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.TOTALWD
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.TOTALWD
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.TOTALWD
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.TOTALWD
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.TOTALWD
                                    ELSE FDK.TOTALWD
                                END
                                    AS TOTALWD,
                                COALESCE(FP.INSTALLMENT, 0) AS INSTALLMENT,
                                COALESCE(FP.IDC_INSTALLMENT,0) AS IDC_INSTALLMENT,
                                COALESCE(FP.INTEREST, 0) AS INTEREST,
                                COALESCE(FP.IDC_INTEREST,0) AS IDC_INTEREST,
                                FP.DATE_FORECAST,
                                FP.PERIOD_MONTH AS MONTH,
                                FP.PERIOD_YEAR AS YEAR,
                                FP.LAT_PM,
                                FP.LAY_PY,
                                FP.IS_PAYMENT,
                                FP.ID
                            FROM FUNDS_MASTER FM
                                LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                                LEFT JOIN BANK B ON B.FCCODE = FM.BANK
                                LEFT JOIN
                                (SELECT FA.UUID,
                                        FA.SUB_CREDIT_TYPE,
                                        TO_CHAR (FA.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                                        FA.INTEREST,
                                        FA.CURRENCY,
                                        FA.AMOUNT_LIMIT,
                                        FA.CONTRACT_NUMBER,
                                        FW.TOTALWD
                                    FROM FUNDS_DETAIL_WA FA
                                        LEFT JOIN
                                        (SELECT *
                                            FROM (  SELECT UUID, SUM (AMOUNT) TOTALWD, WD_TYPE
                                                    FROM FUNDS_WITHDRAW
                                                    WHERE STATUS = '1'
                                                GROUP BY UUID, WD_TYPE)) FW
                                            ON (    FA.UUID = FW.UUID
                                                AND FW.WD_TYPE = FA.SUB_CREDIT_TYPE)
                                WHERE     FA.IS_ACC = '1'
                                        AND FA.ISACTIVE = '1'
                                        AND FW.TOTALWD > 0) FDW
                                    ON FDW.UUID = FM.UUID
                                LEFT JOIN
                                (SELECT DISTINCT
                                        FR.UUID,
                                        FR.SUB_CREDIT_TYPE,
                                        TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                                        FR.INTEREST,
                                        FR.CURRENCY,
                                        FR.AMOUNT_LIMIT,
                                        FR.CONTRACT_NUMBER,
                                        FW.TOTALWD
                                    FROM FUNDS_DETAIL_RK FR
                                        LEFT JOIN (  SELECT UUID, SUM (AMOUNT) TOTALWD, WD_TYPE
                                                        FROM FUNDS_WITHDRAW
                                                    WHERE STATUS = '1'
                                                    GROUP BY UUID, WD_TYPE) FW
                                            ON (    FR.UUID = FW.UUID
                                                AND FW.WD_TYPE = FR.SUB_CREDIT_TYPE)
                                WHERE     FR.IS_ACC = '1'
                                        AND FR.ISACTIVE = '1'
                                        AND FW.TOTALWD > 0) FDR
                                    ON FDR.UUID = FM.UUID
                                LEFT JOIN
                                (SELECT FR.UUID,
                                        FM.CREDIT_TYPE,
                                        TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                                        FR.INTEREST,
                                        FDR.CURRENCY,
                                        FDR.AMOUNT_LIMIT,
                                        FDR.CONTRACT_NUMBER,
                                        FW.TOTALWD
                                    FROM FUNDS_DETAIL_KI FR
                                        LEFT JOIN
                                        (SELECT UUID,
                                                LIMIT_TRANCHE AMOUNT_LIMIT,
                                                CONTRACT_NUMBER AS CONTRACT_NUMBER,
                                                TRANCHE_NUMBER,
                                                CURRENCY
                                            FROM FUNDS_DETAIL_KI_TRANCHE
                                        WHERE     ISACTIVE = '1'
                                                AND IS_ACC = '1'
                                                AND IS_COMPLETE IS NULL) FDR
                                            ON FR.UUID = FDR.UUID
                                        LEFT JOIN
                                        (  SELECT UUID, SUM (DDOWN_AMT) TOTALWD, TRANCHE_NUMBER
                                            FROM FUNDS_WD_KI_TRANCHE
                                            WHERE STATUS = '1'
                                        GROUP BY UUID, TRANCHE_NUMBER) FW
                                            ON (    FR.UUID = FW.UUID
                                                AND FDR.TRANCHE_NUMBER = FW.TRANCHE_NUMBER)
                                        LEFT JOIN (SELECT UUID, CREDIT_TYPE
                                                    FROM FUNDS_MASTER
                                                    WHERE ISACTIVE = '1' AND IS_ACC = '1') FM
                                            ON FM.UUID = FR.UUID
                                WHERE     FR.IS_ACC = '1'
                                        AND FR.ISACTIVE = '1'
                                        AND FW.TOTALWD > 0) FDK
                                    ON FDK.UUID = FM.UUID
                                LEFT JOIN
                                (  SELECT 
                                        FP.ID,
                                        FP.INSTALLMENT,
                                        FP.IDC_INSTALLMENT,
                                        FP.INTEREST,
                                        FP.IDC_INTEREST,
                                        FP.IS_PAYMENT,
                                        FP.CONTRACT_NUMBER,
                                        TO_CHAR (START_PERIOD, 'YYYY-MM-DD') AS START_PERIOD_C,
                                        TO_CHAR (END_PERIOD, 'YYYY-MM-DD') AS END_PERIOD_C,
                                        TO_CHAR (PAYMENT_DATE, 'MM/DD/YYYY') AS PAYMENT_DATE_C,
                                        TO_CHAR (END_PERIOD, 'MM-DD-YYYY') AS DATE_FORECAST,
                                        FPC.CURRENTACCOUNTINGYEAR,
                                        FPC.CURRENTACCOUNTINGPERIOD,
                                        FP.PERIOD_YEAR,
                                        FP.PERIOD_MONTH,
                                        LAT_PER.LAT_PM,
                                        LAT_PER.LAT_PY,
                                        FDKIT.CURRENCY
                                    FROM FUNDSPAYMENT FP
                                        LEFT JOIN FUNDS_PERIODCONTROL FPC
                                            ON FPC.COMPANY = FP.COMPANY
                                        LEFT JOIN FUNDS_MASTER FM
                                            ON FP.PK_NUMBER = FM.PK_NUMBER
                                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FDKIT
                                            ON     FDKIT.UUID = FM.UUID
                                                AND FP.CONTRACT_NUMBER = FDKIT.CONTRACT_NUMBER
                                                AND FDKIT.ISACTIVE = 1
                                        LEFT JOIN
                                        (SELECT PERIOD_MONTH AS LAT_PM, PERIOD_YEAR AS LAT_PY, FP.CONTRACT_NUMBER
                                        FROM FUNDSPAYMENT FP
                                                LEFT JOIN (  SELECT MIN (PERIOD) AS max_per, CONTRACT_NUMBER
                                                            FROM FUNDSPAYMENT WHERE IS_PAYMENT IS NULL
                                                        GROUP BY CONTRACT_NUMBER) LAT_PER
                                                ON LAT_PER.CONTRACT_NUMBER = FP.CONTRACT_NUMBER
                                        WHERE FP.PERIOD = LAT_PER.max_per) LAT_PER
                                        ON FP.CONTRACT_NUMBER = LAT_PER.CONTRACT_NUMBER
                                    WHERE     PERIOD_MONTH = FPC.CURRENTACCOUNTINGPERIOD
                                        AND PERIOD_YEAR = FPC.CURRENTACCOUNTINGYEAR
                                ORDER BY PERIOD ASC NULLS FIRST) FP
                                    ON (FP.CONTRACT_NUMBER = FDK.CONTRACT_NUMBER OR FP.CONTRACT_NUMBER = FDR.CONTRACT_NUMBER OR FP.CONTRACT_NUMBER = FDW.CONTRACT_NUMBER
                                    )
                        WHERE FM.IS_ACC = '1' AND FM.ISACTIVE = '1')
                WHERE TOTALWD > 0
ERROR - 2023-08-18 10:44:16 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 10:44:36 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 15:44:37 --> Severity: Warning --> oci_execute(): ORA-00904: &quot;FP&quot;.&quot;LAY_PY&quot;: invalid identifier C:\xampp\htdocs\bankrelation\system\database\drivers\oci8\oci8_driver.php 286
ERROR - 2023-08-18 15:44:37 --> Query error: ORA-00904: "FP"."LAY_PY": invalid identifier - Invalid query: SELECT *
                    FROM (SELECT FM.UUID,
                                C.COMPANYCODE,
                                C.ID AS COMPANY,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA'
                                    THEN
                                    FDW.CONTRACT_NUMBER
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP'
                                    THEN
                                    FDW.CONTRACT_NUMBER
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR'
                                    THEN
                                    FDW.CONTRACT_NUMBER
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD'
                                    THEN
                                    FDR.CONTRACT_NUMBER
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK'
                                    THEN
                                    FDR.CONTRACT_NUMBER
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL'
                                    THEN
                                    FDR.CONTRACT_NUMBER
                                    ELSE
                                    FDK.CONTRACT_NUMBER
                                END
                                    AS CONTRACT_NUMBER,
                                FM.PK_NUMBER,
                                FM.CREDIT_TYPE,
                                -- FM.SUB_CREDIT_TYPE,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE IS NOT NULL
                                    THEN
                                    FDW.SUB_CREDIT_TYPE
                                    ELSE
                                    FM.SUB_CREDIT_TYPE
                                END
                                    AS SUB_CREDIT_TYPE,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.DOCDATE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.DOCDATE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.DOCDATE
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.DOCDATE
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.DOCDATE
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.DOCDATE
                                    ELSE FDK.DOCDATE
                                END
                                    AS DOCDATE,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.INTEREST
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.INTEREST
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.INTEREST
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.INTEREST
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.INTEREST
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.INTEREST
                                    ELSE FDK.INTEREST
                                END
                                    AS INTEREST_RATE,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.CURRENCY
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.CURRENCY
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.CURRENCY
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.CURRENCY
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CURRENCY
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CURRENCY
                                    ELSE FDK.CURRENCY
                                END
                                    AS CURRENCY,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA'
                                    THEN
                                    FDW.AMOUNT_LIMIT
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP'
                                    THEN
                                    FDW.AMOUNT_LIMIT
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR'
                                    THEN
                                    FDW.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD'
                                    THEN
                                    FDR.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK'
                                    THEN
                                    FDR.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL'
                                    THEN
                                    FDR.AMOUNT_LIMIT
                                    ELSE
                                    FDK.AMOUNT_LIMIT
                                END
                                    AS AMOUNT_LIMIT,
                                CASE
                                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.TOTALWD
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.TOTALWD
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.TOTALWD
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.TOTALWD
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.TOTALWD
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.TOTALWD
                                    ELSE FDK.TOTALWD
                                END
                                    AS TOTALWD,
                                COALESCE(FP.INSTALLMENT, 0) AS INSTALLMENT,
                                COALESCE(FP.IDC_INSTALLMENT,0) AS IDC_INSTALLMENT,
                                COALESCE(FP.INTEREST, 0) AS INTEREST,
                                COALESCE(FP.IDC_INTEREST,0) AS IDC_INTEREST,
                                FP.DATE_FORECAST,
                                FP.PERIOD_MONTH AS MONTH,
                                FP.PERIOD_YEAR AS YEAR,
                                FP.LAT_PM,
                                FP.LAY_PY,
                                FP.IS_PAYMENT,
                                FP.ID
                            FROM FUNDS_MASTER FM
                                LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                                LEFT JOIN BANK B ON B.FCCODE = FM.BANK
                                LEFT JOIN
                                (SELECT FA.UUID,
                                        FA.SUB_CREDIT_TYPE,
                                        TO_CHAR (FA.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                                        FA.INTEREST,
                                        FA.CURRENCY,
                                        FA.AMOUNT_LIMIT,
                                        FA.CONTRACT_NUMBER,
                                        FW.TOTALWD
                                    FROM FUNDS_DETAIL_WA FA
                                        LEFT JOIN
                                        (SELECT *
                                            FROM (  SELECT UUID, SUM (AMOUNT) TOTALWD, WD_TYPE
                                                    FROM FUNDS_WITHDRAW
                                                    WHERE STATUS = '1'
                                                GROUP BY UUID, WD_TYPE)) FW
                                            ON (    FA.UUID = FW.UUID
                                                AND FW.WD_TYPE = FA.SUB_CREDIT_TYPE)
                                WHERE     FA.IS_ACC = '1'
                                        AND FA.ISACTIVE = '1'
                                        AND FW.TOTALWD > 0) FDW
                                    ON FDW.UUID = FM.UUID
                                LEFT JOIN
                                (SELECT DISTINCT
                                        FR.UUID,
                                        FR.SUB_CREDIT_TYPE,
                                        TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                                        FR.INTEREST,
                                        FR.CURRENCY,
                                        FR.AMOUNT_LIMIT,
                                        FR.CONTRACT_NUMBER,
                                        FW.TOTALWD
                                    FROM FUNDS_DETAIL_RK FR
                                        LEFT JOIN (  SELECT UUID, SUM (AMOUNT) TOTALWD, WD_TYPE
                                                        FROM FUNDS_WITHDRAW
                                                    WHERE STATUS = '1'
                                                    GROUP BY UUID, WD_TYPE) FW
                                            ON (    FR.UUID = FW.UUID
                                                AND FW.WD_TYPE = FR.SUB_CREDIT_TYPE)
                                WHERE     FR.IS_ACC = '1'
                                        AND FR.ISACTIVE = '1'
                                        AND FW.TOTALWD > 0) FDR
                                    ON FDR.UUID = FM.UUID
                                LEFT JOIN
                                (SELECT FR.UUID,
                                        FM.CREDIT_TYPE,
                                        TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                                        FR.INTEREST,
                                        FDR.CURRENCY,
                                        FDR.AMOUNT_LIMIT,
                                        FDR.CONTRACT_NUMBER,
                                        FW.TOTALWD
                                    FROM FUNDS_DETAIL_KI FR
                                        LEFT JOIN
                                        (SELECT UUID,
                                                LIMIT_TRANCHE AMOUNT_LIMIT,
                                                CONTRACT_NUMBER AS CONTRACT_NUMBER,
                                                TRANCHE_NUMBER,
                                                CURRENCY
                                            FROM FUNDS_DETAIL_KI_TRANCHE
                                        WHERE     ISACTIVE = '1'
                                                AND IS_ACC = '1'
                                                AND IS_COMPLETE IS NULL) FDR
                                            ON FR.UUID = FDR.UUID
                                        LEFT JOIN
                                        (  SELECT UUID, SUM (DDOWN_AMT) TOTALWD, TRANCHE_NUMBER
                                            FROM FUNDS_WD_KI_TRANCHE
                                            WHERE STATUS = '1'
                                        GROUP BY UUID, TRANCHE_NUMBER) FW
                                            ON (    FR.UUID = FW.UUID
                                                AND FDR.TRANCHE_NUMBER = FW.TRANCHE_NUMBER)
                                        LEFT JOIN (SELECT UUID, CREDIT_TYPE
                                                    FROM FUNDS_MASTER
                                                    WHERE ISACTIVE = '1' AND IS_ACC = '1') FM
                                            ON FM.UUID = FR.UUID
                                WHERE     FR.IS_ACC = '1'
                                        AND FR.ISACTIVE = '1'
                                        AND FW.TOTALWD > 0) FDK
                                    ON FDK.UUID = FM.UUID
                                LEFT JOIN
                                (  SELECT 
                                        FP.ID,
                                        FP.INSTALLMENT,
                                        FP.IDC_INSTALLMENT,
                                        FP.INTEREST,
                                        FP.IDC_INTEREST,
                                        FP.IS_PAYMENT,
                                        FP.CONTRACT_NUMBER,
                                        TO_CHAR (START_PERIOD, 'YYYY-MM-DD') AS START_PERIOD_C,
                                        TO_CHAR (END_PERIOD, 'YYYY-MM-DD') AS END_PERIOD_C,
                                        TO_CHAR (PAYMENT_DATE, 'MM/DD/YYYY') AS PAYMENT_DATE_C,
                                        TO_CHAR (END_PERIOD, 'MM-DD-YYYY') AS DATE_FORECAST,
                                        FPC.CURRENTACCOUNTINGYEAR,
                                        FPC.CURRENTACCOUNTINGPERIOD,
                                        FP.PERIOD_YEAR,
                                        FP.PERIOD_MONTH,
                                        LAT_PER.LAT_PM,
                                        LAT_PER.LAT_PY,
                                        FDKIT.CURRENCY
                                    FROM FUNDSPAYMENT FP
                                        LEFT JOIN FUNDS_PERIODCONTROL FPC
                                            ON FPC.COMPANY = FP.COMPANY
                                        LEFT JOIN FUNDS_MASTER FM
                                            ON FP.PK_NUMBER = FM.PK_NUMBER
                                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FDKIT
                                            ON     FDKIT.UUID = FM.UUID
                                                AND FP.CONTRACT_NUMBER = FDKIT.CONTRACT_NUMBER
                                                AND FDKIT.ISACTIVE = 1
                                        LEFT JOIN
                                        (SELECT PERIOD_MONTH AS LAT_PM, PERIOD_YEAR AS LAT_PY, FP.CONTRACT_NUMBER
                                        FROM FUNDSPAYMENT FP
                                                LEFT JOIN (  SELECT MIN (PERIOD) AS max_per, CONTRACT_NUMBER
                                                            FROM FUNDSPAYMENT WHERE IS_PAYMENT IS NULL
                                                        GROUP BY CONTRACT_NUMBER) LAT_PER
                                                ON LAT_PER.CONTRACT_NUMBER = FP.CONTRACT_NUMBER
                                        WHERE FP.PERIOD = LAT_PER.max_per) LAT_PER
                                        ON FP.CONTRACT_NUMBER = LAT_PER.CONTRACT_NUMBER
                                    WHERE     PERIOD_MONTH = FPC.CURRENTACCOUNTINGPERIOD
                                        AND PERIOD_YEAR = FPC.CURRENTACCOUNTINGYEAR
                                ORDER BY PERIOD ASC NULLS FIRST) FP
                                    ON (FP.CONTRACT_NUMBER = FDK.CONTRACT_NUMBER OR FP.CONTRACT_NUMBER = FDR.CONTRACT_NUMBER OR FP.CONTRACT_NUMBER = FDW.CONTRACT_NUMBER
                                    )
                        WHERE FM.IS_ACC = '1' AND FM.ISACTIVE = '1')
                WHERE TOTALWD > 0
ERROR - 2023-08-18 10:45:16 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 10:48:16 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 10:49:28 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 10:50:22 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:18:45 --> Severity: Notice --> Undefined variable: Uid C:\xampp\htdocs\bankrelation\application\views\kmk\WithdrawDataKI.php 716
ERROR - 2023-08-18 11:18:45 --> Severity: Notice --> Undefined variable: DtTranche C:\xampp\htdocs\bankrelation\application\views\kmk\WithdrawDataKI.php 722
ERROR - 2023-08-18 11:18:45 --> Severity: Notice --> Undefined variable: Uid C:\xampp\htdocs\bankrelation\application\views\kmk\WithdrawDataKI.php 1273
ERROR - 2023-08-18 11:18:45 --> Severity: Notice --> Undefined variable: Uid C:\xampp\htdocs\bankrelation\application\views\kmk\WithdrawDataKI.php 1350
ERROR - 2023-08-18 11:18:45 --> Severity: Notice --> Undefined variable: Uid C:\xampp\htdocs\bankrelation\application\views\kmk\WithdrawDataKI.php 1486
ERROR - 2023-08-18 11:18:45 --> Severity: Notice --> Undefined variable: Uid C:\xampp\htdocs\bankrelation\application\views\kmk\WithdrawDataKI.php 1552
ERROR - 2023-08-18 11:18:45 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\WithdrawDataKI.php 1804
ERROR - 2023-08-18 11:22:28 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:23:04 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:25:58 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:32:42 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 666
ERROR - 2023-08-18 11:32:42 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 721
ERROR - 2023-08-18 11:32:42 --> Severity: Notice --> Undefined variable: bankKI C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 763
ERROR - 2023-08-18 11:32:43 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:33:06 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 16:33:07 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 16:33:07 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 16:33:07 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 16:33:07 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 16:33:07 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 16:33:07 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 16:33:07 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 11:33:18 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 666
ERROR - 2023-08-18 11:33:18 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 721
ERROR - 2023-08-18 11:33:18 --> Severity: Notice --> Undefined variable: bankKI C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterKI.php 763
ERROR - 2023-08-18 11:33:18 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:33:49 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 16:33:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 16:33:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 16:33:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 16:33:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 16:33:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 16:33:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 16:33:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 11:36:46 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 16:38:02 --> Severity: Notice --> Undefined index: FCENTRY C:\xampp\htdocs\bankrelation\application\models\PayReqKMKKIModel.php 983
ERROR - 2023-08-18 16:38:02 --> Severity: Notice --> Undefined index: FCENTRY C:\xampp\htdocs\bankrelation\application\models\PayReqKMKKIModel.php 984
ERROR - 2023-08-18 11:45:51 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:46:37 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:47:09 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:49:00 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:51:29 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:52:12 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 11:53:00 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 12:03:27 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterWA.php 459
ERROR - 2023-08-18 12:03:28 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 12:03:51 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterWA.php 221
ERROR - 2023-08-18 12:03:51 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterWA.php 459
ERROR - 2023-08-18 12:03:51 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 17:03:52 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 17:03:52 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 17:03:52 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 17:03:52 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 17:03:52 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 17:03:52 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 17:03:52 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
ERROR - 2023-08-18 12:04:00 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 12:05:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterWA.php 221
ERROR - 2023-08-18 12:05:46 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterWA.php 459
ERROR - 2023-08-18 12:06:46 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterWA.php 221
ERROR - 2023-08-18 12:06:46 --> Severity: Notice --> Undefined variable: DtUpload C:\xampp\htdocs\bankrelation\application\views\kmk\KMKMasterWA.php 459
ERROR - 2023-08-18 12:06:47 --> 404 Page Not Found: Assets/plugins
ERROR - 2023-08-18 17:06:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1783
ERROR - 2023-08-18 17:06:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1790
ERROR - 2023-08-18 17:06:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1797
ERROR - 2023-08-18 17:06:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1804
ERROR - 2023-08-18 17:06:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1811
ERROR - 2023-08-18 17:06:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1818
ERROR - 2023-08-18 17:06:49 --> Severity: Notice --> Undefined index: SUB_CREDIT_TYPE C:\xampp\htdocs\bankrelation\application\models\KMKModel.php 1825
