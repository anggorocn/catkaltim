<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaAssesment.php");
include_once("../WEB/classes/base/FormulaAssesmentUjianTahap.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
// include_once("../WEB/classes/base-cat/UjianPegawai.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data data terlebih dahulu.');";	
	echo "window.parent.location.href = 'formula_assesment_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

$ujian_tahap= new FormulaAssesmentUjianTahap();
$tipe_ujian= new TipeUjian();
// $ujian_pegawai= new UjianPegawai();

$statement= " AND A.FORMULA_ID = ".$reqId;
$set= new FormulaAssesment();
$set->selectByParams(array("FORMULA_ID"=> $reqId),-1,-1,'');
$set->firstRow();
$tempFormula= $set->getField('FORMULA');
$tempFormulaTahun= $set->getField('TAHUN');
$tempFormulaKeterangan= $set->getField('KETERANGAN');
unset($set);

$index_data= 0;
$arrData="";
$statement= " AND A.FORMULA_ASSESMENT_ID = ".$reqId;
$ujian_tahap->selectByParamsMonitoring(array(), -1,-1, $statement);
// echo $ujian_tahap->query;exit;
while($ujian_tahap->nextRow())
{	
	$arrData[$index_data]["UJIAN_ID"]= $ujian_tahap->getField("UJIAN_ID");
	$arrData[$index_data]["TIPE_UJIAN_ID"]= $ujian_tahap->getField("TIPE_UJIAN_ID");
	$arrData[$index_data]["TIPE"]= $ujian_tahap->getField("TIPE");
	$arrData[$index_data]["UJIAN_TAHAP_ID"]= $ujian_tahap->getField("UJIAN_TAHAP_ID");
	$arrData[$index_data]["JUMLAH_SOAL_UJIAN_TAHAP"]= $ujian_tahap->getField("JUMLAH_SOAL_UJIAN_TAHAP");
	$arrData[$index_data]["BOBOT"]= $ujian_tahap->getField("BOBOT");
	$arrData[$index_data]["MENIT_SOAL"]= $ujian_tahap->getField("MENIT_SOAL");
	$arrData[$index_data]["JUMLAH_SOAL"]= $ujian_tahap->getField("JUMLAH_SOAL");
	$arrData[$index_data]["ID"]= $ujian_tahap->getField("ID");
	$arrData[$index_data]["PARENT_ID"]= $ujian_tahap->getField("PARENT_ID");
	$arrData[$index_data]["TIPE_READONLY"]= $ujian_tahap->getField("TIPE_READONLY");
	$arrData[$index_data]["STATUS_ANAK"]= $ujian_tahap->getField("STATUS_ANAK");
	$index_data++;
}
$jumlah_data = $index_data;
// print_r($arrData);exit();

// cek kalau ada data maka simulasi tutup
$statementdetil= " AND FORMULA_ASSESMENT_ID = ".$reqId." GROUP BY FORMULA_ASSESMENT_ID";
$set_detil= new FormulaAssesment();
$tempJumlahPegawai= $set_detil->getCountByParamsPegawaiJadwalFormula($statementdetil);
// echo $set_detil->query;exit();
// $tempJumlahPegawai=0;

$statement= " AND PARENT_ID = '0'";
$index_tipe= 0;
$arrDataTipe="";
$tipe_ujian->selectByParamsJumlahSoal(array(), -1,-1,$statement);
// echo $tipe_ujian->query;exit;
while($tipe_ujian->nextRow())
{
	$arrDataTipe[$index_tipe]["TIPE_UJIAN_ID"]= $tipe_ujian->getField("TIPE_UJIAN_ID");
	$arrDataTipe[$index_tipe]["TIPE"]= $tipe_ujian->getField("TIPE");
	$arrDataTipe[$index_tipe]["JUMLAH_SOAL"]= $tipe_ujian->getField("JUMLAH_SOAL");
	$arrDataTipe[$index_tipe]["WAKTU"]= $tipe_ujian->getField("WAKTU");
	$arrDataTipe[$index_tipe]["TIPE_READONLY"]= $tipe_ujian->getField("TIPE_READONLY");
	$arrDataTipe[$index_tipe]["STATUS_ANAK"]= $tipe_ujian->getField("STATUS_ANAK");
	$arrDataTipe[$index_tipe]["ID"]= $tipe_ujian->getField("ID");
	$index_tipe++;
}
// print_r($arrDataTipe);exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<script type="text/javascript">

function checkNan(value)
{
	if(typeof value == "undefined" || isNaN(value))
	return 0;
	else
	return value;
}

function iecompattest(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
{
	var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
	var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
	
	opWidth = iecompattest().clientWidth - 5;
	opHeight = iecompattest().clientHeight - 45;
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
}

function setJumlahSoal()
{
	tabBody=document.getElementsByTagName("TBODY").item(1);
	var rownum= tabBody.rows.length;
	
	var reqJumlahSoalUjianTahapVal= reqMenitSoalVal= 0;
	for(var i=0; i<=rownum; i++) 
	{
		if(typeof $("#reqJumlahSoalUjianTahap"+i).val() == "undefined" || $("#reqJumlahSoalUjianTahap"+i).val() == "")
		{}
		else
		{
			reqJumlahSoalUjianTahap= $("#reqJumlahSoalUjianTahap"+i).val();
			reqJumlahSoalUjianTahapVal= parseInt(reqJumlahSoalUjianTahapVal)+parseInt(reqJumlahSoalUjianTahap);
		}
		
		if(typeof $("#reqMenitSoal"+i).val() == "undefined" || $("#reqMenitSoal"+i).val() == "")
		{}
		else
		{
			reqMenitSoal= $("#reqMenitSoal"+i).val();
			reqMenitSoalVal= parseInt(reqMenitSoalVal)+parseInt(reqMenitSoal);
		}
	}

	$("#reqTotalWaktu").val(reqMenitSoalVal);
	$("#reqJumlahSoal").val(reqJumlahSoalUjianTahapVal);
}

function addRow()
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(1);
	
	
	var rownum= tabBody.rows.length;
	
	var reqTipeUjianVal= "";
	for(var i=0; i<=rownum; i++) 
	{
		if(typeof $("#reqTipeUjian"+i).val() == "undefined")
		{}
		else
		{
			reqTipeUjian= $("#reqTipeUjian"+i).val();
			if(reqTipeUjianVal == "")
			{
				reqTipeUjianVal= reqTipeUjian;
			}
			else
			{
				reqTipeUjianVal= reqTipeUjianVal+","+reqTipeUjian;
			}
		}
	}
	//alert(reqTipeUjianVal);
	reqArrTipeUjian= reqTipeUjianVal.split(',');
	row=document.createElement("TR");
	
	cell = document.createElement("TD");
	var button = document.createElement("select");
	button.setAttribute("id", "reqInfoTipeUjian"+rownum);
	
	var totalDataTipe=0;
	var tempStatusAnak=0;
	<?
	for($row=0; $row < count($arrDataTipe); $row++)
	{
	?>
		var tempTipeInfoUjianId= '<?=$arrDataTipe[$row]["TIPE_UJIAN_ID"]."-".$arrDataTipe[$row]["JUMLAH_SOAL"]."-".$arrDataTipe[$row]["TIPE_READONLY"]."-".$arrDataTipe[$row]["WAKTU"]."-".$arrDataTipe[$row]["STATUS_ANAK"]."-".$arrDataTipe[$row]["ID"]?>';
		var tempTipeUjianId= '<?=$arrDataTipe[$row]["TIPE_UJIAN_ID"]?>';
		var tempTipeUjianNama= '<?=$arrDataTipe[$row]["TIPE"]?>';
		var tempStatusAnak= '<?=$arrDataTipe[$row]["STATUS_ANAK"]?>';
		//alert(tempStatusAnak+";"+tempTipeUjianId);
		var elementRow= reqArrTipeUjian.indexOf(tempTipeUjianId);
		if(elementRow == -1)
		{
			var option = document.createElement('option');
			option.setAttribute('value', tempTipeInfoUjianId);
			option.appendChild( document.createTextNode(tempTipeUjianNama) );
			button.appendChild(option);
			totalDataTipe++;
		}
	<?
	}
	?>
	cell.style.textAlign="center";
	cell.appendChild(button);
	row.appendChild(cell);
	
	if(totalDataTipe == 0)
	return false;
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '<input type="text" name="reqJumlahSoalUjianTahap[]" style="width:50px;" class="easyui-validatebox" id="reqJumlahSoalUjianTahap'+rownum+'" />';
	cell.style.textAlign="center";
	cell.appendChild(button);
	row.appendChild(cell);	
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '<input type="text" name="reqMenitSoal[]" style="width:30px;" class="easyui-validatebox" id="reqMenitSoal'+rownum+'" />';
	cell.style.textAlign="center";
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '<input type="hidden" name="reqRowId[]" id="reqRowId'+rownum+'" />'
	+'<input type="hidden" name="reqStatusAnak[]" id="reqStatusAnak'+rownum+'" />'
	+'<input type="hidden" name="reqIdTree[]" id="reqIdTree'+rownum+'" />'
	+'<input type="hidden" name="reqTipeUjian[]" id="reqTipeUjian'+rownum+'" />'
	+'<input type="hidden" name="reqUjianTahapId[]" id="reqUjianTahapId'+rownum+'" />'
	+'<center><a style="cursorointer" onclick="deleteRowDrawTable(\'tableOrder\', this)"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>';
	cell.appendChild(button);
	row.appendChild(cell);
		  
	tabBody.appendChild(row);
	
	var rowCount = tabBody.rows.length;
	rowCount= rowCount-1;
	setInfoTipeUjian(rowCount);
	
	$('#reqJumlahSoalUjianTahap'+rowCount).validatebox({  
		required: true
	});
		
	$('#reqMenitSoal'+rowCount).validatebox({  
		required: true
	});
	
	$('select[id^="reqInfoTipeUjian"]').change(function() {
		var id= $(this).attr('id');
		id= id.replace("reqInfoTipeUjian", "")
		//alert('--'+id);
		setInfoTipeUjian(id);
	});
	
	$('input[id^="reqJumlahSoalUjianTahap"], input[id^="reqMenitSoal"]').keyup(function() {
		var id= $(this).attr('id');
		id= id.replace("reqJumlahSoalUjianTahap", "")
		//alert('--'+id);
		setJumlahSoal();
	});
	
	$("input[id^='reqMenitSoal'], input[id^='reqJumlahSoalUjianTahap']").keypress(function(e) {
		//alert(e.which);
		//if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});
}

function setInfoTipeUjian(id)
{
	var reqInfoTipeUjian= reqTipeUjian= reqJumlahSoalUjianTahap= reqTipeReadonly= reqWaktu= reqStatusAnak= reqIdTree= "";
	reqInfoTipeUjian= $("#reqInfoTipeUjian"+id).val();
	
	reqInfoTipeUjian= reqInfoTipeUjian.split('-');
	reqTipeUjian= reqInfoTipeUjian[0];
	reqJumlahSoalUjianTahap= reqInfoTipeUjian[1];
	reqTipeReadonly= reqInfoTipeUjian[2];
	reqWaktu= reqInfoTipeUjian[3];
	reqStatusAnak= reqInfoTipeUjian[4];
	reqIdTree= reqInfoTipeUjian[5];
	//alert(id);
	$("#reqTipeUjian"+id).val(reqTipeUjian);
	$("#reqJumlahSoalUjianTahap"+id).val(reqJumlahSoalUjianTahap);
	$("#reqMenitSoal"+id).val(reqWaktu);
	$("#reqStatusAnak"+id).val(reqStatusAnak);
	$("#reqIdTree"+id).val(reqIdTree);
	
	// console.log(reqTipeUjian);
	$("#reqJumlahSoalUjianTahap"+id).show();
	if(reqTipeUjian == 16)
	$("#reqJumlahSoalUjianTahap"+id).hide();

	if(reqStatusAnak == 1)
	{
		$("#reqJumlahSoalUjianTahap"+id).prop('type', 'hidden');
		$("#reqMenitSoal"+id).prop('type', 'hidden');
		$("#reqJumlahSoalUjianTahap"+id).val("0");
		$("#reqMenitSoal"+id).val("0");
	}
	else
	{
		$("#reqJumlahSoalUjianTahap"+id).prop('type', 'text');
		$("#reqMenitSoal"+id).prop('type', 'text');
	}
	
	$("#reqJumlahSoalUjianTahap"+id).prop('readonly', false);
	if(reqTipeReadonly == 1)
	$("#reqJumlahSoalUjianTahap"+id).prop('readonly', true);
	
	
	setJumlahSoal();
}

function lookupDetil(row){
	var reqUjianTahapId= reqId= "";
	reqUjianTahapId= $("#reqUjianTahapId"+row).val();
	reqId= $("#reqId").val();
	
	OpenDHTML("ujian_add_bank_soal.php?reqLihat=1&reqUjianTahapId="+reqUjianTahapId+"&reqId="+reqId, "CAT Online - Angkasa Pura I Support", 1150, 600);
}
    
function deleteRowDrawTable(tableID, id) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) {
			table.deleteRow(i);
			setJumlahSoal();
		}
	}
	}catch(e) {
		alert(e);
	}
}

