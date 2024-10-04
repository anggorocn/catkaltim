<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaSuksesi.php");
include_once("../WEB/classes/base/FormulaFaktor.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");

$set = new FormulaSuksesi();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqTahun= httpFilterGet("reqTahun");
$reqFormulaJabatanTargetId= httpFilterGet("reqFormulaJabatanTargetId");
$reqFormulaUnsurId= httpFilterGet("reqFormulaUnsurId");
$reqKeterangan = httpFilterRequest("reqKeterangan");
$reqId = httpFilterRequest("reqId");
$reqCari = httpFilterRequest("reqCari");
$reqSearch = httpFilterGet("reqSearch");

function setnumberdua($val)
{
	return number_format(round($val,2), 2, '.', '');
}

$aColumns = array("NO","NAMA", "NIP_BARU", "JABATAN_NAMA", "SATKER");

if(!empty($reqFormulaUnsurId))
{
	$jumlahdetil= 0;
	$setdetil= new FormulaFaktor();
	$setdetil->selectByParams(array(), -1, -1, " AND A.FORMULA_ID = ".$reqFormulaUnsurId);
	$setdetil->firstRow();
	$infoassment= $setdetil->getField("ASSESMENT");
	$infografikid= $setdetil->getField("ID_GRAFIK");
	$infokuadranid= $setdetil->getField("ID_KUADRAN");
	// echo $infokuadranid;exit;

	if($infoassment > 0)
	{
		array_push($aColumns, "JPM");
	}

	$setdetil= new FormulaSuksesi();
	$setdetil->selectByParamsUnsurFormula(array(), -1, -1, " AND A.FORMULA_UNSUR_ID = ".$reqFormulaUnsurId);
	while ($setdetil->nextRow()) 
	{
		$detilrowid= $setdetil->getField("UNSUR_ID");
		array_push($aColumns, "DATA-".$detilrowid);
	}
}

