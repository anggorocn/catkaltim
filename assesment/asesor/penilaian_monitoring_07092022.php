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
include_once("../WEB/functions/crfs_protect.php");
$csrf = new crfs_protect('_crfs_login');

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
  $userLogin->retrieveUserInfo();  
}
// ini_set('max_execution_time', '0');
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
// ini_set('max_execution_time', 500000);
// ini_set("memory_limit", 500000000000000000);
// ini_set("max_input_vars", 5000);

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// echo $actual_link; exit;
$reqEror=httpFilterGet("reqEror");
// echo $reqEror; exit;
if($reqEror==1){
  // echo "Sasasa"; exit;
  flush();
  ob_flush();
}

//$max_time = ini_get("max_execution_time");
//echo $max_time;
$tempAsesorId= $userLogin->userAsesorId;
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqSelectPenggalianId= httpFilterGet("reqSelectPenggalianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
// echo $tempAsesorId;exit();

if($tempAsesorId == "")
{
  echo '<script language="javascript">';
  echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
  echo 'top.location.href = "../main/login.php";';
  echo '</script>';   
  exit;
}

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

$statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
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

// $tempBulanSekarang= '02';
// $tempTahunSekarang= "2018";
// $tempSystemTanggalNow= "01-02-2018";

$set= new Asesor();
$set->selectByParams(array(), -1,-1, " AND A.ASESOR_ID = ".$tempAsesorId);
$set->firstRow();
// echo $set->query;exit();
$tempAsesorTipeNama= $set->getField("TIPE_NAMA");
$tempAsesorNoSk= $set->getField("NO_SK");
$tempAsesorNama= $set->getField("NAMA");
$tempAsesorAlamat= $set->getField("ALAMAT");
$tempAsesorEmail= $set->getField("EMAIL");
$tempAsesorTelepon= $set->getField("TELEPON");
unset($set);

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

$statementcount.= " AND CASE WHEN PENGGALIAN_KODE_ID IS NOT NULL THEN 1 ELSE 0 END = 1";
$set= new JadwalAsesor();
$tempJumlahAsesorPegangCbi= $set->getCountByParamsPenggalianAsesorPegawai($statementcount, $statementdetil);
// echo $set->query;exit;

// if($tempJumlahAsesorPegangCbi > 0){}
// else
// $statement.= " AND B.PENGGALIAN_ID > 0 ";
// $statement.= " AND A.ASESOR_ID = ".$tempAsesorId;

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
  $index_loop++;

  if($set->getField("PENGGALIAN_ID") == 0){}
  else
  $jumlahNilaiAkhir++;
}
$jumlah_asesor= $index_loop;
// print_r($arrAsesor);exit();
//$jumlah_asesor= 0;

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new PenilaianRekomendasi();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit();
$reqNilaiAkhirSaranPengembangan= $set->getField("KETERANGAN");

$index_loop= 0;
$arrPegawaiAsesor="";
// $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND B.PENGGALIAN_ID > 0
// AND EXISTS
// (
//   SELECT 1 FROM jadwal_asesor X WHERE JADWAL_TES_ID = ".$reqJadwalTesId." AND A.JADWAL_ASESOR_ID = X.JADWAL_ASESOR_ID
// )";

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

// $statement= " AND C.JADWAL_TES_ID = ".$reqJadwalTesId." AND B.ASESOR_ID = ".$tempAsesorId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND C1.PENGGALIAN_ID > 0 AND F.ASPEK_ID = 2";

$statement= " AND C.JADWAL_TES_ID = ".$reqJadwalTesId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND C1.PENGGALIAN_ID > 0 AND F.ASPEK_ID = 2";
// if($tempJumlahAsesorPegangCbi > 0){}
// else
// $statement.= " AND C1.PENGGALIAN_ID > 0 ";
// $statement.= " AND B.ASESOR_ID = ".$tempAsesorId;

// sesuai atribut penggalian kondisional
$statement.= " AND EXISTS (SELECT 1 FROM atribut_penggalian X WHERE D.FORMULA_ATRIBUT_ID = X.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = X.PENGGALIAN_ID)";
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
// echo $set->query;exit;
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

  $index_loop++;
}
$jumlah_pegawai_nilai= $index_loop;
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

// $statement= " AND C.JADWAL_TES_ID = ".$reqJadwalTesId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND C1.PENGGALIAN_ID > 0 AND F.ASPEK_ID = 2";
// $statement.= " AND EXISTS (SELECT 1 FROM atribut_penggalian X WHERE D.FORMULA_ATRIBUT_ID = X.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = X.PENGGALIAN_ID)";
// $statement= " AND C.JADWAL_TES_ID = ".$reqJadwalTesId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND C1.PENGGALIAN_ID = 0 AND F.ASPEK_ID = 2";
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

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Aplikasi Pelaporan Hasil Assesment</title>

    <!-- BOOTSTRAP -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="../WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../WEB/css/gaya-main.css" type="text/css">
    <link rel="stylesheet" href="../WEB/css/gaya-assesor.css" type="text/css">
    <link rel="stylesheet" href="../WEB/lib/Font-Awesome-4.5.0/css/font-awesome.css">
    
    <!--<script type='text/javascript' src="../WEB/lib/bootstrap/jquery.js"></script> -->

    <style>
    .col-md-12{
      *padding-left:0px;
      *padding-right:0px;
  }
</style>

<!-- <script src="../WEB/lib/emodal/eModal.js"></script> -->
<script>

  function reloaderor(){
    location.href = "<?=$actual_link?>&reqEror=1";
    // console.log('xxx');
  }

  function openPopup() {
    //document.getElementById("demo").innerHTML = "Hello World";
    //alert('hhh');
    // Display a ajax modal, with a title
    eModal.ajax('konten.html', 'Judul Popup')
    //  .then(ajaxOnLoadCallback);
  }

  function cetak(url)
  {
    newWindow = window.open('../ikk/'+url+'.php?reqId=<?=$reqPegawaiId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$tahunjadwaltes?>');
    newWindow.focus();          
  }

  function cetakRekap(url)
  {
    newWindow = window.OpenDHTMLDetil('../ikk/infografik.php?reqInfoLink=Ringkasan Laporan Individu&reqLink=cetak_ringkasan_pdf&reqId=<?=$reqPegawaiId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$tahunjadwaltes?>', '<?=$reqJadwalPegawaiNip?> - <?=$reqJadwalPegawaiNama?>', '880', '495');

     // newWindow = window.OpenDHTMLDetil('../ikk/infografik.php?reqInfoLink=Ringkasan Laporan Individu&reqLink=cetak_ringkasan_pdf&reqId=<?=$reqPegawaiId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$tahunjadwaltes?>,<?=$reqJadwalPegawaiNip?>-<?=$reqJadwalPegawaiNama?>', '880', '495');
    newWindow.focus();          
  }

   function CetakDataPeserta(pegawaiId,mode)
  {

    var IdPegawai = pegawaiId;
    var Mode = mode;
    // newWindow = window.open('../pengaturan/cetak_data_pribadi_pdf.php?reqPegawaiId='+IdPegawai+'&reqId=<?=$reqId?>&reqMode='+Mode, '<?=$reqJadwalPegawaiNip?> - <?=$reqJadwalPegawaiNama?>', '880', '495');
    newWindow = window.open('../pengaturan/cetak_data_pribadi_pdf.php?reqPegawaiId='+IdPegawai+'&reqId=<?=$reqId?>&reqMode='+Mode, '<?=$reqJadwalPegawaiNip?> - <?=$reqJadwalPegawaiNama?>');
    newWindow.focus();          
     // newWindow = window.OpenDHTMLDetil('../ikk/infografik.php?reqInfoLink=Ringkasan Laporan Individu&reqLink=cetak_ringkasan_pdf&reqId=<?=$reqPegawaiId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$tahunjadwaltes?>,<?=$reqJadwalPegawaiNip?>-<?=$reqJadwalPegawaiNama?>', '880', '495');
    // newWindow.focus();          
  }
 
</script>

<!-- FLUSH FOOTER -->
<style>
html, body {
  height: 100%;
}

#wrap-utama {
  min-height: 100%;
  *min-height: calc(100% - 10px);
}

#main {
  overflow:auto;
  padding-bottom:50px; /* this needs to be bigger than footer height*/
}

.footer {
  position: relative;
  margin-top: -50px; /* negative value of footer height */
  height: 50px;
  clear:both;
  padding-top:20px;
  *background:cyan;

  text-align:center;
  color:#FFF;
}
@media screen and (max-width:767px) {
    .footer {
        font-size:12px;
    }
}

.nicEdit-main{
     background-color: white;
     color: black;
}

</style>

<style>
  .rbtn ul{
    list-style-type:none;
  }
  .rbtn ul li{
    *cursor:pointer;
    *display:inline-block; 
    display:inherit;
    *width:100px; 
    border:1px solid #06345f; 
    padding:5px;
    margin:-5px;
    *margin-right:5px; 
    
    -moz-border-radius: 3px; 
    -webkit-border-radius: 3px; 
    -khtml-border-radius: 3px; 
    border-radius: 3px; 
    
    *text-align:center;
    text-align:left;
    
  }
  .over{
    background: #063a69;
  }
  
  .sebelumselected{
    background: #063a69; 
    color:#fff;
    *margin:2px;
  }
  
  .sebelumselected:before{
    font-family:"FontAwesome";
    content:"\f096";
    *margin-right:10px;
    color:#f8a406;
    font-size:18px;
    *vertical-align:middle;
  }
  
  .selected{
    background: #063a69; 
    color:#fff;
  }
  .selected:before{
    font-family:"FontAwesome";
    content:"\f046";
    *margin-right:10px;
    color:#f8a406;
    font-size:18px;
    *vertical-align:middle;
  }
  </style>

<!-- SCROLLING TAB -->
<link href="../WEB/lib/Scrolling/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="../WEB/lib/Scrolling/jquery-ui.css" rel="stylesheet" type="text/css">
<!-- <link rel="stylesheet" href="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/css/style.css" type="text/css"> -->
  
<style type="text/css">
body {
  font-size: 12px;
  font-family: "Roboto", HelveticaNeue, Helvetica, sans-serif;
  margin: 0;
  background-color:#fafafa;
}
h1 { margin:150px auto 50px auto; text-align:center;}
p { font-size: 13px }

h2 { font-size: 16px; }

.ui-scroll-tabs-header:after {
  content: "";
  display: table;
  clear: both;
}

/* Scroll tab default css*/

.ui-scroll-tabs-view {
  z-index: 1;
  overflow: hidden;
}

.ui-scroll-tabs-view .ui-widget-header {
  border: none;
  background: transparent;
}

.ui-scroll-tabs-header {
  position: relative;
  overflow: hidden;
}

.ui-scroll-tabs-header .stNavMain {
  position: absolute;
  top: 0;
  z-index: 2;
  height: 100%;
  opacity: 0;
  transition: left .5s, right .5s, opacity .8s;
  transition-timing-function: swing;
}

.ui-scroll-tabs-header .stNavMain button { height: 100%; }

.ui-scroll-tabs-header .stNavMainLeft { left: -250px; }

.ui-scroll-tabs-header .stNavMainLeft.stNavVisible {
  left: 0;
  opacity: 1;
}

.ui-scroll-tabs-header .stNavMainRight { right: -250px; }

.ui-scroll-tabs-header .stNavMainRight.stNavVisible {
  right: 0;
  opacity: 1;
}

.ui-scroll-tabs-header ul.ui-tabs-nav {
  position: relative;
  white-space: nowrap;
}

.ui-scroll-tabs-header ul.ui-tabs-nav li {
  display: inline-block;
  float: none;
}

.ui-scroll-tabs-header ul.ui-tabs-nav li.stHasCloseBtn a { padding-right: 0.5em; }

.ui-scroll-tabs-header ul.ui-tabs-nav li span.stCloseBtn {
  float: left;
  padding: 4px 2px;
  border: none;
  cursor: pointer;
}

/*End of scrolltabs css*/
</style>

