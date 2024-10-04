<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Klasifikasi.php");
include_once("../WEB/classes/utils/UserLogin.php");

$klasifikasi = new Klasifikasi();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqNama= httpFilterPost("reqNama");
$reqKeterangan= httpFilterPost("reqKeterangan");

$klasifikasi->setField('NAMA', $reqNama);
$klasifikasi->setField('KETERANGAN', $reqKeterangan);
$klasifikasi->setField('KLASIFIKASI_ID', $reqId); 


if($reqMode == "insert")
{
	if($klasifikasi->insert())
		echo "Data berhasil disimpan.";
}
else
{
	if($klasifikasi->update())
		echo "Data berhasil disimpan.";
	
}
?>