<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");

$set = new UjianPegawaiDaftar();

/* LOGIN CHECK  */

if ($userLogin->checkUserLoginAdmin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqPangkatId= httpFilterGet("reqPangkatId");
$reqJabatanId= httpFilterGet("reqJabatanId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqLowonganTahapanId= httpFilterGet("reqLowonganTahapanId");

if($reqId == ""){}
else
$statement = " AND A.UJIAN_ID = ".$reqId;

if($reqPangkatId == ""){}
else
$statement.= " AND B.PANGKAT_ID = ".$reqPangkatId;

if($reqJabatanId == ""){}
else
$statement.= " AND B.JABATAN_ID = ".$reqJabatanId;

$searchJson= " AND (UPPER(B.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
$tempTotal= $set->getCountByParamsMonitoring(array(), $statement);
echo $tempTotal;
?>
