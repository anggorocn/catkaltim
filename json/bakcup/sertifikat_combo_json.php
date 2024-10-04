<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Sertifikat.php");


/* create objects */
$sertifikat = new Sertifikat();

$reqId = httpFilterGet("reqId");

$sertifikat->selectByParams(array());
//echo $sertifikat->query;
$arr_json = array();
$i = 0;

while($sertifikat->nextRow())
{
	$arr_json[$i]['id'] = $sertifikat->getField("SERTIFIKAT_ID");
	$arr_json[$i]['text'] = $sertifikat->getField("NAMA");
	
	$i++;
}

echo json_encode($arr_json);
?>