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
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TVRI - Televisi Republik Indonesia</title>

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib-ujian/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css-ujian/gaya.css" type="text/css">
<link rel="stylesheet" href="../WEB/lib-ujian/font-awesome/4.5.0/css/font-awesome.css">
    
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
	function nextModul()
	{
		document.location.href= "index.php";
	}
	</script>

    
</head>

<body onLoad="disableBackButton()">
    
    <div class="container-fluid">
    	
        <div class="row">
        	<div class="col-md-12 padding-kiri-0 padding-kanan-0">
            	<div class="area-header">
                	<div class="area-header-inner">
                        <div class="logo"><img src="../WEB/images-ujian/logo-header.png"></div>
                        <div class="judul">
                            <div>TVRI</div>
                            <div>Televisi Republik Indonesia</div>
                        </div>
                    </div>
                </div>
                <div class="area-main">
                	<div class="container utama">
						<div class="row">
					    	<div class="col-md-12">
								<div class="area-judul-halaman">Form Konfirmasi</div>
					        </div>
					        
					        <div class="col-md-12">
					       	  <div class="area-persetujuan">
					          	<input type="hidden" name="reqJumlahInfo" id="reqJumlahInfo" value="0" />
					           	<ul>
									<li>Peserta agar mengikuti CAT sesuai jadwal yang telah ditetapkan.</li> 
									<li>Bagi peserta yang <b>tidak mengikuti CAT pada jadwal yang telah ditetapkan</b>, dinyatakan <b>gugur</b> dengan sendirinya.</li>
					                <li>Pastikan koneksi intenet dalam keadaan baik</li>
					                <li>Jika terkendala web berhenti, silahkan <b>tekan f5 untuk merefresh web. Atau juga dapat menutup browser dan membuka ulang halaman web cat</b></li>
					              </ul>
					        	</div>
					            
					        </div>
                			<div class="setuju" align="center"><a href="#" onclick="nextModul()">Setuju</a></div>
					        
					    	<!-- <div class="area-prev-next">
					            <div class="next"><a href="#" onclick="nextModul()"><i class="fa fa-chevron-right"></i></a></div>
					        </div> -->
					    
					    </div>
					</div>
               
                </div>
                <div class="area-footer">
                	 Copyright © 2020 Pemerintahan Provinsi Bali
                </div>
                
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