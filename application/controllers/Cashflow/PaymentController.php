<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model("Cashflow/PaymentModel", "PaymentModel");
    }

    public function cekBeforeDel() {
        $param = $this->input->post();
        try {
            $list = $this->PaymentModel->cekBeforeDel($param);
            // var_dump($list);exit;
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

    public function getPaymentPeriod(){
        $param = $this->input->post();
        try {
            $this->db->where('COMPANY',$param['COMPANY']);
            $list = $this->db->get('PAYMENT_PERIODCONTROL')->row();
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

    public function getFlag(){
        $param = $this->input->post();
        try {
            $this->db->where('MASTERID',$param['masterid']);
            $list = $this->db->get('BMCODEMASTER')->row();
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

    public function checkPeriod(){
        $param = $this->input->post();
        try {
            $where = array(
                'COMPANY' => $param['COMPANY'], 
                'CURRENTACCOUNTINGYEAR' => $param['YEAR'], 
                'CURRENTACCOUNTINGPERIOD' => $param['MONTH']);
            $this->db->where($where);
            $list = $this->db->get('PAYMENT_PERIODCONTROL')->row();
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

    public function ShowDataPeriod() {
        try {
            $list = $this->PaymentModel->ShowDataPeriod();
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
    public function saveProcessPeriod(){
        
        $param = $this->input->post();
        try {
            if($param['YEAR'] == null || $param['MONTH'] == null){
                throw new Exception('Data Bulan dan Tahun Kosong');
            }
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->saveUpdatePeriod($param, $this->GetIpAddress());
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

    public function saveClosing(){
        
        $param = $this->input->post();
        try {
            if($param['COMPANY'] == null || $param['COMPANY'] == ''){
                throw new Exception('Cant Empty');
            }
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->saveClosing($param, $this->GetIpAddress());
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

    public function saveClosingNew() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->saveClosingNew($param, $this->GetIpAddress());
            // var_dump($return);exit;
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
        header('Content-Type: application/json');
        $this->SendResponse();
    }

    public function getCompanyClosing() {
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            // if (
            //         $param["MONTH"] != NULL && $param["YEAR"] != "" ||
            //         $param["YEAR"] != NULL && $param["MONTH"] != ""
            // ) {
                $list = $this->PaymentModel->getCompanyClosing($param);
            // }
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

    public function ShowDataForecast() {
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            if ($param["YEAR"] != NULL && $param["YEAR"] != "") {
                $list = $this->PaymentModel->ShowDataForecast($param);
            }
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

    public function getSubGroup(){
        $FCCODE = $this->input->post('FCCODE');
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $this->db->select('*');
            $this->db->where('FCCODE_GROUP',$FCCODE);
            $res = $this->db->get('COMPANY_SUBGROUP')->result();
            if ($res) {
                $this->db->select('ID,COMPANYCODE,COMPANYNAME');
                $this->db->where('COMPANY_SUBGROUP',$FCCODE);
                $company = $this->db->get('COMPANY')->result();
                // var_dump($this->db->last_query());exit;
                $this->resource = [
                    'status' => 200,
                    'data' => $res,
                    'data_2' => $company
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
        $response = $this->BuildResponse_new($this->resource['status'], $this->resource['data'], $this->resource['data_2']);

        echo json_encode($response);
    }

    public function SearchOtherVoucher() {
        $param = $this->input->post();
        try {
            $list = $this->PaymentModel->SearchOtherVoucher($param);
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

    public function DtBankCompany() {
        try {
            $param = $_POST;
            $list = [];
            if ($param["COMPANY"] != NULL && $param["COMPANY"] != "") {
                $list = $this->PaymentModel->DtBankCompany($param);
            }
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

    public function DtOsPayment() {
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            if (($param["CASHFLOWTYPE"] != NULL && $param["CASHFLOWTYPE"] != "") && ($param["COMPANY"] != NULL && $param["COMPANY"] != "")) {
                $list = $this->PaymentModel->DtOsPayment($param);
            }
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

    public function DtOsPayment1() {
        try {
            $param = $_POST;
            $list = [];
            if (($param["CASHFLOWTYPE"] != NULL && $param["CASHFLOWTYPE"] != "") && ($param["COMPANY"] != NULL && $param["COMPANY"] != "")) {
                $list = $this->PaymentModel->DtOsPayment1($param);
            }
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

    public function DtOsPayment1Reversal() {
        try {
            $param = $_POST;
            $list = [];
            if (($param["CASHFLOWTYPE"] != NULL && $param["CASHFLOWTYPE"] != "") && ($param["COMPANY"] != NULL && $param["COMPANY"] != "")) {
                $list = $this->PaymentModel->DtOsPayment1Reversal($param);
            }
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

    public function SavePaymentReversal() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->SavePaymentReversal($param, $this->GetIpAddress());
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

    public function getRefVoucher(){
        $param = $this->input->post();
        try {
            $DOCNUMBER = $param['DOCNUMBER'];
            $q  = "SELECT VOUCHERNO,
                       AMOUNTBANK,
                       AMOUNT,
                       ref_voucher
                  FROM PAYMENT PY
                       INNER JOIN CF_TRANSACTION CF ON (PY.CFTRANSID = CF.ID)
                       INNER JOIN CF_TRANSACTION PO
                          ON (CF.COMPANY = PO.COMPANY AND CF.DOCREF = PO.DOCNUMBER)
                 WHERE     PO.DOCNUMBER = '$DOCNUMBER'
                       AND VOUCHERNO NOT IN (SELECT REF_VOUCHER
                                               FROM PAYMENT
                                              WHERE REF_VOUCHER IS NOT NULL)
                       AND REF_VOUCHER IS NULL";
            $company = $this->db->query($q)->result();
            
            $this->resource = array(
                'status' => 200,
                'data' => $company
            );
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        $this->SendResponse();
    }

    public function SavePayment() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->SavePayment($param, $this->GetIpAddress());
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

    public function ShowDataPaid() {
        try {
            $param = $_POST;
            $list = [
                "data" => [],
                "recordsFiltered" => 0,
                "recordsTotal" => 0
            ];
            // if (
            //         $param["MONTH"] != NULL && $param["YEAR"] != "" ||
            //         $param["YEAR"] != NULL && $param["MONTH"] != ""
            // ) {
                $list = $this->PaymentModel->ShowDataPaid($param);
            // }
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

    public function EditPayment() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->EditPayment($param, $this->GetIpAddress());
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

    public function DeletePayment() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->DeletePayment($param, $this->GetIpAddress());
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

    public function SaveOtherPayment() {
        $param = $this->input->post();
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->SaveOtherPayment($param, $this->GetIpAddress());
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

    public function DeleteOtherPayment() {
        try {
            $param = $this->input->post();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            $return = $this->PaymentModel->DeleteOtherPayment($param, $this->GetIpAddress());
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

}
