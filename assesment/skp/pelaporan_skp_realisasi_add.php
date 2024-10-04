<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Kegiatan.php");
include_once("../WEB/classes/base-skp/KegiatanTambahan.php");

$kegiatan = new Kegiatan();
$kegiatan_tambahan = new KegiatanTambahan();

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
	$arrKegiatanMain[$index_main]["KUANTITAS_REALISASI"] = $kegiatan->getField("KUANTITAS_REALISASI");
	$arrKegiatanMain[$index_main]["KUANTITAS_SATUAN"] = $kegiatan->getField("KUANTITAS_SATUAN");
	$arrKegiatanMain[$index_main]["KUALITAS"] = $kegiatan->getField("KUALITAS");
	$arrKegiatanMain[$index_main]["KUALITAS_REALISASI"] = $kegiatan->getField("KUALITAS_REALISASI");
	$arrKegiatanMain[$index_main]["WAKTU"] = $kegiatan->getField("WAKTU");
	$arrKegiatanMain[$index_main]["WAKTU_REALISASI"] = $kegiatan->getField("WAKTU_REALISASI");
	$arrKegiatanMain[$index_main]["WAKTU_SATUAN"] = $kegiatan->getField("WAKTU_SATUAN");
	$arrKegiatanMain[$index_main]["BIAYA"] = $kegiatan->getField("BIAYA");
	$arrKegiatanMain[$index_main]["BIAYA_REALISASI"] = $kegiatan->getField("BIAYA_REALISASI");
	$arrKegiatanMain[$index_main]["KEGIATAN_ID"] = $kegiatan->getField("KEGIATAN_ID");
	$index_main++;
}

$index_tambahan= 0;
$kegiatan_tambahan->selectByParams(array("PEGAWAI_ID"=>$reqId, "BULAN" => $reqBulan, "TAHUN" => $reqTahun));
while($kegiatan_tambahan->nextRow())
{
	$arrKegiatanTambahan[$index_tambahan]["NAMA"] = $kegiatan_tambahan->getField("NAMA");
	$arrKegiatanTambahan[$index_tambahan]["KUANTITAS"] = $kegiatan_tambahan->getField("KUANTITAS");
	$arrKegiatanTambahan[$index_tambahan]["KUANTITAS_REALISASI"] = $kegiatan_tambahan->getField("KUANTITAS_REALISASI");
	$arrKegiatanTambahan[$index_tambahan]["KUANTITAS_SATUAN"] = $kegiatan_tambahan->getField("KUANTITAS_SATUAN");
	$arrKegiatanTambahan[$index_tambahan]["KEGIATAN_TAMBAHAN_ID"] = $kegiatan_tambahan->getField("KEGIATAN_TAMBAHAN_ID");
	$index_tambahan++;
}

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

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
$(function(){
	$('#ff').form({
		url:'../json-skp/pelaporan_skp_realisasi_add.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);
			$.messager.alert('Info', data, 'info');
			document.location.href = 'pelaporan_skp_realisasi_add.php?reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>';
			top.frames['mainFrame'].location.reload();
		}
	});
		
	function validasiKuantitas(id, jumlah)
	{
		entrian = parseInt($("#"+id).val());
		jumlah = parseInt(jumlah);
		if(entrian > jumlah)
		{
			alert("Pencapaian kuantitas melebihi target.");
			$("#"+id).val(jumlah);
		}
	}
	
});
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
    <legend>Periode Penilaian</legend>
    <div id="data-detil">      
     <table>
        <tr>
        	<td style="width:70px;">Periode</td>
            <td style="width:20px;">:</td>
            <td>
            	<?=getNameMonth($reqBulan)?>
            	<input type="hidden" name="reqBulan" id="reqBulan" value="<?=$reqBulan?>">
            	<?=$reqTahun?>
            	<input type="hidden" name="reqTahun" id="reqTahun" value="<?=$reqTahun?>">
            </td>
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
              <th style="width:4%" rowspan="2">
                No
              </th>
              <th style="width:500px" rowspan="2">Nama</th>
              <th style="width:50px" rowspan="2">AK</th> 	
              <th style="width:150px" colspan="3">Kuantitas</th>
              <!-- <th style="width:50px">Kualitas</th> -->
              <th style="width:150px" colspan="3">Waktu</th>
              <th style="width:100px" colspan="2">Biaya</th>
          </tr>
          <tr>
              <th style="width:50px">T</th> 	
              <th style="width:50px">R</th> 	
              <th style="width:50px">SATUAN</th> 
              <th style="width:50px">T</th> 	
              <th style="width:50px">R</th> 	
              <th style="width:50px">SATUAN</th> 
              <th style="width:50px">T</th> 	
              <th style="width:50px">R</th> 	
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
                  	<?=$i?>
                  	<input type="hidden" name="reqNoUrut[]" id="reqNoUrut<?=$checkbox_index?>" style="width:30px;" class="easyui-validatebox" value="<?=$i?>" readonly />
                  	<input type="hidden" name="reqKegiatanId[]" id="reqKegiatanId<?=$checkbox_index?>" class="easyui-validatebox" value="<?=$arrKegiatanMain[$checkbox_index]["KEGIATAN_ID"]?>" />
                  </td>
                  <td>
                    <?=$arrKegiatanMain[$checkbox_index]["NAMA"]?>
                  </td>
                  <td>
                    <?=$arrKegiatanMain[$checkbox_index]["AK"]?>
                  </td>
                  <td>
                    <?=$arrKegiatanMain[$checkbox_index]["KUANTITAS"]?>
                  </td>
                  <td>
                  	<input type="text" id="reqKuantitas<?=$checkbox_index?>" name="reqKuantitas[]" class="easyui-validatebox" value="<?=$arrKegiatanMain[$checkbox_index]["KUANTITAS_REALISASI"]?>" style="width:30px;" OnKeyUp="validasiKuantitas('reqKuantitas<?=$checkbox_index?>', '<?=$arrKegiatanMain[$checkbox_index]["KUANTITAS"]?>')" />
                  </td>
                  <td>
                    <?=$arrKegiatanMain[$checkbox_index]["KUANTITAS_SATUAN"]?>
                  </td>
                  <!--<td>
                    <input type="text" name="reqKualitas[]" id="reqKualitas<?=$checkbox_index?>" class="easyui-validatebox" value="<?=$arrKegiatanMain[$checkbox_index]["KUALITAS_REALISASI"]?>" style="width:30px;" /> %
                  </td> -->
                  <td>
                    <?=$arrKegiatanMain[$checkbox_index]["WAKTU"]?>
                  </td>
                  <td>
                    <input type="text" name="reqWaktu[]" id="reqWaktu<?=$checkbox_index?>" class="easyui-validatebox" value="<?=$arrKegiatanMain[$checkbox_index]["WAKTU_REALISASI"]?>" style="width:30px;" />
                  </td>
                  <td>
                    <?=$arrKegiatanMain[$checkbox_index]["WAKTU_SATUAN"]?>
                  </td>
                  <td>
                    <?=numberToIna($arrKegiatanMain[$checkbox_index]["BIAYA"])?>
                  </td>
                  <td>
                  	<input type="text" id="reqBiaya<?=$checkbox_index?>" name="reqBiaya[]" class="easyui-validatebox" style="width:100px; text-align:right;" OnFocus="FormatAngka('reqBiaya<?=$checkbox_index?>')" 
