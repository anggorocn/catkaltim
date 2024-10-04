<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PenggalianSatkerPenilaian.php");
include_once("../WEB/classes/base/SatkerEselonAtribut.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

$reqMode= $_GET["reqMode"];
$reqSatkerEselonAtributId= $_GET["reqSatkerEselonAtributId"];
$reqTahun= $_GET["reqTahun"];
$reqAtributId= $_GET["reqAtributId"];
$reqPenggalianId= $_GET["reqPenggalianId"];

$statement= " AND A.SATKER_ESELON_ATRIBUT_ID = ".$reqSatkerEselonAtributId;
$set= new SatkerEselonAtribut();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqAtributId= $set->getField("ATRIBUT_ID");
$reqTahun= $set->getField("TAHUN");
unset($set);
//echo $tempAtributParentId;exit;
if($reqSatkerEselonAtributId == "" || $reqAtributId == "" || $reqTahun == "" || $reqPenggalianId == ""){}
else
{
	if($reqMode == "hapus")
	{
		$set= new PenggalianSatkerPenilaian();
		$set->setField("PENGGALIAN_ID", $reqPenggalianId);
		$set->setField("SATKER_ESELON_ATRIBUT_ID", $reqSatkerEselonAtributId);
		$set->deleteAll();
		//echo $set->query;exit;
	}
	else
	{
		$set= new PenggalianSatkerPenilaian();
		$set->selectByParams(array(), -1,-1, " AND ATRIBUT_ID = '".$reqAtributId."' AND TAHUN = '".$reqTahun."' AND SATKER_ESELON_ATRIBUT_ID =".$reqSatkerEselonAtributId." AND PENGGALIAN_ID = ".$reqPenggalianId);
		//echo $set->query;exit;
		$set->firstRow();
		$tempRowId= $set->getField("SATKER_ESELON_ATRIBUT_ID");
		unset($set);
		
		if($tempRowId == "")
		{
			$set= new PenggalianSatkerPenilaian();
			$set->setField("PENGGALIAN_ID", $reqPenggalianId);
			$set->setField("TAHUN", $reqTahun);
			$set->setField("SATKER_ESELON_ATRIBUT_ID", $reqSatkerEselonAtributId);
			$set->setField("ATRIBUT_ID", $reqAtributId);
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set->insert();
			//echo $set->query;exit;
		}
	}
}
exit;
?>