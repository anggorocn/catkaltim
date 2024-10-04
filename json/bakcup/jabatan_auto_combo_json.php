<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Jabatan.php");


/* create objects */

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqCabang= httpFilterGet("reqCabang");

// get the search term
$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";

$jabatan_count = new Jabatan();
$jumlah = $jabatan_count->getCountByParams(array("CABANG_P3_ID" => $reqCabang));
if($jumlah > 0)
	$statement = " AND CABANG_P3_ID = ". $reqCabang;
else
	$statement = " AND COALESCE(CABANG_P3_ID, 0) = 0 ";
	
$j=0;
$set = new Jabatan();
$set->selectByParams(array(), 20, 0, $statement." AND (UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' OR UPPER(A.KODE) LIKE '%".strtoupper($search_term)."%') ");
//echo $set->query;exit;
//$set->selectByParams(array(), 5, 0);
//echo $set->query;exit;
while($set->nextRow())
{
	$arr_parent[$j]['id'] = $set->getField("JABATAN_ID");
	$arr_parent[$j]['label'] = $set->getField("NAMA");
	$arr_parent[$j]['desc'] = $set->getField("NAMA");
	$j++;
}
//echo json_encode($arr_parent, JSON_UNESCAPED_SLASHES);
echo json_encode($arr_parent);
?>