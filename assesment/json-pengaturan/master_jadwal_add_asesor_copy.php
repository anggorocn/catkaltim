<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAsesor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqPenggalianId= httpFilterGet("reqPenggalianId");
$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");

$set_detil= new JadwalAsesor();
$set_detil->setField('JADWAL_TES_ID', $reqId);
$set_detil->setField('JADWAL_ACARA_ID', $reqRowId);
$set_detil->setField('PENGGALIAN_ID', $reqPenggalianId);
$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
$set_detil->setField("LAST_CREATE_DATE", "NOW()");
$set_detil->insertCopas();
?>