<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

$set = new Kelautan();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTrainingId= httpFilterGet("reqTrainingId");
$reqAtributId= httpFilterGet("reqAtributId");
$reqKeterangan = httpFilterRequest("reqKeterangan");
$reqId = httpFilterRequest("reqId");
$reqCari = httpFilterRequest("reqCari");
$reqSearch = httpFilterGet("reqSearch");

if($reqId == "")
	$statement='';
else
	//$statement .= " AND D.ID_TREE LIKE '".$reqId."%' ";
	$statement .= " AND S.KODE_UNKER LIKE '%".$reqId."'";

if($reqAtributId == "")
	$statement .='';
else
	$statement .= " AND D1.ATRIBUT_ID = '".$reqAtributId."' ";

if($reqTrainingId == "")
	$statement .='';
else
	$statement .= " AND F1.TRAINING_ID = '".$reqTrainingId."' ";

$field = array("NO", "NIP_LAMA", "NIP_BARU", "NAMA", "NAMA_GOL", "NAMA_JAB_STRUKTURAL", "SATKER");

$allRecord= $set->getCountByParamsMonitoringAnalisaPegawai(array(), $statement);
$set->selectByParamsMonitoringAnalisaPegawai(array(), -1, -1, $statement);
//echo $set->query;exit;

$set_detil= new Kelautan();
$set_detil->selectByParamsMonitoringAnalisaPegawai(array("D1.ATRIBUT_ID"=>$reqAtributId, "F1.TRAINING_ID"=>$reqTrainingId),-1,-1);
$set_detil->firstRow();
$tempNama= $set_detil->getField("ATRIBUT_NAMA").", Training: ".$set_detil->getField("TRAINING_NAMA");
unset($set_detil);

$field = array("NO", "NIP_LAMA", "NIP_BARU", "NAMA", "NAMA_GOL", "NAMA_JAB_STRUKTURAL", "SATKER");

?>
<?php
//start report
$html = "
<div style='margin-top:18px;' id='header'>
	<p style='text-decoration:underline; text-align:center; width:950px;'><strong>Laporan ".$tempNama."</strong></p>
</div>
<div id='detil'>
	<table style='margin-bottom:30px;'>
		<tr>
			<td align='center'>No.</td>
			<td align='center' style='width:100px;'>NIP</td>
			<td align='center' style='width:100px;'>NIP Baru</td>
			<td align='center' style='width:150px;'>Nama</td>
			<td align='center' style='width:100px;'>Pangkat</td>
			<td align='center' style='width:150px;'>Jabatan</td>
			<td align='center' style='width:250px;'>Unit Kerja</td>
		</tr>
	"
	?>
	<?
	$no=1;
		while($set->nextRow())
		{
	?>
	<?
	  $html.="        
		<tr>
			<td align='center'>".$no."</td>
	  ";
	  	for($i=1; $i<count($field); $i++)
	    {
			$html.="
			<td align='justify' style='padding-left:10px'>".$set->getField($field[$i])."</td>
		";
		}
		
		$html.="
		</tr>
		"
	?>
	<?		
		$no++;
		}
	?>
	<?
	$html.="
	</table>
</div> <!-- END DETIL -->
";
//echo $html;exit;
//==============================================================
//==============================================================
//==============================================================
include("../WEB/lib/MPDF60/mpdf.php");

$mpdf=new mPDF('c','A4'); 

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('../WEB/css/pdf_template.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

//$mpdf->Output('rekapitulasi_kehadiran_pdf.pdf','I');
//exit;
$isi= "diklat_analisa_kompetensi_pdf".$userLogin->UID.".pdf";
$mpdf->Output($isi,F);
//==============================================================
//==============================================================
//==============================================================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<script language="javascript">
window.onload = function(){
  document.getElementById("reqReload").click();
}
</script>
</head>

<body>
<a href="panduan_penggunaan.php?file=<?=$isi?>" target="_self" id="reqReload"></a>
</body>
</html>