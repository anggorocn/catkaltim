<?
include_once("../WEB/setup/defines.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/SessionLog.php");
include_once("../WEB/classes/base-cat/SessionLoginLog.php");
include_once("../WEB/classes/utils/crfs_protect.php");

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

function get_ip_address() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}

$reqMode = httpFilterRequest("reqMode");
$reqUser = httpFilterPost("reqUser");
$reqPasswd = httpFilterPost("reqPasswd");
$reqIp = httpFilterPost("reqIp");
$mode = httpFilterGet("mode");
// echo $mode; exit;
$tempIpUser= ( preg_match( "/^([d]{1,3}).([d]{1,3}).([d]{1,3}).([d]{1,3})$/", $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] );
$tempIpUser= get_ip_address();

/* ACTIONS BY reqMode */
if ($mode!='simulasi'){
	if ($reqMode == "submitLogin" && $reqUser != "" && $reqPasswd != "") 
	{
		$csrf = new crfs_protect('_crfs_login');
		if (!$csrf->isTokenValid($_POST['_crfs_login']))
		exit();

		$userLogin->resetLogin();
		if ($userLogin->verifyUjianUserLogin($reqUser, $reqPasswd)) 
		{
			//$statement_session= " AND NID = ".$userLogin->ujianUid." AND APLIKASI_ID_LOG = '1' AND EXTRACT(HOUR FROM NOW() - LOGIN_DATE) < 1";
			$statement_session= " AND NID = ".$userLogin->ujianUid." AND APLIKASI_ID_LOG = '1' AND CURRENT_DATE = TO_DATE(CAST(LOGIN_DATE AS TEXT), 'YYYY-MM-DD')";
			$set_session= new SessionLog();
			$set_session->getSelect($statement_session);
			//echo $set_session->query;exit;
			$set_session->firstRow();
			$tempNid= $set_session->getField("NID");
			$tempDataIpUser= $set_session->getField("IP_ADDRESS");
			
			//echo $set_session->query;exit;
			unset($set_session);
			
			//log login
			$set_session= new SessionLoginLog();
			$set_session->setField("NID", $userLogin->ujianUid);
			$set_session->setField("APLIKASI_ID_LOG", "1");
			$set_session->setField("LOGIN_DATE", "NOW()");
			$set_session->setField("IP_ADDRESS", $reqIp);
			$set_session->insert();
			unset($set_session);
			
			$tempNid= "";
			//kalau tidak ada nid maka tidak ada yg pakai
			if($tempNid == "")
			{
				//$tempIpUser
				$set_session= new SessionLog();
				$set_session->setField("NID", $userLogin->ujianUid);
				$set_session->setField("APLIKASI_ID_LOG", "1");
				$set_session->setField("LOGIN_DATE", "NOW()");
				$set_session->setField("IP_ADDRESS", $reqIp);
				$set_session->insert();
				unset($set_session);
				
				header("location:index.php?pg=dashboard");
				exit;
			}
			elseif($tempDataIpUser !== "")
			{
				if($tempDataIpUser == $reqIp)
				{
					header("location:index.php?pg=selamat_datang");
					exit;
				}
				else
				{
					$userLogin->resetLogin();
					$userLogin->emptyUsrSessions();
					echo '<script language="javascript">';
					echo 'alert("Anda pernah login di lokasi lain, tunggu satu hari dari login terakhir untuk login selanjutnya");';
					echo 'document.location.href = "index.php";';
					echo '</script>';
					exit;
				}
			}
			else
			{
				$userLogin->resetLogin();
				$userLogin->emptyUsrSessions();
				echo '<script language="javascript">';
				echo 'alert("Login ada sedang di pakai pada komputer lain");';
				echo 'document.location.href = "index.php";';
				echo '</script>';
				//header("location:index.php");
				exit;
			}
		}
		else
		{
			echo '<script language="javascript">';
			echo 'alert("nip '.$reqUser.' tidak ada pada sistem, atau password yang ada ketik salah, silakan login ulang.");';
			echo 'document.location.href = "index.php";';
			echo '</script>';
			exit;
		}
	}
	else if ($reqMode == "submitLogout")
	{
		$statement_session= " AND APLIKASI_ID_LOG = '1' AND CURRENT_DATE = TO_DATE(CAST(LOGIN_DATE AS TEXT), 'YYYY-MM-DD') AND IP_ADDRESS = '".$tempIpUser."'";
		$set_session= new SessionLog();
		$set_session->setField("NID", $userLogin->ujianUid);
		$set_session->deleteKondisi($statement_session);
		unset($set_session);
		
		$userLogin->resetLogin();
		$userLogin->emptyUsrSessions();
	}

	$tempPegawaiId= $userLogin->pegawaiId;
	$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
	$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
	$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
	$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;

	$tempSystemTanggalNow= date("d-m-Y");

	if($userLogin->ujianUid == "")
	{
		if($pg == "" || $pg == "home"){}
		else
		{
			echo '<script language="javascript">';
			echo 'top.location.href = "index.php";';
			echo '</script>';
			exit;
		}
	}
	else
	{
		if($pg == "" || $pg == "home")
		{
			echo '<script language="javascript">';
			echo 'top.location.href = "index.php?pg=dashboard";';
			echo '</script>';
			exit;
		}
	}

	if($tempPegawaiId == "")
	{
		$statement_session= " AND APLIKASI_ID_LOG = '1' AND CURRENT_DATE = TO_DATE(CAST(LOGIN_DATE AS TEXT), 'YYYY-MM-DD') AND IP_ADDRESS = '".$tempIpUser."'";
		$set_session= new SessionLog();
		$set_session->setField("NID", $userLogin->ujianUid);
		$set_session->deleteKondisi($statement_session);
		unset($set_session);

		$userLogin->resetLogin();
		$userLogin->emptyUsrSessions();
	}
	else
	{
		$tempUjianId= $ujianPegawaiUjianId;
		if($tempUjianId == ""){}
		else
		{
			$set_detil= new UjianPegawaiDaftar();
			$set_detil->setField("UJIAN_ID", $tempUjianId);
			$set_detil->setField("PEGAWAI_ID", $tempPegawaiId);
			$set_detil->setField("FIELD", "STATUS_LOGIN");
			$set_detil->setField("FIELD_VALUE", "1");
			$set_detil->updateStatusLog();
			// echo $set_detil->query;exit();
			unset($set_detil);
		}
	}
}

