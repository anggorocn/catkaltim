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
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqId= httpFilterGet("reqId");

/* DATA VIEW */
$index_loop= 0;
$arrAsesor="";
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND C.JADWAL_TES_ID = ".$reqId;
$set= new Asesor();
$set->selectByParamsHistoriTanggal($statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrAsesor[$index_loop]["KODE"]= $set->getField("KODE");	
	$arrAsesor[$index_loop]["METODE"]= $set->getField("METODE");
	$arrAsesor[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrAsesor[$index_loop]["ASESOR_NAMA"]= $set->getField("ASESOR_NAMA");
	$arrAsesor[$index_loop]["NAMA"]= $set->getField("NAMA");
	$arrAsesor[$index_loop]["NILAI"]= $set->getField("NILAI");
	$arrAsesor[$index_loop]["PEGAWAI_KETERANGAN"]= $set->getField("PEGAWAI_KETERANGAN");
	$arrAsesor[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
	$index_loop++;
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
	<div id="header-tna-detil">
    <?
	if($jumlah_asesor == 0)
	{
    ?>
    <span>Pegawai Belum dinilai oleh asesor</span>
    <?
	}
	else
	{
    ?>
    <span><?=$arrAsesor[0]["NAMA"]?></span>
    <?
	}
    ?>
    </div>
    <div id="konten">
    <table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
    <?
	if($jumlah_asesor == 0){}
	else
	{
    ?>
        <thead class="altrowstable">
            <tr>
              <th>Indikator yg dinilai</th>
              <th>Asesor</th>
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
			$tempAsesorNama= $arrAsesor[$checkbox_index]["ASESOR_NAMA"];
			$tempAtributNama= $arrAsesor[$checkbox_index]["ATRIBUT_NAMA"];
			$tempNamaIndikator= $arrAsesor[$checkbox_index]["NAMA_INDIKATOR"];
			$tempNama= $arrAsesor[$checkbox_index]["NAMA"];
			$tempNilai= $arrAsesor[$checkbox_index]["NILAI"];
			$tempKeterangan= $arrAsesor[$checkbox_index]["PEGAWAI_KETERANGAN"];
		?>
        <?
		$tempAtributNamaKeterangan= $tempAtributNama.$tempKeterangan.$tempAsesorNama;
		if($tempNamaKondisi == $tempNama)
		{
			if($tempAtributKondisi == $tempAtributNamaKeterangan){}
			else
			{
		?>
        <tr>
        	<td style="background-color:#CCC"><?=$tempAtributNama?></td>
            <td style="background-color:#CCC"><?=$tempAsesorNama?></td>
            <td style="background-color:#CCC"><?=$tempKeterangan?></td>
        </tr>
        <?
			}
		}
		else
		{
			if($tempAtributKondisi == $tempAtributNama){}
			else
			{
		?>
        <tr>
        	<td style="background-color:#CCC"><?=$tempAtributNama?></td>
            <td style="background-color:#CCC"><?=$tempAsesorNama?></td>
            <td style="background-color:#CCC"><?=$tempKeterangan?></td>
        </tr>
        <?
			}
		}
        ?>
        <tr>
            <td colspan="3"><?=$tempNamaIndikator?></td>
		<? 
		$tempNamaKondisi= $tempNama;
		$tempAtributKondisi= $tempAtributNama.$tempKeterangan.$tempAsesorNama;
		}
		?>
        </tbody>
    <?
	}
    ?>
    </table>
</div>
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