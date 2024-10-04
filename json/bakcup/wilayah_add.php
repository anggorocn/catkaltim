<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Wilayah.php");
include_once("../WEB/classes/utils/UserLogin.php");

$wilayah = new Wilayah();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKode= httpFilterPost("reqKode");
$reqNama= httpFilterPost("reqNama"); 

$wilayah->setField('KODE', $reqKode);
$wilayah->setField('NAMA', $reqNama);
	
if($reqMode == "insert")
{
	$wilayah->setField("LAST_CREATE_USER", $userLogin->nama);
	$wilayah->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	if($wilayah->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$wilayah->setField("LAST_UPDATE_USER", $userLogin->nama);
	$wilayah->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	$wilayah->setField('WILAYAH_ID', $reqId); 
	if($wilayah->update())
		echo "Data berhasil disimpan.";	
}
?>