array_push($aColumns, "TOTAL_STANDART");
array_push($aColumns, "PEGAWAI_ID");
// print_r($aColumns);exit;
$aColumnsAlias = $aColumns;

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
	if ( trim($sOrder) == "ORDER BY IDPEG asc" )
	{
		/*
		* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
		* If there is no order by clause there might be bugs in table display.
		* No order by clause means that the db is not responsible for the data ordering,
		* which means that the same row can be displayed in two pages - while
		* another row will not be displayed at all.
		*/
		$sOrder = " ORDER BY coalesce(C.KODE_ESELON,99) ASC, B.KODE_GOL DESC";
		 
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

// if($reqId == "")
// 	$statement='';
// else
// 	$statement .= " AND A.SATKER_ID LIKE '".$reqId."%'";

if(empty($reqFormulaUnsurId))
{
	$statement .= " AND 1 = 2";
	$reqFormulaUnsurId= -1;
}

$tahunsekarang= date("Y");

if(!empty($infokuadranid))
{
	$statementkuadran= " AND X.KUADRAN_PEGAWAI IN (".$infokuadranid.")";
}

/*if($infografikid == "1")
{
	$statement .= " AND EXISTS
	(
		SELECT 1
		FROM
		(
			SELECT
			A.KUADRAN_PEGAWAI, A.PEGAWAI_ID
			FROM
			(
				SELECT 
					CAST
					(
						CASE WHEN
						COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X1 
						THEN '1'
						WHEN 
						COALESCE(X.NILAI_POTENSI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X2
						THEN '2'
						ELSE '3' END
						||
						CASE 
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) >= 0) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
						ELSE '3' END
					AS INTEGER) KUADRAN_PEGAWAI
					, A.PEGAWAI_ID
				FROM simpeg.pegawai A
				INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
				INNER JOIN
				(
					SELECT PEGAWAI_ID
					, D.FORMULA
					, D.FORMULA_ID
					FROM penilaian A
					INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
					INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
					INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
					WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tahunsekarang."' AND ASPEK_ID in (1,2)
					GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), D.FORMULA, D.FORMULA_ID
				) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
					, D.FORMULA
					, D.FORMULA_ID
					FROM penilaian A
					INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
					INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
					INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID  
					WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tahunsekarang."' AND ASPEK_ID in (1)
				) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
					, D.FORMULA
					, D.FORMULA_ID
					FROM penilaian A
					INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
					INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
					INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID  
					WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tahunsekarang."' AND ASPEK_ID in (2)
				) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
				, 
				(
					SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
					FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$tahunsekarang."'
				) KD_Y,
				(
					SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
					FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$tahunsekarang."'
				) KD_X
				WHERE 1=1
			) A
		) X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID ".$statementkuadran."
	)";
}
elseif($infografikid == "2")
{
	$statement .= " AND EXISTS
	(
		SELECT 1
		FROM
		(
			SELECT
			A.KUADRAN_PEGAWAI, A.PEGAWAI_ID
			FROM
			(
				SELECT 
					CAST
					(
						CASE WHEN
						COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X1 
						THEN '1'
						WHEN 
						COALESCE(X.NILAI_POTENSI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X2
						THEN '2'
						ELSE '3' END
						||
						CASE 
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) >= 0) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
						ELSE '3' END
					AS INTEGER) KUADRAN_PEGAWAI
					, A.PEGAWAI_ID
				FROM simpeg.pegawai A
				INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
				INNER JOIN
				(
					SELECT PEGAWAI_ID
					FROM penilaian A
					INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
					INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
					INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
					WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tahunsekarang."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
				) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
					, D.FORMULA
					, D.FORMULA_ID
					FROM penilaian A
					INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
					INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
					INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
					WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tahunsekarang."' AND ASPEK_ID in (2)
				) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, TAHUN, NILAI_SKP NILAI_KOMPETENSI
					FROM
					(
						SELECT NOMOR, PEGAWAI_ID, TAHUN, NILAI_SKP
						FROM
						(
							SELECT 
							ROW_NUMBER () OVER (PARTITION BY PEGAWAI_ID ORDER BY TAHUN) NOMOR
							, PEGAWAI_ID, TAHUN, NILAI_SKP
							FROM
							(
								SELECT PEGAWAI_ID, 9999 TAHUN, CAST(LAST_SKP AS NUMERIC) NILAI_SKP
								FROM simpeg.pegawai A
								UNION ALL
								SELECT PEGAWAI_ID, CAST(SKP_TAHUN AS NUMERIC) TAHUN, CAST(NILAI_SKP AS NUMERIC) NILAI_SKP
								FROM simpeg.riwayat_skp A 
								WHERE SKP_TAHUN = '".$tahunsekarang."'
							) A
						) A
						WHERE NOMOR = 1
					) A
				) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
				, 
				(
					SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
					FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$tahunsekarang."'
				) KD_Y,
				(
					SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
					FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$tahunsekarang."'
				) KD_X
				WHERE 1=1
			) A
		) X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID ".$statementkuadran."
	)";
}
elseif($infografikid == "3")
{
	$statement .= " AND EXISTS
	(
		SELECT 1
		FROM
		(
			SELECT
			A.KUADRAN_PEGAWAI, A.PEGAWAI_ID
			FROM
			(
				SELECT 
					CAST
					(
						CASE WHEN
						COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X1 
						THEN '1'
						WHEN 
						COALESCE(X.NILAI_POTENSI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X2
						THEN '2'
						ELSE '3' END
						||
						CASE 
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) >= 0) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
						ELSE '3' END
					AS INTEGER) KUADRAN_PEGAWAI
					, A.PEGAWAI_ID
				FROM simpeg.pegawai A
				INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID				
				INNER JOIN
				(
					SELECT PEGAWAI_ID, 
					ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  NILAI_POTENSI
					, 100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
					, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
					, A.FORMULA
					, A.FORMULA_ID 
					FROM
					(
					SELECT PEGAWAI_ID, 
					round((SUM(JPM))*100,2) JPM, 
					round((SUM(IKK))*100,2) IKK
					, CASE WHEN ASPEK_ID = 1 THEN round((SUM(JPM))*100,2) ELSE 0 END PSIKOLOGI_JPM
					, CASE WHEN ASPEK_ID = 1 THEN round((SUM(IKK))*100,2) ELSE 0 END PSIKOLOGI_IKK
					, CASE WHEN ASPEK_ID = 2 THEN round((SUM(JPM))*100,2) ELSE 0 END KOMPETEN_JPM
					, CASE WHEN ASPEK_ID = 2 THEN round((SUM(IKK))*100,2) ELSE 0 END KOMPETEN_IKK
					, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
					, D.FORMULA
					, D.FORMULA_ID
					FROM penilaian A
					INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
					INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
					INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
					WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tahunsekarang."' AND ASPEK_ID in (1,2) 
					GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI, D.FORMULA
					, D.FORMULA_ID
					) A
					GROUP BY A.PEGAWAI_ID, PROSEN_POTENSI, PROSEN_KOMPETENSI, A.FORMULA
					, A.FORMULA_ID
				) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, TAHUN, NILAI_SKP NILAI_KOMPETENSI
						FROM
						(
							SELECT NOMOR, PEGAWAI_ID, TAHUN, NILAI_SKP
							FROM
							(
								SELECT 
								ROW_NUMBER () OVER (PARTITION BY PEGAWAI_ID ORDER BY TAHUN) NOMOR
								, PEGAWAI_ID, TAHUN, NILAI_SKP
								FROM
								(
									SELECT PEGAWAI_ID, 9999 TAHUN, CAST(LAST_SKP AS NUMERIC) NILAI_SKP
									FROM simpeg.pegawai A
									UNION ALL
									SELECT PEGAWAI_ID, CAST(SKP_TAHUN AS NUMERIC) TAHUN, CAST(NILAI_SKP AS NUMERIC) NILAI_SKP
									FROM simpeg.riwayat_skp A 
									WHERE SKP_TAHUN = '".$tahunsekarang."'
								) A
							) A
							WHERE NOMOR = 1
						) A
				) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
				, 
				(
					SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
					FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$tahunsekarang."'
				) KD_Y,
				(
					SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
					FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$tahunsekarang."'
				) KD_X
				WHERE 1=1
			) A
		) X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID ".$statementkuadran."
	)";
}*/
if(!empty($reqFormulaJabatanTargetId))
{
	$statement.= " AND A.PEGAWAI_ID IN (SELECT A.PEGAWAI_ID FROM formula_jabatan_target_pegawai A WHERE A.FORMULA_JABATAN_TARGET_ID = ".$reqFormulaJabatanTargetId.") ";
}
else
{
	$statement .= " AND 1 = 2";
}

$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%') ";
$allRecord = $set->getCountByParamsMonitoringPegawai(array(), $statement);
if($_GET['sSearch'] == "")
	$allRecordFilter = $allRecord;
else
	$allRecordFilter = $set->getCountByParamsMonitoringPegawai(array(), $statement.$searchJson);
// echo $set->query;exit;
// $sOrder="ORDER BY A.LAST_PANGKAT_ID  DESC, COALESCE(A.LAST_ESELON_ID,'99')  ASC";
$sOrder= "ORDER BY DT.NILAI DESC";
$set->selectByParamsMonitoringPegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $reqFormulaUnsurId, $sOrder);
// echo $set->errorMsg;exit;
// echo $set->query;exit;
/*
 * Output
 */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $allRecord,
	"iTotalDisplayRecords" => $allRecordFilter,
	"aaData" => array()
);

