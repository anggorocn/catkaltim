<?
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");
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

    <!-- Custom CSS -->
    <link href="../WEB/lib/startbootstrap-blog-post-1.0.4/css/blog-post.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link href="../WEB/css/gaya.css" rel="stylesheet">
    
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

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
        	<div class="row area-atas" style="background:#1b4a73; height:60px; line-height:60px;">
            	<div class="col-md-6">
                	<span><img src="../WEB/images/logo-pds-rekrutmen.png"></span> <span>Career and Recruitment Center</span>
                </div>
                <div class="col-md-6 text-right">
                	<a href="#">Ke Website Utama</a>
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
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
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

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <div class="lowongan-area">
                	<div class="list">
                    	<div class="tanggal">
                        	<div class="bulan">Januari</div>
                            <div class="tgl">24<br><span class="thn">2016</span></div>
                        </div>
                        <div class="data">
                        	<div class="judul"><a href="#">PLANT GENERAL MANAGER JOB (CONSUMER GOODS)</a></div>
                            <div class="isi">Rekrutmen PT. APS ini tidak dipungut biaya dalam bentuk apapun Hanya pelamar yang memenuhi persyaratan dan terbaik yang akan dipanggil untuk mengikuti proses rekrutmen dan seleksi Keputusan panitia rekrutmen & seleksi ...</div>
                        </div>
                    </div>
                    
                    <div class="list">
                    	<div class="tanggal">
                        	<div class="bulan">Januari</div>
                            <div class="tgl">24<br><span class="thn">2016</span></div>
                        </div>
                        <div class="data">
                        	<div class="judul"><a href="#">PLANT GENERAL MANAGER JOB (CONSUMER GOODS)</a></div>
                            <div class="isi">Rekrutmen PT. APS ini tidak dipungut biaya dalam bentuk apapun Hanya pelamar yang memenuhi persyaratan dan terbaik yang akan dipanggil untuk mengikuti proses rekrutmen dan seleksi Keputusan panitia rekrutmen & seleksi ...</div>
                        </div>
                    </div>
                    
                    <div class="list">
                    	<div class="tanggal">
                        	<div class="bulan">Januari</div>
                            <div class="tgl">24<br><span class="thn">2016</span></div>
                        </div>
                        <div class="data">
                        	<div class="judul"><a href="#">PLANT GENERAL MANAGER JOB (CONSUMER GOODS)</a></div>
                            <div class="isi">Rekrutmen PT. APS ini tidak dipungut biaya dalam bentuk apapun Hanya pelamar yang memenuhi persyaratan dan terbaik yang akan dipanggil untuk mengikuti proses rekrutmen dan seleksi Keputusan panitia rekrutmen & seleksi ...</div>
                        </div>
                    </div>
                    
                </div>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Blog Search Well -->
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
                </div>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
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

    <!-- jQuery -->
    <script src="../WEB/lib/startbootstrap-blog-post-1.0.4/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../WEB/lib/startbootstrap-blog-post-1.0.4/js/bootstrap.min.js"></script>
    
    <!-- FIXED MENU -->
    <script type='text/javascript'>//<![CDATA[
	$(window).load(function(){
		$(document).ready(function() {
			
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
	
	</script>

</body>

</html>
