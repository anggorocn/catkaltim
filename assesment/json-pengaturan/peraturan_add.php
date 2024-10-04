<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Permen.php");
include_once("../WEB/classes/utils/UserLogin.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new Permen();

$reqPermenId= httpFilterPost("reqPermenId");
$reqMode= httpFilterPost("reqMode");
$reqNama= httpFilterPost("reqNama");
$reqKeterangan= httpFilterPost("reqKeterangan");
$reqStatus= httpFilterPost("reqStatus");

$set->setField("PERMEN_ID", $reqPermenId);
$set->setField("NAMA", $reqNama);
$set->setField("KETERANGAN", $reqKeterangan);
$set->setField("STATUS", setNULL($reqStatus));

if($reqMode == "insert")
{
	if($set->insert())
	{
		$reqId = $set->id;

		if($reqStatus == "1")
		{
			$set->setField("PERMEN_ID", $reqId);
			$set->setField("FIELD", "STATUS");
			$set->setField("FIELD_VALUE", "0");
			$set->updateNonaktif();
		}
		
		echo "Data berhasil disimpan.";
		//echo $set->query;exit;
	}
}
else
{
	
	if($set->update())
	{
		if($reqStatus == "1")
		{
			$set->setField("PERMEN_ID", $reqPermenId);
			$set->setField("FIELD", "STATUS");
			$set->setField("FIELD_VALUE", "0");
			$set->updateNonaktif();
		}
		echo "Data berhasil disimpan.";
	}
	
}

//echo $set->query;exit;
?>