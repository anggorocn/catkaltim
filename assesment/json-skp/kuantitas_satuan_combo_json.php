<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/KuantitasSatuan.php");


/* create objects */
$kuantitas_satuan = new KuantitasSatuan();

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/
	
$kuantitas_satuan->selectByParams(array(), -1, -1, "", " ORDER BY KODE ASC");

$arr_json = array();
$i = 0;
while($kuantitas_satuan->nextRow())
{
	$arr_json[$i]['id'] = $kuantitas_satuan->getField("KODE");
	$arr_json[$i]['text'] = $kuantitas_satuan->getField("KODE");
	
	$i++;
}
echo json_encode($arr_json);
?>