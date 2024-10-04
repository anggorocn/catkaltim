<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

$tempListInfo= $userLogin->userTempList;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aplikasi Pelaporan Hasil Assesment</title>

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css/gaya-main.css" type="text/css">
<link rel="stylesheet" href="../WEB/css/gaya-assesor.css" type="text/css">
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
		color:#FFF;
	}
	@media screen and (max-width:767px) {
		.footer {
			font-size:12px;
		}
	}

	</style>
    
</head>

<body>

<div id="wrap-utama" style="height:100%; ">
    <div id="main" class="container-fluid clear-top" style="height:100%;">
		
        <div class="row">
        	<div class="col-md-12">
            	<div class="area-header">
                	<span class="judul-app"><a href="index.php"><img src="../WEB/images/logo-kemendagri.png"> Aplikasi Pelaporan Hasil Assessment</a></span>
                    
                    <div class="area-akun">
                    	Selamat datang, <strong>Magdalena Doris</strong> (Tim Penilai Jabatan Fungsional Assessor) - 
                    	<a href="../main/login.php?reqMode=submitLogout">Logout</a>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="row" style="height:calc(100% - 20px);">
        	<div class="col-md-12" style="height:100%;">
            	
                
                <div class="container area-menu-app">
                	<div class="row">
                        <div class="col-md-12">
                        	<div class="breadcrumb"><a href="index.php"><i class="fa fa-home"></i> Home</a> &raquo; Data Kegiatan</div>
                        	
                        	<div class="judul-halaman">Info Kegiatan :</div>
                        	<div class="area-table-assesor">
                            	<table>
                                <tbody>
                                	<tr>
                                    	<td>Nama Kegiatan</td>
                                        <td>:</td>
                                        <td><strong>Leaderless Group Discussion (LGD)</strong></td>
                                    </tr>
                                    <tr>
                                    	<td>Kelompok</td>
                                        <td>:</td>
                                        <td>4</td>
                                    </tr>
                                    <tr>
                                    	<td>Ruang</td>
                                        <td>:</td>
                                        <td>LGD 4</td>
                                    </tr>
                                    <tr>
                                    	<td valign="top">Asesor</td>
                                        <td valign="top">:</td>
                                        <td valign="top">
                                        	<ul>
                                            	<li>Magda</li>
                                                <li>Dira</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                                
                                <br>
                                <div class="judul-halaman">Data Kelompok yang diasesor :</div>
                            	<table style="margin-bottom:60px;">
                                <thead>
                                	<tr>
                                    	<th>Waktu</th>
                                        <th>Nomor Peserta</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<tr>
                                    	<td>13.00 - 13.45</td>
                                        <td>33</td>
                                        <td><a href="index3.php">Lihat Data Peserta <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                    <tr>
                                    	<td>13.00 - 13.45</td>
                                        <td>34</td>
                                        <td><a href="index3.php">Lihat Data Peserta <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                    <tr>
                                    	<td>13.00 - 13.45</td>
                                        <td>35</td>
                                        <td><a href="index3.php">Lihat Data Peserta <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                    <tr>
                                    	<td>13.00 - 13.45</td>
                                        <td>36</td>
                                        <td><a href="index3.php">Lihat Data Peserta <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                    <tr>
                                    	<td>13.00 - 13.45</td>
                                        <td>38</td>
                                        <td><a href="index3.php">Lihat Data Peserta <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                    <tr>
                                    	<td>13.00 - 13.45</td>
                                        <td>39</td>
                                        <td><a href="index3.php">Lihat Data Peserta <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
		</div>
        
        
        
    </div>
</div>
<footer class="footer">
	 © 2016 Kementerian Dalam Negeri. All Rights Reserved. 
</footer>



    
<?php /*?>    <div class="container-fluid">
	
	
	<div class="row">
		<div class="col-md-12">
			<div class="area-footer">
			© 2016 Kementerian Dalam Negeri. All Rights Reserved. 
			</div>
		</div>
	</div>
	
</div>
<!-- /.container --> <?php */?>

<?php /*?><script type='text/javascript' src="../WEB/lib/bootstrap/bootstrap.js"></script> <?php */?>
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 
    
</body>
</html>
