<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/RiwayatSkp.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");

/* create objects */
$set = new RiwayatSkp();

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
	$set->setField('RIWAYAT_SKP_ID', $reqRowId);
	
	if($set->delete())	$mode = 'hapus';
	else							$mode = 'error';
}

if($reqMode == "delete")
{
	echo '<script language="javascript">';
	echo "parent.frames['mainFrame'].location.href = 'skp.php?reqPegawaiId=".$reqPegawaiId."&reqMode=".$mode."';";
	echo '</script>';
	
	echo '<script language="javascript">';
	echo "parent.frames['mainFrameDetil'].location.href = 'skp_detil.php?reqPegawaiId=".$reqPegawaiId."';";
	echo '</script>';
}

if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view'){
$set->selectByParamsMonitoring(array('A.RIWAYAT_SKP_ID'=>$reqRowId));
// echo $set->query;exit();
$set->firstRow();
$tempRowId= $set->getField('RIWAYAT_SKP_ID');
$tempNilaiSkp= $set->getField('NILAI_SKP');
$tempTahun= $set->getField('SKP_TAHUN');
}

if($tempJenis == "")
	$tempJenis = 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
	<style>
		/* UNTUK TABLE GRADIENT STYLE*/
		.gradient-style th {
		font-size: 12px;
		font-weight:400;
		background:#b9c9fe url(images/gradhead.png) repeat-x;
		border-top:2px solid #d3ddff;
		border-bottom:1px solid #fff;
		color:#039;
		padding:8px;
		}
		
		.gradient-style td {
		font-size: 12px;
		border-bottom:1px solid #fff;
		color:#669;
		border-top:1px solid #fff;
		background:#e8edff url(images/gradback.png) repeat-x;
		padding:8px;
		}
		
		.gradient-style tfoot tr td {
		background:#e8edff;
		font-size: 14px;
		color:#99c;
		}
		
		.gradient-style tbody tr:hover td {
		background:#d0dafd url(images/gradhover.png) repeat-x;
		color:#339;
		}
		
		.gradient-style {
		font-family: 'Open SansRegular';
		font-size: 14px;
		width:480px;
		text-align:left;
		border-collapse:collapse;
		margin:0px 0px 0px 10px;
		}
	</style>
    
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
			/* prevent horizontal scrollbar */
			font-size:11px;
			overflow-x: hidden;
		}
		/* IE 6 doesn't support max-height
		 * we use height instead, but this forces the menu to always be this tall
		 */
		* html .ui-autocomplete {
			height: 200px;
		}
	</style>
    
    <!-- AUTO KOMPLIT -->
    <script type="text/javascript" src="../WEB/lib/easyui/easyloader.js"></script>   
  <!--   <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.form.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.linkbutton.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.draggable.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.resizable.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.panel.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.window.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.progressbar.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.messager.js"></script>      
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.tooltip.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.validatebox.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.combo.js"></script> -->
    
    <!-- <script type="text/javascript" src="../WEB/lib/easyui/kalender.js"></script> -->
    <!-- <script type="text/javascript" src="../WEB/lib/easyui/klik_kanan.js"></script> -->
	<script type="text/javascript">
		<? include_once "../jslib/formHandler.php"; ?>
		var value_status="";
  		var mode="";
		
		$(function(){
			$('#ff').form({
				url:'../json-ikk/skp_detil.php',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					// console.log(data);return false;
					data = data.split("-");
					$.messager.alert('Info', data[1], 'info');
					reqId= data[0];
					mode= data[2];
					setTimeout(setReload, 250);
				}
			});
			
			
		});
		
		function setReload()
		{
			parent.frames['mainFrame'].location.href = 'skp.php?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=' + mode;
			parent.frames['mainFrameDetil'].location.href = 'skp_detil.php?reqPegawaiId=<?=$reqPegawaiId?>';
		}
						
	</script>
    <!-- <link href="../WEB/css/begron.css" rel="stylesheet" type="text/css">
    <link href="../WEB/css/admin.css" rel="stylesheet" type="text/css">
	<link href="../WEB/themes/main.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
    
    <!-- <link href="tabs.css" rel="stylesheet" type="text/css" /> -->
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

<body>
<!-- <div id="bg"><img src="images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div> -->
<form id="ff" method="post" novalidate>
<div id="content" style="height:auto; width:100%">
	<div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">
    <ul>
    <?
	//if($userLogin->userLihatProses== 1){}else{
	?>
    	<? if($reqMode == ''){
			$read = 'readonly'; $disabled = 'disabled';
		?>
            <li><a href="skp_detil.php?reqMode=tambah&reqPegawaiId=<?=$tempPegawaiId?>"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
        <? }
		elseif($reqMode == 'cancel' && $reqRowId == ''){
			$read = 'readonly'; $disabled = 'disabled';
		?>
            <li><a href="skp_detil.php?reqMode=tambah&reqPegawaiId=<?=$tempPegawaiId?>"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
            <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
        <? }
		elseif($reqMode == 'view' || $reqMode == 'cancel'){
			$read = 'readonly'; $disabled = 'disabled';
		?>
        <li><a href="skp_detil.php?reqMode=tambah&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
        <li><a href="skp_detil.php?reqMode=edit&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
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
        <li><a href="skp_detil.php?reqMode=cancel&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
        <? }
		elseif($reqMode == 'SubmitEdit' || $reqMode == 'SubmitSimpan'){
			$read = ''; $disabled = '';
		?>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
        <li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
        <li><a href="#" onclick="$('#ff').submit();"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
        <input type="hidden" name="reqMode" value="<?=$reqMode?>">
        <li><a href="skp_detil.php?reqMode=cancel&reqPegawaiId=<?=$tempPegawaiId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
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
            <td style="width:100px">Nilai SKP</td>
            <td>:</td>
			<td>
				<input <?=$disabled?> type="text" style="width:4%" id="reqNilaiSkp" name="reqNilaiSkp" value="<?=$tempNilaiSkp?>" class="easyui-validatebox" title="Nilai skp harus diisi" />
			</td>
        </tr>
        <tr>
        	<td>Tahun</td>
            <td>:</td>
			<td>
				<input <?=$disabled?> type="text" style="width:5%" id="reqTahun" name="reqTahun" value="<?=$tempTahun?>" class="easyui-validatebox" title="Tahun harus diisi" />
			</td>
        </tr>
    </table>
</div>
</div>
</form>

<script>
$('#reqNilaiSkp').bind('keyup paste', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
});

$('#reqTahun').bind('keyup paste', function(){
	this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
</body>
</html>