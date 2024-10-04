<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/MasterJabatan.php");
include_once("../WEB/classes/utils/UserLogin.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new MasterJabatan();

$reqMasterJabatanId= httpFilterPost("reqMasterJabatanId");
$reqMasterJabatanParentId= httpFilterPost("reqMasterJabatanParentId");
$reqTahun= httpFilterPost("reqTahun"); 
$reqMode= httpFilterPost("reqMode");
$reqNama= httpFilterPost("reqNama");
$reqKet= httpFilterPost("reqKet");
$reqSatkerId= httpFilterPost("reqSatkerId");
$reqRumpunId= httpFilterPost("reqRumpunId");
$reqEselonId= httpFilterPost("reqEselonId");
// echo $reqSatkerId.'---'.$reqRumpunId.'----'.$reqEselonId; exit; 
 
$set->setField("JABATAN_ID", $reqMasterJabatanId);
$set->setField("JABATAN_ID_PARENT", $reqMasterJabatanParentId);  
$set->setField("NAMA_JABATAN", $reqNama);
$set->setField("KODE_JABATAN", $reqKet);
$set->setField("SATKER_ID", $reqSatkerId);
$set->setField("RUMPUN_ID", $reqRumpunId);
$set->setField("ESELON_ID", ValToNullDB($reqEselonId));



//echo $set->query();exit;
$simpan= "";
if($reqMode == "insert")
{
	$set->setField("LAST_CREATE_USER", $userLogin->nama);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	$set->setField("LAST_CREATE_USER", $userLogin->userSatkerId);
	if($set->insert())
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
	$set->setField("USER_GROUP_ID", $reqId);
	
	if($set->update())
	{
		echo "-Data berhasil disimpan.";
		exit();
	}
}


if($simpan == "")
{
	echo "xxx-Data gagal disimpan.";
}
	// echo $set->query;exit;


?>