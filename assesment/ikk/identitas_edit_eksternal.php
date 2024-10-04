<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/Kelautan.php");
include_once("../WEB/classes/base-simpeg/Pangkat.php");
include_once("../WEB/classes/base-simpeg/Eselon.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/functions/date.func.php");
ini_set("memory_limit","100M");

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

/* create objects */
$pegawai= new Kelautan();
$file = new FileHandler();
//$file = new FileHandler();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$pangkat= new Pangkat();
$pangkat->selectByParams();

$eselon= new Eselon();
$eselon->selectByParams();

$pendidikan= new Kelautan();
$pendidikan->selectByParamsDataPendidikan();

/* VARIABLE */
$reqDaftarAlamatId = httpFilterRequest("reqDaftarAlamatId");
$reqMode = httpFilterRequest("reqMode");
$reqRowMode = httpFilterGet("reqRowMode");
$reqPegawaiId = httpFilterRequest("reqPegawaiId");
$reqStatusJenis = httpFilterGet("reqStatusJenis");
$infostatusjenis= $reqStatusJenis;

/* VALIDATION */
$pegawai->selectByParamsPegawai(array("A.PEGAWAI_ID" => $reqPegawaiId));
$pegawai->firstRow();
// echo $pegawai->query;exit;

$tempTipePegawai= $pegawai->getField('TIPE_PEGAWAI_ID');
$tempGelarDepan= $pegawai->getField('GELAR_DEPAN');
$tempGelarBelakang= $pegawai->getField('GELAR_BELAKANG');
$tempTglPensiun= datetimeToPage($pegawai->getField('TANGGAL_PENSIUN'), "date");
$tempJenisPegawai= $pegawai->getField('JENIS_PEGAWAI_ID');
$tempKartuPegawai= $pegawai->getField('KARTU_PEGAWAI');
$tempSukuBangsa= $pegawai->getField('SUKU_BANGSA');
$tempGolDarah= $pegawai->getField('GOLONGAN_DARAH');
$tempAkses= $pegawai->getField('ASKES');
$tempTaspen= $pegawai->getField('TASPEN');
$tempNPWP= $pegawai->getField('NPWP');
$tempNIK= $pegawai->getField('NIK');
$tempPropinsi= $pegawai->getField('PROPINSI_ID');
$tempKabupaten= $pegawai->getField('KABUPATEN_ID');
$tempKecamatan= $pegawai->getField('KECAMATAN_ID');
$tempDesa= $pegawai->getField('KELURAHAN_ID');
$tempBank= $pegawai->getField('BANK_ID');
$tempNoRekening= $pegawai->getField('NO_REKENING');
$tempJurusanTerkahir= $pegawai->getField('JURUSAN');
$tempTahunLulus= $pegawai->getField('TAHUN');
$tempGambar= $pegawai->getField('FOTO_BLOB');
$tempAgamaId= $pegawai->getField('AGAMA_ID');
$tempKedudukanId= $pegawai->getField('KEDUDUKAN_ID');
$data = $pegawai->getField('FOTO_BLOB');
$data_other = $pegawai->getField('FOTO_BLOB_OTHER');
$data_karpeg= $pegawai->getField('DOSIR_KARPEG');
$data_askes= $pegawai->getField('DOSIR_ASKES');
$data_taspen= $pegawai->getField('DOSIR_TASPEN');
$data_npwp= $pegawai->getField('DOSIR_NPWP');
$reqGambarTmp   = $pegawai->getField('FOTO_BLOB');
$tempGambarSetengah= $pegawai->getField('FOTO_BLOB_OTHER');
$reqGambarTmpSetengah= $pegawai->getField('FOTO_BLOB_OTHER');


