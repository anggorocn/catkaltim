<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/file.func.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

function getFileCariTes($directory, $arrNama, $findme)
{
	foreach ($directory[0] as $k=>$v)
	//foreach ($directory as $k=>$v)
	{
	 if(stripos($v, $findme) !== false)
	 {
		return $v;
		exit;
	 }
	}
}

$reqNama= httpFilterGet("reqNama");
//$reqNama= "Anjang Bangun Prasetio";
$tempKondisi=0;
$arrNama= "../ikk/INTEPRETASI ES 3";
$directory= getFolderTree($arrNama);
$findme=$reqNama;
$tempCari= getFileCari($directory, $arrNama, $findme);
//echo $tempCari;exit;
if($tempCari == "")
{
	$tempKondisi=1;
}
else
{
	$tempCari= $arrNama."/".$tempCari;
	echo $tempCari; exit;
}

if($tempKondisi == 1)
{
	$arrNama= "../ikk/INTEPRETASI ES 4";
	$directory= "";
	$directory= getFolderTree($arrNama);
	//print_r($directory);exit;
	$findme=$reqNama;
	$tempCari= getFileCari($directory, $arrNama, $findme);
	//print_r($tempCari);exit;
	//echo $tempCari."--".$findme."--".$arrNama;exit;
	if($tempCari == "")
	{
		$tempKondisi=1;
	}
	else
	{
		$tempCari= $arrNama."/".$tempCari;
		echo $tempCari; exit;
	}
}
//$directory= getFolderTree('INTEPRETASI ES 4');
//$htmlTree = createTree($directory["videos"]);
?>