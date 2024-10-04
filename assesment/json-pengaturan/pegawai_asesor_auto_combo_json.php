<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalPegawai.php");

/* create objects */

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$set = new JadwalPegawai();
$reqId= httpFilterGet("reqId");

// get the search term
$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";

$statement= " ";
$j=0;
$set->selectByParamsLookupPegawai(array(), 10, 0, $statement." AND ( UPPER(A.NAME) LIKE '%".strtoupper($search_term)."%' OR A.NIP LIKE '%".strtoupper($search_term)."%') ");
//echo $set->query;exit;
while($set->nextRow())
{
	$arr_parent[$j]['id'] = $set->getField("ID");
	$arr_parent[$j]['label'] = "NIP: ".$set->getField("PEGAWAI_NIP").", Nama: ".$set->getField("PEGAWAI_NAMA");
	$arr_parent[$j]['desc'] = "NIP: ".$set->getField("PEGAWAI_NIP").", Nama: ".$set->getField("PEGAWAI_NAMA");
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