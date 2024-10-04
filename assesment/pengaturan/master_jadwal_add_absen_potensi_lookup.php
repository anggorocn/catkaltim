<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalAcara.php");
include_once("../WEB/classes/base/JadwalAsesorPotensi.php");
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
$reqNotId= httpFilterGet("reqNotId");

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
	$arrJadwalAsesorPotensi="";
	$statement= " AND A.JADWAL_ACARA_ID = ".$reqRowId;
	if($reqNotId == ""){}
	else
	$statement.= " AND A.JADWAL_ASESOR_POTENSI_ID NOT IN (".$reqNotId.")";

	$set_detil= new JadwalAsesorPotensi();
	$set_detil->selectByParamsMonitoring(array(), -1,-1, $statement);
	// echo $set_detil->query;exit;
	while($set_detil->nextRow())
	{
		$arrJadwalAsesorPotensi[$index_loop]["JADWAL_ASESOR_POTENSI_ID"]= $set_detil->getField("JADWAL_ASESOR_POTENSI_ID");
		$arrJadwalAsesorPotensi[$index_loop]["ASESOR_ID"]= $set_detil->getField("ASESOR_ID");
		$arrJadwalAsesorPotensi[$index_loop]["ASESOR_NAMA"]= $set_detil->getField("ASESOR_NAMA");
		$arrJadwalAsesorPotensi[$index_loop]["KELOMPOK"]= $set_detil->getField("KELOMPOK");
		$arrJadwalAsesorPotensi[$index_loop]["RUANG"]= $set_detil->getField("RUANG");
		$arrJadwalAsesorPotensi[$index_loop]["JADWAL_KELOMPOK_RUANGAN_ID"]= $set_detil->getField("JADWAL_KELOMPOK_RUANGAN_ID");
		$arrJadwalAsesorPotensi[$index_loop]["KELOMPOK_RUANGAN_NAMA"]= $set_detil->getField("KELOMPOK_RUANGAN_NAMA");
		$arrJadwalAsesorPotensi[$index_loop]["KETERANGAN_JADWAL"]= $set_detil->getField("KETERANGAN_JADWAL");
		$arrJadwalAsesorPotensi[$index_loop]["TOTAL_JAM_ASESOR"]= $set_detil->getField("TOTAL_JAM_ASESOR");
		$index_loop++;
	}
}

if($index_loop > 0)
{
	$tempKelompok= $arrJadwalAsesorPotensi[0]["KELOMPOK"];
	$tempRuang= $arrJadwalAsesorPotensi[0]["RUANG"];
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
	function pilihdata(id)
	{
		var reqJadwalAsesorPotensiId= reqAsesorId= "";
		reqJadwalAsesorPotensiId= $("#reqJadwalAsesorPotensiId"+id).val();

		parent.getJadwalPotensiAsesor("<?=$reqRowId?>", reqJadwalAsesorPotensiId);
		parent.divwin.close();
       	// console.log(reqJadwalAsesorId);
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
    <table class="table_list" cellspacing="1" width="100%">
        <tr>
            <td style="width:150px">Pukul</td><td style="width:5px">:</td>
            <td><?=$tempPukul1?> s/d <?=$tempPukul2?></td>	
        </tr>
        <tr>
            <td>Keterangan</td><td>:</td>
            <td><?=$tempKeterangan?></td>	
        </tr>
        <tr style="display:none">
            <td style="vertical-align:top">Kelompok & Ruangan</td><td style="vertical-align:top">:</td>
            <td><?=$tempJadwalKelompokRuangData?></td>	
        </tr>
    </table>
    <table class="gradient-style" id="tableAsesor" style="width:100%; margin-left:-1px">
    <thead>
    <tr>
    	<th scope="col">
        Nama Asesor
        </th>
        <th scope="col" style="text-align:center; width:50px">Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?
	for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
	{
		$tempJadwalAsesorPotensiId= $arrJadwalAsesorPotensi[$checkbox_index]["JADWAL_ASESOR_POTENSI_ID"];
		$tempAsesorId= $arrJadwalAsesorPotensi[$checkbox_index]["ASESOR_ID"];
		$tempAsesor= $arrJadwalAsesorPotensi[$checkbox_index]["ASESOR_NAMA"];
		$tempTotalJamAsesor= getTimeIndo($arrJadwalAsesorPotensi[$checkbox_index]["TOTAL_JAM_ASESOR"]);
		$styleCss= "";
		if(getTimeJam($arrJadwalAsesorPotensi[$checkbox_index]["TOTAL_JAM_ASESOR"]) >= 5)
		$styleCss= "color:#F33";
		
		//;KELOMPOK_RUANGAN_NAMA
		$tempJadwalKelompokRuanganId= $arrJadwalAsesorPotensi[$checkbox_index]["JADWAL_KELOMPOK_RUANGAN_ID"];
		$tempKeteranganAsesor= $arrJadwalAsesorPotensi[$checkbox_index]["KETERANGAN_JADWAL"];
    ?>
    <tr>
        <td>
        	<input type="hidden" name="reqAsesorId[]" id="reqAsesorId<?=$checkbox_index?>" value="<?=$tempAsesorId?>" />
            <label id="reqAsesor<?=$checkbox_index?>"><?=$tempAsesor?></label>
        </td>
        <td>
        	<center><button style="cursor:pointer" onclick="pilihdata('<?=$checkbox_index?>')">Pilih</button>
            <input type="hidden" name="reqJadwalAsesorPotensiId[]" id="reqJadwalAsesorPotensiId<?=$checkbox_index?>" value="<?=$tempJadwalAsesorPotensiId?>" />
        </td>
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