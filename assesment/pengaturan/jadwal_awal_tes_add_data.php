<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalAwalTes.php");

$set= new JadwalAwalTes();

$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode= "insert";
	$tempTempat= "UPTD Penilaian Kompetensi Pegawai BKD Kalimantan Timur";
	$tempAlamat= "Jalan Kartini No 13 Samarinda, Kalimantan Timur";

	// $tempTempat= $tempAlamat= "";
}
else
{
	$reqMode= "update";
	$set->selectByParamsFormulaEselon(array("A.JADWAL_AWAL_TES_ID"=> $reqId),-1,-1,'');
	$set->firstRow();
	//echo $set->query;exit;
	
	$tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), "date");
	$tempTanggalTesAkhir= datetimeToPage($set->getField('TANGGAL_TES_AKHIR'), "date");
	$tempBatch= $set->getField('BATCH');
	$tempAcara= $set->getField('ACARA');
	$tempTempat= $set->getField('TEMPAT');
	$tempAlamat= $set->getField('ALAMAT');
	$tempKeterangan= $set->getField('KETERANGAN');
	$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');
	$reqStatusValid= $set->getField('STATUS_VALID');
	
	$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
	$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');
	
	$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');
	$tempJumlahRuangan= $set->getField('JUMLAH_RUANGAN');

	$reqStatusJenis= $set->getField('STATUS_JENIS');
}
//echo $user_group->query;exit;
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

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>

<script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/daterangepicker.js"></script>

<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="../WEB/lib/autokomplit/jquery-ui.css">

<script type="text/javascript">	
	var tempNRP='';
		
	$(function(){
		$('#ff').form({
			url:'../json-pengaturan/jadwal_awal_tes_add_data.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// alert(data);return false;
				data = data.split("-");
				rowid= data[0];
				infodata= data[1];

				$.messager.alert('Info', infodata, 'info');
				if(rowid == "xxx")
				{
					return false;
				}
				else
				{
					// top.frames['mainFullFrame'].location.reload();
					parent.frames['menuFrame'].location.href = 'jadwal_awal_tes_add_menu.php?reqId='+rowid;
					document.location.href = 'jadwal_awal_tes_add_data.php?reqId='+rowid;
				}
			}
		});

		var dates = $( "#reqTanggalTes, #reqTanggalTesAkhir" ).datepicker({
			defaultDate: "+0w",
			dateFormat: 'dd-mm-yy',
			numberOfMonths: 1,
			beforeShow: function( ) {
				var other = this.id == "reqTanggalTes" ? "#reqTanggalTesAkhir" : "#reqTanggalTes";
				var option = this.id == "reqTanggalTes" ? "maxDate" : "minDate";
				var selectedDate = $(other).datepicker('getDate');

				$(this).datepicker( "option", option, selectedDate );
			}
		}).change(function(){
			var other = this.id == "reqTanggalTes" ? "#reqTanggalTesAkhir" : "#reqTanggalTes";

			if ( $('#reqTanggalTes').datepicker('getDate') > $('#reqTanggalTesAkhir').datepicker('getDate') )
				$(other).datepicker('setDate', $(this).datepicker('getDate') );
		});
		
	});

	function openPencarianKaryawan()
	{
		parent.OpenDHTML('formula_eselon_pencarian.php?reqId=<?=$reqId?>', 'Pencarian Formula', 780, 350)
	}
	
	var tempId= tempJabatan= tempDepartemen= tempNama= tempKelas= "";
	function OptionSet(id)
	{
		tempId=id;
		$.getJSON('../json-pengaturan/formula_eselon_get_json.php?reqId=<?=$reqId?>&reqFormulaEselonId='+ id,
		  function(data){
			reqFormulaEselonId= data.tempId;
			reqFormulaEselon=data.tempNama;
			
			$("#reqFormulaEselonId").val(reqFormulaEselonId);
			$("#reqFormulaEselon").text(reqFormulaEselon);
		  });
	}
	
</script>
<style type="text/css" media="screen">
  label {
	/*font-size: 10px;
	font-weight: bold;
	text-transform: uppercase;
	margin-bottom: 3px;*/
	clear: both;
  }
</style>
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
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
			<table class="table_list" cellspacing="1" width="100%">
				<tr>
					<td colspan="6">
					<div id="header-tna-detil">Data <span>Jadwal</span></div>
					</td>			
				</tr>
                <tr>
					<td width="200px">Formula</td>
					<td width="2px">:</td>
					<td>        
						<input type="hidden" name="reqFormulaEselonId" id="reqFormulaEselonId" value="<?=$tempFormulaEselonId?>" />
                        <label id="reqFormulaEselon"><?=$tempFormulaEselon?></label>
                        <img src="../WEB/images/icn_search.png" onClick="openPencarianKaryawan()" style="cursor:pointer" />
				   </td>
				</tr>
			<!-- 	<tr>
					<td>Status Jenis</td>
					<td>:</td>
					<td>
						<select id="reqStatusJenis" name="reqStatusJenis">
							<option value="1" <? if($reqStatusJenis == "1") echo "selected";?>>Internal</option>
							<option value="2" <? if($reqStatusJenis == "2") echo "selected";?>>Eksternal</option>
						</select>
				   </td>
				</tr> -->
				<tr>
					<td>Tanggal Tes</td>
					<td>:</td>
					<td>
						<input type="text" id="reqTanggalTes" name="reqTanggalTes" class="easyui-validatebox" style="width:80px" required value="<?=$tempTanggalTes?>" />
						s/d
						<input type="text" id="reqTanggalTesAkhir" name="reqTanggalTesAkhir" class="easyui-validatebox" style="width:80px" required value="<?=$tempTanggalTesAkhir?>" />
				   </td>
				</tr>
                <tr>
					<td>Acara</td>
					<td>:</td>
					<td>
                    	<input type="text" name="reqAcara" id="reqAcara" class="easyui-validatebox" style="width:200px" value="<?=$tempAcara?>" />
				   </td>
				</tr>
				<tr>
					<td>Tempat</td>
					<td>:</td>
					<td>
                    	<input type="text" name="reqTempat" id="reqTempat" class="easyui-validatebox" style="width:50%" value="<?=$tempTempat?>" />
				   </td>
				</tr>
                <tr>
					<td>Alamat</td>
					<td>:</td>
					<td>
                    	<input type="text" name="reqAlamat" id="reqAlamat" class="easyui-validatebox" style="width:80%" value="<?=$tempAlamat?>" />
				   </td>
				</tr>
                <tr>
					<td>Keterangan</td>
					<td>:</td>
					<td>        
						<textarea name="reqKeterangan" style="width:98%" rows="4"><?=$tempKeterangan?></textarea>
				   </td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="reqId" value="<?=$reqId?>">
						<input type="hidden" name="reqMode" value="<?=$reqMode?>">
						<input type="submit" name="" value="Simpan" /> 
						<!-- <input type="reset" name="" value="Reset" /> -->
					</td>
				</tr> 
			</table>       
        </form>
        <script>
		$("#reqUrut, #reqJumlahRuangan").keypress(function(e) {
			//alert(e.which);
			if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			{
			return false;
			}
		});
		</script>
    </div>
</div>
</body>
</html>