<style>
.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active{
  *border: 1px solid red;
}
.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab > a.ui-tabs-anchor{
  padding: 20px;
  *background: #dddddd;
  
  background-color: #dddddd;
  background: url(images/linear_bg_2.png);
  background-repeat: repeat-x;
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#dddddd), to(#c0c0c0));
  background: -webkit-linear-gradient(top, #dddddd, #c0c0c0);
  background: -moz-linear-gradient(top, #dddddd, #c0c0c0);
  background: -ms-linear-gradient(top, #dddddd, #c0c0c0);
  background: -o-linear-gradient(top, #dddddd, #c0c0c0);

}
.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active > a.ui-tabs-anchor{
  background: #f8a406;
  color: #FFFFFF;
}
</style>

</head>

<body>

    <div id="wrap-utama" style="height:100%; ">
        <div id="main" class="container-fluid clear-top" style="height:100%;">

            <div class="row">
                <div class="col-md-12">
                    <div class="area-header">
                        <span class="judul-app"><a href="index.php"><img src="../WEB/images/logo-judul.png"> Aplikasi Pelaporan Hasil Assessment</a></span>

                        <div class="area-akun">
                            Selamat datang, <strong><?=$tempAsesorNama?></strong> - 
                            <a href="../main/login.php?reqMode=submitLogout">Logout</a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row" style="height:calc(100% - 20px);">
                <div class="col-md-12" style="height:100%;">

                    <div class="container area-menu-app">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="breadcrumb"><a href="index.php"><i class="fa fa-home"></i> Home</a></div>
                                <div class="row profil-area" style="min-height:205px">
                                    <div class="col-md-2">
                                        <div class="profil-foto">
                                            <img id="reqImagePeserta" />
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                       <div class="judul-halaman">Info Asessee</div>
                                       <table class="profil">
                                        <tr>
                                            <th style="width:165px">Nama</th>
                                            <th style="width:5px">:</th>
                                            <td  colspan="4"><? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?> </td>
                                        </tr>
                                        <tr>
                                            <th>NIP</th>
                                            <th>:</th>
                                            <td colspan="4"><?=$reqJadwalPegawaiNip?></td>
                                        </tr>
                                        <tr>
                                            <th>Pangkat / Gol.Ruang</th>
                                            <th>:</th>
                                            <td colspan="4"><?=$data['pangkat']?></td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan</th>
                                            <th>:</th>
                                            <td colspan="4"><?=$reqJadwalPegawaiJabatan?></td>
                                        </tr>
                                        <tr>
                                            <th>Assesment</th>
                                            <th>:</th>
                                            <td colspan="4"><?=$reqKeterangan?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>:</th>
                                            <td colspan="4"><?=$TanggalTes?></td>
                                        </tr>                                        
                                        <tr>
                                            <th>JPM Potensi</th>
                                            <th>:</th>
                                            <td><?=$jpmPotensi?></td> 
                                            <th style="width:165px">JPM Kompetensi</th>
                                            <th style="width:5px">:</th>
                                            <td><?=$jpmKompetensi?></td>
                                        </tr>
                                        <tr>
                                            <th>JPM Final</th>
                                            <th>:</th>
                                            <td><?=$jpm?></td>

                                            <th style="width:165px">IKK</th>
                                            <th style="width:5px">:</th>
                                            <td><?=$ikk?></td> 
                                        </tr>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>:</th>
                                            <td colspan="4"><?=$HasilKonversi?></td>
                                        </tr>
                                        <tr>
                                            <th>Kode Kuadran</th>
                                            <th>:</th>
                                            <td><?=$kodeKuadran?></td> 
                                            <th style="width:165px">Nama Kuadran</th>
                                            <th style="width:5px">:</th>
                                            <td><?=$namaKuadran?></td>
                                        </tr>
                                        <tr>
                                            <th>Rekomendasi</th>
                                            <th>:</th>
                                            <td colspan="4"><?=$rekomKuadran?></td>
                                        </tr>
                                        <tr>
                                            <th>Cetak Psikogram Asesor</th>
                                            <th>:</th>
                                            <td colspan="1"><a href="javascript: void(0)" onclick="cetak('cetak_psikogram_assesment_new_pdf')" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>

                                            <th>Cetak Ringkasan</th>
                                            <th>:</th>
                                            <td colspan="1"><a href="javascript: void(0)" onclick="cetakRekap('cetak_ringkasan_pdf')" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>
                                            
                                        </tr>
                                        <tr>
                                            <th>Data Pribadi Peserta</th>
                                            <th>:</th>
                                            <td colspan="1"><a href="javascript: void(0)" onclick="CetakDataPeserta(<?=$reqPegawaiId?>)" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>

                                            <th>Critical Incident</th>
                                            <th>:</th>
                                            <td colspan="1"><a href="javascript: void(0)" onclick="CetakDataPeserta(<?=$reqPegawaiId?>,'CI')" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>
                                            
                                        </tr>
                                        <tr>
                                            <th>Q Inta</th>
                                            <th>:</th>
                                            <td colspan="1"><a href="javascript: void(0)" onclick="CetakDataPeserta(<?=$reqPegawaiId?>,'QI')" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>
                                            <? if ($reqJadwalPegawaiLastEselon==99){?>
                                            <th>Q Kompetensi Pelaksana</th>
                                            <th>:</th>
                                            <td colspan="1"><a href="javascript: void(0)" onclick="CetakDataPeserta(<?=$reqPegawaiId?>,'QK_Pelaksana')" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>
                                            <?}
                                            else{?>
                                            <th>Q Kompetensi Eselon</th>
                                            <th>:</th>
                                            <td colspan="1"><a href="javascript: void(0)" onclick="CetakDataPeserta(<?=$reqPegawaiId?>,'QK_Eselon')" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>
                                            <?}?>
                                            
                                        </tr>
                                        <th style="width:165px">In Tray</th>
                                            <th style="width:5px">:</th>

                                            <? if (!empty($tempFileTray))
                                            {
                                            ?>
                                              <td colspan="4"><a href="<?=$tempFileTray?>" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                            <td colspan="4"><a href="<?=$tempFileTray?>" title="Cetak"></td>
                                            <?
                                             }
                                             ?> 
                                        </tr> 
                                        <tr>
                                              <th>Portofolio</th>
                                            <th>:</th>

                                            <? if (!empty($tempFileRiwayat))
                                            {
                                            ?>
                                              <td colspan="1"><a href="<?=$tempFileRiwayat?>" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                            <td colspan="1"><a href="<?=$tempFileRiwayat?>" title="Cetak"></td>
                                            <?
                                             }
                                             ?>

                                            <th >Formulir Pendukung</th>
                                            <th >:</th>

                                            <? if (!empty($tempFileKompetensi))
                                            {
                                            ?>
                                              <td colspan="1"><a href="<?=$tempFileKompetensi?>" title="Cetak"><img src="../WEB/images/down_icon.png"/></a></td>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                            <td colspan="1"><a href="<?=$tempFileKompetensi?>" title="Cetak"></td>
                                            <?
                                             }
                                             ?>
                                        </tr>
                                        <tr>
                                           
                                            <th>Detil Pegawai</th>
                                            <th>:</th>
                                            <td colspan="4"><a style ="color:#FFFFFF !important" href="../silat/pegawai_edit.php?reqId=<?=$reqPegawaiId?>" title="Cetak" target="_blank">Detil Pegawai</a></td>
                                        </tr>
                                         <tr>
                                              <th>Hasil File Test</th>
                                            <th>:</th>                                             
                                            <td  colspan="4">
                                                <?
                                                for($x=0; $x < count($arrfilejenis); $x++)
                                                {
                                                    $infoid= $arrfilejenis[$x]["id"];
                                                    $infolabel= $arrfilejenis[$x]["nama"];
                                                    $infojenislabel= $arrfilejenis[$x]["kode"];

                                                    $setdetil= new PermohonanFile();
                                                    $setdetil->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqkuncijenis, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenisPegawai, "A.PEGAWAI_ID"=>$infojenislabel."-".$reqPegawaiId));
                                                    $setdetil->firstRow();
                                                    // echo $setdetil->query;exit;
                                                    $infofilerowid= $setdetil->getField("PERMOHONAN_FILE_ID");
                                                    $infolinkfile= $infoketerangan= "";
                                                    if(!empty($infofilerowid))
                                                    {
                                                        $infolinkfile= $setdetil->getField("LINK_FILE");
                                                        // echo $infolinkfile;exit;
                                                        if(file_exists($infolinkfile))
                                                        {
                                                ?>
                                                          <a style ="color:#FFFFFF !important" href="<?=$infolinkfile?>" title="<?=$infolabel?>" target="_blank"><?=$infolabel?></a><br> 
                                                <?
                                                        }
                                                        else
                                                        {
                                                ?>
                                                          <a style="cursor: none; text-decoration: none; color:#FFFFFF !important" href="javascript:void(0)" title="<?=$infolabel?>"><?=$infolabel?> : belum upload</a><br>
                                                <?
                                                        }
                                                    }
                                                    else
                                                    {
                                                ?>
                                                          <a style="cursor: none; text-decoration: none; color:#FFFFFF !important" href="javascript:void(0)" title="<?=$infolabel?>"><?=$infolabel?> : belum upload</a><br> 
                                                <?
                                                    }
                                                }
                                                ?>
                                            </td> 
                                        </tr>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>
                    <br>
                    <a style="padding:10px; background-color: royalblue; color: white; border-radius: 10px;" onclick="reloaderor()">Klik Jika Eror</a>
                    <br>
                    <br>
                    <!------>
                    <div id="example_0">
                      <ul role="tablist">
                        <?
                        for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
                        {
                          // $arrAsesor[$index_loop]["JADWAL_ASESOR_ID"];
                          $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
                          // $arrAsesor[$index_loop]["PENGGALIAN_NAMA"];
                          $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];

                          $tabSelectCss= "";
                          if($reqInfoPenggalianId == $reqSelectPenggalianId)
                          $tabSelectCss= "ui-tabs-active ui-state-active";
                        ?>
                        <li role="tab" class="<?=$tabSelectCss?>"><a href="#tabs-<?=$reqInfoPenggalianId?>" role="presentation"><?=$reqInfoPenggalianKode?></a></li>
                        <?
                        }
                        ?>
                        <?
                        $tempKondisiNilaiAkhir= 1;
                        if($tempKondisiNilaiAkhir == "1")
                        {
                          $tabSelectCss= "";
                          if("tabs-nilaiakhir" == $reqSelectPenggalianId)
                          $tabSelectCss= "ui-tabs-active ui-state-active";
                        ?>
                        <li role="tab" class="<?=$tabSelectCss?>"><a href="#tabs-nilaiakhir" role="presentation">Nilai Akhir</a></li>
                        <?
                        }
                        ?>

                        <?
                        $tabSelectCss= "";
                        if("tabs-lain" == $reqSelectPenggalianId)
                        $tabSelectCss= "ui-tabs-active ui-state-active";
                        ?>
                        <li role="tab" class="<?=$tabSelectCss?>"><a href="#tabs-lain" role="presentation">Kesimpulan </a></li>

                      </ul>
                      <?
                      for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
                      {
                        // $arrAsesor[$index_loop]["JADWAL_ASESOR_ID"];
                        $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
                        // $arrAsesor[$index_loop]["PENGGALIAN_NAMA"];
                        $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
                      ?>
                      <div class="ne-except" id="tabs-<?=$reqInfoPenggalianId?>" role="tabpanel" style="background: #063a69 !important;">


                        <?
                        // set form kalau potensi
                        if($reqInfoPenggalianId == "0")
                        {
                        ?>
                        <div class="row">
                          <div class="col-md-12">

                            <div class="area-table-assesor">
                              <br>
                              <div class="judul-halaman">Penilaian Psikotes</div>
                              <form id="ff-<?=$reqInfoPenggalianId?>" method="post" novalidate>

                                <table style="margin-bottom:60px;" class="profil"> 
                                  <tbody>
                                    <?
                                    $arrayKey= in_array_column("1", "ASPEK_ID", $arrPenilaian);
                                    // print_r($arrayKey);exit;

                                    if($arrayKey == ''){}
                                    else
                                    {
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
                                        // echo "xxxx".$reqPenilaianPotensiNilai;
                                        $reqPenilaianPotensiNilaiPecah= explode(".",$reqPenilaianPotensiNilai);
                                        $reqPenilaianPotensiNilai= $reqPenilaianPotensiNilaiPecah[0];
                                        $reqPenilaianPotensiNilaiDecimal= $reqPenilaianPotensiNilaiPecah[1];
                                        $reqPenilaianPotensiGap= $arrPenilaian[$index_row]["GAP"];
                                        if($reqPenilaianPotensiGap == "" || $reqPenilaianPotensiGap == "0")
                                          $reqPenilaianPotensiGap= 0;
                                        else
                                          $reqPenilaianPotensiGap= $reqPenilaianPotensiNilai-$reqPenilaianPotensiNilaiStandar;

                                        $reqPenilaianPotensiCatatan= $arrPenilaian[$index_row]["CATATAN"];
                                        $reqPenilaianPotensiBukti= $arrPenilaian[$index_row]["BUKTI"];

                                        // $reqPenilaianPotensiCatatan= str_replace("<br>", "\n", $reqPenilaianPotensiCatatan);

                                        //kondisi parent
                                        if($reqPenilaianPotensiGroup == $reqPenilaianPotensiAtributGroup)
                                        {
                                          $index_atribut++;
                                        }
                                        else
                                        {
                                          $index_atribut_parent++;
                                          $index_atribut= 0;
                                        }

                                        $reqPenilaianPotensiGroup= $reqPenilaianPotensiAtributGroup;

                                        if($index_atribut_parent % 2 == 0)
                                          $css= "terang";
                                        else
                                          $css= "gelap";
                                    ?>
                                        <?
                                        if($reqPenilaianPotensiAtributIdParent == "0")
                                        {
                                        ?>
                                          <tr class="">
                                            <td style="text-align:center; width: 1%" rowspan="2">No</td>
                                            <td style="text-align:center;" rowspan="2">ATRIBUT & INDIKATOR</td>
                                            <td style="text-align:center; width: 10%" rowspan="2">Standar</td>
                                            <td style="text-align:center" colspan="7">Hasil Individu</td>
                                            <!-- <td style="text-align:center; width: 10%" rowspan="2">Gap</td> -->
                                            <!-- <td style="text-align:center" rowspan="2">Deskripsi</td>
                                            <td style="text-align:center" rowspan="2">Saran Pengembang</td> -->
                                          </tr>
                                          <tr>
                                            <td style="text-align:center; width: 10%">0</td>
                                            <td style="text-align:center; width: 10%">1</td>
                                            <td style="text-align:center; width: 10%">2</td>
                                            <td style="text-align:center; width: 10%">3</td>
                                            <td style="text-align:center; width: 10%">4</td>
                                            <td style="text-align:center; width: 10%">5</td>
                                            <td style="text-align:center; width: 10%">DECIMAL</td>
                                          </tr>
                                          <tr class="<?=$css?>">
                                            <th style="text-align:center"><b><?=romanicNumber($index_atribut_parent)?></b></th>
                                            <th colspan="9"><b><?=$reqPenilaianPotensiAtributNama?></b></th>
                                          </tr>
                                        <?
                                        }
                                        else
                                        {
                                          $arrChecked= "";
                                          if($reqPenilaianPotensiNilai == "" ){}
                                          else
                                          $arrChecked= radioPenilaian($reqPenilaianPotensiNilai);
                                        ?>
                                          <tr class="<?=$css?>">
                                            <?
                                            $kondisilihatatribut="1";
                                            $disabledatribut= "disabled";
                                            // button muncul apabila asesor yg berwenang
                                            if($reqPenilaianPotensiDataAsesorId == $tempAsesorId)
                                            {
                                              $disabledatribut= "";
                                              $kondisilihatatribut="";
                                            }
                                            ?>
                                            <td rowspan="2" style="text-align:center; vertical-align: top;">
                                              <?=$index_atribut?>
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianPotensiDetilId[]" id="reqPenilaianPotensiDetilId<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianPotensiDetilId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianPotensiNilai[]" id="reqPenilaianPotensiNilai<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianPotensiNilai?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianPotensiGap[]" id="reqPenilaianPotensiGap<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianPotensiGap?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianPotensiNilaiStandar[]" id="reqPenilaianPotensiNilaiStandar<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianPotensiNilaiStandar?>" />
                                            </td>
                                            <td><?=$reqPenilaianPotensiAtributNama?></td>
                                            <td align="center"><?=NolToNone($reqPenilaianPotensiNilaiStandar)?>&nbsp;</td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[0] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[0])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[0]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>-0" value="0" data-options="validType:'requireRadio[\'#ff-<?=$reqInfoPenggalianId?> input[name=reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>]\', \'Pilih nilai\']'"/>
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[1] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[1])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[1]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>-1" value="1" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[2] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[2])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[2]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>-2" value="2" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[3] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[3])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[3]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>-3" value="3" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[4] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[4])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[4]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>-4" value="4" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[5] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[5])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[5]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>-5" value="5" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                             <td>    
                                              <?
                                                if($disabledatribut != "")
                                                {?>
                                                  <?=$reqPenilaianPotensiNilaiDecimal?>
                                                <?
                                                }else{?>
                                              <input value="<?=$reqPenilaianPotensiNilaiDecimal?>" type="number" name="reqPenilaianPotensiNilaiDecimal[]" style="color:black;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');">
                                              <?}?>
                                            </td>
                                            <!-- <td align="center"><label id="reqPenilaianPotensiGapInfo<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>"><?=$reqPenilaianPotensiGap?></label>&nbsp;</td> -->
                                          </tr>

                                          <!-- onesebelumupdate -->
                                          <!-- <?
                                          if($checkmunculsaranpotensi == "1")
                                          {
                                          ?>
                                          <tr>
                                            <td colspan="9">
                                              <?
                                              $munculsarancss="";
                                              // if($reqPenilaianPotensiGap == 0)
                                              // $munculsarancss="display:none";

                                              if($disabledatribut == "")
                                              {
                                              ?>
                                              <fieldset>
                                                <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Deskripsi</legend>
                                                <textarea name="reqPenilaianPotensiBukti[]" id="reqPenilaianPotensiBukti<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" style="width:95%" rows="1" ><?=$reqPenilaianPotensiBukti?></textarea>
                                              </fieldset>
                                              <span style="<?=$munculsarancss?>" id="reqPenilaianPotensiSaran<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>">
                                                <fieldset>
                                                  <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Saran Pengembangan</legend>
                                                  <textarea name="reqPenilaianPotensiCatatan[]" id="reqPenilaianPotensiCatatan<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" style="width:95%" rows="1" ><?=$reqPenilaianPotensiCatatan?></textarea>
                                                </fieldset>
                                              </span>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <fieldset style="border: 1px solid; padding: 10px !important">
                                                <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Deskripsi</legend>
                                                <input type="hidden" name="reqPenilaianPotensiBukti[]" value="<?=$reqPenilaianPotensiBukti?>" />
                                                <?=$reqPenilaianPotensiBukti?>
                                              </fieldset>
                                              <span style="<?=$munculsarancss?>" id="reqPenilaianPotensiSaran<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>">
                                                <fieldset style="border: 1px solid; padding: 10px !important">
                                                  <legend style="font-size: 14px !important; border: medium none !important; margin-top: 20px; margin-bottom: 10px; color: white !important;">Saran Pengembangan</legend>
                                                  <input type="hidden" name="reqPenilaianPotensiCatatan[]" value="<?=$reqPenilaianPotensiCatatan?>" />
                                                  <?=$reqPenilaianPotensiCatatan?>
                                                </fieldset>
                                              </span>
                                              <?
                                              }
                                              ?>
                                            </td>
                                          </tr>
                                          <?
                                          }
                                          else
                                          {
                                          ?>
                                          <input type="hidden" name="reqPenilaianPotensiBukti[]" id="reqPenilaianPotensiBukti<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" />
                                          <input type="hidden" name="reqPenilaianPotensiCatatan[]" id="reqPenilaianPotensiCatatan<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" />
                                          <?
                                          }
                                          ?> -->
                                          <!-- onesebelumupdate -->

                                          <tr>
                                            <td colspan="9" style="color:black !important ;background-color: white !important;">
                                              <?
                                              if($disabledatribut == "")
                                              {
                                              ?>
                                              <center><a href="javascript: void(0)" style="background-color: blue;color: white; width:200px" onclick="resetpenilaianpsikotes(<?=$reqPenilaianPotensiAspekId?>,<?=$index_detil?>)" title="Cetak">Reset Penilaian</a></center>
                                              <textarea name="reqPenilaianPotensiCatatan[]" class="easyui-validatebox" data-options="validType:'justText'" style="color:#000 !important "><?=$reqPenilaianPotensiCatatan?></textarea>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="hidden" name="reqPenilaianPotensiCatatan[]" value="<?=$reqPenilaianPotensiCatatan?>" />
                                              <?=$reqPenilaianPotensiCatatan?>
                                              <?
                                              }
                                              ?>
                                            </td>
                                          </tr>

                                        <?
                                        }
                                        ?>
                                      <?
                                      }
                                      ?>
                                    <?
                                    }
                                    ?>
                                    <tr class="terang">
                                      <td colspan="10">Profil Kepribadian</td>
                                    </tr>
                                    <tr  style="background-color:white;">
                                      <td></td>
                                      <?
                                        $setkepribadian= new PenilaianRekomendasi();
                                        $setkepribadian->selectByParams(array('PEGAWAI_ID'=>$reqPegawaiId, 'JADWAL_TES_ID'=>$reqJadwalTesId, 'tipe'=>'profil_kepribadian'));
                                        // echo $setkepribadian->query; exit;
                                          $setkepribadian->firstRow();
                                        $reqPenilaianKeteranganKepribadian= $setkepribadian->getField("KETERANGAN");
                                      ?>
                                      <td colspan="9">  <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianKeteranganKepribadian"><?=$reqPenilaianKeteranganKepribadian?></textarea>
                                          <input type="hidden" style="color:#000 !important" name="reqPenilaianKperibadianPegawaiId" value="<?=$reqPegawaiId?>" />
                                          <input type="hidden" style="color:#000 !important" name="reqPenilaianKperibadianjadwalId" value="<?=$reqJadwalTesId?>" />
                                        </td>
                                    </tr>
                                    <?
                                    // button muncul apabila asesor yg berwenang
                                    if($reqPenilaianPotensiDataAsesorId == $tempAsesorId)
                                    {
                                    ?>
                                    <tr>
                                      <td colspan="11" align="center">
                                       <input type="hidden" name="reqMode" value="insert">
                                       <input name="submit1" type="submit" value="Simpan" />
                                     </td>
                                    </tr>
                                    <?
                                    }
                                    ?>
                                 
                                  </tbody>
                                </table>

                              </form>
                            </div>

                          </div>
                        </div>
                        <?
                        }
                        // set form kalau bukan potensi
                        else
                        {
                        ?>
                        <div class="row">
                          <div class="col-md-12">

                            <div class="area-table-assesor">
                              <br>
                              <div class="judul-halaman">Penilaian dan Catatan :</div>
                              <form id="ff-<?=$reqInfoPenggalianId?>" method="post" novalidate>
                                <table style="margin-bottom:60px;" class="profil">
                                  <thead>
                                    <tr>
                                      <th width="100%" colspan="2">Hasil Individu</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?
                                    $arrayKey= in_array_column($reqInfoPenggalianId, "PENGGALIAN_ID", $arrPegawaiNilai);
                                    // print_r($arrayKey);exit;

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
                                    ?>
                                        <?
                                        if($reqJadwalPegawaiAtributIdLookUp == $reqJadwalPegawaiAtributId)
                                        {
                                          $indexTr++;
                                        ?>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                      <tr>
                                        <td style="vertical-align:top; width:51%">
                                          <div style="margin-bottom: 10px;"><?=$reqJadwalPegawaiAtributNama?></div>
                                          <div class="rbtn">
                                            <ul>
                                              <li style="width:100%; text-align:left" id="rbtn-<?=$index_detil?>-<?=$reqJadwalPegawaiIndikatorId?>-<?=$reqJadwalPegawaiLevelId?>-<?=$reqInfoPenggalianId?>-<?=$reqJadwalPegawaiDataAsesorId?>-<?=$tempAsesorId?>" class=" <?=$cssIndikator?>">
                                                <input type="hidden" id="reqJadwalPegawaiDetilId<?=$reqJadwalPegawaiIndikatorId?>" style="color:#000 !important" name="reqJadwalPegawaiDetilId[]" value="<?=$reqJadwalPegawaiDetilId?>" />
                                                <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiTesId[]" value="<?=$reqJadwalTesId?>" />
                                                <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiPenggalianId[]" value="<?=$reqInfoPenggalianId?>" />
                                                <input type="hidden" id="reqJadwalPegawaiLevelDataId<?=$reqJadwalPegawaiIndikatorId?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqJadwalPegawaiLevelDataId[]" value="<?=$reqJadwalPegawaiLevelDataId?>" />
                                                <input type="hidden" id="reqJadwalPegawaiIndikatorDataId<?=$reqJadwalPegawaiIndikatorId?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqJadwalPegawaiIndikatorDataId[]" value="<?=$reqJadwalPegawaiIndikatorDataId?>" />
                                                <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiDataId[]" value="<?=$reqJadwalPegawaiDataId?>" />
                                                <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiJadwalAsesorId[]" value="<?=$reqJadwalPegawaiJadwalAsesorId?>" />
                                                <input type="hidden" id="reqJadwalPegawaiAtributId<?=$reqJadwalPegawaiIndikatorId?>" style="color:#000 !important" name="reqJadwalPegawaiAtributId[]" value="<?=$reqJadwalPegawaiAtributId?>" />
                                                <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiPegawaiId[]" value="<?=$reqPegawaiId?>" />
                                                <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiAsesorId[]" value="<?=$tempAsesorId?>" />
                                                <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiFormPermenId[]" value="<?=$reqJadwalPegawaiFormPermenId?>" />
                                                <?=$reqJadwalPegawaiNamaIndikator?>.
                                              </li>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($reqJadwalPegawaiAtributIdLookUp == $reqJadwalPegawaiAtributId && $indexTr <= $reqJadwalPegawaiJumlahLevel)
                                        {
                                        ?>
                                            <br/><li style="width:100%; text-align:left" id="rbtn-<?=$index_detil?>-<?=$reqJadwalPegawaiIndikatorId?>-<?=$reqJadwalPegawaiLevelId?>-<?=$reqInfoPenggalianId?>-<?=$reqJadwalPegawaiDataAsesorId?>-<?=$tempAsesorId?>" class=" <?=$cssIndikator?>">
                                              <input type="hidden" id="reqJadwalPegawaiDetilId<?=$reqJadwalPegawaiIndikatorId?>" style="color:#000 !important" name="reqJadwalPegawaiDetilId[]" value="<?=$reqJadwalPegawaiDetilId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiTesId[]" value="<?=$reqJadwalTesId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiPenggalianId[]" value="<?=$reqInfoPenggalianId?>" />
                                              <input type="hidden" id="reqJadwalPegawaiLevelDataId<?=$reqJadwalPegawaiIndikatorId?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqJadwalPegawaiLevelDataId[]" value="<?=$reqJadwalPegawaiLevelDataId?>" />
                                              <input type="hidden" id="reqJadwalPegawaiIndikatorDataId<?=$reqJadwalPegawaiIndikatorId?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqJadwalPegawaiIndikatorDataId[]" value="<?=$reqJadwalPegawaiIndikatorDataId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiDataId[]" value="<?=$reqJadwalPegawaiDataId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiJadwalAsesorId[]" value="<?=$reqJadwalPegawaiJadwalAsesorId?>" />
                                              <input type="hidden" id="reqJadwalPegawaiAtributId<?=$reqJadwalPegawaiIndikatorId?>" style="color:#000 !important" name="reqJadwalPegawaiAtributId[]" value="<?=$reqJadwalPegawaiAtributId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiPegawaiId[]" value="<?=$reqPegawaiId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiAsesorId[]" value="<?=$tempAsesorId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqJadwalPegawaiFormPermenId[]" value="<?=$reqJadwalPegawaiFormPermenId?>" />
                                              <?=$reqJadwalPegawaiNamaIndikator?>
                                            </li>
                                        <?
                                        }
                                        ?>

                                      <?
                                      if($indexTr == $reqJadwalPegawaiJumlahLevel)
                                      {
                                        // reset info
                                        $indexTr= 1;
                                        $reqJadwalPegawaiAtributIdLookUp= "";

                                        $arrChecked= "";
                                        if($reqDetilAtributNilai == ""){}
                                        else{
                                          $arrChecked= radioPenilaian($reqDetilAtributNilai);
                                        }
                                          // echo $reqDetilAtributNilai;
                                      // print_r($arrChecked);
                                      ?>
                                            </ul>
                                          </div>
                                        </td>

                                        <!-- set data atribut -->
                                        <td style="vertical-align:top; background-color:transparent; color:#000 !important">
                                           <table style="width:100%; border:none">
                                            <tr>
                                              <td style="text-align:center; <?if ($arrChecked[0] != ""){?>background-color: orange !important;<?}?>">0</td>
                                              <td style="text-align:center; <?if ($arrChecked[1] != ""){?>background-color: orange !important;<?}?>">1</td>
                                              <td style="text-align:center; <?if ($arrChecked[2] != ""){?>background-color: orange !important;<?}?>">2</td>
                                              <td style="text-align:center; <?if ($arrChecked[3] != ""){?>background-color: orange !important;<?}?>">3</td>
                                              <td style="text-align:center; <?if ($arrChecked[4] != ""){?>background-color: orange !important;<?}?>">4</td>
                                              <td style="text-align:center; <?if ($arrChecked[5] != ""){?>background-color: orange !important;<?}?>">5</td>
                                              <td style="text-align:center" width="20%">Decimal</td>
                                            </tr>
                                            <tr>
                                              <?
                                              $kondisilihatatribut="1";
                                              $disabledatribut= "disabled";
                                              // button muncul apabila asesor yg berwenang
                                              if($reqJadwalPegawaiDataAsesorId == $tempAsesorId)
                                              {
                                                $disabledatribut= "";
                                                $kondisilihatatribut="";
                                              }
                                            
                                              ?>
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributDetilAtributId[]" value="<?=$reqDetilAtributDetilAtributId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributJadwalTesId[]" value="<?=$reqJadwalTesId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributPenggalianId[]" value="<?=$reqInfoPenggalianId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributJadwalPegawaiDataId[]" value="<?=$reqJadwalPegawaiDataId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributJadwalAsesorId[]" value="<?=$reqJadwalPegawaiJadwalAsesorId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributAtributId[]" value="<?=$reqJadwalPegawaiAtributId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributPegawaiId[]" value="<?=$reqPegawaiId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributAsesorId[]" value="<?=$tempAsesorId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqDetilAtributFormPermenId[]" value="<?=$reqJadwalPegawaiFormPermenId?>" />

                                              <input type="hidden" id="reqDetilAtributNilaiStandar<?=$index_detil?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqDetilAtributNilaiStandar[]" value="<?=$reqDetilAtributNilaiStandar?>" />
                                              <input type="hidden" id="reqDetilAtributNilai<?=$index_detil?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqDetilAtributNilai[]" <?if ($reqDetilAtributNilai==''){?> value="0" <?}else{?>value="<?=$reqDetilAtributNilai?>"<?}?> />
                                              <input type="hidden" id="reqDetilAtributGap<?=$index_detil?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqDetilAtributGap[]"<?if ($reqDetilAtributGap==''){?> value="0" <?}else{?>value="<?=$reqDetilAtributGap?>"<?}?> />

                                              <td align="center">
                                                <?
                                                if($kondisilihatatribut == "1" && $arrChecked[0] !== "")
                                                {
                                                ?>
                                                <label><?=valuechecked($arrChecked[0])?></label>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[0]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>-0" value="0" data-options="validType:'requireRadio[\'#ff-<?=$reqInfoPenggalianId?> input[name=reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>]\', \'Pilih nilai\']'"/>
                                                <?
                                                }
                                                ?>
                                              </td>
                                               <td align="center">
                                                <?
                                                if($kondisilihatatribut == "1" && $arrChecked[1] !== "")
                                                {
                                                ?>
                                                <label><?=valuechecked($arrChecked[1])?></label>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[1]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>-1" value="1" data-options="validType:'requireRadio[\'#ff-<?=$reqInfoPenggalianId?> input[name=reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>]\', \'Pilih nilai\']'"/>
                                                <?
                                                }
                                                ?>
                                              </td>
                                              <td align="center">
                                                <?
                                                if($kondisilihatatribut == "1" && $arrChecked[2] !== "")
                                                {
                                                ?>
                                                <label><?=valuechecked($arrChecked[2])?></label>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[2]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>-2" value="2" />
                                                <?
                                                }
                                                ?>
                                              </td>
                                              <td align="center">
                                                <?
                                                if($kondisilihatatribut == "1" && $arrChecked[3] !== "")
                                                {
                                                ?>
                                                <label><?=valuechecked($arrChecked[3])?></label>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[3]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>-3" value="3" />
                                                <?
                                                }
                                                ?>
                                              </td>
                                              <td align="center">
                                                <?
                                                if($kondisilihatatribut == "1" && $arrChecked[4] !== "")
                                                {
                                                ?>
                                                <label><?=valuechecked($arrChecked[4])?></label>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[4]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>-4" value="4" />
                                                <?
                                                }
                                                ?>
                                              </td>
                                              <td align="center">
                                                <?
                                                if($kondisilihatatribut == "1" && $arrChecked[5] !== "")
                                                {
                                                ?>
                                                <label><?=valuechecked($arrChecked[5])?></label>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[5]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>-5" value="5" />
                                                <?
                                                }
                                                ?>
                                              </td>
                                              <td>
                                                <?
                                                if($disabledatribut != "")
                                                {?>
                                                  <?=$reqDetilAtributDecimalJadi?>
                                                <?
                                                }else{?>
                                                <input value="<?=$reqDetilAtributDecimalJadi?>" type="number" name="decimalValue[]" style="color:black;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');">
                                                <?}?>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td colspan="7" style="background-color:white !important;color: black;">
                                                <?
                                                if($disabledatribut == "")
                                                {
                                                ?>
                                               <!-- <button onclick="resetpenilaian()">Reset Penilaian</button> -->
                                               <center>
                                               <a href="javascript: void(0)" style="background-color: blue;color: white; width:200px" onclick="resetpenilaiancbi(<?=$index_detil?>,<?=$reqInfoPenggalianId?>)" title="Cetak">Reset Penilaian</a></center>
                                                <textarea name="reqDetilAtributCatatan[]" class="easyui-validatebox" data-options="validType:'justText'"><?=$reqDetilAtributCatatan?></textarea>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="hidden" name="reqDetilAtributCatatan[]" style="color:black" value="<?=$reqDetilAtributCatatan?>" />
                                                <?=$reqDetilAtributCatatan?>
                                                <?
                                                }
                                                ?>
                                              </td>
                                            </tr>
                                          </table>
                                        </td>

                                      </tr>

                                      <?
                                      }
                                      ?>
                                  <?
                                      $reqJadwalPegawaiAtributIdLookUp= $reqJadwalPegawaiAtributId;
                                    }
                                  }
                                  ?>

                                  <?
                                  // button muncul apabila asesor yg berwenang
                                  if($reqJadwalPegawaiDataAsesorId == $tempAsesorId)
                                  {
                                  ?>
                                  <tr>
                                    <td colspan="2" align="center">
                                     <input type="hidden" name="reqMode" value="insert">
                                     <input name="submit1" type="submit" value="Simpan" />
                                   </td>
                                 </tr>
                                 <?
                                 }
                                 ?>
                               </tbody>
                             </table>
                           </form>
                         </div>
                        </div>
                        </div>
                        <?
                        }
                        // set form kalau bukan potensi
                        ?>

                      </div>
                      <?
                      }
                      ?>

                      <?
                      if($tempKondisiNilaiAkhir == "1")
                      {
                        $tempsimpankompetensi= "";
                      ?>
                      <div id="tabs-nilaiakhir" role="tabpanel" style="background: #063a69 !important;">
                        <!-- start of -->
                        <div class="row">
                          <div class="col-md-12">

                            <div class="area-table-assesor">
                              <br>
                              <div class="judul-halaman">Penilaian Kompetensi</div>
                              <form id="ff-" method="post" novalidate>

                                <table style="margin-bottom:60px;" class="profil">
                                  <thead>
                                    <!-- <tr>
                                      <th width="100%" colspan="10">Potensi Kecerdasan</th>
                                    </tr> -->
                                  </thead>
                                  <tbody>
                                    <?
                                    $arrayKey= in_array_column("2", "ASPEK_ID", $arrPenilaian);
                                    // print_r($arrayKey);exit;

                                    if($arrayKey == ''){}
                                    else
                                    {
                                      $reqPenilaianKompetensiGroup= "";
                                      $index_atribut_parent= 0;
                                      for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                                      {
                                        $index_row= $arrayKey[$index_detil];

                                        $reqPenilaianKompetensiAspekId= $arrPenilaian[$index_row]["ASPEK_ID"];
                                        $reqPenilaianKompetensiAtributNama= $arrPenilaian[$index_row]["NAMA"];
                                        $reqPenilaianKompetensiNilaiStandar= $arrPenilaian[$index_row]["NILAI_STANDAR"];
                                        $reqPenilaianKompetensiAtributId= $arrPenilaian[$index_row]["ATRIBUT_ID"];
                                        $reqPenilaianKompetensiAtributIdParent= $arrPenilaian[$index_row]["ATRIBUT_ID_PARENT"];
                                        $reqPenilaianKompetensiAtributGroup= $arrPenilaian[$index_row]["ATRIBUT_GROUP"];
                                        $reqPenilaianKompetensiDetilId= $arrPenilaian[$index_row]["PENILAIAN_DETIL_ID"];
                                        $reqPenilaianKompetensiNilaiAsli= $arrPenilaian[$index_row]["NILAI"];
                                        $pecahdecimal= explode(".",$reqPenilaianKompetensiNilaiAsli);
                                        $decimalValueNilaiAkhir= $pecahdecimal[1];
                                        $reqPenilaianKompetensiNilai= $pecahdecimal[0];
                                        $reqPenilaianKompetensiGap= $arrPenilaian[$index_row]["GAP"];
                                          // $reqPenilaianKompetensiGap= 0;


                                        if($reqPenilaianKompetensiGap == "" || $reqPenilaianKompetensiGap == "0")
                                          $reqPenilaianKompetensiGap= 0;
                                        else
                                          $reqPenilaianKompetensiGap= $reqPenilaianKompetensiNilaiAsli-$reqPenilaianKompetensiNilaiStandar;

                                        $reqPenilaianKompetensiCatatan= $arrPenilaian[$index_row]["BUKTI"];
                                        $reqPenilaianKompetensiBukti= $arrPenilaian[$index_row]["CATATAN"];

                                        
                                        // $reqPenilaianKompetensiCatatan= str_replace("<br>", "\n", $reqPenilaianKompetensiCatatan);

                                        // kondisi khusus karena salah data
                                        if($reqPenilaianKompetensiAtributId == "02")
                                          continue;

                                        //kondisi parent
                                        if($reqPenilaianKompetensiGroup == $reqPenilaianKompetensiAtributGroup)
                                        {
                                          $index_atribut++;
                                        }
                                        else
                                        {

                                          // kondisi khusus karena salah data
                                          if($reqPenilaianKompetensiAtributIdParent == "02")
                                            $index_atribut++;
                                          else
                                          {
                                            $index_atribut= 0;
                                            $index_atribut_parent++;
                                          }

                                        }

                                        $reqPenilaianKompetensiGroup= $reqPenilaianKompetensiAtributGroup;

                                        if($index_atribut_parent % 2 == 0)
                                          $css= "terang";
                                        else
                                          $css= "gelap";
                                    ?>
                                        <?
                                        if($reqPenilaianKompetensiAtributIdParent == "0")
                                        {
                                          // $tempcolspan= 9 + ($jumlahNilaiAkhir - 1);
                                          $tempcolspan= 8 + ($jumlah_pegawai_asesor);
                                          $tempcolspandetil= $tempcolspan+1;
                                        ?>
                                          <tr class="<?=$css?>">
                                            <th style="text-align:center"><b><?=romanicNumber($index_atribut_parent)?></b></th>
                                            <th colspan="<?=$tempcolspan+1?>"><b><?=$reqPenilaianKompetensiAtributNama?></b></th>
                                          </tr>
                                        <?
                                        }
                                        else
                                        {
                                          $arrChecked= "";
                                          if($reqPenilaianKompetensiNilai == "" ){}
                                          else
                                          $arrChecked= radioPenilaian($reqPenilaianKompetensiNilai);
                                        // echo "ahh";
                                        // print_r($arrChecked);
                                        ?>
                                          <tr class="">
                                            <td style="text-align:center; width: 1%" rowspan="2">No</td>
                                            <td style="text-align:center;" rowspan="2">ATRIBUT & INDIKATOR</td>
                                            <?
                                            // for($index_loop=0; $index_loop < $jumlah_pegawai_asesor; $index_loop++)
                                            // {
                                            //   $reqInfoPenggalianKode= $arrPegawaiAsesor[$index_loop]["PENGGALIAN_KODE"];
                                            ?>
                                            <!-- <td style="text-align:center; width: 5%" rowspan="2">Nilai <?=$reqInfoPenggalianKode?></td> -->
                                            <?
                                            // }
                                            ?>
                                            <td style="text-align:center; width: 5%" rowspan="2">Standar Rating</td>
                                            <td style="text-align:center" colspan="7">Hasil Individu</td>
                                            <td style="text-align:center; width: 5%" rowspan="2">Gap</td>
                                            <!-- <td style="text-align:center" rowspan="2">Deskripsi</td>
                                            <td style="text-align:center" rowspan="2">Saran Pengembang</td> -->
                                          </tr>
                                          <tr>
                                            <td style="text-align:center; width: 5%; <?if ($arrChecked[0] !== ""){?>background-color: orange !important;<?}?>">0</td>
                                            <td style="text-align:center; width: 5%; <?if ($arrChecked[1] !== ""){?>background-color: orange !important;<?}?>">1</td>
                                            <td style="text-align:center; width: 5%; <?if ($arrChecked[2] !== ""){?>background-color: orange !important;<?}?>">2</td>
                                            <td style="text-align:center; width: 5%; <?if ($arrChecked[3] !== ""){?>background-color: orange !important;<?}?>">3</td>
                                            <td style="text-align:center; width: 5%; <?if ($arrChecked[4] !== ""){?>background-color: orange !important;<?}?>">4</td>
                                            <td style="text-align:center; width: 5%; <?if ($arrChecked[5] !== ""){?>background-color: orange !important;<?}?>">5</td>
                                            <td style="text-align:center; width: 5%;">DESIMAL</td>
                                          </tr>
                                          <tr class="<?=$css?>">
                                            <td rowspan="2" style="vertical-align: top; text-align:center">
                                              <?=$index_atribut?>
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianKompetensiDetilId[]" id="reqPenilaianKompetensiDetilId<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianKompetensiDetilId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianKompetensiNilai[]" id="reqPenilaianKompetensiNilai<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianKompetensiNilai?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianKompetensiGap[]" id="reqPenilaianKompetensiGap<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianKompetensiGap?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianKompetensiNilaiStandar[]" id="reqPenilaianKompetensiNilaiStandar<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianKompetensiNilaiStandar?>" />
                                            </td>
                                            <td><?=$reqPenilaianKompetensiAtributNama?>.</td>
                                            <?
                                            $reqInfoDataPenggalianAsesorDataId= "";
                                            for($index_loop=0; $index_loop < $jumlah_pegawai_asesor; $index_loop++)
                                            {
                                              $reqInfoPenggalianKode= $arrPegawaiAsesor[$index_loop]["PENGGALIAN_KODE"];

                                              $tempCariDataDetilNilai= $arrPegawaiAsesor[$index_loop]["PENGGALIAN_ID"]."-".$reqPenilaianKompetensiAtributId;

                                              $arrayDetilKey= "";
                                              $arrayDetilKey= in_array_column($tempCariDataDetilNilai, "PENGGALIAN_ATRIBUT", $arrPegawaiPenilaian);
                                              // print_r($arrayDetilKey);exit;
                                              $tempInfoDataPenggalianAtributNilai= "-";
                                              if($arrayDetilKey == ''){}
                                              else
                                              {
                                                $index_detil_row= $arrayDetilKey[0];
                                                $tempInfoDataPenggalianAtributNilai= $arrPegawaiPenilaian[$index_detil_row]["NILAI"];
                                              }

                                              //============================
                                              // $tempCariDataDetilNilai= $reqPenilaianKompetensiAtributId."-".$tempAsesorId."-".$arrPegawaiAsesor[$index_loop]["PENGGALIAN_ID"];
                                              $tempCariDataDetilNilai= $reqPenilaianKompetensiAtributId."-".$tempAsesorId."-21";
                                              $arrayDetilKey= "";
                                              $arrayDetilKey= in_array_column($tempCariDataDetilNilai, "PENGGALIAN_ASESOR_ID", $arrAsesorPenilaianKompetensi);
                                              // print_r($tempCariDataDetilNilai);
                                              // print_r($arrAsesorPenilaianKompetensi);
                                              ?><span style="display:none;">asdasdasd</span><? 
                                            // echo "xxxx".$reqInfoDataPenggalianAsesorDataId."yyy";

                                              if($arrayDetilKey == ''){}
                                              else
                                              {
                                                $index_detil_row= $arrayDetilKey[0];
                                                $reqInfoDataPenggalianAsesorId= $arrAsesorPenilaianKompetensi[$index_detil_row]["ASESOR_ID"];

                                                // echo $reqInfoDataPenggalianAsesorId."<br/>";
                                                // kalau data asesor kosong maka set untuk validasi entri
                                                if($reqInfoDataPenggalianAsesorDataId == "")
                                                {                                                  
                                                  $reqInfoDataPenggalianAsesorDataId= $reqInfoDataPenggalianAsesorId;
                                                }
                                              }
                                            ?>
                                              <!-- <td style="text-align:center;"><?=$tempInfoDataPenggalianAtributNilai?></td> -->
                                            <?
                                            }
                                            // exit();
                                            ?>

                                            <?
                                            $bolehsimpan="";
                                            $kondisilihatatribut="1";
                                            $disabledatribut= "disabled";
                                            // button muncul apabila asesor yg berwenang
                                            if($reqInfoDataPenggalianAsesorDataId == $tempAsesorId)
                                            {
                                              $disabledatribut= "";
                                              $bolehsimpan= "1";
                                              $kondisilihatatribut="";

                                              // kalau ada data yg di bisa entri maka, bisa simpan asesor
                                              if($tempsimpankompetensi == "")
                                                $tempsimpankompetensi= 1;
                                            }
                                            ?>
                                            <td align="center"><?=NolToNone($reqPenilaianKompetensiNilaiStandar)?>&nbsp;</td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[0] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[0])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[0]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>-1" value="0" data-options="validType:'requireRadio[\'#ff- input[name=reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>]\', \'Pilih nilai\']'"/>
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[1] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[1])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[1]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>-2" value="1" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[2] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[2])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[2]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>-3" value="2" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[3] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[3])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[3]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>-4" value="3" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[4] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[4])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[4]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>-5" value="4" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center">
                                              <?
                                              if($kondisilihatatribut == "1" && $arrChecked[5] !== "")
                                              {
                                              ?>
                                              <label><?=valuechecked($arrChecked[5])?></label>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[5]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>-5" value="5" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            
                                            <td>
                                             <?
                                                if($disabledatribut != "")
                                                {?>
                                                  <?=$decimalValueNilaiAkhir?>
                                                <?
                                                }else{?>    
                                              <input value="<?=$decimalValueNilaiAkhir?>" type="number" name="decimalValueNilaiAkhir[]" id="decimalValueNilaiAkhir<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" style="color:black;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');">
                                              <?}?>
                                            </td>
                                            <td align="center"><label id="reqPenilaianKompetensiGapInfo<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>"><?=$reqPenilaianKompetensiGap?></label>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td colspan="9">
                                              <?if($disabledatribut==''){?>
                                              <center><a href="javascript: void(0)" style="background-color:white ;color: blue; width:200px" onclick="resetpenilaianna(<?=$reqPenilaianKompetensiAspekId?>,<?=$index_detil?>)" title="Cetak">Reset Penilaian</a></center>
                                              <?}?>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td colspan="<?=$tempcolspandetil+1?>">
                                              <input type="hidden" name="reqPenilaianKompetensiBolehSimpan[]" value="<?=$bolehsimpan?>" />
                                              <?
                                              $munculsarancss="";
                                              if($reqPenilaianKompetensiGap >= 0)
                                              $munculsarancss="display:none";

                                              if($disabledatribut == "")
                                              {
                                              ?>
                                              <fieldset>
                                                <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important; ">Deskripsi</legend>
                                                <textarea name="reqPenilaianKompetensiBukti[]" id="reqPenilaianKompetensiBukti<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" style="width:95%;" rows="1" ><?=$reqPenilaianKompetensiBukti?></textarea>
                                              </fieldset>
                                              <span style="<?=$munculsarancss?>" id="reqPenilaianKompetensiSaran<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>">
                                                <fieldset>
                                                  <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Saran Pengembangan</legend>
                                                  <textarea name="reqPenilaianKompetensiCatatan[]" id="reqPenilaianKompetensiCatatan<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" style="width:95%" rows="1" ><?=$reqPenilaianKompetensiCatatan?></textarea>
                                                </fieldset>
                                              </span>
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <fieldset style="border: 1px solid; padding: 10px !important">
                                                <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important; ">Deskripsi</legend>
                                                <input type="hidden" name="reqPenilaianKompetensiBukti[]" value="<?=$reqPenilaianKompetensiBukti?>" />
                                                <?=$reqPenilaianKompetensiBukti?>
                                              </fieldset>
                                              <span style="<?=$munculsarancss?>" id="reqPenilaianKompetensiSaran<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>">
                                                <fieldset style="border: 1px solid; padding: 10px !important">
                                                  <legend style="font-size: 14px !important; border: medium none !important; margin-top: 20px; margin-bottom: 10px; ">Saran Pengembangan</legend>
                                                  <input type="hidden" name="reqPenilaianKompetensiCatatan[]" value="<?=$reqPenilaianKompetensiCatatan?>" />
                                                  <?=$reqPenilaianKompetensiCatatan?>
                                                </fieldset>
                                              </span>
                                              <?
                                              }
                                              ?>
                                            </td>
                                          </tr>
                                        <?
                                        }
                                        ?>
                                      <?
                                      }
                                      ?>
                                    <?
                                    }
                                    ?>

                                    <?
                                    $tempsarancolspan= $tempcolspan + 1;
                                    ?>
                                    <tr>
                                      <th colspan="<?=$tempsarancolspan+1?>">
                                        Area Pengembangan
                                        <?
                                        $moderekomendasi= "NilaiAkhirSaranPengembangan";
                                        if($tempsimpankompetensi == "1")
                                        {
                                        ?>
                                        <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                        <?
                                        }
                                        ?>
                                      </th>
                                      <input type="hidden" name="reqNilaiAkhirSaranPengembanganPegawaiId" value="<?=$reqPegawaiId?>" />
                                      <input type="hidden" name="reqNilaiAkhirSaranPengembanganJadwalTesId" value="<?=$reqJadwalTesId?>." />
                                    </tr>
                                    <tr>
                                      <td colspan="<?=$tempsarancolspan+1?>">
                                      <?
                                      if($tempsimpankompetensi == "1")
                                      {
                                      ?>
                                      <div id ="<?=$moderekomendasi?>">
                                        <fieldset>
                                        <?
                                        for($index_catatan=0; $index_catatan<$jumlahNilaiAkhirSaranPengembangan; $index_catatan++)
                                        {
                                          $reqinfocatatan= $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"];
                                        ?>
                                          <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqNilaiAkhirSaranPengembangan[]" value="<?=$reqinfocatatan?>" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                          <p>
                                            <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqNilaiAkhirSaranPengembangan[]"><?=$reqinfocatatan?></textarea>
                                            <?
                                            if($index_catatan > 0)
                                            {
                                            ?>
                                            <a rel="<?=$index_catatan+1?>" class="remScnt" href="#">Remove</a>
                                            <?
                                            }
                                            ?>
                                          </p>
                                        <?
                                        }
                                        if($index_catatan == 0)
                                        {
                                        ?>
                                          <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqNilaiAkhirSaranPengembangan[]" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('textarea').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a>-->
                                          <p>
                                            <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqNilaiAkhirSaranPengembangan[]"></textarea>
                                          </p>
                                        <?
                                        }
                                        ?>
                                        </fieldset>
                                      </div>
                                      <br>
                                      <?
                                      }
                                      else
                                      {
                                      ?>
                                      <fieldset style="border: 1px solid; padding: 10px !important">
                                        <!-- <input type="hidden" name="reqNilaiAkhirSaranPengembangan" value="<?=$reqNilaiAkhirSaranPengembangan?>" /><?=$reqNilaiAkhirSaranPengembangan?> -->
                                        <?
                                        for($index_catatan=0; $index_catatan<$jumlahNilaiAkhirSaranPengembangan; $index_catatan++)
                                        {
                                        $reqinfocatatan= $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"];
                                        ?>
                                        <li style="margin-left: 10px;"><?=$reqinfocatatan?></li>
                                        <?
                                        }
                                        ?>
                                      </fieldset>
                                      <?
                                      }
                                      ?>
                                      </td>
                                    </tr>

                                    <?
                                    // $tempsimpankompetensi= "1";
                                    if($tempsimpankompetensi == "1")
                                    {
                                    ?>
                                    <tr>
                                      <td colspan="15" align="center">
                                        <input type="hidden" name="reqNilaiAkhirPegawaiId" value="<?=$reqPegawaiId?>" />
                                        <input type="hidden" name="reqNilaiAkhirJadwalTesId" value="<?=$reqJadwalTesId?>" />
                                        <input type="hidden" name="reqMode" value="insert">
                                        <input name="submit1" type="submit" value="Simpan" />
                                     </td>
                                   </tr>
                                   <?
                                   }
                                   ?>
                                  </tbody>
                                </table>

                              </form>
                            </div>

                          </div>
                        </div>
                        <!-- end of -->
                      </div>

                      <div id="tabs-lain" role="tabpanel" style="background: #063a69 !important;">
                        <div class="row">
                          <div class="col-md-12">

                            <div class="area-table-assesor">
                              <br>
                              <div class="judul-halaman">Kesimpulan</div>
                              <form id="ff-simpan" method="post" novalidate>

                                <table style="margin-bottom:60px;" class="profil">
                                  <thead>

                                  </thead>
                                  <tbody id="tbDataLoop">
                                
                                    <tr>
                                      <td colspan="5"  style="background-color:white !important; color:black;">

                                        <?
                                        // untuk reset bisa entri
                                        $disabledatribut= "";
                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiStrength";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Kekuatan
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <!--  <textarea name="reqPenilaianPotensiStrength" id="reqPenilaianPotensiStrength" style="width:95%" rows="1" ><?=$reqPenilaianPotensiStrength?></textarea> -->
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPotensiStrength[$index_catatan]["KETERANGAN"];
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiStrength[]" value="<?=$reqinfocatatan?>" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                              <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiStrength[]"><?=$reqinfocatatan?></textarea>
                                                <?
                                                if($index_catatan > 0)
                                                {
                                                ?>
                                                <a rel="<?=$index_catatan+1?>" class="remScnt" href="#">Remove</a>
                                                <?
                                                }
                                                ?>
                                              </p>
                                            <?
                                            }
                                            if($index_catatan == 0)
                                            {
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiStrength[]" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                              <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiStrength[]"></textarea>
                                              </p>
                                            <?
                                            }
                                            ?>
                                          </fieldset>
                                        </div>
                                        <br>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Kekuatan</legend>
                                          <!-- <input type="hidden" name="reqPenilaianPotensiStrength" value="<?=$reqPenilaianPotensiStrength?>" /><?=$reqPenilaianPotensiStrength?> -->
                                          <?
                                          for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
                                          {
                                            $reqinfocatatan= $arrPotensiStrength[$index_catatan]["KETERANGAN"];
                                          ?>
                                            <li style="margin-left: 10px;"><?=$reqinfocatatan?></li>
                                          <?
                                          }
                                          ?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiWeaknes";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Kelemahan
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <!--  <textarea name="reqPenilaianPotensiWeaknes" id="reqPenilaianPotensiWeaknes" style="width:95%" rows="1" ><?=$reqPenilaianPotensiWeaknes?></textarea> -->
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiWeaknes; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiWeaknes[$index_catatan]["KETERANGAN"];
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiWeaknes[]" value="<?=$reqinfocatatan?>" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                              <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiWeaknes[]"><?=$reqinfocatatan?></textarea>
                                                <?
                                                if($index_catatan > 0)
                                                {
                                                ?>
                                                <a rel="<?=$index_catatan+1?>" class="remScnt" href="#">Remove</a>
                                                <?
                                                }
                                                ?>
                                              </p>
                                            <?
                                            }
                                            if($index_catatan == 0)
                                            {
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiWeaknes[]" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                              <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiWeaknes[]"></textarea>
                                              </p>
                                            <?
                                            }
                                            ?>
                                          </fieldset>
                                        </div>
                                        <br>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Kelemahan</legend>
                                          <!-- <input type="hidden" name="reqPenilaianPotensiWeaknes" value="<?=$reqPenilaianPotensiWeaknes?>" /><?=$reqPenilaianPotensiWeaknes?> -->
                                          <?
                                          for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiWeaknes; $index_catatan++)
                                          {
                                            $reqinfocatatan= $arrPenilaianPotensiWeaknes[$index_catatan]["KETERANGAN"];
                                          ?>
                                            <li style="margin-left: 10px;"><?=$reqinfocatatan?></li>
                                          <?
                                          }
                                          ?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiKesimpulan";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Rekomendasi
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <!--  <textarea name="reqPenilaianPotensiKesimpulan" id="reqPenilaianPotensiKesimpulan" style="width:95%" rows="1" ><?=$reqPenilaianPotensiKesimpulan?></textarea> -->
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiKesimpulan; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiKesimpulan[$index_catatan]["KETERANGAN"];
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiKesimpulan[]" value="<?=$reqinfocatatan?>" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                              <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiKesimpulan[]"><?=$reqinfocatatan?></textarea>
                                                <?
                                                if($index_catatan > 0)
                                                {
                                                ?>
                                                <a rel="<?=$index_catatan+1?>" class="remScnt" href="#">Remove</a>
                                                <?
                                                }
                                                ?>
                                              </p>
                                            <?
                                            }
                                            if($index_catatan == 0)
                                            {
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiKesimpulan[]" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                              <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiKesimpulan[]"></textarea>
                                              </p>
                                            <?
                                            }
                                            ?>
                                          </fieldset>
                                        </div>
                                        <br>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Rekomendasi</legend>
                                          <!-- <input type="hidden" name="reqPenilaianPotensiKesimpulan" value="<?=$reqPenilaianPotensiKesimpulan?>" /><?=$reqPenilaianPotensiKesimpulan?> -->
                                          <?
                                          for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiKesimpulan; $index_catatan++)
                                          {
                                            $reqinfocatatan= $arrPenilaianPotensiKesimpulan[$index_catatan]["KETERANGAN"];
                                          ?>
                                            <li style="margin-left: 10px;"><?=$reqinfocatatan?></li>
                                          <?
                                          }
                                          ?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiSaranPengembangan";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Saran Pengembangan
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <!--  <textarea name="reqPenilaianPotensiSaranPengembangan" id="reqPenilaianPotensiSaranPengembangan" style="width:95%" rows="1" ><?=$reqPenilaianPotensiSaranPengembangan?></textarea> -->
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPengembangan; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"];
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiSaranPengembangan[]" value="<?=$reqinfocatatan?>" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                              <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiSaranPengembangan[]"><?=$reqinfocatatan?></textarea>
                                                <?
                                                if($index_catatan > 0)
                                                {
                                                ?>
                                                <a rel="<?=$index_catatan+1?>" class="remScnt" href="#">Remove</a>
                                                <?
                                                }
                                                ?>
                                              </p>
                                            <?
                                            }
                                            if($index_catatan == 0)
                                            {
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiSaranPengembangan[]" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                               <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiSaranPengembangan[]"></textarea>
                                              </p>
                                            <?
                                            }
                                            ?>
                                          </fieldset>
                                        </div>
                                        <br>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Saran Pengembangan</legend>
                                          <!-- <input type="hidden" name="reqPenilaianPotensiSaranPengembangan" value="<?=$reqPenilaianPotensiSaranPengembangan?>" /><?=$reqPenilaianPotensiSaranPengembangan?> -->
                                          <?
                                          for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPengembangan; $index_catatan++)
                                          {
                                            $reqinfocatatan= $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"];
                                          ?>
                                            <li style="margin-left: 10px;"><?=$reqinfocatatan?></li>
                                          <?
                                          }
                                          ?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiSaranPenempatan";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Saran Penempatan
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <!--  <textarea name="reqPenilaianPotensiSaranPenempatan" id="reqPenilaianPotensiSaranPenempatan" style="width:95%" rows="1" ><?=$reqPenilaianPotensiSaranPenempatan?></textarea> -->
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPenempatan; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiSaranPenempatan[$index_catatan]["KETERANGAN"];
                                            ?>
                                             <!--  <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiSaranPenempatan[]" value="<?=$reqinfocatatan?>" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> 
                                             -->
                                             <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiSaranPenempatan[]"><?=$reqinfocatatan?></textarea>
                                                <?
                                                if($index_catatan > 0)
                                                {
                                                ?>
                                                <a rel="<?=$index_catatan+1?>" class="remScnt" href="#">Remove</a>
                                                <?
                                                }
                                                ?>
                                              </p>
                                            <?
                                            }
                                            if($index_catatan == 0)
                                            {
                                            ?>
                                              <!-- <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiSaranPenempatan[]" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                               <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiSaranPenempatan[]"></textarea>
                                              </p>
                                            <?
                                            }
                                            ?>
                                          </fieldset>
                                        </div>
                                        <br>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Saran Penempatan</legend>
                                          <!-- <input type="hidden" name="reqPenilaianPotensiSaranPenempatan" value="<?=$reqPenilaianPotensiSaranPenempatan?>" /><?=$reqPenilaianPotensiSaranPenempatan?> -->
                                          <?
                                          for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPenempatan; $index_catatan++)
                                          {
                                            $reqinfocatatan= $arrPenilaianPotensiSaranPenempatan[$index_catatan]["KETERANGAN"];
                                          ?>
                                            <li style="margin-left: 10px;"><?=$reqinfocatatan?></li>
                                          <?
                                          }
                                          ?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>
                                        
                                        <?
                                        // if($disabledatribut == "" || $reqInfoPenggalianId == "0")
                                        if($disabledatribut == "" && ($reqPenilaianPotensiDataAsesorId == $tempAsesorId || $tempsimpankompetensi == "1") )
                                        {
                                          $moderekomendasi= "PenilaianKeteranganKepribadian";
                                        ?>
                                          <div id ="<?=$moderekomendasi?>">
                                          
                                          </div> 
                                          <br>
                                        
                                        <?
                                        }
                                        else
                                        {
                                        ?>

                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                        ?>
                                       <!--  <fieldset>
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Kesesuaian Rumpun Pekerjaan</legend>
                                          <textarea name="reqPenilaianPotensiKesesuaianRumpun" id="reqPenilaianPotensiKesesuaianRumpun" style="width:95%" rows="1" ><?=$reqPenilaianPotensiKesesuaianRumpun?></textarea>
                                        </fieldset> -->
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <!-- <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Kesesuaian Rumpun Pekerjaan</legend>
                                          <input type="hidden" name="reqPenilaianPotensiKesesuaianRumpun" value="<?=$reqPenilaianPotensiKesesuaianRumpun?>" />
                                          <?=$reqPenilaianPotensiKesesuaianRumpun?>
                                        </fieldset> -->
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiProfilKompetensi";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Ringkasan Profil Kompetensi
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <!--  <textarea name="reqPenilaianPotensiProfilKompetensi" id="reqPenilaianPotensiProfilKompetensi" style="width:95%" rows="1" ><?=$reqPenilaianPotensiProfilKompetensi?></textarea> -->
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiProfilKompetensi; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"];
                                            ?>
                                             <!--  <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiProfilKompetensi[]" value="<?=$reqinfocatatan?>" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                             <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiProfilKompetensi[]"><?=$reqinfocatatan?></textarea>
                                                <?
                                                if($index_catatan > 0)
                                                {
                                                ?>
                                                <a rel="<?=$index_catatan+1?>" class="remScnt" href="#">Remove</a>
                                                <?
                                                }
                                                ?>
                                              </p>
                                            <?
                                            }
                                            if($index_catatan == 0)
                                            {
                                            ?>
                                             <!--  <input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="reqPenilaianPotensiProfilKompetensi[]" /> <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev('input').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a> -->
                                             <p>
                                                <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianPotensiProfilKompetensi[]"></textarea>
                                              </p>
                                            <?
                                            }
                                            ?>
                                          </fieldset>
                                        </div>
                                        <br>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Ringkasan Profil Kompetensi</legend>
                                          <!-- <input type="hidden" name="reqPenilaianPotensiProfilKompetensi" value="<?=$reqPenilaianPotensiProfilKompetensi?>" /><?=$reqPenilaianPotensiProfilKompetensi?> -->
                                          <?
                                          for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiProfilKompetensi; $index_catatan++)
                                          {
                                            $reqinfocatatan= $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"];
                                          ?>
                                            <li style="margin-left: 10px;"><?=$reqinfocatatan?></li>
                                          <?
                                          }
                                          ?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>
                                        
                                        <input type="hidden" style="color:#000 !important" name="reqLainPenilaianPotensiId" value="<?=$reqLainPenilaianPotensiId?>" />
                                      </td>
                                    </tr>
                                 
                                    <?
                                    // $tempsimpankompetensi= "1";
                                    // if($tempsimpankompetensi == "1" || $reqInfoPenggalianId == "0")
                                    if($reqPenilaianPotensiDataAsesorId == $tempAsesorId || $tempsimpankompetensi == "1")
                                    {
                                    ?>
                                    <tr>
                                      <td colspan="15" align="center">
                                        <input type="hidden" name="reqMode" value="insert">
                                        <input name="submit1" type="submit" value="Simpan" />
                                      </td>
                                    </tr>
                                    <?
                                    }
                                    ?>
                                  </tbody>
                                </table>

                                <input type="hidden" name="reqKesimpulanPegawaiId" value="<?=$reqPegawaiId?>" />
                                <input type="hidden" name="reqKesimpulanJadwalTesId" value="<?=$reqJadwalTesId?>" />

                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?
                      }
                      ?>

           </div>
           
           

       </div>
   </div>


   <div style="margin:40px">&nbsp;</div>
   
