<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

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
$reqJadwalPegawaiId= httpFilterGet("reqJadwalPegawaiId");

$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId;
$set= new JadwalPegawai();
$set->selectByParamsJadwalPegawaiInfo(array(), -1,-1, $statement);
//echo $set->query;exit;
$set->firstRow();
$tempPegawaiInfoNama= $set->getField("NAMA_PEGAWAI");
$tempPegawaiInfoJabatan= $set->getField("JABATAN_INI_TES");
$tempPegawaiInfoSatuanKerja= $set->getField("SATUAN_KERJA_INI_TES");
$tempPegawaiInfoNamaAsesi= $set->getField("NAMA_ASESI");
$tempPegawaiInfoMetode= $set->getField("METODE");
$tempPegawaiInfoTanggalTes= $set->getField("TANGGAL_TES");
$tempPegawaiInfoStatusPenilaian= $set->getField("STATUS_PENILAIAN");
$tempPegawaiInfoJadwalTesId= $set->getField("JADWAL_TES_ID");
$tempPegawaiInfoJadwalAsesorId= $set->getField("JADWAL_ASESOR_ID");
unset($set);

$index_loop= 0;
$arrPegawaiNilai="";
$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND B.ASESOR_ID = ".$tempAsesorId;
$set= new JadwalPegawaiDetil();
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ID"]= $set->getField("JADWAL_PEGAWAI_DETIL_ID");
	$arrPegawaiNilai[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrPegawaiNilai[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
	$arrPegawaiNilai[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_INDIKATOR_ID"]= $set->getField("PEGAWAI_INDIKATOR_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_LEVEL_ID"]= $set->getField("PEGAWAI_LEVEL_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"]= $set->getField("PEGAWAI_KETERANGAN");
	$arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrPegawaiNilai[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
	$arrPegawaiNilai[$index_loop]["JUMLAH_LEVEL"]= $set->getField("JUMLAH_LEVEL");
	$index_loop++;
}
$jumlah_pegawai_nilai= $index_loop;
//print_r($arrPegawaiNilai);exit;
$disabled= "";
if($tempPegawaiInfoStatusPenilaian == "1")
$disabled= "disabled";
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
    
    <style>
	.rbtn ul{
		list-style-type:none;
	}
	.rbtn ul li{
		cursor:pointer; 
		*display:inline-block; 
		display:inherit;
		width:100px; 
		border:1px solid #06345f; 
		padding:5px;
		margin:-5px;
		*margin-right:5px; 
		
		-moz-border-radius: 3px; 
		-webkit-border-radius: 3px; 
		-khtml-border-radius: 3px; 
		border-radius: 3px; 
		
		text-align:center;
		
	}
	.over{
		background: #063a69;
	}
	
	.sebelumselected{
		background: #063a69; 
		color:#fff;
		*margin:2px;
	}
	
	.sebelumselected:before{
		font-family:"FontAwesome";
		content:"\f096";
		*margin-right:10px;
		color:#f8a406;
		font-size:18px;
		*vertical-align:middle;
	}
	
	.selected{
		background: #063a69; 
		color:#fff;
	}
	.selected:before{
		font-family:"FontAwesome";
		content:"\f046";
		*margin-right:10px;
		color:#f8a406;
		font-size:18px;
		*vertical-align:middle;
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
                        	<div class="breadcrumb"><a href="index.php"><i class="fa fa-home"></i> Home</a> &raquo; <a href="kegiatan.php?reqJadwalTesId=<?=$tempPegawaiInfoJadwalTesId?>&reqJadwalAsesorId=<?=$tempPegawaiInfoJadwalAsesorId?>">Data Kegiatan</a> &raquo; Data Peserta</div>
                        	
                        	<div class="judul-halaman">Data Peserta Asesor :</div>
                        	<div class="area-table-assesor">
                            	<table>
                                <tbody>
                                	<tr>
                                    	<td style="width:150px">Nama</td>
                                        <td>:</td>
                                        <td><strong> <?=$tempPegawaiInfoNama?> </strong></td>
                                        <td rowspan="8" align="center">
                                        	<img src="../WEB/images/foto2.png" width="180">
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Jabatan Saat ini</td>
                                        <td>:</td>
                                        <td> <?=$tempPegawaiInfoJabatan?> </td>
                                    </tr>
                                    <tr>
                                    	<td>Unit Kerja Saat ini</td>
                                        <td>:</td>
                                        <td> <?=$tempPegawaiInfoSatuanKerja?> </td>
                                    </tr>
                                    <tr>
                                    	<td>Jabatan Saat Tes</td>
                                        <td>:</td>
                                        <td> <?=$tempPegawaiInfoJabatan?> </td>
                                    </tr>
                                    <tr>
                                    	<td>Unit Kerja Saat Tes</td>
                                        <td>:</td>
                                        <td> <?=$tempPegawaiInfoSatuanKerja?> </td>
                                    </tr>
                                    <tr>
                                    	<td>Nama Asesi</td>
                                        <td>:</td>
                                        <td> <?=$tempPegawaiInfoNamaAsesi?> </td>
                                    </tr>
                                    <tr>
                                    	<td>Metode</td>
                                        <td>:</td>
                                        <td> <?=$tempPegawaiInfoMetode?> </td>
                                    </tr>
                                    <tr>
                                    	<td>Tanggal Tes</td>
                                        <td>:</td>
                                        <td> <?=getFormattedDate($tempPegawaiInfoTanggalTes)?> </td>
                                    </tr>
                                    
                                </tbody>
                                </table>
                                
                                <br>
                              <div class="judul-halaman">Penilaian dan Catatan :</div>
                              	<form id="ff" method="post" novalidate>
                            	<table style="margin-bottom:60px;">
                                <thead>
                                    <tr>
                                        <th width="51%">Hasil Individu</th>
                                        <th width="49%">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php /*?><tr>
                                    <td style="vertical-align:top">
                                        <div style="margin-bottom: 5px;">Kecerdasan Umum/General Intellectual</div>
                                        <div class="rbtn">
                                        <ul>
                                            <li style="width:100%; text-align:left" id="rbtn-<?=$checkbox_index?>-1" class="sebelumselected">Menjelaskan pengertian Kerangka Acuan Kerja (KAK) dan Rencana Anggaran Biaya (RAB)</li><br/>
                                            <li style="width:100%; text-align:left" id="rbtn-<?=$checkbox_index?>-1" class="sebelumselected">Menjelaskan sistematika umum KAK</li>
                                        </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea name="reqKeterangan[]" <?=$disabled?> style="color:#000 !important"><?=$tempKeterangan?></textarea>
                                    </td>
                                </tr><?php */?>
								<?
								$indexKeterangan= 0;
								$indexTr= 1;
								$tempAtributNamaLookUp= "";
								for($checkbox_index=0;$checkbox_index < $jumlah_pegawai_nilai;$checkbox_index++)
								{
									$index_loop= $checkbox_index;
									$tempAtributJadwalPegawaiDetilId= $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ID"];
									$tempAtributId= $arrPegawaiNilai[$index_loop]["ATRIBUT_ID"];
									$tempAtributIndikatorId= $arrPegawaiNilai[$index_loop]["INDIKATOR_ID"];
									$tempAtributLevelId= $arrPegawaiNilai[$index_loop]["LEVEL_ID"];
									
									$tempAtributPegawaiIndikatorId= $arrPegawaiNilai[$index_loop]["PEGAWAI_INDIKATOR_ID"];
									$tempAtributPegawaiLevelId= $arrPegawaiNilai[$index_loop]["PEGAWAI_LEVEL_ID"];
									
									$tempAtributNama= $arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"];
									$tempAtributIndikator= $arrPegawaiNilai[$index_loop]["NAMA_INDIKATOR"];
									$tempAtributJumlahLevel= $arrPegawaiNilai[$index_loop]["JUMLAH_LEVEL"];
									
									$arrSelected= "";
									$arrSelected= radioPenilaian($tempAtributNilai, "selected");
									
									
									$cssIndikator= "sebelumselected";
									if($tempAtributJadwalPegawaiDetilId == ""){}
									else
									$cssIndikator= "selected";
									
									?>
                                    
                                    <?
									if($tempAtributNamaLookUp == $tempAtributNama)
									{
										if($arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"] == ""){}
										else
										$tempAtributPegawaiKeterangan= $arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"];
										$indexTr++;
									}
									else
									{
										$tempAtributPegawaiKeterangan= "";
										if($arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"] == ""){}
										else
										$tempAtributPegawaiKeterangan= $arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"];
                                    ?>
                                    <tr>
                                        <td style="vertical-align:top">
                                            <div style="margin-bottom: 10px;"><?=$tempAtributNama?></div>
                                            <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-<?=$checkbox_index?>-<?=$tempAtributIndikatorId?>-<?=$tempAtributLevelId?>" class=" <?=$cssIndikator?>">
                                                <input type="hidden" id="reqAtributId<?=$tempAtributIndikatorId?>" style="color:#000 !important" name="reqAtributId[]" value="<?=$tempAtributId?>" />
                                                <input type="hidden" id="reqJadwalPegawaiDetilId<?=$tempAtributIndikatorId?>" style="color:#000 !important" name="reqJadwalPegawaiDetilId[]" value="<?=$tempAtributJadwalPegawaiDetilId?>" />
                                                <input type="hidden" id="reqIndikatorId<?=$tempAtributIndikatorId?>" style="color:#000 !important" name="reqIndikatorId[]" value="<?=$tempAtributPegawaiIndikatorId?>" />
                                                <input type="hidden" id="reqLevelId<?=$tempAtributIndikatorId?>" style="color:#000 !important" name="reqLevelId[]" value="<?=$tempAtributLevelId?>" />
                                                <input name="reqJumlahKeterangan[]" type="hidden" style="color:#000 !important" value="<?=$indexKeterangan?>" />
												<?=$tempAtributIndikator?>
                                                </li>
                                    <?
									}
									?>
                                    
                                    <?
									if($tempAtributNamaLookUp == $tempAtributNama && $indexTr <= $tempAtributJumlahLevel)
									{
                                    ?>
                                                <br/><li style="width:100%; text-align:left" id="rbtn-<?=$checkbox_index?>-<?=$tempAtributIndikatorId?>-<?=$tempAtributLevelId?>" class=" <?=$cssIndikator?>">
                                                <input type="hidden" id="reqAtributId<?=$tempAtributIndikatorId?>" style="color:#000 !important" name="reqAtributId[]" value="<?=$tempAtributId?>" />
                                                <input type="hidden" id="reqJadwalPegawaiDetilId<?=$tempAtributIndikatorId?>" style="color:#000 !important" name="reqJadwalPegawaiDetilId[]" value="<?=$tempAtributJadwalPegawaiDetilId?>" />
                                                <input type="hidden" id="reqIndikatorId<?=$tempAtributIndikatorId?>" style="color:#000 !important" name="reqIndikatorId[]" value="<?=$tempAtributPegawaiIndikatorId?>" />
                                                <input type="hidden" id="reqLevelId<?=$tempAtributIndikatorId?>" style="color:#000 !important" name="reqLevelId[]" value="<?=$tempAtributLevelId?>" />
                                                <input name="reqJumlahKeterangan[]" type="hidden" style="color:#000 !important" value="<?=$indexKeterangan?>" />
												<?=$tempAtributIndikator?>
                                                </li>
                                    <?
										if($indexTr == $tempAtributJumlahLevel)
										{
									?>
                                            </ul>
                                            </div>
                                        </td>
                                        <td style="vertical-align:top; background-color:transparent; color:#000 !important">
                                        	<textarea name="reqKeterangan[]" style="color:#000 !important"><?=$tempAtributPegawaiKeterangan?></textarea>
                                        </td>
                                    </tr>
                                    <?
										$indexTr= 1;
										$indexKeterangan++;
										}
									}
								
								$tempAtributNamaLookUp= $tempAtributNama;
								}
								
								if($tempPegawaiInfoStatusPenilaian == "1"){}
								else
								{
									if($jumlah_pegawai_nilai > 0)
									{
                                ?>
                                    <tr>
                                        <td colspan="2" align="center">
                                        	<input type="hidden" name="reqJadwalTesId" value="<?=$tempPegawaiInfoJadwalTesId?>">
                                        	<input type="hidden" name="reqRowId" value="<?=$reqJadwalPegawaiId?>">
                                        	<input type="hidden" name="reqMode" value="insert">
                                            <input name="submit1" type="submit" value="Simpan" />
                                        </td>
                                    </tr>
                                <?
									}
								}
                                ?>
                                </tbody>
                                </table>
                                </form>
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

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyuiasesor.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<?php /*?><script src="../WEB/lib/easyui/jquery.min.js" type="text/javascript"></script><?php */?>
<script>
$(document).ready(function() {
	$(function(){
		<?
		if($tempPegawaiInfoStatusPenilaian == "1"){}
		else
		{
		?>
		$('.rbtn ul li').click(function(){
		// get the value from the id of the clicked li and attach it to the window object to be able to use it later.
			var choice= this.id;
			var text= $(this).text();
			var element= choice.split('-');
			
			if($('li[id^="'+choice+'"]').hasClass("selected") == true)
			{
				$('li[id^="'+choice+'"]').removeClass('selected');
				$('li[id^="'+choice+'"]').addClass('sebelumselected');
				$("#reqIndikatorId"+element[2]+", #reqLevelId"+element[2]).val("");
			}
			else
			{
				$('li[id^="'+choice+'"]').removeClass('sebelumselected');
				$('li[id^="'+choice+'"]').addClass('selected');
				$("#reqIndikatorId"+element[2]).val(element[2]);
				$("#reqLevelId"+element[2]).val(element[3]);
			}
			
		}); 
		
		$('.rbtn ul li').mouseover(function(){
			$(this).addClass('over');
		});
		
		$('.rbtn ul li').mouseout(function(){
			$(this).removeClass('over');
		});
		<?
		}
		?>
	}); //end function
}); //document ready

$(function(){
	$('#ff').form({
		url:'../json-asesor/penilaian_pegawai.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);return false;
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			//parent.setShowHideMenu(3);
			document.location.href = 'penilaian_pegawai.php?reqJadwalAsesorId=<?=$tempPegawaiInfoJadwalAsesorId?>&reqJadwalPegawaiId=<?=$reqJadwalPegawaiId?>';
		}
	});
});
</script>

<script type="text/javascript" src="../niceEdit/nicedit.js"></script>
<script type="text/javascript">
	//new nicEditor({fullPanel : true}).panelInstance('reqIsi');
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>

</body>
</html>