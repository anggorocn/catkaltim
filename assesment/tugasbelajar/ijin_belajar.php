<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

$reqTahun= httpFilterGet("reqTahun");
$reqPegawaiId = httpFilterGet("reqPegawaiId");
$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");
$reqKeterangan = httpFilterGet("reqKeterangan");
$reqCari = httpFilterGet("reqCari");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	
	

$lengthSatkerId= strlen($userLogin->userSatkerId);
$tinggi = 213;
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
	
	.coloringRowRed { background-color:#FBADA2; }
	.coloringRowKuning { background-color:#F3FB91; }
	.coloringRowPutih { background-color:#FFF; }
	.coloringRowHijau { background-color:#88EA2D; }
</style>

<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/extensions/Responsive/css/dataTables.responsive.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/examples/resources/demo.css">

<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/daterangepicker.js"></script>
    
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/examples/resources/demo.js"></script>	
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
		
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
        var oTable = $('#example').dataTable({ "iDisplayLength": 50,bJQueryUI: true,
		/* UNTUK MENGHIDE KOLOM ID */
		"aoColumns": [ 
						{ bVisible:false },
						null,
						null,
						null,
						null,
						null,
						null,
						null
				  ],			
		"bProcessing": true,
		"bServerSide": true,
		//responsive: true,
		//columnDefs: [{ className: 'never', targets: [ 0, 1, -1, -2] }, { className: 'none', targets: [ -3,-4,-5,-6,-7,-8,-9,-10,-11,-12,-13 ] }],
		//columnDefs: [{ className: 'never', targets: [ -1, -2] }],
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",					  
		"sAjaxSource": "../json-tugasbelajar/ijin_belajar_json.php",
		"sPaginationType": "full_numbers",
		  "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			 if(aData[8] == 1)
			  {
				  var i=0;
				  for (i=0;i<=7;i++)
				  {
				  jQuery('td:eq('+i+')', nRow).addClass('coloringRowPutih');
				  }
			  }
			  else if(aData[8] == 2)
			  {
				  var i=0;
				  for (i=0;i<=7;i++)
				  {
				  jQuery('td:eq('+i+')', nRow).addClass('coloringRowKuning');
				  }
			  }
			  else if(aData[8] == 3)
			  {
				  var i=0;
				  for (i=0;i<=7;i++)
				  {
				  jQuery('td:eq('+i+')', nRow).addClass('coloringRowRed');
				  }
			  }
			  else if(aData[8] == 4)
			  {
				  var i=0;
				  for (i=0;i<=7;i++)
				  {
				  jQuery('td:eq('+i+')', nRow).addClass('coloringRowHijau');
				  }
			  }
			  return nRow;
			 }
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
		  var anSelectedId = anSelectedStatusInfoId= anSelectedPegawaiId = '';
		  
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
			  anSelectedId= element[0];
			  anSelectedPegawaiId= element[element.length-1];
			  anSelectedStatusInfoId= element[element.length-2];
			  //alert(anSelectedId);
		  });
		  
		  $('#btnTambah,#btnUbahData').on('click', function () {
			  var tempId= $(this).attr('id');
			  if(tempId == "btnTambah")
				anSelectedId= "";
			  else
			  {
				  if(anSelectedData == "")
					  return false;
			  }
			  
			  opWidth = 1050;
			  opHeight = 550;
			  
			  opUrl= 'ijin_belajar_add.php?reqId='+anSelectedId+'&reqPegawaiId='+anSelectedPegawaiId;
			  
			  window.top.OpenDHTML(opUrl, "Ijin Belajar", '880', '495');
		  });
		  
		  $('#btnPerpanjangan').bind('click', function () {
			 if(anSelectedStatusInfoId !== "2")
				  return false;
			  //alert(anSelectedPegawaiId);
			  $.messager.confirm('Konfirmasi', "Apakah Anda yakin merubah data menjadi perpanjangan dan tmt akhir nya d tambah 1 tahun ?",function(r){
					if (r){
						var win = $.messager.progress({
											title:'Proses Kirim',
											msg:'Proses data...'
						});
						
						var jqxhr = $.post( "../json-tugasbelajar/ijin_belajar_add.php?reqId="+anSelectedId, function() {
							arrChecked = [];
							$.messager.progress('close'); 
						})
						.done(function(dataJson) {
							if(dataJson == 1)
							{
								$.messager.alert('Info', "Data berhasil di simpan", 'info');
							}
							else
							$.messager.alert('Info', dataJson, 'info');
							
							var reqStatusBelajar= reqTanggalAwalFilter= reqTanggalAkhirFilter= "";
				
							reqStatusBelajar= $("#reqStatusBelajar").val();
							reqTanggalAwalFilter= $("#reqTanggalAwalFilter").val();
							reqTanggalAkhirFilter= $("#reqTanggalAkhirFilter").val();
							
							if(reqTanggalAwalFilter.length == 10){}
							else
								reqTanggalAwalFilter= "";
							
							if(reqTanggalAkhirFilter.length == 10){}
							else
								reqTanggalAkhirFilter= "";
							
							oTable.fnReloadAjax("../json-tugasbelajar/ijin_belajar_json.php?reqStatusBelajar="+reqStatusBelajar+"&reqTanggalAwalFilter="+reqTanggalAwalFilter+"&reqTanggalAkhirFilter="+reqTanggalAkhirFilter);
						})
						.fail(function() {
							arrChecked = [];
							alert( "error" );
							$.messager.progress('close');
						});								
					}
				});					  
		  });
		  
		  $('#btnEksporXls').on('click', function () {
			  newWindow = window.open('rekap_tugas_belajar_excel.php', 'Cetak');
			  newWindow.focus();
		  });
			  
			$("#reqStatusBelajar,#reqTanggalAwalFilter,#reqTanggalAkhirFilter").change(function() { 
				var reqStatusBelajar= reqTanggalAwalFilter= reqTanggalAkhirFilter= "";
				
				reqStatusBelajar= $("#reqStatusBelajar").val();
				reqTanggalAwalFilter= $("#reqTanggalAwalFilter").val();
				reqTanggalAkhirFilter= $("#reqTanggalAkhirFilter").val();
				
				if(reqTanggalAwalFilter.length == 10){}
				else
					reqTanggalAwalFilter= "";
				
				if(reqTanggalAkhirFilter.length == 10){}
				else
					reqTanggalAkhirFilter= "";
				
				oTable.fnReloadAjax("../json-tugasbelajar/ijin_belajar_json.php?reqStatusBelajar="+reqStatusBelajar+"&reqTanggalAwalFilter="+reqTanggalAwalFilter+"&reqTanggalAkhirFilter="+reqTanggalAkhirFilter);
			});
			
			$("#reqStatusInfo").change(function() { 
				var reqStatusInfo= "";
				
				reqStatusInfo= $("#reqStatusInfo").val();
				
				oTable.fnReloadAjax("../json-tugasbelajar/ijin_belajar_json.php?reqStatusInfo="+reqStatusInfo);
			});
			
		  $('#btnDelete').on('click', function () {
			  if(anSelectedData == "")
				  return false;
				
				$.messager.confirm('Confirm','Apakah anda yakin ingin menghapus data terpilih ?',function(r){
					if (r){
					
						var win = $.messager.progress({
											title:'Proses',
											msg:'Hapus data...'
										});
						var jqxhr = $.get( "../json-tugasbelajar/delete.php?reqMode=tugas_belajar&id="+anSelectedId, function() {
							$.messager.progress('close');
						})
						.done(function() {
							$.messager.progress('close');
							
							oTable.fnReloadAjax("../json-tugasbelajar/ijin_belajar_json.php");
						})
						.fail(function() {
							alert( "error" );
							$.messager.progress('close');
						});
					
					}
				});	
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
				  $('.vmenu').hide();
				  $('.overlay').hide();
			  }
		   });

		   $('.vmenu .inner_li span').on('click',function() {												
				  if($(this).text() == 'FIP 01')
				  {
					  $("#btnLembarFIP01Row").click();																										
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
	
	$(function() {
		var dates = $( "#reqTanggalAwalFilter, #reqTanggalAkhirFilter" ).datepicker({
			defaultDate: "+1w",
			dateFormat: 'dd-mm-yy',
			numberOfMonths: 1,
			beforeShow: function( ) {
				var other = this.id == "reqTanggalAwalFilter" ? "#reqTanggalAkhirFilter" : "#reqTanggalAwalFilter";
				var option = this.id == "reqTanggalAwalFilter" ? "maxDate" : "minDate";
				var selectedDate = $(other).datepicker('getDate');
				
				$(this).datepicker( "option", option, selectedDate );
			}
		}).change(function(){
			var other = this.id == "reqTanggalAwalFilter" ? "#reqTanggalAkhirFilter" : "#reqTanggalAwalFilter";
			
			if ( $('#reqTanggalAwalFilter').datepicker('getDate') > $('#reqTanggalAkhirFilter').datepicker('getDate') )
				$(other).datepicker('setDate', $(this).datepicker('getDate') );
		});
	});
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
<script type="text/javascript" src="css/dropdowntabs.js"></script>
</head>

<body id="index" class="grid_2_3" style="overflow:hidden">
    <div class="full_width" style="width:100%;">
    <form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
    </form>
    <div id="header-tna">Master <span>Ijin Belajar</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
          <li>
            <?
			if($userLogin->userLihatProses== 1){
			?>	
				<li><a href="#" id="btnTambah" title="Tambah"><img src="../WEB/images/pegawai-edit.png"/>Tambah</a></li>
            	<li><a href="#" id="btnUbahData" title="Ubah"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Ubah</a></li>                
			<?
            }
			else{
			?>
            	<li><a href="#" id="btnUbahData" title="Ubah"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Lihat</a></li>
            <?
			}
			?>
            <?
			if($userLogin->userLihatProses== 1){
			?>
				<li><a href="#" id="btnPerpanjangan" title="Perpanjangan"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Perpanjangan</a></li>
                <li><a href="#" id="btnDelete" title="Hapus"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Hapus</a></li>
                <li><a href="#" id="btnUsulan" title="Usulan"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Proses Usulan</a></li>
                <li><a href="#" id="btnEksporXls" title="Ekspor"><img src="../WEB/images/icon-excel.png" /> Export Excel</a></li>
			<?
			}
			else{
			?>
            <? }
            ?>
          </li>
    </ul>
    </div>
      
    <!--2nd drop down menu -->
    <div id="dropmenu2_b" class="dropmenudiv_b" style="width: 250px;">
    <a href="#" title="FIP 01" id="btnLembarFIP01Row">FIP 01</a>
    </div>
    
    <script type="text/javascript">
    //SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
    tabdropdown.init("bluemenu")
    </script>
    
    </div>  
    
    <div id="bar-status">
    Tanggal
    <input type="text" id="reqTanggalAwalFilter" name="reqTanggalAwalFilter" style="width:70px" />
    &nbsp;s/d&nbsp;
    <input type="text" id="reqTanggalAkhirFilter" name="reqTanggalAkhirFilter" style="width:70px" />
    &nbsp;Status Belajar&nbsp;:&nbsp;
    <select id="reqStatusBelajar" name="reqStatusBelajar">
    	<option value="">Semua</option>
        <option value="1">Tugas Belajar</option>
        <option value="2">Perpanjangan Tugas Belajar</option>
        <option value="3">Pengaktifan Tugas Belajar</option>
    </select>
    &nbsp;Status Belajar&nbsp;:&nbsp;
    <select id="reqStatusInfo" name="reqStatusInfo" style="width:100px">
    	<option value="">Semua</option>
        <option value="1"><= 3 bulan Tugas Belajar</option>
        <option value="2">> 3 bulan Tugas Belajar</option>
    </select>
    </div>
    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th width="1px">id</th>
            <th width="150px">Nama Pegawai</th>
            <th width="70px">No SK</th>
            <th width="150px">Jurusan</th>
            <th width="150px">Nama Sekolah</th>
             <th width="100px">Status Belajar</th>
            <th width="100px">Mulai</th>
            <th width="100px">Selesai</th>
        </tr>
    </thead>
    </table>
    </div> <!--RIGHT CLICK EVENT -->
    
    
    <div class="vmenu">
        <div class="first_li"><span>Detail Data</span></div>
    </div>
</body>
</html>