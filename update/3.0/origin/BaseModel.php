<?php

class BaseModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    protected $fillable;
//    Access User 
    protected $USERS_TAB = "USERS_TAB";
    protected $USER_GROUP = "USER_GROUP";
    protected $USER_DEPART = "USER_DEPART";
    protected $MSTMENU = "MSTMENU";
    protected $MENU_ACCESS = "MENU_ACCESS";
//    Master Data
    protected $DEPARTMENT = "DEPARTMENT";
    protected $COMPANY = "COMPANY";
    protected $REGION = "REGION";
    protected $REGIONGROUP = "REGIONGROUP";
    protected $SUPPLIER = "SUPPLIER";
//    --> General
    protected $COMPANY_GROUP = "COMPANY_GROUP";
    protected $COMPANY_SUBGROUP = "COMPANY_SUBGROUP";
    protected $COMPANY_DEPART = "COMPANY_DEPART";
    protected $COMPANY_EXTSYS = "COMPANY_EXTSYS";
    protected $BUSINESSUNIT = "BUSINESSUNIT";
    protected $BUSINESSUNIT_EXTSYS = "BUSINESSUNIT_EXTSYS";
    protected $BANK = "BANK";
    protected $MATERIAL = "MATERIAL";
    protected $DEPARTMENT_CATEGORY = "DEPARTMENT_CATEGORY";
//     
//    
//    Process
    protected $CF_TRANSACTION = 'CF_TRANSACTION';
    protected $CF_TRANSACTION_DET = 'CF_TRANSACTION_DET';
    protected $FORECAST_FIX = 'FORECAST_FIX';
    protected $FORECAST_FIX_TEMP = 'FORECAST_FIX_TEMP';
    protected $FORECASTDANA = 'FORECASTDANA';
    protected $FORECAST = 'FORECAST';
    protected $FORECASTDET = 'FORECASTDET';
    protected $FORECASTDETAIL = 'FORECASTDETAIL';
    protected $FORECAST_VALIDATION = 'FORECAST_VALIDATION';
    protected $FORECAST_VALIDATION_HISTORY = 'FORECAST_VALIDATION_HISTORY';
    protected $PAYMENT = 'PAYMENT';
    protected $PAYMENT_OTHER = 'PAYMENT_OTHER';
    
    protected $DEPARTBUDGET = 'DEPARTBUDGET';
//    
    protected $EXTSYSTEM = "EXTSYSTEM";
    protected $SETTING_WEEK = "SETTING_WEEK";
//    Template Upload
    protected $TEMP_UPLOAD_PAYMENT = "TEMP_UPLOAD_PAYMENT";
    protected $TEMP_UPLOAD_API = "TEMP_UPLOAD_API";
    protected $TEMP_UPLOAD_PO_FOR_TODAY = "TEMP_UPLOAD_PO_FOR_TODAY";
    protected $TEMP_UPLOAD_PO_FOR_MANUAL = "TEMP_UPLOAD_PO_FOR_MANUAL";
    protected $TEMP_UPLOAD_PAYMENT_OTHERS = "TEMP_UPLOAD_PAYMENT_OTHERS";
    protected $TEMP_UPLOAD_INTERCOLOANS = "TEMP_UPLOAD_INTERCOLOANS";
    protected $TEMP_UPLOAD_PO = "TEMP_UPLOAD_PO";
    protected $TEMPPO1 = "TEMPPO1";
    protected $TEMPPO2 = "TEMPPO2";
//    Code Master (Table Parameter)
    protected $BMCODEMASTER = "BMCODEMASTER";
    protected $BMCODEDETAIL = "BMCODEDETAIL";
