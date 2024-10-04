<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");

$set= new Pelamar();
$reqVal= httpFilterGet("reqVal");
//$reqVal=123;
$set->selectByParams(array(),-1,-1, " AND KTP_NO = '".$reqVal."'");
$set->firstRow();
$tempValue= $set->getField("KTP_NO");

//echo $set->query;exit;
$arrFinal = array("VALUE_VALIDASI" => $tempValue);

echo json_encode($arrFinal);
?>