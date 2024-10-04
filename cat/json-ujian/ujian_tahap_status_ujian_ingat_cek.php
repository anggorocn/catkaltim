<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjianIngat.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

date_default_timezone_set('Asia/Jakarta');

$reqUjianId= httpFilterGet("reqUjianId");
$reqUjianTahapId= httpFilterGet("reqUjianTahapId");
$tempPegawaiId= $userLogin->pegawaiId;
$tempSystemTanggalNow= date("d-m-Y");

$set= new UjianTahap();
$set->selectByParams(array(), -1,-1, " AND UJIAN_TAHAP_ID = ".$reqUjianTahapId);
$set->firstRow();
$reqTipeUjianId= $set->getField("TIPE_UJIAN_ID");

// $statement= " AND UJIAN_ID = ".$reqUjianId." AND PEGAWAI_ID = ".$tempPegawaiId." AND UJIAN_TAHAP_ID = ".$reqUjianTahapId;
$statement= " AND UJIAN_ID = ".$reqUjianId." AND PEGAWAI_ID = ".$tempPegawaiId." AND TIPE_UJIAN_ID = ".$reqTipeUjianId;
$set= new UjianTahapStatusUjianIngat();
$tempNoUrut= $set->getCountByParams(array(), $statement);
// echo $set->query;exit;
// echo $tempNoUrut;
echo $tempNoUrut;
exit;
?>