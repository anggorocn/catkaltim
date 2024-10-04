<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalAcara.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/JadwalKelompokRuangan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode 	= httpFilterRequest("reqMode");
$reqId = httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");

$jadwal_acara = new JadwalAcara();

$jadwal_acara->selectByParamsMonitoring(array("JADWAL_ACARA_ID"=> $reqRowId),-1,-1,'');
//echo $jadwal_acara->query;exit;
$jadwal_acara->firstRow();
$tempJadwalTesId= $jadwal_acara->getField('JADWAL_TES_ID');
$tempPenggalianNama= $jadwal_acara->getField('PENGGALIAN_NAMA');
$tempPenggalianId= $jadwal_acara->getField('PENGGALIAN_ID');
$tempPukul1= $jadwal_acara->getField('PUKUL1');
$tempPukul2= $jadwal_acara->getField('PUKUL2');
$tempKeterangan= $jadwal_acara->getField('KETERANGAN_ACARA');
$tempJadwalKelompokRuangData= $jadwal_acara->getField('JADWAL_KELOMPOK_RUANG_DATA');

$index_loop= 0;
if($reqRowId == ""){}
else
{
	$arrJadwalAsesor="";
	$statement= " AND A.JADWAL_ACARA_ID = ".$reqRowId;
	$set_detil= new JadwalAsesor();
	$set_detil->selectByParamsMonitoring(array(), -1,-1, $statement);
	//echo $set_detil->query;exit;
	while($set_detil->nextRow())
	{
		$arrJadwalAsesor[$index_loop]["JADWAL_ASESOR_ID"]= $set_detil->getField("JADWAL_ASESOR_ID");
		$arrJadwalAsesor[$index_loop]["ASESOR_ID"]= $set_detil->getField("ASESOR_ID");
		$arrJadwalAsesor[$index_loop]["ASESOR_NAMA"]= $set_detil->getField("ASESOR_NAMA");
		$arrJadwalAsesor[$index_loop]["KELOMPOK"]= $set_detil->getField("KELOMPOK");
		$arrJadwalAsesor[$index_loop]["RUANG"]= $set_detil->getField("RUANG");
		$arrJadwalAsesor[$index_loop]["JADWAL_KELOMPOK_RUANGAN_ID"]= $set_detil->getField("JADWAL_KELOMPOK_RUANGAN_ID");
		$arrJadwalAsesor[$index_loop]["KELOMPOK_RUANGAN_NAMA"]= $set_detil->getField("KELOMPOK_RUANGAN_NAMA");
		$arrJadwalAsesor[$index_loop]["KETERANGAN_JADWAL"]= $set_detil->getField("KETERANGAN_JADWAL");
		$arrJadwalAsesor[$index_loop]["TOTAL_JAM_ASESOR"]= $set_detil->getField("TOTAL_JAM_ASESOR");
		$index_loop++;
	}
}

if($index_loop > 0)
{
	$tempKelompok= $arrJadwalAsesor[0]["KELOMPOK"];
	$tempRuang= $arrJadwalAsesor[0]["RUANG"];
}
$jumlah_asesor= $index_loop;

$statement= " AND A.JADWAL_ACARA_ID = ".$reqRowId;
$arrJadwalKelompokRuangan="";
$index_arr= 0;
$jadwal_kelompok_ruangan= new JadwalKelompokRuangan();
$jadwal_kelompok_ruangan->selectByParamsMonitoring(array(), -1,-1, $statement);
//echo $jadwal_kelompok_ruangan->query;exit;
while($jadwal_kelompok_ruangan->nextRow())
{
	$arrJadwalKelompokRuangan[$index_arr]["JADWAL_KELOMPOK_RUANGAN_ID"] = $jadwal_kelompok_ruangan->getField("JADWAL_KELOMPOK_RUANGAN_ID");
	$arrJadwalKelompokRuangan[$index_arr]["KELOMPOK_RUANGAN_NAMA"] = $jadwal_kelompok_ruangan->getField("KELOMPOK_RUANGAN_NAMA");
	$index_arr++;
}
unset($jadwal_kelompok_ruangan);

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

