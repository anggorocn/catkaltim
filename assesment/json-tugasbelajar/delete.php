<?
//include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");

$reqId = $_GET['id'];
$reqRowDetilId= $_GET['reqRowDetilId'];
$reqMode = $_GET['reqMode'];

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/
if($reqMode == "tugas_belajar")
{
	include_once("../WEB/classes/base-tugasbelajar/TugasBelajar.php");
	$set = new TugasBelajar();
	$set->setField("TUGAS_BELAJAR_ID", $reqId);
	if($set->deleteAll())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if ($reqMode == "hukuman")
{
	include_once("../WEB/classes/base-tugasbelajar/Hukuman.php");
	$set = new Hukuman();
	$set->setField("HUKUMAN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
?>