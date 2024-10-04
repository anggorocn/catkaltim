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
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$reqEror=httpFilterGet("reqEror");

$tempAsesorId= $userLogin->userAsesorId;
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqSelectPenggalianId= httpFilterGet("reqSelectPenggalianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqTanggalTes= httpFilterGet("reqTanggalTes");
$reqTipe= httpFilterGet("reqTipe");
if($reqTipe=='Psikotes'){
	$reqTipe='PT';
}


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

//hasil cat

//view asesment
$set= new CetakanPdf();
$statement1= " AND A.PEGAWAI_ID= ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$statement2= " AND B.PEGAWAI_ID= ".$reqPegawaiId;
$set->selectByParamsMonitoringTableTalentPoolMonitoring(array(), -1, -1, $statement1, $statement2, "", $reqTahun, "");
$set->firstRow();
$namaKuadran= $set->getField("NAMA_KUADRAN");
$kodeKuadran= $set->getField("KODE_KUADRAN");
$rekomKuadran= $set->getField("NAMA_KUADRAN");

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

$index_loop= 0;
$arrAsesor="";
$statementcount= $statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId."
AND EXISTS (SELECT 1 FROM jadwal_pegawai X WHERE X.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_ASESOR_ID = X.JADWAL_ASESOR_ID) and c.kode='".$reqTipe."'";

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
  $index_loop++;

  if($set->getField("PENGGALIAN_ID") == 0){}
  else
  $jumlahNilaiAkhir++;
}
$jumlah_asesor= $index_loop;
// print_r($arrAsesor);exit;

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

$setdetil= new JadwalTes();
$setdetil->selectByParams(array("JADWAL_TES_ID"=>$reqJadwalTesId),-1,-1);
$setdetil->firstRow();
$infotahun= getDay(datetimeToPage($setdetil->getField("TANGGAL_TES"), 'date'));

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

<body>
<pagebreak />

<h1 style="text-align: center;">Hasil Penilaian <?=$arrAsesor[0]["PENGGALIAN_NAMA"]?></h1>

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
	                <td colspan="4"><?=$arrAsesor[0]["NAMA_ASESOR"]?></td>
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
	if($reqInfoPenggalianId != "0")
	{
		$arrayKey= in_array_column($reqInfoPenggalianId, "PENGGALIAN_ID", $arrPegawaiNilai);
		?>
	    <br>
	    <table style="width :100%; font-size:11pt; border-collapse: collapse;">
	    	<tr>
	    		<td style="width: 20%;border: 0.5px solid black;text-align: center;"><b>Kompetensi</b></td>
	    		<td style="border: 0.5px solid black;text-align: center;"><b>Uraian</b></td>
	    		<td style="width: 10%;border: 0.5px solid black;text-align: center;"><b>Nilai</b></td>
	    	</tr>

		<?	
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
			   
			    $cssIndikator= "✖";
			    if($reqJadwalPegawaiDetilId != ""){
			      $cssIndikator= "✓";
			    }
			    ?>
			    <?
			    if($reqJadwalPegawaiAtributIdLookUp != $reqJadwalPegawaiAtributId){
    			  $reqJadwalPegawaiAtributIdLookUp= $reqJadwalPegawaiAtributId;?>
    			  <tr>
    			  	<td style="vertical-align: top; width: 20%;border: 0.5px solid black;">
    			  		<?=$reqJadwalPegawaiAtributNama?>
    			  	</td>
    			  	<td style="border: 0.5px solid black;text-align:justify ;">
    			  		<?=$reqDetilAtributCatatan?>
    			  	</td>
    			  	<td style="vertical-align: top; width:10%;border: 0.5px solid black;text-align: center;">
    			  		<?=$reqDetilAtributNilai?>
    			  	</td>
    			  </tr>
    			  <?
			    }
			}
		?>
	    </table>
	    <?
		}
	}

	// set form kalau bukan potensi
	else
    {
    ?>
    <br>
	    <table width ='100%' style=" font-size:11pt; border-collapse: collapse;"> 
          <tr>
            <td  style="border: 0.5px solid black; text-align: center;">ATRIBUT & INDIKATOR</td>
            <td  style="border: 0.5px solid black; text-align: center;">Standar</td>
            <td  style="border: 0.5px solid black; text-align: center;">Nilai</td>
          </tr>
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
	            $reqPenilaianPotensiGap= $arrPenilaian[$index_row]["GAP"];

	            $reqPenilaianPotensiNilaiDecimal= explode(".", $reqPenilaianPotensiGap);
	            if(count($reqPenilaianPotensiNilaiDecimal) > 1){
	              $reqPenilaianPotensiNilaiDecimal= end($reqPenilaianPotensiNilaiDecimal);
	            }
	            else{
	              $reqPenilaianPotensiNilaiDecimal= "";
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
	        ?>
	            <?
	            if($reqPenilaianPotensiAtributIdParent == "0")
	            {
	            ?>
	              <tr >
	                <th colspan="3"  style="border: 0.5px solid black; text-align: center; background-color: lightgray;"><b><?=$reqPenilaianPotensiAtributNama?></b></th>
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
	              <tr>
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
	                <td style="border: 0.5px solid black;" ><?=$reqPenilaianPotensiAtributNama?></td>
	                <td style="border: 0.5px solid black; text-align: center;"><?=NolToNone($reqPenilaianPotensiNilaiStandar)?>&nbsp;</td>
	                <td style="border: 0.5px solid black; text-align: center;"><?=$reqPenilaianPotensiNilai?></td>
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
	    </table>
	    <br>
	    <br>
	    <p  style="font-size: 11pt;"> <b style="font-size: 11pt;">Data Tambahan Untuk Profil Kompetensi</b></p>
        <table  style="width :100%; font-size:11pt; border-collapse: collapse;">
        	<tr>
	            <td style="border: 0.5px solid black;" width="3%">No</td>
	            <td style="border: 0.5px solid black;" width="20%">Kompetensi</td>
	            <td style="border: 0.5px solid black;" width="5%">Rating</td>
	            <td style="border: 0.5px solid black;" >Keterangan</td>
	        </tr>
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
                  $setPenilaianKompetensi->firstRow();
                ?>
                <tr>
                  <td style="border: 0.5px solid black;"><?=$index_atribut_parent?></td>
                  <td style="border: 0.5px solid black;"><?=$reqPenilaianKompetensiAtributNama?></td>
                  <td style="border: 0.5px solid black;"><?=$setPenilaianKompetensi->getField("penilaian")?></td>
                  <td style="border: 0.5px solid black;"><?=$setPenilaianKompetensi->getField("keterangan")?></td>
                </tr>
                <?
                $index_atribut_parent++;
                }
              }
            }
            ?>
          </tbody>
        </table>

        <br>
        <br>

        <p  style="font-size: 11pt;"> <b>Dinamika Psikologis :</b></p>
        <?
	        $setkepribadian= new PenilaianRekomendasi();
	        $setkepribadian->selectByParams(array('PEGAWAI_ID'=>$reqPegawaiId, 'JADWAL_TES_ID'=>$reqJadwalTesId, 'tipe'=>'profil_kepribadian'));
	        // echo $setkepribadian->query; exit;
	          $setkepribadian->firstRow();
	        $reqPenilaianKeteranganKepribadian= $setkepribadian->getField("KETERANGAN");
	    ?>
	    <p style="font-size: 11pt;text-align: justify;"><?=$reqPenilaianKeteranganKepribadian?></p>
    <?
    }
}
?>