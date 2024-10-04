<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqJenis= httpFilterGet("reqJenis");
$infoid= $reqId= httpFilterGet("reqId");
$reqCheckId= httpFilterGet("reqCheckId");

if($reqId == "")
{
	// exit();
}

$arreselon= [];
$arrId= explode(",", $reqId);
$jumlahid= count($arrId);
if(!empty($reqCheckId))
{
	$reqId= $reqCheckId;
}
else
{
	$reqId= $arrId[0];
}

if($jumlahid >! 1)
{
	$i= 0;
	$setdetil= new JadwalTes();
	$setdetil->selectByParamsFormulaEselon(array(),-1,-1,"");
	// echo $setdetil->query; exit;
	while($setdetil->nextRow())
	{
		$arreselon[$i]['JADWAL_TES_ID'] = $setdetil->getField('JADWAL_TES_ID');
		$arreselon[$i]['NAMA_FORMULA_ESELON'] = $setdetil->getField('NAMA_FORMULA_ESELON');
		$i++;
	}
	$jumlaheselon= $i;
}
// print_r($arreselon);exit;

$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> 277),-1,-1,'');
$set->firstRow();
// echo $set->query;exit;

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
$arrpegawaidata=[];
// $statement= " AND JA.LAST_UPDATE_DATE IS NULL AND JADWAL_AWAL_TES_SIMULASI_ID = ".$reqId;
$statement= " AND JADWAL_AWAL_TES_SIMULASI_ID = 277";
$set_detil= new JadwalTes();
$set_detil->selectByParamsPegawaiAbsen(array(), -1,-1, $statement);
// echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrpegawaidata[$index_loop]["SATKER_TES_ID"]= $set_detil->getField("SATKER_TES_ID");
	$arrpegawaidata[$index_loop]["PEGAWAI_ID"]= $set_detil->getField("PEGAWAI_ID");
	$arrpegawaidata[$index_loop]["PEGAWAI_NAMA"]= $set_detil->getField("PEGAWAI_NAMA");
	$arrpegawaidata[$index_loop]["PEGAWAI_NIP"]= $set_detil->getField("PEGAWAI_NIP");
	$arrpegawaidata[$index_loop]["PEGAWAI_GOL"]= $set_detil->getField("PEGAWAI_GOL");
	$arrpegawaidata[$index_loop]["PEGAWAI_ESELON"]= $set_detil->getField("PEGAWAI_ESELON");
	$arrpegawaidata[$index_loop]["PEGAWAI_SATKER"]= $set_detil->getField("SATKER");
	$arrpegawaidata[$index_loop]["PEGAWAI_JAB_STRUKTURAL"]= $set_detil->getField("PEGAWAI_JAB_STRUKTURAL");
	$arrpegawaidata[$index_loop]["NOMOR_URUT_GENERATE"]= $set_detil->getField("NOMOR_URUT_GENERATE");
	$index_loop++;
}
$jumlah_pegawai_data= $index_loop;
// print_r($arrpegawaidata);exit;
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

