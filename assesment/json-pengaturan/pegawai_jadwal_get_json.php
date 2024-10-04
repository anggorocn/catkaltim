<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalPegawai.php");

/* variable */
$reqId= httpFilterGet("reqId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
// $reqPegawaiId=846;

$statement= " AND A.PEGAWAI_ID = '".$reqPegawaiId."'";
$statement.= " AND EXISTS (SELECT 1 FROM jadwal_tes_simulasi_pegawai X WHERE 1=1 AND X.JADWAL_TES_ID = ".$reqId." AND X.PEGAWAI_ID = A.PEGAWAI_ID)";

$set= new JadwalPegawai();
$set->selectByParamsLookupJadwalPegawai(array(), -1, -1, $statement, $reqId);
$set->firstRow();
// echo $set->query;exit;
$tempNomorUrut= $set->getField("NOMOR_URUT_GENERATE");
unset($set);


$index_loop= 0;
$arrData="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set_detil= new JadwalTes();
$set_detil->selectByParamsAbsenJadwal(array(), -1,-1, $reqPegawaiId, $statement);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrData[$index_loop]["JADWAL_TES_ID"]= $set_detil->getField("JADWAL_TES_ID");
	$arrData[$index_loop]["JADWAL_ACARA_ID"]= $set_detil->getField("JADWAL_ACARA_ID");
	$arrData[$index_loop]["PUKUL1"]= $set_detil->getField("PUKUL1");
	$arrData[$index_loop]["PUKUL2"]= $set_detil->getField("PUKUL2");
	$arrData[$index_loop]["PENGGALIAN_ID"]= $set_detil->getField("PENGGALIAN_ID");
	$arrData[$index_loop]["KODE"]= $set_detil->getField("KODE");
	$arrData[$index_loop]["PENGGALIAN_NAMA"]= $set_detil->getField("PENGGALIAN_NAMA");
	$arrData[$index_loop]["ASESOR_NAMA"]= $set_detil->getField("ASESOR_NAMA");
	$arrData[$index_loop]["JADWAL_PEGAWAI_ID"]= $set_detil->getField("JADWAL_PEGAWAI_ID");
	$arrData[$index_loop]["JADWAL_ASESOR_ID"]= $set_detil->getField("JADWAL_ASESOR_ID");
	$arrData[$index_loop]["JADWAL_ASESOR_POTENSI_PEGAWAI_ID"]= $set_detil->getField("JADWAL_ASESOR_POTENSI_PEGAWAI_ID");
	$arrData[$index_loop]["JADWAL_ASESOR_POTENSI_ID"]= $set_detil->getField("JADWAL_ASESOR_POTENSI_ID");
	$arrData[$index_loop]["ASESOR_POTENSI_ID"]= $set_detil->getField("ASESOR_POTENSI_ID");
	
	$arrData[$index_loop]["NOMOR_URUT_GENERATE"]= $tempNomorUrut;
	
	$index_loop++;
}
	
echo json_encode($arrData);
?>
