<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowongan.php");
include_once("../WEB/classes/base/PelamarLowonganShortlist.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/KMail.php");


$reqId = httpFilterPost("reqId");
$reqUndangan = $_POST["reqUndangan"];

for($i=0;$i<count($reqUndangan);$i++)
{
	$pelamar_lowongan = new PelamarLowongan();
	$pelamar_lowongan_shortlist = new PelamarLowonganShortlist();
	$pelamar_lowongan->setField("PELAMAR_ID", $reqId);
	$pelamar_lowongan->setField("STATUS_PELAMAR", 2);
	$pelamar_lowongan->setField("LOWONGAN_ID", $reqUndangan[$i]);
	
	$pelamar_lowongan_shortlist->setField("PELAMAR_ID", $reqId);
	$pelamar_lowongan_shortlist->setField("LOWONGAN_ID", $reqUndangan[$i]);
	if($pelamar_lowongan->insertUndangan())
	{
		if($pelamar_lowongan_shortlist->insert())
		{
			echo "Pelamar berhasil diundang.";
		}
	/*
		$xmlfile = "../WEB/weburl.xml";
		$data = simplexml_load_file($xmlfile);
		$linktemplate= $data->urlConfig->linkConfig->linktemplate;
		//$body = file_get_contents("http://".$_SERVER['SERVER_NAME']."/pds-rekrutmen/templates/undangan_lowongan.php?reqLowonganId=".$reqUndangan[$i]."&reqPelamarId=".$reqId);
		$body = file_get_contents("http://".$_SERVER['SERVER_NAME'].$linktemplate."undangan_lowongan.php?reqLowonganId=".$reqUndangan[$i]."&reqPelamarId=".$reqId);
	
		$mail = new KMail("backup");
		$mail->AddAddress("rosyidi.alhamdani@valsix.co.id" , "Rosyidi Alhamdani");
		//$mail->AddAddress("riza@ptpds.co.id", "Riza Akhmad Juliantoko"); 
		$mail->Subject  =  "Undangan Lowongan - Career and Recruitment Center PT Pelindo Daya Sejatera";
		$mail->MsgHTML($body);
		if(!$mail->Send())
		{
			echo "Gagal kirim";
		}
		else
		{
			echo "Berhasil kirim";
		}	*/
					
	}
	unset($pelamar_lowongan);		
}

?>