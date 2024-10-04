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

$arrdata= array("D", "I", "S", "C");
$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set = new RekapSehat();
$set->selectByParamsMonitoringDisc(array(), -1, -1, $reqId, $statement);
$set->firstRow();
$valdata= array();
$indexdata=0;
// echo $set->query;exit();
for($x=1; $x<=3;$x++)
{
	for($y=0;$y<count($arrdata);$y++)
	{
		$modestatus= $arrdata[$y];
		$modestatuskondisi= $modestatus.$x;

		$field= $modestatus."_".$x;
		$nilai= $set->getField($field);
		$valdata[$indexdata][$field]= $nilai;

		$statementdetil= " AND STATUS_AKTIF = 1 AND MODE_STATUS = '".$modestatuskondisi."' AND NILAI = ".$nilai;
		$setdetil= new RekapSehat();
		$hasil= $setdetil->setkonversidisk(array(), $statementdetil);
		// if($x == 2 && $modestatus == "D")
		// {
		// 	// echo $modestatuskondisi."-".$field."-".$hasil;exit();
		// 	echo $setdetil->query;exit();
		// }
		unset($setdetil);
		$valdata[$indexdata][$field."_KONVERSI"]= $hasil;
	}
	$indexdata++;
	
}
unset($set);
// print_r($valdata);exit();

// $arrWarna= array("1E90FF", "b30000");
$arrWarna= array("1E90FF", "1E90FF", "1E90FF");

$result= "";
$result= array();

$soal= 40;
$rows= "";
$rows= array();

// for($x=0; $x<1;$x++)
for($x=0; $x<3;$x++)
{
	$rows['name'] = "";
	$rows['color'] = "#".$arrWarna[0];

	$x1= $x+1;

	for($y=0;$y<count($arrdata);$y++)
	{
		$modestatus= $arrdata[$y];
		$field= $modestatus."_".$x1."_KONVERSI";
		$nilai= $valdata[$x][$field];
		$rows['data'][$y] = (float)$nilai;
	}
	array_push($result,$rows);
	
	// $rows['data'][0] = (float)-5.3;
	// $rows['data'][1] = (float)3.5;
	// $rows['data'][2] = (float)-1.5;
	// $rows['data'][3] = (float)0.5;

}
// print_r($result);exit();
print json_encode($result);
?>