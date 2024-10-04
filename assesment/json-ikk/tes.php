<?
header('Content-Type: application/json');

/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* create objects */

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTahun= httpFilterGet("reqTahun");
$reqEselonId= httpFilterGet("reqEselonId");
$reqId= httpFilterGet("reqId");

if($reqTahun == "")
	$reqTahun= "2015";

//$reqId= "0100000000";
$set= new Kelautan();
$statement = setAndKondisi($reqId, "A.KODE_UNKER");

if($reqEselonId == "")
	$statement.="";
else
	$statement .= " AND A.KODE_ESELON = '".$reqEselonId."' ";
	
$field = array("NO", "NAMA", "NAMA_JAB_STRUKTURAL", "SATKER", "JPM", "IKK");
$sOrder = " ORDER BY X.IKK DESC ";
$set->selectByParamsMonitoringTalentPool(array(), -1, -1, $statement, $sOrder, $reqTahun);
echo $set->query;exit;
$i=0;
while($set->nextRow())
{
	$arrData[$i]= array("x" => (float)$set->getField("NILAI_X"), "y" => (float)$set->getField("NILAI_Y"), "myData" => $set->getField("NAMA"));
	$i++;
}
//print_r($arrData);exit;
$arr_json= $arrData;
/*array("x" => 100, "y" => 80, "myData" => "GELLWYNN DANIEL HAMZAH JUSUF"),
array("x" => 90, "y" => 70, "myData" => "sdasd");*/

echo json_encode($arr_json);
die();

?>