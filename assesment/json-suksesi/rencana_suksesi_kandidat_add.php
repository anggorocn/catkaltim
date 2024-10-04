<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqPensiunId= httpFilterRequest("reqPensiunId");
$reqRowId= httpFilterRequest("reqRowId");

$statement= " AND A.PEGAWAI_ID_PENSIUN = ".$reqPensiunId." AND A.PEGAWAI_ID_PENGGANTI = ".$reqRowId." AND (A.STATUS IS NULL OR A.STATUS = 0)";
$set= new Kelautan();
$set->selectByParamsPegawaiKandidat(array(), -1,-1, $statement);
$set->firstRow();
$tempPegawaiIdPensiun= $set->getField("PEGAWAI_ID_PENSIUN");
//echo $set->query;exit;
unset($set);
//echo $tempPegawaiIdPensiun;exit;
if($tempPegawaiIdPensiun == "")
{
	$set= new Kelautan();
	$set->setField("PEGAWAI_ID_PENSIUN", $reqPensiunId);
	$set->setField("PEGAWAI_ID_PENGGANTI", $reqRowId);

	if($set->insertKandidat())
	{
		echo "1";
	}
}
else
{
	echo "Pegawai Kandidat sudah dipilih sebelumnya";
}
?>