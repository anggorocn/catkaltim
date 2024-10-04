<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaAssesmentUjianTahap.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new FormulaAssesmentUjianTahap();

$reqId= httpFilterPost("reqId");

$reqRowId= $_POST["reqRowId"];
$reqUrutanTes= $_POST["reqUrutanTes"];

for($i=0; $i<count($reqRowId); $i++)
{
	if($reqRowId[$i] == ""){}
	else
	{
		$set = new FormulaAssesmentUjianTahap();
		$set->setField("LAST_UPDATE_USER", $userLogin->nama);
		$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
		$set->setField("URUTAN_TES", ValToNullDB($reqUrutanTes[$i]));
		$set->setField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID", $reqRowId[$i]);
		$set->updateUrutanTes();
		//echo $set->query;exit;
		unset($set);
	}
}
echo "Data Berhasil Disimpan.";
?>