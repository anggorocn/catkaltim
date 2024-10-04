<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Kegiatan.php");
include_once("../WEB/classes/base-skp/KegiatanTambahan.php");
include_once("../WEB/classes/base-skp/KegiatanPersetujuan.php");

$kegiatan = new Kegiatan();
$kegiatan_tambahan = new KegiatanTambahan();
$kegiatan_persetujuan = new KegiatanPersetujuan();

$reqId = httpFilterGet("reqId");
$reqMode = httpFilterGet("reqMode");
$reqBulan = httpFilterGet("reqBulan");
$reqTahun = httpFilterGet("reqTahun");

if($reqBulan == "")
	$reqBulan = (int)date("m");

if($reqTahun == "")
	$reqTahun = (int)date("Y");

	

$index_main= 0;
$kegiatan->selectByParams(array("PEGAWAI_ID"=>$reqId, "BULAN" => $reqBulan, "TAHUN" => $reqTahun));

while($kegiatan->nextRow())
{
	$arrKegiatanMain[$index_main]["URUT"] = $kegiatan->getField("URUT");
	$arrKegiatanMain[$index_main]["NAMA"] = $kegiatan->getField("NAMA");
	$arrKegiatanMain[$index_main]["AK"] = $kegiatan->getField("AK");
	$arrKegiatanMain[$index_main]["KUANTITAS"] = $kegiatan->getField("KUANTITAS");
	$arrKegiatanMain[$index_main]["KUANTITAS_SATUAN"] = $kegiatan->getField("KUANTITAS_SATUAN");
	$arrKegiatanMain[$index_main]["KUALITAS"] = $kegiatan->getField("KUALITAS");
	$arrKegiatanMain[$index_main]["WAKTU"] = $kegiatan->getField("WAKTU");
	$arrKegiatanMain[$index_main]["WAKTU_SATUAN"] = $kegiatan->getField("WAKTU_SATUAN");
	$arrKegiatanMain[$index_main]["BIAYA"] = $kegiatan->getField("BIAYA");
	$index_main++;
}

$index_tambahan= 0;
$kegiatan_tambahan->selectByParams(array("PEGAWAI_ID"=>$reqId, "BULAN" => $reqBulan, "TAHUN" => $reqTahun));
while($kegiatan_tambahan->nextRow())
{
	$arrKegiatanTambahan[$index_tambahan]["NAMA"] = $kegiatan_tambahan->getField("NAMA");
	$arrKegiatanTambahan[$index_tambahan]["KUANTITAS"] = $kegiatan_tambahan->getField("KUANTITAS");
	$arrKegiatanTambahan[$index_tambahan]["KUANTITAS_SATUAN"] = $kegiatan_tambahan->getField("KUANTITAS_SATUAN");
	$index_tambahan++;
}

$kegiatan_persetujuan->selectByParams(array("PEGAWAI_ID"=>$reqId, "BULAN" => $reqBulan, "TAHUN" => $reqTahun));
$kegiatan_persetujuan->firstRow();
$tempStatusId = $kegiatan_persetujuan->getField("KEGIATAN_PERSETUJUAN_ID");
$tempStatus = $kegiatan_persetujuan->getField("STATUS");
$tempAlasan = $kegiatan_persetujuan->getField("ALASAN");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/entri_kegiatan.js"></script>
<script type="text/javascript" src="js/entri_kegiatan_tambahan.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
$(function(){
	$('#ff').form({
		url:'../json-skp/pelaporan_skp_add.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);
			$.messager.alert('Info', data, 'info');
			document.location.href = 'pelaporan_skp_add.php?reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>';
			top.frames['mainFrame'].location.reload();
		}
	});
			
	$("#reqBulan").change(function() { 
		document.location.href = "pelaporan_skp_add.php?reqBulan="+ $("#reqBulan").val()+"&reqTahun="+ $("#reqTahun").val();		 	 
	});
	
	$("#reqTahun").change(function() { 
		document.location.href = "pelaporan_skp_add.php?reqBulan="+ $("#reqBulan").val()+"&reqTahun="+ $("#reqTahun").val();		 	 
	});
	
});
</script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/jAlert-master/jAlert-v2.css">
<script src='../WEB/lib/jAlert-master/bootstrap.min.js'></script>
<script src='../WEB/lib/jAlert-master/jAlert-v2.js'></script>
<script type="text/javascript">

