<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base-diklat/Peserta.php");

$reqId= $userLogin->userPelamarId;

if($reqId == "")
{
	exit();
}

$file = new FileHandler();
$reqKtp= httpFilterPost("reqKtp");
$reqNama= httpFilterPost("reqNama");
$reqNIP= httpFilterPost("reqNIP");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqTempatLahir= httpFilterPost("reqTempatLahir");
$reqTanggalLahir= httpFilterPost("reqTanggalLahir");
$reqAgama= httpFilterPost("reqAgama");
$reqEmail= httpFilterPost("reqEmail");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqStatusKawin= httpFilterPost("reqStatusKawin");
$reqAlamat= httpFilterPost("reqAlamat");
$reqStatusPegawai= httpFilterPost("reqStatusPegawai");
$reqTempatKerja= httpFilterPost("reqTempatKerja");
$reqAlamatTempatKerja= httpFilterPost("reqAlamatTempatKerja");
$reqSosmed= httpFilterPost("reqSosmed");
$reqAuto= httpFilterPost("reqAuto");

$reqHp= httpFilterPost("reqHp");
$reqFotoFile= $_FILES["reqFotoFile"];

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlfoto= $data->urlConfig->main->urlfoto;

$FILE_DIR= $urlfoto."/".$reqId."/";
makedirs($FILE_DIR);
// echo $urlfoto;exit();

$fileName = basename($_FILES["reqFotoFile"]["name"]);
$target_file= $fileName;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// echo "xxx-".$imageFileType;exit();
if($imageFileType == ""){}
else
{
	if($imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "jpg") {}
	else
	{
		echo "xxx-Data gagal disimpan, foto harus dengan format png/jpeg/jpg";
		// echo '<script language="javascript">';
		// // echo 'alert("Username atau password anda masih salah.");';
		// echo 'top.location.href = "../main/index.php?pg=diklat_upload_spt&reqRowId='.$reqDiklatId.'";';
		// echo '</script>';
		exit();
	}
}
$tempPesertaId= $reqId;
$set= new Peserta();

$set->setField("NIK", $reqKtp);
$set->setField("NAMA", ucwordsPertama($reqNama));
$set->setField("JENIS_KELAMIN", $reqJenisKelamin);
$set->setField("TEMPAT_LAHIR", ucwordsPertama($reqTempatLahir));
$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTanggalLahir));
$set->setField("AGAMA", $reqAgama);
$set->setField("EMAIL", $reqEmail);
$set->setField("STATUS_KAWIN", $reqStatusKawin);
$set->setField("ALAMAT", $reqAlamat);
$set->setField("STATUS_PEGAWAI_ID", ValToNullDB($reqStatusPegawai));
$set->setField("TEMPAT_KERJA", $reqTempatKerja);
$set->setField("ALAMAT_TEMPAT_KERJA", $reqAlamatTempatKerja);
$set->setField("SOSIAL_MEDIA", $reqSosmed);
$set->setField("AUTO_ANAMNESA", $reqAuto);

$set->setField("HP", $reqHp);
$set->setField("PESERTA_ID", $tempPesertaId);

$tempPesertaSimpan= "";

if($set->updateDataPribadi())
{
	// untuk upload file
	if($reqFotoFile['name'] == ""){}
	else			
	{
		// $renameFile= $reqPegawaiId.".".strtolower(getExtension($reqLinkFile['name']));
		$renameFile= $reqNIP.".png";
		// echo $renameFile;exit();
		if (move_uploaded_file($reqFotoFile['tmp_name'], $FILE_DIR.$renameFile))
		{
		}
	}

	echo $tempPesertaId."-Data berhasil di simpan";
}
else
	echo "xxx-Data gagal disimpan.";
// echo $set->query;exit;
unset($set);
?>