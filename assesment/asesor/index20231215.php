<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalAsesor.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
    $userLogin->retrieveUserInfo();  
}

$reqMode=httpFilterGet("reqMode");


$tempAsesorId= $userLogin->userAsesorId;
// echo $tempAsesorId; exit;
// if($tempAsesorId == "")
// {
//  echo '<script language="javascript">';
//  echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
//  echo 'top.location.href = "../main/login.php";';
//  echo '</script>';       
//  exit;
// }

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
$tempAsesorTipeNama= $set->getField("TIPE_NAMA");
$tempAsesorNoSk= $set->getField("NO_SK");
$tempAsesorNama= $set->getField("NAMA");
$tempAsesorAlamat= $set->getField("ALAMAT");
$tempAsesorEmail= $set->getField("EMAIL");
$tempAsesorTelepon= $set->getField("TELEPON");
unset($set);

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");
$reqTanggalTes= httpFilterGet("reqTanggalTes");

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempAsesorNoSk.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);
//$dateNow= date("d-m-Y");

$index_loop= 0;
$arrAsesor="";
//$statement= " AND (A.STATUS_PENILAIAN = '' OR A.STATUS_PENILAIAN IS NULL) AND COALESCE(B.JUMLAH_PESERTA,0) > 0 AND A.JADWAL_TES_ID IN (SELECT X.JADWAL_TES_ID FROM jadwal_asesor X WHERE X.ASESOR_ID = ".$tempAsesorId." GROUP BY X.JADWAL_TES_ID) ";
$statement= "";
$set= new JadwalAsesor();
if($reqMode!='administrator'){
    $set->selectByParamsJumlahAsesorPegawai($statement, $tempAsesorId);
}
else{
    $set->selectByParamsJumlahAsesorPegawaiSuper($statement, $tempAsesorId);
}
//echo $set->query;exit;
while($set->nextRow())
{
    // $arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
    $arrAsesor[$index_loop]["TANGGAL_TES"]= dateToPageCheck(datetimeToPage($set->getField("TANGGAL_TES"), "date"));
    $arrAsesor[$index_loop]["JUMLAH"]= $set->getField("JUMLAH");
    $index_loop++;
}
$jumlah_asesor= $index_loop;
// print_r($arrAsesor);exit();
//$jumlah_asesor= 0;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Aplikasi Assesment Center</title>

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

<script>
    function openPopup() {
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
  text-align:center;
  color:#FFF;
}
@media screen and (max-width:767px) {
    .footer {
        font-size:12px;
    }
}

</style>

</head>

