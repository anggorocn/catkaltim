<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base-portal/formulircritical.php");

$reqId= $userLogin->userPelamarId;

if($reqId == "")
{
	exit();
}

$reqJawaban= httpFilterPost("reqJawaban");
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqBulan= httpFilterPost("reqBulan");
$reqTahun= httpFilterPost("reqTahun");
$reqSoalId= httpFilterPost("reqSoalId");

$reqTopik= httpFilterPost("reqTopik");
$reqSampai= httpFilterPost("reqSampai");
$reqTanggal= httpFilterPost("reqTanggal");

$reqJawabanTambahan= httpFilterPost("reqJawabanTambahan");
$reqSoalJawabanId= httpFilterPost("reqSoalJawabanId");

$reqSoalHeaderId= httpFilterPost("reqSoalHeaderId");





// print_r($reqSoalHeaderId);exit;


 
$set= new FormulirCritical();
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->delete();
unset($set);


$tempPesertaSimpan= "";

for ($i = 0; $i < count($reqTopik); $i++) {
	if ($reqTopik[$i] == "") {
	} else {
		$set= new FormulirCritical();
		// $set->setField("FORMULIR_CRITICAL_JAWABAN_ID", $reqSoalId[$i]);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("FORMULIR_SOAL_CRITICAL_HEADER_ID", $reqSoalId[$i]);
		$set->setField("TOPIK", $reqTopik[$i]);
		$set->setField("TANGGAL",  ValToNullDB($reqTanggal[$i]));
		$set->setField("BULAN", ValToNullDB($reqBulan[$i]));
		$set->setField("TAHUN", ValToNullDB($reqTahun[$i]));
		$set->setField("SAMPAI", ValToNullDB($reqSampai[$i]));
		if($set->insert())
		{
			$tempPesertaSimpan =1;
		}
	}
}


$set= new FormulirCritical();
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->deleteJawaban();
unset($set);

for ($i = 0; $i < count($reqJawabanTambahan); $i++) {
	if ($reqJawabanTambahan[$i] == "") {
	} else {
		$set= new FormulirCritical();
		// $set->setField("FORMULIR_CRITICAL_JAWABAN_ID", $reqSoalId[$i]);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID", $reqSoalJawabanId[$i]);
		$set->setField("FORMULIR_SOAL_CRITICAL_HEADER_ID", $reqSoalHeaderId[$i]);
		$set->setField("JAWABAN", $reqJawabanTambahan[$i]);
		if($set->insertJawaban())
		{
			$tempPesertaSimpan =1;
		}
	}
}

if($tempPesertaSimpan == 1)
{
	echo $tempPesertaId."-Data berhasil di simpan";
}
else
{
	echo $tempPesertaId."-Data gagal di simpan";
}

// else
// 	echo "xxx-Data gagal disimpan.";
// echo $set->query;exit;
unset($set);
?>