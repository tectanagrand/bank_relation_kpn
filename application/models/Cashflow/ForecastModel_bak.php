<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ForecastModel extends BaseModel {

    function __construct() {
        parent::__construct();
    }

    public function GetWeek($param) {
        $MOUNT = strtoupper(explode(' ', $param['PERIOD'])[0]);
        $YEAR = explode(' ', $param['PERIOD'])[1];
        $SQL = "SELECT WEEK, CONCAT(WEEK, CONCAT(' (', CONCAT(DATEFROM, CONCAT('-', CONCAT(DATEUNTIL, ')'))))) AS WEEKKET, MONTH
                  FROM $this->SETTING_WEEK
                 WHERE MONTHNAME = ?
                   AND YEAR = ?";
        $result = $this->db->query($SQL, [$MOUNT, $YEAR])->result();
        $this->db->close();
        return $result;
    }

    public function DtOutstanding($param) {
        $SQL = "SELECT CFT.*, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
					   CASE WHEN NVL(PAYMENT.AMOUNT, 0) >= NVL(FF.AMOUNTINV, 0) THEN
					   (CFT.AMOUNT_INCLUDE_VAT - NVL(PAYMENT.AMOUNT, 0))
                        ELSE
					   (CFT.AMOUNT_INCLUDE_VAT - NVL(FF.AMOUNTINV, 0))
                        END AMOUNTOUTSTANDING,
					   DT.CASHFLOWTYPE, 0 AS FLAG, NVL(CR.RATE, 1) AS RATE
                  FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         INNER JOIN $this->CF_TRANSACTION CFPO
                                 ON CFPO.DOCNUMBER = CFT.DOCREF
                                AND CFPO.COMPANY = CFT.COMPANY
                                AND CFPO.DOCTYPE <> 'PDO'
                         WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR') AND CFT.company || cft.docref || TO_CHAR (cft.DOCDATE, 'yyyy') NOT IN
                        (SELECT DISTINCT
                                CFT.COMPANY || CFT.DOCREF || TO_CHAR (CFT.DOCDATE, 'yyyy')
                                   DOCDATE
                           FROM FORECAST_FIX FF INNER JOIN CF_TRANSACTION CFT ON CFT.ID = FF.CFTRANSID
                          WHERE CFT.DOCNUMBER LIKE '%TMPINV%')
                         UNION ALL
                        SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
						 UNION ALL 
						 SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, CFT.INVOICEVENDORNO,
                               (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, CFT.CURRENCY
                          FROM $this->CF_TRANSACTION CFT
                         WHERE CFT.DOCTYPE = 'INV_AP_SPC' and CFT.EXTSYS ='TIPTOP'
						 ) CFT
                 INNER JOIN $this->DOCTYPE DT
                         ON DT.FCCODE = CFT.DOCTYPE
                  LEFT JOIN (SELECT CFTRANSID, SUM(DECODE(ISACTIVE, 0, AMOUNTREQUEST, NULL, 0, AMOUNTADJS)) AS AMOUNTINV
                               FROM $this->FORECAST_FIX
                              GROUP BY CFTRANSID) FF
                         ON FF.CFTRANSID = CFT.ID
                  LEFT JOIN (SELECT DISTINCT CFTRANSID
                               FROM $this->FORECAST_FIX 
                              WHERE YEAR = ?
                                AND MONTH = ?) DTFF
                         ON DTFF.CFTRANSID = CFT.ID
				  LEFT JOIN PAYMENT ON CFT.ID = PAYMENT.CFTRANSID
                 INNER JOIN $this->COMPANY C
                         ON C.ID = CFT.COMPANY
                 INNER JOIN COMPANY_SUBGROUP CSG ON CSG.FCCODE = C.COMPANY_SUBGROUP
                 INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
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
                        AND CR.CURSMONTH = ?";
        if ($param["POUTSTAND"] <> '') {
            $SQL .= " LEFT JOIN (SELECT ID FROM $this->CF_TRANSACTION 
                                 WHERE ID IN (" . $param["POUTSTAND"] . ")) DTE
                             ON DTE.ID = CFT.ID";
        }
        $SQL .= " WHERE (CFT.AMOUNT_INCLUDE_VAT - NVL(FF.AMOUNTINV, 0)) > 0 AND (CFT.AMOUNT_INCLUDE_VAT - NVL (PAYMENT.AMOUNT, 0)) > 0 
                    AND DTFF.CFTRANSID IS NULL
                    AND CFT.DEPARTMENT = ?
                    AND DT.CASHFLOWTYPE = ? ";
        $subgroup = $param['COMPANYSUBGROUP'];
        $group    = $param['COMPANYGROUP'];

        if($group != null && $group != ''){
          $SQL .= " AND BS.COMPANYGROUP = '$group' ";
        }
        if($subgroup != null && $subgroup != ''){
           $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
        }                    
        if ($param["POUTSTAND"] <> '') {
            $SQL .= " AND DTE.ID IS NULL";
        }
        $SQL .= " ORDER BY CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCNUMBER, CFT.INVOICEVENDORNO";
        $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["DEPARTMENT"], $param["CASHFLOWTYPE"]])->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

