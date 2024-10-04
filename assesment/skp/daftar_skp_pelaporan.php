<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-skp/Kategori.php");


//echo 'tes'.$reqCari;
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	


$kategori = new Kategori();
$jumlah_kategori = $kategori->getCountByParams(array());
$kategori->selectByParams(array());

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
							  { bVisible:false },
							 { bVisible:false },
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 null,
							 <?
							 for($i=1;$i<=$jumlah_kategori;$i++)
							 {
							 ?>
							 null,							 
							 <?
							 }
							 ?>
							 null		
				  ],			
		"bProcessing": true,
		"bServerSide": true,
		//responsive: true,
		//columnDefs: [{ className: 'never', targets: [ 0, 1, -1, -2] }, { className: 'none', targets: [ -3,-4,-5,-6,-7,-8,-9,-10,-11,-12,-13 ] }],
		//columnDefs: [{ className: 'never', targets: [ 0, 1, 7, 13, 14, 16] }],
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",
		"sAjaxSource": "../json-skp/daftar_skp_pelaporan_json.php",
		"sPaginationType": "full_numbers"
		});
		/* Click event handler */
		  
		  $('#example tbody tr').on('dblclick', function () {
			  $("#btnEdit").click();	
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
		  var anSelectedId = '';
		  var anSelectedPosition = '';	
		  
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
			  anSelectedId = element[0];
			  
		  });
		  
		  $('#btnCetakCatatan').on('click', function () {				  
			  window.top.OpenDHTML('pelaporan_catatan_perilaku_pdf.php?reqId='+anSelectedId, 'Sasaran Kerja Pegawai');
		  });
		  
		  $('#btnCetakPrestasi').on('click', function () {				  
			  window.top.OpenDHTML('pelaporan_prestasi_kerja_pdf.php?reqId='+anSelectedId, 'Sasaran Kerja Pegawai');
		  });
		  
		  $('#btnCetakPnsTubel').on('click', function () {				  
			  window.top.OpenDHTML('pelaporan_pns_tubel_pdf.php?reqId='+anSelectedId, 'Sasaran Kerja Pegawai');
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
						var jqxhr = $.get( "delete.php?reqMode=daftar_skp_pelaporan&id="+anSelectedId, function() {
							$.messager.progress('close');
						})
						.done(function() {
							$.messager.progress('close');
							oTable.fnReloadAjax("../json-skp/daftar_skp_pelaporan_json.php?reqSearch=" + $("#reqStatus").val() + "&reqId=<?=$reqId?>");
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
				  if($(this).children().text() == 'Ubah Data')
				  {
					  $("#btnEdit").click();																										
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
	
		
	function openValidasi(pegawaiId, bulanValidasi, statusValidasi)
	{
		
		if(statusValidasi == 'validasi')
			window.top.OpenDHTML('daftar_skp_penilaian_add.php?reqId='+pegawaiId+'&reqBulan='+bulanValidasi+'&reqTahun=<?=date("Y")?>', 'Sasaran Kerja Pegawai');	
		else
		{
			if(statusValidasi == 'setujui')
				alert("Pencapaian telah finalisasi.");
			else if(statusValidasi == 'proses')	
				alert("Pencapaian sedang proses entri.");
			else if(statusValidasi == 'belum')	
				alert("Pencapaian belum diisi / proses persetujuan.");
		}
	}

	function openPencapaian(pegawaiId, bulanValidasi, statusValidasi)
	{
		window.top.OpenDHTML('daftar_skp_penilaian_lihat.php?reqId='+pegawaiId+'&reqBulan='+bulanValidasi+'&reqTahun=<?=date("Y")?>', 'Sasaran Kerja Pegawai');				
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

<!-- Flex Menu -->
<link rel="stylesheet" type="text/css" href="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/flexdropdown.css" />
<script type="text/javascript" src="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/jquery.min.js"></script>
<script type="text/javascript" src="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/flexdropdown.js"></script> 

</head>

<body id="index" class="grid_2_3" style="overflow:hidden">
    <div class="full_width" style="width:100%;">
    <form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
    </form>
    <div id="header-tna">Data <span>Formulir SKP</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
        <li><a href="#" id="btnCetakCatatan" title="Cetak Catatan"> Catatan Perilaku</a></li>
        <li><a href="#" id="btnCetakPrestasi" title="Prestasi Kerja"> Prestasi Kerja</a></li>
        <li><a href="#" id="btnCetakPnsTubel" title="PNS Tubel"> PNS Tubel</a></li>  
    </ul>
    </div>
    
    </div>  
    <?php /*?><div class="bar-status">&nbsp;</div><?php */?>
    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th rowspan="2">Id</th>
            <th width="100px" rowspan="2">NIP Lama</th> 
            <th width="100px" rowspan="2">NIP Baru</th> 
            <th width="150px" rowspan="2">Nama</th> 
            <th width="50px" rowspan="2">Gol.&nbsp;Ruang</th> 
            <th width="50px" rowspan="2">Eselon</th> 
            <th width="250px" rowspan="2">Jabatan</th>
            <th width="250px" colspan="12" style="text-align:center">Sasaran Kerja Pegawai</th>   
            <th width="250px" colspan="<?=$jumlah_kategori?>" style="text-align:center">Perilaku Kerja</th>                                                                                                
        </tr>
        <tr>
            <th width="20px">I</th>                                                 
            <th width="20px">II</th>                                                 
            <th width="20px">III</th>                                                 
            <th width="20px">IV</th>                                                 
            <th width="20px">V</th>                                                 
            <th width="20px">VI</th>                                                 
            <th width="20px">VII</th>                                                 
            <th width="20px">VIII</th>                                                 
            <th width="20px">IX</th>                                                 
            <th width="20px">X</th>                                                 
            <th width="20px">XI</th>                                                 
            <th width="20px">XII</th>  
            <?
            while($kategori->nextRow())
			{
			?>          
            <th width="20px"><?=str_replace(" ", "&nbsp;", $kategori->getField("KETERANGAN"))?></th>  
            <?
			}
			?>
        </tr>
    </thead>
    </table>
    </div> <!--RIGHT CLICK EVENT -->
    
    
    <div class="vmenu">
        <div class="first_li"><span>Ubah Data</span></div>
        <div class="first_li"><span>Hapus Data</span></div>
    </div>
    
</body>
</html>