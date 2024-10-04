<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalAsesorPotensiPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");

$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
$tempTanggalTesAkhir= getFormattedDateTime($set->getField('TANGGAL_TES_AKHIR'), false);
// $tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), 'date');
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');
$reqStatusValid= $set->getField('STATUS_VALID');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$tempStatusValidasi= $reqStatusValid;
$reqTipe= $set->getField("TIPE");

$statement= " AND JADWAL_TES_ID = ".$reqId;
$set_detil= new JadwalAsesorPotensiPegawai();
$index_loop= $set_detil->getCountByParams(array(), $statement);
// echo $set_detil->query;exit();

$arrData= array(
	array("label"=>"NIP", "width"=>"", "display"=>"100")
	, array("label"=>"NAMA", "width"=>"", "display"=>"100")
	, array("label"=>"JABATAN", "width"=>"", "display"=>"100")
	, array("label"=>"SATKER", "width"=>"", "display"=>"100")
	, array("label"=>"File Test", "width"=>"", "display"=>"")
);

$tinggi = 285;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

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
	var oTable;
	var arrChecked = [];

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
        oTable = $('#example').dataTable({ "iDisplayLength": 50,bJQueryUI: true,
		/* UNTUK MENGHIDE KOLOM ID */
		"aoColumns": [ 
		<?
		for($col=0; $col<count($arrData); $col++)
		{
			if($col == 0){}
			else
				echo ",";

			if($arrData[$col]["display"] == "1")
				echo "{'bVisible': false}";
			else
				echo "null";
		}
		?>
		],			
		"bProcessing": true,
		"bServerSide": true,
		"bFilter": false,
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",					  
		"sAjaxSource": "../json-pengaturan/master_jadwal_add_file_rekap_json.php?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>",
		"sPaginationType": "full_numbers"
		});
		/* Click event handler */

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
		  var anSelectedRowId = anSelectedRowDetilId= '';
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
			  anSelectedRowId = element[0];//element[element.length-1];
			  anSelectedRowDetilId= element[element.length-2];
			  anSelectedPegawaiId = element[0];
			  anSelectedPegawaiNipBaru = element[3];
			  anSelectedPegawaiNama = element[4];
		  });
		  
		  $('#example tbody').on( 'dblclick', 'tr', function () {
			  if(anSelectedData == "")
				  return false;
			  $("#btnUbahData").click();	
		  });
		  
		  $('#btnCari').on('click', function () {
			  	var reqPegawaiId= "";
			  	reqPegawaiId= $("#reqPegawaiId").val();
                reqCariFilter= $("#reqCariFilter").val();

			  	oTable.fnReloadAjax("../json-pengaturan/master_jadwal_add_file_rekap_json.php?reqId=<?=$reqId?>&reqTipe=<?=$reqTipe?>&reqCheckId="+reqPegawaiId+"&sSearch="+reqCariFilter);
		  });

		  $("#reqCariFilter").keyup(function(e) {
		  	var code = e.which;
		  	if(code==13)
		  	{
		  		setCariInfo();
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
		
	function setCariInfo()
    {
        $(document).ready( function () {
            $("#btnCari").click();
        });
    }

    function opendownload(pageurl)
	{
		newWindow = window.open(pageurl);
		newWindow.focus();
	}
</script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />

<style>
#example td:nth-child(5) {
    text-align : center;
    *font-weight: bold;
	*color:#F00 !important
}
</style>
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; width:100%">
	<div id="header-tna-detil">Data <span>Peserta Ujian</span></div>
    <table class="table_list" cellspacing="1" width="100%">
        <tr>
            <td width="200px">Formula</td>
            <td width="2px">:</td>
            <td><label id="reqFormulaEselon"><?=$tempFormulaEselon?></label></td>
        </tr>
        <tr>
            <td>Tanggal Tes</td>
            <td>:</td>
            <td>
            	<?=$tempTanggalTes?>
            </td>
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
        <tr>
            <td>Total Peserta</td>
            <td>:</td>
            <td>
            	<label id="reqInfoTotalPeserta"><?=$index_loop?></label>
    			<input type="hidden" id="reqPegawaiId" name="reqPegawaiId" />
            </td>
        </tr>
        <!-- <tr>
            <td>Batas Peserta</td>
            <td>:</td>
            <td>
            	<td><label id="reqInfoTotalPeserta"><?=$reqBatasPegawai?></label></td>
            </td>
        </tr> -->
        <?
		if($reqId == ""){}
		else
		{
        ?>
        <tr>
            <td>
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                <input type="hidden" name="reqMode" value="insert" />
                <?
				if($tempStatusValidasi == "1"){}
				else
				{
				?>
                <!-- <input type="submit" name="" value="Simpan" /> -->
                <?
				}
                ?>
            </td>
        </tr>
        <?
		}
        ?>
    </table>
    </div>

    <div id="bar-status">
	    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif); margin-top: -2px !important; border: none !important;" >
	    <ul>
	    	<a href="#" id="btnCari" style="display: none;" title="Cari"></a>

	    	<?
			if($tempStatusValidasi == "1"){}
			else
			{
			?>
	        <!-- <li><a style="background: inherit !important;" href="#" title="Tambah" id="btnTambahData"><img src="../WEB/images/icn_add.gif" style="width: 10px" />&nbsp;Tambah</a></li>
	        <li><a style="background: inherit !important;" href="#" title="Hapus" id="btnHapusData"><img src="../WEB/images/delete-icon.png" style="width: 10px" />&nbsp;Hapus</a></li> -->
	        <?
			}
	        ?>
	    </ul>
	    </div>

	    <div style="position: relative; float:right; z-index:9999; font-size:12px; margin-top: -33px; margin-right: -100px">
    		Pencarian <input type="text" id="reqCariFilter" style="width:150%" />
    	</div>
    </div>

    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
        	<?
        	for($i=0; $i < count($arrData); $i++)
        	{
        		$infolabel= $arrData[$i]["label"];
        		$infowidth= $arrData[$i]["width"];
        	?>
        		<th class="th_like" style="<?=$style?>;" width="<?=$infowidth?>px"><?=$infolabel?></th>
        	<?
        	}
        	?>
        </tr>
    </thead>
    </table>
    </div> <!--RIGHT CLICK EVENT -->

</div>
<script>
//$('input[id^="reqAnakNama"]').keyup(function(e) {
$('#reqBatasPegawai').bind('keyup paste', function(){
	this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
</body>
</html>