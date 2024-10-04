<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-skp/Pertanyaan.php");
include_once("../WEB/classes/base-skp/Kategori.php");
include_once("../WEB/classes/base-skp/Jawaban.php");

$pertanyaan = new Pertanyaan();
$kategori = new Kategori();
$jawaban= new Jawaban();
$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");

if($reqMode == "update")
{
	$pertanyaan->selectByParams(array("PERTANYAAN_ID" => $reqId));
	$pertanyaan->firstRow();
	$tempKategori = $pertanyaan->getField('KATEGORI_ID');
	$tempPertanyaan  = $pertanyaan->getField("PERTANYAAN");
	$tempNoUrut  = $pertanyaan->getField("URUT");
	$tempBobot  = $pertanyaan->getField("BOBOT");
	
	$jawaban->selectByParams(array("PERTANYAAN_ID"=>$reqId),-1,-1);
	$index = 0;
	while($jawaban->nextRow())
	{	  
		$arrJawaban[$index]["PERTANYAAN_ID"] = $jawaban->getField("PERTANYAAN_ID");
		$arrJawaban[$index]["JAWABAN"] = $jawaban->getField("JAWABAN");
		$arrJawaban[$index]["KETERANGAN"] = $jawaban->getField("KETERANGAN");
		$arrJawaban[$index]["RANGE_1"] = $jawaban->getField("RANGE_1");
		$arrJawaban[$index]["RANGE_2"] = $jawaban->getField("RANGE_2");
		$arrJawaban[$index]["RANGE_3"] = $jawaban->getField("RANGE_3");
		
		$index++;
	}
	
}
$kategori->selectByParams();
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
function addRowDrawTables(tableID) {
	var table = document.getElementById(tableID);

	var rowCount = table.rows.length;
	var id_row = rowCount-1;
	var row = table.insertRow(rowCount);
	
	$('#reqArrayIndex').val(rowCount);
	
	var column0= row.insertCell(0);
	column0.valign = "top" ;
	var add_label = document.createElement('label');
	add_label.style.textAlign='center';
	column0.appendChild(add_label);
	add_label.innerHTML = '<input id="reqJawaban'+id_row+'" type="name" name="reqJawaban['+id_row+']" style="220px" />';
	$('#reqJawaban'+id_row).validatebox({  
		required: true
	});
	
	var column1= row.insertCell(1);
	column1.align = "center" ;
	var add_label = document.createElement('label');
	add_label.style.textAlign='center';
	column1.appendChild(add_label);
	add_label.innerHTML = '<textarea id="reqKeterangan'+id_row+'" type="name" name="reqKeterangan['+id_row+']" cols="60"></textarea>';
	
	var column2= row.insertCell(2);
	column2.align = "center" ;
	var add_label = document.createElement('label');
	add_label.style.textAlign='center';
	column2.appendChild(add_label);
	add_label.innerHTML = '<input id="reqRange1'+id_row+'" type="name" name="reqRange1['+id_row+']" size="10" />';
	
	var column3= row.insertCell(3);
	column3.align = "center" ;
	var add_label = document.createElement('label');
	add_label.style.textAlign='center';
	column3.appendChild(add_label);
	add_label.innerHTML = '<input id="reqRange2'+id_row+'" type="name" name="reqRange2['+id_row+']" size="10" />';
	
	var column4= row.insertCell(4);
	column4.align = "center" ;
	var add_label = document.createElement('label');
	add_label.style.textAlign='center';
	column4.appendChild(add_label);
	add_label.innerHTML = '<input id="reqRange3'+id_row+'" type="name" name="reqRange3['+id_row+']" size="10" />';
	
	$('input[id^="reqRange"]').keypress(function(e) {
		if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});
	
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
		}
	}
	}catch(e) {
		alert(e);
	}
}

function deleteRowDrawTablePhp(tableID, tipe, id) {
	
	var pesan;
	if(tipe == 'add')
		pesan = 'Apakah anda ingin menghapus data terpilih?';
	else
		pesan = 'Menghapus data terpilih akan menyebabkan histori terhapus. Lanjutkan?';
	
	if(confirm(pesan) == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) {
			table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}
</script>

<script type="text/javascript">	
$(function(){
	$('#ff').form({
		url:'../json-skp/pertanyaan_add.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
			<? if($reqMode == "update") { ?>
				window.parent.divwin.close();
			<? } ?>
		}
	});
	
});
</script>
<style type="text/css" media="screen">
  label {
	/*font-size: 10px;
	font-weight: bold;
	text-transform: uppercase;
	margin-bottom: 3px;*/
	clear: both;
  }
