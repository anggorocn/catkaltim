<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganKesemaptaan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_lowongan_kesemaptaan = new PelamarLowonganKesemaptaan();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");

$reqPelamarLowonganId= httpFilterPost("reqPelamarLowonganId");
$reqRowId= httpFilterPost("reqRowId"); 
$reqLari= httpFilterPost("reqLari"); 
$reqShuttleRun= httpFilterPost("reqShuttleRun"); 
$reqPushUp= httpFilterPost("reqPushUp"); 
$reqPullUp= httpFilterPost("reqPullUp"); 
$reqSitUp= httpFilterPost("reqSitUp"); 
$reqKeterangan= httpFilterPost("reqKeterangan"); 

$pelamar_lowongan_kesemaptaan->setField('PELAMAR_LOWONGAN_ID', $reqPelamarLowonganId);
$pelamar_lowongan_kesemaptaan->setField('LARI', $reqLari);
$pelamar_lowongan_kesemaptaan->setField('SHUTTLE_RUN', $reqShuttleRun);
$pelamar_lowongan_kesemaptaan->setField('PUSH_UP', $reqPushUp);
$pelamar_lowongan_kesemaptaan->setField('PULL_UP', $reqPullUp);
$pelamar_lowongan_kesemaptaan->setField('SIT_UP', $reqSitUp);
$pelamar_lowongan_kesemaptaan->setField('KETERANGAN', $reqKeterangan);
	
if($reqMode == "insert")
{
	if($pelamar_lowongan_kesemaptaan->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$pelamar_lowongan_kesemaptaan->setField('PELAMAR_LOWONGAN_KESEMAPTAAN_ID', $reqRowId); 
	if($pelamar_lowongan_kesemaptaan->update())
		echo "Data berhasil disimpan.";	
}
?>