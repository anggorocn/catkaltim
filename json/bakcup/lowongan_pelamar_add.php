<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowongan.php");
include_once("../WEB/classes/base/LowonganDokumen.php");
include_once("../WEB/classes/utils/UserLogin.php");

$reqPelamarId = httpFilterGet("reqPelamarId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqMode = httpFilterGet("reqMode");

$gagal = 0;
$berhasil = 0;
$arrPelamarId = explode(",", $reqPelamarId);

for($i=0;$i<count($arrPelamarId);$i++)
{
	$reqRowId= $arrPelamarId[$i];
	$pelamar_lowongan= new PelamarLowongan();
	$lowongan_dokumen = new LowonganDokumen();	
	
	$ada= $lowongan_dokumen->getCountByParams(array("A.LOWONGAN_ID" => $reqLowonganId));
	//echo $lowongan_dokumen->query;exit;
	/*if($ada == 0)	
		$reqTanggalKirim = "CURRENT_DATE";
	else
		$reqTanggalKirim = "NULL";*/
		
	$reqTanggalKirim = "CURRENT_DATE";
	
	$pelamar_lowongan_id = $pelamar_lowongan->getPelamarLowonganId(array("A.PELAMAR_ID" => $reqRowId, "A.LOWONGAN_ID" => $reqLowonganId));
	if($pelamar_lowongan_id == "")	
	{
		$pelamar_lowongan->setField("LOWONGAN_ID", $reqLowonganId);
		$pelamar_lowongan->setField("TANGGAL_KIRIM", $reqTanggalKirim);
		$pelamar_lowongan->setField("STATUS_PELAMAR", 2);
		$pelamar_lowongan->setField("PELAMAR_ID", $reqRowId);
		if($pelamar_lowongan->insert())
			$berhasil++;
		else
			$gagal++;
	}
	else
		$gagal++;
}
		
echo "Update data berhasil : ".$berhasil.", gagal : ".$gagal;
?>