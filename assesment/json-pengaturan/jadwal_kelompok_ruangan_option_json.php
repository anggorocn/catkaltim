<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalKelompokRuangan.php");

$set= new JadwalKelompokRuangan();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqJadwalAcaraId= httpFilterGet("reqJadwalAcaraId");
if($reqJadwalAcaraId == ""){}
else
{
	$statement= " AND A.JADWAL_ACARA_ID = ".$reqJadwalAcaraId;
}
$set->selectByParamsMonitoring(array(), -1,-1, $statement);

ini_set("memory_limit", "-1");
set_time_limit(0);

$i=0;
$arrID[$i] = "";
$arrNama[$i] = "";
$i++;

while($set->nextRow())
{
	$arrID[$i] = $set->getField("JADWAL_KELOMPOK_RUANGAN_ID");
	$arrNama[$i] = $set->getField("KELOMPOK_RUANGAN_NAMA");
	$i += 1;
}
$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama);
echo json_encode($arrFinal);
?>