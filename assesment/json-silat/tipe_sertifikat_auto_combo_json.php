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
$reqTipe= httpFilterGet("reqTipe");

// get the search term
$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";

$j=0;

if($reqTipe == 1)//TOEFL
{
	$data=array(
	  array('text' =>'Paper Based Test TOEFL','id' =>'1'),
	  array('text' =>'Computer Based Test TOEFL','id' =>'2'),
	  array('text' =>'Internet Based Test TOEFL','id' =>'3')
	  //array('text' =>'IELTS','id' =>'2')
	);
}
elseif($reqTipe == 2)//IELTS
{
	$data=array(
	  array('text' =>'No original English used','id' =>'5'),
	  array('text' =>'Non user','id' =>'6'),
	  array('text' =>'Intermittent user','id' =>'7'),
	  array('text' =>'Extremely limited user','id' =>'8'),
	  array('text' =>'Limited user','id' =>'9'),
	  array('text' =>'Modest user','id' =>'10'),
	  array('text' =>'Competen user','id' =>'11'),
	  array('text' =>'Good user','id' =>'12'),
	  array('text' =>'Very good user','id' =>'13'),
	  array('text' =>'Expert user','id' =>'14')
	);
}
$findme=$search_term;

if($reqTipe == ""){}
else
{
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
}

//echo json_encode($arr_parent, JSON_UNESCAPED_SLASHES);
echo json_encode($arr_parent);
?>