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

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set = new RekapSehat();
$set->selectByParamsMonitoringBigFive(array(), -1, -1, $reqId, $statement);
$set->firstRow();
// echo $set->query;exit();
$tempNipPegawai= $set->getField("NIP_BARU");
$tempNamaPegawai= $set->getField("NAMA_PEGAWAI");

$arrbigfive= bigfive();
$jumlahbigfive= count($arrbigfive);

$result= [];
$soal= 4;
for($x=0; $x < $jumlahbigfive; $x++)
{
	$rows= [];
	$rows['name'] = $arrbigfive[$x]["id"];
	$rows['color'] = "#".$arrbigfive[$x]["color"];
	for($i=0; $i < $jumlahbigfive; $i++)
	{
		if($x == $i)
			$rows['data'][] = (float)$set->getField("PERSEN_".$arrbigfive[$x]["id"]);
		else
			$rows['data'][] = null;
	}
	array_push($result,$rows);
}
print json_encode($result);
?>