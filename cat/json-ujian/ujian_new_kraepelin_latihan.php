<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianNKraepelin.php");
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


$reqPakaiKraepelinId= httpFilterPost("reqPakaiKraepelinId");
$reqUjianPegawaiDaftarId= httpFilterPost("reqUjianPegawaiDaftarId");
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqJadwalTesId= httpFilterPost("reqJadwalTesId");
$reqFormulaAssesmentId= httpFilterPost("reqFormulaAssesmentId");
$reqFormulaEselonId= httpFilterPost("reqFormulaEselonId");
$reqUjianId= httpFilterPost("reqUjianId");
$reqTipeUjianId= httpFilterPost("reqTipeUjianId");
$reqUjianTahapId= httpFilterPost("reqUjianTahapId");

$reqXdata= $_POST["reqXdata"];
$reqYdata= $_POST["reqYdata"];
$reqXYdataNilai= $_POST["reqXYdataNilai"];

// print_r($reqXdata);
// exit();
// , UJIAN_KRAEPELIN_ID, 
	
	for($i=0; $i < count($reqXdata); $i++)
	{
		//echo $reqMode."-".$tempUjianBankSoalPilihanPegawaiId;exit;
		$set= new UjianNKraepelin();
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
		$set->insertLatihan();
		// echo $set->query;exit();
		// $set->setField("LAST_UPDATE_USER", ValToNullDB($userLogin->nama));
		// $set->setField("LAST_UPDATE_DATE", "NOW()");
	}

	echo "Data Berhasil di simpan";
?>