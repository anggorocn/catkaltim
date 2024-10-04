<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowongan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_lowongan = new PelamarLowongan();

$reqPelamarLowonganId = httpFilterPost("reqId");
$reqLowonganId = httpFilterPost("reqLowonganId");

//if($pelamar_lowongan->getValidasiKirimLamaran($reqLowonganId, $reqPelamarLowonganId) == 1)
//{
	$pelamar_lowongan->setField("FIELD", "TANGGAL_KIRIM");
	$pelamar_lowongan->setField("FIELD_VALUE", "CURRENT_DATE");
	$pelamar_lowongan->setField("PELAMAR_LOWONGAN_ID", $reqPelamarLowonganId);
	$pelamar_lowongan->updateByField2();	
	echo "";
//}
//else
	//echo "Upload dokumen persyaratan wajib terlebih dahulu.";

?>