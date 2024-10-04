<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/PegawaiPenilai.php");

$pegawai_penilai = new PegawaiPenilai();
$pegawai_dinilai = new PegawaiPenilai();

$reqPenilaiId = httpFilterGet("reqPenilaiId");


$pegawai_penilai->selectByParamsPenilai(array('A.PEGAWAI_ID'=>$reqPenilaiId));
$pegawai_penilai->firstRow();

$tempNama= $pegawai_penilai->getField('NAMA');
$tempJabatan= $pegawai_penilai->getField('JABATAN');
$tempNipBaru= $pegawai_penilai->getField('NIP_BARU');
$tempDepartemen=$pegawai_penilai->getField('DEPARTEMEN');


$pegawai_dinilai->selectByParamsDinilai(array('PEGAWAI_ID_PENILAI'=>$reqPenilaiId));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript">
$.fn.datebox.defaults.formatter = function(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return d+'-'+m+'-'+y;
}		
$(function(){
	$('#ff').form({
		url:'../json-masterdata/pegawai_penilai_add.php',
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
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Monitoring Pegawai Penilai</div>
	<div id="konten">
    	<table>
    		<tr>
            	<td>NIP Baru</td>
                <td>:</td>
                <td>
					&nbsp;<?=$tempNipBaru?>
                </td>
            </tr>
        	<tr>
            	<td>Nama</td>
                <td>:</td>
                <td>
					&nbsp;<?=$tempNama?>
                </td>
            </tr>
        	<tr>
            	<td>Jabatan</td>
                <td>:</td>
                <td>
                	&nbsp;<?=$tempJabatan?>
                </td>
            </tr>  
        	<tr>
            	<td>Unit Kerja</td>
                <td>:</td>
                <td>
                	&nbsp;<?=$tempDepartemen?>
                </td>
            </tr>  
        </table><br />
    	<div id="popup-tabel2">
    	<table class="example" id="dataTableRowDinamis">
        	<thead class="altrowstable">
        		<tr>
                	<th>Nip Baru</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Departemen</th>
                </tr>
            </thead>
        	<tbody class="example altrowstable" id="alternatecolor"> 
            	<?
					while($pegawai_dinilai->nextRow())
					{
                ?>
                    <tr>
                    	<td><?=$pegawai_dinilai->getField("NIP_BARU")?></td>
                    	<td><?=$pegawai_dinilai->getField("NAMA")?></td>
                    	<td><?=$pegawai_dinilai->getField("JABATAN")?></td>
                    	<td><?=$pegawai_dinilai->getField("DEPARTEMEN")?></td>
                    </tr>
                <?
					}
                ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>