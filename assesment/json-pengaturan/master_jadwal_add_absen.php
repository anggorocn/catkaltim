<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAsesorPotensiPegawai.php");
include_once("../WEB/classes/base/JadwalPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqPegawaiId= httpFilterPost("reqPegawaiId");

$reqJadwalTesId= $_POST["reqJadwalTesId"];
$reqJadwalAcaraId= $_POST["reqJadwalAcaraId"];
$reqPenggalianId= $_POST["reqPenggalianId"];
$reqJadwalPegawaiId= $_POST["reqJadwalPegawaiId"];
$reqJadwalAsesorId= $_POST["reqJadwalAsesorId"];
$reqJadwalAsesorPotensiPegawaiId= $_POST["reqJadwalAsesorPotensiPegawaiId"];
$reqJadwalAsesorPotensiId= $_POST["reqJadwalAsesorPotensiId"];
$reqAsesorPotensiId= $_POST["reqAsesorPotensiId"];

if($reqMode == "insert")
{
	for($i=0; $i < count($reqPenggalianId); $i++)
	{
		if($reqPenggalianId[$i] == 0)
		{
			$set= new JadwalAsesorPotensiPegawai();
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField('JADWAL_ASESOR_POTENSI_PEGAWAI_ID', $reqJadwalAsesorPotensiPegawaiId[$i]);
			$set->setField('JADWAL_ASESOR_POTENSI_ID', $reqJadwalAsesorPotensiId[$i]);
			$set->setField('JADWAL_TES_ID', $reqJadwalTesId[$i]);
			$set->setField('JADWAL_ACARA_ID', $reqJadwalAcaraId[$i]);
			$set->setField('ASESOR_ID', $reqAsesorPotensiId[$i]);
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");

			if($reqJadwalAsesorPotensiPegawaiId[$i] == "")
			{
				$set->insert();
			}
			else
			{
				$set->update();
			}
		}
		else
		{
			$set= new JadwalPegawai();
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField('JADWAL_PEGAWAI_ID', $reqJadwalPegawaiId[$i]);
			$set->setField('PENGGALIAN_ID', $reqPenggalianId[$i]);
			$set->setField('JADWAL_ASESOR_ID', $reqJadwalAsesorId[$i]);
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");

			if($reqJadwalPegawaiId[$i] == "")
			{
				$set->insert();
			}
			else
			{
				$set->update();
			}
			// echo $set->query;
		}

	}
	echo "Data berhasil disimpan";
}
?>