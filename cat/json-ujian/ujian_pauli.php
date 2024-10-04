<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPauli.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");

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


$reqPakaiPauliId= httpFilterPost("reqPakaiPauliId");

$reqUjianPegawaiDaftarId= $tempUjianPegawaiDaftarId;
$reqJadwalTesId= $ujianPegawaiJadwalTesId;
$reqFormulaAssesmentId= $ujianPegawaiFormulaAssesmentId;
$reqFormulaEselonId= $ujianPegawaiFormulaEselonId;
$reqUjianId= $tempUjianId;
$reqPegawaiId= $tempPegawaiId;
// $reqUjianPegawaiDaftarId= httpFilterPost("reqUjianPegawaiDaftarId");
// $reqPegawaiId= httpFilterPost("reqPegawaiId");
// $reqJadwalTesId= httpFilterPost("reqJadwalTesId");
// $reqFormulaAssesmentId= httpFilterPost("reqFormulaAssesmentId");
// $reqFormulaEselonId= httpFilterPost("reqFormulaEselonId");
// $reqUjianId= httpFilterPost("reqUjianId");
// $reqTipeUjianId= httpFilterPost("reqTipeUjianId");
// $reqUjianTahapId= httpFilterPost("reqUjianTahapId");

$reqTipeUjianId= 28;
$statement= " AND COALESCE(B.MENIT_SOAL,0) > 0 AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND C.TIPE_UJIAN_ID = ".$reqTipeUjianId;
$set= new UjianTahap();
$set->selectByParamsUjianPegawaiTahap(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();
$reqUjianTahapId= $set->getField("UJIAN_TAHAP_ID");

$reqXdata= $_POST["reqXdata"];
$reqYdata= $_POST["reqYdata"];
$reqXYdataNilai= $_POST["reqXYdataNilai"];

// print_r($reqXYdataNilai); exit();
// , UJIAN_KRAEPELIN_ID, 
	
	for($i=0; $i < count($reqXdata); $i++)
	{
		//echo $reqMode."-".$tempUjianBankSoalPilihanPegawaiId;exit;
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

		$set->setField("X_DATA", $reqXdata[$i]);
		$set->setField("Y_DATA", $reqYdata[$i]);
		$set->setField("NILAI", ValToNullDB($reqXYdataNilai[$i]));

		$set->setField("LAST_CREATE_USER", ValToNullDB($userLogin->nama));
		$set->setField("LAST_CREATE_DATE", "NOW()");
		$set->insert();
		// $set->setField("LAST_UPDATE_USER", ValToNullDB($userLogin->nama));
		// $set->setField("LAST_UPDATE_DATE", "NOW()");
	}

	echo "Data Berhasil di simpan";
?>