<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/Kelautan.php");
include_once("../WEB/classes/base-simpeg/Pegawai.php");
include_once("../WEB/classes/base-simpeg/RiwayatJabatan.php");
include_once("../WEB/classes/base-simpeg/RiwayatPangkat.php");
include_once("../WEB/classes/base-simpeg/RiwayatPendidikan.php");


/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}


// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$reqMode= httpFilterRequest("reqMode");
$reqPegawaiId= httpFilterPost("reqPegawaiId");

$reqNIP2= httpFilterPost("reqNIP2");
$reqSatuanKerjaId= httpFilterPost("reqSatuanKerjaId");
$reqStatusJenis= httpFilterPost("reqStatusJenis");
$reqKtp= httpFilterPost("reqKtp");
$reqStatusPegawaiId= httpFilterPost("reqStatusPegawaiId");
$reqNama= httpFilterPost("reqNama");
$reqTempatLahir= httpFilterPost("reqTempatLahir");
$reqTglLahir= httpFilterPost("reqTglLahir");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqAgama= httpFilterPost("reqAgama");
$reqStatusKawin= httpFilterPost("reqStatusKawin");
$reqAlamat= httpFilterPost("reqAlamat");
$reqAlamatTempatKerja= httpFilterPost("reqAlamatTempatKerja");
$reqEmail= httpFilterPost("reqEmail");
$reqSosmed= httpFilterPost("reqSosmed");
$reqHp= httpFilterPost("reqHp");
$reqAutoAnamnesa= httpFilterPost("reqAutoAnamnesa");

$reqPangkatId= httpFilterPost("reqPangkatId");
$reqPangkatTmt= httpFilterPost("reqPangkatTmt");
$reqJabatanId= httpFilterPost("reqJabatanId");
$reqJabatanNama= httpFilterPost("reqJabatanNama");
$reqJabatanTmt= httpFilterPost("reqJabatanTmt");
$reqPendidikanId= httpFilterPost("reqPendidikanId");
$reqPendidikanJurusan= httpFilterPost("reqPendidikanJurusan");
$reqTempatKerja= httpFilterPost("reqTempatKerja");
$reqSatuanEksternalKerjaId= httpFilterPost("reqSatuanEksternalKerjaId");
$reqJabatanLamar= httpFilterPost("reqJabatanLamar");


if($reqStatusJenis == "1")
{
	if(empty($reqStatusPegawaiId))
	{
		$reqStatusPegawaiId= "2";
	}
}
else
{
	// $reqStatusPegawaiId= "";
}

$set= new Kelautan();
$set->setField("NIP_BARU", $reqNIP2);
$set->setField("SATKER_ID", $reqSatuanKerjaId);
$set->setField("SATKER_EKSTERNAL_ID", $reqSatuanEksternalKerjaId);

$set->setField("STATUS_JENIS", ValToNullDB($reqStatusJenis));
$set->setField("NIK", $reqKtp);
$set->setField("STATUS_PEGAWAI_ID", ValToNullDB($reqStatusPegawaiId));
$set->setField("NAMA", setQuote($reqNama,1));
$set->setField("TEMPAT_LAHIR", setQuote($reqTempatLahir,1));
$set->setField("TGL_LAHIR", dateToDBCheck($reqTglLahir));
$set->setField("JENIS_KELAMIN", $reqJenisKelamin);
$set->setField("AGAMA", $reqAgama);
$set->setField("STATUS_KAWIN", $reqStatusKawin);
$set->setField("ALAMAT", setQuote($reqAlamat,1));
$set->setField("ALAMAT_TEMPAT_KERJA", setQuote($reqAlamatTempatKerja,1));
$set->setField("EMAIL", $reqEmail);
$set->setField("SOSIAL_MEDIA", $reqSosmed);
$set->setField("HP", $reqHp);
$set->setField("AUTO_ANAMNESA", setQuote($reqAutoAnamnesa,1));
$set->setField("TEMPAT_KERJA", $reqTempatKerja);
$set->setField("JABATAN_LAMAR", $reqJabatanLamar);

