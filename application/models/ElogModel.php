<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ElogModel extends BaseModel {

    public $variable;

    public function __construct()
    {
        parent::__construct();
    }

    // function LogSave($param, $Location) {
    //     try {
    //         $DEPARTMENT   = $this->session->userdata('DEPARTMENT');
    //         $USERNAME     = $this->session->userdata('username');
    //         // var_dump($DEPARTMENT);exit();
    //         $this->db->trans_begin();
    //         $result = FALSE;
    //         $count = 0;
    //         if($param['ACTION'] == 'ADD'){
    //             $no_urut        = $param['no_urut'];
    //             $INVOICE_CODE   = $param['INVOICE_CODE'];
    //             $NO_PO          = $param['NO_PO'];
    //             $VENDOR         = $param['VENDOR'];
    //             $COMPANY        = $param['COMPANY'];
    //             $CURRENCY       = $param['CURRENCY'];
    //             $AMOUNT         = $param['AMOUNT'];
    //             $NOTES          = $param['NOTES'];
                
    //             if ($no_urut){
    //                 $count = count($no_urut);    
    //             }
    //             for($i=0; $i < $count; $i++){
    //                 if ($no_urut[$i] > 0){

    //                     if($DEPARTMENT != "HRD"){
    //                         if($INVOICE_CODE[$i] == NULL || $INVOICE_CODE[$i] == ''){
    //                             throw new Exception("Invoice Number Cant Be Empty");
    //                         }
    //                     }
                        
    //                     $q = $this->db->get_where('LOG_FIRSTRECEIPT',array('INVOICE_CODE' => $INVOICE_CODE[$i]));
    //                     if($q->num_rows() > 0){
    //                         throw new Exception("$INVOICE_CODE[$i] Already Exist", 1);
    //                     }

    //                     $tAMOUNT       = preg_replace("/[^\d\.\-]/","",$AMOUNT[$i]);
    //                     $SQL = "SELECT (maxno + 1) NUMMAX
    //                               FROM (SELECT NVL(MAX (SUBSTR (NO_RECEIPT_DOC, 5)),0) maxno
    //                                       FROM LOG_FIRSTRECEIPT
    //                                      WHERE     TO_CHAR (CREATED_AT,'mm') = TO_CHAR (SYSDATE, 'MM')
    //                                            AND TO_CHAR (CREATED_AT,'yy') = TO_CHAR (SYSDATE, 'YY')) A";
                        
    //                     $auto = $this->db->query($SQL)->row()->NUMMAX;
    //                     // var_dump($auto);exit();

    //                     $no = $auto;

    //                     $dt = [
    //                         'NO_RECEIPT_DOC' => date('ym').sprintf("%04s", $no),
    //                         'INVOICE_CODE' => $INVOICE_CODE[$i],
    //                         'NO_PO' => $NO_PO[$i],
    //                         'VENDOR' => $VENDOR[$i],
    //                         'COMPANY' => $COMPANY[$i],
    //                         'CURRENCY' => $CURRENCY[$i],
    //                         'NOTES' => $NOTES[$i],
    //                         'AMOUNT' => $tAMOUNT,
    //                         'FIRST_DEPT' => $DEPARTMENT

    //                     ];
    //                     $result1 = $this->db->set('CREATED_AT', "SYSDATE", false);

    //                     $dt['UUID']           = $this->uuid->v4();
    //                     // $dt['NO_RECEIPT_DOC'] = 'RN-'.$DEPARTMENT.date('ym').sprintf("%04s", $no);
    //                     $dt['CREATED_BY'] = $USERNAME;
    //                     $result1 = $result1->set($dt)->insert('LOG_FIRSTRECEIPT');

    //                     if($result1 == true){
    //                         $trans = array(
    //                             'NO_RECEIPT_DOC' => $dt['NO_RECEIPT_DOC'],
    //                             'INVOICE_CODE'   => $INVOICE_CODE[$i],
    //                             'NO_PO'          => $NO_PO[$i],
    //                             'VENDOR' => $VENDOR[$i],
    //                             'AMOUNT' => $tAMOUNT,
    //                             'CREATED_BY' => $USERNAME,
    //                             'DEPT' => $DEPARTMENT,
    //                             'POS'   => 1,
    //                             'STATUS' => 0
    //                         );
    //                     }
                            
    //                     $insertTrans = $this->db->set('DATE_RECEIPT', "SYSDATE", false);
    //                     $insertTrans = $insertTrans->set($trans)->insert('LOG_TRANSACTION');

    //                     if ($result1 && $insertTrans) {
    //                         $this->db->trans_commit();
    //                         $return = [
    //                             'STATUS' => TRUE,
    //                             'MESSAGE' => 'Data has been Successfully Saved !!'
    //                         ];
    //                     } else {
    //                         throw new Exception('Data Save Failed !!');
    //                         $this->db->trans_rollback();
    //                         $return = [
    //                             'STATUS' => FALSE,
    //                             'MESSAGE' => 'Data Save Failed !!'
    //                         ];
    //                     }
    //                 }
    //                 $no++;
    //             }
    //         }else{
    //             $UUID           = $param['UUID'];
    //             $COMPANY        = $param['COMPANY'];
    //             $AMOUNT         = $param['AMOUNT'];
    //             $INVOICE_CODE   = $param['INVOICE_CODE'];
    //             $NO_RECEIPT_DOC = $param['NO_RECEIPT_DOC'];
    //             $NO_PO          = $param['NO_PO'];
    //             $VENDOR         = $param['VENDOR'];
    //             $NOTES          = $param['NOTES'];
    //             $CURRENCY       = $param['CURRENCY'];

    //             $q = $this->db->get_where('LOG_FIRSTRECEIPT',array('UUID' => $UUID));
    //             $q = $q->row();
    //             // var_dump($q);exit();

    //             $tAMOUNT = preg_replace("/[^\d\.\-]/","",$AMOUNT);

    //             $dt = array(
    //                 'INVOICE_CODE' => $INVOICE_CODE,
    //                 'NO_PO'  => $NO_PO,
    //                 'AMOUNT' => $tAMOUNT
    //             );

    //             if($VENDOR == ''){
    //                 $dt['VENDOR'] = $q->VENDOR;
    //             }
    //             elseif($q->VENDOR != $VENDOR){
    //                 $dt['VENDOR'] = $VENDOR;
    //             }
    //             if($COMPANY == ''){
    //                 $this->db->set('COMPANY', $q->COMPANY);
    //             }
    //             elseif($q->COMPANY != $COMPANY){
    //                 $this->db->set('COMPANY', $COMPANY);
    //             }
    //             if($CURRENCY == ''){
    //                 $this->db->set('CURRENCY', $q->CURRENCY);
    //             }
    //             elseif($q->CURRENCY != $CURRENCY){
    //                 $this->db->set('CURRENCY', $CURRENCY);
    //             }
    //             $this->db->set('NOTES', $NOTES);
    //             $this->db->where('UUID',$param['UUID']);
    //             $up_fr = $this->db->update('LOG_FIRSTRECEIPT',$dt);

    //             $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
    //             $up_trans = $this->db->update('LOG_TRANSACTION',$dt);

    //             if ($up_fr && $up_trans) {
    //                         $this->db->trans_commit();
    //                         $return = [
    //                             'STATUS' => TRUE,
    //                             'MESSAGE' => 'Data has been Successfully Saved !!'
    //                         ];
    //                     } else {
    //                         throw new Exception('Data Save Failed !!');
    //                         $this->db->trans_rollback();
    //                         $return = [
    //                             'STATUS' => FALSE,
    //                             'MESSAGE' => 'Data Save Failed !!'
    //                         ];
    //                     }
                
    //         }
            
    //     } catch (Exception $ex) {
    //         $this->db->trans_rollback();
    //         $return = [
    //             'STATUS' => FALSE,
    //             'MESSAGE' => $ex->getMessage()
    //         ];
    //     }
    //     $this->db->close();
    //     return $return;
    // }

    function LogSave($param, $Location) {

        try {
            $DEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME     = $this->session->userdata('username');
            // var_dump($DEPARTMENT);exit();
            $this->db->trans_begin();
            $result = FALSE;
            $count = 0;
            if($param['ACTION'] == 'ADD'){
                foreach ($param['DtElog'] as $key => $value) {
                    $no_urut        = $value['no_urut'];
                    $INVOICE_CODE   = $value['INVOICE_CODE'];
                    $NO_PO          = $value['NO_PO'];
                    $VENDOR         = $value['VENDOR'];
                    $COMPANY        = $value['COMPANY'];
                    $CURRENCY       = $value['CURRENCY'];
                    $AMOUNT         = $value['AMOUNT'];
                    $NOTES          = $value['NOTES'];
                    
                    if($COMPANY == "Select" || $COMPANY == null || $COMPANY == ''){
                        throw new Exception("Select Company");
                        
                    }

                    if($DEPARTMENT != "HRD"){
                        if($INVOICE_CODE == NULL || $INVOICE_CODE == ''){
                            throw new Exception("Invoice Number Cant Be Empty");
                        }
                    }
                    
                    $q = $this->db->get_where('LOG_FIRSTRECEIPT',array('INVOICE_CODE' => $INVOICE_CODE));
                    if($q->num_rows() > 0){
                        throw new Exception("$INVOICE_CODE Already Exist", 1);
                    }

                    // $q = $this->db->get_where('LOG_FIRSTRECEIPT',array('NO_PO' => $NO_PO));
                    // if($q->num_rows() > 0){
                    //     throw new Exception("$NO_PO Already Exist", 1);
                    // }

                    $tAMOUNT       = preg_replace("/[^\d\.\-]/","",$AMOUNT);
                    // $SQL = "SELECT (maxno + 1) NUMMAX
                    //           FROM (SELECT NVL(MAX (SUBSTR (NO_RECEIPT_DOC, 5)),0) maxno
                    //                   FROM LOG_FIRSTRECEIPT
                    //                  WHERE     TO_CHAR (CREATED_AT,'mm') = TO_CHAR (SYSDATE, 'MM')
                    //                        AND TO_CHAR (CREATED_AT,'yy') = TO_CHAR (SYSDATE, 'YY')) A";
                    
                    // $auto = $this->db->query($SQL)->row()->NUMMAX;
                    // // var_dump($auto);exit();

                    // $no = $auto;

                    $dt = [
                        'NO_RECEIPT_DOC' => $this->uuid->v4(),
                        'INVOICE_CODE' => $INVOICE_CODE,
                        'NO_PO' => $NO_PO,
                        'VENDOR' => $VENDOR,
                        'COMPANY' => $COMPANY,
                        'CURRENCY' => $CURRENCY,
                        'NOTES' => $NOTES,
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
                            'INVOICE_CODE'   => $INVOICE_CODE,
                            'NO_PO'          => $NO_PO,
                            'VENDOR' => $VENDOR,
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
                    // $no++;
                }
            }else{
                $UUID           = $param['UUID'];
                $COMPANY        = $param['COMPANY'];
                $AMOUNT         = $param['AMOUNT'];
                $INVOICE_CODE   = $param['INVOICE_CODE'];
                $NO_RECEIPT_DOC = $param['NO_RECEIPT_DOC'];
                $NO_PO          = $param['NO_PO'];
                $VENDOR         = $param['VENDOR'];
                $NOTES          = $param['NOTES'];
                $CURRENCY       = $param['CURRENCY'];

                $q = $this->db->get_where('LOG_FIRSTRECEIPT',array('UUID' => $UUID));
                $q = $q->row();
                // var_dump($q);exit();

                $tAMOUNT = preg_replace("/[^\d\.\-]/","",$AMOUNT);

                $qCekPosisi = "SELECT * FROM LOG_TRANSACTION where ID = (select max(ID) from LOG_TRANSACTION where NO_RECEIPT_DOC = '$NO_RECEIPT_DOC')";
                $cekPosisi  = $this->db->query($qCekPosisi)->row();

                if($cekPosisi->POS == 2 && $cekPosisi->STATUS == 1){
                    if($cekPosisi->SEND_TO != $q->FIRST_DEPT){
                        throw new Exception("Data hanya bisa diupdate oleh department ".$q->FIRST_DEPT." dan posisi harus dikembalikan ke department ".$q->FIRST_DEPT." ");
                    }
                    else{
                        if($INVOICE_CODE != $q->INVOICE_CODE){
                            $q1 = $this->db->get_where('LOG_FIRSTRECEIPT',array('INVOICE_CODE' => $INVOICE_CODE));
                            if($q1->num_rows() > 0){
                                throw new Exception("$INVOICE_CODE Already Exist");
                            }else{
                                $INVOICE_CODE = $INVOICE_CODE;
                            }
                        }

                       // if($NO_PO != $q->NO_PO){
                       //      $q1 = $this->db->get_where('LOG_FIRSTRECEIPT',array('NO_PO' => $NO_PO));
                       //      if($q1->num_rows() > 0){
                       //          throw new Exception("$NO_PO Already Exist", 1);
                       //      }else{
                                $NO_PO = $NO_PO;
                       //      }
                       // }

                        $dt = array(
                            'INVOICE_CODE' => $INVOICE_CODE,
                            'NO_PO'  => $NO_PO,
                            'AMOUNT' => $tAMOUNT
                        );

                        if($VENDOR == ''){
                            $dt['VENDOR'] = $q->VENDOR;
                        }
                        elseif($q->VENDOR != $VENDOR){
                            $dt['VENDOR'] = $VENDOR;
                        }
                        if($COMPANY == ''){
                            $this->db->set('COMPANY', $q->COMPANY);
                        }
                        elseif($q->COMPANY != $COMPANY){
                            $this->db->set('COMPANY', $COMPANY);
                        }
                        if($CURRENCY == ''){
                            $this->db->set('CURRENCY', $q->CURRENCY);
                        }
                        elseif($q->CURRENCY != $CURRENCY){
                            $this->db->set('CURRENCY', $CURRENCY);
                        }
                        $this->db->set('NOTES', $NOTES);
                        $this->db->where('UUID',$param['UUID']);
                        $up_fr = $this->db->update('LOG_FIRSTRECEIPT',$dt);

                        $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
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
                }
                if($cekPosisi->POS == 3 && $cekPosisi->STATUS == 0){
                    if($cekPosisi->DEPT != $q->FIRST_DEPT){
                            throw new Exception("Data hanya bisa diupdate oleh department ".$q->FIRST_DEPT." dan posisi harus dikembalikan ke department ".$q->FIRST_DEPT." ");
                    }
                    else{
                        if($INVOICE_CODE != $q->INVOICE_CODE){
                            $q1 = $this->db->get_where('LOG_FIRSTRECEIPT',array('INVOICE_CODE' => $INVOICE_CODE));
                            if($q1->num_rows() > 0){
                                throw new Exception("$INVOICE_CODE Already Exist");
                            }else{
                                $INVOICE_CODE = $INVOICE_CODE;
                            }
                        }

                       // if($NO_PO != $q->NO_PO){
                       //      $q1 = $this->db->get_where('LOG_FIRSTRECEIPT',array('NO_PO' => $NO_PO));
                       //      if($q1->num_rows() > 0){
                       //          throw new Exception("$NO_PO Already Exist", 1);
                       //      }else{
                                $NO_PO = $NO_PO;
                       //      }
                       // }

                        $dt = array(
                            'INVOICE_CODE' => $INVOICE_CODE,
                            'NO_PO'  => $NO_PO,
                            'AMOUNT' => $tAMOUNT
                        );

                        if($VENDOR == ''){
                            $dt['VENDOR'] = $q->VENDOR;
                        }
                        elseif($q->VENDOR != $VENDOR){
                            $dt['VENDOR'] = $VENDOR;
                        }
                        if($COMPANY == ''){
                            $this->db->set('COMPANY', $q->COMPANY);
                        }
                        elseif($q->COMPANY != $COMPANY){
                            $this->db->set('COMPANY', $COMPANY);
                        }
                        if($CURRENCY == ''){
                            $this->db->set('CURRENCY', $q->CURRENCY);
                        }
                        elseif($q->CURRENCY != $CURRENCY){
                            $this->db->set('CURRENCY', $CURRENCY);
                        }
                        $this->db->set('NOTES', $NOTES);
                        $this->db->where('UUID',$param['UUID']);
                        $up_fr = $this->db->update('LOG_FIRSTRECEIPT',$dt);

                        $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
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
                }
                else{
                        if($INVOICE_CODE != $q->INVOICE_CODE){
                            $q1 = $this->db->get_where('LOG_FIRSTRECEIPT',array('INVOICE_CODE' => $INVOICE_CODE));
                            if($q1->num_rows() > 0){
                                throw new Exception("$INVOICE_CODE Already Exist");
                            }else{
                                $INVOICE_CODE = $INVOICE_CODE;
                            }
                        }

                       // if($NO_PO != $q->NO_PO){
                       //      $q1 = $this->db->get_where('LOG_FIRSTRECEIPT',array('NO_PO' => $NO_PO));
                       //      if($q1->num_rows() > 0){
                       //          throw new Exception("$NO_PO Already Exist", 1);
                       //      }else{
                                $NO_PO = $NO_PO;
                       //      }
                       // }

                        $dt = array(
                            'INVOICE_CODE' => $INVOICE_CODE,
                            'NO_PO'  => $NO_PO,
                            'AMOUNT' => $tAMOUNT
                        );

                        if($VENDOR == ''){
                            $dt['VENDOR'] = $q->VENDOR;
                        }
                        elseif($q->VENDOR != $VENDOR){
                            $dt['VENDOR'] = $VENDOR;
                        }
                        if($COMPANY == ''){
                            $this->db->set('COMPANY', $q->COMPANY);
                        }
                        elseif($q->COMPANY != $COMPANY){
                            $this->db->set('COMPANY', $COMPANY);
                        }
                        if($CURRENCY == ''){
                            $this->db->set('CURRENCY', $q->CURRENCY);
                        }
                        elseif($q->CURRENCY != $CURRENCY){
                            $this->db->set('CURRENCY', $CURRENCY);
                        }
                        $this->db->set('NOTES', $NOTES);
                        $this->db->where('UUID',$param['UUID']);
                        $up_fr = $this->db->update('LOG_FIRSTRECEIPT',$dt);

                        $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
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
            $where = array("L.FIRST_DEPT" => $DEPARTMENT, "to_char(CREATED_AT,'mm-yyyy')" => $fDate);
        }else{
            $where = array("to_char(CREATED_AT,'mm-yyyy')" => $fDate);
        }
        $result = $this->db->select('L.*,S.FCNAME AS VENDORNAME, C.COMPANYNAME')
                        ->from("LOG_FIRSTRECEIPT L")
                        ->join("COMPANY C", 'C.ID = L.COMPANY', 'inner')
                        ->join("SUPPLIER S", 'S.ID = L.VENDOR', 'inner')
                        ->where($where)
                        // ->where("to_char(CREATED_AT,'mm-dd-yyyy')",$fDate)
                        ->order_by('L.ID ASC')->get()->result();
                        // var_dump($this->db->last_query());exit();
        $this->db->close();
        
        return $result;
    }

    function ShowSendData($param) {
        $DEPT         = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $COMPANY      = $param['COMPANY'];
        $SUBGROUP     = $param['SUBGROUP'];
        $VENDOR       = $param['VENDOR'];
        // if($DEPT == 'HRD'){
            $sDATE        = $param['sDATE'];
            $fDATE        = $param['fDATE'];
            if($sDATE == null || $sDATE == ''){
                $WHERE = " AND TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '".Date('Y')."'";
            }
            if($fDATE != '' || $sDATE != ''){
                $WHERE = " AND LT.DATE_RECEIPT BETWEEN TO_DATE ('$fDATE', 'mm/dd/yyyy') AND TO_DATE ('$sDATE', 'mm/dd/yyyy') ";
            }
            if($VENDOR != "" || $VENDOR != null){
                $WHERE .= " AND S.ID = '$VENDOR'";
            }
        // }

        $Lenght     = $param["length"];
        $Start      = $param["start"];
        $Columns    = $param["columns"];
        $Search     = $param["search"];
        $Order      = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        if($COMPANY != "0"){
            $WHERE .= " AND L.COMPANY = '$COMPANY'";
        }
        if($SUBGROUP != "0"){
            $WHERE .= " AND C.COMPANY_SUBGROUP = '$SUBGROUP'";
        }

        if($DEPT == 'IT'){
            $q2 = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT,LAST_REMARK,SENDER, FIRST_DEPT,VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, LAST_REMARK,SENDER,FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID,LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.SEND_TO, LT.DATE_RECEIPT, LS.LAST_REMARK, LS.SENDER, L.FIRST_DEPT, LT.POS,LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN (SELECT *
                            FROM (  SELECT NO_RECEIPT_DOC,
                                            NO_PO,
                                           REMARK AS LAST_REMARK,
                                           DATE_RECEIPT,
                                           MAX (DATE_RECEIPT)
                                              OVER (PARTITION BY NO_RECEIPT_DOC)
                                              max_date,
                                              UPDATED_BY as SENDER
                                      FROM LOG_TRANSACTION
                                     WHERE POS IN (2)
                                  GROUP BY NO_RECEIPT_DOC,NO_PO, REMARK, DATE_RECEIPT, UPDATED_BY)
                           WHERE DATE_RECEIPT = MAX_DATE) LS ON LS.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC
                                  WHERE LT.POS IN(1,3) AND LT.STATUS = '0' AND LT.POS <> '4' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO,DATE_RECEIPT, LAST_REMARK,SENDER, FIRST_DEPT, VENDORNAME,CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1)";
        }
        else{
            $q2 = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO,TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, LAST_REMARK,SENDER, FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS,STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, LAST_REMARK,SENDER, FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.SEND_TO, LT.DATE_RECEIPT, LS.LAST_REMARK, LS.SENDER, L.FIRST_DEPT, LT.POS,LT.STATUS, S.FCNAME AS VENDORNAME,L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN (SELECT *
                            FROM (  SELECT NO_RECEIPT_DOC,
                                            NO_PO,
                                           REMARK AS LAST_REMARK,
                                           DATE_RECEIPT,
                                           MAX (DATE_RECEIPT)
                                              OVER (PARTITION BY NO_RECEIPT_DOC)
                                              max_date,
                                              UPDATED_BY as SENDER
                                      FROM LOG_TRANSACTION
                                     WHERE POS IN (2) AND SEND_TO = '$DEPT'
                                  GROUP BY NO_RECEIPT_DOC,NO_PO, REMARK, DATE_RECEIPT,UPDATED_BY)
                           WHERE DATE_RECEIPT = MAX_DATE) LS ON LS.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC WHERE LT.POS IN(1,3) AND LT.SEND_TO = '$DEPT' OR LT.DEPT = '$DEPT' AND LT.STATUS = '0' AND LT.POS <> '4' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, LAST_REMARK, SENDER, FIRST_DEPT, VENDORNAME,CURRENCY, COMPANYNAME, POS,STATUS ORDER BY ID DESC) WHERE RANKI = 1)";
        }
        
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            // $SQLO = " ORDER BY DATE_RECEIPT";
            $SQLO = "";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $q2 FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q2 FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q2 FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    function ShowSendData1($param) {
        $DEPT         = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];
        $SUBGROUP     = $param['SUBGROUP'];

        if($COMPANY != "0"){
            $WHERE = " AND L.COMPANY = '$COMPANY'";
        }
        if($SUBGROUP != "0"){
            $WHERE .= " AND C.COMPANY_SUBGROUP = '$SUBGROUP'";
        }
        if($DEPT == 'IT'){
            $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT,LAST_REMARK,SENDER, FIRST_DEPT,VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, LAST_REMARK,SENDER,FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID,LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LS.LAST_REMARK, LS.SENDER, L.FIRST_DEPT, LT.POS,LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN (SELECT *
                            FROM (  SELECT NO_RECEIPT_DOC,
                                            NO_PO,
                                           REMARK AS LAST_REMARK,
                                           DATE_RECEIPT,
                                           MAX (DATE_RECEIPT)
                                              OVER (PARTITION BY NO_RECEIPT_DOC)
                                              max_date,
                                              UPDATED_BY as SENDER
                                      FROM LOG_TRANSACTION
                                     WHERE POS IN (2)
                                  GROUP BY NO_RECEIPT_DOC,NO_PO, REMARK, DATE_RECEIPT, UPDATED_BY)
                           WHERE DATE_RECEIPT = MAX_DATE) LS ON LS.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC
                                  WHERE LT.POS IN(1,3) AND LT.STATUS = '0' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO,DATE_RECEIPT, LAST_REMARK,SENDER, FIRST_DEPT, VENDORNAME,CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
        }
        else{
            $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO,TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, LAST_REMARK,SENDER, FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS,STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, LAST_REMARK,SENDER, FIRST_DEPT, VENDORNAME, CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LS.LAST_REMARK, LS.SENDER, L.FIRST_DEPT, LT.POS,LT.STATUS, S.FCNAME AS VENDORNAME,L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN (SELECT *
                            FROM (  SELECT NO_RECEIPT_DOC,
                                            NO_PO,
                                           REMARK AS LAST_REMARK,
                                           DATE_RECEIPT,
                                           MAX (DATE_RECEIPT)
                                              OVER (PARTITION BY NO_RECEIPT_DOC)
                                              max_date,
                                              UPDATED_BY as SENDER
                                      FROM LOG_TRANSACTION
                                     WHERE POS IN (2) AND SEND_TO = '$DEPT'
                                  GROUP BY NO_RECEIPT_DOC,NO_PO, REMARK, DATE_RECEIPT,UPDATED_BY)
                           WHERE DATE_RECEIPT = MAX_DATE) LS ON LS.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC WHERE LT.POS IN(1,3) AND LT.SEND_TO = '$DEPT' OR LT.DEPT = '$DEPT' AND LT.STATUS = '0' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, LAST_REMARK, SENDER, FIRST_DEPT, VENDORNAME,CURRENCY, COMPANYNAME, POS,STATUS ORDER BY ID DESC) WHERE RANKI = 1";
        }
        $result = $this->db->query($q2)->result();
        $this->db->close();
        // var_dump($this->db->last_query());exit();
        return $result;
    }

    function getVendorSend($q){
        $DEPT = $this->session->userdata('DEPARTMENT');
        if($DEPT == 'IT'){
            $q2 = "SELECT DISTINCT VENDORID,VENDORNAME FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, LAST_REMARK,SENDER,FIRST_DEPT, VENDORNAME,VENDORID, CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID,LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.SEND_TO, LT.DATE_RECEIPT, LS.LAST_REMARK, LS.SENDER, L.FIRST_DEPT, LT.POS,LT.STATUS, S.FCNAME AS VENDORNAME,S.ID AS VENDORID, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN (SELECT *
                            FROM (  SELECT NO_RECEIPT_DOC,
                                            NO_PO,
                                           REMARK AS LAST_REMARK,
                                           DATE_RECEIPT,
                                           MAX (DATE_RECEIPT)
                                              OVER (PARTITION BY NO_RECEIPT_DOC)
                                              max_date,
                                              UPDATED_BY as SENDER
                                      FROM LOG_TRANSACTION
                                     WHERE POS IN (2)
                                  GROUP BY NO_RECEIPT_DOC,NO_PO, REMARK, DATE_RECEIPT, UPDATED_BY)
                           WHERE DATE_RECEIPT = MAX_DATE) LS ON LS.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC
                                  WHERE LT.POS IN(1,3) AND LT.STATUS = '0' AND LT.POS <> '4' ";
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO,DATE_RECEIPT, LAST_REMARK,SENDER, FIRST_DEPT, VENDORNAME,VENDORID,CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1 AND VENDORNAME LIKE '%".$q."%'";
        }
        else{
            $q2 = "SELECT DISTINCT VENDORID,VENDORNAME FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, LAST_REMARK,SENDER, FIRST_DEPT, VENDORNAME,VENDORID, CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.SEND_TO, LT.DATE_RECEIPT, LS.LAST_REMARK, LS.SENDER, L.FIRST_DEPT, LT.POS,LT.STATUS, S.FCNAME AS VENDORNAME,S.ID AS VENDORID,L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN (SELECT *
                            FROM (  SELECT NO_RECEIPT_DOC,
                                            NO_PO,
                                           REMARK AS LAST_REMARK,
                                           DATE_RECEIPT,
                                           MAX (DATE_RECEIPT)
                                              OVER (PARTITION BY NO_RECEIPT_DOC)
                                              max_date,
                                              UPDATED_BY as SENDER
                                      FROM LOG_TRANSACTION
                                     WHERE POS IN (2) AND SEND_TO = '$DEPT'
                                  GROUP BY NO_RECEIPT_DOC,NO_PO, REMARK, DATE_RECEIPT,UPDATED_BY)
                           WHERE DATE_RECEIPT = MAX_DATE) LS ON LS.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC WHERE LT.POS IN(1,3) AND LT.SEND_TO = '$DEPT' OR LT.DEPT = '$DEPT' AND LT.STATUS = '0' AND LT.POS <> '4' ";
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, LAST_REMARK, SENDER, FIRST_DEPT, VENDORNAME,VENDORID,CURRENCY, COMPANYNAME, POS,STATUS ORDER BY ID DESC) WHERE RANKI = 1 AND VENDORNAME LIKE '%".$q."%'";
        }

        $res = $this->db->query($q2);
        // var_dump($this->db->last_query());exit;
        return $res->result(); 
    }

    function getVendorNew($q){
        $DEPT = $this->session->userdata('DEPARTMENT');
        if($DEPT == 'IT'){
            $q2 = "SELECT DISTINCT VENDORID,VENDORNAME FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH, AMOUNT_NET, SEND_TO, DATE_RECEIPT, VENDORNAME,VENDORID, CURRENCY,FIRST_DEPT, COMPANYCODE, MAX (POS) AS POS, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME,S.ID AS VENDORID, L.CURRENCY, L.FIRST_DEPT, C.COMPANYCODE FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE LT.POS = '2' AND LT.STATUS = '1' ";
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, VENDORNAME,VENDORID, CURRENCY,FIRST_DEPT, COMPANYCODE, POS,STATUS ORDER BY ID DESC) WHERE RANKI = 1 AND VENDORNAME LIKE '%".$q."%' ";
        }
        else{
            $q2 = "SELECT DISTINCT VENDORID, VENDORNAME FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, VENDORNAME,VENDORID,CURRENCY,FIRST_DEPT, COMPANYCODE, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME,S.ID AS VENDORID, L.CURRENCY, L.FIRST_DEPT, C.COMPANYCODE FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE LT.SEND_TO = '$DEPT' AND LT.POS = '2' AND LT.STATUS = '1'";
            $q2 .= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, VENDORNAME,VENDORID, CURRENCY,FIRST_DEPT, COMPANYCODE, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1 AND VENDORNAME LIKE '%".$q."%'";
        }

        $res = $this->db->query($q2);
        // var_dump($this->db->last_query());
        return $res->result(); 
    }

    function ShowReceiveData($param) {
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];
        $SUBGROUP     = $param['SUBGROUP'];
        $FROMDATE     = $param['FROMDATE'];
        $VENDOR       = $param['VENDOR'];

        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[8]["column"]]["data"];

        if($COMPANY != "0"){
            $WHERE = " AND L.COMPANY = '$COMPANY'";
        }
        if($SUBGROUP != "0"){
            $WHERE .= " AND C.COMPANY_SUBGROUP = '$SUBGROUP'";
        }
        if($FROMDATE != "" || $FROMDATE != null){
            $WHERE .= " AND TO_CHAR(LT.DATE_RECEIPT,'mm/dd/yyyy') = '$FROMDATE'";
        }
        if($VENDOR != "" || $VENDOR != null){
            $WHERE .= " AND S.ID = '$VENDOR'";
        }

        if($DEPT == 'IT'){
            $q2 = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH, AMOUNT_NET, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYCODE, POS, STATUS,FILENAME FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH, AMOUNT_NET, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYCODE, MAX (POS) AS POS, STATUS,FILENAME, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, L.FIRST_DEPT, C.COMPANYCODE, LF.FILENAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN LOG_FILES LF ON LF.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC WHERE LT.POS = '2' AND LT.STATUS = '1' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYCODE, POS,STATUS,FILENAME ORDER BY ID DESC) WHERE RANKI = 1)";
        }
        else{
            $q2 = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH, AMOUNT_NET, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYCODE, POS, STATUS,FILENAME FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, VENDORNAME,CURRENCY,FIRST_DEPT, COMPANYCODE, MAX (POS) AS POS,STATUS,FILENAME, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, L.FIRST_DEPT, C.COMPANYCODE,LF.FILENAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR LEFT JOIN LOG_FILES LF ON LF.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC WHERE LT.SEND_TO = '$DEPT' AND LT.POS = '2' AND LT.STATUS = '1' ".$WHERE;
            $q2 .= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYCODE, POS, STATUS,FILENAME ORDER BY ID DESC) WHERE RANKI = 1)";
        }
        
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            $SQLO = " ORDER BY ID DESC";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[8]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $q2 FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q2 FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q2 FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    function ShowReceiveData1($param) {
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];
        $SUBGROUP     = $param['SUBGROUP'];

        if($COMPANY != "0"){
            $WHERE = " AND L.COMPANY = '$COMPANY'";
        }
        if($SUBGROUP != "0"){
            $WHERE .= " AND C.COMPANY_SUBGROUP = '$SUBGROUP'";
        }

        if($DEPT == 'IT'){
            $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, MAX (POS) AS POS, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE LT.POS = '2' AND LT.STATUS = '1' ". $WHERE;
            $q2.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, POS,STATUS ORDER BY ID DESC) WHERE RANKI = 1";
        }
        else{
            $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME,CURRENCY, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE LT.SEND_TO = '$DEPT' AND LT.POS = '2' AND LT.STATUS = '1' ".$WHERE;
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
        $NO_POS         = $param['NO_POS'];
        $INVOICE_CODES  = $param['INVOICE_CODES'];
        
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME     = $this->session->userdata('username');


            if($DEPARTMENT == $sessDEPARTMENT){
                if($sessDEPARTMENT == 'FINANCE' || "IT"){

                }else{
                    throw new Exception("error same department");    
                }
                
            }

            if($USERNAME == null){
                throw new Exception("Session Username is null, please re-login");
            }

            if($sessDEPARTMENT == null){
                throw new Exception("Session Department is null, please re-login");
            }

            // if($INVOICE_CODES == 'null'){
            //     $AND = '';
            // }
            // else{
            //      $AND = "AND INVOICE_CODE = '$INVOICE_CODES'";
            // }
            $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC'";
            $get  = $this->db->query($qget)->row();

            $qget2 = "SELECT * FROM LOG_TRANSACTION WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC' ORDER BY ID DESC OFFSET 0 ROWS
            FETCH NEXT 1 ROWS ONLY";
            $get2  = $this->db->query($qget2)->row();

            // var_dump($this->db->last_query());exit;

            $this->db->set('STATUS',1);
            $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
            // $this->db->where('INVOICE_CODE',$INVOICE_CODES);
            // $this->db->where('NO_PO',$NO_POS);
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
                    'VOUCHERNO'  => $get2->VOUCHERNO,
                    'DPP' => $get2->DPP,
                    'AMOUNT_PPN' => $get2->AMOUNT_PPN,
                    'AMOUNT_PPH' => $get2->AMOUNT_PPH,
                    'AMOUNT_NET' => $get2->AMOUNT_NET,
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

    function otherSend($param,$Location){
        
        $NO_RECEIPT_DOC = $param['NO_RECEIPT_DOC'];
        $DEPARTMENT     = $param['DEPARTMENT'];
        $REMARKS        = $param['REMARKS'];
        $POS            = $param['POS'];
        $VOUCHERNO      = $param['VOUCHERNO'];
        $NET            = intval(preg_replace("/[^\d\.\-]/","",$param['NET']));
        $PPH            = intval(preg_replace("/[^\d\.\-]/","",$param['PPH']));
        $PPN            = intval(preg_replace("/[^\d\.\-]/","",$param['PPN']));
        $DPP            = intval(preg_replace("/[^\d\.\-]/","",$param['DPP']));

        if($DEPARTMENT === null || $DEPARTMENT === '' || $DEPARTMENT === 'Choose'){
            throw new Exception("Choose Department");
            exit;
        }
        
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME     = $this->session->userdata('username');


            if($DEPARTMENT == $sessDEPARTMENT){
                if($sessDEPARTMENT == 'FINANCE' || 'IT'){

                }else{
                    throw new Exception("error same department");    
                }
                
            }

            if($USERNAME == null){
                throw new Exception("Session Username is null, please re-login");
            }

            if($sessDEPARTMENT == null){
                throw new Exception("Session Department is null, please re-login");
            }

            // if($INVOICE_CODES == 'null'){
            //     $AND = '';
            // }
            // else{
            //      $AND = "AND INVOICE_CODE = '$INVOICE_CODES'";
            // }
            $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC'";
            $get  = $this->db->query($qget)->row();

            // var_dump($this->db->last_query());exit;

            $this->db->set('STATUS',1);
            $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
            // $this->db->where('INVOICE_CODE',$INVOICE_CODES);
            // $this->db->where('NO_PO',$NO_POS);
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
                    'VOUCHERNO'  => $VOUCHERNO,
                    'DPP' => $DPP,
                    'AMOUNT_PPN' => $PPN,
                    'AMOUNT_PPH' => $PPH,
                    'AMOUNT_NET' => $NET,
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
        $NO_PO         = $param['NO_PO'];
        $INVOICE_CODE  = $param['INVOICE_CODE'];
        // $DEPARTMENT     = $param['DEPARTMENT'];
        $REMARK        = $param['REMARK'];

        try {
            $this->db->trans_begin();
            $result = FALSE;
            $sessDEPARTMENT   = $this->session->userdata('DEPARTMENT');
            $USERNAME     = $this->session->userdata('username');

            if($USERNAME == null){
                throw new Exception("Session Username is null, please re-login");
            }

            if($sessDEPARTMENT == null){
                throw new Exception("Session Department is null, please re-login");
            }

            // if($INVOICE_CODE == 'null'){
            //     $AND = '';
            // }
            // else{
            //      $AND = "AND INVOICE_CODE = '$INVOICE_CODE'";
            // }
            $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC'";
            $get  = $this->db->query($qget)->row();

            $qget2 = "SELECT * FROM LOG_TRANSACTION WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC' ORDER BY ID DESC OFFSET 0 ROWS
            FETCH NEXT 1 ROWS ONLY";
            $get2  = $this->db->query($qget2)->row();

            // var_dump($this->db->last_query());exit;

            $this->db->set('STATUS',2);
            $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
            // $this->db->where('INVOICE_CODE',$INVOICE_CODE);
            // $this->db->where('NO_PO',$NO_PO);
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
                    'VOUCHERNO'  => $get2->VOUCHERNO,
                    'DPP' => $get2->DPP,
                    'AMOUNT_PPN' => $get2->AMOUNT_PPN,
                    'AMOUNT_PPH' => $get2->AMOUNT_PPH,
                    'AMOUNT_NET' => $get2->AMOUNT_NET,
                    'STATUS' => 0
            ];
                    
            $cekq = "SELECT * FROM LOG_TRANSACTION WHERE NO_RECEIPT_DOC = '".$param['NO_RECEIPT_DOC']."' AND POS = '3' AND STATUS = '0'";
                    $cekq = $this->db->query($cekq)->result();
                    
            if (count($cekq) > 0) {
                throw new Exception('Some Data Already Exists !!!');
            }else{
                if($REMARK != null || $REMARK != ''){
                    $dt['REMARK'] = $REMARK;
                }
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

    function receiveReceiptClosing($param,$Location){
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

            $pos = 4;


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

                    if($USERNAME == null){
                        throw new Exception("Session Username is null, please re-login");
                    }

                    if($sessDEPARTMENT == null){
                        throw new Exception("Session Department is null, please re-login");
                    }

                    if($row['DEPARTMENT'] == $sessDEPARTMENT){
                        throw new Exception("Send Failed");
                    }

                    $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '".$row['NO_RECEIPT_DOC']."'";
                    $get  = $this->db->query($qget)->row();

                    $this->db->set('STATUS',1);
                    $this->db->where('NO_RECEIPT_DOC',$row['NO_RECEIPT_DOC']);
                    $this->db->where('POS',$row['POS']);
                    // $this->db->where('INVOICE_CODE',$row['INVOICE_CODE']);
                    $this->db->where('NO_PO',$row['NO_PO']);
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

                    if($USERNAME == null){
                        throw new Exception("Session Username is null, please re-login");
                    }

                    if($sessDEPARTMENT == null){
                        throw new Exception("Session Department is null, please re-login");
                    }

                    $qget = "SELECT * FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '".$row['NO_RECEIPT_DOC']."' AND NO_PO = '".$row['NO_PO']."'";
                    $get  = $this->db->query($qget)->row();

                    $this->db->set('STATUS',2);
                    $this->db->where('NO_RECEIPT_DOC',$row['NO_RECEIPT_DOC']);
                    // $this->db->where('INVOICE_CODE',$row['INVOICE_CODE']);
                    $this->db->where('NO_PO',$row['NO_PO']);
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
                    
                    $cekq = "SELECT * FROM LOG_TRANSACTION WHERE NO_RECEIPT_DOC = '".$row['NO_RECEIPT_DOC']."' AND INVOICE_CODE = '".$row['INVOICE_CODE']."' AND POS = '3' AND STATUS = '0'";
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
        $WHERE2 = "";
        $COMPANY      = $param['COMPANY'];
                
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        if($COMPANY != "0"){
            $WHERE = " AND L.COMPANY = '$COMPANY'";
            $WHERE2 = " WHERE L.COMPANY = '$COMPANY'";
        }
        // if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
            $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_DATE(DATE_RECEIPT) AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE2; 
            $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY DATE_RECEIPT DESC) WHERE RANKI = 1)";
        // }else{
        //     $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT' ".$WHERE; 
        //     $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1)";
        // }
        
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            $SQLO = " ORDER BY TO_DATE(DATE_RECEIPT) DESC";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $q FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

     function ShowDataLastDocNew($param){
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $WHERE2 = "";
        $COMPANY      = $param['COMPANY'];
        $YEAR         = $param['YEAR'];
                
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];


        if(count($COMPANY) > 1){
            foreach($COMPANY as $comp){
                
                $WHERE  .= " OR L.COMPANY = '$comp'";
                $WHERE2 .= " OR L.COMPANY = '$comp'";
            }
            $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_DATE(DATE_RECEIPT) AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' AND L.COMPANY = '$comp' ".$WHERE2; 
            $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1)";
        }
        else{
            if($COMPANY[0] != "0"){
                $WHERE = " AND L.COMPANY = '$COMPANY[0]'";
                $WHERE2 = " AND L.COMPANY = '$COMPANY[0]'";
            }

            $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_DATE(DATE_RECEIPT) AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' ".$WHERE2; 
            $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1)";
        }
        

        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            $SQLO = " ORDER BY ID DESC";
        } else {
            $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
        }
        $result = $this->db->query("SELECT * FROM $q FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }
    
    // function ShowDataLastDoc($param){
    //     $DEPT = $this->session->userdata('DEPARTMENT');
    //     $USERNAME     = $this->session->userdata('username');

    //     $WHERE = "";
    //     $WHERE2 = "";
    //     $COMPANY      = $param['COMPANY'];
                
    //     $Lenght = $param["length"];
    //     $Start = $param["start"];
    //     $Columns = $param["columns"];
    //     $Search = $param["search"];
    //     $Order = $param["order"];
    //     $OrderField = $Columns[$Order[0]["column"]]["data"];

    //     if($COMPANY != "0"){
    //         $WHERE = " AND L.COMPANY = '$COMPANY'";
    //         $WHERE2 = " WHERE L.COMPANY = '$COMPANY'";
    //     }
    //     if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
    //         $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE2; 
    //         $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1)";
    //     }else{
    //         $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT' ".$WHERE; 
    //         $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1)";
    //     }
        
    //     $idx = 1;
    //     $SQLW = "";
    //     if ($Search["regex"] == 'true') {
    //         $Search['value'] = strtoupper($Search['value']);
    //         foreach ($Columns as $values) {
    //             if ($values["data"] != NULL && $values["data"] != '') {
    //                 $FIELD = "FC." . $values["data"];
    //                 $VAL = "%" . $Search["value"] . "%";
    //                 if ($idx == 1) {
    //                     $SQLW .= " WHERE";
    //                 } else {
    //                     $SQLW .= " OR";
    //                 }
    //                 $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
    //                 $idx++;
    //             }
    //         }
    //     }
    //     $SQLO = "";
    //     if ($OrderField == "" || $OrderField == NULL) {
    //         $SQLO = " ORDER BY DATE_RECEIPT";
    //     } else {
    //         $SQLO = " ORDER BY $OrderField " . $Order[0]["dir"];
    //     }
    //     $result = $this->db->query("SELECT * FROM $q FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
    //     // var_dump($this->db->last_query());exit();
    //     $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC $SQLW")->result();
    //     $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC")->result();
    //     $return = [
    //         "data" => $result,
    //         "recordsTotal" => $CountAll[0]->JML,
    //         "recordsFiltered" => $CountFil[0]->JML
    //     ];
    //     $this->db->close();
    //     return $return;
    // }

    function HistoryDocNew($param){
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $WHERE2 = "";
        $COMPANY      = $param['COMPANY'];
        // var_dump($COMPANY[0]);exit;
        $YEAR = $param['YEAR'];

        if(count($COMPANY) > 1){
            foreach($COMPANY as $comp){
                $WHEREKOORD .= " OR L.COMPANY = '$comp'";
                $WHERE  .= " OR L.COMPANY = '$comp'";
                $WHERE2 .= " OR L.COMPANY = '$comp'";
            }

            if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
                $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' AND L.COMPANY = '$comp' ".$WHERE2; 
                $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
            }else if($DEPT == 'KOORD-KTU'){

                $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' AND L.COMPANY = '$comp' ".$WHEREKOORD; 
                $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
                // var_dump($q);exit;
            }
            else{
                // WHERE L.FIRST_DEPT = '$DEPT' 
                $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' AND L.COMPANY = '$comp' ".$WHERE; 
                $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
            }

        }
        else{
            if($DEPT == 'KOORD-KTU' && $COMPANY[0] == "0"){
                $FCCODE = $this->session->userdata('FCCODE');
                $q2 = "SELECT c.id, c.companycode,c.companyname,c.companyno, c.isactive FROM company c
                        INNER JOIN user_company_tab uct ON uct.companycode = c.companycode where uct.usercode = '$FCCODE'";
                $result = $this->db->query($q2)->row();
                $COMPANY = $result->ID;
                $WHEREKOORD = " AND TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' AND L.COMPANY = '$COMPANY[0]'";
            }

            if($COMPANY[0] != "0" ){
                $WHEREKOORD = " AND L.COMPANY = '$COMPANY[0]'";
                $WHERE = "  AND L.COMPANY = '$COMPANY[0]'";
                $WHERE2 = "  AND L.COMPANY = '$COMPANY[0]'";
                // var_dump($WHERE);exit;
            }

            if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
                $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' ".$WHERE2; 
                $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
            }else if($DEPT == 'KOORD-KTU'){

                $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' ".$WHEREKOORD; 
                $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
                // var_dump($q);exit;
            }
            else{
                // WHERE L.FIRST_DEPT = '$DEPT' 
                $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE TO_CHAR(LT.DATE_RECEIPT,'YYYY') = '$YEAR' ".$WHERE; 
                $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
            }


        }
        
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        // echo "<pre>";
        
        // var_dump($q);exit;
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            $SQLO = '';
            // " ORDER BY TO_DATE(DATE_RECEIPT,'mm-dd-yyyy') ". $Order[0]["dir"];
        } else {
            $SQLO = " ORDER BY TO_DATE( $OrderField,'mm-dd-yyyy') " . $Order[0]["dir"];
        }

        // var_dump($SQLO);exit;
        $result = $this->db->query("SELECT * FROM $q FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }

    function getHistoryDoc($param){
        $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, case when UPDATED_BY is null then CREATED_BY else UPDATED_BY END AS UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.CREATED_BY, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.COMPANY = ? AND LT.NO_RECEIPT_DOC = ?) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK,CREATED_BY, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID ASC";
        // var_dump($this->db->last_query());exit();
        return $this->db->query($q,[$param['COMPANY'], $param['NO_RECEIPT_DOC']])->result();
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
            // $this->db->from("TEMP_UPLOAD_FR");
            // $this->db->truncate();
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
                      $VOUCHERNO    = trim($rowData[0][6]);
                      $DPP          = trim($rowData[0][7]);
                      $PPN          = trim($rowData[0][8]);
                      $PPH          = trim($rowData[0][9]);
                      $NET          = $DPP + $PPN + $PPH;

                      $isNotZeroAll   = true;
                      if($COMPANYCODE == null && $NO_PO == null && $INVOICE_CODE == null && $VENDOR == null && $CURRENCY == null && $AMOUNT == null && $VOUCHERNO == null && $DPP == null && $PPN == null && $PPH == null && $NET == null){
                        $isNotZeroAll = false;
                      }
                      if($isNotZeroAll){

                            if($CURRENCY == null || $CURRENCY == ''){
                                $hadError        = true;
                                $ERROR_MESSAGE[] = "Currency is null"; 
                            }
                            if($AMOUNT == null || $AMOUNT == ''){
                                $hadError        = true;
                                $ERROR_MESSAGE[] = "Amount is null"; 
                            }
                            $this->db->where('COMPANYCODE',strtoupper($COMPANYCODE));
                            $getCompany = $this->db->get('COMPANY')->row();

                            if($getCompany == NULL){
                                $hadError        = true;
                                $ERROR_MESSAGE[] = "COMPANYCODE NOT FOUND"; 
                                $compCode = $COMPANYCODE;
                            }else{
                                $compCode   = $getCompany->ID;
                            }

                            $this->db->where('COMPANY',$compCode);
                            $this->db->where('INVOICE_CODE',$INVOICE_CODE);
                            $getExists = $this->db->get('LOG_FIRSTRECEIPT');

                            if($getExists->num_rows() > 0){
                                $hadError        = true;
                                $ERROR_MESSAGE[] = "No INV $INVOICE_CODE Already Exists"; 
                            }

                            // $this->db->where('NO_PO',$NO_PO);
                            // $getExists = $this->db->get('LOG_FIRSTRECEIPT');

                            // if($getExists->num_rows() > 0){
                            //     $hadError        = true;
                            //     $ERROR_MESSAGE[] = "No PO $NO_PO Already Exists"; 
                            // }
                            
                            $this->db->where('FCCODE',$VENDOR);
                            $getVendor = $this->db->get('SUPPLIER')->row();

                            if($getVendor == NULL){
                                $hadError        = true;
                                $ERROR_MESSAGE[] = "$VENDOR NOT FOUND"; 
                                $vendorCode = $VENDOR;
                            }else{
                                $vendorCode = $getVendor->ID;
                            }

                            $cekQuery = "DELETE TEMP_UPLOAD_FR WHERE COMPANY = '$compCode' AND INVOICE_CODE = '$INVOICE_CODE' AND NO_PO = '$NO_PO' AND VENDOR = '$vendorCode' AND AMOUNT = '$AMOUNT' AND CURRENCY = '$CURRENCY' AND VOUCHERNO = '$VOUCHERNO' AND DPP = '$DPP' AND AMOUNT_PPN = '$PPN' AND AMOUNT_PPH = '$PPH'";
                            $thisCek = $this->db->query($cekQuery);

                                $dataR = array(
                                          'COMPANY'   => $compCode,
                                          'INVOICE_CODE' => $INVOICE_CODE,
                                          'NO_PO' => $NO_PO,
                                          'VENDOR' => $vendorCode,
                                          'AMOUNT' => $AMOUNT,
                                          'VOUCHERNO' => $VOUCHERNO,
                                          'DPP' => $DPP,
                                          'AMOUNT_PPN' => $PPN,
                                          'AMOUNT_PPH' => $PPH,
                                          'AMOUNT_NET' => $NET,
                                          'CURRENCY' => $CURRENCY,
                                          "UUID" => $this->uuid->v4(),
                                          'USERNAME' => $USERNAME
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
                $qGet = "SELECT L.*, S.FCNAME AS VENDORNAME, C.COMPANYNAME AS COMPANYNAME FROM TEMP_UPLOAD_FR L LEFT JOIN COMPANY C ON C.ID = L.COMPANY LEFT JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.USERNAME = '$USERNAME'";
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

            $getDataTemp = $this->db->get_where("TEMP_UPLOAD_FR",['USERNAME' => $USERNAME])->result();
            $DEPARTMENT   = $this->session->userdata('DEPARTMENT');
            // $SQL = "SELECT (maxno + 1) NUMMAX
            //                   FROM (SELECT NVL(MAX (SUBSTR (NO_RECEIPT_DOC, 5)),0) maxno
            //                           FROM LOG_FIRSTRECEIPT
            //                          WHERE     TO_CHAR (CREATED_AT,'mm') = TO_CHAR (SYSDATE, 'MM')
            //                                AND TO_CHAR (CREATED_AT,'yy') = TO_CHAR (SYSDATE, 'YY')) A";
                    
            //         $auto = $this->db->query($SQL)->row()->NUMMAX;
            // var_dump($auto);exit();

                // $no = $auto;
                if($getDataTemp){
                    foreach($getDataTemp as $r){

                        $TEMPID            = $r->ID;
                        $COMPANY           = $r->COMPANY;
                        $INVOICE_CODE      = $r->INVOICE_CODE;
                        $NO_PO             = $r->NO_PO;
                        $VENDOR            = $r->VENDOR;
                        $CURRENCY          = $r->CURRENCY;
                        $AMOUNT            = $r->AMOUNT;
                        $VOUCHERNO         = $r->VOUCHERNO;
                        $DPP               = $r->DPP;
                        $PPN               = $r->AMOUNT_PPN;
                        $PPH               = $r->AMOUNT_PPH;
                        $NET               = $r->AMOUNT_NET;
                        
                        $this->db->where('COMPANY',$COMPANY);
                        $this->db->where('INVOICE_CODE',$INVOICE_CODE);
                        $getExists = $this->db->get('LOG_FIRSTRECEIPT');

                        if($getExists->num_rows() > 0){
                            $this->db->trans_rollback();
                            throw new Exception("Duplicate INV $INVOICE_CODE");exit;
                        }

                        // $this->db->where('NO_PO',$NO_PO);
                        // $getExists = $this->db->get('LOG_FIRSTRECEIPT');

                        // if($getExists->num_rows() > 0){
                        //     $this->db->trans_rollback();
                        //     throw new Exception("Duplicate NO PO $NO_PO");
                        // }

                        
                        // $USERNAME     = $this->session->userdata('username');
                        
                        $dt = [
                            'NO_RECEIPT_DOC' => $this->uuid->v4(),
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

                        $nodt = $dt['NO_RECEIPT_DOC'];
                        $this->db->where('NO_RECEIPT_DOC',$nodt);
                        $getNoExists = $this->db->get('LOG_FIRSTRECEIPT');

                        if($getNoExists->num_rows() > 0){
                            $this->db->trans_rollback();
                            throw new Exception("Duplicate No Receipt $nodt");exit;
                        }else{
                            $result1 = $result1->set($dt)->insert('LOG_FIRSTRECEIPT');    
                        }

                        

                        if($result1 == true){
                            $trans = array(
                                'NO_RECEIPT_DOC' => $dt['NO_RECEIPT_DOC'],
                                'INVOICE_CODE'   => $INVOICE_CODE,
                                'NO_PO'          => $NO_PO,
                                'VENDOR' => $VENDOR,
                                'AMOUNT' => $AMOUNT,
                                'VOUCHERNO' => $VOUCHERNO,
                                'DPP' => $DPP,
                                'AMOUNT_PPN' => $AMOUNT_PPN,
                                'AMOUNT_PPH' => $AMOUNT_PPH,
                                'AMOUNT_NET' => $AMOUNT_NET,
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
                        // $no++;
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

    function ReceiveExport($data){
        // echo "<pre>";
        // var_dump($data);exit;
        try{

            $DEPT = $this->session->userdata('DEPARTMENT');
            $COMPANY = $data['COMPANY'];
            $MONTH   = $data['MONTH'];
            $YEAR    = $data['YEAR'];

            $VENDOR = $data['VENDOR'];

            if($MONTH < "10"){
                $MONTH = '0'.$MONTH;
            }
            if($MONTH != null && $YEAR != null){
                $WHEREDATE = " WHERE TO_CHAR (DATE_RECEIPT, 'YYYY') = '$YEAR' AND TO_CHAR (DATE_RECEIPT, 'MM') = '$MONTH' ";
            }
            else{
                $FROMDATE    = $data['FROMDATE'];
                $TODATE    = $data['TODATE'];
                $WHEREDATE = " WHERE DATE_RECEIPT BETWEEN TO_DATE ('$FROMDATE', 'mm/dd/yyyy') AND TO_DATE ('$TODATE', 'mm/dd/yyyy') ";
            }
            
            $WHERE  = '';            
            
            if($COMPANY != "0"){
                $WHERE = " AND L.COMPANY = '$COMPANY'";
            }


            if($VENDOR != "0" || 0){
                $WHERE  .= " AND L.VENDOR = '$VENDOR' ";
            }

            if($DEPT == 'IT'){
                $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYNAME, MAX (POS) AS POS, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, L.FIRST_DEPT, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE LT.POS = '2' AND LT.STATUS = '1' ". $WHERE;
                $q2.= " ) $WHEREDATE GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYNAME, POS,STATUS ORDER BY ID DESC) WHERE RANKI = 1";
            }
            else{
                $q2 = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYNAME, POS, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME,CURRENCY,FIRST_DEPT, COMPANYNAME, MAX (POS) AS POS,STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (POS) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.SEND_TO, LT.DATE_RECEIPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, L.FIRST_DEPT, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE LT.SEND_TO = '$DEPT' AND LT.POS = '2' AND LT.STATUS = '1' ".$WHERE;
                $q2 .= " ) $WHEREDATE GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, SEND_TO, DATE_RECEIPT, VENDORNAME, CURRENCY,FIRST_DEPT, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
            }


            $result = $this->db->query($q2)->result();
            // var_dump($this->db->last_query());exit;
            $spreadsheet  = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('KPN CORP');

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', "COMPANY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', "INV CODE");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', "NO PO");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', "VENDOR");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', "CURRENCY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', "AMOUNT");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', "REMARK");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', "SEND TO");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', "UPDATED BY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', "DATE");
                

            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];

            $style_Content = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
            );

            $style_ContentNumeric = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
              
            );

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '0000000'],
                    ],
                ]
            ];

            $sheet = $spreadsheet->getActiveSheet();
            $colall = array('A','B','C','D','E','F','G','H','I','J','K','L',
                    'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

            $i = 2;
            $p = 2;
            $no = 2;

            foreach ($result as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $row->COMPANYNAME)
                ->setCellValue('B'.$i, $row->INVOICE_CODE)
                ->setCellValue('C'.$i, $row->NO_PO)
                ->setCellValue('D'.$i, $row->VENDORNAME)
                ->setCellValue('E'.$i, $row->CURRENCY)
                ->setCellValue('F'.$i, $row->AMOUNT)
                ->setCellValue('G'.$i, $row->REMARK)
                ->setCellValue('H'.$i, $row->SEND_TO)
                ->setCellValue('I'.$i, $row->UPDATED_BY)
                ->setCellValue('J'.$i, $row->DATE_RECEIPT);
                $i++;
            }
            

            foreach(range('A','J') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);

            }
            
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F2:F'.$i)
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING);  
            
            $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            
            $sheet->getStyle('A1:J1')->applyFromArray($styleHeader);
            $sheet->getStyle('A1:J'.$i)->applyFromArray($styleArray);
            $sheet->getStyle('A2:J'.$i)->applyFromArray($style_Content);
            $sheet->getStyle('F2:F'.$i)->applyFromArray($style_ContentNumeric);
            $sheet->setTitle('Last Doc');
            
            $return = [
                    'STATUS' => TRUE,
                    'Data' => $spreadsheet
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

    function LastDocExport($data){
        
        try{

            $DEPT = $this->session->userdata('DEPARTMENT');
            $COMPANY = $data['COMPANY'];
            $MONTH   = $data['MONTH'];
            $YEAR    = $data['YEAR'];
            
            $SEND_TO = $data['SEND_TO'];
            $DEPTWHERE    = $data['DEPT'];

            $VENDOR = $data['VENDOR'];

            if($MONTH < "10"){
                $MONTH = '0'.$MONTH;
            }
            if($MONTH != null && $YEAR != null){
                $WHEREDATE = " WHERE TO_CHAR (DATE_RECEIPT, 'YYYY') = '$YEAR' AND TO_CHAR (DATE_RECEIPT, 'MM') = '$MONTH' ";
            }
            else{
                $FROMDATE    = $data['FROMDATE'];
                $TODATE    = $data['TODATE'];
                $WHEREDATE = " WHERE TO_CHAR (DATE_RECEIPT, 'mm/dd/yyyy') BETWEEN '$FROMDATE' AND '$TODATE'";
            }
            
            $WHERE  = '';            
            $WHERE2 = '';
            if($COMPANY != "0" || 0){
                $WHERE = " AND COMPANYID = '$COMPANY'";
                $WHERE2 = " AND COMPANYID = '$COMPANY'";
            }

            if($SEND_TO != "0" || 0){
                $WHERE  .= " AND SEND_TO = '$SEND_TO' ";
                $WHERE2 .= " AND SEND_TO = '$SEND_TO' ";
            }

            if($DEPTWHERE != "0" || 0){
                $WHERE  .= " AND DEPT = '$DEPTWHERE' ";
                $WHERE2 .= " AND DEPT = '$DEPTWHERE' ";
            }

            if($VENDOR != "0" || 0){
                $WHERE  .= " AND VENDOR = '$VENDOR' ";
            }

            // if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
                $q = "SELECT DISTINCT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT,TIME_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY,CREATED_BY, COMPANYNAME, STATUS FROM ( ";
                $q .= " SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT,TIME_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY,CREATED_BY, COMPANYNAME, STATUS FROM ( ";
                $q .= " SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, TO_CHAR (DATE_RECEIPT, 'MM-DD-YYYY') AS DATE_RECEIPT,TO_CHAR (DATE_RECEIPT, 'HH24:MI:SS') AS TIME_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY,CREATED_BY, COMPANYNAME, STATUS,COMPANYID, RANK ()
                         OVER (PARTITION BY NO_RECEIPT_DOC
                               ORDER BY MAX (DATE_RECEIPT) DESC)
                            AS RANKI FROM ( ";
                $q .= " SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY,LT.VOUCHERNO,LT.DPP,LT.AMOUNT_PPN,LT.AMOUNT_PPH,LT.AMOUNT_NET, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY,L.CREATED_BY, C.COMPANYNAME,C.ID as COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR) ".$WHEREDATE.$WHERE; 
                $q .= " GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY,VOUCHERNO,DPP,AMOUNT_PPN,AMOUNT_PPH,AMOUNT_NET, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY,CREATED_BY, COMPANYNAME, POS, STATUS,COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
            // }
            // else{
            //     $q = "SELECT DISTINCT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( ";
            //     $q .= " SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( ";
            //     $q .= " SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR (DATE_RECEIPT, 'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK ()
            //              OVER (PARTITION BY NO_RECEIPT_DOC
            //                    ORDER BY MAX (DATE_RECEIPT) DESC)
            //                 AS RANKI FROM ( ";
            //     $q .= " SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT') ".$WHEREDATE.$WHERE; 
            //     $q .= " GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1)";
            // }


            $result = $this->db->query($q)->result();
            // var_dump($this->db->last_query());exit;
            $spreadsheet  = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('KPN CORP');
            

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', "COMPANY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', "INV CODE");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', "NO PO");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', "VENDOR");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', "CURRENCY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', "AMOUNT");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', "DEPT");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', "REMARKS");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', "SEND TO");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', "UPDATED BY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K1', "DATE");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L1', "TIMES");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M1', "VOUCHERNO");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('N1', "DPP");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O1', "AMOUNT_PPN");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P1', "AMOUNT_PPH");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q1', "AMOUNT_NET");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('R1', "CREATED_BY");
                

            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];

            $style_Content = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
            );

            $style_ContentNumeric = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
              
            );

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '0000000'],
                    ],
                ]
            ];

            $sheet = $spreadsheet->getActiveSheet();
            $colall = array('A','B','C','D','E','F','G','H','I','J','K','L',
                    'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

            $i = 2;
            $p = 2;
            $no = 2;

            foreach ($result as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $row->COMPANYNAME)
                ->setCellValue('B'.$i, $row->INVOICE_CODE)
                ->setCellValue('C'.$i, $row->NO_PO)
                ->setCellValue('D'.$i, $row->VENDORNAME)
                ->setCellValue('E'.$i, $row->CURRENCY)
                ->setCellValue('F'.$i, $row->AMOUNT)
                ->setCellValue('G'.$i, $row->DEPT)
                ->setCellValue('H'.$i, $row->REMARK)
                ->setCellValue('I'.$i, $row->SEND_TO)
                ->setCellValue('J'.$i, $row->UPDATED_BY)
                ->setCellValue('K'.$i, $row->DATE_RECEIPT)
                ->setCellValue('L'.$i, $row->TIME_RECEIPT)
                ->setCellValue('M'.$i, $row->VOUCHERNO)
                ->setCellValue('N'.$i, $row->DPP)
                ->setCellValue('O'.$i, $row->AMOUNT_PPN)
                ->setCellValue('P'.$i, $row->AMOUNT_PPH)
                ->setCellValue('Q'.$i, $row->AMOUNT_NET)
                ->setCellValue('R'.$i, $row->CREATED_BY);
                $i++;
            }
            

            foreach(range('A','R') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);

            }
            
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F2:F'.$i)
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING);  
            
            $sheet->getStyle('A1:R1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            
            $sheet->getStyle('A1:R1')->applyFromArray($styleHeader);
            $sheet->getStyle('A1:R'.$i)->applyFromArray($styleArray);
            $sheet->getStyle('A2:R'.$i)->applyFromArray($style_Content);
            $sheet->getStyle('F2:F'.$i)->applyFromArray($style_ContentNumeric);
            $sheet->getStyle('N2:Q'.$i)->applyFromArray($style_ContentNumeric);
            $sheet->setTitle('EXPORT');
            
            // var_dump($spreadsheet);exit;
            $return = [
                    'STATUS' => TRUE,
                    'Data' => $spreadsheet
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

    function ExportFirst($data){
        
        try{

            $DEPT = $this->session->userdata('DEPARTMENT');
            
            $DEPTWHERE    = $data['DEPT'];

            $FROMDATE    = $data['FROMDATE'];
            $TODATE    = $data['TODATE'];
            $WHEREDATE = " WHERE TO_CHAR (L.CREATED_AT, 'mm/dd/yyyy') BETWEEN '$FROMDATE' AND '$TODATE'";
            
            $WHERE  = '';            
            $WHERE2 = '';

            if($DEPTWHERE != "0" || 0){
                $WHERE  .= " AND L.FIRST_DEPT = '$DEPTWHERE' ";
            }

            $q = "  SELECT L.*, S.FCNAME AS VENDORNAME, C.COMPANYCODE FROM LOG_FIRSTRECEIPT L
                     INNER JOIN COMPANY C ON C.ID = L.COMPANY
                     INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHEREDATE.$WHERE." ORDER BY L.ID ASC";

            $result = $this->db->query($q)->result();
            // var_dump($this->db->last_query());exit;
            $spreadsheet  = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('KPN CORP');
            

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', "COMPANY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', "INV");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', "NO PO");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', "VENDOR");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', "CURRENCY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', "AMOUNT");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', "DEPT");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', "REMARKS");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', "CREATED_AT");
                

            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];

            $style_Content = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
            );

            $style_ContentNumeric = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
              
            );

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '0000000'],
                    ],
                ]
            ];

            $sheet = $spreadsheet->getActiveSheet();
            $colall = array('A','B','C','D','E','F','G','H','I','J','K','L',
                    'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

            $i = 2;
            $p = 2;
            $no = 2;

            foreach ($result as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $row->COMPANYCODE)
                ->setCellValue('B'.$i, $row->INVOICE_CODE)
                ->setCellValue('C'.$i, $row->NO_PO)
                ->setCellValue('D'.$i, $row->VENDORNAME)
                ->setCellValue('E'.$i, $row->CURRENCY)
                ->setCellValue('F'.$i, $row->AMOUNT)
                ->setCellValue('G'.$i, $row->FIRST_DEPT)
                ->setCellValue('H'.$i, $row->REMARK)
                ->setCellValue('I'.$i, $row->CREATED_AT);
                $i++;
            }
            

            foreach(range('A','I') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);

            }
            
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F2:F'.$i)
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING);  
            
            $sheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            
            $sheet->getStyle('A1:I1')->applyFromArray($styleHeader);
            $sheet->getStyle('A1:I'.$i)->applyFromArray($styleArray);
            $sheet->getStyle('A2:I'.$i)->applyFromArray($style_Content);
            $sheet->getStyle('F2:F'.$i)->applyFromArray($style_ContentNumeric);
            $sheet->setTitle('EXPORT');
            
            // var_dump($spreadsheet);exit;
            $return = [
                    'STATUS' => TRUE,
                    'Data' => $spreadsheet
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

    function __LastDocExport($data){
        // echo "<pre>";
        // var_dump($data);exit;
        try{

            $DEPT = $this->session->userdata('DEPARTMENT');
            $COMPANY = $data['COMPANY'];
            $MONTH   = $data['MONTH'];
            $YEAR    = $data['YEAR'];
            $FROMDATE    = $data['FROMDATE'];
            $TODATE    = $data['TODATE'];
            $SEND_TO = $data['SEND_TO'];

            if($MONTH < "10"){
                $MONTH = '0'.$MONTH;
            }
            if($MONTH != null && $YEAR != null){
                $WHEREDATE = " WHERE TO_CHAR (DATE_RECEIPT, 'YYYY') = '$YEAR' AND TO_CHAR (DATE_RECEIPT, 'MM') = '$MONTH' ";
            }
            else{
                $WHEREDATE = " WHERE TO_CHAR (DATE_RECEIPT, 'mm/dd/yyyy') BETWEEN '$FROMDATE' AND '$TODATE' ";
            }
            
            if($COMPANY != "0"){
                $WHERE = " AND L.COMPANY = '$COMPANY'";
                $WHERE2 = " WHERE L.COMPANY = '$COMPANY'";
            }

            if($SEND_TO != "0"){
                $WHERE  .= " AND LT.SEND_TO = '$SEND_TO' ";
                $WHERE2 .= " AND LT.SEND_TO = '$SEND_TO' ";
            }

            if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
                $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE2; 
                $q.= " ) ".$WHEREDATE;
                $q.= " GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
            }else{
                $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT' ".$WHERE; 
                $q.= " ) ".$WHEREDATE;
                $q.= "  GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
            }


            $result = $this->db->query($q)->result();
            // var_dump($this->db->last_query());exit;
            $spreadsheet  = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('KPN CORP');

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', "COMPANY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', "INV CODE");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', "NO PO");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', "VENDOR");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', "CURRENCY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', "AMOUNT");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', "DEPT");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', "SEND TO");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', "UPDATED BY");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', "DATE");
                

            $styleHeader = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];

            $style_Content = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
            );

            $style_ContentNumeric = array(          
              'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              )
              
            );

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '0000000'],
                    ],
                ]
            ];

            $sheet = $spreadsheet->getActiveSheet();
            $colall = array('A','B','C','D','E','F','G','H','I','J','K','L',
                    'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

            $i = 2;
            $p = 2;
            $no = 2;

            foreach ($result as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $row->COMPANYNAME)
                ->setCellValue('B'.$i, $row->INVOICE_CODE)
                ->setCellValue('C'.$i, $row->NO_PO)
                ->setCellValue('D'.$i, $row->VENDORNAME)
                ->setCellValue('E'.$i, $row->CURRENCY)
                ->setCellValue('F'.$i, $row->AMOUNT)
                ->setCellValue('G'.$i, $row->DEPT)
                ->setCellValue('H'.$i, $row->SEND_TO)
                ->setCellValue('I'.$i, $row->UPDATED_BY)
                ->setCellValue('J'.$i, $row->DATE_RECEIPT);
                $i++;
            }
            

            foreach(range('A','J') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);

            }
            
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F2:F'.$i)
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING);  
            
            $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            
            $sheet->getStyle('A1:J1')->applyFromArray($styleHeader);
            $sheet->getStyle('A1:J'.$i)->applyFromArray($styleArray);
            $sheet->getStyle('A2:J'.$i)->applyFromArray($style_Content);
            $sheet->getStyle('F2:F'.$i)->applyFromArray($style_ContentNumeric);
            $sheet->setTitle('Last Doc');
            
            $return = [
                    'STATUS' => TRUE,
                    'Data' => $spreadsheet
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

    public function DeleteMaster($param){
        // var_dump($param);exit;
        try {
            $this->db->trans_begin();
            // $TIPE = $param['TIPE'];
            $NO_RECEIPT_DOC  = $param['NO_RECEIPT_DOC'];
            $NO_PO           = $param['NO_PO'];
            $UUID          = $param['UUID'];
            $res  = FALSE;

            $q = "DELETE FROM LOG_FIRSTRECEIPT WHERE NO_RECEIPT_DOC = '".$NO_RECEIPT_DOC."' AND UUID = '".$UUID."'";
            $res = $this->db->query($q);


            if ($res) {
                $q   = "DELETE FROM LOG_TRANSACTION WHERE NO_RECEIPT_DOC = '".$NO_RECEIPT_DOC."' AND NO_PO = '".$NO_PO."'";
                $res = $this->db->query($q);
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                $this->db->trans_rollback(); throw new Exception('Data Save Failed !!');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $return = [
                'STATUS' => FALSE,
                'MESSAGE' => $ex->getMessage()
            ];
        }
        $this->db->close();
        return $return;
    }

        function HistoryDocOld($param){
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $WHERE2 = "";
        $COMPANY      = $param['COMPANY'];

        if($DEPT == 'KOORD-KTU' && $COMPANY == "0"){
            $FCCODE = $this->session->userdata('FCCODE');
            $q2 = "SELECT c.id, c.companycode,c.companyname,c.companyno, c.isactive FROM company c
                    INNER JOIN user_company_tab uct ON uct.companycode = c.companycode where uct.usercode = '$FCCODE'";
            $result = $this->db->query($q2)->row();
            $COMPANY = $result->ID;
            $WHEREKOORD = "WHERE L.COMPANY = '$COMPANY'";
        }
        

        if($COMPANY != "0"){
            $WHEREKOORD = "WHERE L.COMPANY = '$COMPANY'";
            $WHERE = " WHERE L.COMPANY = '$COMPANY'";
            $WHERE2 = " WHERE L.COMPANY = '$COMPANY'";
        }
        // if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
        //     $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE2; 
        //     $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1";
        // }else{
        //     $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT' ".$WHERE; 
        //     $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1";
        // }
        // return $this->db->query($q)->result();
        // $this->db->close();
        $Lenght = $param["length"];
        $Start = $param["start"];
        $Columns = $param["columns"];
        $Search = $param["search"];
        $Order = $param["order"];
        $OrderField = $Columns[$Order[0]["column"]]["data"];
        // echo "<pre>";
        
        if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
            $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE2; 
            $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
        }else if($DEPT == 'KOORD-KTU'){

            $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHEREKOORD; 
            $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
            // var_dump($q);exit;
        }
        else{
            // WHERE L.FIRST_DEPT = '$DEPT' 
            $q = "(SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE; 
            $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1)";
        }
        
        // var_dump($q);exit;
        $idx = 1;
        $SQLW = "";
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $FIELD = "FC." . $values["data"];
                    $VAL = "%" . $Search["value"] . "%";
                    if ($idx == 1) {
                        $SQLW .= " WHERE";
                    } else {
                        $SQLW .= " OR";
                    }
                    $SQLW .= " UPPER($FIELD) LIKE '$VAL'";
                    $idx++;
                }
            }
        }
        $SQLO = "";
        if ($OrderField == "" || $OrderField == NULL) {
            $SQLO = '';
            // " ORDER BY TO_DATE(DATE_RECEIPT,'mm-dd-yyyy') ". $Order[0]["dir"];
        } else {
            $SQLO = " ORDER BY TO_DATE( $OrderField,'mm-dd-yyyy') " . $Order[0]["dir"];
        }

        // var_dump($SQLO);exit;
        $result = $this->db->query("SELECT * FROM $q FC $SQLW $SQLO OFFSET $Start ROWS FETCH NEXT $Lenght ROWS ONLY")->result();
        // var_dump($this->db->last_query());exit();
        $CountFil = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC $SQLW")->result();
        $CountAll = $this->db->query("SELECT COUNT(*) AS JML FROM $q FC")->result();
        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];
        $this->db->close();
        return $return;
    }


    function ShowDataLastDoc1($param){
            $DEPT = $this->session->userdata('DEPARTMENT');
            $USERNAME     = $this->session->userdata('username');

            $WHERE = "";
            $WHERE2 = "";
            $COMPANY      = $param['COMPANY'];

            if($COMPANY != "0"){
                $WHERE = " AND L.COMPANY = '$COMPANY'";
                $WHERE2 = " WHERE L.COMPANY = '$COMPANY'";
            }
            if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
                $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE2; 
                $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
            }else{
                $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT' ".$WHERE; 
                $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
            }
            
            return $this->db->query($q)->result();
            
            $this->db->close();
    }


    public function ShowFileData($param) {
        $result = $this->db->select("*")
                    ->from("LOG_FILES")
                    ->where('NO_RECEIPT_DOC',$param['NO_RECEIPT_DOC'])
                    ->order_by('ID DESC')->get()->result();
        $this->db->close();
        return $result;
    }

    public function uploadElogFile($param,$location) {

        try
        {   
            $USERNAME     = $this->session->userdata('FCCODE');
            $NO_RECEIPT_DOC         = $param['NO_RECEIPT_DOC'];
            $this->db->trans_begin();
            
            $result = FALSE;
            $config['upload_path']          = LOGFILES;
            $config['allowed_types']        = 'zip';
            $config['overwrite']            = TRUE;
            $config['max_size']             = 5120;

            $this->load->library('upload');
            $this->upload->initialize($config);
            $check = $this->upload->do_upload('userfile');
            $media = $this->upload->data();
            // var_dump($config);exit;
            if (!$check){
                throw new Exception($this->upload->display_errors());
                
            } else {
                $qGet = "SELECT * FROM LOG_FILES WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC' ";
                $res   = $this->db->query($qGet);          
                if($res->num_rows() > 0){
                    
                    $path_to_file = LOGFILES.$res->result_array()[0]['FILENAME'];
                    // echo "<pre>";
                    // var_dump($path_to_file);exit;
                    if ( file_exists($path_to_file) ){
                        if( unlink($path_to_file) ) {
                            
                            $hp = array(
                                'FILENAME' => $media['file_name'],
                                'FCENTRY'   => $USERNAME
                            );
                            $this->db->where('NO_RECEIPT_DOC',$NO_RECEIPT_DOC);
                            $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                            ->set($hp)->update('LOG_FILES');
                        }
                    }
                }else{
                    $hp = array(
                        'NO_RECEIPT_DOC' => $NO_RECEIPT_DOC,
                        'FILENAME' => $media['file_name'],
                        'FCENTRY'   => $USERNAME
                    );
                    
                    $result = $this->db->set("LASTUPDATE", "SYSDATE", false)
                                    ->set($hp)->insert('LOG_FILES');
                }
                $qGet = "SELECT * FROM LOG_FILES WHERE NO_RECEIPT_DOC = '$NO_RECEIPT_DOC' ";
                $res   = $this->db->query($qGet)->result();          
                if ($result) {
                    $this->db->trans_commit();
                    $return = [
                        'STATUS' => TRUE,
                        'MESSAGE' => $res
                    ];
                }
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

    function HistoryDoc1($param){
        $DEPT = $this->session->userdata('DEPARTMENT');
        $USERNAME     = $this->session->userdata('username');

        $WHERE = "";
        $COMPANY      = $param['COMPANY'];

        if($COMPANY != "0"){
            $WHERE = " AND L.COMPANY = '$COMPANY'";
            // $WHERE2 = " WHERE L.COMPANY = '$COMPANY'";
        }
        // if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
            $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE; 
            $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1";
        // }else{
        //     $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME,POS, STATUS, COMPANYID FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME, C.ID AS COMPANYID FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT' ".$WHERE; 
        //     $q.= " ) GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS, COMPANYID ORDER BY ID DESC) WHERE RANKI = 1";
        // }
        // var_dump($q)exit();
        return $this->db->query($q)->result();

        $this->db->close();
    }

    // function LastDocExport($data){
    //     // echo "<pre>";
    //     // var_dump($data);exit;
    //     try{

    //         $DEPT = $this->session->userdata('DEPARTMENT');
    //         $COMPANY = $data['COMPANY'];
    //         $MONTH   = $data['MONTH'];
    //         $YEAR    = $data['YEAR'];

    //         if($MONTH < "10"){
    //             $MONTH = '0'.$MONTH;
    //         }

    //         if($COMPANY != "0"){
    //             $WHERE = " AND L.COMPANY = '$COMPANY'";
    //             $WHERE2 = " WHERE L.COMPANY = '$COMPANY'";
    //         }
    //         if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
    //             $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE2; 
    //             $q.= " ) WHERE TO_CHAR (DATE_RECEIPT, 'YYYY') = '$YEAR' AND TO_CHAR (DATE_RECEIPT, 'MM') = '$MONTH' GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
    //         }else{
    //             $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT' ".$WHERE; 
    //             $q.= " ) WHERE TO_CHAR (DATE_RECEIPT, 'YYYY') = '$YEAR' AND TO_CHAR (DATE_RECEIPT, 'MM') = '$MONTH' GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
    //         }


    //         $result = $this->db->query($q)->result();

    //         $spreadsheet  = new Spreadsheet();
    //         $spreadsheet->getProperties()->setCreator('KPN CORP');

    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', "COMPANY");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', "INV CODE");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', "NO PO");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', "VENDOR");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', "CURRENCY");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', "AMOUNT");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', "DEPT");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', "SEND TO");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', "UPDATED BY");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', "DATE");
                

    //         $styleHeader = [
    //             'alignment' => [
    //                 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             ],
    //         ];

    //         $style_Content = array(          
    //           'alignment' => array(
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    //             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    //           )
    //         );

    //         $style_ContentNumeric = array(          
    //           'alignment' => array(
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
    //             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    //           )
              
    //         );

    //         $styleArray = [
    //             'borders' => [
    //                 'allBorders' => [
    //                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                     'color' => ['argb' => '0000000'],
    //                 ],
    //             ]
    //         ];

    //         $sheet = $spreadsheet->getActiveSheet();
    //         $colall = array('A','B','C','D','E','F','G','H','I','J','K','L',
    //                 'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

    //         $i = 2;
    //         $p = 2;
    //         $no = 2;

    //         foreach ($result as $row) {
    //             $spreadsheet->setActiveSheetIndex(0)
    //             ->setCellValue('A'.$i, $row->COMPANYNAME)
    //             ->setCellValue('B'.$i, $row->INVOICE_CODE)
    //             ->setCellValue('C'.$i, $row->NO_PO)
    //             ->setCellValue('D'.$i, $row->VENDORNAME)
    //             ->setCellValue('E'.$i, $row->CURRENCY)
    //             ->setCellValue('F'.$i, $row->AMOUNT)
    //             ->setCellValue('G'.$i, $row->DEPT)
    //             ->setCellValue('H'.$i, $row->SEND_TO)
    //             ->setCellValue('I'.$i, $row->UPDATED_BY)
    //             ->setCellValue('J'.$i, $row->DATE_RECEIPT);
    //             $i++;
    //         }
            

    //         foreach(range('A','J') as $columnID) {
    //             $sheet->getColumnDimension($columnID)->setAutoSize(true);

    //         }
            
    //         $spreadsheet->setActiveSheetIndex(0)->getStyle('F2:F'.$i)
    //         ->getNumberFormat()
    //         ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING);  
            
    //         $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            
    //         $sheet->getStyle('A1:J1')->applyFromArray($styleHeader);
    //         $sheet->getStyle('A1:J'.$i)->applyFromArray($styleArray);
    //         $sheet->getStyle('A2:J'.$i)->applyFromArray($style_Content);
    //         $sheet->getStyle('F2:F'.$i)->applyFromArray($style_ContentNumeric);
    //         $sheet->setTitle('Last Doc');
            
    //         $return = [
    //                 'STATUS' => TRUE,
    //                 'Data' => $spreadsheet
    //             ];
    //     } catch (Exception $ex) {
    //         $return = [
    //             'STATUS' => FALSE,
    //             'Data' => $ex->getMessage()
    //         ];
    //     }
    //     $this->db->close();
    //     return $return;

    // }

    // function LastDocExport($data){
    //     // echo "<pre>";
    //     // var_dump($data);exit;
    //     try{

    //         $DEPT = $this->session->userdata('DEPARTMENT');
    //         $COMPANY = $data['COMPANY'];
    //         $MONTH   = $data['MONTH'];
    //         $YEAR    = $data['YEAR'];

    //         if($MONTH < "10"){
    //             $MONTH = '0'.$MONTH;
    //         }

    //         if($COMPANY != "0"){
    //             $WHERE = " AND L.COMPANY = '$COMPANY'";
    //             $WHERE2 = " WHERE L.COMPANY = '$COMPANY'";
    //         }
    //         if($DEPT == 'AP' || $DEPT == 'GL' || $DEPT == 'FINANCE' || $DEPT == 'TAX' || $DEPT == 'IT'){
    //             $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR ".$WHERE2; 
    //             $q.= " ) WHERE TO_CHAR (DATE_RECEIPT, 'YYYY') = '$YEAR' AND TO_CHAR (DATE_RECEIPT, 'MM') = '$MONTH' GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
    //         }else{
    //             $q = "SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS FROM ( SELECT ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, TO_CHAR(DATE_RECEIPT,'MM-DD-YYYY HH24:MI:SS') AS DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, STATUS, RANK () OVER (PARTITION BY NO_RECEIPT_DOC ORDER BY MAX (DATE_RECEIPT) DESC) AS RANKI FROM (SELECT LT.ID, LT.NO_RECEIPT_DOC, LT.INVOICE_CODE, LT.NO_PO, LT.VENDOR, LT.AMOUNT, LT.REMARK, LT.UPDATED_BY, LT.DATE_RECEIPT, LT.SEND_TO, LT.DEPT, LT.POS, LT.STATUS, S.FCNAME AS VENDORNAME, L.CURRENCY, C.COMPANYNAME FROM LOG_FIRSTRECEIPT L INNER JOIN LOG_TRANSACTION LT ON LT.NO_RECEIPT_DOC = L.NO_RECEIPT_DOC INNER JOIN COMPANY C ON C.ID = L.COMPANY INNER JOIN SUPPLIER S ON S.ID = L.VENDOR WHERE L.FIRST_DEPT = '$DEPT' ".$WHERE; 
    //             $q.= " ) WHERE TO_CHAR (DATE_RECEIPT, 'YYYY') = '$YEAR' AND TO_CHAR (DATE_RECEIPT, 'MM') = '$MONTH' GROUP BY ID, NO_RECEIPT_DOC, INVOICE_CODE, NO_PO, VENDOR, AMOUNT, REMARK, UPDATED_BY, DATE_RECEIPT, SEND_TO, DEPT, VENDORNAME, CURRENCY, COMPANYNAME, POS, STATUS ORDER BY ID DESC) WHERE RANKI = 1";
    //         }


    //         $result = $this->db->query($q)->result();

    //         $spreadsheet  = new Spreadsheet();
    //         $spreadsheet->getProperties()->setCreator('KPN CORP');

    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', "COMPANY");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', "INV CODE");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', "NO PO");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', "VENDOR");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', "CURRENCY");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', "AMOUNT");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', "DEPT");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', "SEND TO");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', "UPDATED BY");
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', "DATE");
                

    //         $styleHeader = [
    //             'alignment' => [
    //                 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             ],
    //         ];

    //         $style_Content = array(          
    //           'alignment' => array(
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    //             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    //           )
    //         );

    //         $style_ContentNumeric = array(          
    //           'alignment' => array(
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
    //             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    //           )
              
    //         );

    //         $styleArray = [
    //             'borders' => [
    //                 'allBorders' => [
    //                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                     'color' => ['argb' => '0000000'],
    //                 ],
    //             ]
    //         ];

    //         $sheet = $spreadsheet->getActiveSheet();
    //         $colall = array('A','B','C','D','E','F','G','H','I','J','K','L',
    //                 'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

    //         $i = 2;
    //         $p = 2;
    //         $no = 2;

    //         foreach ($result as $row) {
    //             $spreadsheet->setActiveSheetIndex(0)
    //             ->setCellValue('A'.$i, $row->COMPANYNAME)
    //             ->setCellValue('B'.$i, $row->INVOICE_CODE)
    //             ->setCellValue('C'.$i, $row->NO_PO)
    //             ->setCellValue('D'.$i, $row->VENDORNAME)
    //             ->setCellValue('E'.$i, $row->CURRENCY)
    //             ->setCellValue('F'.$i, $row->AMOUNT)
    //             ->setCellValue('G'.$i, $row->DEPT)
    //             ->setCellValue('H'.$i, $row->SEND_TO)
    //             ->setCellValue('I'.$i, $row->UPDATED_BY)
    //             ->setCellValue('J'.$i, $row->DATE_RECEIPT);
    //             $i++;
    //         }
            

    //         foreach(range('A','J') as $columnID) {
    //             $sheet->getColumnDimension($columnID)->setAutoSize(true);

    //         }
            
    //         $spreadsheet->setActiveSheetIndex(0)->getStyle('F2:F'.$i)
    //         ->getNumberFormat()
    //         ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING);  
            
    //         $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            
    //         $sheet->getStyle('A1:J1')->applyFromArray($styleHeader);
    //         $sheet->getStyle('A1:J'.$i)->applyFromArray($styleArray);
    //         $sheet->getStyle('A2:J'.$i)->applyFromArray($style_Content);
    //         $sheet->getStyle('F2:F'.$i)->applyFromArray($style_ContentNumeric);
    //         $sheet->setTitle('Last Doc');
            
    //         $return = [
    //                 'STATUS' => TRUE,
    //                 'Data' => $spreadsheet
    //             ];
    //     } catch (Exception $ex) {
    //         $return = [
    //             'STATUS' => FALSE,
    //             'Data' => $ex->getMessage()
    //         ];
    //     }
    //     $this->db->close();
    //     return $return;

    // }

}

/* End of file ElogModel.php */
/* Location: ./application/models/ElogModel.php */