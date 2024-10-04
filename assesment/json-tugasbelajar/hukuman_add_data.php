<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-tugasbelajar/Hukuman.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* CREATE OBJECT*/
$hukuman = new Hukuman();

$reqMode					= httpFilterRequest("reqMode");
$reqId						= httpFilterRequest('reqId');

$reqPegawaiId				= httpFilterRequest('reqPegawaiId');

$reqPejabatPenetapId 		= httpFilterPost("reqPejabatPenetapId");
$reqTingkatHukuman 			= httpFilterPost("reqTingkatHukuman");
$reqPeraturan 				= httpFilterPost("reqPeraturan");
$reqMasihBerlaku 			= httpFilterPost("reqMasihBerlaku");
$reqJenisHukuman			= httpFilterPost("reqJenisHukuman");
$reqNoSK					= httpFilterPost("reqNoSK");
$reqTanggalSK				= httpFilterPost("reqTanggalSK");
$reqTMTSK					= httpFilterPost("reqTMTSK");
$reqPermasalahan			= httpFilterPost("reqPermasalahan");

$reqTanggalMulai			= httpFilterPost("reqTanggalMulai");
$reqTanggalAkhir			= httpFilterPost("reqTanggalAkhir");

if($reqMode == "insert")
{
	$hukuman->setField('PEGAWAI_ID', $reqPegawaiId);
	$hukuman->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
	$hukuman->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
	$hukuman->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));
	$hukuman->setField('NO_SK', $reqNoSK);
	$hukuman->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSK));
	$hukuman->setField('TMT_SK', dateToDBCheck($reqTMTSK));
	$hukuman->setField('JENIS_HUKUMAN_ID', ValToNullDB($reqJenisHukuman));
	$hukuman->setField('TINGKAT_HUKUMAN_ID', ValToNullDB($reqTingkatHukuman));
	$hukuman->setField('PERATURAN_ID', ValToNullDB($reqPeraturan));
	$hukuman->setField('KETERANGAN', $reqPermasalahan);
	$hukuman->setField('PEGAWAI_ID',$reqPegawaiId);
	$hukuman->setField('BERLAKU',ValToNullDB((int)$reqMasihBerlaku));
	$hukuman->setField("LAST_CREATE_USER", $userLogin->idUser);
	$hukuman->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$hukuman->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
	if($hukuman->insert())
	{
		$mode = 'simpan';
	}
	else
		$mode = 'error';
		
	echo "-Data Tersimpan-".$mode;
}
elseif($reqMode == "update")
{
	$hukuman->setField('PEGAWAI_ID', $reqPegawaiId);
	$hukuman->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
	$hukuman->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
	$hukuman->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));
	$hukuman->setField('HUKUMAN_ID', $reqRowId);
	$hukuman->setField('NO_SK', $reqNoSK);
	//$hukuman->setField('PEJABAT_PENETAP_ID', $reqPjPenetap);
	$hukuman->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSK));
	$hukuman->setField('TMT_SK', dateToDBCheck($reqTMTSK));
	$hukuman->setField('JENIS_HUKUMAN_ID', ValToNullDB($reqJenisHukuman));
	$hukuman->setField('TINGKAT_HUKUMAN_ID', ValToNullDB($reqTingkatHukuman));
	$hukuman->setField('PERATURAN_ID', ValToNullDB($reqPeraturan));
	$hukuman->setField('KETERANGAN', $reqPermasalahan);
	$hukuman->setField('PEGAWAI_ID',$reqPegawaiId);
	$hukuman->setField('BERLAKU',ValToNullDB((int)$reqMasihBerlaku));
	$hukuman->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$hukuman->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$hukuman->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);	
	$hukuman->setField("HUKUMAN_ID", $reqId);
	
	if($hukuman->update())
	{
		$mode = 'simpan';
	}
	else
		$mode = 'error';
	echo "-Data Tersimpan-".$mode;
}
?>