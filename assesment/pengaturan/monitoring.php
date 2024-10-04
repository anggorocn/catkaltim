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
	$reqId = $userLogin->userSatkerId;
	$set= new Satker();
	$set->selectByParams(array("SATKER_ID"=>$reqId),-1,-1);
	$set->firstRow();
	$reqKeterangan= $set->getField("NAMA");
	unset($set);
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

if($reqId == ""){
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
	case "peraturan" :
		$link = "peraturan.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "atribut" :
		$link = "atribut.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "indikator_penilaian" :
		$link = "indikator_penilaian.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "formula_assesment" :
		$link = "formula_assesment.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "jabatan_atribut_potensi" :
		$link = "jabatan_atribut_potensi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "jabatan_atribut_kompetensi" :
		$link = "jabatan_atribut_kompetensi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "training" :
		$link = "training.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	// START MENUU BAWAH
	case "user_app" :
		$link = "user_app.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "user_group" :
		$link = "user_group.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	// START MENU MASTER
	case "master_jenis_hukuman" :
		$link = "master_jenis_hukuman.php";
	break;
	case "master_tingkat_hukuman" :
		$link = "master_tingkat_hukuman.php";
	break;
	case "master_peraturan" :
		$link = "master_peraturan.php";
	break;
	
	// START MENU PENILAIAN
	case "kelompok" :
		$link = "master_kelompok.php";
	break;
	case "ruangan" :
		$link = "master_ruangan.php";
	break;

	case "pejabat" :
		$link = "pejabat.php";
	break;
	
	case "asesor" :
		$link = "master_asesor.php";
	break;

	case "histori_asesor" :
		$link = "histori_asesor.php";
	break;
	case "penggalian" :
		$link = "master_penggalian.php";
	break;
	case "penilaian" :
		$link = "master_penggalian_penilaian.php";
	break;

	case "jadwal" :
		$link = "master_jadwal.php";
	break;

	case "jadwal_absen" :
		$link = "master_jadwal.php?reqJenis=1";
	break;

	case "jadwal_multi_absen" :
		$link = "master_jadwal.php?reqJenis=2";
	break;

	case "jadwal_hasil_cat" :
		$link = "master_jadwal.php?reqJenis=2";
	break;

	case "jadwal_awal_tes" :
		$link = "jadwal_awal_tes.php";
	break;
	
	case "assesment_meeting" :
		$link = "assesment_meeting.php";
	break;

	case "tipe_soal" :
		$link = "tipe_soal.php";
	break;
	
	case "bank_soal" :
		$link = "bank_soal.php";
	break;

	case "bank_tipe_soal" :
		$link = "bank_tipe_soal.php?reqTipeUjianId=$reqTipe";
	break;

	case "satker_internal" :
		$link = "satker_internal.php";
	break;

	case "satker_eksternal" :
		$link = "satker_eksternal.php";
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