//Region Addition For Forecast Without Invoice
    public function DtOutstandingNoInv($param) {
        $SQL = "SELECT
				ID, COMPANY, BUSINESSUNIT, DOCTYPE, DOCNUMBER, VENDOR, DUEDATE, INVNO, CURRENCY, 
				MAX(AMOUNT) AMOUNT, MAX(FLAG)FLAG
				FROM
				(SELECT DISTINCT cfa.ID, COMPANY.COMPANYCODE COMPANY, BUSINESSUNIT.FCCODE BUSINESSUNIT, 
				cfa.DOCTYPE, hdr.DOCNUMBER, SUPPLIER.FCNAME VENDOR, cfa.DUEDATE, hdr.INVNO, cfa.CURRENCY, 
				(cfa.AMOUNT_INCLUDE_VAT - NVL(cfa.AMOUNT_PPH, 0)) AMOUNT, 0 AS FLAG from(
					select cf.DEPARTMENT, cf.DOCNUMBER, cfb.DOCNUMBER INVNO from CF_TRANSACTION cf 
					left join CF_TRANSACTION cfb on cf.docnumber = cfb.docref
					where 
					cf.Department = ? and cf.DOCTYPE IN('PO','SPO','SPK','STO')
				) hdr
				inner join CF_TRANSACTION cfa on hdr.DOCNUMBER = cfa.DOCNUMBER and hdr.DEPARTMENT = cfa.DEPARTMENT
				inner join COMPANY on cfa.COMPANY = COMPANY.ID
				inner join BUSINESSUNIT on cfa.BUSINESSUNIT = BUSINESSUNIT.ID
				inner join SUPPLIER on cfa.VENDOR = SUPPLIER.id
				and cfa.Department = ?
				where hdr.INVNO is null
				union all
				SELECT cfa.ID,
			   COMPANY.COMPANYCODE COMPANY,
			   BUSINESSUNIT.FCCODE BUSINESSUNIT,
			   cfa.DOCTYPE,
			   hdr.DOCNUMBER,
			   SUPPLIER.FCNAME VENDOR,
			   cfa.DUEDATE,
			   hdr.INVNO,
			   cfa.CURRENCY,
			   (nvl(hdr.amountH,0)- NVL (cfa.AMOUNT_PPH, 0) - nvl(hdr.amountD,0)) AMOUNT,
			   0 AS FLAG
		  FROM (SELECT cf.DEPARTMENT, cf.DOCNUMBER, '' INVNO, cf.AMOUNT_INCLUDE_VAT amountH, 
				  (select nvl(sum(AMOUNT_INCLUDE_VAT),0) from CF_TRANSACTION where docref = cf.DOCNUMBER ) amountD
				  FROM CF_TRANSACTION cf 
				  WHERE
				 cf.Department = ? AND cf.DOCTYPE IN ('PO', 'SPO', 'SPK','STO')
				) hdr
			   INNER JOIN CF_TRANSACTION cfa ON hdr.DOCNUMBER = cfa.DOCNUMBER AND hdr.DEPARTMENT = cfa.DEPARTMENT
			   INNER JOIN COMPANY ON cfa.COMPANY = COMPANY.ID
			   INNER JOIN BUSINESSUNIT ON cfa.BUSINESSUNIT = BUSINESSUNIT.ID
			   INNER JOIN SUPPLIER ON cfa.VENDOR = SUPPLIER.id AND cfa.Department = ?
			   where 
			   (nvl(hdr.amountH,0)- NVL (cfa.AMOUNT_PPH, 0) - nvl(hdr.amountD,0)) > 1)
			   GROUP BY ID, COMPANY, BUSINESSUNIT, DOCTYPE, DOCNUMBER, VENDOR, DUEDATE, INVNO, CURRENCY";
		// var_dump($SQL);exit();
		$result = $this->db->query($SQL, [$param["DEPARTMENT"],$param["DEPARTMENT"],$param["DEPARTMENT"],$param["DEPARTMENT"]])->result();
        // var_dump($this->db->last_query());exit();
		$this->db->close();
		
        return $result;
    }
	
	public function AddDummyInv($param){
		 try {
            $this->db->trans_begin();
            $result = FALSE;
			
			$SQL =  "INSERT INTO CF_TRANSACTION
										select
										LOWER (SYS_GUID ()) ID,
										CFT.EXTSYS,
										CFT.COMPANY,
										CFT.BUSINESSUNIT,
										CFT.DEPARTMENT,
										'INV' DOCTYPE,
										'TMPINV-'|| CFT.DOCNUMBER DOCNUMBER,
										To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy') DOCDATE,
										CFT.DOCNUMBER DOCREF,
										CFT.VENDOR,
										CFT.TRANS_LOC,
										To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+30 BASELINEDATE,
										CFT.PAYTERM,
										To_date(to_char(sysdate,'mm/dd/yyy'),'mm/dd/yyy')+30 DUEDATE,
										'Dummy Invoice - by System !' REMARK,
										CFT.AMOUNT_INCLUDE_VAT - nvl(CFT.AMOUNT_PPH,0) - 
										(select nvl(sum(AMOUNT_INCLUDE_VAT),0) from CF_TRANSACTION where DOCREF = CFT.DOCNUMBER) AMNTINV,
										0 AMOUNT_PPH,
										CFT.ISACTIVE,
										CFT.FCENTRY,
										CFT.FCEDIT,
										CFT.FCIP,
										sysdate,
										CFT.LASTTIME,
										CFT.UPLOAD_REF,
										CFT.VAT,
										CFT.FAKTUR_PAJAK,
										CFT.TOTAL_BAYAR,
										CFT.CURRENCY,
										CFT.AMOUNT_PPN,
										CFT.ISADENDUM,
										CFT.RATE,
										CFT.INVOICEVENDORNO,
                    '' ITEM_STO
										from 
										CF_TRANSACTION CFT
										WHERE CFT.ID = ?";
			$result = $this->db->query($SQL, [$param['ID']]);
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Create Dummy Inv Failed !!');
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
    public function ShowDtForecast($param) {
        $subgroup = $param['COMPANYSUBGROUP'];
        $group    = $param['COMPANYGROUP'];
        $param["CASHFLOWTYPE"] = "%" . $param["CASHFLOWTYPE"] . "%";
        $SQL = "SELECT DISTINCT CFT.*, DT.CASHFLOWTYPE, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                       FF.REQUESTW1, FF.REQUESTW2, FF.REQUESTW3, FF.REQUESTW4, FF.REQUESTW5, ADJSW1, ADJSW2, ADJSW3, ADJSW4, ADJSW5, 
                       FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, FF.ISACTIVE, FF.LOCKS, 
                       (CFT.AMOUNT_INCLUDE_VAT - NVL(FO.AMOUNTINV, 0) + NVL(FF.AMOUNTINV, 0)) AS AMOUNTOUTSTANDING, NVL(CR.RATE, 1) AS RATE
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
        $ARR2 = [$param["USERNAME"], $param['YEAR'], $param['MONTH'], $param["CASHFLOWTYPE"]];
        if ($param["DEPARTMENT"] != NULL && $param["DEPARTMENT"] != '') {
            $SQL .= " AND CFT.DEPARTMENT = ?";
            $SQL1 .= " AND FD.DEPARTMENT = ?";
            array_push($ARR1, $param["DEPARTMENT"]);
            array_push($ARR2, $param["DEPARTMENT"]);
        }
        if($group != null && $group != ''){
          $SQL .= " AND BS.COMPANYGROUP = '$group'";
        }
        if($subgroup != null && $subgroup != ''){
           $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
        }
        if ($param["USERACCESS"] == "100003") {
            $SQL .= " AND FF.LOCKS = 1";
            $SQL1 .= " AND FD.LOCKS = 1";
        }
        $SQL .= " ORDER BY DT.CASHFLOWTYPE, CFT.DEPARTMENT, FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, 
                           CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER, CFT.INVOICEVENDORNO";
        $SQL1 .= " GROUP BY FD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, FD.LOCKS, FD.ISACTIVE 
                   ORDER BY FD.CASHFLOWTYPE, FD.DEPARTMENT, FD.YEAR, FD.MONTH";

        $result = $this->db->query($SQL, $ARR1)->result();
        // var_dump($this->db->last_query());exit();
        $DtRevisi = $this->db->query($SQL1, $ARR2)->result();

        if ($param["DEPARTMENT"] == "") {
            $BTNSUBMIT = 0;
        } else {
            $SQL = "SELECT * 
                      FROM AUTHORIZESTRUCTURE
                     WHERE AUTHORIZETEMPLATECODE = 'FORECAST'
                       AND AUTHORIZELEVELCODE = '900'
                       AND DEPARTMENT = ?
                       AND FCUSERCODE = ?";
            $BTNSUBMIT = $this->db->query($SQL, [$param['DEPARTMENT'], $param['USERNAME']])->num_rows();
        }

        $return = [
            "DtForecast" => $result,
            "DtRevisi" => $DtRevisi,
            "BTNSUBMIT" => $BTNSUBMIT
        ];
        $this->db->close();
        return $return;
    }

    public function SaveForecast($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
//            Cek Pengguna Forecast
            $SQL = "SELECT * FROM $this->FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            $DTCek = $this->db->query($SQL, [
                        $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']
                    ])->result();
            if (count($DTCek) > 0) {
                foreach ($DTCek as $value) {
                    if ($value->FLAG_ACTIVE == "0") {
                        throw new Exception("Data Forecast has been Closed !!!");
                    } else {
                        if ($Data["KEYSAVE"] != $value->KEYSAVE) {
                            throw new Exception("Data Forecast is used by" . $value->USERNAME . " !!!");
                        }
                    }
                }
            } else {
                throw new Exception("Data Validation Forecast Not Found !!!");
            }

//            Update Amount Forecast 0
            $SQL = "UPDATE $this->FORECAST_FIX FF
                       SET FF.AMOUNTREQUEST = 0,
                           FF.AMOUNTADJS = 0,
                           FF.FCEDIT = ?,
                           FF.LASTUPDATE = SYSDATE,
                           FF.FCIP = ?
                     WHERE FF.YEAR = ?
                       AND FF.MONTH = ?
                       AND FF.CFTRANSID IN (SELECT CFT.ID
                                              FROM (SELECT CFT.ID, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCTYPE 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     INNER JOIN $this->CF_TRANSACTION CFPO
                                                             ON CFPO.DOCNUMBER = CFT.DOCREF
                                                            AND CFPO.COMPANY = CFT.COMPANY
                                                     WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                     UNION ALL
                                                    SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
													 UNION ALL
                                                    SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE = 'INV_AP_SPC' and CFT.EXTSYS ='TIPTOP') CFT
                                             INNER JOIN $this->DOCTYPE DT
                                                     ON DT.FCCODE = CFT.DOCTYPE
                                             INNER JOIN BUSINESSUNIT BS ON BS.ID = CFT.BUSINESSUNIT
                                             WHERE CFT.DEPARTMENT = ?
                                               AND DT.CASHFLOWTYPE = ? AND BS.COMPANYGROUP = ? AND BS.COMPANY_SUBGROUP = ?)";
            $DTU = $this->db->query($SQL, [$Data['USERNAME'], $Location, $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"],$Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"]]);
            // var_dump($SQL);
