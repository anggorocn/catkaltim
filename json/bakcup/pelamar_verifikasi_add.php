<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_verifikasi = new Pelamar();

$reqId = httpFilterGet("reqId");
$reqMode = httpFilterGet("reqMode");

if($reqMode == "verifikasi")
{			
	$pelamar_verifikasi->setField('VERIFIKASI', 1);
	$pelamar_verifikasi->setField('PELAMAR_ID', $reqId);
	$pelamar_verifikasi->setField('LAST_VERIFIED_USER', $userLogin->nama);
	$pelamar_verifikasi->setField('LAST_VERIFIED_DATE', "CURRENT_DATE"); 	
	if($pelamar_verifikasi->verifikasi())
		echo "Data berhasil diverifikasi.";
}
?>