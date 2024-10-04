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

$reqId= httpFilterGet("reqId");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqTahun= httpFilterGet("reqTahun");

$set= new Grafik();
$statement = " AND X.JADWAL_TES_ID = ".$reqJadwalTesId." AND A.PEGAWAI_ID = ".$reqId;
	
$field = array("NO", "NAMA", "NAMA_JAB_STRUKTURAL", "SATKER", "JPM", "IKK");
// $sOrder = " ORDER BY COALESCE(X.NILAI_POTENSI,0) DESC, COALESCE(Y.NILAI_KOMPETENSI,0) DESC ";
// $set->selectByParamsPersonalTalentPoolSkpJPMPegawai(array(), -1, -1, $statement, $sOrder);
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
/*array("x" => 100, "y" => 80, "myData" => "GELLWYNN DANIEL HAMZAH JUSUF"),
array("x" => 90, "y" => 70, "myData" => "sdasd");*/

echo json_encode($arr_json);
die();

?>