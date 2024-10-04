<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar = new Pelamar();
$user_login = new UserLogin();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");

$reqNPP= httpFilterPost("reqNPP");
$reqNama= httpFilterPost("reqNama");
$reqAgamaId= httpFilterPost("reqAgamaId");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqAsalPelabuhanId= httpFilterPost("reqAsalPelabuhanId");
$reqDepartemen = httpFilterPost("reqDepartemen");
$reqTempat= httpFilterPost("reqTempat");
$reqTanggal= httpFilterPost("reqTanggal");
$reqTanggalNpwp= httpFilterPost("reqTanggalNpwp");
$reqAlamat= httpFilterPost("reqAlamat");
$reqTelepon = httpFilterPost("reqTelepon");
$reqEmail= httpFilterPost("reqEmail");
$reqGolDarah= httpFilterPost("reqGolDarah");
$reqStatusPernikahan= httpFilterPost("reqStatusPernikahan");
$reqNRP= httpFilterPost("reqNRP");
$reqFingerId= httpFilterPost("reqFingerId");

$reqStatusPegawai= httpFilterPost("reqStatusPegawai");
$reqStatusKeluarga= httpFilterPost("reqStatusKeluarga");
$reqBankId= httpFilterPost("reqBankId");
$reqRekeningNo= httpFilterPost("reqRekeningNo");
$reqRekeningNama= httpFilterPost("reqRekeningNama");
$reqNPWP= httpFilterPost("reqNPWP");
$reqTglPensiun= httpFilterPost("reqTglPensiun");
$reqTglMutasiKeluar= httpFilterPost("reqTglMutasiKeluar");
$reqTglWafat= httpFilterPost("reqTglWafat");
$reqNoSKMPP= httpFilterPost("reqNoSKMPP");
$reqTMTMPP= httpFilterPost("reqTMTMPP");
$reqHobby= httpFilterPost("reqHobby");
$reqFingerId= httpFilterPost("reqFingerId");
$reqKtpNo= httpFilterPost("reqKtpNo");
$reqTMTNONAKTIF= httpFilterPost("reqTMTNONAKTIF");
$reqTglKeluar= httpFilterPost("reqTglKeluar");
$reqTglKontrakAkhir= httpFilterPost("reqTglKontrakAkhir");
$reqKelompokPegawai = httpFilterPost("reqKelompokPegawai");

$reqTinggi= httpFilterPost("reqTinggi");
$reqBeratBadan= httpFilterPost("reqBeratBadan");
$reqNoSepatu= httpFilterPost("reqNoSepatu");
$reqDomisili= httpFilterPost("reqDomisili");
$reqKotaId= httpFilterPost("reqKotaId");

$reqJamsostek = httpFilterPost("reqJamsostek");
$reqJamsostekTanggal = httpFilterPost("reqJamsostekTanggal");
$reqKesehatan = httpFilterPost("reqKesehatan");
$reqKesehatanTanggal = httpFilterPost("reqKesehatanTanggal");
$reqKesehatanFaskes = httpFilterPost("reqKesehatanFaskes");
$reqKkNo = httpFilterPost("reqKkNo");

$reqStatusKacamata= httpFilterPost("reqStatusKacamata");

$reqLampiran = $_FILES['reqLampiran'];
$reqLampiranTemp = httpFilterPost('reqLampiranTemp');

if($reqDepartemen == 0)
	$reqDepartemen = "NULL";


