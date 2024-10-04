<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

$reqId= httpFilterGet("reqId");
$reqFormulaSuksesiId= httpFilterGet("reqFormulaSuksesiId");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	

$lengthSatkerId= strlen($userLogin->userSatkerId);
$tinggi = 195;

$arrData= array("Pilih", "Nama Pegawai", "NIP", "Gol.Ruang", "Eselon", "Jabatan");

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
	
	var oTable;
	var arrChecked = [];
    $(document).ready( function () {
		
        var id = -1;//simulation of id
		$(window).resize(function() {
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
        oTable = $('#example').dataTable({ "iDisplayLength": 50,bJQueryUI: true,
		/* UNTUK MENGHIDE KOLOM ID */
		"aoColumns": [ 
		<?
        for($i=0; $i < count($arrData); $i++)
        {
            if($i == 0){}
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
		//columnDefs: [{ className: 'never', targets: [ -1, -2] }],
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",					  
		"sAjaxSource": "../json-suksesi/formula_jabatan_target_add_list_data_json.php?reqId=<?=$reqId?>&reqFormulaSuksesiId=<?=$reqFormulaSuksesiId?>",
		"sPaginationType": "full_numbers",
		"fnDrawCallback": function( oSettings ) {
			setKlikCheck();
		}
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
			  anSelectedId = element[0];
		  });
		  
		  $("#reqPencarian").keyup(function(e) { 
			var code = e.which;
			if(code==13)
			{
				setCariInfo();
			}
		  });

		  $('#btnCari').on('click', function () {
		  	var reqStatusPeg= reqPencarian= reqPegawaiId= "";

		  	reqStatusPeg= $("#reqStatusPeg").val();
		  	reqPencarian= $("#reqPencarian").val();
			reqPegawaiId= $("#reqPegawaiId").val();


		  	oTable.fnReloadAjax("../json-suksesi/formula_jabatan_target_add_list_data_json.php?reqId=<?=$reqId?>&reqFormulaSuksesiId=<?=$reqFormulaSuksesiId?>&reqCheckId="+reqPegawaiId+"&reqCari="+reqPencarian);
		  });

		  $('#btnHapusData').on('click', function () {
			  	var reqPegawaiId= "";
			  	reqPegawaiId= $("#reqPegawaiId").val();

			  	if(reqPegawaiId == "")
			  	{
			  		$.messager.alert('Info', "Pilih data terlebih dahulu", 'info');
			  		return false;
			  	}

			  	$.messager.confirm('Konfirmasi',"Apakah anda yakin simpan, data terpilih?",function(r){
			  		if (r)
			  		{
			  			$.getJSON("../json-suksesi/formula_jabatan_target_add_list_data_add.php?reqId=<?=$reqId?>&reqFormulaSuksesiId=<?=$reqFormulaSuksesiId?>&reqPegawaiId="+reqPegawaiId,
		  				function(data){
		  					// setCariInfo();
		  					parent.setCariInfo();
			  				parent.divwin.close();
		  					$("#reqPegawaiId").val("");
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
		  
		  function getChecked()
			{
				var data = "";
				var validasi = 0;
				for(i=0;i<arrChecked.length; i++)
				{
					value = arrChecked[i];
					arrValue = value.split("-");
					
					if(data == "")
					data = arrValue[0];
					else
					data = data + "," + arrValue[0];
				}
				return data;
			}
			
			function setCheckedAll()
			{
				if($("#reqCheckAll").prop('checked')) {
				   // do what you need here
				   //alert("Checked");
				   $('input[id^="reqPilihCheck"]').each(function(){
						var id= $(this).attr('id');
						id= id.replace("reqPilihCheck", "")
						$(this).prop('checked', true);
						arrChecked.push($(this).val());
				   });
				}
				else {
				   // do what you need here
				   //alert("Unchecked");
				   $('input[id^="reqPilihCheck"]').each(function(){
						var id= $(this).attr('id');
						id= id.replace("reqPilihCheck", "")
						$(this).prop('checked', false);
				   });
				   
				   arrChecked = [];
				}
			}
			
			$("#reqCheckAll").change(function() {
				setCheckedAll();
			});
			
	} );

		function setCariInfo()
        {
            $(document).ready( function () {
                $("#btnCari").click();
            });
        }

		var transaksiDetilDonaturLaysosIdArr= [];
        function setKlikCheck()
        {
          reqDonaturLaysosId= String($("#reqPegawaiId").val());
          reqArrDonaturLaysosId= reqDonaturLaysosId.split(',');

          var i= "";
          if(reqDonaturLaysosId == ""){}
          else
          {
            transaksiDetilDonaturLaysosIdArr= reqArrDonaturLaysosId;
            i= transaksiDetilDonaturLaysosIdArr.length - 1;
            
            i= transaksiDetilDonaturLaysosIdArr.length;
          }
          
          reqPilihCheck= reqPilihCheckVal= reqNominalBantuan= reqNominalBantuanVal= reqCatatan= reqCatatanVal= "";
          $('input[id^="reqPilihCheck"]:checkbox:checked').each(function(i){
            reqPilihCheck= $(this).val();
            var id= $(this).attr('id');
            id= id.replace("reqPilihCheck", "");

            if(reqPilihCheckVal == "")
            {
              reqPilihCheckVal= reqPilihCheck;
              reqNominalBantuanVal= reqNominalBantuan;
              reqCatatanVal= reqCatatan;
            }
            else
            {
              reqPilihCheckVal= reqPilihCheckVal+","+reqPilihCheck;
              reqNominalBantuanVal= reqNominalBantuanVal+","+reqNominalBantuan;
              reqCatatanVal= reqCatatanVal+"||"+reqCatatan;
            }
            
            var elementRow= transaksiDetilDonaturLaysosIdArr.indexOf(reqPilihCheck);
            //alert(elementRow);
            if(elementRow == -1)
            {
              i= transaksiDetilDonaturLaysosIdArr.length;

              transaksiDetilDonaturLaysosIdArr[i]= reqPilihCheck;
            }
            
            });
          
          $('input[id^="reqPilihCheck"]:checkbox:not(:checked)').each(function(i){
            reqPilihCheck= $(this).val();
            var id= $(this).attr('id');
            id= id.replace("reqPilihCheck", "");
            
            var elementRow= transaksiDetilDonaturLaysosIdArr.indexOf(reqPilihCheck);
            //alert(reqPilihCheck+"-"+elementRow);
            if(parseInt(elementRow) >= 0)
            {
              transaksiDetilDonaturLaysosIdArr.splice(elementRow, 1);
            }
          });
          
          //collect data ke field
          reqPilihCheck= reqPilihCheckVal= reqNominalBantuan= reqNominalBantuanVal= reqCatatan= reqCatatanVal= "";

          for(var i=0; i<transaksiDetilDonaturLaysosIdArr.length; i++) 
          {
            if(reqPilihCheckVal == "")
            {
              reqPilihCheckVal= transaksiDetilDonaturLaysosIdArr[i];
            }
            else
            {
              reqPilihCheckVal= reqPilihCheckVal+","+transaksiDetilDonaturLaysosIdArr[i];
            }
          }

          $("#reqPegawaiId").val(reqPilihCheckVal);
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
<script type="text/javascript" src="css/dropdowntabs.js"></script>
</head>

<body id="index" class="grid_2_3" style="overflow:hidden">
    <div class="full_width" style="width:100%;">
    <form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
    </form>
    <div id="header-tna"><span>Pegawai</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
	    <a href="#" id="btnCari" style="display: none;" title="Cari"></a>
    	<li><a style="background: inherit !important;" href="#" title="Hapus" id="btnHapusData"><img src="../WEB/images/icon_centang.png" style="width: 10px" />&nbsp;Simpan</a></li>
    </ul>
    </div>
      
    </div>  
    
    <div id="bar-status">
    	<!-- <input type="checkbox" id="reqCheckAll"/>&nbsp;Pilih Semua &nbsp;&nbsp; -->
    </div>
    <div style="position: relative; margin-bottom:-40px; margin-top:6px; margin-right:8px; float:right; z-index:9999; font-size:12px;">
        Pencarian :
        <input type="text" id="reqPencarian" style="width:255px" />
        <input type="hidden" id="reqPegawaiId" name="reqPegawaiId" />
    </div>
    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <?
        	for($i=0; $i < count($arrData); $i++)
        	{
        		$width= "";
        		if($i == 0 || $i == 3)
        			$width= "10";
        		elseif($i == 2)
        			$width= "10";
        		elseif($i == 1 || $i == 4)
        			$width= "100";
        		?>
        		<th width="<?=$width?>px"><?=$arrData[$i]?></th>
        		<?
        	}
        	?>
        </tr>
    </thead>
    </table>
    </div> <!--RIGHT CLICK EVENT -->
    
</body>
</html>