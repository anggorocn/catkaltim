<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-simpeg/Pegawai.php");
include_once("../WEB/classes/base-simpeg/Pangkat.php");
include_once("../WEB/classes/base-simpeg/Eselon.php");
ini_set("memory_limit","100M");

/* create objects */
$pegawai= new Pegawai();
$eselon= new Eselon();
$pangkat= new Pangkat();
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
//echo $pegawai->query;exit;

$tempNIP1				= $pegawai->getField('NIP_LAMA');
$tempNIP2				= $pegawai->getField('NIP_BARU');
$tempNama				= $pegawai->getField('NAMA');
$tempTempatLahir		= $pegawai->getField('TEMPAT_LAHIR');
$tempTanggalLahir		= dateToPageCheck($pegawai->getField('TGL_LAHIR'));
$tempJenisKelamin		= $pegawai->getField('JENIS_KELAMIN');
$tempStatusPernikahan	= $pegawai->getField('STATUS_KAWIN');

$tempPangkatId			= $pegawai->getField('LAST_PANGKAT_ID');
$tempPangkatTerakhir	= $pegawai->getField('NAMA_GOL');
$tempTMTPangkat			= dateToPageCheck($pegawai->getField('TMT_GOL_AKHIR'));
$tempEselonId			= $pegawai->getField('LAST_ESELON_ID');

$tempJabatanTerkahir	= $pegawai->getField('LAST_JABATAN');
$tempTMTJabatan			= dateToPageCheck($pegawai->getField('LAST_TMT_JABATAN'));
$tempPendidikanTerkahir	= $pegawai->getField('PENDIDIKAN_NAMA');

$tempTmtCpns			= dateToPageCheck($pegawai->getField('TMT_CPNS'));
$tempTmtPns				= dateToPageCheck($pegawai->getField('TMT_PNS'));
$tempMasaJabTahun		= $pegawai->getField('MASA_JAB_TAHUN');
$tempMasaJabBulan		= $pegawai->getField('MASA_JAB_BULAN');
$tempMasaKerjaTahun		= $pegawai->getField('MASA_KERJA_TAHUN');
$tempMasaKerjaBulan		= $pegawai->getField('MASA_KERJA_BULAN');
$tempTipePegawaiId		= $pegawai->getField('TIPE_PEGAWAI_ID');
$tempStatusPegawaiId	= $pegawai->getField('STATUS_PEGAWAI_ID');
$tempLastDikJenjang		= $pegawai->getField('LAST_DIK_JENJANG');
$tempLastDikTahun		= $pegawai->getField('LAST_DIK_TAHUN');
$tempLastDikJurusan		= $pegawai->getField('LAST_DIK_JURUSAN');
$tempAlamat				= $pegawai->getField('ALAMAT');
$tempAgama				= $pegawai->getField('AGAMA');

$tempSatuanKerja		= $pegawai->getField('SATKER_ID');
$tempSatuanKerjaNama	= $pegawai->getField('SATKER');

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

if($reqPegawaiId==""){$reqMode="insert";}else{$reqMode="update";}

$pangkat->selectByParams();
$eselon->selectByParams();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css"><script language="JavaScript" src="../jslib/displayElement.js"></script>
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>

<script type="text/javascript" src="jquery-1.4.2.min.js"></script>

<script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="validate/jquery-ui.min.js"></script> 
<script type="text/javascript" src="validate/jquery.validate.js"></script>
<link type="text/css" href="validate/jquery-ui.datepickerValidate.css" rel="stylesheet" />

<script type="text/javascript">	
	var tempNRP='';
		
	$(function(){
		$('#ff').form({
			url:'../json-simpeg/identitas_edit.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// alert(data);return false;
				data = data.split("-");
				$.messager.alert('Info', data[1], 'info');
				reqId= data[0];
				$('#rst_form').click();
				
				top.frames['mainFrame'].location.reload();
				parent.frames['menuFrame'].location.href = 'pegawai_menu_edit.php?reqPegawaiId='+reqId;
				document.location.href = 'identitas_edit.php?reqPegawaiId='+reqId;
			}
		});
		
	});
