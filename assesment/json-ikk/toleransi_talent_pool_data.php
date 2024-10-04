<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/ToleransiTalentPool.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTahun= httpFilterGet("reqTahun");

$statement= " AND A.TAHUN = ".$reqTahun;
$set= new ToleransiTalentPool();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
$tempToleransiY= $set->getField("TOLERANSI_Y");
$tempToleransiX= $set->getField("TOLERANSI_X");

$arrFinal = array(
"tempToleransiY" => $tempToleransiY, "tempToleransiX" => $tempToleransiX
);

echo json_encode($arrFinal);
?>