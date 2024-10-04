<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjianPetunjuk.php");

if($userLogin->ujianUid == "")
{
  if($pg == "" || $pg == "home"){}
  else
  {
    echo '<script language="javascript">';
    echo 'top.location.href = "index.php";';
    echo '</script>';
    exit;
  }
}
date_default_timezone_set('Asia/Jakarta');

$reqId= httpFilterGet("reqId");
$reqUjianTahapId= $reqId;

$tempPegawaiId= $userLogin->pegawaiId;
$reqPegawaiId= $tempPegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

// $tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiLowonganId= $ujianPegawaiJadwalTesId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$tempUjianId= $ujianPegawaiUjianId;

$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$ujianPegawaiUjianId." AND B.UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianTahap();
$set->selectByParamsUjianPegawaiTahapLatihan(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();
$tempUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$tempTipeUjianId= $set->getField("TIPE_UJIAN_ID");
$tempPanjangTipeUjianId= strlen($set->getField("PARENT_ID"));
$tempStatusTipeUjianId= $set->getField("STATUS_TAHAP_UJIAN");
$tempTipeTahap= $set->getField("TIPE");
unset($set);
// echo $tempUjianPegawaiDaftarId;exit();

  $sOrder= "ORDER BY URUT, RANDOM()";
  if($tempPanjangTipeUjianId == 2)
  $sOrder= "ORDER BY UP.URUT, A.UJIAN_ID, B.UJIAN_TAHAP_ID";
  // $sOrder= "ORDER BY UP.URUT, A.UJIAN_ID, B.BANK_SOAL_ID, UP.UJIAN_PEGAWAI_ID";
  // set untuk urut
  // $sOrder= "ORDER BY LENGTH(C.PATH_SOAL), C.PATH_SOAL, A.UJIAN_ID, B.BANK_SOAL_ID, UP.UJIAN_PEGAWAI_ID";
  
  $index_loop=0;
  $arrDataJumlahSoalPegawai=array();

  $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.UJIAN_TAHAP_ID = ".$reqId." AND A.UJIAN_ID = ".$tempUjianId;
    $statementujian= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND A.UJIAN_TAHAP_ID = ".$reqId;
  $set= new UjianPegawaiDaftar();
  $set->selectByParamsSoalRevisiLatihan(array(), -1,-1, $tempUjianPegawaiLowonganId, $statement, $statementujian, $sOrder);
  // echo $set->query;exit;
  $tempValueRowId= "";
  while($set->nextRow())
  {
    if($set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID") == $tempValueRowId){}
    else
    {
      $nomor= $index_loop+1;
      $arrDataJumlahSoalPegawai[$index_loop]["ID_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
      $arrDataJumlahSoalPegawai[$index_loop]["NOMOR"]= $nomor;
      $arrDataJumlahSoalPegawai[$index_loop]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");
      $arrDataJumlahSoalPegawai[$index_loop]["UJIAN_ID"]= $set->getField("UJIAN_ID");
      $arrDataJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set->getField("UJIAN_BANK_SOAL_ID");
      $arrDataJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
      $arrDataJumlahSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"]= $set->getField("BANK_SOAL_PILIHAN_ID");
      $arrDataJumlahSoalPegawai[$index_loop]["KEMAMPUAN"]= $set->getField("KEMAMPUAN");
      $arrDataJumlahSoalPegawai[$index_loop]["KATEGORI"]= $set->getField("KATEGORI");
      $arrDataJumlahSoalPegawai[$index_loop]["PERTANYAAN"]= $set->getField("PERTANYAAN");
      $arrDataJumlahSoalPegawai[$index_loop]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
      $arrDataJumlahSoalPegawai[$index_loop]["PATH_GAMBAR"]= $set->getField("PATH_GAMBAR");
      $arrDataJumlahSoalPegawai[$index_loop]["PATH_SOAL"]= $set->getField("PATH_SOAL");
    
      $index_loop++;
    }
    $tempValueRowId= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
    
  }
  $tempDataJumlahSoalPegawai= $index_loop;
  unset($set);
  // print_r($arrDataJumlahSoalPegawai);exit;
  
  // set untuk urut
  $sOrder= "ORDER BY LENGTH(C.PATH_SOAL), C.PATH_SOAL";
  // $sOrder= "ORDER BY RANDOM()";

  //$sOrder= "ORDER BY B.UJIAN_BANK_SOAL_ID";
  $index_loop=0;
  $arrDataJumlahJawabanSoalPegawai="";

  $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.UJIAN_TAHAP_ID = ".$reqId." AND COALESCE(NULLIF(D.JAWABAN, ''), NULL) IS NOT NULL AND A.UJIAN_ID = ".$tempUjianId;
  $statement.= " AND D.GRADE_PROSENTASE > 0";

  $set= new UjianPegawaiDaftar();
  $set->selectByParamsJawabanSoalLatihan(array(), -1,-1, $statement, $sOrder);
  // echo $set->query;exit;
  while($set->nextRow())
  {
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["ID_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["ID_VAL_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("UJIAN_BANK_SOAL_ID")."-".$set->getField("BANK_SOAL_ID")."-".$set->getField("BANK_SOAL_PILIHAN_ID");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["UJIAN_ID"]= $set->getField("UJIAN_ID");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set->getField("UJIAN_BANK_SOAL_ID");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"]= $set->getField("BANK_SOAL_PILIHAN_ID");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["JAWABAN"]= $set->getField("JAWABAN");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["PATH_GAMBAR"]= $set->getField("PATH_GAMBAR");
    $arrDataJumlahJawabanSoalPegawai[$index_loop]["PATH_SOAL"]= $set->getField("PATH_SOAL");
    $index_loop++;
  }
  $tempDataJumlahJawabanSoalPegawai= $index_loop;
  unset($set);
  // print_r($arrDataJumlahJawabanSoalPegawai);exit();

$index_loop=0;
$arrJumlahSoalPegawai=array();
$set= new UjianPegawaiDaftar();

if($tempTipeUjianId == 7)
{
  exit();
}
else
{
  $sOrder= "ORDER BY A.URUT, A.UJIAN_ID, A.UJIAN_TAHAP_ID";
  $set= new UjianPegawai();
  $statement= " AND A.UJIAN_TAHAP_ID = ".$reqId." AND A.UJIAN_ID = ".$tempUjianId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
  $set->selectByParamsSoalFinishRevisiLatihan(array(), -1,-1, $tempUjianPegawaiLowonganId, $statement, $sOrder);
  // echo $set->query;exit();
}
//echo $set->query;exit;
$tempValueRowId= "";
while($set->nextRow())
{
  // if($set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID")."-".$set->getField("UJIAN_TAHAP_ID") == $tempValueRowId){}
  if($set->getField("UJIAN_ID")."-".$set->getField("NOMOR")."-".$set->getField("UJIAN_TAHAP_ID") == $tempValueRowId){}
  else
  {
    if($tempUjianTahapId == $set->getField("UJIAN_TAHAP_ID"))
    {
      $nomor++;
    }
    else
    $nomor= 1;
    
    
    $arrJumlahSoalPegawai[$index_loop]["ID_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
    $arrJumlahSoalPegawai[$index_loop]["NOMOR"]= $nomor;
    $arrJumlahSoalPegawai[$index_loop]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");
    $arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"]= $set->getField("UJIAN_ID");
    $arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set->getField("UJIAN_BANK_SOAL_ID");
    $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
    $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"]= $set->getField("BANK_SOAL_PILIHAN_ID");
    $arrJumlahSoalPegawai[$index_loop]["KEMAMPUAN"]= $set->getField("KEMAMPUAN");
    $arrJumlahSoalPegawai[$index_loop]["KATEGORI"]= $set->getField("KATEGORI");
    $arrJumlahSoalPegawai[$index_loop]["PERTANYAAN"]= $set->getField("PERTANYAAN");
    $arrJumlahSoalPegawai[$index_loop]["UJIAN_TAHAP_ID"]= $set->getField("UJIAN_TAHAP_ID");
    $arrJumlahSoalPegawai[$index_loop]["TIPE"]= $set->getField("TIPE");
    
    $tempUjianTahapId= $set->getField("UJIAN_TAHAP_ID");
    // $tempUjianTahapId= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
    $index_loop++;
  }
  // $tempValueRowId= $set->getField("UJIAN_ID")."-".$set->getField("NOMOR")."-".$set->getField("UJIAN_TAHAP_ID");
  $tempValueRowId= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID")."-".$set->getField("UJIAN_TAHAP_ID");
  
}
$tempJumlahSoalPegawai= $index_loop;
unset($set);
// print_r($arrJumlahSoalPegawai);exit;

$index_data=0;
$arrJumlahTahap="";
$statement= " AND B.UJIAN_TAHAP_ID = ".$reqId." AND COALESCE(B.MENIT_SOAL,0) > 0 AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId;
$set_tahap= new UjianTahap();
$set_tahap->selectByParamsUjianPegawaiTahapLatihan(array(), -1,-1, $statement, "ORDER BY ID");
// echo $set_tahap->query;exit;
while($set_tahap->nextRow())
{
  $arrJumlahTahap[$index_data]["UJIAN_TAHAP_ID"]= $set_tahap->getField("UJIAN_TAHAP_ID");
  $arrJumlahTahap[$index_data]["TIPE"]= $set_tahap->getField("TIPE");
  $index_data++;
}
$tempJumlahTahap= $index_data;
unset($set_tahap);
// print_r($arrJumlahTahap);exit();

$statement= " AND A.UJIAN_ID = ".$tempUjianId." AND B.PEGAWAI_ID = ".$tempPegawaiId;
$set= new UjianTahap();
$set->selectByParamsPegawaiSelesaiTahapLatihan(array(), -1,-1, $statement);
//echo $set->errorMsg;exit;
// echo $set->query;exit;
$set->firstRow();
$tempJumlahPegawaiSelesaiTahap= $set->getField("JUMLAH_PEGAWAI_SELESAI_TAHAP");
// echo $tempJumlahPegawaiSelesaiTahap;exit();
unset($set);

//=================
$statement= " AND COALESCE(B.MENIT_SOAL,0) > 0 AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND B.UJIAN_TAHAP_ID = ".$reqUjianTahapId;
$set= new UjianTahap();
$set->selectByParamsUjianPegawaiTahapLatihan(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();
$tempMenitSoal= $set->getField("MENIT_SOAL");
$reqUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$reqPegawaiId= $tempPegawaiId;
$reqJadwalTesId= $ujianPegawaiJadwalTesId;
$reqFormulaAssesmentId= $ujianPegawaiFormulaAssesmentId;
$reqFormulaEselonId= $ujianPegawaiFormulaEselonId;
$reqUjianId= $tempUjianId;
$reqTipeUjianId= $set->getField("TIPE_UJIAN_ID");

// cfit b mabil latihan cfit a
if($reqTipeUjianId >=8 && $reqTipeUjianId <= 11)
$reqTipeUjianIdLatihan= $reqTipeUjianId + 4;
// cfit a mabil latihan cfit b
elseif($reqTipeUjianId >=12 && $reqTipeUjianId <= 15)
$reqTipeUjianIdLatihan= $reqTipeUjianId - 4;
else
$reqTipeUjianIdLatihan= $reqTipeUjianId;

$statement= " AND UJIAN_ID= ".$ujianPegawaiUjianId." AND UJIAN_TAHAP_ID = ".$reqUjianTahapId." AND PEGAWAI_ID = ".$tempPegawaiId;
$set= new UjianTahapStatusUjian();
$set->selectByParamsLatihan(array(), -1,-1, $statement);
$set->firstRow();
$tempPegawaiId= $set->getField("PEGAWAI_ID");
unset($set);

if($tempPegawaiId == "")
{
  $set= new UjianTahapStatusUjian();
  $set->setField("UJIAN_PEGAWAI_DAFTAR_ID", $reqUjianPegawaiDaftarId);
  $set->setField("LOWONGAN_ID", $tempUjianPegawaiLowonganId);
  $set->setField("JADWAL_TES_ID", $ujianPegawaiJadwalTesId);
  $set->setField("FORMULA_ASSESMENT_ID", $ujianPegawaiFormulaAssesmentId);
  $set->setField("FORMULA_ESELON_ID", $ujianPegawaiFormulaEselonId);
  $set->setField("TIPE_UJIAN_ID", $reqTipeUjianId);
  $set->setField("UJIAN_ID", $ujianPegawaiUjianId);
  $set->setField("UJIAN_TAHAP_ID", $reqUjianTahapId);
  $set->setField("PEGAWAI_ID", $reqPegawaiId);
  $set->setField("STATUS", "1");
  $set->setField("LAST_CREATE_USER", $userLogin->nama);
  $set->setField("LAST_CREATE_DATE", "NOW()");
  if($set->insertLatihan())
  {
  }
  // echo $set->query;exit;
  unset($set);
}

// $set_detil= new UjianTahap();
// $set_detil->selectByParams(array(), -1,-1, " AND TIPE_UJIAN_ID = ".$reqTipeUjianIdLatihan." AND UJIAN_ID = ".$ujianPegawaiUjianId);
// $set_detil->firstRow();
// // echo $set_detil->query;exit();
// $reqUjianTahapId= $set_detil->getField("UJIAN_TAHAP_ID");

$statement= " AND A.UJIAN_ID = ".$ujianPegawaiUjianId." AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TIPE_UJIAN_ID = ".$reqTipeUjianIdLatihan;
$set= new UjianTahapStatusUjianPetunjuk();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;
$set->firstRow();
$reqUjianTahapId= $set->getField("UJIAN_TAHAP_ID");
// echo $reqUjianTahapId;exit();
?>

<script language="javascript">
  // untuk clear time
  localStorage.clear();
</script>

<style>

  /* a few styles for the default page to make it presentable */
  .tabbable {
      margin-bottom: 18px;
  }
  .tab-content {
      padding: 15px; 
      border-bottom: 1px solid #ddd;
    display:inline-block;
    width:100%;
  }

  /* tab styles for small screen */
  @media (max-width: 767px) {
      
      .tabbable.responsive .nav-tabs {
          font-size: 16px;
      }
      .tabbable.responsive .nav-tabs ul {
          margin: 0;
      }
      .tabbable.responsive .nav-tabs li {
          /* box-sizing seems like the cleanest way to make sure width includes padding */
          -webkit-box-sizing: border-box;
             -moz-box-sizing: border-box; 
              -ms-box-sizing: border-box;
               -o-box-sizing: border-box;
                  box-sizing: border-box; 
          display: inline-block; 
          width: 100%; 
          height: 44px;
          line-height: 44px; 
          padding: 0 15px;
          border: 1px solid #ddd;
          overflow: hidden;
      }
      .tabbable.responsive .nav-tabs > li > a {
          border-style: none;
          display: inline-block;
          margin: 0;
          padding: 0;
      }
      /* include hover and active styling for links to override bootstrap defaults */
      .tabbable.responsive .nav-tabs > li > a:hover {
          border-style: none; 
          background-color: transparent;}
      .tabbable.responsive .nav-tabs > li > a:active,
      .tabbable.responsive .nav-tabs > .active > a,
      .tabbable.responsive .nav-tabs > .active > a:hover {
          border-style: none;
      }
  }

  /* sample styles for the tab controls on small screens  - start with left control and override for right */
  .tabbable.responsive .nav-tabs > li > a.tab-control,
  .tabbable.responsive .nav-tabs > li > span.tab-control-spacer {
      float: left;
      width: 36px;
      height: 36px;
      margin-top: 4px;
      font-size: 56px;
      font-weight: 100;
      line-height: 26px;
      color: #fff;
      text-align: center;
      background: #444;
      -webkit-border-radius: 18px;
         -moz-border-radius: 18px;
              border-radius: 18px;
      }
  .tabbable.responsive .nav-tabs > li > a.tab-control.right,
  .tabbable.responsive .nav-tabs > li > span.tab-control-spacer.right {
      float: right;
  }
  .tabbable.responsive .nav-tabs > li > a.tab-control:hover {
      color: #fff;
      background: #444;
  }
  .tabbable.responsive .nav-tabs > li > span.tab-control-spacer {
      line-height: 28px;
      color: transparent;
      background: transparent;
  }

</style>

<style>
.area-main{
  height: auto;
}
.area-soal{
  max-height: none;
  *border: 1px solid red;
  *max-height: calc(70vh - 100px);
  *overflow: auto;
}
.area-sudah {
  max-height: none;
  *border: 1px solid red;
  padding: 0 0;
}
.area-sudah.finish {
    *border: 2px solid red;
  border: 1px solid rgba(0,0,0,0.1);
    padding: 20px 20px 0;
    margin-top: 0px;
  margin-bottom: 15px;
  background: #EEEEEE;
}
.area-sudah .tab-content {
    padding: 0 15px;
    border-bottom: none;
    display: inline-block;
    width: 100%;
}
.area-finish {
  *border: 1px solid red;
}

/***/
.area-prev-next {
    position: relative;
    width: 100%;
    bottom: auto;
    left: auto;
    right: auto;
    *background: cyan;
  *border: 1px solid red;
  float: left;
  width: 100%;
  
  margin-top: 15px;
  margin-bottom: 15px;
}
</style>

<div class="container utama">
  <div class="row">
      <div class="col-md-12">
          
            <div class="area-sisa-waktu" id="reqStatusWaktu" style="clear:both; ">
              <div class="judul">
                  <?
          for($index_loop=0; $index_loop < $tempJumlahTahap; $index_loop++)
          {
            $tempUjianTahap= $arrJumlahTahap[$index_loop]["UJIAN_TAHAP_ID"];
            $tempTipe= $arrJumlahTahap[$index_loop]["TIPE"];
            
            $setClassAktif= "";
            if($index_loop==0)
            {
              $setClassAktif= "active";
            }
          ?>
          <?=$tempTipe?>
          <?
          }
          ?>
                </div>
                <div class="waktu">
                  <div id="divCounter"></div>
                </div>
            </div>
            
            <div class="area-soal">
            
              <div class="area-soal-jawaban-finish">
              <?
              for($index_loop=0; $index_loop < $tempDataJumlahSoalPegawai; $index_loop++)
              {
                $tempIdRow= $arrDataJumlahSoalPegawai[$index_loop]["ID_ROW"];
                $tempNomor= $arrDataJumlahSoalPegawai[$index_loop]["NOMOR"];
                $tempPertanyaan= $arrDataJumlahSoalPegawai[$index_loop]["PERTANYAAN"];
                $tempTipeSoal= $arrDataJumlahSoalPegawai[$index_loop]["TIPE_SOAL"];
                $tempPathSoal= $arrDataJumlahSoalPegawai[$index_loop]["PATH_SOAL"];
                $tempPathGambar= $arrDataJumlahSoalPegawai[$index_loop]["PATH_GAMBAR"];
                
                $tempUjianId= $arrDataJumlahSoalPegawai[$index_loop]["UJIAN_ID"];
                $tempUjianBankSoalId= $arrDataJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"];
                $tempBankSoalId= $arrDataJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"];
                $tempBankSoalPilihanId= $arrDataJumlahSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"];
              ?>
              <div class="panel-wrapper">
                  <span class="nomor"><?=$tempNomor?></span>
                  <?
                  if($tempTipeSoal==1)
                  {
                  ?>
                    <span class="pertanyaan"><?=$tempPertanyaan?></span>
                  <?
                  }
                  else if($tempTipeSoal==2)
                  {
                  ?>
                    <span class="gambar-soal-kiri">
                      <?
                      if(file_exists($tempPathGambar.$tempPathSoal))
                      {
                      ?>
                        <img src="<?=$tempPathGambar.$tempPathSoal?>">
                      <?
                      }
                      ?>
                    </span>
                    <?
                  }
                  else if($tempTipeSoal==5)
                  {
                  ?>
                    <span class="gambar-soal-kiri">
                      <?
                      if(file_exists($tempPathGambar.$tempPathSoal))
                      {
                      ?>
                        <img src="<?=$tempPathGambar.$tempPathSoal?>">
                      <?
                      }
                      ?>
                    </span>
                    <span class="teks"><?=$tempPertanyaan?></span>
                  <?
                  }
                  ?>
                  <div class="area-jawab-pilihan-ganda">
                    <?
                    $arrayJawaban= '';
                    $arrayJawaban= in_array_column($tempIdRow, "ID_ROW", $arrDataJumlahJawabanSoalPegawai);
                    // print_r($arrayJawaban);exit();
                    if($arrayJawaban == ''){}
                    else
                    {
                        for($index_detil=0; $index_detil < count($arrayJawaban); $index_detil++)
                        {
                          $index_row= $arrayJawaban[$index_detil];
                          $tempJawaban= $arrDataJumlahJawabanSoalPegawai[$index_row]["JAWABAN"];
                          $tempTipeSoal= $arrDataJumlahJawabanSoalPegawai[$index_row]["TIPE_SOAL"];
                          $tempPathSoal= $arrDataJumlahJawabanSoalPegawai[$index_row]["PATH_SOAL"];
                          $tempPathGambar= $arrDataJumlahJawabanSoalPegawai[$index_row]["PATH_GAMBAR"];

                          $tempUjianId= $arrDataJumlahJawabanSoalPegawai[$index_row]["UJIAN_ID"];
                          $tempUjianBankSoalId= $arrDataJumlahJawabanSoalPegawai[$index_row]["UJIAN_BANK_SOAL_ID"];
                          $tempBankSoalId= $arrDataJumlahJawabanSoalPegawai[$index_row]["BANK_SOAL_ID"];
                          $tempBankSoalPilihanDetilId= $arrDataJumlahJawabanSoalPegawai[$index_row]["BANK_SOAL_PILIHAN_ID"];

                          $tempChecked= setInfoChecked($tempBankSoalPilihanId, $tempBankSoalPilihanDetilId);
                    ?>
                    <label class="gambar-kecil">
                      <span>
                        <?
                          if($tempPathSoal == ""){}
                          else
                          {
                            if(file_exists($tempPathGambar.$tempPathSoal))
                            {
                              ?>
                              <img src="<?=$tempPathGambar.$tempPathSoal?>" width="55" height="53" />
                              <?
                            }
                          }
                          ?>
                      </span> 
                    </label>
                    <?
                        }
                    }
                    ?>
                  </div>
                </div>
                <?
                }
                ?>

            </div>
            
                <div class="area-sudah finish">
                
                      <div class="tabbable responsive">
                        <div class="tab-content">
                        <?
                        for($index_loop=0; $index_loop < $tempJumlahTahap; $index_loop++)
                        {
                          $tempUjianTahap= $arrJumlahTahap[$index_loop]["UJIAN_TAHAP_ID"];
                          $tempTipe= $arrJumlahTahap[$index_loop]["TIPE"];
                          
                          $setClassAktif= "";
                          if($index_loop==0)
                          {
                            $setClassAktif= "active";
                          }
                        ?>
                              <div class="tab-pane fade in <?=$setClassAktif?>" id="tab<?=$tempUjianTahap?>">
                                <?
                                $arrayJawaban= '';
                                $arrayJawaban= in_array_column($tempUjianTahap, "UJIAN_TAHAP_ID", $arrJumlahSoalPegawai);
                                // print_r($arrayJawaban);
                                if($arrayJawaban == ''){}
                                else
                                {
                                  for($index_loop_detil=0; $index_loop_detil < count($arrayJawaban); $index_loop_detil++)
                                  {
                                    $index_row= $arrayJawaban[$index_loop_detil];

                                    // print_r($arrJumlahSoalPegawai[$index_row]);
                                    $tempNomor= $arrJumlahSoalPegawai[$index_row]["NOMOR"];
                                    $tempUjianId= $arrJumlahSoalPegawai[$index_row]["UJIAN_ID"];
                                    $tempUjianBankSoalId= $arrJumlahSoalPegawai[$index_row]["UJIAN_BANK_SOAL_ID"];
                                    $tempBankSoalId= $arrJumlahSoalPegawai[$index_row]["BANK_SOAL_ID"];
                                    $tempBankSoalPilihanId= $arrJumlahSoalPegawai[$index_row]["BANK_SOAL_PILIHAN_ID"];
                                    $tempJumlahData= $arrJumlahSoalPegawai[$index_row]["JUMLAH_DATA"];
                                  ?>
                                  <div class="itemlookup">
                                    <?
                                    if($tempJumlahData == "0")
                                    // if($tempBankSoalPilihanId != "")
                                    {
                                    ?>
                                    <a href="#<?=$tempNomor?>" id="reqHrefNomor<?=$tempNomor?>"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-circle"></i>  <?=$tempNomor?></a>
                                    <?
                                    }
                                    else
                                    {
                                    ?>
                                    <a href="#<?=$tempNomor?>" id="reqHrefNomor<?=$tempNomor?>" class="sudah"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-check-circle"></i>  <?=$tempNomor?></a>
                                    <?
                                    }
                                    ?>
                                  </div>
                                  <?
                                  }
                                  ?>
                                  
                        <?
                        }
                        ?>
                      </div>
                    <?
                    }
                    ?>
                      
                    </div>
                  </div> 
                    
                  
                </div>
            </div>
            
            <?php /*?><div class="area-finish">
            Anda telah selesai<br>mengikuti ujian online
            </div><?php */?>
            <div class="area-finish">
            Anda telah menyelesaikan ujian latihan <!-- <br><?=$tempTipe?> -->
            </div>
      
        </div>
        
      <div class="area-prev-next">
            <div class="kembali-home">
                <?
        if($tempJumlahPegawaiSelesaiTahap > 0)
        {
                ?>
                <span class="menupilihan-data" id="reqIdSelesai" style="float:right; margin-left:10px;"><a href="?pg=ujian_online&reqId=<?=$reqUjianTahapId?>" style="background:#06BB49;">Mulai Ujian &raquo;</a></span>
                <?
        }
                ?>
              <a style="display: none;" class="link-home" href="?pg=dashboard"><i class="fa fa-home"></i> Home <!--&raquo;--></a>
            </div>
        </div>
    
    </div>
</div>

<!-- RESPONSIVE TAB MASTER -->
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../WEB/lib-ujian/responsive-tabs-master/example/assets/js/jquery.js"></script>
    <script src="../WEB/lib-ujian/responsive-tabs-master/example/assets/js/bootstrap.min.js"></script>
    
    <script>
      
window.log=function(){log.history=log.history||[];log.history.push(arguments);if(this.console){console.log(Array.prototype.slice.call(arguments))}};

/*!
 * jQuery plug-in to turn bootstrap tabbed navigation into responsive tabbed navigation
 * Original author: @stephen_thomas
 * Plugin Boilerplate: http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 * Additional Boilerplate: http://f6design.com/journal/2012/05/06/a-jquery-plugin-boilerplate/
 * Comments from boilerplate sources retained
 * Licensed under the MIT license
 */


// the semi-colon before the function invocation is a safety 
// net against concatenated scripts and/or other plugins 
// that are not closed properly.
;(function ( $, window, document, undefined ) {
  
    // undefined is used here as the undefined global 
    // variable in ECMAScript 3 and is mutable (i.e. it can 
    // be changed by someone else). undefined isn't really 
    // being passed in so we can ensure that its value is 
    // truly undefined. In ES5, undefined can no longer be 
    // modified.

    // window and document are passed through as local 
    // variables rather than as globals, because this (slightly) 
    // quickens the resolution process and can be more 
    // efficiently minified (especially when both are 
    // regularly referenced in your plugin).


    // From http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/
    //
    // Strict Mode is a new feature in ECMAScript 5 that allows you to
    // place a program, or a function, in a "strict" operating context.
    // This strict context prevents certain actions from being taken
    // and throws more exceptions (generally providing the user with
    // more information and a tapered-down coding experience).
    //
    // Since ECMAScript 5 is backwards-compatible with ECMAScript 3,
    // all of the "features" that were in ECMAScript 3 that were
    // "deprecated" are just disabled (or throw errors) in strict
    // mode, instead.
    //
    // Strict mode helps out in a couple ways:
    //
    //  *  It catches some common coding bloopers, throwing exceptions.
    //  *  It prevents, or throws errors, when relatively "unsafe"
    //     actions are taken (such as gaining access to the global object).
    //  *  It disables features that are confusing or poorly thought out.
    
    "use strict";

    var pluginName = 'responsiveTabs';

    // The plug-in itself is implemented as an object. Here's the
    // constructor function

    function Plugin(element, options) {

        // Members
        var el = element,      // DOM version of element
           $el = $(element),   // jQuery version of element
           windowSize;         // last measured window size

        // Extend default options with those supplied by user.
        options = $.extend({}, $.fn[pluginName].defaults, options);
        
        // Slide the tab itself (not the content)
        function slideTab($tabEl, inOrOut, leftOrRight) {
          
            // grab the current values for styles we might change
            var oldStyles = {
                    "margin-left": $tabEl.css('margin-left'),
                    "opacity":     $tabEl.css('opacity'),
                    "position":    $tabEl.css('position')
                },
                startAnimation = {},
                endAnimation = {};
            
            // if the tab is going away, absolute position it so the
            // replacement tab will appear in its place
            if (inOrOut === 'out') {
                $tabEl.css('position', 'absolute');
                // define where the animation should end
                endAnimation["opacity"]     = 0;
                endAnimation["margin-left"] = $(window).width();
                if (leftOrRight === 'left') { endAnimation["margin-left"] *= -1; }

            // if the tab is coming into view, position it where it can
            // start its animation and set it up to fade in
            } else if (inOrOut === 'in') {
                startAnimation["opacity"]     = 0;
                startAnimation["margin-left"] = $(window).width();
                if (leftOrRight === 'left') { startAnimation["margin-left"] *= -1; }
                $tabEl.css(startAnimation);
                // ensure the tab will be visible as it moves in
                $tabEl.show();
                // end up with the modified styles restored
                endAnimation["opacity"]     = oldStyles["opacity"];
                endAnimation["margin-left"] = oldStyles["margin-left"];
            }

            // do the animation
            $tabEl.animate(
                endAnimation,
                options.slideTime,
                function() {

                    // if the tab has gone away, hide it in the conventional
                    // way and restore the properties that we animated
                    if (inOrOut === 'out') {
                        $tabEl.hide();
                        $tabEl.css(oldStyles);
                    }
                }
            );
       }
        
        // Set the small screen (responsive) style for tabbable naviation.
        function setSmallStyle() {

            $(".nav-tabs > li",$el).css("text-align", "center");
            $(".nav-tabs > li:not(.active)",$el).hide();
            $("<a class='right tab-control'>&rsaquo;</a>").appendTo($(".nav-tabs li:not(:last-child)",$el))
              .each(function(i){
                var thisLi  = $(this).parents("ul").first().children("li:nth-child("+(i+1)+")"),
                    thisTab = $(thisLi).children("a[href]"),
                    nextLi  = $(this).parents("ul").first().children("li:nth-child("+(i+2)+")"),
                    nextTab = $(nextLi).children("a[href]");
                $(this).click(function() {
                    slideTab(thisLi, "out", "left" );
                    slideTab(nextLi, "in",  "right");
                    $(nextTab).tab('show');
                });
            });
            $("<a class='left tab-control'>&lsaquo;</a>").prependTo($(".nav-tabs li:not(:first-child)",$el))
              .each(function(i){
                var thisLi = $(this).parents("ul").first().children("li:nth-child("+(i+2)+")"),
                    thisTab = $(thisLi).children("a[href]"),
                    prevLi = $(this).parents("ul").first().children("li:nth-child("+(i+1)+")"),
                    prevTab = $(prevLi).children("a[href]");
                $(this).click(function() {
                    slideTab(thisLi, "out", "right");
                    slideTab(prevLi, "in", "left");
                    $(prevTab).tab('show');
                });
            });
            $(".nav-tabs li:first-child",$el).prepend("<span class='left tab-control-spacer'> </span>");
            $(".nav-tabs li:last-child",$el).append("<span class='right tab-control-spacer'> </span>");
        }

        // Set the large screen version of tabbable navigation;
        // this is just the bootstrap default, so all we need to do is
        // to undo any potential changes we made for a small screen
        // style.
                
        function setLargeStyle() {
          
            $(".nav-tabs > li",$el).css("text-align", "left");
            $(".nav-tabs > li:not(.active)",$el).show();
            $(".tab-control",$el).remove();
            $(".tab-control-spacer",$el).remove();

        }
 
        function windowResized() {
          
            // Although this isn't strictly necessary, let's monitor the
            // window size so we can detect when it crosses the threshold
            // that triggers re-styling. Not likely a big deal for actual
            // users, but we include the functionality for the geeks that
            // like to look at responsive web sites and mess around with
            // browser window widths.
            //
            // We're not bothering with debouncing the window resize 
            // event since we only care when a breakpoint is crossed.
            // Ignoring the other resizes effectively serves as a
            // debouncer.
                        
            var newWidth = $('body').width();
            if ( (windowSize > options.maxSmallWidth) && 
                 (newWidth <= options.maxSmallWidth) )  {

                setSmallStyle();

            } else if ( (windowSize <= options.maxSmallWidth) && 
                        (newWidth > options.maxSmallWidth) ) {

                setLargeStyle();
                    
            }
            windowSize = newWidth;
        }

        // Initialize plugin.
        function init() {
            // keep track of the window size so we can tell when it crosses a breakpoint
            windowSize = $('body').width();
            
            // default is large window styling; adjust if appropriate
            if (windowSize <= options.maxSmallWidth) {
                setSmallStyle();
            }
            
            // track window size changes to look for breakpoints
            $(window).on('resize', windowResized);
            
            hook('onInit');

        }
 
        // Get/set a plugin option.
        // Get usage: $('#el').demoplugin('option', 'key');
        // Set usage: $('#el').demoplugin('option', 'key', value);
    
        function option (key, val) {
            if (val) {
                options[key] = val;
            } else {
                return options[key];
            }
        }
 
        // Destroy plugin.
        // Usage: $('#el').demoplugin('destroy');

        function destroy() {
            // Clean up by removing the event handlers we've added
            $(window).off('resize', windowResized);
            
            // restore styles and DOM
            setLargeStyle();
                
            // Iterate over each matching element.
            $el.each(function() {
                var el = this,
                   $el = $(this);
     
                hook('onDestroy');
                
                // Remove Plugin instance from the element.
                $el.removeData('plugin_' + pluginName);
            });
        }
 
        // Callback hooks.
        // Usage: In the defaults object specify a callback function:
        // hookName: function() {}
        // Then somewhere in the plugin trigger the callback:
        // hook('hookName');
    
        function hook(hookName) {
            if (options[hookName] !== undefined) {
                // Call the user defined function.
                // Scope is set to the jQuery element we are operating on.
                options[hookName].call(el);
            }
        }
 
        // Initialize the plugin instance.
        init();
 
        // Expose methods of Plugin we wish to be public.
        return {
            option: option,
            destroy: destroy
        };
    }
 
    // Build the plugin here 

    $.fn[pluginName] = function ( options ) {

        // If the first parameter is a string, treat this as a call to
        // a public method. The first parameter is the method name and
        // following parameters are arguments for the method.
  
        if (typeof arguments[0] === 'string') {
            var methodName = arguments[0];
            var args = Array.prototype.slice.call(arguments, 1);
            var returnVal;
            this.each(function() {
                // Check that the element has a plugin instance, and that
                // the requested public method exists.
                if ( $.data(this, 'plugin_' + pluginName) && 
                     typeof $.data(this, 'plugin_' + pluginName)[methodName] === 'function' ) {
                    // Call the method of the Plugin instance, and Pass it
                    // the supplied arguments.
                    returnVal = $.data(this, 'plugin_' + pluginName)[methodName].apply(this, args);
                } else {
                    throw new Error('Method ' +  methodName + ' does not exist on jQuery.' + pluginName);
                }
            });
            if (returnVal !== undefined){
                // If the method returned a value, return the value.
                return returnVal;
            } else {
                // Otherwise, returning 'this' preserves chainability.
                return this;
            }

        // If the first parameter is an object (options), or was omitted,
        // instantiate a new instance of the plugin.
  
        } else if (typeof options === "object" || !options) {

            return this.each(function() {
                // Only allow the plugin to be instantiated once.
                if (!$.data(this, 'plugin_' + pluginName)) {
                    // Pass options to Plugin constructor, and store Plugin
                    // instance in the elements jQuery data object.
                    $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
                }
            });
        }
    };
    
    // Default plugin options.
    // Options can be overwritten when initializing plugin, by
    // passing an object literal, or after initialization:
    // $('#el').responsiveTabs('option', 'key', value);
    $.fn[pluginName].defaults = {
        maxSmallWidth: 767,   // biggest screen size for which we use "small" configuration
        slideTime: 500,       // milliseconds to slide from one tab to another
        onInit: function() {},
        onDestroy: function() {}
    };

})( jQuery, window, document );


$(document).ready(function() {
    $(".tabbable.responsive").responsiveTabs(); 
});

    </script>