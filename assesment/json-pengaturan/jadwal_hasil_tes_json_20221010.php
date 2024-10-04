<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base/RekapSehat.php");

$set = new RekapSehat();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

if($reqTipeUjianId == "7")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI"
	, "NILAI_G", "NILAI_L", "NILAI_I", "NILAI_T", "NILAI_V", "NILAI_S", "NILAI_R", "NILAI_D", "NILAI_C", "NILAI_E", "TOTAL_1"
	, "NILAI_N", "NILAI_A", "NILAI_P", "NILAI_X", "NILAI_B", "NILAI_O", "NILAI_Z", "NILAI_K", "NILAI_F", "NILAI_W", "TOTAL_2"
	, "TOTAL", "RATA_RATA");
	$aColumnsAlias = array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI"
	, "NILAI_G", "NILAI_L", "NILAI_I", "NILAI_T", "NILAI_V", "NILAI_S", "NILAI_R", "NILAI_D", "NILAI_C", "NILAI_E", "TOTAL_1"
	, "NILAI_N", "NILAI_A", "NILAI_P", "NILAI_X", "NILAI_B", "NILAI_O", "NILAI_Z", "NILAI_K", "NILAI_F", "NILAI_W", "TOTAL_2"
	, "TOTAL", "RATA_RATA");
}
elseif($reqTipeUjianId == "17")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI"
	, "JUMLAH_1", "S_KETERANGAN_1", "JUMLAH_2", "S_KETERANGAN_2", "JUMLAH_3", "S_KETERANGAN_3"
	, "JUMLAH_4", "S_KETERANGAN_4", "JUMLAH_5", "S_KETERANGAN_5", "JUMLAH_6", "S_KETERANGAN_6"
	, "JUMLAH_7", "S_KETERANGAN_7", "JUMLAH_8", "S_KETERANGAN_8", "JUMLAH_9", "S_KETERANGAN_9"
	, "JUMLAH_10", "S_KETERANGAN_10", "JUMLAH_11", "S_KETERANGAN_11", "JUMLAH_12", "S_KETERANGAN_12"
	, "JUMLAH_13", "S_KETERANGAN_13", "JUMLAH_14", "S_KETERANGAN_14", "JUMLAH_15", "S_KETERANGAN_15"
	, "TOTAL_DATA", "CONS_DATA");
	$aColumnsAlias = array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI"
	, "JUMLAH_1", "S_KETERANGAN_1", "JUMLAH_2", "S_KETERANGAN_2", "JUMLAH_3", "S_KETERANGAN_3"
	, "JUMLAH_4", "S_KETERANGAN_4", "JUMLAH_5", "S_KETERANGAN_5", "JUMLAH_6", "S_KETERANGAN_6"
	, "JUMLAH_7", "S_KETERANGAN_7", "JUMLAH_8", "S_KETERANGAN_8", "JUMLAH_9", "S_KETERANGAN_9"
	, "JUMLAH_10", "S_KETERANGAN_10", "JUMLAH_11", "S_KETERANGAN_11", "JUMLAH_12", "S_KETERANGAN_12"
	, "JUMLAH_13", "S_KETERANGAN_13", "JUMLAH_14", "S_KETERANGAN_14", "JUMLAH_15", "S_KETERANGAN_15"
	, "TOTAL_DATA", "CONS_DATA");

}
elseif($reqTipeUjianId == "16")
{
	$aColumns= array("UJIAN_PEGAWAI_DAFTAR_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "TOTAL", "RATA_RATA", "Y_MAX_DATA", "Y_MIN_DATA", "Y_MAX_DATA1", "Y_MIN_DATA1", "JUMLAH_BENAR", "JUMLAH_SALAH", "JUMLAH_TERLONCATI");
	$jumlahcolom= count($aColumns);
	for($colomx= 1; $colomx <= 50; $colomx ++)
	{
		$aColumns[$jumlahcolom]= "Y_DATA".$colomx;
		$jumlahcolom++;
	}
	$aColumns[$jumlahcolom]= "PEGAWAI_ID";

	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "43")
{
	$aColumns= array("UJIAN_PEGAWAI_DAFTAR_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "TOTAL_KESALAHAN_1", "TOTAL_KESALAHAN_2", "TOTAL_KESALAHAN_3", "TOTAL_TDK_ISI_1", "TOTAL_TDK_ISI_2", "TOTAL_TDK_ISI_3", "PUNCAK_TERTINGGI", "LIST_PUNCAK_TERTINGGI", "PUNCAK_TERENDAH", "LIST_PUNCAK_TERENDAH", "KETELITIAN_RS", "KETELITIAN_SS", "KETELITIAN_KESIMPULAN", "KECEPATAN_RS", "KECEPATAN_SS", "KECEPATAN_KESIMPULAN", "PEGAWAI_ID");

	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
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

	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "28")
{
	// pauli
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI");
	$jumlahcolom= count($aColumns);

	for($colomx= 1; $colomx <= 20; $colomx ++)
	{
		$aColumns[$jumlahcolom]= "RW".$colomx;
		$jumlahcolom++;
	}

	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "40")
{
	// 16pf
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI");
	$jumlahcolom= count($aColumns);

	$arrField= array("MD", "A", "B", "C", "E", "F", "G", "H", "I", "L", "M", "N", "O", "Q1", "Q2", "Q3", "Q4");
	for($colomx= 0; $colomx < count($arrField); $colomx ++)
	{
		$aColumns[$jumlahcolom]= "NILAI_".$arrField[$colomx];
		$jumlahcolom++;
	}

	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "66")
{
	// mmpi
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "PLUS", "NEGATIP");
	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "41")
{
	// mbti
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "NILAI_I", "NILAI_E", "NILAI_S", "NILAI_N", "NILAI_T", "NILAI_F", "NILAI_J", "NILAI_P", "KONVERSI_INFO");
	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "42")
{
	// disc
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "D_1", "I_1", "S_1", "C_1", "X_1", "D_2", "I_2", "S_2", "C_2", "X_2", "D_3", "I_3", "S_3", "C_3");

	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "45")
{
	// big five
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "PERSEN_AGREEABLENESS", "PERSEN_CONSCIENTIOUSNESS", "PERSEN_EXTRAVERSION", "PERSEN_NEUROTICISM", "PERSEN_OPENNESS");

	$aColumnsAlias= "";
	$aColumnsAlias= $aColumns;
	// print_r($aColumnsAlias);exit();
}
elseif($reqTipeUjianId == "1" || $reqTipeUjianId == "2")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_BENAR_0101", "JUMLAH_BENAR_0102", "JUMLAH_BENAR_0103", "JUMLAH_BENAR_0104", "JUMLAH_BENAR", "NILAI_HASIL");
	$aColumnsAlias= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_BENAR_0101", "JUMLAH_BENAR_0102", "JUMLAH_BENAR_0103", "JUMLAH_BENAR_0104", "JUMLAH_BENAR", "NILAI_HASIL");
}
elseif($reqTipeUjianId == "4" || $reqTipeUjianId == "46" || $reqTipeUjianId == "50" || $reqTipeUjianId == "51" || $reqTipeUjianId == "52" || $reqTipeUjianId == "53" || $reqTipeUjianId == "54" || $reqTipeUjianId == "55" || $reqTipeUjianId == "56" || $reqTipeUjianId == "57" || $reqTipeUjianId == "58" || $reqTipeUjianId == "59"|| $reqTipeUjianId == "60" || $reqTipeUjianId == "61" || $reqTipeUjianId == "62" || $reqTipeUjianId == "63" || $reqTipeUjianId == "64" || $reqTipeUjianId == "65")//ENGLISH DAN LAIN
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_SOAL", "JUMLAH_BENAR");
	$aColumnsAlias= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_SOAL", "JUMLAH_BENAR");
}
elseif($reqTipeUjianId == "47")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_SOAL", "JUMLAH_BENAR", "IQ_KETERANGAN");
	$aColumnsAlias= $aColumns;
}
elseif($reqTipeUjianId == "48")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "NILAI", "NILAI_KESIMPULAN");
	$aColumnsAlias= $aColumns;
}
elseif($reqTipeUjianId == "49")
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "NILAI_R", "NILAI_I", "NILAI_A", "NILAI_S", "NILAI_E", "NILAI_C", "HASIL");
	$aColumnsAlias = $aColumns;
}
else
{
	$aColumns= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_SOAL", "JUMLAH_BENAR", "NILAI_HASIL");
	$aColumnsAlias= array("PEGAWAI_ID", "NOMOR_URUT_GENERATE", "NIP_BARU", "NAMA_PEGAWAI", "JUMLAH_SOAL", "JUMLAH_BENAR", "NILAI_HASIL");
}
// print_r($aColumns);exit();

