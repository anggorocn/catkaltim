<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/lib/MPDF60/mpdf.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasiPegawai.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqToleransi= httpFilterGet("reqToleransi");
$reqTipe= httpFilterGet("reqTipe");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
    $userLogin->retrieveUserInfoKhusus($reqId);
}



$statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$setCetak= new CetakanPdf();
$setCetak->selectByParamsJadwalFormula($statement);
// echo $setCetak->query; exit;
$setCetak->firstRow();
$reqFormulaId= $setCetak->getField("FORMULA_ID");
$reqTtdAsesor= $setCetak->getField("TTD_ASESOR");
$reqTtdPimpinan= $setCetak->getField("TTD_PIMPINAN");
$reqNipAsesor= $setCetak->getField("NIP_ASESOR");
if($reqNipAsesor==''){
    $reqNipAsesor=1;
}
$reqNipPimpinan= $setCetak->getField("NIP_PIMPINAN");
//$TanggalNow = getFormattedDate(date("Y-m-d"));
$TanggalNow= getFormattedDateTime($setCetak->getField('TTD_TANGGAL'), false);

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$urlLink= $data->urlConfig->main->urlLink;


//$html= file_get_contents($urlLink."ikk/tes3.php?reqId=".$reqId."&reqTahun=".$reqTahun."");
//$html.= "<pagebreak />";

$html.= file_get_contents($urlLink."ikk/cetak_admin_acara_temp.php?reqJadwalTesId=".$reqJadwalTesId."&reqPegawaiId=".$reqPegawaiId);

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
        <td align='center'>".$reqTtdAsesor."</td>
        <td align='center' style='width:20%'></td>
        <td align='center'>".$reqTtdPimpinan."</td>
    </tr>
    <tr>
        <td align='center'>".$reqNipAsesor."</td>
        <td align='center' style='width:20%'></td>
        <td align='center'>".$reqNipPimpinan."</td>
    </tr>
</table>";
// if($reqTipe=='CBI'||$reqTipe=='ITR'||$reqTipe=='LGD'||$reqTipe=='Psikotes'||$reqTipe=='PW'){
//     $html.= file_get_contents("http://".$urlLink."asesor/cetak_cbi.php?reqTipe=".$reqTipe."&reqJadwalTesId=".$reqJadwalTesId."&reqPegawaiId=".$reqPegawaiId);
// }
// else{
//     $html.= file_get_contents("http://".$urlLink."asesor/cetak_rekap.php??reqTipe=".$reqTipe."&reqJadwalTesId=".$reqJadwalTesId."&reqPegawaiId=".$reqPegawaiId);
// }
// echo $html;exit;
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
 'L');  // L - landscape, P - portrait



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

$mpdf->Output('Cetak Rekap -'. $reqJadwalPegawaiNip .'-'.$nama.').pdf','I');
//exit;
?>