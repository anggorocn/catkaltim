<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganShortlist.php");
include_once("../WEB/classes/base/PelamarLowonganTahapan.php");
include_once("../WEB/classes/base/LowonganTahapan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$reqPelamarId = httpFilterGet("reqPelamarId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqTanggal = httpFilterGet("reqTanggal");

$gagal = 0;
$berhasil = 0;
$arrPelamarId = explode(",", $reqPelamarId);
for($i=0;$i<count($arrPelamarId);$i++)
{
	
	$pelamar_lowongan_shortlist = new PelamarLowonganShortlist();
	$pelamar_lowongan_tahapan = new PelamarLowonganTahapan();
	$lowongan_tahapan = new LowonganTahapan();
	
	$lowongan_tahapan->selectByParams(array("A.LOWONGAN_ID" => $reqLowonganId, "URUT" => "1"));
	$lowongan_tahapan->firstRow();
	
	$pelamar_lowongan_shortlist->setField('LOWONGAN_ID', $reqLowonganId);
	$pelamar_lowongan_shortlist->setField('PELAMAR_ID', $arrPelamarId[$i]); 	
	if($pelamar_lowongan_shortlist->updateHadir())
	{
		$statement= " AND A.PELAMAR_ID = ".$arrPelamarId[$i]." AND A.LOWONGAN_ID = ".$reqLowonganId." AND A.LOWONGAN_TAHAPAN_ID = ".$lowongan_tahapan->getField("LOWONGAN_TAHAPAN_ID");
		$set= new PelamarLowonganTahapan();
		$tempCheck= $set->getCountByParams(array(), $statement);
		unset($set);
		//echo $arrPelamarId[$i]."-".$reqLowonganId."-".$lowongan_tahapan->getField("LOWONGAN_TAHAPAN_ID")."-".$tempCheck;
		//exit;
		if($tempCheck == 0)
		{
			$pelamar_lowongan_tahapan->setField("PELAMAR_ID", $arrPelamarId[$i]);
			$pelamar_lowongan_tahapan->setField("LOWONGAN_ID", $reqLowonganId);
			$pelamar_lowongan_tahapan->setField("LOWONGAN_TAHAPAN_ID", $lowongan_tahapan->getField("LOWONGAN_TAHAPAN_ID"));
			$pelamar_lowongan_tahapan->setField("LOLOS", "0");
			$pelamar_lowongan_tahapan->setField("LAST_CREATE_USER", $userLogin->nama);
			$pelamar_lowongan_tahapan->insertAwal();
		}
		$berhasil++;
	}
	else
		$gagal++;
		
	unset($pelamar_lowongan_shortlist);
	unset($pelamar_lowongan_tahapan);
	unset($lowongan_tahapan);
		
}

echo "Update data berhasil : ".$berhasil.", gagal : ".$gagal;

?>