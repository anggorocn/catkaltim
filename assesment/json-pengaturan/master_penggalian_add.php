<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Penggalian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$penggalian	= new Penggalian();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqTahun 		= httpFilterPost("reqTahun");
$reqKode 		= httpFilterPost("reqKode");
$reqNama 		= httpFilterPost("reqNama");
$reqKeterangan 	= httpFilterPost("reqKeterangan");
$reqStatusGroup= httpFilterPost("reqStatusGroup");
$reqStatusCbi= httpFilterPost("reqStatusCbi");
$reqStatusCat= httpFilterPost("reqStatusCat");


$penggalian->setField('TAHUN', $reqTahun);
$penggalian->setField('KODE', $reqKode);
$penggalian->setField('NAMA', $reqNama);
$penggalian->setField('KETERANGAN', $reqKeterangan);

if($reqStatusGroup == "")
$penggalian->setField('STATUS_GROUP', ValToNullDB($req));
else
$penggalian->setField('STATUS_GROUP', "'".$reqStatusGroup."'");
$penggalian->setField('STATUS_CBI', ValToNullDB($reqStatusCbi));
$penggalian->setField('STATUS_CAT', ValToNullDB($reqStatusCat));



if($reqMode == "insert")
{
	$penggalian->setField("LAST_CREATE_USER", $userLogin->idUser);
	$penggalian->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	
	if($penggalian->insert())
	{
		$reqId= $penggalian->id;
		$mode = 'Data berhasil disimpan';
		//echo $penggalian->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';
		//echo $penggalian->query;exit;		
	echo $mode;
}
elseif($reqMode == "update")
{
	$penggalian->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$penggalian->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$penggalian->setField('PENGGALIAN_ID',$reqId);
	
	if($penggalian->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>