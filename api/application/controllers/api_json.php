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

            $set = new Api;
            $sOrder = " ORDER BY COALESCE(X.NILAI_POTENSI,0) DESC, COALESCE(Y.NILAI_KOMPETENSI,0) DESC ";
            $aColumns = array("pegawai_id","nama","nip_baru","KODE_KUADRAN","NAMA_KUADRAN");
            $set->selectByParamsApi(array(), -1, -1, $statement, $sOrder, $reqTahun);

            // echo $set->query; exit;
            $total = 0;
            while($set->nextRow())
            {
                $result[$total]['pegawai_id'] = $set->getField('pegawai_id');
                $result[$total]['nama'] = $set->getField('nama');
                $result[$total]['nip'] = $set->getField('nip_baru');
                $result[$total]['kuadran'] = $set->getField('KODE_KUADRAN');
                $result[$total]['ket_kuadran'] = $set->getField('NAMA_KUADRAN');
                $result[$total]['tgl_tes'] = $set->getField('tanggal_tes');
                $setPenilaian = new Api;
                $setPenilaian->selectByParamsApiPenilaian(array(), -1, -1, "and pegawai_id =".$set->getField('pegawai_id')." and jadwal_tes_id=".$set->getField('jadwal_tes_id') );
                $totalKekuatan = 0;
                $totalKelemahan = 0;
                while($setPenilaian->nextRow())
                {   
                    if($setPenilaian->getField('tipe')=='profil_kekuatan'){
                        $result[$total]['kelebihan'][$totalKekuatan] = $setPenilaian->getField('keterangan');
                        $totalKekuatan++;
                    }
                    else if($setPenilaian->getField('tipe')=='profil_kelemahan'){
                        $result[$total]['kekurangan'][$totalKelemahan] = $setPenilaian->getField('keterangan');
                        $totalKelemahan++;
                    }
                    else if($setPenilaian->getField('tipe')=='profil_saran_pengembangan'){
                        $result[$total]['saran_pengembangan'] = $setPenilaian->getField('keterangan');
                    }
                }

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