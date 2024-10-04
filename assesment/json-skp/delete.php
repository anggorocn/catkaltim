<?
//include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");

$reqId = $_GET['id'];
$reqMode = $_GET['reqMode'];

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/
if($reqMode == "kategori")
{
	include_once("../WEB/classes/base/Kategori.php");
	$set = new Kategori();
	$set->setField('KATEGORI_ID', $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
elseif($reqMode == "boks_penyimpanan")
{
	include_once("../WEB/classes/base/BoksPenyimpanan.php");
	$set = new BoksPenyimpanan();
	$set->setField('BOKS_PENYIMPANAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
?>