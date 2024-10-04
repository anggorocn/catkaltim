<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/PelatihanHcdp.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	
$tinggi = 213;

$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");

$atribut= new PelatihanHcdp();
if($reqMode == "delete")
{
    $atribut->setField("PELATIHAN_HCDP_ID", $reqId);
    if($atribut->delete())
    {
        echo '<script language="javascript">';
        echo "alert('Data berhasil dihapus.');";
        echo "document.location.href = '?reqId=0101&reqKeterangan=Semua Satuan Kerja';";
        echo '</script>';       
    }   
    else
    {
        echo '<script language="javascript">';
        echo "alert('Data tidak dapat dihapus, silahkan cek data lain yang berelasi dengan data ini.');";
        echo '</script>';               
    }
}

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
		$('#tt').treegrid({url:'../json-pengaturan/pelatihan_hcdp_tree.php'});
	}
	
	$(function(){
		$('#reqAspekId').bind('change', function(ev) {
			reloadMe();
		});	
	});
	
	function OpenDHTMLPopUp(opAddress) {
        $.window({
            showModal: true,
            modalOpacity: 0.6,
            title: "Modul Pengaturan",
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
<!-- <script type="text/javascript" src="css/dropdowntabs.js"></script> -->
</head>

<body style="margin:0; width:100%; margin-left:-20px; margin-top:-20px; background-color:#FFF !important">
<div style="width:100%;">
    <div id="header-tna">
     <span>Tipe Pelatihan</span>
    </div>
    <table id="tt" title="Tipe Pelatihan" class="easyui-treegrid" style="width:100%;height:600px"
        data-options="
        url: '../json-silat/pelatihan_hcdp_tree.php',
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
                <th field="NAMA" width="90%">
                Nama
                <a onClick="window.OpenDHTMLPopUp('pelatihan_hcdp_add.php?reqPelatihanHcdpParentId=0')"><img src="../WEB/images/icn_add.png"></a>
                </th>
                <th field="LINK_URL" width="10%" align="right">Aksi</th>
            </tr>
        </thead>
    </table>
</div>
</body>
</html>