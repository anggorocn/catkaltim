<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaUnsur.php");
include_once("../WEB/classes/base/FormulaUnsurBobot.php");
include_once("../WEB/classes/base/LevelAtribut.php");
include_once("../WEB/classes/base/AtributPenggalian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqRowId= httpFilterPost("reqRowId");
$reqAspekId= httpFilterPost("reqAspekId");

$reqFormulaEselonPermenId= httpFilterPost("reqFormulaEselonPermenId");
$reqBobotAtributId= $_POST["reqBobotAtributId"];
$reqAtributNilaiStandar= $_POST["reqAtributNilaiStandar"];
$reqAtributBobot= $_POST["reqAtributBobot"];
$reqFormulaUnsurBobotId= $_POST["reqFormulaUnsurBobotId"];
//print_r($reqBobotAtributId);
//exit;
if($reqMode == "insert")
{
	for($i=0; $i < count($reqFormulaUnsurBobotId); $i++)
	{
		$set_detil= new FormulaUnsurBobot();
		$set_detil->setField('FORMULA_UNSUR_ID',$reqId);
		// $set_detil->setField('ASPEK_ID', $reqAspekId);
		$set_detil->setField('UNSUR_ID', $reqBobotAtributId[$i]);
		$set_detil->setField('UNSUR_BOBOT', ValToNullDB($reqAtributNilaiStandar[$i]));
		$set_detil->setField('BOBOT', ValToNullDB($reqAtributBobot[$i]));
		$set_detil->setField('FORMULA_UNSUR_BOBOT_ID', $reqFormulaUnsurBobotId[$i]);

		if($reqFormulaEselonPermenId == "")
		{
			$set_detil->setField('PERMEN_ID', "(SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')");
		}
		else
		{
			$set_detil->setField('PERMEN_ID', ValToNullDB($reqFormulaEselonPermenId));
		}

		if($reqFormulaUnsurBobotId[$i] == "")
		{
			$set_detil->insert();
		}
		else
		{
			$set_detil->update();
		}

		// if($reqFormulaUnsurBobotId[$i] == 19)
		// {
			// echo $set_detil->query;exit;
		// }
	}
	echo "Data berhasil disimpan";
}
?>