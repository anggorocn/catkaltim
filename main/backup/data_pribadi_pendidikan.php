<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPendidikan.php");
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
//echo $set->query;exit;
$tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
unset($set);

$arrData="";
$index_data= 0;
$set= new PelamarPendidikan();
$set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId),-1,-1, "", "ORDER BY A.PELAMAR_PENDIDIKAN_ID ASC");
//echo $set->query;
while($set->nextRow())
{
	$arrData[$index_data]["PELAMAR_PENDIDIKAN_ID"] = $set->getField("PELAMAR_PENDIDIKAN_ID");
	$arrData[$index_data]["PENDIDIKAN_ID"] = $set->getField("PENDIDIKAN_ID");
	$arrData[$index_data]["JURUSAN"] = $set->getField("JURUSAN");
	$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
	$arrData[$index_data]["NOMOR"] = $set->getField("NOMOR");
	$arrData[$index_data]["TANGGAL"] = dateToPageCheck($set->getField("TANGGAL"));
	
	$index_data++;
}
if($index_data > 0)
{
	$tempRowJabatanId= $arrData[0]["PELAMAR_PENDIDIKAN_ID"];
	$tempPendidikanId= $arrData[0]["PENDIDIKAN_ID"];
	$tempJurusan= $arrData[0]["JURUSAN"];
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
			//url:'<?=$tempLinkSecurity?>json/data_pribadi_pendidikan',
			url:'<?=$tempLinkSecurity?>json/data_pribadi_pendidikan.php',
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
				if($tempIsStatusIsiFormulir == "3")
				{
				?>
				document.location.href = '?pg=data_pribadi_pelatihan';
				<?
				}
				else
				{
				?>
				document.location.href = '?pg=data_pribadi_pendidikan';
				<?
				}
				?>
				
			}
		});
		
	});
	
</script>

<script type="text/javascript">
function addRowPendidikan()
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(1);
	
	var rownum = tabBody.rows.length;
	row=document.createElement("TR");

	//start
	cell = document.createElement("TD");
	var element = document.createElement("select");
	element.type = "text";
	element.style.width = "120px";
	element.className='easyui-validatebox';
	element.setAttribute("name", "reqPendidikanId[]");
	var option = document.createElement("option");
	element.options[0] = new Option("Belum Dipilih", "");
	element.options[1] = new Option("Sekolah Dasar", "1");
	element.options[2] = new Option("SLTP", "2");
	element.options[3] = new Option("SLTA/SMU", "3");
	element.options[4] = new Option("Diploma", "4");
	element.options[5] = new Option("S1", "5");
	element.options[6] = new Option("S2", "6");
	element.options[7] = new Option("S3", "7");
	element.setAttribute('id', "reqPendidikanId"+rownum);
	cell.appendChild(element);
	row.appendChild(cell);
							  	
	//start
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqJurusan[]";
	element.id = "reqJurusan"+rownum;
	element.style.width = "200px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	
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
	element.name = "reqNomor[]";
	element.id = "reqNomor"+rownum;
	element.style.width = "200px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	
	/* KOLOM 7 */
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.style.textAlign='center';
	
	button.innerHTML = '<center>'
	+'<input type="hidden" name="reqRowPendidikanId[]" id="reqRowPendidikanId'+rownum+'" />'
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
			var valRowId= $("#reqRowPendidikanId"+rowId).val();
			$.getJSON('<?=$tempLinkSecurity?>json/delete.php?reqMode=data_pribadi_pendidikan&id='+valRowId, function (data) 
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

    <div id="judul-halaman">Riwayat Pendidikan</div>
    
    <form id="ff" method="post" novalidate enctype="multipart/form-data">
    <div id="pendaftaran" style="overflow-x:scroll">
        <table>
            <tr>
              <td colspan="3" class="judul-grup">
              <a style="cursor:pointer; float:right;" title="Tambah" onclick="addRowPendidikan()">Tambah Riwayat Pendidikan<img src="<?=$tempLinkCssSecurity?>images/tambah.png" width="16" height="16" border="0" /></a>
              </td>
            </tr>
            <tr>
            	<td colspan="3">
                <table class="jadwal" id="dataTableRowPangkatDinamis">
                    <thead>
                      <tr>
                      	  <th style="width:100px">Pendidikan</th>
                          <th>Jurusan</th>
                          <th>Nama Sekolah / Universitas</th>
                          <th>Nomor Ijazah</th>
                          <th style="width:50px">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="alternatecolor"> 
                      <?
					  if($index_data > 0)
					  {
						  for($checkbox_index=0;$checkbox_index<count($arrData);$checkbox_index++)
						  {
							  $tempRowJabatanId= $arrData[$checkbox_index]["PELAMAR_PENDIDIKAN_ID"];
							  $tempPendidikanId= $arrData[$checkbox_index]["PENDIDIKAN_ID"];
							  $tempJurusan= $arrData[$checkbox_index]["JURUSAN"];
							  $tempNama= $arrData[$checkbox_index]["NAMA"];
							  $tempNomor= $arrData[$checkbox_index]["NOMOR"];
					  ?>
						  <tr>
							  <td>
                              	<select name="reqPendidikanId[]" id="reqPendidikanId<?=$checkbox_index?>" required class="easyui-validatebox">
                                    <option value="" <? if($tempPendidikanId == "") echo "selected";?>>Belum Dipilih</option>
                                    <option value="1" <? if($tempPendidikanId == "1") echo "selected";?>>Sekolah Dasar</option>
                                    <option value="2" <? if($tempPendidikanId == "2") echo "selected";?>>SLTP</option>
                                    <option value="3" <? if($tempPendidikanId == "3") echo "selected";?>>SLTA/SMU</option>
                                    <option value="4" <? if($tempPendidikanId == "4") echo "selected";?>>Diploma</option>
                                    <option value="5" <? if($tempPendidikanId == "5") echo "selected";?>>S1</option>
                                    <option value="6" <? if($tempPendidikanId == "6") echo "selected";?>>S2</option>
                                    <option value="7" <? if($tempPendidikanId == "7") echo "selected";?>>S3</option>
                                </select>
                              </td>
                              <td>
                                <input type="text" name="reqJurusan[]" id="reqJurusan<?=$checkbox_index?>" class="easyui-validatebox" style="width:200px;" value="<?=$tempJurusan?>" />
                              </td>
                              <td>
                                <input type="text" name="reqNama[]" id="reqNama<?=$checkbox_index?>" class="easyui-validatebox" style="width:200px;" value="<?=$tempNama?>" />
                              </td>
                              <td>
                                <input type="text" name="reqNomor[]" id="reqNomor<?=$checkbox_index?>" class="easyui-validatebox" style="width:200px;" value="<?=$tempNomor?>" />
                              </td>
                              <td align="center">
                              <label>
                                <input type="hidden" name="reqRowPendidikanId[]" id="reqRowPendidikanId<?=$checkbox_index?>" value="<?=$tempRowJabatanId?>" />
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