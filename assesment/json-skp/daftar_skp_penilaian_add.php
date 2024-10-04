<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/KegiatanPersetujuan.php");
include_once("../WEB/classes/base-skp/Kegiatan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$kegiatan_persetujuan = new KegiatanPersetujuan();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqTahun = httpFilterPost("reqTahun");
$reqBulan = httpFilterPost("reqBulan");

$reqKegiatanId = $_POST["reqKegiatanId"];
$reqKualitas = $_POST["reqKualitas"];

for($i=0;$i<count($reqKegiatanId);$i++)
{
	$kegiatan = new Kegiatan();
	$kegiatan->setField("KEGIATAN_ID", $reqKegiatanId[$i]);
	$kegiatan->setField("FIELD", "KUALITAS_REALISASI");
	$kegiatan->setField("FIELD_VALUE", $reqKualitas[$i]);
	$kegiatan->updateByField();
	unset($kegiatan);	
}

$kegiatan_persetujuan->setField("TAHUN", $reqTahun);
$kegiatan_persetujuan->setField("BULAN", $reqBulan);
$kegiatan_persetujuan->setField("PEGAWAI_ID", ValToNullDB($reqId));
$kegiatan_persetujuan->setField("FIELD", "STATUS");
$kegiatan_persetujuan->setField("FIELD_VALUE", "F");
$kegiatan_persetujuan->updateByPegawaiBulanTahun();

echo "Data berhasil disimpan.";	
?>