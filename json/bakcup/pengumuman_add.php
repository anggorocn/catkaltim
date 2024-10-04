<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pengumuman.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$FILE_DIR = "../uploads/pengumuman/";

$pengumuman = new Pengumuman();
$file = new FileHandler();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqNama= httpFilterPost("reqNama"); 
$reqLowonganId= httpFilterPost("reqLowonganId");

//echo $reqId;exit; 

$reqLinkFile= $_FILES['reqLinkFile'];
$reqLinkFileTemp = httpFilterPost("reqLinkFileTemp");

$pengumuman->setField('LOWONGAN_ID', ValToNullDB($reqLowonganId));
$pengumuman->setField('KETERANGAN', setQuote($reqKeterangan,1));
$pengumuman->setField('NAMA', setQuote($reqNama,1));

$tempSimpan = "";	
if($reqMode == "insert")
{
	$pengumuman->setField("LAST_CREATE_USER", $userLogin->nama);
	$pengumuman->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	if($pengumuman->insert())
	{
		$tempSimpan = 1;
		$reqId = $pengumuman->id;
	}
}
else
{
	$pengumuman->setField("LAST_UPDATE_USER", $userLogin->nama);
	$pengumuman->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	$pengumuman->setField('PENGUMUMAN_ID', $reqId); 
	if($pengumuman->update())
	{
		$tempSimpan = 1;
	}
}
//echo $pengumuman->query;exit;

if($tempSimpan==1)
{
	$cek = formatTextToDb($file->getFileName('reqLinkFile'));
	if($cek == "")
	{
	}
	else
	{
		/*$allowed = array(".exe");	$status_allowed='';
		foreach ($allowed as $file_cek) 
		{
			if(preg_match("/$file_cek\$/i", $_FILES['reqLinkFile']['name'])) 
			{
				$status_allowed = 'tidak_boleh';
			}
		}*/
		
		$renameFile = $reqId.'~'.formatTextToDb($file->getFileName('reqLinkFile'));
		$renameFile = str_replace(" ", "", $renameFile);
		$renameFile = str_replace(",", "_", $renameFile);
				
		$varSource=$FILE_DIR.$reqLinkFileTemp;
		
		if($reqLinkFileTemp != ''){
			$file->delete($varSource);
		}
		
		if($file->uploadToDir('reqLinkFile', $FILE_DIR, $renameFile))
		{
					
			$insertLinkFile = $file->uploadedFileName;
			$set_file = new Pengumuman();
			$set_file->setField('PENGUMUMAN_ID', $reqId);	
			$set_file->setField('LINK_FILE', $insertLinkFile);
			$set_file->update_file();
			//echo $set_file->query;exit;
		}
	}
		echo $reqId."-Data berhasil disimpan.";
}
?>