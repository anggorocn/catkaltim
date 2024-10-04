<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaFaktor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set= new FormulaFaktor();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");
$reqFormulaFaktorId			= httpFilterPost("reqFormulaFaktorId");


$reqAssesment= httpFilterPost("reqAssesment");
$reqGrafikId= httpFilterPost("reqGrafikId");
$reqKuadranId= httpFilterPost("reqKuadranId");


$set->setField('ASSESMENT', $reqAssesment);
$set->setField('ID_GRAFIK', ValToNullDB($reqGrafikId));
$set->setField('ID_KUADRAN', $reqKuadranId);
$set->setField('FORMULA_ID', $reqId);


if($reqFormulaFaktorId == "")
{
	$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	
	if($set->insert())
	{
		$reqId= $set->id;
		$mode = 'Data berhasil disimpan';
		//echo $set->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';
		//echo $set->query;exit;		
	echo $reqId."-".$mode;
}
elseif($reqFormulaFaktorId !== "" && $reqMode == "update")
{
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set->setField('FORMULA_ID',$reqId);
	$set->setField('FORMULA_FAKTOR_ID',$reqFormulaFaktorId);
	
	if($set->update())
	{
		$mode = 'Data berhasil disimpan';
		//echo $set->query;exit;
	}
	else
		$mode = 'Data gagal disimpan';
		
	//echo $set->query;exit;	
	echo $reqId."-".$mode;
}

	// echo $set->query;exit;	

?>