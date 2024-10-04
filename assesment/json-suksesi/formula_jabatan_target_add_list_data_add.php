<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaJabatanTargetPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$reqArrPegawaiId= explode(",", $reqPegawaiId);

for($i=0; $i<count($reqArrPegawaiId); $i++)
{
	$reqRowId= $reqArrPegawaiId[$i];
	$set= new FormulaJabatanTargetPegawai();
	$set->setField('FORMULA_JABATAN_TARGET_ID', $reqId);
	$set->setField('PEGAWAI_ID', $reqRowId);
	// $set->setField('STATUS',ValToNullDB($reqStatus));
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	$set->insert();
	// echo $set->query;exit();
	unset($set);
}
echo "1";
?>