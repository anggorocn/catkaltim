<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

$reqAspekId= httpFilterGet("reqAspekId");
$reqTahun= httpFilterGet("reqTahun");
$reqEselonId= httpFilterGet("reqEselonId");
$reqSatuanKerjaId= httpFilterGet("reqSatuanKerjaId");

//echo 'tes'.$reqCari;
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title></title>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/demo/demo.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1-4-2.easyui.min.js"></script>

<script type="text/javascript">
	function setReloadParent()
	{
		parent.reloadMe();
	}
	
	function pilihatribut(id)
	{
		$.messager.confirm('Konfirmasi', "Apakah anda yakin pilih data ?",function(r){
			if (r){
				var win = $.messager.progress({
									title:'Proses Hapus Data',
									msg:'Proses data...'
				});
				
				var jqxhr = $.get( "../json-pengaturan/jabatan_atribut_tree_simpan.php?reqTahun=<?=$reqTahun?>&reqAspekId=<?=$reqAspekId?>&reqEselonId=<?=$reqEselonId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqAtributId="+id, function() {
					arrChecked = [];
					$.messager.progress('close'); 
				})
				.done(function() {
					$.messager.progress('close');
					arrChecked = [];
					setReloadParent();
					document.location.href= "jabatan_atribut_tree_lookup.php?reqTahun=<?=$reqTahun?>&reqAspekId=<?=$reqAspekId?>&reqEselonId=<?=$reqEselonId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>";
				})
				.fail(function() {
					arrChecked = [];
					alert( "error" );
					$.messager.progress('close');
				});								
			}
		});
		//alert(id);
	}
</script>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>
</head>

<body style="margin:0; width:100%; margin-left:-20px; margin-top:-20px">
<div style="width:100%;">
    <div id="header-tna">Master <span>Jabatan Atribut</span></div>
    <table id="tt" title="Jabatan Atribut" class="easyui-treegrid" style="width:100%;height:600px"
        data-options="
        url: '../json-pengaturan/jabatan_atribut_tree_lookup.php?reqTahun=<?=$reqTahun?>&reqAspekId=<?=$reqAspekId?>&reqEselonId=<?=$reqEselonId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>',
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