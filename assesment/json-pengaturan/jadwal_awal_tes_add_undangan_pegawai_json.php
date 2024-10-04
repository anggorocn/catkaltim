<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base/JadwalAwalTesPegawai.php");
include_once("../WEB/classes/base/JadwalAwalTes.php");

$set = new JadwalAwalTesPegawai();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqId= httpFilterGet("reqId");
$reqCheckId= httpFilterGet("reqCheckId");
$arrayCheckId= explode(',', $reqCheckId);
// print_r($arrayCheckId);exit();

if($reqId == ""){}
else
{
	$setdetil= new JadwalAwalTes();
	$setdetil->selectByParams(array(), -1,-1, " AND JADWAL_AWAL_TES_ID = ".$reqId);
	$setdetil->firstRow();
	$reqStatusJenis= $setdetil->getField("STATUS_JENIS");
}

if($reqStatusJenis == "2")
	$aColumns= array("CHECK", "PEGAWAI_NAMA", "NIK");
else
	$aColumns= array("CHECK", "PEGAWAI_NAMA", "PEGAWAI_NIP", "PEGAWAI_GOL", "PEGAWAI_ESELON", "PEGAWAI_JAB_STRUKTURAL", "SATKER");

$aColumnsAlias= $aColumns;
 
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
	if ( trim($sOrder) == "ORDER BY CHECK asc" )
	{
		/*
		* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
		* If there is no order by clause there might be bugs in table display.
		* No order by clause means that the db is not responsible for the data ordering,
		* which means that the same row can be displayed in two pages - while
		* another row will not be displayed at all.
		*/
		$sOrder = " ORDER BY A1.NIP_BARU";
		 
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

$statement= " AND A.JADWAL_AWAL_TES_ID = ".$reqId;
$searchJson= " AND (UPPER(A1.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A1.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%') ";

$allRecord = $set->getCountByParamsMonitoringPegawai(array(), $statement);
if($_GET['sSearch'] == "")
	$allRecordFilter = $allRecord;
else
	$allRecordFilter = $set->getCountByParamsMonitoringPegawai(array(), $statement.$searchJson);
	//echo $set->query;exit;

$set->selectByParamsMonitoringPegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
 
$number = 1;
while($set->nextRow())
{
	$row = array();
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if($aColumns[$i] == 'CHECK')
		{
			// $checked= "";
			// if($set->getField("CHECK_DATA") == "1")
			// $checked= "checked disabled";

			$checked= "";
			/*$keyArray= array_search($set->getField("PEGAWAI_ID"), $arrayTreeId);
			if($keyArray == ""){}
			else
				$checked= "checked";*/
			$tempCheckId= $set->getField("PEGAWAI_ID");
			if(in_array($tempCheckId, $arrayCheckId))
			{
				$checked= "checked";
			}

			if($set->getField("JUMLAH_DATA") == "0")
			{
				$row[] = "<input type='checkbox' $checked onclick='setKlikCheck()' class='editor-active' id='reqPilihCheck".$set->getField("PEGAWAI_ID")."' ".$checked." value='".$set->getField("PEGAWAI_ID")."'>";
			}
			else
				$row[] = "";
		}
		else if(trim($aColumns[$i]) == "TANGGAL_TES")
			$row[] = getFormattedDateTime($set->getField(trim($aColumns[$i])), false);
			// $row[] = datetimeToPage($set->getField(trim($aColumns[$i])), "date");
		else
			$row[] = $set->getField(trim($aColumns[$i]));
	}
	
	$number++;
	$output['aaData'][] = $row;
}


echo json_encode( $output );
?>