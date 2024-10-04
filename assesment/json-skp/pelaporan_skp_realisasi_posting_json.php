<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/KegiatanPersetujuan.php");
include_once("../WEB/classes/base-skp/PeriodePenilaian.php");
include_once("../WEB/classes/utils/UserLogin.php");

$kegiatan_persetujuan = new KegiatanPersetujuan();
$periode_penilaian = new PeriodePenilaian();

$reqId = httpFilterGet("reqId");
$reqTahun = httpFilterGet("reqTahun");
$reqBulan = httpFilterGet("reqBulan");


$kegiatan_persetujuan->setField("TAHUN", $reqTahun);
$kegiatan_persetujuan->setField("BULAN", $reqBulan);
$kegiatan_persetujuan->setField("PEGAWAI_ID", ValToNullDB($reqId));
$kegiatan_persetujuan->setField("FIELD", "STATUS");
$kegiatan_persetujuan->setField("FIELD_VALUE", "V");
$kegiatan_persetujuan->updateByPegawaiBulanTahun();

$pesan = "Data berhasil diposting.";	

$arrFinal = array("PESAN" => $pesan);

echo json_encode($arrFinal);
?>