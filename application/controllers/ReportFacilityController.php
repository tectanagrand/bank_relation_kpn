<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriterException;
use Carbon\Carbon;

//This is the Controller for codeigniter crud using ajax application.
class ReportFacilityController extends BaseController {

    public function __construct() {
        parent::__construct();
        try {
            $this->load->model(array('ReportFacilityModel', 'ReportGenModel'));
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
    }

    public function ShowData() {
        ini_set('display_errors', 'On');
        $param = $this->input->post();
        try {
            $data = $this->ReportFacilityModel->ShowData($param);
            // var_dump($data) ; exit;
            $no = $param['start'] ;
            $arr = [];
            foreach($data[2] as $item) {
                $no++ ;
                $row = [];
                $row['NO'] = $no;
                $row['CID'] = $item->CID ;
                $row['COMPANYNAME'] = $item->COMPANYNAME ;
                $row['CONTRACT_NUMBER'] = $item->CONTRACT_NUMBER ; 
                $row['CREDIT_TYPE'] = $item->CREDIT_TYPE ;
                $row['DOCDATE'] = $item->DOCDATE ;
                $row['FCNAME'] = $item->FCNAME ;
                $row['ID'] = $item->ID ;
                $row['KI_TYPE'] = $item->KI_TYPE ;
                $row['MATURITY_DATE'] = $item->MATURITY_DATE ;
                $row['PK_NUMBER'] = $item->PK_NUMBER ;
                $row['SUB_CREDIT_TYPE'] = $item->SUB_CREDIT_TYPE ;
                $row['UUID'] = $item->UUID ;
                $arr[] = $row ;
            }

            $output = array(
                "draw" => $param['draw'],
                // "start" => intval($param['start'] + 1),
                // "end" => $no,
                "recordsTotal" => $data[0],
                "recordsFiltered" => $data[1],
                "data" => $arr,
            );
            $this->resource = array(
                'status' => 200,
                'data' => $output
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
        // echo json_encode($output);
    }

    public function ShowDataNot() {
        $param = $this->input->post();

        try {
            $list = $this->ReportFacilityModel->ShowDataNot();
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function ExportRecapReport() {
        $param = $this->input->get();
        // var_dump($param) ; exit;
        $param = [
            'COMPANY' => $param['COMPANY'] != '\'\'' ? $param['COMPANY'] : '' ,
            'START' => $param['START'] != '\'\'' ? $param['START'] : '',
            'END' => $param['END'] != '\'\'' ? $param['END'] : ''
        ];
        // var_dump($param); exit;
        // ini_set('display_errors', 'On');
        try { 
            $result = $this->ReportGenModel->ExportRecapReport($param);
            // $newName = preg_replace("/[\.]/", "_", $param['PK_NUMBER']);
            // echo "<pre>";
            // var_dump($result); exit;
            if($result['STATUS']) {
                $NameFile = "TESTRECAP" ;
                $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result['Data'], 'Xlsx');
                // echo "<pre>";
                // var_dump($objWrite); exit;
                
                ob_end_clean();
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $NameFile . '.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                
                // If you're serving to IE over SSL, then the following may be needed
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                try {
                    ob_end_clean();
                    $objWriter->save('php://output');
                    exit;
                    // var_dump($return); exit;
                }
                catch (Exception $ex) {
                    throw new Exception($ex->getMessage());
                }
                catch(\PhpOffice\PhpSpreadsheet\Writer\Exception $ex) {
                    echo $ex->getMessage();
                }
            }
            else {
                throw new Exception($result['Data']);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function ShowSummaryReportWeekly() {
        $param = $this->input->post();
        // $param = [
        //     'COMPANY' => 'AAN',
        //     'BANK'  => 'SINAR MAS',
        //     'CREDIT_TYPE' => 'KI',
        //     'PERIOD' => '5/26/2029'
        // ] ;
        try {
            $list = $this->ReportGenModel->ShowSummaryReportWeekly($param);
            $this->resource = array(
                'status' => 200,
                'data' => $list
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function ExportReportWeekly() {
        $param = $this->input->get();
        // $param = [
        //     'COMPANY' => 'AAN',
        //     'BANK'  => 'SINAR MAS',
        //     'CREDIT_TYPE' => 'KI',
        //     'PERIOD' => '5/26/2029'
        // ] ;
        // var_dump($param); exit;
        // ini_set('display_errors', 'On');
        try { 
            $result = $this->ReportGenModel->ExportReportSummaryWeekly($param);
            // $newName = preg_replace("/[\.]/", "_", $param['PK_NUMBER']);
            // echo "<pre>";
            // var_dump($result); exit;
            if($result['STATUS']) {
                $NameFile = "Weekly Report KMK KI- {$param['PERIOD']}" ;
                $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result['Data'], 'Xlsx');
                // echo "<pre>";
                // var_dump($objWrite); exit;
                
                ob_end_clean();
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $NameFile . '.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                
                // If you're serving to IE over SSL, then the following may be needed
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                try {
                    // ob_end_clean();
                    $objWriter->save('php://output');
                    // exit;
                    // var_dump($return); exit;
                }
                catch (Exception $ex) {
                    throw new Exception($ex->getMessage());
                }
            }
            else {
                throw new Exception($result['Data']);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}

?>