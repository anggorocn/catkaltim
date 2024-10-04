<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UsersBase.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/KMail.php");

$user_base = new UsersBase();

$reqId = httpFilterPost("reqId");
$reqPassword = httpFilterPost("reqPassword");

$user_base->selectByParamsSimple(array("MD5(USER_LOGIN_ID::VARCHAR)" => $reqId));
$user_base->firstRow();
//echo "asdfasdf".$user_base->query;exit;

$user_password = new UsersBase();
$user_password->setField("USER_PASS", md5($reqPassword));
$user_password->setField("USER_LOGIN_ID", $user_base->getField("USER_LOGIN_ID"));
if($user_password->updatePassword())
{
	if($user_base->getField("EMAIL") == "")
	{
		echo "Email tidak ditemukan.";	
	}
	else
	{
		/*
		$xmlfile = "../WEB/weburl.xml";
		$data = simplexml_load_file($xmlfile);
		$linktemplate= $data->urlConfig->linkConfig->linktemplate;	
		//$body = file_get_contents("http://".$_SERVER['SERVER_NAME']."/pds-rekrutmen/templates/reset_password_berhasil.php?reqId=".$user_base->getField("USER_LOGIN_ID"));
		$body = file_get_contents("http://".$_SERVER['SERVER_NAME'].$linktemplate."reset_password_berhasil.php?reqId=".$user_base->getField("USER_LOGIN_ID"));
		
		$mail = new KMail("backup");
		
		$mail->AddAddress($user_base->getField("EMAIL") , $user_base->getField("NAMA"));
	//	$mail->AddAddress("rosyidi.alhamdani@valsix.co.id" , "Rosyidi Alhamdani");
	//	$mail->AddAddress("riza@ptpds.co.id", "Riza Akhmad Juliantoko"); 
		$mail->Subject  =  "Konfirmasi Reset Password - Career and Recruitment Center PT Pelindo Daya Sejatera";
		$mail->MsgHTML($body);
		if(!$mail->Send())
		{
			echo "Gagal kirim";
		}
		else
		{
			echo "Berhasil kirim";
		}	*/
		echo "Data Berhasil Disimpan.";	
	}
}
//echo $user_password->query;exiit;
		

?>