<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/functions/crfs_protect.php");
$csrf = new crfs_protect('_crfs_login');



$reqId= $userLogin->userPelamarId;


// if($reqId == "")
// {
// 	echo "autologin"; exit;
// }

 // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$file = new FileHandler();
$reqUser= httpFilterPost("reqUser");
$reqPasswd= httpFilterPost("reqPasswd");
$reqSecurity= httpFilterPost("reqSecurity");

		// print_r(md5($reqSecurity).'-'. $_SESSION['security_code']);exit;

if(md5($reqSecurity) == $_SESSION['security_code'])
{
		// print_r(md5($reqSecurity));exit;

	if (!$csrf->isTokenValid($_POST['_crfs_login']))
	{
		echo "gagal"; 
		exit;
	}

	$userLogin->resetLogin();
	if ($userLogin->verifyUserLogin($reqUser, $reqPasswd)) 
	{	
		echo "success"; 
		exit;		
	}
	else
	{
		echo "gagal"; 
		exit;
	}
}

?>