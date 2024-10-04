<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");

/* variable */
$reqId= httpFilterGet("reqId");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

include_once("../WEB/classes/base/JadwalPegawai.php");
$statement= " AND REPLACE(A.NIP_BARU, ' ', '') = '".$reqId."'";
$statement.= " AND EXISTS (SELECT 1 FROM jadwal_tes_simulasi_pegawai X WHERE 1=1 AND X.JADWAL_TES_ID = ".$reqJadwalTesId." AND X.PEGAWAI_ID = A.PEGAWAI_ID)";

$set= new JadwalPegawai();
$set->selectByParamsLookupJadwalPegawai(array(), -1, -1, $statement, $reqJadwalTesId);
$set->firstRow();
// echo $set->query;exit;
$tempId= $set->getField("ID");
$tempNama= $set->getField("PEGAWAI_NAMA");
$tempNip= $set->getField("PEGAWAI_NIP");
$tempGol= $set->getField("PEGAWAI_GOL");
$tempEselon= $set->getField("PEGAWAI_ESELON");
$tempJabatan= $set->getField("PEGAWAI_JAB_STRUKTURAL");
$tempNomorUrut= $set->getField("NOMOR_URUT_GENERATE");
unset($set);
$arrFinal = array(
"tempId" => $tempId, "tempNama" => $tempNama, "tempNip" => $tempNip, "tempGol" => $tempGol, "tempEselon" => $tempEselon
, "tempJabatan" => $tempJabatan, "tempNomorUrut" => $tempNomorUrut
);
	
echo json_encode($arrFinal);
?>
