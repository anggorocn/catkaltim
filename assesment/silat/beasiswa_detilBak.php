<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Beasiswa.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");

/* create objects */
$beasiswa = new Beasiswa();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqRowId 	= httpFilterRequest("reqRowId");
$reqMode 			= httpFilterRequest("reqMode");
$reqPegawaiId 		= httpFilterRequest('reqPegawaiId');
$tempPegawaiId		= $reqPegawaiId;

if($reqMode == "delete")
{
	$beasiswa->setField('BEASISWA_ID', $reqRowId);
	
	if($beasiswa->delete())	$mode = 'hapus';
	else							$mode = 'error';
}

if($reqMode == "delete")
{
	echo '<script language="javascript">';
	echo "parent.frames['mainFrame'].location.href = 'beasiswa.php?reqPegawaiId=".$reqPegawaiId."&reqMode=".$mode."';";
	echo '</script>';
	
	echo '<script language="javascript">';
	echo "parent.frames['mainFrameDetil'].location.href = 'beasiswa_detil.php?reqPegawaiId=".$reqPegawaiId."';";
	echo '</script>';
}

if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view'){
$beasiswa->selectByParams(array('BEASISWA_ID'=>$reqRowId));
$beasiswa->firstRow();
$tempRowId= $beasiswa->getField('BEASISWA_ID');
$tempJenis = $beasiswa->getField("JENIS");
$tempUniversitasAsal = $beasiswa->getField("UNIVERSITAS_ASAL");
$tempJurusanAsal = $beasiswa->getField("JURUSAN_ASAL");
$tempAkreditasi = $beasiswa->getField("AKREDITASI");
$tempIpk = $beasiswa->getField("IPK");
$tempSertifikat = $beasiswa->getField("SERTIFIKAT_INGGRIS");
$tempPda = $beasiswa->getField("PDA");
$tempPfs = $beasiswa->getField("PFS");
$tempPascaSarjana = $beasiswa->getField("PASCA_SARJANA");
$tempUniversitas = $beasiswa->getField("UNIVERSITAS");
$tempJurusan = $beasiswa->getField("JURUSAN");
$tempOrganisasiDonor = $beasiswa->getField("ORGANISASI_DONOR");
$tempStatus = $beasiswa->getField("STATUS");
$tempNegara = $beasiswa->getField("NEGARA");
$tempTahun = $beasiswa->getField("TAHUN");
$tempTanggalMulai = datetimeToPage($beasiswa->getField("TANGGAL_MULAI"), "date");
$tempTanggalSelesai = datetimeToPage($beasiswa->getField("TANGGAL_SELESAI"), "date");
$tempJudul = $beasiswa->getField("JUDUL");
$tempNomor = $beasiswa->getField("NOMOR");
$tempKeterangan = $beasiswa->getField("KETERANGAN");
//$tempPegawaiId		= $beasiswa->getField('PEGAWAI_ID');
}

if($tempJenis == "")
	$tempJenis = 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/kalender.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/klik_kanan.js"></script>
	<script type="text/javascript">
		<? include_once "../jslib/formHandler.php"; ?>
		var value_status="";
  		var mode="";
		
		function setValue()
		{
			value_status= '<?=$tempJenis?>';
			setShow();
		}
		
		$(function(){
			$('#ff').form({
				url:'../json-silat/beasiswa_detil.php',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					//alert(data);
					data = data.split("-");
					mode= data[2];
					//$.messager.alert('Info', data[1], 'info');
					$('#rst_form').click();
					
					setTimeout(setReload, 250);
				}
			});
			
			$('#reqJenis').bind('change', function(ev) {		
				value_status= $('#reqJenis').val();
				setShow();
			});
			
		});
		
		function setReload()
		{
			parent.frames['mainFrame'].location.href = 'beasiswa.php?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=' + mode;
			parent.frames['mainFrameDetil'].location.href = 'beasiswa_detil.php?reqPegawaiId=<?=$reqPegawaiId?>';
		}
		
		function setShow()
	   	{
		  if(value_status == '1')
		  {
			  $('#setPriority').hide();
			  $('#reqPda').val('');
			  $('#reqPfs').val('');
		  }
		  else
		  {
			  $('#setPriority').show();
		  }
	  	}
		
	</script>
    <link href="../WEB/css/begron.css" rel="stylesheet" type="text/css">
    <link href="../WEB/css/admin.css" rel="stylesheet" type="text/css">
	<link href="../WEB/themes/main.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
    
    <link href="tabs.css" rel="stylesheet" type="text/css" />
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
    /* Specify the position and layering for the content that needs to appear in front of the background image. Must have a higher z-index than the background image. Also add some padding to compensate for removing the margin from the 'html' and 'body' tags. */
    #content {position:relative; z-index:1;}
    /* prepares the background image to full capacity of the viewing area */
    #bg {position:fixed; top:0; left:0; width:100%; height:100%;}
    /* places the content ontop of the background image */
    #content {position:relative; z-index:1;}
    </style>
    
</head>

