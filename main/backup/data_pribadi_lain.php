<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPrestasi.php");
include_once("../WEB/classes/base/PelamarKaryaTulis.php");
include_once("../WEB/classes/base/PelamarKegiatanSosial.php");
include_once("../WEB/classes/utils/FileHandler.php");

$reqId= $userLogin->userPelamarEnkripId;

if($reqId == "")
{
	echo '<script language="javascript">';
	echo 'alert("Login telebih dahulu.");';	
	echo 'top.location.href = "index.php";';
	echo '</script>';
	exit();
}

$set= new Pelamar();
$set->selectByParams(array("md5(CAST(PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
$set->firstRow();
$tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
unset($set);

$arrDataPrestasi="";
$index_data_prestasi= 0;
$set= new PelamarPrestasi();
$set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId),-1,-1, "", "ORDER BY A.PELAMAR_PRESTASI_ID ASC");
//echo $set->query;
while($set->nextRow())
{
	$arrDataPrestasi[$index_data_prestasi]["PELAMAR_PRESTASI_ID"] = $set->getField("PELAMAR_PRESTASI_ID");
	$arrDataPrestasi[$index_data_prestasi]["TINGKAT"] = $set->getField("TINGKAT");
	$arrDataPrestasi[$index_data_prestasi]["TAHUN"] = $set->getField("TAHUN");
	$arrDataPrestasi[$index_data_prestasi]["NAMA"] = $set->getField("NAMA");
	$arrDataPrestasi[$index_data_prestasi]["PENGHARGAAN"] = $set->getField("PENGHARGAAN");
	$arrDataPrestasi[$index_data_prestasi]["TANGGAL"] = dateToPageCheck($set->getField("TANGGAL"));
	
	$index_data_prestasi++;
}
if($index_data_prestasi > 0)
{
	$tempRowPrestasiId= $arrDataPrestasi[0]["PELAMAR_PRESTASI_ID"];
	$tempPenugasanTingkat= $arrDataPrestasi[0]["TINGKAT"];
	$tempPenugasanTahun= $arrDataPrestasi[0]["TAHUN"];
	$tempPenugasanNama= $arrDataPrestasi[0]["NAMA"];
	$tempPenugasanPenghargaan= $arrDataPrestasi[0]["PENGHARGAAN"];
	$tempPeriode= $arrDataPrestasi[0]["TANGGAL"];
	
}
unset($set);


$arrDataKaryaTulis="";
$index_data_karya_tulis= 0;
$set= new PelamarKaryaTulis();
$set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId),-1,-1, "", "ORDER BY A.PELAMAR_KARYA_TULIS_ID ASC");
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataKaryaTulis[$index_data_karya_tulis]["PELAMAR_KARYA_TULIS_ID"] = $set->getField("PELAMAR_KARYA_TULIS_ID");
	$arrDataKaryaTulis[$index_data_karya_tulis]["TAHUN"] = $set->getField("TAHUN");
	$arrDataKaryaTulis[$index_data_karya_tulis]["NAMA"] = $set->getField("NAMA");
	$arrDataKaryaTulis[$index_data_karya_tulis]["TANGGAL"] = dateToPageCheck($set->getField("TANGGAL"));
	
	$index_data_karya_tulis++;
}
if($index_data_karya_tulis > 0)
{
	$tempRowKaryaTulisId= $arrDataKaryaTulis[0]["PELAMAR_KARYA_TULIS_ID"];
	$tempKaryaTulisTahun= $arrDataKaryaTulis[0]["TAHUN"];
	$tempKaryaTulisNama= $arrDataKaryaTulis[0]["NAMA"];
	$tempPeriode= $arrDataKaryaTulis[0]["TANGGAL"];
	
}
unset($set);

