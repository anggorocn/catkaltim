<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/LevelAtribut.php");
include_once("../WEB/classes/base/IndikatorPenilaian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode= httpFilterRequest("reqMode");
$reqAtributId= httpFilterGet("reqAtributId");
$reqRowId= httpFilterGet("reqRowId");

$statement= " AND A.LEVEL_ID = ".$reqRowId;
$set_atribut= new LevelAtribut();
$set_atribut->selectByParamsLevelAtribut(array(), -1,-1, $statement);
$set_atribut->firstRow();
$tempAtributNama= $set_atribut->getField("ATRIBUT_NAMA");
$tempAtributLevel= $set_atribut->getField("LEVEL");
$tempAtributKeterangan= $set_atribut->getField("KETERANGAN");
unset($set_atribut);

$index_loop= 0;
$arrIndikatorPenilaian="";
$statement= " AND A.LEVEL_ID = ".$reqRowId;
$set= new IndikatorPenilaian();
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrIndikatorPenilaian[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
	$arrIndikatorPenilaian[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
	$arrIndikatorPenilaian[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
	$index_loop++;
}
$jumlah_indikator_penilaian= $index_loop;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
function setLoad()
{
	document.location.href= 'indikator_penilaian_add_indikator_penilaian.php?reqAtributId=<?=$reqAtributId?>&reqRowId=<?=$reqRowId?>';
}

$(function(){
	$('#ff').form({
		url:'../json-pengaturan/indikator_penilaian_add_indikator_penilaian.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);return false;
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			//parent.setShowHideMenu(3);
			parent.frames['mainFrame'].location.href = 'indikator_penilaian_add_indikator_penilaian.php?reqAtributId=<?=$reqAtributId?>&reqRowId=<?=$reqRowId?>';
		}
	});
	
});

function addClonerRow()
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(1);
	
	var rownum= tabBody.rows.length;
	
	row=document.createElement("TR");
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '<input type="text" name="reqNamaIndikator[]" id="reqNamaIndikator'+rownum+'" style="width:100%" /> ';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '<input type="hidden" name="reqIndikatorId[]" id="reqIndikatorId'+rownum+'" />'
	+'<center><a style="cursorointer" onclick="deleteRowDrawTable(\'tableAsesor\', this)"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>';
	cell.appendChild(button);
	row.appendChild(cell);
		  
	tabBody.appendChild(row);
}

function deleteRowDrawTable(tableID, id) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) {
			table.deleteRow(i);
			//setHitungTotal();
			//setMasakanUtamaHitung();
		}
	}
	}catch(e) {
		alert(e);
	}
}

function deleteRowDrawTablePhp(tableID, id, rowId, tempId, mode) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) 
		{
			var valRowId= $("#"+tempId+rowId).val();
			$.getJSON('../json-pengaturan/delete.php?reqMode='+mode+'&id='+valRowId, function (data) 
			{
			});
			
			table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}
</script>

<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
	<div id="content" style="height:auto; width:100%">
    <form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="100%">
    	<tr>
            <td colspan="3">
            <div id="header-tna-detil">Level <span><?=$tempAtributLevel?></span></div>
            </td>			
        </tr>
        <tr>           
            <td style="width:150px">Atribut</td><td style="width:5px">:</td>
            <td><?=$tempAtributNama?></td>
        </tr>
        <tr>
            <td>Keterangan</td><td>:</td>
            <td><?=$tempAtributKeterangan?></td>
        </tr>
        <?
		if($reqRowId == ""){}
		else
		{
        ?>
        <tr>
            <td>
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
                <input type="hidden" name="reqAtributId" value="<?=$reqAtributId?>">
                <input type="hidden" name="reqMode" value="insert">
                <input type="submit" name="" value="Simpan" />
            </td>
        </tr>
        <?
		}
        ?>
    </table>
    <table class="gradient-style" id="tableAsesor" style="width:100%; margin-left:-1px">
    <thead>
    <tr>
        <th scope="col" style="width:95%">
        Indikator
        <?
		if($reqRowId == ""){}
		else
		{
        ?>
        <a style="cursor:pointer" title="Tambah" onclick="addClonerRow()"><img src="../WEB/images/icn_add.gif" width="16" height="16" border="0" /></a>
        <?
		}
        ?>
        </th>
        <th scope="col" style="text-align:center; width:50px">Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?
	for($checkbox_index=0;$checkbox_index < $jumlah_indikator_penilaian;$checkbox_index++)
	{
		$tempIndikatorId= $arrIndikatorPenilaian[$checkbox_index]["INDIKATOR_ID"];
		$tempLevel= $arrIndikatorPenilaian[$checkbox_index]["LEVEL_ID"];
		$tempNamaIndikator= $arrIndikatorPenilaian[$checkbox_index]["NAMA_INDIKATOR"];
    ?>
    <tr>
        <td>
            <input type="text" name="reqNamaIndikator[]" id="reqNamaIndikator<?=$checkbox_index?>" style="width:100%" value="<?=$tempNamaIndikator?>" /> 
        </td>
        <td>
        	<center><a style="cursor:pointer" onclick="deleteRowDrawTablePhp('tableAsesor', this, '<?=$checkbox_index?>', 'reqIndikatorId', 'indikator_penilaian')"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>
            <input type="hidden" name="reqIndikatorId[]" id="reqIndikatorId<?=$checkbox_index?>" value="<?=$tempIndikatorId?>" />
        </td>
    </tr>
    <?
	}
    ?>
    </tbody>
    </table>
    </form>
    </div>
</div>
</body>
</html>