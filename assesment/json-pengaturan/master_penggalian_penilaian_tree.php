<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/SatkerEselonAtribut.php");
include_once("../WEB/classes/base/SatkerEselonAtribut.php");
include_once("../WEB/functions/kode.func.php");

$set = new SatkerEselonAtribut();

$reqId= $_GET["reqId"];
$reqAspekId= $_GET["reqAspekId"];
$reqTahun= $_GET["reqTahun"];
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;//
$id = isset($_POST['id']) ? $_POST['id'] : 0;//
$result = array();

if ($id == 0)
{
	$result["total"] = 0;//$set->getCountByParamsSatuanKerja(array("PARENT_ID" => 0), $statement);	
	$set->selectByParamsPenggalianPenilaianJabatanAtributCombo(array("PARENT_ID" => 0), $rows, $offset, $reqTahun, $reqAspekId, $statement);
	//echo $set->query;exit;
	//echo $set->errorMsg;exit;
	$i=0;
	while($set->nextRow())
	{
		$items[$i]['ID'] = $set->getField("ID");
		$items[$i]['NAMA'] = $set->getField("NAMA");
		$result[$i]['LINK_URL'] = $set->getField("LINK_URL");
		//$items[$i]['state'] = has_child($set->getField("ID"), $statement) ? 'closed' : 'open';
		$items[$i]['state'] = 'closed';
		$i++;
	}
	$result["rows"] = $items;
} 
else 
{
	//$statement= "AND PARENT_ID LIKE '".$id."-%'";
	$statement= "AND PARENT_ID = '".$id."'";
	$set->selectByParamsPenggalianPenilaianJabatanAtributCombo(array(), -1, -1, $reqTahun, $reqAspekId, $statement);
	//$set->selectByParamsPenggalianPenilaianJabatanAtributCombo(array("PARENT_ID" => $id), -1, -1, $reqTahun, $reqAspekId, $statement);
	//echo $set->query;exit;
	$i=0;
	while($set->nextRow())
	{
		$result[$i]['ID'] = $set->getField("ID");
		$result[$i]['NAMA'] = $set->getField("NAMA");
		$result[$i]['LINK_URL'] = $set->getField("LINK_URL");
		
		if($set->getField("ID_ROW") == 0 || $set->getField("ID_ROW") == 999)
		$result[$i]['state'] = 'closed';
		else
		{
			//$statement= "AND PARENT_ID LIKE '".$result[$i]['ID']."-%'";
			$statement= "AND PARENT_ID = '".$result[$i]['ID']."'";
			$set_detil= new SatkerEselonAtribut();
			//$set_detil->selectByParamsPenggalianPenilaianJabatanAtributCombo(array("PARENT_ID" => $result[$i]['ID']), -1, -1, $reqTahun, $reqAspekId, $statement);
			$set_detil->selectByParamsPenggalianPenilaianJabatanAtributCombo(array(), -1, -1, $reqTahun, $reqAspekId, $statement);
			$set_detil->firstRow();
			$tempRowDetil= $set_detil->getField("ID");
			unset($set_detil);
			
			if($tempRowDetil == "")
				$result[$i]['state'] = 'open';
			else
				$result[$i]['state'] = 'closed';
		}
		$i++;
	}
}
echo json_encode($result);
?>