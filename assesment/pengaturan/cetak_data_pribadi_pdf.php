<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/lib/MPDF60/mpdf.php");

$reqId= httpFilterGet("reqId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqMode= httpFilterGet("reqMode");
// flush();
// ob_flush();
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$urlLink= $data->urlConfig->main->urlLink;
$urlLink='https://simace.kaltimbkd.info/assesment/';
//$html= file_get_contents($urlLink."ikk/tes3.php?reqId=".$reqId."&reqTahun=".$reqTahun."");
//$html.= "<pagebreak />";
// echo $urlLink;exit;
//echo $urlLink."ikk/hal1user.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."";exit;	
// echo $urlLink;exit;

if($reqMode==CI){
    $html.= file_get_contents($urlLink."/pengaturan/cetak_critical_incident.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
else if($reqMode==QI){
    $html.= file_get_contents($urlLink."/pengaturan/cetak_q_inta.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
else if($reqMode==QK_Eselon){
    $html.= file_get_contents($urlLink."/pengaturan/cetak_q_kompetensi_eselon.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
else if($reqMode=="QK_Pelaksana"){
    $html.= file_get_contents($urlLink."/pengaturan/cetak_q_kompetensi_pelaksana.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
else{
    $html.= file_get_contents($urlLink."/pengaturan/cetak_data_pribadi.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}


// echo $urlLink;exit;
// $html.= "<pagebreak />";
// $html.= file_get_contents($urlLink."ikk/hal2ringkasan.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");
// $html.= "<pagebreak />";
// $html.= file_get_contents($urlLink."ikk/hal3ringkasan.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");

$mpdf = new mPDF('',    // mode - default ''
 'FOLIO',    // format - A4, for example, default ''
 0,     // font size - default 0
 'cambria',    // default font family
 0,    // margin_left
 0,    // margin right
 6,     // margin top
 6,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L');  // L - landscape, P - portrait

//$mpdf->SetDisplayMode('fullpage');

//$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('../WEB/css/cetakdata.css');
// echo $stylesheet;exit;
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

// $mpdf->fontdata = array(
//     "cambria" => array(
//         'R' => "Cambria.ttf",
//         'B' => "Cambria.ttf",
//     ),
// );

// $mpdf->SetFont('cambria');

//$isi= "tandaterima.pdf";
//$mpdf->Output($isi,F);

// $url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$reqNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
// $data = json_decode(file_get_contents($url), true);

$mpdf->WriteHTML($html,2);

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new CetakanPdf();
$set->selectByParamsDataPegawai($statement);

// echo $set->query; exit;
$set->firstRow();
// echo $html;exit;

$reqNip= $set->getField("NIP_baru");
$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$reqNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);
// echo $reqNip; exit;


// print_r($data); exit;

//echo $tempNamaAsesor.'_'.$tempNamaPegawai.'.pdf';exit;
$mpdf->Output('Laporan '.$reqMode.' ('.$data['nama'].'_'.$reqNip.').pdf','I');
// $mpdf->Output('Laporan '.$reqMode.' ('.$reqNip.').pdf','I');
//exit;
?>