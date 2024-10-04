<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-tugasbelajar/TugasBelajar.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterRequest('reqId');
$reqRowId= httpFilterPost("reqRowId");

$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqNoSk= httpFilterPost("reqNoSk");
$reqJurusan= httpFilterPost("reqJurusan");
$reqSatkerId= httpFilterPost("reqSatkerId");
$reqPendidikan= httpFilterPost("reqPendidikan");
$reqNamaSekolah= httpFilterPost("reqNamaSekolah");
$reqPembiayaan= httpFilterPost("reqPembiayaan");
$reqStatusBelajar= httpFilterPost("reqStatusBelajar");
$reqSatkerIdEselon= '';
$reqStatusIjin= '';
$reqTmtMulai= httpFilterPost("reqTmtMulai");
$reqTmtSelesai= httpFilterPost("reqTmtSelesai");
$reqTipeTugas= httpFilterPost("reqTipeTugas");

$set= new TugasBelajar();
$set->setField("TUGAS_BELAJAR_ID", $reqId);
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->setField("NO_SK", $reqNoSk);
$set->setField("JURUSAN", $reqJurusan);
$set->setField("PENDIDIKAN", $reqPendidikan);
$set->setField("NAMA_SEKOLAH", $reqNamaSekolah);
$set->setField("SATKER_ID", $reqSatkerId);
$set->setField("SATKER_ID_ESELON", $reqSatkerIdEselon);
$set->setField("STATUS_IJIN", $reqStatusIjin);
$set->setField("STATUS_BELAJAR", $reqStatusBelajar);
$set->setField("TMT_MULAI", dateToDBCheck($reqTmtMulai));
$set->setField("TMT_SELESAI", dateToDBCheck($reqTmtSelesai));
$set->setField("TMT_PERPANJANGAN", dateToDBCheck($reqTmtPerpanjangan));
$set->setField("TMT_AKTIF", dateToDBCheck($reqTmtAktif));
$set->setField("PEMBIAYAAN", $reqPembiayaan);
$set->setField("TIPE_TUGAS", $reqTipeTugas);

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