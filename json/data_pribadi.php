<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base-diklat/Peserta.php");

$reqId= $userLogin->userPelamarId;

if($reqId == "")
{
	echo "autologin"; exit;
}

 // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

// insialisasi status
$statusAtasan='<br>❌ Nama Atasan Langsung';
$statusJabatanAtasan='<br>❌ Jabatan Atasan Langsung';
$statusTugas='<br>❌ Tugas Untuk Dua Posisi Terakhir';
$statusDataPekerjaan='<br>❌ Data Pekerjaan';
$statusKondisiPekerjaan='<br>❌ Kondisi Pekerjaan';
$statusMinat='<br>❌ Paling Disukai dari pekerjaan / jabatan';
$statusHarapan='<br>❌ Paling Tidak Disukai dari pekerjaan / jabatan';
$statusKelemahan='<br>❌ Kelemahan';
$statusKekuatan='<br>❌ Kekuatan';

$file = new FileHandler();
$reqKtp= httpFilterPost("reqKtp");
$reqNama= httpFilterPost("reqNama");
$reqNIP= httpFilterPost("reqNIP");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqTempatLahir= httpFilterPost("reqTempatLahir");
$reqTanggalLahir= httpFilterPost("reqTanggalLahir");
$reqAgama= httpFilterPost("reqAgama");
$reqEmail= httpFilterPost("reqEmail");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqStatusKawin= httpFilterPost("reqStatusKawin");
$reqAlamat= httpFilterPost("reqAlamat");
$reqStatusPegawai= httpFilterPost("reqStatusPegawai");
$reqTempatKerja= httpFilterPost("reqTempatKerja");
$reqAlamatTempatKerja= httpFilterPost("reqAlamatTempatKerja");
$reqSosmed= httpFilterPost("reqSosmed");
$reqAuto= httpFilterPost("reqAuto");
$reqHp= httpFilterPost("reqHp");
$reqPegawaiAtasanLangsungNama= httpFilterPost("reqPegawaiAtasanLangsungNama");
if($reqPegawaiAtasanLangsungNama!=''){
	$statusAtasan='';
}
$reqPegawaiAtasanLangsungJabatan= httpFilterPost("reqPegawaiAtasanLangsungJabatan");
if($reqPegawaiAtasanLangsungJabatan!=''){
	$statusJabatanAtasan='';
}
$reqPegawaiPangkatId= httpFilterPost("reqPegawaiPangkatId");
$reqKeluargaSaudara= httpFilterPost("reqKeluargaSaudara");
$reqKeluargaSaudaraJenisKelamin= httpFilterPost("reqKeluargaSaudaraJenisKelamin");
$reqKeluargaSaudaraStatus= httpFilterPost("reqKeluargaSaudaraStatus");
$reqKeluargaTempat= httpFilterPost("reqKeluargaTempat");
$reqKeluargaTgllahir= httpFilterPost("reqKeluargaTgllahir");
$reqKeluargaPendidikan= httpFilterPost("reqKeluargaPendidikan");
$reqKeluargaPekerjaan= httpFilterPost("reqKeluargaPekerjaan");

$reqPendidikanRiwayatPendidikanId= httpFilterPost("reqPendidikanRiwayatPendidikanId");
$reqPendidikanRiwayatNamaSekolah= httpFilterPost("reqPendidikanRiwayatNamaSekolah");
$reqPendidikanRiwayatJurusan= httpFilterPost("reqPendidikanRiwayatJurusan");
$reqPendidikanRiwayatTempat= httpFilterPost("reqPendidikanRiwayatTempat");
$reqPendidikanRiwayatTahunAwal= httpFilterPost("reqPendidikanRiwayatTahunAwal");
$reqPendidikanRiwayatTahunAkhir= httpFilterPost("reqPendidikanRiwayatTahunAkhir");
$reqPendidikanRiwayatId= httpFilterPost("reqPendidikanRiwayatId");
$reqPendidikanRiwayatKeterangan= httpFilterPost("reqPendidikanRiwayatKeterangan");