$totalstandar= 0;
$searchword= "DATA-";
$no_urut=1;
while($set->nextRow())
{
	$row = array();
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if(trim($aColumns[$i]) == "TGL_LAHIR" || trim($aColumns[$i]) == "TMT_GOL_AKHIR" || trim($aColumns[$i]) == "TMT_JABATAN")
			$row[] = dateToPageCheck($set->getField(trim($aColumns[$i])));
		elseif(trim($aColumns[$i]) == "NILAI" || trim($aColumns[$i]) == "NILAI")
			$row[] = setnumberdua($set->getField(trim($aColumns[$i])));
		elseif(preg_match("/\b$searchword\b/i", $aColumns[$i]))
		{
			$fielddata= str_replace($searchword, "", $aColumns[$i]);
			// echo $fielddata;exit;

			$statementdetil= " AND A.FORMULA_UNSUR_ID = ".$reqFormulaUnsurId." AND A.UNSUR_ID = '".$fielddata."'";
			$setdetil= new FormulaSuksesi();
			$setdetil->selectByParamsGetBobot(array(), -1, -1, $statementdetil);
			$setdetil->firstRow();
			// echo $setdetil->query;exit();
			$infobobot= $setdetil->getField("BOBOT");
			// $infobobot= 1;
			unset($setdetil);

			if($fielddata == "03")
			{
				$statementdetil= " AND A.PEGAWAI_ID = ".$set->getField("PEGAWAI_ID");
				$setdetil= new FormulaSuksesi();
				$setdetil->selectByParamsAmbilNilaiJpmPegawai(array(), -1, -1, $statementdetil);
				$setdetil->firstRow();
				// echo $setdetil->query;exit();
				// echo $setdetil->errorMsg;exit();
				$infovalue= setnumberdua($setdetil->getField("JPM") * $infobobot);
				// echo $infovalue;exit;

	        	$row[] = $infovalue;
	        	$totalstandar+= $infovalue;
	        	unset($setdetil);
			}
			else
			{
				$statementdetil= " AND A.PEGAWAI_ID = ".$set->getField("PEGAWAI_ID")." AND A.FORMULA_UNSUR_ID = ".$reqFormulaUnsurId." AND A.UNSUR_ID = '".$fielddata."'";
				$setdetil= new FormulaSuksesi();
				$setdetil->selectByParamsAmbilNilaiUnsurPegawai(array(), -1, -1, $statementdetil);
				$setdetil->firstRow();
				// echo $setdetil->query;exit();
				// echo $setdetil->errorMsg;exit();
				$infovalue= setnumberdua($setdetil->getField("NILAI"));
				// echo $infovalue;exit;

	        	$row[] = $infovalue;
	        	$totalstandar+= $infovalue;
	        	unset($setdetil);
			}
    	}
    	elseif(trim($aColumns[$i]) == "JPM")
    	{
    		$statementdetil= " AND A.PEGAWAI_ID = ".$set->getField("PEGAWAI_ID");
			$setdetil= new FormulaSuksesi();
			$setdetil->selectByParamsAmbilNilaiJpmPegawai(array(), -1, -1, $statementdetil);
			$setdetil->firstRow();
			// echo $setdetil->query;exit();
			// echo $setdetil->errorMsg;exit();
			$infovalue= setnumberdua($setdetil->getField("JPM"));
			// echo $infovalue;exit;

        	$row[] = $infovalue;
        	$totalstandar+= $infovalue;
        	unset($setdetil);
    	}
    	
    	elseif(trim($aColumns[$i]) == "INFO_JADWAL")
		{
			$row[] = $set->getField("JT_ACARA").",<br/>".getFormattedDateTime($set->getField("JT_TANGGAL_TES"), false);
			$totalstandar= 0;
		}
    	elseif(trim($aColumns[$i]) == "TOTAL_STANDART")
		{
			$row[] = $totalstandar;
			$totalstandar= 0;
		}
		elseif($aColumns[$i] == "NO")
		{
			$row[] = $no_urut;
		}
		else
		$row[] = $set->getField(trim($aColumns[$i]));
	}
	$no_urut++;
	$output['aaData'][] = $row;
}

echo json_encode( $output );
?>