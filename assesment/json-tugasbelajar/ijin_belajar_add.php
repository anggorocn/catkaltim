<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-tugasbelajar/TugasBelajar.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqId= httpFilterRequest("reqId");

$set= new TugasBelajar();
$set->selectByParams(array("A.TUGAS_BELAJAR_ID" => $reqId));
//echo $set->query;exit;
$set->firstRow();
$tempStatusBelajar= $set->getField("STATUS_BELAJAR");
$tempTmtSelesai= dateToPageCheck($set->getField("TMT_SELESAI_PERPANJANGAN"));
if($tempStatusBelajar == "1")
{
	$set= new TugasBelajar();
	$set->setField("TUGAS_BELAJAR_ID", $reqId);
	$set->setField("TMT_SELESAI", dateToDBCheck($tempTmtSelesai));
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);

	if($set->updatePerpanjangan())
	{
		echo "1";
	}
}
else
{
	echo "Status belajar bukan, Tugas Belajar";
}
?>