$set->setField("LAST_PANGKAT_ID", ValToNullDB($reqPangkatId));
$set->setField("LAST_TMT_PANGKAT", dateToDBCheck($reqPangkatTmt));
$set->setField("LAST_ESELON_ID", ValToNullDB($reqJabatanId));
$set->setField("LAST_JABATAN", setQuote($reqJabatanNama,1));
$set->setField("LAST_TMT_JABATAN", dateToDBCheck($reqJabatanTmt));
$set->setField("LAST_DIK_JENJANG", ValToNullDB($reqPendidikanId));
$set->setField("LAST_DIK_JURUSAN", setQuote($reqPendidikanJurusan,1));
$set->setField("PEGAWAI_ID", $reqPegawaiId);

$reqSimpan= "";
if(empty($reqPegawaiId))
{	
	if($set->insertpegawai())
	{
		$reqPegawaiId= $set->id;
		$mode = 'Data berhasil disimpan';
		$reqSimpan= "1";
		//echo $set->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';

	// echo $set->query;exit;
}
else
{
	if($set->updatepegawai())
	{
		$mode = 'Data berhasil disimpan';
		$reqSimpan= "1";
	}
	else
		$mode = 'Data gagal disimpan';
}
// echo $set->query;exit;

if($reqSimpan == "1")
{	
	$cek_jabatan = new RiwayatJabatan();
	$cek_jabatan->selectByParams(array("PEGAWAI_ID"=>$reqPegawaiId));
	$cek_jabatan->firstRow();
	$reqRiwayatJabatanId = $cek_jabatan->getField("RIWAYAT_JABATAN_ID");
	unset($cek_jabatan);
	
	$set_jabatan = new RiwayatJabatan();
	$set_jabatan->setField("JABATAN", setQuote($reqJabatanNama,1));
	$set_jabatan->setField("ESELON_ID", ValToNullDB($reqJabatanId));
	$set_jabatan->setField("TMT_JABATAN", dateToDBCheck($reqJabatanTmt));
	$set_jabatan->setField("MASA_JAB_TAHUN", ValToNullDB($reqJabatanMasaTahun));
	$set_jabatan->setField("MASA_JAB_BULAN", ValToNullDB($reqJabatanMasaBulan));
	$set_jabatan->setField("PEGAWAI_ID", $reqPegawaiId);
	
	if($reqRiwayatJabatanId=="")
	{
		$set_jabatan->insert();
	}
	else
	{
		$set_jabatan->setField("RIWAYAT_JABATAN_ID", $reqRiwayatJabatanId);
		$set_jabatan->update();
	}

	$cek_pangkat = new RiwayatPangkat();
	$cek_pangkat->selectByParams(array("PEGAWAI_ID"=>$reqPegawaiId));
	$cek_pangkat->firstRow();
	$reqRiwayatPangkatId = $cek_pangkat->getField("RIWAYAT_PANGKAT_ID");
	unset($cek_pangkat);

	$set_pangkat = new RiwayatPangkat();
	$set_pangkat->setField("PANGKAT_ID", $reqPangkatId);
	$set_pangkat->setField("TMT_PANGKAT", dateToDBCheck($reqPangkatTmt));
	$set_pangkat->setField("MK_TAHUN", ValToNullDB($reqPangkatMasaKerjaTahun));
	$set_pangkat->setField("MK_BULAN", ValToNullDB($reqPangkatMasaKerjaBulan));
	$set_pangkat->setField("PEGAWAI_ID", $reqPegawaiId);
	
	if($reqRiwayatPangkatId=="")
	{
		$set_pangkat->insert();
	}
	else
	{
		$set_pangkat->setField("RIWAYAT_PANGKAT_ID", $reqRiwayatPangkatId);
		$set_pangkat->update();
	}


	echo $reqPegawaiId."-".$mode;
}
else
{
	echo "xxx-".$mode;
}
?>