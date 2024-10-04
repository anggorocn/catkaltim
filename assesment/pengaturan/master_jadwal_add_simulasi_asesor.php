<?

/* INCLUDE FILE */

include_once("../WEB/classes/utils/UserLogin.php");

include_once("../WEB/classes/utils/FileHandler.php");

include_once("../WEB/functions/string.func.php");

include_once("../WEB/functions/default.func.php");

include_once("../WEB/functions/date.func.php");

include_once("../WEB/classes/base/JadwalTes.php");

include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");

include_once("../WEB/classes/base/JadwalTesSimulasiPegawai.php");

include_once("../WEB/classes/base/Penggalian.php");



/* LOGIN CHECK */

if ($userLogin->checkUserLogin()) 

{ 

	$userLogin->retrieveUserInfo();

}



/* VARIABLE */

$reqMode= httpFilterRequest("reqMode");

$reqId= httpFilterGet("reqId");

$reqRowId= httpFilterGet("reqRowId");

$reqJenisId= httpFilterGet("reqJenisId");



$set= new JadwalTes();

$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');

$set->firstRow();

//echo $set->query;exit;

$tempJumlahRuangan= $set->getField('JUMLAH_RUANGAN');

unset($set);



$set= new JadwalTesSimulasiPegawai();

$jumlah_pegawai_simulasi= $set->getCountByParams(array(), " AND JADWAL_TES_ID = ".$reqId);

unset($set);



$set = new JadwalTesSimulasiAsesor();



$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PUKUL_AWAL = '".$reqRowId."'";

if($reqJenisId == "1")

{

	$statement.= " AND A.PENGGALIAN_ID IS NULL";

}

elseif($reqJenisId == "2")

{

	$statement.= " AND A.PENGGALIAN_ID IS NOT NULL";

}



$set->selectByParamsMonitoring(array(),-1,-1, $statement);

//echo $set->query;exit;

$set->firstRow();

$tempJadwalTesId= $set->getField('JADWAL_TES_ID');

$tempSimulasiNama= $set->getField("NAMA_SIMULASI");

$tempJam= $set->getField("PUKUL_AWAL")." s/d ".$set->getField("PUKUL_AKHIR");

$tempSimulasiPukulAwal= $set->getField("PUKUL_AWAL");

$tempSimulasiPukulAkhir= $set->getField("PUKUL_AKHIR");

$tempSimulasiKelompokJumlah= $set->getField("KELOMPOK_JUMLAH");

$tempSimulasiWaktu= $set->getField("WAKTU");

$tempSimulasiPenggalianId= $set->getField("PENGGALIAN_ID");

$tempSimulasiPenggalianStatus= $set->getField("STATUS_GROUP");



$index_loop= 0;

$arrJadwalAsesor="";

$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PUKUL_AWAL = '".$reqRowId."'";

$set_detil= new JadwalTesSimulasiAsesor();

$set_detil->selectByParamsAsesorMonitoring(array(), -1,-1, $statement);

//echo $set_detil->query;exit;

while($set_detil->nextRow())

{

	$arrJadwalAsesor[$index_loop]["ASESOR_ID"]= $set_detil->getField("ASESOR_ID");

	$arrJadwalAsesor[$index_loop]["ASESOR_NAMA"]= $set_detil->getField("ASESOR_NAMA");

	$arrJadwalAsesor[$index_loop]["TOTAL_JAM_ASESOR"]= $set_detil->getField("TOTAL_JAM_ASESOR");

	$index_loop++;

}

$jumlah_asesor= $index_loop;



if($jumlah_pegawai_simulasi > 0 && $jumlah_asesor > 0)

{

	$tempJumlahLoopAsesor= round($jumlah_pegawai_simulasi / $jumlah_asesor);

}



if($reqRowId == "")

{

	$statement= "

	AND A.PENGGALIAN_ID NOT IN

	(

	SELECT 

	PENGGALIAN_ID

	FROM jadwal_tes_simulasi_asesor

	WHERE PENGGALIAN_ID IS NOT NULL AND JADWAL_TES_ID = ".$reqId."

	GROUP BY PENGGALIAN_ID

	)";

}

else

