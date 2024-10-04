<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	
$tinggi = 213;
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
		$('#tt').treegrid({url:'../json-pengaturan/indikator_penilaian_tree.php?reqAspekId='+$('#reqAspekId').val()});
	}
	
	$(function(){
		$('#reqAspekId').bind('change', function(ev) {
			reloadMe();
		});	
	});
	
	function iecompattest(){
	return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
	}

	function OpenDHTMLPopUp(opAddress) {
		//alert(iecompattest().clientHeight);return false;
		var valHeight= iecompattest().clientHeight - 50;
        $.window({
            showModal: true,
            modalOpacity: 0.6,
			center:"yes",
            title: "Modul Pengaturan",
            url: opAddress,
            bookmarkable: false,
			showFooter: false
			//, width: 600
            ,height: valHeight
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
    <span>Indikator Penilaian</span>
    <?php /*?>Aspek
    <select id="reqAspekId">
    	<option value="">All</option>
    	<option value="1">Potensi</option>
        <option value="2">Kompetensi</option>
    </select><?php */?>
    <input type="hidden" id="reqAspekId" value="2" />
    </div>
    <table id="tt" title="Indikator Penilaian" class="easyui-treegrid" style="width:100%;height:600px"
        data-options="
        url: '../json-pengaturan/indikator_penilaian_tree.php?reqAspekId='+$('#reqAspekId').val(),
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