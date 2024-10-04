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
	$userSatkerPengembanganSdm= $userLogin->userSatkerPengembanganSdm;
	//userSatkerIkk;userSatkerPengembanganSdm;userSatkerPolaKarir;userSatkerEvaluasiKinerja;userSatkerTugasBelajar;userSatkerRencanaSuksesi
	if($userSatkerPengembanganSdm == ""){}
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
	$reqId= $_SESSION["ssUsr_SatkerId"];
}
else
{
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

if($reqId == 0)
	$reqId = "";

switch($reqMode)
{
	// START MENUU BAWAH
	case "master_atribut" :
		$link = "master_atribut.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "master_traning" :
		$link = "master_traning.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "pegawai" :
		$link = "pegawai.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "pegawai_beasiswa" :
		$link = "pegawai_beasiswa.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
 	case "diklat_analisa_kompetensi_bendel" :
		$link = "diklat_analisa_kompetensi_bendel.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "diklat_analisa_kompetensi" :
		$link = "diklat_analisa_kompetensi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "pelatihan_hcdp" :
		$link = "pelatihan_hcdp.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
	case "pengembangan_kompetensi" :
		$link = "pengembangan_kompetensi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "rekapitulasi_kompetensi" :
		$link = "rekapitulasi_kompetensi.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;

	case "rekap_pengembangan" :
		$link = "rekap_pengembangan.php?reqId=".$reqId."&reqKeterangan=".$reqKeterangan;
	break;
	
 	
  	// START MENU LAPORAN
	case "laporan_diklat" :
		$link = "laporan_diklat.php";
	break;
	 
	// START MENU MASTER
	case "masterJenisDiklat" :
		$link = "masterJenisDiklat.php";
	break;
	case "masterPerencanaanDiklat" :
		$link = "perencanaan_diklat.php";
	break;
	case "masterStandarKompetensi" :
		$link = "masterStandarKompetensi.php";
	break;
	case "masterDiklatSDPK" :
		$link = "masterStandarKompetensiFile.php";
		//$link = "perencanaan_diklat_sdpk.php";
	break;
	case "masterMataDiklat" :
		$link = "masterMataDiklat.php";
	break;
	
	case "masterKurikulum" :
		$link = "masterKurikulum.php";
	break;
	case "masterKamusKompetensiJabatan" :
		$link = "masterKamusKompetensiJabatan.php";
	break;
	case "masterTipe" :
		$link = "master_tipe_pelatihan.php";
	break;
	case "masterKategoriPelatihan" :
		$link = "master_kategori_pelatihan.php";
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