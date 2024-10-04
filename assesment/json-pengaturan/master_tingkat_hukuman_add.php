<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/TingkatHukuman.php");


$tingkat_hukuman	= new TingkatHukuman();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqNama 		= httpFilterPost("reqNama");
$reqPeraturanId 		= httpFilterPost("reqPeraturanId");

$tingkat_hukuman->setField('NAMA', $reqNama);
$tingkat_hukuman->setField('PERATURAN_ID', $reqPeraturanId);

if($reqMode == "insert")
{
	$tingkat_hukuman->setField("LAST_CREATE_USER", $userLogin->idUser);
	$tingkat_hukuman->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$tingkat_hukuman->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
	
	if($tingkat_hukuman->insert())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';
			
	echo $mode;
}
elseif($reqMode == "update")
{
	$tingkat_hukuman->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$tingkat_hukuman->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$tingkat_hukuman->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);		
	$tingkat_hukuman->setField('TINGKAT_HUKUMAN_ID',$reqId);
	
	if($tingkat_hukuman->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>