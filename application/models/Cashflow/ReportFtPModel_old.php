<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ReportFtPModel extends BaseModel
{

    function __construct()
    {
        parent::__construct();
    }

    public function ShowData($param)
    {
        $param['DEPARTMENT'] = "%" . $param["DEPARTMENT"] . "%";

        $SQL = "SELECT CFT.ID, CFT.COMPANY, C.COMPANYCODE, C.COMPANYNAME, CFT.BUSINESSUNIT, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, 
                        NVL(CFPO.DEPARTMENT, CFT.DEPARTMENT) AS DEPARTMENT, CFPO.DOCTYPE, DECODE(CFPO.DOCNUMBER, NULL, '', CFT.DOCNUMBER) AS DOCNUMBER, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, NVL(CFPO.DOCNUMBER, CFT.DOCNUMBER) AS DOCREF, CFT.VENDOR, S.FCNAME AS VENDORNAME,
                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINV, 
                        NVL(FF.REQUESTW1, 0) AS REQUESTW1, NVL(FF.REQUESTW2, 0) AS REQUESTW2, NVL(FF.REQUESTW3, 0) AS REQUESTW3, NVL(FF.REQUESTW4, 0) AS REQUESTW4, 
                        NVL(FF.REQUESTW5, 0) AS REQUESTW5, NVL(FF.ADJSW1, 0) AS ADJSW1, NVL(FF.ADJSW2, 0) AS ADJSW2, NVL(FF.ADJSW3, 0) AS ADJSW3, NVL(FF.ADJSW4, 0 ) AS ADJSW4, 
                        NVL(FF.ADJSW5, 0) AS ADJSW5, NVL(PY.PAIDW1, 0) AS PAIDW1, NVL(PY.PAIDW2, 0) AS PAIDW2, NVL(PY.PAIDW3, 0) AS PAIDW3, NVL(PY.PAIDW4, 0) AS PAIDW4, 
                        NVL(PY.PAIDW5, 0) AS PAIDW5, FF.LOCKS
                FROM CF_TRANSACTION CFT
                LEFT JOIN CF_TRANSACTION CFPO
                       ON CFPO.DOCNUMBER = CFT.DOCREF
                LEFT JOIN (SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5
                                FROM FORECAST_FIX FF
                            WHERE FF.YEAR = ?
                                AND FF.MONTH = ?
                            GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS) FF
                        ON FF.CFTRANSID = CFT.ID
                LEFT JOIN (SELECT FF.CFTRANSID, PY.PAIDW1, PY.PAIDW2, PY.PAIDW3, PY.PAIDW4, PY.PAIDW5
                                FROM FORECAST_FIX FF
                            INNER JOIN (SELECT PY.FORECASTID, ROUND(SUM(DECODE(PY.WEEK, 'W1', PY.AMOUNT, 0)), 2) AS PAIDW1, 
                                                ROUND(SUM(DECODE(PY.WEEK, 'W2', PY.AMOUNT, 0)), 2) AS PAIDW2,
                                                ROUND(SUM(DECODE(PY.WEEK, 'W3', PY.AMOUNT, 0)), 2) AS PAIDW3,
                                                ROUND(SUM(DECODE(PY.WEEK, 'W4', PY.AMOUNT, 0)), 2) AS PAIDW4,
                                                ROUND(SUM(DECODE(PY.WEEK, 'W5', PY.AMOUNT, 0)), 2) AS PAIDW5
                                            FROM (SELECT PY.FORECASTID, PY.AMOUNT, (SELECT SW.WEEK
                                                                                    FROM SETTING_WEEK SW
                                                                                    WHERE SW.YEAR = TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'yyyy'))
                                                                                        AND SW.MONTH = TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'mm'))
                                                                                        AND SW.DATEFROM <= TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'dd')) 
                                                                                        AND SW.DATEUNTIL >= TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'dd'))) AS WEEK
                                                    FROM PAYMENT PY
                                                    WHERE TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'yyyy')) = ?
                                                    AND TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'mm')) = ?) PY
                                            GROUP BY PY.FORECASTID) PY
                                    ON PY.FORECASTID = FF.ID) PY
                        ON PY.CFTRANSID = CFT.ID
                INNER JOIN COMPANY C
                        ON C.ID = CFT.COMPANY
                INNER JOIN BUSINESSUNIT BS
                        ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                LEFT JOIN SUPPLIER S
                        ON S.ID = CFT.VENDOR
                INNER JOIN USER_DEPART UD
                        ON UD.FCCODE = ?
                        AND UD.DEPARTMENT = NVL(CFPO.DEPARTMENT, CFT.DEPARTMENT)
                WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'PDO' OR CFT.DOCTYPE = 'INV_AR')
                    AND (FF.CFTRANSID IS NOT NULL OR PY.CFTRANSID IS NOT NULL)
                    AND NVL(CFPO.DEPARTMENT, CFT.DEPARTMENT) LIKE ?
                ORDER BY CFT.DOCREF, CFT.DOCNUMBER";
        $result = $this->db->query($SQL, [$param['YEAR'], $param['MONTH'], $param['YEAR'], $param['MONTH'], $param['USERNAME'], $param['DEPARTMENT']])->result();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function Export($Data)
    {
        try {
            $SQL = "SELECT CFT.ID, CFT.COMPANY, C.COMPANYCODE, C.COMPANYNAME, CFT.BUSINESSUNIT, BS.FCCODE AS BUSINESSUNITCODE, BS.FCNAME AS BUSINESSUNITNAME, 
                        NVL(CFPO.DEPARTMENT, CFT.DEPARTMENT) AS DEPARTMENT, CFPO.DOCTYPE, DECODE(CFPO.DOCNUMBER, NULL, '', CFT.DOCNUMBER) AS DOCNUMBER, TO_CHAR(CFT.DOCDATE, 'YYYY/MM/DD') AS DOCDATE, NVL(CFPO.DOCNUMBER, CFT.DOCNUMBER) AS DOCREF, CFT.VENDOR, S.FCNAME AS VENDORNAME,
                        TO_CHAR(CFT.DUEDATE, 'YYYY/MM/DD') AS DUEDATE, (CFT.AMOUNT_INCLUDE_VAT - NVL(CFT.AMOUNT_PPH, 0)) AS AMOUNTINV, NVL(FF.REQUESTW1, 0) AS REQUESTW1, NVL(FF.REQUESTW2, 0) AS REQUESTW2, NVL(FF.REQUESTW3, 0) AS REQUESTW3, NVL(FF.REQUESTW4, 0) AS REQUESTW4, 
                        NVL(FF.REQUESTW5, 0) AS REQUESTW5, NVL(FF.ADJSW1, 0) AS ADJSW1, NVL(FF.ADJSW2, 0) AS ADJSW2, NVL(FF.ADJSW3, 0) AS ADJSW3, NVL(FF.ADJSW4, 0 ) AS ADJSW4, 
                        NVL(FF.ADJSW5, 0) AS ADJSW5, NVL(PY.PAIDW1, 0) AS PAIDW1, NVL(PY.PAIDW2, 0) AS PAIDW2, NVL(PY.PAIDW3, 0) AS PAIDW3, NVL(PY.PAIDW4, 0) AS PAIDW4, 
                        NVL(PY.PAIDW5, 0) AS PAIDW5, FF.LOCKS
                FROM CF_TRANSACTION CFT
                LEFT JOIN CF_TRANSACTION CFPO
                       ON CFPO.DOCNUMBER = CFT.DOCREF
                LEFT JOIN (SELECT FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTREQUEST, 0)), 2) AS REQUESTW5,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W1', FF.AMOUNTADJS, 0)), 2) AS ADJSW1,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W2', FF.AMOUNTADJS, 0)), 2) AS ADJSW2,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W3', FF.AMOUNTADJS, 0)), 2) AS ADJSW3,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W4', FF.AMOUNTADJS, 0)), 2) AS ADJSW4,
                                    ROUND(SUM(DECODE(FF.WEEK, 'W5', FF.AMOUNTADJS, 0)), 2) AS ADJSW5
                                FROM FORECAST_FIX FF
                            WHERE FF.YEAR = '" . $Data["YEAR"] . "'
                                AND FF.MONTH = '" . $Data["MONTH"] . "'
                            GROUP BY FF.CFTRANSID, FF.YEAR, FF.MONTH, FF.ISACTIVE, FF.LOCKS) FF
                        ON FF.CFTRANSID = CFT.ID
                LEFT JOIN (SELECT FF.CFTRANSID, PY.PAIDW1, PY.PAIDW2, PY.PAIDW3, PY.PAIDW4, PY.PAIDW5
                                FROM FORECAST_FIX FF
                            INNER JOIN (SELECT PY.FORECASTID, ROUND(SUM(DECODE(PY.WEEK, 'W1', PY.AMOUNT, 0)), 2) AS PAIDW1, 
                                                ROUND(SUM(DECODE(PY.WEEK, 'W2', PY.AMOUNT, 0)), 2) AS PAIDW2,
                                                ROUND(SUM(DECODE(PY.WEEK, 'W3', PY.AMOUNT, 0)), 2) AS PAIDW3,
                                                ROUND(SUM(DECODE(PY.WEEK, 'W4', PY.AMOUNT, 0)), 2) AS PAIDW4,
                                                ROUND(SUM(DECODE(PY.WEEK, 'W5', PY.AMOUNT, 0)), 2) AS PAIDW5
                                            FROM (SELECT PY.FORECASTID, PY.AMOUNT, (SELECT SW.WEEK
                                                                                    FROM SETTING_WEEK SW
                                                                                    WHERE SW.YEAR = TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'yyyy'))
                                                                                        AND SW.MONTH = TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'mm'))
                                                                                        AND SW.DATEFROM <= TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'dd')) 
                                                                                        AND SW.DATEUNTIL >= TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'dd'))) AS WEEK
                                                    FROM PAYMENT PY
                                                    WHERE TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'yyyy')) = '" . $Data["YEAR"] . "'
                                                    AND TO_NUMBER(TO_CHAR(PY.DATERELEASE, 'mm')) = '" . $Data["MONTH"] . "') PY
                                            GROUP BY PY.FORECASTID) PY
                                    ON PY.FORECASTID = FF.ID) PY
                        ON PY.CFTRANSID = CFT.ID
                LEFT JOIN DEPARTMENT DP
                       ON DP.FCCODE = CFT.DEPARTMENT
                LEFT JOIN CF_TRANSACTION CFPO
                       ON CFPO.DOCNUMBER = CFT.DOCREF
                INNER JOIN COMPANY C
                        ON C.ID = CFT.COMPANY
                INNER JOIN BUSINESSUNIT BS
                        ON BS.ID = CFT.BUSINESSUNIT
                        AND BS.COMPANY = CFT.COMPANY
                LEFT JOIN SUPPLIER S
                        ON S.ID = CFT.VENDOR
                INNER JOIN USER_DEPART UD
                        ON UD.FCCODE = '" . $Data["USERNAME"] . "'
                        AND UD.DEPARTMENT = NVL(CFPO.DEPARTMENT, CFT.DEPARTMENT)
                LEFT JOIN CURS CR ON CR.CURSCODE = CFT.CURRENCY 
                AND CR.CURSYEAR = '" . $Data["YEAR"] . "' AND CR.CURSMONTH =  '" . $Data["MONTH"] . "'
                WHERE (CFT.DOCTYPE = 'INV' OR CFT.DOCTYPE = 'PDO' OR CFT.DOCTYPE = 'INV_AR')
                    AND (FF.CFTRANSID IS NOT NULL OR PY.CFTRANSID IS NOT NULL)";
            $ParamW = [$Data["USERNAME"], $Data["YEAR"], $Data["MONTH"]];
            if ($Data["DEPARTMENT"] == "ALL") {
                $FDepartment = "Department : All Department";
            } else {
                $FDepartment = "Department : " . $Data["DEPARTMENT"];
                $SQL .= " AND NVL(CFPO.DEPARTMENT, CFT.DEPARTMENT) LIKE '" . $Data["DEPARTMENT"] . "'";
                array_push($ParamW);
                // echo($Data["DEPARTMENT"]);
            }
            $SQL .= " ORDER BY CFT.DEPARTMENT, CFT.DUEDATE, C.COMPANYNAME, CFT.DOCREF, CFT.DOCNUMBER";
            $result = $this->db->query($SQL, $ParamW)->result();
            // var_dump($this->db->last_query());exit();
            $FPeriod = "Period Forecash to Payment : " . Carbon::parse($Data["YEAR"] . substr("0" . $Data["MONTH"], -2) . "01")->format('Y-M');
            $GExport = "Date Export : " . Carbon::now('Asia/Jakarta')->format('d-M-Y');
            $objPHPExcel = new Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("IT GAMA")
                ->setLastModifiedBy("IT GAMA")
                ->setTitle("Report Forecash to Payment")
                ->setSubject("Report Forecash to Payment")
                ->setDescription("Data Document in System $FDepartment, $FPeriod, $GExport")
                ->setKeywords("Report Forecash to Payment")
                ->setCategory("Report Forecash to Payment");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Data');
            $i = 1;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(23);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);

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
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $FPeriod);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $GExport);
            $i++;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . ($i + 1))->applyFromArray([
                'font' => $StyleBold, 'alignment' => $StyleCenterAll, 'borders' => $StyleBorder
            ]);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'No');
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':A' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'Company');
            $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':B' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'Business Unit');
            $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':C' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'Department');
            $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':D' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'Doc Type');
            $objPHPExcel->getActiveSheet()->mergeCells('E' . $i . ':E' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'Doc Number');
            $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':F' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'Doc Date');
            $objPHPExcel->getActiveSheet()->mergeCells('G' . $i . ':G' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, 'Doc Reference');
            $objPHPExcel->getActiveSheet()->mergeCells('H' . $i . ':H' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'Vendor');
            $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':I' . ($i + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, 'Due Date');
            $objPHPExcel->getActiveSheet()->mergeCells('J' . $i . ':J' . ($i + 1));;
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, 'Amount Doc');
            $objPHPExcel->getActiveSheet()->mergeCells('K' . $i . ':K' . ($i + 1));;
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'Requested');
            $objPHPExcel->getActiveSheet()->mergeCells('L' . $i . ':P' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'Adjusted');
            $objPHPExcel->getActiveSheet()->mergeCells('Q' . $i . ':U' . $i);
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'Paid');
            $objPHPExcel->getActiveSheet()->mergeCells('V' . $i . ':Z' . $i);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, 'W5');
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, 'W5');
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, 'W1');
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, 'W2');
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, 'W3');
            $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, 'W4');
            $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, 'W5');
            $i++;

            if (count($result) > 0) {
                $iDtAwal = $i;
                $No = 1;
                foreach ($result as $values) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $No);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $values->COMPANYNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $values->BUSINESSUNITNAME);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $values->DEPARTMENT);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $values->DOCTYPE);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, $values->DOCNUMBER, DataType::TYPE_STRING);
                    if ($values->DOCDATE != NULL && $values->DOCDATE != "") {
                        $DtDate = explode('/', $values->DOCDATE);
                        $XlsTime = gmmktime(0, 0, 0, intval($DtDate[1]), intval($DtDate[2]), intval($DtDate[0]));
                        $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, Date::PHPToExcel($XlsTime));
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                    }
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $i, $values->DOCREF, DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $values->VENDORNAME);
                    if ($values->DUEDATE != NULL && $values->DUEDATE != "") {
                        $DtDate = explode('/', $values->DUEDATE);
                        $XlsTime = gmmktime(0, 0, 0, intval($DtDate[1]), intval($DtDate[2]), intval($DtDate[0]));
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, Date::PHPToExcel($XlsTime));
                        $objPHPExcel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $values->AMOUNTINV);
                    $objPHPExcel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $values->REQUESTW1);
                    $objPHPExcel->getActiveSheet()->getStyle('L' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $values->REQUESTW2);
                    $objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $values->REQUESTW3);
                    $objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $values->REQUESTW4);
                    $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $values->REQUESTW5);
                    $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $values->ADJSW1);
                    $objPHPExcel->getActiveSheet()->getStyle('Q' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $values->ADJSW2);
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $values->ADJSW3);
                    $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $values->ADJSW4);
                    $objPHPExcel->getActiveSheet()->getStyle('T' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $values->ADJSW5);
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $values->PAIDW1);
                    $objPHPExcel->getActiveSheet()->getStyle('V' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $values->PAIDW2);
                    $objPHPExcel->getActiveSheet()->getStyle('W' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $values->PAIDW3);
                    $objPHPExcel->getActiveSheet()->getStyle('X' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $values->PAIDW4);
                    $objPHPExcel->getActiveSheet()->getStyle('Y' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $values->PAIDW5);
                    $objPHPExcel->getActiveSheet()->getStyle('Z' . $i)->getNumberFormat()->setFormatCode("#,##0");
                    $i++;
                    $No++;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A' . $iDtAwal . ':Z' . ($i - 1))->applyFromArray([
                    'font' => $StyleDefault, 'borders' => $StyleBorder
                ]);
                // $objPHPExcel->getActiveSheet()->getStyle('G' . $iDtAwal . ':G' . ($i - 1))->applyFromArray(['alignment' => $StyleCenterAll]);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "No Data");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':Z' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . $i)->applyFromArray([
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
}
