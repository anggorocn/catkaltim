<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/RekapSehat.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");
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

if($reqTipeUjianId == "7")
{
	// $aColumns= array("NIP_BARU", "NAMA_PEGAWAI"
	// , "NILAI_W", "NILAI_F", "NILAI_K", "NILAI_Z", "NILAI_O", "NILAI_B", "NILAI_X", "NILAI_P", "NILAI_A", "NILAI_N"
	// , "NILAI_G", "NILAI_L", "NILAI_I", "NILAI_T", "NILAI_V", "NILAI_S", "NILAI_R", "NILAI_D", "NILAI_C", "NILAI_E");

	$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI"
	, "NILAI_G", "NILAI_L", "NILAI_I", "NILAI_T", "NILAI_V", "NILAI_S", "NILAI_R", "NILAI_D", "NILAI_C", "NILAI_E", "TOTAL_1"
	, "NILAI_N", "NILAI_A", "NILAI_P", "NILAI_X", "NILAI_B", "NILAI_O", "NILAI_Z", "NILAI_K", "NILAI_F", "NILAI_W", "TOTAL_2"
	, "TOTAL", "RATA_RATA");

	// $arrData= array("NIP", "Nama", "W", "F", "K", "Z", "O", "B", "X", "P", "A", "N", "G", "L", "I", "T", "V", "S", "R", "D", "C", "E");
	// $arrData= array("NIP", "Nama", "G", "L", "I", "T", "V", "S", "R", "D", "C", "E", "N", "A", "P", "X", "B", "O", "Z", "K", "F", "W");
	$arrData= array(
		"No Urut", "NIP", "Nama"
		, "G", "L", "I", "T", "V", "S", "R", "D", "C", "E", "TOTAL_1"
		, "N", "A", "P", "X", "B", "O", "Z", "K", "F", "W", "TOTAL_2"
		, "Total", "Rata-rata"
	);
}
elseif($reqTipeUjianId == "17")
{
    $aColumns= array("NIP_BARU", "NAMA_PEGAWAI"
	, "JUMLAH_1", "S_KETERANGAN_1", "JUMLAH_2", "S_KETERANGAN_2", "JUMLAH_3", "S_KETERANGAN_3"
	, "JUMLAH_4", "S_KETERANGAN_4", "JUMLAH_5", "S_KETERANGAN_5", "JUMLAH_6", "S_KETERANGAN_6"
	, "JUMLAH_7", "S_KETERANGAN_7", "JUMLAH_8", "S_KETERANGAN_8", "JUMLAH_9", "S_KETERANGAN_9"
	, "JUMLAH_10", "S_KETERANGAN_10", "JUMLAH_11", "S_KETERANGAN_11", "JUMLAH_12", "S_KETERANGAN_12"
	, "JUMLAH_13", "S_KETERANGAN_13", "JUMLAH_14", "S_KETERANGAN_14", "JUMLAH_15", "S_KETERANGAN_15"
	, "TOTAL_DATA", "CONS_DATA");

	$arrData= array(
        "Email", "Nama"
        , "Ach", "Def", "Ord", "Exh", "Aut", "Aff", "Int", "Suc", "Dom", "Aba", "Nur", "Chg", "End", "Het", "Agg"
        , "Total", "Cons"
    );

}
elseif($reqTipeUjianId == "16")
{
	$aColumns= array("NIP_BARU", "NAMA_PEGAWAI", "TOTAL", "RATA_RATA", "Y_MAX_DATA", "Y_MIN_DATA", "Y_MAX_DATA1", "Y_MIN_DATA1", "JUMLAH_BENAR", "JUMLAH_SALAH", "JUMLAH_TERLONCATI");
	$jumlahcolom= count($aColumns);
	for($colomx= 1; $colomx <= 50; $colomx ++)
	{
		$aColumns[$jumlahcolom]= "Y_DATA".$colomx;
		$jumlahcolom++;
		$aColumns[$jumlahcolom]= "Y_MIN_DATA".$colomx;
		$jumlahcolom++;
		$aColumns[$jumlahcolom]= "BARIS_JUMLAH_BENAR".$colomx;
		$jumlahcolom++;
		$aColumns[$jumlahcolom]= "BARIS_JUMLAH_SALAH".$colomx;
		$jumlahcolom++;
	}

    $arrData= array("Email", "Nama", "Total", "Rata-rata", "Titik Tertinggi", "Titik Terendah", "Titik Maksimal", "Titik Minimal", "Jumlah Benar", "Jumlah Salah", "Jumlah Terloncati", "Y1", "Y2", "Y3", "Y4", "Y5", "Y6", "Y7", "Y8", "Y9", "Y10", "Y11", "Y12", "Y13", "Y14", "Y15", "Y16", "Y17", "Y18", "Y19", "Y20", "Y21", "Y22", "Y23", "Y24", "Y25", "Y26", "Y27", "Y28", "Y29", "Y30", "Y31", "Y32", "Y33", "Y34", "Y35", "Y36", "Y37", "Y38", "Y39", "Y40", "Y41", "Y42", "Y43", "Y44", "Y45", "Y46", "Y47", "Y48", "Y49", "Y50");
}
elseif($reqTipeUjianId == "18")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI");
	$jumlahcolom= count($aColumns);

	$arrField= array("SE", "WA", "AN", "GE", "ME", "RA", "ZR", "FA", "WU", "JUMLAH");
	for($colomx= 0; $colomx < count($arrField); $colomx ++)
	{
		$aColumns[$jumlahcolom]= "RW_".$arrField[$colomx];
		$jumlahcolom++;
		$aColumns[$jumlahcolom]= "SW_".$arrField[$colomx];
		$jumlahcolom++;
	}
	$aColumns[$jumlahcolom]= "IQ";

	$arrData= array("ID", "No Urut", "NIP", "Nama", "SE", "WA", "AN", "GE", "ME", "RA", "ZR", "FA", "WU", "Jumlah", "IQ");
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "28")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI");
	$jumlahcolom= count($aColumns);

	for($colomx= 1; $colomx <= 20; $colomx ++)
	{
		$aColumns[$jumlahcolom]= "RW".$colomx;
		$jumlahcolom++;
	}

    $arrData= array("ID", "No Urut", "NIP", "Nama");
    $jumlahdata= count($arrData);
    for($colomx= 1; $colomx <= 20; $colomx ++)
    {
      $arrData[$jumlahdata]= $colomx;
      $jumlahdata++;
    }

}
elseif($reqTipeUjianId == "43")
{
	$aColumns= array("NIP_BARU", "NAMA_PEGAWAI", "TOTAL_KESALAHAN_1", "TOTAL_KESALAHAN_2", "TOTAL_KESALAHAN_3", "TOTAL_TDK_ISI_1", "TOTAL_TDK_ISI_2", "TOTAL_TDK_ISI_3", "PUNCAK_TERTINGGI", "LIST_PUNCAK_TERTINGGI", "PUNCAK_TERENDAH", "LIST_PUNCAK_TERENDAH", "KETELITIAN_RS", "KETELITIAN_SS", "KETELITIAN_KESIMPULAN", "KECEPATAN_RS", "KECEPATAN_SS", "KECEPATAN_KESIMPULAN");

	$arrData= array("Email", "Nama", "Range I", "Range II", "Range III", "Range I", "Range II", "Range III", "Puncak", "List", "Puncak", "List", "RS", "SS", "Kesimpulan", "RS", "SS", "Kesimpulan");
}
elseif($reqTipeUjianId == "1" || $reqTipeUjianId == "2")
{
	$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_BENAR_0101", "JUMLAH_BENAR_0102", "JUMLAH_BENAR_0103", "JUMLAH_BENAR_0104", "JUMLAH_BENAR", "NILAI_HASIL", "KESIMPULAN");

	$arrData= array("No Urut", "NIP", "Nama", "Skor Sub Tes 1", "Skor Sub Tes 2", "Skor Sub Tes 3", "Skor Sub Tes 4", "Raw Skor", "Nilai", "Kesimpulan");
}
elseif ($reqTipeUjianId == "66")
{
	$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "PEGAWAI_NAMA", "JUMLAH_SOAL", "JUMLAH_BENAR", "NILAI_HASIL");

	$arrData= array("No Urut", "Soal", "Jawaban");
}
elseif($reqTipeUjianId == "45")
{
	$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "PERSEN_AGREEABLENESS", "PERSEN_CONSCIENTIOUSNESS", "PERSEN_EXTRAVERSION", "PERSEN_NEUROTICISM", "PERSEN_OPENNESS");
    $arrData= array("No Urut", "NIP", "Nama", "Agreeableness", "Conscientiousness", "Extraversion", "Neuroticism", "Openness");
}
elseif($reqTipeUjianId == "4" || $reqTipeUjianId == "46" || $reqTipeUjianId == "50" || $reqTipeUjianId == "51" || $reqTipeUjianId == "52" || $reqTipeUjianId == "53" || $reqTipeUjianId == "54" || $reqTipeUjianId == "55" || $reqTipeUjianId == "56" || $reqTipeUjianId == "57" || $reqTipeUjianId == "58" || $reqTipeUjianId == "59"|| $reqTipeUjianId == "60" || $reqTipeUjianId == "61" || $reqTipeUjianId == "62" || $reqTipeUjianId == "63" || $reqTipeUjianId == "64" || $reqTipeUjianId == "65")
{
	$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_SOAL", "JUMLAH_BENAR");

	$arrData= array("No Urut", "NIP", "Nama", "Jumlah Soal", "Jumlah Benar");
}
elseif($reqTipeUjianId == "47")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_SOAL", "JUMLAH_BENAR", "IQ_KETERANGAN");
	$arrData= array("ID", "No Urut", "NIP", "Nama", "Jumlah Soal", "Jumlah Benar", "IQ Keterangan");
}
elseif($reqTipeUjianId == "48")
{
	$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "NILAI", "NILAI_KESIMPULAN");
	$arrData= array("No Urut", "NIP", "Nama", "Nilai", "Keterangan");
}
elseif($reqTipeUjianId == "49")
{
	// $aColumns= array("NOMOR", "PERTANYAAN", "JAWABAN");
	// $arrData= array("No", "Pertanyaan", "Jawaban");
		$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "HASIL");
		$arrData= array("NO", "NIP BARU", "NAMA PEGAWAI","HASIL");

}
elseif($reqTipeUjianId == "41")
{
	// $aColumns= array("NOMOR", "PERTANYAAN", "JAWABAN");
	// $arrData= array("No", "Pertanyaan", "Jawaban");
		$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "NILAI_I", "NILAI_E", "NILAI_S", "NILAI_N", "NILAI_T", "NILAI_F", "NILAI_J", "NILAI_P", "KONVERSI_INFO");
		$arrData= array("NO", "NIP BARU", "NAMA PEGAWAI","I","E","S","N","T","F","J","P", "TIPE KEPRIBADIAN" );

}
else
{
	$aColumns= array("NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_SOAL", "JUMLAH_BENAR", "NILAI_HASIL");

	$arrData= array("No Urut", "NIP", "Nama", "Jumlah Soal", "Jumlah Benar", "Nilai Hasil");
	// echo "dadada"; exit;
}


