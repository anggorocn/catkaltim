<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");

$reqId = httpFilterGet("reqId");

$set_cetak = new Pelamar();
$set_cetak->setField("FIELD", "STATUS_CETAK");
$set_cetak->setField("FIELD_VALUE", 1);
$set_cetak->setField("PELAMAR_ID",$reqId);
if($set_cetak->updateByField())
{
	echo "Data Berhasil disimpan.";
}
?>