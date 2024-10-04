<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarSertifikat.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_sertifikat = new PelamarSertifikat();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqRowId= httpFilterPost("reqRowId");

$reqNama = httpFilterPost("reqNama");
$reqTanggalTerbit 			= httpFilterPost("reqTanggalTerbit");
$reqTanggalKadaluarsa 		= httpFilterPost("reqTanggalKadaluarsa");
$reqGroup= httpFilterPost("reqGroup");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqSertifikatId = httpFilterPost("reqSertifikatId");
$reqPegawaiSertifikatId = httpFilterPost("reqPegawaiSertifikatId");

$pelamar_sertifikat->setField("NAMA", $reqNama);
$pelamar_sertifikat->setField("TANGGAL_TERBIT", dateToDBCheck($reqTanggalTerbit));
$pelamar_sertifikat->setField("TANGGAL_KADALUARSA", dateToDBCheck($reqTanggalKadaluarsa));
$pelamar_sertifikat->setField("GROUP_SERTIFIKAT", $reqGroup);
$pelamar_sertifikat->setField("KETERANGAN", $reqKeterangan);
$pelamar_sertifikat->setField("SERTIFIKAT_ID", ValToNullDB($reqSertifikatId));
$pelamar_sertifikat->setField("PELAMAR_ID", $userLogin->userPelamarId);

if($reqMode == "insert")
{
	$pelamar_sertifikat->setField("LAST_CREATE_USER", $userLogin->nama);
	$pelamar_sertifikat->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	if($pelamar_sertifikat->insert())
	{
		echo "Data berhasil disimpan.";
	}
}
else
{
	$pelamar_sertifikat->setField("PELAMAR_SERTIFIKAT_ID", $reqRowId);
	$pelamar_sertifikat->setField("LAST_UPDATE_USER", $userLogin->nama);
	$pelamar_sertifikat->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	if($pelamar_sertifikat->update())
	{
		echo "Data berhasil disimpan.";
	}
}
?>