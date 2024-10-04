<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalAcara.php");

$set= new JadwalTes();

$reqId = httpFilterGet("reqId");

$reqMode= "update";
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;

$tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), "date");
$tempTanggalTesInfo= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');
$reqStatusValid= $set->getField('STATUS_VALID');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');
$tempJumlahRuangan= $set->getField('JUMLAH_RUANGAN');
//echo $user_group->query;exit;

$index_loop= 0;
$arrJadwalAcara="";
$arrJadwalAcara=array();
$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PENGGALIAN_ID IS NOT NULL";
$set= new JadwalAcara();
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"]= $set->getField("JADWAL_ACARA_ID");
	$arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
	$arrJadwalAcara[$index_loop]["KETERANGAN_ACARA"]= $set->getField("KETERANGAN_ACARA");
	$arrJadwalAcara[$index_loop]["JAM"]= $set->getField("PUKUL1")." s/d ".$set->getField("PUKUL2");
	$index_loop++;
}
$jumlahjadwalacara= $index_loop;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<!-- <script type="text/javascript" src="css/dropdowntabs.js"></script> -->

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link href="../WEB/lib/bp/css/bootstrap.min.css" rel="stylesheet"/>
<link href="../WEB/lib/geni/geni.css?v=3" rel="stylesheet"/>

<!-- <link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css"> -->
<!-- <script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script> -->


<script type="text/javascript" src="../WEB/lib/jexcel/jquery.min.js"></script>

<link href="../WEB/lib/sweetalert/sweetalert2.min.css" rel="stylesheet"/>
<script src="../WEB/lib/sweetalert/sweetalert2.min.js"></script>

<link rel="stylesheet" href="../WEB/lib/jexcel/jquery-ui.css">
<script src="../WEB/lib/jexcel/jquery-ui.js"></script>

<!-- <script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script> -->
<!-- <script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script> -->

<script src="../WEB/lib/loading-overlay/loadingoverlay.min.js"></script>
<script src="../WEB/lib/geni/geni.js"></script>
<!-- <script type="text/javascript" src="../WEB/lib/geni/adminpro/js/waves.js"></script>
<script type="text/javascript" src="../WEB/lib/geni/adminpro/js/sidebarmenu.js"></script> -->
    

<link href="../WEB/lib/jexcel/jsuites.css" rel="stylesheet"/>
<script src="../WEB/lib/jexcel/jsuites.js"></script>
<link href="../WEB/lib/jexcel/jexcel.css" rel="stylesheet"/>
<script src="../WEB/lib/jexcel/jexcelinfo.js"></script>
<script src="../WEB/lib/jexcel/fungsitambahan.js"></script>
<script src="../WEB/lib/jquery.hotkeys/jquery.hotkeys.js"></script>

<script type="text/javascript">	

<?
    $arrkolomdata= array(
        array("val"=>"reqCatalogId", "nama"=>"id", "width"=>"")
        , array("val"=>"reqCheckDelete", "nama"=>"Delete", "width"=>"100")
        , array("val"=>"reqLineNo", "nama"=>"No.", "width"=>"80")
        , array("val"=>"reqAsesorId", "nama"=>"reqAsesorId", "width"=>"")
        , array("val"=>"reqAsesorNama", "nama"=>"Nama Asesor", "width"=>"250")
        , array("val"=>"reqBatasPegawai", "nama"=>"Batas", "width"=>"100")
        , array("val"=>"reqKeterangan", "nama"=>"Keterangan", "width"=>"500")
    );

    // buat header
    $jWidth= ""; $jAlign= ""; $jlinecolumns= "";
    for($idata=0; $idata < count($arrkolomdata); $idata++)
    {
       
        $findcolumn= $arrkolomdata[$idata]['val'];
        $column= $arrkolomdata[$idata]['nama'];
        $width= $arrkolomdata[$idata]['width'];

        // set type jexcel
        $type= "{ title: '".$column."'";
        if(in_array($findcolumn, array("reqCatalogId", "reqAsesorId")))
            $type .= ", type:'hidden'";
        else if(in_array($findcolumn, array("reqCheckDelete")))
            $type .= ", type: 'checkbox'";
        else if(in_array($findcolumn, array("reqLineNo")))
            $type .= ", readOnly: true " ;
        else if(in_array($findcolumn, array("reqAsesorNama")))
            $type .= ", type: 'invpritemtypelookup' ";
        else if(in_array($findcolumn, array("reqBatasPegawai")))
            $type .= ", type: 'numeric', mask:'[-]#.##,00', decimal:',' ";

        if(empty($width))
        {
        $type .= "}";
        }
        else
        {
            $type .= ", width: ".$width." }";
        }

        if($jlinecolumns == "")
            $jlinecolumns = $type;
        else
            $jlinecolumns .= ",".$type;
    }
    // print_r($jlinecolumns);exit;
