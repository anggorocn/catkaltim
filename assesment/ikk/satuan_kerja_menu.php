<?
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: menu.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: menu page for admin's section
***************************************************************************************************** */

	include_once("../WEB/classes/utils/UserLogin.php");
	include_once("../WEB/classes/base/Satker.php");
	include_once("../WEB/functions/string.func.php");
	include_once("../WEB/functions/default.func.php");
	
	$satker = new Satker();
	
	/* LOGIN CHECK */
	if ($userLogin->checkUserLogin()) 
	{ 
		$userLogin->retrieveUserInfo();
	}

	ini_set("memory_limit","800M");
	ini_set('max_execution_time', 7200);	

	$submitCari = httpFilterPost("submitCari");
	$reqSearchSatker = httpFilterPost("reqSearchSatker");
	$reqMode = httpFilterGet("reqMode");
	$reqStatus= httpFilterGet("reqStatus");
	
	switch($reqMode)
	{					
		case "monitoring" :
			$link = "pegawai.php";
			break;		
		
		
	}
	
	$link = 'monitoring.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Destroydrop &raquo; Javascripts &raquo; Tree</title>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/icon.css">
<!-- <link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/demo/demo.css"> -->
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1-4-2.easyui.min.js"></script>

<script type="text/javascript">

var gAksi = '';
var gMode = '';

function refreshTree()
{
	$('#treeSatker').treegrid({
      	url:'../json-ikk/satker_tree_json.php'
	});
}

function reloadTree()
{
	$('#treeSatker').treegrid('reload');	
}

$(document).ready( function () {
	$('#treeSatker').treegrid({
		  onClickRow: function(param){
			  parent.$("#idMainFrame").attr("src", "<?=$link?>?reqId=" + param.ID_TABLE+"&reqStatus=<?=$reqStatus?>");
		  }
	});
});
</script>
<style>
html, body{height:100%;}
body{ margin:0 0; padding:0 0; overflow:hidden;}
</style>

<style>

.datagrid-header-rownumber,
.datagrid-cell-rownumber {
	display:none;
}
.datagrid-pager.pagination{
	height:0px;
}

/** VALSIX **/
/*
.datagrid-body{
	overflow-x:auto;
}*/
.datagrid-cell{
	/*background:#0FC;*/
	*overflow:auto;
	overflow:hidden;
	*border:2px solid #933;
	*width:300px;
}
table.datagrid-btable{
	/*border:1px solid green;*/
	overflow:scroll;
	width:200px;
}
table.datagrid-btable tbody{
	/*border:12px solid red;*/
	overflow:scroll;
}

.tree-icon{
	display:none;
}
</style>

</head>

<!--<body style="background-image:url(images/wall-kiri.jpg); background-attachment:fixed; margin-left:-210px; width:300px;">-->
<body style="background-image:url(../WEB/images/wall-kiri.jpg); background-attachment:fixed; width:240px;">
<!--<body style="background-image:url(images/wall-kiri.jpg); background-attachment:fixed;">-->
<style type="text/css">
.tree-icon{
	display:none;
}
</style>
<!--<div id="content" style="margin-left:-16px; border:2px solid red;">-->

    <table 
        id="treeSatker"
        class="easyui-treegrid" 
        style="width:100%;height:100%;"
        data-options="
        url: '../json-ikk/satker_tree_json.php?reqStatus=<?=$reqStatus?>',
        rownumbers: false,
        pagination: false,
        pageSize: 2,
        pageList: [1000],
        idField: 'ID',
        treeField: 'NAMA_WARNA', 
        onBeforeLoad: function(row,param){
            if (!row) { // load top level rows
                param.id = 0; 
            }
        }"
    >
    <thead>
        <tr>
            <th field="NAMA_WARNA"  >
                <img src="../WEB/images/tree-satker.png" align="left"> 
                <img src="../WEB/images/tree-subsatker.png"> 
            </th>
        </tr>
    </thead>
    </table>
<!--</div>-->

</body>

</html>