/*
 * Ordering
 */
  
 
if ( isset( $_GET['iSortCol_0'] ) )
{
	$sOrder = " ORDER BY ";
	 
	//Go over all sorting cols
	for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
	{
		//If need to sort by current col
		if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
		{
			//Add to the order by clause
			$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
			 
			//Determine if it is sorted asc or desc
			if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
			{
				$sOrder .=" asc, ";
			}else
			{
				$sOrder .=" desc, ";
			}
		}
	}
	
	 
	//Remove the last space / comma
	$sOrder = substr_replace( $sOrder, "", -2 );
	
	//Check if there is an order by clause
	if ( trim($sOrder) == "ORDER BY FORMULA_ID asc" )
	{
		/*
		* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
		* If there is no order by clause there might be bugs in table display.
		* No order by clause means that the db is not responsible for the data ordering,
		* which means that the same row can be displayed in two pages - while
		* another row will not be displayed at all.
		*/
		$sOrder = " ORDER BY TAHUN ASC";
		 
	}
}
 
 
/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables.
 */
$sWhere = "";
$nWhereGenearalCount = 0;
if (isset($_GET['sSearch']))
{
	$sWhereGenearal = $_GET['sSearch'];
}
else
{
	$sWhereGenearal = '';
}

if ( $_GET['sSearch'] != "" )
{
	//Set a default where clause in order for the where clause not to fail
	//in cases where there are no searchable cols at all.
	$sWhere = " AND (";
	for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
	{
		//If current col has a search param
		if ( $_GET['bSearchable_'.$i] == "true" )
		{
			//Add the search to the where clause
			$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
			$nWhereGenearalCount += 1;
		}
	}
	$sWhere = substr_replace( $sWhere, "", -3 );
	$sWhere .= ')';
}
 
