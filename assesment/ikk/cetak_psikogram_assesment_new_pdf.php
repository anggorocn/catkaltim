<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/lib/MPDF60/mpdf.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqToleransi= httpFilterGet("reqToleransi");
$reqTipe= httpFilterGet("reqTipe");
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
    $userLogin->retrieveUserInfoKhusus($reqId);
}

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$urlLink= $data->urlConfig->main->urlLink;
// $urlLink= 'https://simace.kaltimbkd.info/assesment/';

// echo $urlLink; exit;

//$html= file_get_contents($urlLink."ikk/tes3.php?reqId=".$reqId."&reqTahun=".$reqTahun."");
//$html.= "<pagebreak />";

if($reqTipe=='sederhana'){
    $html.= file_get_contents($urlLink."ikk/sederhana_hal1.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
    $html.= "<pagebreak />";
    $html.= file_get_contents($urlLink."ikk/sederhana_hal2.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
    // $html.= file_get_contents($urlLink."ikk/sederhana_hal3.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
}
else if($reqTipe=='sedang'){
    $html.= file_get_contents($urlLink."ikk/sedang_hal1.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
    $html.= "<pagebreak />";
    $html.= file_get_contents($urlLink."ikk/sedang_hal2.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
}
else if($reqTipe=='kompleks'){
    $html.= file_get_contents($urlLink."ikk/kompleks_hal1.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
    $html.= "<pagebreak />";
    $html.= file_get_contents($urlLink."ikk/kompleks_hal2.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
}
else if($reqTipe=='baru'){
    $html.= file_get_contents($urlLink."ikk/baru_hal1.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
// echo $html;exit;
    $html.= "<pagebreak />";
    $html.= file_get_contents($urlLink."ikk/baru_hal2.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun);
}
else{
    $html.= file_get_contents($urlLink."ikk/hal1.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");
    $html.= "<pagebreak />";
    $html.= file_get_contents($urlLink."ikk/hal2.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");
}

$html.= "<pagebreak />";
$html.= file_get_contents($urlLink."ikk/hal4.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");
$html.= "<pagebreak />";
$html.= file_get_contents($urlLink."ikk/hal5.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");
$html.= "<pagebreak />";
$html.= file_get_contents($urlLink."ikk/hal6.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");
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

//echo $tempNamaAsesor.'_'.$tempNamaPegawai.'.pdf';exit;
$mpdf->Output($data['nama'].'_'.$reqNip.'.pdf','I');
//exit;
?>