<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/kode.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/

$reqId= httpFilterGet("reqId");
$reqBulan= httpFilterGet("reqBulan");
$reqBulan= (int)$reqBulan;
$reqTahun= httpFilterGet("reqTahun");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$aColumns= array("nmunit", "bulan", "tahun", "kdiku", "nmiku", "satuan", "target", "capaian", "presentase");

$tempId= setKode($reqId);
$string = file_get_contents("http://kinerjaku.kkp.go.id/2015/kinerjakuskp.php?bulan=".$reqBulan."&tahun=".$reqTahun."&unitid=".$tempId."&userid=skp&token=0cc175b9c0f1b6a831c399e269772661");
$data_json=json_decode($string,true);
//print_r($data_json);exit;

foreach ($data_json["dtkinerja"] as $key => $value) 
{
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		echo $value[$aColumns[$i]]."<br/>";
		//$row[] = $value[$aColumns[$i]];
	}
}
?>
