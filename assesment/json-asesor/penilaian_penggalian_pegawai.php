<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalPenggalianPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$tempAsesorId= $userLogin->userAsesorId;
// print_r($tempAsesorId);exit;
if($tempAsesorId == "")
{
	exit;
}

$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$set= new JadwalPenggalianPegawai();
$set->setField("JADWAL_TES_ID", $reqJadwalTesId);
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->pjadwalpenggalianpegawai();
echo "1";
?>