<body onload="setValue();">
<div id="bg"><img src="images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<form id="ff" method="post" novalidate>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
	<div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">
    <ul>
    <?
	//if($userLogin->userLihatProses== 1){}else{
	?>
    	<? if($reqMode == ''){
			$read = 'readonly'; $disabled = 'disabled';
		?>
            <li><a href="beasiswa_detil.php?reqMode=tambah&reqPegawaiId=<?=$tempPegawaiId?>"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
        <? }
		elseif($reqMode == 'cancel' && $reqRowId == ''){
			$read = 'readonly'; $disabled = 'disabled';
		?>
            <li><a href="beasiswa_detil.php?reqMode=tambah&reqPegawaiId=<?=$tempPegawaiId?>"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
        <? }
		elseif($reqMode == 'view' || $reqMode == 'cancel'){
			$read = 'readonly'; $disabled = 'disabled';
		?>
        <?php /*?><?
		if(strlen($beasiswa->getField('FOTO_BLOB')) > 1){
        ?>
        	<li><a href="#" onclick="windowOpenerPopup(350,450,'Lihat Lampiran','download.php?reqMode=beasiswa&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>');" title="Lampiran" ><img src="images/down.png"> Lihat</a></li>
        <?
		}
        ?>
        <li><a href="lampiran_beasiswa.php?reqMode=view&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/drive.png" width="15" height="15"/> Upload</a></li>
        <li><a href="scanner_beasiswa.php?reqMode=view&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="images/icn_print.png" width="15" height="15"/> Scanner</a></li><?php */?>
        <li><a href="beasiswa_detil.php?reqMode=tambah&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
        <li><a href="beasiswa_detil.php?reqMode=edit&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
        <li><a href="javascript:confirmAction('?reqMode=delete&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>', '1')"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>        
        <? }
		elseif($reqMode == 'tambah' || $reqMode == 'edit'){
			$read = ''; $disabled = '';
		?>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
        <li><a href="#" onclick="$('#ff').submit();"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
			<? if($reqMode == 'edit'){?>
                <input type="hidden" name="reqMode" value="SubmitEdit">
            <? }else{?>
                <input type="hidden" name="reqMode" value="SubmitSimpan">
            <? }?>
        <li><a href="beasiswa_detil.php?reqMode=cancel&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
        <? }
		elseif($reqMode == 'SubmitEdit' || $reqMode == 'SubmitSimpan'){
			$read = ''; $disabled = '';
		?>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
        <li><a href="#" onclick="$('#ff').submit();"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
        <input type="hidden" name="reqMode" value="<?=$reqMode?>">
        <li><a href="beasiswa_detil.php?reqMode=cancel&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
        <? }
		?>
    <? //}?>
    </ul>
    </div>
