<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Lowongan.php");

$lowongan = new Lowongan();

/* LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/

$reqCabangP3Id= httpFilterGet("reqCabangP3Id");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$aColumns = array("LOWONGAN_ID", "KODE", "JABATAN", "TANGGAL", "TANGGAL_AWAL", "TANGGAL_AKHIR", "JUMLAH_PELAMAR", "JUMLAH_SHORTLIST");
$aColumnsAlias = array("LOWONGAN_ID", "KODE", "JABATAN", "TANGGAL", "TANGGAL_AWAL", "TANGGAL_AKHIR", "(SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.LOWONGAN_ID = A.LOWONGAN_ID AND TANGGAL_KIRIM IS NOT NULL)", "(SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.LOWONGAN_ID = A.LOWONGAN_ID)");

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
	if ( trim($sOrder) == "ORDER BY LOWONGAN_ID asc" )
	{
		/*
		* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
		* If there is no order by clause there might be bugs in table display.
		* No order by clause means that the db is not responsible for the data ordering,
		* which means that the same row can be displayed in two pages - while
		* another row will not be displayed at all.
		*/
		$sOrder = " ORDER BY TANGGAL DESC";
		 
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

$statement = " AND EXISTS(SELECT 1 FROM pds_rekrutmen.LOWONGAN_TAHAPAN X WHERE X.LOWONGAN_ID = A.LOWONGAN_ID) ";

$tempSessionCabangP3Id= $userLogin->cabangP3Id;
$tempSessionWilayahId= $userLogin->wilayahId;
if($tempSessionCabangP3Id == "" || $tempSessionWilayahId != "")
{
	if($reqCabangP3Id == ""){}
	else
	$statement .= " AND A.CABANG_P3_ID = ".$reqCabangP3Id;
	
	if($tempSessionWilayahId == ""){}
	else
	$statement .= " AND EXISTS (SELECT 1 FROM pds_rekrutmen.CABANG_P3 X WHERE A.CABANG_P3_ID = X.CABANG_P3_ID AND WILAYAH_ID = ".$tempSessionWilayahId.")";
}
else
$statement .= " AND A.CABANG_P3_ID = ".$tempSessionCabangP3Id;

$statement.= " AND A.STATUS != '2' ";

$searchJson= " AND (UPPER(B.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.KODE) LIKE '%".strtoupper($_GET['sSearch'])."%')";
$allRecord = $lowongan->getCountByParams(array(), $statement);
if($_GET['sSearch'] == "")
	$allRecordFilter = $allRecord;
else	
	$allRecordFilter = $lowongan->getCountByParams(array(), $statement.$searchJson);

$lowongan->selectByParamsPelamar(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
//echo $lowongan->query;exit;
/* Output */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $allRecord,
	"iTotalDisplayRecords" => $allRecordFilter,
	"aaData" => array()
);

while($lowongan->nextRow())
{
	$row = array();
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if($aColumns[$i] == "TANGGAL")
			$row[] = getFormattedDateTime($lowongan->getField($aColumns[$i]));
		elseif($aColumns[$i] == "TANGGAL_AWAL" || $aColumns[$i] == "TANGGAL_AKHIR")
			$row[] = getFormattedDate($lowongan->getField($aColumns[$i]));				
		else if($aColumns[$i] == "KETERANGAN")
			$row[] = truncate($lowongan->getField($aColumns[$i]), 5)."...";
		else
			$row[] = $lowongan->getField($aColumns[$i]);
	}
	
	$output['aaData'][] = $row;
}

echo json_encode( $output );
?>