?>

var selectedjexcel = "<?= $arrJadwalAcara[0]["JADWAL_ACARA_ID"]?>";

	$(function(){
		$( "#tabs" ).tabs({
			activate: function(event, ui) {
		        selectedjexcel =  ui.newTab.attr('li',"tab-id")[0].getAttribute("tab-id")
		    }
		});
	});

	function simpan()
	{
		var lineInvalid = false;
        validasiinvalid= $('td.invalid').length;
        if (validasiinvalid) {
            lineInvalid = true;
        }

        if (lineInvalid) {
            swal('', 'Form berisi kesalahan. Silakan periksa lagi.', 'error');
            return false;
        }

		var formData = new FormData;
		formData.append('reqJadwalTesId', "<?=$reqId?>");
		<?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
			$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
		?>
		// cek simpan percobaan
	        var dataexcel = $('#vardatajexcel<?=$infoid?>').jexcel('getData');

	        for (var i = 0; i < dataexcel.length; i++) {
	            var row = dataexcel[i];

	            var productid= row["<?=array_search('reqAsesorId', array_column($arrkolomdata, 'val'))?>"];

	            for (var j = 0; j < row.length; j++) {
	                var cell = row[j];

	                // console.log(productid+"--"+j+"--"+i);
	                if(productid == "")
	                {
	                	continue;
	                }

	                if(productid == "" && parseInt(i) > 0)
	                {
	                    j= row.length;
	                }

	                if(j == "<?=array_search('reqBatasPegawai', array_column($arrkolomdata, 'val'))?>" && (cell == 0 || cell == "") )
	                {
	                    swal({
	                        title: "",
	                        text: "Data gagal disimpan, karena kolom Batas Pegawai belum diisi",
	                        icon: "success",
	                    })
	                    return false;break; 
	                }
	                
	            }
	        }

	        formData.append('reqData[<?=$infoid?>]', JSON.stringify(dataexcel));

        	// return false;
		<?
		}
		?>

		$(function(){
		
			$.ajax({
	          url: "../json-pengaturan/ex_imp_jadwal_acara_asesor.php",
	          data: formData,
	          processData: false,
	          contentType: false,
	          type: 'POST'
	          , beforeSend: function () {geni.block('body');}
	          , success: function(data) {
	          	// console.log(data);return false;
	          	document.location.href = "ex_imp_jadwal_acara_asesor.php?reqId=<?=$reqId?>";
	          }
	          , complete: function () {geni.unblock('body');}
	        });
	    });
		
	}

</script>

<style type="text/css">
body {
    font-family: 'Open SansRegular' !important;
    font-size: 14px !important;
}

[type="checkbox"] {
    left: auto !important;
    opacity: 1 !important;
    position: relative !important;
}

.jexcel > thead > tr > td
{
    z-index: 1 !important;
}

.jexcel .invalid {
    background-color: #ff4c42;
}
</style>
</head>

