<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$lowongan = new Lowongan();

$reqId = httpFilterGet("reqId");


$lowongan->setField('LOWONGAN_ID', $reqId);

if($lowongan->callProsesCopyLowongan())
	echo "Data berhasil disimpan.";

?>