function deleteRowDrawTablePhp(tableID, id, rowId, tempId, mode) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) 
		{
			var valRowId= $("#"+tempId+rowId).val();
			$.getJSON('../json-pengaturan/delete.php?reqMode='+mode+'&id='+valRowId, function (data) 
			{
			});
			
			table.deleteRow(i);
			setJumlahSoal();
		}
	}
	}catch(e) {
		alert(e);
	}
}

$(function(){
	setJumlahSoal();
	$('#ff').form({
		url:'../json-pengaturan/formula_assesment_add_ujian_tahap.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			// console.log(data);return false;
			$.messager.alert('Info', data, 'info');
			// $('#rst_form').click();
			document.location.href= "formula_assesment_add_ujian_tahap.php?reqId=<?=$reqId?>";
		}
	});
	
	$('input[id^="reqJumlahSoalUjianTahap"], input[id^="reqMenitSoal"]').keyup(function() {
		var id= $(this).attr('id');
		id= id.replace("reqJumlahSoalUjianTahap", "")
		//alert('--'+id);
		setJumlahSoal();
	});

});

</script>

<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
	<div id="content" style="height:auto; width:100%">
    <form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="100%">
        <tr>
            <td colspan="3">
            <div id="header-tna-detil">Simulasi <span>Soal</span></div>
            </td>			
        </tr>
        <tr>           
            <td style="width:150px">Tahun</td><td style="width:5px">:</td>
            <td><?=$tempFormulaTahun?></td>
        </tr>
        <tr>
            <td>Formula</td><td>:</td>
            <td><?=$tempFormula?></td>	
        </tr>
        <tr>
            <td>Keterangan</td><td>:</td>
            <td><?=$tempFormulaKeterangan?></td>
        </tr>
        <?
		if($reqId == ""){}
		else
		{
		if($tempJumlahPegawai > 0){}
		else
		{
        ?>
        <tr>
            <td>
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqMode" value="insert">
                <input type="hidden" name="reqTahun" value="<?=$tempFormulaTahun?>">
                <input type="submit" value="Simulasi" />
            </td>
        </tr>
        <?
    	}
		}
		?>
    </table>
    
    <table class="gradient-style" id="tableOrder" style="width:99%; margin-left:2px">
        <thead class="altrowstable">
          <tr>
              <th style="width:100px">Tipe Ujian
              	<?
              	if($tempJumlahPegawai > 0){}
				else
				{
		        ?>
                <a style="cursor:pointer" title="Tambah Rincian" onclick="addRow()"><img src="../WEB/images/icn_add.gif" width="16" height="16" border="0" /></a>
                <?
            	}
                ?>
              </th>
              <th style="width:50px">Jumlah Soal</th>
              <th style="width:50px">Menit</th>
              <th style="width:8%">Aksi</th>
          </tr>
        </thead>
        <tbody class="example altrowstable" id="alternatecolor"> 
          <?
		  $i = 1;
          $unit = 0;
		  $harga = 0;
		  $jumlah = 0;		  
          for($checkbox_index=0;$checkbox_index<$jumlah_data;$checkbox_index++)
          {
			  $tempId= $arrData[$checkbox_index]["ID"];
			  $tempParentId= $arrData[$checkbox_index]["PARENT_ID"];
			  $tempTipeUjianId= $arrData[$checkbox_index]["TIPE_UJIAN_ID"];
			  $tempTipeReadOnly= $arrData[$checkbox_index]["TIPE_READONLY"];
			  $tempStatusAnak= $arrData[$checkbox_index]["STATUS_ANAK"];
			  
			  $tempStyle= "text-align:center;";
			  if($tempParentId == "0")
			  {
				  $tempStyle= "text-align:left;";
				  //background-color:#999; padding-left:10px; 
			  }
          ?>                      
              <tr id="node-<?=$i?>">
              	  <?
				  if($tempStatusAnak == "1")
				  {
                  ?>
                  <td colspan="3" style=" background-color:#999; padding-left:10px">
                  <input type="hidden" name="reqTipeUjian[]" id="reqTipeUjian<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["TIPE_UJIAN_ID"]?>">
                  <input type="hidden" name="reqJumlahSoalUjianTahap[]" id="reqJumlahSoalUjianTahap<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["JUMLAH_SOAL_UJIAN_TAHAP"]?>" />
                  <input type="hidden" name="reqMenitSoal[]" id="reqMenitSoal<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["MENIT_SOAL"]?>" />
                  <?=$arrData[$checkbox_index]["TIPE"]?>
                  </td>
                  <?
				  }
				  else
				  {
                  ?>
                  <td style=" <?=$tempStyle?>">
                  <input type="hidden" name="reqTipeUjian[]" id="reqTipeUjian<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["TIPE_UJIAN_ID"]?>">
                  <?=$arrData[$checkbox_index]["TIPE"]?>
                  </td>
                  <td style="text-align:center">
                  <?
                  if($arrData[$checkbox_index]["TIPE_UJIAN_ID"] == "16")
                  {
                  ?>
                  <input type="hidden" name="reqJumlahSoalUjianTahap[]" id="reqJumlahSoalUjianTahap<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["JUMLAH_SOAL_UJIAN_TAHAP"]?>" />
                  <?
                  }
                  else
                  {
                  ?>
                  <input type="text" required name="reqJumlahSoalUjianTahap[]" style="width:50px;" class="easyui-validatebox" id="reqJumlahSoalUjianTahap<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["JUMLAH_SOAL_UJIAN_TAHAP"]?>" />
                  <?
              	  }
                  ?>
                  </td>
                  <td style="text-align:center">
                  <input type="text" required name="reqMenitSoal[]" style="width:30px;" class="easyui-validatebox" id="reqMenitSoal<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["MENIT_SOAL"]?>" />
                  </td>
                  <?
				  }
                  ?>
                  <td>
                  <center>
                  <?
				  if($tempStatusAnak == "1"){}
				  else
				  {
                  ?>
                  <!-- <a style="cursor:pointer" onclick="lookupDetil(<?=$checkbox_index?>)"><img src="../WEB/images/icon-histori.png" width="15" height="15" border="0" /></a> -->
                  <?
				  }
				  // if($tempTipeReadOnly == "1"){}
				  // else
				  // {  

				  if($tempJumlahPegawai > 0){}
				  else
				  {
                  ?>
                  &nbsp;
                  <a style="cursor:pointer" onclick="deleteRowDrawTablePhp('tableOrder', this, '<?=$checkbox_index?>', 'reqRowId', 'formula_assesment_ujian_tahap')"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a>
                  <?
				  }
                  ?>
                  </center>

                  <input type="hidden" name="reqRowId[]" id="reqRowId<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["UJIAN_TAHAP_ID"]?>">
                  <input type="hidden" name="reqStatusAnak[]" id="reqStatusAnak<?=$checkbox_index?>" value="<?=$tempStatusAnak?>" />
                  <input type="hidden" name="reqIdTree[]" id="reqIdTree<?=$checkbox_index?>" value="<?=$tempId?>" />
                  <!-- <input type="hidden" name="reqTipeUjian[]" id="reqTipeUjian<?=$checkbox_index?>" value="<?=$tempTipeUjianId?>" /> -->
                  <input type="hidden" name="reqUjianTahapId[]" id="reqUjianTahapId<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["UJIAN_TAHAP_ID"]?>">
                  </td>
              </tr>
		  <?		  
            $i++;
          }
		  ?>         
        </tbody>   
        <tfoot class="altrowstable">
        	<tr>
            	<td style="text-align:right">Total Soal : &nbsp;&nbsp;&nbsp;</td>
            	<td>
                	<input type="text" name="reqJumlahSoal" readonly id="reqJumlahSoal" style="text-align:center; width:100px; background:inherit; border:none" />
                    <input type="hidden" name="reqTotalWaktu" id="reqTotalWaktu" />
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>  
    </table>
    <script>
	$("input[id^='reqMenitSoal'], input[id^='reqJumlahSoalUjianTahap']").keypress(function(e) {
		//alert(e.which);
		//if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});
	</script>
    </form>
    </div>
</div>
</body>
</html>