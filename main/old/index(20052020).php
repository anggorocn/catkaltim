<?
include_once("../WEB/setup/defines.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Dokumen.php");

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

// echo $tempUserPelamarId;exit();

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
$arrImage="";
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
				console.log($(window).scrollTop())
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
    
    <!-- GlossyAccordionMenu -->
    <?php /*?>
    <script type="text/javascript" src="../WEB/lib/GlossyAccordionMenu/ddaccordion.js">
    
    /***********************************************
    * Accordion Content script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
    * Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
    * This notice must stay intact for legal use
    ***********************************************
    </script>
    
    <script type="text/javascript">
        ddaccordion.init({
            headerclass: "submenuheader", //Shared CSS class name of headers group
            contentclass: "submenu", //Shared CSS class name of contents group
            revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
            mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
            collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
            defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
            onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
            animatedefault: false, //Should contents open by default be animated into view?
            persiststate: true, //persist state of opened contents within browser session?
            toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
            //togglehtml: ["suffix", "<img src='../WEB/lib/GlossyAccordionMenu/plus.gif' class='statusicon' />", "<img src='../WEB/lib/GlossyAccordionMenu/minus.gif' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
			<?
			if(	$pg == "pendaftaran" || $pg == "content" || 
			$pg == "data_pribadi_pangkat" || $pg == "data_pribadi_jabatan" || $pg == "data_pribadi_pendidikan" || $pg == "data_pribadi_pelatihan" || $pg == "data_pribadi_penugasan" || 
			$pg == "data_pribadi_lain" || $pg == "data_pribadi_upload" || 
			$pg == "data_pribadi" || $pg == "data_pendidikan_formal" || $pg == "pendidikan_formal" || $pg == "pengalaman_bekerja" || $pg == "pelatihan" || $pg == "arah_minat" || $pg == "data_pribadi_upload")
			{
			?>
			togglehtml: ["suffix", "<img src='../WEB/lib/GlossyAccordionMenu/minus.gif' class='statusicon' />", "<img src='../WEB/lib/GlossyAccordionMenu/minus.gif' class='statusicon' />"],
			<?
			}
			else
			{
			?>
			togglehtml: ["suffix", "<img src='../WEB/lib/GlossyAccordionMenu/plus.gif' class='statusicon' />", "<img src='../WEB/lib/GlossyAccordionMenu/minus.gif' class='statusicon' />"],
			<?
			}
			?>
            animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
            oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
                //do nothing
            },
            onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
                //do nothing
            }
        })
    </script><?php */?>
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
                        <li><a href="https://bkd.baliprov.go.id/" target="_blank">Website Utama</a></li>
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
            <div class="col-md-4 sisi-kanan" style="background:#FFF;">
				
                <div>
                

                    <div class="login-title-area">
                        <div class="title">Login <? 
                        // if($tempUserPelamarId == "") 
                        if($tempKondisiSudahLogin == "")
                        { echo "Area"; } else { echo $arrayJudul["index"]["informasi_area"]; } ?></div>
                    </div>
                    
                    <?
					// if($tempUserPelamarId == "")
					if($tempKondisiSudahLogin == "")
					{
					?>
					
                    <div class="login-area">
                        <div class="foto"><i class="fa fa-user fa-4x"></i></div>
                        <form id="ffLogin1" method="post" novalidate enctype="multipart/form-data" action="index.php">
                        <div class="form">
                            <input type="text" name="reqUser" id="reqUser" class="easyui-validatebox" required placeholder="Nip / NIK Anda"/>
                            <input type="password" name="reqPasswd" id="reqPasswd" class="easyui-validatebox" required placeholder="Password" />
                            <input type="hidden" name="reqMode" value="submitLogin">
                            <input type="submit" value="LOGIN">
                            <div class="ket">
                            	<!-- <a href="?pg=password"><?=$arrayJudul["index"]["lupapassword"]?></a> |  -->
                            	<a href="?pg=register">Register</a>
                            </div>
                        </div>
                        </form>
                    </div>
                    
                    <?
					}
					else
					{
					?>
					<div class="login-area-inner">
                    	<div class="data-login">
                        	<table style="width: 100%">
                                <tr>
                                	<td style="text-align: center;" colspan="3" >
                                    	<div class="foto-sidebar">
                                        	<img id="reqImagePeserta" src="../WEB/images/akun-foto.jpg" style="width: 90%; height: 100%" >
                                        </div>
                                    </td>
                                </tr>
                            	<tr>
                                	<td><i class="fa fa-user"></i> <?=$arrayJudul["index"]["nama"]?></td>
                                    <td>:</td>
                                    <td><?=$userLogin->nama?></td>
                                </tr>
                                <tr>
                                	<td><i class="fa fa-arrow-circle-right"></i><?=$tempinfologin?></td>
                                    <td>:</td>
                                    <td><?=$tempinfologindetil?></td>
                                </tr>
                            </table>
                        </div>
                        <!-- <a  class="btn btn-warning" onclick="openPopup()" style="cursor:pointer;"><i class="fa fa-key"></i> <?=$arrayJudul["index"]["gantipassword"]?></a> -->
                        <a  class="btn btn-danger" href="index.php?reqMode=submitLogout"><i class="fa fa-sign-out"></i> Logout</a>
					</div>
					<?
					}
					?>
                                    
                </div>
                <?
				if(	$tempUserFasilitatorId != "")
				{
				?>
				<div class="glossymenu">
                    <a class="menuitem submenuheader" href="#"><?=$arrayJudul["index"]["mainmenu"]?></a>
					<div class="submenu">
						<ul>
							<li><a href="?pg=fasilitator_diklat" <? if($pg == "fasilitator_diklat") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["daftarlowongan"]?></a></li>
							<li><a href="?pg=fasilitator_dokumen" <? if($pg == "fasilitator_dokumen") { ?> class="submenu-current" <? } ?>>Dokumen</a></li>
                        </ul>
					</div>
				</div>
				<?
				}
				//|| $pg == "konfirmasi" 
				if(	$tempUserPelamarId != "" || $pg == "pendaftaran" || 
				$pg == "data_pribadi_pangkat" || $pg == "data_pribadi_jabatan" || $pg == "data_pribadi_pendidikan" || $pg == "data_pribadi_pelatihan" || $pg == "data_pribadi_penugasan" || 
				$pg == "data_pribadi_lain" || 
				$pg == "data_pribadi" || $pg == "data_pendidikan_formal" || $pg == "pendidikan_formal" || $pg == "pengalaman_bekerja" || $pg == "pelatihan" || $pg == "arah_minat" || $pg == "data_pribadi_upload"
				)
				{
				?>
				<div class="glossymenu">
                    <a class="menuitem submenuheader" href="#"><?=$arrayJudul["index"]["mainmenu"]?></a>
					<div class="submenu">
						<ul>
							<li><a href="?pg=daftar_lowongan" <? if($pg == "daftar_lowongan") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["daftarlowongan"]?></a></li>
                            <li><a href="?pg=daftar_lamaran" <? if($pg == "daftar_lamaran") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["daftarlamarananda"]?></a></li>

                            <?
                            if($tempUserStatusJenis == "1")
                            {
                            ?>
                            <li><a href="?pg=data_koreksi_nip" <? if($pg == "data_koreksi_nip") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["data_koreksi_nip"]?></a></li>
                            <?
                        	}
                            ?>
                            
                            <?php /*?><li><a href="?pg=tanya_jawab" <? if($pg == "tanya_jawab") { ?> class="submenu-current" <? } ?>>Tanya Jawab</a></li><?php */?>
                        </ul>
					</div>                    
                    
                    <a class="menuitem submenuheader" href="#"><?=$arrayJudul["index"]["isianformulir"]?></a>
					<div class="submenu">
						<ul>
                        <?
                        $statement= " AND A.PELAMAR_ID = ".$tempUserPelamarId." AND COALESCE(NULLIF(A.STATUS_AKTIF, ''), NULL) = '1'";
                        $daftar_entrian = new Pelamar();
                        $daftar_entrian->selectByParamsDaftarEntrian(array(), -1,-1, $statement);
						//echo $daftar_entrian->query;exit;
                        while($daftar_entrian->nextRow())
						{
							if($daftar_entrian->getField("DAFTAR_ENTRIAN_ID") == "7")
							{
								$xmlfile = "../WEB/web.xml";
								$data = simplexml_load_file($xmlfile);
								// print_r($data);
								$urlfoto= $data->urlConfig->main->urlfoto;
								$urlfoto.="/".$tempUserPelamarId."/";
								$FILE_DIR = $urlfoto;

								$checkFile= 0;
								$pelamar_dokumen = new Dokumen();
								$statement = " AND A.STATUS_AKTIF = '1'";
								$totalwajibfile= $pelamar_dokumen->getCountByParams(array(), $statement);
								$pelamar_dokumen->selectByParams(array(), -1, -1, $statement, " ORDER BY A.DOKUMEN_ID ASC");
								while($pelamar_dokumen->nextRow())
								{
									$reqFormat= $pelamar_dokumen->getField("FORMAT");
									if($reqFormat == "jpg,jpeg,png")
									{
										$reqFormat= "png";
									}

									$tempFIleCheck= $FILE_DIR.$pelamar_dokumen->getField("PENAMAAN_FILE").".".$reqFormat;
									if(file_exists("$tempFIleCheck"))
									{
										$checkFile++;
									}
								}

								$tempAda=0;
								if($totalwajibfile == $checkFile)
								$tempAda=1;
							}
							else
								$tempAda= $daftar_entrian->getField("ADA");

							if($tempAda == 0)
								$img = "icon-belum";
							else
								$img = "icon-sudah";
						?>
							<li><a href="?pg=<?=$daftar_entrian->getField("LINK_FILE")?>" <? if($pg == $daftar_entrian->getField("LINK_FILE")) { ?> class="submenu-current" <? } ?>>
							<?=$arrayJudul["index"][$daftar_entrian->getField("LINK_FILE")]?> 
							<?
                            if($daftar_entrian->getField("WAJIB_ISI") == "1")
							{
							?>
                            	<span><img src="../WEB/images/<?=$img?>.png" /></span>
                            <?
							}
							else
							{
								if($img == "icon-sudah")
								{
							?>
		                            <span><img src="../WEB/images/<?=$img?>.png" /></span>                            
                            <?
								}
							}
							?>
                            </a></li>
                        <?
						}
						?>
                        </ul>
					</div>
					
				</div>
				<?
				}
				?>				
                

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
                        <span class="logo-footer"><img src="../WEB/images/logo-aps-rekrutmen-bali.png"></span><p>Copyright &copy; 2019. Badan Kepegawaian Daerah Pemerintah Provinsi Bali<br>All Right Reserved.</p>
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
  $tempPegawaiFoto= $urlfoto.$tempUserPelamarNip.".png";
  if(file_exists("$tempPegawaiFoto"))
  {
  ?>
  	$("#reqImagePeserta").attr("src", "<?=$urlfoto.$tempUserPelamarNip?>.png");
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