$reqNonPendidikanRiwayatJurusan= httpFilterPost("reqNonPendidikanRiwayatJurusan");
$reqNonPendidikanRiwayatTempat= httpFilterPost("reqNonPendidikanRiwayatTempat");
$reqNonPendidikanRiwayatTahun= httpFilterPost("reqNonPendidikanRiwayatTahun");
$reqNonPendidikanRiwayatKeterangan= httpFilterPost("reqNonPendidikanRiwayatKeterangan");

$reqJabatanRiwayatNama= httpFilterPost("reqJabatanRiwayatNama");
$reqJabatanRiwayatTahunAwal= httpFilterPost("reqJabatanRiwayatTahunAwal");
$reqJabatanRiwayatTahunAkhir= httpFilterPost("reqJabatanRiwayatTahunAkhir");
$reqJabatanRiwayatInstansi= httpFilterPost("reqJabatanRiwayatInstansi");

$reqJabatanRiwayatInfoTugasId= httpFilterPost("reqJabatanRiwayatInfoTugasId");
$reqJabatanRiwayatInfoTugasStatus= httpFilterPost("reqJabatanRiwayatInfoTugasStatus");

$reqJabatanRiwayatInfoTugasKeterangan= httpFilterPost("reqJabatanRiwayatInfoTugasKeterangan");

$reqDataId= httpFilterPost("reqDataId");
$reqGambaran= httpFilterPost("reqGambaran");
$reqUraikan= httpFilterPost("reqUraikan");
$reqTanggungJawabPekerjaan= httpFilterPost("reqTanggungJawabPekerjaan");

$reqKondisiKerjaId= httpFilterPost("reqKondisiKerjaId");
$reqBaikId= httpFilterPost("reqBaikId");
// print_r($reqBaikId);exit;
$reqCukupBaikId= httpFilterPost("reqCukupBaikId");
$reqPerluId= httpFilterPost("reqPerluId");
$reqKondisi= httpFilterPost("reqKondisi");
$reqAspek= httpFilterPost("reqAspek");

$reqMinatId= httpFilterPost("reqMinatId");
$reqSukai= httpFilterPost("reqSukai");
$reqTidakSukai= httpFilterPost("reqTidakSukai");

$reqKekuatanId= httpFilterPost("reqKekuatanId");
$reqKekuatan= httpFilterPost("reqKekuatan");
$reqKelemahan= httpFilterPost("reqKelemahan");




$tempPesertaId= $reqId;
$set= new Peserta();

$set->setField("NIK", $reqKtp);
$set->setField("NAMA", ucwordsPertama($reqNama));
$set->setField("JENIS_KELAMIN", $reqJenisKelamin);
$set->setField("TEMPAT_LAHIR", ucwordsPertama($reqTempatLahir));
$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTanggalLahir));
$set->setField("AGAMA", $reqAgama);
$set->setField("EMAIL", $reqEmail);
$set->setField("STATUS_KAWIN", $reqStatusKawin);
$set->setField("ALAMAT", $reqAlamat);
$set->setField("STATUS_PEGAWAI_ID", ValToNullDB($reqStatusPegawai));
$set->setField("TEMPAT_KERJA", $reqTempatKerja);
$set->setField("ALAMAT_TEMPAT_KERJA", $reqAlamatTempatKerja);
$set->setField("SOSIAL_MEDIA", $reqSosmed);
$set->setField("LAST_PANGKAT_ID", ValToNullDB($reqPegawaiPangkatId));
$set->setField("LAST_ATASAN_LANGSUNG_NAMA", $reqPegawaiAtasanLangsungNama);
$set->setField("LAST_ATASAN_LANGSUNG_JABATAN", $reqPegawaiAtasanLangsungJabatan);


$set->setField("HP", $reqHp);
$set->setField("PESERTA_ID", $tempPesertaId);

$tempStatusSimpan= "";

if($set->updateDataPribadi())
{
	$tempStatusSimpan= 1;// untuk upload file
}


