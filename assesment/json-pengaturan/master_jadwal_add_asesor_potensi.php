<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAsesorPotensi.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqRowId= httpFilterPost("reqRowId");
$reqKelompok= httpFilterPost("reqKelompok");
$reqRuang= httpFilterPost("reqRuang");

$reqJadwalAsesorPotensiId= $_POST["reqJadwalAsesorPotensiId"];
$reqAsesorId= $_POST["reqAsesorId"];
$reqJadwalKelompokRuanganId= $_POST["reqJadwalKelompokRuanganId"];
$reqKeterangan= $_POST["reqKeterangan"];

if($reqMode == "insert")
{
	for($i=0; $i < count($reqJadwalAsesorPotensiId); $i++)
	{
		if($reqAsesorId[$i] == ""){}
		else
		{
			$set_detil= new JadwalAsesorPotensi();
			$set_detil->setField('JADWAL_TES_ID', $reqId);
			$set_detil->setField('JADWAL_ACARA_ID', $reqRowId);
			$set_detil->setField('KELOMPOK', $reqKelompok);
			$set_detil->setField('RUANG', $reqRuang);
			$set_detil->setField('ASESOR_ID', $reqAsesorId[$i]);
			$set_detil->setField('JADWAL_KELOMPOK_RUANGAN_ID', ValToNullDB($reqJadwalKelompokRuanganId[$i]));
			$set_detil->setField('KETERANGAN', $reqKeterangan[$i]);
			$set_detil->setField('JADWAL_ASESOR_POTENSI_ID',$reqJadwalAsesorPotensiId[$i]);
			
			if($reqJadwalAsesorPotensiId[$i] == "")
			{
				$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
				$set_detil->insert();
			}
			else 
			{
				$set_detil->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
				$set_detil->update();
			}
		}
	}
	echo "Data berhasil disimpan";
}
?>