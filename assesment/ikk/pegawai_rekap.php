<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/FormulaAssesment.php");
include_once("../WEB/classes/base/RekapAssesment.php");
include_once("../WEB/classes/base/JadwalAwalTes.php");

$reqId = httpFilterGet("reqId");
$reqPegawaiId = httpFilterGet("reqPegawaiId");
$reqJadwalTesId = httpFilterGet("reqJadwalTesId");
$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");
$reqKeterangan = httpFilterGet("reqKeterangan");
$reqCari = httpFilterGet("reqCari");
$reqTahun = httpFilterGet("reqTahun");
$reqUjian = httpFilterGet("reqUjian");
$reqDateUjian = httpFilterGet("reqDateUjian");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	


$setUjian= new JadwalAwalTes();
$setUjian->selectByParams();
// $arrTahun="";
// $index_arr= 0;
// $penilaian_tahun= new Penilaian();
// $penilaian_tahun->selectByParamsTahunPenilaian();
// //echo $penilaian_tahun->errorMsg;exit;
// //echo $penilaian_tahun->query;exit;
// while($penilaian_tahun->nextRow())
// {
// 	$arrTahun[$index_arr]["TAHUN"] = $penilaian_tahun->getField("TAHUN");
// 	$index_arr++;
// }
// unset($penilaian_tahun);
//print_r($arrTahun);exit;

$set_eselon= new FormulaAssesment();
$set_eselon->selectByParams(array(), -1,-1, "", "ORDER BY A.TAHUN DESC, A.FORMULA_ID DESC");
// echo $set_eselon->query;exit;

$lengthSatkerId= strlen($userLogin->userSatkerId);
$tinggi = 236;

$arrkolomdata= array(
    array("label"=>"Nama", "width"=>"220px")
    , array("label"=>"NIP Baru", "width"=>"120px")
    , array("label"=>"Jabatan", "width"=>"100px")
    , array("label"=>"Instansi", "width"=>"150px")
    , array("label"=>"Info", "width"=>"150px")
);

