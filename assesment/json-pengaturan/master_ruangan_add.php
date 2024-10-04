<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Ruangan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$asesor	= new Ruangan();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqNama 		= httpFilterPost("reqNama");
$reqStatus		= httpFilterPost("reqStatus");

$asesor->setField('NAMA', $reqNama);
$asesor->setField('STATUS_AKTIF', $reqStatus);

if($reqMode == "insert")
{
	$asesor->setField("LAST_CREATE_USER", $userLogin->idUser);
	$asesor->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	
	if($asesor->insert())
	{
		$mode = 'Data berhasil disimpan';
		//echo $asesor->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';
		//echo $asesor->query;exit;		
	echo $mode;
}
elseif($reqMode == "update")
{
	$asesor->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$asesor->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$asesor->setField('RUANGAN_ID',$reqId);
	
	if($asesor->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>