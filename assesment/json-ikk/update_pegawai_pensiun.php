<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/UpdatePegawaiApi.php");
ini_set('max_execution_time', 3000000);
// echo "Sasasa"; exit;
$reqId 		= httpFilterRequest("reqId");
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}
	$MasukkanData= new UpdatePegawaiApi();
	if($MasukkanData->updatepensiun()){
		echo '1';
	}

exit;

?>