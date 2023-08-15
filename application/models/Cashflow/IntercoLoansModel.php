<?php

defined('BASEPATH') or exit('No direct script access allowed');

class IntercoLoansModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }

    public function ShowData($param) {

    }

    public function SearchOtherPaymentnew($param) {

        $MONTH = $param['MONTH'];
        $YEAR  = $param['YEAR'];
        $COMPANY = $param['COMPANY'];

        if($MONTH < 10){
            $MONTH = '0'.$MONTH;
        }

        $WHERE = '';
        if($COMPANY != null){
            $WHERE .= " AND INC.COMPANYSOURCE = '$COMPANY'";
        }
        $SQL = "SELECT INC.INTERCOID, TO_CHAR(INC.DATERELEASE,'MM/DD/YYYY')DATERELEASE, 
                                    INC.COMPANYSOURCE, COMPS.COMPANYNAME COMPANYSOURCENAME,
                                    INC.BANKSOURCE, BANKS.FCNAME||'-'||BANKS.CURRENCY BANKSOURCENAME,
                                    INC.COMPANYTARGET, COMPT.COMPANYNAME COMPANYTARGETNAME,
                                    INC.BANKTARGET, BANKT.FCNAME||'-'||BANKT.CURRENCY BANKTARGETNAME,
                                    INC.VOUCHERNO,INC.NOCEKGIRO,INC.RATE, INC.SOURCEAMOUNT, INC.AMOUNT,INC.ISACTIVE,INC.REMARKS,
                                    INC.FCENTRY,INC.FCEDIT,INC.FCIP,INC.LASTUPDATE,
                                    CASE WHEN PPC.CURRENTACCOUNTINGYEAR = '$YEAR' AND PPC.CURRENTACCOUNTINGPERIOD = '$MONTH' THEN 1 ELSE 0 END AS STATUS
                                    FROM INTERCOLOANS INC 
                                    INNER JOIN COMPANY COMPT ON INC.COMPANYTARGET = COMPT.ID
                                    INNER JOIN COMPANY COMPS ON INC.COMPANYSOURCE = COMPS.ID
                                    INNER JOIN BANK BANKT ON INC.BANKTARGET = BANKT.FCCODE
                                    INNER JOIN BANK BANKS ON INC.BANKSOURCE = BANKS.FCCODE
                                    INNER JOIN PAYMENT_PERIODCONTROL PPC ON PPC.COMPANY = INC.COMPANYTARGET
                                     WHERE TO_CHAR(INC.DATERELEASE,'mm') = '$MONTH' AND TO_CHAR(INC.DATERELEASE,'yyyy') = '$YEAR'".$WHERE;
        $SQL .= " ORDER BY INC.LASTUPDATE DESC";
        $query = $this->db->query($SQL);
         return $query->result();
    }

	public function SearchIntercoLoans($param) {
		
		$query = $this->db->query("SELECT INC.INTERCOID, TO_CHAR(INC.DATERELEASE,'MM/DD/YYYY')DATERELEASE, 
									INC.COMPANYSOURCE, COMPS.COMPANYNAME COMPANYSOURCENAME,
									INC.BANKSOURCE, BANKS.FCNAME||'-'||BANKS.CURRENCY BANKSOURCENAME,
									INC.COMPANYTARGET, COMPT.COMPANYNAME COMPANYTARGETNAME,
									INC.BANKTARGET, BANKT.FCNAME||'-'||BANKT.CURRENCY BANKTARGETNAME,
									INC.VOUCHERNO,INC.NOCEKGIRO,INC.RATE, INC.SOURCEAMOUNT, INC.AMOUNT,INC.ISACTIVE,INC.REMARKS,
									INC.FCENTRY,INC.FCEDIT,INC.FCIP,INC.LASTUPDATE
									FROM INTERCOLOANS INC 
									INNER JOIN COMPANY COMPT ON INC.COMPANYTARGET = COMPT.ID
									INNER JOIN COMPANY COMPS ON INC.COMPANYSOURCE = COMPS.ID
									INNER JOIN BANK BANKT ON INC.BANKTARGET = BANKT.FCCODE
									INNER JOIN BANK BANKS ON INC.BANKSOURCE = BANKS.FCCODE
									ORDER BY INC.LASTUPDATE");
         return $query->result();
    }

    public function SaveIntercoLoans($Data, $Location) {
        try {

            // echo "<pre>";
            // print_r ($Data);
            // echo "</pre>";exit;
            $this->db->select('COMPANYCODE');
            $getCompany = $this->db->get_where('COMPANY',[ 'ID' => $Data['COMPANYSOURCE']])->row()->COMPANYCODE;
            $this->db->select('COMPANYCODE');
            $getUser = $this->db->get_where('USER_COMPANY_TAB',[ 'USERCODE' => $Data['USERNAME']])->num_rows();
            if($getUser > 0){
                $getUserCompany = $this->db->get_where('USER_COMPANY_TAB',[ 'USERCODE' => $Data['USERNAME'], 'COMPANYCODE' => $getCompany])->num_rows();
                if($getUserCompany == 0){
                    throw new Exception("Company tidak terdaftar di user");
                }
            }

            $this->db->trans_begin();
            $result = FALSE;

            if($Data['ACTION'] == 'ADD'){

                $checkVoc = "SELECT COMPANYSOURCE, VOUCHERNO FROM INTERCOLOANS WHERE VOUCHERNO = '".$Data['VOUCHERNO']."' AND COMPANYSOURCE = '".$Data['COMPANYSOURCE']."'";
                $checkVoc = $this->db->query($checkVoc)->num_rows();
                if($checkVoc > 0){
                    throw new Exception("Duplicate VoucherNo");
                }
            }


            $cekYear = Date('Y');
            $LIKE = "'%IL/".$getCompany."%'";
            $q  = "SELECT CASE
                  WHEN (NUMMAX <= 10000 AND NUMMAX >= 1000)
                  THEN
                     '0' || TO_CHAR (NUMMAX)
                  WHEN (NUMMAX <= 1000 AND NUMMAX >= 100)
                  THEN
                     '00' || TO_CHAR (NUMMAX)
                  WHEN (NUMMAX <= 100 AND NUMMAX >= 10)
                  THEN
                     '000' || TO_CHAR (NUMMAX)
                  WHEN (NUMMAX <= 10 AND NUMMAX >= 0)
                  THEN
                     '0000' || TO_CHAR (NUMMAX)
                  ELSE
                     TO_CHAR (NUMMAX) END AS noF ";
            $q  .= " FROM (SELECT TO_NUMBER (maxno) + 1 NUMMAX FROM (SELECT NVL (MAX (SUBSTR (VOUCHERNO, -5)), 0) maxno FROM INTERCOLOANS where voucherno like $LIKE AND TO_CHAR(LASTUPDATE,'YYYY') = '$cekYear' ))";
            $genNumber  = $this->db->query($q)->row()->NOF;

            // var_dump($this->db->last_query());exit;
            $genVoucher = 'IL/'.$getCompany.'/'.Date('Y').'/'.$genNumber;

            $qcekDupe = "SELECT * FROM INTERCOLOANS WHERE VOUCHERNO = '$genVoucher' AND COMPANYSOURCE = '".$Data['COMPANYSOURCE']."'";
            $cekDupe  = $this->db->query($qcekDupe)->num_rows();
            if($cekDupe > 0){
                throw new Exception("Duplicate VoucherNo, Please Contact Your Administrator");
            }

            $dt = [
                'COMPANYSOURCE' => $Data['COMPANYSOURCE'],
                'BANKSOURCE' => $Data['BANKSOURCE'],
                'COMPANYTARGET' => $Data['COMPANYTARGET'],
                'BANKTARGET' => $Data['BANKTARGET'],
                'NOCEKGIRO' => $Data['NOCEKGIRO'],
                'SOURCEAMOUNT' => $Data['SOURCEAMOUNT'],
                'RATE' => $Data['RATE'],
                'AMOUNT' => $Data['AMOUNT'],
                'REMARKS' => $Data['REMARK'],
                'ISACTIVE' => 1,
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];

            // Cek Period
            // $SQL = "SELECT CURRENTACCOUNTINGYEAR, CURRENTACCOUNTINGPERIOD
            //           FROM PAYMENT_PERIODCONTROL 
            //          WHERE COMPANY = ?";
            // $cekPeriod = $this->db->query($SQL, [$Data["COMPANYTARGET"]])->result();
            // $PERIOD = "";
            // $YEAR = "";
            // $MONTH = "";
            // if (count($cekPeriod) > 0) {
            //     foreach ($cekPeriod as $value) {
            //         $PERIOD = $value->CURRENTACCOUNTINGYEAR . substr("0" . $value->CURRENTACCOUNTINGPERIOD, -2);
            //         $YEAR = $value->CURRENTACCOUNTINGYEAR;
            //         $MONTH = $value->CURRENTACCOUNTINGPERIOD;
            //     }
            //     $PERIODT = explode('/', $Data["DATERELEASE"]);
            //     $PERIODV = $PERIODT[2] . $PERIODT[0];
            //     if (intval($PERIOD) > intval($PERIODV)) {
            //         throw new Exception("Period is Over !!!");
            //     }
            // } else {
            //     throw new Exception("Period Balance Not Set !!!");
            // }

            // Cek Amount Bank
            // if ($Data['CASHFLOWTYPE'] == "1") {
            // $AMOUNTNOW = 99999999;
                // $AMOUNTNOW = $this->CekBankAmount($PERIOD, $YEAR, $MONTH, $Data["BANKSOURCE"]);
                // // var_dump($this->db->last_query());exit;
                // if (floatval($AMOUNTNOW) < floatval($Data["SOURCEAMOUNT"])) {
                //     throw new Exception("Insufficient Balance!!!");
                // }
            // }

            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                ->set('DATERELEASE', "TO_DATE('" . $Data['DATERELEASE'] . "','mm/dd/yyyy')", false);

             if ($Data['INTERCOID'] != '' && $Data['INTERCOID'] != null) {
                $result = $result->set($dt)
                        ->where(['INTERCOID' => $Data['INTERCOID']])
                        ->update('INTERCOLOANS');
            } else {
                $dt['FCENTRY'] = $Data['USERNAME'];
                $dt['VOUCHERNO'] = $genVoucher;
                $result = $result->set($dt)->insert('INTERCOLOANS');
            }

            if ($result) {
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
	
	public function SaveIntercoLoansold($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            
            $checkVoc = "SELECT COMPANYSOURCE, VOUCHERNO FROM INTERCOLOANS WHERE VOUCHERNO = '".$Data['VOUCHERNO']."' AND COMPANYSOURCE = '".$Data['COMPANYSOURCE']."'";
            $checkVoc = $this->db->query($checkVoc)->num_rows();
            if($checkVoc > 0){
                throw new Exception("Duplicate VoucherNo");
            }

            $dt = [
                'COMPANYSOURCE' => $Data['COMPANYSOURCE'],
				'BANKSOURCE' => $Data['BANKSOURCE'],
				'COMPANYTARGET' => $Data['COMPANYTARGET'],
				'BANKTARGET' => $Data['BANKTARGET'],
                'VOUCHERNO' => $Data['VOUCHERNO'],
                'NOCEKGIRO' => $Data['NOCEKGIRO'],
				'SOURCEAMOUNT' => $Data['SOURCEAMOUNT'],
				'RATE' => $Data['RATE'],
				'AMOUNT' => $Data['AMOUNT'],
                'REMARKS' => $Data['REMARK'],
                'ISACTIVE' => 1,
                'FCEDIT' => $Data['USERNAME'],
                'FCIP' => $Location
            ];

            // Cek Period
            $SQL = "SELECT CURRENTACCOUNTINGYEAR, CURRENTACCOUNTINGPERIOD
                      FROM $this->PERIODCONTROL 
                     WHERE COMPANY = ?";
            $cekPeriod = $this->db->query($SQL, [$Data["COMPANYTARGET"]])->result();
            $PERIOD = "";
            $YEAR = "";
            $MONTH = "";
            if (count($cekPeriod) > 0) {
                foreach ($cekPeriod as $value) {
                    $PERIOD = $value->CURRENTACCOUNTINGYEAR . substr("0" . $value->CURRENTACCOUNTINGPERIOD, -2);
                    $YEAR = $value->CURRENTACCOUNTINGYEAR;
                    $MONTH = $value->CURRENTACCOUNTINGPERIOD;
                }
                $PERIODT = explode('/', $Data["DATERELEASE"]);
                $PERIODV = $PERIODT[2] . $PERIODT[0];
                // if (intval($PERIOD) > intval($PERIODV)) {
                //     throw new Exception("Period is Over !!!");
                // }
            } else {
                throw new Exception("Period Balance Not Set !!!");
            }

            // Cek Amount Bank
            // if ($Data['CASHFLOWTYPE'] == "1") {
                //$AMOUNTNOW = $this->CekBankAmount($PERIOD, $YEAR, $MONTH, $Data["BANKSOURCE"]);
                //if (floatval($AMOUNTNOW) < floatval($Data["SOURCEAMOUNT"])) {
                //    throw new Exception("Insufficient Balance!!!");
                //}
            // }

            $result = $this->db->set('LASTUPDATE', "SYSDATE", false)
                                ->set('DATERELEASE', "TO_DATE('" . $Data['DATERELEASE'] . "','mm/dd/yyyy')", false);

             if ($Data['INTERCOID'] != '' && $Data['INTERCOID']) {
                $result = $result->set($dt)
                        ->where(['INTERCOID' => $Data['INTERCOID']])
                        ->update('INTERCOLOANS');
            } else {
                $dt['FCENTRY'] = $Data['USERNAME'];
                $result = $result->set($dt)->insert('INTERCOLOANS');
            }

            if ($result) {
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
	
	
	public function DeleteIntercoLoans($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = $this->db->delete('INTERCOLOANS', ['INTERCOID' => $Data['INTERCOID']]);
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
	
	public function validateBank($Data)
	{
		$query = $this->db->query("SELECT
									MAX(CURRSOURCE)CURRSOURCE,MAX(CURRTARGET)CURRTARGET
									from(
									select 
									DECODE(FCCODE,'".$Data['CURRSOURCE']."',CURRENCY)CURRSOURCE,DECODE(FCCODE,'".$Data['CURRTARGET']."',CURRENCY)CURRTARGET  
									from bank 
									where fccode IN ('".$Data['CURRSOURCE']."','".$Data['CURRTARGET']."')
									)");
		return $query->row();
	}
}