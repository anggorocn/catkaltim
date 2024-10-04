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
// 	echo '<script language="javascript">';
// 	echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
// 	echo 'top.location.href = "../main/login.php";';
// 	echo '</script>';		
// 	exit;
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

<!-- <script src="../WEB/lib/emodal/eModal.js"></script> -->
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

</head>

<body>

     <div id="wrap-utama" style="height:100%; ">
        <div id="main" class="container-fluid clear-top" style="height:100%;">

            <div class="row">
                <div class="col-md-12">
                    <div class="area-header">
                        <span class="judul-app"><a href="index.php"><img src="../WEB/images/logo-judul.png"> Pelaporan Hasil Assessment</a></span>

                        <div class="area-akun" style="margin-top: 15px;">
                            <?if($tempAsesorId!=''){?>
                                Selamat datang, <strong><? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?> </strong> 
                                <a href="../main/login.php?reqMode=submitLogout">( Logout )</a>
                            <?}
                            else{?>
                                <span id="account-new"><a href="../main/index.php" ><img src="../WEB/images/icon-main-menu2.png" style="width:40px"> </a></span>
                            <?}?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row" style="height:calc(100% - 20px);">
                <div class="col-md-12" style="height:100%;">

                    <div class="container area-menu-app">
                        <div class="row">
                            <div class="col-md-12" style=" background: rgba(255, 255, 255, 0.7)">
                                <div class="breadcrumb">
                                    <i class="fa fa-home"></i> Home
                                </div>
                                <div class="row profil-area" style="min-height:205px">
                                    <div class="col-md-2">
                                        <div class="profil-foto">
                                            <img id="reqImagePeserta" style="height:200px; width: 150px;" />
                                        </div>
                                    </div>
                                    <?if($tempAsesorId!=''){?>
                                    <div class="col-md-10">
                                        <div class="judul-halaman" style="color: black">Profil</div>
                                        <table class="profil">
                                        <tr>
                                            <th style="width:165px">Tipe</th>
                                            <th style="width:5px">:</th>
                                            <td><?=$tempAsesorTipeNama?></td>
                                        </tr>
                                        <tr>
                                            <th>No SK</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorNoSk?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <th>:</th>
                                            <? if($data['nama']==''){?>
                                                <td><?=$tempAsesorNama?></td>
                                            <? } 
                                            else{ ?>
                                            <td><? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?> </td>
                                            <?}?>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <th>:</th>
                                            <? if($data['alamat']==''){?>
                                                <td><?=$tempAsesorAlamat?></td>
                                            <? } 
                                            else{ ?>
                                            <td><?=$data['alamat']?></td>
                                            <?}?>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <th>:</th>
                                            <? if($data['email']==''){?>
                                                <td><?=$tempAsesorEmail?></td>
                                            <? } 
                                            else{ ?>
                                            <td><?=$data['email']?></td>
                                            <?}?>
                                        </tr>
                                        <tr>
                                            <th>Telepon</th>
                                            <th>:</th>
                                            <? if($data['no_hape']==''){?>
                                                <td><?=$tempAsesorTelepon?></td>
                                            <? } 
                                            else{ ?>
                                            <td><?=$data['no_hape']?></td>
                                            <?}?>
                                        </tr>
                                        </table>
                                    </div>
                                    <?}
                                    else{?>
                                     <div class="col-md-10">
                                        <div class="judul-halaman" style="color: black">Profil</div>
                                        <table class="profil">
                                            <tr>
                                                <th style="width:165px">Tipe</th>
                                                <th style="width:5px">:</th>
                                                <td>Super Admin</td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <th>:</th>
                                                <td>Super Admin</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <?}?>

                            </div>

                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12" style="background: rgba(255, 255, 255, 0.7)">
                            <div class="col-md-3">
                               <div class="judul-halaman" style="color: black">Kalender</div>
                               <!-- Responsive calendar - START -->
                               <div class="responsive-calendar">
                                <div class="controls" style="margin-bottom:-10px !important">
                                    <a class="pull-left" data-go="prev"><div class="btn btn-primary">Prev</div></a>
                                    <h4><span data-head-month></span> <span data-head-year></span></h4>
                                    <a class="pull-right" data-go="next"><div class="btn btn-primary">Next</div></a>
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
                           <div class="judul-halaman" style="color: black">Nama Peserta</div>
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

       </div>
   </div>


   <div style="margin:40px">&nbsp;</div>
</div>
</div>
<footer class="footer" style="background: rgba(255, 255, 255, 0.7); color: black">
  © 2021 Pemprov Kaltim. All Rights Reserved. 
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
