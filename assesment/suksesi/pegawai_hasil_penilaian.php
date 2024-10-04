<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/FormulaSuksesi.php");
include_once("../WEB/classes/base/FormulaJabatanTarget.php");
include_once("../WEB/classes/base/FormulaFaktor.php");

$reqId = httpFilterGet("reqId");
$reqPegawaiId = httpFilterGet("reqPegawaiId");
$reqFormulaJabatanTargetId = httpFilterGet("reqFormulaJabatanTargetId");
$reqFormulaUnsurId = httpFilterGet("reqFormulaUnsurId");
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

$set_eselon= new FormulaJabatanTarget();
$set_eselon->selectByParams(array(), -1,-1, "", "ORDER BY A.NAMA");
// echo $set_eselon->query;exit;

$lengthSatkerId= strlen($userLogin->userSatkerId);
$tinggi = 240;
if(!empty($reqFormulaJabatanTargetId))
	$tinggi = 270;

$arrkolomdata= array(
	array("label"=>"No", "width"=>"50px")
    , array("label"=>"Nama", "width"=>"220px")
    , array("label"=>"NIP", "width"=>"120px")
    , array("label"=>"Jabatan", "width"=>"")
    , array("label"=>"Instansi", "width"=>"150px")
);

$totalstandart= 0;
if(!empty($reqFormulaJabatanTargetId))
{
	$set_info= new FormulaJabatanTarget();
	$set_info->selectByParams(array(), -1, -1, " AND A.FORMULA_JABATAN_TARGET_ID = ".$reqFormulaJabatanTargetId);
	$set_info->firstRow();
	$reqFormulaUnsurId= $set_info->getField("FORMULA_SUKSESI_ID");
	unset($set_info);

	$jumlahdetil= 0;

	$setdetil= new FormulaFaktor();
	$setdetil->selectByParams(array(), -1, -1, " AND A.FORMULA_ID = ".$reqFormulaUnsurId);
	$setdetil->firstRow();
	$infoassment= $setdetil->getField("ASSESMENT");
	if($infoassment > 0)
	{
		array_push($arrkolomdata,     
			array("label"=>"JPM", "width"=>"100px")
		);	
		$jumlahdetil++;
	}

	$setdetil= new FormulaSuksesi();
	$setdetil->selectByParamsUnsurFormula(array(), -1, -1, " AND A.FORMULA_UNSUR_ID = ".$reqFormulaUnsurId);
	// echo $setdetil->query;exit();
	while ($setdetil->nextRow()) 
	{
		array_push($arrkolomdata,     
			array("label"=>$setdetil->getField("NAMA"), "width"=>"100px")
			// array("label"=>$setdetil->getField("NAMA")."<br/>".$setdetil->getField("NILAI_STANDAR"), "width"=>"100px")
		);
		$totalstandart += $setdetil->getField("NILAI_STANDAR");
		$jumlahdetil++;
	}
}
// echo $jumlahdetil;exit();
array_push($arrkolomdata,
	array("label"=>"Total", "width"=>"100px")
);
// print_r($arrkolomdata);exit;
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
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",
		"sAjaxSource": "../json-suksesi/pegawai_hasil_penilaian_json.php?reqId=<?=$reqId?>&reqFormulaJabatanTargetId=<?=$reqFormulaJabatanTargetId?>&reqFormulaUnsurId=<?=$reqFormulaUnsurId?>",
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
			  anSelectedPegawaiId =  element[element.length-1];
			  anSelectedPegawaiNipBaru = element[3];
			  anSelectedPegawaiNama = element[4];
			  
		  });

		  $('#cetakexcel').on('click', function () {
		  	  var reqFormulaJabatanTargetId= reqPencarian= "";
			  reqFormulaJabatanTargetId= $("#reqFormulaJabatanTargetId").val();
			  reqPencarian= $("#reqPencarian").val();

			  if(reqFormulaJabatanTargetId == "")
			  {
			  	$.messager.alert('Info', "Pilih data terlebih dahulu", 'info');
			  	return false;
			  }

			  newWindow = window.open("hasil_penilaian_excel.php?reqId="+anSelectedPegawaiId+"&reqFormulaJabatanTargetId="+reqFormulaJabatanTargetId+"&sSearch="+reqPencarian);
			  newWindow.focus();
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
		  
		  $("#reqPencarian").keyup(function(e) { 
			var code = e.which;
			if(code==13)
			{
				  var reqStatusPeg= reqTahun= reqFormulaUnsurId= reqFormulaJabatanTargetId= reqPencarian= "";
				  // reqStatusPeg= $("#reqStatusPeg").val();
				  // reqTahun= $("#reqTahun").val();
				  reqFormulaUnsurId= $("#reqFormulaUnsurId").val();
				  reqFormulaJabatanTargetId= $("#reqFormulaJabatanTargetId").val();
				  reqPencarian= $("#reqPencarian").val();
				  //setFooter();
				  oTable.fnReloadAjax("../json-suksesi/pegawai_hasil_penilaian_json.php?reqFormulaJabatanTargetId="+reqFormulaJabatanTargetId+"&reqFormulaUnsurId="+reqFormulaUnsurId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian);
			}
		  });

		  $("#reqFormulaJabatanTargetId").change(function() { 
		  	  var reqStatusPeg= reqTahun= reqFormulaUnsurId= reqFormulaJabatanTargetId= reqPencarian= "";
			  
			  // reqStatusPeg= $("#reqStatusPeg").val();
			  // reqTahun= $("#reqTahun").val();
			  reqFormulaUnsurId= $("#reqFormulaUnsurId").val();
			  reqFormulaJabatanTargetId= $("#reqFormulaJabatanTargetId").val();
			  reqPencarian= $("#reqPencarian").val();


			  //setFooter();
			  document.location.href= "pegawai_hasil_penilaian.php?reqFormulaJabatanTargetId="+reqFormulaJabatanTargetId+"&reqFormulaUnsurId="+reqFormulaUnsurId+"&reqId=<?=$reqId?>&sSearch="+reqPencarian;
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
		var reqStatusPeg= reqTahun= reqFormulaUnsurId= reqPencarian= "";
			  
		reqStatusPeg= $("#reqStatusPeg").val();
		reqTahun= $("#reqTahun").val();
		reqFormulaUnsurId= $("#reqFormulaUnsurId").val();
		reqPencarian= $("#reqPencarian").val();
				 
		var s_url= "../json-ikk/jpm_ikk_total_json.php?reqId=<?=$reqId?>&reqTahun="+reqTahun+"&reqFormulaUnsurId="+reqFormulaUnsurId;
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
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
          <li>
            <a href="#" title="Cetak" id="cetakexcel"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak</a>
          </li>
           <!-- <li>
            <a href="#" title="CetakRekap" id="cetakrekap"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Cetak Rekap</a>
          </li> -->
   </ul>
   </div>

    </div>  
    <div class="bar-status">  	
    <input type="hidden" id="reqStatusPeg" name="reqStatusPeg" value="" />
    <input type="hidden" id="reqFormulaUnsurId" name="reqFormulaUnsurId" value="<?=$reqFormulaUnsurId?>" />
    Formula Jabatan
    <select id="reqFormulaJabatanTargetId" name="reqFormulaJabatanTargetId" style="width: 250px">
    	<option selected="selected" value="">Semua</option>
        <?
		while($set_eselon->nextRow())
		{
        ?>
        <option value="<?=$set_eselon->getField("FORMULA_JABATAN_TARGET_ID")?>" <? if($reqFormulaJabatanTargetId == $set_eselon->getField("FORMULA_JABATAN_TARGET_ID")) echo "selected";?>><?=$set_eselon->getField("NAMA")?></option>
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
        	if(!empty($reqFormulaUnsurId) && $jumlahdetil > 0)
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
				<th style="text-align: center;" colspan="<?=$jumlahdetil?>">Nilai</th>
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
        if(!empty($reqFormulaUnsurId) && $jumlahdetil > 0)
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