<div class="content" style="height:90%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
	<input type="hidden" name="reqPegawaiId" value="<?=$tempPegawaiId?>">
    <input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
    <table class="table_list" cellspacing="1" width="100%">
    	<tr>           
            <td style="width:200px">Jenis</td>
            <td>:</td>
			<td>			
				<select name="reqJenis" id="reqJenis" <?=$disabled?>>
                	<option value="1" <? if($tempJenis == 1) echo "selected";?>>Dalam Negeri</option>
                    <option value="2" <? if($tempJenis == 2) echo "selected";?>>Luar Negeri</option>
                </select>
			</td>
            <td style="width:200px">Universitas Asal</td>
            <td>:</td>
			<td>
            	<input id="reqUniversitasAsal" name="reqUniversitasAsal" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=UNIVERSITAS_ASAL'
                "<?php */?>
                style="width:350px" <?=$disabled?> value="<?=$tempUniversitasAsal?>" 
                />
			</td>
        </tr>
        <tr>           
            <td>Jurusan Asal</td>
            <td>:</td>
			<td>
            	<input id="reqJurusanAsal" name="reqJurusanAsal" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=JURUSAN_ASAL'
                "<?php */?>
                style="width:200px" <?=$disabled?> value="<?=$tempJurusanAsal?>" 
                />
			</td>
            <td>Akreditasi Asal</td>
            <td>:</td>
			<td>
            	<input id="reqAkreditasi" name="reqAkreditasi" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=AKREDITASI'
                "<?php */?>
                style="width:50px" <?=$disabled?> value="<?=$tempAkreditasi?>" 
                />
			</td>
        </tr>
        <tr>           
            <td>IPK</td>
            <td>:</td>
			<td>			
				<input type="text" name="reqIpk" id="reqIpk" <?=$disabled?> style="width:50px" value="<?=$tempIpk?>" />
			</td>
            <td>Sertifikat Kemampuan Bahasa Inggris</td>
            <td>:</td>
			<td>
            	<select name="reqSertifikat" <?=$disabled?>>
                	<option value="1" <? if($tempSertifikat == "1") echo "selected";?>>TOEFL</option>
                    <option value="2" <? if($tempSertifikat == "2") echo "selected";?>>IELTS</option>
                </select>
			</td>			
        </tr>
        <tr id="setPriority" style="display:none">           
            <td>Priority development area (PDA) (untuk luar negeri)</td>
            <td>:</td>
			<td>
            	<input id="reqPda" name="reqPda" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=PDA'
                "<?php */?>
                style="width:250px" <?=$disabled?> value="<?=$tempPda?>" 
                />
			</td>
            <td>Priority field of study (PFS) (untuk luar negeri)</td>
            <td>:</td>
			<td>
            	<input id="reqPfs" name="reqPfs" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=PFS'
                "<?php */?>
                style="width:250px" <?=$disabled?> value="<?=$tempPfs?>" 
                />
			</td>
        </tr>
        <tr>           
            <td>Pasca Sarjana</td>
            <td>:</td>
			<td>			
				<select name="reqPascaSarjana" id="reqPascaSarjana" <?=$disabled?>>
                	<option value="1" <? if($tempPascaSarjana == 1) echo "selected";?>>S1</option>
                    <option value="2" <? if($tempPascaSarjana == 2) echo "selected";?>>S2</option>
                </select>
			</td>
            <td>Universitas</td>
            <td>:</td>
			<td>
            	<input id="reqUniversitas" name="reqUniversitas" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=UNIVERSITAS'
                "<?php */?>
                style="width:225px" <?=$disabled?> value="<?=$tempUniversitas?>" 
                />
                &nbsp;&nbsp;&nbsp; Tahun
                <input type="text" name="reqTahun" id="reqTahun" <?=$disabled?> style="width:50px" value="<?=$tempTahun?>" />
			</td>
        </tr>
        <tr>           
            <td>Jurusan</td>
            <td>:</td>
			<td>
            	<input id="reqJurusan" name="reqJurusan" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=JURUSAN'
                "<?php */?>
                style="width:200px" <?=$disabled?> value="<?=$tempJurusanAsal?>" 
                />
			</td>
            <td>Organisasi Donor</td>
            <td>:</td>
			<td>
            	<input id="reqOrganisasiDonor" name="reqOrganisasiDonor" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=ORGANISASI_DONOR'
                "<?php */?>
                style="width:80px" <?=$disabled?> value="<?=$tempOrganisasiDonor?>" 
                />
                &nbsp;&nbsp;Status
                <select name="reqStatus" <?=$disabled?>>
                	<option value="1" <? if($tempStatus == 1) echo "selected";?>>Pelamar</option>
                    <option value="2" <? if($tempStatus == 2) echo "selected";?>>Shortlisting(Luar Negeri)</option>
                    <option value="3" <? if($tempStatus == 3) echo "selected";?>>Lulus</option>
                    <option value="4" <? if($tempStatus == 4) echo "selected";?>>Tidak Lulus</option>
                    <option value="5" <? if($tempStatus == 5) echo "selected";?>>Belum Berangkat(Luar Negeri)</option>
                    <option value="6" <? if($tempStatus == 6) echo "selected";?>>Masa Pendidikan</option>
                    <option value="7" <? if($tempStatus == 7) echo "selected";?>>Telah Kembali(Luar Negeri)</option>
                </select>
			</td>
        </tr>
        <tr>           
            <td>Negara</td>
            <td>:</td>
			<td>
            	<input id="reqNegara" name="reqNegara" <?php /*?>class="easyui-combobox" data-options="filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },valueField: 'id', textField: 'text',
                url: '../json-silat/beasiswa_combo_cari_json.php?reqMode=NEGARA'
                "<?php */?>
                style="width:100px" <?=$disabled?> value="<?=$tempNegara?>" 
                />
			</td>
            <td>Tgl Mulai Pendidikan</td>
            <td>:</td>
			<td>
            	<input id="reqTanggalMulai" name="reqTanggalMulai" class="easyui-datebox" <?=$disabled?> style="width:100px" data-options="validType:'date'" value="<?=$tempTanggalMulai?>" />
                &nbsp;&nbsp;Tgl Selesai Pendidikan
                <input id="reqTanggalSelesai" name="reqTanggalSelesai" class="easyui-datebox" <?=$disabled?> style="width:100px" data-options="validType:'date'" value="<?=$tempTanggalSelesai?>" />
			</td>			
        </tr>
        <tr>           
            <td>Judul Tesis</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqJudul" id="reqJudul" <?=$disabled?> style="width:200px" value="<?=$tempJudul?>" />
			</td>
            <td>Nomor Ijasah</td>
            <td>:</td>
			<td>			
				<input type="text" name="reqNomor" id="reqNomor" <?=$disabled?> style="width:200px" value="<?=$tempNomor?>" />
			</td>			
        </tr>
        <tr>           
            <td>Keterangan</td>
            <td>:</td>
			<td colspan="4">
            	<textarea rows="3" cols="100" name="reqKeterangan" id="reqKeterangan" <?=$disabled?>><?=$tempKeterangan?></textarea>
			</td>
        </tr>
    </table>
</div>
</div>
</form>

<script>
$("#reqTahun").keypress(function(e) {
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});
</script>
</body>
</html>