<body>
<div id="page_effect">
	<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
	<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<table class="table_list" cellspacing="1" width="100%">
			<tr>
				<td colspan="6">
				<div id="header-tna-detil">Export / Import <span>Jadwal Asesor</span></div>
				</td>			
			</tr>
            <tr>
				<td width="200px">Formula</td>
				<td width="2px">:</td>
				<td>
                    <label id="reqFormulaEselon"><?=$tempFormulaEselon?></label>
			   </td>
			</tr>
			<tr>
				<td>Tanggal Tes</td>
				<td>:</td>
				<td>
					<?=$tempTanggalTesInfo?>
			   </td>
			</tr>
            <tr>
				<td>Acara</td>
				<td>:</td>
				<td>
					<?=$tempAcara?>
			   </td>
			</tr>
			<tr>
				<td>Tempat</td>
				<td>:</td>
				<td>
					<?=$tempTempat?>
			   </td>
			</tr>
            <tr>
				<td>Alamat</td>
				<td>:</td>
				<td>
					<?=$tempAlamat?>
			   </td>
			</tr>
            <tr>
				<td>Keterangan</td>
				<td>:</td>
				<td>
					<?=$tempKeterangan?>
			   </td>
			</tr>
			<?
			if($jumlahjadwalacara > 0)
			{
			?>
			<tr>
				<td>
					<input type="hidden" name="reqId" value="<?=$reqId?>">
					<button onclick="simpan()">Simpan</button>
				</td>
			</tr> 
			<?
			}
			?>
		</table>
    </div>

	<?
	if($jumlahjadwalacara > 0)
	{
	?>
	<div class="card border-top-0 shadow-none rounded-0" id="tabs">
		<ul>
			<?
			for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
			{
				$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
				$infonama= $arrJadwalAcara[$index_loop]["KETERANGAN_ACARA"];
			?>
			<li tab-id="<?=$infoid?>"><a href="#tabs-<?=$infoid?>"><?=$infonama?></a></li>
			<?
			}
			?>
		</ul>
		<?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
			$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
		?>
		<div id="tabs-<?=$infoid?>" class="col-md-12">
			<div id="vardatajexcel<?=$infoid?>"></div>
		</div>
		<?
		}
		?>
	</div>
	<?
	}
	?>

</div>

<script>
var jlinecolumns = [<?=$jlinecolumns?>];
var jexcelOptions = {
    contextMenu:function() { return false; }
    , defaultColWidth: 200
    , defaultColAlign: 'center'
    , rows: {height: 50}
    , allowInsertColumn: false
    , allowManualInsertColumn: false
    , allowDeleteColumn: false
    , allowRenameColumn: false
    , columnSorting: false
    , tableOverflow: true
    , tableWidth: "100%"
    , tableHeight: "auto"
    , wordWrap: true
    , parseFormulas: true
    , allowDeleteRow: true
    , tableOverflow:true
    , tableHeight:'300px'
    , copyCompatibility: true
    , updateTable: function(instance, cell, col, row, val, label, cellName) {
        cellIRowIdtable= $(instance).prop('id');
        // mewarnai kalau value updating material 1
        if(col == "<?=array_search('reqBatasPegawai', array_column($arrkolomdata, 'val'))?>")
        {
            rownext= parseInt(row) + 1;
            var valdata = parseFloat(replacenum($("#"+cellIRowIdtable).jexcel('getValue', codeexceltochar(<?=array_search('reqBatasPegawai', array_column($arrkolomdata, 'val'))?>)+rownext)));

            if(valdata == 0)
            {
                $(cell).addClass('invalid');
            }
            else
            	$(cell).removeClass('invalid');
            	
        }
    }

}

function checkNullValue(textData)
{
   return textData==null ? '' : textData;
}

function replacenum(num)
{
    return checkNullValue(num).toString().replace(/\,/g, '');
}

var changevardatajexcel = function (instance, cell, x, y, value, old) {
    if (value == old) {
        return false;
    }

    selectedJexcel= $(instance).prop('id');
    selectedJexcel= String(selectedJexcel);
    selectedJexcel= selectedJexcel.split('vardatajexcel'); 
	selectedJexcel= selectedJexcel[1];
	// console.log(selectedJexcel);
    selectedCell= cell;
    definecoltable= x;
    definerowtable= y;

    if (x == <?=array_search('reqAsesorNama', array_column($arrkolomdata, 'val'))?> && triggerasesorlookup == 1)
    {
        setasesorlookup(value, selectedCell, selectedJexcel, definerowtable);
    }
};

<?
for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
{
	$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
	$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
?>
var vardatajexcel<?=$infoid?>= "";
<?
}
?>
var selectedTable = selectedCell = selectedJexcel= null;
var definecoltable, definerowtable;
var triggerasesorlookup= 1;

var insertedRow = function(instance, rowNumber, numOfRows, rowRecords, insertBefore) {
    cellIRowIdtable= $(instance).prop('id');
    dataexcel = $('#'+cellIRowIdtable).jexcel('getData');
    r_data= dataexcel.length;
    rowid= String(cellIRowIdtable);
    rowid= rowid.split('vardatajexcel'); 
	rowid= rowid[1];
    // console.log(rowid);

    rowNumber= parseInt(rowNumber) + 1;
    rowNumberIndex= parseInt(rowNumber) + 1;
    // console.log(rowNumber+"--"+rowNumberIndex);

    <?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
		$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
	?>
		if(rowid == <?=$infoid?>)
		{
		    vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqLineNo', array_column($arrkolomdata, 'val'))?>, rowNumber]).classList.remove('readonly');
		    $('#'+cellIRowIdtable).jexcel('setValue', codeexceltochar(<?=array_search('reqLineNo', array_column($arrkolomdata, 'val'))?>)+rowNumberIndex, r_data);
		    vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqLineNo', array_column($arrkolomdata, 'val'))?>, rowNumber]).classList.add('readonly');
		}
    <?
	}
    ?>
}