</div>
</div>
<footer class="footer">
  © 2021 Pemprov Kaltim. All Rights Reserved. 
</footer>

<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<!-- <script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script>  -->

<!-- SCROLLING TAB -->
<script src="../WEB/lib/Scrolling/jquery-1.12.4.min.js"></script>
<script src="../WEB/lib/Scrolling/jquery-ui.min.js"></script>
<script src="../WEB/lib/Scrolling/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquery.ui.scrolltabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyuiasesor.css">
<!-- <script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script> -->
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>

<script>
  // $.messager.alert('Info', "s", 'info');

$(document).ready(function() {
  $(function(){
        <?
         if($data['foto_original'] == "")
       {
        ?>
        $("#reqImagePeserta").attr("src", "../WEB/images/no-picture.jpg");
        <?
        }
        else
        {
            ?>
            $("#reqImagePeserta").attr("src", "<?=$data['foto_original']?>");
            <?
        }
        ?>

        $('.rbtn ul li').click(function(){
        // get the value from the id of the clicked li and attach it to the window object to be able to use it later.
            var choice= this.id;
            var text= $(this).text();
            var element= choice.split('-');

            var reqJadwalPegawaiIndikatorId= reqJadwalPegawaiLevelId= reqInfoPenggalianId= "";
            reqJadwalPegawaiIndikatorId= element[2];
            reqJadwalPegawaiLevelId= element[3];
            reqInfoPenggalianId= element[4];
            reqJadwalPegawaiDataAsesorId= element[5];
            tempAsesorId= element[6];

            // cursor:not-allowed;
            // button muncul apabila asesor yg berwenang
            if(reqJadwalPegawaiDataAsesorId == tempAsesorId)
            {
              if($('li[id^="'+choice+'"]').hasClass("selected") == true)
              {
                  $('li[id^="'+choice+'"]').removeClass('selected');
                  $('li[id^="'+choice+'"]').addClass('sebelumselected');
                  $("#reqJadwalPegawaiIndikatorDataId"+reqJadwalPegawaiIndikatorId+"-"+reqInfoPenggalianId+", #reqJadwalPegawaiLevelDataId"+reqJadwalPegawaiIndikatorId+"-"+reqInfoPenggalianId).val("");
              }
              else
              {
                  $('li[id^="'+choice+'"]').removeClass('sebelumselected');
                  $('li[id^="'+choice+'"]').addClass('selected');
                  $("#reqJadwalPegawaiIndikatorDataId"+reqJadwalPegawaiIndikatorId+"-"+reqInfoPenggalianId).val(reqJadwalPegawaiIndikatorId);
                  $("#reqJadwalPegawaiLevelDataId"+reqJadwalPegawaiIndikatorId+"-"+reqInfoPenggalianId).val(reqJadwalPegawaiLevelId);
              }
            }
            
        }); 
        
        $('.rbtn ul li').mouseover(function(){
            var choice= this.id;
            var text= $(this).text();
            var element= choice.split('-');

            var reqJadwalPegawaiIndikatorId= reqJadwalPegawaiLevelId= reqInfoPenggalianId= "";
            reqJadwalPegawaiIndikatorId= element[2];
            reqJadwalPegawaiLevelId= element[3];
            reqInfoPenggalianId= element[4];
            reqJadwalPegawaiDataAsesorId= element[5];
            tempAsesorId= element[6];
            // console.log("s");

            $('.rbtn ul li').attr('style','cursor: pointer;');
            // button muncul apabila asesor yg berwenang
            if(reqJadwalPegawaiDataAsesorId == tempAsesorId){}
            else
            {
              $('.rbtn ul li').attr('style','cursor: not-allowed;');
            }
            // $(this).addClass('over');
        });
        
        $('.rbtn ul li').mouseout(function(){
            // console.log("e");
            // $(this).removeClass('over');
        });

    });
});

