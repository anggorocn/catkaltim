<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/Kelautan.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/functions/date.func.php");
ini_set("memory_limit","100M");

/* create objects */
$pegawai= new Kelautan();
$file = new FileHandler();
//$file = new FileHandler();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqDaftarAlamatId 	= httpFilterRequest("reqDaftarAlamatId");
$reqMode 			= httpFilterRequest("reqMode");
$reqRowMode 		= httpFilterGet("reqRowMode");
$reqPegawaiId 		= httpFilterRequest("reqPegawaiId");

/* VALIDATION */
$pegawai->selectByParamsPegawai(array("A.PEGAWAI_ID" => $reqPegawaiId));
$pegawai->firstRow();
// echo $pegawai->query;exit;

$tempNIP1				= $pegawai->getField('NIP_LAMA');
$tempNIP2				= $pegawai->getField('NIP_BARU');
$tempNama				= $pegawai->getField('NAMA');
$tempTipePegawai		= $pegawai->getField('TIPE_PEGAWAI_ID');
$tempGelarDepan			= $pegawai->getField('GELAR_DEPAN');
$tempGelarBelakang		= $pegawai->getField('GELAR_BELAKANG');
$tempStatusPegawai		= $pegawai->getField('STATUS');
$tempTempatLahir		= $pegawai->getField('TEMPAT_LAHIR');
$tempTanggalLahir		= datetimeToPage($pegawai->getField('TGL_LAHIR'), "date");
$tempTglPensiun			= datetimeToPage($pegawai->getField('TANGGAL_PENSIUN'), "date");
$tempJenisKelamin		= $pegawai->getField('JENIS_KELAMIN');
$tempJenisPegawai		= $pegawai->getField('JENIS_PEGAWAI_ID');
$tempStatusPernikahan	= $pegawai->getField('STATUS_KAWIN');
// echo $tempStatusPernikahan;exit;
$tempKartuPegawai		= $pegawai->getField('KARTU_PEGAWAI');
$tempSukuBangsa			= $pegawai->getField('SUKU_BANGSA');
$tempGolDarah			= $pegawai->getField('GOLONGAN_DARAH');
$tempAkses				= $pegawai->getField('ASKES');
$tempTaspen				= $pegawai->getField('TASPEN');
$tempAlamat				= $pegawai->getField('ALAMAT');
$tempAlamatTempatKerja	= $pegawai->getField('ALAMAT_TEMPAT_KERJA');

$tempNPWP				= $pegawai->getField('NPWP');
$tempNIK				= $pegawai->getField('NIK');
$tempRT					= $pegawai->getField('RT');
$tempRW					= $pegawai->getField('RW');
$tempEmail				= $pegawai->getField('EMAIL');
$tempPropinsi			= $pegawai->getField('PROPINSI_ID');
$tempKabupaten			= $pegawai->getField('KABUPATEN_ID');
$tempKecamatan			= $pegawai->getField('KECAMATAN_ID');
$tempDesa				= $pegawai->getField('KELURAHAN_ID');
$tempBank				= $pegawai->getField('BANK_ID');
$tempNoRekening			= $pegawai->getField('NO_REKENING');
$tempPangkatTerkahir	= $pegawai->getField('NAMA_GOL');
$tempTMTPangkat			= $pegawai->getField('TMT_GOL_AKHIR');
$tempJabatanTerkahir	= $pegawai->getField('NAMA_JAB_STRUKTURAL');
$tempTMTJabatan			= $pegawai->getField('TMT_JABATAN');
$tempPendidikanTerkahir	= $pegawai->getField('PENDIDIKAN_NAMA');
$tempJurusanTerkahir	= $pegawai->getField('JURUSAN');
$tempTahunLulus			= $pegawai->getField('TAHUN');
$tempGambar				= $pegawai->getField('FOTO_BLOB');
$tempAgamaId			= $pegawai->getField('AGAMA_ID');
$tempAgama			    = $pegawai->getField('AGAMA');
$tempSosmed				= $pegawai->getField('SOSIAL_MEDIA');
$tempAuto				= $pegawai->getField('AUTO_ANAMNESA');



