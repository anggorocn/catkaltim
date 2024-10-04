<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaJabatanTarget.php");
include_once("../WEB/classes/utils/UserLogin.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new FormulaJabatanTarget();

$reqMode= httpFilterPost("reqMode");
$reqId= httpFilterPost("reqId");
$reqNama= httpFilterPost("reqNama");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqFormulaSuksesiId= httpFilterPost("reqFormulaSuksesiId");
$reqJabatanId= httpFilterPost("reqJabatanId");
$reqRumpunId= httpFilterPost("reqRumpunId");
$reqSatkerId= httpFilterPost("reqSatkerId");
$reqTarget= httpFilterPost("reqTarget");

$set->setField("FORMULA_JABATAN_TARGET_ID", $reqId);
$set->setField("NAMA", $reqNama);
$set->setField("KETERANGAN", $reqKeterangan);
$set->setField("FORMULA_JABATAN_TARGET_ID", $reqId);
$set->setField("TARGET", ValToNullDB($reqTarget));
$set->setField("FORMULA_SUKSESI_ID", ValToNullDB($reqFormulaSuksesiId));
$set->setField("JABATAN_ID", $reqJabatanId);
$set->setField("RUMPUN_ID", $reqRumpunId);
$set->setField("SATKER_ID", $reqSatkerId);
$set->setField("LAST_CREATE_USER", $userLogin->idUser);
$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	

if($reqMode == "insert")
{
	if($set->insert())
	{
		$reqId = $set->id;
		echo $reqId."-Data berhasil disimpan.";
	}
	else
		echo "xxx-Data berhasil disimpan.";
}
else
{
	
	if($set->update())
	{
		echo $reqId."-Data berhasil disimpan.";
	}
	else
		echo "xxx-Data berhasil disimpan.";
}
//echo $set->query;exit;
?>