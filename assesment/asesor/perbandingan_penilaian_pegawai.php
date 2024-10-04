<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");
include_once("../WEB/classes/base/JadwalPegawaiDetilKomentar.php");

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
$tempPegawaiInfoId= $set->getField("PEGAWAI_ID");
unset($set);

$index_loop= 0;
$arrDataAtribut="";
$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$set= new JadwalPegawai();
$set->selectByParamsAsesorPenilaianAtribut(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrDataAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$index_loop++;
}
$jumlah_pegawai_atribut= $index_loop;

$index_loop= 0;
$arrDataAsesor="";
//$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId." AND B.ASESOR_ID NOT IN (".$tempAsesorId.")";
$statement= "  AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$statement= $statement." AND F.atribut_id in 
(
SELECT F.ATRIBUT_ID
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID  
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_pegawai A ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				WHERE 1=1
				 AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId." 
				 GROUP BY F.ATRIBUT_ID, F.NAMA
)";
$set= new JadwalPegawai();
$set->selectByParamsAsesorJumlah(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAsesor[$index_loop]["ASESOR_ID"]= $set->getField("ASESOR_ID");
	$arrDataAsesor[$index_loop]["NAMA_ASESOR"]= $set->getField("NAMA_ASESOR");
	$index_loop++;
}
$jumlah_data_asesor= $index_loop;

