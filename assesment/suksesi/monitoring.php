<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Satker.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

$reqId = httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");
$reqKeterangan = httpFilterGet("reqKeterangan");
$reqMode = httpFilterGet("reqMode");
$reqTipe = httpFilterGet("reqTipe");
$reqBulan = httpFilterGet("reqBulan");
$reqTahun = httpFilterGet("reqTahun");

if($reqId == "")
{
	$userSatkerRencanaSuksesi= $userLogin->userSatkerRencanaSuksesi;
	//userSatkerIkk;userSatkerPengembanganSdm;userSatkerPolaKarir;userSatkerEvaluasiKinerja;userSatkerTugasBelajar;userSatkerRencanaSuksesi
	if($userSatkerRencanaSuksesi == ""){}
	else
	{
	$reqId = $userLogin->userSatkerId;
	$set= new Satker();
	$set->selectByParams(array("SATKER_ID"=>$reqId),-1,-1);
	$set->firstRow();
	$reqKeterangan= $set->getField("NAMA");
	unset($set);
	}
}
		
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

/* SET MENU */
if($_SESSION["ssUsr_reqMode"] == "")
{
	$_SESSION["ssUsr_reqMode"] = $reqMode;	
}
if($reqMode == ""){
	$reqMode = $_SESSION["ssUsr_reqMode"];
}
else{
	$_SESSION["ssUsr_reqMode"] = $reqMode;
}
/* END OF SET MENU */


/* SET INFORMATION */
if($_SESSION["ssUsr_SatkerId"] == ""){
	$_SESSION["ssUsr_SatkerId"] = $reqId;
}

if($reqRowId == 1)
	$_SESSION["ssUsr_SatkerId"]= $reqId;

if($reqId == "" && $_SESSION["ssUsr_SatkerId"] !== "")
{
	$reqId = $_SESSION["ssUsr_SatkerId"];
}
else{
	$_SESSION["ssUsr_SatkerId"] = $reqId;
}


if($_SESSION["ssUsr_Satker"] == ""){
	$_SESSION["ssUsr_Satker"] = $reqKeterangan;
}

if($reqKeterangan == ""){
	$reqKeterangan = $_SESSION["ssUsr_Satker"];
}
else{
	$_SESSION["ssUsr_Satker"] = $reqKeterangan;
}

if($reqKeterangan == '') $reqKeterangan = 'Semua Satuan Kerja';
/* END OF SET INFORMATION */

if($reqId == 0)
	$reqId = "";

switch($reqMode)
{
	// START MENUU BAWAH
	case "pegawai" :
		$link = "pegawai.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "pegawai_hasil_penilaian" :
		$link = "pegawai_hasil_penilaian.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	case "pegawai_penilaian" :
		$link = "pegawai_penilaian.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "rencana_suksesi" :
		$link = "rencana_suksesi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "hukuman" :
		$link = "hukuman.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "master_unsur_penilaian" :
		$link = "master_unsur_penilaian.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "rumpun_jabatan" :
		$link = "rumpun_jabatan.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "master_jabatan" :
		$link = "master_jabatan.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "formula_suksesi" :
		$link = "formula_suksesi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "formula_jabatan_target" :
		$link = "formula_jabatan_target.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title></title>
<head>
</head>
<script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script>
<style type="text/css">
html {overflow: auto;}
html, body, div, iframe {margin: 0px; padding: 0px; height: 100%; border: none;}
iframe {display: block; width: 100%; border: none; overflow-y: hidden; overflow-x: hidden;}
</style> 

<body>
<iframe src="<?=$link?>" name="menuMonitoring"></iframe>

</body>
</html>