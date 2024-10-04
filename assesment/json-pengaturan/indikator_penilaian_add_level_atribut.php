<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/LevelAtribut.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set= new LevelAtribut();

$reqMode= httpFilterRequest("reqMode");
$reqRowId= httpFilterPost("reqRowId");

$reqAtributId= httpFilterPost("reqAtributId");
$reqLevel= httpFilterPost("reqLevel");
$reqKeterangan= httpFilterPost("reqKeterangan");

$set->setField("ATRIBUT_ID", $reqAtributId);
$set->setField("LEVEL", $reqLevel);
$set->setField("KETERANGAN", $reqKeterangan);

if($reqRowId == "")
{
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	
	if($set->insert())
	{
		$mode = 'Data berhasil disimpan';
		//echo $set->query;exit;	
	}
	else {
		$mode = 'Data gagal disimpan';
		//echo $set->query;exit;
	}
	
	echo $mode;
}
else
{
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set->setField('LEVEL_ID',$reqRowId);
	
	if($set->update())
	{
		$mode = 'Data berhasil disimpan';
		//echo $set->query;exit;
	}
	else {
		$mode = 'Data gagal disimpan';
		//echo $set->query;exit;
	}
	
	echo $mode;
}
?>