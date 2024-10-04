<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");

$reqId= httpFilterGet("reqId");

$arrTahun="";
$index_tahun= 0;
$set= new Penilaian();
$set->selectByParamsTahunPenilaian();
//echo $set->query;exit;
while($set->nextRow())
{
	$arrTahun[$index_tahun]["TAHUN"] = $set->getField("TAHUN");
	$index_tahun++;
}
//print_r($arrTahun);exit;

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

for($index_loop=0; $index_loop < $index_tahun; $index_loop++)
{
	$html= "
	<div style='margin-top:18px;' id='header'>
		<p style='text-decoration:underline; text-align:center; width:950px;'><strong>LAPORAN REKAPITULASI HASIL PENILAIAN POTENSI TAHUN ".$arrTahun[$index_loop]["TAHUN"]."</strong></p>
	</div>
	<div id='detil'>
		<table style='margin-bottom:30px;'>
			<tr>
				<td align='center'>RANKING</td>
				<td align='center'>NAMA</td>
				<td align='center'>JABATAN</td>
				<td align='center'>JPM</td>
				<td align='center'>IKK</td>
			</tr>
	";
		$no=1;
		
		if($reqId == "" || $reqId == "1")
			$statement='';
		else
			$statement= setAndKondisi($reqId, "A.KODE_UNKER");
		
		$set_detil= new Penilaian();
		$statement.= " AND D.ASPEK_ID = 1 AND YEAR(D.TANGGAL_TES) = ".$arrTahun[$index_loop]["TAHUN"];
		$set_detil->selectByParamsPersonalJkmIkk($statement);
		//echo $set_detil->query;exit;
		while($set_detil->nextRow())
		{
		$html.="
		<tr>
			<td>".$no."</td>
			<td>".$set_detil->getField("NAMA")."</td>
			<td>".$set_detil->getField("NAMA_JAB_STRUKTURAL")."</td>
			<td>".$set_detil->getField("NILAI_JPM_PERSEN")."</td>
			<td>".$set_detil->getField("NILAI_IKK_PERSEN")."</td>
		</tr>
		";
		$no++;
		}
		
	$html.="
		</table>
		";
		$html.="
	</div>
	";

	if($index_loop > 0)
		$mpdf->AddPage();
	
	$mpdf->WriteHTML($html,2);
}


$mpdf->Output('cetak_assesment.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================


?>