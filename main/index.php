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
if($userLogin->userPelamarId=='')
{
	// header("location:index.php?pg=login");	
	$pg='login';
}

if($reqMode == "submitLogout")
{
	$userLogin->resetLogin();
	$userLogin->emptyUsrSessions();
	header("location:index.php?pg=login");	
}

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserFasilitatorId= $userLogin->userFasilitatorId;
$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserStatusJenis= $userLogin->userStatusJenis;

$peserta= new Peserta();
$peserta->selectByParamsDataPribadi(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);
// echo $peserta->query; exit;
$peserta->firstRow();
$infoeselonid= $peserta->getField("LAST_ESELON_ID");
$reqJenjangJabatan= $peserta->getField("Jenjang_jabatan");
$reqStatusPegawaiId= $peserta->getField("STATUS_PEGAWAI_ID");

if($reqJenjangJabatan=='administrator'){
    $totalQInta=6;
}
else if($reqJenjangJabatan=='pengawas'){
    $totalQInta=5;
}
else{
    $totalQInta=4;
}

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempUserPelamarNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApi = json_decode(file_get_contents($url), true);


$reqNama='';
if($dataApi['glr_depan']!='-'){ $reqNama.=$dataApi['glr_depan']; }
$reqNama.=$dataApi['nama'];
if($dataApi['glr_belakang']!='-'){ $reqNama.=$dataApi['glr_belakang']; }

if ($reqNama==''){
	$reqNama=$peserta->getField("NAMA");;

}

if($dataApi['foto_original']==''){
	$reqFoto='../WEB/images/image_default.png';
}
else{
	$reqFoto=$dataApi['foto_original'];
}



