<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Propinsi.php");

$set= new Propinsi();

/* LOGIN CHECK */
// if ($userLogin->checkUserLogin()) 
// { 
// 	$userLogin->retrieveUserInfo();
// }

$reqParentId= httpFilterGet("reqParentId");

$statement= " AND PROPINSI_PARENT_ID = '".$reqParentId."'";

$set->selectByParams(array(), 10, 0, $statement);
// echo $set->query;exit;

ini_set("memory_limit", "-1");
set_time_limit(0);

$i=0;
$arrID[$i] = "";
$arrNama[$i] = "";
$i++;

while($set->nextRow())
{
	$arrID[$i] = $set->getField("PROPINSI_ID");
	$arrNama[$i] = $set->getField("NAMA");
	$i += 1;
}//, "arrHarga" => $arrHarga
$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama);
echo json_encode($arrFinal);
?>
