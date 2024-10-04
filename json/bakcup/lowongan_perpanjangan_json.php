<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$set_data = new Lowongan();

$reqId = httpFilterGet("reqId");
$reqTanggalPerpanjangan = httpFilterGet("reqTanggalPerpanjangan");

$set_data->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalPerpanjangan));
$set_data->setField('LOWONGAN_ID', $reqId);	
if($set_data->updatePerpanjangan())
	echo "1";

//echo $set_data->query;exit;
?>