$tempTelepon			= $pegawai->getField('TELP');
$tempKodePos			= $pegawai->getField('KODEPOS');
$tempKedudukanId		= $pegawai->getField('KEDUDUKAN_ID');
$tempKtp				= $pegawai->getField('KTP');

$tempHp= $pegawai->getField('HP');



$tempSatuanKerjaNama=$pegawai->getField('SATKER');

$data = $pegawai->getField('FOTO_BLOB');
/*if(strlen($data) > 1 && $reqPegawaiId){
	$im = imagecreatefromstring($data);
	if ($im != false) {
		$save = "../main/foto/". $reqPegawaiId .".png";
		imagepng($im, $save, 0, NULL);		
		imagedestroy($im);
	}
	else {
		echo 'An error occurred.';
	}
}*/

$data_other = $pegawai->getField('FOTO_BLOB_OTHER');
/*if(strlen($data) > 1 && $reqPegawaiId){
	$im = imagecreatefromstring($data);
	if ($im != false) {
		$save = "../main/foto/". $reqPegawaiId ."_OTHER.png";
		imagepng($im, $save, 0, NULL);		
		imagedestroy($im);
	}
	else {
		echo 'An error occurred.';
	}
}*/
$data_karpeg= $pegawai->getField('DOSIR_KARPEG');
$data_askes= $pegawai->getField('DOSIR_ASKES');
$data_taspen= $pegawai->getField('DOSIR_TASPEN');
$data_npwp= $pegawai->getField('DOSIR_NPWP');
			   
$reqGambarTmp   		= $pegawai->getField('FOTO_BLOB');
$tempGambarSetengah		= $pegawai->getField('FOTO_BLOB_OTHER');
$reqGambarTmpSetengah	= $pegawai->getField('FOTO_BLOB_OTHER');

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempNIP2.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
// echo $url; exit;
$data = json_decode(file_get_contents($url), true);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css">
<script language="JavaScript" src="../jslib/displayElement.js"></script>
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>

<!-- <script type="text/javascript" src="jquery-1.4.2.min.js"></script> -->
<!-- 
<script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="validate/jquery-ui.min.js"></script> 
<script type="text/javascript" src="validate/jquery.validate.js"></script>
<link type="text/css" href="validate/jquery-ui.datepickerValidate.css" rel="stylesheet" /> -->

<script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
<link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="../WEB/css/dropdowntabs.js"></script>


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
    /* Remove margins from the 'html' and 'body' tags, and ensure the page takes up full screen height */
    html, body {height:100%; margin:0; padding:0;}
    /* Set the position and dimensions of the background image. */
    #page-background {position:fixed; top:0; left:0; width:100%; height:100%;}
    /* Specify the position and layering for the content that needs to appear in front of the background image. Must have a higher z-index value than the background image. Also add some padding to compensate for removing the margin from the 'html' and 'body' tags. */
    #content {position:relative; z-index:1;}
    /* prepares the background image to full capacity of the viewing area */
    #bg {position:fixed; top:0; left:0; width:100%; height:100%;}
    /* places the content ontop of the background image */
    #content {position:relative; z-index:1;}
    </style>

<!-- <link href="styles.css" rel="stylesheet" type="text/css" /> -->
<script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script>
</head>
<body>
<div id="page_effect">
<!-- <div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div> -->
<form action="" name="frmDaftarAlamat" id="alumniForm" method="post" enctype="multipart/form-data">
<div id="content" style="height:auto; margin-top:-4px; width:100%">

