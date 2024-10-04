<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");

/* variable */
$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");
$reqAsesorId= httpFilterGet("reqAsesorId");

include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/Asesor.php");

$statement= " AND A.JADWAL_ACARA_ID = ".$reqRowId;
$set= new JadwalAsesor();
$set->selectByParamsAcaraJam(array(), -1,-1, $statement);
$set->firstRow();
$tempJam= $set->getField("JAM");
unset($set);

$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.ASESOR_ID = ".$reqAsesorId;
$set= new JadwalAsesor();
$set->selectByParamsAcaraJamAsesor(array(), -1,-1, $tempJam, $statement);
$set->firstRow();
$tempJamAsesor= $set->getField("JAM_ASESOR");
//echo $set->query;exit;

if($tempJamAsesor == "")
$tempJamAsesor= $tempJam;

//echo $tempJamAsesor;exit;
$statusJamAsesor= "";
if(getTimeJam($tempJamAsesor) >= 5)
$statusJamAsesor= "1";

$set= new Asesor();
$set->selectByParams(array("A.ASESOR_ID" => $reqAsesorId), -1, -1);
$set->firstRow();
$tempId= $set->getField("ASESOR_ID");
$tempNama= $set->getField("NAMA");
$tempNoSk= $set->getField("NO_SK");
$tempTipeNama= $set->getField("TIPE_NAMA");
unset($set);
$arrFinal = array(
"tempId" => $tempId, "tempNama" => $tempNama, "tempNoSk" => $tempNoSk, "tempTipeNama" => $tempTipeNama, "tempJamAsesor" => getTimeIndo($tempJamAsesor), "statusJamAsesor" => $statusJamAsesor
);
	
echo json_encode($arrFinal);
?>
