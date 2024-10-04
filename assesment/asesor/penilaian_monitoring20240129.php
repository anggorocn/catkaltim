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
include_once("../WEB/functions/crfs_protect.php");
$csrf = new crfs_protect('_crfs_login');
$reqMode=httpFilterGet("reqMode");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
  $userLogin->retrieveUserInfo();  
}
$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$urlLink= $data->urlConfig->main->urlLink;

ini_set('memory_limit', -1);
ini_set('max_execution_time', -1);

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$reqEror=httpFilterGet("reqEror");
if($reqEror==1){
  flush();
  ob_flush();
}

$tempAsesorId= $userLogin->userAsesorId;
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqSelectPenggalianId= httpFilterGet("reqSelectPenggalianId");
$reqTab= httpFilterGet("reqTab");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqTanggalTes= httpFilterGet("reqTanggalTes");

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
$set->selectByParamsMonitoringTableTalentPoolMonitoring(array(), -1, -1, $statement1, $statement2, "", $reqTahun, "");
// echo $set->query;exit;
$set->firstRow();
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
$tempFileRiwayatCheck= $checkdokumen->getField("LINK_FILE1");
$tempFileKompetensiCheck= $checkdokumen->getField("LINK_FILE2");
$tempFileCriticalCheck= $checkdokumen->getField("LINK_FILE3");
$tempFileRiwayat= str_replace("../uploads", "../../uploads", $tempFileRiwayatCheck);
$tempFileKompetensi= str_replace("../uploads", "../../uploads", $tempFileKompetensiCheck);
$tempFileCritical= str_replace("../uploads", "../../uploads", $tempFileCriticalCheck);

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
// echo $set->query;exit;

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
$sOrder='ORDER BY UR.URUT , atribut_id asc';
$set->selectByParamsMonitoring(array(), -1,-1, $statement,$sOrder);
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

