<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Beasiswa.php");
include_once("../WEB/classes/base-silat/BeasiswaSertifikat.php");
include_once("../WEB/classes/utils/UserLogin.php");

$reqBEASISWA_ID 	= httpFilterRequest("reqBEASISWA_ID");
$reqMode 			= httpFilterRequest("reqMode");
$reqPegawaiId 		= httpFilterRequest('reqPegawaiId');
$reqRowId= httpFilterPost("reqRowId");

$reqJenis= httpFilterPost("reqJenis");
$reqUniversitasAsal= httpFilterPost("reqUniversitasAsal");
$reqJurusanAsal= httpFilterPost("reqJurusanAsal");
$reqAkreditasi= httpFilterPost("reqAkreditasi");
$reqIpk= httpFilterPost("reqIpk");
$reqSertifikat= httpFilterPost("reqSertifikat");
$reqPda= httpFilterPost("reqPda");
$reqPfs= httpFilterPost("reqPfs");
$reqPascaSarjana= httpFilterPost("reqPascaSarjana");
$reqUniversitas= httpFilterPost("reqUniversitas");
$reqJurusan= httpFilterPost("reqJurusan");
$reqOrganisasiDonor= httpFilterPost("reqOrganisasiDonor");
$reqStatus= httpFilterPost("reqStatus");
$reqNegara= httpFilterPost("reqNegara");
$reqTahun= httpFilterPost("reqTahun");
$reqTanggalMulai= httpFilterPost("reqTanggalMulai");
$reqTanggalSelesai= httpFilterPost("reqTanggalSelesai");
$reqJudul= httpFilterPost("reqJudul");
$reqNomor= httpFilterPost("reqNomor");
$reqKeterangan= httpFilterPost("reqKeterangan");

$reqTipeSertifikatId= $_POST["reqTipeSertifikatId"];
$reqTanggal= $_POST["reqTanggal"];
$reqSkor= $_POST["reqSkor"];
$reqLembaga= $_POST["reqLembaga"];
$reqJenisSertifikatId= $_POST["reqJenisSertifikatId"];
$reqRowDetilId= $_POST["reqRowDetilId"];

$tempSimpan="";
if($reqMode == "SubmitSimpan")
{
	$set_main_menu= new Beasiswa();
	$set_main_menu->setField("JENIS", $reqJenis);
	$set_main_menu->setField("UNIVERSITAS_ASAL", $reqUniversitasAsal);
	$set_main_menu->setField("JURUSAN_ASAL", $reqJurusanAsal);
	$set_main_menu->setField("AKREDITASI", $reqAkreditasi);
	$set_main_menu->setField("IPK", $reqIpk);
	$set_main_menu->setField("SERTIFIKAT_INGGRIS", $reqSertifikat);
	$set_main_menu->setField("PDA", $reqPda);
	$set_main_menu->setField("PFS", $reqPfs);
	$set_main_menu->setField("PASCA_SARJANA", $reqPascaSarjana);
	$set_main_menu->setField("UNIVERSITAS", $reqUniversitas);
	$set_main_menu->setField("JURUSAN", $reqJurusan);
	$set_main_menu->setField("ORGANISASI_DONOR", $reqOrganisasiDonor);
	$set_main_menu->setField("STATUS", $reqStatus);
	$set_main_menu->setField("NEGARA", $reqNegara);
	$set_main_menu->setField("TAHUN", $reqTahun);
	$set_main_menu->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalMulai));
	$set_main_menu->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalSelesai));
	$set_main_menu->setField("JUDUL", $reqJudul);
	$set_main_menu->setField("NOMOR", $reqNomor);
	$set_main_menu->setField("KETERANGAN", $reqKeterangan);
	$set_main_menu->setField('PEGAWAI_ID', $reqPegawaiId);
	$set_main_menu->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set_main_menu->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
	$set_main_menu->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
	
	if($set_main_menu->insert())
	{
		$mode = 'simpan';
		$tempSimpan='simpan';
	}
	else
		$tempSimpan='error';;
		//$mode = 'error';
	//echo $set_main_menu->query;exit;
	//echo "-Data Tersimpan-".$mode;
}
elseif($reqMode == "SubmitEdit")
{
	$set_main_menu= new Beasiswa();
	$set_main_menu->setField('BEASISWA_ID', $reqRowId);
	$set_main_menu->setField("JENIS", $reqJenis);
	$set_main_menu->setField("UNIVERSITAS_ASAL", $reqUniversitasAsal);
	$set_main_menu->setField("JURUSAN_ASAL", $reqJurusanAsal);
	$set_main_menu->setField("AKREDITASI", $reqAkreditasi);
	$set_main_menu->setField("IPK", $reqIpk);
	$set_main_menu->setField("SERTIFIKAT_INGGRIS", $reqSertifikat);
	$set_main_menu->setField("PDA", $reqPda);
	$set_main_menu->setField("PFS", $reqPfs);
	$set_main_menu->setField("PASCA_SARJANA", $reqPascaSarjana);
	$set_main_menu->setField("UNIVERSITAS", $reqUniversitas);
	$set_main_menu->setField("JURUSAN", $reqJurusan);
	$set_main_menu->setField("ORGANISASI_DONOR", $reqOrganisasiDonor);
	$set_main_menu->setField("STATUS", $reqStatus);
	$set_main_menu->setField("NEGARA", $reqNegara);
	$set_main_menu->setField("TAHUN", $reqTahun);
	$set_main_menu->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalMulai));
	$set_main_menu->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalSelesai));
	$set_main_menu->setField("JUDUL", $reqJudul);
	$set_main_menu->setField("NOMOR", $reqNomor);
	$set_main_menu->setField("KETERANGAN", $reqKeterangan);
	$set_main_menu->setField('PEGAWAI_ID', $reqPegawaiId);
	$set_main_menu->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set_main_menu->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$set_main_menu->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
	
	if($set_main_menu->update())	$tempSimpan='simpan';//$mode = 'simpan';
	else							$tempSimpan='error';//$mode = 'error';
	
	//echo $set_main_menu->query;exit;
	//echo $reqRowId."-Data Tersimpan-".$mode;
}

if($tempSimpan == ""){}
else
{
	//print_r($reqRowDetilId);exit;
	for($i=0; $i < count($reqRowDetilId); $i++)
	{
		//set order detil
		$set_detil= new BeasiswaSertifikat();
		$set_detil->setField("BEASISWA_SERTIFIKAT_ID", $reqRowDetilId[$i]);
		$set_detil->setField("BEASISWA_ID", $reqRowId);
		$set_detil->setField("JENIS_SERTIFIKAT", $reqJenisSertifikatId[$i]);
		$set_detil->setField("LEMBAGA", $reqLembaga[$i]);
		$set_detil->setField("SKOR", $reqSkor[$i]);
		$set_detil->setField("TIPE_SERTIFIKAT", $reqTipeSertifikatId[$i]);
		$set_detil->setField("TANGGAL", dateToDBCheck($reqTanggal[$i]));
		
		if($reqRowDetilId[$i] == "")
		{
			$set_detil->setField("LAST_CREATE_USER", $userLogin->UID);
			$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set_detil->insert();
		}
		else
		{
			$set_detil->setField("LAST_UPDATE_USER", $userLogin->UID);
			$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
			$set_detil->update();
		}
		//if($i == 1)
//		{
//			echo $set_detil->query;exit;
//		}
		unset($set_detil);
	}
	echo $reqRowId."-Data Tersimpan-".$tempSimpan;
}
?>