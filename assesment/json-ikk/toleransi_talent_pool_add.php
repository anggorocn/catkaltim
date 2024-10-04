<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/ToleransiTalentPool.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTahun= httpFilterGet("reqTahun");
$reqToleransiY= httpFilterGet("reqToleransiY");
$reqToleransiX= httpFilterGet("reqToleransiX");

$reqSkpX0= httpFilterGet("reqSkpX0");
$reqGmY0= httpFilterGet("reqGmY0");
$reqSkpX1= httpFilterGet("reqSkpX1");
$reqGmY1= httpFilterGet("reqGmY1");
$reqSkpX2= httpFilterGet("reqSkpX2");
$reqGmY2= httpFilterGet("reqGmY2");

$statement= " AND A.TAHUN = ".$reqTahun;
$set= new ToleransiTalentPool();
$jumlah_data= $set->getCountByParams(array(), $statement);
$set->setField("TAHUN", $reqTahun);
$set->setField("TOLERANSI_Y", $reqToleransiY);
$set->setField("TOLERANSI_X", $reqToleransiX);

$set->setField("SKP_X0", ValToNullDB($reqSkpX0));
$set->setField("GM_Y0", ValToNullDB($reqGmY0));
$set->setField("SKP_X1", ValToNullDB($reqSkpX1));
$set->setField("GM_Y1", ValToNullDB($reqGmY1));
$set->setField("SKP_X2", ValToNullDB($reqSkpX2));
$set->setField("GM_Y2", ValToNullDB($reqGmY2));

if($jumlah_data == 0)
{
	if($set->insert())
	echo "1";
}
else
{
	if($set->update())
	echo "1";
}
?>