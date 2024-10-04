<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelatihanHcdp.php");

$set= new PelatihanHcdp();

$reqPelatihanHcdpParentId= httpFilterGet("reqPelatihanHcdpParentId");
$reqPelatihanHcdpId= httpFilterGet("reqPelatihanHcdpId");
$reqTahun= httpFilterGet("reqTahun");

if($reqPelatihanHcdpId == "")
{
	$reqMode = "insert";
	$statement= " AND A.PELATIHAN_HCDP_ID = '".$reqPelatihanHcdpParentId."'".$statement_tahun;
	$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM permen WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

	$set->selectByParams(array(), -1,-1, $statement);
	//echo $set->query;exit;
	$set->firstRow();
	$tempKet= $set->getField("KETERANGAN");
	
	$statement= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM permen WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";
	$set_parent= new PelatihanHcdp();
	$set_parent->selectByParams(array('A.PELATIHAN_HCDP_ID'=>$reqPelatihanHcdpParentId), -1, -1, $statement);
	$set_parent->firstRow();
	$tempPelatihanHcdpParent= $set_parent->getField("NAMA");
}
else
{
	$reqMode = "update";
	$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM permen WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

	$set->selectByParams(array('A.PELATIHAN_HCDP_ID'=>$reqPelatihanHcdpId), -1, -1, $statement.$statement_tahun);
	$set->firstRow();
	
	$reqTahun= $set->getField("TAHUN");
	$reqPelatihanHcdpParentId= $set->getField("PELATIHAN_HCDP_ID_PARENT");
	$tempNama= $set->getField("NAMA");
	$tempKet= $set->getField("KETERANGAN");
	$tempBobot= $set->getField("BOBOT");
	$tempNilaiStandar= $set->getField("NILAI_STANDAR");
	$tempNilaiEs2= $set->getField("NILAI_ES2");
	$tempNilaiEs3= $set->getField("NILAI_ES3");
	$tempNilaiEs4= $set->getField("NILAI_ES4");
	
	$set_parent= new PelatihanHcdp();
	$set_parent->selectByParams(array('A.PELATIHAN_HCDP_ID'=>substr($reqPelatihanHcdpParentId, 0, 2)), -1, -1, $statement_tahun);
	$set_parent->firstRow();
	$tempPelatihanHcdpParent= $set_parent->getField("NAMA");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<!-- <link href="styles.css" rel="stylesheet" type="text/css" /> -->

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<!-- <script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script> -->
<script type="text/javascript">	
	$(function(){
		$('#ff').form({
			url:'../json-silat/pelatihan_hcdp_add.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// alert(data);return false;
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
<!-- <div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div> -->
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
    	<table class="table_list" cellspacing="1" width="100%">
            <tr>
                <td colspan="6">
                <div id="header-tna-detil">Master <span>Tipe Pelatihan</span></div>
                </td>			
            </tr>
            <?
			if($tempPelatihanHcdpParent == ""){}
			else
			{
            ?>
            <tr>
                <td>Nama Parent</td><td>:</td>
                <td><?=$tempPelatihanHcdpParent?></td>
            </tr>
            <?
			}
            ?>
            <tr>
                <td>Nama</td><td>:</td>
                <td><input id="reqNama" name="reqNama" class="easyui-validatebox" required style="width:70%" type="text" value="<?=$tempNama?>" /></td>
            </tr>
             <tr>
                <td>Keterangan</td><td>:</td>
               <td><textarea id="reqKet" name="reqKet" style="width:80%" row="5"><?=$tempKet?></textarea></td>
            </tr>
            <tr>
                <td>
                	<input type="hidden" name="reqTahun" value="<?=$reqTahun?>">
                    <input type="hidden" name="reqPelatihanHcdpParentId" value="<?=$reqPelatihanHcdpParentId?>">
                    <input type="hidden" name="reqPelatihanHcdpId" value="<?=$reqPelatihanHcdpId?>">
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
</html>