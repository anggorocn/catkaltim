<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");
include_once("../WEB/classes/base-ikk/PenilaianRekomendasi.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/classes/base-cat/UploadFileUjian.php");
include_once("../WEB/classes/base/UploadFile.php"); 
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base/Penggalian.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/PermohonanFile.php");
include_once("../WEB/classes/base/PenilaianKompetensi.php");
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// echo $actual_link; exit;
$reqEror=httpFilterGet("reqEror");

$tempAsesorId= $userLogin->userAsesorId;
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqSelectPenggalianId= httpFilterGet("reqSelectPenggalianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqTanggalTes= httpFilterGet("reqTanggalTes");

$statement= " AND A.TANGGAL_TES='".dateToPage($reqTanggalTes)."' and pegawai_id='".$reqPegawaiId."'";
$set= new CetakanPdf();
$set->selectByParamsJadwalFormula($statement);
$set->firstRow();
$reqFormulaId= $set->getField("FORMULA_ID");
$reqTtdAsesor= $set->getField("TTD_ASESOR");
$reqTtdPimpinan= $set->getField("TTD_PIMPINAN");
$reqNipAsesor= $set->getField("NIP_ASESOR");
$reqNipPimpinan= $set->getField("NIP_PIMPINAN");
$TanggalTes = getFormattedDate($set->getField("TANGGAL_TES"));
$reqKeterangan= $set->getField("KETERANGAN"); 
$reqJadwalTesId= $set->getField("jadwal_tes_id"); 


$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new Penilaian();
$set->selectByParamsTahunPenilaian($statement);
$set->firstRow();
$reqTahun= $set->getField("TAHUN");

//hasil cat
$setJadwal= new JadwalTesSimulasiAsesor();
$index_dataJadwal= 0;
$arrDataJadwal="";
$statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId." AND PARENT_ID = '0' and kategori = '2' ";
$setJadwal->selectByParamsJadwalTahap($statement);
// echo $setJadwal->query;exit;
while($setJadwal->nextRow())
{ 
  $arrDataJadwal[$index_dataJadwal]["FORMULA_ASSESMENT_ID"]= $setJadwal->getField("FORMULA_ASSESMENT_ID");
  $arrDataJadwal[$index_dataJadwal]["TIPE_UJIAN_ID"]= $setJadwal->getField("TIPE_UJIAN_ID");
  $arrDataJadwal[$index_dataJadwal]["TIPE"]= $setJadwal->getField("TIPE");
  $arrDataJadwal[$index_dataJadwal]["UJIAN_TAHAP_ID"]= $setJadwal->getField("UJIAN_TAHAP_ID");
  $arrDataJadwal[$index_dataJadwal]["JUMLAH_SOAL_UJIAN_TAHAP"]= $setJadwal->getField("JUMLAH_SOAL_UJIAN_TAHAP");
  $arrDataJadwal[$index_dataJadwal]["BOBOT"]= $setJadwal->getField("BOBOT");
  $arrDataJadwal[$index_dataJadwal]["MENIT_SOAL"]= $setJadwal->getField("MENIT_SOAL");
  $arrDataJadwal[$index_dataJadwal]["JUMLAH_SOAL"]= $setJadwal->getField("JUMLAH_SOAL");
  $arrDataJadwal[$index_dataJadwal]["ID"]= $setJadwal->getField("ID");
  $arrDataJadwal[$index_dataJadwal]["PARENT_ID"]= $setJadwal->getField("PARENT_ID");
  $arrDataJadwal[$index_dataJadwal]["TIPE_READONLY"]= $setJadwal->getField("TIPE_READONLY");
  $arrDataJadwal[$index_dataJadwal]["STATUS_ANAK"]= $setJadwal->getField("STATUS_ANAK");
  $index_dataJadwal++;
}
$jumlah_cat= $index_dataJadwal;
 // print_r($arrDataJadwal);exit();

//view asesment
$set= new CetakanPdf();
$statement1= " AND A.PEGAWAI_ID= ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$statement2= " AND B.PEGAWAI_ID= ".$reqPegawaiId;
 // $set->selectByParamsMonitoringTableTalentPoolJPMMonitoring(array(), -1, -1, $statement1,$statement2);
$set->selectByParamsMonitoringTableTalentPoolMonitoring(array(), -1, -1, $statement1, $statement2, "", $reqTahun, "");
// echo $set->query;exit;
$set->firstRow();
// $namaKuadran= $set->getField("NAMA_KUADRAN");
// $kodeKuadran= $set->getField("KODE_KUADRAN");
// $rekomKuadran= $set->getField("REKOMENDASI_KUADRAN");
$namaKuadran= $set->getField("NAMA_KUADRAN");
$kodeKuadran= $set->getField("KODE_KUADRAN");
$rekomKuadran= $set->getField("NAMA_KUADRAN");

$checktray= new UploadFileUjian();
$statement= " AND A.PEGAWAI_ID= ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$checktray->selectByParams(array(), -1, -1, $statement);
$checktray->firstRow();
// echo $checktray->query;exit;
$tempFileTrayCheck= $checktray->getField("LINK_FILE");
$tempFileTray= str_replace("../upload", "../../cat/upload", $tempFileTrayCheck);



$checkdokumen= new UploadFile();
$statementcheck= " AND A.PEGAWAI_ID= ".$reqPegawaiId;
$checkdokumen->selectByParams(array(), -1, -1, $statementcheck);
$checkdokumen->firstRow();
// echo $checktray->query;exit;
$tempFileRiwayatCheck= $checkdokumen->getField("LINK_FILE1");
$tempFileKompetensiCheck= $checkdokumen->getField("LINK_FILE2");
$tempFileCriticalCheck= $checkdokumen->getField("LINK_FILE3");
$tempFileRiwayat= str_replace("../uploads", "../../uploads", $tempFileRiwayatCheck);
$tempFileKompetensi= str_replace("../uploads", "../../uploads", $tempFileKompetensiCheck);
$tempFileCritical= str_replace("../uploads", "../../uploads", $tempFileCriticalCheck);

// echo $tempFileTraycheck;exit;


$set= new CetakanPdf();
$statement= " AND A.PEGAWAI_ID= ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsPenilaian(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDate($set->getField("TANGGAL_TES"));
$tahunjadwaltes= getYear($set->getField("TANGGAL_TES"));
$tempSatkerTes= $set->getField("SATKER_TES");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempAspekNama= strtoupper($set->getField("ASPEK_NAMA"));
$tempAspekId= strtoupper($set->getField("ASPEK_ID"));
$tempTanggalTes= dateToPageCheck($set->getField("TANGGAL_TES"));
$tempTipeTes= $set->getField("TIPE_FORMULA");
// echo $tempAspekId; exit;

$statement= "  AND A.JADWAL_TES_ID = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$reqPegawaiId; 
$statementgroup= "";
$index_loop= 0; 
$jpm=0;
$arrPenilaianAtributJPM="";
$set= new CetakanPdf();
$set->selectByParamsPenilaianJpmAkhir(array(), -1,-1, $statement, $statementgroup);
//echo $set->query;exit;
$set->firstRow();
$jumlah_penilaian_atribut= $index_loop;

$jpm = $set->getField("JPM");
$ikk = $set->getField("IKK");
$jpmPotensi = $set->getField("PSIKOLOGI_JPM");
$jpmKompetensi = $set->getField("KOMPETEN_JPM");

if ($jpm > 100)
  $jpm = 100;

//perhitungan
//echo 
if($tempTipeTes == '1')
{
  if ($jpm >= 80)
    $HasilKonversi = 'MS = Memenuhi Syarat.';
  elseif ($jpm >= 68 && $jpm < 80)
    $HasilKonversi = 'MMS = Masih Memenuhi Syarat.';
  elseif ($jpm < 68)
    $HasilKonversi = 'KMS = Kurang Memenuhi Syarat.';
  else
    $HasilKonversi = '-'; 
}
elseif($tempTipeTes == '2')
{
  if ($jpm >= 90)
    $HasilKonversi = 'O = Optimal.';
  elseif ($jpm >= 78 && $jpm < 90)
    $HasilKonversi = 'CO = Cukup Optimal.';
  elseif ($jpm < 78)
    $HasilKonversi = 'KO = Kurang Optimal.';
  else
    $HasilKonversi = '-'; 
}
else
  $HasilKonversi = '-'; 


$tempBulanSekarang= date("m");
$tempTahunSekarang= date("Y");

$tempBulanSekarang= date("m");
$tempSystemTanggalNow= date("d-m-Y");

$set= new Asesor();
if($tempAsesorId!=''){
  $set->selectByParams(array(), -1,-1, " AND A.ASESOR_ID = ".$tempAsesorId);
}
else{
  $set->selectByParams(array(), -1,-1);
}
$set->firstRow();
// echo $set->query;exit();
$tempAsesorTipeNama= $set->getField("TIPE_NAMA");
$tempAsesorNoSk= $set->getField("NO_SK");
$tempAsesorNama= $set->getField("NAMA");
$tempAsesorAlamat= $set->getField("ALAMAT");
$tempAsesorEmail= $set->getField("EMAIL");
$tempAsesorTelepon= $set->getField("TELEPON");
unset($set);

if($tempAsesorId!=''){

  $statement= "
  AND A.PEGAWAI_ID = ".$reqPegawaiId."
  AND EXISTS
  (
    SELECT 1
    FROM
    (
      SELECT A.JADWAL_TES_ID 
      FROM jadwal_asesor A
      WHERE JADWAL_TES_ID = ".$reqJadwalTesId." AND A.ASESOR_ID = ".$tempAsesorId." GROUP BY A.JADWAL_TES_ID
    ) X WHERE C.JADWAL_TES_ID = X.JADWAL_TES_ID
  )";
}
else{
  $statement= "
  AND A.PEGAWAI_ID = ".$reqPegawaiId."
  AND EXISTS
  (
    SELECT 1
    FROM
    (
      SELECT A.JADWAL_TES_ID 
      FROM jadwal_asesor A
      WHERE JADWAL_TES_ID = ".$reqJadwalTesId." GROUP BY A.JADWAL_TES_ID
    ) X WHERE C.JADWAL_TES_ID = X.JADWAL_TES_ID
  )"; 
}
$set= new JadwalPegawai();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit();
$reqJadwalPegawaiNama= $set->getField("PEGAWAI_NAMA");
$reqJadwalLinkFoto= $set->getField("link_foto");
$reqJadwalPegawaiNip= $set->getField("PEGAWAI_NIP");
$reqJadwalPegawaiGol= $set->getField("PEGAWAI_GOL");
$reqJadwalPegawaiEselon= $set->getField("PEGAWAI_ESELON");
$reqJadwalPegawaiJabatan= $set->getField("PEGAWAI_JAB_STRUKTURAL");
$reqJadwalPegawaiLastEselon= $set->getField("LAST_ESELON_ID");
// echo $reqJadwalPegawaiNip; exit;

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$reqJadwalPegawaiNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);

