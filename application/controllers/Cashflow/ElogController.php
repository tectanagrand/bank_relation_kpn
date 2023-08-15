<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ElogController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("MasterData/General/CompanyModel","BusinessUnitModel","VendorModel","ElogModel","MaterialModel"));
	}

    public function ShowFileData() {
        $param = $this->input->post();
        try {
            $list = $this->ElogModel->ShowFileData($param);
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

    public function DeleteFile(){
        $param = $this->input->post();
        try {
            $path_to_file = LOGFILES.$param['FILENAME'];
            // var_dump($path_to_file);exit;
            if ( file_exists($path_to_file) ){
                if( unlink($path_to_file) ) {
                    $this->db->where('ID',$param['ID']);
                    $isDeleted = $this->db->delete('LOG_FILES');
                    if($isDeleted){
                        $res = "Data Deleted";
                    }
                }else{
                    $res = "Delete Error";
                }
            }
            $this->resource = array(
                'status' => 200,
                'data' => $res
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function uploadElogFile() {
        
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->uploadElogFile($param,$this->GetIpAddress());
            // echo "<pre>";
            // var_dump($param);exit;
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function DeleteMaster() {
        $param = $this->input->post();
        // var_dump($param);exit();
        try {
            $list = $this->ElogModel->DeleteMaster($param);
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

    function deleteUpload(){
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $this->db->where('USERNAME',$param['USERNAME']);
            $return = $this->db->delete('TEMP_UPLOAD_FR');
            if ($return) {
                $this->resource = [
                    'status' => 200,
                    'data' => 'Data dihapus'
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function UploadFR() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->UploadFR($param,$this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function Save_FR() {
        $param = $this->input->post();
        try {
            $param["DATA"] = json_decode($param["DATA"], TRUE);
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->Save_FR($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function Save() {
        $param = $this->input->post();
        try {

            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->LogSave($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function viewDetail(){
        $UUID = $this->input->post('ID');
        $q    = "SELECT L.*, S.FCNAME AS VENDORNAME, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE UUID = '$UUID'";
        $result = $this->db->query($q)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        echo json_encode($result);
    }

    public function ShowData() {

        $param = $this->input->post();

        try {
            $list = $this->ElogModel->ShowData($param);
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

    public function ShowSendData() {
        // $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->ElogModel->ShowSendData($param);
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

    public function ShowReceiveData() {
        // $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->ElogModel->ShowReceiveData($param);
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

    public function ShowDataLastDoc() {
        // $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->ElogModel->ShowDataLastDoc($param);
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

    public function ShowDataLastDocNew() {
        // $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->ElogModel->ShowDataLastDocNew($param);
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

    public function HistoryDoc() {
        // $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->ElogModel->HistoryDocNew($param);
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

    public function HistoryDocNew() {
        // $param = $this->input->post();
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            $list = $this->ElogModel->HistoryDocNew($param);
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

    public function getHistoryDoc() {
        $param = $this->input->post();

        try {
            $list = $this->ElogModel->getHistoryDoc($param);
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

    public function sendReceiptAll() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->sendReceiptAll($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function sendReceipt() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->sendReceipt($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function otherSend() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->otherSend($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function receiveReceiptClosing() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->receiveReceiptClosing($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function receiveReceipt() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->receiveReceipt($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

     public function receiveReceiptAll() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->ElogModel->receiveReceiptAll($param, $this->GetIpAddress());
            if ($return['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $return['MESSAGE']
                ];
            } else {
                throw new Exception($return['MESSAGE']);
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }


    public function exportTransaction() {
        $post = $this->input->get();
        try {
            $result = $this->LeasingModel->ExportTransaction($post);
            if ($result["STATUS"]) {
                $NameFile = 'Report Leasing';
                
                $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result["Data"], 'Xlsx');
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
                
                $objWriter->save('php://output');
                exit;
            } else {
                throw new Exception($result["Data"]);
            }
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function ReceiveExport() {
        try {
            if (isset($_GET)) {
                $result = $this->ElogModel->ReceiveExport($_GET);
                if ($result["STATUS"]) {
                    $NameFile = "Receive_Export_" . Date('m-d-Y').'.xlsx';

                    $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result["Data"], 'Xlsx');
                    ob_end_clean();
                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="' . $NameFile . '"');
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $objWriter->save('php://output');
                    exit;
                } else {
                    throw new Exception($result["Data"]);
                }
            } else {
                throw new Exception('Tidak di Temukan');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function LastDocExport() {
        try {
            if (isset($_GET)) {
                $result = $this->ElogModel->LastDocExport($_GET);
                if ($result["STATUS"]) {
                    $NameFile = "Last_Position_Doc_" . Date('m-d-Y').'.xlsx';

                    $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result["Data"], 'Xlsx');
                    ob_end_clean();
                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="' . $NameFile . '"');
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $objWriter->save('php://output');
                    exit;
                } else {
                    throw new Exception($result["Data"]);
                }
            } else {
                throw new Exception('Tidak di Temukan');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function ExportFirst() {
        try {
            if (isset($_GET)) {
                $result = $this->ElogModel->ExportFirst($_GET);
                if ($result["STATUS"]) {
                    $NameFile = "First_Receipt_" . Date('m-d-Y').'.xlsx';

                    $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($result["Data"], 'Xlsx');
                    ob_end_clean();
                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="' . $NameFile . '"');
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $objWriter->save('php://output');
                    exit;
                } else {
                    throw new Exception($result["Data"]);
                }
            } else {
                throw new Exception('Tidak di Temukan');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

	public function getCompany(){
        $q = $this->input->get_post('q', true);
        $company = $this->CompanyModel->loadCompany(strtoupper($q));
     	echo json_encode($company);
    }
    public function getBusinessUnit(){
        $q = $this->input->get_post('COMPANY', true);
        $bu = $this->BusinessUnitModel->loadBusinessUnit($q);
     	echo json_encode($bu);
    }
    public function getVendor(){
        $q = $this->input->get_post('q', true);
        $bu = $this->VendorModel->loadVendor(strtoupper($q));
        echo json_encode($bu);
    }
    public function getMaterialCode(){
        $q      = $this->input->get_post('q', true);
        $EXTSYS = $this->input->get_post('EXTSYS', true);
        $bu = $this->MaterialModel->loadMaterial($q,$EXTSYS);
        echo json_encode($bu);
    }

    public function getVendorNew(){
        $q = $this->input->get_post('q', true);
        $bu = $this->ElogModel->getVendorNew(strtoupper($q));
        echo json_encode($bu);
    }

    public function getVendorSend(){
        $q = $this->input->get_post('q', true);
        $bu = $this->ElogModel->getVendorSend(strtoupper($q));
        echo json_encode($bu);
    }


}

/* End of file LeasingController.php */
/* Location: ./application/controllers/Cashflow/LeasingController.php */