$totalstandart= 0;
// if(!empty($reqJadwalTesId))
// {
// 	$jumlahdetil= 0;
// 	$setdetil= new RekapAssesment();
// 	$setdetil->selectByParamsFormula(array(), -1, -1, '', $reqJadwalTesId);
// 	// echo $setdetil->query;exit();
// 	while ($setdetil->nextRow()) 
// 	{
// 		array_push($arrkolomdata,     
// 			array("label"=>$setdetil->getField("ATRIBUT_NAMA")."<br/>".$setdetil->getField("NILAI_STANDAR"), "width"=>"100px")
// 		);
// 		$totalstandart += $setdetil->getField("NILAI_STANDAR");
// 		$jumlahdetil++;
// 	}
// }
// echo $jumlahdetil;exit();
array_push($arrkolomdata,
	// array("label"=>"Jumlah Skor<br/>".$totalstandart, "width"=>"100px"),
	array("label"=>"JPM", "width"=>"100px"),
	array("label"=>"Kategori", "width"=>"100px")
);
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
			<?
			for($col=0; $col<count($arrkolomdata); $col++)
			{
				if($col == 0){}
				else
					echo ",";
			?>
				null
			<?
			}
			?>
		],			
		"bProcessing": true,
		"bServerSide": true,
		"bFilter": false,
		//responsive: true,
		//columnDefs: [{ className: 'never', targets: [ 0, 1, -1, -2] }, { className: 'none', targets: [ -3,-4,-5,-6,-7,-8,-9,-10,-11,-12,-13 ] }],
		// columnDefs: [{ className: 'never', targets: [ 0, 1, 7, 13, 14, 16] }],
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",
		"sAjaxSource": "../json-silat/pegawai_rekap_json.php?reqId=<?=$reqId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqUjian=<?=$reqUjian?>&reqDateUjian=<?=$reqDateUjian?>",
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
			  //if(anSelectedData == "")
			  //{
				  //$.messager.alert('Info', "Pilih salah satu pegawai terlebih dahulu", 'info');  
				  //return false;
			  //}
			  
			  reqTahun= $("#reqTahun").val();
			  newWindow = window.open('cetak_psikogram_assesment_new_pdf.php?reqId='+anSelectedPegawaiId+'&reqTahun='+reqTahun);
			  newWindow.focus();				  
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
		  	console.log('vvv');
			  newWindow = window.open('pegawai_assement_cetak_excel.php');
			  newWindow.focus();				  
		  });

		  $('#btnCetakRekap').on('click', function () {
		  	console.log('bbb');
			  newWindow = window.open('pegawai_assement_cetak_excel_rekap.php');
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
				  var reqStatusPeg= reqTahun= reqJadwalTesId= reqPencarian= "";
				  // reqStatusPeg= $("#reqStatusPeg").val();
				  // reqTahun= $("#reqTahun").val();
				  reqJadwalTesId= $("#reqJadwalTesId").val();
				  reqPencarian= $("#reqPencarian").val();
				  //setFooter();
				  oTable.fnReloadAjax("../json-silat/pegawai_rekap_json.php?reqJadwalTesId="+reqJadwalTesId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian);
			}
		  });

		  $('#cetakexcel').on('click', function () {
		  	  var reqStatusPeg= reqTahun= reqJadwalTesId= reqPencarian= "";
			  
			  // reqStatusPeg= $("#reqStatusPeg").val();
			  // reqTahun= $("#reqTahun").val();
			  reqJadwalTesId= $("#reqJadwalTesId").val();
			  reqPencarian= $("#reqPencarian").val();

			  newWindow = window.open("pegawai_rekap_excel_baru.php?reqJadwalTesId="+reqJadwalTesId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian);
			  newWindow.focus();
		  });

		   $('#cetakrekap').on('click', function () {
		   			  	  console.log('aaa');

		  	  var reqStatusPeg= reqTahun= reqJadwalTesId= reqPencarian= "";

			  
			  // reqStatusPeg= $("#reqStatusPeg").val();
			  // reqTahun= $("#reqTahun").val();
			  reqJadwalTesId= $("#reqJadwalTesId").val();
			  reqPencarian= $("#reqPencarian").val();

			  // newWindow = window.open("pegawai_rekap_excel_rekap.php?reqJadwalTesId="+reqJadwalTesId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian);
			  newWindow = window.open("../json-ikk/cetak_rekap_json.php?reqJadwalTesId="+reqJadwalTesId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian);
			  newWindow.focus();
		  });
			  
		  $("#reqJadwalTesId,#reqTahun,#reqUjian,#reqDateUjian").change(function() { 
		  	  var reqStatusPeg= reqTahun= reqJadwalTesId= reqPencarian= "";
			  
			  reqJadwalTesId= $("#reqJadwalTesId").val();
			  reqPencarian= $("#reqPencarian").val();
 			  reqTahun= $("#reqTahun").val();
			  reqUjian= $("#reqUjian").val();
			  reqDateUjian= $("#reqDateUjian").val();

			  //setFooter();
			  document.location.href= "pegawai_rekap.php?reqJadwalTesId="+reqJadwalTesId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian+"&reqTahun="+reqTahun+"&reqUjian="+reqUjian+"&reqDateUjian="+reqDateUjian;
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
		var reqStatusPeg= reqTahun= reqJadwalTesId= reqPencarian= "";
			  
		reqStatusPeg= $("#reqStatusPeg").val();
		reqTahun= $("#reqTahun").val();
		reqJadwalTesId= $("#reqJadwalTesId").val();
		reqPencarian= $("#reqPencarian").val();
				 
		var s_url= "../json-ikk/jpm_ikk_total_json.php?reqId=<?=$reqId?>&reqTahun="+reqTahun+"&reqJadwalTesId="+reqJadwalTesId;
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
    <div id="header-tna">Data <span> Rekap Assesment</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
          <li>
            <a href="#" title="Cetak" id="cetakexcel"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak</a>
          </li>
<!--            <li>
            <a href="#" title="CetakRekap" id="cetakrekap"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak Rekap</a>
          </li> -->
   </ul>
   </div>

    </div>  
    <div class="bar-status">  	
    <input type="hidden" id="reqStatusPeg" name="reqStatusPeg" value="" />
    Formula
    <select id="reqJadwalTesId" name="reqJadwalTesId" style="width: 250px">
    	<option selected="selected" value="">Semua</option>
        <?
		while($set_eselon->nextRow())
		{
        ?>
        <option value="<?=$set_eselon->getField("FORMULA_ID")?>" <? if($reqJadwalTesId == $set_eselon->getField("FORMULA_ID")) echo "selected";?>><?=$set_eselon->getField("FORMULA")?></option>
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
        	<?
        	$mulaicolom= 4;
        	$rowspan= 1;
        	$batas= -1;
        	if(!empty($reqJadwalTesId) && $jumlahdetil > 0)
			{
				$rowspan= 2;
        		// $batas= count($arrkolomdata) - $jumlahdetil;
        		$batas= $jumlahdetil + $mulaicolom;
        	}

			for($col=0; $col<count($arrkolomdata); $col++)
			{
				if($col <= $mulaicolom)
				{
			?>
				<th rowspan="<?=$rowspan?>" width="<?=$arrkolomdata[$col]['width']?>"><?=$arrkolomdata[$col]['label']?></th>
			<?
				}
				elseif($col > $mulaicolom && $col <= $batas)
				// elseif($col <= $batas)
				{
					if($col == $batas)
					{
			?>
				<th style="text-align: center;" colspan="<?=$jumlahdetil?>">Standar Kompetensi</th>
			<?
					}
				}
				else
				{
			?>
				<th rowspan="<?=$rowspan?>" width="<?=$arrkolomdata[$col]['width']?>"><?=$arrkolomdata[$col]['label']?></th>
			<?
				}
			}
			?>
        </tr>

        <?
        if(!empty($reqJadwalTesId) && $jumlahdetil > 0)
        {
        ?>
        <tr>
        	<?
        	for($col=0; $col<count($arrkolomdata); $col++)
			{
				if($col > $mulaicolom && $col <= $batas)
				{
			?>
				<th width="<?=$arrkolomdata[$col]['width']?>"><?=$arrkolomdata[$col]['label']?></th>
			<?
				}
			}
			?>
        </tr>
        <?
    	}
        ?>
    </thead>
    </table>
    </div> <!--RIGHT CLICK EVENT -->
    
</body>
</html>