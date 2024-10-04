<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* create objects */

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId = httpFilterGet("reqId");

$arrData= setFipInfo();

for($j=0; $j<count($arrData); $j++)
{
	$arrID[$j]= $arrData[$j];
	$arrNama[$j]= $arrData[$j];
}

$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama);
echo json_encode($arrFinal);
?>