// cegah hapus data kl ada id
var beforedeleterow = function(instance, rowNumber, numOfRows) {
    var cellIRowIdtable= "";
    cellIRowIdtable= $(instance).prop('id');
    rowid= String(cellIRowIdtable);
    rowid= rowid.split('vardatajexcel'); 
	rowid= rowid[1];

	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
		$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
	?>
		if(rowid == <?=$infoid?>)
		{
			if (typeof vardatajexcel<?=$infoid?>.getValueFromCoords(<?=array_search('reqCatalogId', array_column($arrkolomdata, 'val'))?>, rowNumber) !== "undefined") 
			{
				checkid= vardatajexcel<?=$infoid?>.getValueFromCoords(<?=array_search('reqCatalogId', array_column($arrkolomdata, 'val'))?>, rowNumber);
				if(checkid !== "")
		    	{
					swal('', 'Kalau hapus pakai checklist, karena data sudah masuk ke database.', 'error');
			    	return false;
		    	}
			}
		}
	<?
	}
    ?>
    return true;

}
<?
if($jumlahjadwalacara > 0)
{
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
		$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
?>
	urlAjax= "../json-pengaturan/ex_imp_jadwal_acara_asesor_data.php?reqMode=viewdetil&reqId=<?=$infoid?>";
	$.ajax({
	    'url': urlAjax, 
	    dataType: 'json', 
	    'success': function(datajson){
	        vardatajexcel<?=$infoid?> = jexcel(document.getElementById('vardatajexcel<?=$infoid?>'), $.extend({data: datajson, columns: jlinecolumns, onchange: changevardatajexcel, oninsertrow: insertedRow, onbeforedeleterow: beforedeleterow}, jexcelOptions));
	        // console.log(datajson);
	    }
	});

<?
	}
}
?>


// function OptionSetAsesorDetil(id, setselectedcell, selectedjexcel_, setrowtable)
function OptionSetAsesorDetil(id, rowid)
{
	var arrId = new Array();
	arrId = id.split(",");
	var count = arrId.length;
	temp = arrId[0];
	value= temp;

	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
			$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
			$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
	?>
		if(selectedjexcel == "<?=$infoid?>")
		{
            definerowtable= jumlahlastrow= vardatajexcel<?=$infoid?>.options.data.length;
		}
	<?
	}
	?>

	if(jumlahlastrow >= 1)
	definerowtable= parseInt(definerowtable) - 1;

	optionarrayset(id, rowid, definerowtable, value, 0, arrId);
}

var ajaxdataasesorid= ajaxdataasesornama= tempDepartemen= tempNama= tempKelas= "";

function optionarrayset(id, setselectedcell, setrowtable , value, recursiveindex, arrId)
{
	definerowtable= setrowtable;
	selectedasesorid= dataasesorpopup(selectedjexcel);

    // console.log(selectedasesorid);
    urlAjax= "../json-pengaturan/ex_imp_jadwal_acara_asesor_data.php?reqMode=asesor&reqRowId="+selectedasesorid+"&reqAsesorId="+value;
    $.ajax({'url': urlAjax, dataType: "json", beforeSend: function () {geni.block(setselectedcell);}, 'success': function(datajson){
    	definerowtable= setrowtable;
    	triggerasesorlookup= 0;
    	ajaxdataasesorid= checkNullValue(datajson.ASESOR_ID);
    	ajaxdataasesornama= checkNullValue(datajson.NAMA);

		// console.log(selectedjexcel +'-'+ <?=$infoid?>);

    	<?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
			$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
		?>
			// console.log(selectedjexcel);
			if(selectedjexcel == <?=$infoid?>)
			{
				vardatajexcel<?=$infoid?>.ignoreEvents = true;
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqAsesorId', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdataasesorid, true);
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqAsesorNama', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdataasesornama, true);
		        vardatajexcel<?=$infoid?>.ignoreEvents = false;
		    }
	    <?
		}
	    ?>
        triggerasesorlookup= 1;
	}, complete: function () {

        var jumlahrowimport= arrId.length;
		recursiveindex= parseInt(recursiveindex) + 1;
		// console.log(recursiveindex +'-'+ jumlahrowimport);

		if(parseInt(recursiveindex) < parseInt(jumlahrowimport))
		{
		<?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
			$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
		?>

			if(selectedjexcel == <?=$infoid?>)
			{
				vardatajexcel<?=$infoid?>.insertRow(1);
				definecoltable= "<?=array_search('reqAsesorNama', array_column($arrkolomdata, 'val'))?>";
				selectedCell= vardatajexcel<?=$infoid?>.getCellFromCoords(definecoltable,definerowtable);
				value= arrId[recursiveindex];

			}


	    <?
		}
	    ?>
			optionarrayset(id, setselectedcell, definerowtable, value, recursiveindex, arrId );
		}
		
		geni.unblock(setselectedcell);
	}
	});		
}


