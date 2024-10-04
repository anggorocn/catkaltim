<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PenggalianPenilaian.php");
include_once("../WEB/classes/base/JabatanEselonAtribut.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

$reqMode= $_GET["reqMode"];
$reqJabatanEselonAtributId= $_GET["reqJabatanEselonAtributId"];
$reqTahun= $_GET["reqTahun"];
$reqAtributId= $_GET["reqAtributId"];
$reqPenggalianId= $_GET["reqPenggalianId"];

$statement= " AND A.JABATAN_ESELON_ATRIBUT_ID = ".$reqJabatanEselonAtributId;
$set= new JabatanEselonAtribut();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqAtributId= $set->getField("ATRIBUT_ID");
$reqTahun= $set->getField("TAHUN");
unset($set);
//echo $tempAtributParentId;exit;
if($reqJabatanEselonAtributId == "" || $reqAtributId == "" || $reqTahun == "" || $reqPenggalianId == ""){}
else
{
	if($reqMode == "hapus")
	{
		$set= new PenggalianPenilaian();
		$set->setField("PENGGALIAN_ID", $reqPenggalianId);
		$set->setField("JABATAN_ESELON_ATRIBUT_ID", $reqJabatanEselonAtributId);
		$set->deleteAll();
		//echo $set->query;exit;
	}
	else
	{
		$set= new PenggalianPenilaian();
		$set->setField("PENGGALIAN_ID", $reqPenggalianId);
		$set->setField("TAHUN", $reqTahun);
		$set->setField("JABATAN_ESELON_ATRIBUT_ID", $reqJabatanEselonAtributId);
		$set->setField("ATRIBUT_ID", $reqAtributId);
		$set->setField("LAST_CREATE_USER", $userLogin->idUser);
		$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
		$set->insert();
		//echo $set->query;exit;
	}
}
exit;
?>