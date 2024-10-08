<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");
include_once("../WEB/classes/base-ikk/PenilaianRekomendasi.php");

// echo 'asda';exit;

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$tempAsesorId= $userLogin->userAsesorId;
if($tempAsesorId == "")
{
	exit;
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
$reqDetilAtributGap= $_POST["reqDetilAtributGap"];
$reqDetilAtributCatatan= $_POST["reqDetilAtributCatatan"];

$reqPenilaianPotensiDetilId= $_POST["reqPenilaianPotensiDetilId"];
$reqPenilaianPotensiId= $_POST["reqPenilaianPotensiId"];

$reqPenilaianPotensiNilai= $_POST["reqPenilaianPotensiNilai"];
$reqPenilaianPotensiGap= $_POST["reqPenilaianPotensiGap"];
$reqPenilaianPotensiCatatan= $_POST["reqPenilaianPotensiCatatan"];
$reqPenilaianPotensiBukti= $_POST["reqPenilaianPotensiBukti"];
$reqPenilaianPotensiDeskripsi= $_POST["reqPenilaianPotensiDeskripsi"];

$reqLainPenilaianPotensiId= $_POST["reqLainPenilaianPotensiId"];
$reqPenilaianPotensiStrength= $_POST["reqPenilaianPotensiStrength"];
$reqPenilaianPotensiWeaknes= $_POST["reqPenilaianPotensiWeaknes"];
$reqPenilaianPotensiKesimpulan= $_POST["reqPenilaianPotensiKesimpulan"];
$reqPenilaianPotensiSaranPengembangan= $_POST["reqPenilaianPotensiSaranPengembangan"];
$reqPenilaianPotensiSaranPenempatan= $_POST["reqPenilaianPotensiSaranPenempatan"];


$reqPenilaianKompetensiDetilId= $_POST["reqPenilaianKompetensiDetilId"];
$reqPenilaianKompetensiNilai= $_POST["reqPenilaianKompetensiNilai"];
$reqPenilaianKompetensiGap= $_POST["reqPenilaianKompetensiGap"];
$reqPenilaianKompetensiCatatan= $_POST["reqPenilaianKompetensiCatatan"];
$reqPenilaianKompetensiBukti= $_POST["reqPenilaianKompetensiBukti"];
$reqPenilaianKompetensiBolehSimpan= $_POST["reqPenilaianKompetensiBolehSimpan"];



$set_detil= new JadwalPegawaiDetil();
$set_detil->setField('JADWAL_TES_ID',$reqJadwalPegawaiTesId[0]);
$set_detil->setField('PENGGALIAN_ID',$reqJadwalPegawaiPenggalianId[0]);
$set_detil->setField('PEGAWAI_ID',$reqJadwalPegawaiPegawaiId[0]);
$set_detil->deletePenggalianTesPegawai();
// echo $set_detil->query;exit();

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

	// if($reqJadwalPegawaiDetilId[$i] == "")
	// {
		$set_detil->insert();
	// }
	// else
	// {
	// 	$set_detil->update();
	// }
	//echo $set_detil->query;exit;
	unset($set_detil);
}

$set_detil= new JadwalPegawaiDetil();
$set_detil->setField('JADWAL_TES_ID',$reqJadwalPegawaiTesId[0]);
$set_detil->setField('PENGGALIAN_ID',$reqJadwalPegawaiPenggalianId[0]);
$set_detil->setField('PEGAWAI_ID',$reqJadwalPegawaiPegawaiId[0]);
$set_detil->deletePenggalianTesAtributPegawai();
// echo $set_detil->query;exit();

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
	$set_detil->setField('NILAI',$reqDetilAtributNilai[$i]);
	$set_detil->setField('GAP',$reqDetilAtributGap[$i]);
	$set_detil->setField('CATATAN',setQuote($reqDetilAtributCatatan[$i]));

	$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
	$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");

	$set_detil->setField("LAST_UPDATE_USER", $userLogin->idUser);
	$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");

	// if($reqDetilAtributDetilAtributId[$i] == "")
	// {
		$set_detil->insertdetil();
	// }
	// else
	// {
	// 	$set_detil->update();
	// }
	// echo $set_detil->query;exit;
	// echo $set_detil->query;
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

if(!empty($reqLainPenilaianPotensiId))
{
	$set_text= new JadwalPegawaiDetil();
	$set_text->setField('CATATAN_STRENGTH', setQuote($reqPenilaianPotensiStrength));
	$set_text->setField('CATATAN_WEAKNES', setQuote($reqPenilaianPotensiWeaknes));
	$set_text->setField('KESIMPULAN', setQuote($reqPenilaianPotensiKesimpulan));
	$set_text->setField('SARAN_PENGEMBANGAN', setQuote($reqPenilaianPotensiSaranPengembangan));
	$set_text->setField('SARAN_PENEMPATAN', setQuote($reqPenilaianPotensiSaranPenempatan));
	$set_text->setField('PENILAIAN_ID',$reqLainPenilaianPotensiId);
	$set_text->updatePenilaianText();
	 // echo $set_text->query;exit();
}

// print_r($reqPenilaianKompetensiDetilId);exit;
// set value kompetensi
for($i=0; $i < count($reqPenilaianKompetensiDetilId); $i++)
{
	if($reqPenilaianKompetensiBolehSimpan[$i] == "1")
	{
		if($reqPenilaianKompetensiDetilId[$i] == ""){}
		else
		{
			$set_detil= new JadwalPegawaiDetil();
			$set_detil->setField('NILAI',$reqPenilaianKompetensiNilai[$i]);
			$set_detil->setField('GAP',$reqPenilaianKompetensiGap[$i]);
			$set_detil->setField('BUKTI', setQuote($reqPenilaianKompetensiCatatan[$i]));
			$set_detil->setField('CATATAN', setQuote($reqPenilaianKompetensiBukti[$i]));
			$set_detil->setField('PENILAIAN_DETIL_ID',$reqPenilaianKompetensiDetilId[$i]);
			$set_detil->updatePenilaian();
			// echo $set_detil->query;exit();
		}
	}
}

$reqNilaiAkhirSaranPengembangan= $_POST["reqNilaiAkhirSaranPengembangan"];
$reqNilaiAkhirSaranPengembanganPegawaiId= $_POST["reqNilaiAkhirSaranPengembanganPegawaiId"];
$reqNilaiAkhirSaranPengembanganJadwalTesId= $_POST["reqNilaiAkhirSaranPengembanganJadwalTesId"];


$statement= " AND A.PEGAWAI_ID = ".$reqNilaiAkhirSaranPengembanganPegawaiId." AND A.JADWAL_TES_ID = ".$reqNilaiAkhirSaranPengembanganJadwalTesId;
$set= new PenilaianRekomendasi();
$set->selectByParams(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();
$reqPenilaianRekomendasiId= $set->getField("PENILAIAN_REKOMENDASI_ID");
unset($set);
// echo "--".$reqPenilaianRekomendasiId;exit();

$set= new PenilaianRekomendasi();
$set->setField("JADWAL_TES_ID", $reqNilaiAkhirSaranPengembanganJadwalTesId);
$set->setField("PEGAWAI_ID", $reqNilaiAkhirSaranPengembanganPegawaiId);
$set->setField('KETERANGAN', setQuote($reqNilaiAkhirSaranPengembangan));
if($reqPenilaianRekomendasiId == "")
	$set->insert();
else
	$set->update();

// exit();
echo "Data berhasil disimpan";
?>