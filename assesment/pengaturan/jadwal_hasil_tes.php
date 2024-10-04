<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);	

$reqId= httpFilterGet("reqId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");

$statement= " AND TIPE_UJIAN_ID = ".$reqTipeUjianId;
$set= new TipeUjian();
$set->selectByParams(array(), -1,-1, $statement);
// echo $set->query;exit;
$set->firstRow();
$tempNamaTipe= $set->getField("TIPE");
unset($set);

$parentId= "";
if($reqTipeUjianId == 1)
  $parentId= "01";
elseif($reqTipeUjianId == 2)
  $parentId= "02";
elseif($reqTipeUjianId == 18)
  $parentId= "10";
elseif($reqTipeUjianId == 29)
  $parentId= "12";


if($parentId == ""){}
else
{
  $statement= " AND PARENT_ID = '".$parentId."'";
  $set_detil= new TipeUjian();
  $set_detil->selectByParams(array(), -1,-1, $statement);

  $statement= " AND PARENT_ID = '".$parentId."'";
  $set_detil1= new TipeUjian();
  $set_detil1->selectByParams(array(), -1,-1, $statement);
}

if($reqTipeUjianId == "7")
$tinggi = 158;
else
$tinggi = 130;

if($reqTipeUjianId == "7")
    $arrData= array(
        "ID",  "NO Urut","NIP", "Nama"
        , "G", "L", "I", "T", "V", "S", "R", "D", "C", "E", "TOTAL_1"
        , "N", "A", "P", "X", "B", "O", "Z", "K", "F", "W", "TOTAL_2"
        , "Total", "Rata-rata"
    );
  // , "Uraian"
    // $arrData= array("NIP", "Nama", "W", "F", "K", "Z", "O", "B", "X", "P", "A", "N", "G", "L", "I", "T", "V", "S", "R", "D", "C", "E");
elseif($reqTipeUjianId == "17")
    $arrData= array(
        "ID",  "NIP", "Nama"
        , "Ach", "Def", "Ord", "Exh", "Aut", "Aff", "Int", "Suc", "Dom", "Aba", "Nur", "Chg", "End", "Het", "Agg"
        , "Total", "Cons"
    );
    // $arrData= array("NIP", "Nama", "W", "F", "K", "Z", "O", "B", "X", "P", "A", "N", "G", "L", "I", "T", "V", "S", "R", "D", "C", "E");
elseif($reqTipeUjianId == "16")
    $arrData= array("ID",  "NIP", "Nama", "Total", "Rata-rata", "Titik Tertinggi", "Titik Terendah", "Titik Maksimal", "Titik Minimal", "Jumlah Benar", "Jumlah Salah", "Jumlah Terloncati", "Y1", "Y2", "Y3", "Y4", "Y5", "Y6", "Y7", "Y8", "Y9", "Y10", "Y11", "Y12", "Y13", "Y14", "Y15", "Y16", "Y17", "Y18", "Y19", "Y20", "Y21", "Y22", "Y23", "Y24", "Y25", "Y26", "Y27", "Y28", "Y29", "Y30", "Y31", "Y32", "Y33", "Y34", "Y35", "Y36", "Y37", "Y38", "Y39", "Y40", "Y41", "Y42", "Y43", "Y44", "Y45", "Y46", "Y47", "Y48", "Y49", "Y50");
elseif($reqTipeUjianId == "43")
	$arrData= array("ID",  "NIP", "Nama", "Range I", "Range II", "Range III", "Range I", "Range II", "Range III", "Puncak", "List", "Puncak", "List", "RS", "SS", "Kesimpulan", "RS", "SS", "Kesimpulan");
elseif($reqTipeUjianId == "18")
    $arrData= array("ID",  "No Urut","NIP", "Nama", "SE", "WA", "AN", "GE", "ME", "RA", "ZR", "FA", "WU", "Jumlah", "IQ");
elseif($reqTipeUjianId == "28")
{
    $arrData= array("ID",  "NIP", "Nama");
    $jumlahdata= count($arrData);
    for($colomx= 1; $colomx <= 20; $colomx ++)
    {
      $arrData[$jumlahdata]= $colomx;
      $jumlahdata++;
    }

}
elseif($reqTipeUjianId == "40")
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "MD", "A", "B", "C", "E", "F", "G", "H", "I", "L", "M", "N", "O", "Q1", "Q2", "Q3", "Q4");
elseif($reqTipeUjianId == "66")
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "Plus (+)", "Negatip (-)");