$(function(){
  $.extend($.fn.validatebox.defaults.rules, {
    requireRadio: {  
      validator: function(value, param){  
        var input = $(param[0]);
        input.off('.requireRadio').on('click.requireRadio',function(){
          $(this).focus();
        });
        // console.log(param[0]);
        return true;
        // return $(param[0] + ':checked').val() != undefined;
      },  
      message: 'Please choose option for {1}.'  
    },
    justText: {  
     validator: function(value, param){
       return true;
       // if(value == "<br>")
       // {
       //  // console.log("ada");
       //  return false;
       // }
       // else
       //  return true;

       // console.log(value);
       // return !value.match(/[0-9]/);
     },  
     message: 'Please enter only text.'  
    }
  });

  <?
  for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
  {
    $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
    // $tempurl= "";
    // if($reqInfoPenggalianId == "0"){}
    // else
    $tempurl= "penilaian_monitoring.php";

  ?>
    $('#ff-<?=$reqInfoPenggalianId?>').form({
      url:'../json-asesor/<?=$tempurl?>',
      onSubmit:function(){
        var temp= $(this).form('validate');
        
        if($(this).form('validate') == false)
        {
          $.messager.alert('Info', "Isi terlebih dahulu, atribut dan catatan", 'info');
          return false;
        }

        return $(this).form('validate');
      },
      success:function(data){
                // autologin(); return false ;
        
        // console.log(data); return false;
        // $.messager.alert('Info', data, 'info');
        // $('#rst_form').click();
        //parent.setShowHideMenu(3);
        data = data.split("-");
            rowid= data[0];
            infodata= data[1];
         if(rowid == "xxx"){}
            else if(rowid == "autologin"){
                autologin(); return false ;
            }
            else
            {
              document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=<?=$reqInfoPenggalianId?>';
            }
        // document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=<?=$reqInfoPenggalianId?>';
      }
    });
  <?
  }
  if($tempKondisiNilaiAkhir == "1")
  {
    $tempurl= "penilaian_monitoring.php";
  ?>
    $('#ff-').form({
      url:'../json-asesor/<?=$tempurl?>',
      onSubmit:function(){
        var temp= $(this).form('validate');
        // console.log('-'+temp);
        // return false;
        if($(this).form('validate') == false)
        {
          $.messager.alert('Info', "Isi terlebih dahulu, atribut dan catatan", 'info');
          return false;
        }

        return $(this).form('validate');
      },
      success:function(data){
        // console.log(data);return false;
        // $.messager.alert('Info', data, 'info');
        // $('#rst_form').click();
        //parent.setShowHideMenu(3);
        data = data.split("-");
            rowid= data[0];
            infodata= data[1];
          if(rowid == "xxx"){}
            else if(rowid == "autologin"){
                autologin(); return false ;
            }
            else
            {
                document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-nilaiakhir';
            }
        // docume
        // document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-nilaiakhir';
      }
    });
  <?
  }
  ?>

  $('#ff-simpan').form({
    url:'../json-asesor/penilaian_monitoring.php',
    onSubmit:function(){
      var temp= $(this).form('validate');
      // console.log('-'+temp);
      // return false;
      if($(this).form('validate') == false)
      {
        $.messager.alert('Info', "Isi terlebih dahulu, atribut dan catatan", 'info');
        return false;
      }

      return $(this).form('validate');
    },
    success:function(data){
      // console.log(data);return false;
      data = data.split("-");
            rowid= data[0];
            infodata= data[1];
       if(rowid == "xxx"){}
            else if(rowid == "autologin"){
                autologin(); return false ;
            }
            else
            {
                 document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-lain';
            }
      // document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-lain';
    }
  });
  
  $('input[id^="reqRadio"]').change(function(e) {
    var tempId= $(this).attr('id');
    var tempValId= $(this).val();
    arrId= tempId.split('reqRadio');
    arrId= arrId[1].split('-');
    tempId= arrId[0];
    reqInfoPenggalianId= arrId[1];

    rowid= tempId+"-"+reqInfoPenggalianId;
    // console.log(rowid);

    $("#reqDetilAtributNilai"+rowid).val(tempValId);
    var gap= parseInt(tempValId) - parseInt($("#reqDetilAtributNilaiStandar"+rowid).val());
    $("#reqDetilAtributGap"+rowid).val(gap);
    // $("#reqGapInfo"+rowid).text(gap);
  });

  $('input[id^="reqPenilaianRadio"]').change(function(e) {
    var tempId= $(this).attr('id');
    var tempValId= $(this).val();
    arrId= tempId.split('reqPenilaianRadio');
    arrId= arrId[1].split('-');
    tempAspekId= arrId[0];
    reqIndekId= arrId[1];

    rowid= tempAspekId+"-"+reqIndekId;
    // console.log(rowid);

    $("#reqPenilaianPotensiNilai"+rowid).val(tempValId);
    var gap= parseInt(tempValId) - parseInt($("#reqPenilaianPotensiNilaiStandar"+rowid).val());
    $("#reqPenilaianPotensiGap"+rowid).val(gap);
    $("#reqPenilaianPotensiGapInfo"+rowid).text(gap);

    /*$("#reqPenilaianPotensiSaran"+rowid).show();
    if(gap == 0)
    $("#reqPenilaianPotensiSaran"+rowid).hide();*/

  });

  $('input[id^="reqPenilaianKompetensiRadio"]').change(function(e) {
    var tempId= $(this).attr('id');
    var tempValId= $(this).val();
    arrId= tempId.split('reqPenilaianKompetensiRadio');
    arrId= arrId[1].split('-');
    tempAspekId= arrId[0];
    reqIndekId= arrId[1];

    rowid= tempAspekId+"-"+reqIndekId;
    // console.log(rowid);

    nilaikoma= $("#decimalValueNilaiAkhir"+rowid).val();
    // console.log(parseFloat(tempValId+"."+nilaikoma));

    $("#reqPenilaianKompetensiNilai"+rowid).val(tempValId);
    var gap= parseFloat(tempValId+"."+nilaikoma) - parseFloat($("#reqPenilaianKompetensiNilaiStandar"+rowid).val());
    $("#reqPenilaianKompetensiGap"+rowid).val(gap);
    $("#reqPenilaianKompetensiGapInfo"+rowid).text(gap);

    $("#reqPenilaianKompetensiSaran"+rowid).show();
    if(parseFloat(gap) >= 0)
    $("#reqPenilaianKompetensiSaran"+rowid).hide();

  });

  $('input[id^="decimalValueNilaiAkhir"]').keyup(function(e) {
    var tempId= $(this).attr('id');
    var nilaikoma= $(this).val();
    arrId= tempId.split('decimalValueNilaiAkhir');
    arrId= arrId[1].split('-');
    tempAspekId= arrId[0];
    reqIndekId= arrId[1];

    rowid= tempAspekId+"-"+reqIndekId;
    // console.log(rowid);
    // console.log(nilaikoma);
    
    tempValId= $("input[name='reqPenilaianKompetensiRadio"+rowid+"']:checked").val();
    // console.log(tempValId);
    // console.log(parseFloat(tempValId+"."+nilaikoma));

    $("#reqPenilaianKompetensiNilai"+rowid).val(tempValId);
    var gap= parseFloat(tempValId+"."+nilaikoma) - parseFloat($("#reqPenilaianKompetensiNilaiStandar"+rowid).val());
    $("#reqPenilaianKompetensiGap"+rowid).val(gap);
    $("#reqPenilaianKompetensiGapInfo"+rowid).text(gap);

    $("#reqPenilaianKompetensiSaran"+rowid).show();
    if(parseFloat(gap) >= 0)
    $("#reqPenilaianKompetensiSaran"+rowid).hide();

  });




});
  
  var editors = {}; //Keep track of all added nicEditors for removal later  
  function createRow(mode)
  {
    var scntDiv = $("#"+mode +" fieldset");

    // infodata= '<input style="margin: 4px auto; width:97%; color:#06345f;" type="text" name="req'+mode+'[]" />';
    // infodata+= ' <a style="cursor:pointer; text-align: left; display: inline" onclick="$(this).prev(\'input\').remove(); $(this).remove();"><img src="../WEB/images/delete-icon.png"></a>';

    infodata= '<textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="req'+mode+'[]"></textarea>';
    // setdelete($(this));
    // $("#"+mode +" fieldset").append(infodata);

    var elm = $(infodata).appendTo(scntDiv); // Add the textarea to DOM
    var curSize = $('textarea[name="req'+mode+'[]"]').length; //Get the current SIZE of textArea
    // console.log(curSize);
    editors[curSize] = new nicEditor().panelInstance(elm[0]); //Set the Object with the index as key and reference for removel

    elm.after($('<a/>', { //Create anchor Tag with rel attribute as that of the index of corresponding editor
       rel: curSize,
           'class': "remScnt",
       text: "Remove",
       href: '#'
   })).next().andSelf().wrapAll($('<p/>'));
   // })).next().wrapAll($('<p/>'));
  }

  $(document).on('click', '.remScnt', function (e) {
       e.preventDefault();
       var elem = $(this).prev('textarea'); //Get the textarea of the respective anchor
       // console.log(elem);
       var index = this.rel; //get the key from rel attribute of the anchor
       // console.log(index);

       if(typeof editors[index] == "undefined"){}
       else
       {
          editors[index].removeInstance(elem[0]); //Use it to get the instace and remove
          delete editors[index]; //delete the property from object
       }
       // $(this).closest('.nicEdit-main').remove(); //remove the element.
       $(this).closest('p').remove(); //remove the element.
       // elem.remove(); //remove the element.
       
   });

  /*function setdelete(this)
  {
    var elem = $(this).prev('textarea'); //Get the textarea of the respective anchor
    var index = this.rel; //get the key from rel attribute of the anchor
    editors[index].removeInstance(elem[0]); //Use it to get the instace and remove
    delete editors[index]; //delete the property from object
    $(this).closest('p').remove(); //remove the element.
  }*/

