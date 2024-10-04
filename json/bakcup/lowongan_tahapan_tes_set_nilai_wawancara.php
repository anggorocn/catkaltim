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

$reqNilai1 = httpFilterPost("reqNilai1");
$reqNilai2 = httpFilterPost("reqNilai2");
$reqNilai3 = httpFilterPost("reqNilai3");
$reqNilai4 = httpFilterPost("reqNilai4");
$reqNilai5 = httpFilterPost("reqNilai5");

$reqRekom1 = httpFilterPost("reqRekom1");
$reqRekom2 = httpFilterPost("reqRekom2");
$reqRekom3 = httpFilterPost("reqRekom3");
$reqRekom4 = httpFilterPost("reqRekom4");
$reqRekom5 = httpFilterPost("reqRekom5");

$reqTotalNilai = httpFilterPost("reqTotalNilai");
$reqTotalRekom = httpFilterPost("reqTotalRekom");

$pembagi_nilai=0;
if($reqNilai1 == ""){}
else
{
	$pembagi_nilai++;
}
if($reqNilai2 == ""){}
else
{
	$pembagi_nilai++;
}
if($reqNilai3 == ""){}
else
{
	$pembagi_nilai++;
}
if($reqNilai4 == ""){}
else
{
	$pembagi_nilai++;
}
if($reqNilai5 == ""){}
else
{
	$pembagi_nilai++;
}

$pembagi_rekom=0;
if($reqRekom1 == ""){}
else
{
	$pembagi_rekom++;
}
if($reqRekom2 == ""){}
else
{
	$pembagi_rekom++;
}
if($reqRekom3 == ""){}
else
{
	$pembagi_rekom++;
}
if($reqRekom4 == ""){}
else
{
	$pembagi_rekom++;
}
if($reqRekom5 == ""){}
else
{
	$pembagi_rekom++;
}

$rata_nilai = $reqTotalNilai/$pembagi_nilai;
$rata_rekom = $reqTotalRekom/$pembagi_rekom;
$rata_nilai = number_format($rata_nilai,2);
$rata_rekom = number_format($rata_rekom,2);

$pelamar_lowongan_tahapan = new PelamarLowonganTahapan();

$pelamar_lowongan_tahapan->setField("PELAMAR_ID", $reqPelamarId);
$pelamar_lowongan_tahapan->setField("LOWONGAN_ID", $reqLowonganId);
$pelamar_lowongan_tahapan->setField("LOWONGAN_TAHAPAN_ID", $reqLowonganTahapanId);
$pelamar_lowongan_tahapan->setField("WAWANCARA_NILAI1", ValToNullDB($reqNilai1));
$pelamar_lowongan_tahapan->setField("WAWANCARA_NILAI2", ValToNullDB($reqNilai2));
$pelamar_lowongan_tahapan->setField("WAWANCARA_NILAI3", ValToNullDB($reqNilai3));
$pelamar_lowongan_tahapan->setField("WAWANCARA_NILAI4", ValToNullDB($reqNilai4));
$pelamar_lowongan_tahapan->setField("WAWANCARA_NILAI5", ValToNullDB($reqNilai5));
$pelamar_lowongan_tahapan->setField("WAWANCARA_REKOM1", ValToNullDB($reqRekom1));
$pelamar_lowongan_tahapan->setField("WAWANCARA_REKOM2", ValToNullDB($reqRekom2));
$pelamar_lowongan_tahapan->setField("WAWANCARA_REKOM3", ValToNullDB($reqRekom3));
$pelamar_lowongan_tahapan->setField("WAWANCARA_REKOM4", ValToNullDB($reqRekom4));
$pelamar_lowongan_tahapan->setField("WAWANCARA_REKOM5", ValToNullDB($reqRekom5));
$pelamar_lowongan_tahapan->setField("WAWANCARA_RATA_NILAI", ValToNullDB($rata_nilai));
$pelamar_lowongan_tahapan->setField("WAWANCARA_RATA_REKOM", ValToNullDB($rata_rekom));
$pelamar_lowongan_tahapan->setField("LAST_UPDATE_USER", $userLogin->nama);


if($pelamar_lowongan_tahapan->updateNilaiWawancara())
	echo "Set nilai berhasil.";
else
	echo "Set nilai gagal.";
		

?>