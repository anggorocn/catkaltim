<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPauli.php");
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
// echo $ujianPegawaiJadwalTesId;exit();

// $tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiLowonganId= $ujianPegawaiJadwalTesId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$tempUjianId= $ujianPegawaiUjianId;

$reqPakaiPauliId= httpFilterGet("reqPakaiPauliId");
$reqUjianPegawaiDaftarId= httpFilterGet("reqUjianPegawaiDaftarId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqFormulaAssesmentId= httpFilterGet("reqFormulaAssesmentId");
$reqFormulaEselonId= httpFilterGet("reqFormulaEselonId");
$reqUjianId= httpFilterGet("reqUjianId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$reqUjianTahapId= httpFilterGet("reqUjianTahapId");

// print_r($reqXYdataNilai); exit();

$sOrder= " ORDER BY A.NOMOR DESC";
$statement= " AND A.JADWAL_TES_ID = ".$tempUjianPegawaiLowonganId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new UjianPauli();
$set->selectByTanda($tempUjianPegawaiLowonganId, $statement);
// echo $set->query;exit();
$set->firstRow();
$reqNomor= $set->getField("NOMOR");
echo $reqNomor;
?>