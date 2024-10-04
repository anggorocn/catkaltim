<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/RiwayatSkp.php");
// echo 'asda';exit;
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set= new RiwayatSkp();

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqRowId= httpFilterPost("reqRowId");
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqNilaiSkp= httpFilterPost("reqNilaiSkp");
$reqTahun= httpFilterPost("reqTahun");

// echo $reqMode;exit; 
$set->setField("NILAI_SKP", ValToNullDB($reqNilaiSkp));
$set->setField("SKP_TAHUN", $reqTahun);
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->setField("RIWAYAT_SKP_ID", $reqRowId);
$set->setField("PEGAWAI_ID", $reqPegawaiId);

$tempSimpan="";
if($reqMode == "SubmitSimpan")
{
	if($set->insert())
	{
		$reqId= $set->id;
		$mode = 'simpan';
		$tempSimpan='simpan';
	}
	else
		$tempSimpan='error';;

}
else
{
	if($set->update())
	{
		$tempSimpan='simpan';
	}
	else
	{
		$tempSimpan='error';
	}
}
// echo $set->query;exit();
echo $reqId."-Data Tersimpan-".$tempSimpan;
?>