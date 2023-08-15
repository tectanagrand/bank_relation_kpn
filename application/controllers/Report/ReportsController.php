<?php

defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
use Carbon\Carbon;

class ReportsController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model("Report/ReportsModel", "ReportsModel");
    }

    public function GetROSnAging() {
        $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->ReportsModel->GetROSnAging($param);
            $this->resource = array(
                'status' => 200,
                'data' => [
                    "draw" => $_POST["draw"],
                    "recordsTotal" => $list["recordsTotal"],
                    "recordsFiltered" => $list["recordsFiltered"],
                    "data" => $list["data"]
                ]
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    // // Export to excel
    // public function OSnAgingExport()
    // {
    //     try {
    //         if (isset($_GET)) {
    //             $this->load->model("Report/ReportsModel", "ReportsModel");
    //             $result = $this->ReportsModel->Export($_GET);
    //             if ($result["STATUS"]) {
    //                 $NameFile = 'Payment';
    //                 if ($_GET['type'] == 'EXCEL') {
    //                     $NameFile .= '.xlsx';
    //                 } else {
    //                     throw new Exception('Entity Not Match !!!');
    //                 }

    //                 $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result["Data"], 'Xlsx');
    //                 ob_end_clean();
    //                 // Redirect output to a client’s web browser (Excel2007)
    //                 header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //                 header('Content-Disposition: attachment;filename="' . $NameFile . '"');
    //                 header('Cache-Control: max-age=0');
    //                 // If you're serving to IE 9, then the following may be needed
    //                 header('Cache-Control: max-age=1');

    //                 // If you're serving to IE over SSL, then the following may be needed
    //                 header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    //                 header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    //                 header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    //                 header('Pragma: public'); // HTTP/1.0

    //                 $objWriter->save('php://output');
    //                 exit;
    //             } else {
    //                 throw new Exception($result["Data"]);
    //             }
    //         } else {
    //             throw new Exception('Tidak di Temukan');
    //         }
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

}
