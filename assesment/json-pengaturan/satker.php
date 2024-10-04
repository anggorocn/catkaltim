<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Satker.php");
include_once("../WEB/functions/kode.func.php");

$set = new Satker();

$reqMode = $_GET["reqMode"];
$reqStatus= $_GET["reqStatus"];
$reqTipe= $_GET["reqTipe"];

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
$offset = ($page-1)*$rows;
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$result = array();

if ($reqTipe=="internal")
{


	if ($id == 0)
	{
		if($userSatkerIkk == "")
		{
			$result["total"] = $set->getCountByParamsSatuanKerja(array("SATKER_ID_PARENT" => 0), $statement);	
			$set->selectByParamsSatuanKerja(array("SATKER_ID_PARENT" => 0), $rows, $offset, $statement);
		//echo $satker->query;exit;
		}
		else
		{
			$result["total"] = $set->getCountByParamsSatuanKerja(array("A.ID" => $userSatkerIkk), $statement);	
			$set->selectByParamsSatuanKerja(array("A.ID" => $userSatkerIkk), $rows, $offset, $statement);
		}
		// echo $set->query;exit;
		//echo $set->errorMsg;exit;
		$i=0;
		// $items[$i]['ID'] = "0";
		// $items[$i]['NAMA'] = "PROVINSI BALI";
		// $items[$i]['NAMA_WARNA'] = "PROVINSI BALI";
		// $items[$i]['ID_TABLE'] = "0";
		// $items[$i]['ID_TABLE_PARENT'] = "";
		// $items[$i]['state'] = has_child("", $statement) ? 'closed' : 'open';
		// $i++;
		
		while($set->nextRow())
		{
			$items[$i]['ID'] = $set->getField("ID");
			$items[$i]['NAMA'] = $set->getField("NAMA");
			$items[$i]['NAMA_WARNA'] = $set->getField("NAMA_WARNA");	
			$items[$i]['ID_TABLE'] = $set->getField("ID_TABLE");
			$items[$i]['ID_TABLE_PARENT'] = $set->getField("ID_TABLE_PARENT");
			$items[$i]['LINK_URL'] = $set->getField("LINK_URL");
			$items[$i]['state'] = has_child($set->getField("ID"), $statement,$reqTipe) ? 'closed' : 'open';
			$i++;
		}
		$result["rows"] = $items;
	} 
	else 
	{
		$set->selectByParamsSatuanKerja(array("SATKER_ID_PARENT" => $id), -1, -1, $statement);
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
			$result[$i]['LINK_URL'] = $set->getField("LINK_URL");
			if($reqStatus == 1){}
				else
				{
					$result[$i]['state'] = has_child($set->getField("ID"), $statement,$reqTipe) ? 'closed' : 'open';
				}
				$i++;
			}
		}

		

}
else
{

	if ($id == 0)
	{
		if($userSatkerIkk == "")
		{
			$result["total"] = $set->getCountByParamsSatuanKerjaEksternal(array("SATKER_EKSTERNAL_ID_PARENT" => 0), $statement);	
			$set->selectByParamsSatuanKerjaEkternal(array("SATKER_EKSTERNAL_ID_PARENT" => 0), $rows, $offset, $statement);
		//echo $satker->query;exit;
		}
		else
		{
			$result["total"] = $set->getCountByParamsSatuanKerjaEksternal(array("A.ID" => $userSatkerIkk), $statement);	
			$set->selectByParamsSatuanKerjaEkternal(array("A.ID" => $userSatkerIkk), $rows, $offset, $statement);
		}
		// echo $set->query;exit;
		//echo $set->errorMsg;exit;
		$i=0;
		// $items[$i]['ID'] = "0";
		// $items[$i]['NAMA'] = "PROVINSI BALI";
		// $items[$i]['NAMA_WARNA'] = "PROVINSI BALI";
		// $items[$i]['ID_TABLE'] = "0";
		// $items[$i]['ID_TABLE_PARENT'] = "";
		// $items[$i]['state'] = has_child("", $statement) ? 'closed' : 'open';
		// $i++;
		
		while($set->nextRow())
		{
			$items[$i]['ID'] = $set->getField("ID");
			$items[$i]['NAMA'] = $set->getField("NAMA");
			$items[$i]['NAMA_WARNA'] = $set->getField("NAMA_WARNA");	
			$items[$i]['ID_TABLE'] = $set->getField("ID_TABLE");
			$items[$i]['ID_TABLE_PARENT'] = $set->getField("ID_TABLE_PARENT");
			$items[$i]['LINK_URL'] = $set->getField("LINK_URL");
			$items[$i]['state'] = has_child($set->getField("ID"), $statement,$reqTipe) ? 'closed' : 'open';
			$i++;
		}
		$result["rows"] = $items;
	} 
	else 
	{
		$set->selectByParamsSatuanKerjaEkternal(array("SATKER_EKSTERNAL_ID_PARENT" => $id), -1, -1, $statement);
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
			$result[$i]['LINK_URL'] = $set->getField("LINK_URL");
			if($reqStatus == 1){}
				else
				{
					$result[$i]['state'] = has_child($set->getField("ID"), $statement,$reqTipe) ? 'closed' : 'open';
				}
				$i++;
			}
		}

	

}
function has_child($id, $statement,$reqTipe)
{

	$child = new Satker();
	if ($reqTipe=="internal")
	{
		$child->selectByParamsSatuanKerja(array("SATKER_ID_PARENT" => $id), -1, -1, $statement);
	}
	else
	{
		$child->selectByParamsSatuanKerjaEkternal(array("SATKER_EKSTERNAL_ID_PARENT" => $id), -1, -1, $statement);
	}
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