$index_loop= 0;
$arrDataAsesorPenilaian="";
$statement= "   AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$statement= $statement." AND A.atribut_id in 
(
SELECT F.ATRIBUT_ID
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID  
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_pegawai A ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				WHERE 1=1
				 AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId." 
				 GROUP BY F.ATRIBUT_ID, F.NAMA
)";
$set= new JadwalPegawai();
$set->selectByParamsAsesorPenilaianDetil(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAsesorPenilaian[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("LEVEL_ID")."-".$set->getField("INDIKATOR_ID");
	$index_loop++;
}

$index_loop= 0;
$arrDataAsesorNilai="";
$statement= "   AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
 $statement= $statement." AND F.atribut_id in 
(
SELECT F.ATRIBUT_ID
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID  
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_pegawai A ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				WHERE 1=1
				 AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId." 
				 GROUP BY F.ATRIBUT_ID, F.NAMA
)";
$set= new JadwalPegawai();
$set->selectByParamsAsesorNilai(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAsesorNilai[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("PEGAWAI_ID");
	$arrDataAsesorNilai[$index_loop]["NILAI_PEMBULATAN"]= $set->getField("NILAI_PEMBULATAN");
	$index_loop++;
}

$index_loop= 0;
$arrDataPegawaiKomentar="";
$statement= "   AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$set= new JadwalPegawaiDetilKomentar();
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataPegawaiKomentar[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("LEVEL_ID")."-".$set->getField("INDIKATOR_ID")."-".$set->getField("PEGAWAI_ID")."-".$set->getField("JADWAL_PEGAWAI_ID")."-".$set->getField("JADWAL_TES_ID");
	$arrDataPegawaiKomentar[$index_loop]["KETERANGAN"]= str_replace("\n","<br/>",$set->getField("KETERANGAN"));
	$index_loop++;
}

$index_loop= 0;
$arrDataPegawaiKomentarLain="";
$statement= "  AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND A.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
 
$set= new JadwalPegawaiDetilKomentar();
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataPegawaiKomentarLain[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("PEGAWAI_ID")."-".$set->getField("JADWAL_TES_ID");
	$arrDataPegawaiKomentarLain[$index_loop]["KETERANGAN"]= str_replace("\n","<br/>",$set->getField("KETERANGAN"));
	$arrDataPegawaiKomentarLain[$index_loop]["ASESOR_KOMENTAR_NAMA"]= $set->getField("ASESOR_KOMENTAR_NAMA");
	$index_loop++;
}

$index_loop= 0;
$arrPegawaiNilai="";
//$statement= " AND F.ATRIBUT_ID = '0101' AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND B.ASESOR_ID = ".$tempAsesorId;
$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND B.ASESOR_ID = ".$tempAsesorId;
$set= new JadwalPegawaiDetil();
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ID"]= $set->getField("JADWAL_PEGAWAI_DETIL_ID");
	$arrPegawaiNilai[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("LEVEL_ID")."-".$set->getField("INDIKATOR_ID");
	$arrPegawaiNilai[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrPegawaiNilai[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
	$arrPegawaiNilai[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_INDIKATOR_ID"]= $set->getField("PEGAWAI_INDIKATOR_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_LEVEL_ID"]= $set->getField("PEGAWAI_LEVEL_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"]= $set->getField("PEGAWAI_KETERANGAN");
	$arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrPegawaiNilai[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
	$arrPegawaiNilai[$index_loop]["JUMLAH_LEVEL"]= $set->getField("JUMLAH_LEVEL");
	$arrPegawaiNilai[$index_loop]["NAMA_ASESOR"]= $set->getField("NAMA_ASESOR");
	$index_loop++;
}
$jumlah_pegawai_nilai= $index_loop;
//print_r($arrPegawaiNilai);exit;
if($jumlah_pegawai_nilai > 0)
{
	$tempPegawaiNamaAsesor= $arrPegawaiNilai[0]["NAMA_ASESOR"];
}

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
<link href="../WEB/lib/bootstrap/equal-height-columns.css" rel="stylesheet">
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
	
	.komentarindikator:before{
		cursor:pointer;
		font-family:"FontAwesome";
		content:"\f096";
		margin-left:50%;
		margin-right:50%;
		color:#f8a406;
		font-size:18px;
	}
	
	.komentarindikatorselected:before{
		cursor:pointer;
		font-family:"FontAwesome";
		content:"\f046";
		margin-left:50%;
		margin-right:50%;
		color:#f8a406;
		font-size:18px;
	}
	
	.infoindikator:before{
		font-family:"FontAwesome";
		content:"\f096";
		margin-left:50%;
		margin-right:50%;
		color:#f8a406;
		font-size:18px;
	}
	
	.infoindikatorselected:before{
		font-family:"FontAwesome";
		content:"\f046";
		margin-left:50%;
		margin-right:50%;
		color:#f8a406;
		font-size:18px;
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
	
	.headassesorAwal{background-color:#f8a406; margin-left:5px; color:#000; font-weight:bold; text-align:center}
	.headassesor{background-color:#f8a406; *margin-left:5px; color:#000; font-weight:bold; text-align:center}
	.headassesorAkhir{background-color:#f8a406; margin-right:5px; color:#000; font-weight:bold; text-align:center}
	.bodyassesorAwal{*background-color:#f8a406; margin-left:5px; color:#000; *font-weight:bold; *text-align:center}
	.bodyassesorAkhir{*background-color:#f8a406; margin-right:5px; color:#000; font-weight:bold; text-align:center}
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
                                    <tr style="display:none">
                                    	<td>Nama Asesi</td>
                                        <td>:</td>
                                        <td> <?=$tempPegawaiInfoNamaAsesi?> </td>
                                    </tr>
                                    <tr style="display:none">
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
                            	<table style="margin-bottom:60px;">
                                <thead>
                                <tr>
                                	<th>Assement Meeting
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="vertical-align:top">
                                        <div class="rbtn">
                                        	<?
											for($index_atribut=0;$index_atribut < $jumlah_pegawai_atribut;$index_atribut++)
											{
												$tempAtributId= $arrDataAtribut[$index_atribut]["ATRIBUT_ID"];
												$tempAtributNama= $arrDataAtribut[$index_atribut]["ATRIBUT_NAMA"];
											?>
                                            <!--buat judul-->
                                        	<div class="judul-halaman"><?=$tempAtributNama?></div>
                                            <div class="row row-eq-height" style="font-size:12px !important">
                                            	<div class="col-xs-4 headassesorAwal" style="width:70% !important">Asesor</div>
                                                <?
                                                $arrayKey= '';
                                                $arrayKey= in_array_column($tempAtributId, "ATRIBUT_ID", $arrPegawaiNilai);
                                                //print_r($arrayKey);exit;
                                                if($arrayKey == ''){}
                                                else
                                                {
													for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
													{
														$nomor_indikator= $index_detil+1;
													$index_row= $arrayKey[$index_detil];
													$tempNamaIndikator= $arrPegawaiNilai[$index_row]["NAMA_INDIKATOR"];
												?>
                                                <div class="col-xs-4 headassesor">
                                                <a href="#" title="<?=$tempNamaIndikator?>" rel="tooltip" style="white-space: nowrap; color:#000 !important;">Indikator <?=$nomor_indikator?></a>
                                                </div>
                                                <?
													}
												}
                                                ?>
                                                <div class="col-xs-4 headassesor" style="width:20% !important">Nilai</div>
                                                <div class="col-xs-4 headassesorAkhir">Komentar</div>
                                            </div>
                                            <!--buat isi berdasarkan asesor-->
											<?
                                            for($index_asesor=0;$index_asesor < $jumlah_data_asesor;$index_asesor++)
                                            {
                                                $tempAsesorPenilaianId= $arrDataAsesor[$index_asesor]["ASESOR_ID"];
                                                $tempNamaAsesorPenilaian= $arrDataAsesor[$index_asesor]["NAMA_ASESOR"];
												
												$tempNilaiId= $tempAsesorPenilaianId."-".$tempAtributId."-".$tempPegawaiInfoId;
                                                $arrayKey= '';
                                                $arrayKey= in_array_column($tempNilaiId, "ID", $arrDataAsesorNilai);
                                                //print_r($arrayKey);exit;
                                                if($arrayKey == '')
												{
													continue;
												}
												
                                            ?>
                                            <div class="row row-eq-height">
                                                <div class="col-xs-4 bodyassesorAwal" id="reqSpanKomentar<?=$tempAsesorPenilaianId."-".$tempAtributId."-".$tempPegawaiInfoId."-".$tempPegawaiInfoJadwalTesId?>" style="width:70% !important">
                                                <?
                                                //ASESOR_ID;ATRIBUT_ID;PEGAWAI_ID;JADWAL_TES_ID
												$tempRowIdAsesorLain= $tempAsesorPenilaianId."-".$tempAtributId."-".$tempPegawaiInfoId."-".$tempPegawaiInfoJadwalTesId;
												$tempIdKeteranganLain= $tempKeteranganLainAsesor= $tempAsesorKomentarNama= "";
												$arrayKey= '';
                                                $arrayKey= in_array_column($tempRowIdAsesorLain, "ID", $arrDataPegawaiKomentarLain);
                                                //print_r($arrayKey);exit;
                                                if($arrayKey == ''){}
                                                else
                                                {
                                                    for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                                                    {
														$index_row= $arrayKey[$index_detil];
														$tempIdKeteranganLain= $arrDataPegawaiKomentarLain[$index_row]["ID"];
														$tempKeteranganLain= $arrDataPegawaiKomentarLain[$index_row]["KETERANGAN"];
														$tempAsesorKomentarNamaVal= $arrDataPegawaiKomentarLain[$index_row]["ASESOR_KOMENTAR_NAMA"];
														
														$image_komentar= "icon_centang";
														$tempNoneLain= "none";
														if($tempIdKeteranganLain == "")
														{
															//continue;
														}
														else
														{
															$tempNoneLain= "";
															if($tempKeteranganLain == "")
															{
																$tempKeteranganLain= $tempAsesorKomentarNamaVal.": Setuju";
															}
															else
															{
																$tempKeteranganLain= $tempAsesorKomentarNamaVal.": ".$tempKeteranganLain;
																$image_komentar= "icon_cross";
															}
														}
														
                                                ?>
                                                <span title="<?=$tempKeteranganLain?>" rel="tooltip" data-html="true" style="white-space: nowrap; color:#000 !important;"><img src="../WEB/images/<?=$image_komentar?>.png" width="15" height="15"></span>
                                                <?
													}
												}
												
												if($tempKeteranganLain == ""){}
												else
												echo "<br/>";
                                                ?>
                                                <?php /*?><?=$tempAsesorPenilaianId."-".$tempAtributId."-".$tempPegawaiInfoId."-".$tempPegawaiInfoJadwalTesId?><?php */?>
												<?=$tempNamaAsesorPenilaian?>
                                                </div>
                                                <?
                                                $arrayKey= '';
                                                $arrayKey= in_array_column($tempAtributId, "ATRIBUT_ID", $arrPegawaiNilai);
                                                //print_r($arrayKey);exit;
                                                if($arrayKey == ''){}
                                                else
                                                {
                                                    for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                                                    {
                                                    $index_row= $arrayKey[$index_detil];
                                                    $tempNamaIndikator= $arrPegawaiNilai[$index_row]["NAMA_INDIKATOR"];
													$tempIdPenilaian= $tempAsesorPenilaianId."-".$arrPegawaiNilai[$index_row]["ATRIBUT_ID"]."-".$arrPegawaiNilai[$index_row]["LEVEL_ID"]."-".$arrPegawaiNilai[$index_row]["INDIKATOR_ID"];
													
													$arrayPenilaianKey= '';
													$arrayPenilaianKey= in_array_column($tempIdPenilaian, "ID", $arrDataAsesorPenilaian);
													//print_r($arrayPenilaianKey);exit;
													
													$tempCssPenilaian= "infoindikator";
													if($arrayPenilaianKey == ''){}
													else
													{
													$tempCssPenilaian= "infoindikatorselected";
													}
												?>
                                                <div class="col-xs-4 <?=$tempCssPenilaian?>"></div>
                                                <?
                                                    }
                                                }
                                                ?>
                                                
                                                <?
												
												//ASESOR_ID;ATRIBUT_ID;LEVEL_ID;INDIKATOR_ID;PEGAWAI_ID;JADWAL_PEGAWAI_ID;JADWAL_TES_ID
												$tempNilaiPembulatan= "";
												$tempRowIdAsesor= $tempIdPenilaian."-".$tempPegawaiInfoId."-".$reqJadwalPegawaiId."-".$tempPegawaiInfoJadwalTesId;
												$tempNilaiId= $tempAsesorPenilaianId."-".$tempAtributId."-".$tempPegawaiInfoId;
                                                $arrayKey= '';
                                                $arrayKey= in_array_column($tempNilaiId, "ID", $arrDataAsesorNilai);
                                                //print_r($arrayKey);exit;
                                                if($arrayKey == ''){}
                                                else
                                                {
                                                    for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                                                    {
                                                    $index_row= $arrayKey[$index_detil];
                                                    $tempNilaiPembulatan= $arrDataAsesorNilai[$index_row]["NILAI_PEMBULATAN"];
													}
												}
												
												$tempIdKeterangan= $tempKeterangan= "";
												$arrayKey= '';
                                                $arrayKey= in_array_column($tempRowIdAsesor, "ID", $arrDataPegawaiKomentar);
                                                //print_r($arrayKey);exit;
                                                if($arrayKey == ''){}
                                                else
                                                {
                                                    for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                                                    {
                                                    $index_row= $arrayKey[$index_detil];
													$tempIdKeterangan= $arrDataPegawaiKomentar[$index_row]["ID"];
                                                    $tempKeterangan= $arrDataPegawaiKomentar[$index_row]["KETERANGAN"];
													}
												}
												
												$cssKomentar= "komentarindikator";
												$tempNone= "none";
												if($tempIdKeterangan == ""){}
												else
												{
													$cssKomentar= "komentarindikatorselected";
													if($tempKeterangan == ""){}
													else
													{
													$tempNone= "";
													$cssKomentar= "komentarindikator";
													}
												}
                                                ?>
                                                <div class="col-xs-4" style="text-align:center; width:20% !important"><?=$tempNilaiPembulatan?></div>
                                                <?
												if($tempAsesorPenilaianId == $tempAsesorId || $tempNilaiPembulatan == 0)
												{
												?>
                                                <div class="col-xs-4" style="text-align:left; margin-right:5px !important"></div>
                                                <?
												}
												else
												{
                                                ?>
                                                <div class="col-xs-4 <?=$cssKomentar?>" id="reqKomentar<?=$tempRowIdAsesor?>" style="text-align:left; margin-right:5px !important">
                                                	<p title="<?=$tempKeterangan?>" rel="tooltip" data-html="true" id="reqDetilKomentar<?=$tempRowIdAsesor?>" style="display:<?=$tempNone?>; white-space: nowrap; color:#000 !important; margin-top:-44px; margin-left:50px"><img src="../WEB/images/komen.png" width="15" height="15"></p>
                                                    <?php /*?><?=$tempIdKeterangan?><?=$tempRowIdAsesor?><?php */?>
                                                	<?php /*?><textarea name="reqKeterangan[]" style="color:#000 !important"><?=$tempAtributPegawaiKeterangan?></textarea><?php */?>
												</div>
                                                <?php /*?>$tempAsesorId<?php */?>
                                                <?
												}
                                                ?>
                                            </div>
                                            <?
                                            }
                                            ?>
                                            
                                            <?
											}
                                            ?>
                                            <?php /*?><div class="row">
                                                <div class="col-md-12">Hasil Assesment Meeting: 4</div>
                                            </div><?php */?>
                                        </div>
                                    </td>
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
	 © 2020 Pemprov Bali. All Rights Reserved. 
</footer>

<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 

<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<script type="text/javascript">
function iecompattest(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}
function OpenDHTMLCenter(opAddress, opCaption, opWidth, opHeight)
{
	opCaption= "Komentar";
	var width  = opWidth;
	var height = opHeight;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var top = iecompattest().scrollTop + 44;
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
</script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyuiasesor.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script>
//function setKomentar(id, msg)
function setKomentar(id)
{
	/*$("#reqDetilKomentar"+id).show();
	$("#reqDetilKomentar"+id).attr('title',msg);
	$.messager.alert('Info', "Komentar berhasil simpan", 'info');
	divwin.close();
	
	//$('[data-toggle="myToolTip"]').tooltip();
	return false;*/
	
	//ASESOR_ID;ATRIBUT_ID;LEVEL_ID;INDIKATOR_ID;PEGAWAI_ID;JADWAL_PEGAWAI_ID
	//11-0101-3-15-182-4
	//11-0101-3-15-182-4
	//alert(id+", "+val);
	//alert(val);
	var i=0;
	var element= id.split('-');
	var reqAsesorId= reqAtributId= reqLevelId= reqIndikatorId= reqPegawaiId= reqJadwalPegawaiId= "";
	reqAsesorId= element[i];i++;
	reqAtributId= element[i];i++;
	reqLevelId= element[i];i++;
	reqIndikatorId= element[i];i++;
	reqPegawaiId= element[i];i++;
	reqJadwalPegawaiId= element[i];
	var s_url= "../json-asesor/perbandingan_penilaian_pegawai_penilaian.php?reqMode=data&reqAsesorId="+reqAsesorId+"&reqAtributId="+reqAtributId+"&reqLevelId="+reqLevelId+"&reqIndikatorId="+reqIndikatorId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalPegawaiId="+reqJadwalPegawaiId+"&reqJadwalTesId="+reqJadwalTesId;
	//alert(s_url);return false;
	
	/*var request = $.get(s_url);
	request.done(function(msg)
	{
		if(msg == '' || msg == '1'){}
		else
		{
			$("#reqDetilKomentar"+id).show();
			$("#reqDetilKomentar"+id).attr('title',msg);
			//alert(msg);
			$.messager.alert('Info', "Komentar berhasil simpan", 'info');
			divwin.close();
			$('[data-toggle="myToolTip"]').tooltip();
		}
	});*/
	
	$.ajax({'url': s_url,'success': function(msg) {
		if(msg == '' || msg == '1'){}
		else
		{
			$("#reqDetilKomentar"+id).show();
			$("#reqDetilKomentar"+id).attr('title',msg);
			//alert(msg);
			$.messager.alert('Info', "Komentar berhasil simpan", 'info');
			divwin.close();
			
			//$('[data-toggle="myToolTip"]').tooltip();
			//$('#'+target).html(msg);
		}
	}});
}

$(function(){
	/*var textArray= $('#reqSpanKomentar5-0101-182-13').find('span').map(function(){
        return $(this).html();
    }).get();*/
	
	//var temp= textArray.replace(",", " ");
	//$("#reqSpanKomentar5-0101-182-13").html(temp+"<span title='asem' rel='tooltip' data-html='true' style='white-space: nowrap; color:#000 !important;'><img src='../WEB/images/icon_check.png' width='15' height='15'></span>");
	
	$('[data-toggle="myToolTip"]').tooltip();
	
	$('div[id^="reqKomentar"]').click(function(){
		var choice= this.id;
		var id= choice.replace("reqKomentar", "");
		var element= id.split('-');
		//alert(element[0]);
		
		var i=0;
		//ASESOR_ID;ATRIBUT_ID;LEVEL_ID;INDIKATOR_ID;PEGAWAI_ID;JADWAL_PEGAWAI_ID
		var reqAsesorId= reqAtributId= reqLevelId= reqIndikatorId= reqPegawaiId= reqJadwalPegawaiId= reqJadwalTesId= "";
		reqAsesorId= element[i];i++;
		reqAtributId= element[i];i++;
		reqLevelId= element[i];i++;
		reqIndikatorId= element[i];i++;
		reqPegawaiId= element[i];i++;
		reqJadwalPegawaiId= element[i];i++;
		reqJadwalTesId= element[i];
		
		//opUrl= "perbandingan_penilaian_pegawai_penilaian.php?reqAsesorId="+reqAsesorId+"&reqAtributId="+reqAtributId+"&reqLevelId="+reqLevelId+"&reqIndikatorId="+reqIndikatorId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalPegawaiId="+reqJadwalPegawaiId+"&reqJadwalTesId="+reqJadwalTesId;
		//OpenDHTMLCenter(opUrl, "Komentar", '548', '225');
		//return false;
		
		$.messager.defaults.ok = 'Ya';
		$.messager.defaults.cancel = 'Tidak';
		if($('div[id^="'+choice+'"]').hasClass("komentarindikatorselected") == true)
		{
			$.messager.confirm('Konfirmasi', "Apakah anda tidak setuju?",function(r){
				if (r){
					var jqxhr = $.get( "../json-asesor/perbandingan_penilaian_pegawai_penilaian.php?reqMode=insert&reqAsesorId="+reqAsesorId+"&reqAtributId="+reqAtributId+"&reqLevelId="+reqLevelId+"&reqIndikatorId="+reqIndikatorId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalPegawaiId="+reqJadwalPegawaiId+"&reqJadwalTesId="+reqJadwalTesId, function() {
						arrChecked = [];
						$.messager.progress('close'); 
					})
					.done(function(info) {
						if(info == "" || info == "1")
						{
						
						}
						else
						alert(info);
						
					})
					.fail(function() {
						arrChecked = [];
						alert( "error" );
					});
					
					$('div[id^="'+choice+'"]').removeClass('komentarindikatorselected');
					$('div[id^="'+choice+'"]').addClass('komentarindikator');
					
					opUrl= "perbandingan_penilaian_pegawai_penilaian.php?reqAsesorId="+reqAsesorId+"&reqAtributId="+reqAtributId+"&reqLevelId="+reqLevelId+"&reqIndikatorId="+reqIndikatorId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalPegawaiId="+reqJadwalPegawaiId+"&reqJadwalTesId="+reqJadwalTesId;
					OpenDHTMLCenter(opUrl, "Komentar", '548', '225');
				}
				else
				{
					//$('div[id^="'+choice+'"]').removeClass('komentarindikatorselected');
					//$('div[id^="'+choice+'"]').addClass('komentarindikator');
					//$("#reqDetilKomentar"+id).attr('title','s');
				}
			});
			//alert('a');
		}
		else
		{
			$.messager.confirm('Konfirmasi', "Apakah anda setuju?",function(r){
				if (r){
					try {
						var jqxhr = $.get( "../json-asesor/perbandingan_penilaian_pegawai_penilaian.php?reqStatus=1&reqMode=insert&reqAsesorId="+reqAsesorId+"&reqAtributId="+reqAtributId+"&reqLevelId="+reqLevelId+"&reqIndikatorId="+reqIndikatorId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalPegawaiId="+reqJadwalPegawaiId+"&reqJadwalTesId="+reqJadwalTesId, function() {
							arrChecked = [];
							$.messager.progress('close'); 
						})
						.done(function(info) {
							if(info == "" || info == "1")
							{
							
							}
							else
							alert(info);
							
						})
						.fail(function() {
							arrChecked = [];
							alert( "error" );
						});
					}catch(e) {
						alert(e);
					}
					
					$("#reqDetilKomentar"+id).hide();
					$('div[id^="'+choice+'"]').removeClass('komentarindikator');
					$('div[id^="'+choice+'"]').addClass('komentarindikatorselected');
					//$("#reqDetilKomentar"+id).attr('title','');
				}
				else
				{
					opUrl= "perbandingan_penilaian_pegawai_penilaian.php?reqAsesorId="+reqAsesorId+"&reqAtributId="+reqAtributId+"&reqLevelId="+reqLevelId+"&reqIndikatorId="+reqIndikatorId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalPegawaiId="+reqJadwalPegawaiId+"&reqJadwalTesId="+reqJadwalTesId;
					OpenDHTMLCenter(opUrl, "Komentar", '548', '225');
				}
			});
			//alert('b');
		}
		
	});
	
	
	$('#ff').form({
		url:'../json-asesor/perbandingan_penilaian_pegawai.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);return false;
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			//parent.setShowHideMenu(3);
			document.location.href = 'perbandingan_penilaian_pegawai.php?reqJadwalAsesorId=<?=$tempPegawaiInfoJadwalAsesorId?>&reqJadwalPegawaiId=<?=$reqJadwalPegawaiId?>';
		}
	});
});
</script>

<script type="text/javascript" src="../niceEdit/nicedit.js"></script>
<script type="text/javascript">
	//new nicEditor({fullPanel : true}).panelInstance('reqIsi');
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>

<style>
/*
	TOOLTIP
*/

#tooltip
{
	font-family: Ubuntu, sans-serif;
	font-size: 0.875em;
	text-align: center;
	text-shadow: 0 1px rgba( 0, 0, 0, .5 );
	line-height: 1.5;
	color: #fff;
	background: #333;
	background: -webkit-gradient( linear, left top, left bottom, from( rgba( 0, 0, 0, .6 ) ), to( rgba( 0, 0, 0, .8 ) ) );
	background: -webkit-linear-gradient( top, rgba( 0, 0, 0, .6 ), rgba( 0, 0, 0, .8 ) );
	background: -moz-linear-gradient( top, rgba( 0, 0, 0, .6 ), rgba( 0, 0, 0, .8 ) );
	background: -ms-radial-gradient( top, rgba( 0, 0, 0, .6 ), rgba( 0, 0, 0, .8 ) );
	background: -o-linear-gradient( top, rgba( 0, 0, 0, .6 ), rgba( 0, 0, 0, .8 ) );
	background: linear-gradient( top, rgba( 0, 0, 0, .6 ), rgba( 0, 0, 0, .8 ) );
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	border-top: 1px solid #fff;
	-webkit-box-shadow: 0 3px 5px rgba( 0, 0, 0, .3 );
	-moz-box-shadow: 0 3px 5px rgba( 0, 0, 0, .3 );
	box-shadow: 0 3px 5px rgba( 0, 0, 0, .3 );
	position: absolute;
	z-index: 100;
	padding: 15px;
}
	#tooltip:after
	{
		width: 0;
		height: 0;
		border-left: 10px solid transparent;
		border-right: 10px solid transparent;
		border-top: 10px solid #333;
		border-top-color: rgba( 0, 0, 0, .7 );
		content: '';
		position: absolute;
		left: 50%;
		bottom: -10px;
		margin-left: -10px;
	}
		#tooltip.top:after
		{
			border-top-color: transparent;
			border-bottom: 10px solid #333;
			border-bottom-color: rgba( 0, 0, 0, .6 );
			top: -20px;
			bottom: auto;
		}
		#tooltip.left:after
		{
			left: 10px;
			margin: 0;
		}
		#tooltip.right:after
		{
			right: 10px;
			left: auto;
			margin: 0;
		}
</style>

<script>

	/*
		TOOLTIP
	*/

	$( function()
	{
		var targets = $( '[rel~=tooltip]' ),
			target	= false,
			tooltip = false,
			title	= false;

		targets.bind( 'mouseenter', function()
		{
			target	= $( this );
			tip		= target.attr( 'title' );
			tooltip	= $( '<div id="tooltip"></div>' );

			if( !tip || tip == '' )
				return false;

			target.removeAttr( 'title' );
			tooltip.css( 'opacity', 0 )
				   .html( tip )
				   .appendTo( 'body' );

			var init_tooltip = function()
			{
				if( $( window ).width() < tooltip.outerWidth() * 1.5 )
					tooltip.css( 'max-width', $( window ).width() / 2 );
				else
					tooltip.css( 'max-width', 340 );

				var pos_left = target.offset().left + ( target.outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
					pos_top	 = target.offset().top - tooltip.outerHeight() - 20;

				if( pos_left < 0 )
				{
					pos_left = target.offset().left + target.outerWidth() / 2 - 20;
					tooltip.addClass( 'left' );
				}
				else
					tooltip.removeClass( 'left' );

				if( pos_left + tooltip.outerWidth() > $( window ).width() )
				{
					pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
					tooltip.addClass( 'right' );
				}
				else
					tooltip.removeClass( 'right' );

				if( pos_top < 0 )
				{
					var pos_top	 = target.offset().top + target.outerHeight();
					tooltip.addClass( 'top' );
				}
				else
					tooltip.removeClass( 'top' );

				tooltip.css( { left: pos_left, top: pos_top } )
					   .animate( { top: '+=10', opacity: 1 }, 50 );
			};

			init_tooltip();
			$( window ).resize( init_tooltip );

			var remove_tooltip = function()
			{
				tooltip.animate( { top: '-=10', opacity: 0 }, 50, function()
				{
					$( this ).remove();
				});

				target.attr( 'title', tip );
			};

			target.bind( 'mouseleave', remove_tooltip );
			tooltip.bind( 'click', remove_tooltip );
		});
	});

</script>

</body>
</html>