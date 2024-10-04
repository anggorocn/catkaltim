<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Propinsi.php");


$provinsi	= new Propinsi();
$provinsiCheck	= new Propinsi();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqNama 		= httpFilterPost("reqNama");

$provinsi->setField('NAMA', $reqNama);	

if($reqMode == "insert")
{
	$provinsi->setField("LAST_CREATE_USER", $userLogin->nama);
	$provinsi->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	
	if($provinsi->insert())
	{
		$reqId = $provinsi->id;
		$mode = 'Data berhasil disimpan';
	}
	else
	//echo $provinsi->query;exit;
		$mode = 'Data gagal disimpan';
			
	echo $mode;
}
elseif($reqMode == "update")
{
	$provinsi->setField("LAST_UPDATE_USER", $userLogin->nama);
	$provinsi->setField("LAST_UPDATE_DATE", "CURRENT_DATE");			
	$provinsi->setField('PROPINSI_ID',$reqId);
	
	if($provinsi->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>