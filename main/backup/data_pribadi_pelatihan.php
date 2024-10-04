<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPelatihan.php");
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

$arrData="";
$index_data= 0;
$set= new PelamarPelatihan();
$set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId),-1,-1, "", "ORDER BY A.PELAMAR_PELATIHAN_ID ASC");
//echo $set->query;
while($set->nextRow())
{
	$arrData[$index_data]["PELAMAR_PELATIHAN_ID"] = $set->getField("PELAMAR_PELATIHAN_ID");
	$arrData[$index_data]["LEMBAGA"] = $set->getField("LEMBAGA");
	$arrData[$index_data]["TAHUN"] = $set->getField("TAHUN");
	$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
	$arrData[$index_data]["NOMOR"] = $set->getField("NOMOR");
	$arrData[$index_data]["TANGGAL"] = dateToPageCheck($set->getField("TANGGAL"));
	
	$index_data++;
}
if($index_data > 0)
{
	$tempRowPelatihanId= $arrData[0]["PELAMAR_PELATIHAN_ID"];
	$tempLembaga= $arrData[0]["LEMBAGA"];
	$tempTahun= $arrData[0]["TAHUN"];
	$tempNama= $arrData[0]["NAMA"];
	$tempNomor= $arrData[0]["NOMOR"];
	$tempPeriode= $arrData[0]["TANGGAL"];
	
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
			//url:'<?=$tempLinkSecurity?>json/data_pribadi_pelatihan',
			url:'<?=$tempLinkSecurity?>json/data_pribadi_pelatihan.php',
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
				if($tempIsStatusIsiFormulir == "4")
				{
				?>
				document.location.href = '?pg=data_pribadi_lain';
				<?
				}
				else
				{
				?>
				document.location.href = '?pg=data_pribadi_pelatihan';
				<?
				}
				?>
				
			}
		});
		
	});
	
</script>

<script type="text/javascript">
function addRowDiklat()
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(1);
	
	var rownum = tabBody.rows.length;
	row=document.createElement("TR");

	//start
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqNama[]";
	element.id = "reqNama"+rownum;
	element.style.width = "200px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	
	//start
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqLembaga[]";
	element.id = "reqLembaga"+rownum;
	element.style.width = "200px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	
	//start
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqNomor[]";
	element.id = "reqNomor"+rownum;
	element.style.width = "200px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
							  
	//start
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqTahun[]";
	element.id = "reqTahun"+rownum;
	element.style.width = "100px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	
	/* KOLOM 7 */
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.style.textAlign='center';
	
	button.innerHTML = '<center>'
	+'<input type="hidden" name="reqRowPelatihanId[]" id="reqRowPelatihanId'+rownum+'" />'
	+'<a style="cursor:pointer;" onclick="deleteRowDrawTable(\'dataTableRowPangkatDinamis\', this)"><img src="<?=$tempLinkCssSecurity?>images/icon-delete.png" width="15" height="15" border="0" /></a></center>';
	cell.appendChild(button);
	row.appendChild(cell);
	
	tabBody.appendChild(row);
	
	var rowCount = tabBody.rows.length;
	$("#reqArrData").val(rowCount);
	rowCount= rowCount-1;
	
	$('#reqPangkat'+rowCount+',#reqPosisi'+rowCount).validatebox({
		required:true
	});
	
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

function deleteRowDrawTablePhp(tableID, id, rowId) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) 
		{
			var valRowId= $("#reqRowPelatihanId"+rowId).val();
			$.getJSON('<?=$tempLinkSecurity?>json/delete.php?reqMode=data_pribadi_pelatihan&id='+valRowId, function (data) 
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

<div class="col-lg-8">

    <div id="judul-halaman">Riwayat Pendidikan dan Pelatihan</div>
    
    <form id="ff" method="post" novalidate enctype="multipart/form-data">
    <div id="pendaftaran" style="overflow-x:scroll">
        <table>
        	<tr>
              <td colspan="3" class="judul-grup">
              <a style="cursor:pointer; float:right;" title="Tambah" onclick="addRowDiklat()">Tambah Riwayat Pendidikan dan Pelatihan<img src="<?=$tempLinkCssSecurity?>images/tambah.png" width="16" height="16" border="0" /></a>
              </td>
            </tr>
            <tr>
            	<td colspan="3">
                <table class="jadwal" id="dataTableRowPangkatDinamis">
                    <thead>
                      <tr>
                      	  <th style="width:100px">Nama Diklat/Shortcourse/Workshop</th>
                          <th>Lembaga Penyelenggara</th>
                          <th>Nomor Sertifikat</th>
                          <th>Tahun</th>
                          <th style="width:50px">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="alternatecolor"> 
                      <?
					  if($index_data > 0)
					  {
						  for($checkbox_index=0;$checkbox_index<count($arrData);$checkbox_index++)
						  {
							  $tempRowPelatihanId= $arrData[$checkbox_index]["PELAMAR_PELATIHAN_ID"];
							  $tempLembaga= $arrData[$checkbox_index]["LEMBAGA"];
							  $tempTahun= $arrData[$checkbox_index]["TAHUN"];
							  $tempNama= $arrData[$checkbox_index]["NAMA"];
							  $tempNomor= $arrData[$checkbox_index]["NOMOR"];
					  ?>
						  <tr>
							  <td>
                              	<input type="text" name="reqNama[]" id="reqNama<?=$checkbox_index?>" class="easyui-validatebox" style="width:200px;" value="<?=$tempNama?>" />
                              </td>
                              <td>
                              	<input type="text" name="reqLembaga[]" id="reqLembaga<?=$checkbox_index?>" class="easyui-validatebox" style="width:200px;" value="<?=$tempLembaga?>" />
                              </td>
                              <td>
                                <input type="text" name="reqNomor[]" id="reqNomor<?=$checkbox_index?>" class="easyui-validatebox" style="width:200px;" value="<?=$tempNomor?>" />
                              </td>
                              <td>
                                <input type="text" name="reqTahun[]" id="reqTahun<?=$checkbox_index?>" class="easyui-validatebox" style="width:100px;" value="<?=$tempTahun?>" />
                              </td>
                              <td align="center">
                              <label>
                                <input type="hidden" name="reqRowPelatihanId[]" id="reqRowPelatihanId<?=$checkbox_index?>" value="<?=$tempRowPelatihanId?>" />
                                <a style="cursor:pointer" onclick="deleteRowDrawTablePhp('dataTableRowPangkatDinamis', this, <?=$checkbox_index?>)"><img src="<?=$tempLinkCssSecurity?>images/icon-delete.png" width="15" height="15" border="0" /></a>
                              </label>
                              </td>
						  </tr>
					  <?
							$i++;
						  }
					  }
					  ?>
                    </tbody>
                </table>
                </td>
            </tr>
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
$("#reqTotalTahunBekerja,#reqKodePos,#reqKkUraian,#reqNpwp,#reqKodeTelpRumah,#reqTelpRumah").keypress(function(e) {
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});
</script>