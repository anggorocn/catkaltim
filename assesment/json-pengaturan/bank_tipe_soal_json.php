<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base-cat/BankSoal.php");

$set = new BankSoal();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqTipeSoal= httpFilterGet("reqTipeSoal");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");

$aColumns= array("NO", "INFO_SOAL");

$arrexcept= [];
$arrexcept= array("7","9","13","20","22","23","24","41","42","72","73");
if(in_array($reqTipeUjianId, $arrexcept)){}
else
{
	array_push($aColumns, "INFO_JAWABAN");
}
array_push($aColumns, "BANK_SOAL_ID");
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
	if ( trim($sOrder) == "ORDER BY BANK_SOAL_ID asc" )
	{
		/*
		* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
		* If there is no order by clause there might be bugs in table display.
		* No order by clause means that the db is not responsible for the data ordering,
		* which means that the same row can be displayed in two pages - while
		* another row will not be displayed at all.
		*/
		$sOrder = " ORDER BY A.TIPE_SOAL, B.TIPE";
		 
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

if($reqTipeSoal == ""){}
else
$statement= " AND A.TIPE_SOAL = ".$reqTipeSoal;

if(!empty($reqTipeUjianId))
$statement.= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId;

if($reqTipeUjianId == 7)
{
	$searchJson= "";
	$sOrder= "ORDER BY A.SOAL_PAPI_ID";

	$allRecord = $set->getCountByParamsBankPapiTipeSoal(array(), $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else
		$allRecordFilter = $set->getCountByParamsBankPapiTipeSoal(array(), $statement.$searchJson);
		//echo $set->query;exit;

	$set->selectByParamsBankPapiTipeSoal(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
}
elseif($reqTipeUjianId == 41)
{
	$searchJson= "";
	$sOrder= "ORDER BY A.MBTI_SOAL_ID";

	$allRecord = $set->getCountByParamsBankMbtiTipeSoal(array(), $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else
		$allRecordFilter = $set->getCountByParamsBankMbtiTipeSoal(array(), $statement.$searchJson);
		//echo $set->query;exit;

	$set->selectByParamsBankMbtiTipeSoal(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
}
elseif($reqTipeUjianId == 42)
{
	$statement= "";
	$searchJson= "";
	$sOrder= "ORDER BY A.DISK_SOAL_ID";

	$allRecord = $set->getCountByParamsBankDiscTipeSoal(array(), $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else
		$allRecordFilter = $set->getCountByParamsBankDiscTipeSoal(array(), $statement.$searchJson);
		//echo $set->query;exit;

	$set->selectByParamsBankDiscTipeSoal(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
}
elseif($reqTipeUjianId == 45)
{
	$searchJson= "";
	$sOrder= "ORDER BY A.BIG_FIVE_SOAL_ID";

	$allRecord = $set->getCountByParamsBankBigFiveTipeSoal(array(), $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else
		$allRecordFilter = $set->getCountByParamsBankBigFiveTipeSoal(array(), $statement.$searchJson);
		//echo $set->query;exit;

	$set->selectByParamsBankBigFiveTipeSoal(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
}
elseif($reqTipeUjianId == 47)
{
	$searchJson= "";
	$sOrder= "ORDER BY A.WPT_SOAL_ID";

	$allRecord = $set->getCountByParamsBankWptTipeSoal(array(), $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else
		$allRecordFilter = $set->getCountByParamsBankWptTipeSoal(array(), $statement.$searchJson);
		//echo $set->query;exit;

	$set->selectByParamsBankWptTipeSoal(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
}
elseif($reqTipeUjianId == 49)
{
	$searchJson= "";
	$sOrder= "ORDER BY A.HOLAND_SOAL_ID";

	$allRecord = $set->getCountByParamsBankHolandTipeSoal(array(), $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else
		$allRecordFilter = $set->getCountByParamsBankHolandTipeSoal(array(), $statement.$searchJson);
		//echo $set->query;exit;

	$set->selectByParamsBankHolandTipeSoal(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
}
else
{
	$arrexcept= [];
	$arrexcept= array("20","74");
	if(in_array($reqTipeUjianId, $arrexcept))
		$searchJson= "";
	else
		$searchJson= " AND (UPPER(PERTANYAAN) LIKE '%".strtoupper($_GET['sSearch'])."%') ";

	$sOrder= "ORDER BY A.BANK_SOAL_ID";

	$allRecord = $set->getCountByParamsBankTipeSoal(array(), $statement);
	if($_GET['sSearch'] == "")
		$allRecordFilter = $allRecord;
	else
		$allRecordFilter = $set->getCountByParamsBankTipeSoal(array(), $statement.$searchJson);
		//echo $set->query;exit;

	$set->selectByParamsBankTipeSoal(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
}
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

$rangeinfo= ($dsplyRange/$dsplyStart) * $dsplyRange;
// echo $rangeinfo;exit;
$number = $rangeinfo + 1;
while($set->nextRow())
{
	$infosoalid= $set->getField("BANK_SOAL_ID");
	$infosoal= $set->getField("INFO_SOAL");
	$infotipesoal= $set->getField("TIPE_SOAL");
	$infopathgambar= $set->getField("PATH_GAMBAR");

	$row = array();
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if($aColumns[$i] == "NO")
		{
			if($reqTipeUjianId == 7)
			{
				$soalotherid= $set->getField("SOAL_PAPI_ID");
				$row[] = $soalotherid;
			}
			elseif($reqTipeUjianId == 41)
			{
				$soalotherid= $set->getField("MBTI_SOAL_ID");
				$row[] = $soalotherid;
			}
			elseif($reqTipeUjianId == 42)
			{
				$soalotherid= $set->getField("DISK_SOAL_ID");
				$row[] = $soalotherid;
			}
			elseif($reqTipeUjianId == 45)
			{
				$soalotherid= $set->getField("BIG_FIVE_SOAL_ID");
				$row[] = $soalotherid;
			}
			elseif($reqTipeUjianId == 47)
			{
				$soalotherid= $set->getField("WPT_SOAL_ID");
				$row[] = $soalotherid;
			}
			elseif($reqTipeUjianId == 49)
			{
				$soalotherid= $set->getField("HOLAND_SOAL_ID");
				$row[] = $soalotherid;
			}
			else
			$row[] = $number;
		}
		elseif($aColumns[$i] == "INFO_SOAL")
		{
			if($reqTipeUjianId == 7)
			{
				$infotext= "";
				$setdetil= new BankSoal();
				$setdetil->selectByParamsPapiSoal(array(), -1, -1, " AND A.SOAL_PAPI_ID = ".$soalotherid);
				// echo $setdetil->query;exit;
				while($setdetil->nextRow())
				{
					if(!empty($infotext))
						$infotext.= "<br/>";

					$infotext.= "- ".$setdetil->getField("JAWABAN");
				}
				$row[]= $infotext;
			}
			elseif($reqTipeUjianId == 41)
			{
				$infotext= "";
				$setdetil= new BankSoal();
				$setdetil->selectByParamsMbtiSoal(array(), -1, -1, " AND A.MBTI_SOAL_ID = ".$soalotherid);
				// echo $setdetil->query;exit;
				while($setdetil->nextRow())
				{
					if(!empty($infotext))
						$infotext.= "<br/>";

					$infotext.= "- ".$setdetil->getField("JAWABAN");
				}
				$row[]= $infotext;
			}
			elseif($reqTipeUjianId == 42)
			{
				$infotext= "";
				$setdetil= new BankSoal();
				$setdetil->selectByParamsDiscSoal(array(), -1, -1, " AND A.DISK_SOAL_ID = ".$soalotherid);
				// echo $setdetil->query;exit;
				while($setdetil->nextRow())
				{
					if(!empty($infotext))
						$infotext.= "<br/>";

					$infotext.= "- ".$setdetil->getField("JAWABAN");
				}
				$row[]= $infotext;
			}
			elseif($infotipesoal == 2 || $infotipesoal == 5)
			{
				$row[]= '<img src="'.$infosoal.'" width="100px">';
			}
			elseif($infotipesoal == 3)
			{
				$infoimage= "";
				$setdetil= new BankSoal();
				$setdetil->selectByParamsBankSoalPilihan(array(), -1, -1, " AND A.BANK_SOAL_ID = ".$infosoalid);
				// echo $setdetil->query;exit;
				while($setdetil->nextRow())
				{
					$infoimage.= '<img src="'.$infosoal.$setdetil->getField("PATH_GAMBAR").'" width="80px">';
				}
				$row[]= $infoimage;
			}
			elseif($infotipesoal == "1")
			{
				$arrexcept= [];
				$arrexcept= array("21","27","40","70","71","73");
				if(in_array($reqTipeUjianId, $arrexcept))
				{
					$infoimage= $set->getField("PERTANYAAN");
				}
				else
				{
					$infoimage= "";
					$setdetil= new BankSoal();
					$setdetil->selectByParamsBankSoalPilihan(array(), -1, -1, " AND A.BANK_SOAL_ID = ".$infosoalid);
					// echo $setdetil->query;exit;
					while($setdetil->nextRow())
					{
						if(!empty($infoimage))
							$infoimage.= "<br/>";

						$infoimage.= "- ".$setdetil->getField("JAWABAN");
					}
				}
				$row[]= $infoimage;
			}
			else
			{
				$arrexcept= [];
				$arrexcept= array("45","47","49");
				if(in_array($reqTipeUjianId, $arrexcept))
				{
					$infosoal= $set->getField("PERTANYAAN");
				}

				$row[]= $infosoal;
			}
		}
		elseif($aColumns[$i] == "INFO_JAWABAN")
		{
			if($reqTipeUjianId == 45)
			{
				$infotext= "";
				$setdetil= new BankSoal();
				$setdetil->selectByParamsBigFiveSoal(array(), -1, -1, " AND A.BIG_FIVE_SOAL_ID = ".$soalotherid);
				// echo $setdetil->query;exit;
				while($setdetil->nextRow())
				{
					if(!empty($infotext))
						$infotext.= "<br/>";

					$infotext.= "- ".$setdetil->getField("JAWABAN_INFO");
				}
				$row[]= $infotext;
			}
			elseif($reqTipeUjianId == 47)
			{
				$arrexcept= [];
				$arrexcept= array("7","7");

				$infotext= "";
				$setdetil= new BankSoal();
				$setdetil->selectByParamsWptSoal(array(), -1, -1, " AND A.WPT_SOAL_ID = ".$soalotherid);
				// echo $setdetil->query;exit;
				while($setdetil->nextRow())
				{
					if(in_array($soalotherid, $arrexcept))
					{
						$infotext.= '<img src="../../cat/main/uploads/wpt/'.$setdetil->getField("JAWABAN").'" width="50px">';
					}
					else
					{
						if(!empty($infotext))
							$infotext.= "<br/>";

						$infotext.= "- ".$setdetil->getField("JAWABAN");	
					}
				}
				$row[]= $infotext;
			}
			elseif($reqTipeUjianId == 49)
			{
				$infotext= "";
				$setdetil= new BankSoal();
				$setdetil->selectByParamsHolandSoal(array(), -1, -1, " AND A.HOLAND_SOAL_ID = ".$soalotherid);
				// echo $setdetil->query;exit;
				while($setdetil->nextRow())
				{
					if(!empty($infotext))
						$infotext.= "<br/>";

					$infotext.= "- ".$setdetil->getField("JAWABAN");
				}
				$row[]= $infotext;
			}
			else
			{
				$infoimage= "";
				$setdetil= new BankSoal();
				$setdetil->selectByParamsBankSoalPilihan(array(), -1, -1, " AND A.BANK_SOAL_ID = ".$infosoalid);
				// echo $setdetil->query;exit;
				while($setdetil->nextRow())
				{
					if($infotipesoal == "1" || $infotipesoal == "5")
					{
						if(!empty($infoimage))
							$infoimage.= "<br/>";

						$infoimage.= "- ".$setdetil->getField("JAWABAN");
					}
					else
					{
						$infoimage.= '<img src="'.$infopathgambar.$setdetil->getField("PATH_GAMBAR").'" width="50px">';
					}
				}
				$row[]= $infoimage;
			}
		}
		else
		$row[]= $set->getField(trim($aColumns[$i]));
	}
	
	$number++;
	$output['aaData'][] = $row;
}
echo json_encode( $output );
?>