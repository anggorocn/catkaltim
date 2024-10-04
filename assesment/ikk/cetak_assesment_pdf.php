
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/InventarisRuangan.php");

?>
<?php
//start report
$html = "
<div style='margin-top:18px;' id='header'>
	<p style='text-decoration:underline; text-align:center; width:950px;'><strong>LAPORAN HASIL KOMPETENSI (POTENSI DAN KOMPETENSI)</strong></p>
</div>
<div id='detil'> 
	<table style='margin-bottom:30px;'>
		<tr>
			<td align='center'>NOMOR</td>
			<td align='center'>ASPEK</td>
			<td align='center'>BOBOT</td>
			<td align='center'>NILAI JPM</td>
			<td align='center'>TOTAL</td>
		</tr>
	</table>
	"
	?>
	<?
	$html.="
	  
		</table>
	
	
</div> <!-- END DETIL -->
";
?>
<?

//==============================================================
//==============================================================
//==============================================================
include("../WEB/lib/MPDF60/mpdf.php");

$mpdf=new mPDF('c','A4'); 

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('../WEB/css/cetak_assesment.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('cetak_assesment.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================


?>