<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JenisHukuman.php");


/* create objects */
$set = new JenisHukuman();
$reqMode= httpFilterGet("reqMode");
$reqId= httpFilterGet("reqId");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set->selectByParamsEdit(array("TINGKAT_HUKUMAN_ID"=>$reqId),-1,-1, '');
//echo $set->query;
$arr_json = array();

$i = 0;

while($set->nextRow())
{
	$arr_json[$i]['id'] = $set->getField("JENIS_HUKUMAN_ID");
	$arr_json[$i]['text'] = $set->getField("NAMA");
	$i++;
}

if($i == 0)
{
	$arr_json[0]['id'] = "";
	$arr_json[0]['text'] = "";
}
echo json_encode($arr_json);
?>