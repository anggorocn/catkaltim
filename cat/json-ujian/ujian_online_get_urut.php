<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");
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

$reqMode= httpFilterGet("reqMode");
$reqUjianTahapId= httpFilterGet("reqUjianTahapId");
$reqUjianId= httpFilterGet("reqUjianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$statement= " AND BANK_SOAL_PILIHAN_ID IS NOT NULL AND UJIAN_ID = ".$reqUjianId." AND UJIAN_TAHAP_ID = ".$reqUjianTahapId." AND PEGAWAI_ID = ".$reqPegawaiId;
$set= new UjianPegawai();
$tempNoUrut= $set->getNoUrut($ujianPegawaiJadwalTesId, $statement);
echo $tempNoUrut;
exit;
?>