<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base/Content.php");
include_once("../WEB/classes/utils/FileHandler.php");


$content = new Content();
$file = new FileHandler();


$reqCID = httpFilterPost("reqCID");

$reqBahasa = httpFilterPost("reqBahasa");
$reqKeterangan = $_POST["reqKeterangan"];
$reqKeterangan = str_replace('\\','', $reqKeterangan);

$reqLinkFile= $_FILES['reqLinkFile'];
$reqLinkFileTemp = httpFilterPost("reqLinkFileTemp");

$FILE_DIR = "../uploads/konten/";
$_THUMB_PREFIX = "z__thumb_";

	$content->setField('KONTEN_ID', $reqCID);
	
	if($reqBahasa=="ina")
	{
		$field= 'KETERANGAN';
	}
	if($reqBahasa=="en")
	{
		$field= 'DESCRIPTION';
	}
	$content->setField($field, $reqKeterangan);
	
	if($reqBahasa=="ina"){		
		if($content->updateINA())
		{
			echo "Data berhasil di Simpan";
		}
		else
		{
			echo "Simpan Gagal : ".$content->getErrorMsg();
		}
	}
	else{
		if($content->updateEN())
		{
			echo "Data berhasil di Simpan";
		}
		else
		{
			echo "Simpan Gagal : ".$content->getErrorMsg();
		}		
	}
		
$cek = formatTextToDb($file->getFileName('reqLinkFile'));
if($cek != "")
{
	$renameFile = $reqCID.'~'.formatTextToDb($file->getFileName('reqLinkFile'));
	$renameFile = str_replace(" ", "", $renameFile);
	
	$varSource=$FILE_DIR.$reqLinkFileTemp;
	$varThumbnail = $FILE_DIR.$_THUMB_PREFIX.$reqLinkFileTemp;
	
	if($file->uploadToDir('reqLinkFile', $FILE_DIR, $renameFile))
	{
		
		$thumbDestination = $file->dirLocation.$_THUMB_PREFIX.$file->uploadedFileName;
		if(!createThumbnail($file->uploadedFile, $thumbDestination))
			$alertMsg .= "Error creating thumbnail";
		
		if($reqLinkFileTemp != ''){
			if($file->delete($varSource)){
					$file->delete($varThumbnail);
				}
		}
		$insertLinkFile = $file->uploadedFileName;
		$set_file = new Content();
		$set_file->setField('KONTEN_ID', $reqCID);	
		$set_file->setField('LINK_URL', $insertLinkFile);
		$set_file->update_file();
	}
}
?>