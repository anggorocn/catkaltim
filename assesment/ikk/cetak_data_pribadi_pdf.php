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


/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$urlLink= $data->urlConfig->main->urlLink;

//$html= file_get_contents($urlLink."ikk/tes3.php?reqId=".$reqId."&reqTahun=".$reqTahun."");
//$html.= "<pagebreak />";
// echo $urlLink;exit;
//echo $urlLink."ikk/hal1user.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."";exit;	

if($reqMode==CI){
    $html.= file_get_contents($urlLink."/pengaturan/cetak_critical_incident.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
else if($reqMode==QI){
    $html.= file_get_contents($urlLink."/pengaturan/cetak_q_inta.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
else if($reqMode==QK_Eselon){
    $html.= file_get_contents($urlLink."/pengaturan/cetak_q_kompetensi_eselon.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
else if($reqMode==QK_Pelaksana){
    $html.= file_get_contents($urlLink."/pengaturan/cetak_q_kompetensi_pelaksana.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
else{
    $html.= file_get_contents($urlLink."/pengaturan/cetak_data_pribadi.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."");
}
// echo $html;exit;
// $html.= "<pagebreak />";
// $html.= file_get_contents($urlLink."ikk/hal2ringkasan.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");
// $html.= "<pagebreak />";
// $html.= file_get_contents($urlLink."ikk/hal3ringkasan.php?reqId=".$reqId."&reqJadwalTesId=".$reqJadwalTesId."&reqTahun=".$reqTahun."");

$mpdf = new mPDF('',    // mode - default ''
 'FOLIO',    // format - A4, for example, default ''
 0,     // font size - default 0
 'cambria',    // default font family
 5,    // margin_left
 5,    // margin right
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

//echo $html;exit;
$mpdf->WriteHTML($html,2);

$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set= new CetakanPdf();
$set->selectByParamsAsesorCbi($statement, $reqTahun);
$set->firstRow();
$tempNamaAsesor= $set->getField("ASESOR_NAMA");
$tempNamaPegawai= $set->getField("PEGAWAI_NAMA");
//echo $tempNamaAsesor.'_'.$tempNamaPegawai.'.pdf';exit;
ob_end_clean();
$mpdf->Output($tempNamaAsesor.'_'.$tempNamaPegawai.'.pdf','I');
//exit;
?>