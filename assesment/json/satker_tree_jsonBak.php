<?php
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/functions/kode.func.php");

$set = new Kelautan();

$reqMode = $_GET["reqMode"];
$reqStatus= $_GET["reqStatus"];
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
$offset = ($page-1)*$rows;
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$result = array();

if ($id == 0)
{	
	$result["total"] = $set->getCountByParamsSatuanKerja(array("PARENT_TREE" => 0));
	$set->selectByParamsSatuanKerja(array("PARENT_TREE" => 0), $rows, $offset, $statement);
	//echo $set->query;exit;
	//echo $set->errorMsg;exit;
	$i=0;
	while($set->nextRow())
	{
		$items[$i]['ID'] = $set->getField("ID");
		$items[$i]['NAMA'] = $set->getField("NAMA");
		$items[$i]['NAMA_WARNA'] = $set->getField("NAMA_WARNA");	
		$items[$i]['ID_TABLE'] = $set->getField("ID_TABLE");
		$items[$i]['ID_TABLE_PARENT'] = $set->getField("ID_TABLE_PARENT");
		$items[$i]['state'] = has_child($set->getField("ID"), $statement) ? 'closed' : 'open';
		$i++;
	}
	$result["rows"] = $items;
} 
else 
{
	$set->selectByParamsSatuanKerja(array("PARENT_TREE" => $id), -1, -1, $statement);
	$i=0;
	while($set->nextRow())
	{
		$tempId= setKode($set->getField("ID"));
		
		$result[$i]['ID'] = $set->getField("ID");
		$result[$i]['NAMA'] = $set->getField("NAMA");
		//$result[$i]['NAMA_WARNA'] = $set->getField("NAMA_WARNA");
		$result[$i]['NAMA_WARNA'] = $set->getField("NAMA_WARNA");
		$result[$i]['ID_TABLE'] = $set->getField("ID_TABLE");
		$result[$i]['ID_TABLE_PARENT'] = $set->getField("ID_TABLE_PARENT");
		if($reqStatus == 1){}
		else
		{
			$result[$i]['state'] = has_child($set->getField("ID"), $statement) ? 'closed' : 'open';
		}
		$i++;
	}
}
echo json_encode($result);

function has_child($id, $stat)
{
	$child = new Kelautan();
	$jumlah = $child->getCountByParamsSatuanKerja(array("PARENT_TREE" => $id), $stat);
	return $jumlah > 0 ? true : false;
}
?>