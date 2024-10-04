<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalAsesor.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$tempAsesorId= $userLogin->userAsesorId;

if($tempAsesorId == "")
{
	echo '<script language="javascript">';
	echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
	echo 'top.location.href = "../main/login.php";';
	echo '</script>';		
	exit;
}

$set= new Asesor();
$set->selectByParams(array(), -1,-1, " AND A.ASESOR_ID = ".$tempAsesorId);
$set->firstRow();
$tempAsesorNama= $set->getField("NAMA");
unset($set);

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

//$dateNow= date("d-m-Y");

$index_loop= 0;
$arrAsesor="";
$statement= " AND (A.STATUS_PENILAIAN = '' OR A.STATUS_PENILAIAN IS NULL) AND COALESCE(B.JUMLAH_PESERTA,0) > 0 AND A.JADWAL_TES_ID IN (SELECT X.JADWAL_TES_ID FROM jadwal_asesor X WHERE X.ASESOR_ID = ".$tempAsesorId." GROUP BY X.JADWAL_TES_ID) ";
$set= new JadwalAsesor();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrAsesor[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrAsesor[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
	$arrAsesor[$index_loop]["BATCH"]= $set->getField("BATCH");
	$arrAsesor[$index_loop]["ACARA"]= $set->getField("ACARA");
	$arrAsesor[$index_loop]["TEMPAT"]= $set->getField("TEMPAT");
	$arrAsesor[$index_loop]["ALAMAT"]= $set->getField("ALAMAT");
	$arrAsesor[$index_loop]["JUMLAH_PESERTA"]= $set->getField("JUMLAH_PESERTA");
	$arrAsesor[$index_loop]["KODE"]= $set->getField("KODE");
	$arrAsesor[$index_loop]["KELOMPOK_RUANGAN_NAMA"]= $set->getField("KELOMPOK_RUANGAN_NAMA");
	$arrAsesor[$index_loop]["TANGGAL_TES"]= dateToPageCheck($set->getField("TANGGAL_TES"));
	$index_loop++;
}
$jumlah_asesor= $index_loop;
//$jumlah_asesor= 0;
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
                    	Selamat datang, <strong><?=$tempAsesorNama?></strong> - 
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
                        	<div class="breadcrumb"><i class="fa fa-home"></i> Home</div>
                        	<div class="area-table-assesor">
                                <div class="judul-halaman">Kegiatan yang ada :</div>
                            	<table>
                                <?
								$tempAcara= "";
								for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
								{
									if($tempAcara == $arrAsesor[$checkbox_index]["ACARA"]){}
									else
									{
                                ?>
                                	<tr>
                                        <th colspan="5" style="padding:0px">&nbsp;</th>
                                    </tr>
                                	<tr>
                                    	<td>Acara</td>
                                        <td colspan="4">: <?=$arrAsesor[$checkbox_index]["ACARA"]?></td>
                                    </tr>
                                    <tr>
                                    	<td>Tempat</td>
                                        <td colspan="4">: <?=$arrAsesor[$checkbox_index]["TEMPAT"]?></td>
                                    </tr>
                                    <tr>
                                    	<td>Alamat</td>
                                        <td colspan="4">: <?=$arrAsesor[$checkbox_index]["ALAMAT"]?></td>
                                    </tr>
                                	<tr>
                                    	<th>Tanggal Tes</th>
                                        <th>Kode</th>
                                        <th>Ruang</th>
                                        <th>Jumlah Peserta</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                <?
									}
                                ?>
                                	<tr>
                                    	<td><?=$arrAsesor[$checkbox_index]["TANGGAL_TES"]?></td>
                                        <td><?=$arrAsesor[$checkbox_index]["KODE"]?></td>
                                        <td><?=$arrAsesor[$checkbox_index]["KELOMPOK_RUANGAN_NAMA"]?></td>
                                        <td><?=$arrAsesor[$checkbox_index]["JUMLAH_PESERTA"]?></td>;
                                        <td><a href="kegiatan.php?reqJadwalTesId=<?=$arrAsesor[$checkbox_index]["JADWAL_TES_ID"]?>&reqJadwalAsesorId=<?=$arrAsesor[$checkbox_index]["JADWAL_ASESOR_ID"]?>">Lihat Data Kegiatan <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                <?
								$tempAcara= $arrAsesor[$checkbox_index]["ACARA"];
								}
                                ?>
                                </table>
                                <div style="margin:20px">&nbsp;</div>
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
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 
    
</body>
</html>
