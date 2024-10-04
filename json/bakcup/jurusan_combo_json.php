<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Jurusan.php");


/* create objects */
$jurusan = new Jurusan();

$reqId = httpFilterGet("reqId");

$jurusan->selectByParams(array());
//echo $jurusan->query;
$arr_json = array();
$i = 0;

while($jurusan->nextRow())
{
	$arr_json[$i]['id'] = $jurusan->getField("JURUSAN_ID");
	$arr_json[$i]['text'] = $jurusan->getField("NAMA");
	
	$i++;
}

echo json_encode($arr_json);
?>