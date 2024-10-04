<?
//include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");

$reqId = $_GET['id'];
$reqRowDetilId= $_GET['reqRowDetilId'];
$reqMode = $_GET['reqMode'];
$reqFormulaUnsurId = $_GET['id'];

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base/FormulaUnsur.php");
include_once("../WEB/classes/base/FormulaSuksesi.php");
include_once("../WEB/classes/base/FormulaJabatanTarget.php");

if($reqMode == "pegawai_kandidat")
{
	$set = new Kelautan();
	$set->setField("PEGAWAI_ID_PENSIUN", $reqRowDetilId);
	$set->setField("PEGAWAI_ID_PENGGANTI", $reqId);
	if($set->deleteKandidat())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "formula_atribut")
{
	$set = new FormulaUnsur();
	$set->setField("FORMULA_UNSUR_ID", $reqFormulaUnsurId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
	// echo $set->query;exit;
}
else if($reqMode == "formula_suksesi")
{
	$set = new FormulaSuksesi();
	$set->setField("FORMULA_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
	// echo $set->query;exit;
}
else if($reqMode == "formula_jabatan_target")
{
	$set = new FormulaJabatanTarget();
	$set->setField("FORMULA_JABATAN_TARGET_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
	// echo $set->query;exit;
}
?>