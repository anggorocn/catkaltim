<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base-polakarir/PerencanaanDetil.php");
include_once("../WEB/classes/base-polakarir/JabatanSyarat.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base/Pegawai.php");

/* create objects */
$pegawai= new Pegawai();
$jabatan_syarat= new JabatanSyarat();
$pegawai_add_karir_rencana = new PerencanaanDetil();

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


$pegawai_add_karir_rencana->selectByParams(array('PERENCANAAN_DETIL_ID'=>$reqRowId));
$pegawai_add_karir_rencana->firstRow();
//echo $pegawai_add_karir_rencana->query;exit;

$tempUsiaRen = $pegawai_add_karir_rencana->getField("USIA_REN");
$tempTipeRencana = $pegawai_add_karir_rencana->getField("TIPE_RENCANA");
$tempPangkatIdRenc = $pegawai_add_karir_rencana->getField("PANGKAT_ID_REN");
$tempPangkatRenc= $pegawai_add_karir_rencana->getField("PANGKAT_REN");
$tempSatkerIdRen = $pegawai_add_karir_rencana->getField("SATKER_ID_REN");
$tempSatkerRen = $pegawai_add_karir_rencana->getField("SATKER_REN");
$tempJabatanIdRenc = $pegawai_add_karir_rencana->getField("JABATAN_ID_REN");
$tempJabatan= $pegawai_add_karir_rencana->getField("JABATAN");
$tempTahun= $pegawai_add_karir_rencana->getField("TAHUN");
$tempTmtJabatanRen = dateToPageCheck($pegawai_add_karir_rencana->getField("TMT_JABATAN_REN"));
$tempPendidikanRenc = $pegawai_add_karir_rencana->getField("PENDIDIKAN");
$reqPendidikanIdRenc= $pegawai_add_karir_rencana->getField("PENDIDIKAN_REN");
$tempKinerjaSkpRen = $pegawai_add_karir_rencana->getField("SKP");
$tempKinerjaPkRen = $pegawai_add_karir_rencana->getField("PK");					
$tempDiklatStrukRen = $pegawai_add_karir_rencana->getField("DIKLAT_STRUKTURAL");
$tempDiklatStrukRenId= $pegawai_add_karir_rencana->getField("DIKLAT_STRUKTURAL_ID");
$tempDiklatTeknisRen = $pegawai_add_karir_rencana->getField("DIKLAT_TEKNIS");
$tempDiklatTeknisRenId = $pegawai_add_karir_rencana->getField("DIKLAT_TEKNIS_ID");

$tempUmur= $pegawai_add_karir_rencana->getField("UMUR");
$tempRowId = $pegawai_add_karir_rencana->getField('PERENCANAAN_DETIL_ID');