</script>

<script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
<link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>


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

<link href="styles.css" rel="stylesheet" type="text/css" />
<?php /*?><script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script><?php */?>
</head>
<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
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
            <td width="20%">NIP Lama</td><td width="2%">:</td>
			<td>
				<input type="text" style="width:100px" name="reqNIP1"  <? if($reqPegawaiId == "") { ?> class="reqNIP1" <? } ?> id="reqNIP1" value="<?=$tempNIP1?>" /> &nbsp;NIP Baru :
				<input type="text" style="width:150px" name="reqNIP2"  <? if($reqPegawaiId == "") { ?> class="reqNIP2 required"  title="NIP harus diisi" <? } ?> id="reqNIP2" value="<?=$tempNIP2?>" />
			</td>	
			<td width="10%">Satuan Kerja</td><td width="2%">:</td>
			<td width="30%">
            	<input id="reqSatuanKerja" class="easyui-combotree"  required="true" name="reqSatuanKerja" data-options="panelHeight:'200',url:'../json-silat/satuan_kerja_combo_json.php', method: 'get', valueField:'id',  textField:'text'" style="width:300px;" value="<?=$tempSatuanKerja?>" />
			</td>			
        </tr>
		<tr>           
            <td>Nama</td><td>:</td>
			<td>
				<input type="text" style="width:318px" name="reqNama" value="<?=$tempNama?>" class="easyui-validatebox" title="Nama harus diisi"  />
			</td>
        </tr>
		<tr style="display:none">
        	<td width="5%">Status Pegawai</td><td width="2%">:</td>
			<td>
				<input type="text" style="width:100px" name="reqStatusPegawaiId" class="easyui-validatebox" value="<?=$tempStatusPegawaiId?>"  />
			</td>	
		</tr>
		<tr>      
			<td>Tempat Lahir</td><td>:</td>
				<td><input type="text" style="width:100px" name="reqTempatLahir" class="easyui-validatebox" value="<?=$tempTempatLahir?>"  />
				Tanggal Lahir
				<input type="text" style="width:100px" name="reqTanggalLahir" id="reqTanggalLahir"  maxlength="10" class="easyui-datebox" value="<?=$tempTanggalLahir?>" />
			</td>
        </tr>
		<tr>           
			<td width="5%">Jenis Kelamin</td><td width="2%">:</td>
			<td>
				<select name = "reqJenisKelamin"  >
					<option value="L" <? if($tempJenisKelamin == 'L') echo 'selected';?>>L</option>
					<option value="P" <? if($tempJenisKelamin == 'P') echo 'selected';?>>P</option>
				</select>
			</td>
        </tr>
		<tr style="display:none">
			<td width="5%">Status Pernikahan</td><td width="2%">:</td>
			<td>
				<select name="reqStatusPernikahan"  >
					<option value="1" <? if($tempStatusPernikahan == "1") echo 'selected'?>>Belum Kawin</option>
					<option value="2" <? if($tempStatusPernikahan == "2") echo 'selected'?>>Kawin</option>
					<option value="3" <? if($tempStatusPernikahan == "3") echo 'selected'?>>Janda</option>
					<option value="4" <? if($tempStatusPernikahan == "4") echo 'selected'?>>Duda</option>
				</select>
			</td>
        </tr>
		<tr>           
			<td valign="top">Alamat</td><td valign="top">:</td>			
				<td>
                <textarea name="reqAlamat" cols="35"><?=$tempAlamat?></textarea>
			</td>
        </tr>	
		<tr style="display:none">
			<td>RT</td><td>:</td>			
				<td><input type="text" style="width:50px" name="reqRT"  value="<?=$tempRT?>" />
				RW
				<input type="text" style="width:50px" name="reqRW"  value="<?=$tempRW?>" />
			</td>
        </tr>
		<tr style="display:none">
			<td>Telepon</td><td>:</td>			
				<td><input type="text" style="width:100px" name="reqTelepon"  value="<?=$tempTelepon?>" />
				Kode Pos
				<input type="text" style="width:100px" name="reqKodePos"  value="<?=$tempKodePos?>" />
			</td>
        </tr>
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
			<td>Pangkat Terakhir</td>
            <td>:</td>			
			<td>
            	<input type="hidden" name="reqTMTPangkat" value="<?=$tempTMTPangkat?>">
            	<input type="hidden" name="reqJabatanTerkahir" value="<?=$tempJabatanTerkahir?>">
            	<input type="hidden" name="reqTMTJabatan" value="<?=$tempTMTJabatan?>">
            	<input type="hidden" name="reqLastDikJenjang" value="<?=$tempLastDikJenjang?>">
            	<input type="hidden" name="reqLastDikTahun" value="<?=$tempLastDikTahun?>">
            	<input type="hidden" name="reqLastDikJurusan" value="<?=$tempLastDikJurusan?>">
            	<input type="hidden" name="reqTipePegawaiId" value="<?=$tempTipePegawaiId?>">
            	<input type="hidden" name="reqAgama" value="<?=$tempAgama?>">
            	<input type="hidden" name="reqMasaJabBulan" value="<?=$tempMasaJabBulan?>">
            	<input type="hidden" name="reqMasaJabTahun" value="<?=$tempMasaJabTahun?>">
            	<input type="hidden" name="reqMasaKerjaBulan" value="<?=$tempMasaKerjaBulan?>">
            	<input type="hidden" name="reqMasaKerjaTahun" value="<?=$tempMasaKerjaTahun?>">
            	<input type="hidden" name="reqTmtCpns" value="<?=$tempTmtCpns?>">
            	<input type="hidden" name="reqTmtPns" value="<?=$tempTmtPns?>">
            	
                <select name="reqPangkatId" id="reqPangkatId">
                	<option value=""></option>
                	<?
					while($pangkat->nextRow())
					{
                    ?>
                	<option value="<?=$pangkat->getField("PANGKAT_ID")?>" <? if($pangkat->getField("PANGKAT_ID")==$tempPangkatId) echo 'selected'?>><?=$pangkat->getField("KODE")?></option>
                    <?
					}
                    ?>
                </select>
            </td>
			<td>TMT Pangkat</td>
            <td>:</td>			
			<td>
				<input type="text" style="width:100px" name="reqTMTPangkat" id="reqTMTPangkat"  maxlength="10" class="easyui-datebox" value="<?=$tempTMTPangkat?>" />
            </td>		
        </tr>
		<tr>           
			<td valign="top">Jabatan Terakhir</td>
            <td valign="top">:</td>			
			<td>
                <textarea name="reqJabatanTerkahir" cols="35"><?=$tempJabatanTerkahir?></textarea>
            </td>
			<td valign="top">TMT Jabatan</td>
            <td valign="top">:</td>			
			<td valign="top">
				<input type="text" style="width:100px" name="reqTMTJabatan" id="reqTMTJabatan"  maxlength="10" class="easyui-datebox" value="<?=$tempTMTJabatan?>" />
            </td>		
        </tr>
        <tr>           
			<td>Eselon</td>
            <td>:</td>			
			<td>
            	<select name="reqEselonId" id="reqEselonId">
                	<option value=""></option>
                	<?
					while($eselon->nextRow())
					{
                    ?>
                	<option value="<?=$eselon->getField("ESELON_ID")?>" <? if($eselon->getField("ESELON_ID")==$tempEselonId) echo 'selected'?>><?=$eselon->getField("NAMA")?></option>
                    <?
					}
                    ?>
                </select>
            </td>
        </tr>
        <?php /*?><tr>           
			<td>Pendidikan Terakhir</td>
            <td>:</td>			
			<td><?=$tempPendidikanTerkahir?></td>
        </tr><?php */?>
		<? 
		}
		?>
    </tr>
    <tr>
        <td>
            <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
            <input type="hidden" name="reqMode" value="<?=$reqMode?>">
            <input type="submit" name="" value="Simpan" /> 
            <input type="reset" name="" value="Reset" />
        </td>
    </tr> 
    </table>
</div>
</div>
</form>
</div>
</body>
</html>