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

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
  $userLogin->retrieveUserInfo();  
}

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
$reqJadwalPegawaiNip= $set->getField("PEGAWAI_NIP");
$reqJadwalPegawaiGol= $set->getField("PEGAWAI_GOL");
$reqJadwalPegawaiEselon= $set->getField("PEGAWAI_ESELON");
$reqJadwalPegawaiJabatan= $set->getField("PEGAWAI_JAB_STRUKTURAL");

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

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
  }

  $arrPenilaian[$index_loop]["PENILAIAN_DETIL_ID"]= $set->getField("PENILAIAN_DETIL_ID");
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
$statement= " AND C.JADWAL_TES_ID = ".$reqJadwalTesId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND C1.PENGGALIAN_ID = 0 AND F.ASPEK_ID = 2";
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

<script src="../WEB/lib/emodal/eModal.js"></script>
<script>
  function openPopup() {
    //document.getElementById("demo").innerHTML = "Hello World";
    //alert('hhh');
    // Display a ajax modal, with a title
    eModal.ajax('konten.html', 'Judul Popup')
    //  .then(ajaxOnLoadCallback);
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
<link rel="stylesheet" href="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/css/style.css" type="text/css">
  
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
                        <span class="judul-app"><a href="index.php"><img src="../WEB/images/logo_pemprov_bali_asli.png"> Aplikasi Pelaporan Hasil Assessment</a></span>

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
                                            <td><?=$reqJadwalPegawaiNama?></td>
                                        </tr>
                                        <tr>
                                            <th>NIP</th>
                                            <th>:</th>
                                            <td><?=$reqJadwalPegawaiNip?></td>
                                        </tr>
                                        <tr>
                                            <th>Pangkat / Gol.Ruang</th>
                                            <th>:</th>
                                            <td><?=$reqJadwalPegawaiGol?></td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan</th>
                                            <th>:</th>
                                            <td><?=$reqJadwalPegawaiJabatan?></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>
                    
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
                        <li role="tab" class="<?=$tabSelectCss?>"><a href="#tabs-lain" role="presentation">Lain-Lain </a></li>

                      </ul>
                      <?
                      for($index_loop=0; $index_loop < $jumlah_asesor; $index_loop++)
                      {
                        // $arrAsesor[$index_loop]["JADWAL_ASESOR_ID"];
                        $reqInfoPenggalianId= $arrAsesor[$index_loop]["PENGGALIAN_ID"];
                        // $arrAsesor[$index_loop]["PENGGALIAN_NAMA"];
                        $reqInfoPenggalianKode= $arrAsesor[$index_loop]["PENGGALIAN_KODE"];
                      ?>
                      <div class="ne-except" id="tabs-<?=$reqInfoPenggalianId?>" role="tabpanel" style="background: #063a69 url(../images/bg-main.png) !important;">


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
                                            <td style="text-align:center; width: 10%" rowspan="2">Standar Rating</td>
                                            <td style="text-align:center" colspan="5">Hasil Individu</td>
                                            <!-- <td style="text-align:center; width: 10%" rowspan="2">Gap</td> -->
                                            <!-- <td style="text-align:center" rowspan="2">Deskripsi</td>
                                            <td style="text-align:center" rowspan="2">Saran Pengembang</td> -->
                                          </tr>
                                          <tr>
                                            <td style="text-align:center; width: 10%">1</td>
                                            <td style="text-align:center; width: 10%">2</td>
                                            <td style="text-align:center; width: 10%">3</td>
                                            <td style="text-align:center; width: 10%">4</td>
                                            <td style="text-align:center; width: 10%">5</td>
                                          </tr>
                                          <tr class="<?=$css?>">
                                            <th style="text-align:center"><b><?=romanicNumber($index_atribut_parent)?></b></th>
                                            <th colspan="8"><b><?=$reqPenilaianPotensiAtributNama?></b></th>
                                          </tr>
                                        <?
                                        }
                                        else
                                        {
                                          $arrChecked= "";
                                          if($reqPenilaianPotensiNilai == "" || $reqPenilaianPotensiNilai == "0"){}
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[0]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="1" data-options="validType:'requireRadio[\'#ff-<?=$reqInfoPenggalianId?> input[name=reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>]\', \'Pilih nilai\']'"/>
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[1]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="2" />
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[2]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="3" />
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[3]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="4" />
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[4]?> name="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" id="reqPenilaianRadio<?=$reqPenilaianPotensiAspekId?>-<?=$index_detil?>" value="5" />
                                              <?
                                              }
                                              ?>
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
                                            <td colspan="8">
                                              <?
                                              if($disabledatribut == "")
                                              {
                                              ?>
                                              
                                              <textarea name="reqPenilaianPotensiCatatan[]" class="easyui-validatebox" data-options="validType:'justText'" style="color:#000 !important"><?=$reqPenilaianPotensiCatatan?></textarea>
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
                                        $reqDetilAtributGap= $arrPegawaiNilai[$index_row]["GAP"];
                                        $reqDetilAtributCatatan= $arrPegawaiNilai[$index_row]["CATATAN"];

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
                                                <?=$reqJadwalPegawaiNamaIndikator?>
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
                                        else
                                        $arrChecked= radioPenilaian($reqDetilAtributNilai);
                                      ?>
                                            </ul>
                                          </div>
                                        </td>

                                        <!-- set data atribut -->
                                        <td style="vertical-align:top; background-color:transparent; color:#000 !important">
                                           <table style="width:100%; border:none">
                                            <tr>
                                              <td style="text-align:center">1</td>
                                              <td style="text-align:center">2</td>
                                              <td style="text-align:center">3</td>
                                              <td style="text-align:center">4</td>
                                              <td style="text-align:center">5</td>
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
                                              <input type="hidden" id="reqDetilAtributNilai<?=$index_detil?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqDetilAtributNilai[]" value="<?=$reqDetilAtributNilai?>" />
                                              <input type="hidden" id="reqDetilAtributGap<?=$index_detil?>-<?=$reqInfoPenggalianId?>" style="color:#000 !important" name="reqDetilAtributGap[]" value="<?=$reqDetilAtributGap?>" />

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
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[0]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" value="1" data-options="validType:'requireRadio[\'#ff-<?=$reqInfoPenggalianId?> input[name=reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>]\', \'Pilih nilai\']'"/>
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
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[1]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" value="2" />
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
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[2]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" value="3" />
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
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[3]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" value="4" />
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
                                                <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[4]?> name="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" id="reqRadio<?=$index_detil?>-<?=$reqInfoPenggalianId?>" value="5" />
                                                <?
                                                }
                                                ?>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td colspan="5">
                                                <?
                                                if($disabledatribut == "")
                                                {
                                                ?>
                                                <textarea name="reqDetilAtributCatatan[]" class="easyui-validatebox" data-options="validType:'justText'" style="color:#000 !important"><?=$reqDetilAtributCatatan?></textarea>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                <input type="hidden" name="reqDetilAtributCatatan[]" value="<?=$reqDetilAtributCatatan?>" />
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
                      <div id="tabs-nilaiakhir" role="tabpanel" style="background: #063a69 url(../images/bg-main.png) !important;">
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
                                        $reqPenilaianKompetensiNilai= $arrPenilaian[$index_row]["NILAI"];
                                        $reqPenilaianKompetensiGap= $arrPenilaian[$index_row]["GAP"];

                                        if($reqPenilaianKompetensiGap == "" || $reqPenilaianKompetensiGap == "0")
                                          $reqPenilaianKompetensiGap= 0;
                                        else
                                          $reqPenilaianKompetensiGap= $reqPenilaianKompetensiNilai-$reqPenilaianKompetensiNilaiStandar;

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
                                            <th colspan="<?=$tempcolspan?>"><b><?=$reqPenilaianKompetensiAtributNama?></b></th>
                                          </tr>
                                        <?
                                        }
                                        else
                                        {
                                          $arrChecked= "";
                                          if($reqPenilaianKompetensiNilai == "" || $reqPenilaianKompetensiNilai == "0"){}
                                          else
                                          $arrChecked= radioPenilaian($reqPenilaianKompetensiNilai);
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
                                            <td style="text-align:center; width: 5%" rowspan="2">Standar Level</td>
                                            <td style="text-align:center" colspan="5">Hasil Individu</td>
                                            <td style="text-align:center; width: 5%" rowspan="2">Gap</td>
                                            <!-- <td style="text-align:center" rowspan="2">Deskripsi</td>
                                            <td style="text-align:center" rowspan="2">Saran Pengembang</td> -->
                                          </tr>
                                          <tr>
                                            <td style="text-align:center; width: 5%">1</td>
                                            <td style="text-align:center; width: 5%">2</td>
                                            <td style="text-align:center; width: 5%">3</td>
                                            <td style="text-align:center; width: 5%">4</td>
                                            <td style="text-align:center; width: 5%">5</td>
                                          </tr>
                                          <tr class="<?=$css?>">
                                            <td rowspan="2" style="vertical-align: top; text-align:center">
                                              <?=$index_atribut?>
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianKompetensiDetilId[]" id="reqPenilaianKompetensiDetilId<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianKompetensiDetilId?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianKompetensiNilai[]" id="reqPenilaianKompetensiNilai<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianKompetensiNilai?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianKompetensiGap[]" id="reqPenilaianKompetensiGap<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianKompetensiGap?>" />
                                              <input type="hidden" style="color:#000 !important" name="reqPenilaianKompetensiNilaiStandar[]" id="reqPenilaianKompetensiNilaiStandar<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="<?=$reqPenilaianKompetensiNilaiStandar?>" />
                                            </td>
                                            <td><?=$reqPenilaianKompetensiAtributNama?></td>
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
                                              $tempCariDataDetilNilai= $reqPenilaianKompetensiAtributId."-".$tempAsesorId."-".$arrPegawaiAsesor[$index_loop]["PENGGALIAN_ID"];
                                              $arrayDetilKey= "";
                                              $arrayDetilKey= in_array_column($tempCariDataDetilNilai, "PENGGALIAN_ASESOR_ID", $arrAsesorPenilaianKompetensi);
                                              // print_r($arrAsesorPenilaianKompetensi);exit();
                                              // print_r($arrayDetilKey);exit;
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[0]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="1" data-options="validType:'requireRadio[\'#ff- input[name=reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>]\', \'Pilih nilai\']'"/>
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[1]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="2" />
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[2]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="3" />
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[3]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="4" />
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
                                              <input type="radio" <?=$disabledatribut?> class="easyui-validatebox" <?=$arrChecked[4]?> name="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" id="reqPenilaianKompetensiRadio<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" value="5" />
                                              <?
                                              }
                                              ?>
                                            </td>
                                            <td align="center"><label id="reqPenilaianKompetensiGapInfo<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>"><?=$reqPenilaianKompetensiGap?></label>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td colspan="<?=$tempcolspandetil?>">
                                              <input type="hidden" name="reqPenilaianKompetensiBolehSimpan[]" value="<?=$bolehsimpan?>" />
                                              <?
                                              $munculsarancss="";
                                              if($reqPenilaianKompetensiGap >= 0)
                                              $munculsarancss="display:none";

                                              if($disabledatribut == "")
                                              {
                                              ?>
                                              <fieldset>
                                                <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Deskripsi</legend>
                                                <textarea name="reqPenilaianKompetensiBukti[]" id="reqPenilaianKompetensiBukti<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>" style="width:95%" rows="1" ><?=$reqPenilaianKompetensiBukti?></textarea>
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
                                                <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Deskripsi</legend>
                                                <input type="hidden" name="reqPenilaianKompetensiBukti[]" value="<?=$reqPenilaianKompetensiBukti?>" />
                                                <?=$reqPenilaianKompetensiBukti?>
                                              </fieldset>
                                              <span style="<?=$munculsarancss?>" id="reqPenilaianKompetensiSaran<?=$reqPenilaianKompetensiAspekId?>-<?=$index_detil?>">
                                                <fieldset style="border: 1px solid; padding: 10px !important">
                                                  <legend style="font-size: 14px !important; border: medium none !important; margin-top: 20px; margin-bottom: 10px; color: white !important;">Saran Pengembangan</legend>
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
                                      <th colspan="<?=$tempsarancolspan?>">Area Pengembangan</th>
                                      <input type="hidden" name="reqNilaiAkhirSaranPengembanganPegawaiId" value="<?=$reqPegawaiId?>" />
                                      <input type="hidden" name="reqNilaiAkhirSaranPengembanganJadwalTesId" value="<?=$reqJadwalTesId?>" />
                                    </tr>
                                    <tr>
                                      <td colspan="<?=$tempsarancolspan?>">
                                      <?
                                      if($kondisiasesorsaranid == "1")
                                      {
                                      ?>
                                      <fieldset>
                                        <textarea name="reqNilaiAkhirSaranPengembangan" id="reqNilaiAkhirSaranPengembangan" style="width:95%" rows="1" ><?=$reqNilaiAkhirSaranPengembangan?></textarea>
                                      </fieldset>
                                      <?
                                      }
                                      else
                                      {
                                      ?>
                                      <fieldset style="border: 1px solid; padding: 10px !important">
                                        <input type="hidden" name="reqNilaiAkhirSaranPengembangan" value="<?=$reqNilaiAkhirSaranPengembangan?>" />
                                        <?=$reqNilaiAkhirSaranPengembangan?>
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

                      <div id="tabs-lain" role="tabpanel" style="background: #063a69 url(../images/bg-main.png) !important;">
                        <div class="row">
                          <div class="col-md-12">

                            <div class="area-table-assesor">
                              <br>
                              <div class="judul-halaman">Lain Lain</div>
                              <form id="ff-simpan" method="post" novalidate>

                                <table style="margin-bottom:60px;" class="profil">
                                  <thead>

                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td colspan="5" >

                                        <?
                                        if($disabledatribut == "")
                                        {
                                        ?>
                                        <fieldset>
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Kekuatan</legend>
                                          <textarea name="reqPenilaianPotensiStrength" id="reqPenilaianPotensiStrength" style="width:95%" rows="1" ><?=$reqPenilaianPotensiStrength?></textarea>
                                        </fieldset>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Kekuatan</legend>
                                          <input type="hidden" name="reqPenilaianPotensiStrength" value="<?=$reqPenilaianPotensiStrength?>" />
                                          <?=$reqPenilaianPotensiStrength?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                        ?>
                                        <fieldset>
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Kelemahan</legend>
                                          <textarea name="reqPenilaianPotensiWeaknes" id="reqPenilaianPotensiWeaknes" style="width:95%" rows="1" ><?=$reqPenilaianPotensiWeaknes?></textarea>
                                        </fieldset>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Kelemahan</legend>
                                          <input type="hidden" name="reqPenilaianPotensiWeaknes" value="<?=$reqPenilaianPotensiWeaknes?>" />
                                          <?=$reqPenilaianPotensiWeaknes?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                        ?>
                                        <fieldset>
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Rekomendasi</legend>
                                          <textarea name="reqPenilaianPotensiKesimpulan" id="reqPenilaianPotensiKesimpulan" style="width:95%" rows="1" ><?=$reqPenilaianPotensiKesimpulan?></textarea>
                                        </fieldset>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Rekomendasi</legend>
                                          <input type="hidden" name="reqPenilaianPotensiKesimpulan" value="<?=$reqPenilaianPotensiKesimpulan?>" />
                                          <?=$reqPenilaianPotensiKesimpulan?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                        ?>
                                        <fieldset>
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Saran Pengembangan</legend>
                                          <textarea name="reqPenilaianPotensiSaranPengembangan" id="reqPenilaianPotensiSaranPengembangan" style="width:95%" rows="1" ><?=$reqPenilaianPotensiSaranPengembangan?></textarea>
                                        </fieldset>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Saran Pengembangan</legend>
                                          <input type="hidden" name="reqPenilaianPotensiSaranPengembangan" value="<?=$reqPenilaianPotensiSaranPengembangan?>" />
                                          <?=$reqPenilaianPotensiSaranPengembangan?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>

                                        <?
                                        if($disabledatribut == "")
                                        {
                                        ?>
                                        <fieldset>
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Saran Penempatan</legend>
                                          <textarea name="reqPenilaianPotensiSaranPenempatan" id="reqPenilaianPotensiSaranPenempatan" style="width:95%" rows="1" ><?=$reqPenilaianPotensiSaranPenempatan?></textarea>
                                        </fieldset>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <fieldset style="border: 1px solid; padding: 10px !important">
                                          <legend style="font-size: 14px !important; border: medium none !important; margin-bottom: 10px; color: white !important;">Saran Penempatan</legend>
                                          <input type="hidden" name="reqPenilaianPotensiSaranPenempatan" value="<?=$reqPenilaianPotensiSaranPenempatan?>" />
                                          <?=$reqPenilaianPotensiSaranPenempatan?>
                                        </fieldset>
                                        <?
                                        }
                                        ?>
                                        <input type="hidden" style="color:#000 !important" name="reqLainPenilaianPotensiId" value="<?=$reqLainPenilaianPotensiId?>" />
                                      </td>
                                    </tr>
                                    <?
                                    // $tempsimpankompetensi= "1";
                                    if($tempsimpankompetensi == "1")
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
  © 2020 Pemprov Bali. All Rights Reserved. 
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
        if($tempFotoLink == "")
        {
          ?>
          $("#reqImagePeserta").attr("src", "../WEB/images/no-picture.jpg");
          <?
        }
        else
        {
          ?>
          $("#reqImagePeserta").attr("src", "../uploads/<?=$tempPesertaKtp?>/foto/<?=$tempFotoLink?>");
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
        document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=<?=$reqInfoPenggalianId?>';
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
        document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-nilaiakhir';
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
      document.location.href = 'penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqSelectPenggalianId=tabs-lain';
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

    $("#reqPenilaianKompetensiNilai"+rowid).val(tempValId);
    var gap= parseInt(tempValId) - parseInt($("#reqPenilaianKompetensiNilaiStandar"+rowid).val());
    $("#reqPenilaianKompetensiGap"+rowid).val(gap);
    $("#reqPenilaianKompetensiGapInfo"+rowid).text(gap);

    $("#reqPenilaianKompetensiSaran"+rowid).show();
    if(parseInt(gap) >= 0)
    $("#reqPenilaianKompetensiSaran"+rowid).hide();

  });



});
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

<script type="text/javascript" src="../niceEdit/nicedit.js"></script>
</body>
</html>