<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$lowongan = new Lowongan();

$reqId = httpFilterGet("reqId");
$reqMode = httpFilterGet("reqMode");

if($reqMode == "publish")
	$reqValue = '1';
else
	$reqValue = '0';	


$lowongan->setField('LOWONGAN_ID', $reqId);
$lowongan->setField('FIELD', "STATUS");
$lowongan->setField('FIELD_VALUE', $reqValue);

if($lowongan->updateByField())
	echo "Data berhasil disimpan.";

?>