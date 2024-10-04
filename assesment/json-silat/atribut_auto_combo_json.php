<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/Atribut.php");


/* create objects */

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqMode= httpFilterGet("reqMode");
$reqTahun= httpFilterGet("reqTahun");
$set = new Atribut();

// get the search term
$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";

$j=0;

//A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.TAHUN, A.NAMA, A.KETERANGAN, A.BOBOT, A.NILAI_STANDAR
$statement= " AND A.ATRIBUT_ID_PARENT NOT IN ('0') AND A.TAHUN = '".$reqTahun."'";
$set->selectByParams(array(), 5, 0, $statement." AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ");
//$set->selectByParams(array(), 5, 0);
//echo $set->query;exit;
while($set->nextRow())
{
	$arr_parent[$j]['id'] = $set->getField("ATRIBUT_ID");
	$arr_parent[$j]['label'] = $set->getField("NAMA");
	$arr_parent[$j]['desc'] = $set->getField("NAMA");
	$j++;
}
//echo json_encode($arr_parent, JSON_UNESCAPED_SLASHES);
echo json_encode($arr_parent);
?>