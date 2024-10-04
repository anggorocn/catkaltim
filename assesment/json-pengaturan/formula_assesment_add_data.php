<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaAssesment.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set= new FormulaAssesment();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqFormula= httpFilterPost("reqFormula");
$reqTahun= httpFilterPost("reqTahun");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqTipeFormula= httpFilterPost("reqTipeFormula");

$set->setField('FORMULA', $reqFormula);
$set->setField('TAHUN', $reqTahun);
$set->setField('KETERANGAN', $reqKeterangan);
$set->setField('TIPE_FORMULA', $reqTipeFormula);

if($reqMode == "insert")
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
elseif($reqMode == "update")
{
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set->setField('FORMULA_ID',$reqId);
	
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
?>