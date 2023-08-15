<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompletionModel extends BaseModel {

   public $variable;

   public function __construct()
   {
      parent::__construct();
   }
    
   function ShowDataCompletion($param){
      $COMPANY        = $param['COMPANY'];
      $CREDIT_TYPE    = $param['CREDIT_TYPE'];
      $COND = [] ;
      $WHERE = '' ;
      if($COMPANY != null && $COMPANY != '0') 
      {
         array_push($COND, "COMPANYCODE = '$COMPANY'") ;
      }   
      if($CREDIT_TYPE != null && $CREDIT_TYPE != '0') 
      {
         array_push($COND, "CREDIT_TYPE = '$CREDIT_TYPE'") ;
      }
      $idx = 0 ;
      foreach($COND as $term) {
         if($idx <= (sizeof($COND) - 1)) {
            $WHERE.= ' AND ' ;
         }
         $WHERE .= $term ;
      } 

      $q = "SELECT A.*, A.TOTALWD - A.TOTAL_INSTALLMENT AS OUTSTANDING 
        FROM (SELECT C.ID AS COMPANYCODE,
                     C.COMPANYNAME,
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
                     B.FCCODE AS BANKCODE,
                     B.FCNAME AS BANK,
                     FM.CREDIT_TYPE,
                     FM.UUID,
                     FM.PK_NUMBER,
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
                        WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.TOTALWD
                        WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.TOTALWD
                        WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.TOTALWD
                        WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.TOTALWD
                        WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.TOTALWD
                        WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.TOTALWD
                        ELSE FDK.TOTALWD
                     END
                        AS TOTALWD,
                        COALESCE(FP.TOTAL_INSTALLMENT, 0) AS TOTAL_INSTALLMENT
                FROM FUNDS_MASTER FM
                     LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                     LEFT JOIN SUPPLIER B ON B.ID = FM.BANK
                     LEFT JOIN
                     (SELECT FA.UUID,
                             FA.SUB_CREDIT_TYPE,
                             FA.DOCDATE,
                             FA.INTEREST,
                             FA.CURRENCY,
                             FA.AMOUNT_LIMIT,
                             FA.CONTRACT_NUMBER,
                             FW.TOTALWD
                        FROM FUNDS_DETAIL_WA FA
                             LEFT JOIN
                             (SELECT *
                                FROM (  SELECT UUID,
                                               SUM (AMOUNT) TOTALWD,
                                               CONTRACT_NUMBER,
                                               WD_TYPE
                                          FROM FUNDS_WITHDRAW
                                         WHERE STATUS = '1'
                                      GROUP BY UUID,
                                               WD_TYPE,
                                               CONTRACT_NUMBER)) FW
                                ON (    FA.UUID = FW.UUID
                                    AND FW.WD_TYPE = FA.SUB_CREDIT_TYPE
                                    AND FW.CONTRACT_NUMBER =
                                           FA.CONTRACT_NUMBER)
                       WHERE     FA.IS_ACC = '1'
                             AND FA.ISACTIVE = '1'
                             AND FW.TOTALWD > 0) FDW
                        ON FDW.UUID = FM.UUID
                     LEFT JOIN
                     (SELECT DISTINCT FR.UUID,
                                      FR.SUB_CREDIT_TYPE,
                                      FR.DOCDATE,
                                      FR.INTEREST,
                                      FR.CURRENCY,
                                      FR.AMOUNT_LIMIT,
                                      FR.CONTRACT_NUMBER,
                                      FW.TOTALWD
                        FROM FUNDS_DETAIL_RK FR
                             LEFT JOIN
                             (  SELECT UUID, SUM (AMOUNT) TOTALWD, WD_TYPE
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
                             FR.SUB_CREDIT_TYPE,
                             FR.DOCDATE,
                             FR.INTEREST,
                             FDR.CURRENCY,
                             FDR.AMOUNT_LIMIT,
                             FDR.CONTRACT_NUMBER,
                             FW.TOTALWD
                        FROM FUNDS_DETAIL_KI FR
                             LEFT JOIN
                             (SELECT UUID,
                                     LIMIT_TRANCHE AMOUNT_LIMIT,
                                     CONTRACT_NUMBER,
                                     TRANCHE_NUMBER,
                                     CURRENCY,
                                     ISACTIVE
                                FROM FUNDS_DETAIL_KI_TRANCHE
                                WHERE IS_COMPLETE IS NULL) FDR
                                ON FR.UUID = FDR.UUID AND FDR.ISACTIVE = 1
                             LEFT JOIN
                             (  SELECT UUID, SUM (DDOWN_AMT) TOTALWD, TRANCHE_NUMBER
                                  FROM FUNDS_WD_KI_TRANCHE
                                 WHERE STATUS = '1'
                              GROUP BY UUID, TRANCHE_NUMBER) FW
                                ON (    FR.UUID = FW.UUID
                                    AND FDR.TRANCHE_NUMBER = FW.TRANCHE_NUMBER)
                       WHERE     FR.IS_ACC = '1'
                             AND FR.ISACTIVE = '1'
                             AND FW.TOTALWD > 0) FDK
                        ON FDK.UUID = FM.UUID
                  LEFT JOIN (
                      SELECT SUM(INSTALLMENT) AS TOTAL_INSTALLMENT, CONTRACT_NUMBER, PK_NUMBER FROM FUNDSPAYMENT WHERE IS_PAYMENT = 1 GROUP BY CONTRACT_NUMBER, PK_NUMBER
                  ) FP ON (FP.CONTRACT_NUMBER = FDR.CONTRACT_NUMBER OR FP.CONTRACT_NUMBER = FDK.CONTRACT_NUMBER OR FP.CONTRACT_NUMBER = FDW.CONTRACT_NUMBER)
               WHERE FM.IS_ACC = '1' AND FM.ISACTIVE = '1') A
       WHERE TOTALWD > 0 ".$WHERE;

      $result = $this->db->query($q)->result();
      // var_dump($this->db->last_query());exit;
      $this->db->close();
      return $result;
   }

   function GetIDFundsPayment($param) {
      $UUID              = $param['UUID'];
      $COMPANYCODE       = $param['COMPANYCODE'];
      $CURRENCY          = $param['CURRENCY'];
      $CONTRACTNUMBER    = $param['CONTRACTNUMBER'];
      $BANKCODE          = $param['BANKCODE'];
      $CREDIT_TYPE       = $param['CREDIT_TYPE'];
      $wdtype            = $param['SUB_CREDIT_TYPE'];
      $DOCDATE           = $param['DOCDATE'];
      $OUTSTANDING       = $param['OUTSTANDING'];
      
      $SQL= "SELECT UUID, PK_NUMBER, CONTRACT_NUMBER,
                     OUTSTANDING,
                     TOTAL_PAID,
                     OUTSTANDING - TOTAL_PAID AS AMOUNT_COMPLETION,
                     CASE 
                        WHEN KI_PENALTY IS NOT NULL THEN KI_PENALTY
                        WHEN WA_PENALTY IS NOT NULL THEN WA_PENALTY
                        WHEN RK_PENALTY IS NOT NULL THEN KI_PENALTY
                        ELSE 0
                        END
                     AS PRE_PAYMENT_PENALTY,
                     TOTAL_TENOR,
                     CASE
                  WHEN INTEREST IS NULL THEN 0
                  ELSE INTEREST
                  END
                  AS INTEREST,
                  CASE
                  WHEN IDC IS NULL THEN 0
                  ELSE IDC
                  END
                  AS IDC,
                  CASE
                  WHEN IDC_INTEREST IS NULL THEN 0
                  ELSE IDC_INTEREST
                  END
                  AS IDC_INTEREST
                     FROM
               (SELECT 
                  FM.UUID,
                     FM.PK_NUMBER,
                     FP.TOTAL_TENOR,
                     CASE 
                        WHEN FM.CREDIT_TYPE = 'KI' THEN FDKIT.CONTRACT_NUMBER
                        WHEN FM.CREDIT_TYPE = 'KMK' THEN
                           CASE 
                                 WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FWA.CONTRACT_NUMBER
                                 WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FRK.CONTRACT_NUMBER
                                 WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FRK.CONTRACT_NUMBER
                                 ELSE ''
                                 END
                        ELSE ''
                        END
                        AS CONTRACT_NUMBER,
                     CASE
                        WHEN FM.SUB_CREDIT_TYPE = 'KI' THEN
                           CASE
                                 WHEN FWKI.OUTSTANDING IS NULL THEN FDKIT.LIMIT_TRANCHE
                                 ELSE FWKI.OUTSTANDING
                                 END
                        WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN
                           CASE 
                                 WHEN FWA.SUB_CREDIT_TYPE = 'WA'
                                    THEN CASE 
                                             WHEN FW.OUTSTANDING IS NULL THEN FWA.AMOUNT_LIMIT
                                             ELSE FW.OUTSTANDING
                                             END
                                 WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AR'
                                    THEN CASE 
                                             WHEN FW.OUTSTANDING IS NULL THEN FWA.AMOUNT_LIMIT
                                             ELSE FW.OUTSTANDING
                                             END
                                 WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AP'
                                    THEN CASE 
                                             WHEN FW.OUTSTANDING IS NULL THEN FWA.AMOUNT_LIMIT
                                             ELSE FW.OUTSTANDING
                                             END
                                 ELSE 0
                                 END
                        ELSE 
                           CASE 
                                 WHEN FW.OUTSTANDING IS NULL THEN FRK.AMOUNT_LIMIT
                                 ELSE FW.OUTSTANDING
                                 END
                        END
                        AS OUTSTANDING,
                        CASE 
                           WHEN FP.TOTAL_PAID IS NOT NULL THEN FP.TOTAL_PAID
                           ELSE 0
                           END
                        AS TOTAL_PAID,
                        FKI.PRE_PAYMENT_PENALTY AS KI_PENALTY,
                        FRK.PRE_PAYMENT_PENALTY AS RK_PENALTY,
                        FWA.PRE_PAYMENT_PENALTY AS WA_PENALTY,
                        FP.INTEREST,
                        FP.IDC,
                        FP.IDC_INTEREST
               FROM FUNDS_MASTER FM 
               LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FDKIT ON FDKIT.UUID = FM.UUID AND FDKIT.ISACTIVE = 1
               LEFT JOIN FUNDS_DETAIL_KI FKI ON FKI.UUID = FM.UUID AND FKI.ISACTIVE = 1 
               LEFT JOIN FUNDS_DETAIL_RK FRK ON FRK.UUID = FM.UUID AND FRK.ISACTIVE = 1
               LEFT JOIN FUNDS_DETAIL_WA FWA ON FWA.UUID = FM.UUID AND FWA.ISACTIVE = 1
               LEFT JOIN (SELECT COUNT(FP.ID) AS TOTAL_TENOR,
                           SUM (FP.INSTALLMENT)
                                 AS TOTAL_PAID,
                              contract_number,
                              CREDIT_TYPE,
                              SUM (FP.INTEREST) AS INTEREST,
                              SUM (FP.IDC_INSTALLMENT) AS IDC,
                              SUM (FP.IDC_INTEREST) AS IDC_INTEREST,
                              FP.PK_NUMBER,
                              FP.IS_PAYMENT
                           FROM FUNDSPAYMENT FP 
                           GROUP BY CREDIT_TYPE, PK_NUMBER, IS_PAYMENT, CONTRACT_NUMBER) FP 
                           ON FP.CONTRACT_NUMBER = CASE 
                                                WHEN FM.SUB_CREDIT_TYPE = 'KI' THEN FDKIT.CONTRACT_NUMBER
                                                WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN FWA.CONTRACT_NUMBER
                                                WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FWA.CONTRACT_NUMBER
                                                WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FWA.CONTRACT_NUMBER
                                                ELSE FRK.CONTRACT_NUMBER
                                                END
                           AND IS_PAYMENT = '1'
               LEFT JOIN (SELECT SUM(FWKI.DDOWN_AMT) AS OUTSTANDING,TRANCHE_NUMBER, UUID
                                 FROM FUNDS_WD_KI_TRANCHE FWKI WHERE COUNTER IS NULL GROUP BY TRANCHE_NUMBER, UUID) FWKI
                        ON FWKI.TRANCHE_NUMBER = FDKIT.TRANCHE_NUMBER AND FWKI.UUID = FDKIT.UUID
               LEFT JOIN (SELECT FW.AMOUNT AS OUTSTANDING, FW.CONTRACT_NUMBER
                           FROM FUNDS_WITHDRAW FW) FW ON FW.CONTRACT_NUMBER = (CASE
                                WHEN FDKIT.CONTRACT_NUMBER IS NOT NULL THEN FDKIT.CONTRACT_NUMBER
                                WHEN FRK.CONTRACT_NUMBER IS NOT NULL THEN FRK.CONTRACT_NUMBER
                                WHEN FWA.CONTRACT_NUMBER IS NOT NULL THEN FWA.CONTRACT_NUMBER
                                ELSE FP.CONTRACT_NUMBER
                                END)
               WHERE FM.ISACTIVE = '1') 
               WHERE UUID = '$UUID' AND CONTRACT_NUMBER = '$CONTRACTNUMBER'";

      

      $result = $this->db->query($SQL)->row();
      // var_dump($this->db->last_query());exit;
      $this->db->close();
      return $result;
   }

   public function SaveCompletion($param, $Location) {
      ini_set('Display_errors','On');
      $UUID              = $param['UUID'];
      $COMPANYCODE       = $param['COMPANYCODE'];
      $CURRENCY          = $param['CURRENCY'];
      $CONTRACTNUMBER    = $param['CONTRACTNUMBER'];
      $BANKCODE          = $param['BANKCODE'];
      $CREDIT_TYPE       = $param['CREDIT_TYPE'];
      $wdtype            = $param['SUB_CREDIT_TYPE'];
      $DOCDATE           = $param['DOCDATE'];
      $OUTSTANDING       = intval(preg_replace("/[^\d\.\-]/","",$param['OUTSTANDING']));
      $AMOUNT_COMPLETION = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_COMPLETION']));
      $TOTAL_INSTALLMENT = intval(preg_replace("/[^\d\.\-]/","",$param['TOTAL_INSTALLMENT']));
      $AMOUNT_PENALTY    = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PENALTY']));
      $AMOUNT_INTEREST   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_INTEREST']));
      $AMOUNT_IDC        = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_IDC']));
      $AMOUNT_IDC_INTEREST   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_IDC_INTEREST']));
      $USERNAME          = $param['USERNAME'];
      $LASTUPDATE        = $param['DATE_COMPLETION'];
      $datetim           = new DateTime($LASTUPDATE);
      $date = $datetim->format('Y-n-j');
      $datepay = explode('-', $date) ;
      $monthcomp = intval($datepay[1]);
      $yearcomp = intval($datepay[0]);

      $pknumberq = "SELECT FM.PK_NUMBER FROM FUNDS_DETAIL_KI_TRANCHE FDKIT LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FDKIT.UUID WHERE FDKIT.CONTRACT_NUMBER = ?" ;
      $pknumber = $this->db->query($pknumberq, $CONTRACTNUMBER)->row();
      $COMP_ID = $pknumber->PK_NUMBER.'$'.$CONTRACTNUMBER.'$'.$date.'$COMPLETE' ;

      //check if completion date outofrange
      $lastpaydateq = "SELECT MAX(END_PERIOD) AS START_PERIOD from FUNDSPAYMENT WHERE CONTRACT_NUMBER = ?" ;
      $lastpaydate = $this->db->query($lastpaydateq, $CONTRACTNUMBER)->row();
      $maxpay = explode('/',$lastpaydate->START_PERIOD);
      // var_dump($COMP_ID); exit;
        try {
         $maxmonth = $maxpay[0] ;
         $maxyear = $maxpay[2] ;
         if($yearcomp > $maxyear) {
            throw new Exception('Comp Date Exceeded') ;
         }
         else {
            if($yearcomp == $maxyear) {
               if($monthcomp > $maxmonth) {
                  throw new Exception('Comp Date Exceeded') ;
               }
            }
         }
            $this->db->trans_begin();
            $result = FALSE;
          
            $SQL = "SELECT * FROM FUNDS_COMPLETION WHERE CONTRACT_NUMBER = '".$CONTRACTNUMBER."'";
            $CEK = $this->db->query($SQL)->row();
            // var_dump($CEK);exit;

            if ($CEK != null) {
               throw new Exception('Data Already Paid !!');
            } else {
               //throw new Exception('Data Not Null !!');
               $INSERT = ['UUID'=>$UUID,
                           'CONTRACT_NUMBER'=>$CONTRACTNUMBER,
                           'COMPANY'=>$COMPANYCODE,
                           'BANK'=>$BANKCODE,
                           'CREDIT_TYPE'=>$CREDIT_TYPE,
                           'SUB_CREDIT_TYPE'=>$wdtype,
                           'CURRENCY'=>$CURRENCY,
                           'OUTSTANDING'=>$OUTSTANDING,
                           'AMOUNT_WITH_PENALTY'=>$AMOUNT_PENALTY,
                           'AMOUNT_COMPLETION'=>$AMOUNT_COMPLETION,
                           'AMOUNT_INTEREST'=>$AMOUNT_INTEREST,
                           'AMOUNT_IDC'=>$AMOUNT_IDC,
                           'AMOUNT_IDC_INTEREST'=>$AMOUNT_IDC_INTEREST,
                           'COMPLETION_ID'=>$COMP_ID,
                           'FCENTRY'=>$USERNAME,
                           'FCIP'=>$Location
               ];
               $result = $this->db->set('LASTUPDATE', "TO_DATE('" . $param['DATE_COMPLETION'] . "','mm/dd/yyyy')", false)//('LASTUPDATE', "SYSDATE", false)
               ->set('DOCDATE', "TO_DATE('" . $param['DOCDATE'] . "','yyyy-mm-dd')", false);
               $result = $result->set($INSERT)->insert('FUNDS_COMPLETION');
               //update all existed payment to paid 
               $amountpaidq = "SELECT PERIOD_MONTH, PERIOD_YEAR, INSTALLMENT, INTEREST, IDC, IDC_INTEREST, IDC_INSTALLMENT, GID, DOCDATE, FCIP, PERIOD FROM FUNDSPAYMENT WHERE PK_NUMBER = ? AND CONTRACT_NUMBER = ? AND (IS_PAYMENT IS NULL OR IS_PAYMENT = '0') ORDER BY START_PERIOD" ;
               $amountpaid = $this->db->query($amountpaidq, array($pknumber->PK_NUMBER, $CONTRACTNUMBER))->result();
               $payid = $pknumber->PK_NUMBER.'$'.$CONTRACTNUMBER.'$'.$date;
               $passAccum = true ;
               foreach($amountpaid as $key => $amount) {
                  $monthpred = intval($amount->PERIOD_MONTH) ;
                  $yearpred = intval($amount->PERIOD_YEAR) ;
                  if($monthcomp < $monthpred && $yearcomp < $yearpred && $passAccum) {
                     // var_dump($TOTAL_INSTALLMENT, $amount->GID); 
                     $updatepay = $this->db->set('PAY_ID', $payid.'-'.$key)
                              ->set('IS_PAYMENT', '1')
                              ->set('PAYMENT_DATE',"TO_DATE('$LASTUPDATE', 'mm-dd-yyyy')", false)
                              ->set('INSTALLMENT', $TOTAL_INSTALLMENT)
                              ->set('IDC_INSTALLMENT', $AMOUNT_IDC)
                              ->where('GID', $amount->GID)
                              ->update('FUNDSPAYMENT');
                     $passAccum = false ;
                  }
                  // else if($monthcomp == $monthpred && $yearcomp == $yearpred) {
                  //    $dt = [
                  //       'CONTRACT_NUMBER' => $CONTRACTNUMBER,
                  //       'PK_NUMBER' => $pknumber,
                  //       'COMPANY' => $COMPANYCODE,
                  //       'CREDIT_TYPE' => 'KI',
                  //       'DOCDATE' => $amount->DOCDATE,
                  //       'FCENTRY' => $USERNAME,
                  //       'FCIP' => $amount->FCIP,
                  //       'PERIOD_MONTH' => $monthcomp,
                  //       'PERIOD_YEAR' => $yearcomp,
                  //       'PAY_ID' => $payid,
                  //       'IS_PAYMENT' => '1',
                  //       'PERIOD' => $amount->PERIOD,
                  //       'INSTALLMENT' => $TOTAL_INSTALLMENT,
                  //       'INTEREST' =>$AMOUNT_INTEREST,
                  //       'IDC_INTEREST' => $AMOUNT_IDC_INTEREST,
                  //       'GID' => $amount->GID,
                  //       'UUID' => $amount->UUID,
                  //       'IDC_INSTALLMENT' => $AMOUNT_IDC
                  //    ] ;
                  //    $updatepay = $this->db->set($dt)->set('')
                  // }
                  else {
                     $updatepay = $this->db->set('PAY_ID', $payid.'-'.$key)
                                 ->set('IS_PAYMENT', '1')
                                 ->set('PAYMENT_DATE',"TO_DATE('$LASTUPDATE', 'mm-dd-yyyy')", false )
                                 ->set('INSTALLMENT', 0)
                                 ->set('IDC_INSTALLMENT',0)
                                 ->where('GID', $amount->GID)
                                 ->update('FUNDSPAYMENT');
                  }
                  if(!$updatepay) {
                     throw new Exception("Failed Update Remaining Installment") ;
                  }
               }
               // exit;
               // foreach($amountpaid as $amount) {
               //    dtUpPay = [] ;
               //    $monthdt = $amount->PERIOD_MONTH ;
               //    $yeardt = $amount->PERIOD_YEAR ;
               //    $is_inIntervalMTH = $monthcomp > $monthdt && $monthcomp 
               //    $updatepay = $this->db->set('PAY_ID', $payid)
               //                ->set('IS_PAYMENT', 1)
               //                ->where('GID', $amount->GID)
               //                ->update('FUNDSPAYMENT');
               // }
               //header master
               $fmq = "SELECT FM.COMPANY,FM.BUNIT,FM.PK_NUMBER,FM.CREDIT_TYPE,FM.SUB_CREDIT_TYPE,FM.VENDOR,FDKI.CURRENCY FROM FUNDS_MASTER FM LEFT JOIN FUNDS_DETAIL_KI FDR ON FDR.UUID = FM.UUID LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FDKI ON FM.UUID = FDKI.UUID WHERE FM.UUID = '".$param['UUID']."' AND FDKI.CONTRACT_NUMBER = '".$param['CONTRACTNUMBER']."'" ;
               $fm = $this->db->query($fmq)->row();
               // var_dump($fm); exit;
               //insert transaction to cf_transaction
               $cf = [
                  "DEPARTMENT" => 'BANK-RELATION',
                  "COMPANY" => $fm->COMPANY,
                  "BUSINESSUNIT" => $fm->BUNIT,
                  "DOCNUMBER" => $COMP_ID,
                  "DOCTYPE" => $fm->CREDIT_TYPE,
                  "VENDOR" => $fm->VENDOR,
                  "CURRENCY" => $fm->CURRENCY,
                  "EXTSYS" => 'SAPHANA',
                  "VAT" => "",
                  "RATE" => 1,
                  "REMARK" => "",
                  "AMOUNT_INCLUDE_VAT" => $AMOUNT_COMPLETION,
                  "TOTAL_BAYAR" => $AMOUNT_COMPLETION,
                  "AMOUNT_PPH" => 0,
                  "FCEDIT" => $param['USERNAME'],
                  "FCIP" => $Location
               ] ;
               $Data["ID"] = $this->uuid->v4();
               $cf["ID"]   = $Data["ID"];
               $cf["ISACTIVE"] = "TRUE";
               $cf["FCENTRY"] = $param['USERNAME'];
               // var_dump($this->oracle_date('date')); exit;
               $resultcf = $this->db->set('LASTUPDATE', "SYSDATE", false)
               ->set('DOCDATE', "TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy')", false)
               ->set('DUEDATE', "TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy')", false)
               ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
               ->set($cf)
               ->insert("CF_TRANSACTION");
               // var_dump($resultcf); exit;
               if(!$resultcf) {
                  $result = false;
               }

               //deactivate tranche
               if($result) {
                  $deact = $this->db
                           ->set('IS_COMPLETE', 1)
                           ->where([
                              'UUID' => $param['UUID'],
                              'CONTRACT_NUMBER' => $param['CONTRACTNUMBER'],
                              'ISACTIVE' =>1
                           ])
                           ->update('FUNDS_DETAIL_KI_TRANCHE');
                  $result = $deact;
               }
               if($result) {
                  //check if all tranche is deactivated, pk will be deactivate
                  $checkTr = $this->db->select('CONTRACT_NUMBER')->where(
                     "UUID = '{$param['UUID']} AND IS_COMPLETE IS NULL'"
                  )->get('FUNDS_DETAIL_KI_TRANCHE')->result();
                  if($checkTr == null) {
                     $deactPK = $this->db->set('IS_COMPLETE', 1)
                                 ->where([
                                    'UUID' => $param['UUID'],
                                    'ISACTIVE' => '1'
                                 ])
                                 ->update('FUNDS_DETAIL_KI');
                     $deactMst = $this->db->set('IS_COMPLETE', 1)
                                 ->where("UUID = '{$param['UUID']} AND IS_COMPLETE IS NULL'")
                                 ->update('FUNDS_MASTER');
                     if($deactPK && $deactMst) {
                        $result = true ;
                     }
                     else {
                        $result = false ;
                     }
                  }
               }
               // var_dump($this->db->last_query());exit;
               // $result1 = $this->db->set('ISACTIVE',"0", false)
               //                      ->where(['UUID' => $param['UUID']])
               //                      ->update('FUNDS_MASTER');
               //var_dump($this->db->last_query());exit;
               //var_dump($result1);exit;
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

   public function GetRemainingInterestByDate ($param) {
      $DATE = $param['DATE_COMPLETION'] ;
      $CONTRACT_NUMBER = $param['CONTRACT_NUMBER'];
      
      // $takeintq = "SELECT CONTRACT_NUMBER, SUM(INTEREST) AS REMAINING_INTEREST, SUM(IDC) AS REMAIN_IDC, SUM(IDC_INTEREST) AS REMAIN_IDCINT FROM (
      //    SELECT * FROM FUNDSPAYMENT WHERE CONTRACT_NUMBER = '".$CONTRACT_NUMBER."' AND END_PERIOD < TO_DATE('".$DATE."', 'MM-DD-YY') AND IS_PAID IS NULL
      // ) GROUP BY CONTRACT_NUMBER" ;
      // $takeint = $this->db->query($takeintq)->row();

      $takeintq = "SELECT SUM (INTEREST) AS REMAINING_INTEREST,
                                SUM (IDC_INTEREST) AS REMAIN_IDCINT,
                                COUNT (FR.ID) AS REMAIN_COUNT
                        FROM FUNDSPAYMENT FR
                        LEFT JOIN FUNDS_MASTER FM ON FR.UUID = FM.UUID
                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FKI ON FM.UUID = FKI.UUID AND FR.CONTRACT_NUMBER = FKI.CONTRACT_NUMBER AND FKI.ISACTIVE = '1'
                        WHERE     FKI.CONTRACT_NUMBER = '".$CONTRACT_NUMBER."'
                                AND END_PERIOD > ( CASE 
                                                      WHEN ( SELECT DISTINCT 1 FROM FUNDSPAYMENT WHERE (IS_PAID = 1 OR IS_PAYMENT = 1) AND CONTRACT_NUMBER = '".$CONTRACT_NUMBER."' ) = 1 THEN 
                                                      (     
                                                         SELECT END_PERIOD
                                                            FROM FUNDSPAYMENT FP
                                                                     LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FKI
                                                                     ON     FP.CONTRACT_NUMBER = FKI.CONTRACT_NUMBER
                                                                        AND ISACTIVE = 1
                                                            WHERE FP.CONTRACT_NUMBER = '".$CONTRACT_NUMBER."' AND (IS_PAID = 1 OR IS_PAYMENT = 1)
                                                         ORDER BY PERIOD DESC
                                                         FETCH FIRST 1 ROWS ONLY
                                                      )
                                                      ELSE (SELECT EFFECTIVE_DATE FROM FUNDS_DETAIL_KI_TRANCHE WHERE CONTRACT_NUMBER = '".$CONTRACT_NUMBER."' AND ISACTIVE = '1')
                                                      END
                                                )
                                AND END_PERIOD < TO_DATE('".$DATE."', 'MM-DD-YYYY')
                                AND FR.UUID = FKI.UUID AND FR.CONTRACT_NUMBER = FKI.CONTRACT_NUMBER" ;
      $takeint = $this->db->query($takeintq)->row();

      $this->db->close();
      return $takeint ;
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

}