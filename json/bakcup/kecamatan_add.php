<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/Kecamatan.php");
include_once("../WEB/classes/utils/UserLogin.php");

$kecamatan = new Kecamatan();

$reqId 		= httpFilterPost("reqId");
$reqMode 	= httpFilterPost("reqMode");
$reqPropinsiId 	= httpFilterPost("reqPropinsiId");
$reqKabupatenId 	= httpFilterPost("reqKabupatenId");
$reqNama	= httpFilterPost("reqNama");


$kecamatan->setField('PROPINSI_ID', $reqPropinsiId);
$kecamatan->setField('KABUPATEN_ID', $reqKabupatenId);
$kecamatan->setField('NAMA', $reqNama);

if($reqMode == "insert")
{
	if($kecamatan->insert())
		echo "Data berhasil disimpan.";
		echo $kecamatan->query;exit;
}
else
{
	$kecamatan->setField('KECAMATAN_ID', $reqId); 
	if($kecamatan->update())
		echo "Data berhasil disimpan.";
		//echo $kecamatan->query;exit;
	
}
//echo $kecamatan->query;exit;
?>