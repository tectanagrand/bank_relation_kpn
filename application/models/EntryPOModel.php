<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class EntryPOModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    protected $table = 'CF_TRANSACTION';
    protected $tablePD = 'CF_TRANSACTION_DET';
    protected $tableCom = 'COMPANY';
    protected $tableBU = 'BUSINESSUNIT';
    protected $tableDpt = 'DEPARTMENT';
    protected $tableMtr = 'MATERIAL';
    protected $tableV = 'SUPPLIER';
    protected $USER_DEPART = 'USER_DEPART';

    /* For Index DataTable */
    public function ShowData($param) {
        $Lenght = $param['length'];
        $Start = $param['start'];
        $Columns = $param['columns'];
        $Search = $param['search'];
        $Order = $param['order'];
        $OrderField = $Columns[$Order[0]["column"]]["data"];

        $DEPARTMENT = "%".$param["DEPARTMENT"]."%";
        $USERNAME = $param["USERNAME"];

        $SQL = "(SELECT CFT.*, C.COMPANYCODE, C.COMPANYNAME, BU.FCCODE AS BUSINESSUNITCODE, BU.FCNAME AS BUSINESSUNITNAME, S.FCNAME AS VENDORNAME
                    FROM (SELECT CFT.ID, CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCNUMBER, CFT.DOCREF, CFT.DOCDATE, CFT.VENDOR,
                                    (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF, CFT.REMARK, CFT.AMOUNT_PPH, CFT.AMOUNT_INCLUDE_VAT, CFT.ISACTIVE
                                FROM CF_TRANSACTION CFT
                            WHERE (CFT.DOCTYPE != 'INV' 
                                AND CFT.DOCTYPE != 'INV_AR')) CFT
                    INNER JOIN COMPANY C
                            ON C.ID = CFT.COMPANY
                    INNER JOIN BUSINESSUNIT BU
                            ON BU.COMPANY = CFT.COMPANY
                            AND BU.ID = CFT.BUSINESSUNIT
                        LEFT JOIN SUPPLIER S
                            ON S.ID = CFT.VENDOR
                    INNER JOIN USER_DEPART UD
                            ON UD.DEPARTMENT = CFT.DEPARTMENT
                            AND UD.FCCODE = '$USERNAME'
                    WHERE CFT.DEPARTMENT LIKE '%$DEPARTMENT%')";

        $result = $this->db->select(["*"])->from("$SQL FL");

        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $result = $result->or_like("UPPER(FL." . $values["data"] . ")", $Search['value'], 'both');
                }
            }
        }

        if ($OrderField == "" || $OrderField == NULL) {
            $result = $result->order_by('FL.DEPARTMENT, FL.COMPANY, FL.DOCDATE DESC, FL.DOCNUMBER');
        } else {
            $result = $result->order_by($OrderField, $Order[0]["dir"]);
        }
        $result = $result->limit($Lenght, $Start)->get()->result();

        

        $CountFil = $this->db->select(["COUNT(*) AS JML"])->from("$SQL FL");
        if ($Search["regex"] == 'true') {
            $Search['value'] = strtoupper($Search['value']);
            foreach ($Columns as $values) {
                if ($values["data"] != NULL && $values["data"] != '') {
                    $CountFil = $CountFil->or_like("UPPER(FL." . $values["data"] . ")", $Search['value'], 'both');
                }
            }
        }
        $CountFil = $CountFil->get()->result();

        $CountAll = "SELECT COUNT(*) AS JML
                        FROM (SELECT CFT.ID, CFT.DEPARTMENT, CFT.COMPANY, CFT.BUSINESSUNIT, CFT.DOCTYPE, CFT.DOCNUMBER, CFT.DOCREF, CFT.DOCDATE, CFT.VENDOR,
                                        (CFT.AMOUNT_INCLUDE_VAT - CFT.AMOUNT_PPH) AS AMOUNTDOCUMNET, CFT.UPLOAD_REF, CFT.REMARK, CFT.AMOUNT_PPH, CFT.AMOUNT_INCLUDE_VAT, CFT.ISACTIVE
                                    FROM CF_TRANSACTION CFT
                                WHERE (CFT.DOCTYPE != 'INV' 
                                    AND CFT.DOCTYPE != 'INV_AR')) CFT
                        INNER JOIN COMPANY C
                                ON C.ID = CFT.COMPANY
                        INNER JOIN BUSINESSUNIT BU
                                ON BU.COMPANY = CFT.COMPANY
                                AND BU.ID = CFT.BUSINESSUNIT
                            LEFT JOIN SUPPLIER S
                                ON S.ID = CFT.VENDOR
                        INNER JOIN USER_DEPART UD
                                ON UD.DEPARTMENT = CFT.DEPARTMENT
                                AND UD.FCCODE = '$USERNAME'
                        WHERE CFT.DEPARTMENT LIKE '%$DEPARTMENT%'";

        $CountAll = $this->db->query($CountAll)->result();

        $this->db->close();

        $return = [
            "data" => $result,
            "recordsTotal" => $CountAll[0]->JML,
            "recordsFiltered" => $CountFil[0]->JML
        ];

        return $return;
    }

    /* For View Details PO DataTable */
    public function ShowDetailPO($ID) {
        $this->fillable = ['PD.*', 'Mtr.FCNAME as FCNAMEMtr'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->tablePD PD")
                        ->join("$this->tableMtr Mtr", 'Mtr.ID = PD.MATERIAL', 'left')
                        ->where(['PD.ID' => $ID])
                        ->order_by('PD.LASTUPDATE')->get()->result();
        $this->db->close();

        return $result;
    }

    /* For View Invoice DataTable */
    public function DetailInvoiceList($docRef) {
        // $SQL = "(SELECT CFT.ID, CFT.DOCNUMBER AS INV_DOC_NUMBER, to_char(CFT.DOCDATE,'MM/DD/YYYY') AS INV_DOC_DATE2, CFT.REMARK AS INV_REMARK, 
        //                 DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 100), CFT.AMOUNT_INCLUDE_VAT) AS INV_AMOUNT,
        //                 DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 10), CFT.AMOUNT_INCLUDE_VAT * 10/100) AS INV_AMOUNT_VAT,
        //                 ROUND(
        //                         DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 100), CFT.AMOUNT_INCLUDE_VAT) +
        //                         (
        //                             DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 10), CFT.AMOUNT_INCLUDE_VAT * 10/100)
        //                         )
        //                 ) AS INV_AMOUNT_AFTER_VAT
        //           FROM $this->table CFT
        //          INNER JOIN $this->table CFT2 
        //                 ON CFT2.DOCNUMBER = CFT.DOCREF
        //                 AND CFT.DOCREF = '$docRef'
        //          WHERE CFT.DOCTYPE = 'INV')";
        $SQL = "(SELECT CFT.ID, CFT.DOCNUMBER AS INV_DOC_NUMBER, to_char(CFT.DOCDATE,'MM/DD/YYYY') AS INV_DOC_DATE2, CFT.REMARK AS INV_REMARK, CFT.AMOUNT_INCLUDE_VAT AS INV_AMOUNT, CFT.AMOUNT_PPH AS INV_AMOUNT_PPH, CFT.FAKTUR_PAJAK AS INV_FAKTUR_PAJAK, CFT.VAT
                  FROM $this->table CFT
                 INNER JOIN $this->table CFT2 
                        ON CFT2.DOCNUMBER = CFT.DOCREF
                        AND CFT.DOCREF = '$docRef'
                 WHERE CFT.DOCTYPE = 'INV')";
        
        $result = $this->db->select(["*"])->from($SQL)->get()->result_array();
        $this->db->close();
        
        return $result;
    }

    /* For Edit Form */
    public function GetData($ID) {
        $this->fillable = ['CFT.*', "to_char(CFT.DOCDATE,'MM/DD/YYYY') AS INV_DOC_DATE2", 'VDR.FCNAME AS VENDORNAME'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->table CFT")
                        ->join("$this->tableV VDR", 'VDR.ID = CFT.VENDOR', 'left')
                        ->where(['CFT.ID' => $ID])->get()->row();
        $this->db->close();
        return $result;
    }

    /* Get Data Invoice */
    public function GetDataInvoice($ID) {
        // $dateFormat = "'MM/DD/YYYY'";

        // $SQL = "(SELECT CFT.ID, CFT.DOCNUMBER AS INV_DOC_NUMBER, to_char(CFT.DOCDATE,'MM/DD/YYYY') AS INV_DOC_DATE2, CFT.REMARK AS INV_REMARK, 
        //                 DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 100), CFT.AMOUNT_INCLUDE_VAT) AS INV_AMOUNT,
        //                 DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 10), CFT.AMOUNT_INCLUDE_VAT * 10/100) AS INV_AMOUNT_VAT,
        //                 ROUND(
        //                         DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 100), CFT.AMOUNT_INCLUDE_VAT) +
        //                         (
        //                             DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 10), CFT.AMOUNT_INCLUDE_VAT * 10/100)
        //                         )
        //                 ) AS INV_AMOUNT_AFTER_VAT
        //           FROM $this->table CFT
        //          INNER JOIN $this->table CFT2 
        //                 ON CFT2.DOCNUMBER = CFT.DOCREF
        //                 AND CFT2.ID = '$ID'
        //          WHERE CFT.DOCTYPE = 'INV')";

        $SQL = "(SELECT CFT.ID, CFT.DOCNUMBER AS INV_DOC_NUMBER, to_char(CFT.DOCDATE,'MM/DD/YYYY') AS INV_DOC_DATE2, CFT.REMARK AS INV_REMARK, CFT.AMOUNT_INCLUDE_VAT AS INV_AMOUNT, CFT.AMOUNT_PPH AS INV_AMOUNT_PPH, CFT.FAKTUR_PAJAK AS INV_FAKTUR_PAJAK
                  FROM $this->table CFT
                 INNER JOIN $this->table CFT2 
                        ON CFT2.DOCNUMBER = CFT.DOCREF
                        AND CFT2.ID = '$ID'
                 WHERE CFT.DOCTYPE = 'INV')";
        
        $result = $this->db->select(["*"])->from($SQL)->get()->result_array();

        // $this->fillable = ['CFT.DOCNUMBER AS INV_DOC_NUMBER', 'CFT.ID', "to_char(CFT.DOCDATE,$dateFormat) AS INV_DOC_DATE2", 'CFT.REMARK AS INV_REMARK', 'DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 100), CFT.AMOUNT_INCLUDE_VAT) AS INV_AMOUNT', 'DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 10), CFT.AMOUNT_INCLUDE_VAT * 10/100) AS INV_AMOUNT_VAT', 'ROUND(CFT.AMOUNT_INCLUDE_VAT + (DECODE(CFT2.VAT, 1, (CFT.AMOUNT_INCLUDE_VAT / 110 * 10), CFT.AMOUNT_INCLUDE_VAT * 10/100))) AS INV_AMOUNT_AFTER_VAT'];
        // $result = $this->db->select($this->fillable)
        //                 ->from("$this->table CFT")
        //                 ->join("$this->table CFT2", "CFT2.DOCNUMBER = CFT.DOCREF AND CFT2.ID = $ID", 'inner')
        //                 ->where(['CFT.DOCTYPE' => 'INV'])->get()->result_array();
        $this->db->close();
        return $result;
    }

    public function GetPODetails($ID) {
        $this->fillable = ['M.ID', 'M.FCNAME', 'PD.AMOUNT_INCLUDE_VAT', 'PD.AMOUNT_PPH', 'M.FCCODE', 'PD.REMARKS'];
        $result = $this->db->select($this->fillable)
                        ->from("$this->tablePD PD")
                        ->join("$this->tableMtr M", 'M.ID = PD.MATERIAL', 'left')
                        ->where(['PD.ID' => $ID])->get()->result_array();
        $this->db->close();
        return $result;
    }

    /* For Save */
    public function Save($Data, $Location) {
        parse_str($Data['form_data'], $parseSerialize);

        try {
            $this->db->trans_begin();

            $result = FALSE;

            $dt = [
                'COMPANY' => $parseSerialize['COMPANY'],
                'BUSINESSUNIT' => $parseSerialize['BUSINESSUNIT'],
                'DEPARTMENT' => $parseSerialize['DEPARTMENT'],
                'DOCNUMBER' => $parseSerialize['DOCNUMBER'],
                'VENDOR' => $parseSerialize['VENDOR'],
                'REMARK' => (isset($parseSerialize['REMARK'])) ? $parseSerialize['REMARK'] : NULL,
                'AMOUNT_INCLUDE_VAT' => str_replace(",", "", $parseSerialize['AMOUNT_INCLUDE_VAT']),
                'AMOUNT_PPH' => (isset($parseSerialize['AMOUNT_PPH']) && $parseSerialize['AMOUNT_PPH'] != '') ? str_replace(",", "", $parseSerialize['AMOUNT_PPH']) : 0,
                'UPLOAD_REF' => (isset($parseSerialize['UPLOAD_REF'])) ? $parseSerialize['UPLOAD_REF'] : NULL,
                'EXTSYS' => (isset($parseSerialize['EXTSYSTEM'])) ? $parseSerialize['EXTSYSTEM'] : NULL,
                'FAKTUR_PAJAK' => (isset($parseSerialize['PPH_FAKTUR_PAJAK'])) ? $parseSerialize['PPH_FAKTUR_PAJAK'] : NULL,
                'DOCTYPE' => $parseSerialize['DOCTYPE'],
                'TOTAL_BAYAR' => str_replace(",", "", $parseSerialize['TOTAL_BAYAR']),
                'VAT' => $parseSerialize['VAT'],
                'ISACTIVE' => 'TRUE',
                'FCEDIT' => $parseSerialize['USERNAME'],
                'FCIP' => $Location
            ];
            $res = $this->db
                    ->set('LASTUPDATE', "SYSDATE", false)
                    ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);

            if ($Data['docDatePO'] == NULL || $Data['docDatePO'] == '') {
                $res = $res->set('DUEDATE', 'NULL', false);
            } else {
                $res = $res->set('DUEDATE', "TO_DATE('" . $Data['docDatePO'] . "','mm/dd/yyyy')", false);
            }
            
            if ($parseSerialize['ACTION'] == 'ADD') {
                $res = $res->set('DOCDATE', "SYSDATE", false);
                $parseSerialize['ID'] = $this->uuid->v4();
                $dt['ID'] = $parseSerialize['ID'];
                $dt['FCENTRY'] = $parseSerialize['USERNAME'];
                $res = $res->set($dt)->insert($this->table);
            } else if ($parseSerialize['ACTION'] == 'EDIT') {

                    $res = $res->set($dt)
                        ->where(['ID' => $parseSerialize['ID']])
                        ->update($this->table);
                
            }
            
            $result2 = FALSE;
            if ($res) {
                $result = TRUE;
                $DeletePODetail = $this->db->delete($this->tablePD, ['ID' => $parseSerialize['ID']]);

                if (isset($parseSerialize['DET_MATERIAL']) && count($parseSerialize['DET_MATERIAL']) > 0) {
                    foreach ($parseSerialize['DET_MATERIAL'] as $key => $value) {
                        if ($value && $parseSerialize['DET_AMOUNT_INCLUDE_VAT'][$key]) {
                            $datadetail = [
                                'ID' => $parseSerialize['ID'],
                                'MATERIAL' => $value,
                                'AMOUNT_INCLUDE_VAT' => str_replace(",", "", $parseSerialize['DET_AMOUNT_INCLUDE_VAT'][$key]),
                                'REMARKS' => $parseSerialize['DET_REMARKS'][$key],
                                // 'AMOUNT_PPH' => (isset($parseSerialize['DET_AMOUNT_PPH'][$key])) ? str_replace(",", "", $parseSerialize['DET_AMOUNT_PPH'][$key]) : 0,
                                'ISACTIVE' => 'TRUE',
                                'FCENTRY' => $parseSerialize['USERNAME'],
                                'FCEDIT' => $parseSerialize['USERNAME'],
                                'FCIP' => $Location
                            ];
                            $result2 = $this->db->set('LASTUPDATE', "SYSDATE", false)->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                                            ->set($datadetail)->insert($this->tablePD);
                        }
                    }
                } else {
                    $result2 = TRUE;
                }
            } else {
                throw new Exception("Data Save Failed !!");
            }

            $result3 = FALSE;
            if ($result && $result2) {
                $DeleteInvoice = $this->db->delete($this->table, ['DOCREF' => $parseSerialize['DOCNUMBER']]);

                if (isset($Data['DtInvoice']) && count($Data['DtInvoice']) > 0) {
                    foreach ($Data['DtInvoice'] as $key => $value) {
                        if ($value) {
                            
                            if ($parseSerialize['ACTION'] == 'ADD') {
                                $SQL = "SELECT * FROM $this->table WHERE COMPANY = ? AND BUSINESSUNIT = ? AND DOCNUMBER = ?";
                                $Cek = $this->db->query($SQL, [$parseSerialize['COMPANY'], $parseSerialize['BUSINESSUNIT'], $value['INV_DOC_NUMBER']]);
                                
                                if ($Cek->num_rows() > 0) {
                                    throw new Exception("Doc number " .$value['INV_DOC_NUMBER']. " already exist!");
                                }
                            } else if ($parseSerialize['ACTION'] == 'EDIT') {
                                $SQL = "SELECT * FROM $this->table WHERE COMPANY = ? AND BUSINESSUNIT = ? AND DOCNUMBER = ? AND ID != ?";
                                $Cek = $this->db->query($SQL, [$parseSerialize['COMPANY'], $parseSerialize['BUSINESSUNIT'], $value['INV_DOC_NUMBER'], $value['ID']]);
                                
                                if ($Cek->num_rows() > 0) {
                                    throw new Exception("Doc number " .$value['INV_DOC_NUMBER']. " already exist!");
                                }
                            }

                            $datadetail = [
                                'ID' => $this->uuid->v4(),
                                'COMPANY' => $parseSerialize['COMPANY'],
                                'BUSINESSUNIT' => $parseSerialize['BUSINESSUNIT'],
                                'DEPARTMENT' => $parseSerialize['DEPARTMENT'],
                                'VENDOR' => $parseSerialize['VENDOR'],
                                'UPLOAD_REF' => (isset($parseSerialize['UPLOAD_REF'])) ? $parseSerialize['UPLOAD_REF'] : NULL,
                                'EXTSYS' => (isset($parseSerialize['EXTSYSTEM'])) ? $parseSerialize['EXTSYSTEM'] : NULL,
                                'VAT' => $parseSerialize['VAT'],
                                'ISACTIVE' => 'TRUE',
                                'FCEDIT' => $parseSerialize['USERNAME'],
                                'FCIP' => $Location,
                                
                                'FCENTRY' => $parseSerialize['USERNAME'],
                                'DOCTYPE' => 'INV',
                                'DOCREF' => $parseSerialize['DOCNUMBER'],
                                'DOCNUMBER' => $value['INV_DOC_NUMBER'],
                                'REMARK' => (isset($value['INV_REMARK'])) ? $value['INV_REMARK'] : NULL,
                                'AMOUNT_INCLUDE_VAT' => $value['INV_AMOUNT'],
                                'AMOUNT_PPH' => $value['INV_AMOUNT_PPH'],
                                'FAKTUR_PAJAK' => $value['INV_FAKTUR_PAJAK'],
                                // 'AMOUNT_INCLUDE_VAT' => ($parseSerialize['VAT']) ? $value['INV_AMOUNT_AFTER_VAT'] : $value['INV_AMOUNT'],
                                // 'AMOUNT_PPH' => (isset($parseSerialize['AMOUNT_PPH'])) ? str_replace(",", "", $parseSerialize['AMOUNT_PPH']) : 0,

                            ];

                            $res3 = $this->db
                                ->set('LASTUPDATE', "SYSDATE", false)
                                ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false);

                            if ($value['INV_DOC_DATE2'] == NULL || $value['INV_DOC_DATE2'] == '') {
                                $res3 = $res3->set('DOCDATE', 'NULL', false);
                            } else {
                                $res3 = $res3->set('DOCDATE', "TO_DATE('" . $value['INV_DOC_DATE2'] . "','mm/dd/yyyy')", false);
                            }

                            $result3 = $res3->set($datadetail)->insert($this->table);
                        }
                    }
                } else {
                    $result2 = TRUE;
                }
            } else {
                throw new Exception('Data Save Failed !!');
            }

            if ($result && $result2) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Saved !!'
                ];
            } else {
                throw new Exception('Data Save Failed !!');
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

    /* For Delete */
    public function Delete($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            $DeletePODetail = $this->db->delete($this->tablePD, ['ID' => $Data['ID']]);
            $DeletePOHeader = $this->db->delete($this->table, ['ID' => $Data['ID']]);
            $DeletePOInvoice = $this->db->delete($this->table, ['DOCREF' => $Data['DOCNUMBER']]);

            if ($DeletePODetail && $DeletePOHeader) {
                $result = TRUE;
            }
            if ($result) {
                $this->db->trans_commit();
                $return = [
                    'STATUS' => TRUE,
                    'MESSAGE' => 'Data has been Successfully Deleted !!'
                ];
            } else {
                throw new Exception('Deleted Failed !!');
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
}