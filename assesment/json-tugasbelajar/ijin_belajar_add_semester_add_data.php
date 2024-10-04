<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-tugasbelajar/TugasBelajarLapor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterRequest('reqId');
$reqRowId= httpFilterRequest("reqRowId");

$reqTugasBelajarId= httpFilterPost("reqTugasBelajarId");
$reqTanggal= httpFilterPost("reqTanggal");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqStatusBelajar= httpFilterPost("reqStatusBelajar");
$reqSemester= httpFilterPost("reqSemester");
$reqTipeTugas= httpFilterPost("reqTipeTugas");


$set= new TugasBelajarLapor();
$set->setField("TUGAS_BELAJAR_LAPOR_ID", $reqRowId);
$set->setField("TUGAS_BELAJAR_ID", $reqId);
$set->setField("KETERANGAN", $reqKeterangan);
$set->setField("STATUS_BELAJAR", $reqStatusBelajar);
$set->setField("SEMESTER", $reqSemester);
$set->setField("TANGGAL", dateToDBCheck($reqTanggal));
$set->setField("TIPE_TUGAS", $reqTipeTugas);

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