/* Individual column filtering */
$sWhereSpecificArray = array();
$sWhereSpecificArrayCount = 0;
for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
{
	if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
	{
		//If there was no where clause
		if ( $sWhere == "" )
		{
			$sWhere = "AND ";
		}
		else
		{
			$sWhere .= " AND ";
		}
		 
		//Add the clause of the specific col to the where clause
		$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
		 
		//Inc sWhereSpecificArrayCount. It is needed for the bind var.
		//We could just do count($sWhereSpecificArray) - but that would be less efficient.
		$sWhereSpecificArrayCount++;
		 
		//Add current search param to the array for later use (binding).
		$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
		 
	}
}
 
//If there is still no where clause - set a general - always true where clause
if ( $sWhere == "" )
{
	$sWhere = " AND 1=1";
}
 
 



//Bind variables.
 
if ( isset( $_GET['iDisplayStart'] ))
{
	$dsplyStart = $_GET['iDisplayStart'];
}
else{
	$dsplyStart = 0;
}
 
if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
{
	$dsplyRange = $_GET['iDisplayLength'];
	if ($dsplyRange > (2147483645 - intval($dsplyStart)))
	{
		$dsplyRange = 2147483645;
	}
	else
	{
		$dsplyRange = intval($dsplyRange);
	}
}
else
{
	$dsplyRange = 2147483645;
}

$sOrder= "order by JA.NOMOR_URUT asc";

