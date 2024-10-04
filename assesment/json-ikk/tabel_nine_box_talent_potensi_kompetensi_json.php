<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Grafik.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");

$set = new Grafik();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqKeterangan = httpFilterRequest("reqKeterangan");
$reqId= httpFilterGet("reqId");
$reqKdOrganisasiId= httpFilterGet("reqKdOrganisasiId");
$reqJenisJabatan= httpFilterGet("reqJenisJabatan");

$reqTahun = httpFilterRequest("reqTahun");
$reqSearch = httpFilterGet("reqSearch");
$reqKuadranId= httpFilterGet("reqKuadranId");
$reqEselonId= httpFilterGet("reqEselonId");
$reqFormulaId= httpFilterGet("reqFormulaId");

$reqStatusPeg= httpFilterGet("reqStatusPeg");
$reqSearch= httpFilterGet("reqSearch");

$aColumns = array("PEGAWAI_ID", "NO", "NAMA", "NAMA_JAB_STRUKTURAL", "PSIKOLOGI_JPM", "KOMPETEN_JPM", "IKK", "JPM_TOTAL", "IKK_TOTAL", "NILAI", "KODE_KUADRAN", "NAMA_KUADRAN");
$aColumnsAlias = array("PEGAWAI_ID", "NO", "NAMA", "NAMA_JAB_STRUKTURAL", "PSIKOLOGI_JPM", "KOMPETEN_JPM", "IKK", "JPM_TOTAL", "IKK_TOTAL", "NILAI", "KODE_KUADRAN", "NAMA_KUADRAN");

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

if($reqId == "" || $reqId == "1")
	$statement='';
else
	$statement .= " AND A.SATKER_ID LIKE '".$reqId."%'";
//echo $statement;exit;

$searchJson= " AND (UPPER(B.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(CAST(B.PEGAWAI_ID AS TEXT)) LIKE '%".strtoupper($_GET['sSearch'])."%' ) ";
$orderby=" ORDER BY JPM_TOTAL DESC,NILAI DESC";

if($reqKuadranId == ""){}
else
$statementDetil= " AND A.ID_KUADRAN = ".$reqKuadranId;

// if($reqJenisJabatan == ""){}
// else
// {
//     if($reqEselonId == "")
//     {
//         $statement .= " AND ".jenisjabatanDb($reqJenisJabatan, "A.");
//     }
// }

if($reqKdOrganisasiId == ""){}
else
{
    $statement .= " AND A.KD_SATUAN_ORGANISASI = '".$reqKdOrganisasiId."'";
}

// if($reqEselonId == ""){}
// else
// {
// 	$statement .= " AND ".jejangjabatanDb($reqEselonId, "A.");
// }

if($reqFormulaId == "")
	$statement.="";
else
	$statement .= " AND X1.FORMULA_ID = '".$reqFormulaId."' ";

$allRecord = $set->getCountByParamsMonitoringTableTalentPoolMonitoring(array(), $statement, $reqTahun, $statementDetil);
if($_GET['sSearch'] == "")
	$allRecordFilter = $allRecord;
else
	$allRecordFilter = $set->getCountByParamsMonitoringTableTalentPoolMonitoring(array(), $statement, $reqTahun, $statementDetil.$searchJson,$orderby);
//echo $set->query;exit;
$set->selectByParamsMonitoringTableTalentPoolMonitoring(array(), $dsplyRange, $dsplyStart, $statement, "", $reqTahun, $statementDetil.$searchJson,$orderby);
 // echo $set->query;exit;
//echo $set->errorMsg;exit;
//echo $set->query;exit;
/*
 * Output
 */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $allRecord,
	"iTotalDisplayRecords" => $allRecordFilter,
	"aaData" => array()
);

$no_urut=1;
while($set->nextRow())
{
	$row = array();
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if(trim($aColumns[$i]) == "TGL_LAHIR" || trim($aColumns[$i]) == "TMT_GOL_AKHIR" || trim($aColumns[$i]) == "TMT_JABATAN")
		$row[] = dateToPageCheck($set->getField(trim($aColumns[$i])));
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