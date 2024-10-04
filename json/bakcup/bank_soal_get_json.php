<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/BankSoal.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");

/* create objects */

$set_data = new BankSoal();

$reqBankSoalId= httpFilterGet("reqBankSoalId");

/* LOGIN CHECK */
if ($userLogin->checkUserLoginAdmin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set_data->selectByParams(array('A.BANK_SOAL_ID'=>$reqBankSoalId));
$set_data->firstRow();

$tempBankSoalId=$set_data->getField("BANK_SOAL_ID");
$tempPertanyaan=$set_data->getField("PERTANYAAN");


$arrFinal = array(
"tempBankSoalId"=>$tempBankSoalId, "tempPertanyaan"=>$tempPertanyaan);

echo json_encode($arrFinal);
?>