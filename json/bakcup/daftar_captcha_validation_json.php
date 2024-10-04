<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

$reqVal= httpFilterGet("reqVal");

$tempValue="";
if($_SESSION['security_code'] == md5($reqVal))
	$tempValue= 1;
//echo md5($_SESSION['security_code'])." == ".md5($reqVal);exit;	
//echo $set->query;exit;
$arrFinal = array("VALUE_VALIDASI" => $tempValue);

echo json_encode($arrFinal);
?>