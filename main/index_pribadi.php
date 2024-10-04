<?
include_once("../WEB/setup/defines.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Dokumen.php");
include_once("../WEB/classes/base/UploadFile.php");
include_once("../WEB/classes/base/Pelamar.php");
// include_once("../WEB/classes/base/Jabatan.php");
// include_once("../WEB/classes/base/CabangP3.php");
// include_once("../WEB/classes/base/Visitor.php");
// include_once("../WEB/classes/base/UsersBase.php");

include_once("../WEB/classes/base-diklat/KontenInformasi.php");

$pg = httpFilterRequest("pg");
$reqId = httpFilterGet("reqId");
$menu = httpFilterRequest("menu");

$reqMode = httpFilterRequest("reqMode");
$reqUser = httpFilterRequest("reqUser");
$reqPasswd = httpFilterRequest("reqPasswd");

$bahasa = httpFilterGet("bahasa");
$lang = $_SESSION['lang'];

$get_url = explode("?", $_SERVER['REQUEST_URI']);
$url = $get_url[1];
if($bahasa == "")
{}
else
{
	$_SESSION["lang"] = $bahasa;
	if($page == "home" || $page == "")
		header("location: index.php");
	else
	{
		$translate = array("&bahasa=id", "&bahasa=en");
		$hasil = str_replace($translate, "" ,$url);
		$location = "index.php?".$hasil;
		header('Location: '.$location);
	}
	//header("url= index.php?$url");
}

if($reqMode == "submitLogin" && $reqUser != "" && $reqPasswd != "")
{
	$userLogin->resetLogin();
	if ($userLogin->verifyUserLogin(strtolower($reqUser), $reqPasswd)) 
	{
		header("location:index.php");	
		exit;		
	}
	else
	{
		echo '<script language="javascript">';
		echo 'alert("Username atau password anda masih salah.");';
		echo 'top.location.href = "index.php";';
		echo '</script>';		
		exit;		
	}		
}
elseif($reqMode == "submitLupa" && $reqUser != "") 
{
	// $set= new Pelamar();
	// $set->selectByParams(array("EMAIL1"=>$reqUser));
	// $set->firstRow();
	// $tempEmail1= $set->getField("EMAIL1");
	// unset($set);
	
	// if($tempEmail1 == ""){}
	// else
	// {
	// }
}
else if($reqMode == "submitLogout")
{
	$userLogin->resetLogin();
	$userLogin->emptyUsrSessions();
}

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserFasilitatorId= $userLogin->userFasilitatorId;
$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserStatusJenis= $userLogin->userStatusJenis;
$tempUserNik= $userLogin->userNik;

if($tempUserStatusJenis == "1")
{
	$tempinfologin= "NIP";
	$tempinfologindetil= $tempUserPelamarNip;
}
else
{
	$tempinfologin= "NIK";
	$tempinfologindetil= $tempUserNik;
}

$tempKondisiSudahLogin= "";
if($tempUserPelamarId != "" || $tempUserFasilitatorId != "")
{
	$tempKondisiSudahLogin= "1";
}

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlfoto= $data->urlConfig->main->urlfoto;
$urlfoto.="/".$tempUserPelamarId."/";
$urldashboard= $data->urlConfig->main->urldashboard;

$upload_file = new UploadFile();
$upload_file->selectByParams(array('A.PEGAWAI_ID'=>$tempUserPelamarId), -1, -1);
// echo $upload_file->query;exit;
$upload_file->firstRow();
$tempPegawaiFoto= $upload_file->getField("LINK_FOTO");

 // echo $tempPegawaiFoto;exit();

