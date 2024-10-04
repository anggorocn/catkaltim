<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarPengalaman.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_pengalaman = new PelamarPengalaman();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqRowId = httpFilterPost("reqRowId");

$reqJabatan= httpFilterPost("reqJabatan");
$reqPerusahaan= httpFilterPost("reqPerusahaan");
$reqDurasi= httpFilterPost("reqDurasi");
$reqTahun= httpFilterPost("reqTahun");
$reqTanggalMasuk= httpFilterPost("reqTanggalMasuk");

if($reqDurasi == "" && $reqTahun == "")
{
	$reqDurasi = 0;
	$reqTahun = 0;
}
elseif($reqDurasi == "")
	$reqDurasi = 0;
elseif($reqTahun == "")
	$reqTahun = 0;
else
{
	$reqDurasi = $reqDurasi;
	$reqTahun = $reqTahun;
}

$pelamar_pengalaman->setField('JABATAN', $reqJabatan);
$pelamar_pengalaman->setField('PERUSAHAAN', $reqPerusahaan);
$pelamar_pengalaman->setField('DURASI', $reqDurasi);
$pelamar_pengalaman->setField('TANGGAL_MASUK', dateToDBCheck($reqTanggalMasuk));
$pelamar_pengalaman->setField('TAHUN', $reqTahun);
$pelamar_pengalaman->setField('PELAMAR_ID', $userLogin->userPelamarId);

if($reqMode == "insert")
{
	if($pelamar_pengalaman->insert()){
		$reqRowId= $pelamar_pengalaman->id;
		echo "Data berhasil disimpan.";
	}
}
else
{	
	$pelamar_pengalaman->setField('PELAMAR_PENGALAMAN_ID', $reqRowId);

	if($pelamar_pengalaman->update()){
		echo "Data berhasil disimpan.";
	}
	//echo $pelamar_pengalaman->query;
}
?>