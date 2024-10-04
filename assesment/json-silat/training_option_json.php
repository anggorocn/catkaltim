<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Training.php");

$set= new Training();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTahun= httpFilterGet("reqTahun");
if($reqTahun == ""){}
else
{
	$statement_cabang= " AND TAHUN = ".$reqTahun;
	$statement_cabang_alias= " AND A.TAHUN = '".$reqTahun."'";
}
$set->selectByParams(array(), -1,-1, $statement_cabang_alias);

ini_set("memory_limit", "-1");
set_time_limit(0);

$i=0;
$arrID[$i] = "";
$arrNama[$i] = "";
$i++;

while($set->nextRow())
{
	$arrID[$i] = $set->getField("TRAINING_ID");
	$arrNama[$i] = $set->getField("NAMA");
	$i += 1;
}
$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama);
echo json_encode($arrFinal);
?>