$pelamar->setField('PELAMAR_ID', $userLogin->userPelamarId);
$pelamar->setField('DEPARTEMEN_ID', $reqDepartemen);
$pelamar->setField('NRP', $reqNRP);
$pelamar->setField('NIPP', $reqNPP);
$pelamar->setField('NAMA', setQuote($reqNama,1));
$pelamar->setField('AGAMA_ID', $reqAgamaId);
$pelamar->setField('JENIS_KELAMIN', $reqJenisKelamin);
$pelamar->setField('PELABUHAN_ID', $reqAsalPelabuhanId);
$pelamar->setField('TEMPAT_LAHIR', $reqTempat);
$pelamar->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggal));
$pelamar->setField('ALAMAT', setQuote($reqAlamat, 1));
$pelamar->setField('TELEPON', $reqTelepon);
$pelamar->setField('EMAIL', $reqEmail);
$pelamar->setField('GOLONGAN_DARAH', $reqGolDarah);
$pelamar->setField('STATUS_KAWIN', $reqStatusPernikahan);
$pelamar->setField('STATUS_PELAMAR_ID', $reqStatusPegawai);
$pelamar->setField('BANK_ID', $reqBankId);
$pelamar->setField('REKENING_NO', $reqRekeningNo);
$pelamar->setField('REKENING_NAMA', $reqRekeningNama);
$pelamar->setField('NPWP', $reqNPWP);
$pelamar->setField('STATUS_KELUARGA_ID', ValToNullDB($reqStatusKeluarga));
$pelamar->setField('JAMSOSTEK_NO', $reqJamsostek);
$pelamar->setField('JAMSOSTEK_TANGGAL', dateToDBCheck($reqJamsostekTanggal));
$pelamar->setField('KESEHATAN_NO', $reqKesehatan);
$pelamar->setField('KESEHATAN_TANGGAL', dateToDBCheck($reqKesehatanTanggal));
$pelamar->setField('HOBBY', $reqHobby);
$pelamar->setField('FINGER_ID', ValToNullDB($reqFingerId));
$pelamar->setField('TANGGAL_NPWP', dateToDBCheck($reqTanggalNpwp));
$pelamar->setField('TINGGI', $reqTinggi);
$pelamar->setField('BERAT_BADAN', $reqBeratBadan);
$pelamar->setField('NO_SEPATU', $reqNoSepatu);
$pelamar->setField('KTP_NO', $reqKtpNo);
$pelamar->setField('KELOMPOK_PEGAWAI', $reqKelompokPegawai);
$pelamar->setField('KK_NO', $reqKkNo);
$pelamar->setField('KESEHATAN_FASKES', $reqKesehatanFaskes);
$pelamar->setField('TANGGAL_PENSIUN', 'NULL');
$pelamar->setField('TANGGAL_MUTASI_KELUAR', 'NULL');
$pelamar->setField('TANGGAL_WAFAT', 'NULL');
$pelamar->setField('NO_MPP', 'NULL');
$pelamar->setField('TANGGAL_MPP', 'NULL');
$pelamar->setField('TGL_NON_AKTIF', 'NULL');
$pelamar->setField('TGL_DIKELUARKAN', 'NULL');
$pelamar->setField('TGL_KONTRAK_AKHIR', 'NULL');
$pelamar->setField('DOMISILI', $reqDomisili);
$pelamar->setField('KOTA_ID', $reqKotaId);

$pelamar->setField("STATUS_KACAMATA", ValToNullDB($reqStatusKacamata));

$pelamar->setField("LAST_UPDATE_USER", $userLogin->nama);
$pelamar->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
$pelamar->updatePelamar();


/* START UPLOAD FILE */
$insertLinkFile = "";
$FILE_DIR = "../uploads/";
for($i=0;$i<count($reqLampiran);$i++)
{	
	if($reqLampiran['name'][$i] == "")
	{}
	else			
	{
		$renameFile = md5(date("dmYHis").$reqLampiran['name'][$i]).".".getExtension($reqLampiran['name'][$i]);
		if (move_uploaded_file($reqLampiran['tmp_name'][$i], $FILE_DIR.$renameFile))
		{
			if($i == 0)	
				$insertLinkFile = $renameFile;
			else
				$insertLinkFile .= ",".$renameFile;
			
		}			
	}	
}		

if($insertLinkFile == "")
{}
else
{
	$pelamar->setField("FIELD", "FOTO");
	$pelamar->setField("FIELD_VALUE", $insertLinkFile);
	$pelamar->setField("PELAMAR_ID", $userLogin->userPelamarId);
	$pelamar->updateByField();
}

echo "Data berhasil disimpan.";

?>