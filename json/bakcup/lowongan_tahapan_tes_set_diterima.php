<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganTahapan.php");
include_once("../WEB/classes/base/PelamarLowonganDiterima.php");
include_once("../WEB/classes/base/LowonganTahapan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$reqLowonganTahapanId = httpFilterGet("reqLowonganTahapanId");
$reqPelamarId = httpFilterGet("reqPelamarId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqNilai = httpFilterGet("reqNilai");

$gagal = 0;
$berhasil = 0;
$arrPelamarId = explode(",", $reqPelamarId);
for($i=0;$i<count($arrPelamarId);$i++)
{
	$pelamar_lowongan_tahapan = new PelamarLowonganTahapan();
	$pelamar_lowongan_diterima = new PelamarLowonganDiterima();
	$lowongan_tahapan = new LowonganTahapan();
	
	$pelamar_lowongan_tahapan->setField("PELAMAR_ID", $arrPelamarId[$i]);
	$pelamar_lowongan_tahapan->setField("LOWONGAN_ID", $reqLowonganId);
	$pelamar_lowongan_tahapan->setField("LOWONGAN_TAHAPAN_ID", $reqLowonganTahapanId);
	if($pelamar_lowongan_tahapan->updateLolos())
	{
		$pelamar_lowongan_diterima->setField("PELAMAR_ID", $arrPelamarId[$i]);
		$pelamar_lowongan_diterima->setField("LOWONGAN_ID", $reqLowonganId);
		$pelamar_lowongan_diterima->setField("LAST_CREATE_USER", $userLogin->nama);
		$pelamar_lowongan_diterima->insert();
		$berhasil++;	
	}
	else
		$gagal++;
		
	unset($pelamar_lowongan_tahapan);
	unset($pelamar_lowongan_diterima);
	unset($lowongan_tahapan);
}

echo "Update data berhasil : ".$berhasil.", gagal : ".$gagal;
?>