<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaEselon.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqTahun= httpFilterPost("reqTahun");

$reqFormulaEselonId= $_POST["reqFormulaEselonId"];
$reqEselonId= $_POST["reqEselonId"];
$reqProsenPotensi= $_POST["reqProsenPotensi"];
$reqProsenKomptensi= $_POST["reqProsenKomptensi"];

if($reqMode == "insert")
{
	for($i=0; $i < count($reqFormulaEselonId); $i++)
	{
		if($reqEselonId[$i] == ""){}
		else
		{
			if($reqProsenPotensi[$i] == "" && $reqProsenKomptensi[$i] == ""){}
			else
			{
				$set_detil= new FormulaEselon();
				$set_detil->setField('TAHUN', $reqTahun);
				$set_detil->setField('FORMULA_ID', $reqId);
				$set_detil->setField('ESELON_ID', $reqEselonId[$i]);
				$set_detil->setField('PROSEN_POTENSI', ValToNullDB($reqProsenPotensi[$i]));
				$set_detil->setField('PROSEN_KOMPETENSI', ValToNullDB($reqProsenKomptensi[$i]));
				$set_detil->setField('FORMULA_ESELON_ID',$reqFormulaEselonId[$i]);
				
				if($reqFormulaEselonId[$i] == "")
				{
					$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
					$set_detil->insert();
				}
				else 
				{
					$set_detil->setField("LAST_UPDATE_USER", $userLogin->idUser);
					$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
					$set_detil->update();
					// echo $set_detil->query;exit();
				}
			}
		}
	}
	echo "Data berhasil disimpan";
}
?>