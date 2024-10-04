<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/TipePelatihan.php");


$tipe_ujian	= new TipePelatihan();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");
$reqKodePelatihan= httpFilterPost("reqKodePelatihan");
$reqNamaPelatihan= httpFilterPost("reqNamaPelatihan");

$tipe_ujian->setField('KODE_PELATIHAN', $reqKodePelatihan);
$tipe_ujian->setField('NAMA_TIPE_PELATIHAN', $reqNamaPelatihan);

//echo $reqMode;exit;

if($reqMode == "insert")
{
	
	if($tipe_ujian->insert())
	{
		$reqId = $tipe_ujian->id;
		$mode = 'Data berhasil disimpan';
	}
	else
	//echo $tipe_ujian->query;exit;
		$mode = 'Data gagal disimpan';
			
	echo $mode;
}
elseif($reqMode == "update")
{
	$tipe_ujian->setField('TIPE_PELATIHAN_ID',$reqId);
	
	if($tipe_ujian->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>