<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAwalTesPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$reqArrPegawaiId= explode(",", $reqPegawaiId);

for($i=0; $i<count($reqArrPegawaiId); $i++)
{
	$reqRowId= $reqArrPegawaiId[$i];
	$set= new JadwalAwalTesPegawai();
	$set->setField("JADWAL_AWAL_TES_ID", $reqId);
	$set->setField("PEGAWAI_ID", $reqRowId);
	// echo "asd";exit();
	$set->delete();
	// echo $set->query;exit();
}
echo "1";
?>