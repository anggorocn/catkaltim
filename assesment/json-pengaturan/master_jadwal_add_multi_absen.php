<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasiPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterPost("reqId");
$reqInfoPegawaiId= $_POST["reqInfoPegawaiId"];
// print_r($reqPegawaiId);

for($i=0; $i < count($reqInfoPegawaiId); $i++)
{
	$reqPegawaiId= $reqInfoPegawaiId[$i];
	if(!empty($reqPegawaiId))
	{
		// echo $reqPegawaiId;
		$set= new JadwalPegawai();
		$set->setField("JADWAL_TES_ID", $reqId);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("LAST_CREATE_USER", $userLogin->idUser);
		$set->prosesJadwalAbsen();
		// echo $set->query;exit();

		$set= new JadwalAwalTesSimulasiPegawai();
		$set->setField("JADWAL_TES_ID", $reqId);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->updateAbsen();
	}
}
echo "1";
?>