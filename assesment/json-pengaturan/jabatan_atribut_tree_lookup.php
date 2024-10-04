<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/SatkerEselonAtribut.php");
include_once("../WEB/functions/kode.func.php");

$set = new SatkerEselonAtribut();

$reqAspekId= $_GET["reqAspekId"];
$reqTahun= $_GET["reqTahun"];
$reqEselonId= $_GET["reqEselonId"];
$reqSatuanKerjaId= $_GET["reqSatuanKerjaId"];
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;//
$id = isset($_POST['id']) ? $_POST['id'] : 0;//
$result = array();

if ($id == 0)
{
	$statement= " AND A.ASPEK_ID = ".$reqAspekId." AND A.TAHUN = '".$reqTahun."'";
	$result["total"] = 0;//$set->getCountByParamsSatuanKerja(array("ATRIBUT_ID_PARENT" => 0), $statement);	
	$set->selectByParamsJabatanAtributLookup(array("ATRIBUT_ID_PARENT" => 0), $rows, $offset, $reqEselonId, $reqSatuanKerjaId, $statement);
	//echo $set->query;exit;
	//echo $set->errorMsg;exit;
	$i=0;
	while($set->nextRow())
	{
		$items[$i]['ID'] = $set->getField("ATRIBUT_ID");
		$items[$i]['NAMA'] = $set->getField("NAMA");
		$result[$i]['LINK_URL'] = $set->getField("LINK_URL");
		//echo has_child($set->getField("ATRIBUT_ID"), $statement);
		$items[$i]['state'] = has_child($set->getField("ATRIBUT_ID"), $reqEselonId, $reqSatuanKerjaId, $statement) ? 'closed' : 'open';
		$i++;
	}
	$result["rows"] = $items;
} 
else 
{
	$statement= " AND A.ASPEK_ID = ".$reqAspekId." AND A.TAHUN = '".$reqTahun."'";
	$set->selectByParamsJabatanAtributLookup(array("ATRIBUT_ID_PARENT" => $id), -1, -1, $reqEselonId, $reqSatuanKerjaId, $statement);
	//echo $set->query;exit;
	$i=0;
	while($set->nextRow())
	{
		$result[$i]['ID'] = $set->getField("ATRIBUT_ID");
		$result[$i]['NAMA'] = $set->getField("NAMA");
		$result[$i]['LINK_URL'] = $set->getField("LINK_URL");
		$items[$i]['state'] = has_child($set->getField("ATRIBUT_ID"), $reqEselonId, $reqSatuanKerjaId, $statement) ? 'closed' : 'open';
		$i++;
	}
}

function has_child($id, $reqEselonId, $reqSatuanKerjaId, $stat)
{
	$child = new SatkerEselonAtribut();
	$child->selectByParamsJabatanAtributLookup(array("ATRIBUT_ID_PARENT" => $id), -1,-1, $reqEselonId, $reqSatuanKerjaId);
	$child->firstRow();
	$tempId= $child->getField("ATRIBUT_ID");
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