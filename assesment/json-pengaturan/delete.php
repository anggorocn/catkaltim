<?
//include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");

$reqId = $_GET['id'];
$reqRowDetilId= $_GET['reqRowDetilId'];
$reqMode = $_GET['reqMode'];
$reqAspekId= $_GET['reqAspekId'];

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/
if($reqMode == "user_app")
{
	include_once("../WEB/classes/base/UsersBase.php");
	$set = new UsersBase();
	$set->setField("USER_APP_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "master_jenis_hukuman")
{
	include_once("../WEB/classes/base/JenisHukuman.php");
	$set = new JenisHukuman();
	$set->setField("JENIS_HUKUMAN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "master_tingkat_hukuman")
{
	include_once("../WEB/classes/base/TingkatHukuman.php");
	$set = new TingkatHukuman();
	$set->setField("TINGKAT_HUKUMAN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "master_peraturan")
{
	include_once("../WEB/classes/base/Peraturan.php");
	$set = new Peraturan();
	$set->setField("PERATURAN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "user_group")
{
	include_once("../WEB/classes/base/UserGroupsBase.php");
	$set = new UserGroupsBase();
	$set->setField("USER_GROUP_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "master_asesor")
{
	include_once("../WEB/classes/base/Asesor.php");
	$set = new Asesor();
	$set->setField("ASESOR_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "master_jadwal_tes")
{
	include_once("../WEB/classes/base/JadwalTes.php");
	$set = new JadwalTes();
	$set->setField("JADWAL_TES_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "jadwal_kelompok_ruangan")
{
	include_once("../WEB/classes/base/JadwalKelompokRuangan.php");
	$set = new JadwalKelompokRuangan();
	$set->setField("JADWAL_KELOMPOK_RUANGAN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "jadwal_pegawai")
{
	include_once("../WEB/classes/base/JadwalPegawai.php");
	$set = new JadwalPegawai();
	$set->setField("JADWAL_PEGAWAI_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "master_jadwal_acara")
{
	include_once("../WEB/classes/base/JadwalAcara.php");
	$set = new JadwalAcara();
	$set->setField("JADWAL_ACARA_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "master_penggalian")
{
	include_once("../WEB/classes/base/Penggalian.php");
	$set = new Penggalian();
	$set->setField("PENGGALIAN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "jadwal_asesor_potensi")
{
	include_once("../WEB/classes/base/JadwalAsesorPotensi.php");
	$set = new JadwalAsesorPotensi();
	$set->setField("JADWAL_ASESOR_POTENSI_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "jadwal_asesor_potensi_pegawai")
{
	include_once("../WEB/classes/base/JadwalAsesorPotensiPegawai.php");
	$set = new JadwalAsesorPotensiPegawai();
	$set->setField("JADWAL_ASESOR_POTENSI_PEGAWAI_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "indikator_penilaian")
{
	include_once("../WEB/classes/base/IndikatorPenilaian.php");
	$set = new IndikatorPenilaian();
	$set->setField("INDIKATOR_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "formula_atribut")
{
	include_once("../WEB/classes/base/FormulaAtribut.php");
	if($reqAspekId == "1")
	{
		$set_level= new FormulaAtribut();
		$set_level->selectByParams(array(), -1, -1, " AND FORMULA_ATRIBUT_ID = ".$reqId);
		$set_level->firstRow();
		$tempLevelId= $set_level->getField("LEVEL_ID");
		unset($set_level);
		
		if($tempLevelId == ""){}
		else
		{
			$set = new FormulaAtribut();
			$set->setField("FORMULA_ATRIBUT_ID", $reqId);
			$set->setField("LEVEL_ID", $tempLevelId);
			if($set->deleteLevel())
				$alertMsg .= "Data berhasil dihapus";
			else
				$alertMsg .= "Error ".$set->getErrorMsg();
		}
	}
	else
	{
		$set = new FormulaAtribut();
		$set->setField("FORMULA_ATRIBUT_ID", $reqId);
		if($set->delete())
			$alertMsg .= "Data berhasil dihapus";
		else
			$alertMsg .= "Error ".$set->getErrorMsg();
	}
}
else if($reqMode == "jadwal_asesor")
{
	include_once("../WEB/classes/base/JadwalAsesor.php");
	$set = new JadwalAsesor();
	$set->setField("JADWAL_ASESOR_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "peraturan")
{
	include_once("../WEB/classes/base/Permen.php");
	$set = new Permen();
	$set->setField("PERMEN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "pejabat")
{
	include_once("../WEB/classes/base/Pejabat.php");
	$set = new Pejabat();
	$set->setField("PEJABAT_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "formula_assesment")
{
	include_once("../WEB/classes/base/FormulaAssesment.php");
	$set = new FormulaAssesment();
	$set->setField("formula_id", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "tipe_soal")
{
	include_once("../WEB/classes/base-cat/TipeUjian.php");
	$set = new TipeUjian();
	$set->setField("TIPE_UJIAN_ID", $reqId);

	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "jadwal_awal_tes")
{
	include_once("../WEB/classes/base/JadwalAwalTes.php");
	$set = new JadwalAwalTes();
	$set->setField("JADWAL_AWAL_TES_ID", $reqId);
	
	if($set->delete()){
		$alertMsg .= "Data berhasil dihapus";
	}
	else{
		$alertMsg .= "Error ".$set->getErrorMsg();
	}
}
else if($reqMode == "jadwal_tes_simulasi_pegawai")
{
	include_once("../WEB/classes/base/JadwalAwalTesPegawai.php");
	$set = new JadwalAwalTesPegawai();
	$set->setField("JADWAL_AWAL_TES_ID", $reqRowDetilId);
	$set->setField("PEGAWAI_ID", $reqId);
	
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "jadwal_awal_tes_simulasi_pegawai")
{
	include_once("../WEB/classes/base/JadwalAwalTesSimulasiPegawai.php");
	$set = new JadwalAwalTesSimulasiPegawai();
	$set->setField("JADWAL_AWAL_TES_ID", $reqRowDetilId);
	$set->setField("PEGAWAI_ID", $reqId);
	
	if($set->deletepegawai())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "formula_assesment_ujian_tahap")
{
	include_once("../WEB/classes/base/FormulaAssesmentUjianTahap.php");
	$set = new FormulaAssesmentUjianTahap();
	$set->setField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID", $reqId);
	
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "resetwaktupapi")
{
	include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");
	$set = new UjianTahapStatusUjian();
	$set->setField("JADWAL_TES_ID", $reqId);
	$set->setField("PEGAWAI_ID", $reqRowDetilId);
	$set->setField("TIPE_UJIAN_ID", $reqAspekId);

	if($set->resetwaktu())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();

	// echo $alertMsg;
}

?>