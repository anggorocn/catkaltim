<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* variable */
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$statement .= " AND IDPEG = '".$reqPegawaiId."'";

$set= new Kelautan();
$set->selectByParamsMonitoringPegawai(array(), -1, -1,$statement);
$set->firstRow();
$reqPegawaiId= $set->getField("IDPEG");
$reqPegawai= $set->getField("NAMA");
$reqKodeUnker= $set->getField("KODE_UNKER");
$reqKodeNamaUnker= $set->getField("SATKER");

$arrFinal = array(
"reqPegawaiId"=>$reqPegawaiId, "reqPegawai"=>$reqPegawai, "reqKodeUnker" =>$reqKodeUnker, "reqKodeNamaUnker" =>$reqKodeNamaUnker
);
	
echo json_encode($arrFinal);
?>
