<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PegawaiHcdp.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqFormulaId= httpFilterGet("reqFormulaId");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

if(!empty($reqFormulaId))
{
	$reqJadwalTesId= "";
}

if(!empty($reqJadwalTesId))
{
	$set= new PegawaiHcdp();
	$set->selectByParamsJadwalTes(array("A.JADWAL_TES_ID"=>$reqJadwalTesId));
	$set->firstRow();
	$reqFormulaId= $set->getField("FORMULA_ID");
}

// echo $."-".$reqFormulaId;exit;
$set= new PegawaiHcdp();
$set->setField("FORMULA_ID", $reqFormulaId);
$set->setField("JADWAL_TES_ID", ValToNullDB($reqJadwalTesId));
$set->sethcdp();

echo "1";
?>