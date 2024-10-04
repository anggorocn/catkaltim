<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");
include_once("../WEB/classes/base-ikk/PenilaianRekomendasi.php");
include_once("../WEB/classes/base/PenilaianKompetensi.php");

// echo 'asda';exit;

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}



$reqJadwalPegawaiDetilId= $_POST["reqJadwalPegawaiDetilId"];
$reqJadwalPegawaiTesId= $_POST["reqJadwalPegawaiTesId"];
$reqJadwalPegawaiPenggalianId= $_POST["reqJadwalPegawaiPenggalianId"];
$reqJadwalPegawaiLevelDataId= $_POST["reqJadwalPegawaiLevelDataId"];
$reqJadwalPegawaiIndikatorDataId= $_POST["reqJadwalPegawaiIndikatorDataId"];
$reqJadwalPegawaiDataId= $_POST["reqJadwalPegawaiDataId"];
$reqJadwalPegawaiJadwalAsesorId= $_POST["reqJadwalPegawaiJadwalAsesorId"];
$reqJadwalPegawaiAtributId= $_POST["reqJadwalPegawaiAtributId"];
$reqJadwalPegawaiPegawaiId= $_POST["reqJadwalPegawaiPegawaiId"];
$reqJadwalPegawaiAsesorId= $_POST["reqJadwalPegawaiAsesorId"];
$reqJadwalPegawaiFormPermenId= $_POST["reqJadwalPegawaiFormPermenId"];

$reqDetilAtributDetilAtributId= $_POST["reqDetilAtributDetilAtributId"];
$reqDetilAtributJadwalTesId= $_POST["reqDetilAtributJadwalTesId"];
$reqDetilAtributPenggalianId= $_POST["reqDetilAtributPenggalianId"];
$reqDetilAtributJadwalPegawaiDataId= $_POST["reqDetilAtributJadwalPegawaiDataId"];
$reqDetilAtributJadwalAsesorId= $_POST["reqDetilAtributJadwalAsesorId"];
$reqDetilAtributAtributId= $_POST["reqDetilAtributAtributId"];
$reqDetilAtributPegawaiId= $_POST["reqDetilAtributPegawaiId"];
$reqDetilAtributAsesorId= $_POST["reqDetilAtributAsesorId"];
$reqDetilAtributFormPermenId= $_POST["reqDetilAtributFormPermenId"];
$reqDetilAtributNilaiStandar= $_POST["reqDetilAtributNilaiStandar"];
$reqDetilAtributNilai= $_POST["reqDetilAtributNilai"];
$decimalValue= $_POST["decimalValue"];
$reqDetilAtributGap= $_POST["reqDetilAtributGap"];
$reqDetilAtributCatatan= $_POST["reqDetilAtributCatatan"];

$reqPenilaianPotensiDetilId= $_POST["reqPenilaianPotensiDetilId"];
$reqPenilaianPotensiId= $_POST["reqPenilaianPotensiId"];

$reqPenilaianPotensiNilai= $_POST["reqPenilaianPotensiNilai"];
$reqPenilaianPotensiNilaiDecimal= $_POST["reqPenilaianPotensiNilaiDecimal"];

$reqPenilaianPotensiGap= $_POST["reqPenilaianPotensiGap"];
$reqPenilaianPotensiCatatan= $_POST["reqPenilaianPotensiCatatan"];
$reqPenilaianPotensiBukti= $_POST["reqPenilaianPotensiBukti"];
$reqPenilaianPotensiDeskripsi= $_POST["reqPenilaianPotensiDeskripsi"];
$reqPenilaianPotensiKepribadian= $_POST["reqPenilaianPotensiKepribadian"];

$reqKesimpulanPegawaiId= $_POST["reqKesimpulanPegawaiId"];
$reqKesimpulanJadwalTesId= $_POST["reqKesimpulanJadwalTesId"];