// echo $pg; exit;

//$userLogin->resetLogin();
//$userLogin->emptyUsrSessions();


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title style="color: #009F3B;">CAT PENILAIAN KOMPETENSI</title>

<link rel="shortcut icon" href="../WEB/images/favicon.ico" type="image/x-icon">
<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib-ujian/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css-ujian/gaya.css" type="text/css">
<link rel="stylesheet" href="../WEB/lib-ujian/font-awesome/4.5.0/css/font-awesome.css">
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

    
<!--<script type='text/javascript' src="../WEB/lib-ujian/bootstrap/jquery.js"></script> -->

    <style>
	.col-md-12{
		*padding-left:0px;
		*padding-right:0px;
	}
	</style>
    
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
	
	function disableBackButton()
	{
		setTimeout("preventPageLoad()", 1);
	}
	
	function preventPageLoad()
	{
		try {
		history.forward();
		} 
		catch (e) {    
		}
		// Try again every 200 milisecs. 
		setTimeout("preventPageLoad()", 100);
	}

	function iecompattest(){
	return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
	}

	function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
	{
		opCaption= "Modul Cat";
		//var left= top = "";
		// var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
		// var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
		
		opWidth = iecompattest().clientWidth - 800;
		opHeight = iecompattest().clientHeight - 400;
		//divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
		divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,center=1,resize=1,scrolling=1,midle=1'); return false;
		
	}
	function OpenDHTML2(opAddress, opCaption, opWidth, opHeight)
	{
		opCaption= "Video Tata Cara Test";
		//var left= top = "";
		// var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
		// var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
		
		opWidth = iecompattest().clientWidth - 600;
		opHeight = iecompattest().clientHeight - 125;
		//divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
		divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,center=1,resize=1,scrolling=1,midle=1'); return false;
		
	}
	function OpenDHTMLDetil(opAddress, opCaption, opWidth, opHeight)
	{
		opCaption= "Modul Cat";
		var left = (screen.width/2)-(opWidth/2);
		var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
		//alert(top);return false;
		var width  = opWidth;
		var height = opHeight;
		var params = 'width='+width+', height='+height;
		params += ', top='+top+', left='+left;
		params += ', directories=no';
		params += ', location=no';
		params += ', menubar=no';
		params += ', resizable=no';
		params += ', scrollbars=no';
		params += ', status=no';
		params += ', toolbar=no';
		params += ', center=yes';
		divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
		
		//divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+',center=1,resize=1,scrolling=1,midle=1'); return false;
	}

	function OpenDHTMLCenter(opAddress, opCaption, opWidth, opHeight)
	{
		opCaption= "Modul Cat";
		var width  = opWidth;
		var height = opHeight;
		var left   = (screen.width  - width)/2;
		var top    = (screen.height - height)/2;
		var params = 'width='+width+', height='+height;
		params += ', top='+top+', left='+left;
		params += ', directories=no';
		params += ', location=no';
		params += ', menubar=no';
		params += ', resizable=no';
		params += ', scrollbars=no';
		params += ', status=no';
		params += ', toolbar=no';
		params += ', center=yes';
		divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
	}
	
	<?
	if($userLogin->ujianUid == ""){}
	else
	{
	?>
	$(document).ready(function () {
        var idleState = false;
        var idleTimer = null;
        $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
			clearTimeout(idleTimer);
			if (idleState == true) { 
				// alert('Sesi anda telah berakhir.');
				// document.location.href= 'index.php?reqMode=submitLogout';
            }
			idleState = false;
            idleTimer = setTimeout(function () { 
                idleState = true; }, 1000000);
        });
		//300000
        $("body").trigger("mousemove");
    });
	
	<!-- JIKA POSISI LOGIN CHECK APAKAH SUDAH ADA LOGOUT DARI APLIKASI LAIN -->
	$(window).focus(function() {
	});
	<?
	}
	?>
	</script>

    