{

	$statement= "

	AND A.PENGGALIAN_ID IN

	(

	SELECT 

	PENGGALIAN_ID

	FROM jadwal_tes_simulasi_asesor

	WHERE PENGGALIAN_ID IS NOT NULL AND JADWAL_TES_ID = ".$reqId." AND PUKUL_AWAL = '".$reqRowId."'

	GROUP BY PENGGALIAN_ID

	)";

}

$index_loop= 0;

$arrPenggalian="";

$penggalian= new Penggalian();

$penggalian->selectByParamsJadwalTes(array(), -1,-1, $statement, $reqId);

//echo $penggalian->query;exit;

while($penggalian->nextRow())

{

	$arrPenggalian[$index_loop]["PENGGALIAN_ID"]= $penggalian->getField("PENGGALIAN_ID");

	$arrPenggalian[$index_loop]["NAMA"]= $penggalian->getField("NAMA");

	$arrPenggalian[$index_loop]["STATUS_GROUP"]= $penggalian->getField("STATUS_GROUP");

	$index_loop++;

}

$jumlah_penggalian= $index_loop;



if($tempSimulasiPenggalianId == "" && $reqJenisId == "2" && $jumlah_penggalian > 0)

{

	$tempSimulasiPenggalianId= $arrPenggalian[0]["PENGGALIAN_ID"];

}



$statement= " AND JADWAL_TES_ID = ".$reqId;

$set_validasi= new JadwalTesSimulasiAsesor();

$set_validasi->selectByParams(array(), -1,-1, $statement);

$set_validasi->firstRow();

$tempStatusValidasi= $set_validasi->getField("STATUS");

unset($set_validasi);

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

<link href="styles.css" rel="stylesheet" type="text/css" />



<link rel="stylesheet" href="../WEB/lib/autokomplit/jquery-ui.css">

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">

<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>

<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>



<script type="text/javascript" src="../WEB/lib/timepicker/jquery-ui.min.js"></script>

<script type="text/javascript" src="../WEB/lib/timepicker/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="../WEB/lib/timepicker/jquery-ui-sliderAccess.js"></script>



<script src="../WEB/lib/autokomplit/jquery-ui.js"></script>  

<script type="text/javascript" src="../WEB/lib/easyui/easyloader.js"></script>   

<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.validatebox.js"></script>  

<script type="text/javascript" src="../WEB/lib/easyui/plugins/jquery.combo.js"></script>



<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>

