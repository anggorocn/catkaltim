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
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasiPegawai.php");

$reqMode=httpFilterGet("reqMode");

// $tempAsesorId= $userLogin->userAsesorId;
$reqTanggalTes= httpFilterGet("reqTanggalTes");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqSelectPenggalianId= httpFilterGet("reqSelectPenggalianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqTanggalTes= httpFilterGet("reqTanggalTes");
$reqTipe= httpFilterGet("reqTipe");
$tempAsesorId= httpFilterGet("tempAsesorId");
$reqMode= httpFilterGet("reqMode");

if($reqTipe=='Psikotes'){
  $reqTipe='PT';
}



$statement= " AND A.tanggal_tes = '".dateToPage($reqTanggalTes)." 00:00:00'";
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

$set= new CetakanPdf();
$statement= " AND acara= '".$reqKeterangan."'";
$set->selectByParamsKeseluruhanJadwalTes($statement);
// echo $set->query; exit;
$index_loop=0;
while($set->nextRow())
{
  $arrJadwal[$index_loop]["TanggalTes"]= $set->getField("TANGGAL_TES");
  $index_loop++;
}
$TotalJadwal= $index_loop;

$set= new CetakanPdf();
$statement= " AND jt.tanggal_tes = '".dateToPage($reqTanggalTes)." 00:00:00' AND P.kode = '".$reqTipe."'";
$set->selectByParamsKeseluruhanAsesor($statement);
// echo $set->query; exit;
$index_loop=0;
while($set->nextRow())
{
  $arrAsesorDetil[$index_loop]["NAMA"]= $set->getField("NAMA");
  $arrAsesorDetil[$index_loop]["NO_SK"]= $set->getField("NO_SK");
  $index_loop++;
}
$TotalAsesor= $index_loop;

// $statement= " AND JA.JADWAL_TES_ID = ".$reqJadwalTesId." ";
$statement= " AND JA.tanggal_tes = '".dateToPage($reqTanggalTes)." 00:00:00' ";

$set= new JadwalAsesor();
if($reqMode==''){
    $set->selectByParamsDataAsesorPegawai($statement, $tempAsesorId);
}
else{
    $set->selectByParamsDataAsesorPegawaiSuper($statement, $tempAsesorId);
}
// echo $set->query;exit;
$index_loop=0;
$total_muncul=0;
while($set->nextRow())
{

    $arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
    $arrAsesor[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
    $arrAsesor[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
    $arrAsesor[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
    $arrAsesor[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
    $arrAsesor[$index_loop]["ESELON"]= $set->getField("last_eselon_id");

    $statementTugas="  AND Jt.tanggal_tes = '".dateToPage($reqTanggalTes)." 00:00:00' and b.pegawai_id=".$set->getField("PEGAWAI_ID");
    if($reqMode==''){
      $statementTugas.= " and A.ASESOR_ID = ".$tempAsesorId;
    }
    $setTugas= new JadwalAsesor();
    $setTugas->selectByParamsTugas($statementTugas);    
    // echo $setTugas->query; exit;
    $tugas="";
    while($setTugas->nextRow())
    {
        if($tugas==''){
            $tandabaca="";
        }
        else{
            $tandabaca=", ";
        }
        if($reqMode==''){
            if($arrAsesor[$index_loop]["MUNCUL"]==0){
              if($setTugas->getField("kode")==$reqTipe){
                  $arrAsesor[$index_loop]["MUNCUL"]= 1;
              $total_muncul++;
              }
              else{
                  $arrAsesor[$index_loop]["MUNCUL"]= 0;
              }
            }
        }
        else
        {
            $arrAsesor[$index_loop]["MUNCUL"]= 1;
            $total_muncul++;
        }
    }

    $index_loop++;
}
$total_pegawai=$index_loop;

if($reqMode!=''){
  $total_muncul=$total_pegawai;
}
// echo "xxxx".$total_muncul; exit;
// print_r($arrAsesor); exit;

?>
<body>
  
<p style="text-align: center;"><b>Laporan Pelaksanaan Kegiatan <?=$reqTipe?></b></p>

<p style="text-align:justify;">Pada Pelaksanaan <?=$reqKeterangan?> pada tanggal 
  <?if($TotalJadwal==1){
    echo getDateIndo($arrJadwal[0]["TanggalTes"]);
  }
  else if($reqTanggalTes!=''){?>
    <?=getDateIndo($reqTanggalTes)?>
  <?}
    else{?>
    16 s.d. 19 November 2021
  <?}?>

      dengan jumlah peserta sebanyak <?=$total_pegawai?> orang, dengan pembagian ke <?=count($arrAsesorDetil)?> orang Assessor yaitu :</p>


<table style=" border-collapse: collapse;width: 100%;">
  <tr>
    <td style="border:0.5px solid black;text-align: center;">No</td>
    <td style="border:0.5px solid black;text-align: center;">Nama</td>
    <td style="border:0.5px solid black;text-align: center;">Nip</td>
  </tr>
  <?for ($i=0; $i<count($arrAsesorDetil) ;$i++){?>
  <tr>
    <td style="border:0.5px solid black;text-align: center;"><?=$i+1?></td>
    <td style="border:0.5px solid black;"><?=$arrAsesorDetil[$i]['NAMA']?> </td>
    <td style="border:0.5px solid black;text-align: center;"><?=$arrAsesorDetil[$i]['NO_SK']?></td>
  </tr>
  <?}?>
</table>

<?if($reqMode=''){?>
  <p style="text-align:justify;">Berdasarkan pembagian beban kerja asessor tersebut di lakukan penilaian <?=$arrAsesor[0]["PENGGALIAN_NAMA"]?> dengan jumlah peserta sebanyak <?=$total_muncul?> orang. Berikut Nama Peserta yang telah dilakukan penilaian
<?=$arrAsesor[0]["PENGGALIAN_NAMA"]?> :</p>
<?}else{?>
  <p style="text-align:justify;">Berdasarkan pembagian beban kerja asessor tersebut dilakukan penilaian dan assesor meeting dengan jumlah peserta sebanyak <?=$total_muncul?> orang. Berikut Nama Peserta yang telah dilakukan penilaian dan assesor meeting  :</p>
<?}?>
<table style=" border-collapse: collapse;width: 100%;margin-bottom: 15px;">
  <tr>
    <td style="border:0.5px solid black;text-align: center;">No</td>
    <td style="border:0.5px solid black;text-align: center;width: 40%;">Nama</td>
    <td style="border:0.5px solid black;text-align: center;width: 40%;">Nip</td>
    <td style="border:0.5px solid black;text-align: center;">Simulasi Yang Dipakai</td>
  </tr>
<?
$no=1;
for($i=0;$i<$total_pegawai;$i++){
  if($reqMode==''){
   if($arrAsesor[$i]["MUNCUL"]=='1'){?>  
    <tr>
      <td style="border:0.5px solid black;text-align: center;"><?=$no?></td>
      <td style="border:0.5px solid black;"><?=$arrAsesor[$i]["NAMA_PEGAWAI"]?></td>
      <td style="border:0.5px solid black;text-align: center;"><?=$arrAsesor[$i]["NIP_BARU"]?></td>
      <td style="border:0.5px solid black;text-align: center;"><?=$reqTipe?></td>
    </tr>
    <?
    $no++;
    }
  }
  else{?>
  <tr>
      <td style="border:0.5px solid black;text-align: center;"><?=$no?></td>
      <td style="border:0.5px solid black;"><?=$arrAsesor[$i]["NAMA_PEGAWAI"]?></td>
      <td style="border:0.5px solid black;text-align: center;"><?=$arrAsesor[$i]["NIP_BARU"]?></td>
      <td style="border:0.5px solid black;text-align: center;"><?=$reqTipe?></td>
    </tr>
  <?
  $no++;
  }
}
?>
</table>


Berikut bukti hasil penilaian <?=$arrAsesor[0]["PENGGALIAN_NAMA"]?>
