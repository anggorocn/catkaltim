<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-tugasbelajar/Hukuman.php");
include_once("../WEB/classes/base/TingkatHukuman.php");
include_once("../WEB/classes/base/JenisHukuman.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/functions/date.func.php");

/* create objects */
$set= new Hukuman();
$tingkat_hukuman = new TingkatHukuman();
$jenis_hukuman = new JenisHukuman();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode= httpFilterRequest("reqMode");
$reqRowMode= httpFilterGet("reqRowMode");
$reqId= httpFilterRequest("reqId");

if($reqId == '')
{
	$reqMode = "insert";
}
else
{
$reqMode = "update";
/* VALIDATION */
$set->selectByParams(array("A.HUKUMAN_ID" => $reqId));
//echo $set->query;exit;
$set->firstRow();


$tempId = $set->getField("TUGAS_BELAJAR_ID");
$tempPegawaiId = $set->getField("PEGAWAI_ID");
$tempTingkatHukuman = $set->getField("TINGKAT_HUKUMAN_ID");
$tempJenisHukuman = $set->getField("JENIS_HUKUMAN_ID");
$tempNoSk = $set->getField("NO_SK");
$tempTanggalSK = dateToPage($set->getField("TANGGAL_SK"));
$tempTMTSK = dateToPage($set->getField("TMT_SK"));
$tempTanggalMulai = dateToPage($set->getField("TANGGAL_MULAI"));
$tempTanggalAkhir = dateToPage($set->getField("TANGGAL_AKHIR"));
$tempPegawaiNama = $set->getField("NAMA_PEGAWAI");
$tempPermasalahan = $set->getField("KETERANGAN");
}
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
    
    <script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
    <script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/daterangepicker.js"></script>
    
	<script type="text/javascript">
		<? include_once "../jslib/formHandler.php"; ?>
		var value_status="";
  		var mode=tempId="";
		
		$.extend($.fn.validatebox.defaults.rules, {
			dateValidPicker: {
				validator: function(value){  
					var check = false;
					//var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
					var re = /^(\d{1,2})(-|\/)(\d{1,2})\2(\d{1,4})$/;
					if( re.test(value)){
						var adata = value.split('-');
						var mm = parseInt(adata[1],10);
						var dd = parseInt(adata[0],10);
						var yyyy = parseInt(adata[2],10);
						var xdata = new Date(yyyy,mm-1,dd);
						if ( ( xdata.getFullYear() == yyyy ) && ( xdata.getMonth () == mm - 1 ) && ( xdata.getDate() == dd ) )
							check = true;
						else
							check = false;
					} else
						check = false;
					
					return check;
				},
				message: "Tanggal belum valid, dd-mm-yyyy"
			}
		});
		
		$(function(){
			$('#ff').form({
				url:'../json-tugasbelajar/hukuman_add_data.php',
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
			
			var dates = $('input[id^="reqTanggalMulai"], input[id^="reqTanggalAkhir"]').datepicker({
				defaultDate: "+1w",
				dateFormat: 'dd-mm-yy',
				numberOfMonths: 1,
				beforeShow: function( ) {
					//var other = this.id == "reqTanggalMulai" ? "#reqTanggalAkhir" : "#reqTanggalMulai";
					//var option = this.id == "reqTanggalMulai" ? "maxDate" : "minDate";
					
					var id= indexId= other= option= "";
					id= this.id;
					//alert(id);
					
					if (id.indexOf('reqTanggalMulai') !== -1)
					{
						indexId= id.split('reqTanggalMulai');
						indexId= indexId[1];
						
						other= "#reqTanggalAkhir"+indexId;
						option= "maxDate";
					}
					else
					{
						indexId= id.split('reqTanggalAkhir');
						indexId= indexId[1];
						
						other= "#reqTanggalMulai"+indexId;
						option= "minDate";
					}
					
					var selectedDate = $(other).datepicker('getDate');
					
					$(this).datepicker( "option", option, selectedDate );
				}
			}).change(function(){
				//var other = this.id == "reqTanggalMulai" ? "#reqTanggalAkhir" : "#reqTanggalMulai";
				
				var id= indexId= other= "";
				id= this.id;
				
				if (id.indexOf('reqTanggalMulai') !== -1)
				{
					indexId= id.split('reqTanggalMulai');
					indexId= indexId[1];
						
					other= "#reqTanggalAkhir"+indexId;
				}
				else
				{
					indexId= id.split('reqTanggalAkhir');
					indexId= indexId[1];
					
					other= "#reqTanggalMulai"+indexId;
				}
					
				if ( $('#reqTanggalMulai'+indexId).datepicker('getDate') > $('#reqTanggalAkhir'+indexId).datepicker('getDate') )
					$(other).datepicker('setDate', $(this).datepicker('getDate') );
			});
			
		});
		
		function setReload()
		{
			parent.frames['mainFrame'].location.href = 'hukuman_add_data.php?reqId='+tempId;
			top.frames['mainFullFrame'].location.href = 'hukuman.php';
		}
		
		function setJenisHukuman(val)
		{
			var url = '../json-tugasbelajar/jenis_hukuman_combo_json.php?reqId='+val;
			
			$('#reqJenisHukuman').combobox('reload', url);
			
			if((parseFloat(val) == parseInt(val)) && !isNaN(val))
			{
				$('#reqJenisHukuman').combobox('setValue', '');
			} 
			else 
			{
			}
		}
	</script>
    <link href="../WEB/css/begron.css" rel="stylesheet" type="text/css">
    <link href="../WEB/css/admin.css" rel="stylesheet" type="text/css">
	<link href="../WEB/themes/main.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
    
    <link href="tabs.css" rel="stylesheet" type="text/css" />
 	<style type="text/css" media="screen">
      label {
        font-size: 12px;
        /*font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 3px;
        clear: both;*/
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
    
    <link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
	<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script> 
    
    <script type="text/javascript">
    function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
    {
        var left = 10;//(screen.width/2)-(opWidth/2);
        
        divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+',top=20,resize=1,scrolling=1,midle=1'); return false;
    }
	
	function openPencarianPegawai()
    {
        OpenDHTML('../pengaturan/pegawai_pencarian.php', 'Pencarian Pegawai', 1200, 600);	
    }
    
    function OptionSetPegawai(id){
        $.getJSON('../json-pengaturan/pegawai_pencarian_info.php?reqPegawaiId=' + id,
          function(data){
            reqPegawai=data.reqPegawai;
			reqKodeUnker= data.reqKodeUnker;
			reqKodeNamaUnker= data.reqKodeNamaUnker;
            reqPegawaiId=data.reqPegawaiId;
            if(data.reqPegawaiId == ""){}
            else
            {
				$('#reqSatkerNamaId').text(reqKodeNamaUnker);
				$('#reqSatkerId').val(reqKodeUnker);
				$('#reqPegawaiId').val(reqPegawaiId);
				$('#reqPegawaiNama').val(reqPegawai);
            }
            
        });
    }
        
    function openPencarianSatker()
    {
        OpenDHTML('satuan_kerja_pencarian.php', 'Pencarian Satuan Kerja', 800, 600);	
    }
    
    function OptionSetSatker(id){
        $.getJSON('../json-pengaturan/satuan_kerja_pencarian_info.php?reqSatkerId=' + id,
          function(data){
            reqSatker=data.reqSatker;
            reqSatkerId=data.reqSatkerId;
            if(data.reqSatkerId == ""){}
            else
            {
				$('#reqSatkerId').val(reqSatkerId);
				$('#reqSatkerNama').val(reqSatker);
            }
            
        });
    }
    </script> 
    

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
            <div id="header-tna-detil">Data <span>Hukuman</span></div>
			</td>			
        </tr>
        <tr>
            <td>Nama Pegawai</td>
            <td>:</td>
            <td>
            	<input type="hidden" id="reqPegawaiId" name="reqPegawaiId" value="<?=$tempPegawaiId?>" />
                <input id="reqPegawaiNama" name="reqPegawaiNama" class="easyui-validatebox" size="50" type="text" value="<?=$tempPegawaiNama?>" />
                <img src="../WEB/images/icn_search.gif" onClick="openPencarianPegawai()">
            </td>
        </tr>
        <tr>
            <td width="10%">Tingkat Hukuman</td>
            <td width="2%">:</td>
			<td colspan="4">            	
	            <input id="reqTingkatHukuman" name="reqTingkatHukuman" required style="width:225px" class="easyui-combobox" <?=$disabled?> data-options="
                filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },
                valueField: 'id', textField: 'text',
                url: '../json-tugasbelajar/tingkat_hukuman_combo_json.php',
                onChange:function(newValue,oldValue)
                {
                    setJenisHukuman(newValue);
                },
                onSelect:function(rec){
                    var value = rec.id;
                    setJenisHukuman(value);
                }
                "
                validType="exists['#reqTingkatHukuman']"
                value="<?=$tempTingkatHukuman?>" />
				<input type="checkbox" name="reqMasihBerlaku" disabled="disabled" value="1" <? if($tempMasihBerlaku == 1) echo 'checked'?>/> Masih Berlaku<br />
			</td>			
        </tr>
		<tr>           
            <td>Jenis Hukuman</td>
            <td>:</td>
			<td colspan="4">
            	<input id="reqJenisHukuman" name="reqJenisHukuman" required style="width:500px" class="easyui-combobox" <?=$disabled?> data-options="
                filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },
                valueField: 'id', textField: 'text',
                url: '../json-tugasbelajar/jenis_hukuman_combo_json.php?reqId=<?=$tempTingkatHukuman?>'
                "
                validType="exists['#reqJenisHukuman']"
                value="<?=$tempJenisHukuman?>" />
			</td>			
        </tr>
		<tr>      
			<td width="5%">No. SK</td>
            <td width="2%">:</td>
			<td colspan="4">
				<input type="text" class="easyui-validatebox" required style="width:150px" name="reqNoSK" value="<?=$tempNoSk?>" title="No SK harus diisi" />
			</td>            
        </tr>
		<tr>      
			<td width="5%">Tanggal SK</td>
            <td width="2%">:</td>
			<td colspan="4">
            	<input id="reqTanggalSK" name="reqTanggalSK" class="easyui-datebox" style="width:100px" data-options="validType:'date'" value="<?=$tempTanggalSK?>" />
			</td>            
        </tr>
		<tr>           
			<td>TMT SK</td>
            <td>:</td>
			<td>
            	<input id="reqTMTSK" name="reqTMTSK" class="easyui-datebox" style="width:100px" data-options="validType:'date', panelHeight:'170'" value="<?=$tempTMTSK?>" />
			</td>
        </tr>
        <tr>
            <td>Jangka Waktu</td>
            <td>:</td>
			<td>
            	<input type="text" class="easyui-validatebox" style="width:100px" id="reqTanggalMulai" name="reqTanggalMulai"
                data-options="validType:['dateValidPicker']"
                value="<?=$tempTanggalMulai?>" />
                s/d
                <input type="text" class="easyui-validatebox" style="width:100px" id="reqTanggalAkhir" name="reqTanggalAkhir"
                data-options="validType:['dateValidPicker']"
                value="<?=$tempTanggalAkhir?>" />
			</td>
        </tr>
        <tr>           
			<td>Permasalahan</td>
            <td>:</td>
			<td>
				<input type="text" class="easyui-validatebox" style="width:350px" name="reqPermasalahan" value="<?=$tempPermasalahan?>" />
			</td>
        </tr>
    </table>
    
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">
    <ul>
    	<input type="hidden" name="reqId" value="<?=$reqId?>">
    	<input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
        <input type="hidden" name="reqMode" value="<?=$reqMode?>">
        <?php /*?><input type="submit" value="Submit">
        <input type="reset" id="rst_form"><?php */?>
        <?
			if($userLogin->userLihatProses== 1){}else{
			?>
        <li><a href="#" onclick="$('#ff').submit();">SIMPAN</a></li>
        <? 
			}?>
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