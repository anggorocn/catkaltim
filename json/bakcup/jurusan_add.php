<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Jurusan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$jurusan = new Jurusan();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKode= httpFilterPost("reqKode");
$reqNama= httpFilterPost("reqNama");
$reqKeterangan= httpFilterPost("reqKeterangan");

if($reqMode == "insert")
{
	$jurusan->setField("LAST_CREATE_USER", $userLogin->nama);
	$jurusan->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$jurusan->setField('KODE', $reqKode);
	$jurusan->setField('NAMA', $reqNama);
	$jurusan->setField('KETERANGAN', $reqKeterangan);
	
	if($jurusan->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$jurusan->setField("LAST_UPDATE_USER", $userLogin->nama);
	$jurusan->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$jurusan->setField('JURUSAN_ID', $reqId); 
	$jurusan->setField('KODE', $reqKode);
	$jurusan->setField('NAMA', $reqNama);
	$jurusan->setField('KETERANGAN', $reqKeterangan);
	
	if($jurusan->update())
		echo "Data berhasil disimpan.";
	
}
?>