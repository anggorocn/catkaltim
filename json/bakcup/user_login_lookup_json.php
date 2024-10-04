<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UserLoginBase.php");


/* create objects */

$user_login = new UserLoginBase();

/* LOGIN CHECK */
if ($userLogin->checkUserLoginAdmin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$q = isset($_POST['q']) ? $_POST['q'] : ''; 

if($q == '')
	$statement='';
else
	$statement=" AND A.NAMA like '".$q."%' ";

$statement='';
	
$total= $user_login->getCountByParams(array(),$statement);
$arr_json['total'] = $total;

	$j=0;
	$user_login->selectByParams(array(),-1,-1,$statement);
	while($user_login->nextRow())
	{
		$arr_parent[$j]['id'] = $user_login->getField("USER_LOGIN_ID");
		$arr_parent[$j]['text'] = $user_login->getField("NAMA");
		$arr_parent[$j]['email'] = $user_login->getField("EMAIL");
		$j++;
	}
	$arr_json['rows'] = $arr_parent;
	
echo json_encode($arr_json);
?>