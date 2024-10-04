<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAwalTesPegawai.php");

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
$reqStatus= "";

if($reqMode == "insert")
{
	$set= new JadwalAwalTesPegawai();
	$set->setField('JADWAL_AWAL_TES_ID', $reqId);
	$set->delete();
	unset($set);

	for($i=0; $i < count($reqPegawaiId); $i++)
	{
		if($reqPegawaiId[$i] == ""){}
		else
		{
			$set= new JadwalAwalTesPegawai();
			$set->setField('JADWAL_AWAL_TES_ID', $reqId);
			$set->setField('PEGAWAI_ID', $reqPegawaiId[$i]);
			$set->setField('STATUS',ValToNullDB($reqStatus));
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set->insert();
			// echo $set->query;exit();
			unset($set);
		}
	}
	echo "-Data berhasil di simpan";
}
?>