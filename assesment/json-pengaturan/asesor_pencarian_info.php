<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Asesor.php");

/* variable */
$reqId= httpFilterGet("reqId");

$statement .= " AND ASESOR_ID = ".$reqId;

$set= new Asesor();
$set->selectByParams(array(), -1, -1,$statement);
$set->firstRow();
$reqPegawaiId= $set->getField("ASESOR_ID");
$reqPegawai= $set->getField("NAMA");
$reqKodeUnker= $set->getField("KODE_UNKER");
$reqKodeNamaUnker= $set->getField("SATKER");

$arrFinal = array(
"reqPegawaiId"=>$reqPegawaiId, "reqPegawai"=>$reqPegawai, "reqKodeUnker" =>$reqKodeUnker, "reqKodeNamaUnker" =>$reqKodeNamaUnker
);
	
echo json_encode($arrFinal);
?>
