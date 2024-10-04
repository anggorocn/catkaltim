<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/classes/base-cat/BankSoal.php");
include_once("../WEB/classes/base-cat/BankSoalPilihan.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");

$bank_soal = new BankSoal();
$bank_soal_pilihan = new BankSoalPilihan();
$file = new FileHandler();
$file_jawaban = new FileHandler();

$reqId 		= httpFilterPost("reqId");
$reqMode 	= httpFilterPost("reqMode");
$reqPertanyaanText= httpFilterPost("reqPertanyaanText");
$reqPertanyaan= httpFilterPost("reqPertanyaan");
$reqKemampuan	= httpFilterPost("reqKemampuan");
$reqKategori	= httpFilterPost("reqKategori");
$reqTipeSoal	= httpFilterPost("reqTipeSoal");
$reqTipeUjian	= httpFilterPost("reqTipeUjian");

if($reqTipeSoal == "1" || $reqTipeSoal == "4" || $reqTipeSoal == 5)
{
	$reqPertanyaan= $reqPertanyaanText;
}
//echo $reqTipeSoal."-".$reqPertanyaan."-".$reqPertanyaanText;exit;
$reqRowId		= $_POST["reqRowId"];
$reqJawaban		= $_POST["reqJawaban"];
$reqGradeProsentase	= $_POST["reqGradeProsentase"];
//print_r($reqJawaban);exit;
$awalDetil= 0;
$akhirDetil= 5;
if($reqTipeSoal == 1 || $reqTipeSoal == 4 || $reqTipeSoal == 5){}
else
{
	$awalDetil= 6;
	$akhirDetil= 11;
}

//echo $reqTipeSoal;exit;
//print_r($reqGradeProsentase);exit;
$reqLinkFile = $_FILES["reqLinkFile"];
$reqLinkFileTemp= $_POST["reqLinkFileTemp"];

$reqLinkFileJawaban = $_FILES["reqLinkFileJawaban"];
$reqLinkFileJawabanTemp= $_POST["reqLinkFileJawabanTemp"];

$xmlfile = "../WEB/websetting.xml";
$data= simplexml_load_file($xmlfile);
$urlimage= $data->urlConfig->setting->urlimage;

//$FILE_DIR_SOAL = "../main/uploads/bank_soal/";
//$FILE_DIR_JAWABAN = "../main/uploads/bank_soal_pilihan/";
$FILE_DIR_SOAL= $urlimage."uploads/bank_soal/";
$FILE_DIR_JAWABAN= $urlimage."uploads/bank_soal_pilihan/";

$bank_soal->setField('PERTANYAAN', $reqPertanyaan);
$bank_soal->setField('KEMAMPUAN', $reqKemampuan);
$bank_soal->setField('KATEGORI', $reqKategori);
$bank_soal->setField('TIPE_SOAL', ValToNullDB($reqTipeSoal));
$bank_soal->setField('TIPE_UJIAN_ID', ValToNullDB($reqTipeUjian));

