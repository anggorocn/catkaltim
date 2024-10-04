<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/functions/jexcelarray.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId = httpFilterGet("reqId");
$reqRowId = httpFilterGet("reqRowId");
$reqPencarian = httpFilterGet("reqPencarian");
$reqAsesorId = httpFilterGet("reqAsesorId");

$reqMode = httpFilterGet("reqMode");

if($reqMode == "viewdetil")
{
	$reqDataLineNo= 0;
	$arrkolomdata= "";
	$arrkolomdata= array();
	$arrkolomdata= getarray("jadwalacaraasesorview");

	$arrJadwalAsesor="";
	$statement= " AND A.JADWAL_ACARA_ID = ".$reqId;
	$set= new JadwalAsesor();
	$set->selectByParamsMonitoring(array(), -1,-1, $statement);
	// echo $set->query;exit;

	$index=0;
	$arrData= array();
	$reqCheckCatalogData= "";
	while ($set->nextRow()) 
	{
		$reqCatalogId= $set->getField($arrkolomdata[array_search('reqCatalogId', array_column($arrkolomdata, 'val'))]["field"]);
		$reqCheckCatalogValue= $reqCatalogId;

		// kalau sama tmbahi data lama
		if($reqCheckCatalogData == $reqCheckCatalogValue){}
		// kalau beda buat index baru
		else
		{
			for($iColumn=0;$iColumn<count($arrkolomdata);$iColumn++)
			{
				if($arrkolomdata[$iColumn]["val"] == "reqCatalogId")
				{
					$arrData[$index][$iColumn]= $reqCatalogId;
				}
				elseif($arrkolomdata[$iColumn]["val"] == "reqLineNo")
				{
					$reqDataLineNo= $reqDataLineNo + 1;
					$arrData[$index][$iColumn]= $reqDataLineNo;
				}
				elseif($arrkolomdata[$iColumn]["val"] == "reqCheckDelete")
				{
					$arrData[$index][$iColumn]= "";
				}
				else
					$arrData[$index][$iColumn]= $set->getField($arrkolomdata[$iColumn]["field"]);
			}
			$index++;
		}
		$reqCheckCatalogData= $reqCheckCatalogValue;
	}

	if($index == 0)
	{
		$datajson= array('', '', '1', '', '', '');
		array_push($arrData, $datajson);
	}
	echo json_encode($arrData);
}
elseif($reqMode == "asesor")
{
	$arr_json = array();

	if(!empty($reqPencarian))
	{
		$statement= "";
		$statement= " AND ( UPPER(EMAIL) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(NAMA) LIKE '%".strtoupper($reqPencarian)."%' ) ";
		if(!empty($reqRowId))
		{
			$statement.= " AND ASESOR_ID NOT IN (".$reqRowId.")";
		}

		$set= new Asesor();
		$set->selectByParams(array(), -1, -1, $statement);
		$set->firstRow();
		// echo $set->query;exit();
		$arrColumn = array("ASESOR_ID", "CHECK", "NO_SK", "TIPE_NAMA", "NAMA", "ALAMAT", "EMAIL", "TELEPON", "STATUS_KET");
		
		for($i=0;$i<count($arrColumn);$i++)
		{
			$kolom = $arrColumn[$i];
			$arr_json[$kolom] = $set->getField($kolom);
		}
	}
	else if(!empty($reqAsesorId))
	{
		// $statement.= " AND ASESOR_ID =".$reqAsesorId;
		$statement.= " AND ASESOR_ID IN (".$reqAsesorId.")";

		if(!empty($reqRowId))
		{
			$statement.= " AND ASESOR_ID NOT IN (".$reqRowId.")";
		}

		$set= new Asesor();
		$set->selectByParams(array(), -1, -1, $statement);
		$set->firstRow();
		// echo $set->query;exit();
		$arrColumn = array("ASESOR_ID", "CHECK", "NO_SK", "TIPE_NAMA", "NAMA", "ALAMAT", "EMAIL", "TELEPON", "STATUS_KET");

		for($i=0;$i<count($arrColumn);$i++)
		{
			$kolom = $arrColumn[$i];
			$arr_json[$kolom] = $set->getField($kolom);

		}
	

	}


	echo json_encode($arr_json);
}
?>