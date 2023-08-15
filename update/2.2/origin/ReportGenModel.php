<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportGenModel extends BaseModel {

    public $variable ;

    public function __construct() {
        parent::__construct();
    }

    public function SaveReportKMK($param, $Location) {
        // ini_set('display_errors', 'On');
        // exit;
        try {
            $this->db->trans_begin();
            $rearr = $this->db->where('PK_NUMBER', $param['PK_NUMBER'])->delete('FUNDS_KMK_REPORT');

            //header
            $headerdetq = "SELECT A.*, (TO_CHAR (A.DOCDATE, 'dd')) AS STARTDATE,
            (TO_CHAR (A.DOCDATE, 'mm')) AS STARTMONTH,
            (TO_CHAR (A.DOCDATE, 'yyyy')) AS STARTYEAR, TO_CHAR(A.DOCDATE, 'fmMM/DD/YYYY') AS DDATE_COM FROM
            (
                SELECT FM.UUID, FM.PK_NUMBER, FM.COMPANY, 
                    CASE
                        WHEN FM.SUB_CREDIT_TYPE = 'RK' OR FM.SUB_CREDIT_TYPE = 'BD' THEN FRK.DOCDATE
                        WHEN FM.SUB_CREDIT_TYPE = 'WA' THEN
                            (
                                CASE
                                    WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FWA.DOCDATE
                                    WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FWA.DOCDATE
                                    ELSE FWA.DOCDATE
                                END
                            )
                        ELSE FRK.DOCDATE
                        END AS DOCDATE,
                        CASE
                            WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FWA.SUB_CREDIT_TYPE
                            WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FWA.SUB_CREDIT_TYPE
                            ELSE FM.SUB_CREDIT_TYPE
                        END AS SUB_CREDIT_TYPE,
                        CASE
                            WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FWA.CONTRACT_NUMBER
                            WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FWA.CONTRACT_NUMBER
                            WHEN FWA.SUB_CREDIT_TYPE = 'WA'         THEN FWA.CONTRACT_NUMBER
                            ELSE FRK.CONTRACT_NUMBER
                        END AS CONTRACT_NUMBER,
                        CASE
                            WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FWA.TENOR
                            WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FWA.TENOR
                            WHEN FWA.SUB_CREDIT_TYPE = 'WA'         THEN FWA.TENOR
                            ELSE FRK.TENOR
                        END AS TENOR,
                        CASE
                            WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN (TO_CHAR (FWA.FIRST_DATE_INTEREST_PAYMENT, 'dd'))
                            WHEN FWA.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN (TO_CHAR (FWA.FIRST_DATE_INTEREST_PAYMENT, 'dd'))
                            WHEN FWA.SUB_CREDIT_TYPE = 'WA'         THEN (TO_CHAR (FWA.FIRST_DATE_INTEREST_PAYMENT, 'dd'))
                            ELSE (TO_CHAR (FRK.FIRST_DATE_INTEREST_PAYMENT, 'dd'))
                        END AS FIRST_DATE
                    FROM FUNDS_MASTER FM 
                    LEFT JOIN FUNDS_DETAIL_RK FRK ON FM.UUID = FRK.UUID AND FRK.ISACTIVE = 1
                    LEFT JOIN FUNDS_DETAIL_WA FWA ON FM.UUID = FWA.UUID AND FWA.ISACTIVE = 1
            ) A
            WHERE SUB_CREDIT_TYPE <> 'KMK_SCF_AP' AND SUB_CREDIT_TYPE <> 'KMK_SCF_AR' AND A.UUID = '".$param['UUID']."'" ;

            $headerdet = $this->db->query($headerdetq)->row();
            $STARTDATE = $headerdet->STARTDATE ;
            $STARTMONTH = $headerdet->STARTMONTH ;
            $STARTYEAR = $headerdet->STARTYEAR ;
            // var_dump($this->db->last_query()); exit;

            //get latest transaction in SAP API

            //index for interest, installment, and withdrawal
            $idx_interest = 1 ;
            $idx_payment = 0 ;
            $idx_withdr = 0 ;
            $notOnPay = true ;
            $notOnWD = true ;
            $skippay = false ;
            
            //interest
            if($headerdet->SUB_CREDIT_TYPE == 'RK' || $headerdet->SUB_CREDIT_TYPE == 'BD' || $headerdet->SUB_CREDIT_TYPE == 'TL') {
                $interestq = "SELECT FM.PK_NUMBER, FRK.INTEREST, (TO_CHAR(FRK.DOCDATE, 'fmMM/DD/YYYY')) AS DOCDATE, FRK.MATURITY_DATE FROM FUNDS_MASTER FM 
                                    LEFT JOIN FUNDS_DETAIL_RK FRK ON FRK.UUID = FM.UUID
                                    WHERE FM.UUID = '".$param['UUID']."'" ;
            } else {
                $interestq = "SELECT FM.PK_NUMBER, FWA.INTEREST, (TO_CHAR(FWA.DOCDATE, 'fmMM/DD/YYYY')) AS DOCDATE, FWA.MATURITY_DATE FROM FUNDS_MASTER FM 
                                    LEFT JOIN FUNDS_DETAIL_WA FWA ON FWA.UUID = FM.UUID
                                    WHERE FM.UUID = '".$param['UUID']."'" ;
            }

            $interest = $this->db->query($interestq)->result();

            //withdraw
            $withdrawq = "SELECT FM.PK_NUMBER, TO_CHAR(FWD.VALUE_DATE, 'fmMM/DD/YYYY') VALUE_DATE, FWD.AMOUNT  FROM FUNDS_WITHDRAW FWD
                                LEFT JOIN FUNDS_MASTER FM ON FWD.UUID = FM.UUID
                                WHERE FM.UUID = '".$param['UUID']."'
                                ORDER BY VALUE_DATE ASC" ;
            $withdraw = $this->db->query($withdrawq)->result();

            //payment
            $paymentq = "SELECT TO_CHAR(END_PERIOD, 'fmMM/DD/YYYY') AS PAYMENT_DATE, INSTALLMENT, GID FROM FUNDSPAYMENT WHERE PK_NUMBER = '".$param['PK_NUMBER']."' AND CONTRACT_NUMBER = '".$param['CONTRACT_NUMBER']."' ORDER BY END_PERIOD ASC " ;
            $payment = $this->db->query($paymentq)->result();

            if($headerdet->SUB_CREDIT_TYPE == 'RK') {
                $latestq = "SELECT TO_CHAR(MIN(POSTING_DATE), 'dd') AS STARTDATE, TO_CHAR(MIN(POSTING_DATE), 'mm') AS STARTMONTH, TO_CHAR(MIN(POSTING_DATE), 'yyyy') AS STARTYEAR FROM (SELECT POSTING_DATE FROM FAGLFLEXA_REC UNION SELECT POSTING_DATE FROM BSIKBSAK_REC) " ;
                $latest = $this->db->query($latestq)->row();
                $STARTDATE = $latest->STARTDATE ;
                $STARTMONTH = $latest->STARTMONTH ;
                $STARTYEAR = $latest->STARTYEAR ;
                $rkpar = [
                    'PK_NUMBER' => $headerdet->PK_NUMBER,
                    'CONTRACT_NUMBER' => $headerdet->CONTRACT_NUMBER
                ] ;
                list($payment, $withdraw) = $this->getPayAndWDRK($rkpar) ;
            }
            // echo "<pre>" ;
            // var_dump("Payment :") ;
            // var_dump($payment) ;
            // var_dump("Withdraw :") ;
            // var_dump($withdraw) ;
            if($payment == null) {
                $skippay = true ;
            }
            // var_dump($skippay); exit ;
            if(!$skippay) {
                //when transaction date is earlier than docdate existed, move idx start
                do {
                    $datedoc = $headerdet->DDATE_COM ;
                    $c_datedoc = new DateTime($datedoc) ;
                    $datepay = $payment[$idx_payment]->PAYMENT_DATE ;
                    $c_datepay = new DateTime($datepay);
                    $datewd= $withdraw[$idx_withdr]->VALUE_DATE ;
                    $c_datewd = new DateTime($datewd);
                    if($c_datedoc > $c_datepay) {
                        $idx_payment += 1;
                    } else {
                        $notOnPay = false ;
                    }
                    if($c_datedoc > $c_datewd) {
                        $idx_withdr += 1 ;
                    } else {
                        $notOnWD = false ;
                    }
    
                } while ($notOnPay || $notOnWD) ;

                $max_payment = sizeof($payment) - 1;
               
            }

            //saving report data
            $passdate = 0 ;
            $CONTRACT_NUM = $headerdet->CONTRACT_NUMBER ;
            $COMPANY = $headerdet->COMPANY ;
            $DOCDATE = $headerdet->DOCDATE ;
            $CONTRACT_NUM = $headerdet->CONTRACT_NUMBER ;
            $CREDIT_TYPE = $headerdet->SUB_CREDIT_TYPE;

            //len of params
            $max_interest = sizeof($interest) ;
            $max_withdraw = sizeof($withdraw) - 1;

            //amount
            $withdrawal_amt = 0;
            $payment_amt = 0;
            $withdrawal_bfr = 0 ;
            $interest_payment = 0;

            //period
            $period = 1;
            $startmonth = $STARTMONTH;
            $startyear = $STARTYEAR; 
            $startdate = $STARTDATE;
            $PAY_DATE = $headerdet->FIRST_DATE;

            $IDX = ((int) $headerdet->TENOR) * 2 - 1 ;
            $result = false ;
            // var_dump($IDX);
            for($x = 0 ; $x < $IDX ; $x++) {
                //GID
                $gid = null ;

                //flag
                $withdr_onintrv = false ;
                $payment_onintrv = false;

                $payment_amt = 0 ;
                $withdrawal_db = 0;
                $withdrawal_bfr = $withdrawal_amt;
                // Hitung tanggal periodenya
                // check terhadap : 
                    // - Withdrawal
                    // - Perubahan Interest
                    // - Payment request

                if ($x == 0) {
                    $pardate = $startdate ;
                    $parmonth = $startmonth ;
                    $paryear = $startyear ;
                }

                //take date format mm/dd/yyyy
                
                $dateStart = $parmonth."/".$pardate."/".$paryear ;
                $date1 = $paryear.'-'.$parmonth.'-'.$pardate;
                $c_dateStart = new DateTime($dateStart);
                // var_dump($withdraw);
                
                // make END_PERIOD
                if ($pardate < (int) $PAY_DATE){
                    //interval of before the payment schedule
                    $nextdate = $PAY_DATE;
                    $nextmonth = $parmonth ;
                    $nextyear = $paryear;
                    
                    if($nextmonth == 2) {
                        if($nextdate >= 29) {
                            $lastdatethatyear = new DateTime("last day of $nextyear-2");
                            $nextdate = $lastdatethatyear->format('d');
                        }
                    }
                    else{
                    if($nextdate > 30) {
                        $lastdatethatmonth = new DateTime("last day of $nextyear-$nextmonth");
                        $nextdate = $lastdatethatmonth->format('d');
                    }}
                    
                } else {
                    //interval after the payment schedule until the first day of next month
                    $nextdate = 1 ;
                    $nextmonth = $parmonth + 1 ;
                    $nextyear = $paryear ;
                    if ($nextmonth > 12) {
                        $nextmonth = 1 ;
                        $nextyear = $paryear + 1 ;
                    }
                }

                //set date end
                $dateEnd = $nextmonth."/".$nextdate."/".$nextyear ;
                
                $c_dateEnd = new DateTime($dateEnd);
                
                if($withdraw != null && $idx_withdr <= $max_withdraw) {
                    $date_wd = $withdraw[$idx_withdr]->VALUE_DATE ;
                    $dateWithdraw = new DateTime($date_wd) ;
                    if($dateWithdraw <= $c_dateEnd) {
                        $withdr_onintrv = true ;
                    }
                }
                if($payment != null && $idx_payment <= $max_payment) {
                    $date_pay = $payment[$idx_payment]->PAYMENT_DATE ;
                    $datePayment = new DateTime($date_pay) ;
                    if($datePayment <= $c_dateEnd) {
                        $payment_onintrv = true ;
                    }
                }

                //assign withdrawal before 
                 // rules :
                 // - Payment or Withdrawal maybe in the same interval
                 // - decide which one come first, set it as the end date
                if($withdr_onintrv && $payment_onintrv) {
                    $IDX++;
                    if($dateWithdraw < $datePayment ) {
                        $dateEnd = $date_wd ;
                        $withdrawal_db = $withdraw[$idx_withdr]->AMOUNT ;
                        $withdrawal_amt += $withdrawal_db ;
                        $idx_withdr++ ;
                    }
                    else if ($datePayment < $dateWithdraw) {
                        $dateEnd = $date_pay ;
                        $payment_amt = $payment[$idx_payment]->INSTALLMENT ;
                        $withdrawal_amt -= $payment_amt ;
                        $gid = $payment[$idx_payment]->GID ;
                        $idx_payment++ ; 
                    }
                    else if ($datePayment == $dateWithdraw) {
                        $dateEnd = $date_pay ;
                        $withdrawal_db = $withdraw[$idx_withdr]->AMOUNT ;
                        $payment_amt = $payment[$idx_payment]->INSTALLMENT ;
                        $withdrawal_amt += ($withdrawal_db - $payment_amt);
                        $idx_payment++ ;
                        $idx_withdr++ ;
                    }
                }
                else if ($withdr_onintrv) {
                    $IDX++;
                    $dateEnd = $date_wd;
                    $withdrawal_db = $withdraw[$idx_withdr]->AMOUNT ;
                    $withdrawal_amt += $withdrawal_db ;
                    $idx_withdr++ ;
                }
                else if($payment_onintrv) {
                    $IDX++;
                    $dateEnd = $date_pay ;
                    $payment_amt = $payment[$idx_payment]->INSTALLMENT ;
                    $withdrawal_amt -= $payment_amt ;
                    $gid = $payment[$idx_payment]->GID ;
                    $idx_payment++ ; 
                }
                $c_dateEnd = new DateTime($dateEnd);

                //change interest 
                if($max_interest > 1 && $idx_interest <= $max_interest) {
                    $dateInterest = $interest[$idx_interest - 1]->DOCDATE ;
                    $c_dateInterest = new DateTime($dateInterest);
                    if($c_dateInterest >= $c_dateEnd){
                        $idx_interest++ ;
                    }
                }

                //splits dateEnd to date, month, and year 
                $current_dateEnd = explode("/", $dateEnd) ;
                $nextdate = $current_dateEnd[1];
                $nextmonth = $current_dateEnd[0];
                $nextyear = $current_dateEnd[2];
                $date2 = $nextyear.'-'.$nextmonth.'-'.$nextdate;

                $c_date1 = DateTime::createFromFormat('Y-m-d', $date1);
                $c_date2 = DateTime::createFromFormat('Y-m-d', $date2);
                
                $daydiff = date_diff($c_date1, $c_date2);
                $day = $daydiff->d;
                $idx = $idx_interest - 1 ;
                $Interest = floatval($interest[$idx]->INTEREST) ;

                //calculate interest
                $cal_interest = intval(round($withdrawal_bfr * $Interest / 100  * $day / 360)) ;
                $cal_installment = $cal_interest + $payment_amt ;

                //convert date
                $i_dateEnd = date('m/d/Y', strtotime($dateEnd));
                $i_dateStart = date('m/d/Y', strtotime($dateStart));

                if($parmonth < $nextmonth || $paryear < $nextyear) {
                    $period++;
                }
                $pardate = $nextdate ;
                $parmonth = $nextmonth ;
                $paryear = $nextyear ;

                if($gid == null) {
                    $gid = $this->uuid->v4();
                } 
                
                $dt_row = [
                    'PK_NUMBER' => $param['PK_NUMBER'],
                    'CONTRACT_NUMBER' => $CONTRACT_NUM,
                    'CREDIT_TYPE' => $CREDIT_TYPE,
                    'INTEREST' => $Interest,
                    'WITHDRAWAL' => $withdrawal_db,
                    'PERIOD' => $period,
                    'INSTALLMENT' => $payment_amt,
                    'CALC_INSTALLMENT' => $cal_installment,
                    'CALC_INTEREST' => $cal_interest,
                    'REMAINING_BALANCE' => intval(round($withdrawal_bfr,2)),
                    'GID'   => $gid,
                    'PERIOD_IDX' => $x+1,
                    'DAY' => $day,
                    'PERIOD_YEAR' => $nextyear,
                    'UUID' => $param['UUID']
                ] ;
                
                // var_dump($date1, $date2) ; 
                $result = $this->db->set('FCIP', $Location)
                            ->set('START_PERIOD', "TO_DATE('".$i_dateStart."', 'mm-dd-yyyy')", false)
                            ->set('END_PERIOD', "TO_DATE('".$i_dateEnd."', 'mm-dd-yyyy')", false)
                            ->set('CREATED_BY', $this->session->userdata('FCCODE') )
                            ->set('CREATED_AT', 'SYSDATE', false)
                            ->set($dt_row)
                            ->insert('FUNDS_KMK_REPORT');
                if(!$result) {
                    break;
                }
            }
            // exit;
            if($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Save Data Successfull'
                ] ;
            } 
            else {
                $this->db->trans_rollback();
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Save Data Failed !!!'
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
        return $return ;
    }

    public function SaveReportKI ($param, $location) {
        // ini_set('display_errors', '1');
        try {
            $this->db->trans_begin();
            $rearr = $this->db->where('PK_NUMBER', $param['PK_NUMBER'])
            ->delete('FUNDS_KI_REPORT');
            $headerdetq = "SELECT FDKI.MATURITY_DATE,
                                FDKIT.EFFECTIVE_DATE,
                                TO_CHAR(FDKI.DOCDATE, 'fmMM/DD/YYYY') AS DDATE_COM,
                                TO_CHAR(FDKI.FIRST_DATE_INTEREST_PAYMENT, 'fmMM/DD/YYYY') AS FIRST_PAY,
                                TO_CHAR(FDKIT.GRACE_PERIOD_FROM, 'yyyy-mm-dd') AS GRACE_PERIOD_FROM,
                                TO_CHAR(FDKIT.GRACE_PERIOD_TO, 'yyyy-mm-dd') AS GRACE_PERIOD_TO,
                                FDKI.TENOR,
                                FDKI.INSTALLMENT_PERIOD,
                                FDKI.INTEREST_PAYMENT_SCHEDULE,
                                TO_CHAR(FDKI.DOCDATE, 'mm-dd-yyyy') AS DOCDATE,
                                FDKIT.TRANCHE_NUMBER,
                                FDKIT.CONTRACT_NUMBER,
                                FM.PK_NUMBER,
                                FM.UUID,
                                FM.COMPANY,
                                FDKIT.IDC,
                                (TO_CHAR (FDKI.DOCDATE, 'dd')) AS EF_DATE,
                                FDKI.INTEREST_PAYMENT_SCHEDULE_DATE,
                                (TO_CHAR (FDKI.DOCDATE, 'mm')) AS STARTMONTH,
                                (TO_CHAR (FDKI.DOCDATE, 'yyyy')) AS STARTYEAR,
                                TO_CHAR (FDKI.FIRST_DATE_INTEREST_PAYMENT, 'yyyy-mm-dd') AS FIRST_DATE
                        FROM FUNDS_DETAIL_KI FDKI
                                LEFT JOIN (SELECT UUID, CONTRACT_NUMBER, TRANCHE_NUMBER, EFFECTIVE_DATE, IDC, GRACE_PERIOD_FROM, GRACE_PERIOD_TO FROM FUNDS_DETAIL_KI_TRANCHE WHERE ISACTIVE = 1 ) FDKIT ON FDKI.UUID = FDKIT.UUID
                                LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FDKI.UUID
                        WHERE     FDKI.ISACTIVE = 1
                                AND FM.PK_NUMBER = '".$param['PK_NUMBER']."'" ;

            $interestq = " SELECT FM.PK_NUMBER, FDKI.INTEREST, (TO_CHAR(FDKI.ADDENDUM_DATE, 'fmMM/DD/YYYY')) as ADDENDUM_DATE, FDKI.ADDENDUM_DATE AS ADD_DATE, FDKI.MATURITY_DATE, FDKI.COUNTER FROM FUNDS_MASTER FM 
                                LEFT JOIN FUNDS_DETAIL_KI FDKI ON FM.UUID = FDKI.UUID
                                WHERE PK_NUMBER = '".$param['PK_NUMBER']."' AND FDKI.ADDENDUM_DATE IS NOT NULL
                                ORDER BY COUNTER ASC" ;

            $header = $this->db->query($headerdetq)->result();
           
            $tranche = array();
            for ($l = 0 ; $l < sizeof($header) ; $l++) {
                $tranche[$l] = $header[$l]->TRANCHE_NUMBER ;
            }

            $interest = $this->db->query($interestq)->result();
            
            //installment and withdrawal per tranche
            $all_inst = array();
            $all_with = array();

            foreach($tranche as $row) {
                $installmentq = "  SELECT
                                            INST.PERIOD_MONTH,
                                            INST.PERIOD_YEAR,
                                            CASE
                                            WHEN FP.IS_PAYMENT = 1 THEN FP.INSTALLMENT
                                            ELSE 0
                                            END
                                            AS INSTALLMENT_ACTUAL,
                                            INST.INSTALLMENT_AMOUNT AS INSTALLMENT_AMOUNT,
                                            FP.IS_PAYMENT
                                    FROM FUNDS_KI_INSTALLMENT INST
                                            LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FD
                                            ON     INST.TRANCHE_NUMBER = FD.TRANCHE_NUMBER
                                                AND INST.UUID = FD.UUID
                                                AND FD.ISACTIVE = 1
                                            LEFT JOIN FUNDSPAYMENT FP
                                            ON     INST.UUID = FP.UUID
                                                AND FD.CONTRACT_NUMBER = FP.CONTRACT_NUMBER
                                                AND INST.PERIOD_MONTH = FP.PERIOD_MONTH
                                                AND INST.PERIOD_YEAR = FP.PERIOD_YEAR
                                    WHERE     INST.UUID = '".$param['UUID']."'
                                            AND INST.TRANCHE_NUMBER = '".$row."'
                                            AND INST.COUNTER = (SELECT MAX (COUNTER)
                                                                FROM FUNDS_KI_INSTALLMENT
                                                                WHERE UUID = '".$param['UUID']."' AND TRANCHE_NUMBER = '".$row."')
                                            AND IS_ORIGINAL =
                                                (     SELECT DISTINCT IS_ORIGINAL
                                                        FROM FUNDS_KI_INSTALLMENT
                                                        WHERE     UUID = '".$param['UUID']."'
                                                                AND TRANCHE_NUMBER = '".$row."'
                                                                AND IS_ORIGINAL IS NOT NULL
                                                    ORDER BY IS_ORIGINAL ASC
                                                    FETCH FIRST 1 ROWS ONLY)
                                ORDER BY INST.ID ASC" ;

                $idcinstallmentq = "SELECT
                                        INST.PERIOD_MONTH,
                                        INST.PERIOD_YEAR,
                                        CASE
                                            WHEN FP.IS_PAYMENT = 1 THEN FP.IDC_INSTALLMENT
                                            ELSE 0
                                        END
                                        AS INSTALLMENT_ACTUAL,
                                    INST.INSTALLMENT_AMOUNT AS INSTALLMENT_AMOUNT,
                                    FP.IS_PAYMENT
                                    FROM FUNDS_KI_INSTALLMENT_IDC INST
                                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FD
                                            ON     INST.TRANCHE_NUMBER = FD.TRANCHE_NUMBER
                                                AND INST.UUID = FD.UUID
                                                AND FD.ISACTIVE = 1
                                        LEFT JOIN FUNDSPAYMENT FP
                                            ON     INST.UUID = FP.UUID
                                                AND FD.CONTRACT_NUMBER = FP.CONTRACT_NUMBER
                                                AND INST.PERIOD_MONTH = FP.PERIOD_MONTH
                                                AND INST.PERIOD_YEAR = FP.PERIOD_YEAR
                                    WHERE     INST.UUID = '".$param['UUID']."'
                                        AND INST.TRANCHE_NUMBER = '".$row."'
                                        AND INST.COUNTER = (SELECT MAX (COUNTER)
                                                                FROM FUNDS_KI_INSTALLMENT_IDC
                                                                WHERE UUID = '".$param['UUID']."' AND TRANCHE_NUMBER = '".$row."')
                                        AND IS_ORIGINAL =
                                                (     SELECT DISTINCT IS_ORIGINAL
                                                        FROM FUNDS_KI_INSTALLMENT_IDC
                                                        WHERE     UUID = '".$param['UUID']."'
                                                            AND TRANCHE_NUMBER = '".$row."'
                                                            AND IS_ORIGINAL IS NOT NULL
                                                    ORDER BY IS_ORIGINAL ASC
                                                FETCH FIRST 1 ROWS ONLY)
                                ORDER BY INST.ID ASC" ;

                $withdrawalq = "SELECT FM.PK_NUMBER, FWDKIT.TRANCHE_NUMBER, TO_CHAR(FWDKI.VALUE_DATE, 'mm/dd/yyyy') VALUE_DATE, VALUE_DATE AS VAL_DATE, FWDKIT.DDOWN_AMT  FROM FUNDS_WD_KI FWDKI
                                LEFT JOIN FUNDS_WD_KI_TRANCHE FWDKIT ON FWDKI.CTRWD = FWDKIT.BATCHID AND FWDKI.UUID = FWDKIT.UUID AND FWDKIT.COUNTER IS NULL
                                LEFT JOIN FUNDS_MASTER FM ON FWDKI.UUID = FM.UUID
                                WHERE FM.PK_NUMBER = '".$param['PK_NUMBER']."' AND FWDKIT.TRANCHE_NUMBER = '".$row."' AND FWDKIT.STATUS = '1' 
                                ORDER BY VAL_DATE ASC" ;
                $installment = $this->db->query($installmentq)->result(); 
                // var_dump($this->db->last_query()); exit;
                $idcinstallment = $this->db->query($idcinstallmentq)->result();
                $withdrawal = $this->db->query($withdrawalq)->result();

                $all_inst[$row] = $installment;
                $all_idc_inst[$row] = $idcinstallment;
                $all_with[$row] = $withdrawal;
            }
            $passdate = 0;
           
            //make report per tranche
            for($i = 0 ; $i < sizeof($tranche) ; $i++ ) {
                $IDC_STAT = $header[$i]->IDC ;
                if($IDC_STAT == null || $IDC_STAT == '') {
                    $IDC_STAT = 'WITHOUT_IDC' ;
                }

                //flag
                $upIdc = false ;
                $notOnInterest = true ;
                $notOnInstallment = true ;
                $notOnInstallmentidc = true ;
                $endidc = false ;
                $firstWd = false ;
                $notYet = true ;
               
                $IDC_payment = 0;
                $IDC_int_payment = 0;
                $passdate = 0;
                $CONTRACT_NUM = $header[$i]->CONTRACT_NUMBER;
                $UUID = $header[$i]->UUID ;
                $PK_NUM = $header[$i]->PK_NUMBER;
                $TR_NUM = $header[$i]->TRANCHE_NUMBER;
                $COMPANY = $header[$i]->COMPANY;
                $INT_TYPE = $header[$i]->INTEREST_PAYMENT_SCHEDULE;
                $DOCDATE = $header[$i]->DOCDATE;
                $GP_FROM =  DateTime::createFromFormat('Y-m-d', $header[$i]->GRACE_PERIOD_FROM) ;
                $GP_TO = DateTime::createFromFormat('Y-m-d', $header[$i]->GRACE_PERIOD_TO) ;

                $FIRST_PAY = $header[$i]->FIRST_PAY;
                $dateFirstPay = new DateTime($FIRST_PAY);

                //index for interest, installment, and withdrawal
                $idx_interest = 1 ;
                $idx_install = 0 ;
                $idx_installidc = 0 ;
                $idx_withdr = 0 ;
                $ctr_quart = 0 ;
                // $idx_exc = 0;

                //move interest based on latest effective date
                do {
                    $efdt = $header[$i]->DDATE_COM ;
                    $c_efdt = new DateTime($efdt) ;
                    $dtintrst = $interest[$idx_interest - 1]->ADDENDUM_DATE ;
                    $c_dtintrst = new DateTime($dtintrst);
                    if($c_efdt < $c_dtintrst && $idx_interest < sizeof($interest)) {
                        $idx_interest++ ;
                    }
                    else {
                        $notOnInterest = false ;
                    }
                } while ($notOnInterest) ;

                //amount_actual
                $withdrawal_amt = 0;
                $installment_amt = 0;
                $withdrawal_bfr = 0 ;
                $interest_payment = 0;
                $bank_interest_amt = 0 ;
                $IDC_val = 0 ;
                $idcinstallment_amt = 0 ;
                $cal_installment = 0 ;
                $interest_payment = 0 ;
                $idc_interest_payment = 0 ;

                //amount_prediction, for fundspayment table
                $IDC_val_pred = 0 ;
                $idcinstallment_amt_pred = 0 ;
                $cal_installment_pred = 0 ;
                $withdrawal_amt_pred = 0;
                $installment_amt_pred = 0;
                $withdrawal_bfr_pred = 0 ;
                $bank_interest_amt_pred = 0 ;


                //start period
                $period = 1;
                $startmonth = (int)$header[$i]->STARTMONTH;
                $startyear = (int) $header[$i]->STARTYEAR; 
                $startdate = (int) $header[$i]->EF_DATE;
                $EFF_DATE = (int)$header[$i]->EF_DATE ;
                $PAY_DATE = (int)$header[$i]->INTEREST_PAYMENT_SCHEDULE_DATE;

                $withdraw = $all_with[$tranche[$i]];
                $installment = $all_inst[$tranche[$i]];
                $idcinstallment = $all_idc_inst[$tranche[$i]];

                //move installment based on start report
                do {
                    if(intval($installment[$idx_install]->PERIOD_YEAR) < $startyear && ($idx_install < sizeof($installment))) {
                        $idx_install++;
                    }
                    else if (intval($installment[$idx_install]->PERIOD_YEAR) == $startyear && ($idx_install < sizeof($installment))){
                        if(intval($installment[$idx_install]->PERIOD_MONTH) < $startmonth && ($idx_install < sizeof($installment))) {
                            $idx_install++;
                        }
                        else {
                            $notOnInstallment = false ;
                        }
                    }
                    else {
                        $notOnInstallment = false ;
                    }
                }
                while($notOnInstallment) ;

                //move installment based on start report
                do {
                    if(intval($idcinstallment[$idx_installidc]->PERIOD_YEAR) < $startyear && ($idx_installidc < sizeof($idcinstallment))) {
                        $idx_installidc++;
                    }
                    else if (intval($idcinstallment[$idx_install]->PERIOD_YEAR) == $startyear && ($idx_install < sizeof($idcinstallment))){
                        if(intval($idcinstallment[$idx_install]->PERIOD_MONTH) < $startmonth && ($idx_install < sizeof($idcinstallment))) {
                            $idx_installidc++;
                        }
                        else {
                            $notOnInstallmentidc = false ;
                        }
                    }
                    else {
                        $notOnInstallmentidc = false ;
                    }
                }
                while($notOnInstallmentidc) ;

                $TOTAL_IDX = ((int) $header[$i]->TENOR) * 2 - 1 ;
                $IDX = $TOTAL_IDX ;
                
                for($x = 0 ; $x < $IDX ; $x++) {
                    //GID
                    $gid = null ;

                    //flag
                    $ISTLMT_FLAG = false;
                    $EXIST = false ;
                    $INSERT_TO_FP = false ;

                    $actualAmount = 0;
                    $installment_amt = 0 ;
                    $installment_amt_pred = 0 ;
                    $withdrawal_db = 0;
                    // var_dump("$x $withdrawal_bfr $withdrawal_amt");
                    $withdrawal_bfr = $withdrawal_amt;
                    $withdrawal_bfr_pred = $withdrawal_amt_pred;
                    $IDC_interest = 0 ;
                    $IDC_interest_pred = 0 ;
                    $cal_interest = 0 ;
                    $cal_interest_pred = 0 ;
                    $comp_interest = 0 ;
                    $comp_interest_pred = 0 ;
                    $bank_interest = 0 ;
                    $bank_interest_pred = 0 ;
                    // Hitung tanggal periodenya
                    // check terhadap : 
                        // - Withdrawal
                        // - Perubahan Interest
                        // - Perubahan Installment
                        // - Perubahan payment schedule date
                    if ($x == 0) {
                        $pardate = $startdate ;
                        $parmonth = $startmonth ;
                        $paryear = $startyear ;
                    }

                    //take date format mm/dd/yyyy
                    
                    $dateStart = $parmonth."/".$pardate."/".$paryear ;
                    $date1 = $paryear.'-'.$parmonth.'-'.$pardate;
                    if($withdraw != null) {
                        $activeWithdrawal = true ;
                    }
                    else {
                        $activeWithdrawal = false;
                    }
                    $dateWithdrawal = $withdraw[$idx_withdr]->VALUE_DATE;

                    //update idc value after month change
                    if($upIdc) {
                        $IDC_val_pred = $IDC_val_pred - $idcinstallment_amt_pred + $bank_interest_amt_pred ;
                        $IDC_val = $IDC_val - $idcinstallment_amt + $bank_interest_amt ;
                        $bank_interest_amt_pred = 0 ;
                        $bank_interest_amt = 0 ;
                        $upIdc = false ;
                        $idcinstallment_amt = 0 ;
                        $idcinstallment_amt_pred = 0 ;
                    }

                    if ($pardate <= ((int) $header[$i]->INTEREST_PAYMENT_SCHEDULE_DATE) && ($passdate == 0)){
                        //interval of before the payment schedule
                        $passdate = 1;
                        $nextdate = (int) $header[$i]->INTEREST_PAYMENT_SCHEDULE_DATE ;
                        $nextmonth = $parmonth ;
                        $nextyear = $paryear;
                        $upIdc = true ;
                        if($firstWd == true) {
                            $ctr_quart++ ;
                            if($INT_TYPE == 'Quarterly' && ($ctr_quart % 3 == 0)){
                                $INSERT_TO_FP = true ;
                                $ISTLMT_FLAG = true ;
                            }
                            else if($INT_TYPE == 'Monthly') {
                                if(!$notYet) {
                                    $INSERT_TO_FP = true ;
                                    $ISTLMT_FLAG = true ;
                                }
                                else {
                                    $notYet = false ;
                                }
                            }
                        } 

                        if($nextmonth == 2) {
                            if($nextdate >= 29) {
                                $lastdatethatyear = new DateTime("last day of $nextyear-2");
                                $nextdate = $lastdatethatyear->format('d');
                            }
                        }
                        else{
                        if($nextdate > 30) {
                            $lastdatethatmonth = new DateTime("last day of $nextyear-$nextmonth");
                            $nextdate = $lastdatethatmonth->format('d');
                        }}
                        
                    } else {
                        //interval after the payment schedule until the first day of next month
                        $nextdate = 1 ;
                        $nextmonth = $parmonth + 1 ;
                        $nextyear = $paryear;
                        if ($nextmonth > 12) {
                            $nextmonth = 1 ;
                            $nextyear = $paryear + 1 ;
                        }
                        $passdate = 0;
                    }
                    
                    //set date end
                    $dateEnd = $nextmonth."/".$nextdate."/".$nextyear ;
                    $date2 = $nextyear.'-'.$nextmonth.'-'.$nextdate;
                    $c_dateEnd = new DateTime($dateEnd);
                    $c_dateStart = new DateTime($dateStart);
                    $c_dateWithdrawal = new DateTime($dateWithdrawal);
                    
                    //if there's a withdrawal in interval of end and start date, change end date with withdrawal date on interval
                    if(($c_dateStart <= $c_dateWithdrawal )&& ($c_dateEnd >= $c_dateWithdrawal ) && $activeWithdrawal && $dateWithdrawal != "") {
                        $cur_datewith = explode("/", $dateWithdrawal) ;
                        $nextdate = $cur_datewith[1];
                        $nextmonth = $cur_datewith[0];
                        $nextyear = $cur_datewith[2];
                        $dateEnd = $nextmonth."/".$nextdate."/".$nextyear ;
                        $date2 = $nextyear.'-'.$nextmonth.'-'.$nextdate;
                        if($nextmonth[0] == '0') {
                            $nextmonth = $nextmonth[1];
                        }
                        $withdrawal_db = (int) $withdraw[$idx_withdr]->DDOWN_AMT;
                        $withdrawal_bfr_pred = $withdrawal_amt_pred ;
                        $withdrawal_bfr =  $withdrawal_amt;
                        $withdrawal_amt_pred += $withdrawal_db ;
                        $withdrawal_amt += $withdrawal_db;
                        $idx_withdr += 1;
                        if($idx_withdr >= sizeof($withdraw)) {
                            $activeWithdrawal = false ;
                        }
                        if($pardate == (int)$header[$i]->INTEREST_PAYMENT_SCHEDULE_DATE){
                                $passdate = 1 ;
                            }
                            else {
                                $passdate = 0 ;
                                $IDX += 1;
                            }
                        if($firstWd == false) {
                           $notYet = true ;
                        }
                        $firstWd = true ;
                    }
                    $monthIst = $installment[$idx_install]->PERIOD_MONTH ;
                    $yearIst = $installment[$idx_install]->PERIOD_YEAR ;
                    $monthIstidc = $idcinstallment[$idx_install]->PERIOD_MONTH ;
                    $yearIstidc = $idcinstallment[$idx_install]->PERIOD_YEAR ;

                    $c_dateEnd = new DateTime($dateEnd);
                    $c_dateStart = new DateTime($dateStart);

                    if(($nextdate == ((int) $header[$i]->INTEREST_PAYMENT_SCHEDULE_DATE) && $nextmonth == $monthIst && $nextyear == $yearIst && $firstWd)) {
                       
                        $ISTLMT_FLAG = true;
                        if($installment[$idx_install]->IS_PAYMENT == '1'){
                            $INSERT_TO_FP = false ;
                        }
                        else {
                            $INSERT_TO_FP = true ;
                        }
                        $installment_amt = (int)$installment[$idx_install]->INSTALLMENT_ACTUAL ;
                        $installment_amt_pred = (int)$installment[$idx_install]->INSTALLMENT_AMOUNT ;
                        $withdrawal_bfr_pred = $withdrawal_amt_pred ;
                        $withdrawal_bfr = $withdrawal_amt ;
                        $withdrawal_amt_pred -= $installment_amt_pred ;
                        $withdrawal_amt -= $installment_amt;
                        $idx_install++;
                        $onInstallment = false ;
                    } 
                    if(($nextdate == ((int) $header[$i]->INTEREST_PAYMENT_SCHEDULE_DATE) && $nextmonth == $monthIstidc && $nextyear == $yearIstidc)) {
                        $idcinstallment_amt = (int)$idcinstallment[$idx_installidc]->INSTALLMENT_ACTUAL ; 
                        $idcinstallment_amt_pred = (int)$idcinstallment[$idx_installidc]->INSTALLMENT_AMOUNT ; 
                        $idx_installidc++;
                    } 

                    //if there's addendum on interest, change its interest
                    if($idx_interest <= (sizeof($interest) - 1)){
                        $dateInterest = $interest[$idx_interest]->ADDENDUM_DATE ;
                        $c_dateInterest = new DateTime($dateInterest);
                        if(($c_dateStart <= $c_dateInterest )&& ($c_dateEnd > $c_dateInterest) && ($idx_interest < sizeof($interest))){
                            $idx_interest += 1; 
                        }
                    }
                    $idx = $idx_interest - 1 ;
                    $Interest = floatval($interest[$idx]->INTEREST) ;
                    $c_date1 = DateTime::createFromFormat('Y-m-d', $date1);
                    $c_date2 = DateTime::createFromFormat('Y-m-d', $date2);
                    $daydiff = date_diff($c_date1, $c_date2);
                    $day = $daydiff->d;

                    //calculate interest
                    
                    if($IDC_STAT == 'WITH_IDC') {
                        if($c_date2 <= $GP_TO && $c_date1 >= $GP_FROM) {
                            $cal_interest = $withdrawal_bfr * $Interest / 100  * $day / 360 ;
                            $cal_interest_pred = $withdrawal_bfr_pred * $Interest / 100  * $day / 360 ;
                            $bank_interest = $cal_interest * 70 / 100 ;
                            $bank_interest_pred = $cal_interest_pred * 70 / 100 ;
                            $comp_interest = $cal_interest * 30 / 100;
                            $comp_interest_pred = $cal_interest_pred * 30 / 100 ;
                            $bank_interest_amt += $bank_interest ;
                            $bank_interest_amt_pred += $bank_interest_pred;
                            $IDC_interest = $IDC_val * $Interest / 100 * $day / 360;
                            $IDC_interest_pred = $IDC_val_pred * $Interest / 100 * $day / 360;
                            $comp_interest += $IDC_interest;
                            $comp_interest_pred += $IDC_interest_pred ;
                            $cal_installment = $cal_installment + $installment_amt + $IDC_val + $IDC_interest ;
                            $cal_installment_pred = $cal_installment_pred + $installment_amt_pred + $IDC_val_pred + $IDC_interest_pred ;
                            $idc_interest_payment += $IDC_interest_pred ;
                            $interest_payment += $comp_interest_pred ;
                            $endidc = true ;
                        } else {
                            //if idc calculation has end, reupdate idc installment
                            if($endidc) {
                                $par['UUID'] = $UUID ;
                                $par['TRANCHE_NUMBER'] = $TR_NUM ; 
                                $updateintidc = $this->reupdateInstallmentIDC($par, intval(round($IDC_val_pred))) ;
                                foreach($updateintidc as $id => $updatedvalue) {
                                    $idcinstallment[$id]->INSTALLMENT_AMOUNT = $updatedvalue ;
                                }
                                $endidc = false ;
                            }
                            $cal_interest = $withdrawal_bfr * $Interest / 100  * $day / 360 ;
                            $cal_interest_pred = $withdrawal_bfr_pred * $Interest / 100  * $day / 360 ;
                            $bank_interest = 0 ;
                            $bank_interest_pred = 0 ;
                            $comp_interest = $cal_interest ;
                            $comp_interest_pred = $cal_interest_pred;
                            $bank_interest_amt += $bank_interest ;
                            $bank_interest_amt_pred += $bank_interest_pred ;
                            $IDC_interest = $IDC_val * $Interest / 100 * $day / 360;
                            $IDC_interest_pred = $IDC_val_pred * $Interest / 100 * $day / 360;
                            $cal_installment = $cal_installment + $installment_amt + $IDC_val + $IDC_interest ;
                            $cal_installment_pred = $cal_installment_pred + $installment_amt_pred + $IDC_val_pred + $IDC_interest_pred ;
                            $idc_interest_payment += $IDC_interest_pred ;
                            $interest_payment += $comp_interest_pred ;
                        }
                    }
                    else {
                        $cal_interest = $withdrawal_bfr * $Interest / 100  * $day / 360 ;
                        $cal_interest_pred = $withdrawal_bfr_pred * $Interest / 100  * $day / 360 ;
                        $cal_installment = $cal_interest + $installment_amt ;
                        $cal_installment_pred = $cal_interest_pred + $installment_amt_pred ;
                        $interest_payment += $cal_interest_pred ;
                        $idc_interest_payment += 0 ;
                    }

                    //convert date
                    $i_dateEnd = date('m/d/Y', strtotime($dateEnd));
                    $i_dateStart = date('m/d/Y', strtotime($dateStart));
                    
                    //check whether payment data already exist or not
                    $paymentq = "SELECT * FROM FUNDSPAYMENT WHERE PK_NUMBER = '".$param['PK_NUMBER']."' AND PERIOD = '".$period."' AND CONTRACT_NUMBER = '".$CONTRACT_NUM."' AND START_PERIOD = TO_DATE('{$dateStart}', 'mm/dd/yyyy') AND END_PERIOD = TO_DATE('{$dateEnd}', 'mm/dd/yyyy')" ;
                    $payment = $this->db->query($paymentq)->row();
                    if($payment != null) {
                        $gid = $payment->GID;
                        $actualAmount = $payment->BILLING_VALUE ;
                        $EXIST = true;
                        if($payment->IS_PAYMENT == '1') {
                            $INSERT_TO_FP = false ;
                        }
                    }

                    if($gid == null) {
                        $gid = $this->uuid->v4();
                    } 
                    
                    $dt_row = [
                        'PK_NUMBER' => $param['PK_NUMBER'],
                        'TRANCHE_NUMBER' => $tranche[$i],
                        'INTEREST' => $Interest,
                        'WITHDRAWAL' => $withdrawal_db,
                        'CURRENT_MONTH' => $period,
                        'INSTALLMENT' => $installment_amt_pred,
                        'CALC_INSTALLMENT' => $cal_installment_pred,
                        'CALC_INTEREST' => ($IDC_STAT == 'WITH_IDC') ? $comp_interest_pred : $cal_interest_pred,
                        'BANK_INTEREST' => $bank_interest_pred,
                        'MAIN_INTEREST' => $cal_interest_pred,
                        'REMAINING_BALANCE' => intval(round($withdrawal_bfr_pred,2)),
                        'GID'   => $gid,
                        'PERIOD_IDX' => $x+1,
                        'DAY' => $day,
                        'PERIOD_YEAR' => $nextyear,
                        'PERIOD_MONTH' => $nextmonth,
                        'UUID' => $param['UUID'],
                        'ACTUAL' => $actualAmount
                    ] ;

                    if($IDC_STAT == 'WITH_IDC') {
                        $dt_row['CALC_IDC'] = $IDC_val_pred ;
                        $dt_row['IDC_INTEREST'] = $IDC_interest_pred ; 
                        $dt_row['IDC_INSTALLMENT'] = $idcinstallment_amt_pred;
                    }

                    $result = $this->db->set('FCIP', $location)
                                ->set('START_PERIOD', "TO_DATE('".$dateStart."', 'mm-dd-yyyy')", false)
                                ->set('END_PERIOD', "TO_DATE('".$dateEnd."', 'mm-dd-yyyy')", false)
                                ->set('FCENTRY', $this->session->userdata('FCCODE') )
                                ->set('STATUS', 0)
                                ->set('LAST_UPDATE', 'SYSDATE', false)
                                ->set($dt_row)
                                ->insert('FUNDS_KI_REPORT');

                    if($INSERT_TO_FP) {
                        if($ISTLMT_FLAG && !$EXIST) {
                            $dt = [
                                'CONTRACT_NUMBER' => $CONTRACT_NUM,
                                'PK_NUMBER' => $param['PK_NUMBER'],
                                'COMPANY' => $COMPANY,
                                'CREDIT_TYPE' => 'KI',
                                'FCENTRY' => $this->session->userdata('FCCODE'),
                                'FCIP' => $location,
                                'PERIOD_MONTH' => $parmonth,
                                'PERIOD_YEAR' => $paryear,
                                'PERIOD' => $period,
                                'INSTALLMENT' => $installment_amt_pred,
                                'INTEREST' => intval(round($interest_payment)),
                                'GID'   => $gid,
                                'UUID' => $param['UUID'],
                                'IDC' => intval(round($IDC_payment)), 
                                'IDC_INTEREST' => intval(round($idc_interest_payment)),
                                'IDC_INSTALLMENT' => intval(round($idcinstallment_amt_pred)),
                            ] ;
                            $interest_payment = 0 ;
                            $idc_interest_payment = 0 ;
                        $result = $this->db->set('DOCDATE', "TO_DATE('".$DOCDATE."', 'mm-dd-yyyy')", false)
                                    ->set('START_PERIOD',  "TO_DATE('".$dateStart."', 'mm-dd-yyyy')", false)
                                    ->set('END_PERIOD',"TO_DATE('".$dateEnd."', 'mm-dd-yyyy')", false)
                                    ->set('LASTUPDATE', 'SYSDATE',false)
                                    ->set($dt)
                                    ->insert('FUNDSPAYMENT');
                        
                        }
                        else if ($ISTLMT_FLAG && $EXIST){
                            $dt = [
                                'CONTRACT_NUMBER' => $CONTRACT_NUM,
                                'PK_NUMBER' => $param['PK_NUMBER'],
                                'COMPANY' => $COMPANY,
                                'CREDIT_TYPE' => 'KI',
                                'FCENTRY' => $this->session->userdata('FCCODE'),
                                'FCIP' => $location,
                                'PERIOD_MONTH' => $parmonth,
                                'PERIOD_YEAR' => $paryear,
                                'PERIOD' => $period,
                                'INSTALLMENT' => $installment_amt_pred,
                                'INTEREST' => intval(round($interest_payment)),
                                'IDC' => intval(round($IDC_payment)), 
                                'IDC_INTEREST' => intval(round($idc_interest_payment)),
                                'IDC_INSTALLMENT' => intval(round($idcinstallment_amt_pred)),
                            ] ;
                            $interest_payment = 0 ;
                            $idc_interest_payment = 0 ;
                            $result = $this->db
                                        ->set('DOCDATE', "TO_DATE('".$DOCDATE."', 'mm-dd-yyyy')", false)
                                        ->set('START_PERIOD',  "TO_DATE('".$dateStart."', 'mm-dd-yyyy')", false)
                                        ->set('END_PERIOD',"TO_DATE('".$dateEnd."', 'mm-dd-yyyy')", false)
                                        ->set('LASTUPDATE', 'SYSDATE',false)
                                        ->set($dt)
                                        ->where('GID', $gid)
                                        ->update('FUNDSPAYMENT');
    
                        }
                    }
                    if($parmonth < $nextmonth || $paryear < $nextyear) {
                        $period++;
                    }
                    $pardate = $nextdate ;
                    $parmonth = $nextmonth ;
                    $paryear = $nextyear ;
                    if(!$result) {
                        break;
                    }
                }
            }
            if($result) {
                $this->db->trans_commit() ;
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been successfully saved' 
                ] ;
            }
            else {
                throw new Exception("error in ". $param['PK_NUMBER'] ." - ". $TR_NUM) ;
            }
        }
        catch (Exception $ex) {
        $this->db->trans_rollback();
           $return = [
                'STATUS' => 500,
                'MESSAGE' => $ex->getMessage()
           ];
        }
        $this->db->close();
        return $return;
    }

    public function SaveReportKI_SYD ($param, $location) {
        /*
            $param contain :
                -> UUID
        */
        // ini_set('display_errors', 'On');
        $this->db->trans_begin();
        $cleartable = $this->db->where(['UUID' => $param['UUID'], 'BANK IS NOT NULL' => null], false)->delete('FUNDS_KI_REPORT') ;

        $lastresultq = "SELECT *
                            FROM FUNDS_KI_REPORT FR
                        WHERE UUID = '".$param['UUID']."' AND BANK IS NULL" ;
        $bankq = "SELECT FM.PK_NUMBER,
                        BKI.BANKNAME AS BANK_CODE,
                        B.FCNAME AS BANK_NAME,
                        BKI.PORTION,
                        BKI.PERCENTAGE
                FROM BANK_KI BKI
                        LEFT JOIN FUNDS_MASTER FM ON FM.UUID = BKI.UUID
                        LEFT JOIN SUPPLIER B ON B.ID = BKI.BANKNAME
                        LEFT JOIN (SELECT UUID, MAX(COUNTER) as max_count FROM BANK_KI GROUP BY UUID) LT_CTR ON LT_CTR.UUID = FM.UUID 
                WHERE FM.UUID = '".$param['UUID']."' AND BKI.COUNTER = LT_CTR.max_count";
        $banks = $this->db->query($bankq)->result();
        $lastresult = $this->db->query($lastresultq)->result();
        $TR_NUM = '' ;
        $PK_NUM = $lastresult[0]->PK_NUMBER ;
        try {
            foreach($banks as $bank) {
                $bank_perc = $bank->PERCENTAGE / 100 ;
                $bankname = $bank->BANK_NAME ;
                foreach($lastresult as $item) {
                    $lid = $this->uuid->v4();
                    $dt = [ 
                        'PK_NUMBER' => $item->PK_NUMBER,
                        'TRANCHE_NUMBER' => $item->TRANCHE_NUMBER,
                        'START_PERIOD' => $item->START_PERIOD,
                        'END_PERIOD' => $item->END_PERIOD,
                        'LAST_UPDATE' => $item->LAST_UPDATE,
                        'INTEREST' => $item->INTEREST,
                        'CURRENT_MONTH' => $item->CURRENT_MONTH,
                        'CALC_INTEREST' => $item->CALC_INTEREST * $bank_perc,
                        'INSTALLMENT' => $item->INSTALLMENT * $bank_perc,
                        'CALC_INSTALLMENT' => $item->CALC_INSTALLMENT * $bank_perc,
                        'PERIOD_IDX' => $item->PERIOD_IDX,
                        'GID' => $lid,
                        'FCIP' => $location,
                        'FCENTRY' => $item->FCENTRY,
                        'STATUS' => $item->STATUS,
                        'REMAINING_BALANCE' => $item->REMAINING_BALANCE * $bank_perc,
                        'DAY' => $item->DAY,
                        'WITHDRAWAL' => $item->WITHDRAWAL * $bank_perc,
                        'CONTRACT_TYPE' => $item->CONTRACT_TYPE,
                        'IDC_INTEREST' => $item->IDC_INTEREST  * $bank_perc,
                        'CALC_IDC' => $item->CALC_IDC  * $bank_perc,
                        'BANK' => $bankname,
                        'PERIOD_YEAR' => $item->PERIOD_YEAR,
                        'UUID' => $item->UUID,
                        'PERIOD_MONTH' => $item->PERIOD_MONTH,
                        'IDC_INSTALLMENT' => $item->IDC_INSTALLMENT * $bank_perc, 
                        'BANK_INTEREST' => $item->BANK_INTEREST * $bank_perc,
                        'MAIN_INTEREST' => $item->MAIN_INTEREST * $bank_perc,
                        'ACTUAL' => $item->ACTUAL 
                    ] ;
                    $insert = $this->db->set($dt)->insert('FUNDS_KI_REPORT') ;
                    if(!$insert) {
                        throw new Exception ("Error : $PK_NUM - {$item->TRANCHE_NUMBER}") ;
                    }
                }
            }
            if($insert) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data SYD Created Successfully'
                ] ;
            }
        } catch (Exception $ex) {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ] ;
            $this->db->trans_rollback();
        }
        return $return ;
    }

    public function ExportReportKI ($param, $location) {
        try {
            $headerq = " SELECT   
                        FM.PK_NUMBER,
                        FM.CREDIT_TYPE,
                        FDKIT.IDC,
                        FDKIT.TRANCHE_NUMBER,
                        FDKIT.LOAN_ACCOUNT_NUMBER,
                        FDKIT.LIMIT_TRANCHE,
                        FDKIT.CONTRACT_NUMBER,
                        FDKI.TENOR,
                        B.FCNAME AS BANK,
                        BU.FCNAME AS BUSINESSUNIT,
                        C.COMPANYNAME AS COMPANY,
                        C.COMPANYCODE
                    FROM FUNDS_MASTER FM
                                LEFT JOIN COMPANY C ON FM.COMPANY = C.ID
                                LEFT JOIN BUSINESSUNIT BU ON FM.BUNIT = BU.ID
                                LEFT JOIN SUPPLIER B ON FM.BANK = B.ID
                                LEFT JOIN FUNDS_DETAIL_KI FDKI ON FM.UUID = FDKI.UUID AND FDKI.ISACTIVE = 1 AND FDKI.IS_ACC = 1
                                LEFT JOIN (SELECT UUID, TRANCHE_NUMBER, LOAN_ACCOUNT_NUMBER, LIMIT_TRANCHE, CONTRACT_NUMBER, IDC FROM FUNDS_DETAIL_KI_TRANCHE WHERE IS_ACC = 1 AND ISACTIVE = 1) FDKIT ON FM.UUID = FDKIT.UUID
                    WHERE FM.CREDIT_TYPE = 'KI' AND FM.ISACTIVE = 1 AND FM.IS_ACC = 1 
                    AND FM.UUID = '".$param['UUID']."' 
            " ;
            
            $header = $this->db->query($headerq)->result();
            $CUR_IDC_STAT = $header[0]->IDC ;
            if($CUR_IDC_STAT == '' || $CUR_IDC_STAT == null) {
                $CUR_IDC_STAT = 'WITHOUT_IDC';
            }
            $NEXT_IDC_STAT = '' ;
            $tranche = array();
            $all_report = array();
            for ($l = 0 ; $l < sizeof($header) ; $l++) {
                $tranche[$l] = $header[$l]->TRANCHE_NUMBER ;
            }
            foreach($tranche as $row) {
                $period = '' ;
                $reportq = "SELECT * FROM FUNDS_KI_REPORT 
                        WHERE UUID = '".$param['UUID']."' AND TRANCHE_NUMBER = '".$row."'  
                        ORDER BY PERIOD_IDX ASC" ;

                $report = $this->db->query($reportq)->result();
                $all_report[$row] = $report;
            }
            
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator($this->session->userdata('FCCODE'))
            ->setLastModifiedBy($this->session->userdata('FCCODE'))
            ->setTitle('Report KI '. Carbon::now('Asia/Jakarta')->format('d-M-Y')." ".$header[0]->PK_NUMBER)
            ->setSubject('Report KI');

            //style
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '0000000'],
                    ],
                ],
            ];

            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $style_Content = array(          
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            )
            );
            $style_ContentNumeric = array(          
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'numberFormat' => array(
                    'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING
                )
            );

            //table length
            $col =  [
                'A' => 1,
                'B' => 2,
                'C' => 3,
                'D' => 4,
                'E' => 5,
                'F' => 6,
                'G' => 7, 
                'H' => 8,
                'I' => 9,
                'J' => 10,
                'K' => 11
            ] ;
            if($CUR_IDC_STAT == 'WITH_IDC') {
                $col['L'] = 12 ;
                $col['M'] = 13 ;
                $col['N'] = 14 ;
                $col['O'] = 15 ;
                $col['P'] = 16 ;
                $col['Q'] = 17 ;
            }
            
            for($k = 0 ; $k < sizeof($tranche) ; $k++) {
                if(($k+1) < sizeof($tranche)) {
                    $NEXT_IDC_STAT = $header[$k+1]->IDC ;
                    if($NEXT_IDC_STAT == null || $NEXT_IDC_STAT == '') {
                        $NEXT_IDC_STAT = 'WITHOUT_IDC' ;
                    }
                    
                }
                else {
                    $NEXT_IDC_STAT = 'SKIP' ;
                }
                $colname = array();
                $idx_name = array();
                foreach($col as $ix) {
                    $colname[array_search($ix,$col)] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ix) ;
                    array_push($idx_name, \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ix) );
                }
                    //setting header excel
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col['A'],1, 'TRANCHE NUMBER')
                ->setCellValueByColumnAndRow($col['A'],2, 'PK Number')
                ->setCellValueByColumnAndRow($col['A'],3, 'Bank')
                ->setCellValueByColumnAndRow($col['A'],4, 'Business Unit')
                ->setCellValueByColumnAndRow($col['C'],1, 'Limit') ;
                
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col['B'],1, "{$header[$k]->TRANCHE_NUMBER} ({$header[$k]->CONTRACT_NUMBER})")
                ->setCellValueByColumnAndRow($col['B'],2, $header[$k]->PK_NUMBER)
                ->setCellValueByColumnAndRow($col['B'],3, $header[$k]->BANK)
                ->setCellValueByColumnAndRow($col['B'],4, $header[$k]->BUSINESSUNIT) 
                ->setCellValueByColumnAndRow($col['D'],1, $header[$k]->LIMIT_TRANCHE);
                
                //setting table header
                //PERIOD
                $s = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['A']);
                $e =\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['C']);
                $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells("{$s}5:{$e}6");
                $objPHPExcel->setActiveSheetIndex(0)->getCell("{$s}5")->setValue('Period');
                
                //Perhitungan Bunga
                $sbung = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['D']);
                $ebung = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['I']);
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$sbung}5:{$ebung}5");
                $objPHPExcel->setActiveSheetIndex(0)->getCell("{$sbung}5")->setValue('Perhitungan Bunga');
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col['D'],6, 'Pokok')
                ->setCellValueByColumnAndRow($col['E'],6, 'Penarikan')
                ->setCellValueByColumnAndRow($col['F'],6, 'Cicil Pokok')
                ->setCellValueByColumnAndRow($col['G'],6, 'Bunga')
                ->setCellValueByColumnAndRow($col['H'],6, 'Hari' )
                ->setCellValueByColumnAndRow($col['I'],6, 'Bunga Pokok');

                if($CUR_IDC_STAT == 'WITH_IDC') {
                    $objPHPExcel->getActiveSheet()
                    ->setCellValueByColumnAndRow($col['J'],6, 'IDC')
                    ->setCellValueByColumnAndRow($col['K'],6, 'Cicil IDC')
                    ->setCellValueByColumnAndRow($col['L'],6, 'Bunga IDC')
                    ->setCellValueByColumnAndRow($col['M'],6, 'Total Hutang')
                    ->setCellValueByColumnAndRow($col['N'],6, 'Total Bunga');
                }
                
                if($CUR_IDC_STAT == 'WITH_IDC') {
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['O']}5:{$colname['P']}5") ;
                    $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['O']}5")->setValue("Pembayaran Bunga") ;
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['Q']}5:{$colname['Q']}6");
                    $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['Q']}5")->setValue('ACTUAL');
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col['O'], 6, 'Bank')
                    ->setCellValueByColumnAndRow($col['P'], 6, $header[$k]->COMPANYCODE);
                }
                else {
                     //TOTAL
                    $stota = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['J']);
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$stota}5:{$stota}6");
                    $objPHPExcel->setActiveSheetIndex(0)->getCell("{$stota}5")->setValue('TOTAL');
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['K']}5:{$colname['K']}6");
                    $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['K']}5")->setValue('ACTUAL');
                }
               
                

                $report = $all_report[$tranche[$k]];

                $i = 0 ; //index report
                $idx = 7 ; //excel line
                $totalInstall = 0 ;
                $totalInterest = 0;
                $totalAll_A = 0;
                $totalAll = 0 ;
                $totalAllInstall = 0;
                $totalAllInterest = 0;
                $totalYearlyInstall = 0;
                $totalYearlyInterest = 0 ;
                $totalYearlyAll = 0 ;
                $totalYearlyDays = 0 ;
                $currentYear = intval($report[$i]->PERIOD_YEAR);
                $nextYear = intval($report[$i]->PERIOD_YEAR) ;
                $startmonth = intval($report[$i]->CURRENT_MONTH) ;
                $secPart = false ;

                //if IDC
                $totalBank = 0 ;
                $totalBank_All = 0 ;
                $totalBank_Yearly = 0 ;

                $totalComp = 0 ;
                $totalComp_All = 0 ;
                $totalComp_Yearly = 0 ;

                $totalIDCInt = 0 ;
                $totalIDCInt_All = 0 ;
                $totalIDCInt_Yearly = 0 ;

                $totalSumInt = 0 ;
                $totalSumInt_All = 0 ;
                $totalSumInt_Yearly = 0 ;

                $totalIDCItl = 0 ;
                $totalIDCItl_All = 0 ;
                $totalIDCItl_Yearly = 0 ;

                //Actual
                $totalActual = 0 ;
                $totalAllActual = 0 ;
                $totalYearlyActual = 0 ;

                do {
                    $bankamt = intval(intval($report[$i]->BANK_INTEREST)) ;
                    $nextYear = intval($report[$i]->PERIOD_YEAR) ;
                    $nextmonth = intval($report[$i]->CURRENT_MONTH);
                    if(!$secPart) {
                        if($startmonth > $nextmonth && $nextmonth < 10) {
                            $startmonth = 12 - $startmonth ;
                        }
                        if($nextmonth - $startmonth == 3) {
                            $secPart = true ;
                            $startmonth = $nextmonth ;
                        }
                    }
                    else if ($secPart) {
                        if($CUR_IDC_STAT == 'WITH_IDC') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $report[$i]->CALC_IDC);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalIDCItl);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['L'], $idx, $totalIDCInt);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, $totalSumInt);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, $totalBank);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, $totalComp);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['Q'], $idx, $totalActual);

                        }
                        else {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalAll);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalActual);
                        }
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalInstall);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalInterest);
                        

                        $totalAllInstall += $totalInstall;
                        $totalAllInterest +=$totalInterest;
                        $totalAll_A += $totalAll;
                        $totalBank_All += $totalBank ;
                        $totalComp_All += $totalComp ;
                        $totalIDCInt_All += $totalIDCInt ;
                        $totalIDCItl_All += $totalIDCItl ;
                        $totalSumInt_All += $totalSumInt ;
                        $totalAllActual += $totalActual ;

                        // var_dump($totalInstall);

                        $totalActual = 0 ;
                        $totalInstall = 0 ;
                        $totalInterest = 0;
                        $totalAll = 0 ;
                        $totalBank = 0 ;
                        $totalComp = 0 ;
                        $totalIDCInt = 0 ;
                        $totalIDCItl = 0 ;
                        $totalSumInt = 0 ;
                        $idx++;
                        $secPart = false ;
                    }
                    if($currentYear < $nextYear) {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx, "TOTAL TAHUN $currentYear");
                        $objPHPExcel->getActiveSheet()->mergeCells("{$colname['A']}{$idx}:{$colname['C']}{$idx}");
                        if($CUR_IDC_STAT == 'WITH_IDC') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalIDCItl_Yearly);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['L'], $idx, $totalIDCInt_Yearly);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, $totalSumInt_Yearly);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, $totalBank_Yearly);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, $totalComp_Yearly);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['Q'], $idx, $totalYearlyActual);
                            $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['Q']}{$idx}")->applyFromArray($style_ContentNumeric);
                            $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['Q']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                        } 
                        else {
                            $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['K']}{$idx}")->applyFromArray($style_ContentNumeric);
                            $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['K']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalYearlyAll);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalYearlyActual);
                        }
                       
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalYearlyInstall);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalYearlyInterest);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['H'], $idx, $totalYearlyDays);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['D'], $idx, $report[$i]->REMAINING_BALANCE) ;
                        $totalYearlyInstall = 0 ;
                        $totalYearlyInterest = 0 ;
                        $totalIDCInt_Yearly = 0 ;
                        $totalIDCItl_Yearly = 0 ;
                        $totalSumInt_Yearly = 0 ;
                        $totalBank_Yearly = 0 ;
                        $totalComp_Yearly = 0 ;
                        $totalYearlyAll = 0;
                        $totalYearlyDays = 0;
                        $totalYearlyActual = 0 ;
                        $startmonth = $nextmonth;
                        $idx++;
                    }
                    else {
                        $totalInstall += intval($report[$i]->INSTALLMENT) ;
                        $totalInterest += intval($report[$i]->MAIN_INTEREST);
                        $totalAll += intval($report[$i]->CALC_INSTALLMENT);
                        $totalIDCInt += intval($report[$i]->IDC_INTEREST);
                        $totalIDCItl += intval($report[$i]->IDC_INSTALLMENT);
                        $totalSumInt += intval($report[$i]->CALC_INTEREST) + intval($report[$i]->CALC_IDC) ;
                        $totalBank += $bankamt ;
                        $totalComp += intval($report[$i]->CALC_INTEREST) ;
                        $totalBank_Yearly += $bankamt ;
                        $totalComp_Yearly += intval($report[$i]->CALC_INTEREST) ;
                        $totalActual += intval($report[$i]->ACTUAL);

                        $totalYearlyInstall += intval($report[$i]->INSTALLMENT) ;
                        $totalYearlyInterest += intval($report[$i]->MAIN_INTEREST) ;
                        $totalYearlyAll += intval($report[$i]->CALC_INSTALLMENT);
                        $totalIDCInt_Yearly += intval($report[$i]->IDC_INTEREST);
                        $totalIDCItl_Yearly += intval($report[$i]->IDC_INSTALLMENT);
                        $totalSumInt_Yearly += intval($report[$i]->MAIN_INTEREST) + intval($report[$i]->CALC_IDC) ;
                        $totalYearlyDays += intval($report[$i]->DAY);
                        $totalYearlyActual += intval($report[$i]->ACTUAL);

                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx, $report[$i]->START_PERIOD) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['B'], $idx, "-") ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['C'], $idx, $report[$i]->END_PERIOD) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['D'], $idx, $report[$i]->REMAINING_BALANCE) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['E'], $idx, $report[$i]->WITHDRAWAL) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $report[$i]->INSTALLMENT) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['G'], $idx, floatval($report[$i]->INTEREST)/100) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['H'], $idx, $report[$i]->DAY) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $report[$i]->MAIN_INTEREST) ;
                        if($CUR_IDC_STAT == 'WITH_IDC') {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $report[$i]->CALC_IDC) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $report[$i]->IDC_INSTALLMENT) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['L'], $idx, $report[$i]->IDC_INTEREST) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['M'], $idx, "={$colname['J']}$idx + {$colname['F']}$idx") ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, "={$colname['L']}$idx + {$colname['I']}$idx") ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, $report[$i]->BANK_INTEREST) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, $report[$i]->CALC_INTEREST) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['Q'], $idx, $report[$i]->ACTUAL) ;
                            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['M'], $idx, "={$colname['J']}$idx + {$colname['F']}$idx") ;
                            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, "={$colname['L']}$idx + {$colname['I']}$idx") ;
                            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, "={$colname['I']}$idx * 70 / 100") ;
                            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, "={$colname['I']}$idx - {$colname['O']}$idx") ;
                        }
                        else {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $report[$i]->CALC_INSTALLMENT) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $report[$i]->ACTUAL) ;
                        }
                        $i++;
                        $idx++;
                    }
                    $currentYear = $nextYear ;
                } while ($i < sizeof($report)) ;
                $saltot = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['A']);
                $ealtot = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['C']);
                $objPHPExcel->getActiveSheet()->mergeCells("{$saltot}{$idx}:{$ealtot}{$idx}");
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx,'TOTAL DURING PERIOD');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalAllInstall + $totalInstall);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalAllInterest + $totalInterest);
                if($CUR_IDC_STAT == 'WITH_IDC') {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['L'], $idx, $totalIDCInt_All + $totalIDCInt);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, $totalSumInt_All + $totalSumInt);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, $totalBank_All + $totalBank);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, $totalComp_All + $totalComp);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['Q'], $idx, $totalAllActual + $totalActual);
                }
                else {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalAll_A + $totalAll);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalAllActual + $totalAllActual);
                }

                if($CUR_IDC_STAT == 'WITH_IDC') {
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['F']}4")->applyFromArray($styleHeader);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['D']}1")->applyFromArray($style_ContentNumeric);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['Q']}6")->applyFromArray($styleHeader);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['Q']}".$idx)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}7:{$colname['Q']}".$idx)->applyFromArray($style_ContentNumeric);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['B']}7:{$colname['B']}".$idx)->applyFromArray($styleHeader);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['G']}7:{$colname['G']}".$idx)->getNumberFormat()->setFormatCode('0.00000%');
                }
                else {
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['F']}4")->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['D']}1")->applyFromArray($style_ContentNumeric);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['K']}6")->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['K']}".$idx)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}7:{$colname['K']}".$idx)->applyFromArray($style_ContentNumeric);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['B']}7:{$colname['B']}".$idx)->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['G']}7:{$colname['G']}".$idx)->getNumberFormat()->setFormatCode('0.00000%');
                }

                if($NEXT_IDC_STAT != $CUR_IDC_STAT && ($NEXT_IDC_STAT != 'SKIP')) {
                    if($NEXT_IDC_STAT == 'WITH_IDC' && $CUR_IDC_STAT == 'WITHOUT_IDC') {
                        $idx_col = array_values($col) ;
                        $ix = 0 ;
                        $max_lat = 0 ;
                        unset($col);
                        foreach (range('A', 'Q') as $alp) {
                            if($ix < sizeof($idx_col)) {
                                $col[$alp] = $idx_col[$ix] + 18 - 7 ;
                                if($ix == (sizeof($idx_col) - 1)) {
                                    $max_lat = $idx_col[$ix] + 1 ;
                                }
                                $ix++;
                            }
                            else {
                                $col[$alp] = $max_lat + 18 - 7;
                                $max_lat ++ ;
                            }
                        }
                    } 
                    else if ($NEXT_IDC_STAT == 'WITHOUT_IDC' && $CUR_IDC_STAT == 'WITH_IDC') {
                        //take 10 from the end array of col, assign to new array of col A till J
                        $col_slice = array_slice($col, 6, 11) ;
                        // var_dump($col_slice);
                        $idx_lastten_col = array_values($col_slice) ;
                        unset($col) ;
                        $ix = 0 ;
                        foreach(range('A', 'K') as $alp) {
                            $col[$alp] = $idx_lastten_col[$ix] + 12 ;
                            $ix++ ;
                        }
                    }
                }
                else {
                    if($CUR_IDC_STAT == 'WITH_IDC') {
                        $al = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P', 'Q'];
                        foreach($al as $x) {
                            $col[$x] +=  18;
                        }
                    }
                    else {
                        $al = ['A','B','C','D','E','F','G','H','I','J', 'K'];
                        foreach($al as $x) {
                            $col[$x] +=  12;
                        }
                    }
                }
                foreach($idx_name as $columnID) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                if($CUR_IDC_STAT == 'WITH_IDC') {
                    $objPHPExcel->getActiveSheet()->getStyle("A5:{$colname['Q']}6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()->getStyle("A{$idx}:{$colname['Q']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                }
                else {
                    $objPHPExcel->getActiveSheet()->getStyle("A5:{$colname['K']}6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()->getStyle("A{$idx}:{$colname['K']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                }
               $CUR_IDC_STAT = $NEXT_IDC_STAT ;
            }
            
            $objPHPExcel->getActiveSheet()->setTitle('Report KI');

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

    public function ExportReportKI_SYD ($param, $location) {
        try {
            $headerq = " SELECT   
                        FM.PK_NUMBER,
                        FM.CREDIT_TYPE,
                        FDKIT.TRANCHE_NUMBER,
                        FDKIT.LOAN_ACCOUNT_NUMBER,
                        FDKIT.LIMIT_TRANCHE,
                        FDKI.TENOR,
                        FDKIT.IDC,
                        B.FCNAME AS BANK,
                        BU.FCNAME AS BUSINESSUNIT,
                        C.COMPANYNAME AS COMPANY
                    FROM FUNDS_MASTER FM
                                LEFT JOIN COMPANY C ON FM.COMPANY = C.ID
                                LEFT JOIN BUSINESSUNIT BU ON FM.BUNIT = BU.ID
                                LEFT JOIN SUPPLIER B ON FM.BANK = B.ID
                                LEFT JOIN FUNDS_DETAIL_KI FDKI ON FM.UUID = FDKI.UUID AND FDKI.ISACTIVE = '1'
                                LEFT JOIN (SELECT UUID, TRANCHE_NUMBER, LOAN_ACCOUNT_NUMBER, LIMIT_TRANCHE, CONTRACT_NUMBER, IDC FROM FUNDS_DETAIL_KI_TRANCHE WHERE ISACTIVE = 1) FDKIT ON FM.UUID = FDKIT.UUID
                    WHERE FM.CREDIT_TYPE = 'KI' AND FM.ISACTIVE = 1 
                    AND FM.PK_NUMBER = '".$param['PK_NUMBER']."' 
            " ;

            $bankq = "SELECT FM.PK_NUMBER,
                            BKI.BANKNAME AS BANK_CODE,
                            B.FCNAME AS BANK_NAME,
                            BKI.PORTION,
                            BKI.PERCENTAGE
                    FROM BANK_KI BKI
                            LEFT JOIN FUNDS_MASTER FM ON FM.UUID = BKI.UUID
                            LEFT JOIN SUPPLIER B ON B.ID = BKI.BANKNAME
                            LEFT JOIN (SELECT UUID, MAX(COUNTER) as max_count FROM BANK_KI GROUP BY UUID) LT_CTR ON LT_CTR.UUID = FM.UUID 
                    WHERE FM.PK_NUMBER = '".$param['PK_NUMBER']."' AND BKI.COUNTER = LT_CTR.max_count ";
            
            $header = $this->db->query($headerq)->result();
            $bank = $this->db->query($bankq)->result();
            $CUR_IDC_STAT = $header[0]->IDC ;
            if($CUR_IDC_STAT == '' || $CUR_IDC_STAT == null) {
                $CUR_IDC_STAT = 'WITHOUT_IDC';
            }
            $tranche = array();
            $all_report = array();
            for ($v = 0 ; $v < sizeof($header) ; $v++) {
                $tranche[$v] = $header[$v]->TRANCHE_NUMBER ;
            }
            // var_dump($tranche); exit;
            foreach($tranche as $row) {
                foreach($bank as $b) {

                    $reportq = "SELECT * FROM FUNDS_KI_REPORT 
                            WHERE PK_NUMBER = '".$param['PK_NUMBER']."' AND TRANCHE_NUMBER = '".$row."'
                            AND BANK = '".$b->BANK_NAME."' 
                            ORDER BY PERIOD_IDX ASC" ;

                    $report = $this->db->query($reportq)->result();
                    $all_report[$row][$b->BANK_NAME] = $report;
                }
            }
            
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator($this->session->userdata('FCCODE'))
            ->setLastModifiedBy($this->session->userdata('FCCODE'))
            ->setTitle('Report KI '. Carbon::now('Asia/Jakarta')->format('d-M-Y')." ".$header[0]->PK_NUMBER)
            ->setSubject('Report KI');

            //style
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '0000000'],
                    ],
                ],
            ];

            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $style_Content = array(          
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            )
            );
            $style_ContentNumeric = array(          
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'numberFormat' => array(
                    'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING
                )
            );

            //table length
            $col =  [
                'A' => 1,
                'B' => 2,
                'C' => 3,
                'D' => 4,
                'E' => 5,
                'F' => 6,
                'G' => 7, 
                'H' => 8,
                'I' => 9,
                'J' => 10,
                'K' => 11
            ] ;
            if($CUR_IDC_STAT == 'WITH_IDC') {
                $col['L'] = 12 ;
                $col['M'] = 13 ;
                $col['N'] = 14 ;
                $col['O'] = 15 ;
                $col['P'] = 16 ;
                $col['Q'] = 17 ;
            }

           
            // var_dump($tranche); exit;
            
            for($k = 0 ; $k < sizeof($tranche) ; $k++) {
                if(($k+1) < sizeof($tranche)) {
                    $NEXT_IDC_STAT = $header[$k+1]->IDC ;
                    if($NEXT_IDC_STAT == null || $NEXT_IDC_STAT == '') {
                        $NEXT_IDC_STAT = 'WITHOUT_IDC' ;
                    }   
                }
                else {
                    $NEXT_IDC_STAT = 'SKIP' ;
                }
                for($l = 0 ; $l < sizeof($bank) ;$l++) {
                    // var_dump($col); exit;
                    $colname = array();
                    $idx_name = array();
                    foreach($col as $ix) {
                        $colname[array_search($ix,$col)] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ix) ;
                        array_push($idx_name, \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ix) );
                    }
                    
                        //setting header excel
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col['A'],1, 'TRANCHE NUMBER')
                    ->setCellValueByColumnAndRow($col['A'],2, 'PK Number')
                    ->setCellValueByColumnAndRow($col['A'],3, 'Bank')
                    ->setCellValueByColumnAndRow($col['A'],4, 'Business Unit')
                    ->setCellValueByColumnAndRow($col['C'],1, 'Limit')
                    ->setCellValueByColumnAndRow($col['G'],1, 'Bank Syndication')
                    ->setCellValueByColumnAndRow($col['G'],2, 'Portion');

                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col['B'],1, $header[$k]->TRANCHE_NUMBER)
                    ->setCellValueByColumnAndRow($col['B'],2, $header[$k]->PK_NUMBER)
                    ->setCellValueByColumnAndRow($col['B'],3, $header[$k]->BANK)
                    ->setCellValueByColumnAndRow($col['B'],4, $header[$k]->BUSINESSUNIT) 
                    ->setCellValueByColumnAndRow($col['D'],1, $header[$k]->LIMIT_TRANCHE)
                    ->setCellValueByColumnAndRow($col['H'],1, $bank[$l]->BANK_NAME)
                    ->setCellValueByColumnAndRow($col['H'],2, floatval(floatval($bank[$l]->PERCENTAGE)/100)) ;
                    
                    //setting table header
                    //PERIOD
                    $s = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['A']);
                    $e =\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['C']);
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->mergeCells("{$s}5:{$e}6");
                    $objPHPExcel->setActiveSheetIndex(0)->getCell("{$s}5")->setValue('Period');
                    
                    //Perhitungan Bunga
                   
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col['D'],6, 'Pokok')
                    ->setCellValueByColumnAndRow($col['E'],6, 'Penarikan')
                    ->setCellValueByColumnAndRow($col['F'],6, 'Cicil Pokok')
                    ->setCellValueByColumnAndRow($col['G'],6, 'Bunga')
                    ->setCellValueByColumnAndRow($col['H'],6, 'Hari' )
                    ->setCellValueByColumnAndRow($col['I'],6, 'Bunga Pokok');
    
                    if($CUR_IDC_STAT == 'WITH_IDC') {
                        $objPHPExcel->getActiveSheet()
                        ->setCellValueByColumnAndRow($col['J'],6, 'IDC')
                        ->setCellValueByColumnAndRow($col['K'],6, 'Cicil IDC')
                        ->setCellValueByColumnAndRow($col['L'],6, 'Bunga IDC')
                        ->setCellValueByColumnAndRow($col['M'],6, 'Total Hutang')
                        ->setCellValueByColumnAndRow($col['N'],6, 'Total Bunga');
                    }

                    // var_dump($colname);

                    if($CUR_IDC_STAT == 'WITH_IDC') {
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['D']}5:{$colname['N']}5");
                        $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['D']}5")->setValue('Perhitungan Bunga');
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['O']}5:{$colname['P']}5") ;
                        $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['O']}5")->setValue("Pembayaran Bunga") ;
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['Q']}5:{$colname['Q']}6");
                    $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['Q']}5")->setValue('ACTUAL');
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($col['O'], 6, 'Bank')
                        ->setCellValueByColumnAndRow($col['P'], 6, $header[$k]->COMPANY);
                    }
                    else {
                         //TOTAL
                        $stota = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['J']);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$stota}5:{$stota}6");
                        $objPHPExcel->setActiveSheetIndex(0)->getCell("{$stota}5")->setValue('TOTAL');
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['K']}5:{$colname['K']}6");
                    $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['K']}5")->setValue('ACTUAL');
                    }
                    $report = $all_report[$tranche[$k]][$bank[$l]->BANK_NAME];
                    $secPart = false ;
                    $i = 0 ; //index report
                    $idx = 7 ; //excel line
                    $totalInstall = 0 ;
                    $totalInterest = 0;
                    // Total Bank Interest
                    $totalbank = 0;
                    $totalbank_A = 0;
                    // Total Vendor Interest
                    $totalven = 0;
                    $totalven_A = 0;
                    //Total IDC
                    $totalIDCInt = 0 ;
                    $totalALLIDCInt = 0;
                    //Total sum interest ;
                    $totalSumInt = 0 ;
                    $totalSumInt_A = 0;


                    $totalAllInstall = 0;
                    $totalAllInterest = 0;

                    $totalAll = 0 ;
                    $totalAll_A = 0 ;
                    $totalAll_Yearly = 0 ;
                    $totalYearlyAll = 0 ;
                    $totalAllIDCInt = 0 ;
                    //totalYearly
                    $totalYearlyInstall = 0;
                    $totalYearlyInterest = 0 ;
                    $totalYearlyIDCInt = 0 ;
                    $totalYearlySumInt = 0 ;
                    $totalYearlyven = 0;
                    $totalYearlybank = 0;
                    $totalYearlyDays = 0 ;

                    //Actual 
                    $totalActual = 0 ;
                    $totalAllActual = 0 ;
                    $totalYearlyActual = 0 ;

                    //paramYearlyAndQuarterly
                    $currentYear = intval($report[$i]->PERIOD_YEAR);
                    $nextYear = intval($report[$i]->PERIOD_YEAR) ;
                    $startmonth = intval($report[$i]->CURRENT_MONTH) ;
    
                    do {
                        $bankamt = intval(intval($report[$i]->CALC_INTEREST) * 70/100) ;
                        $nextYear = intval($report[$i]->PERIOD_YEAR) ;
                        $nextmonth = intval($report[$i]->CURRENT_MONTH);
                        if(!$secPart) {
                            if($startmonth > $nextmonth && $nextmonth < 10) {
                                $startmonth = 12 - $startmonth ;
                            }
                            if($nextmonth - $startmonth == 3) {
                                $secPart = true ;
                                $startmonth = $nextmonth ;
                            }
                        }
                        else if ($secPart) {
                            // var_dump("test");exit;
                            if($CUR_IDC_STAT == 'WITH_IDC') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $report[$i]->CALC_IDC);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['L'], $idx, $totalIDCInt);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, $totalSumInt);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, $totalbank);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, $totalven);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['Q'], $idx, $totalActual);
    
                            }
                            else {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalAll);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalActual);
                            }
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalInstall);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalInterest);

                            $totalAllInstall += $totalInstall;
                            $totalAllInterest +=$totalInterest;
                            $totalAllIDCInt += $totalIDCInt;
                            $totalbank_A += $totalbank;
                            $totalven_A += $totalven ;
                            $totalSumInt_A += $totalSumInt;
                            $totalAll_A += $totalAll ;
                            $totalAllActual += $totalActual ;

                            $totalActual = 0 ;
                            $totalInstall = 0 ;
                            $totalAll = 0 ;
                            $totalInterest = 0;
                            $totalIDCInt = 0;
                            $totalbank = 0;
                            $totalven = 0;
                            $totalSumInt = 0;

                            $startmonth = $nextmonth ;
                            $idx++;
                            $secPart = false ;
                        }
                        if($currentYear < $nextYear) {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx, "TOTAL TAHUN $currentYear");
                            $objPHPExcel->getActiveSheet()->mergeCells("{$colname['A']}{$idx}:{$colname['C']}{$idx}");
                            if($CUR_IDC_STAT == 'WITH_IDC') {
                                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['Q']}{$idx}")->applyFromArray($style_Content);
                                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['Q']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['L'], $idx, $totalYearlyIDCInt);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, $totalYearlySumInt);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, $totalYearlybank);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, $totalYearlyven);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['Q'], $idx, $totalYearlyActual);
                            }
                            else {
                                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['K']}{$idx}")->applyFromArray($style_ContentNumeric);
                            $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['K']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalAll_Yearly);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalAll_Yearly);
                            }
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['H'], $idx, $totalYearlyDays);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalYearlyInstall);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalYearlyInterest);
                            $totalYearlyInstall = 0 ;
                            $totalYearlyInterest = 0 ;
                            $totalYearlyIDCInt = 0 ;
                            $totalYearlySumInt = 0;
                            $totalYearlybank = 0 ;
                            $totalYearlyven = 0;
                            $totalAll_Yearly = 0 ;
                            $totalYearlyDays = 0;
                            $totalYearlyActual = 0 ;
                            $idx++;
                        }
                        else {
                            $totalInstall += intval($report[$i]->INSTALLMENT) ;
                            $totalInterest += intval($report[$i]->CALC_INTEREST);
                            $totalAll += intval($report[$i]->CALC_INSTALLMENT);
                            $totalIDCInt += intval($report[$i]->IDC_INTEREST);
                            $totalbank += $bankamt;
                            $totalven += intval($report[$i]->CALC_INTEREST) - $bankamt ;
                            $totalSumInt += intval($report[$i]->CALC_INTEREST) + intval($report[$i]->CALC_IDC);
                            $totalAll += intval($report[$i]->CALC_INSTALLMENT);
                            $totalActual += intval($report[$i]->ACTUAL);

                            //Yearly
                            $totalYearlyInstall += intval($report[$i]->INSTALLMENT) ;
                            $totalYearlyInterest += intval($report[$i]->CALC_INTEREST);
                            $totalYearlyAll += intval($report[$i]->CALC_INSTALLMENT);
                            $totalYearlyIDCInt += intval($report[$i]->IDC_INTEREST);
                            $totalYearlybank += $bankamt;
                            $totalYearlyven += intval($report[$i]->CALC_INTEREST) - $bankamt ;
                            $totalYearlySumInt += intval($report[$i]->CALC_INTEREST) + intval($report[$i]->CALC_IDC);
                            $totalYearlyDays += intval($report[$i]->DAY);
                            $totalAll_Yearly += intval($report[$i]->CALC_INSTALLMENT);
                            $totalYearlyActual += intval($report[$i]->ACTUAL);

                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx, $report[$i]->START_PERIOD) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['B'], $idx, "-") ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['C'], $idx, $report[$i]->END_PERIOD) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['D'], $idx, $report[$i]->REMAINING_BALANCE) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['E'], $idx, $report[$i]->WITHDRAWAL) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $report[$i]->INSTALLMENT) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['G'], $idx, floatval($report[$i]->INTEREST)/100) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['H'], $idx, $report[$i]->DAY) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $report[$i]->CALC_INTEREST) ;
                            if($CUR_IDC_STAT == 'WITH_IDC') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $report[$i]->CALC_IDC) ;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, "-") ;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['L'], $idx, $report[$i]->IDC_INTEREST) ;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['M'], $idx, "={$colname['J']}$idx + {$colname['F']}$idx") ;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, "={$colname['L']}$idx + {$colname['I']}$idx") ;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, "={$colname['I']}$idx * 70 / 100") ;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, "={$colname['I']}$idx - {$colname['O']}$idx") ;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['Q'], $idx, $report[$i]->ACTUAL) ;
                            }
                            else {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $report[$i]->CALC_INSTALLMENT) ;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $report[$i]->ACTUAL) ;
                            }
                            
                            $i++;
                            $idx++;
                        }
                        $currentYear = $nextYear ;
    
                    } while ($i < sizeof($report)) ;
                    $saltot = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['A']);
                    $ealtot = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col['C']);
                    $objPHPExcel->getActiveSheet()->mergeCells("{$saltot}{$idx}:{$ealtot}{$idx}");
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx,'TOTAL DURING PERIOD');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalAllInstall + $totalInstall);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalAllInterest + $totalInterest);
                    if($CUR_IDC_STAT == 'WITH_IDC') {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['L'], $idx, $totalAllIDCInt + $totalIDCInt);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['N'], $idx, $totalSumInt_A + $totalSumInt);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['O'], $idx, $totalbank_A + $totalbank);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['P'], $idx, $totalven_A + $totalven);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['Q'], $idx, $totalAllActual + $totalActual);
                    }
                    else {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalAll_A + $totalAll);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalAllActual + $totalAllActual);
                    }

                    
                    
                   
                if($CUR_IDC_STAT == 'WITH_IDC') {
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['F']}4")->applyFromArray($styleHeader);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['D']}1")->applyFromArray($style_ContentNumeric);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['Q']}6")->applyFromArray($styleHeader);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['Q']}".$idx)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}7:{$colname['Q']}".$idx)->applyFromArray($style_ContentNumeric);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['B']}7:{$colname['B']}".$idx)->applyFromArray($styleHeader);
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['G']}7:{$colname['G']}".$idx)->getNumberFormat()->setFormatCode('0.00000%');
                }
                else {
                    $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['F']}4")->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['D']}1")->applyFromArray($style_ContentNumeric);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['K']}6")->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['K']}".$idx)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}7:{$colname['K']}".$idx)->applyFromArray($style_ContentNumeric);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['B']}7:{$colname['B']}".$idx)->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['G']}7:{$colname['G']}".$idx)->getNumberFormat()->setFormatCode('0.00000%');
                }
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['H']}2")->getNumberFormat()->applyFromArray([
                    'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00
                ]);
                    // var_dump($colname);
                if($NEXT_IDC_STAT != $CUR_IDC_STAT && ($NEXT_IDC_STAT != 'SKIP')) {
                    if($NEXT_IDC_STAT == 'WITH_IDC' && $CUR_IDC_STAT == 'WITHOUT_IDC') {
                        $idx_col = array_values($col) ;
                        $ix = 0 ;
                        $max_lat = 0 ;
                        unset($col);
                        foreach (range('A', 'Q') as $alp) {
                            if($ix < sizeof($idx_col)) {
                                $col[$alp] = $idx_col[$ix] + 18 - 7 ;
                                if($ix == (sizeof($idx_col) - 1)) {
                                    $max_lat = $idx_col[$ix] + 1 ;
                                }
                                $ix++;
                            }
                            else {
                                $col[$alp] = $max_lat + 18 - 7;
                                $max_lat ++ ;
                            }
                        }
                    } 
                    else if ($NEXT_IDC_STAT == 'WITHOUT_IDC' && $CUR_IDC_STAT == 'WITH_IDC') {
                        //take 10 from the end array of col, assign to new array of col A till J
                        $col_slice = array_slice($col, 6, 11) ;
                        // var_dump($col_slice);
                        $idx_lastten_col = array_values($col_slice) ;
                        unset($col) ;
                        $ix = 0 ;
                        foreach(range('A', 'K') as $alp) {
                            $col[$alp] = $idx_lastten_col[$ix] + 12 ;
                            $ix++ ;
                        }
                    }
                }
                else {
                    if($CUR_IDC_STAT == 'WITH_IDC') {
                        $al = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q'];
                        foreach($al as $x) {
                            $col[$x] +=  18;
                        }
                    }
                    else {
                        $al = ['A','B','C','D','E','F','G','H','I','J','K'];
                        foreach($al as $x) {
                            $col[$x] +=  12;
                        }
                    }
                }
                foreach($idx_name as $columnID) {
                    // var_dump(range($col['A'],$col['J'])); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                // var_dump($objPHPExcel); exit;
                // $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                if($CUR_IDC_STAT == 'WITH_IDC') {
                    $objPHPExcel->getActiveSheet()->getStyle("A5:{$colname['Q']}6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()->getStyle("A{$idx}:{$colname['Q']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                }
                else {
                    $objPHPExcel->getActiveSheet()->getStyle("A5:{$colname['K']}6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()->getStyle("A{$idx}:{$colname['K']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                }
                $CUR_IDC_STAT = $NEXT_IDC_STAT ;
                    // var_dump($col) ;
                }
                // exit;
            }
            // exit;
            // var_dump($idx_name); exit; 
            // var_dump($col); 
            
            $objPHPExcel->getActiveSheet()->setTitle('Report KI');
            
            $return = [
                'STATUS' => TRUE,
                'Data' => $objPHPExcel
            ];
        } catch (Exception $ex) {
            $return = [
                'STATUS' => FALSE,
                'Data' => $ex->getMessage()
            ];
            // var_dump($ex->getMessage()); 
        }
        $this->db->close();
        return $return;
    }

    public function ExportReportKMK($param) {
        ini_set('display_errors', 'On');
        try {
            $headerq = "SELECT FM.PK_NUMBER, FM.CREDIT_TYPE, FM.SUB_CREDIT_TYPE,
                            CASE    
                                WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FRK.CONTRACT_NUMBER
                                WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FRK.CONTRACT_NUMBER
                                WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FRK.CONTRACT_NUMBER
                                ELSE FWA.CONTRACT_NUMBER
                            END
                            AS CONTRACT_NUMBER,
                            CASE    
                                WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FRK.AMOUNT_LIMIT
                                WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FRK.AMOUNT_LIMIT
                                WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FRK.AMOUNT_LIMIT
                                ELSE FWA.AMOUNT_LIMIT
                            END
                            AS AMOUNT_LIMIT,
                            CASE    
                                WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FRK.TENOR
                                WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FRK.TENOR
                                WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FRK.TENOR
                                ELSE FWA.TENOR
                            END
                            AS TENOR,
                            CASE    
                                WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FRK.INTEREST_PAYMENT_SCHEDULE_DATE
                                WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FRK.INTEREST_PAYMENT_SCHEDULE_DATE
                                WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FRK.INTEREST_PAYMENT_SCHEDULE_DATE
                                ELSE FWA.INTEREST_PAYMENT_SCHEDULE_DATE
                            END
                            AS DATE_SCHED,
                            B.FCNAME AS BANK,
                            BU.FCNAME AS BUSINESSUNIT,
                            C.COMPANYNAME ||' ' || '-' ||' '|| C.COMPANYCODE AS COMPANY
                        FROM FUNDS_MASTER FM
                        LEFT JOIN FUNDS_DETAIL_RK FRK ON FRK.UUID = FM.UUID AND FRK.ISACTIVE = 1
                        LEFT JOIN FUNDS_DETAIL_WA FWA ON FWA.UUID = FM.UUID AND FWA.SUB_CREDIT_TYPE = 'WA' AND FWA.ISACTIVE = 1
                        LEFT JOIN SUPPLIER B ON FM.BANK = B.ID
                        LEFT JOIN BUSINESSUNIT BU ON FM.BUNIT = BU.ID
                        LEFT JOIN COMPANY C ON FM.COMPANY = C.ID
                        WHERE FM.CREDIT_TYPE = 'KMK' AND FM.PK_NUMBER = '".$param['PK_NUMBER']."'" ;
            $header = $this->db->query($headerq)->row() ;

            //get report from funds_kmk_report
            $period = '' ;
            if($param['START_PERIOD'] != '0' && $param['END_PERIOD'] != '0') {
                $period .= "AND START_PERIOD >= TO_DATE('".$param['START_PERIOD']."', 'mm-dd-yyyy') AND END_PERIOD <= TO_DATE('".$param['END_PERIOD']."', 'mm-dd-yyyy') ";
            }
            else if ($param['END_PERIOD'] == '0' && $param['START_PERIOD'] != '0') {
                $period.= "AND START_PERIOD >= TO_DATE('".$param['START_PERIOD']."', 'mm-dd-yyyy')";
            }
            else if ($param['START_PERIOD'] == '0' && $param['START_PERIOD'] != '0') {
                $period.= "AND END_PERIOD <= TO_DATE('".$param['END_PERIOD']."', 'mm-dd-yyyy')";
            } else {
                $period = '';
            }

            $reportq = "SELECT A.*, TO_CHAR(A.END_PERIOD, 'YYYY-fmMM-fmDD') AS TMP_END_PERIOD FROM FUNDS_KMK_REPORT A
                        WHERE PK_NUMBER = '".$param['PK_NUMBER']."' AND CONTRACT_NUMBER = '".$param['CONTRACT_NUMBER']."' 
                        ".$period."
                        ORDER BY PERIOD_IDX ASC" ;
            $report = $this->db->query($reportq)->result();
            // var_dump($report) ; exit;
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator($this->session->userdata('FCCODE'))
            ->setLastModifiedBy($this->session->userdata('FCCODE'))
            ->setTitle('Report KMK '. Carbon::now('Asia/Jakarta')->format('d-M-Y')." ".$header->PK_NUMBER)
            ->setSubject('Report KMK');
            // echo "<pre>";
            // var_dump($param);
            // var_dump('header\n');
            // var_dump($header);
            // var_dump('\n');
            // var_dump($report);
            // exit;
            //style
            $styleArray = [
                'borders' => [
                    'allBorders' =>[ 
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                    ],
                ],
            ];

            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ],
            ];

            $style_Content = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            $style_ContentNumeric = array(          
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ),
                'numberFormat' => array(
                        'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING
                    )
            );
            $isWA = ($header->SUB_CREDIT_TYPE == 'WA') ;
            if($isWA) {
                $col = [
                    'A' => 1,
                    'B' => 2,
                    'C' => 3,
                    'D' => 4,
                    'E' => 5, 
                    'F' => 6,
                    'G' => 7,
                    'H' => 8,
                    'I' => 9,
                    'J' => 10,
                    'K' => 11
                ];
            }
            else {
                $col = [
                    'A' => 1,
                    'B' => 2,
                    'C' => 3,
                    'D' => 4,
                    'E' => 5, 
                    'F' => 6,
                    'G' => 7,
                    'H' => 8,
                    'I' => 9,
                    'J' => 10
                ];
    
            }
            
            $colname = array();
            $idx_name = array();
            foreach($col as $ix) {
                $colname[array_search($ix,$col)] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ix) ;
                array_push($idx_name, \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ix) );
            }
            // var_dump($report[0]);exit;
            
            //setting header excel
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow($col['A'],1, 'PK Number')
            ->setCellValueByColumnAndRow($col['A'], 2, 'Contract Number')
            ->setCellValueByColumnAndRow($col['A'],3, 'Bank')
            ->setCellValueByColumnAndRow($col['A'],4, 'Business Unit / Company')
            ->setCellValueByColumnAndRow($col['C'],1, 'Limit')
            ->setCellValueByColumnAndRow($col['C'],2, 'Period');

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow($col['B'],1, $header->PK_NUMBER)
            ->setCellValueByColumnAndRow($col['B'],2, $header->CONTRACT_NUMBER)
            ->setCellValueByColumnAndRow($col['B'],3, $header->BANK)
            ->setCellValueByColumnAndRow($col['B'],4, "$header->BUSINESSUNIT / $header->COMPANY") 
            ->setCellValueByColumnAndRow($col['D'],1, $header->AMOUNT_LIMIT)
            ->setCellValueByColumnAndRow($col['D'],2, $param['START_PERIOD'])
            ->setCellValueByColumnAndRow($col['E'],2, 'To')
            ->setCellValueByColumnAndRow($col['F'],2, $param['END_PERIOD']);

            $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells("{$colname['A']}5:{$colname['C']}6");
            $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['A']}5")->setValue('Period');

            if($isWA) {
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['D']}5:{$colname['J']}5");
                $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['D']}5")->setValue('Perhitungan Bunga');
            }
           else {
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['D']}5:{$colname['I']}5");
                $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['D']}5")->setValue('Perhitungan Bunga');
           }
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow($col['D'],6, 'Pokok')
            ->setCellValueByColumnAndRow($col['E'],6, 'Penarikan')
            ->setCellValueByColumnAndRow($col['F'],6, 'Cicil Pokok')
            ->setCellValueByColumnAndRow($col['G'],6, 'Bunga')
            ->setCellValueByColumnAndRow($col['H'],6, 'Hari');
            
            if($isWA) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col['J'],6, 'Bunga Pokok')
                ->setCellValueByColumnAndRow($col['I'], 6, '\'@ payment');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['K']}5:{$colname['K']}6");
                $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['K']}5")->setValue('TOTAL');
            }
            else {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col['I'],6, 'Bunga Pokok');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$colname['J']}5:{$colname['J']}6");
                $objPHPExcel->setActiveSheetIndex(0)->getCell("{$colname['J']}5")->setValue('TOTAL');
            }
            

            $i = 0 ;
            $idx = 7 ;
            $totalInstall = 0 ;
            $totalInterest = 0;
            $totalAll_A = 0;
            $totalAll = 0 ;
            $totalAllInstall = 0;
            $totalAllInterest = 0;
            $totalYearlyInstall = 0;
            $totalYearlyInterest = 0 ;
            $totalYearlyAll = 0 ;
            $totalYearlyDays = 0 ;
            $totalAtPayment = 0 ;
            $totalYearlyAtPayment = 0 ;
            $totalAllAtPayment = 0 ;
            $calctotal = 0 ;
            $calcinterest = 0 ;
            $intAtPayment = 0 ;

            $currentYear = intval($report[$i]->PERIOD_YEAR);
            $nextYear = intval($report[$i]->PERIOD_YEAR) ;
            $startmonth = intval($report[$i]->PERIOD) ;
            // var_dump('test'); exit;
            do {
                $nextYear = intval($report[$i]->PERIOD_YEAR) ;
                $nextmonth = intval($report[$i]->PERIOD);
                $intAtPayment = 0 ;
                if($nextmonth - $startmonth == 3) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalInstall);
                    if($isWA) {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalAtPayment);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalInterest);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalAll);
                    }
                    else {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalInterest);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalAll);
                    }

                    $totalAllInstall += $totalInstall;
                    $totalAllAtPayment += $totalAtPayment ;
                    $totalAllInterest +=$totalInterest;
                    $totalAll_A += $totalAll;

                    // var_dump($totalInstall);

                    $totalInstall = 0 ;
                    $totalInterest = 0;
                    $totalAtPayment = 0 ;
                    $totalAll = 0 ;
                    $startmonth = $nextmonth ;
                    $idx++;
                }
                if($currentYear < $nextYear) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx, "TOTAL TAHUN $currentYear");
                    $objPHPExcel->getActiveSheet()->mergeCells("{$colname['A']}{$idx}:{$colname['C']}{$idx}");
                    if($isWA) {
                        $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['K']}{$idx}")->applyFromArray($style_Content);
                        $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['K']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalYearlyAll);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalYearlyAtPayment);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalYearlyInterest);

                    }
                    else {
                        $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['J']}{$idx}")->applyFromArray($style_Content);
                        $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}{$idx}:{$colname['J']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalYearlyAll);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalYearlyInterest);

                    }
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalYearlyInstall);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['H'], $idx, $totalYearlyDays);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['D'], $idx, $report[$i]->REMAINING_BALANCE) ;
                    $totalYearlyInstall = 0 ;
                    $totalYearlyInterest = 0 ;
                    $totalYearlyAll = 0;
                    $totalYearlyDays = 0;
                    $totalYearlyAtPayment = 0 ;
                    $idx++;
                }
                else {
                    if($isWA) {
                        $temp_EndPeriod = explode('-', $report[$i]->TMP_END_PERIOD) ;
                        if($temp_EndPeriod[2] == $header->DATE_SCHED) {
                            // $dateSched = $temp_EndPeriod[1].'-'.$temp_EndPeriod[2].'-'.$temp_EndPeriod[0] ; //mm-dd-yyyy
                            $FdateSched = DateTime::createFromFormat('Y-m-d', $report[$i]->TMP_END_PERIOD);
                        }
                        if($report[$i]->INSTALLMENT != '0' || $report[$i]->INSTALLMENT != 0 ) {
                            $payDate = DateTime::createFromFormat('Y-m-d', $report[$i]->TMP_END_PERIOD);
                            $fdiffdt = date_diff($payDate, $FdateSched) ;
                            $diffdt = intval($fdiffdt->d) ;
                            // var_dump($payDate, $FdateSched);
                            // var_dump($diffdt);
                            $intAtPayment = intval(intval($report[$i]->INSTALLMENT) * floatval($report[$i]->INTEREST) / 100 * $diffdt / 360) ;
                            $totalYearlyAtPayment += $intAtPayment;
                            $totalAtPayment += $intAtPayment;
                            //calculated interest
                            $calcinterest = $report[$i]->CALC_INTEREST - $intAtPayment ;
                            $calctotal = $report[$i]->INSTALLMENT + $calcinterest ;
                            $totalInterest += intval($calcinterest);
                            $totalYearlyInterest += intval($calcinterest);
                        }
                        else {
                            $calcinterest = $report[$i]->CALC_INTEREST ;
                            $calctotal = $report[$i]->INSTALLMENT + $calcinterest ;
                            $totalInterest += intval($report[$i]->CALC_INTEREST);
                            $totalYearlyInterest += intval($report[$i]->CALC_INTEREST);
                        }
                        $totalAll += $calctotal;
                        $totalYearlyAll += $calctotal;
                    }
                    else {
                        $totalInterest += intval($report[$i]->CALC_INTEREST);
                        $totalYearlyInterest += intval($report[$i]->CALC_INTEREST);
                        $totalAll += intval($report[$i]->CALC_INSTALLMENT);
                        $totalYearlyAll += intval($report[$i]->CALC_INSTALLMENT);
                    }

                    $totalInstall += intval($report[$i]->INSTALLMENT) ;
                    $totalYearlyInstall += intval($report[$i]->INSTALLMENT) ;
                    $totalYearlyDays += intval($report[$i]->DAY);

                    
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx, $report[$i]->START_PERIOD) ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['B'], $idx, "-") ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['C'], $idx, $report[$i]->END_PERIOD) ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['D'], $idx, $report[$i]->REMAINING_BALANCE) ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['E'], $idx, $report[$i]->WITHDRAWAL) ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $report[$i]->INSTALLMENT) ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['G'], $idx, floatval($report[$i]->INTEREST)/100) ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['H'], $idx, $report[$i]->DAY) ;
                    if($isWA) {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $intAtPayment) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $calcinterest) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $calctotal) ;
                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $report[$i]->CALC_INTEREST) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $report[$i]->CALC_INSTALLMENT) ;
                    }
                    $idx++;
                    $i++;
                }
                $currentYear = $nextYear ;
            } while ($i < sizeof($report)) ;

            $objPHPExcel->getActiveSheet()->mergeCells("{$colname['A']}{$idx}:{$colname['C']}{$idx}");
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['A'], $idx,'TOTAL DURING PERIOD');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['F'], $idx, $totalAllInstall + $totalInstall);
            if($isWA) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalAllAtPayment + $intAtPayment);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalAllInterest + $totalInterest);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['K'], $idx, $totalAll_A + $totalAll);
            }
            else {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['I'], $idx, $totalAllInterest + $totalInterest);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col['J'], $idx, $totalAll_A + $totalAll);
            }
            
            $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['F']}4")->applyFromArray($styleHeader);
            $objPHPExcel->getActiveSheet()->getStyle("{$colname['D']}1")->applyFromArray($style_ContentNumeric);
            if($isWA) {
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['K']}6")->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['K']}".$idx)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}7:{$colname['K']}".$idx)->applyFromArray($style_ContentNumeric);
            }
            else {
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['J']}6")->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}1:{$colname['J']}".$idx)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle("{$colname['A']}7:{$colname['J']}".$idx)->applyFromArray($style_ContentNumeric);
            }
           
            $objPHPExcel->getActiveSheet()->getStyle("{$colname['B']}7:{$colname['B']}".$idx)->applyFromArray($styleHeader);
            $objPHPExcel->getActiveSheet()->getStyle("{$colname['G']}7:{$colname['G']}".$idx)->getNumberFormat()->setFormatCode('0.00000%');
            // var_dump('test'); exit;
            foreach($idx_name as $columnID) {
                // var_dump($columnID);
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            // var_dump($idx_name);exit;
            if($isWA) {
                $objPHPExcel->getActiveSheet()->getStyle("A5:{$colname['K']}6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle("A{$idx}:{$colname['K']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            }
            else {
                $objPHPExcel->getActiveSheet()->getStyle("A5:{$colname['J']}6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A{$idx}:{$colname['J']}{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
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
        // exit;
        $this->db->close();
        return $return ;
    }

    public function getPayAndWDRK ($param) {
        $headerq = "SELECT FM.UUID,
                            FM.PK_NUMBER,
                            FM.COMPANY,
                            FRK.DOCDATE,
                            FM.SUB_CREDIT_TYPE,
                            FRK.CONTRACT_NUMBER,
                            FRK.TENOR,
                            FRK.LOAN_ACCOUNT_NUMBER,
                            TO_CHAR (FRK.FIRST_DATE_INTEREST_PAYMENT, 'dd') AS FIRST_DATE
                    FROM FUNDS_MASTER FM
                    LEFT JOIN FUNDS_DETAIL_RK FRK ON FRK.UUID = FM.UUID
                    WHERE FRK.ISACTIVE = '1' AND FM.PK_NUMBER = '".$param['PK_NUMBER']."' AND FRK.CONTRACT_NUMBER = '".$param['CONTRACT_NUMBER']."'
                    " ;
        
        $header = $this->db->query($headerq)->row();

        $trans_rkq = "  select 
                            fm.COMPANY,
                            ffx.POSTING_DATE,
                            ffx.CURRENCY,
                            ffx.AMOUNT_TSL AS AMOUNT,
                            ffx.DEBITCREDIT_IND,
                        RRK.ACCOUNT_NUM AS ACCOUNT_NUMBER
                        from faglflexa_rec ffx
                        LEFT JOIN rek_acc_rk rrk
                                ON rrk.COA = ffx.ACCOUNT_NUMBER and rrk.company = ffx.companycode
                        LEFT JOIN funds_detail_ki fd
                            ON fd.loan_account_number = rrk.account_num
                        LEFT JOIN funds_master fm
                            on fm.company = rrk.company
                        UNION
                        SELECT 
                            fm.COMPANY,
                                bbk.POSTING_DATE,
                            bbk.CURRENCY,
                            bbk.AMOUNT_LOC AS AMOUNT,
                            CASE 
                                    WHEN BBK.AMOUNT_LOC < 0 THEN 'H'
                                    ELSE 'S'
                                END AS DEBITCREDIT_IND,
                            RRK.ACCOUNT_NUM AS ACCOUNT_NUMBER
                        FROM bsikbsak_rec bbk
                            LEFT JOIN rek_acc_rk rrk
                                ON RRK.VENDORCODE = bbk.FCCODE AND RRK.PART_BANK = BBK.VENDOR_BANK
                            LEFT JOIN funds_detail_ki fd
                                ON fd.loan_account_number = rrk.account_num
                            LEFT JOIN funds_master fm
                                on fm.company = rrk.company" ;
        
        $paymentq = "SELECT 
                    TO_CHAR(A.posting_date, 'fmMM/DD/YYYY') AS PAYMENT_DATE,
                    posting_date,
                    ABS(A.amount) as INSTALLMENT FROM (". $trans_rkq .") A where A.debitcredit_ind = 'H' AND A.account_number = '".$header->LOAN_ACCOUNT_NUMBER."' ORDER BY posting_date ASC" ;

        $withdrawalq = "SELECT 
                        TO_CHAR(A.posting_date, 'fmMM/DD/YYYY') AS VALUE_DATE,
                        posting_date,
                        ABS(a.amount) AS AMOUNT FROM (". $trans_rkq .") A where A.debitcredit_ind = 'S' AND A.account_number = '".$header->LOAN_ACCOUNT_NUMBER."' ORDER BY posting_date ASC";

        $payment = $this->db->query($paymentq)->result();
        $withdrawal = $this->db->query($withdrawalq)->result();
        // var_dump($this->db->last_query()); exit;
        // echo "<pre>";
        // var_dump($payment, $withdrawal); exit;

        return [$payment, $withdrawal];
    }
    public function ExportRecapReport ($param) {
        ini_set('memory_limit', '256M');
        // exit;
        //Case all company report :
        // a. Define length of report :
        //   1. Take minimum year and maximum of all contract
        //   2. In a year there's 13 cells, so the formula : length = (maxyear - minyear) * 13
        //   3. Sum with remaining cells and convert it to colchar of excel (array of alphabeth from start point to length base on number)

        // b. Store data into report :
        //     1. Make into object of =>
                // RECAPOBJ[C]
                //     =>
                //     COMPANY (C)
                //         -> DRAWDOWN (DD)
                //             ->CURRENCY (CC)
                //                 ->[TRANCHEA] (XTR)
                                        // ->[IDC]
                                            //->YEARYYYY
                        //                         ->MONTHX => AMOUNT
                        //                         ->MONTHX => AMOUNT (X12)
                                        // ->[NONIDC]
                //                     
                //         -> INSTALLMENT (ITL)
                //              ->CURRENCY (CC)
                //                 ->[TRANCHEA] (XTR)
                //                     ->YEARYYYY
                //                         ->MONTHX => AMOUNT
                //                         ->MONTHX => AMOUNT (X12)
                //         -> INTEREST (ITR)
                //             ->CURRENCY (CC)
                //                 ->[TRANCHEA] (XTR)
                //                     ->YEARYYYY
                //                         ->MONTHX => AMOUNT
                //                         ->MONTHX => AMOUNT (X12)

        // c. iterate through object 
        //IMPLEMENTATION :
        //--------------
        //start index
        try {
            $heightstart = 3 ;
            $lengthstart = 5 ;
    
            $COND = array();
            $WHERE = "";
            if($param['COMPANY'] != null || $param['COMPANY'] != '') {
                $comp = explode(',', $param['COMPANY']) ;
                for($i = 0 ; $i < sizeof($comp) ; $i++) {
                    $comp[$i] = "'".$comp[$i]."'" ;
                }
                $newcond = implode(',', $comp);
                array_push($COND, "COMPANYCODE IN (".$newcond.")");
            }
            if($param['START'] != null || $param['START'] != '') {
                $period = explode($param['START'], '/') ;
                array_push($COND, "FRK.PERIOD_MONTH > {$period[0]} AND FRK.PERIOD_YEAR >". $period[1]);
            }
            if($param['END'] != null || $param['END'] != '') {
                $period = explode($param['END'], '/') ;
                array_push($COND, "FRK.PERIOD_MONTH < {$period[0]} AND FRK.PERIOD_YEAR <". $period[1]);
            }
    
            if(sizeof($COND) != 0) {
                $WHERE = "WHERE " ;
                $x = 0 ;
                foreach ($COND as $cond) {
                    if($x != 0) {
                        $WHERE .= "AND ";
                    }
                    $WHERE .= $cond ;
                    $x++;
                }
            }
            $recapcompanyq = "    SELECT C.COMPANYNAME,
                                        C.COMPANYCODE,
                                        FRK.PK_NUMBER,
                                        FRK.TRANCHE_NUMBER,
                                        FRK.PERIOD_MONTH,
                                        FRK.PERIOD_YEAR,
                                        SUM (FRK.WITHDRAWAL) AS WITHDRAWAL,
                                        CASE
                                            WHEN PAY.IS_PAID = 1 THEN SUM (FRK.INSTALLMENT) 
                                            ELSE 0
                                            END AS INSTALLMENT,
                                        SUM (FRK.CALC_INTEREST) AS INTEREST,
                                        SUM (FRK.IDC_INSTALLMENT) AS IDC_INSTALLMENT,
                                        SUM (FRK.CALC_IDC) AS IDC_DRAWDOWN,
                                        SUM (FRK.IDC_INTEREST) AS IDC_INTEREST,
                                        FD.IDC AS IDC_STAT,
                                        FD.CURRENCY,
                                        PAY.IS_PAID
                                FROM FUNDS_KI_REPORT FRK
                                        LEFT JOIN FUNDS_MASTER FM ON FM.UUID = FRK.UUID
                                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FD ON FM.UUID = FD.UUID AND FRK.TRANCHE_NUMBER = FD.TRANCHE_NUMBER AND FD.ISACTIVE = '1'
                                        LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                                        LEFT JOIN
                                        (SELECT UUID,
                                                PERIOD_YEAR,
                                                PERIOD_MONTH,
                                                IS_PAID,
                                                IS_PAYMENT,
                                                CONTRACT_NUMBER
                                        FROM FUNDSPAYMENT) PAY
                                        ON     FRK.UUID = PAY.UUID
                                            AND PAY.PERIOD_YEAR = FRK.PERIOD_YEAR
                                            AND PAY.PERIOD_MONTH = FRK.PERIOD_MONTH
                                            AND PAY.CONTRACT_NUMBER = FD.CONTRACT_NUMBER " .$WHERE. 
                                "
                                GROUP BY FRK.PERIOD_YEAR,
                                        FRK.PERIOD_MONTH,
                                        FRK.PK_NUMBER,
                                        FRK.TRANCHE_NUMBER,
                                        PAY.IS_PAID,
                                        C.COMPANYNAME,
                                        C.COMPANYCODE,
                                        FD.CURRENCY,
                                        FD.IDC
                                ORDER BY FRK.PERIOD_YEAR ASC, FRK.PERIOD_MONTH ASC " ;
            // var_dump($recapcompanyq); exit;
            $minmaxrecapyearq = "SELECT MAX(PERIOD_YEAR) AS MX, MIN(PERIOD_YEAR) AS MN FROM ({$recapcompanyq}) WHERE PERIOD_YEAR > 1000" ;
            $recapcompany = $this->db->query($recapcompanyq)->result();
            $minmax = $this->db->query($minmaxrecapyearq)->row();
            $rangeyear = intval($minmax->MX) - intval($minmax->MN) ;
            // var_dump($minmax); exit;
            //prep style
            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $style_Content = array(          
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                )
                );
            $style_ContentNumeric = array(          
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'numberFormat' => array(
                    'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING
                )
            );
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '0000000'],
                    ],
                ],
            ];
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator($this->session->userdata('FCCODE'))
                ->setLastModifiedBy($this->session->userdata('FCCODE'))
                ->setTitle('Recap Report')
                ->setSubject('Report ');
            //make col header
            $length = $lengthstart ;
            $colstartgen = 0 ;
            $colendgen = 0 ; 
            $startcol = 0 ;
            $endcol = 0;
            $years = range($minmax->MN, $minmax->MX) ;
            $months = ['JAN','FEB','MAR','APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
            $letter = range('A','Z');
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            for ($i = 0 ; $i <= $rangeyear ; $i++) {
                $idxmonth = 0 ;
                $suffixyear = substr($years[$i], 2);
                if($i == 0) {
                    $startcol = $length ;
                    $colstartgen = $length ;
                }
                else {
                    $startcol = $endcol + 1;
                }
                $endcol = $startcol + 11 ;
                $cols = range($startcol, $endcol);
                // var_dump(sizeof($cols));
                //name col
                $startcol_name = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startcol) ;
                $endcol_name = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endcol) ;
                // var_dump($startcol_name, $endcol_name);
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells("{$startcol_name}1:{$endcol_name}1");
                $objPHPExcel->setActiveSheetIndex(0)->getCell("{$startcol_name}1")->setValue($years[$i]);
                foreach ($cols as $indexcol) {
                    // var_dump($indexcol);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($indexcol, 2, "{$months[$idxmonth]}-$suffixyear");
                    $colname = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indexcol);
                    $objPHPExcel->getActiveSheet()->getColumnDimension($colname)->setOutlineLevel(1);                    
                    $objPHPExcel->getActiveSheet()->getColumnDimension($colname)->setAutoSize(true);
                    $idxmonth++;
                }
                $endcol++;
                $endcol_name = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endcol) ;
                $objPHPExcel->getActiveSheet()->getColumnDimension($endcol_name)->setAutoSize(true);                    
                $objPHPExcel->getActiveSheet()->mergeCells("{$endcol_name}1:{$endcol_name}2");
                $objPHPExcel->getActiveSheet()->getCell("{$endcol_name}1")->setValue("TOTAL {$years[$i]}");
            }
            $colendgen = $endcol ;
            $recapArr = $this->createRecapArr($recapcompany) ;
            //input data
            $height = $heightstart ;
            $length = $lengthstart ;
            $objPHPExcel->getActiveSheet()->freezePane('E3');
            foreach ($recapArr as $Code => $Company) { //Iterate Through Company
                foreach($Company as $dttype => $Types) { //Iterate Through Types : Drawdown, Installment, Outstanding, Interest
                        foreach($Types as $curr => $IDCType) { //Iterate Through Currency[] => IDC[]
                            $title = "$Code - $dttype - $curr" ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $height, $title) ;
                            $mainTitleHeight = $height ;
                            $height++ ;
                            foreach($IDCType['IDC'] as $item) {
                                if($item == null) {
                                    $skipidc = true ;
                                    $Tr = $IDCType['POKOK'] ;
                                }
                                else {
                                    $skipidc = false ;
                                    break;
                                }
                            }
                            if($skipidc) {
                                $totalPerMonth = [];
                                $startTotMth = true ;
                                $idxBul = 0 ;
                                $bullets = array_slice($letter, 0, sizeof($Tr)) ; 
                                $idxTranche = 1 ;
                                $maxIdxTranche = sizeof($Tr) ;
                                $startTotalMth = 0 ;
                                $endTotalMth = 0 ;
                                    foreach($Tr as $trancheName => $Year){ //Iterate Tranche, in tranche there's years
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $height, $bullets[$idxBul]);
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $height, $trancheName);
                                        $idxBul ++ ;
                                        $startcol = 0 ;
                                        $endcol = 0 ;
                                        $startYear = true ;
                                        $yearIsEmpty = false ;
                                        foreach($years as $Y) { //Iterate Through Years, there's months and value 
                                            $startTotalTr = 0 ;
                                            $endTotalTr = 0 ;
                                            if(!array_key_exists($Y, $Year)) {
                                                $yearIsEmpty = true ;
                                            }
                                            else {
                                                $dataYear = $Year[$Y] ;
                                                $yearIsEmpty = false ;
                                            }
                                            if($startYear) {
                                                $startcol = $length;
                                                $startYear = false ;
                                            }
                                            else {
                                                $startcol = $endcol + 1 ;
                                            }
                                            $endcol = $startcol + 12 ;
                                            $startcol_name = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startcol) ;
                                            $endcol_name = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endcol - 1) ;
                                            $cols = range($startcol, $endcol) ;
                                            foreach($cols as $idx => $col) {
                                                if($startTotMth) {
                                                    $startTotalMth = $height ;
                                                }
                                                if($idx == 0) {
                                                    $startTotalTr = $col ;
                                                }
                                                if($idx == (sizeof($cols) - 2)) {
                                                    $endTotalTr = $col ;
                                                }
                                                $month = intval($idx) + 1 ;
                                                if($yearIsEmpty) {
                                                    $amount = null ;
                                                }
                                                else {
                                                    if(!array_key_exists(strval($month), $dataYear)){
                                                        $amount = null ;
                                                    }
                                                    else {
                                                        if($dataYear[strval($month)] == null) {
                                                            $amount = null ;
                                                        }
                                                        else {
                                                            $amount = intval($dataYear[strval($month)]) ;
                                                        }
                                                    }
                                                }
                                                if($idx == 12) {
                                                    $colnameStr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startTotalTr) ;
                                                    $colnameEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endTotalTr) ;
                                                    $sumTr = "=SUM($colnameStr{$height}:$colnameEnd{$height})";
                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $height, $sumTr);
                                                }
                                                else {
                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $height, $amount);
                                                }
                                            }
                                        }
                                        $objPHPExcel->getActiveSheet()->getRowDimension($height)->setOutlineLevel(1);
                                        $startTotMth = false ;
                                        if($idxTranche == $maxIdxTranche) {
                                            $endTotalMth = $height ;
                                        }
                                        $height++ ;
                                        $idxTranche++ ;
                                    }
                                    $colTotMth = range($colstartgen, $colendgen) ;
                                    foreach($colTotMth as $col) {
                                        $colname = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(intval($col)) ;
                                        $sum = "=SUM($colname{$startTotalMth}:$colname{$endTotalMth})" ;
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $mainTitleHeight, $sum);
                                    }
                                }
                                else {
                                    $colTotMth = range($colstartgen, $colendgen) ;
                                    //if theres idc, iterate through idc
                                    foreach ($IDCType as $idctype => $Tr) {
                                        if($idctype == 'IDC' ) {
                                            $bullets = array_slice($letter, 0, sizeof($Tr)) ; 
                                            $headeridc = "$Code - IDC" ;
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $height, $headeridc) ;
                                            $submaincoordinateidc = $this->drawRecapSubValue($Tr, $colTotMth,$years, $height, $length, $bullets, $objPHPExcel) ;
                                            // exit;
                                        }
                                        else {
                                            $bullets = array_slice($letter, 0, sizeof($Tr)) ; 
                                            $headeridc = "$Code - Efektif" ;
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $height, $headeridc) ;
                                            $submaincoordinateefektif = $this->drawRecapSubValue($Tr, $colTotMth, $years, $height, $length, $bullets, $objPHPExcel);
                                        }
                                     }
                                     $colsummary = range($colstartgen, $colendgen) ;
                                     foreach($colsummary as $col) {
                                        $colname = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(intval($col)) ;
                                        $sum = "=$colname{$submaincoordinateidc}+$colname{$submaincoordinateefektif}" ;
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $mainTitleHeight, $sum);
                                     }
                                }
                            }
                        }
                        // var_dump($height);
                }
            // }
            //export recap report
            $colnameendgen = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colendgen);
            // var_dump("E1:{$colnameendgen}2"); exit;
            $objPHPExcel->getActiveSheet()->getStyle("B3:$colnameendgen{$height}")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("E1:{$colnameendgen}2")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("E1:{$colnameendgen}2")->applyFromArray($styleHeader);
            $objPHPExcel->getActiveSheet()->getStyle("E3:{$colnameendgen}{$height}")->applyFromArray($style_ContentNumeric);
            $objPHPExcel->getActiveSheet()->setTitle('Recap Report KI');
            $objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(70);
            $objPHPExcel->getActiveSheet()->setAutoFilter("B3:D{$height}");
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
        // return 0 ;
    }

    public function drawRecapSubValue($TrArr, $colTotMth, $years, &$height, $length, $bullets, $objPHPExcel) {
        $idxBul = 0 ;
        $startTotMth = true;
        $idxTranche = 1 ;
        $maxIdxTranche = sizeof($TrArr) ;
        $mainsubtitleheight = $height ;
        $height++;
        $startTotalMth = 0 ;
        $endTotalMth = 0 ;
        // echo "<pre>";
        // var_dump($TrArr) ;
        // var_dump($mainsubtitleheight);
        foreach ($TrArr as $trancheName => $Year) {
            // var_dump($trancheName);
            // var_dump($Year);
            if($Year == null) {
                // var_dump("skipped - $trancheName");
                $maxIdxTranche--;
                continue;
            }
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $height, $bullets[$idxBul]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $height, $trancheName);
            $idxBul ++ ;
            $startcol = 0 ;
            $endcol = 0 ;
            $startYear = true ;
            $yearIsEmpty = false ;
            foreach($years as $Y) {
                $startTotalTr = 0 ;
                $endTotalTr = 0 ;
                if(!array_key_exists($Y, $Year)) {
                    $yearIsEmpty = true ;
                }
                else {
                    $dataYear = $Year[$Y] ;
                    $yearIsEmpty = false;
                }
                if($startYear) {
                    $startcol = $length ;
                    $startYear = false ;
                }
                else {
                    $startcol = $endcol + 1 ;
                }
                $endcol = $startcol + 12 ;
                $cols = range($startcol, $endcol) ;
                foreach ($cols as $idx => $col) {
                    if($startTotMth) {
                        $startTotalMth = $height ;
                    }
                    if($idx == 0) {
                        $startTotalTr = $col ;
                    }
                    if($idx == (sizeof($cols) - 2)) {
                        $endTotalTr = $col ;
                    }
                    $month = intval($idx) + 1 ;
                    if($yearIsEmpty) {
                        $amount = '-' ;
                    }
                    else {
                        if(!array_key_exists(strval($month), $dataYear)) {
                            $amount = '-';
                        }
                        else {
                            if($dataYear[strval($month)] == null) {
                                $amount = '-' ;
                            }
                            else {
                                $amount = intval($dataYear[strval($month)]) ;
                            }
                        }
                    }
                    if($idx == 12) {
                        $colnameStr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startTotalTr) ;
                        $colnameEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endTotalTr) ;
                        $sumTr = "=SUM($colnameStr{$height}:$colnameEnd{$height})";
                        // var_dump($sumTr);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $height, $sumTr);
                    }
                    else {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $height, $amount);
                    }
                }
            }
            $objPHPExcel->getActiveSheet()->getRowDimension($height)->setOutlineLevel(1);
            $startTotMth = false ;
            $height++ ;
            $idxTranche++ ;
        }
        $endTotalMth = $height - 1 ;
        foreach($colTotMth as $col) {
            $colname = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(intval($col)) ;
            $sum = "=SUM($colname{$startTotalMth}:$colname{$endTotalMth})" ;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $mainsubtitleheight, $sum);
        }

        return $mainsubtitleheight ;
    }
    
    public function createRecapArr($recap) {
        // echo "<pre>" ;
        $return = [];
        foreach ($recap as $item) {
            // var_dump($item->IDC_STAT) ;
            $DDOWN =  $item->WITHDRAWAL ? $item->WITHDRAWAL : 0 ;
            $INT = $item->INSTALLMENT ? $item->INSTALLMENT : 0;
            $IDC_INSTALLMENT = $item->IDC_INSTALLMENT ? $item->IDC_INSTALLMENT : 0 ;
            $IDC_DRAWDOWN = $item->IDC_DRAWDOWN ? $item->IDC_DRAWDOWN : 0 ;
            $IDC_INTEREST = $item->IDC_INTEREST ? $item->IDC_INTEREST : 0 ;
            if($item->IDC_STAT == 'WITHOUT_IDC' || $item->IDC_STAT == null) {
                $return[$item->COMPANYCODE]['DRAWDOWN'][$item->CURRENCY]['POKOK'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $DDOWN ;
                $return[$item->COMPANYCODE]['INSTALLMENT'][$item->CURRENCY]['POKOK'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $INT;
                $return[$item->COMPANYCODE]['OUTSTANDING'][$item->CURRENCY]['POKOK'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = intval($DDOWN) - intval($INT);
                $return[$item->COMPANYCODE]['INTEREST'][$item->CURRENCY]['POKOK'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $item->INTEREST;
                $return[$item->COMPANYCODE]['DRAWDOWN'][$item->CURRENCY]['IDC'][$item->TRANCHE_NUMBER] = null ;
                $return[$item->COMPANYCODE]['INSTALLMENT'][$item->CURRENCY]['IDC'][$item->TRANCHE_NUMBER] = null;
                $return[$item->COMPANYCODE]['OUTSTANDING'][$item->CURRENCY]['IDC'][$item->TRANCHE_NUMBER] = null;
                $return[$item->COMPANYCODE]['INTEREST'][$item->CURRENCY]['IDC'][$item->TRANCHE_NUMBER] = null;
            }
            else if ($item->IDC_STAT == 'WITH_IDC'){
                $return[$item->COMPANYCODE]['DRAWDOWN'][$item->CURRENCY]['POKOK'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $DDOWN ;
                $return[$item->COMPANYCODE]['INSTALLMENT'][$item->CURRENCY]['POKOK'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $INT;
                $return[$item->COMPANYCODE]['OUTSTANDING'][$item->CURRENCY]['POKOK'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = intval($DDOWN) - intval($INT);
                $return[$item->COMPANYCODE]['INTEREST'][$item->CURRENCY]['POKOK'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $item->INTEREST;
                $return[$item->COMPANYCODE]['DRAWDOWN'][$item->CURRENCY]['IDC'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $IDC_DRAWDOWN ;
                $return[$item->COMPANYCODE]['INSTALLMENT'][$item->CURRENCY]['IDC'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $IDC_INSTALLMENT;
                $return[$item->COMPANYCODE]['OUTSTANDING'][$item->CURRENCY]['IDC'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = intval($IDC_DRAWDOWN) - intval($IDC_INSTALLMENT);
                $return[$item->COMPANYCODE]['INTEREST'][$item->CURRENCY]['IDC'][$item->TRANCHE_NUMBER][$item->PERIOD_YEAR][$item->PERIOD_MONTH] = $item->IDC_INTEREST;
            }
        }
        // var_dump($return) ; 
        // exit;
        return $return ;
    }

    public function ShowSummaryReportWeekly($param) {
        // var_dump($param);
        $COND = [] ;
        $WHERE = '' ;
        if($param['COMPANY'] != '' && $param['COMPANY'] != null) {
            array_push($COND, "COMPANYCODE = '{$param['COMPANY']}'") ;
        }
        if($param['BANK'] != '' && $param['BANK'] != null) {
            array_push($COND, "FCNAME = '{$param['BANK']}'") ;
        }
        if($param['CREDIT_TYPE'] != '' && $param['CREDIT_TYPE'] != null) {
            array_push($COND,"SUB_CREDIT_TYPE = '{$param['CREDIT_TYPE']}'") ;
        }
        // var_dump($COND);
        for($i = 0 ; $i < sizeof($COND) ; $i++) {
            if($i == 0) {
                $WHERE .= "WHERE " ;
            }
            $WHERE .= $COND[$i] ;
            if($i >= 0 && $i != (sizeof($COND) - 1)) {
                $WHERE .= " AND " ;
            }
        }
        // var_dump($WHERE) ; exit;
        $summarygenq = "  SELECT UUID, KI_TYPE, IDC_TYPE,
                                COMPANYCODE AS COMPANY,
                                FCNAME AS BANK,
                                PK_NUMBER,
                                TRANCHE_NUMBER,
                                SUB_CREDIT_TYPE,
                                FIRSTPAY,
                                CREDIT_TYPE,
                                SUB_WA,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN RK_CURRENCY
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN RK_CURRENCY
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN RK_CURRENCY
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN WA_CURRENCY
                                ELSE KI_CURR
                                END
                                AS CURRENCY,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN RK_LIMIT
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN RK_LIMIT
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN RK_LIMIT
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN WA_LIMIT
                                ELSE KI_LIMIT
                                END
                                AS LIMIT,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN TO_CHAR(RK_PROVISI)
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN TO_CHAR(RK_PROVISI)
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN TO_CHAR(RK_PROVISI)
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN '0'
                                ELSE TO_CHAR(KI_PROVISI) || '%' || ' (' || KI_PROVISI_TYPE || ')' 
                                END
                                AS PROVISI,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN TO_CHAR(RK_INTEREST) || '%'
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN TO_CHAR(RK_INTEREST) || '%'
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN TO_CHAR(RK_INTEREST) || '%'
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN TO_CHAR(WA_INTEREST) || '%'
                                ELSE TO_CHAR(KI_INTEREST) || '%'
                                END
                                AS INTEREST ,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN RK_TENOR
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN RK_TENOR
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN RK_TENOR
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN WA_TENOR
                                ELSE KI_TENOR
                                END
                                AS PERIOD,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN RK_DUEDATE
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN RK_DUEDATE
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN RK_DUEDATE
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN WA_DUEDATE
                                ELSE TO_NUMBER (KI_DUEDATE)
                                END
                                AS DUEDATE,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN RK_PAST_DUE
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN RK_PAST_DUE
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN RK_PAST_DUE
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN WA_PAST_DUE
                                ELSE KI_PAST_DUE
                                END
                                AS PAST_DUE,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN RK_WD - PAYMENT
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN RK_WD - PAYMENT
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN RK_WD - PAYMENT
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN WA_WD - PAYMENT
                                ELSE KI_WD - PAYMENT
                                END
                                AS OUTSTANDING,
                                CASE
                                WHEN SUB_CREDIT_TYPE = 'RK' THEN SUB_CREDIT_TYPE
                                WHEN SUB_CREDIT_TYPE = 'TL' THEN SUB_CREDIT_TYPE
                                WHEN SUB_CREDIT_TYPE = 'BD' THEN SUB_CREDIT_TYPE
                                WHEN SUB_CREDIT_TYPE = 'WA' THEN 
                                (
                                    CASE WHEN 
                                        SUB_WA IS NOT NULL THEN SUB_WA
                                        ELSE SUB_CREDIT_TYPE
                                    END
                                )
                                ELSE PURPOSE
                                END
                                AS PURPOSE
                        FROM (SELECT C.COMPANYCODE,
                                        B.FCNAME,
                                        FM.UUID,
                                        FM.PK_NUMBER,
                                        FM.CREDIT_TYPE,
                                        FM.SUB_CREDIT_TYPE,
                                        FM.KI_TYPE,
                                        FDWA.SUB_CREDIT_TYPE AS SUB_WA,
                                        FDKI.ADD_REMARK,
                                        CASE
                                            WHEN CREDIT_TYPE = 'KI' THEN COALESCE(FDKIT.IDC, 'WITHOUT_IDC') 
                                            ELSE FDKIT.IDC
                                        END
                                        AS IDC_TYPE,
                                        FDKIT.TRANCHE_NUMBER,
                                        FDKIT.PURPOSE,
                                        FDKIT.CURRENCY AS KI_CURR,
                                        FDKIT.LIMIT_TRANCHE AS KI_LIMIT,
                                        FDKI.PROVISI AS KI_PROVISI,
                                        FDKI.PROVISI_TYPE AS KI_PROVISI_TYPE,
                                        FDKI.MATURITY_DATE AS KI_PAST_DUE,
                                        FDKI.INTEREST AS KI_INTEREST,
                                        FDKI.TENOR AS KI_TENOR,
                                        FDKI.INTEREST_PAYMENT_SCHEDULE_DATE AS KI_DUEDATE,
                                        FDRK.MATURITY_DATE AS RK_PAST_DUE,
                                        FDRK.AMOUNT_LIMIT AS RK_LIMIT,
                                        FDRK.CURRENCY AS RK_CURRENCY,
                                        FDRK.PROVISI AS RK_PROVISI,
                                        FDRK.INTEREST AS RK_INTEREST,
                                        FDRK.TENOR AS RK_TENOR,
                                        FDRK.INTEREST_PAYMENT_SCHEDULE_DATE AS RK_DUEDATE,
                                        FDWA.AMOUNT_LIMIT AS WA_LIMIT,
                                        FDWA.MATURITY_DATE AS WA_PAST_DUE,
                                        FDWA.CURRENCY AS WA_CURRENCY,
                                        FDWA.INTEREST AS WA_INTEREST,
                                        FDWA.TENOR AS WA_TENOR,
                                        FDWA.INTEREST_PAYMENT_SCHEDULE_DATE AS WA_DUEDATE,
                                        FIRST_PAY.FIRST AS FIRSTPAY,
                                        CASE
                                        WHEN WDKI.WITHDRAWAL IS NULL THEN 0
                                        ELSE WDKI.WITHDRAWAL
                                        END
                                        AS KI_WD,
                                        CASE
                                        WHEN WDRK.WITHDRAWAL IS NULL THEN 0
                                        ELSE WDRK.WITHDRAWAL
                                        END
                                        AS RK_WD,
                                        CASE
                                        WHEN WDWA.WITHDRAWAL IS NULL THEN 0
                                        ELSE WDWA.WITHDRAWAL
                                        END
                                        AS WA_WD,
                                        CASE WHEN PAY.PAYMENT IS NULL THEN 0 ELSE PAY.PAYMENT END
                                        AS PAYMENT
                                FROM FUNDS_MASTER FM
                                        LEFT JOIN FUNDS_DETAIL_WA FDWA
                                        ON FDWA.UUID = FM.UUID AND FDWA.ISACTIVE = 1
                                        LEFT JOIN FUNDS_DETAIL_RK FDRK
                                        ON FDRK.UUID = FM.UUID AND FDRK.ISACTIVE = 1
                                        LEFT JOIN FUNDS_DETAIL_KI FDKI
                                        ON FDKI.UUID = FM.UUID AND FDKI.ISACTIVE = 1
                                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FDKIT
                                        ON FDKIT.UUID = FM.UUID AND FDKIT.ISACTIVE = 1
                                        LEFT JOIN
                                        (  SELECT SUM (B.DDOWN_AMT) AS WITHDRAWAL,
                                                B.TRANCHE_NUMBER,
                                                B.UUID
                                            FROM FUNDS_WD_KI_TRANCHE B
                                                LEFT JOIN FUNDS_WD_KI A ON A.CTRWD = B.BATCHID
                                            WHERE     A.VALUE_DATE < TO_DATE ('".$param['PERIOD']."', 'yyyy-mm-dd')
                                                AND A.STATUS = 1
                                        GROUP BY B.UUID, B.TRANCHE_NUMBER) WDKI
                                        ON     FM.UUID = WDKI.UUID
                                            AND FDKIT.TRANCHE_NUMBER = WDKI.TRANCHE_NUMBER
                                        LEFT JOIN
                                        (  SELECT SUM (AMOUNT) AS WITHDRAWAL, UUID, WD_TYPE
                                            FROM FUNDS_WITHDRAW
                                            WHERE     VALUE_DATE < TO_DATE ('".$param['PERIOD']."', 'yyyy-mm-dd')
                                                AND STATUS = 1
                                        GROUP BY UUID, WD_TYPE) WDRK
                                        ON     FM.UUID = WDRK.UUID
                                            AND WDRK.WD_TYPE = FDRK.SUB_CREDIT_TYPE
                                        LEFT JOIN
                                        (  SELECT SUM (AMOUNT) AS WITHDRAWAL, UUID, WD_TYPE
                                            FROM (SELECT UUID,
                                                        VALUE_DATE,
                                                        STATUS,
                                                        CASE
                                                            WHEN SUB_WD_TYPE IS NULL THEN WD_TYPE
                                                            ELSE SUB_WD_TYPE
                                                        END
                                                            AS WD_TYPE,
                                                        AMOUNT
                                                    FROM FUNDS_WITHDRAW
                                                    WHERE WD_TYPE = 'WA')
                                            WHERE     VALUE_DATE < TO_DATE ('".$param['PERIOD']."', 'yyyy-mm-dd')
                                                AND STATUS = 1
                                        GROUP BY UUID, WD_TYPE) WDWA
                                        ON     FM.UUID = WDWA.UUID
                                            AND WDWA.WD_TYPE = FDWA.SUB_CREDIT_TYPE
                                        LEFT JOIN
                                        (  SELECT SUM (INSTALLMENT) AS PAYMENT, CONTRACT_NUMBER
                                            FROM FUNDSPAYMENT
                                            WHERE     IS_PAID = 1
                                                AND END_PERIOD < TO_DATE ('".$param['PERIOD']."', 'yyyy-mm-dd')
                                        GROUP BY CONTRACT_NUMBER) PAY
                                        ON PAY.CONTRACT_NUMBER = FDKIT.CONTRACT_NUMBER
                                        LEFT JOIN (  SELECT MIN (START_PERIOD) AS FIRST, CONTRACT_NUMBER
                                                    FROM FUNDSPAYMENT
                                                GROUP BY CONTRACT_NUMBER) FIRST_PAY
                                        ON FIRST_PAY.CONTRACT_NUMBER = FDKIT.CONTRACT_NUMBER
                                        LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                                        LEFT JOIN SUPPLIER B ON B.ID = FM.BANK
                                        WHERE FM.ISACTIVE = '1')".$WHERE."
                                        ORDER BY COMPANYCODE,
                                                FCNAME,
                                                PK_NUMBER,
                                                TRANCHE_NUMBER" ;
        $testdummy = "SELECT COMPANY, UUID, IDC_TYPE, KI_TYPE,
                                BANK, 
                                CURRENCY, 
                                PURPOSE, 
                                CREDIT_TYPE,
                                SUB_CREDIT_TYPE,
                                SUB_WA,
                                SUM(LIMIT) AS LIMIT, SUM(OUTSTANDING) AS OUTSTANDING, 
                                PROVISI,
                                INTEREST, 
                                MIN(PERIOD) AS MIN_PERIOD, MAX(PERIOD) AS MAX_PERIOD, MIN(FIRSTPAY) AS FIRSTPAY, MAX(PAST_DUE) AS PAST_DUE, MIN(DUEDATE) AS MIN_DUEDATE, MAX(DUEDATE) AS MAX_DUEDATE,
                                CASE
                                    WHEN MAX(PERIOD) = MIN(PERIOD) THEN TO_CHAR(MIN(PERIOD))
                                    ELSE TO_CHAR(MIN(PERIOD)) || ' - ' || TO_CHAR(MAX(PERIOD)) 
                                    END
                                AS PERIOD,
                                CASE
                                    WHEN MAX(DUEDATE) = MIN(DUEDATE) THEN TO_CHAR(MIN(DUEDATE))
                                    ELSE TO_CHAR(MIN(DUEDATE)) || ' - ' || TO_CHAR(MAX(DUEDATE)) 
                                    END
                                AS DUEDATE
                        FROM (".$summarygenq.") GROUP BY COMPANY, BANK, CURRENCY, PURPOSE, SUB_CREDIT_TYPE, SUB_WA, UUID, IDC_TYPE, KI_TYPE, INTEREST, PROVISI, CREDIT_TYPE" ;

        $nestedtdum = "SELECT TBL_A.* , FDKI.ADD_REMARK, B.FCNAME, BKI.PERCENTAGE
                        FROM ($testdummy) TBL_A 
                        LEFT JOIN FUNDS_DETAIL_KI FDKI ON FDKI.UUID = TBL_A.UUID AND FDKI.ISACTIVE = 1
                        LEFT JOIN (SELECT UUID, MAX(COUNTER) as maxcount from BANK_KI GROUP BY UUID) LT_COUNTER_BKI ON LT_COUNTER_BKI.UUID = TBL_A.UUID 
                        LEFT JOIN BANK_KI BKI ON TBL_A.UUID = BKI.UUID AND BKI.COUNTER = LT_COUNTER_BKI.maxcount
                        LEFT JOIN SUPPLIER B ON BKI.BANKNAME = B.ID
                        " ;
        $nested2tddum = "SELECT COMPANY, IDC_TYPE, KI_TYPE, BANK, FCNAME AS BANK_SYD, CURRENCY, PURPOSE, SUB_CREDIT_TYPE, CREDIT_TYPE, SUB_WA, PERCENTAGE AS BANK_PERC, SUM(OUTSTANDING) AS OUTSTANDING, SUM(LIMIT) AS LIMIT,
                        CAST(SUM(OUTSTANDING) * CAST(PERCENTAGE AS FLOAT) AS INTEGER) AS OUTSTANDING_SYD,
                        CAST(SUM(LIMIT) * CAST(PERCENTAGE AS FLOAT) AS INTEGER) AS LIMIT_SYD, 
                        REGEXP_REPLACE(LISTAGG(PROVISI, ',') WITHIN GROUP (ORDER BY COMPANY, BANK, CURRENCY, PURPOSE), '([^,]+)(,\\1)*(,|$)', '\\1\\3') PROVISI, 
                        REGEXP_REPLACE(LISTAGG(
                            CASE
                                WHEN INTEREST = '%' THEN '0'
                                ELSE INTEREST
                                END, ',') WITHIN GROUP (ORDER BY COMPANY, BANK, CURRENCY, PURPOSE), '([^,]+)(,\\1)*(,|$)', '\\1\\3') INTEREST, 
                        CASE
                            WHEN MAX(PERIOD) = MIN(PERIOD) THEN TO_CHAR(MIN(PERIOD))
                            ELSE TO_CHAR(MIN(PERIOD)) || ' - ' || TO_CHAR(MAX(PERIOD)) 
                            END
                        AS PERIOD,
                        CASE
                            WHEN MAX(DUEDATE) = MIN(DUEDATE) THEN TO_CHAR(MIN(DUEDATE))
                            ELSE TO_CHAR(MIN(DUEDATE)) || ' - ' || TO_CHAR(MAX(DUEDATE)) 
                            END
                        AS DUEDATE,
                        LISTAGG(ADD_REMARK, ',') WITHIN GROUP (ORDER BY COMPANY, BANK, CURRENCY, PURPOSE) ADD_REMARK, MIN(FIRSTPAY) AS FIRSTPAY, MIN(PAST_DUE) AS PAST_DUE
                        FROM ($nestedtdum) GROUP BY COMPANY, IDC_TYPE, KI_TYPE, BANK, CURRENCY, PURPOSE, SUB_CREDIT_TYPE, CREDIT_TYPE, SUB_WA, INTEREST, PERCENTAGE, FCNAME
                        ORDER BY COMPANY ASC, BANK ASC, FCNAME ASC" ;
        $summarygen = $this->db->query($nested2tddum)->result();
        $this->db->close();
        return $summarygen;
    }

    public function ExportReportSummaryWeekly ($param) {
        // ini_set('display_errors', 'On');
        $data = $this->ShowSummaryReportWeekly($param) ;
        $rate = $param['CURSRATE'];
        try {
            if(count($data) == 0){
                throw new Exception("Data Kosong");
            }

            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("BANK RELATION")
                    ->setLastModifiedBy("BANK RELATION")
                    ->setTitle("Summary Weekly")
                    ->setSubject("Summary Weekly");

            $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '0000000'],
                        ],
                    ],
            ];
            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $style_Content = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
            );
            $style_ContentNumeric = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              ),
              'numberFormat' => array(
                    'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING
                )
            );
            $style_TotalBank = array(
                'font' => array(
                    'bold' => true,
                ),
            );

            $objPHPExcel->setActiveSheetIndex(0)->setTitle('Report');

            //header 
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'COMPANY')
                ->setCellValue('B1', 'BANK')
                ->setCellValue('C1', 'FACILITY NO.')
                ->setCellValue('D1', 'TYPE')
                ->setCellValue('E1', 'CCY')
                ->setCellValue('F1', 'LIMIT')
                ->setCellValue('G1', 'PROVISION %')
                ->setCellValue('H1', 'INTEREST RATE %')
                ->setCellValue('I1', 'TENOR')
                ->setCellValue('J1', 'DUE DATE')
                ->setCellValue('K1', 'FIRST PAYMENT OF INSTALLMENT')
                ->setCellValue('L1', 'PAST DUE')
                ->setCellValue('M1', 'REMARKS')
                ->setCellValue('N1', 'OUTSTANDING '.$param['PERIOD']) ;

            $idx = 2 ;
            $x = 0 ;
            // var_dump($data); exit;
            $startMergePointCom = 0 ;
            $startMergePointBank = 0;
            $totalBankPerCurr = [];
            $totalComPerCurr = [];
            $grandTotalPerCurr = [];

            $firstDataCom = true ;
            $firstDataBank = true ;
            $comNotChg = false ;
            $detailprint = false ;
            $bankChg = false ;

            //initialization
            $prevCom = '' ;
            $prevBank = '' ;
            // echo "<pre>" ;
            do{ 
                $bankname = '';
                if($x == sizeof($data)) {
                    $curCom = '';
                    $curBank = '';
                    $firstDataCom = false ;
                }
                else {
                    if($data[$x]->KI_TYPE == 'SYNDICATION') {
                        $bankname = 'SIND - ' . $data[$x]->BANK_SYD ;
                    }
                    else {
                        $bankname = $data[$x]->BANK ;
                    }
                    $curCom = $data[$x]->COMPANY ;
                    $curBank = $bankname ;
                }
                
                if($prevCom != $curCom ) {
                    if($firstDataCom) {
                       
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$idx,$curCom);
                        $startMergePointCom = $idx ;
                        $currency = $data[$x]->CURRENCY ? $data[$x]->CURRENCY : 'NA' ;
                       $totalComPerCurr[$currency]['LIMIT'] = intval($data[$x]->LIMIT ? $data[$x]->LIMIT : 0 );
                       $totalComPerCurr[$currency]['OTS'] = intval($data[$x]->OUTSTANDING ? $data[$x]->OUTSTANDING : 0);
                       $firstDataCom = false ;
                       $prevCom = $curCom ;
                       $comNotChg = false ;
                    } else {
                        $counterCurrBank = 0;
                        $counterCurrCom = 0 ;
                        foreach ($totalBankPerCurr as $item) {
                            $counterCurrBank ++;
                            $curr = array_keys($totalBankPerCurr, $item);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $idx, $curr[0]);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $idx, $item['LIMIT']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $idx, $item['OTS']);
                            $idx++;
                        }
                        $idx-- ;
                        $totalBank = $idx - $counterCurrBank + 1;
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B{$startMergePointBank}:B{$idx}");
                        $objPHPExcel->getActiveSheet()->getStyle("C$totalBank:N$idx")->applyFromArray($style_TotalBank);
                        $idx++;
                        unset($totalBankPerCurr);
                        foreach ($totalComPerCurr as $item) {
                            $counterCurrCom++ ;
                            $curr = array_keys($totalComPerCurr, $item);
                            if(!(array_key_exists($curr[0], $grandTotalPerCurr))) {
                                $grandTotalPerCurr[$curr[0]] = [] ;
                            }
                            array_push($grandTotalPerCurr[$curr[0]], $idx) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $idx, $curr[0]);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $idx, $item['LIMIT']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $idx, $item['OTS']);
                            $idx++;
                        }
                        $idx-- ;
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A{$startMergePointCom}:A{$idx}");
                        //recolor
                        $startTotal = $idx - $counterCurrCom + 1;
                        $objPHPExcel->getActiveSheet()->getStyle("A{$startMergePointCom}:A$idx")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                        $objPHPExcel->getActiveSheet()->getStyle("B{$startTotal}:N{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                        $idx++ ;
                        unset($totalComPerCurr);
                        $firstDataCom = true;
                        $firstDataBank = true;
                        $prevBank = '' ;
                        $prevCom = '' ;
                        $comNotChg = true ;
                        $detailprint = false ;
                        if($x == sizeof($data)) {
                            $x++;
                        }
                    }
                }
                else{
                    $currency = $data[$x]->CURRENCY ? $data[$x]->CURRENCY : 'NA' ;
                    $exist = false ;
                    foreach(array_keys($totalComPerCurr) as $curr) {
                        if($currency == $curr) {
                            $exist = true ;
                        }
                    }
                    if(!$bankChg) {
                        if(!$exist) {
                            $totalComPerCurr[$currency]['LIMIT'] = intval($data[$x]->LIMIT ? $data[$x]->LIMIT : 0 );
                            $totalComPerCurr[$currency]['OTS'] = intval($data[$x]->OUTSTANDING ? $data[$x]->OUTSTANDING : 0);
                        } else {
                            $totalComPerCurr[$currency]['LIMIT'] += intval($data[$x]->LIMIT ? $data[$x]->LIMIT : 0 );
                            $totalComPerCurr[$currency]['OTS'] += intval($data[$x]->OUTSTANDING ? $data[$x]->OUTSTANDING : 0);
                        }
                    }
                }

                if(!$comNotChg) {
                    if($prevBank != $curBank ) {
                        if($firstDataBank) {
                            $currency = $data[$x]->CURRENCY ? $data[$x]->CURRENCY : 'NA' ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$idx,$curBank);
                            $startMergePointBank = $idx ;
                            $totalBankPerCurr[$currency]['LIMIT'] = intval($data[$x]->LIMIT ? $data[$x]->LIMIT : 0 );
                            $totalBankPerCurr[$currency]['OTS'] = intval($data[$x]->OUTSTANDING ? $data[$x]->OUTSTANDING : 0);
                            $firstDataBank = false ;
                            $prevBank = $curBank ;
                            $detailprint = true ;
                            $bankChg = false ;
                        }
                        else {
                            $counterCurrBank = 0 ;
                            foreach ($totalBankPerCurr as $item) {
                                $counterCurrBank ++ ;
                                $curr = array_keys($totalBankPerCurr, $item);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $idx, $curr[0]);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $idx, $item['LIMIT']);
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $idx, $item['OTS']);
                                $idx++;
                            }
                            unset($totalBankPerCurr);
                            $idx-- ;
                            $totalBank = $idx - $counterCurrBank + 1;
                            $objPHPExcel->getActiveSheet()->getStyle("C$totalBank:N$idx")->applyFromArray($style_TotalBank);
                            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B{$startMergePointBank}:B{$idx}");
                            $idx++ ;
                            $firstDataBank = true ;
                            $detailprint = false ;
                            $bankChg = true ;
                        }
                    }
                    else {
                        $currency = $data[$x]->CURRENCY ? $data[$x]->CURRENCY : 'NA' ;
                        $exist = false ;
                        foreach(array_keys($totalBankPerCurr) as $curr) {
                            if($currency == $curr) {
                                $exist = true ;
                            }
                        }
                        if(!$exist) {
                            $totalBankPerCurr[$currency]['LIMIT'] = intval($data[$x]->LIMIT ? $data[$x]->LIMIT : 0 );
                            $totalBankPerCurr[$currency]['OTS'] =  intval($data[$x]->OUTSTANDING ? $data[$x]->OUTSTANDING : 0);
                        } else {
                            $totalBankPerCurr[$currency]['LIMIT'] += intval($data[$x]->LIMIT ? $data[$x]->LIMIT : 0 );
                            $totalBankPerCurr[$currency]['OTS'] +=  intval($data[$x]->OUTSTANDING ? $data[$x]->OUTSTANDING : 0);
                        }
                        $detailprint = true ;
                    }
                }

                if($detailprint) {
                    $currency = $data[$x]->CURRENCY ? $data[$x]->CURRENCY : 'NA' ;
                    $outstanding = intval($data[$x]->OUTSTANDING ? $data[$x]->OUTSTANDING : 0) ;
                    $creditType = $data[$x]->SUB_WA ? $data[$x]->SUB_WA : $data[$x]->SUB_CREDIT_TYPE ;

                    //Tidy up commas
                    $interest = $this->convertCommaDelim($data[$x]->INTEREST) ;
                    $provisi = $this->convertCommaDelim($data[$x]->PROVISI) ;
                    $creditType = '' ;
                    if($data[$x]->IDC_TYPE == 'WITH_IDC') {
                        $creditType = $data[$x]->SUB_CREDIT_TYPE . ' - IDC'; 
                    }
                    else {
                        $creditType = $data[$x]->SUB_CREDIT_TYPE ;
                    }
                    if($data[$x]->KI_TYPE == 'SYNDICATION') {
                        $limit = intval($data[$x]->LIMIT_SYD ? $data[$x]->LIMIT_SYD : 0 ) ;
                    }
                    else {
                        $limit = intval($data[$x]->LIMIT ? $data[$x]->LIMIT : 0 ) ;
                    }
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $idx, $data[$x]->PURPOSE ) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $idx, $creditType) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $idx, $currency) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $idx, $limit) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $idx, $provisi) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $idx, $interest) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $idx, $data[$x]->PERIOD) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $idx, $data[$x]->DUEDATE) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $idx, $data[$x]->FIRSTPAY) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $idx, $data[$x]->PAST_DUE) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $idx, $data[$x]->ADD_REMARK) ;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $idx, $outstanding) ;
                        $idx++;
                        $x++;
                }

            } while ($x <= sizeof($data)) ;
            //create grand total
            $grandTotalCoord = [] ;
            $startMergePointGT = $idx ;
            $idxGT = 1 ;
            $lastidxGT = sizeof($grandTotalPerCurr) ;

            foreach($grandTotalPerCurr as $curr => $Coord) {
                $sumClauseLimit = '=SUM(' ;
                $sumClauseOTS = '=SUM(' ; 
                $index = 1 ;
                $lastindex = sizeof($Coord) ;
                    foreach($Coord as $loc) {
                        $sumClauseLimit .= 'F'.$loc.',' ;
                        $sumClauseOTS .= 'N'.$loc.',' ;
                        if($index == $lastindex) {
                            $sumClauseLimit .= ')' ;
                            $sumClauseOTS .= ')' ; 
                            var_dump($sumClauseLimit);
                            var_dump($sumClauseOTS);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $idx, $curr) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $idx, $sumClauseLimit) ;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $idx, $sumClauseOTS) ;
                            $grandTotalCoord[$curr] = $idx;
                        }
                        $index++;
                    }
                if($idxGT == $lastidxGT) {
                    $objPHPExcel->getActiveSheet()->mergeCells("A$startMergePointGT:D$idx") ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $startMergePointGT, "GRAND TOTAL") ;
                }
                $idx++ ;
                $idxGT ++ ;
            }
            $startMergePointEQGT = $idx ;
            unset($grandTotalCoord['NA']);
            $idxEqGT = 1 ;
            $lastEqGT = sizeof($grandTotalCoord) ;
            // var_dump($grandTotalCoord); exit;
            foreach ($grandTotalCoord as $key => $value) {
                $eqLIMIT = '=' ;
                $eqOTS = '=' ; 
                $idxCheck = 1 ;
                foreach($grandTotalCoord as $curr => $item) {
                    if($curr == $key) {
                        $eqLIMIT.='F'.$item;
                        $eqOTS.='N'.$item;
                    }
                    else if ($key == 'IDR') {
                        if($curr == 'US$') {
                            $eqLIMIT.='('.'F'.$item."*$rate".')';
                            $eqOTS.='('.'N'.$item."*$rate".')';
                        }
                        else {
                            $eqLIMIT.='F'.$item;
                            $eqOTS.='N'.$item;
                        }
                    }
                    else if($key == 'US$') {
                        if($curr == 'IDR') {
                            $eqLIMIT.='('.'F'.$item."/$rate".')';
                            $eqOTS.='('.'N'.$item."/$rate".')';
                        }
                        else {
                            $eqLIMIT.='F'.$item;
                            $eqOTS.='N'.$item;
                        }
                    }
                    else {
                        $eqLIMIT.='F'.$item;
                        $eqOTS.='N'.$item;
                    }
                    if($idxCheck != $lastEqGT ) {
                        $eqLIMIT.='+' ;
                        $eqOTS.='+' ;
                    }
                    $idxCheck++;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $idx, $key) ;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $idx, $eqLIMIT) ;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $idx, $eqOTS) ;
                if($idxEqGT == $lastEqGT) {
                    $objPHPExcel->getActiveSheet()->mergeCells("A$startMergePointEQGT:D$idx") ;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $startMergePointEQGT, "GRAND TOTAL EQUIVALENT") ;
                    $idx++ ;
                }
                else {
                    $idx++ ;
                    $idxEqGT++ ;
                }
            }
            // exit;
            $idx--;
            $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->applyFromArray($styleHeader) ;
            $objPHPExcel->getActiveSheet()->getStyle("A2:E$idx")->applyFromArray($style_Content) ;
            $objPHPExcel->getActiveSheet()->getStyle("F2:F$idx")->applyFromArray($style_ContentNumeric) ;
            $objPHPExcel->getActiveSheet()->getStyle("M2:N$idx")->applyFromArray($style_ContentNumeric) ;
            $objPHPExcel->getActiveSheet()->getStyle("G2:M$idx")->applyFromArray($style_Content) ;
            $objPHPExcel->getActiveSheet()->getStyle("A1:N$idx")->applyFromArray($styleArray) ;
            $objPHPExcel->getActiveSheet()->getRowDimension("1")->setRowHeight(50);
            $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E5FFCC');
            foreach(range('A', 'L') as $ColID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($ColID)->setAutoSize(true);
            }
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getStyle("M1:M$idx")
            ->getAlignment()->setWrapText(true); 

            $objPHPExcel->getActiveSheet()->setTitle('Report Weekly') ;

            $return = [
                'STATUS' =>TRUE,
                'Data' => $objPHPExcel
            ] ;
        } catch (Exception $ex) {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return ;
    }

    public function convertCommaDelim($text) {
        $item = explode(',', $text);
        for ($i = 0 ; $i < sizeof($item) ; $i++) {
            $item[$i] = trim($item[$i]) ;
            $arraychar = str_split($item[$i]) ;

            if($arraychar[0] == '.') 
            {
                $item[$i] = '0'.$item[$i] ;
            }
            if($item[$i] == '') {
                $item[$i] = '-' ;
            }
            if($item[$i] == '%') {
                $item[$i] = '-' ;
            }
        }
        $newitem = implode(', ', $item);
        return $newitem;
    }

    public function reupdateInstallmentIDC($param, $lastamountidc) {
        // ini_set('display_errors', 'On');
        try {
            $updated = [];
            $UUID = $param['UUID'] ;
            $TR_NUM = $param['TRANCHE_NUMBER'] ;
            $installmentidcq = "SELECT IDC.*
                FROM FUNDS_KI_INSTALLMENT_IDC IDC
                        LEFT JOIN (  SELECT MAX (COUNTER) AS max_counter, UUID, TRANCHE_NUMBER
                                    FROM FUNDS_KI_INSTALLMENT_IDC
                                GROUP BY UUID, TRANCHE_NUMBER) LT_C
                        ON     IDC.UUID = LT_C.UUID
                            AND IDC.TRANCHE_NUMBER = LT_C.TRANCHE_NUMBER
                WHERE     IDC.UUID = '$UUID'
                        AND IDC.TRANCHE_NUMBER = '$TR_NUM'
                        AND IDC.COUNTER = LT_C.max_counter
                        AND IS_ORIGINAl = '1'
                ORDER BY ID" ; 
            $installmentidc = $this->db->query($installmentidcq)->result();
            $lastPayIdx = false ;
            foreach($installmentidc as $item) {
                $lastamountidc -= intval($item->INSTALLMENT_AMOUNT) ;
                if($lastamountidc < 0) {
                    if($lastPayIdx == false) {
                        $newAmount = intval($item->INSTALLMENT_AMOUNT) + $lastamountidc ;
                        $lastPayIdx = true ; 
                    }
                    else {
                        $newAmount = 0 ;
                    }
                    $updated[$item->ID] = $newAmount ;
                }
            }
           return $updated;
        } catch (Exception $ex) {
            return false;
        }
    }
}   