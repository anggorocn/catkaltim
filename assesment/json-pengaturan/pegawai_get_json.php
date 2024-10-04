<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");

/* variable */
$reqId = httpFilterGet("reqId");

include_once("../WEB/classes/base/JadwalPegawai.php");
$set= new JadwalPegawai();
$set->selectByParamsLookupPegawai(array("A.PEGAWAI_ID" => $reqId), -1, -1);
$set->firstRow();
$tempId= $set->getField("ID");
$tempNama= $set->getField("PEGAWAI_NAMA");
$tempNip= $set->getField("PEGAWAI_NIP");
$tempGol= $set->getField("PEGAWAI_GOL");
$tempEselon= $set->getField("PEGAWAI_ESELON");
$tempJabatan= $set->getField("PEGAWAI_JAB_STRUKTURAL");
unset($set);
$arrFinal = array(
"tempId" => $tempId, "tempNama" => $tempNama, "tempNip" => $tempNip, "tempGol" => $tempGol, "tempEselon" => $tempEselon, "tempJabatan" => $tempJabatan
);
	
echo json_encode($arrFinal);
?>
