<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/FormulaUnsurPegawai.php");
include_once("../WEB/classes/base-silat/FormulaUnsurPegawaiBobot.php");
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
// echo $reqRowId;exit;
$reqAspekId= httpFilterPost("reqAspekId");
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqFormulaEselonPermenId= httpFilterPost("reqFormulaEselonPermenId");

$reqStatusAtributId= $_POST["reqStatusAtributId"];
$reqLevelId= $_POST["reqLevelId"];
$reqNilaiStandar= $_POST["reqNilaiStandar"];
$reqFormulaUnsurPegawaiId= $_POST["reqFormulaUnsurPegawaiId"];
$reqAtributPenggalianId= $_POST["reqAtributPenggalianId"];
$reqAtributId= $_POST["reqAtributId"];

$reqBobotStatusAtributId= $_POST["reqBobotStatusAtributId"];
$reqFormulaUnsurPegawaiBobotId= $_POST["reqFormulaUnsurPegawaiBobotId"];
// print_r($reqFormulaUnsurPegawaiBobotId);exit;
$reqBobotAtributId= $_POST["reqBobotAtributId"];
$reqAtributNilaiStandar= $_POST["reqAtributNilaiStandar"];
$reqAtributBobot= $_POST["reqAtributBobot"];
$reqAtributSkor= $_POST["reqAtributSkor"];

//print_r($reqBobotAtributId);
//exit;
if($reqMode == "insert")
{
	for($i=0; $i < count($reqFormulaUnsurPegawaiBobotId); $i++)
	{
		$set_detil= new FormulaUnsurPegawaiBobot();
		$set_detil->setField('FORMULA_UNSUR_PEGAWAI_ID',$reqRowId);
		$set_detil->setField('PEGAWAI_ID',$reqPegawaiId);
		$set_detil->setField('UNSUR_ID', $reqBobotAtributId[$i]);
		$set_detil->setField('UNSUR_BOBOT', ValToNullDB($reqAtributNilaiStandar[$i]));
		$set_detil->setField('FORMULA_UNSUR_PEGAWAI_BOBOT_ID', $reqFormulaUnsurPegawaiBobotId[$i]);

		if($reqFormulaUnsurPegawaiBobotId[$i] == "")
		{
			// $set_detil->delete();
			$set_detil->insert();
			// echo $set_detil->query;
		}
		else
		{
			// $set_detil->delete();
			$set_detil->update();
			// echo $set_detil->query;
		}
	}
	// exit;
	
	
	echo "Data berhasil disimpan";
}
?>