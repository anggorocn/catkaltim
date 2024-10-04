<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/MasterJabatan.php");
include_once("../WEB/classes/base-simpeg/Eselon.php");

$set= new MasterJabatan();

$reqMasterJabatanParentId= httpFilterGet("reqMasterJabatanParentId");
$reqMasterJabatanId= httpFilterGet("reqMasterJabatanId");
$reqTahun= httpFilterGet("reqTahun");

if($reqMasterJabatanId == "")
{
	$reqMode = "insert";
	$statement= " AND A.JABATAN_ID = '".$reqMasterJabatanParentId."'".$statement_tahun;
	$set->selectByParams(array(), -1,-1, $statement);
	//echo $set->query;exit;
	$set->firstRow();
	// $tempKode= $set->getField("KODE_JABATAN");
	
	$set_parent= new MasterJabatan();
	$set_parent->selectByParams(array('A.JABATAN_ID'=>$reqMasterJabatanParentId), -1, -1, $statement);
	$set_parent->firstRow();
	$tempMasterJabatanParent= $set_parent->getField("NAMA_JABATAN");
}
else
{
	$reqMode = "update";

	$set->selectByParams(array('A.JABATAN_ID'=>$reqMasterJabatanId), -1, -1, $statement.$statement_tahun);
	// echo $set->query;exit;
	$set->firstRow();
	
	$reqMasterJabatanParentId= $set->getField("JABATAN_ID_PARENT");
	$tempNama= $set->getField("NAMA_JABATAN");
	$tempKode= $set->getField("KODE_JABATAN");
    $tempRumpunId= $set->getField("RUMPUN_ID");
    $tempRumpun= $set->getField("NAMA_RUMPUN");
    $tempEselon= $set->getField("NAMA_ESELON");
    $tempEselonId= $set->getField("ESELON_ID");
    $tempSatkerId= $set->getField("SATKER_ID");
    $tempSatker= $set->getField("NAMA_SATKER");
	
	$set_parent= new MasterJabatan();
	$set_parent->selectByParams(array('A.JABATAN_ID'=>substr($reqMasterJabatanParentId, 0, 2)), -1, -1, $statement_tahun);
	$set_parent->firstRow();
	$tempMasterJabatanParent= $set_parent->getField("NAMA_JABATAN");
}

$set_eselon= new Eselon();
$set_eselon->selectByParams();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />


<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>


<script type="text/javascript">	
	$(function(){
		$('#ff').form({
			url:'../json-suksesi/master_jabatan_add.php',
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
					$.messager.alert('Info', infodata, 'info');
				}
				else
				{
					$.messager.alert('Info', infodata, 'info');
					top.frames['mainFullFrame'].location.reload();
					<? if($reqMode == "update") { ?>
						window.parent.divwin.close();
					<? } ?>
					/*$.messager.alert('Info',infodata,'info',function(){
						$('#rst_form').click();
						top.frames['mainFullFrame'].location.reload();
						<? if($reqMode == "update") { ?>
							window.parent.divwin.close();
						<? } ?>
					});*/
					// $.messager.alert('Info', infodata, 'info');
				}
			}
		});
		
	});
</script>
<script type="text/javascript">

    function OpenPopup(opAddress, opCaption, opWidth, opHeight)
    {
        var left = (screen.width/2)-(opWidth/2) - 170;
        console.log(left);
        var top = (screen.height/2)-(opHeight/2) - 100;

        divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
    }
    function openPencarianRumpun()
    {
    	OpenPopup('rumpun_pencarian.php?reqId=<?=$reqId?>', 'Pencarian Rumpun', 780, 400);
    }


    function openPencarianSatker()
    {
    	OpenPopup('satker_pencarian.php?reqId=<?=$reqId?>', 'Pencarian Satker', 780, 400);
    }

    function OptionSetRumpun(id, nama){
        console.log(id);
    	document.getElementById('reqRumpunId').value = id;
    	document.getElementById('reqRumpun').value = nama;
    }

    function OptionSetSatker(id, nama){
        console.log(id);
        document.getElementById('reqSatkerId').value = id;
        document.getElementById('reqSatker').value = nama;
    }
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
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
    	<table class="table_list" cellspacing="1" width="100%">
            <tr>
                <td colspan="6">
                <div id="header-tna-detil">Master <span>Jabatan</span></div>
                </td>			
            </tr>
            
            <?
			if($tempMasterJabatanParent == ""){}
			else
			{
            ?>
            <tr>
                <td>Master Jabatan </td><td>:</td>
                <td><?=$tempMasterJabatanParent?></td>
            </tr>
            <?
			}
            ?>
            <tr>
               <td>Kode</td><td>:</td>
             
                <td><input id="reqKet" name="reqKet" class="easyui-validatebox"  style="width:70%" type="text" value="<?=$tempKode?>" /></td>
            </tr>
            <tr>
                <td>Nama</td><td>:</td>
                  <td><textarea id="reqNama" name="reqNama" style="width:80%" row="5"><?=$tempNama?></textarea></td>
            </tr>
            <tr>
            	<td>Eselon</td><td>:</td>
            	<td>
            		<select id="reqEselonId" name="reqEselonId">
            			<option selected="selected" value="">Semua</option>
            			<?
            			while($set_eselon->nextRow())
            			{
            				?>
                            <option value="<?=$set_eselon->getField('ESELON_ID')?>"
                                <? if ($set_eselon->getField('ESELON_ID') == $tempEselonId) echo 'selected'?>><?=$set_eselon->getField('NAMA')?>
                            </option>

            				<?
            			}
            			?>
            		</select>
            	</td>
            </tr>
            <tr>
            	<td>Rumpun Jabatan</td><td>:</td>
            	<td>
            		<input type="hidden" name="reqRumpunId" id="reqRumpunId" value="<?=$tempRumpunId?>">
            		<input type="text" style="width:80%"  class="easyui-validatebox"  name="reqRumpun" id="reqRumpun" value="<?=$tempRumpun?>" />
            		<img src="../WEB/images/icn_search.png" onClick="openPencarianRumpun()" style="cursor:pointer" />
            	</td>
            </tr>
            <tr>
            	<td>Satuan Kerja</td><td>:</td>
            	<td>
            		<input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$tempSatkerId?>">
            		<input type="text" style="width:80%"  class="easyui-validatebox"  name="reqSatker" id="reqSatker" value="<?=$tempSatker?>" />
            		<img src="../WEB/images/icn_search.png" onClick="openPencarianSatker()" style="cursor:pointer" />
            	</td>
            </tr>
            <tr>
                <td>
                	<input type="hidden" name="reqTahun" value="<?=$reqTahun?>">
                    <input type="hidden" name="reqMasterJabatanParentId" value="<?=$reqMasterJabatanParentId?>">
                    <input type="hidden" name="reqMasterJabatanId" value="<?=$reqMasterJabatanId?>">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                	<input type="submit" name="" value="Simpan" /> 
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
<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>
<link id="jquery_ui_theme_loader" type="text/css" href="../WEB/lib/window/js/jquery/themes/black-tie/jquery-ui.css" rel="stylesheet" />
<link type="text/css" href="../WEB/lib/window/js/jquery/window/css/jquery.window.css" rel="stylesheet" />

<!-- <script type="text/javascript" src="../WEB/lib/window/js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="../WEB/lib/window/js/jquery/window/jquery.window.js"></script> -->

</html>