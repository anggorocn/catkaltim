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
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$tempPegawaiId= $userLogin->pegawaiId;
$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId;
$set= new UjianTahapStatusUjianPetunjuk();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;
$set->firstRow();
$tempUjianTahapId= $set->getField("UJIAN_TAHAP_ID");

// if(($reqTipeUjianId >=12 && $reqTipeUjianId <= 15) || ($reqTipeUjianId >=8 && $reqTipeUjianId <= 11) || $reqTipeUjianId == 16 || $reqTipeUjianId == 43)
// {
// 	// cfit b mabil latihan cfit a
// 	if($reqTipeUjianId >=12 && $reqTipeUjianId <= 15)
// 	$reqTipeUjianIdLatihan= $reqTipeUjianId - 4;
// 	// cfit a mabil latihan cfit b
// 	elseif($reqTipeUjianId >=8 && $reqTipeUjianId <= 11)
// 	$reqTipeUjianIdLatihan= $reqTipeUjianId + 4;
// 	else
// 	$reqTipeUjianIdLatihan= $reqTipeUjianId;

// 	$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TIPE_UJIAN_ID = ".$reqTipeUjianIdLatihan;
// 	$set= new UjianTahapStatusUjianPetunjuk();
// 	$set->selectByParamsLatihan(array(), -1, -1, $statement);
// 	// echo $set->query;exit;
// 	$set->firstRow();
// 	$tempUjianTahapId= $set->getField("UJIAN_TAHAP_ID");
// }
echo $tempUjianTahapId;
exit;
?>