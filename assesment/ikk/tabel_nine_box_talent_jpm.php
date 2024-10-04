<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base/FormulaAssesment.php");


$reqId = httpFilterGet("reqId");
$reqPegawaiId = httpFilterGet("reqPegawaiId");
$reqMode = httpFilterGet("reqMode");
$reqKuadranId= httpFilterGet("reqKuadranId");
$reqFormulaId= httpFilterGet("reqFormulaId");
$reqKeterangan = httpFilterGet("reqKeterangan");
$reqTahun = httpFilterGet("reqTahun");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

	ini_set("memory_limit","500M");
	ini_set('max_execution_time', 520);	

$arrTahun="";
$index_arr= 0;
$penilaian_tahun= new Penilaian();
$penilaian_tahun->selectByParamsTahunPenilaian();
//echo $penilaian_tahun->errorMsg;exit;
// echo $penilaian_tahun->query;exit;
while($penilaian_tahun->nextRow())
{
	$arrTahun[$index_arr]["TAHUN"] = $penilaian_tahun->getField("TAHUN");
	$index_arr++;
}
unset($penilaian_tahun);

if($reqTahun == "")
{
	if($index_arr > 0)
		$reqTahun= $arrTahun[0]["TAHUN"];	
}

$set_formula= new FormulaAssesment();
$set_formula->selectByParams();
/*if($reqId == "")
	$reqId= $userLogin->userSatkerId;*/

