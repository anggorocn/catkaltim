<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjianPetunjuk.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

$reqUjianId= httpFilterGet("reqUjianId");
$reqUjianTahapId= httpFilterGet("reqUjianTahapId");
$tempPegawaiId= $userLogin->pegawaiId;
$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND UJIAN_ID = ".$reqUjianId." AND PEGAWAI_ID = ".$tempPegawaiId." AND UJIAN_TAHAP_ID = ".$reqUjianTahapId;
$set= new UjianTahapStatusUjianPetunjuk();
$tempNoUrut= $set->getCountByParams(array(), $statement);
// echo $set->query;exit;

if(($reqTipeUjianId >=12 && $reqTipeUjianId <= 15) || ($reqTipeUjianId >=8 && $reqTipeUjianId <= 11) || $reqTipeUjianId == 16 || $reqTipeUjianId == 43)
{
	// cfit b mabil latihan cfit a
	if($reqTipeUjianId >=12 && $reqTipeUjianId <= 15)
	$reqTipeUjianIdLatihan= $reqTipeUjianId - 4;
	// cfit a mabil latihan cfit b
	elseif($reqTipeUjianId >=8 && $reqTipeUjianId <= 11)
	$reqTipeUjianIdLatihan= $reqTipeUjianId + 4;
	else
	$reqTipeUjianIdLatihan= $reqTipeUjianId;

	$statement= " AND UJIAN_ID = ".$reqUjianId." AND PEGAWAI_ID = ".$tempPegawaiId." AND TIPE_UJIAN_ID = ".$reqTipeUjianIdLatihan;
	$set= new UjianTahapStatusUjianPetunjuk();
	$tempNoUrut= $set->getCountByParamsLatihan(array(), $statement);
	// echo $set->query;exit;
}
echo $tempNoUrut;
exit;
?>