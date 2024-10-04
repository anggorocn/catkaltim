<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base/Pelamar.php");

$pegawai = new Pelamar();

/* LOGIN CHECK  */
if ($userLogin->checkUserLoginAdmin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId = httpFilterGet("reqId");
$reqPangkatId= httpFilterGet("reqPangkatId");
$reqJabatanId= httpFilterGet("reqJabatanId");
$reqStatus = httpFilterGet("reqStatus");
$reqCutoff = httpFilterGet("reqCutoff");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqLowonganTahapanId= httpFilterGet("reqLowonganTahapanId");

if($reqCutoff == "")
	$reqCutoff = date("d-m-Y");
	
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);


$aColumns = array("PELAMAR_ID", "CHECK", "KTP_NO", "NAMA", "JENIS_KELAMIN", "AGAMA","EMAIL","TEMPAT_LAHIR", "TANGGAL_LAHIR");
$aColumnsAlias = array("PELAMAR_ID", "CHECK", "KTP_NO", "NAMA", "JENIS_KELAMIN", "AGAMA","EMAIL","TEMPAT_LAHIR", "TANGGAL_LAHIR");

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

if($reqId == ""){}
else
$statement= " AND A.PELAMAR_ID NOT IN (SELECT A.PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR A INNER JOIN cat.UJIAN B ON A.UJIAN_ID = B.UJIAN_ID WHERE B.LOWONGAN_ID = ".$reqLowonganId." AND B.LOWONGAN_TAHAPAN_ID = ".$reqLowonganTahapanId.")";
//$statement = " AND A.PELAMAR_ID NOT IN (SELECT PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_ID = ".$reqId.")";

if($reqPangkatId == ""){}
else
$statement.= " AND A.PANGKAT_ID = ".$reqPangkatId;

if($reqJabatanId == ""){}
else
$statement.= " AND A.JABATAN_ID = ".$reqJabatanId;

if($reqLowonganId == ""){}
else
$statement.= " AND PL.LOWONGAN_ID = ".$reqLowonganId;

if($reqLowonganTahapanId == ""){}
else
$statement.= " AND PLT.LOWONGAN_TAHAPAN_ID = ".$reqLowonganTahapanId;

$statement .= " AND A.PELAMAR_ID NOT IN (SELECT C.PEGAWAI_ID FROM cat.UJIAN B 
			 INNER JOIN
			 (SELECT A.PEGAWAI_ID, A.UJIAN_ID, TO_CHAR(TGL_MULAI, 'DD-MM-YYYY') TGL_MULAI, TO_CHAR(TGL_SELESAI, 'DD-MM-YYYY') TGL_SELESAI
			 FROM cat.UJIAN_PEGAWAI_DAFTAR A 
			 INNER JOIN cat.UJIAN B ON A.UJIAN_ID = B.UJIAN_ID) C ON  TO_CHAR(B.TGL_MULAI, 'DD-MM-YYYY') = C.TGL_MULAI AND TO_CHAR(B.TGL_SELESAI, 'DD-MM-YYYY') = C.TGL_SELESAI AND C.UJIAN_ID = B.UJIAN_ID
			 WHERE B.UJIAN_ID = ".$reqId.")";

$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.KTP_NO) LIKE '%".strtoupper($_GET['sSearch'])."%')";
$allRecord = $pegawai->getCountByParamsTahapanCutOff(array(), $statement);
if($_GET['sSearch'] == "")
	$allRecordFilter = $allRecord;
else	
	$allRecordFilter = $pegawai->getCountByParamsTahapanCutOff(array(), $statement.$searchJson);

$pegawai->selectByParamsTahapanCutOff($reqCutoff, array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder); 
//echo $pegawai->query;exit;

/* Output */
$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $allRecord,
	"iTotalDisplayRecords" => $allRecordFilter,
	"aaData" => array()
);

while($pegawai->nextRow())
{
	$row = array();
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if($aColumns[$i] == 'CHECK')
		{
			$row[] = "<input type='checkbox' class='editor-active' id='reqPilihCheck".$no_urut."' value='".$pegawai->getField("PELAMAR_ID")."'>";
		}
		else if($aColumns[$i] == "TANGGAL_LAHIR")
			$row[] = getFormattedDate($pegawai->getField($aColumns[$i]));
		else if($aColumns[$i] == "PERTANYAAN")
			$row[] = truncate($pegawai->getField($aColumns[$i]), 15)."...";
		else if($aColumns[$i] == "TARIF_NORMAL" || $aColumns[$i] == "TARIF_MAKSIMAL")
			$row[] = currencyToPage($pegawai->getField($aColumns[$i]));			
		else
			$row[] = $pegawai->getField($aColumns[$i]);
	}
	
	$output['aaData'][] = $row;
}

echo json_encode( $output );
?>
