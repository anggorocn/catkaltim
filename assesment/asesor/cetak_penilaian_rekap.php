<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/lib/MPDF60/mpdf.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasiPegawai.php");
include_once("../WEB/classes/base/PenilaianKompetensi.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalTes.php");

$tempAsesorId= $userLogin->userAsesorId;
$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqToleransi= httpFilterGet("reqToleransi");
$reqTipe= httpFilterGet("reqTipe");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqMode= httpFilterGet("reqMode");
$reqTanggalTes= httpFilterGet("reqTanggalTes");
// echo $reqMode; exit;
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
    $userLogin->retrieveUserInfoKhusus($reqId);
}

$set= new JadwalTes();
$set->selectByParams(array(),-1,-1,' and jadwal_tes_id ='.$reqJadwalTesId);
// echo $set->query; exit;
$set->firstRow();
$namaUjian=$set->getField("ACARA");
$tanggalTes=explode(' ',$set->getField("TANGGAL_TES"));
$setdetil= new JadwalAwalTesSimulasiPegawai();

$statement= " AND JA.JADWAL_TES_ID = ".$reqJadwalTesId." ";
$set= new JadwalAsesor();
if($reqMode==''){
    $set->selectByParamsDataAsesorPegawai($statement, $tempAsesorId);
}
else{
    $set->selectByParamsDataAsesorPegawaiSuper($statement, $tempAsesorId);
}
// echo $set->query;exit;