<link rel="stylesheet" href="../WEB/lib/autokomplit/jquery-ui.css">

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="../WEB/lib/autokomplit/jquery-ui.css">
<script src="../WEB/lib/autokomplit/jquery-ui.js"></script>  
<style>
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        font-size:11px;
        overflow-x: hidden;
    }
    /* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
    * html .ui-autocomplete {
        height: 200px;
    }
</style>

<!-- AUTO KOMPLIT -->
<script type="text/javascript" src="../WEB/lib/easyui/easyloader.js"></script>   
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.form.js"></script>  
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.linkbutton.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.draggable.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.resizable.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.panel.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.window.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.progressbar.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.messager.js"></script>      
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.tooltip.js"></script>  
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.validatebox.js"></script>  
<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.combo.js"></script>
    
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
	$(function(){
		$('#ff').form({
			url:'../json-pengaturan/master_jadwal_add_asesor.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);return false;
				$.messager.alert('Info', data, 'info');
				$('#rst_form').click();
				//parent.setShowHideMenu(3);
				parent.frames['mainFrame'].location.href = 'master_jadwal_add_asesor_monitoring.php?reqId=<?=$reqId?>';
				parent.frames['mainFrameDetil'].location.href = 'master_jadwal_add_asesor.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
			}
		});
		
		$('input[id^="reqAsesor"]').autocomplete({ 
			source:function(request, response){
				var id= this.element.attr('id');
				var replaceAnakId= replaceAnak= urlAjax= "";
				
				if (id.indexOf('reqAsesor') !== -1)
				{
					var element= id.split('reqAsesor');
					var indexId= "reqAsesorId"+element[1];
					urlAjax= "../json-pengaturan/asesor_auto_combo_json.php";
				}
				
				$.ajax({
					url: urlAjax,
					type: "GET",
					dataType: "json",
					data: { term: request.term },
					success: function(responseData){
						$("#"+indexId).val("").trigger('change');
						if(replaceAnakId == ""){}
						else
						{
						$("#"+replaceAnakId).val("").trigger('change');
						$("#"+replaceAnak).val("").trigger('change');
						}
						
						if(responseData == null)
						{
							response(null);
						}
						else
						{
							var array = responseData.map(function(element) {
								return {desc: element['desc'], id: element['id'], label: element['label']};
							});
							response(array);
						}
					}
				})
			},
			select: function (event, ui) 
			{ 
				var id= $(this).attr('id');
				var replaceAnakId= replaceAnak= "";
				
				if (id.indexOf('reqAsesor') !== -1)
				{
					var element= id.split('reqAsesor');
					var indexId= "reqAsesorId"+element[1];
				}
				$("#"+indexId).val(ui.item.id).trigger('change');
			}, 
			//minLength:3,
			autoFocus: true
			}).autocomplete( "instance" )._renderItem = function( ul, item ) {
				return $( "<li>" )
			  .append( "<a>" + item.desc + "</a>" )
			  .appendTo( ul );
		};
			
	});
	
	function addAsesorRow()
	{
		if (!document.getElementsByTagName) return;
		tabBody=document.getElementsByTagName("TBODY").item(1);
		
		var rownum= tabBody.rows.length;
		
		var reqInfoTotalPeserta= "";
		reqInfoTotalPeserta= $("#reqInfoTotalPeserta").text();
		reqInfoTotalPeserta= parseInt(reqInfoTotalPeserta) + 1;
		$("#reqInfoTotalPeserta").text(reqInfoTotalPeserta);
		
		row=document.createElement("TR");
		
		cell = document.createElement("TD");
		var button = document.createElement('label');
		
		button.innerHTML = '<input type="hidden" name="reqAsesorId[]" id="reqAsesorId'+rownum+'" />'
		+'<label id="reqAsesor'+rownum+'"></label>'
		+'<img src="../WEB/images/icn_search.png" onClick="openPencarianKaryawan('+rownum+')" style="cursor:pointer" />';
		cell.appendChild(button);
		row.appendChild(cell);
		
		cell = document.createElement("TD");
		var button = document.createElement('label');
		button.innerHTML = '<label id="reqTotalJamAsesor'+rownum+'"></label> ';
		cell.appendChild(button);
		row.appendChild(cell);
		
		cell = document.createElement("TD");
		var element = document.createElement("select");
		element.setAttribute("name", "reqJadwalKelompokRuanganId[]");
		var option = document.createElement("option");
		element.options[0] = new Option("", "");
		<?
		for($index_loop=0; $index_loop < count($arrJadwalKelompokRuangan); $index_loop++)
		{
			$tempIndexLoop= $index_loop+1;
		?>
			element.options[<?=$tempIndexLoop?>] = new Option("<?=$arrJadwalKelompokRuangan[$index_loop]["KELOMPOK_RUANGAN_NAMA"]?>", "<?=$arrJadwalKelompokRuangan[$index_loop]["JADWAL_KELOMPOK_RUANGAN_ID"]?>");
		<?
		}
		?>
		element.setAttribute('id', "reqJadwalKelompokRuanganId"+rownum);
		element.style.width= "100%";
		cell.appendChild(element);
		row.appendChild(cell);
			
		cell = document.createElement("TD");
		var button = document.createElement('label');
		button.innerHTML = '<input type="text" name="reqKeterangan[]" id="reqKeterangan'+rownum+'" style="width:100%" /> ';
		cell.appendChild(button);
		row.appendChild(cell);
		
		cell = document.createElement("TD");
		var button = document.createElement('label');
		button.innerHTML = '<input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId'+rownum+'" />'
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
				//setHitungTotal();
				//setMasakanUtamaHitung();
				//top.frames['mainFrame'].location.reload();
			}
		}
		}catch(e) {
			alert(e);
		}
	}
	
	function addKaryawanPopUp()
	{
		var tempPegawaiId= separatorTempRowId= anSelectedId= "";
		tabBody=document.getElementsByTagName("TBODY").item(1);
		var rownum= tabBody.rows.length;
		if(rownum > 0)
		{
			for(var i=0; i < rownum; i++)
			{
				anSelectedId= $("#reqAsesorId"+i).val();
				if(tempPegawaiId == "")
					separatorTempRowId= "";
				else
					separatorTempRowId= ",";
				
				if(anSelectedId == ""){}
				else
				tempPegawaiId= tempPegawaiId+separatorTempRowId+anSelectedId;
			}
		}
		//alert(tempPegawaiId);return false;
		parent.OpenDHTML('asesor_jadwal_pilih_pencarian.php?reqId=<?=$reqId?>&reqRowId='+rownum+'&reqAsesorId='+tempPegawaiId, 'Pencarian Asesor', 780, 500)
	}
	
	function openPencarianKaryawan(rowid)
	{
		var tempAsesorId= separatorTempRowId= anSelectedId= "";
		tabBody=document.getElementsByTagName("TBODY").item(1);
		var rownum= tabBody.rows.length;
		if(rownum > 0)
		{
			for(var i=0; i < rownum; i++)
			{
				anSelectedId= $("#reqAsesorId"+i).val();
				if(tempAsesorId == "")
					separatorTempRowId= "";
				else
					separatorTempRowId= ",";
				
				if(anSelectedId == ""){}
				else
				tempAsesorId= tempAsesorId+separatorTempRowId+anSelectedId;
			}
		}
		parent.OpenDHTML('asesor_jadwal_pencarian.php?reqRowId='+rowid+'&reqAsesorId='+tempAsesorId, 'Pencarian Asesor', 780, 500)
	}
	
	var tempId= tempJabatan= tempDepartemen= tempNama= tempKelas= "";
	function OptionSet(id, rowid)
	{
		tempId=id;
		$.getJSON('../json-pengaturan/asesor_get_json.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqAsesorId='+ id,
		  function(data){
			reqAsesorId= data.tempId;
			reqAsesor=data.tempNama;
			tempJamAsesor= data.tempJamAsesor;
			statusJamAsesor= data.statusJamAsesor;
			
			$("#reqAsesorId"+rowid).val(reqAsesorId);
			$("#reqAsesor"+rowid).text(reqAsesor);
			$("#reqTotalJamAsesor"+rowid).text(tempJamAsesor);
			if(statusJamAsesor == "1")
			$("#reqTotalJamAsesor"+rowid).css("color", "#F33");
		  });
	}
	
	function setJadwalKelompokRuangan()
	{
	}
</script>

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

<style>
	/* UNTUK TABLE GRADIENT STYLE*/
	.gradient-style th {
	font-size: 12px;
	font-weight:400;
	background:#b9c9fe url(images/gradhead.png) repeat-x;
	border-top:2px solid #d3ddff;
	border-bottom:1px solid #fff;
	color:#039;
	padding:8px;
	}
	
	.gradient-style td {
	font-size: 12px;
	border-bottom:1px solid #fff;
	color:#669;
	border-top:1px solid #fff;
	background:#e8edff url(images/gradback.png) repeat-x;
	padding:8px;
	}
	
	.gradient-style tfoot tr td {
	background:#e8edff;
	font-size: 14px;
	color:#99c;
	}
	
	.gradient-style tbody tr:hover td {
	background:#d0dafd url(images/gradhover.png) repeat-x;
	color:#339;
	}
	
	.gradient-style {
	font-family: 'Open SansRegular';
	font-size: 14px;
	width:480px;
	text-align:left;
	border-collapse:collapse;
	margin:0px 0px 0px 10px;
	}
