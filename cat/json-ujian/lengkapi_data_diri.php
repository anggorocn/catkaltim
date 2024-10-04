<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Pegawai.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

date_default_timezone_set('Asia/Jakarta');

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;
// echo $ujianPegawaiJadwalTesId;exit();

$tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$tempUjianId= $ujianPegawaiUjianId;

$reqNama= httpFilterPost("reqNama");
$reqEmail= httpFilterPost("reqEmail");
// echo $reqEmail;exit;
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqTglLahir= httpFilterPost("reqTglLahir");
$reqPendidikan= httpFilterPost("reqPendidikan");

if($tempPegawaiId == ""){}
else
{
	$set= new Pegawai();
	$set->setField("NAMA", $reqNama);
	$set->setField("EMAIL", $reqEmail);
	$set->setField("TGL_LAHIR", dateToDBCheck($reqTglLahir));
	$set->setField("JENIS_KELAMIN", $reqJenisKelamin);
	$set->setField("PENDIDIKAN", $reqPendidikan);
	$set->setField("PEGAWAI_ID", $tempPegawaiId);
	
	$tempStatusSimpan= "";
	//kalau tidak ada maka simpan
	if($tempPegawaiId == ""){}
	else
	{
		if($set->update())
		$tempStatusSimpan= 1;
	}
	// echo $set->query;exit;
	unset($set);
	
	if($tempStatusSimpan == "1")
	{
		echo "1-Data berhasil disimpan";
	}
	else
	{
		echo "2-Data gagal disimpan";
	}
}
?>