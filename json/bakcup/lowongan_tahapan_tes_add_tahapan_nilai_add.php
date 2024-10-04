<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganTahapanNilai.php");
include_once("../WEB/classes/utils/UserLogin.php");

$reqLowonganTahapanId = httpFilterPost("reqLowonganTahapanId");
$reqPelamarId = httpFilterPost("reqPelamarId");
$reqLowonganId= httpFilterPost("reqLowonganId");
$reqTahapanTesId= httpFilterPost("reqTahapanTesId");

$reqPenilaian = $_POST["reqPenilaian"];
$reqTahapanTesNilaiId = $_POST["reqTahapanTesNilaiId"];
$reqPenilaiKe = $_POST["reqPenilaiKe"];

$pelamar_lowongan_tahapan_nilai = new PelamarLowonganTahapanNilai();
$pelamar_lowongan_tahapan_nilai->setField("PELAMAR_ID", $reqPelamarId);
$pelamar_lowongan_tahapan_nilai->setField("LOWONGAN_ID", $reqLowonganId);
$pelamar_lowongan_tahapan_nilai->setField("LOWONGAN_TAHAPAN_ID", $reqLowonganTahapanId);
$pelamar_lowongan_tahapan_nilai->deleteData();

for($i=0;$i<count($reqPenilaian);$i++)							 
{
	$pelamar_lowongan_tahapan_nilai = new PelamarLowonganTahapanNilai();
	
	$pelamar_lowongan_tahapan_nilai->setField("PELAMAR_ID", $reqPelamarId);
	$pelamar_lowongan_tahapan_nilai->setField("LOWONGAN_ID", $reqLowonganId);
	$pelamar_lowongan_tahapan_nilai->setField("LOWONGAN_TAHAPAN_ID", $reqLowonganTahapanId);
	$pelamar_lowongan_tahapan_nilai->setField("TAHAPAN_TES_NILAI_ID", $reqTahapanTesNilaiId[$i]);
	$pelamar_lowongan_tahapan_nilai->setField("PENILAI_KE", $reqPenilaiKe[$i]);
	$pelamar_lowongan_tahapan_nilai->setField("NILAI", $reqPenilaian[$i]);
	$pelamar_lowongan_tahapan_nilai->setField("LAST_CREATE_USER", $userLogin->nama);
	$pelamar_lowongan_tahapan_nilai->insert();
	
	unset($pelamar_lowongan_tahapan_nilai);
}

echo "Set nilai berhasil.";
		

?>