<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UserLoginBase.php");
include_once("../WEB/classes/utils/UserLogin.php");

$user_login = new UserLoginBase();

$reqPassword = httpFilterPost("reqPassword");
$reqPassword1 = httpFilterPost("reqPassword1");

$update_pass = false;

if($reqPassword == "")
	echo "Masukkan password.";	
elseif($reqPassword1 == "")
	echo "Masukkan konfirmasi password.";	
elseif($reqPassword == $reqPassword1)
	$update_pass = true;
else
	echo "Password dan Konfirmasi password tidak sama.";
	
if($update_pass == true)
{
	$user_login->setField("FIELD", "USER_PASS");
	$user_login->setField("FIELD_VALUE", md5($reqPassword));
	$user_login->setField("USER_LOGIN_ID", $userLogin->UID);
	
	if($user_login->updateByField())
	{
		echo "Password berhasil diubah.";
	}
}
?>