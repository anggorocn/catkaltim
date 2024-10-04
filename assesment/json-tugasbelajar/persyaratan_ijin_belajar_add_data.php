<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-tugasbelajar/ProsesSyaratBelajar.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}


$reqId 		  = httpFilterPost("reqId");
$reqSyarat 	  = $_POST["reqSyarat"];
$reqCek 	  = $_POST["reqCek"];

$set_delete = new ProsesSyaratBelajar;
$set_delete->setField("TUGAS_BELAJAR_ID", $reqId);
$set_delete->delete();

for($i=0; $i<count($reqSyarat); $i++)
{
	$set = new ProsesSyaratBelajar();
	$set->setField("TUGAS_BELAJAR_ID", $reqId);
	$set->setField("PERSYARATAN_ID", $reqSyarat[$i]);
	$set->setField("MEMENUHI", ValToNull($reqCek[$i]));
	$set->setField("TEST", ValToNull($reqCek[$i]));
	$set->insert();
	unset($set);
}
//echo $set->query;exit;
echo $reqId."-Data Berhasil Disimpan";
?>