<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

class PaymentKIModel extends BaseModel {
    
    function __construct() {
        parent::__construct();
    }

    public function ShowRemainingInterestByDate($param) {
        $rmning_intq = "SELECT SUM (CALC_INTEREST) AS INTEREST,
                                SUM (IDC_INTEREST) AS INTEREST_IDC,
                                SUM (CALC_IDC) AS IDC
                        FROM FUNDS_KI_REPORT FR
                        LEFT JOIN FUNDS_MASTER FM ON FR.PK_NUMBER = FM.PK_NUMBER
                        LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FKI ON FM.UUID = FKI.UUID AND FR.TRANCHE_NUMBER = FKI.TRANCHE_NUMBER 
                        WHERE     FKI.CONTRACT_NUMBER = '"$param['CONTRACT_NUMBER']"'
                                AND END_PERIOD >
                                    (     SELECT END_PERIOD
                                            FROM FUNDSPAYMENT FP
                                                    LEFT JOIN FUNDS_DETAIL_KI_TRANCHE FKI
                                                    ON     FP.CONTRACT_NUMBER = FKI.CONTRACT_NUMBER
                                                        AND ISACTIVE = 1
                                            WHERE FP.CONTRACT_NUMBER = '"$param['CONTRACT_NUMBER']"' AND IS_PAID = 1
                                        ORDER BY PERIOD DESC
                                        FETCH FIRST 1 ROWS ONLY)
                                AND END_PERIOD < TO_DATE('"$param['DATE_COMPLETION']"', 'MM-DD-YYYY')" ;
        $rmning_int = $this->db->query($rmning_intq)->row();
        $this->db->close();
        return $rmning_int;
    }
}