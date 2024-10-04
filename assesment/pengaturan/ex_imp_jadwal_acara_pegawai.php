<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalPegawai.php");

$set= new JadwalTes();

$reqId = httpFilterGet("reqId");

// echo $reqId;exit;

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
$arrJadwalPegawai="";
$arrJadwalPegawai=array();
$statement= " AND B.JADWAL_TES_ID = ".$reqId;
$set= new JadwalPegawai();
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
	$arrJadwalPegawai[$index_loop]["JADWAL_ACARA_ID"]= $set->getField("JADWAL_ACARA_ID");
	// $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
	$arrJadwalPegawai[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_KODE")." - ";
	$arrJadwalPegawai[$index_loop]["ASESOR_NAMA"]= $set->getField("ASESOR_NAMA");
	$arrJadwalPegawai[$index_loop]["KETERANGAN_ACARA"]= $set->getField("KETERANGAN_ACARA");
	$arrJadwalPegawai[$index_loop]["JAM"]= $set->getField("PUKUL1")." s/d ".$set->getField("PUKUL2");
	$index_loop++;
}
$jumlahjadwalacara= $index_loop;
// print_r($arrJadwalPegawai);exit;
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
	var selectedjexcel = "<?= $arrJadwalPegawai[0]["JADWAL_ASESOR_ID"]?>";
	$(function(){
		$( "#tabs" ).tabs({
			activate: function(event, ui) {
				var selectedTab = $('#tabs').tabs('option', 'active');
				// console.log(selectedTab);
		        // selectedjexcel =  ui.newTab.attr('li',"tab-id")[0].getAttribute("tab-id")
		        // console.log("--"+selectedjexcel);
		    }
		});

		// $("#tabs").tabs({disabled: [0,1]});

		// $("#tabs > li").click(function(){
		// 	console.log($(this).prop('id'));
		// 	if($(this).hasClass("disabled"))
		// 		return false;
		// });
		// $( "#tabs" ).tabs();
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
			$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
			$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
		?>
		// cek simpan percobaan
	        var dataexcel = $('#vardatajexcel<?=$infoid?>').jexcel('getData');
	        // console.log(dataexcel);
	        formData.append('reqData[<?=$infoid?>]', JSON.stringify(dataexcel));
        	// return false;
		<?
		}
		?>

		$(function(){
			$.ajax({
	          url: "../json-pengaturan/ex_imp_jadwal_acara_pegawai.php",
	          data: formData,
	          processData: false,
	          contentType: false,
	          type: 'POST'
	          , beforeSend: function () {geni.block('body');}
	          , success: function(data) {
	          	// console.log(data);return false;
	          	document.location.href = "ex_imp_jadwal_acara_pegawai.php?reqId=<?=$reqId?>";
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
				<div id="header-tna-detil">Export / Import <span>Jadwal Pegawai</span></div>
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
				$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
				$infonama= $arrJadwalPegawai[$index_loop]["KETERANGAN_ACARA"];
				$infonamaasesor= $arrJadwalPegawai[$index_loop]["ASESOR_NAMA"];

			?>
			<li><a href="#tabs-<?=$infoid?>"><?=$infonama.'&nbsp-&nbsp'.$infonamaasesor?></a></li>
			<?
			}
			?>
		</ul>
		<?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
			$infonama= $arrJadwalPegawai[$index_loop]["KETERANGAN_ACARA"];
			$infonamaasesor= $arrJadwalPegawai[$index_loop]["ASESOR_NAMA"];

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
<?
    $arrkolomdata= array(
        array("val"=>"reqCatalogId", "nama"=>"id", "width"=>"")
        , array("val"=>"reqCheckDelete", "nama"=>"Delete", "width"=>"80")
        , array("val"=>"reqLineNo", "nama"=>"No.", "width"=>"80")
        , array("val"=>"reqAsesorId", "nama"=>"reqAsesorId", "width"=>"")
        , array("val"=>"reqPegawaiId", "nama"=>"reqPegawaiId", "width"=>"")
        , array("val"=>"reqNipPegawai", "nama"=>"Nip", "width"=>"200")
        , array("val"=>"reqPegawaiNama", "nama"=>"Nama Pegawai", "width"=>"250")
        , array("val"=>"reqEselon", "nama"=>"Eselon", "width"=>"200")
        , array("val"=>"reqJabatan", "nama"=>"Jabatan", "width"=>"200")

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
        if(in_array($findcolumn, array("reqCatalogId", "reqPegawaiId","reqAsesorId")))
            $type .= ", type:'hidden'";
        else if(in_array($findcolumn, array("reqCheckDelete")))
            $type .= ", type: 'checkbox'";
        else if(in_array($findcolumn, array("reqLineNo","reqPegawaiNama","reqEselon","reqJabatan")))
            $type .= ", readOnly: true " ;
        else if(in_array($findcolumn, array("reqNipPegawai")))
            $type .= ", type: 'pegawailookup' ";
        

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
}

var changevardatajexcel = function (instance, cell, x, y, value, old) {
    if (value == old) {
        return false;
    }

    selectedJexcel= $(instance).prop('id');
    selectedJexcel= String(selectedJexcel);
    selectedJexcel= selectedJexcel.split('vardatajexcel'); 
	selectedJexcel= selectedJexcel[1];

    selectedCell= cell;
    definecoltable= x;
    definerowtable= y;

    if (x == <?=array_search('reqNipPegawai', array_column($arrkolomdata, 'val'))?> && triggerasesorlookup == 1)
    {
        setpegawailookup(value, selectedCell, selectedJexcel, definerowtable);
    }
};

<?
for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
{
	$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
	$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
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
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
	?>
		if(rowid == <?=$infoid?>)
		{
		    vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqLineNo', array_column($arrkolomdata, 'val'))?>, rowNumber]).classList.remove('readonly');
		    $('#'+cellIRowIdtable).jexcel('setValue', codeexceltochar(<?=array_search('reqLineNo', array_column($arrkolomdata, 'val'))?>)+rowNumberIndex, r_data);
		    vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqLineNo', array_column($arrkolomdata, 'val'))?>, rowNumber]).classList.add('readonly');

		    $("#tabs-<?=$infoid?>").add('disabled');
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
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
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
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
?>
	urlAjax= "../json-pengaturan/ex_imp_jadwal_acara_pegawai_data.php?reqMode=viewdetil&reqId=<?=$infoid?>";
	$.ajax({
	    'url': urlAjax, 
	    dataType: 'json', 
	    'success': function(datajson){
	        vardatajexcel<?=$infoid?> = jexcel(document.getElementById('vardatajexcel<?=$infoid?>'), $.extend({data: datajson, columns: jlinecolumns, onchange: changevardatajexcel, oninsertrow: insertedRow, onbeforedeleterow: beforedeleterow}, jexcelOptions));
	    }
	});

<?
	}
}
?>

function pegawailookup(id)
{
	// console.log(id);
	setpegawailookup(id, selectedCell, definerowtable, "", "");
}

function OptionSetPegawaiDetil(id, rowid)
{
	var arrId = new Array();
	arrId = id.split(",");
	var count = arrId.length;
	temp = arrId[0];
	value= temp;

	// console.log("selectedjexcel"+selectedjexcel);

	var itab= [];
	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
	?>
		if(selectedjexcel == "<?=$infoid?>")
		{
            definerowtable= jumlahlastrow= vardatajexcel<?=$infoid?>.options.data.length;
		}
		else
		{
			itab.push(parseInt("<?=$index_loop?>"));
			// if(itab == "")
			// 	itab= "<?=$index_loop?>";
			// else
			// 	itab= itab+",<?=$index_loop?>";
		}
	<?
	}
	?>

	// console.log(itab);
	$("#tabs").tabs({disabled: itab});
	// $("#tabs").tabs({disabled: [0,2,3,4,5]});
	// $("#tabs").tabs({disabled: [0,1]});


	if(jumlahlastrow >= 1)
		definerowtable= parseInt(definerowtable) - 1;

	optionarrayset(id, rowid, definerowtable, value, 0, arrId);

}

var ajaxdatapegawaiid= ajaxdatapegawainama= tempDepartemen= tempNama= tempKelas= "";
function optionarrayset(id, setselectedcell, setrowtable , value, recursiveindex, arrId)
{
	definerowtable= setrowtable;
    selectedpegawaiid= datapegawaipopup(selectedjexcel);
    urlAjax= "../json-pengaturan/ex_imp_jadwal_acara_pegawai_data.php?reqMode=pegawai&reqRowId="+selectedpegawaiid+"&reqPegawaiId="+value;
    $.ajax({'url': urlAjax, dataType: "json", beforeSend: function () {geni.block(setselectedcell);}, 'success': function(datajson){
    	definerowtable= setrowtable;
    	triggerasesorlookup= 0;
    	
        ajaxdatapegawaiid= checkNullValue(datajson.PEGAWAI_ID);
        ajaxdatapegawainama= checkNullValue(datajson.PEGAWAI_NAMA);
        ajaxdatapegawainip= checkNullValue(datajson.PEGAWAI_NIP);
        ajaxdatapegawaigol= checkNullValue(datajson.NAMA_GOL);
        ajaxdatapegawaieselon= checkNullValue(datajson.PEGAWAI_ESELON);
        ajaxdatapegawaijabatan= checkNullValue(datajson.PEGAWAI_JAB_STRUKTURAL);

        <?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
			$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
		?>
			// console.log(selectedjexcel);
			if(selectedjexcel == <?=$infoid?>)
			{
				vardatajexcel<?=$infoid?>.ignoreEvents = true;
				vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqPegawaiId', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawaiid, true);
				vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqPegawaiNama', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawainama, true);
				vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqNipPegawai', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawainip, true);
				vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqEselon', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawaieselon, true);
				vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqJabatan', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawaijabatan, true);
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
			$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
			$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
		?>

			if(selectedjexcel == <?=$infoid?>)
			{
				vardatajexcel<?=$infoid?>.insertRow(1);
				definecoltable= "<?=array_search('reqPegawaiNama', array_column($arrkolomdata, 'val'))?>";
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
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
	?>
		if(selectedjexcel == <?=$infoid?>)
		{
			validasicolor = vardatajexcel<?=$infoid?>.getValueFromCoords(<?=array_search('reqPegawaiId', array_column($arrkolomdata, 'val'))?>, y);
		    if(validasicolor == '')
		    {
		        $(vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqNipPegawai', array_column($arrkolomdata, 'val'))?>, y])).addClass('invalid');
		    }
		    else
		    {
		        $(vardatajexcel<?=$infoid?>.getCell([<?=array_search('reqNipPegawai', array_column($arrkolomdata, 'val'))?>, y])).removeClass('invalid');
		    }
		}
	<?
	}
    ?>
}


function setpegawailookup(id, setselectedcell, selectedjexcel, setrowtable)
{
    definerowtable= setrowtable;

    selectedasesorid= dataasesor(selectedjexcel);

    // console.log(selectedasesorid);
    urlAjax= "../json-pengaturan/ex_imp_jadwal_acara_pegawai_data.php?reqMode=pegawai&reqRowId="+selectedasesorid+"&reqJadwalTesId=<?=$reqId?>&reqPencarian="+encodeURIComponent(id);
    $.ajax({'url': urlAjax, dataType: "json", beforeSend: function () {geni.block(setselectedcell);}, 'success': function(datajson){
        definerowtable= setrowtable;
        triggerasesorlookup= 0;

        ajaxdatapegawaiid= checkNullValue(datajson.PEGAWAI_ID);
        ajaxdatapegawainama= checkNullValue(datajson.PEGAWAI_NAMA);
        ajaxdatapegawainip= checkNullValue(datajson.PEGAWAI_NIP);
        ajaxdatapegawaigol= checkNullValue(datajson.NAMA_GOL);
        ajaxdatapegawaieselon= checkNullValue(datajson.PEGAWAI_ESELON);
        ajaxdatapegawaijabatan= checkNullValue(datajson.PEGAWAI_JAB_STRUKTURAL);
        // console.log(ajaxdataasesorid);

        var itab= [];
        <?
		for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
		{
			$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
			$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
		?>
			if(selectedjexcel == <?=$infoid?>)
			{
				vardatajexcel<?=$infoid?>.ignoreEvents = true;
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqPegawaiId', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawaiid, true);
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqPegawaiNama', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawainama, true);
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqNipPegawai', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawainip, true);
		       
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqEselon', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawaieselon, true);
		        vardatajexcel<?=$infoid?>.setValueFromCoords(<?=array_search('reqJabatan', array_column($arrkolomdata, 'val'))?>, definerowtable, ajaxdatapegawaijabatan, true);
		        vardatajexcel<?=$infoid?>.ignoreEvents = false;
			}
			else
			{
				itab.push(parseInt("<?=$index_loop?>"));
			}
	    <?
		}
	    ?>
	    $("#tabs").tabs({disabled: itab});
        triggerasesorlookup= 1;

    }, complete: function () {setvalidasicolor(selectedjexcel, definerowtable); geni.unblock(setselectedcell);}});

}

function dataasesor(selectedjexcel)
{
	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
	?>
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
		        dataasesorid= dataexcel[idata][<?=array_search('reqPegawaiId', array_column($arrkolomdata, 'val'))?>];

		        // console.log(dataasesorid);

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


function datapegawaipopup(selectedjexcel)
{
	<?
	for($index_loop=0; $index_loop < $jumlahjadwalacara; $index_loop++)
	{
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
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
		        dataasesorid= dataexcel[idata][<?=array_search('reqPegawaiId', array_column($arrkolomdata, 'val'))?>];

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
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
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
			// console.log("selectedjexcelpres:"+selectedjexcel);
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
		$infoid= $arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		$infopenggalianid= $arrJadwalPegawai[$index_loop]["PENGGALIAN_ID"];
		$infonama= $arrJadwalPegawai[$index_loop]["PENGGALIAN_NAMA"];
	?> 
		if (selectedjexcel == '<?=$infoid?>')
		{
			if (definecoltable == "<?=array_search('reqNipPegawai', array_column($arrkolomdata, 'val'))?>")
			{
				reqCallMode= "OptionSetPegawaiDetil";
				valrowid= "<?=$infopenggalianid?>";
				urllink= "cari";
			}
		}
	<?
	}
    ?>

    if(urllink == ""){}
	else
	{
		
		var reqPegawaiId = tempPegawaiId= separatorTempRowId= anSelectedId= "";
		selectedpegawaiid= datapegawaipopup(selectedjexcel);
		// console.log(selectedpegawaiid);
		// return false;
		var rownum ='';

		parent.OpenDHTML('pegawai_simulasi_detil_pencarian_export.php?reqId=<?=$reqId?>&reqRowId='+selectedpegawaiid+'&reqPegawaiId='+valrowid+'&reqJadwalTesId=<?=$reqId?>', 'Pencarian Pegawai', 780, 500)

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