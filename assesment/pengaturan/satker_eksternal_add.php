<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Satker.php");

$set= new Satker();

$reqSatkerParentId= httpFilterGet("reqSatkerParentId");
$reqSatkerId= httpFilterGet("reqSatkerId");
$reqTahun= httpFilterGet("reqTahun");


$statement= "";
if($reqSatkerId == "")
{
	$reqMode = "insert";

	$set_parent= new Satker();
	$set_parent->selectByParamsSatuanKerjaEkternal(array('A.SATKER_EKSTERNAL_ID'=>$reqSatkerParentId), -1, -1, $statement);
	$set_parent->firstRow();
	$tempAtributParent= $set_parent->getField("NAMA");
}
else
{
	$reqMode = "update";

	$set->selectByParamsSatuanKerjaEkternal(array('A.SATKER_EKSTERNAL_ID'=>$reqSatkerId), -1, -1, $statement.$statement_tahun);
	// echo $set->query;exit;
	$set->firstRow();
	
	$reqTahun= $set->getField("TAHUN");
	$reqSatkerParentId= $set->getField("SATKER_EKSTERNAL_ID_PARENT");

	$tempNama= $set->getField("NAMA");


	$set_parent= new Satker();
	$set_parent->selectByParamsSatuanKerjaEkternal(array('A.SATKER_EKSTERNAL_ID'=>substr($reqSatkerParentId, 0, 2)), -1, -1,  $statement);
	$set_parent->firstRow();
	$tempAtributParent= $set_parent->getField("NAMA");
	// echo $set_parent->query;exit;
}

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
			url:'../json-pengaturan/satker_add.php',
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
				}
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
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
    	<table class="table_list" cellspacing="1" width="100%">
            <tr>
                <td colspan="6">
                <div id="header-tna-detil">Master <span>Satuan Kerja Eksternal</span></div>
                </td>			
            </tr>
            <?
			if($tempAtributParent == ""){}
			else
			{
            ?>
            <tr>
                <td>Satker</td><td>:</td>
                <td><?=$tempAtributParent?></td>
            </tr>
            <?
			}
            ?>
            <tr>
                <td>Nama</td><td>:</td>
                <td><input id="reqNama" name="reqNama" class="easyui-validatebox" required style="width:70%" type="text" value="<?=$tempNama?>" /></td>
            </tr>
            <tr>
                <td>
                	<input type="hidden" name="reqTahun" value="<?=$reqTahun?>">
                    <input type="hidden" name="reqSatkerParentId" value="<?=$reqSatkerParentId?>">
                    <input type="hidden" name="reqSatkerId" value="<?=$reqSatkerId?>">
                    <input type="hidden" name="reqTipe" value="eksternal">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                	<input type="submit" name="" value="Simpan" /> 
                </td>
            </tr> 
        </table>       
        </form>
    </div>
</div>
</body>
</html>