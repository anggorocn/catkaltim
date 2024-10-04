<?
include_once("../WEB/setup/defines.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/Konfigurasi.php");
include_once("../WEB/classes/base/InformasiHeader.php");
include_once("../WEB/classes/base/Jadwal.php");

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

$reqMode = httpFilterRequest("reqMode");
$reqUser = httpFilterPost("reqUser");
$reqPasswd = httpFilterPost("reqPasswd");
$reqSecurity= httpFilterPost("reqSecurity");
//echo $reqMode."--".$reqUser."--".$reqPasswd;exit;

//------ xml -------
$xml_file = "../WEB/web.xml"; 
$data_xml = simplexml_load_file($xml_file);
$server=1;
$path_server= $data_xml->path->path->configValue->$server;

//if($reqMode == "submitLogin" && $reqUser != "" && $reqPasswd != "" && (md5($reqSecurity) == $_SESSION['security_login_code'])) 
if($reqMode == "submitLogin" && $reqUser != "" && $reqPasswd != "") 
{
	$set= new Pelamar();
	$set->selectByParamsData(array("A.EMAIL1"=>$reqUser),-1,-1);
	$set->firstRow();
	//echo $set->query;exit;
	$tempIsDaftar= $set->getField("IS_DAFTAR");
	$tempIsKirimSmsValidasi= $set->getField("IS_KIRIM_SMS_VALIDASI");
	$tempNoHp= $set->getField("NO_HP");
	unset($set);
	
	/*if($tempIsDaftar == "")
	{
		echo '<script language="javascript">';
		echo 'alert("Yang belum Anda validasi adalah:\n1.	[Email: '.$reqUser.']\n\n\nSilakan cek terlebih dahulu email untuk melakukan validasi email tersebut.\nSebelum Anda melakukan semua validasi email, Anda belum dapat melakukan pengiriman lamaran pada formasi lowongan yang tersedia.");';
		echo 'top.location.href = "index.php";';
		echo '</script>';
		exit;
	}*/
	
	/*if($tempIsKirimSmsValidasi == "")
	{
		echo '<script language="javascript">';
		echo 'alert("Yang belum Anda validasi adalah:\n1.	[Hp: '.$tempNoHp.']\n\n\nSilakan cek terlebih dahulu email dan HP lain untuk melakukan validasi email tersebut.\nSebelum Anda melakukan semua validasi email, Anda belum dapat melakukan pengiriman lamaran pada formasi lowongan yang tersedia.");';
		echo 'top.location.href = "index.php";';
		echo '</script>';
		exit;
	}*/
		
	$userLogin->resetLogin();
	if ($userLogin->verifyUserLogin($reqUser, $reqPasswd)) 
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
//elseif($reqMode == "submitLupa" && $reqUser != "" && (md5($reqSecurity) == $_SESSION['security_lupa_code'])) 
elseif($reqMode == "submitLupa" && $reqUser != "") 
{
	$set= new Pelamar();
	$set->selectByParams(array("EMAIL1"=>$reqUser));
	$set->firstRow();
	$tempEmail1= $set->getField("EMAIL1");
	unset($set);
	
	if($tempEmail1 == ""){}
	else
	{
	}
}
else if ($reqMode == "submitLogout")
{
	$userLogin->resetLogin();
	$userLogin->emptyUsrSessions();
}

//$userLogin->resetLogin();
//$userLogin->emptyUsrSessions();
	
$set= new Pelamar();
$reqId= $userLogin->userPelamarEnkripId;
$set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
$set->firstRow();
$tempIsKirimLamaran= $set->getField("IS_KIRIM_LAMARAN");
$tempIsPernyataan= $set->getField("IS_PERNYATAAN");
$tempIsDaftar= $set->getField("IS_DAFTAR");
$tempNama= $set->getField("NAMA");
$tempNoKtp= $set->getField("NO_KTP");
$tempNpwp= $set->getField("NPWP");
$tempTanggalLahir= dateToPageCheck($set->getField("TANGGAL_LAHIR"));
$tempLinkPg= $set->getField("IS_STATUS_ISI_FORMULIR_INFO");
$tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
$tempIsloginPertamaIndex= $set->getField("IS_LOGIN_PERTAMA");
unset($set);

$set= new Pelamar();
$reqId= $userLogin->userPelamarId;
$set->selectByParamsInfoJejak($reqId);
//echo $set->query;exit;
$set->firstRow();
$tempJejakDataPribadi= $set->getField("JEJAK_SIMPAN_DAFTAR");
$tempJejakDataPribadiPangkat= $set->getField("JEJAK_SIMPAN_DAFTAR_PANGKAT");
$tempJejakDataPribadiJabatan= $set->getField("JEJAK_SIMPAN_DAFTAR_JABATAN");
$tempJejakDataPribadiPendidikan= $set->getField("JEJAK_SIMPAN_DAFTAR_PENDIDIKAN");
$tempJejakDataPribadiPelatihan= $set->getField("JEJAK_SIMPAN_DAFTAR_PELATIHAN");
$tempJejakDataPribadiPenugasan= $set->getField("JEJAK_SIMPAN_DAFTAR_PENUGASAN");
$tempJejakDataPribadiLain= $set->getField("JEJAK_SIMPAN_DAFTAR_LAIN");

$tempJejakPendidikanFormal= $set->getField("JEJAK_SIMPAN_PENDIDIKAN");
$tempJejakPengalamanBekerja= $set->getField("JEJAK_SIMPAN_PENGALAMAN");
$tempJejakPelatihan= $set->getField("JEJAK_SIMPAN_SERTIFIKASI");
$tempJejakArahMinat= $set->getField("JEJAK_SIMPAN_MINAT");
unset($set);

$konfigurasi= new Konfigurasi();
$konfigurasi->selectByParams(array('KONFIGURASI_ID'=>1), -1, -1);
$konfigurasi->firstRow();
$tempInfoIndonesiaMemanggil= $konfigurasi->getField('INFO_INDONESIA_MEMANGGIL');

$konfigurasi_running_text= new InformasiHeader();
$konfigurasi_running_text->selectByParams(array(), -1, -1, "AND STATUS = '1'", "ORDER BY TANGGAL DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PDS Recruitment &amp; Training Center</title>

    <!-- Bootstrap Core CSS -->
    <link href="../WEB/lib/startbootstrap-blog-post-1.0.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="../WEB/lib/startbootstrap-freelancer-1.0.3/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../WEB/lib/startbootstrap-blog-post-1.0.4/css/blog-post.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link href="../WEB/css/gaya.css" rel="stylesheet">
    <link href="../WEB/css/rekrutmen.css" rel="stylesheet">
    
    <style>
	.full-width-div {
		position: relative;
		*width: 100%;
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
		left:100px;
		text-transform:uppercase;
		font-size:26px;
		font-style:italic;
		*display:inline-block;
	}
	
	/****/
	a.navbar-brand{
		margin-left:70px !important;
		font-size:17px;
		font-family:Arial, Helvetica, sans-serif;
		text-transform:uppercase;
		
		*background:#9C3;
		color:#FFF !important;
	}
	</style>
	
    <!-- jQuery -->
    <script src="../WEB/lib/startbootstrap-blog-post-1.0.4/js/jquery.js"></script>
	
    <!-- Bootstrap Core JavaScript -->
    <script src="../WEB/lib/startbootstrap-blog-post-1.0.4/js/bootstrap.min.js"></script>
    
    <!-- FIXED MENU -->
    <script type='text/javascript'>//<![CDATA[
	$(window).load(function(){
		$(document).ready(function() {
			
			setModal('setLookupLogin', 'login.php');
			
			// hide .navbar first
			//$(".area-atas").hide();
		    //$(".logo-fix").hide();
			//$(".bahasa-cari-fix").hide();
	
			$(window).scroll(function () {
				//if you hard code, then use console
				//.log to determine when you want the 
				//nav bar to stick.  
				console.log($(window).scrollTop())
				if ($(window).scrollTop() < 79) {
					//$('#nav_bar').addClass('navbar-fixed');
					$('.area-atas').fadeIn();
					//$(".bahasa-cari-fix").fadeIn();
					//$('.logo-fix').show();
					//$(".bahasa-cari-fix").show();
					
					//$('.row.halaman-detil').addClass('tambahan');
					//$('#konten-detil-id').removeClass('row halaman-detil');
					//$('#konten-detil-id').addClass('row halaman-detil2');
					//alert('hai');
				}
				if ($(window).scrollTop() > 80) {
					//$('#nav_bar').removeClass('navbar-fixed');
					$('.area-atas').fadeOut();
					//$(".bahasa-cari-fix").fadeOut();
					
					//$('.row.halaman-detil').removeClass('tambahan');
					
					
					//$('#konten-detil-id').removeClass('row halaman-detil2');
					//$('#konten-detil-id').addClass('row halaman-detil');
					
					//$('.logo-fix').hide();
					//$(".bahasa-cari-fix").hide();
					//alert('haio');
				}
			});
		});
	});//]]> 
	
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
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
        	<div class="row area-atas" style="background:#164165; height:60px; line-height:60px;">
            	<div class="col-md-6">
                	<span><img src="../WEB/images/logo-pds-rekrutmen.png"></span> <span>Career and Recruitment Center</span>
                </div>
                <div class="col-md-6 text-right">
                	<a href="#" class="link-web-utama"><i class="fa fa-globe fa-lg"></i> Ke Website Utama</a>
                </div>
            </div>
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header" style="background:#2c7bbf;">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">PT. APS</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="background:#2c7bbf;">
                <ul class="nav navbar-nav navbar-right">
                	<li><a href="#">Profil <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="?pg=sekilas_perusahaan">Sekilas Perusahaan</a></li>
                            <li><a href="?pg=visi_misi">Visi Misi</a></li>
                            <li><a href="?pg=manajemen">Manajemen</a></li>
                            <li><a href="?pg=wilayah_kerja">Wilayah Kerja</a></li>
                            <li><a href="?pg=bidang_usaha">Bidang Usaha</a></li>
                            <li><a href="?pg=struktur_organisasi">Struktur Organisasi</a></li>
						</ul>
                    </li>
                    <li>
                        <a href="#">Posisi</a>
                    </li>
                    <li>
                        <a href="#">Informasi</a>
                    </li>
                    <li>
                        <a href="#">Pengumuman</a>
                    </li>
                    <li>
                        <a href="#">Kontak</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
	
    <div class="container-fluid">
    	<div class="row" style="border:0px solid red;">
        	<div class="col-lg-12" style="border:0px solid cyan; padding-left:0px; padding-right:0px;">
            	<div class="full-width-div" style="border:0px solid blue;">
                	<img src="../WEB/images/banner-home.png" style="position:absolute; left:0px; right:0px;">
                </div>
            	
            </div>
        </div>
    </div>
    
    <!-- Page Content -->
    <div class="container">
		
        <!--<div class="row">
        	<div class="col-lg-12">
            	<div class="full-width-div">
                	<img src="../WEB/images/banner-home.png" style="position:absolute; left:0px; right:0px;">
                </div>
            	
            </div>
        </div>-->
        
        <div class="row main-home">
        
        	<?php
            $includePage = $page_to_load->loadPage();
            include_once($includePage);
            ?>

            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4" style="background:#FFF;">
				
                <div id="setLookupLogin"></div>
                
                
                <div class="glossymenu">
                    <a class="menuitem" href="?pg=pendaftaran">Halaman Pernyataan</a>
                    <a class="menuitem submenuheader" href="#">Isian Formulir</a>
                    <div class="submenu">
                        <ul>
                        <li><a href="?pg=data_pribadi">Data Pribadi</a></li>
                        <li><a href="?pg=pendidikan_formal">Pendidikan Formal</a></li>
                        <li><a href="?pg=pengalaman_bekerja">Pengalaman Bekerja</a></li>
                        <li><a href="?pg=pelatihan">Pelatihan / Sertifikasi Keahlian</a></li>
                        <li><a href="?pg=arah_minat">Arah Minat</a></li>
                        <li><a href="?pg=konfirmasi">Konfirmasi</a></li>
                        </ul>
                    </div>
    
                </div>
                
                
                
                

                <?php /*?><!-- Blog Search Well -->
                <div class="well">
                    <h4>Blog Search</h4>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                    </div>
                    <!-- /.input-group -->
                </div><?php */?>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-unstyled">
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                            </ul>
                        </div>
                        
                    </div>
                    <!-- /.row -->
                </div>
				
                
                <!-- Side Widget Well -->
                <div class="well">
                    <h4>Side Widget Well</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
                </div>

            </div>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->
</body>

</html>
