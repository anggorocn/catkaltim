<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class api_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
        // kuadran ,keterangan dri kuadran, Nama, nip, kekuatan, kelebihan, area pengembangan dan saran pengembangan
        // http://192.168.88.100/jombang/siapasn/api/Info_dispen_satker_json?reqToken=24ef14945de5cf9fcbade3429f7d5fc7
        // https://siapasn.jombangkab.go.id/api/Info_dispen_satker_json?reqToken=24ef14945de5cf9fcbade3429f7d5fc7

        $this->load->model('UserLoginLog');
        $this->load->model('base/Api');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");
        // $id = $this->input->get("id");
        $nip = $this->input->get("nip");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        
        // $reqSatuanKerjaId= $user_login_log->getToken(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // "24ef14945de5cf9fcbade3429f7d5fc7"
        if($reqToken == md5('valsixdispen'))
        {

            if($reqTahun == "")
                $reqTahun= "2022";
            // $statementsatuankerja.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";
            // echo $statementsatuankerja;exit();

            $set = new Api;
            $aColumns = array("pegawai_id","nama","nip_baru","KODE_KUADRAN","NAMA_KUADRAN");
            $sOrder = " ORDER BY COALESCE(X.NILAI_POTENSI,0) DESC, COALESCE(Y.NILAI_KOMPETENSI,0) DESC ";
            $set->selectByParamsApi(array(), -1, -1, $statement, $sOrder, $reqTahun);
          // echo "Sasasa"; exit;

            // echo $set->query;exit();

            $total = 0;
            while($set->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TMT")
                        $row[trim($aColumns[$i])] = getFormattedDateView($set->getField(trim($aColumns[$i])));
                    else
                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
                }
                $result[] = $row;

                $total++;
            }
            
            if($total == 0)
            {
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    $row[trim($aColumns[$i])] = "";
                }
                $result[] = $row;
            }
            
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => $total ,'result' => $result));
        }
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }

    }

    
    // insert new data to entitas
    function index_post() {
    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}