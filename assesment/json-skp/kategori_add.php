<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Kategori.php");
include_once("../WEB/classes/utils/UserLogin.php");

$kategori = new Kategori();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqNama = httpFilterPost("reqNama");
$reqKeterangan = httpFilterPost("reqKeterangan");
$reqUrut = httpFilterPost("reqUrut");


$kategori->setField("NAMA", $reqNama);
$kategori->setField("KETERANGAN", $reqKeterangan);
$kategori->setField("URUT", $reqUrut);

if($reqMode == "insert")
{
	if($kategori->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$kategori->setField("KATEGORI_ID", $reqId);
	
	if($kategori->update())
		echo "Data berhasil disimpan.";
	
}
?>