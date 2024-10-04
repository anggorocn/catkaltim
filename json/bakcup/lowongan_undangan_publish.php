<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$lowongan = new Lowongan();

$reqId = httpFilterGet("reqId");
$reqPublish = httpFilterGet("reqPublish");


$lowongan->setField('LOWONGAN_ID', $reqId);
$lowongan->setField('FIELD', "STATUS_UNDANGAN");
$lowongan->setField('FIELD_VALUE', $reqPublish);

if($lowongan->updateByField())
	echo "Data berhasil disimpan.";

?>