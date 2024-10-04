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
	$reqTahun= "2016";

//$reqId= "0100000000";
$set= new Kelautan();
//$statement = setAndKondisi($reqId, "A.KODE_UNKER");

$statement = " AND A.SATKER_ID LIKE '".$reqId."%'";
$statement .= " AND ((CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) + (CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END)) > 0";

if($reqEselonId == "")
	$statement.="";
else
	$statement .= " AND SUBSTRING(A.LAST_ESELON_ID,1,1) = '".$reqEselonId."' ";
	
$field = array("NO", "NAMA", "NAMA_JAB_STRUKTURAL", "SATKER", "JPM", "IKK");
$sOrder = " ORDER BY X.NILAI DESC ";
$set->selectByParamsMonitoringTalentPoolPotensiKompetensi(array(), -1, -1, $statement, $sOrder, $reqTahun);
//echo $set->query;exit;
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