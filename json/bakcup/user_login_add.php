<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UserLoginBase.php");
include_once("../WEB/classes/utils/UserLogin.php");

$user_login = new UserLoginBase();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqDepartemen = httpFilterPost("reqDepartemen");
$reqCabang = httpFilterPost("reqCabang");
$reqUserGroup = httpFilterPost("reqUserGroup");
$reqNama = httpFilterPost("reqNama");
$reqJabatan = httpFilterPost("reqJabatan");
$reqEmail = httpFilterPost("reqEmail");
$reqTelepon = httpFilterPost("reqTelepon");
$reqUserLogin = httpFilterPost("reqUserLogin");
$reqUserPassword = httpFilterPost("reqUserPassword");
$reqSubmit = httpFilterPost("reqSubmit");
$reqPegawaiId = httpFilterPost("reqPegawaiId");
$reqIsWilayah = httpFilterPost("reqIsWilayah");
$reqWilayahId = httpFilterPost("reqWilayahId");

if($reqDepartemen == 0)
	$reqDepartemen = "NULL";
else
	$reqDepartemen = "'".$reqDepartemen."'";


$user_login->setField("DEPARTEMEN_ID", ValToNullDB($reqDepartemen));
$user_login->setField("USER_GROUP_ID", $reqUserGroup);
$user_login->setField("NAMA", $reqNama);
$user_login->setField("JABATAN", $reqJabatan);
$user_login->setField("EMAIL", $reqEmail);
$user_login->setField("TELEPON", $reqTelepon);
$user_login->setField("USER_LOGIN", $reqUserLogin);
$user_login->setField("USER_PASS", $reqUserPassword);
$user_login->setField("STATUS", 1);
$user_login->setField("PEGAWAI_ID", ValToNullDB($reqPegawaiId));	
$user_login->setField("CABANG_P3_ID", ValToNullDB($reqCabang));
$user_login->setField("IS_WILAYAH", $reqIsWilayah);
$user_login->setField("WILAYAH_ID", ValToNullDB($reqWilayahId));
		
if($reqMode == "insert")
{
	$user_login->setField("LAST_CREATE_USER", $userLogin->UID);
	$user_login->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	if($user_login->insert())
	{
		echo "Data berhasil disimpan.";
		
	}
}
else
{
	$user_login->setField("USER_LOGIN_ID", $reqId);
	$user_login->setField("LAST_UPDATE_USER", $userLogin->UID);
	$user_login->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	
	if($user_login->update())
		echo "Data berhasil disimpan.";
	
}
?>