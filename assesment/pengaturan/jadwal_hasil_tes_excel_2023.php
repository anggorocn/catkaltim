<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/RekapCetak.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

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
$urlLink= $data->urlConfig->main->urlLink;
// echo $urlLink;exit;

$sOrder= " ORDER BY NOMOR_URUT_GENERATE";
$sOrder= "";

$set = new RekapCetak();

if($reqTipeUjianId == "7")
{
	$statement = " AND B.UJIAN_ID = ".$reqId;
	// $statement.= " AND B.PEGAWAI_ID = ".$reqRowId;
	$statement.= " AND COALESCE(HSL.JUMLAH_TOTAL,0) = 90";
	$set->selectByParamsMonitoringPapiHasil(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// $set->selectByParamsMonitoringPapiHasil(array(), 2, 0, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "17")
{
	$statement = " AND B.UJIAN_ID = ".$reqId;
	$statement .= " 
	AND EXISTS
	(
		SELECT 1
		FROM cat_rekrutmen_pegawai.ujian_pegawai_".$reqId." XD
		WHERE XD.BANK_SOAL_PILIHAN_ID IS NOT NULL 
		AND B.PEGAWAI_ID = XD.PEGAWAI_ID AND B.UJIAN_ID = XD.UJIAN_ID
		GROUP BY PEGAWAI_ID, UJIAN_ID
	)";
	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringEppsHasil(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
}
elseif($reqTipeUjianId == "16")
{
	$statement= " AND B.UJIAN_ID = ".$reqId;
	$statement .= " 
	AND EXISTS
	(
		SELECT 1
		FROM cat_rekrutmen_pegawai.ujian_kraepelin_".$reqId." XD
		WHERE XD.NILAI IS NOT NULL
		AND B.PEGAWAI_ID = XD.PEGAWAI_ID AND B.UJIAN_ID = XD.UJIAN_ID
		GROUP BY PEGAWAI_ID, UJIAN_ID
	)";
	$set->selectByParamsMonitoringKraepelin(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "43")
{
	$statement= " AND B.UJIAN_ID = ".$reqId." ORDER BY nip_baru ASC";
	$set->selectByParamsMonitoringBaruKraepelin(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "40")
{
	$statement= " AND A.UJIAN_ID = ".$reqId." ORDER BY nip_baru ASC";
	$set->selectByParamsMonitoringPf16(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// $set->selectByParamsMonitoringPf16(array(), 2, 0, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "42")
{
	$statement= " AND r.jadwal_tes_id = ".$reqId." ORDER BY nip_baru ASC";
	$set->selectByParamsMonitoringDisc(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "66")
{
	$statement= " AND A.UJIAN_ID = ".$reqId." ORDER BY nip_baru ASC";
	$set->selectByParamsRekapMmpi(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// $set->selectByParamsRekapMmpi(array(), 2, 0, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
else
{
	if($reqTipeUjianId == 2)
	{
		$statementdetil.= " AND A.UJIAN_ID = ".$reqId;
		$sOrder= " ORDER BY nip_baru ASC";
		$set->selectByParamsMonitoringCfitHasilRekapB(array(), -1, -1, $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil, $sOrder);
		// echo $set->query;exit;
	}
	elseif($reqTipeUjianId == 1)
	{
		$statementdetil.= " AND A.UJIAN_ID = ".$reqId;
		$sOrder= " ORDER BY nip_baru ASC";
		$set->selectByParamsMonitoringCfitHasilRekapA(array(), -1, -1, $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil, $sOrder);
		// echo $set->query;exit;
	}
	else
	{
		$statementdetil.= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND A.UJIAN_ID = ".$reqId;

		$set->selectByParamsMonitoringCfitHasilRekap(array(), -1, -1, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
	}
	// echo $set->query;exit;
}
$data_files_to_zip= [];
$index_data_baru= 0;
while ($set->nextRow())
{
	$reqRowId= $set->getField("PEGAWAI_ID");
	if($reqTipeUjianId == 42)
	{
		$html= file_get_contents($urlLink."pengaturan/jadwal_hasil_tes_disk_excel.php?reqId=".$reqId."&reqTipeUjianId=".$reqTipeUjianId."&reqRowId=".$reqRowId."&reqGetData=1");
	}
	else
		$html= file_get_contents($urlLink."pengaturan/jadwal_hasil_tes_excel.php?reqId=".$reqId."&reqTipeUjianId=".$reqTipeUjianId."&reqRowId=".$reqRowId."&reqGetData=1");


	if($reqTipeUjianId == 42)
	{
		$filelokasiformat= $html;
	echo $html; exit;
		
	}
	else
	{
		// $p= new Rekap();
		// $p->selectByParamsInfoPegawai(array(), -1,-1, " AND B.PEGAWAI_ID = ".$reqRowId." AND B.UJIAN_ID = ".$reqId);
		// $p->firstRow();
		$infopegawainama= $set->getField("NAMA_PEGAWAI");
		$infonopeserta= $set->getField("nip_baru");
		$tempTanggalTesInfo= $tempTanggalTesInfo;
		// echo $p->query; exit;
		// unset($p);

		$filelokasiformat= $direktoriFile.$tempNamaTipe." ".$tempTanggalTesInfo." ".$infonopeserta." (".$infopegawainama.").xls";
		// unset($p);

		// $filelokasiformat= $direktoriFile.$infopegawainama.".xls";
		file_put_contents($filelokasiformat, $html);
	}

	$data_files_to_zip[$index_data_baru]= $filelokasiformat;
    $index_data_baru++;
	// exit;
}

// print_r($index_data_baru);exit;

if($index_data_baru > 0)
{
	$filelokasiformat= $tempNamaTipe.".zip";
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