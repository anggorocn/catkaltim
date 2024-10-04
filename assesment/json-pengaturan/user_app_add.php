<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UsersBase.php");
include_once("../WEB/classes/utils/UserLogin.php");

$set = new UsersBase();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqUserGroup = httpFilterPost("reqUserGroup");
$reqNama = httpFilterPost("reqNama");
$reqUserLogin = httpFilterPost("reqUserLogin");
$reqUserPassword = httpFilterPost("reqUserPassword");
$reqPegawaiId = httpFilterPost("reqPegawaiId");
$reqSatkerId = httpFilterPost("reqSatkerId");
$reqSatkerIdTambahan = httpFilterPost("reqSatkerIdTambahan");

$set->setField("USER_GROUP_ID", setNULL($reqUserGroup));
$set->setField("NAMA", $reqNama);
$set->setField("USER_LOGIN", $reqUserLogin);
$set->setField("PEGAWAI_ID", setNULL($reqPegawaiId));
$set->setField("SATKER_ID", $reqSatkerId);
$set->setField("USER_PASS", $reqUserPassword);
$set->setField("SATKER_ID_TAMBAHAN", $reqSatkerIdTambahan);

if($reqMode == "insert")
{
	if($set->insertModif())
		echo "Data berhasil disimpan.";
		//echo $set->query;exit;
}
else
{
	$set->setField("USER_APP_ID", $reqId);
	
	if($set->updateModif())
		echo "Data berhasil disimpan.";
	
}

//echo $set->query;exit;
?>