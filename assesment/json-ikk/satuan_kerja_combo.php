<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
// $reqRowMode = httpFilterGet("reqRowMode");

$set = new Kelautan();
$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";
$set->selectByParamsSatuanKerja(array(), 70, 0, $statementAktif.$statement);
// echo $set->query;exit;
$arr_json = array();
$i = 0;
while($set->nextRow())
{
	$arr_json[$i]['id'] = $set->getField("ID");
	$arr_json[$i]['label'] = $set->getField("NAMA");
	$arr_json[$i]['desc'] = $set->getField("NAMA")."<br/><label style='font-size:12px'>".$set->getField("SATUAN_KERJA_NAMA_DETIL")."</label>";
	$i++;
}
echo json_encode($arr_json);
?>