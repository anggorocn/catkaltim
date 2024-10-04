<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base/FormulaAssesment.php");
include_once("../WEB/classes/base/JadwalAwalTes.php");


$reqId = httpFilterGet("reqId");
$reqPegawaiId = httpFilterGet("reqPegawaiId");
$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");
$reqKeterangan = httpFilterGet("reqKeterangan");
$reqCari = httpFilterGet("reqCari");
$reqUjian = httpFilterGet("reqUjian");
$reqDateUjian = httpFilterGet("reqDateUjian");

//echo 'tes'.$reqCari;
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

	ini_set("memory_limit","500M");
	ini_set('max_execution_time', 520);	
	
/*if($reqId == "")
	$reqId= $userLogin->userSatkerId;*/

//$arrTahun= setTahunLoop(0,6);
//$reqTahun= 2015;

$arrTahun="";
$index_arr= 0;
$penilaian_tahun= new Penilaian();
$penilaian_tahun->selectByParamsTahunPenilaian();
//echo $penilaian_tahun->errorMsg;exit;
//echo $penilaian_tahun->query;exit;
while($penilaian_tahun->nextRow())
{
	$arrTahun[$index_arr]["TAHUN"] = $penilaian_tahun->getField("TAHUN");
	$index_arr++;
}
unset($penilaian_tahun);
//print_r($arrTahun);exit;

if($index_arr > 0)
	$reqTahun= $arrTahun[0]["TAHUN"];

$statement= " AND TIPE_FORMULA in('1','2')";

$set_eselon= new FormulaAssesment();
$set_eselon->selectByParams();
$set_eselon->selectByParams(array(), -1, -1, $statement);

$setUjian= new JadwalAwalTes();
$setUjian->selectByParams();

