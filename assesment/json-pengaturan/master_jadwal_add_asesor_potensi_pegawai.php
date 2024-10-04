<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAsesorPotensiPegawai.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqRowId= httpFilterPost("reqRowId");
$reqPenggalianId= httpFilterPost("reqPenggalianId");
$reqJadwalTesId= httpFilterPost("reqJadwalTesId");
$reqJadwalAcaraId= httpFilterPost("reqJadwalAcaraId");
$reqAsesorId= httpFilterPost("reqAsesorId");

$reqJadwalAsesorPotensiPegawaiId= $_POST["reqJadwalAsesorPotensiPegawaiId"];
$reqPegawaiId= $_POST["reqPegawaiId"];
$reqPegawaiSatkerTesId= $_POST["reqPegawaiSatkerTesId"];
$reqPegawaiTanggalTesId= $_POST["reqPegawaiTanggalTesId"];
$reqPegawaJabatan= $_POST["reqPegawaJabatan"];

if($reqMode == "insert")
{
	for($i=0; $i < count($reqJadwalAsesorPotensiPegawaiId); $i++)
	{
		if($reqPegawaiId[$i] == ""){}
		else
		{
			$set_detil= new JadwalAsesorPotensiPegawai();
			$set_detil->setField('JADWAL_ASESOR_POTENSI_ID', $reqRowId);
			$set_detil->setField('PENGGALIAN_ID', ValToNullDB($reqPenggalianId));
			$set_detil->setField('JADWAL_TES_ID', ValToNullDB($reqJadwalTesId));
			$set_detil->setField('JADWAL_ACARA_ID', ValToNullDB($reqJadwalAcaraId));
			$set_detil->setField('ASESOR_ID', ValToNullDB($reqAsesorId));
			$set_detil->setField('PEGAWAI_ID', $reqPegawaiId[$i]);
			$set_detil->setField('JADWAL_ASESOR_POTENSI_PEGAWAI_ID',$reqJadwalAsesorPotensiPegawaiId[$i]);
			
			$tempStatusSimpan= "";
			if($reqJadwalAsesorPotensiPegawaiId[$i] == "")
			{
				$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
				if($set_detil->insert())
					$tempStatusSimpan= 1;
				//echo $set_detil->query;exit;
			}
			else 
			{
				$set_detil->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
				if($set_detil->update())
					$tempStatusSimpan= 1;
			}
			
			//triger data ke penilaian
			if($tempStatusSimpan == 1)
			{
				$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId[$i]." AND DATE_FORMAT(A.TANGGAL_TES, '%d-%m-%Y') = '".$reqPegawaiTanggalTesId[$i]."' AND A.SATKER_TES_ID = '".$reqPegawaiSatkerTesId[$i]."' AND A.ASPEK_ID = 1";
				$set= new Penilaian();
				$set->selectByParams(array(), -1,-1, $statement);
				$set->firstRow();
				$tempRowId= $set->getField("PENILAIAN_ID");
				//echo $set->query;exit;
				unset($set);
				
				$set_penilaian= new Penilaian();
				$set_penilaian->setField("TANGGAL_TES", dateToDBCheck($reqPegawaiTanggalTesId[$i]));
				$set_penilaian->setField("SATKER_TES_ID", $reqPegawaiSatkerTesId[$i]);
				$set_penilaian->setField("JABATAN_TES_ID", $reqPegawaJabatan[$i]);
				$set_penilaian->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
				$set_penilaian->setField("ASPEK_ID", "1");
				$set_penilaian->setField("ESELON", setNULL($tempPenilaianEselonId));
				$set_penilaian->setField("NAMA_ASESI", $tempPenilaianNamaAsesi);
				$set_penilaian->setField("METODE", $tempPenilaianMetode);
				$set_penilaian->setField("JADWAL_TES_ID", $reqId);
				$set_penilaian->setField("PENILAIAN_ID", $tempRowId);
				//$set_penilaian->setField("JADWAL_TES_ID", $tempRowId);
				
				$reqStatusSimpan= "";	
				if($tempRowId == "")
				{
					/*if($set_penilaian->insert())
					{
						$tempRowId= $set_penilaian->id;
					}*/
					//echo $set_penilaian->query;exit;
				}
				else
				{
					//if($set_penilaian->update()){}
				}
				
				
				$statement= " AND PENILAIAN_ID = ".$tempRowId." AND PEGAWAI_ID =".$reqPegawaiId[$i];
				$penilaian_detil= new PenilaianDetil();
				$jumlah_nilai= $penilaian_detil->getCountByParamsJumlahNilai(array(), $statement);
				//echo $penilaian_detil->query;exit;
				unset($penilaian_detil);
				
				if($jumlah_nilai > 0){}
				else
				{
					$penilaian_detil= new PenilaianDetil();
					$penilaian_detil->setField("PENILAIAN_ID", $tempRowId);
					$penilaian_detil->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
					$penilaian_detil->setField("JADWAL_TES_ID", $reqId);
					//$penilaian_detil->insertTrigerPotensi();
					//echo $penilaian_detil->errorMsg;exit;
					//echo $penilaian_detil->query;exit;
					unset($penilaian_detil);
				}
				
			}
			
		}
	}
	echo "Data berhasil disimpan";
}
?>