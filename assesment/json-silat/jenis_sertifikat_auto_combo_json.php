<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* create objects */

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqId= httpFilterGet("reqId");

// get the search term
$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";

$j=0;

$data=array(
  array('text' =>'TOEFL','id' =>'1'),
  array('text' =>'IELTS','id' =>'2')
);
 
$findme=$search_term;
 
foreach ($data as $k=>$v)
{
 if(stripos($v['text'], $findme) !== false)
 {
	$arr_parent[$j]['id'] = $v["id"];
	$arr_parent[$j]['label'] = $v["text"];
	$arr_parent[$j]['desc'] = $v["text"];
	$j++;
 }
}

if($j == 0)
{
	foreach ($data as $k=>$v)
	{
		$arr_parent[$j]['id'] = $v["id"];
		$arr_parent[$j]['label'] = $v["text"];
		$arr_parent[$j]['desc'] = $v["text"];
		$j++;
	}
}

//echo json_encode($arr_parent, JSON_UNESCAPED_SLASHES);
echo json_encode($arr_parent);
?>