//$dateNow= date("d-m-Y");

$index_loop= 0;
$arrAsesor="";
// $statementcount= $statement= " AND A.ASESOR_ID = ".$tempAsesorId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId."
// AND EXISTS (SELECT 1 FROM jadwal_pegawai X WHERE X.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_ASESOR_ID = X.JADWAL_ASESOR_ID)";
$statementcount= $statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId."
AND EXISTS (SELECT 1 FROM jadwal_pegawai X WHERE X.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_ASESOR_ID = X.JADWAL_ASESOR_ID)";

if($tempAsesorId!=''){
  $statementdetil= " 
  AND EXISTS
  (
    SELECT 1
    FROM
    (
      SELECT JADWAL_ACARA_ID
      FROM jadwal_asesor A
      WHERE 1=1 AND A.JADWAL_TES_ID = ".$reqJadwalTesId." AND A.ASESOR_ID = ".$tempAsesorId."
      AND EXISTS
      (
      SELECT 1
      FROM jadwal_pegawai X 
      WHERE X.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_ASESOR_ID = X.JADWAL_ASESOR_ID
      )
    ) X
    WHERE A.JADWAL_ACARA_ID = X.JADWAL_ACARA_ID
  )
  ";
}
else{
   $statementdetil= " 
  AND EXISTS
  (
    SELECT 1
    FROM
    (
      SELECT JADWAL_ACARA_ID
      FROM jadwal_asesor A
      WHERE 1=1 AND A.JADWAL_TES_ID = ".$reqJadwalTesId."
      AND EXISTS
      (
      SELECT 1
      FROM jadwal_pegawai X 
      WHERE X.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_ASESOR_ID = X.JADWAL_ASESOR_ID
      )
    ) X
    WHERE A.JADWAL_ACARA_ID = X.JADWAL_ACARA_ID
  )
  "; 
}

