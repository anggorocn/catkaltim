<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/functions/date.func.php");
ini_set("memory_limit","100M");
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
		url:'../json-ikk/import_pegawai_eksternal.php',
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
    
<body>
	<div id="page_effect">
		<form id="ff" method="post" novalidate enctype="multipart/form-data">
	    <div id="header-tna-detil">Eksport <span>Pegawai Eksternal</span></div>
	    <br>
	    <br>
			<div id="content" style="height:auto; margin-top:-4px; width:100%">
				<h2><center>Eksport Data Pegawai Eksternal Dari .XLS</center></h2>
				<h4><center>Unduh Template</center></h4>
				<center>
					<a style="background-color:forestgreen;color: white;padding: 12px 28px;border-radius: 10px;" href="../template/ikk/eksport_pegawai_eksternal.xlsx" target="_blank"><i class="fa fa-file-excel-o"></i>Template Excel</a>
				</center>
				<br>
				<br>
				<div style="margin:30px;padding: 20px; border-style: dashed; ">
					<h4><center>Upload Data</center></h4>
					<center><input type="file" class="easyui-validatebox" name="reqLinkFile" id="reqLinkFile" accept=".xlsx" style="border: solid black 1px;padding: 10px;border-radius: 10px;"></center>
					<h4><center>Pastikan Data Telah Sesuai Dengan Template Yang Diberikan</center></h4>
			    <center>
			    	<input style="background-color:forestgreen;color: white;padding: 12px 28px;border-radius: 10px;" type="submit" name="" value="Upload" />
			    </center>
				</div>
			</div>
		</form>
	</div>
</body>
