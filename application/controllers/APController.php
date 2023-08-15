    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('MAX_EXECUTION_TIME', '6000'); 
ini_set('max_input_time', '6000'); 

class APController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

    public function getDataManual() {
        ini_set('display_errors', 'On');
        // result
        // echo json_encode($dataJson['d']['results'],true);
        try {
            $return = [];
            $loop = 12;
            $year = 2022;
            // $mulaitahun =
            for($i = 1;$i<=12;$i++){
                
                if($i < 10){
                    $bulan = "0".$i;                    
                    }else{
                    $bulan = $i;
                    
                }

                $bulanto = strval($bulan)+1;
                if($bulanto < 10){
                    $bulanto = "0".$bulanto;                    
                    }else{
                    $bulanto = $bulanto;
                    
                }
                
                $tohari = "01";
                if($bulan == 12){
                    $tohari     = "31";
                    $bulanto      = 12;
                }
                $PERIODFROM  = $year.'-'.$bulan.'-01T00:00:00';
                $PERIODTO    = $year.'-'.$bulanto.'-'.$tohari.'T00:00:00';

                // var_dump($PERIODTO);exit;
                // $PERIODTO    = '2022-'..'02-17'.'T'.'00:00:00';
                $username = 'KPN-IT-INV';
                $password = 'Kpn@2022';
                $where = "";
                $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_APPINO_SRV/APPINOSet?$"."format=json&$"."filter=(DocDate%20ge%20datetime%27".$PERIODFROM."%27)and(DocDate%20le%20datetime%27".$PERIODTO."%27)";
                // var_dump($url); exit;
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //timeout after 30 seconds
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                // curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
                curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                // var_dump($ch); exit;
                $result = curl_exec ($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
                if(curl_errno($ch))
                {
                    echo 'Curl error: ' . curl_error($ch). curl_errno($ch);
                }
                echo $ch;
                curl_close ($ch);
                $dataJson = json_decode($result, true);
                // var_dump($dataJson);exit;

                // $result = $this->DlapModel->DataUpload($dataJson['d']['results']);
                $result = $this->DataInput($dataJson['d']['results']);
            }
            
            
            // var_dump($result);
            if ($result) {
                $result = [
                    'status' => 200,
                    'for-ke' => $i,
                    'data' => $result
                ];
            } else {
                $result = [
                    'status' => 500,
                    'for-ke' => $i,
                    'data' => $result
                ];
            }
        } catch (Exception $ex) {
            $result = array(
                'status' => 500,
                'for-ke' => $i,
                'data' => $ex->getMessage()
            );
        }
        echo "<pre>";
        print_r($result);
    }

    // manual
    public function getDataWithDocnumber() {
        ini_set('display_errors', 'On');
        // result
        // echo json_encode($dataJson['d']['results'],true);
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            // d.PERIODFROM = moment($('#PERIODFROM').val()).format('YYYY-MM-DD'+'T'+'00:00:00');
                        // d.PERIODTO = moment($('#PERIODTO').val()).format('YYYY-MM-DD'+'T'+'00:00:00');

            // $PERIODFROM  = '2023-02-17'.'T'.'00:00:00';
            // $PERIODTO    = '2023-02-17'.'T'.'00:00:00';
            // $PERIODFROM = Date('Y-m-d').'T'.'00:00:00';
            // $PERIODTO   = Date('Y-m-d').'T'.'00:00:00';
            // $COMPANY    = "";
            $manual  = "";
            $fromUrl = $this->input->get('DOCNUMBER');
            $DOCNUMBER  = $fromUrl ? $fromUrl : $manual;
            // $username = 'gpfn-mm';
            // $password = 'Palm@123';
            $username = 'KPN-IT-INV';
            $password = 'Kpn@2022';
            $where = "";
            // var_dump();exit;
            // if($COMPANY != ""){
            //     $where .= "and(Company%20eq%20%27".$COMPANY."%27)";
            // }
            // if($DOCNUMBER != '' || $DOCNUMBER != null){
            //     $where .= "and(PinoNo%20eq%20%27$DOCNUMBER%27)";
            //     // $where .= "and(PinoNo%20eq%20%27%27)";
            //     // (PinoNo%20eq%20%271009009810%27)
                
            // }
            // $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_APPINO_SRV/APPINOSet?$"."format=json&$"."filter=(DocDate%20ge%20datetime%27".$PERIODFROM."%27)and(DocDate%20le%20datetime%27".$PERIODTO."%27)";

            // http://erpdev-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_APPINO_SRV/APPINOSet?$filter=(Company%20eq%20%27EU%27)and(PinoNo%20eq%20%271009009810%27)and(DocDate%20ge%20datetime%272020-01-01T00:00:00%27)and(DocDate%20le%20datetime%272020-12-31T00:00:00%27)&$format=json
            $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_APPINO_SRV/APPINOSet?$"."format=json&$"."filter=(PinoNo%20eq%20%27$DOCNUMBER%27)";
            // var_dump($url); exit;
            // $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_APPINO_SRV/APPINOSet?$"."format=json&$"."filter=(DocDate%20ge%20datetime%27".$PERIODFROM."%27)and(DocDate%20le%20datetime%27".$PERIODTO."%27)".$where;
            // echo "<pre>";
            //var_dump($url);exit;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //timeout after 30 seconds
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            // curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            $result = curl_exec ($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
            curl_close ($ch);
            $dataJson = json_decode($result, true);
            // var_dump($dataJson);exit;
            
            // $result = $this->DlapModel->DataUpload($dataJson['d']['results']);
            $result = $this->DataInput($dataJson['d']['results']);
            
            // var_dump($result);
            if ($result['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $result['MESSAGE']
                ];
            } else {
                $this->resource = [
                    'status' => 500,
                    'data' => $result['MESSAGE']
                ];
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        echo "<pre>";
        print_r($result);
    }

    //automatic
    public function getData() {
        ini_set('display_errors', 'On');
        // result
        // echo json_encode($dataJson['d']['results'],true);
        try {
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => ''
            ];
            // d.PERIODFROM = moment($('#PERIODFROM').val()).format('YYYY-MM-DD'+'T'+'00:00:00');
                        // d.PERIODTO = moment($('#PERIODTO').val()).format('YYYY-MM-DD'+'T'+'00:00:00');

            $PERIODFROM    = '2023-02-03'.'T'.'00:00:00';
            $PERIODTO    = '2023-03-06'.'T'.'00:00:00';
            // $PERIODFROM = Date('Y-m-d').'T'.'00:00:00';
            // $PERIODTO   = Date('Y-m-d').'T'.'00:00:00';
            // $COMPANY    = $this->input->get_post('COMPANY');
            // $DOCNUMBER  = "1023004620";
            // $username = 'gpfn-mm';
            // $password = 'Palm@123';
            $username = 'KPN-IT-INV';
            $password = 'Kpn@2022';
            $where = "";
            // if($COMPANY != '0'){
            //     $where .= "and(Company%20eq%20%27".$COMPANY."%27)";
            // }
            // if($DOCNUMBER != '' || $DOCNUMBER != null){
            //     $where .= "and(PinoNo%20eq%20%27$DOCNUMBER%27)";
            //     // $where .= "and(PinoNo%20eq%20%27%27)";
            //     // (PinoNo%20eq%20%271009009810%27)
                
            // }
            // http://erpdev-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_APPINO_SRV/APPINOSet?$filter=(Company%20eq%20%27EU%27)and(PinoNo%20eq%20%271009009810%27)and(DocDate%20ge%20datetime%272020-01-01T00:00:00%27)and(DocDate%20le%20datetime%272020-12-31T00:00:00%27)&$format=json
            $url = "http://erpprd-gm.gamasap.com:8000//sap/opu/odata/sap/ZGW_APPINO_SRV/APPINOSet?$"."format=json&$"."filter=(DocDate%20ge%20datetime%27".$PERIODFROM."%27)and(DocDate%20le%20datetime%27".$PERIODTO."%27)".$where;
            // echo "<pre>";
            // var_dump($url);exit;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //timeout after 30 seconds
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            // curl_setopt($ch, CURLOPT_HTTPHEADER,["Authorization: Basic ".base64_encode($username.":".$password)]);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            $result = curl_exec ($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
            curl_close ($ch);
            $dataJson = json_decode($result, true);
            // var_dump($dataJson);exit;
            
            // $result = $this->DlapModel->DataUpload($dataJson['d']['results']);
            $result = $this->DataInput($dataJson['d']['results']);
            
            // var_dump($result);
            if ($result['STATUS'] == true) {
                $this->resource = [
                    'status' => 200,
                    'data' => $result['MESSAGE']
                ];
            } else {
                $this->resource = [
                    'status' => 500,
                    'data' => $result['MESSAGE']
                ];
            }
        } catch (Exception $ex) {
            $this->resource = array(
                'status' => 500,
                'data' => $ex->getMessage()
            );
        }
        echo "<pre>";
        print_r($result);
    }

    public function DataInput($param){
        ini_set("display_errors", 'On');
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $data = [];
            $UUID       = $this->uuid->v4();
            $EXTSYSTEM  = 'SAPHANA';
            $DOCTYPE    = 'INV';
            $USERNAME   = "ERPKPN";
            $idx        = 0;
            $this->db->where('CREATED_BY','NEWAPI_AP');
            $this->db->delete('TEMP_UPLOAD_PO');

            foreach ($param as $value) {
                $status = 0;
                $dt = [
                    'UUID' => $UUID,
                    'ID' => $idx,
                    'COMPANY' => '',
                    'COMPANYCODE' => strval($value['Company']),
                    'BUSINESSUNIT' => '',
                    'BUSINESSUNITCODE' => strval($value['BusArea']),
                    'DEPARTMENT' => '',
                    'DEPARTMENTCODE' => trim(strval($value['Department'])),
                    'DOCDATE' => trim(strval($value['DocdateShow'])),
                    'DOCNUMBER' => trim(strval($value['PinoNo'])),
                    'DOCREF' => trim(strval($value['DocRef'])),
                    'VENDORCODE' => trim(strval($value['ThirdParties'])),
                    'TRANS_LOC' => trim(strval($value['PurchaseLoc'])),
                    'BASELINEDATE' => trim(strval($value['BaseDate'])),
                    'PAYTERM' => trim(strval($value['PayTerms'])),
                    'MATERIALCODE' => trim(strval($value['ItemCode'])),
                    'REMARKS' => trim(strval($value['Remarks'])),
                    'AMOUNT_INCLUDE_VAT' => trim(strval($value['TotalIncppn'])),
                    'AMOUNT_PPH' => trim(strval($value['Pph'])),
                    'STATUSH' => 0,
                    'STATUSD' => $status,
                    'MESSAGEH' => '',
                    'MESSAGED' => '',
                    'EXTSYSTEM' => 'SAPHANA',
                    'DOCTYPE' => 'INV',
                    'CURRENCY' => $value['Currency'],
                    'ISADENDUM' => $value['IsAddendum'],
                    'RATE' => $value['Rate'],
                    'INVOICEVENDORNO' => $value['InvNo'],
                    'AP_NO' => $value['ApAccNo'],
                    'CREATED_BY' => 'NEWAPI_AP'
                ];

                if ($dt["DOCDATE"] != NULL || $dt["DOCDATE"] != '') {
                    $tahun  = substr($dt['DOCDATE'],0,4);
                    $bulan  = substr($dt['DOCDATE'],4,2);
                    $hari   = substr($dt['DOCDATE'],6,2);

                    $dt['DOCDATE'] = $bulan . "/" . $hari . "/" . $tahun;
                    if (!is_numeric($tahun) || !is_numeric($hari) || !is_numeric($bulan) || $bulan > 12) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format Date Not Valid !!!";
                    }
                }

                if ($DOCTYPE == 'INV') {
                    if ($dt["DOCREF"] == NULL || $dt["DOCREF"] == '') {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Doc Ref can't be empty !!";
                    }
                }
                if ($dt["AMOUNT_INCLUDE_VAT"] != NULL && $dt["AMOUNT_INCLUDE_VAT"] != '') {
                    $number = $dt["AMOUNT_INCLUDE_VAT"];
                    if (!is_numeric($number)) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format Amount Include Vat Not Valid !!!";
                    }
                }
                if ($dt["AMOUNT_PPH"] != NULL && $dt["AMOUNT_PPH"] != '') {
                    $number = $dt["AMOUNT_PPH"];
                    if (!is_numeric($number)) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format Amount PPH Not Valid !!!";
                    }
                }
                if ($dt["PAYTERM"] != NULL && $dt["PAYTERM"] != '') {
                    if (!is_numeric($dt["PAYTERM"])) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format Payterm Not Valid !!!";
                    }
                }
                if ($dt["BASELINEDATE"] != NULL && $dt["BASELINEDATE"] != '') {
                    $tahun  = substr($dt['BASELINEDATE'],0,4);
                    $bulan  = substr($dt['BASELINEDATE'],4,2);
                    $hari   = substr($dt['BASELINEDATE'],6,2);

                    $dt['BASELINEDATE'] = $bulan . "/" . $hari . "/" . $tahun;
                    if (!is_numeric($tahun) || !is_numeric($hari) || !is_numeric($bulan) || $bulan > 12) {
                        $dt['STATUSD'] = 1;
                        $dt['MESSAGED'] = "Format Date Not Valid !!!";
                    }
                    if ($dt["PAYTERM"] != NULL && $dt["PAYTERM"] != '') {
                        if (is_numeric($dt["PAYTERM"])) {
                            $dt['DUEDATE'] = strval(date('m/d/Y', strtotime($dt['PAYTERM'] . " days", strtotime($tahun . $bulan . $hari))));
                        }
                    }
                }
                // if($dt["AMOUNT_INCLUDE_VAT"] == null || $dt["AMOUNT_INCLUDE_VAT"] == 0){
                //     throw new Exception('Amount Tidak Boleh Kosong.');        
                // }
                
            //                        array_push($data, $dt);
                $data[] = $dt;
                $this->db->flush_cache();
                $result = $this->db->insert('TEMP_UPLOAD_PO', $dt);
                $idx++;
            //                        $result = $this->db->insert('TEMP_UPLOAD_PO', $dt);
            }
            // echo "<pre>";
            // var_dump($data);exit;
            $this->db->flush_cache();
            // $result = $this->db->insert_batch(TEMP_UPLOAD_PO, (array)$data);
            // end foreach
            // $this->db->flush_cache();
            if ($result) {
                // echo "<pre>";
                // var_dump(is_null($data[0]['AP_NO']));exit;
                $this->CheckValidation($EXTSYSTEM, $UUID, $USERNAME);
                foreach($data as $d => $rows){
                    // is_null($rows->AP_NO);exit;
                    if (is_null($rows['AP_NO']) === false) {
                        $this->CheckValidationAP_NO($EXTSYSTEM, $UUID, $USERNAME);
                    }
                    // $this->CheckValidationAP_NO($EXTSYSTEM, $UUID, $USERNAME);  
                }
                
    //Update Error Di Header PO
                $SQL = " UPDATE TEMP_UPLOAD_PO UPO SET (STATUSH, MESSAGEH) = (SELECT DECODE(TP.JML, 1, (SELECT DISTINCT TEU.STATUSD
                                                        FROM TEMP_UPLOAD_PO TEU
                                                        WHERE TEU.UUID = TP.UUID 
                                                        AND (TEU.COMPANYCODE = TP.COMPANYCODE OR TEU.COMPANYCODE IS NULL)
                                                        AND (TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE OR TEU.BUSINESSUNITCODE IS NULL)
                                                        AND (TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE OR TEU.DEPARTMENTCODE IS NULL)
                                                        AND TEU.DOCNUMBER = TP.DOCNUMBER
                                                        AND TEU.DOCREF = TP.DOCREF 
                                                        AND TEU.INVOICEVENDORNO = TP.INVOICEVENDORNO), 1) AS STATUSH, 
                                    DECODE(TP.JML, 1, (SELECT DISTINCT TEU.MESSAGED
                                                        FROM TEMP_UPLOAD_PO TEU
                                                        WHERE TEU.UUID = TP.UUID 
                                                        AND (TEU.COMPANYCODE = TP.COMPANYCODE OR TEU.COMPANYCODE IS NULL)
                                                        AND (TEU.BUSINESSUNITCODE = TP.BUSINESSUNITCODE OR TEU.BUSINESSUNITCODE IS NULL)
                                                        AND (TEU.DEPARTMENTCODE = TP.DEPARTMENTCODE OR TEU.DEPARTMENTCODE IS NULL)
                                                        AND TEU.DOCNUMBER = TP.DOCNUMBER AND TEU.DOCREF = TP.DOCREF AND TEU.INVOICEVENDORNO = TP.INVOICEVENDORNO), 'Please, Check Data Doc Date until Payterm must be the same, and there are no errors lined up !!') AS MESSAGEH
                            FROM (SELECT TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER,TUP.DOCREF,TUP.INVOICEVENDORNO, COUNT(*) AS JML
                                    FROM (SELECT DISTINCT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER, DOCDATE, VENDOR, DOCTYPE, DOCREF,INVOICEVENDORNO, TRANS_LOC,
                                                    BASELINEDATE, PAYTERM, MESSAGED
                                            FROM TEMP_UPLOAD_PO
                                            WHERE UUID = ?) TUP
                                    GROUP BY TUP.UUID, TUP.COMPANYCODE, TUP.BUSINESSUNITCODE, TUP.DEPARTMENTCODE, TUP.DOCNUMBER,TUP.DOCREF,TUP.INVOICEVENDORNO) TP 
                            WHERE (TP.COMPANYCODE = UPO.COMPANYCODE OR TP.COMPANYCODE IS NULL)
                                AND (TP.BUSINESSUNITCODE = UPO.BUSINESSUNITCODE OR TP.BUSINESSUNITCODE IS NULL)
                                AND (TP.DEPARTMENTCODE = UPO.DEPARTMENTCODE OR TP.DEPARTMENTCODE IS NULL)
                                AND TP.DOCNUMBER = UPO.DOCNUMBER AND UPO.DOCREF = TP.DOCREF AND UPO.INVOICEVENDORNO = TP.INVOICEVENDORNO
                                AND TP.UUID = UPO.UUID) WHERE UPO.UUID = ?";
                $result = $this->db->query($SQL, [$UUID, $UUID]);
                
    //                  Select Header PO and Get Detail PO
                $SQL = "SELECT DISTINCT * FROM TEMP_UPLOAD_PO TUP WHERE TUP.UUID = ?";
                $data = $this->db->query($SQL, [$UUID])->result();
                // $SQL = "SELECT DISTINCT TUP.COMPANY, TUP.COMPANYCODE, TUP.BUSINESSUNIT, TUP.BUSINESSUNITCODE, TUP.DEPARTMENT, TUP.DEPARTMENTCODE, TUP.DOCNUMBER, TUP.DOCREF,TUP.STATUSH, TUP.MESSAGEH, TP.JML, TP.AMOUNT_INCLUDE_VAT, TP.AMOUNT_PPH
                //         FROM TEMP_UPLOAD_PO TUP
                //         INNER JOIN (SELECT UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER,DOCREF, COUNT(*) AS JML, SUM(AMOUNT_INCLUDE_VAT) AS AMOUNT_INCLUDE_VAT, SUM(AMOUNT_PPH) AS AMOUNT_PPH
                //                     FROM TEMP_UPLOAD_PO 
                //                     WHERE UUID = ?
                //                     GROUP BY UUID, COMPANYCODE, BUSINESSUNITCODE, DEPARTMENTCODE, DOCNUMBER,DOCREF) TP
                //                 ON TP.UUID = TUP.UUID
                //             AND (TP.COMPANYCODE = TUP.COMPANYCODE OR TP.COMPANYCODE IS NULL)
                //             AND (TP.BUSINESSUNITCODE = TUP.BUSINESSUNITCODE OR TP.BUSINESSUNITCODE IS NULL)
                //             AND (TP.DEPARTMENTCODE = TUP.DEPARTMENTCODE OR TP.DEPARTMENTCODE IS NULL)
                //             AND TP.DOCNUMBER = TUP.DOCNUMBER AND TP.DOCREF = TUP.DOCREF
                //         WHERE TUP.UUID = ?";
                // $data = $this->db->query($SQL, [$UUID, $UUID])->result();
                $SQL = "SELECT * FROM TEMP_UPLOAD_PO 
                            WHERE UUID = ?
                            AND (COMPANYCODE = ? OR COMPANYCODE IS NULL)
                            AND (BUSINESSUNITCODE = ? OR BUSINESSUNITCODE IS NULL)
                            AND (DEPARTMENTCODE = ? OR DEPARTMENTCODE IS NULL)
                            AND DOCNUMBER = ? AND DOCREF = ?";
                foreach ($data as $values) {
                    $DtParam = [$UUID, $values->COMPANYCODE, $values->BUSINESSUNITCODE, $values->DEPARTMENTCODE, $values->DOCNUMBER, $values->DOCREF];
                    $values->datadetail = $this->db->query($SQL, $DtParam)->result();
                }

                // $SQL = "DELETE FROM TEMP_UPLOAD_PO WHERE UUID = ?";
                // $result = $this->db->query($SQL, [$UUID]);

            // echo "<pre>";
            // var_dump($data);
            $data = json_encode($data, TRUE);
            $data = json_decode($data, TRUE);
            $UPLOAD_REF = date("Ymd_His") . "_NEWAPI_AP";
            foreach ($data as $value) {
                $ADENDUM = 'FALSE';
                if($value['STATUSH'] != "1" || $value['STATUSH'] != 1){
                    $checkSubgroup = $this->db->get_where('COMPANY',['ID' => $value['COMPANY']])->row();
                    $sub = '';
                    if($checkSubgroup->COMPANY_SUBGROUP == "DOWNSTREAM"){
                        $sub = "-DWS";
                    }
                    if ($value['ISADENDUM'] == "1") {
                        $ADENDUM = 'TRUE';
                    }
                    $dt = [
                        'EXTSYS' => $value['EXTSYSTEM'],
                        'DOCTYPE' => $value['DOCTYPE'],
                        'COMPANY' => $value['COMPANY'],
                        'BUSINESSUNIT' => $value['BUSINESSUNIT'],
                        'DEPARTMENT' => trim($value['DEPARTMENT'].$sub),
                        'DOCNUMBER' => $value['DOCNUMBER'],
                        'DOCREF' => $value['DOCREF'],
                        'VENDOR' => $value['VENDOR'],
                        'TRANS_LOC' => $value['TRANS_LOC'],
                        'PAYTERM' => $value['PAYTERM'],
                        'VENDOR' => $value['VENDOR'],
                        'AMOUNT_INCLUDE_VAT' => abs($value['AMOUNT_INCLUDE_VAT']),
                        'AMOUNT_PPH' => $value['AMOUNT_PPH'],
                        'TOTAL_BAYAR' => abs($value['AMOUNT_INCLUDE_VAT'])-$value['AMOUNT_PPH'],
                        'ISADENDUM' => $ADENDUM,
                        'CURRENCY' => $value['CURRENCY'],
                        'RATE' => $value['RATE'],
                        'INVOICEVENDORNO' => $value['INVOICEVENDORNO'],
                        'REMARK' => $value['REMARKS'],
                        'ISACTIVE' => 'TRUE',
                        'FCENTRY' => 'NEWAPI_AP',
                        'FCEDIT' => 'NEWAPI_AP',
                        'FCIP' => '127.0.0.1',
                        'UPLOAD_REF' => $UPLOAD_REF,
                        'VAT' => 0
                    ];

                    $res = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                    if ($value['DOCDATE'] == NULL || $value['DOCDATE'] == '') {
                        $res = $res->set('DOCDATE', 'NULL', false);
                    } else {
                        $res = $res->set('DOCDATE', "TO_DATE('" . $value['DOCDATE'] . "','mm/dd/yyyy')", false);
                    }
                    if ($value['BASELINEDATE'] == NULL || $value['BASELINEDATE'] == '') {
                        $res = $res->set('BASELINEDATE', 'NULL', false);
                    } else {
                        $res = $res->set('BASELINEDATE', "TO_DATE('" . $value['BASELINEDATE'] . "','mm/dd/yyyy')", false);
                    }
                    if ($value['DUEDATE'] == NULL || $value['DUEDATE'] == '') {
                        $res = $res->set('DUEDATE', 'NULL', false);
                    } else {
                        $res = $res->set('DUEDATE', "TO_DATE('" . $value['DUEDATE'] . "','mm/dd/yyyy')", false);
                    }
                    $dt['ID'] = $this->uuid->v4();
                    $res = $res->set($dt)->insert('CF_TRANSACTION');

                    foreach ($value['datadetail'] as $val) {
                        $dat = [
                            'ID' => $dt['ID'],
                            'MATERIAL' => $val['MATERIAL'],
                            'REMARKS' => $val['REMARKS'],
                            'AMOUNT_INCLUDE_VAT' => $val['AMOUNT_INCLUDE_VAT'],
                            'AMOUNT_PPH' => $val['AMOUNT_PPH'],
                            'ISACTIVE' => 'TRUE',
                            'FCENTRY' => 'NEWAPI_AP',
                            'FCEDIT' => 'NEWAPI_AP',
                            'FCIP' => '1.1.1.1'
                        ];
                        $resd = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);
                        $resd = $resd->set($dat)->insert('CF_TRANSACTION_DET');
                        
                    }
                    
                }
            // end
            }

                // $SQL = "DELETE FROM TEMP_UPLOAD_PO WHERE UUID = ?";
                // $result = $this->db->query($SQL, [$UUID]);
        }
            // end if result
            
        $this->db->trans_commit();
        $return = [
            'STATUS' => TRUE,
            'MESSAGE' => $data
        ];
            
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            echo $ex->getMessage();
        }
        $this->db->close();
        return $return;
    }

    public function CheckValidation($EXTSYSTEM, $UUID, $USERNAME) {
//        Update Field Company and Pesan Error
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP
                   SET TUP.COMPANY = (SELECT c.ID
                                        FROM COMPANY C 
                                       INNER JOIN COMPANY_EXTSYS CE
                                               ON CE.COMPANY = C.ID
                                              AND CE.EXTSYSTEM = ?
                                       WHERE C.ISACTIVE = 1 
                                         AND CE.EXTSYSCOMPANYCODE = TUP.COMPANYCODE)
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Company Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.COMPANY IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Business Unit and Pesan Error
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.BUSINESSUNIT = (SELECT B.ID
                                            FROM BUSINESSUNIT B
                                           INNER JOIN BUSINESSUNIT_EXTSYS BE
                                                   ON BE.BUSINESSUNIT = B.ID
                                                  AND BE.EXTSYSTEM = ?
                                           WHERE B.ISACTIVE = 'TRUE'
                                             AND B.COMPANY = TUP.COMPANY
                                             AND BE.EXTSYSBUSINESSUNITCODE = TUP.BUSINESSUNITCODE)
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Business Unit Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.BUSINESSUNIT IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Docnumber cant empty, check ur document!!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.DOCNUMBER IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Docnumber cant empty, check ur document!!!'
                 WHERE TUP.STATUSD <> 1 
                   AND (TUP.DOCNUMBER IS NULL OR TUP.DOCNUMBER = '')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Department and Pesan Error
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.DEPARTMENT = (SELECT DEPARTMENT
                                           FROM USER_DEPART
                                          WHERE FCCODE = ?
                                            AND DEPARTMENT = TUP.DEPARTMENTCODE)
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$USERNAME, $UUID]);
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Department Not Granted !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.DEPARTMENT IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Vendor and Pesan Error
        $SQL = "UPDATE TEMP_UPLOAD_PO TP 
                   SET TP.VENDORCODE = (SELECT TUP.VENDORCODE
                                          FROM (SELECT TUP.COMPANYCODE, TUP.DOCNUMBER, MAX(TUP.VENDORCODE) AS VENDORCODE, COUNT(*) AS JML
                                                  FROM (SELECT DISTINCT TUP.COMPANYCODE, TUP.DOCNUMBER, TUP.VENDORCODE
                                                          FROM TEMP_UPLOAD_PO TUP
                                                         WHERE TUP.UUID = ?
                                                           AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR')) TUP
                                                         GROUP BY TUP.COMPANYCODE, TUP.DOCNUMBER) TUP
                                                 WHERE TUP.COMPANYCODE = TP.COMPANYCODE
                                                   AND TUP.DOCNUMBER = TP.DOCNUMBER)
                 WHERE TP.STATUSD <> 1
                   AND (TP.DOCTYPE <> 'INV' AND TP.DOCTYPE <> 'INV_AR')
                   AND TP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID, $UUID]);
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.VENDOR = (SELECT ID
                                       FROM SUPPLIER
                                      WHERE FCCODE = TUP.VENDORCODE
                                        AND ISACTIVE = 'TRUE')
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP
                      SET TUP.STATUSD = 1,
                      TUP.MESSAGED = 'Vendor Not Found !!!'
                      WHERE TUP.STATUSD <> 1
                      AND TUP.VENDOR IS NULL
                      AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Update Field Material and Pesan Error
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.MATERIAL = (SELECT ID
                                         FROM MATERIAL 
                                        WHERE FCCODE = TUP.MATERIALCODE
                                          AND EXTSYSTEM = ?
                                          AND ISACTIVE = 'TRUE')
                 WHERE TUP.STATUSD <> 1
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$EXTSYSTEM, $UUID]);
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                  SET TUP.STATUSD = 1,
                      TUP.MESSAGED = 'Material Not Found !!!'
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.MATERIAL IS NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET (TUP.STATUSD, TUP.MESSAGED) = (SELECT DECODE(TP.JML, 0, 1, 0) AS STATUSD, DECODE(TP.JML, 0, 'Material Group Not Found !!!', '') AS MESSAGED
                                                        FROM (SELECT TUP.EXTSYSTEM, TUP.MATERIAL, COUNT(MGI.MATERIAL) AS JML
                                                                FROM TEMP_UPLOAD_PO TUP
                                                                LEFT JOIN MATERIAL_GROUPITEM MGI
                                                                       ON MGI.MATERIAL = TUP.MATERIAL
                                                                      AND MGI.EXTSYSTEM = TUP.EXTSYSTEM
                                                               WHERE TUP.UUID = ?
                                                                 AND TUP.MATERIAL IS NOT NULL
                                                               GROUP BY TUP.EXTSYSTEM, TUP.MATERIAL) TP
                                                       WHERE TP.EXTSYSTEM = TUP.EXTSYSTEM
                                                         AND TP.MATERIAL = TUP.MATERIAL)
                 WHERE TUP.STATUSD <> 1 
                   AND TUP.MATERIAL IS NOT NULL
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID, $UUID]);

