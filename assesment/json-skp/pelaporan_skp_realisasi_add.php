<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Kegiatan.php");
include_once("../WEB/classes/base-skp/KegiatanTambahan.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-skp/PeriodePenilaian.php");

$kegiatan = new Kegiatan();
$kegiatan_tambahan = new KegiatanTambahan();
$periode_penilaian = new PeriodePenilaian();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqTahun = httpFilterPost("reqTahun");
$reqBulan = httpFilterPost("reqBulan");

$reqNoUrut = $_POST["reqNoUrut"];
$reqNama = $_POST["reqNama"];
$reqAK = $_POST["reqAK"];
$reqKuantitas = $_POST["reqKuantitas"];
$reqKuantitasSatuan = $_POST["reqKuantitasSatuan"];
$reqKualitas = $_POST["reqKualitas"];
$reqWaktu = $_POST["reqWaktu"];
$reqWaktuSatuan = $_POST["reqWaktuSatuan"];
$reqBiaya = $_POST["reqBiaya"];
$reqKegiatanId = $_POST["reqKegiatanId"];


$reqNamaTambahan = $_POST["reqNamaTambahan"];
$reqKuantitasTambahan = $_POST["reqKuantitasTambahan"];
$reqKuantitasSatuanTambahan = $_POST["reqKuantitasSatuanTambahan"];
$reqKegiatanTambahanId = $_POST["reqKegiatanTambahanId"];

for($i=0;$i<count($reqKegiatanId);$i++)
{
	$kegiatan = new Kegiatan();
	$kegiatan->setField("KUANTITAS_REALISASI", ValToNullDB($reqKuantitas[$i]));
	$kegiatan->setField("KUALITAS_REALISASI", ValToNullDB($reqKualitas[$i]));
	$kegiatan->setField("WAKTU_REALISASI", ValToNullDB($reqWaktu[$i]));
	$kegiatan->setField("BIAYA_REALISASI", ValToNullDB(dotToNo($reqBiaya[$i])));
	$kegiatan->setField("KEGIATAN_ID", $reqKegiatanId[$i]);
	$kegiatan->updatePelaporan();
	//echo $kegiatan->query;
	unset($kegiatan);	
}

for($i=0;$i<count($reqKegiatanTambahanId);$i++)
{
	$kegiatan_tambahan = new KegiatanTambahan();
	$kegiatan_tambahan->setField("KUANTITAS_REALISASI", ValToNullDB($reqKuantitasTambahan[$i]));
	$kegiatan_tambahan->setField("KEGIATAN_TAMBAHAN_ID", $reqKegiatanTambahanId[$i]);
	$kegiatan_tambahan->updatePelaporan();
	//echo $kegiatan_tambahan->query;
	unset($kegiatan_tambahan);	
}

echo "Data berhasil disimpan.";	
?>