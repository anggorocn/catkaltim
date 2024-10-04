<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/RekapSehat.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$statement= " AND B.UJIAN_PEGAWAI_DAFTAR_ID = ".$reqPegawaiId;
$set = new RekapSehat();
$set->selectByParamsGrafikKraepelinBaru(array(), -1, -1, $reqId, $statement);
$set->firstRow();
// echo $set->query;exit();
$tempNipPegawai= $set->getField("NIP_BARU");
$tempNamaPegawai= $set->getField("NAMA_PEGAWAI");

$arrWarna= array("1E90FF", "b30000");

$result= "";
$result= array();

$soal= 40;
$rows= "";
$rows= array();
$rows['name'] = $tempNamaPegawai;
$rows['color'] = "#".$arrWarna[0];
for($i=1; $i<=$soal; $i++)
{
	// echo $set->getField("Y_DATA1");exit();
	$rows['data'][] = (float)$set->getField("Y_DATA".$i);
}
array_push($result,$rows);
// $index++;
print json_encode($result);
?>