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
$reqUserLogin = httpFilterPost("reqUserLogin");
$reqUserPassword = httpFilterPost("reqUserPassword");

$set->setField("USER_LOGIN", $reqUserLogin);
$set->setField("USER_PASS", $reqUserPassword);

if($reqMode == "insert")
{
}
else
{
	$set->setField("USER_APP_ID", $reqId);
	
	if($set->resetPassword())
		echo "Data berhasil disimpan.";
	
}

//echo $set->query;exit;
?>