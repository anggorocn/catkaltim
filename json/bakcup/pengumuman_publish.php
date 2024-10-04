<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pengumuman.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pengumuman = new Pengumuman();

$reqId = httpFilterGet("reqId");
$reqMode = httpFilterGet("reqMode");

if($reqMode == "publish")
	$reqValue = '1';
else
	$reqValue = '0';	


$pengumuman->setField('PENGUMUMAN_ID', $reqId);
$pengumuman->setField('FIELD', "PUBLISH");
$pengumuman->setField('FIELD_VALUE', $reqValue);

if($pengumuman->updateByField())
	echo "Data berhasil disimpan.";

?>