$statementcount.= " AND CASE WHEN PENGGALIAN_KODE_ID IS NOT NULL THEN 1 ELSE 0 END = 1";
$set= new JadwalAsesor();
$tempJumlahAsesorPegangCbi= $set->getCountByParamsPenggalianAsesorPegawai($statementcount, $statementdetil);

$kondisiasesorsaranid= "";
$jumlahNilaiAkhir=0;
$set= new JadwalAsesor();
$set->selectByParamsPenggalianAsesorPegawai($statement, $statementdetil);
// echo $set->query;exit;
while($set->nextRow())
{
  $asesorsaranid= $set->getField("ASESOR_ID");

  if($tempAsesorId == $asesorsaranid)
  {
    $kondisiasesorsaranid= "1";
  }

  $arrAsesor[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
  $arrAsesor[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrAsesor[$index_loop]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
  $arrAsesor[$index_loop]["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
  $arrAsesor[$index_loop]["PENGGALIAN_KODE_ID"]= $set->getField("PENGGALIAN_KODE_ID");
  $arrAsesor[$index_loop]["PENGGALIAN_KODE_STATUS"]= $set->getField("PENGGALIAN_KODE_STATUS");
  $arrAsesor[$index_loop]["NAMA_ASESOR"]= $set->getField("nama_asesor");
  $arrAsesor1[$set->getField("PENGGALIAN_KODE")]['nama']= $set->getField("nama_asesor");
  $arrAsesor1[$set->getField("PENGGALIAN_KODE")]['nip']= $set->getField("nip_asesor");
  $index_loop++;

  if($set->getField("PENGGALIAN_ID") == 0){}
  else
  $jumlahNilaiAkhir++;
}
$jumlah_asesor= $index_loop;


$ttdnama=$arrAsesor1['CBI']['nama'];

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new PenilaianRekomendasi();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit();
$reqNilaiAkhirSaranPengembangan= $set->getField("KETERANGAN");

$index_loop= 0;
$arrPegawaiAsesor="";

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND B.PENGGALIAN_ID = 0
AND EXISTS
(
  SELECT 1 FROM jadwal_asesor X WHERE JADWAL_TES_ID = ".$reqJadwalTesId." AND A.JADWAL_ASESOR_ID = X.JADWAL_ASESOR_ID
)";

$set= new JadwalAsesor();
$set->selectByParamsPenggalianPegawai($statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrPegawaiAsesor[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrPegawaiAsesor[$index_loop]["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
  $index_loop++;
}
$jumlah_pegawai_asesor= $index_loop;
// print_r($arrPegawaiAsesor);exit();


$tempKondisiNilaiAkhir= $arrAsesor[0]["PENGGALIAN_KODE_STATUS"];

// ambil data penilaian terhadap peserta berdasarkan penggalian, kecuali potensi
$index_loop= 0;
$arrPegawaiNilai="";
$set= new JadwalPegawaiDetil();

$statement= " AND C.JADWAL_TES_ID = ".$reqJadwalTesId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND C1.PENGGALIAN_ID > 0 AND F.ASPEK_ID = 2";

// sesuai atribut penggalian kondisional
$statement.= " AND EXISTS (SELECT 1 FROM atribut_penggalian X WHERE D.FORMULA_ATRIBUT_ID = X.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = X.PENGGALIAN_ID)";
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
// echo $set->query;exit;
$cekPenggalian='';
$iPenggalian=0;
while($set->nextRow())
{
  $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ID"]= $set->getField("JADWAL_PEGAWAI_DETIL_ID");

  $arrPegawaiNilai[$index_loop]["ASESOR_ID"]= $set->getField("ASESOR_ID");

  $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_ID"]= $set->getField("JADWAL_PEGAWAI_ID");
  $arrPegawaiNilai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
  $arrPegawaiNilai[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrPegawaiNilai[$index_loop]["PENGGALIAN_ATRIBUT"]= $set->getField("PENGGALIAN_ID")."-".$set->getField("ATRIBUT_ID");
  $arrPegawaiNilai[$index_loop]["FORM_PERMEN_ID"]= $set->getField("FORM_PERMEN_ID");
  $arrPegawaiNilai[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
  $arrPegawaiNilai[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
  $arrPegawaiNilai[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
  $arrPegawaiNilai[$index_loop]["PEGAWAI_INDIKATOR_ID"]= $set->getField("PEGAWAI_INDIKATOR_ID");
  $arrPegawaiNilai[$index_loop]["PEGAWAI_LEVEL_ID"]= $set->getField("PEGAWAI_LEVEL_ID");
  $arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"]= $set->getField("PEGAWAI_KETERANGAN");
  $arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
  $arrPegawaiNilai[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
  $arrPegawaiNilai[$index_loop]["JUMLAH_LEVEL"]= $set->getField("JUMLAH_LEVEL");

  $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ATRIBUT_ID"]= $set->getField("JADWAL_PEGAWAI_DETIL_ATRIBUT_ID");
  $arrPegawaiNilai[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
  $arrPegawaiNilai[$index_loop]["NILAI"]= $set->getField("NILAI");
  $arrPegawaiNilai[$index_loop]["GAP"]= $set->getField("GAP");
  $arrPegawaiNilai[$index_loop]["CATATAN"]= $set->getField("CATATAN");
  $arrPegawaiNilai[$index_loop]["DECIMAL"]= $set->getField("DECIMAL");

  if($set->getField("PENGGALIAN_ID")!=$cekPenggalian){
    $cekPenggalian=$set->getField("PENGGALIAN_ID");
    $arrPenggalian[$iPenggalian]= $set->getField("ASESOR_ID");
    $iPenggalian++;
  }

  $index_loop++;
}
$jumlah_pegawai_nilai= $index_loop;
// print_r($arrPenggalian);
// print_r($arrPegawaiNilai);exit;

$statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId." AND B.PENGGALIAN_ID = 0
AND EXISTS
(
  SELECT 1 FROM jadwal_pegawai X WHERE PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_ASESOR_ID = X.JADWAL_ASESOR_ID
)";
$set= new JadwalAsesor();
$set->selectByParamsAsesorPotensi(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();
$reqAsesorPotensiPegawaiId= $set->getField("ASESOR_ID");

$index_loop= 0;
$arrPenilaian="";
$set= new JadwalPegawaiDetil();
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsPenilaianAsesor($statement);
// echo $set->query;exit;
while($set->nextRow())
{
  // ambil data lain dari aspek 1
  if($set->getField("ASPEK_ID") == "1")
  {
    $reqLainPenilaianPotensiId= $set->getField("PENILAIAN_ID");
    $reqPenilaianPotensiStrength= $set->getField("CATATAN_STRENGTH");
    $reqPenilaianPotensiWeaknes= $set->getField("CATATAN_WEAKNES");
    $reqPenilaianPotensiKesimpulan= $set->getField("KESIMPULAN");
    $reqPenilaianPotensiSaranPengembangan= $set->getField("SARAN_PENGEMBANGAN");
    $reqPenilaianPotensiSaranPenempatan= $set->getField("SARAN_PENEMPATAN");
    $reqPenilaianPotensiProfilKepribadian= $set->getField("PROFIL_KEPRIBADIAN");
    $reqPenilaianPotensiKesesuaianRumpun= $set->getField("KESESUAIAN_RUMPUN");
    $reqPenilaianPotensiProfilKompetensi= $set->getField("RINGKASAN_PROFIL_KOMPETENSI");


  }

  $arrPenilaian[$index_loop]["PENILAIAN_DETIL_ID"]= $set->getField("PENILAIAN_DETIL_ID");
  $arrPenilaian[$index_loop]["CATATAN_STRENGTH"]= $set->getField("CATATAN_STRENGTH");
  $arrPenilaian[$index_loop]["PROFIL_KEPRIBADIAN"]= $set->getField("PROFIL_KEPRIBADIAN");

  $arrPenilaian[$index_loop]["ATRIBUT_GROUP"]= $set->getField("ATRIBUT_GROUP");
  $arrPenilaian[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
  $arrPenilaian[$index_loop]["NAMA"]= $set->getField("NAMA");
  $arrPenilaian[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
  $arrPenilaian[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
  $arrPenilaian[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
  $arrPenilaian[$index_loop]["NILAI"]= $set->getField("NILAI");
  $arrPenilaian[$index_loop]["GAP"]= $set->getField("GAP");
  $arrPenilaian[$index_loop]["ASESOR_POTENSI_ID"]= $reqAsesorPotensiPegawaiId;
  // $arrPenilaian[$index_loop]["NILAI"]= 4;
  // $arrPenilaian[$index_loop]["GAP"]= 1;

  $arrPenilaian[$index_loop]["CATATAN"]= $set->getField("CATATAN");
  $arrPenilaian[$index_loop]["BUKTI"]= $set->getField("BUKTI");

  $index_loop++;
}
$jumlah_penilaian= $index_loop;

// onecheck: awal tambahan rekomendasi
$index_catatan= 0;
$arrPotensiStrength=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kekuatan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPotensiStrength[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPotensiStrength= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiWeaknes=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kelemahan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiWeaknes[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiWeaknes= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiKesimpulan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_rekomendasi' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiKesimpulan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiKesimpulan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiSaranPengembangan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_saran_pengembangan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiSaranPengembangan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiSaranPenempatan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_saran_penempatan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiSaranPenempatan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiSaranPenempatan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiProfilKepribadian=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kepribadian' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiProfilKepribadian[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiProfilKepribadian= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiProfilKompetensi=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kompetensi' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiProfilKompetensi= $index_catatan;

$index_catatan= 0;
$arrNilaiAkhirSaranPengembangan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'area_pengembangan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahNilaiAkhirSaranPengembangan= $index_catatan;

// onecheck: end tambahan rekomendasi

// print_r($arrPenilaian);exit;
// echo $reqPenilaianIdInfo;exit();
$index_loop= 0;
$arrPegawaiPenilaian="";
$set= new JadwalPegawaiDetil();
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsPenilaianPegawaiAtribut($statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrPegawaiPenilaian[$index_loop]["PENGGALIAN_ATRIBUT"]= $set->getField("PENGGALIAN_ID")."-".$set->getField("ATRIBUT_ID");
  $arrPegawaiPenilaian[$index_loop]["NILAI"]= $set->getField("NILAI");
  $index_loop++;
}
$jumlah_pegawai_penilaian= $index_loop;
// print_r($arrPenilaian);exit;

$index_loop= 0;
$arrAsesorPenilaianKompetensi="";
$set= new JadwalAsesor();

$statement= " AND C.JADWAL_TES_ID = ".$reqJadwalTesId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND F.ASPEK_ID = 2
AND EXISTS
(
 SELECT 1
 FROM penggalian X
 WHERE X.KODE = 'CBI' AND X.TAHUN = CAST(TO_CHAR(C.TANGGAL_TES, 'YYYY') AS NUMERIC)
 AND C1.PENGGALIAN_ID = X.PENGGALIAN_ID
)";

$set->selectByParamsAsesorKompetensi(array(),-1,-1,$statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrAsesorPenilaianKompetensi[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrAsesorPenilaianKompetensi[$index_loop]["PENGGALIAN_ASESOR_ID"]= $set->getField("ATRIBUT_ID")."-".$set->getField("ASESOR_ID")."-".$set->getField("PENGGALIAN_ID");

  $arrAsesorPenilaianKompetensi[$index_loop]["ASESOR_ID"]= $set->getField("ASESOR_ID");
  $index_loop++;
}
$jumlah_asesor_penilaian_kompetensi= $index_loop;
// print_r($arrAsesorPenilaianKompetensi);exit;


$setdetil= new JadwalTes();
$setdetil->selectByParams(array("JADWAL_TES_ID"=>$reqJadwalTesId),-1,-1);
$setdetil->firstRow();
$infotahun= getDay(datetimeToPage($setdetil->getField("TANGGAL_TES"), 'date'));
// echo $infotahun;exit;

$idata= 0;
$setdetil= new Penggalian();
$setdetil->selectByParams(array(), -1, -1, " AND A.TAHUN = '".$infotahun."' AND A.KODE != 'PT'
and exists
(
    select 1
    from
    (
        select pegawai_id kode 
        from permohonan_file 
        where permohonan_table_nama = 'jadwaltes".$reqJadwalTesId."-soal'
    ) x where a.kode = x.kode
)
");
// echo $setdetil->query;exit;
while($setdetil->nextRow())
{
    $arrfilejenis[$idata]["id"]= $setdetil->getField("PENGGALIAN_ID");
    $arrfilejenis[$idata]["nama"]= $setdetil->getField("NAMA");
    $arrfilejenis[$idata]["kode"]= $setdetil->getField("KODE");
    $idata++;
}
$jumlahpenggalian= $idata;
// print_r($arrfilejenis);exit;

$reqkuncijenis= $reqJadwalTesId;
$reqfolderjenis= "jadwaltes".$reqkuncijenis;
$reqJenis= $reqfolderjenis."-soal";
$reqJenisPegawai= $reqfolderjenis."-jawab";

?>

<h1 style="text-align: center;">Hasil Rekap Penilaian </h1>
<table style="width:100%">
  <tr>
    <td style="width:20%"><img style="width: 100px;" src="<?=$data['foto_original']?>" /></td>
    <td>
      <table >
              <tr>
                  <th style="text-align: left;">Nama</th>
                  <th style="width:5px">:</th>
                  <td  colspan="4"><? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?> </td>
              </tr>
              <tr>
                  <th style="text-align: left;">NIP</th>
                  <th>:</th>
                  <td colspan="4"><?=$reqJadwalPegawaiNip?></td>
              </tr>
              <tr>
                  <th style="text-align: left;">Pangkat / Gol.Ruang</th>
                  <th>:</th>
                  <td colspan="4"><?=$data['pangkat']?></td>
              </tr>
              <tr>
                  <th style="text-align: left;">Jabatan</th>
                  <th>:</th>
                  <td colspan="4"><?=$reqJadwalPegawaiJabatan?></td>
              </tr>
              <tr>
                  <th style="text-align: left;">Assesment</th>
                  <th>:</th>
                  <td colspan="4"><?=$reqKeterangan?></td>
              </tr>
              <tr>
                  <th style="text-align: left;">Tanggal</th>
                  <th>:</th>
                  <td colspan="4"><?=$TanggalTes?></td>
              </tr>
              <tr>
                  <th style="text-align: left;">Nama Asesor</th>
                  <th>:</th>
                  <td colspan="4"><?=$ttdnama?></td>
              </tr>                                        
          </table>
    </td>
  </tr>
</table>
<?
for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
{
  $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
  $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
  if($reqInfoPenggalianId == "0")
  {
    $arrayKey= in_array_column("1", "ASPEK_ID", $arrPenilaian);
      $reqPenilaianPotensiGroup= "";
      $index_atribut_parent= 0;
      for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
      {
        $index_row= $arrayKey[$index_detil];

        $reqPenilaianPotensiDataAsesorId= $arrPenilaian[$index_row]["ASESOR_POTENSI_ID"];

        $reqPenilaianPotensiAspekId= $arrPenilaian[$index_row]["ASPEK_ID"];
        $reqPenilaianPotensiAtributNama= $arrPenilaian[$index_row]["NAMA"];
        $reqPenilaianPotensiNilaiStandar= $arrPenilaian[$index_row]["NILAI_STANDAR"];
        $reqPenilaianPotensiKepribadian= $arrPenilaian[$index_row]["PROFIL_KEPRIBADIAN"];
        $reqPenilaianPotensiAtributParentCurrentId= $arrPenilaian[$index_row]["ATRIBUT_ID_PARENT"];
        $index_row_next= $index_detil+1;
        $index_row_next= $arrayKey[$index_row_next];
        $reqPenilaianPotensiAtributParentNextId= $arrPenilaian[$index_row_next]["ATRIBUT_ID_PARENT"];

        // muncul saran apabila parent potensi terakhir data
        $checkmunculsaranpotensi= "";
        if($reqPenilaianPotensiAtributParentCurrentId == $reqPenilaianPotensiAtributParentNextId){}
        else
        $checkmunculsaranpotensi= "1";
        
        $reqPenilaianPotensiAtributIdParent= $arrPenilaian[$index_row]["ATRIBUT_ID_PARENT"];
        $reqPenilaianPotensiAtributGroup= $arrPenilaian[$index_row]["ATRIBUT_GROUP"];
        $reqPenilaianPotensiDetilId= $arrPenilaian[$index_row]["PENILAIAN_DETIL_ID"];

        $reqPenilaianPotensiNilai= $arrPenilaian[$index_row]["NILAI"];
        $reqPenilaianPotensiGap= $arrPenilaian[$index_row]["GAP"];

        $reqPenilaianPotensiCatatan= $arrPenilaian[$index_row]["CATATAN"];
        $reqPenilaianPotensiBukti= $arrPenilaian[$index_row]["BUKTI"];
    }                                    
  }
  // set form kalau bukan potensi
  else
  {
      $arrayKey= in_array_column($reqInfoPenggalianId, "PENGGALIAN_ID", $arrPegawaiNilai);

      if($arrayKey == ''){}
      else
      {
        $indexKeterangan= 0;
        $indexTr= 1;
        $reqJadwalPegawaiAtributIdLookUp= "";
        for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
        {
          $index_row= $arrayKey[$index_detil];
          $reqJadwalPegawaiDetilId= $arrPegawaiNilai[$index_row]["JADWAL_PEGAWAI_DETIL_ID"];

          $reqJadwalPegawaiDataAsesorId= $arrPegawaiNilai[$index_row]["ASESOR_ID"];

          $reqJadwalPegawaiDataId= $arrPegawaiNilai[$index_row]["JADWAL_PEGAWAI_ID"];
          $reqJadwalPegawaiJadwalAsesorId= $arrPegawaiNilai[$index_row]["JADWAL_ASESOR_ID"];
          $reqJadwalPegawaiPenggalianId= $arrPegawaiNilai[$index_row]["PENGGALIAN_ID"];
          $reqJadwalPegawaiFormPermenId= $arrPegawaiNilai[$index_row]["FORM_PERMEN_ID"];
          $reqJadwalPegawaiAtributId= $arrPegawaiNilai[$index_row]["ATRIBUT_ID"];
          $reqJadwalPegawaiIndikatorId= $arrPegawaiNilai[$index_row]["INDIKATOR_ID"];
          $reqJadwalPegawaiLevelId= $arrPegawaiNilai[$index_row]["LEVEL_ID"];
          $reqJadwalPegawaiIndikatorDataId= $arrPegawaiNilai[$index_row]["PEGAWAI_INDIKATOR_ID"];
          $reqJadwalPegawaiLevelDataId= $arrPegawaiNilai[$index_row]["PEGAWAI_LEVEL_ID"];
          $reqJadwalPegawaiAtributNama= $arrPegawaiNilai[$index_row]["ATRIBUT_NAMA"];
          $reqJadwalPegawaiNamaIndikator= $arrPegawaiNilai[$index_row]["NAMA_INDIKATOR"];
          $reqJadwalPegawaiJumlahLevel= $arrPegawaiNilai[$index_row]["JUMLAH_LEVEL"];

          $reqDetilAtributDetilAtributId= $arrPegawaiNilai[$index_row]["JADWAL_PEGAWAI_DETIL_ATRIBUT_ID"];
          $reqDetilAtributNilaiStandar= $arrPegawaiNilai[$index_row]["NILAI_STANDAR"];
          $reqDetilAtributNilai= $arrPegawaiNilai[$index_row]["NILAI"];
          $reqDetilAtributNilaiRekap[$reqInfoPenggalianId][]= $arrPegawaiNilai[$index_row]["NILAI"];
          // echo "xxx".$reqDetilAtributNilai;
          $reqDetilAtributGap= $arrPegawaiNilai[$index_row]["GAP"];
          $reqDetilAtributCatatan= $arrPegawaiNilai[$index_row]["CATATAN"];
          $reqDetilAtributDecimal= $arrPegawaiNilai[$index_row]["DECIMAL"];
          // echo "xx".$reqDetilAtributDecimal;
          if($reqDetilAtributNilai!=''){
            $reqDetilAtributNilai2= explode(".",$reqDetilAtributNilai);
            // print_r($reqDetilAtributNilai);

            if ($reqDetilAtributNilai2[1] !=''){
              $reqDetilAtributDecimalJadi= $reqDetilAtributNilai2[1];
              $reqDetilAtributNilai= $reqDetilAtributNilai2[0];
            }
          }

          $cssIndikator= "sebelumselected";
          if($reqJadwalPegawaiDetilId == ""){}
          else
            $cssIndikator= "selected";

          // $cssIndikator= "sebelumselected";
        }
      }
    
   }
  }
  // set form kalau bukan potensi
if($tempKondisiNilaiAkhir == "1")
{
  $tempsimpankompetensi= "";
?>
        <h2 style="text-align: center;">Rekap Penilaian</h2>
        <?
        ?>
          <table style="width:100%;border-collapse: collapse;">
            <tr>
              <td style="background-color: #f8a406 !important;text-align: center;width: 5%;border: 0.5px solid black;">No</td>
              <td style="background-color: #f8a406 !important;text-align: center;width: 30%;border: 0.5px solid black;">Kompetensi</td>
              <?
              for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
              {
                $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
                $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
                $reqInfoPenggalianAsesorId= $arrAsesor[$index_loop]["NAMA_ASESOR"];
                if($reqInfoPenggalianKode!='Psikotes'){
              ?>
                <td style="background-color: #f8a406 !important;text-align: center;border: 0.5px solid black;"><?=$reqInfoPenggalianKode?><br><?=$reqInfoPenggalianAsesorId?></td>
              <?
                }
              }
              ?>
              <td style="background-color: #f8a406 !important;text-align: center;width: 10%;border: 0.5px solid black;">Kesimpulan</td>
            </tr>
           <?
           // $arrayKey= in_array_column("2", "ASPEK_ID", $arrPenilaian);
            $arrayKey= in_array_column($arrAsesor[0]["PENGGALIAN_ID"], "PENGGALIAN_ID", $arrPegawaiNilai);
            $ceknama='';
            $no='1';
             $arrayKeysss= in_array_column("2", "ASPEK_ID", $arrPenilaian);
            for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
            {
              $index_row= $arrayKey[$index_detil];
              $reqJadwalPegawaiAtributNama= $arrPegawaiNilai[$index_row]["ATRIBUT_NAMA"];
              $reqDetilAtributNilai= $arrPegawaiNilai[$index_row]["NILAI"];
              
              if($ceknama!=$reqJadwalPegawaiAtributNama){
                ?>
              <tr>
                <td style="background-color: #f8a406 !important;text-align: center;border: 0.5px solid black;"><?=$no?></td>
                <td style="border: 0.5px solid black"><?=$reqJadwalPegawaiAtributNama?></td>
              <?
                for($index_loop=0; $index_loop < count($arrAsesor); $index_loop++)
                {
                  $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
                  if($arrAsesor[$index_loop]['PENGGALIAN_NAMA']!='Psikotes'){
                ?>
                  <td style="border: 0.5px solid black"><center><?=$reqDetilAtributNilaiRekap[$arrAsesor[$index_loop]['PENGGALIAN_ID']][$index_detil]?></center></td>
                <?
                  }
                }
                if($arrPenilaian[$arrayKeysss[$no]]["PENILAIAN_DETIL_ID"]==''){?>
                  <td style="border: 0.5px solid black"><center><?=$arrPenilaian[$arrayKeysss[$no+1]]["NILAI"];?></center></td>
                <?}
                else{
                ?>
                  <td style="border: 0.5px solid black"><center><?=$arrPenilaian[$arrayKeysss[$no]]["NILAI"];?></center></td>
                <?}?>
              </tr>
              <?  
              $no++;
              $ceknama=$reqJadwalPegawaiAtributNama;
              }
            }
            ?>
          </table>
          <br>
        <h2 style="text-align: center;">Data Tes Psikologi</h2>
            <table style="width:100%;border-collapse: collapse;" class="profil">
            <tr>
              <td style="background-color: #f8a406 !important;text-align: center;width: 5%;border: 0.5px solid black">No</td>
              <td style="background-color: #f8a406 !important;text-align: center;border: 0.5px solid black">Aspek Potensi</td>
              <td style="background-color: #f8a406 !important;text-align: center;border: 0.5px solid black">Rating</td>
              <td style="background-color: #f8a406 !important;text-align: center;border: 0.5px solid black">Keterangan</td>
            </tr>
           <?
            $arrayKey= in_array_column("1", "ASPEK_ID", $arrPenilaian);
            
            if($arrayKey == ''){}
            else
            {
              $reqPenilaianKompetensiGroup= "";
              $index_atribut_parent= 1;
              for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
              {
                $index_row= $arrayKey[$index_detil];
                $reqPenilaianKompetensiAtributNama= $arrPenilaian[$index_row]["NAMA"];
                $reqPenilaianKompetensiAtributId= $arrPenilaian[$index_row]["ATRIBUT_ID"];
                $reqPenilaianKompetensiAtributIdParent= $arrPenilaian[$index_row]["ATRIBUT_ID_PARENT"];
                if($reqPenilaianKompetensiAtributIdParent!=0){
                  $setPenilaianKompetensi= new PenilaianKompetensi();
                  $setPenilaianKompetensi->selectByParamsDasarDataPsikologi(array(), -1,-1, "and b.pegawai_id = ".$reqPegawaiId." and jadwal_tes_id = ".$reqJadwalTesId." and atribut_id = '".$reqPenilaianKompetensiAtributId."'");
                  $setPenilaianKompetensi->firstRow();
                ?>
                <tr>
                  <td style="border: 0.5px solid black"><?=$index_atribut_parent?></td>
                  <td style="border: 0.5px solid black"><?=$reqPenilaianKompetensiAtributNama?></td>
                  <td style="border: 0.5px solid black"><?=$setPenilaianKompetensi->getField("nilai")?></td>
                  <td style="border: 0.5px solid black"><?=$setPenilaianKompetensi->getField("catatan")?></td>
                </tr>
                <?
                $index_atribut_parent++;
                }
                else{?> 
                <tr>
                  <td colspan="4" style="border: 0.5px solid black" ><b><?=$reqPenilaianKompetensiAtributNama?></b></td>                         
                </tr>
                <?
                $index_atribut_parent= 1;
                }
              }
            }
            ?>
          </table>

<?
}
?>