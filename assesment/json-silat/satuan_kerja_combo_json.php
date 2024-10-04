<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Satker.php");


/* create objects */
$satker = new Satker();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$satker->selectByParams(array("SATKER_ID_PARENT" => 0));
$arr_json = array();
$i = 0;
while($satker->nextRow())
{
	$arr_json[$i]['id'] = $satker->getField("SATKER_ID");
	$arr_json[$i]['text'] = $satker->getField("NAMA");

	$j=0;
	$departemen = new Satker();
	$departemen->selectByParams(array("SATKER_ID_PARENT" => $satker->getField("SATKER_ID")));
	while($departemen->nextRow())
	{
		$arr_parent[$j]['id'] = $departemen->getField("SATKER_ID");
		$arr_parent[$j]['text'] = $departemen->getField("NAMA");
		$k = 0;
		$child = new Satker();
		$child->selectByParams(array("SATKER_ID_PARENT" => $departemen->getField("SATKER_ID")));
		while($child->nextRow())
		{
			$arr_child[$k]['id'] = $child->getField("SATKER_ID");
			$arr_child[$k]['text'] = $child->getField("NAMA");
			
			$l = 0;
			$sub = new Satker();
			$sub->selectByParams(array("SATKER_ID_PARENT" => $child->getField("SATKER_ID")));
			while($sub->nextRow())
			{
				$arr_sub[$l]['id'] = $sub->getField("SATKER_ID");
				$arr_sub[$l]['text'] = $sub->getField("NAMA");	
				$l++;
			}
			
			$arr_child[$k]['children'] = $arr_sub;
			unset($sub);
			unset($arr_sub);
			$k++;
		}
		$arr_parent[$j]['children'] = $arr_child;
		
		unset($child);
		unset($arr_child);
		
		$j++;
	}
	
	$arr_json[$i]['children'] = $arr_parent;
	unset($departemen);	
	unset($arr_parent);
	$i++;
}

echo json_encode($arr_json);
?>