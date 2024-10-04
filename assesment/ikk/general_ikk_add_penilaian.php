<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");

/* create objects */
$set = new Penilaian();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqRowId= httpFilterRequest("reqRowId");
$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterRequest('reqId');
$reqTahun= httpFilterRequest('reqTahun');
$tempId= $reqId;

if($reqMode == "delete")
{
	$set->setField('PENILAIAN_ID', $reqRowId);
	
	if($set->delete())
	{
		$mode = 'hapus';
	}
	else
		$mode = 'error';
}

if($reqMode == "delete")
{
	echo '<script language="javascript">';
	echo "parent.frames['mainFrame'].location.href = 'general_ikk_add_penilaian_monitoring.php?reqId=".$reqId."&reqTahun=".$reqTahun."&reqMode=".$mode."';";
	echo '</script>';
	
	echo '<script language="javascript">';
	echo "parent.frames['mainFrameDetil'].location.href = 'general_ikk_add_penilaian.php?reqId=".$reqId."&reqTahun=".$reqTahun."';";
	echo '</script>';
}

if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	$set->selectByParams(array("A.PENILAIAN_ID"=>$reqRowId));
	$set->firstRow();
	$tempRowId= $set->getField("PENILAIAN_ID");
	$tempSatkerTes= $set->getField("SATKER_TES");
	$tempSatkerTesId= $set->getField("SATKER_TES_ID");
	$tempJabatanTes= $set->getField("JABATAN_TES");
	$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
	$tempTanggalTes= dateToPageCheck($set->getField("TANGGAL_TES"));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>jQuery treeTable Plugin Documentation</title>
	<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
	<?php /*?><link href="../WEB/lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" /> <?php */?>
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
			<? include_once "../jslib/formHandler.php"; ?>
			var value_status="";
			var mode="";

			function setValue()
			{
				value_status= '<?=$tempJenis?>';
				setShow();
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
					url:'../json-ikk/general_ikk_add_penilaian.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
							data = data.split("-");
							mode= data[2];
							$('#rst_form').click();

							setTimeout(setReload, 250);
							//alert(data);
							
						}
					});

				$("#reqSatkerTes, #reqJabatanTes, #reqSatkerTesKirim, #reqJabatanTesKirim").autocomplete({ 
					source:function(request, response){
						var id= this.element.attr('id');
						var replaceAnakId= replaceAnak= urlAjax= "";
						
						if (id.indexOf('reqSatkerTes') !== -1)
						{
							var element= id.split('reqSatkerTes');
							var indexId= "reqSatkerTesId"+element[1];
							urlAjax= "../json-ikk/satuan_kerja_auto_combo_json.php";
							replaceAnakId= "reqJabatanTesId";
							replaceAnak= "reqJabatanTes";
						}
						else if (id.indexOf('reqJabatanTes') !== -1)
						{
							var element= id.split('reqJabatanTes');
							var indexId= "reqJabatanTesId"+element[1];
							var idVal= $("#reqSatkerTesId").val();
							urlAjax= "../json-ikk/jabatan_atribut_auto_combo_json.php?reqId="+idVal;
						}
						
						$.ajax({
							url: urlAjax,
							type: "GET",
							dataType: "json",
							data: { term: request.term },
							success: function(responseData){
								$("#"+indexId).val("").trigger('change');
								if(replaceAnakId == ""){}
									else
									{
										$("#"+replaceAnakId).val("").trigger('change');
										$("#"+replaceAnak).val("").trigger('change');
									}

									if(responseData == null)
									{
										response(null);
									}
									else
									{
										var array = responseData.map(function(element) {
											return {desc: element['desc'], id: element['id'], label: element['label']};
										});
										response(array);
									}
								}
							})
					},
					select: function (event, ui) 
					{ 
						var id= $(this).attr('id');
						var replaceAnakId= replaceAnak= "";
						
						if (id.indexOf('reqSatkerTes') !== -1)
						{
							var element= id.split('reqSatkerTes');
							var indexId= "reqSatkerTesId"+element[1];
							replaceAnakId= "reqJabatanTesId";
							replaceAnak= "reqJabatanTes";
						}
						else if (id.indexOf('reqJabatanTes') !== -1)
						{
							var element= id.split('reqJabatanTes');
							var indexId= "reqJabatanTesId"+element[1];
						}
						
						$("#"+indexId).val(ui.item.id).trigger('change');
					}, 
					//minLength:3,
					autoFocus: true
				}).autocomplete( "instance" )._renderItem = function( ul, item ) {
					return $( "<li>" )
					.append( "<a>" + item.desc + "</a>" )
					.appendTo( ul );
				};

				$('#reqJenis').bind('change', function(ev) {		
					value_status= $('#reqJenis').val();
					setShow();
				});

			});

			function setReload()
			{
				parent.frames['mainFrame'].location.href = 'general_ikk_add_penilaian_monitoring.php?reqId=<?=$reqId?>&reqTahun=<?=$reqTahun?>&reqMode=' + mode;
				parent.frames['mainFrameDetil'].location.href = 'general_ikk_add_penilaian.php?reqId=<?=$reqId?>&reqTahun=<?=$reqTahun?>';
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
		<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css" media="screen" /> 
		<link href="../WEB/css/begron.css" rel="stylesheet" type="text/css">
		<link href="../WEB/css/admin.css" rel="stylesheet" type="text/css">
		<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css">
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
	<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
	<form id="ff" method="post" novalidate>
		<div id="content" style="height:auto; margin-top:-4px; width:100%">
			<div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">
				<ul>
					<?
					if($userLogin->userPengaturanIKK== 0){}else{
						?>
						<? 
						if($reqMode == '')
						{
							$read = 'readonly'; $disabled = 'disabled';
							?>
							<li style="display:none"><a href="general_ikk_add_penilaian.php?reqMode=tambah&reqId=<?=$tempId?>"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
							<li style="display:none"><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
							<li style="display:none"><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
							<li style="display:none"><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
							<li style="display:none"><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>

							<? 
						}
						elseif($reqMode == 'cancel' && $reqRowId == '')
						{
							$read = 'readonly'; $disabled = 'disabled';
							?>
							<li><a href="general_ikk_add_penilaian.php?reqMode=tambah&reqId=<?=$tempId?>"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
							<? 
						}
						elseif($reqMode == 'view' || $reqMode == 'cancel')
						{
							$read = 'readonly'; $disabled = 'disabled';
							?>
							<li><a href="#" onclick="parent.setLoad('general_ikk_add_penilaian_atribut_view.php?reqId=<?=$tempId?>&reqTahun=<?=$reqTahun?>&reqRowId=<?=$reqRowId?>', 1);"><img src="../WEB/img/tambah.png" width="15" height="15"/> Lihat Nilai</a></li>
							<? 
						}
						elseif($reqMode == 'tambah' || $reqMode == 'edit')
						{
							$read = ''; $disabled = '';
							?>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
							<li><a href="#" onclick="$('#ff').submit();"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
							<? 
							if($reqMode == 'edit')
							{
								?>
								<input type="hidden" name="reqMode" value="update">
								<? 
							}
							else
							{
								?>
								<input type="hidden" name="reqMode" value="insert">
								<? 
							}
							?>
							<li><a href="general_ikk_add_penilaian.php?reqMode=cancel&reqId=<?=$tempId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
							<? 
						}
						elseif($reqMode == 'update' || $reqMode == 'insert')
						{
							$read = ''; $disabled = '';
							?>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/tambah.png" width="15" height="15"/> Tambah</a></li>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/edit.png" width="15" height="15"/> Edit</a></li>
							<li><a href="#" style="background: white url(media/bluetab.gif) top left repeat-x; cursor:default; color:#C0C0C0"><img src="../WEB/img/hapus.png" width="15" height="15"/> Hapus</a></li>        
							<li><a href="#" onclick="$('#ff').submit();"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
							<input type="hidden" name="reqMode" value="<?=$reqMode?>">
							<li><a href="general_ikk_add_penilaian.php?reqMode=cancel&reqId=<?=$tempId?>&reqRowId=<?=$reqRowId?>"><img src="../WEB/img/cancel.png" width="15" height="15"/> Batal</a></li>
							<? 
						}
						?>
						<? 
					}
					?>
				</ul>
			</div>
			<div class="content" style="height:90%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
				<input type="hidden" name="reqId" value="<?=$tempId?>">
				<input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
				<table class="table_list" cellspacing="1" width="100%">
					<tr class="gelap">
						<td width="200px">Tanggal Tes</td>
						<td width="1px">:</td>
						<td>
							<input id="reqTanggalTes" name="reqTanggalTes" class="easyui-datebox" required <?=$disabled?> style="width:100px" data-options="validType:'date'" value="<?=$tempTanggalTes?>" />
						</td>
					</tr>
					<tr>
						<td>Unit Kerja Saat Tes</td>
						<td>:</td>
						<td>
							<input type="hidden" name="reqSatkerTesId" id="reqSatkerTesId" value="<?=$tempSatkerTesId?>" /> 
							<input type="text" <?=$disabled?> class="easyui-validatebox" required style="width:98%" id="reqSatkerTes" 
							data-options="validType:['sameAutoLoder[\'reqSatkerTes\', \'\']']"
							value="<?=$tempSatkerTes?>" <?=$readonly?> />
						</td>
					</tr>
					<tr>
						<td>Jabatan Saat Tes</td>
						<td>:</td>
						<td>
							<input type="hidden" name="reqJabatanTesId" id="reqJabatanTesId" value="<?=$tempJabatanTesId?>" /> 
							<input type="text" <?=$disabled?> class="easyui-validatebox" required style="width:98%" id="reqJabatanTes" 
							data-options="validType:['sameAutoLoder[\'reqJabatanTes\', \'\']']"
							value="<?=$tempJabatanTes?>" <?=$readonly?> />
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>

	<script>
/*$("#reqTahun").keypress(function(e) {
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});*/
</script>
</body>
</html>