<div class="content" style="height:97%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
    <table class="table_list" cellspacing="1" width="100%">
		<tr>
            <td colspan="6">
            <div id="header-tna-detil">Identitas <span>Pegawai</span></div>	                    
			</td>			
        </tr>
		<tr>
            <td width="20%">NIP</td><td width="2%">:</td>
			<td>
				<input type="hidden" style="width:150px" name="reqNIP1" disabled="disabled" <? if($reqPegawaiId == "") { ?> class="reqNIP1" <? } ?> id="reqNIP1" value="<?=$tempNIP1?>" /><?php /*?>/<?php */?>
				<input type="text" style="width:150px" name="reqNIP2" disabled="disabled" <? if($reqPegawaiId == "") { ?> class="reqNIP2 required"  title="NIP harus diisi" <? } ?> id="reqNIP2" value="<?=$tempNIP2?>" />
			</td>	
			<td width="10%">Satuan Kerja</td><td width="2%">:</td>
			<td width="30%">
				<input type="hidden" style="width:150px" name="reqSatuanKerja" id="reqSatkerId" value="<?=$tempSatuanKerja?>" />
                <textarea style="display:none" name="reqSatuanKerjaNama" id="reqSatkerNama" readonly="readonly" cols="40" rows="2" disabled="disabled" ></textarea>
                <?=$data['skpd']?>
			</td>			
        </tr>
        <tr>
        	<td>No KTP</td><td>:</td>
        	<td>
        		<input type="text" style="width:318px" name="reqKtp" value="<?=$data['nik']?>" class="required" title="Nama harus diisi" disabled="disabled" />
        	</td>
		<tr>          
            <td >Nama</td><td>:</td>
			<td>
				<input type="text" style="width:318px" name="reqNama" value="<?=$data['glr_depan']?> <?=$data['nama']?> <?=$data['glr_belakang']?>" class="required" title="Nama harus diisi" disabled="disabled" />
			</td>
        </tr>
		<tr>
        	<td width="5%">Status Pegawai</td><td width="2%">:</td>
			<td>
				<input type="text" style="width:100px" name="reqStatusPegawai" value="<?=$data['status_pegawai']?>" disabled="disabled" />
			</td>	
		</tr>
		<tr>      
			<td>Tempat Lahir</td><td>:</td>
				<td><input type="text" style="width:100px" name="reqTempatLahir" value="<?=$data['tempat_lahir']?>" disabled="disabled" />
				Tanggal Lahir
				<input type="text" style="width:80px" name="reqTanggalLahir" id="reqTanggalLahir" disabled="disabled" maxlength="10" onkeydown="return format_date(event,'reqTanggalLahir');" value="<?=$data['tgl_lahir']?>" />
			</td>
        </tr>
		<tr>           
			<td width="5%">Jenis Kelamin</td><td width="2%">:</td>
			<td>
				<select name = "reqJenisKelamin" disabled="disabled" >
					<option value="L" <? if($data['jenis_kelamin'] == 'Laki-laki') echo 'selected';?>>L</option>
					<option value="P" <? if($data['jenis_kelamin'] == 'Perempuan') echo 'selected';?>>P</option>
				</select>
			</td>
        </tr>
        <tr>
        	<td>Agama</td><td width="2%">:</td>
        	<td>
        		<select disabled="disabled" name="reqAgama" id="reqAgama" class="form-control" <?=$disabled?> style="width:45%">
        			<option value="" <? if($data['id_agama'] == "") echo "selected"?>></option>
        			<option value="ISLAM" <? if($data['id_agama'] == "1") echo "selected"?>>ISLAM</option>
        			<option value="KRISTEN PROTESTAN" <? if($data['id_agama'] == "2") echo "selected"?>>KRISTEN PROTESTAN</option>
        			<option value="KRISTEN KATHOLIK" <? if($data['id_agama'] == "3") echo "selected"?>>KRISTEN KATHOLIK</option>
        			<option value="HINDU" <? if($data['id_agama'] == "4") echo "selected"?>>HINDU</option>
        			<option value="BUDHA" <? if($data['id_agama'] == "5") echo "selected"?>>BUDHA</option>
        			<option value="KONGHUCHU" <? if($data['id_agama'] == "6") echo "selected"?>>KONGHUCHU</option>
        		</select>
        	</td>
        </tr>
		<tr>
			<td width="5%">Status Pernikahan</td><td width="2%">:</td>
			<td>
				<select name="reqStatusPernikahan" disabled="disabled" >
					<option value="1" <? if($data['id_status_nikah'] == "1") echo 'selected'?>>Menikah</option>
					<option value="2" <? if($data['id_status_nikah'] == "2") echo 'selected'?>>Belum Menikah</option>
					<option value="3" <? if($data['id_status_nikah'] == "3") echo 'selected'?>>Janda/Duda</option>
					<option value="5" <? if($data['id_status_nikah'] == "5") echo 'selected'?>>Cerai</option>
				</select>
			</td>
        </tr>
		<tr>           
			<td>Alamat</td><td>:</td>			
				<td>
                <textarea style="display:none" name="reqAlamat" cols="35" <?php /*?>class="required" title="Alamat harus diisi"<?php */?> disabled="disabled" ><?=$data['alamat']?></textarea>
                <?=$tempAlamat?>
			</td>
        </tr>

       <!--  <tr>
        	<td >Alamat Tempat Kerja</td> <td>:</td>
        	<td>
        		<?=$tempAlamatTempatKerja?>
        	</td>
        </tr> -->
        <tr>
        	<td> Alamat Email</td><td>:</td>
        	<td>
        		<input type="text" name="reqEmail" id="reqEmail" <?=$read?> value="<?=$data['email']?>" style="width:50%" class="form-control easyui-validatebox" disabled="disabled"  />
        	</td>
        </tr>
       <!--  <tr>
        	<td>Akun Sosial Media</td><td>:</td>
        	<td>	
        		<?=$tempSosmed?>
        	</td>
        </tr> -->
        <tr>
        	<td>Kontak Pribadi (HP)</td><td>:</td>
        	<td>
        		<input type="text" style="width:40%" name="reqHp" id="reqHp" <?=$read?> value="<?=$data['no_hape']?>" class="form-control easyui-validatebox"  disabled="disabled" />
        	</td>
        </tr>
        <!-- <tr>
        	<td>Auto Anamnesa</td><td>:</td>
        	<td>
        		<?=$tempAuto?>
        	</td>
        </tr>	 -->
		<!-- tr style="display:none">
			<td>RT</td><td>:</td>			
				<td><input type="text" style="width:50px" name="reqRT" disabled="disabled" value="<?=$tempRT?>" />
				RW
				<input type="text" style="width:50px" name="reqRW" disabled="disabled" value="<?=$tempRW?>" />
			</td>
        </tr> -->
		<!-- <tr style="display:none">
			<td>Telepon</td><td>:</td>			
				<td><input type="text" style="width:100px" name="reqTelepon" disabled="disabled" value="<?=$tempTelepon?>" />
				Kode Pos
				<input type="text" style="width:100px" name="reqKodePos" disabled="disabled" value="<?=$tempKodePos?>" />
			</td>
        </tr> -->
		<?php /*?><tr>
			<td>Gambar</td><td>:</td>			
			<td>
                <? if(strlen($data) > 1){?>
                <img src="image_script.php?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=pegawai" width=150 height=200>
                <? }?>
			</td>
            <td>Gambar Setengah</td><td>:</td>			
			<td>
                <? if(strlen($data_other) > 1){?>
                <img src="image_script.php?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=pegawai_other" width=150 height=200>
                <? }?>
			</td>
        </tr><?php */?>
        <? 
		if($reqPegawaiId == ""){}
		else
		{
		?>
		<tr>           
			<td>Pangkat Terakhir</td><td>:</td>			
			<td><?=$data['golongan']?></td>
			<td>TMT Pangkat</td><td>:</td>			
			<td><?=$data['tmt_golongan']?></td>		
        </tr>
		<tr>           
			<td>Jabatan Terakhir</td><td>:</td>			
			<td><?=$data['jabatan']?></td>
			<td>TMT Jabatan</td><td>:</td>			
			<td>-</td>		
        </tr><tr>           
			<td>Pendidikan Terakhir</td><td>:</td>			
			<td><?=$data['pendidikan']?></td>
        </tr>
		<? 
		}
		?>
    </tr>
    </table>
</div>
</div>
</form>
</div>
</body>
</html>