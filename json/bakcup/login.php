<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");

$reqMode = httpFilterRequest("reqMode");
$reqUser = httpFilterPost("reqUser");
$reqPasswd = httpFilterPost("reqPasswd");
$reqSecurity= httpFilterPost("reqSecurity");

//------ xml -------
$xml_file = "../WEB/web.xml"; 
$data_xml = simplexml_load_file($xml_file);
$server=1;
$path_server= $data_xml->path->path->configValue->$server;

//if($reqMode == "submitLogin" && $reqUser != "" && $reqPasswd != "" && (md5($reqSecurity) == $_SESSION['security_login_code'])) 
if($reqMode == "submitLogin" && $reqUser != "" && $reqPasswd != "") 
{
	//echo $reqMode."--".$reqUser."--".$reqPasswd;exit;
	$set= new Pelamar();
	$set->selectByParamsData(array("A.EMAIL1"=>$reqUser),-1,-1);
	$set->firstRow();
	//echo $set->query;exit;
	$tempIsDaftar= $set->getField("IS_DAFTAR");
	$tempIsKirimSmsValidasi= $set->getField("IS_KIRIM_SMS_VALIDASI");
	$tempNoHp= $set->getField("NO_HP");
	unset($set);
	
	/*if($tempIsDaftar == "")
	{
		echo '<script language="javascript">';
		echo 'alert("Yang belum Anda validasi adalah:\n1.	[Email: '.$reqUser.']\n\n\nSilakan cek terlebih dahulu email untuk melakukan validasi email tersebut.\nSebelum Anda melakukan semua validasi email, Anda belum dapat melakukan pengiriman lamaran pada formasi lowongan yang tersedia.");';
		echo 'top.location.href = "index.php";';
		echo '</script>';
		exit;
	}*/
	
	/*if($tempIsKirimSmsValidasi == "")
	{
		echo '<script language="javascript">';
		echo 'alert("Yang belum Anda validasi adalah:\n1.	[Hp: '.$tempNoHp.']\n\n\nSilakan cek terlebih dahulu email dan HP lain untuk melakukan validasi email tersebut.\nSebelum Anda melakukan semua validasi email, Anda belum dapat melakukan pengiriman lamaran pada formasi lowongan yang tersedia.");';
		echo 'top.location.href = "index.php";';
		echo '</script>';
		exit;
	}*/
	$userLogin->resetLogin();
	if ($userLogin->verifyUserLogin($reqUser, $reqPasswd)) 
	{
		//header("location:index.php");
		//exit;		
	}
	else
	{
		echo "Username atau password anda masih salah.";
		/*echo '<script language="javascript">';
		echo 'alert("Username atau password anda masih salah.");';
		echo 'top.location.href = "index.php";';
		echo '</script>';		
		exit;*/
	}
}
?>