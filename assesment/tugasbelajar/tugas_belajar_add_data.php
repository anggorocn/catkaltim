<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-tugasbelajar/TugasBelajar.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/functions/date.func.php");

/* create objects */
$set= new TugasBelajar();

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
	$tempTipeTugas=1;
}
else
{

/* VALIDATION */
$set->selectByParams(array("A.TUGAS_BELAJAR_ID" => $reqId));
$set->firstRow();
//echo $set->query;exit;


$tempId = $set->getField("TUGAS_BELAJAR_ID");
$tempPegawaiId = $set->getField("PEGAWAI_ID");
$tempNoSk = $set->getField("NO_SK");
$tempJurusan = $set->getField("JURUSAN");
$tempPendidikan = $set->getField("PENDIDIKAN");
$tempNamaSekolah = $set->getField("NAMA_SEKOLAH");
$tempSatkerId = $set->getField("SATKER_ID");
$tempSatkerIdEselon = $set->getField("SATKER_ID_ESELON");
$tempStatusIjin = $set->getField("STATUS_IJIN");
$tempStatusBelajar = $set->getField("STATUS_BELAJAR");
$tempTmtMulai = dateToPageCheck($set->getField("TMT_MULAI"));
$tempTmtSelesai = dateToPageCheck($set->getField("TMT_SELESAI"));
$tempTmtPerpanjangan = $set->getField("TMT_PERPANJANGAN");
$tempTmtAktif = $set->getField("TMT_AKTIF");
$tempPegawaiNama = $set->getField("NAMA_PEGAWAI");
$tempSatkerNamaId = $set->getField("NAMA_UNKER");
$tempPembiayaan= $set->getField("PEMBIAYAAN");
$tempTipeTugas = $set->getField("TIPE_TUGAS");
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
				url:'../json-tugasbelajar/tugas_belajar_add_data.php',
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
			
			var dates = $('input[id^="reqTmtMulai"], input[id^="reqTmtSelesai"]').datepicker({
				defaultDate: "+1w",
				dateFormat: 'dd-mm-yy',
				numberOfMonths: 1,
				beforeShow: function( ) {
					//var other = this.id == "reqTmtMulai" ? "#reqTmtSelesai" : "#reqTmtMulai";
					//var option = this.id == "reqTmtMulai" ? "maxDate" : "minDate";
					
					var id= indexId= other= option= "";
					id= this.id;
					//alert(id);
					
					if (id.indexOf('reqTmtMulai') !== -1)
					{
						indexId= id.split('reqTmtMulai');
						indexId= indexId[1];
						
						other= "#reqTmtSelesai"+indexId;
						option= "maxDate";
					}
					else
					{
						indexId= id.split('reqTmtSelesai');
						indexId= indexId[1];
						
						other= "#reqTmtMulai"+indexId;
						option= "minDate";
					}
					
					var selectedDate = $(other).datepicker('getDate');
					
					$(this).datepicker( "option", option, selectedDate );
				}
			}).change(function(){
				//var other = this.id == "reqTmtMulai" ? "#reqTmtSelesai" : "#reqTmtMulai";
				
				var id= indexId= other= "";
				id= this.id;
				
				if (id.indexOf('reqTmtMulai') !== -1)
				{
					indexId= id.split('reqTmtMulai');
					indexId= indexId[1];
						
					other= "#reqTmtSelesai"+indexId;
				}
				else
				{
					indexId= id.split('reqTmtSelesai');
					indexId= indexId[1];
					
					other= "#reqTmtMulai"+indexId;
				}
					
				if ( $('#reqTmtMulai'+indexId).datepicker('getDate') > $('#reqTmtSelesai'+indexId).datepicker('getDate') )
					$(other).datepicker('setDate', $(this).datepicker('getDate') );
			});
			
		});
		
		function setReload()
		{
			var reqTahun= reqPegawaiId= "";
			parent.frames['menuFrame'].location.href = 'tugas_belajar_menu.php?reqId='+tempId+"&reqPegawaiId="+$("#reqPegawaiId").val();
			parent.frames['mainFrame'].location.href = 'tugas_belajar_add_data.php?reqId='+tempId;
			top.frames['mainFullFrame'].location.href = 'tugas_belajar.php';
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
            <div id="header-tna-detil">Data <span>Tugas Belajar</span></div>
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
            <td>No SK</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqNoSk" id="reqNoSk" style="width:200px" value="<?=$tempNoSk?>" class="easyui-validatebox" required />
			</td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>:</td>
            <td>
            	<input type="hidden" id="reqSatkerId" name="reqSatkerId" value="<?=$tempSatkerId?>" />
                <label id="reqSatkerNamaId"><?=$tempSatkerNamaId?></label>
            </td>
        </tr>
        <tr>
            <td>Tugas Belajar</td>
            <td>:</td>
			<td>
            	<select id="reqPendidikan" name="reqPendidikan">
                	<option value="D4" <? if($tempPendidikan == "D4") echo "selected";?>>DIV</option>
                	<option value="S1" <? if($tempPendidikan == "S1") echo "selected";?>>S1</option>
                	<option value="S2" <? if($tempPendidikan == "S2") echo "selected";?>>S2</option>
                    <option value="S3" <? if($tempPendidikan == "S3") echo "selected";?>>S3</option>
                </select>
			</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqJurusan" id="reqJurusan" style="width:300px" value="<?=$tempJurusan?>" class="easyui-validatebox" required />
			</td>
        </tr>
        <tr>
            <td>Perguruan Tinggi</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqNamaSekolah" id="reqNamaSekolah" style="width:300px" value="<?=$tempNamaSekolah?>" class="easyui-validatebox" required />
			</td>
        </tr>
        <tr>
            <td>Pembiayaan</td>
            <td>:</td>
			<td>
            	<input type="text" name="reqPembiayaan" id="reqPembiayaan" style="width:250px" value="<?=$tempPembiayaan?>" class="easyui-validatebox" required />
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
            <td>Jangka Waktu</td>
            <td>:</td>
			<td>
            	<input type="text" class="easyui-validatebox" style="width:100px" id="reqTmtMulai" name="reqTmtMulai"
                data-options="validType:['dateValidPicker']"
                value="<?=$tempTmtMulai?>" />
                s/d
                <input type="text" class="easyui-validatebox" style="width:100px" id="reqTmtSelesai" name="reqTmtSelesai"
                data-options="validType:['dateValidPicker']"
                value="<?=$tempTmtSelesai?>" />
			</td>
        </tr>
    </table>
    
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">
    <ul>
    	<input type="hidden" name="reqId" value="<?=$tempId?>">
    	<input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
        <input type="hidden" name="reqMode" value="<?=$reqMode?>">
        <input type="hidden" name="reqTipeTugas" value="<?=$tempTipeTugas?>">
        <?php /*?><input type="submit" value="Submit">
        <input type="reset" id="rst_form"><?php */?>
         <?
			if($userLogin->userLihatProses== 1){
				?>
                <a href="#" onclick="$('#ff').submit();">SIMPAN</a></li>
				<?
				}else{
			?>
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