<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UsersBase.php");
include_once("../WEB/classes/utils/UserLogin.php");

$user_login = new UsersBase();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqUserLogin = httpFilterPost("reqUserLogin");
$reqUserPassword = httpFilterPost("reqUserPassword");

$user_login->setField("USER_LOGIN", $reqUserLogin);
$user_login->setField("USER_PASS", $reqUserPassword);

$user_login->setField("STATUS", ValToNullDB($reqStatus));
$user_login->setField("USER_LOGIN_ID", $reqId);	
$user_login->setField("LAST_UPDATE_USER", $userLogin->nama);
$user_login->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	

if($user_login->updatePassword())
	echo "Data berhasil disimpan.";
	

?>