$arrDataKegiatanSosial="";
$index_data_kegiatan_sosial= 0;
$set= new PelamarKegiatanSosial();
$set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId),-1,-1, "", "ORDER BY A.PELAMAR_KEGIATAN_SOSIAL_ID ASC");
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataKegiatanSosial[$index_data_kegiatan_sosial]["PELAMAR_KEGIATAN_SOSIAL_ID"] = $set->getField("PELAMAR_KEGIATAN_SOSIAL_ID");
	$arrDataKegiatanSosial[$index_data_kegiatan_sosial]["TAHUN"] = $set->getField("TAHUN");
	$arrDataKegiatanSosial[$index_data_kegiatan_sosial]["NAMA"] = $set->getField("NAMA");
	$arrDataKegiatanSosial[$index_data_kegiatan_sosial]["JABATAN"] = $set->getField("JABATAN");
	$arrDataKegiatanSosial[$index_data_kegiatan_sosial]["TANGGAL"] = dateToPageCheck($set->getField("TANGGAL"));
	
	$index_data_kegiatan_sosial++;
}
if($index_data_kegiatan_sosial > 0)
{
	$tempRowKegiatanSosialId= $arrDataKegiatanSosial[0]["PELAMAR_KEGIATAN_SOSIAL_ID"];
	$tempKegiatanSosialTahun= $arrDataKegiatanSosial[0]["TAHUN"];
	$tempKegiatanSosialNama= $arrDataKegiatanSosial[0]["NAMA"];
	$tempKegiatanSosialJabatan= $arrDataKegiatanSosial[0]["JABATAN"];
	$tempPeriode= $arrDataKegiatanSosial[0]["TANGGAL"];
	
}
unset($set);

//$tempLinkCssSecurity= $INDEX_SUB."/assets/valsix/WEB/";
//$tempLinkSecurity= $INDEX_SUB."/";

$tempLinkCssSecurity= $INDEX_SUB."../WEB/";
$tempLinkSecurity= $INDEX_SUB."../";

//echo $tempFotoFile;
?>

<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script>

<script type="text/javascript">
	$(function(){
		$('#ff').form({
			//url:'<?=$tempLinkSecurity?>json/data_pribadi_lain',
			url:'<?=$tempLinkSecurity?>json/data_pribadi_lain.php',
			onSubmit:function(){
				//alert($(this).form('validate'));
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);
				//$.messager.alert('Info', data, 'info');
				data = data.split("-");
				$.messager.alert('Info', data[1], 'info');
				$('#rst_form').click();
				
				<?
				if($tempIsStatusIsiFormulir == "6")
				{
				?>
				document.location.href = '?pg=data_pribadi_upload';
				<?
				}
				else
				{
				?>
				document.location.href = '?pg=data_pribadi_lain';
				<?
				}
				?>
				
			}
		});
		
	});
	
</script>

<script type="text/javascript">
function addRowPrestasi(index)
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(index);
	
	var rownum= tabBody.rows.length;
	var infoData="";
	//infoData= (parseInt(rownum) / 3) + 1;
	//alert(rownum);
	//alert(infoData);
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	cell.className="judul-grup";
	var button = document.createElement('label');
	
	button.innerHTML = 'Prestasi '+infoData+' <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTable(\'dataTableRowPrestasiDinamis\', this, 6)">Hapus Prestasi<img src="<?=$tempLinkCssSecurity?>images/hapus.png" width="15" height="15" border="0" /></a>'
	+'<input id="reqRowPrestasiId'+rownum+'" type="hidden" name="reqRowPrestasiId[]" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Prestasi yang pernah dicapai*';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqPrestasiNama[]";
	element.id = "reqPrestasiNama"+rownum;
	element.style.width = "350px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Tingkat**';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var element = document.createElement("select");
	element.className='easyui-validatebox';
	element.setAttribute("name", "reqPrestasiTingkat[]");
	var option = document.createElement("option");
	element.options[0] = new Option("Belum Dipilih", "");
	element.options[1] = new Option("Organisasi Kerja", "Organisasi Kerja");
	element.options[2] = new Option("Nasional", "Nasional");
	element.options[3] = new Option("Internasional", "Internasional");
	element.setAttribute('id', "reqPrestasiTingkat"+rownum);
	//element.style.width = "145px";
	cell.appendChild(element);
	
	//var button = document.createElement('label');
	//button.innerHTML = '&nbsp;<label><input type="hidden" name="reqJenisInstansiLain[]" id="reqJenisInstansiLain'+rownum+'" style="width:150px" /></label>';
	//cell.appendChild(button);
	
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Pemberi Penghargaan';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqPrestasiPenghargaan[]";
	element.id = "reqPrestasiPenghargaan"+rownum;
	element.style.width = "200px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	
    //start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Tahun Perolehan';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqPrestasiTahun[]";
	element.id = "reqPrestasiTahun"+rownum;
	element.style.width = "100px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
			
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	var rowCount = tabBody.rows.length;
	$("#reqArrData").val(rowCount);
	rowCount= rowCount-6;
	
	$('#reqPrestasiNama'+rowCount+',#reqPrestasiNama'+rowCount).validatebox({
		required:true
	});
	
}