//            Insert Or Update Amount Forecast
            $dt = [];
            $SQLC = "SELECT * FROM $this->FORECAST_FIX WHERE YEAR = ? AND MONTH = ? AND CFTRANSID = ? AND WEEK = ?";
            foreach ($Data['DtForecast'] AS $VALUES) {
                if ($VALUES["REQUESTW1"] > 0 || $VALUES["ADJSW1"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW1'],
                        "AMOUNTADJS" => $VALUES['ADJSW1'],
                        "PRIORITY" => $VALUES['PRIORITYW1'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W1'])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W1'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W1';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
                if ($VALUES["REQUESTW2"] > 0 || $VALUES["ADJSW2"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW2'],
                        "AMOUNTADJS" => $VALUES['ADJSW2'],
                        "PRIORITY" => $VALUES['PRIORITYW2'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W2'])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W2'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W2';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
                if ($VALUES["REQUESTW3"] > 0 || $VALUES["ADJSW3"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW3'],
                        "AMOUNTADJS" => $VALUES['ADJSW3'],
                        "PRIORITY" => $VALUES['PRIORITYW3'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W3'])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W3'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W3';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
                if ($VALUES["REQUESTW4"] > 0 || $VALUES["ADJSW4"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW4'],
                        "AMOUNTADJS" => $VALUES['ADJSW4'],
                        "PRIORITY" => $VALUES['PRIORITYW4'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W4'])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W4'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W4';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
                if ($VALUES["REQUESTW5"] > 0 || $VALUES["ADJSW5"] > 0) {
                    $dt = [
                        "AMOUNTREQUEST" => $VALUES['REQUESTW5'],
                        "AMOUNTADJS" => $VALUES['ADJSW5'],
                        "PRIORITY" => $VALUES['PRIORITYW5'],
                        "DEPARTMENT" => $VALUES['DEPARTMENT'],
                        "INVOICEVENDORNO" => $VALUES['INVOICEVENDORNO'],
                        "ISACTIVE" => $Data['FCSTATUS'],
                        "LOCKS" => $Data['FCLOCK'],
                        "FCIP" => $Location,
                        "FCEDIT" => $Data['USERNAME']
                    ];
                    $CEK1 = $this->db->query($SQLC, [$Data['YEAR'], $Data['MONTH'], $VALUES["ID"], 'W5'])->num_rows();
                    if ($CEK1 > 0) {
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->where([
                                    "YEAR" => $Data['YEAR'],
                                    "MONTH" => $Data['MONTH'],
                                    "CFTRANSID" => $VALUES["ID"],
                                    "WEEK" => 'W5'
                                ])->update($this->FORECAST_FIX);
                    } else {
                        $dt["CFTRANSID"] = $VALUES['ID'];
                        $dt["YEAR"] = $Data['YEAR'];
                        $dt["MONTH"] = $Data['MONTH'];
                        $dt["WEEK"] = 'W5';
                        $dt["FCENTRY"] = $Data['USERNAME'];
                        $result1 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set($dt)->insert($this->FORECAST_FIX);
                    }
                }
            }
//            Delete Amount Forecast 0
            $SQL = "DELETE FROM $this->FORECAST_FIX FF
                     WHERE FF.YEAR = ?
                       AND FF.MONTH = ?
                       AND FF.CFTRANSID IN (SELECT CFT.ID
                                              FROM (SELECT CFT.ID, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCTYPE
                                                      FROM $this->CF_TRANSACTION CFT
                                                     INNER JOIN $this->CF_TRANSACTION CFPO
                                                             ON CFPO.DOCNUMBER = CFT.DOCREF
                                                            AND CFPO.COMPANY = CFT.COMPANY
                                                     WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                     UNION ALL
                                                    SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
													 UNION ALL
                                                    SELECT CFT.ID,CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCTYPE
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE = 'INV_AP_SPC' and CFT.EXTSYS ='TIPTOP'
													 ) CFT
                                             INNER JOIN $this->DOCTYPE DT
                                                     ON DT.FCCODE = CFT.DOCTYPE
                                             INNER JOIN BUSINESSUNIT BS ON BS.ID = CFT.BUSINESSUNIT
                                             WHERE CFT.DEPARTMENT = ?
                                               AND DT.CASHFLOWTYPE = ? AND BS.COMPANYGROUP = ? AND BS.COMPANY_SUBGROUP = ?)
                       AND FF.AMOUNTREQUEST = 0 
                       AND FF.AMOUNTADJS = 0";
            $DDT = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"],$Data["COMPANYGROUP"], $Data["COMPANYSUBGROUP"]]);
//            Insert Or Update Data To Forecast Dana
            if ($Data['ACTION'] <> '0') {
                $SQL = "SELECT * FROM $this->FORECASTDANA WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ?";
                $Cek = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'], $Data['DEPARTMENT'], $Data["CASHFLOWTYPE"]])->num_rows();
                $dt = [
                    "LOCKS" => $Data['FCLOCK'],
                    "ISACTIVE" => $Data['FCSTATUS'],
                    "UPDATED_BY" => $Data['USERNAME'],
                    "UPDATED_LOC" => $Location
                ];
                if ($Data["CASHFLOWTYPE"] == '0') {
                    $dt["AMOUNTREQUEST"] = $Data['REQUESTINTOT'];
                    $dt["AMOUNTADJS"] = $Data['ADJSINTOT'];
                } elseif ($Data["CASHFLOWTYPE"] == '1') {
                    $dt["AMOUNTREQUEST"] = $Data['REQUESTOUTTOT'];
                    $dt["AMOUNTADJS"] = $Data['ADJSOUTTOT'];
                }
                $result2 = $this->db->set('UPDATED_AT', "SYSDATE", false);
                if ($Data['ACTION'] == '1') {
                    $dt["CSUBMIT"] = 1;
                } elseif ($Data['ACTION'] == '2') {
                    $result2 = $result2->set('CSUBMIT', "CREVISI + 1", false);
                }
                if ($Cek > 0) {
                    $result2 = $result2->set($dt)->where([
                                'DEPARTMENT' => $Data['DEPARTMENT'],
                                'YEAR' => $Data['YEAR'],
                                'MONTH' => $Data['MONTH'],
                                'CASHFLOWTYPE' => $Data['CASHFLOWTYPE']
                            ])->update($this->FORECASTDANA);
                } else {
                    $dt["DEPARTMENT"] = $Data['DEPARTMENT'];
                    $dt["YEAR"] = $Data['YEAR'];
                    $dt["MONTH"] = $Data['MONTH'];
                    $dt["CASHFLOWTYPE"] = $Data['CASHFLOWTYPE'];
                    $dt["CREATED_BY"] = $Data['USERNAME'];
                    $dt["CREATED_LOC"] = $Location;
                    $result2 = $result2->set('CREATED_AT', "SYSDATE", false)->set($dt)->insert($this->FORECASTDANA);
                }
            }
            if ($result1) {
                $result = TRUE;
                $this->db->set('UPDATED_AT', "SYSDATE", false)->set([
                    "DEPARTMENT" => $Data["DEPARTMENT"],
                    "CASHFLOWTYPE" => $Data["CASHFLOWTYPE"],
                    "COMPANYGROUP" => $Data["COMPANYGROUP"],
                    "COMPANYSUBGROUP" => $Data["COMPANYSUBGROUP"],
                    "YEAR" => $Data["YEAR"],
                    "MONTH" => $Data["MONTH"],
                    "USERNAME" => $Data["USERNAME"],
                    "REMARK" => "Close Edit Data Forecast",
                    "UPDATED_BY" => $Data["USERNAME"],
                    "UPDATED_LOC" => $Location
                ])->insert($this->FORECAST_VALIDATION_HISTORY);
                $dt = [
                    "USERNAME" => "",
                    "UPDATED_BY" => $Data["USERNAME"],
                    "UPDATED_LOC" => $Location,
                    "FLAG_ACTIVE" => 0,
                    "KEYSAVE" => ""
                ];
                $this->db->set('UPDATED_AT', "SYSDATE", false)->set($dt)->where([
                    'DEPARTMENT' => $Data['DEPARTMENT'],
                    'YEAR' => $Data['YEAR'],
                    'MONTH' => $Data['MONTH'],
                    "COMPANYGROUP" => $Data["COMPANYGROUP"],
                    "COMPANYSUBGROUP" => $Data["COMPANYSUBGROUP"],
                    'CASHFLOWTYPE' => $Data['CASHFLOWTYPE']
                ])->update($this->FORECAST_VALIDATION);
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

    public function SaveRA($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->FORECASTDANA WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ?";
            $Cek = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'], $Data['DEPARTMENT']])->result();
            if (count($Cek) > 0) {
                $CSubmit = 0;
                foreach ($Cek as $values) {
                    $CSubmit = $values->CSUBMIT;
                }
                if ($CSubmit == 1) {
                    $SQL = "UPDATE $this->FORECAST_FIX FF
                       SET FF.ISACTIVE = ?,
                           FF.AMOUNTADJS = FF.AMOUNTREQUEST,
                           FF.LOCKS = 0
                     WHERE FF.YEAR = ?
                       AND FF.MONTH = ?
                       AND FF.CFTRANSID IN (SELECT CFT.ID
                                              FROM (SELECT CFT.ID 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     INNER JOIN $this->CF_TRANSACTION CFPO
                                                             ON CFPO.DOCNUMBER = CFT.DOCREF
                                                            AND CFPO.COMPANY = CFT.COMPANY
                                                     WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                     UNION ALL
                                                    SELECT CFT.ID
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE IN('PDO','PDO_IN')) CFT
                                             WHERE CFT.DEPARTMENT = ?)";
                } else {
                    $SQL = "UPDATE $this->FORECAST_FIX FF
                       SET FF.ISACTIVE = ?,
                           FF.LOCKS = 0
                     WHERE FF.YEAR = ?
                       AND FF.MONTH = ?
                       AND FF.CFTRANSID IN (SELECT CFT.ID
                                              FROM (SELECT CFT.ID 
                                                      FROM $this->CF_TRANSACTION CFT
                                                     INNER JOIN $this->CF_TRANSACTION CFPO
                                                             ON CFPO.DOCNUMBER = CFT.DOCREF
                                                            AND CFPO.COMPANY = CFT.COMPANY
                                                     WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                     UNION ALL
                                                    SELECT CFT.ID
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
													  UNION ALL
                                                    SELECT CFT.ID
                                                      FROM $this->CF_TRANSACTION CFT
                                                     WHERE CFT.DOCTYPE = 'INV_AP_SPC' and CFT.EXTSYS ='TIPTOP') CFT
                                             WHERE CFT.DEPARTMENT = ?)";
                }
                $result1 = $this->db->query($SQL, [$Data['RASTATUS'], $Data['YEAR'], $Data['MONTH'], $Data['DEPARTMENT']]);
                $dt = [
                    "ISACTIVE" => $Data['RASTATUS'],
                    "FCIP" => $Location,
                    "FCEDIT" => $Data['USERNAME']
                ];
                $result = $this->db->set('LASTUPDATE', "SYSDATE", false);
                if ($Data['RASTATUS'] == '1') {
                    $dt["AMOUNTREVISI"] = $Data['AMOUNTREVISI'];
                    $result = $result->set('CREVISI', "CSUBMIT", false);
                }
                if ($Data['RASTATUS'] == '2') {
                    if ($CSubmit == 1) {
                        $dt["AMOUNTAPPROVE"] = $Data['REQUESTWTOTAL'];
                    } else {
                        $dt["AMOUNTAPPROVE"] = $Data['ADJSWTOTAL'];
                    }
                }
                $result = $result->set($dt)->where([
                            'DEPARTMENT' => $Data['DEPARTMENT'],
                            'YEAR' => $Data['YEAR'],
                            'MONTH' => $Data['MONTH']
                        ])
                        ->update($this->FORECASTDANA);
            } else {
                throw new Exception('Data Request Not Found !!!');
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

    public function DtRevAppDana($param) {
        $SQL = "SELECT UD.DEPARTMENT, FD.YEAR, FD.MONTH, FD.CASHFLOWTYPE, NVL(FD.AMOUNTREQUEST, 0) AS AMOUNTREQUEST, NVL(FD.AMOUNTADJS, 0) AS AMOUNTADJS,
                       DECODE(NVL(FD.ISACTIVE, 0), 2, 0, NVL(FD.AMOUNTREVISI, 0)) AS AMOUNTREVISI, NVL(FD.AMOUNTAPPROVE, 0) AS AMOUNTAPPROVE,
                       NVL(FD.ISACTIVE, 0) AS ISACTIVE, NVL(FD.LOCKS, 0) AS LOCKS, '' AS SREVAPP
                  FROM USER_DEPART UD
                  LEFT JOIN FORECASTDANA FD
                         ON FD.DEPARTMENT = UD.DEPARTMENT
                        AND FD.YEAR = ?
                        AND FD.MONTH = ?
                 WHERE UD.FCCODE = ?";

        $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param["USERNAME"]])->result();
        // var_dump($this->db->last_query());exit();
        /* $SQL = "SELECT UD.DEPARTMENT, NVL(RA.AMOUNTREQUEST, 0) AS AMOUNTREQUEST, NVL(RA.AMOUNTADJS, 0) AS AMOUNTADJS,
          DECODE(NVL(RA.ISACTIVE, 0), 2, 0, NVL(FD.AMOUNTREVISI, 0)) AS AMOUNTREVISI, NVL(FD.AMOUNTAPPROVE, 0) AS AMOUNTAPPROVE,
          NVL(RA.ISACTIVE, 0) AS ISACTIVE, NVL(RA.LOCKS, 0) AS LOCKS
          FROM USER_DEPART UD
          LEFT JOIN FORECASTDANA FD
          ON FD.DEPARTMENT = UD.DEPARTMENT
          AND FD.YEAR = ?
          AND FD.MONTH = ?
          LEFT JOIN (SELECT CFPO.DEPARTMENT, SUM(FF.AMOUNTREQUEST) AS AMOUNTREQUEST, SUM(FF.AMOUNTADJS) AS AMOUNTADJS, MAX(FF.ISACTIVE) AS ISACTIVE, MAX(FF.LOCKS) AS LOCKS
          FROM CF_TRANSACTION CFT
          INNER JOIN FORECAST_FIX FF
          ON FF.CFTRANSID = CFT.ID
          AND FF.YEAR = ?
          AND FF.MONTH = ?
          INNER JOIN CF_TRANSACTION CFPO
          ON CFPO.DOCNUMBER = CFT.DOCREF
          WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'PDO' OR CFT.DOCTYPE = 'INV_AR')
          GROUP BY CFPO.DEPARTMENT) RA
          ON RA.DEPARTMENT = UD.DEPARTMENT
          WHERE UD.FCCODE = ?";
          $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param['YEAR'], $param['MONTH'], $param["USERNAME"]])->result(); */
        $this->db->close();
        return $result;
    }

    public function SaveRevApp($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $SQL = "SELECT * FROM $this->FORECASTDANA WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ?";
            foreach ($Data['DATA'] AS $values) {
                $Cek = $this->db->query($SQL, [$Data['YEAR'], $Data['MONTH'], $values['DEPARTMENT'], $values['CASHFLOWTYPE']])->result();
                if (count($Cek) > 0) {
                    $CSubmit = 0;
                    foreach ($Cek as $value) {
                        $CSubmit = $value->CSUBMIT;
                    }
                    if ($values['SREVAPP'] == '2') {
                        $LOCKS = 1;
                    } else {
                        $LOCKS = 0;
                    }
                    $SQL1 = "UPDATE $this->FORECAST_FIX FF
                                   SET FF.ISACTIVE = ?, ";
                    if ($CSubmit == 1) {
                        $SQL1 .= " FF.AMOUNTADJS = FF.AMOUNTREQUEST, ";
                    }
                    $SQL1 .= "          FF.LOCKS = ?
                                 WHERE FF.YEAR = ?
                                   AND FF.MONTH = ?
                                   AND FF.CFTRANSID IN (SELECT CFT.ID
                                                          FROM (SELECT CFT.ID, CFPO.DEPARTMENT, CFT.DOCTYPE 
                                                                  FROM $this->CF_TRANSACTION CFT
                                                                 INNER JOIN $this->CF_TRANSACTION CFPO
                                                                         ON CFPO.DOCNUMBER = CFT.DOCREF
                                                                        AND CFPO.COMPANY = CFT.COMPANY
                                                                 WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR')
                                                                 UNION ALL
                                                                SELECT CFT.ID, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                                  FROM $this->CF_TRANSACTION CFT
                                                                 WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
																 UNION ALL
                                                                SELECT CFT.ID, CFT.DEPARTMENT, CFT.DOCTYPE 
                                                                  FROM $this->CF_TRANSACTION CFT
                                                                 WHERE CFT.DOCTYPE = 'INV_AP_SPC' and CFT.EXTSYS ='TIPTOP') CFT
                                                         INNER JOIN $this->DOCTYPE DT
                                                                 ON DT.FCCODE = CFT.DOCTYPE
                                                         WHERE CFT.DEPARTMENT = ?
                                                           AND DT.CASHFLOWTYPE = ?)";
                    $result1 = $this->db->query($SQL1, [$values['SREVAPP'], $LOCKS, $Data['YEAR'], $Data['MONTH'], $values['DEPARTMENT'], $values['CASHFLOWTYPE']]);
                    $dt = [
                        "LOCKS" => $LOCKS,
                        "ISACTIVE" => $values['SREVAPP'],
                        "UPDATED_BY" => $Data['USERNAME'],
                        "UPDATED_LOC" => $Location
                    ];
                    $result = $this->db->set('UPDATED_AT', "SYSDATE", false);
                    if ($values['SREVAPP'] == '1') {
                        $dt["AMOUNTREVISI"] = $values['AMOUNTREVISI'];
                        $result = $result->set('CREVISI', "CSUBMIT", false);
                    } elseif ($values['SREVAPP'] == '2') {
                        if ($CSubmit == 1) {
                            $dt["AMOUNTAPPROVE"] = $values['AMOUNTREQUEST'];
                        } else {
                            $dt["AMOUNTAPPROVE"] = $values['AMOUNTADJS'];
                        }
                    }
                    $result = $result->set($dt)->where([
                                'DEPARTMENT' => $values['DEPARTMENT'],
                                'YEAR' => $Data['YEAR'],
                                'MONTH' => $Data['MONTH'],
                                'CASHFLOWTYPE' => $values['CASHFLOWTYPE']
                            ])->update($this->FORECASTDANA);
                } else {
                    throw new Exception('Data Request Not Found !!!');
                }
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Data Save Failed !!#');
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

    public function Export($Data) {
        try {
            $FCashType = "Cashflow Type : ";
            if ($Data["CASHFLOWTYPE"] == "ALL") {
                $CASHFLOWTYPE = "%%";
                $FCashType .= "All";
            } else {
                $CASHFLOWTYPE = "%" . $Data["CASHFLOWTYPE"] . "%";
                if ($Data["CASHFLOWTYPE"] == "0") {
                    $FCashType .= "Cash In";
                } elseif ($Data["CASHFLOWTYPE"] == "1") {
                    $FCashType .= "Cash Out";
                }
            }
            $subgroup = $Data['COMPANYSUBGROUP'];
            $group    = $Data['COMPANYGROUP'];
            $SQL = "SELECT DISTINCT CFT.*, DP.FCNAME AS DEPARTMENTNAME, DT.CASHFLOWTYPE, C.COMPANYCODE, C.COMPANYNAME, BS.FCCODE AS BUSINESSUNITCODE, 
                           BS.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME,
                           FF.REQUESTW1, FF.REQUESTW2, FF.REQUESTW3, FF.REQUESTW4, FF.REQUESTW5, ADJSW1, ADJSW2, ADJSW3, ADJSW4, ADJSW5, 
                           FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, FF.ISACTIVE, FF.LOCKS, 
                           (CFT.AMOUNT_INCLUDE_VAT - NVL(FO.AMOUNTINV, 0) + NVL(FF.AMOUNTINV, 0)) AS AMOUNTOUTSTANDING, NVL( CR.RATE, 1 ) AS RATE 
                      FROM (SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFPO.DEPARTMENT, CFT.DOCNUMBER, CFT.DOCREF, CFT.VENDOR, 
                                   TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,CFT.INVOICEVENDORNO, CFT.CURRENCY,
                                   (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, 
                                   CFPO.DOCTYPE, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE
                              FROM $this->CF_TRANSACTION CFT
                             INNER JOIN $this->CF_TRANSACTION CFPO
                                     ON CFPO.DOCNUMBER = CFT.DOCREF
                                    AND CFPO.COMPANY = CFT.COMPANY
                                    AND CFPO.DOCTYPE <> 'PDO'
                             WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'INV_AR' OR CFT.DOCTYPE = 'LEASING')
                             UNION ALL
                            SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                   TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,CFT.INVOICEVENDORNO, CFT.CURRENCY,
                                   (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, 
                                   TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE
                              FROM $this->CF_TRANSACTION CFT
                             WHERE CFT.DOCTYPE IN('PDO','PDO_IN')
							 UNION ALL
                            SELECT CFT.ID, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DEPARTMENT, CFT.DOCREF, CFT.DOCNUMBER, CFT.VENDOR, 
                                   TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE,CFT.INVOICEVENDORNO, CFT.CURRENCY,
                                   (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNT_INCLUDE_VAT, CFT.DOCTYPE, 
                                   TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE
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
                      LEFT JOIN $this->DEPARTMENT DP
                             ON DP.FCCODE = CFT.DEPARTMENT
                     INNER JOIN $this->DOCTYPE DT
                             ON DT.FCCODE = CFT.DOCTYPE
                     INNER JOIN $this->COMPANY C
                             ON C.ID = CFT.COMPANY
                     INNER JOIN COMPANY_SUBGROUP CSG ON CSG.FCCODE = C.COMPANY_SUBGROUP
                     INNER JOIN COMPANY_GROUP CG ON CG.FCCODE = CSG.FCCODE_GROUP
                     INNER JOIN $this->BUSINESSUNIT BS
                             ON BS.ID = CFT.BUSINESSUNIT
                            AND BS.COMPANY = CFT.COMPANY
                      LEFT JOIN $this->SUPPLIER S
                             ON S.ID = CFT.VENDOR
                     INNER JOIN $this->USER_DEPART UD
                             ON UD.FCCODE = ?
                            AND UD.DEPARTMENT = CFT.DEPARTMENT 
                            LEFT JOIN CURS CR ON CR.CURSCODE = CFT.CURRENCY 
                AND CR.CURSYEAR = '".$Data["YEAR"]."' AND CR.CURSMONTH =  '".$Data["MONTH"]."' WHERE DT.CASHFLOWTYPE LIKE ?";
            $ParamW = [$Data["YEAR"], $Data["MONTH"], $Data["USERNAME"], $CASHFLOWTYPE];
            if ($Data["DEPARTMENT"] == "ALL") {
                $FDepartment = "Department : All Department";
            } else {
                $FDepartment = "Department : " . $Data["DEPARTMENT"];
                $SQL .= " AND CFT.DEPARTMENT = ?";
                array_push($ParamW, $Data["DEPARTMENT"]);
            }
            if($group != null && $group != ''){
              $SQL .= " AND BS.COMPANYGROUP = '$group' ";
            }
            if($subgroup != null && $subgroup != ''){
               $SQL .= " AND BS.COMPANY_SUBGROUP = '".$subgroup."'";
            }
            $SQL .= " ORDER BY DT.CASHFLOWTYPE, CFT.DEPARTMENT, FF.PRIORITYW1, FF.PRIORITYW2, FF.PRIORITYW3, FF.PRIORITYW4, FF.PRIORITYW5, 
                               CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER";
            $result = $this->db->query($SQL, $ParamW)->result();
            // var_dump($this->db->last_query());exit();
            $FPeriod = "Period Forecast : " . Carbon::parse($Data["YEAR"] . substr("0" . $Data["MONTH"], -2) . "01")->format('Y-M');
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');

            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("IT GAMA")
                    ->setLastModifiedBy("IT GAMA")
                    ->setTitle("Report Forecast")
                    ->setSubject("Report Forecast")
                    ->setDescription("Data Document in System $FDepartment, $FPeriod, $FCashType, $GExport")
                    ->setKeywords("Report Forecast")
                    ->setCategory("Report Forecast");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Data');
            $i = 1;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(38);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(9.5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(9.5);

            $StyleDefault = [
                'bold' => FALSE,
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Calibri'
            ];
            $StyleBold = [
                'bold' => TRUE,
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Calibri'
            ];
            $StyleCenterAll = [
                'vertical' => 'center',
                'horizontal' => 'center'
            ];
            $StyleRight = [
                'vertical' => 'center',
                'horizontal' => 'right'
            ];
            $StyleBorder = [
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('argb' => '000000')
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => array('argb' => '000000')
                ]
            ];

            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "Data Document In System");
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                'font' => [
                    'bold' => TRUE,
                    'color' => array('rgb' => '000000'),
                    'size' => 14,
                    'name' => 'Calibri'
                ]
            ]);
            $i++;
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':A' . ($i + 2))->applyFromArray(['font' => $StyleDefault]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FDepartment);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FCashType);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $GExport);
            $i++;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AB' . ($i + 2))->applyFromArray([
                'font' => $StyleBold, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
            ]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'NO');
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':A' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'TYPE');
            $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':B' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'DEPARMNET');
            $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':C' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'COMPANY');
            $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':D' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'BUSINESS UNIT');
            $objPHPExcel->getActiveSheet()->mergeCells('E' . $i . ':E' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'VENDOR');
            $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':F' . ($i + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'DOCUMENT');
            $objPHPExcel->getActiveSheet()->mergeCells('G' . $i . ':M' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'WEEK');
            $objPHPExcel->getActiveSheet()->mergeCells('N' . $i . ':AB' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'TYPE');
            $objPHPExcel->getActiveSheet()->mergeCells('G' . $i . ':G' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'NUMBER');
            $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':H' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'INVOICE');
            $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':I' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'DUE DATE');
            $objPHPExcel->getActiveSheet()->mergeCells('J' . $i . ':J' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'CURRENCY');
            $objPHPExcel->getActiveSheet()->mergeCells('K' . $i . ':K' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'AMOUNT');
            $objPHPExcel->getActiveSheet()->mergeCells('L' . $i . ':M' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->mergeCells('N' . $i . ':P' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->mergeCells('Q' . $i . ':S' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->mergeCells('T' . $i . ':V' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->mergeCells('W' . $i . ':Y' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, 'W5');
            $objPHPExcel->getActiveSheet()->mergeCells('Z' . $i . ':AB' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'SOURCE');
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'OUTSTANDING');
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, 'PRIORITY');
            $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, 'REQUEST');
            $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, 'ADJUSTED');
            $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, 'PRIORITY');
            $i++;

            if (count($result) > 0) {
                $iDtAwal = $i;
                $iLoop = 0;
                $icashin = $i;
                $No = 1;
                $CTYPE1 = "";
                foreach ($result as $values) {
                    if ($values->CASHFLOWTYPE == '0') {
                        $CTYPE = "CASH IN";
                    } else {
                        $CTYPE = "CASH OUT";
                    }
                    if ($iDtAwal == $i) {
                        $CTYPE1 = $CTYPE;
                        $iLoop = $i;
                    }
                    if ($CTYPE1 != $CTYPE) {
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $iLoop . ':AB' . ($i - 1))->applyFromArray([
                            'font' => $StyleDefault, 'borders' => $StyleBorder
                        ]);
                        $objPHPExcel->getActiveSheet()->getStyle('J' . $iLoop . ':J' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);

                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "TOTAL $CTYPE1 :");
                        $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':M' . $i);
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, "=SUM(N$iLoop:N" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=SUM(O$iLoop:O" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, "=SUM(Q$iLoop:Q" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=SUM(R$iLoop:R" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, "=SUM(T$iLoop:T" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('T' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=SUM(U$iLoop:U" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, "=SUM(W$iLoop:W" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('W' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=SUM(X$iLoop:X" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, "=SUM(Z$iLoop:Z" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('Z' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=SUM(AA$iLoop:AA" . ($i - 1) . ")");
                        $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AB' . $i)->applyFromArray([
                            'font' => $StyleDefault, 'borders' => $StyleBorder
                        ]);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                            'font' => $StyleBold, 'alignment' => $StyleRight
                        ]);
                        $icashin = $i;
                        $CTYPE1 = $CTYPE;
                        $No = 1;
                        $i++;
                        $$iLoop = $i;
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $No);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $CTYPE);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $values->DEPARTMENTNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $values->COMPANYNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $values->BUSINESSUNITNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $values->VENDORNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $values->INVOICEVENDORNO);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $i, $values->DOCREF, DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $i, $values->DOCNUMBER, DataType::TYPE_STRING);
                    if ($values->DUEDATE != NULL && $values->DUEDATE != "") {
                        $DtDate = explode('/', $values->DUEDATE);
                        $XlsTime = gmmktime(0, 0, 0, intval($DtDate[1]), intval($DtDate[2]), intval($DtDate[0]));
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, Date::PHPToExcel($XlsTime));
                        $objPHPExcel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $values->CURRENCY);
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $values->AMOUNT_INCLUDE_VAT);
                    $objPHPExcel->getActiveSheet()->getStyle('L' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $values->AMOUNTOUTSTANDING);
                    $objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $values->REQUESTW1);
                    $objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $values->ADJSW1);
                    $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $values->PRIORITYW1);
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $values->REQUESTW2);
                    $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $values->ADJSW2);
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $values->PRIORITYW2);
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $values->REQUESTW3);
                    $objPHPExcel->getActiveSheet()->getStyle('T' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $values->ADJSW3);
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $values->PRIORITYW3);
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $values->REQUESTW4);
                    $objPHPExcel->getActiveSheet()->getStyle('W' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $values->ADJSW4);
                    $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $values->PRIORITYW4);
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $values->REQUESTW5);
                    $objPHPExcel->getActiveSheet()->getStyle('Z' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, $values->ADJSW5);
                    $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, $values->PRIORITYW5);
                    $i++;
                    $No++;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A' . $iLoop . ':AB' . ($i - 1))->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                $objPHPExcel->getActiveSheet()->getStyle('J' . $iLoop . ':J' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "TOTAL $CTYPE1 :");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':M' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, "=SUM(N$iLoop:N" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=SUM(O$iLoop:O" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, "=SUM(Q$iLoop:Q" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=SUM(R$iLoop:R" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                // $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, "=SUM(T$iLoop:T" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, "=SUM(T$iLoop:T" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('T' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=SUM(U$iLoop:U" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, "=SUM(W$iLoop:W" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('W' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=SUM(X$iLoop:X" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, "=SUM(Z$iLoop:Z" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('Z' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=SUM(AA$iLoop:AA" . ($i - 1) . ")");
                $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AB' . $i)->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                    'font' => $StyleBold, 'alignment' => $StyleRight
                ]);
                $i++;

                if ($icashin != $iDtAwal) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "GRAND TOTAL :");
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':M' . $i);
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, "=N$icashin - N" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, "=O$icashin - O" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, "=Q$icashin - Q" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, "=R$icashin - R" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, "=T$icashin - T" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('T' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, "=U$icashin - U" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, "=W$icashin - W" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('W' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, "=X$icashin - X" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, "=Z$icashin - Z" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('Z' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, "=AA$icashin - AA" . ($i - 1));
                    $objPHPExcel->getActiveSheet()->getStyle('AA' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AB' . $i)->applyFromArray([
                        'font' => $StyleDefault, 'borders' => $StyleBorder
                    ]);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray([
                        'font' => $StyleBold, 'alignment' => $StyleRight
                    ]);
                }
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "No Data");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':AB' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':AB' . $i)->applyFromArray([
                    'font' => $StyleDefault, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
                ]);
                $i++;
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

    public function CekValidation($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $Key = $Data["DEPARTMENT"] . Carbon::now('Asia/Jakarta')->format('YmdHis');
            $Key1 = "";
            $USERNAME = "";
            $FLAG_ACTIVE = 0;
            $SQL = "SELECT * FROM $this->FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            $DTCek = $this->db->query($SQL, [
                        $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']
                    ])->result();
            if (count($DTCek) > 0) {
                foreach ($DTCek as $value) {
                    $FLAG_ACTIVE = $value->FLAG_ACTIVE;
                    $USERNAME = $value->USERNAME;
                    $Key1 = $value->KEYSAVE;
                }
                if ($FLAG_ACTIVE == '1') {
//                    Untuk Edit Forecast sama user bisa di buka
                    /*if ($USERNAME == $Data["USERNAME"]) {
                        $Key = $Key1;
                    } else {*/
					/*edit by iwa */
                    if ($this->session->userdata('username') != $USERNAME ){						
							throw new Exception("Data is used by $USERNAME !");						
						}
					/*end edit by iwa */
//                    }
                }
            }
            $dt = [
                "USERNAME" => $Data["USERNAME"],
                "UPDATED_BY" => $Data["USERNAME"],
                "UPDATED_LOC" => $Location,
                "FLAG_ACTIVE" => 1,
                "KEYSAVE" => $Key
            ];
            $result = $this->db->set('UPDATED_AT', "SYSDATE", false);
            if (count($DTCek) > 0) {
                $result = $result->set($dt)->where([
                            'DEPARTMENT' => $Data['DEPARTMENT'],
                            'YEAR' => $Data['YEAR'],
                            'MONTH' => $Data['MONTH'],
                            'CASHFLOWTYPE' => $Data['CASHFLOWTYPE'],
                            'COMPANYGROUP' => $Data['COMPANYGROUP'],
                            'COMPANYSUBGROUP' => $Data['COMPANYSUBGROUP']
                        ])->update($this->FORECAST_VALIDATION);
            } else {
                $dt["DEPARTMENT"] = $Data["DEPARTMENT"];
                $dt["CASHFLOWTYPE"] = $Data["CASHFLOWTYPE"];
                $dt["YEAR"] = $Data["YEAR"];
                $dt["MONTH"] = $Data["MONTH"];
                $dt["CREATED_BY"] = $Data["USERNAME"];
                $dt["CREATED_LOC"] = $Location;
                $dt["COMPANYGROUP"] = $Data["COMPANYGROUP"];
                $dt["COMPANYSUBGROUP"] = $Data["COMPANYSUBGROUP"];
                $result = $result->set('CREATED_AT', "SYSDATE", false)->set($dt)->insert($this->FORECAST_VALIDATION);
            }
            $this->db->set('UPDATED_AT', "SYSDATE", false)->set([
                "DEPARTMENT" => $Data["DEPARTMENT"],
                "CASHFLOWTYPE" => $Data["CASHFLOWTYPE"],
                "YEAR" => $Data["YEAR"],
                "MONTH" => $Data["MONTH"],
                "USERNAME" => $Data["USERNAME"],
                "COMPANYGROUP" => $Data['COMPANYGROUP'],
                "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                "REMARK" => "Open Edit Data Forecast",
                "UPDATED_BY" => $Data["USERNAME"],
                "UPDATED_LOC" => $Location
            ])->insert($this->FORECAST_VALIDATION_HISTORY);

            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $Key
                ];
            } else {
                throw new Exception('Get Data Failed !');
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

    public function CancelValidation($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $USERNAME = "";
            $FLAG_ACTIVE = 0;
            $KEYSAVE = "";
            $SQL = "SELECT * FROM $this->FORECAST_VALIDATION WHERE YEAR = ? AND MONTH = ? AND DEPARTMENT = ? AND CASHFLOWTYPE = ? AND COMPANYGROUP = ? AND COMPANYSUBGROUP = ?";
            $DTCek = $this->db->query($SQL, [
                        $Data['YEAR'], $Data['MONTH'], $Data["DEPARTMENT"], $Data["CASHFLOWTYPE"], $Data['COMPANYGROUP'],$Data['COMPANYSUBGROUP']
                    ])->result();
            if (count($DTCek) > 0) {
                foreach ($DTCek as $value) {
                    $FLAG_ACTIVE = $value->FLAG_ACTIVE;
                    $USERNAME = $value->USERNAME;
                    $KEYSAVE = $value->KEYSAVE;
                }
            } else {
                throw new Exception("Data Not Found!!!");
            }
            if ($KEYSAVE == $Data["KEYSAVE"]) {
                $dt = [
                    "USERNAME" => "",
                    "UPDATED_BY" => $Data["USERNAME"],
                    "UPDATED_LOC" => $Location,
                    "FLAG_ACTIVE" => 0,
                    "KEYSAVE" => ""
                ];
                $result = $this->db->set('UPDATED_AT', "SYSDATE", false)->set($dt)->where([
                            'DEPARTMENT' => $Data['DEPARTMENT'],
                            'YEAR' => $Data['YEAR'],
                            'MONTH' => $Data['MONTH'],
                            'CASHFLOWTYPE' => $Data['CASHFLOWTYPE'],
                            "COMPANYGROUP" => $Data['COMPANYGROUP'],
                            "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                        ])->update($this->FORECAST_VALIDATION);
            } else {
                throw new Exception("Data has been Closed !!!");
            }
            $this->db->set('UPDATED_AT', "SYSDATE", false)->set([
                "DEPARTMENT" => $Data["DEPARTMENT"],
                "CASHFLOWTYPE" => $Data["CASHFLOWTYPE"],
                "YEAR" => $Data["YEAR"],
                "MONTH" => $Data["MONTH"],
                "USERNAME" => $Data["USERNAME"],
                "REMARK" => "Close Edit Data Forecast",
                "UPDATED_BY" => $Data["USERNAME"],
                "COMPANYGROUP" => $Data['COMPANYGROUP'],
                "COMPANYSUBGROUP" => $Data['COMPANYSUBGROUP'],
                "UPDATED_LOC" => $Location
            ])->insert($this->FORECAST_VALIDATION_HISTORY);

            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => "Successfully Closed Edit Data Forecast !!"
                ];
            } else {
                throw new Exception('Get Data Failed !!!');
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

}
