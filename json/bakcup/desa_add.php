<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/Desa.php");
include_once("../WEB/classes/utils/UserLogin.php");

$desa = new Desa();

$reqId 		= httpFilterPost("reqId");
$reqMode 	= httpFilterPost("reqMode");
$reqPropinsiId 	= httpFilterPost("reqPropinsiId");
$reqKabupatenId 	= httpFilterPost("reqKabupatenId");
$reqKecamatanId= httpFilterPost("reqKecamatanId");
$reqNama	= httpFilterPost("reqNama");


$desa->setField('PROPINSI_ID', $reqPropinsiId);
$desa->setField('KABUPATEN_ID', $reqKabupatenId);
$desa->setField('KECAMATAN_ID', $reqKecamatanId);
$desa->setField('NAMA', $reqNama);

if($reqMode == "insert")
{
	if($desa->insert())
		echo "Data berhasil disimpan.";
		echo $desa->query;exit;
}
else
{
	$desa->setField('KECAMATAN_ID', $reqId); 
	if($desa->update())
		echo "Data berhasil disimpan.";
		//echo $desa->query;exit;
	
}
//echo $desa->query;exit;
?>