$reqNipLama= $pegawai->getField('NIP_LAMA');
$reqNipBaru= $pegawai->getField('NIP_BARU');
$reqNama= $pegawai->getField('NAMA');
$reqStatusJenis= $pegawai->getField('STATUS_JENIS');
$reqStatusPegawaiId= $pegawai->getField('STATUS_PEGAWAI_ID');
$reqStatusNama= $pegawai->getField('STATUS');
$reqTempatLahir= $pegawai->getField('TEMPAT_LAHIR');
$reqTglLahir= datetimeToPage($pegawai->getField('TGL_LAHIR'), "date");
$reqJenisKelamin= $pegawai->getField('JENIS_KELAMIN');
$reqStatusKawin= $pegawai->getField('STATUS_KAWIN');
$reqAlamat= $pegawai->getField('ALAMAT');
$reqAlamatTempatKerja= $pegawai->getField('ALAMAT_TEMPAT_KERJA');
$reqRt= $pegawai->getField('RT');
$reqRw= $pegawai->getField('RW');
$reqEmail= $pegawai->getField('EMAIL');
$reqSosmed= $pegawai->getField('SOSIAL_MEDIA');
$reqAutoAnamnesa= $pegawai->getField('AUTO_ANAMNESA');
$reqTelepon= $pegawai->getField('TELP');
$reqKodePos= $pegawai->getField('KODEPOS');
$reqKtp= $pegawai->getField('KTP');
$reqHp= $pegawai->getField('HP');
$reqAgama= strtoupper($pegawai->getField('AGAMA'));
// $reqSatuanKerjaId=$pegawai->getField('KODE_UNKER');
// $reqSatuanKerjaNama=$pegawai->getField('SATKER');
$reqSatuanEksternalKerjaId=$pegawai->getField('KODE_EKSTERNAL');
$reqSatuanKerjaNama=$pegawai->getField('SATKER_EKSTERNAL');
$reqJabatanLamar=$pegawai->getField('JABATAN_LAMAR');

$reqTempatKerja= $pegawai->getField('TEMPAT_KERJA');

$reqPangkatId= $pegawai->getField('LAST_PANGKAT_ID');
$reqPangkatKode= $pegawai->getField('NAMA_GOL');
$reqPangkatTmt= datetimeToPage($pegawai->getField('TMT_GOL_AKHIR'), "date");
$reqJabatanId= $pegawai->getField('LAST_ESELON_ID');
$reqJabatanNama= $pegawai->getField('NAMA_JAB_STRUKTURAL');
$reqJabatanTmt= datetimeToPage($pegawai->getField('TMT_JABATAN'), "date");
$reqPendidikanId= $pegawai->getField('LAST_DIK_JENJANG');
$reqPendidikanNama= $pegawai->getField('PENDIDIKAN_NAMA');
$reqPendidikanJurusan= $pegawai->getField('LAST_DIK_JURUSAN');

