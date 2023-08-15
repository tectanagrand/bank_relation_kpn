<?php

class MyHookClass {
    public function GetIpAddress() {
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif (
                array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
        ) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {

            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        if (!isset($the_ip) || $the_ip == 0 || $the_ip == null || $the_ip == '')
            $the_ip = '127.0.0.1';

        return $the_ip;
    }

    public function preControllerMethod() {
        $CI = &get_instance() ;
        $CI->load->model('ReportGenModel') ;
        $current_controller = $CI->router->fetch_class();
        $current_method = $CI->router->fetch_method();
        var_dump($current_controller, $current_method);
        if($current_controller === 'KmkController' && $current_method == 'ShowDataPaymentRequest') {
            $allq = "select fm.UUID , fm.PK_NUMBER from funds_master fm left join funds_detail_ki fd on fd.uuid = fm.uuid where fm.credit_type = 'KI' and fm.isactive = 1 and fd.isactive = 1" ;
            $all = $this->db->query($allq)->result();
            // echo "<pre>";
            // var_dump($all); 
            // exit;
            foreach($all as $item) {
                $param = [
                    'UUID' => $item->UUID,
                    'PK_NUMBER' => $item->PK_NUMBER
                ] ;
                try {
                    $list = $this->ReportGenModel->SaveReportKI($param, $this->GetIpAddress()) ;
                }
                catch (Exception $ex) {
                    echo $ex->getMessage();
                }
            }
        }
    }
}