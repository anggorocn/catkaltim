<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAcara.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$jadwal_acara		= new JadwalAcara();

$reqMode 			= httpFilterRequest("reqMode");
$reqRowId= httpFilterPost("reqRowId");

$reqId= httpFilterPost("reqId");
$reqPenggalianId= httpFilterPost("reqPenggalianId");
$reqPukul1= httpFilterPost("reqPukul1");
$reqPukul2= httpFilterPost("reqPukul2");
$reqKeterangan= httpFilterPost("reqKeterangan");

$jadwal_acara->setField('JADWAL_TES_ID', $reqId);
$jadwal_acara->setField('PENGGALIAN_ID', ValToNullDB($reqPenggalianId));
$jadwal_acara->setField('PUKUL1', $reqPukul1);
$jadwal_acara->setField('PUKUL2', $reqPukul2);
$jadwal_acara->setField('KETERANGAN', $reqKeterangan);

if($reqRowId == "")
{
	$jadwal_acara->setField("LAST_CREATE_USER", $userLogin->idUser);
	$jadwal_acara->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	
	if($jadwal_acara->insert())
	{
		$mode = 'Data berhasil disimpan';
		//echo $jadwal_acara->query;exit;	
	}
	else {
		$mode = 'Data gagal disimpan';
		//echo $jadwal_acara->query;exit;
	}
	
	echo $mode;
}
else
{
	$jadwal_acara->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$jadwal_acara->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$jadwal_acara->setField('JADWAL_ACARA_ID',$reqRowId);
	
	if($jadwal_acara->update())
	{
		$mode = 'Data berhasil disimpan';
		//echo $jadwal_acara->query;exit;
	}
	else {
		$mode = 'Data gagal disimpan';
		//echo $jadwal_acara->query;exit;
	}
	
	echo $mode;
}
?>