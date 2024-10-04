<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/Kabupaten.php");
include_once("../WEB/classes/utils/UserLogin.php");

$kabupaten = new Kabupaten();

$reqId 		= httpFilterPost("reqId");
$reqMode 	= httpFilterPost("reqMode");
$reqPropinsiId 	= httpFilterPost("reqPropinsiId");
$reqNama	= httpFilterPost("reqNama");


$kabupaten->setField('PROPINSI_ID', $reqPropinsiId);
$kabupaten->setField('NAMA', $reqNama);

if($reqMode == "insert")
{
	if($kabupaten->insert())
		echo "Data berhasil disimpan.";
		//echo $kabupaten->query;exit;
}
else
{
	$kabupaten->setField('KABUPATEN_ID', $reqId); 
	if($kabupaten->update())
		echo "Data berhasil disimpan.";
		//echo $kabupaten->query;exit;
	
}

?>