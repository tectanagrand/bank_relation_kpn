<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CronController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
    // http://10.10.10.94:8080/index.php/CronController/genLeasingReport?DOCNUMBER=3226001085&COMPANY=C000000010
    public function genLeasingReport(){
        // var_dump($this->input->get());exit;

        $docnumber  = $this->input->get('DOCNUMBER');
        $company    = $this->input->get('COMPANY');
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $this->db->where('DOCNUMBER',$docnumber);
            $this->db->delete('LEASINGREPORT');

            $q2 = "SELECT L.*,TO_NUMBER (TO_CHAR(L.VALID_FROM,'MM')) DETMONTH, TO_NUMBER (TO_CHAR(L.VALID_FROM,'YYYY')) DETYEAR FROM LEASINGMASTER L WHERE L.COMPANY = '$company' AND L.DOCNUMBER = '$docnumber'";
            $getDetails = $this->db->query($q2)->row();
            // echo "<pre>";
            // var_dump($getDetails);exit;
            $CURR       = $getDetails->CURRENCY;
            
            
            if($getDetails->ISACTIVE == 'FALSE'){
                throw new Exception('Leasing Tidak Aktif.');
            }
            else{
                $startMonth = $getDetails->DETMONTH;
                $startYear  = $getDetails->DETYEAR;
                $totalMonth = $getDetails->TOTAL_MONTH;

                for($i=0;$i<$totalMonth;$i++) {

                    if($startMonth == 1 || $startMonth == '1'){
                        $parMonth = 12;    
                        $parYear  = $startYear - 1;
                    }else{
                        $parMonth = $startMonth - 1;
                        $parYear  = $startYear;
                    }

                    $SQL = "SELECT * FROM LEASINGREPORT WHERE DOCNUMBER = '$docnumber' AND PERIOD_YEAR = '$parYear' AND PERIOD_MONTH = '$parMonth'";
                    $Cek = $this->db->query($SQL)->row();

                    // var_dump($this->db->last_query());exit;
                    $INTEREST_PERCENTAGE = $getDetails->INTEREST_PERCENTAGE;
                    $INTEREST_AMOUNT     = $getDetails->INTEREST_AMOUNT;
                    $AMOUNT_BEFORE_CONV  = $getDetails->AMOUNT_BEFORE_CONV;

                    if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                        $AMOUNT_MONTHLY_LEASING  = round(($getDetails->BASIC_AMOUNT * (($INTEREST_PERCENTAGE / 100 ) / 12) / (1-pow(1/(1+(($INTEREST_PERCENTAGE / 100) / 12)),($getDetails->TOTAL_MONTH-1)))));
                        
                    }

                    //cek jika month to be nya 1
                    if($Cek == NULL){
                        $MONTHTOBE = 1;
                        $REMAIN_MONTH = $getDetails->TOTAL_MONTH - 1;
                        $LINENO = 1;
                        $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                            $INTEREST_AMOUNT_MONTHLY = $getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12;
                            $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY;
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                            $INTEREST_AMOUNT_MONTHLY = 0;
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY);
                            
                            $BASIC_AMOUNT_MONTHLY    = round($AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY);
                            $BASIC_AMOUNT_YEARLY     = round($AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY);
                            $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT);
                            $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY);
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                            $BASIC_AMOUNT_MONTHLY    = round($getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH);
                            $INTEREST_AMOUNT_MONTHLY = round($getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12);
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY);
                        }
                        // var_dump("1");exit;
                    }else{
                        $MONTHTOBE  = $Cek->MONTHTOBE + 1;
                        $REMAIN_MONTH = $Cek->REMAIN_MONTH - 1;
                        $LINENO = $Cek->LINENO + 1;
                        $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                            $INTEREST_AMOUNT_MONTHLY = $Cek->REMAIN_BASIC_AMOUNT_LEASING*(($INTEREST_PERCENTAGE/100) / 12);
                            $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY;
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                            $INTEREST_AMOUNT_MONTHLY = round((($INTEREST_PERCENTAGE/100)*$Cek->REMAIN_BASIC_AMOUNT_LEASING/12));
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY);
                            $BASIC_AMOUNT_MONTHLY    = round($AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY);
                            $BASIC_AMOUNT_YEARLY     = round($AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY);

                            $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY);
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                            $BASIC_AMOUNT_MONTHLY    = round($getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH);

                            $INTEREST_AMOUNT_MONTHLY = round(((($getDetails->BASIC_AMOUNT-($MONTHTOBE-1)*$BASIC_AMOUNT_MONTHLY)*$INTEREST_PERCENTAGE/100)/12));
                            
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY);
                            
                        }
                        // var_dump($this->db->last_query());exit;
                    }

                    if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                        $AMOUNT_MONTHLY_LEASING  = ($getDetails->BASIC_AMOUNT * (($INTEREST_PERCENTAGE / 100 ) / 12) / (1-pow(1/(1+(($INTEREST_PERCENTAGE / 100) / 12)),$getDetails->TOTAL_MONTH)));
                        
                        $AMOUNT_YEARLY_LEASING   = $AMOUNT_MONTHLY_LEASING * $MONTHTOBE;                    
                        
                        $BASIC_AMOUNT_MONTHLY    = $AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY;
                        $BASIC_AMOUNT_YEARLY     = $AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY;

                        $REMAIN_BASIC_AMOUNT_LEASING = $getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY;
                        $REMAIN_TOTAL_AMOUNT_LEASING = $REMAIN_MONTH * $AMOUNT_MONTHLY_LEASING;
                        $REMAIN_INTEREST_AMOUNT_LEASING = $REMAIN_TOTAL_AMOUNT_LEASING-$REMAIN_BASIC_AMOUNT_LEASING;

                    }

                    if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                        // $AMOUNT_MONTHLY_LEASING  = round(($getDetails->BASIC_AMOUNT * (($INTEREST_PERCENTAGE / 100 ) / 12) / (1-pow(1/(1+(($INTEREST_PERCENTAGE / 100) / 12)),($getDetails->TOTAL_MONTH-1)))));
                        
                        // $AMOUNT_YEARLY_LEASING   = $AMOUNT_MONTHLY_LEASING * $MONTHTOBE;                    
                        
                        
                        $REMAIN_TOTAL_AMOUNT_LEASING    = round($REMAIN_MONTH * $AMOUNT_MONTHLY_LEASING);
                        $REMAIN_INTEREST_AMOUNT_LEASING = round($REMAIN_TOTAL_AMOUNT_LEASING-$REMAIN_BASIC_AMOUNT_LEASING);

                    }
                    if($getDetails->TRANSACTIONMETHOD_BY == 'FLAT'){

                        $AMOUNT_MONTHLY_LEASING  = round($getDetails->AMOUNT_BEFORE_CONV / $getDetails->TOTAL_MONTH);
                        $AMOUNT_YEARLY_LEASING   = round($MONTHTOBE * $AMOUNT_MONTHLY_LEASING);

                        $BASIC_AMOUNT_MONTHLY    = round($getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH);
                        $BASIC_AMOUNT_YEARLY     = round($BASIC_AMOUNT_MONTHLY * $MONTHTOBE);

                        $INTEREST_AMOUNT_MONTHLY = round($getDetails->INTEREST_AMOUNT / $getDetails->TOTAL_MONTH);
                        $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY * $MONTHTOBE);

                        $REMAIN_BASIC_AMOUNT_LEASING    = round($getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY);
                        $REMAIN_INTEREST_AMOUNT_LEASING = round($getDetails->INTEREST_AMOUNT-$INTEREST_AMOUNT_YEARLY);
                        $REMAIN_TOTAL_AMOUNT_LEASING = round($REMAIN_BASIC_AMOUNT_LEASING+$REMAIN_INTEREST_AMOUNT_LEASING);
                    }
                    if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                        
                        $AMOUNT_MONTHLY_LEASING  = round($BASIC_AMOUNT_MONTHLY+$INTEREST_AMOUNT_MONTHLY);
                        
                        $BASIC_AMOUNT_YEARLY     = round($BASIC_AMOUNT_MONTHLY * $MONTHTOBE);

                        $AMOUNT_YEARLY_LEASING   = round($BASIC_AMOUNT_YEARLY+$INTEREST_AMOUNT_YEARLY);

                        $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY);
                        $REMAIN_INTEREST_AMOUNT_LEASING = round($REMAIN_BASIC_AMOUNT_LEASING*($INTEREST_PERCENTAGE / 100) / 12);
                        // $REMAIN_INTEREST_AMOUNT_LEASING = ($INTEREST_AMOUNT - $INTEREST_AMOUNT_MONTHLY) / 2;
                        $REMAIN_TOTAL_AMOUNT_LEASING = round($REMAIN_BASIC_AMOUNT_LEASING + $REMAIN_INTEREST_AMOUNT_LEASING);
                    }

                    if($REMAIN_BASIC_AMOUNT_LEASING < 0){
                        $REMAIN_BASIC_AMOUNT_LEASING = 0;
                    }
                    if($REMAIN_INTEREST_AMOUNT_LEASING < 0){
                        $REMAIN_INTEREST_AMOUNT_LEASING = 0;
                    }
                    if($REMAIN_TOTAL_AMOUNT_LEASING < 0){
                        $REMAIN_TOTAL_AMOUNT_LEASING = 0;
                    }

                    if($CURR != 'IDR'){
                        $q4 = "SELECT RATE FROM CURS WHERE CURSCODE = '$CURR' AND CURSYEAR = '$startYear' AND CURSMONTH = '$startMonth'";
                    
                        $cekCURR = $this->db->query($q4)->row();
                        if($cekCURR == NULL){
                            throw new Exception("Rate $CURR Periode ".$startMonth." / ".$startYear." Tidak Ada");
                        }
                        else{
                            $cekCURR = $cekCURR->RATE;
                        }
                    }else{
                        $cekCURR = 1;
                    }

                    // var_dump($this->db->last_query());exit;

                    if($MONTHTOBE == 1){
                        $BASIC_AMOUNT_MONTHLY_CONV      = round($BASIC_AMOUNT_MONTHLY * $getDetails->RATE);
                        $INTEREST_AMOUNT_MONTHLY_CONV   = round($INTEREST_AMOUNT_MONTHLY * $getDetails->RATE);
                        $AMOUNT_MONTHLY_LEASING_CONV    = round($AMOUNT_MONTHLY_LEASING * $getDetails->RATE);
                    }else{
                        $BASIC_AMOUNT_MONTHLY_CONV      = round($BASIC_AMOUNT_MONTHLY * $cekCURR);
                        $INTEREST_AMOUNT_MONTHLY_CONV   = round($INTEREST_AMOUNT_MONTHLY * $cekCURR);
                        $AMOUNT_MONTHLY_LEASING_CONV    = round($AMOUNT_MONTHLY_LEASING * $cekCURR);
                    }

                    // var_dump($BASIC_AMOUNT_MONTHLY_CONV.$INTEREST_AMOUNT_MONTHLY_CONV.$AMOUNT_MONTHLY_LEASING_CONV);exit

                    $DUEDATE    = $startMonth.'/'.$getDetails->DUEDATE_PER_MONTH.'/'.$startYear;
                    if($startMonth == '02' || $startMonth == '2'){
                        if($getDetails->DUEDATE_PER_MONTH > 29){
                            $DUEDATE = new DateTime("last day of $startYear-2");
                            $formatDD = $DUEDATE->format('m/d/Y');    
                        }else{
                            $formatDD   = date('m/d/Y',strtotime($DUEDATE));        
                        }
                    }else{
                        $formatDD   = date('m/d/Y',strtotime($DUEDATE));    
                    }

                    $Data["ID"] = $this->uuid->v4();
                    $dt = [
                        'GID'      => $Data['ID'],
                        'DOCNUMBER' => $docnumber,
                        'PERIOD_YEAR' => $startYear,
                        'PERIOD_MONTH' => $startMonth,
                        'BASIC_AMOUNT' => $getDetails->BASIC_AMOUNT,
                        'INTEREST_AMOUNT' => $getDetails->INTEREST_AMOUNT,
                        'AMOUNT_AFTER_CONV' => $getDetails->AMOUNT_AFTER_CONV,
                        'TOTAL_MONTH' => $getDetails->TOTAL_MONTH,
                        'MONTHTOBE' => $MONTHTOBE,
                        'REMAIN_MONTH' => $REMAIN_MONTH,
                        'BASIC_AMOUNT_MONTHLY' => $BASIC_AMOUNT_MONTHLY,
                        'BASIC_AMOUNT_YEARLY' => $BASIC_AMOUNT_YEARLY,
                        'INTEREST_AMOUNT_MONTHLY' => $INTEREST_AMOUNT_MONTHLY,
                        'INTEREST_AMOUNT_YEARLY' => $INTEREST_AMOUNT_YEARLY,
                        'AMOUNT_MONTHLY_LEASING' => $AMOUNT_MONTHLY_LEASING,
                        'AMOUNT_YEARLY_LEASING' => $AMOUNT_YEARLY_LEASING,
                        'REMAIN_BASIC_AMOUNT_LEASING' => $REMAIN_BASIC_AMOUNT_LEASING,
                        'REMAIN_INTEREST_AMOUNT_LEASING' => $REMAIN_INTEREST_AMOUNT_LEASING,
                        'REMAIN_TOTAL_AMOUNT_LEASING' => $REMAIN_TOTAL_AMOUNT_LEASING,
                        'BASIC_AMOUNT_MONTHLY_CONV' => $BASIC_AMOUNT_MONTHLY_CONV,
                        'INTEREST_AMOUNT_MONTHLY_CONV' => $INTEREST_AMOUNT_MONTHLY_CONV,
                        'AMOUNT_MONTHLY_LEASING_CONV' => $AMOUNT_MONTHLY_LEASING_CONV,
                        'AMOUNT_BEFORE_CONV' => $AMOUNT_BEFORE_CONV,
                        'LINENO' => $LINENO,
                        'STATUS' => 1,
                        'FCENTRY' => $this->session->userdata('FCCODE')
                        // 'FCIP' => $Location
                    ];
                    // echo "<pre>";
                    // var_dump($dt);exit;

                    $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false);
                    
                    $result1 = $result1->set($dt)->insert('LEASINGREPORT');

                    if ($startMonth == 12){
                        $startMonth = 0;
                        $startYear = $startYear + 1;
                    }
                    $startMonth++;

                } //end for
                    
                    if($result1){
                        $result = true;
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
                
            }//end else
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

    public function checkDate(){
        $qCek = "SELECT a.DUEDATE,
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
                     WHERE to_char(a.DUEDATE,'mmyyyy') = '".Date('mY')."'
            ORDER BY TO_NUMBER (a.monthtobe) ASC";
            $return = $this->db->query($qCek)->result();
            // var_dump($this->db->last_query());exit;
            foreach ($return as $key => $row) {

                $setTask = array(
                    'CREATED_BY' => 'SYSTEM',
                    'AMOUNT'     => $row->AMOUNT_MONTHLY_LEASING,
                    'COMPANY'    => $row->COMPANYCODE,
                    'DOCNUMBER'  => $row->DOCNUMBER,
                    'VENDOR'     => $row->FCNAME,
                    'IS_SEND'    => '0'
                );
                // ini_set('display_errors', 'On');
                $formatDD   = date('m/d/Y',strtotime($row->DUEDATE));
                $getDue   = $this->getDayWork($row->DOCNUMBER);
                $getBulan = date("m",strtotime($getDue));
                $getTahun = date("Y",strtotime($getDue));

                $cekDuplicate = "SELECT * FROM TASK_SCHEDULER where COMPANY = '".$row->COMPANYCODE."' AND DOCNUMBER = '".$row->DOCNUMBER."' AND AMOUNT = '".$row->AMOUNT_MONTHLY_LEASING."' AND TO_CHAR(EMAILDATE,'mm/dd/yyyy') = '".$getDue."' AND IS_SEND = '0'";
                $cekDuplicate = $this->db->query($cekDuplicate);
                
                if($cekDuplicate->num_rows() > 0){
                    $qDel = "DELETE FROM TASK_SCHEDULER WHERE COMPANY = '".$row->COMPANYCODE."' AND DOCNUMBER = '".$row->DOCNUMBER."' AND AMOUNT = '".$row->AMOUNT_MONTHLY_LEASING."' AND TO_CHAR(EMAILDATE,'mm/dd/yyyy') = '".$getDue."'";
                    $this->db->query($qDel);
                    // var_dump($this->db->last_query());exit;
                }else{
                    $insertTask = $this->db->set('CREATED_AT', "SYSDATE", false)
                                    ->set("EMAILDATE","TO_DATE('".$getDue."','mm-dd-yyyy')", false)
                                    ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                                    ->set($setTask)->insert("TASK_SCHEDULER");
                    $this->db->set('TIPE','1');
                    $insertDue = $this->db->set('CREATED_AT', "SYSDATE", false)
                                    ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                                    ->set("EMAILDATE","TO_DATE('".$getBulan.'/'.'25/'.$getTahun."','mm-dd-yyyy')", false)
                                    ->set($setTask)->insert("TASK_SCHEDULER");
                }
            }
        echo "<pre>";
        print_r($this->db->get('TASK_SCHEDULER')->result());
            
    }

    function getDayWork($docnumber){
        $q = "select to_char(dt,'mm/dd/yyyy') dt from (select rownum as no, dt,duedate from (
                select cek.dt,lt.duedate,lt.docnumber from leasingtransaction lt right join (
                SELECT * FROM (SELECT *
                  FROM (    SELECT DATE '2022-01-01' + ROWNUM - 1 dt
                              FROM DUAL
                        CONNECT BY LEVEL <=
                                      (SELECT TRUNC (
                                                   TO_DATE ('01/01/2023', 'mm/dd/yyyy')
                                                 - TO_DATE ('01/01/2022', 'mm/dd/yyyy'))
                                                 AS days
                                         FROM DUAL)
                        MINUS
                        SELECT DISTINCT HariLibur
                          FROM (SELECT dt AS HariLibur
                                  FROM (WITH dts
                                             AS (    SELECT DATE '2022-01-01' + ROWNUM - 1 dt
                                                       FROM DUAL
                                                 CONNECT BY LEVEL <=
                                                               (SELECT TRUNC (
                                                                            TO_DATE (
                                                                               '01/01/2023',
                                                                               'mm/dd/yyyy')
                                                                          - TO_DATE (
                                                                               '01/01/2022',
                                                                               'mm/dd/yyyy'))
                                                                          AS days
                                                                  FROM DUAL))
                                        SELECT *
                                          FROM dts
                                         WHERE TO_CHAR (dt,
                                                        'fmday',
                                                        'NLS_DATE_LANGUAGE=AMERICAN') =
                                                  'saturday')
                                UNION ALL
                                SELECT dt AS HariLibur
                                  FROM (WITH dts
                                             AS (    SELECT DATE '2022-01-01' + ROWNUM - 1 dt
                                                       FROM DUAL
                                                 CONNECT BY LEVEL <=
                                                               (SELECT TRUNC (
                                                                            TO_DATE (
                                                                               '01/01/2023',
                                                                               'mm/dd/yyyy')
                                                                          - TO_DATE (
                                                                               '01/01/2022',
                                                                               'mm/dd/yyyy'))
                                                                          AS days
                                                                  FROM DUAL))
                                        SELECT *
                                          FROM dts
                                         WHERE TO_CHAR (dt,
                                                        'fmday',
                                                        'NLS_DATE_LANGUAGE=AMERICAN') =
                                                  'sunday')
                                UNION ALL
                                SELECT holidaydate
                                  FROM holiday
                                 WHERE period_year = 2022)
                        ORDER BY dt)
                UNION ALL
                SELECT *
                  FROM (    SELECT DATE '2021-01-01' + ROWNUM - 1 dt
                              FROM DUAL
                        CONNECT BY LEVEL <=
                                      (SELECT TRUNC (
                                                   TO_DATE ('01/01/2022', 'mm/dd/yyyy')
                                                 - TO_DATE ('01/01/2021', 'mm/dd/yyyy'))
                                                 AS days
                                         FROM DUAL)
                        MINUS
                        SELECT DISTINCT HariLibur
                          FROM (SELECT dt AS HariLibur
                                  FROM (WITH dts
                                             AS (    SELECT DATE '2021-01-01' + ROWNUM - 1 dt
                                                       FROM DUAL
                                                 CONNECT BY LEVEL <=
                                                               (SELECT TRUNC (
                                                                            TO_DATE (
                                                                               '01/01/2022',
                                                                               'mm/dd/yyyy')
                                                                          - TO_DATE (
                                                                               '01/01/2021',
                                                                               'mm/dd/yyyy'))
                                                                          AS days
                                                                  FROM DUAL))
                                        SELECT *
                                          FROM dts
                                         WHERE TO_CHAR (dt,
                                                        'fmday',
                                                        'NLS_DATE_LANGUAGE=AMERICAN') =
                                                  'saturday')
                                UNION ALL
                                SELECT dt AS HariLibur
                                  FROM (WITH dts
                                             AS (    SELECT DATE '2021-01-01' + ROWNUM - 1 dt
                                                       FROM DUAL
                                                 CONNECT BY LEVEL <=
                                                               (SELECT TRUNC (
                                                                            TO_DATE (
                                                                               '01/01/2022',
                                                                               'mm/dd/yyyy')
                                                                          - TO_DATE (
                                                                               '01/01/2021',
                                                                               'mm/dd/yyyy'))
                                                                          AS days
                                                                  FROM DUAL))
                                        SELECT *
                                          FROM dts
                                         WHERE TO_CHAR (dt,
                                                        'fmday',
                                                        'NLS_DATE_LANGUAGE=AMERICAN') =
                                                  'sunday')
                                UNION ALL
                                SELECT holidaydate
                                  FROM holiday
                                 WHERE period_year = 2021)
                        ORDER BY dt))) cek on (lt.duedate >= cek.dt) 
                where lt.docnumber= '$docnumber' order by cek.dt desc)) where no = 7";
        return $this->db->query($q)->row()->DT;
    }

	function sendEmail(){

		$from = "bank.relation@kpndomain.com";
        $config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'mail.kpndomain.com',
                'smtp_port' => 587,
                'smtp_user' => $from,
                'smtp_pass' => 'Kpn#2022',
                'mailtype'  => 'html', 
                'charset'   => 'UTF-8'
        );
        $dateNow = Date('m/d/Y');
        $q 		 = "SELECT * FROM TASK_SCHEDULER WHERE to_char(EMAILDATE,'mm/dd/yyyy') = '$dateNow' AND IS_SEND = '0'";
        $res 	 = $this->db->query($q)->result();
        $data['task']    = $res;
        // var_dump($data);exit;
        if($res == null){
            echo "data kosong";
        }else{
            // foreach ($res as $key => $value) {
                $this->load->library('email');
                $this->email->set_mailtype("html");
                $this->email->set_newline("\r\n");
                $this->email->set_crlf("\r\n");
                $this->email->set_header('MIME-Version', '1.0; charset=utf-8'); 
                $this->email->set_header('Content-type', 'text/html');  
                $this->email->initialize($config);
                $this->email->from($from, "Bank Relation");
                $this->email->to('afif.julhendrik@kpn-corp.com'); 
                $this->email->to('triputraanugrah@gmail.com');
                $this->email->to('tri.anugrah@kpn-corp.com');
                $this->email->subject("Reminder Leasing");

                $message = $this->load->view('emailprogress',$data,true);
                
                $this->email->message($message);

                if(!$this->email->send())
                {
                    $error = show_error($this->email->print_debugger());
                    echo "<pre>";
                    print_r($error);
                    echo "</pre>";

                }
                else{
                    foreach ($res as $key => $val) {
                        $this->db->where('ID',$val->ID);
                        $this->db->set('IS_SEND',1);
                        $this->db->set('CREATED_AT', "SYSDATE", false);
                        $this->db->update('TASK_SCHEDULER');
                    }

                    echo "Updated";
                    
                }
            // }
        }

	}

}

/* End of file CronController.php */
/* Location: ./application/controllers/CronController.php */