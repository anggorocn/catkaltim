<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalTesSimulasiPegawai.php");
include_once("../WEB/functions/jexcelarray.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId = httpFilterGet("reqId");
// echo $reqId;exit;
$reqRowId = httpFilterGet("reqRowId");
$reqJadwalTesId = httpFilterGet("reqJadwalTesId");
$reqPegawaiId = httpFilterGet("reqPegawaiId");

$reqPencarian = httpFilterGet("reqPencarian");
$reqMode = httpFilterGet("reqMode");

if($reqMode == "viewdetil")
{
	$reqDataLineNo= 0;
	$arrkolomdata= "";
	$arrkolomdata= array();
	$arrkolomdata= getarray("jadwalacarapegawaiview");

	$arrJadwalPegawai="";
	$statement= " AND A.JADWAL_ASESOR_ID = ".$reqId;
	$set= new JadwalPegawai();
	$set->selectByParamsPegawai(array(), -1,-1, $statement);
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
elseif($reqMode == "pegawai")
{
	$arr_json = array();

	if(!empty($reqPencarian))
	{
		$statement= "";
		$statement= " AND A1.NIP_BARU = '".$reqPencarian."' AND A.JADWAL_TES_ID = '".$reqJadwalTesId."'";
		if(!empty($reqRowId))
		{
			$statement.= " AND A.PEGAWAI_ID NOT IN (".$reqRowId.")";
		}
		$set= new JadwalTesSimulasiPegawai();
		$set->selectByParamsPegawai(array(), -1, -1, $statement);
		$set->firstRow();
		// echo $set->query;exit();
		$arrColumn = array("PEGAWAI_ID", "CHECK", "PEGAWAI_NIP", "PEGAWAI_NAMA",  "PEGAWAI_ESELON", "PEGAWAI_JAB_STRUKTURAL");
		
		for($i=0;$i<count($arrColumn);$i++)
		{
			$kolom = $arrColumn[$i];
			$arr_json[$kolom] = $set->getField($kolom);
		}
	}
	else if(!empty($reqPegawaiId))
	{
		// $statement.= " AND ASESOR_ID =".$reqAsesorId;
		$statement.= " AND A.PEGAWAI_ID IN (".$reqPegawaiId.")";

		if(!empty($reqRowId) )
		{
			$statement.= " AND A.PEGAWAI_ID NOT IN (".$reqRowId.")";
		}

		$set= new JadwalTesSimulasiPegawai();
		$set->selectByParamsPegawai(array(), -1, -1, $statement);
		$set->firstRow();
		// echo $set->query;exit();
		$arrColumn = array("PEGAWAI_ID", "CHECK", "PEGAWAI_NIP", "PEGAWAI_NAMA",  "PEGAWAI_ESELON", "PEGAWAI_JAB_STRUKTURAL");
		
		for($i=0;$i<count($arrColumn);$i++)
		{
			$kolom = $arrColumn[$i];
			$arr_json[$kolom] = $set->getField($kolom);
		}
	

	}


	echo json_encode($arr_json);
}
?>