<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/PeriodePenilaian.php");
include_once("../WEB/classes/utils/UserLogin.php");

$periode_penilaian = new PeriodePenilaian();

$reqId = httpFilterPost("reqId");
$reqPeriode = httpFilterPost("reqPeriode");

$tanggalAwal = "01-01-".$reqPeriode;
$tanggalAkhir = "31-12-".$reqPeriode;
	
$periode_penilaian->setField("TAHUN", $reqPeriode);
$periode_penilaian->setField("TANGGAL_AWAL", dateToDBCheck($tanggalAwal));
$periode_penilaian->setField("TANGGAL_AKHIR", dateToDBCheck($tanggalAkhir));
if($periode_penilaian->insert())
	echo "Data berhasil disimpan.";
//echo $periode_penilaian->query;
?>