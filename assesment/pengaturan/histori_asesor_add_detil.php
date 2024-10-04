<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$set = new Asesor();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqAsesorId= httpFilterGet("reqAsesorId");
$reqTanggalTes= httpFilterGet("reqTanggalTes");
$reqPenggalianId= httpFilterGet("reqPenggalianId");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

/* DATA VIEW */

$statement= " AND C.ASESOR_ID = ".$reqAsesorId." AND DATE_FORMAT(E.TANGGAL_TES, '%d-%m-%Y') = '".dateToPageCheck($reqTanggalTes)."' AND A.PENGGALIAN_ID = ".$reqPenggalianId;
$set= new Asesor();
$set->selectByParamsHistori(array(), -1, -1, $statement);
//echo $set->query;exit;
$set->firstRow();
$tempKode= $set->getField("KODE");
$tempMetode= $set->getField("METODE");
$tempTanggalTes= $set->getField("TANGGAL_TES");

$index_loop= 0;
$arrAsesor="";

$statement= " AND B.ASESOR_ID = ".$reqAsesorId." AND C.JADWAL_TES_ID = ".$reqJadwalTesId." AND D1.PENGGALIAN_ID = ".$reqPenggalianId;
$set= new Asesor();
$set->selectByParamsHistoriTanggal($statement);
//echo $set->query;exit;
if($set->errorMsg == "")
{
	while($set->nextRow())
	{
		$arrAsesor[$index_loop]["KODE"]= $set->getField("KODE");	
		$arrAsesor[$index_loop]["METODE"]= $set->getField("METODE");
		$arrAsesor[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
		$arrAsesor[$index_loop]["NAMA"]= $set->getField("NAMA");
		$arrAsesor[$index_loop]["NILAI"]= $set->getField("NILAI");
		$arrAsesor[$index_loop]["PEGAWAI_KETERANGAN"]= $set->getField("PEGAWAI_KETERANGAN");
		$arrAsesor[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
		$index_loop++;
	}
}
$jumlah_asesor= $index_loop;
//print_r($arrAsesor);exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
	<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../WEB/js/globalfunction.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script>
    
    <style>
		/* UNTUK TABLE GRADIENT STYLE*/
		.gradient-style th {
		font-size: 12px;
		font-weight:400;
		background:#b9c9fe url(images/gradhead.png) repeat-x;
		border-top:2px solid #d3ddff;
		border-bottom:1px solid #fff;
		color:#039;
		padding:8px;
		}
		
		.gradient-style td {
		font-size: 12px;
		border-bottom:1px solid #fff;
		color:#669;
		border-top:1px solid #fff;
		background:#e8edff url(images/gradback.png) repeat-x;
		padding:8px;
		}
		
		.gradient-style tfoot tr td {
		background:#e8edff;
		font-size: 14px;
		color:#99c;
		}
		
		.gradient-style tbody tr:hover td {
		background:#d0dafd url(images/gradhover.png) repeat-x;
		color:#339;
		}
		
		.gradient-style {
		font-family: 'Open SansRegular';
		font-size: 14px;
		width:480px;
		text-align:left;
		border-collapse:collapse;
		margin:0px 0px 0px 10px;
		}
	</style>
    
	<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
</head>
<body class="bg-form" style="overflow-x:scroll;">
	<?
	if($jumlah_asesor == 0){}
	else
	{
    ?>
    <div id="header-tna-detil">(<?=$tempKode?>) <?=$tempMetode?> <span><?=getFormattedDate($tempTanggalTes)?></span></div>
    <div id="konten">
    <table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
        <thead class="altrowstable">
            <tr>
              <th style="display:none; width:50px">Kode</th>
              <th style="display:none; width:200px">Metode</th>
              <th>Indikator yg dinilai</th>
              <th style="display:none; width:20px">Nilai</th>
              <th style="width:300px">Catatan</th>
            </tr>
       </thead>
       <tbody class="example altrowstable" id="alternatecolor"> 
		<? 
		$tempNamaKondisi= "";
		$tempAtributKondisi= "";
		for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
		{
			$tempKode= $arrAsesor[$checkbox_index]["KODE"];	
			$tempMetode= $arrAsesor[$checkbox_index]["METODE"];
			$tempAtributNama= $arrAsesor[$checkbox_index]["ATRIBUT_NAMA"];
			$tempNamaIndikator= $arrAsesor[$checkbox_index]["NAMA_INDIKATOR"];
			$tempNama= $arrAsesor[$checkbox_index]["NAMA"];
			$tempNilai= $arrAsesor[$checkbox_index]["NILAI"];
			$tempKeterangan= $arrAsesor[$checkbox_index]["PEGAWAI_KETERANGAN"];
		?>
        <?
		if($tempNamaKondisi == $tempNama)
		{
			if($tempAtributKondisi == $tempAtributNama){}
			else
			{
		?>
        <tr>
        	<td style="background-color:#CCC"><?=$tempAtributNama?></td>
            <td style="background-color:#CCC"><?=$tempKeterangan?></td>
        </tr>
        <?
			}
		}
		else
		{
        ?>
        <tr>
        	<th colspan="6"><?=$tempNama?></th>
        </tr>
        <?
			if($tempAtributKondisi == $tempAtributNama){}
			else
			{
		?>
        <tr>
        	<td style="background-color:#CCC"><?=$tempAtributNama?></td>
            <td style="background-color:#CCC"><?=$tempKeterangan?></td>
        </tr>
        <?
			}
		}
        ?>
        <tr>
        	<td style="display:none;"><?=$tempKode?></td>
            <td style="display:none;"><?=$tempMetode?></td>
            <td colspan="2"><?=$tempNamaIndikator?></td>
            <td style="display:none;"><?=$tempNilai?></td>
            <td style="display:none;"><?=$tempKeterangan?></td>
		<? 
		$tempNamaKondisi= $tempNama;
		$tempAtributKondisi= $tempAtributNama;
		}
		?>
        </tbody>
    </table>
</div>
	<?
	}
	?>
</div>
<?
if($reqMode == 'simpan' || $reqMode == 'error' || $reqMode == 'hapus'){
	echo '<script language="javascript">';
	echo "$.jGrowl('".$alertStatus."');";
	echo '</script>';
	//$alertMsg = "Data Berhasil Tersimpan";
}
?>
</body>
</html>
</html>