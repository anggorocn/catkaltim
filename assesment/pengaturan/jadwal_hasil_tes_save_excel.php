<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/RekapCetak.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");

// if ($userLogin->checkUserLogin()) 
// { 
// 	$userLogin->retrieveUserInfo();
// }

$reqId= httpFilterGet("reqId");
$reqLinkFile= httpFilterGet("reqLinkFile");
// $reqRowId= httpFilterGet("reqRowId");
// echo $reqRowId;exit;
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");

$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
// echo $set->query;exit;
$tempTanggalTesInfo= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
// echo "sasasas";exit;

$statement= " AND TIPE_UJIAN_ID = ".$reqTipeUjianId;
$set= new TipeUjian();
$set->selectByParams(array(), -1,-1, $statement);
// echo $set->query;exit;
$set->firstRow();
$tempNamaTipe= $set->getField("TIPE");
unset($set);

$buatfolderzip= $reqId."-".$reqTipeUjianId;
$direktoriFile= "../uploadszip/".$buatfolderzip."/";

if(file_exists($direktoriFile)){}
else
{
	makedirs($direktoriFile);
}

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// $urlLink= $data->urlConfig->main->urlLink;
$urlLink='https://simace.kaltimbkd.info/assesment/';
// echo $urlLink;exit;
// $sOrder= "order by JA.NOMOR_URUT asc";
$set = new RekapCetak();
    		$arrCheck= array(7, 40, 42, 43, 66);

if($reqTipeUjianId == "7")
{
	$statement = " AND B.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringPapiHasil(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
else if($reqTipeUjianId == "40")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringPf16(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
else if($reqTipeUjianId == "42")
{
	$statement= " AND r.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringDisc(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
else if($reqTipeUjianId == "43")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringBaruKraepelin(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
else
{
	$statementdetil.= " AND HSL.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND HSL.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringRekapLain(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
}

$data_files_to_zip= [];
$index_data_baru= 0;
while ($set->nextRow())
{
	$reqRowId= $set->getField("PEGAWAI_ID");
	if($reqTipeUjianId == 7||$reqTipeUjianId == 40||$reqTipeUjianId == 66)
	{
		$html= file_get_contents($urlLink."pengaturan/jadwal_hasil_tes_excel.php?reqId=".$reqId."&reqTipeUjianId=".$reqTipeUjianId."&reqRowId=".$reqRowId);
	}
	else if($reqTipeUjianId == 42)
	{
		$html= file_get_contents($urlLink."pengaturan/jadwal_hasil_tes_disk_excel.php?reqId=".$reqId."&reqTipeUjianId=".$reqTipeUjianId."&reqRowId=".$reqRowId);
	}
	else
	{
		$html= file_get_contents($urlLink."pengaturan/jadwal_hasil_tes_excel.php?reqId=".$reqId."&reqTipeUjianId=".$reqTipeUjianId);
	}
	// echo $urlLink."pengaturan/jadwal_hasil_tes_disk_excel.php?reqId=".$reqId."&reqTipeUjianId=".$reqTipeUjianId."&reqRowId=".$reqRowId;exit;

	$infopegawainama= $set->getField("NAMA_PEGAWAI");
	$infonopeserta= $set->getField("nip_baru");
	$tempTanggalTesInfo= $tempTanggalTesInfo;
	$filelokasiformat= $direktoriFile.$infopegawainama." (".$infonopeserta.").xls";

	file_put_contents($filelokasiformat, $html);


	$data_files_to_zip[$index_data_baru]= $filelokasiformat;
    $index_data_baru++;
	// exit;
}

// print_r($set);exit;

if($index_data_baru > 0)
{
	$filelokasiformat= $tempNamaTipe." ".$tempTanggalTesInfo.".zip";
	$setLokasiZip= $direktoriFile.str_replace(" ", "_", str_replace("/","_", $filelokasiformat));
	// echo $setLokasiZip;exit;
	if(file_exists($setLokasiZip))
	{
		unlink($setLokasiZip);
	}
	$result = create_zip($data_files_to_zip,$setLokasiZip);
	deleteFileZip($data_files_to_zip);

	// echo json_encode($filelokasiformat);
	echo json_encode($setLokasiZip);
}


?>