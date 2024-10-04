<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base-diklat/Peserta.php");

$reqId= $userLogin->userPelamarId;


// if($reqId == "")
// {
// 	echo "autologin"; exit;
// }

 // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$file = new FileHandler();
$reqUser= httpFilterPost("reqUser");
$reqPasswd= httpFilterPost("reqPasswd");

$userLogin->resetLogin();
if ($userLogin->verifyUserLogin(strtolower($reqUser), $reqPasswd)) 
{
	echo "success"; 
	exit;
}
else
{
	echo "gagal"; 
	exit;	
}
?>