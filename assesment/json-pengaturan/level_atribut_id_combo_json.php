<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/LevelAtribut.php");

ini_set("memory_limit", "-1");
set_time_limit(0);

$reqAtributId= httpFilterGet("reqAtributId");
$reqFormulaAtributId= httpFilterGet("reqFormulaAtributId");
$reqAtributParentLevelId= httpFilterGet("reqAtributParentLevelId");

$statement= " AND A.ATRIBUT_ID = '".$reqAtributId."'";
// kondisi aktif permen
$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM PERMEN WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

$i=0;
$set= new LevelAtribut();
$set->selectByParams(array(), -1,-1, $statement, "ORDER BY A.LEVEL ASC");
// echo $set->query;exit;

while($set->nextRow())
{
	$arrID[$i]= $set->getField("LEVEL_ID");
	$arrNama[$i]= $set->getField("LEVEL");
	$arrFormulaAtributId[$i]= $reqFormulaAtributId;
	$arrAtributParentLevelId[$i]= $reqAtributParentLevelId;
	$i++;
}

$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama, "arrFormulaAtributId" => $arrFormulaAtributId, "arrAtributParentLevelId" => $arrAtributParentLevelId);
echo json_encode($arrFinal);
?>