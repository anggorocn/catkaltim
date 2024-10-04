<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
// include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base-diklat/Peserta.php");

// $set= new Pelamar();
$set= new Peserta();

$reqVal= httpFilterGet("reqVal");
//$reqVal=123;
$set->selectByParams(array(),-1,-1, " AND (A.NIP_BARU = '".$reqVal."' OR A.NIK = '".$reqVal."')");
// echo $set->query;exit;
$set->firstRow();
$tempValue= $set->getField("NAMA");

$arrFinal = array("VALUE_VALIDASI" => $tempValue);

echo json_encode($arrFinal);
?>