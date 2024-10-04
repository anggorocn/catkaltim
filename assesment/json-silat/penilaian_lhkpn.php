<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/PenilaianLhkpn.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterRequest('reqId');
$reqRowId= httpFilterPost("reqRowId");

$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqPenilaianId= httpFilterPost("reqPenilaianId");
$reqTipe= httpFilterPost("reqTipe");
$reqTanggalLapor= httpFilterPost("reqTanggalLapor");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqNilai= httpFilterPost("reqNilai");

$set= new PenilaianLhkpn();
$set->setField("PENILAIAN_ID", $reqPenilaianId);
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->setField("TIPE", $reqTipe);
$set->setField("NILAI", $reqNilai);
$set->setField("TANGGAL_LAPOR", $reqTanggalLapor);

if($reqId == "")
	$reqMode= "insert";
else
	$reqMode= "update";

if($reqMode == "insert")
{
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);	
	if($set->insert())
	{
		$reqId= $set->id;
		$mode = 'simpan';
	}
	else
		$mode = 'error';
	//echo $set->query;exit;
	echo $reqId."-Data Tersimpan-".$mode;
}
elseif($reqMode == "update")
{
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
	if($set->update())
	{
		$mode = 'simpan';
	}
	else
		$mode = 'error';
	
	//echo $set->query;
	echo $reqId."-Data Tersimpan-".$mode;
}
?>