<script type="text/javascript">	

	function setLoad()

	{

		document.location.href= 'master_jadwal_add_simulasi_asesor.php?reqId=<?=$reqId?>&reqJenisId=<?=$reqJenisId?>';

	}

	

	function addtime(start_time, end_time){

		//var startArr = start_time.replace('hrs','',start_time).split('.');

		//var endArr = end_time.replace('hrs','',end_time).split('.');

		

		var startArr = start_time.split(':');

		var endArr = end_time.split(':');

		

		var d = new Date();

		startArr[0] = (startArr[0]) ? parseInt(startArr[0], 10) : 0;

		startArr[1] = (startArr[1]) ? parseInt(startArr[1], 10) : 0;

		endArr[0] = (endArr[0]) ? parseInt(endArr[0], 10) : 0;

		endArr[1] = (endArr[1]) ? parseInt(endArr[1], 10) : 0;

		

		d.setHours(startArr[0] + endArr[0]);

		d.setMinutes(startArr[1] + endArr[1]);   

		

		var hours = d.getHours();

		var minutes = d.getMinutes();

		

		if(parseInt(hours) < 10)

		hours= "0"+hours;

		

		if(parseInt(minutes) < 10)

		minutes= "0"+minutes;

		

		return hours+":"+minutes;

	}

 

	function setTanggalAkhir()

	{

		var reqJumlahLoopAsesor= reqPukulAwal= reqWaktu= "";

		reqJumlahLoopAsesor= $("#reqJumlahLoopAsesor").val();

		reqPukulAwal=  $("#reqPukulAwal").val();

		reqWaktu=  $("#reqWaktu").val();

		

		var reqPukulAkhir= "";

		for(var i=0; i < parseInt(reqJumlahLoopAsesor); i++)

		{

			if(reqPukulAkhir == "")

			{

				reqPukulAkhir= addtime(reqPukulAwal,reqWaktu);

			}

			else

			{

				reqPukulAkhir= addtime(reqPukulAkhir,reqWaktu);

			}

		}

		

		$("#reqPukulAkhir").val(reqPukulAkhir);

		//var a = "07:30";

		//var b = "01:20";

		//alert(addtime(a,b));

	}



	$(function(){

		<?

		if($reqJenisId == "1")

		{

		?>

		$("#reqPukulAkhir").removeClass('inputInherit');

		$("#reqInfoEstimasiWaktu, #reqWaktu").hide();

		

		$("#reqInfoPenggalianId, #trJumlahKelompok, #reqAddAsesor").hide();

		$('#reqJadwalNama').validatebox({required: true});

		//reqKelompokJumlah

		<?

		}

		else

		{

		?>

		setPenggalian();

		$("#reqStatusCheckBoxPenggalian").prop("checked", true);

		$('#reqJadwalNama').hide();

		$('#reqJadwalNama').val("");

		$('#reqJadwalNama').validatebox({required: false});

		$('#reqJadwalNama').removeClass('validatebox-invalid');

		<?

		}

		?>



		$('#ff').form({

			url:'../json-pengaturan/master_jadwal_add_simulasi_asesor.php',

			onSubmit:function(){

				var reqPukulAwal= reqPukulAkhir= reqPenggalianStatusGroup= reqPenggalianId= reqKelompokJumlah= reqJumlahRuangan= "";

				reqPukulAwal= $("#reqPukulAwal").val();

				reqPukulAkhir= $("#reqPukulAkhir").val();

				reqPenggalianStatusGroup= $("#reqPenggalianStatusGroup").val();

				reqPenggalianId= $("#reqPenggalianId").val();

				reqKelompokJumlah= $("#reqKelompokJumlah").val();

				

				reqJumlahRuangan= $("#reqJumlahRuangan").val();

				

				if($(this).form('validate') == false)

				{

					return false;

				}

				if(reqKelompokJumlah == 0 && reqPenggalianId != "")

				{

					$.messager.alert('Info', "Isikan asesor terlebih dahulu, sebelum simpan", 'info');

					return false;

				}

				if(reqPukulAwal == "")

				{

					$.messager.alert('Info', "Isikan waktu awal terlebih dahulu, sebelum simpan", 'info');

					return false;

				}

				if(reqPukulAkhir == "" && reqPenggalianStatusGroup == "")

				{

					$.messager.alert('Info', "Isikan waktu akhir terlebih dahulu, sebelum simpan", 'info');

					return false;

				}

				

				if(reqPenggalianId == ""){}

				else

				{

					if(parseInt(reqKelompokJumlah) > parseInt(reqJumlahRuangan))

					{

						$.messager.alert('Info', "Batas ruangan telah melebihi dari "+reqJumlahRuangan, 'info');

						return false;

					}

				}

				//return $(this).form('validate');

			},

			success:function(data){

				//alert(data);return false;

				$.messager.alert('Info', data, 'info');

				$('#rst_form').click();

				//parent.setShowHideMenu(3);

				parent.frames['mainFrame'].location.href = 'master_jadwal_add_simulasi_asesor_monitoring.php?reqId=<?=$reqId?>&reqJenisId=<?=$reqJenisId?>';

				parent.frames['mainFrameDetil'].location.href = 'master_jadwal_add_simulasi_asesor.php?reqId=<?=$reqId?>&reqJenisId=<?=$reqJenisId?>&reqRowId=<?=$reqRowId?>';

			}

		});

		

		$('#reqInfoPenggalianId').change(function () {

			setPenggalian();

		});

		

		$('#reqKelompokJumlahTemp').keyup(function () {

			var reqKelompokJumlahTemp= "";

			reqKelompokJumlahTemp= $("#reqKelompokJumlahTemp").val();

			$("#reqKelompokJumlah").val(reqKelompokJumlahTemp);

		});

		

		$('#reqStatusCheckBoxPenggalian').click(function () {

			setStatusPenggalian();

		});

			

	});

	

	function setJumlahLoopAsesor()

	{

		var reqJumlahLoopAsesor= reqKelompokJumlahTemp= "";

		reqKelompokJumlah= $("#reqKelompokJumlah").val();

		//alert(reqKelompokJumlah);

		if(parseInt("<?=$jumlah_pegawai_simulasi?>") > 0 && parseInt(reqKelompokJumlah) > 0)

		{

			reqJumlahLoopAsesor= Math.round(parseFloat(parseInt("<?=$jumlah_pegawai_simulasi?>") / parseInt(reqKelompokJumlah)));

		}

		else

		{

			reqJumlahLoopAsesor= 0;

		}

		$("#reqJumlahLoopAsesor").val(reqJumlahLoopAsesor);

		

		// set tanggal akhir untuk group 1

		setTanggalAkhir();

	}

	

	function setPenggalian()

	{

		var reqInfoPenggalianId= reqPenggalianId= reqPenggalianStatusGroup= reqWaktu= "";

		reqInfoPenggalianId= $("#reqInfoPenggalianId").val();

		reqInfoPenggalianId= reqInfoPenggalianId.split('-');

		//inputInherit

		reqPenggalianId= reqInfoPenggalianId[0];

		reqPenggalianStatusGroup= reqInfoPenggalianId[1];



		if(reqPenggalianStatusGroup == "" || "<?=$reqJenisId?>" == "1")

		{

			$("#infoJumlahKelompok").text("Jumlah Kelompok");

			$("#reqKelompokJumlahTemp, #reqPukulAkhir").removeClass('inputInherit');

			//alert('a');

			$('#reqPukulAkhir').timepicker();

			$("#reqKelompokJumlahTemp, #reqPukulAkhir").attr("readonly",false);

			$("#reqInfoEstimasiWaktu, #reqWaktu").hide();

			$("#reqWaktu").val("");

			$('#reqWaktu').validatebox({required: false});

			$('#reqWaktu').removeClass('validatebox-invalid');

		}

		else

		{

			$("#infoJumlahKelompok").text("Jumlah Asesor");

			$("#reqKelompokJumlahTemp, #reqPukulAkhir").addClass('inputInherit');

			//alert('b');

			$('#reqPukulAkhir').timepicker('destroy');

			$("#reqKelompokJumlahTemp, #reqPukulAkhir").attr("readonly",true);

			$('#reqWaktu').validatebox({required: true});

			$("#reqInfoEstimasiWaktu, #reqWaktu").show();

			setJumlahLoopAsesor();

		}

		

		reqWaktu= $("#reqWaktu").val();

		// kalau group 1 maka, sebelum mengisi asesor maka isikan dulu estimasi waktu

		if(reqPenggalianStatusGroup == "1")

		{

			//document.getElementById('idLookupAsesor').style.visibility='hidden';

			//document.getElementById('idLookupAsesor').style.visibility='visible';

			$('#idLookupAsesor img').hide();

			if(reqWaktu == "")

			{

				

			}

		}

		

		//alert(reqPenggalianId+"-"+reqPenggalianStatusGroup);

		$("#reqPenggalianId").val(reqPenggalianId);

		$("#reqPenggalianStatusGroup").val(reqPenggalianStatusGroup);

	}

	

	function setStatusPenggalian()

	{

		if($("#reqStatusCheckBoxPenggalian").prop('checked')) 

		{

			alert('a');

		}

		else

		{

			alert('b');

		}

	}

	function addAsesorRow()

	{

		if (!document.getElementsByTagName) return;

		tabBody=document.getElementsByTagName("TBODY").item(1);

		

		var rownum= tabBody.rows.length;

		

		row=document.createElement("TR");

		

		cell = document.createElement("TD");

		var button = document.createElement('label');

		

		button.innerHTML = '<input type="hidden" name="reqAsesorId[]" id="reqAsesorId'+rownum+'" />'

		+'<label id="reqAsesor'+rownum+'"></label>'

		+'<img src="../WEB/images/icn_search.png" onClick="openPencarianKaryawan('+rownum+')" style="cursor:pointer" />';

		cell.appendChild(button);

		row.appendChild(cell);

		

		cell = document.createElement("TD");

		var button = document.createElement('label');

		button.innerHTML = '<input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId'+rownum+'" />'

		+'<center><a style="cursorointer" onclick="deleteRowDrawTable(\'tableAsesor\', this)"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>';

		cell.appendChild(button);

		row.appendChild(cell);

			  

		tabBody.appendChild(row);

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

				setAsesorJumlah();

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

				table.deleteRow(i);

				setAsesorJumlah();

			}

		}

		}catch(e) {

			alert(e);

		}

	}

	

	function addKaryawanPopUp()

	{

		var tempPegawaiId= separatorTempRowId= anSelectedId= "";

		tabBody=document.getElementsByTagName("TBODY").item(1);

		var rownum= tabBody.rows.length;

		if(rownum > 0)

		{

			for(var i=0; i < rownum; i++)

			{

				anSelectedId= $("#reqAsesorId"+i).val();

				if(tempPegawaiId == "")

					separatorTempRowId= "";

				else

					separatorTempRowId= ",";

				

				if(anSelectedId == ""){}

				else

				tempPegawaiId= tempPegawaiId+separatorTempRowId+anSelectedId;

			}

		}

		//alert(tempPegawaiId);return false;

		parent.OpenDHTML('asesor_jadwal_pilih_pencarian.php?reqId=<?=$reqId?>&reqRowId='+rownum+'&reqAsesorId='+tempPegawaiId, 'Pencarian Asesor', 780, 500)

	}

	

	function openPencarianKaryawan(rowid)

	{

		var tempAsesorId= separatorTempRowId= anSelectedId= "";

		tabBody=document.getElementsByTagName("TBODY").item(1);

		var rownum= tabBody.rows.length;

		if(rownum > 0)

		{

			for(var i=0; i < rownum; i++)

			{

				anSelectedId= $("#reqAsesorId"+i).val();

				if(tempAsesorId == "")

					separatorTempRowId= "";

				else

					separatorTempRowId= ",";

				

				if(anSelectedId == ""){}

				else

				tempAsesorId= tempAsesorId+separatorTempRowId+anSelectedId;

			}

		}

		parent.OpenDHTML('asesor_jadwal_pencarian.php?reqRowId='+rowid+'&reqAsesorId='+tempAsesorId, 'Pencarian Asesor', 780, 500)

	}

	

	var tempId= tempJabatan= tempDepartemen= tempNama= tempKelas= "";

	function OptionSet(id, rowid)

	{

		tempId=id;

		$.getJSON('../json-pengaturan/asesor_get_json.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqAsesorId='+ id,

		  function(data){

			reqAsesorId= data.tempId;

			reqAsesor=data.tempNama;

			tempJamAsesor= data.tempJamAsesor;

			statusJamAsesor= data.statusJamAsesor;

			

			$("#reqAsesorId"+rowid).val(reqAsesorId);

			$("#reqAsesor"+rowid).text(reqAsesor);

			$("#reqTotalJamAsesor"+rowid).text(tempJamAsesor);

			if(statusJamAsesor == "1")

			$("#reqTotalJamAsesor"+rowid).css("color", "#F33");

			

			setAsesorJumlah();

		  });

	}

	

	function setAsesorJumlah()

	{

		var tempJumlahAsesor= 0;

		$('input[id^="reqAsesorId"]').each(function(){

			var id= $(this).attr('id');

			var val= $(this).val();

			id= id.replace("reqStatusCheckBoxFix", "")

			if(val == ""){}

			else

			{

				tempJumlahAsesor= parseInt(tempJumlahAsesor) + 1;

			}

	   });

	   

	   var reqPenggalianStatusGroup= reqPenggalianId= "";

	   reqPenggalianStatusGroup= $("#reqPenggalianStatusGroup").val();

	   

	   if(reqPenggalianStatusGroup == "1")

	   {

		   $("#reqKelompokJumlahTemp, #reqKelompokJumlah").val(tempJumlahAsesor);

		   setJumlahLoopAsesor();

	   }

	}

	

	

