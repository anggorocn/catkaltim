<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/JabatanAtribut.php");
include_once("../WEB/classes/base-ikk/JabatanEselonAtribut.php");

/* create objects */

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$set = new JabatanEselonAtribut();
$reqId= httpFilterGet("reqId");

// get the search term
$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";

if($reqId == ""){}
else
$statement .= " AND A.SATKER_ID LIKE '".$reqId."%'";
//$statement .= " AND A.SATKER_ID = '".$reqId."'";

$j=0;

//$set->selectByParamsJabatan(array(), 10, 0, $statement." AND UPPER(B.NAMA) LIKE '%".strtoupper($search_term)."%' ");
$set->selectByParamsJabatanUnitKerja(array(), 10, 0, $statement." AND UPPER(A.POSITION) LIKE '%".strtoupper($search_term)."%' ");
//$set->selectByParams(array(), 5, 0);
//echo $set->query;exit;
while($set->nextRow())
{
	$arr_parent[$j]['id'] = $set->getField("JABATAN_ID");
	$arr_parent[$j]['label'] = $set->getField("JABATAN_NAMA");
	$arr_parent[$j]['desc'] = $set->getField("JABATAN_NAMA");
	$j++;
}

if($j == 0)
{
	$arr_parent[$j]['id'] = "";
	$arr_parent[$j]['label'] = "";
	$arr_parent[$j]['desc'] = "";
}

//echo json_encode($arr_parent, JSON_UNESCAPED_SLASHES);
echo json_encode($arr_parent);
?>