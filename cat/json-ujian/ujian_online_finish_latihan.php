<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");
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

// $tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiLowonganId= $ujianPegawaiJadwalTesId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$reqUjianId= httpFilterGet("reqUjianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqId= httpFilterGet("reqId");

$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianPegawai();
$set->selectByParamsCheckLatihan(array(), -1,-1, $tempUjianPegawaiLowonganId, $statement);
// echo $set->query;exit;
$set->firstRow();
$reqUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$reqTipeUjianId= $set->getField("TIPE_UJIAN_ID");


$statement= " AND UJIAN_ID= ".$reqUjianId." AND UJIAN_TAHAP_ID = ".$reqId." AND PEGAWAI_ID = ".$reqPegawaiId;
$set= new UjianTahapStatusUjian();
$set->selectByParamsLatihan(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit();
$tempPegawaiId= $set->getField("PEGAWAI_ID");
unset($set);

if($tempPegawaiId == "")
{
	$set= new UjianTahapStatusUjian();
	$set->setField("UJIAN_PEGAWAI_DAFTAR_ID", $reqUjianPegawaiDaftarId);
	$set->setField("JADWAL_TES_ID", $ujianPegawaiJadwalTesId);
	$set->setField("LOWONGAN_ID", $tempUjianPegawaiLowonganId);
	$set->setField("FORMULA_ASSESMENT_ID", $ujianPegawaiFormulaAssesmentId);
	$set->setField("FORMULA_ESELON_ID", $ujianPegawaiFormulaEselonId);
	$set->setField("TIPE_UJIAN_ID", $reqTipeUjianId);
	$set->setField("UJIAN_ID", $reqUjianId);
	$set->setField("UJIAN_TAHAP_ID", $reqId);
	$set->setField("PEGAWAI_ID", $reqPegawaiId);
	$set->setField("STATUS", "1");
	$set->setField("LAST_CREATE_USER", $userLogin->nama);
	$set->setField("LAST_CREATE_DATE", "NOW()");
	$set->insertLatihan();
	//echo $set->query;exit;
	unset($set);
}

echo "1";
?>