// echo $userLogin->userPelamarId;exit();
if($tempUserPelamarId)
{
	// echo $urlfoto;exit();

	/*$pelamar= new Pelamar();
	$tempIsPernyataan= $pelamar->getFieldById("PAKTA_INTEGRITAS", $tempUserPelamarId);
	$statement= " AND A.PELAMAR_ID = ".$tempUserPelamarId;
	$pelamar->selectByParams(array(), -1,-1, $statement);
	$pelamar->firstRow();
	$tempStatusSyaratKetentuan= $pelamar->getField("STATUS_SYARAT_KETENTUAN");
	//echo $pelamar->getField("STATUS_SYARAT_KETENTUAN")."-".$pelamar->getField("PELAMAR_ID");exit;
	
	$user_base = new UsersBase();
	$user_base->selectByParamsSimple(array("MD5(USER_LOGIN_ID::VARCHAR)" => md5($userLogin->UID)));
	$user_base->firstRow();
	$tempPassword = $user_base->getField("USER_PASS");
	
	if($tempPassword == md5("admin"))
	{		
		if($pg == "data_password"){}
		else
		{
			echo '<script language="javascript">';
			echo 'document.location.href = "index.php?pg=data_password";';
			echo '</script>';
			exit;
		}
	}
	else
	{
		if($tempStatusSyaratKetentuan == "")
		{
			if($pg == "content" && $reqId == "1"){}
			else
			{
				echo '<script language="javascript">';
				echo 'document.location.href = "index.php?pg=content&reqId=1";';
				echo '</script>';
				exit;
			}
		}
	}*/
}

$arrayJudul= "";
$arrayJudul= setJudul($lang);

$set= new KontenInformasi();
$index_loop= 0;
$arrImage=array();
$statement= " AND COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL";
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrImage[$index_loop]["KETERANGAN"]= $set->getField("KETERANGAN");
	$arrImage[$index_loop]["PATH"]= $set->getField("PATH");
	$index_loop++;
}
$jumlah_image= $index_loop;

