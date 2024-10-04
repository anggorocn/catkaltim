<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAwalTes.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set= new JadwalAwalTes();

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");

$reqFormulaEselonId= httpFilterPost("reqFormulaEselonId");
$reqTanggalTes= httpFilterPost("reqTanggalTes");
$reqTanggalTesAkhir= httpFilterPost("reqTanggalTesAkhir");
$reqAcara= httpFilterPost("reqAcara");
$reqTempat= httpFilterPost("reqTempat");
$reqAlamat= httpFilterPost("reqAlamat");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqStatusJenis= httpFilterPost("reqStatusJenis");

$set->setField('FORMULA_ESELON_ID', $reqFormulaEselonId);
$set->setField('TANGGAL_TES', dateToDBCheck($reqTanggalTes));
$set->setField('TANGGAL_TES_AKHIR', dateToDBCheck($reqTanggalTesAkhir));
$set->setField('ACARA', $reqAcara);
$set->setField('TEMPAT', $reqTempat);
$set->setField('ALAMAT', $reqAlamat);
$set->setField('KETERANGAN', $reqKeterangan);
$set->setField('STATUS_JENIS', 1);
$set->setField('JADWAL_AWAL_TES_ID',$reqId);

if($reqMode == "insert")
{
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	
	if($set->insert())
	{
		$reqId= $set->id;
		$mode = 'Data berhasil disimpan';
		//echo $set->query;exit;
	}
	else
	{
		$reqId= "xxx";
		$mode = 'Data gagal disimpan';
	}
	//echo $set->query;exit;
	echo $reqId."-".$mode;
}
elseif($reqMode == "update")
{
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	
	if($set->update())
	{
		$mode = 'Data berhasil disimpan';
		//echo $set->query;exit;
	}
	else
	{
		$reqId= "xxx";
		$mode = 'Data gagal disimpan';
	}
		
	// echo $set->query;exit;
	echo $reqId."-".$mode;
}
?>