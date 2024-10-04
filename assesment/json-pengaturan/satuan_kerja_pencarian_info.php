<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Satker.php");

/* variable */
$reqSatkerId= httpFilterGet("reqSatkerId");

$statement .= " AND KODE_UNKER = '".$reqSatkerId."'";

$set= new Satker();
$set->selectByParams(array(), -1, -1,$statement);
$set->firstRow();
$reqSatkerId= $set->getField("KODE_UNKER");
$reqSatker= $set->getField("NAMA_UNKER");

$arrFinal = array(
"reqSatkerId"=>$reqSatkerId, "reqSatker"=>$reqSatker
);
	
echo json_encode($arrFinal);
?>
