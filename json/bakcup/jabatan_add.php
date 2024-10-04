<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Jabatan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$jabatan = new Jabatan();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKode= httpFilterPost("reqKode");
$reqNoUrut= httpFilterPost("reqNoUrut");
$reqKelas= httpFilterPost("reqKelas");
$reqPPH= httpFilterPost("reqPPH");
$reqNama= httpFilterPost("reqNama");
$reqStatus= httpFilterPost("reqStatus");
$reqKelompok= httpFilterPost("reqKelompok");
$reqNamaSlip = httpFilterPost("reqNamaSlip");
$reqMaksUsia = httpFilterPost("reqMaksUsia");
$reqKeterangan = httpFilterPost("reqKeterangan"); 
$reqCabangP3Id= httpFilterPost("reqCabangP3Id"); 

$reqKandidatPengalaman= httpFilterPost("reqKandidatPengalaman");
$reqKandidatPendidikanKode= httpFilterPost("reqKandidatPendidikanKode");
$reqKandidatUsia= httpFilterPost("reqKandidatUsia");

if($reqMode == "insert")
{
	$jabatan->setField('CABANG_P3_ID', $reqCabangP3Id);
	$jabatan->setField('KODE', $reqKode);
	$jabatan->setField('NAMA', $reqNama);
	$jabatan->setField('KETERANGAN', $reqKeterangan);
	$jabatan->setField('STATUS', '1');
	$jabatan->setField('KELOMPOK', 'O');
	if($jabatan->insert())
		echo "Data berhasil disimpan.";
	//echo $jabatan->query;
}
else
{
	$jabatan->setField('CABANG_P3_ID', $reqCabangP3Id);
	$jabatan->setField('JABATAN_ID', $reqId); 
	$jabatan->setField('KODE', $reqKode);
	$jabatan->setField('NAMA', $reqNama);
	$jabatan->setField('KETERANGAN', $reqKeterangan);
	$jabatan->setField('STATUS', '1');
	$jabatan->setField('KELOMPOK', 'O');
	if($jabatan->update())
		echo "Data berhasil disimpan.";
	
}
?>