//    Setting 
    protected $DOCTYPE = "DOCTYPE";
    protected $BANKBALANCE = "BANKBALANCE";
    protected $PERIODCONTROL = "PERIODCONTROL";
    protected $CURS = "CURS";

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

    public function DtDepart($USERNAME) {
        $this->fillable = ["UD.DEPARTMENT", "DP.FCNAME AS DEPARTEMENTNAME"];
        $result = $this->db->select($this->fillable)
                        ->from("$this->USER_DEPART UD")
                        ->join("$this->DEPARTMENT DP", 'DP.FCCODE = UD.DEPARTMENT', 'inner')
                        ->where(['UD.FCCODE' => $USERNAME])
                        ->order_by('DP.FCNAME')
                        ->get()->result();
        return $result;
    }

    public function CekBankAmount($PERIOD, $YEAR, $MONTH, $BANKCODE) {
        $AMOUNT = 0;
        $SQL = "SELECT BB.PERIOD_YEAR, BB.PERIOD_MONTH, BB.BANKCODE, BB.OPENING_BALANCE, NVL(PY.AMOUNTUSE, 0) AS AMOUNTUSE, (BB.OPENING_BALANCE - NVL(PY.AMOUNTUSE, 0)) AS AMOUNTNOW
                  FROM $this->BANKBALANCE BB
                  LEFT JOIN (SELECT PY.BANKCODE, SUM(PY.AMOUNTUSE) AS AMOUNTUSE
                               FROM (SELECT PY.BANKCODE, (DECODE(PY.CASHFLOWTYPE, 0, -1, 1) * PY.AMOUNT) AS AMOUNTUSE
                                       FROM $this->PAYMENT PY
                                      WHERE PY.ISACTIVE = 1
                                        AND PY.DATERELEASE >= TO_DATE(CONCAT(?, '01'), 'yyyymmdd')
                                        AND PY.BANKCODE = ?
                                      UNION ALL
                                     SELECT PO.BANKCODE, (DECODE(PO.CASHFLOWTYPE, 0, -1, 1) * PO.AMOUNT) AS AMOUNTUSE
                                       FROM $this->PAYMENT_OTHER PO
                                      WHERE PO.ISACTIVE = 1
                                        AND PO.DATERELEASE >= TO_DATE(CONCAT(?, '01'), 'yyyymmdd')
                                        AND PO.BANKCODE = ?
                                      UNION ALL
                                     SELECT IC.BANKSOURCE, IC.SOURCEAMOUNT AS AMOUNTPAID
                                       FROM INTERCOLOANS IC
                                      WHERE IC.ISACTIVE = 1
                                        AND IC.DATERELEASE >= TO_DATE(CONCAT(?, '01'), 'yyyymmdd')
                                        AND IC.BANKSOURCE = ?
                                      UNION ALL
                                     SELECT IC.BANKTARGET, (-1 * IC.AMOUNT) AS AMOUNTPAID
                                       FROM INTERCOLOANS IC
                                      WHERE IC.ISACTIVE = 1
                                        AND IC.DATERELEASE >= TO_DATE(CONCAT(?, '01'), 'yyyymmdd')
                                        AND IC.BANKTARGET = ?) PY
              GROUP BY PY.BANKCODE) PY
                         ON PY.BANKCODE = BB.BANKCODE
                 WHERE BB.PERIOD_YEAR = ?
                   AND BB.PERIOD_MONTH = ?
                   AND BB.BANKCODE = ?";
         $result = $this->db->query($SQL, [
          $PERIOD, $BANKCODE, $PERIOD, $BANKCODE,
          $PERIOD, $BANKCODE, $PERIOD, $BANKCODE,
          $YEAR, $MONTH, $BANKCODE
          ])->result();
          foreach ($result as $values) {
          $AMOUNT = $values->AMOUNTNOW;
          } 
        // $AMOUNT = 99999999999999;
        return $AMOUNT;
    }

    public function amountBalance ($amtModals, $amtFWD, $amtUserIns) {
        // var_dump("in func amountBalance");
        if ($amtModals != 0) {
            $amtFWD -= $amtModals ;
            $amtFWD += $amtUserIns ;
        } 
        else {
            $amtFWD += $amtUserIns ;
        }
        return $amtFWD ;
    }
}
