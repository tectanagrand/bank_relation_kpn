<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KMKModel extends BaseModel {

    public $variable;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function acceptWDKI($param,$Location) {
    try {
        $this->db->trans_begin();
        if($param['STATUS'] == 1) {

            $q2 = "SELECT * FROM FUNDS_WD_KI WHERE UUID = ? AND (STATUS = 0 OR STATUS = 2) AND CTRWD = ? " ;
            $q = "SELECT FKI.UUID,
                        FKI.TRANCHE_NUMBER,
                        CASE 
                            WHEN FWDT.BALANCE IS NULL THEN (SELECT BALANCE FROM FUNDS_WD_KI_TRANCHE WHERE TRANCHE_NUMBER = FKI.TRANCHE_NUMBER AND UUID = FKI.UUID AND STATUS = 1 ORDER BY COUNTER NULLS LAST, BATCHID DESC, COUNTER DESC FETCH FIRST 1 ROWS ONLY)
                            ELSE FWDT.BALANCE
                            END
                            AS BALANCE,
                        FWDT.BATCHID,
                        FKI.LIMIT_TRANCHE,
                        CASE
                            WHEN FWDT.DDOWN_AMT IS NULL THEN 0
                            ELSE FWDT.DDOWN_AMT
                            END
                            AS DRAWDOWN_VALUE
                FROM FUNDS_DETAIL_KI_TRANCHE FKI
                        LEFT JOIN (SELECT BATCHID,
                                        BALANCE,
                                        DDOWN_AMT,
                                        TRANCHE_NUMBER,
                                        UUID,
                                        IS_PAYMENT, 
                                        COUNTER
                                    FROM FUNDS_WD_KI_TRANCHE FKIT
                                        LEFT JOIN
                                        (       SELECT CTRWD
                                                FROM FUNDS_WD_KI
                                                WHERE UUID = '".$param['UUID']."' AND (STATUS = 0 OR STATUS = 1)
                                            ORDER BY CTRWD DESC
                                        FETCH FIRST 1 ROWS ONLY ) FWD
                                            ON FWD.CTRWD = FKIT.BATCHID 
                                    WHERE FWD.CTRWD = FKIT.BATCHID
                                    ORDER BY COUNTER, COUNTER NULLS LAST) FWDT
                        ON FKI.TRANCHE_NUMBER = FWDT.TRANCHE_NUMBER AND FKI.UUID = FWDT.UUID
                WHERE     
                        FKI.UUID = '".$param['UUID']."'
                        AND FKI.ISACTIVE = '1' AND FKI.IS_COMPLETE IS NULL" ;

            $lastBalance = $this->db->query($q)->result();
            // var_dump($lastBalance); exit;
            // var_dump($this->db->last_query()); exit;
            $que = $this->db->query($q2, array($param['UUID'], $param['BATCHID']))->row();

            $totalBalance = 0 ;
           
            foreach ($lastBalance as $balance)
            {
                if($balance->BALANCE == null) {
                    $updateBalance = intval($balance->LIMIT_TRANCHE) - intval($balance->DRAWDOWN_VALUE) ;
                }
                else {
                    $updateBalance = intval($balance->BALANCE) - intval($balance->DRAWDOWN_VALUE) ;
                }

                $dt = [
                    'BALANCE'       => $updateBalance,
                    'STATUS'        => 1,
                    'CREATED_BY'    => $param['USERNAME']
                ];
                $upt = $this->db->set('CREATED_AT','SYSDATE',false);
                $upt = $this->db->set($dt)->where("UUID = '".$param['UUID']."' AND TRANCHE_NUMBER = '".$balance->TRANCHE_NUMBER."' AND BATCHID = '".$balance->BATCHID."'")->update('FUNDS_WD_KI_TRANCHE');
                $totalBalance += $updateBalance;
                
            }
            
            $dtmain = [
                'BALANCE'       => $totalBalance,
                'STATUS'        => 1,
                'CREATED_BY'    => $param['USERNAME']
            ];
            $last = $this->db->set($dtmain)->where("UUID = '".$param['UUID']."' AND CTRWD = '".$param['BATCHID']."'")->update('FUNDS_WD_KI');
            $update_up_flag = $this->db->set('UP_BAL', NULL)
                            ->where(['UP_BAL' => '1', 'UUID' => $param['UUID']])
                            ->update('FUNDS_DETAIL_KI_TRANCHE');
            // var_dump($last) ; exit;
            // var_dump($updateBalance); exit;
        }
        // var_dump($last); exit;
        if($last) {
            
        
            //amount of cashflow, taken from FUNDS_WD_KI header of withdrawal 
            //DND!!!
            $AMOUNTCF = $que->DRAWDOWN_VALUE;
            // var_dump($AMOUNTCF) ; exit;
            // $dt = [
            //     'BALANCE'       => $updateBalance,
            //     'STATUS'        => 1,
            //     'CREATED_BY'    => $param['USERNAME']
            //     // 'DRAWDOWN'      => $que->DRAWDOWN,
            //     // 'DRAWDOWN_VALUE'=> $que->DRAWDOWN_VALUE,
            //     // 'VALUE_DATE'    => $que->VALUE_DATE,
            //     // 'RATE_IDRUSD'   => $que->RATE_IDRUSD,
            //     // 'RATE_CNYUSD'   => $que->RATE_CNYUSD,
            //     // 'RATE_SGDUSD'   => $que->RATE_SGDUSD,
            //     // 'TRANCHE_NUMBER'=> $que->TRANCHE_NUMBER,
            //     // 'UUID'          => $que->UUID
            // ] ;
            // // var_dump($dt);
            // // exit;
            // $last = $this->db->set('CREATED_AT','SYSDATE',false);
            // $last = $this->db->set($dt)->where("UUID = '".$param['UUID']."' AND ID = '".$param['ID']."'")->update('FUNDS_WD_KI');
                // $last   = $this->db->set('CREATED_AT','SYSDATE',false);
                // $last   = $this->db->set($dt)->insert('FUNDS_WD_KI');

                if($param['SUB_CREDIT_TYPE'] == 'KI'){
                        $q2 = "SELECT FM.COMPANY,FM.BUNIT,FM.PK_NUMBER,FM.CREDIT_TYPE,FM.SUB_CREDIT_TYPE,FM.VENDOR,FDR.CURRENCY FROM FUNDS_MASTER FM LEFT JOIN FUNDS_DETAIL_KI FDR ON FDR.UUID = FM.UUID WHERE FM.UUID = '".$param['UUID']."' AND FM.SUB_CREDIT_TYPE = '".$param['SUB_CREDIT_TYPE']."'";
                        $getDetails = $this->db->query($q2)->row();
                    }
                
                    //DND!!!
                $Data["ID"] = $this->uuid->v4();
                    // var_dump($this->db->last_query());exit;
                $cf = [
                        "DEPARTMENT" => 'BANK-RELATION',
                        "COMPANY" => $getDetails->COMPANY,
                        "BUSINESSUNIT" => $getDetails->BUNIT,
                        "DOCNUMBER" => $que->PK_ID,
                        "DOCTYPE" => $getDetails->CREDIT_TYPE,
                        "VENDOR" => $getDetails->VENDOR,
                        "CURRENCY" => $getDetails->CURRENCY,
                        "EXTSYS" => 'SAPHANA',
                        "VAT" => "",
                        "RATE" => 1,
                        "REMARK" => "",
                        "AMOUNT_INCLUDE_VAT" => $AMOUNTCF,
                        "TOTAL_BAYAR" => $AMOUNTCF,
                        "AMOUNT_PPH" => 0,
                        "FCEDIT" => $param['USERNAME'],
                        "FCIP" => $Location
                    ];   
                    ini_set('display_errors', 'On');
                    // var_dump($this->oracle_date('date'));exit;
                    try {
                        $resultcf = $this->db->set('LASTUPDATE', "SYSDATE", false)
                        ->set('DOCDATE', "TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy')", false)
                        ->set('DUEDATE', "TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy')", false)
                        // ->set('DOCDATE', "ADD_MONTHS(TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy'),1)", false)
                        // ->set('DUEDATE', "ADD_MONTHS(TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy'),1)", false)
                        // ->set('DOCDATE', "TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                        // ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        $cf["ID"]   = $Data["ID"];
                        $cf["ISACTIVE"] = "TRUE";
                        $cf["FCENTRY"] = $param['USERNAME'];
                        $resultcf = $resultcf->set($cf)->insert($this->CF_TRANSACTION);
                    } catch (Exception $ex) {
                        $this->db->trans_rollback();
                        $return = [
                            'STATUS' => FALSE,
                            'MESSAGE' => $ex->getMessage()
                        ];
                        throw new Exception ($ex->getMessage());
                    }
                    $last = $resultcf;
                    // var_dump($last); exit;
                    //DND!!!
                    // if($resultcf){
                        
                    //     // start cf details
                    //     $cf_det = [
                    //         "ID" => $Data["ID"],
                    //         'MATERIAL' => $getDetails->ITEM_CODE,
                    //         'REMARKS' => '',
                    //         'AMOUNT_INCLUDE_VAT' => $cf['AMOUNT_INCLUDE_VAT'],
                    //         'AMOUNT_PPH' => 0,
                    //         "ISACTIVE" => "TRUE",
                    //         "FCENTRY" => $param["USERNAME"],
                    //         "FCEDIT" => $param["USERNAME"],
                    //         "FCIP" => $Location
                    //     ];
                    //     $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                    //                     ->set($cf_det)->insert($this->CF_TRANSACTION_DET);
                    // }
            // var_dump($this->db->last_query());
            //
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE)
            {
                $res = "Accept Error";
                $this->db->trans_rollback();
            }
            else if($last){
                $res = "Data Updated";
                $this->db->trans_commit();
            }
            else{
                $res = "Rollback";
                $this->db->trans_rollback();
            }
        
            // $this->db->trans_commit();
            $return = [
                'STATUS' => TRUE,
                'MESSAGE' => $res
            ];
            // var_dump($return); exit;
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

    public function acceptWD($param,$Location){
        // $param = $this->input->post();
        // echo "<pre>";
        // var_dump($param);exit;
        try {
                $this->db->trans_begin();
                if($param['STATUS'] == '1'){
                    if($param['SUB_CREDIT_TYPE'] == 'RK' || $param['SUB_CREDIT_TYPE'] == 'WA' || $param['SUB_CREDIT_TYPE'] == 'TL' || $param['SUB_CREDIT_TYPE'] == 'BD' || $param['SUB_CREDIT_TYPE'] == 'KMK_SCF_AR' || $param['SUB_CREDIT_TYPE'] == 'KMK_SCF_AP'){

                        $q = "SELECT FW.ID,
                                FW.UUID,
                                FW.WD_TYPE AS SUB_CREDIT_TYPE_1,
                                FW.SUB_WD_TYPE,
                                FW.AMOUNT,
                                FM.CONTRACT_NUMBER,
                                C.COMPANYCODE,
                                FM.PK_NUMBER,
                                FM.CREDIT_TYPE,
                                FM.SUB_CREDIT_TYPE,
                                CASE 
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' AND FW.SUB_WD_TYPE = 'KMK_SCF_AP' THEN SUB_FDW.ID
                                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' AND FW.SUB_WD_TYPE = 'KMK_SCF_AR' THEN SUB_FDW.ID
                                    ELSE 0 END AS IDDETAPAR, 
                                CASE 
                                    WHEN FW.WD_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'RK'          THEN FRK.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'TL'          THEN FRK.AMOUNT_LIMIT
                                    WHEN FM.SUB_CREDIT_TYPE = 'BD'          THEN FRK.AMOUNT_LIMIT
                                    WHEN FDW.SUB_CREDIT_TYPE is null THEN FDW.AMOUNT_LIMIT
                                    ELSE 0 END AS AMOUNT_LIMIT,
                                CASE
                                    WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AP' THEN SUB_FDW.AMOUNT_LIMIT
                                    WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AR' THEN SUB_FDW.AMOUNT_LIMIT
                                    ELSE 0 END AS SUB_AMOUNT_LIMIT,
                                FW.AMOUNT_BALANCE,
                                FW.SUB_BALANCE,
                                FW.CREATED_AT,
                                FW.BATCHID,
                                FW.STATUS,
                                -- others data
                                FW.VALUE_DATE,
                                FW.DUEDATE,
                                FW.RATE,
                                FW.AMOUNT_CONVERT,
                                FW.PROVISION,
                                FW.INTEREST
                            FROM FUNDS_WITHDRAW FW
                                LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FW.UUID
                                LEFT JOIN (SELECT * FROM FUNDS_DETAIL_WA WHERE SUB_CREDIT_TYPE = 'WA') FDW ON FDW.UUID = FW.UUID
                                LEFT JOIN (SELECT * FROM FUNDS_DETAIL_WA WHERE SUB_CREDIT_TYPE = 'KMK_SCF_AP' OR SUB_CREDIT_TYPE = 'KMK_SCF_AR') SUB_FDW ON SUB_FDW.UUID = FW.UUID
                                LEFT JOIN FUNDS_DETAIL_RK FRK ON FRK.UUID = FW.UUID AND FW.CON_COUNTER = FRK.COUNTER
                                LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                                WHERE (FW.STATUS = '0' OR FW.STATUS = '2') AND FW.UUID = ? AND FW.ID = ? 
                        " ;
                        $que = $this->db->query($q, array($param['UUID'], $param['ID']))->row();
                        $AMOUNT_LIMIT = (int) $que->AMOUNT_LIMIT;
                        $AMOUNT = (int) $que->AMOUNT;
                        if($que->SUB_WD_TYPE == 'KMK_SCF_AP' || $que->SUB_WD_TYPE == 'KMK_SCF_AR') {
                            $qwa = "SELECT * FROM FUNDS_WITHDRAW WHERE UUID = ? AND WD_TYPE = 'WA' AND STATUS = 1 ORDER BY AMOUNT_BALANCE NULLS LAST, ID DESC FETCH FIRST 1 ROWS ONLY" ;
                            $qapar = "SELECT * FROM FUNDS_WITHDRAW WHERE UUID = ? AND SUB_WD_TYPE = '".$param['SUB_WD_TYPE']."' AND STATUS = 1 ORDER BY SUB_BALANCE NULLS LAST, ID DESC FETCH FIRST 1 ROWS ONLY" ;
                            $lastBalanceWA = $this->db->query($qwa, array($param['UUID']))->row();
                            $lastBalanceAPAR = $this->db->query($qapar, array($param['UUID']))->row();
                            $AMOUNT_LIMIT = (int) $que->AMOUNT_LIMIT ;
                            $RATE = (int) $que->RATE ;
                            $AMOUNT = (int) $que->AMOUNT ;
                            $AMOUNT_BALANCE = (int) $lastbalanceWA->AMOUNT_BALANCE ;
                            $SUB_AMOUNT_LIMIT = (int) $que->SUB_AMOUNT_LIMIT;
                            $SUB_BALANCE = (int) $lastBalanceAPAR->SUB_BALANCE;

                            if($lastBalanceWA->AMOUNT_BALANCE == null || $lastBalanceWA == null ) {
                                $amtBalance = (int) round($AMOUNT_LIMIT - ($AMOUNT / $RATE))  ;
                            }
                            else {
                                $amtBalance = $AMOUNT_BALANCE - $AMOUNT ;
                            }
                            if($lastBalanceAPAR->AMOUNT_BALANCE == null || $lastBalanceAPAR == null ) {
                                $amtSubBalance = $SUB_AMOUNT_LIMIT - $AMOUNT ;
                            }
                            else {
                                $amtSubBalance = $SUB_BALANCE - $AMOUNT ;
                            }
                            $dt = [
                                'AMOUNT_BALANCE'    => $amtBalance,
                                'SUB_BALANCE'       => $amtSubBalance,
                                'CREATED_BY'        => $param['USERNAME'],
                                'STATUS'            => '1',
                            ];
                        } else {
                            $q2 = "SELECT * FROM FUNDS_WITHDRAW WHERE UUID = ? AND WD_TYPE = ? AND STATUS = 1 ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY" ;
                            $lastBalance = $this->db->query($q2, array($param['UUID'], $param['SUB_CREDIT_TYPE']))->row();
                            $AMOUNT_BALANCE = (int) $lastBalance->AMOUNT_BALANCE;
                            // var_dump($this->db->last_query());exit;
                            // var_dump($que->AMOUNT_LIMIT, $que->AMOUNT); exit;
                            $AMOUNTCF = $AMOUNT;
                            // $amtBalance = $que->AMOUNT_LIMIT - $que->AMOUNT ;
                            if($AMOUNT_BALANCE == null) {
                            $amtBalance = $AMOUNT_LIMIT - $AMOUNT ;
                            }
                            else {
                                $amtBalance = $AMOUNT_BALANCE - $AMOUNT ;
                            }
                            $dt = [
                                'AMOUNT_BALANCE'    => $amtBalance,
                                'CREATED_BY'        => $param['USERNAME'],
                                'STATUS'            => '1',
                            ];
                        }
                        
                        // $dt = [
                        //     'AMOUNT_BALANCE'    => $amtBalance,
                        //     'CREATED_BY'        => $param['USERNAME'],
                        //     'STATUS'            => '1',                            
                        //     'UUID'              => $que->UUID,
                        //     'VALUE_DATE'        => $que->VALUE_DATE,
                        //     'DUEDATE'           => $que->DUEDATE,
                        //     'RATE'              => $que->RATE,
                        //     'AMOUNT_CONVERT'    => $que->AMOUNT_CONVERT,
                        //     'AMOUNT'            => $que->AMOUNT,
                        //     'WD_TYPE'           => $que->SUB_CREDIT_TYPE_1,
                        //     'PROVISION'         => $que->PROVISION,
                        //     'INTEREST'          => $que->INTEREST,
                        //     'BATCHID'           => $que->BATCHID
                        // ];
                        $last = $this->db->set('CREATED_AT','SYSDATE',false);
                        $last = $this->db->set($dt)->where("UUID = '".$param['UUID']."' AND ID = '".$param['ID']."'")->update('FUNDS_WITHDRAW');
                        
                        // $pre    = $this->db->set("STATUS", '1');
                        // $pre    = $this->db->where("UUID = '".$param['UUID']."' AND ID = '".$param['ID']."'")->update('FUNDS_WITHDRAW');
                        // // var_dump($pre);
                        // // exit;
                        // if($pre) {
                        //     $last   = $this->db->set('CREATED_AT','SYSDATE',false);
                        //     $last   = $this->db->set($dt)->insert('FUNDS_WITHDRAW');
                        // }
                    }
                    if($param['SUB_CREDIT_TYPE'] == 'FINANCING' || $param['SUB_CREDIT_TYPE'] == 'REFINANCING'){
                        $q = "SELECT 
                                WDKI.ID,
                                WDKI.UUID,
                                WDKI.TRANCHE_NUMBER,
                                WDKI.DRAWDOWN_VALUE,
                                WDKI.BALANCE,
                                -- others data
                                WDKI.DRAWDOWN,
                                WDKI.VALUE_DATE,
                                WDKI.RATE_IDRUSD,
                                WDKI.RATE_CNYUSD,
                                WDKI.RATE_SGDUSD,
                                FDKI.LIMIT_TRANCHE
                            FROM FUNDS_WD_KI WDKI
                                LEFT JOIN FUNDS_DETAIL_KI_TRANCHE
                                FDKI ON FDKI.UUID = WDKI.UUID AND WDKI.TRANCHE_NUMBER = FDKI.TRANCHE_NUMBER
                                WHERE WDKI.UUID = ? AND WDKI.ID = ?" ;
                        $que = $this->db->query($q, array($param['UUID'], $param['ID']));
                        if($que->BALANCE == null) {
                        $updateBalance = $que->LIMIT_TRANCHE - $que->DRAWDOWN_VALUE ;
                        }
                        else {
                            $updateBalance = $que->BALANCE - $que->DRAWDOWN_VALUE ;
                        }

                        $AMOUNTCF = $que->DRAWDOWN_VALUE;

                        $dt = [
                            'BALANCE'       => $updateBalance,
                            'STATUS'        => 1,
                            'CREATED_BY'    => $param['USERNAME'],
                            'DRAWDOWN'      => $que->DRAWDOWN,
                            'DRAWDOWN_VALUE'=> $que->DRAWDOWN_VALUE,
                            'VALUE_DATE'    => $que->VALUE_DATE,
                            'RATE_IDRUSD'   => $que->RATE_IDRUSD,
                            'RATE_CNYUSD'   => $que->RATE_CNYUSD,
                            'RATE_SGDUSD'   => $que->RATE_SGDUSD,
                            'TRANCHE_NUMBER'=> $que->TRANCHE_NUMBER,
                            'UUID'          => $que->UUID
                        ] ;
                        $pre = $this->db->set("STATUS", '1')->set('CREATED_BY',$param['USERNAME'])->set('CREATED_AT', "SYSDATE", false);
                        $pre = $this->db->where("UUID = '".$param['UUID']."' AND ID = '".$param['ID']."'")->update('FUNDS_WD_KI');
                        // var_dump($pre);
                        // exit;
                        if($pre) {
                            $last   = $this->db->set('CREATED_AT','SYSDATE',false);
                            $last   = $this->db->set($dt)->insert('FUNDS_WD_KI');
                        }
                        // var_dump($this->db->last_query());
                        //
                    }
                }else{
                    
                    if($param['SUB_CREDIT_TYPE'] == 'RK' || $param['SUB_CREDIT_TYPE'] == 'WA' || $param['SUB_CREDIT_TYPE'] == 'TL'){
                        $this->db->set('STATUS','2');
                        $this->db->set('CREATED_BY',$param['USERNAME']);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->where('UUID',$param['UUID']);
                        $this->db->where('ID',$param['ID']);
                        $last = $this->db->update('FUNDS_WITHDRAW');
                    }
                }
                // var_dump($AMOUNT, $AMOUNT_LIMIT,$amtBalance); exit;
                if($param['STATUS'] == '1' && $last){
                    if($param['SUB_CREDIT_TYPE'] == 'RK' || $param['SUB_CREDIT_TYPE'] == 'BD' || $param['SUB_CREDIT_TYPE'] == 'TL'){
                        $q2 = "SELECT FM.COMPANY,FM.BUNIT,FM.PK_NUMBER,FM.CREDIT_TYPE,FM.SUB_CREDIT_TYPE,FM.VENDOR,FDR.CURRENCY,FDR.RATE FROM FUNDS_MASTER FM LEFT JOIN FUNDS_DETAIL_RK FDR ON FDR.UUID = FM.UUID WHERE FM.UUID = '".$param['UUID']."' AND FM.SUB_CREDIT_TYPE = '".$param['SUB_CREDIT_TYPE']."'";
                        $getDetails = $this->db->query($q2)->row();
                    }
                    if($param['SUB_CREDIT_TYPE'] == 'WA' || $param['SUB_CREDIT_TYPE'] == 'KMK_SCF_AP' || $param['SUB_CREDIT_TYPE'] == 'KMK_SCF_AR'){
                        $q2 = "SELECT FM.COMPANY,FM.BUNIT,FM.PK_NUMBER,FM.CREDIT_TYPE,FM.SUB_CREDIT_TYPE,FM.VENDOR,FDW.CURRENCY,FDW.RATE FROM FUNDS_MASTER FM LEFT JOIN FUNDS_DETAIL_WA FDW ON FDW.UUID = FM.UUID WHERE FM.UUID = '".$param['UUID']."'";
                        $getDetails = $this->db->query($q2)->row();
                    }
                    $Data["ID"] = $this->uuid->v4();
                    // var_dump($this->db->last_query());exit;
                    $cf = [
                            "DEPARTMENT" => 'BANK-RELATION',
                            "COMPANY" => $getDetails->COMPANY,
                            "BUSINESSUNIT" => $getDetails->BUNIT,
                            "DOCNUMBER" => $getDetails->PK_NUMBER,
                            "DOCTYPE" => $getDetails->CREDIT_TYPE,
                            "VENDOR" => $getDetails->VENDOR,
                            "CURRENCY" => $getDetails->CURRENCY,
                            "EXTSYS" => 'SAPHANA',
                            "VAT" => "",
                            "RATE" => isset($getDetails->RATE) ? $getDetails->RATE : 1,
                            "REMARK" => "",
                            "AMOUNT_INCLUDE_VAT" => $AMOUNTCF,
                            "TOTAL_BAYAR" => $AMOUNTCF,
                            "AMOUNT_PPH" => 0,
                            "FCEDIT" => $param['USERNAME'],
                            "FCIP" => $Location
                        ];   
                        ini_set('display_errors', 'On');
                        // var_dump($this->oracle_date('date'));exit;
                        $resultcf = $this->db->set('LASTUPDATE', "SYSDATE", false)
                        // ->set('DOCDATE', "TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy')", false)
                        // ->set('DUEDATE', "TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy')", false)
                        ->set('DOCDATE', "ADD_MONTHS(TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy'),1)", false)
                        ->set('DUEDATE', "ADD_MONTHS(TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy'),1)", false)
                        // ->set('DOCDATE', "TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                        // ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        
                        $cf["ID"]   = $Data["ID"];
                        $cf["ISACTIVE"] = "TRUE";
                        $cf["FCENTRY"] = $param['USERNAME'];
                        $resultcf = $resultcf->set($cf)->insert($this->CF_TRANSACTION);

                        // if($resultcf){
                            
                        //     // start cf details
                        //     $cf_det = [
                        //         "ID" => $Data["ID"],
                        //         'MATERIAL' => $getDetails->ITEM_CODE,
                        //         'REMARKS' => '',
                        //         'AMOUNT_INCLUDE_VAT' => $cf['AMOUNT_INCLUDE_VAT'],
                        //         'AMOUNT_PPH' => 0,
                        //         "ISACTIVE" => "TRUE",
                        //         "FCENTRY" => $param["USERNAME"],
                        //         "FCEDIT" => $param["USERNAME"],
                        //         "FCIP" => $Location
                        //     ];
                        //     $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                        //                     ->set($cf_det)->insert($this->CF_TRANSACTION_DET);
                        // }
                }

                if ($this->db->trans_status() === FALSE)
                {
                    $res = "Accept Error";
                    $this->db->trans_rollback();
                }
                else if($last){
                    $res = "Data Updated";
                    $this->db->trans_commit();
                }
                else{
                    $res = "Rollback";
                    $this->db->trans_rollback();
                }
            
            // $this->db->trans_commit();
            $return = [
                'STATUS' => TRUE,
                'MESSAGE' => $res
            ];
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

    public function oracle_date($timestamp=''){
        $this->load->helper('date');
        if($timestamp=='date'){
            $datestring = '%d-%m-%Y';
        }
        else{
            $datestring = '%d-%m-%Y %h.%i.%s %a';
        }

        $time = time();
        $timestamp = strtoupper(mdate($datestring, $time));
        return $timestamp;
    }

    // WITHDRAW DATA
    public function SaveWDbyId($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $WD_TYPE        = $param['WD_TYPE'];
            $AMOUNT         = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT']));
            $RATE           = intval(preg_replace("/[^\d\.\-]/","",$param['RATE']));
            // $AMOUNT_LIMIT   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_LIMIT']));
            $q   = "SELECT * FROM FUNDS_WITHDRAW WHERE UUID = '".$param['UUID']."' AND ID = '".$param['IDDET']."' AND STATUS = '1' ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY";
            $getBalance = $this->db->query($q)->row();
            // var_dump($this->db->last_query());exit;
            
            
            if($getBalance->AMOUNT_BALANCE != null){
                if($AMOUNT > $getBalance->AMOUNT_BALANCE){
                    $msg = "Amount Exceed";
                    throw new Exception($msg);
                }else{
                    $AMOUNT_LIMIT = $getBalance->AMOUNT_BALANCE;    
                }
            }
            // var_dump($AMOUNT_LIMIT);exit;
            // STATUS 0 BELUM DI ACC
            $dt = [
                'AMOUNT' => $AMOUNT,
                // 'AMOUNT_BALANCE' => $AMOUNT_LIMIT - $AMOUNT,
                'WD_TYPE' => $param['WD_TYPE']
            ];

            if($WD_TYPE == 'WA'){
                $dt['RATE']           = $RATE;
                $dt['AMOUNT_CONVERT'] = $AMOUNT * $RATE;
            }

            if($WD_TYPE == 'TL' || $WD_TYPE == 'WA'){
                $this->db->set("DUEDATE","TO_DATE('" . $param['DUEDATE'] . "','yyyy-mm-dd')", false);
                $this->db->set("VALUE_DATE","TO_DATE('" . $param['VALUE_DATE'] . "','yyyy-mm-dd')", false);
            }
            $result1 = $this->db->set('CREATED_AT', "SYSDATE", false);

            // $cekDup = $this->db->get_where('FUNDS_WITHDRAW',['UUID' => $param['UUID']])->row();
            // if ($cekDup == NULL) {
                $dt['UUID'] = $param['UUID'];
                $dt['CREATED_BY'] = $param['USERNAME'];
                // $result1 = $result1->set($dt)->insert('FUNDS_WITHDRAW');    
                
            // } else {
            //     $dt['CREATED_BY'] = $param['USERNAME'];
                $result1 = $result1->set($dt)
                        ->where(['UUID' => $param['UUID'],'ID'=>$param['IDDET']])
                        ->update('FUNDS_WITHDRAW');
            // }

            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $msg = "Data has been Successfully Saved !!";
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $msg
                    // 'IDS' => $dt['UUID']
                ];
            } else {
                $this->db->trans_rollback(); 
                throw new Exception($msg);
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
    // END WITHDRAW DATA

    public function ShowData($param) {
        $URL = $param['URL'];
        $SELECT = "";
        $JOIN   = "";
        $WHERE  = "";

        if($URL == "KI"){
            $SELECT .= " ,FM.SUB_CREDIT_TYPE, FKI.DOCDATE, FKI.CONTRACT_NUMBER, FM.PK_NUMBER";            
            // $SELECT .= " ,FDK.DOCDATE,FDKT.LIMIT_TRANCHE AS AMOUNT_LIMIT";
            $JOIN   .= " LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
            LEFT JOIN (SELECT FR.UUID,
                                FR.SUB_CREDIT_TYPE,
                                FDR.AMOUNT_LIMIT,
                                FDR.CONTRACT_NUMBER,
                                FR.DOCDATE,
                                FR.ISACTIVE,
                                FR.IS_ACC
                        FROM FUNDS_DETAIL_KI FR
                                LEFT JOIN
                                (SELECT UUID, SUM(LIMIT_TRANCHE) AS AMOUNT_LIMIT, MAX(CONTRACT_NUMBER) AS CONTRACT_NUMBER
                                FROM FUNDS_DETAIL_KI_TRANCHE GROUP BY UUID) FDR
                                ON FR.UUID = FDR.UUID AND FR.IS_COMPLETE IS NULL) FKI ON FM.UUID = FKI.UUID AND FKI.ISACTIVE = 1";
            $WHERE  .= " WHERE FM.CREDIT_TYPE = 'KI' AND FM.ISACTIVE = 1 AND FM.IS_COMPLETE IS NULL ";
        }
        if($URL == "RK"){
            $SELECT .= ",FM.SUB_CREDIT_TYPE,FDR.AMOUNT_LIMIT,FDR.CONTRACT_NUMBER,FDR.DOCDATE";
            // $JOIN   .= " LEFT JOIN (SELECT * FROM FUNDS_DETAIL_RK WHERE ISACTIVE = '1') FDR ON FDR.UUID = FM.UUID ";   
            $JOIN   .= " LEFT JOIN (SELECT DISTINCT FR.UUID,
            FR.SUB_CREDIT_TYPE,
            FR.AMOUNT_LIMIT,
            FR.CONTRACT_NUMBER,
            FR.DOCDATE
            FROM FUNDS_DETAIL_RK FR
            WHERE FR.ISACTIVE = '1') FDR ON FDR.UUID = FM.UUID ";   
            $WHERE  .= " WHERE FM.SUB_CREDIT_TYPE = 'RK' OR FM.SUB_CREDIT_TYPE = 'BD' OR FM.SUB_CREDIT_TYPE = 'TL'";
        }
        if($URL == "WA"){
            $SELECT .= " ,FM.SUB_CREDIT_TYPE,FDW.DOCDATE, FDW.AMOUNT_LIMIT";
            $JOIN   .= " LEFT JOIN (SELECT * FROM FUNDS_DETAIL_WA WHERE ISACTIVE = '1') FDW ON FDW.UUID = FM.UUID ";   
            $WHERE  .= " WHERE FM.SUB_CREDIT_TYPE = 'WA' AND FDW.POS = '0' ";
        }
        $q      = "SELECT FM.COMPANY, FM.UUID,FM.IS_ACC, FM.CREATED_AT, C.COMPANYCODE, FM.PK_NUMBER, B.FCNAME ".$SELECT;
        $q     .= " FROM FUNDS_MASTER FM LEFT JOIN COMPANY C ON C.ID = FM.COMPANY LEFT JOIN SUPPLIER B ON B.ID = FM.BANK ".$JOIN.$WHERE."ORDER BY FM.CREATED_AT DESC ";
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());
        return $result;
    }
    

    #save master
    public function Save($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit;
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $AMOUNT_PER_MONTH   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PER_MONTH']));

            $dt = [
                'PK_NUMBER' => $param['PK_NUMBER'],
                'COMPANY' => $param['COMPANY'],
                'BUNIT' => $param['BUSINESSUNIT'],
                'BANK' => $param['BANK'],
                'CREDIT_TYPE' => $param['CREDIT_TYPE'],
                'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE'],
                'CREATED_BY' => $param['USERNAME'],
                'IDC_STATUS' => $param['IDC_STATUS'],
                'KI_TYPE'   => $param['CTYPE'],
                'CONTRACT_REF' => $param['CONTRACT_REF'],
                'ISACTIVE' => 1,
                'IS_ACC' => 0
            ];

            $getBank         = $this->db->get_where('BANK',['FCCODE' => $param['BANK'] ])->row()->FCNAME;
            if($getBank == "MANDIRI"){
                $vendorID = "2ea62b32-1203-47ec-bdcd-bd7795370a10";
            }
            if($getBank == "SEA BANK" || $getBank == "SEABANK"){
                $vendorID = "58837088-3d91-452d-b715-3ef5412b2159";
            }
            if($getBank == "BCA"){
                $vendorID = "58837088-3d91-452d-b715-3ef5412b2160";
            }
            if($getBank == "BRI"){
                $vendorID = "95ea56f6-765d-4e21-bf21-0ab06b22f16e";
            }
            $dt['VENDOR'] = $vendorID;
            $result1 = $this->db->set('CREATED_AT', "SYSDATE", false);
            if($param['CREDIT_TYPE'] == 'KI') {
                $SQL = "SELECT * FROM FUNDS_MASTER WHERE PK_NUMBER = ? AND CREDIT_TYPE = ?";
                $Cek = $this->db->query($SQL, array($param['PK_NUMBER'], $param['CREDIT_TYPE']));
            } 
            else if ($param['CREDIT_TYPE'] == 'KMK') {
                $SQL = "SELECT * FROM FUNDS_MASTER WHERE PK_NUMBER = ? AND CREDIT_TYPE = ? AND SUB_CREDIT_TYPE = ?";
                $Cek = $this->db->query($SQL, array($param['PK_NUMBER'], $param['CREDIT_TYPE'], $param['SUB_CREDIT_TYPE']));                
            }
            if ($Cek->num_rows() > 0) {
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'PK_NUMBER Sudah Ada'
                ];
            }else{
                if ($param['ACTION'] == 'ADD') {            
                    $dt['UUID'] = $this->uuid->v4();
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $result1 = $result1->set($dt)->insert('FUNDS_MASTER');    
                    
                    
                } elseif ($param['ACTION'] == 'EDIT') {
                    $dt['UUID'] = $param['ID'];
                    $result1 = $result1->set($dt)
                            ->where(['UUID' => $param['ID']])
                            ->update('FUNDS_MASTER');
                }

                if ($result1) {
                    $result = TRUE;
                }
                if ($result) {
                    $this->db->trans_commit();
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => 'Data has been Successfully Saved !!',
                        'IDS' => $dt['UUID']
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

    public function SaveRK($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $AMOUNT_LIMIT   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNTLIMIT']));
            $ADMIN_FEE   = intval(preg_replace("/[^\d\.\-]/","",$param['ADMIN_FEE']));
            if($param['ADDENDUM_DATE'] == null || $param['ADDENDUM_DATE'] == '') {
                $param['ADDENDUM_DATE'] = $param['DOCDATE'] ;
            }
            $dt = [
                'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE'],
                'AMOUNT_LIMIT' => $AMOUNT_LIMIT,
                'FEE' => $param['FEE'],
                'UPFRONT_FEE' => $param['UPFRONT_FEE'],
                'ANNUAL_FEE' => $param['ANNUAL_FEE'],
                'ADMIN_FEE' => $ADMIN_FEE,
                'PRE_PAYMENT_PENALTY' => $param['PRE_PAYMENT_PENALTY'],
                'ADM_FEE_CURRENCY' => $param['ADM_FEE_CURRENCY'],
                'RATE' => $param['RATE'],
                'TENOR' => $param['TENOR'],
                'CURRENCY' => $param['CURRENCY'],
                'INTEREST' => $param['INTEREST'],
                'PROVISI' => $param['PROVISI'],
                'LOAN_ACCOUNT_NUMBER' => $param['LOAN_ACCOUNT_NUMBER'],
                // 'INSTALLMENT_PERIOD' => $param['INSTALLMENT_PERIOD'],
                'INTEREST_PAYMENT_SCHEDULE' => $param['INTEREST_PAYMENT_SCHEDULE'],
                'INTEREST_PAYMENT_SCHEDULE_DATE' => $param['INTEREST_PAYMENT_SCHEDULE_DATE'],
                'UUID' => $param['UUID'],
                'ADD_REMARK' => $param['ADD_REMARK'],
                'PAYMENT_BANK_ACC' => $param['PAYMENT_BANK_ACCOUNT'],
                'IS_ACC' => 0
            ];
            $result1 = $this->db->set('CREATED_AT', "SYSDATE", false)
                        ->set("DOCDATE","TO_DATE('" . $param['DOCDATE'] . "','yyyy-mm-dd')", false)
                        ->set("MATURITY_DATE","TO_DATE('" . $param['MATURITY_DATE'] . "','yyyy-mm-dd')", false)
                        ->set("FIRST_DATE_INTEREST_PAYMENT","TO_DATE('" . $param['FIRST_DATE_INTEREST_PAYMENT'] . "','yyyy-mm-dd')", false)
                        ->set("ADDENDUM_DATE","TO_DATE('" . $param['ADDENDUM_DATE'] . "','yyyy-mm-dd')", false);
                        // ->set("INTEREST_PERIOD_FROM","TO_DATE('" . $param['INTEREST_PERIOD_FROM'] . "','yyyy-mm-dd')", false)
                        // ->set("INTEREST_PERIOD_TO","TO_DATE('" . $param['INTEREST_PERIOD_TO'] . "','yyyy-mm-dd')", false);

            $cekDup = "SELECT * FROM FUNDS_DETAIL_RK WHERE UUID = '".$param['UUID']."' ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY";
            $cekDup = $this->db->query($cekDup)->row();
            // var_dump($cekDup);exit;
            if ($cekDup == NULL) {
                
                // end generate number contract
                $dt['ISACTIVE']     = 1;
                $dt['SUB_CREDIT_TYPE'] = $param['SUB_CREDIT_TYPE'];
                $dt['CREATED_BY']   = $param['USERNAME'];
                $dt['COUNTER'] = 0 ;
                $result1 = $result1->set($dt)->insert('FUNDS_DETAIL_RK');    

                $checkCompany = $this->db->query("SELECT C.COMPANY_SUBGROUP,CE.EXTSYSCOMPANYCODE FROM COMPANY C INNER JOIN COMPANY_EXTSYS CE ON CE.COMPANY = C.ID WHERE C.ID = '".$param['COMPANY']."' AND CE.EXTSYSTEM = 'SAPHANA'")->row();

                #start generate number contract
                if($checkCompany->COMPANY_SUBGROUP === "UPSTREAM"){
                    $comp = "01".$checkCompany->EXTSYSCOMPANYCODE;
                }
                if($checkCompany->COMPANY_SUBGROUP === "DOWNSTREAM"){
                    $comp = "02".$checkCompany->EXTSYSCOMPANYCODE;
                }
                if($checkCompany->COMPANY_SUBGROUP === "CEMENT"){
                    $comp = "03".$checkCompany->EXTSYSCOMPANYCODE;
                }
                if($checkCompany->COMPANY_SUBGROUP === "PROPERTY"){
                    $comp = "04".$checkCompany->EXTSYSCOMPANYCODE;
                }
                if($checkCompany->COMPANY_SUBGROUP == '' || $checkCompany == null){
                    $comp = "05".$checkCompany->EXTSYSCOMPANYCODE;
                }
                
                $qgenid = $this->db->query("SELECT NVL(COUNTER,0)+1 AS GENID FROM FUNDS_MASTER WHERE COUNTER IS NOT NULL ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY")->row()->GENID;
                $qgenid =  sprintf('%03d', $qgenid);

                //
                if(($param['SUB_CREDIT_TYPE'] == 'RK' && $param['CURRENCY'] == 'IDR') || ($param['SUB_CREDIT_TYPE'] == 'TL' && $param['CURRENCY'] == 'IDR') || ($param['SUB_CREDIT_TYPE'] == 'BD' && $param['CURRENCY'] == 'IDR' || ($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'IDR'))){
                    $code = 'WL';
                }

                if(($param['SUB_CREDIT_TYPE'] == 'RK' && $param['CURRENCY'] == 'USD') || ($param['SUB_CREDIT_TYPE'] == 'TL' && $param['CURRENCY'] == 'USD') || ($param['SUB_CREDIT_TYPE'] == 'BD' && $param['CURRENCY'] == 'USD') || ($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'USD')){
                    $code = 'WF';
                }

                if(($param['SUB_CREDIT_TYPE'] == 'RK' && $param['CURRENCY'] == 'US$') || ($param['SUB_CREDIT_TYPE'] == 'TL' && $param['CURRENCY'] == 'US$') || ($param['SUB_CREDIT_TYPE'] == 'BD' && $param['CURRENCY'] == 'US$') || ($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'US$')){
                    $code = 'WF';
                }

                $getMaster       = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
                if($getMaster->SUB_CREDIT_TYPE == 'WA'){
                    $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_WA',['UUID' => $param['UUID']])->row();
                }
                if($getMaster->SUB_CREDIT_TYPE == 'RK' || $getMaster->SUB_CREDIT_TYPE == 'TL' || $getMaster->SUB_CREDIT_TYPE == 'BD'){
                    $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_RK',['UUID' => $param['UUID']])->row();
                }
                $codeTranche = "B";
                if($getMaster->SUB_CREDIT_TYPE == 'REFINANCING' || $getMaster->SUB_CREDIT_TYPE == 'FINANCING'){
                    $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_KI',['UUID' => $param['UUID']])->row();
                    if($getMaster->IDC_STATUS == 'WITH_IDC'){
                        $jenisIDC = "1";
                    }else{
                        $jenisIDC = "0";
                    }
                    $codeTranche = "A";
                }
                
                $getBank         = $this->db->get_where('BANK',['FCCODE' => $getMaster->BANK ])->row();
                $BIC = $getBank->BIC;
                
                $genid  = Date('y').$comp.$code.$BIC.$codeTranche.$jenisIDC.$qgenid;

                $cekDup1 = $this->db->get_where('FUNDS_DETAIL_RK',['CONTRACT_NUMBER' => $genid])->row();
                #tinggal get BIC 
                if($cekDup1 == NULL){
                    $genid = $genid;
                }else{
                    $qgenid = $this->db->query("SELECT NVL(COUNTER,0)+1 AS GENID FROM FUNDS_MASTER WHERE COUNTER IS NOT NULL ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY")->row()->GENID;
                    $qgenid =  sprintf('%03d', $qgenid);
                    $genid  = Date('y').$comp.$code.$BIC.$codeTranche.$jenisIDC.$qgenid;
                }
                $this->db->set('CONTRACT_NUMBER',$genid);
                $this->db->where('UUID',$param['UUID']);
                $this->db->where('SUB_CREDIT_TYPE', $param['SUB_CREDIT_TYPE']);
                $upd = $this->db->update('FUNDS_DETAIL_RK');
                if($upd){
                    $this->db->set('COUNTER',$qgenid);
                    $this->db->where('UUID',$param['UUID']);
                    $this->db->update('FUNDS_MASTER');
                }
                
                //set Counter Addendum to 0
            }
            else if ($cekDup->IS_ACC == "1") {
                $this->db->set('CREATED_AT', "SYSDATE", false);
                $dt['CREATED_BY']   = $param['USERNAME'];
                $dt['IS_ADDENDUM']  = "1";
                $dt['REFER_ID']     = $param['UUID'];
                $dt['ISACTIVE']     = 1;
                $dt['SUB_CREDIT_TYPE'] = $param['SUB_CREDIT_TYPE'];
                $dt['CONTRACT_NUMBER'] = $cekDup->CONTRACT_NUMBER ;
                $dt['COUNTER'] = intval($cekDup->COUNTER) + 1;
                $result1->set($dt)->insert('FUNDS_DETAIL_RK');    
                $this->db->set('ISACTIVE',0);
                $this->db->where(['UUID' => $param['UUID'],'ID'=>$cekDup->ID]);
                $this->db->update('FUNDS_DETAIL_RK');
                $this->db->set('IS_ACC',0);
                $this->db->where('UUID',$param['UUID']);
                $this->db->update('FUNDS_MASTER');
                
            } 
            else {
                $dt['CREATED_BY'] = $param['USERNAME'];
                $result1 = $result1->set($dt)
                        ->where(['UUID' => $param['UUID'],'ID'=>$cekDup->ID])
                        ->update('FUNDS_DETAIL_RK');
            }
            
            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!',
                    'IDS' => $dt['UUID'],
                    'CONTRACT_NUMBER' => $genid ? $genid : $cekDup->CONTRACT_NUMBER
                ];
            } else {
                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
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

    public function SaveWA($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit();
        
        $cekData = $this->db->get_where('FUNDS_DETAIL_WA',['UUID'=>$param['UUID'],'SUB_CREDIT_TYPE'=>$param['SUB_CREDIT_TYPE']]);
        if($param['ACTIONM'] == "ADD"){
            if($cekData->num_rows() > 0){
                throw new Exception($param['SUB_CREDIT_TYPE']." Sudah Ada");
            }
        }

        try {
            $this->db->trans_begin();
            $result = FALSE;
            $AMOUNT_LIMIT   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNTLIMIT']));
            $dt = [
                'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE'],
                'AMOUNT_LIMIT' => $AMOUNT_LIMIT,
                'FEE' => $param['FEE'],
                'CURRENCY' => $param['CURRENCY'],
                'INTEREST' => $param['INTEREST'],
                'TENOR' => $param['TENOR'],
                'LOAN_ACCOUNT_NUMBER' => $param['LOAN_ACCOUNT_NUMBER'],
                'INSTALLMENT_PERIOD' => $param['INSTALLMENT_PERIOD'],
                'UUID' => $param['UUID'],
                'IS_ACC' => 0,
                'INTEREST_PAYMENT_SCHEDULE_DATE' => $param['INTEREST_PAYMENT_SCHEDULE_DATE'],
                'INTEREST_PAYMENT_SCHEDULE' => $param['INTEREST_PAYMENT_SCHEDULE'],
                'PRE_PAYMENT_PENALTY' => $param['PRE_PAYMENT_PENALTY'],
                'PAYMENT_BANK_ACC' => $param['PAYMENT_BANK_ACC']
                
            ];
            $result1 = $this->db->set('CREATED_AT', "SYSDATE", false)
                        ->set("DOCDATE","TO_DATE('" . $param['DOCDATE'] . "','yyyy-mm-dd')", false)
                        ->set("MATURITY_DATE","TO_DATE('" . $param['MATURITY_DATE'] . "','yyyy-mm-dd')", false)
                        ->set("FIRST_DATE_INTEREST_PAYMENT","TO_DATE('" . $param['FIRST_DATE_INTEREST_PAYMENT'] . "','yyyy-mm-dd')", false);

            #opt 1 itu scf_ap / scf_ar - opt 0 itu wa
            if($param['OPT'] == 1){
                $cekDup = $this->db->get_where('FUNDS_DETAIL_WA',['UUID' => $param['UUID'],'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE']])->row();
               // var_dump($this->db->last_query());exit;
                if ($cekDup == NULL) {
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $dt['ISACTIVE'] = 1;
                    $dt['IS_ACC'] = 1;
                    $result1 = $result1->set($dt)->insert('FUNDS_DETAIL_WA');    
                    $checkCompany = $this->db->query("SELECT C.COMPANY_SUBGROUP,CE.EXTSYSCOMPANYCODE FROM COMPANY C INNER JOIN COMPANY_EXTSYS CE ON CE.COMPANY = C.ID WHERE C.ID = '".$param['COMPANY']."' AND CE.EXTSYSTEM = 'SAPHANA'")->row();

                    #start generate number contract
                    if($checkCompany->COMPANY_SUBGROUP === "UPSTREAM"){
                        $comp = "01".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "DOWNSTREAM"){
                        $comp = "02".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "CEMENT"){
                        $comp = "03".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "PROPERTY"){
                        $comp = "04".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP == '' || $checkCompany == null){
                        $comp = "05".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    
                    $qgenid = $this->db->query("SELECT NVL(COUNTER,0)+1 AS GENID FROM FUNDS_MASTER WHERE COUNTER IS NOT NULL ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY")->row()->GENID;
                    $qgenid =  sprintf('%03d', $qgenid);

                    //
                    if(($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'IDR')){
                        $code = 'WL';
                    }

                    if(($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'USD')){
                        $code = 'WF';
                    }

                    if(($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'US$')){
                        $code = 'WF';
                    }

                    $getMaster       = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
                    if($getMaster->SUB_CREDIT_TYPE == 'WA'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_WA',['UUID' => $param['UUID']])->row();
                    }
                    if($getMaster->SUB_CREDIT_TYPE == 'RK' || $getMaster->SUB_CREDIT_TYPE == 'TL' || $getMaster->SUB_CREDIT_TYPE == 'BD'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_RK',['UUID' => $param['UUID']])->row();
                    }
                    $codeTranche = "B";
                    if($getMaster->SUB_CREDIT_TYPE == 'REFINANCING' || $getMaster->SUB_CREDIT_TYPE == 'FINANCING'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_KI',['UUID' => $param['UUID']])->row();
                        if($getMaster->IDC_STATUS == 'WITH_IDC'){
                            $jenisIDC = "1";
                        }else{
                            $jenisIDC = "0";
                        }
                        $codeTranche = "A";
                    }
                    
                    $getBank   = $this->db->get_where('BANK',['FCCODE' => $getMaster->BANK ])->row();
                    $BIC       = $getBank->BIC;
                    
                    $genid  = Date('y').$comp.$code.$BIC.$codeTranche.$jenisIDC.$qgenid;

                    $cekDup = $this->db->get_where('FUNDS_DETAIL_WA',['CONTRACT_NUMBER' => $genid])->row();
                    #tinggal get BIC 
                    if($cekDup == NULL){
                        $genid = $genid;
                    }else{
                        $qgenid = $this->db->query("SELECT NVL(COUNTER,0)+1 AS GENID FROM FUNDS_MASTER WHERE COUNTER IS NOT NULL ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY")->row()->GENID;
                        $qgenid =  sprintf('%03d', $qgenid);
                        $genid  = Date('y').$comp.$code.$BIC.$codeTranche.$jenisIDC.$qgenid;
                    }
                    $this->db->set('CONTRACT_NUMBER',$genid);
                    $this->db->where('UUID',$param['UUID']);
                    $this->db->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE']);
                    $upd = $this->db->update('FUNDS_DETAIL_WA');
                    if($upd){
                        $this->db->set('COUNTER',$qgenid);
                        $this->db->where('UUID',$param['UUID']);
                        $this->db->update('FUNDS_MASTER');
                    }
                    
                } else {
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $result1 = $result1->set($dt)
                            ->where(['UUID' => $param['UUID'],'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE']])
                            ->update('FUNDS_DETAIL_WA');
                }
            }else{
                $cekDup = "SELECT * FROM FUNDS_DETAIL_WA WHERE UUID = '".$param['UUID']."' AND POS = '0' ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY";
                $cekDup = $this->db->query($cekDup)->row();
                if ($cekDup == NULL) {
                    
                    // end generate number contract
                    $dt['POS'] = 0;
                    $dt['ISACTIVE'] = 1;
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $dt['SUB_CREDIT_TYPE'] = 'WA';
                    $result1 = $result1->set($dt)->insert('FUNDS_DETAIL_WA');    

                    $checkCompany = $this->db->query("SELECT C.COMPANY_SUBGROUP,CE.EXTSYSCOMPANYCODE FROM COMPANY C INNER JOIN COMPANY_EXTSYS CE ON CE.COMPANY = C.ID WHERE C.ID = '".$param['COMPANY']."' AND CE.EXTSYSTEM = 'SAPHANA'")->row();

                    #start generate number contract
                    if($checkCompany->COMPANY_SUBGROUP === "UPSTREAM"){
                        $comp = "01".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "DOWNSTREAM"){
                        $comp = "02".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "CEMENT"){
                        $comp = "03".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "PROPERTY"){
                        $comp = "04".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP == '' || $checkCompany == null){
                        $comp = "05".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    
                    $qgenid = $this->db->query("SELECT NVL(COUNTER,0)+1 AS GENID FROM FUNDS_MASTER WHERE COUNTER IS NOT NULL ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY")->row()->GENID;
                    $qgenid =  sprintf('%03d', $qgenid);

                    //
                    if(($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'IDR')){
                        $code = 'WL';
                    }

                    if(($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'USD')){
                        $code = 'WF';
                    }

                    if(($param['SUB_CREDIT_TYPE'] == 'WA' && $param['CURRENCY'] == 'US$')){
                        $code = 'WF';
                    }

                    $getMaster       = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
                    if($getMaster->SUB_CREDIT_TYPE == 'WA'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_WA',['UUID' => $param['UUID']])->row();
                    }
                    if($getMaster->SUB_CREDIT_TYPE == 'RK' || $getMaster->SUB_CREDIT_TYPE == 'TL' || $getMaster->SUB_CREDIT_TYPE == 'BD'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_RK',['UUID' => $param['UUID']])->row();
                    }
                    $codeTranche = "B";
                    if($getMaster->SUB_CREDIT_TYPE == 'REFINANCING' || $getMaster->SUB_CREDIT_TYPE == 'FINANCING'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_KI',['UUID' => $param['UUID']])->row();
                        if($getMaster->IDC_STATUS == 'WITH_IDC'){
                            $jenisIDC = "1";
                        }else{
                            $jenisIDC = "0";
                        }
                        $codeTranche = "A";
                    }
                    
                    $getBank         = $this->db->get_where('BANK',['FCCODE' => $getMaster->BANK ])->row();
                    $BIC = $getBank->BIC;
                    
                    $genid  = Date('y').$comp.$code.$BIC.$codeTranche.$jenisIDC.$qgenid;

                    $cekDup = $this->db->get_where('FUNDS_DETAIL_WA',['CONTRACT_NUMBER' => $genid])->row();
                    #tinggal get BIC 
                    if($cekDup == NULL){
                        $genid = $genid;
                    }else{
                        $qgenid = $this->db->query("SELECT NVL(COUNTER,0)+1 AS GENID FROM FUNDS_MASTER WHERE COUNTER IS NOT NULL ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY")->row()->GENID;
                        $qgenid =  sprintf('%03d', $qgenid);
                        $genid  = Date('y').$comp.$code.$BIC.$codeTranche.$jenisIDC.$qgenid;
                    }
                    $this->db->set('CONTRACT_NUMBER',$genid);
                    $this->db->where('UUID',$param['UUID']);
                    $this->db->where('SUB_CREDIT_TYPE',$dt['SUB_CREDIT_TYPE']);
                    $upd = $this->db->update('FUNDS_DETAIL_WA');
                    if($upd){
                        $this->db->set('COUNTER',$qgenid);
                        $this->db->where('UUID',$param['UUID']);
                        $this->db->update('FUNDS_MASTER');
                    }
                    
                }
                else if ($cekDup->IS_ACC == "1") {
                    $this->db->set('CREATED_AT', "SYSDATE", false);
                    $dt['CREATED_BY']   = $param['USERNAME'];
                    $dt['IS_ADDENDUM']  = "1";
                    $dt['POS'] = 0;
                    $dt['ISACTIVE'] = 1;
                    $dt['REFER_ID']     = $param['UUID'];
                    $dt['SUB_CREDIT_TYPE'] = 'WA';
                    $result1->set($dt)->insert('FUNDS_DETAIL_WA');   
                    $this->db->set('ISACTIVE',0);
                    $this->db->where(['UUID' => $param['UUID'],'ID'=>$cekDup->ID]);
                    $this->db->update('FUNDS_DETAIL_WA'); 
                    $this->db->set('IS_ACC',0);
                    $this->db->where('UUID',$param['UUID']);
                    $this->db->update('FUNDS_MASTER');
                    
                }
                else {
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $result1 = $result1->set($dt)
                            ->where(['UUID' => $param['UUID'],'POS'=>'0','ID'=>$cekDup->ID])
                            ->update('FUNDS_DETAIL_WA');
                }
            }
            
            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!',
                    'IDS' => $dt['UUID']
                ];
            } else {
                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
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

    #interest period from and to are changed to interest payment schedule
    public function SaveKI($param, $Location) {

        try {
            $this->db->trans_begin();
            $result = FALSE;
            $AGENCY_FEE     = intval(preg_replace("/[^\d\.\-]/","",$param['AGENCY_FEE']));
            $RATE     = intval(preg_replace("/[^\d\.\-]/","",$param['RATE']));
            $ADM_FEE        = intval(preg_replace("/[^\d\.\-]/","",$param['ADM_FEE']));
            $ADDENDUM_DATE  = $param['ADDENDUM_DATE'] ? $param['ADDENDUM_DATE'] : $param['DOCDATE'];
            $dt = [
                'TOBANK' => $param['TOBANK'],
                // 'SUB_CREDIT_TYPE'=> $param['SUB_CREDIT_TYPE'],
                'FEE' => $param['FEE'],
                'ADM_FEE' => $ADM_FEE,
                'AGENCY_FEE' => $AGENCY_FEE,
                'PROVISI' => $param['PROVISI'],
                'UPFRONT_FEE' => $param['UPFRONT_FEE'],
                'ANNUAL_FEE' => $param['ANNUAL_FEE'],
                'COMMIT_FEE' => $param['COMMIT_FEE'],
                'INTEREST' => $param['INTEREST'],
                'TENOR' => $param['TENOR'],
                'CURRENCY' => $param['CURRENCY'],
                'ADM_FEE_CURRENCY' => $param['ADM_FEE_CURRENCY'],
                'AG_FEE_CURRENCY' => $param['AG_FEE_CURRENCY'],
                'LOAN_ACCOUNT_NUMBER' => $param['LOAN_ACCOUNT_NUMBER'],
                'INSTALLMENT_PERIOD' => $param['INSTALLMENT_PERIOD'],
                'INTEREST_PAYMENT_SCHEDULE' => $param['INTEREST_PAYMENT_SCHEDULE'],
                'INTEREST_PAYMENT_SCHEDULE_DATE' => $param['INTEREST_PAYMENT_SCHEDULE_DATE'],
                'INSTALLMENT_TYPE' => $param['INSTALLMENT_TYPE'],
                'UUID' => $param['UUID'],
                'ADD_REMARK' => $param['ADD_REMARK'],
                'PRE_PAYMENT_PENALTY' => $param['PRE_PAYMENT_PENALTY'],
                'AG_FEE_FAC' => $param['AG_FEE_FAC'],
                'REFER_ID' => $param['UUID'],
                'PROVISI_TYPE' => $param['PROVISI_TYPE'],
                'PAYMENT_BANK_ACC' => $param['PAYMENT_BANK_ACCOUNT']
            ];
            // var_dump($dt); exit;
            $result1 = $this->db->set('CREATED_AT', "SYSDATE", false)
                        ->set("FIRST_DATE_INTEREST_PAYMENT","TO_DATE('" . $param['FIRST_DATE_INTEREST_PAYMENT'] . "','yyyy-mm-dd')", false)
                        ->set("DOCDATE","TO_DATE('" . $param['DOCDATE'] . "','yyyy-mm-dd')", false)
                        ->set("ADDENDUM_DATE","TO_DATE('" . $ADDENDUM_DATE . "','yyyy-mm-dd')", false)
                        ->set("MATURITY_DATE","TO_DATE('" . $param['MATURITY_DATE'] . "','yyyy-mm-dd')", false);

            if($param['OPT'] == 1){
                $cekDup = $this->db->get_where('FUNDS_DETAIL_KI',['UUID' => $param['UUID'],'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE']])->row();
                if ($cekDup == NULL) {
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $dt['ISACTIVE'] = 1;
                    $dt['IS_ACC'] = 0;
                    $result1 = $result1->set($dt)->insert('FUNDS_DETAIL_KI');    
                    
                } else {
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $result1 = $result1->set($dt)
                    ->where(['UUID' => $param['UUID'],'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE']])
                    ->update('FUNDS_DETAIL_KI');
                }
            }else{
                $cekDup = "SELECT * FROM FUNDS_DETAIL_KI WHERE UUID = '".$param['UUID']."' ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY ";
                $cekDup = $this->db->query($cekDup)->row();
                if ($cekDup == NULL) {
                    
                    $dt['POS'] = 0;
                    $dt['ISACTIVE'] = 1;
                    $dt['COUNTER'] = 0 ;
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $result1 = $result1->set($dt)->insert('FUNDS_DETAIL_KI'); 
                    
                }
                else if ($cekDup->IS_ACC == "1" /*&& $param['IS_ADDENDUM'] == 1*/) {
                    $this->db->set('CREATED_AT', "SYSDATE", false);
                    $nextCount = intval($cekDup->COUNTER) + 1 ;
                    $dt['COUNTER'] = $nextCount ; 
                    $dt['CREATED_BY']   = $param['USERNAME'];
                    $dt['IS_ADDENDUM']  = "1";
                    $dt['POS'] = 0;
                    $dt['ISACTIVE'] = 1;
                    $dt['REFER_ID']     = $param['UUID'];
                    $result1->set($dt)->insert('FUNDS_DETAIL_KI'); 
                    //
                    
                    $this->db->set('ISACTIVE',0);
                    $this->db->where(['UUID' => $param['UUID'],'ID'=>$cekDup->ID]);
                    $this->db->update('FUNDS_DETAIL_KI'); 
                    $this->db->set('IS_ACC',0);
                    $this->db->where('UUID',$param['UUID']);
                    $this->db->update('FUNDS_MASTER');
                    //
                    
                    $searchTrancheq = "select * from funds_detail_ki_tranche where uuid = '".$param['UUID']."' and counter = (select counter from funds_detail_ki_tranche where uuid = '".$param['UUID']."' order by counter desc fetch first 1 rows only )" ;
                    $searchTranche = $this->db->query($searchTrancheq)->result();
                    // var_dump($searchTranche); exit;
                    foreach ($searchTranche as $tranche) {
                        $dup = [
                            'UUID' => $tranche->UUID,
                            'TRANCHE_NUMBER' => $tranche->TRANCHE_NUMBER,
                            'SUB_CREDIT_TYPE' => $tranche->SUB_CREDIT_TYPE,
                            'EFFECTIVE_DATE' => $tranche->EFFECTIVE_DATE,
                            'LOAN_ACCOUNT_NUMBER' => $tranche->LOAN_ACCOUNT_NUMBER,
                            'LIMIT_TRANCHE' => $tranche->LIMIT_TRANCHE,
                            'AVAIL_PERIOD_FROM' => $tranche->AVAIL_PERIOD_FROM,
                            'AVAIL_PERIOD_TO' => $tranche->AVAIL_PERIOD_TO,
                            'GRACE_PERIOD_FROM' => $tranche->GRACE_PERIOD_FROM,
                            'GRACE_PERIOD_TO' => $tranche->GRACE_PERIOD_TO,
                            'CONTRACT_NUMBER' => $tranche->CONTRACT_NUMBER,
                            'ADD_REMARKS'       => $tranche->ADD_REMARKS,
                            'CURRENCY' => $tranche->CURRENCY,
                            'EXCHANGE_RATE' => $tranche->EXCHANGE_RATE,
                            'CREATED_BY'       => $tranche->CREATED_BY,
                            'IDC' => $tranche->IDC,
                            'PURPOSE' => $tranche->PURPOSE,
                            'ISACTIVE' => $tranche->ISACTIVE
                            ] ;
                            
                            $this->db->set('CREATED_AT', 'SYSDATE', false)
                            ->set('COUNTER', $nextCount)
                            ->set($dup)
                            ->insert('FUNDS_DETAIL_KI_TRANCHE');

                            $this->db->set('ISACTIVE',0)
                            ->set('IS_ACC',0)
                            ->set('IS_ADDENDUM',1)
                            ->where(['UUID' => $param['UUID'],'COUNTER'=>$searchTranche[0]->COUNTER])
                            ->update('FUNDS_DETAIL_KI_TRANCHE'); 
                            // var_dump($this->db->last_query());exit;
                    }
                    
                }
                else {
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $result1 = $result1->set($dt)
                            ->where(['UUID' => $param['UUID'],'ID'=>$cekDup->ID])
                            ->update('FUNDS_DETAIL_KI');
                    // var_dump($this->db->last_query()); exit;
                }
            }
            
            //generate report template
            $checkq = "SELECT FDKIT.UUID, FM.KI_TYPE, FM.PK_NUMBER FROM FUNDS_DETAIL_KI_TRANCHE FDKIT LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FDKIT.UUID WHERE FDKIT.UUID = '".$param['UUID']."'" ;
            $check = $this->db->query($checkq)->row();
            // var_dump($check); exit;
            // if($check != null) {
            //     $type = $check->KI_TYPE;
            //     $PK_NUMBER = $check->PK_NUMBER;
            //     $parRep = [
            //         'PK_NUMBER' => $PK_NUMBER,
            //         'UUID' => $param['UUID']
            //     ];
            //     if($type == 'SINGLE') {
            //         // var_dump($parRep);
            //         // var_dump($check); 
            //         $saverep = $this->SaveReportKI($parRep, $Location);
            //         // var_dump($saverep); exit;
            //     }
            //     else if ($type = 'SYNDICATION') {
            //         $this->SaveReportKI_SYD($parRep, $Location);
            //     }
            // }


            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!',
                    'IDS' => $dt['UUID']
                ];
            } else {
                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
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

    public function SaveKITranche($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit();
        // $DETAIL_KI_ID = [];
        $this->db->trans_begin();
        try {
            $result = FALSE;
            $LIMIT_TRANCHE      = intval(preg_replace("/[^\d\.\-]/","",$param['LIMIT_TRANCHE']));
            $BANK_PORTION       = intval(preg_replace("/[^\d\.\-]/","",$param['BANK_PORTION']));
            $dt = [
                'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'],
                'LOAN_ACCOUNT_NUMBER' => $param['LOAN_ACCOUNT_NUMBER'],
                'INSTALLMENT_PERIOD' => $param['INSTALLMENT_PERIOD'],
                'LIMIT_TRANCHE' => $LIMIT_TRANCHE,
                'BANK_PORTION' => $BANK_PORTION,
                'UUID' => $param['UUID'],
                'ADD_REMARKS'=> $param['ADD_REMARKS'],
                'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE'],
                'CURRENCY' => $param['CURRENCY'],
                'EXCHANGE_RATE' => $param['EXCHANGE_RATE'],
                'IDC' => $param['IDC'],
                'PURPOSE' => $param['PURPOSE']
            ];
            $result1 = $this->db->set('CREATED_AT', "SYSDATE", false)
                        ->set("EFFECTIVE_DATE","TO_DATE('" . $param['EFFECTIVE_DATE'] . "','yyyy-mm-dd')", false)
                        ->set("INTEREST_PERIOD_FROM","TO_DATE('" . $param['INTEREST_PERIOD_FROM'] . "','yyyy-mm-dd')", false)
                        ->set("INTEREST_PERIOD_TO","TO_DATE('" . $param['INTEREST_PERIOD_TO'] . "','yyyy-mm-dd')", false)
                        ->set("AVAIL_PERIOD_FROM","TO_DATE('" . $param['AVAIL_PERIOD_FROM'] . "','yyyy-mm-dd')", false)
                        ->set("AVAIL_PERIOD_TO","TO_DATE('" . $param['AVAIL_PERIOD_TO'] . "','yyyy-mm-dd')", false)
                        ->set("GRACE_PERIOD_FROM","TO_DATE('" . $param['GRACE_PERIOD_FROM'] . "','yyyy-mm-dd')", false)
                        ->set("GRACE_PERIOD_TO","TO_DATE('" . $param['GRACE_PERIOD_TO'] . "','yyyy-mm-dd')", false)
                        ->set("INSTALLMENT_PERIOD_FROM","TO_DATE('" . $param['INSTALLMENT_PERIOD_FROM'] . "','yyyy-mm-dd')", false)
                        ->set("INSTALLMENT_PERIOD_TO","TO_DATE('" . $param['INSTALLMENT_PERIOD_TO'] . "','yyyy-mm-dd')", false);

            
            $cekDup1 = $this->db->get_where('FUNDS_DETAIL_KI_TRANCHE',['UUID' => $param['UUID'],'ID' => $param['ID']])->row();
            //  var_dump($cekDup);exit();
            $searchHeader = $this->db->select('*')->from('FUNDS_DETAIL_KI')->where(array('UUID' => $param['UUID'], 'ISACTIVE' => 1))->get()->row() ;
            // var_dump($searchHeader); exit;
            if ($cekDup1 == NULL) {
                $dt['CREATED_BY'] = $param['USERNAME'];
                $dt['ISACTIVE'] = 1;
                if($searchHeader->IS_ACC == 1) {
                    $dt['COUNTER'] = $searchHeader->COUNTER + 1;
                } 
                else {
                    $dt['COUNTER'] = $searchHeader->COUNTER ? $searchHeader->COUNTER : 0 ;   
                }
                $dt['IS_ACC'] = 0;
                $result1 = $result1->set($dt)->insert('FUNDS_DETAIL_KI_TRANCHE'); 
                $lastinsert =  $this->db->query("SELECT ID FROM FUNDS_DETAIL_KI_TRANCHE ORDER BY ID DESC OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY")->row();  
                $checkCompany = $this->db->query("SELECT C.COMPANY_SUBGROUP,CE.EXTSYSCOMPANYCODE FROM COMPANY C INNER JOIN COMPANY_EXTSYS CE ON CE.COMPANY = C.ID WHERE C.ID = '".$param['COMPANY']."' AND CE.EXTSYSTEM = 'SAPHANA'")->row();

                    #start generate number contract
                    if($checkCompany->COMPANY_SUBGROUP === "UPSTREAM"){
                        $comp = "01".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "DOWNSTREAM"){
                        $comp = "02".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "CEMENT"){
                        $comp = "03".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP === "PROPERTY"){
                        $comp = "04".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    if($checkCompany->COMPANY_SUBGROUP == '' || $checkCompany == null){
                        $comp = "05".$checkCompany->EXTSYSCOMPANYCODE;
                    }
                    
                    $qgenid = $this->db->query("SELECT NVL(COUNTER,0)+1 AS GENID FROM FUNDS_MASTER WHERE COUNTER IS NOT NULL ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY")->row()->GENID;
                    $qgenid =  sprintf('%03d', $qgenid);

                    //
                    if(($param['SUB_CREDIT_TYPE'] == 'FINANCING' && $param['CURRENCY'] == 'IDR') || ($param['SUB_CREDIT_TYPE'] == 'REFINANCING' && $param['CURRENCY'] == 'IDR')){
                        $code = 'IL';
                    }

                    if(($param['SUB_CREDIT_TYPE'] == 'FINANCING' && $param['CURRENCY'] == 'USD') || ($param['SUB_CREDIT_TYPE'] == 'REFINANCING' && $param['CURRENCY'] == 'USD')){
                        $code = 'IF';
                    }

                    if(($param['SUB_CREDIT_TYPE'] == 'FINANCING' && $param['CURRENCY'] == 'US$') || ($param['SUB_CREDIT_TYPE'] == 'REFINANCING' && $param['CURRENCY'] == 'US$')){
                        $code = 'IF';
                    }

                    $getMaster       = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
                    if($getMaster->SUB_CREDIT_TYPE == 'WA'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_WA',['UUID' => $param['UUID']])->row();
                    }
                    if($getMaster->SUB_CREDIT_TYPE == 'RK' || $getMaster->SUB_CREDIT_TYPE == 'TL' || $getMaster->SUB_CREDIT_TYPE == 'BD'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_RK',['UUID' => $param['UUID']])->row();
                    }
                    $codeTranche = "B";
                    if($getMaster->SUB_CREDIT_TYPE == 'KI'){
                        $getDetailMaster = $this->db->get_where('FUNDS_DETAIL_KI',['UUID' => $param['UUID']])->row();
                        if($getMaster->IDC_STATUS == 'WITH_IDC'){
                            $jenisIDC = "1";
                        }else{
                            $jenisIDC = "0";
                        }
                        $codeTranche = "A";
                    }
                    
                    $getBank         = $this->db->get_where('BANK',['FCCODE' => $getMaster->BANK ])->row();
                    $BIC = $getBank->BIC;
                    
                    $genid  = Date('y').$comp.$code.$BIC.$codeTranche.$jenisIDC.$qgenid;

                    $cekDup = $this->db->get_where('FUNDS_DETAIL_KI_TRANCHE',['CONTRACT_NUMBER' => $genid])->row();
                    #tinggal get BIC 
                    if($cekDup == NULL){
                        $genid = $genid;
                    }else{
                        $qgenid = $this->db->query("SELECT NVL(COUNTER,0)+1 AS GENID FROM FUNDS_MASTER WHERE COUNTER IS NOT NULL ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY")->row()->GENID;
                        $qgenid =  sprintf('%03d', $qgenid);
                        $genid  = Date('y').$comp.$code.$BIC.$codeTranche.$jenisIDC.$qgenid;
                    }
                    $this->db->set('CONTRACT_NUMBER',$genid);
                    $this->db->where('UUID',$param['UUID']);
                    $this->db->where('TRANCHE_NUMBER',$param['TRANCHE_NUMBER']);
                    $upd = $this->db->update('FUNDS_DETAIL_KI_TRANCHE');
                    if($upd){
                        $this->db->set('COUNTER',$qgenid);
                        $this->db->where('UUID',$param['UUID']);
                        $this->db->update('FUNDS_MASTER');
                    }

                    if($searchHeader->IS_ACC == "1" /*&& $param['IS_ADDENDUM'] == 1*/){
                        $this->db->set('IS_ACC',0);
                        $this->db->where('UUID',$param['UUID']);
                        $this->db->update('FUNDS_MASTER');

                        $nextCount = $searchHeader->COUNTER + 1;
                            //duplicate all connected entity (header ki and tranches)
                        $searchTranche = $this->db->select('*')->from('FUNDS_DETAIL_KI_TRANCHE')->where(array('UUID' => $param['UUID'], 'IS_ACC' => 1))->get()->result() ;
                        // var_dump($searchTranche); exit;
                        foreach ($searchTranche as $tranche) {
                            // var_dump($cekDup->ID); 
                            // var_dump($tranche->ID); exit;
                                $dup = [
                                    'UUID' => $tranche->UUID,
                                    'TRANCHE_NUMBER' => $tranche->TRANCHE_NUMBER,
                                    'EFFECTIVE_DATE' => $tranche->EFFECTIVE_DATE,
                                    'LOAN_ACCOUNT_NUMBER' => $tranche->LOAN_ACCOUNT_NUMBER,
                                    'LIMIT_TRANCHE' => $tranche->LIMIT_TRANCHE,
                                    'AVAIL_PERIOD_FROM' => $tranche->AVAIL_PERIOD_FROM,
                                    'AVAIL_PERIOD_TO' => $tranche->AVAIL_PERIOD_TO,
                                    'GRACE_PERIOD_FROM' => $tranche->GRACE_PERIOD_FROM,
                                    'GRACE_PERIOD_TO' => $tranche->GRACE_PERIOD_TO,
                                    'CONTRACT_NUMBER' => $tranche->CONTRACT_NUMBER,
                                    'ADD_REMARKS'       => $tranche->ADD_REMARKS,
                                    'CREATED_BY'       => $tranche->CREATED_BY,
                                    'SUB_CREDIT_TYPE'   =>$tranche->SUB_CREDIT_TYPE,
                                    'CURRENCY'          =>$tranche->CURRENCY,
                                    'EXCHANGE_RATE'     =>$tranche->EXCHANGE_RATE,
                                    'IDC' => $tranche->IDC,
                                    'PURPOSE' => $tranche->PURPOSE,
                                    'BANK_PORTION' => $tranche->BANK_PORTION
                                    ] ;
                                    $this->db->set('CREATED_AT', 'SYSDATE', false)
                                    ->set('COUNTER', $nextCount)
                                    ->set('ISACTIVE', 1)
                                    ->set('IS_ACC', 0)
                                    ->set($dup)
                                    ->insert('FUNDS_DETAIL_KI_TRANCHE');
                                    $this->db->set('ISACTIVE',0)
                                    ->set('IS_ACC',0)
                                    ->set('IS_ADDENDUM',1)
                                    ->where(['UUID' => $param['UUID'],'ID'=>$tranche->ID])
                                    ->update('FUNDS_DETAIL_KI_TRANCHE'); 
                        }

                        $dupHeader = [
                            'TOBANK' => $searchHeader->TOBANK,
                            'SUB_CREDIT_TYPE'=> $searchHeader->SUB_CREDIT_TYPE,
                            'FEE' =>  $searchHeader->FEE,
                            'ADM_FEE' => $searchHeader->ADM_FEE,
                            'AGENCY_FEE' => $searchHeader->AGENCY_FEE,
                            'PROVISI' => $searchHeader->PROVISI,
                            'UPFRONT_FEE' => $searchHeader->UPFRONT_FEE,
                            'ANNUAL_FEE' => $searchHeader->ANNUAL_FEE,
                            'COMMIT_FEE' => $searchHeader->COMMIT_FEE,
                            'INTEREST' => $searchHeader->INTEREST,
                            'TENOR' => $searchHeader->TENOR,
                            'CURRENCY' => $searchHeader->CURRENCY,
                            'ADM_FEE_CURRENCY' => $searchHeader->ADM_FEE_CURRENCY,
                            'AG_FEE_CURRENCY' => $searchHeader->AG_FEE_CURRENCY,
                            'LOAN_ACCOUNT_NUMBER' => $searchHeader->LOAN_ACCOUNT_NUMBER,
                            'INSTALLMENT_PERIOD' => $searchHeader->INSTALLMENT_PERIOD,
                            'INTEREST_PAYMENT_SCHEDULE' => $searchHeader->INTEREST_PAYMENT_SCHEDULE,
                            'INTEREST_PAYMENT_SCHEDULE_DATE' => $searchHeader->INTEREST_PAYMENT_SCHEDULE_DATE,
                            'INSTALLMENT_TYPE' => $searchHeader->INSTALLMENT_TYPE,
                            'UUID' => $searchHeader->UUID,
                            'ADD_REMARK' => $searchHeader->ADD_REMARK,
                            'PRE_PAYMENT_PENALTY' => $searchHeader->PRE_PAYMENT_PENALTY,
                            'FIRST_DATE_INTEREST_PAYMENT' => $searchHeader->FIRST_DATE_INTEREST_PAYMENT,
                            'DOCDATE' => $searchHeader->DOCDATE,
                            'MATURITY_DATE' => $searchHeader->MATURITY_DATE,
                            'REFER_ID' => $searchHeader->REFER_ID,
                            'COUNTER' => $nextCount,
                            'POS' => $searchHeader->POS,
                            'ADDENDUM_DATE' => $searchHeader->ADDENDUM_DATE,
                            'PROVISI_TYPE' => $searchHeader->PROVISI_TYPE,
                            'AG_FEE_FAC' => $searchHeader->AG_FEE_FAC,
                            'PAYMENT_BANK_ACC' => $searchHeader->PAYMENT_BANK_ACC
                        ] ;
                        $this->db->set('CREATED_AT', 'SYSDATE', false)
                            ->set('CREATED_BY', $param['USERNAME'])
                            ->set('ISACTIVE', 1)
                            ->set('IS_ADDENDUM', 1)
                            ->set('IS_ACC', 0)
                            ->set($dupHeader)
                            ->insert('FUNDS_DETAIL_KI') ;

                        $this->db->set('ISACTIVE',0)
                                ->set('IS_ACC',0)
                                ->set('IS_ADDENDUM',1)
                                ->where(['UUID' => $param['UUID'],'ID'=>$searchHeader->ID])
                                ->update('FUNDS_DETAIL_KI'); 
                        }
                
            } else {
                if($cekDup1->IS_ACC == '1' /*&& $param['IS_ADDENDUM'] == "1"*/){
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $dt['CONTRACT_NUMBER'] = $cekDup1->CONTRACT_NUMBER;
                    //insert new addendum
                    $nextCount = intval($cekDup1->COUNTER) + 1 ;
                    $dt['COUNTER'] = $nextCount ;
                    $dt['IS_ADDENDUM'] = 1 ;
                    $dt['ISACTIVE'] = 1 ;
                    $result1->set($dt)->insert('FUNDS_DETAIL_KI_TRANCHE');
                    $lastinsert =  $this->db->query("SELECT ID FROM FUNDS_DETAIL_KI_TRANCHE ORDER BY ID DESC OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY")->row();
                    
                    $this->db->set('IS_ACC',0);
                    $this->db->where('UUID',$param['UUID']);
                    $this->db->update('FUNDS_MASTER');
                    //duplicate all connected entity (header ki and tranches)
                    $searchTranche = $this->db->select('*')->from('FUNDS_DETAIL_KI_TRANCHE')->where(array('UUID' => $param['UUID'], 'IS_ACC' => 1))->get()->result() ;
                    // var_dump($searchTranche); exit;
                    foreach ($searchTranche as $tranche) {
                        // var_dump($cekDup->ID); 
                        // var_dump($tranche->ID); exit;
                        if($tranche->ID != $cekDup1->ID){
                            $dup = [
                                'UUID' => $tranche->UUID,
                                'TRANCHE_NUMBER' => $tranche->TRANCHE_NUMBER,
                                'EFFECTIVE_DATE' => $tranche->EFFECTIVE_DATE,
                                'LOAN_ACCOUNT_NUMBER' => $tranche->LOAN_ACCOUNT_NUMBER,
                                'LIMIT_TRANCHE' => $tranche->LIMIT_TRANCHE,
                                'AVAIL_PERIOD_FROM' => $tranche->AVAIL_PERIOD_FROM,
                                'AVAIL_PERIOD_TO' => $tranche->AVAIL_PERIOD_TO,
                                'GRACE_PERIOD_FROM' => $tranche->GRACE_PERIOD_FROM,
                                'GRACE_PERIOD_TO' => $tranche->GRACE_PERIOD_TO,
                                'CONTRACT_NUMBER' => $tranche->CONTRACT_NUMBER,
                                'ADD_REMARKS'       => $tranche->ADD_REMARKS,
                                'CREATED_BY'       => $tranche->CREATED_BY,
                                'SUB_CREDIT_TYPE' => $tranche->SUB_CREDIT_TYPE,
                                'CURRENCY'          => $tranche->CURRENCY,
                                'EXCHANGE_RATE'     => $tranche->EXCHANGE_RATE,
                                'IDC'               => $tranche->IDC,
                                'PURPOSE'           => $tranche->PURPOSE,
                                'BANK_PORTION' => $tranche->BANK_PORTION
                                ] ;
                                
                                $this->db->set('CREATED_AT', 'SYSDATE', false)
                                ->set('COUNTER', $nextCount)
                                ->set('ISACTIVE', 1)
                                ->set('IS_ADDENDUM', 1)
                                ->set($dup)
                                ->insert('FUNDS_DETAIL_KI_TRANCHE');

                                $this->db->set('ISACTIVE',0)
                                ->set('IS_ACC',0)
                                ->set('IS_ADDENDUM',1)
                                ->where(['UUID' => $param['UUID'],'ID'=>$tranche->ID])
                                ->update('FUNDS_DETAIL_KI_TRANCHE'); 
                        }
                        else {
                            $this->db->set('CONTRACT_NUMBER', $cekDup1->CONTRACT_NUMBER)
                            ->where(['UUID' => $param['UUID'], 'ID' => $lastinsert->ID])
                            ->update('FUNDS_DETAIL_KI_TRANCHE');
                            $this->db->set('ISACTIVE',0)
                                ->set('IS_ACC',0)
                                ->set('IS_ADDENDUM',1)
                                ->where(['UUID' => $param['UUID'],'ID'=>$tranche->ID])
                                ->update('FUNDS_DETAIL_KI_TRANCHE'); 
                            // continue ;
                        }
                    }

                    
                    $dupHeader = [
                        'TOBANK' => $searchHeader->TOBANK,
                        'SUB_CREDIT_TYPE'=> $searchHeader->SUB_CREDIT_TYPE,
                        'FEE' =>  $searchHeader->FEE,
                        'ADM_FEE' => $searchHeader->ADM_FEE,
                        'AGENCY_FEE' => $searchHeader->AGENCY_FEE,
                        'PROVISI' => $searchHeader->PROVISI,
                        'UPFRONT_FEE' => $searchHeader->UPFRONT_FEE,
                        'ANNUAL_FEE' => $searchHeader->ANNUAL_FEE,
                        'COMMIT_FEE' => $searchHeader->COMMIT_FEE,
                        'INTEREST' => $searchHeader->INTEREST,
                        'TENOR' => $searchHeader->TENOR,
                        'CURRENCY' => $searchHeader->CURRENCY,
                        'ADM_FEE_CURRENCY' => $searchHeader->ADM_FEE_CURRENCY,
                        'AG_FEE_CURRENCY' => $searchHeader->AG_FEE_CURRENCY,
                        'LOAN_ACCOUNT_NUMBER' => $searchHeader->LOAN_ACCOUNT_NUMBER,
                        'INSTALLMENT_PERIOD' => $searchHeader->INSTALLMENT_PERIOD,
                        'INTEREST_PAYMENT_SCHEDULE' => $searchHeader->INTEREST_PAYMENT_SCHEDULE,
                        'INTEREST_PAYMENT_SCHEDULE_DATE' => $searchHeader->INTEREST_PAYMENT_SCHEDULE_DATE,
                        'INSTALLMENT_TYPE' => $searchHeader->INSTALLMENT_TYPE,
                        'UUID' => $searchHeader->UUID,
                        'ADD_REMARK' => $searchHeader->ADD_REMARK,
                        'PRE_PAYMENT_PENALTY' => $searchHeader->PRE_PAYMENT_PENALTY,
                        'FIRST_DATE_INTEREST_PAYMENT' => $searchHeader->FIRST_DATE_INTEREST_PAYMENT,
                        'DOCDATE' => $searchHeader->DOCDATE,
                        'MATURITY_DATE' => $searchHeader->MATURITY_DATE,
                        'REFER_ID' => $searchHeader->REFER_ID,
                        'COUNTER' => $nextCount,
                        'PROVISI_TYPE' => $searchHeader->PROVISI_TYPE,
                        'AG_FEE_FAC' => $searchHeader->AG_FEE_FAC,
                    ] ;
                    $this->db->set('CREATED_AT', 'SYSDATE', false)
                        ->set('CREATED_BY', $param['USERNAME'])
                        ->set('ISACTIVE', 1)
                        ->set('IS_ADDENDUM', 1)
                        ->set('IS_ACC', 0)
                        ->set($dupHeader)
                        ->insert('FUNDS_DETAIL_KI') ;

                    $this->db->set('ISACTIVE',0)
                            ->set('IS_ACC',0)
                            ->set('IS_ADDENDUM',1)
                            ->where(['UUID' => $param['UUID'],'ID'=>$searchHeader->ID])
                            ->update('FUNDS_DETAIL_KI'); 
                }
                else {
                    // if ($cekDup->IS_ACC == 1) {
                    //     $dt_up = [
                    //         'LOAN_ACCOUNT_NUMBER' => $param['LOAN_ACCOUNT_NUMBER'],
                    //         'CREATED_BY' => $param['USERNAME']
                    //     ];
                    //     $result1 = $result1->set($dt_up)
                    //     ->set('IS_ACC', 0)
                    //     ->set('CREATED_BY', 'SYSDATE', false)
                    //     ->where(['UUID'=>$param['UUID'], 'ID'=> $param['ID']])
                    //     ->update('FUNDS_DETAIL_KI_TRANCHE') ;

                    // }
                    $idtr = $param['ID'] ;
                    $dt['CREATED_BY'] = $param['USERNAME'];
                    $result1 = $result1->set($dt)
                            ->where(['UUID' => $param['UUID'],'ID' => $param['ID']])
                            ->update('FUNDS_DETAIL_KI_TRANCHE');
                }
            }
            //update limit balance 
            $result2 = $this->updateLimitBalance([
                                'LIMIT_MODAL'   => $cekDup1->LIMIT_TRANCHE,
                                'LIMIT_TRANCHE' => $dt['LIMIT_TRANCHE'],
                                'RATE'          => $dt['EXCHANGE_RATE'],
                                'T_NUMBER'      => $dt['TRANCHE_NUMBER'],
                                'CURRENCY'      => $dt['CURRENCY'],
                                'UUID'          => $dt['UUID'],
                                'ID'            => $lastinsert->ID
            ]);
            if ($result1 && ($result2['STATUS'] == TRUE)) {
                $result = TRUE;
                // var_dump($result); exit;
            }
            // var_dump($result2); exit;
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!',
                    'MESSAGE2'=> $result2['MESSAGE'],
                    'IDS' => $dt['UUID'],
                    'IDT' => $lastinsert->ID ? $lastinsert->ID : $idtr, 
                    'TNUM' => $dt['TRANCHE_NUMBER'],
                    'LIMIT_TRANCHE' => $dt['LIMIT_TRANCHE'],
                    'CURR' => $dt['CURRENCY']
                ];
            } else {
                $this->db->trans_rollback(); 
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Save Failed !!',
                    'MESSAGE2'=> $result2['MESSAGE']
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

    public function ShowFileData($param) {

        if($param['SUB_CREDIT_TYPE'] == "TL"){
            $result = $this->db->select("*")
                        ->from("FUNDS_WD_FILE")
                        ->where('UUID',$param['UUID'])
                        ->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE'])
                        ->order_by('ID DESC')->get()->result();
        }
        if($param['SUB_CREDIT_TYPE'] == "WA"){
            $result = $this->db->select("*")
                        ->from("FUNDS_WD_FILE")
                        ->where('UUID',$param['UUID'])
                        ->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE'])
                        ->order_by('ID DESC')->get()->result();
        }
        if($param['SUB_CREDIT_TYPE'] == "BD"){
            $result = $this->db->select("*")
                        ->from("FUNDS_WD_FILE")
                        ->where('UUID',$param['UUID'])
                        ->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE'])
                        ->order_by('ID DESC')->get()->result();
        }
        if($param['SUB_CREDIT_TYPE'] == "KI"){
            $result = $this->db->select("*")
                        ->from("FUNDS_WD_FILE")
                        ->where('UUID',$param['UUID'])
                        ->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE'])
                        ->order_by('ID DESC')->get()->result();
        }
        if($param['SUB_CREDIT_TYPE'] == "KMK_SCF_AR"){
            $result = $this->db->select("*")
                        ->from("FUNDS_WD_FILE")
                        ->where('UUID',$param['UUID'])
                        ->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE'])
                        ->order_by('ID DESC')->get()->result();
        }
        if($param['SUB_CREDIT_TYPE'] == "KMK_SCF_AP"){
            $result = $this->db->select("*")
                        ->from("FUNDS_WD_FILE")
                        ->where('UUID',$param['UUID'])
                        ->where('SUB_CREDIT_TYPE',$param['SUB_CREDIT_TYPE'])
                        ->order_by('ID DESC')->get()->result();
        }
        else if($param['SUB_CREDIT_TYPE'] == NULL || $param['SUB_CREDIT_TYPE'] == '' ){
            $result = $this->db->select("*")
                        ->from("FUNDS_FILE")
                        ->where('UUID',$param['UUID'])
                        ->order_by('ID DESC')->get()->result();
        }
        
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function uploadFile($param,$location) {

        try
        {   
            $USERNAME     = $param['USERNAME'];
            $UUID         = $param['UUID'];
            $TIPE         = $param['TIPE'];
            $this->db->trans_begin();
            
            $result = FALSE;
            $config['upload_path']          = ROOT;
            $config['allowed_types']        = 'pdf|docx|doc|xls|xlsx';
            $config['overwrite']            = TRUE;
            $config['max_size']             = 20480;


            $this->load->library('upload');
            $this->upload->initialize($config);
            $result = $this->upload->do_upload('userfile');
            if (!$this->upload->do_upload('userfile')){
                throw new Exception($this->upload->display_errors());
                
            }elseif($this->upload->do_upload()){
                $media = $this->upload->data();
                // var_dump($media) ; exit;
                $hp = array(
                    'UUID' => $UUID,
                    'FILENAME' => $media['file_name'],
                    'FCENTRY'   => $USERNAME,
                    'TIPE' => $TIPE,
                );
                
                $result = $this->db->set("LASTUPDATE", "SYSDATE", false)->set($hp)->insert('FUNDS_FILE');
                $qGet = "SELECT * FROM FUNDS_FILE ";
                $res   = $this->db->query($qGet)->result();          
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $res
                ];
            }
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
          $this->db->close();
          return $return;
    }

    public function multiUploadFile($param, $location) {
        try
        {   
            $this->db->trans_begin();
            $USERNAME     = $param['USERNAME'];
            $UUID         = $param['UUID'];
            $TIPE         = $param['TIPE'];
            $ADDENDUM_NUM = $param['ADDENDUM_NUM'];
            $result = FALSE;
            $file_length = count($_FILES['userfile']['name']);
            // var_dump($_FILES); exit;
            for ($index = 0 ; $index < $file_length ; $index++ ) {
                $_FILES['file']['name'] = $_FILES['userfile']['name'][$index];
                $_FILES['file']['type'] = $_FILES['userfile']['type'][$index];
                $_FILES['file']['tmp_name'] = $_FILES['userfile']['tmp_name'][$index];
                $_FILES['file']['error'] = $_FILES['userfile']['error'][$index];
                $_FILES['file']['size'] = $_FILES['userfile']['size'][$index];



                $config['upload_path']          = ROOT;
                $config['allowed_types']        = 'pdf|docx|doc|xls|xlsx';
                $config['overwrite']            = TRUE;
                $config['max_size']             = 20480;
                $config['file_name']            = $_FILES['userfile']['name'][$index];


                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                $result = $this->upload->do_upload('file');
                if($result){
                    $media = $this->upload->data();
                    // var_dump($media) ; exit;
                    $hp = array(
                        'UUID' => $UUID,
                        'FILENAME' => $media['file_name'],
                        'FCENTRY'   => $USERNAME,
                        'TIPE' => $TIPE,
                    );
                    
                    $result = $this->db->set("LASTUPDATE", "SYSDATE", false)->set($hp)->insert('FUNDS_FILE');
                }
                else {
                    throw new Exception($this->upload->display_errors());
                }
            }
            $qGet = "SELECT * FROM FUNDS_FILE ";
            $res   = $this->db->query($qGet)->result();          
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $res
                ];
            }
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
          $this->db->close();
          return $return;
    }

    public function uploadWDFile($param,$location) {

        try
        {   
            $USERNAME     = $param['USERNAME'];
            $UUID         = $param['UUID'];
            $TIPE         = $param['TIPE'];
            // $this->db->where('FCENTRY',$USERNAME);
            // $this->db->delete('TEMP_UPLOAD_FORECAST');    
            // $this->db->close();
            $this->db->trans_begin();
            
            $result = FALSE;
            $config['upload_path']          = ROOT;
            $config['allowed_types']        = 'pdf|docx|doc|xls|xlsx';
            $config['overwrite']            = TRUE;
            $config['max_size']             = 5421;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $check = $this->upload->do_upload('userfile');
            $media = $this->upload->data();
            // var_dump($config);exit;
            if (!$check){
                throw new Exception($this->upload->display_errors());
                
            } else {
                $hp = array(
                    'UUID' => $UUID,
                    'FILENAME' => $media['file_name'],
                    'FCENTRY'   => $USERNAME,
                    'WD_TIPE' => $TIPE,
                    'SUB_CREDIT_TYPE' => $param['SUB_CREDIT_TYPE']
                );
                
                $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set($hp)->insert('FUNDS_WD_FILE');
                $qGet = "SELECT * FROM FUNDS_WD_FILE ";
                $res   = $this->db->query($qGet)->result();          
                if ($result) {
                    $this->db->trans_commit();
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => $res
                    ];
                }
            }

            
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
          $this->db->close();
          return $return;
    }

    public function DtBankCompany($param) {
        $SQL = "SELECT B.FCCODE, B.FCNAME, B.BANKACCOUNT, B.CURRENCY, B.ISDEFAULT
                  FROM $this->BANK B    
                 WHERE B.COMPANY = ?
                   AND B.ACTIVATION = 'Y'
                 ORDER BY B.ISDEFAULT DESC, B.COMPANYGROUP, B.COMPANY, B.FCNAME";
        // $SQL = "SELECT FCNAME, MAX(FCCODE) AS FCCODE, MAX(CURRENCY) AS CURRENCY, MAX(ISDEFAULT) AS ISDEFAULT
        // FROM (  SELECT B.FCCODE,
        //                RTRIM (B.FCNAME) AS FCNAME,
        //                B.BANKACCOUNT,
        //                B.CURRENCY,
        //                B.ISDEFAULT
        //           FROM BANK B
        //          WHERE B.ACTIVATION = 'Y' 
        //       ORDER BY FCNAME ASC) GROUP BY FCNAME ORDER BY FCNAME ASC ";         
        $result = $this->db->query($SQL, $param['COMPANY'])->result();
        $this->db->close();
        return $result;
    }

    public function DtBankCompanyKI($param) {
        // $SQL = "SELECT FCNAME, MAX(FCCODE) AS FCCODE, MAX(CURRENCY) AS CURRENCY, MAX(ISDEFAULT) AS ISDEFAULT
        // FROM (  SELECT B.FCCODE,
        //                RTRIM (B.FCNAME) AS FCNAME,
        //                B.BANKACCOUNT,
        //                B.CURRENCY,
        //                B.ISDEFAULT
        //           FROM BANK B
        //          WHERE B.ACTIVATION = 'Y' 
        //       ORDER BY FCNAME ASC) GROUP BY FCNAME ORDER BY FCNAME ASC ";
        $SQL = "SELECT FCNAME, ID FROM SUPPLIER WHERE SUPPLIER_TYPE = 'BANK' ORDER BY FCNAME ASC" ;
        $result = $this->db->query($SQL, $param['COMPANY'])->result();
        $this->db->close();
        return $result;
    }

    function GetDataWA($param){
        // $get_where = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
        // if($get_where->SUB_CREDIT_TYPE == "BD"){
            $SQL = "SELECT L.*,
                            L.UUID AS LID,
                            FM.PK_NUMBER AS REF_CONTRACT,
                            BU.ID AS BUID,
                            BU.FCNAME AS BUFCNAME,
                            B.FCNAME AS GETBANK,
                            L.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPEMASTER,
                            FR.SUB_CREDIT_TYPE AS SCT,
                            FR.*,
                            FR.ID AS ID_DETAIL
                    FROM FUNDS_MASTER L
                            LEFT JOIN (SELECT *
                                        FROM FUNDS_DETAIL_WA
                                        WHERE POS = '0') FR
                            ON FR.UUID = L.UUID
                            LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT
                            LEFT JOIN SUPPLIER B ON B.ID = L.BANK
                            LEFT JOIN (SELECT UUID, PK_NUMBER FROM FUNDS_MASTER) FM
                            ON FM.UUID = L.CONTRACT_REF
                    WHERE L.UUID = ? --AND FR.POS = '0' 
            ORDER BY FR.POS ASC";
            $result = $this->db->query($SQL, $param["UUID"])->row();    
        // }
        
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    function GetDataKI($param){
        // $get_where = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
        // if($get_where->SUB_CREDIT_TYPE == "BD"){
            // $SQL = "SELECT L.*,  L.UUID as LID,  BU.ID AS BUID, BU.FCNAME AS BUFCNAME,L.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPEMASTER,FR.SUB_CREDIT_TYPE as SCT, TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE_DETAIL,
            // B.BANKACCOUNT || ' - ' || B.FCNAME || ' - ' || B.CURRENCY AS GETBANK, L.KI_TYPE AS CTYPE,
            //        FR.TOBANK,
            //        FR.FEE,
            //        FR.PROVISI,
            //        FR.ANNUAL_FEE,
            //        FR.UPFRONT_FEE,
            //        FR.COMMIT_FEE,
            //        NVL(FR.ADM_FEE,0) AS ADM_FEE,
            //        NVL(FR.AGENCY_FEE,0) AS AGENCY_FEE,
            //        FR.RATE,
            //        FR.CURRENCY,
            //        FR.ADM_FEE_CURRENCY,
            //        FR.AG_FEE_CURRENCY,
            //        FR.TENOR,
            //        TO_CHAR (FR.MATURITY_DATE, 'yyyy-mm-dd') AS MATURITY_DATE,
            //        FR.LOAN_ACCOUNT_NUMBER,
            //        FR.INTEREST_PAYMENT_SCHEDULE,
            //        FR.INTEREST_PAYMENT_SCHEDULE_DATE,
            //        TO_CHAR (FR.FIRST_DATE_INTEREST_PAYMENT,'yyyy-mm-dd') AS FIRST_DATE_INTEREST_PAYMENT, 
            //        FR.INSTALLMENT_PERIOD,
            //        FR.INSTALLMENT_TYPE,
            //        FR.ADD_REMARK FROM FUNDS_MASTER L LEFT JOIN FUNDS_DETAIL_KI FR ON FR.UUID = L.UUID LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT LEFT JOIN BANK B ON B.FCCODE = L.BANK WHERE L.UUID = ?";
            
            // $SQL = "SELECT L.*,  L.UUID as LID,  BU.ID AS BUID, BU.FCNAME AS BUFCNAME,L.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPEMASTER,FR.SUB_CREDIT_TYPE as SCT, TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE_DETAIL,
            // B.BANKACCOUNT || ' - ' || B.FCNAME || ' - ' || B.CURRENCY AS GETBANK, L.KI_TYPE AS CTYPE,
            //        FR.TOBANK,
            //        FR.IS_ACC,
            //        FR.FEE,
            //        FR.PROVISI,
            //        FR.ANNUAL_FEE,
            //        FR.UPFRONT_FEE,
            //        FR.COMMIT_FEE,
            //        NVL(FR.ADM_FEE,0) AS ADM_FEE,
            //        NVL(FR.AGENCY_FEE,0) AS AGENCY_FEE,
            //        FR.INTEREST,
            //        FR.CURRENCY,
            //        FR.ADM_FEE_CURRENCY,
            //        FR.AG_FEE_CURRENCY,
            //        FR.TENOR,
            //        TO_CHAR (FR.MATURITY_DATE, 'yyyy-mm-dd') AS MATURITY_DATE,
            //        FR.LOAN_ACCOUNT_NUMBER,
            //        FR.INTEREST_PAYMENT_SCHEDULE,
            //        FR.INTEREST_PAYMENT_SCHEDULE_DATE,
            //        TO_CHAR (FR.FIRST_DATE_INTEREST_PAYMENT,'yyyy-mm-dd') AS FIRST_DATE_INTEREST_PAYMENT, 
            //        FR.INSTALLMENT_PERIOD,
            //        FR.INSTALLMENT_TYPE,
            //        FR.PRE_PAYMENT_PENALTY,
            //        FR.ADD_REMARK FROM FUNDS_MASTER L LEFT JOIN FUNDS_DETAIL_KI FR ON FR.UUID = L.UUID LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT LEFT JOIN BANK B ON B.FCCODE = L.BANK WHERE L.UUID = ?";

            $SQL = "SELECT L.*,  L.UUID as LID,  BU.ID AS BUID, BU.FCNAME AS BUFCNAME,L.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPEMASTER,FR.SUB_CREDIT_TYPE as SCT, TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE_DETAIL,
            B.FCNAME AS GETBANK, L.KI_TYPE AS CTYPE,
                FR.PROVISI_TYPE,
                FM.PK_NUMBER AS REF_CONTRACT,
                FR.TOBANK,
                L.IS_ACC,
                FR.COUNTER AS LAT_ADD,
                FR.FEE,
                FR.PROVISI,
                FR.ANNUAL_FEE,
                FR.UPFRONT_FEE,
                FR.COMMIT_FEE,
                NVL(FR.ADM_FEE,0) AS ADM_FEE,
                NVL(FR.AGENCY_FEE,0) AS AGENCY_FEE,
                FR.INTEREST,
                FR.COUNTER,
                FR.CURRENCY,
                FR.ADM_FEE_CURRENCY,
                FR.AG_FEE_CURRENCY,
                FR.AG_FEE_FAC,
                FR.TENOR,
                TO_CHAR (FR.MATURITY_DATE, 'yyyy-mm-dd') AS MATURITY_DATE,
                FR.LOAN_ACCOUNT_NUMBER,
                FR.INTEREST_PAYMENT_SCHEDULE,
                FR.INTEREST_PAYMENT_SCHEDULE_DATE,
                TO_CHAR (FR.FIRST_DATE_INTEREST_PAYMENT,'yyyy-mm-dd') AS FIRST_DATE_INTEREST_PAYMENT, 
                FR.INSTALLMENT_PERIOD,
                FR.INSTALLMENT_TYPE,
                FR.PRE_PAYMENT_PENALTY,
                FR.IS_ADDENDUM,
                FR.ADD_REMARK,
                FR.PAYMENT_BANK_ACC,
                TO_CHAR(FR.ADDENDUM_DATE, 'yyyy-mm-dd') AS ADDENDUM_DATE
                FROM FUNDS_MASTER L LEFT JOIN FUNDS_DETAIL_KI FR ON FR.UUID = L.UUID LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT LEFT JOIN SUPPLIER B ON B.ID = L.BANK LEFT JOIN (SELECT UUID, PK_NUMBER FROM FUNDS_MASTER) FM ON FM.UUID = L.CONTRACT_REF WHERE L.UUID = ? AND (L.ISACTIVE = 1) 
                ORDER BY LAT_ADD DESC NULLS LAST";
            $result = $this->db->query($SQL, $param["UUID"])->row(); 
            // var_dump($this->db->last_query());exit();
            // var_dump($result); exit;   
        // }
        
        $this->db->close();
        return $result;
    }

    function HeaderDetailKI($param){
        $SQL = "SELECT DISTINCT FR.ID,
                    FR.CONTRACT_NUMBER,
                    FR.TRANCHE_NUMBER,
                    TO_CHAR (FR.EFFECTIVE_DATE,'yyyy-mm-dd') EFFECTIVE_DATE,
                    FR.LOAN_ACCOUNT_NUMBER,
                    FR.INTEREST_PERIOD_FROM,
                    FR.INTEREST_PERIOD_TO,
                    FR.INSTALLMENT_PERIOD,
                    FR.LIMIT_TRANCHE,
                    FR.ADD_REMARKS,
                    FR.CURRENCY,
                    FR.EXCHANGE_RATE,
                    FR.AVAIL_PERIOD_FROM,
                    FR.AVAIL_PERIOD_TO,
                    FR.GRACE_PERIOD_FROM,
                    FR.GRACE_PERIOD_TO,
                    FR.INSTALLMENT_PERIOD_FROM,
                    FR.INSTALLMENT_PERIOD_TO,
                    FR.SUB_CREDIT_TYPE,
                    FR.IDC,
                    FR.PURPOSE,
                    FR.ISACTIVE,
                    TO_CHAR (FR.AVAIL_PERIOD_FROM,'yyyy-mm-dd') || ' to ' || TO_CHAR (FR.AVAIL_PERIOD_TO,'yyyy-mm-dd') AVAIL_PERIOD,
                    TO_CHAR (FR.GRACE_PERIOD_FROM,'yyyy-mm-dd') || ' to ' || TO_CHAR (FR.GRACE_PERIOD_TO,'yyyy-mm-dd') GRACE_PERIOD,
                    TO_CHAR (FR.INSTALLMENT_PERIOD_FROM,'yyyy-mm-dd') || ' to ' || TO_CHAR (FR.INSTALLMENT_PERIOD_TO,'yyyy-mm-dd') INSTALLMENT_PERIOD_DATE,
                    TO_CHAR (FR.INTEREST_PERIOD_FROM,'yyyy-mm-dd') || ' to ' || TO_CHAR (FR.INTEREST_PERIOD_TO,'yyyy-mm-dd') INTEREST_PERIOD,
                    FR.BANK_PORTION  FROM FUNDS_MASTER L 
                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FR ON FR.UUID = L.UUID  
                        LEFT JOIN (SELECT DISTINCT UUID, MAX(COUNTER) AS MAX_COUNTER FROM FUNDS_DETAIL_KI_TRANCHE GROUP BY UUID) mx_ctr ON mx_ctr.UUID = L.UUID
                        LEFT JOIN FUNDS_DETAIL_KI FDK ON FDK.UUID = L.UUID 
                            WHERE L.UUID = ? AND FR.COUNTER = mx_ctr.MAX_COUNTER AND FR.IS_COMPLETE IS NULL ORDER BY FR.ID DESC";
        $result = $this->db->query($SQL, $param["UUID"])->result();   
        $this->db->close();
        // var_dump($this->db->last_query());exit;
        return $result;
    }


    function WDDetailKI($param){
        $SQL = "SELECT FR.*, 
                CASE
                    WHEN S.ID IS NOT NULL THEN S.ID
                    WHEN C.ID IS NOT NULL THEN C.ID
                    ELSE S.ID
                END
                AS SUPPID,
                CASE
                    WHEN S.FCNAME IS NOT NULL THEN S.FCNAME
                    WHEN C.COMPANYNAME IS NOT NULL THEN C.COMPANYNAME
                    ELSE S.FCNAME
                END
                AS VENDORNAME
                 FROM FUNDS_WD_KI_DETAIL FR LEFT JOIN SUPPLIER S ON S.ID = FR.VENDOR LEFT JOIN COMPANY C ON C.ID = FR.VENDOR WHERE FR.UUID = ? AND FR.IDHEADER = ?  ORDER BY FR.ID ASC";
        $result = $this->db->query($SQL, array($param["UUID"], $param['IDHEADER']))->result();    
        $this->db->close();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    function HeaderDetailWA($param){
        $SQL = "SELECT FR.ID,
                 FR.SUB_CREDIT_TYPE AS SCT,
                 FR.AMOUNT_LIMIT,
                 TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                 TO_CHAR (FR.MATURITY_DATE, 'yyyy-mm-dd') AS MATURITY_DATE,
                 FR.LOAN_ACCOUNT_NUMBER,
                 FR.FEE,
                 FR.TENOR,
                 FR.CURRENCY,
                 FR.INTEREST,
                 FR.POS FROM FUNDS_MASTER L LEFT JOIN FUNDS_DETAIL_WA FR ON FR.UUID = L.UUID  WHERE L.UUID = ? AND (FR.SUB_CREDIT_TYPE = 'KMK_SCF_AP' OR FR.SUB_CREDIT_TYPE = 'KMK_SCF_AR') ORDER BY FR.ID ASC";
        $result = $this->db->query($SQL, $param["UUID"])->result();    
        $this->db->close();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    function GetDataRK($param){
        // $get_where = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
        // if($get_where->SUB_CREDIT_TYPE == "BD"){
            $SQL = "  SELECT L.*,  L.UUID as LID,  L.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPEMASTER, C_REF.PK_NUMBER AS REF_CONTRACT,
                         BU.ID AS BUID,
                         BU.FCNAME AS BUFCNAME,
                            B.FCNAME AS GETBANK,
                         B.FCCODE AS BANKCODE,
                         NVL(FR.AMOUNT_LIMIT,0) AS AMOUNT_LIMIT,
                           FR.PROVISI,
                           TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE_DETAIL,
                           FR.FEE,
                           FR.IS_ACC,
                           FR.INTEREST,
                           FR.IS_ADDENDUM,
                           FR.UPFRONT_FEE,
                           FR.ANNUAL_FEE,
                           FR.ADMIN_FEE,
                           FR.ADM_FEE_CURRENCY,
                           FR.RATE,
                           FR.CURRENCY,
                           FR.TENOR,
                           FR.ADD_REMARK,
                           FR.PRE_PAYMENT_PENALTY,
                           FR.CONTRACT_NUMBER,
                           FR.INTEREST_PAYMENT_SCHEDULE_DATE,
                           FR.INTEREST_PAYMENT_SCHEDULE,
                           TO_CHAR (FR.FIRST_DATE_INTEREST_PAYMENT, 'yyyy-mm-dd') AS FIRST_DATE_INTEREST_PAYMENT,
                           TO_CHAR (FR.MATURITY_DATE, 'yyyy-mm-dd') AS MATURITY_DATE,
                           TO_CHAR (FR.ADDENDUM_DATE, 'yyyy-mm-dd') AS ADDENDUM_DATE,
                           FR.LOAN_ACCOUNT_NUMBER,
                           FR.PAYMENT_BANK_ACC,
                            FR.INTEREST_PERIOD_FROM,
                             FR.INTEREST_PERIOD_TO,
                             FR.INTEREST_PERIOD_FROM || ' - ' || FR.INTEREST_PERIOD_TO as INTEREST_PERIOD,
                             FR.INSTALLMENT_PERIOD
                    FROM FUNDS_MASTER L
                         LEFT JOIN (SELECT * FROM FUNDS_DETAIL_RK WHERE UUID = ? ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY  ) FR ON FR.UUID = L.UUID
                         LEFT JOIN (SELECT UUID,PK_NUMBER FROM FUNDS_MASTER ) C_REF ON C_REF.UUID = L.CONTRACT_REF
                         LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT 
                         LEFT JOIN SUPPLIER B ON B.ID = L.BANK
                         WHERE L.UUID = ?";
            $result = $this->db->query($SQL, [$param["UUID"],$param["UUID"]])->row();    
        // }
        
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    function HeaderDetailRK($param){
        $SQL = "SELECT FR.ID,
                 FR.SUB_CREDIT_TYPE AS SCT,
                 FR.AMOUNT_LIMIT,
                 TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                 TO_CHAR (FR.MATURITY_DATE, 'yyyy-mm-dd') AS MATURITY_DATE,
                 FR.LOAN_ACCOUNT_NUMBER,
                 FR.TENOR,
                 FR.FEE,
                 FR.UPFRONT_FEE,
                 FR.ANNUAL_FEE,
                 FR.RATE,
                 FR.CURRENCY,
                 FR.INTEREST,
                 FR.PROVISI,
                 TO_CHAR (FR.INTEREST_PERIOD_FROM, 'yyyy-mm-dd') AS INTEREST_PERIOD_FROM,
                 TO_CHAR (FR.INTEREST_PERIOD_TO, 'yyyy-mm-dd') AS INTEREST_PERIOD_TO,
                 TO_CHAR (FR.INTEREST_PERIOD_FROM, 'yyyy-mm-dd')  || ' to ' || TO_CHAR (FR.INTEREST_PERIOD_TO, 'yyyy-mm-dd') as INTEREST_PERIOD,
                 FR.INSTALLMENT_PERIOD,
                 FR.POS FROM FUNDS_MASTER L LEFT JOIN FUNDS_DETAIL_RK FR ON FR.UUID = L.UUID  WHERE L.UUID = ? AND (FR.SUB_CREDIT_TYPE = 'KMK_SCF_AP' OR FR.SUB_CREDIT_TYPE = 'KMK_SCF_AR') ORDER BY FR.ID ASC";
        $result = $this->db->query($SQL, $param["UUID"])->result();    
        $this->db->close();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    public function SaveWD($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit();
        // ini_set('display_errors','On');
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $WD_TYPE        = $param['WD_TYPE'];
            
            $SUB_WD_TYPE    = isset($param['SUB_WD_TYPE']) ? $param['SUB_WD_TYPE'] : null;
            $AMOUNT         = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT']));
            $AMOUNT_LIMIT   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_LIMIT']));
            $RATE           = isset($param['RATE']) ? intval(preg_replace("/[^\d\.\-]/","",$param['RATE'])) : null;
            $lastconq        = "SELECT COUNTER FROM FUNDS_DETAIL_RK WHERE UUID = '".$param['UUID']."' ORDER BY ID DESC";
            $lastcon        = $this->db->query($lastconq)->row();

            $getStatus = "SELECT IS_ACC FROM FUNDS_MASTER WHERE UUID = '".$param['UUID']."'";
            $getStatus = $this->db->query($getStatus)->row();
            if ($getStatus->IS_ACC == '0' || $getStatus->IS_ACC == '2') {
                $msg = "Kontrak Belum DiApprove, Silakan Approve Terlebih Dahulu";
                throw new Exception($msg);
            }
            $q   = "SELECT * FROM FUNDS_WITHDRAW WHERE UUID = '".$param['UUID']."' AND WD_TYPE = '".$WD_TYPE."' AND (SUB_WD_TYPE IS NULL) ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY";
            $getBalance = $this->db->query($q)->row();
            // var_dump($this->db->last_query());exit;
            // var_dump($getBalance);exit;
            if($getBalance != null ){
                if ($getBalance->STATUS == '0' || $getBalance->STATUS == '2') {
                    $msg = "Data Sebelumnya Belum DiApprove, Silakan Approve Terlebih Dahulu";
                    throw new Exception($msg);
                    
                }
                if($getBalance->AMOUNT_BALANCE != null){
                    if($AMOUNT > $getBalance->AMOUNT_BALANCE){
                        $msg = "Amount Exceed";
                        throw new Exception($msg);
                    }else{
                        $AMOUNT_LIMIT = $getBalance->AMOUNT_BALANCE;    
                    }
                }
            }

            // STATUS 0 BELUM DI ACC
            $dt = [
                'AMOUNT' => $AMOUNT,
                // 'AMOUNT_BALANCE' => $AMOUNT_LIMIT - $AMOUNT,
                'WD_TYPE' => $param['WD_TYPE'],
                'STATUS' => 0,
                'UUID'   => $param['UUID'],
                'CONTRACT_NUMBER'   => $param['CONTRACT_NUMBER'],
                'CON_COUNTER'   => $lastcon->COUNTER
            ];
            if($WD_TYPE == 'WA'){
                $dt['RATE'] = $RATE;
                $dt['AMOUNT_CONVERT'] = $RATE * $AMOUNT;
            }

            if($WD_TYPE == 'TL' || $WD_TYPE == 'WA'){
                    $result1 = $this->db->set('CREATED_AT', "SYSDATE", false)
                         ->set("DUEDATE","TO_DATE('" . $param['DUEDATE'] . "','yyyy-mm-dd')", false)
                         ->set("VALUE_DATE","TO_DATE('" . $param['VALUE_DATE'] . "','yyyy-mm-dd')", false);
                    // $result1 = $this->db->set('CREATED_AT', "SYSDATE", false);
                    // $dt['CREATED_BY'] = $param['USERNAME'];
                    // $result1 = $result1->set($dt)->insert('FUNDS_WITHDRAW');
                }else{
                    $result1 = $this->db->set('CREATED_AT', "SYSDATE", false);    
                }
            // echo "<pre>";
            // var_dump($dt);exit;
            
            // $cekDup = $this->db->get_where('FUNDS_WITHDRAW',['UUID' => $param['UUID']])->row();
            // if ($cekDup == NULL) {
                $dt['UUID'] = $param['UUID'];
                $dt['CREATED_BY'] = $param['USERNAME'];
                $result1 = $result1->set($dt)->insert('FUNDS_WITHDRAW');    
                
            // } else {
            //     $dt['CREATED_BY'] = $param['USERNAME'];
            //     $result1 = $result1->set($dt)
            //             ->where(['UUID' => $param['UUID']])
            //             ->update('FUNDS_WITHDRAW');
            // }

            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $msg = "Data has been Successfully Saved !!";
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $msg,
                    'IDS' => $dt['UUID'],
                    'BATCHID'=> isset($dt['BATCHID']) ? $dt['BATCHID'] : ''
                    // 'IDS' => $dt['UUID']
                ];
            } else {
                $this->db->trans_rollback(); 
                throw new Exception($msg);
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

    public function SaveWDKI($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $WD_TYPE        = $param['WD_TYPE'];
            $DRAWDOWN_VALUE = intval(preg_replace("/[^\d\.\-]/","",$param['DRAWDOWN_VALUE']));
            // var_dump($DRAWDOWN_VALUE); exit; 
            $AMOUNT_LIMIT   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_LIMIT']));
            $IDRUSD         = intval(preg_replace("/[^\d\.\-]/","",$param['RATE_IDRUSD']));
            $CNYUSD         = intval(preg_replace("/[^\d\.\-]/","",$param['RATE_CNYUSD']));
            $SGDUSD         = intval(preg_replace("/[^\d\.\-]/","",$param['RATE_SGDUSD']));
            $TRANCHE_NUMBER = $param['TRANCHE_NUMBER'];

            $q   = "SELECT * FROM FUNDS_WD_KI WHERE UUID = '".$param['UUID']."' ORDER BY CTRWD DESC FETCH FIRST 1 ROWS ONLY";
            $checkLatestData = $this->db->query($q)->row();

            // $q1  = "SELECT * FROM FUNDS_WD_KI_TRANCHE WHERE TRANCHE_NUMBER = '".$TRANCHE_NUMBER."' AND UUID = '".$param['UUID']."' "; 
            // $getBalance = $this
            // // var_dump($this->db->last_query());exit;
            // // var_dump($getBalance);exit;
            if($checkLatestData == null) {
                $CTRWD = 0 ;
            }
            else {
                if ($checkLatestData->STATUS == '0' || $checkLatestData->STATUS == '2') {
                    $msg = "Data Sebelumnya Belum DiApprove, Silakan Approve Terlebih Dahulu";
                    throw new Exception($msg);
                    
                }
                else {
                    $CTRWD = intval($checkLatestData->CTRWD) + 1 ;
                }
            }
            // if($getBalance->BALANCE != null){
            //     if($DRAWDOWN_VALUE > $getBalance->BALANCE){
            //         $msg = "Amount Exceed";
            //         throw new Exception($msg);
            //     }else{
            //         $AMOUNT_LIMIT = $getBalance->BALANCE;    
            //     }
            // }  

            $dt = [
                'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'],
                'DRAWDOWN' => $param['DRAWDOWN_TYPE'],
                'DRAWDOWN_VALUE' => $DRAWDOWN_VALUE,
                'RATE_IDRUSD' => $IDRUSD,
                'RATE_CNYUSD' => $CNYUSD,
                'RATE_SGDUSD' => $SGDUSD,
                // 'BALANCE' => $AMOUNT_LIMIT - $DRAWDOWN_VALUE,
                'STATUS' => 0,
                'CTRWD' => $CTRWD
            ];

            $this->db->set("VALUE_DATE","TO_DATE('" . $param['VALUE_DATE'] . "','yyyy-mm-dd')", false);            
            $result1 = $this->db->set('CREATED_AT', "SYSDATE", false);

            // $cekDup = $this->db->get_where('FUNDS_WITHDRAW',['UUID' => $param['UUID']])->row();
            // if ($cekDup == NULL) {
                $dt['UUID'] = $param['UUID'];
                $dt['CREATED_BY'] = $param['USERNAME'];
                $result1 = $result1->set($dt)->insert('FUNDS_WD_KI');    

                if($result1) {
                    $data = $this->db->query('SELECT ID FROM FUNDS_WD_KI ORDER BY ID DESC OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY')->row();
                    $inserted_id = $data->ID ;
                    $PK_ID = $param['PK_NUMBER']."$".$param['VALUE_DATE'].'$'.$CTRWD;
                    $result1 = $this->db->set('PK_ID', $PK_ID)
                                ->where('ID', $inserted_id)
                                ->update('FUNDS_WD_KI');
                }
                
            // } else {
            //     $dt['CREATED_BY'] = $param['USERNAME'];
            //     $result1 = $result1->set($dt)
            //             ->where(['UUID' => $param['UUID']])
            //             ->update('FUNDS_WITHDRAW');
            // }

            if ($result1) {
                $result = TRUE;
                $msg = 'Data has been Successfully Saved !!';
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $msg,
                    'IDS' => $CTRWD
                ];
            } else {
                $this->db->trans_rollback(); 
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

    public function ShowWithdrawDataKI($param) {
        $q = "SELECT FW.ID, FW.CTRWD, FM.UUID,
        C.COMPANYCODE,
        FWDKI.CONTRACT_NUMBER,
        FKI.CURRENCY,
        FM.PK_NUMBER,
        FM.CREDIT_TYPE,
        FM.SUB_CREDIT_TYPE,
        FW.DRAWDOWN,
        FW.DRAWDOWN_VALUE,
        CASE 
             WHEN FWDKI.BALANCE IS NULL THEN FWDKI.LIMIT_TRANCHE
             ELSE FWDKI.BALANCE
             END
         AS BALANCE,
        -- FDW.SUB_CREDIT_TYPE as SUB_CREDIT_TYPE_1,
        -- FW.AMOUNT_BALANCE,
        FW.CREATED_AT,
        FW.STATUS
   FROM FUNDS_WD_KI FW
         LEFT JOIN (
                 SELECT CTRWD, ID, FWDKI.UUID, BALANCE, FM.LIMIT_TRANCHE, FM.CONTRACT_NUMBER FROM FUNDS_WD_KI FWDKI
                 LEFT JOIN (
                     SELECT UUID, SUM(LIMIT_TRANCHE) AS LIMIT_TRANCHE, MAX(CONTRACT_NUMBER) AS CONTRACT_NUMBER FROM FUNDS_DETAIL_KI_TRANCHE WHERE ISACTIVE = 1 GROUP BY UUID
                 ) FM ON FWDKI.UUID = FM.UUID
         ) FWDKI ON FW.UUID = FWDKI.UUID AND FWDKI.ID = FW.ID
        LEFT JOIN FUNDS_MASTER FM ON (FW.UUID = FM.UUID) 
        LEFT JOIN FUNDS_DETAIL_KI FKI ON FKI.UUID = FW.UUID AND FKI.ISACTIVE = 1
        LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
        LEFT JOIN BANK B ON B.FCCODE = FM.BANK 
        WHERE FM.ISACTIVE = 1
        ORDER BY FW.ID DESC";
        $result = $this->db->query($q)->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    // 0 1
    public function ShowWithdrawData($param) {
        // $q = "SELECT FW.ID,
        //         FM.UUID,
        //            C.COMPANYCODE,
        //            FM.CONTRACT_NUMBER,
        //            FM.PK_NUMBER,
        //            FM.CREDIT_TYPE,
        //            FM.SUB_CREDIT_TYPE,
        //            -- FDW.SUB_CREDIT_TYPE as SUB_CREDIT_TYPE_1,
        //            -- CASE WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
        //            -- WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
        //            -- WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
        //            -- ELSE 0 END AS AMOUNT_LIMIT,
        //            FW.AMOUNT_BALANCE,
        //            FW.CREATED_AT,
        //            FW.STATUS
        //       FROM FUNDS_WITHDRAW FW
        //            LEFT JOIN FUNDS_MASTER FM ON (FW.UUID = FM.UUID AND FW.WD_TYPE = FM.SUB_CREDIT_TYPE)
        //            LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
        //            LEFT JOIN BANK B ON B.FCCODE = FM.BANK
        //            -- LEFT JOIN FUNDS_DETAIL_WA FDW ON FDW.UUID = FM.UUID
        //            -- LEFT JOIN FUNDS_DETAIL_RK FDR ON FDR.UUID = FM.UUID 
        //            WHERE FW.STATUS = '0'
        //            ORDER BY FW.CREATED_AT DESC";
            $q  =" SELECT DISTINCT FW.ID,
            FW.UUID,
            FW.WD_TYPE AS SUB_CREDIT_TYPE_1,
            FW.SUB_WD_TYPE AS SUB_SCT_1,
            CASE 
                WHEN FW.WD_TYPE = 'RK' THEN FDR.CONTRACT_NUMBER
                WHEN FW.WD_TYPE = 'TL' THEN FDR.CONTRACT_NUMBER
                WHEN FW.WD_TYPE = 'BD' THEN FDR.CONTRACT_NUMBER
                WHEN FW.WD_TYPE = 'WA' THEN FDW.CONTRACT_NUMBER
                WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AP' THEN FDW.CONTRACT_NUMBER
                WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AR' THEN FDW.CONTRACT_NUMBER
                ELSE FM.CONTRACT_NUMBER
                END
            AS CONTRACT_NUMBER,
            -- FM.CONTRACT_NUMBER,
            C.COMPANYCODE,
            FM.PK_NUMBER,
            FM.CREDIT_TYPE,
            FM.SUB_CREDIT_TYPE,
            CASE WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AP' THEN FDW.ID
            WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AR' THEN FDW.ID
            WHEN FW.WD_TYPE = 'WA' THEN FDW.ID
            ELSE 0 END AS IDDETAPAR, 
            -- CASE WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
            -- WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
            -- WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
            -- ELSE 0 END AS AMOUNT_LIMIT,
            FW.AMOUNT_BALANCE,
            FW.CREATED_AT,
            FW.BATCHID,
            FW.STATUS
        FROM FUNDS_WITHDRAW FW
            LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FW.UUID AND FM.IS_COMPLETE IS NULL
            LEFT JOIN FUNDS_DETAIL_WA FDW ON FDW.UUID = FW.UUID AND (FDW.SUB_CREDIT_TYPE = 
                (
                    CASE
                        WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AP' THEN 'KMK_SCF_AP'
                        WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AR' THEN 'KMK_SCF_AR' 
                        WHEN FW.WD_TYPE = 'WA' THEN 'WA'
                        ELSE FW.WD_TYPE
                        END
                ) 
            )
            LEFT JOIN FUNDS_DETAIL_RK FDR ON FDR.UUID = FW.UUID
            LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
            WHERE FW.STATUS in('0','1','2')
            ORDER BY FW.CREATED_AT DESC" ;
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function ShowDataWithdraw($param){
        // $q = "SELECT FM.UUID,
        //        C.COMPANYCODE,
        //        CASE
        //           WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.CONTRACT_NUMBER
        //           WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.CONTRACT_NUMBER
        //           WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.CONTRACT_NUMBER
        //           WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.CONTRACT_NUMBER
        //           WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CONTRACT_NUMBER
        //           WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CONTRACT_NUMBER
        //           ELSE FDK.CONTRACT_NUMBER
        //        END
        //           AS CONTRACT_NUMBER,
        //        FM.PK_NUMBER,
        //        FM.CREDIT_TYPE,
        //        FM.SUB_CREDIT_TYPE,
        //        FDW.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPE_1,
        //        CASE
        //           WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
        //           WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.AMOUNT_LIMIT
        //           WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.AMOUNT_LIMIT
        //           WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.AMOUNT_LIMIT
        //           WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
        //           WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
        //           ELSE FDK.AMOUNT_LIMIT
        //        END
        //           AS AMOUNT_LIMIT,
        //        CASE
        //           WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_BALANCE
        //           WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.AMOUNT_BALANCE
        //           WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.AMOUNT_BALANCE
        //           WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.AMOUNT_BALANCE
        //           WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_BALANCE
        //           WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_BALANCE
        //           ELSE FDK.AMOUNT_BALANCE
        //        END
        //           AS AMOUNT_BALANCE,
        //        CASE
        //           WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.CREATED_AT
        //           WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.CREATED_AT
        //           WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.CREATED_AT
        //           WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.CREATED_AT
        //           WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CREATED_AT
        //           WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CREATED_AT
        //           ELSE FDK.CREATED_AT
        //        END
        //           AS CREATED_AT
        //   FROM FUNDS_MASTER FM
        //        LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
        //        LEFT JOIN BANK B ON B.FCCODE = FM.BANK
        //     --    LEFT JOIN (SELECT DISTINCT FA.UUID,
        //     --                               FA.SUB_CREDIT_TYPE,
        //     --                               FA.AMOUNT_LIMIT,
        //     --                               FA.CONTRACT_NUMBER,
        //     --                               FW.AMOUNT_BALANCE,
        //     --                               FW.CREATED_AT,
        //     --                               FW.WD_TYPE,
        //     --                               FW.SUB_WD_TYPE,
        //     --                               FW.SUB_BALANCE
        //     --                 FROM FUNDS_DETAIL_WA FA
        //     --                      LEFT JOIN
        //     --                      (SELECT *
        //     --                         FROM (SELECT UUID,
        //     --                                      AMOUNT_BALANCE,
        //     --                                      SUB_BALANCE,
        //     --                                      WD_TYPE,
        //     --                                      SUB_WD_TYPE,
        //     --                                      CREATED_AT,
        //     --                                      MAX (CREATED_AT)
        //     --                                         OVER (PARTITION BY UUID, WD_TYPE, SUB_WD_TYPE)
        //     --                                         max_date
        //     --                                 FROM FUNDS_WITHDRAW
        //     --                                WHERE STATUS = '1')
        //     --                        WHERE CREATED_AT = max_date) FW
        //     --                         ON (FA.UUID = FW.UUID)
        //     --                WHERE FA.IS_ACC = '1' AND FA.ISACTIVE = '1') FDW
        //     --       ON FDW.UUID = FM.UUID
        //           LEFT JOIN (SELECT * FROM (SELECT DISTINCT FA.UUID,
        //                                   FA.SUB_CREDIT_TYPE,
        //                                   FA.AMOUNT_LIMIT,
        //                                   FA.CONTRACT_NUMBER,
        //                                   FW.CREATED_AT,
        //                                   MAX(FW.CREATED_AT)
        //                                     OVER (PARTITION BY FA.UUID, FA.SUB_CREDIT_TYPE)
        //                                     max_date_up,
        //                                   FW.WD_TYPE,
        //                                   CASE
        //                                     WHEN FA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FW.SUB_BALANCE
        //                                     WHEN FA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FW.SUB_BALANCE
        //                                     ELSE FW.AMOUNT_BALANCE
        //                                    END
        //                                    AS AMOUNT_BALANCE,
        //                                   FW.SUB_WD_TYPE
        //                     FROM FUNDS_DETAIL_WA FA
        //                          LEFT JOIN
        //                          (SELECT *
        //                             FROM (SELECT UUID,
        //                                          AMOUNT_BALANCE,
        //                                          SUB_BALANCE,
        //                                          WD_TYPE,
        //                                          SUB_WD_TYPE,
        //                                          CREATED_AT,
        //                                          MAX (CREATED_AT)
        //                                             OVER (PARTITION BY UUID, WD_TYPE, SUB_WD_TYPE)
        //                                             max_date
        //                                     FROM FUNDS_WITHDRAW
        //                                    WHERE STATUS = '1')
        //                            WHERE CREATED_AT = max_date) FW
        //                             ON (FA.UUID = FW.UUID AND (FA.SUB_CREDIT_TYPE = FW.WD_TYPE OR FA.SUB_CREDIT_TYPE = FW.SUB_WD_TYPE ))
        //                    WHERE FA.IS_ACC = '1' AND FA.ISACTIVE = '1' )
        //                    ) FDW
        //           ON FDW.UUID = FM.UUID
        //        LEFT JOIN
        //        (SELECT DISTINCT FR.UUID,
        //                         FR.SUB_CREDIT_TYPE,
        //                         FR.AMOUNT_LIMIT,
        //                         FR.CONTRACT_NUMBER,
        //                         FW.AMOUNT_BALANCE,
        //                         FW.CREATED_AT
        //           FROM FUNDS_DETAIL_RK FR
        //                LEFT JOIN
        //                (SELECT UUID,
        //                        AMOUNT_BALANCE,
        //                        WD_TYPE,
        //                        CREATED_AT,
        //                        MAX (CREATED_AT)
        //                           OVER (PARTITION BY UUID, WD_TYPE)
        //                           max_date
        //                   FROM FUNDS_WITHDRAW
        //                  WHERE STATUS = '1') FW
        //                   ON (    FR.UUID = FW.UUID
        //                       AND FW.WD_TYPE = FR.SUB_CREDIT_TYPE)
        //          WHERE     FR.IS_ACC = '1'
        //                AND FR.ISACTIVE = '1'
        //                AND FW.CREATED_AT = fw.max_date) FDR
        //           ON FDR.UUID = FM.UUID
        //        LEFT JOIN
        //        (SELECT FR.UUID,
        //                FR.SUB_CREDIT_TYPE,
        //                FDR.AMOUNT_LIMIT,
        //                FDR.CONTRACT_NUMBER,
        //                FW.AMOUNT_BALANCE,
        //                FW.CREATED_AT
        //           FROM FUNDS_DETAIL_KI FR
        //                LEFT JOIN
        //                (SELECT UUID, LIMIT_TRANCHE AMOUNT_LIMIT, CONTRACT_NUMBER
        //                   FROM FUNDS_DETAIL_KI_TRANCHE) FDR
        //                   ON FR.UUID = FDR.UUID
        //                LEFT JOIN
        //                (SELECT UUID,
        //                        BALANCE AMOUNT_BALANCE,
        //                        DRAWDOWN WD_TYPE,
        //                        CREATED_AT,
        //                        MAX (CREATED_AT) OVER (PARTITION BY UUID, DRAWDOWN)
        //                           max_date
        //                   FROM FUNDS_WD_KI
        //                  WHERE STATUS = '1') FW
        //                   ON (FR.UUID = FW.UUID AND FW.WD_TYPE = FR.SUB_CREDIT_TYPE)
        //          WHERE     FR.IS_ACC = '1'
        //                AND FR.ISACTIVE = '1'
        //                AND FW.CREATED_AT = fw.max_date) FDK
        //           ON FDK.UUID = FM.UUID
        //  WHERE FM.IS_ACC = '1'";
        $q = "SELECT * FROM (SELECT FM.UUID,
                    C.COMPANYCODE,
                    CASE
                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.CONTRACT_NUMBER
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.CONTRACT_NUMBER
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.CONTRACT_NUMBER
                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.CONTRACT_NUMBER
                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CONTRACT_NUMBER
                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CONTRACT_NUMBER
                    WHEN FM.CREDIT_TYPE = 'KI'  THEN FDK.CONTRACT_NUMBER
                    ELSE FDK.CONTRACT_NUMBER
                    END
                    AS CONTRACT_NUMBER,
                    FM.PK_NUMBER,
                    FM.CREDIT_TYPE,
                    FM.SUB_CREDIT_TYPE,
                    FDW.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPE_1,
                    CASE
                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.AMOUNT_LIMIT
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.AMOUNT_LIMIT
                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.AMOUNT_LIMIT
                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
                    WHEN FM.CREDIT_TYPE = 'KI' THEN FDK.AMOUNT_LIMIT
                    ELSE 0
                    END
                    AS AMOUNT_LIMIT,
                    CASE
                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_BALANCE
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.AMOUNT_BALANCE
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.AMOUNT_BALANCE
                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.AMOUNT_BALANCE
                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_BALANCE
                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_BALANCE
                    WHEN FM.CREDIT_TYPE ='KI' THEN FDK.AMOUNT_BALANCE
                    ELSE 0
                    END
                    AS AMOUNT_BALANCE,
                    CASE
                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.CREATED_AT
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.CREATED_AT
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.CREATED_AT
                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.CREATED_AT
                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CREATED_AT
                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CREATED_AT
                    WHEN FM.CREDIT_TYPE ='KI' THEN FDK.CREATED_AT
                    ELSE FDK.CREATED_AT
                    END
                    AS CREATED_AT,
                    CASE
                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.CURRENCY
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.CURRENCY
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.CURRENCY
                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.CURRENCY
                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CURRENCY
                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CURRENCY
                    WHEN FM.CREDIT_TYPE ='KI' THEN FDK.CURRENCY
                    ELSE FDK.CURRENCY
                    END
                    AS CURRENCY,
                    MAX(FDW.CREATED_AT) OVER(PARTITION BY FM.UUID, FDW.SUB_CREDIT_TYPE) fdw_max_date_last,
                    MAX(FDR.CREATED_AT) OVER(PARTITION BY FM.UUID, FDW.SUB_CREDIT_TYPE) fdr_max_date_last,
                    MAX(FDK.CREATED_AT) OVER(PARTITION BY FM.UUID, FDW.SUB_CREDIT_TYPE) fdki_max_date_last
                    FROM FUNDS_MASTER FM
                    LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                    LEFT JOIN BANK B ON B.FCCODE = FM.BANK
                    LEFT JOIN (SELECT * FROM (SELECT DISTINCT FA.UUID,
                                            FA.SUB_CREDIT_TYPE,
                                            FA.AMOUNT_LIMIT,
                                            FA.CONTRACT_NUMBER,
                                            FA.CURRENCY,
                                            FW.CREATED_AT,
                                            MAX(FW.CREATED_AT)
                                                OVER (PARTITION BY FA.UUID, FA.SUB_CREDIT_TYPE)
                                                max_date_up,
                                            FW.WD_TYPE,
                                            CASE
                                                WHEN FA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FW.SUB_BALANCE
                                                WHEN FA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FW.SUB_BALANCE
                                                ELSE FW.AMOUNT_BALANCE
                                                END
                                                AS AMOUNT_BALANCE,
                                            CASE
                                                WHEN FW.SUB_WD_TYPE IS NULL AND FA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN 'KMK_SCF_AP'
                                                WHEN FW.SUB_WD_TYPE IS NULL AND FA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN 'KMK_SCF_AR'
                                                ELSE FW.SUB_WD_TYPE
                                                END
                                                AS SUB_WD_TYPE
                                FROM FUNDS_DETAIL_WA FA
                                    LEFT JOIN
                                    (SELECT *
                                        FROM (SELECT UUID,
                                                    AMOUNT_BALANCE,
                                                    SUB_BALANCE,
                                                    WD_TYPE,
                                                    SUB_WD_TYPE,
                                                    CREATED_AT,
                                                    MAX (CREATED_AT)
                                                        OVER (PARTITION BY UUID, WD_TYPE, SUB_WD_TYPE)
                                                        max_date
                                                FROM FUNDS_WITHDRAW
                                                WHERE STATUS = '1')
                                        WHERE CREATED_AT = max_date) FW
                                        ON (FA.UUID = FW.UUID AND (FA.SUB_CREDIT_TYPE = FW.WD_TYPE OR FA.SUB_CREDIT_TYPE = FW.SUB_WD_TYPE ))
                                WHERE FA.IS_ACC = '1' AND FA.ISACTIVE = '1' )
                                ) FDW
                    ON FDW.UUID = FM.UUID
                    LEFT JOIN
                    (SELECT DISTINCT FR.UUID,
                                    FR.SUB_CREDIT_TYPE,
                                    FR.CURRENCY,
                                    FR.AMOUNT_LIMIT,
                                    FR.CONTRACT_NUMBER,
                                    FW.AMOUNT_BALANCE,
                                    FW.CREATED_AT
                    FROM FUNDS_DETAIL_RK FR
                            LEFT JOIN
                            (SELECT UUID,
                                    AMOUNT_BALANCE,
                                    WD_TYPE,
                                    CREATED_AT,
                                    MAX (CREATED_AT)
                                    OVER (PARTITION BY UUID, WD_TYPE)
                                    max_date
                            FROM FUNDS_WITHDRAW
                            WHERE STATUS = '1') FW
                            ON (    FR.UUID = FW.UUID
                                AND FW.WD_TYPE = FR.SUB_CREDIT_TYPE)
                    WHERE     FR.IS_ACC = '1'
                            AND FR.ISACTIVE = '1'
                            AND FW.CREATED_AT = fw.max_date) FDR
                    ON FDR.UUID = FM.UUID
                    LEFT JOIN
                    (SELECT FR.UUID,
                            FR.SUB_CREDIT_TYPE,
                            FR.CURRENCY,
                            FDR.AMOUNT_LIMIT,
                            FDR.CONTRACT_NUMBER,
                            FW.AMOUNT_BALANCE,
                            FW.CREATED_AT
                    FROM FUNDS_DETAIL_KI FR
                            LEFT JOIN
                            (SELECT FDRL.UUID, MAX(CONTRACT_NUMBER) AS CONTRACT_NUMBER, SUM(FDRL.AMOUNT_LIMIT) AS AMOUNT_LIMIT FROM(SELECT UUID, LIMIT_TRANCHE AMOUNT_LIMIT, CONTRACT_NUMBER, COUNTER, MAX(COUNTER) OVER (PARTITION BY UUID) as last_lim
                            FROM FUNDS_DETAIL_KI_TRANCHE ORDER BY COUNTER DESC) FDRL WHERE COUNTER = last_lim GROUP BY FDRL.UUID ) FDR
                            ON FR.UUID = FDR.UUID
                            LEFT JOIN (SELECT * FROM
                            (SELECT UUID, CTRWD,
                                    BALANCE AMOUNT_BALANCE,
                                    DRAWDOWN WD_TYPE,
                                    CREATED_AT,
                                    MAX (CTRWD) OVER (PARTITION BY UUID, DRAWDOWN)
                                    max_ctrwd,
                                    MAX (CREATED_AT) OVER (PARTITION BY UUID, DRAWDOWN)
                                    MAX_CREATED_AT
                            FROM (SELECT UUID, CASE 
                                                    WHEN LAST_ID IS NULL THEN CTRWD
                                                    ELSE LAST_ID
                                                    END
                                                    AS CTRWD,
                                        BALANCE,
                                        DRAWDOWN,
                                        STATUS,
                                        IS_PAYMENT,
                                        CREATED_AT FROM FUNDS_WD_KI)FWDKI
                            WHERE STATUS = '1' ) WHERE CTRWD = MAX_CTRWD AND CREATED_AT = MAX_CREATED_AT) FW
                            ON (FR.UUID = FW.UUID)
                    WHERE     FR.IS_ACC = '1'
                            AND FR.ISACTIVE = '1') FDK
                    ON FDK.UUID = FM.UUID
            WHERE FM.IS_ACC = '1' AND FM.ISACTIVE = '1' AND FM.IS_COMPLETE IS NULL and FM.SUB_CREDIT_TYPE <> 'RK')
            WHERE CREATED_AT = fdw_max_date_last OR CREATED_AT = fdr_max_date_last OR CREATED_AT = fdki_max_date_last OR CREATED_AT IS NULL
        " ; 
        $result = $this->db->query($q)->result();
        // var_dump($this->db->last_query());exit;
        $this->db->close();
        return $result;
    }

    public function ShowDataWithdrawold($param) {
        $q = "select * from (SELECT uuid,
                   companycode,
                   contract_number,
                   pk_number,
                   credit_type,
                   sub_credit_type,
                   sub_credit_type_1,
                   amount_limit,
                   amount_balance,
                   RANK () OVER (PARTITION BY UUID ORDER BY MAX (CREATED_AT) DESC)
                      AS RANKI
              FROM ( SELECT FM.UUID,
                               C.COMPANYCODE,
                               FM.CONTRACT_NUMBER,
                               FM.PK_NUMBER,
                               FM.CREDIT_TYPE,
                               FM.SUB_CREDIT_TYPE,
                               FDW.SUB_CREDIT_TYPE as SUB_CREDIT_TYPE_1,
                               CASE WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
                               WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.AMOUNT_LIMIT
                               WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
                               WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
                               ELSE 0 END AS AMOUNT_LIMIT,
                               FW.AMOUNT_BALANCE,
                               FW.CREATED_AT
                          FROM FUNDS_MASTER FM
                               LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                               LEFT JOIN BANK B ON B.FCCODE = FM.BANK
                               LEFT JOIN (SELECT * FROM FUNDS_DETAIL_WA WHERE IS_ACC = '1' AND ISACTIVE = '1') FDW ON FDW.UUID = FM.UUID
                                LEFT JOIN (SELECT * FROM FUNDS_DETAIL_RK WHERE IS_ACC = '1' AND ISACTIVE = '1') FDR ON FDR.UUID = FM.UUID
                                LEFT JOIN (SELECT * FROM FUNDS_DETAIL_KI WHERE IS_ACC = '1' AND ISACTIVE = '1') FDK ON FDK.UUID = FM.UUID
                               LEFT JOIN FUNDS_WITHDRAW FW ON (FW.UUID = FM.UUID AND FW.WD_TYPE = FM.SUB_CREDIT_TYPE) WHERE FM.IS_ACC = '1') GROUP BY uuid,companycode,
                   contract_number,
                   pk_number,
                   credit_type,
                   sub_credit_type,
                   sub_credit_type_1,
                   amount_limit,
                   amount_balance) WHERE RANKI = 1
                         ";
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function GetDataWDbyID($param) {
        $q = "SELECT FW.ID,
                   FM.UUID,
                   C.COMPANYCODE,
                   FM.COMPANY,
                   B.ID AS BUID, B.FCNAME AS BUFCNAME,
                   CASE
                        WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.CONTRACT_NUMBER
                        WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CONTRACT_NUMBER
                        WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CONTRACT_NUMBER
                        ELSE FM.CONTRACT_NUMBER
                    END
                    AS CONTRACT_NUMBER,
                   FM.PK_NUMBER,
                   FM.CREDIT_TYPE,
                   FM.SUB_CREDIT_TYPE,
                   CASE WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
                   WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
                   WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
                   ELSE 0 END AS AMOUNT_LIMIT,
                   FW.AMOUNT_BALANCE,
                   FW.AMOUNT,
                   FW.VALUE_DATE,
                   FW.DUEDATE,
                   FW.RATE,
                   FW.AMOUNT_CONVERT,
                   FW.STATUS
              FROM FUNDS_WITHDRAW FW
                   LEFT JOIN FUNDS_MASTER FM ON (FW.UUID = FM.UUID AND FW.WD_TYPE = FM.SUB_CREDIT_TYPE)
                   LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                   LEFT JOIN BANK B ON B.FCCODE = FM.BANK
                   LEFT JOIN BUSINESSUNIT B ON B.ID = FM.BUNIT
                   LEFT JOIN FUNDS_DETAIL_WA FDW ON FDW.UUID = FM.UUID AND FDW.ISACTIVE = 1
                   LEFT JOIN FUNDS_DETAIL_RK FDR ON FDR.UUID = FM.UUID AND FDR.ISACTIVE = 1
             WHERE FW.UUID = '".$param['UUID']."' AND FW.ID = '".$param['IDDET']."' ORDER BY FW.ID DESC FETCH FIRST 1 ROWS ONLY";
        $result = $this->db->query($q)->row();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function GetDataWDKIbyID($param) {
    //     $q = "SELECT FW.ID,
    //         FM.UUID,
    //         FWDT.BATCHID AS IDHEADER,
    //         C.COMPANYCODE,
    //         FM.COMPANY,
    //         B.ID AS BUID,
    //         B.FCNAME AS BUFCNAME,
    //         FM.CONTRACT_NUMBER,
    //         FM.PK_NUMBER,
    //         FM.CREDIT_TYPE,
    //         FM.SUB_CREDIT_TYPE,
    //         FWD.LIMIT_TRANCHE,
    //         FW.TRANCHE_NUMBER,
    //         FW.DRAWDOWN,
    //         FW.VALUE_DATE,
    //         FW.DRAWDOWN_VALUE,
    //         FW.RATE_IDRUSD,
    //         FW.RATE_CNYUSD,
    //         FW.RATE_SGDUSD,
    //         FM.IDC_STATUS,
    //         FW.STATUS
    //    FROM FUNDS_WD_KI FW
    //         LEFT JOIN FUNDS_MASTER FM
    //            ON (FW.UUID = FM.UUID)
    //            LEFT JOIN (SELECT FWD.TRANCHE_NUMBER, FKT.LIMIT_TRANCHE
    //            FROM FUNDS_WD_KI_DETAIL FWD
    //                 INNER JOIN FUNDS_DETAIL_KI_TRANCHE FKT
    //                    ON     FKT.UUID = FWD.UUID
    //                       AND FKT.TRANCHE_NUMBER = FWD.TRANCHE_NUMBER) FWD ON (FW.TRANCHE_NUMBER = FWD.TRANCHE_NUMBER)
    //         LEFT JOIN FUNDS_WD_KI_TRANCHE FWDT ON (FW.ID = FWDT.BATCHID)
    //         LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
    //         LEFT JOIN BANK B ON B.FCCODE = FM.BANK
    //         LEFT JOIN BUSINESSUNIT B ON B.ID = FM.BUNIT
    //          WHERE FW.UUID = '".$param['UUID']."' AND FW.ID = '".$param['IDDET']."' ORDER BY FW.ID DESC FETCH FIRST 1 ROWS ONLY";

        $q = "SELECT FWDT.BATCHID AS ID,
                    FKI.UUID,
                    FWDT.BATCHID AS IDHEADER,
                    C.COMPANYCODE,
                    FM.COMPANY,
                    B.ID AS BUID,
                    B.FCNAME AS BUFCNAME,
                    FM.CONTRACT_NUMBER,
                    FM.PK_NUMBER,
                    FM.CREDIT_TYPE,
                    FM.SUB_CREDIT_TYPE,
                    FKI.TRANCHE_NUMBER,
                    FWD.DRAWDOWN,
                    FWD.VALUE_DATE,
                    CASE
                    WHEN FWDT.BALANCE IS NULL
                    THEN
                        (     SELECT BALANCE
                                FROM FUNDS_WD_KI_TRANCHE
                                WHERE     TRANCHE_NUMBER = FKI.TRANCHE_NUMBER AND UUID = FKI.UUID
                                    AND STATUS = 1
                                    AND BATCHID < '".$param['IDDET']."'
                            ORDER BY BATCHID DESC, COUNTER DESC NULLS LAST
                        FETCH FIRST 1 ROWS ONLY)
                    ELSE
                        FWDT.BALANCE
                    END
                    AS BALANCE,
                    FKI.LIMIT_TRANCHE,
                    CASE WHEN FWDT.DDOWN_AMT IS NULL THEN 0 ELSE FWDT.DDOWN_AMT END
                    AS DRAWDOWN_VALUE,
                    FWD.RATE_IDRUSD,
                    FWD.RATE_CNYUSD,
                    FWD.RATE_SGDUSD,
                    FM.IDC_STATUS,
                    FWD.STATUS,
                    FKI.CURRENCY
            FROM FUNDS_DETAIL_KI_TRANCHE FKI
                    LEFT JOIN FUNDS_WD_KI FWD ON FWD.UUID = FKI.UUID
                    LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FKI.UUID
                    LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                    LEFT JOIN BUSINESSUNIT B ON B.ID = FM.BUNIT
                    LEFT JOIN (SELECT BATCHID,
                                    BALANCE,
                                    DDOWN_AMT,
                                    TRANCHE_NUMBER,
                                    UUID
                                FROM FUNDS_WD_KI_TRANCHE FKIT
                                WHERE BATCHID = '".$param['IDDET']."' AND UUID = '".$param['UUID']."' AND IS_PAYMENT IS NULL) FWDT
                    ON FKI.TRANCHE_NUMBER = FWDT.TRANCHE_NUMBER
            WHERE     FKI.UUID = '".$param['UUID']."'
                    AND FKI.ISACTIVE = '1'
                    AND FWD.CTRWD = '".$param['IDDET']."'
                    AND FWD.UUID = '".$param['UUID']."'
                    ORDER BY IDHEADER NULLS LAST" ;

        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function GetDataWDRK($param) {
        $q = "SELECT FW.ID,
                    FM.UUID,
                    C.COMPANYCODE,
                    FM.COMPANY,
                    B.ID AS BUID,
                    B.FCNAME AS BUFCNAME,
                    CASE
                        WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.CONTRACT_NUMBER
                        WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CONTRACT_NUMBER
                        WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CONTRACT_NUMBER
                        ELSE FM.CONTRACT_NUMBER
                    END
                    AS CONTRACT_NUMBER,
                    FM.PK_NUMBER,
                    FM.CREDIT_TYPE,
                    FM.SUB_CREDIT_TYPE,
                    CASE
                    WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
                    ELSE 0
                    END
                    AS AMOUNT_LIMIT,
                    CASE
                    WHEN FW.WD_TYPE = 'WA' THEN FW.AMOUNT_BALANCE
                    WHEN FW.WD_TYPE = 'RK' THEN FW.AMOUNT_BALANCE
                    WHEN FW.WD_TYPE = 'TL' THEN FW.AMOUNT_BALANCE
                    ELSE 0
                    END
                    AS AMOUNT_BALANCE
            FROM FUNDS_MASTER FM
                    LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                    LEFT JOIN BANK B ON B.FCCODE = FM.BANK
                    LEFT JOIN BUSINESSUNIT B ON B.ID = FM.BUNIT
                    LEFT JOIN FUNDS_DETAIL_WA FDW
                    ON FDW.UUID = FM.UUID AND FDW.SUB_CREDIT_TYPE = FM.SUB_CREDIT_TYPE AND FDW.ISACTIVE = 1
                    LEFT JOIN FUNDS_DETAIL_RK FDR
                    ON FDR.UUID = FM.UUID AND FDR.SUB_CREDIT_TYPE = FM.SUB_CREDIT_TYPE AND FDR.ISACTIVE = 1
                    LEFT JOIN
                    (  SELECT UUID,
                            ID,
                            AMOUNT_BALANCE,
                            WD_TYPE
                        FROM FUNDS_WITHDRAW
                        WHERE UUID = '".$param['UUID']."' AND STATUS = 1 
                    ORDER BY ID DESC
                    FETCH FIRST 1 ROWS ONLY) FW 
                    ON (FW.UUID = FM.UUID AND FW.WD_TYPE = FM.SUB_CREDIT_TYPE)
            WHERE FM.UUID = '".$param['UUID']."' 
            ORDER BY FW.ID DESC
            FETCH FIRST 1 ROWS ONLY
            ";
        $result = $this->db->query($q)->row();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function GetDataWDKI($param) {
        $q = "SELECT FM.UUID,
                   C.COMPANYCODE,
                   FM.COMPANY,
                   B.ID AS BUID, B.FCNAME AS BUFCNAME,
                   FM.CONTRACT_NUMBER,
                   FM.PK_NUMBER,
                   FM.CREDIT_TYPE,
                   FM.SUB_CREDIT_TYPE,
                   FM.IDC_STATUS
              FROM FUNDS_MASTER FM
                   LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                   LEFT JOIN BUSINESSUNIT B ON B.ID = FM.BUNIT
             WHERE FM.UUID = '".$param['UUID']."'";
        $result = $this->db->query($q)->row();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    function SaveWDKIDet($param){
        // var_dump($param); 
        try{
            $USERNAME = $param['USERNAME'];
            $IDHEADER = $param['IDHEADER'] ? $param['IDHEADER'] : $param['IDHEADER1'];
            $DRAWDOWN_VALUE      = intval(preg_replace("/[^\d\.\-]/","",$param['DRAWDOWN_VALUE']));

            // take balance of tranche
            $qtr = "SELECT FKI.TRANCHE_NUMBER, BAL.BATCHID, 
                        BAL.BALANCE,
                    FKI.LIMIT_TRANCHE, FKI.CURRENCY, FKI.COUNTER
                        FROM FUNDS_DETAIL_KI_TRANCHE FKI 
                LEFT JOIN (SELECT BALANCE, TRANCHE_NUMBER, UUID, BATCHID FROM FUNDS_WD_KI_TRANCHE WHERE STATUS = 1 AND TRANCHE_NUMBER = '".$param['TRANCHE_NUMBER']."' AND UUID = '".$param['UUID']."' ORDER BY COUNTER NULLS LAST, COUNTER DESC, BATCHID DESC FETCH FIRST 1 ROWS ONLY) BAL ON BAL.UUID = FKI.UUID AND BAL.TRANCHE_NUMBER = FKI.TRANCHE_NUMBER
                WHERE FKI.UUID = '".$param['UUID']."' AND FKI.TRANCHE_NUMBER = '".$param['TRANCHE_NUMBER']."' AND FKI.ISACTIVE = 1 ";
            $baltranche = $this->db->query($qtr)->row();
            // var_dump($this->db->last_query()); exit;
            $currentBalance = intval((($baltranche->BALANCE != null) ? $baltranche->BALANCE : $baltranche->LIMIT_TRANCHE)) ;
            // var_dump($currentBalance); exit ;
            // var_dump($baltranche->LIMIT_TRANCHE, $baltranche->DDOWN_AMT, $baltranche->BALANCE); exit;
            if($DRAWDOWN_VALUE > $currentBalance) {
                $msg = "Drawdown Value is over the limit" ;
                throw new Exception($msg);
            }
            $this->db->trans_begin(); 
            if($param['DRAWDOWN_TYPE'] == 'DISBURSEMENT' || $param['DRAWDOWN_TYPE'] == 'COMBINE'){  

            $result = FALSE;
            $config['upload_path']          = ROOT;
            $config['allowed_types']        = 'pdf|docx|doc|xls|xlsx';
            $config['overwrite']            = TRUE;
            $config['max_size']             = 5421;

            $this->load->library('upload');
            $this->upload->initialize($config);
            // var_dump($config);exit;
            // if (!$this->upload->do_upload('userfile')){
            //     throw new Exception($this->upload->display_errors());
                
            // }else
            // if(!empty($_FILES)){
                $this->upload->do_upload('userfile');
                $dataUpload = $this->upload->data();
                
            // }
            $INVOICE_VALUE       = intval(preg_replace("/[^\d\.\-]/","",$param['INVOICE_VALUE']));
            $dt_invoice = [
                'VENDOR' => $param['VENDOR'],
                'VENDOR_BANK' => $param['BANK'],
                'VENDOR_BANK_ACC' => $param['NOREK'],
                'INVOICE_NUMBER' => $param['INVOICE_NUMBER'],
                'PO_NUMBER' => $param['DOCNUMBER'],
                'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'],
                'INVOICE_VALUE' => $INVOICE_VALUE,
                'DRAWDOWN_VALUE' => $DRAWDOWN_VALUE,
                'CURRENCY' => $param['CURRENCY'],
                'UUID' => $param['UUID'],
                'CREATED_BY'=>$USERNAME,
                'IDHEADER'=> $IDHEADER

            ];
            if(!empty($_FILES['userfile']['name'][0])){
                $dt_invoice['FILENAME'] = $param['FILENAME'];
            }
            }
        // var_dump($dataUpload);exit;
        // var_dump($IDHEADER); exit;
        // var_dump($dt_invoice); exit;
           
            // var_dump($dataUpload);exit;
            if($param['ID'] == NULL){
                if($param['DRAWDOWN_TYPE'] == 'DISBURSEMENT' || $param['DRAWDOWN_TYPE'] == 'COMBINE'){
                    $result = $this->db->set('CREATED_AT', "SYSDATE", false)
                    ->set("DRAWDOWN_DATE","TO_DATE('" . $param['DRAWDOWN_DATE'] . "','yyyy-mm-dd')", false)
                    ->set("INVOICE_DATE", "TO_DATE('". $param['INVOICE_DATE'] . "', 'yyyy-mm-dd')", false)
                    ->set($dt_invoice)
                    ->insert('FUNDS_WD_KI_DETAIL');     
                }
                
                // new detail data
                $checkTranche = $this->db->select("TRANCHE_NUMBER, DDOWN_AMT, BATCHID")
                                ->from('FUNDS_WD_KI_TRANCHE')
                                ->where(array('TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'], 'BATCHID' => ($IDHEADER), 'UUID' => $param['UUID'] ))->get()->row();
                if($checkTranche == null) {
                    $result = $this->db->set('CREATED_AT', 'SYSDATE', false)
                            ->set('CREATED_BY', $param['USERNAME'])
                            ->set('DDOWN_AMT', $DRAWDOWN_VALUE)
                            ->set('TRANCHE_NUMBER', $param['TRANCHE_NUMBER'])
                            ->set('UUID', $param['UUID'])
                            ->set('STATUS', 0)
                            ->set('BATCHID', $IDHEADER)
                            ->set('C_TR', $baltranche->COUNTER)
                            ->insert('FUNDS_WD_KI_TRANCHE');
                } else {
                    $newDdownamt = intval($checkTranche->DDOWN_AMT) - intval($param['AMTMODALS']) + $DRAWDOWN_VALUE ;
                    $result = $this->db->set('CREATED_AT', 'SYSDATE', false)
                        ->set('CREATED_BY', $param['USERNAME'])
                        ->set('DDOWN_AMT', $newDdownamt)
                        ->where(array('TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'], 'BATCHID' => $IDHEADER, 'UUID' => $param['UUID']))
                        ->update('FUNDS_WD_KI_TRANCHE');
                }
                // check fwdkitranche where tranchenum and batchid 
                // if exist add balance 
                // else new record in tranche

                //add drawdown val to header
                $totalDrawdownVal = $this->db->query('SELECT sum(DDOWN_AMT) as DDOWN_AMT FROM FUNDS_WD_KI_TRANCHE WHERE BATCHID = ? AND UUID = ?', [$IDHEADER, $param['UUID']])->row();
                $this->db->set('DRAWDOWN_VALUE', $totalDrawdownVal->DDOWN_AMT)
                        ->set('CREATED_AT', 'SYSDATE', false)
                        ->set('CREATED_BY', $param['USERNAME'])
                        ->where(['CTRWD' => $IDHEADER, 'UUID' => $param['UUID']])
                        ->update('FUNDS_WD_KI') ;
                
            }else{
                if($param['DRAWDOWN_TYPE'] == 'DISBURSEMENT' || $param['DRAWDOWN_TYPE'] == 'COMBINE') {
                    $this->db->where('ID',$param['ID']);
                    $result = $this->db->set('CREATED_AT', "SYSDATE", false)
                    ->set("DRAWDOWN_DATE","TO_DATE('" . $param['DRAWDOWN_DATE'] . "','yyyy-mm-dd')", false)
                    ->set("INVOICE_DATE", "TO_DATE('". $param['INVOICE_DATE'] . "', 'yyyy-mm-dd')", false)
                    ->set($dt_invoice)
                    ->update('FUNDS_WD_KI_DETAIL'); 
                }
                // detail data exist
                $latestDdownamt = $this->db->select('DDOWN_AMT, TRANCHE_NUMBER, BATCHID')
                ->from('FUNDS_WD_KI_TRANCHE') 
                ->where(array('TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'], 'BATCHID' => $IDHEADER))->get()->row() ;
                // var_dump($this->db->last_query()); exit;
                // update data (plus or minus)
                // var_dump(intval($latestDdownamt->DDOWN_AMT), intval($param['AMTMODALS']), $DRAWDOWN_VALUE) ; exit;
                $newDdownamt = intval($latestDdownamt->DDOWN_AMT) - intval($param['AMTMODALS']) + $DRAWDOWN_VALUE ;
                // var_dump($newDdownamt) ; exit;
                $result = $this->db->set('CREATED_AT', 'SYSDATE', false)
                    ->set('CREATED_BY', $param['USERNAME'])
                    ->set('DDOWN_AMT', $newDdownamt)
                    ->where(array('TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'], 'BATCHID' => $IDHEADER, 'UUID' => $param['UUID']))
                    ->update('FUNDS_WD_KI_TRANCHE');
                //add drawdown val to header
                $totalDrawdownVal = $this->db->query('SELECT sum(DDOWN_AMT) as DDOWN_AMT FROM FUNDS_WD_KI_TRANCHE WHERE BATCHID = ? AND UUID = ?', [$IDHEADER, $param['UUID']])->row();
                
                $this->db->set('DRAWDOWN_VALUE', $totalDrawdownVal->DDOWN_AMT)
                        ->set('CREATED_AT', 'SYSDATE', false)
                        ->set('CREATED_BY', $param['USERNAME'])
                        ->where(['UUID' => $param['UUID'], 'CTRWD' => $IDHEADER])
                        ->update('FUNDS_WD_KI') ;
                // var_dump($this->db->last_query()); exit;

            }

            // var_dump($this->db->last_query());exit;
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "success",
                    'TNUM' => $param['TRANCHE_NUMBER'],
                    'LDDOWN'=> intval($param['AMTMODALS']),
                    'DDOWN'=> $DRAWDOWN_VALUE,
                    'STAT' => "EDIT",
                ];
            }
        }catch(Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        } 
       
        $this->db->close();
        return $return;
        
    }

    function SaveWDNonDiskonto($param){

        $BIL_VAL     = intval(preg_replace("/[^\d\.\-]/","",$param['BIL_VAL']));
        $DDOWN_AMT      = intval(preg_replace("/[^\d\.\-]/","",$param['DDOWN_AMT']));
        $amtModals = intval(preg_replace("/[^\d\.\-]/","",$param['AMTMODALS']));
        $lastconq = "SELECT COUNTER FROM FUNDS_DETAIL_RK WHERE UUID = '".$param['UUID']."' ORDER BY ID DESC" ;
        $lastcon = $this->db->query($lastconq)->row();
        //take amount of main valuedate document
        $getAmount = $this->db->select('AMOUNT')->from('FUNDS_WITHDRAW')->where("UUID = '".$param['UUID']."' AND VALUE_DATE = TO_DATE('".$param['VALUE_DATE']."','yyyy-mm-dd') ")->get()->row();
        // var_dump($getAmount->AMOUNT); exit;
        //
        // calculate current amount, function in BaseModel
        $AMOUNT         = $this->amountBalance($amtModals,(int)$getAmount->AMOUNT, $DDOWN_AMT);
        // 
        //UPDATE : query changed to select record with status 0
        $q = "SELECT * FROM FUNDS_WITHDRAW WHERE UUID = '".$param['UUID']."' AND STATUS = '0' ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY";
        //
        $checkrec = $this->db->query($q)->row();
        // change format from form to db and compare it
        $date = strtotime($param['VALUE_DATE']);
        $date = strtoupper(date('d-M-y', $date));
        //
        try{
            $this->db->trans_begin();
            
            if($checkrec != null){
            // UPDATE : when status from record is 0 or 2 ...
            if ($checkrec->STATUS == '0' || $checkrec->STATUS == '2') {
                // and value date from form and db are different, it means the data has not approved yet so error thrown
                if ($date != $checkrec->VALUE_DATE) {
                    var_dump($date, $checkrec->VALUE_DATE);
                    $msg = "Data Sebelumnya Belum DiApprove, Silakan Approve Terlebih Dahulu";
                    var_dump($msg); exit;
                    throw new Exception($msg);
                    
                }
            }
            //
            if($checkrec->AMOUNT_BALANCE != null && ($DDOWN_AMT > $checkrec->AMOUNT_BALANCE)){
                $msg = "Amount Exceed";

                    throw new Exception($msg);
                }
            }

        $result = FALSE;
        $config['upload_path']          = ROOT;
        $config['allowed_types']        = 'pdf|docx|doc|xls|xlsx';
        $config['overwrite']            = TRUE;
        $config['max_size']             = 5421;

        $this->load->library('upload');
        $this->upload->initialize($config);
        $check = $this->upload->do_upload('userfile');
        $media = $this->upload->data();
        // var_dump($config);exit;
        if (!$check){
            throw new Exception($this->upload->display_errors());
        }
        $USERNAME = $param['USERNAME'];

       
        $dt_invoice = [
            'VENDOR' => $param['VENDOR'],
            'SCF_NDK_TYPE' => $param['SCF_NONDISKONTO_TYPE'],
            'APP_LETTER_NUM' => $param['APP_LETTER_NUM'],
            'BAST_NUM' => $param['BAST_NUM'],
            'INV_NUM' => $param['INV_NUM'],
            'BILLING_VAL' => $BIL_VAL,
            'DDOWN_AMT' => $DDOWN_AMT,
            'UUID' => $param['UUID'],
            'FILE_NAME'=> $media['file_name'],
            'CREATED_BY'=>$USERNAME
        ];
        
        $dt_wd = [
            'AMOUNT'        => $AMOUNT,
            'CONTRACT_NUMBER' => $param['CONTRACT_NUMBER'],
            'CREATED_BY'    => $param['USERNAME'],
            'UUID'          => $param['UUID'],
            'CON_COUNTER'   => $lastcon->COUNTER
        ];

        if(!$checkrec) {
            $dt_wd['WD_TYPE'] = $param['WD_TYPE'];
            $dt_wd['STATUS'] = 0;
            $result1 = $this->db->set('VALUE_DATE',"TO_DATE('".$param['VALUE_DATE']."', 'yyyy-mm-dd')",false)
            ->set('DUEDATE', "TO_DATE('".$param['DUE_DATE']."','yyyy-mm-dd')", false)
            ->set('CREATED_AT', 'SYSDATE', false)
            ->set($dt_wd)->insert('FUNDS_WITHDRAW');
            $lastinsertq =  $this->db->query("SELECT ID FROM FUNDS_WITHDRAW ORDER BY ID DESC OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY")->row();  
            $lastinsert = $lastinsertq->ID;


        } else {
            $result1 = $this->db->get_where('FUNDS_WITHDRAW',['WD_TYPE' => $param['WD_TYPE']])->row();
            $lastinsertq = $this->db->get_where('FUNDS_WITHDRAW',["UUID" => $param["UUID"], "VALUE_DATE" => "TO_DATE('".$param['VALUE_DATE']."', 'yyyy-mm-dd')"])->row();
            $lastinsert = $lastinsertq->ID ;
            $result1 = $this->db->set('CREATED_AT', 'SYSDATE', false);
            $result1 = $this->db->set($dt_wd)->where("UUID = '".$param['UUID']."' AND VALUE_DATE = TO_DATE('".$param['VALUE_DATE']."', 'yyyy-mm-dd')")->update('FUNDS_WITHDRAW');
        }
        $dt_invoice['BATCHID'] = $lastinsert;
        // var_dump($this->db->last_query()); exit;
        // var_dump($result1); exit;
            if($param['ID'] == NULL) {
                $result = $this->db->set('CREATED_AT', "SYSDATE", false)
                ->set("INV_DATE","TO_DATE('" . $param['INV_DATE'] . "','yyyy-mm-dd')", false)
                ->set("VALUE_DATE","TO_DATE('" . $param['VALUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set("DUEDATE","TO_DATE('" . $param['DUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set("BAST_DATE","TO_DATE('" . $param['BAST_DATE'] . "','yyyy-mm-dd')", false)
                ->set("APP_LETTER_DATE","TO_DATE('" . $param['APP_LETTER_DATE'] . "','yyyy-mm-dd')", false)
                ->set($dt_invoice)
                ->insert('WD_FUNDS_NONDISKONTO');
            } else {
                $this->db->where('ID', $param['ID']);
                $result = $this->db->set('CREATED_AT', "SYSDATE", false)
                ->set("DUEDATE", "TO_DATE('". $param['DUE_DATE'] . "', 'yyyy-mm-dd')", false)
                ->set("VALUE_DATE","TO_DATE('" . $param['VALUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set("APP_LETTER_DATE","TO_DATE('" . $param['APP_LETTER_DATE'] . "','yyyy-mm-dd')", false)
                ->set("BAST_DATE","TO_DATE('" . $param['BAST_DATE'] . "','yyyy-mm-dd')", false)
                ->set("INV_DATE","TO_DATE('" . $param['INV_DATE'] . "','yyyy-mm-dd')", false)
                ->set($dt_invoice)
                ->update('WD_FUNDS_NONDISKONTO'); 
            }
            if ($result1 && $result) {

                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "success"
                ];
            }
        }catch(Expection $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        } 
       
        $this->db->close();
        return $return;
        
    }

    public function GetDataWDNonDiskonto($param) {
        $lastidq = "SELECT ID FROM FUNDS_WITHDRAW WHERE WD_TYPE = 'BD' AND UUID = '".$param['UUID']."' AND STATUS = '0' ORDER BY ID DESC ";
        $lastid = $this->db->query($lastidq)->row();
        // var_dump($lastid);
        $sql = "SELECT WDNDK.*, SP.FCNAME as VENDORNAME FROM WD_FUNDS_NONDISKONTO WDNDK 
                        LEFT JOIN SUPPLIER SP ON WDNDK.VENDOR = SP.ID
                        WHERE WDNDK.UUID = ? AND WDNDK.BATCHID = '".$lastid->ID."'";
        $result = $this->db->query($sql, $param['UUID'])->result();
        // var_dump($result);
        // $result = $this->db->select("WD_FUNDS_NONDISKONTO.*, SUPPLIER.FCNAME AS VENDOR")
        //         ->from("WD_FUNDS_NONDISKONTO")
        //         ->join("SUPPLIER", "SUPPLIER.ID = WD_FUNDS_NONDISKONTO.VENDOR")
        //         ->where("UUID", $param['UUID'])->get()->result();
        $this->db->close();
        return $result;
    }

    public function SaveWDARDet($param) {
        try{
            $this->db->trans_begin();
    
            $result = FALSE;
            $config['upload_path']          = ROOT;
            $config['allowed_types']        = 'pdf|docx|doc|xls|xlsx';
            $config['overwrite']            = TRUE;
            $config['max_size']             = 5421;
    
            $this->load->library('upload');
            $this->upload->initialize($config);
            $check = $this->upload->do_upload('userfile');
            $media = $this->upload->data();
            // var_dump($config);exit;
            if (!$check){
                throw new Exception($this->upload->display_errors());
            }
            //getcounter
            $lastconq = "SELECT COUNTER FROM FUNDS_DETAIL_WA WHERE UUID = '".$param['UUID']."' ORDER BY ID DESC";
            $lastcon = $this->db->query($lastconq)->row();
            //take amount of main valuedate document
            $getAmount = $this->db->select('AMOUNT')->from('FUNDS_WITHDRAW')->where("BATCHID = '".$param['BATCHID']."' ")->get()->row();
            //
            //check withdrawal exceed or not
            $q1   = "SELECT * FROM FUNDS_WITHDRAW WHERE UUID = '".$param['UUID']."' AND SUB_WD_TYPE = '".$param['WD_TYPE']."' ORDER BY SUB_BALANCE NULLS LAST, ID DESC FETCH FIRST 1 ROWS ONLY";
            $q2   = "SELECT FA.UUID,
                        FA.ID,
                        FA.AMOUNT_LIMIT,
                        FWD.AMOUNT_BALANCE
                        FROM FUNDS_DETAIL_WA FA
                                LEFT JOIN (  SELECT UUID, AMOUNT_BALANCE, WD_TYPE
                                            FROM FUNDS_WITHDRAW
                                            WHERE WD_TYPE = 'WA'
                                        ORDER BY ID DESC) FWD
                                ON FWD.UUID = FA.UUID
                        WHERE SUB_CREDIT_TYPE = 'WA' AND FA.UUID = '".$param['UUID']."' FETCH FIRST 1 ROWS ONLY";
             $getBalanceAR = $this->db->query($q1)->row();
             $getBalanceWA   = $this->db->query($q2)->row();
             $limitWA = intval($getBalanceWA->AMOUNT_LIMIT);
                $DDOWN_AMT      = intval(preg_replace("/[^\d\.\-]/","",$param['DDOWN_AMT']));
            // var_dump($getLimit); exit;
            if ($getBalanceAR->SUB_BALANCE == null || $getBalanceAR == null) {
                if ($limitWA < $param['DDOWN_AMT']){
                    // var_dump("no balance"); exit;
                    $msg = "Withdrawal Amount Exceeded !" ;
                    throw new Exception($msg);
                }
            } else {
                $balanceAR = intval($getBalanceAR->SUB_BALANCE);
                //  var_dump("balance"); exit;
                if ($balanceAR < $param['DDOWN_AMT']){
                    $msg = "Withdrawal Amount Exceeded !" ;

                    throw new Exception($msg);
                }
            }
            //
            $USERNAME = $param['USERNAME'];
            // take pre-amount from views
            $amtModals      = intval(preg_replace("/[^\d\.\-]/","",$param['AMTMODALS']));
            //
            $BIL_VAL        = intval(preg_replace("/[^\d\.\-]/","",$param['BILLING_VAL']));
            // calculate current amount, function in BaseModel
            $AMOUNT         = $this->amountBalance($amtModals,(int)$getAmount->AMOUNT, $DDOWN_AMT);
            // var_dump($AMOUNT, $amtModals,(int)$getAmount->AMOUNT, $DDOWN_AMT); exit;
            //
            $NET_DISBUR     = intval(preg_replace("/[^\d\.\-]/","",$param['NET_DISBUR']));
            $DISKONTO       = intval(preg_replace("/[^\d\.\-]/","",$param['DISKONTO']));
            $dt_invoice = [
                'BATCHID' => $param['BATCHID'],
                'VENDOR' => $param['VENDOR'],
                'INV_NUM' => $param['INV_NUM'],
                'BILLING_VAL' => $BIL_VAL,
                'DDOWN_AMT' => $DDOWN_AMT,
                'UUID' => $param['UUID'],
                'FILE_NAME'=> $media['file_name'],
                'CREATED_BY'=>$USERNAME,
                'NET_DISBUR'=>$NET_DISBUR,
                'TOTAL_DAYS'=>$param['TOTAL_DAYS'],
                'DISKONTO'=>$DISKONTO,
                'BAST_NUM'=>$param['BAST_NUM']
            ];

            $dt_wd = [
                'AMOUNT' => $AMOUNT,
                'CREATED_BY' => $USERNAME,
                'CON_COUNTER' =>$lastcon->COUNTER
            ];



            if($param['ID'] == NULL){
                //create new batch withdrawal
                $result = $this->db->set('CREATED_AT', "SYSDATE", false)
                ->set("VALUE_DATE","TO_DATE('" . $param['VALUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set("INV_DATE","TO_DATE('" . $param['INV_DATE'] . "','yyyy-mm-dd')", false)
                ->set("DUE_DATE","TO_DATE('" . $param['DUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set("BAST_DATE","TO_DATE('" . $param['BAST_DATE'] . "','yyyy-mm-dd')", false)
                ->set("PAY_DATE","TO_DATE('" . $param['PAY_DATE'] . "','yyyy-mm-dd')", false)
                ->set($dt_invoice)
                ->insert('FUNDS_WDDETAIL_SCFAR');
                //
                //update amount in funds_withdraw
                $result1 = $this->db->set('CREATED_AT', 'SYSDATE', false)
                ->set($dt_wd)->where("BATCHID = '".$param['BATCHID']."'")->update('FUNDS_WITHDRAW');
                //
                // var_dump($this->db->last_query()); exit;
            } else {
                $this->db->where('ID',$param['ID']);
                $result = $this->db->set('CREATED_AT', "SYSDATE", false)
                ->set("VALUE_DATE","TO_DATE('" . $param['VALUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set("INV_DATE","TO_DATE('" . $param['INV_DATE'] . "','yyyy-mm-dd')", false)
                ->set("DUE_DATE","TO_DATE('" . $param['DUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set("BAST_DATE","TO_DATE('" . $param['BAST_DATE'] . "','yyyy-mm-dd')", false)
                ->set("PAY_DATE","TO_DATE('" . $param['PAY_DATE'] . "','yyyy-mm-dd')", false)
                ->set($dt_invoice)
                ->update('FUNDS_WDDETAIL_SCFAR');
                //update amount in FUNDS_WITHDRAW
                $result1 = $this->db->set('CREATED_AT', 'SYSDATE', false)
                ->set($dt_wd)->where("BATCHID = '".$param['BATCHID']."'")->update('FUNDS_WITHDRAW');
                //
            }
                if ($result && $result1) {
                    $this->db->trans_commit();
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => "success"
                    ];
                }
            }catch(Exception $ex) {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => $ex->getMessage()
                ];
            } 
           
            $this->db->close();
            return $return;
    }

    public function GetDataWDARDet($param) {
        $uuid = $param['UUID'];
        $sql = "SELECT WDAR.*, SP.FCNAME as VENDORNAME FROM FUNDS_WDDETAIL_SCFAR WDAR 
                        LEFT JOIN SUPPLIER SP ON WDAR.VENDOR = SP.ID
                        WHERE WDAR.BATCHID = ? ";
        $result = $this->db->query($sql, array($uuid))->result();
        $this->db->close();
        return $result;
    }

    function GetDataWAAR($param){
        // $get_where = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
        // if($get_where->SUB_CREDIT_TYPE == "BD"){
            $SQL = "SELECT L.*, (SELECT CASE 
                                WHEN FWD.AMOUNT_BALANCE IS NULL THEN FDW.AMOUNT_LIMIT
                                ELSE FWD.AMOUNT_BALANCE
                            END
                                AS AMOUNT_LIMIT
                        FROM FUNDS_DETAIL_WA FDW
                            LEFT JOIN FUNDS_WITHDRAW FWD
                                ON     FWD.UUID = FDW.UUID
                                AND FWD.WD_TYPE = FDW.SUB_CREDIT_TYPE
                    WHERE     FDW.UUID = '".$param['UUID']."'
                            AND FDW.SUB_CREDIT_TYPE = 'WA' ORDER BY FWD.CREATED_AT DESC
                    FETCH FIRST 1 ROWS ONLY) AS LIMIT_WA, (SELECT CURRENCY FROM FUNDS_DETAIL_WA WHERE UUID = '".$param['UUID']."' AND SUB_CREDIT_TYPE = 'WA') AS CURR_WA, L.UUID as LID, BU.ID AS BUID, BU.FCNAME AS BUFCNAME,B.BANKACCOUNT || ' - ' || B.FCNAME || ' - ' || B.CURRENCY AS GETBANK, L.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPEMASTER,FR.SUB_CREDIT_TYPE as SCT, FR.* FROM FUNDS_MASTER L LEFT JOIN FUNDS_DETAIL_WA FR ON FR.UUID = L.UUID LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT LEFT JOIN BANK B ON B.FCCODE = L.BANK WHERE L.UUID = ? AND FR.SUB_CREDIT_TYPE = 'KMK_SCF_AR' ORDER BY FR.POS ASC";
            $result = $this->db->query($SQL, $param["UUID"])->row();    
        // }
        
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    function GetDataWAAP($param){
        // $get_where = $this->db->get_where('FUNDS_MASTER',['UUID' => $param['UUID']])->row();
        // if($get_where->SUB_CREDIT_TYPE == "BD"){
            $SQL = "SELECT L.*, (SELECT CASE 
                                WHEN FWD.AMOUNT_BALANCE IS NULL THEN FDW.AMOUNT_LIMIT
                                ELSE FWD.AMOUNT_BALANCE
                            END
                                AS AMOUNT_LIMIT
                        FROM FUNDS_DETAIL_WA FDW
                            LEFT JOIN FUNDS_WITHDRAW FWD
                                ON     FWD.UUID = FDW.UUID
                                AND FWD.WD_TYPE = FDW.SUB_CREDIT_TYPE
                    WHERE     FDW.UUID = '".$param['UUID']."'
                            AND FDW.SUB_CREDIT_TYPE = 'WA' ORDER BY FWD.CREATED_AT DESC
                    FETCH FIRST 1 ROWS ONLY) AS LIMIT_WA, (SELECT CURRENCY FROM FUNDS_DETAIL_WA WHERE UUID = '".$param['UUID']."' AND SUB_CREDIT_TYPE = 'WA') AS CURR_WA, L.UUID as LID, BU.ID AS BUID, BU.FCNAME AS BUFCNAME,B.BANKACCOUNT || ' - ' || B.FCNAME || ' - ' || B.CURRENCY AS GETBANK, L.SUB_CREDIT_TYPE AS SUB_CREDIT_TYPEMASTER,FR.SUB_CREDIT_TYPE as SCT, FR.* FROM FUNDS_MASTER L LEFT JOIN FUNDS_DETAIL_WA FR ON FR.UUID = L.UUID LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT LEFT JOIN BANK B ON B.FCCODE = L.BANK WHERE L.UUID = '".$param['UUID']."' AND FR.SUB_CREDIT_TYPE = 'KMK_SCF_AP' ORDER BY FR.POS ASC";
            $result = $this->db->query($SQL)->row();    
        // }
        
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function GetDataWDAR($param) {
        if ($param['LOOK']) {
            $q = "SELECT FWD.*, TO_CHAR(FWD.VALUE_DATE,'yyyy-MM-dd') as VAL_DATE FROM FUNDS_WITHDRAW FWD WHERE UUID = ? AND SUB_WD_TYPE = ? " ;
            $result = $this->db->query($q, array($param['UUID'], $param['WD_TYPE']))->row();
            // var_dump($this->db->last_query()); exit; 
        }
        else {
            $q = "SELECT TO_CHAR(VALUE_DATE,'yyyy-mm-dd') as VAL_DATE, FWD.*, FWA.AMOUNT_LIMIT 
            FROM FUNDS_WITHDRAW FWD 
            LEFT JOIN FUNDS_DETAIL_WA FWA ON FWD.UUID = FWA.UUID AND FWD.SUB_WD_TYPE = FWA.SUB_CREDIT_TYPE
            WHERE FWD.UUID = ? AND FWD.SUB_WD_TYPE = ? ORDER BY AMOUNT_BALANCE NULLS LAST, FWD.ID DESC FETCH FIRST 1 ROWS ONLY" ;
            $result = $this->db->query($q, array($param['UUID'], $param['WD_TYPE']))->row();
            // var_dump($this->db->last_query()); exit;
        }
        // var_dump($result);
        $tmp = (array) $result;
        if(empty($tmp)) {
            $result['RATE'] = 0;
            $result['INTEREST'] = 0;
            $result['PROVISION'] = 0;
            $result['DATA_EXIST'] = 0;
        } else {
            $result->DATA_EXIST = 1;
        }
        $this->db->close();
        return $result;
    }

    public function SaveWDAPDet($param) {
        try{
            $this->db->trans_begin();
    
            $result = FALSE;
            $config['upload_path']          = ROOT;
            $config['allowed_types']        = 'pdf|docx|doc|xls|xlsx';
            // UPDATE : can overwrite file uploaded
            $config['overwrite']            = TRUE;
            //
            $config['max_size']             = 5421;
    
            $this->load->library('upload');
            $this->upload->initialize($config);
            $check = $this->upload->do_upload('userfile');
            $media = $this->upload->data();
            // var_dump($config);exit;
            if (!$check){
                throw new Exception($this->upload->display_errors());
                
            }
            //check withdrawal exceed or not
            $lastconq = "SELECT COUNTER FROM FUNDS_DETAIL_WA WHERE UUID = '".$param['UUID']."' ORDER BY ID DESC " ;
            $lastcon = $this->db->query($lastconq)->row();
            $q1   = "SELECT * FROM FUNDS_WITHDRAW WHERE UUID = '".$param['UUID']."' AND SUB_WD_TYPE = '".$param['WD_TYPE']."' ORDER BY SUB_BALANCE NULLS LAST, ID DESC FETCH FIRST 1 ROWS ONLY";
            $q2   = "SELECT FA.UUID,
                        FA.ID,
                        FA.AMOUNT_LIMIT,
                        FWD.AMOUNT_BALANCE
                        FROM FUNDS_DETAIL_WA FA
                                LEFT JOIN (  SELECT UUID, AMOUNT_BALANCE, WD_TYPE
                                            FROM FUNDS_WITHDRAW
                                            WHERE WD_TYPE = 'WA'
                                        ORDER BY ID DESC) FWD
                                ON FWD.UUID = FA.UUID
                        WHERE SUB_CREDIT_TYPE = 'KMK_SCF_AP' AND FA.UUID = '".$param['UUID']."' FETCH FIRST 1 ROWS ONLY";
            $getBalanceAP = $this->db->query($q1)->row();
            $getBalanceWA   = $this->db->query($q2)->row();
            // var_dump($getBalanceAP); 
            // var_dump('---');
            // var_dump($getBalanceWA);exit;
            if ($getBalanceAP->SUB_BALANCE == null || $getBalanceAP == null) {
                if ($getBalanceWA->AMOUNT_LIMIT < $param['DDOWN_AMT']){
                    $msg = "Withdrawal Amount Exceeded !" ;
                    throw new Exception($msg);
                }
            } else {
                if ($getBalanceAP->SUB_BALANCE < $param['DDOWN_AMT']){
                    $msg = "Withdrawal Amount Exceeded !" ;

                    throw new Exception($msg);
                }
            }
            //
            $USERNAME = $param['USERNAME'];
            //take amount of main valuedate document for manipulating in table FUNDS WITHDRAW
            $getAmount = $this->db->select('AMOUNT')->from('FUNDS_WITHDRAW')->where("BATCHID = '".$param['BATCHID']."' ")->get()->row();
            //
            
            // amount from the views 
            $amtModals      = intval(preg_replace("/[^\d\.\-]/","",$param['AMTMODALS']));
            //
            $INV_VALUE        = intval(preg_replace("/[^\d\.\-]/","",$param['INV_VALUE']));
            $DDOWN_AMT      = intval(preg_replace("/[^\d\.\-]/","",$param['DDOWN_AMT']));
            //calculate current amount (amountBalance func created in BaseModel)
            $AMOUNT         = $this->amountBalance($amtModals,(int)$getAmount->AMOUNT, $DDOWN_AMT);
            //
            $DISKONTO       = intval(preg_replace("/[^\d\.\-]/","",$param['DISKONTO']));
            $dt_invoice = [
                'BATCHID' => $param['BATCHID'],
                'UUID' => $param['UUID'],
                'FILE_NAME'=> $param['FILENAME'],
                'CREATED_BY'=>$USERNAME,
                'VENDOR' => $param['VENDOR'],
                'VENDOR_BANK' => $param['VENDOR_BANK'],
                'V_BANK_ACC' => $param['V_BANK_ACC'],
                'INV_NUM' => $param['INV_NUM'],
                'INV_VALUE' =>$INV_VALUE,
                'DDOWN_AMT' => $param['DDOWN_AMT'],
                'DISKONTO'=>$DISKONTO
            ];

            $dt_wd = [
                'AMOUNT' => $AMOUNT,
                'CREATED_BY' => $USERNAME,
                'CON_COUNTER' => $lastcon->COUNTER
            ];

            
            if($param['ID'] == NULL){
                $result = $this->db->set('CREATED_AT', "SYSDATE", false)
                ->set("INV_DATE","TO_DATE('" . $param['INV_DATE'] . "','yyyy-mm-dd')", false)
                ->set("DUE_DATE","TO_DATE('" . $param['DUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set($dt_invoice)
                ->insert('FUNDS_WDDETAIL_SCFAP');
            } else {
                $this->db->where('ID',$param['ID']);
                $result = $this->db->set('CREATED_AT', "SYSDATE", false)
                ->set("INV_DATE","TO_DATE('" . $param['INV_DATE'] . "','yyyy-mm-dd')", false)
                ->set("DUE_DATE","TO_DATE('" . $param['DUE_DATE'] . "','yyyy-mm-dd')", false)
                ->set($dt_invoice)
                ->update('FUNDS_WDDETAIL_SCFAP');
            }

            //update amount in funds_withdraw
            $result1 = $this->db->set('CREATED_AT', 'SYSDATE', false)
            ->set($dt_wd)->where("BATCHID = '".$param['BATCHID']."'")->update('FUNDS_WITHDRAW');
            //
            
                if ($result && $result1) {
                    $this->db->trans_commit();
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => "success"
                    ];
                }
            }catch(Exception $ex) {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => $ex->getMessage()
                ];
            } 
           
            $this->db->close();
            return $return;
    }

    public function GetDataWDAP($param) {
        if ($param['LOOK']) {
            $q = "SELECT FWD.*, TO_CHAR(FWD.VALUE_DATE,'yyyy-MM-dd') as VAL_DATE FROM FUNDS_WITHDRAW FWD WHERE VALUE_DATE = TO_DATE(?, 'yyyy-mm-dd') AND ID = ? AND SUB_WD_TYPE = ? AND UUID = ?" ;
            $result = $this->db->query($q, array($param['VALUE_DATE'], $param['ID'], $param['WD_TYPE'], $param['UUID']))->row();
        }
        else {
            if($param['PART']){
        $q = "SELECT * FROM FUNDS_WITHDRAW FWD WHERE VALUE_DATE = TO_DATE(?, 'yyyy-mm-dd') AND UUID = ? AND WD_TYPE = ?" ;
        $result = $this->db->query($q, array($param['VALUE_DATE'], $param['UUID'], $param['WD_TYPE']))->row();
            }
            else {
                $q = "SELECT TO_CHAR(VALUE_DATE,'yyyy-mm-dd') as VAL_DATE, FWD.*, FWA.AMOUNT_LIMIT 
                        FROM FUNDS_WITHDRAW FWD 
                LEFT JOIN FUNDS_DETAIL_WA FWA ON FWD.UUID = FWA.UUID AND FWD.SUB_WD_TYPE = FWA.SUB_CREDIT_TYPE
                WHERE FWD.UUID = ? AND FWD.SUB_WD_TYPE = ? ORDER BY AMOUNT_BALANCE NULLS LAST, FWD.ID DESC FETCH FIRST 1 ROWS ONLY" ;
                $result = $this->db->query($q, array($param['UUID'], $param['WD_TYPE']))->row();
                // var_dump($this->db->last_query()); exit;
            }
           
        }
        // var_dump($this->db->last_query()); exit;
        $tmp = (array) $result;
        if(empty($tmp)) {
            $result['RATE'] = 0;
            $result['INTEREST'] = 0;
            $result['PROVISION'] = 0;
            $result['DATA_EXIST'] = 0;
        } else {
            $result->DATA_EXIST = 1;
        }
        $this->db->close();
        return $result;
    }

    public function GetDataWDAPDet($param) {
        $uuid = $param['UUID'];
        $sql = "SELECT WDAP.*, SP.FCNAME as VENDORNAME FROM FUNDS_WDDETAIL_SCFAP WDAP 
                        LEFT JOIN SUPPLIER SP ON WDAP.VENDOR = SP.ID
                        WHERE WDAP.BATCHID = ? ";
        $result = $this->db->query($sql, array($uuid))->result();
        // var_dump($this->db->last_query()); exit;
        $this->db->close();
        return $result;
    }

    public function getVendorAP ($param) {
        $vendorID = $param['ID'] ;
        $result = $this->db->select('FCNAME, BANKNAME, BANKACCOUNT')->from('SUPPLIER')->where('ID', $vendorID)->get()->result();
        $this->db->close();
        return $result;
    }

    public function showApprovalContract(){
        $q = "SELECT FM.UUID,
                   C.COMPANYCODE,
                   CASE 
                        WHEN FM.SUB_CREDIT_TYPE = 'KI' THEN FKI.CONTRACT_NUMBER
                        WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FWA.CONTRACT_NUMBER
                        ELSE FRK.CONTRACT_NUMBER
                    END AS CONTRACT_NUMBER,
                   FM.PK_NUMBER,
                   FM.CREDIT_TYPE,
                   FM.SUB_CREDIT_TYPE,
                   FM.CREATED_AT,
                   -- FM.STATUS,
                   FM.IS_ACC
              FROM FUNDS_MASTER FM
                   LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                   LEFT JOIN BANK B ON B.FCCODE = FM.BANK 
                   LEFT JOIN FUNDS_DETAIL_RK FRK ON FRK.UUID = FM.UUID 
                   LEFT JOIN (
                    SELECT uuid, MAX (contract_number) AS contract_number
                        FROM funds_detail_ki_tranche
                    GROUP BY uuid
                    ) FKI ON FKI.UUID = FM.UUID
                    LEFT JOIN (
                    SELECT uuid, MAX (contract_number) AS contract_number
                        FROM funds_detail_wa
                    GROUP BY uuid
                    ) FWA ON FWA.UUID = FM.UUID
                   WHERE FM.IS_ACC <> '1'  ORDER BY FM.CREATED_AT DESC";
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function showApprovalWithdrawal($param) {
        // $q = "SELECT FW.ID,FM.UUID,
        //            C.COMPANYCODE,
        //            FM.CONTRACT_NUMBER,
        //            FM.PK_NUMBER,
        //            FM.CREDIT_TYPE,
        //            FM.SUB_CREDIT_TYPE,
        //            -- FDW.SUB_CREDIT_TYPE as SUB_CREDIT_TYPE_1,
        //            -- CASE WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
        //            -- WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
        //            -- WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
        //            -- ELSE 0 END AS AMOUNT_LIMIT,
        //            FW.AMOUNT_BALANCE,
        //            FW.CREATED_AT,
        //            FW.STATUS
        //       FROM FUNDS_WITHDRAW FW
        //            LEFT JOIN FUNDS_MASTER FM ON (FW.UUID = FM.UUID AND FW.WD_TYPE = FM.SUB_CREDIT_TYPE)
        //            LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
        //            LEFT JOIN BANK B ON B.FCCODE = FM.BANK
        //            -- LEFT JOIN FUNDS_DETAIL_WA FDW ON FDW.UUID = FM.UUID
        //            -- LEFT JOIN FUNDS_DETAIL_RK FDR ON FDR.UUID = FM.UUID 
        //            ORDER BY FW.CREATED_AT DESC";
                   //         -- CASE WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
                   // -- WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
                   // -- WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
                   // -- ELSE 0 END AS AMOUNT_LIMIT,
        // $q  = "SELECT FW.ID,
        //         FW.UUID,
        //         FW.WD_TYPE AS SUB_CREDIT_TYPE_1,
        //            FM.CONTRACT_NUMBER,
        //         C.COMPANYCODE,
        //            FM.PK_NUMBER,
        //            FM.CREDIT_TYPE,
        //            FM.SUB_CREDIT_TYPE,
        //         CASE WHEN FW.WD_TYPE = 'KMK_SCF_AP' THEN FDW.ID
        //         WHEN FW.WD_TYPE = 'KMK_SCF_AR' THEN FDW.ID
        //         ELSE 0 END AS IDDETAPAR, 
        //            FW.AMOUNT_BALANCE,
        //            FW.CREATED_AT,
        //         FW.BATCHID,
        //            FW.STATUS
        //       FROM FUNDS_WITHDRAW FW
        //         LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FW.UUID
        //         LEFT JOIN FUNDS_DETAIL_WA FDW ON FDW.UUID = FW.UUID AND FDW.SUB_CREDIT_TYPE = FW.WD_TYPE
        //            LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
        //         WHERE FW.STATUS IN ('0','2')
        //         ORDER BY FW.CREATED_AT DESC" ; 
        $q  =" SELECT DISTINCT FW.ID,
        FW.UUID,
        FW.WD_TYPE AS SUB_CREDIT_TYPE_1,
        FW.SUB_WD_TYPE AS SUB_SCT_1,
        CASE 
            WHEN FW.WD_TYPE = 'RK' THEN FDR.CONTRACT_NUMBER
            WHEN FW.WD_TYPE = 'TL' THEN FDR.CONTRACT_NUMBER
            WHEN FW.WD_TYPE = 'BD' THEN FDR.CONTRACT_NUMBER
            WHEN FW.WD_TYPE = 'WA' THEN FDW.CONTRACT_NUMBER
            WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AP' THEN FDW.CONTRACT_NUMBER
            WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AR' THEN FDW.CONTRACT_NUMBER
            ELSE FM.CONTRACT_NUMBER
            END
        AS CONTRACT_NUMBER,
        -- FM.CONTRACT_NUMBER,
        C.COMPANYCODE,
        FM.PK_NUMBER,
        FM.CREDIT_TYPE,
        FM.SUB_CREDIT_TYPE,
        CASE WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AP' THEN FDW.ID
        WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AR' THEN FDW.ID
        WHEN FW.WD_TYPE = 'WA' THEN FDW.ID
        ELSE 0 END AS IDDETAPAR, 
        -- CASE WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
        -- WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
        -- WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
        -- ELSE 0 END AS AMOUNT_LIMIT,
        FW.AMOUNT_BALANCE,
        FW.CREATED_AT,
        FW.BATCHID,
        FW.STATUS
    FROM FUNDS_WITHDRAW FW
        LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FW.UUID
        LEFT JOIN (SELECT * FROM FUNDS_DETAIL_WA WHERE ISACTIVE = 1) FDW ON FDW.UUID = FW.UUID AND (FDW.SUB_CREDIT_TYPE = 
            (
                CASE
                    WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AP' THEN 'KMK_SCF_AP'
                    WHEN FW.SUB_WD_TYPE = 'KMK_SCF_AR' THEN 'KMK_SCF_AR' 
                    WHEN FW.WD_TYPE = 'WA' THEN 'WA'
                    ELSE FW.WD_TYPE
                    END
            ) 
        )
        LEFT JOIN (SELECT * FROM FUNDS_DETAIL_RK WHERE ISACTIVE = 1) FDR ON FDR.UUID = FW.UUID
        LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
        WHERE FW.STATUS in('0','2')
        ORDER BY FW.CREATED_AT DESC" ;
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function showApprovalWithdrawalKI($param) {
        $q  = "SELECT FWK.CTRWD AS ID,
                     FWK.UUID,
                     FKI.CONTRACT_NUMBER,
                     C.COMPANYCODE,
                     FM.PK_NUMBER,
                     FM.CREDIT_TYPE,
                     FM.SUB_CREDIT_TYPE,
                     FWK.DRAWDOWN_VALUE,
                     FWK.DRAWDOWN,
                     FWK.CREATED_AT,
                     FWK.STATUS
                FROM FUNDS_WD_KI FWK
                     LEFT JOIN FUNDS_MASTER FM ON (FM.UUID = FWK.UUID)
                     LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FKI ON FM.UUID = FKI.UUID AND FWK.TRANCHE_NUMBER = FKI.TRANCHE_NUMBER AND FKI.ISACTIVE = '1'
                     LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
               WHERE FWK.STATUS IN ('0', '2')
            ORDER BY FWK.CREATED_AT DESC" ; 
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function SaveWDAPAR($param, $location) {
        $WD_TYPE        = $param['WD_TYPE'];
        $AMOUNT         = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT']));
        $RATE           = intval(preg_replace("/[^\d\.\-]/","",$param['RATE']));                                           //rate input is int
        // ini_set('display_errors','On');
        // $q   = "SELECT FW.*, FA.AMOUNT_LIMIT FROM FUNDS_WITHDRAW FW LEFT JOIN FUNDS_DETAIL_WA FA ON FW.UUID = FW.UUID AND FW.SUB_WD_TYPE = FA.SUB_CREDIT_TYPE WHERE FW.UUID = '".$param['UUID']."' AND FW.SUB_WD_TYPE='".$param['WD_TYPE']."'ORDER BY FW.ID DESC FETCH FIRST 1 ROWS ONLY";
        $q  = "SELECT FA.*,
                        FW.AMOUNT_BALANCE,
                        FW.SUB_BALANCE
                FROM FUNDS_DETAIL_WA FA
                        LEFT JOIN (
                            SELECT * FROM FUNDS_WITHDRAW WHERE SUB_WD_TYPE = '".$param['WD_TYPE']."'
                        ) FW
                        ON FA.UUID = FW.UUID AND FW.SUB_WD_TYPE = FA.SUB_CREDIT_TYPE 
                WHERE     FA.UUID = '".$param['UUID']."' AND FA.SUB_CREDIT_TYPE = '".$param['WD_TYPE']."'
                ORDER BY AMOUNT_BALANCE NULLS LAST, SUB_BALANCE NULLS LAST, FW.ID DESC FETCH FIRST 1 ROWS ONLY" ;
        $getBalance = $this->db->query($q)->row();
        $q1 = "SELECT FA.UUID,
                FA.ID,
                FA.AMOUNT_LIMIT,
                FWD.AMOUNT_BALANCE
                FROM FUNDS_DETAIL_WA FA
                        LEFT JOIN (  SELECT UUID, AMOUNT_BALANCE, WD_TYPE
                                    FROM FUNDS_WITHDRAW
                                    WHERE WD_TYPE = 'WA'
                                ORDER BY ID DESC) FWD
                        ON FWD.UUID = FA.UUID
                WHERE SUB_CREDIT_TYPE = 'WA' AND FA.UUID = ? FETCH FIRST 1 ROWS ONLY" ;
        $getParBalance = $this->db->query($q1, $param['UUID'])->row();
        $amountLimitParent = intval($getParBalance->AMOUNT_LIMIT) * $RATE ;
        $amountBalanceParent = intval($getParBalance->AMOUNT_BALANCE) * $RATE ;
        // var_dump($amountBalanceParent); exit;
        // var_dump($getBalance); exit;
        try {
            $this->db->trans_begin();
            $balance        = intval($getBalance->SUB_BALANCE) ;
            $limit          = intval($getBalance->AMOUNT_LIMIT);
            // var_dump($this->db->last_query());exit;
        
            // var_dump($amountLimitParent, $amountBalanceParent, $balance, $limit, $AMOUNT);exit;
                
            if ($getBalance->STATUS == '0' || $getBalance->STATUS == '2') {
                $msg = "Data Sebelumnya Belum DiApprove, Silakan Approve Terlebih Dahulu";
                throw new Exception($msg);
                
            }
            if($balance != 0){
                if(($AMOUNT > $balance) || ($AMOUNT > ($amountBalanceParent ? $amountBalanceParent : $amountLimitParent)) ){
                    $msg = "Amount Exceed"; 
                    var_dump($msg); exit;
                    throw new Exception($msg);
                }else{
                    $AMOUNT_LIMIT = $balance;    
                }
            }
            else {
                if($AMOUNT > $limit || ($AMOUNT > ($amountBalanceParent ? $amountBalanceParent : $amountLimitParent))){
                    $msg = "Amount Exceed";
                    throw new Exception($msg);
                }
            }
            //ASSIGN DATA
            $dt = [
                'WD_TYPE'           => 'WA',
                'SUB_WD_TYPE'       => $WD_TYPE,
                'STATUS'            => 0,
                'UUID'              => $param['UUID'],
                'RATE'              => $RATE,
                // 'AMOUNT_CONVERT'    => $AMOUNT * $RATE,
                'PROVISION'         => $param['PROVISION'],
                'INTEREST'          => $param['INTEREST'],
                'CREATED_BY'        => $param['USERNAME'],
                'CONTRACT_NUMBER'   => $param['CONTRACT_NUMBER']
            ];
            //CHECK IF DATA ALREADY EXIST 
            $check =  $this->db->select('*')->from('FUNDS_WITHDRAW')->where("VALUE_DATE = TO_DATE('" . $param['VALUE_DATE'] . "', 'yyyy-mm-dd') AND UUID = '". $param['UUID'] ."' AND BATCHID = '". $param['BATCHID'] ."'" )->get()->result();
            // var_dump($this->db->last_query());exit;
            // var_dump($check); exit;
            if($check == null) {
                $dt['AMOUNT'] = 0 ;
                $result = $this->db->set("VALUE_DATE","TO_DATE('" . $param['VALUE_DATE'] . "','yyyy-mm-dd')", false)
                                    ->set('CREATED_AT', "SYSDATE", false) ;
                //CREATE A ID FOR THIS RECORD
                $dt['BATCHID'] = $this->uuid->v4();
                $result = $result->set($dt)->insert('FUNDS_WITHDRAW');
                // var_dump($result); exit;
            } else {
                $result = $this->db->get_where('FUNDS_WITHDRAW', ['WD_TYPE' => $param['WD_TYPE']])->row();
                // var_dump($result); exit;
                $result = $this->db->set('CREATED_AT', 'SYSDATE', false);
                $result = $result->set($dt)
                            ->where(['BATCHID' => $param['BATCHID']])
                            ->update('FUNDS_WITHDRAW');
            }
            // var_dump($result); exit;
            if ($result) {
                $this->db->trans_commit();
                $msg = "Data has been Successfully Saved !!";
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $msg,
                    'IDS' => $dt['UUID'],
                    'BATCHID'=> $dt['BATCHID']
                    // 'IDS' => $dt['UUID']
                ];
            } else {
                $this->db->trans_rollback(); 
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

    public function DeleteAR ($param) {
        // var_dump($param); exit;
        try {
            $this->db->trans_begin();
            $AMOUNT = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT']));
            $getUUID = $this->db->get_where('FUNDS_WDDETAIL_SCFAR', ['ID' => $param['ID']])->row();
            $uuid = $getUUID->UUID;
            $getFWD = $this->db->get_where('FUNDS_WITHDRAW',"UUID = '".$uuid."' AND SUB_WD_TYPE ='KMK_SCF_AR' AND STATUS = 0")->row();
            // var_dump($this->db->last_query()); exit;
            $getAmount = $getFWD->AMOUNT;
            $getAmount -= $AMOUNT;
            $dt = [
                'AMOUNT'     => $getAmount,
                'CREATED_BY' => $param['USERNAME']
            ];
            // var_dump($dt);
            $result = $this->db->set('CREATED_AT','SYSDATE',false);
            $result = $this->db->set($dt)->where("UUID = '".$uuid."'  AND SUB_WD_TYPE='KMK_SCF_AR' AND STATUS = 0")->update('FUNDS_WITHDRAW');
            // var_dump($this->db->last_query()); exit;
            if($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "success"
                ];
            }
        } catch(Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function DeleteAP ($param) {
        try {
            $this->db->trans_begin();
            $AMOUNT = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT']));
            $getUUID = $this->db->get_where('FUNDS_WDDETAIL_SCFAP', ['ID' => $param['ID']])->row();
            $uuid = $getUUID->UUID;
            $getFWD = $this->db->get_where('FUNDS_WITHDRAW',"UUID = '".$uuid."' AND BATCHID = '".$param['BATCHID']."' AND SUB_WD_TYPE ='KMK_SCF_AP'")->row();
            $getAmount = $getFWD->AMOUNT;
            $getAmount -= $AMOUNT;
            // var_dump($getAmount); exit;
            $dt = [
                'AMOUNT'     => $getAmount,
                'CREATED_BY' => $param['USERNAME']
            ];
            // var_dump($dt);
            $result = $this->db->set('CREATED_AT','SYSDATE',false);
            $result = $this->db->set($dt)->where("UUID = '".$uuid."' AND BATCHID = '".$param['BATCHID']."' AND SUB_WD_TYPE='KMK_SCF_AP'")->update('FUNDS_WITHDRAW');
            // var_dump($this->db->last_query()); exit;
            if($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "success"
                ];
            }
        } catch(Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function DeleteWDNDK($param) {
        // var_dump($param); exit;
        try {
            $this->db->trans_begin();
            $AMOUNT = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT']));
            $getUUID = $this->db->get_where('WD_FUNDS_NONDISKONTO', ['ID' => $param['ID']])->row();
            $uuid = $getUUID->UUID;
            $getFWD = $this->db->get_where('FUNDS_WITHDRAW',"UUID = '".$uuid."' AND VALUE_DATE = TO_DATE('".$param['VALUE_DATE']."')")->row();
            $getAmount = $getFWD->AMOUNT;
            $getAmount -= $AMOUNT;
            $dt = [
                'AMOUNT'     => $getAmount,
                'CREATED_BY' => $param['USERNAME']
            ];

            $result = $this->db->set('CREATED_AT','SYSDATE',false);
            $result = $this->db->set($dt)->where("UUID = '".$uuid."' AND VALUE_DATE = TO_DATE('".$param['VALUE_DATE']."')")->update('FUNDS_WITHDRAW');

            if($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "success"
                ];
            }
        } catch(Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => false,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    function getHistoryDoc($param){
        $q = "SELECT  FR.ID,
               L.CREDIT_TYPE,
               L.SUB_CREDIT_TYPE,
               BU.FCNAME AS BUFCNAME,
               B.BANKACCOUNT || ' - ' || B.FCNAME || ' - ' || B.CURRENCY AS GETBANK,
               NVL (FR.AMOUNT_LIMIT, 0) AS AMOUNT_LIMIT,
               FR.PROVISI,
               TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE_DETAIL,
               FR.FEE,
               FR.INTEREST,
               FR.UPFRONT_FEE,
               FR.ANNUAL_FEE,
               FR.RATE,
               FR.CURRENCY,
               FR.TENOR,
               TO_CHAR (FR.MATURITY_DATE, 'yyyy-mm-dd') AS MATURITY_DATE,
               FR.LOAN_ACCOUNT_NUMBER,
               FR.INTEREST_PERIOD_FROM,
               FR.INTEREST_PERIOD_TO,
               FR.INTEREST_PERIOD_FROM || ' - ' || FR.INTEREST_PERIOD_TO
                  AS INTEREST_PERIOD,
               FR.INSTALLMENT_PERIOD,
               FR.IS_ADDENDUM
          FROM FUNDS_DETAIL_RK FR
               LEFT JOIN FUNDS_MASTER L ON FR.UUID = L.UUID
               LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT
               LEFT JOIN BANK B ON B.FCCODE = L.BANK
         WHERE L.COMPANY = ? AND FR.UUID = ? ORDER BY FR.ID ASC";
        // var_dump($this->db->last_query());exit();
        return $this->db->query($q,[$param['COMPANY'], $param['UUID']])->result();
        $this->db->close();
    }

    public function getHistoryDocWA($param){
        $q = "SELECT L.CREDIT_TYPE,
                 L.SUB_CREDIT_TYPE,
                 BU.FCNAME AS BUFCNAME,
                 B.BANKACCOUNT || ' - ' || B.FCNAME || ' - ' || B.CURRENCY AS GETBANK,
                 FR.*,
                 FR.INTEREST_PERIOD_FROM || ' - ' || FR.INTEREST_PERIOD_TO
                  AS INTEREST_PERIOD
            FROM FUNDS_DETAIL_WA FR
                 LEFT JOIN FUNDS_MASTER L ON FR.UUID = L.UUID
                 LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT
                 LEFT JOIN BANK B ON B.FCCODE = L.BANK
           WHERE L.COMPANY = ? AND L.UUID = ?
           AND FR.POS = '0'";
        // var_dump($this->db->last_query());exit();
        return $this->db->query($q,[$param['COMPANY'], $param['UUID']])->result();
        $this->db->close();
    }

    public function getHistoryDocKI($param){
        $q = "SELECT L.CREDIT_TYPE,
        L.UUID,
               L.SUB_CREDIT_TYPE,
               BU.FCNAME AS BUFCNAME,
               TO_CHAR (FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE_DETAIL,
               B.FCNAME AS GETBANK,
               FR.TOBANK,
               FR.FEE,
               FR.PROVISI,
               FR.ANNUAL_FEE,
               FR.UPFRONT_FEE,
               FR.COMMIT_FEE,
               FR.COUNTER,
               NVL (FR.ADM_FEE, 0) AS ADM_FEE,
               NVL (FR.AGENCY_FEE, 0) AS AGENCY_FEE,
               FR.INTEREST,
               FR.CURRENCY,
               FR.TENOR,
               TO_CHAR (FR.MATURITY_DATE, 'yyyy-mm-dd') AS MATURITY_DATE,
               FR.LOAN_ACCOUNT_NUMBER,
               FR.INTEREST_PAYMENT_SCHEDULE,
               FR.INTEREST_PAYMENT_SCHEDULE_DATE,
               FR.INSTALLMENT_PERIOD,
               FR.INSTALLMENT_TYPE,
               FR.ADDENDUM_DATE,
               FR.ADD_REMARK,
               F.FILENAME
          FROM FUNDS_DETAIL_KI FR
               LEFT JOIN FUNDS_MASTER L ON FR.UUID = L.UUID
               LEFT JOIN BUSINESSUNIT BU ON BU.ID = L.BUNIT
               LEFT JOIN (SELECT FILENAME, UUID, ADDENDUM_NUM FROM FUNDS_FILE WHERE TIPE = 'ADDENDUM_DOCUMENT' OR TIPE = 'AKTA_PK')F ON FR.UUID = F.UUID AND FR.COUNTER = F.ADDENDUM_NUM 
               LEFT JOIN SUPPLIER B ON B.ID = L.BANK AND B.SUPPLIER_TYPE = 'BANK'
         WHERE L.COMPANY = ? AND L.UUID = ?
         ORDER BY COUNTER ASC";
        $result = $this->db->query($q,[$param['COMPANY'], $param['UUID']])->result();
        // var_dump($this->db->last_query());exit();
        return $result ;
        $this->db->close();
    }

    public function GetDataWDAPARbyID($param) {
        $q = "SELECT FW.ID,
                   FM.UUID,
                   C.COMPANYCODE,
                   FM.COMPANY,
                   B.ID AS BUID, B.FCNAME AS BUFCNAME,
                   FDW.CONTRACT_NUMBER,
                   FDW.CURRENCY,
                   FM.PK_NUMBER,
                   FM.CREDIT_TYPE,
                   FM.SUB_CREDIT_TYPE,
                   FDW.AMOUNT_LIMIT,
                   FW.AMOUNT_BALANCE,
                   FW.AMOUNT,
                   FW.VALUE_DATE,
                   FW.DUEDATE,
                   FW.RATE,
                    FW.PROVISION,
                    FW.INTEREST,
                   FW.AMOUNT_CONVERT,
                   FW.STATUS,
                   FW.WD_TYPE,
                   FW.SUB_WD_TYPE,
                   FW.BATCHID,
                   TO_CHAR(FW.VALUE_DATE,'yyyy-MM-dd') as VAL_DATE,
                   (SELECT CASE 
            WHEN FWD.AMOUNT_BALANCE IS NULL THEN FDW.AMOUNT_LIMIT
            ELSE FWD.AMOUNT_BALANCE
         END
            AS AMOUNT_LIMIT
    FROM FUNDS_DETAIL_WA FDW
         LEFT JOIN FUNDS_WITHDRAW FWD
            ON     FWD.UUID = FDW.UUID
               AND FWD.WD_TYPE = FDW.SUB_CREDIT_TYPE
   WHERE     FDW.UUID = '".$param['UUID']."'
         AND FDW.SUB_CREDIT_TYPE = 'WA' ORDER BY FWD.CREATED_AT DESC
FETCH FIRST 1 ROWS ONLY) AS LIMIT_WA,
            (SELECT CURRENCY FROM FUNDS_DETAIL_WA WHERE UUID = '".$param['UUID']."' AND SUB_CREDIT_TYPE = 'WA') AS CURR_WA
              FROM FUNDS_WITHDRAW FW
                   LEFT JOIN FUNDS_MASTER FM ON (FW.UUID = FM.UUID OR FW.WD_TYPE = FM.SUB_CREDIT_TYPE)
                   LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                   LEFT JOIN BUSINESSUNIT B ON B.ID = FM.BUNIT
                   LEFT JOIN FUNDS_DETAIL_WA FDW ON FDW.UUID = FM.UUID
                   LEFT JOIN FUNDS_DETAIL_RK FDR ON FDR.UUID = FM.UUID
             WHERE FW.UUID = '".$param['UUID']."' AND FW.ID = '".$param['IDDET']."' AND FDW.ID = '".$param['IDDETAP']."' AND FW.SUB_WD_TYPE='".$param['WD_TYPE']."'";
        $result = $this->db->query($q)->row();
        // var_dump($this->db->last_query()); exit;
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }
    public function GetDataWDNDKbyID($param) {
        $q = "SELECT FW.ID,
                   FM.UUID,
                   C.COMPANYCODE,
                   FM.COMPANY,
                   B.ID AS BUID, B.FCNAME AS BUFCNAME,
                   FM.CONTRACT_NUMBER,
                   FM.PK_NUMBER,
                   FM.CREDIT_TYPE,
                   FM.SUB_CREDIT_TYPE,
                   CASE WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
                   WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
                   WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
                   WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.AMOUNT_LIMIT
                   ELSE 0 END AS AMOUNT_LIMIT,
                   FW.AMOUNT_BALANCE,
                   FW.AMOUNT,
                   TO_CHAR(FW.VALUE_DATE,'yyyy-MM-dd') as VALUE_DATE,
                   FW.DUEDATE,
                   FW.RATE,
                   FW.AMOUNT_CONVERT,
                   FW.STATUS,
                   FDR.INTEREST,
                   FNDK.SCF_NDK_TYPE
              FROM FUNDS_WITHDRAW FW
                   LEFT JOIN FUNDS_MASTER FM ON (FW.UUID = FM.UUID)
                   LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                   LEFT JOIN BANK B ON B.FCCODE = FM.BANK
                   LEFT JOIN BUSINESSUNIT B ON B.ID = FM.BUNIT
                   LEFT JOIN FUNDS_DETAIL_WA FDW ON FDW.UUID = FM.UUID
                   LEFT JOIN FUNDS_DETAIL_RK FDR ON FDR.UUID = FM.UUID
                   LEFT JOIN WD_FUNDS_NONDISKONTO FNDK ON FNDK.UUID = FW.UUID 
             WHERE FW.UUID = '".$param['UUID']."' AND FW.ID = '".$param['IDDET']."' ORDER BY FW.AMOUNT_BALANCE NULLS LAST, FW.ID DESC FETCH FIRST 1 ROWS ONLY ";
        $result = $this->db->query($q)->row();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }
    
    public function GetDataWDNDK($param) {

        $q = "SELECT UUID, ID ,TO_CHAR(VALUE_DATE,'yyyy-mm-dd') AS VALUE_DATE, AMOUNT_BALANCE, AMOUNT FROM FUNDS_WITHDRAW WHERE UUID = ? AND WD_TYPE = ? ORDER BY AMOUNT_BALANCE NULLS LAST, ID DESC FETCH FIRST 1 ROWS ONLY ";
            $result = $this->db->query($q, array($param['UUID'], $param['WD_TYPE']))->row();
        // var_dump($this->db->last_query());exit;
        
        
        $tmp = (array) $result;
        if(empty($tmp)) {
            $result['DATA_EXIST'] = 0;
        } else {
            $result->DATA_EXIST = 1;
        }
        $this->db->close();
        return $result;
    }

    function ShowDataPaymentRequest($param){
        // var_dump($param); exit;

        $q = "SELECT * FROM (SELECT FM.UUID,
               C.COMPANYCODE,
               C.ID AS COMPANY,
               CASE
          WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.CONTRACT_NUMBER
          WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.CONTRACT_NUMBER
          WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.CONTRACT_NUMBER
          WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.CONTRACT_NUMBER
          WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.CONTRACT_NUMBER
          WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.CONTRACT_NUMBER
          ELSE FDK.CONTRACT_NUMBER
       END
          AS CONTRACT_NUMBER,
               FM.PK_NUMBER,
               FM.CREDIT_TYPE,
               -- FM.SUB_CREDIT_TYPE,
               CASE WHEN FDW.SUB_CREDIT_TYPE IS NOT NULL THEN FDW.SUB_CREDIT_TYPE ELSE FM.SUB_CREDIT_TYPE END AS SUB_CREDIT_TYPE,
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
          AS INTEREST,
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
                  WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.AMOUNT_LIMIT
                  WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.AMOUNT_LIMIT
                  WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.AMOUNT_LIMIT
                  WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.AMOUNT_LIMIT
                  WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.AMOUNT_LIMIT
                  WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.AMOUNT_LIMIT
                  ELSE FDK.AMOUNT_LIMIT
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
                  AS TOTALWD
          FROM FUNDS_MASTER FM
               LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
               LEFT JOIN BANK B ON B.FCCODE = FM.BANK
               LEFT JOIN
               (SELECT FA.UUID,
                                FA.SUB_CREDIT_TYPE,
                                TO_CHAR(FA.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
                                FA.INTEREST,
                                FA.CURRENCY,
                                FA.AMOUNT_LIMIT,
                                FA.CONTRACT_NUMBER,
                                FW.TOTALWD
                  FROM FUNDS_DETAIL_WA FA
                       LEFT JOIN
                       (SELECT * FROM (SELECT UUID,
                               SUM(AMOUNT) TOTALWD,
                               WD_TYPE
                          FROM FUNDS_WITHDRAW
                         WHERE STATUS = '1' GROUP BY UUID,WD_TYPE)) FW
                          ON (    FA.UUID = FW.UUID AND FW.WD_TYPE = FA.SUB_CREDIT_TYPE)
                 WHERE     FA.IS_ACC = '1'
                       AND FA.ISACTIVE = '1' AND FW.TOTALWD > 0) FDW
                  ON FDW.UUID = FM.UUID
               LEFT JOIN
               (SELECT DISTINCT FR.UUID,
                                FR.SUB_CREDIT_TYPE,
                                TO_CHAR(FR.DOCDATE,'yyyy-mm-dd') AS DOCDATE,
                                FR.INTEREST,
                                FR.CURRENCY,
                                FR.AMOUNT_LIMIT,
                                FR.CONTRACT_NUMBER,
                                FW.TOTALWD
                  FROM FUNDS_DETAIL_RK FR
                       LEFT JOIN
                       (SELECT UUID,
                               SUM(AMOUNT) TOTALWD,
                               WD_TYPE
                          FROM FUNDS_WITHDRAW
                         WHERE STATUS = '1' GROUP BY UUID,WD_TYPE) FW
                          ON (    FR.UUID = FW.UUID
                              AND FW.WD_TYPE = FR.SUB_CREDIT_TYPE)
                 WHERE     FR.IS_ACC = '1'
                       AND FR.ISACTIVE = '1' AND FW.TOTALWD > 0) FDR
                  ON FDR.UUID = FM.UUID
               LEFT JOIN
               ( SELECT FR.UUID,
                       FM.CREDIT_TYPE,
                       TO_CHAR(FR.DOCDATE, 'yyyy-mm-dd') AS DOCDATE,
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
                          WHERE ISACTIVE = '1' AND IS_ACC = '1' AND IS_COMPLETE IS NULL) FDR
                          ON FR.UUID = FDR.UUID
                       LEFT JOIN
                       (  SELECT UUID,
                                 SUM (DDOWN_AMT) TOTALWD,
                                 TRANCHE_NUMBER
                            FROM FUNDS_WD_KI_TRANCHE
                           WHERE STATUS = '1'
                        GROUP BY UUID, TRANCHE_NUMBER) FW
                          ON (    FR.UUID = FW.UUID AND FDR.TRANCHE_NUMBER = FW.TRANCHE_NUMBER)
                        LEFT JOIN 
                        (SELECT UUID, CREDIT_TYPE FROM FUNDS_MASTER WHERE ISACTIVE = '1' AND IS_ACC = '1')FM ON FM.UUID = FR.UUID 
                 WHERE     FR.IS_ACC = '1'
                       AND FR.ISACTIVE = '1'
                       AND FW.TOTALWD > 0) FDK
                  ON FDK.UUID = FM.UUID
         WHERE FM.IS_ACC = '1' AND FM.ISACTIVE = '1') WHERE TOTALWD > 0 ";

        if($param['COMPANY'] != '0') {
            $q .= "AND COMPANY = '".$param['COMPANY']."'" ;
        }
        if($param['CREDIT_TYPE'] != '0') {
            $q .= "AND CREDIT_TYPE = '".$param['CREDIT_TYPE']."'" ;
        }
        

        // var_dump($q);
        $result = $this->db->query($q)->result();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    public function getDataLastBalanceRK ($param) {
        $q = "SELECT ID, UUID, WD_TYPE, AMOUNT_BALANCE, STATUS FROM
                FUNDS_WITHDRAW WHERE UUID = ?
                    ORDER BY AMOUNT_BALANCE NULLS LAST, ID DESC
                        FETCH FIRST 1 ROWS ONLY";
        $result = $this->db->query($q, array($param['UUID']))->row();
        $this->db->close();
        return $result ;
    }

    public function getBankKI($param) {
        $q = "SELECT DISTINCT BKI.ID, BKI.UUID, BKI.BANKNAME, BKI.PORTION, B.FCNAME, BKI.CURRENCY FROM BANK_KI BKI LEFT JOIN SUPPLIER B ON BKI.BANKNAME = B.ID  WHERE UUID = '".$param['UUID']."' AND COUNTER = (SELECT MAX(COUNTER) FROM BANK_KI WHERE UUID = '".$param['UUID']."') " ;
        $result = $this->db->query($q, array($param['UUID']))->result();
        // var_dump($this->db->last_query()); exit;
        $this->db->close();
        return $result ;
    }

    public function getTrancheNumberList($param) {
        $getq = "SELECT TRANCHE_NUMBER FROM FUNDS_DETAIL_KI_TRANCHE WHERE UUID = '".$param['UUID']."' AND ISACTIVE = 1" ;
        $get = $this->db->query($getq)->result();
        // var_dump($this->db->last_query()); exit;
        $this->db->close();
        return $get ;
    }

    public function saveBankKI ($param) {
        $this->db->trans_begin();
        // var_dump($param); exit;
        $check = false ;
        $total_amount = 0;
        $idx = 1 ;
        $lengthdata = sizeof($param['BANK_SYNDICATION']);
        $totalperc = 0.0;
        foreach($param['BANK_SYNDICATION'] as $key => $value) {
            $portion = intval(preg_replace("/[^\d\.\-]/","",$param['BANK_PORTION'][$key]));
            $total_amount += $portion ;
        }
        foreach($param['BANK_SYNDICATION'] as $key => $value) {
                $portion = intval(preg_replace("/[^\d\.\-]/","",$param['BANK_PORTION'][$key]));
                if($idx == $lengthdata) {
                    $percentage = 100.0 - $totalperc ;
                }
                else {
                    $percentage = round($portion / $total_amount * 100, 2);
                    $totalperc += $percentage ;
                }
                $dt = [
                    'UUID' => $param['UUID'],
                    'BANKNAME' => $value,
                    'PORTION'   => $portion,
                    'CREATED_BY'=> $param['USERNAME'],
                    'CURRENCY'  => $param['AG_SYND_CURRENCY'][$key],
                    'PERCENTAGE'=> floatval($percentage)
                    ] ;
                // var_dump($dt);
                $checkDup = $this->db->query("SELECT UUID FROM BANK_KI WHERE ID = '".$param['ID_REC'][$key]."' AND COUNTER = '".$param['COUNTER']."'")->result();
                if($checkDup == null) {
                    $dt['COUNTER'] = $param['COUNTER'] ;
                    $result = $this->db->set('CREATED_AT', "SYSDATE", false);
                    $result = $this->db->set($dt)->insert("BANK_KI");

                }
                else {
                    $result = $this->db->set('CREATED_AT', "SYSDATE", false);
                    $result = $this->db->set($dt)->where(['ID' => $param['ID_REC'][$key], 'COUNTER' => $param['COUNTER']])->update("BANK_KI");
                }
                // var_dump($result); 
                if($result) {
                    $this->db->trans_commit();
                   $check = true ;
                }
                else {
                    $this->db->trans_rollback();
                   $check = false ;
                   break ;
                }
                $idx++;
        }
        // var_dump($check);exit;
        if($check) {
            $return = [
                'STATUS' => TRUE,
                'MESSAGE' => 'Data has been Successfully Saved !!'
            ];
        } else {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => 'error'
            ];
        }
            $this->db->close();
            return $return;
        }
    
        public function loadVendorKI($q){
            $query = "SELECT ID,FCNAME as TEXT,BIC, FCCODE FROM SUPPLIER WHERE (FCCODE LIKE '%LN%' OR FCCODE LIKE '%SU%') AND ISACTIVE = 'TRUE' AND FCNAME LIKE '%$q%' ESCAPE '!'";
            // var_dump($query);
            $res = $this->db->query($query);
            
            return $res->result(); 
        }

        public function dataVendorKI($param) {
            $q = "SELECT ID, BANKNAME, BANKACCOUNT FROM SUPPLIER WHERE FCNAME LIKE '%".$param['FCNAME']."%'" ;
            $result = $this->db->query($q)->row();
            // var_dump($this->db->last_query()); exit;
            $this->db->close();
            return $result ;
        }

        public function getOWTrancheKI($param) {
            $q = "SELECT FKIT.TRANCHE_NUMBER, FKIT.LOAN_ACCOUNT_NUMBER, FKIT.EFFECTIVE_DATE, FKIT.LIMIT_TRANCHE, FKIT.COUNTER FROM FUNDS_DETAIL_KI_TRANCHE FKIT WHERE FKIT.UUID = ? AND FKIT.COUNTER = ?" ;
            $result = $this->db->query($q, array($param['UUID'], $param['COUNTER']))->result();
            // var_dump($this->db->last_query());
            $this->db->close();
            return $result;
        }

        public function getOWTrancheKIByID($param) {
            $q = "SELECT fwdkit.TRANCHE_NUMBER,
            DDOWN_AMT, CURRENCY, latest_balance,
            CASE
               WHEN balance IS NULL
               THEN
                  CASE
                     WHEN latest_balance IS NULL THEN limit_tranche
                     ELSE latest_balance
                  END
               ELSE
                  balance
            END
               AS balance
       FROM funds_wd_ki_tranche fwdkit
            LEFT JOIN (    select * from ( 
                           SELECT balance AS latest_balance, tranche_number, uuid, c_tr, counter, batchid, status, max(counter) over (partition by uuid, tranche_number, batchid) max_counter
                              FROM funds_wd_ki_tranche
                          ORDER BY batchid DESC, counter nulls last, counter desc)
                           where counter = max_counter
                           ) ltst
               ON     fwdkit.uuid = ltst.uuid
                  AND fwdkit.tranche_number = ltst.tranche_number and fwdkit.c_tr = ltst.c_tr 
            LEFT JOIN (SELECT limit_tranche, uuid, tranche_number, currency, counter
                         FROM funds_detail_ki_tranche) fdki
               ON     fdki.uuid = fwdkit.uuid AND fdki.tranche_number = fwdkit.tranche_number and fdki.counter = fwdkit.c_tr
      WHERE fwdkit.batchid = ? and fwdkit.counter is null and fwdkit.uuid = ?" ;
            $result = $this->db->query($q, array($param['BATCHID'], $param['UUID']))->result();
            // var_dump($this->db->last_query());
            $this->db->close();
            return $result;
        }

        public function getWDTranchesKI($param) {
            $q = "SELECT * FROM FUNDS_WD_KI_TRANCHE WHERE BATCHID = ? AND UUID = ?" ;
            $result = $this->db->query($q, array($param['BATCHID'], $param['UUID']))->result();
            $this->db->close();
            return $result;
        }
        
        public function UploadInstallment($param) {
            $this->db->trans_begin();
            $param = $this->input->post();
            $checkAddendum = $this->db->select('IS_ACC')->from('FUNDS_MASTER')->where('UUID', $param['UUID'])->get()->row();
            $checklatest = $this->db->select('COUNTER')->from('FUNDS_KI_INSTALLMENT')->where('UUID', $param['UUID'])->order_by('COUNTER', 'DESC')->get()->row();
            $counter = intval($checklatest->COUNTER);
            $getLastInstallmentq = "SELECT * FROM FUNDS_KI_INSTALLMENT WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER ='{$param['TRANCHE_NUMBER']}' AND COUNTER = '{$counter}' ORDER BY ID ASC" ;
            $getLastInstallment = $this->db->query($getLastInstallmentq)->result();
            // $this->db->where(array('UUID'=>$param['UUID'], 'COUNTER'=>'0', 'TRANCHE_NUMBER'=>$param['TRANCHE_NUMBER']))->delete('FUNDS_KI_INSTALLMENT');
            // var_dump($checklatest->COUNTER); exit;
            try {
                $data = [];
                if($param['COUNTER'] == null) {
                    throw new Exception('Fill in header data first before fill in tranche data !!!') ;
                }
                if(!isset($_FILES['uploads'])) {
                    throw new Exception('No Files uploaded !!!');
                }
                else {
                    $file = $_FILES['uploads'] ;
                    $spreadsheet = IOFactory::load($file['tmp_name']);
                    $sheetData = $spreadsheet->getActiveSheet()->removeRow(1)->toArray(null, true, true, true);
                    $result = false ;
                    // var_dump($sheetData); exit;
                    foreach ($sheetData as $value) {
                       
                        if($value['B'] != NULL && !(str_contains($value['B'], '/'))) {
                            throw new Exception('Input data not match to format');
                        }
                        if(
                            $value['B'] == NULL ||
                            $value['C'] == NULL 
                        ) {
                           continue;
                        } else {
                            $INT_AMT = intval(preg_replace("/[^\d\.\-]/","",$value['C']));
                            $date = explode( '/',$value['B']);
                            $month = $date[0] ;
                            $year = $date[1] ;
                            $dt = [
                                'UUID' => $param['UUID'],
                                'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'],
                                'CREATED_BY' => $param['USERNAME'],
                                'PERIOD_MONTH' => $month,
                                'PERIOD_YEAR' => $year,
                                'INSTALLMENT_AMOUNT' => $INT_AMT,
                                'ID' => $value['A'],
                                'IS_ORIGINAL' => 1
                            ] ;
                            if($counter != $param['COUNTER']) {
                                // var_dump($INT_AMT);
                                $dt['COUNTER'] = $param['COUNTER'] ;
                                if($counter == null) {
                                    $dt['IS_PAYMENT'] = 0 ;
                                    $dt['IS_PAID'] = 0 ;
                                }
                                else {
                                    $dt['IS_PAYMENT'] = $getLastInstallment[$value['A']]->IS_PAYMENT ;
                                    $dt['IS_PAID'] = $getLastInstallment[$value['A']]->IS_PAID ;
                                }
                                $result = $this->db->set('CREATED_AT', 'SYSDATE', false)->set($dt) ;
                                $result = $this->db->insert('FUNDS_KI_INSTALLMENT');
                            }
                            else {
                                $dt['COUNTER'] = $param['COUNTER'] ? $param['COUNTER'] : 0  ;
                                $checkid = $this->db->get_where('FUNDS_KI_INSTALLMENT',[
                                    'ID' => $value['A'],
                                    'UUID' => $param['UUID'],
                                    'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER']
                                ])->result();
                                if($checkid == null) {
                                    if($counter == null) {
                                        $dt['IS_PAYMENT'] = 0 ;
                                        $dt['IS_PAID'] = 0 ;
                                    }
                                    else {
                                        $dt['IS_PAYMENT'] = $getLastInstallment[$value['A']]->IS_PAYMENT ;
                                        $dt['IS_PAID'] = $getLastInstallment[$value['A']]->IS_PAID ;
                                    }
                                    $result = $this->db->set('CREATED_AT', 'SYSDATE', false)->set($dt) ;
                                    $result = $this->db->insert('FUNDS_KI_INSTALLMENT');
                                }
                                else {
                                    $result = $this->db->set([
                                        'PERIOD_MONTH' => $month,
                                        'PERIOD_YEAR' => $year,
                                        'INSTALLMENT_AMOUNT' => $INT_AMT,
                                        'CREATED_BY' => $param['USERNAME']
                                    ])
                                    ->set('CREATED_AT', 'SYSDATE', false)
                                    ->where([
                                        'ID' => $value['A'],
                                        'UUID' => $param['UUID'],
                                        'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER']
                                        ])
                                    ->update('FUNDS_KI_INSTALLMENT') ;
                                }
                            }
                            // array_push($data, $dt);
                        }
                        // var_dump("{$value['A']} - {$value['B']} - {$value['C']} ");
                    }
                    // exit ;
                    if($result) {
                        $this->db->trans_commit();
                        $return = [
                            'STATUS' => TRUE,
                            'MESSAGE' => 'Data has been Successfully Saved !!'
                        ] ;
                    } else {
                        $this->db->trans_rollback();
                        $return = [
                            'STATUS' => FALSE,
                            'MESSAGE' => 'error'
                        ];
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

    public function UploadInstallmentidc($param) {
            $this->db->trans_begin();
            $param = $this->input->post();
            $checkAddendum = $this->db->select('IS_ACC')->from('FUNDS_MASTER')->where('UUID', $param['UUID'])->get()->row();
            // $checklatest = $this->db->select('COUNTER')->from('FUNDS_KI_INSTALLMENT')->where('UUID', $param['UUID'])->order_by('COUNTER', 'DESC')->get()->row();
            // $counter = intval($checklatest->COUNTER);
            // if($checkAddendum->IS_ACC == 0) {
            $this->db->where(array('UUID'=>$param['UUID'], 'COUNTER'=>'0', 'TRANCHE_NUMBER'=>$param['TRANCHE_NUMBER']))->delete('FUNDS_KI_INSTALLMENT_IDC');
            // }
            
            // var_dump($checklatest->COUNTER); exit;
            try {
                $data = [];
                if(!isset($_FILES['uploads'])) {
                    throw new Exception('No Files uploaded !!!');
                }
                else {
                    $file = $_FILES['uploads'] ;
                    $spreadsheet = IOFactory::load($file['tmp_name']);
                    $sheetData = $spreadsheet->getActiveSheet()->removeRow(1)->toArray(null, true, true, true);
                    // var_dump($sheetData); exit;
                    foreach ($sheetData as $value) {
                        if(
                            $value['A'] == NULL &&
                            $value['B'] == NULL &&
                            $value['C'] == NULL
                        ) {
                            continue ;
                        } else {
                            $INT_AMT = intval(preg_replace("/[^\d\.\-]/","",$value['C']));
                            $date = explode( '/',$value['B']);
                            $month = $date[0] ;
                            $year = $date[1] ;
                            // var_dump($INT_AMT);
                            $dt = [
                                'UUID' => $param['UUID'],
                                'TRANCHE_NUMBER' => $param['TRANCHE_NUMBER'],
                                'CREATED_BY' => $param['USERNAME'],
                                'PERIOD_MONTH' => $month,
                                'PERIOD_YEAR' => $year,
                                'INSTALLMENT_AMOUNT' => $INT_AMT,
                                'ID' => $value['A'],
                                'COUNTER' => 0,
                                'IS_ORIGINAL' => 1
                            ] ;
                            // array_push($data, $dt);
                        }
                            $result = $this->db->set('CREATED_AT', 'SYSDATE', false)->set($dt) ;
                            $result = $this->db->insert('FUNDS_KI_INSTALLMENT_IDC');
                    }
                    if($result) {
                        $this->db->trans_commit();
                        $return = [
                            'STATUS' => TRUE,
                            'MESSAGE' => 'Data has been Successfully Saved !!'
                        ] ;
                    } else {
                        $this->db->trans_rollback();
                        $return = [
                            'STATUS' => FALSE,
                            'MESSAGE' => 'error'
                        ];
                    }
                    
                }

            } catch (Exception $ex) {
                $return = [
                            'STATUS' => FALSE,
                            'MESSAGE' => $ex->getMessage()
                        ];
            }
            $this->db->close();
            return $return;
        }


    public function saveUpdatePeriod($param,$location){
        try {
            $this->db->trans_begin();
            $result     = FALSE;

            $COMPANY    = $param['COMPANY'];
            $YEAR       = $param['YEAR'];
            $MONTH      = $param['MONTH'];

            $WHERE = " WHERE SYSTEM = 'BANK' ";
            if($COMPANY != '0'){
                $WHERE .= " AND COMPANY = '".$COMPANY."'";
            }
            // if($MONTH == '1'){
            //     $minMonth = 12;
            //     $minYear  = $YEAR - 1;
            // }else{
            //     $minMonth = $MONTH - 1;
            //     $minYear  = $YEAR;
            // }

            $q = "update funds_periodcontrol set lastperiodposted = (select last_day(add_months((to_date('$MONTH/$YEAR','mm/yyyy')),-1)) from dual), currentdate = (select trunc(add_months((to_date('$MONTH/$YEAR','mm/yyyy')),-0),'month') from dual), currentaccountingyear = (select to_number('$YEAR') as from dual), currentaccountingperiod = (select to_number('$MONTH') as from dual), closeaccountingyear = ( select case when (select to_number('$MONTH') as from dual) -1 = 0 then (select to_number('$YEAR') as from dual)-1 else (select to_number('$YEAR') as from dual) end as closeaccountingyear from dual ), closeaccountingperiod = ( select case when (select to_number('$MONTH') as from dual) -1 = 0 then 12 else (select to_number('$MONTH') as from dual)-1 end as closeaccountingperiod from dual ) ". $WHERE;
            // $q = "UPDATE PERIODCONTROL SET CURRENTACCOUNTINGYEAR = '".$YEAR."', CURRENTACCOUNTINGPERIOD = '".$MONTH."', CLOSEACCOUNTINGYEAR = '".$minYear."', CLOSEACCOUNTINGPERIOD = '".$minMonth."'".$WHERE;
            // var_dump($q);exit();
            $result1 = $this->db->query($q);

            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
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

    public function ShowDataPeriod() {
        $this->fillable = ['C.COMPANYNAME',"P.CURRENTDATE","P.CURRENTACCOUNTINGYEAR" ,'P.CURRENTACCOUNTINGPERIOD', 'P.CLOSEACCOUNTINGYEAR', 'P.CLOSEACCOUNTINGPERIOD'];
        $result = $this->db->select($this->fillable)
                        ->from("FUNDS_PERIODCONTROL P")
                        ->join("COMPANY C", 'C.ID = P.COMPANY', 'left')
                        ->order_by('P.COMPANY ASC')->get()->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function getOWTrancheBalanceKI($param) {
        $q = "SELECT DISTINCT UUID, TRANCHE_NUMBER, LIMIT_TRANCHE, BALANCE, CURRENCY, BATCHID, MAX_BID, MAX_CTR, COUNTER, MAX_COUNTER, ISACTIVE FROM
        (SELECT UUID, ISACTIVE, TRANCHE_NUMBER, LIMIT_TRANCHE, BALANCE, CURRENCY ,COALESCE(BATCHID, 0) AS BATCHID, COALESCE(MAX_BID, 0) AS MAX_BID, COALESCE(MAX_CTR, 0) AS MAX_CTR, COUNTER, MAX(COUNTER) OVER (PARTITION BY UUID, TRANCHE_NUMBER) max_counter from
            (
            SELECT FKIT.UUID, FKIT.TRANCHE_NUMBER, FKIT.LIMIT_TRANCHE, WDKIT.IS_PAYMENT, FKIT.CURRENCY, WDKIT.BATCHID,WDKIT.MAX_BID, WDKIT.MAX_CTR, FKIT.ISACTIVE,
                    CASE
                        WHEN WDKIT.COUNTER IS NULL THEN 0
                        ELSE WDKIT.COUNTER + 1
                        END
                    AS COUNTER,
                    CASE
                        WHEN WDKIT.BALANCE IS NULL THEN FKIT.LIMIT_TRANCHE
                        ELSE WDKIT.BALANCE
                    END
                    AS BALANCE
                  FROM FUNDS_DETAIL_KI_TRANCHE FKIT
                       LEFT JOIN
                       (
                        SELECT
                               X.UUID,
                               X.TRANCHE_NUMBER,
                               X.STATUS,
                               X.BALANCE,
                               X.IS_PAYMENT,
                               COALESCE(X.COUNTER, 0) AS COUNTER,
                               A.BATCHID AS MAX_BID,
                               B.COUNTER AS MAX_CTR,
                               COALESCE(X.BATCHID, 0) AS BATCHID
                          FROM FUNDS_WD_KI_TRANCHE X
                               LEFT JOIN (
              SELECT MAX (COALESCE(BATCHID, 0)) AS BATCHID, TRANCHE_NUMBER, UUID
                FROM FUNDS_WD_KI_TRANCHE
               WHERE STATUS = 1
            GROUP BY TRANCHE_NUMBER, UUID) A
                                  ON A.TRANCHE_NUMBER = X.TRANCHE_NUMBER AND A.UUID = X.UUID
                               LEFT JOIN (
               SELECT MAX(COALESCE(COUNTER, 0)) AS COUNTER , TRANCHE_NUMBER, UUID
                FROM FUNDS_WD_KI_TRANCHE
                WHERE STATUS = 1
                GROUP BY TRANCHE_NUMBER, UUID
                               ) B ON B.TRANCHE_NUMBER = X.TRANCHE_NUMBER AND B.UUID = X.UUID 
                                  ORDER BY UUID, TRANCHE_NUMBER
                       )
                       WDKIT ON FKIT.UUID = WDKIT.UUID AND WDKIT.TRANCHE_NUMBER = FKIT.TRANCHE_NUMBER
                       AND FKIT.ISACTIVE = '1'
                       AND FKIT.IS_COMPLETE IS NULL
            ) M 
        )  WHERE COUNTER = MAX_COUNTER AND BATCHID = MAX_BID AND UUID = ? AND ISACTIVE = 1" ;

        $result = $this->db->query($q, $param['UUID'])->result();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    public function updateBalanceAfterPaid () {
        try {
            $startsession = FALSE ;
            $notpaid = FALSE ;
            $result = FALSE;
            $dataheadKI = array();
            $this->db->trans_begin();
            $checkpaymentq = "SELECT FM.UUID, FP.*, CF.*, P.*,FDKIT.TRANCHE_NUMBER
                                FROM fundspayment fp
                                    LEFT JOIN (SELECT id as cf_id, docnumber FROM cf_transaction) cf
                                        ON cf.docnumber = fp.pay_id
                                    LEFT JOIN (SELECT CFTRANSID FROM PAYMENT) P
                                        ON P.CFTRANSID = CF.cf_id
                                    LEFT JOIN (SELECT UUID, PK_NUMBER, SUB_CREDIT_TYPE FROM FUNDS_MASTER)FM
                                        ON FM.PK_NUMBER = FP.PK_NUMBER AND FM.SUB_CREDIT_TYPE = FP.CREDIT_TYPE
                                    LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FDKIT ON FP.CONTRACT_NUMBER = FDKIT.CONTRACT_NUMBER AND FM.UUID = FDKIT.UUID AND FDKIT.ISACTIVE = 1 AND FDKIT.IS_COMPLETE IS NULL
                            WHERE fp.is_payment = 1 and fp.is_paid is null" ;
            $checkpayment = $this->db->query($checkpaymentq)->result();
            
            // var_dump($this->db->last_query()) ; exit;
            if($checkpayment == null) {
                throw new Exception('No Payment Currently');
            }
            else {
                foreach ($checkpayment as $data) {
                    // var_dump($data);
                    if($data->CFTRANSID == null) {
                        // var_dump($data); exit;
                        $notpaid = TRUE;
                        continue;
                    }
                    $PK_NUMBER = $data->PK_NUMBER ;
                    $CONTRACT_NUM = $data->CONTRACT_NUMBER ;
                    $UUID = $data->UUID;
                    $latestwdq = "SELECT fm.pk_number,fwdkit.*
                                    FROM funds_wd_ki_tranche fwdkit
                                        LEFT JOIN funds_master fm ON fm.uuid = fwdkit.uuid AND FM.ISACTIVE = '1' AND FM.IS_COMPLETE IS NULL
                                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FDKIT ON FDKIT.UUID = FM.UUID AND FWDKIT.TRANCHE_NUMBER = FDKIT.TRANCHE_NUMBER AND FDKIT.ISACTIVE = '1' AND FDKIT.IS_COMPLETE IS NULL
                                        WHERE FM.PK_NUMBER = '$PK_NUMBER' AND FDKIT.CONTRACT_NUMBER = '$CONTRACT_NUM' AND FM.UUID = '$UUID'
                                        ORDER BY BATCHID DESC, fwdkit.COUNTER DESC NULLS LAST FETCH FIRST 1 ROWS ONLY";
                    $latestwd = $this->db->query($latestwdq)->row();
                    // var_dump($latestwd->COUNTER != null) ; exit;
                    // var_dump($this->db->last_query()); exit;
                    // $checkupq = "SELECT fm.pk_number,fwdkit.*
                    //                 FROM funds_wd_ki_tranche fwdkit
                    //                     LEFT JOIN funds_master fm ON fm.uuid = fwdkit.uuid
                    //                     LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FDKIT ON FDKIT.UUID = FM.UUID AND FWDKIT.TRANCHE_NUMBER = FDKIT.TRANCHE_NUMBER
                    //                     WHERE FM.PK_NUMBER = '$PK_NUMBER' AND FDKIT.CONTRACT_NUMBER = '$CONTRACT_NUM' AND FM.UUID = '$UUID' AND fwdkit.IS_PAYMENT = 1
                    //                     ORDER BY fwdkit.COUNTER DESC FETCH FIRST 1 ROWS ONLY";
                    // $checkupwd = $this->db->query($checkupq)->row();
                    $duplicatedwd = [
                        'UUID' => $latestwd->UUID,
                        'TRANCHE_NUMBER' => $data->TRANCHE_NUMBER,
                        'DDOWN_AMT' => 0,
                        'BATCHID' => $latestwd->BATCHID,
                        'IS_PAYMENT' => 1,
                        'STATUS' => 1,
                        'BALANCE' => intval($data->INSTALLMENT) + intval($latestwd->BALANCE) 
                    ] ;
                    if(!(array_key_exists($CONTRACT_NUM, $dataheadKI))) {
                        $dataheadKI[$CONTRACT_NUM] = [
                            'UUID' => $UUID
                        ];
                    }
                    // var_dump($duplicatedwd); exit;
                    if($startsession == FALSE) {
                        if($latestwd->COUNTER == null) {
                            $counter = 0;
                            $duplicatedwd['COUNTER'] = $counter ;
                            $result = $this->db->set($duplicatedwd)
                            ->set('CREATED_AT', 'SYSDATE', FALSE)
                            ->set('CREATED_BY', $this->session->userdata('FCCODE'))
                            ->insert('FUNDS_WD_KI_TRANCHE');
                        } else {
                            $counter = intval($checkupwd->COUNTER) + 1 ;
                            $duplicatedwd['COUNTER'] = $counter ;
                            $result = $this->db->set($duplicatedwd)
                            ->set('CREATED_AT', 'SYSDATE', FALSE)
                            ->set('CREATED_BY', $this->session->userdata('FCCODE'))
                            ->insert('FUNDS_WD_KI_TRANCHE');
                        }
                        $startsession = TRUE ;
                    } else {
                        $duplicatedwd['COUNTER'] = $counter ;
                        $result = $this->db->set($duplicatedwd)
                            ->set('CREATED_AT', 'SYSDATE', FALSE)
                            ->set('CREATED_BY', $this->session->userdata('FCCODE'))
                            ->where(array('PK_NUMBER' => $PK_NUMBER, 'CONTRACT_NUMBER' => $CONTRACT_NUM, 'COUNTER' => $counter))
                            ->update('FUNDS_WD_KI_TRANCHE');
                    }
                    $result = $this->db->set('IS_PAID', '1')
                        ->where('ID', $data->ID)
                        ->update('FUNDSPAYMENT');
                    // var_dump($this->db->last_query());
                    if(!$result) {
                        break;
                    }
                }
                // foreach ($dataheadKI as $dataki) {
                //     $xUUID = $dataki['UUID'];
                //     $totalbalance = 0 ;
                //     $counter = 0;
                //     $balchildq = "SELECT FKI.UUID, FWDT.COUNTER, FWDT.IS_PAYMENT,
                //                         FKI.TRANCHE_NUMBER,
                //                         CASE 
                //                             WHEN FWDT.BALANCE IS NULL THEN (SELECT BALANCE FROM FUNDS_WD_KI_TRANCHE WHERE TRANCHE_NUMBER = FKI.TRANCHE_NUMBER AND UUID = FKI.UUID AND STATUS = 1 ORDER BY BATCHID DESC, COUNTER DESC, IS_PAYMENT NULLS LAST FETCH FIRST 1 ROWS ONLY)
                //                             ELSE FWDT.BALANCE
                //                             END
                //                             AS BALANCE,
                //                         FWDT.BATCHID,
                //                         FKI.LIMIT_TRANCHE,
                //                         CASE
                //                             WHEN FWDT.DDOWN_AMT IS NULL THEN 0
                //                             ELSE FWDT.DDOWN_AMT
                //                             END
                //                             AS DRAWDOWN_VALUE
                //                 FROM FUNDS_DETAIL_KI_TRANCHE FKI
                //                         LEFT JOIN (SELECT BATCHID,
                //                                         BALANCE,
                //                                         DDOWN_AMT,
                //                                         TRANCHE_NUMBER,
                //                                         UUID,
                //                                         IS_PAYMENT, 
                //                                         COUNTER
                //                                     FROM FUNDS_WD_KI_TRANCHE FKIT
                //                                         LEFT JOIN
                //                                         (       SELECT ID
                //                                                 FROM FUNDS_WD_KI
                //                                                 WHERE UUID = '$xUUID' AND (STATUS = 1)
                //                                             ORDER BY ID DESC
                //                                         FETCH FIRST 1 ROWS ONLY ) FWD
                //                                             ON FWD.ID = FKIT.BATCHID 
                //                                     WHERE FWD.ID = FKIT.BATCHID
                //                                     ORDER BY COUNTER, IS_PAYMENT NULLS LAST FETCH FIRST 1 ROWS ONLY) FWDT
                //                         ON FKI.TRANCHE_NUMBER = FWDT.TRANCHE_NUMBER
                //                 WHERE     
                //                         FKI.UUID = '$xUUID'
                //                         AND FKI.ISACTIVE = '1'" ;
                //     $balchild = $this->db->query($balchildq)->result();
                //     foreach ($balchild as $amount) {
                //         if(!isset($amount->BALANCE)) {
                //             $totalbalance += $amount->LIMIT_TRANCHE;
                //         } else {
                //             $totalbalance += $amount->BALANCE ;
                //         }
                //     }
                //     $lastbalancemainq = "SELECT * FROM FUNDS_WD_KI WHERE UUID = '$xUUID' AND STATUS = 1 ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY " ;
                //     $lb = $this->db->query($lastbalancemainq)->row();
                //     $checkispayq = "SELECT * FROM FUNDS_WD_KI WHERE UUID = '$xUUID' AND STATUS = 1 AND IS_PAYMENT = 1 ORDER BY ID DESC FETCH FIRST 1 ROWS ONLY" ;
                //     $ispay = $this->db->query($checkispayq)->row();
                //     $uplastbalancemain = [
                //         'LAST_ID' => $lb->ID,
                //         'UUID' => $lb->UUID,
                //         'VALUE_DATE' => $lb->VALUE_DATE,
                //         'TRANCHE_NUMBER' => $lb->TRANCHE_NUMBER,
                //         'DRAWDOWN' => $lb->DRAWDOWN,
                //         'DRAWDOWN_VALUE' => $lb->DRAWDOWN_VALUE,
                //         'RATE_IDRUSD' => $lb->RATE_IDRUSD,
                //         'RATE_CNYUSD' => $lb->RATE_CNYUSD,
                //         'RATE_SGDUSD' => $lb->RATE_SGDUSD,
                //         'BALANCE' => $totalbalance,
                //         'STATUS' => $lb->STATUS,
                //         'PK_ID' => $lb->PK_ID
                //     ];
                //     if($ispay == null) {
                //         $counter1 = 0;
                //         $uplastbalancemain['COUNTER'] = $counter1 ;
                //         $result1 = $this->db->set('CREATED_AT', 'SYSDATE', FALSE)
                //                     ->set('CREATED_BY', $this->session->userdata('FCCODE'))
                //                     ->set($uplastbalancemain)
                //                     ->insert('FUNDS_WD_KI') ;
                //     } else {
                //         $counter1 = intval($ispay->COUNTER) + 1;
                //         $uplastbalancemain['COUNTER'] = $counter1 ;
                //         $result1 = $this->db->set('CREATED_AT', 'SYSDATE', FALSE)
                //                     ->set('CREATED_BY', $this->session->userdata('FCCODE'))
                //                     ->set($uplastbalancemain)
                //                     ->insert('FUNDS_WD_KI') ;
                //     } 
                // }   
                if((!$result)) {
                    if($notpaid) {
                        // var_dump($result); exit;
                        throw new Exception ('Any Payment Request Not Yet Paid');
                    }
                    else {
                        throw new Exception ('Failed to Update Balance');
                    }
                }
            }
            $this->db->trans_complete();
            if($this->db->trans_status() === FALSE) {
                throw new Exception ('Failed to Update Balance');
            }
            else if($result && $result1) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been successfully saved !!'
                ];
            }
            else {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'error'
                ];
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            if($notpaid) {
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => $ex->getMessage(),
                    'NOTPAID' => TRUE
                ];
            } else {
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => $ex->getMessage()
                ];
            }

        }
        $this->db->close();
        return $return;
    }

    public function updateLimitBalance ($param) {
        // var_dump($param) ; exit;
        // $this->db->trans_begin();
        try {
            $setnewlim = false;
            $newLimit = intval(preg_replace("/[^\d\.\-]/","",$param['LIMIT_TRANCHE']));
            // $rate = intval(preg_replace("/[^\d\.\-]/","",$param['RATE']));
            $checkrate = intval($param['RATE']);
            if($checkrate <= 0 ) {
                $rate = floatval($param['RATE']);
            } else {
                $rate = intval($param['RATE']);
            }
            $tranchenumber = $param['T_NUMBER'];
            // A. calculate limit new and latest difference
            // 1. get last limit data
            // Check if there's diff between latest limit tranche and new limit tranche
            $latestlimitq = "SELECT * FROM FUNDS_DETAIL_KI_TRANCHE A WHERE UUID = '".$param['UUID']."' AND TRANCHE_NUMBER = '".$tranchenumber."' AND ISACTIVE = '0' ORDER BY COUNTER DESC  " ;
            $latestlimit = $this->db->query($latestlimitq)->row();
            $latlim = intval($latestlimit->LIMIT_TRANCHE);
            // var_dump($newLimit, $latlim);
            if($newLimit != $latlim ) {
                
                $setnewlim = true;
            }
            //Check if there's flag UP_BAL
            // context :
                // - Ketika kondisi kontrak bukan addendum, berarti patokan data limit yaitu data yang aktif, yang sedang diedit.
                // - Kalau addendum, limit yang dipakai adalah limit yang tidak aktif karena limit addendum sudah dimasukkan terlebih dahulu sebelum update balance ke masing-masing penarikan
                // - Parameter = UP_FLAG => Kalau UP_FLAG == 1, kondisinya tranche adalah data yang sedang diedit. Kalau UP_FLAG == 0 berarti data tranchenya baru diaddendum, ambil limitnya dari data yang isactive == 0 
            if($setnewlim) {

                $up_flagq    = " SELECT * FROM FUNDS_DETAIL_KI_TRANCHE A WHERE UUID = '".$param['UUID']."' AND TRANCHE_NUMBER = '".$tranchenumber."' AND UP_BAL = '1'" ;
                $up_flag = $this->db->query($up_flagq)->row();
                // var_dump($up_flag) ; exit;
                if(!isset($up_flag)) {
                    $lastlimitq = "SELECT A.*, C.COUNTERMAX
                                        FROM FUNDS_DETAIL_KI_TRANCHE A
                                            LEFT JOIN (  SELECT MAX (COUNTER) AS COUNTERMAX, UUID
                                                            FROM FUNDS_DETAIL_KI_TRANCHE WHERE ISACTIVE = 0
                                                        GROUP BY UUID) C
                                                ON C.UUID = A.UUID
                                    WHERE     A.UUID = '".$param['UUID']."' AND
                                        A.COUNTER = COUNTERMAX AND A.TRANCHE_NUMBER = '".$tranchenumber."'" ;
                                        
                    $lastlimit = $this->db->query($lastlimitq)->row();
                    $return = $this->db->set('UP_BAL', '1')
                                ->where(['TRANCHE_NUMBER' => $tranchenumber, 'ID' => $param['ID']])
                                ->update('FUNDS_DETAIL_KI_TRANCHE') ;
                    // var_dump($lastlimit);
                }
                else {
                    $lastlimit = $up_flag;
                    // var_dump($lastlimit);
                }
                // exit;
                // var_dump($lastlimit); 
                //check if curr after and before
                // $rateexchange = $rate;
                if($lastlimit->CURRENCY == 'IDR') {
                    $rateexchange = 1 / intval($param['RATE']);
                }
                else if ($param['CURRENCY'] == 'IDR') {
                    $rateexchange = intval($param['RATE']);
                }
                else {
                    $rateexchange = 1 / intval($param['RATE']);
                }
                // var_dump($rateexchange); exit;
                $amountLimit = intval($param['LIMIT_MODAL'] ? $param['LIMIT_MODAL'] : $lastlimit->LIMIT_TRANCHE) ;
                // 3. Convert latestlimit
                $convertedLatLimit = intval(round($amountLimit * $rateexchange));
                // var_dump($convertedLatLimit) ;
                // 4. Get diff with new
                $latNewDiff = $newLimit - $convertedLatLimit;
                // var_dump($newLimit); 
                // 5. convert balances to new currency
                // a. get last balance per tranche
                $lastBalanceTrq = "SELECT * FROM FUNDS_WD_KI_TRANCHE WHERE UUID = '".$param['UUID']."' AND TRANCHE_NUMBER = '".$tranchenumber."' AND STATUS = 1 ORDER BY COUNTER NULLS LAST, COUNTER DESC FETCH FIRST 1 ROWS ONLY" ;
                $lastbalanceTr= $this->db->query($lastBalanceTrq)->row();
                // var_dump($lastbalanceTr); exit;
                if(sizeof($lastbalanceTr) == 0) {
                    $result = [
                        'STATUS' => TRUE,
                        'MESSAGE' => 'No Withdrawal, Balance not updated'
                    ] ;
                    return $result ;
                }
                // b. add diff to balance
                // var_dump($balance) ;exit;
                $latbal = $lastbalanceTr->BALANCE ;
                $ID = $lastbalanceTr->BATCHID;
                $COUNTER = $lastbalanceTr->COUNTER ;
                //duplicate latest data 
                $dupli = [
                    'UUID' => $lastbalanceTr->UUID,
                    'TRANCHE_NUMBER' => $lastbalanceTr->TRANCHE_NUMBER,
                    'DDOWN_AMT' => 0,
                    'BATCHID' => $ID,
                    'STATUS' => 1
                ] ;
                //convert balance to prefered currency
                $convertedLatBal = intval(round($latbal * $rateexchange));
                $newBal = $convertedLatBal + $latNewDiff;
                // var_dump($convertedLatBal, $latNewDiff);
                $newcounter = $COUNTER + 1;
                $dupli['BALANCE'] = $newBal;
                $dupli['COUNTER'] = $newcounter;
                // var_dump($latNewDiff,$dupli); exit; 
                $return = $this->db->set($dupli)
                        ->set('CREATED_AT', 'SYSDATE', false)
                        ->set('CREATED_BY', $this->session->userdata('FCCODE'))
                        ->insert('FUNDS_WD_KI_TRANCHE') ;
                if($newBal < 0) {
                    $return = false;
                }
                // var_dump($return); exit;
                if(!$return) {
                    if($newBal < 0) {
                        throw new Exception('Less than last withdrawal');    
                    }
                    throw new Exception('Save Data Failed ID = '.$ID);
                } else {
                    $result = [
                        'STATUS' => TRUE,
                        'MESSAGE' => 'UPDATED'
                    ];
                }
            }
            else {
                $result = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Limit unchanged'
                ];
            }

        } catch (Exception $ex) {
            // $this->db-trans_commit();
            $result = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ] ;
        }
        // $this->db->close();
        return $result ;
    }

    public function updateInstallmentByWD ($param) {
        // ini_set('display_errors', 'On');
        //implement this on :
        // - Save withdrawal
        try {
            $this->db->trans_begin();
            //clear db 
            $clear = $this->db->where("UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}' AND COUNTER = (SELECT COUNTER FROM FUNDS_KI_INSTALLMENT WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}' ORDER BY COUNTER DESC FETCH FIRST 1 ROWS ONLY) AND IS_ORIGINAL = '0'")->delete('FUNDS_KI_INSTALLMENT') ;
            $currentInstallmentq = "SELECT * FROM FUNDS_KI_INSTALLMENT WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}' AND COUNTER = (SELECT COUNTER FROM FUNDS_KI_INSTALLMENT WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}' ORDER BY COUNTER DESC FETCH FIRST 1 ROWS ONLY) AND IS_ORIGINAL = '1' ORDER BY ID ASC" ;
            $currentOutstandingq = "SELECT SUM(DDOWN_AMT) AS OUT FROM FUNDS_WD_KI_TRANCHE WHERE UUID = '{$param['UUID']}' AND TRANCHE_NUMBER = '{$param['TRANCHE_NUMBER']}' AND COUNTER IS NULL " ;
            $currentInstallment = $this->db->query($currentInstallmentq)->result();
            $currentOutstanding = $this->db->query($currentOutstandingq)->row();
            if($currentOutstanding == null || $currentOutstanding->OUT == '' || $currentOutstanding->OUT == '0') {
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'UPDATE INSTALLMENT : Outstanding Zero'
                ] ;
                $this->db->close();
                return $return ;
            }
            if($currentInstallment == null) {
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'UPDATE INSTALLMENT : Installment Zero'
                ] ;
                $this->db->close();
                return $return ;
            }
            // echo "<pre>";
            // var_dump($currentInstallment); 
            // var_dump($currentOutstanding);  
            $amount = intval($currentOutstanding->OUT) ;
            $lastPayIdx = false ;
            $installmentUp = [] ;
            $COUNTER = $currentInstallment[0]->COUNTER ;
            foreach($currentInstallment as $item) {
                $amount -= intval($item->INSTALLMENT_AMOUNT) ;
                // var_dump($amount);
                if($amount < 0) {
                    if($lastPayIdx == false) {
                        // array_push($installmentUp, ['ID' => $item->ID, 'AMOUNT' => intval($item->INSTALLMENT_AMOUNT) + $amount]) ;
                        $newAmount = intval($item->INSTALLMENT_AMOUNT) + $amount ;
                        $lastPayIdx = true ;
                    }
                    else {
                        // array_push($installmentUp, ['ID' => $item->ID, 'AMOUNT' => 0]) ;
                        $newAmount = 0 ;
                    }
                    $dt = [
                        'UUID' => $item->UUID,
                        'TRANCHE_NUMBER' => $item->TRANCHE_NUMBER,
                        'CREATED_BY' => $item->CREATED_BY,
                        'PERIOD_MONTH' => $item->PERIOD_MONTH,
                        'PERIOD_YEAR' => $item->PERIOD_YEAR,
                        'INSTALLMENT_AMOUNT' => $newAmount,
                        'ID' => $item->ID,
                        'COUNTER' => $item->COUNTER,
                        'IS_ORIGINAL' => 0
                    ] ;
                }
                else {
                    $dt = [
                        'UUID' => $item->UUID,
                        'TRANCHE_NUMBER' => $item->TRANCHE_NUMBER,
                        'CREATED_BY' => $item->CREATED_BY,
                        'PERIOD_MONTH' => $item->PERIOD_MONTH,
                        'PERIOD_YEAR' => $item->PERIOD_YEAR,
                        'INSTALLMENT_AMOUNT' => $item->INSTALLMENT_AMOUNT,
                        'ID' => $item->ID,
                        'COUNTER' => $item->COUNTER,
                        'IS_ORIGINAL' => 0
                    ] ;
                }
                $result = $this->db->set('CREATED_AT', 'SYSDATE', false)->set($dt) ;
                $result = $this->db->insert('FUNDS_KI_INSTALLMENT');
            }
            if($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "UPDATE INSTALLMENT : Amount successfully updated {$param[TRANCHE_NUMBER]} - {$param['UUID']}"
                ] ;
            }
            else {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => "UPDATE INSTALLMENT : Failed to Update {$param[TRANCHE_NUMBER]} - {$param['UUID']}"
                ] ;
            }
        }
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ] ;
        }
        $this->db->close();
        return $return ;
    }

    //Update1.3
    public function DisableEnableTranche($param) {
        ini_set('display_errors', 'On');
        try {
            $this->db->trans_begin();
            $result = false ;
            $IDTR = $param['ID'] ;
            $tr = $this->db->select('*')->where('ID', $IDTR)->from('FUNDS_DETAIL_KI_TRANCHE')->get()->row();
            //inactive the current tranche 
            $inact = $this->db->set('ISACTIVE', 0)->where('ID', $IDTR)->update('FUNDS_DETAIL_KI_TRANCHE') ;
            if(!$inact) {
                throw new Exception('Failed to update !!!') ;
            }
            $checkAcc = $this->db->select('IS_ACC')->where(['UUID' => $tr->UUID])->from('FUNDS_MASTER')->get()->row();
            $isEnabled = $tr->ISACTIVE == 1 ? true : false ; 
            $isAcc = $checkAcc->IS_ACC == 1 ? true : false ;
            
            if($isAcc) {
                //insert updated tranche
                if($isEnabled) {
                    $insertUp = $this->insertUpdateTr($tr, 'disable', 1, $param['USERNAME']) ;
                    if(!$insertUp){
                        throw new Exception('Tranche Failed to Update !!') ;
                    }
                } else {
                    $insertUp = $this->insertUpdateTr($tr, 'activate', 1, $param['USERNAME']) ;
                    if(!$insertUp){
                        throw new Exception('Tranche Failed to Update !!') ;
                    }
                }
                //copy all latest item
                if($insertUp) {
                    $copyItem = $this->copyLatestItemKI($tr->UUID, $tr->COUNTER, $param['USERNAME']);
                    if($copyItem['STATUS'] == FALSE) {
                        throw new Exception($copyItem['MESSAGE']) ;
                    } 
                    else {
                        $result = true ;
                    }
                }
            }
            else {
                if($isEnabled) {
                    $insertUp = $this->insertUpdateTr($tr, 'disable', 0, $param['USERNAME']) ;
                    if(!$insertUp){
                        throw new Exception('Tranche Failed to Update !!') ;
                    }
                    else {
                        $result = true ;
                    }
                } else {
                    $insertUp = $this->insertUpdateTr($tr, 'activate', 0, $param['USERNAME']) ;
                    if(!$insertUp){
                        throw new Exception('Tranche Failed to Update !!') ;
                    }
                    else {
                        $result = true ;
                    }
                }
            }
            // var_dump($result); exit;
            if($result && $isAcc) {
                $result = $this->db->set(['IS_ACC' => 0])->where('UUID', $tr->UUID)->update('FUNDS_MASTER') ;
            }
            if($result) {
                $this->db->trans_commit();
                $result = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Update Success'
                ] ;
            }
            else {
                throw new Exception('Update Failed !!') ;
            }
        } catch(Exception $ex) {
            $this->db->trans_rollback();
            $result = [
                'STATUS' => FALSE ,
                'MESSAGE' => $ex->getMessage()
            ] ;
        }
        $this->db->close();
        return $result;
    }

    public function copyLatestItemKI($UUID, $COUNTER, $USERNAME) {
        $hdDet = $this->db->select('*')->where(['UUID' => $UUID, 'COUNTER' => $COUNTER, 'ISACTIVE' => 1])->from('FUNDS_DETAIL_KI')->get()->row();
        $tr = $this->db->select('*')->where(['UUID' => $UUID, 'COUNTER'=> $COUNTER, 'ISACTIVE' => 1])->from('FUNDS_DETAIL_KI_TRANCHE')->get()->result();
        $newCounter = $COUNTER + 1;
        //duplicate headerDetail 
        $newHd = [
            'TOBANK' => $hdDet->TOBANK,
            'SUB_CREDIT_TYPE'=> $hdDet->SUB_CREDIT_TYPE,
            'FEE' =>  $hdDet->FEE,
            'ADM_FEE' => $hdDet->ADM_FEE,
            'AGENCY_FEE' => $hdDet->AGENCY_FEE,
            'PROVISI' => $hdDet->PROVISI,
            'UPFRONT_FEE' => $hdDet->UPFRONT_FEE,
            'ANNUAL_FEE' => $hdDet->ANNUAL_FEE,
            'COMMIT_FEE' => $hdDet->COMMIT_FEE,
            'INTEREST' => $hdDet->INTEREST,
            'TENOR' => $hdDet->TENOR,
            'CURRENCY' => $hdDet->CURRENCY,
            'ADM_FEE_CURRENCY' => $hdDet->ADM_FEE_CURRENCY,
            'AG_FEE_CURRENCY' => $hdDet->AG_FEE_CURRENCY,
            'LOAN_ACCOUNT_NUMBER' => $hdDet->LOAN_ACCOUNT_NUMBER,
            'INSTALLMENT_PERIOD' => $hdDet->INSTALLMENT_PERIOD,
            'INTEREST_PAYMENT_SCHEDULE' => $hdDet->INTEREST_PAYMENT_SCHEDULE,
            'INTEREST_PAYMENT_SCHEDULE_DATE' => $hdDet->INTEREST_PAYMENT_SCHEDULE_DATE,
            'INSTALLMENT_TYPE' => $hdDet->INSTALLMENT_TYPE,
            'UUID' => $hdDet->UUID,
            'ADD_REMARK' => $hdDet->ADD_REMARK,
            'PRE_PAYMENT_PENALTY' => $hdDet->PRE_PAYMENT_PENALTY,
            'FIRST_DATE_INTEREST_PAYMENT' => $hdDet->FIRST_DATE_INTEREST_PAYMENT,
            'DOCDATE' => $hdDet->DOCDATE,
            'MATURITY_DATE' => $hdDet->MATURITY_DATE,
            'REFER_ID' => $hdDet->REFER_ID,
            'COUNTER' => $newCounter,
            'POS' => $hdDet->POS,
            'ADDENDUM_DATE' => $hdDet->ADDENDUM_DATE,
            'PROVISI_TYPE' => $hdDet->PROVISI_TYPE,
            'AG_FEE_FAC' => $hdDet->AG_FEE_FAC,
            'PAYMENT_BANK_ACC' => $hdDet->PAYMENT_BANK_ACC
        ] ;
        $insertHd = $this->db->set($newHd)
                ->set('CREATED_BY', $USERNAME)
                ->set('CREATED_AT', 'SYSDATE', false)
                ->set('ISACTIVE', 1)
                ->set('IS_ADDENDUM', 1)
                ->set('IS_ACC', 0)
                ->insert('FUNDS_DETAIL_KI') ;
        $deactLast = $this->db->set('ISACTIVE',0)
                ->set('IS_ACC',0)
                ->set('IS_ADDENDUM',1)
                ->where(['UUID' => $hdDet->UUID,'ID'=>$hdDet->ID])
                ->update('FUNDS_DETAIL_KI'); 
        if(!$insertHd || !$deactLast) {
            return [
                'STATUS' => FALSE,
                'MESSAGE' => 'Failed update header detail'
            ] ;
        } 
        foreach($tr as $item) {
            $dup = [
                'UUID' => $item->UUID,
                'TRANCHE_NUMBER' => $item->TRANCHE_NUMBER,
                'EFFECTIVE_DATE' => $item->EFFECTIVE_DATE,
                'LOAN_ACCOUNT_NUMBER' => $item->LOAN_ACCOUNT_NUMBER,
                'LIMIT_TRANCHE' => $item->LIMIT_TRANCHE,
                'AVAIL_PERIOD_FROM' => $item->AVAIL_PERIOD_FROM,
                'AVAIL_PERIOD_TO' => $item->AVAIL_PERIOD_TO,
                'GRACE_PERIOD_FROM' => $item->GRACE_PERIOD_FROM,
                'GRACE_PERIOD_TO' => $item->GRACE_PERIOD_TO,
                'CONTRACT_NUMBER' => $item->CONTRACT_NUMBER,
                'ADD_REMARKS'       => $item->ADD_REMARKS,
                'CREATED_BY'       => $item->CREATED_BY,
                'SUB_CREDIT_TYPE' => $item->SUB_CREDIT_TYPE,
                'CURRENCY'          => $item->CURRENCY,
                'EXCHANGE_RATE'     => $item->EXCHANGE_RATE,
                'IDC'               => $item->IDC,
                'PURPOSE'           => $item->PURPOSE,
                'BANK_PORTION' => $item->BANK_PORTION
                ] ;
                
            $insertTr = $this->db->set('CREATED_AT', 'SYSDATE', false)
            ->set('COUNTER', $newCounter)
            ->set('ISACTIVE', 1)
            ->set('IS_ACC',0)
            ->set('IS_ADDENDUM', 0)
            ->set($dup)
            ->insert('FUNDS_DETAIL_KI_TRANCHE');

            $deactLast = $this->db->set('ISACTIVE',0)
            ->set('IS_ACC',0)
            ->set('IS_ADDENDUM',1)
            ->where(['UUID' => $hdDet->UUID,'ID'=>$item->ID])
            ->update('FUNDS_DETAIL_KI_TRANCHE'); 

            if(!$insertTr || !$deactLast) {
                return [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Failed update header detail'
                ] ;
            } 
        }
        return [
            'STATUS' => TRUE
        ];
    }

    public function insertUpdateTr($tr, $action, $isAcc, $USERNAME) {
        $newCounter = $tr->COUNTER + 1;
        if($action == 'disable') {
            $active = 0 ;
        }
        else if($action == 'activate') {
            $active = 1 ;
        }

        if($isAcc) {
        $dtUpdate = [
            'TRANCHE_NUMBER' => $tr->TRANCHE_NUMBER,
            'LOAN_ACCOUNT_NUMBER' => $tr->LOAN_ACCOUNT_NUMBER,
            'INSTALLMENT_PERIOD' => $tr->INSTALLMENT_PERIOD,
            'LIMIT_TRANCHE' => $tr->LIMIT_TRANCHE,
            'BANK_PORTION' => $tr->BANK_PORTION,
            'UUID' => $tr->UUID,
            'ADD_REMARKS'=> $tr->ADD_REMARKS,
            'SUB_CREDIT_TYPE' => $tr->SUB_CREDIT_TYPE,
            'CURRENCY' => $tr->CURRENCY,
            'EXCHANGE_RATE' => $tr->EXCHANGE_RATE,
            'IDC' => $tr->IDC,
            'PURPOSE' => $tr->PURPOSE,
            'ISACTIVE' => $active,
            'COUNTER' => $newCounter,
            'EFFECTIVE_DATE' => $tr->EFFECTIVE_DATE,
            'INTEREST_PERIOD_FROM' => $tr->INTEREST_PERIOD_FROM,
            'INTEREST_PERIOD_TO' => $tr->INTEREST_PERIOD_TO,
            'AVAIL_PERIOD_FROM' => $tr->AVAIL_PERIOD_FROM,
            'AVAIL_PERIOD_TO' => $tr->AVAIL_PERIOD_TO,
            'GRACE_PERIOD_FROM' => $tr->GRACE_PERIOD_FROM,
            'GRACE_PERIOD_TO' => $tr->GRACE_PERIOD_TO,
            'INSTALLMENT_PERIOD_FROM' => $tr->INSTALLMENT_PERIOD_FROM,
            'INSTALLMENT_PERIOD_TO' => $tr->INSTALLMENT_PERIOD_TO,
            'CREATED_BY' => $USERNAME,
            'CONTRACT_NUMBER' => $tr->CONTRACT_NUMBER
            ] ;
            // var_dump($dtUpdate) ; exit;
            $result = $this->db->set($dtUpdate)
            ->set(['IS_ADDENDUM' => 0 , 'IS_ACC' => 0])
            ->set('CREATED_AT', 'SYSDATE', false)
            ->insert('FUNDS_DETAIL_KI_TRANCHE');
        }
        else {
            $result = $this->db->set(['ISACTIVE' => $active])->where(['ID' => $tr->ID])->update('FUNDS_DETAIL_KI_TRANCHE') ;
        }
        return $result ;
    }

    //^ ^ ^

    // public function resetMasterBank () {
    //     $this->db->trans_begin();
    //     $listLastBankq = "select distinct fm.bank, b.fcname from funds_master fm left join bank b on b.fccode = fm.bank where credit_type = 'KI' and isactive = 1" ;
    //     $listLastBank = $this->db->query($listLastBankq)->result(); 

    //     /*
    //         List fccode supplier
    //         MANDIRI = SU0003143
    //         BNI = SU0003144
    //         BCA = SU0003196
    //         OCBC NISP = SU0003201
    //         SINAR MAS = SU0003181
    //         CIMB NIAGA = SU0003199
    //         PERMATA = SU0003195
    //         MAY VBANK = SU0003197
    //         BRi = SU0003142

    //         LIST FCCODE BANK
    //         MANDIRI = BPLT0266
    //         BNI = BPLT0267
    //         BCA = BPLT0210
    //         OCBC NISP = BPLT0191
    //         SINAR MAS = BPLT0037
    //         CIMB NIAGA = BPLT0096
    //         PERMATA = BPLT0095
    //         MAY VBANK = YCI621
    //     */
    //     try {
    //         foreach ($listLastBank as $item) {
    //             $lastBank = $item->BANK ;
    
    //             if($lastBank == 'BPLT0266'){
    //                 $dt['BANK'] = 'SU0003143';
    //             }
    //             else if ($lastBank == 'BPLT0267'){
    //                 $dt['BANK'] = 'SU0003144';
    //             }
    //             else if ($lastBank == 'BPLT0210'){
    //                 $dt['BANK'] = 'SU0003196' ;
    //             }
    //             else if ($lastBank == 'BPLT0191'){
    //                 $dt['BANK'] = 'SU0003201' ;
    //             }
    //             else if ($lastBank == 'BPLT0037'){
    //                 $dt['BANK'] = 'SU0003181' ;
    //             }
    //             else if ($lastBank == 'BPLT0096'){
    //                 $dt['BANK'] = 'SU0003199' ;
    //             }
    //             else if ($lastBank == 'BPLT0095'){
    //                 $dt['BANK'] = 'SU0003195' ;
    //             }
    //             else if ($lastBank == 'YCI621'){
    //                 $dt['BANK'] = 'SU0003197' ;
    //             }
    //             else {
    //                 continue ;
    //             }
    
    //             $result = $this->db->set($dt)->where(['BANK' => $lastBank])->update('FUNDS_MASTER') ;
    //             if(!$result) {
    //                 throw new Exception('Update Failed') ;
    //             }
    //         }
    //     }
    //     catch (Exception $ex) {
    //         $result = [
    //             'STATUS' => FALSE,
    //             'MESSAGE' => $ex->getMessage()
    //         ] ;
    //         $this->db->trans_rollback();
    //         return $result ;
    //     }
    //     if($result) {
    //         $result = [
    //             'STATUS' => TRUE,
    //             'MESSAGE' => 'Update Success'
    //         ] ;
    //         $this->db->trans_commit();
    //     }
    //     var_dump($result) ;
    //     return $result ;
    // }
}