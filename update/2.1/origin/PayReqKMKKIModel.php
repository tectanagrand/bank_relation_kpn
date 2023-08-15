<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PayReqKMKKIModel extends BaseModel {

    public $variable ;

    public function __construct() {
        parent::__construct();
    }

    public function ShowLatestInstallment ($param) {
        $q = "SELECT FP.*,
                        TO_CHAR (START_PERIOD, 'YYYY-MM-DD') AS START_PERIOD_C,
                        TO_CHAR (END_PERIOD, 'YYYY-MM-DD') AS END_PERIOD_C,
                        TO_CHAR (PAYMENT_DATE, 'MM/DD/YYYY') AS PAYMENT_DATE_C,
                        FPC.CURRENTACCOUNTINGYEAR,
                        FPC.CURRENTACCOUNTINGPERIOD,
                        FDKIT.CURRENCY,
                        LAT_PER.LAT_PM,
                        LAT_PER.LAT_PY
                FROM FUNDSPAYMENT FP
                        LEFT JOIN FUNDS_PERIODCONTROL FPC ON FPC.COMPANY = FP.COMPANY
                        LEFT JOIN FUNDS_MASTER FM ON FP.PK_NUMBER = FM.PK_NUMBER
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
                WHERE     FP.CONTRACT_NUMBER = ?
                        AND PERIOD_MONTH = FPC.CURRENTACCOUNTINGPERIOD
                        AND PERIOD_YEAR = FPC.CURRENTACCOUNTINGYEAR
                ORDER BY PERIOD ASC NULLS FIRST" ;
        $res = $this->db->query($q, array($param['CONTRACT_NUMBER']))->row();
        if($res == null) {
            $q = "SELECT PERIOD_MONTH||'-'||PERIOD_YEAR AS PERIOD_NXT
                    FROM FUNDSPAYMENT
                    WHERE CONTRACT_NUMBER = ? AND IS_PAYMENT IS NULL
                ORDER BY PERIOD ASC NULLS FIRST" ;
            $res = $this->db->query($q, $param['CONTRACT_NUMBER'])->row();
            $result = [
                'IS_EXIST' => FALSE,
                'DATA' => $res
            ] ;
        }
        else {
            $result = [
                'IS_EXIST' =>TRUE,
                'DATA' => $res
            ] ;
        }
        // var_dump($this->db->last_query()); exit;
        return $result ;
    }
    
    public function ShowPaymentDatabyDate ($param) {
        // Flow :
        // 1. Funds Outstanding from withdrawal amount (FundsWD) substracted by amount paid (FundsPayment)
        // 2. Set date, look after joined table fundspayment and fundskmkreport, when last payment occur set as min date
        // 3. select data from fundskmkreport, take its amount exclude one last rows and take its START_PERIOD
        // 4. count days from startperiod until payment date
        // 5. set interestratio = currentdays/rowdays * interestrow
        //$param input = {Date, PKNumber, Contract Number}
        // $result = [];
        $dataq = " SELECT CALC_INTEREST, TO_CHAR(FR.START_PERIOD, 'YYYY-MM-DD') AS START_PERIOD, TO_CHAR(FR.END_PERIOD, 'YYYY-MM-DD') AS END_PERIOD, FR.DAY, FR.PERIOD
        FROM FUNDS_KMK_REPORT FR
            LEFT JOIN (     SELECT CONTRACT_NUMBER, END_PERIOD
                            FROM FUNDSPAYMENT
                            WHERE IS_PAID = '1' AND CONTRACT_NUMBER = '".$param['CONTRACT_NUMBER']."'
                        ORDER BY END_PERIOD ASC
                        FETCH FIRST 1 ROWS ONLY) FP
                ON FP.CONTRACT_NUMBER = FR.CONTRACT_NUMBER
    WHERE     FR.CONTRACT_NUMBER = '".$param['CONTRACT_NUMBER']."'
            AND  (    FR.END_PERIOD >= (CASE
                            WHEN FP.END_PERIOD IS NULL THEN FR.START_PERIOD
                            ELSE FP.END_PERIOD
                            END) 
                    AND (FR.START_PERIOD <= TO_DATE ('".$param['PAYMENT_DATE']."', 'yyyy-mm-dd')))
    ORDER BY FR.END_PERIOD DESC" ;
        $data = $this->db->query($dataq)->result();
        // var_dump($data);exit;
        $lendata = sizeof($data) ;
        $sumofint = 0 ;
        for($i = 1 ; $i < $lendata ; $i++) {
            $sumofint += intval($data[$i]->CALC_INTEREST) ;
        }
        //count ratio
        // var_dump($sumofint); 
        $paydate = DateTime::createFromFormat('Y-m-d', $param['PAYMENT_DATE']) ;
        // var_dump($paydate);
        $startperiod = DateTime::createFromFormat('Y-m-d', $data[0]->START_PERIOD) ;
        // var_dump($startperiod);
        $daybefore = intval($data[0]->DAY) ;
        $intbefore = intval($data[0]->CALC_INTEREST);
        $diffnew = date_diff($paydate, $startperiod);
        $diff = intval($diffnew->d) ;
        // var_dump($diff, $daybefore, $intbefore); exit;
        $newinterest = intval(round($diff / $daybefore * $intbefore)) ;
        $sumofint += $newinterest;
        //interest
        if($sumofint <= 0) {
            $result['STATUS'] = FALSE ;
        } else {
            $result['STATUS'] = TRUE ;
        }
        // var_dump($newinterest) ; exit;
        $result['INTEREST'] = $sumofint;
        $result['PERIOD'] = $data[0]->PERIOD;
        $result['START_PERIOD'] = $data[0]->START_PERIOD;
        return $result;
    }

    public function SavePaymentReqKMK ($param, $Location) {
        // var_dump($param); 
        $CONTRACT_NUM   = $param['CONTRACT_NUMBER'] ;
        $PK_NUM         = $param['PK_NUMBER'] ;
        $COMPANY        = $param['COMPANY'] ;
        $CREDIT_TYPE    = 'KMK' ;
        $DOCDATE        = $param['DOCDATE'] ;
        $FCENTRY        = $param['USERNAME'] ;
        $PAYMENT_DATE   = $param['PAYMENT_DATE'] ;
        $INSTALLMENT    = intval(preg_replace("/[^\d\.\-]/","",$param['INSTALLMENT'])) ;
        $INTEREST       = intval(preg_replace("/[^\d\.\-]/","",$param['INTEREST'])) ;
        $PERIOD         = $param['PERIOD'] ;
        $START_PERIOD   = $param['START_PERIOD'];
        $CURRENCY       = $param['CURRENCY'] ;
        $AMOUNT_PAID    = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PAID'])) ;
        $UUID           = $this->uuid->v4();

        //cf
        $datetim = new DateTime($param['PAYMENT_DATE']);
        $MONTH = $datetim->format('n');
        $YEAR = $datetim->format('Y');
        $date = $datetim->format('Y-n-j');
        $datein = $datetim->format('n/j/Y');
        $pay_id = $PK_NUM.'$'.$CONTRACT_NUM.'$'.$date;

        //date conv
        $start_per = date('m/d/Y', strtotime($START_PERIOD));
        $end_per = date('m/d/Y', strtotime($PAYMENT_DATE));
        $docdt = date('m/d/Y', strtotime($DOCDATE));
        try {
            $this->db->trans_begin();
            //upload file 
            $config['upload_path'] = ROOT;
            $config['allowed_types'] = 'pdf|docx|doc|xls|xlsx';
            $config['overwrite'] = TRUE;
            $config['max_size'] = 1024;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $check = $this->upload->do_upload('userfile');
            $media = $this->upload->data();

            if(!$check) {
                throw new Exception($this->upload->display_errors());
            }


            $cekDupQ = "SELECT CONTRACT_NUMBER FROM FUNDSPAYMENT WHERE CONTRACT_NUMBER = '".$CONTRACT_NUM."' AND END_PERIOD = TO_DATE('".$PAYMENT_DATE."', 'YYYY-MM-DD')" ;
            $cekDup = $this->db->query($cekDupQ)->result();
            if($cekDup != null) {
                throw new Exception("Payment already occured on current date") ;
            }
            $dt = [
                'CONTRACT_NUMBER' => $CONTRACT_NUM,
                'PK_NUMBER' => $PK_NUM,
                'COMPANY' => $COMPANY,
                'CREDIT_TYPE' => $CREDIT_TYPE,
                'FCENTRY' => $FCENTRY,
                'FCIP' => $Location,
                'PERIOD_MONTH' => $MONTH,
                'PERIOD_YEAR' => $YEAR,
                'PERIOD' => intval($PERIOD),
                'INSTALLMENT' => $INSTALLMENT,
                'INTEREST' => $INTEREST,
                'GID'   => $UUID,
                'PAY_ID' => $pay_id,
                'FILENAME' => $media['file_name'],
                'IS_PAYMENT' => '1'
            ];
            // var_dump($dt); exit;
            $result = $this->db->set('LASTUPDATE', 'SYSDATE', false)
                    ->set('START_PERIOD', "TO_DATE('".$START_PERIOD."', 'yyyy-mm-dd')",false)
                    ->set('DOCDATE', "TO_DATE('".$DOCDATE."', 'yyyy-mm-dd')",false)
                    ->set('END_PERIOD', "TO_DATE('".$PAYMENT_DATE."', 'yyyy-mm-dd')",false )
                    ->set($dt)
                    ->insert('FUNDSPAYMENT');

            //cf
            $cekTrans = "SELECT COMPANY, DOCNUMBER, DOCDATE FROM CF_TRANSACTION WHERE COMPANY = '".$COMPANY."' AND DOCNUMBER = '".$pay_id."' AND TO_CHAR(DOCDATE,'MMYYYY') = '$date'";
            $cekTrans = $this->db->query($cekTrans);

            if($cekTrans->num_rows < 0 || $cekTrans->num_rows == null) {
                $q =    "SELECT FM.COMPANY,FM.BUNIT,FM.VENDOR,FM.PK_NUMBER,FM.CREDIT_TYPE FROM FUNDS_MASTER FM WHERE PK_NUMBER = '".$PK_NUM."'";
                $getDetMaster = $this->db->query($q)->row();
                $cf = [
                    "DEPARTMENT" => 'BANK-RELATION',
                    "COMPANY" => $getDetMaster->COMPANY,
                    "BUSINESSUNIT" => $getDetMaster->BUNIT,
                    "DOCNUMBER" => $pay_id,
                    "DOCTYPE" => $getDetMaster->CREDIT_TYPE,
                    "VENDOR" => $getDetMaster->VENDOR,
                    "CURRENCY" => $CURRENCY,
                    "EXTSYS" => 'SAPHANA',
                    "VAT" => "",
                    "REMARK" => "Payment Req",
                    "AMOUNT_INCLUDE_VAT" => intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PAID'])),
                    "TOTAL_BAYAR" => intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PAID'])),
                    "AMOUNT_PPH" => 0,
                    "RATE" => 1,
                    "FCEDIT" => $FCENTRY,
                    "FCIP" => $Location,
                    "ID" => $UUID,
                    "ISACTIVE" => "TRUE",
                    "FCENTRY" => $FCENTRY
                ];
                // var_dump($cf);exit;

                $result = $this->db->set('LASTUPDATE', 'SYSDATE', false) 
                        ->set('DOCDATE', "TO_DATE('".$this->oracle_date('date')."', 'dd/mm/yyyy')",false)
                        ->set('DUEDATE', "TO_DATE('".$this->oracle_date('date')."', 'dd/mm/yyyy')",false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')",false)
                        ->set($cf)
                        ->insert($this->CF_TRANSACTION);

                $cf_det = [
                    "ID" => $UUID,
                    "MATERIAL" => '100078',
                    "REMARKS" => '',
                    "AMOUNT_INCLUDE_VAT" => $cf['AMOUNT_INCLUDE_VAT'],
                    "AMOUNT_PPH" => 0,
                    "ISACTIVE" => "TRUE",
                    "FCENTRY" => $FCENTRY,
                    "FCEDIT" => $FCENTRY,
                    "FCIP" => $Location
                ];
                $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                ->set($cf_det)->insert($this->CF_TRANSACTION_DET);
            }   
            
            if($result && $result2) {
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "Payment Success"
                ] ;
                $this->db->trans_commit();
            }
            else {
                throw new Exception('Payment error !!!');
            }

        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ] ;
        }
        $this->db->close();
        return $return ;
    }

    //helper
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

    public function savePaymentReqKI($param, $Location) {
        // echo "<pre>";
        // var_dump($param);
        $AMOUNT_PAID = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PAID'])) ;
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $datetim = new DateTime($param['DATE_PAY']);
            $month = $datetim->format('n');
            $year = $datetim->format('Y');
            $date = $datetim->format('Y-n-j');
            $datein = $datetim->format('n/j/Y');
            $pay_id = $param['PK_NUMBER'].'$'.$param['CONTRACT_NUMBER'].'$'.$date;
            // var_dump($pay_id); exit;
                $SQL = "SELECT IS_PAYMENT, GID, PERIOD_MONTH, PERIOD_YEAR, UUID, PK_NUMBER, C_INST FROM FUNDSPAYMENT WHERE CONTRACT_NUMBER = '".$param['CONTRACT_NUMBER']."' AND ID = '".$param['ID']."'";
                $Cek = $this->db->query($SQL)->row();

                if ($Cek->IS_PAYMENT == "1") {
                    throw new Exception('Data Already Paid !!');
                }else{
                   
                // $time   = new DateTime($param['PERIOD_MONTH'].$param['PERIOD_YEAR']);
                // var_dump($time);exit;

                $cekTrans = "SELECT COMPANY, DOCNUMBER, DOCDATE FROM CF_TRANSACTION WHERE COMPANY = '".$param['COMPANY']."' AND DOCNUMBER = '".$pay_id."' AND TO_CHAR(DOCDATE,'MMYYYY') = '$date'";
                $cekTrans = $this->db->query($cekTrans);
                // var_dump($this->db->last_query());
                // var_dump($cekTrans); exit;
                if($cekTrans->num_rows < 0 || $cekTrans->num_rows == null){

                        $q = "SELECT FM.COMPANY,FM.BUNIT,FM.BANK,FM.PK_NUMBER,FM.CREDIT_TYPE FROM FUNDS_MASTER FM WHERE PK_NUMBER = '".$param['PK_NUMBER']."'";
                        $getDetMaster = $this->db->query($q)->row();
                        //insert cf transaction
                        $cf = [
                            "DEPARTMENT" => 'BANK-RELATION',
                            "COMPANY" => $getDetMaster->COMPANY,
                            "BUSINESSUNIT" => $getDetMaster->BUNIT,
                            "DOCNUMBER" => $pay_id,
                            "DOCTYPE" => $getDetMaster->CREDIT_TYPE,
                            "VENDOR" => $getDetMaster->BANK,
                            "CURRENCY" => $param['CURRENCY'],
                            "EXTSYS" => 'SAPHANA',
                            "VAT" => "",
                            "REMARK" => "Payment Req",
                            "AMOUNT_INCLUDE_VAT" => intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PAID'])),
                            "TOTAL_BAYAR" => intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PAID'])),
                            "AMOUNT_PPH" => 0,
                            "RATE" => 1,
                            "FCEDIT" => $param['USERNAME'],
                            "FCIP" => $Location
                        ];   

                        $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                        ->set('DOCDATE', "TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy')", false)
                        ->set('DUEDATE', "TO_DATE('".$this->oracle_date('date')."','dd/mm/yyyy')", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        
                        $Data["ID"] = $this->uuid->v4();
                        $cf["ID"]   = $Data["ID"];
                        $cf["ISACTIVE"] = "TRUE";
                        $cf["FCENTRY"] = $param['USERNAME'];
                        $result = $result->set($cf)->insert($this->CF_TRANSACTION);
                        // end cf
                        // start cf details
                        // echo "<pre>";
                        // var_dump($result);exit();
                        $cf_det = [
                            "ID" => $Data['ID'],
                            "MATERIAL" => '100078',
                            "REMARKS" => '',
                            "AMOUNT_INCLUDE_VAT" => $cf['AMOUNT_INCLUDE_VAT'],
                            "AMOUNT_PPH" => 0,
                            "ISACTIVE" => "TRUE",
                            "FCENTRY" => $param["USERNAME"],
                            "FCEDIT" => $param["USERNAME"],
                            "FCIP" => $Location
                        ];
                        $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                        ->set($cf_det)->insert($this->CF_TRANSACTION_DET);
                        // end

                        if($result && $result2){
                            $dt_pay = [
                                'PAY_ID' => $pay_id,
                                'IS_PAYMENT' => 1
                            ];
                            // $whereUpInst = [
                            //     'UUID' => $Cek->UUID,
                            //     'TRANCHE_NUMBER' => $Cek->TRANCHE_NUMBER,
                            //     'PERIOD_MONTH' => $Cek->PERIOD_MONTH,
                            //     'PERIOD_YEAR'   => $Cek->PERIOD_YEAR
                            // ]
                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                        ->set('PAYMENT_DATE', "TO_DATE('" . $datein . "','MM/DD/YYYY')", false)
                                        ->set($dt_pay)
                                        ->where('ID', $param['ID'])
                                        ->update('FUNDSPAYMENT');
                            // $result1 = $this->db->set('CREATED_AT', 'SYSDATE', false)
                            //             ->set('IS_PAYMENT', '1')
                            //             ->where($whereUpInst)
                            //             ->update('FUNDS_KI_INSTALLMENT');
                        }
                            // $time   = new DateTime($param['COMDATE']);
                            // $time   = $time->format('m').$time->format('Y');
                } 
            }
                // }
                        


            if ($result1) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback(); 
                throw new Exception('Data Save Failed !!');
            }
                    // }//end if tipe
                // }
            //end else
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

    public function easyUpdatePeriodControl ($param) {
        $nextPer = explode('-',$param['PERIOD']) ;
        try {
            $dt = [
                'CURRENTACCOUNTINGYEAR' => $nextPer[1],
                'CURRENTACCOUNTINGPERIOD' => $nextPer[0]
            ] ;
            $update = $this->db->set($dt)
            ->set('LASTUPDATE', 'SYSDATE', false)
            ->where(['COMPANY' => $param['COMPANY']])
            ->update('FUNDS_PERIODCONTROL') ;
            
            if(!$update) {
                throw new Exception ('Failed to Update');
            }
        } catch (Exception $ex) {
            $result = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
            return $result ;
        }

        if($update) {
            $result = [
                'STATUS' => TRUE,
                'MESSAGE' => 'Period Updated'
            ] ;
        }
        return $result ;
    } 
    // 1.4
    public function ShowPaymentDataHistRequest($param) {
        $q = "SELECT fp.contract_number,
                        fp.pk_number,
                        c.companycode,
                        fp.PAY_ID,
                        fp.CREDIT_TYPE,
                        fp.PERIOD_MONTH || '-' || fp.PERIOD_YEAR AS PERIOD,
                        cf.total_bayar AS AMOUNT,
                        cf.currency,
                        fp.filename
                FROM fundspayment fp
                        LEFT JOIN company c ON fp.company = c.id
                        LEFT JOIN cf_transaction cf ON cf.DOCNUMBER = fp.PAY_ID
                WHERE is_payment = '1'" ;
        if($param['COMPANY'] != '0') {
            $q .= "AND C.COMPANYCODE = '".$param['COMPANY']."'" ;
        }
        if($param['CREDIT_TYPE'] != '0') {
            $q .= "AND CREDIT_TYPE = '".$param['CREDIT_TYPE']."'" ;
        }
        if($param['PERIOD'] != null) {
            $period = explode('-', $param['PERIOD']);
            $q .= "AND PERIOD_MONTH = '".$period[0]."' AND PERIOD_YEAR = '".$period[1]."'" ;
        }
        $result = $this->db->query($q)->result() ;
        return $result ;
    }

    public function UploadPaymentBillKMKKI($param) {
        $DOCNUMBER = $param['DOCNUMBER'] ;
        // var_dump(ROOT);exit;
        try {
            $this->db->trans_begin();
            
            $config['upload_path'] = "/var/www/html/assets/file/";
            $config['allowed_types'] = 'pdf|docx|doc|xls|xlsx';
            $config['overwrite'] = TRUE;
            $config['max_size'] = 1024;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $uploadfile = $this->upload->do_upload('userfile') ;
            $media = $this->upload->data();

            if(!$uploadfile) {
                throw new Exception($this->upload->display_errors());
            }

            $filename = $media['file_name'];

            $updateattachment = $this->db->set(['FILENAME' => $filename])
                                ->set('DATE_FILENAME', 'SYSDATE', false)
                                ->where('PAY_ID', $DOCNUMBER)
                                ->update('FUNDSPAYMENT') ;
            // var_dump($this->db->last_query()); exit ;

            if(!$updateattachment) {
                throw new Exception('Error') ;
            }
            else {
                $this->db->trans_commit();
                $result = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "SUCCESS Upload File Attachment"
                ] ;
            }

        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $result = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ] ;
        }
        $this->db->close();
        return $result ;
    }
    // ^^^
}