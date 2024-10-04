<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/Atribut.php");

$reqTahun= httpFilterGet("reqTahun");
$reqPegawaiId = httpFilterGet("reqPegawaiId");
$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");
$reqKeterangan = httpFilterGet("reqKeterangan");
$reqCari = httpFilterGet("reqCari");

//echo 'tes'.$reqCari;
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	
$tinggi = 213;

$set_tahun= new Atribut();
$set_tahun->selectByParamsCombo(array(), -1,-1, "", "A.TAHUN");
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
	function reloadMe()
	{
		$('#tt').treegrid({url:'../json-pengaturan/jabatan_atribut_tree.php?reqAspekId=1&reqTahun='+$("#reqTahun").val()});  
	}
	
	function pilihatribut(reqEselonId, reqSatuanKerjaId, reqTahun, reqAtributId)
	{
		if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
			return "";
				
		try {
			var jqxhr = $.get( "../json-pengaturan/jabatan_atribut_tree_simpan.php?reqMode=hapus&reqTahun="+reqTahun+"&reqEselonId="+reqEselonId+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqAtributId="+reqAtributId, function() {
				arrChecked = [];
				$.messager.progress('close'); 
			})
			.done(function() {
				reloadMe();
			})
			.fail(function() {
				arrChecked = [];
				alert( "error" );
			});
		}catch(e) {
			alert(e);
		}
	}
	
	$(function(){
		$('#reqTahun').bind('change', function(ev) {
			reloadMe();
		});	
	});
	
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
    Master <span>Jabatan Atribut Potensi</span>
    Tahun 
    <select id="reqTahun">
    	<option value="">All</option>
        <?
		while($set_tahun->nextRow())
		{
        ?>
        <option value="<?=$set_tahun->getField("TAHUN")?>"><?=$set_tahun->getField("TAHUN")?></option>
        <?
		}
        ?>
    </select>
    </div>
    <table id="tt" title="Jabatan Atribut" class="easyui-treegrid" style="width:100%;height:600px"
        data-options="
        url: '../json-pengaturan/jabatan_atribut_tree.php?reqAspekId=1',
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