elseif($reqTipeUjianId == "41")
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "I","E","S","N","T","F","J","P", "Tipe Kepribadian");

elseif($reqTipeUjianId == "42")
    $arrData= array("ID", "No Urut", "NIP", "Nama", "Line 1", "Line 2", "Line 3");

elseif($reqTipeUjianId == "45")
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "Agreeableness", "Conscientiousness", "Extraversion", "Neuroticism", "Openness");

elseif($reqTipeUjianId == "1" || $reqTipeUjianId == "2")
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "Skor Sub Tes 1", "Skor Sub Tes 2", "Skor Sub Tes 3", "Skor Sub Tes 4", "Raw Skor", "Nilai");
    // $arrData= array( "NIP", "Nama", "Raw Skor", "Nilai");
    // $arrData= array("NIP", "Nama", "Raw Skor", "Nilai", "Kesimpulan");
elseif($reqTipeUjianId == "4" || $reqTipeUjianId == "46" || $reqTipeUjianId == "50" || $reqTipeUjianId == "51" || $reqTipeUjianId == "52" || $reqTipeUjianId == "53" || $reqTipeUjianId == "54" || $reqTipeUjianId == "55" || $reqTipeUjianId == "56" || $reqTipeUjianId == "57" || $reqTipeUjianId == "58" || $reqTipeUjianId == "59"|| $reqTipeUjianId == "60" || $reqTipeUjianId == "61" || $reqTipeUjianId == "62" || $reqTipeUjianId == "63" || $reqTipeUjianId == "64" || $reqTipeUjianId == "65") //ENGLISH DAN LAIN
  	$arrData= array("ID",  "No Urut", "NIP", "Nama", "Jumlah Soal", "Jumlah Benar");
elseif($reqTipeUjianId == "47")
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "Jumlah Soal", "Skor Jawaban Benar", "IQ Keterangan");
elseif($reqTipeUjianId == "48")
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "Nilai", "Keterangan");
elseif($reqTipeUjianId == "49")
    // $arrData= array("ID",  "NIP", "Nama", "R", "I", "A", "S", "E", "C", "Aktivitas", "Kompetensi", "Pekerjaan");
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "NILAI R", "NILAI I", "NILAI A", "NILAI S", "NILAI E", "NILAI C", "HASIL");
elseif($reqTipeUjianId == "70"||$reqTipeUjianId == "71"||$reqTipeUjianId == "72"||$reqTipeUjianId == "73" ||$reqTipeUjianId == "74")
    // $arrData= array("ID",  "NIP", "Nama", "R", "I", "A", "S", "E", "C", "Aktivitas", "Kompetensi", "Pekerjaan");
    $arrData= array("ID",  "NO URUT", "NIP", "Nama", "Jumlah Soal", "Jumlah Benar");
else
    $arrData= array("ID",  "No Urut", "NIP", "Nama", "Jumlah Soal", "Jumlah Benar", "Nilai Hasil");

// print_r($arrData);exit();
// $tinggi = 213;

// $tinggi = 184;
$tinggi = 199;

$tinggi = 213;
if($reqTipeUjianId == "1" || $reqTipeUjianId == "2")
$tinggi = 170;
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

<!-- <script type="text/javascript" src="../WEB/lib/window/js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="../WEB/lib/window/js/jquery/window/jquery.window.js"></script>
 -->
