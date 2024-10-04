<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base-cat/BankSoal.php");

$bank_soal = new BankSoal();

/* LOGIN CHECK  */

if ($userLogin->checkUserLoginAdmin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqRowId = httpFilterGet("reqRowId");
$reqTipeUjian = httpFilterGet("reqTipeUjian");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$aColumns = array("BANK_SOAL_ID", "NO", "PERTANYAAN", "TIPE_SOAL_INFO");
$aColumnsAlias = array("BANK_SOAL_ID", "NO", "PERTANYAAN", "TIPE_SOAL_INFO");

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
	if ( trim($sOrder) == "ORDER BY A.NO_URUT ASC" )
	{
		/*
		* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
		* If there is no order by clause there might be bugs in table display.
		* No order by clause means that the db is not responsible for the data ordering,
		* which means that the same row can be displayed in two pages - while
		* another row will not be displayed at all.
		*/
		$sOrder = " ORDER BY A.NO_URUT DESC";
		 
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

if($reqRowId == ""){}
else
$statement = " AND A.BANK_SOAL_ID NOT IN (".$reqRowId.")";

$statement.= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjian;

$allRecord = $bank_soal->getCountByParams(array(), $statement);
if($_GET['sSearch'] == "")
	$allRecordFilter = $allRecord;
else	
	$allRecordFilter = $bank_soal->getCountByParams(array(), $statement." AND (UPPER(PERTANYAAN) LIKE '%".strtoupper($_GET['sSearch'])."%')");

$bank_soal->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement." AND (UPPER(PERTANYAAN) LIKE '%".strtoupper($_GET['sSearch'])."%')", $sOrder);     		
//echo $bank_soal->query;exit;

/* Output */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $allRecord,
	"iTotalDisplayRecords" => $allRecordFilter,
	"aaData" => array()
);

$number = 1;
while($bank_soal->nextRow())
{
	$row = array();
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if($aColumns[$i] == "NO")
			$row[] = $number;
		else if($aColumns[$i] == "TANGGAL")
			$row[] = getFormattedDate($bank_soal->getField($aColumns[$i]));
		else if($aColumns[$i] == "PERTANYAAN")
		{
			$tempUrlGambar="";
			$tempTipeSoal= $bank_soal->getField("TIPE_SOAL");
			$tempPertanyaan= truncate($bank_soal->getField($aColumns[$i]), 15)."...";
			if($tempTipeSoal == "1"){}
			else
			{
				$tempUrlGambar= $bank_soal->getField("PATH_GAMBAR").$bank_soal->getField("PATH_SOAL");
				if(file_exists($tempUrlGambar))
				{
					$tempUrlGambar= '  <img src="'.$tempUrlGambar.'">';
				}
				else
				$tempUrlGambar="";
			}
			
			$row[] = $tempPertanyaan.$tempUrlGambar;
		}
		else if($aColumns[$i] == "TARIF_NORMAL" || $aColumns[$i] == "TARIF_MAKSIMAL")
			$row[] = currencyToPage($bank_soal->getField($aColumns[$i]));			
		else
			$row[] = $bank_soal->getField($aColumns[$i]);
		
	}
	
	$output['aaData'][] = $row;
		$number++;
}

echo json_encode( $output );
?>
