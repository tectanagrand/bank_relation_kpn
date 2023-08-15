<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ReportBankBalanceModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }

    public function ShowData($param) {
        $param['COMPANYGROUP'] = "%".$param["COMPANYGROUP"]."%";
        $param['COMPANYSUBGROUP'] = "%".$param["COMPANYSUBGROUP"]."%";
        $param['COMPANY'] = "%".$param["COMPANY"]."%";
        $param['BANKCODE'] = "%".$param["BANKCODE"]."%";

        $SQL = "SELECT BB.BANKCODE, B.FCNAME, B.BANKACCOUNT, BB.CURRENCY, BB.OPENING_BALANCE, BB.CASHIN, BB.CASHOUT, BB.ENDING_BALANCE, B.REMARKS
                FROM BANKBALANCE BB
                INNER JOIN BANK B 
                        ON BB.BANKCODE = B.FCCODE
                INNER JOIN COMPANY C 
                        ON BB.COMPANY = C.ID 
                WHERE BB.PERIOD_YEAR = ?
                AND BB.PERIOD_MONTH = ?
                AND B.COMPANYGROUP LIKE ?
                AND B.COMPANYSUBGROUP LIKE ?
                AND BB.COMPANY LIKE ?
                AND BB.BANKCODE LIKE ?
                ORDER BY BB.BANKCODE";
        $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param['COMPANYGROUP'], $param['COMPANYSUBGROUP'], $param['COMPANY'], $param['BANKCODE']])->result();
        // var_dump($SQL);exit();
        $this->db->close();
        return $result;
    }

}