OnKeyUp="FormatUang('reqBiaya<?=$checkbox_index?>')" OnBlur="FormatUang('reqBiaya<?=$checkbox_index?>')" value="<?=numberToIna($arrKegiatanMain[$checkbox_index]["BIAYA_REALISASI"])?>" /> 
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
              <th style="width:4%" rowspan="2">
                No
              </th>
              <th style="width:1050px" rowspan="2">Nama</th>
              <th style="width:150px" colspan="3">Kuantitas</th>
          </tr>
          <tr>
              <th style="width:50px">T</th>
              <th style="width:50px">R</th>
              <th style="width:50px">SATUAN</th>          
          </tr>
        </thead>
        <tbody class="example altrowstable" id="alternatecolor"> 
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
                  	<?=$i?>
                  	<input type="hidden" name="reqNoUrut[]" id="reqNoUrut<?=$checkbox_index?>" style="width:30px;" class="easyui-validatebox" value="<?=$i?>" />
                    <input type="hidden" name="reqKegiatanTambahanId[]" id="reqKegiatanTambahanId<?=$checkbox_index?>" class="easyui-validatebox" value="<?=$arrKegiatanTambahan[$checkbox_index]["KEGIATAN_TAMBAHAN_ID"]?>" />
                  </td>
                  <td>
                    <?=$arrKegiatanTambahan[$checkbox_index]["NAMA"]?>
                  </td>
                  <td>
                    <?=$arrKegiatanTambahan[$checkbox_index]["KUANTITAS"]?>
                  </td>
                  <td>
                  	<input type="text" id="reqKuantitasTambahan<?=$checkbox_index?>" name="reqKuantitasTambahan[]" class="easyui-validatebox" value="<?=$arrKegiatanTambahan[$checkbox_index]["KUANTITAS_REALISASI"]?>" style="width:30px;" />
                  	
                  </td>
                  <td>
                    <?=$arrKegiatanTambahan[$checkbox_index]["KUANTITAS_SATUAN"]?>
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
    <input type="submit" value="Submit">
    <input type="reset" id="rst_form">
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