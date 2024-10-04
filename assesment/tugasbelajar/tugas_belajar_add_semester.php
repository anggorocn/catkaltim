<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base-tugasbelajar/TugasBelajarLapor.php");

/* create objects */
$tugas_belajar_add_semester = new TugasBelajarLapor();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");

if($reqRowId == '')
{	
	$tempTanggal = date("d-m-Y");
	$tempTipeTugas=1;
}
else
{
	$tugas_belajar_add_semester->selectByParams(array('A.TUGAS_BELAJAR_LAPOR_ID'=>$reqRowId));
	$tugas_belajar_add_semester->firstRow();
	//echo $tugas_belajar_add_semester->query;exit;
	
	$tempTanggal = dateToPage($tugas_belajar_add_semester->getField("TANGGAL"));
	$tempKeterangan = $tugas_belajar_add_semester->getField("KETERANGAN");
	$tempStatusBelajar = $tugas_belajar_add_semester->getField("STATUS_BELAJAR");
	$tempSemester = $tugas_belajar_add_semester->getField("SEMESTER");
	$tempTipeTugas = $tugas_belajar_add_semester->getField("TIPE_TUGAS");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
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
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.form.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.linkbutton.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.draggable.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.resizable.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.panel.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.window.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.progressbar.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.messager.js"></script>      
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.tooltip.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.validatebox.js"></script>  
    <script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.combo.js"></script>
    
    <script type="text/javascript" src="../WEB/lib/easyui/kalender.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/klik_kanan.js"></script>
	<script type="text/javascript">
		var tempTMT='';
		
		function setValue(){
			setDownload("<?=$tempJabatanIdRenc?>");
			//$('#ccJabatan').combobox('setValue', '<?=$tempJabatan?>');
		}
		
		function getDownload()
		{
			var linkData="";
			linkData= $("#reqLinkData").val();
			//alert('<?=$FILE_DIR?>'+linkData);
			parent.OpenDHTMLDetil('<?=$FILE_DIR?>'+linkData, 'File', '450', '400');
		}
		
		<? include_once "../jslib/formHandler.php"; ?>
		var value_status="";
  		var mode="";
		
		function setValue()
		{
			value_status= '<?=$tempJenis?>';
			setShow();
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
		
		$.extend($.fn.validatebox.defaults.rules, {
			sameAutoLoder: {
				validator: function(value, param){  
					var indexId= param[0]+"Id"+param[1];
					var value= $("#"+indexId).val();

					if(value == "")
						return false;
					else
						return true;
				},
				message: 'Data tidak ditemukan'
			}
		});
		
		$(function(){
			$('#ff').form({
				url:'../json-tugasbelajar/tugas_belajar_add_semester_add_data.php',
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
			
		});
		
		function setReload()
		{
			parent.frames['mainFrame'].location.href = 'tugas_belajar_add_semester_monitoring.php?reqId=<?=$reqId?>&reqMode=' + mode;
			parent.frames['mainFrameDetil'].location.href = 'tugas_belajar_add_semester.php?reqId=<?=$reqId?>';
		}
		
	</script>
    <link href="../WEB/css/begron.css" rel="stylesheet" type="text/css">
    <link href="../WEB/css/admin.css" rel="stylesheet" type="text/css">
	<link href="../WEB/themes/main.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
    
    <link href="tabs.css" rel="stylesheet" type="text/css" />
 	<?php /*?><style type="text/css" media="screen">
      label {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 3px;
        clear: both;
      }
    </style><?php */?>
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
        if($userLogin->userLihatProses== 1){
		?>
			<li><a href="#" onclick="$('#ff').submit();"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
		<?
		}else{
        ?>
    	<li><a href="#" onclick="$('#ff').submit();"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
	 	<? 
        }?>
        <?
        if($reqRowId == "") {}
		else
		{
		?>
            <li>
            <a href="pegawai_add_karir_rencana.php?reqId=<?=$reqId?>"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a>
            </li>        
        <?
		}
		?>
    </ul>
    </div>
<div class="content" style="height:90%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
	<input type="hidden" name="reqId" value="<?=$tempPegawaiId?>">
    <input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
    <table>
    	<tr>
            <td width="200px">Tanggal</td>
            <td>:</td>
            <td>
            	<input id="reqTanggal" name="reqTanggal" class="easyui-datebox" required style="width:100px" data-options="validType:'date'" value="<?=$tempTanggal?>" />
            </td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqKeterangan" id="reqKeterangan" style="width:800px" value="<?=$tempKeterangan?>" class="easyui-validatebox" required />
			</td>
        </tr>
        <tr>
            <td>Status Belajar</td>
            <td>:</td>
			<td>
            	<select id="reqStatusBelajar" name="reqStatusBelajar">
                	<option value="1" <? if($tempStatusBelajar == "1") echo "selected";?>>Tugas Belajar</option>
                    <option value="2" <? if($tempStatusBelajar == "2") echo "selected";?>>Perpanjangan Tugas Belajar</option>
                    <option value="3" <? if($tempStatusBelajar == "3") echo "selected";?>>Pengaktifan Tugas Belajar</option>
                </select>
			</td>
        </tr>
        <tr>
            <td>Semester</td>
            <td>:</td>
			<td>
            	<select id="reqSemester" name="reqSemester">
                	<?
					for($i=1; $i<=14; $i++)
					{
					?>
                	<option value="<?=$i?>" <? if($tempSemester == "$i") echo "selected";?>><?=$i?></option>
                    <?
					}
                    ?>
                </select>
			</td>
        </tr>
    </table>
        <div style="display:none">
        	<? if($reqRowId == ''){ $reqMode='insert'; }else{ $reqMode='update'; }?>
        	<input type="text" name="reqRowId" value="<?=$reqRowId?>">
            <input type="text" name="reqId" value="<?=$reqId?>">
            <input type="text" name="reqMode" value="<?=$reqMode?>">
            <input type="hidden" name="reqTipeTugas" value="<?=$tempTipeTugas?>">
            <input type="submit" name="reqSubmit" id="btnSubmit" value="Submit">
            <input type="reset" id="rst_form">
        </div>
</div>
</div>
</form>

<script>
$("#reqUsiaRen,#reqTahun").keypress(function(e) {
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});

$("#reqKinerjaSkpRen, #reqKinerjaPkRen").keypress(function(e) {
	//alert(e.which);
	if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});
</script>
</body>
</html>