function addRowKarya(index)
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(index);
	
	var rownum= tabBody.rows.length;
	var infoData="";
	//infoData= (parseInt(rownum) / 3) + 1;
	//alert(rownum);
	//alert(infoData);
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	cell.className="judul-grup";
	var button = document.createElement('label');
	
	button.innerHTML = 'Karya Tulis '+infoData+' <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTable(\'dataTableRowKaryaTulisDinamis\', this, 4)">Hapus Karya Tulis<img src="<?=$tempLinkCssSecurity?>images/hapus.png" width="15" height="15" border="0" /></a>'
	+'<input id="reqRowKaryaTulisId'+rownum+'" type="hidden" name="reqRowKaryaTulisId[]" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Judul';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKaryaTulisNama[]";
	element.id = "reqKaryaTulisNama"+rownum;
	element.style.width = "350px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Tahun';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKaryaTulisTahun[]";
	element.id = "reqKaryaTulisTahun"+rownum;
	element.style.width = "100px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
			
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	var rowCount = tabBody.rows.length;
	$("#reqArrData").val(rowCount);
	rowCount= rowCount-4;
	
	//$('#reqPrestasiNama'+rowCount+',#reqPrestasiNama'+rowCount).validatebox({
		//required:true
	//});
	
}

function addRowSosial(index)
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(index);
	
	var rownum= tabBody.rows.length;
	var infoData="";
	//infoData= (parseInt(rownum) / 3) + 1;
	//alert(rownum);
	//alert(infoData);
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	cell.className="judul-grup";
	var button = document.createElement('label');
	
	button.innerHTML = 'Kegiatan Sosial Kemasyarakatan '+infoData+' <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTable(\'dataTableRowKegiatanSosialDinamis\', this, 5)">Hapus Kegiatan Sosial Kemasyarakatan<img src="<?=$tempLinkCssSecurity?>images/hapus.png" width="15" height="15" border="0" /></a>'
	+'<input id="reqRowKegiatanSosialId'+rownum+'" type="hidden" name="reqRowKegiatanSosialId[]" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Nama Organisasi / Kegiatan';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKegiatanSosialNama[]";
	element.id = "reqKegiatanSosialNama"+rownum;
	element.style.width = "350px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Jabatan';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKegiatanSosialJabatan[]";
	element.id = "reqKegiatanSosialJabatan"+rownum;
	element.style.width = "200px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Tahun';//&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10">
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKegiatanSosialTahun[]";
	element.id = "reqKegiatanSosialTahun"+rownum;
	element.style.width = "100px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
			
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	var rowCount = tabBody.rows.length;
	$("#reqArrData").val(rowCount);
	rowCount= rowCount-4;
	
	//$('#reqPrestasiNama'+rowCount+',#reqPrestasiNama'+rowCount).validatebox({
		//required:true
	//});
	
}


function deleteRowDrawAllTable(tableID) {
	try {
	var table = document.getElementById(tableID);
	var rowCount= table.rows.length;
	//alert(rowCount);
		
		for (var x=rowCount-1; x>0; x--) {
		   table.deleteRow(x);
		}
	}
	catch(e) 
	{
		alert(e);
	}
}

function deleteRowDrawTable(tableID, id, maxDataHapus) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount= table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	//alert(tableID+", "+id+", "+maxDataHapus+", "+rowCount);
	//alert(rowCount);
	for(var i=0; i<=rowCount; i++) {
		//alert(id+'-s-'+1);
		if(id == i) {
			for(var i_index=0; i_index < maxDataHapus; i_index++)
				table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}

function deleteRowDrawTablePhp(tableID, id, rowId, reqMode, maxDataHapus) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) 
		{
			var valRowId= $("#"+rowId).val();
			$.getJSON('<?=$tempLinkSecurity?>json/delete.php?reqMode='+reqMode+'&id='+valRowId, function (data) 
			{
			});
			
			for(var i_index=0; i_index < maxDataHapus; i_index++)
				table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}

</script>

