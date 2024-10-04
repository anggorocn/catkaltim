<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganTahapan.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/KMail.php");


$pelamar_lowongan_tahapan = new PelamarLowonganTahapan();

$reqPelamarId = httpFilterGet("reqPelamarId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqLowonganTahapanId = httpFilterGet("reqLowonganTahapanId");
$reqTanggal = httpFilterGet("reqTanggal");
$reqJam = httpFilterGet("reqJam");

$arrPelamarId = explode(",", $reqPelamarId);

$gagal = 0;
$berhasil = 0;
for($i=0;$i<count($arrPelamarId);$i++)
{
	$pelamar_lowongan_tahapan = new PelamarLowonganTahapan();
	
	$pelamar_lowongan_tahapan->setField('LOWONGAN_ID', $reqLowonganId);
	$pelamar_lowongan_tahapan->setField('PELAMAR_ID', $arrPelamarId[$i]); 	
	$pelamar_lowongan_tahapan->setField('LOWONGAN_TAHAPAN_ID', $reqLowonganTahapanId); 	
	$pelamar_lowongan_tahapan->setField('TANGGAL_HADIR', dateTimeToDBCheck($reqTanggal." ".$reqJam.":00")); 	
	if($pelamar_lowongan_tahapan->updateEmail())
	{
		$xmlfile = "../WEB/weburl.xml";
		$data = simplexml_load_file($xmlfile);
		$linktemplate= $data->urlConfig->linkConfig->linktemplate;
		//$body = file_get_contents("http://".$_SERVER['SERVER_NAME']."/pds-rekrutmen/templates/undangan_tes_tahapan_awal.php?reqLowonganTahapanId=".$reqLowonganTahapanId."&reqLowonganId=".$reqLowonganId."&reqPelamarId=".$arrPelamarId[$i]);
		$body = file_get_contents("http://".$_SERVER['SERVER_NAME'].$linktemplate."undangan_tes_tahapan_awal.php?reqLowonganTahapanId=".$reqLowonganTahapanId."&reqLowonganId=".$reqLowonganId."&reqPelamarId=".$arrPelamarId[$i]);
		
		$mail = new KMail("backup");
		$mail->AddAddress("rosyidi.alhamdani@valsix.co.id" , "Rosyidi Alhamdani");
		//$mail->AddAddress("riza@ptpds.co.id", "Riza Akhmad Juliantoko"); 
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
		
	unset($pelamar_lowongan_tahapan);
}
echo "Email terkirim : ".$berhasil.", email gagal terkirim : ".$gagal;
		

?>