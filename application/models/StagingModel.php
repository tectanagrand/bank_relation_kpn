<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StagingModel extends BaseModel {

	public function ShowStagingData($param){
        
        $COMPANY      	= $param['COMPANY'];
        $DOCNUMBER      = $param['DOCNUMBER'];
        // $YEAR     		= $param['YEAR'];
        $BUSINESSUNIT   = $param['BUSINESSUNIT'];

        $WHERE = "";
        if(!empty($COMPANY)) {
            $WHERE .= " AND STG.COMPANY = '$COMPANY'";
        }
        if(!empty($BUSINESSUNIT)){
            $WHERE .= " AND STG.BUSINESSUNIT = '$BUSINESSUNIT'";
        }
        // if(!empty($YEAR)){
        //     $WHERE .= " AND SUBSTR (STG.DOCDATE, 1, 4) = '$YEAR'";
        // }
        if(!empty($DOCNUMBER)){
            $WHERE .= " AND STG.DOCNUMBER = '$DOCNUMBER'";
        }


        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        $query  = "( SELECT EXTSYS,
                 COMPANY,
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 MAX(BUSINESSUNIT)BUSINESSUNIT,
                 MAX(EXTSYSBUSINESSUNITCODE)EXTSYSBUSINESSUNITCODE,
                 DEPARTMENT,
                 DOCTYPE,
                 MAX (DOCNUMBER) DOCNUMBER, 
                 TO_DATE (DOCDATE, 'yyyymmdd') DOCDATE,
                 SUBSTR (DOCDATE, 1, 4) year,
                 DOCREF,
                 MAX(VENDOR)VENDOR,
                 VENDORNAME,
                 TRANS_LOC,
                 BASELINEDATE,
                 IS_NUMBER (SUBSTR (PAYTERM, 2)) PAYTERM,
                 TO_DATE (
                    CASE
                       WHEN     BASELINEDATE IS NULL
                            AND IS_NUMBER (SUBSTR (PAYTERM, 2)) IS NULL
                       THEN
                          NULL
                       ELSE
                          BASELINEDATE + IS_NUMBER (SUBSTR (PAYTERM, 2))
                    END,
                    'mm/dd/yyyy')
                    DUEDATE,
                 REMARK,
                 AMOUNT_PPH,
                 ISACTIVE,
                 FCENTRY,
                 FCEDIT,
                 FCIP,
                 LASTUPDATE,
                 LASTTIME,
                 UPLOAD_REF,
                 CASE WHEN SUM (AMOUNT_PPN) > 0 THEN 10 ELSE 0 END VAT,
                 FAKTUR_PAJAK,
                 (SUM (AMOUNT_INCLUDE_VAT) + SUM (AMOUNT_PPN)) AMOUNT_INCLUDE_VAT,
                 (SUM (AMOUNT_INCLUDE_VAT) + SUM (AMOUNT_PPN) - AMOUNT_PPH) TOTAL_BAYAR,
                 CURRENCY,
                 SUM (AMOUNT_PPN) AMOUNT_PPN,
                 ISADENDUM,
                 RATE,
                 INVOICEVENDORNO
            FROM ( ";
        $query .= " SELECT
                         STG.EXTSYS,
                         CE.COMPANY,
                         CO.COMPANYNAME,
                         CE.EXTSYSCOMPANYCODE,
                         BE.BUSINESSUNIT,
                         BE.EXTSYSBUSINESSUNITCODE,
                         DP.DEPARTMENT,
                         DOCTYPE,
                         STG.DOCNUMBER,
                         STG.DOCITEM,
                         STG.DOCDATE,
                         DOCREF,
                         SP.ID AS VENDOR,
                         SP.FCNAME AS VENDORNAME,
                         NULL TRANS_LOC,
                         DP.PURCHORG,
                         BASELINEDATE,
                         CASE
                            WHEN REGEXP_LIKE (SUBSTR (STG.PAYTERM, 2), '^\d+(\.\d+)?$')
                            THEN
                               SUBSTR (STG.PAYTERM, 2)
                            ELSE
                               '0'
                         END
                            PAYTERM,
                         DUEDATE,
                         NULL REMARK,
                         NVL (STG.AMOUNT_INCLUDE_VAT, 0) AMOUNT_INCLUDE_VAT,
                         0 AMOUNT_PPH,
                         'TRUE' ISACTIVE,
                         'SAPBRIDGE' FCENTRY,
                         'SAPBRIDGE' FCEDIT,
                         '172.0.0' FCIP,
                         SYSDATE LASTUPDATE,
                         '00:00' LASTTIME,
                         NULL UPLOAD_REF,
                         0 VAT,
                         NULL FAKTUR_PAJAK,
                         0 TOTAL_BAYAR,
                         STG.CURRENCY,
                         NVL (AMOUNT_PPN, 0) AMOUNT_PPN,
                         NULL ISADENDUM,
                         1 RATE,
                         NULL INVOICEVENDORNO
                    FROM CF_TRANS@dblink_staging STG ";
        $query .= " INNER JOIN COMPANY_EXTSYS CE
                            ON     STG.COMPANY = CE.EXTSYSCOMPANYCODE
                               AND CE.EXTSYSTEM = 'SAPHANA'
                         INNER JOIN COMPANY CO ON CE.COMPANY = CO.ID
                         INNER JOIN BUSINESSUNIT_EXTSYS BE
                            ON     STG.BUSINESSUNIT = BE.EXTSYSBUSINESSUNITCODE
                               AND BE.EXTSYSTEM = 'SAPHANA'
                         INNER JOIN BUSINESSUNIT bu ON (BE.businessunit = bu.id)
                         LEFT JOIN DEPARTMENT_PURCHORG DP
                            ON (    BU.FCCODE = DP.BUSINESSUNIT
                                AND STG.DEPARTMENT = DP.PURCHORG)
                         INNER JOIN SUPPLIER SP ON STG.vendor = SP.fccode
                         INNER JOIN DOCTYPE DT ON (DT.FCCODE = STG.DOCTYPE) ";
        $query .= " WHERE     STG.ISRETRIVEDBYCF IS NULL
                         AND STG.ISACTIVE = 'TRUE'
                         AND STG.DOCTYPE not in ('STO','SO','PO')
                         AND STG.AMOUNT_INCLUDE_VAT > 0 AND ce.company || STG.doctype || STG.docnumber NOT IN
                              (SELECT ce.company || doctype || docnumber
                                 FROM cf_transaction where DOCTYPE not in ('STO','SO','PO')) ".$WHERE. " ) ";
        $query .= " WHERE     DEPARTMENT IS NOT NULL GROUP BY EXTSYS,
                 COMPANY,
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 DEPARTMENT,
                 DOCTYPE, 
                 TO_DATE (DOCDATE, 'yyyymmdd'),
                 SUBSTR (DOCDATE, 1, 4),
                 DOCREF, 
                 TRANS_LOC,
                 VENDORNAME,
                 BASELINEDATE,
                 PAYTERM,
                 DUEDATE,
                 REMARK,
                 AMOUNT_PPH,
                 ISACTIVE,
                 FCENTRY,
                 FCEDIT,
                 FCIP,
                 LASTUPDATE,
                 LASTTIME,
                 UPLOAD_REF,
                 VAT,
                 FAKTUR_PAJAK,
                 CURRENCY,
                 ISADENDUM,
                 RATE,
                 INVOICEVENDORNO ) ";

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
            $SQLO = " ORDER BY LASTUPDATE DESC";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $query FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
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

    public function saveStaging($param,$Location){
        // echo "<pre>";
        // var_dump($param);exit();
        
        try {
            foreach($param['dtPurch'] AS $key => $row) {
                $this->db->trans_begin();
                // var_dump($row->EXTSYS);exit; 
                $result = FALSE;
                $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
                $USERNAME     = $this->session->userdata('username');

                $cf = [
                        "EXTSYS"     => $row['EXTSYS'],
                        "COMPANY" => $row['COMPANY'],
                        "DEPARTMENT" => $row['DEPARTMENT'],
                        "BUSINESSUNIT" => $row['BUSINESSUNIT'],
                        "DOCTYPE" => $row['DOCTYPE'],
                        "DOCNUMBER" => $row['DOCNUMBER'],
                        // "DOCDATE" => date('m/d/Y',strtotime($row['DOCDATE'])),
                        // "YEAR" => $row['YEAR'],
                        "DOCREF" => $row['DOCREF'],
                        "VENDOR" => $row['VENDOR'],
                        "TRANS_LOC" => $row['TRANS_LOC'],
                        "BASELINEDATE" => $row['BASELINEDATE'],
                        "PAYTERM" => $row['PAYTERM'],
                        "DUEDATE" => $row['DUEDATE'],
                        "REMARK" => $row['REMARK'],
                        "AMOUNT_PPH" => $row['AMOUNT_PPH'],
                        "ISACTIVE" => $row['ISACTIVE'],
                        "FCENTRY" => $USERNAME,
                        "FCEDIT" => $USERNAME,
                        "FCIP" => $Location,
                        "UPLOAD_REF" => $row['UPLOAD_REF'],
                        "VAT" => $row['VAT'],
                        "FAKTUR_PAJAK" => $row['FAKTUR_PAJAK'],
                        "AMOUNT_INCLUDE_VAT" => $row['AMOUNT_INCLUDE_VAT'],
                        "TOTAL_BAYAR" => $row['TOTAL_BAYAR'],
                        "CURRENCY" => $row['CURRENCY'],
                        "TOTAL_BAYAR" => $row['AMOUNT_PPN'],
                        "ISADENDUM" => $row['ISADENDUM'],
                        "RATE" => $row['RATE'],
                        "INVOICEVENDORNO" => $row['INVOICEVENDORNO']
                    ];   
                $DOCDATE = date('m/d/Y',strtotime($row['DOCDATE']));

                $resultcf = $this->db->set('LASTUPDATE', "SYSDATE", false)
                ->set('DOCDATE', "TO_DATE('".$DOCDATE."','mm-dd-yyyy')", false)
                ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                $UUID = $this->uuid->v4();
                $cf["ID"]   = $UUID;
                $resultcf = $resultcf->set($cf)->insert($this->CF_TRANSACTION);
                
                if ($resultcf) {
                    $type = 'SPO';
                    $isretrieved = $this->isRetrieved($row['DOCNUMBER'],$row['EXTSYSCOMPANYCODE'],$row['EXTSYSBUSINESSUNITCODE'],$type);
                    $getMaterial = $this->funcMaterial($row['DOCNUMBER'],$type);

                    $cf_det = [
                                "ID" => $UUID,
                                'MATERIAL' => $getMaterial->MATERIAL,
                                'REMARKS' => $row['REMARK'],
                                'AMOUNT_INCLUDE_VAT' => $row['AMOUNT_INCLUDE_VAT'],
                                'AMOUNT_PPH' => $row['AMOUNT_PPH'],
                                "ISACTIVE" => "TRUE",
                                "FCENTRY" => $USERNAME,
                                "FCEDIT" => $USERNAME,
                                "FCIP" => $Location
                            ];
                    $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                    ->set($cf_det)->insert($this->CF_TRANSACTION_DET);
                    if ($resultcf) {
                        $result = TRUE;
                    }
                }
                if ($result) {
                    $this->db->trans_commit();
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => 'Data has been Successfully Saved !!'
                    ];
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Data Save Failed !!');
                }

                    
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

    public function funcMaterial($docnumber,$type){
        if($type == 'SPO'){
            $query = "SELECT DISTINCT ID,
                       EXTSYS,
                       DEPARTMENT,
                       DOCTYPE,
                       DOCNUMBER,
                       DOCITEM,
                       TO_DATE (DOCDATE, 'yyyymmdd') DOCDATE,
                       SUBSTR (DOCDATE, 1, 4) YEAR,
                       MATERIAL,
                       fcname,
                       AMOUNT_INCLUDE_VAT,
                       AMOUNT_PPH,
                       ISACTIVE,
                       FCENTRY,
                       FCEDIT,
                       FCIP,
                       LASTUPDATE,
                       LASTTIME
                    FROM (SELECT CFT.ID,
                               STG.EXTSYS,
                               STG.DEPARTMENT,
                               STG.DOCTYPE,
                               STG.DOCITEM,
                               STG.DOCNUMBER,
                               STG.DOCDATE,
                               BU.FCTYPE,
                               MATERIAL_API.GET_ID_SPO (DMS.DEPARTMENT, DMS.BUTYPE) asd,
                               CASE
                                  WHEN STG.DOCTYPE = 'SPO'
                                  THEN
                                     MATERIAL_API.GET_ID (
                                        STG.EXTSYS,
                                        MATERIAL_API.GET_ID_SPO (DMS.DEPARTMENT, DMS.BUTYPE))
                                  ELSE
                                     CASE WHEN MT.ID IS NULL THEN STG.MATERIAL ELSE MT.ID END
                               END
                                  MATERIAL,
                                  mt.fcname,
                               (NVL (STG.AMOUNT_INCLUDE_VAT, 0) + NVL (STG.AMOUNT_PPN, 0)) AMOUNT_INCLUDE_VAT,
                               NVL (CFT.AMOUNT_PPH, 0) AMOUNT_PPH,
                               'TRUE' ISACTIVE,
                               'SAPBRIDGE' FCENTRY,
                               'SAPBRIDGE' FCEDIT,
                               '172.0.0' FCIP,
                               SYSDATE LASTUPDATE,
                               '00:00' LASTTIME
                          FROM CF_TRANS@DBLINK_STAGING STG
                               INNER JOIN COMPANY_EXTSYS CE
                                  ON     STG.COMPANY = CE.EXTSYSCOMPANYCODE
                                     AND CE.EXTSYSTEM = 'SAPHANA'
                               INNER JOIN BUSINESSUNIT_EXTSYS BE
                                  ON     STG.BUSINESSUNIT = BE.EXTSYSBUSINESSUNITCODE
                                     AND BE.EXTSYSTEM = 'SAPHANA'
                               INNER JOIN BUSINESSUNIT BU
                                  ON     BE.BUSINESSUNIT = BU.ID 
                               INNER JOIN DEPARTMENT_PURCHORG DP
                                  ON (    BU.FCCODE = DP.BUSINESSUNIT
                                      AND DP.PURCHORG = STG.DEPARTMENT)
                               INNER JOIN DEPARTMENT DT ON (DP.DEPARTMENT = DT.FCCODE)
                               INNER JOIN DEPARTMENT_MATERIAL_SPO DMS
                                  ON (DMS.DEPARTMENT = DT.FCCODE AND DMS.BUTYPE = BU.FCTYPE)
                               LEFT JOIN MATERIAL MT
                                  ON (MT.FCCODE = DMS.MATERIAL AND MT.EXTSYSTEM = 'SAPHANA')
                               INNER JOIN CF_TRANSACTION CFT
                                  ON (    CFT.DOCNUMBER = STG.DOCNUMBER
                                      AND CFT.EXTSYS = 'SAPHANA')
                               INNER JOIN DOCTYPE DT ON (DT.FCCODE = STG.DOCTYPE)
                         WHERE     CFT.ID IS NOT NULL 
                               AND STG.DOCTYPE not in ('STO','SO','PO')
                               AND STG.AMOUNT_INCLUDE_VAT > 0
                               AND STG.ISACTIVE = 'TRUE'
                               AND STG.DOCNUMBER = '".$docnumber."'
                               ) WHERE MATERIAL IS NOT NULL";
        }else{
            $query = "SELECT ID,
                       EXTSYS,
                       DEPARTMENT,
                       DOCTYPE,
                       DOCNUMBER,
                       DOCITEM,
                       TO_DATE (DOCDATE, 'yyyymmdd') DOCDATE,
                       SUBSTR (DOCDATE, 1, 4) YEAR,
                       MATERIAL_ORI,
                       MATERIAL,
                       MATERIAL_NEW,
                       AMOUNT_INCLUDE_VAT,
                       AMOUNT_PPH,
                       ISACTIVE,
                       FCENTRY,
                       FCEDIT,
                       FCIP,
                       LASTUPDATE,
                       LASTTIME
                  FROM (SELECT CFT.ID,
                               STG.EXTSYS,
                               STG.DEPARTMENT,
                               STG.DOCTYPE,
                               STG.DOCITEM,
                               STG.DOCNUMBER,
                               STG.DOCDATE,
                               BU.FCTYPE,
                               STG.MATERIAL AS MATERIAL_ORI,
                               D.ID MATERIAL,
                               D.FCCODE AS MATERIAL_NEW,
                               (NVL (STG.AMOUNT_INCLUDE_VAT, 0) + NVL (STG.AMOUNT_PPN, 0))
                                  AMOUNT_INCLUDE_VAT,
                               NVL (CFT.AMOUNT_PPH, 0) AMOUNT_PPH,
                               'TRUE' ISACTIVE,
                               'SAPBRIDGE' FCENTRY,
                               'SAPBRIDGE' FCEDIT,
                               '172.0.0' FCIP,
                               SYSDATE LASTUPDATE,
                               '00:00' LASTTIME
                          FROM CF_TRANS@DBLINK_STAGING STG
                               INNER JOIN COMPANY_EXTSYS CE
                                  ON     STG.COMPANY = CE.EXTSYSCOMPANYCODE
                                     AND CE.EXTSYSTEM = 'SAPHANA'
                               INNER JOIN BUSINESSUNIT_EXTSYS BE
                                  ON     STG.BUSINESSUNIT = BE.EXTSYSBUSINESSUNITCODE
                                     AND BE.EXTSYSTEM = 'SAPHANA'
                               INNER JOIN BUSINESSUNIT BU ON BE.BUSINESSUNIT = BU.ID
                               INNER JOIN MATERIAL B
                                  ON (STG.MATERIAL = B.FCCODE AND B.EXTSYSTEM = 'SAPHANA')
                               INNER JOIN MATERIAL_GROUPITEM C
                                  ON (C.MATERIAL = B.ID AND C.EXTSYSTEM = 'SAPHANA')
                               LEFT JOIN MATERIAL D
                                  ON (C.MATERIAL_2 = D.ID AND D.EXTSYSTEM = 'SAPHANA')
                               INNER JOIN CF_TRANSACTION CFT
                                  ON (    CFT.DOCNUMBER = STG.DOCNUMBER
                                      AND CFT.EXTSYS = 'SAPHANA')
                               INNER JOIN DOCTYPE DT ON (DT.FCCODE = STG.DOCTYPE)
                         WHERE     CFT.ID IS NOT NULL
                               AND STG.DOCTYPE NOT IN ('SPO', 'SO')
                               AND STG.AMOUNT_INCLUDE_VAT > 0
                               AND STG.ISACTIVE = 'TRUE'
                               AND STG.DOCNUMBER = '$docnumber'
                       )
                 WHERE MATERIAL IS NOT NULL";
        }
        return $this->db->query($query)->row();
    }

    public function isRetrieved($docnumber,$company,$bu,$type){
        if($type == 'SPO'){
            $q = "UPDATE CF_TRANS@dblink_staging
                             SET ISRETRIVEDBYCF = 'X'
                        WHERE  DOCTYPE not in ('STO','SO','PO')
                        AND DOCNUMBER = '$docnumber'
                        AND company = '$company'
                        AND businessunit = '$bu'";    
        }else{
            $q = "UPDATE CF_TRANS@dblink_staging
                             SET ISRETRIVEDBYCF = 'X'
                        WHERE DOCTYPE not in ('SPO','SO') 
                        AND DOCNUMBER = '$docnumber'
                        AND company = '$company'
                        AND businessunit = '$bu'";
        }
        
        $this->db->query($q);
    }

    public function ShowStagingDataSTO($param){
        
        $COMPANY        = $param['COMPANY'];
        $DOCNUMBER      = $param['DOCNUMBER'];
        // $YEAR           = $param['YEAR'];
        $BUSINESSUNIT   = $param['BUSINESSUNIT'];

        $WHERE = "";
        if(!empty($COMPANY)) {
            $WHERE .= " AND STG.COMPANY = '$COMPANY'";
        }
        if(!empty($BUSINESSUNIT)){
            $WHERE .= " AND STG.BUSINESSUNIT = '$BUSINESSUNIT'";
        }
        // if(!empty($YEAR)){
        //     $WHERE .= " AND SUBSTR (STG.DOCDATE, 1, 4) = '$YEAR'";
        // }
        if(!empty($DOCNUMBER)){
            $WHERE .= " AND STG.DOCNUMBER = '$DOCNUMBER'";
        }


        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        $query  = "( SELECT EXTSYS,
                     COMPANY,
                     COMPANYNAME,
                     EXTSYSCOMPANYCODE,
                     MAX (BUSINESSUNIT) BUSINESSUNIT,
                     MAX (EXTSYSBUSINESSUNITCODE) EXTSYSBUSINESSUNITCODE,
                     DEPARTMENT,
                     DOCTYPE,
                     MAX (DOCNUMBER) DOCNUMBER,
                     TO_DATE (DOCDATE, 'yyyymmdd') DOCDATE,
                     SUBSTR (DOCDATE, 1, 4) year,
                     DOCREF,
                     VENDOR,
                     VENDORNAME,
                     TRANS_LOC,
                     BASELINEDATE,
                     IS_NUMBER (SUBSTR (PAYTERM, 2)) PAYTERM,
                     TO_DATE (
                        CASE
                           WHEN     BASELINEDATE IS NULL
                                AND IS_NUMBER (SUBSTR (PAYTERM, 2)) IS NULL
                           THEN
                              NULL
                           ELSE
                              BASELINEDATE + IS_NUMBER (SUBSTR (PAYTERM, 2))
                        END,
                        'mm/dd/yyyy')
                        DUEDATE,
                     REMARK,
                     AMOUNT_PPH,
                     ISACTIVE,
                     FCENTRY,
                     FCEDIT,
                     FCIP,
                     LASTUPDATE,
                     LASTTIME,
                     UPLOAD_REF,
                     CASE WHEN SUM (AMOUNT_PPN) > 0 THEN 10 ELSE 0 END VAT,
                     FAKTUR_PAJAK,
                     (SUM (AMOUNT_INCLUDE_VAT) + SUM (AMOUNT_PPN)) AMOUNT_INCLUDE_VAT,
                     (SUM (AMOUNT_INCLUDE_VAT) + SUM (AMOUNT_PPN) - AMOUNT_PPH) TOTAL_BAYAR,
                     CURRENCY,
                     SUM (AMOUNT_PPN) AMOUNT_PPN,
                     ISADENDUM,
                     RATE,
                     INVOICEVENDORNO,
                     CASE WHEN DOCTYPE = 'STO' THEN 1 ELSE 0 END DOC_ITEM
            FROM ( ";
        $query .= " SELECT
                     STG.EXTSYS,
                     CE.COMPANY,
                     CO.COMPANYNAME,
                     CE.EXTSYSCOMPANYCODE,
                     BE.BUSINESSUNIT,
                     BE.EXTSYSBUSINESSUNITCODE,
                     DP.DEPARTMENT,
                     DOCTYPE,
                     STG.DOCNUMBER,
                     STG.DOCITEM,
                     STG.DOCDATE,
                     DOCREF,
                     SP.ID AS VENDOR,
                     SP.FCNAME AS VENDORNAME,
                     NULL TRANS_LOC,
                     DP.PURCHORG,
                     BASELINEDATE,
                     CASE
                        WHEN REGEXP_LIKE (SUBSTR (STG.PAYTERM, 2), '^\d+(\.\d+)?$')
                        THEN
                           SUBSTR (STG.PAYTERM, 2)
                        ELSE
                           '0'
                     END
                        PAYTERM,
                     DUEDATE,
                     NULL REMARK,
                     NVL (STG.AMOUNT_INCLUDE_VAT, 0) AMOUNT_INCLUDE_VAT,
                     0 AMOUNT_PPH,
                     'TRUE' ISACTIVE,
                     'SAPBRIDGE' FCENTRY,
                     'SAPBRIDGE' FCEDIT,
                     '172.0.0' FCIP,
                     SYSDATE LASTUPDATE,
                     '00:00' LASTTIME,
                     NULL UPLOAD_REF,
                     0 VAT,
                     NULL FAKTUR_PAJAK,
                     0 TOTAL_BAYAR,
                     STG.CURRENCY,
                     NVL (AMOUNT_PPN, 0) AMOUNT_PPN,
                     NULL ISADENDUM,
                     1 RATE,
                     NULL INVOICEVENDORNO
                    FROM CF_TRANS@dblink_staging STG ";
        $query .= " INNER JOIN COMPANY_EXTSYS CE
                    ON     STG.COMPANY = CE.EXTSYSCOMPANYCODE
                       AND CE.EXTSYSTEM = 'SAPHANA'
                 INNER JOIN COMPANY CO ON CE.COMPANY = CO.ID
                 INNER JOIN BUSINESSUNIT_EXTSYS BE
                    ON     STG.BUSINESSUNIT = BE.EXTSYSBUSINESSUNITCODE
                       AND BE.EXTSYSTEM = 'SAPHANA'
                 INNER JOIN BUSINESSUNIT bu ON (BE.businessunit = bu.id)
                 LEFT JOIN DEPARTMENT_PURCHORG DP
                    ON (    BU.FCCODE = DP.BUSINESSUNIT
                        AND STG.DEPARTMENT = DP.PURCHORG)
                 INNER JOIN SUPPLIER SP ON STG.vendor = SP.fccode
                 INNER JOIN DOCTYPE DT ON (DT.FCCODE = STG.DOCTYPE) ";
        $query .= " WHERE     STG.ISRETRIVEDBYCF IS NULL
                 AND STG.ISACTIVE = 'TRUE'
                 AND STG.DOCTYPE NOT IN ('SPO', 'SO')
                 AND STG.AMOUNT_INCLUDE_VAT > 0
                 AND ce.company || STG.doctype || STG.docnumber NOT IN
                        (SELECT ce.company || doctype || docnumber
                                 FROM cf_transaction where DOCTYPE NOT IN ('SPO', 'SO')) ".$WHERE. " ) ";
        $query .= " WHERE DEPARTMENT IS NOT NULL
                 GROUP BY EXTSYS,
                 COMPANY,
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 DEPARTMENT,
                 DOCTYPE,
                 TO_DATE (DOCDATE, 'yyyymmdd'),
                 SUBSTR (DOCDATE, 1, 4),
                 DOCREF,
                 VENDOR,
                 VENDORNAME,
                 TRANS_LOC,
                 BASELINEDATE,
                 PAYTERM,
                 DUEDATE,
                 REMARK,
                 AMOUNT_PPH,
                 ISACTIVE,
                 FCENTRY,
                 FCEDIT,
                 FCIP,
                 LASTUPDATE,
                 LASTTIME,
                 UPLOAD_REF,
                 VAT,
                 FAKTUR_PAJAK,
                 CURRENCY,
                 ISADENDUM,
                 RATE,
                 INVOICEVENDORNO ) ";

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
            $SQLO = " ORDER BY LASTUPDATE DESC";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $query FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
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

    public function saveStagingSTO($param,$Location){
        // echo "<pre>";
        // var_dump($param);exit();
        
        try {
            foreach($param['dtPurch'] AS $key => $row) {
                $this->db->trans_begin();
                // var_dump($row->EXTSYS);exit; 
                $result = FALSE;
                $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
                $USERNAME     = $this->session->userdata('username');

                $cf = [
                        "EXTSYS"     => $row['EXTSYS'],
                        "COMPANY" => $row['COMPANY'],
                        "DEPARTMENT" => $row['DEPARTMENT'],
                        "BUSINESSUNIT" => $row['BUSINESSUNIT'],
                        "DOCTYPE" => $row['DOCTYPE'],
                        "DOCNUMBER" => $row['DOCNUMBER'],
                        // "DOCDATE" => date('m/d/Y',strtotime($row['DOCDATE'])),
                        // "YEAR" => $row['YEAR'],
                        "DOCREF" => $row['DOCREF'],
                        "VENDOR" => $row['VENDOR'],
                        "TRANS_LOC" => $row['TRANS_LOC'],
                        "BASELINEDATE" => $row['BASELINEDATE'],
                        "PAYTERM" => $row['PAYTERM'],
                        "DUEDATE" => $row['DUEDATE'],
                        "REMARK" => $row['REMARK'],
                        "AMOUNT_PPH" => $row['AMOUNT_PPH'],
                        "ISACTIVE" => $row['ISACTIVE'],
                        "FCENTRY" => $USERNAME,
                        "FCEDIT" => $USERNAME,
                        "FCIP" => $Location,
                        "UPLOAD_REF" => $row['UPLOAD_REF'],
                        "VAT" => $row['VAT'],
                        "FAKTUR_PAJAK" => $row['FAKTUR_PAJAK'],
                        "AMOUNT_INCLUDE_VAT" => $row['AMOUNT_INCLUDE_VAT'],
                        "TOTAL_BAYAR" => $row['TOTAL_BAYAR'],
                        "CURRENCY" => $row['CURRENCY'],
                        "TOTAL_BAYAR" => $row['AMOUNT_PPN'],
                        "ISADENDUM" => $row['ISADENDUM'],
                        "RATE" => $row['RATE'],
                        "INVOICEVENDORNO" => $row['INVOICEVENDORNO']
                    ];   
                $DOCDATE = date('m/d/Y',strtotime($row['DOCDATE']));

                $resultcf = $this->db->set('LASTUPDATE', "SYSDATE", false)
                ->set('DOCDATE', "TO_DATE('".$DOCDATE."','mm-dd-yyyy')", false)
                ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                $UUID = $this->uuid->v4();
                $cf["ID"]   = $UUID;
                $resultcf = $resultcf->set($cf)->insert($this->CF_TRANSACTION);
                
                if ($resultcf) {
                    $type = 'STO';
                    $isretrieved = $this->isRetrieved($row['DOCNUMBER'],$row['EXTSYSCOMPANYCODE'],$row['EXTSYSBUSINESSUNITCODE'],$type);

                    $getMaterial = $this->funcMaterial($row['DOCNUMBER'],$type);

                    $cf_det = [
                                "ID" => $UUID,
                                'MATERIAL' => $getMaterial->MATERIAL,
                                'REMARKS' => $row['REMARK'],
                                'AMOUNT_INCLUDE_VAT' => $row['AMOUNT_INCLUDE_VAT'],
                                'AMOUNT_PPH' => $row['AMOUNT_PPH'],
                                "ISACTIVE" => "TRUE",
                                "FCENTRY" => $USERNAME,
                                "FCEDIT" => $USERNAME,
                                "FCIP" => $Location
                            ];
                    $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                    ->set($cf_det)->insert($this->CF_TRANSACTION_DET);
                    if ($resultcf) {
                        $result = TRUE;
                    }
                }
                if ($result) {
                    $this->db->trans_commit();
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => 'Data has been Successfully Saved !!'
                    ];
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Data Save Failed !!');
                }

                    
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

    public function loadCompany(){
        $q = "SELECT DISTINCT EXTSYSCOMPANYCODE, COMPANYNAME FROM (SELECT 
                 EXTSYSCOMPANYCODE,COMPANYNAME
            FROM (SELECT
                         STG.EXTSYS,
                         CE.COMPANY,
                         CO.COMPANYNAME,
                         CE.EXTSYSCOMPANYCODE,
                         BE.BUSINESSUNIT,
                         BE.EXTSYSBUSINESSUNITCODE,
                         DP.DEPARTMENT,
                         DOCTYPE,
                         STG.DOCNUMBER,
                         STG.DOCITEM,
                         STG.DOCDATE,
                         DOCREF,
                         SP.ID AS VENDOR,
                         SP.FCNAME as VENDORNAME,
                         NULL TRANS_LOC,
                         DP.PURCHORG,
                         BASELINEDATE,
                         CASE
                            WHEN REGEXP_LIKE (SUBSTR (STG.PAYTERM, 2), '^\d+(\.\d+)?$')
                            THEN
                               SUBSTR (STG.PAYTERM, 2)
                            ELSE
                               '0'
                         END
                            PAYTERM,
                         DUEDATE,
                         NULL REMARK,
                         NVL (STG.AMOUNT_INCLUDE_VAT, 0) AMOUNT_INCLUDE_VAT,
                         0 AMOUNT_PPH,
                         'TRUE' ISACTIVE,
                         'SAPBRIDGE' FCENTRY,
                         'SAPBRIDGE' FCEDIT,
                         '172.0.0' FCIP,
                         SYSDATE LASTUPDATE,
                         '00:00' LASTTIME,
                         NULL UPLOAD_REF,
                         0 VAT,
                         NULL FAKTUR_PAJAK,
                         0 TOTAL_BAYAR,
                         STG.CURRENCY,
                         NVL (AMOUNT_PPN, 0) AMOUNT_PPN,
                         NULL ISADENDUM,
                         1 RATE,
                         NULL INVOICEVENDORNO
                    FROM CF_TRANS@dblink_staging STG
                         INNER JOIN (select COMPANY, EXTSYSCOMPANYCODE,EXTSYSTEM from COMPANY_EXTSYS where EXTSYSTEM = 'SAPHANA') CE
                              ON     STG.COMPANY = CE.EXTSYSCOMPANYCODE
                           INNER JOIN (select id,companyname from COMPANY) CO ON CE.COMPANY = CO.ID
                           INNER JOIN (select EXTSYSBUSINESSUNITCODE,businessunit from BUSINESSUNIT_EXTSYS where EXTSYSTEM = 'SAPHANA') BE
                              ON     STG.BUSINESSUNIT = BE.EXTSYSBUSINESSUNITCODE
                           INNER JOIN (select id,FCCODE from BUSINESSUNIT) bu
                              ON (BE.businessunit = bu.id)
                           LEFT JOIN (select businessunit,purchorg,department from DEPARTMENT_PURCHORG) DP
                              ON (    BU.FCCODE = DP.BUSINESSUNIT
                                  AND STG.DEPARTMENT = DP.PURCHORG)
                           INNER JOIN (select id,fccode,fcname from SUPPLIER) SP ON STG.vendor = SP.fccode
                           INNER JOIN (select fccode from DOCTYPE) DT ON (DT.FCCODE = STG.DOCTYPE)
                   WHERE     STG.ISRETRIVEDBYCF IS NULL
                         AND STG.ISACTIVE = 'TRUE'
                         AND STG.DOCTYPE not in ('STO','SO','PO')
                         AND STG.AMOUNT_INCLUDE_VAT > 0
                         AND ce.company || STG.doctype || STG.docnumber NOT IN
                              (SELECT ce.company || doctype || docnumber
                                 FROM cf_transaction)
                         )
           WHERE     DEPARTMENT IS NOT NULL
            GROUP BY EXTSYS,
                 COMPANY,
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 DEPARTMENT,
                 DOCTYPE, 
                 TO_DATE (DOCDATE, 'yyyymmdd'),
                 SUBSTR (DOCDATE, 1, 4),
                 DOCREF,
                 VENDORNAME,
                 TRANS_LOC,
                 BASELINEDATE,
                 PAYTERM,
                 DUEDATE,
                 REMARK,
                 AMOUNT_PPH,
                 ISACTIVE,
                 FCENTRY,
                 FCEDIT,
                 FCIP,
                 LASTUPDATE,
                 LASTTIME,
                 UPLOAD_REF,
                 VAT,
                 FAKTUR_PAJAK,
                 CURRENCY,
                 ISADENDUM,
                 RATE,
                 INVOICEVENDORNO ) GROUP BY EXTSYSCOMPANYCODE,COMPANYNAME ORDER BY COMPANYNAME ASC";
        return $this->db->query($q)->result();
    }

    public function loadBusinessUnit(){
        $q = "SELECT DISTINCT EXTSYSBUSINESSUNITCODE FROM (SELECT 
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 MAX(EXTSYSBUSINESSUNITCODE)EXTSYSBUSINESSUNITCODE
            FROM (SELECT
                         STG.EXTSYS,
                         CE.COMPANY,
                         CO.COMPANYNAME,
                         CE.EXTSYSCOMPANYCODE,
                         BE.BUSINESSUNIT,
                         BE.EXTSYSBUSINESSUNITCODE,
                         DP.DEPARTMENT,
                         DOCTYPE,
                         STG.DOCNUMBER,
                         STG.DOCITEM,
                         STG.DOCDATE,
                         DOCREF,
                         SP.ID AS VENDOR,
                         SP.FCNAME as VENDORNAME,
                         NULL TRANS_LOC,
                         DP.PURCHORG,
                         BASELINEDATE,
                         CASE
                            WHEN REGEXP_LIKE (SUBSTR (STG.PAYTERM, 2), '^\d+(\.\d+)?$')
                            THEN
                               SUBSTR (STG.PAYTERM, 2)
                            ELSE
                               '0'
                         END
                            PAYTERM,
                         DUEDATE,
                         NULL REMARK,
                         NVL (STG.AMOUNT_INCLUDE_VAT, 0) AMOUNT_INCLUDE_VAT,
                         0 AMOUNT_PPH,
                         'TRUE' ISACTIVE,
                         'SAPBRIDGE' FCENTRY,
                         'SAPBRIDGE' FCEDIT,
                         '172.0.0' FCIP,
                         SYSDATE LASTUPDATE,
                         '00:00' LASTTIME,
                         NULL UPLOAD_REF,
                         0 VAT,
                         NULL FAKTUR_PAJAK,
                         0 TOTAL_BAYAR,
                         STG.CURRENCY,
                         NVL (AMOUNT_PPN, 0) AMOUNT_PPN,
                         NULL ISADENDUM,
                         1 RATE,
                         NULL INVOICEVENDORNO
                    FROM CF_TRANS@dblink_staging STG
                         INNER JOIN COMPANY_EXTSYS CE
                            ON     STG.COMPANY = CE.EXTSYSCOMPANYCODE
                               AND CE.EXTSYSTEM = 'SAPHANA'
                         INNER JOIN COMPANY CO ON CE.COMPANY = CO.ID
                         INNER JOIN BUSINESSUNIT_EXTSYS BE
                            ON     STG.BUSINESSUNIT = BE.EXTSYSBUSINESSUNITCODE
                               AND BE.EXTSYSTEM = 'SAPHANA'
                         INNER JOIN BUSINESSUNIT bu ON (BE.businessunit = bu.id)
                         LEFT JOIN DEPARTMENT_PURCHORG DP
                            ON (    BU.FCCODE = DP.BUSINESSUNIT
                                AND STG.DEPARTMENT = DP.PURCHORG)
                         INNER JOIN SUPPLIER SP ON STG.vendor = SP.fccode
                         INNER JOIN DOCTYPE DT ON (DT.FCCODE = STG.DOCTYPE)
                   WHERE     STG.ISRETRIVEDBYCF IS NULL
                         AND STG.ISACTIVE = 'TRUE'
                         AND STG.DOCTYPE not in ('STO','SO','PO')
                         AND STG.AMOUNT_INCLUDE_VAT > 0
                         AND ce.company || STG.doctype || STG.docnumber NOT IN
                              (SELECT ce.company || doctype || docnumber
                                 FROM cf_transaction)
                         )
           WHERE     DEPARTMENT IS NOT NULL
        GROUP BY EXTSYS,
                 COMPANY,
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 DEPARTMENT,
                 DOCTYPE, 
                 TO_DATE (DOCDATE, 'yyyymmdd'),
                 SUBSTR (DOCDATE, 1, 4),
                 DOCREF,
                 VENDORNAME,
                 TRANS_LOC,
                 BASELINEDATE,
                 PAYTERM,
                 DUEDATE,
                 REMARK,
                 AMOUNT_PPH,
                 ISACTIVE,
                 FCENTRY,
                 FCEDIT,
                 FCIP,
                 LASTUPDATE,
                 LASTTIME,
                 UPLOAD_REF,
                 VAT,
                 FAKTUR_PAJAK,
                 CURRENCY,
                 ISADENDUM,
                 RATE,
                 INVOICEVENDORNO ) GROUP BY COMPANYNAME,EXTSYSBUSINESSUNITCODE ORDER BY EXTSYSBUSINESSUNITCODE ASC";
        return $this->db->query($q)->result();
    }

    public function loadCompanySTO(){
        $q = "SELECT DISTINCT EXTSYSCOMPANYCODE, COMPANYNAME FROM (SELECT 
                 EXTSYSCOMPANYCODE,COMPANYNAME
                    FROM (SELECT
                         STG.EXTSYS,
                         CE.COMPANY,
                         CO.COMPANYNAME,
                         CE.EXTSYSCOMPANYCODE,
                         BE.BUSINESSUNIT,
                         BE.EXTSYSBUSINESSUNITCODE,
                         DP.DEPARTMENT,
                         DOCTYPE,
                         STG.DOCNUMBER,
                         STG.DOCITEM,
                         STG.DOCDATE,
                         DOCREF,
                         SP.ID AS VENDOR,
                         NULL TRANS_LOC,
                         DP.PURCHORG,
                         BASELINEDATE,
                         CASE
                            WHEN REGEXP_LIKE (SUBSTR (STG.PAYTERM, 2), '^\d+(\.\d+)?$')
                            THEN
                               SUBSTR (STG.PAYTERM, 2)
                            ELSE
                               '0'
                         END
                            PAYTERM,
                         DUEDATE,
                         NULL REMARK,
                         NVL (STG.AMOUNT_INCLUDE_VAT, 0) AMOUNT_INCLUDE_VAT,
                         0 AMOUNT_PPH,
                         'TRUE' ISACTIVE,
                         'SAPBRIDGE' FCENTRY,
                         'SAPBRIDGE' FCEDIT,
                         '172.0.0' FCIP,
                         SYSDATE LASTUPDATE,
                         '00:00' LASTTIME,
                         NULL UPLOAD_REF,
                         0 VAT,
                         NULL FAKTUR_PAJAK,
                         0 TOTAL_BAYAR,
                         STG.CURRENCY,
                         NVL (AMOUNT_PPN, 0) AMOUNT_PPN,
                         NULL ISADENDUM,
                         1 RATE,
                         NULL INVOICEVENDORNO
                    FROM CF_TRANS@dblink_staging STG
                         INNER JOIN (select COMPANY, EXTSYSCOMPANYCODE,EXTSYSTEM from COMPANY_EXTSYS where EXTSYSTEM = 'SAPHANA') CE
                            ON     STG.COMPANY = CE.EXTSYSCOMPANYCODE
                         INNER JOIN (select id,companyname from COMPANY) CO ON CE.COMPANY = CO.ID
                         INNER JOIN (select EXTSYSBUSINESSUNITCODE,businessunit from BUSINESSUNIT_EXTSYS where EXTSYSTEM = 'SAPHANA') BE
                            ON     STG.BUSINESSUNIT = BE.EXTSYSBUSINESSUNITCODE
                         INNER JOIN (select id,FCCODE from BUSINESSUNIT) bu ON (BE.businessunit = bu.id)
                         LEFT JOIN (select businessunit,purchorg,department from DEPARTMENT_PURCHORG) DP
                            ON (    BU.FCCODE = DP.BUSINESSUNIT
                                AND STG.DEPARTMENT = DP.PURCHORG)
                         INNER JOIN (select id,fccode,fcname from SUPPLIER) SP ON STG.vendor = SP.fccode
                         INNER JOIN (select fccode from DOCTYPE) DT ON (DT.FCCODE = STG.DOCTYPE)
                   WHERE     STG.ISRETRIVEDBYCF IS NULL
                         AND STG.ISACTIVE = 'TRUE'
                         AND STG.DOCTYPE NOT IN ('SPO', 'SO')
                         AND STG.AMOUNT_INCLUDE_VAT > 0
                         AND ce.company || STG.doctype || STG.docnumber NOT IN
                                (SELECT ce.company || doctype || docnumber
                                         FROM cf_transaction where DOCTYPE NOT IN ('SPO', 'SO'))
                                         )
           WHERE DEPARTMENT IS NOT NULL
        GROUP BY EXTSYS,
                 COMPANY,
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 DEPARTMENT,
                 DOCTYPE,
                 TO_DATE (DOCDATE, 'yyyymmdd'),
                 SUBSTR (DOCDATE, 1, 4),
                 DOCREF,
                 VENDOR,
                 TRANS_LOC,
                 BASELINEDATE,
                 PAYTERM,
                 DUEDATE,
                 REMARK,
                 AMOUNT_PPH,
                 ISACTIVE,
                 FCENTRY,
                 FCEDIT,
                 FCIP,
                 LASTUPDATE,
                 LASTTIME,
                 UPLOAD_REF,
                 VAT,
                 FAKTUR_PAJAK,
                 CURRENCY,
                 ISADENDUM,
                 RATE,
                 INVOICEVENDORNO) GROUP BY EXTSYSCOMPANYCODE,COMPANYNAME ORDER BY COMPANYNAME ASC";
        return $this->db->query($q)->result();
    }

    public function loadBusinessUnitSTO(){
        $q = "SELECT DISTINCT EXTSYSBUSINESSUNITCODE FROM (SELECT 
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 MAX(EXTSYSBUSINESSUNITCODE)EXTSYSBUSINESSUNITCODE
            FROM (SELECT
                         STG.EXTSYS,
                         CE.COMPANY,
                         CO.COMPANYNAME,
                         CE.EXTSYSCOMPANYCODE,
                         BE.BUSINESSUNIT,
                         BE.EXTSYSBUSINESSUNITCODE,
                         DP.DEPARTMENT,
                         DOCTYPE,
                         STG.DOCNUMBER,
                         STG.DOCITEM,
                         STG.DOCDATE,
                         DOCREF,
                         SP.ID AS VENDOR,
                         NULL TRANS_LOC,
                         DP.PURCHORG,
                         BASELINEDATE,
                         CASE
                            WHEN REGEXP_LIKE (SUBSTR (STG.PAYTERM, 2), '^\d+(\.\d+)?$')
                            THEN
                               SUBSTR (STG.PAYTERM, 2)
                            ELSE
                               '0'
                         END
                            PAYTERM,
                         DUEDATE,
                         NULL REMARK,
                         NVL (STG.AMOUNT_INCLUDE_VAT, 0) AMOUNT_INCLUDE_VAT,
                         0 AMOUNT_PPH,
                         'TRUE' ISACTIVE,
                         'SAPBRIDGE' FCENTRY,
                         'SAPBRIDGE' FCEDIT,
                         '172.0.0' FCIP,
                         SYSDATE LASTUPDATE,
                         '00:00' LASTTIME,
                         NULL UPLOAD_REF,
                         0 VAT,
                         NULL FAKTUR_PAJAK,
                         0 TOTAL_BAYAR,
                         STG.CURRENCY,
                         NVL (AMOUNT_PPN, 0) AMOUNT_PPN,
                         NULL ISADENDUM,
                         1 RATE,
                         NULL INVOICEVENDORNO
                    FROM CF_TRANS@dblink_staging STG
                         INNER JOIN COMPANY_EXTSYS CE
                            ON     STG.COMPANY = CE.EXTSYSCOMPANYCODE
                               AND CE.EXTSYSTEM = 'SAPHANA'
                         INNER JOIN COMPANY CO ON CE.COMPANY = CO.ID
                         INNER JOIN BUSINESSUNIT_EXTSYS BE
                            ON     STG.BUSINESSUNIT = BE.EXTSYSBUSINESSUNITCODE
                               AND BE.EXTSYSTEM = 'SAPHANA'
                         INNER JOIN BUSINESSUNIT bu ON (BE.businessunit = bu.id)
                         LEFT JOIN DEPARTMENT_PURCHORG DP
                            ON (    BU.FCCODE = DP.BUSINESSUNIT
                                AND STG.DEPARTMENT = DP.PURCHORG)
                         INNER JOIN SUPPLIER SP ON STG.vendor = SP.fccode
                         INNER JOIN DOCTYPE DT ON (DT.FCCODE = STG.DOCTYPE)
                   WHERE     STG.ISRETRIVEDBYCF IS NULL
                         AND STG.ISACTIVE = 'TRUE'
                         AND STG.DOCTYPE NOT IN ('SPO', 'SO')
                         AND STG.AMOUNT_INCLUDE_VAT > 0
                         AND ce.company || STG.doctype || STG.docnumber NOT IN
                                (SELECT ce.company || doctype || docnumber
                                         FROM cf_transaction where DOCTYPE NOT IN ('SPO', 'SO'))
                                         )
           WHERE DEPARTMENT IS NOT NULL
        GROUP BY EXTSYS,
                 COMPANY,
                 COMPANYNAME,
                 EXTSYSCOMPANYCODE,
                 DEPARTMENT,
                 DOCTYPE,
                 TO_DATE (DOCDATE, 'yyyymmdd'),
                 SUBSTR (DOCDATE, 1, 4),
                 DOCREF,
                 VENDOR,
                 TRANS_LOC,
                 BASELINEDATE,
                 PAYTERM,
                 DUEDATE,
                 REMARK,
                 AMOUNT_PPH,
                 ISACTIVE,
                 FCENTRY,
                 FCEDIT,
                 FCIP,
                 LASTUPDATE,
                 LASTTIME,
                 UPLOAD_REF,
                 VAT,
                 FAKTUR_PAJAK,
                 CURRENCY,
                 ISADENDUM,
                 RATE,
                 INVOICEVENDORNO) GROUP BY COMPANYNAME,EXTSYSBUSINESSUNITCODE ORDER BY EXTSYSBUSINESSUNITCODE ASC";
        return $this->db->query($q)->result();
    }

}

/* End of file StagingModel.php */
/* Location: ./application/models/StagingModel.php */