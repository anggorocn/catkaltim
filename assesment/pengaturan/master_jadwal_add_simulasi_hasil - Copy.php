<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode 	= httpFilterRequest("reqMode");
$reqId = httpFilterGet("reqId");

$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;

$tempTanggalTes= getFormattedDate($set->getField('TANGGAL_TES'));
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$tempJumlahPeserta= $set->getField('JUMLAH_PEGAWAI');
$tempJumlahAsesor= $set->getField('JUMLAH_ASESOR');

$statement= " AND JADWAL_TES_ID = ".$reqId;
$set_validasi= new JadwalTesSimulasiAsesor();
$set_validasi->selectByParams(array(), -1,-1, $statement);
$set_validasi->firstRow();
$tempStatusValidasi= $set_validasi->getField("STATUS");
unset($set_validasi);

$index_loop= 0;
$arrData="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set= new JadwalTesSimulasiAsesor();
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$tempKelompokJumlah= $set->getField("KELOMPOK_JUMLAH");
	$tempStatusGroup= $set->getField("STATUS_GROUP");
	$arrData[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrData[$index_loop]["NAMA_SIMULASI"]= $set->getField("NAMA_SIMULASI");
	$arrData[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrData[$index_loop]["STATUS_GROUP"]= $tempStatusGroup;
	$arrData[$index_loop]["PUKUL_AWAL"]= $set->getField("PUKUL_AWAL");
	
	if($tempKelompokJumlah == "Tidak Ada")
	{
		$arrData[$index_loop]["URL"]= "master_jadwal_add_simulasi_hasil_tanpa_penggalian.php";
		if($set->getField("PENGGALIAN_ID") == "0")
		$arrData[$index_loop]["MODE"]= "1";
		else
		$arrData[$index_loop]["MODE"]= "";
		$arrData[$index_loop]["JENIS"]= "1";
	}
	else
	{
		if($tempStatusGroup == "")
		{
			$arrData[$index_loop]["URL"]= "master_jadwal_add_simulasi_hasil_penggalian_tanpa_group.php";
			$arrData[$index_loop]["JENIS"]= "2";
			$arrData[$index_loop]["MODE"]= "";
		}
		else
		{
			$arrData[$index_loop]["URL"]= "master_jadwal_add_simulasi_hasil_penggalian_group.php";
			$arrData[$index_loop]["JENIS"]= "3";
			$arrData[$index_loop]["MODE"]= "";
		}
	}
	
	$index_loop++;
}
$jumlah_data= $index_loop;
unset($set);
//print_r($arrData);exit;
$arrJsonData= json_encode($arrData);
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
<script type="text/javascript">

var arrJsonData= panjang_array= "";
panjang_array= 0;
<?
if($jumlah_data > 0)
{
?>
	arrJsonData= <?=$arrJsonData?>;
	panjang_array= arrJsonData.length;
	//alert(arrJsonData[1].NAMA_SIMULASI);
<?
}
?>

var reqNo= 1;
var reqIndex= 0;
$(function(){
	//setLoadData();
	<?
	if($tempStatusValidasi == "1")
	{
	?>
	setLoadData();
	<?
	}
	?>
});

function setSetujui()
{
	$.messager.confirm('Konfirmasi', "Apakah anda yakin menyetujui simulasi ?",function(r){
	if (r){
			var win = $.messager.progress({title:'Proses data', msg:'Proses data...'});
			s_url= "../json-pengaturan/master_jadwal_add_simulasi_status_setuju.php?reqId=<?=$reqId?>";
			$.ajax({'url': s_url,'success': function(msg) {
				if(msg == '1')
				{
					$.messager.progress('close');
					parent.frames['menuFrame'].location.href = 'master_jadwal_add_menu.php?reqId=<?=$reqId?>';
					document.location.href= "master_jadwal_add_simulasi_hasil.php?reqId=<?=$reqId?>";
				}
			}});
		}
	});
}

function setCetakHasil()
{
	newWindow = window.open('master_jadwal_add_simulasi_hasil_pdf.php?reqId=<?=$reqId?>');
	newWindow.focus();
}

function setProses()
{
	$.messager.confirm('Konfirmasi', "Apakah anda yakin memproses simulasi ?",function(r){
	if (r){
			//$("#reqSimulasiHasilJadwalTanpaPenggalian, #reqSimulasiHasilJadwalPenggalianTanpaGroup, #reqSimulasiHasilJadwalPenggalianGroup").remove();
			var win = $.messager.progress({title:'Proses data', msg:'Proses data...'});
			s_url= "../json-pengaturan/master_jadwal_add_simulasi_proses.php?reqId=<?=$reqId?>";
			$.ajax({'url': s_url,'success': function(msg) {
				if(msg == ''){}
				else
				{
					$.messager.progress('close');
					reqNo= 1;
					$("#reqAddInfoTable").empty();
					setLoadData();
				}
			}});
		}
	});
}

function setPrintPdf()
{
	tabBody=document.getElementsByTagName("TBODY").item(1);
	tempJumlahRow= tabBody.rows.length;
	if(tempJumlahRow > 0)
	{
		$("#reqCetakHasil").show();
	}
}

function setLoadData()
{
	var url= arrJsonData[reqIndex].URL;
	var mode= arrJsonData[reqIndex].MODE;
	var jenis= arrJsonData[reqIndex].JENIS;
	var pukul_awal= arrJsonData[reqIndex].PUKUL_AWAL;
	
	setModalAwal("reqAddInfoTable", url+"?reqId=<?=$reqId?>&reqMode="+mode+"&&reqJenisId="+jenis+"&reqNo="+reqNo+"&reqPukulAwal="+pukul_awal, reqIndex);
}

function setModalAwal(target, link_url, infoId)
{
	var s_url= link_url;
	$.ajax({'url': s_url,'success': function(msg) {
		if(msg == ''){}
		else
		{
			$('#reqAddInfoTable').append(msg);
			tabBody=document.getElementsByTagName("TBODY").item(1);
			reqIndex= parseInt(reqIndex) + 1;
			reqIndexNext= parseInt(reqIndex) + 1;
			
			var url= jenis= pukul_awal= "";;
			
			if(reqIndexNext < panjang_array)
			{
				url= arrJsonData[reqIndexNext].URL;
				mode= arrJsonData[reqIndexNext].MODE;
				jenis= arrJsonData[reqIndexNext].JENIS;
				pukul_awal= arrJsonData[reqIndexNext].PUKUL_AWAL;
			}
			
			if(jenis == 3)
			reqNo= tabBody.rows.length + 1;
			else
			reqNo= tabBody.rows.length;
			
			if(reqIndex == panjang_array)
			{
				// set asesor meeting
				s_url= "master_jadwal_add_simulasi_hasil_meeting_asesor.php?reqId=<?=$reqId?>&reqNo="+reqNo;
				$.ajax({'url': s_url,'success': function(msg) {
					if(msg == ''){}
					else
					{
						$('#reqAddInfoTable').append(msg);
					}
				}});
				
				panjang_array= 0;
				reqNo= 1;
				reqIndex= 0;
				<?
				if($jumlah_data > 0)
				{
				?>
				arrJsonData= <?=$arrJsonData?>;
				panjang_array= arrJsonData.length;
				<?
				}
				?>
				setPrintPdf();
				return false;
			}
			
			var url= arrJsonData[reqIndex].URL;
			var mode= arrJsonData[reqIndex].MODE;
			var jenis= arrJsonData[reqIndex].JENIS;
			var pukul_awal= arrJsonData[reqIndex].PUKUL_AWAL;
			setModalAwal("reqAddInfoTable", url+"?reqId=<?=$reqId?>&reqMode="+mode+"&reqJenisId="+jenis+"&reqNo="+reqNo+"&reqPukulAwal="+pukul_awal, reqIndex);
		}
	}});
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
	
	.modifth
	{
		background-color:#F69 !important; color:#FFF !important;
	}
</style>
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; width:100%">
	<div id="header-tna-detil">Simulasi <span>Hasil</span></div>
    <table class="table_list" cellspacing="1" width="100%">
        <tr>
            <td width="200px">Formula</td>
            <td width="2px">:</td>
            <td><label id="reqFormulaEselon"><?=$tempFormulaEselon?></label></td>
        </tr>
        <tr>
            <td>Tanggal Tes</td>
            <td>:</td>
            <td><?=$tempTanggalTes?></td>
        </tr>
        <tr>
            <td>Acara</td>
            <td>:</td>
            <td><?=$tempAcara?></td>
        </tr>
        <tr>
            <td>Peserta</td>
            <td>:</td>
            <td><?=$tempJumlahPeserta?> Peserta</td>
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
            <td>Jumlah Asesor</td>
            <td>:</td>
            <td><?=$tempJumlahAsesor?> Asesor</td>
        </tr>
        <?
		if($reqId == ""){}
		else
		{
        ?>
        <tr>
            <td colspan="3">
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqMode" value="insert">
                <input type="button" name="" onclick="setCetakHasil()" id="reqCetakHasil" style="display:none" value="Cetak Hasil" />
                <?
				if($tempStatusValidasi == "1"){}
				else
				{
				?>
                <input type="button" name="" onclick="setProses()" value="Lihat Hasil" />
                <input type="button" name="" onclick="setSetujui()" value="Setujui" />
                <?
				}
                ?>
            </td>
        </tr>
        <?
		}
        ?>
    </table>
    <table class="gradient-style" style="width:100%; margin-left:-1px">
    <thead>
        <tr>
            <th scope="col" style="width:30px; text-align:center">No</th>
            <th scope="col" style="width:120px; text-align:center">Waktu</th>
            <th scope="col" style="text-align:center">Keterangan</th>
        </tr>
    </thead>
    <tbody id="reqAddInfoTable">
    	<?php /*?><tr>
        	<td style="text-align:center">a</td>
            <td style="text-align:center">07:30 - Selesai</td>
            <td>
            	<table style="width:100%; border:none !important">
                <tr>
                    <td colspan="2" style="text-align:center; background-color:#CCC; border:none !important">LGD</td>
                </tr>
                <tr>
                    <td style="text-align:center; background-color:#09F; color:#FFF; border:none !important">Kel 1</td>
                    <td style="text-align:center; background-color:#09F; color:#FFF; border:none !important">Kel 2</td>
                </tr>
                <tr>
                    <td style="text-align:center; border:none !important">Ruang 1</td>
                    <td style="text-align:center; border:none !important">Ruang 2</td>
                </tr>
                <tr>
                    <td style="text-align:center; background-color:#6F6; border:none !important">Dini</td>
                    <td style="text-align:center; background-color:#6F6; border:none !important">Regina</td>
                </tr>
                </table>
            </td>
        </tr><?php */?>
    </tbody>
    </table>
    </div>
</div>
</body>
</html>