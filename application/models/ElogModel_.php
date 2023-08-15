<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class ElogModel extends BaseModel {

    public $variable;

    public function __construct()
    {
        parent::__construct();
    }

    function LogSave($param, $Location) {
        try {
            $DEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME     = $this->session->userdata('username');
            $this->db->trans_begin();
            $result = FALSE;
            $count = 0;
            if($param['ACTION'] == 'ADD'){
                $no_urut        = $param['no_urut'];
                $INVOICE_CODE   = $param['INVOICE_CODE'];
                $NO_PO          = $param['NO_PO'];
                $VENDOR         = $param['VENDOR'];
                $COMPANY        = $param['COMPANY'];
                $CURRENCY       = $param['CURRENCY'];
                $AMOUNT         = $param['AMOUNT'];
                $NOTES          = $param['NOTES'];
                
                if ($no_urut){
                    $count = count($no_urut);    
                }
                for($i=0; $i < $count; $i++){
                    if ($no_urut[$i] > 0){

                        if($DEPARTMENT != 'HRD' && $INVOICE_CODE[$i] == NULL || $INVOICE_CODE[$i] == ''){
                            throw new Exception("Invoice Number Cant Be Empty");
                        }
                        $q = $this->db->get_where('LOG_FIRSTRECEIPT',array('INVOICE_CODE' => $INVOICE_CODE[$i]));
                        if($q->num_rows() > 0){
                            throw new Exception("$INVOICE_CODE[$i] Already Exist", 1);
                        }

                        $tAMOUNT       = intval(preg_replace("/[^\d\.\-]/","",$AMOUNT[$i]));
                        $SQL = "SELECT (maxno + 1) NUMMAX
                                  FROM (SELECT NVL(MAX (SUBSTR (NO_RECEIPT_DOC, 5)),0) maxno
                                          FROM LOG_FIRSTRECEIPT
                                         WHERE     TO_CHAR (CREATED_AT,'mm') = TO_CHAR (SYSDATE, 'MM')
                                               AND TO_CHAR (CREATED_AT,'yy') = TO_CHAR (SYSDATE, 'YY')) A";
                        
                        $auto = $this->db->query($SQL)->row()->NUMMAX;
                        // var_dump($auto);exit();

                        $no = $auto;

                        $dt = [
                            'NO_RECEIPT_DOC' => date('ym').sprintf("%04s", $no),
                            'INVOICE_CODE' => $INVOICE_CODE[$i],
                            'NO_PO' => $NO_PO[$i],
                            'VENDOR' => $VENDOR[$i],
                            'COMPANY' => $COMPANY[$i],
                            'CURRENCY' => $CURRENCY[$i],
                            'NOTES' => $NOTES[$i],
                            'AMOUNT' => $tAMOUNT,
                            'FIRST_DEPT' => $DEPARTMENT

                        ];
                        $result1 = $this->db->set('CREATED_AT', "SYSDATE", false);

                        $dt['UUID']           = $this->uuid->v4();
                        // $dt['NO_RECEIPT_DOC'] = 'RN-'.$DEPARTMENT.date('ym').sprintf("%04s", $no);
                        $dt['CREATED_BY'] = $USERNAME;
                        $result1 = $result1->set($dt)->insert('LOG_FIRSTRECEIPT');

                        if($result1 == true){
                            $trans = array(
                                'NO_RECEIPT_DOC' => $dt['NO_RECEIPT_DOC'],
                                'INVOICE_CODE'   => $INVOICE_CODE[$i],
                                'NO_PO'          => $NO_PO[$i],
                                'VENDOR' => $VENDOR[$i],
                                'AMOUNT' => $tAMOUNT,
                                'CREATED_BY' => $USERNAME,
                                'DEPT' => $DEPARTMENT,
                                'POS'   => 1,
                                'STATUS' => 0
                            );
                        }
                            
                        $insertTrans = $this->db->set('DATE_RECEIPT', "SYSDATE", false);
                        $insertTrans = $insertTrans->set($trans)->insert('LOG_TRANSACTION');

                        if ($result1 && $insertTrans) {
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
                    }
                    $no++;
                }
            }else{
                $AMOUNT         = $param['AMOUNT'];
                $INVOICE_CODE   = $param['INVOICE_CODE'];
                $NO_RECEIPT_DOC = $param['NO_RECEIPT_DOC'];

                $tAMOUNT = intval(preg_replace("/[^\d\.\-]/","",$AMOUNT));

                $dt = array(
                    'AMOUNT' => $tAMOUNT
                );

                $this->db->where('UUID',$param['UUID']);
                $up_fr = $this->db->update('LOG_FIRSTRECEIPT',$dt);

                $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
                $this->db->where('INVOICE_CODE',$INVOICE_CODE);
                $up_trans = $this->db->update('LOG_TRANSACTION',$dt);

                if ($up_fr && $up_trans) {
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

    function ShowData($param) {
        $fDate      = $param['fDate'];
        $DEPARTMENT = $this->session->userdata('DEPARTMENT');
        if($DEPARTMENT != 'IT'){
            $where = array("L.FIRST_DEPT" => $DEPARTMENT, "to_char(CREATED_AT,'mm-dd-yyyy')" => $fDate);
        }else{
            $where = array("to_char(CREATED_AT,'mm-dd-yyyy')" => $fDate);
        }
        $result = $this->db->select('L.*,S.FCNAME AS VENDORNAME, C.COMPANYNAME')
                        ->from("LOG_FIRSTRECEIPT L")
                        ->join("COMPANY C", 'C.ID = L.COMPANY', 'inner')
                        ->join("SUPPLIER S", 'S.ID = L.VENDOR', 'inner')
                        ->where($where)
                        // ->where("to_char(CREATED_AT,'mm-dd-yyyy')",$fDate)
                        ->order_by('L.ID ASC')->get()->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    function ShowSendData($param) {
        $DEPT         = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];

        if($COMPANY != "0"){
            $WHERE = " AND L.COMPANY = '$COMPANY'";
        }
        if($DEPT == 'IT'){
            $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT,LAST_REMARK, FIRST_DEPT,VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, LAST_REMARK,FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY INVOICE_CODE ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID,LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LS.LAST_REMARK, L.FIRST_DEPT, LT.POS,LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN (SELECT *
                            FROM (  SELECT NO_RECEIPT_DOC,
                                            NO_PO,
                                           REMARK AS LAST_REMARK,
                                           DATE_RECEIPT,
                                           MAX (DATE_RECEIPT)
                                              OVER (PARTITION BY NO_RECEIPT_DOC)
                                              max_date
                                      FROM LOG_TRANSACTION
                                     WHERE POS IN (2)
                                  GROUP BY NO_RECEIPT_DOC,NO_PO, REMARK, DATE_RECEIPT)
                           WHERE DATE_RECEIPT = MAX_DATE) LS ON LS.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC
                                  WHERE LT.POS IN(1,3) AND LT.STATUS = '0' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO,DATE_RECEIPT, LAST_REMARK, FIRST_DEPT, VENDORNAME,CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
        }
        else{
            $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO,TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, LAST_REMARK, FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS,STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, LAST_REMARK, FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY INVOICE_CODE ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LS.LAST_REMARK, L.FIRST_DEPT, LT.POS,LT.STATUS, S.FCNAME AS VENDORNAME,L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN (SELECT *
                            FROM (  SELECT NO_RECEIPT_DOC,
                                            NO_PO,
                                           REMARK AS LAST_REMARK,
                                           DATE_RECEIPT,
                                           MAX (DATE_RECEIPT)
                                              OVER (PARTITION BY NO_RECEIPT_DOC)
                                              max_date
                                      FROM LOG_TRANSACTION
                                     WHERE POS IN (2) AND SEND_TO = '$DEPT'
                                  GROUP BY NO_RECEIPT_DOC,NO_PO, REMARK, DATE_RECEIPT)
                           WHERE DATE_RECEIPT = MAX_DATE) LS ON LS.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC WHERE LT.POS IN(1,3) AND LT.SEND_TO = '$DEPT' OR LT.DEPT = '$DEPT' AND LT.STATUS = '0' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, LAST_REMARK, FIRST_DEPT, VENDORNAME,CURRENCY, COMPANYNAME, POS,STATUS ORDER BY ID DESC) WHERE RANKI = 1";
        }
        $result = $this->db->query($q2)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    function ShowReceiveData($param) {
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];

        if($COMPANY != "0"){
            $WHERE = " AND L.COMPANY = '$COMPANY'";
        }

        if($DEPT == 'IT'){
            $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, MAX (POS) AS POS, STATUS, RANK () OVER (PARTITION BY INVOICE_CODE ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE LT.POS = '2' AND LT.STATUS = '1' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, POS,STATUS ORDER BY ID DESC) WHERE RANKI = 1";
        }
        else{
            $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME,CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY INVOICE_CODE ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE LT.SEND_TO = '$DEPT' AND LT.POS = '2' AND LT.STATUS = '1' ".$WHERE;
            $q2 .= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
        }
        $result = $this->db->query($q2)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    function sendReceipt($param,$Location){
        $NO_RECEIPT_DOC = $param['NO_RECEIPT_DOC'];
        $DEPARTMENT     = $param['DEPARTMENT'];
        $REMARKS        = $param['REMARKS'];
        $POS            = $param['POS'];
        
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME     = $this->session->userdata('username');

            if($DEPARTMENT == $sessDEPARTMENT){
                throw new Exception("Send Failed");
            }

            $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC'";
            $get  = $this->db->query($qget)->row();

            $this->db->set('STATUS',1);
            $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
            $this->db->where('POS',$POS);
            $this->db->update('LOG_TRANSACTION');

            $pos = 2;

            $dept_ke      = $sessDEPARTMENT;
            $dt = [
                    'NO_RECEIPT_DOC' => $get->NO_RECEIPT_DOC,
                    'INVOICE_CODE' => $get->INVOICE_CODE,
                    'NO_PO'      => $get->NO_PO,
                    'VENDOR'     => $get->VENDOR,
                    'AMOUNT'     => $get->AMOUNT,
                    'CREATED_BY' => $USERNAME,
                    'UPDATED_BY' => $USERNAME.' - '.$sessDEPARTMENT,
                    'SEND_TO'    => $DEPARTMENT,
                    'DEPT'       => $sessDEPARTMENT,
                    'REMARK'     => $REMARKS,
                    'POS'  => $pos,
                    'STATUS' => 1
            ];
                    
            $result1 = $this->db->set($dt)
                        ->set('DATE_RECEIPT','SYSDATE',false)
                        ->insert('LOG_TRANSACTION');
            

            if ($result1) {
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

    function receiveReceipt($param,$Location){
        $NO_RECEIPT_DOC   = $param['NO_RECEIPT_DOC'];
        // $DEPARTMENT     = $param['DEPARTMENT'];
        // $REMARKS        = $param['REMARKS'];

        try {
            $this->db->trans_begin();
            $result = FALSE;
            $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME     = $this->session->userdata('username');

            $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC'";
            $get  = $this->db->query($qget)->row();

            $this->db->set('STATUS',2);
            $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
            $this->db->where('POS',2);
            $this->db->update('LOG_TRANSACTION');

            $pos = 3;


            $dept_ke      = $sessDEPARTMENT;
            $dt = [
                    'NO_RECEIPT_DOC' => $get->NO_RECEIPT_DOC,
                    'INVOICE_CODE' => $get->INVOICE_CODE,
                    'NO_PO'      => $get->NO_PO,
                    'VENDOR'     => $get->VENDOR,
                    'AMOUNT'     => $get->AMOUNT,
                    'CREATED_BY' => $USERNAME,
                    'UPDATED_BY' => $USERNAME.' - '.$sessDEPARTMENT,
                    'DEPT'       => $sessDEPARTMENT,
                    'POS'  => $pos,
                    'STATUS' => 0
            ];
                    
            $result1 = $this->db->set($dt)
                        ->set('DATE_RECEIPT','SYSDATE',false)
                        ->insert('LOG_TRANSACTION');
            

            if ($result1) {
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

    function sendReceiptAll($param,$Location){
        // echo "<pre>";
        // var_dump($param);exit();
        try {
            foreach($param['DtElog'] AS $key => $row) {
                // var_dump($row);exit();
                $FLAG = isset($row["FLAG"]);
                
                if($row["FLAG"] != 0){
                    $this->db->trans_begin();

                    $result = FALSE;
                    $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
                    $USERNAME     = $this->session->userdata('username');

                    if($row['DEPARTMENT'] == $sessDEPARTMENT){
                        throw new Exception("Send Failed");
                    }

                    $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '".$row['NO_RECEIPT_DOC']."'";
                    $get  = $this->db->query($qget)->row();

                    $this->db->set('STATUS',1);
                    $this->db->where('NO_RECEIPT_DOC',$row['NO_RECEIPT_DOC']);
                    $this->db->where('POS',$row['POS']);
                    $this->db->update('LOG_TRANSACTION');

                    $pos = 2;

                    $dept_ke      = $sessDEPARTMENT;
                    $dt = [
                            'NO_RECEIPT_DOC' => $get->NO_RECEIPT_DOC,
                            'INVOICE_CODE' => $get->INVOICE_CODE,
                            'NO_PO'      => $get->NO_PO,
                            'VENDOR'     => $get->VENDOR,
                            'AMOUNT'     => $get->AMOUNT,
                            'CREATED_BY' => $USERNAME,
                            'UPDATED_BY' => $USERNAME.' - '.$sessDEPARTMENT,
                            'SEND_TO'    => $row['DEPARTMENT'],
                            'DEPT'       => $sessDEPARTMENT,
                            'REMARK'     => $row['REMARKS'],
                            'POS'  => $pos,
                            'STATUS' => 1
                    ];
                    $dateNow = Date('m/d/Y');
                    // var_dump($dateNow);exit();
                    
                    $cekq = "SELECT * FROM LOG_TRANSACTION WHERE INVOICE_CODE = '".$row['INVOICE_CODE']."' AND POS = '2' AND STATUS = '1' AND SEND_TO = '".$row['DEPARTMENT']."' AND TO_CHAR(DATE_RECEIPT,'mm/dd/yyyy') = '".$dateNow."'";
                    $cekq = $this->db->query($cekq)->result();
                    
                    if (count($cekq) > 0) {
                        throw new Exception('Some Data Already Exists !!!');
                    }else{
                        $result1 = $this->db->set($dt)
                                ->set('DATE_RECEIPT','SYSDATE',false)
                                ->insert('LOG_TRANSACTION');
                    }
                    

                    if ($result1) {
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
                }
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

    function receiveReceiptAll($param){
        // echo "<pre>";
        // var_dump($param);exit();
        // $DEPARTMENT     = $param['DEPARTMENT'];
        // $REMARKS        = $param['REMARKS'];

        try {
             foreach($param['DtElog'] AS $key => $row) {

                if($row["FLAG"] != 0 || $row["FLAG"] != "0"){
                    $this->db->trans_begin();
                    $result = FALSE;
                    // $NO_RECEIPT_DOC   = $param['NO_RECEIPT_DOC'];
                    $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
                    $USERNAME     = $this->session->userdata('username');

                    $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '".$row['NO_RECEIPT_DOC']."'";
                    $get  = $this->db->query($qget)->row();

                    $this->db->set('STATUS',2);
                    $this->db->where('NO_RECEIPT_DOC',$row['NO_RECEIPT_DOC']);
                    $this->db->where('POS',2);
                    $this->db->update('LOG_TRANSACTION');

                    $pos = 3;


                    $dept_ke      = $sessDEPARTMENT;
                    $dt = [
                            'NO_RECEIPT_DOC' => $get->NO_RECEIPT_DOC,
                            'INVOICE_CODE' => $get->INVOICE_CODE,
                            'NO_PO'      => $get->NO_PO,
                            'VENDOR'     => $get->VENDOR,
                            'AMOUNT'     => $get->AMOUNT,
                            'CREATED_BY' => $USERNAME,
                            'UPDATED_BY' => $USERNAME.' - '.$sessDEPARTMENT,
                            'DEPT'       => $sessDEPARTMENT,
                            'POS'  => $pos,
                            'STATUS' => 0
                    ];
                    

                    $dateNow = Date('m/d/Y');
                    
                    $cekq = "SELECT * FROM LOG_TRANSACTION WHERE INVOICE_CODE = '".$row['INVOICE_CODE']."' AND POS = '3' AND STATUS = '0' AND TO_CHAR(DATE_RECEIPT,'mm/dd/yyyy') = '".$dateNow."'";
                    $cekq = $this->db->query($cekq)->result();
                    
                    if (count($cekq) > 0) {
                        throw new Exception('Some Data Already Exists !!!');
                    }else{
                        $result1 = $this->db->set($dt)
                                ->set('DATE_RECEIPT','SYSDATE',false)
                                ->insert('LOG_TRANSACTION');
                    }

                    if ($result1) {
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
                }
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

    function ShowDataLastDoc($param){
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];

        if($COMPANY != "0"){
            $WHERE = " WHERE L.COMPANY = '$COMPANY'";
        }
        $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY INVOICE_CODE ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE; 
        $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
        return $this->db->query($q)->result();
        $this->db->close();
    }

    function HistoryDoc($param){
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];

        if($COMPANY != "0"){
            $WHERE = " WHERE L.COMPANY = '$COMPANY'";
        }
        $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY INVOICE_CODE ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE; 
        $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1";
        return $this->db->query($q)->result();
        $this->db->close();
    }

    function getHistoryDoc($param){
        $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.COMPANY = ? AND LT.INVOICE_CODE = ?) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY DATE_RECEIPT ASC";
        return $this->db->query($q,[$param['COMPANY'], $param['INVOICE_CODE']])->result();
        // var_dump($q);exit();
        $this->db->close();
    }

    function GetData($param){
        $SQL = "SELECT L.*, S.FCNAME AS VENDORNAME, S.ID AS SUPPID FROM LOG_FIRSTRECEIPT L INNER JOIN SUPPLIER S ON S.ID = L.VENDOR
                     INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.UUID = ?";
        $result = $this->db->query($SQL, $param["UUID"])->row();
        // var_dump($this->db->last_query());exit();
        $this->db->close();
        return $result;
    }

    public function UploadFR($param,$location) {
        try
        {
            $this->db->trans_begin();
            $this->db->from("TEMP_UPLOAD_FR");
            $this->db->truncate();
            $result = FALSE;
            $data = [];
            if (!isset($_FILES['uploads'])) {
                throw new Exception('No files uploaded!!');
            } else {
              $file = $_FILES['uploads'];
              $inputFileName = $file['tmp_name'];
              $inputFileType = IOFactory::identify($inputFileName);
              $reader = IOFactory::createReader($inputFileType);
              $spreadsheet = $reader->load($inputFileName);
              $sheet = $spreadsheet->getSheet(0);
              $highestRow = $sheet->getHighestRow();
              $highestColumn = $sheet->getHighestColumn();

              $USERNAME     = $param['USERNAME'];

                for( $row = 2; $row <= $highestRow; $row++ ){
                      $hadError = false;
                      
                      $ERROR_MESSAGE = array();
                      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);

                      $COMPANYCODE  = trim($rowData[0][0]);
                      $INVOICE_CODE = trim($rowData[0][1]);
                      $NO_PO        = trim($rowData[0][2]);
                      $VENDOR       = trim($rowData[0][3]);
                      $CURRENCY     = trim($rowData[0][4]);
                      $AMOUNT       = trim($rowData[0][5]);

                      $isNotZeroAll   = true;
                      if($COMPANYCODE == null && $NO_PO == null && $INVOICE_CODE == null && $VENDOR == null && $CURRENCY == null && $AMOUNT == null ){
                        $isNotZeroAll = false;
                      }
                      if($isNotZeroAll){
                            $this->db->where('COMPANYCODE',"$COMPANYCODE");
                            $getCompany = $this->db->get('COMPANY')->row();

                            if($getCompany == NULL){
                                $hadError        = true;
                                $ERROR_MESSAGE[] = "COMPANYCODE NOT FOUND"; 
                                $compCode = $COMPANYCODE;
                            }else{
                                $compCode   = $getCompany->ID;
                            }

                            $this->db->where('INVOICE_CODE',"$INVOICE_CODE");
                            $getExists = $this->db->get('LOG_FIRSTRECEIPT');

                            if($getExists->num_rows() > 0){
                                $hadError        = true;
                                $ERROR_MESSAGE[] = "No INV $INVOICE_CODE Already Exists"; 
                            }
                            
                            $this->db->where('FCCODE',"$VENDOR");
                            $getVendor = $this->db->get('SUPPLIER')->row();

                            if($getVendor == NULL){
                                $hadError        = true;
                                $ERROR_MESSAGE[] = "VENDOR NOT FOUND"; 
                                $vendorCode = $VENDOR;
                            }else{
                                $vendorCode = $getVendor->ID;
                            }

                                $dataR = array(
                                          'COMPANY'   => $compCode,
                                          'INVOICE_CODE' => $INVOICE_CODE,
                                          'NO_PO' => $NO_PO,
                                          'VENDOR' => $vendorCode,
                                          'AMOUNT' => $AMOUNT,
                                          'CURRENCY' => $CURRENCY,
                                          "UUID" => $this->uuid->v4()
                                      );
                                
                                // var_dump($paidDate);exit();
                                $result = $this->db->set($dataR)->insert("TEMP_UPLOAD_FR");
                                $thisUUID = $dataR['UUID'];
                            
                            if($hadError){
                                $ermsg = implode(',', $ERROR_MESSAGE);
                                $updateErr = array('ERROR_MSG' => $ermsg);
                                //var_dump($updateErr);exit();
                                $this->db->set($updateErr);
                                $this->db->where('UUID',$thisUUID);
                                $this->db->update('TEMP_UPLOAD_FR');
                            }
                       }//end not zero
                }//end for
                $qGet = "SELECT L.*, S.FCNAME AS VENDORNAME, C.COMPANYNAME AS COMPANYNAME FROM TEMP_UPLOAD_FR L LEFT JOIN COMPANY C ON C.ID = L.COMPANY LEFT JOIN SUPPLIER S ON S.ID = L.VENDOR";
                $res   = $this->db->query($qGet)->result();
                // $getTb = $this->db->get("TEMP_UPLOAD_FR")->result();
            }//end else
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => $res
                ];
            }
        } 
        catch (Exception $ex) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
          $this->db->close();
          return $return;
    }

    public function Save_FR($param,$location){
        try {
            $this->db->trans_begin();
            $USERNAME     = $param['USERNAME'];
            date_default_timezone_set("Asia/Jakarta");
            $getDataTemp = $this->db->get("TEMP_UPLOAD_FR")->result();
            $DEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $SQL = "SELECT (maxno + 1) NUMMAX
                              FROM (SELECT NVL(MAX (SUBSTR (NO_RECEIPT_DOC, 5)),0) maxno
                                      FROM LOG_FIRSTRECEIPT
                                     WHERE     TO_CHAR (CREATED_AT,'mm') = TO_CHAR (SYSDATE, 'MM')
                                           AND TO_CHAR (CREATED_AT,'yy') = TO_CHAR (SYSDATE, 'YY')) A";
                    
                    $auto = $this->db->query($SQL)->row()->NUMMAX;
                    // var_dump($auto);exit();

                    $no = $auto;
                if($getDataTemp){
                    foreach($getDataTemp as $r){

                        $TEMPID            = $r->ID;
                        $COMPANY           = $r->COMPANY;
                        $INVOICE_CODE      = $r->INVOICE_CODE;
                        $NO_PO             = $r->NO_PO;
                        $VENDOR            = $r->VENDOR;
                        $CURRENCY          = $r->CURRENCY;
                        $AMOUNT            = $r->AMOUNT;

                        
                        $USERNAME     = $this->session->userdata('username');
                        
                        $dt = [
                            'NO_RECEIPT_DOC' => date('ym').sprintf("%04s", $no),
                            'INVOICE_CODE' => $INVOICE_CODE,
                            'NO_PO' => $NO_PO,
                            'VENDOR' => $VENDOR,
                            'COMPANY' => $COMPANY,
                            'CURRENCY' => $CURRENCY,
                            'AMOUNT' => $AMOUNT,
                            'FIRST_DEPT' => $DEPARTMENT

                        ];
                        $result1 = $this->db->set('CREATED_AT', "SYSDATE", false);

                        $dt['UUID']           = $this->uuid->v4();
                        // $dt['NO_RECEIPT_DOC'] = 'RN-'.$DEPARTMENT.date('ym').sprintf("%04s", $no);
                        $dt['CREATED_BY'] = $USERNAME;
                        $result1 = $result1->set($dt)->insert('LOG_FIRSTRECEIPT');

                        if($result1 == true){
                            $trans = array(
                                'NO_RECEIPT_DOC' => $dt['NO_RECEIPT_DOC'],
                                'INVOICE_CODE'   => $INVOICE_CODE,
                                'NO_PO'          => $NO_PO,
                                'VENDOR' => $VENDOR,
                                'AMOUNT' => $AMOUNT,
                                'CREATED_BY' => $USERNAME,
                                'DEPT' => $DEPARTMENT,
                                'POS'   => 1,
                                'STATUS' => 0
                            );
                        }
                        
                        $insertTrans = $this->db->set('DATE_RECEIPT', "SYSDATE", false);
                        $insertTrans = $insertTrans->set($trans)->insert('LOG_TRANSACTION');

                        //delete temp data after save
                        $this->db->where('ID',$TEMPID);
                        $this->db->delete("TEMP_UPLOAD_FR");
                        $no++;
                    }
                }
                else{
                    throw new Exception('Data Save Failed !!');
                }
            if ($result1 && $insertTrans) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback();
                throw new Exception('Data Save Failed !!');
            }
        } 
        catch (Exception $ex) {
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

/* End of file ElogModel.php */
/* Location: ./application/models/ElogModel.php */