$tempStatus= "";
if($reqMode == "insert")
{
	$bank_soal->setField("LAST_CREATE_USER", $userLogin->nama);
	$bank_soal->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	if($bank_soal->insert())
	{
		$reqId= $bank_soal->id;
		$tempStatus= "1";
	}
}
else
{
	$bank_soal->setField("BANK_SOAL_ID", $reqId); 
	$bank_soal->setField("LAST_UPDATE_USER", $userLogin->nama);
	$bank_soal->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	if($bank_soal->update())
	{
		$tempStatus= "1";
		//echo $reqId."-Data berhasil disimpan.";
	}
	//echo $reqAddon;
}
//echo $bank_soal->query;exit;
if($tempStatus == "1")
{
	$statement= " AND TIPE_UJIAN_ID= ".$reqTipeUjian;
	$tipe_ujian= new TipeUjian();
	$tipe_ujian->selectByParams(array(), -1,-1, $statement);
	$tipe_ujian->firstRow();
	$tempTipe= $tipe_ujian->getField("TIPE");
	$tempTipe= str_replace(" ","_",$tempTipe);
	unset($tipe_ujian);
	
	$tempPathGambar= $FILE_DIR_SOAL.$tempTipe."/";
	$tempPathDetilGambar= $FILE_DIR_SOAL.$tempTipe."/";
	//$tempPathDetilGambar= $FILE_DIR_JAWABAN.$tempTipe."/";
	//echo $tempPathGambar;exit;
	makedirs($tempPathGambar);
	$link_file = new BankSoal();
	$link_file->setField("BANK_SOAL_ID", $reqId);
	$link_file->setField("PATH_GAMBAR", $tempPathGambar);
	$link_file->uploadFile();
	unset($link_file);
	
	$cek = formatTextToDb($file->getFileName('reqLinkFile'));
	if($cek == "")
	{
		$insertLinkFile = $reqLinkFileTemp;
	}
	else
	{
		$renameFile = $reqId."_".date('dm')."_".formatTextToDb($file->getFileName('reqLinkFile'));
		//$varSource=$FILE_DIR_SOAL.$reqLinkFileTemp;
		$varSource=$tempPathGambar.$reqLinkFileTemp;
		//if($file->uploadToDir('reqLinkFile', $FILE_DIR_SOAL, $renameFile))
		if($file->uploadToDir('reqLinkFile', $tempPathGambar, $renameFile))
		{			
			if($reqLinkFileTemp == ''){}
			else
			{
				if($file->delete($varSource)){
				}
			}
	
			$insertLinkFile = $file->uploadedFileName;
			$insertLinkFilesSize = $file->uploadedSize;
			$insertLinkFilesExe = $file->uploadedExtension;
		}	
	}

	$link_file = new BankSoal();
	$link_file->setField("BANK_SOAL_ID", $reqId);
	$link_file->setField("PATH_SOAL", $insertLinkFile);
	$link_file->uploadSoalFile();
	//echo $link_file->query;exit;
	unset($link_file);
	
	//for($i=0; $i<=5; $i++)
	$x=0;
	for($i=$awalDetil; $i<=$akhirDetil; $i++)
	{
		$bank_soal_pilihan = new BankSoalPilihan();
		$bank_soal_pilihan->setField("BANK_SOAL_ID", $reqId);
		$bank_soal_pilihan->setField("BANK_SOAL_PILIHAN_ID", $reqRowId[$i]);
		$bank_soal_pilihan->setField("JAWABAN", $reqJawaban[$i]);
		$bank_soal_pilihan->setField("GRADE_PROSENTASE", ValToNullDB($reqGradeProsentase[$i]));
		
		if($reqRowId[$i] == "")
		{
			$bank_soal_pilihan->setField("LAST_CREATE_USER", $userLogin->nama);
			$bank_soal_pilihan->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			if($bank_soal_pilihan->insert())
			{
				$tempRowId = $bank_soal_pilihan->id;
			}
		}
		else
		{
			$bank_soal_pilihan->setField("LAST_UPDATE_USER", $userLogin->nama);
			$bank_soal_pilihan->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
			$bank_soal_pilihan->setField("BANK_SOAL_PILIHAN_ID", $reqRowId[$i]);
			if($bank_soal_pilihan->update())
			{
				$tempRowId = $reqRowId[$i];
			}
		}
		//echo $bank_soal_pilihan->query;exit;
		$cek = formatTextToDb($file_jawaban->getFileNameArray('reqLinkFileJawaban',$x));
		if($cek == "")
		{
			if($reqLinkFileJawabanTemp[$x] == "")
			$insertLinkFile = "";
			else
			$insertLinkFile = $reqLinkFileJawabanTemp[$x];
		}
		else
		{
			$renameFile = $tempRowId.'_'.date('dm')."_".formatTextToDb($file_jawaban->getFileNameArray('reqLinkFileJawaban',$x));
			//$varSource=$FILE_DIR_JAWABAN.$reqLinkFileJawabanTemp[$i];
			//makedirs($tempPathDetilGambar);
			
			$varSource=$tempPathDetilGambar.$reqLinkFileJawabanTemp[$x];
			//if($file_jawaban->uploadToDirArray('reqLinkFileJawaban', $FILE_DIR_JAWABAN, $renameFile, $x))
			if($file_jawaban->uploadToDirArray('reqLinkFileJawaban', $tempPathDetilGambar, $renameFile, $x))
			{
				
				if($reqLinkFileJawabanTemp[$i] == ''){}
				else
				{
					if($file_jawaban->delete($varSource)){
					}
				}
		
				$insertLinkFile = $file_jawaban->uploadedFileName;
				$insertLinkFilesSize = $file_jawaban->uploadedSize;
				$insertLinkFilesExe = $file_jawaban->uploadedExtension;
			}	
		}
		
		$link_file = new BankSoalPilihan();
		$link_file->setField("BANK_SOAL_PILIHAN_ID", $tempRowId);
		$link_file->setField("PATH_GAMBAR", $insertLinkFile);
		$link_file->uploadFile();
		//echo $link_file->query;exit;
		unset($link_file);
		
		if($reqTipeSoal == "1" || $reqTipeSoal == "4" || $reqTipeSoal == "5"){}
		else
		{
		$link_file = new BankSoalPilihan();
		$link_file->setField("BANK_SOAL_PILIHAN_ID", $tempRowId);
		$link_file->setField("JAWABAN", $insertLinkFile);
		$link_file->uploadJawabanFile();
		//echo $link_file->query;exit;
		unset($link_file);
		}
		
		unset($bank_soal_pilihan);
		$x++;
	}
	echo $reqId."-Data Berhasil Disimpan.";
}
else
{
	echo $reqId."-Data Gagal Disimpan.";
	//echo $design->query;
}
?>