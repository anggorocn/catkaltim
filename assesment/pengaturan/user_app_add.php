<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UsersBase.php");
include_once("../WEB/classes/base/UserGroupsBase.php");

$set= new UsersBase();

$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode = "insert";	
}
else
{
	$reqMode = "update";	
	$set->selectByParamsMonitoring(array('A.USER_APP_ID'=>$reqId), -1, -1);
	$set->firstRow();
	
	$tempUserLogin		= $set->getField("USER_LOGIN");
	$tempUserGroup 		= $set->getField("USER_GROUP_ID");
	$tempNama 			= $set->getField("NAMA");
	$tempPegawaiId		= $set->getField("PEGAWAI_ID");
	$tempPegawaiNama	= $set->getField("PEGAWAI_NAMA");
	$tempSatkerId		= $set->getField("SATKER_ID");
	$tempSatkerIdTambahan	= $set->getField("SATKER_ID_TAMBAHAN");	
	$tempSatkerNama			= $set->getField("SATKER_NAMA");
	$tempSatkerNamaTambahan	= 	$set->getField("SATKER_NAMA_TAMBAHAN");
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
			url:'../json-pengaturan/user_app_add.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);
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
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script> 
<script type="text/javascript">
    function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
    {
		var width  = opWidth;
		var height = opHeight;
		var left   = (screen.width  - width)/2;
		var top    = (screen.height - height)/2;
		var params = 'width='+width+', height='+height;
		params += ', top='+top+', left='+left;
		params += ', directories=no';
		params += ', location=no';
		params += ', menubar=no';
		params += ', resizable=no';
		params += ', scrollbars=no';
		params += ', status=no';
		params += ', toolbar=no';
		divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
    }
	
	function openPencarianPegawaiBak()
    {
        OpenDHTML('pegawai_pencarian.php', 'Pencarian Pegawai', 1500, 600);	
    }
	
	function openPencarianPegawai()
    {
        OpenDHTML('asesor_pencarian.php', 'Pencarian Pegawai', 800, 400);	
    }
    
	function OptionSetPegawai(id){
        $.getJSON('../json-pengaturan/asesor_pencarian_info.php?reqId=' + id,
          function(data){
            reqPegawai=data.reqPegawai;
            reqPegawaiId=data.reqPegawaiId;
            if(data.reqPegawaiId == ""){}
            else
            {
				$('#reqPegawaiId').val(reqPegawaiId);
				$('#reqPegawaiNama').val(reqPegawai);
            }
            
        });
    }
	
    function OptionSetPegawaiBak(id){
        $.getJSON('../json-pengaturan/pegawai_pencarian_info.php?reqPegawaiId=' + id,
          function(data){
            reqPegawai=data.reqPegawai;
            reqPegawaiId=data.reqPegawaiId;
            if(data.reqPegawaiId == ""){}
            else
            {
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
	
	function openPencarianSatkerTambahan()
    {
        OpenDHTML('satuan_kerja_pencarian_tambahan.php', 'Pencarian Satuan Kerja', 800, 600);	
    }
    
    function OptionSetSatkerTambahan(id){
        $.getJSON('../json-pengaturan/satuan_kerja_pencarian_info.php?reqSatkerId=' + id,
          function(data){
            reqSatkerNamaTambahan=data.reqSatker;
            reqSatkerIdTambahan=data.reqSatkerId;
            if(data.reqSatkerIdTambahan == ""){}
            else
            {
				$('#reqSatkerIdTambahan').val(reqSatkerIdTambahan);
				$('#reqSatkerNamaTambahan').val(reqSatkerNamaTambahan);
            }
            
        });
    }
	
    </script> 
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
    	<table class="table_list" cellspacing="1" width="100%">
            <tr>
                <td colspan="6">
                <div id="header-tna-detil">Master <span>User</span></div>	                    
                </td>			
            </tr>
            <tr>
                <td>User Group</td>
                <td>
                    <select name="reqUserGroup">
                        <?
                        while($user_group->nextRow())
                        {
                        ?>
                            <option value="<?=$user_group->getField("USER_GROUP_ID")?>" <? if($tempUserGroup == $user_group->getField("USER_GROUP_ID")) { ?> selected <? } ?>><?=$user_group->getField("NAMA")?></option>
                        <?
                        }
                        ?>	
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>
                    <input id="reqNama" name="reqNama" class="easyui-validatebox" required size="50" type="text" value="<?=$tempNama?>" />
                </td>
            </tr>
            <tr>
            <td>User Login</td>
                <td>
                    <input type="hidden" name="reqUserLoginTemp" id="reqUserLoginTemp" value="<?=$tempUserLogin?>" >
                    <input name="reqUserLogin" id="reqUserLogin" class="easyui-validatebox" validType="existUserLogin['#reqUserLoginTemp']" required size="20" type="text" value="<?=$tempUserLogin?>" />
                </td>
            </tr>
            <?
            if($reqMode == "insert")
            {
            ?>
            <tr>
                <td>User Password <input type="checkbox" title="Lihat Password" id="reqIdLihatPassword" /></td>
                <td>
                    <input name="reqUserPassword" id="reqUserPassword" class="easyui-validatebox" required size="60" type="password"  />
                </td>
            </tr>
            <?
            }
            ?>
            <tr>
            	<td>Asesor</td>
                <td>
                	<input type="hidden" id="reqPegawaiId" name="reqPegawaiId" value="<?=$tempPegawaiId?>" />
                    <input id="reqPegawaiNama" name="reqPegawaiNama" class="easyui-validatebox" size="50" type="text" value="<?=$tempPegawaiNama?>" />
                    <img src="../WEB/images/icn_search.gif" onClick="openPencarianPegawai()">
                </td>
            </tr>
            <tr style="display:none">
            	<td>Satker</td>
                <td>
                	<input type="hidden" id="reqSatkerId" name="reqSatkerId" value="<?=$tempSatkerId?>" />
                    <textarea id="reqSatkerNama" name="reqSatkerNama" cols="50" rows="2" required readonly="readonly"><?=$tempSatkerNama?></textarea>
                    <img src="../WEB/images/icn_search.gif" onClick="openPencarianSatker()">
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
</html>