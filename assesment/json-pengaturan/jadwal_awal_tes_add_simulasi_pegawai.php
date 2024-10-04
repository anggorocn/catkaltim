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

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqRowId= httpFilterPost("reqRowId");
$reqBatasPegawai= httpFilterPost("reqBatasPegawai");

$reqJadwalPegawaiId= $_POST["reqJadwalPegawaiId"];
$reqPegawaiId= $_POST["reqPegawaiId"];
$reqPegawaiSatkerTesId= $_POST["reqPegawaiSatkerTesId"];
$reqPegawaiTanggalTesId= $_POST["reqPegawaiTanggalTesId"];
$reqPegawaJabatan= $_POST["reqPegawaJabatan"];
$reqStatus= 1;

if($reqMode == "insert")
{
	$set= new JadwalAwalTesSimulasi();
	$set->setField('JADWAL_AWAL_TES_SIMULASI_ID', $reqRowId);
	$set->setField('BATAS_PEGAWAI', $reqBatasPegawai);
	$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	if($set->update())
	{
		echo $reqId."-Data berhasil disimpan";
	}
	else
	{
		echo "xxx-Data gagal disimpan";
	}
	unset($set);
	
	// $set= new JadwalAwalTesSimulasiPegawai();
	// $set->setField('JADWAL_AWAL_TES_ID', $reqId);
	// $set->delete();
	// unset($set);
	
	// for($i=0; $i < count($reqPegawaiId); $i++)
	// {
	// 	if($reqPegawaiId[$i] == ""){}
	// 	else
	// 	{
	// 		$set= new JadwalAwalTesSimulasiPegawai();
	// 		$set->setField('JADWAL_AWAL_TES_ID', $reqId);
	// 		$set->setField('JADWAL_AWAL_TES_SIMULASI_ID', $reqRowId);
	// 		$set->setField('PEGAWAI_ID', $reqPegawaiId[$i]);
	// 		$set->setField('STATUS',ValToNull($reqStatus));
	// 		$set->setField("LAST_CREATE_USER", $userLogin->idUser);
	// 		$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	// 		$set->insert();
	// 		unset($set);
	// 	}
	// }
}
?>