<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/JadwalTesFormulaAssesmentUjianTahap.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$tempUjianId= $ujianPegawaiUjianId;
$tempSystemTanggalNow= date("d-m-Y");

$reqTipeUjianId= httpFilterGet("reqTipeUjianId");

$set= new JadwalTesFormulaAssesmentUjianTahap();
$set->selectByParams(array(), -1,-1, " AND JADWAL_TES_ID = ".$ujianPegawaiJadwalTesId." AND TIPE_UJIAN_ID = ".$reqTipeUjianId);
$set->firstRow();
// echo $set->query;exit;
$tempinfo= $set->getField("LAST_CREATE_USER");
echo json_encode($tempinfo);
exit;
?>