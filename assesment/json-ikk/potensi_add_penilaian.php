<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterRequest('reqId');
$reqRowId= httpFilterPost("reqRowId");

$reqJabatanTesId= httpFilterPost("reqJabatanTesId");
$reqSatkerTesId= httpFilterPost("reqSatkerTesId");
$reqTanggalTes= httpFilterPost("reqTanggalTes");
$reqNamaAsesi= httpFilterPost("reqNamaAsesi");
$reqMetode= httpFilterPost("reqMetode");

$pegawai= new Kelautan();
$pegawai->selectByParamsMonitoringPegawai(array("A.ID" => $reqId)); 
$pegawai->firstRow();
$tempEselonId= $pegawai->getField("ESELON_PENILAIAN");

$set= new Penilaian();
$set->setField("TANGGAL_TES", dateToDBCheck($reqTanggalTes));
$set->setField("SATKER_TES_ID", $reqSatkerTesId);
$set->setField("JABATAN_TES_ID", $reqJabatanTesId);
$set->setField("PEGAWAI_ID", $reqId);
$set->setField("ASPEK_ID", "1");
$set->setField("ESELON", setNULL($tempEselonId));
$set->setField("NAMA_ASESI", $reqNamaAsesi);
$set->setField("METODE", $reqMetode);

if($reqMode == "insert")
{
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);	
	if($set->insert())
	{
		$mode = 'simpan';
	}
	else
		$mode = 'error';
	//echo $set->query;exit;
	echo "-Data Tersimpan-".$mode;
}
elseif($reqMode == "update")
{
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);	
	$set->setField("PENILAIAN_ID", $reqRowId);
	if($set->update())
	{
		$mode = 'simpan';
	}
	else
		$mode = 'error';
	
	//echo $set->query;
	echo $reqRowId."-Data Tersimpan-".$mode;
}
?>