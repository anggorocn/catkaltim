<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/FileHandler.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PermohonanFile.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}
$reqId= httpFilterGet("reqId");

$setfile= new PermohonanFile();
$statement= " AND A.PERMOHONAN_FILE_ID = ".$reqId;
$setfile->selectByParams(array(), -1,-1, $statement);
// echo $setfile->query;exit;
$setfile->firstRow();
$linkfile= $setfile->getField("LINK_FILE");
// echo $linkfile;

$setfile->setField("PERMOHONAN_FILE_ID", $reqId);
if($setfile->delete())
{
	unlink($linkfile);
}
// echo $setfile->query;exit;
echo "1";
?>