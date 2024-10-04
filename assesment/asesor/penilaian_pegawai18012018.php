<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");
include_once("../WEB/classes/base/AsesorPenilaianDetil.php");

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

$reqJadwalAsesorId= httpFilterGet("reqJadwalAsesorId");
$reqAspekId= httpFilterGet("reqAspekId");
$reqPenggalianId= httpFilterGet("reqPenggalianId");
$reqJadwalPegawaiId= httpFilterGet("reqJadwalPegawaiId");

$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId;
$set= new JadwalPegawai();
$set->selectByParamsJadwalPegawaiInfo(array(), -1,-1, $statement);
//echo $set->query;exit;
$set->firstRow();
$tempPegawaiInfoId= $set->getField("PEGAWAI_ID");
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


$index_loop= 0;
$arrAsesorPegawaiNilai="";
$statement= " AND A.JADWAL_ASESOR_ID = ".$reqJadwalAsesorId." AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND B.ASESOR_ID = ".$tempAsesorId." AND F.ASPEK_ID = ".$reqAspekId;
$set= new AsesorPenilaianDetil();
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	//ASESOR_PENILAIAN_DETIL_ID,JADWAL_TES_ID,TANGGAL_TES,JABATAN_TES_ID,SATKER_TES_ID,JADWAL_ASESOR_ID,ASPEK_ID,ASESOR_ATRIBUT_ID,NILAI_STANDAR
	//NILAI,GAP,ASESOR_FORMULA_ESELON_ID,ASESOR_FORMULA_ATRIBUT_ID,ASESOR_PENGGALIAN_ID,ASESOR_PEGAWAI_ID,CATATAN
	$arrAsesorPegawaiNilai[$index_loop]["ASESOR_PENILAIAN_DETIL_ID"]= $set->getField("ASESOR_PENILAIAN_DETIL_ID");
	$arrAsesorPegawaiNilai[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrAsesorPegawaiNilai[$index_loop]["TANGGAL_TES"]= dateToPageCheck($set->getField("TANGGAL_TES"));
	$arrAsesorPegawaiNilai[$index_loop]["JABATAN_TES_ID"]= $set->getField("JABATAN_TES_ID");
	$arrAsesorPegawaiNilai[$index_loop]["SATKER_TES_ID"]= $set->getField("SATKER_TES_ID");
	$arrAsesorPegawaiNilai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
	$arrAsesorPegawaiNilai[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
	$arrAsesorPegawaiNilai[$index_loop]["ASESOR_ATRIBUT_ID"]= $set->getField("ASESOR_ATRIBUT_ID");
	$arrAsesorPegawaiNilai[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
	$arrAsesorPegawaiNilai[$index_loop]["NILAI"]= $set->getField("NILAI");
	$arrAsesorPegawaiNilai[$index_loop]["GAP"]= $set->getField("GAP");
	$arrAsesorPegawaiNilai[$index_loop]["ASESOR_FORMULA_ESELON_ID"]= $set->getField("ASESOR_FORMULA_ESELON_ID");
	$arrAsesorPegawaiNilai[$index_loop]["ASESOR_FORMULA_ATRIBUT_ID"]= $set->getField("ASESOR_FORMULA_ATRIBUT_ID");
	$arrAsesorPegawaiNilai[$index_loop]["ASESOR_PENGGALIAN_ID"]= $set->getField("ASESOR_PENGGALIAN_ID");
	$arrAsesorPegawaiNilai[$index_loop]["ASESOR_PEGAWAI_ID"]= $set->getField("ASESOR_PEGAWAI_ID");
	$arrAsesorPegawaiNilai[$index_loop]["CATATAN"]= $set->getField("CATATAN");
	$index_loop++;
}
$jumlah_asesor_pegawai_nilai= $index_loop;
//print_r($arrAsesorPegawaiNilai);exit;
//tempPegawaiInfoId

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
                                        <th width="100%" colspan="2">Hasil Individu</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?
								$indexKeterangan= 0;
								$indexTr= 1;
								$tempAtributNamaLookUp= "";
								for($checkbox_index=0;$checkbox_index < $jumlah_pegawai_nilai;$checkbox_index++)
								{
									$index_loop= $checkbox_index;
									$tempAtributId= $arrPegawaiNilai[$index_loop]["ATRIBUT_ID"];
									$tempAtributNama= $arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"];
									$tempAtributIndikator= $arrPegawaiNilai[$index_loop]["NAMA_INDIKATOR"];
									$tempAtributJumlahLevel= $arrPegawaiNilai[$index_loop]["JUMLAH_LEVEL"];
									
									$tempAsesorPenilaianDetilId= $tempJadwalTesId= $tempTanggalTes= $tempJabatanTesId= $tempSatkerTesId= $tempJadwalAsesorId= $tempAspekId= $tempAsesorAtributId= $tempNilaiStandar= $tempNilai= $tempGap= $tempAsesorFormulaEselonId= $tempAsesorFormulaAtributId= $tempAsesorPenggalianId= $tempCatatan= "";
									$arrayKey= in_array_column($tempAtributId, "ASESOR_ATRIBUT_ID", $arrAsesorPegawaiNilai);
									//print_r($arrayKey);exit;
									if($arrayKey == ''){}
									else
									{
										$index_data= $arrayKey[0];
										$tempAsesorPenilaianDetilId= $arrAsesorPegawaiNilai[$index_data]["ASESOR_PENILAIAN_DETIL_ID"];
										$tempJadwalTesId= $arrAsesorPegawaiNilai[$index_data]["JADWAL_TES_ID"];
										$tempTanggalTes= $arrAsesorPegawaiNilai[$index_data]["TANGGAL_TES"];
										$tempJabatanTesId= $arrAsesorPegawaiNilai[$index_data]["JABATAN_TES_ID"];
										$tempSatkerTesId= $arrAsesorPegawaiNilai[$index_data]["SATKER_TES_ID"];
										$tempJadwalAsesorId= $arrAsesorPegawaiNilai[$index_data]["JADWAL_ASESOR_ID"];
										$tempAspekId= $arrAsesorPegawaiNilai[$index_data]["ASPEK_ID"];
										$tempAsesorAtributId= $arrAsesorPegawaiNilai[$index_data]["ASESOR_ATRIBUT_ID"];
										$tempNilaiStandar= $arrAsesorPegawaiNilai[$index_data]["NILAI_STANDAR"];
										$tempNilai= $arrAsesorPegawaiNilai[$index_data]["NILAI"];
										$tempGap= $arrAsesorPegawaiNilai[$index_data]["GAP"];
										$tempAsesorFormulaEselonId= $arrAsesorPegawaiNilai[$index_data]["ASESOR_FORMULA_ESELON_ID"];
										$tempAsesorFormulaAtributId= $arrAsesorPegawaiNilai[$index_data]["ASESOR_FORMULA_ATRIBUT_ID"];
										$tempAsesorPenggalianId= $arrAsesorPegawaiNilai[$index_data]["ASESOR_PENGGALIAN_ID"];
										$tempAsesorPegawaiId= $arrAsesorPegawaiNilai[$index_data]["ASESOR_PEGAWAI_ID"];
										$tempCatatan= $arrAsesorPegawaiNilai[$index_data]["CATATAN"];
									}
									
									$arrChecked= radioPenilaian($tempNilai);
									
									//
									if($reqAspekId == "2"){}
									else
									{
									$indexTr= $tempAtributJumlahLevel= 1;
									}
									
									$arrSelected= "";
									$arrSelected= radioPenilaian($tempAtributNilai, "selected");
									$arrSelected= "";
									
									$cssIndikator= "sebelumselected";
									if($tempAtributJadwalPegawaiDetilId == ""){}
									else
									$cssIndikator= "selected";
									
									$cssIndikator= "sebelumselected";
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
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;"><?=$tempAtributNama?>
                                            <?php /*?><?=$indexTr." == ".$tempAtributJumlahLevel?><?php */?>
                                            <?
											if($reqAspekId == "1")
											{
                                            ?>
                                            <textarea name="reqCatatan[]" style="color:#000 !important"><?=$tempCatatan?></textarea>
                                            <?
											}
                                            ?>
                                            </div>
                                            
                                            <?
											if($reqAspekId == "2")
											{
                                            ?>
                                            <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-<?=$checkbox_index?>-<?=$tempAtributIndikatorId?>-<?=$tempAtributLevelId?>" class=" <?=$cssIndikator?>">
												<?=$tempAtributIndikator?>
                                                </li>
                                    <?
											}
									}
									?>
                                    
									<?
									if($tempAtributNamaLookUp == $tempAtributNama && $indexTr <= $tempAtributJumlahLevel)
									{
                                    ?>
                                                <br/><li style="width:100%; text-align:left" id="rbtn-<?=$checkbox_index?>-<?=$tempAtributIndikatorId?>-<?=$tempAtributLevelId?>" class=" <?=$cssIndikator?>">
												<?=$tempAtributIndikator?>
                                                </li>
                                    <?
									//=============
									}
										if($indexTr == $tempAtributJumlahLevel)
										{
									?>
                                            </ul>
                                            </div>
                                        </td>
                                        <td style="vertical-align:top; background-color:transparent; color:#000 !important">
                                        	<table style="width:100%; border:none">
                                            <tr>
                                                <td style="text-align:center">1</td>
                                                <td style="text-align:center">2</td>
                                                <td style="text-align:center">3</td>
                                                <td style="text-align:center">4</td>
                                                <td style="text-align:center">5</td>
                                            </tr>
                                            <tr>
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId<?=$checkbox_index?>" value="<?=$tempAsesorPenilaianDetilId?>" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId<?=$checkbox_index?>" value="<?=$tempJadwalTesId?>" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes<?=$checkbox_index?>" value="<?=$tempTanggalTes?>" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId<?=$checkbox_index?>" value="<?=$tempJabatanTesId?>" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId<?=$checkbox_index?>" value="<?=$tempSatkerTesId?>" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId<?=$checkbox_index?>" value="<?=$tempJadwalAsesorId?>" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId<?=$checkbox_index?>" value="<?=$tempAspekId?>" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId<?=$checkbox_index?>" value="<?=$tempAsesorAtributId?>" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar<?=$checkbox_index?>" value="<?=$tempNilaiStandar?>" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai<?=$checkbox_index?>" value="<?=$tempNilai?>" />
                                                <input type="hidden" name="reqGap[]" id="reqGap<?=$checkbox_index?>" value="<?=$tempGap?>" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId<?=$checkbox_index?>" value="<?=$tempAsesorFormulaEselonId?>" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId<?=$checkbox_index?>" value="<?=$tempAsesorFormulaAtributId?>" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId<?=$checkbox_index?>" value="<?=$tempAsesorPenggalianId?>" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId<?=$checkbox_index?>" value="<?=$tempAsesorPegawaiId?>" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[0]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio<?=$checkbox_index?>]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[1]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[2]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[3]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[4]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="5" /></td>
                                            </tr>
                                            <?
											if($reqAspekId == "2")
											{
                                            ?>
                                            <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><?=$tempCatatan?></textarea>
                                                </td>
                                            </tr>
                                            <?
											}
                                            ?>
                                            </table>
                                        </td>
                                    </tr>
                                    <?
										$indexTr= 1;
										$indexKeterangan++;
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
		<?php /*?>$('.rbtn ul li').click(function(){
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
		});<?php */?>
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
			document.location.href = 'penilaian_pegawai.php?reqAspekId=<?=$reqAspekId?>&reqJadwalAsesorId=<?=$tempPegawaiInfoJadwalAsesorId?>&reqJadwalPegawaiId=<?=$reqJadwalPegawaiId?>';
		}
	});
	
	$('input[id^="reqRadio"]').change(function(e) {
		var tempId= $(this).attr('id');
		var tempValId= $(this).val();
		tempId= tempId.split('reqRadio');
		tempId= tempId[1];
		
		$("#reqNilai"+tempId).val(tempValId);
		var gap= parseInt(tempValId) - parseInt($("#reqNilaiStandar"+tempId).val());
		$("#reqGap"+tempId).val(gap);
		$("#reqGapInfo"+tempId).text(gap);
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