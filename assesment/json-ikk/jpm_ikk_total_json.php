<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* variable */
$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqEselonId= httpFilterGet("reqEselonId");

if($reqId == "")
	$statement='';
else
	$statement .= " AND AA.KODE_UNKER LIKE '%".$reqId."'";

if($reqEselonId == "")
	$statement.="";
elseif($reqEselonId == "9")
{
	$statement .= " AND AA.ESSELON NOT IN ('1','2','3','4','5','6','7','8') ";
}
else
	$statement .= " AND AA.ESSELON = '".$reqEselonId."' ";

$set= new PenilaianDetil();
$set->selectByParamsPersonalIkkJpm(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempJpm= $set->getField("NILAI_JPM_PERSEN");
$tempIkk= $set->getField("NILAI_IKK_PERSEN");
$tempId= 1;
unset($set);

$arrFinal = array(
"tempId" => $tempId, "tempJpm" => $tempJpm
, "tempIkk" => $tempIkk
);

echo json_encode($arrFinal);
?>