</script>

<script type="text/javascript">
   // setModal("tabs-15", "tes2.php");
   function setModal(target, link_url)
   {
     var s_url= link_url;
     $.ajax({'url': s_url,'success': function(msg)
     {
      if(msg == ''){}
        else
        {
         $('#'+target).html(msg);
         // bkLib.onDomLoaded(nicEditors.allTextAreas);
       }
     }});
   }
</script>

<script>
var $tabs;
var scrollEnabled;
$(function () {
  // $('.nicEdit-main').width('100%');
    // To get the random tabs label with variable length for testing the calculations
    $('#example_0').scrollTabs({
      scrollOptions: {
        // enableDebug: true,
        selectTabAfterScroll: false,
        closable: false,
      }
    });

    // $('#tabs-17').trigger('click');

     bkLib.onDomLoaded(function() {
      nicEditors.allTextAreas();
        // new nicEditor({fullPanel : true, maxHeight:100}).panelInstance('myArea');
        $('.nicEdit-panelContain').parent().width('100%');
        $('.nicEdit-panelContain').parent().next().width('98%');
        $('.nicEdit-main').width('100%');
        });

  // bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });


});

</script>

<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<script type="text/javascript">
function iecompattest(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
{
  var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
  var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
  
  opWidth = iecompattest().clientWidth - 5;
  opHeight = iecompattest().clientHeight - 45;
  divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
}

function OpenDHTMLDetil(opAddress, opCaption, opWidth, opHeight)
{
  var left = (screen.width/2)-(opWidth/2);
  var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
  divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
}

function tess()
{
  //$("iframe#FrameFIP")[1].contentWindow.tess();
  alert('index');
}
</script>

<script type="text/javascript" src="../niceedit/nicedit.js"></script>
<script type="text/javascript">
  function resetpenilaiancbi(x,y){
   $('#reqRadio'+x+'-'+y+'-1').attr('checked', false);
   $('#reqRadio'+x+'-'+y+'-2').attr('checked', false);
   $('#reqRadio'+x+'-'+y+'-3').attr('checked', false);
   $('#reqRadio'+x+'-'+y+'-4').attr('checked', false);
   $('#reqRadio'+x+'-'+y+'-5').attr('checked', false);
   $('#reqDetilAtributNilai'+x+'-'+y).val('');
   $('#reqDetilAtributGap'+x+'-'+y).val('');
    // return false;
  } 

  function resetpenilaianna(x,y){
   $('#reqPenilaianKompetensiRadio'+x+'-'+y+'-1').attr('checked', false);
   $('#reqPenilaianKompetensiRadio'+x+'-'+y+'-2').attr('checked', false);
   $('#reqPenilaianKompetensiRadio'+x+'-'+y+'-3').attr('checked', false);
   $('#reqPenilaianKompetensiRadio'+x+'-'+y+'-4').attr('checked', false);
   $('#reqPenilaianKompetensiRadio'+x+'-'+y+'-5').attr('checked', false);
   $('#reqPenilaianKompetensiNilai'+x+'-'+y).val('');
       // return false;
  } 

  function resetpenilaianpsikotes(x,y){
   $('#reqPenilaianRadio'+x+'-'+y+'-1').attr('checked', false);
   $('#reqPenilaianRadio'+x+'-'+y+'-2').attr('checked', false);
   $('#reqPenilaianRadio'+x+'-'+y+'-3').attr('checked', false);
   $('#reqPenilaianRadio'+x+'-'+y+'-4').attr('checked', false);
   $('#reqPenilaianRadio'+x+'-'+y+'-5').attr('checked', false);
    // return false;
  }

  function autologin(){
    $('#infoujian2').firstVisitPopup({
      cookieName : 'homepage',
      showAgainSelector: '#show-message'
    });
     reloadcaptchadinamis('captchaImage', '../WEB/functions/CaptchaSecurityImages.php');
  }

  $(function(){
    $('#ffLogin1').form({
      url:'../json-asesor/relog_json.php',
      onSubmit:function(){
        return $(this).form('validate');
                // console.log('masuk');
                // return false ;
              },
              success:function(data){
                // console.log(data);return false;
                if(data == "success"){
                  $.messager.alert('Info', 'Session Telah Diperbarui, Tekan Tombol X Dibagian Kanan Atas dan Klik Simpan Untuk Melanjutkan Menyimpan Data', 'info');
                  return false;
                }
                else
                {
                  $.messager.alert('Info', 'Username / Password Salah. Silahkan Login Kembali', 'info');
                  reloadcaptchadinamis('captchaImage', '../WEB/functions/CaptchaSecurityImages.php');

                  return false;

                }
              }
            }); 
  });

  function reloadcaptchadinamis(value, json)
    {
      $('#'+value).attr('src', json+'?random=' + (new Date).getTime()+'width=100&amp;height=40&amp;characters=5');
    }
</script>
<script src="../WEB/lib/first-visit-popup-master/jquery.firstVisitPopup.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/css/gayainfo2.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/rekrutmen.css">


<div class="my-welcome-message" id="infoujian2"  style="">
    <div class="konten-welcome">
    <div class="row" style="height:100%;">
         <div class="login-area">
            <div class="foto" style="text-align: center"><i class="fa fa-user fa-4x"></i></div>
            <form id="ffLogin1" method="post" novalidate enctype="multipart/form-data">
                <center><br><b>SESSION HABIS</b><br>
                Silahkan Login Kembali<center>
            <div class="form">
             <fieldset> 
                <div class="form-group">
                  <input type="text" name="reqUser" id="reqUser" class="form-control input-lg" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" name="reqPasswd" id="reqPasswd" class="form-control input-lg" placeholder="Password">
                </div> 
                <div class="form-group">
                  <img src="../WEB/functions/CaptchaSecurityImages.php?width=100&amp;height=40&amp;characters=5" id="captchaImage" />&nbsp;&nbsp;&nbsp;<img src="../WEB/functions/refresh.png" 
                  onclick="reloadcaptchadinamis('captchaImage', '../WEB/functions/CaptchaSecurityImages.php')" style="cursor:pointer" title="refresh captcha">
                  <input name="reqSecurity" id="reqSecurityDaftar" class="form-control input-lg" type="text" placeholder="Ketik Captcha" />
                </div>
                <div class="row">
                  <div class="col-xs-6 col-sm-6 col-md-4">
                  </div>
                  <div class="col-xs-6 col-sm-6 col-md-4">
                    <input name="slogin_POST_send" type="submit" class="btn btn-lg btn-success btn-block" value="Login" alt="DO LOGIN!" >
                    <input type="hidden" name="reqMode" value="submitLogin">
                  </div>
<!--                   <div class="col-xs-6 col-sm-6 col-md-6">
                    <input type="reset" class="btn btn-lg btn-warning btn-block" value="Reset"> 
                  </div> -->
                </div>
            </fieldset>
             <?=$csrf->echoInputField();?>

            </div>
            </form>
        </div>
    </div>
    </div>
</div>
</body>
</html>

<script type="text/javascript">

</script>