//echo $set_eselon->query;exit;
$lengthSatkerId= strlen($userLogin->userSatkerId);
$tinggi = 210;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title></title>
<style type="text/css" media="screen">
    @import "../WEB/lib/media/css/site_jui.css";
    @import "../WEB/lib/media/css/demo_table_jui.css";
    @import "../WEB/lib/media/css/themes/base/jquery-ui.css";
	
    /*
     * Override styles needed due to the mix of three different CSS sources! For proper examples
     * please see the themes example in the 'Examples' section of this site
     */
    .dataTables_info { padding-top: 0; }
    .dataTables_paginate { padding-top: 0; }
    .css_right { float: right; }
    #example_wrapper .fg-toolbar { font-size: 12px; }
    #theme_links span { float: left; padding: 2px 10px; }
	/*.transactionDebit { background-color:#6CF; }*/
	.hukumanStyle { background-color:#FC7370; }
</style>

<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/extensions/Responsive/css/dataTables.responsive.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
<!-- <link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/examples/resources/demo.css"> -->

<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/media/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
<!-- <script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/examples/resources/demo.js"></script>	 -->
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>	
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>	
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/extensions/Scroller/js/dataTables.scroller.min.js"></script>	
<script type="text/javascript" language="javascript" class="init">
	function trim(str)
	{
		if(!str || typeof str != 'string')
			return null;
	
		return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
	}

    $(document).ready( function () {
		//setFooter();
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
        var oTable = $('#example').dataTable({ "iDisplayLength": 50,bJQueryUI: true,
		/* UNTUK MENGHIDE KOLOM ID */
		"aoColumns": [ 
					   {"bVisible": false},
					   {"bVisible": false},
					   {"bVisible": false},
					   null,
					   null,
					   {"bVisible": false},
					   {"bVisible": false},
					   null,
					   {"bVisible": false},
					   null,
					   {"bVisible": false},
					   null,
					   null,
					   {"bVisible": false},
					   {"bVisible": false},
					   {"bVisible": false},
					   {"bVisible": false},
					   {"bVisible": false},
					   {"bVisible": false},
					   null
				  ],			
		"bProcessing": true,
		"bServerSide": true,
		"bFilter": false,
		//responsive: true,
		//columnDefs: [{ className: 'never', targets: [ 0, 1, -1, -2] }, { className: 'none', targets: [ -3,-4,-5,-6,-7,-8,-9,-10,-11,-12,-13 ] }],
		columnDefs: [{ className: 'never', targets: [ 0, 1, 7, 13, 14, 16] }],
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",
		"sAjaxSource": "../json-silat/pegawai_assement_json.php?reqId=<?=$reqId?>&reqCari=<?=$reqCari?>&reqTahun=<?=$reqTahun?>",
		"sPaginationType": "full_numbers"
		});
		/* Click event handler */
		  
		  $('#example tbody tr').on('dblclick', function () {
			  $("#btnUbahData").click();	
		  });														

		  /* RIGHT CLICK EVENT */
		  function fnGetSelected( oTableLocal )
		  {
			  var aReturn = new Array();
			  var aTrs = oTableLocal.fnGetNodes();
			  for ( var i=0 ; i<aTrs.length ; i++ )
			  {
				  if ( $(aTrs[i]).hasClass('row_selected') )
				  {
					  aReturn.push( aTrs[i] );
				  }
			  }
			  return aReturn;
		  }
		  function findRowIndexUsingCol(StringToCheckFor, oTableLocal, iColumn){
			  // Initialize variables
			  var i, aData, sValue, IndexLoc, oTable, iColumn;
			   
			  aiRows = oTableLocal.fnGetNodes();
			   
			  for (i=0,c=aiRows.length; i<c; i++) {
				  iRow = aiRows[i];   // assign current row to iRow variable
				  aData = oTableLocal.fnGetData(iRow); // Pull the row
				   
				  sValue = aData[iColumn];    // Pull the value from the corresponding column for that row
				   
				  if(sValue == StringToCheckFor){
					  IndexLoc = i;
					  break;
				  }
			  }
			   
			  return IndexLoc;
		  }
		  
		  var anSelectedData = '';
		  var anSelectedSatkerId = '';
		  var anSelectedPegawaiId = '';
		  var anSelectedPegawaiNipBaru = '';
		  var anSelectedPegawaiNama = '';
		  
		  $('#example tbody').on( 'click', 'tr', function () {
			  
			  $("#example tr").removeClass('row_selected');
			  $(".DTFC_Cloned tr").removeClass("row_selected");
			  var row = $(this);
			  var rowIndex = row.index() + 1;
			  
			  if (row.parent().parent().hasClass("DTFC_Cloned")) {
				  $("#example tr:nth-child(" + rowIndex + ")").addClass("row_selected");;
			  } else {
				  $(".DTFC_Cloned tr:nth-child(" + rowIndex + ")").addClass("row_selected");
			  }
			  
			  row.addClass("row_selected");												
			  var anSelected = fnGetSelected(oTable);													
			  anSelectedData = String(oTable.fnGetData(anSelected[0]));
			  var element = anSelectedData.split(','); 
			  anSelectedJadwalTesId = element[element.length-1];
			  // console.log(anSelectedJadwalTesId);
			  anSelectedSatkerId = element[element.length-2];
			  anSelectedPegawaiId = element[0];
			  anSelectedPegawaiNipBaru = element[3];
			  anSelectedPegawaiNama = element[4];
			  
		  });
		  
		  $('#btnUbahData').on('click', function () {
			  if(anSelectedData == "")
				  return false;
			  
			  opWidth = 1050;
			  opHeight = 550;
			  
			  opUrl    = '../silat/pegawai_edit.php?reqId='+anSelectedPegawaiId;
			  //newWindow = window.open(opUrl, "", 'fullscreen=yes, scrollbars=auto');
			  //newWindow.focus();
			  window.top.OpenDHTML(opUrl, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');
		  });
		  
		  $('#btnMutasi').on('click', function () {
			  if(anSelectedData == "")
				  return false;
			  
			  opWidth= 1050;
			  opHeight= 550;
			  opUrl= 'mutasi_pegawai.php?reqPegawaiId='+anSelectedPegawaiId+'&reqId='+anSelectedSatkerId;
			  //newWindow = window.open(opUrl, "", 'fullscreen=yes, scrollbars=auto');
			  //newWindow.focus();
			  window.top.OpenDHTML(opUrl, 'SIMPEG - Sistem Informasi Kepegawaian', '880', '495');
		  });
		  
		  $('#btnLihatPenilaian').on('click', function () {
			  if(anSelectedData == "")
				  return false;
			  var reqTahun= "";
			  reqTahun= $("#reqTahun").val();
			  window.top.OpenDHTML('general_ikk_add.php?reqId='+anSelectedPegawaiId+"&reqTahun="+reqTahun, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');
		  });
		  
		  $('#btnCetak').on('click', function () {
				reqTahun= $("#reqTahun").val();
				
			  	newWindow = window.open('pegawai_assement_excel.php?reqTahun='+reqTahun+'&reqId=<?=$reqId?>', 'Cetak');
				newWindow.focus();
		  });
		  
		  $('#btnLihatFile').on('click', function () {
			  if(anSelectedData == "")
				  return false;
				  
				$.messager.confirm('Confirm','Apakah anda ingin melihat data terpilih ?',function(r){
					if (r){
					
						var win = $.messager.progress({
											title:'Proses',
											msg:'Hapus data...'
										});
						var jqxhr = $.get( "../json-ikk/pegawai_lihat_file_get_json.php?reqNama="+anSelectedPegawaiNama, function() {
							$.messager.progress('close');
						})
						.done(function(data) {
							if(data == "")
								$.messager.alert('Info', "File tidak di temukan", 'info');
							else
							{
								newWindow = window.open(data, "Cetak");
				  				newWindow.focus();
							}
							$.messager.progress('close');
						})
						.fail(function() {
							alert( "error" );
							$.messager.progress('close');
						});
					
					}
				});	
		  });
		  
		  $('#preview').on('click', function () {
			  if(anSelectedPegawaiId == "")
			  {
				  $.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  return false;
			  }

			  reqTahun= $("#reqTahun").val();
			  // window.top.OpenDHTMLDetil('infografik.php?reqInfoLink=Cetak Psikogram Assessor&reqLink=cetak_psikogram_assesment_new_pdf&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');
			  
			  newWindow = window.open('cetak_psikogram_assesment_new_pdf.php?reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun);
			  newWindow.focus();				  
		  });

		  $('#previewUser').on('click', function () {
		  	  if(anSelectedPegawaiId == "")
			  {
				  $.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  return false;
			  }

			  reqTahun= $("#reqTahun").val();
			  // window.top.OpenDHTMLDetil('infografik.php?reqInfoLink=Cetak Psikogram User&reqLink=cetak_psikogram_assesment_new_user_pdf&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');

			  newWindow = window.open('cetak_psikogram_assesment_new_user_pdf.php?reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun);
			  newWindow.focus();				  
		  });

		  $('#previewSederhana').on('click', function () {
		  	  if(anSelectedPegawaiId == "")
			  {
				  $.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  return false;
			  }

			  reqTahun= $("#reqTahun").val();
			  // window.top.OpenDHTMLDetil('infografik.php?reqInfoLink=Cetak Psikogram User&reqLink=cetak_psikogram_assesment_new_user_pdf&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');

			  newWindow = window.open('cetak_psikogram_assesment_new_pdf.php?reqTipe=sederhana&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun);
			  newWindow.focus();				  
		  });

		  $('#previewSedang').on('click', function () {
		  	  if(anSelectedPegawaiId == "")
			  {
				  $.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  return false;
			  }

			  reqTahun= $("#reqTahun").val();
			  // window.top.OpenDHTMLDetil('infografik.php?reqInfoLink=Cetak Psikogram User&reqLink=cetak_psikogram_assesment_new_user_pdf&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');

			  newWindow = window.open('cetak_psikogram_assesment_new_pdf.php?reqTipe=sedang&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun);
			  newWindow.focus();				  
		  });

		  $('#previewKompleks').on('click', function () {
		  	  if(anSelectedPegawaiId == "")
			  {
				  $.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  return false;
			  }

			  reqTahun= $("#reqTahun").val();
			  // window.top.OpenDHTMLDetil('infografik.php?reqInfoLink=Cetak Psikogram User&reqLink=cetak_psikogram_assesment_new_user_pdf&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');

			  newWindow = window.open('cetak_psikogram_assesment_new_pdf.php?reqTipe=kompleks&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun);
			  newWindow.focus();				  
		  });
		  $('#previewRingkasan').on('click', function () {
		  	  if(anSelectedPegawaiId == "")
			  {
				  $.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  return false;
			  }

			  reqTahun= $("#reqTahun").val();

			  window.top.OpenDHTMLDetil('infografik.php?reqInfoLink=Ringkasan Laporan Individu&reqLink=cetak_ringkasan_pdf&reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');
			  // newWindow = window.open('cetak_ringkasan_pdf.php?reqId='+anSelectedPegawaiId+'&reqJadwalTesId='+anSelectedJadwalTesId+'&reqTahun='+reqTahun);
			  // newWindow.focus();				  
		  });
		  
		  
		  $('#btnLembarPsikogramRow').on('click', function () {
			  if(anSelectedData == "")
			  {
				  $.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  return false;
			  }
			  reqTahun= $("#reqTahun").val();
			  newWindow = window.open('cetak_psikogram_assesment_pdf.php?reqId='+anSelectedPegawaiId+'&reqTahun='+reqTahun);
			  newWindow.focus();				  
		  });
		  
		  $('#btnCetakExcel').on('click', function () {
			  newWindow = window.open('pegawai_assement_cetak_excel.php');
			  newWindow.focus();				  
		  });

		  // =============================================================
		  $('#btn1').on('click', function () {
			  newWindow = window.open('hal1.php');
			  newWindow.focus();				  
		  });
		   $('#btn2').on('click', function () {
			  newWindow = window.open('hal2.php');
			  newWindow.focus();				  
		  });
		    $('#btn3').on('click', function () {
			  newWindow = window.open('hal3.php');
			  newWindow.focus();				  
		  });
		  // =============================================================
		  
		  $('#btnCetakHasilIndividu').on('click', function () {
			  if(anSelectedData == "")
			  {
				  $.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  return false;
			  }
			  reqTahun= $("#reqTahun").val();
			  
			  $.messager.prompt('Laporan Hasil Individu', 'Masukkan tolerensi :', function(r){
				  if (r){
					  if(!isNaN(r))
					  {
						  newWindow = window.open('cetak_psikogram_assesment_hasil_pdf.php?reqId='+anSelectedPegawaiId+'&reqTahun='+reqTahun+"&reqToleransi="+r);
						  newWindow.focus();
					  }
					  else
					  {
						  $.messager.alert('Info', "Masukkan angka dengan benar", 'info');  
				  		  return false;
					  }
				  }
		      });
			  //newWindow = window.open('cetak_psikogram_assesment_hasil_pdf.php?reqId='+anSelectedPegawaiId+'&reqTahun='+reqTahun);
			  //newWindow.focus();				  
		  });
		  
		  $('#btnLembarPdfRow').on('click', function () {				   
				  newWindow = window.open('cetak_assesment_pdf.php');
				  newWindow.focus();				  
			  });
		  
		  $('#btnLembarSPMTRow').on('click', function () {
			  if(anSelectedData == "")
				  return false;
				  
			  var url= 'cetak_spmt.php?reqId='+anSelectedPegawaiId;
			  
			  newWindow = window.open(url, 'Cetak');
			  newWindow.focus();
		  });
		  
		  
		  $("#reqPencarian").keyup(function(e) { 
			var code = e.which;
			if(code==13)
			{
				  var reqStatusPeg= reqTahun= reqEselonId= reqPencarian= "";
				  reqStatusPeg= $("#reqStatusPeg").val();
				  reqTahun= $("#reqTahun").val();
				  reqEselonId= $("#reqEselonId").val();
				  reqPencarian= $("#reqPencarian").val();
				  //setFooter();
				  oTable.fnReloadAjax("../json-silat/pegawai_assement_json.php?reqStatusPeg="+reqStatusPeg+"&reqTahun="+reqTahun+"&reqEselonId="+reqEselonId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian);
			}
		  });
			  
		  $("#reqStatusPeg,#reqTahun,#reqEselonId,#reqUjian,#reqDateUjian").change(function() { 
		  	  var reqStatusPeg= reqTahun= reqEselonId= reqPencarian=reqDateUjian=reqUjian="";
			  
			  reqStatusPeg= $("#reqStatusPeg").val();
			  reqTahun= $("#reqTahun").val();
			  reqEselonId= $("#reqEselonId").val();
			  reqPencarian= $("#reqPencarian").val();
			  reqUjian= $("#reqUjian").val();
			  reqDateUjian= $("#reqDateUjian").val();
			  
			  //setFooter();
			  oTable.fnReloadAjax("../json-silat/pegawai_assement_json.php?reqStatusPeg="+reqStatusPeg+"&reqTahun="+reqTahun+"&reqEselonId="+reqEselonId+"&reqUjian="+reqUjian+"&reqDateUjian="+reqDateUjian+"&reqId=<?=$reqId?>&sSearch="+reqPencarian);
		  });
		  
		  $('#rightclickarea').bind('contextmenu',function(e){
			  if(anSelectedData == '')	
				  return false;							
		  var $cmenu = $(this).next();
		  $('<div class="overlay"></div>').css({left : '0px', top : '0px',position: 'absolute', width: '100%', height: '100%', zIndex: '100' }).click(function() {				
			  $(this).remove();
			  $cmenu.hide();
		  }).bind('contextmenu' , function(){return false;}).appendTo(document.body);
		  $(this).next().css({ left: e.pageX, top: e.pageY, zIndex: '101' }).show();

		  return false;
		   });

		   $('.vmenu .first_li').on('click',function() {
			  if( $(this).children().size() == 1 ) {
				  if($(this).children().text() == 'Detail Data')
				  {
					  $("#btnUbahData").click();																										
				  }
				  else if($(this).children().text() == 'Tambah Data')
				  {
					  $("#btnAddNewRow").click();
				  }
				  else if($(this).children().text() == 'Hapus Data')
				  {
					  $("#btnDeleteRow").click();
				  }											
				  $('.vmenu').hide();
				  $('.overlay').hide();
			  }
		   });

		   $('.vmenu .inner_li span').on('click',function() {												
				  if($(this).text() == 'FIP 01')
				  {
					  $("#btnLembarFIP01Row").click();																										
				  }
				  else if($(this).text() == 'FIP 02')
				  {
					  $("#btnLembarFIP02Row").click();																										
				  }
				  else if($(this).text() == 'Biodata Lengkap')
				  {
					  $("#btnLembarBioLengkapRow").click();																										
				  }
				  else if($(this).text() == 'Biodata Singkat')
				  {
					  $("#btnLembarBioSingkatRow").click();																										
				  }
				  else if($(this).text() == 'Model C')
				  {
					  $("#btnLembarModelRow").click();																										
				  }
				  else if($(this).text() == 'SPMT')
				  {
					  $("#btnLembarSPMTRow").click();																										
				  }
				  else if($(this).text() == 'Copy')
				  {
				  }												
				  $('.vmenu').hide();
				  $('.overlay').hide();
		   });

  
		  $(".first_li , .sec_li, .inner_li span").hover(function () {
			  $(this).css({backgroundColor : '#E0EDFE' , cursor : 'pointer'});
		  if ( $(this).children().size() >0 )
				  $(this).find('.inner_li').show();	
				  $(this).css({cursor : 'default'});
		  }, 
		  function () {
			  $(this).css('background-color' , '#fff' );
			  $(this).find('.inner_li').hide();
		  });
		  /* RIGHT CLICK EVENT */
							
	} );
	
	function setFooter()
	{
		var reqStatusPeg= reqTahun= reqEselonId= reqPencarian= "";
			  
		reqStatusPeg= $("#reqStatusPeg").val();
		reqTahun= $("#reqTahun").val();
		reqEselonId= $("#reqEselonId").val();
		reqPencarian= $("#reqPencarian").val();
				 
		var s_url= "../json-ikk/jpm_ikk_total_json.php?reqId=<?=$reqId?>&reqTahun="+reqTahun+"&reqEselonId="+reqEselonId;
		var request = $.get(s_url);
		request.done(function(dataJson)
		{
			var data= JSON.parse(dataJson);
			tempJpm= data.tempJpm;
			tempIkk= data.tempIkk;
			tempId= data.tempId;
			
			if(tempId == ""){}
			else
			{
				$("#reqInfoJpm").text(tempJpm);
				$("#reqInfoIkk").text(tempIkk);
			}
		});
	}
</script>

<!--RIGHT CLICK EVENT-->		
<style>
	.vmenu{
	border:1px solid #aaa;
	position:absolute;
	background:#fff;
	display:none;font-size:0.75em;}
	.first_li{}
	.first_li span{width:100px;display:block;padding:5px 10px;cursor:pointer}
	.inner_li{display:none;margin-left:120px;position:absolute;border:1px solid #aaa;border-left:1px solid #ccc;margin-top:-28px;background:#fff;}
	.sep_li{border-top: 1px ridge #aaa;margin:5px 0}
	.fill_title{font-size:11px;font-weight:bold;/height:15px;/overflow:hidden;word-wrap:break-word;}

</style>
<!--RIGHT CLICK EVENT-->		
<!--<link href="themes/main_datatables.css" rel="stylesheet" type="text/css" /> -->

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="../WEB/css/dropdowntabs.js"></script>
</head>

<body id="index" class="grid_2_3" style="overflow:hidden">
    <div class="full_width" style="width:100%;">
    <form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
    </form>
    <div id="header-tna">Data <span>Assesment</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(../WEB/css/media/bluetab.gif)">    
    <ul>
          <li>
          	<a href="#" title="Laporan Individu" id="btnLihatFile" style="display:none"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Laporan Individu</a>
            <a href="#" title="Lihat Penilaian" id="btnLihatPenilaian" ><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Lihat Penilaian</a>
            <a href="#" title="Cetak" rel="dropmenu2_b"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak</a>
         
          </li>
   </ul>
   </div>
      
    <!--2nd drop down menu -->
    <div id="dropmenu2_b" class="dropmenudiv_b" style="width: 250px;">
    <!-- <a href="#" title="CetakPsikogram" id="btnLembarPsikogramRow">Cetak Psikogram</a> -->
    <a href="#" title="CetakPsikogram" id="preview">Cetak Psikogram Assessor</a>
    <a href="#" title="CetakPsikogramUser" id="previewUser">Cetak Psikogram User</a>
    <!-- <a href="#" title="Cetak Hasil Individu" id="btnCetakHasilIndividu">Laporan Hasil Individu</a> -->
    <a href="#" title="Cetak Ringkasan" id="previewRingkasan">Ringkasan Laporan Individu</a>
    <a href="#" title="CetakPsikogramSederhana" id="previewSederhana">Cetak Psikogram Sederhana</a>
    <a href="#" title="CetakPsikogramSedang" id="previewSedang">Cetak Psikogram Sedang</a>
    <a href="#" title="CetakPsikogramKompleks" id="previewKompleks">Cetak Psikogram Kompleks</a>

    <!-- <a href="#" title="CetakExcel" id="btnCetakExcel">Cetak Excel</a> -->
    <?php /*?><a href="#" title="CetakPdf" id="btnLembarPdfRow">Cetak Pdf</a><?php */?>
    </div>
    
    
    <script type="text/javascript">
    //SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
    tabdropdown.init("bluemenu")
    </script>
    
    </div>  
    <div class="bar-status" style="margin-left:20px">  	
    <input type="hidden" id="reqStatusPeg" name="reqStatusPeg" value="" />
    <?php /*?>Status Pegawai 
    <select id="reqStatusPeg" name="reqStatusPeg">
    	<option selected="selected" value="">Semua</option>
    	<option value="0" >Aktif</option>
        <option value="k">Kontrak</option>
    </select><?php */?>
    <!-- <input type="hidden" id="reqEselonId" value="" /> -->
    
    Formula
    <select id="reqEselonId" name="reqEselonId">
    	<option selected="selected" value="">Semua</option>
        <?
		while($set_eselon->nextRow())
		{
        ?>
        <option value="<?=$set_eselon->getField("FORMULA_ID")?>"><?=$set_eselon->getField("FORMULA")?></option>
        <?
		}
        ?>
    </select>
    Tahun : 
    <select name="reqTahun" id="reqTahun">
    <?
    for($i=0;$i<count($arrTahun);$i++)
    {
    ?>
    <option value="<?=$arrTahun[$i]["TAHUN"]?>" <? if($reqTahun == $arrTahun[$i]["TAHUN"]) { ?> selected <? } ?>><?=$arrTahun[$i]["TAHUN"]?></option>
    <?	  
    }
    ?>
    </select>
    Nama Ujian
    <select id="reqUjian" name="reqUjian">
    	<option selected="selected" value="">Semua</option>
        <?
		while($setUjian->nextRow())
		{
        ?>
        <option value="<?=$setUjian->getField("JADWAL_AWAL_TES_ID")?>"><?=$setUjian->getField("KETERANGAN")?></option>
        <?
		}
        ?>
    </select>
    Tanggal
    <input type="date" name="reqDateUjian" id="reqDateUjian">
    <?php /*?>Status Pegawai <select id="reqStatus" name="reqStatus"><option value="AND STATUS_PEGAWAI = 0">Usulan</option><option value="AND (STATUS_PEGAWAI = 1 OR STATUS_PEGAWAI = 2)" selected="selected">CPNS / PNS</option><option value="AND STATUS_PEGAWAI = 1">CPNS</option><option value="AND STATUS_PEGAWAI = 2">PNS</option><option value="AND STATUS_PEGAWAI = 3">Pensiun</option><option value="AND STATUS_PEGAWAI = 4">TNI</option><option value="AND (STATUS_PEGAWAI = 5 OR STATUS_PEGAWAI = 6)">Tewas / Wafat</option><option value="AND STATUS_PEGAWAI = 7">Pindah</option><option value="AND STATUS_PEGAWAI = 8">Diberhentikan</option><option value="AND EXISTS(SELECT 1 FROM HUKUMAN_TERAKHIR WHERE SYSDATE <= G.TANGGAL_AKHIR AND SYSDATE >= G.TANGGAL_MULAI)">Hukuman</option></select>
    <span style="padding-left:35px;"><label class="hukumanStyle">&nbsp;&nbsp;&nbsp;&nbsp;</label> Hukuman Disiplin</span><?php */?>
    <span style="display:none">
    JPM per satker :
    <label style="font-weight:bold; font-size:14px;" id="reqInfoJpm"></label>&nbsp;&nbsp;&nbsp;
    IKK per satker :
    <label style="font-weight:bold; font-size:14px;" id="reqInfoIkk"></label>
    </span>
    </div>
    
    <div style="position: relative; margin-bottom:-30px; margin-top:8px; float:right; z-index:9999; font-size:12px;">
        Pencarian :
        <input type="text" id="reqPencarian" style="width:155px" />
    </div>
    
    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th style="width:10px;">Id</th>
            <th width="10px">No</th>
            <th width="100px">NIP</th>
            <th width="120px">NIP Baru</th>
            <th width="220px">Nama</th>
            <th width="90px">Tempat Lahir</th>
            <th width="90px">Tgl. Lahir</th>
            <th width="20px">L/P</th>
            <th width="90px">Status</th>
            <th width="80px">Gol. Ruang</th>
            <th width="120px">TMT Pangkat</th>      
            <th width="20px">Eselon</th>            
            <th width="320px">Jabatan</th>                  
            <th width="120px">TMT Jabatan</th>                  
            <th width="80px">Agama</th>                                         
            <th width="100px">Telepon</th>                                         
            <th width="300px">Alamat</th>                                         
            <th width="300px">Satuan Kerja</th>                                         
            <th width="100px">TMT Pensiun</th>
            <th width="100px">Info</th>
        </tr>
    </thead>
    </table>
    </div> <!--RIGHT CLICK EVENT -->
    
    
    <div class="vmenu">
        <div class="first_li"><span>Detail Data</span></div>
    </div>
    
    <?php /*?><!--RIGHT CLICK EVENT -->
    <div class="vmenu">
        <div class="sep_li"></div>
        <div class="first_li"><span>Cetak Data</span>
            <div class="inner_li">
                <span>FIP 01</span> 
                <span>FIP 02</span>
                <span>Biodata Lengkap</span>
                <span>Biodata Singkat</span>              
                <span>Model C</span>              
                <span>SPMT</span>                                          
            </div>
        </div>
    
        <div class="first_li"><span>Data Pegawai</span>
            <div class="inner_li">
                <span>Simpan Data</span>
                <span>Copy</span>
            </div>
        </div>
    	<!--RIGHT CLICK EVENT -->
    </div><?php */?>
    
</body>
</html>