<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganTahapan.php");
include_once("../WEB/classes/base/LowonganTahapan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$reqLowonganTahapanId = httpFilterPost("reqLowonganTahapanId");
$reqPelamarId = httpFilterPost("reqPelamarId");
$reqLowonganId= httpFilterPost("reqLowonganId");

$reqNilai = httpFilterPost("reqNilai");

$reqRekom = httpFilterPost("reqRekom");

$pelamar_lowongan_tahapan = new PelamarLowonganTahapan();

$pelamar_lowongan_tahapan->setField("PELAMAR_ID", $reqPelamarId);
$pelamar_lowongan_tahapan->setField("LOWONGAN_ID", $reqLowonganId);
$pelamar_lowongan_tahapan->setField("LOWONGAN_TAHAPAN_ID", $reqLowonganTahapanId);
$pelamar_lowongan_tahapan->setField("PSIKOTES_NILAI", ValToNullDB($reqNilai));
$pelamar_lowongan_tahapan->setField("PSIKOTES_REKOM", ValToNullDB($reqRekom));
$pelamar_lowongan_tahapan->setField("LAST_UPDATE_USER", $userLogin->nama);
if($pelamar_lowongan_tahapan->updateNilaiPsikotes())
	echo "Set nilai berhasil.";
else
	echo "Set nilai gagal.";

		

?>