$t_pegawai= new Kelautan();
$t_pegawai->selectByParamsMonitoringPegawai(array("A.IDPEG"=>$reqId),-1,-1);
//echo $t_pegawai->query;exit;
//echo $t_pegawai->errorMsg;
$t_pegawai->firstRow();
if($tempRowId == '')
{	
	$tempUmur= $t_pegawai->getField("UMUR");
}
$tempGolonganPegawai= $t_pegawai->getField("golongan");
unset($t_pegawai);

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
				url:'../json-polakarir/pegawai_add_karir_rencana.php',
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
			
			$("#reqUsiaRen").keyup(function(e){
				var today=new Date();
				var tahun= today.getFullYear();
				var tempHitungTahun= "";
				
				if($("#reqUsiaRen").val() == "")
				{
					tahun="";
				}
				else
				{
					if(parseInt($("#reqUmur").val()) >= parseInt($("#reqUsiaRen").val()))
					{
						tempHitungTahun= parseInt($("#reqUmur").val()) - parseInt($("#reqUsiaRen").val());
						tahun= parseInt(tahun) - parseInt(tempHitungTahun);
					}
					else
					{
						tempHitungTahun= parseInt($("#reqUsiaRen").val()) - parseInt($("#reqUmur").val());
						tahun= parseInt(tahun) + parseInt(tempHitungTahun);
					}
				}
				
				$("#reqTahun").val(tahun);
			});
			
			$("#reqTahun").keyup(function(e){
				var tahun= $("#reqUmur").val();
				var tempHitungTahun= "";
				if($("#reqTahun").val() == "")
				{
					tahun="";
				}
				else
				{
					if(parseInt($("#reqTahun").val()) >= parseInt("<?=date("Y")?>"))
					{
						tempHitungTahun= parseInt("<?=date("Y")?>") - parseInt($("#reqTahun").val());
						tahun= parseInt(tahun) - parseInt(tempHitungTahun);
					}
					else
					{
						tempHitungTahun= parseInt($("#reqTahun").val()) - parseInt("<?=date("Y")?>");
						tahun= parseInt(tahun) + parseInt(tempHitungTahun);
					}
				}
				
				$("#reqUsiaRen").val(tahun);
			});
			
			$("#reqSkpd").autocomplete({ 
					source:function(request, response){
						var id= this.element.attr('id');
						var replaceAnakId= replaceAnak= urlAjax= "";
						
						if (id.indexOf('reqSkpd') !== -1)
						{
							var element= id.split('reqSkpd');
							var indexId= "reqSkpdId"+element[1];
							urlAjax= "../json/satker_auto_combo_json.php";
						}
						
						$.ajax({
							url: urlAjax,
							type: "GET",
							dataType: "json",
							data: { term: request.term },
							success: function(responseData){
								$("#"+indexId).val("").trigger('change');
								
								if(responseData == null)
								{
									response(null);
								}
								else
								{
									var array = responseData.map(function(element) {
										return {desc: element['desc'], id: element['id'], label: element['label'], nama_jabatan: element['nama_jabatan']};
									});
									response(array);
								}
							}
						})
					},
					select: function (event, ui) 
					{ 
						var id= $(this).attr('id');
						
						if (id.indexOf('reqSkpd') !== -1)
						{
							var element= id.split('reqSkpd');
							var indexId= "reqSkpdId"+element[1];
						}
						
						$("#"+indexId).val(ui.item.id).trigger('change');
						$("#reqInfoNamaJabatan").text(ui.item.nama_jabatan).trigger('change');
					}, 
					//minLength:3,
					autoFocus: true
				}).autocomplete( "instance" )._renderItem = function( ul, item ) {
					return $( "<li>" )
				  .append( "<a>" + item.desc + "</a>" )
				  .appendTo( ul );
			};
			
		});
		
		function setReload()
		{
			parent.frames['mainFrame'].location.href = 'pegawai_add_karir_rencana_monitoring.php?reqId=<?=$reqId?>&reqMode=' + mode;
			parent.frames['mainFrameDetil'].location.href = 'pegawai_add_karir_rencana.php?reqId=<?=$reqId?>';
		}		
		
		function setJabatan(satker)
		{	
			var url;
			
			if((parseFloat(satker) == parseInt(satker)) && !isNaN(satker))
			{
				if(satker == "<?=$tempSatkerIdRen?>"){}
				else
				$('#reqJabatanIdRenc').combobox('setValue', '');
				
				url = '../json/jabatan_satker_combo_json.php?reqSatkerId='+satker;
			} 
			else 
			{
				url = '../json/jabatan_satker_combo_json.php';
			}
			$('#reqJabatanIdRenc').combobox('reload', url);
			//setPendidikan(pangkat);
		}
		
		function setPendidikan(pangkat)
		{
			var url;
			
			if((parseFloat(pangkat) == parseInt(pangkat)) && !isNaN(pangkat))
			{
				if(pangkat == "<?=$tempPangkatIdRenc?>"){}
				else
				$('#reqPendidikanRenc').combobox('setValue', '');
				
				url: '../json/pendidikan_combo_json.php?reqId='+ pangkat;
			} 
			else 
			{
				url = '../json/pendidikan_combo_json.php';
			}
			$('#reqPendidikanRenc').combobox('reload', url);
		}
		
		function setDownload(reqJabatanId)
		{
			if((parseFloat(reqJabatanId) == parseInt(reqJabatanId)) && !isNaN(reqJabatanId))
			{
				var tempJabatanSyaratId= tempSyarat= tempPath= tempDiklatTeknisRen= tempDiklatTeknisRenId= tempDiklatStrukRen= tempDiklatStrukRenId= tempKinerjaPkRen= tempKinerjaSkpRen= tempPangkatIdRenc= tempPangkatRenc= tempPendidikanRenc= tempPendidikan= "";
				$.getJSON('../json/jabatan_syarat_info.php?reqId=' + reqJabatanId,
				  function(data){
					tempSyarat= data.tempSyarat;
					tempPath= data.tempPath;
					tempDiklatTeknisRen= data.tempDiklatTeknisRen;
					tempDiklatTeknisRenId= data.tempDiklatTeknisRenId;
					tempDiklatStrukRen= data.tempDiklatStrukRen;
					tempDiklatStrukRenId= data.tempDiklatStrukRenId;
					tempKinerjaPkRen= data.tempKinerjaPkRen;
					tempKinerjaSkpRen= data.tempKinerjaSkpRen;
					tempPangkatIdRenc= data.tempPangkatIdRenc;
					tempPangkatRenc= data.tempPangkatRenc;
					tempPendidikanRenc= data.tempPendidikanRenc;
					tempPendidikan= data.tempPendidikan;
					tempJabatanSyaratId= data.tempJabatanSyaratId;
					
					if(tempJabatanSyaratId == "")
					{
						$("#reqDownloadId").hide();
						$("#reqSyarat").attr('src', '');
						//$("#reqSyarat").val('');
						$("#reqLinkData, #tempDiklatTeknisRen, #tempDiklatTeknisRenId, #tempDiklatStrukRen, #tempDiklatStrukRenId, #tempKinerjaPkRen, #tempKinerjaSkpRen, #tempPangkatIdRenc, #tempPangkatRenc, #tempPendidikanRenc, #tempPendidikan").val('');
					}
					else
					{
						$("#reqDownloadId").hide();
						$("#reqSyarat").attr('src', '');
						if(tempPath == ""){}
						else
						{
							$("#reqDownloadId").show();
							$("#reqSyarat").attr('src', '<?=$FILE_DIR?>'+tempPath);
						}
						$("#reqDiklatTeknisRen").val(tempDiklatTeknisRen);
						$("#reqDiklatTeknisRenId").val(tempDiklatTeknisRenId);
						$("#reqDiklatStrukRen").val(tempDiklatStrukRen);
						$("#reqDiklatStrukRenId").val(tempDiklatStrukRenId);
						$("#reqKinerjaPkRen").val(tempKinerjaPkRen);
						$("#reqKinerjaSkpRen").val(tempKinerjaSkpRen);
						$("#reqPangkatIdRenc").val(tempPangkatIdRenc);
						$("#reqPangkatRenc").val(tempPangkatRenc);
						$("#reqPendidikanRenc").val(tempPendidikanRenc);
						$("#reqPendidikan").val(tempPendidikan);
						$("#reqLinkData").val(tempPath);
					}
				  });
			}
			else
			{
				$("#reqDownloadId").hide();
				$("#reqSyarat").attr('src', '');
				//$("#reqSyarat").val('');
				$("#reqLinkData").val('');
			}
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
    	<li><a href="#" onclick="$('#ff').submit();"><img src="../WEB/img/save.png" width="15" height="15"/> Simpan</a></li>
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
        	<td>Usia Skrng</td>
            <td>
            	<input name="reqUmur" id="reqUmur" style="width:50px" type="text" disabled value="<?=$tempUmur?>" /> 
               
            </td>
            <td rowspan="10" >
            	<?php /*?>Catatan<br/><?php */?>
                <iframe id="reqSyarat" scrolling="auto" frameborder="0" style="width:480px; height:250px; margin-top:0px; "></iframe>
                <?php /*?><textarea id="reqSyarat" cols="50" readonly rows="8"><?=$tempSyarat?></textarea><?php */?>
            </td>
        </tr>
		 <tr>
            <td>Usia Rencana</td>
            <td>
			  <input type="text" id="reqUsiaRen" name="reqUsiaRen" class="easyui-validatebox" style="width:50px" value="<?=$tempUsiaRen?>"
                validType="batasMinUmum['#reqUmur', 'Sisa tidak boleh kurang dari Usia Sekarang']" />
			&nbsp;&nbsp; Tahun Rencana              
                <input id="reqTahun" name="reqTahun" style="width:100px" class="easyui-validatebox" value="<?=$tempTahun?>"/> 
			</td>
		</tr>	
		<tr>
			<td>Rencana Karir</td>
			<td>
				<select name="reqTipeRencana">
                	<option value="1" <? if($tempTipeRencana == "1") echo "selected";?>>Reguler</option>
                    <option value="2" <? if($tempTipeRencana == "2") echo "selected";?>>Fast Track</option>
                </select>
			</td>
		</tr>
        <tr>
            <td>Unit Kerja</td>
            <td>
            	<?php /*?><input type="hidden" name="reqSkpd" id="reqSkpd" value="<?=$tempSatkerIdRen?>" />
            	<input type="text" style="width:300px;" id="reqSkpdNama" name="reqSkpdNama" readonly value="<?=$tempSatkerRen?>" /><?php */?>
                
                <input type="hidden" name="reqSkpd" id="reqSkpdId" value="<?=$tempSatkerIdRen?>" /> 
                <input type="text" class="easyui-validatebox" style="width:300px" id="reqSkpd" name="reqSkpdNama"
                data-options="validType:['sameAutoLoder[\'reqSkpd\', \'\']']"
                value="<?=$tempSatkerRen?>" <?=$readonly?> />
                            
            	<?php /*?><a href="#" id="reqCariJurusanInfo" onClick="setLookupJabatan();" ><img src="../WEB/images/icn_search.png" title="cari" /></a><?php */?>
            </td>
        </tr>
        <tr>
            <td>Nama Jabatan</td>
            <td>
            	<label id="reqInfoNamaJabatan"><?=$tempJabatan?></label>
            	<?php /*?><input id="reqJabatanIdRenc" name="reqJabatanIdRenc" style="width:300px" class="easyui-combobox" 
                data-options="
                filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },
				valueField: 'id', textField: 'text',
                url: '../json/jabatan_satker_combo_json.php?reqSatkerId=<?=$tempSatkerIdRen?>',
                onChange:function(newValue,oldValue)
                {
                    setDownload(newValue);
                },
                onSelect:function(rec){
                    var value = rec.id;
                    setDownload(value);
                }
                "
                validType="exists['#reqJabatanIdRenc']"
                value="<?=$tempJabatanIdRenc?>"
                /><?php */?>
                <input type="hidden" id="reqLinkData" value="<?=$tempPath?>" />
                <a href="#" id="reqDownloadId" style="display:none" onclick="getDownload();">
				  <img src="images/download.png" width="16" height="16">
				</a>
            </td>
        </tr>
        <tr style="display:none">
        	<td>Pangkat/Gol Ruang</td>
            <td>
            	<input type="hidden" id="reqPangkatIdRenc" name="reqPangkatIdRenc" value="<?=$tempPangkatIdRenc?>" />
                <input type="text" id="reqPangkatRenc" name="reqPangkatRenc" readonly style="width:80px; <?php /*?>background:none; border:none<?php */?>" value="<?=$tempPangkatRenc?>" />
                &nbsp;Pendidikan&nbsp;
                <input type="hidden" id="reqPendidikanIdRenc" name="reqPendidikanRenc" value="<?=$reqPendidikanIdRenc?>" />
                <input type="text" id="reqPendidikan" name="reqPendidikan" readonly style="width:80px; <?php /*?>background:none; border:none<?php */?>" value="<?=$tempPendidikanRenc?>" />
            </td>
        </tr>
        <tr style="display:none">
            <td>Hasil Penilaian Kinerja</td>
            <td>
            	SKP&nbsp;
                <input name="reqKinerjaSkpRen" id="reqKinerjaSkpRen" readonly type="text" style="width:50px; <?php /*?>background:none; border:none<?php */?>" value="<?=$tempKinerjaSkpRen?>" />
                &nbsp;PK&nbsp;
                <input name="reqKinerjaPkRen" id="reqKinerjaPkRen" readonly type="text" style="width:50px; <?php /*?>background:none; border:none<?php */?>" value="<?=$tempKinerjaPkRen?>" />
            </td>
        </tr>
        <tr style="display:none">
            <td>Diklat Pim</td>
            <td>
            	<input type="hidden" id="reqDiklatStrukRenId" name="reqDiklatStrukRenId" value="<?=$tempDiklatStrukRenId?>" />
            	<input name="reqDiklatStrukRen" id="reqDiklatStrukRen" readonly type="text" style="width:300px; <?php /*?>background:none; border:none<?php */?>" value="<?=$tempDiklatStrukRen?>" />
            </td>
        </tr>
		<tr style="display:none">
            <td>Diklat Teknis</td>
            <td>
            	<input type="hidden" id="reqDiklatTeknisRenId" name="reqDiklatTeknisRenId" value="<?=$tempDiklatTeknisRenId?>" />
                <input name="reqDiklatTeknisRen" id="reqDiklatTeknisRen" readonly type="text" style="width:300px; <?php /*?>background:none; border:none<?php */?>" value="<?=$tempDiklatTeknisRen?>" />
            </td>
        </tr>
    </table>
        <div style="display:none">
        	<? if($tempRowId == ''){ $reqMode='insert'; }else{ $reqMode='update'; }?>
        	<input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
            <input type="hidden" name="reqId" value="<?=$reqId?>">
            <input type="hidden" name="reqMode" value="<?=$reqMode?>">
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