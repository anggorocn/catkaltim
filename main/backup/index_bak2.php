<?
include_once("../WEB/setup/defines.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/Jabatan.php");

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

$reqMode = httpFilterRequest("reqMode");
$reqUser = httpFilterRequest("reqUser");
$reqPasswd = httpFilterRequest("reqPasswd");

if($reqMode == "submitLogin" && $reqUser != "" && $reqPasswd != "")
{
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
else if($reqMode == "submitLogout")
{
	$userLogin->resetLogin();
	$userLogin->emptyUsrSessions();
}

if($userLogin->userPelamarId)
{
	$pelamar = new Pelamar();
	$tempIsPernyataan = $pelamar->getFieldById("PAKTA_INTEGRITAS", $userLogin->userPelamarId);
}

$jabatan = new Jabatan();
$jabatan->selectByParamsJumlah(array("KELOMPOK" => 'O'));
?>

<!DOCTYPE html>
<html lang="en"><head>

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

    <link href="../WEB/css/gaya-rekrutmen.css" rel="stylesheet">
    <link href="../WEB/css/rekrutmen.css" rel="stylesheet">
    <link href="../WEB/css/halaman.css" rel="stylesheet" type="text/css">
    
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
		left:80px;
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
    
    <?php /*?><!-- GlossyAccordionMenu -->
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
		overflow:hidden !important;
	}
}
</style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid" style="padding-left:0px; padding-right:0px; background:#2c7bbf;">
        
        	<div class="row area-atas" >
            
            	<div class="container atas">
                    <div class="col-md-6 career-nama">
                        <span><img src="../WEB/images/logo-pds-rekrutmen.png"></span> <span>Career and Recruitment Center</span>
                    </div>
                    <div class="col-md-6 text-right" style="padding-right:0px;">
                        <a href="http://www.ptpds.co.id" class="link-web-utama"><i class="fa fa-globe fa-lg"></i> Ke Website Utama</a>
                    </div>
                </div>
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
                    <a class="navbar-brand" href="index.php">PT. APS</a>
                </div>
                
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                        	<a href="index.php">Beranda</a>
                        </li>
                        <li>
                        	<a href="?pg=content&reqId=1">Informasi</a>
                        </li>
                        <li>
                            <a href="?pg=pengumuman_seleksi_administrasi">Pengumuman</a>
                        </li>
                        <!--<li>
                            <a href="#">Kontak</a>
                        </li>-->
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            
        </div>
        <!-- /.container -->
    </nav>
	
    <?
    if($pg == "" || $pg == "home"){
	?>
    <div class="container-fluid banner-home">
    	<div class="row" style="border:0px solid red;">
        	<div class="col-lg-12" style="border:0px solid cyan; padding-left:0px; padding-right:0px;">
            	<div class="full-width-div" style="border:0px solid blue;">
                	<img src="../WEB/images/banner-home.png" style="position:absolute; left:0px; right:0px;">
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
                        <div class="title">Login <? if($userLogin->userPelamarId == "") { echo "Area"; } else { echo "Informasi"; } ?></div>
                    </div>
                    
                    <?
					if($userLogin->userPelamarId == "")
					{
					?>
					
                    <div class="login-area">
                        <div class="foto"><i class="fa fa-user fa-4x"></i></div>
                        <form id="ffLogin1" method="post" novalidate enctype="multipart/form-data" action="index.php">
                        <div class="form">
                            <input type="text" name="reqUser" id="reqUser" class="easyui-validatebox" required placeholder="Email"/>
                            <input type="password" name="reqPasswd" id="reqPasswd" class="easyui-validatebox" required placeholder="Password" />
                            <input type="hidden" name="reqMode" value="submitLogin">
                            <input type="submit" value="LOGIN">
                            <div class="ket"><a href="#">Lupa Password</a> | <a href="?pg=register">Register</a></div>
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
                        	<table>
                            	<tr>
                                	<td><i class="fa fa-user"></i> Nama</td>
                                    <td>:</td>
                                    <td><?=$userLogin->nama?></td>
                                </tr>
                                <tr>
                                	<td><i class="fa fa-arrow-circle-right"></i> No. Register</td>
                                    <td>:</td>
                                    <td><?=$userLogin->userNoRegister?></td>
                                </tr>
                                <!--<tr>
                                	<td colspan="2"><a  class="btn btn-danger" href="index.php?reqMode=submitLogout"><i class="fa fa-sign-out"></i> Logout</a></td>
                                </tr>-->
                            </table>
                            <?php /*?><span><i class="fa fa-user"></i> <?=$userLogin->nama?></span> 
                            <span class="nik">No. Register : <?=$userLogin->userNoRegister?></span><?php */?>
                        </div>
                        <a  class="btn btn-danger" href="index.php?reqMode=submitLogout"><i class="fa fa-sign-out"></i> Logout</a>
                        <?php /*?><div>
                        	<a  class="btn btn-danger" href="index.php?reqMode=submitLogout"><i class="fa fa-sign-out"></i> Logout</a>
                        </div><?php */?>
					</div>
					<?
					}
					?>
                                    
                </div>
                
                <?
				//|| $pg == "konfirmasi" 
				if(	$userLogin->userPelamarId != "" || $pg == "pendaftaran" || 
				$pg == "data_pribadi_pangkat" || $pg == "data_pribadi_jabatan" || $pg == "data_pribadi_pendidikan" || $pg == "data_pribadi_pelatihan" || $pg == "data_pribadi_penugasan" || 
				$pg == "data_pribadi_lain" || 
				$pg == "data_pribadi" || $pg == "data_pendidikan_formal" || $pg == "pendidikan_formal" || $pg == "pengalaman_bekerja" || $pg == "pelatihan" || $pg == "arah_minat" || $pg == "data_pribadi_upload")
				{
				?>
				<div class="glossymenu">
					<?					
					$daftar_entrian = new Pelamar();
					$daftar_entrian->selectByParamsDaftarEntrian(array("PELAMAR_ID" => $userLogin->userPelamarId));
					?>
					<a class="menuitem submenuheader" href="#">Isian Formulir</a>
					<div class="submenu">
						<ul>
                        <?
                        while($daftar_entrian->nextRow())
						{
							if($daftar_entrian->getField("ADA") == 0)
								$img = "icon-belum";
							else
								$img = "icon-sudah";
						?>
							<li><a href="?pg=<?=$daftar_entrian->getField("LINK_FILE")?>" <? if($pg == $daftar_entrian->getField("LINK_FILE")) { ?> class="submenu-current" <? } ?>><?=$daftar_entrian->getField("NAMA")?> <span><img src="../WEB/images/<?=$img?>.png" /></span></a></li>
                        <?
						}
						?>
                        </ul>
					</div>
					<a class="menuitem submenuheader" href="#">Main Menu</a>
					<div class="submenu">
						<ul>
							<li><a href="?pg=daftar_lamaran" <? if($pg == "daftar_lamaran") { ?> class="submenu-current" <? } ?>>Daftar Lamaran Anda</a></li>    
							<li><a href="?pg=daftar_lowongan" <? if($pg == "daftar_lowongan") { ?> class="submenu-current" <? } ?>>Daftar Lowongan</a></li>                         
                        </ul>
					</div>                    
				</div>
				<?
				}
				?>				
                
                <div class="sidebar-title-area">
                	<div class="title">Kategori Lowongan</div>
                </div>
                <div class="kategori-area">
                <?
                while($jabatan->nextRow())
				{
				?>
                	<div class="item"><a href="index.php?pg=kategori_list&reqId=<?=$jabatan->getField("JABATAN_ID")?>"><?=strtoupper($jabatan->getField("NAMA"))?> <span>(<?=$jabatan->getField("TOTAL")?>)</span></a></div>
                <?
				}
				unset($jabatan);
                ?>
                </div>
                
                
                

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
                        <p>Copyright &copy; 2016 PT. APS<br>All Right Reserved.</p>
                    </div>
                    <div class="col-md-4 text-right">
                        CALL CENTER : 031 - 51166384<br><br>
                        
                        Dilayani setiap hari kerja pukul :<br>
                        <span>08.00 – 17.00</span> WIB  
                    </div>
                </div>
            </div>
            
            <!-- /.row -->
        </footer>

    </div>
    
</body>

</html>
