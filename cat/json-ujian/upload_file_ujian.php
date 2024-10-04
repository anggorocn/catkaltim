<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base-cat/UploadFileUjian.php");

$reqId= $userLogin->userPelamarId;
$sessNama= $userLogin->nama;
$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$tempUjianId= $ujianPegawaiUjianId;

$file = new FileHandler();
$reqRowId= httpFilterPost("reqRowId");
$reqNip= httpFilterPost("reqNip");
$reqLinkFile= $_FILES["reqLinkFile"];

$FILE_DIR		= "../upload/file/".$tempPegawaiId."/".$tempUjianId."/";

makedirs($FILE_DIR);
// echo $urlfoto;exit();
$fileName = basename($_FILES["reqLinkFile"]["name"]);

$target_file= $fileName;

$urlupload1= $FILE_DIR.$fileName;
// echo $urlupload3;exit;

// echo "xxx-".$imageFileType;exit();
// echo $fileName3;exit;

$set_check= new UploadFileUjian();
$statement= " AND A.PEGAWAI_ID = ".$userLogin->pegawaiId." AND A.JADWAL_TES_ID = ".$ujianPegawaiJadwalTesId." AND A.UJIAN_ID = ".$ujianPegawaiUjianId;
$set_check->selectByParams(array(), -1, -1, $statement);
    // echo $set_check->query;exit;
$set_check->firstRow();
$TempLinkFile1 = $set_check->getField('LINK_FILE');
// var_dump($TempLinkFile1) ;exit;
// echo $TempLinkFoto;exit; 
$namaupload = 'Dokumen 1';

$set= new UploadFileUjian();
$set->setField("NAMA", $namaupload);
$set->setField("PEGAWAI_ID", $tempPegawaiId);
$set->setField("JADWAL_TES_ID", $ujianPegawaiJadwalTesId);
$set->setField("UJIAN_ID", $tempUjianId);


$set->setField("LINK_FILE", $urlupload1);

$tempSimpan= "";
 if($TempLinkFile1 == "")
 {
 	if($set->insert())
	{
		$reqUploadId= $set->id;
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
	
	$renameFile= $fileName;
		// echo $renameFile;exit();
	if (move_uploaded_file($reqLinkFile['tmp_name'], $FILE_DIR.$renameFile))
	{
	}

	echo $fileName."-Data berhasil di simpan";
}
?>