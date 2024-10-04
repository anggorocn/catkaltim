<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Dokumen.php");
include_once("../WEB/classes/utils/UserLogin.php");

$dokumen = new Dokumen();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqNama= httpFilterPost("reqNama"); 
$reqFormat= httpFilterPost("reqFormat"); 
$reqWajib= httpFilterPost("reqWajib"); 
$reqStatusAktif= httpFilterPost("reqStatusAktif"); 

$dokumen->setField('KETERANGAN', $reqKeterangan);
$dokumen->setField('NAMA', $reqNama);
$dokumen->setField('FORMAT', $reqFormat);
$dokumen->setField('WAJIB', $reqWajib);
$dokumen->setField('STATUS_AKTIF', $reqStatusAktif);
	
if($reqMode == "insert")
{
	$dokumen->setField("LAST_CREATE_USER", $userLogin->nama);
	$dokumen->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	if($dokumen->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$dokumen->setField("LAST_UPDATE_USER", $userLogin->nama);
	$dokumen->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	$dokumen->setField('DOKUMEN_ID', $reqId); 
	if($dokumen->update())
		echo "Data berhasil disimpan.";	
}
?>