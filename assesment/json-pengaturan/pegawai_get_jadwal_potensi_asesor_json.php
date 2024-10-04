<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/JadwalAsesorPotensi.php");

$reqJadwalAcaraId= httpFilterGet("reqJadwalAcaraId");
$reqJadwalAsesorPotensiId= httpFilterGet("reqJadwalAsesorPotensiId");

$statement= " AND A.JADWAL_ACARA_ID = ".$reqJadwalAcaraId;
$statement.= " AND A.JADWAL_ASESOR_POTENSI_ID = ".$reqJadwalAsesorPotensiId;

$set= new JadwalAsesorPotensi();
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();

$reqPenggalianId= $set->getField("PENGGALIAN_ID");
$reqJadwalAsesorPotensiId= $set->getField("JADWAL_ASESOR_POTENSI_ID");
$reqAsesorPotensiId= $set->getField("ASESOR_ID");
$reqAsesorNama= $set->getField("ASESOR_NAMA");
unset($set);
$arrFinal = array(
"reqPenggalianId" => $reqPenggalianId, "reqJadwalAsesorPotensiId" => $reqJadwalAsesorPotensiId, "reqAsesorPotensiId" => $reqAsesorPotensiId, "reqAsesorNama" => $reqAsesorNama
);
	
echo json_encode($arrFinal);
?>
