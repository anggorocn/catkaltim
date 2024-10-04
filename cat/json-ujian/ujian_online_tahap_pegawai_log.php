<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianTahapPegawai.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

$reqUjianTahapId= httpFilterGet("reqUjianTahapId");
$reqUjianId= httpFilterGet("reqUjianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$reqLogWaktu= httpFilterGet("reqLogWaktu");

if($reqLogWaktu >= 0)
{
	// set STATUS_UJIAN
	$set_detil= new UjianTahapPegawai();
	$set_detil->setField("UJIAN_ID", $reqUjianId);
	$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
	$set_detil->setField("UJIAN_TAHAP_ID", $reqUjianTahapId);
	$set_detil->setField("TIPE_UJIAN_ID", $reqTipeUjianId);
	$set_detil->setField("LOG_WAKTU", $reqLogWaktu);
	$set_detil->updateLog();
	unset($set_detil);
	//echo $set_detil->query;exit;
	unset($set_detil);
}
echo "1";
?>