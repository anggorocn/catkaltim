<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Beasiswa.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterGet("reqMode");
$reqTipe= httpFilterGet("reqTipe");

$arr_json = array();
$i = 0;
$set_combo = new Beasiswa();
$set_combo->selectByParamsCombo(array(),-1,-1, $statement, trim($reqMode));

while($set_combo->nextRow()){
	$arr_json[$i]['id'] = $set_combo->getField(trim($reqMode));
	$arr_json[$i]['text'] = $set_combo->getField(trim($reqMode));
	$i++;
}

echo json_encode($arr_json);
?>