<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");

/* create objects */

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$set = new Pelamar();

// get the search term
$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";

$j=0;

$set->selectByParams(array(), 10, 0, " AND UPPER(KTP_NO) LIKE '%".strtoupper($search_term)."%' ");
//$set->selectByParams(array(), 5, 0);
//echo $set->query;
while($set->nextRow())
{
	$arr_parent[$j]['id'] = $set->getField("KTP_NO").'#'.$set->getField("NAMA").'#'.$set->getField("TEMPAT_LAHIR").'#'.$set->getField("TANGGAL_LAHIR");
	$arr_parent[$j]['label'] = $set->getField("KTP_NO");
	
	$j++;
}

if($j == 0)
{
	$arr_parent[$j]['id'] = "";
	$arr_parent[$j]['label'] = "";
}

//echo json_encode($arr_parent, JSON_UNESCAPED_SLASHES);
echo json_encode($arr_parent);
?>