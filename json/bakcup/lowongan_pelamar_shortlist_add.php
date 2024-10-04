<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganShortlist.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_lowongan_shortlist = new PelamarLowonganShortlist();

$reqPelamarId = httpFilterGet("reqPelamarId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqMode = httpFilterGet("reqMode");

$gagal = 0;
$berhasil = 0;
$arrPelamarId = explode(",", $reqPelamarId);
for($i=0;$i<count($arrPelamarId);$i++)
{
	if($reqMode == "insert")
	{
		$pelamar_lowongan_shortlist_delete = new PelamarLowonganShortlist();
		$pelamar_lowongan_shortlist_delete->setField('LOWONGAN_ID', $reqLowonganId);
		$pelamar_lowongan_shortlist_delete->setField('PELAMAR_ID', $arrPelamarId[$i]); 	
		$pelamar_lowongan_shortlist_delete->delete();
				
		$pelamar_lowongan_shortlist->setField("LAST_CREATE_USER", $userLogin->nama);
		$pelamar_lowongan_shortlist->setField('LOWONGAN_ID', $reqLowonganId);
		$pelamar_lowongan_shortlist->setField('PELAMAR_ID', $arrPelamarId[$i]); 	
		if($pelamar_lowongan_shortlist->insert())
			$berhasil++;
		else
			$gagal++;
	}
	else
	{
		$pelamar_lowongan_shortlist->setField('LOWONGAN_ID', $reqLowonganId);
		$pelamar_lowongan_shortlist->setField('PELAMAR_ID', $arrPelamarId[$i]); 	
		if($pelamar_lowongan_shortlist->delete())
			$berhasil++;
		else
			$gagal++;
	}
}
		
echo "Update data berhasil : ".$berhasil.", gagal : ".$gagal;
?>