</style>
<style type="text/css">
	/* Remove margins from the 'html' and 'body' tags, and ensure the page takes up full screen height */
	html, body {height:100%; margin:0; padding:0;}
	/* Set the position and dimensions of the background image. */
	#page-background {position:fixed; top:0; left:0; width:100%; height:100%;}
	/* Specify the position and layering for the content that needs to appear in front of the background image. Must have a higher z-index value than the background image. Also add some padding to compensate for removing the margin from the 'html' and 'body' tags. */
	#content {position:relative; z-index:1;}
	/* prepares the background image to full capacity of the viewing area */
	#bg {position:fixed; top:0; left:0; width:100%; height:100%;}
	/* places the content ontop of the background image */
	#content {position:relative; z-index:1;}
</style>
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>  
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
    	<table class="table_list" cellspacing="1" width="100%">
        	<tr>
                <td colspan="6">
                <div id="header-tna-detil">Tambah <span>Kategori</span></div>	                    
                </td>			
            </tr>
        	<tr>
                <td>No. Urut</td>
                <td>
                    <input name="reqUrut" id="reqUrut" class="easyui-validatebox" required="true" title="No. Urut harus diisi" style="width:50px;" type="text" value="<?=$tempNoUrut?>" />
                </td>
            </tr>    
            <tr>
                <td>Kategori</td>
                <td>
                    <select id="reqKategori" name="reqKategori">
                        <? while($kategori->nextRow()){?>
                        <option value="<?=$kategori->getField('KATEGORI_ID')?>" <? if($kategori->getField('KATEGORI_ID') == $tempKategori) echo 'selected';?>><?=$kategori->getField('NAMA')?></option>
                        <? }?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Pertanyaan</td>
                <td>
                    <textarea name="reqPertanyaan" cols="60"><?=$tempPertanyaan?></textarea>
                </td>
            </tr>    
            <tr>
                <td>Bobot</td>
                <td>
                    <input name="reqBobot" id="reqBobot" class="easyui-validatebox" required="true" title="Bobot harus diisi" style="width:50px;" type="text" value="<?=$tempBobot?>" /> %
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div id="header-tna-detil">Jawaban</div>	
                    <table class="example" id="dataTableJabatanKru">
                    <thead>
                      <tr>
                          <th width="80px">Jawaban
                              <a style="cursor:pointer" title="Tambah Kapal" onclick="addRowDrawTables('dataTableJabatanKru')"><img src="../WEB/images/icn_add.gif" width="16" height="16" border="0" /></a>
                          </th>
                          <th>Keterangan</th>
                          <th>Range 1</th>
                          <th>Range 2</th>
                          <th>Range 3</th>
                      </tr>
                    </thead>
                    <tbody> 
                      <?
                      $checkbox_index = 0;
                      for($i=0;$i<count($arrJawaban);$i++)
                      {
                      ?>
                          <tr id="node-<?=$arrJawaban[$i]["PERTANYAAN_ID"]?>">
                              <td>
                                <input id="reqJawaban<?=$checkbox_index?>" type="text" name="reqJawaban[<?=$checkbox_index?>]" style="width:220px" value="<?=$arrJawaban[$i]["JAWABAN"]?>">
                              </td>
                              <td style="text-align:center">
                                <textarea name="reqKeterangan[<?=$checkbox_index?>]" cols="60"><?=$arrJawaban[$i]["KETERANGAN"]?></textarea>
                              </td>
                              <td>
                                <input id="reqRange1<?=$checkbox_index?>" type="text" name="reqRange1[<?=$checkbox_index?>]" size="10" value="<?=$arrJawaban[$i]["RANGE_1"]?>">
                              </td>
                              <td>
                                <input id="reqRange2<?=$checkbox_index?>" type="text" name="reqRange2[<?=$checkbox_index?>]" size="10" value="<?=$arrJawaban[$i]["RANGE_2"]?>">
                              </td>
                              <td>
                                <input id="reqRange3<?=$checkbox_index?>" type="text" name="reqRange3[<?=$checkbox_index?>]" size="10" value="<?=$arrJawaban[$i]["RANGE_3"]?>">
                              </td>
                          </tr>
                      <?php
                        $checkbox_index++;
                      }
                      ?>  
                    </tbody>            
                    </table>
                </td>
            </tr>
        </table>       
        </form>
        <script>
		$("#reqUrut").keypress(function(e) {
			//alert(e.which);
			if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			{
			return false;
			}
		});
		</script>
    </div>
</div>
</body>
</html>