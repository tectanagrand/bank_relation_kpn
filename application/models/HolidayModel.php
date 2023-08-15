<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class HolidayModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->library('encryption');
    }

    protected $table = 'HOLIDAY';
    // protected $tableCE = 'COMPANY_EXTSYS';
    protected $fillable;

    public function ShowData() {
        $result = $this->db->select('*')
                        ->from($this->table)
                        ->order_by('HOLIDAYDATE')->get()->result();
        $this->db->close();
        return $result;
    }

    public function getData($param, $location){
        $PERIOD = $param['PERIOD'];

        $this->db->trans_begin();
            $result = FALSE;
            $res = FALSE;
            $USERNAME = USERNAME;
            $jsonfile = (JSONFILE.'holiday.json');
            $dataApi = file_get_contents($jsonfile);
            $data = json_decode($dataApi, true);

            $cek = $this->db->select('*')
                        ->from($this->table)
                        ->where([
                            'PERIOD_YEAR' => $PERIOD
                        ])->get()->result();
            if ($cek == NULL) {
                foreach ($data as $key => $value) {
                    $HOLIDAYNAME        = $value['holiday_name'];

                    $IS_NATIONAL_HOLIDAY = '';
                    if (isset($value['is_national_holiday']) && $value['is_national_holiday'] == 0) $IS_NATIONAL_HOLIDAY = 'FALSE';
                    if (isset($value['is_national_holiday']) && $value['is_national_holiday'] == 1) $IS_NATIONAL_HOLIDAY = 'TRUE';
                    
                    $date = DateTime::createFromFormat("Y-m-d", $value['holiday_date']);
                    $getYear = $date->format("Y");
                    $dat = [
                        'HOLIDAYNAME' => $value['holiday_name'],
                        'IS_NATIONAL_HOLIDAY' => $IS_NATIONAL_HOLIDAY,
                        'PERIOD_YEAR' => $getYear
                    ];
                    $result1 = $this->db->set("HOLIDAYDATE","TO_DATE('" . $value['holiday_date'] . "','yyyy-mm-dd')", false);

                    //var_dump($dat);exit;
                    if ($IS_NATIONAL_HOLIDAY == 'TRUE') {
                        $res = $result1->set($dat)->insert($this->table);
                    }
                    // var_dump($this->db->lastquery());exit;
                }
            }
        
        if ($res) {
            $this->db->trans_commit();
            $return = [
                'STATUS' => TRUE,
                'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
        } else {
            throw new Exception('Data Save Failed !!');
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => 'Data Save Failed !!'
            ];
        }
            
        $this->db->close();
        return $return;
    }

    /*public function culrApi($url) {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://api-harilibur.vercel.app/api?year=");
        //curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 4);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //    "cache-control: no-cache"
        //));
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://api-harilibur.vercel.app/api?year=",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_USERAGENT => $_SERVER["HTTP_USER_AGENT"],
            CURLOPT_SSLVERSION => 4,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "GET_POST",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $url,
            CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
            ),
        ));
        if(curl_exec($ch) === false) {
            echo 'Curl error: ' . curl_error($ch);
            echo curl_errno($ch);
        } else {
            echo 'Operation completed without any errors';
        }     
        curl_close($ch);
        //$output = curl_exec($curl); 
        //curl_close($curl);
        return $output;
    }*/
}