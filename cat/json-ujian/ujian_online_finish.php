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

$reqUjianId= httpFilterGet("reqUjianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqId= httpFilterGet("reqId");

$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianPegawai();
$set->selectByParamsCheck(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement);
// echo $set->query;exit;
$set->firstRow();
$reqUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$req= $set->getField("JADWAL_TES_ID");
$req= $set->getField("FORMULA_ASSESMENT_ID");
$req= $set->getField("FORMULA_ESELON_ID");
$req= $set->getField("UJIAN_ID");
$req= $set->getField("UJIAN_TAHAP_ID");
$reqTipeUjianId= $set->getField("TIPE_UJIAN_ID");


$statement= " AND UJIAN_ID= ".$reqUjianId." AND UJIAN_TAHAP_ID = ".$reqId." AND PEGAWAI_ID = ".$reqPegawaiId;
$set= new UjianTahapStatusUjian();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
$tempPegawaiId= $set->getField("PEGAWAI_ID");
unset($set);

if($tempPegawaiId == "")
{
	$set= new UjianTahapStatusUjian();
	$set->setField("UJIAN_PEGAWAI_DAFTAR_ID", $reqUjianPegawaiDaftarId);
	$set->setField("JADWAL_TES_ID", $ujianPegawaiJadwalTesId);
	$set->setField("FORMULA_ASSESMENT_ID", $ujianPegawaiFormulaAssesmentId);
	$set->setField("FORMULA_ESELON_ID", $ujianPegawaiFormulaEselonId);
	$set->setField("TIPE_UJIAN_ID", $reqTipeUjianId);
	$set->setField("UJIAN_ID", $reqUjianId);
	$set->setField("UJIAN_TAHAP_ID", $reqId);
	$set->setField("PEGAWAI_ID", $reqPegawaiId);
	$set->setField("STATUS", "1");
	$set->setField("LAST_CREATE_USER", $userLogin->nama);
	$set->setField("LAST_CREATE_DATE", "NOW()");
	$set->insert();
	//echo $set->query;exit;
	unset($set);
}

// set STATUS_UJIAN
/*$set_detil= new UjianPegawaiDaftar();
$set_detil->setField("UJIAN_ID", $reqUjianId);
$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
$set_detil->setField("FIELD", "STATUS_SELESAI");
$set_detil->setField("FIELD_VALUE", "1");
$set_detil->updateStatusLog();
unset($set_detil);
//echo $set_detil->query;exit;
unset($set_detil);*/

echo "1";
?>