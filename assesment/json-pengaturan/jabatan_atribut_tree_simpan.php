<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/SatkerEselonAtribut.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

$reqMode= $_GET["reqMode"];
$reqAspekId= $_GET["reqAspekId"];
$reqTahun= $_GET["reqTahun"];
$reqEselonId= $_GET["reqEselonId"];
$reqSatuanKerjaId= $_GET["reqSatuanKerjaId"];
$reqAtributId= $_GET["reqAtributId"];

$statement= " AND A.ATRIBUT_ID = '".$reqAtributId."'";
$set= new SatkerEselonAtribut();
$set->selectByParamsJabatanAtributLookup(array(), -1,-1, $reqEselonId, $reqSatuanKerjaId, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempKondisiStatus= $set->getField("KONDISI_STATUS");
$tempAtributParentId= $set->getField("ATRIBUT_ID_PARENT");
unset($set);
//echo $tempAtributParentId;exit;
if($reqEselonId == "" || $reqSatuanKerjaId == "" || $reqTahun == ""){}
else
{
	if($reqMode == "hapus")
	{
		$set= new SatkerEselonAtribut();
		$set->setField("TAHUN", $reqTahun);
		$set->setField("ESELON_ID", $reqEselonId);
		$set->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
		$set->setField("ATRIBUT_ID", $reqAtributId);
		$set->delete();
		//echo $set->query;exit;
	}
	else
	{
		// cek parent sdh disimpan atau belum
		if(strlen($tempAtributParentId) == 2)
		{
			$set= new SatkerEselonAtribut();
			$set->selectByParams(array(), -1,-1, " AND ATRIBUT_ID = '".$tempAtributParentId."' AND TAHUN = '".$reqTahun."' AND ESELON_ID ='".$reqEselonId."' AND SATUAN_KERJA_ID = '".$reqSatuanKerjaId."'");
			//echo $set->query;exit;
			$set->firstRow();
			$tempRowId= $set->getField("SATKER_ESELON_ATRIBUT_ID");
			unset($set);
			
			if($tempRowId == "")
			{
				$set= new SatkerEselonAtribut();
				$set->setField("ASPEK_ID", $reqAspekId);
				$set->setField("TAHUN", $reqTahun);
				$set->setField("ESELON_ID", $reqEselonId);
				$set->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
				$set->setField("ATRIBUT_ID", $tempAtributParentId);
				$set->setField("ATRIBUT_PARENT_ID", "0");
				$set->insert();
			}
		}
		
		if($tempKondisiStatus == "")
		{
			$set= new SatkerEselonAtribut();
			$set->selectByParams(array(), -1,-1, " AND ATRIBUT_ID = '".$reqAtributId."' AND TAHUN = '".$reqTahun."' AND ESELON_ID ='".$reqEselonId."' AND SATUAN_KERJA_ID = '".$reqSatuanKerjaId."'");
			//echo $set->query;exit;
			$set->firstRow();
			$tempRowId= $set->getField("SATKER_ESELON_ATRIBUT_ID");
			unset($set);
			
			if($tempRowId == "")
			{
				$set= new SatkerEselonAtribut();
				$set->setField("ASPEK_ID", $reqAspekId);
				$set->setField("TAHUN", $reqTahun);
				$set->setField("ESELON_ID", $reqEselonId);
				$set->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
				$set->setField("ATRIBUT_ID", $reqAtributId);
				$set->setField("ATRIBUT_PARENT_ID", $tempAtributParentId);
				$set->insert();
				//echo $set->query;exit;
			}
		}
	}
}
exit;

?>