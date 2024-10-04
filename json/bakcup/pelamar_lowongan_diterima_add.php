<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganDiterima.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_lowongan_diterima = new PelamarLowonganDiterima();

$reqId = httpFilterPost("reqId");
$reqLowonganId = httpFilterPost("reqLowonganId");
$reqMode = httpFilterPost("reqMode");

$reqNoBeritaAcara = httpFilterPost("reqNoBeritaAcara");
$reqLampiran = $_FILES['reqLampiran'];
//$reqLampiranTemp = httpFilterPost('reqLampiranTemp');
$reqLampiranTemp= $_POST["reqLampiranTemp"];
		
$pelamar_lowongan_diterima->setField('LOWONGAN_ID', $reqLowonganId);
$pelamar_lowongan_diterima->setField('PELAMAR_ID', $reqId);
$pelamar_lowongan_diterima->setField('NO_BERITA_ACARA', $reqNoBeritaAcara);
$pelamar_lowongan_diterima->setField('LAST_UPDATE_USER', $userLogin->nama);
$pelamar_lowongan_diterima->setField('LAST_UPDATE_DATE', "CURRENT_DATE"); 	

/* START UPLOAD FILE */
$FILE_DIR = "../uploads/berita_acara/";
for($i=0;$i<count($reqLampiran);$i++)
{	
	if($reqLampiran['name'][$i] == "")
	{}
	else			
	{
		$renameFile = md5(date("dmYHis").$reqLampiran['name'][$i]).".".getExtension($reqLampiran['name'][$i]);
		if (move_uploaded_file($reqLampiran['tmp_name'][$i], $FILE_DIR.$renameFile))
		{
			if($i == 0)	
				$insertLinkFile = $renameFile;
			else
				$insertLinkFile .= ",".$renameFile;
			
		}			
	}	
}
for($i=0;$i<count($reqLampiranTemp);$i++)
{
	if($reqLampiranTemp[$i] == "")
	{}
	else
	{
		if($insertLinkFile == "")	
			$insertLinkFile = $reqLampiranTemp[$i];
		else
			$insertLinkFile .= ",".$reqLampiranTemp[$i];
	}
}
	
$pelamar_lowongan_diterima->setField("DOKUMEN_BERITA_ACARA", $insertLinkFile);
/* END UPLOAD FILE */

if($pelamar_lowongan_diterima->updateBeritaAcara())
echo "Data berhasil disimpan.";
?>