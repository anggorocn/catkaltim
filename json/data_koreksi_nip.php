<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base-diklat/PesertaPerubahanNip.php");

$reqId= $userLogin->userPelamarId;
$sessNama= $userLogin->nama;


if($reqId == "")
{
	exit();
}

$file = new FileHandler();
$reqRowId= httpFilterPost("reqRowId");
$reqNip= httpFilterPost("reqNip");
$reqFotoFile= $_FILES["reqFotoFile"];

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlfoto= $data->urlConfig->main->urlupload;

$FILE_DIR= $urlfoto."/scankarpeg/".$reqId."/";
makedirs($FILE_DIR);
// echo $urlfoto;exit();

$fileName = basename($_FILES["reqFotoFile"]["name"]);
$target_file= $fileName;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// echo "xxx-".$imageFileType;exit();
if($imageFileType == ""){}
else
{
	// if($imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "jpg") {}
	if($imageFileType == "pdf") {}
	else
	{
		echo "xxx-Data gagal disimpan, foto harus dengan format pdf";
		// echo '<script language="javascript">';
		// // echo 'alert("Username atau password anda masih salah.");';
		// echo 'top.location.href = "../main/index.php?pg=diklat_upload_spt&reqRowId='.$reqDiklatId.'";';
		// echo '</script>';
		exit();
	}
}

$set= new PesertaPerubahanNip();
$set->setField("PESERTA_ID", $reqId);
$set->setField("NIP", $reqNip);
$set->setField("PESERTA_PERUBAHAN_NIP_ID", $reqRowId);
$set->setField("LAST_CREATE_USER", $sessNama);
$set->setField("LAST_CREATE_DATE", "NOW()");
$set->setField("LAST_UPDATE_USER", $sessNama);
$set->setField("LAST_UPDATE_DATE", "NOW()");

$tempSimpan= "";
if($reqRowId == "")
{
	if($set->insert())
	{
		$reqRowId= $set->id;
		$tempSimpan= "1";
	}
}
else
{
	if($set->update())
	{
		$tempSimpan= "1";
	}
}

if($tempSimpan= "")
{
	echo "xxx-Data gagal disimpan.";
}
else
{
	// untuk upload file
	if($reqFotoFile['name'] == ""){}
	else			
	{
		$renameFile= $reqRowId.".pdf";
		// echo $renameFile;exit();
		if (move_uploaded_file($reqFotoFile['tmp_name'], $FILE_DIR.$renameFile))
		{
		}
	}

	echo $reqRowId."-Data berhasil di simpan";
}
?>