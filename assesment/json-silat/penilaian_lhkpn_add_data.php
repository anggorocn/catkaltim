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

$set= new PenilaianLhkpn();

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterRequest('reqId');
$reqRowId= httpFilterRequest("reqRowId");
$reqPegawaiId= httpFilterRequest("reqPegawaiId");


$reqTipe= httpFilterPost("reqTipe");
$reqTanggalLapor= httpFilterPost("reqTanggalLapor");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqNilai= httpFilterPost("reqNilai");
$reqPegawaiId= httpFilterPost("reqPegawaiId");


$set->setField("PENILAIAN_LHKPN_ID", $reqRowId);
$set->setField("TIPE", $reqTipe);
$set->setField("TANGGAL_LAPOR", dateToDBCheck($reqTanggalLapor));
$set->setField("KETERANGAN", $reqKeterangan);
$set->setField("NILAI", 1);
$set->setField("PEGAWAI_ID", $reqPegawaiId);

if($reqMode == "insert")
{
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);	
	if($set->insert())
	{
		$reqRowId= $set->id;
		$mode = 'simpan';
	}
	else
		$mode = 'error';
	//echo $set->query;exit;
	echo $reqId."-Data Tersimpan-".$reqRowId;

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
	
	//echo $set->query;exit;
	echo $reqId."-Data Tersimpan-".$mode;
}
?>