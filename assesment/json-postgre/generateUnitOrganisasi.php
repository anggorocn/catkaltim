<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-sql/SqlKonversi.php");
include_once "../json-sql/array_sql_server.php";

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}



//get array satker
$statement = " AND CONVERT(VARCHAR, YEAR(tglmulai)) >= 2016";
$arrData= getUnitOrgArray($statement);
//print_r($arrData);exit;

if(empty($arrData)){}
else
{
	for($index_data=0; $index_data < count($arrData); $index_data++)
	{
		$tempUnitKey= $arrData[$index_data]["KODE_UNIT"];
		$tempNamaUnit= $arrData[$index_data]["NAMA_UNIT"];
		
		$set_detil= new SqlKonversi();
		
		$statement= " AND KODE_UNIT = '".$tempUnitKey."'";
		$tempDataId= $set_detil->cariIdTable("KODE_UNIT", "UNIT_ORGANISASI", $statement);
		//echo $set_detil->query;exit;
		
		//CariProgPusatExist
		if($tempDataId == "")
		{
			$set_data= new SqlKonversi();
			$set_data->setField("KODE_UNIT", $tempUnitKey);
			$set_data->setField("NAMA_UNIT", setQuote($tempNamaUnit));
			$set_data->setField("LAST_CREATE_USER", $userLogin->loginnamauser);
			$set_data->setField("LAST_CREATE_DATE", "NOW()");
			$set_data->insertSatker();
			unset($set_data);
		}
		else
		{
			$set_data= new SqlKonversi();
			$set_data->setField("KODE_UNIT", $tempUnitKey);
			$set_data->setField("NAMA_UNIT", setQuote($tempNamaUnit));
			$set_data->setField("LAST_UPDATE_USER", $userLogin->loginnamauser);
			$set_data->setField("LAST_UPDATE_DATE", "NOW()");
			$set_data->updateSatker();
			unset($set_data);
		}
	}
}
echo "1";
?>