<script type="text/javascript" language="javascript" class="init">

	var oTable = "";

	function reloadajaxclick()
	{
		$('#example').dataTable().fnClearTable();
		$('#example').dataTable().fnDestroy();

		/*var reqDonaturLaysosId= reqNominal= reqCatatan= reqPencarian= "";
		reqDonaturLaysosId= $("#reqDonaturLaysosId").val();
		reqNominal= $("#reqNominal").val();
		reqCatatan= $("#reqCatatan").val();*/
		reqPencarian= $("#reqPencarian").val();

		oTable = $('#example').dataTable({ "iDisplayLength": 50,bJQueryUI: true,
		/* UNTUK MENGHIDE KOLOM ID */
		"aoColumns": [
			{ bVisible:false }
            , null// , { bVisible:false }
            <?
            for($i=2; $i < count($arrData); $i++)
            {
            ?>

              <?
              if($reqTipeUjianId == "17" && $i > 3 && $i < 19)
              {
              ?>
              , null
              , null
              <?
              }
              elseif($reqTipeUjianId == "18" && $i > 3 && $i < 14)
              {
              ?>
              , null
              , null
              <?
              }
              // elseif($reqTipeUjianId == "16" && $i > 12)
              elseif($reqTipeUjianId == "16" || $reqTipeUjianId == "43")
              {
              ?>
              , null
              // , null
              // , null
              // , null
              <?
              }
              elseif($reqTipeUjianId == "42")
              {
              	if($i == 2 || $i == 3)
              	{
              ?>
              	, null
              <?
              	}
              	elseif($i == 4 || $i == 5)
              	{
              ?>
              	, null, null, null, null, null
              <?
              	}
              	else
              	{
              ?>
              	, null, null, null, null
              <?
              	}
              }            
              else
              {
              ?>
              , null
              <?
              }
              ?>
            <?
            }
            ?>
		],			
		"bProcessing": true,
		"bServerSide": true,
		"sScrollY": ($(window).height() - <?=$tinggi?>),
		"sScrollX": "100%",
		"sScrollXInner": "100%",
		"ajax": {
			'type': 'POST',
			'url': "../json-pengaturan/jadwal_hasil_tes_json.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>",
			'data': {
				// reqDonaturLaysosId: String($("#reqDonaturLaysosId").val())
				// , reqNominal: String($("#reqNominal").val())
				// , reqCatatan: String($("#reqCatatan").val())
			}
		},
		"sPaginationType": "full_numbers"
		});

	}

    $(document).ready( function () {
		
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
        
        reloadajaxclick();

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
		  var anSelectedPegawaiId = anSelectedDataPegawaiId= '';
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
			  anSelectedDataPegawaiId= element[element.length-1];
			  anSelectedPegawaiId = element[0];
			  // console.log(anSelectedPegawaiId);
			  anSelectedPegawaiNipBaru = element[3];
			  anSelectedPegawaiNama = element[4];
		  });
		  
		  $('#example tbody').on( 'dblclick', 'tr', function () {
			  if(anSelectedData == "")
				  return false;
			  $("#btnUbahData").click();	
		  });
		  $('#btnCetakSemua').on('click', function () {

		  	<?
		  	if($reqTipeUjianId == "7" || $reqTipeUjianId == "40" || $reqTipeUjianId == "66" || $reqTipeUjianId == "42")
		  	{
		  	?>
		  		$.messager.confirm('Confirm','Apakah anda yakin cetak semua data ?',function(r){
					if (r){
						var win = $.messager.progress({ title:'Proses', msg:'Prosed data...'});
				  		urlAjax= "jadwal_hasil_tes_save_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>";
					    $.ajax({
					        'url': urlAjax, 
					        dataType: 'json', 
					        'success': function(datajson){
					        	// console.log(datajson);
					        	$.messager.progress('close');
					        	newWindow = window.open(datajson, 'Cetak');
					        	newWindow.focus();
					        }
					    });
					}
				});

		  		//-----
		  		// var params = oTable.$('input').serializeArray();
		  		// $.each(params, function(i,field){
		  		// 	var pegawai = field.value; 
		  		// 	var datas = pegawai.split('-');
		  		// 	if(datas[1] == 4.5){
		  		// window.open("jadwal_hasil_tes_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqRowId="+datas[0], "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400"); 
		  		// }
		  		// });
			  	
			  	//----
		  	<?
		  	}
		  	else
		  	{
		  	?>
			  	newWindow = window.open("jadwal_hasil_tes_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>", 'Cetak');
			  	newWindow.focus();
		  	<?
		  	}
		  	?>
			  
		  });
		  
		  $('#btnResetPapi').on('click', function () {
			  if(anSelectedData == "")
				  return false;
				
				<?
				if($reqTipeUjianId == "7"){}
				else if($reqTipeUjianId == "16")
				{
				?>
				anSelectedPegawaiId= anSelectedDataPegawaiId
				<?
				}
				?>
				
				$.messager.confirm('Confirm','Apakah anda yakin ingin reset waktu data terpilih ?',function(r){
					if (r){
					
						var win = $.messager.progress({ title:'Proses', msg:'Hapus data...'});
						var jqxhr = $.get( "../json-pengaturan/delete.php?reqMode=resetwaktupapi&id=<?=$reqId?>&reqAspekId=<?=$reqTipeUjianId?>&reqRowDetilId="+anSelectedPegawaiId, function() {
							$.messager.progress('close');
						})
						.done(function() {
							$.messager.progress('close');
							// setCariInfo();
						})
						.fail(function() {
							alert( "error" );
							$.messager.progress('close');
						});
					
					}
				});	
		  });

		  <?
		  if($parentId == ""){}
		  else
		  {
		  while($set_detil1->nextRow())
		  {
		  ?>
		  $('#btnResetCfit<?=$set_detil1->getField("TIPE_UJIAN_ID")?>').on('click', function () {
			  if(anSelectedData == "")
				  return false;
				
				anSelectedPegawaiId= anSelectedRowId;
				
				$.messager.confirm('Confirm','Apakah anda yakin ingin reset waktu data terpilih ?',function(r){
					if (r){
					
						var win = $.messager.progress({ title:'Proses', msg:'Hapus data...'});
						var jqxhr = $.get( "../json-pengaturan/delete.php?reqMode=resetwaktupapi&id=<?=$reqId?>&reqAspekId=<?=$set_detil1->getField("TIPE_UJIAN_ID")?>&reqRowDetilId="+anSelectedPegawaiId, function() {
							$.messager.progress('close');
						})
						.done(function() {
							$.messager.progress('close');
							// setCariInfo();
						})
						.fail(function() {
							alert( "error" );
							$.messager.progress('close');
						});
					
					}
				});	
		  });
		  <?
		  }
		  }
		  ?>

		  $('#btnGrafik').on('click', function () {
			  var tempId= $(this).attr('id');
			  if(tempId == "btnTambah")
				anSelectedRowId= "";
			  else
			  {
				  if(anSelectedData == "")
					  return false;
			  }
			  
			  opWidth = 1050;
			  opHeight = 550;
			  
			  opUrl= 'jadwal_hasil_tes_grafik.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqPegawaiId='+anSelectedRowId;
			  window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
			  //OpenDHTMLPopUp(opUrl);
		  });

		  $('#btnGrafikNew').on('click', function () {
			  var tempId= $(this).attr('id');
			  if(tempId == "btnTambah")
				anSelectedRowId= "";
			  else
			  {
				  if(anSelectedData == "")
					  return false;
			  }
			  
			  opWidth = 1050;
			  opHeight = 550;
			  
			  opUrl= 'jadwal_hasil_tes_grafik_new.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqPegawaiId='+anSelectedRowId;
			  window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
			  //OpenDHTMLPopUp(opUrl);
		  });

		  $('#btnGrafikDisc').on('click', function () {
			  var tempId= $(this).attr('id');
			  if(tempId == "btnTambah")
				anSelectedRowId= "";
			  else
			  {
				  if(anSelectedData == "")
					  return false;
			  }
			  
			  opWidth = 1050;
			  opHeight = 550;
			  
			  opUrl= 'jadwal_hasil_tes_grafik_disc.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqPegawaiId='+anSelectedRowId;
			  window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
			  //OpenDHTMLPopUp(opUrl);
		  });

		  $('#btnGrafikBigFive').on('click', function () {
			  var tempId= $(this).attr('id');
			  if(tempId == "btnTambah")
				anSelectedRowId= "";
			  else
			  {
				  if(anSelectedData == "")
					  return false;
			  }
			  
			  opWidth = 1050;
			  opHeight = 550;
			  
			  opUrl= 'jadwal_hasil_tes_grafik_big_five.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqPegawaiId='+anSelectedRowId;
			  window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
			  //OpenDHTMLPopUp(opUrl);
		  });

		  $('#btnAnalisa').on('click', function () {
            if(anSelectedData == "")
              return false;

          	opUrl= 'jadwal_hasil_tes_tipe_analisa.php?reqTipeUjianId=<?=$reqTipeUjianId?>&reqLowonganId=<?=$reqId?>&reqId='+anSelectedRowId;
          	window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
          });

          $('#btnAnalisaWpt').on('click', function () {
            if(anSelectedData == "")
              return false;

          	opUrl= 'jadwal_hasil_tes_tipe_analisa_wpt.php?reqTipeUjianId=<?=$reqTipeUjianId?>&reqLowonganId=<?=$reqId?>&reqId='+anSelectedRowId;
          	window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
          });

          $('#btnAnalisaKertih').on('click', function () {
            if(anSelectedData == "")
              return false;

          	opUrl= 'jadwal_hasil_tes_tipe_analisa_kertih.php?reqTipeUjianId=<?=$reqTipeUjianId?>&reqLowonganId=<?=$reqId?>&reqId='+anSelectedRowId;
          	window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
          });

          $('#btnAnalisaEpps').on('click', function () {
            if(anSelectedData == "")
              return false;

          	opUrl= 'jadwal_hasil_tes_tipe_analisa_epps.php?reqMode=1&reqId=<?=$reqId?>&reqId='+anSelectedRowId;
          	window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
          });

          $('#btnAnalisaKrapelinBaru').on('click', function () {
            if(anSelectedData == "")
              return false;

          	opUrl= 'jadwal_hasil_tes_tipe_analisa_krapelin_baru_jawaban.php?reqMode=1&reqLowonganId=<?=$reqId?>&reqId='+anSelectedDataPegawaiId;
          	// window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
          	newWindow = window.open(opUrl, 'Cetak');
          	newWindow.focus();
          });

           $('#btnAnalisaBigFive').on('click', function () {
             if(anSelectedData == "")
              return false;

          	opUrl= 'jadwal_hasil_tes_tipe_big_five.php?reqTipeUjianId=<?=$reqTipeUjianId?>&reqLowonganId=<?=$reqId?>&reqId='+anSelectedRowId;
          	window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
          });

          $('#btnAnalisaKrapelin').on('click', function () {
            if(anSelectedData == "")
              return false;

          	opUrl= 'jadwal_hasil_tes_tipe_analisa_krapelin_jawaban.php?reqMode=1&reqId=<?=$reqId?>&reqId='+anSelectedDataPegawaiId;
          	window.OpenDHTML(opUrl, "Master Peraturan", '1000', '615');
          });

		  $('#btnCetak').on('click', function () {

		  	<?
		  	$arrexcept= [];
		  	$arrexcept= array("7", "40", "66");
		  	// if($reqTipeUjianId == "7" || $reqTipeUjianId == "40" || $reqTipeUjianId == "66")
    		if(in_array($reqTipeUjianId, $arrexcept))
		  	{
		  	?>

			  	if(anSelectedRowId == "")
			  	{
			  		$.messager.alert('Info', "Pilih salah satu pelamar.", 'info');
			  		return false;
			  	}
			  	newWindow = window.open("jadwal_hasil_tes_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqRowId="+anSelectedRowId, 'Cetak');
			  	newWindow.focus();
		  	<?
		  	}
		  	else if ($reqTipeUjianId == "42")
		  	{
		  	?>
		  		if(anSelectedRowId == "")
			  	{
			  		$.messager.alert('Info', "Pilih salah satu pelamar.", 'info');
			  		return false;
			  	}
			  	
			  	newWindow = window.open("jadwal_hasil_tes_disk_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqRowId="+anSelectedRowId, 'Cetak');
			  	newWindow.focus();
		  	<?
		  	}
		  	else
		  	{
		  	?>
			  	newWindow = window.open("jadwal_hasil_tes_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>", 'Cetak');
			  	newWindow.focus();
		  	<?
		  	}
		  	?>
			  
		  });

		  $('#btnCetakPersonal').on('click', function () {

		  	<?
		  	$arrexcept= [];
		  	$arrexcept= array("7", "40", "66");
		  	// if($reqTipeUjianId == "7" || $reqTipeUjianId == "40" || $reqTipeUjianId == "66")
    		if(in_array($reqTipeUjianId, $arrexcept))
		  	{
		  	?>

			  	if(anSelectedRowId == "")
			  	{
			  		$.messager.alert('Info', "Pilih salah satu pelamar.", 'info');
			  		return false;
			  	}
			  	newWindow = window.open("jadwal_hasil_tes_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqRowId="+anSelectedRowId, 'Cetak');
			  	newWindow.focus();
		  	<?
		  	}
		  	else if ($reqTipeUjianId == "41")
		  	{
		  	?>
			  	newWindow = window.open("jadwal_hasil_tes_mbti_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqRowId="+anSelectedRowId, 'Cetak');
			  	newWindow.focus();
		  	<?
		  	}
		  	else
		  	{
		  	?>
			  	newWindow = window.open("jadwal_hasil_tes_excel.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>", 'Cetak');
			  	newWindow.focus();
		  	<?
		  	}
		  	?>
			  
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

function OpenDHTMLPopUp(opAddress) {
	$.window({
		showModal: true,
		modalOpacity: 0.6,
		title: "Lookup Data",
		url: opAddress,
		bookmarkable: false,
		showFooter: false
		//, width: 600,
		//height: 400
	});
	maximized = true;
}

function iecompattest(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
{
	opCaption= "Modul Pengaturan";
	//var left= top = "";
	var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
	var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
	
	opWidth = iecompattest().clientWidth - 25;
	opHeight = iecompattest().clientHeight - 45;
	//divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,center=1,resize=1,scrolling=1,midle=1'); return false;
	
}

function OpenDHTMLCenter(opAddress, opCaption, opWidth, opHeight)
{
	// alert("s");return false;
	opCaption= "Modul Pengaturan";
	var width  = opWidth;
	var height = opHeight;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var params = 'width='+width+', height='+height;
	params += ', top='+top+', left='+left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=no';
	params += ', scrollbars=no';
	params += ', status=no';
	params += ', toolbar=no';
	params += ', center=yes';
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
}

function OpenDHTMLDetil(opAddress, opCaption, opWidth, opHeight)
{
	opCaption= "Modul Pengaturan";
	var left = (screen.width/2)-(opWidth/2);
	var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
	//alert(top);return false;
	var width  = opWidth;
	var height = opHeight;
	var params = 'width='+width+', height='+height;
	params += ', top='+top+', left='+left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=no';
	params += ', scrollbars=no';
	params += ', status=no';
	params += ', toolbar=no';
	params += ', center=yes';
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
}
	
</script>

<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<!-- Flex Menu -->
<link rel="stylesheet" type="text/css" href="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/flexdropdown.css" />
<script type="text/javascript" src="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/jquery.min.js"></script>
<script type="text/javascript" src="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/flexdropdown.js"></script> 
    

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>
</head>

<body id="index" class="grid_2_3" style="overflow:hidden">
    <div class="full_width" style="width:100%;">
    <form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
    </form>
    <div id="header-tna">Hasil <span><?=$tempNamaTipe?></span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    <ul>
    	<li>
    		<?
    		$arrexcept= [];
    		$arrexcept= array("7", "28", "40", "42", "49", "41");
    		if(in_array($reqTipeUjianId, $arrexcept)){}
            // if($reqTipeUjianId == "7" || $reqTipeUjianId == "28" || $reqTipeUjianId == "40" || $reqTipeUjianId == "42"){}
        	elseif($reqTipeUjianId == "16")
            {
            ?>
            <a href="#" id="btnAnalisaKrapelin" title="Lihat Jawaban">Lihat Jawaban</a>
            <?
            }
            elseif($reqTipeUjianId == "43")
            {
            ?>
            <a href="#" id="btnAnalisaKrapelinBaru" title="Lihat Jawaban">Lihat Jawaban</a>
            <?
            }
            elseif($reqTipeUjianId == "45")
            {
            ?>
            <a href="#" id="btnAnalisaBigFive" title="Lihat Jawaban">Lihat Jawaban</a>
            <?
            }
            elseif($reqTipeUjianId == "17")
            {
            ?>
            <a href="#" id="btnAnalisaEpps" title="Lihat Jawaban">Lihat Jawaban</a>
            <?
            }
            elseif($reqTipeUjianId == "47")
            {
            ?>
            <a href="#" id="btnAnalisaWpt" title="Lihat Jawaban">Lihat Jawaban</a>
            <?
            }
            elseif($reqTipeUjianId == "48")
            {
            ?>
            <a href="#" id="btnAnalisaKertih" title="Lihat Jawaban">Lihat Jawaban</a>
            <?
            }
            else
            {
            ?>
            <a href="#" id="btnAnalisa" title="Lihat Jawaban">Lihat Jawaban</a>
            <?
            }
            $arrCheck= [];
    		$arrCheck= array(7, 40, 42, 43, 66);
    		if (in_array($reqTipeUjianId, $arrCheck)) 
    		{
    		?>
    			<a href="#" id="btnCetakSemua" title="CetakSemua">Cetak Semua</a>
    		<?
    		}
    		?>		

    		<?
    		// if($reqTipeUjianId == "1" || $reqTipeUjianId == "2" || $reqTipeUjianId == "18") 
            if($parentId !== "")
            {
            ?>
              <a href="#" title="Cetak" id="btnCetakRoot" data-flexmenu="flexmenu3">&nbsp;Reset Waktu</a>
            <?
            }
            else
            {
            	if($reqTipeUjianId == "16" || $reqTipeUjianId == "43"){}
            	else
            	{
            ?>
            <a href="#" id="btnResetPapi" title="Reset Waktu">Reset Waktu</a>
            <?
            	}
            }
            ?>

            <a href="#" id="btnCetak" title="Cetak">Cetak</a>

    		<?
    		if($reqTipeUjianId == "16")
    		{
    		?>
    		<a href="#" id="btnGrafik" title="Grafik">Grafik</a>
    		<?
    		}
    		elseif($reqTipeUjianId == "43")
    		{
    		?>
    		<a href="#" id="btnGrafikNew" title="Grafik">Grafik</a>
    		<?
    		}
    		elseif($reqTipeUjianId == "41")
    		{
    		?>
    		<a href="#" id="btnCetakPersonal" title="Cetak Individu">Cetak Individu</a>
    		<?
    		}
    		elseif($reqTipeUjianId == "42")
    		{
    		?>
    		<a href="#" id="btnGrafikDisc" title="Grafik">Grafik</a>
    		<?
    		}
    		elseif($reqTipeUjianId == "45")
    		{
    		?>
    		<a href="#" id="btnGrafikBigFive" title="Grafik">Grafik</a>
    		<?
    		}
    		?>
    	</li>
          <!-- <li>
          	<li><a href="#" id="btnTambah" title="Tambah"><img src="../WEB/images/pegawai-edit.png"/>Tambah</a></li>
          	<li><a href="#" title="Ubah" id="btnUbahData"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Ubah</a></li>
            <li><a href="#" id="btnDelete" title="Hapus"><img src="../WEB/images/pegawai-edit.png"/>&nbsp;Hapus</a></li>
          </li> -->
    </ul>

    <?
    // if($reqTipeUjianId == "1" || $reqTipeUjianId == "2" || $reqTipeUjianId == "18")
    if($parentId !== "")
    {
    ?>
    <ul id="flexmenu3" class="flexdropdownmenu">
      <?
      while($set_detil->nextRow())
      {
      ?>
      <li><a href="#" id="btnResetCfit<?=$set_detil->getField("TIPE_UJIAN_ID")?>" title="Reset Waktu">Reset Waktu <?=$set_detil->getField("TIPE")?></a></li>
      <?
      }
      ?>
    </ul>
    <?
    }
    ?>
    </div>
      
    <div id="bar-status">
    </div>
    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
    	<?
	    if($reqTipeUjianId == "17")
	    {
	    ?>
	    <tr>
	        <?
	        for($i=0; $i < count($arrData); $i++)
	        {
	            $width= "10";
	            if($i == 0)
	                $width= "100";
	            elseif($i == 1)
	                $width= "250";
	        ?>
	            <?
	            if($i > 3 && $i < 19)
	            {
	            ?>
	            <th style="text-align:center" colspan="2"><?=$arrData[$i]?></th>
	            <?
	            }
	            else
	            {
	            ?>
	            <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
	            <?
	            }
	            ?>
	        <?
	        }
	        ?>
	    </tr>
	    <tr>
	        <?
	        for($i=4; $i < count($arrData) - 2; $i++)
	        {
	        ?>
	        <th style="text-align:center">S</th>
	        <th style="text-align:center">SS</th>
	        <?
	        }
	        ?>
	    </tr>
	    <?
	    }
	    elseif($reqTipeUjianId == "18")
	    {
	    ?>
	    <tr>
	        <?
	        for($i=0; $i < count($arrData); $i++)
	        {
	            $width= "10";
	            if($i == 0)
	                $width= "100";
	            elseif($i == 1)
	                $width= "250";
	        ?>
	            <?
	            if($i > 3 && $i < 14)
	            {
	            ?>
	            <th style="text-align:center" colspan="2"><?=$arrData[$i]?></th>
	            <?
	            }
	            else
	            {
	            ?>
	            <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
	            <?
	            }
	            ?>
	        <?
	        }
	        ?>
	    </tr>
	    <tr>
	        <?
	        for($i=4; $i < count($arrData) - 1; $i++)
	        {
	        ?>
	        <th style="text-align:center">RW</th>
	        <th style="text-align:center">SE</th>
	        <?
	        }
	        ?>
	    </tr>
	    <?
	    }
	    elseif($reqTipeUjianId == "16")
	    {
	    ?>
	    <tr>
	        <?
	        for($i=0; $i < count($arrData); $i++)
	        {
	            $width= "10";
	            if($i == 0)
	                $width= "100";
	            elseif($i == 1)
	                $width= "250";
	        ?>
	            <?
	            // if($i > 12)
	            // {
	            ?>
	            <!-- <th style="text-align:center" colspan="4"><?=$arrData[$i]?></th> -->
	            <?
	            // }
	            // else
	            // {
	            ?>
	            <!-- <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th> -->
	            <?
	            // }
	            ?>
	            <th width="<?=$width?>px"><?=$arrData[$i]?></th>
	        <?
	        }
	        ?>
	    </tr>
	    <!-- <tr> -->
	        <?
	        // for($i=13; $i < count($arrData); $i++)
	        // {
	        ?>
	        <!-- <th style="text-align:center">Puncak</th>
	        <th style="text-align:center">Terendah</th>
	        <th style="text-align:center">Benar</th>
	        <th style="text-align:center">Salah</th> -->
	        <?
	        // }
	        ?>
	    <!-- </tr> -->
	    <?
	    }
	    elseif($reqTipeUjianId == "43")
	    {
	    ?>
	    <tr>
	        <?
	        for($i=0; $i < count($arrData); $i++)
	        {
	            $width= "10";
	            if($i == 0)
	                $width= "100";
	            elseif($i == 1)
	                $width= "250";
	        ?>
	            <?
	            if($i == 4)
	            {
	            ?>
	            <th style="text-align:center" colspan="3">Jumlah Kesalahan</th>
	            <?
	        	}
	            elseif($i >= 5 && $i <= 6){}
	            elseif($i == 7)
	            {
	            ?>
	            <th style="text-align:center" colspan="3">Jumlah Tidak Diisi</th>
	            <?
	        	}
	            elseif($i >= 8 && $i <= 9){}
	            elseif($i == 10)
	            {
	            ?>
	            <th style="text-align:center" colspan="2">Puncak Tertinggi</th>
	            <?
	            }
	            elseif($i == 11){}
	            elseif($i == 12)
	            {
	            ?>
	            <th style="text-align:center" colspan="2">Puncak Terendah</th>
	            <?
	            }
	            elseif($i == 13){}
	            elseif($i == 14)
	            {
	            ?>
	            <th style="text-align:center" colspan="3">Ketelitian</th>
	            <?
	            }
	            elseif($i == 15 || $i == 16){}
	            elseif($i == 17)
	            {
	            ?>
	            <th style="text-align:center" colspan="3">Kecepatan</th>
	            <?
	            }
	            elseif($i == 18 || $i == 19){}
	            else
	            {
	            ?>
	            <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
	            <?
	            }
	            ?>
	        <?
	        }
	        ?>
	    </tr>
	    <tr>
	        <?
	        for($i=4; $i < count($arrData); $i++)
	        {
	        ?>
	        <th width="<?=$width?>px"><?=$arrData[$i]?></th>
	        <?
	        }
	        ?>
	    </tr>
	    <?
	    }
	    elseif($reqTipeUjianId == "42")
	    {
	    ?>
	    <tr>
	        <?
	        for($i=0; $i < count($arrData); $i++)
	        {
	            $width= "5";
	            if($i == 0)
	                $width= "100";
	            elseif($i == 3)
	                $width= "250";

	            if($i > 3)
	            {
	            	$colspan= "5";
	            	if($i == 6)
	            		$colspan= "4";
	            ?>
	            <th style="text-align:center" colspan="<?=$colspan?>"><?=$arrData[$i]?></th>
	            <?
	            }
	            else
	            {
	            ?>
	            <th rowspan="2" width="<?=$width?>px"><?=$arrData[$i]?></th>
	            <?
	            }
	        }
	    ?>
		</tr>
		<tr>
	        <th style="text-align:center">D</th>
	        <th style="text-align:center">I</th>
	        <th style="text-align:center">S</th>
	        <th style="text-align:center">C</th>
	        <th style="text-align:center">*</th>
	        <th style="text-align:center">D</th>
	        <th style="text-align:center">I</th>
	        <th style="text-align:center">S</th>
	        <th style="text-align:center">C</th>
	        <th style="text-align:center">*</th>
	        <th style="text-align:center">D</th>
	        <th style="text-align:center">I</th>
	        <th style="text-align:center">S</th>
	        <th style="text-align:center">C</th>
	    </tr>
	    <?
	    }	   
	    else
	    {
	    ?>
	    <tr>
	        <?
	        for($i=0; $i < count($arrData); $i++)
	        {
	            /*$width= "10";
	            if($i == 0)
	                $width= "100";
	            elseif($i == 1)
	                $width= "250";*/
	            if($i == 1)
	                $width= "30";
	        ?>
	            <th width="<?=$width?>px"><?=$arrData[$i]?></th>
	        <?
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

