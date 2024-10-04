<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/utils/FileHandler.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$file = new FileHandler();
$jadwal_tes	= new JadwalTes();

$reqLinkSoalIntray= $_FILES["reqLinkSoalIntray"];

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");
$reqFormulaEselonId= httpFilterPost("reqFormulaEselonId");
$reqTanggalTes= httpFilterPost("reqTanggalTes");
$reqBatch= httpFilterPost("reqBatch");
$reqAcara= httpFilterPost("reqAcara");
$reqTempat= httpFilterPost("reqTempat");
$reqAlamat= httpFilterPost("reqAlamat");
$reqTtdAsesor= httpFilterPost("reqTtdAsesor");
$reqNipAsesor= httpFilterPost("reqNipAsesor");
$reqTtdPimpinan= httpFilterPost("reqTtdPimpinan");
$reqNipPimpinan= httpFilterPost("reqNipPimpinan");

$reqKeterangan= httpFilterPost("reqKeterangan");
$reqStatusPenilaian= httpFilterPost("reqStatusPenilaian");
$reqStatusValid= httpFilterPost("reqStatusValid");
$reqJumlahRuangan= httpFilterPost("reqJumlahRuangan");
$reqTanggalTesTTd= httpFilterPost("reqTanggalTesTTd");

$FILE_DIR		= "../upload/soal/".$reqId."/";
makedirs($FILE_DIR);
$fileName = basename($_FILES["reqLinkSoalIntray"]["name"]);
$urllinksoal= $FILE_DIR.$fileName;
// echo $urllinksoal;exit;

$jadwal_tes->setField('FORMULA_ESELON_ID', $reqFormulaEselonId);
$jadwal_tes->setField('TANGGAL_TES', dateToDBCheck($reqTanggalTes));
$jadwal_tes->setField('BATCH', $reqBatch);
$jadwal_tes->setField('ACARA', $reqAcara);
$jadwal_tes->setField('TEMPAT', $reqTempat);
$jadwal_tes->setField('ALAMAT', $reqAlamat);
$jadwal_tes->setField('NIP_ASESOR', $reqNipAsesor);
$jadwal_tes->setField('TTD_ASESOR', $reqTtdAsesor);
$jadwal_tes->setField('NIP_PIMPINAN', $reqNipPimpinan);
$jadwal_tes->setField('TTD_PIMPINAN', $reqTtdPimpinan);
$jadwal_tes->setField('KETERANGAN', $reqKeterangan);
$jadwal_tes->setField('STATUS_PENILAIAN', $reqStatusPenilaian);
$jadwal_tes->setField('STATUS_VALID', ValToNullDB($reqStatusValid));
$jadwal_tes->setField('TTD_TANGGAL', dateToDBCheck($reqTanggalTesTTd));
$jadwal_tes->setField('JUMLAH_RUANGAN', ValToNullDB($reqJumlahRuangan));
$jadwal_tes->setField("LINK_SOAL", $urllinksoal);

// print_r($_FILES);exit;
$tempsimpan = "";
if($reqMode == "insert")
{
	$jadwal_tes->setField("LAST_CREATE_USER", $userLogin->idUser);
	$jadwal_tes->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	
	if($jadwal_tes->insert())
	{
		$reqId= $jadwal_tes->id;
		$mode = 'Data berhasil disimpan';
		$tempsimpan = "1";
		//echo $jadwal_tes->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';
		//echo $jadwal_tes->query;exit;
	echo $reqId."-".$mode;
}
elseif($reqMode == "update")
{
	$jadwal_tes->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$jadwal_tes->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$jadwal_tes->setField('JADWAL_TES_ID',$reqId);
	
	if($jadwal_tes->update())
	{
		$mode = 'Data berhasil disimpan';
		$tempsimpan = "1";
		//echo $jadwal_tes->query;exit;
	}
	else
		$mode = 'Data gagal disimpan';
		
	//echo $jadwal_tes->query;exit;
	echo $reqId."-".$mode;
}

$renameFile= $fileName;

if($tempsimpan == "1")
{
	if (move_uploaded_file($reqLinkSoalIntray['tmp_name'], $FILE_DIR.$renameFile))
	{
	}
}


?>