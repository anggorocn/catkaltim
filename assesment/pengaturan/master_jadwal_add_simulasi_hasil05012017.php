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

$tempTanggalTes= dateToPageCheck($set->getField('TANGGAL_TES'));
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$statement= " AND JADWAL_TES_ID = ".$reqId;
$set_validasi= new JadwalTesSimulasiAsesor();
$set_validasi->selectByParams(array(), -1,-1, $statement);
$set_validasi->firstRow();
$tempStatusValidasi= $set_validasi->getField("STATUS");
unset($set_validasi);
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
$(function(){
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
			s_url= "../json-pengaturan/master_jadwal_add_simulasi_status_setuju.php?reqId=<?=$reqId?>";
			$.ajax({'url': s_url,'success': function(msg) {
				if(msg == '1')
				{
					document.location.href= "master_jadwal_add_simulasi_hasil.php?reqId=<?=$reqId?>";
				}
			}});
		}
	});
}

function setProses()
{
	$.messager.confirm('Konfirmasi', "Apakah anda yakin memproses simulasi ?",function(r){
	if (r){
			//$("#reqSimulasiHasilJadwalTanpaPenggalian, #reqSimulasiHasilJadwalPenggalianTanpaGroup, #reqSimulasiHasilJadwalPenggalianGroup").remove();
			s_url= "../json-pengaturan/master_jadwal_add_simulasi_proses.php?reqId=<?=$reqId?>";
			$.ajax({'url': s_url,'success': function(msg) {
				if(msg == ''){}
				else
				{
					//$('#reqSimulasiHasilJadwalTanpaPenggalian').replaceWith("");
					setLoadData();
				}
			}});
		}
	});
}

function setLoadData()
{
	// set reqSimulasiHasilJadwalTanpaPenggalian
	target= "reqSimulasiHasilJadwalTanpaPenggalian";
	link_url= "master_jadwal_add_simulasi_hasil_tanpa_penggalian.php?reqId=<?=$reqId?>&reqJenisId=1";
	setModal(target, link_url);
	
	// set reqSimulasiHasilJadwalPenggalianTanpaGroup
	target= "reqSimulasiHasilJadwalPenggalianTanpaGroup";
	link_url= "master_jadwal_add_simulasi_hasil_penggalian_tanpa_group.php?reqId=<?=$reqId?>&reqJenisId=2";
	setModal(target, link_url);
	
	// set reqSimulasiHasilJadwalPenggalianGroup
	target= "reqSimulasiHasilJadwalPenggalianGroup";
	link_url= "master_jadwal_add_simulasi_hasil_penggalian_group.php?reqId=<?=$reqId?>&reqJenisId=3";
	setModal(target, link_url);
}

function setModal(target, link_url)
{
	var s_url= link_url;
	$.ajax({'url': s_url,'success': function(msg) {
		if(msg == ''){}
		else
		{
			//$('#'+target).html(msg);
			$('#'+target).replaceWith(msg);
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
            <td>Tempat</td>
            <td>:</td>
            <td><?=$tempTempat?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?=$tempAlamat?></td>
        </tr>
        <?
		if($reqId == ""){}
		else
		{
        ?>
        <tr>
            <td>
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqMode" value="insert">
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
    <div id="reqSimulasiHasilJadwalTanpaPenggalian">
    </div>
    <div id="reqSimulasiHasilJadwalPenggalianTanpaGroup">
    </div>
    <div id="reqSimulasiHasilJadwalPenggalianGroup">
    </div>
    
    </div>
</div>
</body>
</html>