function setvalidasicolor(selectedjexcel, y)
{
	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
		$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
	?>
		if(selectedjexcel == <?=$infoid?>)
		{
			validasicolor = vardatajexcel<?=$infoid?>.getValueFromCoords(<?=array_search('reqAsesorId', array_column($arrkolomdata, 'val'))?>, y);
		    if(validasicolor == '')
		    {
		        $(vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqAsesorNama', array_column($arrkolomdata, 'val'))?>, y])).addClass('invalid');
		    }
		    else
		    {
		        $(vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqAsesorNama', array_column($arrkolomdata, 'val'))?>, y])).removeClass('invalid');
		    }

		    validasicolor = vardatajexcel<?=$infoid?>.getValueFromCoords(<?=array_search('reqBatasPegawai', array_column($arrkolomdata, 'val'))?>, y);
		    if(validasicolor == 0 || validasicolor == "")
		    {
		        $(vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqBatasPegawai', array_column($arrkolomdata, 'val'))?>, y])).addClass('invalid');
		    }


		    else if (x == <?=array_search('reqBatasPegawai', array_column($arrkolomdata, 'val'))?> && (value == 0 || value == ""))
    {
    	<?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
			$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
		?>
			if(selectedJexcel == <?=$infoid?>)
			{
    		vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqBatasPegawai', array_column($arrkolomdata, 'val'))?>, definerowtable]).classList.add('invalid');
    		}
    	<?
    	}
    	?>
    }




		}
	<?
	}
    ?>
}

function setasesorlookup(id, setselectedcell, selectedjexcel, setrowtable)
{

	// console.log(selectedjexcel);
    definerowtable= setrowtable;
    // console.log(definerowtable);
    selectedasesorid= dataasesor(selectedjexcel);
    urlAjax= "../json-pengaturan/ex_imp_jadwal_acara_asesor_data.php?reqMode=asesor&reqRowId="+selectedasesorid+"&reqPencarian="+encodeURIComponent(id);
    $.ajax({'url': urlAjax, dataType: "json", beforeSend: function () {geni.block(setselectedcell);}, 'success': function(datajson){
        definerowtable= setrowtable;
        triggerasesorlookup= 0;

        ajaxdataasesorid= checkNullValue(datajson.ASESOR_ID);
        ajaxdataasesornama= checkNullValue(datajson.NAMA);

        <?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
			$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
		?>
			if(selectedjexcel == <?=$infoid?>)
			{
				vardatajexcel<?=$infoid?>.ignoreEvents = true;
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqAsesorId', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdataasesorid, true);
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqAsesorNama', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdataasesornama, true);
		        vardatajexcel<?=$infoid?>.ignoreEvents = false;
			}
	    <?
		}
	    ?>

        triggerasesorlookup= 1;

    }, complete: function () {setvalidasicolor(selectedjexcel, definerowtable); geni.unblock(setselectedcell);}});

}


function dataasesor(selectedjexcel)
{
	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
		$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
	?> 
        // console.log(selectedjexcel);
		if(selectedjexcel == <?=$infoid?>)
		{
		    dataexcel = $('#vardatajexcel<?=$infoid?>').jexcel('getData');
		    p_data= dataexcel.length;
		    // console.log(p_data);
		    separator= infodata= "";
		    for(idata=0; parseInt(idata) < p_data; idata++)
		    {
		        arrdata= "";
		        arrdata= dataexcel[idata];
		        dataasesorid= dataexcel[idata][<?=array_search('reqAsesorId', array_column($arrkolomdata, 'val'))?>];

		        if(infodata == "")
		        	separator= "";
		        else
		        	separator= ",";

		        if(dataasesorid == ""){}
		    	else
		    		infodata= infodata+separator+dataasesorid;
		    }
		    return infodata;
		}
	<?
	}
    ?>
}


