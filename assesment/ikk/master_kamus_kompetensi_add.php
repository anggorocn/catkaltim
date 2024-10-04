<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

$reqId = httpFilterGet("reqId");

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

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">
$(function(){
	$('#ff').form({
		url:'../json-admin/agenda_add.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
		}
	});
	
});
</script>
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
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>  
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
   <div id="content" style="height:auto; margin-top:-4px; width:100%">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
    	<table class="table_list" cellspacing="1" width="100%">
        	<tr>
                <td colspan="6">
                <div id="header-tna-detil">Kamus Kompetensi</div>	                    
                </td>			
            </tr>
            <tr>
                <td width="20%">Jenis Kompetensi</td>
                <td width="2%">:</td>
                <td>
                    <select name="reqJenisKompetensi" id="reqJenisKompetensi">
                    	<option value="1">Kompetensi Inti JPT</option>
                    	<option value="2">Kompetensi Manajerial</option>
                    	<option value="3">Kompetensi Teknis</option>
                    	<option value="4">Kompetensi Sosial Kultural</option>
                    </select>
                </td>
            </tr>  
       		<tr>
                <td>Kompetensi</td>
                <td>:</td>
                <td>
                    <input name="reqNama" class="easyui-validatebox" required title="Nama harus diisi" style="width:150px" type="text" value="<?=$tempNama?>" />
                </td>
            </tr>   
       		<tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>
                    <input name="reqNama" class="easyui-validatebox" required title="Nama harus diisi" style="width:150px" type="text" value="<?=$tempNama?>" />
                </td>
            </tr>  
        	<tr>
            	<td>&nbsp;</td>
            	<td>&nbsp;</td>
            	<td>
                    <input type="hidden" id="reqId" name="reqId" value="<?=$reqId?>">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                	<input type="submit" name="" value="Simpan" /> 
                    <input type="reset" name="" value="Reset" />
                </td>
            </tr>        
        </table>
    </form>
    </div>
</div>
</body>
</html>