</style>
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; width:100%">
    <form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="100%">
        <tr>           
            <td style="width:150px">Penggalian</td><td style="width:5px">:</td>
            <td><?=$tempPenggalianNama?></td>
        </tr>
        <tr>
            <td>Pukul</td><td>:</td>
            <td><?=$tempPukul1?> s/d <?=$tempPukul2?></td>	
        </tr>
        <tr>
            <td>Keterangan</td><td>:</td>
            <td><?=$tempKeterangan?></td>	
        </tr>
        <tr>
            <td style="vertical-align:top">Kelompok & Ruangan</td><td style="vertical-align:top">:</td>
            <td><?=$tempJadwalKelompokRuangData?></td>	
        </tr>
        <tr>
            <td>Total Asesor</td>
            <td>:</td>
            <td><label id="reqInfoTotalPeserta"><?=$jumlah_asesor?></label></td>
        </tr>
        <?
		if($reqRowId == ""){}
		else
		{
        ?>
        <tr>
            <td>
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
                <input type="hidden" name="reqId" value="<?=$reqId?>">
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
        <th scope="col" style="width:25%">
        Nama Asesor
        <?
		if($reqRowId == ""){}
		else
		{
        ?>
        <a style="cursor:pointer" title="Tambah" onclick="addKaryawanPopUp()"><img src="../WEB/images/icn_add.gif" width="16" height="16" border="0" /></a>
        <?
		}
        ?>
        </th>
        <th scope="col" style="text-align:center; width:15%">Total Jam dalam Hari ini</th>
        <th scope="col" style="text-align:center; width:20%">Kelompok & Ruangan</th>
        <th scope="col" style="text-align:center; width:30%">Keterangan</th>
        <th scope="col" style="text-align:center; width:50px">Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?
	for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
	{
		$tempJadwalAsesorId= $arrJadwalAsesor[$checkbox_index]["JADWAL_ASESOR_ID"];
		$tempAsesorId= $arrJadwalAsesor[$checkbox_index]["ASESOR_ID"];
		$tempAsesor= $arrJadwalAsesor[$checkbox_index]["ASESOR_NAMA"];
		$tempTotalJamAsesor= getTimeIndo($arrJadwalAsesor[$checkbox_index]["TOTAL_JAM_ASESOR"]);
		$styleCss= "";
		if(getTimeJam($arrJadwalAsesor[$checkbox_index]["TOTAL_JAM_ASESOR"]) >= 5)
		$styleCss= "color:#F33";
		
		//;KELOMPOK_RUANGAN_NAMA
		$tempJadwalKelompokRuanganId= $arrJadwalAsesor[$checkbox_index]["JADWAL_KELOMPOK_RUANGAN_ID"];
		$tempKeteranganAsesor= $arrJadwalAsesor[$checkbox_index]["KETERANGAN_JADWAL"];
    ?>
    <tr>
        <td>
        	<input type="hidden" name="reqAsesorId[]" id="reqAsesorId<?=$checkbox_index?>" value="<?=$tempAsesorId?>" />
            <label id="reqAsesor<?=$checkbox_index?>"><?=$tempAsesor?></label>
        	<img src="../WEB/images/icn_search.png" onClick="openPencarianKaryawan(<?=$checkbox_index?>)" style="cursor:pointer" />
        </td>
        <td>
        	<label style=" <?=$styleCss?>" id="reqTotalJamAsesor<?=$checkbox_index?>"><?=$tempTotalJamAsesor?></label>
        </td>
        <td>
            <select id="reqJadwalKelompokRuanganId<?=$checkbox_index?>" name="reqJadwalKelompokRuanganId[]" style="width:100%">
            <option value=""></option>
			<?
            for($index_loop=0; $index_loop < count($arrJadwalKelompokRuangan); $index_loop++)
            {
            ?>
                <option value="<?=$arrJadwalKelompokRuangan[$index_loop]["JADWAL_KELOMPOK_RUANGAN_ID"]?>" <? if($arrJadwalKelompokRuangan[$index_loop]["JADWAL_KELOMPOK_RUANGAN_ID"] == $tempJadwalKelompokRuanganId) echo "selected";?>><?=$arrJadwalKelompokRuangan[$index_loop]["KELOMPOK_RUANGAN_NAMA"]?></option>
            <?
            }
            ?>
            </select>
        </td>
        <td>
            <input type="text" name="reqKeterangan[]" id="reqKeterangan<?=$checkbox_index?>" style="width:100%" value="<?=$tempKeteranganAsesor?>" /> 
        </td>
        <td>
        	<center><a style="cursor:pointer" onclick="deleteRowDrawTablePhp('tableAsesor', this, '<?=$checkbox_index?>', 'reqJadwalAsesorId', 'jadwal_asesor')"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>
            <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId<?=$checkbox_index?>" value="<?=$tempJadwalAsesorId?>" />
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