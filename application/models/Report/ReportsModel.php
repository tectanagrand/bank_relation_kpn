<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class ReportsModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function GetROSnAging($param) {
             $Lenght = $param["length"];
             $Start = $param["start"];
             $Columns = $param["columns"];
             $Search = $param["search"];
             $Order = $param["order"];
             $OrderField = $Columns[$Order[0]["column"]]["data"];

               $WHERE = "";
               if($param["DEPARTMENT"] != null || $param["DEPARTMENT"] != ''){
                    $WHERE .= " AND CFT.DEPARTMENT = '".$param["DEPARTMENT"]."'";
               }
               // $param["DEPARTMENT"] = $param["DEPARTMENT"]; //TO_DATE(?, 'YYYYMMDD')
               if($param['COMPANYGROUP'] != null || $param['COMPANYGROUP'] != ''){
                    $WHERE .= " AND BU.COMPANYGROUP = '" . $param['COMPANYGROUP'] . "'";
               }
               // if($param['COMPANYGROUP'] != null || $param['COMPANYGROUP'] != ''){
               //     $WHERE .= " AND CSG.FCCODE_GROUP = '" . $param['COMPANYGROUP'] . "'";
               // }
               if($param['COMPANYSUBGROUP'] != null || $param['COMPANYSUBGROUP'] != ''){
                    $WHERE .= " AND BU.COMPANY_SUBGROUP = '" . $param['COMPANYSUBGROUP'] . "'";
               }
               if($param["COMPANY"] != null || $param["COMPANY"] != ''){
                    $WHERE .= " AND CFT.COMPANY = '".$param["COMPANY"]."'";
               }
               if($param['WITHINVOICE'] != null || $param['WITHINVOICE'] != ''){
                    $WHERE .= " AND CFT.DOCNUMBER <> ' ' AND CFT.DOCNUMBER <> '0' ";

               }
               $SelectQuery = "(SELECT CFT.*, NVL(PY.AMOUNTPAID, 0) AS AMOUNTPAID, (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) AS AMOUNTOS, 
                            FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) AS OVERDUE,
                            C.COMPANYCODE, C.COMPANYNAME, BU.FCCODE AS BUSINESSUNITCODE, BU.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) <= 0 
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LCURRENT,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN 1 AND '".$param['VALUE1']."' 
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS1,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE2']."' AND '".$param['VALUE3']."' 
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS2,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE4']."' AND '".$param['VALUE5']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS3,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE6']."' AND '".$param['VALUE7']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS4,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE8']."' AND '".$param['VALUE9']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS5,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE10']."' AND '".$param['VALUE11']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS6,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) > '".$param['VALUE11']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS7
                       FROM (SELECT CFT.ID, CFPO.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCNUMBER, CFT.DOCREF, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
                                    CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM,
                                    TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF ,
                      CFT.CURRENCY
                               FROM $this->CF_TRANSACTION CFT
                              INNER JOIN $this->CF_TRANSACTION CFPO
                                      ON CFPO.DOCNUMBER = CFT.DOCREF
                                     AND CFPO.COMPANY = CFT.COMPANY
                                     AND CFPO.DOCTYPE <> 'PDO'
                              WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') <= '".$param['DOCDATETO']."' AND TO_CHAR(CFT.DUEDATE,'YYYYMMDD') <= '".$param['OVERDATE']."'
                              UNION ALL
                             SELECT CFT.ID, CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCREF, CFT.DOCNUMBER, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
                                    CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM,
                                    TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF ,
                      CFT.CURRENCY
                               FROM $this->CF_TRANSACTION CFT
                              WHERE CFT.DOCTYPE <> 'PDO'
                                AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') <= '".$param['DOCDATETO']."' AND TO_CHAR(CFT.DUEDATE,'YYYYMMDD') <= '".$param['OVERDATE']."' AND CFT.DOCTYPE NOT LIKE '%INV%'
                         AND CFT.COMPANY || CFT.DOCNUMBER IN
                                (SELECT company || docnumber
                                   FROM CF_TRANSACTION
                                 MINUS
                                 SELECT company || docref
                                   FROM cf_transaction)) CFT
                       LEFT JOIN (SELECT PY.CFTRANSID, SUM(PY.AMOUNT) AS AMOUNTPAID
                                    FROM $this->PAYMENT PY
                                   WHERE TO_CHAR(PY.DATERELEASE, 'YYYYMMDD') <= '".$param['PAIDDATE']."' 
                                   GROUP BY PY.CFTRANSID) PY
                              ON PY.CFTRANSID = CFT.ID
                      INNER JOIN $this->COMPANY C
                              ON C.ID = CFT.COMPANY
                     INNER JOIN COMPANY_SUBGROUP CSG ON CSG.FCCODE = C.COMPANY_SUBGROUP
                     INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
                      INNER JOIN $this->BUSINESSUNIT BU
                              ON BU.COMPANY = CFT.COMPANY
                             AND BU.ID = CFT.BUSINESSUNIT
                       LEFT JOIN SUPPLIER S
                              ON S.ID = CFT.VENDOR
                      INNER JOIN $this->USER_DEPART UD
                              ON UD.DEPARTMENT = CFT.DEPARTMENT
                             AND UD.FCCODE = '".$param['USERNAME']."'
                      WHERE (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) > 0
                        ". $WHERE . " AND (CFT.DOCNUMBER NOT LIKE '%TMPINV%' AND CFT.DOCNUMBER NOT LIKE '%TMP%' AND CFT.DOCREF NOT LIKE '%TMPINV%')";
                        
                     $SelectQuery .= " ORDER BY FLOOR(SYSDATE - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) DESC, CFT.DEPARTMENT, CFT.COMPANY)";
                     // $PARAMETER = [
                     //       $param["OVERDATE"], $param["OVERDATE"],
                     //       $param["OVERDATE"], $param["VALUE1"],
                     //       $param["OVERDATE"], $param["VALUE2"], $param["VALUE3"],
                     //       $param["OVERDATE"], $param["VALUE4"], $param["VALUE5"],
                     //       $param["OVERDATE"], $param["VALUE6"], $param["VALUE7"],
                     //       $param["OVERDATE"], $param["VALUE8"], $param["VALUE9"],
                     //       $param["OVERDATE"], $param["VALUE10"], $param["VALUE11"],
                     //       $param["OVERDATE"], $param["VALUE11"],
                     //       $param["DOCDATETO"], $param["DOCDATETO"],
                     //       $param["PAIDDATE"],
                     //       $param["USERNAME"]
                     //   ];
             
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
                      $SQLO = " ORDER BY FLOOR (SYSDATE - TO_DATE (NVL (DUEDATE, DOCDATE), 'MM/DD/YYYY')) DESC, DEPARTMENT,COMPANY";
                  } else {
                      $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
                  }
                  $result = $this->db->query("SELECT distinct * FROM $SelectQuery FC $SQLW $SQLO")->result();
                  // $result = $this->db->query("SELECT distinct * FROM $SelectQuery FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
                  // var_dump($this->db->last_query());exit();
                  $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $SelectQuery FC $SQLW")->result();
                  $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $SelectQuery FC")->result();
                  $return = [
                      "data" => $result,
                      "recordsTotal" => $CountAll[0]->JML,
                      "recordsFiltered" => $CountFil[0]->JML
                  ];
                  $this->db->close();
                  return $return;
    }

    public function GetROSnAgingasli($param) {
             $Lenght = $param["length"];
             $Start = $param["start"];
             $Columns = $param["columns"];
             $Search = $param["search"];
             $Order = $param["order"];
             $OrderField = $Columns[$Order[0]["column"]]["data"];

               $WHERE = "";
               if($param["DEPARTMENT"] != null || $param["DEPARTMENT"] != ''){
                    $WHERE .= " AND CFT.DEPARTMENT = '".$param["DEPARTMENT"]."'";
               }
               // $param["DEPARTMENT"] = $param["DEPARTMENT"]; //TO_DATE(?, 'YYYYMMDD')
               if($param['COMPANYGROUP'] != null || $param['COMPANYGROUP'] != ''){
                    $WHERE .= " AND BU.COMPANYGROUP = '" . $param['COMPANYGROUP'] . "'";
               }
               // if($param['COMPANYGROUP'] != null || $param['COMPANYGROUP'] != ''){
               //     $WHERE .= " AND CSG.FCCODE_GROUP = '" . $param['COMPANYGROUP'] . "'";
               // }
               if($param['COMPANYSUBGROUP'] != null || $param['COMPANYSUBGROUP'] != ''){
                    $WHERE .= " AND BU.COMPANY_SUBGROUP = '" . $param['COMPANYSUBGROUP'] . "'";
               }
               if($param["COMPANY"] != null || $param["COMPANY"] != ''){
                    $WHERE .= " AND CFT.COMPANY = '".$param["COMPANY"]."'";
               }
               $SelectQuery = "(SELECT CFT.*, NVL(PY.AMOUNTPAID, 0) AS AMOUNTPAID, (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) AS AMOUNTOS, 
                            FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) AS OVERDUE,
                            C.COMPANYCODE, C.COMPANYNAME, BU.FCCODE AS BUSINESSUNITCODE, BU.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) <= 0 
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LCURRENT,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN 1 AND '".$param['VALUE1']."' 
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS1,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE2']."' AND '".$param['VALUE3']."' 
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS2,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE4']."' AND '".$param['VALUE5']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS3,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE6']."' AND '".$param['VALUE7']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS4,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE8']."' AND '".$param['VALUE9']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS5,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN '".$param['VALUE10']."' AND '".$param['VALUE11']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS6,
                            CASE WHEN FLOOR(TO_DATE('".$param['OVERDATE']."', 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) > '".$param['VALUE11']."'
                                 THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                            END AS LOS7
                       FROM (SELECT CFT.ID, CFPO.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCNUMBER, CFT.DOCREF, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
                                    CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM,
                                    TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF ,
                      CFT.CURRENCY
                               FROM $this->CF_TRANSACTION CFT
                              INNER JOIN $this->CF_TRANSACTION CFPO
                                      ON CFPO.DOCNUMBER = CFT.DOCREF
                                     AND CFPO.COMPANY = CFT.COMPANY
                                     AND CFPO.DOCTYPE <> 'PDO'
                              WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') <= '".$param['DOCDATETO']."' AND TO_CHAR(CFT.DUEDATE,'YYYYMMDD') <= '".$param['OVERDATE']."'
                              UNION ALL
                             SELECT CFT.ID, CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCREF, CFT.DOCNUMBER, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
                                    CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM,
                                    TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF ,
                      CFT.CURRENCY
                               FROM $this->CF_TRANSACTION CFT
                              WHERE CFT.DOCTYPE <> 'PDO'
                                AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') <= '".$param['DOCDATETO']."' AND TO_CHAR(CFT.DUEDATE,'YYYYMMDD') <= '".$param['OVERDATE']."' AND CFT.DOCTYPE NOT LIKE '%INV%'
                         AND CFT.COMPANY || CFT.DOCNUMBER IN
                                (SELECT company || docnumber
                                   FROM CF_TRANSACTION
                                 MINUS
                                 SELECT company || docref
                                   FROM cf_transaction)) CFT
                       LEFT JOIN (SELECT PY.CFTRANSID, SUM(PY.AMOUNT) AS AMOUNTPAID
                                    FROM $this->PAYMENT PY
                                   WHERE TO_CHAR(PY.DATERELEASE, 'YYYYMMDD') <= '".$param['PAIDDATE']."' 
                                   GROUP BY PY.CFTRANSID) PY
                              ON PY.CFTRANSID = CFT.ID
                      INNER JOIN $this->COMPANY C
                              ON C.ID = CFT.COMPANY
                     INNER JOIN COMPANY_SUBGROUP CSG ON CSG.FCCODE = C.COMPANY_SUBGROUP
                     INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
                      INNER JOIN $this->BUSINESSUNIT BU
                              ON BU.COMPANY = CFT.COMPANY
                             AND BU.ID = CFT.BUSINESSUNIT
                       LEFT JOIN SUPPLIER S
                              ON S.ID = CFT.VENDOR
                      INNER JOIN $this->USER_DEPART UD
                              ON UD.DEPARTMENT = CFT.DEPARTMENT
                             AND UD.FCCODE = '".$param['USERNAME']."'
                      WHERE (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) > 0
                        ". $WHERE . " AND (CFT.DOCNUMBER NOT LIKE '%TMPINV%' AND CFT.DOCNUMBER NOT LIKE '%TMP%')";
                        
                     $SelectQuery .= " ORDER BY FLOOR(SYSDATE - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) DESC, CFT.DEPARTMENT, CFT.COMPANY)";
                     // $PARAMETER = [
                     //       $param["OVERDATE"], $param["OVERDATE"],
                     //       $param["OVERDATE"], $param["VALUE1"],
                     //       $param["OVERDATE"], $param["VALUE2"], $param["VALUE3"],
                     //       $param["OVERDATE"], $param["VALUE4"], $param["VALUE5"],
                     //       $param["OVERDATE"], $param["VALUE6"], $param["VALUE7"],
                     //       $param["OVERDATE"], $param["VALUE8"], $param["VALUE9"],
                     //       $param["OVERDATE"], $param["VALUE10"], $param["VALUE11"],
                     //       $param["OVERDATE"], $param["VALUE11"],
                     //       $param["DOCDATETO"], $param["DOCDATETO"],
                     //       $param["PAIDDATE"],
                     //       $param["USERNAME"]
                     //   ];
             
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
                      $SQLO = " ORDER BY FLOOR (SYSDATE - TO_DATE (NVL (DUEDATE, DOCDATE), 'MM/DD/YYYY')) DESC, DEPARTMENT,COMPANY";
                  } else {
                      $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
                  }
                  $result = $this->db->query("SELECT * FROM $SelectQuery FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
                  // var_dump($this->db->last_query());exit();
                  $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $SelectQuery FC $SQLW")->result();
                  $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $SelectQuery FC")->result();
                  $return = [
                      "data" => $result,
                      "recordsTotal" => $CountAll[0]->JML,
                      "recordsFiltered" => $CountFil[0]->JML
                  ];
                  $this->db->close();
                  return $return;
    }

    public function GetROSnAging__($param) {

          $WHERE = "";
          if($param["DEPARTMENT"] != null || $param["DEPARTMENT"] != ''){
          $WHERE .= " AND CFT.DEPARTMENT = '".$param["DEPARTMENT"]."'";
          }
          // $param["DEPARTMENT"] = $param["DEPARTMENT"]; //TO_DATE(?, 'YYYYMMDD')
          if($param['COMPANYGROUP'] != null || $param['COMPANYGROUP'] != ''){
            $WHERE .= " AND BU.COMPANYGROUP = '" . $param['COMPANYGROUP'] . "'";
          }
          // if($param['COMPANYGROUP'] != null || $param['COMPANYGROUP'] != ''){
          //     $WHERE .= " AND CSG.FCCODE_GROUP = '" . $param['COMPANYGROUP'] . "'";
          // }
          if($param['COMPANYSUBGROUP'] != null || $param['COMPANYSUBGROUP'] != ''){
            $WHERE .= " AND BU.COMPANY_SUBGROUP = '" . $param['COMPANYSUBGROUP'] . "'";
          }
          if($param["COMPANY"] != null || $param["COMPANY"] != ''){
               $WHERE .= " AND CFT.COMPANY = '".$param["COMPANY"]."'";
             }
          $SQL = "SELECT CFT.*, NVL(PY.AMOUNTPAID, 0) AS AMOUNTPAID, (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) AS AMOUNTOS, 
                       FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) AS OVERDUE,
                       C.COMPANYCODE, C.COMPANYNAME, BU.FCCODE AS BUSINESSUNITCODE, BU.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                       CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) <= 0 
                            THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                       END AS LCURRENT,
                       CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN 1 AND ? 
                            THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                       END AS LOS1,
                       CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ? 
                            THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                       END AS LOS2,
                       CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ?
                            THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                       END AS LOS3,
                       CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ?
                            THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                       END AS LOS4,
                       CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ?
                            THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                       END AS LOS5,
                       CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ?
                            THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                       END AS LOS6,
                       CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) > ?
                            THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
                       END AS LOS7
                  FROM (SELECT CFT.ID, CFPO.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCNUMBER, CFT.DOCREF, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
                               CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM,
                               TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF ,
                 CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                           AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') <= ? AND TO_CHAR(CFT.DUEDATE,'YYYYMMDD') <= '".$param['OVERDATE']."'
                         UNION ALL
                        SELECT CFT.ID, CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCREF, CFT.DOCNUMBER, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
                               CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM,
                               TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF ,
                 CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'PDO'
                           AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') <= ? AND TO_CHAR(CFT.DUEDATE,'YYYYMMDD') <= '".$param['OVERDATE']."') CFT
                  LEFT JOIN (SELECT PY.CFTRANSID, SUM(PY.AMOUNT) AS AMOUNTPAID
                               FROM $this->PAYMENT PY
                              WHERE TO_CHAR(PY.DATERELEASE, 'YYYYMMDD') <= ? 
                              GROUP BY PY.CFTRANSID) PY
                         ON PY.CFTRANSID = CFT.ID
                 INNER JOIN $this->COMPANY C
                         ON C.ID = CFT.COMPANY
                INNER JOIN COMPANY_SUBGROUP CSG ON CSG.FCCODE = C.COMPANY_SUBGROUP
                INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
                 INNER JOIN $this->BUSINESSUNIT BU
                         ON BU.COMPANY = CFT.COMPANY
                        AND BU.ID = CFT.BUSINESSUNIT
                  LEFT JOIN SUPPLIER S
                         ON S.ID = CFT.VENDOR
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.DEPARTMENT = CFT.DEPARTMENT
                        AND UD.FCCODE = ?
                 WHERE (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) > 0
                   ". $WHERE . " AND (CFT.DOCNUMBER NOT LIKE '%TMPINV%' AND CFT.DOCNUMBER NOT LIKE '%TMP%')";
                   
                $SQL .= " ORDER BY FLOOR(SYSDATE - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) DESC, CFT.DEPARTMENT, CFT.COMPANY";
             $PARAMETER = [
                 $param["OVERDATE"], $param["OVERDATE"],
                 $param["OVERDATE"], $param["VALUE1"],
                 $param["OVERDATE"], $param["VALUE2"], $param["VALUE3"],
                 $param["OVERDATE"], $param["VALUE4"], $param["VALUE5"],
                 $param["OVERDATE"], $param["VALUE6"], $param["VALUE7"],
                 $param["OVERDATE"], $param["VALUE8"], $param["VALUE9"],
                 $param["OVERDATE"], $param["VALUE10"], $param["VALUE11"],
                 $param["OVERDATE"], $param["VALUE11"],
                 $param["DOCDATETO"], $param["DOCDATETO"],
                 $param["PAIDDATE"],
                 $param["USERNAME"]
             ];

             $result = $this->db->query($SQL, $PARAMETER)->result();
             // var_dump($this->db->last_query());exit;
             $this->db->close();
             return $result;
    }

    // public function GetROSnAging($param) {
    //     $param["DEPARTMENT"] = "%" . $param["DEPARTMENT"] . "%"; //TO_DATE(?, 'YYYYMMDD')
    //     $SQL = "SELECT CFT.*, NVL(PY.AMOUNTPAID, 0) AS AMOUNTPAID, (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) AS AMOUNTOS, 
    //                    FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) AS OVERDUE,
    //                    C.COMPANYCODE, C.COMPANYNAME, BU.FCCODE AS BUSINESSUNITCODE, BU.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
    //                    CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) <= 0 
    //                         THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
    //                    END AS LCURRENT,
    //                    CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN 1 AND ? 
    //                         THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
    //                    END AS LOS1,
    //                    CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ? 
    //                         THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
    //                    END AS LOS2,
    //                    CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ?
    //                         THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
    //                    END AS LOS3,
    //                    CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ?
    //                         THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
    //                    END AS LOS4,
    //                    CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ?
    //                         THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
    //                    END AS LOS5,
    //                    CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) BETWEEN ? AND ?
    //                         THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
    //                    END AS LOS6,
    //                    CASE WHEN FLOOR(TO_DATE(?, 'YYYYMMDD') - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) > ?
    //                         THEN (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) ELSE 0 
    //                    END AS LOS7
    //               FROM (SELECT CFT.ID, CFPO.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCNUMBER, CFT.DOCREF, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
    //                            CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM,
    //                            TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF 
    //                       FROM $this->CF_TRANSACTION CFT
    //                      INNER JOIN $this->CF_TRANSACTION CFPO
    //                              ON CFPO.DOCNUMBER = CFT.DOCREF
    //                             AND CFPO.COMPANY = CFT.COMPANY
    //                             AND CFPO.DOCTYPE <> 'PDO'
    //                      WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
    //                        AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') <= ?
    //                      UNION ALL
    //                     SELECT CFT.ID, CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCREF, CFT.DOCNUMBER, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
    //                            CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM,
    //                            TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF 
    //                       FROM $this->CF_TRANSACTION CFT
    //                      WHERE CFT.DOCTYPE = 'PDO'
    //                        AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') <= ?) CFT
    //               LEFT JOIN (SELECT PY.CFTRANSID, SUM(PY.AMOUNT) AS AMOUNTPAID
    //                            FROM $this->PAYMENT PY
    //                           WHERE TO_CHAR(PY.DATERELEASE, 'YYYYMMDD') <= ?
    //                           GROUP BY PY.CFTRANSID) PY
    //                      ON PY.CFTRANSID = CFT.ID
    //              INNER JOIN $this->COMPANY C
    //                      ON C.ID = CFT.COMPANY
    //              INNER JOIN $this->BUSINESSUNIT BU
    //                      ON BU.COMPANY = CFT.COMPANY
    //                     AND BU.ID = CFT.BUSINESSUNIT
    //               LEFT JOIN SUPPLIER S
    //                      ON S.ID = CFT.VENDOR
    //              INNER JOIN $this->USER_DEPART UD
    //                      ON UD.DEPARTMENT = CFT.DEPARTMENT
    //                     AND UD.FCCODE = ?
    //              WHERE (CFT.AMOUNTDOCUMNET - NVL(PY.AMOUNTPAID, 0)) > 0
    //                AND CFT.DEPARTMENT LIKE ?
    //              ORDER BY FLOOR(SYSDATE - TO_DATE(NVL(CFT.DUEDATE, CFT.DOCDATE), 'MM/DD/YYYY')) DESC, CFT.DEPARTMENT, CFT.COMPANY";
    //     $PARAMETER = [
    //         $param["OVERDATE"], $param["OVERDATE"],
    //         $param["OVERDATE"], $param["VALUE1"],
    //         $param["OVERDATE"], $param["VALUE2"], $param["VALUE3"],
    //         $param["OVERDATE"], $param["VALUE4"], $param["VALUE5"],
    //         $param["OVERDATE"], $param["VALUE6"], $param["VALUE7"],
    //         $param["OVERDATE"], $param["VALUE8"], $param["VALUE9"],
    //         $param["OVERDATE"], $param["VALUE10"], $param["VALUE11"],
    //         $param["OVERDATE"], $param["VALUE11"],
    //         $param["DOCDATETO"], $param["DOCDATETO"],
    //         $param["PAIDDATE"],
    //         $param["USERNAME"], $param["DEPARTMENT"]
    //     ];
    //  //    print_r($this->db->last_query());    
    //     $result = $this->db->query($SQL, $PARAMETER)->result();
    //     $this->db->close();
    //     return $result;
    // }

     public function Export($Data)
     {
          try {
               $SQL = "SELECT CFT.ID, CFT.DEPARTMENT, ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) AS AMOUNTOS, NVL(PY.AMOUNT, 0) AS AMOUNTPAID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCNUMBER, CFT.DOCREF, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
                              CFT.VENDOR, TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, CFT.PAYTERM, C.COMPANYCODE, C.COMPANYNAME, BU.FCCODE AS BUSINESSUNITCODE, BU.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                              TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF, FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) AS OVERDUE,
                              CASE WHEN FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) <= 0 
                                   THEN ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) ELSE 0 
                              END AS LCURRENT,
                              CASE WHEN FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) BETWEEN 1 AND 30 
                                   THEN ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) ELSE 0 
                              END AS LOS1,
                              CASE WHEN FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) BETWEEN 31 AND 60 
                                   THEN ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) ELSE 0 
                              END AS LOS2,
                              CASE WHEN FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) BETWEEN 61 AND 90
                                   THEN ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) ELSE 0 
                              END AS LOS3,
                              CASE WHEN FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) BETWEEN 91 AND 120
                                   THEN ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) ELSE 0 
                              END AS LOS4,
                              CASE WHEN FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) BETWEEN 121 AND 180
                                   THEN ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) ELSE 0 
                              END AS LOS5,
                              CASE WHEN FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) BETWEEN 180 AND 365
                                   THEN ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) ELSE 0 
                              END AS LOS6,
                              CASE WHEN FLOOR(TO_DATE(CFT.DUEDATE) - TO_DATE(CFT.DOCDATE)) > 365
                                   THEN ((CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) - NVL(PY.AMOUNT, 0)) ELSE 0 
                              END AS LOS7
                         FROM $this->CF_TRANSACTION CFT
                         LEFT JOIN $this->PAYMENT PY
                              ON PY.CFTRANSID = CFT.ID
                         LEFT JOIN $this->DEPARTMENT DP
                              ON DP.FCCODE = CFT.DEPARTMENT
                         INNER JOIN $this->COMPANY C
                              ON C.ID = CFT.COMPANY
                         INNER JOIN $this->BUSINESSUNIT BU
                              ON BU.COMPANY = CFT.COMPANY
                              AND BU.ID = CFT.BUSINESSUNIT
                         LEFT JOIN SUPPLIER S
                              ON S.ID = CFT.VENDOR
                         INNER JOIN $this->USER_DEPART UD
                              ON UD.FCCODE = ?
                           AND UD.DEPARTMENT = CFT.DEPARTMENT
                         WHERE TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') BETWEEN ? AND ?";
               $ParamW = [$Data["USERNAME"], $Data["DOCDATEFROM"], $Data["DOCDATETO"]];
               if ($Data["DEPARTMENT"] == "ALL") {
                    $FDepartment = "Department : All Department";
               } else {
                    $FDepartment = "Department : " . $Data["DEPARTMENT"];
                    $SQL .= " AND CFT.DEPARTMENT = ?";
                    array_push($ParamW, $Data["DEPARTMENT"]);
               }
               $SQL .= " ORDER BY CFT.DEPARTMENT, CFT.DOCTYPE, CFT.DOCDATE, CFT.DOCDATE, CFT.DOCNUMBER";
               $result = $this->db->query($SQL, $ParamW)->result();
               // var_dump($this->db->last_query());exit();
               $FRange = "Document Date Range : " . Carbon::parse($Data["DOCDATEFROM"])->format('d-M-Y') . " s/d " . Carbon::parse($Data["DOCDATETO"])->format('d-M-Y');
               $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
               $objPHPExcel = new Spreadsheet();
               $objPHPExcel->getProperties()->setCreator("IT GAMA")
               ->setLastModifiedBy("IT GAMA")
               ->setTitle("Report OSnAging")
               ->setSubject("Report OSnAging")
               ->setDescription("Data Document in System $FDepartment, $FRange, $GExport")
               ->setKeywords("Report OSnAging")
               ->setCategory("Report OSnAging");
               $objPHPExcel->setActiveSheetIndex(0);
               $objPHPExcel->getActiveSheet()->setTitle('Data');
               $i = 1;
               $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
               $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
               $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
               $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
               $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(23);
               $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
               $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
               $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
               $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
               $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
               $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
               $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30);
               $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(30);

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

               $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':V' . ($i + 1))->applyFromArray([
                    'font' => $StyleBold, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
               ]);
               $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'No');
               $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':A' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'Department');
               $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':B' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'Company');
               $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':C' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'Business Unit');
               $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':D' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'Vendor');
               $objPHPExcel->getActiveSheet()->mergeCells('E' . $i . ':E' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'AP/AR Date');
               $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':H' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'TOP');
               $objPHPExcel->getActiveSheet()->mergeCells('L' . $i . ':L' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'Due Date');
               $objPHPExcel->getActiveSheet()->mergeCells('M' . $i . ':M' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'Overdue (Day)');
               $objPHPExcel->getActiveSheet()->mergeCells('N' . $i . ':N' . ($i + 1));
               $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'Document');
               $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':G' . $i);
               $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'Amount');
               $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':K' . $i);
               $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'Aging');
               $objPHPExcel->getActiveSheet()->mergeCells('O' . $i . ':V' . $i);
               $i++;
               $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'PO/SO/STO/SPO/PDO');
               $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'Invoice AP/AR');        
               $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'Invoice');
               $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'Paid');
               $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'OS');
               $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'Current');
               $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, '1 - 30');
               $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, '31 - 60');
               $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, '61 - 90');
               $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, '91 - 120');
               $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, '121 - 180');
               $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, '180 - 365');
               $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, '> 365');
               $i++;

               if (count($result) > 0) {
                    $iDtAwal = $i;
                    $No = 1;
                    foreach ($result as $values) {
                         $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $No);
                         $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $values->DEPARTMENT);
                         $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $values->COMPANYNAME);
                         $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $values->BUSINESSUNITNAME);
                         $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $values->VENDORNAME);
                         $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, $values->DOCNUMBER, DataType::TYPE_STRING);
                         $objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $i, $values->DOCREF, DataType::TYPE_STRING);
                         $DtDate = explode('/', $values->DOCDATE);
                         $XlsTime = gmmktime(0, 0, 0, intval($DtDate[0]), intval($DtDate[1]), intval($DtDate[2]));
                         $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, Date::PHPToExcel($XlsTime));
                         $objPHPExcel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                         $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $values->AMOUNTDOCUMNET);
                         $objPHPExcel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $values->AMOUNTPAID);
                         $objPHPExcel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $values->AMOUNTOS);
                         $objPHPExcel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $values->PAYTERM);
                         // $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $values->DUEDATE);
                         $DtDate2 = explode('/', $values->DUEDATE);
                         $XlsTime2 = gmmktime(0, 0, 0, intval($DtDate2[0]), intval($DtDate2[1]), intval($DtDate2[2]));
                         $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, Date::PHPToExcel($XlsTime2));
                         $objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                         $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $values->OVERDUE);
                         $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $values->LCURRENT);
                         $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('P' .$i, $values->LOS1);
                         $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $values->LOS2);
                         $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $values->LOS3);
                         $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $values->LOS4);
                         $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $values->LOS5);
                         $objPHPExcel->getActiveSheet()->getStyle('T' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $values->LOS6);
                         $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $values->LOS7);
                         $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                         $i++;
                         $No++;
                    }
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $iDtAwal . ':V' . ($i - 1))->applyFromArray([
                         'font' => $StyleDefault, 'borders' => $StyleBorder
                    ]);
                    // $objPHPExcel->getActiveSheet()->getStyle('G' . $iDtAwal . ':G' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);
               } else {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "No Data");
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':V' . $i);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':V' . $i)->applyFromArray([
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
