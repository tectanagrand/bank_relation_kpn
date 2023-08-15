<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ReportFacilityModel extends BaseModel
{

    function __construct()
    {
        parent::__construct();
    }

    public function generate_query_datatable($param) {
        $columnSearch = ['PK_NUMBER', 'CONTRACT_NUMBER', 'COMPANYNAME', 'CREDIT_TYPE', 'SUB_CREDIT_TYPE' ] ;
        $columnOrder = ['ID','PK_NUMBER', 'CONTRACT_NUMBER', 'COMPANYNAME', 'CREDIT_TYPE', 'SUB_CREDIT_TYPE' ] ;
        $order = ['ID' => 'ASC'] ;
        $whereclause = '' ;
        $searchterm = $param['search']['value'] ;
        $i = 0 ;
        //make search value query
        foreach ($columnSearch as $item) {
            if($param['search']['value']) {
                if($i==0) {
                    $whereclause .= "WHERE ( $item LIKE UPPER('%$searchterm%') ESCAPE '!'" ;
                }
                else {
                    $whereclause .= "OR $item LIKE UPPER('%$searchterm%') ESCAPE '!'" ;
                }
                if(count($columnSearch) - 1 == $i) {
                    $whereclause .= ") " ;
                }
            }
            $i++ ;
        }

        //make order by query
        if(isset($param['order'])) {
            $orderterm = $columnOrder[$param['order']['0']['column']] ;
            $ordertype = $param['order']['0']['dir'] ;
            $whereclause .= "ORDER BY $orderterm $ordertype " ;
        } else {
            $orderterm = key($order) ;
            // var_dump(key($order));
            $ordertype = $order[$orderterm];
            $whereclause .= "ORDER BY $orderterm $ordertype " ;
        }

        return $whereclause ;
    }

    public function ShowData($param) {
        ini_set('display_errors', 'On');
        $WHERE = '' ;
        $COND = [];
        $sside = $this->generate_query_datatable($param); 
        // var_dump($sside) ;exit ;
        $COMPANY = $param['COMPANY'] ;
        $SUBCREDITTYPE = $param['SUBCREDITTYPE'];
        $FDATE = $param['FDATE'];
        $TDATE = $param['TDATE'] ;

        if($COMPANY != '0') {
            array_push($COND, "CID = '$COMPANY'");
        }
        if($SUBCREDITTYPE != '0') {
            array_push($COND, "CREDIT_TYPE = '$SUBCREDITTYPE'");
        }
        if($FDATE != '0' && $FDATE != '' && $FDATE != NULL) {
            array_push($COND, "DOCDATE >= TO_DATE('$FDATE', 'mm-dd-yyyy') OR MATURITY_DATE >= TO_DATE('$FDATE', 'mm-dd-yyyy')") ;
        }
        if($TDATE != '0' && $TDATE != '' && $TDATE != NULL) {
            array_push($COND, "MATURITY_DATE <= TO_DATE('$TDATE', 'mm-dd-yyyy') OR DOCDATE >= TO_DATE('$TDATE', 'mm-dd-yyyy')") ;
        }
        for($i = 0 ; $i < sizeof($COND) ; $i++) {
            if($i == 0) {
                $WHERE .= 'WHERE ' ;
            }
            $WHERE .= $COND[$i] ;
            if($i >= 0 && $i != (sizeof($COND) -1) ) {
                $WHERE .= " AND " ;
            }
        }
        // var_dump($COND) ;
        //$URL = $param['URL'];
        $q = " SELECT * FROM (
                    SELECT FM.PK_NUMBER,
                    ROW_NUMBER() OVER (ORDER BY FM.PK_NUMBER) ID,
                    C.COMPANYNAME || ' - ' || C.COMPANYCODE AS COMPANYNAME,
                    FM.UUID,
                    FM.KI_TYPE,
                    B.FCNAME,
                    C.ID AS CID,
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
                    WHEN FDW.SUB_CREDIT_TYPE = 'WA' THEN FDW.MATURITY_DATE
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AP' THEN FDW.MATURITY_DATE
                    WHEN FDW.SUB_CREDIT_TYPE = 'KMK_SCF_AR' THEN FDW.MATURITY_DATE
                    WHEN FM.SUB_CREDIT_TYPE = 'BD' THEN FDR.MATURITY_DATE
                    WHEN FM.SUB_CREDIT_TYPE = 'RK' THEN FDR.MATURITY_DATE
                    WHEN FM.SUB_CREDIT_TYPE = 'TL' THEN FDR.MATURITY_DATE
                    ELSE FDK.MATURITY_DATE
                    END
                    AS MATURITY_DATE,
                    CASE
                    WHEN FDW.SUB_CREDIT_TYPE IS NOT NULL THEN FDW.SUB_CREDIT_TYPE
                    ELSE FM.SUB_CREDIT_TYPE
                    END
                    AS SUB_CREDIT_TYPE,
                    FM.CREDIT_TYPE
            FROM FUNDS_MASTER FM
                    LEFT JOIN COMPANY C ON C.ID = FM.COMPANY
                    LEFT JOIN BANK B ON B.FCCODE = FM.BANK
                    LEFT JOIN (SELECT FA.UUID,
                                    FA.SUB_CREDIT_TYPE,
                                    FA.AMOUNT_LIMIT,
                                    FA.DOCDATE,
                                    FA.MATURITY_DATE,
                                    FA.CONTRACT_NUMBER
                                FROM FUNDS_DETAIL_WA FA
                                WHERE FA.IS_ACC = '1' AND FA.ISACTIVE = '1') FDW
                    ON FDW.UUID = FM.UUID
                    LEFT JOIN (SELECT FR.UUID,
                                    FR.SUB_CREDIT_TYPE,
                                    FR.AMOUNT_LIMIT,
                                    FR.DOCDATE,
                                    FR.MATURITY_DATE,
                                    FR.CONTRACT_NUMBER
                                FROM FUNDS_DETAIL_RK FR
                                WHERE FR.IS_ACC = '1' AND FR.ISACTIVE = '1') FDR
                    ON FDR.UUID = FM.UUID
                    LEFT JOIN (SELECT FR.UUID,
                                    FR.SUB_CREDIT_TYPE,
                                    FR.DOCDATE,
                                    FR.MATURITY_DATE,
                                    FDR.CONTRACT_NUMBER
                                FROM FUNDS_DETAIL_KI FR
                                    LEFT JOIN
                                    (  SELECT UUID,
                                                MAX (CONTRACT_NUMBER) AS CONTRACT_NUMBER
                                        FROM FUNDS_DETAIL_KI_TRANCHE
                                        WHERE ISACTIVE = '1'
                                    GROUP BY UUID) FDR
                                        ON FR.UUID = FDR.UUID
                                WHERE FR.IS_ACC = '1' AND FR.ISACTIVE = '1') FDK
                    ON FDK.UUID = FM.UUID
                    INNER JOIN COMPANY C ON FM.COMPANY = C.ID WHERE FM.ISACTIVE = '1') ".$WHERE ;
        $qcountall = $this->db->query($q);
        $countall = $qcountall->num_rows();
        //make limit query

        $start = $param['start'] ;
        $length = $param['length'] ;
        $limitclause = " OFFSET $start ROWS FETCH NEXT $length ROWS ONLY " ;
        $qcountfiltered = "SELECT * FROM (".$q.") ".$sside ;
        $qsside = "SELECT * FROM (".$q.") ".$sside.$limitclause ;
        // var_dump($qsside) ;
        $result = $this->db->query($qsside)->result();
        $countfiltered =  $this->db->query($qcountfiltered)->num_rows();
        $return = [$countall, $countfiltered, $result] ;
        // var_dump($this->db->last_query()); exit;
        $this->db->close();
        return $return;
    }
}
