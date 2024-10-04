<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesSimulasiPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");

$reqJadwalPegawaiId= $_POST["reqJadwalPegawaiId"];
$reqPegawaiId= $_POST["reqPegawaiId"];
$reqPegawaiSatkerTesId= $_POST["reqPegawaiSatkerTesId"];
$reqPegawaiTanggalTesId= $_POST["reqPegawaiTanggalTesId"];
$reqPegawaJabatan= $_POST["reqPegawaJabatan"];

if($reqMode == "insert")
{
	$set= new JadwalTesSimulasiPegawai();
	$set->setField('JADWAL_TES_ID', $reqId);
	$set->delete();
	unset($set);
	
	for($i=0; $i < count($reqPegawaiId); $i++)
	{
		if($reqPegawaiId[$i] == ""){}
		else
		{
			$set= new JadwalTesSimulasiPegawai();
			$set->setField('JADWAL_TES_ID', $reqId);
			$set->setField('PEGAWAI_ID', $reqPegawaiId[$i]);
			$set->setField('STATUS',ValToNull($reqStatus));
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set->insert();
			unset($set);
		}
	}
	echo "Data berhasil disimpan";


}
?>