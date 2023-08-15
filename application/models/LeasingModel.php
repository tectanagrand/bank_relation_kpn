<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class LeasingModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function GetData($param) {
        $SQL = "SELECT LM.*, C.COMPANYNAME, B.ID AS BUID, B.FCNAME AS BUFCNAME, S.FCNAME AS VENDORNAME, S.ID AS SUPPID, M.ID AS MID, M.FCNAME AS MFCNAME FROM LEASINGMASTER LM INNER JOIN COMPANY C ON C.ID = LM.COMPANY INNER JOIN SUPPLIER S ON S.ID = LM.VENDOR
                     INNER JOIN MATERIAL M ON M.ID = LM.ITEM_CODE
                     INNER JOIN BUSINESSUNIT B ON B.ID = LM.BUSINESSUNIT WHERE LM.UUID = ?";
        $result = $this->db->query($SQL, $param["UUID"])->row();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function ShowDataPeriod() {
        $this->fillable = ['C.COMPANYNAME',"P.CURRENTDATE","P.CURRENTACCOUNTINGYEAR" ,'P.CURRENTACCOUNTINGPERIOD', 'P.CLOSEACCOUNTINGYEAR', 'P.CLOSEACCOUNTINGPERIOD'];
        $result = $this->db->select($this->fillable)
                        ->from("PERIODCONTROL P")
                        ->join("COMPANY C", 'C.ID = P.COMPANY', 'left')
                        ->order_by('P.COMPANY ASC')->get()->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
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

            $q = "update periodcontrol set lastperiodposted = (select last_day(add_months((to_date('$MONTH/$YEAR','mm/yyyy')),-1)) from dual), currentdate = (select trunc(add_months((to_date('$MONTH/$YEAR','mm/yyyy')),-0),'month') from dual), currentaccountingyear = (select to_number('$YEAR') as from dual), currentaccountingperiod = (select to_number('$MONTH') as from dual), closeaccountingyear = ( select case when (select to_number('$MONTH') as from dual) -1 = 0 then (select to_number('$YEAR') as from dual)-1 else (select to_number('$YEAR') as from dual) end as closeaccountingyear from dual ), closeaccountingperiod = ( select case when (select to_number('$MONTH') as from dual) -1 = 0 then 12 else (select to_number('$MONTH') as from dual)-1 end as closeaccountingperiod from dual ) ". $WHERE;
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

    public function Save($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit();

        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            // "TO_DATE('" . $Data["DOCDATE"] . "','MM/DD/YYYY')"
            $BASIC_AMOUNT       = intval(preg_replace("/[^\d\.\-]/","",$param['BASIC_AMOUNT']));
            $INTEREST_AMOUNT    = intval(preg_replace("/[^\d\.\-]/","",$param['INTEREST_AMOUNT']));
            $AMOUNT_BEFORE_CONV = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_BEFORE_CONV']));
            $AMOUNT_AFTER_CONV  = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_AFTER_CONV']));
            $AMOUNT_PER_MONTH   = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_PER_MONTH']));
            $BIC                = $param['BIC'];

            $getQ = "select ID,FCCODE from material where extsystem||fccode = ( select detailid||detailname from bmcodedetail where masterid = '000019' and detailid = '".$param['EXTSYS']."' )";
            $getItemCode = $this->db->query($getQ)->row();

            
            // var_dump($genid);exit;

            $dt = [
                'COMPANY' => $param['COMPANY'],
                'BUSINESSUNIT' => $param['BUSINESSUNIT'],
                'DEPARTMENT' => $param['DEPARTMENT'],
                'DOCNUMBER' => trim($param['DOCNUMBER']),
                'DESCRIPTION' => $param['DESCRIPTION'],
                'VENDOR' => $param['VENDOR'],
                'ITEM_CODE' => $getItemCode->ID,
                'EXTSYS' => $param['EXTSYS'],
                // 'DOCDATE' => $param['DOCDATE'],
                // 'VALID_FROM' => $param['VALID_FROM'],
                // 'VALID_UNTIL' => $param['VALID_UNTIL'],
                'DUEDATE_PER_MONTH' => $param['DUEDATE_PERMONTH'],
                'TOTAL_MONTH' => $param['TOTAL_MONTH'],
                'CURRENCY' => $param['CURRENCY'],
                'BASIC_AMOUNT' => $BASIC_AMOUNT,
                'INTEREST_PERCENTAGE' => $param['INTEREST_PERCENTAGE'],
                'DENDA_PERCENTAGE' => $param['DENDA_PERCENTAGE'],
                'PENALTY_PERCENTAGE'  => $param['PENALTY_PERCENTAGE'],
                'INTEREST_AMOUNT' => $INTEREST_AMOUNT,
                'AMOUNT_BEFORE_CONV' => $AMOUNT_BEFORE_CONV,
                'RATE' => $param['RATE'],
                'AMOUNT_AFTER_CONV' => $AMOUNT_AFTER_CONV,
                'AMOUNT_PER_MONTH' => $AMOUNT_PER_MONTH,
                'TRANSACTIONMETHOD_BY' => $param['TRANSACTIONMETHOD_BY'],
                // 'RATE' => $param['RATE'],
                'FCENTRY' => $param['USERNAME'],
                'FCIP' => $Location,
                'ISACTIVE' => 'TRUE'
            ];
            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                        ->set("DOCDATE","TO_DATE('" . $param['DOCDATE'] . "','yyyy-mm-dd')", false)
                        ->set("VALID_FROM","TO_DATE('" . $param['VALID_FROM'] . "','yyyy-mm-dd')", false)
                        ->set("VALID_UNTIL","TO_DATE('" . $param['VALID_UNTIL'] . "','yyyy-mm-dd')", false);

            if ($param['ACTION'] == 'ADD') {
                ini_set('display_errors', 'On');
                $SQL = "SELECT * FROM LEASINGMASTER WHERE DOCNUMBER = ?";
                $Cek = $this->db->query($SQL, $param['DOCNUMBER']);
                if ($Cek->num_rows() > 0) {
                    throw new Exception('DOCNUMBER Already Exists !!');
                }else{
                    $checkCompany = $this->db->query("SELECT C.COMPANY_SUBGROUP,CE.EXTSYSCOMPANYCODE FROM COMPANY C INNER JOIN COMPANY_EXTSYS CE ON CE.COMPANY = C.ID WHERE C.ID = '".$param['COMPANY']."' AND CE.EXTSYSTEM = 'SAPHANA'")->row();

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

            // var_dump($comp);exit;
            ini_set('display_errors', 'On');
            $qgenid = $this->db->query("SELECT NVL((COUNTER),0)+1 AS GENID FROM LEASINGMASTER ORDER BY ID DESC FETCH FIRST 1 ROW ONLY")->row()->GENID;
            $qgenid =  sprintf('%03d', $qgenid);
            $genid  = Date('y').$comp."LS".$BIC.'A0'.$qgenid;

            $cekDup = $this->db->get_where('LEASINGMASTER',['GENID' => $genid])->row();
            
            if($cekDup == NULL){
                $genid = $genid;
            }else{
                $qgenid = $this->db->query("SELECT NVL((COUNTER),0)+1 AS GENID FROM LEASINGMASTER ORDER BY ID DESC FETCH FIRST 1 ROW ONLY")->row()->GENID;
                $qgenid =  sprintf('%03d', $qgenid);
                $genid  = Date('y').$comp."LS".$BIC.'A0'.$qgenid;
            }
                    $dt['FCENTRY'] = $param['USERNAME'];
                    $dt['GENID'] = $genid;
                    $dt['COUNTER'] = $qgenid;
                    $dt['UUID'] = $this->uuid->v4();
                    $UUID = $dt['UUID'];
                    $result1 = $result1->set($dt)->insert('LEASINGMASTER');
                }
                
            } elseif ($param['ACTION'] == 'EDIT') {
                // $this->db->where(['UUID' => $param['ID']]);
                $this->db->where('DOCNUMBER',trim($param['DOCNUMBER']));
                $this->db->delete('LEASINGREPORT');
                $UUID = $param['ID'];
                $result1 = $result1->set($dt)
                        ->where(['UUID' => $param['ID']])
                        ->update('LEASINGMASTER');
            
            }
            if ($result1) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!',
                    'IDS' => $UUID
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

    public function saveLeasingReport($docnumber, $company) {
        // echo "<pre>";
        // var_dump($param);exit();
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
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                            $INTEREST_AMOUNT_MONTHLY = $getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12;
                            $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY;
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                            $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                            $INTEREST_AMOUNT_MONTHLY = 0;
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY);
                            
                            $BASIC_AMOUNT_MONTHLY    = round($AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY);
                            $BASIC_AMOUNT_YEARLY     = round($AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY);
                            $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT);
                            // $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY);
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
                        
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                            $INTEREST_AMOUNT_MONTHLY = $Cek->REMAIN_BASIC_AMOUNT_LEASING*(($INTEREST_PERCENTAGE/100) / 12);
                            $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY;
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                            $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                            $INTEREST_AMOUNT_MONTHLY = round((($INTEREST_PERCENTAGE/100)*$Cek->REMAIN_BASIC_AMOUNT_LEASING/12));
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY);
                            $BASIC_AMOUNT_MONTHLY    = round($AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY);
                            $BASIC_AMOUNT_YEARLY     = round($AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY);

                            $REMAIN_BASIC_AMOUNT_LEASING = round($Cek->REMAIN_BASIC_AMOUNT_LEASING - $BASIC_AMOUNT_MONTHLY);
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

    public function uploadFile($param,$location) {

        try
        {   
            $USERNAME     = $param['USERNAME'];
            $UUID         = $param['UUID'];
            $NOTES        = $param['NOTES'];
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
            $this->upload->do_upload('userfile');
            // var_dump($config);exit;
            if (!$this->upload->do_upload('userfile')){
                throw new Exception($this->upload->display_errors());
                
            }elseif($this->upload->do_upload()){
                $media = $this->upload->data();

                $hp = array(
                    'UUID' => $UUID,
                    'FILENAME' => $media['file_name'],
                    'FCENTRY'   => $USERNAME,
                    'FCIP' => $location,
                    'NOTES' => $NOTES,
                );
                
                $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                ->set($hp)->insert('LEASINGFILE');
                $qGet = "SELECT * FROM LEASINGFILE ";
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

    public function DeleteMaster($param){
        
        try {
            $this->db->trans_begin();
            // $TIPE = $param['TIPE'];
            $DOCNUMBER  = $param['DOCNUMBER'];
            $res  = FALSE;
            $cekq  = "SELECT DOCNUMBER,GID
                      FROM LEASINGTRANSACTION LT 
                      INNER JOIN PAYMENT PY ON PY.CFTRANSID = LT.GID
                     WHERE LT.DOCNUMBER = '$DOCNUMBER'";
            $cek   = $this->db->query($cekq);
            // var_dump($cek->num_rows());exit;
            if($cek->num_rows() > 0){
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Data Sudah Ada Transaksi !!'
                ];
            }else{
                $q   = "DELETE FROM LEASINGMASTER WHERE DOCNUMBER = '".$DOCNUMBER."'";
                $res = $this->db->query($q);
            }

            if ($res) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback(); 
                // throw new Exception('Data Save Failed !!');
                $return = [
                    'STATUS' => FALSE,
                    'MESSAGE' => 'Data Sudah Ada Transaksi !!'
                ];
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function ShowData() {
        $result = $this->db->select("L.ID,L.UUID, C.COMPANYNAME, L.COMPANY,L.GENID, L.DOCNUMBER, S.FCNAME, B.FCNAME AS BUNAME, L.TRANSACTIONMETHOD_BY")
                        ->from("LEASINGMASTER L")
                        ->join("COMPANY C", 'C.ID = L.COMPANY', 'left')
                        ->join("SUPPLIER S", 'S.ID = L.VENDOR', 'left')
                        ->join("BUSINESSUNIT B", 'B.ID = L.BUSINESSUNIT', 'left')
                        ->where('L.ISACTIVE','TRUE')
                        ->order_by('L.ID DESC')->get()->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function ShowFileData($param) {

        $result = $this->db->select("*")
                        ->from("LEASINGFILE")
                        ->where('UUID',$param['UUID'])
                        ->order_by('ID DESC')->get()->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    public function ShowDataTransaction($param) {
        $COMPANY = $param['COMPANY'];
        if($COMPANY == null || $COMPANY == ''){
            $qPeriod = "SELECT COUNT (DISTINCT CURRENTACCOUNTINGPERIOD) as CURRENTACCOUNTINGPERIOD, COUNT (DISTINCT CURRENTACCOUNTINGYEAR) FROM PERIODCONTROL";
            $cekPeriod = $this->db->query($qPeriod)->row();
            if($cekPeriod->CURRENTACCOUNTINGPERIOD == 1){
                $sql = "";
                $sql2 = " WHERE ";
            }
            else{
                throw new Exception('Period Tidak Sama');
            }
            
        }else{
            $sql  = " WHERE LM.COMPANY = '".$COMPANY."'";
            $sql2 = " WHERE LM.COMPANY = '".$COMPANY."' AND ";
        }

        $q = "SELECT UUID,
       COMPANY,
       DOCNUMBER,
       DUEDATE_PER_MONTH,
       CURRENCY,
       BASIC_AMOUNT,
       AMOUNT_PER_MONTH,
       AMOUNT_BEFORE_CONV,
       AMOUNT_AFTER_CONV,
       INTEREST_PERCENTAGE,
       INTEREST_AMOUNT,
       TOTAL_MONTH,
       BASIC_AMOUNT_MONTHLY,
       INTEREST_AMOUNT_MONTHLY,
       AMOUNT_MONTHLY_LEASING,
       MONTHTOBE,
       REMAIN_BASIC_AMOUNT_LEASING,
       REMAIN_INTEREST_AMOUNT_LEASING,
       REMAIN_TOTAL_AMOUNT_LEASING,
       REMAIN_MONTH,
       TRANSACTIONMETHOD_BY,
       PERIOD_YEAR,
       PERIOD_MONTH,
       BASIC_AMOUNT_MONTHLY_CONV,
       INTEREST_AMOUNT_MONTHLY_CONV,
       AMOUNT_MONTHLY_LEASING_CONV,
       COMPANYNAME,
       CURRENTACCOUNTINGPERIOD,
       CURRENTACCOUNTINGYEAR,
       FCNAME,
       VENDORNAME,
       status
  FROM (  SELECT UUID,
                 COMPANY,
                 DOCNUMBER,
                 DUEDATE_PER_MONTH,
                 CURRENCY,
                 BASIC_AMOUNT,
                 AMOUNT_PER_MONTH,
                 AMOUNT_BEFORE_CONV,
                 AMOUNT_AFTER_CONV,
                 INTEREST_PERCENTAGE,
                 INTEREST_AMOUNT,
                 TOTAL_MONTH,
                 BASIC_AMOUNT_MONTHLY,
                 INTEREST_AMOUNT_MONTHLY,
                 AMOUNT_MONTHLY_LEASING,
                 MONTHTOBE,
                 REMAIN_BASIC_AMOUNT_LEASING,
                 REMAIN_INTEREST_AMOUNT_LEASING,
                 REMAIN_TOTAL_AMOUNT_LEASING,
                 REMAIN_MONTH,
                 TRANSACTIONMETHOD_BY,
                 MAX (PERIOD_YEAR) AS PERIOD_YEAR,
                 MAX (PERIOD_MONTH) AS PERIOD_MONTH,
                 BASIC_AMOUNT_MONTHLY_CONV,
                 INTEREST_AMOUNT_MONTHLY_CONV,
                 AMOUNT_MONTHLY_LEASING_CONV,
                 COMPANYNAME,
                 CURRENTACCOUNTINGPERIOD,
                 CURRENTACCOUNTINGYEAR,
                 FCNAME,
                 VENDORNAME,
                 MAX (status) AS status,
                 RANK ()
                 OVER (PARTITION BY DOCNUMBER
                       ORDER BY MAX (STATUS) DESC)
                    AS RANKI
            FROM (SELECT LM.UUID,
                         LM.COMPANY,
                         LM.DOCNUMBER,
                         LM.DUEDATE_PER_MONTH,
                         LM.CURRENCY,
                         LM.BASIC_AMOUNT,
                         LM.AMOUNT_PER_MONTH,
                         LM.AMOUNT_BEFORE_CONV,
                         LM.AMOUNT_AFTER_CONV,
                         LM.INTEREST_PERCENTAGE,
                         LM.INTEREST_AMOUNT,
                         LM.TOTAL_MONTH,
                         LT.BASIC_AMOUNT_MONTHLY,
                         LT.INTEREST_AMOUNT_MONTHLY,
                         LT.AMOUNT_MONTHLY_LEASING,
                         LT.MONTHTOBE,
                         LT.REMAIN_BASIC_AMOUNT_LEASING,
                         LT.REMAIN_INTEREST_AMOUNT_LEASING,
                         LT.REMAIN_TOTAL_AMOUNT_LEASING,
                         LT.REMAIN_MONTH,
                         TRANSACTIONMETHOD_BY,
                         TO_NUMBER (LT.PERIOD_YEAR) AS PERIOD_YEAR,
                         TO_NUMBER (LT.PERIOD_MONTH) AS PERIOD_MONTH,
                         LT.BASIC_AMOUNT_MONTHLY_CONV,
                         LT.INTEREST_AMOUNT_MONTHLY_CONV,
                         LT.AMOUNT_MONTHLY_LEASING_CONV,
                         C.COMPANYNAME,
                         PC.CURRENTACCOUNTINGPERIOD,
                         PC.CURRENTACCOUNTINGYEAR,
                         M.FCNAME,
                         S.FCNAME AS VENDORNAME,
                         0 AS status
                    FROM (SELECT DOCNUMBER,
                                 PERIOD_YEAR,
                                 PERIOD_MONTH,
                                 BASIC_AMOUNT_MONTHLY,
                                 INTEREST_AMOUNT_MONTHLY,
                                 AMOUNT_MONTHLY_LEASING,
                                 MONTHTOBE,
                                 REMAIN_BASIC_AMOUNT_LEASING,
                                 REMAIN_INTEREST_AMOUNT_LEASING,
                                 REMAIN_TOTAL_AMOUNT_LEASING,
                                 BASIC_AMOUNT_MONTHLY_CONV,
                                 INTEREST_AMOUNT_MONTHLY_CONV,
                                 AMOUNT_MONTHLY_LEASING_CONV,
                                 REMAIN_MONTH,
                                 STATUS
                            FROM LEASINGTRANSACTION
                           WHERE DOCNUMBER NOT IN (SELECT DOCNUMBER
                                                     FROM LEASINGTRANSACTION
                                                    WHERE REMAIN_MONTH = 0)) LT
                         INNER JOIN LEASINGMASTER LM
                            ON LM.DOCNUMBER = LT.DOCNUMBER
                         INNER JOIN PERIODCONTROL PC
                            ON     PC.COMPANY = LM.COMPANY
                               AND PC.closeaCCOUNTINGPERIOD = LT.PERIOD_MONTH
                         INNER JOIN COMPANY C ON C.ID = LM.COMPANY
                         INNER JOIN MATERIAL M ON M.ID = LM.ITEM_CODE
                         INNER JOIN SUPPLIER S ON S.ID = LM.VENDOR ";
                         $q .= $sql;
                  $q .= " UNION ALL
                  SELECT LM.UUID,
                         LM.COMPANY,
                         LM.DOCNUMBER,
                         LM.DUEDATE_PER_MONTH,
                         LM.CURRENCY,
                         LM.BASIC_AMOUNT,
                         LM.AMOUNT_PER_MONTH,
                         LM.AMOUNT_BEFORE_CONV,
                         LM.AMOUNT_AFTER_CONV,
                         LM.INTEREST_PERCENTAGE,
                         LM.INTEREST_AMOUNT,
                         LM.TOTAL_MONTH,
                         0 AS BASIC_AMOUNT_MONTHLY,
                         0 AS INTEREST_AMOUNT_MONTHLY,
                         0 AS AMOUNT_MONTHLY_LEASING,
                         '0' AS MONTHTOBE,
                         0 AS REMAIN_BASIC_AMOUNT_LEASING,
                         0 AS REMAIN_INTEREST_AMOUNT_LEASING,
                         0 AS REMAIN_TOTAL_AMOUNT_LEASING,
                         '0' AS REMAIN_MONTH,
                         TRANSACTIONMETHOD_BY,
                         PC.CURRENTACCOUNTINGYEAR,
                         PC.CURRENTACCOUNTINGPERIOD,
                         0 AS BASIC_AMOUNT_MONTHLY_CONV,
                         0 AS INTEREST_AMOUNT_MONTHLY_CONV,
                         0 AS AMOUNT_MONTHLY_LEASING_CONV,
                         C.COMPANYNAME,
                         PC.CURRENTACCOUNTINGPERIOD,
                         PC.CURRENTACCOUNTINGYEAR,
                         M.FCNAME,
                         S.FCNAME AS VENDORNAME,
                         0 AS status
                    FROM LEASINGMASTER LM
                         INNER JOIN PERIODCONTROL PC ON PC.COMPANY = LM.COMPANY
                         INNER JOIN COMPANY C ON C.ID = LM.COMPANY
                         INNER JOIN MATERIAL M ON M.ID = LM.ITEM_CODE
                         INNER JOIN SUPPLIER S ON S.ID = LM.VENDOR ";
                $q.= $sql2;
            $q.=" TO_CHAR (LM.VALID_FROM, 'mm/yyyy') =
                                TO_CHAR (PC.CURRENTDATE, 'mm/yyyy')
                  UNION ALL
                  SELECT LM.UUID,
                         LM.COMPANY,
                         LM.DOCNUMBER,
                         LM.DUEDATE_PER_MONTH,
                         LM.CURRENCY,
                         LM.BASIC_AMOUNT,
                         LM.AMOUNT_PER_MONTH,
                         LM.AMOUNT_BEFORE_CONV,
                         LM.AMOUNT_AFTER_CONV,
                         LM.INTEREST_PERCENTAGE,
                         LM.INTEREST_AMOUNT,
                         LM.TOTAL_MONTH,
                         LT.BASIC_AMOUNT_MONTHLY,
                         LT.INTEREST_AMOUNT_MONTHLY,
                         LT.AMOUNT_MONTHLY_LEASING,
                         LT.MONTHTOBE,
                         LT.REMAIN_BASIC_AMOUNT_LEASING,
                         LT.REMAIN_INTEREST_AMOUNT_LEASING,
                         LT.REMAIN_TOTAL_AMOUNT_LEASING,
                         LT.REMAIN_MONTH,
                         TRANSACTIONMETHOD_BY,
                         TO_NUMBER (LT.PERIOD_YEAR) AS PERIOD_YEAR,
                         TO_NUMBER (LT.PERIOD_MONTH) AS PERIOD_MONTH,
                         LT.BASIC_AMOUNT_MONTHLY_CONV,
                         LT.INTEREST_AMOUNT_MONTHLY_CONV,
                         LT.AMOUNT_MONTHLY_LEASING_CONV,
                         C.COMPANYNAME,
                         PC.CURRENTACCOUNTINGPERIOD,
                         PC.CURRENTACCOUNTINGYEAR,
                         M.FCNAME,
                         S.FCNAME AS VENDORNAME,
                         1 AS status
                    FROM (SELECT DOCNUMBER,
                                 PERIOD_YEAR,
                                 PERIOD_MONTH,
                                 STATUS,
                                 BASIC_AMOUNT_MONTHLY,
                                 INTEREST_AMOUNT_MONTHLY,
                                 AMOUNT_MONTHLY_LEASING,
                                 MONTHTOBE,
                                 REMAIN_BASIC_AMOUNT_LEASING,
                                 REMAIN_INTEREST_AMOUNT_LEASING,
                                 REMAIN_TOTAL_AMOUNT_LEASING,
                                 REMAIN_MONTH,
                                 BASIC_AMOUNT_MONTHLY_CONV,
                                 INTEREST_AMOUNT_MONTHLY_CONV,
                                 AMOUNT_MONTHLY_LEASING_CONV
                            FROM LEASINGTRANSACTION
                           WHERE DOCNUMBER NOT IN (SELECT DOCNUMBER
                                                     FROM LEASINGTRANSACTION
                                                    WHERE REMAIN_MONTH = 0)) LT
                         INNER JOIN LEASINGMASTER LM
                            ON LM.DOCNUMBER = LT.DOCNUMBER
                         INNER JOIN PERIODCONTROL PC
                            ON     PC.COMPANY = LM.COMPANY
                               AND PC.currentaCCOUNTINGPERIOD = LT.PERIOD_MONTH
                         INNER JOIN COMPANY C ON C.ID = LM.COMPANY
                         INNER JOIN MATERIAL M ON M.ID = LM.ITEM_CODE
                         INNER JOIN SUPPLIER S ON S.ID = LM.VENDOR ";
                   $q.= $sql; 
                   $q.= " ) GROUP BY UUID,
                 COMPANY,
                 DOCNUMBER,
                 DUEDATE_PER_MONTH,
                 CURRENCY,
                 BASIC_AMOUNT,
                 AMOUNT_PER_MONTH,
                 AMOUNT_BEFORE_CONV,
                 AMOUNT_AFTER_CONV,
                 INTEREST_PERCENTAGE,
                 INTEREST_AMOUNT,
                 TOTAL_MONTH,
                 BASIC_AMOUNT_MONTHLY,
                 INTEREST_AMOUNT_MONTHLY,
                 AMOUNT_MONTHLY_LEASING,
                 MONTHTOBE,
                 REMAIN_BASIC_AMOUNT_LEASING,
                 REMAIN_INTEREST_AMOUNT_LEASING,
                 REMAIN_TOTAL_AMOUNT_LEASING,
                 REMAIN_MONTH,
                 TRANSACTIONMETHOD_BY,
                 COMPANYNAME,
                 CURRENTACCOUNTINGPERIOD,
                 CURRENTACCOUNTINGYEAR,
                 BASIC_AMOUNT_MONTHLY_CONV,
                 INTEREST_AMOUNT_MONTHLY_CONV,
                 AMOUNT_MONTHLY_LEASING_CONV,
                 FCNAME,
                 VENDORNAME) AA
 WHERE RANKI = 1";
        // $q = "SELECT UUID, COMPANY, DOCNUMBER, DUEDATE_PER_MONTH, BASIC_AMOUNT, AMOUNT_PER_MONTH, AMOUNT_AFTER_CONV, INTEREST_PERCENTAGE, INTEREST_AMOUNT, TOTAL_MONTH, TRANSACTIONMETHOD_BY, MAX (PERIOD_YEAR) AS PERIOD_YEAR, MAX (PERIOD_MONTH) AS PERIOD_MONTH, COMPANYNAME, CURRENTACCOUNTINGPERIOD, CURRENTACCOUNTINGYEAR, FCNAME, MAX (status) AS status FROM (SELECT LM.UUID, LM.COMPANY, LM.DOCNUMBER, LM.DUEDATE_PER_MONTH, LM.BASIC_AMOUNT, LM.AMOUNT_PER_MONTH, LM.AMOUNT_AFTER_CONV, LM.INTEREST_PERCENTAGE, LM.INTEREST_AMOUNT, LM.TOTAL_MONTH, TRANSACTIONMETHOD_BY, TO_NUMBER (LT.PERIOD_YEAR) AS PERIOD_YEAR, TO_NUMBER (LT.PERIOD_MONTH) AS PERIOD_MONTH, C.COMPANYNAME, PC.CURRENTACCOUNTINGPERIOD, PC.CURRENTACCOUNTINGYEAR, M.FCNAME, 0 AS status FROM (SELECT DOCNUMBER, PERIOD_YEAR, PERIOD_MONTH, STATUS FROM LEASINGTRANSACTION WHERE DOCNUMBER NOT IN (SELECT DOCNUMBER FROM LEASINGTRANSACTION WHERE REMAIN_MONTH = 0)) LT INNER JOIN LEASINGMASTER LM ON LM.DOCNUMBER = LT.DOCNUMBER INNER JOIN PERIODCONTROL PC ON PC.COMPANY = LM.COMPANY AND PC.closeaCCOUNTINGPERIOD = LT.PERIOD_MONTH INNER JOIN COMPANY C ON C.ID = LM.COMPANY INNER JOIN MATERIAL M ON M.ID = LM.ITEM_CODE WHERE LM.COMPANY = '".$COMPANY."' UNION ALL SELECT LM.UUID, LM.COMPANY, LM.DOCNUMBER, LM.DUEDATE_PER_MONTH, LM.BASIC_AMOUNT, LM.AMOUNT_PER_MONTH, LM.AMOUNT_AFTER_CONV, LM.INTEREST_PERCENTAGE, LM.INTEREST_AMOUNT, LM.TOTAL_MONTH, TRANSACTIONMETHOD_BY, PC.CURRENTACCOUNTINGYEAR, PC.CURRENTACCOUNTINGPERIOD, C.COMPANYNAME, PC.CURRENTACCOUNTINGPERIOD, PC.CURRENTACCOUNTINGYEAR, M.FCNAME, 0 AS status FROM LEASINGMASTER LM INNER JOIN PERIODCONTROL PC ON PC.COMPANY = LM.COMPANY INNER JOIN COMPANY C ON C.ID = LM.COMPANY INNER JOIN MATERIAL M ON M.ID = LM.ITEM_CODE WHERE LM.COMPANY = '".$COMPANY."' AND TO_CHAR (LM.VALID_FROM, 'mm/yyyy') = TO_CHAR (PC.CURRENTDATE, 'mm/yyyy') UNION ALL SELECT LM.UUID, LM.COMPANY, LM.DOCNUMBER, LM.DUEDATE_PER_MONTH, LM.BASIC_AMOUNT, LM.AMOUNT_PER_MONTH, LM.AMOUNT_AFTER_CONV, LM.INTEREST_PERCENTAGE, LM.INTEREST_AMOUNT, LM.TOTAL_MONTH, TRANSACTIONMETHOD_BY, TO_NUMBER (LT.PERIOD_YEAR) AS PERIOD_YEAR, TO_NUMBER (LT.PERIOD_MONTH) AS PERIOD_MONTH, C.COMPANYNAME, PC.CURRENTACCOUNTINGPERIOD, PC.CURRENTACCOUNTINGYEAR, M.FCNAME, 1 AS status FROM (SELECT DOCNUMBER, PERIOD_YEAR, PERIOD_MONTH, STATUS FROM LEASINGTRANSACTION WHERE DOCNUMBER NOT IN (SELECT DOCNUMBER FROM LEASINGTRANSACTION WHERE REMAIN_MONTH = 0)) LT INNER JOIN LEASINGMASTER LM ON LM.DOCNUMBER = LT.DOCNUMBER INNER JOIN PERIODCONTROL PC ON PC.COMPANY = LM.COMPANY AND PC.currentaCCOUNTINGPERIOD = LT.PERIOD_MONTH INNER JOIN COMPANY C ON C.ID = LM.COMPANY INNER JOIN MATERIAL M ON M.ID = LM.ITEM_CODE WHERE LM.COMPANY = '".$COMPANY."') GROUP BY UUID, COMPANY, DOCNUMBER, DUEDATE_PER_MONTH, BASIC_AMOUNT, AMOUNT_PER_MONTH, AMOUNT_AFTER_CONV, INTEREST_PERCENTAGE, INTEREST_AMOUNT, TOTAL_MONTH, TRANSACTIONMETHOD_BY, COMPANYNAME, CURRENTACCOUNTINGPERIOD, CURRENTACCOUNTINGYEAR, FCNAME";

    // var_dump($q);exit();
        return $this->db->query($q)->result();
    }


    public function payAllLeasingTransaction($param, $Location) {
        // echo "<pre>";
        // var_dump($param);
        // exit;
        try {
            // echo count($param['DtLeasing']);exit();
            foreach ($param['DtLeasing'] AS $key => $row) {
                // $FLAG = isset($row["FLAG"]);
                // var_dump($row["FLAG"]);exit();
                if($row["STATUS"] == "0" && $row["FLAG"] == "1"){
                    $result = FALSE;
                    $q = "SELECT * FROM PERIODCONTROL WHERE COMPANY = '".$row['COMPANY']."' AND CURRENTACCOUNTINGYEAR = '".$param['YEAR']."' AND CURRENTACCOUNTINGPERIOD = '".$param['MONTH']."'";
                    $res = $this->db->query($q)->row();

                    // echo "<pre>";
                    // var_dump($this->db->last_query());exit();
                    if($res == NULL){
                        throw new Exception('Period ini belum closing.');
                    }
                    if($res->CURRENTACCOUNTINGPERIOD != $param['MONTH'] && $res->CURRENTACCOUNTINGYEAR != $param['YEAR']){
                        throw new Exception('Period is Changed, Reload First.');
                    }


                    $q2 = "SELECT * FROM LEASINGMASTER WHERE COMPANY = '".$row['COMPANY']."' AND DOCNUMBER = '".$row['DOCNUMBER']."' AND UUID = '".$row['UUID']."'";
                    $getDetails = $this->db->query($q2)->row();

                    if($getDetails->ISACTIVE == 'FALSE'){
                        throw new Exception('Leasing Tidak Aktif.');
                    }

                    $SQL = "SELECT * FROM LEASINGTRANSACTION WHERE DOCNUMBER = '".$row['DOCNUMBER']."' AND PERIOD_YEAR = '".$param['YEAR']."' AND PERIOD_MONTH = '".$param['MONTH']."'";
                    $Cek = $this->db->query($SQL)->row();

                    $q3 = "SELECT * FROM LEASINGCOMPLETION WHERE DOCNUMBER = '".$row['DOCNUMBER']."'";
                    $cekCompletion = $this->db->query($q3)->row();

                    $CURR = $getDetails->CURRENCY;
                    $q4 = "SELECT RATE FROM CURS WHERE CURSCODE = '$CURR' AND CURSYEAR = '".$param['YEAR']."' AND CURSMONTH = '".$param['MONTH']."'";
                    $cekCURR = $this->db->query($q4)->row();


                    if($CURR != 'IDR'){
                        $q4 = "SELECT RATE FROM CURS WHERE CURSCODE = '$CURR' AND CURSYEAR = '".$param['YEAR']."' AND CURSMONTH = '".$param['MONTH']."'";
                    
                        $cekCURR = $this->db->query($q4)->row();
                        if($cekCURR == NULL){
                            throw new Exception("Rate $CURR Periode ".$param['MONTH']." / ".$param['YEAR']." Tidak Ada");
                        }
                        else{
                            $cekCURR = $cekCURR->RATE;
                        }
                    }else{
                        $cekCURR = 1;
                    }
                    if($cekCompletion != NULL){
                        throw new Exception("Leasing '".$row['DOCNUMBER']."' Sudah Complete.");
                    }
                    else{
                        if ($Cek != NULL) {
                            throw new Exception($row['DOCNUMBER']. ' Data Already Paid !!');
                        }
                        if($Cek == NULL){
                            if($param['MONTH'] == 1 || $param['MONTH'] == '1'){
                                $parMonth = 12;    
                                $parYear  = $param['YEAR'] - 1;
                            }else{
                                $parMonth = $param['MONTH'] - 1;
                                $parYear  = $param['YEAR'];
                            }
                            
                            $SQL = "SELECT * FROM LEASINGTRANSACTION WHERE DOCNUMBER = '".$row['DOCNUMBER']."' AND PERIOD_YEAR = '".$parYear."' AND PERIOD_MONTH = '".$parMonth."'";
                            $Cek = $this->db->query($SQL)->row();
                            // var_dump($SQL);exit();    
                            
                            $DUEDATE    = $param['MONTH'].'/'.$getDetails->DUEDATE_PER_MONTH.'/'.$param['YEAR'];
                            $formatDD   = date('m/d/Y',strtotime("$DUEDATE"));
                            
                            // $getValidYear  = date('Y',strtotime($getDetails->VALID_UNTIL));
                            // $getValidMonth = date('m',strtotime($getDetails->VALID_UNTIL));
                            // if($getValidYear > $res->CURRENTACCOUNTINGYEAR && $getValidMonth > $res->CURRENTACCOUNTINGPERIOD){
                            //     throw new Exception('VALID UNTIL Lebih Besar dari Month di PERIODCONTROL !!');
                            // }
                            $AMOUNT_BEFORE_CONV  = $getDetails->AMOUNT_BEFORE_CONV;
                            $INTEREST_PERCENTAGE = $getDetails->INTEREST_PERCENTAGE;
                            $INTEREST_AMOUNT     = $getDetails->INTEREST_AMOUNT;
                            //cek jika month to be nya 1
                            if($Cek == NULL){
                                $MONTHTOBE = 1;
                                $REMAIN_MONTH = $getDetails->TOTAL_MONTH - 1;
                                $LINENO = 1;
                                if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                                    $INTEREST_AMOUNT_MONTHLY = $getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12;
                                    $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY;
                                }
                                if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                                    $BASIC_AMOUNT_MONTHLY    = $getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH;
                                    $INTEREST_AMOUNT_MONTHLY = $getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12;
                                    $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY;
                                }
                                
                            }else{
                                $MONTHTOBE = $Cek->MONTHTOBE + 1;
                                $REMAIN_MONTH = $Cek->REMAIN_MONTH - 1;
                                $LINENO = $Cek->LINENO + 1;
                                if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                                    $INTEREST_AMOUNT_MONTHLY = $Cek->REMAIN_BASIC_AMOUNT_LEASING*(($INTEREST_PERCENTAGE/100) / 12);
                                    $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY;
                                }
                                if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                                    $BASIC_AMOUNT_MONTHLY    = $getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH;

                                    $INTEREST_AMOUNT_MONTHLY = ((($getDetails->BASIC_AMOUNT-($MONTHTOBE-1)*$BASIC_AMOUNT_MONTHLY)*$INTEREST_PERCENTAGE/100)/12);
                                    
                                    $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY;
                                    
                                }
                            }


                            if($MONTHTOBE > $getDetails->TOTAL_MONTH){
                                throw new Exception('MONTHTOBE Lebih Besar dari TOTAL MONTH !!');
                            }
                            if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                                $AMOUNT_MONTHLY_LEASING  = ($getDetails->BASIC_AMOUNT * (($INTEREST_PERCENTAGE / 100 ) / 12) / (1-pow(1/(1+(($INTEREST_PERCENTAGE / 100) / 12)),$getDetails->TOTAL_MONTH)));
                                $AMOUNT_YEARLY_LEASING   = $AMOUNT_MONTHLY_LEASING * $MONTHTOBE;                    
                                
                                $BASIC_AMOUNT_MONTHLY    = $AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY;
                                $BASIC_AMOUNT_YEARLY     = $AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY;

                                $REMAIN_BASIC_AMOUNT_LEASING = $getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY;
                                $REMAIN_TOTAL_AMOUNT_LEASING = $REMAIN_MONTH * $AMOUNT_MONTHLY_LEASING;
                                $REMAIN_INTEREST_AMOUNT_LEASING = $REMAIN_TOTAL_AMOUNT_LEASING-$REMAIN_BASIC_AMOUNT_LEASING;
                            }
                            if($getDetails->TRANSACTIONMETHOD_BY == 'FLAT'){

                                $AMOUNT_MONTHLY_LEASING  = $getDetails->AMOUNT_BEFORE_CONV / $getDetails->TOTAL_MONTH;
                                $AMOUNT_YEARLY_LEASING   = $MONTHTOBE * $AMOUNT_MONTHLY_LEASING;

                                $BASIC_AMOUNT_MONTHLY    = $getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH;
                                $BASIC_AMOUNT_YEARLY     = $BASIC_AMOUNT_MONTHLY * $MONTHTOBE;

                                $INTEREST_AMOUNT_MONTHLY = $getDetails->INTEREST_AMOUNT / $getDetails->TOTAL_MONTH;
                                $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY * $MONTHTOBE;

                                $REMAIN_BASIC_AMOUNT_LEASING = $getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY;
                                $REMAIN_INTEREST_AMOUNT_LEASING = $getDetails->INTEREST_AMOUNT-$INTEREST_AMOUNT_YEARLY;
                                $REMAIN_TOTAL_AMOUNT_LEASING = $REMAIN_BASIC_AMOUNT_LEASING+$REMAIN_INTEREST_AMOUNT_LEASING;
                            }
                            if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                                
                                $AMOUNT_MONTHLY_LEASING  = $BASIC_AMOUNT_MONTHLY+$INTEREST_AMOUNT_MONTHLY;
                                
                                $BASIC_AMOUNT_YEARLY     = $BASIC_AMOUNT_MONTHLY * $MONTHTOBE;

                                $AMOUNT_YEARLY_LEASING   = $BASIC_AMOUNT_YEARLY+$INTEREST_AMOUNT_YEARLY;

                                $REMAIN_BASIC_AMOUNT_LEASING = $getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY;
                                $REMAIN_INTEREST_AMOUNT_LEASING = ($INTEREST_AMOUNT_MONTHLY*$REMAIN_MONTH)/2;
                                // $REMAIN_INTEREST_AMOUNT_LEASING = $REMAIN_BASIC_AMOUNT_LEASING*($INTEREST_PERCENTAGE / 100) / 12;
                                $REMAIN_TOTAL_AMOUNT_LEASING = $REMAIN_BASIC_AMOUNT_LEASING + $REMAIN_INTEREST_AMOUNT_LEASING;
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

                            if($MONTHTOBE == 1){
                                $BASIC_AMOUNT_MONTHLY_CONV      = $BASIC_AMOUNT_MONTHLY * $getDetails->RATE;
                                $INTEREST_AMOUNT_MONTHLY_CONV   = $INTEREST_AMOUNT_MONTHLY * $getDetails->RATE;
                                $AMOUNT_MONTHLY_LEASING_CONV    = $AMOUNT_MONTHLY_LEASING * $getDetails->RATE;
                            }else{
                                $BASIC_AMOUNT_MONTHLY_CONV      = $BASIC_AMOUNT_MONTHLY * $cekCURR;
                                $INTEREST_AMOUNT_MONTHLY_CONV   = $INTEREST_AMOUNT_MONTHLY * $cekCURR;
                                $AMOUNT_MONTHLY_LEASING_CONV    = $AMOUNT_MONTHLY_LEASING * $cekCURR;
                            }

                            $Data["ID"] = $this->uuid->v4();

                            $dt = [
                                'GID'      => $Data['ID'],
                                'DOCNUMBER' => $row['DOCNUMBER'],
                                'PERIOD_YEAR' => $param['YEAR'],
                                'PERIOD_MONTH' => $param['MONTH'],
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
                                'FCENTRY' => $param['USERNAME'],
                                'FCIP' => $Location
                            ];
                            // echo "<pre>";
                            // print_r($dt);exit();

                            $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                        ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false);
                            // var_dump($result1);exit();
                            $this->db->trans_begin();
                            $result1 = $result1->set($dt)->insert('LEASINGTRANSACTION');
                            
                            if ($result1) {
                                //insert cf transaction
                                // $result1 = true;
                                $cf = [
                                    "DEPARTMENT" => $getDetails->DEPARTMENT,
                                    "COMPANY" => $getDetails->COMPANY,
                                    "BUSINESSUNIT" => $getDetails->BUSINESSUNIT,
                                    "DOCNUMBER" => $getDetails->DOCNUMBER,
                                    "DOCTYPE" => "LEASING",
                                    "VENDOR" => $getDetails->VENDOR,
                                    "CURRENCY" => $getDetails->CURRENCY,
                                    "EXTSYS" => $getDetails->EXTSYS,
                                    "VAT" => "",
                                    "RATE" => $getDetails->RATE,
                                    "REMARK" => "",
                                    "AMOUNT_INCLUDE_VAT" => $AMOUNT_MONTHLY_LEASING,
                                    "TOTAL_BAYAR" => $getDetails->RATE * $AMOUNT_MONTHLY_LEASING,
                                    "AMOUNT_PPH" => 0,
                                    "FCEDIT" => $param['USERNAME'],
                                    "FCIP" => $Location
                                ];   

                                $resultcf = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                ->set('DOCDATE', "TO_DATE('" .$formatDD. "','mm-dd-yyyy')", false)
                                ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                                ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                                // $Data["ID"] = $this->uuid->v4();
                                $cf["ID"]   = $Data["ID"];
                                $cf["ISACTIVE"] = "TRUE";
                                $cf["FCENTRY"] = $param['USERNAME'];
                                $resultcf = $resultcf->set($cf)->insert($this->CF_TRANSACTION);
                                if($resultcf){
                                    $cf_det = [
                                        "ID" => $Data["ID"],
                                        'MATERIAL' => $getDetails->ITEM_CODE,
                                        'REMARKS' => '',
                                        'AMOUNT_INCLUDE_VAT' => $cf['AMOUNT_INCLUDE_VAT'],
                                        'AMOUNT_PPH' => 0,
                                        "ISACTIVE" => "TRUE",
                                        "FCENTRY" => $param["USERNAME"],
                                        "FCEDIT" => $param["USERNAME"],
                                        "FCIP" => $Location
                                    ];
                                    $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                                    ->set($cf_det)->insert($this->CF_TRANSACTION_DET);
                                    // end
                                    //get group
                                    $qGETS = $this->db->get_where('COMPANY',array('ID' => $getDetails->COMPANY));
                                    $qGETS = $qGETS->row();
                                    if($qGETS->COMPANY_SUBGROUP == "UPSTREAM" || $qGETS->COMPANY_SUBGROUP == "DOWNSTREAM"){
                                        $GRUP = "PLT";
                                    }
                                    else if($qGETS->COMPANY_SUBGROUP == "CEMENT"){
                                        $GRUP = "CMT";
                                    }
                                    else{
                                        $GRUP = "PROPERTY";
                                    }

                                    // start insert forecast fix
                                    $strDate = $formatDD;
                                    $dateArray = explode("/", $strDate);
                                    $date = new DateTime();
                                    $date->setDate($dateArray[2], $dateArray[0], $dateArray[1]);
                                    $getWeek = floor((date_format($date, 'j') - 1) / 7) + 1; 

                                    if($result2){
                                        // $result2 = true;
                                        $forecast = array(
                                            'DEPARTMENT' => $getDetails->DEPARTMENT,
                                            'CFTRANSID'  => $Data["ID"],
                                            'YEAR'       => $param['YEAR'],
                                            'MONTH'      => $param['MONTH'],
                                            'WEEK'       => 'W'.$getWeek,
                                            'AMOUNTREQUEST' => $cf['AMOUNT_INCLUDE_VAT'],
                                            'AMOUNTADJS' => 0,
                                            'ISACTIVE'   => 1,
                                            'FCENTRY' => $param["USERNAME"],
                                            'FCEDIT' => $param["USERNAME"],
                                            "FCIP" => $Location,
                                            "PRIORITY" => 1,
                                            "LOCKS" => 1,
                                            "STATE" => 0,
                                            "INVOICEVENDORNO" => "",
                                            "COMPANYGROUP" => $GRUP,
                                            "COMPANYSUBGROUP" => $qGETS->COMPANY_SUBGROUP
                                        );
                                        $result3 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                                    ->set($forecast)->insert($this->FORECAST_FIX);
                                        // $result3 = true;
                                    }
                                }else{
                                    $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
                                }
                                // end cf
                                // $resultcf = true;
                                // start cf details
                                
                            }
                            else {
                                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
                            }
                            if($result1 && $resultcf && $result2 && $result3 ){
                                $result = TRUE;
                            }
                            if ($result) {
                                $this->db->trans_commit();
                                $return = [
                                    'STATUS' => TRUE,
                                    'MESSAGE' => 'Data has been Successfully Saved !!'
                                ];
                            } else {
                                $this->db->trans_rollback();
                                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
                            }
                        }
                    }//end else
                }// end if flag
                else{

                }
            }//end foreach
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



    public function saveLeasingTransaction($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $q = "SELECT * FROM PERIODCONTROL WHERE COMPANY = '".$param['COMPANY']."' AND CURRENTACCOUNTINGYEAR = '".$param['YEAR']."' AND CURRENTACCOUNTINGPERIOD = '".$param['MONTH']."'";
            $res = $this->db->query($q)->row();
            
            $SQL = "SELECT * FROM LEASINGTRANSACTION WHERE DOCNUMBER = '".$param['DOCNUMBER']."' AND PERIOD_YEAR = '".$param['YEAR']."' AND PERIOD_MONTH = '".$param['MONTH']."'";
            $Cek = $this->db->query($SQL)->row();

            $q2 = "SELECT * FROM LEASINGMASTER WHERE COMPANY = '".$param['COMPANY']."' AND DOCNUMBER = '".$param['DOCNUMBER']."' AND UUID = '".$param['UUID']."'";
            $getDetails = $this->db->query($q2)->row();
            
            $q3 = "SELECT * FROM LEASINGCOMPLETION WHERE DOCNUMBER = '".$param['DOCNUMBER']."'";
            $cekCompletion = $this->db->query($q3)->row();

            $CURR = $getDetails->CURRENCY;

            if($CURR != 'IDR'){
                $q4 = "SELECT RATE FROM CURS WHERE CURSCODE = '$CURR' AND CURSYEAR = '".$param['YEAR']."' AND CURSMONTH = '".$param['MONTH']."'";
            
                $cekCURR = $this->db->query($q4)->row();
                if($cekCURR == NULL){
                    throw new Exception("Rate $CURR Periode ".$param['MONTH']." / ".$param['YEAR']." Tidak Ada");
                }
                else{
                    $cekCURR = $cekCURR->RATE;
                }
            }else{
                $cekCURR = 1;
            }
            


            
            if($res == NULL){
                throw new Exception('Period ini belum closing.');
            }
            if($res->CURRENTACCOUNTINGPERIOD != $param['MONTH'] && $res->CURRENTACCOUNTINGYEAR != $param['YEAR']){
                        throw new Exception('Period is Changed, Reload First.');
                    }
            if($getDetails->ISACTIVE == 'FALSE'){
	            throw new Exception('Leasing Tidak Aktif.');
	        }
            if($cekCompletion != NULL){
                throw new Exception("Leasing '".$param['DOCNUMBER']."' Sudah Complete.");
            }
            else{
                if ($Cek != NULL) {
                    throw new Exception('Data Already Paid !!');
                }
                if($Cek == NULL){
                    if($param['MONTH'] == 1 || $param['MONTH'] == '1'){
                        $parMonth = 12;    
                        $parYear  = $param['YEAR'] - 1;
                    }else{
                        $parMonth = $param['MONTH'] - 1;
                        $parYear  = $param['YEAR'];
                    }
                    
                    $SQL = "SELECT * FROM LEASINGTRANSACTION WHERE DOCNUMBER = '".$param['DOCNUMBER']."' AND PERIOD_YEAR = '".$parYear."' AND PERIOD_MONTH = '".$parMonth."'";
                    $Cek = $this->db->query($SQL)->row();
                    // var_dump($SQL);exit();    
                    
                    $DUEDATE    = $param['MONTH'].'/'.$getDetails->DUEDATE_PER_MONTH.'/'.$param['YEAR'];
                    $formatDD   = date('m/d/Y',strtotime("$DUEDATE"));
                    // $getValidYear  = date('Y',strtotime($getDetails->VALID_UNTIL));
                    // $getValidMonth = date('m',strtotime($getDetails->VALID_UNTIL));
                    // if($getValidYear > $res->CURRENTACCOUNTINGYEAR && $getValidMonth > $res->CURRENTACCOUNTINGPERIOD){
                    //     throw new Exception('VALID UNTIL Lebih Besar dari Month di PERIODCONTROL !!');
                    // }
                    $INTEREST_PERCENTAGE = $getDetails->INTEREST_PERCENTAGE;
                    $INTEREST_AMOUNT     = $getDetails->INTEREST_AMOUNT;
                    $AMOUNT_BEFORE_CONV  = $getDetails->AMOUNT_BEFORE_CONV;

                    if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                        $AMOUNT_MONTHLY_LEASING  = round(($getDetails->BASIC_AMOUNT * (($INTEREST_PERCENTAGE / 100 ) / 12) / (1-pow(1/(1+(($INTEREST_PERCENTAGE / 100) / 12)),($getDetails->TOTAL_MONTH-1)))));
                        // $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                    }
                    // echo "<pre>";
                    // var_dump($getDetails);exit;
                    // var_dump($AMOUNT_MONTHLY_LEASING.'-'.$AMOUNT_YEARLY_LEASING);exit;

                    //cek jika month to be nya 1
                    if($Cek == NULL){
                        $MONTHTOBE = 1;
                        $REMAIN_MONTH = $getDetails->TOTAL_MONTH - 1;
                        $LINENO = 1;
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                            $INTEREST_AMOUNT_MONTHLY = round($getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12);
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY);
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                            $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                            // $INTEREST_AMOUNT_MONTHLY = $getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12;
                            $INTEREST_AMOUNT_MONTHLY = 0;
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY);
                            
                            $BASIC_AMOUNT_MONTHLY    = round($AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY);
                            $BASIC_AMOUNT_YEARLY     = round($AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY);
                            $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT);
                            // $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY);
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                            $BASIC_AMOUNT_MONTHLY    = round($getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH);
                            $INTEREST_AMOUNT_MONTHLY = round($getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12);
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY);
                        }
                        
                    }else{
                        // echo "<pre>";
                        // var_dump($Cek->REMAIN_BASIC_AMOUNT_LEASING);exit;
                        $MONTHTOBE  = (int)$Cek->MONTHTOBE + 1;
                        $REMAIN_MONTH = $Cek->REMAIN_MONTH - 1;
                        $LINENO = $Cek->LINENO + 1;
                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                            $INTEREST_AMOUNT_MONTHLY = round($Cek->REMAIN_BASIC_AMOUNT_LEASING*(($INTEREST_PERCENTAGE/100) / 12));
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY);
                        }

                        if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                            $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                            // var_dump($this->db->last_query());exit;
                            $INTEREST_AMOUNT_MONTHLY = round((($INTEREST_PERCENTAGE/100)*$Cek->REMAIN_BASIC_AMOUNT_LEASING/12));
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY);
                            $BASIC_AMOUNT_MONTHLY    = round($AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY);
                            $BASIC_AMOUNT_YEARLY     = round($AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY);

                            $REMAIN_BASIC_AMOUNT_LEASING = round($Cek->REMAIN_BASIC_AMOUNT_LEASING - $BASIC_AMOUNT_MONTHLY);
                        }
                        if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                            $BASIC_AMOUNT_MONTHLY    = round($getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH);

                            $INTEREST_AMOUNT_MONTHLY = round(((($getDetails->BASIC_AMOUNT-($MONTHTOBE-1)*$BASIC_AMOUNT_MONTHLY)*$INTEREST_PERCENTAGE/100)/12));
                            
                            $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY);
                            
                        }
                    }
                    // if($Cek == NULL){
                    //     $MONTHTOBE = 1;
                    //     $REMAIN_MONTH = $getDetails->TOTAL_MONTH - 1;
                    //     $LINENO = 1;
                    //     if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                    //         $INTEREST_AMOUNT_MONTHLY = $getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12;
                    //         $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY;
                    //     }
                    //     if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                    //         $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                    //         // $INTEREST_AMOUNT_MONTHLY = $getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12;
                    //         $INTEREST_AMOUNT_MONTHLY = 0;
                    //         $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY);
                            
                    //         $BASIC_AMOUNT_MONTHLY    = round($AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY);
                    //         $BASIC_AMOUNT_YEARLY     = round($AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY);
                    //         $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT);
                    //         // $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY);
                    //     }
                    //     if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                    //         $BASIC_AMOUNT_MONTHLY    = $getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH;
                    //         $INTEREST_AMOUNT_MONTHLY = $getDetails->BASIC_AMOUNT*($INTEREST_PERCENTAGE/100)/12;
                    //         $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY;
                    //     }
                        
                    // }else{
                    //     $MONTHTOBE  = $Cek->MONTHTOBE + 1;
                    //     $REMAIN_MONTH = $Cek->REMAIN_MONTH - 1;
                    //     $LINENO = $Cek->LINENO + 1;
                    //     if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS_SC'){
                    //         $INTEREST_AMOUNT_MONTHLY = $Cek->REMAIN_BASIC_AMOUNT_LEASING*(($INTEREST_PERCENTAGE/100) / 12);
                    //         $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY;
                    //     }

                    //     if($getDetails->TRANSACTIONMETHOD_BY == 'ANUITAS'){
                    //         $AMOUNT_YEARLY_LEASING   = round($AMOUNT_MONTHLY_LEASING * $MONTHTOBE);   
                    //         $INTEREST_AMOUNT_MONTHLY = round((($INTEREST_PERCENTAGE/100)*$Cek->REMAIN_BASIC_AMOUNT_LEASING/12));
                    //         $INTEREST_AMOUNT_YEARLY  = round($INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY);
                    //         $BASIC_AMOUNT_MONTHLY    = round($AMOUNT_MONTHLY_LEASING - $INTEREST_AMOUNT_MONTHLY);
                    //         $BASIC_AMOUNT_YEARLY     = round($AMOUNT_YEARLY_LEASING - $INTEREST_AMOUNT_YEARLY);

                    //         $REMAIN_BASIC_AMOUNT_LEASING = round($getDetails->BASIC_AMOUNT - $BASIC_AMOUNT_YEARLY);
                    //     }
                    //     if($getDetails->TRANSACTIONMETHOD_BY == 'EFEKTIF'){
                    //         $BASIC_AMOUNT_MONTHLY    = $getDetails->BASIC_AMOUNT / $getDetails->TOTAL_MONTH;

                    //         $INTEREST_AMOUNT_MONTHLY = ((($getDetails->BASIC_AMOUNT-($MONTHTOBE-1)*$BASIC_AMOUNT_MONTHLY)*$INTEREST_PERCENTAGE/100)/12);
                            
                    //         $INTEREST_AMOUNT_YEARLY  = $INTEREST_AMOUNT_MONTHLY+$Cek->INTEREST_AMOUNT_YEARLY;
                            
                    //     }
                    // }


                    if($MONTHTOBE > $getDetails->TOTAL_MONTH){
                        throw new Exception('MONTHTOBE Lebih Besar dari TOTAL MONTH !!');
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

                    if($MONTHTOBE == 1){
                        $BASIC_AMOUNT_MONTHLY_CONV      = round($BASIC_AMOUNT_MONTHLY * $getDetails->RATE);
                        $INTEREST_AMOUNT_MONTHLY_CONV   = round($INTEREST_AMOUNT_MONTHLY * $getDetails->RATE);
                        $AMOUNT_MONTHLY_LEASING_CONV    = round($AMOUNT_MONTHLY_LEASING * $getDetails->RATE);
                    }else{
                        $BASIC_AMOUNT_MONTHLY_CONV      = round($BASIC_AMOUNT_MONTHLY * $cekCURR);
                        $INTEREST_AMOUNT_MONTHLY_CONV   = round($INTEREST_AMOUNT_MONTHLY * $cekCURR);
                        $AMOUNT_MONTHLY_LEASING_CONV    = round($AMOUNT_MONTHLY_LEASING * $cekCURR);
                    }


                    $Data["ID"] = $this->uuid->v4();

                    $dt = [
                        'GID'      => $Data['ID'],
                        'DOCNUMBER' => $param['DOCNUMBER'],
                        'PERIOD_YEAR' => $param['YEAR'],
                        'PERIOD_MONTH' => $param['MONTH'],
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
                        'FCENTRY' => $param['USERNAME'],
                        'FCIP' => $Location
                    ];
                    // echo "<pre>";
                    // print_r($dt);exit();

                    $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false);
                    // var_dump($result1);exit();
                    
                    $result1 = $result1->set($dt)->insert('LEASINGTRANSACTION');
                    
                    if ($result1) {
                        //insert cf transaction
                        $cf = [
                            "DEPARTMENT" => $getDetails->DEPARTMENT,
                            "COMPANY" => $getDetails->COMPANY,
                            "BUSINESSUNIT" => $getDetails->BUSINESSUNIT,
                            "DOCNUMBER" => $getDetails->DOCNUMBER,
                            "DOCTYPE" => "LEASING",
                            "VENDOR" => $getDetails->VENDOR,
                            "CURRENCY" => $getDetails->CURRENCY,
                            "EXTSYS" => $getDetails->EXTSYS,
                            "VAT" => "",
                            "RATE" => $getDetails->RATE,
                            "REMARK" => "",
                            "AMOUNT_INCLUDE_VAT" => $AMOUNT_MONTHLY_LEASING,
                            "TOTAL_BAYAR" => $getDetails->RATE * $AMOUNT_MONTHLY_LEASING,
                            "AMOUNT_PPH" => 0,
                            "FCEDIT" => $param['USERNAME'],
                            "FCIP" => $Location
                        ];   

                        $resultcf = $this->db->set('LASTUPDATE', "SYSDATE", false)
                        ->set('DOCDATE', "TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                        ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        
                        $cf["ID"]   = $Data["ID"];
                        $cf["ISACTIVE"] = "TRUE";
                        $cf["FCENTRY"] = $param['USERNAME'];
                        $resultcf = $resultcf->set($cf)->insert($this->CF_TRANSACTION);

                        if($resultcf){
                            
                            // start cf details
                            $cf_det = [
                                "ID" => $Data["ID"],
                                'MATERIAL' => $getDetails->ITEM_CODE,
                                'REMARKS' => '',
                                'AMOUNT_INCLUDE_VAT' => $cf['AMOUNT_INCLUDE_VAT'],
                                'AMOUNT_PPH' => 0,
                                "ISACTIVE" => "TRUE",
                                "FCENTRY" => $param["USERNAME"],
                                "FCEDIT" => $param["USERNAME"],
                                "FCIP" => $Location
                            ];
                            $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                            ->set($cf_det)->insert($this->CF_TRANSACTION_DET);

                            $qGETS = $this->db->get_where('COMPANY',array('ID' => $getDetails->COMPANY));
                            $qGETS = $qGETS->row();
                            if($qGETS->COMPANY_SUBGROUP == "UPSTREAM" || $qGETS->COMPANY_SUBGROUP == "DOWNSTREAM"){
                                $GRUP = "PLT";
                            }
                            else if($qGETS->COMPANY_SUBGROUP == "CEMENT"){
                                $GRUP = "CMT";
                            }
                            else{
                                $GRUP = "PROPERTY";
                            }
                            // start insert forecast fix
                            $strDate = $formatDD;
                            $dateArray = explode("/", $strDate);
                            $date = new DateTime();
                            $date->setDate($dateArray[2], $dateArray[0], $dateArray[1]);
                            $getWeek = floor((date_format($date, 'j') - 1) / 7) + 1; 
                            
                            if($result2){
                                
                                $forecast = array(
                                    'DEPARTMENT' => $getDetails->DEPARTMENT,
                                    'CFTRANSID'  => $Data["ID"],
                                    'YEAR'       => $param['YEAR'],
                                    'MONTH'      => $param['MONTH'],
                                    'WEEK'       => 'W'.$getWeek,
                                    'AMOUNTREQUEST' => $cf['AMOUNT_INCLUDE_VAT'],
                                    'AMOUNTADJS' => 0,
                                    'ISACTIVE'   => 1,
                                    'FCENTRY' => $param["USERNAME"],
                                    'FCEDIT' => $param["USERNAME"],
                                    "FCIP" => $Location,
                                    "PRIORITY" => 1,
                                    "LOCKS" => 1,
                                    "STATE" => 0,
                                    "INVOICEVENDORNO" => "",
                                    "COMPANYGROUP" => $GRUP,
                                    "COMPANYSUBGROUP" => $qGETS->COMPANY_SUBGROUP
                                );
                                $result3 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                            ->set($forecast)->insert($this->FORECAST_FIX);
                                if($result3){
                                    $result3 = true;
                                }else{
                                     $this->db->trans_rollback();
                                    throw new Exception('Data Save Forecast Failed !!');
                                }
                            }
                            // end
                        }else{
                            $this->db->trans_rollback();
                            throw new Exception('Data Save Transaction Failed !!');
                        }
                        // end cf
                        
                    }
                    else {
                        $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
                    }
                    if($result1 && $resultcf && $result2 && $result3){
                        // $setTask = array(
                        //     'CREATED_BY' => $param["USERNAME"],
                        //     'AMOUNT'     => $cf['AMOUNT_INCLUDE_VAT'],
                        //     'COMPANY'    => $qGETS->COMPANYCODE,
                        //     'DOCNUMBER'  => $cf['DOCNUMBER'],
                        //     'VENDOR'     => $this->db->get_where('SUPPLIER',['ID'=>$getDetails->VENDOR])->row()->FCNAME
                        // );
                        // ini_set('display_errors', 'On');
                        // $getDue   = $this->getDayWork($cf['DOCNUMBER']);
                        // $getBulan = date("m",strtotime($getDue));
                        // $getTahun = date("Y",strtotime($getDue));
                        // $insertTask = $this->db->set('CREATED_AT', "SYSDATE", false)
                        //                     ->set("EMAILDATE","TO_DATE('".$getDue."','mm-dd-yyyy')", false)
                        //                     ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                        //                     ->set($setTask)->insert("TASK_SCHEDULER");

                        // $this->db->set('TIPE','1');
                        // $insertDue = $this->db->set('CREATED_AT', "SYSDATE", false)
                        //                     ->set("DUEDATE","TO_DATE('".$formatDD."','mm-dd-yyyy')", false)
                        //                     ->set("EMAILDATE","TO_DATE('".$getBulan.'/'.'25/'.$getTahun."','mm-dd-yyyy')", false)
                        //                     ->set($setTask)->insert("TASK_SCHEDULER");
                        
                        $result = true;
                    }else{
                        $this->db->trans_rollback();
                        throw new Exception('Data Save Failed !!');
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

    public function ShowDataCompletion($param){
        // $DOCDATE   = $param['DOCDATE'];
        // $DOCNUMBER = $param['DOCNUMBER'];
        $COMPANY      = $param['COMPANY'];
        
        $q = "SELECT LT.ID, LT.DOCNUMBER,M.FCNAME,LT.PERIOD_YEAR,LT.PERIOD_MONTH, LT.LINENO, LT.AMOUNT_YEARLY_LEASING,LT.REMAIN_BASIC_AMOUNT_LEASING, REMAIN_TOTAL_AMOUNT_LEASING AS AMOUNT_COMPLETION, L.DOCDATE, L.COMPANY,L.PENALTY_PERCENTAGE,L.UUID,L.TRANSACTIONMETHOD_BY, C.COMPANYNAME, L.BASIC_AMOUNT,S.FCNAME as VENDORNAME FROM ( SELECT ID, PERIOD_YEAR, PERIOD_MONTH, DOCNUMBER, LINENO,  AMOUNT_YEARLY_LEASING,REMAIN_BASIC_AMOUNT_LEASING, REMAIN_TOTAL_AMOUNT_LEASING FROM LEASINGTRANSACTION WHERE (DOCNUMBER,LINENO) IN (SELECT DOCNUMBER, MAX(LINENO) FROM LEASINGTRANSACTION GROUP BY DOCNUMBER) GROUP BY ID, DOCNUMBER, PERIOD_YEAR,PERIOD_MONTH,LINENO, AMOUNT_YEARLY_LEASING,REMAIN_BASIC_AMOUNT_LEASING,REMAIN_TOTAL_AMOUNT_LEASING ) LT INNER JOIN LEASINGMASTER L ON L.DOCNUMBER = LT.DOCNUMBER INNER JOIN MATERIAL M ON M.ID = L.ITEM_CODE INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.COMPANY = '$COMPANY' AND L.DOCNUMBER NOT IN (SELECT DOCNUMBER FROM LEASINGCOMPLETION) GROUP BY LT.ID, LT.DOCNUMBER, M.FCNAME, LT.PERIOD_YEAR,LT.PERIOD_MONTH, LT.LINENO, LT.REMAIN_BASIC_AMOUNT_LEASING,LT.REMAIN_TOTAL_AMOUNT_LEASING, LT.AMOUNT_YEARLY_LEASING, L.DOCDATE, L.COMPANY,L.PENALTY_PERCENTAGE,L.UUID,L.TRANSACTIONMETHOD_BY, C.COMPANYNAME,L.BASIC_AMOUNT,S.FCNAME";

        // var_dump($q);
        $result = $this->db->query($q)->result();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    public function saveLeasingCompletion($param, $Location) {
        // echo "<pre>";
        // var_dump($param);exit;
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
                $SQL = "SELECT * FROM LEASINGCOMPLETION WHERE DOCNUMBER = '".$param['DOCNUMBER']."'";
                $Cek = $this->db->query($SQL)->row();

                if ($Cek != null) {
                    throw new Exception('Data Already Paid !!');
                }else{
                	$TIPE = $param['TIPE'];
                	if($TIPE == "1"){
	                    $q = "SELECT LM.* FROM LEASINGMASTER lm WHERE lm.DOCNUMBER = '".$param['DOCNUMBER']."'";

	                    $getDetMaster = $this->db->query($q)->row();

	                    $q2 = "SELECT * FROM LEASINGTRANSACTION WHERE DOCNUMBER = '".$param['DOCNUMBER']."' AND ID = '".$param['ID']."' AND LINENO = '".$param['LINENO']."' AND PERIOD_YEAR = '".$param['YEAR']."' AND PERIOD_MONTH = '".$param['MONTH']."'";

	                    $getDetails = $this->db->query($q2)->row();

                        $qgetDenda = "SELECT DENDA FROM DENDA_LEASING WHERE DOCNUMBER = '".$param['DOCNUMBER']."'";
                        $getDenda = $this->db->query($qgetDenda)->row()->DENDA;

                        // var_dump($this->db->last_query());exit;
	                    // $DUEDATE    = $getDetails->DUEDATE_PER_MONTH;
	                    // if($param['CBTN'] == 2){
                            $AMOUNT_WITH_PENALTY = intval(preg_replace("/[^\d\.\-]/","",$param['AMOUNT_WITH_PENALTY'])+$getDetails->REMAIN_BASIC_AMOUNT_LEASING+$getDenda);
                        // }else{
                            // $AMOUNT_COMPLETION = round($getDetails->REMAIN_BASIC_AMOUNT_LEASING);
                        // }
	                    $AMOUNT_COMPLETION    = $getDetails->AMOUNT_AFTER_CONV - $getDetails->AMOUNT_YEARLY_LEASING;

	                    $dt = [
	                        'DOCNUMBER' => $getDetails->DOCNUMBER,
	                        'MAX_LINENO' => $param['LINENO'],
	                        'AMOUNT_AFTER_CONV' => $getDetails->AMOUNT_AFTER_CONV,
	                        'AMOUNT_YEARLY_LEASING' => $getDetails->AMOUNT_YEARLY_LEASING,
	                        'AMOUNT_COMPLETION' => $AMOUNT_COMPLETION,
                            'AMOUNT_WITH_PENALTY' => $AMOUNT_WITH_PENALTY,
	                        'FCENTRY' => $param['USERNAME'],
	                        'FCIP' => $Location
	                    ];

                        // $plusdate = $getDetMaster->DOCDATE;
                        // $tomorrow = date('m-d-Y',strtotime($plusdate . "+1 days"));

	                    $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)
	                                ->set('DOCDATE', "TO_DATE('" . $param['COMDATE'] . "','MM/DD/YYYY')", false);
	                    $result1 = $result1->set($dt)->insert('LEASINGCOMPLETION');

                        $time   = new DateTime($param['COMDATE']);
                        $time   = $time->format('m').$time->format('Y');

                        $cekTrans = "SELECT COMPANY, DOCNUMBER, DOCDATE FROM CF_TRANSACTION WHERE COMPANY = '".$param['COMPANY']."' AND DOCNUMBER = '".$param['DOCNUMBER']."' AND TO_CHAR(DOCDATE,'MMYYYY') = '$time'";
                        $cekTrans = $this->db->query($cekTrans);
	                    // var_dump($this->db->last_query());exit;
                        if($cekTrans->num_rows() > 0){
                            // var_dump('1');exit;
                            if ($result1) {
                                //insert cf transaction
                                $cf = [
                                    "DEPARTMENT" => $getDetMaster->DEPARTMENT,
                                    "COMPANY" => $getDetMaster->COMPANY,
                                    "BUSINESSUNIT" => $getDetMaster->BUSINESSUNIT,
                                    "DOCNUMBER" => $getDetMaster->DOCNUMBER.'-COMP_NOFORECAST',
                                    "DOCTYPE" => "LEASING",
                                    "VENDOR" => $getDetMaster->VENDOR,
                                    "CURRENCY" => $getDetMaster->CURRENCY,
                                    "EXTSYS" => $getDetMaster->EXTSYS,
                                    "VAT" => "",
                                    "REMARK" => "LEASING COMPLETION",
                                    "AMOUNT_INCLUDE_VAT" => $AMOUNT_WITH_PENALTY,
                                    "TOTAL_BAYAR" => $AMOUNT_WITH_PENALTY,
                                    "AMOUNT_PPH" => 0,
                                    "RATE" => $getDetMaster->RATE,
                                    "FCEDIT" => $param['USERNAME'],
                                    "FCIP" => $Location
                                ];   

                                $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                ->set('DOCDATE', "TO_DATE('" . $param['COMDATE'] . "','MM/DD/YYYY')", false)
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
                                    "ID" => $Data["ID"],
                                    "MATERIAL" => $getDetMaster->ITEM_CODE,
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
                                $result3 = $result2;

                                if($result2){
                                    $up = "UPDATE LEASINGMASTER SET VALID_UNTIL = TO_DATE('" . $param['COMDATE'] . "','MM/DD/YYYY') WHERE DOCNUMBER = '".$getDetails->DOCNUMBER."'";
                                    $this->db->query($up);
                                }

                            }
                            else {
                                $this->db->trans_rollback(); 
                                throw new Exception('Data Save Failed !!');
                            }
                        }else{
                            // var_dump('2');exit;
                            if ($result1) {
                                //insert cf transaction
                                $cf = [
                                    "DEPARTMENT" => $getDetMaster->DEPARTMENT,
                                    "COMPANY" => $getDetMaster->COMPANY,
                                    "BUSINESSUNIT" => $getDetMaster->BUSINESSUNIT,
                                    "DOCNUMBER" => $getDetMaster->DOCNUMBER,
                                    "DOCTYPE" => "LEASING",
                                    "VENDOR" => $getDetMaster->VENDOR,
                                    "CURRENCY" => $getDetMaster->CURRENCY,
                                    "EXTSYS" => $getDetMaster->EXTSYS,
                                    "VAT" => "",
                                    "REMARK" => "LEASING COMPLETION",
                                    "AMOUNT_INCLUDE_VAT" => $AMOUNT_WITH_PENALTY,
                                    "TOTAL_BAYAR" => $AMOUNT_WITH_PENALTY,
                                    "AMOUNT_PPH" => 0,
                                    "RATE" => $getDetMaster->RATE,
                                    "FCEDIT" => $param['USERNAME'],
                                    "FCIP" => $Location
                                ];   

                                $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                ->set('DOCDATE', "TO_DATE('" . $param['COMDATE'] . "','MM/DD/YYYY')", false)
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
                                    "ID" => $Data["ID"],
                                    "MATERIAL" => $getDetMaster->ITEM_CODE,
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
                                //g
                                $qGETS = $this->db->get_where('COMPANY',array('ID' => $getDetMaster->COMPANY));
                                $qGETS = $qGETS->row();
                                if($qGETS->COMPANY_SUBGROUP == "UPSTREAM" || $qGETS->COMPANY_SUBGROUP == "DOWNSTREAM"){
                                    $GRUP = "PLT";
                                }
                                else if($qGETS->COMPANY_SUBGROUP == "CEMENT"){
                                    $GRUP = "CMT";
                                }
                                else{
                                    $GRUP = "PROPERTY";
                                }
                                // start insert forecast fix
                                $getDate = $tomorrow;
                                // var_dump($getDate);exit();
                                $dateArray = explode("-", $getDate);
                                $date = new DateTime();
                                $date->setDate($dateArray[2], $dateArray[0], $dateArray[1]);
                                $getWeek = floor((date_format($date, 'j') - 1) / 7) + 1;  
                                // var_dump($date);exit();
                                if($result2){
                                    $forecast = array(
                                        'DEPARTMENT' => $getDetMaster->DEPARTMENT,
                                        'CFTRANSID'  => $Data["ID"],
                                        'YEAR'       => $param['YEAR'],
                                        'MONTH'      => $param['MONTH'],
                                        'WEEK'       => 'W'.$getWeek,
                                        'AMOUNTREQUEST' => $AMOUNT_WITH_PENALTY,
                                        'AMOUNTADJS' => 0,
                                        'ISACTIVE'   => 1,
                                        'FCENTRY' => $param["USERNAME"],
                                        'FCEDIT' => $param["USERNAME"],
                                        "FCIP" => $Location,
                                        "PRIORITY" => 1,
                                        "LOCKS" => 1,
                                        "STATE" => 0,
                                        "INVOICEVENDORNO" => "",
                                        "COMPANYGROUP" => $GRUP,
                                        "COMPANYSUBGROUP" => $qGETS->COMPANY_SUBGROUP
                                    );
                                    $result3 = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                                ->set($forecast)->insert($this->FORECAST_FIX);
                                }

                                if($result3){
                                    $up = "UPDATE LEASINGMASTER SET VALID_UNTIL = TO_DATE('" . $param['COMDATE'] . "','MM/DD/YYYY') WHERE DOCNUMBER = '".$getDetails->DOCNUMBER."'";
                                    $this->db->query($up);
                                }

                            }
                            else {
                                $this->db->trans_rollback(); 
                                throw new Exception('Data Save Failed !!');
                            }
                        }

	                    if ($result && $result2 && $result3) {
	                        $this->db->trans_commit();
	                        $return = [
	                            'STATUS' => TRUE,
	                            'MESSAGE' => 'Data has been Successfully Saved !!'
	                        ];
	                    } else {
	                        $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
	                    }
	                }//end if tipe
	                else{
	                	$q = "SELECT * FROM LEASINGMASTER WHERE DOCNUMBER = '".$param['DOCNUMBER']."'";

	                    $getDetMaster = $this->db->query($q)->row();
	                    $plusdate = $getDetMaster->DOCDATE;
	                    $tomorrow = date('m-d-Y',strtotime($plusdate . "+1 days"));
                    	$up = "UPDATE LEASINGMASTER SET ISACTIVE = 'FALSE' WHERE DOCNUMBER = '".$param['DOCNUMBER']."'";
                        // $up = "UPDATE LEASINGMASTER SET VALID_UNTIL = TO_DATE('" . $tomorrow . "','MM/DD/YYYY') WHERE DOCNUMBER = '".$param['DOCNUMBER']."'";
                        $result = $this->db->query($up);

                        if ($result) {
                        	$this->db->trans_commit();
	                        $return = [
	                            'STATUS' => TRUE,
	                            'MESSAGE' => 'Data has been Successfully Saved !!'
	                        ];
	                    } else {
	                        $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
	                    }
	                }//end else tipe
                }
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

    public function ReportDataTransaction($param){

        if($param['MONTH'] < 10){
            $MONTH = '0'.$param['MONTH'];
        }
        else{
            $MONTH = $param['MONTH'];
        }

       
        $YEAR  = $param['YEAR'];

        $SQL = "SELECT LM.UUID, LM.COMPANY, LM.DOCNUMBER, LM.CURRENCY, LM.DUEDATE_PER_MONTH, LM.BASIC_AMOUNT, LM.AMOUNT_PER_MONTH, LM.AMOUNT_AFTER_CONV, LM.INTEREST_PERCENTAGE, LM.TOTAL_MONTH, LT.INTEREST_AMOUNT_MONTHLY, LT.MONTHTOBE, LT.BASIC_AMOUNT_MONTHLY, LT.INTEREST_AMOUNT_MONTHLY, LT.AMOUNT_MONTHLY_LEASING, LT.BASIC_AMOUNT_MONTHLY_CONV, LT.INTEREST_AMOUNT_MONTHLY_CONV, LT.AMOUNT_MONTHLY_LEASING_CONV, LT.REMAIN_MONTH, TRANSACTIONMETHOD_BY, TO_NUMBER (LT.PERIOD_YEAR) AS PERIOD_YEAR, TO_NUMBER (LT.PERIOD_MONTH) AS PERIOD_MONTH, C.COMPANYNAME, PC.CURRENTACCOUNTINGPERIOD, PC.CURRENTACCOUNTINGYEAR, M.FCNAME, S.FCNAME AS VENDORNAME ";
        $SQL .= " FROM LEASINGTRANSACTION LT INNER JOIN LEASINGMASTER LM ON LM.DOCNUMBER = LT.DOCNUMBER INNER JOIN PERIODCONTROL PC ON PC.COMPANY = LM.COMPANY INNER JOIN COMPANY C ON C.ID = LM.COMPANY INNER JOIN MATERIAL M ON M.ID = LM.ITEM_CODE INNER JOIN SUPPLIER S ON S.ID = LM.VENDOR WHERE TO_CHAR(LT.DUEDATE,'yyyy') = '$YEAR' AND TO_CHAR(LT.DUEDATE,'mm') = '$MONTH'";
        // var_dump($SQL);exit();
        $result = $this->db->query($SQL)->result();

        return $result;
    }

    public function showReportLeasing($param){
       
        $COMPANY  = $param['COMPANY'];
        $WHERE = " WHERE p2.id IS NULL";
        if($COMPANY != "0"){
            $WHERE .= " AND lm.company = '$COMPANY' ";
        }
        $SQL = "SELECT DISTINCT c.companycode,
                   c.id as companyid,
                   supp.fcname AS suppname,
                   lm.docnumber,
                   lm.currency,
                   lm.basic_amount,
                   lm.INTEREST_PERCENTAGE,
                   lm.total_month,
                   lm.duedate_per_month,
                   lt.remain_basic_amount_leasing,
                   lm.valid_from,
                   BU.FCNAME as BUNAME
              FROM (select * from leasingmaster where ISACTIVE = 'TRUE') lm
              LEFT join (select id,gid, docnumber, remain_basic_amount_leasing,lastupdate from leasingtransaction) lt on lt.docnumber = lm.docnumber
              LEFT OUTER JOIN leasingtransaction p2 ON (lm.docnumber = p2.docnumber AND 
    (lt.lastupdate < p2.lastupdate OR (lt.lastupdate = p2.lastupdate AND lt.id < p2.id)))
              LEFT JOIN (SELECT id,fcname FROM supplier) supp
                          ON supp.id = lm.vendor
              LEFT JOIN (SELECT COMPANYCODE, ID FROM COMPANY) C ON C.ID = LM.COMPANY LEFT JOIN BUSINESSUNIT BU ON BU.ID = LM.BUSINESSUNIT ".$WHERE;
        
        $result = $this->db->query($SQL)->result();
        // var_dump($this->db->last_query());exit;
        return $result;
    }

    public function showDeleteTransaction($param){
        $COMPANY      = $param['COMPANY'];
        $q = "SELECT LT.ID,LT.GID, LT.DOCNUMBER, M.FCNAME, LT.PERIOD_YEAR, LT.PERIOD_MONTH, LT.LINENO AS TRANSAKSI_KE, LT.BASIC_AMOUNT_MONTHLY, LT.INTEREST_AMOUNT_MONTHLY, LT.AMOUNT_MONTHLY_LEASING, L.DOCDATE, L.COMPANY, L.TRANSACTIONMETHOD_BY, C.COMPANYNAME FROM ( SELECT ID, GID, PERIOD_YEAR, PERIOD_MONTH, DOCNUMBER, LINENO, BASIC_AMOUNT_MONTHLY, INTEREST_AMOUNT_MONTHLY, AMOUNT_MONTHLY_LEASING FROM LEASINGTRANSACTION GROUP BY ID,GID, DOCNUMBER, PERIOD_YEAR, PERIOD_MONTH, LINENO, BASIC_AMOUNT_MONTHLY, INTEREST_AMOUNT_MONTHLY, AMOUNT_MONTHLY_LEASING) LT INNER JOIN LEASINGMASTER L ON L.DOCNUMBER = LT.DOCNUMBER INNER JOIN MATERIAL M ON M.ID = L.ITEM_CODE INNER JOIN COMPANY C ON C.ID = L.COMPANY WHERE L.COMPANY = '".$COMPANY."' GROUP BY LT.ID, LT.GID, LT.DOCNUMBER, M.FCNAME, LT.PERIOD_YEAR, LT.PERIOD_MONTH, LT.LINENO, LT.BASIC_AMOUNT_MONTHLY, LT.INTEREST_AMOUNT_MONTHLY, LT.AMOUNT_MONTHLY_LEASING, L.DOCDATE, L.COMPANY, L.TRANSACTIONMETHOD_BY, C.COMPANYNAME ORDER BY LT.LINENO ASC";
        $result = $this->db->query($q)->result();

        return $result;
    }

    public function showDeleteForecast($param){
        $param["CASHFLOWTYPE"] = "%" . $param["CASHFLOWTYPE"] . "%";
        $SQL = "SELECT DISTINCT CFT.*, DT.CASHFLOWTYPE, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,(  CFT.AMOUNT_INCLUDE_VAT
          - NVL (FO.AMOUNTINV, 0)
          + NVL (FF.AMOUNTINV, 0))
            AS AMOUNTOUTSTANDING
                  FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR' OR CFT.DOCTYPE = 'LEASING')
                         UNION ALL
                        SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN','LEASING')
                         UNION ALL 
                         SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AP_SPC' and CFT.EXTSYS ='TIPTOP') CFT
                 INNER JOIN (SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5,
                                    ROUND(SUM(DECODE(FF.ISACTIVE, 0, FF.AMOUNTREQUEST, FF.AMOUNTADJS)), 2) AS AMOUNTINV,
                                    MAX(DECODE(FF.WEEK, 'W1', FF.PRIORITY, '')) AS PRIORITYW1, 
                                    MAX(DECODE(FF.WEEK, 'W2', FF.PRIORITY, '')) AS PRIORITYW2, 
                                    MAX(DECODE(FF.WEEK, 'W3', FF.PRIORITY, '')) AS PRIORITYW3, 
                                    MAX(DECODE(FF.WEEK, 'W4', FF.PRIORITY, '')) AS PRIORITYW4, 
                                    MAX(DECODE(FF.WEEK, 'W5', FF.PRIORITY, '')) AS PRIORITYW5
                               FROM $this->FORECAST_FIX FF
                              WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                              GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS) FF
                         ON FF.CFTRANSID = CFT.ID
                  LEFT JOIN (SELECT CFTRANSID, SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                               FROM $this->FORECAST_FIX
                              GROUP BY CFTRANSID) FO
                         ON FO.CFTRANSID = CFT.ID
                 INNER JOIN $this->DOCTYPE DT
                         ON DT.FCCODE = CFT.DOCTYPE
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
                  LEFT JOIN CURS CR
                         ON CR.CURSCODE = CFT.CURRENCY
                        AND CR.CURSYEAR = ?
                        AND CR.CURSMONTH = ?
                 WHERE DT.CASHFLOWTYPE LIKE ?";

        $SQL1 = "SELECT FD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, SUM(FD.AMOUNTREQUEST) AS AMOUNTREQUEST, SUM(FD.AMOUNTADJS) AS AMOUNTADJS,
                        SUM(FD.AMOUNTREVISI) AS AMOUNTREVISI, SUM(FD.AMOUNTAPPROVE) AS AMOUNTAPPROVE, FD.LOCKS, FD.ISACTIVE
                   FROM $this->FORECASTDANA FD
                  INNER JOIN $this->USER_DEPART UD
                          ON UD.FCCODE = ?
                         AND UD.DEPARTMENT = FD.DEPARTMENT
                  WHERE FD.YEAR = ?
                    AND FD.MONTH = ? 
                    AND FD.CASHFLOWTYPE LIKE ?";
        $ARR1 = [$param['YEAR'], $param['MONTH'], $param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["CASHFLOWTYPE"]];
        if ($param["DEPARTMENT"] != NULL && $param["DEPARTMENT"] != '') {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            array_push($ARR1, $param["DEPARTMENT"]);
        }
        if ($param["USERACCESS"] == "100003") {
            $SQL .= " AND FF.LOCKS = 1";
        }
        $SQL .= " ORDER BY DT.CASHFLOWTYPE, CFT.DEPARTMENT,
                           CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER, CFT.INVOICEVENDORNO";

        $result = $this->db->query($SQL, $ARR1)->result();
        return $result;
    }

    public function DeleteLeasingTransaction($param){
        
        try {
            $this->db->trans_begin();
            // $TIPE = $param['TIPE'];
            $GID  = $param['GID'];
            $res  = FALSE;

            // if($TIPE == "1"){
                $q   = "DELETE FROM LEASINGTRANSACTION WHERE GID = '".$GID."'";
                $res = $this->db->query($q);

                $qCFTrans   = "DELETE FROM CF_TRANSACTION WHERE ID = '".$GID."'";
                $res = $this->db->query($qCFTrans);
                $qCFTransDet   = "DELETE FROM CF_TRANSACTION_DET WHERE ID = '".$GID."'";
                $res = $this->db->query($qCFTransDet);

                $qForecast = "DELETE FROM FORECAST_FIX WHERE CFTRANSID = '".$GID."'";
                $res = $this->db->query($qForecast);
            // }else{
            //     $q   = "DELETE FROM LEASINGTRANSACTION WHERE GID = '".$GID."'";
            //     $res = $this->db->query($q);

            //     $qCFTrans   = "DELETE FROM CF_TRANSACTION WHERE ID = '".$GID."'";
            //     $res = $this->db->query($qCFTrans);
            //     $qCFTransDet   = "DELETE FROM CF_TRANSACTION_DET WHERE ID = '".$GID."'";
            //     $res = $this->db->query($qCFTransDet);
            // }

            if ($res) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function DeleteForecastTransaction($param){
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            $this->db->trans_begin();
            // $TIPE = $param['TIPE'];
            $GID  = $param['GID'];
            $result  = FALSE;


            $qForecast = "SELECT CFTRANSID FROM FORECAST_FIX WHERE CFTRANSID = '".$GID."'";
            $resFF = $this->db->query($qForecast);    

            if($resFF->num_rows() > 0){
                $qFF   = "DELETE FROM FORECAST_FIX WHERE CFTRANSID = '".$GID."'";
                $res = $this->db->query($qFF);

                if($res){
                    $qCFTransDet   = "DELETE FROM CF_TRANSACTION_DET WHERE ID = '".$GID."'";
                    $res = $this->db->query($qCFTransDet);
                    
                    $qCFTrans   = "DELETE FROM CF_TRANSACTION WHERE ID = '".$GID."'";
                    $res = $this->db->query($qCFTrans);
                }
                $result = true;
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
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

    public function ExportTransaction($data){
        $COMPANY    = $data['COMPANY'];
        $DOCNUMBER  = $data['DOCNUMBER'];
        // var_dump($data);exit;
        // $this->saveLeasingReport($DOCNUMBER,$COMPANY);
        
        try {
            $SQL = "SELECT a.DUEDATE,
                     a.DOCNUMBER,
                     C.COMPANYCODE,
                     L.DOCNUMBER,
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
                FROM (  SELECT DUEDATE,DOCNUMBER,
                               MONTHTOBE,
                               ISPAYMENT,
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
                                 WHERE DOCNUMBER = '$DOCNUMBER'
                                UNION
                                SELECT lr.*, '' AS daterelease, 0 AS lebih_hari
                                  FROM leasingreport lr
                                 WHERE DOCNUMBER = '$DOCNUMBER')
                      GROUP BY DUEDATE,DOCNUMBER, MONTHTOBE,ISPAYMENT) a
                     INNER JOIN
                     (SELECT DOCNUMBER, COMPANY, TRANSACTIONMETHOD_BY, TOTAL_MONTH,AMOUNT_AFTER_CONV, INTEREST_PERCENTAGE, DENDA_PERCENTAGE, VENDOR, ITEM_CODE, BUSINESSUNIT FROM LEASINGMASTER) L
                        ON L.DOCNUMBER = a.docnumber
                     INNER JOIN (SELECT COMPANYCODE, ID FROM COMPANY) C ON C.ID = L.COMPANY
                     INNER JOIN SUPPLIER S ON S.ID = L.VENDOR
                     INNER JOIN MATERIAL M ON M.ID = L.ITEM_CODE
                     INNER JOIN BUSINESSUNIT B ON B.ID = L.BUSINESSUNIT
            ORDER BY TO_NUMBER (a.monthtobe) ASC";
            $result = $this->db->query($SQL)->result();
            // echo "<pre>";
            // var_dump($this->db->last_query());exit();
            $SQL2 = "SELECT ((L.PENALTY_PERCENTAGE/100) * MIN(LT.REMAIN_BASIC_AMOUNT_LEASING)) PEN_SISTEM,
                           LC.AMOUNT_WITH_PENALTY,MIN(LT.REMAIN_BASIC_AMOUNT_LEASING) REMAIN_BASIC_AMOUNT_LEASING,
                           (SELECT DENDA
                          FROM DENDA_LEASING
                         WHERE DOCNUMBER = '$DOCNUMBER') DENDA
                      FROM LEASINGMASTER L
                      INNER JOIN LEASINGTRANSACTION LT ON LT.DOCNUMBER = L.DOCNUMBER
                           INNER JOIN LEASINGCOMPLETION LC ON LC.DOCNUMBER = L.DOCNUMBER
                     WHERE L.DOCNUMBER = '$DOCNUMBER' GROUP BY PENALTY_PERCENTAGE,AMOUNT_WITH_PENALTY";
            $result2 = $this->db->query($SQL2)->row();

            $cekKomplit = "SELECT DOCDATE FROM LEASINGCOMPLETION WHERE DOCNUMBER = '$DOCNUMBER'";
            $resultCek  = $this->db->query($cekKomplit)->row();

            if($resultCek != null){
                $resultCek = $resultCek->DOCDATE;
            }else{
                $resultCek = "-";
            }

            $q2    = "SELECT DENDA
                          FROM DENDA_LEASING
                         WHERE DOCNUMBER = '$DOCNUMBER'";
            $resq = $this->db->query($q2)->row()->DENDA;
            

            // var_dump($result2->PEN_SISTEM.'-'.$result2->AMOUNT_WITH_PENALTY.'-'.$result->REMAIN_BASIC_AMOUNT_LEASING.'-'.$resq);exit;
            if(count($result) == 0){
                throw new Exception("Data Kosong");
            }
            
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("IT GAMA")
                    ->setLastModifiedBy("IT GAMA")
                    ->setTitle("Report Leasing Transaction")
                    ->setSubject("Report Leasing Transaction");

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
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'COMPANY')
            ->setCellValue('B1', 'DOCNUMBER')
            ->setCellValue('C1', 'TRANSACTION METHOD')
            ->setCellValue('D1', 'PINJAMAN')
            ->setCellValue('E1', 'JUMLAH ANGSURAN')
            ->setCellValue('F1', 'BUNGA')
            ->setCellValue('G1', 'MATERIAL')
            ->setCellValue('H1', 'VENDOR')
            ->setCellValue('J1', 'DATE COMPLETION');

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', $result[0]->COMPANYCODE)
            ->setCellValue('B2', $result[0]->DOCNUMBER)
            ->setCellValue('C2', $result[0]->TRANSACTIONMETHOD_BY)
            ->setCellValue('D2', number_format($result[0]->AMOUNT_AFTER_CONV))
            ->setCellValue('E2', $result[0]->TOTAL_MONTH-1)
            ->setCellValue('F2', $result[0]->INTEREST_PERCENTAGE.' %')
            ->setCellValue('G2', $result[0]->ITEM_NAME)
            ->setCellValue('H2', $result[0]->FCNAME)
            ->setCellValue('J2', $resultCek);

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'NO')
            ->setCellValue('B3', 'ANGSURAN')
            ->setCellValue('C3', 'POKOK')
            ->setCellValue('D3', 'BUNGA')
            ->setCellValue('E3', 'BESAR ANGSURAN')
            ->setCellValue('F3', 'SISA PINJAMAN')
            ->setCellValue('G3', 'DENDA')
            ->setCellValue('H3', 'PAID DATE')
            ->setCellValue('I3', 'PENALTY')
            ->setCellValue('J3', 'DISKON')
            ->setCellValue('K3', 'TOTAL COMPLETION');

            $objPHPExcel->getActiveSheet()->setTitle('Data');

            $i = 4;
            if (count($result) > 0) {
                $iDtAwal = $i;
                $No = 1;
                foreach ($result as $row) {
                    $totalBasic = 0;
                    $totalInterest = 0;
                    $totalRemain = 0;
                    $BASIC_AMOUNT_MONTHLY       = floatval($row->BASIC_AMOUNT_MONTHLY);
                    $INTEREST_AMOUNT_MONTHLY    = floatval($row->INTEREST_AMOUNT_MONTHLY);
                    $AMOUNT_MONTHLY_LEASING     = floatval($row->AMOUNT_MONTHLY_LEASING);

                    $objPHPExcel->getActiveSheet()->setCellValue('A'. $i, $row->MONTHTOBE);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'. $i, $row->DUEDATE);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'. $i, $BASIC_AMOUNT_MONTHLY);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'. $i, $INTEREST_AMOUNT_MONTHLY);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'. $i, $AMOUNT_MONTHLY_LEASING);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'. $i, $row->REMAIN_BASIC_AMOUNT_LEASING);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'. $i, $row->LEBIH_HARI*$AMOUNT_MONTHLY_LEASING*floatval($row->DENDA_PERCENTAGE/ 100));
                    $objPHPExcel->getActiveSheet()->setCellValue('H'. $i, $row->DATERELEASE);

                    $totalBasic    += floatval($row->BASIC_AMOUNT_MONTHLY);
                    $totalInterest += floatval($row->INTEREST_AMOUNT_MONTHLY);
                    $totalRemain   += floatval($row->AMOUNT_MONTHLY_LEASING);

                    $i++;
                    // $objPHPExcel->setActiveSheetIndex(0)
                    // ->setCellValue('B'.$i,$totalBasic) 
                    // ->setCellValue('C'.$i,$totalInterest) 
                    // ->setCellValue('D'.$i,$totalRemain);
                    $No++;
                }

                $sumrange = 'E4:E'.$i;
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D2', '=SUM(' . $sumrange . ')');
                $PEN_SISTEM = '';
                $ji         = '';
                $AMT_PENALTY = '';
                if($result2 != NULL){
                    $PEN_SISTEM = $result2->PEN_SISTEM;
                    $ji         = $result2->PEN_SISTEM - ($result2->AMOUNT_WITH_PENALTY - $result2->REMAIN_BASIC_AMOUNT_LEASING - $result2->DENDA);
                    $AMT_PENALTY = $result2->AMOUNT_WITH_PENALTY;
                }

                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('I'.$i,$PEN_SISTEM)
                ->setCellValue('J'.$i,$ji)
                ->setCellValue('K'.$i,$AMT_PENALTY);
                $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($style_ContentNumeric);
                
                $objPHPExcel->getActiveSheet()->getStyle('A1:K3')->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle('A1:K2')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A1:K'.$i)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('B4:K'.$i)->applyFromArray($style_ContentNumeric);
                foreach(range('A','K') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle('A3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
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

    public function exportNewReport($data){
        $COMPANY    = $data['COMPANY'];
        
        try {
            $WHERE = "";
            if($COMPANY != "0"){
                $WHERE .= " WHERE lm.company = '$COMPANY' ";
            }
            $SQL = "SELECT c.companycode,
                       c.id AS companyid,
                       supp.fcname AS suppname,
                       lm.currency,
                       lm.basic_amount,
                       lm.rate,
                       lm.total_month,
                       lm.duedate_per_month,
                       lt.remain_basic_amount_leasing,
                       lm.valid_from,
                       lm.valid_until
                  FROM (select * from leasingmaster where ISACTIVE = 'TRUE') lm
                       INNER JOIN
                       (SELECT gid, docnumber, remain_basic_amount_leasing
                          FROM leasingtransaction) lt
                          ON lt.docnumber = lm.docnumber
                       -- INNER JOIN (SELECT bankcode, daterelease, cftransid FROM payment) py
                       --    ON py.cftransid = lt.gid
                       INNER JOIN (SELECT id,fcname FROM supplier) supp
                          ON supp.id = lm.vendor
                       INNER JOIN (SELECT COMPANYCODE, ID FROM COMPANY) C
                          ON C.ID = LM.COMPANY".$WHERE." order by companycode";
            $result = $this->db->query($SQL)->result();
            // var_dump($this->db->last_query());exit();
            if(count($result) == 0){
                throw new Exception("Data Kosong");
            }
            
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("IT GAMA")
                    ->setLastModifiedBy("IT GAMA")
                    ->setTitle("Report Leasing")
                    ->setSubject("Report Leasing");

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

            $objPHPExcel->getActiveSheet()->setTitle('Data');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'COMPANY')
                ->setCellValue('B1', 'VENDOR')
                ->setCellValue('C1', 'CURRENCY')
                ->setCellValue('D1', 'BASIC AMOUNT')
                ->setCellValue('E1', 'PROVISION %')
                ->setCellValue('F1', 'RATE %')
                ->setCellValue('G1', 'TOTAL PERIOD')
                ->setCellValue('H1', 'DUE DATE')
                ->setCellValue('I1', 'FIRST PAYMENT')
                ->setCellValue('J1', 'PAST DUE')
                ->setCellValue('K1', 'OUTSTANDING '.Date('d-m-Y'));

            $i = 2;
            $startRow = -1;
            $previousKey = '';
            if (count($result) > 0) {
                // $iDtAwal = $i;
                $No = 1;
                foreach ($result as $index => $row) {
                    
                    if($startRow == -1){
                        $startRow = $i;
                        $previousKey = $result[$index]->COMPANYCODE;
                    }

                    $objPHPExcel->getActiveSheet()->setCellValue('A'. $i, $row->COMPANYCODE);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'. $i, $row->SUPPNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'. $i, $row->CURRENCY);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'. $i, number_format($row->BASIC_AMOUNT,2));
                    $objPHPExcel->getActiveSheet()->setCellValue('E'. $i, "");
                    $objPHPExcel->getActiveSheet()->setCellValue('F'. $i, $row->RATE);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'. $i, $row->TOTAL_MONTH);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'. $i, $row->DUEDATE_PER_MONTH);
                    $objPHPExcel->getActiveSheet()->setCellValue('I'. $i, $row->VALID_FROM);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'. $i, $row->VALID_UNTIL);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'. $i, number_format($row->REMAIN_BASIC_AMOUNT_LEASING,2));

                    // $totalBasic    += floatval($row->BASIC_AMOUNT_MONTHLY);
                    // $totalInterest += floatval($row->INTEREST_AMOUNT_MONTHLY);
                    // $totalRemain   += floatval($row->AMOUNT_MONTHLY_LEASING);

                    $nextKey = isset($result[$index+1]) ? $result[$index+1]->COMPANYCODE : null;
                    if($i >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                        $cellToMerge = 'A'.$startRow.':A'.$i;
                        $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                        $startRow = -1;
                    }

                    $i++;
                    // $objPHPExcel->setActiveSheetIndex(0)
                    // ->setCellValue('B'.$i,$totalBasic) 
                    // ->setCellValue('C'.$i,$totalInterest) 
                    // ->setCellValue('D'.$i,$totalRemain);
                    // $No++;
                }
                // $objPHPExcel->getActiveSheet()->mergeCells('A2:A' . ($i));

                // $objPHPExcel->setActiveSheetIndex(0)
                // ->setCellValue('H'.$i,number_format($result2->AMOUNT_WITH_PENALTY))
                // ->setCellValue('I'.$i,number_format($result2->PENALTY - $result2->AMOUNT_WITH_PENALTY));
                $objPHPExcel->getActiveSheet()->getStyle('A2:A'.$i)->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleHeader);
                $objPHPExcel->getActiveSheet()->getStyle('A1:K'.$i)->applyFromArray($styleArray);
                // $objPHPExcel->getActiveSheet()->getStyle('B4:I'.$i)->applyFromArray($style_ContentNumeric);
                foreach(range('A','K') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                // $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
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