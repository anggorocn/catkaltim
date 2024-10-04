<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Peraturan.php");


$peraturan	= new Peraturan();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqNama 		= httpFilterPost("reqNama");
$reqKeterangan	= httpFilterPost("reqKeterangan");

$peraturan->setField('NAMA', $reqNama);
$peraturan->setField('KETERANGAN', $reqKeterangan);


if($reqMode == "insert")
{
	$peraturan->setField("LAST_CREATE_USER", $userLogin->idUser);
	$peraturan->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$peraturan->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
	
	if($peraturan->insert())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';
	//echo $peraturan->query;exit;		
	echo $mode;
}
elseif($reqMode == "update")
{
	$peraturan->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$peraturan->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$peraturan->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);		
	$peraturan->setField('PERATURAN_ID',$reqId);
	
	if($peraturan->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>