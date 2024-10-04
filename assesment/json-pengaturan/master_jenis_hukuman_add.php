<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JenisHukuman.php");


$jenis_hukuman	= new JenisHukuman();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqTingkatHukumanId 	= httpFilterPost("reqTingkatHukumanId");
$reqNama 		= httpFilterPost("reqNama");


$jenis_hukuman->setField('TINGKAT_HUKUMAN_ID', $reqTingkatHukumanId);	
$jenis_hukuman->setField('NAMA', $reqNama);		

if($reqMode == "insert")
{
	$jenis_hukuman->setField("LAST_CREATE_USER", $userLogin->idUser);
	$jenis_hukuman->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$jenis_hukuman->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
	
	if($jenis_hukuman->insert())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';
			
	echo $mode;
}
elseif($reqMode == "update")
{
	$jenis_hukuman->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$jenis_hukuman->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$jenis_hukuman->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);		
	$jenis_hukuman->setField('JENIS_HUKUMAN_ID',$reqId);
	
	if($jenis_hukuman->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>