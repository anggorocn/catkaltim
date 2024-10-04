<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/BankSoal.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLoginAdmin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new BankSoal();

$reqRowId= httpFilterRequest("reqRowId");

$jumlah_data= $set->getCountByParams(array("BANK_SOAL_ID"=>$reqRowId));
/*if($jumlah_data > 0)
{
	echo "BankSoal Telah Dipilih";
}
else
{
	echo "1";
}*/
echo "1";
?>