$lengthSatkerId= strlen($userLogin->userSatkerId);
$tinggi = 241;
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
		
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
        var oTable = $('#example').dataTable({ "iDisplayLength": 50,bJQueryUI: true,
		/* UNTUK MENGHIDE KOLOM ID */
		"aoColumns": [ 
					   {"bVisible": false},
					   null,
					   null,
					   null,
					   null,
					   null,
					   {"bVisible": false},
					   {"bVisible": false},
					   {"bVisible": false},
					   {"bVisible": false},
					   null,
					   null
				  ],			
		"bProcessing": true,
		"bServerSide": true,
		"bFilter": false,
		//responsive: true,
		//columnDefs: [{ className: 'never', targets: [ 0, 1, -1, -2] }, { className: 'none', targets: [ -3,-4,-5,-6,-7,-8,-9,-10,-11,-12,-13 ] }],
		//columnDefs: [{ className: 'never', targets: [ 0, 1, 7, 13, 14, 16] }],
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",
		"sAjaxSource": "../json-ikk/tabel_nine_box_talent_jpm_json.php?reqId=<?=$reqId?>&reqKuadranId=<?=$reqKuadranId?>&reqTahun=<?=$reqTahun?>&reqFormulaId=<?=$reqFormulaId?>",
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
			  
			  opUrl    = '../silat/tabel_nine_box_talent_edit.php?reqId='+anSelectedPegawaiId;
			  //newWindow = window.open(opUrl, "", 'fullscreen=yes, scrollbars=auto');
			  //newWindow.focus();
			  window.top.OpenDHTML(opUrl, anSelectedPegawaiNipBaru+' - '+anSelectedPegawaiNama, '880', '495');
		  });
		  
		  $('#btnExcel').on('click', function () {
			  var reqKuadranId= reqTahun= "";
			  reqKuadranId= $("#reqKuadranId").val();
			  reqTahun= $("#reqTahun").val();
			  
			  newWindow = window.open("tabel_nine_box_talent_excel.php?reqId=<?=$reqId?>&reqKuadranId="+reqKuadranId+"&reqTahun="+reqTahun);
			  newWindow.focus();				  
		  });
		  
		  $('#btnMutasi').on('click', function () {
			  if(anSelectedData == "")
				  return false;
			  
			  opWidth= 1050;
			  opHeight= 550;
			  opUrl= 'mutasi_tabel_nine_box_talent.php?reqPegawaiId='+anSelectedPegawaiId+'&reqId='+anSelectedSatkerId;
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
						var jqxhr = $.get( "delete.php?reqMode=tabel_nine_box_talent_jpm_json&id="+anSelectedPegawaiId, function() {
							$.messager.progress('close');
						})
						.done(function() {
							$.messager.progress('close');
							oTable.fnReloadAjax("../json/tabel_nine_box_talent_jpm_json.php?reqSearch=" + $("#reqStatus").val() + "&reqId=<?=$reqId?>");
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
		  
		  $("#reqPencarian").keyup(function(e) { 
			var code = e.which;
			if(code==13)
			{
				  var reqStatusPeg= reqPencarian= "";
			  
				  reqStatusPeg= $("#reqStatusPeg").val();
				  reqPencarian= $("#reqPencarian").val();
				  
				  oTable.fnReloadAjax("../json-ikk/tabel_nine_box_talent_jpm_json.php?reqStatusPeg="+reqStatusPeg+ "&reqId=<?=$reqId?>&sSearch="+reqPencarian);
			}
		  });
		  
		  $("#reqTahun, #reqKuadranId, #reqFormulaId").change(function() { 
		  	  var reqTahun= reqKuadranId= reqPencarian= reqFormulaId= "";
			  
			  reqTahun= $("#reqTahun").val();
			  reqKuadranId= $("#reqKuadranId").val();
			  reqPencarian= $("#reqPencarian").val();
			  reqFormulaId= $("#reqFormulaId").val();

			  
			  oTable.fnReloadAjax("../json-ikk/tabel_nine_box_talent_jpm_json.php?reqTahun="+reqTahun+"&reqKuadranId="+reqKuadranId+"&reqFormulaId="+reqFormulaId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian);
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
<!-- <script type="text/javascript" src="css/dropdowntabs.js"></script> -->
</head>

<body id="index" class="grid_2_3" style="overflow:hidden">
    <div class="full_width" style="width:100%;">
    <form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
    </form>
    <div id="header-tna">Data <span>Talent Pool</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
          <li>
          	<a href="#" title="Cetak" id="btnExcel" style="display:none"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak</a>
          </li>
    </ul>
    </div>
    
    </div>  
    
    <div class="bar-status">
    <?
	if($reqKuadranId == "")
	{
    ?>
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
    &nbsp;&nbsp;Kuadran&nbsp;&nbsp; :&nbsp;
    <select id="reqKuadranId" name="reqKuadranId">
    	<option selected="selected" value="">Semua</option> 
        <option value="11">I. Kinerja dibawah ekspektasi dan JPM rendah</option>
        <option value="12">II. Kinerja sesuai ekspektasi dan JPM rendah</option>
        <option value="21">III. Kinerja dibawah ekspektasi dan JPM menengah</option>
        <option value="13">IV. Kinerja diatas ekspektasi dan JPM rendah</option>
        <option value="22">V. Kinerja sesuai ekspektasi dan JPM menengah</option>
        <option value="31">VI. Kinerja dibawah ekspektasi dan JPM tinggi</option>
        <option value="23">VII. Kinerja diatas ekspektasi dan JPM menengah</option>
        <option value="32">VIII. Kinerja sesuai ekspektasi dan JPM tinggi</option>
        <option value="33">IX. Kinerja diatas ekspektasi dan JPM tinggi</option>
    </select>
    <?
	}
	else
	{
    ?>
    <input id="reqKuadranId" name="reqKuadranId" value="<?=$reqKuadranId?>" type="hidden" />
<!--     <input id="reqFormulaId" name="reqFormulaId" value="<?=$reqFormulaId?>" type="text" />
 -->    <input name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" type="hidden" />

    Tahun <?=$reqTahun?>
    <?
	}
    ?>

    Formula
    <select id="reqFormulaId" name="reqFormulaId">
    	<option selected="selected" value="">Semua</option>
    	<?
    	while($set_formula->nextRow())
    	{
    		?>
    		<option value="<?=$set_formula->getField("FORMULA_ID")?>"><?=$set_formula->getField("FORMULA")?></option>
    		<?
    	}
    	?>
    </select>
    </div>
    
    
    
	<div style="position: relative; margin-bottom:-30px; margin-top:8px; float:right; z-index:9999; font-size:12px;">
        Pencarian :
        <input type="text" id="reqPencarian" style="width:155px" />
    </div>
    
    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th rowspan="2" style="width:10px;">Id</th>
            <th rowspan="2" style="width:10px;"><div align="center">No</div></th>
            <th rowspan="2" style="width:150px;"><div align="center">Nama</div></th>
            <th rowspan="2" style="width:200px;"><div align="center">Jabatan</div></th>
            <th colspan="3" style="width:10px;"><div align="center">Hasil Assesment Center</div></th>
            <th rowspan="2" style="width:10px;"><div align="center">JPM</div></th>
            <th rowspan="2" style="width:10px;"><div align="center">IKK</div></th>
            <th rowspan="2" style="width:10px;"><div align="center">SKP</div></th>
            <th rowspan="2" style="width:10px;"><div align="center">Kuadran</div></th>
            <th rowspan="2" style="width:100px;"><div align="center">Keterangan</div></th>
        </tr>
        <tr>
          <th style="width:50px;"><div align="center">SKP</div></th>
          <th style="width:50px;"><div align="center">JPM</div></th>
          <th style="width:50px;"><div align="center">General</div></th>
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