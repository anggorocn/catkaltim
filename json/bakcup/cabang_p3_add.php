<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/CabangP3.php");
include_once("../WEB/classes/utils/UserLogin.php");

$cabang_p3= new CabangP3();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");

$reqKelasPelabuhan= httpFilterPost("reqKelasPelabuhan");
$reqKodeCabang= httpFilterPost("reqKodeCabang");
$reqNama= httpFilterPost("reqNama");
$reqKategoriJabatanPs= httpFilterPost("reqKategoriJabatanPs");
$reqKategoriJabatanOps= httpFilterPost("reqKategoriJabatanOps");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqAlamat= httpFilterPost("reqAlamat");
$reqTelepon= httpFilterPost("reqTelepon");
$reqWilayahId= httpFilterPost("reqWilayahId");

$cabang_p3->setField('CABANG_P3_ID', $reqId);
$cabang_p3->setField('WILAYAH_ID', $reqWilayahId);
$cabang_p3->setField('KELAS_PELABUHAN', $reqKelasPelabuhan);
$cabang_p3->setField('KODE_CABANG', $reqKodeCabang);
$cabang_p3->setField('NAMA', $reqNama);
$cabang_p3->setField('KETERANGAN', $reqKeterangan);
$cabang_p3->setField('ALAMAT', $reqAlamat);
$cabang_p3->setField('TELEPON', $reqTelepon);
	
if($reqMode == "insert")
{
	$cabang_p3->setField("LAST_CREATE_USER", $userLogin->nama);
	$cabang_p3->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	if($cabang_p3->insert()){
		echo "Data berhasil disimpan.";
	}
	//echo $cabang_p3->query;
}
else
{

	$cabang_p3->setField("LAST_UPDATE_USER", $userLogin->nama);
	$cabang_p3->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	if($cabang_p3->update()){
		echo "Data berhasil disimpan.";
	}
}
?>