<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UnsurPenilaian.php");
include_once("../WEB/classes/utils/UserLogin.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new UnsurPenilaian();

$reqUnsurPenilaianId= httpFilterPost("reqUnsurPenilaianId");
$reqUnsurPenilaianParentId= httpFilterPost("reqUnsurPenilaianParentId");
$reqTahun= httpFilterPost("reqTahun"); 
$reqMode= httpFilterPost("reqMode");
$reqNama= httpFilterPost("reqNama");
$reqKet= httpFilterPost("reqKet");
 
 

$set->setField("UNSUR_ID", $reqUnsurPenilaianId);
$set->setField("UNSUR_ID_PARENT", $reqUnsurPenilaianParentId);  
$set->setField("NAMA", $reqNama);
 $set->setField("KETERANGAN", $reqKet);
// $set->setField("BOBOT", setValNol($reqBobot));
// $set->setField("NILAI_STANDAR", setValNol($reqNilaiStandar));
// $set->setField("NILAI_ES2", setValNol($reqNilaiEs2));
// $set->setField("NILAI_ES3", setValNol($reqNilaiEs3));
// $set->setField("NILAI_ES4", setValNol($reqNilaiEs4));
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