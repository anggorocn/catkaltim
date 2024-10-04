<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasi.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasiPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqPegawaiId= $_POST['reqPegawaiId'];
$reqPegawaiUrut= $_POST['reqPegawaiUrut'];
$reqJadwalTesId= $_POST['reqRowId'];
// print_r($reqJadwalTesId); exit;
// echo count($reqPegawaiUrut); exit;

for($i=0; $i<count($reqPegawaiId); $i++){
	$set= new JadwalAwalTesSimulasi();
	$set->setField('PEGAWAI_ID', $reqPegawaiId[$i]);
	$set->setField('NO_URUT', ValToNullDB($reqPegawaiUrut[$i]));	
	$set->setField('JADWAL_TES_ID', $reqJadwalTesId);	
	if($set->updateNoUrut())
	{
		echo $reqId."-Data berhasil disimpan";
	}
	else
	{
		echo "xxx-Data gagal disimpan";
	}
}
?>