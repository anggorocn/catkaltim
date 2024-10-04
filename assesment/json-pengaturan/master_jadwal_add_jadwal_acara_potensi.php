<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqRowId= httpFilterPost("reqRowId");
$reqJenisId= httpFilterPost("reqJenisId");
$reqJadwalNama= httpFilterPost("reqJadwalNama");
$reqPenggalianStatusGroup= httpFilterPost("reqPenggalianStatusGroup");
$reqPenggalianId= httpFilterPost("reqPenggalianId");
$reqPukulAwalTemp= httpFilterPost("reqPukulAwalTemp");
$reqPukulAwal= httpFilterPost("reqPukulAwal");
$reqPukulAkhir= httpFilterPost("reqPukulAkhir");
$reqKelompokJumlah= httpFilterPost("reqKelompokJumlah");
$reqJumlahLoopAsesor= httpFilterPost("reqJumlahLoopAsesor");
$reqWaktu= httpFilterPost("reqWaktu");

$reqAsesorId= $_POST["reqAsesorId"];
//exit;
if($reqMode == "insert")
{
	// mulai baru hapus dulu
	if($reqPukulAwalTemp == ""){}
	else
	{
		if($reqPenggalianId == "0")
		{
			$set_delete= new JadwalTesSimulasiAsesor();
			$set_delete->setField("JADWAL_TES_ID", $reqId);
			$set_delete->setField("PENGGALIAN_ID", $reqPenggalianId);
			$set_delete->deletePenggalianPotensi();
			//echo $set_delete->query;exit;
			unset($set_delete);
		}
		else
		{
			$set_delete= new JadwalTesSimulasiAsesor();
			$set_delete->setField("JADWAL_TES_ID", $reqId);
			$set_delete->setField("PENGGALIAN_ID", $reqPenggalianId);
			$set_delete->deletePenggalian();
			unset($set_delete);
		}
	}
	
	// kalau 1 maka simpan data jadwal acara
	if($reqJenisId == "1")
	{
		$set_detil= new JadwalTesSimulasiAsesor();
		$set_detil->setField("KELOMPOK_JUMLAH", ValToNullDB($req));
		$set_detil->setField("ASESOR_ID", ValToNullDB($req));
		$set_detil->setField("JADWAL_NAMA", $reqJadwalNama);
		$set_detil->setField("PENGGALIAN_ID", ValToNullDB($reqPenggalianId));
		$set_detil->setField("WAKTU", $req);
		$set_detil->setField("PUKUL_AWAL", $reqPukulAwal);
		$set_detil->setField("PUKUL_AKHIR", $reqPukulAkhir);
		$set_detil->setField("JADWAL_TES_ID", $reqId);
		$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
		$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
		$set_detil->insert();
		unset($set_detil);
	}
	else
	{
		//kalau group penggalian status 1 maka looping tanggal akhir sesuai jumlah asesor
		if($reqPenggalianStatusGroup == "1")
		{
			$tempPukulAkhir= "";
			for($x=0; $x < $reqJumlahLoopAsesor; $x++)
			{
				if($tempPukulAkhir == "")
				{
					$tempPukulAwal= $reqPukulAwal;
					$tempPukulAkhir= addTwoTimes($reqPukulAwal, $reqWaktu);
				}
				else
				{
					$tempPukulAwal= $tempPukulAkhir;
					$tempPukulAkhir= addTwoTimes($tempPukulAkhir, $reqWaktu);
				}
					
				for($i=0; $i < count($reqAsesorId); $i++)
				{
					if($reqAsesorId[$i] == ""){}
					else
					{
						$set_detil= new JadwalTesSimulasiAsesor();
						$set_detil->setField("KELOMPOK_JUMLAH", $reqKelompokJumlah);
						$set_detil->setField("ASESOR_ID", ValToNullDB($reqAsesorId[$i]));
						$set_detil->setField("JADWAL_NAMA", $req);
						$set_detil->setField("PENGGALIAN_ID", ValToNullDB($reqPenggalianId));
						$set_detil->setField("WAKTU", $reqWaktu);
						$set_detil->setField("PUKUL_AWAL", $tempPukulAwal);
						$set_detil->setField("PUKUL_AKHIR", $tempPukulAkhir);
						$set_detil->setField("JADWAL_TES_ID", $reqId);
						$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
						$set_detil->insert();
						unset($set_detil);
					}
				}
			}
		}
		else
		{
			for($i=0; $i < count($reqAsesorId); $i++)
			{
				if($reqAsesorId[$i] == ""){}
				else
				{
					$set_detil= new JadwalTesSimulasiAsesor();
					$set_detil->setField("KELOMPOK_JUMLAH", $reqKelompokJumlah);
					$set_detil->setField("ASESOR_ID", ValToNullDB($reqAsesorId[$i]));
					$set_detil->setField("JADWAL_NAMA", $req);
					$set_detil->setField("PENGGALIAN_ID", ValToNullDB($reqPenggalianId));
					$set_detil->setField("WAKTU", $req);
					$set_detil->setField("PUKUL_AWAL", $reqPukulAwal);
					$set_detil->setField("PUKUL_AKHIR", $reqPukulAkhir);
					$set_detil->setField("JADWAL_TES_ID", $reqId);
					$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
					$set_detil->insert();
					unset($set_detil);
				}
			}
		}
	}
	echo "Data berhasil disimpan";
}
?>