<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarPendidikan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_pendidikan = new PelamarPendidikan();
$user_login = new UserLogin();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqRowId = httpFilterPost("reqRowId");

$reqPendidikanId= httpFilterPost("reqPendidikanId");
$reqPendidikanBiayaId= httpFilterPost("reqPendidikanBiayaId");
$reqNama= httpFilterPost("reqNama");
$reqKota= httpFilterPost("reqKota");
$reqUniversitasId= httpFilterPost("reqUniversitasId");
$reqTanggalIjasah= httpFilterPost("reqTanggalIjasah");
$reqLulus= httpFilterPost("reqLulus");
$reqNoIjasah= httpFilterPost("reqNoIjasah");
$reqTtdIjazah= httpFilterPost("reqTtdIjazah");
$reqJurusan= httpFilterPost("reqJurusan");
$reqJurusanId= httpFilterPost("reqJurusanId");
$reqTanggalAcc= httpFilterPost("reqTanggalAcc");

$pelamar_pendidikan->setField('PENDIDIKAN_ID', $reqPendidikanId);
$pelamar_pendidikan->setField('PENDIDIKAN_BIAYA_ID', ValToNullDB($reqPendidikanBiayaId));
$pelamar_pendidikan->setField('NAMA', $reqNama);
$pelamar_pendidikan->setField('KOTA', $reqKota);
$pelamar_pendidikan->setField('UNIVERSITAS_ID', ValToNullDB($reqUniversitasId));
$pelamar_pendidikan->setField('TANGGAL_IJASAH', dateToDBCheck($reqTanggalIjasah));
$pelamar_pendidikan->setField('LULUS', $reqLulus);
$pelamar_pendidikan->setField('NO_IJASAH', $reqNoIjasah);
$pelamar_pendidikan->setField('TTD_IJASAH', $reqTtdIjazah);
$pelamar_pendidikan->setField('JURUSAN', $reqJurusan);
$pelamar_pendidikan->setField('JURUSAN_ID', ValToNullDB($reqJurusanId));
$pelamar_pendidikan->setField('TANGGAL_ACC', dateToDBCheck($reqTanggalAcc));
$pelamar_pendidikan->setField('PELAMAR_PENDIDIKAN_ID', $reqRowId);
$pelamar_pendidikan->setField('PELAMAR_ID', $userLogin->userPelamarId);
//echo $reqMode."asd";exit;
if($reqMode == "insert")
{
	$pelamar_pendidikan->setField("LAST_CREATE_USER", $userLogin->nama);
	$pelamar_pendidikan->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	if($pelamar_pendidikan->insert()){
		$reqRowId= $pelamar_pendidikan->id;
		echo "Data berhasil disimpan.";
	}
	//echo $pelamar_pendidikan->query;
}
else
{
	$pelamar_pendidikan->setField("LAST_UPDATE_USER", $userLogin->nama);
	$pelamar_pendidikan->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	if($pelamar_pendidikan->update()){
		echo "Data berhasil disimpan.";
	}
	//echo $pelamar_pendidikan->query;
}
?>