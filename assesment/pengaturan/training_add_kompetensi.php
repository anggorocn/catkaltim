<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Training.php");

$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data Training terlebih dahulu.');";	
	echo "window.top.location.href = 'training_add.php?reqId=".$reqId."';";
	echo '</script>';
}

$set= new Training();
$set->selectByParams(array("TRAINING_ID"=> $reqId),-1,-1,'');
$set->firstRow();
$tempTahun= $set->getField("TAHUN");
//echo $user_group->query;exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<!-- jQuery Library -->
<script type="text/javascript" src="../WEB/lib/window/js/jquery/jquery.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/demo/demo.css">
<?php /*?><script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script><?php */?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1-4-2.easyui.min.js"></script>

<link id="jquery_ui_theme_loader" type="text/css" href="../WEB/lib/window/js/jquery/themes/black-tie/jquery-ui.css" rel="stylesheet" />
<link type="text/css" href="../WEB/lib/window/js/jquery/window/css/jquery.window.css" rel="stylesheet" />

<script type="text/javascript" src="../WEB/lib/window/js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="../WEB/lib/window/js/jquery/window/jquery.window.js"></script>
<script type="text/javascript">
	function reloadMe()
	{
		$('#tt').treegrid({url:'../json-pengaturan/training_add_kompetensi_tree.php?reqAspekId='+$('#reqAspekId').val()+'&reqTahun='+$("#reqTahun").val()});
	}
	
	$(function(){
		$('#reqAspekId, #reqTahun').bind('change', function(ev) {
			reloadMe();
		});	
	});
	
	//function pilihatribut(reqTahun, reqAtributId, reqmode)
	function pilihatribut(reqAtributId, reqmode)
	{
		var reqTahun= "";
		reqTahun= $("#reqTahun").val();
		var info= '';
		if(reqmode == 'hapus')
			info= 'Apakah anda ingin menghapus data terpilih?';
		else
			info= 'Apakah anda ingin simpan data terpilih?';
		//alert(reqJabatanEselonAtributId); return false;
		//$("#reqInfo"+reqJabatanEselonAtributId).hide();
		//return false;
		if(confirm(info) == false)
			return "";
		try {
			var jqxhr = $.get( "../json-pengaturan/training_add_kompetensi.php?reqId=<?=$reqId?>&reqMode="+reqmode+"&reqTahun="+reqTahun+"&reqAtributId="+reqAtributId, function(){
				arrChecked = [];
				$.messager.progress('close');
			})
			.done(function(info){
				if(info == "")
				{
					if(reqmode == 'hapus')
					{
						//$("#reqInfoSimpan"+reqTahun+"-"+reqAtributId).show();
						//$("#reqInfoHapus"+reqTahun+"-"+reqAtributId).hide();
						
						$("#reqInfoSimpan"+reqAtributId).show();
						$("#reqInfoHapus"+reqAtributId).hide();
					}
					else
					{
						//$("#reqInfoSimpan"+reqTahun+"-"+reqAtributId).hide();
						//$("#reqInfoHapus"+reqTahun+"-"+reqAtributId).show();
						
						$("#reqInfoSimpan"+reqAtributId).hide();
						$("#reqInfoHapus"+reqAtributId).show();
					}
				}
				else
				alert(info);
				
			})
			.fail(function() {
				arrChecked = [];
				alert( "error" );
			});
		}catch(e) {
			alert(e);
		}
	}
	
	function OpenDHTMLPopUp(opAddress) {
        $.window({
            showModal: true,
            modalOpacity: 0.6,
            title: "Lookup Data",
            url: opAddress,
            bookmarkable: false,
			showFooter: false,
            width: 600,
            height: 400
        });
		maximized = true;
    }
</script>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>
</head>

<body style="margin:0; width:100%; margin-left:-20px; margin-top:-20px; background-color:#FFF !important">
<div style="width:100%;">
    <div id="header-tna">
    Atribut <span>Tahun <?=$tempTahun?></span>
    <input type="hidden" id="reqTahun" value="<?=$tempTahun?>">
    Aspek
    <select id="reqAspekId">
    	<option value="">All</option>
    	<option value="1">Potensi</option>
        <option value="2">Kompetensi</option>
    </select>
    
    </div>
    <table id="tt" class="easyui-treegrid" style="width:100%;height:600px"
        data-options="
        url: '../json-pengaturan/training_add_kompetensi_tree.php?reqId=<?=$reqId?>&reqTahun='+$('#reqTahun').val()+'&reqAspekId='+$('#reqAspekId').val(),
        rownumbers: false,
        pagination: true,
        pageList: [100, 150],
        idField: 'ID',
        treeField: 'NAMA',
        onBeforeLoad: function(row,param){
        if (!row) { // load top level rows
        param.id = 0; // set id=0, indicate to load new page rows
        }
        }
        ">
        <thead>
            <tr>
                <th field="NAMA" width="70%">Nama</th>
                <th field="ASPEK_NAMA" width="15%">Aspek</th>
                <th field="LINK_URL" width="10%" align="right">Aksi</th>
            </tr>
        </thead>
    </table>
</div>
</body>
</html>