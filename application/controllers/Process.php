<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Process extends BaseController {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('status') <> 'login') {
            redirect(site_url("login"));
            die();
        }
        $this->load->model("MasterData/PermissionModel", "PermissionModel");
        $this->datasend['SESSION'] = $this->session;
        $this->datasend['DtUser2'] = $this->PermissionModel->GetDtUser($this->datasend['SESSION']->FCCODE);
        $this->datasend['ROLEACCESS'] = $this->datasend['DtUser2']->USERGROUPID;
        $this->datasend['Menu'] = $this->PermissionModel->GetMenu($this->datasend['ROLEACCESS']);
    }

    public function EntryData() {
        $this->datasend['formid'] = '28';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        if (isset($_GET['type'])) {
            $this->datasend['DtPO'] = [];
            $this->datasend['DtPODetail'] = [];
            $this->datasend['DtInvoice'] = [];
            $this->datasend['DtBusiness'] = [];
            if ($_GET['type'] == 'edit') {
                if (!isset($_GET['id'])) {
                    echo '<script>
                             alert("ID Not Found !!!");
                             window.location.href = window.location.href.split("?")[0];
                          </script>';
                }
                $this->load->model("Cashflow/EntryPOModel", "EntryPOModel");
                $this->datasend['DtPO'] = $this->EntryPOModel->GetData(["ID" => $_GET['id']]);
                $this->datasend['DtPODetail'] = $this->EntryPOModel->ShowDataDetail(["ID" => $_GET['id']]);
                $this->datasend['DtInvoice'] = $this->EntryPOModel->ShowDataInvoice(["ID" => $_GET['id']]);
                $this->load->model("BusinessUnitModel");
                $this->datasend['DtBusiness'] = $this->BusinessUnitModel->GetDataAjax($this->datasend['DtPO']->COMPANY);
                // echo "<pre>";
                // var_dump($this->datasend);exit();
            }
            $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
            $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
            $this->load->model(["ExtSystemModel", "DocTypeModel"]);
            $this->datasend['DtDocType'] = $this->DocTypeModel->GetDataActiveHead();
            $this->datasend['DtExtSystem'] = $this->ExtSystemModel->GetDataActive();
            $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        }

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/EntryData';
        $this->load->view('template', $this->datasend);
    }

    public function EntryDataExport() {
        try {
            if (isset($_GET)) {
                $this->load->model("Cashflow/EntryPOModel", "EntryPOModel");
                $result = $this->EntryPOModel->Export($_GET);
                if ($result["STATUS"]) {
                    $NameFile = 'EntryData';
                    if ($_GET['type'] == 'EXCEL') {
                        $NameFile .= '.xlsx';
                    } else {
                        throw new Exception('Entity Not Match !!!');
                    }

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

    public function Forecast() {
        $this->datasend['formid'] = '39';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/Forecast';
        $this->load->view('template', $this->datasend);
    }
    
    public function OpenForecast(){
        $this->datasend['formid'] = '63';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->datasend['sidebar'] = 'sidebar_view'; //Folder view 
        $this->datasend['content'] = 'Process/OpenForecast'; //Folder view 
        $this->load->view('template', $this->datasend);
    }

    public function ForecastExport() {
        try {
            if (isset($_GET)) {
                $this->load->model("Cashflow/ForecastModel", "ForecastModel");
                $result = $this->ForecastModel->Export($_GET);
                if ($result["STATUS"]) {
                    $PERIOD = $_GET["YEAR"] . substr("0" . $_GET["MONTH"], -2) . "01";
                    $NameFile = "DtForecast" . Carbon::parse($PERIOD)->format('Y-M');
                    if ($_GET['type'] == 'EXCEL') {
                        $NameFile .= '.xlsx';
                    } else {
                        throw new Exception('Entity Not Match !!!');
                    }

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
    // Export to excel
    public function PaymentReceiveExport()
    {
        try {
            if (isset($_GET)) {
                $this->load->model("Cashflow/PaymentModel", "PaymentModel");
                $result = $this->PaymentModel->Export($_GET);
                if ($result["STATUS"]) {
                    $NameFile = 'Payment';
                    if ($_GET['type'] == 'EXCEL') {
                        $NameFile .= '.xlsx';
                    } else {
                        throw new Exception('Entity Not Match !!!');
                    }

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

    // Export to excel
    public function PaymentExport()
    {
        try {
            if (isset($_GET)) {
                $this->load->model("Cashflow/ReportFtPModel", "ReportFtPModel");
                $result = $this->ReportFtPModel->Export($_GET);
                if ($result["STATUS"]) {
                    $NameFile = 'ReportPayment';
                    if ($_GET['type'] == 'EXCEL') {
                        $NameFile .= '.xlsx';
                    } else {
                        throw new Exception('Entity Not Match !!!');
                    }

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

    // Export to excel
    public function OSnAgingExport()
    {
        try {
            if (isset($_GET)) {
                $this->load->model("Report/ReportsModel", "ReportsModel");
                $result = $this->ReportsModel->Export($_GET);
                if ($result["STATUS"]) {
                    $NameFile = 'DtOSnAging';
                    if ($_GET['type'] == 'EXCEL') {
                        $NameFile .= '.xlsx';
                    } else {
                        throw new Exception('Entity Not Match !!!');
                    }

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

//    Link Pembayaran
    public function PaymentReceive() {
        $this->datasend['formid'] = '37';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->load->model("CompanyGroupModel", "CompanyGroupModel");
        $this->load->model("CompanySubGroupModel", "CompanySubGroupModel");

        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['Dtcompanygroup'] = $this->CompanyGroupModel->GetDataActive();
        $this->datasend['Dtcompanysubgroup'] = $this->CompanySubGroupModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/PaymentReceive';
        $this->load->view('template', $this->datasend);
    }

    public function PaymentReceiveReversal() {
        $this->datasend['formid'] = '117';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->load->model("CompanyGroupModel", "CompanyGroupModel");
        $this->load->model("CompanySubGroupModel", "CompanySubGroupModel");

        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['Dtcompanygroup'] = $this->CompanyGroupModel->GetDataActive();
        $this->datasend['Dtcompanysubgroup'] = $this->CompanySubGroupModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/PaymentReceiveReversal';
        $this->load->view('template', $this->datasend);
    }

    public function UploadPaymentReceive() {
        $this->datasend['formid'] = '82';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->load->model("CompanyGroupModel", "CompanyGroupModel");
        $this->load->model("CompanySubGroupModel", "CompanySubGroupModel");

        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['Dtcompanygroup'] = $this->CompanyGroupModel->GetDataActive();
        $this->datasend['Dtcompanysubgroup'] = $this->CompanySubGroupModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');
        $this->datasend['UUID'] = $this->uuid->v4();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/UploadPaymentReceive';
        $this->load->view('template', $this->datasend);
    }

    public function UploadPaymentInterco() {
        $this->datasend['formid'] = '83';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->load->model("CompanyGroupModel", "CompanyGroupModel");
        $this->load->model("CompanySubGroupModel", "CompanySubGroupModel");

        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['Dtcompanygroup'] = $this->CompanyGroupModel->GetDataActive();
        $this->datasend['Dtcompanysubgroup'] = $this->CompanySubGroupModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail('000001');
        $this->datasend['UUID'] = $this->uuid->v4();
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/UploadPaymentInterco';
        $this->load->view('template', $this->datasend);
    }

    public function EditPaymentReceive() {
        $this->datasend['formid'] = '35';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtDepartment'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();
        $this->datasend['DtCurrency'] = $this->PermissionModel->BMCodeDetail("000001");
        
        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Process/PaymentReceiveEdit';
        $this->load->view('template', $this->datasend);
    }

    public function otherVoucher() {
        $this->datasend['formid'] = '55';
        $this->datasend['ACCESS'] = $this->PermissionModel->GetAccessMenu($this->datasend['ROLEACCESS'], $this->datasend['formid']);
        if ($this->datasend['ACCESS']['VIEWS'] != 1) {
            redirect('/');
        }
        $this->datasend['DtOtherVoucher'] = [];
        $this->datasend['departement'] = $this->PermissionModel->GetDtDepart($this->datasend['SESSION']->FCCODE);
        $this->load->model("MasterData/General/CompanyModel", "CompanyModel");
        $this->datasend['DtCompany'] = $this->CompanyModel->GetDataActive();

        $this->datasend['sidebar'] = 'sidebar_view';
        $this->datasend['content'] = 'Cashflow/OtherVoucher';
        $this->load->view('template', $this->datasend);
    }
 
}