<body>
    <div id="wrap-utama" style="height:100%;">
        <div id="main" class="container-fluid clear-top" style="height:100%;">            
            <div class="row">
                <div class="col-md-12 area-header">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="../WEB/images/logo-judul.png"> 
                            </div>
                            <div class="col-md-5" style="margin-left: -40px;">
                                <span><b>Sistem Informasi 
                                <br>Manajemen Assessment Center</b></span>
                                <hr style="margin:0px">
                                <span style="font-size: 12px;color: #009f3b ;">Provinsi Kalimantan Timur</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="area-akun">
                            Selamat datang, <strong><?=$userLogin->nama?></strong> , 
                            <a href="login.php?reqMode=submitLogout"> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin: 0px 20px;">
                <div class="col-md-1" style="margin-top: 15px;">
                    <img id="reqImagePeserta" style="width: 75px;border-radius:200px ;" />
                </div>
                <div class="col-md-11">
                    <hr style="margin-top: 0px; margin-bottom: 15px;border-top: 1px solid black;">
                    <div style="margin:10px 0px"><b>
                        <? if($data['nama']==''){?>
                            <?=$tempAsesorNama?>
                        <? } 
                        else{ ?>
                        <? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?>
                        <?}?>
                        </b>
                        <br>
                        <p style="font-size: 12px;"><?=$tempAsesorNoSk?></p>
                    </div>
                    <hr style="margin-top: 0px; margin-bottom: 10px;border-top: 1px solid black;">
                    <div class="row">
                        <div class="col-md-4" style="margin: 3px 0px;">
                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
                            Email : <? if($data['email']==''){?>
                                            <?=$tempAsesorEmail?>
                                            <? } 
                                            else{ ?>
                                            <?=$data['email']?>
                                            <?}?>
                                                
                        </div>
                        <div class="col-md-8" style="margin: 3px 0px;">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            Telepon :<? if($data['no_hape']==''){?>
                                                <?=$tempAsesorTelepon?>
                                            <? } 
                                            else{ ?>
                                            <?=$data['no_hape']?>
                                            <?}?>
                        </div>
                        <div class="col-md-4" style="margin: 3px 0px;"> <i class="fa fa-list-alt" aria-hidden="true"></i> Tipe : <?=$tempAsesorTipeNama?></div>
                        <div class="col-md-8" style="margin: 3px 0px;">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            ALamat : <? if($data['alamat']==''){?>
                                                <?=$tempAsesorAlamat?>
                                            <? } 
                                            else{ ?>
                                            <?=$data['alamat']?>
                                            <?}?>
                        </div>
                    </div>
                    <hr style="margin-bottom: 0px; margin-top: 10px;border-top: 1px solid black;">
                </div>
            </div>
            <div class="row" style="margin: 10px 20px;">
                <div class="col-md-12">
                    <div class="col-md-3" style="background-color:#0e7476; padding: 10px;border-radius: 10px;">
                       <div class="responsive-calendar" style="color: white;">
                        <div class="controls" style="margin-bottom:-10px !important">
                            <span data-head-month></span> <span data-head-year></span>
                            <a class="pull-right" data-go="next">
                                <div class="btn btn-primary" style="padding: 0px 6px;margin-left: 10px; border: solid white 0.5px; background-color: transparent;">
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                </div>
                            </a>
                            <a class="pull-right" data-go="prev">
                                <div class="btn btn-primary"  style="padding: 0px 6px;margin-left: 10px; border: solid white 0.5px; background-color: transparent;">
                                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div><hr/>
                        <div class="day-headers" style="margin-top:-15px !important">
                            <div class="day header">Sen</div>
                            <div class="day header">Sel</div>
                            <div class="day header">Rbu</div>
                            <div class="day header">Kms</div>
                            <div class="day header">Jmt</div>
                            <div class="day header">Sbt</div>
                            <div class="day header">Mng</div>
                        </div>
                        <div class="days" data-group="days">
                            <!-- the place where days will be generated -->
                        </div>
                    </div>
                    <!-- Responsive calendar - END -->

                </div>

                <div class="col-md-9">
                    <div class="judul-halaman" style="background-color: #daf2f2; color: black;border-radius: 10px 10px 0px 0px">Nama Peserta</div>
                   <div class="area-table-assesor">
                       <div id="reqTableKegiatan">
                           <table>
                               <tr>
                                   <td>Tidak Ada</td>
                               </tr>
                           </table>
                       </div>
                   </div>
               </div>

           </div>
        </div>
    </div>

    <footer class="footer" style="color: black;background-color: rgba(255, 255, 255);">
         © <? echo date('Y'); ?> Pemerintah Provinsi Kalimantan Timur. All Rights Reserved.
    </footer>
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 

<script>
      $(function() {
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
});
</script>

<link href="../WEB/lib/responsive-calendar-0.9/css/responsive-calendar.css" rel="stylesheet">
<script src="../WEB/lib/responsive-calendar-0.9/js/responsive-calendar.js"></script>
<script type="text/javascript">
    function addLeadingZero(num) {
       if (num < 10) {
           return "0" + num;
       } else {
           return "" + num;
       }
   }

   function setModal(target, link_url)
   {
       var s_url= link_url;
       var request = $.get(s_url);

       request.done(function(msg)
       {
          if(msg == ''){}
              else
              {
                 $('#'+target).html(msg);
             }
         });
    //alert(target+'--'+link_url);
}

$(document).ready(function () {
    var link_url= 'index_detil.php?reqTanggalTes=<?=$reqTanggalTes?>&reqMode=<?=$reqMode?>';
    setModal("reqTableKegiatan", link_url);
    textDate='<?=$reqTanggalTes?>';
    const myArray = textDate.split("-");
    if(textDate!=''){
        timeset=myArray[2]+"-"+myArray[1];
    }
    else{
        timeset='<?=$tempTahunSekarang?>-<?=$tempBulanSekarang?>';
    }

    $(".responsive-calendar").responsiveCalendar({

     translateMonths: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
     time: timeset,
     events: {
      "":{}
      <?
      for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
      {

          ?>
          ,"<?=$arrAsesor[$checkbox_index]["TANGGAL_TES"]?>":{"number": <?=$arrAsesor[$checkbox_index]["JUMLAH"]?>, "cssModif": "f8a406"}
          <?
      }
      ?>
  },
  onActiveDayClick: function(events) {
    var thisDayEvent, key;

          //key = $(this).data('year')+'-'+addLeadingZero( $(this).data('month') )+'-'+addLeadingZero( $(this).data('day') );
          key= addLeadingZero( $(this).data('day') )+'-'+addLeadingZero( $(this).data('month') )+'-'+$(this).data('year');
          thisDayEvent = events[key];
          
          var link_url= 'index_detil.php?reqTanggalTes='+key+'&reqMode=<?=$reqMode?>';
          setModal("reqTableKegiatan", link_url);
          //alert(thisDayEvent.number);
        }
    });
});
</script>

</body>
</html>
