<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/UsersBase.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

$set = new UsersBase();

$reqUserLogin= httpFilterGet("reqUserLogin");
$reqUserLoginTemp= httpFilterGet("reqUserLoginTemp");

if($reqUserLoginTemp == "")
{
	$set->selectByParamsMonitoring(array('A.USER_LOGIN'=>$reqUserLogin));
}
else
{
	$set->selectByParamsMonitoring(array('A.USER_LOGIN'=>$reqUserLogin, "NOT A.USER_LOGIN" => $reqUserLoginTemp));
}

$set->firstRow();
//echo $set->query;exit;
$arrFinal = array("USER_LOGIN" => $set->getField("USER_LOGIN"));

echo json_encode($arrFinal);exit;
?>