<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Satker.php");
include_once("../WEB/classes/utils/UserLogin.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new Satker();

$reqSatkerId= httpFilterPost("reqSatkerId");
$reqSatkerParentId= httpFilterPost("reqSatkerParentId");
$reqMode= httpFilterPost("reqMode");
$reqNama= httpFilterPost("reqNama");
$reqTipe= httpFilterPost("reqTipe");

// print_r($reqTipe);exit;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


$simpan= "";
if($reqTipe=="internal")
{

	$set->setField("SATKER_ID", $reqSatkerId);
	$set->setField("SATKER_ID_PARENT", $reqSatkerParentId);
	$set->setField("NAMA", $reqNama);


	if($reqMode == "insert")
	{
		$set->setField("LAST_CREATE_USER", $userLogin->nama);
		$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
		$set->setField("LAST_CREATE_USER", $userLogin->userSatkerId);
		if($set->insertmaster())
		{
			echo "-Data berhasil disimpan.";
			exit();
		}
	}
	else
	{
		$set->setField("LAST_UPDATE_USER", $userLogin->nama);
		$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
		$set->setField("LAST_UPDATE_USER", $userLogin->userSatkerId);
		$set->setField("SATKER_ID", $reqSatkerId);

		if($set->updatemaster())
		{
			echo "-Data berhasil disimpan.";
			exit();
		}
	}

}
else
{

	$set->setField("SATKER_EKSTERNAL_ID", $reqSatkerId);
	$set->setField("SATKER_EKSTERNAL_ID_PARENT", $reqSatkerParentId);
	$set->setField("NAMA", $reqNama);


	if($reqMode == "insert")
	{
		$set->setField("LAST_CREATE_USER", $userLogin->nama);
		$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
		$set->setField("LAST_CREATE_USER", $userLogin->userSatkerId);
		if($set->inserteksternal())
		{
			echo "-Data berhasil disimpan.";
			exit();
		}
	}
	else
	{
		$set->setField("LAST_UPDATE_USER", $userLogin->nama);
		$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
		$set->setField("LAST_UPDATE_USER", $userLogin->userSatkerId);
		$set->setField("SATKER_EKSTERNAL_ID", $reqSatkerId);

		if($set->updateeksternal())
		{
			echo "-Data berhasil disimpan.";
			exit();
		}
	}

}



if($simpan == "")
{
	echo "xxx-Data gagal disimpan.";
}
  // echo $set->query;exit;

?>