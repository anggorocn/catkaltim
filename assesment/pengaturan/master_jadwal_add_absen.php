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

if($jumlahid > 1)
{
	$i= 0;
	$setdetil= new JadwalTes();
	$setdetil->selectByParamsFormulaEselon(array(),-1,-1," AND A.JADWAL_TES_ID IN (".$infoid.")");
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
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
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

$reqPegawaiId=0;
// $reqPegawaiId=846;
$index_loop= 0;
$arrData="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set_detil= new JadwalTes();
$set_detil->selectByParamsAbsenJadwal(array(), -1,-1, $reqPegawaiId, $statement);
// echo $set_detil->query;exit;

$cekLihatProses= "";
while($set_detil->nextRow())
{
	$arrData[$index_loop]["JADWAL_TES_ID"]= $set_detil->getField("JADWAL_TES_ID");
	$arrData[$index_loop]["JADWAL_ACARA_ID"]= $set_detil->getField("JADWAL_ACARA_ID");
	$arrData[$index_loop]["PUKUL1"]= $set_detil->getField("PUKUL1");
	$arrData[$index_loop]["PUKUL2"]= $set_detil->getField("PUKUL2");
	$arrData[$index_loop]["PENGGALIAN_ID"]= $set_detil->getField("PENGGALIAN_ID");
	$arrData[$index_loop]["KODE"]= $set_detil->getField("KODE");
	$arrData[$index_loop]["PENGGALIAN_NAMA"]= $set_detil->getField("PENGGALIAN_NAMA");
	$arrData[$index_loop]["ASESOR_NAMA"]= $set_detil->getField("ASESOR_NAMA");

	if($cekLihatProses == "")
	{
		if($set_detil->getField("ASESOR_NAMA") == ""){}
		else
		$cekLihatProses= 1;
	}

	$arrData[$index_loop]["JADWAL_PEGAWAI_ID"]= $set_detil->getField("JADWAL_PEGAWAI_ID");
	$arrData[$index_loop]["JADWAL_ASESOR_ID"]= $set_detil->getField("JADWAL_ASESOR_ID");
	$arrData[$index_loop]["JADWAL_ASESOR_POTENSI_PEGAWAI_ID"]= $set_detil->getField("JADWAL_ASESOR_POTENSI_PEGAWAI_ID");
	$arrData[$index_loop]["JADWAL_ASESOR_POTENSI_ID"]= $set_detil->getField("JADWAL_ASESOR_POTENSI_ID");
	$arrData[$index_loop]["ASESOR_POTENSI_ID"]= $set_detil->getField("ASESOR_POTENSI_ID");
	$index_loop++;
}
$jumlah_data= $index_loop;
// print_r($arrData);exit;

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new Kelautan();
$set->selectByParamsMonitoring2(array(),-1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
//echo $set->errorMsg;exit;
$reqPegawaiNama=$set->getField('NAMA');
$reqPegawaiNipBaru=$set->getField('NIP_BARU');
// $reqPegawaiNipBaru="196610171992031001";
// $reqPegawaiNipBaru="196311201984031001";
$reqPegawaiPangkat= $set->getField('NAMA_GOL');
$reqPegawaiJabatan= $set->getField('NAMA_JAB_STRUKTURAL');
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

	//reqJadwalAsesorPotensiId;reqAsesorPotensiId
	function getJadwalPotensiAsesor(reqJadwalAcaraId, reqJadwalAsesorPotensiId)
	{
       	// console.log(reqJadwalAcaraId+", "+reqJadwalAsesorId);
		urlAjax= "../json-pengaturan/pegawai_get_jadwal_potensi_asesor_json.php?reqJadwalAcaraId="+reqJadwalAcaraId+"&reqJadwalAsesorPotensiId="+reqJadwalAsesorPotensiId;
   		$.ajax({'url': urlAjax,'success': function(data){
   			var reqPenggalianId= reqJadwalAsesorId= reqAsesorNama= "";
   			var data= JSON.parse(data);

   			reqPenggalianId= data.reqPenggalianId;
   			reqJadwalAsesorPotensiId= data.reqJadwalAsesorPotensiId;
   			reqAsesorPotensiId= data.reqAsesorPotensiId;
   			reqAsesorNama= data.reqAsesorNama;

   			$("#reqInfoSimpan"+reqPenggalianId).show();
			$("#reqInfoHapus"+reqPenggalianId).hide();
   			if(reqAsesorNama == ""){}
   			else
			{
				$("#reqInfoSimpan"+reqPenggalianId).hide();
				$("#reqInfoHapus"+reqPenggalianId).show();
			}

       		$("#reqAsesorNama"+reqPenggalianId).text(reqAsesorNama);
   			$("#reqJadwalAsesorPotensiId"+reqPenggalianId).val(reqJadwalAsesorPotensiId);
   			$("#reqAsesorPotensiId"+reqPenggalianId).val(reqAsesorPotensiId);
		}});
	}

	function getJadwalAsesor(reqJadwalAcaraId, reqJadwalAsesorId)
	{
       	// console.log(reqJadwalAcaraId+", "+reqJadwalAsesorId);
		urlAjax= "../json-pengaturan/pegawai_get_jadwal_asesor_json.php?reqJadwalAcaraId="+reqJadwalAcaraId+"&reqJadwalAsesorId="+reqJadwalAsesorId;
   		$.ajax({'url': urlAjax,'success': function(data){
   			var reqPenggalianId= reqJadwalAsesorId= reqAsesorNama= "";
   			var data= JSON.parse(data);

   			reqPenggalianId= data.reqPenggalianId;
   			reqJadwalAsesorId= data.reqJadwalAsesorId;
   			reqAsesorNama= data.reqAsesorNama;

   			$("#reqInfoSimpan"+reqPenggalianId).show();
			$("#reqInfoHapus"+reqPenggalianId).hide();
			if(reqAsesorNama == ""){}
			else
			{
				$("#reqInfoSimpan"+reqPenggalianId).hide();
				$("#reqInfoHapus"+reqPenggalianId).show();
			}

       		$("#reqAsesorNama"+reqPenggalianId).text(reqAsesorNama);
   			$("#reqJadwalAsesorId"+reqPenggalianId).val(reqJadwalAsesorId);
		}});
	}

	function sethapus(reqPenggalianId)
	{
		$.messager.confirm('Konfirmasi', "Apakah Anda yakin untuk hapus data ?",function(r){
			if (r){

				if(reqPenggalianId == 0)
				{
					valRowId= $("#reqJadwalAsesorPotensiPegawaiId"+reqPenggalianId).val();
					urlAjax= "../json-pengaturan/delete.php?reqMode=jadwal_asesor_potensi_pegawai&id="+valRowId;
				}
				else
				{
					valRowId= $("#reqJadwalPegawaiId"+reqPenggalianId).val();
					urlAjax= "../json-pengaturan/delete.php?reqMode=jadwal_pegawai&id="+valRowId;
				}

		   		$.ajax({'url': urlAjax,'success': function(data){
		   			$("#reqAsesorNama"+reqPenggalianId).text("");

					$("#reqJadwalPegawaiId"+reqPenggalianId).val("");
					$("#reqJadwalAsesorId"+reqPenggalianId).val("");
					$("#reqJadwalAsesorPotensiPegawaiId"+reqPenggalianId).val("");
					$("#reqJadwalAsesorPotensiId"+reqPenggalianId).val("");
					$("#reqAsesorPotensiId"+reqPenggalianId).val("");
		   			$("#reqInfoSimpan"+reqPenggalianId).show();
					$("#reqInfoHapus"+reqPenggalianId).hide();
				}});

				

			}
		});
	}

	function setpegawaiabsen(id)
	{
		var win = $.messager.progress({title:'Lookup data pegawai', msg:'Pencarian...'});
   		urlAjax= "../json-pengaturan/pegawai_nip_get_json.php?reqJadwalTesId=<?=$reqId?>&reqId="+id;
		
   		$.ajax({'url': urlAjax,'success': function(data){
   			var reqPegawaiId= reqPegawaiNama= reqPegawaiPangkat= reqPegawaiJabatan= reqPegawaiNomorUrut= "";
   			var data= JSON.parse(data);

   			reqPegawaiId= data.tempId;
   			reqPegawaiNama= data.tempNama;
			reqPegawaiPangkat= data.tempGol;
			reqPegawaiJabatan= data.tempJabatan;
			reqPegawaiNomorUrut= data.tempNomorUrut;

			$("#reqPegawaiId").val(reqPegawaiId);
			$("#reqPegawaiNama").text(reqPegawaiNama);
			$("#reqPegawaiPangkat").text(reqPegawaiPangkat);
			$("#reqPegawaiJabatan").text(reqPegawaiJabatan);
			$("#reqPegawaiNomorUrut").text(reqPegawaiNomorUrut);
   			// console.log(reqPegawaiId+"--");

   			$('a[id^="reqCariAsesor"]').hide();

   			$("#reqInfoData").text("");

   			setnomorstyle(1);
   			if(reqPegawaiId == "")
   			{
   				$("#reqInfoData").text("Data tidak di temukan");

   				$('[id^="reqAsesorNama"]').text("");
   				$('[id^="reqJadwalPegawaiId"], [id^="reqJadwalAsesorId"], [id^="reqJadwalAsesorPotensiPegawaiId"], [id^="reqJadwalAsesorPotensiId"], [id^="reqAsesorPotensiId"]').val("");

   				$('[id^="reqInfoSimpan"]').show();
   				$('[id^="reqInfoHapus"]').hide();
				$("#reqCetakData, #reqProsesData, #reqSubmit").hide();

   				$.messager.progress('close');
   			}
   			else
   			{
   				if(reqPegawaiNomorUrut == "")
   				{
	   				//set data simpan
					urlAjax= "../json-pengaturan/master_jadwal_add_absen_proses.php?reqId=<?=$reqId?>&reqPegawaiId="+reqPegawaiId;
					$.ajax({'url': urlAjax,'success': function(data){
						setpegawaijadwal(reqPegawaiId, reqPegawaiNomorUrut);
					}});
   				}
   				else
   				{
   					setpegawaijadwal(reqPegawaiId, reqPegawaiNomorUrut);
   				}
   				// setpegawaijadwal(reqPegawaiId, reqPegawaiNomorUrut);

   				setnomorstyle(2);
   				$("#reqCetakData, #reqSubmit").show();
   				$('a[id^="reqCariAsesor"]').show();
   			}

   			$.messager.progress('close');
		}});

	}

	function setpegawaijadwal(reqPegawaiId, reqPegawaiNomorUrut)
	{
		urlAjax= "../json-pengaturan/pegawai_jadwal_get_json.php?reqId=<?=$reqId?>&reqPegawaiId="+reqPegawaiId;
		$.ajax({'url': urlAjax,'success': function(data){
				var data= JSON.parse(data);
				panjangdata= data.length;
				for(i=0; i < panjangdata; i++)
				{
					reqJadwalTesId= reqJadwalAcaraId= reqPukul1= reqPukul2= reqPenggalianId= reqKode= 
					reqPenggalianNama= reqAsesorNama= reqJadwalPegawaiId= reqJadwalAsesorId= 
					reqJadwalAsesorPotensiPegawaiId= reqJadwalAsesorPotensiId= reqAsesorPotensiId= "";


					reqPenggalianId= data[i].PENGGALIAN_ID;
					reqAsesorNama= data[i].ASESOR_NAMA;
					reqJadwalPegawaiId= data[i].JADWAL_PEGAWAI_ID;
					reqJadwalAsesorId= data[i].JADWAL_ASESOR_ID;
					reqJadwalAsesorPotensiPegawaiId= data[i].JADWAL_ASESOR_POTENSI_PEGAWAI_ID;
					reqJadwalAsesorPotensiId= data[i].JADWAL_ASESOR_POTENSI_ID;
					reqAsesorPotensiId= data[i].ASESOR_POTENSI_ID;

					if(reqPegawaiNomorUrut == "")
					{
						reqPegawaiNomorUrut= data[i].NOMOR_URUT_GENERATE;
						$("#reqPegawaiNomorUrut").text(reqPegawaiNomorUrut);
					}

					$("#reqAsesorNama"+reqPenggalianId).text(reqAsesorNama);

					$("#reqJadwalPegawaiId"+reqPenggalianId).val(reqJadwalPegawaiId);
					$("#reqJadwalAsesorId"+reqPenggalianId).val(reqJadwalAsesorId);
					$("#reqJadwalAsesorPotensiPegawaiId"+reqPenggalianId).val(reqJadwalAsesorPotensiPegawaiId);
					$("#reqJadwalAsesorPotensiId"+reqPenggalianId).val(reqJadwalAsesorPotensiId);
					$("#reqAsesorPotensiId"+reqPenggalianId).val(reqAsesorPotensiId);

					// console.log(reqAsesorNama);

					// reqInfoSimpan;reqInfoHapus
				$("#reqInfoSimpan"+reqPenggalianId).show();
				$("#reqInfoHapus"+reqPenggalianId).hide();

				$("#reqProsesData").hide();
				var cekLihatProses= "";

				if(reqPenggalianId == 0)
				{
					if(reqJadwalAsesorPotensiPegawaiId == ""){}
					else
					{
						if(cekLihatProses == "")
						{
							cekLihatProses= 1;
						}

						$("#reqInfoSimpan"+reqPenggalianId).hide();
						$("#reqInfoHapus"+reqPenggalianId).show();
					}
				}
				else
				{
					if(reqJadwalPegawaiId == ""){}
					else
					{
						if(cekLihatProses == "")
						{
							cekLihatProses= 1;
						}

						$("#reqInfoSimpan"+reqPenggalianId).hide();
						$("#reqInfoHapus"+reqPenggalianId).show();
					}
				}

				}

				if(cekLihatProses == "")
				{
					/**/
				$("#reqProsesData").show();
				}

				// console.log(data.length);
		}});
	}

	function setnomorstyle(tipe)
    {
        $(document).ready( function () {
        	if(tipe == "1")
        	$("#spannomorurut").removeClass("texinfonumber");
        	else if(tipe == "2")
            $("#spannomorurut").addClass("texinfonumber");
        });
    }

	$(function(){
		$("#reqProsesData, #reqSubmit").hide();

		$("#btnKembali").on("click", function () {
			document.location.href="master_jadwal.php?reqJenis=<?=$reqJenis?>";
		});

		$("#reqSubmit").on("click", function () {
			$.messager.confirm('Konfirmasi', "Apakah Anda yakin untuk simpan data ?",function(r){
				if (r){
					$('#ff').submit();
					return true;
				}
			});
		});

		$("#reqCetakData").on("click", function () {
			$.messager.confirm('Konfirmasi', "Apakah Anda yakin untuk cetak kartu peserta ?",function(r){
				if (r){
					reqPegawaiId= $("#reqPegawaiId").val();

					opUrl= "diklat_kartu_peserta.php?reqId=<?=$reqId?>&reqPegawaiId="+reqPegawaiId;
					newWindow = window.open(opUrl, 'Cetak');
					newWindow.focus();
					// tempindextab= parseInt(tempindextab) + 1;
				}
			});
		});

		$("#reqProsesData").on("click", function () {
			reqPegawaiId= $("#reqPegawaiId").val();

			if(reqPegawaiId == "")
			{
				$.messager.alert('Info', "Isikan pegawai terlebih dahulu", 'info');
				return false;
			}
			else
			{
				$.messager.confirm('Konfirmasi', "Apakah Anda yakin untuk set data ?",function(r){
					if (r){

						urlAjax= "../json-pengaturan/master_jadwal_add_absen_proses.php?reqId=<?=$reqId?>&reqPegawaiId="+reqPegawaiId;
	       				$.ajax({'url': urlAjax,'success': function(data){
	       					// var data= JSON.parse(data);
	       					// panjangdata= data.length;

	       					var reqPegawaiNipBaru= "";
	       					reqPegawaiNipBaru= $("#reqPegawaiNipBaru").val();
	       					setpegawaiabsen(reqPegawaiNipBaru);

	       					// document.location.href = 'master_jadwal_add_absen.php?reqJenis=<?=$reqJenis?>&reqId=<?=$reqId?>';
							// $.messager.progress('close');
						}});

						
					}
				});
			}

		});

       $('a[id^="reqCariAsesor"]').click(function(e) {
       		var tempId= $(this).attr('id');
       		// console.log(tempId);

			// var tempValId= $(this).val();
			tempId= tempId.split('reqCariAsesor');
			reqPenggalianId= tempId[1];
			reqJadwalAcaraId= $("#reqJadwalAcaraId"+reqPenggalianId).val();
			reqJadwalAsesorId= $("#reqJadwalAsesorId"+reqPenggalianId).val();
			reqJadwalAsesorPotensiId= $("#reqJadwalAsesorPotensiId"+reqPenggalianId).val();

			varUrl= "";
			if(reqPenggalianId == 0)
			{
				varUrl= "master_jadwal_add_absen_potensi_lookup.php?reqId=<?=$reqId?>&reqRowId="+reqJadwalAcaraId+"&reqNotId="+reqJadwalAsesorPotensiId;
			}
			else
			{
				varUrl= "master_jadwal_add_absen_lookup.php?reqId=<?=$reqId?>&reqRowId="+reqJadwalAcaraId+"&reqNotId="+reqJadwalAsesorId;
			}
			OpenDHTMLCenter(varUrl, 'Pencarian Asesor', 780, 500);
       		// console.log(varUrl);
       		// console.log(reqPenggalianId);
       });

       $("#reqPegawaiNipBaru").keydown( function(e) {
	       	var id= $(this).attr('id');
	       	var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
	       	if(key == 13 || key == 9) 
	       	{
	       		e.preventDefault();
	       	}
       });

       $("#reqPegawaiNipBaru").keyup(function(e) {
       	var code = e.which;
       	// console.log(code);
       	// console.log($(this).val());
       	if(code==13)
       	{
       		setpegawaiabsen($(this).val());
       	}
       });

		$('#ff').form({
			url:'../json-pengaturan/master_jadwal_add_absen.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);return false;
				// console.log(data);return false;
				$.messager.alert('Info', data, 'info');
				document.location.href = 'master_jadwal_add_absen.php?reqJenis=<?=$reqJenis?>&reqId=<?=$reqId?>';
			}
		});

		$("#selectformula").on("change", function(){  
			valinfo= $(this).val();
			// alert(valinfo);
			document.location.href = 'master_jadwal_add_absen.php?reqJenis=<?=$reqJenis?>&reqId=<?=$infoid?>&reqCheckId='+valinfo;
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
	<div id="header-tna-detil">Validasi <span>Pegawai</span></div>
	<?
	if($reqId == "")
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
		    <td style="width: 50%; vertical-align: top;">
		    	<table class="table_list" cellspacing="1" width="100%">
			        <tr>
			            <td style="width: 15%; vertical-align: top;">Nip Peserta</td>
			            <td style="vertical-align: top;">:</td>
			            <td>
			            	<input autofocus type="text" style="width: 40%" id="reqPegawaiNipBaru" value="<?=$reqPegawaiNipBaru?>" />
			            	<label id="reqInfoData" style="font-size: 10px; font-weight: bold; color: red !important"></label>
			            	<br/>
			            	<label style="font-size: 10px; font-weight: bold;">Tekan enter untuk pencarian</label>
			            	<input type="hidden" class="easyui-validatebox" required name="reqPegawaiId" id="reqPegawaiId" />
			            </td>
			            <td rowspan="4">
			            	<span id="spannomorurut"><label id="reqPegawaiNomorUrut"></label></span>
			            </td>
			        </tr>
			        <tr>
			            <td>Nama</td>
			            <td>:</td>
			            <td><label id="reqPegawaiNama"><?=$reqPegawaiNama?></label></td>
			        </tr>
			        <tr>
			            <td>Pangkat</td>
			            <td>:</td>
			            <td><label id="reqPegawaiPangkat"><?=$reqPegawaiPangkat?></label></td>
			        </tr>
			        <tr>
			            <td>Jabatan</td>
			            <td>:</td>
			            <td><label id="reqPegawaiJabatan"><?=$reqPegawaiJabatan?></label></td>
			        </tr>
		    	</table>
		    </td>
		</tr>
        <tr>
            <td colspan="2">
            	<!-- style="display: none;" -->
                <input type="hidden" name="reqMode" value="insert">
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="button" id="btnKembali" value="Kembali" />
                <input type="button" id="reqSubmit" value="Simpan" />
                <?
                // if($cekLihatProses == "")
                // {
                ?>
                <input type="button" id="reqProsesData" value="Proses" />
                <?
            	// }
                ?>
                <input type="button" id="reqCetakData" value="Cetak" />
            </td>
        </tr>
    </table>
    <table class="gradient-style" style="width:100%; margin-left:-1px">
    <thead>
	    <tr>
	    	<th scope="col" style="width:1%"></th>
	    	<th scope="col" style="width:20%">Penggalian</th>
	    	<th scope="col" style="width:10%">Jam</th>
	    	<th scope="col">Keterangan</th>
	    </tr>
	</thead>
	<tbody>
    <?
	for($checkbox_index=0;$checkbox_index < $jumlah_data;$checkbox_index++)
	{
		$reqJadwalTesId= $arrData[$checkbox_index]["JADWAL_TES_ID"];
		$reqJadwalAcaraId= $arrData[$checkbox_index]["JADWAL_ACARA_ID"];
		$reqPenggalianId= $arrData[$checkbox_index]["PENGGALIAN_ID"];
		$arrData[$checkbox_index]["KODE"];
		$reqAsesorNama= $arrData[$checkbox_index]["ASESOR_NAMA"];
		$reqJadwalPegawaiId= $arrData[$checkbox_index]["JADWAL_PEGAWAI_ID"];
		$reqJadwalAsesorId= $arrData[$checkbox_index]["JADWAL_ASESOR_ID"];
		$reqJadwalAsesorPotensiPegawaiId= $arrData[$checkbox_index]["JADWAL_ASESOR_POTENSI_PEGAWAI_ID"];
		$reqJadwalAsesorPotensiId= $arrData[$checkbox_index]["JADWAL_ASESOR_POTENSI_ID"];
		$reqAsesorPotensiId= $arrData[$checkbox_index]["ASESOR_POTENSI_ID"];

		$tempLink= "";

		$displayNoneSimpan= "";
		$displayNoneHapus= "display:none";
		if($reqPenggalianId == 0)
		{
			if($reqJadwalAsesorPotensiPegawaiId == ""){}
			else
			{
				$displayNoneSimpan= "display:none";
				$displayNoneHapus= "";
			}
		}
		else
		{
			if($reqJadwalPegawaiId == ""){}
			else
			{
				$displayNoneSimpan= "display:none";
				$displayNoneHapus= "";
			}
		}

		$reqPenggalianNama= $arrData[$checkbox_index]["PENGGALIAN_NAMA"];
		$reqJam= $arrData[$checkbox_index]["PUKUL1"]." s/d ".$arrData[$checkbox_index]["PUKUL2"];
    ?>
    <tr>
    	<td>
    		<!-- cursor:pointer; -->
    		<a id="reqInfoSimpan<?=$reqPenggalianId?>" style=" <?=$displayNoneSimpan?>">
    			<img src="../WEB/images/icon_uncheck.png" width="15px" heigth="15px">
    		</a>
    		<a id="reqInfoHapus<?=$reqPenggalianId?>" style="cursor:pointer; <?=$displayNoneHapus?>" onclick="sethapus('<?=$reqPenggalianId?>')" >
    			<img src="../WEB/images/icon_check.png" width="15px" heigth="15px">
    		</a>

    		<input type="hidden" name="reqJadwalTesId[]" value="<?=$reqJadwalTesId?>" />
    		<input type="hidden" name="reqJadwalAcaraId[]" id="reqJadwalAcaraId<?=$reqPenggalianId?>" value="<?=$reqJadwalAcaraId?>" />
    		<input type="hidden" name="reqPenggalianId[]" value="<?=$reqPenggalianId?>" />

    		<input type="hidden" name="reqJadwalPegawaiId[]" id="reqJadwalPegawaiId<?=$reqPenggalianId?>" value="<?=$reqJadwalPegawaiId?>" />
    		<input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId<?=$reqPenggalianId?>" value="<?=$reqJadwalAsesorId?>" />
    		<input type="hidden" name="reqJadwalAsesorPotensiPegawaiId[]" id="reqJadwalAsesorPotensiPegawaiId<?=$reqPenggalianId?>" value="<?=$reqJadwalAsesorPotensiPegawaiId?>" />
    		<input type="hidden" name="reqJadwalAsesorPotensiId[]" id="reqJadwalAsesorPotensiId<?=$reqPenggalianId?>" value="<?=$reqJadwalAsesorPotensiId?>" />
    		<input type="hidden" name="reqAsesorPotensiId[]" id="reqAsesorPotensiId<?=$reqPenggalianId?>" value="<?=$reqAsesorPotensiId?>" />
    	</td>
    	<td><?=$reqPenggalianNama?></td>
    	<td><?=$reqJam?></td>
    	<td>
    		<a id="reqCariAsesor<?=$reqPenggalianId?>" style="display: none; cursor:pointer;">
    			<img src="../WEB/images/icn_search.png" width="15px" heigth="15px">
    		</a>
    		<label id="reqAsesorNama<?=$reqPenggalianId?>"><?=$reqAsesorNama?></label>
    	</td>
    </tr>
    <?
	}
    ?>
	</tbody>
	</table>
    </form>
    </div>
    <?
	}
    ?>
</div>
</body>
</html>