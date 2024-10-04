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

$dateNow= date("d-m-Y");

$index_loop= 0;
$arrAsesor="";
$statement= " AND A.JADWAL_TES_ID IN (SELECT X.JADWAL_TES_ID FROM jadwal_asesor X WHERE X.ASESOR_ID = ".$tempAsesorId." GROUP BY X.JADWAL_TES_ID) AND DATE_FORMAT(A.TANGGAL_TES, '%d-%m-%Y') = '".$dateNow."' ";
$set= new JadwalAsesor();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrAsesor[$index_loop]["BATCH"]= $set->getField("BATCH");
	$arrAsesor[$index_loop]["ACARA"]= $set->getField("ACARA");
	$arrAsesor[$index_loop]["TEMPAT"]= $set->getField("TEMPAT");
	$arrAsesor[$index_loop]["ALAMAT"]= $set->getField("ALAMAT");
	$arrAsesor[$index_loop]["JUMLAH_PESERTA"]= $set->getField("JUMLAH_PESERTA");
	$index_loop++;
}
$jumlah_asesor= $index_loop;
//$jumlah_asesor= 0;

$index_loop= 0;
$arrPenggalian="";
$statement= " AND B.ASESOR_ID = ".$tempAsesorId." AND DATE_FORMAT(C.TANGGAL_TES, '%d-%m-%Y') = '".$dateNow."' ";
$set= new JadwalAsesor();
$set->selectByParamsPenggalian(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
//	$arrPenggalian[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrPenggalian[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
	$arrPenggalian[$index_loop]["NAMA"]= $set->getField("NAMA");
	$arrPenggalian[$index_loop]["KODE"]= $set->getField("KODE");
	$index_loop++;
}
$jumlah_penggalian= $index_loop;
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
                        	
                        	<div class="judul-halaman">Info Kegiatan :</div>
                        	<div class="area-table-assesor">
                            	<table>
                                <tbody>
                                <?
								if($jumlah_asesor > 0)
								{
                                ?>
                                	<tr>
                                    	<td>Hari / Tanggal</td>
                                        <td>:</td>
                                        <td><strong><?=getFormattedDate(dateToPageCheck($dateNow))?></strong></td>
                                    </tr>
                                    <tr>
                                    	<td>Acara</td>
                                        <td>:</td>
                                        <td><?=$arrAsesor[0]["ACARA"]?></td>
                                    </tr>
                                    <tr>
                                    	<td>Peserta</td>
                                        <td>:</td>
                                        <td><strong><?=$arrAsesor[0]["JUMLAH_PESERTA"]?> Peserta</strong></td>
                                    </tr>
                                    <tr>
                                    	<td>Tempat</td>
                                        <td>:</td>
                                        <td><?=$arrAsesor[0]["TEMPAT"]?></td>
                                    </tr>
                                    <tr>
                                    	<td>Alamat</td>
                                        <td>:</td>
                                        <td><?=$arrAsesor[0]["ALAMAT"]?></td>
                                    </tr>
                                <?
								}
								else
								{
                                ?>
                                	<tr>
                                    	<td>Tidak Ada Kegiatan</td>
                                    </tr>
                                <?
								}
                                ?>
                                </tbody>
                                </table>
                                
                                <br>
                                <div class="judul-halaman">Kegiatan yang ada :</div>
                            	<table>
                                <thead>
                                	<tr>
                                    	<th>Nama Kegiatan</th>
                                        <th>Kode</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?
								for($checkbox_index=0;$checkbox_index < $jumlah_penggalian;$checkbox_index++)
								{
                                ?>
                                	<tr>
                                    	<td><?=$arrPenggalian[$checkbox_index]["NAMA"]?></td>
                                        <td><?=$arrPenggalian[$checkbox_index]["KODE"]?></td>
                                        <?php /*?><td><a href="kegiatan.php?reqPenggalianId=<?=$arrPenggalian[$checkbox_index]["PENGGALIAN_ID"]?>">Lihat Data Kegiatan <i class="fa fa-chevron-circle-right"></i></a></td><?php */?>
                                        <td><a href="kegiatan.php?reqPenggalianId=<?=$arrPenggalian[$checkbox_index]["JADWAL_ASESOR_ID"]?>">Lihat Data Kegiatan <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                <?
								}
                                ?>
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
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 
    
</body>
</html>
