<?

// echo "Dadad"; exit;
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalTesSimulasiPegawai.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode 	= httpFilterRequest("reqMode");
$reqId = httpFilterGet("reqId");



if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data jadwal terlebih dahulu.');";	
	echo "window.parent.location.href = 'master_jadwal_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}
 
$urlkarpeg= '../../uploads/';



$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;

$tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), 'date');
$tempTanggalTesInfo= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');
$reqStatusValid= $set->getField('STATUS_VALID');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$index_loop= 0;
$arrJadwalAsesor="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set_detil= new JadwalTesSimulasiPegawai();
$set_detil->selectByParamsTanggalPegawai(array(), -1,-1, $statement);
// echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrJadwalAsesor[$index_loop]["SATKER_TES_ID"]= $set_detil->getField("SATKER_TES_ID");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_ID"]= $set_detil->getField("PEGAWAI_ID");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_NAMA"]= $set_detil->getField("PEGAWAI_NAMA");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_NIP"]= $set_detil->getField("PEGAWAI_NIP");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_GOL"]= $set_detil->getField("PEGAWAI_GOL");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_ESELON"]= $set_detil->getField("PEGAWAI_ESELON");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_JAB_STRUKTURAL"]= $set_detil->getField("PEGAWAI_JAB_STRUKTURAL");
	$arrJadwalAsesor[$index_loop]["KETERANGAN_JADWAL"]= $set_detil->getField("KETERANGAN_JADWAL");
	$arrJadwalAsesor[$index_loop]["LINK_FILE1"]= $set_detil->getField("LINK_FILE1");
	$arrJadwalAsesor[$index_loop]["LINK_FILE2"]= $set_detil->getField("LINK_FILE2");
	$arrJadwalAsesor[$index_loop]["LINK_FILE3"]= $set_detil->getField("LINK_FILE3");
	$arrJadwalAsesor[$index_loop]["LINK_FOTO"]= $set_detil->getField("LINK_FOTO");
	$arrJadwalAsesor[$index_loop]["LAST_UPDATE_DATE"]= $set_detil->getField("LAST_UPDATE_DATE");
	$index_loop++;
}
$jumlah_asesor= $index_loop;
//print_r($arrJadwalAsesor);exit;

$statement= " AND JADWAL_TES_ID = ".$reqId;
$set_validasi= new JadwalTesSimulasiAsesor();
$set_validasi->selectByParams(array(), -1,-1, $statement);
$set_validasi->firstRow();
$tempStatusValidasi= $set_validasi->getField("STATUS");
unset($set_validasi);

$tempStatusValidasi= $reqStatusValid;
$tempStatusValidasi= 1;
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
	img.gambar {
    display: block;
    margin: 0 auto;
}
</style>
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
			url:'../json-pengaturan/master_jadwal_add_simulasi_pegawai.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);return false;
				$.messager.alert('Info', data, 'info');
				$('#rst_form').click();
				//parent.setShowHideMenu(3);
				document.location.href = 'master_jadwal_add_simulasi_pegawai.php?reqId=<?=$reqId?>';
			}
		});

		$('#cetakexcel').on('click', function () {
			var reqId= '<?=$reqId?>';
			if(reqId == "")
			{
			}
			else
			{
				newWindow = window.open("cetak_jadwal_pegawai.php?reqId="+reqId);
				newWindow.focus();	
			}
		});
	});
	
	


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
	<div id="header-tna-detil">Absen <span>Pegawai</span></div>
    <form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="100%">
        <tr>
            <td width="200px">Formula</td>
            <td width="2px">:</td>
            <td><label id="reqFormulaEselon"><?=$tempFormulaEselon?></label></td>
        </tr>
        <tr>
            <td>Tanggal Tes</td>
            <td>:</td>
            <td><?=$tempTanggalTesInfo?></td>
        </tr>
        <tr>
            <td>Acara</td>
            <td>:</td>
            <td><?=$tempAcara?></td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td><?=$tempTempat?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?=$tempAlamat?></td>
        </tr>
        <tr>
            <td>Total Peserta</td>
            <td>:</td>
            <td><label id="reqInfoTotalPeserta"><?=$index_loop?></label></td>
        </tr>

    </table>
    <p style="padding-top: -20px"></p>

    <table class="gradient-style" id="tableAsesor" style="width:100%; margin-left:-1px">
    <thead>
    	<tr>
    		<th scope="col" style="width:25%;background-color: white;border-top: none">
    			<a href="#" title="Cetak" id="cetakexcel"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak</a>
    		</th>
    	</tr>
    <tr>
        <th scope="col" style="width:25%;text-align: center">
        Nama Pegawai
        </th>
        <th scope="col" style="width:5%;text-align:center">NIP</th>
        <th scope="col" style="width:15%;text-align:center">Gol.Ruang</th>
        <th scope="col" style="width:5%;text-align:center">Eselon</th>
        <th scope="col" style="width:25%;text-align:center">Jabatan</th>
        <th scope="col" >Tanggal</th>
    </tr>
    </thead>
    <tbody>
    <?
	for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
	{
		$tempJadwalAsesorId= $arrJadwalAsesor[$checkbox_index]["JADWAL_PEGAWAI_ID"];
		$tempPegawaiId= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_ID"];
		
		$tempPegawaiSatkerTesId= $arrJadwalAsesor[$checkbox_index]["SATKER_TES_ID"];
		$tempPegawaiTanggalTes= $arrJadwalAsesor[$checkbox_index]["TANGGAL_TES"];
		
		$tempPegawai= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_NAMA"];
		$tempPegawaiNip= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_NIP"];
		$tempPegawaiGol= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_GOL"];
		$tempPegawaiEselon= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_ESELON"];
		$tempPegawaiJabatan= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_JAB_STRUKTURAL"];
		$tempUpdateDate= $arrJadwalAsesor[$checkbox_index]["LAST_UPDATE_DATE"];
    ?>
    <tr>
        <td>
            <input type="hidden" name="reqPegawaiSatkerTesId[]" id="reqPegawaiSatkerTesId<?=$checkbox_index?>" value="<?=$tempPegawaiSatkerTesId?>" />
            <input type="hidden" name="reqPegawaiTanggalTesId[]" id="reqPegawaiTanggalTesId<?=$checkbox_index?>" value="<?=$tempPegawaiTanggalTes?>" />
            <input type="hidden" name="reqPegawaJabatan[]" id="reqPegawaJabatan<?=$checkbox_index?>" value="<?=$tempPegawaiJabatan?>" />
            <input type="hidden" name="reqPegawaiId[]" id="reqPegawaiId<?=$checkbox_index?>" value="<?=$tempPegawaiId?>" />
            <label id="reqPegawai<?=$checkbox_index?>"><?=$tempPegawai?></label>
        </td>
        <td><label id="reqPegawaiNip<?=$checkbox_index?>"><?=$tempPegawaiNip?></label></td>
        <td><label id="reqPegawaiGol<?=$checkbox_index?>"><?=$tempPegawaiGol?></label></td>
        <td><label id="reqPegawaiEselon<?=$checkbox_index?>"><?=$tempPegawaiEselon?></label></td>
        <td><label id="reqPegawaiJabatan<?=$checkbox_index?>"><?=$tempPegawaiJabatan?></label></td>        
      
    	<td><label id="reqUpdateDate<?=$checkbox_index?>"><?=$tempUpdateDate?></label></td>
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