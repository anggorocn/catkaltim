<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
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
$reqMode= httpFilterRequest("reqMode");
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
$statement.= " AND A.PUKUL_AWAL = '".$reqPukulAwal."'";
if($reqJenisId == "1")
{
	if($reqMode == "1")
	$statement.= " AND A.PENGGALIAN_ID = 0";
	else
	$statement.= " AND A.PENGGALIAN_ID IS NULL";
}
elseif($reqJenisId == "2")
{
	$statement.= " AND A.PENGGALIAN_ID IS NOT NULL";
}
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
?>
<? 
while($set->nextRow())
{
	$tempRowId= $set->getField("PUKUL_AWAL");
	$tempSimulasiNama= $set->getField("NAMA_SIMULASI");
	$tempJam= $set->getField("PUKUL_AWAL")." s/d ".$set->getField("PUKUL_AKHIR");
?>
<tr>
	<td style="text-align:center"><?=$reqNo?></td>
    <td style="text-align:center"><?=$tempJam?></td>
    <td style="text-align:center"><?=$tempSimulasiNama?></td>
</tr>
<?
	$reqNo++;
}
?>