if(empty($reqPegawaiId))
{
	$reqStatusJenis= $infostatusjenis;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="../WEB/lib/autokomplit/jquery-ui.css">
<script src="../WEB/lib/autokomplit/jquery-ui.js"></script>  
<style>
	.ui-autocomplete {
		max-height: 200px;
		overflow-y: auto;
		font-size:11px;
		overflow-x: hidden;
	}
	* html .ui-autocomplete {
		height: 200px;
	}
</style>

<!-- AUTO KOMPLIT -->
<script type="text/javascript" src="../WEB/lib/easyui/easyloader.js"></script>   
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.form.js"></script>  
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.linkbutton.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.draggable.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.resizable.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.panel.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.window.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.progressbar.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.messager.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.validatebox.js"></script>  
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.combo.js"></script>

<script type="text/javascript" src="../WEB/lib/easyui/kalender.js"></script>
<!-- <script type="text/javascript" src="../WEB/lib/easyui/klik_kanan.js"></script> -->

<script type="text/javascript">
$(function(){
	$('#ff').form({
		url:'../json-ikk/identitas_edit.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			// console.log(data);return false;
			data = data.split("-");
			rowid= data[0];
			infodata= data[1];

			if(rowid == "xxx")
			{
				$.messager.alert('Error', infodata, 'error');
			}
			else
			{
				parent.reloadparenttab();
				$.messager.alert('Info',infodata,'info',function(){
					parent.frames['menuFrame'].location.href = "../silat/pegawai_menu_edit.php?reqPegawaiId="+rowid+"&reqMode=external";
					document.location.href = "identitas_edit_eksternal.php?reqPegawaiId="+rowid;
				});
			}
		}
	});

	$('input[id^="reqSatuanKerjaNama"]').each(function(){
		$(this).autocomplete({
			source:function(request, response){
				var id= this.element.attr('id');
				var replaceAnakId= replaceAnak= urlAjax= "";

				if (id.indexOf('reqSatuanKerjaNama') !== -1)
				{
					var element= id.split('reqSatuanKerjaNama');
					var indexId= "reqSatuanEksternalKerjaId"+element[1];
					urlAjax= '../json-ikk/satuan_kerja_eksternal_combo.php';
				}

				$.ajax({
					url: urlAjax,
					type: "GET",
					dataType: "json",
					data: { term: request.term },
					success: function(responseData){
						if(responseData == null)
						{
							response(null);
						}
						else
						{
							var array = responseData.map(function(element) {
								return {desc: element['desc'], id: element['id'], label: element['label']};
							});
							response(array);
						}
					}
				})
			},
			focus: function (event, ui) 
			{ 
				var id= $(this).attr('id');
				if (id.indexOf('reqSatuanKerjaNama') !== -1)
				{
					var element= id.split('reqSatuanKerjaNama');
					var indexId= "reqSatuanEksternalKerjaId"+element[1];
				}

				$("#"+indexId).val(ui.item.id).trigger('change');
			},
			autoFocus: true
		})
		.autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
    }
    ;
    });
	
	$('#reqNip2,#reqKtp,#reqHp').bind('keyup paste', function(){
		this.value = this.value.replace(/[^0-9]/g, '');
		// this.value = this.value.replace(/[^0-9\.]/g, '');
	});

});
</script>
<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css" media="screen" /> 
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />

<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
	<style type="text/css" media="screen">
  label {
    font-size: 10px;
    font-weight: bold;
    text-transform: uppercase;
    margin-bottom: 3px;
    clear: both;
  }
</style>
<style type="text/css">
html, body {height:100%; margin:0; padding:0;}
#page-background {position:fixed; top:0; left:0; width:100%; height:100%;}
#content {position:relative; z-index:1;}
#bg {position:fixed; top:0; left:0; width:100%; height:100%;}
#content {position:relative; z-index:1;}
</style>
    
</head>

<body>
<div id="page_effect">

<form id="ff" method="post" novalidate>
<div id="content" style="height:auto; margin-top:-4px; width:100%">