if($tempStatusSimpan == "1")
{
	$deletesaudara= new Peserta();
	$deletesaudara->setField("PEGAWAI_ID", $reqId);
	$deletesaudara->deletesaudara();
	for($i=0; $i < count($reqKeluargaSaudara); $i++)
	{
		if(!empty($reqKeluargaSaudara[$i]))
		{
			$set= new Peserta();
			$set->setField("PEGAWAI_ID", $tempPesertaId);
			$set->setField("NAMA", setQuote($reqKeluargaSaudara[$i]));
			$set->setField("JENIS_KELAMIN", $reqKeluargaSaudaraJenisKelamin[$i]);
			$set->setField("STATUS", $reqKeluargaSaudaraStatus[$i]);
			$set->setField("TEMPAT", $reqKeluargaTempat[$i]);

			$cektanggal=explode("-",$reqKeluargaTgllahir[$i]);
			// print_r(strlen($cektanggal[0])); exit;
			if (strlen($cektanggal[0])==4){
				$set->setField("TGL_LAHIR", dateToDBCheck2($reqKeluargaTgllahir[$i]));
			}else{
				$set->setField("TGL_LAHIR", dateToDBCheck($reqKeluargaTgllahir[$i]));
			}
			$set->setField("PENDIDIKAN", $reqKeluargaPendidikan[$i]);
			$set->setField("PEKERJAAN", $reqKeluargaPekerjaan[$i]);
			$set->insertsaudara();
			// echo $set->query; exit;
		}
	}
	$deletesaudara= new Peserta();
	$deletesaudara->setField("PEGAWAI_ID", $reqId);
	$deletesaudara->deletependidikanriwayatNew();

	for($i=0; $i < count($reqPendidikanRiwayatNamaSekolah); $i++)
	{
		if(!empty($reqPendidikanRiwayatNamaSekolah[$i]))
		{
			$set= new Peserta();
			$set->setField("PEGAWAI_ID", $tempPesertaId);
			$set->setField("PENDIDIKAN_ID", $reqPendidikanRiwayatPendidikanId[$i]);
			$set->setField("NAMA_SEKOLAH", setQuote($reqPendidikanRiwayatNamaSekolah[$i]));
			$set->setField("JURUSAN", setQuote($reqPendidikanRiwayatJurusan[$i]));
			$set->setField("TEMPAT", setQuote($reqPendidikanRiwayatTempat[$i]));
			$set->setField("TAHUN_AWAL", ValToNullDB($reqPendidikanRiwayatTahunAwal[$i]));
			$set->setField("TAHUN_AKHIR", ValToNullDB($reqPendidikanRiwayatTahunAkhir[$i]));
			$set->setField("KETERANGAN", setQuote($reqPendidikanRiwayatKeterangan[$i]));
			$set->setField("RIWAYAT_PENDIDIKAN_ID", $reqPendidikanRiwayatId[$i]);
			$set->insertpendidikanriwayat();

		}
	}
	for($i=0; $i < count($reqNonPendidikanRiwayatJurusan); $i++)
	{	
		if(!empty($reqNonPendidikanRiwayatJurusan[$i]))
		{
			$set= new Peserta();
			$set->setField("PEGAWAI_ID", $tempPesertaId);
			$set->setField("JENIS_PELATIHAN", setQuote($reqNonPendidikanRiwayatJurusan[$i]));
			$set->setField("TEMPAT", setQuote($reqNonPendidikanRiwayatTempat[$i]));
			$set->setField("TAHUN", $reqNonPendidikanRiwayatTahun[$i]);
			$set->setField("KETERANGAN", setQuote($reqNonPendidikanRiwayatKeterangan[$i]));
			$set->insertpendidikanriwayatnonformal();
		}
	}
	$deletejabatan= new Peserta();
	$deletejabatan->setField("PEGAWAI_ID", $reqId);
	$deletejabatan->deletejabatanriwayat();

	for($i=0; $i < count($reqJabatanRiwayatNama); $i++)
	{
		if(!empty($reqJabatanRiwayatNama[$i]))
		{
			$set= new Peserta();
			$set->setField("PEGAWAI_ID", $tempPesertaId);
			$set->setField("JABATAN", setQuote($reqJabatanRiwayatNama[$i]));
			$set->setField("TAHUN_AWAL",$reqJabatanRiwayatTahunAwal[$i]);
			$set->setField("TAHUN_AKHIR", $reqJabatanRiwayatTahunAkhir[$i]);
			$set->setField("UNIT_KERJA", setQuote($reqJabatanRiwayatInstansi[$i]));
			$set->insertjabatanriwayat();
		}
	}
	for($i=0; $i < count($reqJabatanRiwayatInfoTugasKeterangan); $i++)
	{
		$set= new Peserta();
		$set->setField("PEGAWAI_ID", $tempPesertaId);
		$set->setField("STATUS", $reqJabatanRiwayatInfoTugasStatus[$i]);
		$set->setField("KETERANGAN", setQuote($reqJabatanRiwayatInfoTugasKeterangan[$i]));
		if($reqJabatanRiwayatInfoTugasKeterangan[$i]==''){
			$statusTugas='';
		}
		else{
			$statusTugas='<br> X Tugas Untuk Dua Posisi Terakhir';
		}

		$set->setField("RIWAYAT_JABATAN_INFO_ID", $reqJabatanRiwayatInfoTugasId[$i]);
		if(empty($reqJabatanRiwayatInfoTugasId[$i]))
			$set->insertjabatanriwayatinfo();
		else
			$set->updatejabatanriwayatinfo();
	}
	$set= new Peserta();
	$set->setField("DATA_PEKERJAAN_ID", $reqDataId);
	$set->setField("PEGAWAI_ID", $tempPesertaId);
	$set->setField("GAMBARAN", $reqGambaran);
	$set->setField("URAIKAN", $reqUraikan);
	$set->setField("TANGGUNG_JAWAB", $reqTanggungJawabPekerjaan);
	if($reqGambaran!='' && $reqTanggungJawabPekerjaan!='' && $reqUraikan!=''){
		$statusDataPekerjaan='';
	}
	if(empty($reqDataId))
		$set->insertDataPekerjaan();
	else
		$set->updateDataPekerjaan();


	$set= new Peserta();
	$set->setField("KONDISI_KERJA_ID", $reqKondisiKerjaId);
	$set->setField("PEGAWAI_ID", $tempPesertaId);
	$set->setField("BAIK_ID", ValToNullDB($reqBaikId));
	$set->setField("CUKUP_ID", ValToNullDB($reqCukupBaikId));
	$set->setField("PERLU_ID", ValToNullDB($reqPerluId));
	$set->setField("KONDISI", $reqKondisi);
	$set->setField("ASPEK", $reqAspek);
	if($reqBaikId!='' || $reqKondisi!='' || $reqAspek!=''){
		$statusKondisiPekerjaan='';
	}
	if(empty($reqKondisiKerjaId))
		$set->insertKondisiKerja();
	else
		$set->updateKondisiKerja();

	$set= new Peserta();
	$set->setField("MINAT_HARAPAN_ID", $reqMinatId);
	$set->setField("PEGAWAI_ID", $tempPesertaId);
	$set->setField("SUKAI", $reqSukai);
	$set->setField("TIDAK_SUKAI", $reqTidakSukai);
	if($reqSukai!=''){
		$statusMinat='';
	}
	if($reqTidakSukai!=''){
		$statusHarapan='';
	}

	if(empty($reqMinatId))
		$set->insertMinat();
	else
		$set->updateMinat();

	$set= new Peserta();
	$set->setField("KEKUATAN_KELEMAHAN_ID", $reqKekuatanId);
	$set->setField("PEGAWAI_ID", $tempPesertaId);
	
	$set->setField("KEKUATAN", $reqKekuatan);
	$set->setField("KELEMAHAN", $reqKelemahan);

	if($reqKekuatan!=''){
		$statusKekuatan='';
	}
	if($reqKelemahan!=''){
		$statusKelemahan='';
	}

	if(empty($reqKekuatanId))
		$set->insertKekuatan();
	else
		$set->updateKekuatan();
	echo $tempPesertaId."-<center><h3><b>Data berhasil di simpan</b></h3>".$statusAtasan.$statusJabatanAtasan.$statusTugas.$statusDataPekerjaan.$statusKondisiPekerjaan.$statusHarapan.$statusKelemahan.$statusMinat.$statusKekuatan."</center>";


}
else
{
	echo "xxx-Data gagal disimpan.";
}





// echo $set->query;exit;
unset($set);
?>