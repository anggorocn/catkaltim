<?
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/UserLogin.php");

include_once("../WEB/functions/crfs_protect.php");

$csrf = new crfs_protect('_crfs_login');

/* PARAMETERS */
$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");
$reqMode = httpFilterRequest("reqMode");
$reqUser = httpFilterPost("reqUser");
$reqPasswd = httpFilterPost("reqPasswd");
$reqSecurity= httpFilterPost("reqSecurity");

/* ACTIONS BY reqMode */
if($reqMode == "submitLogin" && $reqUser != "" && $reqPasswd != "") 
{
	if(md5($reqSecurity) == $_SESSION['security_code'])
	{
		if (!$csrf->isTokenValid($_POST['_crfs_login']))
		{
			echo '<script language="javascript">';
			echo 'alert("Username atau password anda masih salah.");';
			echo 'top.location.href = "login.php";';
			echo '</script>';		
			exit;
		}
		
		$userLogin->resetLogin();
		if ($userLogin->verifyUserLogin($reqUser, $reqPasswd)) 
		{	
			// if($userLogin->userPegawaiId=="")
			// {	
			// 	header("location:index.php");
			// }
			// else
			// {
			// 	header("location:../asesor/index.php");
			// }
			// exit;
			header("location:index.php");
		}
		else
		{
			echo '<script language="javascript">';
			echo 'alert("Username atau password anda masih salah.");';
			echo 'top.location.href = "login.php";';
			echo '</script>';		
			exit;
		}
	}
	else
	{
		echo '<script language="javascript">';
		echo 'alert("Captcha yang anda ketik masih salah.");';
		echo 'top.location.href = "login.php";';
		echo '</script>';		
		exit;
	}
}
else if ($reqMode == "submitLogout")
{
	$userLogin->resetLogin();
	$userLogin->emptyUsrSessions();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aplikasi Assessment Center - Pemerintah Provinsi Kalimantan Timur</title>

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css/gaya-main.css" type="text/css">
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
	} 
	</style>

	<script type='text/javascript' src="../WEB/lib/js/jquery.js"></script> 
	<script type="text/javascript">
		function reloadcaptchadinamis(value, json)
		{
			$('#'+value).attr('src', json+'?random=' + (new Date).getTime()+'width=100&amp;height=40&amp;characters=5');
		}
	</script>
    

</head>

<body>

<div id="wrap-utama" style="height:100%;">
    <div id="main" class="container-fluid clear-top" style="height:100%;">
		
        <div class="row">
        	<div class="col-md-12 area-header">
        		<span class="judul-app"><img src="../WEB/images/logo-judul.png"> Sistem Manajemen Assesment Center</span>
				
            </div>
        </div>
        
        <div class="row">
        	<div class="col-md-12" style="height:100%;">
        		<div class="row" style="height:100%;">
                	
                    <div class="col-md-12">
                		<div class="row">
                            <div class="col-md-12 area-logo-login">
                            </div>
							
							<div class="col-xs-12 col-md-4 col-sm-offset-2 col-md-offset-4 area-login">
                            	<!-- <span class="judul-app" style="color: black">Sistem Manajemen Assesment Center</span></span> -->
                            
                                <form role="form" method="post" action="">
                                    <fieldset> 
                                        <div class="form-group">
                                            <input type="text" name="reqUser" id="reqUser" class="form-control input-lg" placeholder="Username">
                                        </div>
                                        <div class="form-group" style="height: 40px" >
                                            <div class="col-md-10" style="padding-right: 0px;padding-left: 0px">
	                                            <input type="password" name="reqPasswd" id="reqPasswd" class="form-control input-lg" placeholder="Password">                                       	
                                            </div>
                                            <div class="col-md-2" style="padding-right: 0px;padding-left: 0px">
	                                            <div class="input-group-addon" style="height: 40px;"  onclick="showPass()">
										        	<i class="fa fa-eye-slash" id="eye" aria-hidden="true"></i>
										      	</div>                                            	
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                        	<img src="../WEB/functions/CaptchaSecurityImages.php?width=100&amp;height=40&amp;characters=5" id="captchaImage" />&nbsp;&nbsp;&nbsp;<img src="../WEB/functions/refresh.png" 
                                        	onclick="reloadcaptchadinamis('captchaImage', '../WEB/functions/CaptchaSecurityImages.php')" style="cursor:pointer" title="refresh captcha">
                                        	<input name="reqSecurity" id="reqSecurityDaftar" class="form-control input-lg" type="text" placeholder="Ketik Captcha" />
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <input name="slogin_POST_send" type="submit" class="btn btn-lg btn-success btn-block" value="Login" alt="DO LOGIN!" >
                                                <input type="hidden" name="reqMode" value="submitLogin">
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                            	<input type="reset" class="btn btn-lg btn-warning btn-block" value="Reset"> 
                                            </div>
                                        </div>
                                    </fieldset>

                                    <?=$csrf->echoInputField();?>

                                </form>
                            </div>
						</div>
                    </div>
                </div>
            </div>
		</div>
        
        
        
    </div>
</div>

    
<script type='text/javascript' src="../WEB/lib/bootstrap/bootstrap.js"></script> 
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
</body>
</html>
<script type="text/javascript">
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