$statement= " AND JA.tanggal_tes = '".dateToPage($reqTanggalTes)." 00:00:00' ";
$set= new JadwalAsesor();
if($reqMode==''){
    $set->selectByParamsDataAsesorPegawai($statement, $tempAsesorId);
}
else{
    $set->selectByParamsDataAsesorPegawaiSuper($statement, $tempAsesorId);
}
// echo $set->query;exit;
$index_loop=0;
$total_muncul=0;
while($set->nextRow())
{

    $arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
    $arrAsesor[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
    $arrAsesor[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
    $arrAsesor[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
    $arrAsesor[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
    $arrAsesor[$index_loop]["ESELON"]= $set->getField("last_eselon_id");

    $statementTugas="  AND Jt.tanggal_tes = '".dateToPage($reqTanggalTes)." 00:00:00' and b.pegawai_id=".$set->getField("PEGAWAI_ID");
    if($reqMode==''){
      $statementTugas.= " and A.ASESOR_ID = ".$tempAsesorId;
    }
    $setTugas= new JadwalAsesor();
    $setTugas->selectByParamsTugas($statementTugas);    
    // echo $setTugas->query; exit;
    $tugas="";
    while($setTugas->nextRow())
    {
        if($tugas==''){
            $tandabaca="";
        }
        else{
            $tandabaca=", ";
        }
        if($reqMode==''){
            if($arrAsesor[$index_loop]["MUNCUL"]==0){
              if($setTugas->getField("kode")==$reqTipe){
                  $arrAsesor[$index_loop]["MUNCUL"]= 1;
              $total_muncul++;
              }
              else{
                echo $setTugas->getField("kode");
                  $arrAsesor[$index_loop]["MUNCUL"]= 0;
              }
            }
        }
        else
        {
            $arrAsesor[$index_loop]["MUNCUL"]= 1;
            $total_muncul++;
        }
    }

    $index_loop++;
}
$total_pegawai=$index_loop;

if($reqMode!=''){
  $total_muncul=$total_pegawai;
}
// print_r($arrAsesor); exit;


if($total_muncul==0){
$message = "Akun Anda Tidak Bisa Mengakses";
echo "<script type='text/javascript'>alert('$message'); window.close();</script>"; 
 // echo "<script></script>";
exit;
}

$sOrder='order by a.no_urut';
$setdetil->selectByParamsPegawai(array(), -1, -1, $statement2, $sOrder, $reqJadwalTesId);
$jumlah_data=0;
while($setdetil->nextRow()){
  $reqPegawaiIdBaru[]= $setdetil->getField('PEGAWAI_ID');
  $reqPegawaiNama[]= $setdetil->getField('PEGAWAI_NAMA');
  $reqPegawaiNip[]= $setdetil->getField('PEGAWAI_NIP');
  $reqPegawaiJabatan[]= $setdetil->getField('PEGAWAI_JAB_STRUKTURAL');
  $jumlah_data++;
}

$statement= " AND A.tanggal_tes = '".dateToPage($reqTanggalTes)." 00:00:00'";
$setCetak= new CetakanPdf();
$setCetak->selectByParamsJadwalFormula($statement);
// echo $setCetak->query; exit;
$setCetak->firstRow();
$reqFormulaId= $setCetak->getField("FORMULA_ID");
$reqTtdAsesor= $setCetak->getField("TTD_ASESOR");
$reqTtdPimpinan= $setCetak->getField("TTD_PIMPINAN");
$reqNipAsesor= $setCetak->getField("NIP_ASESOR");
$reqNipPimpinan= $setCetak->getField("NIP_PIMPINAN");
//$TanggalNow = getFormattedDate(date("Y-m-d"));
$TanggalNow= getFormattedDateTime($setCetak->getField('TTD_TANGGAL'), false);

$set= new Asesor();
$set->selectByParams(array(), -1,-1, " AND A.ASESOR_ID = ".$tempAsesorId);
$set->firstRow();
$tempAsesorTipeNama= $set->getField("TIPE_NAMA");
$tempAsesorNoSk= $set->getField("NO_SK");
$tempAsesorNama= $set->getField("NAMA");
$tempAsesorAlamat= $set->getField("ALAMAT");
$tempAsesorEmail= $set->getField("EMAIL");
$tempAsesorTelepon= $set->getField("TELEPON");


$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$urlLink= $data->urlConfig->main->urlLink;
$urlLink= 'https://simace.kaltimbkd.info/assesment/';

// echo $urlLink; exit;

//$html= file_get_contents($urlLink."ikk/tes3.php?reqId=".$reqId."&reqTahun=".$reqTahun."");
//$html.= "<pagebreak />";

$html.= file_get_contents($urlLink."asesor/cetak_rekap_keseluruhan.php?reqTipe=".$reqTipe."&reqJadwalTesId=".$reqJadwalTesId."&reqPegawaiId=".$reqPegawaiId."&tempAsesorId=".$tempAsesorId."&reqMode=".$reqMode."&reqTanggalTes=".$reqTanggalTes);
for($i=0;$i<$total_pegawai;$i++){
    if($reqMode==''){    
        if($arrAsesor[$i]["MUNCUL"]=='1'){
            $html.= "<pagebreak />";  
            if($reqTipe=='CBI'||$reqTipe=='ITR'||$reqTipe=='LGD'||$reqTipe=='Psikotes'||$reqTipe=='PW'||$reqTipe=='AK'||$reqTipe=='PTR'||$reqTipe=='PT'){
                $html.= file_get_contents($urlLink."asesor/cetak_cbi.php?reqTipe=".$reqTipe."&reqJadwalTesId=".$reqJadwalTesId."&reqPegawaiId=".$arrAsesor[$i]['PEGAWAI_ID']."&reqTanggalTes=".$reqTanggalTes);
            }
            else{
                $html.= file_get_contents($urlLink."asesor/cetak_rekap.php?reqTipe=".$reqTipe."&reqJadwalTesId=".$reqJadwalTesId."&reqPegawaiId=".$arrAsesor[$i]['PEGAWAI_ID']."&reqTanggalTes=".$reqTanggalTes);
            }
        }
    }
    else{
    $html.= "<pagebreak />";
    $html.= file_get_contents($urlLink."asesor/cetak_rekap.php??reqTipe=".$reqTipe."&reqJadwalTesId=".$reqJadwalTesId."&reqPegawaiId=".$reqPegawaiId."&reqTanggalTes=".$reqTanggalTes);
    }
}
// echo $html;exit;

$html.= "
<br>
<br>
<br>
 <table style='font-size: 10pt; width: 100%; padding-left: 25px'>
    <tr>
        <td align='center' style='width:40%'></td>
        <td align='center' style='width:20%'></td>
        <td align='center'>Samarinda,    ".$TanggalNow."</td>
    </tr>
    <tr>
        <td align='center'>Administrator Kegiatan</td>
        <td align='center' style='width:20%'></td>
        <td align='center'>Asesor </td>
    </tr>
    <tr>
        <td align='center'></td>
        <td align='center' style='width:20%'></td>
        <td align='center'>Penilaian Kompetensi</td>
    </tr>
    <tr>
        <td align='center' style='height: 80px'></td>
        <td align='center' style='width:20%'></td>
        <td align='center'></td>
    </tr>
    <tr>
        <td align='center'><u>".$reqTtdAsesor."</u></td>
        <td align='center' style='width:20%'></td>
        <td align='center'><u>".$tempAsesorNama."</u></td>
    </tr>
    <tr>
        <td align='center'>".$reqNipAsesor."</td>
        <td align='center' style='width:20%'></td>
        <td align='center'>".$tempAsesorNoSk."</td>
    </tr>
</table>";
$mpdf = new mPDF('',    // mode - default ''
 'FOLIO',    // format - A4, for example, default ''
 0,     // font size - default 0
 'cambria',    // default font family
 5,    // margin_left
 5,    // margin right
 15,     // margin top
 15,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L'); 

//$mpdf->SetDisplayMode('fullpage');

//$mpdf->list_indent_first_level = 0;   // 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('../WEB/css/cetaknew.css');
// echo $stylesheet;exit;
$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

// $mpdf->fontdata = array(
//     "cambria" => array(
//         'R' => "Cambria.ttf",
//         'B' => "Cambria.ttf",
//     ),
// );

// $mpdf->SetFont('cambria');

//$isi= "tandaterima.pdf";
//$mpdf->Output($isi,F);

// echo $html;exit;
$mpdf->WriteHTML($html,2);

// $statement= " AND A.PEGAWAI_ID = ".$reqId;
// $set= new CetakanPdf();
// $set->selectByParamsAsesorCbi($statement, $reqTahun);
// $set->firstRow();
// $tempNamaAsesor= $set->getField("ASESOR_NAMA");
// $tempNamaPegawai= $set->getField("PEGAWAI_NAMA");
// //echo $tempNamaAsesor.'_'.$tempNamaPegawai.'.pdf';exit;
// $mpdf->Output($tempNamaAsesor.'_'.$tempNamaPegawai.'.pdf','I');

// $mpdf->WriteHTML($html,2);

$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set= new CetakanPdf();
$set->selectByParamsDataPegawai($statement);

// echo $set->query; exit;
$set->firstRow();
// echo $html;exit;

$reqNip= $set->getField("NIP_baru");
// echo $reqNip; exit;

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$reqNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);
// print_r($data); exit;

$statement= "
  AND A.PEGAWAI_ID = ".$reqPegawaiId."
  AND EXISTS
  (
    SELECT 1
    FROM
    (
      SELECT A.JADWAL_TES_ID 
      FROM jadwal_asesor A
      WHERE JADWAL_TES_ID = ".$reqJadwalTesId." GROUP BY A.JADWAL_TES_ID
    ) X WHERE C.JADWAL_TES_ID = X.JADWAL_TES_ID
  )"; 

$set= new JadwalPegawai();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit();
$reqJadwalPegawaiNama= $set->getField("PEGAWAI_NAMA");
$reqJadwalLinkFoto= $set->getField("link_foto");
$reqJadwalPegawaiNip= $set->getField("PEGAWAI_NIP");
$reqJadwalPegawaiGol= $set->getField("PEGAWAI_GOL");
$reqJadwalPegawaiEselon= $set->getField("PEGAWAI_ESELON");
$reqJadwalPegawaiJabatan= $set->getField("PEGAWAI_JAB_STRUKTURAL");
$reqJadwalPegawaiLastEselon= $set->getField("LAST_ESELON_ID");
// echo $reqJadwalPegawaiNip; exit;

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$reqJadwalPegawaiNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);

if($data['glr_depan']!='-'){
    $gelardepan= $data['glr_depan']; 
} 

if($data['glr_belakang']!='-'){
    $gelarBelakang=$data['glr_belakang']; 
}

$nama=$gelardepan." ".$data['nama']." ". $gelarBelakang;

$mpdf->Output('PENILAIAN '.$reqTipe.'('. $tempAsesorNama .'/'. $namaUjian .'/'.$tanggalTes[0].').pdf','I');
//exit;
?>