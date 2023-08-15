<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 'On');
class ZModel extends BaseModel {

    public function __construct()
    {
        parent::__construct();
    }

	public function showData($param) {
		
        // var_dump($param);exit;
        $DEPT         = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];
        // $SUBGROUP     = $param['SUBGROUP'];
        // $VENDOR       = $param['VENDOR'];
        
        // $sDATE        = $param['sDATE'];
        // if($sDATE != null || $sDate != ''){
        //     $WHERE .= " AND TO_CHAR(LT.DATE_RECEIPT,'mm-dd-yyyy') = '$sDATE'";
        // }
        // if($VENDOR != "" || $VENDOR != null){
        //     $WHERE .= " AND S.ID = '$VENDOR'";
        // }
        

        $Lenght     = $param["length"];
        $Start      = $param["start"];
        $Columns    = $param["columns"];
        $Search     = $param["search"];
        $Order      = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        if($COMPANY != "0" || $COMPANY != null || $COMPANY != ''){
            $WHERE .= " WHERE CFT.COMPANY = '$COMPANY'";
        }
        
        $q2 = "(SELECT C.COMPANYCODE,BS.FCCODE AS BUSINESSUNITCODE,CF.ID, CF.DEPARTMENT,S.FCNAME AS VENDORNAME,CF.DOCNUMBER,CF.NEGO_NO, CF.AMOUNT_INCLUDE_VAT 
                FROM CF_TRANSACTION CF
               INNER JOIN COMPANY C ON CF.COMPANY = C.ID
               INNER JOIN BUSINESSUNIT BS ON BS.ID = CF.BUSINESSUNIT
               INNER JOIN SUPPLIER S ON S.ID = CF.VENDOR
                WHERE CF.NEGO_NO IS NOT NULL AND CF.DOCNUMBER NOT LIKE 'TMPINV%' AND CF.DOCNUMBER NOT LIKE '%REVERSAL')";

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
            // $SQLO = " ORDER BY DATE_RECEIPT";
            $SQLO = "";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $q2 FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q2 FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q2 FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    function sendForecast($param,$Location){
        $CFID           = $param['CFID'];
        
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME         = $this->session->userdata('username');

            if($USERNAME == null){
                throw new Exception("Session Username is null, please re-login");
            }
            if($sessDEPARTMENT == null){
                throw new Exception("Session Department is null, please re-login");
            }

            $qget = "SELECT * FROM CF_TRANSACTION WHERE ID = '$CFID'";
            $get  = $this->db->query($qget)->row();
            $AMOUNT = $get->AMOUNT_INCLUDE_VAT;

            $RANDID = $this->uuid->v4();
            $SQL =  "INSERT INTO CF_TRANSACTION
                                        select
                                        '$RANDID' ID,
                                        CFT.EXTSYS,
                                        CFT.COMPANY,
                                        CFT.BUSINESSUNIT,
                                        CFT.DEPARTMENT,
                                        CASE WHEN DOCTYPE = 'SO' THEN 'INV_AR' ELSE 'INV' END AS DOCTYPE,
                                        'TMPINV-'|| CFT.DOCNUMBER DOCNUMBER,
                                        To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy') DOCDATE,
                                        CFT.DOCNUMBER DOCREF,
                                        CFT.VENDOR,
                                        CFT.TRANS_LOC,
                                        To_date(to_char(CFT.DOCDATE,'mm/dd/yyy'),'mm/dd/yyy') BASELINEDATE,
                                        CFT.PAYTERM,
                                        To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+CFT.PAYTERM DUEDATE,
                                        'Dummy Invoice - by System !' REMARK,
                                        CFT.AMOUNT_INCLUDE_VAT,
                                        0 AMOUNT_PPH,
                                        CFT.ISACTIVE,
                                        CFT.FCENTRY,
                                        CFT.FCEDIT,
                                        CFT.FCIP,
                                        sysdate LASTUPDATE,
                                        CFT.LASTTIME,
                                        CFT.UPLOAD_REF,
                                        CFT.VAT,
                                        CFT.FAKTUR_PAJAK,
                                        (CFT.AMOUNT_INCLUDE_VAT - 0) AS TOTAL_BAYAR,
                                        CFT.CURRENCY,
                                        CASE WHEN CFT.VAT > 0 THEN ROUND((((CFT.AMOUNT_INCLUDE_VAT/((100+CFT.VAT)/100))*CFT.VAT)/100 ),0) ELSE 0 END AS AMOUNT_PPN,
                                        CFT.ISADENDUM,
                                        CFT.RATE,
                                        CFT.INVOICEVENDORNO,
                                        '' ITEM_STO,
                                        CFT.PO_B2BSELLER,
                                        CFT.NEGO_NO
                                        from 
                                        CF_TRANSACTION CFT
                                        WHERE CFT.ID = ?";
            $addDummy = $this->db->query($SQL, [$CFID]);

            $qget = "SELECT * FROM CF_TRANSACTION WHERE ID = '$RANDID'";
            $get  = $this->db->query($qget)->row();

            $qGetGroup = "select c.COMPANY_SUBGROUP,cs.FCCODE_GROUP from company c inner join company_subgroup cs on cs.fccode = c.company_subgroup where c.id = '$get->COMPANY'";
            $getGroup  = $this->db->query($qGetGroup)->row();
            
            $formatDD   = date('m/d/Y',strtotime("$get->DUEDATE"));
            $dateArray = explode("/", $formatDD);
            $date = new DateTime();
            $date->setDate($dateArray[2], $dateArray[0], $dateArray[1]);
            // var_dump($dateArray[0]);exit;
            $getWeek = floor((date_format($date, 'j') - 1) / 7) + 1; 

            $forecast = array(
                    'DEPARTMENT' => $get->DEPARTMENT,
                    'CFTRANSID'  => $RANDID,
                    'YEAR'       => $dateArray[2],
                    'MONTH'      => $dateArray[0],
                    'WEEK'       => 'W'.$getWeek,
                    'AMOUNTREQUEST' => $get->AMOUNT_INCLUDE_VAT,
                    'AMOUNTADJS' => 0,
                    'ISACTIVE'   => 1,
                    'FCENTRY' => $USERNAME,
                    'FCEDIT' => $USERNAME,
                    "FCIP" => $Location,
                    "PRIORITY" => 1,
                    "LOCKS" => 1,
                    "STATE" => 0,
                    "INVOICEVENDORNO" => "",
                    "COMPANYGROUP" => $getGroup->FCCODE_GROUP,
                    "COMPANYSUBGROUP" => $getGroup->COMPANY_SUBGROUP
                );
                $result3 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                            ->set($forecast)->insert($this->FORECAST_FIX);
            

            if ($addDummy && $result3) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Data Save Failed !!'
                ];
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

    public function showDataReversal($param) {
        
        // var_dump($param);exit;
        $DEPT         = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];
        // $SUBGROUP     = $param['SUBGROUP'];
        // $VENDOR       = $param['VENDOR'];
        
        // $sDATE        = $param['sDATE'];
        // if($sDATE != null || $sDate != ''){
        //     $WHERE .= " AND TO_CHAR(LT.DATE_RECEIPT,'mm-dd-yyyy') = '$sDATE'";
        // }
        // if($VENDOR != "" || $VENDOR != null){
        //     $WHERE .= " AND S.ID = '$VENDOR'";
        // }
        

        $Lenght     = $param["length"];
        $Start      = $param["start"];
        $Columns    = $param["columns"];
        $Search     = $param["search"];
        $Order      = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        if($COMPANY != "0" || $COMPANY != null || $COMPANY != ''){
            $WHERE .= " WHERE CFT.COMPANY = '$COMPANY'";
        }
        
        $q2 = "(SELECT C.COMPANYCODE,
                   BS.FCCODE AS BUSINESSUNITCODE,
                   CF.ID,
                   CF.DOCTYPE,
                   CF.DOCDATE,
                   CF.DEPARTMENT,
                   S.FCNAME AS VENDORNAME,
                   CF.DOCNUMBER,
                   CF.NEGO_NO,
                   CF.AMOUNT_INCLUDE_VAT
              FROM CF_TRANSACTION CF
                   INNER JOIN COMPANY C ON CF.COMPANY = C.ID
                   INNER JOIN BUSINESSUNIT BS ON BS.ID = CF.BUSINESSUNIT
                   INNER JOIN SUPPLIER S ON S.ID = CF.VENDOR
             WHERE        CF.NEGO_NO IS NOT NULL
                      AND CF.DOCTYPE IN('SO','PO')
                      AND CF.DOCNUMBER NOT LIKE 'TMPINV%'
                      AND CF.DOCTYPE NOT LIKE 'INV'
                      AND CF.DOCTYPE NOT LIKE '%_REVERSAL'
                      AND CF.company
                          || CF.DOCNUMBER
                          || TO_CHAR (CF.DOCDATE, 'yyyy') IN
                             (SELECT DISTINCT
                                        CFT.COMPANY
                                     || CFT.DOCREF
                                     || TO_CHAR (CFT.DOCDATE, 'yyyy')
                                        DOCDATE
                                FROM FORECAST_FIX FF
                                     INNER JOIN CF_TRANSACTION CFT
                                        ON CFT.ID = FF.CFTRANSID)) ";

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
            // $SQLO = " ORDER BY DATE_RECEIPT";
            $SQLO = "";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $q2 FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q2 FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q2 FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    function sendReversal($param,$Location){
        try {
            $this->db->trans_begin();
            $result = FALSE;

            $CFID           = $param['CFID'];
            $DOCNUMBER      = $param['DOCNUMBER'];
            $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME         = $this->session->userdata('username');

            if($USERNAME == null){
                throw new Exception("Session Username is null, please re-login");
            }
            if($sessDEPARTMENT == null){
                throw new Exception("Session Department is null, please re-login");
            }

            $qFF    = "SELECT CF.ID,
                           CF.DOCNUMBER,
                           CF.AMOUNT_INCLUDE_VAT,
                           CF.AMOUNT_PPH,
                           FF.ISACTIVE,
                           FF.DEPARTMENT,
                           FF.AMOUNTREQUEST,
                           FF.AMOUNTADJS,
                           CFD.MATERIAL
                      FROM CF_TRANSACTION CF 
                      INNER JOIN FORECAST_FIX FF ON FF.CFTRANSID = CF.ID
                      INNER JOIN CF_TRANSACTION CFPO ON (CFPO.DOCNUMBER = CF.DOCREF AND CFPO.COMPANY = CF.COMPANY)
                      INNER JOIN CF_TRANSACTION_DET CFD ON CFD.ID = CFPO.ID
                     WHERE CF.DOCREF = '$DOCNUMBER'";
            $getFF  = $this->db->query($qFF)->row();

            $checkDup = "SELECT * FROM CF_TRANSACTION WHERE DOCNUMBER = '".$DOCNUMBER."_REVERSAL'";
            $checkDup = $this->db->query($checkDup)->num_rows();
            
            if($getFF == NULL){
                throw new Exception("FORECAST KOSONG");
            }else if($getFF->ISACTIVE == 2){
                throw new Exception("FORECAST ON FINANCE");
            }
            else if($checkDup > 0){
                throw new Exception("Duplicate Data");
            }
            else{
                // var_dump($this->db->last_query());exit;
                // var_dump("masuk");exit;
                $RANDID = $this->uuid->v4();
                $SQL =  "INSERT INTO CF_TRANSACTION
                                            select
                                            '$RANDID' ID,
                                            CFT.EXTSYS,
                                            CFT.COMPANY,
                                            CFT.BUSINESSUNIT,
                                            CFT.DEPARTMENT,
                                            CASE WHEN DOCTYPE = 'SO' THEN 'SO_REVERSAL' ELSE 'PO_REVERSAL' END AS DOCTYPE,
                                            CFT.DOCNUMBER||'_REVERSAL' DOCNUMBER,
                                            To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy') DOCDATE,
                                            CFT.DOCNUMBER DOCREF,
                                            CFT.VENDOR,
                                            CFT.TRANS_LOC,
                                            To_date(to_char(CFT.DOCDATE,'mm/dd/yyy'),'mm/dd/yyy') BASELINEDATE,
                                            CFT.PAYTERM,
                                            To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+CFT.PAYTERM DUEDATE,
                                            CFT.REMARK REMARK,
                                            (CFT.AMOUNT_INCLUDE_VAT * -1 ) AMOUNT_INCLUDE_VAT,
                                            (CFT.AMOUNT_PPH * -1) AMOUNT_PPH,
                                            CFT.ISACTIVE,
                                            CFT.FCENTRY,
                                            CFT.FCEDIT,
                                            CFT.FCIP,
                                            sysdate LASTUPDATE,
                                            CFT.LASTTIME,
                                            CFT.UPLOAD_REF,
                                            CFT.VAT,
                                            CFT.FAKTUR_PAJAK,
                                            (CFT.TOTAL_BAYAR * -1) TOTAL_BAYAR,
                                            CFT.CURRENCY,
                                            (CFT.AMOUNT_PPN * -1) AMOUNT_PPN,
                                            CFT.ISADENDUM,
                                            CFT.RATE,
                                            CFT.INVOICEVENDORNO,
                                            CFT.ITEM_STO,
                                            CFT.PO_B2BSELLER,
                                            CFT.NEGO_NO
                                            from 
                                            CF_TRANSACTION CFT
                                            WHERE CFT.ID = ?";
                $addCF = $this->db->query($SQL, [$CFID]);

                //get data addcf
                $qCF    = "SELECT ID,COMPANY,DEPARTMENT,DUEDATE,AMOUNT_INCLUDE_VAT,AMOUNT_PPH,REMARK FROM CF_TRANSACTION WHERE ID = '$RANDID'";
                $getCF  = $this->db->query($qCF)->row();

                // $qCFD   = "SELECT ID,MATERIAL,AMOUNT_INCLUDE_VAT,AMOUNT_PPH FROM CF_TRANSACTION_DET WHERE ID = '$CFID'";
                // $getCFD = $this->db->query($qCFD)->row();

                if($addCF){
                    $cf_det = [
                            "ID" => $RANDID,
                            'MATERIAL' => $getFF->MATERIAL,
                            'REMARKS' => $getCF->REMARK,
                            'AMOUNT_INCLUDE_VAT' => $getCF->AMOUNT_INCLUDE_VAT,
                            'AMOUNT_PPH' => $getCF->AMOUNT_PPH,
                            "ISACTIVE" => "TRUE",
                            "FCENTRY" => $param["USERNAME"],
                            "FCEDIT" => $param["USERNAME"],
                            "FCIP" => $Location
                        ];
                    $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                                        ->set($cf_det)->insert($this->CF_TRANSACTION_DET);

                    if($result2){
                        $qGetGroup = "select c.COMPANY_SUBGROUP,cs.FCCODE_GROUP from company c inner join company_subgroup cs on cs.fccode = c.company_subgroup where c.id = '$getCF->COMPANY'";
                        $getGroup  = $this->db->query($qGetGroup)->row();
                        
                        $formatDD   = date('m/d/Y',strtotime("$getCF->DUEDATE"));
                        $dateArray = explode("/", $formatDD);
                        $date = new DateTime();
                        $date->setDate($dateArray[2], $dateArray[0], $dateArray[1]);
                        // var_dump($dateArray[0]);exit;
                        $getWeek = floor((date_format($date, 'j') - 1) / 7) + 1; 

                        $forecast = array(
                                'DEPARTMENT' => $getFF->DEPARTMENT,
                                'CFTRANSID'  => $RANDID,
                                'YEAR'       => $dateArray[2],
                                'MONTH'      => $dateArray[0],
                                'WEEK'       => 'W'.$getWeek,
                                'AMOUNTREQUEST' => $getFF->AMOUNTREQUEST * -1,
                                'AMOUNTADJS' => $getFF->AMOUNTADJS * -1,
                                'ISACTIVE'   => 2,
                                'FCENTRY' => $USERNAME,
                                'FCEDIT' => $USERNAME,
                                "FCIP" => $Location,
                                "PRIORITY" => 1,
                                "LOCKS" => 1,
                                "STATE" => 0,
                                "INVOICEVENDORNO" => "",
                                "COMPANYGROUP" => $getGroup->FCCODE_GROUP,
                                "COMPANYSUBGROUP" => $getGroup->COMPANY_SUBGROUP
                            );
                            $result3 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                        ->set($forecast)->insert($this->FORECAST_FIX);
                    }else{
                        $this->db->trans_rollback();
                        throw new Exception("Error Processing Request CF DET");
                    }//end else cfdet / result2
                }
                else{
                    $this->db->trans_rollback();
                    throw new Exception("Error Processing Request CF");
                }//else addcf
            }//end else

            if ($addCF && $result2 && $result3) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Data Save Failed !!'
                ];
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

    function sendAll($param,$Location){
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            foreach($param['DtData'] AS $key => $row) {
                // echo "<pre>";
                // var_dump($param);exit();
                $FLAG = isset($row["FLAG"]);
                
                if($row["FLAG"] != 0){
                    $this->db->trans_begin();

                    $result = FALSE;
                    $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
                    $USERNAME     = $this->session->userdata('username');

                    if($USERNAME == null){
                        throw new Exception("Session Username is null, please re-login");
                    }

                    if($sessDEPARTMENT == null){
                        throw new Exception("Session Department is null, please re-login");
                    }

                    $CFID           = $row['ID'];
                    $DOCNUMBER      = $row['DOCNUMBER'];
                    $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
                    $USERNAME         = $this->session->userdata('username');

                    if($USERNAME == null){
                        throw new Exception("Session Username is null, please re-login");
                    }
                    if($sessDEPARTMENT == null){
                        throw new Exception("Session Department is null, please re-login");
                    }

                    $qFF    = "SELECT CF.ID,
                                   CF.DOCNUMBER,
                                   CF.AMOUNT_INCLUDE_VAT,
                                   CF.AMOUNT_PPH,
                                   FF.ISACTIVE,
                                   FF.DEPARTMENT,
                                   FF.AMOUNTREQUEST,
                                   FF.AMOUNTADJS,
                                   CFD.MATERIAL
                              FROM CF_TRANSACTION CF 
                              INNER JOIN FORECAST_FIX FF ON FF.CFTRANSID = CF.ID
                              INNER JOIN CF_TRANSACTION CFPO ON CFPO.DOCNUMBER = CF.DOCREF
                              INNER JOIN CF_TRANSACTION_DET CFD ON CFD.ID = CFPO.ID
                             WHERE CF.DOCREF = '$DOCNUMBER'";
                    $getFF  = $this->db->query($qFF)->row();
                    
                    if($getFF == NULL){
                        throw new Exception("FORECAST KOSONG");
                    }else if($getFF->ISACTIVE == 2){
                        throw new Exception("FORECAST ON FINANCE");
                    }
                    else{
                        // var_dump($this->db->last_query());exit;
                        // var_dump("masuk");exit;
                        $RANDID = $this->uuid->v4();
                        $SQL =  "INSERT INTO CF_TRANSACTION
                                                    select
                                                    '$RANDID' ID,
                                                    CFT.EXTSYS,
                                                    CFT.COMPANY,
                                                    CFT.BUSINESSUNIT,
                                                    CFT.DEPARTMENT,
                                                    CASE WHEN DOCTYPE = 'SO' THEN 'SO_REVERSAL' ELSE 'PO_REVERSAL' END AS DOCTYPE,
                                                    CFT.DOCNUMBER||'_REVERSAL' DOCNUMBER,
                                                    To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy') DOCDATE,
                                                    CFT.DOCNUMBER DOCREF,
                                                    CFT.VENDOR,
                                                    CFT.TRANS_LOC,
                                                    To_date(to_char(CFT.DOCDATE,'mm/dd/yyy'),'mm/dd/yyy') BASELINEDATE,
                                                    CFT.PAYTERM,
                                                    To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+CFT.PAYTERM DUEDATE,
                                                    CFT.REMARK REMARK,
                                                    (CFT.AMOUNT_INCLUDE_VAT * -1 ) AMOUNT_INCLUDE_VAT,
                                                    (CFT.AMOUNT_PPH * -1) AMOUNT_PPH,
                                                    CFT.ISACTIVE,
                                                    CFT.FCENTRY,
                                                    CFT.FCEDIT,
                                                    CFT.FCIP,
                                                    sysdate LASTUPDATE,
                                                    CFT.LASTTIME,
                                                    CFT.UPLOAD_REF,
                                                    CFT.VAT,
                                                    CFT.FAKTUR_PAJAK,
                                                    (CFT.TOTAL_BAYAR * -1) TOTAL_BAYAR,
                                                    CFT.CURRENCY,
                                                    (CFT.AMOUNT_PPN * -1) AMOUNT_PPN,
                                                    CFT.ISADENDUM,
                                                    CFT.RATE,
                                                    CFT.INVOICEVENDORNO,
                                                    CFT.ITEM_STO,
                                                    CFT.PO_B2BSELLER,
                                                    CFT.NEGO_NO
                                                    from 
                                                    CF_TRANSACTION CFT
                                                    WHERE CFT.ID = ?";
                        $addCF = $this->db->query($SQL, [$CFID]);

                        //get data addcf
                        $qCF    = "SELECT ID,COMPANY,DEPARTMENT,DUEDATE,AMOUNT_INCLUDE_VAT,AMOUNT_PPH,REMARK FROM CF_TRANSACTION WHERE ID = '$RANDID'";
                        $getCF  = $this->db->query($qCF)->row();

                        $qCFD   = "SELECT ID,MATERIAL,AMOUNT_INCLUDE_VAT,AMOUNT_PPH FROM CF_TRANSACTION_DET WHERE ID = '$CFID'";
                        $getCFD = $this->db->query($qCFD)->row();

                        if($addCF){
                            $cf_det = [
                                    "ID" => $RANDID,
                                    'MATERIAL' => $getFF->MATERIAL,
                                    'REMARKS' => $getCF->REMARK,
                                    'AMOUNT_INCLUDE_VAT' => $getCF->AMOUNT_INCLUDE_VAT,
                                    'AMOUNT_PPH' => $getCF->AMOUNT_PPH,
                                    "ISACTIVE" => "TRUE",
                                    "FCENTRY" => $USERNAME,
                                    "FCEDIT" => $USERNAME,
                                    "FCIP" => $Location
                                ];
                            $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                                                ->set($cf_det)->insert($this->CF_TRANSACTION_DET);

                            if($result2){
                                $qGetGroup = "select c.COMPANY_SUBGROUP,cs.FCCODE_GROUP from company c inner join company_subgroup cs on cs.fccode = c.company_subgroup where c.id = '$getCF->COMPANY'";
                                $getGroup  = $this->db->query($qGetGroup)->row();
                                
                                $formatDD   = date('m/d/Y',strtotime("$getCF->DUEDATE"));
                                $dateArray = explode("/", $formatDD);
                                $date = new DateTime();
                                $date->setDate($dateArray[2], $dateArray[0], $dateArray[1]);
                                // var_dump($dateArray[0]);exit;
                                $getWeek = floor((date_format($date, 'j') - 1) / 7) + 1; 

                                $forecast = array(
                                        'DEPARTMENT' => $getFF->DEPARTMENT,
                                        'CFTRANSID'  => $RANDID,
                                        'YEAR'       => $dateArray[2],
                                        'MONTH'      => $dateArray[0],
                                        'WEEK'       => 'W'.$getWeek,
                                        'AMOUNTREQUEST' => $getFF->AMOUNTREQUEST * -1,
                                        'AMOUNTADJS' => $getFF->AMOUNTADJS * -1,
                                        'ISACTIVE'   => 1,
                                        'FCENTRY' => $USERNAME,
                                        'FCEDIT' => $USERNAME,
                                        "FCIP" => $Location,
                                        "PRIORITY" => 1,
                                        "LOCKS" => 1,
                                        "STATE" => 0,
                                        "INVOICEVENDORNO" => "",
                                        "COMPANYGROUP" => $getGroup->FCCODE_GROUP,
                                        "COMPANYSUBGROUP" => $getGroup->COMPANY_SUBGROUP
                                    );
                                    $result3 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                                ->set($forecast)->insert($this->FORECAST_FIX);
                            }else{
                                $this->db->trans_rollback();
                                throw new Exception("Error Processing Request CF DET");
                            }//end else cfdet / result2
                        }
                        else{
                            $this->db->trans_rollback();
                            throw new Exception("Error Processing Request CF");
                        }//else addcf
                    }//end else
                    

                    if ($addCF && $result2 && $result3) {
                        $this->db->trans_commit();
                        $return = [
                            'STATUS' => TRUE,
                            'MESSAGE' => 'Data has been Successfully Saved !!'
                        ];
                    } else {
                        $this->db->trans_rollback();
                        $return = [
                            'STATUS' => FALSE,
                            'MESSAGE' => 'Data Save Failed !!'
                        ];
                    }
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

}

/* End of file ZModel.php */
/* Location: ./application/models/ZModel.php */