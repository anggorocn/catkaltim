<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base/Informasi.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/classes/utils/UserLogin.php");


$informasi = new Informasi();
$file = new FileHandler();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqUID = httpFilterPost("reqUID");
$reqStatus = httpFilterPost("reqStatus");

$reqTanggal = httpFilterPost("reqTanggal");
$reqNama = httpFilterPost("reqNama");
$reqKeterangan = $_POST["reqKeterangan"];
$reqKeterangan = str_replace('\\','', $reqKeterangan);
$reqStatusHalamanDepan = httpFilterPost("reqStatusHalamanDepan");
$reqStatusAktif = httpFilterPost("reqStatusAktif");
$reqLinkGambar = httpFilterPost("reqLinkGambar");
//$reqInfoDetil = httpFilterPost("reqInfoDetil");
$reqInfoDetil = $_POST["reqInfoDetil"];

$reqLinkFile= $_FILES['reqLinkFile'];
$reqLinkFileTemp = httpFilterPost("reqLinkFileTemp");


$FILE_DIR = "../uploads/informasi/";
//$FILE_DIR = "../../pdsweb3/uploads/informasi/";
$_THUMB_PREFIX = "z__thumb_";

$tempStatus= "";
if($reqMode == "insert")
{	
	$informasi->setField("NAMA", $reqNama);
	$informasi->setField("KETERANGAN", $reqKeterangan);
	$informasi->setField("TANGGAL", dateToDBCheck($reqTanggal));
	$informasi->setField("USER_LOGIN_ID", $userLogin->UID);
	$informasi->setField("STATUS_HALAMAN_DEPAN", 0);
	$informasi->setField("STATUS_AKTIF", setNULL($reqStatusAktif));
	$informasi->setField("STATUS_INFORMASI", 1);
	/*$informasi->setField("LAST_CREATE_USER", $reqUID);
	$informasi->setField("LAST_CREATE_DATE", "CURDATE()");*/

	if($informasi->insert()){
			$tempStatus= 1;
			//echo "Data berhasil disimpan."; 
			$reqDetilId = $informasi->id;
		}
}
else
{
	$informasi->setField("INFORMASI_ID", $reqId);
	$informasi->setField("NAMA", $reqNama);
	$informasi->setField("KETERANGAN", $reqKeterangan);
	$informasi->setField("TANGGAL", dateToDBCheck($reqTanggal));
	$informasi->setField("USER_LOGIN_ID", $userLogin->UID);
	$informasi->setField("STATUS_HALAMAN_DEPAN", 0);
	$informasi->setField("STATUS_AKTIF", setNULL($reqStatusAktif));
	$informasi->setField("STATUS_INFORMASI", 1);
		
	/*$informasi->setField("LAST_UPDATE_USER", $reqUID);
	$informasi->setField("LAST_UPDATE_DATE", "CURDATE()");*/	
	
	if($informasi->update()){
			$tempStatus= 1;
			//echo "Data berhasil disimpan."; 
			$reqDetilId = $reqId;
		}
}

if($tempStatus == 1)
{
	
	$cek = formatTextToDb($file->getFileName('reqLinkFile'));
	if($cek == "")
	{
	}
	else
	{
		$allowed = array(".exe");	$status_allowed='';
		foreach ($allowed as $file_cek) 
		{
			if(preg_match("/$file_cek\$/i", $_FILES['reqLinkFile']['name'])) 
			{
				$status_allowed = 'tidak_boleh';
			}
		}
		
		$renameFile = $reqDetilId.'~'.formatTextToDb($file->getFileName('reqLinkFile'));
		$renameFile = str_replace(" ", "", $renameFile);
		
		$varSource=$FILE_DIR.$reqLinkFileTemp;
		$varThumbnail = $FILE_DIR.$_THUMB_PREFIX.$reqLinkFileTemp;
		
		if($file->uploadToDir('reqLinkFile', $FILE_DIR, $renameFile))
		{
			if($reqStatus == "1")
			{
				if($reqLinkFileTemp != ''){
					if($file->delete($varSource)){
							$file->delete($varThumbnail);
						}
				}
			}
			$insertLinkFile = $file->uploadedFileName;
			$set_file = new Informasi();
			$set_file->setField('INFORMASI_ID', $reqDetilId);	
			$set_file->setField('LINK_FILE', $insertLinkFile);
			$set_file->update_file();
		}
	}
	
	echo "Data berhasil disimpan."; 
}

?>