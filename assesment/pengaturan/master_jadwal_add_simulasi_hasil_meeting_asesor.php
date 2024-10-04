<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$set = new JadwalTesSimulasiAsesor();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqNo= httpFilterRequest("reqNo");
$reqJenisId= httpFilterRequest("reqJenisId");
$reqId= httpFilterRequest("reqId");
$reqRowId= httpFilterRequest("reqRowId");
$reqPukulAwal= httpFilterRequest("reqPukulAwal");
$reqMode = httpFilterRequest("reqMode");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data jadwal terlebih dahulu.');";	
	echo "window.parent.location.href = 'master_jadwal_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

/* DATA VIEW */
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set->selectByParamsMonitoring(array(), -1, -1, $statement, "ORDER BY PUKUL_AWAL DESC");
$set->firstRow();
//echo $set->query;exit;
$tempRowId= $set->getField("PUKUL_AWAL");
$tempSimulasiNama= $set->getField("NAMA_SIMULASI");
$tempJam= $set->getField("PUKUL_AKHIR")." s/d Selesai";
?>
<tr>
    <td style="text-align:center"><?=$reqNo?></td>
    <td style="text-align:center"><?=$tempJam?></td>
    <td style="text-align:center; background-color:#CCC; border:none !important">Meeting Asesor</td>
</tr>