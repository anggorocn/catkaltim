<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelatihanHcdp.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$statement= "
AND EXISTS
(
	SELECT 1
	FROM
	(
		SELECT
		PERMEN_ID
		FROM PERMEN
		WHERE STATUS = '1'
	) XXX WHERE A.PERMEN_ID = XXX.PERMEN_ID
)
";
$set= new PelatihanHcdp();
$set->selectByParams(array("PELATIHAN_HCDP_ID_PARENT"=>0), -1, -1, $statement, "ORDER BY A.PELATIHAN_HCDP_ID");
// echo $set->query;exit;
$i=0;
$items=[];
while($set->nextRow())
{
    $infoid= $set->getField("PELATIHAN_HCDP_ID");
    $items[$i]['id']= $infoid;
    $items[$i]['text']= $set->getField("NAMA");
    $items[$i]['children']= combotreegetchild($infoid);
    $i++;
}

function combotreegetchild($id)
{
	$statementdetil= " 
	AND EXISTS
	(
		SELECT 1
		FROM
		(
			SELECT
			PERMEN_ID
			FROM PERMEN
			WHERE STATUS = '1'
		) XXX WHERE A.PERMEN_ID = XXX.PERMEN_ID
	)";

	$statement= $statementdetil;
	$set = new PelatihanHcdp();
	$set->selectByParams(array("PELATIHAN_HCDP_ID_PARENT"=>$id), -1, -1, $statement);
	// echo $set->query;exit;
	
	$arr_json = array();
	$j=0;
	while($set->nextRow())
	{
		$arr_json[$j]['id'] = $set->getField("PELATIHAN_HCDP_ID");
		$arr_json[$j]['text'] = $set->getField("NAMA");
		
		$statement= $statementdetil;
		$set_detil= new PelatihanHcdp();
		$record= $set_detil->getCountByParams(array("PELATIHAN_HCDP_ID_PARENT"=>$set->getField("PELATIHAN_HCDP_ID")), $statement);
		// echo $set_detil->query;exit;
		unset($set_detil);
		
		if($record > 0)
			$arr_json[$j]['children'] = combotreegetchild($set->getField("PELATIHAN_HCDP_ID"));
			
		$j++;
	}
	return $arr_json;
}
// print_r($items);exit;
$items= json_encode($items);
// echo $items;exit;
return $items;
?>