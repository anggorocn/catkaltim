<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalKelompokRuangan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqRowId= httpFilterPost("reqRowId");

$reqJadwalKelompokRuanganId= $_POST["reqJadwalKelompokRuanganId"];
$reqKelompokId= $_POST["reqKelompokId"];
$reqRuanganId= $_POST["reqRuanganId"];

if($reqMode == "insert")
{
	for($i=0; $i < count($reqJadwalKelompokRuanganId); $i++)
	{
		//if($reqAsesorId[$i] == ""){}
		//else
		//{
			$set_detil= new JadwalKelompokRuangan();
			$set_detil->setField('JADWAL_TES_ID', $reqId);
			$set_detil->setField('JADWAL_ACARA_ID', $reqRowId);
			$set_detil->setField('KELOMPOK_ID', $reqKelompokId[$i]);
			$set_detil->setField('RUANGAN_ID', $reqRuanganId[$i]);
			$set_detil->setField('JADWAL_KELOMPOK_RUANGAN_ID',$reqJadwalKelompokRuanganId[$i]);
			
			if($reqJadwalKelompokRuanganId[$i] == "")
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
			//echo $set_detil->query;exit;
		//}
	}
	echo "Data berhasil disimpan";
}
?>