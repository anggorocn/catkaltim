<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarPelatihan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_pelatihan = new PelamarPelatihan();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqRowId = httpFilterPost("reqRowId");

$reqWaktu= httpFilterPost("reqWaktu");
$reqJenis= httpFilterPost("reqJenis");
$reqJumlah= httpFilterPost("reqJumlah");
$reqPelatih= httpFilterPost("reqPelatih");
$reqTahun= httpFilterPost("reqTahun");
$reqNomorLisensi= httpFilterPost("reqNomorLisensi");
$reqJenisLisensi= httpFilterPost("reqJenisLisensi");
$reqTanggalMulai= httpFilterPost("reqTanggalMulai");
$reqTanggalSelesai= httpFilterPost("reqTanggalSelesai");

$pelamar_pelatihan->setField('WAKTU', $reqWaktu);
$pelamar_pelatihan->setField('JENIS', $reqJenis);
$pelamar_pelatihan->setField('JUMLAH', $reqJumlah);
$pelamar_pelatihan->setField('PELATIH', $reqPelatih);
$pelamar_pelatihan->setField('TAHUN', $reqTahun);
$pelamar_pelatihan->setField('NOMOR_LISENSI', $reqNomorLisensi);
$pelamar_pelatihan->setField('JENIS_LISENSI', $reqJenisLisensi);
$pelamar_pelatihan->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
$pelamar_pelatihan->setField('TANGGAL_SELESAI', dateToDBCheck($reqTanggalSelesai));
$pelamar_pelatihan->setField('PELAMAR_ID', $userLogin->userPelamarId);

if($reqMode == "insert")
{
	if($pelamar_pelatihan->insert()){
		$reqRowId= $pelamar_pelatihan->id;
		echo "Data berhasil disimpan.";
	}
	//echo $pelamar_pelatihan->query;
}
else
{	
	$pelamar_pelatihan->setField('PELAMAR_PELATIHAN_ID', $reqRowId);

	if($pelamar_pelatihan->update()){
		echo "Data berhasil disimpan.";
	}
	//echo $pelamar_pelatihan->query;
}
?>