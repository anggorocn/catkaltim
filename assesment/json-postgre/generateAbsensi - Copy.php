<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-sql/SqlKonversi.php");
include_once "../json-sql/array_mysql.php";
include_once("../WEB/classes/base/Absensi.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTanggalAwal = httpFilterGet("reqTanggalAwal");
$reqTanggalAkhir = httpFilterGet("reqTanggalAkhir");

$arrayAbsensi = "";
$index_absensi = 0;
$absensi = new Absensi();
$absensi->selectByParamsRangeTanggal(array(), -1, -1, $reqTanggalAwal, $reqTanggalAkhir);
while($absensi->nextRow())
{
	$arrayAbsensi[$index_absensi]["TANGGAL"] = $absensi->getField("TANGGAL");
	$index_absensi++;
}
//print_r($arrayAbsensi);exit;
$tempAbsensi = $index_absensi;

for($check_index=0; $check_index<$tempAbsensi; $check_index++)
{
	$tanggal = $arrayAbsensi[$check_index]["TANGGAL"];
	//echo $tanggal."<br>";
	$set_delete= new SqlKonversi();
	$set_delete->deleteIdTable("ABSENSI", " AND TO_CHAR(TANGGAL, 'YYYYMMDD') = '".$tanggal."'");
	unset($set_delete);
	
	//get array satker
	$arrData="";
	$arrData= getAbsensi($tanggal);
	//echo $tanggal;print_r($arrData);//exit;
	
	if(empty($arrData)){}
	else
	{
		for($index_data=0; $index_data < count($arrData); $index_data++)
		{
			$tempKey	= $arrData[$index_data]["NIPBARU"];
			$tempDatang	= $arrData[$index_data]["DATANG"];
			$tempPulang	= $arrData[$index_data]["PULANG"];
			$tempMasuk	= $arrData[$index_data]["MASUK"];
			$tempKeluar	= $arrData[$index_data]["KELUAR"];
			
			$set_detil= new SqlKonversi();
			
			$statement= " AND NIP_BARU = '".$tempKey."' AND TO_CHAR(TANGGAL, 'YYYYMMDD') = '".$tanggal."'";
			$tempDataId= $set_detil->cariIdTable("NIP_BARU", "ABSENSI", $statement);
			//echo $set_detil->query;exit;
			
			//CariProgPusatExist
			if($tempDataId == "")
			{
				$set_data= new SqlKonversi();
				$set_data->setField("NIP_BARU", $tempKey);
				$set_data->setField("DATANG", $tempDatang);
				$set_data->setField("PULANG", $tempPulang);
				$set_data->setField("MASUK", $tempMasuk);
				$set_data->setField("KELUAR", $tempKeluar);
				$set_data->setField("TANGGAL", "TO_DATE('".$tanggal."', 'YYYYMMDD')");
				$set_data->setField("LAST_CREATE_USER", $userLogin->loginnamauser);
				$set_data->setField("LAST_CREATE_DATE", "NOW()");
				$set_data->insertAbsensi();
			}
			else
			{
				$set_data= new SqlKonversi();
				$set_data->setField("NIP_BARU", $tempKey);
				$set_data->setField("DATANG", $tempDatang);
				$set_data->setField("PULANG", $tempPulang);
				$set_data->setField("MASUK", $tempMasuk);
				$set_data->setField("KELUAR", $tempKeluar);
				$set_data->setField("TANGGAL", "TO_DATE('".$tanggal."', 'YYYYMMDD')");
				$set_data->setField("LAST_UPDATE_USER", $userLogin->loginnamauser);
				$set_data->setField("LAST_UPDATE_DATE", "NOW()");
				$set_data->updateAbsensi();
			}
			//echo $set_data->query;exit;
		}
	}
}
echo "1";
?>