<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/MasterJabatan.php");

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

$atribut= new MasterJabatan();
if($reqMode == "delete")
{
    $atribut->setField("JABATAN_ID", $reqId);
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

<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

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
		$('#tt').treegrid({url:'../json-suksesi/master_jabatan_tree.php?reqAspekId='+$('#reqAspekId').val()});
	}
	
	$(function(){
		$('#reqAspekId').bind('change', function(ev) {
			reloadMe();
		});	
	});

    function iecompattest(){
        return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
    }

    function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
    {
        var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
        var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
        
        opWidth = iecompattest().clientWidth - 400;
        opHeight = iecompattest().clientHeight - 100;
        divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
    }

	
	function OpenDHTMLCheck(opAddress, opCaption, opWidth, opHeight) {
        // console.log(opWidth);
        $.window({
            showModal: false,
            modalOpacity: 0.6,
            title: opCaption,
            url: opAddress,
            bookmarkable: false,
            showFooter: false,
            width: opWidth,
            height: opHeight
           //  onShow: function(wnd) {  // a callback function while container is added into body
           //    $(".maximizeImg").click();
           // }
        });
        maximized = false;
    }

    function OpenDHTMLDetil(opAddress, opCaption, opWidth, opHeight)
    {
        var left = (screen.width/2)-(opWidth/2);
        var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
        divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
    }


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
<script type="text/javascript" src="css/dropdowntabs.js"></script>
</head>

<body style="margin:0; width:100%; margin-left:-20px; margin-top:-20px; background-color:#FFF !important">
<div style="width:100%;">
    <div id="header-tna">
     <span>Master Jabatan</span>
    
    </div>
    <table id="tt" title="Master Jabatan" class="easyui-treegrid" style="width:100%;height:600px"
        data-options="
        url: '../json-suksesi/master_jabatan_tree.php',
        rownumbers: false,
        pagination: true,
        pageList: [100, 150],
        idField: 'ID',
        treeField: 'NAMA_JABATAN',
        onBeforeLoad: function(row,param){
        if (!row) { // load top level rows
        param.id = 0; // set id=0, indicate to load new page rows
        }
        }
        ">
        <thead>
            <tr>
                <th field="NAMA_JABATAN" width="70%">
                Nama
                <a onClick="window.OpenDHTMLCheck('master_jabatan_add.php?reqMasterJabatanParentId=0', 'Tambah Jabatan', 1000, 500)"><img src="../WEB/images/icn_add.png"></a>
                </th>
                <th field="LINK_URL" width="30%" align="center">Aksi</th>
            </tr>
        </thead>
    </table>
</div>
</body>
</html>