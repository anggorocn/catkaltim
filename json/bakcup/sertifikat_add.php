<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Sertifikat.php");
include_once("../WEB/classes/utils/UserLogin.php");

$sertifikat = new Sertifikat();


$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKode= httpFilterPost("reqKode");
$reqNama= httpFilterPost("reqNama");
$reqKeterangan= httpFilterPost("reqKeterangan");

if($reqMode == "insert")
{
	$sertifikat->setField("LAST_CREATE_USER", $userLogin->nama);
	$sertifikat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$sertifikat->setField('KODE', $reqKode);
	$sertifikat->setField('NAMA', $reqNama);
	$sertifikat->setField('KETERANGAN', $reqKeterangan);
	
	if($sertifikat->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$sertifikat->setField("LAST_UPDATE_USER", $userLogin->nama);
	$sertifikat->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$sertifikat->setField('SERTIFIKAT_ID', $reqId); 
	$sertifikat->setField('KODE', $reqKode);
	$sertifikat->setField('NAMA', $reqNama);
	$sertifikat->setField('KETERANGAN', $reqKeterangan);
	
	if($sertifikat->update())
		echo "Data berhasil disimpan.";
	
}
?>