// echo "asd";exit();
?>
<!DOCTYPE html>
<html lang="en"><head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Assesment Center</title>
    <link href="../WEB/images/favicon.ico" rel="shortcut icon">

    <!-- Bootstrap Core CSS -->
    <link href="../WEB/lib/startbootstrap-blog-post-1.0.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="../WEB/lib/startbootstrap-freelancer-1.0.3/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../WEB/lib/startbootstrap-blog-post-1.0.4/css/blog-post.css" rel="stylesheet">

    <link href="../WEB/css/gaya-rekrutmen.css" rel="stylesheet">
    <link href="../WEB/css/rekrutmen.css" rel="stylesheet">
    <link href="../WEB/css/halaman.css" rel="stylesheet" type="text/css">
    
    <style>
	.full-width-div {
		position: relative;
		*width: 100%;
		*height: 100%;
		*left: 0;
		
		*border:2px solid red !important;
	}
	col-lg-12{
		padding:0 0px;	
	}
	
	/****/
	.area-atas span:nth-child(1){
		*display:inline-block;
		*border:1px solid red;
		*float:left;
	}
	.area-atas span img{
		*border:2px solid red;
		position:absolute;
		*display:inline-block;
	}
	.area-atas span:nth-child(2){
		*border:1px solid cyan;
		color:#FFF;
		position:absolute;
		left:120px;
		text-transform:uppercase;
		font-size:26px;
		font-style:italic;
		*display:inline-block;
	}
	
	/****/
	a.navbar-brand{
		margin-left:110px !important;
		font-size:17px;
		font-family:Arial, Helvetica, sans-serif;
		text-transform:uppercase;
		
		*background:#9C3;
		color:#FFF !important;
	}
	
	.nav.navbar-nav.navbar-right li a{
		color:#FFF;
		text-transform:uppercase;
	}
	@media screen and (max-width:767px) {
		.container.atas div.navbar-header{
			padding-left:10px;
			padding-right:10px;
		}
		a.navbar-brand{
			margin-left:0px !important;
	
		}
	}
	</style>
    
    <!-- jQuery -->
    <script src="../WEB/lib/startbootstrap-blog-post-1.0.4/js/jquery.js"></script>
	
    <!-- Bootstrap Core JavaScript -->
    <script src="../WEB/lib/startbootstrap-blog-post-1.0.4/js/bootstrap.min.js"></script>
    
     <!-- jQuery Version 1.11.1 -->
    <script src="../WEB/lib-ujian/bootstrap/jquery.js"></script>
    <script type='text/javascript' src="../WEB/lib-ujian/bootstrap/bootstrap.js"></script> 
    
    <script src="../WEB/lib-ujian/emodal/eModal.js"></script>
    <script>
	function openPopup() {
		//document.getElementById("demo").innerHTML = "Hello World";
		//alert('hhh');
		// Display a ajax modal, with a title
		eModal.ajax('ubah_password.php', 'UBAH PASSWORD')
		//  .then(ajaxOnLoadCallback);
	}
	
	function openPopupFile(link_file, judul) {
		//document.getElementById("demo").innerHTML = "Hello World";
		//alert('hhh');
		// Display a ajax modal, with a title
		eModal.ajax(link_file, judul)
		//  .then(ajaxOnLoadCallback);
	}
	</script>    
        
    <!-- FIXED MENU -->
    <script type='text/javascript'>//<![CDATA[
	$(window).load(function(){
		$(document).ready(function() {
			
			$(window).scroll(function () {
				//if you hard code, then use console
				//.log to determine when you want the 
				//nav bar to stick.  
				// console.log($(window).scrollTop())
				if ($(window).scrollTop() < 79) {
					$('.area-atas').fadeIn();
				}
				if ($(window).scrollTop() > 80) {
					$('.area-atas').fadeOut();
				}
			});
		});
	});//]]> 
	
	function reloadCaptchaDinamis(value, json)
	{
		$('#'+value).attr('src', json+'?random=' + (new Date).getTime()+'width=100&amp;height=40&amp;characters=5');
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
	
	</script>
    
   
    <link rel="stylesheet" href="../WEB/lib/GlossyAccordionMenu/glossymenu.css" type="text/css" />
	
<!-- PAGINATION -->
<link rel="stylesheet" href="../WEB/lib/pagination/css/style.css"> <!-- Resource style -->
<script src="../WEB/lib/pagination/js/modernizr.js"></script> <!-- Modernizr -->

<style>
.col-md-6.career-nama{
	padding-left:0px;
}
@media screen and (max-width:767px) {
	.nav.navbar-nav.navbar-right li a{
		*border:2px solid red;
		padding-left:25px;
		*padding-right:15px;
	}
	.col-md-6.career-nama span:nth-child(1) img{
		height:60px;
		margin-left:25px;
	}
	.col-md-6.career-nama span:nth-child(2){
		font-size:11px;
		width:100px;
		line-height:normal !important;
		margin-top:7px;
	}	
	a.link-web-utama{
		margin-right:25px;
	}
	.container-fluid.banner-home{
		display:none;
		height: 30px;
	}
	.row.main-home{
		margin-top:0px !important;
	}
	.row.main-detil{
		margin-top:0px !important;
	}
	
	/****/
	footer .col-lg-8.text-left p{
		text-align:center !important;
		border-bottom:1px solid #2c7bbf;
		padding-bottom:10px;
	}
	footer .col-md-4.text-right{
		text-align:center !important;
	}
}
</style>

<style>
@media screen and (max-width:767px) {

	html, body{
		*overflow:hidden !important;
	}
}
</style>

<!-- STEP PROGRESS -->
<link href="../WEB/lib/css-progress-wizard-master/css/progress-wizard.min.css" rel="stylesheet">

<style>
.foto-sidebar{
	*border: 1px solid red;
	
	width: 130px;
	height: 130px;
	
	-webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	border-radius: 50%;
	
	overflow: hidden;
	
	margin: 0 auto;
}
.foto-sidebar img{
	width: 100% !important;
	height: auto !important;
}
</style>

	<link rel="stylesheet" href="../WEB/css/gaya-baru.css" type="text/css">

</head>
<?
// if($tempUserPelamarId == "")
if($tempKondisiSudahLogin == "")
{
	?>
<!--<body style="padding-top: 70px;">-->
<!-- <body style="padding-top: 110px;"> -->
<!-- <body style="padding-top: 110px;"> -->
    <?
	
	}
else
{
?>
<!-- <body style="padding-top: 110px;"> -->
<!-- <body style="padding-top: 130px;"> -->
<?
}
?>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style=" border-bottom:none !important;">
        <!-- <div class="container-fluid" style="padding-left:0px; padding-right:0px; background:#76080e;"> -->
        
        	<!-- <div class="row area-atas" > -->
            
            	<!-- <div class="container atas"> -->
                    <!-- <div class="col-md-6 career-nama"> -->
						<!-- <div style="height: 110px; display: flex; justify-content: center; align-items: center; "> -->
						<!-- </div> -->
                    <!-- </div> -->
                <!-- </div> -->
            </div>
            
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="container atas">
            	<div class="navbar-header" style="">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- <a class="navbar-brand" href="index.php">SIMPEKA</a> -->
                    <?php /*?><a href="index.php?<?=$get_url[1]?>&bahasa=id" title="Bahasa Indonesia"><img src="../WEB/images/icn-id.png" /></a> 
                    <a href="index.php?<?=$get_url[1]?>&bahasa=en" title="English"><img src="../WEB/images/icn-en.png" /></a><?php */?>
                </div>
                
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="">
                    <ul class="nav navbar-nav navbar-left">
                    	<img src="../WEB/images/logo-eoffice-bali.png">
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="https://bkd.kaltimprov.go.id/" target="_blank">Website Utama</a></li>
                        <li><a href="index.php"><?=$arrayJudul["index"]["beranda"]?></a></li>
                        <!-- <li>
                        	<a href="?pg=content&reqId=1"><?=$arrayJudul["index"]["syaratketentuan"]?></a>
                        </li>
                        <li>
                        	<a href="?pg=informasi"><?=$arrayJudul["index"]["informasi"]?></a>
                        </li>
                        <?
						if($tempUserPelamarId == ""){}
						else
						{
                        ?>
                        <li>
                            <a href="?pg=pengumuman_seleksi_administrasi">Pengumuman</a>
                        </li>
                        <?
						}
                        ?>
                         
                        <li>
                            <a href="?pg=faq">FAQ</a>
                        </li>-->
                        <!-- <li class="area-pilih-bahasa">
                        	<a href="index.php?<?=$get_url[1]?>&bahasa=id" title="Bahasa Indonesia"><img src="../WEB/images/icn-id.png" /></a> 
		                    <a href="index.php?<?=$get_url[1]?>&bahasa=en" title="English"><img src="../WEB/images/icn-en.png" /></a>
                        </li> -->
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            
        </div>
        <!-- /.container -->
		<?
        // if($tempUserPelamarId == "")
        if($tempKondisiSudahLogin == "")
        {}
		else
		{
        ?>        
        <!-- <div class="container-fluid" style=" background:#ececec; height:60px; padding-top:10px; border-bottom:1px solid #c3c3c3;">
        	<ul class="progress-indicator" style="width:80%; margin:0 auto;">
                <li class="completed">
                    <span class="bubble"></span>
                    Registrasi
                </li>
                <li class="completed">
                    <span class="bubble"></span>
                    Login
                </li>
                <li class="<? if($kurang_entri > 0) { echo 'active'; } else { echo 'completed'; }?>">
                    <span class="bubble"></span>
                    Isian Dokumen
                </li>
                <li <? if($kurang_entri > 0)  { ?>  <? } else { ?>  class="active" <? } ?>>
                    <span class="bubble"></span>
                    Pilih Diklat
                </li>
                <li <? if($status_cetak > 0)  { ?> class="active" <? } else { ?> <? } ?>>
                    <span class="bubble"></span>
                    Cetak Identitas Calon Karyawan (ICK)
                </li>
            </ul>
            
        </div> -->
        <?
		}
		?>
        
        
    </nav>
	
    <?
    if($pg == "" || $pg == "home"){
	?>
    <div class="container-fluid banner-home">
    	<div class="row">
        	<div class="col-lg-12" style="padding-left:0px; padding-right:0px;">
            	<div class="full-width-div">
                    <ul class="rslides" id="slider1" style="width:100% height:100%;">
                    	<li><img src="../WEB/images/slide0.png" alt=""></li>
                        <li><img src="../WEB/images/slide1.png" alt=""></li>
                        <li><img src="../WEB/images/slide2.png" alt=""></li>
                    </ul>                    
                </div>
            </div>
        </div>
    </div>
    <?
	}
	?>
    
    <!-- Page Content -->
    <div class="container" style="background:#FFF;">
		<?
		if($pg == "" || $pg == "home"){
		?>
        <div class="row main-home sisi-utama">
        <?
		} else {
		?>
        <div class="row main-detil sisi-utama">
        <?
		}
		?>
        	
        	<?php
            $includePage = $page_to_load->loadPage();
            include_once($includePage);
            ?>

            <!-- Blog Sidebar Widgets Column -->
           
               
						
                

            </div>

        </div>
        <!-- /.row -->
        
    </div>
    <!-- /.container -->
    
    <div class="container-fluid footer">
    	<!-- Footer -->
        <footer>
        	<div class="container">
            	<div class="row">
                    <div class="col-lg-8 text-left">
                        <span class="logo-footer"><img src="../WEB/images/logo-aps-rekrutmen-bali.png"></span><p>Copyright &copy; 2021. Badan Kepegawaian Daerah Pemerintah Provinsi Kalimantan Timur<br>All Right Reserved.</p>
                    </div>
                    <!-- <div class="col-md-4 text-right">
                        CALL CENTER : T(62-21)65866496 F:(62-21)65866497<br>
                        
                        Dilayani setiap hari kerja pukul :<br>
                        <span>08.00 – 17.00</span> WIB  
                    </div> -->
                </div>
            </div>
            
            <!-- /.row -->
        </footer>

    </div>

<!-- RESPONSIVE SLIDE -->
<link rel="stylesheet" href="../WEB/lib/ResponsiveSlides.js-master/responsiveslides.css">
<script src="../WEB/lib/ResponsiveSlides.js-master/responsiveslides.min.js"></script>
<script>
// You can also use "$(window).load(function() {"
$(function () {

  // Slideshow 1
  $("#slider1").responsiveSlides({
    //maxwidth: 800,
    speed: 800
  });

  <?
  // $tempPegawaiFoto= $urlfoto.$tempUserPelamarNip.".png";
  if(file_exists("$tempPegawaiFoto"))
  {
  ?>
  	$("#reqImagePeserta").attr("src", "<?=$tempPegawaiFoto?>");
  <?
  }
  ?>

});
</script>

<!-- SCROLLING TAB -->
<?
if($pg == "home_detil"){
?>
  <link href="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <link href="https://code.jquery.com/ui/1.12.1/themes/flick/jquery-ui.css" rel="stylesheet" type="text/css">
  <!-- <link href="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquery-ui.css" rel="stylesheet" type="text/css"> -->
  <link rel="stylesheet" href="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/css/style.css" type="text/css">
  <script src="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquery.mousewheel.min.js"></script>
  <script type="text/javascript" src="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquery.ui.scrolltabs.js"></script>
  
  <!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->
  <!-- <script src="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquery-ui.min.js"></script> -->

  <!-- <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <link href="https://code.jquery.com/ui/1.12.1/themes/flick/jquery-ui.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/css/style.css" type="text/css">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
  <script type="text/javascript" src="../WEB/lib/Scrolling-jQuery-UI-Tabs-jQuery-ScrollTabs/jquery.ui.scrolltabs.js"></script> -->

  <style type="text/css">
/*body {
  font-size: 12px;
  font-family: "Roboto", HelveticaNeue, Helvetica, sans-serif;
  margin: 0;
  background-color:#fafafa;
}
h1 { margin:150px auto 50px auto; text-align:center;}
p { font-size: 13px }

h2 { font-size: 16px; }*/

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


<!-- SCRIPT -->
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
<?
}
?>

</body>

</html>