</script>



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



<style>

	/* UNTUK TABLE GRADIENT STYLE*/

	.gradient-style th {

	font-size: 12px;

	font-weight:400;

	background:#b9c9fe url(images/gradhead.png) repeat-x;

	border-top:2px solid #d3ddff;

	border-bottom:1px solid #fff;

	color:#039;

	padding:8px;

	}

	

	.gradient-style td {

	font-size: 12px;

	border-bottom:1px solid #fff;

	color:#669;

	border-top:1px solid #fff;

	background:#e8edff url(images/gradback.png) repeat-x;

	padding:8px;

	}

	

	.gradient-style tfoot tr td {

	background:#e8edff;

	font-size: 14px;

	color:#99c;

	}

	

	.gradient-style tbody tr:hover td {

	background:#d0dafd url(images/gradhover.png) repeat-x;

	color:#339;

	}

	

	.gradient-style {

	font-family: 'Open SansRegular';

	font-size: 14px;

	width:480px;

	text-align:left;

	border-collapse:collapse;

	margin:0px 0px 0px 10px;

	}

	

	.inputInherit{

		border:none; background:inherit;

	}

</style>

</head>



<body>

<div id="page_effect">

<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>

<div id="content" style="height:auto; width:100%">

	<?

	if($reqJenisId == 2 && ($jumlah_pegawai_simulasi == 0 || $jumlah_penggalian == 0) && $reqRowId == "")

	{

		if($jumlah_penggalian == 0)

		{

	?>

    <label>Pilih salah satu penggalian</label>

    <?

		}

		elseif($jumlah_pegawai_simulasi == 0)

		{

    ?>

    <label>Entri pegawai simulasi terlebih dahulu</label>

    <?

		}

	}

	else

	{

    ?>

    <form id="ff" method="post" novalidate>

    <table class="table_list" cellspacing="1" width="100%">

        <tr>           

            <td style="width:150px">

            	Nama Simulasi

                <input type="hidden" title="Check untuk data penggalian " id="reqStatusCheckBoxPenggalian" name="reqStatusCheckBoxPenggalian" />

            </td>

            <td style="width:5px">

            	:

            </td>

            <td>

            	<select name="reqInfoPenggalianId" id="reqInfoPenggalianId" style="width:200px;">

				<?

				for($checkbox_index=0;$checkbox_index < $jumlah_penggalian;$checkbox_index++)

				{

					$tempPenggalianId= $arrPenggalian[$checkbox_index]["PENGGALIAN_ID"];

					$tempPenggalianStatusGroup= $arrPenggalian[$checkbox_index]["STATUS_GROUP"];

					$tempPenggalianNama= $arrPenggalian[$checkbox_index]["NAMA"];

					$tempPenggalianIdStatusGroup= $tempPenggalianId."-".$tempPenggalianStatusGroup;

                ?>

                <option value="<?=$tempPenggalianIdStatusGroup?>" <? if($tempPenggalianId == $tempSimulasiPenggalianId) echo "selected"?>><?=$tempPenggalianNama?></option>

                <?

                }

                ?>

                </select>

                <input type="hidden" id="reqPenggalianId" name="reqPenggalianId" value="<?=$tempSimulasiPenggalianId?>" />

                <input type="text" name="reqJadwalNama" id="reqJadwalNama" class="easyui-validatebox" style="width:60%" value="<?=$tempSimulasiNama?>" />

            </td>

        </tr>

        <tr>

            <td>Waktu</td><td>:</td>

            <td>

            	<input type="hidden" name="reqPukulAwalTemp" id="reqPukulAwalTemp" value="<?=$tempSimulasiPukulAwal?>" />

                <input type="text" name="reqPukulAwal" id="reqPukulAwal" value="<?=$tempSimulasiPukulAwal?>" style="width:50px" />

                s/d

                <input type="text" name="reqPukulAkhir" id="reqPukulAkhir" value="<?=$tempSimulasiPukulAkhir?>" style="width:50px" class="inputInherit" />

                

                <label id="reqInfoEstimasiWaktu">&nbsp;&nbsp;&nbsp;Estimasi Waktu :</label>

            	<input type="text" name="reqWaktu" id="reqWaktu" value="<?=$tempSimulasiWaktu?>" class="easyui-validatebox" style="width:50px" />

            </td>

        </tr>

        <tr id="trJumlahKelompok">

            <td>

            <label id="infoJumlahKelompok"></label>

            </td><td>:</td>

            <td>

			<?

			//if(preg_match("/^[0-9]+$/",$tempSimulasiKelompokJumlah))

			//{

			?>

            <input type="text" id="reqKelompokJumlahTemp" style="width:25px" value="<?=$tempSimulasiKelompokJumlah?>" class="inputInherit"/>

            Batas Kelompok / Ruangan&nbsp;:&nbsp;

            <input type="text" name="reqJumlahRuangan" id="reqJumlahRuangan" value="<?=$tempJumlahRuangan?>" style="width:50px;" class="inputInherit" />

            </td>

        </tr>

        <?

		if($reqId == ""){}

		else

		{

        ?>

        <tr>

            <td>

				<input type="hidden" name="reqRowId" value="<?=$reqRowId?>">

                <input type="hidden" name="reqId" value="<?=$reqId?>">

                <input type="hidden" name="reqJenisId" value="<?=$reqJenisId?>">

                <input type="hidden" name="reqKelompokJumlah" id="reqKelompokJumlah" value="<?=$tempSimulasiKelompokJumlah?>">

                <input type="hidden" name="reqJumlahLoopAsesor" id="reqJumlahLoopAsesor" value="<?=$tempJumlahLoopAsesor?>">

                <input type="hidden" name="reqPenggalianStatusGroup" id="reqPenggalianStatusGroup" value="<?=$tempSimulasiPenggalianStatus?>">

                <input type="hidden" name="reqMode" value="insert">

                <?

				if($tempStatusValidasi == "1"){}

				else

				{

				?>

                <input type="submit" name="" value="Simpan" />

                <input type="button" onclick="setLoad()" value="Baru" />

                <?

				}

                ?>

            </td>

        </tr>

        <?

		}

        ?>

    </table>

    

    <?

	if($reqJenisId == "1"){}

	else

	{

	?>

    <table class="gradient-style" id="tableAsesor" style="width:100%; margin-left:-1px">

    <thead>

    <tr>

        <th scope="col">

        Nama Asesor

        <?

		if($tempStatusValidasi == "1"){}

		else

		{

		?>

        <a style="cursor:pointer" id="reqAddAsesor" title="Tambah" onclick="addKaryawanPopUp()"><img src="../WEB/images/icn_add.gif" width="16" height="16" border="0" /></a>

        <?

		}

        ?>

        </th>

        <?php /*?><th scope="col" style="text-align:center; width:15%">Total Jam dalam Hari ini</th><?php */?>

        <?

		if($tempStatusValidasi == "1"){}

		else

		{

		?>

        <th scope="col" style="text-align:center; width:50px">Aksi</th>

        <?

		}

        ?>

    </tr>

    </thead>

    <tbody>

    <?

	for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)

	{

		$tempAsesorId= $arrJadwalAsesor[$checkbox_index]["ASESOR_ID"];

		$tempAsesor= $arrJadwalAsesor[$checkbox_index]["ASESOR_NAMA"];

		$tempTotalJamAsesor= getTimeIndo($arrJadwalAsesor[$checkbox_index]["TOTAL_JAM_ASESOR"]);

		$styleCss= "";

		if(getTimeJam($arrJadwalAsesor[$checkbox_index]["TOTAL_JAM_ASESOR"]) >= 5)

		$styleCss= "color:#F33";

    ?>

    <tr>

        <td>

        	<input type="hidden" name="reqAsesorId[]" id="reqAsesorId<?=$checkbox_index?>" value="<?=$tempAsesorId?>" />

            <label id="reqAsesor<?=$checkbox_index?>"><?=$tempAsesor?></label>

            <?

			if($tempStatusValidasi == "1"){}

			else

			{

			?>

        	<img src="../WEB/images/icn_search.png" onClick="openPencarianKaryawan(<?=$checkbox_index?>)" id="idLookupAsesor" style="cursor:pointer;" />

            <?

			}

			?>

        </td>

        <?php /*?><td>

        	<label style=" <?=$styleCss?>" id="reqTotalJamAsesor<?=$checkbox_index?>"><?=$tempTotalJamAsesor?></label>

        </td><?php */?>

        <?

		if($tempStatusValidasi == "1"){}

		else

		{

		?>

        <td>

        	<center><a style="cursor:pointer" onclick="deleteRowDrawTablePhp('tableAsesor', this, '<?=$checkbox_index?>', 'reqJadwalAsesorId', 'jadwal_asesor')"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>

            <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId<?=$checkbox_index?>" value="<?=$tempJadwalAsesorId?>" />

        </td>

        <?

		}

        ?>

    </tr>

    <?

	}

    ?>

    </tbody>

    </table>

    <?

	}

    ?>

    </form>

    <?

	}

    ?>

    </div>

</div>



<script>

$('#reqPukulAwal, #reqWaktu').timepicker();

$("#reqKelompokJumlah").keypress(function(e) {

	//alert(e.which);

	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))

	//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))

	{

	return false;

	}

});

</script>

</body>

</html>