<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

$string = file_get_contents("http://kinerjaku.kkp.go.id/2015/kinerjakuskp.php?bulan=9&tahun=2015&unitid=85&userid=skp&token=0cc175b9c0f1b6a831c399e269772661");
	
//$string = file_get_contents("tes.json");
$data_json=json_decode($string,true);
//echo $data_json;
$i=0;
foreach ($data_json as $key => $value) 
{
	print_r($value);
}
?>