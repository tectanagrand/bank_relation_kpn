<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class MaterialUploadModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function Save($Data, $Location) {
        try {
            $this->db->trans_begin();
            $result = FALSE;
            foreach ($Data['DATA'] as $value) {
                $dt = [
                    'FCCODE' => $value['CODEITEM'],
                    'FCNAME' => $value['NAMAITEM'],
                    'DESCRIPTION' => $value['DESCRIPTION'],
                    'PARTNO' => $value['PARTNO'],
                    'ITEMTYPE' => $value['ITEMTYPE'],
                    'EXTSYSTEM' => $value['EXTSYSTEM'],
                    'ISACTIVE' => 'TRUE'
                ];

                $cek = $this->db->select('*')
                                ->from($this->MATERIAL)
                                ->where([
                                    'FCCODE' => $dt['FCCODE'],
                                    'EXTSYSTEM' => $dt['EXTSYSTEM']
                                ])->get()->result();
                if (count($cek) > 0) {
                    throw new Exception('Some Data Already Exists !!!');
                }
                $dt['ID'] = $this->uuid->v4();
                $result = $this->db
                        ->set('LASTUPDATE', "SYSDATE", false)
                        ->set('LASTTIME', "TO_CHAR(SYSDATE, 'HH24:MI')", false)
                        ->set($dt)->insert($this->MATERIAL);

            }
            if ($result) {
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

}
