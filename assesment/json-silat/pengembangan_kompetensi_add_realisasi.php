<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PegawaiHcdp.php");
include_once("../WEB/classes/base/PegawaiHcdpDetil.php");
include_once("../WEB/classes/base/PelatihanHcdp.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqRowId= httpFilterPost("reqRowId");
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqFormulaId= httpFilterPost("reqFormulaId");
$reqJumlahJp= httpFilterPost("reqJumlahJp");
// $reqJp= httpFilterPost("reqJp");
// $reqTahun= httpFilterPost("reqTahun");

$reqAtributId= $_POST["reqAtributId"];
$reqBiaya= $_POST["reqBiaya"];
$reqWaktuPelaksana= $_POST["reqWaktuPelaksana"];
$reqPenyelenggara= $_POST["reqPenyelenggara"];
$reqSumberDana= $_POST["reqSumberDana"];
$reqMateriPengembangan= $_POST["reqMateriPengembangan"];
$reqJp= $_POST["reqJp"];
$reqStatus= $_POST["reqStatus"];
$reqAlasanPengajuan= $_POST["reqAlasanPengajuan"];

$setdetil= new PegawaiHcdpDetil();
$setdetil->setField("PEGAWAI_HCDP_ID", $reqRowId);
$setdetil->deleterealisasi();
unset($setdetil);

for($i=0; $i < count($reqAtributId); $i++)
{
	$setdetil= new PegawaiHcdpDetil();
	$setdetil->setField("PEGAWAI_HCDP_ID", $reqRowId);
	$setdetil->setField("PEGAWAI_ID", $reqPegawaiId);
	$setdetil->setField("ATRIBUT_ID", $reqAtributId[$i]);
	$setdetil->setField("JP", ValToNullDB($reqJp[$i]));
	$setdetil->setField("BIAYA", ValToNullDB(dotToNo($reqBiaya[$i])));
	$setdetil->setField("WAKTU_PELAKSANA", setQuote($reqWaktuPelaksana[$i]));
	$setdetil->setField("PENYELENGGARA", setQuote($reqPenyelenggara[$i]));
	$setdetil->setField("SUMBER_DANA", setQuote($reqSumberDana[$i]));
	$setdetil->setField("MATERI_PENGEMBANGAN", setQuote($reqMateriPengembangan[$i]));
	$setdetil->setField("STATUS", setQuote($reqStatus[$i]));
	$setdetil->setField("ALASAN_PENGAJUAN", setQuote($reqAlasanPengajuan[$i]));
	$setdetil->insertrealisasi();
	// echo $setdetil->query;exit;
	unset($setdetil);
}

$mode = 'Data berhasil disimpan';
echo $reqId."-".$mode;
?>