<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/RiwayatPendidikan.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");

$file = new FileHandler();

//tempNoKtp;tempNama;tempEmail1;tempEmail2;TempatLahir;TanggalLahir;Alamat;Kota;JenisKelamin;tempHp;reqFotoFile;reqKtpFile;reqPendidikanId;SekolahId;JurusanPendidikan;reqFile1;reqTranskipNilaiFile;reqIpk;reqIsLulusanLuarNegeri;KonversiIPKLuarNegeriFile
//TahunDikAwal;TahunDikAkhir;AkreditasiJurusan;AkreditasiSekolah;NoIjazah;
$reqSubmit= httpFilterPost("reqSubmit");
$reqId= httpFilterPost("reqId");
$reqTahunDikAwal= httpFilterPost("reqTahunDikAwal");
$reqTahunDikAkhir= httpFilterPost("reqTahunDikAkhir");
$reqAkreditasiJurusan= httpFilterPost("reqAkreditasiJurusan");
$reqAkreditasiSekolah= httpFilterPost("reqAkreditasiSekolah");
$reqNoIjazah= httpFilterPost("reqNoIjazah");
$reqPendidikanId= httpFilterPost("reqPendidikanId");
$reqSekolahId= httpFilterPost("reqSekolahId");
$reqJurusanPendidikan= httpFilterPost("reqJurusanPendidikan");
$reqJurusanId= httpFilterPost("reqJurusanId");
$reqIpk= httpFilterPost("reqIpk");
$reqIpkLuar= httpFilterPost("reqIpkLuar");
$reqIsLulusanLuarNegeri= httpFilterPost("reqIsLulusanLuarNegeri");
$reqPropinsiId= httpFilterPost("reqPropinsiId");
$reqKabupatenId= httpFilterPost("reqKabupatenId");
$reqUniversitas= httpFilterPost("reqUniversitas");
$reqKota= httpFilterPost("reqKota");

$reqKotaLuar= httpFilterPost("reqKotaLuar");
$reqNegaraLuar= httpFilterPost("reqNegaraLuar");

$reqFotoFile= $_FILES["reqFotoFile"];
$reqKtpFile= $_FILES["reqKtpFile"];
$reqTranskipNilaiFile= $_FILES["reqTranskipNilaiFile"];
$reqKonversiIPKLuarNegeriFile= $_FILES["reqKonversiIPKLuarNegeriFile"];

$reqFile1= $_FILES["reqFile1"];
$reqFile2= $_FILES["reqFile2"];
$reqFile3= $_FILES["reqFile3"];
$reqFile4= $_FILES["reqFile4"];
$reqFile5= $_FILES["reqFile5"];
$reqFile6= $_FILES["reqFile6"];
$reqFile7= $_FILES["reqFile7"];
$reqFile8= $_FILES["reqFile8"];
$reqFile9= $_FILES["reqFile9"];
$reqFile10= $_FILES["reqFile10"];
$reqFile11= $_FILES["reqFile11"];
$reqFile12= $_FILES["reqFile12"];
$reqFile13= $_FILES["reqFile13"];
$reqFile14= $_FILES["reqFile14"];
$reqFile15= $_FILES["reqFile15"];
$reqFile16= $_FILES["reqFile16"];

