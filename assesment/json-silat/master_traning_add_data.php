<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Training.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterRequest('reqId');
$reqRowId= httpFilterPost("reqRowId");

$reqTahun= httpFilterPost("reqTahun");
$reqNama= httpFilterPost("reqNama");
$reqJamEs2= httpFilterPost("reqJamEs2");
$reqJamEs3= httpFilterPost("reqJamEs3");
$reqJamEs4= httpFilterPost("reqJamEs4");
$reqJamFu= httpFilterPost("reqJamFu");

$set= new Training();
$set->setField("NAMA", setQuote($reqNama,1));
$set->setField("TAHUN", $reqTahun);
$set->setField("JAM_ES2", setNULL($reqJamEs2));
$set->setField("JAM_ES3", setNULL($reqJamEs3));
$set->setField("JAM_ES4", setNULL($reqJamEs4));
$set->setField("JAM_JFU", setNULL($reqJamFu));
$set->setField("TRAINING_ID", $reqId);

if($reqId == "")
	$reqMode= "insert";
else
	$reqMode= "update";

if($reqMode == "insert")
{
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);	
	if($set->insert())
	{
		$reqId= $set->id;
		$mode = 'simpan';
	}
	else
		$mode = 'error';
	//echo $set->query;exit;
	echo $reqId."-Data Tersimpan-".$mode;
}
elseif($reqMode == "update")
{
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
	if($set->update())
	{
		$mode = 'simpan';
	}
	else
		$mode = 'error';
	
	//echo $set->query;
	echo $reqId."-Data Tersimpan-".$mode;
}
?>