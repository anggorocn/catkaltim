<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganDiterima.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/KMail.php");

$pelamar_lowongan_diterima = new PelamarLowonganDiterima();

$reqPelamarId = httpFilterGet("reqPelamarId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqTanggal = httpFilterGet("reqTanggal");

$pelamar_lowongan_diterima->setField('LOWONGAN_ID', $reqLowonganId);
$pelamar_lowongan_diterima->setField('PELAMAR_ID', $reqPelamarId); 	
$pelamar_lowongan_diterima->setField('TANGGAL_HADIR', dateTimeToDBCheck($reqTanggal.":00")); 	
if($pelamar_lowongan_diterima->updateEmail())
{
	$xmlfile = "../WEB/weburl.xml";
	$data = simplexml_load_file($xmlfile);
	$linktemplate= $data->urlConfig->linkConfig->linktemplate;
	//$body = file_get_contents("http://".$_SERVER['SERVER_NAME']."/pds-rekrutmen/templates/undangan_diterima.php?reqLowonganId=".$reqLowonganId."&reqPelamarId=".$reqPelamarId);
	$body = file_get_contents("http://".$_SERVER['SERVER_NAME'].$linktemplate."undangan_diterima.php?reqLowonganId=".$reqLowonganId."&reqPelamarId=".$reqPelamarId);
	
	
	$mail = new KMail("backup");
	$mail->AddAddress("rosyidi.alhamdani@valsix.co.id" , "Rosyidi Alhamdani");
	$mail->AddAddress("riza@ptpds.co.id", "Riza Akhmad Juliantoko"); 
	$mail->Subject  =  "Undangan - Career and Recruitment Center PT Pelindo Daya Sejatera";
	$mail->MsgHTML($body);
	if(!$mail->Send())
	{
		echo "Gagal kirim";
	}
	else
	{
		echo "Berhasil kirim";
	}	
}
else
	echo "Update data gagal.";

		

?>