<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode 	= httpFilterRequest("reqMode");
$reqId = httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");

$jadwal_asesor= new JadwalAsesor();
$jadwal_asesor->selectByParamsMonitoring(array("A.JADWAL_ASESOR_ID"=> $reqRowId),-1,-1,'');
$jadwal_asesor->firstRow();
// echo $jadwal_asesor->query;exit;
$tempJadwalTesId= $jadwal_asesor->getField('JADWAL_TES_ID');
$tempPenggalianNama= $jadwal_asesor->getField('PENGGALIAN_NAMA');
$tempPenggalianId= $jadwal_asesor->getField('PENGGALIAN_ID');
$tempPukul1= $jadwal_asesor->getField('PUKUL1');
$tempPukul2= $jadwal_asesor->getField('PUKUL2');
$tempKeterangan= $jadwal_asesor->getField('KETERANGAN_ACARA');
$tempPegawaiNama= $jadwal_asesor->getField('NAMA');
$tempKelompokRuangNama= $jadwal_asesor->getField('KELOMPOK_RUANGAN_NAMA');
$tempJadwalKelompokRuangData= $jadwal_asesor->getField('JADWAL_KELOMPOK_RUANG_DATA');

$index_loop= 0;
if($reqRowId == ""){}
else
{
	$arrJadwalAsesor="";
	$statement= " AND A.JADWAL_ASESOR_ID = ".$reqRowId;
	$set_detil= new JadwalPegawai();
	$set_detil->selectByParamsPegawai(array(), -1,-1, $statement);
	// echo $set_detil->query;exit;
	while($set_detil->nextRow())
	{
		$arrJadwalAsesor[$index_loop]["SATKER_TES_ID"]= $set_detil->getField("SATKER_TES_ID");
		$arrJadwalAsesor[$index_loop]["TANGGAL_TES"]= dateToPageCheck($set_detil->getField("TANGGAL_TES"));
		
		$arrJadwalAsesor[$index_loop]["JADWAL_PEGAWAI_ID"]= $set_detil->getField("JADWAL_PEGAWAI_ID");
		$arrJadwalAsesor[$index_loop]["PEGAWAI_ID"]= $set_detil->getField("PEGAWAI_ID");
		$arrJadwalAsesor[$index_loop]["PEGAWAI_NAMA"]= $set_detil->getField("PEGAWAI_NAMA");
		$arrJadwalAsesor[$index_loop]["PEGAWAI_NIP"]= $set_detil->getField("PEGAWAI_NIP");
		$arrJadwalAsesor[$index_loop]["PEGAWAI_GOL"]= $set_detil->getField("PEGAWAI_GOL");
		$arrJadwalAsesor[$index_loop]["PEGAWAI_ESELON"]= $set_detil->getField("PEGAWAI_ESELON");
		$arrJadwalAsesor[$index_loop]["SATKER"]= $set_detil->getField("SATKER");
		$arrJadwalAsesor[$index_loop]["PEGAWAI_JAB_STRUKTURAL"]= $set_detil->getField("PEGAWAI_JAB_STRUKTURAL");
		$arrJadwalAsesor[$index_loop]["KETERANGAN_JADWAL"]= $set_detil->getField("KETERANGAN_JADWAL");
		$index_loop++;
	}
}
$jumlah_asesor= $index_loop;
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
			url:'../json-pengaturan/master_jadwal_add_pegawai.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				$.messager.alert('Info', data, 'info');
				$('#rst_form').click();
				//parent.setShowHideMenu(3);
				parent.frames['mainFrame'].location.href = 'master_jadwal_add_pegawai_monitoring.php?reqId=<?=$reqId?>';
				parent.frames['mainFrameDetil'].location.href = 'master_jadwal_add_pegawai.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
			}
		});
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
		
		button.innerHTML = '<input type="hidden" name="reqPegawaiId[]" id="reqPegawaiId'+rownum+'" />'
		+'<label id="reqPegawai'+rownum+'"></label>'
		+'<img src="../WEB/images/icn_search.png" onClick="openPencarianKaryawan('+rownum+')" style="cursor:pointer" />';
		cell.appendChild(button);
		row.appendChild(cell);
		
		cell = document.createElement("TD");
		var button = document.createElement('label');
		button.innerHTML = '<label id="reqPegawaiNip'+rownum+'"></label>';
		cell.appendChild(button);
		row.appendChild(cell);
		
		cell = document.createElement("TD");
		var button = document.createElement('label');
		button.innerHTML = '<label id="reqPegawaiGol'+rownum+'"></label>';
		cell.appendChild(button);
		row.appendChild(cell);
		
		cell = document.createElement("TD");
		var button = document.createElement('label');
		button.innerHTML = '<label id="reqPegawaiEselon'+rownum+'"></label>';
		cell.appendChild(button);
		row.appendChild(cell);
		
		cell = document.createElement("TD");
		var button = document.createElement('label');
		button.innerHTML = '<label id="reqPegawaiJabatan'+rownum+'"></label>';
		cell.appendChild(button);
		row.appendChild(cell);
		
		cell = document.createElement("TD");
		var button = document.createElement('label');
		button.innerHTML = '<input type="hidden" name="reqJadwalPegawaiId[]" id="reqJadwalPegawaiId'+rownum+'" />'
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
				anSelectedId= $("#reqPegawaiId"+i).val();
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
		parent.OpenDHTML('pegawai_simulasi_detil_pencarian.php?reqId=<?=$reqId?>&reqJadwalTesId=<?=$tempJadwalTesId?>&reqPenggalianId=<?=$tempPenggalianId?>&reqRowId='+rownum+'&reqPegawaiId='+tempPegawaiId, 'Pencarian Pegawai', 780, 500)
	}
	
	function openPencarianKaryawan(rowid)
	{
		var tempPegawaiId= separatorTempRowId= anSelectedId= "";
		tabBody=document.getElementsByTagName("TBODY").item(1);
		var rownum= tabBody.rows.length;
		if(rownum > 0)
		{
			for(var i=0; i < rownum; i++)
			{
				anSelectedId= $("#reqPegawaiId"+i).val();
				if(tempPegawaiId == "")
					separatorTempRowId= "";
				else
					separatorTempRowId= ",";
				
				if(anSelectedId == ""){}
				else
				tempPegawaiId= tempPegawaiId+separatorTempRowId+anSelectedId;
			}
		}
		parent.OpenDHTML('pegawai_jadwal_pencarian.php?reqId=<?=$reqId?>&reqRowId='+rowid+'&reqPegawaiId='+tempPegawaiId, 'Pencarian Pegawai', 780, 500)
	}
	
	var tempId= tempJabatan= tempDepartemen= tempNama= tempKelas= "";
	function OptionSet(id, rowid)
	{
		tempId=id;
		$.getJSON('../json-pengaturan/pegawai_get_json.php?reqId=' + id,
		  function(data){
			reqPegawaiId= data.tempId;
			reqPegawai=data.tempNama;
			tempNip= data.tempNip;
			tempGol= data.tempGol;
			tempEselon= data.tempEselon;
			tempJabatan= data.tempJabatan;
			$("#reqPegawaiId"+rowid).val(reqPegawaiId);
			$("#reqPegawai"+rowid).text(reqPegawai);
			$("#reqPegawaiNip"+rowid).text(tempNip);
			$("#reqPegawaiGol"+rowid).text(tempGol);
			$("#reqPegawaiEselon"+rowid).text(tempEselon);
			$("#reqPegawaiJabatan"+rowid).text(tempJabatan);
		  });
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
            <td>Asesor</td><td>:</td>
            <td><?=$tempPegawaiNama?></td>	
        </tr>
        <tr>
            <td>Keterangan</td><td>:</td>
            <td><?=$tempKeterangan?></td>	
        </tr>
       <!--  <tr>
            <td style="vertical-align:top">Kelompok & Ruangan</td><td style="vertical-align:top">:</td>
            <td><?=$tempKelompokRuangNama?></td>	
        </tr> -->
        <tr>
            <td>Total Peserta</td>
            <td>:</td>
            <td><label id="reqInfoTotalPeserta"><?=$index_loop?></label></td>
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
                <input type="hidden" name="reqPenggalianId" value="<?=$tempPenggalianId?>">
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
        Nama Pegawai
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
        <th scope="col" style="width:5%">NIP</th>
        <th scope="col" style="width:5%">Gol.Ruang</th>
        <th scope="col" style="width:5%">Eselon</th>
        <th scope="col" style="width:20%">Jabatan</th>
        <th scope="col" style="width:40%">Satker</th>
        <th scope="col" style="text-align:center; width:50px">Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?
	for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
	{
		$tempJadwalAsesorId= $arrJadwalAsesor[$checkbox_index]["JADWAL_PEGAWAI_ID"];
		$tempPegawaiId= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_ID"];
		//$tempPegawai= "NIP: ".$arrJadwalAsesor[$checkbox_index]["PEGAWAI_NIP"].", Nama: ".$arrJadwalAsesor[$checkbox_index]["PEGAWAI_NAMA"];
		
		$tempPegawaiSatkerTesId= $arrJadwalAsesor[$checkbox_index]["SATKER_TES_ID"];
		$tempPegawaiTanggalTes= $arrJadwalAsesor[$checkbox_index]["TANGGAL_TES"];
		
		$tempPegawai= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_NAMA"];
		$tempPegawaiNip= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_NIP"];
		$tempPegawaiGol= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_GOL"];
		$tempPegawaiEselon= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_ESELON"];
		$tempPegawaiSatker= $arrJadwalAsesor[$checkbox_index]["SATKER"];
		$tempPegawaiJabatan= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_JAB_STRUKTURAL"];
		$tempKeteranganAsesor= $arrJadwalAsesor[$checkbox_index]["KETERANGAN_JADWAL"];
    ?>
    <tr>
        <td>
            <input type="hidden" name="reqPegawaiSatkerTesId[]" id="reqPegawaiSatkerTesId<?=$checkbox_index?>" value="<?=$tempPegawaiSatkerTesId?>" />
            <input type="hidden" name="reqPegawaiTanggalTesId[]" id="reqPegawaiTanggalTesId<?=$checkbox_index?>" value="<?=$tempPegawaiTanggalTes?>" />
            <input type="hidden" name="reqPegawaJabatan[]" id="reqPegawaJabatan<?=$checkbox_index?>" value="<?=$tempPegawaiJabatan?>" />
            <input type="hidden" name="reqPegawaiId[]" id="reqPegawaiId<?=$checkbox_index?>" value="<?=$tempPegawaiId?>" />
            <label id="reqPegawai<?=$checkbox_index?>"><?=$tempPegawai?></label>
        	<img src="../WEB/images/icn_search.png" onClick="openPencarianKaryawan(<?=$checkbox_index?>)" style="cursor:pointer" />
        </td>
        <td><label id="reqPegawaiNip<?=$checkbox_index?>"><?=$tempPegawaiNip?></label></td>
        <td><label id="reqPegawaiGol<?=$checkbox_index?>"><?=$tempPegawaiGol?></label></td>
        <td><label id="reqPegawaiEselon<?=$checkbox_index?>"><?=$tempPegawaiEselon?></label></td>
        <td><label id="reqPegawaiJabatan<?=$checkbox_index?>"><?=$tempPegawaiJabatan?></label></td>
        <td><label id="reqPegawaiJabatan<?=$checkbox_index?>"><?=$tempPegawaiSatker?></label></td>
        <td>
        	<center><a style="cursor:pointer" onclick="deleteRowDrawTablePhp('tableAsesor', this, '<?=$checkbox_index?>', 'reqJadwalPegawaiId', 'jadwal_pegawai')"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>
            <input type="hidden" name="reqJadwalPegawaiId[]" id="reqJadwalPegawaiId<?=$checkbox_index?>" value="<?=$tempJadwalAsesorId?>" />
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