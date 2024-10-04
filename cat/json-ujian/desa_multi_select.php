<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Desa.php");

/* create objects */

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqPropinsiId= httpFilterGet("reqPropinsiId");
$reqKabupatenId= httpFilterGet("reqKabupatenId");
$reqKecamatanId= httpFilterGet("reqKecamatanId");

$statement= " AND KECAMATAN_ID = ".$reqKecamatanId." AND KABUPATEN_ID = ".$reqKabupatenId." AND PROPINSI_ID = ".$reqPropinsiId;
$set= new Desa();
$set->selectByParams(array(), -1,-1, $statement);

$j=0;
while($set->nextRow())
{
	$arrID[$j]= $set->getField("DESA_ID");
	$arrNama[$j]= $set->getField("NAMA");
	$j++;
}

$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama);
echo json_encode($arrFinal);
?>