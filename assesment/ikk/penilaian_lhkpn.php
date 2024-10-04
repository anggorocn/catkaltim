<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Pegawai.php");

$reqId = httpFilterGet("reqId");
$reqPegawaiId = httpFilterGet("reqPegawaiId");
$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");
$reqKeterangan = httpFilterGet("reqKeterangan");
$reqCari = httpFilterGet("reqCari");

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

$lengthSatkerId= strlen($userLogin->userSatkerId);
$tinggi = 234;
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
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/examples/resources/demo.css">

<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/media/js/jquery.js"></script>
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
					   {"bVisible": false},
					   {"bVisible": false},
					   null,
					   null,
					   null,
					   null,
					   null,
					   {"bVisible": false},
					   null,
					   null,
					   null,
					   null,
					   null,
					   null,
					   {"bVisible": false},
					   {"bVisible": false},
					   null,
					   null,
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
		"sScrollXInner": "160%",
		"sAjaxSource": "../json-ikk/penilaian_lhkpn_json.php?reqId=<?=$reqId?>&reqCari=<?=$reqCari?>",
		"sPaginationType": "full_numbers",
		  "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			var valueStyle= "";
			valueStyle= nRow % 2;
			//oddWarna;evenWarna;anasWarna
			if( aData[20] == '1')
			{
				var i=0;
				for (i=0;i<=20;i++)
				{
					jQuery('td:eq('+i+')', nRow).addClass('hukumanStyle');
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
			  
			  opUrl    = '../silat/pegawai_edit.php?reqMode=1&reqId='+anSelectedPegawaiId;
			  //newWindow = window.open(opUrl, "", 'fullscreen=yes, scrollbars=auto');
			  //newWindow.focus();
			  window.top.OpenDHTML(opUrl, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');
		  });
		  
		  $('#btnProses').on('click', function () {
			  if(anSelectedData == "")
				  return false;
			  
			  window.top.OpenDHTML('potensi_add.php?reqId='+anSelectedPegawaiId, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');
		  });
		  
		  $('#btnMutasi').on('click', function () {
			  if(anSelectedData == "")
				  return false;
			  
			  opWidth= 1050;
			  opHeight= 550;
			  opUrl= 'mutasi_potensi.php?reqPegawaiId='+anSelectedPegawaiId+'&reqId='+anSelectedSatkerId;
			  //newWindow = window.open(opUrl, "", 'fullscreen=yes, scrollbars=auto');
			  //newWindow.focus();
			  window.top.OpenDHTML(opUrl, 'SIMPEG - Sistem Informasi Kepegawaian', '880', '495');
		  });
		  
		  $('#btnDeleteRow').on('click', function () {
			  if(anSelectedData == "")
				  return false;
				  
				$.messager.confirm('Confirm','Apakah anda yakin ingin menghapus data terpilih ?',function(r){
					if (r){
					
						var win = $.messager.progress({
											title:'Proses',
											msg:'Hapus data...'
										});
						var jqxhr = $.get( "delete.php?reqMode=potensi&id="+anSelectedPegawaiId, function() {
							$.messager.progress('close');
						})
						.done(function() {
							$.messager.progress('close');
							oTable.fnReloadAjax("../json-ikk/potensi_json.php?reqSearch=" + $("#reqStatus").val() + "&reqId=<?=$reqId?>");
						})
						.fail(function() {
							alert( "error" );
							$.messager.progress('close');
						});
					
					}
				});	
		  });
		  
		  $('#btnLembarSPMTRow').on('click', function () {
			  if(anSelectedData == "")
				  return false;
				  
			  var url= 'cetak_spmt.php?reqId='+anSelectedPegawaiId;
			  
			  newWindow = window.open(url, 'Cetak');
			  newWindow.focus();
		  });
		   $('#btnCetak').on('click', function () {
			  	newWindow = window.open('cetak_rekap_potensi_pdf.php?reqId=<?=$reqId?>');
				newWindow.focus();
				
		   });
		  $("#reqPencarian").keyup(function(e) { 
			var code = e.which;
			if(code==13)
			{
				  var reqPencarian= "";
				  reqPencarian= $("#reqPencarian").val();
				  oTable.fnReloadAjax("../json-ikk/potensi_json.php?reqId=<?=$reqId?>&reqCari=<?=$reqCari?>&sSearch="+reqPencarian);
			}
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
    <div id="header-tna">Data <span>Penilaian LHKPN / LHKSN</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
          <li>
          	<a href="#" title="Detail Data" id="btnUbahData"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Detail Data</a>
            <?php /*?><a href="#" title="Penilaian" id="btnProses" ><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Penilaian</a>
            <a href="#" title="Cetak" id="btnCetak" ><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak Rekap</a>
          	<a href="#" title="Cetak" onClick="windowOpenerPopup(250,650,'btncaripotensi','cari_potensi.php');"><img src="../WEB/images/potensi-search.png"/>&nbsp;Cari</a>
            <a href="#" id="btnEditArsip_" title="Pencarian" style="display:none"><img src="../WEB/images/icn_search.png"/>&nbsp;Pencarian</a>
            <a href="#" title="Integrasi" id="btnIntegrasi" ><img src="../WEB/images/potensi-edit.png"/>&nbsp;Integrasi Data</a><?php */?>
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
    <div class="bar-status">  	
    <?php /*?>Status Pegawai <select id="reqStatus" name="reqStatus"><option value="AND STATUS_PEGAWAI = 0">Usulan</option><option value="AND (STATUS_PEGAWAI = 1 OR STATUS_PEGAWAI = 2)" selected="selected">CPNS / PNS</option><option value="AND STATUS_PEGAWAI = 1">CPNS</option><option value="AND STATUS_PEGAWAI = 2">PNS</option><option value="AND STATUS_PEGAWAI = 3">Pensiun</option><option value="AND STATUS_PEGAWAI = 4">TNI</option><option value="AND (STATUS_PEGAWAI = 5 OR STATUS_PEGAWAI = 6)">Tewas / Wafat</option><option value="AND STATUS_PEGAWAI = 7">Pindah</option><option value="AND STATUS_PEGAWAI = 8">Diberhentikan</option><option value="AND EXISTS(SELECT 1 FROM HUKUMAN_TERAKHIR WHERE SYSDATE <= G.TANGGAL_AKHIR AND SYSDATE >= G.TANGGAL_MULAI)">Hukuman</option></select>
    <span style="padding-left:35px;"><label class="hukumanStyle">&nbsp;&nbsp;&nbsp;&nbsp;</label> Hukuman Disiplin</span><?php */?>
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