if($tempUserStatusJenis == "1" || $tempUserStatusJenis == "2")
{
	$tempinfologin= "NIP";
	$tempinfologindetil= $tempUserPelamarNip;
}
else
{
	$tempinfologin= "NIK";
	$tempinfologindetil= $tempUserPelamarNip;
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
    <link href="../assesment/WEB/css/gaya-main.css" rel="stylesheet">
    <link href="../assesment/WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
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
		eModal.ajax('ubah_password.php', 'UBAH PASSWORD')
	}
	
	function openPopupFile(link_file, judul) {
		eModal.ajax(link_file, judul)
	}
	</script>    
        
    <!-- FIXED MENU -->
    <script type='text/javascript'>//<![CDATA[
	$(window).load(function(){
		$(document).ready(function() {			
			$(window).scroll(function () {
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

<style>
		.col-md-12{
			*padding-left:0px;
			*padding-right:0px;
		}
	    
	/*    FLUSH FOOTER*/
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
		} 
		.input-container {
		  display: -ms-flexbox; /* IE10 */
		  display: flex;
		  width: 100%;
		  margin-bottom: 15px;
		  background-color: white;
		  border-radius: 20px;
		}

		.icon {
		  padding: 10px;
/*		  background: dodgerblue;*/
		  color: white;
		  min-width: 50px;
		  text-align: center;
		}

		.input-field {
		  width: 100%;
		  padding: 10px;
		  outline: none;
		  margin-top: 5px;
		}

	</style>
	<link rel="stylesheet" href="../WEB/css/gaya-baru.css" type="text/css">

</head>
<?if ($pg=='login'){?>
	<div class="col-md-4" style="height: 100vh;"> 
		<img src="../assesment/WEB/images/kiri-login.png" style="height: 100vh;">
	</div>
	<div class="col-md-8" style="height: 100vh;">
		<div class="area-login" style="margin: 15% 30%;">
            <form id="ffLogin1" method="post" novalidate enctype="multipart/form-data" action="index.php">
                <fieldset> 
                	<h4 style="text-align: left;"><b>Halaman Login Peserta Assesment</b></h4>
                	<p style="text-align: left;">Selamat datang,<br>
                	silahkan login menggunakan akun anda.</p>
                	<div class="input-container">
                    	<img src="../assesment/WEB/images/user-login.png" class=" icon" style="width:10%">
					    <input class="input-field" type="text" name="reqUser" id="reqUser" placeholder="NIP">
					</div>      			
					<div class="input-container">
                    	<img src="../assesment/WEB/images/pass-login.png" class=" icon" style="width:10%">
					    <input class="input-field" type="password" name="reqPasswd" id="reqPasswd" placeholder="Password">
                    	<i class="fa fa-eye-slash" id="eye" aria-hidden="true" class=" icon" style="margin-top: 5%;font-size: 20px;margin-right: 3%;cursor: pointer;" onclick="showPass()"></i>
					</div> 
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <input name="slogin_POST_send" type="submit" class="btn btn-lg btn-success btn-block" value="LOGIN" alt="DO LOGIN!" style="background-color:#0b7f7d">
                            <input type="hidden" name="reqMode" value="submitLogin">
                        </div>
                    </div>
                </fieldset>
                <?=$csrf->echoInputField();?>
            </form>
        </div>
	</div>
<?}
else{?>
	<div id="wrap-utama" style="height:100%;">
	    <div id="main" class="container-fluid clear-top" style="height:100%;">
	        
	        <div class="row">
	            <div class="col-md-12 area-header">
	                <div class="col-md-6">
	                    <div class="row">
	                        <div class="col-md-2">
	                            <img src="../assesment/WEB/images/logo-judul.png"> 
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
	                        <a href="index.php?reqMode=submitLogout"> Logout</a>
	                    </div>
	                </div>
	            </div>
	        </div>

	        <div class="row">
	            <div class="col-md-8" style="height: 75vh; border-radius: 0px 0px 50px 0px">
	                <div class="row">
	                    <?php
			            $includePage = $page_to_load->loadPage();
			            include_once($includePage);
			            ?>
	                </div>
	            </div>
	            <div class="col-md-4">
	                 <div style="padding: 20px;text-align: center;background-color: #0e7476;margin: 0px 40px;border-radius: 30px;z-index: 1;position: sticky;">
	                 	<img id="reqImagePeserta" src="<?=$reqFoto?>" style="width: 50%; border-radius: 100000px;">
	                 	<b><p style="color: white; margin:10px 0px 0px 0px;"><?=$reqNama?></p></b>
	                 	<p style="color: white; font-size: 12px;"><?=$tempUserPelamarNip?></p>
	                 </div>
	                 <div style="padding: 20px;text-align: center;background-color: #858585;margin: -70px 40px 20px 40px;;border-radius: 30px; padding-top: 50px;">
	                 	<?if(	$tempUserPelamarId != "" || $pg == "pendaftaran" || 
					$pg == "data_pribadi_pangkat" || $pg == "data_pribadi_jabatan" || $pg == "data_pribadi_pendidikan" || $pg == "data_pribadi_pelatihan" || $pg == "data_pribadi_penugasan" || 
					$pg == "data_pribadi_lain" || 
					$pg == "data_pribadi" || $pg == "data_pendidikan_formal" || $pg == "pendidikan_formal" || $pg == "pengalaman_bekerja" || $pg == "pelatihan" || $pg == "arah_minat" || $pg == "data_pribadi_upload" 
					|| $pg == "daftar_riwayat_hidup" || $pg == "formulir_critical_incident" || $pg == "formulir_ci_pelaksana"  || $pg == "formulir_q_inta" || $pg == "formulir_q_kompetensi_eselon" || $pg == "formulir_q_kompetensi_pelaksana"
					
					)
					{
					?>
					<div class="glossymenu">
	                    <a class="menuitem submenuheader" href="#" onclick="show('mainmenu')"><b><?=$arrayJudul["index"]["mainmenu"]?></b></a>
						<div class="submenu" id='mainmenu'>
							<ul>
								<li><a href="?pg=daftar_lowongan" <? if($pg == "daftar_lowongan") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["daftarlowongan"]?></a></li>
	                            <li><a href="?pg=daftar_lamaran" <? if($pg == "daftar_lamaran") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["daftarlamarananda"]?></a></li>
	                        </ul>
						</div>                    
	                   
	                    <a class="menuitem submenuheader" href="#" onclick="show('isianformulir')"><b><?=$arrayJudul["index"]["isianformulir"]?></b></a>
						<div class="submenu" id='isianformulir'>
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
	                        $urlPendidikan = 'https://api-simpeg.kaltimbkd.info//pns/riwayat-pendidikan/'.$tempUserPelamarNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
							$dataApiPendidikan = json_decode(file_get_contents($urlPendidikan), true);
							$riwayatpendidikanapi=count($dataApiPendidikan);
	                        if($atasan == 1  && ($riwayatpendidikan > 0||$riwayatpendidikanapi > 0)  && $bidangpek > 0  && $datapek > 0 && $kondisikerja > 0 && $minharap > 0 && $kekuatan > 0)
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
								?>
									<li><a href="index.php?pg=data_pribadi#"
									<? if($pg == $daftar_entrian->getField("LINK_FILE")) { ?> 
										class="submenu-current" <? } ?>>
								<?=$arrayJudul["index"][$daftar_entrian->getField("LINK_FILE")]?> 
								&nbsp; • Data Diri <span><img src="../WEB/images/<?=$imgdrh?>.png" /></span>                            
	                            </a></li>
	                        <?
							}
							?>
	                        </ul>
						</div>
						<a class="menuitem submenuheader" href="#" onclick="show('formulir')"><b><?=$arrayJudul["index"]["formulir"]?></b></a>
						<?
						$sudah = new Formulir();
						$statementsoal= " AND B.PEGAWAI_ID = ".$tempUserPelamarId."";
						$statementjawaban= " AND A.PEGAWAI_ID = ".$tempUserPelamarId."";
						$statementqinta= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 2";
						$statementeselon= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 3";
						$statementpelaksana= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 4";

						$countcriticalsoal = $sudah->getCountByParamsCriticalSoal(array(), $statementsoal);
						$countcriticaljawaban = $sudah->getCountByParamsCriticalJawaban(array(), $statementjawaban);
						// echo $sudah->query; exit;
						$countqinta = $sudah->getCountByParamsQInta(array(), $statementqinta);
						$counteselon = $sudah->getCountByParamsQInta(array(), $statementeselon);
						$countpel = $sudah->getCountByParamsQInta(array(), $statementpelaksana);

						if($countcriticalsoal == 12 && $countcriticaljawaban == 2 ){
							$img = "icon-sudah";
						}
						else{
							$img = "icon-belum";
							// echo $countcriticaljawaban; exit;
						}

						if($countqinta == $totalQInta ){
							$imginta = "icon-sudah";
						}
						else{
							$imginta = "icon-belum";
							// echo $totalQInta."-".$countqinta; 
						}

						if($counteselon == 19){
							$imgeselon= "icon-sudah";
						}
						else{
							$imgeselon = "icon-belum";
						}

						if($countpel == 11){
							$imgpel= "icon-sudah";
						}
						else{
							$imgpel = "icon-belum";
						}

						if($tempUserStatusJenis==1||$tempUserStatusJenis==2||$tempUserStatusJenis==3){
							if($reqStatusPegawaiId==''){?>
							
							<div class="submenu" id='formulir'>
								<ul>
									<li><a href="?pg=formulir_q_kompetensi_pelaksana" <? if($pg == "formulir_q_kompetensi_pelaksana") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["formulir_q_kompetensi_pelaksana"]?>
									
				                            <span><img src="../WEB/images/<?=$imgpel?>.png" /></span>                            
		                            </a>
									</li>
								</ul>
							</div> 
							<?}
							else{?>
							<div class="submenu" id='formulir'>
								<ul>
									<li><a href="?pg=formulir_critical_incident" <? if($pg == "formulir_critical_incident") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["formulir_critical_incident"]?>
		                          
				                            <span><img src="../WEB/images/<?=$img?>.png" /></span>                            
		                          	</a>
									</li>
									<li><a href="?pg=formulir_q_inta" <? if($pg == "formulir_q_inta") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["formulir_q_inta"]?>
								          <span><img src="../WEB/images/<?=$imginta?>.png" /></span>                            
		                        	</a>
									</li>
									<li><a href="?pg=formulir_q_kompetensi_eselon" <? if($pg == "formulir_q_kompetensi_eselon") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["formulir_q_kompetensi_eselon"]?>
									
				                            <span><img src="../WEB/images/<?=$imgeselon?>.png" /></span>                            
		                            </a>
									</li>
								</ul>
							</div> 
							<?}
						}else{?>
						<div class="submenu">						 
							<?

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
										<li><a href="?pg=formulir_critical_incident" <? if($pg == "formulir_critical_incident") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["formulir_critical_incident"]?>
			                          
					                            <span><img src="../WEB/images/<?=$img?>.png" /></span>                            
			                          	</a>
										</li>
										<li><a href="?pg=formulir_q_inta" <? if($pg == "formulir_q_inta") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["formulir_q_inta"]?>
									          <span><img src="../WEB/images/<?=$imginta?>.png" /></span>                            
			                        	</a>
										</li>
										<li><a href="?pg=formulir_q_kompetensi_eselon" <? if($pg == "formulir_q_kompetensi_eselon") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["formulir_q_kompetensi_eselon"]?>
										
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
								<li><a href="?pg=formulir_q_kompetensi_pelaksana" <? if($pg == "formulir_q_kompetensi_pelaksana") { ?> class="submenu-current" <? } ?>>&nbsp; • <?=$arrayJudul["index"]["formulir_q_kompetensi_pelaksana"]?>
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
						<?}?>
					</div>
					<?
					}
					?>
	                 </div>
	            </div>
	        </div>        
	    </div>
	</div>

	<footer class="footer" style="color: black;background-color: transparent;">
	     © <? echo date('Y'); ?> Pemerintah Provinsi Kalimantan Timur. All Rights Reserved.
	</footer>
<?}?>
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
  
  <style type="text/css">
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
	function show(values){  
		console.log('xxxx');
		$('#mainmenu').hide();
		$('#isianformulir').hide();
		$('#formulir').hide();
		$('#'+values).show();
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
