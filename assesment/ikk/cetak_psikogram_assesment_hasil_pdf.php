<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/lib/MPDF60/mpdf.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqToleransi= httpFilterGet("reqToleransi");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$urlLink= $data->urlConfig->main->urlLink;

//$html= file_get_contents($urlLink."ikk/tes3.php?reqId=".$reqId."&reqTahun=".$reqTahun."");
//echo $html;exit;
//$html.= "<pagebreak />";
/*$html.= file_get_contents($urlLink."ikk/format_laporan_1.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
$html.= "<pagebreak />";
$html.= file_get_contents($urlLink."ikk/format_laporan_2.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
$html.= "<pagebreak />";
$html.= file_get_contents($urlLink."ikk/format_laporan_4.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
$html.= "<pagebreak />";
*/
$html.= file_get_contents($urlLink."ikk/format_laporan_9.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
//echo $html;
//exit;
$html.= "<pagebreak />";
$html.= file_get_contents($urlLink."ikk/format_laporan_10_1.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
$html.= "<pagebreak />";
//$html.= file_get_contents($urlLink."ikk/format_laporan_8.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
//$html.= "<pagebreak />";
//$html.= file_get_contents($urlLink."ikk/format_laporan_9.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
//$html.= "<pagebreak />";
$html.= file_get_contents($urlLink."ikk/format_laporan_10.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
$html.= "<pagebreak />";
$html.= file_get_contents($urlLink."ikk/format_laporan_11.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqToleransi=".$reqToleransi."");
//echo $html;exit;

$mpdf = new mPDF('',    // mode - default ''
 'FOLIO',    // format - A4, for example, default ''
 0,     // font size - default 0
 '',    // default font family
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
$stylesheet = file_get_contents('../WEB/css/cetakpsikogrammodif.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

//$isi= "tandaterima.pdf";
//$mpdf->Output($isi,F);

//echo $html;exit;
$mpdf->WriteHTML($html,2);
$mpdf->Output('psikogram_assesment_hasil.pdf','I');
//exit;
?>