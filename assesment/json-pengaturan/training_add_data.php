<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Training.php");
include_once("../WEB/classes/base-silat/KompetensiTraining.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set= new Training();

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");

$reqTahun= httpFilterPost("reqTahun");
$reqNama= httpFilterPost("reqNama");
$reqJamEs2= httpFilterPost("reqJamEs2");
$reqJamEs3= httpFilterPost("reqJamEs3");
$reqJamEs4= httpFilterPost("reqJamEs4");
$reqJamJfu= httpFilterPost("reqJamJfu");

$set->setField("TAHUN", $reqTahun);
$set->setField("NAMA", $reqNama);
$set->setField("JAM_ES2", $reqJamEs2);
$set->setField("JAM_ES3", $reqJamEs3);
$set->setField("JAM_ES4", $reqJamEs4);
$set->setField("JAM_JFU", $reqJamJfu);

$reqSimpan= "";
if($reqMode == "insert")
{
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	
	if($set->insert())
	{
		$reqId= $set->id;
		$mode = 'Data berhasil disimpan';
		$reqSimpan= "1";
		//echo $set->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';
		//echo $set->query;exit;
}
elseif($reqMode == "update")
{
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set->setField("TRAINING_ID", $reqId);
	
	if($set->update())
	{
		$mode = 'Data berhasil disimpan';
		$reqSimpan= "1";
		//echo $set->query;exit;
	}
	else
		$mode = 'Data gagal disimpan';
		
	//echo $set->query;exit;
}

if($reqSimpan == "1")
{
	$set_detil= new KompetensiTraining();
	$set_detil->setField("TRAINING_ID", $reqId);
	$set_detil->setField("TAHUN", $reqTahun);
	$set_detil->updateTrainingTahun();
	unset($set_detil);
	
	echo $reqId."-".$mode;
}
?>