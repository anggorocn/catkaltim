<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Ujian.php");


$ujian	= new Ujian();

$reqMode 		= httpFilterPost("reqMode");
$reqId			= httpFilterPost("reqId");
$reqTglMulai	= httpFilterPost("reqTglMulai");
$reqTglSelesai	= httpFilterPost("reqTglSelesai");
$reqNilaiLulus	= httpFilterPost("reqNilaiLulus");
$reqBatasWaktu	= httpFilterPost("reqBatasWaktu");
$reqStatus	= httpFilterPost("reqStatus");

$reqLowonganId= httpFilterPost("reqLowonganId");
$reqLowonganTahapanId= httpFilterPost("reqLowonganTahapanId");

$reqNama 		= httpFilterPost("reqNama");


$ujian->setField('LOWONGAN_ID', $reqLowonganId);
$ujian->setField('LOWONGAN_TAHAPAN_ID', $reqLowonganTahapanId);
$ujian->setField('STATUS', $reqStatus);	
$ujian->setField('TGL_MULAI', dateToDBCheck($reqTglMulai));	
$ujian->setField('TGL_SELESAI', dateToDBCheck($reqTglMulai));	
$ujian->setField('STATUS', $reqStatus);	
$ujian->setField('NILAI_LULUS', ValToNullDB($reqNilaiLulus));	
$ujian->setField('BATAS_WAKTU_MENIT', ValToNullDB($reqBatasWaktu));	

if($reqMode == "insert")
{
	$ujian->setField("LAST_CREATE_USER", $userLogin->nama);
	$ujian->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	
	if($ujian->insert())
	{
		$reqId = $ujian->id;
		
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';
			
	echo $reqId."-".$mode;
}
elseif($reqMode == "update")
{
	$ujian->setField("LAST_UPDATE_USER", $userLogin->nama);
	$ujian->setField("LAST_UPDATE_DATE", "CURRENT_DATE");			
	$ujian->setField('UJIAN_ID',$reqId);
	
	if($ujian->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $reqId."-".$mode;
}
//echo $ujian->query;exit;
?>