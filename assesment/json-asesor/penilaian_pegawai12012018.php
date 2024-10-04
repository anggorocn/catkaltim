<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqJadwalTesId= httpFilterPost("reqJadwalTesId");
$reqRowId= httpFilterPost("reqRowId");
$reqJadwalPegawaiDetilId= $_POST["reqJadwalPegawaiDetilId"];
$reqAtributId= $_POST["reqAtributId"];
$reqIndikatorId= $_POST["reqIndikatorId"];
$reqLevelId= $_POST["reqLevelId"];
$reqKeterangan= $_POST["reqKeterangan"];
$reqJumlahKeterangan= $_POST["reqJumlahKeterangan"];

$reqMode= httpFilterPost("reqMode");

if($reqMode == "insert")
{
	$reqStatusSimpan= "";
	//satker tes ambil bapak nya dulu.
	$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqRowId;
	$set_jadwal= new JadwalPegawaiDetil();
	$set_jadwal->selectByParamsPenilaian(array(), -1,-1, $statement);
//echo $set_jadwal->query;exit;
	//$set_jadwal->firstRow();
	while($set_jadwal->nextRow())
	{
		$tempPenilaianTanggalTes= dateToPageCheck($set_jadwal->getField("TANGGAL_TES"));
		$tempPenilaianSatkerTesId= $set_jadwal->getField("SATKER_TES_ID");
		$tempPenilaianJabatan= $set_jadwal->getField("JABATAN_INI_TES");
		$tempPenilaianPegawaiId= $set_jadwal->getField("PEGAWAI_ID");
		$tempPenilaianAspekId= $set_jadwal->getField("ASPEK_ID");
		$tempPenilaianEselonId= $set_jadwal->getField("ESELON_ID");
		$tempPenilaianNamaAsesi= $set_jadwal->getField("");
		$tempPenilaianMetode= $set_jadwal->getField("METODE");
		$tempPenilaianAspekId= $set_jadwal->getField("ASPEK_ID");
		$tempPenilaianPegawaiId= $set_jadwal->getField("PEGAWAI_ID");
		
		$statement= " AND A.PEGAWAI_ID = ".$tempPenilaianPegawaiId." AND DATE_FORMAT(A.TANGGAL_TES, '%d-%m-%Y') = '".$tempPenilaianTanggalTes."' AND A.SATKER_TES_ID = '".$tempPenilaianSatkerTesId."' AND A.ASPEK_ID = ".$tempPenilaianAspekId;
		$set= new Penilaian();
		$set->selectByParams(array(), -1,-1, $statement);
		$set->firstRow();
		$tempRowId= $set->getField("PENILAIAN_ID");
		//echo $set->query;exit;
		unset($set);
		
		$set_detil= new Penilaian();
		$set_detil->setField("TANGGAL_TES", dateToDBCheck($tempPenilaianTanggalTes));
		$set_detil->setField("SATKER_TES_ID", $tempPenilaianSatkerTesId);
		$set_detil->setField("JABATAN_TES_ID", $tempPenilaianJabatan);
		$set_detil->setField("PEGAWAI_ID", $tempPenilaianPegawaiId);
		$set_detil->setField("ASPEK_ID", $tempPenilaianAspekId);
		$set_detil->setField("ESELON", setNULL($tempPenilaianEselonId));
		$set_detil->setField("NAMA_ASESI", $tempPenilaianNamaAsesi);
		$set_detil->setField("METODE", $tempPenilaianMetode);
		$set_detil->setField("JADWAL_TES_ID", $reqJadwalTesId);
		$set_detil->setField("PENILAIAN_ID", $tempRowId);
		//$set_detil->setField("JADWAL_TES_ID", $tempRowId);
		
		$reqStatusSimpan= "";	
		if($tempRowId == "")
		{
			if($set_detil->insert())
				$reqStatusSimpan= "1";
			//echo $set_detil->query;exit;
		}
		else
		{
			if($set_detil->update())
				$reqStatusSimpan= "1";
		}
	
	}
	unset($set_jadwal);
	
	//$reqStatusSimpan= 1;
	if($reqStatusSimpan == 1)
	{
		$set_detil= new JadwalPegawaiDetil();
		$set_detil->setField('JADWAL_PEGAWAI_ID', $reqRowId);
		$set_detil->deletePegawai();
		unset($set_detil);
		
		for($i=0; $i < count($reqJadwalPegawaiDetilId); $i++)
		{
			if($reqIndikatorId[$i] == ""){}
			else
			{
				$set_detil= new JadwalPegawaiDetil();
				//$set_detil->setField('JADWAL_PEGAWAI_DETIL_ID', $reqJadwalPegawaiDetilId[$i]);
				$set_detil->setField('JADWAL_PEGAWAI_ID', $reqRowId);
				$set_detil->setField('PEGAWAI_ID', $tempPenilaianPegawaiId);
				$set_detil->setField('ATRIBUT_ID',$reqAtributId[$i]);
				$set_detil->setField('INDIKATOR_ID',$reqIndikatorId[$i]);
				$set_detil->setField('LEVEL_ID', $reqLevelId[$i]);
				
				$tempIndexKeterangan= $reqJumlahKeterangan[$i];
				$set_detil->setField('KETERANGAN', $reqKeterangan[$tempIndexKeterangan]);

				$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
				$set_detil->insert();
				//echo $set_detil->query;exit;
				unset($set_detil);
			}
		}
		echo "Data berhasil disimpan";
	}
	else
	echo "Data gagal disimpan";
}
?>