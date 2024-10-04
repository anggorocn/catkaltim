<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasi.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqJadwalAwalTesSimulasiId= httpFilterGet("reqJadwalAwalTesSimulasiId");

$reqArrJadwalAwalTesSimulasiId= explode(",", $reqJadwalAwalTesSimulasiId);

for($i=0; $i<count($reqArrJadwalAwalTesSimulasiId); $i++)
{
	$reqRowId= $reqArrJadwalAwalTesSimulasiId[$i];
	$set= new JadwalAwalTesSimulasi();
	$set->setField("JADWAL_AWAL_TES_SIMULASI_ID", $reqRowId);
	// echo "asd";exit();
	$set->deletedetil();
	// echo $set->query;exit();
}
echo "1";
?>