function cacatanRevisi()
{
    successAlert('<?=$tempAlasan?>');     			
}
</script>  
<style type="text/css" media="screen">
  label {
	/*font-size: 10px;
	font-weight: bold;
	text-transform: uppercase;
	margin-bottom: 3px;*/
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
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>  
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>    
	<fieldset>
    <legend>Inisialisasi</legend>
    <div id="data-detil">    
        <table>
            <tr>
                <td style="width:60px;">Periode</td>
                <td style="width:10px;">:</td>
                <td>
                    <select name="reqBulan" id="reqBulan">
                        <?
                        for($i=1; $i<=12; $i++)
                        {
                            $tempNama=getNameMonth($i);
                            $tempBulan=$i;
                        ?>
                            <option value="<?=$tempBulan?>" <? if($tempBulan == $reqBulan) echo 'selected'?>><?=$tempNama?></option>
                        <?
                        }
                        ?>
                    </select>
                    <select name="reqTahun" id="reqTahun">
                        <? 
                        for($i=date("Y")-2; $i < date("Y")+2; $i++)
                        {
                        ?>
                        <option value="<?=$i?>" <? if($i == $reqTahun) echo 'selected'?>><?=$i?></option>
                        <?
                        }
                        ?>
                    </select>
                </td>
                <?
                if($tempStatus == "R")
				{
				?>
                <td align="right">
                	<input type="button" value="Catatan Revisi" onClick="cacatanRevisi()">
                </td>
                <?
				}
				?>
            </tr>
        </table>
    </div>
    </fieldset>       
    <fieldset>
    <legend>Data Kegiatan</legend>
    <div id="data-tabel-noborder"> 
    <table class="example" id="dataTableRowDinamisMain">
    <!--<table class="example altrowstable" id="alternatecolor" >-->
        <thead class="altrowstable">
          <tr>
              <th style="width:4%">
                No
                <a style="cursor:pointer" title="Tambah Rincian" onclick="addRow()"><img src="../WEB/images/icn_add.gif" width="16" height="16" border="0" /></a>
              </th>
              <th style="width:500px">Nama</th>
              <th style="width:50px">AK</th> 	
              <th style="width:50px">Kuantitas</th>
              <th style="width:100px">Kuantitas Satuan</th>
              <th style="width:50px">Kualitas</th>
              <th style="width:50px">Waktu</th>
              <th style="width:100px">Waktu Satuan</th>
              <th style="width:100px">Biaya</th>
              <th style="width:5%">Aksi</th>
          </tr>
        </thead>
        <tbody class="example altrowstable" id="alternatecolor"> 
          <?
		  $i = 1;
          $unit = 0;
		  $harga = 0;
		  $jumlah = 0;		  
          for($checkbox_index=0;$checkbox_index<count($arrKegiatanMain);$checkbox_index++)
          {
          ?>          
              <tr id="node-<?=$i?>">
                  <td>
                  	<input type="text" name="reqNoUrut[]" id="reqNoUrut<?=$checkbox_index?>" style="width:30px;" class="easyui-validatebox" value="<?=$i?>" />
                  </td>
                  <td>
                    <input type="text" id="reqNama<?=$checkbox_index?>" name="reqNama[]" value="<?=$arrKegiatanMain[$checkbox_index]["NAMA"]?>" style="width:500px;" />  
                  </td>
                  <td>
                    <input type="text" id="reqAK<?=$checkbox_index?>" name="reqAK[]" class="easyui-validatebox" value="<?=$arrKegiatanMain[$checkbox_index]["AK"]?>" style="width:50px;" />   
                  </td>
                  <td>
                  	<input type="text" id="reqKuantitas<?=$checkbox_index?>" name="reqKuantitas[]" class="easyui-validatebox" value="<?=$arrKegiatanMain[$checkbox_index]["KUANTITAS"]?>" style="width:50px;" />
                  </td>
                  <td>
                  	<input id="reqKuantitasSatuan<?=$checkbox_index?>" class="easyui-combobox"  name="reqKuantitasSatuan[]" 
                        data-options="
                            url:'../json-masterdata/kuantitas_satuan_combo_json.php',
                            valueField:'text',
                            textField:'text',
                            panelHeight:'120'
                            " 
                        style="width:100px;" value="<?=$arrKegiatanMain[$checkbox_index]["KUANTITAS_SATUAN"]?>" />
                  </td>
                  <td>
                    <input type="text" name="reqKualitas[]" id="reqKualitas<?=$checkbox_index?>" class="easyui-validatebox" value="<?=$arrKegiatanMain[$checkbox_index]["KUALITAS"]?>" style="width:50px;" />
                  </td>
                  <td>
                    <input type="text" name="reqWaktu[]" id="reqWaktu<?=$checkbox_index?>" class="easyui-validatebox" value="<?=$arrKegiatanMain[$checkbox_index]["WAKTU"]?>" style="width:50px;" />
                  </td>
                  <td>
                  	<input type="text" id="reqWaktuSatuan<?=$checkbox_index?>" class="easyui-combobox"  name="reqWaktuSatuan[]" 
                        data-options="
                            url:'../json-masterdata/waktu_satuan_combo_json.php',
                            valueField:'text',
                            textField:'text',
                            panelHeight:'120'
                            " 
                        style="width:100px;" value="<?=$arrKegiatanMain[$checkbox_index]["WAKTU_SATUAN"]?>" />
                  </td>
                  <td>
                  	<input type="text" id="reqBiaya<?=$checkbox_index?>" name="reqBiaya[]" class="easyui-validatebox" style="width:100px; text-align:right;" OnFocus="FormatAngka('reqBiaya<?=$checkbox_index?>')" OnKeyUp="FormatUang('reqBiaya<?=$checkbox_index?>')" OnBlur="FormatUang('reqBiaya<?=$checkbox_index?>')" value="<?=numberToIna($arrKegiatanMain[$checkbox_index]["BIAYA"])?>" /> 
                  </td>
                  <td align="center">
                  <label>
                  <a style="cursor:pointer" onclick="deleteRowDrawTablePhp('dataTableRowDinamisMain', this)"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a>
                  </label>
                  </td>
              </tr>
		  <?		  
            $i++;
          }
		  ?>         
        </tbody>   
    </table> 
    </div>
    </fieldset>
    <fieldset>
    <legend>Data Kegiatan Tambahan</legend>
    <div id="data-tabel-noborder">     
    <table class="example" id="dataTableRowDinamisTambahan">
    <!--<table class="example altrowstable" id="alternatecolor" >-->
        <thead class="altrowstable">
          <tr>
              <th style="width:4%">
                No
                <a style="cursor:pointer" title="Tambah Rincian" onclick="addRowTambahan()"><img src="../WEB/images/icn_add.gif" width="16" height="16" border="0" /></a>
              </th>
              <th style="width:1100px">Nama</th>
              <th style="width:50px">Kuantitas</th>
              <th style="width:100px">Kuantitas Satuan</th>
              <th style="width:5%">Aksi</th>
          </tr>
        </thead>
        <tbody class="example altrowstable" id="alternatecolorTambahan"> 
          <?
		  $i = 1;
          $unit = 0;
		  $harga = 0;
		  $jumlah = 0;		  
          for($checkbox_index=0;$checkbox_index<count($arrKegiatanTambahan);$checkbox_index++)
          {
          ?>          
              <tr id="node-<?=$i?>">
                  <td>
                  	<input type="text" name="reqNoUrut[]" id="reqNoUrut<?=$checkbox_index?>" style="width:30px;" class="easyui-validatebox" value="<?=$i?>" />
                  </td>
                  <td>
                    <input type="text" id="reqNamaTambahan<?=$checkbox_index?>" name="reqNamaTambahan[]" value="<?=$arrKegiatanTambahan[$checkbox_index]["NAMA"]?>" style="width:1100px;" />  
                  </td>
                  <td>
                  	<input type="text" id="reqKuantitasTambahan<?=$checkbox_index?>" name="reqKuantitasTambahan[]" class="easyui-validatebox" value="<?=$arrKegiatanTambahan[$checkbox_index]["KUANTITAS"]?>" style="width:50px;" />
                  </td>
                  <td>
                  	<input id="reqKuantitasSatuanTambahan<?=$checkbox_index?>" class="easyui-combobox"  name="reqKuantitasSatuanTambahan[]" 
                        data-options="
                            url:'../json-masterdata/kuantitas_satuan_combo_json.php',
                            valueField:'text',
                            textField:'text',
                            panelHeight:'120'
                            " 
                        style="width:100px;" value="<?=$arrKegiatanTambahan[$checkbox_index]["KUANTITAS_SATUAN"]?>" />
                  </td>
                  <td align="center">
                  <label>
                  <a style="cursor:pointer" onclick="deleteRowDrawTablePhp('dataTableRowDinamisTambahan', this)"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a>
                  </label>
                  </td>
              </tr>
		  <?		  
            $i++;
          }
		  ?>         
        </tbody>   
    </table>    
    </div>
    </fieldset>
    <?
    if($tempStatus == "R")
		$button = "Revisi";
	elseif($tempStatus == "P")
		$pesan = "Formulir SKP telah di posting, anda tidak dapat mengubah isian SKP.";		
	elseif($tempStatus == "S")
		$pesan = "Formulir SKP telah di setujui, anda tidak dapat mengubah isian SKP.";		
	else
		$button = "Submit";
	
	if($tempStatus == "" || $tempStatus == "R")
	{
	?>
    
    <input type="hidden" name="reqStatusId" value="<?=$tempStatusId?>">
    <input type="hidden" name="reqStatus" value="<?=$tempStatus?>">
    <input type="submit" value="<?=$button?>">
    <input type="reset" id="rst_form">
    <?
	}
	?>
    <?=$pesan?>
    </form>
    </div>
	<script>
    $("input[id^='reqAK'], input[id^='reqKuantitas'], input[id^='reqKualitas'], input[id^='reqWaktu'], input[id^='reqKuantitasTambahan']").keypress(function(e) {
        if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
        {
        return false;
        }
    });
    </script>
    </div>
</div>
</body>
</html>