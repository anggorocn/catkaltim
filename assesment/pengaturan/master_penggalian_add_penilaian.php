<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Penggalian.php");

$reqId= httpFilterGet("reqId");

$set= new Penggalian();
$set->selectByParams(array("PENGGALIAN_ID"=> $reqId),-1,-1,'');
$set->firstRow();
$tempTahun= $set->getField('TAHUN');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title></title>

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
	$(function(){
		$("#reqAspekId").change(function() {
			reloadMe();
		});
	});
	
	function reloadMe()
	{
		$('#tt').treegrid({url:'../json-pengaturan/master_penggalian_add_penilaian_tree.php?reqId=<?=$reqId?>&reqAspekId='+$("#reqAspekId").val()+'&reqTahun='+$("#reqTahun").val()});  
	}
	
	
	function pilihatribut(reqJabatanEselonAtributId, reqmode)
	{
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
			var jqxhr = $.get( "../json-pengaturan/master_penggalian_add_penilaian.php?reqMode="+reqmode+"&reqId=<?=$reqId?>&reqJabatanEselonAtributId="+reqJabatanEselonAtributId, function() {
				arrChecked = [];
				$.messager.progress('close'); 
			})
			.done(function() {
				if(reqmode == 'hapus')
				{
					$("#reqInfoSimpan"+reqJabatanEselonAtributId).show();
					$("#reqInfoHapus"+reqJabatanEselonAtributId).hide();
				}
				else
				{
					$("#reqInfoSimpan"+reqJabatanEselonAtributId).hide();
					$("#reqInfoHapus"+reqJabatanEselonAtributId).show();
				}
			
				
				
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

<body style="margin:0; width:100%; margin-left:-20px; margin-top:-20px">
<div style="width:100%;">
    <div id="header-tna">
    Master <span>Jabatan Atribut</span>
    Tahun <?=$tempTahun?>
    <input type="hidden" id="reqTahun" value="<?=$tempTahun?>">
    Aspek
    <select id="reqAspekId">
    	<option value="">Semua</option>
        <option value="1">Potensi</option>
        <option value="2">Kompetensi</option>
    </select>
    </div>
    <table id="tt" title="Jabatan Atribut" class="easyui-treegrid" style="width:100%;height:600px"
        data-options="
        url: '../json-pengaturan/master_penggalian_add_penilaian_tree.php?reqId=<?=$reqId?>&reqAspekId=&reqTahun='+$('#reqTahun').val(),
        rownumbers: false,
        pagination: true,
        pageSize: 2,
        pageList: [5,10,20],
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
                <th field="NAMA" width="85%">Nama</th>
                <th field="LINK_URL" width="10%" align="right">Aksi</th>
            </tr>
        </thead>
    </table>
</div>
</body>
</html>