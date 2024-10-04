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

if($reqTanggalAkhir=="")
{
	$reqTanggalAkhir = $reqTanggalAwal;
}
else if($reqTanggalAwal=="")
{
	$reqTanggalAwal = $reqTanggalAkhir;
}

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
	
	$dbhost= "localhost";
	$dbname= "adms_mjk_2016";
	$dbuser= "root";
	$dbpass= "root";
	
	$db_handle= mysql_connect($dbhost, $dbuser, $dbpass);
	$db_found = mysql_select_db($dbname, $db_handle);
	if (!$db_handle) {
		die('Could not connect: ' . mysql_error());
	}
	
	$sql = "
	Call prAbsenHarian('".$tanggal."');
	";
	//echo $sql;
	$result= mysql_query($sql);
	
	
	$arrData="";
	$index_array= 0;
	while($row = mysql_fetch_assoc($result)) 
	{
		if(strlen($row['nip'])=="1"){}
		else
		{
			$arrData[$index_array]["NIPBARU"] = $row['nip'];
			$arrData[$index_array]["DATANG"] = $row['datang'];
			$arrData[$index_array]["PULANG"] = $row['pulang'];
			$arrData[$index_array]["MASUK"] = $row['masuk'];
			$arrData[$index_array]["KELUAR"] = $row['keluar'];
			$index_array++;
		}
	}
	
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
	mysql_close($db_handle);
}
echo "1";
?>