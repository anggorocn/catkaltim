<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Atribut.php");
include_once("../WEB/functions/kode.func.php");

$set = new Atribut();

$reqAspekId= $_GET["reqAspekId"];
$reqTahun= $_GET["reqTahun"];
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;//
$id = isset($_POST['id']) ? $_POST['id'] : 0;//
$result = array();

if ($id == 0)
{
	//$statement= " AND A.TAHUN = '".$reqTahun."'";
	if($reqAspekId == ""){}
	else
	$statement.= " AND A.ASPEK_ID = ".$reqAspekId;
	
	// kondisi aktif permen
	$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM PERMEN WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

	$result["total"] = $set->getCountByParamsKompetensiAtributCombo(array("PARENT_ID" => 0), $statement);	
	$set->selectByParamsKompetensiAtributCombo(array("PARENT_ID" => 0), $rows, $offset, $statement);
	//echo $set->query;exit;
	//echo $set->errorMsg;exit;
	$i=0;
	while($set->nextRow())
	{
		$items[$i]['ID'] = $set->getField("ID");
		$items[$i]['NAMA'] = $set->getField("NAMA");
		$items[$i]['ASPEK_NAMA'] = $set->getField("ASPEK_NAMA");
		$items[$i]['LINK_URL'] = $set->getField("LINK_URL");
		$items[$i]['state'] = 'closed';
		$i++;
	}
	$result["rows"] = $items;
} 
else 
{
	//$statement= " AND A.TAHUN = '".$reqTahun."'";
	if($reqAspekId == ""){}
	else
	$statement.= " AND A.ASPEK_ID = ".$reqAspekId;
	
	// kondisi aktif permen
	$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM PERMEN WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";
	
	$set->selectByParamsKompetensiAtributCombo(array("PARENT_ID" => $id), -1, -1, $statement);
	//echo $set->query;exit;
	$i=0;
	while($set->nextRow())
	{
		$result[$i]['ID'] = $set->getField("ID");
		$result[$i]['NAMA'] = $set->getField("NAMA");
		$result[$i]['ASPEK_NAMA'] = $set->getField("ASPEK_NAMA");
		$result[$i]['LINK_URL'] = $set->getField("LINK_URL");
		$result[$i]['state'] = 'open';
		$i++;
	}
}
echo json_encode($result);
?>