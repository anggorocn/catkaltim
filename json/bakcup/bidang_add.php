<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Bidang.php");
include_once("../WEB/classes/utils/UserLogin.php");

$bidang = new Bidang();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKode= httpFilterPost("reqKode");
$reqNama= httpFilterPost("reqNama");
$reqKeterangan= httpFilterPost("reqKeterangan");

if($reqMode == "insert")
{
	$bidang->setField("LAST_CREATE_USER", $userLogin->nama);
	$bidang->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$bidang->setField('KODE', $reqKode);
	$bidang->setField('NAMA', $reqNama);
	$bidang->setField('KETERANGAN', $reqKeterangan);
	
	if($bidang->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$bidang->setField("LAST_UPDATE_USER", $userLogin->nama);
	$bidang->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$bidang->setField('JURUSAN_ID', $reqId); 
	$bidang->setField('KODE', $reqKode);
	$bidang->setField('NAMA', $reqNama);
	$bidang->setField('KETERANGAN', $reqKeterangan);
	
	if($bidang->update())
		echo "Data berhasil disimpan.";
	
}
?>