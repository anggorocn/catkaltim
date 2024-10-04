<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarKeluarga.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_keluarga = new PelamarKeluarga();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqRowId = httpFilterPost("reqRowId");

$reqHubunganKeluargaId= httpFilterPost("reqHubunganKeluargaId");
$reqStatusKawin= httpFilterPost("reqStatusKawin");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqStatusTunjangan= httpFilterPost("reqStatusTunjangan");
$reqNama= httpFilterPost("reqNama");
$reqTanggalWafat= httpFilterPost("reqTanggalWafat");
$reqTanggalLahir= httpFilterPost("reqTanggalLahir");
$reqStatusTanggung= httpFilterPost("reqStatusTanggung");
$reqTempatLahir= httpFilterPost("reqTempatLahir");
$reqPendidikanId= httpFilterPost("reqPendidikanId");
$reqPekerjaan= httpFilterPost("reqPekerjaan");
$reqKesehatan = httpFilterPost("reqKesehatan");
$reqKesehatanTanggal = httpFilterPost("reqKesehatanTanggal");
$reqKesehatanFaskes = httpFilterPost("reqKesehatanFaskes");
$reqKtpNo = httpFilterPost("reqKtpNo");

$reqAlamatDomisili= httpFilterPost("reqAlamatDomisili");
$reqNoTelepon= httpFilterPost("reqNoTelepon");

$pelamar_keluarga->setField('HUBUNGAN_KELUARGA_ID', $reqHubunganKeluargaId);
$pelamar_keluarga->setField('STATUS_KAWIN', setNULL($reqStatusKawin));
$pelamar_keluarga->setField('JENIS_KELAMIN', $reqJenisKelamin);
$pelamar_keluarga->setField('STATUS_TUNJANGAN', setNULL($reqStatusTunjangan));
$pelamar_keluarga->setField('NAMA', $reqNama);
$pelamar_keluarga->setField('TANGGAL_WAFAT', dateToDBCheck($reqTanggalWafat));
$pelamar_keluarga->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggalLahir));
$pelamar_keluarga->setField('STATUS_TANGGUNG', setNULL($reqStatusTanggung));
$pelamar_keluarga->setField('TEMPAT_LAHIR', $reqTempatLahir);
$pelamar_keluarga->setField('PENDIDIKAN_ID', $reqPendidikanId);
$pelamar_keluarga->setField('PEKERJAAN', $reqPekerjaan);
$pelamar_keluarga->setField('PELAMAR_KELUARGA_ID', $reqRowId);
$pelamar_keluarga->setField('PELAMAR_ID', $userLogin->userPelamarId);
$pelamar_keluarga->setField('KESEHATAN_NO', $reqKesehatan);
$pelamar_keluarga->setField('KESEHATAN_TANGGAL', dateToDBCheck($reqKesehatanTanggal));
$pelamar_keluarga->setField('KESEHATAN_FASKES', $reqKesehatanFaskes);
$pelamar_keluarga->setField('KTP_NO', $reqKtpNo);

$pelamar_keluarga->setField("ALAMAT_DOMISILI", $reqAlamatDomisili);
$pelamar_keluarga->setField("NO_TELEPON", $reqNoTelepon);

if($reqMode == "insert")
{
	$pelamar_keluarga->setField("LAST_CREATE_USER", $userLogin->nama);
	$pelamar_keluarga->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	if($pelamar_keluarga->insert()){
		$reqRowId= $pelamar_keluarga->id;
		echo "Data berhasil disimpan.";
	}
}
else
{
	$pelamar_keluarga->setField("LAST_UPDATE_USER", $userLogin->nama);
	$pelamar_keluarga->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	if($pelamar_keluarga->update()){
		echo "Data berhasil disimpan.";
	}
}
?>