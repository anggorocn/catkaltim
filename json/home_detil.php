<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/DiklatPeserta.php");

$reqPelamarId= $userLogin->userPelamarId;

if($reqPelamarId == "")
{
	exit();
}

$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserPelamarNiK= $userLogin->userNik;
$tempUserStatusJenis= $userLogin->userStatusJenis;

$reqId= httpFilterGet("reqId");

if($tempUserStatusJenis == "1")
	$infouser= $tempUserPelamarNip;
else
	$infouser= $tempUserPelamarNiK;

$set= new DiklatPeserta();
$set->setField('JADWAL_AWAL_TES_ID', $reqId);
$set->setField('PEGAWAI_ID', $reqPelamarId);
$set->setField('STATUS',ValToNullDB($reqStatus));
$set->setField("LAST_CREATE_USER", $infouser);
$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");

if($set->insertDaftar())
{
}
echo "Anda Berhasil mendaftar";
unset($set);
?>