<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");

$tempValue="";
$set= new Pelamar();
$reqId= $userLogin->userPelamarId;
$set->selectByParamsInfoJejak($reqId);
//echo $set->query;exit;
$set->firstRow();
$tempJejakDataPribadi= $set->getField("JEJAK_SIMPAN_DAFTAR");
$tempJejakDataPribadiPangkat= $set->getField("JEJAK_SIMPAN_DAFTAR_PANGKAT");
$tempJejakDataPribadiJabatan= $set->getField("JEJAK_SIMPAN_DAFTAR_JABATAN");
$tempJejakDataPribadiPendidikan= $set->getField("JEJAK_SIMPAN_DAFTAR_PENDIDIKAN");
$tempJejakDataPribadiPelatihan= $set->getField("JEJAK_SIMPAN_DAFTAR_PELATIHAN");
$tempJejakDataPribadiPenugasan= $set->getField("JEJAK_SIMPAN_DAFTAR_PENUGASAN");
$tempJejakDataPribadiLain= $set->getField("JEJAK_SIMPAN_DAFTAR_LAIN");

$tempJejakPendidikanFormal= $set->getField("JEJAK_SIMPAN_PENDIDIKAN");
$tempJejakPengalamanBekerja= $set->getField("JEJAK_SIMPAN_PENGALAMAN");
$tempJejakPelatihan= $set->getField("JEJAK_SIMPAN_SERTIFIKASI");
$tempJejakArahMinat= $set->getField("JEJAK_SIMPAN_MINAT");
unset($set);

if($tempJejakPendidikanFormal == "icon-sudah")
	$tempValue= 1;

$arrFinal = array("VALUE_VALIDASI" => $tempValue);

echo json_encode($arrFinal);
?>