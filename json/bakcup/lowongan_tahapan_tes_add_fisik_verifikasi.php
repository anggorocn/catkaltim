<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganFisik.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_lowongan_fisik = new PelamarLowonganFisik();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");

$reqPelamarLowonganId= httpFilterPost("reqPelamarLowonganId");
$reqRowId= httpFilterPost("reqRowId"); 
$reqTinggiBadan= httpFilterPost("reqTinggiBadan"); 
$reqBeratBadan= httpFilterPost("reqBeratBadan"); 
$reqButaWarna= httpFilterPost("reqButaWarna"); 
$reqKeterangan= httpFilterPost("reqKeterangan"); 

$pelamar_lowongan_fisik->setField('PELAMAR_LOWONGAN_ID', $reqPelamarLowonganId);
$pelamar_lowongan_fisik->setField('TINGGI_BADAN', $reqTinggiBadan);
$pelamar_lowongan_fisik->setField('BERAT_BADAN', $reqBeratBadan);
$pelamar_lowongan_fisik->setField('BUTA_WARNA', $reqButaWarna);
$pelamar_lowongan_fisik->setField('KETERANGAN', $reqKeterangan);
	
if($reqMode == "insert")
{
	if($pelamar_lowongan_fisik->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$pelamar_lowongan_fisik->setField('PELAMAR_LOWONGAN_FISIK_ID', $reqRowId); 
	if($pelamar_lowongan_fisik->update())
		echo "Data berhasil disimpan.";	
}
?>