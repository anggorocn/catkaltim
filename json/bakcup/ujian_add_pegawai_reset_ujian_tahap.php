<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");
include_once("../WEB/classes/utils/UserLogin.php");

/* LOGIN CHECK  */
if ($userLogin->checkUserLoginAdmin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqUjianTahapId= httpFilterGet("reqUjianTahapId");

if($reqUjianTahapId == ""){}
else
{
	//INSERT DATA KALAU DATA BARU YG BELUM MASUK KE TRANSAKSI DETIL
	
	$set_detil= new UjianPegawai();
	$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
	$set_detil->setField("UJIAN_ID", $reqId);
	$set_detil->setField("UJIAN_TAHAP_ID", $reqUjianTahapId);
	$set_detil->resetUjianTahap();
	//echo $set_detil->query;exit;
	unset($set_detil);
	
}
echo "1";
?>