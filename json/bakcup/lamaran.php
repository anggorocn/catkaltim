<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowongan.php");
include_once("../WEB/classes/base/LowonganDokumen.php");
include_once("../WEB/classes/utils/FileHandler.php");

$reqSubmit= httpFilterPost("reqSubmit");
$reqId= httpFilterPost("reqId");

if($reqSubmit == "update")
{
  
	$pelamar_lowongan= new PelamarLowongan();
	$lowongan_dokumen = new LowonganDokumen();	
	
	$ada = $lowongan_dokumen->getCountByParams(array("A.LOWONGAN_ID" => $reqId));
	if($ada == 0)	
		$reqTanggalKirim = "CURRENT_DATE";
	else
		$reqTanggalKirim = "NULL";
	
	//$reqTanggalKirim = "CURRENT_DATE";
	
	$pelamar_lowongan_id = $pelamar_lowongan->getPelamarLowonganId(array("A.PELAMAR_ID" => $userLogin->userPelamarId, "A.LOWONGAN_ID" => $reqId));
	if($pelamar_lowongan_id == "")	
	{
		$pelamar_lowongan->setField("LOWONGAN_ID", $reqId);
		$pelamar_lowongan->setField("TANGGAL_KIRIM", $reqTanggalKirim);
		$pelamar_lowongan->setField("STATUS_PELAMAR", 1);
		$pelamar_lowongan->setField("PELAMAR_ID", $userLogin->userPelamarId);
		if($pelamar_lowongan->insert())
			echo "";
	}
	else
		echo "Anda sudah melamar lowongan ini sebelumnya. Apabila anda belum melengkapi Dokumen, pilih Main Menu -> Daftar Lamaran Anda.";
	  
}
?>