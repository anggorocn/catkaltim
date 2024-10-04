<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base/UploadFile.php");

$reqId= $userLogin->userPelamarId;
$sessNama= $userLogin->nama;


if($reqId == "")
{
	exit();
}

$file = new FileHandler();
$reqRowId= httpFilterPost("reqRowId");
$reqNip= httpFilterPost("reqNip");
$reqLinkFile1= $_FILES["reqLinkFile1"];
$reqLinkFile2= $_FILES["reqLinkFile2"];
// $reqLinkFile3= $_FILES["reqLinkFile3"];
$reqFotoFile= $_FILES["reqFotoFile"];



$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlfoto= $data->urlConfig->main->urlupload;


$FILE_DIR= $urlfoto."dokumen/".$reqId."/";

$FILE_DIR_FOTO= $urlfoto."foto/".$reqId."/";


makedirs($FILE_DIR);
if (!empty($reqFotoFile))
{
	makedirs($FILE_DIR_FOTO);
}

 // echo $reqFotoFile;exit();
$fileName = basename($_FILES["reqLinkFile1"]["name"]);
$fileName2 = basename($_FILES["reqLinkFile2"]["name"]);
// $fileName3 = basename($_FILES["reqLinkFile3"]["name"]);
$fileFoto = basename($_FILES["reqFotoFile"]["name"]);

   // echo $fileName ;
   //  echo $fileName2 ;
   //   echo $fileFoto ;exit;


$target_file= $fileName;
// $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

$urlupload1= $FILE_DIR.$fileName;
$urlupload2= $FILE_DIR.$fileName2;
// $urlupload3= $FILE_DIR.$fileName3;


$urluploadfoto= $FILE_DIR_FOTO.$fileFoto;

// echo $urlupload2;exit;

// echo "xxx-".$imageFileType;exit();
if($fileName == ""){}
else
{
	// if($imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "jpg") {}
	// if($fileName == "portfolio.doc" || $fileName == "portfolio.docx") {}
	// else
	// {
	// 	echo "xxx-Data gagal disimpan. Nama file Upload Portfolio harus diberi penamaan portfolio.doc / portfolio.docx";
	// 	exit();
	// }

	// if($fileName2 == "form_q_competensi.docx" || $fileName2 == "") {}
	// else
	// {
	// 	echo "xxx-Data gagal disimpan. Nama file Formulir Q Kompetensi  harus sesuai template ";
	// 	exit();
	// }

	// if($fileName3 == "form_critical_insident.docx" || $fileName3 == "") {}
	// else
	// {
	// 	echo "xxx-Data gagal disimpan.  Nama file Form Data Critical Incident harus sesuai template ";
	// 	exit();
	// }
	
}

// echo $fileName3;exit;

$set_check= new UploadFile();
$statement= " AND A.PEGAWAI_ID = ".$userLogin->userPelamarId;
$set_check->selectByParams(array(), -1, -1, $statement);
    // echo $set_check->query;exit;
$set_check->firstRow();
$TempLinkFile1 = $set_check->getField('LINK_FILE1');
// var_dump($TempLinkFile1) ;exit;
$TempLinkFile2 = $set_check->getField('LINK_FILE2');
// $TempLinkFile3 = $set_check->getField('LINK_FILE3');
$TempLinkFoto = $set_check->getField('LINK_FOTO');

// echo $TempLinkFoto;exit; 






$namaupload = 'Dokumen 1';

$set= new UploadFile();
$set->setField("NAMA", $namaupload);
$set->setField("PEGAWAI_ID", $reqId);

if (!empty($fileName))
{
	$set->setField("LINK_FILE1", $urlupload1);
}
if (!empty($fileName2))
{
	$set->setField("LINK_FILE2", $urlupload2);
} 

if (!empty($fileFoto))
{
	$set->setField("LINK_FOTO", $urluploadfoto);
}
	

// echo $urluploadfoto;exit; 


$setcheck= new UploadFile();

$statement= " AND A.PEGAWAI_ID = ".$userLogin->userPelamarId;
// echo $statement;exit();
$setcheck->selectByParams(array(),-1,-1, $statement);
$setcheck->firstRow();
$reqPegawaiId = $setcheck->getField('PEGAWAI_ID');
// echo $reqPegawaiId;exit;


$tempSimpan= "";
if($reqPegawaiId == "")
{
	if($set->insert())
	{
		$reqUploadId= $set->id;
		$tempSimpan= "1";
	}	

}
else
{

	// if (!empty($TempLinkFile1))
	// {
	// 	$set->setField("LINK_FILE1", $TempLinkFile1);
	// }
	// if (!empty($TempLinkFile2))
	// {
	// 	$set->setField("LINK_FILE2", $TempLinkFile2);
	// }
	// if (!empty($fileFoto))
	// {
	// 	$set->setField("LINK_FOTO", $FILE_DIR_FOTO.$fileFoto);
	// } 

	if (!empty($fileName))
	{
		$set->setField("LINK_FILE1", $urlupload1);
	}
	else
	{
		$set->setField("LINK_FILE1", $TempLinkFile1);	
	}

	if (!empty($fileName2))
	{
		$set->setField("LINK_FILE2", $urlupload2);
	} 
	else
	{
		$set->setField("LINK_FILE2", $TempLinkFile2);	
	}

	if (!empty($fileFoto))
	{
		$set->setField("LINK_FOTO", $urluploadfoto);
	}
	else
	{
		$set->setField("LINK_FOTO", $TempLinkFoto);	
	}
	
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
	if($reqLinkFile1['name'] == "" ){}
	else			
	{
		// echo 'asdas';exit;
		$renameFile= $fileName;
		// echo $renameFile;exit();
		if (move_uploaded_file($reqLinkFile1['tmp_name'], $FILE_DIR.$renameFile))
		{
		} 
	}

	if($reqLinkFile2['name'] == "" ){}
	else			
	{
		 
		$renameFile2= $fileName2;
		// echo $renameFile2;exit();
		if (move_uploaded_file($reqLinkFile2['tmp_name'], $FILE_DIR.$renameFile2))
		{
		}
 
	}

	if($reqFotoFile['name'] == "" ){}
		else
		{
			$renameFoto= $fileFoto;
			if (move_uploaded_file($reqFotoFile['tmp_name'], $FILE_DIR_FOTO.$renameFoto))
			{
			}
		}

	echo $fileName."-Data berhasil di simpan";
}
?>