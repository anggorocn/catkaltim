<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/LevelAtribut.php");

ini_set("memory_limit", "-1");
set_time_limit(0);

$reqAtributId= httpFilterGet("reqAtributId");
$reqRowId= httpFilterGet("reqRowId");

$level=1;
for($i=0; $i < 10; $i++)
{
	$arrID[$i]= $level;
	$arrNama[$i]= $level;
	$level++;
}

$statement= " AND A.ATRIBUT_ID = '".$reqAtributId."'";
if($reqRowId == ""){}
else
$statement.= " AND A.LEVEL NOT IN (".$reqRowId.")";
// kondisi aktif permen
$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM PERMEN WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

$set= new LevelAtribut();
$set->selectByParams(array(), -1,-1, $statement);
while($set->nextRow())
{
	$tempLevel= $set->getField("LEVEL");
	array_splice($arrID, array_search($tempLevel, $arrID), 1);
	array_splice($arrNama, array_search($tempLevel, $arrNama), 1);
}

$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama);
echo json_encode($arrFinal);
?>