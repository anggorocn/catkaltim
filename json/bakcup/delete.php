<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

$reqId = $_GET['id'];
$reqMode = $_GET['reqMode'];
$reqDeleteId = $_GET['reqDeleteId'];

if($reqMode == "lowongan")
{
	include_once("../WEB/classes/base/Lowongan.php");
	$set= new Lowongan();
	
	$set->setField('LOWONGAN_ID', $reqId);
	$set->setField('FIELD', "STATUS");
	$set->setField('FIELD_VALUE', "2");
	
	if($set->updateByField())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "data_pribadi_jabatan")
{
	include_once("../WEB/classes/base/PelamarJabatan.php");
	$set= new PelamarJabatan();
	$set->setField('PELAMAR_JABATAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "data_pribadi_pendidikan")
{
	include_once("../WEB/classes/base/PelamarPendidikan.php");
	$set= new PelamarPendidikan();
	$set->setField('PELAMAR_PENDIDIKAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "data_pribadi_pelatihan")
{
	include_once("../WEB/classes/base/PelamarPelatihan.php");
	$set= new PelamarPelatihan();
	$set->setField('PELAMAR_PELATIHAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "data_pribadi_penugasan")
{
	include_once("../WEB/classes/base/PelamarPenugasan.php");
	$set= new PelamarPenugasan();
	$set->setField('PELAMAR_PENUGASAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "data_pribadi_prestasi")
{
	include_once("../WEB/classes/base/PelamarPrestasi.php");
	$set= new PelamarPrestasi();
	$set->setField('PELAMAR_PRESTASI_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "data_pribadi_karya_tulis")
{
	include_once("../WEB/classes/base/PelamarKaryaTulis.php");
	$set= new PelamarKaryaTulis();
	$set->setField('PELAMAR_KARYA_TULIS_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "data_pribadi_kegiatan_sosial")
{
	include_once("../WEB/classes/base/PelamarKegiatanSosial.php");
	$set= new PelamarKegiatanSosial();
	$set->setField('PELAMAR_KEGIATAN_SOSIAL_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "jabatan")
{
	include_once("../WEB/classes/base/Jabatan.php");
	$set= new Jabatan();
	$set->setField('JABATAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "faq")
{
	include_once("../WEB/classes/base/FAQ.php");
	$set= new Faq();
	$set->setField('FAQ_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "blacklist")
{
	include_once("../WEB/classes/base/Blacklist.php");
	$set= new Blacklist();
	$set->setField('BLACKLIST_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "informasi")
{
	include_once("../WEB/classes/base/Informasi.php");
	$set= new Informasi();
	$set->setField('INFORMASI_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "lowongan_tahapan_tes_add_tahapan")
{
	$arrId = explode("-", $reqDeleteId);
	include_once("../WEB/classes/base/PelamarLowonganTahapan.php");
	$set= new PelamarLowonganTahapan();
				  
	$set->setField('LOWONGAN_ID', $arrId[0]);
	$set->setField('LOWONGAN_TAHAPAN_ID', $arrId[1]);
	$set->setField('PELAMAR_ID', $reqId);
	if($set->deleteData())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "jurusan")
{
	include_once("../WEB/classes/base/Jurusan.php");
	$set= new Jurusan();
	$set->setField('JURUSAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
elseif($reqMode == "bidang")
{
	include_once("../WEB/classes/base/Bidang.php");
	$set= new Bidang();
	$set->setField('BIDANG_ID', $reqId);
	if($set->delete())
		$alertMsg .= "1-Data berhasil dihapus";
	else
		$alertMsg .= "0-Error ".$set->getErrorMsg();
}
else if($reqMode == "pelamar")
{
	include_once("../WEB/classes/base/Pelamar.php");
	$set= new Pelamar();
	$set->setField('PELAMAR_ID', $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "ujian")
{
	include_once("../WEB/classes/base-cat/Ujian.php");
	$set= new Ujian();
	$set->setField('UJIAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "ujian_bank_soal")
{
	include_once("../WEB/classes/base-cat/UjianBankSoal.php");
	$set= new UjianBankSoal();
	$set->setField('UJIAN_BANK_SOAL_ID', $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "ujian_pegawai_daftar")
{
	include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
	$set= new UjianPegawaiDaftar();
	$set->setField('UJIAN_PEGAWAI_DAFTAR_ID', $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "bank_soal")
{
	include_once("../WEB/classes/base-cat/BankSoal.php");
	$set= new BankSoal();
	$set->setField('BANK_SOAL_ID', $reqId);
	if($set->deleteAll())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "ujian_tahap")
{
	include_once("../WEB/classes/base-cat/UjianTahap.php");
	$set= new UjianTahap();
	$set->setField('UJIAN_TAHAP_ID', $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "pengumuman")
{
	include_once("../WEB/classes/base/Pengumuman.php");
	$set= new Pengumuman();
	$set->setField('PENGUMUMAN_ID', $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
else if($reqMode == "session_log_cheklist_semua")
{
	include_once("../WEB/classes/base/SessionLog.php");
	$set= new SessionLog();
	if($set->deleteFilter($statement))
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
/*
$arrFinal = array("tempHasil"=>$alertMsg);
echo json_encode($arrFinal);
*/
?>