$index_catatan= 0;
$arrUraianKompetensi=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'uraian_kompetensi' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrUraianKompetensi[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahUraianKompetensi= $index_catatan;

$index_catatan= 0;
$arrUraianPotensi=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'uraian_potensi' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrUraianPotensi[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahUraianPotensi= $index_catatan;

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
  $arrAsesorPenilaianKompetensi[$index_loop]["PENGGALIAN_ASESOR_ID"]= $set->getField("ATRIBUT_ID")."-".$set->getField("ASESOR_ID");
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

  <script>
    function reloaderor(){
      location.href = "<?=$actual_link?>&reqEror=1";
      // console.log('xxx');
    }

      function kembali(){
      location.href = "<?=$urlLink?>asesor/index.php?reqTanggalTes=<?=$reqTanggalTes?>&reqMode=<?=$reqMode?>";
      // console.log('<?=$urlLink?>');
    }

    function openPopup() {
      // Display a ajax modal, with a title
      eModal.ajax('konten.html', 'Judul Popup')
    }

    function cetak(url)
    {
      newWindow = window.open('../ikk/'+url+'.php?reqId=<?=$reqPegawaiId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$tahunjadwaltes?>');
      newWindow.focus();          
    }

     function cetakKhusus(url)
    {
      newWindow = window.open('../ikk/'+url+'&reqId=<?=$reqPegawaiId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$tahunjadwaltes?>');
      newWindow.focus();          
    }

    function cetakRekap(url)
    {
      newWindow = window.OpenDHTMLDetil('../ikk/infografik.php?reqInfoLink=Ringkasan Laporan Individu&reqLink=cetak_ringkasan_pdf&reqId=<?=$reqPegawaiId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$tahunjadwaltes?>', '<?=$reqJadwalPegawaiNip?> - <?=$reqJadwalPegawaiNama?>', '880', '495');
      newWindow.focus();          
    }

     function CetakDataPeserta(pegawaiId,mode)
    {

      var IdPegawai = pegawaiId;
      var Mode = mode;
      newWindow = window.open('../pengaturan/cetak_data_pribadi_pdf.php?reqPegawaiId='+IdPegawai+'&reqId=<?=$reqId?>&reqMode='+Mode, '<?=$reqJadwalPegawaiNip?> - <?=$reqJadwalPegawaiNama?>');
      newWindow.focus();          
    }
  </script>

  <!-- FLUSH FOOTER -->
  <style>

    .col-md-12{
      *padding-left:0px;
      *padding-right:0px;
    }
    html, body {
      height: 100%;
      margin-top: -19px;
    }

    .footer {
      position: relative;
      height: 50px;
      clear:both;
      padding-top:20px;
      text-align:center;
      margin-left: -15px;
      margin-right: -15px;  
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
    .rbtn ul{
      list-style-type:none;
    }
    .rbtn ul li{
      display:inherit;
      padding:5px;
      margin:-5px;
      -moz-border-radius: 4px; 
      -webkit-border-radius: 4px; 
      -khtml-border-radius: 4px; 
      border-radius: 4px; 
      text-align:left;
      font-family: 'Open SansRegular';
      font-size: 13px;
      letter-spacing: normal;
    }
    .over{
      background: #063a69;
    }

    .sebelumselected{
      background-color: #FFFFFF;
      border: 1px solid #dadada;
    }

    .sebelumselected:before{
      font-family:"FontAwesome";
      content:"\f096";
      color:#f8a406;
      font-size:18px;
    }

    .selected{
      background-color: #767676;
      border: 1px solid #767676;
      color: #FFFFFF;
    }
    .selected:before{
      font-family:"FontAwesome";
      content:"\f046";
      color:#f8a406;
      font-size:18px;
    }

    body {
      margin: 0;
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
      margin-bottom: -7px !important;
    }

    .ui-scroll-tabs-view .ui-tabs-nav.ui-corner-all.ui-helper-reset.ui-helper-clearfix.ui-widget-header {

    }

    .ui-scroll-tabs-view .ui-widget-header {
      border: none;
      background: transparent;
    }

    .ui-scroll-tabs-view li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab {
      border: none;
      background-color: transparent;
    }

    .ui-scroll-tabs-view li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active {

    }

    .ui-scroll-tabs-view li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab a {
      border-bottom: 6px solid rgba(0,0,0,0);
      color: rgba(0,0,0,0.4);
    }

    .ui-scroll-tabs-view li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab a:hover {
      color: #333333;
    }

    .ui-scroll-tabs-view li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab a center button {
      top: inherit !important;
      position: absolute;
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

    .ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active{
      
    }
    .ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab > a.ui-tabs-anchor{

    }
    .ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active > a.ui-tabs-anchor{
      border-bottom: 6px solid #f2d149;
      color: #333333
      
    }
  </style>

  <!-- SCROLLING TAB -->
  <link href="../WEB/lib/Scrolling/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <link href="../WEB/lib/Scrolling/jquery-ui.css" rel="stylesheet" type="text/css">

</head>

<body>

    <div id="wrap-utama">
        <div id="main" class="container-fluid clear-top">
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

            <div class="row row-konten">
                <div class="col-md-12">
                    <div class="container area-menu-app">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="breadcrumb"><a href="index.php"><i class="fa fa-home"></i> Home</a></div>
                                <div class="row profil-area" style="min-height:205px">
                                    <div class="col-md-3">
                                      <div class="profil-data">
                                        <div class="profil-foto">
                                            <img id="reqImagePeserta" />
                                        </div>
                                        <div class="nama-nip">
                                          <div class="nama"><? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?> </div>
                                          <div class="nip"><?=$reqJadwalPegawaiNip?></div>
                                        </div>
                                        <div class="inner">
                                          <div class="form-group">
                                            <label>Pangkat / Gol.Ruang:</label>
                                            <div><?=$data['pangkat']?></div>
                                          </div>
                                          <div class="form-group">
                                            <label>Jabatan:</label>
                                            <div>
                                              <?
                                              $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-jabatan/'.$reqJadwalPegawaiNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
                                              $dataApiJabatan = json_decode(file_get_contents($url), true);     
                                                if($dataApiJabatan[0]['jabatan']!='')
                                                {
                                                ?>
                                                  <?=$dataApiJabatan[0]['jabatan']?>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <?=$tempJabatan?>
                                                <?
                                                }
                                                ?>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label>Assesment:</label>
                                            <div><?=$reqKeterangan?></div>
                                          </div>
                                          <div class="form-group">
                                            <label>Tanggal:</label>
                                            <div><?=$TanggalTes?></div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-9">
                                      <div class="judul-halaman">Info Asessee</div>
                                      <div class="area-konten">
                                        <table class="profil">
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
                                            <td colspan="1">
                                              <a href="javascript: void(0)" onclick="cetakKhusus('cetak_psikogram_assesment_new_pdf.php?reqTipe=sederhana')" title="Cetak Sederhana"><img src="../WEB/images/down_icon.png"/>Sederhana</a>
                                              <a href="javascript: void(0)" onclick="cetakKhusus('cetak_psikogram_assesment_new_pdf.php?reqTipe=sedang')" title="Cetak Sedang"><img src="../WEB/images/down_icon.png"/> Sedang</a>
                                              <a href="javascript: void(0)" onclick="cetakKhusus('cetak_psikogram_assesment_new_pdf.php?reqTipe=kompleks')"title="Cetak Kompleks"><img src="../WEB/images/down_icon.png"/> Kompleks</a>
                                              <a href="javascript: void(0)" onclick="cetakKhusus('cetak_psikogram_assesment_new_pdf.php?reqTipe=baru')"title="Cetak Kompleks"><img src="../WEB/images/down_icon.png"/> Sederhana 2</a>
                                            </td>
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
                                            <td colspan="4"><a href="../silat/pegawai_edit.php?reqId=<?=$reqPegawaiId?>" title="Cetak" target="_blank">Detil Pegawai</a></td>
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
                                                          <a href="<?=$infolinkfile?>" title="<?=$infolabel?>" target="_blank"><?=$infolabel?></a><br> 
                                                <?
                                                        }
                                                        else
                                                        {
                                                ?>
                                                          <a href="javascript:void(0)" title="<?=$infolabel?>"><?=$infolabel?> : belum upload</a><br>
                                                <?
                                                        }
                                                    }
                                                    else
                                                    {
                                                ?>
                                                          <a href="javascript:void(0)" title="<?=$infolabel?>"><?=$infolabel?> : belum upload</a><br> 
                                                <?
                                                    }
                                                }
                                                ?>
                                            </td> 
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12 text-right">
                                          <br>
                                          <a class="btn btn-danger" onclick="reloaderor()">Klik Jika Error</a>
                                          <a class="btn btn-primary" onclick="kembali()">Kembali</a>
                                          <?if ($reqMode!=''){?>
                                            <a class="btn btn-primary" onclick="CetakKeseluruhan()">Cetak Keseluruhan</a>
                                          <?}?>
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="example_0">
                      <ul role="tablist" style="z-index: 100;">
                        <?
                        for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
                        {
                          $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
                          $reqInfoPenggalianAsesor= $arrAsesor[$index_loop]["NAMA_ASESOR"];
                          $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
                          if($reqInfoPenggalianKode=='CBI'){
                            $reqInfoPenggalianAsesorLast= $arrAsesor[$index_loop]["NAMA_ASESOR"];
                          }

                          $tabSelectCss= "";
                          if($reqInfoPenggalianId == $reqTab){
                            $tabSelectCss= "ui-tabs-active ui-state-active";
                          }
                        ?>
                        <li role="tab" class="<?=$tabSelectCss?>"><a href="#tabs-<?=$reqInfoPenggalianId?>" role="presentation" onclick="selecttabs('<?=$reqInfoPenggalianId?>')"><center><?=$reqInfoPenggalianKode?> <button onclick="downloadCetakan('<?=$reqInfoPenggalianKode?>')" style="background: url('../WEB/images/down_icon.png');height: 15px;width: 15px;border: none;top: 23px;position: absolute;margin-left: 5px;" ></button> <br><span style="font-size:10px">(<?=$reqInfoPenggalianAsesor?>)</span></center></a></li>
                        <?
                        }
                        $tempKondisiNilaiAkhir= 1;
                        if($tempKondisiNilaiAkhir == "1")
                        {
                          $tabSelectCss= "";
                          if("nilai_akhir" == $reqTab)
                          $tabSelectCss= "ui-tabs-active ui-state-active";
                        ?>
                        <li role="tab" class="<?=$tabSelectCss?>"><a href="#tabs-nilaiakhir" role="presentation" onclick="selecttabs('nilai_akhir')"><center>Nilai Akhir<br><span style="font-size:10px">(<?=$reqInfoPenggalianAsesorLast ?>)</span></center></a></li>
                        <?
                        }

                        $tabSelectCss= "";
                        if("kesimpulan" == $reqTab){
                          $tabSelectCss= "ui-tabs-active ui-state-active";
                        }?>
                         <li role="tab" class="<?=$tabSelectCss?>"><a href="#tabs-lain" role="presentation" onclick="selecttabs('kesimpulan')"><center>Kesimpulan<br><span style="font-size:10px">(<?=$reqInfoPenggalianAsesorLast?>)</span></center></a></li>
                       
                        <?
                        $tabSelectCss= "";
                        if("rekap_penilaian" == $reqTab){
                          $tabSelectCss= "ui-tabs-active ui-state-active";
                        }
                        ?>
                        <li role="tab" class="<?=$tabSelectCss?>"><a href="#tabs-rekap" role="presentation" onclick="selecttabs('rekap_penilaian')"><center>Rekap Penilaian <br><span style="font-size:10px">&nbsp;</span> <button onclick="downloadCetakan('Rekap')" style="background: url('../WEB/images/down_icon.png');height: 15px;width: 15px;border: none;" ></button> </span></center></a></li>
                      </ul>
                      <?
                      for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
                      {
                        $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
                        $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
                      ?>
                      <div class="ne-except area-penilaian-catatan" id="tabs-<?=$reqInfoPenggalianId?>" role="tabpanel">
                        <?
                        // set form kalau potensi
                        if($reqInfoPenggalianId == "0")
                        {
                        ?>
                        <div class="row">
                          <div class="col-md-12">

                            <div class="area-table-assesor">
                              <!-- <br> -->
                              <div class="judul-halaman">Penilaian Psikotes</div>
                              <form id="ff-<?=$reqInfoPenggalianId?>" method="post" novalidate>

                                <table style="margin-bottom:60px;" class="profil"> 
                                  <tbody>
                                    <?
                                    $arrayKey= in_array_column("1", "ASPEK_ID", $arrPenilaian);
                                    // print_r($arrayKey);

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
                                        $reqPenilaianPotensiGap= $arrPenilaian[$index_row]["GAP"];

                                        $reqPenilaianPotensiNilaiDecimal= explode(".", $reqPenilaianPotensiNilai);
                                        if(count($reqPenilaianPotensiNilaiDecimal) > 1){
                                          $reqPenilaianPotensiNilaiPokok=$reqPenilaianPotensiNilaiDecimal[0];
                                          $reqPenilaianPotensiNilaiDecimal= end($reqPenilaianPotensiNilaiDecimal);
                                        }
                                        else{
                                          $reqPenilaianPotensiNilaiDecimal= "";
                                          $reqPenilaianPotensiNilaiPokok= $reqPenilaianPotensiNilai;
                                        }

                                        $reqPenilaianPotensiCatatan= $arrPenilaian[$index_row]["CATATAN"];
                                        $reqPenilaianPotensiBukti= $arrPenilaian[$index_row]["BUKTI"];

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

                                        if($index_atribut_parent % 2 == 0){
                                          $css= "terang";
                                        }
                                        else{
                                          $css= "gelap";
                                        }

                                        if($reqPenilaianPotensiAtributIdParent == "0")
                                        {
                                        ?>
                                          <tr class="">
                                            <td style="text-align:center; width: 1%" rowspan="2">No</td>
                                            <td style="text-align:center;" rowspan="2">ATRIBUT & INDIKATOR</td>
                                            <td style="text-align:center; width: 10%" rowspan="2">Standar</td>
                                            <td style="text-align:center" colspan="7">Hasil Individu</td>
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
                                          if($reqPenilaianPotensiNilaiPokok == "" ){}
                                          else
                                          $arrChecked= radioPenilaian($reqPenilaianPotensiNilaiPokok);
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
                                              <input value="<?=$reqPenilaianPotensiNilaiDecimal?>" id ='reqPenilaianPotensiNilaiDecimal<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>'type="number" name="reqPenilaianPotensiNilaiDecimal[]" style="color:black;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');">
                                              <?}?>
                                            </td>
                                          </tr>

                                          
                                          <tr>
                                            <td colspan="9" style="color:black !important ;background-color: white !important;">
                                              <?
                                              if($disabledatribut == "")
                                              {
                                              ?>
                                              <center>
                                                <!-- <a href="javascript: void(0)" class="btn btn-primary btn-sm" onclick="resetpenilaianpsikotes(<?=$reqPenilaianPotensiAspekId?>,<?=$index_detil?>)" title="Cetak">Reset Penilaian</a> -->
                                                <input name="submit1" type="submit" class="btn btn-primary btn-sm" style="width:100%" value="Simpan" />
                                                <br>
                                              </center>
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
                                      }
                                    }
                                    ?>
                                    <tr>
                                      <th colspan="10">
                                        Data Tambahan Untuk Profil Kompetensi
                                      </th>
                                    </tr>
                                    <tr>
                                      <td colspan="10">
                                        <table style="margin-bottom:60px;" class="profil">
                                          <thead>
                                            <td width="3%">No</td>
                                            <td width="20%">Kompetensi</td>
                                            <td width="5%">Rating</td>
                                            <td>Keterangan</td>
                                          </thead>
                                          <tbody id="tbDataLoop">
                                            <?
                                            $arrayKey= in_array_column("2", "ASPEK_ID", $arrPenilaian);
                                            
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
                                                  $setPenilaianKompetensi->selectByParamsDasar(array(), -1,-1, "and pegawai_id = ".$reqPegawaiId." and jadwal_tes_id = ".$reqJadwalTesId." and penilaian_kompetensi_dasar_id = ".$reqPenilaianKompetensiAtributId);
                                                  // echo $setPenilaianKompetensi->query; exit;
                                                  $setPenilaianKompetensi->firstRow();
                                                  if($kondisilihatatribut==1){
                                                    ?>
                                                    <tr>
                                                      <td><?=$index_atribut_parent?></td>
                                                      <td><?=$reqPenilaianKompetensiAtributNama?></td>
                                                      <td><?=$setPenilaianKompetensi->getField("penilaian")?></td>
                                                      <td><?=$setPenilaianKompetensi->getField("keterangan")?></td>
                                                    </tr>
                                                    <?
                                                  }
                                                  else{
                                                ?>
                                                  <tr>
                                                  <input type="hidden" name="reqKompetensiPenilaianId[]" value="<?=$setPenilaianKompetensi->getField("penilaian_kompetensi_penilaian_id")?>">
                                                  <input type="hidden" name="reqKompetensiDasarId[]" value="<?=$reqPenilaianKompetensiAtributId?>">
                                                  <td><?=$index_atribut_parent?></td>
                                                  <td><?=$reqPenilaianKompetensiAtributNama?></td>
                                                  <td>
                                                    <select style="color: black;" name="reqKompetensiNilai[]">
                                                      <option <?if($setPenilaianKompetensi->getField("penilaian")=='K-'){ echo "selected='selected'" ;}?>>K-</option>
                                                      <option <?if($setPenilaianKompetensi->getField("penilaian")=='K'){ echo "selected='selected'" ;}?>>K</option>
                                                      <option <?if($setPenilaianKompetensi->getField("penilaian")=='K+'){ echo "selected='selected'" ;}?>>K+</option>
                                                      <option <?if($setPenilaianKompetensi->getField("penilaian")=='C-'){ echo "selected='selected'" ;}?>>C-</option>
                                                      <option <?if($setPenilaianKompetensi->getField("penilaian")=='C'){ echo "selected='selected'" ;}?>>C</option>
                                                      <option <?if($setPenilaianKompetensi->getField("penilaian")=='C+'){ echo "selected='selected'" ;}?>>C+</option>
                                                      <option <?if($setPenilaianKompetensi->getField("penilaian")=='B'){ echo "selected='selected'" ;}?>>B</option>
                                                    </select>
                                                  </td>
                                                  <td>
                                                    <textarea name="reqKompetensiKet[]" ><?=$setPenilaianKompetensi->getField("keterangan")?></textarea>
                                                  </td>
                                                </tr>
                                                <?
                                                }
                                                $index_atribut_parent++;
                                                }
                                              }
                                            }
                                            ?>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>

                                    <tr>
                                      <th colspan="10">Dinamika Psikologis</th>
                                    </tr>
                                    <tr>
                                      <?
                                        $setkepribadian= new PenilaianRekomendasi();
                                        $setkepribadian->selectByParams(array('PEGAWAI_ID'=>$reqPegawaiId, 'JADWAL_TES_ID'=>$reqJadwalTesId, 'tipe'=>'profil_kepribadian'));
                                        // echo $setkepribadian->query; exit;
                                          $setkepribadian->firstRow();
                                        $reqPenilaianKeteranganKepribadian= $setkepribadian->getField("KETERANGAN");
                                        if($kondisilihatatribut==1){
                                          ?>
                                          <td colspan="10"> <?=$reqPenilaianKeteranganKepribadian?></td>
                                        <?
                                        }
                                          else{
                                      ?>
                                      <td colspan="10">  <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqPenilaianKeteranganKepribadian"><?=$reqPenilaianKeteranganKepribadian?></textarea>
                                          <input type="hidden" style="color:#000 !important" name="reqPenilaianKperibadianPegawaiId" value="<?=$reqPegawaiId?>" />
                                          <input type="hidden" style="color:#000 !important" name="reqPenilaianKperibadianjadwalId" value="<?=$reqJadwalTesId?>" />
                                        </td>
                                        <?}?>
                                    </tr>

                                    <tr>
                                      <th colspan="10">
                                        Uraian Potensi
                                        <?
                                        $modeUraianKompetensi= "UraianPotensi";
                                        if($kondisilihatatribut != 1)
                                        {
                                        ?>
                                        <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$modeUraianKompetensi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                        <?
                                        }
                                        ?>
                                      </th>
                                    </tr>
                                    <tr>
                                      <td colspan="10">
                                      <?
                                      if($kondisilihatatribut != 1)
                                      {
                                      ?>
                                      <div id ="<?=$modeUraianKompetensi?>">
                                        <fieldset>
                                        <?
                                        for($index_catatan=0; $index_catatan<$jumlahUraianPotensi; $index_catatan++)
                                        {
                                          $reqinfocatatan= $arrUraianPotensi[$index_catatan]["KETERANGAN"];
                                        ?>
                                          <p>
                                            <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqUraianPotensi[]"><?=$reqinfocatatan?></textarea>
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
                                          <p>
                                            <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqUraianPotensi[]"></textarea>
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
                                        <?
                                        for($index_catatan=0; $index_catatan<$jumlahUraianPotensi; $index_catatan++)
                                        {
                                        $reqinfocatatan= $arrUraianPotensi[$index_catatan]["KETERANGAN"];
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
                                    // button muncul apabila asesor yg berwenang
                                    if($reqPenilaianPotensiDataAsesorId == $tempAsesorId)
                                    {
                                    ?>
                                    <tr>
                                      <td colspan="11" align="center">
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
                        <?
                        }
                        // set form kalau bukan potensi
                        else
                        {
                        ?>
                        <div class="row">
                          <div class="col-md-12">

                            <div class="area-table-assesor">
                              <!-- <br> -->
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
                                      // print_r($arrayKey);

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
                                          // echo "xx".$reqDetilAtributCatatan;
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
                                          else{
                                            $cssIndikator= "selected";
                                          }

                                          // $cssIndikator= "sebelumselected";
                                          if($reqJadwalPegawaiAtributIdLookUp == $reqJadwalPegawaiAtributId)
                                          {
                                            $indexTr++;
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
                                                     <center>
                                                      <input name="submit1" type="submit" class="btn btn-primary btn-sm" style="width:100%" value="Simpan" />
                                                     <br>
                                                   </center>
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
                                                    <?
                                                    // biar ga eror
                                                    echo " ";
                                                    ?>
                                                  </tr>
                                                </table>
                                              </td>
                                            </tr>
                                            <?
                                          }
                                          $reqJadwalPegawaiAtributIdLookUp= $reqJadwalPegawaiAtributId;
                                        }
                                      }
                  
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
                      <div id="tabs-nilaiakhir" role="tabpanel" class="area-penilaian-catatan">
                        <!-- start of -->
                        <div class="row">
                          <div class="col-md-12">
                            <div class="area-table-assesor">
                              <!-- <br> -->
                              <div class="judul-halaman">Penilaian Kompetensi</div>
                              <form id="ff-" method="post" novalidate>
                                <table style="margin-bottom:60px;" class="profil">
                                  <thead>
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
                                        if($reqPenilaianKompetensiGap == "" || $reqPenilaianKompetensiGap == "0"){
                                          $reqPenilaianKompetensiGap= 0;
                                        }
                                        else{
                                          $reqPenilaianKompetensiGap= $reqPenilaianKompetensiNilaiAsli-$reqPenilaianKompetensiNilaiStandar;
                                        }

                                        $reqPenilaianKompetensiCatatan= $arrPenilaian[$index_row]["BUKTI"];
                                        $reqPenilaianKompetensiBukti= $arrPenilaian[$index_row]["CATATAN"];

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
                                        if($index_atribut_parent % 2 == 0){
                                          $css= "terang";
                                        }
                                        else{
                                          $css= "gelap";
                                        }

                                        if($reqPenilaianKompetensiAtributIdParent == "0")
                                        {
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
                                            <td style="text-align:center; width: 5%" rowspan="2">Standar Rating</td>
                                            <td style="text-align:center" colspan="7">Hasil Individu</td>
                                            <td style="text-align:center; width: 5%" rowspan="2">Gap</td>
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
                                              $tempInfoDataPenggalianAtributNilai= "-";
                                              if($arrayDetilKey == ''){}
                                              else
                                              {
                                                $index_detil_row= $arrayDetilKey[0];
                                                $tempInfoDataPenggalianAtributNilai= $arrPegawaiPenilaian[$index_detil_row]["NILAI"];
                                              }

                                              $tempCariDataDetilNilai= $reqPenilaianKompetensiAtributId."-".$tempAsesorId;
                                              $arrayDetilKey= "";
                                              $arrayDetilKey= in_array_column($tempCariDataDetilNilai, "PENGGALIAN_ASESOR_ID", $arrAsesorPenilaianKompetensi); ?>
                                              <span style="display:none;">asdasdasd</span><? 
                                              if($arrayDetilKey == ''){
                                              }
                                              else
                                              {
                                                $index_detil_row= $arrayDetilKey[0];
                                                $reqInfoDataPenggalianAsesorId= $arrAsesorPenilaianKompetensi[$index_detil_row]["ASESOR_ID"];
                                                // kalau data asesor kosong maka set untuk validasi entri
                                                if($reqInfoDataPenggalianAsesorDataId == "")
                                                {                                                  
                                                  $reqInfoDataPenggalianAsesorDataId= $reqInfoDataPenggalianAsesorId;
                                                }
                                              }
                                            }
                                            // exit();
                                           
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
                                              <center>
                                                <input name="submit1" type="submit" class="btn btn-primary btn-sm" style="width:100%" value="Simpan" />
                                                <br>
                                              </center>
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
                                                <legend>Deskripsi</legend>
                                                <textarea name="reqPenilaianKompetensiBukti[]" id="reqPenilaianKompetensiBukti<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" style="width:95%;" rows="1" ><?=$reqPenilaianKompetensiBukti?></textarea>
                                              </fieldset>
                                              <span style="<?=$munculsarancss?>" id="reqPenilaianKompetensiSaran<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>">
                                                <fieldset>
                                                  <legend>Saran Pengembangan</legend>
                                                  <textarea name="reqPenilaianKompetensiCatatan[]" id="reqPenilaianKompetensiCatatan<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" style="width:95%" rows="1" ><?=$reqPenilaianKompetensiCatatan?></textarea>
                                                </fieldset>
                                              </span>
                                                <input name="submit1" type="submit" class="btn btn-primary btn-sm" style="width:100%" value="Simpan" />
                                              <?
                                              }
                                              else
                                              {
                                              ?>
                                              <fieldset style="border: 1px solid; padding: 10px !important">
                                                <legend>Deskripsi</legend>
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
                                      }
                                    }

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
                                    <tr>
                                      <th colspan="<?=$tempsarancolspan+1?>">
                                        Uraian Kompetesi
                                        <?
                                        $modeUraianKompetensi= "UraianKompetensi";
                                        if($tempsimpankompetensi == "1")
                                        {
                                        ?>
                                        <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$modeUraianKompetensi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                        <?
                                        }
                                        ?>
                                      </th>
                                    </tr>
                                    <tr>
                                      <td colspan="<?=$tempsarancolspan+1?>">
                                      <?
                                      if($tempsimpankompetensi == "1")
                                      {
                                      ?>
                                      <div id ="<?=$modeUraianKompetensi?>">
                                        <fieldset>
                                        <?
                                        for($index_catatan=0; $index_catatan<$jumlahUraianKompetensi; $index_catatan++)
                                        {
                                          $reqinfocatatan= $arrUraianKompetensi[$index_catatan]["KETERANGAN"];
                                        ?>
                                          <p>
                                            <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqUraianKompetensi[]"><?=$reqinfocatatan?></textarea>
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
                                          <p>
                                            <textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="reqUraianKompetensi[]"></textarea>
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
                                        <?
                                        for($index_catatan=0; $index_catatan<$jumlahUraianKompetensi; $index_catatan++)
                                        {
                                        $reqinfocatatan= $arrUraianKompetensi[$index_catatan]["KETERANGAN"];
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
                      </div>

                      <div id="tabs-lain" role="tabpanel" class="area-penilaian-catatan">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="area-table-assesor">
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
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPotensiStrength[$index_catatan]["KETERANGAN"];
                                            ?>
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

                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiWeaknes";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Kelemahan
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiWeaknes; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiWeaknes[$index_catatan]["KETERANGAN"];
                                            ?>
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
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiKesimpulan; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiKesimpulan[$index_catatan]["KETERANGAN"];
                                            ?>
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

                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiSaranPengembangan";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Saran Pengembangan
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPengembangan; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"];
                                            ?>
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
                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiSaranPenempatan";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Saran Penempatan
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPenempatan; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiSaranPenempatan[$index_catatan]["KETERANGAN"];
                                            ?>
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
                                        }

                                        if($disabledatribut == "")
                                        {
                                          $moderekomendasi= "PenilaianPotensiProfilKompetensi";
                                        ?>
                                        <div id ="<?=$moderekomendasi?>">
                                          <fieldset>
                                            <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; ">Ringkasan Profil Kompetensi
                                              <a style="cursor:pointer; display: inline !important" title="Tambah" onClick="createRow('<?=$moderekomendasi?>')"><img src="../WEB/images/icn_add.gif" /></a>
                                            </legend>
                                            <?
                                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiProfilKompetensi; $index_catatan++)
                                            {
                                              $reqinfocatatan= $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"];
                                            ?>
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

                      <div id="tabs-rekap" role="tabpanel" class="area-penilaian-catatan">
                        <div class="row">
                          <div class="col-md-12">

                            <div class="area-table-assesor">
                              <div class="judul-halaman">Rekap</div>
                                <table style="margin-bottom:60px;" class="profil tabel-rekap">
                                  <tr>
                                    <td style="background-color: #f8a406 !important;text-align: center;width: 5%;">No</td>
                                    <td style="background-color: #f8a406 !important;text-align: center;width: 30%;">Kompetensi</td>
                                    <?
                                    // print_r($arrAsesor); exit;
                                    for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
                                    {
                                      $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
                                      $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
                                      if($reqInfoPenggalianKode!='Psikotes'){
                                    ?>
                                      <td style="background-color: #f8a406 !important;text-align: center;"><?=$reqInfoPenggalianKode?></td>
                                    <?
                                      }
                                    }
                                    ?>
                                    <td style="background-color: #f8a406 !important;text-align: center;width: 30%;">Kesimpulan</td>
                                  </tr>
                                 <?
                                
                                  $arrayKey= in_array_column($arrAsesor[0]["PENGGALIAN_ID"], "PENGGALIAN_ID", $arrPegawaiNilai);
                                  // print_r($arrPegawaiNilai); exit;
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
                                      <td style="background-color: #f8a406 !important;text-align: center;"><?=$no?></td>
                                      <td><?=$reqJadwalPegawaiAtributNama?></td>
                                    <?
                                      for($index_loop=0; $index_loop < count($arrAsesor); $index_loop++)
                                      {
                                        $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
                                        if($arrAsesor[$index_loop]['PENGGALIAN_NAMA']!='Psikotes'){
                                      ?>
                                        <td><center><?=$reqDetilAtributNilaiRekap[$arrAsesor[$index_loop]['PENGGALIAN_ID']][$index_detil]?></center></td>
                                      <?
                                        }
                                      }
                                      if($arrPenilaian[$arrayKeysss[$no]]["PENILAIAN_DETIL_ID"]==''){?>
                                        <td><?=$arrPenilaian[$arrayKeysss[$no+1]]["NILAI"];?></td>
                                      <?}
                                      else{
                                      ?>
                                        <td><?=$arrPenilaian[$arrayKeysss[$no]]["NILAI"];?></td>
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
                              <div class="judul-halaman">Data Tes Psikologi</div>
                                <table style="margin-bottom:60px;" class="profil tabel-rekap">
                                  <tr>
                                    <td style="background-color: #f8a406 !important;text-align: center;width: 5%;">No</td>
                                    <td style="background-color: #f8a406 !important;text-align: center;width: 40%;">Aspek Potensi</td>
                                    <td style="background-color: #f8a406 !important;text-align: center;width: 20%;">Rating</td>
                                    <td style="background-color: #f8a406 !important;text-align: center;">Keterangan</td>
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
                                        // echo $setPenilaianKompetensi->query;exit;
                                        $setPenilaianKompetensi->firstRow();
                                      ?>
                                      <tr>
                                        <td><?=$index_atribut_parent?></td>
                                        <td><?=$reqPenilaianKompetensiAtributNama?></td>
                                        <td><?=$setPenilaianKompetensi->getField("nilai")?></td>
                                        <td><?=$setPenilaianKompetensi->getField("catatan")?></td>
                                      </tr>
                                      <?
                                      $index_atribut_parent++;
                                      }
                                      else{?>
                                      <tr>
                                        <td colspan="4"><?=$reqPenilaianKompetensiAtributNama?></td>                         
                                      </tr>
                                      <?
                                      $index_atribut_parent= 1;
                                      }
                                    }
                                  }
                                  ?>
                                </table>
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
</div>
<footer class="footer">
  © 2021 Pemprov Kaltim. All Rights Reserved. 
</footer>

<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 

<!-- SCROLLING TAB -->
<script src="../WEB/lib/Scrolling/jquery-1.12.4.min.js"></script>
<script src="../WEB/lib/Scrolling/jquery-ui.min.js"></script>
<script src="../WEB/lib/Scrolling/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquery.ui.scrolltabs.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyuiasesor.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>
<script type="text/javascript" src="../niceedit/nicedit.js"></script>

<script>
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
        },  
        message: 'Please choose option for {1}.'  
      },
      justText: {  
       validator: function(value, param){
         return true;
       },  
       message: 'Please enter only text.'  
      }
    });
    <?

    for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
    {
      $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
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
          data = data.split("-");
          rowid= data[0];
          infodata= data[1];
          if(rowid == "xxx"){}
          else if(rowid == "autologin"){
              autologin(); return false ;
          }
          else
          {
            cektabs= $("#valtabsave").val()
            vurl= "penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-nilaiakhir&reqTanggalTes=<?=$reqTanggalTes?>&reqTab="+cektabs;
             setrekapnilai(vurl);
          }
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
          // console.log(<?=$tempurl?>+'-'+temp);
          // return false;
          if($(this).form('validate') == false)
          {
            $.messager.alert('Info', "Isi terlebih dahulu, atribut dan catatan", 'info');
            return false;
          }

          return $(this).form('validate');
        },
        success:function(data){
          data = data.split("-");
          rowid= data[0];
          infodata= data[1];
          if(rowid == "xxx"){}
          else if(rowid == "autologin"){
              autologin(); return false ;
          }
          else
          {
            cektabs= $("#valtabsave").val()
            vurl= "penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-nilaiakhir&reqTanggalTes=<?=$reqTanggalTes?>&reqTab="+cektabs;

            setrekapnilai(vurl);
          }
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
        data = data.split("-");
        rowid= data[0];
        infodata= data[1];
        if(rowid == "xxx"){}
        else if(rowid == "autologin"){
            autologin(); return false ;
        }
        else
        {
          cektabs= $("#valtabsave").val()
          vurl= "penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-nilaiakhir&reqTanggalTes=<?=$reqTanggalTes?>&reqTab="+cektabs;
          setrekapnilai(vurl);
        }
      }
    });

    $('#ff-simpanProfilKompetnsi').form({
      url:'../json-asesor/profil_kompetensi.php',
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
                   // document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-kompetensi&reqTanggalTes=<?=$reqTanggalTes?>';
                cektabs= $("#valtabsave").val()
                  vurl= "penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-nilaiakhir&reqTanggalTes=<?=$reqTanggalTes?>&reqTab="+cektabs;

                  setrekapnilai(vurl);
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

    function roundToTwo(value, decimals) {
      if(typeof decimals == "undefined" || infovalnilai == "")
      {
        decimals= 2;
      }

      return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
    }

    function pembulatandecimal(value, desimal)
    {
      vreturn= value;
      if(parseFloat(desimal) > 0)
      {
        vreturn= parseInt(value)+"."+desimal;
      }
      else
      {
        vreturn= parseInt(vreturn);
      }
      return vreturn;
    }

    $('input[id^="reqPenilaianPotensiNilaiDecimal"]').keyup(function(e) {
      var tempId= $(this).attr('id');
      var tempValId= $(this).val();
      arrId= tempId.split('reqPenilaianPotensiNilaiDecimal');
      arrId= arrId[1].split('-');
      tempAspekId= arrId[0];
      reqIndekId= arrId[1];

      rowid= tempAspekId+"-"+reqIndekId;

      nilaiPotensiBulat= $("#reqPenilaianPotensiNilai"+rowid).val();
      nilaiPotensiDecimal= tempValId;
      nilaiPotensi= pembulatandecimal(nilaiPotensiBulat, nilaiPotensiDecimal);
      $("#reqPenilaianPotensiNilai"+rowid).val(parseFloat(nilaiPotensi));

      var gap= roundToTwo(parseFloat(nilaiPotensi) - parseFloat($("#reqPenilaianPotensiNilaiStandar"+rowid).val()));
      $("#reqPenilaianPotensiGap"+rowid).val(gap);
      $("#reqPenilaianPotensiGapInfo"+rowid).text(gap);
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
      nilaiPotensiBulat= parseInt(tempValId);
      nilaiPotensiDecimal=$("#reqPenilaianPotensiNilaiDecimal"+rowid).val();
      nilaiPotensi= pembulatandecimal(nilaiPotensiBulat, nilaiPotensiDecimal);
      $("#reqPenilaianPotensiNilai"+rowid).val(parseFloat(nilaiPotensi));
      // nilaiPotensi= nilaiPotensiBulat+"."+nilaiPotensiDecimal;

      var gap= roundToTwo(parseFloat(nilaiPotensi) - parseFloat($("#reqPenilaianPotensiNilaiStandar"+rowid).val()));
      $("#reqPenilaianPotensiGap"+rowid).val(gap);
      $("#reqPenilaianPotensiGapInfo"+rowid).text(gap);
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
      tempValId= $("input[name='reqPenilaianKompetensiRadio"+rowid+"']:checked").val();

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
    infodata= '<textarea rows="1" style="margin: 4px auto; width:100%; color:#06345f;" name="req'+mode+'[]"></textarea>';
    var elm = $(infodata).appendTo(scntDiv); // Add the textarea to DOM
    var curSize = $('textarea[name="req'+mode+'[]"]').length; //Get the current SIZE of textArea
    editors[curSize] = new nicEditor().panelInstance(elm[0]); //Set the Object with the index as key and reference for removel
   elm.after($('<a/>', { //Create anchor Tag with rel attribute as that of the index of corresponding editor
       rel: curSize,
           'class': "remScnt",
       text: "Remove",
       href: '#'
   })).next().andSelf().wrapAll($('<p/>'));
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
       $(this).closest('p').remove(); //remove the element.     
  });

  function setModal(target, link_url)
  {
     var s_url= link_url;
     $.ajax({'url': s_url,'success': function(msg)
     {
      if(msg == ''){}
        else
        {
         $('#'+target).html(msg);
       }
     }});
  }

  var $tabs;
  var scrollEnabled;

  $(function () {
    $('#example_0').scrollTabs({
      scrollOptions: {
        selectTabAfterScroll: false,
        closable: false,
      }
    });

    bkLib.onDomLoaded(function() {
      nicEditors.allTextAreas();
      $('.nicEdit-panelContain').parent().width('100%');
      $('.nicEdit-panelContain').parent().next().width('98%');
      $('.nicEdit-main').width('100%');
    });
  });


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
    alert('index');
  }

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

  function setrekapnilai(vurl)
  {
    ajaxurl= "../json-asesor/penilaian_penggalian_pegawai.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>";
    $.ajax({
      cache: false,
      url: ajaxurl,
      processData: false,
      contentType: false,
      type: 'GET',
      dataType: 'json',
      success: function (response) {
      // console.log(vurl); return false;
      if(vurl == ""){}
        else
        {
          document.location.href = vurl;
        }
      },
      error: function(xhr, status, error) {
      },
      complete: function () {
      }
    });
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
              <center><br><b>SESSION HABIS</b><br>Silahkan Login Kembali<center>
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
  function downloadCetakan(val){
    window.open('cetak_penilaian.php?reqTipe='+val+'&reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqTanggalTes=<?=$reqTanggalTes?>', '_blank');
  }

  function CetakKeseluruhan(pegawaiId,mode)
  {
    newWindow = window.open('../ikk/cetak_admin_acara.php?reqPegawaiId=<?=$reqPegawaiId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTanggalTes=<?=$reqTanggalTes?>', 'Cetak');
    newWindow.focus();
  }

  function selecttabs(valll){
    $("#valtabsave").val(valll);
  }
</script>

<input type="hidden" id="valtabsave" value="<?=$reqTab?>">