</head>

<body onLoad="disableBackButton()">
    
    <div class="container-fluid">
    	
        <div class="row">
        	<div class="col-md-12 padding-kiri-0 padding-kanan-0">
            	<?
                if($pg == "" || $pg == "home"){} 
				else
				{
				?>
            	<div class="area-header">
                	<div class="area-header-inner">
                        <div class="logo"><img src="../WEB/images-ujian/logo-nobg-small.png"></div>
                        <div class="judul">
                            <div>ASSESSMENT CENTER</div>
                            <div>PEMERINTAH PROVINSI KALIMANTAN TIMUR</div>
                            <?
                            if($pg=='ujian_pilihan'){
                            ?>
                            <div class="setuju" style="float:right; margin-top:-60px;">
                            	<!-- <a onclick="openPopup()" style="cursor:pointer;" >Ubah Password</a> -->
                            	<a href="index.php?pg=upload_ujian" style="width: 200px;">SIMULASI KOMPETENSI</a>
                            </div>
                            <?
                            }
                            else if($pg=='tes'){
                            ?>
                            <div class="setuju" style="float:right; margin-top:-60px;">
                            	<!-- <a onclick="openPopup()" style="cursor:pointer;" >Ubah Password</a> -->
                            	<!-- <a href="index.php?pg=upload_ujian" >Analis Kasus</a> -->
                            	<a href="index.php?reqMode=submitLogout" >Kembali</a>
                            </div>
                            <?
                            }
							else if(isStrContain($pg, "lengkapi_data") == true || isStrContain($pg, "form_persetujuan") == true || isStrContain($pg, "ujian_mukadimah") == true
							|| isStrContain($pg, "ujian") == true
							|| isStrContain($pg, "finish") == true
							/*|| isStrContain($pg, "ujian_online") == true || isStrContain($pg, "finish") == true
							|| isStrContain($pg, "ujian_tryout") == true || isStrContain($pg, "finish_tryout") == true || isStrContain($pg, "ujian_pilihan") == true*/
							){}
							else
							{
                            ?>
                            <div class="setuju" style="float:right; margin-top:-60px;">
                            	<!-- <a onclick="openPopup()" style="cursor:pointer;" >Ubah Password</a> -->
                            	<a href="index.php?reqMode=submitLogout" >Logout</a>
                            </div>
                            <?
							}
                            ?>
                        </div>
                    </div>
                </div>
                <?
				}
				?>
                
                <?
                if(($pg == "" || $pg == "home") && $userLogin->UID == "")
				{
				?>
                <div class="area-main-login">
                <?
                }
				else if(($pg == "ujian_online" || $pg == "ujian_pilihan") && $userLogin->pegawaiId !== "")
				{
				?>
                <div class="area-main">
                <?
				}
				else
				{
				?>
                <div class="area-main">
                <?
				}
				?>
                	<?php
                    $includePage = $page_to_load->loadPage();
                    include_once($includePage);
                    ?>	
                </div>
                <?if($pg!="ujian_online"){?>
	                <div class="area-footer">
	                	 Copyright © 2021 PEMERINTAH PROVINSI KALIMANTAN TIMUR
	                </div>
	            <?}?>
                
            </div>
        </div>
        
    </div>
    <!-- /.container --> 
    
    <script type='text/javascript' src="../WEB/lib-ujian/bootstrap/angular.js"></script> 
    
    <?
	if($pg == "ujian_online" || $pg == "ujian_onlineBak"){
	?>
    <?php /*?><script src="../WEB/lib-ujian/bootstrap/jquery.min.js"></script>
	<script src="../WEB/lib-ujian/bootstrap/jquery.easing.min.js"></script>
    <script src="../WEB/lib-ujian/bootstrap/jquery.touchSwipe.min.js"></script><?php */?>
    
    <?php /*?><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script><?php */?>
    <!--<script src="../WEB/lib-ujian/liquidslider-master/js/jquery.liquid-slider.min.js"></script>  -->
    <?php /*?><script src="../WEB/lib-ujian/liquidslider-master/src/js/jquery.liquid-slider.js"></script>
    
    <script>
    $('#main-slider').liquidSlider();
    </script><?php */?>
    <?
	}
	?>
	
</body>
</html>