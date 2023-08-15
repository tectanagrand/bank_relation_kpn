<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class EntryPOModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function ShowData($param) {
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        $SQL = "(SELECT CFT.ID, CFT.DEPARTMENT, DP.FCNAME AS DEPARTMENTNAME, CFT.COMPANY, C.COMPANYCODE, C.COMPANYNAME, CFT.BUSINESSUNIT, BS.FCCODE AS BUSINESSUNITCODE, CFT.REMARK,
                       BS.FCNAME AS BUSINESSUNITNAME, CFT.DOCTYPE, CFT.DOCNUMBER, TO_CHAR(CFT.DOCDATE,'MM/DD/YYYY') AS DOCDATE, CFT.VENDOR, S.FCNAME AS VENDORNAME, CFT.CURRENCY, 
                       CFT.AMOUNT_INCLUDE_VAT, CFT.AMOUNT_PPH, CFT.VAT, CFT.TOTAL_BAYAR, CFT.INVOICEVENDORNO
                  FROM $this->CF_TRANSACTION CFT
                  LEFT JOIN $this->DEPARTMENT DP
                         ON DP.FCCODE = CFT.DEPARTMENT
                 INNER JOIN $this->COMPANY C 
                         ON C.ID = CFT.COMPANY
                 LEFT JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                 INNER JOIN $this->USER_DEPART UD
                         ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = CFT.DEPARTMENT
                 WHERE (CFT.DOCTYPE <> 'INV' AND CFT.DOCTYPE <> 'INV_AR')
                   AND CFT.ISACTIVE = 'TRUE'";
        $ParamW = [$param['USERNAME']];
        if ($param["DEPARTMENT"] != "" && $param["DEPARTMENT"] != NULL) {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            array_push($ParamW, $param["DEPARTMENT"]);
        }
        $SQL .= " ORDER BY CFT.DOCDATE DESC, CFT.DEPARTMENT, C.COMPANYNAME, CFT.DOCNUMBER)";
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
            $SQLO = " ORDER BY FC.DEPARTMENT, FC.COMPANYNAME, FC.DOCNUMBER";
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

    public function ShowDataDetail($param) {
      // echo "<pre>";
      // var_dump($param);exit();
      // echo "</pre>";
        $SQL = "SELECT CFD.ID, CFD.MATERIAL, MT.FCCODE AS MATERIALCODE, MT.FCNAME AS MATERIALNAME, CFD.REMARKS, CFT.INVOICEVENDORNO,
                       DECODE(CFT.VAT, 1, (CFD.AMOUNT_INCLUDE_VAT * 100/110), CFD.AMOUNT_INCLUDE_VAT) AS AMOUNT_SOURCE, 
                       (CFD.AMOUNT_INCLUDE_VAT - DECODE(CFT.VAT, 1, (CFD.AMOUNT_INCLUDE_VAT * 100/110), CFD.AMOUNT_INCLUDE_VAT)) AS AMOUNT_PPN, 
                       NVL(CFD.AMOUNT_PPH, 0) AS AMOUNT_PPH, (CFD.AMOUNT_INCLUDE_VAT - NVL(CFD.AMOUNT_PPH, 0)) AS AMOUNT_TOTAL  
                  FROM $this->CF_TRANSACTION_DET CFD
                 INNER JOIN $this->CF_TRANSACTION CFT
                         ON CFT.ID = CFD.ID
                 INNER JOIN $this->MATERIAL MT
                         ON MT.ID = CFD.MATERIAL
                        AND MT.EXTSYSTEM = CFT.EXTSYS
                 WHERE CFD.ID = ?";

        $result = $this->db->query($SQL, [$param["ID"]])->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function ShowDataInvoice($param) {
        $SQL = "SELECT DISTINCT CFT.ID, CFT.DEPARTMENT, CFT.VENDOR, CFT.CURRENCY,CFT.INVOICEVENDORNO, S.FCCODE AS VENDORCODE, S.FCNAME AS VENDORNAME, CFT.COMPANY, C.COMPANYCODE, C.COMPANYNAME, CFT.BUSINESSUNIT, 
                       BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, CFT.DOCNUMBER, TO_CHAR(CFT.DOCDATE, 'MM/DD/YYYY') AS DOCDATE, 
                       TO_CHAR(CFT.BASELINEDATE, 'MM/DD/YYYY') AS BASELINEDATE, 
                       CFT.PAYTERM, TO_CHAR(CFT.DUEDATE, 'MM/DD/YYYY') AS DUEDATE, CFT.REMARK, CFT.FAKTUR_PAJAK, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPN, 0)) AS AMOUNT_SOURCE, 
                       NVL(CFT.AMOUNT_PPN, 0) AS AMOUNT_PPN, NVL(CFT.AMOUNT_PPH, 0) AS AMOUNT_PPH, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_TOTAL,
                       NVL(FF.JML, 0) AS FORECAST, NVL(FF.AMOUNTFORECAST, 0) AS AMOUNTFORECAST, case
        when CFT.DOCNUMBER like 'TMPINV%' then mat2.id
        else CFD.MATERIAL
        end as material,
       case
        when CFT.DOCNUMBER like 'TMPINV%' then mat2.fccode
        else MT.FCCODE
        end as MATERIALCODE,        
       case
        when CFT.DOCNUMBER like 'TMPINV%' then mat2.fcname
        else MT.FCname
        end as MATERIALNAME
                  FROM $this->CF_TRANSACTION CFT
                  LEFT JOIN $this->CF_TRANSACTION_DET CFD
                         ON CFD.ID = CFT.ID
                  LEFT JOIN $this->MATERIAL MT
                         ON MT.ID = CFD.MATERIAL
                       AND MT.EXTSYSTEM = CFT.EXTSYS
                 INNER JOIN $this->CF_TRANSACTION CFPO
                         ON CFPO.COMPANY = CFT.COMPANY
                        AND CFPO.DOCNUMBER = CFT.DOCREF
                 INNER JOIN $this->COMPANY C 
                         ON C.ID = CFT.COMPANY
                 INNER JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                  LEFT JOIN (SELECT CFT.ID, COUNT(*) AS JML, SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)) AS AMOUNTFORECAST
                               FROM (SELECT CFT.ID, CFPO.ID AS PARENTID
                                       FROM $this->CF_TRANSACTION CFT
                                      INNER JOIN $this->CF_TRANSACTION CFPO
                                              ON CFPO.DOCNUMBER = CFT.DOCREF
                                             AND CFPO.COMPANY = CFT.COMPANY
                                             AND CFPO.DOCTYPE <> 'PDO'
                                      WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                        AND CFPO.ID = ?
                                      UNION ALL
                                     SELECT CFT.ID, CFT.ID AS PARENTID
                                       FROM $this->CF_TRANSACTION CFT
                                      WHERE CFT.DOCTYPE = 'PDO'
                                        AND CFT.ID = ?) CFT
                              INNER JOIN $this->FORECAST_FIX FF
                                      ON FF.CFTRANSID = CFT.ID
                              GROUP BY CFT.ID) FF
                         ON FF.ID = CFT.ID
                         inner join (
        select id,fccode,fcname,EXTSYSTEM from material where fccode like 'INV%'
        ) mat2
        on (cfpo.doctype = substr(mat2.fccode,5,99) AND mat2.EXTSYSTEM = CFT.EXTSYS)
                 WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                   AND CFPO.ID = ?";
        $result = $this->db->query($SQL, [$param["ID"], $param["ID"], $param["ID"]])->result();
        // $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function GetData($param) {
        $SQL = "SELECT CFT.ID, CFT.DEPARTMENT, DP.FCNAME AS DEPARTMENTNAME, CFT.COMPANY, C.COMPANYCODE, C.COMPANYNAME, CFT.BUSINESSUNIT, BS.FCCODE AS BUSINESSUNITCODE, 
                       BS.FCNAME AS BUSINESSUNITNAME, CFT.DOCTYPE, CFT.DOCNUMBER, TO_CHAR(CFT.DOCDATE,'MM/DD/YYYY') AS DOCDATE, CFT.VENDOR, S.FCCODE AS VENDORCODE, 
                       S.FCNAME AS VENDORNAME, CFT.CURRENCY, CFT.INVOICEVENDORNO,
                       CFT.EXTSYS, CFT.VAT, CFT.REMARK, DECODE(CFT.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT * 100/110), CFT.AMOUNT_INCLUDE_VAT) AS AMOUNT_SOURCE, 
                       (CFT.AMOUNT_INCLUDE_VAT - DECODE(CFT.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT * 100/110), CFT.AMOUNT_INCLUDE_VAT)) AS AMOUNT_PPN, 
                       NVL(CFT.AMOUNT_PPH, 0) AS AMOUNT_PPH, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_TOTAL,
                       NVL(FF.JML, 0) AS FORECAST
                  FROM $this->CF_TRANSACTION CFT
                  LEFT JOIN $this->DEPARTMENT DP
                         ON DP.FCCODE = CFT.DEPARTMENT
                 INNER JOIN $this->COMPANY C 
                         ON C.ID = CFT.COMPANY
                 INNER JOIN $this->BUSINESSUNIT BS
                         ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                  LEFT JOIN $this->SUPPLIER S
                         ON S.ID = CFT.VENDOR
                  LEFT JOIN (SELECT CFT.PARENTID, COUNT(*) AS JML
                               FROM (SELECT CFT.ID, CFPO.ID AS PARENTID
                                       FROM $this->CF_TRANSACTION CFT
                                      INNER JOIN $this->CF_TRANSACTION CFPO
                                              ON CFPO.DOCNUMBER = CFT.DOCREF
                                             AND CFPO.COMPANY = CFT.COMPANY
                                             AND CFPO.DOCTYPE <> 'PDO'
                                      WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                        AND CFPO.ID = ?
                                      UNION ALL
                                     SELECT CFT.ID, CFT.ID AS PARENTID
                                       FROM $this->CF_TRANSACTION CFT
                                      WHERE CFT.DOCTYPE = 'PDO'
                                        AND CFT.ID = ?) CFT
                              INNER JOIN $this->FORECAST_FIX FF
                                      ON FF.CFTRANSID = CFT.ID
                              GROUP BY CFT.PARENTID) FF
                         ON FF.PARENTID = CFT.ID
                 WHERE CFT.ID = ?";
        $result = $this->db->query($SQL, [$param["ID"], $param["ID"], $param["ID"]])->row();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function GetVendor($param) {
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        $param["SVENDOR"] = "%" . strtoupper($param["SVENDOR"]) . "%";
        $SQL = "(SELECT S.ID, S.FCCODE, S.FCNAME
                   FROM SUPPLIER S
                  WHERE S.ISACTIVE = 'TRUE'
                    AND (UPPER(S.FCCODE) LIKE ?
                     OR UPPER(S.FCNAME) LIKE ?))";
        $ParamW = [$param["SVENDOR"], $param["SVENDOR"]];
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
            $SQLO = " ORDER BY FC.FCNAME, FC.FCCODE";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $SQL FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY", $ParamW)->result();
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

    public function GetItem($param) {
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        $param["SITEM"] = "%" . strtoupper($param["SITEM"]) . "%";
        $SQL = "(SELECT S.ID, S.FCCODE, S.FCNAME
                   FROM MATERIAL S
                  WHERE S.ISACTIVE = 'TRUE'
                    AND S.EXTSYSTEM = ?
                    AND (UPPER(S.FCCODE) LIKE ?
                     OR UPPER(REPLACE(S.FCCODE, '.')) LIKE ?
                     OR UPPER(S.FCNAME) LIKE ?))";
        $ParamW = [$param["EXTSYSTEM"], $param["SITEM"], $param["SITEM"], $param["SITEM"]];
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
            $SQLO = " ORDER BY FC.FCNAME, FC.FCCODE";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $SQL FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY", $ParamW)->result();
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

    public function GetItemInvoice($param) {
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        $param["SITEM"] = "%" . strtoupper($param["SITEM"]) . "%";
        $SQL = "(SELECT S.ID, S.FCCODE, S.FCNAME
                   FROM MATERIAL S
                  WHERE S.ISACTIVE = 'TRUE'
                    AND S.EXTSYSTEM = ?
                    AND (UPPER(S.FCCODE) LIKE ?
                     OR UPPER(REPLACE(S.FCCODE, '.')) LIKE ?
                     OR UPPER(S.FCNAME) LIKE ?)
                    AND UPPER(S.FCCODE) LIKE '%INV%')";
        $ParamW = [$param["EXTSYSTEM"], $param["SITEM"], $param["SITEM"], $param["SITEM"]];
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
            $SQLO = " ORDER BY FC.FCNAME, FC.FCCODE";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $SQL FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY", $ParamW)->result();
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

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $resultFinal = FALSE;
            $result = FALSE;
            $result2 = FALSE;
            $result3 = FALSE;
            if ($Data['ROLEACCESS'] != '100001' && $Data['ROLEACCESS'] != '100002') {
                $SQL = "SELECT * FROM $this->CF_TRANSACTION WHERE ID <> ? AND COMPANY = ? AND DOCNUMBER = ?";
                $Cek = $this->db->query($SQL, [$Data['ID'], $Data['COMPANY'], $Data['DOCNUMBER']]);

                if ($Cek->num_rows() > 0) {
                    throw new Exception('Data Already Exists !!');
                }

                $dt = [
                    "DEPARTMENT" => $Data["DEPARTMENT"],
                    "COMPANY" => $Data["COMPANY"],
                    "BUSINESSUNIT" => $Data["BUSINESSUNIT"],
                    "DOCNUMBER" => $Data["DOCNUMBER"],
                    "DOCTYPE" => $Data["DOCTYPE"],
                    "VENDOR" => $Data["VENDOR"],
                    "CURRENCY" => $Data["CURRENCY"],
                    "EXTSYS" => $Data["EXTSYS"],
                    "VAT" => $Data["VAT"],
                    "REMARK" => $Data["REMARK"],
                    "INVOICEVENDORNO" => $Data["INVOICEVENDORNO"],
                    "AMOUNT_INCLUDE_VAT" => (floatval($Data["AMOUNT_SOURCE"]) + floatval($Data["AMOUNT_PPN"])),
                    "AMOUNT_PPH" => $Data["AMOUNT_PPH"],
                    "FCEDIT" => $Data["USERNAME"],
                    "FCIP" => $Location
                ];
                $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                        ->set('DOCDATE', "TO_DATE('" . $Data["DOCDATE"] . "','MM/DD/YYYY')", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                if ($Data["ACTION"] == "ADD") {
                    $Data["ID"] = $this->uuid->v4();
                    $dt["ID"] = $Data["ID"];
                    $dt["ISACTIVE"] = "TRUE";
                    $dt["FCENTRY"] = "USERNAME";
                    $result = $result->set($dt)->insert($this->CF_TRANSACTION);
                } else {
                    $result = $result->set($dt)
                            ->where(["ID" => $Data["ID"]])
                            ->update($this->CF_TRANSACTION);
                    $this->db->delete($this->CF_TRANSACTION_DET, ["ID" => $Data["ID"]]);
                }
                foreach ($Data["DtPoDetail"] as $value) {
                    $dt = [
                        "ID" => $Data["ID"],
                        'MATERIAL' => $value["MATERIAL"],
                        'REMARKS' => $value["REMARKS"],
                        'AMOUNT_INCLUDE_VAT' => (floatval($value["AMOUNT_SOURCE"]) + floatval($value["AMOUNT_PPN"])),
                        'AMOUNT_PPH' => 0,
                        "ISACTIVE" => "TRUE",
                        "FCENTRY" => $Data["USERNAME"],
                        "FCEDIT" => $Data["USERNAME"],
                        "FCIP" => $Location
                    ];
                    $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                    ->set($dt)->insert($this->CF_TRANSACTION_DET);
                }
            }
            if ($Data['ROLEACCESS'] == '100001' || $Data['ROLEACCESS'] == '100002' || $Data['ROLEACCESS'] == '100005') {
                if (($Data["AMOUNT_PPH"] != '' && $Data["AMOUNT_PPH"] != NULL) && $Data["AMOUNT_PPH"] != 0) {
                    $dt = [
                        "AMOUNT_PPH" => $Data["AMOUNT_PPH"]
                    ];
                    $result = $this->db->set($dt)
                            ->where(["ID" => $Data["ID"]])
                            ->update($this->CF_TRANSACTION);
                }

                // $SQL = "DELETE FROM $this->CF_TRANSACTION_DET 
                //          WHERE ID IN (SELECT ID
                //                         FROM $this->CF_TRANSACTION
                //                        WHERE DOCREF = ?
                //                          AND COMPANY = ?)";
                // $this->db->query($SQL, [$Data["DOCNUMBER"], $Data["COMPANY"]]);
                // $SQL = "UPDATE $this->CF_TRANSACTION 
                //            SET ISACTIVE = 'FALSE'
                //          WHERE DOCREF = ?
                //            AND COMPANY = ?";
                // $this->db->query($SQL, [$Data["DOCNUMBER"], $Data["COMPANY"]]);
                if ($Data["DtInvoice"] == "0") {
                    $result3 = TRUE;
                } else {
                    foreach ($Data["DtInvoice"] as $value) {
                        $SQL = "SELECT * FROM $this->CF_TRANSACTION WHERE ID <> ? AND COMPANY = ? AND DOCNUMBER = ? AND DOCREF = ?";
                        $Cek = $this->db->query($SQL, [$value["ID"], $Data["COMPANY"], $value["DOCNUMBER"], $Data["DOCNUMBER"]]);
                        if ($Cek->num_rows() > 0) {
                            $cekDocnum = $value['DOCNUMBER'];
                            $cekDocref = $Data['DOCNUMBER'];
                            throw new Exception("Data Invoice $cekDocnum - $cekDocref Already Exists !!");
                        }
                        $DOCTYPE = "INV";
                        if ($Data["DOCTYPE"] == "SO") {
                            $DOCTYPE = "INV_AR";
                        }
                        $dt = [
                            "DEPARTMENT" => $value["DEPARTMENT"],
                            "COMPANY" => $Data["COMPANY"],
                            "BUSINESSUNIT" => $Data["BUSINESSUNIT"],
                            "DOCNUMBER" => $value["DOCNUMBER"],
                            "DOCREF" => $Data["DOCNUMBER"],
                            "DOCTYPE" => $DOCTYPE,
                            "VENDOR" => $value["VENDOR"],
                            "PAYTERM" => $value["PAYTERM"],
                            "REMARK" => $value["REMARK"],
                            "FAKTUR_PAJAK" => $value["FAKTUR_PAJAK"],
                            "AMOUNT_INCLUDE_VAT" => (floatval($value["AMOUNT_SOURCE"]) + floatval($value["AMOUNT_PPN"])),
                            "AMOUNT_PPN" => $value["AMOUNT_PPN"],
                            "AMOUNT_PPH" => $value["AMOUNT_PPH"],
                            "CURRENCY"   => $value["CURRENCY"],
                            "INVOICEVENDORNO" => $value['INVOICEVENDORNO'],
                            "ISACTIVE" => 'TRUE',
                            "FCEDIT" => $Data["USERNAME"],
                            "FCIP" => $Location
                        ];
                        $result3 = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set("DOCDATE", "TO_DATE('" . $value["DOCDATE"] . "','MM/DD/YYYY')", false)
                                ->set("BASELINEDATE", "TO_DATE('" . $value["BASELINEDATE"] . "','MM/DD/YYYY')", false)
                                ->set("DUEDATE", "TO_DATE('" . $value["DUEDATE"] . "','MM/DD/YYYY')", false)
                                ->set("LASTTIME", "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        if ($value["ID"] == "" || $value["ID"] == NULL) {
                            $value["ID"] = $this->uuid->v4();
                            $dt["ID"] = $value["ID"];
                            $dt["EXTSYS"] = $Data["EXTSYS"];
                            $dt["VAT"] = "0";
                            $dt["FCENTRY"] = "USERNAME";
                            $result3 = $result3->set($dt)->insert($this->CF_TRANSACTION);
                        } else {
                            $result3 = $result3->set($dt)
                                    ->where(["ID" => $value["ID"]])
                                    ->update($this->CF_TRANSACTION);
                            $this->db->delete($this->CF_TRANSACTION_DET, ["ID" => $value["ID"]]);
                        }
                        $dtdetail = [
                            "ID" => $value["ID"],
                            'MATERIAL' => $value["MATERIAL"],
                            'REMARKS' => $value["REMARK"],
                            'AMOUNT_INCLUDE_VAT' => (floatval($value["AMOUNT_SOURCE"]) + floatval($value["AMOUNT_PPN"])),
                            'AMOUNT_PPH' => 0,
                            "ISACTIVE" => "TRUE",
                            "FCENTRY" => $Data["USERNAME"],
                            "FCEDIT" => $Data["USERNAME"],
                            "FCIP" => $Location
                        ];
                        $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set("LASTTIME", "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                ->set($dtdetail)->insert($this->CF_TRANSACTION_DET);
                    }
                }
                // $SQL = "DELETE FROM $this->CF_TRANSACTION_DET 
                //          WHERE ID IN (SELECT ID
                //                         FROM $this->CF_TRANSACTION
                //                        WHERE DOCREF = ?
                //                          AND COMPANY = ?
                //                          AND ISACTIVE = 'FALSE')";
                // $this->db->query($SQL, [$Data["DOCNUMBER"], $Data["COMPANY"]]);
                
                // $SQL = "DELETE FROM $this->CF_TRANSACTION
                //          WHERE DOCREF = ?
                //            AND COMPANY = ?
                //            AND ISACTIVE = 'FALSE'";
                // $this->db->query($SQL, [$Data["DOCNUMBER"], $Data["COMPANY"]]);
            }

            if ($Data['ROLEACCESS'] != '100001' && $Data['ROLEACCESS'] != '100002' && $Data['ROLEACCESS'] != '100005') {
                if ($result && $result2) {
                    $resultFinal = TRUE;
                }
            } elseif ($Data['ROLEACCESS'] == '100001' || $Data['ROLEACCESS'] == '100002') {
                if ($result3) {
                    $resultFinal = TRUE;
                }
            } else {
                if ($result && $result2 && $result3) {
                    $resultFinal = TRUE;
                }
            }
            if ($resultFinal) {
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

    public function Delete($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "DELETE FROM $this->CF_TRANSACTION_DET CFD WHERE CFD.ID IN (SELECT CFT.ID FROM $this->CF_TRANSACTION CFT WHERE CFT.DOCREF = ?)";
            $this->db->query($SQL, [$Data['DOCNUMBER']]);
            $this->db->delete($this->CF_TRANSACTION, ['ID' => $Data['ID']]);
            $DeletePODetail = $this->db->delete($this->CF_TRANSACTION_DET, ['ID' => $Data['ID']]);
            $DeletePOHeader = $this->db->delete($this->CF_TRANSACTION, ['ID' => $Data['ID']]);

            if ($DeletePODetail && $DeletePOHeader) {
                $result = TRUE;
            }
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
            $SQL = "SELECT CFT.ID, CFT.DEPARTMENT, DP.FCNAME AS DEPARTMENTNAME, CFT.COMPANY, C.COMPANYCODE, C.COMPANYNAME, CFT.BUSINESSUNIT, BS.FCCODE AS BUSINESSUNITCODE, 
                           BS.FCNAME AS BUSINESSUNITNAME, CFT.DOCTYPE, CFT.DOCNUMBER, TO_CHAR(CFT.DOCDATE,'MM/DD/YYYY') AS DOCDATE, CFT.VENDOR, S.FCNAME AS VENDORNAME, CFT.CURRENCY, 
                           CFT.AMOUNT_INCLUDE_VAT, CFT.AMOUNT_PPH, CFT.VAT, CFT.TOTAL_BAYAR
                      FROM $this->CF_TRANSACTION CFT
                      LEFT JOIN $this->DEPARTMENT DP
                             ON DP.FCCODE = CFT.DEPARTMENT
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
                    WHERE (CFT.DOCTYPE <> 'INV' AND CFT.DOCTYPE <> 'INV_AR')
                      AND CFT.ISACTIVE = 'TRUE'
                      AND TO_CHAR(CFT.DOCDATE, 'YYYYMMDD') BETWEEN ? AND ?";
            $ParamW = [$Data["USERNAME"], $Data["DOCDATEFROM"], $Data["DOCDATETO"]];
            if ($Data["DEPARTMENT"] == "ALL") {
                $FDepartment = "Department : All Department";
            } else {
                $FDepartment = "Department : " . $Data["DEPARTMENT"];
                $SQL .= " AND CFT.DEPARTMENT = ?";
                array_push($ParamW, $Data["DEPARTMENT"]);
            }
            $SQL .= " ORDER BY CFT.DEPARTMENT, CFT.DOCTYPE, CFT.DOCDATE, C.COMPANYNAME, CFT.DOCNUMBER, BS.FCNAME";
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
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'SOURCE');
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'PPN');
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'PPH');
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'TOTAL');
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

}
