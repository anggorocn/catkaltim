<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-simpeg/Pegawai.php");
include_once("../WEB/classes/base-simpeg/RiwayatJabatan.php");
include_once("../WEB/classes/base-simpeg/RiwayatPangkat.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set= new Pegawai();

$reqMode= httpFilterRequest("reqMode");
$reqPegawaiId= httpFilterPost("reqPegawaiId");


$reqNIP1= httpFilterPost("reqNIP1");
$reqNIP2= httpFilterPost("reqNIP2");
$reqSatuanKerja= httpFilterPost("reqSatuanKerja");
$reqNama= httpFilterPost("reqNama");
$reqStatusPegawaiId= httpFilterPost("reqStatusPegawaiId");
$reqTempatLahir= httpFilterPost("reqTempatLahir");
$reqTanggalLahir= httpFilterPost("reqTanggalLahir");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqAlamat= httpFilterPost("reqAlamat");
$reqPangkatId= httpFilterPost("reqPangkatId");
$reqEselonId= httpFilterPost("reqEselonId");
$reqTMTPangkat= httpFilterPost("reqTMTPangkat");
$reqJabatanTerkahir= httpFilterPost("reqJabatanTerkahir");
$reqTMTJabatan= httpFilterPost("reqTMTJabatan");
$reqLastDikJenjang= httpFilterPost("reqLastDikJenjang");
$reqLastDikTahun= httpFilterPost("reqLastDikTahun");
$reqLastDikJurusan= httpFilterPost("reqLastDikJurusan");
$reqMasaJabBulan= httpFilterPost("reqMasaJabBulan");
$reqMasaJabTahun= httpFilterPost("reqMasaJabTahun");
$reqMasaKerjaBulan= httpFilterPost("reqMasaKerjaBulan");
$reqMasaKerjaTahun= httpFilterPost("reqMasaKerjaTahun");
$reqTmtCpns= httpFilterPost("reqTmtCpns");
$reqTmtPns= httpFilterPost("reqTmtPns");
$reqTipePegawaiId= httpFilterPost("reqTipePegawaiId");
$reqAgama= httpFilterPost("reqAgama");

$set->setField("NIP", $reqNIP1);
$set->setField("NIP_BARU", $reqNIP2);
$set->setField("NAMA", $reqNama);
$set->setField("TEMPAT_LAHIR", $reqTempatLahir);
$set->setField("TGL_LAHIR", dateToDBCheck($reqTanggalLahir));
$set->setField("JENIS_KELAMIN", $reqJenisKelamin);
$set->setField("AGAMA", $reqAgama);
$set->setField("LAST_PANGKAT_ID", ValToNullDB($reqPangkatId));
$set->setField("LAST_TMT_PANGKAT", dateToDBCheck($reqTMTPangkat));
$set->setField("TMT_CPNS", dateToDBCheck($reqTmtCpns));
$set->setField("TMT_PNS", dateToDBCheck($reqTmtPns));
$set->setField("LAST_JABATAN", $reqJabatanTerkahir);
$set->setField("LAST_ESELON_ID", ValToNullDB($reqEselonId));
$set->setField("LAST_TMT_JABATAN", dateToDBCheck($reqTMTJabatan));
$set->setField("MASA_JAB_TAHUN", ValToNullDB($reqMasaJabTahun));
$set->setField("MASA_JAB_BULAN", ValToNullDB($reqMasaJabBulan));
$set->setField("MASA_KERJA_TAHUN", ValToNullDB($reqMasaKerjaTahun));
$set->setField("MASA_KERJA_BULAN", ValToNullDB($reqMasaKerjaBulan));
$set->setField("SATKER_ID", $reqSatuanKerja);
$set->setField("TIPE_PEGAWAI_ID", ValToNullDB($reqTipePegawaiId));
$set->setField("STATUS_PEGAWAI_ID", ValToNullDB($reqStatusPegawaiId));
$set->setField("LAST_DIK_JENJANG", ValToNullDB($reqLastDikJenjang));
$set->setField("LAST_DIK_TAHUN", $reqLastDikTahun);
$set->setField("LAST_DIK_JURUSAN", $reqLastDikJurusan);
$set->setField("ALAMAT", $reqAlamat);

$reqSimpan= "";
if($reqMode == "insert")
{	
	if($set->insert())
	{
		$reqPegawaiId= $set->id;
		$mode = 'Data berhasil disimpan';
		$reqSimpan= "1";
		//echo $set->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';
		//echo $set->query;exit;
}
elseif($reqMode == "update")
{
	$set->setField("PEGAWAI_ID", $reqPegawaiId);
	
	if($set->update())
	{
		$mode = 'Data berhasil disimpan';
		$reqSimpan= "1";
	}
	else
		$mode = 'Data gagal disimpan';
}
//echo $set->query;exit;

if($reqSimpan == "1")
{	
	$cek_jabatan = new RiwayatJabatan();
	$cek_jabatan->selectByParams(array("PEGAWAI_ID"=>$reqPegawaiId));
	$cek_jabatan->firstRow();
	$reqRiwayatJabatanId = $cek_jabatan->getField("RIWAYAT_JABATAN_ID");
	unset($cek_jabatan);
	
	$set_jabatan = new RiwayatJabatan();
	$set_jabatan->setField("JABATAN", $reqJabatanTerkahir);
	$set_jabatan->setField("ESELON_ID", ValToNullDB($reqEselonId));
	$set_jabatan->setField("TMT_JABATAN", dateToDBCheck($reqTMTJabatan));
	$set_jabatan->setField("MASA_JAB_TAHUN", ValToNullDB($reqMasaJabTahun));
	$set_jabatan->setField("MASA_JAB_BULAN", ValToNullDB($reqMasaJabBulan));
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
	$set_pangkat->setField("TMT_PANGKAT", dateToDBCheck($reqTMTPangkat));
	$set_pangkat->setField("MK_TAHUN", ValToNullDB($reqMasaKerjaTahun));
	$set_pangkat->setField("MK_BULAN", ValToNullDB($reqMasaKerjaBulan));
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
	//echo $set_pangkat->query;exit;
	
		
	echo $reqPegawaiId."-".$mode;
}
else
{
	echo $reqPegawaiId."-".$mode;
}
?>