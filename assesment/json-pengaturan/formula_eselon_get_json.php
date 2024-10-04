<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaEselon.php");

/* variable */
$reqId= httpFilterGet("reqId");
$reqFormulaEselonId= httpFilterGet("reqFormulaEselonId");

$set= new FormulaEselon();
$set->selectByParamsLookupMonitoring(array("A.FORMULA_ESELON_ID" => $reqFormulaEselonId), -1, -1);
$set->firstRow();
$tempId= $set->getField("FORMULA_ESELON_ID");
$tempNama= $set->getField("NAMA_FORMULA_ESELON");
unset($set);
$arrFinal = array(
"tempId" => $tempId, "tempNama" => $tempNama
);
	
echo json_encode($arrFinal);
?>
