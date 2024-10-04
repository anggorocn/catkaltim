<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Training.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

	ini_set("memory_limit","500M");
	ini_set('max_execution_time', 520);	
	
$tinggi = 213;

$set_tahun= new Training();
$set_tahun->selectByParamsCombo(array(), -1,-1, "", "TAHUN");

$arrReqFilter= array("reqTrainingId");
$arrReqJson= array("training_option_json");
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
<script type="text/javascript" language="javascript" src="../WEB/lib/media/js/jquery.dataTables.rowGrouping.js"></script>

<script type="text/javascript">
$(function() {
	
	$("#reqTahun").change(function() {
		setTahun();
	});
	
});

function setTahun()
{
	var reqTahun= $("#reqTahun").val();
	
	<?
	for($indexReqFilter=0; $indexReqFilter < count($arrReqFilter); $indexReqFilter++)
	{
	?>
		$("#<?=$arrReqFilter[$indexReqFilter]?> :selected").remove(); 
		$("#<?=$arrReqFilter[$indexReqFilter]?> option").remove();
		
		var s_url= "../json-silat/<?=$arrReqJson[$indexReqFilter]?>.php?reqTahun="+reqTahun;
		var request = $.get(s_url);
		request.done(function(dataJson)
		{
			var data= JSON.parse(dataJson);
			for(i=0;i<data.arrID.length; i++)
			{
				valId= data.arrID[i]; valNama= data.arrNama[i];
				$("<option value='" + valId + "'>" + valNama + "</option>").appendTo("#<?=$arrReqFilter[$indexReqFilter]?>");
			}
		});
	<?
	}
	?>
	//$("#reqSplitKategoriId"+id+" :selected").remove(); 
	//$("#reqSplitKategoriId"+id+" option").remove();
	
	setCariInfo()
}
</script>

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
					   	null,
						null,
						null
				  ],			
		"bProcessing": true,
		"bServerSide": true,
		//"bFilter": false,
		//responsive: true,
		//columnDefs: [{ className: 'never', targets: [ 0, 1, -1, -2] }, { className: 'none', targets: [ -3,-4,-5,-6,-7,-8,-9,-10,-11,-12,-13 ] }],
		//columnDefs: [{ className: 'never', targets: [ -1, -2] }],
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",					  
		"sAjaxSource": "../json-pengaturan/training_json.php",
		"sPaginationType": "full_numbers"
		}).rowGrouping({bExpandableGrouping: true});
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
		  var anSelectedKodeUnker= anSelectedAtributId = '';
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
			  anSelectedId= element[element.length-1];
		  });
		  
		  $("#btnTambah,#btnEdit").on("click", function () {
			  var tempId= $(this).attr('id');
			  if(tempId == "btnTambah")
			  {
				anSelectedId= "";
				tempLihat= 1;
			  }
			  
			  opUrl= 'training_add.php?reqId='+anSelectedId;
			  window.top.OpenDHTML(opUrl, "Master Training", '880', '495');
		  });
		  
		  $("#btnCari").on("click", function () {
			var reqTahun= reqTrainingId= "";
			reqTrainingId= $("#reqTrainingId").val();
			reqTahun= $("#reqTahun").val();
			oTable.fnReloadAjax("../json-pengaturan/training_json.php?reqTrainingId="+reqTrainingId+"&reqTahun="+reqTahun);
		  });

		  $("#reqTrainingId,#reqTahun").change(function() { 
		  	setCariInfo();
		  });
		  
		  $('#btnExcel').on('click', function () {
			  //if(anSelectedData == "")
				  //return false;
			  
			  var url= 'diklat_analisa_kompetensi_bendel_excel.php?reqAtributId='+anSelectedAtributId+"&reqId="+anSelectedKodeUnker;
			  newWindow = window.open(url, 'Cetak');
			  newWindow.focus();
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
					  $("#btnEdit").click();																										
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
	
	function setCariInfo()
	{
		$(document).ready( function () {
			$("#btnCari").click();			
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
    <div id="header-tna">Jenis <span>Diklat</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
          <li>
          	<a href="#" style="display:none" id="btnCari" title="Cari">Cari</a>
            <li><a href="#" title="Tambah" id="btnTambah"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Tambah</a></li>
          	<li><a href="#" title="Ubah" id="btnEdit"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Ubah</a></li>
            <?php /*?><li><a href="#" title="Cetak" rel="dropmenu2_b"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak</a></li><?php */?>
          </li>
    </ul>
    </div>
      
    <!--2nd drop down menu -->
    <div id="dropmenu2_b" class="dropmenudiv_b" style="width: 250px; z-index:99999">
    	<a href="#" title="Excel" id="btnExcel">Excel</a>
        <?php /*?><a href="#" title="Pdf" id="btnPdf">Pdf</a><?php */?>
    </div>
    
    <script type="text/javascript">
    //SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
    tabdropdown.init("bluemenu")
    </script>
    
    </div>  
    
    <div id="bar-status">
    	Tahun : 
        <select name="reqTahun" id="reqTahun">
        <option value="" <? if($reqTahun == "") echo "selected";?>>All</option>
        <?
		$i=0;
		while($set_tahun->nextRow())
        {
        ?>
        <option value="<?=$set_tahun->getField("TAHUN")?>" <? if($reqTahun == $set_tahun->getField("TAHUN")) { ?> selected <? } ?>><?=$set_tahun->getField("TAHUN")?></option>
        <?
		$i++;
        }
        ?>
        </select>
        Training :
        <select id="reqTrainingId" style="width:400px">
        <option value="">All</option>
        </select>
    </div>
    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th width="50px">group</th>
            <th width="50px">Atribut</th>
            <th width="50px">Tahun</th>
        </tr>
    </thead>
    </table>
    </div> <!--RIGHT CLICK EVENT -->
    
    
    <div class="vmenu" style="display:none">
        <div class="first_li"><span>Detail Data</span></div>
    </div>
</body>
</html>