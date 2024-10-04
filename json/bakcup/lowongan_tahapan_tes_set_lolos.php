<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganTahapan.php");
include_once("../WEB/classes/base/LowonganTahapan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$reqLowonganTahapanId = httpFilterGet("reqLowonganTahapanId");
$reqPelamarId = httpFilterGet("reqPelamarId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqNilai = httpFilterGet("reqNilai");
$reqUrut = httpFilterGet("reqUrut");
$reqLolos = httpFilterGet("reqLolos");

$gagal = 0;
$berhasil = 0;
$arrPelamarId = explode(",", $reqPelamarId);
for($i=0;$i<count($arrPelamarId);$i++)
{
	
	$pelamar_lowongan_tahapan = new PelamarLowonganTahapan();
	$lowongan_tahapan = new LowonganTahapan();
	
	$lowongan_tahapan->selectByParams(array("A.LOWONGAN_ID" => $reqLowonganId, "URUT" => $reqUrut));
	$lowongan_tahapan->firstRow();
	$tempLowonganTahapanId= $lowongan_tahapan->getField("LOWONGAN_TAHAPAN_ID");
	
	$pelamar_lowongan_tahapan->setField("PELAMAR_ID", $arrPelamarId[$i]);
	$pelamar_lowongan_tahapan->setField("LOWONGAN_ID", $reqLowonganId);
	$pelamar_lowongan_tahapan->setField("LOWONGAN_TAHAPAN_ID", $reqLowonganTahapanId);
	
	if($reqLolos == 1)
	{
		if($pelamar_lowongan_tahapan->updateLolos())
		{
			$pelamar_lowongan_tahapan->setField("PELAMAR_ID", $arrPelamarId[$i]);
			$pelamar_lowongan_tahapan->setField("LOWONGAN_ID", $reqLowonganId);
			$pelamar_lowongan_tahapan->setField("LOWONGAN_TAHAPAN_ID", $tempLowonganTahapanId);
			$pelamar_lowongan_tahapan->setField("LOLOS", "0");
			$pelamar_lowongan_tahapan->setField("LAST_CREATE_USER", $userLogin->nama);
			$pelamar_lowongan_tahapan->insert();
			$berhasil++;	
		}
		else
			$gagal++;
	}
	elseif($reqLolos == 2)
	{
		if($pelamar_lowongan_tahapan->updateTidakLolos())
		{
			$pelamar_lowongan_tahapan->setField("PELAMAR_ID", $arrPelamarId[$i]);
			$pelamar_lowongan_tahapan->setField("LOWONGAN_ID", $reqLowonganId);
			$pelamar_lowongan_tahapan->setField("LOWONGAN_TAHAPAN_ID", $tempLowonganTahapanId);
			$pelamar_lowongan_tahapan->deleteData();
			$berhasil++;
		}
		else
			$gagal++;
	}
	
	unset($pelamar_lowongan_tahapan);
	unset($lowongan_tahapan);
}
		
echo "Update data berhasil : ".$berhasil.", gagal : ".$gagal;

?>