function dataasesorpopup(selectedjexcel)
{
	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
		$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
	?> 
		 dataexcel = $('#vardatajexcel<?=$infoid?>').jexcel('getData');
		 p_data= dataexcel.length;
		    // console.log(p_data);
		 // console.log(selectedjexcel);

		if(selectedjexcel == <?=$infoid?>)
	    {
			    separator= infodata= "";
			    for(idata=0; parseInt(idata) < p_data; idata++)
			    {
			        arrdata= "";
			        arrdata= dataexcel[idata];
			        dataasesorid= dataexcel[idata][<?=array_search('reqAsesorId', array_column($arrkolomdata, 'val'))?>];

			        if(infodata == "")
			        	separator= "";
			        else
			        	separator= ",";

			        if(dataasesorid == ""){}
			    	else
			    		infodata= infodata+separator+dataasesorid;
			    }
			    return infodata;
		}
	<?
	}
    ?>
}

jQuery.hotkeys.options.filterInputAcceptingElements=false;
$(document).bind('keydown', 'ctrl+space', function () {
	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
		$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
	?> 
		if (vardatajexcel<?=$infoid?>.getSelectedColumns().length == 1 && vardatajexcel<?=$infoid?>.getSelectedRows().length == 1) {
			definecoltable = vardatajexcel<?=$infoid?>.getSelectedColumns(true)[0];
			definerowtable = vardatajexcel<?=$infoid?>.getSelectedRows(true)[0];
			selectedCell= vardatajexcel<?=$infoid?>.getCellFromCoords(definecoltable,definerowtable);

			selectedTable = 'vardatajexcel<?=$infoid?>';
			selectedjexcel= selectedTable ;

			selectedjexcel= String(selectedjexcel);
			selectedjexcel= selectedjexcel.split('vardatajexcel'); 
			selectedjexcel= selectedjexcel[1];
			// console.log(selectedjexcel);
		}

	<?
	}
    ?>

	if (selectedTable == null){
		return false;
	}

	$(':focus').blur();

	var urllink= valrowid= valpencarian= valregionalid= valunitid= valwhid= "";

	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalAcara[$index_loop]["JADWAL_ACARA_ID"];
		$infonama= $arrJadwalAcara[$index_loop]["PENGGALIAN_NAMA"];
	?> 
		if (selectedjexcel == '<?=$infoid?>')
		{
			if (definecoltable == "<?=array_search('reqAsesorNama', array_column($arrkolomdata, 'val'))?>")
			{
				reqCallMode= "OptionSetAsesorDetil";
				urllink= "cari";
			}
		}
	<?
	}
    ?>

	if(urllink == ""){}
	else
	{
		
		var reqAsesorId = tempPegawaiId= separatorTempRowId= anSelectedId= "";
		selectedasesorid= dataasesorpopup(selectedjexcel);
		// console.log(selectedasesorid);
		var rownum ='';
		parent.OpenDHTML('asesor_jadwal_pilih_pencarian_export.php?reqId=<?=$reqId?>&reqRowId='+rownum+'&reqAsesorId='+selectedasesorid, 'Pencarian Asesor', 780, 500)

	}

	});
</script>
</body>
<style type="text/css">


    .jexcel > div.jexcel-content
    {
        display: inline !important;
    }

    .jexcel_pagination {
     
    }

    .jdropdown-container {
        position: relative !important;
    }

    [type="checkbox"] {
        left: auto !important;
        opacity: 1 !important;
        position: relative !important;
    }

    .jexcel .invalid {
        background-color: #ff4c42;
    }

    .jexcel > tbody > tr > td.readonly {
        background-color: #F3F3F3;
        color: black !important;
    }

    .jexcel .readonlyvalueinvalidtrue {
        background-color: #ff4c42;
    }

    .jexcel > thead > tr > td
    {
        z-index: 1 !important;
    }

    .jexcel > tbody > tr > td.onvalueupdating{
        background-color: #06d79c !important;
        font-weight:500;
    }

    .jexcel > tbody > tr > td.onqtyupdating{
        background-color: #4DB4D7 !important;
        font-weight:500;
    }
</style>
</html>