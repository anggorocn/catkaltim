<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Training.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/functions/date.func.php");

/* create objects */
$pegawai= new Training();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode= httpFilterRequest("reqMode");
$reqRowMode= httpFilterGet("reqRowMode");
$reqId= httpFilterRequest("reqId");

/* VALIDATION */
$pegawai->selectByParams(array("A.TRAINING_ID" => $reqId));
//echo $pegawai->query;exit;
$pegawai->firstRow();
$tempId= $pegawai->getField("TRAINING_ID");
$tempTahun= $pegawai->getField("TAHUN");
$tempNama= $pegawai->getField("NAMA");

$tempJamEs2= $pegawai->getField("JAM_ES2");
$tempJamEs3= $pegawai->getField("JAM_ES3");
$tempJamEs4= $pegawai->getField("JAM_ES4");
$tempJamFu= $pegawai->getField("JAM_JFU");
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
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.form.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.linkbutton.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.draggable.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.resizable.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.panel.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.window.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.progressbar.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.messager.js"></script>      
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.tooltip.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.validatebox.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.combo.js"></script>
    
    <script type="text/javascript" src="../WEB/lib/easyui/kalender.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/klik_kanan.js"></script>
	<script type="text/javascript">
		<? include_once "../jslib/formHandler.php"; ?>
		var value_status="";
  		var mode=tempId="";
		
		$(function(){
			$('#ff').form({
				url:'../json-silat/master_traning_add_data.php',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					//alert(data);
					data = data.split("-");
					mode= data[2];
					tempId= data[0];
					$.messager.alert('Info', data[1], 'info');
					$('#rst_form').click();
					
					setTimeout(setReload, 250);
				}
			});
		});
		
		function setReload()
		{
			var reqTahun= "";
			parent.frames['menuFrame'].location.href = 'master_traning_menu.php?reqId='+tempId;
			parent.frames['mainFrame'].location.href = 'master_traning_add_data.php?reqId='+tempId;
			reqTahun= $("#reqTahun").val();
			top.frames['mainFullFrame'].location.href = 'master_traning.php?reqTahun='+reqTahun;
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
    
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<form id="ff" method="post" novalidate>
<div id="content" style="height:auto; margin-top:-4px; width:100%">

<div class="content" style="height:97%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
    <table class="table_list" cellspacing="1" width="100%">
    	<tr>
            <td colspan="3">
            <div id="header-tna-detil">Data <span>Training</span></div>
			</td>			
        </tr>
        <tr>
            <td style="width:300px">Tahun</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqTahun" id="reqTahun" style="width:50px" value="<?=$tempTahun?>" required />
			</td>
        </tr>
        <tr>           
            <td>Nama</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqNama" id="reqNama" style="width:300px" value="<?=$tempNama?>" class="easyui-validatebox" required />
			</td>
        </tr>
        <tr>           
            <td>Jam training minimal eselon 2</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqJamEs2" id="reqJamEs2" style="width:80px" value="<?=$tempJamEs2?>" />
			</td>
        </tr>
        <tr>           
            <td>Jam training minimal eselon 3</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqJamEs3" id="reqJamEs3" style="width:80px" value="<?=$tempJamEs3?>" />
			</td>
        </tr>
        <tr>           
            <td>Jam training minimal eselon 4</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqJamEs4" id="reqJamEs4" style="width:80px" value="<?=$tempJamEs4?>" />
			</td>
        </tr>
        <tr>           
            <td>Jam training minimal fungsional umum</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqJamFu" id="reqJamFu" style="width:80px" value="<?=$tempJamFu?>" />
			</td>
        </tr>
    </table>
    
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">
    <ul>
    	<input type="hidden" name="reqId" value="<?=$tempId?>">
    	<input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
        <input type="hidden" name="reqMode" value="<?=$reqMode?>">
        <?php /*?><input type="submit" value="Submit">
        <input type="reset" id="rst_form"><?php */?>
        <li><a href="#" onclick="$('#ff').submit();">SIMPAN</a></li>
        <?php /*?><li><a href="#" onclick="reloadMe()">BATAL</a></li><?php */?>
    </ul>
    </div>
</div>
</div>
</form>
</div>

<script>
$("#reqTahun,#reqJamEs2,#reqJamEs3,#reqJamEs4,#reqJamFu").keypress(function(e) {
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});
</script>
</body>
</html>