//        Check Doc Ref Ready For INV or INV_AR
        $SQL = "UPDATE TEMP_UPLOAD_PO UPO
                   SET (UPO.STATUSD, UPO.MESSAGED) = (SELECT DECODE(CT.JML, 0, 1, 0) AS STATUSD, DECODE(CT.JML, 0, 'Doc Ref Not Found !!', '') AS MESSAGED
                                                        FROM (SELECT COUNT(*) AS JML
                                                                FROM CF_TRANSACTION CTR
                                                               WHERE CTR.DOCNUMBER = UPO.DOCREF
                                                                 AND CTR.COMPANY = UPO.COMPANY) CT)
                 WHERE UPO.STATUSD <> 1
                   AND (UPO.DOCTYPE = 'INV' OR UPO.DOCTYPE = 'INV_AR')
                   AND UPO.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

//        Cek Docnumber Same
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Some Data Already Exists !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, TUP.DOCNUMBER) IN (
                   SELECT CONCAT(CFT.COMPANY, CFT.DOCNUMBER)
                            FROM CF_TRANSACTION CFT
                                WHERE ISACTIVE = 'TRUE'
                                    AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR'))
                   AND (TUP.DOCTYPE <> 'INV' AND TUP.DOCTYPE <> 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
        // var_dump($this->db->last_query());exit();
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Some Data Already Exists !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, CONCAT(TUP.DOCNUMBER, TUP.DOCREF)) IN (
                   SELECT CONCAT(CFT.COMPANY, CONCAT(CFT.DOCNUMBER, CFT.DOCREF))
                        FROM CF_TRANSACTION CFT
                            WHERE ISACTIVE = 'TRUE'
                                AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR'))
                   AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);

        // $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
        //            SET TUP.STATUSD = 1,
        //                TUP.MESSAGED = 'Some Data Already Exists 2 !!!'
        //          WHERE TUP.STATUSD <> 1
        //            AND CONCAT(TUP.COMPANY, CONCAT(CASE WHEN TUP.AP_NO IS NOT NULL THEN TUP.AP_NO ELSE TUP.DOCNUMBER END AS AP_NO, TUP.DOCREF)) IN (
        //            SELECT CONCAT(CFT.COMPANY, CONCAT(CFT.DOCNUMBER, CFT.DOCREF))
        //                 FROM $this->CF_TRANSACTION CFT
        //                     WHERE ISACTIVE = 'TRUE'
        //                         AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR'))
        //            AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR')
        //            AND TUP.UUID = ?";
        // $result = $this->db->query($SQL, [$UUID]);

    }

    public function CheckValidationAP_NO($EXTSYSTEM, $UUID, $USERNAME) {
        $SQL = "UPDATE TEMP_UPLOAD_PO TUP 
                   SET TUP.STATUSD = 1,
                       TUP.MESSAGED = 'Some Data Already Exists 2 !!!'
                 WHERE TUP.STATUSD <> 1
                   AND CONCAT(TUP.COMPANY, CONCAT(TUP.AP_NO, TUP.DOCREF)) IN (
                   SELECT CONCAT(CFT.COMPANY, CONCAT(CFT.DOCNUMBER, CFT.DOCREF))
                        FROM CF_TRANSACTION CFT
                            WHERE ISACTIVE = 'TRUE'
                                AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR'))
                   AND (TUP.DOCTYPE = 'INV' OR TUP.DOCTYPE = 'INV_AR')
                   AND TUP.UUID = ?";
        $result = $this->db->query($SQL, [$UUID]);
    }


}