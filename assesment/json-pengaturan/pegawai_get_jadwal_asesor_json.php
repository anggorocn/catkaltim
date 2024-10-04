<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/JadwalAsesor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqJadwalAcaraId= httpFilterGet("reqJadwalAcaraId");
$reqJadwalAsesorId= httpFilterGet("reqJadwalAsesorId");

$statement= " AND A.JADWAL_ACARA_ID = ".$reqJadwalAcaraId;
$statement.= " AND A.JADWAL_ASESOR_ID = ".$reqJadwalAsesorId;

$set= new JadwalAsesor();
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();

$reqPenggalianId= $set->getField("PENGGALIAN_ID");
$reqJadwalAsesorId= $set->getField("JADWAL_ASESOR_ID");
$reqAsesorNama= $set->getField("NAMA");
unset($set);
$arrFinal = array(
"reqPenggalianId" => $reqPenggalianId, "reqJadwalAsesorId" => $reqJadwalAsesorId, "reqAsesorNama" => $reqAsesorNama
);
	
echo json_encode($arrFinal);
?>