<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<script type="text/javascript">	
	function iecompattest(){
		return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
	}

	function OpenDHTMLCenter(opAddress, opCaption, opWidth, opHeight)
	{
		opCaption= "Modul Pengaturan";
		var width  = opWidth;
		var height = opHeight;
		var left   = (screen.width  - width)/2;
		var top    = (screen.height - height)/2;
		var params = 'width='+width+', height='+height;
		params += ', top='+top+', left='+left;
		params += ', directories=no';
		params += ', location=no';
		params += ', menubar=no';
		params += ', resizable=no';
		params += ', scrollbars=no';
		params += ', status=no';
		params += ', toolbar=no';
		params += ', center=yes';
		divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
	}

	$(function(){
		$("#selectformula").on("change", function(){  
			valinfo= $(this).val();
			// alert(valinfo);
			document.location.href = 'master_jadwal_add_multi_absen.php?reqJenis=<?=$reqJenis?>&reqId=277&reqCheckId='+valinfo;
		});

		$('#ff').form({
			url:'../json-pengaturan/master_jadwal_add_multi_absen.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				infodata= "data berhasil absen";
				$.messager.alert('Info',infodata,'info', function()
                {
                    document.location.href = 'master_jadwal_add_multi_absen.php?reqJenis=<?=$reqJenis?>&reqId=<?=$reqId?>';
                });
			}
		});

		$("#reqPilihSemua").on("click", function () {
			$('a[id^="reqInfoSimpanUnCheck"]').each(function() {
				var infoid= $(this).attr('id');
				arrinfoid= infoid.split('reqInfoSimpanUnCheck');
				inforowid= arrinfoid[1];

				$("#reqInfoSimpanUnCheck"+inforowid).hide();
				$("#reqInfoSimpanCheck"+inforowid).show();

				reqPegawaiId= $("#reqPegawaiId"+inforowid).val();
				$("#reqInfoPegawaiId"+inforowid).val(reqPegawaiId);
			});
		});

		$("#reqProsesData").on("click", function () {
			$.messager.confirm('Konfirmasi', "Apakah Anda yakin untuk absen pegawai, sesuai data yang dipilih ?",function(r){
				if (r){
					$('#ff').submit();
					return true;
				}
			});
		});

		$('a[id^="reqInfoSimpan"]').click(function(e) {
			var infoid= $(this).attr('id');
			arrinfoid= infoid.split('reqInfoSimpanUnCheck');
			inforowid= arrinfoid[1];

			infosetpegawaiid= "";
			if(typeof inforowid == "undefined")
			{
				arrinfoid= infoid.split('reqInfoSimpanCheck');
				inforowid= arrinfoid[1];
				$("#reqInfoSimpanUnCheck"+inforowid).show();
				$("#reqInfoSimpanCheck"+inforowid).hide();
			}
			else
			{
				infosetpegawaiid= "1";
				$("#reqInfoSimpanUnCheck"+inforowid).hide();
				$("#reqInfoSimpanCheck"+inforowid).show();
			}

			reqPegawaiId= $("#reqPegawaiId"+inforowid).val();
			if(infosetpegawaiid == "1")
			{
				$("#reqInfoPegawaiId"+inforowid).val(reqPegawaiId);
			}
			else
			{
				$("#reqInfoPegawaiId"+inforowid).val("");
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

	.texinfonumber
	{
		border: 1px solid black;
		padding: 10%;
		font-weight: bold; font-size: 3rem; margin-right: 0.5rem; font-family: 'Abril Fatface', serif;
	}

</style>
</head>

<body  style="background-color: white">
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; width:100%">
	<div id="header-tna-detil">Absen <span>Semua Peserta</span></div>
	<?
	if($reqId == "1000000")
	{
	?>
	<table class="table_list" cellspacing="1" width="100%">
    	<tr>
    		<td>Jadwal Ujian Tidak ada</td>
    	</tr>
    </table>
	<?
	}
	else
	{
	?>
    <form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="100%">
    	<tr>
    		<td style="width: 50%">
		    	<table class="table_list" cellspacing="1" width="100%">
			        <tr>
			            <td width="200px">Formula</td>
			            <td width="2px">:</td>
			            <td>
			            	<?
			            	if($jumlahid > 1)
			            	{
			            	?>
			            	<select id="selectformula">
			            		<?
			            		for($i=0; $i < $jumlaheselon; $i++)
			            		{
			            			$selectid= $arreselon[$i]['JADWAL_TES_ID'];
			            			$selecttext= $arreselon[$i]['NAMA_FORMULA_ESELON'];
			            		?>
			            		<option value="<?=$selectid?>" <? if($selectid == $reqId) echo "selected";?>><?=$selecttext?></option>
			            		<?
			            		}
			            		?>
			            	</select>
			            	<?
			            	}
			            	else
			            	{
			            	?>
			            	<label id="reqFormulaEselon"><?=$tempFormulaEselon?></label>
			            	<?
			            	}
			            	?>
			            </td>
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
		    	</table>
		    </td>
		</tr>
        <tr>
            <td>
            	<!-- style="display: none;" -->
                <input type="hidden" name="reqMode" value="insert">
                <input type="hidden" name="reqId" value="277">
                <input type="button" id="reqProsesData" value="Absen" />
                <input type="button" id="reqPilihSemua" value="Check All" />
            </td>
        </tr>
    </table>
    <table class="gradient-style" style="width:100%; margin-left:-1px">
    <thead>
	    <tr>
	    	<th scope="col" style="width:25%">Nama Pegawai</th>
	    	<th scope="col" style="width:5%">NIP</th>
	    	<th scope="col" style="width:5%">Gol.Ruang</th>
	    	<th scope="col" style="width:5%">Eselon</th>
	    	<th scope="col" style="width:20%">Jabatan</th>
	    	<th scope="col" style="width:40%">Satker</th>
	    	<th scope="col">Nomor Urut</th>
	    </tr>
	</thead>
	<tbody>
		<?
		for($index_loop=0;$index_loop < $jumlah_pegawai_data;$index_loop++)
		{
			// $arrpegawaidata[$index_loop]["SATKER_TES_ID"]= $set_detil->getField("SATKER_TES_ID");
			$reqPegawaiId= $arrpegawaidata[$index_loop]["PEGAWAI_ID"];
			$reqPegawaiNama= $arrpegawaidata[$index_loop]["PEGAWAI_NAMA"];
			$reqPegawaiNip= $arrpegawaidata[$index_loop]["PEGAWAI_NIP"];
			$reqPegawaiGol= $arrpegawaidata[$index_loop]["PEGAWAI_GOL"];
			$reqPegawaiEselon= $arrpegawaidata[$index_loop]["PEGAWAI_ESELON"];
			$reqPegawaiJabatan= $arrpegawaidata[$index_loop]["PEGAWAI_JAB_STRUKTURAL"];
			$reqPegawaiSatker= $arrpegawaidata[$index_loop]["PEGAWAI_SATKER"];
			$reqPegawaiNomorUrut= $arrpegawaidata[$index_loop]["NOMOR_URUT_GENERATE"];
		?>
		<tr>
			<td>
				<?
				if(!empty($reqPegawaiNomorUrut))
				{
				?>
					<img src="../WEB/images/icon_check.png" width="15px" heigth="15px">
				<?
				}
				else
				{
				?>
					<a id="reqInfoSimpanUnCheck<?=$index_loop?>" style="cursor:pointer;">
						<img src="../WEB/images/icon_uncheck.png" width="15px" heigth="15px">
					</a>
					<a id="reqInfoSimpanCheck<?=$index_loop?>" style="cursor:pointer; display: none;">
						<img src="../WEB/images/icon_check.png" width="15px" heigth="15px">
					</a>
				<?
				}
				?>
				<label id="reqPegawaiNama<?=$index_loop?>"><?=$reqPegawaiNama?></label>
				<input type="hidden" id="reqPegawaiId<?=$index_loop?>" value="<?=$reqPegawaiId?>" />
				<input type="hidden" id="reqInfoPegawaiId<?=$index_loop?>" name="reqInfoPegawaiId[]" value="" />
			</td>
			<td><label id="reqPegawaiNip<?=$index_loop?>"><?=$reqPegawaiNip?></label></td>
	        <td><label id="reqPegawaiGol<?=$index_loop?>"><?=$reqPegawaiGol?></label></td>
	        <td><label id="reqPegawaiEselon<?=$index_loop?>"><?=$reqPegawaiEselon?></label></td>
	        <td><label id="reqPegawaiJabatan<?=$index_loop?>"><?=$reqPegawaiJabatan?></label></td>
	        <td><label id="reqPegawaiSatker<?=$index_loop?>"><?=$reqPegawaiSatker?></label></td>
	        <td><label id="reqPegawaiNomorUrut<?=$index_loop?>"><?=$reqPegawaiNomorUrut?></label></td>
	    </tr>
		<?
		}
		?>
	</tbody>
	</table>

    </form>
    <?
	}
    ?>
</div>
</body>
</html>