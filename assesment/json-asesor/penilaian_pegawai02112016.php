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

$reqJadwalPegawaiDetilId= $_POST["reqJadwalPegawaiDetilId"];
$reqJadwalPegawaiId= $_POST["reqJadwalPegawaiId"];
$reqJadwalAsesorId= $_POST["reqJadwalAsesorId"];
$reqPegawaiId= $_POST["reqPegawaiId"];
$reqPenggalianId= $_POST["reqPenggalianId"];
$reqAtributId= $_POST["reqAtributId"];
$reqAtributIdParent= $_POST["reqAtributIdParent"];
$reqAspekId= $_POST["reqAspekId"];
$reqTahun= $_POST["reqTahun"];
$reqNilai= $_POST["reqNilai"];
$reqKeterangan= $_POST["reqKeterangan"];

$reqMode= httpFilterPost("reqMode");
$reqRowId= httpFilterPost("reqRowId");

if($reqMode == "insert")
{
	//satker tes ambil bapak nya dulu.
	$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqRowId;
	$set= new JadwalPegawaiDetil();
	$set->selectByParamsPenilaian(array(), -1,-1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$tempPenilaianTanggalTes= dateToPageCheck($set->getField("TANGGAL_TES"));
	$tempPenilaianSatkerTesId= $set->getField("SATKER_TES_ID");
	$tempPenilaianJabatan= $set->getField("JABATAN_INI_TES");
	$tempPenilaianPegawaiId= $set->getField("PEGAWAI_ID");
	$tempPenilaianAspekId= $set->getField("ASPEK_ID");
	$tempPenilaianEselonId= $set->getField("ESELON_ID");
	$tempPenilaianNamaAsesi= $set->getField("");
	$tempPenilaianMetode= $set->getField("METODE");
	$tempPenilaianAspekId= $set->getField("ASPEK_ID");
	unset($set);
	
	$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId[0]." AND DATE_FORMAT(A.TANGGAL_TES, '%d-%m-%Y') = '".$tempPenilaianTanggalTes."' AND A.SATKER_TES_ID = '".$tempPenilaianSatkerTesId."' AND A.ASPEK_ID = ".$tempPenilaianAspekId;
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
	$set_detil->setField("PENILAIAN_ID", $tempRowId);
	
	$reqStatusSimpan= "";	
	if($tempRowId == "")
	{
		if($set_detil->insert())
			$reqStatusSimpan= "1";
	}
	else
	{
		if($set_detil->update())
			$reqStatusSimpan= "1";
	}
	//echo $set_detil->query;exit;
	
	if($reqStatusSimpan == 1)
	{
		for($i=0; $i < count($reqJadwalPegawaiDetilId); $i++)
		{
			$set_detil= new JadwalPegawaiDetil();
			$set_detil->setField('JADWAL_PEGAWAI_DETIL_ID', $reqJadwalPegawaiDetilId[$i]);
			$set_detil->setField('JADWAL_PEGAWAI_ID', $reqJadwalPegawaiId[$i]);
			$set_detil->setField('JADWAL_ASESOR_ID',$reqJadwalAsesorId[$i]);
			$set_detil->setField('PEGAWAI_ID', $reqPegawaiId[$i]);
			$set_detil->setField('PENGGALIAN_ID', $reqPenggalianId[$i]);
			$set_detil->setField('ATRIBUT_ID', $reqAtributId[$i]);
			$set_detil->setField('ATRIBUT_ID_PARENT', $reqAtributIdParent[$i]);
			$set_detil->setField('ASPEK_ID', $reqAspekId[$i]);
			$set_detil->setField('TAHUN', $reqTahun[$i]);
			$set_detil->setField('NILAI', $reqNilai[$i]);
			$set_detil->setField('KETERANGAN', $reqKeterangan[$i]);
			
			if($reqJadwalPegawaiDetilId[$i] == "")
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
		}
		echo "Data berhasil disimpan";
	}
	else
	echo "Data gagal disimpan";
}
?>