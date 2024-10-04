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
                    	Selamat datang, <strong>CAECILIA WIDYANINGTYAS, S.Psi., Psikolog</strong> - 
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
                        	<div class="breadcrumb"><a href="index.php"><i class="fa fa-home"></i> Home</a> &raquo;
                                                        <a href="kegiatan.php?reqJadwalTesId=24&reqJadwalAsesorId=351">Data Kegiatan</a>
                             &raquo; Data Peserta</div>
                        	
                        	<div class="judul-halaman">Data Peserta Asesor :</div>
                        	<div class="area-table-assesor">
                            	<table>
                                <tbody>
                                	<tr>
                                    	<td style="width:150px">Nama</td>
                                        <td>:</td>
                                        <td><strong> Drs. MADDAREMMENG, M.Si </strong></td>
                                        <td rowspan="8" align="center">
                                        	<img src="../WEB/images/foto2.png" width="180">
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Jabatan Saat ini</td>
                                        <td>:</td>
                                        <td> Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal </td>
                                    </tr>
                                    <tr>
                                    	<td>Unit Kerja Saat ini</td>
                                        <td>:</td>
                                        <td> SEKRETARIAT JENDERAL </td>
                                    </tr>
                                    <tr>
                                    	<td>Jabatan Saat Tes</td>
                                        <td>:</td>
                                        <td> Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal </td>
                                    </tr>
                                    <tr>
                                    	<td>Unit Kerja Saat Tes</td>
                                        <td>:</td>
                                        <td> SEKRETARIAT JENDERAL </td>
                                    </tr>
                                    <tr>
                                    	<td>Nama Asesi</td>
                                        <td>:</td>
                                        <td> CAECILIA WIDYANINGTYAS, S.Psi., Psikolog </td>
                                    </tr>
                                    <tr>
                                    	<td>Metode</td>
                                        <td>:</td>
                                        <td> Competence Based Interview </td>
                                    </tr>
                                    <tr>
                                    	<td>Tanggal Tes</td>
                                        <td>:</td>
                                        <td> 17 November 2018 </td>
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
								                                    
                                                                        <tr>
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;">Integritas                                                                                                                                    </div>
                                            
                                                                                        <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-0--" class=" sebelumselected">
												Menciptakan situasi kerja yang mendorong seluruh pemangku kepentingan mematuhi nilai, norma, dan etika organisasi dalam segala situasi dan Kondisi                                                </li>
                                                                        
									                                    
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-1--" class=" sebelumselected">
												Mendukung dan menerapkan prinsip moral dan standar etika yang tinggi, serta berani menanggung konsekuensinya                                                </li>
                                                                        
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-2--" class=" sebelumselected">
												Berani melakukan koreksi atau mengambil tindakan atas penyimpangan kode etik/nilai-nilai yang dilakukan oleh orang lain, pada tataran lingkup kerja setingkat instansi meskipun ada resiko                                                </li>
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
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId2" value="15695" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId2" value="24" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes2" value="17-11-2018" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId2" value="Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId2" value="01" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId2" value="351" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId2" value="2" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId2" value="0901" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar2" value="3" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai2" value="3" />
                                                <input type="hidden" name="reqGap[]" id="reqGap2" value="0" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId2" value="8" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId2" value="142" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId2" value="11" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId2" value="850" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio2" id="reqRadio2" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio2]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio2" id="reqRadio2" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" checked name="reqRadio2" id="reqRadio2" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio2" id="reqRadio2" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio2" id="reqRadio2" value="5" /></td>
                                            </tr>
                                                                                        <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><br></textarea>
                                                </td>
                                            </tr>
                                                                                        </table>
                                        </td>
                                    </tr>
                                                                        
                                                                        <tr>
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;">Kerjasama                                                                                                                                     </div>
                                            
                                                                                        <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-3--" class=" sebelumselected">
												Membangun sinergi antar unit kerja di lingkup instansi yang dipimpin                                                </li>
                                                                        
									                                    
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-4--" class=" sebelumselected">
												Memfasilitasi kepentingan yang berbeda dari unit kerja lain sehingga tercipta sinergi dalam rangka pencapaian target kerja organisasi                                                </li>
                                                                        
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-5--" class=" sebelumselected">
												Mengembangkan sistem yang menghargai kerja sama antar unit, memberikan dukungan/ semangat untuk memastikan tercapainya sinergi dalam rangka pencapaian target kerja organisasi                                                </li>
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
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId5" value="15696" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId5" value="24" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes5" value="17-11-2018" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId5" value="Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId5" value="01" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId5" value="351" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId5" value="2" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId5" value="0902" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar5" value="3" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai5" value="4" />
                                                <input type="hidden" name="reqGap[]" id="reqGap5" value="1" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId5" value="8" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId5" value="143" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId5" value="11" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId5" value="850" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio5" id="reqRadio5" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio5]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio5" id="reqRadio5" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio5" id="reqRadio5" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" checked name="reqRadio5" id="reqRadio5" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio5" id="reqRadio5" value="5" /></td>
                                            </tr>
                                                                                        <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><br></textarea>
                                                </td>
                                            </tr>
                                                                                        </table>
                                        </td>
                                    </tr>
                                                                        
                                                                        <tr>
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;">Orientasi pada Hasil                                                                                                                                     </div>
                                            
                                                                                        <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-6--" class=" sebelumselected">
												Mendorong unit kerja di tingkat instansi untuk mencapai kinerja yang melebihi target yang ditetapkan                                                </li>
                                                                        
									                                    
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-7--" class=" sebelumselected">
												Memantau dan mengevaluasi hasil kerja unitnya agar selaras dengan sasaran strategis instansi                                                </li>
                                                                        
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-8--" class=" sebelumselected">
												Mendorong pemanfaatan sumber daya bersama antar unit kerja dalam rangka meningkatkan efektifitas dan efisiensi pencapaian target organisasi                                                </li>
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
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId8" value="15697" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId8" value="24" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes8" value="17-11-2018" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId8" value="Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId8" value="01" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId8" value="351" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId8" value="2" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId8" value="0904" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar8" value="3" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai8" value="4" />
                                                <input type="hidden" name="reqGap[]" id="reqGap8" value="1" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId8" value="8" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId8" value="145" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId8" value="11" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId8" value="850" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio8" id="reqRadio8" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio8]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio8" id="reqRadio8" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio8" id="reqRadio8" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" checked name="reqRadio8" id="reqRadio8" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio8" id="reqRadio8" value="5" /></td>
                                            </tr>
                                                                                        <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><br></textarea>
                                                </td>
                                            </tr>
                                                                                        </table>
                                        </td>
                                    </tr>
                                                                        
                                                                        <tr>
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;">Pelayanan Publik                                                                                                                                    </div>
                                            
                                                                                        <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-9--" class=" sebelumselected">
												Memahami dan memberi perhatian kepada isu-isu jangka panjang, kesempatan atau kekuatan politik yang mempengaruhi  organisasi dalam hubungannya dengan dunia luar, memperhitungkan dan mengantisipasi dampak terhadap pelaksanaan tugas-tugas pelayanan pub                                                </li>
                                                                        
									                                    
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-10--" class=" sebelumselected">
												Menjaga agar kebijakan pelayanan publik yang diselenggarakan oleh instansinya telah selaras dengan standar pelayanan yang objektif, netral, tidak memihak, tidak diskriminatif, serta tidak terpengaruh kepentingan pribadi/kelompok/partai politik                                                </li>
                                                                        
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-11--" class=" sebelumselected">
												Menerapkan strategi jangka panjang yang berfokus pada pemenuhan kebutuhan pemangku kepentingan dalam menyusun kebijakan dengan mengikuti standar objektif, netral, tidak memihak, tidak diskriminatif, transparan, tidak terpengaruh kepentingan pribadi/k                                                </li>
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
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId11" value="15698" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId11" value="24" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes11" value="17-11-2018" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId11" value="Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId11" value="01" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId11" value="351" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId11" value="2" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId11" value="0905" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar11" value="3" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai11" value="4" />
                                                <input type="hidden" name="reqGap[]" id="reqGap11" value="1" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId11" value="8" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId11" value="146" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId11" value="11" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId11" value="850" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio11" id="reqRadio11" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio11]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio11" id="reqRadio11" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio11" id="reqRadio11" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" checked name="reqRadio11" id="reqRadio11" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio11" id="reqRadio11" value="5" /></td>
                                            </tr>
                                                                                        <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><br></textarea>
                                                </td>
                                            </tr>
                                                                                        </table>
                                        </td>
                                    </tr>
                                                                        
                                                                        <tr>
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;">Pengembangan Diri dan Orang Lain                                                                                                                                     </div>
                                            
                                                                                        <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-12--" class=" sebelumselected">
												Menyusun program pengembangan jangka panjang bersama-sama dengan bawahan, termasuk didalamnya penetapan tujuan, bimbingan, penugasan dan pengalaman lainnya, serta mengalokasikan waktu untuk mengikuti pelatihan/pendidikan/ pengembangan kompetensi dan                                                 </li>
                                                                        
									                                    
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-13--" class=" sebelumselected">
												Melaksanakan manajemen pembelajaran termasuk evaluasi dan umpan balik pada tataran organisasi                                                </li>
                                                                        
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-14--" class=" sebelumselected">
												Mengembangkan orang-orang disekitarnya secara konsisten, melakukan kaderisasi untuk posisi-posisi di unit kerjanya                                                </li>
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
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId14" value="15699" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId14" value="24" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes14" value="17-11-2018" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId14" value="Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId14" value="01" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId14" value="351" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId14" value="2" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId14" value="0906" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar14" value="3" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai14" value="3" />
                                                <input type="hidden" name="reqGap[]" id="reqGap14" value="0" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId14" value="8" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId14" value="147" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId14" value="11" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId14" value="850" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio14" id="reqRadio14" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio14]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio14" id="reqRadio14" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" checked name="reqRadio14" id="reqRadio14" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio14" id="reqRadio14" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio14" id="reqRadio14" value="5" /></td>
                                            </tr>
                                                                                        <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><br></textarea>
                                                </td>
                                            </tr>
                                                                                        </table>
                                        </td>
                                    </tr>
                                                                        
                                                                        <tr>
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;">Pengambilan Keputusan                                                                                                                                    </div>
                                            
                                                                                        <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-15--" class=" sebelumselected">
												Menyusun dan/atau memutuskan konsep penyelesaian masalah yang melibatkan beberapa/seluruh fungsi dalam organisasi                                                </li>
                                                                        
									                                    
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-16--" class=" sebelumselected">
												Menghasilkan solusi dari berbagai masalah yang kompleks, terkait dengan bidang kerjanya yang berdampak pada pihak lain                                                </li>
                                                                        
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-17--" class=" sebelumselected">
												Membuat keputusan dan mengantisipasi dampak keputusannya serta menyiapkan tindakan  penanganannya (mitigasi risiko)                                                </li>
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
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId17" value="15700" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId17" value="24" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes17" value="17-11-2018" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId17" value="Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId17" value="01" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId17" value="351" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId17" value="2" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId17" value="0908" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar17" value="3" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai17" value="4" />
                                                <input type="hidden" name="reqGap[]" id="reqGap17" value="1" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId17" value="8" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId17" value="149" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId17" value="11" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId17" value="850" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio17" id="reqRadio17" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio17]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio17" id="reqRadio17" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio17" id="reqRadio17" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" checked name="reqRadio17" id="reqRadio17" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio17" id="reqRadio17" value="5" /></td>
                                            </tr>
                                                                                        <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><br></textarea>
                                                </td>
                                            </tr>
                                                                                        </table>
                                        </td>
                                    </tr>
                                                                        
                                                                        <tr>
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;">Perekat Bangsa                                                                                                                                    </div>
                                            
                                                                                        <div class="rbtn">
                                            <ul>
                                            	<li style="width:100%; text-align:left" id="rbtn-18--" class=" sebelumselected">
												Menginisiasi dan merepresentasikan pemerintah di lingkungan kerja dan masyarakat untuk senantiasa menjaga persatuan dan kesatuan dalam keberagaman dan menerima segala bentuk perbedaan dalam kehidupan bermasyarakat                                                </li>
                                                                        
									                                    
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-19--" class=" sebelumselected">
												Mampu mendayagunakan perbedaan latar belakang, agama/kepercayaan, suku, jender, sosial ekonomi, preferensi politik untuk mencapai kelancaran pencapaian tujuan organisasi                                                </li>
                                                                        
                                                                        
									                                                <br/><li style="width:100%; text-align:left" id="rbtn-20--" class=" sebelumselected">
												Mampu membuat program yang mengakomodasi perbedaan latar belakang, agama/kepercayaan, suku, jender, sosial ekonomi, preferensi politik                                                </li>
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
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId20" value="15701" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId20" value="24" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes20" value="17-11-2018" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId20" value="Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId20" value="01" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId20" value="351" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId20" value="2" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId20" value="1001" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar20" value="3" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai20" value="3" />
                                                <input type="hidden" name="reqGap[]" id="reqGap20" value="0" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId20" value="8" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId20" value="150" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId20" value="11" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId20" value="850" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio20" id="reqRadio20" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio20]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio20" id="reqRadio20" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" checked name="reqRadio20" id="reqRadio20" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio20" id="reqRadio20" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio20" id="reqRadio20" value="5" /></td>
                                            </tr>
                                                                                        <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><br></textarea>
                                                </td>
                                            </tr>
                                                                                        </table>
                                        </td>
                                    </tr>
                                                                        <tr>
                                        <td colspan="2" align="center">
                                        	<input type="hidden" name="reqMode" value="insert">
                                            <input name="submit1" type="submit" value="Simpan" />
                                        </td>
                                    </tr>
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
<script>
$(document).ready(function() {
	$(function(){
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
			document.location.href = 'penilaian_pegawai.php?reqAspekId=2&reqJadwalAsesorId=351&reqJadwalPegawaiId=2610';
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