if($reqSubmit == "insert")
{
  $reqId= $userLogin->userPelamarEnkripId;
  $set= new Pelamar();
  $set->selectByParams(array("md5(CAST(PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
  $set->firstRow();
  $reqId= $set->getField("PELAMAR_ID");
  $reqNoKtp= $set->getField("NO_KTP");
  $reqNpp= $set->getField("NPP");
  $tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
  unset($set);
  
  $set= new RiwayatPendidikan();
  $set->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
  $set->firstRow();
  $reqRowId= $set->getField("RIWAYAT_PENDIDIKAN_ID");
  unset($set);
  
  $set= new RiwayatPendidikan();
  $set->setField("RIWAYAT_PENDIDIKAN_ID", $reqRowId);
  $set->setField("PELAMAR_ID", $reqId);
  $set->setField("PENDIDIKAN_ID", ValToNullDB($reqPendidikanId));
  $set->setField("SEKOLAH_ID", ValToNullDB($reqSekolahId));
  $set->setField("JURUSAN_PENDIDIKAN", $reqJurusanPendidikan);
  $set->setField("JURUSAN_ID", ValToNullDB($reqJurusanId));
  $set->setField("NO_IJAZAH", $reqNoIjazah);
  $set->setField("IPK", $reqIpk);
  $set->setField("IPK_LUAR", $reqIpkLuar);
  $set->setField("TAHUN_DIK_AWAL", $reqTahunDikAwal);
  $set->setField("TAHUN_DIK_AKHIR", $reqTahunDikAkhir);
  $set->setField("AKREDITASI_JURUSAN", $reqAkreditasiJurusan);
  $set->setField("AKREDITASI_SEKOLAH", $reqAkreditasiSekolah);
  $set->setField("IS_LULUSAN_LUAR_NEGERI", ValToNullDB($reqIsLulusanLuarNegeri));
  $set->setField("PROPINSI_ID", ValToNullDB($reqPropinsiId));
  $set->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenId));
  $set->setField("UNIVERSITAS", $reqUniversitas);
  $set->setField("KOTA", $reqKota);
  $set->setField("KOTA_LUAR", $reqKotaLuar);
  $set->setField("NEGARA_LUAR", $reqNegaraLuar);
  
  $reqModeSimpan= "";
  if($reqRowId == "")
  {
	  if($set->insert())
	  	$reqModeSimpan= "1";
  }
  else
  {
	  if($set->update())
	  	$reqModeSimpan= "1";
  }
  //echo $set->query;exit;
  if($reqModeSimpan == "1") 
  {
	//$tempLinkUpload= "$INDEX_ROOT/$INDEX_SUB/assets/valsix/";
	$tempLinkUpload= "../";
	
	$cek = formatTextToDb($file->getFileName('reqFile1'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file1/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file1/";
		
		//makedirs($FILE_DIR, 0755);
		$file = new FileHandler();makedirs($FILE_DIR);
		//echo $FILE_DIR;exit;
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_1");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_1");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile1')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile1')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile1', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if($tempExeFile == "png" || $tempExeFile == "jpeg" || $tempExeFile == "jpg")
			{
				if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 1000))
				{
				}
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_1");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile2'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file2/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file2/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_2");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_2");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile2')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile2')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile2', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_2");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile3'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file3/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file3/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_3");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_3");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile3')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile3')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile3', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_3");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile4'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file4/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file4/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_4");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_4");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile4')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile4')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile4', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_4");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile5'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file5/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file5/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_5");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_5");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile5')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile5')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile5', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_5");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile6'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file6/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file6/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_6");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_6");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile6')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile6')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile6', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_6");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile7'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file7/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file7/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_7");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_7");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile7')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile7')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile7', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_7");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile8'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file8/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file8/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_8");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_8");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile8')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile8')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile8', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_8");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile9'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file9/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file9/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_9");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_9");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile9')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile9')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile9', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_9");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile10'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file10/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file10/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_10");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_10");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile10')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile10')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile10', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_10");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile11'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file11/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file11/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_11");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_11");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile11')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile11')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile11', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_11");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile12'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file12/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file12/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_12");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_12");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile12')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile12')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile12', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_12");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile13'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file13/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file13/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_13");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_13");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile13')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile13')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile13', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_13");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile14'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file14/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file14/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_14");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_14");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile14')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile14')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile14', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_14");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile15'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file15/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file15/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_15");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_15");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile15')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile15')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile15', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_15");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqFile16'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= $tempLinkUpload."uploads/".$reqNoKtp."/file16/";
		$FILE_DIR_TEMP= "uploads/".$reqNoKtp."/file16/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFileCheck=$set_upload->getField("FILE_16");
		$tempLinkFile=$tempLinkUpload.$set_upload->getField("FILE_16");

		$renameFile= str_replace(" ","",substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqFile16')));
		$renameFile= strtolower($renameFile);
		$tempExeFile= strtolower($file->getFileExtension($file->getFileName('reqFile16')));
		$thumbDestination = $FILE_DIR.$renameFile;
		
		if($file->uploadToDir('reqFile16', $FILE_DIR, $renameFile))
		{
			$thumbDestination = $FILE_DIR.$renameFile;
			/*if(!createThumbnailFit($FILE_DIR.$renameFile, $thumbDestination, 100))
			{
			}*/
			
			if($tempLinkFileCheck == '')
			{
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR_TEMP.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "FILE_16");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
	
	$cek = formatTextToDb($file->getFileName('reqTranskipNilaiFile'));
	if($cek == ""){}
	else
	{
		$FILE_DIR= "../uploads/".$reqNoKtp."/TranskipNilai/";
		$file = new FileHandler();makedirs($FILE_DIR);
			
		$set_upload= new RiwayatPendidikan();
		$set_upload->selectByParams(array("PELAMAR_ID"=>$reqId),-1,-1);
		$set_upload->firstRow();
		$tempLinkFile=$set_upload->getField("TRANSKIP_NILAI_FILE");
		//, , , , KONVERSI_IPK_LUAR_NEGRI_FILE
		$renameFile= substr(md5($reqId),0,4).formatTextToDb($file->getFileName('reqTranskipNilaiFile'));
		
		if($file->uploadToDir('reqTranskipNilaiFile', $FILE_DIR, $renameFile))
		{
			if($tempLinkFile == '')
			{
				$tempLinkFile= $FILE_DIR.$renameFile;
			}
			else
			{
				if($file->delete($tempLinkFile)){}
				$tempLinkFile= $FILE_DIR.$renameFile;
			}
			
			$set_upload->setField("TABLE", "RIWAYAT_PENDIDIKAN");
			$set_upload->setField("FIELD", "TRANSKIP_NILAI_FILE");
			$set_upload->setField("FIELD_VALUE", $tempLinkFile);
			$set_upload->setField("FIELD_ID", "PELAMAR_ID");
			$set_upload->setField("FIELD_VALUE_ID", $reqId);
			if($set_upload->updateFormatDynamis()){}
		}
	}
  
  if($tempIsStatusIsiFormulir == "1")
  {
	$set_status_formulir= new Pelamar();
	$set_status_formulir->setField("PELAMAR_ID", $reqId);
	$set_status_formulir->setField("IS_STATUS_ISI_FORMULIR", 2);
	$set_status_formulir->updateStatusIsiFormulir();
	unset($set_status_formulir);
  }
	
  echo $id."-Data berhasil disimpan.";
  }
  //echo $set->query;
}
?>