$reqPenilaianPotensiStrength= $_POST["reqPenilaianPotensiStrength"];
$reqPenilaianPotensiWeaknes= $_POST["reqPenilaianPotensiWeaknes"];
$reqPenilaianPotensiKesimpulan= $_POST["reqPenilaianPotensiKesimpulan"];
$reqPenilaianPotensiSaranPengembangan= $_POST["reqPenilaianPotensiSaranPengembangan"];
$reqPenilaianPotensiSaranPenempatan= $_POST["reqPenilaianPotensiSaranPenempatan"];
$reqPenilaianKeteranganKepribadian= $_POST["reqPenilaianKeteranganKepribadian"];
$reqPenilaianPotensiProfilKompetensi= $_POST["reqPenilaianPotensiProfilKompetensi"];

$reqLainPenilaianPotensiId= $_POST["reqLainPenilaianPotensiId"];

$reqPenilaianPotensiProfilKepribadian= $_POST["reqPenilaianPotensiProfilKepribadian"];
$reqPenilaianPotensiKesesuaianRumpun= $_POST["reqPenilaianPotensiKesesuaianRumpun"];

$reqPenilaianKompetensiDetilId= $_POST["reqPenilaianKompetensiDetilId"];
$reqPenilaianKompetensiNilai= $_POST["reqPenilaianKompetensiNilai"];
// print_r($reqPenilaianKompetensiNilai); exit;
$decimalValueNilaiAkhir= $_POST["decimalValueNilaiAkhir"];
$reqPenilaianKompetensiGap= $_POST["reqPenilaianKompetensiGap"];
$reqPenilaianKompetensiCatatan= $_POST["reqPenilaianKompetensiCatatan"];
$reqPenilaianKompetensiBukti= $_POST["reqPenilaianKompetensiBukti"];
$reqPenilaianKompetensiBolehSimpan= $_POST["reqPenilaianKompetensiBolehSimpan"];
$reqPenilaianPotensiKepribadianPegawaiId= $_POST["reqPenilaianPotensiKepribadianPegawaiId"];
$reqPenilaianPotensiKepribadianJadwalTesId= $_POST["reqPenilaianPotensiKepribadianJadwalTesId"];

$reqPenilaianKperibadianPegawaiId= $_POST["reqPenilaianKperibadianPegawaiId"];
$reqPenilaianKperibadianjadwalId= $_POST["reqPenilaianKperibadianjadwalId"];

$set_detil= new JadwalPegawaiDetil();
$set_detil->setField('JADWAL_TES_ID',$reqJadwalPegawaiTesId[0]);
$set_detil->setField('PENGGALIAN_ID',$reqJadwalPegawaiPenggalianId[0]);
$set_detil->setField('PEGAWAI_ID',$reqJadwalPegawaiPegawaiId[0]);
$set_detil->deletePenggalianTesPegawai();

// simpan data indikator per penggalian
for($i=0; $i < count($reqJadwalPegawaiDetilId); $i++)
{
	$set_detil= new JadwalPegawaiDetil();
	$set_detil->setField('JADWAL_PEGAWAI_DETIL_ID',$reqJadwalPegawaiDetilId[$i]);
	$set_detil->setField('JADWAL_TES_ID',$reqJadwalPegawaiTesId[$i]);
	$set_detil->setField('PENGGALIAN_ID',$reqJadwalPegawaiPenggalianId[$i]);
	$set_detil->setField('LEVEL_ID',$reqJadwalPegawaiLevelDataId[$i]);
	$set_detil->setField('INDIKATOR_ID',$reqJadwalPegawaiIndikatorDataId[$i]);
	$set_detil->setField('JADWAL_PEGAWAI_ID',$reqJadwalPegawaiDataId[$i]);
	$set_detil->setField('JADWAL_ASESOR_ID',$reqJadwalPegawaiJadwalAsesorId[$i]);
	$set_detil->setField('ATRIBUT_ID',$reqJadwalPegawaiAtributId[$i]);
	$set_detil->setField('PEGAWAI_ID',$reqJadwalPegawaiPegawaiId[$i]);
	$set_detil->setField('ASESOR_ID',$reqJadwalPegawaiAsesorId[$i]);
	$set_detil->setField('FORM_PERMEN_ID',$reqJadwalPegawaiFormPermenId[$i]);
	
	$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");

	$set_detil->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	$set_detil->insert();
	unset($set_detil);
}

