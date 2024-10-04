<?
header('Content-Type: application/json');

/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Grafik.php");

/* create objects */

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTahun= httpFilterGet("reqTahun");
$reqEselonId= httpFilterGet("reqEselonId");
$reqId= httpFilterGet("reqId");
$reqKdOrganisasiId= httpFilterGet("reqKdOrganisasiId");
$reqJenisJabatan= httpFilterGet("reqJenisJabatan");
$reqPencarian= httpFilterGet("reqPencarian");
$reqFormulaId= httpFilterGet("reqFormulaId");


if($reqTahun == "")
	$reqTahun= "2015";

//$reqId= "0100000000";
$set= new Grafik();

$statement = " AND A.SATKER_ID LIKE '".$reqId."%'";

// if($reqJenisJabatan == ""){}
// else
// {
//     if($reqEselonId == "")
//     {
//         $statement .= " AND ".jenisjabatanDb($reqJenisJabatan, "A.");
//     }
// }

// if($reqKdOrganisasiId == ""){}
// else
// {
//     $statement .= " AND A.KD_SATUAN_ORGANISASI = '".$reqKdOrganisasiId."'";
// }

// if($reqEselonId == ""){}
// else
// {
//     $statement .= " AND ".jejangjabatanDb($reqEselonId, "A.");
// }

if($reqPencarian == ""){}
else
{
	$statement.= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%') ";
}

if($reqFormulaId == "")
	$statement.="";
else
	$statement .= " AND X.FORMULA_ID = '".$reqFormulaId."' ";
	
$field = array("NO", "NAMA", "NAMA_JAB_STRUKTURAL", "SATKER", "JPM", "IKK");
$sOrder = " ORDER BY COALESCE(X.NILAI_POTENSI,0) DESC, COALESCE(Y.NILAI_KOMPETENSI,0) DESC ";
$set->selectByParamsMonitoringTalentPoolNew(array(), -1, -1, $statement, $sOrder, $reqTahun);
// echo $set->query;exit;
$i=0;
while($set->nextRow())
{
	$arrData[$i]= array("x" => (float)$set->getField("NILAI_X"), "y" => (float)$set->getField("NILAI_Y"), "myData" => $set->getField("NAMA"));
	$i++;
}

$arr_json= $arrData;
// print_r($arrData); exit;
/*array("x" => 100, "y" => 80, "myData" => "GELLWYNN DANIEL HAMZAH JUSUF"),
array("x" => 90, "y" => 70, "myData" => "sdasd");*/

echo json_encode($arr_json);
die();

?>