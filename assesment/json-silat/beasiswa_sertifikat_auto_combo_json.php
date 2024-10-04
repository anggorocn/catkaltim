<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/BeasiswaSertifikat.php");


/* create objects */

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqMode= httpFilterGet("reqMode");
$set = new BeasiswaSertifikat();

// get the search term
$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";

$j=0;

$set->selectByParamsCombo(array(), 50, 0, " AND UPPER(".$reqMode.") LIKE '%".strtoupper($search_term)."%' ", trim($reqMode));
//$set->selectByParams(array(), 5, 0);
//echo $set->query;exit;
while($set->nextRow())
{
	$arr_parent[$j]['id'] = $set->getField(trim($reqMode));
	$arr_parent[$j]['label'] = $set->getField(trim($reqMode));
	$arr_parent[$j]['desc'] = $set->getField(trim($reqMode));
	$j++;
}
//echo json_encode($arr_parent, JSON_UNESCAPED_SLASHES);
echo json_encode($arr_parent);
?>