<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/RumpunJabatan.php");
include_once("../WEB/functions/kode.func.php");

$set = new RumpunJabatan();

$reqAspekId= $_GET["reqAspekId"];
$reqTahun= $_GET["reqTahun"];
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;//
$id = isset($_POST['id']) ? $_POST['id'] : 0;//
$result = array();

if ($id == 0)
{
	
	$result["total"] = $set->getCountByParamsRumpunJabatanCombo(array("PARENT_ID" => 0), $statement);	
	$set->selectByParamsRumpunJabatanCombo(array("PARENT_ID" => 0), $rows, $offset, $statement);
	// echo $set->query;exit;
	//echo $set->errorMsg;exit;
	$i=0;
	while($set->nextRow())
	{
		$items[$i]['ID'] = $set->getField("ID");
		$items[$i]['NAMA_RUMPUN'] = $set->getField("NAMA_RUMPUN");
		$items[$i]['LINK_URL'] = $set->getField("LINK_URL");
		$items[$i]['state'] = has_child($set->getField("ID"), $statement) ? 'closed' : 'open';
		//$items[$i]['state'] = 'closed';
		$i++;
	}
	$result["rows"] = $items;
} 
else 
{	
	$set->selectByParamsRumpunJabatanCombo(array("PARENT_ID" => $id), -1, -1, $statement);
	//echo $set->query;exit;
	$i=0;
	while($set->nextRow())
	{
		$result[$i]['ID'] = $set->getField("ID");
		$result[$i]['NAMA_RUMPUN'] = $set->getField("NAMA_RUMPUN");
		$result[$i]['LINK_URL'] = $set->getField("LINK_URL");
		$result[$i]['state'] = has_child($set->getField("ID"), $statement) ? 'closed' : 'open';
		//$result[$i]['state'] = 'open';
		$i++;
	}
}

function has_child($id, $statement)
{
	$child = new RumpunJabatan();
	$child->selectByParamsRumpunJabatanCombo(array("PARENT_ID" => $id), -1, -1, $statement);
	$child->firstRow();
	$tempId= $child->getField("ID");
	//echo $child->query;exit;
	//echo $child->errorMsg;exit;
	//echo $tempId;exit;
	if($tempId == "")
	return false;
	else
	return true;
	unset($child);
}

echo json_encode($result);
?>