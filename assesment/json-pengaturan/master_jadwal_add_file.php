<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/FileHandler.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PermohonanFile.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterPost("reqId");
$reqFileJenisId= $_POST["reqFileJenisId"];
$reqFileJenisKode= $_POST["reqFileJenisKode"];

$reqkuncijenis= $reqId;
$reqfolderjenis= "jadwaltes".$reqkuncijenis;
$reqJenis= $reqfolderjenis."-soal";

$filedata= $_FILES["reqLinkFile"];
$file= new FileHandler();

$checkFile= $file->checkfile($filedata, 5);
$namaLinkFile= $file->setlinkfile($filedata);
// print_r($filedata);exit;
$jumlahdata= 0;
if(empty($namaLinkFile))
{
	if($jumlahfiledata > 0){}
	else
	{
		echo "xxx-Anda belum upload file lampiran.";
		exit();
	}
}
else
{
	if(!empty($checkFile))
	{
		echo "xxx-File harus berformat (ppt/pdf/world/excel)";
		exit();
	}

	$sizeLinkFile= $file->checkmodifsizefile($filedata);
	if(!empty($sizeLinkFile))
	{
		echo "xxx-".$sizeLinkFile;
		exit();
	}

	$jumlahdata= count($filedata);
}

// proses hapus file
// $arrdatafile= array();
// $pfile = new PermohonanFile();
// $pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqkuncijenis, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
// // echo $pfile->query;exit;
// $indexpfile=0;
// while ($pfile->nextRow()) 
// {
// 	$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
// 	$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
// 	$indexpfile++;
// }

$folderfilesimpan= "../uploads/".$reqfolderjenis;
if(file_exists($folderfilesimpan)){}
else
{
	makedirs($folderfilesimpan);
}

$simpan="1";
for($i=0; $i <= $jumlahdata; $i++)
{
	$namafile= $filedata["name"][$i];
	$fileType= $filedata["type"][$i];
	$datafileupload= $filedata["tmp_name"][$i];
	$filepath= $file->getExtension($namafile);

	if($namafile == ""){}
	else
	{
		// $namajenisfile= jenisfiletestget($reqFileJenisId[$i], "jenis");
		$namajenisfile= $reqFileJenisKode[$i];
		$penamaanfile= $reqkuncijenis."_".$namajenisfile."_";

		$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
		$targetsimpan= $folderfilesimpan."/".$linkfile;

		$cfile= new PermohonanFile();
		$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqkuncijenis, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.PEGAWAI_ID"=>$namajenisfile));
		$cfile->firstRow();
		// echo $cfile->query;exit;
		$infofilerowid= $cfile->getField("PERMOHONAN_FILE_ID");
		$infofilelokasi= $cfile->getField("LINK_FILE");
		if(file_exists($infofilelokasi))
		{
			$setfile= new PermohonanFile();
			$setfile->setField("PERMOHONAN_FILE_ID", $infofilerowid);
			$setfile->delete();
			// echo $setfile->query;exit;
			unlink($infofilelokasi);
		}

		if (move_uploaded_file($datafileupload, $targetsimpan))
		{
			$setfile= new PermohonanFile();
			$setfile->setField("PEGAWAI_ID", $namajenisfile);
			$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
			$setfile->setField("PERMOHONAN_TABLE_ID", $reqkuncijenis);
			$setfile->setField("NAMA", $linkfile);
			$setfile->setField("KETERANGAN", $namafile);
			$setfile->setField("LINK_FILE", $targetsimpan);
			$setfile->setField("TIPE", strtolower($fileType));
			$setfile->setField("LAST_USER", $userLogin->nama);
			$setfile->setField("USER_LOGIN_ID", ValToNullDB($userLogin->UID));
			$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($userLogin->UID));
			$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($userLogin->UID));
			$setfile->insert();
			// echo $setfile->query;exit;
			unset($setfile);

			$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
			$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
			$indexpfile++;
		}
	}
}

// proses hapus file
// for ($i=0; $i < $indexpfile; $i++)
// { 
// 	$infofilecheck=  $arrdatafile[$i]['NAMA'];
// 	$cfile= new PermohonanFile();
// 	$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqkuncijenis, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
// 	$cfile->firstRow();
// 	$infofilelokasi= $cfile->getField("LINK_FILE");
// 	if (empty($infofilelokasi))
// 	{
// 		unlink($infofilelokasi);
// 	}
// }

if($simpan == "1")
	echo $reqId."-Data berhasil disimpan.";
else
	echo "xxx-Data gagal disimpan.";
?>