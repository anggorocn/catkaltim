<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Kegiatan.php");
include_once("../WEB/classes/base-skp/KegiatanTambahan.php");
include_once("../WEB/classes/base-skp/KegiatanPersetujuan.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-skp/PeriodePenilaian.php");

$kegiatan = new Kegiatan();
$kegiatan_backup = new Kegiatan();
$kegiatan_tambahan = new KegiatanTambahan();
$kegiatan_tambahan_backup = new KegiatanTambahan();
$periode_penilaian = new PeriodePenilaian();
$kegiatan_persetujuan = new KegiatanPersetujuan();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqTahun = httpFilterPost("reqTahun");
$reqBulan = httpFilterPost("reqBulan");
$reqStatusId = httpFilterPost("reqStatusId");
$reqStatus = httpFilterPost("reqStatus");

$reqNoUrut = $_POST["reqNoUrut"];
$reqNama = $_POST["reqNama"];
$reqAK = $_POST["reqAK"];
$reqKuantitas = $_POST["reqKuantitas"];
$reqKuantitasSatuan = $_POST["reqKuantitasSatuan"];
$reqKualitas = $_POST["reqKualitas"];
$reqWaktu = $_POST["reqWaktu"];
$reqWaktuSatuan = $_POST["reqWaktuSatuan"];
$reqBiaya = $_POST["reqBiaya"];


$reqNamaTambahan = $_POST["reqNamaTambahan"];
$reqKuantitasTambahan = $_POST["reqKuantitasTambahan"];
$reqKuantitasSatuanTambahan = $_POST["reqKuantitasSatuanTambahan"];

if($reqStatusId == "")
{}
else
{
	$kegiatan_persetujuan->setField("KEGIATAN_PERSETUJUAN_ID", $reqStatusId);	
	$kegiatan_persetujuan->setField("FIELD", "STATUS");	
	$kegiatan_persetujuan->setField("FIELD_VALUE", "P");
	$kegiatan_persetujuan->updateByField();
	
	
	/* BACKUP TERLEBIH DAHULU */
	$kegiatan_backup->setField("TAHUN", $reqTahun);
	$kegiatan_backup->setField("BULAN", $reqBulan);
	$kegiatan_backup->setField("PEGAWAI_ID", $userLogin->pegawaiId);
	$kegiatan_backup->insertBackup();
	
	
	/* BACKUP TERLEBIH DAHULU */
	$kegiatan_tambahan_backup->setField("TAHUN", $reqTahun);
	$kegiatan_tambahan_backup->setField("BULAN", $reqBulan);
	$kegiatan_tambahan_backup->setField("PEGAWAI_ID", $userLogin->pegawaiId);
	$kegiatan_tambahan_backup->insertBackup();
	
}

$kegiatan->setField("BULAN", $reqBulan);
$kegiatan->setField("TAHUN", $reqTahun);
$kegiatan->setField("PEGAWAI_ID", $userLogin->pegawaiId);
if($kegiatan->delete())
{
	unset($kegiatan);	
	for($i=0;$i<count($reqNama);$i++)
	{
		$kegiatan = new Kegiatan();
		$kegiatan->setField("URUT", $i+1);
		$kegiatan->setField("NAMA", $reqNama[$i]);
		$kegiatan->setField("AK", ValToNullDB($reqAK[$i]));
		$kegiatan->setField("KUANTITAS", ValToNullDB($reqKuantitas[$i]));
		$kegiatan->setField("KUANTITAS_SATUAN", $reqKuantitasSatuan[$i]);
		$kegiatan->setField("KUALITAS", ValToNullDB($reqKualitas[$i]));
		$kegiatan->setField("WAKTU", ValToNullDB($reqWaktu[$i]));
		$kegiatan->setField("WAKTU_SATUAN", $reqWaktuSatuan[$i]);
		$kegiatan->setField("BIAYA", ValToNullDB(dotToNo($reqBiaya[$i])));
		$kegiatan->setField("TAHUN", $reqTahun);
		$kegiatan->setField("BULAN", $reqBulan);
		$kegiatan->setField("PEGAWAI_ID", $userLogin->pegawaiId);
		$kegiatan->insertPelaporan();
		unset($kegiatan);	
	}
}

$kegiatan_tambahan->setField("BULAN", $reqBulan);
$kegiatan_tambahan->setField("TAHUN", $reqTahun);
$kegiatan_tambahan->setField("PEGAWAI_ID", $userLogin->pegawaiId);
if($kegiatan_tambahan->delete())
{
	unset($kegiatan_tambahan);	
	for($i=0;$i<count($reqNamaTambahan);$i++)
	{
		$kegiatan_tambahan = new KegiatanTambahan();
		$kegiatan_tambahan->setField("NAMA", $reqNamaTambahan[$i]);
		$kegiatan_tambahan->setField("KUANTITAS", ValToNullDB($reqKuantitasTambahan[$i]));
		$kegiatan_tambahan->setField("KUANTITAS_SATUAN", $reqKuantitasSatuanTambahan[$i]);
		$kegiatan_tambahan->setField("TAHUN", $reqTahun);
		$kegiatan_tambahan->setField("BULAN", $reqBulan);
		$kegiatan_tambahan->setField("PEGAWAI_ID", $userLogin->pegawaiId);
		$kegiatan_tambahan->insertPelaporan();
		unset($kegiatan_tambahan);	
		//echo $kegiatan_tambahan->query;
	}
	
}

echo "Data berhasil disimpan.";	
?>