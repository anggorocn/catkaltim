<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UserGroupsBase.php");

$set= new UserGroupsBase();

$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode = "insert";	
	
	$tempIKK = 1;
	$tempPengembangan = 1;
	$tempPolaKarir =1;
	$tempEvaluasiKerja = 1;
	$tempTugasBelajar = 1;
	$tempRencanaSuksesi = 1;
	
}
else
{
	$reqMode = "update";	
	$set->selectByParamsMonitoring(array('A.USER_GROUP_ID'=>$reqId), -1, -1);
	$set->firstRow();
	
	$tempId = $set->getField("USER_GROUP_ID");
	$tempNama = $set->getField("NAMA");
	$tempList =  $set->getField("TEMP_LIST");
	$tempPegawaiProses = $set->getField("PEGAWAI_PROSES");	
	$tempMasterProses = $set->getField("MASTER_PROSES");
	$tempLihatProses = $set->getField("LIHAT_PROSES");
	$tempIKK = $set->getField("IKK_PROSES");
	$tempPengembangan = $set->getField("PENGEMBANGAN_SDM_PROSES");
	$tempPolaKarir = $set->getField("POLA_KARIR_PROSES");
	$tempEvaluasiKerja = $set->getField("EVALUASI_KINERJA_PROSES");
	$tempTugasBelajar = $set->getField("TUGAS_BELAJAR_PROSES");
	$tempRencanaSuksesi = $set->getField("RENCANA_SUKSESI_PROSES");
	$tempPengaturanIKK = $set->getField("PENGATURAN_IKK");
}

$user_group= new UserGroupsBase();
$user_group->selectByParamsMonitoring();
//echo $user_group->query;exit;
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
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
	var tempUserLogin="";
	$.extend($.fn.validatebox.defaults.rules, {
		existUserLogin:{
			validator: function(value, param){
				if($(param[0]).val() == "")
				{
					$.getJSON("../json-pengaturan/user_login_validasi_json.php?reqUserLogin="+value,
					function(data){
						tempUserLogin= data.USER_LOGIN;
					});
				}
				else
				{
					$.getJSON("../json-pengaturan/user_login_validasi_json.php?reqUserLoginTemp="+$(param[0]).val()+"&reqUserLogin="+value,
					function(data){
						tempUserLogin= data.USER_LOGIN;
					});
				}
				 
				 if(tempUserLogin == '')
					return true;
				 else
					return false;
			},
			message: 'User Login, sudah ada.'
		}  
	});
		
	$(function(){
		$('#reqIdLihatPassword').click(function () {
			if($(this).prop('checked')) {
			   // do what you need here
			   $('#reqUserPassword').prop('type','text');
			   //alert("Checked");
			}
			else {
			   // do what you need here
			   $('#reqUserPassword').prop('type','password');
			   //alert("Unchecked");
			}
		});
			
		$('#ff').form({
			url:'../json-pengaturan/user_group_add.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data); return false;
				$.messager.alert('Info', data, 'info');
				$('#rst_form').click();
				top.frames['mainFullFrame'].location.reload();
				<? if($reqMode == "update") { ?>
					window.parent.divwin.close();
				<? } ?>
			}
		});
		
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

<link href="../WEB/lib/MultipleSelect/multiple-select.css" rel="stylesheet"/>
<script src="../WEB/lib/MultipleSelect/jquery.multiple.select.js"></script>

<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>  
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
    	<table class="table_list" cellspacing="1" width="100%">
            <tr>
                <td colspan="6">
                <div id="header-tna-detil">Master <span>Group User</span></div>	                    
                </td>			
            </tr>
            <tr>
                <td>Nama</td>
                <td>
                    <input id="reqNama" name="reqNama" class="easyui-validatebox" required size="50" type="text" value="<?=$tempNama?>" />
                </td>
            </tr>
            <tr>
                <td>Pegawai Proses</td>
                <td>
                    <input type="radio" <? if($tempPegawaiProses== '1') echo 'checked';?>  name="reqPegawaiProses" value="1" /> Ya &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempPegawaiProses== '0') echo 'checked';?> name="reqPegawaiProses"  value="0" /> Tidak
                </td>        
            </tr>
            <tr>
                <td>Master Proses</td>
                <td>
                    <input type="radio" <? if($tempMasterProses== '1') echo 'checked';?>  name="reqMasterProses" value="1" /> Ya &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempMasterProses== '0') echo 'checked';?> name="reqMasterProses" value="0" /> Tidak
                </td>        
            </tr>
            <tr>
                <td>Lihat Proses</td>
                <td>
                    <input type="radio" <? if($tempLihatProses== '1') echo 'checked';?>  name="reqLihatProses" value="1" /> Ya &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempLihatProses== '0') echo 'checked';?> name="reqLihatProses" value="0" /> Tidak
                </td>        
            </tr>
             <tr>
                <td>Pengaturan IKK</td>
                <td>
                  <select name="reqPengaturanIKK">
                         <option value="1" <? if($tempPengaturanIKK == '1') echo 'selected';?>>Lihat Proses Ikk</option>
                         <option value="0" <? if($tempPengaturanIKK == '0') echo 'selected';?>>Tidak</option>
                    </select>
                </td>        
            </tr>
            <tr>
                <td>IKK</td>
                <td>
                    <input type="radio" <? if($tempIKK== '1') echo 'checked';?>  name="reqIKK" value="1" /> Lihat Semua Satker &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempIKK== '0') echo 'checked';?> name="reqIKK" value="0" /> Tidak
                </td>        
            </tr>
            <tr>
                <td>Pengembangan SDM</td>
                <td>
                    <input type="radio" <? if($tempPengembangan== '1') echo 'checked';?>  name="reqPengembangan" value="1" /> Lihat Semua Satker &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempPengembangan== '0') echo 'checked';?> name="reqPengembangan" value="0" /> Tidak
                </td>        
            </tr>
            <tr>
                <td>Pola Karir</td>
                <td>
                    <input type="radio" <? if($tempPolaKarir== '1') echo 'checked';?>  name="reqPolaKarir" value="1" /> Lihat Semua Satker &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempPolaKarir== '0') echo 'checked';?> name="reqPolaKarir" value="0" /> Tidak
                </td>        
            </tr>
            <tr style="display:none">
                <td>Evaluasi Kinerja</td>
                <td>
                    <input type="radio" <? if($tempEvaluasiKerja== '1') echo 'checked';?>  name="reqEvaluasiKerja" value="1" /> Lihat Semua Satker &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempEvaluasiKerja== '0') echo 'checked';?> name="reqEvaluasiKerja" value="0" /> Tidak
                </td>        
            </tr>
            <tr style="display:none">
                <td>Tugas Belajar dan Hukuman</td>
                <td>
                    <input type="radio" <? if($tempTugasBelajar== '1') echo 'checked';?>  name="reqTugasBelajar" value="1" /> Lihat Semua Satker &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempTugasBelajar== '0') echo 'checked';?> name="reqTugasBelajar" value="0" /> Tidak
                </td>        
            </tr>
             <tr>
                <td>Rencana Suksesi</td>
                <td>
                    <input type="radio" <? if($tempRencanaSuksesi== '1') echo 'checked';?>  name="reqRencanaSuksesi" value="1" /> Lihat Semua Satker &nbsp;&nbsp;&nbsp;
                    <input type="radio" <? if($tempRencanaSuksesi== '0') echo 'checked';?> name="reqRencanaSuksesi" value="0" /> Tidak
                </td>        
            </tr>
            
           <?php /*?> <tr>           
                 <td>Menu</td>
                 <td>
                    <input type="hidden" id="reqTempListId" name="reqTempListId" value="<?=$tempList?>" />
                    <select id="reqMenu" name="reqMenu" multiple="multiple">
                    <option>aaaa</option>
                    </select>
                </td>			
            </tr><?php */?>
            <tr>           
                 <td>List Menu</td>
                 <td>
                    <input type="hidden" id="reqTempListId" name="reqTempListId" value="<?=$tempList?>" />
                    <select id="reqTempList" name="reqTempList" multiple="multiple"></select>
                </td>			
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="reqId" value="<?=$reqId?>">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                	<input type="submit" name="" value="Simpan" /> 
                    <input type="reset" name="" value="Reset" />
                </td>
            </tr> 
        </table>       
        </form>
        <script>
		$("#reqUrut").keypress(function(e) {
			//alert(e.which);
			if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			{
			return false;
			}
		});
		</script>
    </div>
