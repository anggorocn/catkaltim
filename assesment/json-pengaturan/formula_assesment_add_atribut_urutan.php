<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaAssesmentAtributUrutan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");

$reqFormulaEselonId= $_POST["reqFormulaEselonId"];
$reqFormulaPermenId= $_POST["reqFormulaPermenId"];
$reqFormulaAtributId= $_POST["reqFormulaAtributId"];
$reqFormulaUrut= $_POST["reqFormulaUrut"];
//print_r($reqFormulaUrut);
//exit;
if($reqMode == "insert")
{
	$set= new FormulaAssesmentAtributUrutan();
	$set->setField('FORMULA_ID', $reqId);
	$set->setField('FORMULA_ESELON_ID', $reqFormulaEselonId[0]);
	$set->delete();
	
	for($i=0; $i < count($reqFormulaEselonId); $i++)
	{
		if($reqFormulaEselonId[$i] == ""){}
		else
		{
			$set_detil= new FormulaAssesmentAtributUrutan();
			$set_detil->setField('FORMULA_ID', $reqId);
			$set_detil->setField('FORMULA_ESELON_ID',$reqFormulaEselonId[$i]);
			$set_detil->setField('PERMEN_ID', $reqFormulaPermenId[$i]);
			$set_detil->setField('ATRIBUT_ID', $reqFormulaAtributId[$i]);
			$set_detil->setField('URUT', $reqFormulaUrut[$i]);
			$set_detil->insert();
			// echo $set_detil->query;exit;
		}
	}

	echo "Data berhasil disimpan";
}
?>                                                                                              
                                                                                              