$set_detil= new JadwalPegawaiDetil();
$set_detil->setField('JADWAL_TES_ID',$reqJadwalPegawaiTesId[0]);
$set_detil->setField('PENGGALIAN_ID',$reqJadwalPegawaiPenggalianId[0]);
$set_detil->setField('PEGAWAI_ID',$reqJadwalPegawaiPegawaiId[0]);
$set_detil->deletePenggalianTesAtributPegawai();
// simpan data atribut per penggalian
for($i=0; $i < count($reqDetilAtributDetilAtributId); $i++)
{
	$set_detil= new JadwalPegawaiDetil();
	$set_detil->setField('JADWAL_PEGAWAI_DETIL_ATRIBUT_ID',$reqDetilAtributDetilAtributId[$i]);
	$set_detil->setField('JADWAL_TES_ID',$reqDetilAtributJadwalTesId[$i]);
	$set_detil->setField('PENGGALIAN_ID',$reqDetilAtributPenggalianId[$i]);
	$set_detil->setField('JADWAL_PEGAWAI_ID',$reqDetilAtributJadwalPegawaiDataId[$i]);
	$set_detil->setField('JADWAL_ASESOR_ID',$reqDetilAtributJadwalAsesorId[$i]);
	$set_detil->setField('ATRIBUT_ID',$reqDetilAtributAtributId[$i]);
	$set_detil->setField('PEGAWAI_ID',$reqDetilAtributPegawaiId[$i]);
	$set_detil->setField('ASESOR_ID',$reqDetilAtributAsesorId[$i]);
	$set_detil->setField('FORM_PERMEN_ID',$reqDetilAtributFormPermenId[$i]);
	
	$set_detil->setField('NILAI_STANDAR',$reqDetilAtributNilaiStandar[$i]);
	if($decimalValue[$i]==''){
		$decimal=0;
	}
	else{
		$decimal=$decimalValue[$i];
	}
	$set_detil->setField('decimalValue',"0.".$decimal);
	$set_detil->setField('NILAI',$reqDetilAtributNilai[$i].".".$decimal);
	$set_detil->setField('GAP',$reqDetilAtributGap[$i]);
	$set_detil->setField('CATATAN',setQuote($reqDetilAtributCatatan[$i]));

	$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");

	$set_detil->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");

	echo " <br>-";
	if($set_detil->insertdetil()){
		echo "Data berhasil disimpan";

	}
	unset($set_detil);
}

// print_r($reqPenilaianPotensiDetilId);exit;
for($i=0; $i < count($reqPenilaianPotensiDetilId); $i++)
{
	if($reqPenilaianPotensiDetilId[$i] == ""){}
	else
	{
		$set_detil= new JadwalPegawaiDetil();
		$set_detil->setField('NILAI',$reqPenilaianPotensiNilai[$i]);
		$set_detil->setField('GAP',$reqPenilaianPotensiGap[$i]);
		$set_detil->setField('CATATAN', setQuote($reqPenilaianPotensiCatatan[$i]));
		$set_detil->setField('BUKTI', setQuote($reqPenilaianPotensiBukti[$i]));
		$set_detil->setField('PENILAIAN_DETIL_ID',$reqPenilaianPotensiDetilId[$i]);
		$set_detil->updatePenilaian();
	}
}
// exit;

