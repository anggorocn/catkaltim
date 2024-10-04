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
include_once("../WEB/classes/base-diklat/Peserta.php");
// include_once("../WEB/classes/base/Jabatan.php");
// include_once("../WEB/classes/base/CabangP3.php");
// include_once("../WEB/classes/base/Visitor.php");
// include_once("../WEB/classes/base/UsersBase.php");

include_once("../WEB/classes/base-diklat/KontenInformasi.php");
include_once("../WEB/classes/base-portal/formulir.php");



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
// $tempUserNik= $userLogin->userNik;
// echo $tempUserStatusJenis; exit;


$peserta= new Peserta();
$peserta->selectByParamsDataPribadi(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);

$peserta->firstRow();
$infoeselonid= $peserta->getField("LAST_ESELON_ID");
$reqJenjangJabatan= $peserta->getField("Jenjang_jabatan");

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempUserPelamarNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApi = json_decode(file_get_contents($url), true);
$reqNama='';
if($dataApi['glr_depan']!='-'){ $reqNama.=$dataApi['glr_depan']; }
$reqNama.=$dataApi['nama'];
if($dataApi['glr_belakang']!='-'){ $reqNama.=$dataApi['glr_belakang']; }


if($tempUserStatusJenis == "1" || $tempUserStatusJenis == "2")
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

    <title>PESERTA PENILAIAN KOMPETENSI</title>
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
                        <li><img src="../WEB/images/slide11.png" alt=""></li>
                        <li><img src="../WEB/images/slide12.png" alt=""></li>
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
                        { echo "Peserta"; } else { echo $arrayJudul["index"]["informasi_area"]; } ?></div>
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
                            <div class="col-md-10" style="padding-right: 0px;padding-left: 0px">
                            	<input type="password" name="reqPasswd" id="reqPasswd" class="easyui-validatebox" required placeholder="Password" />
                            </div>
                            <div class="col-md-2" style="padding-right: 0px;padding-left: 0px">
                                <div class="input-group-addon" style="height: 40px;"  onclick="showPass()">
						        	<i class="fa fa-eye-slash" id="eye" aria-hidden="true"></i>
						      	</div>                                            	
                            </div>

                            <input type="hidden" name="reqMode" value="submitLogin">
                            <input type="submit" value="LOGIN">
                            <div class="ket">
                            	<!-- <a href="?pg=password"><?=$arrayJudul["index"]["lupapassword"]?></a> |  -->
                            	<!-- <a href="?pg=register">Register</a> -->
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
                                        	<img id="reqImagePeserta" src="<?=$dataApi['foto_original']?>" style="width: 90%; height: 100%" >
                                        </div>
                                    </td>
                                </tr>
                            	<tr>
                                	<td><i class="fa fa-user"></i> <?=$arrayJudul["index"]["nama"]?></td>
                                    <td>:</td>
                                    <td><?=$reqNama?></td>
                                </tr>
                                <tr>
                                	<td><i class="fa fa-arrow-circle-right"></i><?=$tempinfologin?></td>
                                    <td>:</td>
                                    <td><?=$tempUserPelamarNip?></td>
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
				|| $pg == "daftar_riwayat_hidup" || $pg == "formulir_critical_incident" || $pg == "formulir_ci_pelaksana"  || $pg == "formulir_q_inta" || $pg == "formulir_q_kompetensi_eselon" || $pg == "formulir_q_kompetensi_pelaksana"
				
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
                            <!-- <li><a href="?pg=data_koreksi_nip" <? if($pg == "data_koreksi_nip") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["data_koreksi_nip"]?></a></li> -->
                            <?
                        	}
                            ?>

                             <!-- <li><a href="?pg=download_dokumen" <? if($pg == "download_dokumen") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["download_dokumen"]?></a></li> -->

                             <!-- <li><a href="?pg=upload_dokumen" <? if($pg == "upload_dokumen") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["upload_dokumen"]?></a></li> -->

                          
                            
                            <?php /*?><li><a href="?pg=tanya_jawab" <? if($pg == "tanya_jawab") { ?> class="submenu-current" <? } ?>>Tanya Jawab</a></li><?php */?>
                        </ul>
					</div>                    
                   
                    <a class="menuitem submenuheader" href="#" style="background-color: seagreen;"><?=$arrayJudul["index"]["isianformulir"]?></a>
					<div class="submenu">
						<ul>
                        <?
                        $statement= " AND A.PEGAWAI_ID = ".$tempUserPelamarId."";
                        $drh= new Peserta();
                        $drh->selectByParamsCekDrh(array(), -1,-1, $statement);
                        // echo $drh->query;exit; 
                        $drh->firstRow();
                        $atasan=$drh->getField("ROWATASAN");
                        $saudara=$drh->getField("ROWSAUDARA");
                        $riwayatpendidikan=$drh->getField("ROWRIPEND");
                        $pendidikannon=$drh->getField("ROWRIPENDNON");
                        $riwayatjabatan=$drh->getField("ROWRIJAB");
                        $bidangpek=$drh->getField("ROWBIDANGPEK");
                        $datapek=$drh->getField("ROWDATAPEK");
                        $kondisikerja=$drh->getField("ROWKONKERJA");
                        $minharap=$drh->getField("ROWMINHARAP");
                        $kekuatan=$drh->getField("ROWKEKKEL");
                        // if($atasan == 1  && $saudara > 0  && $riwayatpendidikan > 0  && $riwayatjabatan > 0 && $bidangpek > 0  && $datapek == 1 && $kondisikerja == 1 && $minharap == 1 && $kekuatan == 1)
                        if($atasan == 1  && $riwayatpendidikan > 0  && $riwayatjabatan > 0 && $bidangpek > 0  && $datapek > 0 && $kondisikerja > 0 && $minharap > 0 && $kekuatan > 0)
                        {
                        	$imgdrh = "icon-sudah";
                        	

                        }
                        else
                        {
                        	$imgdrh = "icon-belum";
                        }

                        $statement= " AND A.PELAMAR_ID = ".$tempUserPelamarId." AND COALESCE(NULLIF(A.STATUS_AKTIF, ''), NULL) = '1'";
                        $daftar_entrian = new Pelamar();
                        $daftar_entrian->selectByParamsDaftarEntrian(array(), -1,-1, $statement);
						// echo $daftar_entrian->query;exit;
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

							// if($tempAda == 0)
							// 	$img = "icon-belum";
							// else
							// 	$img = "icon-sudah";
						?>
							<!-- <li><a href="?pg=<?=$daftar_entrian->getField("LINK_FILE")?>" --> 
								<li><a href="index_pribadi.php?pg=data_pribadi" 
								<? if($pg == $daftar_entrian->getField("LINK_FILE")) { ?> 
									class="submenu-current" <? } ?>>
							<?=$arrayJudul["index"][$daftar_entrian->getField("LINK_FILE")]?> 
							<?
                            if($daftar_entrian->getField("WAJIB_ISI") == "1")
							{
							?>
                            	<!-- <span><img src="../WEB/images/<?=$img?>.png" /></span> -->
                            <?
							}
							else
							{
								// if($imgdrh == "icon-sudah")
								// {
							?>
		                            <!-- <span><img src="../WEB/images/<?=$imgdrh?>.png" /></span>                             -->
                            <?
								// }
							}
							?>Data Diri
		                            <span><img src="../WEB/images/<?=$imgdrh?>.png" /></span>                            
                            </a></li>
                        <?
						}
						?>
                        </ul>
					</div>
					 <a class="menuitem submenuheader" href="#" style="background-color: seagreen;"><?=$arrayJudul["index"]["formulir"]?></a>
					<div class="submenu">
						 
						<?
						if($reqJenjangJabatan=='administrator'){
						    $totalQInta=6;
						}
						else if($reqJenjangJabatan=='pengawas'){
						    $totalQInta=5;
						}
						else{
						    $totalQInta=4;
						}

						if(!empty($infoeselonid)||!empty($dataApi['id_golongan']))
						{
							if($infoeselonid !== "99")
							{ 
								$sudah = new Formulir();
								$statementsoal= " AND B.PEGAWAI_ID = ".$tempUserPelamarId."";
								$statementjawaban= " AND A.PEGAWAI_ID = ".$tempUserPelamarId."";
								$statementqinta= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 2";
								$statementeselon= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 3";
								$statementpelaksana= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 4";

								$countcriticalsoal = $sudah->getCountByParamsCriticalSoal(array(), $statementsoal);
								$countcriticaljawaban = $sudah->getCountByParamsCriticalJawaban(array(), $statementjawaban);
								$countqinta = $sudah->getCountByParamsQInta(array(), $statementqinta);
								$counteselon = $sudah->getCountByParamsQInta(array(), $statementeselon);
								$countpel = $sudah->getCountByParamsQInta(array(), $statementpelaksana);

								if($countcriticalsoal == 12 && $countcriticaljawaban == 2 )
									$img = "icon-sudah";
								else
									$img = "icon-belum";
								if($countqinta == $totalQInta )
									$imginta = "icon-sudah";
								else
									$imginta = "icon-belum";
								if($counteselon == 19)
									$imgeselon= "icon-sudah";
								else
									$imgeselon = "icon-belum";
								if($countpel == 11)
									$imgpel= "icon-sudah";
								else
									$imgpel = "icon-belum";

								?>
								<ul>
									<li><a href="?pg=formulir_critical_incident" <? if($pg == "formulir_critical_incident") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["formulir_critical_incident"]?>
		                          
				                            <span><img src="../WEB/images/<?=$img?>.png" /></span>                            
		                          	</a>
									</li>
									<li><a href="?pg=formulir_q_inta" <? if($pg == "formulir_q_inta") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["formulir_q_inta"]?>
								          <span><img src="../WEB/images/<?=$imginta?>.png" /></span>                            
		                        	</a>
									</li>
									<li><a href="?pg=formulir_q_kompetensi_eselon" <? if($pg == "formulir_q_kompetensi_eselon") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["formulir_q_kompetensi_eselon"]?>
									
				                            <span><img src="../WEB/images/<?=$imgeselon?>.png" /></span>                            
		                            </a>
									</li>
								</ul> 
								<?
							}
							else
							{

								$sudah = new Formulir();
								
								$statementpelaksana= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 4";
								$countpel = $sudah->getCountByParamsQInta(array(), $statementpelaksana);

								if($countpel == 11)
									$imgpel= "icon-sudah";
								else
									$imgpel = "icon-belum";
						?>
						<ul>
							<!-- <li><a href="?pg=formulir_ci_pelaksana" <? if($pg == "formulir_ci_pelaksana") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["formulir_ci_pelaksana"]?></a> -->
							</li>
							<li><a href="?pg=formulir_q_kompetensi_pelaksana" <? if($pg == "formulir_q_kompetensi_pelaksana") { ?> class="submenu-current" <? } ?>><?=$arrayJudul["index"]["formulir_q_kompetensi_pelaksana"]?>
							<?
							if($imgpel == "icon-sudah")
							{
								?>
								<span><img src="../WEB/images/<?=$imgpel?>.png" /></span>                            
								<?
							}
							?>
							</a>
							</li>
						</ul>
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

            <div class="col-md-2"></div>
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

<script type="text/javascript">
    function autologin(){
        $('#infoujian2').firstVisitPopup({
            cookieName : 'homepage',
            showAgainSelector: '#show-message'
        });
    }

    $(function(){
        $('#ffLogin1').form({
            url:'../json/relog_json.php',
            onSubmit:function(){
                return $(this).form('validate');
                // console.log('masuk');
                // return false ;
            },
            success:function(data){
                if(data == "success"){
                    $.messager.alert('Info', 'Session Telah Diperbarui, Tekan Tombol X Dibagian Kanan Atas dan Klik Simpan Untuk Melanjutkan Menyimpan Data', 'info');
                    return false;
                }
                else
                {
                    $.messager.alert('Info', 'Username / Password Salah. Silahkan Login Kembali', 'info');
                    return false;
                }
            }
        }); 
    });

function showPass(){  
    if($('#reqPasswd').attr("type") == "text"){
        document.getElementsByName("reqPasswd")[0].setAttribute('type','password')
        $("#eye").attr('class', 'fa fa-eye-slash')
    }else if($('#reqPasswd').attr("type") == "password"){
        document.getElementsByName("reqPasswd")[0].setAttribute('type','text')
        $("#eye").attr('class', 'fa fa-eye')
    }
}
</script>

<script src="../WEB/lib/first-visit-popup-master/jquery.firstVisitPopup.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/css-ujian/popup.css">


<div class="my-welcome-message" id="infoujian2"  style="height:70%; margin-top:5%">
    <div class="konten-welcome">
    <div class="row" style="height:100%;">
         <div class="login-area">
            <div class="foto"><i class="fa fa-user fa-4x"></i></div>
            <form id="ffLogin1" method="post" novalidate enctype="multipart/form-data">
                <center><br><b>SESSION HABIS</b><br>
                Silahkan Login Kembali<center>
            <div class="form">
                <input type="text" name="reqUser" id="reqUser" class="easyui-validatebox" required placeholder="Nip / NIK Anda"/>
                <input type="password" name="reqPasswd" id="reqPasswd" class="easyui-validatebox" required placeholder="Password" />
                <input type="hidden" name="reqMode" value="submitLogin">
                <input type="submit" value="LOGIN">
                <div class="ket">
                    <!-- <a href="?pg=password"><?=$arrayJudul["index"]["lupapassword"]?></a> |  -->
                    <!-- <a href="?pg=register">Register</a> -->
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
</div>