<div class="content" style="height:97%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
    <table class="table_list" cellspacing="1" width="100%">
		<tr>
	    	<td colspan="6">
	        	<div id="header-tna-detil">Identitas <span>Pegawai</span></div>	                    
			</td>			
	    </tr>
	    <tr>
	    	<td width="15%">Jenis Pegawai</td><td style="width: 2%">:</td>
			<td>
				<select name="reqStatusJenis">
					<!-- <option value="1" <? if($reqStatusJenis == '1') echo 'selected';?>>PNS Prov Bali</option> -->
					<option value="2" <? if($reqStatusJenis == '2') echo 'selected';?>>PNS Luar Prov</option>
					<option value="3" <? if($reqStatusJenis == '3') echo 'selected';?>>Kalangan Umum</option>
				</select>
			</td>
			<td>Status Pegawai</td><td>:</td>
			<td>
				<select id="reqStatusPegawaiId" name="reqStatusPegawaiId">
					<option value="" <? if($reqStatusPegawaiId == "") echo "disabled selected";?>>Pilih Status</option>
					<option value="1" <? if($reqStatusPegawaiId == "1") echo "selected";?>>CPNS</option>
					<option value="2" <? if($reqStatusPegawaiId == "2") echo "selected";?>>PNS</option>
					<option value="3" <? if($reqStatusPegawaiId == "3") echo "selected";?>>UMUM / NON ASN</option>
					<option value="4" <? if($reqStatusPegawaiId == "4") echo "selected";?>>Pensiun</option>
				</select>
			</td>	
	    </tr>
	    <tr>          
	        <td>Nama</td><td>:</td>
			<td colspan="4">
				<input type="text" style="width:318px" name="reqNama" value="<?=$reqNama?>" class="required" title="Nama harus diisi" dixxxsabled="dixxxsabled" />
			</td>
	    </tr>
	    <tr>
	        <td>NIP/KTP</td><td>:</td>
			<td>
				<input type="hidden" style="width:150px" name="reqNIP1" dixxxsabled="dixxxsabled" <? if($reqPegawaiId == "") { ?> class="reqNIP1" <? } ?> id="reqNIP1" value="<?=$reqNipLama?>" /><?php /*?>/<?php */?>
				<input type="text" style="width:45%" name="reqNIP2" id="reqNIP2" value="<?=$reqNipBaru?>" />
			</td>
	    </tr>
	    <tr>
	    	<td width="20%">Instansi</td><td width="2%">:</td>
			<td colspan="4">
				<input type="text" style="width:45%" name="reqTempatKerja" id="reqTempatKerja" value="<?=$reqTempatKerja?>" />
			</td>			
	    </tr>

	    <tr>           
			<td>Jabatan Saat Ini</td><td>:</td>			
			<td>
				<!-- <select name="reqJabatanId" style="width: 10%">
					<option value=""></option>
					<?
					while ($eselon->nextRow()) 
					{
						$infoid= $eselon->getField("ESELON_ID");
						$infonama= $eselon->getField("NAMA");
					?>
					<option value="<?=$infoid?>" <? if($infoid == $reqJabatanId) echo "selected";?>><?=$infonama?></option>
					<?
					}
					?>
				</select> -->
				<input type="text" name="reqJabatanNama" id="reqJabatanNama" <?=$read?> value="<?=$reqJabatanNama?>" style="width:85%" class="form-control easyui-validatebox" dixxxsabled="dixxxsabled"  />
			</td>		
	    </tr>

	    <tr>           
			<td>Jabatan Yang Dilamar</td><td>:</td>			
			<td>
				<!-- <select name="reqJabatanId" style="width: 10%">
					<option value=""></option>
					<?
					while ($eselon->nextRow()) 
					{
						$infoid= $eselon->getField("ESELON_ID");
						$infonama= $eselon->getField("NAMA");
					?>
					<option value="<?=$infoid?>" <? if($infoid == $reqJabatanId) echo "selected";?>><?=$infonama?></option>
					<?
					}
					?>
				</select> -->
				<input type="text" name="reqJabatanLamar" id="reqJabatanLamar" <?=$read?> value="<?=$reqJabatanLamar?>" style="width:85%" class="form-control easyui-validatebox" dixxxsabled="dixxxsabled"  />
			</td>		
	    </tr>
	
		
		<tr>      
			<td>Tempat Lahir</td><td>:</td>
			<td colspan="4">
				<input type="text" style="width:100px" name="reqTempatLahir" value="<?=$reqTempatLahir?>" disabled="disabled" />
				Tanggal Lahir
				<input type="text" class="easyui-datebox" style="width:100px" name="reqTglLahir" id="reqTglLahir" disabled="disabled" maxlength="10" value="<?=$reqTglLahir?>" />
				<!-- onkeydown="return format_date(event,'reqTglLahir');" -->
			</td>
        </tr>
		<tr>           
			<td>Jenis Kelamin</td><td>:</td>
			<td colspan="4">
				<select name="reqJenisKelamin" disabled="disabled" >
					<option value="L" <? if($reqJenisKelamin == 'L') echo 'selected';?>>L</option>
					<option value="P" <? if($reqJenisKelamin == 'P') echo 'selected';?>>P</option>
				</select>
			</td>
        </tr>
        <tr>
        	<td>Agama</td><td>:</td>
        	<td colspan="4">
        		<select disabled="disabled" name="reqAgama" id="reqAgama" class="form-control" <?=$disabled?> style="width:45%">
        			<option value="" <? if($reqAgama == "") echo "selected"?>></option>
        			<option value="ISLAM" <? if($reqAgama == "ISLAM") echo "selected"?>>ISLAM</option>
        			<option value="KRISTEN PROTESTAN" <? if($reqAgama == "KRISTEN PROTESTAN") echo "selected"?>>KRISTEN PROTESTAN</option>
        			<option value="KRISTEN KATHOLIK" <? if($reqAgama == "KRISTEN KATHOLIK") echo "selected"?>>KRISTEN KATHOLIK</option>
        			<option value="HINDU" <? if($reqAgama == "HINDU") echo "selected"?>>HINDU</option>
        			<option value="BUDHA" <? if($reqAgama == "BUDHA") echo "selected"?>>BUDHA</option>
        			<option value="KONGHUCHU" <? if($reqAgama == "KONGHUCHU") echo "selected"?>>KONGHUCHU</option>
        		</select>
        	</td>
        </tr>
		<tr>
			<td>Status Pernikahan</td><td>:</td>
			<td colspan="4">
				<select name="reqStatusKawin" disabled="disabled" >
					<option value="1" <? if($reqStatusKawin == "1") echo 'selected'?>>Belum Kawin</option>
					<option value="2" <? if($reqStatusKawin == "2") echo 'selected'?>>Kawin</option>
					<option value="3" <? if($reqStatusKawin == "3") echo 'selected'?>>Janda</option>
					<option value="4" <? if($reqStatusKawin == "4") echo 'selected'?>>Duda</option>
				</select>
			</td>
        </tr>
		<tr>           
			<td>Alamat</td><td>:</td>			
			<td colspan="4">
                <textarea name="reqAlamat" style="width: 99%" rows="2" disabled><?=$reqAlamat?></textarea>
			</td>
        </tr>
        <tr>
        	<td>Alamat Tempat Kerja</td><td>:</td>
        	<td colspan="4">
        		<textarea name="reqAlamatTempatKerja" style="width: 99%" rows="2" disabled><?=$reqAlamatTempatKerja?></textarea>
        	</td>
        </tr>
        <tr>
        	<td>Alamat Email</td><td>:</td>
        	<td colspan="4">
        		<input type="text" name="reqEmail" id="reqEmail" <?=$read?> value="<?=$reqEmail?>" style="width:50%" class="form-control easyui-validatebox" disabled="disabled"  />
        	</td>
        </tr>
        <tr>
        	<td>Akun Sosial Media</td><td>:</td>
        	<td colspan="4">
        		<input type="text" name="reqSosmed" id="reqSosmed" <?=$read?> value="<?=$reqSosmed?>" style="width:50%" class="form-control easyui-validatebox" disabled="disabled"  />
        	</td>
        </tr>
        <tr>
        	<td>Kontak Pribadi (HP)</td><td>:</td>
        	<td colspan="4">
        		<input type="text" style="width:20%" name="reqHp" id="reqHp" <?=$read?> value="<?=$reqHp?>" class="form-control easyui-validatebox"  disabled="disabled" />
        	</td>
        </tr>
        <tr>
        	<td>Auto Anamnesa</td><td>:</td>
        	<td colspan="4">
        		<input type="text" style="width:99%" name="reqAutoAnamnesa" id="reqAutoAnamnesa" <?=$read?> value="<?=$reqAutoAnamnesa?>" class="form-control easyui-validatebox"  disabled="disabled" />
        	</td>
        </tr>	
		<tr style="display:none">
			<td>RT</td><td>:</td>			
			<td>
				<input type="text" style="width:50px" name="reqRT" disabled="disabled" value="<?=$reqRt?>" />
				RW
				<input type="text" style="width:50px" name="reqRW" disabled="disabled" value="<?=$reqRw?>" />
			</td>
        </tr>
		<tr style="display:none">
			<td>Telepon</td><td>:</td>			
			<td>
				<input type="text" style="width:100px" name="reqTelepon" disabled="disabled" value="<?=$reqTelepon?>" />
				Kode Pos
				<input type="text" style="width:100px" name="reqKodePos" disabled="disabled" value="<?=$reqKodePos?>" />
			</td>
        </tr>
		
		<tr>           
			<td>Pangkat Terakhir</td><td>:</td>
			<td>
				<select name="reqPangkatId" disabled>
					<option value=""></option>
					<?
					while ($pangkat->nextRow()) 
					{
						$infoid= $pangkat->getField("PANGKAT_ID");
						$infonama= $pangkat->getField("NAMA")." (".$pangkat->getField("KODE").")";
					?>
					<option value="<?=$infoid?>" <? if($infoid == $reqPangkatId) echo "selected";?>><?=$infonama?></option>
					<?
					}
					?>
				</select>
			</td>
			<td>TMT Pangkat</td><td>:</td>
			<td>
				<input type="text" class="easyui-datebox" style="width:100px" name="reqPangkatTmt" id="reqPangkatTmt" disabled="disabled" maxlength="10" value="<?=$reqPangkatTmt?>" />
			</td>
        </tr>
		<tr>           
			<td>Jabatan Terakhir</td><td>:</td>			
			<td>
				<!-- <select name="reqJabatanId" style="width: 10%" disabled>
					<option value=""></option>
					<?
					while ($eselon->nextRow()) 
					{
						$infoid= $eselon->getField("ESELON_ID");
						$infonama= $eselon->getField("NAMA");
					?>
					<option value="<?=$infoid?>" <? if($infoid == $reqJabatanId) echo "selected";?>><?=$infonama?></option>
					<?
					}
					?>
				</select> -->
				<input type="text" name="reqJabatanNama" id="reqJabatanNama" <?=$read?> value="<?=$reqJabatanNama?>" style="width:85%" class="form-control easyui-validatebox" disabled="disabled"  />
			</td>
			<td>TMT Jabatan</td><td>:</td>			
			<td>
				<input type="text" class="easyui-datebox" style="width:100px" name="reqJabatanTmt" id="reqJabatanTmt" disabled="disabled" maxlength="10" value="<?=$reqJabatanTmt?>" />
			</td>		
        </tr>
        <tr>           
			<td>Pendidikan Terakhir</td><td>:</td>			
			<td colspan="4">
				<input type="text" name="reqPendidikanJurusan" id="reqPendidikanJurusan" <?=$read?> value="<?=$reqPendidikanJurusan?>" style="width:45%" class="form-control easyui-validatebox" disabled="disabled"  />
				<select name="reqPendidikanId" disabled>
				<?
				while ($pendidikan->nextRow()) 
				{
					$infoid= $pendidikan->getField("PENDIDIKAN_ID");
					$infonama= $pendidikan->getField("NAMA");
				?>
				<option value="<?=$infoid?>" <? if($infoid == $reqPendidikanId) echo "selected";?>><?=$infonama?></option>
				<?
				}
				?>
				</select>
			</td>
        </tr>
		<? 
		// }
		?>
		<tr>
			<td colspan="6">
				<input type="submit" name="" value="Simpan" />
				<input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
				<input type="hidden" name="reqJabatanId" value="0" />
				<!-- <button class="bg-button">Simpan</button> -->
			</td>
		</tr>
    </table>
</div>
</div>
</form>
</div>
</body>
</html>