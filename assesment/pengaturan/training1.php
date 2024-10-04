<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Penggalian.php");

$arrDetil="";
$index_detil= 0;
$set= new Penggalian();
$set->selectByParamsTahun(array(),-1,-1,'');
while($set->nextRow())
{
	$arrDetil[$index_detil]["TAHUN"]= $set->getField("TAHUN");
	$index_detil++;
}
$jumlah_tahun= $index_detil;

if($jumlah_tahun > 0)
	$tempTahun= $arrDetil[0]["TAHUN"]

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
		$("#reqTahun").change(function() {
			reloadMe();
		});
	});
	
	function reloadMe()
	{
		$('#tt').treegrid({url:'../json-pengaturan/training_json.php?reqTahun='+$("#reqTahun").val()});
	}
	
	
	function pilihatribut(reqJabatanEselonAtributId, reqTahun, reqAtributId, reqPenggalianId, reqmode)
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
			var jqxhr = $.get( "../json-pengaturan/training_add.php?reqMode="+reqmode+"&reqJabatanEselonAtributId="+reqJabatanEselonAtributId+"&reqTahun="+reqTahun+"&reqAtributId="+reqAtributId+"&reqPenggalianId="+reqPenggalianId, function() {
				arrChecked = [];
				$.messager.progress('close'); 
			})
			.done(function(info) {
				if(info == "")
				{
					if(reqmode == 'hapus')
					{
						$("#reqInfoSimpan"+reqJabatanEselonAtributId+"-"+reqTahun+"-"+reqAtributId+"-"+reqPenggalianId).show();
						$("#reqInfoHapus"+reqJabatanEselonAtributId+"-"+reqTahun+"-"+reqAtributId+"-"+reqPenggalianId).hide();
					}
					else
					{
						$("#reqInfoSimpan"+reqJabatanEselonAtributId+"-"+reqTahun+"-"+reqAtributId+"-"+reqPenggalianId).hide();
						$("#reqInfoHapus"+reqJabatanEselonAtributId+"-"+reqTahun+"-"+reqAtributId+"-"+reqPenggalianId).show();
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
    Master <span>Training</span>
    Tahun 
    <select id="reqTahun" style="display:none">
    	<?
		for($checkbox_index=0; $checkbox_index < $jumlah_tahun; $checkbox_index++)
		{
			$reqTahun= $arrDetil[$checkbox_index]["TAHUN"];
        ?>
        	<option value="<?=$reqTahun?>" <? if($reqTahun == $tempTahun) echo "selected"?>><?=$reqTahun?></option>
        <?
		}
        ?>
    </select>
    </div>
    <table id="tt" title="Training" class="easyui-treegrid" style="width:100%;height:600px"
        data-options="
        url: '../json-pengaturan/training_json.php?reqTahun='+$('#reqTahun').val(),
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
                <th field="NAMA" width="30%">Nama</th>
                <th field="LINK_URL" width="70%" align="right">Aksi</th>
            </tr>
        </thead>
    </table>
</div>
</body>
</html>