<div class="col-lg-8">

    <div id="judul-halaman">Data Pribadi Lain</div>
    
    <form id="ff" method="post" novalidate enctype="multipart/form-data">
    <div id="pendaftaran"<?php /*?> style="overflow-x:scroll"<?php */?>>
        <table id="dataTableRowPrestasiDinamis">
            <tr>
              <td colspan="3" class="judul-grup">
              Prestasi
              <a style="cursor:pointer; float:right;" title="Tambah" onclick="addRowPrestasi('0')">Tambah Prestasi <img src="<?=$tempLinkCssSecurity?>images/tambah.png" width="16" height="16" border="0" /></a>
              <input type="hidden" name="reqRowPrestasiId[]" id="reqRowPrestasiId" value="<?=$tempRowPrestasiId?>" />
              </td>
            </tr>
            <tr>
              <td>Prestasi yang pernah dicapai*<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input name="reqPrestasiNama[]" class="easyui-validatebox" type="text" id="reqPrestasiNama<?=$checkbox_index?>" required style="width:350px" value="<?=$tempPenugasanNama?>" />
              </td>
            </tr>
            <tr>
              <td>Tingkat**<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<select name="reqPrestasiTingkat[]" id="reqPrestasiTingkat<?=$checkbox_index?>" class="easyui-validatebox">
                        <option value="" <? if($tempPenugasanTingkat == "") echo "selected";?>>Belum Dipilih</option>
                        <option value="Organisasi Kerja" <? if($tempPenugasanTingkat == "Organisasi Kerja") echo "selected";?>>Organisasi Kerja</option>
                        <option value="Nasional" <? if($tempPenugasanTingkat == "Nasional") echo "selected";?>>Nasional</option>
                        <option value="Internasional" <? if($tempPenugasanTingkat == "Internasional") echo "selected";?>>Internasional</option>
                    </select>
              </td>
            </tr>
            <tr>
              <td>Pemberi Penghargaan<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input type="text" name="reqPrestasiPenghargaan[]" id="reqPrestasiPenghargaan<?=$checkbox_index?>" class="easyui-validatebox" style="width:200px;" value="<?=$tempPenugasanPenghargaan?>" />
              </td>
            </tr>
            <tr>
              <td>Tahun Perolehan<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              	<input type="text" name="reqPrestasiTahun[]" id="reqPrestasiTahun<?=$checkbox_index?>" class="easyui-validatebox" style="width:100px;" value="<?=$tempPenugasanTahun?>" />
              </td>
            </tr>
            <tr>
              <td colspan="3">
              	<label>
              	*) Tuliskan prestasi yang dicapai dengan detail.<br/>
				**) Tingkat : Organisasi Kerja, Nasional, Internasional
                </label>
             </td>
            </tr>
            <?
			for($checkbox_index=1;$checkbox_index<count($arrDataPrestasi);$checkbox_index++)
			{
				$tempRowPrestasiId= $arrDataPrestasi[$checkbox_index]["PELAMAR_PRESTASI_ID"];
			    $tempPenugasanTingkat= $arrDataPrestasi[$checkbox_index]["TINGKAT"];
			    $tempPenugasanTahun= $arrDataPrestasi[$checkbox_index]["TAHUN"];
			    $tempPenugasanNama= $arrDataPrestasi[$checkbox_index]["NAMA"];
			    $tempPenugasanPenghargaan= $arrDataPrestasi[$checkbox_index]["PENGHARGAAN"];
            ?>
            <tr>
              <td colspan="3" class="judul-grup">
              Prestasi
              <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTablePhp('dataTableRowPrestasiDinamis', this, 'reqRowPrestasiId<?=$checkbox_index?>', 'data_pribadi_prestasi', 6)">Hapus Pengalaman<img src="<?=$tempLinkCssSecurity?>images/hapus.png" width="15" height="15" border="0" /></a>
              <input type="hidden" name="reqRowPrestasiId[]" id="reqRowPrestasiId<?=$checkbox_index?>" value="<?=$tempRowPrestasiId?>" />
              </td>
            </tr>
            <tr>
              <td>Prestasi yang pernah dicapai*<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input name="reqPrestasiNama[]" class="easyui-validatebox" type="text" id="reqPrestasiNama<?=$checkbox_index?>" required style="width:350px" value="<?=$tempPenugasanNama?>" />
              </td>
            </tr>
            <tr>
              <td>Tingkat**<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<select name="reqPrestasiTingkat[]" id="reqPrestasiTingkat<?=$checkbox_index?>" class="easyui-validatebox">
                        <option value="" <? if($tempPenugasanTingkat == "") echo "selected";?>>Belum Dipilih</option>
                        <option value="Organisasi Kerja" <? if($tempPenugasanTingkat == "Organisasi Kerja") echo "selected";?>>Organisasi Kerja</option>
                        <option value="Nasional" <? if($tempPenugasanTingkat == "Nasional") echo "selected";?>>Nasional</option>
                        <option value="Internasional" <? if($tempPenugasanTingkat == "Internasional") echo "selected";?>>Internasional</option>
                    </select>
              </td>
            </tr>
            <tr>
              <td>Pemberi Penghargaan<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input type="text" name="reqPrestasiPenghargaan[]" id="reqPrestasiPenghargaan<?=$checkbox_index?>" class="easyui-validatebox" style="width:200px;" value="<?=$tempPenugasanPenghargaan?>" />
              </td>
            </tr>
            <tr>
              <td>Tahun Perolehan<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              	<input type="text" name="reqPrestasiTahun[]" id="reqPrestasiTahun<?=$checkbox_index?>" class="easyui-validatebox" style="width:100px;" value="<?=$tempPenugasanTahun?>" />
              </td>
            </tr>
            <tr>
              <td colspan="3"><label></label></td>
            </tr>
            <?
			}
            ?>
            </table>
            
            <table id="dataTableRowKaryaTulisDinamis">
            <tr>
              <td colspan="3" class="judul-grup">
              Karya Tulis
              <a style="cursor:pointer; float:right;" title="Tambah" onclick="addRowKarya('1')">Tambah Karya Tulis <img src="<?=$tempLinkCssSecurity?>images/tambah.png" width="16" height="16" border="0" /></a>
              <input type="hidden" name="reqRowKaryaTulisId[]" id="reqRowKaryaTulisId" value="<?=$tempRowKaryaTulisId?>" />
              </td>
            </tr>
            <tr>
              <td>Judul<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input name="reqKaryaTulisNama[]" class="easyui-validatebox" type="text" id="reqKaryaTulisNama<?=$checkbox_index?>" style="width:350px" value="<?=$tempKaryaTulisNama?>" />
              </td>
            </tr>
            <tr>
              <td>Tahun<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              	<input type="text" name="reqKaryaTulisTahun[]" id="reqKaryaTulisTahun<?=$checkbox_index?>" class="easyui-validatebox" style="width:100px;" value="<?=$tempKaryaTulisTahun?>" />
              </td>
            </tr>
            <tr>
              <td colspan="3"><label></label></td>
            </tr>
            <?
			for($checkbox_index=1;$checkbox_index<count($arrDataKaryaTulis);$checkbox_index++)
			{
				$tempRowKaryaTulisId= $arrDataKaryaTulis[$checkbox_index]["PELAMAR_KARYA_TULIS_ID"];
			    $tempKaryaTulisTahun= $arrDataKaryaTulis[$checkbox_index]["TAHUN"];
			    $tempKaryaTulisNama= $arrDataKaryaTulis[$checkbox_index]["NAMA"];
            ?>
            <tr>
              <td colspan="3" class="judul-grup">
              Karya Tulis
              <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTablePhp('dataTableRowKaryaTulisDinamis', this, 'reqRowKaryaTulisId<?=$checkbox_index?>', 'data_pribadi_karya_tulis', 4)">Hapus Karya Tulis<img src="<?=$tempLinkCssSecurity?>images/hapus.png" width="15" height="15" border="0" /></a>
              <input type="hidden" name="reqRowKaryaTulisId[]" id="reqRowKaryaTulisId<?=$checkbox_index?>" value="<?=$tempRowKaryaTulisId?>" />
              </td>
            </tr>
            <tr>
              <td>Judul<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input name="reqKaryaTulisNama[]" class="easyui-validatebox" type="text" id="reqKaryaTulisNama<?=$checkbox_index?>" required style="width:350px" value="<?=$tempKaryaTulisNama?>" />
              </td>
            </tr>
            <tr>
              <td>Tahun<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              	<input type="text" name="reqKaryaTulisTahun[]" id="reqKaryaTulisTahun<?=$checkbox_index?>" class="easyui-validatebox" style="width:100px;" value="<?=$tempKaryaTulisTahun?>" />
              </td>
            </tr>
            <tr>
              <td colspan="3"><label></label></td>
            </tr>
            <?
			}
            ?>
            </table>
            
            
            <table id="dataTableRowKegiatanSosialDinamis">
            <tr>
              <td colspan="3" class="judul-grup">
              Kegiatan Sosial Kemasyarakatan
              <a style="cursor:pointer; float:right;" title="Tambah" onclick="addRowSosial('2')">Tambah Kegiatan Sosial Kemasyarakatan <img src="<?=$tempLinkCssSecurity?>images/tambah.png" width="16" height="16" border="0" /></a>
              <input type="hidden" name="reqRowKegiatanSosialId[]" id="reqRowKegiatanSosialId" value="<?=$tempRowKegiatanSosialId?>" />
              </td>
            </tr>
            <tr>
              <td>Nama Organisasi / Kegiatan<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input name="reqKegiatanSosialNama[]" class="easyui-validatebox" type="text" id="reqKegiatanSosialNama<?=$checkbox_index?>" style="width:350px" value="<?=$tempKegiatanSosialNama?>" />
              </td>
            </tr>
            <tr>
              <td>Jabatan<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input name="reqKegiatanSosialJabatan[]" class="easyui-validatebox" type="text" id="reqKegiatanSosialJabatan<?=$checkbox_index?>" style="width:250px" value="<?=$tempKegiatanSosialJabatan?>" />
              </td>
            </tr>
            <tr>
              <td>Tahun<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              	<input type="text" name="reqKegiatanSosialTahun[]" id="reqKegiatanSosialTahun<?=$checkbox_index?>" class="easyui-validatebox" style="width:100px;" value="<?=$tempKegiatanSosialTahun?>" />
              </td>
            </tr>
            <tr>
              <td colspan="3"><label></label></td>
            </tr>
            <?
			for($checkbox_index=1;$checkbox_index<count($arrDataKegiatanSosial);$checkbox_index++)
			{
				$tempRowKegiatanSosialId= $arrDataKegiatanSosial[$checkbox_index]["PELAMAR_KEGIATAN_SOSIAL_ID"];
				$tempKegiatanSosialTahun= $arrDataKegiatanSosial[$checkbox_index]["TAHUN"];
				$tempKegiatanSosialNama= $arrDataKegiatanSosial[$checkbox_index]["NAMA"];
				$tempKegiatanSosialJabatan= $arrDataKegiatanSosial[$checkbox_index]["JABATAN"];
            ?>
            <tr>
              <td colspan="3" class="judul-grup">
              Kegiatan Sosial Kemasyarakatan
              <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTablePhp('dataTableRowKegiatanSosialDinamis', this, 'reqRowKegiatanSosialId<?=$checkbox_index?>', 'data_pribadi_kegiatan_sosial', 5)">Hapus Kegiatan Sosial Kemasyarakatan<img src="<?=$tempLinkCssSecurity?>images/hapus.png" width="15" height="15" border="0" /></a>
              <input type="hidden" name="reqRowKegiatanSosialId[]" id="reqRowKegiatanSosialId<?=$checkbox_index?>" value="<?=$tempRowKegiatanSosialId?>" />
              </td>
            </tr>
            <tr>
              <td>Nama Organisasi / Kegiatan<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input name="reqKegiatanSosialNama[]" class="easyui-validatebox" type="text" id="reqKegiatanSosialNama<?=$checkbox_index?>" required style="width:350px" value="<?=$tempKegiatanSosialNama?>" />
              </td>
            </tr>
            <tr>
              <td>Jabatan<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              		<input name="reqKegiatanSosialJabatan[]" class="easyui-validatebox" type="text" id="reqKegiatanSosialJabatan<?=$checkbox_index?>" style="width:250px" value="<?=$tempKegiatanSosialJabatan?>" />
              </td>
            </tr>
            <tr>
              <td>Tahun<?php /*?>&nbsp;<img src="<?=$tempLinkCssSecurity?>images/star.png" width="10" height="10"><?php */?></td>
              <td>:</td>
              <td>
              	<input type="text" name="reqKegiatanSosialTahun[]" id="reqKegiatanSosialTahun<?=$checkbox_index?>" class="easyui-validatebox" style="width:100px;" value="<?=$tempKegiatanSosialTahun?>" />
              </td>
            </tr>
            <tr>
              <td colspan="3"><label></label></td>
            </tr>
            <?
			}
            ?>
            </table>
            
            
    </div>
    <table>
    	<tr>
          <td colspan="3">
            <input name="reqSubmit" type="hidden" value="insert" />
            <input type="submit" value="Simpan" />
          </td>
        </tr>
    </table>
    </form>
    
</div>
<script>
$("#reqTotalPenugasanTahunBekerja,#reqKodePos,#reqKkUraian,#reqNpwp,#reqKodeTelpRumah,#reqTelpRumah").keypress(function(e) {
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});
</script>