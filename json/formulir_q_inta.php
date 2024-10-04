<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base-portal/formulir.php");

$reqId= $userLogin->userPelamarId;

if($reqId == "")
{
	echo "autologin"; exit;
	
}

$reqJawaban= httpFilterPost("reqJawaban");
// print_r($reqJawaban);
$reqSoalId= httpFilterPost("reqSoalId");
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqTipe= httpFilterPost("reqTipe");
$reqMode= httpFilterPost("reqMode");
$reqTipeFormulir= httpFilterPost("reqTipeFormulir");

$set= new Formulir();
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->setField("TIPE_FORMULIR_ID", $reqTipeFormulir);

$set->deleteInta();
unset($set);

$tempPesertaSimpan= "";
$isianSimpan='';
// print_r($reqJawaban);exit;

for ($i = 0; $i < count($reqJawaban); $i++) {
	if ($reqJawaban[$i] == "") {
		$isianSimpan=$isianSimpan." ❌ Soal No".($i+1)." Belum Terisi<br>";
	} else {
		$set= new Formulir();
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("FORMULIR_SOAL_ID",$reqSoalId[$i]);
		$set->setField("TIPE_FORMULIR_ID", $reqTipe[$i]);
		$set->setField("JAWABAN", $reqJawaban[$i]);

		
		if($set->insert())
		{
			$tempPesertaSimpan =1;
		}
	}
}

if($tempPesertaSimpan == 1)
{
	// echo $tempPesertaId."-Data berhasil di simpan<br>".$isianSimpan;
	echo $tempPesertaId."-<center><h3><b>Data berhasil di simpan</b></h3>".$isianSimpan."</center>";
	
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