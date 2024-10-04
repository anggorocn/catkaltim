<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

$setAsesor = new Kelautan();
$set= new JadwalTes();

$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode= "insert";
	$tempTempat= "UPTD Penilaian Kompetensi Pegawai BKD Kalimantan Timur";
	$tempAlamat= "Jalan Kartini No 13 Samarinda, Kalimantan Timur";

	$tempTempat= $tempAlamat= "";
}
else
{
	$reqMode= "update";
	$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
	$set->firstRow();
	// echo $set->query;exit;
	
	$tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), "date");
	$tempTanggalTesInfo= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
	$tempBatch= $set->getField('BATCH');
	$tempAcara= $set->getField('ACARA');
	$tempTempat= $set->getField('TEMPAT');
	$tempTtdAsesor= $set->getField('TTD_ASESOR');
	$tempNipAsesor= $set->getField('NIP_ASESOR');
	$tempTtdPimpinan= $set->getField('TTD_PIMPINAN');
	$tempNipPimpinan= $set->getField('NIP_PIMPINAN');
	$tempAlamat= $set->getField('ALAMAT');
	$tempKeterangan= $set->getField('KETERANGAN');
	$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');
	$reqStatusValid= $set->getField('STATUS_VALID');
	$reqLinkSoal= $set->getField('LINK_SOAL');
	// echo $reqLinkSoal;exit;


	$tempTanggalTesTtd= dateToPageCheck($set->getField('TTD_TANGGAL'));
	$tempTanggalTesTtdInfo= getFormattedDateTime($set->getField('TTD_TANGGAL'), false);
	
	$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
	$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');
	
	$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');
	$tempJumlahRuangan= $set->getField('JUMLAH_RUANGAN');
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
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
	var tempNRP='';
		
	$(function(){
		$('#ff').form({
			url:'../json-pengaturan/master_jadwal_add_data.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				data = data.split("-");
				$.messager.alert('Info', data[1], 'info');
				reqId= data[0];
				$('#rst_form').click();
				top.frames['mainFullFrame'].location.reload();
				parent.frames['menuFrame'].location.href = 'master_jadwal_add_menu.php?reqId='+reqId;
				document.location.href = 'master_jadwal_add_data.php?reqId='+reqId;
			}
		});
		
	});
	
	function addnewAsesor(nama, nip) {
		console.log(nama);
		console.log(nip);
	}

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
		parent.OpenDHTML('pemilihan_admin_kegiatan.php?', 'Pemilihan Admin Kegiatan', 780, 500)
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
		<form id="ff" method="post" novalidate enctype="multipart/form-data">
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
                        <!-- <img src="../WEB/images/icn_search.png" onClick="openPencarianKaryawan()" style="cursor:pointer" /> -->
				   </td>
				</tr>
				<tr>
					<td>Tanggal Tes</td>
					<td>:</td>
					<td>
						<input type="hidden" name="reqTanggalTes" value="<?=$tempTanggalTes?>"/>
						<?=$tempTanggalTesInfo?>
						<!-- <input type="text" name="reqTanggalTes" required style="width:100px" class="easyui-datebox" data-options="validType:'date', panelHeight:'160'" value="<?=$tempTanggalTes?>"/> -->
				   </td>
				</tr>
                <!-- <tr>
					<td>Batch</td>
					<td>:</td>
					<td>
                    	<input type="text" name="reqBatch" id="reqBatch" class="easyui-validatebox" style="width:50px" value="<?=$tempBatch?>" />
				   </td>
				</tr> -->
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
                <tr style="display: none;">
					<td>Jumlah Ruangan</td>
					<td>:</td>
					<td>
                    	<input type="text" name="reqJumlahRuangan" id="reqJumlahRuangan" class="easyui-validatebox" style="width:40px" value="<?=$tempJumlahRuangan?>" />
				   </td>
				</tr>
                <tr style="display: none;">
					<td>Status penutupan penilaian asesor</td>
					<td>:</td>
					<td>        
						<select name="reqStatusPenilaian" id="reqStatusPenilaian">
                        	<option value="" <? if($tempStatusPenilaian == "") echo "selected"?>>Buka</option>
                            <option value="1" <? if($tempStatusPenilaian == "1") echo "selected"?>>Tutup</option>
                        </select>
				   </td>
				</tr>
				<tr>
					<td width="200px">Admin Kegiatan</td>
					<td width="2px">:</td>
					<td>
							<!-- <a style="cursor:pointer" title="Tambah" onclick="addKaryawanPopUp()"><img src="../WEB/images/icn_add.gif" width="16" height="16" border="0" /></a> -->
              <!-- Nama : <input type="text" name="reqTtdAsesor" id="reqTtdAsesor" class="easyui-validatebox" style="width:40%" value="<?=$tempTtdAsesor?>" /> -->
              <select name="reqTtdAsesor" id="reqTtdAsesor" >
              	<option <?if($tempTtdAsesor==''){?>selected<?}?> disabled >Pilih Asesor</option>
              	<?
              	$setAsesor->selectByParamsMonitoringAdminKegiatan();
								$setAsesor->firstRow();
              	while ($setAsesor->nextRow()) {
              	?>
              	<option <?if($tempTtdAsesor==$setAsesor->getField('asesor_id')){?>selected<?}?> value="<?=$setAsesor->getField('asesor_id')?>"><?=$setAsesor->getField('nama')?> (<?=$setAsesor->getField('user_login')?>) </option>
              	<?}?>
              </select>
				   </td>
				</tr>
				<tr>
					<td width="200px">TTD Pimpinan</td>
					<td width="2px">:</td>
					<td>
                    	<input type="text" name="reqTtdPimpinan" id="reqTtdPimpinan" class="easyui-validatebox" style="width:70%" value="<?=$tempTtdPimpinan?>" />
				   </td>
				</tr>
				<tr>
					<td width="200px">NIP Pimpinan</td>
					<td width="2px">:</td>
					<td>
                    	<input type="text" name="reqNipPimpinan" id="reqNipPimpinan" class="easyui-validatebox" style="width:70%" value="<?=$tempNipPimpinan?>" />
				   </td>
				</tr>
				<tr>
					<td>Tanggal TTD</td>
					<td>:</td>
					<td>
						<!-- <input type="date" name="tempTanggalTesTtd" value="<?=$tempTanggalTesTtd?>"/> -->
						<input type="text" name="reqTanggalTesTTd" required style="width:100px" class="easyui-datebox" data-options="validType:'date', panelHeight:'160'" value="<?=$tempTanggalTesTtd?>"/>
				   </td>
				</tr>
				<tr>
					<td>Status Valid</td>
					<td>:</td>
					<td>        
						<select name="reqStatusValid" id="reqStatusValid">
                        	<option value="" <? if($reqStatusValid == "") echo "selected"?>>Buka</option>
                            <option value="1" <? if($reqStatusValid == "1") echo "selected"?>>Tutup</option>
                        </select>
				   </td>
				</tr>
				<tr>
					<td>Rekap Quisioner</td>
					<td>:</td>
					<td>  
						<img src="../WEB/images/down_icon.png" id="btnCetak" style="cursor:pointer;text-align: center" class="gambar">     </td>
				</tr>
			<!-- 	<tr>
                    <td>Upload Soal Intray</td>
                    <td>:</td>
                    <td>
                        <input type="file" style="font-size:10px" name="reqLinkSoalIntray" id="reqLinkSoalIntray" class="easyui-validatebox" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" />
                        <? if ($reqLinkSoal)
                        {
                        ?>
                        <a href="<?=$reqLinkSoal?>" target="_blank"><img src="../WEB/images/down_icon.png" title="download" /></a>
                        <?
                        }
                        ?>
                    </td>        
                </tr> -->
				<tr>
					<td>
						<input type="hidden" name="reqId" value="<?=$reqId?>">
						<input type="hidden" name="reqMode" value="<?=$reqMode?>">
						<input type="submit" name="" value="Simpan" /> 
						 <button type="button" onclick="sethcdp()">Set HCDP</button> 

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

		$('#btnCetak').on('click', function () {
			newWindow = window.open("cetak_queisioner.php?reqId=<?=$reqId?>", 'Cetak');
			newWindow.focus();  
		 });

		

		</script>
    </div>
</div>
</body>
</html>

<script type="text/javascript">
	function sethcdp()
		{
			urllink= "../json-silat/sethcdp.php?reqJadwalTesId=<?=$reqId?>";
			pesan= "yakin?";
			$.messager.confirm('Konfirmasi', pesan, function(r) {
	      if (r) {
	        var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});          
					$.ajax({
		        url: urllink,
		        // dataType: 'json',
		        success: function (response) {
		        	$.messager.progress('close');
		        	// console.log(response);return false;
		        	document.location.href = 'master_jadwal_add_data.php?reqId=<?=$reqId?>';
		        },
		        error: function (response) {
		        	// console.log(response);return false;
		        },
		        complete: function () {
		        }
		    	});
         }
      });
		}
</script>