$sOrder= " ORDER BY NOMOR_URUT_GENERATE";
$sOrder= "";

$set = new RekapSehat();

if($reqTipeUjianId == "7")
{
	$statement = " AND B.JADWAL_TES_ID = ".$reqId;
	$statement .= " AND B.PEGAWAI_ID = ".$reqRowId;
	// $searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";
	$set->selectByParamsMonitoringPapiHasil(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "17")
{
	$statement = " AND B.JADWAL_TES_ID = ".$reqId;
	/*$statement .= " 
	AND EXISTS
	(
		SELECT 1
		FROM cat_pegawai.ujian_pegawai_".$reqId." XD
		WHERE XD.BANK_SOAL_PILIHAN_ID IS NOT NULL 
		AND B.PEGAWAI_ID = XD.PEGAWAI_ID AND B.UJIAN_ID = XD.UJIAN_ID
		GROUP BY PEGAWAI_ID, UJIAN_ID
	)";*/

	// $searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.EMAIL) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringEppsHasil(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringEppsHasil(array(), $reqId, $statement.$searchJson);

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringEppsHasil(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
}
elseif($reqTipeUjianId == "18")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;

	$allRecord = $set->getCountByParamsMonitoringIst(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringIst(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	$set->selectByParamsMonitoringIst(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "28")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;

	$allRecord = $set->getCountByParamsMonitoringPauli(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringPauli(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	$set->selectByParamsMonitoringPauli(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "16")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId;
	$statement .= " 
	AND EXISTS
	(
		SELECT 1
		FROM cat_pegawai.ujian_kraepelin_".$reqId." XD
		WHERE XD.NILAI IS NOT NULL
		AND B.PEGAWAI_ID = XD.PEGAWAI_ID AND B.UJIAN_ID = XD.UJIAN_ID
		GROUP BY PEGAWAI_ID, UJIAN_ID
	)";
	$set->selectByParamsMonitoringKraepelin(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "43")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringBaruKraepelin(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "41")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringMbtiNew(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "45")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringBigFive(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "47")
{
	 
	$statement.= " AND HSL.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND HSL.JADWAL_TES_ID = ".$reqId;
	$statementdetil.= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId;

	$set->selectByParamsMonitoringRekapWPTNew(array(), -1, -1, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "48")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId;
	$set->selectByParamsMonitoringKertih(array(), -1, -1, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "49")
{
	// $statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PEGAWAI_ID = ".$reqRowId;
	$statement= " AND B.JADWAL_TES_ID = ".$reqId;
	$statementdetil.= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId;
	// $set->selectByParamsMonitoringDataHolland(array(), -1, -1, $reqId, $statement.$searchJson, $statementdetil, $sOrder);

	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.EMAIL) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringHolland(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringHolland(array(), $reqId, $statement.$searchJson);

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringHolland(array(), -1, -1, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
	// echo $set->query;exit;
}
else
{
	if($reqTipeUjianId == 2)
	{
		$statement= "";
		$statementdetil.= " AND A.JADWAL_TES_ID = ".$reqId;
		$sOrder="order by NOMOR_URUT_GENERATE asc";
	
		$allRecord= $set->getCountByParamsMonitoringCfitHasilRekapB(array(), $reqId, $reqTipeUjianId, $statement, $statementdetil);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoringCfitHasilRekapB(array(), $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil);
		// echo $set->query;exit;
		$set->selectByParamsMonitoringCfitHasilRekapB(array(), -1, -1, $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil, $sOrder);
	}
	elseif($reqTipeUjianId == 1)
	{
		$statement= "";
		$statementdetil.= " AND A.JADWAL_TES_ID = ".$reqId;
		$sOrder="order by NOMOR_URUT_GENERATE asc";
		$allRecord= $set->getCountByParamsMonitoringCfitHasilRekapA(array(), $reqId, $reqTipeUjianId, $statement, $statementdetil);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoringCfitHasilRekapA(array(), $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil);
		$set->selectByParamsMonitoringCfitHasilRekapA(array(), -1, -1, $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil, $sOrder);
		// echo $set->query;exit;
	}
	elseif($reqTipeUjianId >= 70 && $reqTipeUjianId <= 74)
	{
		// echo "Dadada"; exit;
		// $sOrder= " ORDER BY A.NAMA ASC ";
		// $statementdetil.= " AND HSL.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND HSL.JADWAL_TES_ID = ".$reqId;
		$statement= "";
		$statementdetil= "";
		$allRecord= $set->getCountByParamsMonitoringRekapLain72(array(), $reqId, $statement, $statementdetil);

		if($reqTipeUjianId==70){
			$norma=",case 
			when count(b.ujian_pegawai_daftar_id)>=34 and count(b.ujian_pegawai_daftar_id)<=40 then '20'
			when count(b.ujian_pegawai_daftar_id)=33 then '19'
			when count(b.ujian_pegawai_daftar_id)>=31 and count(b.ujian_pegawai_daftar_id)<=32 then '18'
			when count(b.ujian_pegawai_daftar_id)>=29 and count(b.ujian_pegawai_daftar_id)<=30 then '17'
			when count(b.ujian_pegawai_daftar_id)>=27 and count(b.ujian_pegawai_daftar_id)<=28 then '16'
			when count(b.ujian_pegawai_daftar_id)>=25 and count(b.ujian_pegawai_daftar_id)<=26 then '15'
			when count(b.ujian_pegawai_daftar_id)=24 then '14'
			when count(b.ujian_pegawai_daftar_id)>=22 and count(b.ujian_pegawai_daftar_id)<=23 then '13'
			when count(b.ujian_pegawai_daftar_id)>=20 and count(b.ujian_pegawai_daftar_id)<=21 then '12'
			when count(b.ujian_pegawai_daftar_id)>=18 and count(b.ujian_pegawai_daftar_id)<=19 then '11'
			when count(b.ujian_pegawai_daftar_id)=17 then '10'
			when count(b.ujian_pegawai_daftar_id)>=15 and count(b.ujian_pegawai_daftar_id)<=16 then '9'
			when count(b.ujian_pegawai_daftar_id)>=13 and count(b.ujian_pegawai_daftar_id)<=14 then '8'
			when count(b.ujian_pegawai_daftar_id)>=11 and count(b.ujian_pegawai_daftar_id)<=12 then '7'
			when count(b.ujian_pegawai_daftar_id)>=9 and count(b.ujian_pegawai_daftar_id)<=10 then '6'
			when count(b.ujian_pegawai_daftar_id)=8 then '5'
			when count(b.ujian_pegawai_daftar_id)>=6 and count(b.ujian_pegawai_daftar_id)<=7 then '4'
			when count(b.ujian_pegawai_daftar_id)>=4 and count(b.ujian_pegawai_daftar_id)<=5 then '3'
			when count(b.ujian_pegawai_daftar_id)>=2 and count(b.ujian_pegawai_daftar_id)<=3 then '2'
			when count(b.ujian_pegawai_daftar_id)=1 then '1'
			when count(b.ujian_pegawai_daftar_id)=0 then '0'
		else '-' end NILAI_HASIL";
		}
		else if($reqTipeUjianId==71){
			$norma=",case 
			when count(b.ujian_pegawai_daftar_id)=20 then '16'
			when count(b.ujian_pegawai_daftar_id)=19 then '15'
			when count(b.ujian_pegawai_daftar_id)=18 then '14'
			when count(b.ujian_pegawai_daftar_id)=17 then '13'
			when count(b.ujian_pegawai_daftar_id)=16 then '12'
			when count(b.ujian_pegawai_daftar_id)=15 then '11'
			when count(b.ujian_pegawai_daftar_id)=14 then '10'
			when count(b.ujian_pegawai_daftar_id)=13 then '9'
			when count(b.ujian_pegawai_daftar_id)=12 then '7'
			when count(b.ujian_pegawai_daftar_id)=11 then '7'
			when count(b.ujian_pegawai_daftar_id)=10 then '6'
			when count(b.ujian_pegawai_daftar_id)=9 then '5'
			when count(b.ujian_pegawai_daftar_id)=8 then '4'
			when count(b.ujian_pegawai_daftar_id)=7 then '3'
			when count(b.ujian_pegawai_daftar_id)=6 then '2'
			when count(b.ujian_pegawai_daftar_id)=5 then '1'
			when count(b.ujian_pegawai_daftar_id)>=0 and count(b.ujian_pegawai_daftar_id)<=4 then '0'
		else '-' end NILAI_HASIL";
		}
		else if($reqTipeUjianId==72){
			$norma=",case 
			when count(b.ujian_pegawai_daftar_id)=20 then '19'
			when count(b.ujian_pegawai_daftar_id)=19 then '18'
			when count(b.ujian_pegawai_daftar_id)=18 then '17'
			when count(b.ujian_pegawai_daftar_id)=17 then '16'
			when count(b.ujian_pegawai_daftar_id)=16 then '15'
			when count(b.ujian_pegawai_daftar_id)=15 then '14'
			when count(b.ujian_pegawai_daftar_id)=14 then '13'
			when count(b.ujian_pegawai_daftar_id)=13 then '12'
			when count(b.ujian_pegawai_daftar_id)=12 then '11'
			when count(b.ujian_pegawai_daftar_id)=11 then '10'
			when count(b.ujian_pegawai_daftar_id)=10 then '9'
			when count(b.ujian_pegawai_daftar_id)=9 then '8'
			when count(b.ujian_pegawai_daftar_id)=8 then '7'
			when count(b.ujian_pegawai_daftar_id)=7 then '6'
			when count(b.ujian_pegawai_daftar_id)=6 then '5'
			when count(b.ujian_pegawai_daftar_id)=5 then '4'
			when count(b.ujian_pegawai_daftar_id)=4 then '3'
			when count(b.ujian_pegawai_daftar_id)=3 then '2'
			when count(b.ujian_pegawai_daftar_id)=2 then '1'
			when count(b.ujian_pegawai_daftar_id)=1 then '0'
		else '-' end NILAI_HASIL";
		}

		else if($reqTipeUjianId==73){
			$norma=",case 
			when count(b.ujian_pegawai_daftar_id)>=23 and count(b.ujian_pegawai_daftar_id)<=25 then '18'
			when count(b.ujian_pegawai_daftar_id)=22 then '17'
			when count(b.ujian_pegawai_daftar_id)>=20 and count(b.ujian_pegawai_daftar_id)<=21 then '16'
			when count(b.ujian_pegawai_daftar_id)=19 then '15'
			when count(b.ujian_pegawai_daftar_id)>=16 and count(b.ujian_pegawai_daftar_id)<=18 then '14'
			when count(b.ujian_pegawai_daftar_id)=15 then '13'
			when count(b.ujian_pegawai_daftar_id)>=13 and count(b.ujian_pegawai_daftar_id)<=14 then '12'
			when count(b.ujian_pegawai_daftar_id)=12 then '11'
			when count(b.ujian_pegawai_daftar_id)>=10 and count(b.ujian_pegawai_daftar_id)<=11 then '10'
			when count(b.ujian_pegawai_daftar_id)=9 then '9'
			when count(b.ujian_pegawai_daftar_id)=8 then '8'
			when count(b.ujian_pegawai_daftar_id)>=6 and count(b.ujian_pegawai_daftar_id)<=7 then '7'
			when count(b.ujian_pegawai_daftar_id)=5 then '6'
			when count(b.ujian_pegawai_daftar_id)>=3 and count(b.ujian_pegawai_daftar_id)<=4 then '5'
			when count(b.ujian_pegawai_daftar_id)=2 then '4'
			when count(b.ujian_pegawai_daftar_id)>=0 and count(b.ujian_pegawai_daftar_id)<=1 then '3'
		else '-' end NILAI_HASIL";
		}
		else if($reqTipeUjianId==74){
			$norma=",case 
			when count(b.ujian_pegawai_daftar_id)>=78 and count(b.ujian_pegawai_daftar_id)<=80 then '20'
			when count(b.ujian_pegawai_daftar_id)>=75 and count(b.ujian_pegawai_daftar_id)<=77 then '19'
			when count(b.ujian_pegawai_daftar_id)>=72 and count(b.ujian_pegawai_daftar_id)<=74 then '18'
			when count(b.ujian_pegawai_daftar_id)>=69 and count(b.ujian_pegawai_daftar_id)<=71 then '17'
			when count(b.ujian_pegawai_daftar_id)>=66 and count(b.ujian_pegawai_daftar_id)<=68 then '16'
			when count(b.ujian_pegawai_daftar_id)>=65 and count(b.ujian_pegawai_daftar_id)<=63 then '15'
			when count(b.ujian_pegawai_daftar_id)>=59 and count(b.ujian_pegawai_daftar_id)<=62 then '14'
			when count(b.ujian_pegawai_daftar_id)>=56 and count(b.ujian_pegawai_daftar_id)<=58 then '13'
			when count(b.ujian_pegawai_daftar_id)>=53 and count(b.ujian_pegawai_daftar_id)<=55 then '12'
			when count(b.ujian_pegawai_daftar_id)>=50 and count(b.ujian_pegawai_daftar_id)<=52 then '11'
			when count(b.ujian_pegawai_daftar_id)>=47 and count(b.ujian_pegawai_daftar_id)<=49 then '10'
			when count(b.ujian_pegawai_daftar_id)>=44 and count(b.ujian_pegawai_daftar_id)<=46 then '9'
			when count(b.ujian_pegawai_daftar_id)>=41 and count(b.ujian_pegawai_daftar_id)<=43 then '8'
			when count(b.ujian_pegawai_daftar_id)>=37 and count(b.ujian_pegawai_daftar_id)<=40 then '7'
			when count(b.ujian_pegawai_daftar_id)>=34 and count(b.ujian_pegawai_daftar_id)<=36 then '6'
			when count(b.ujian_pegawai_daftar_id)>=31 and count(b.ujian_pegawai_daftar_id)<=33 then '5'
			when count(b.ujian_pegawai_daftar_id)>=28 and count(b.ujian_pegawai_daftar_id)<=30 then '4'
			when count(b.ujian_pegawai_daftar_id)>=25 and count(b.ujian_pegawai_daftar_id)<=27 then '3'
			when count(b.ujian_pegawai_daftar_id)>=24 and count(b.ujian_pegawai_daftar_id)<=22 then '2'
			when count(b.ujian_pegawai_daftar_id)>=19 and count(b.ujian_pegawai_daftar_id)<=21 then '1'
			when count(b.ujian_pegawai_daftar_id)>=0 and count(b.ujian_pegawai_daftar_id)<=18 then '0'
		else '-' end NILAI_HASIL";
		}
		// echo $allRecord; exit;
		// if($_GET['sSearch'] == "")
		// 	$allRecordFilter = $allRecord;
		// else	
		// 	$allRecordFilter = $set->getCountByParamsMonitoringRekapLain72(array(), $reqId, $statement.$searchJson, $statementdetil);
		// echo $set->query;exit;
		$searchJson='';
		$sOrder='';
		if ($reqTipeUjianId==72){
			$set->selectByParamsMonitoringRekapLain72Khusus(array(), -1,-1, $reqId, $statement.$searchJson, $statementdetil, $sOrder,$norma, $reqTipeUjianId);
		}
		else{
			$set->selectByParamsMonitoringRekapLain72(array(), -1,-1, $reqId, $statement.$searchJson, $statementdetil, $sOrder,$norma, $reqTipeUjianId);
		}
		// echo $set->query;exit;
		// echo $set->query;exit;

	}
	else
	{
		// $statementdetil.= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND A.UJIAN_ID = ".$reqId;
		$sOrder= " ORDER BY A.NAMA ASC ";
		$statementdetil.= " AND HSL.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND HSL.JADWAL_TES_ID = ".$reqId;

		$set->selectByParamsMonitoringRekapLain(array(), -1, -1, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
	}


	// echo $set->query;exit;
}

// echo $set->query;exit;

if(empty($reqRowId))
	$tempNamaFile= $tempNamaTipe." Tanggal : ".$tempTanggalTesInfo.".xls";
else
{
	$p= new RekapSehat();
	$p->selectByParamsInfoPegawai(array(), -1,-1, " AND B.PEGAWAI_ID = ".$reqRowId);
	$p->firstRow();
	$infopegawainama= $p->getField("NAMA_PEGAWAI");
	unset($p);

	$tempNamaFile= $infopegawainama.".xls";
}
// echo $tempNamaFile;exit();

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"".$tempNamaFile."\"");
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<style>
	body, table{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif
	}
	th {
		text-align:center;
		font-weight: bold;
	}
	td {
		vertical-align: top;
  		text-align: left;
	}
	.str{
	  mso-number-format:"\@";/*force text*/
	}
	</style>
<table style="width:100%">
        <tr>
            <td colspan="12" style="font-size:13px ;font-weight:bold">Hasil <?=$tempNamaTipe?></td>

        </tr>
</table>
<br/>
		<?
		$border="1";
		if($reqTipeUjianId == "7")
			$border="0";
		?>

    	<table style="width:100%" border="<?=$border?>" cellspacing="0" cellpadding="0">
    		<?
    		if($reqTipeUjianId == "7" || $reqTipeUjianId == "40" || $reqTipeUjianId == "66"){}
    		else
    		{
    		?>
            <thead>
            	<?
            	if($reqTipeUjianId == "17")
    			{
    			?>
    			<tr>
			        <?
			        for($i=0; $i < count($arrData); $i++)
			        {
			            /*$width= "10";
			            if($i == 0)
			                $width= "100";
			            elseif($i == 1)
			                $width= "250";*/
			        ?>
			            <?
			            if($i > 1 && $i < 17)
			            {
			            ?>
			            <th style="text-align:center" colspan="2"><?=$arrData[$i]?></th>
			            <?
			            }
			            else
			            {
			            ?>
			            <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
			            <?
			            }
			            ?>
			        <?
			        }
			        ?>

			    </tr>
			    <tr>
			        <?
			        for($i=2; $i < count($arrData)-2; $i++)
			        {
			        ?>
			        <th style="text-align:center">S</th>
			        <th style="text-align:center">SS</th>
			        <?
			        }
			        ?>
			    </tr>
    			<?
    			}
    			elseif($reqTipeUjianId == "18")
			    {
			    ?>
			    <tr>
			        <?
			        for($i=0; $i < count($arrData); $i++)
			        {
			        ?>
			            <?
			            if($i > 3 && $i < 14)
			            {
			            ?>
			            <th style="text-align:center" colspan="2"><?=$arrData[$i]?></th>
			            <?
			            }
			            else
			            {
			            ?>
			            <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
			            <?
			            }
			            ?>
			        <?
			        }
			        ?>
			    </tr>
			    <tr>
			        <?
			        for($i=4; $i < count($arrData) - 1; $i++)
			        {
			        ?>
			        <th style="text-align:center">RW</th>
			        <th style="text-align:center">SE</th>
			        <?
			        }
			        ?>
			    </tr>
			    <?
			    }
    			elseif($reqTipeUjianId == "16")
    			{
    			?>
    			<tr>
			        <?
			        for($i=0; $i < count($arrData); $i++)
			        {
			            /*$width= "10";
			            if($i == 0)
			                $width= "100";
			            elseif($i == 1)
			                $width= "250";*/
			        ?>
			        	<?
			            if($i > 10)
			            {
			            ?>
			            <th style="text-align:center" colspan="4"><?=$arrData[$i]?></th>
			            <?
			            }
			            else
			            {
			            ?>
			            <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
			            <?
			            }
			            ?>
			        <?
			        }
			        ?>

			    </tr>
			    <tr>
			    	<?
			        for($i=11; $i < count($arrData); $i++)
			        {
			        ?>
			        <th style="text-align:center">Puncak</th>
        			<th style="text-align:center">Terendah</th>
			        <th style="text-align:center">Benar</th>
			        <th style="text-align:center">Salah</th>
			        <?
			        }
			        ?>
			    </tr>
    			<?
    			}
    			elseif($reqTipeUjianId == "43")
    			{
    			?>
    			<tr>
			        <?
			        for($i=0; $i < count($arrData); $i++)
			        {			            
			        ?>
			            <?
			            if($i == 0 || $i == 1)
			            {
			            ?>
			            	<th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
			            	
			            <?
			            }
			            elseif($i == 2)
			            {
			            ?>
			            	<th style="text-align:center" colspan="3">Jumlah Kesalahan</th>
			            <?
			            }
			            elseif($i == 5)
			            {
			            ?>
			            	<th style="text-align:center" colspan="3">Jumlah Tidak Diisi</th>
			            <?
			            }
			            elseif($i == 8)
			            {
			            ?>
			            	<th style="text-align:center" colspan="2">Puncak Tertinggi</th>
			            <?
			            }
			            elseif($i == 10)
			            {
			            ?>
			            	<th style="text-align:center" colspan="2">Puncak Terendah</th>
			            <?
			            }
			            elseif($i == 12)
			            {
			            ?>
			            	<th style="text-align:center" colspan="3">Ketelitian</th>
			            <?
			            }
			            elseif($i == 15)
			            {
			            ?>
			            	<th style="text-align:center" colspan="3">Kecepatan</th>
			            <?
			            } 
			            ?>
			        <?
			        }
			        ?>

			    </tr>
			     <tr>
			        <?
			        for($i=2; $i < count($arrData); $i++)
			        {
			        ?>
			        <th width="<?=$width?>px"><?=$arrData[$i]?></th>
			        <?
			        }
			        ?>
			    </tr>
				    
    			<?
    			}
    			elseif($reqTipeUjianId == "16")
    			{
    			?>
    			<tr>
			        <?
			        for($i=0; $i < count($arrData); $i++)
			        {
			            $width= "10";
			            if($i == 0)
			                $width= "100";
			            elseif($i == 1)
			                $width= "250";
			        ?>
			            <?
			            if($i == 2)
			            {
			            ?>
			            <th style="text-align:center" colspan="3">Jumlah Kesalahan</th>
			            <?
			        	}
			            elseif($i >= 3 && $i <= 4){}
			            elseif($i == 5)
			            {
			            ?>
			            <th style="text-align:center" colspan="3">Jumlah Tidak Diisi</th>
			            <?
			        	}
			            elseif($i >= 6 && $i <= 7){}
			            elseif($i == 8)
			            {
			            ?>
			            <th style="text-align:center" colspan="2">Puncak Tertinggi</th>
			            <?
			            }
			            elseif($i == 9){}
			            elseif($i == 10)
			            {
			            ?>
			            <th style="text-align:center" colspan="2">Puncak Terendah</th>
			            <?
			            }
			            elseif($i == 11){}
			            elseif($i == 12)
			            {
			            ?>
			            <th style="text-align:center" colspan="3">Ketelitian</th>
			            <?
			            }
			            elseif($i == 13 || $i == 14){}
			            elseif($i == 15)
			            {
			            ?>
			            <th style="text-align:center" colspan="3">Kecepatan</th>
			            <?
			            }
			            elseif($i == 16 || $i == 17){}
			            else
			            {
			            ?>
			            <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
			            <?
			            }
			            ?>
			        <?
			        }
			        ?>
			    </tr>
			    <tr>
			        <?
			        for($i=2; $i < count($arrData); $i++)
			        {
			        ?>
			        <th width="<?=$width?>px"><?=$arrData[$i]?></th>
			        <?
			        }
			        ?>
			    </tr>
			    <?
    			}
    			else
    			{
            	?>
                <tr>
                	<?
		            for($i=0; $i < count($arrData); $i++)
		            {
		            	/*$width= "10";
		            	if($i == 0)
		            		$width= "100";
		            	elseif($i == 1)
		            		$width= "250";*/
		            ?>
		            	<th width="<?=$width?>px"><?=$arrData[$i]?></th>
		            <?
		            }
		            ?>
                </tr>
                <?
            	}
                ?>
            </thead>
            <?
        	}
            ?>
            <tbody>
            <?
            if($reqTipeUjianId == "7")
            {
            	$set->firstRow();
            ?>
            	<!-- <tr>
            		<td style="font-size:13px">Nama</td>
            		<td style="font-size:13px">:</td>
            		<td style="font-size:13px" colspan="3"><?=$set->getField("NAMA_PEGAWAI")?></td>
            	</tr> -->
            <?
            	// echo $set->getField("NILAI_F")."--".$set->getField("INFO_F");exit();
            	$arrayhasil= array("A", "N", "G", "C", "D", "R", "T", "V", "W", "F", "L", "P", "I", "S", "B", "O", "X", "E", "K", "Z");
	            for($i=0; $i<count($arrayhasil); $i++)
	            {
	            	$infonilai= "NILAI_".$arrayhasil[$i];
	            	$infoketerangan= "INFO_".$arrayhasil[$i];
	            ?>
	            <tr>
	            	<td><?=$arrayhasil[$i]?></td>
	            	<td>=</td>
	            	<td><?=$set->getField($infonilai)?></td>
	            	<td>:</td>
	            	<td><?=$set->getField($infoketerangan)?></td>
	            </tr>
	            <tr>
	            	<td></td>
	            	<td></td>
	            	<td></td>
	            	<td></td>
	            	<td><br/></td>
	            </tr>
            <?
        		}
        	}
            else if($reqTipeUjianId == "40")
            {
            	$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PEGAWAI_ID = ".$reqRowId;
            	$set->selectByParamsMonitoringPf16(array(), -1, -1, $reqId, $statement.$searchJson, $sOrder);
            	$set->firstRow();
            	// echo $set->query;exit();
            ?>
            	<tr>
            		<td colspan="13" style="text-align: center;" >STANDARD TEN SCORE (STEN)</td>
            	</tr>
            	<tr>
            		<td rowspan="2" style="text-align: center;">Faktor</td>
            		<td rowspan="2" style="text-align: center;">Skor rendah, uraian singkat:</td>
            		<td colspan="2" style="text-align: center;" width="66">Sangat    Rendah</td>
            		<td colspan="2" style="text-align: center;" width="66">Rendah</td>
            		<td colspan="2" style="text-align: center;" width="66">Cukup</td>
            		<td colspan="2" style="text-align: center;" width="66">Baik</td>
            		<td colspan="2" style="text-align: center;" width="66">Sangat    Baik</td>
            		<td rowspan="2" style="text-align: center;">Skor tinggi, uraian singkat:</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">1</td>
            		<td style="text-align: center;">2</td>
            		<td style="text-align: center;">3</td>
            		<td style="text-align: center;">4</td>
            		<td style="text-align: center;">5</td>
            		<td style="text-align: center;">6</td>
            		<td style="text-align: center;">7</td>
            		<td style="text-align: center;">8</td>
            		<td style="text-align: center;">9</td>
            		<td style="text-align: center;">10</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">A</td>
            		<td>Berhati-hati,    tidak ramah, pendiam, suka menyendiri, kritis, bersikeras, gigih.</td>
            		<!-- √ -->
            		<?
            		$checkvalue= $set->getField("NILAI_A");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Ramah    tamah, lembut hati, tidak suka repot-repot, ikut ambil bagian,    berpartisipasi.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">B</td>
            		<td>Bodoh,    inteligensi rendah, kapasitas mental skolastik rendah.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_B");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Pandai,    inteligensi tinggi, kapasitas mental skolastik tinggi.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">C</td>
            		<td>Dipengaruhi    oleh perasaan, emosi kurang mantap, mudah meledak, ego lemah.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_C");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Emosi    mantap, matang, menghadapi realitas, tenang, kekuatan ego tinggi.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">E</td>
            		<td>Rendah    hati, berwatak halus, mudah dituntun, jinak, patuh, pasrah, suka menolong.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_E");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Ketegangan    sikap, agresif, suka bersaing, keras hati, teguh pendiriannya, dominan.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">F</td>
            		<td>Seadanya,    sederhana, pendiam, serius, tenang, tidak bergelora.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_F");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Tidak    kenal susah, suka bersenang-senang, antusias, menggelora.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">G</td>
            		<td>Bijaksana,    mengabaikan aturan-aturan, superego lemah.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_G");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Teliti,    gigih, tekun, bermoral, tenang, serius, superego kuat.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">H</td>
            		<td>Pemalu,    takut-takut, peka terhadap ancaman-ancaman.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_H");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Suka    bertualang, berani, tidak malu-malu, secara sosial berani, tegas, hebat.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">I</td>
            		<td>Keras    hati, percaya diri, realistik.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_I");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Lembut    hati, peka, dependen, terlalu dilindungi.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">L</td>
            		<td>Menaruh    kepercayaan pada orang lain, menerima semua keadaan.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_L");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Memiliki    prasangka pada orang lain, sukar untuk bertindak bodoh.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">M</td>
            		<td>Praktikal,    berkenan pada hal-hal yang sederhana, biasa dan bersahaja.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_M");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Imajinatif,    hidup bebas (Bohemian), pelupa, suka melamun, linglung.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">N</td>
            		<td>Jujur,    berterus terang, blak-blakan, rendah hati, ikhlas, janggal, kikuk.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_N");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Lihai,    cerdik, halus budi bahasanya, memiliki kesadaran sosial.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">O</td>
            		<td>Yakin    akan dirinya, tenang, aman, puas dengan diri sendiri, tenteram.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_O");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Kuatir,    gelisah, menyalahkan diri sendiri, tidak aman, cemas, memiliki kesukaran.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">Q1</td>
            		<td>Konservatif,    kuno, tradisional.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_Q1");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Liberal,    suka akan hal-hal baru, berpikir bebas, berpikir radikal.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">Q2</td>
            		<td>Tergantung    pada kelompok, pengikut, taat pada kelompok.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_Q2");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Kecukupan    diri, banyak akal, mengambil keputusan sendiri.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">Q3</td>
            		<td>Lalai,    lemah, membolehkan, sembrono, kelemahan integrasi dari self-sentiment</td>
            		<?
            		$checkvalue= $set->getField("NILAI_Q3");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Bisa    mengendalikan diri, suka mengikuti aturan, kompulsif.</td>
            	</tr>
            	<tr>
            		<td style="text-align: center;">Q4</td>
            		<td>Santai,    tenang, lamban, tidak frustrasi, penyabar, ketegangan energi rendah.</td>
            		<?
            		$checkvalue= $set->getField("NILAI_Q4");
            		for($i=1; $i<=10; $i++)
            		{
            			$selectedcheck= "";
            			if($checkvalue == $i)
            				$selectedcheck= "x";
            		?>
            		<td style="text-align: center;"><?=$selectedcheck?></td>
            		<?
            		}
            		?>
            		<td>Tegang,    mudah frustrasi, mudah terangsang, lelah, ketegangan energi tinggi.</td>
            	</tr>
            <?
        	}
        	else if($reqTipeUjianId == "66")
            {
            	$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PEGAWAI_ID = ".$reqRowId;
            	$set->selectByParamsDataMmpi(array(), -1, -1, $reqId,$statement);
            	// echo $set->query;exit();
            ?>

             	<?
				$no = 1;
				// echo 'asdas';exit;
                while($set->nextRow())
                {
                	$infotanda= $set->getField("INFO_TANDA");
                	if($infotanda == "1")
                		$infotanda= "Plus (+)";
                	else
                		$infotanda= "Negatip (-)";
				?>
	            	<tr>
	            		<td style="text-align: center;"><?=$no?></td>
	            		<td style="text-align: left;"><?=$set->getField("PERTANYAAN")?></td>
	            		<td style="text-align: center;"><?=$infotanda?></td>
	            	</tr>
            	<?
            	$no++;
            	}
            	?>
            
            <?
        	}
        	else if($reqTipeUjianId == "49")
            {             
            ?>
             	<?
				$no = 1;  
                while($set->nextRow())
                {                 
				?>
	            	<tr>
	            		<td style="text-align: center;"><?=$no?></td>
	            		<td class="str" style="text-align: left;"><?=$set->getField("NIP_BARU")?></td>
	            		<td style="text-align: left;"><?=$set->getField("NAMA_PEGAWAI")?></td>
	            		<td style="text-align: left;"><?=$set->getField("HASIL")?></td> 
	            	</tr>
            	<?
            	$no++;
            	}
            	?>
            
            <?
        	}
        	else if($reqTipeUjianId == "41")
            {             
            ?>
             	<?
				$no = 1;  
                while($set->nextRow())
                {                 
				?>
	            	<tr>
	            		<td style="text-align: center;"><?=$no?></td>
	            		<td class="str" style="text-align: left;"><?=$set->getField("NIP_BARU")?></td>
	            		<td style="text-align: left;"><?=$set->getField("NAMA_PEGAWAI")?></td>
	            		<td style="text-align: left;"><?=$set->getField("NILAI_I")?></td> 
	            		<td style="text-align: left;"><?=$set->getField("NILAI_E")?></td> 
	            		<td style="text-align: left;"><?=$set->getField("NILAI_S")?></td> 
	            		<td style="text-align: left;"><?=$set->getField("NILAI_N")?></td> 
	            		<td style="text-align: left;"><?=$set->getField("NILAI_T")?></td> 
	            		<td style="text-align: left;"><?=$set->getField("NILAI_F")?></td> 
	            		<td style="text-align: left;"><?=$set->getField("NILAI_J")?></td> 
	            		<td style="text-align: left;"><?=$set->getField("NILAI_P")?></td> 
	            		<td style="text-align: left;"><?=$set->getField("KONVERSI_INFO")?></td>
	            	</tr>
            	<?
            	$no++;
            	}
            	?>
            
            <?
        	}
        	
        	else
        	{
            ?>
                <?

				$no = 1;
		// echo $set->query;exit;
				// var_dump($set);exit;

                while($set->nextRow())
                {
				?>
                	<!-- <tr style="height:120px"> -->
                	<tr>
                		<?
                		$arrTotal= "";
                		for ( $i=0 ; $i<count($aColumns) ; $i++ )
                		{
                			if($aColumns[$i] == "URAIAN")
							{
								$tempValueData= "WORK DIRECTION<br/>".
								$set->getField("INFO_A")."<br/>".$set->getField("INFO_N")."<br/>".$set->getField("INFO_G")."<br/>"
								."<br/>WORK STYLE<br/>".
								$set->getField("INFO_C")."<br/>".$set->getField("INFO_D")."<br/>".$set->getField("INFO_R")."<br/>"
								."<br/>ACTIVITY<br/>".
								$set->getField("INFO_T")."<br/>".$set->getField("INFO_V")."<br/>"
								."<br/>FOLLOWERSHIP<br/>".
								$set->getField("INFO_W")."<br/>".$set->getField("INFO_F")."<br/>"
								."<br/>LEADERSHIP<br/>".
								$set->getField("INFO_L")."<br/>".$set->getField("INFO_P")."<br/>".$set->getField("INFO_I")."<br/>"
								."<br/>SOCIAL NATURE<br/>".
								$set->getField("INFO_S")."<br/>".$set->getField("INFO_B")."<br/>".$set->getField("INFO_O")."<br/>".$set->getField("INFO_X")."<br/>"
								."<br/>TEMPERAMENT<br/>".
								$set->getField("INFO_E")."<br/>".$set->getField("INFO_K")."<br/>".$set->getField("INFO_Z")."<br/>";
							}
							else if($aColumns[$i] == "NILAI_EPPS")
							{
								// $arrTotal[$]
								for($xdata= ($i - 3); $xdata <= 15; $xdata ++)
								{

									$tempValueData= $set->getField("R_".$xdata)+$set->getField("C_".$xdata);
								}
							}
							else
								$tempValueData=$set->getField($aColumns[$i]);
                		?>
                        	<td class="str"><?=$tempValueData?></td>
                        <?
                    	}
                        ?>
                    </tr>
				<?
					$no++;
                }
                ?>

                <?
                // kalau epps tambahi total dan rata2
                if($reqTipeUjianId == "17")
                {
                ?>
                <?
                }
                ?>
            <?
        	}
            ?>
            </tbody>
        </table>
</body>
</html>
