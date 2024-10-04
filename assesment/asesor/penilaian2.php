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

$tempAsesorId= $userLogin->userAsesorId;

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
$tempAsesorTipeNama= $set->getField("TIPE_NAMA");
$tempAsesorNoSk= $set->getField("NO_SK");
$tempAsesorNama= $set->getField("NAMA");
$tempAsesorAlamat= $set->getField("ALAMAT");
$tempAsesorEmail= $set->getField("EMAIL");
$tempAsesorTelepon= $set->getField("TELEPON");
unset($set);

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

//$dateNow= date("d-m-Y");

$index_loop= 0;
$arrAsesor="";
//$statement= " AND (A.STATUS_PENILAIAN = '' OR A.STATUS_PENILAIAN IS NULL) AND COALESCE(B.JUMLAH_PESERTA,0) > 0 AND A.JADWAL_TES_ID IN (SELECT X.JADWAL_TES_ID FROM jadwal_asesor X WHERE X.ASESOR_ID = ".$tempAsesorId." GROUP BY X.JADWAL_TES_ID) ";
$statement= "";
$set= new JadwalAsesor();
$set->selectByParamsJumlahAsesorPegawai($statement, $tempAsesorId);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
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

<!-- SCROLLING TAB -->
<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <link href="https://code.jquery.com/ui/1.12.1/themes/flick/jquery-ui.css" rel="stylesheet" type="text/css">
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
                        <span class="judul-app"><a href="index.php"><img src="../WEB/images/logo-kemendagri.png"> Aplikasi Pelaporan Hasil Assessment</a></span>

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
                                <div class="breadcrumb"><i class="fa fa-home"></i> Home</div>
                                <div class="row profil-area" style="min-height:205px">
                                    <div class="col-md-2">
                                        <div class="profil-foto">
                                            <img id="reqImagePeserta" />
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                       <div class="judul-halaman">Profil</div>
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
                                            <td><?=$tempAsesorNama?></td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorAlamat?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorEmail?></td>
                                        </tr>
                                        <tr>
                                            <th>Telepon</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorTelepon?></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>
                    
                    <!------>
                    <div id="example_0">
                        <ul role="tablist">
                        <li role="tab"><a href="#tabs-0-1" role="presentation">CBI</a></li>
                        <li role="tab" class="ui-tabs-active ui-state-active"><a href="#tabs-0-2" role="presentation">LGD</a></li>
                        <li role="tab"><a href="#tabs-0-3" role="presentation">PA</a></li>
                        <li role="tab"><a href="#tabs-0-4" role="presentation">Psikotes</a></li>
                        <li role="tab"><a href="#tabs-0-5" role="presentation">Nilai Akhir</a></li>
                        <li role="tab"><a href="#tabs-0-6" role="presentation">Tab number 6</a></li>
                        <li role="tab"><a href="#tabs-0-7" role="presentation">And last tab number 7</a></li>
                        <li role="tab"><a href="#tabs-0-8" role="presentation">Very very long name 8</a></li>
                        <li role="tab"><a href="#tabs-0-9" role="presentation">Short name 9</a></li>
                      </ul>
                        <div id="tabs-0-1" role="tabpanel">Tab 1<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate porttitor. Fusce purus
                        leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius sagittis eros imperdiet
                        in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                        <div id="tabs-0-2" role="tabpanel">This is tab 2<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate porttitor. Fusce
                        purus leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius sagittis eros imperdiet
                        in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                        <div id="tabs-0-3" role="tabpanel">This is tab number 3<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate porttitor.
                        Fusce purus leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius sagittis eros
                        imperdiet in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                        <div id="tabs-0-4" role="tabpanel">Tab no 4<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate porttitor. Fusce purus
                        leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius sagittis eros imperdiet
                        in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                        <div id="tabs-0-5" role="tabpanel">And this is the tab number 5<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate
                        porttitor. Fusce purus leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius
                        sagittis eros imperdiet in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                        <div id="tabs-0-6" role="tabpanel">Tab number 6<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate porttitor. Fusce
                        purus leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius sagittis eros imperdiet
                        in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                        <div id="tabs-0-7" role="tabpanel">And last tab number 7<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate porttitor.
                        Fusce purus leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius sagittis eros
                        imperdiet in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                        <div id="tabs-0-8" role="tabpanel">Very very long name 8<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate porttitor.
                        Fusce purus leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius sagittis eros
                        imperdiet in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                        <div id="tabs-0-9" role="tabpanel">Short name 9<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque hendrerit vulputate porttitor. Fusce
                        purus leo, faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim, varius sagittis eros imperdiet
                        in. Vivamus semper sem at metus mattis a aliquam neque ornare. Proin sed semper lacus.</div>
                      </div>
                    <!------>
                


           </div>
           
           

       </div>
   </div>


   <div style="margin:40px">&nbsp;</div>
   
</div>
</div>
<footer class="footer">
  © 2016 Kementerian Dalam Negeri. All Rights Reserved. 
</footer>
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 

<script>
    $(function() {
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
	$(".responsive-calendar").responsiveCalendar({
     translateMonths: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
     time: '<?=$tempTahunSekarang?>-<?=$tempBulanSekarang?>',
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
		  
		  var link_url= 'index_detil.php?reqTanggalTes='+key;
		  setModal("reqTableKegiatan", link_url);
		  //alert(thisDayEvent.number);
		}
	});
});
</script>

<!-- SCROLLING TAB -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquery.ui.scrolltabs.js"></script>

<script>
var $tabs;
var scrollEnabled;
$(function () {
    // To get the random tabs label with variable length for testing the calculations
    var keywords = ['Just a tab label', 'Long string', 'Short',
        'Very very long string', 'tab', 'New tab', 'This is a new tab'];
    $('#example_0').scrollTabs({
        scrollOptions: {
            enableDebug: true,
            selectTabAfterScroll: false,
			closable: false,
        }
    });
    if (scrollEnabled) {
        $tabs = $('#example_1')
            .scrollTabs({
            scrollOptions: {
                customNavNext: '#n',
                customNavPrev: '#p',
                customNavFirst: '#f',
                customNavLast: '#l',
                easing: 'swing',
                enableDebug: false,
                closable: true,
                showFirstLastArrows: false,
                selectTabAfterScroll: true
            }
        });
        $('#example_3').scrollTabs({
            scrollOptions: {
                easing: 'swing',
                enableDebug: false,
                closable: true,
                showFirstLastArrows: false,
                selectTabAfterScroll: true
            }
        });
    }
    else {
        // example
        $tabs = $('#example_1')
            .tabs();
    }
    $('#example_2').tabs();
    // Add new tab
    $('#addTab_1').click(function () {
        var label = keywords[Math.floor(Math.random() * keywords.length)];
        var content = 'This is the content for the ' + label + '<br>Lorem ipsum dolor sit amet,' +
            ' consectetur adipiscing elit. Quisque hendrerit vulputate porttitor. Fusce purus leo,' +
            ' faucibus a sagittis congue, molestie tempus felis. Donec convallis semper enim,' +
            ' varius sagittis eros imperdiet in. Vivamus semper sem at metus mattis a' +
            ' aliquam neque ornare. Proin sed semper lacus.';
        $tabs.trigger('addTab', [label, content]);
        return false;
    });
});
</script>

</body>
</html>
