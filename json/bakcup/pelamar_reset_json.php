<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/utils/UserLogin.php");

$set_data = new Pelamar();

$reqId = httpFilterGet("reqId");

$set_data->setField('USER_PASS', md5("admin"));
$set_data->setField('PELAMAR_ID', $reqId);	
if($set_data->resetPassword())
	echo "Data berhasil direset.";

//echo $set_data->query;exit;
?>