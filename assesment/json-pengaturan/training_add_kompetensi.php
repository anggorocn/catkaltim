<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/KompetensiTraining.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

$reqMode= $_GET["reqMode"];
$reqId= $_GET["reqId"];
$reqTahun= $_GET["reqTahun"];
$reqAtributId= $_GET["reqAtributId"];

$statement= " AND A.ATRIBUT_ID = '".$reqAtributId."' AND A.TAHUN = ".$reqTahun." AND A.TRAINING_ID = ".$reqId;
// kondisi aktif permen
$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM PERMEN WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

$set= new KompetensiTraining();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit;
$reqRowId= $set->getField("KOMPETENSI_TRAINING_ID");
unset($set);
//echo $tempAtributParentId;exit;
if($reqId == "" || $reqAtributId == "" || $reqTahun == ""){}
else
{
	if($reqMode == "hapus")
	{
		$set= new KompetensiTraining();
		$set->setField("KOMPETENSI_TRAINING_ID", $reqRowId);
		$set->delete();
		//echo $set->query;exit;
	}
	else
	{
		if($reqRowId == "")
		{
			$set= new KompetensiTraining();
			$set->setField("TRAINING_ID", $reqId);
			$set->setField("TAHUN", $reqTahun);
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