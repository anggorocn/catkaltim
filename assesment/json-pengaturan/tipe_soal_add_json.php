<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");


$tipe_ujian	= new TipeUjian();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqTipeUjian= httpFilterPost("reqTipeUjian");
$reqKategoriSoal= httpFilterPost("reqKategoriSoal");

$tipe_ujian->setField('TIPE', $reqTipeUjian);
$tipe_ujian->setField('KATEGORI', $reqKategoriSoal);

//echo $reqMode;exit;

if($reqMode == "insert")
{
	$tipe_ujian->setField("LAST_CREATE_USER", $userLogin->nama);
	$tipe_ujian->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	
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
	$tipe_ujian->setField("LAST_UPDATE_USER", $userLogin->nama);
	$tipe_ujian->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$tipe_ujian->setField('TIPE_UJIAN_ID',$reqId);
	
	if($tipe_ujian->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>