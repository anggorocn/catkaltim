<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalPegawaiDetilKomentar.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$tempAsesorId= $userLogin->userAsesorId;

if($tempAsesorId == "")
exit;

$reqStatus= httpFilterRequest("reqStatus");
$reqAsesorId= httpFilterRequest("reqAsesorId");
$reqAtributId= httpFilterRequest("reqAtributId");
$reqLevelId= httpFilterRequest("reqLevelId");
$reqIndikatorId= httpFilterRequest("reqIndikatorId");
$reqPegawaiId= httpFilterRequest("reqPegawaiId");
$reqJadwalPegawaiId= httpFilterRequest("reqJadwalPegawaiId");
$reqJadwalTesId= httpFilterRequest("reqJadwalTesId");
$reqKeterangan= httpFilterRequest("reqKeterangan");
$reqMode= httpFilterRequest("reqMode");

if($reqMode == "insert")
{
	$statement= " AND A.ASESOR_ID = ".$reqAsesorId." AND A.ATRIBUT_ID = '".$reqAtributId."' AND A.LEVEL_ID = ".$reqLevelId." AND A.INDIKATOR_ID = ".$reqIndikatorId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
	$set= new JadwalPegawaiDetilKomentar();
	$set->selectByParams(array(), -1,-1, $statement);
	$set->firstRow();
	$tempRowId= $set->getField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID");
	$tempKeterangan= $set->getField("KETERANGAN");
	unset($set);
	
	$set= new JadwalPegawaiDetilKomentar();
	$set->setField("LEVEL_ID", $reqLevelId);
	$set->setField("INDIKATOR_ID", $reqIndikatorId);
	$set->setField("JADWAL_PEGAWAI_ID", $reqJadwalPegawaiId);
	$set->setField("JADWAL_TES_ID", $reqJadwalTesId);
	
	if($reqStatus == "1")
	$set->setField("KETERANGAN", ValToNullDB($req));
	else
	{
		if($reqKeterangan == "")
		$set->setField("KETERANGAN", ValToNullDB("Tidak Setuju"));
		else
		$set->setField("KETERANGAN", "'".setQuote($reqKeterangan)."'");
	}
	
	$set->setField("ATRIBUT_ID", $reqAtributId);
	$set->setField("PEGAWAI_ID", $reqPegawaiId);
	$set->setField("ASESOR_ID", $reqAsesorId);
	$set->setField("ASESOR_KOMENTAR_ID", $tempAsesorId);
	
	$tempStatus= "";
	if($tempRowId == "")
	{
		$set->setField("LAST_CREATE_USER", $userLogin->idUser);
		$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
		$set->insert();
		$tempStatus= "1";
	}
	else
	{
		$set->setField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID", $tempRowId);
		$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
		$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
		$set->update();
		$tempStatus= "1";
	}
	echo $tempStatus;
	//echo $set->query;
}
elseif($reqMode == "data")
{
	$statement= " AND A.ASESOR_ID = ".$reqAsesorId." AND A.ATRIBUT_ID = '".$reqAtributId."' AND A.LEVEL_ID = ".$reqLevelId." AND A.INDIKATOR_ID = ".$reqIndikatorId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
	$set= new JadwalPegawaiDetilKomentar();
	$set->selectByParams(array(), -1,-1, $statement);
	$set->firstRow();
	$tempRowId= $set->getField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID");
	$tempKeterangan= str_replace("\n","<br/>",$set->getField("KETERANGAN"));
	unset($set);
	
	echo $tempKeterangan;
}
?>