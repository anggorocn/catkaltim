<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganShortlist.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/KMail.php");

$reqPelamarId = httpFilterGet("reqPelamarId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqTanggal = httpFilterGet("reqTanggal");
$reqJam = httpFilterGet("reqJam");

$arrPelamarId = explode(",", $reqPelamarId);

$gagal = 0;
$berhasil = 0;
for($i=0;$i<count($arrPelamarId);$i++)
{
		
	$pelamar_lowongan_shortlist = new PelamarLowonganShortlist();
	$pelamar_lowongan_shortlist->setField('LOWONGAN_ID', $reqLowonganId);
	$pelamar_lowongan_shortlist->setField('PELAMAR_ID', $arrPelamarId[$i]); 	
	$pelamar_lowongan_shortlist->setField('TANGGAL_HADIR', dateTimeToDBCheck($reqTanggal." ".$reqJam.":00")); 	
	if($pelamar_lowongan_shortlist->updateEmail())
	{
		$xmlfile = "../WEB/weburl.xml";
		$data = simplexml_load_file($xmlfile);
		$linktemplate= $data->urlConfig->linkConfig->linktemplate;
		//$body = file_get_contents("http://".$_SERVER['SERVER_NAME']."/pds-rekrutmen/templates/undangan_tes.php?reqLowonganId=".$reqLowonganId."&reqPelamarId=".$arrPelamarId[$i]);
		$body = file_get_contents("http://".$_SERVER['SERVER_NAME'].$linktemplate."undangan_tes.php?reqLowonganId=".$reqLowonganId."&reqPelamarId=".$arrPelamarId[$i]);
		
		$mail = new KMail("backup");
		//$mail->AddAddress("rosyidi.alhamdani@valsix.co.id" , "Rosyidi Alhamdani");
		$mail->AddAddress("riza@ptpds.co.id", "Riza Akhmad Juliantoko"); 
		$mail->AddAddress("novanbagus@gmail.com", "Novan Bagus Setiawan"); 
		$mail->Subject  =  "Undangan - Career and Recruitment Center PT Pelindo Daya Sejatera";
		$mail->MsgHTML($body);
		if(!$mail->Send())
		{
			$gagal++;
		}
		else
		{
			$berhasil++;
		}	
	}
	else
		$gagal++;
		
	unset($pelamar_lowongan_shortlist);
}

echo "Email terkirim : ".$berhasil.", email gagal terkirim : ".$gagal;
			

?>