if($reqTipeUjianId == "7")
{
	$statement = " AND B.JADWAL_TES_ID = ".$reqId;

	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringPapiHasil(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringPapiHasil(array(), $reqId, $statement.$searchJson);

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringPapiHasil(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "16")
{
	$statement= "";
	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringKraepelin(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringKraepelin(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringKraepelin(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "17")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId;

	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.EMAIL) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringEppsHasil(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringEppsHasil(array(), $reqId, $statement.$searchJson);

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringEppsHasil(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit();
}
elseif($reqTipeUjianId == "18")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$searchJson= " AND (UPPER(A.NAMA_PEGAWAI) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringIst(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringIst(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	$set->selectByParamsMonitoringIst(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "28")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$searchJson= " AND (UPPER(A.NAMA_PEGAWAI) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringPauli(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringPauli(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	$set->selectByParamsMonitoringPauli(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "40")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$searchJson= " AND (UPPER(A.NAMA_PEGAWAI) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringPf16(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringPf16(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	$set->selectByParamsMonitoringPf16(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "41")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$searchJson= " AND (UPPER(A.NAMA_PEGAWAI) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringMbti(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringMbti(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	$set->selectByParamsMonitoringMbtiNew(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	// echo $set->query;exit;
}
elseif($reqTipeUjianId == "42")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$searchJson= " AND (UPPER(A.NAMA_PEGAWAI) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringDisc(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringDisc(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	$set->selectByParamsMonitoringDisc(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "45")
{
	$statement= " AND A.JADWAL_TES_ID = ".$reqId;
	$searchJson= " AND (UPPER(A.NAMA_PEGAWAI) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringBigFive(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringBigFive(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	$set->selectByParamsMonitoringBigFive(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "43")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId;

	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.EMAIL) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringBaruKraepelin(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringBaruKraepelin(array(), $reqId, $statement.$searchJson);
	// echo $sOrder;exit();

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringBaruKraepelin(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	//echo $set->query;exit;
}
elseif($reqTipeUjianId == "47")
{
	// $statement= " AND B.JADWAL_TES_ID = ".$reqId." AND C.TIPE_UJIAN_ID = ".$reqTipeUjianId."";
	$statement.= " AND HSL.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND HSL.JADWAL_TES_ID = ".$reqId;
	$statementdetil.= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId;

	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.EMAIL) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord= $set->getCountByParamsMonitoringRekapWPTNew(array(), $reqId, $statement, $statementdetil);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringRekapWPTNew(array(), $reqId, $statement.$searchJson, $statementdetil);
	// echo $sOrder;exit();

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	// $set->selectByParamsMonitoringRekapWPTNew(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $sOrder);
	$set->selectByParamsMonitoringRekapWPTNew(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
	 // echo $set->query;exit;
}
elseif($reqTipeUjianId == "48")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId;

	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.EMAIL) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord= $set->getCountByParamsMonitoringKertih(array(), $reqId, $statement, $statementdetil);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringKertih(array(), $reqId, $statement.$searchJson, $statementdetil);
	// echo $sOrder;exit();

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringKertih(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
	 // echo $set->query;exit;
}
elseif($reqTipeUjianId == "49")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId ;
	$statementDetil= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId ;

	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.EMAIL) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	$allRecord = $set->getCountByParamsMonitoringHolland(array(), $reqId, $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else	
		$allRecordFilter = $set->getCountByParamsMonitoringHolland(array(), $reqId, $statement.$searchJson);

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringHolland(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $statementDetil, $sOrder);
	 // echo $set->query;exit();
}
else
{
	// $statement = " AND B.JADWAL_TES_ID = ".$reqId;
	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";
	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";

	if($reqTipeUjianId == 2)
	{
		$sOrder="";
		
		$statementdetil.= " AND A.JADWAL_TES_ID = ".$reqId;
	
		$allRecord= $set->getCountByParamsMonitoringCfitHasilRekapB(array(), $reqId, $reqTipeUjianId, $statement, $statementdetil);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoringCfitHasilRekapB(array(), $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil);
		// echo $set->query;exit;
		$set->selectByParamsMonitoringCfitHasilRekapB(array(), $dsplyRange, $dsplyStart, $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil);
	}
	elseif($reqTipeUjianId == 1)
	{
		$statementdetil.= " AND A.JADWAL_TES_ID = ".$reqId;
	
		$sOrder="";
		$allRecord= $set->getCountByParamsMonitoringCfitHasilRekapA(array(), $reqId, $reqTipeUjianId, $statement, $statementdetil);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoringCfitHasilRekapA(array(), $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil);
		$set->selectByParamsMonitoringCfitHasilRekapA(array(), $dsplyRange, $dsplyStart, $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil);
		// echo $set->query;exit;

	}
	elseif($reqTipeUjianId == 72)
	{
		// $statementdetil.= " AND A.JADWAL_TES_ID = ".$reqId;
		$searchJson= " AND (UPPER(c.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(c.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		
		$allRecord= $set->getCountByParamsMonitoringRekapLain72(array(), $reqId, $reqTipeUjianId, $statement, $statementdetil);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoringRekapLain72(array(), $reqId, $reqTipeUjianId, $statement.$searchJson, $statementdetil);
		// echo $set->query;exit;
		$set->selectByParamsMonitoringRekapLain72Khusus(array(), -1,-1, $reqId, $statement.$searchJson, $statementdetil, $sOrder,$norma, $reqTipeUjianId);
		// echo $set->query; exit;
	}
	else if($reqTipeUjianId==73)
	{
		// $sOrder= " ORDER BY A.NAMA ASC ";
		$statementdetil.= " AND HSL.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND HSL.JADWAL_TES_ID = ".$reqId;
	
		$allRecord= $set->getCountByParamsMonitoringRekapLain(array(), $reqId, $statement, $statementdetil);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoringRekapLain(array(), $reqId, $statement.$searchJson, $statementdetil);
		// echo $set->query;exit;
		$set->selectByParamsMonitoringRekapLain73(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
		// echo $set->query;exit;
	}
	else
	{
		// $sOrder= " ORDER BY A.NAMA ASC ";
		$statementdetil.= " AND HSL.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND HSL.JADWAL_TES_ID = ".$reqId;
	
		$allRecord= $set->getCountByParamsMonitoringRekapLain(array(), $reqId, $statement, $statementdetil);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoringRekapLain(array(), $reqId, $statement.$searchJson, $statementdetil);
		// echo $set->query;exit;
		$set->selectByParamsMonitoringRekapLain(array(), $dsplyRange, $dsplyStart, $reqId, $statement.$searchJson, $statementdetil, $sOrder);
		// echo $set->query;exit;
	}
	
}

/*
 * Output
 */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $allRecord,
	"iTotalDisplayRecords" => $allRecordFilter,
	"aaData" => array()
);
 
$number = 1;
while($set->nextRow())
{
	$row = array();
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		$row[] = $set->getField(trim($aColumns[$i]));
	}
	
	$number++;
	$output['aaData'][] = $row;
}


echo json_encode( $output );
?>