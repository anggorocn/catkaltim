<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PermohonanFile.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/functions/FileHandler.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
    exit;
}

date_default_timezone_set('Asia/Jakarta');
// ini_set('display_errors', 1);

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;
// echo $ujianPegawaiJadwalTesId;exit();

$tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$tempUjianId= $ujianPegawaiUjianId;

$set= new Pelamar();
$set->selectByParams(array("A.PEGAWAI_ID"=>$tempPegawaiId));
$set->firstRow();
$infopenamaan= $set->getField("NIP_BARU")."-".str_replace("'", "", $set->getField("NAMA"));
// echo $infopenamaan;exit;

$reqFileJenisId= $_POST["reqFileJenisId"];
$reqFileJenisKode= $_POST["reqFileJenisKode"];
// print_r($reqFileJenisId);exit;

$reqkuncijenis= $ujianPegawaiJadwalTesId;
$reqfolderjenis= "jadwaltes".$reqkuncijenis;
$reqJenis= $reqfolderjenis."-jawab";

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
		echo "xxx-File harus berformat (ppt/pdf/word/excel)";
		exit();
	}

	// $sizeLinkFile= $file->checkmodifsizefile($filedata);
	// if(!empty($sizeLinkFile))
	// {
	// 	echo "xxx-".$sizeLinkFile;
	// 	exit();
	// }

	$jumlahdata= count($filedata);
}


$addtoadminfile= "../../assesment/";
$folderfilesimpan= "../uploads/".$reqfolderjenis;
$folderfileuploadsimpan= str_replace("../uploads/", $addtoadminfile."uploads/", $folderfilesimpan);

// echo  $folderfileuploadsimpan;exit;
if(file_exists($folderfileuploadsimpan)){}
else
{
	makedirs($folderfileuploadsimpan);
}

$simpan="1";
for($i=0; $i < $jumlahdata; $i++)
{
	$namafile= $filedata["name"][$i];
	$fileType= $filedata["type"][$i];
	$datafileupload= $filedata["tmp_name"][$i];
	$filepath= $file->getExtension($namafile);

	if($namafile == ""){}
	else
	{
		// $namajenisfile= jenisfiletestget($reqFileJenisId[$i], "jenis")."-".$tempPegawaiId;
		$namajenisfile= $reqFileJenisKode[$i]."-".$tempPegawaiId;

		// $penamaanfile= $reqkuncijenis."_".$namajenisfile."_";
		$penamaanfile= $reqFileJenisKode[$i]."-";

		// $linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
		$linkfile= $penamaanfile.$infopenamaan.".".strtolower($filepath);
		$targetsimpan= $folderfilesimpan."/".$linkfile;
		$targetuploadsimpan= $folderfileuploadsimpan."/".$linkfile;

		$cfile= new PermohonanFile();
		$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqkuncijenis, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.PEGAWAI_ID"=>$namajenisfile));
		// echo $cfile->query;exit;
		while($cfile->nextRow())
		{
			// $cfile->firstRow();
			$infofilerowid= $cfile->getField("PERMOHONAN_FILE_ID");
			$infofilelokasi= $cfile->getField("LINK_FILE");
			$infofilelokasi= str_replace("../uploads/", $addtoadminfile."uploads/", $infofilelokasi);
			if(file_exists($infofilelokasi))
			{
				$setfile= new PermohonanFile();
				$setfile->setField("PERMOHONAN_FILE_ID", $infofilerowid);
				$setfile->delete();
				// echo $setfile->query;exit;
				unlink($infofilelokasi);
			}
		}

		if (move_uploaded_file($datafileupload, $targetuploadsimpan))
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
			$setfile->setField("USER_LOGIN_ID", ValToNullDB($tempPegawaiId));
			$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($tempPegawaiId));
			$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($tempPegawaiId));
			$setfile->insert();
			// echo $setfile->query;exit;
			unset($setfile);

			$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
			$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
			$indexpfile++;
		}
	}
}

if($simpan == "1")
	echo $reqId."-Data berhasil disimpan.";
else
	echo "xxx-Data gagal disimpan.";
?>