<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UsersBase.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/KMail.php");

$user_base = new UsersBase();

$reqEmail = httpFilterPost("reqEmail");

$user_base->selectByParamsSimple(array("USER_LOGIN" => $reqEmail));
$user_base->firstRow();


if($user_base->getField("EMAIL") == "")
{
	echo "Email tidak ditemukan.";	
}
else
{
	$xmlfile = "../WEB/weburl.xml";
	$data = simplexml_load_file($xmlfile);
	$linktemplate= $data->urlConfig->linkConfig->linktemplate;
	//$body = file_get_contents("http://".$_SERVER['SERVER_NAME']."/pds-rekrutmen/templates/reset_password.php?reqId=".$user_base->getField("USER_LOGIN_ID"));
	$body = file_get_contents("http://".$_SERVER['SERVER_NAME'].$linktemplate."reset_password.php?reqId=".$user_base->getField("USER_LOGIN_ID"));

	$mail = new KMail("backup");
	$mail->AddAddress($user_base->getField("EMAIL") , $user_base->getField("NAMA"));
//	$mail->AddAddress("rosyidi.alhamdani@valsix.co.id" , "Rosyidi Alhamdani");
//	$mail->AddAddress("riza@ptpds.co.id", "Riza Akhmad Juliantoko"); 
	$mail->Subject  =  "Reset Password - Career and Recruitment Center PT Pelindo Daya Sejatera";
	$mail->MsgHTML($body);
	if(!$mail->Send())
	{
		echo "Gagal kirim";
	}
	else
	{
		echo "Berhasil kirim";
	}	
}

		

?>