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

$dateNow= date("d-m-Y");

$reqPenggalianId= httpFilterGet("reqPenggalianId");


$index_loop= 0;
$arrPenggalian="";
$statement= " AND B.ASESOR_ID = ".$tempAsesorId." AND DATE_FORMAT(C.TANGGAL_TES, '%d-%m-%Y') = '".$dateNow."' AND B.JADWAL_ASESOR_ID = ".$reqPenggalianId;
$set= new JadwalAsesor();
$set->selectByParamsPenggalian(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrPenggalian[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrPenggalian[$index_loop]["NAMA"]= $set->getField("NAMA");
	$arrPenggalian[$index_loop]["KELOMPOK"]= $set->getField("KELOMPOK");
	$arrPenggalian[$index_loop]["RUANG"]= $set->getField("RUANG");
	$arrPenggalian[$index_loop]["ASESOR"]= $set->getField("ASESOR");
	$index_loop++;
}
$jumlah_penggalian= $index_loop;

$index_loop= 0;
$arrAsesorPegawai="";
$statement= " AND B.ASESOR_ID = ".$tempAsesorId." AND DATE_FORMAT(E.TANGGAL_TES, '%d-%m-%Y') = '".$dateNow."' AND   B.JADWAL_ASESOR_ID  = ".$reqPenggalianId;
$set= new JadwalAsesor();
$set->selectByParamsAsesorPegawai(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrAsesorPegawai[$index_loop]["JADWAL_PEGAWAI_ID"]= $set->getField("JADWAL_PEGAWAI_ID");
	$arrAsesorPegawai[$index_loop]["PUKUL1"]= $set->getField("PUKUL1");
	$arrAsesorPegawai[$index_loop]["PUKUL2"]= $set->getField("PUKUL2");
	$arrAsesorPegawai[$index_loop]["NIP"]= $set->getField("NIP");
	$arrAsesorPegawai[$index_loop]["NAMA"]= $set->getField("NAMA");
	$index_loop++;
}
$jumlah_asesor_pegawai= $index_loop;

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
                        	<div class="breadcrumb"><a href="index.php"><i class="fa fa-home"></i> Home</a> &raquo; Data Kegiatan</div>
                        	
                        	<div class="judul-halaman">Info Kegiatan :</div>
                        	<div class="area-table-assesor">
                            	<table>
                                <tbody>
                                	<?
									if($jumlah_penggalian > 0)
									{
									?>
                                	<tr>
                                    	<td>Nama Kegiatan</td>
                                        <td>:</td>
                                        <td><strong><?=$arrPenggalian[0]["NAMA"]?></strong></td>
                                    </tr>
                                    <tr>
                                    	<td>Kelompok</td>
                                        <td>:</td>
                                        <td><?=$arrPenggalian[0]["KELOMPOK"]?></td>
                                    </tr>
                                    <tr>
                                    	<td>Ruang</td>
                                        <td>:</td>
                                        <td><?=$arrPenggalian[0]["RUANG"]?></td>
                                    </tr>
                                    <tr>
                                    	<td valign="top">Asesor</td>
                                        <td valign="top">:</td>
                                        <td valign="top">
                                        	<?=$arrPenggalian[0]["ASESOR"]?>
                                        	<?php /*?><ul>
                                            	<li>Magda</li>
                                                <li>Dira</li>
                                            </ul><?php */?>
                                        </td>
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
                                <div class="judul-halaman">Data Kelompok yang diasesor pada tanggal : <?=getFormattedDate(dateToPageCheck($dateNow))?></div>
                            	<table style="margin-bottom:60px;">
                                <thead>
                                	<tr>
                                    	<th>Waktu</th>
                                        <th>Peserta</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
    								<?
									for($checkbox_index=0;$checkbox_index < $jumlah_asesor_pegawai;$checkbox_index++)
									{
									?>
                                	<tr>
                                    	<td><?=$arrAsesorPegawai[$checkbox_index]["PUKUL1"]?> - <?=$arrAsesorPegawai[$checkbox_index]["PUKUL2"]?></td>
                                        <td>Nip: <?=$arrAsesorPegawai[$checkbox_index]["NIP"]?>, Nama: <?=$arrAsesorPegawai[$checkbox_index]["NAMA"]?></td>
                                        <td><a href="penilaian_pegawai.php?reqPenggalianId=<?=$reqPenggalianId?>&reqJadwalPegawaiId=<?=$arrAsesorPegawai[$checkbox_index]["JADWAL_PEGAWAI_ID"]?>">Lihat Data Peserta <i class="fa fa-chevron-circle-right"></i></a></td>
                                    </tr>
                                    <?
									}
									if($checkbox_index == 0)
									{
                                    ?>
                                    <tr>
                                    	<td colspan="3">Tidak Ada Peserta</td>
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
