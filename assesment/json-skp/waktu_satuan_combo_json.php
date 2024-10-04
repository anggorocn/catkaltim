<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/WaktuSatuan.php");


/* create objects */
$waktu_satuan = new WaktuSatuan();

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/
	
$waktu_satuan->selectByParams(array(), -1, -1, "", " ORDER BY KODE ASC");

$arr_json = array();
$i = 0;
while($waktu_satuan->nextRow())
{
	$arr_json[$i]['id'] = $waktu_satuan->getField("KODE");
	$arr_json[$i]['text'] = $waktu_satuan->getField("KODE");
	
	$i++;
}
echo json_encode($arr_json);
?>