<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/KegiatanPersetujuan.php");
include_once("../WEB/classes/base-skp/PeriodePenilaian.php");
include_once("../WEB/classes/utils/UserLogin.php");

$kegiatan_persetujuan = new KegiatanPersetujuan();
$kegiatan_persetujuan_delete = new KegiatanPersetujuan();
$periode_penilaian = new PeriodePenilaian();

$reqPegawaiId = httpFilterPost("reqPegawaiId");
$reqKeterangan = httpFilterPost("reqKeterangan");
$reqStatus = httpFilterPost("reqStatus");
$reqTahun = $periode_penilaian->getMaxTahun();
$reqBulan = httpFilterPost("reqBulan");


$kegiatan_persetujuan_delete->setField("PEGAWAI_ID", ValToNullDB($reqPegawaiId));
$kegiatan_persetujuan_delete->setField("TAHUN", $reqTahun);
$kegiatan_persetujuan_delete->setField("BULAN", $reqBulan);
$kegiatan_persetujuan_delete->delete();


$kegiatan_persetujuan->setField("PEGAWAI_ID", ValToNullDB($reqPegawaiId));
$kegiatan_persetujuan->setField("TAHUN", $reqTahun);
$kegiatan_persetujuan->setField("BULAN", $reqBulan);
$kegiatan_persetujuan->setField("PEGAWAI_ID_PERSETUJUAN", ValToNullDB($userLogin->pegawaiId));
$kegiatan_persetujuan->setField("TANGGAL", "SYSDATE");
$kegiatan_persetujuan->setField("ALASAN", $reqKeterangan);
$kegiatan_persetujuan->setField("STATUS", $reqStatus);
$kegiatan_persetujuan->insert();

echo "Data berhasil disimpan.";	
?>