</div>
</body>
<script>

function GetTagFillCombo(valTempId) {
	 jQuery.ajax({
		 type: "GET",
		 url: '../json-pengaturan/group_fip_multi_select.php',
		 data: '',
		 contentType: "application/json; charset=utf-8",
		 dataType: "json",
		 success: function(data){
			FillComboOnSuccess(data, valTempId)
		 },
		 failure: function (response1) {
		 alert(response.d);
		 jQuery("#imgSearchLoading").hide();
	 }
	 });
}

function FillComboOnSuccess(data, idTemp)
{
 var h1 = "";
 var $select = $("#"+idTemp);
 var valSelectedId= $("#"+idTemp+"Id").val();
 //alert(valSelectedId+'--');
 
 valSelectedId= String(valSelectedId);
 valSelectedId= valSelectedId.split(',');
 //alert(valSelectedId[0]+'--');
 
 for (j = 0; j < data.arrID.length; j++) 
 {
	 var valId= data.arrID[j];
	 var valNama= data.arrNama[j];
	 var indexValue= valSelectedId.indexOf(valId); 
	 var selectedValue="";
	 
	 if(indexValue >= 0)
	 	selectedValue= true;
	 else
	 	selectedValue= false;
		
	 $opt = $("<option />", {
		 value: valId,
		 text: valNama,
		 selected: selectedValue
	 });
	 
	 $select.append($opt).multipleSelect("refresh");
 }
 
}

$('select[id^="reqTempList"]').multipleSelect({
	width: 405,
	multiple: true,
	multipleWidth: 145
});

GetTagFillCombo('reqTempList');

$('select[id^="reqTempList"]').change(function() {
	var tempId= $(this).attr('id');
	var tempValueId= $('#'+tempId).multipleSelect("getSelects")
	$('#'+tempId+"Id").val(tempValueId);
});

</script>
</html>