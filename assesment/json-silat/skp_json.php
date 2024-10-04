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
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqNilaiSkp= httpFilterPost("reqNilaiSkp");
$reqTahun= httpFilterPost("reqTahun");


// echo $reqMode;exit; 


$set->setField("NILAI_SKP", ValToNullDB($reqNilaiSkp));
$set->setField("SKP_TAHUN", $reqTahun);
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->setField("RIWAYAT_SKP_ID", $reqId);



// echo 'asdas';exit;
$reqSimpan= "";

if ($reqMode == "insert")
{
	if($set->insert())
	{
		$reqId= $set->id;
		$mode = 'Data berhasil disimpan';
		$reqSimpan= "1";
		//echo $set->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';

}
elseif($reqMode == "update")
{
	$set->setField("PEGAWAI_ID", $reqPegawaiId);
	
	if($set->update())
	{
		$mode = 'Data berhasil disimpan';
		$reqSimpan= "1";
	}
	else
		$mode = 'Data gagal disimpan';

}


	echo $reqPegawaiId."-".$mode;

?>