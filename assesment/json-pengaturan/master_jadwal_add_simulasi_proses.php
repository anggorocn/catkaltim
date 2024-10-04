<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$set= new JadwalTesSimulasiAsesor();
$set->setField("JADWAL_TES_ID", $reqId);
$set->prosesSimulasi();
//echo $set->query;exit;
//echo $set->errorMsg;exit;
echo "1";
unset($set);
?>