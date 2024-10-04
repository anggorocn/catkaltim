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
$arrCheckXYData= httpFilterGet("arrCheckXYData");
$arrCheckXYData= explode("-", $arrCheckXYData);

$reqXdata= $arrCheckXYData[0];
$reqYdata= $arrCheckXYData[1];

// print_r($reqXYdataNilai); exit();

$statement= " AND A.JADWAL_TES_ID = ".$tempUjianPegawaiLowonganId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new UjianPauli();
$set->selectByTanda($tempUjianPegawaiLowonganId, $statement);
// echo $set->query;exit();
$set->firstRow();
$reqNomor= $set->getField("NOMOR");
$reqTandaXdata= $set->getField("X_DATA");
$reqTandaYdata= $set->getField("Y_DATA");
// echo $reqTandaXdata."-".$reqTandaYdata;exit();

if($reqTandaXdata == "")
{
	if($reqYdata == "1")
	{
		$reqKondisiXdata= $reqXdata - 1;
	}
	else
	{
		if($reqXdata == 1)
			$reqKondisiXdata= 0;
		else
			$reqKondisiXdata= $reqXdata - 1;
	}

	$reqKolom1= 0;
	$reqKolom2= ($reqKondisiXdata - 0) * 50;
	$reqKolom3= $reqYdata;
}
else
{
	if($reqTandaXdata == 1 && $reqXdata == 1)
		$reqKondisiXdata= 1;
	else
		$reqKondisiXdata= $reqXdata - 1;

	$reqKolom1= 50 - $reqTandaYdata;
	$reqKolom2= ($reqKondisiXdata - $reqTandaXdata) * 50;
	$reqKolom3= $reqYdata;
}

// echo $reqKolom1."-".$reqKolom2."-".$reqKolom3;exit();
// KOLOM1, KOLOM2, KOLOM3

if($reqNomor < 20)
{
	$set= new UjianPauli();
	$set->setField("UJIAN_PEGAWAI_DAFTAR_ID", $reqUjianPegawaiDaftarId);
	$set->setField("LOWONGAN_ID", $tempUjianPegawaiLowonganId);
	$set->setField("JADWAL_TES_ID", $reqJadwalTesId);
	$set->setField("FORMULA_ASSESMENT_ID", $reqFormulaAssesmentId);
	$set->setField("FORMULA_ESELON_ID", $reqFormulaEselonId);
	$set->setField("UJIAN_ID", $reqUjianId);
	$set->setField("UJIAN_TAHAP_ID", $reqUjianTahapId);
	$set->setField("TIPE_UJIAN_ID", $reqTipeUjianId);
	$set->setField("PEGAWAI_ID", $reqPegawaiId);

	$set->setField("X_DATA", $reqXdata);
	$set->setField("Y_DATA", $reqYdata);

	$set->setField("KOLOM1", $reqKolom1);
	$set->setField("KOLOM2", $reqKolom2);
	$set->setField("KOLOM3", $reqKolom3);

	$set->setField("LAST_CREATE_USER", ValToNullDB($userLogin->nama));
	$set->setField("LAST_CREATE_DATE", "NOW()");
	if($set->insertBatas())
	{
		$sOrder= " ORDER BY A.NOMOR DESC";
		$statement= " AND A.JADWAL_TES_ID = ".$tempUjianPegawaiLowonganId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
		$set= new UjianPauli();
		$set->selectByTanda($tempUjianPegawaiLowonganId, $statement, $sOrder);
		// echo $set->query;exit();
		$set->firstRow();
		$reqNomor= $set->getField("NOMOR");

		echo $reqNomor;
	}
}
else
	echo $reqNomor;

?>