// onecheck: awal tambahan rekomendasi
if(!empty($reqPenilaianPotensiStrength))
{
	$modetipe= "profil_kekuatan";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqKesimpulanPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqKesimpulanJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqPenilaianPotensiStrength);$i++)
	{
		if(empty($reqPenilaianPotensiStrength[$i]) || $reqPenilaianPotensiStrength[$i] == "<br>")
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqKesimpulanPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqPenilaianPotensiStrength[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqKesimpulanJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}

if(!empty($reqPenilaianPotensiWeaknes))
{
	$modetipe= "profil_kelemahan";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqKesimpulanPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqKesimpulanJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqPenilaianPotensiWeaknes);$i++)
	{
		if(empty($reqPenilaianPotensiWeaknes[$i]) || $reqPenilaianPotensiWeaknes[$i] == "<br>")
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqKesimpulanPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqPenilaianPotensiWeaknes[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqKesimpulanJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}

if(!empty($reqPenilaianPotensiKesimpulan))
{
	$modetipe= "profil_rekomendasi";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqKesimpulanPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqKesimpulanJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqPenilaianPotensiKesimpulan);$i++)
	{
		if(empty($reqPenilaianPotensiKesimpulan[$i]) || $reqPenilaianPotensiKesimpulan[$i] == "<br>")
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqKesimpulanPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqPenilaianPotensiKesimpulan[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqKesimpulanJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}

if(!empty($reqPenilaianPotensiSaranPengembangan))
{
	$modetipe= "profil_saran_pengembangan";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqKesimpulanPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqKesimpulanJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqPenilaianPotensiSaranPengembangan);$i++)
	{
		if(empty($reqPenilaianPotensiSaranPengembangan[$i]) || $reqPenilaianPotensiSaranPengembangan[$i] == "<br>")
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqKesimpulanPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqPenilaianPotensiSaranPengembangan[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqKesimpulanJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}

if(!empty($reqPenilaianPotensiSaranPenempatan))
{
	$modetipe= "profil_saran_penempatan";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqKesimpulanPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqKesimpulanJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqPenilaianPotensiSaranPenempatan);$i++)
	{
		if(empty($reqPenilaianPotensiSaranPenempatan[$i]) || $reqPenilaianPotensiSaranPenempatan[$i] == "<br>")
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqKesimpulanPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqPenilaianPotensiSaranPenempatan[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqKesimpulanJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}


	$modetipe= "profil_kepribadian";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqPenilaianKperibadianPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqPenilaianKperibadianjadwalId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	  $nomorurut= 1;
	  
	  $set_detil= new PenilaianRekomendasi();
	  // echo $reqPenilaianKeteranganKepribadian; exit;
	  $set_detil->setField('PEGAWAI_ID', $reqPenilaianKperibadianPegawaiId);
	  $set_detil->setField('KETERANGAN', setQuote($reqPenilaianKeteranganKepribadian));
	  $set_detil->setField('JADWAL_TES_ID', $reqPenilaianKperibadianjadwalId);
	  $set_detil->setField('TIPE', $modetipe);
	  $set_detil->setField('NO_URUT', $nomorurut);
	  $set_detil->insert();


if(!empty($reqPenilaianPotensiProfilKompetensi))
{
	$modetipe= "profil_kompetensi";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqKesimpulanPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqKesimpulanJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqPenilaianPotensiProfilKompetensi);$i++)
	{
		if(empty($reqPenilaianPotensiProfilKompetensi[$i]) || $reqPenilaianPotensiProfilKompetensi[$i] == "<br>")
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqKesimpulanPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqPenilaianPotensiProfilKompetensi[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqKesimpulanJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}

// set value kompetensi
for($i=0; $i < count($reqPenilaianKompetensiDetilId); $i++)
{
	if($reqPenilaianKompetensiBolehSimpan[$i] == "1")
	{
		if($reqPenilaianKompetensiDetilId[$i] == ""){}
		else
		{
			$set_detil= new JadwalPegawaiDetil();
			if($decimalValueNilaiAkhir[$i]==''){
				$decimalValueNilaiAkhir[$i]=0;
			}
			if($reqPenilaianKompetensiNilai[$i]==''){
				$reqPenilaianKompetensiNilai[$i]=0;
			}
			$set_detil->setField('NILAI',$reqPenilaianKompetensiNilai[$i].".".$decimalValueNilaiAkhir[$i]);
			$set_detil->setField('GAP',$reqPenilaianKompetensiGap[$i]);
			$set_detil->setField('BUKTI', setQuote($reqPenilaianKompetensiCatatan[$i]));
			$set_detil->setField('CATATAN', setQuote($reqPenilaianKompetensiBukti[$i]));
			$set_detil->setField('PENILAIAN_DETIL_ID',$reqPenilaianKompetensiDetilId[$i]);
			$set_detil->updatePenilaian();
			// echo $set_detil->query;exit();
		}
	}
}

$reqNilaiAkhirPegawaiId= $_POST["reqNilaiAkhirPegawaiId"];
$reqNilaiAkhirJadwalTesId= $_POST["reqNilaiAkhirJadwalTesId"];
$reqNilaiAkhirSaranPengembangan= $_POST["reqNilaiAkhirSaranPengembangan"];
if(!empty($reqNilaiAkhirSaranPengembangan))
{
	$modetipe= "area_pengembangan";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqNilaiAkhirPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqNilaiAkhirJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqNilaiAkhirSaranPengembangan);$i++)
	{
		if(empty($reqNilaiAkhirSaranPengembangan[$i]))
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqNilaiAkhirPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqNilaiAkhirSaranPengembangan[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqNilaiAkhirJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}

$reqNilaiAkhirPegawaiId= $_POST["reqNilaiAkhirPegawaiId"];
$reqNilaiAkhirJadwalTesId= $_POST["reqNilaiAkhirJadwalTesId"];
$reqUraianKompetensi= $_POST["reqUraianKompetensi"];
if(!empty($reqUraianKompetensi))
{
	$modetipe= "uraian_kompetensi";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqNilaiAkhirPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqNilaiAkhirJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqUraianKompetensi);$i++)
	{
		if(empty($reqUraianKompetensi[$i]))
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqNilaiAkhirPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqUraianKompetensi[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqNilaiAkhirJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}

$reqNilaiAkhirPegawaiId= $_POST["reqNilaiAkhirPegawaiId"];
$reqNilaiAkhirJadwalTesId= $_POST["reqNilaiAkhirJadwalTesId"];
$reqUraianPotensi= $_POST["reqUraianPotensi"];
if(!empty($reqUraianPotensi))
{
	$modetipe= "uraian_potensi";
	$set_detil= new PenilaianRekomendasi();
	$set_detil->setField("PEGAWAI_ID", $reqNilaiAkhirPegawaiId);
	$set_detil->setField("JADWAL_TES_ID", $reqNilaiAkhirJadwalTesId);
	$set_detil->setField("TIPE", $modetipe);
	$set_detil->deletemode();
	unset($set_detil);

	$nomorurut= 1;
	for($i=0;$i<count($reqUraianPotensi);$i++)
	{
		if(empty($reqUraianPotensi[$i]))
		{}
		else
		{
			$set_detil= new PenilaianRekomendasi();
			$set_detil->setField('PEGAWAI_ID', $reqNilaiAkhirPegawaiId);
			$set_detil->setField('KETERANGAN', setQuote($reqUraianPotensi[$i]));
			$set_detil->setField('JADWAL_TES_ID', $reqNilaiAkhirJadwalTesId);
			$set_detil->setField('TIPE', $modetipe);
			$set_detil->setField('NO_URUT', $nomorurut);
			$set_detil->insert();
			$nomorurut++;
		}
	}
}


$reqKompetensiDasarId= $_POST["reqKompetensiDasarId"];
$reqKompetensiNilai= $_POST["reqKompetensiNilai"];
$reqKompetensiKet= $_POST["reqKompetensiKet"];
$reqKompetensiPenilaianId= $_POST["reqKompetensiPenilaianId"];
// print_r($reqKompetensiDasarId); exit;
for($i=0; $i < count($reqKompetensiDasarId); $i++)
{
	$set_detil= new PenilaianKompetensi();
	$set_detil->setField('penilaian_kompetensi_dasar_id',$reqKompetensiDasarId[$i]);
	$set_detil->setField('penilaian',$reqKompetensiNilai[$i]);
	$set_detil->setField('keterangan',$reqKompetensiKet[$i]);
	$set_detil->setField('penilaian_kompetensi_penilaian_id',$reqKompetensiPenilaianId[$i]);
	$set_detil->setField('pegawai_id',$reqPenilaianKperibadianPegawaiId);
	$set_detil->setField('jadwal_tes_id',$reqPenilaianKperibadianjadwalId);

	if($reqKompetensiPenilaianId[$i]==''){
		$set_detil->insert();
	}
	else{
		$set_detil->update();
	}
	unset($set_detil);
}
?>