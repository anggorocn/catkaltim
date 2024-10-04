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
	$userSatkerPolaKarir= $userLogin->userSatkerPolaKarir;
	//userSatkerIkk;userSatkerPengembanganSdm;userSatkerPolaKarir;userSatkerEvaluasiKinerja;userSatkerTugasBelajar;userSatkerRencanaSuksesi
	if($userSatkerPolaKarir == ""){}
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
	
	//START INDEKS KESENJANGAN	
	case "potensi" :
		$link = "potensi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	case "kompetensi" :
		$link = "kompetensi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	case "general_ikk" :
		$link = "general_ikk.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	// START MENUU BAWAH
	case "analisis_kebutuhan_pelatihan" :
		$link = "analisis_kebutuhan_pelatihan.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
 	
  	// START MENU LAPORAN
	case "laporan_diklat" :
		$link = "laporan_diklat.php";
	break;
	
	// START MENU MASTER
	case "master_standar_kompetensi_jabatan" :
		$link = "master_standar_kompetensi_jabatan.php";
	break;
	case "master_kamus_kompetensi" :
		$link = "master_kamus_kompetensi.php";
	break;
	 
	// START TALENT POOL
	case "grafik_nine_box_talent" :
		$link = "grafik_nine_box_talent.php";
	break;
	case "tabel_nine_box_talent" :
		$link = "tabel_nine_box_talent.php";
	break;
	
	 
	case "masterUser" :
		$link = "masterUser.php";
	break;
	case "masterUserGroup" :
		$link = "masterUserGroup.php";
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