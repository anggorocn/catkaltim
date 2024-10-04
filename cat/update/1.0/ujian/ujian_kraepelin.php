<?
include_once("../WEB/functions/infotipeujian.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");
include_once("../WEB/functions/string.func.php");

include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");

include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/KraepelinSoal.php");

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$tempUjianId= $ujianPegawaiUjianId;
$tempSystemTanggalNow= date("d-m-Y");

$reqUjianTahapId= httpFilterGet("reqUjianTahapId");

/*A.UJIAN_ID, A.UJIAN_PEGAWAI_DAFTAR_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
, C.TIPE, D.TIPE_INFO
, B.MENIT_SOAL, C.TIPE_UJIAN_ID, LENGTH(C.PARENT_ID) LENGTH_PARENT, C.PARENT_ID
, (SELECT 1 FROM cat.UJIAN_TAHAP_STATUS_UJIAN X WHERE 1=1 AND X.UJIAN_ID = A.UJIAN_ID AND X.UJIAN_TAHAP_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID AND X.PEGAWAI_ID = A.PEGAWAI_ID) TIPE_STATUS*/

$statement= " AND COALESCE(B.MENIT_SOAL,0) > 0 AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ".$reqUjianTahapId;
$set= new UjianTahap();
$set->selectByParamsUjianPegawaiTahap(array(), -1,-1, $statement);
$set->firstRow();
$tempMenitSoal= $set->getField("MENIT_SOAL");
$reqUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$reqPegawaiId= $tempPegawaiId;
$reqJadwalTesId= $ujianPegawaiJadwalTesId;
$reqFormulaAssesmentId= $ujianPegawaiFormulaAssesmentId;
$reqFormulaEselonId= $ujianPegawaiFormulaEselonId;
$reqUjianId= $tempUjianId;
$reqTipeUjianId= $set->getField("TIPE_UJIAN_ID");

$statement= " AND EXISTS
(
SELECT 1 FROM cat.KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
)";
$statement.= 
"
AND NOT EXISTS
(
	SELECT 1
	FROM
	(
	SELECT
	JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, X_DATA
	FROM cat.UJIAN_KRAEPELIN
	GROUP BY JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, X_DATA
	) X
	WHERE 1=1 AND X.PEGAWAI_ID = ".$tempPegawaiId." AND X.UJIAN_TAHAP_ID = ".$reqUjianTahapId."
	AND A.X_DATA = X.X_DATA
)
";
// WHERE NILAI IS NOT NULL
$set= new KraepelinSoal();
$set->selectByXbarisYbarisParams(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit();
$reqPakaiKraepelinId= $set->getField("PAKAI_KRAEPELIN_ID");
$x_bawah_batas= $set->getField("MIN_X_DATA");
$x_batas= $set->getField("X_DATA");
$checkminxdata= $set->getField("CHECK_MIN_X_DATA");

if($checkminxdata == "1")
	$batas_soal= $x_batas= 1;
else
	$batas_soal= $x_batas= 5;

$x_batas+= $x_bawah_batas;
$y_batas= $set->getField("Y_DATA");
// $y_batas= 3;
// echo $x_bawah_batas."-".$x_batas."-".$y_batas;exit();

// kalau data null maka finish kembali ke menu tahap
if($x_bawah_batas == "")
{
	$statement= " AND UJIAN_ID= ".$ujianPegawaiUjianId." AND UJIAN_TAHAP_ID = ".$reqUjianTahapId." AND PEGAWAI_ID = ".$tempPegawaiId;
	$set= new UjianTahapStatusUjian();
	$set->selectByParams(array(), -1,-1, $statement);
	$set->firstRow();
	$tempPegawaiId= $set->getField("PEGAWAI_ID");
	unset($set);

	if($tempPegawaiId == "")
	{
		$set= new UjianTahapStatusUjian();
		$set->setField("UJIAN_PEGAWAI_DAFTAR_ID", $reqUjianPegawaiDaftarId);
		$set->setField("JADWAL_TES_ID", $ujianPegawaiJadwalTesId);
		$set->setField("FORMULA_ASSESMENT_ID", $ujianPegawaiFormulaAssesmentId);
		$set->setField("FORMULA_ESELON_ID", $ujianPegawaiFormulaEselonId);
		$set->setField("TIPE_UJIAN_ID", $reqTipeUjianId);
		$set->setField("UJIAN_ID", $ujianPegawaiUjianId);
		$set->setField("UJIAN_TAHAP_ID", $reqUjianTahapId);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("STATUS", "1");
		$set->setField("LAST_CREATE_USER", $userLogin->nama);
		$set->setField("LAST_CREATE_DATE", "NOW()");
		if($set->insert())
		{
			echo '<script language="javascript">';
			echo 'top.location.href = "index.php?pg=ujian_pilihan";';
			echo '</script>';
			exit;
		}
		//echo $set->query;exit;
		unset($set);
	}
}

$arrSoal="";
$index_data= 0;
$statement= " AND A.PAKAI_KRAEPELIN_ID = ".$reqPakaiKraepelinId;
$statement.= 
"
AND NOT EXISTS
(
	SELECT 1
	FROM
	(
	SELECT
	JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, X_DATA
	FROM cat.UJIAN_KRAEPELIN
	GROUP BY JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, X_DATA
	) X
	WHERE 1=1 AND X.PEGAWAI_ID = ".$tempPegawaiId." AND X.UJIAN_TAHAP_ID = ".$reqUjianTahapId."
	AND A.X_DATA = X.X_DATA
)
";
// WHERE NILAI IS NOT NULL

$set= new KraepelinSoal();
$set->selectByParams(array(), -1, -1, $statement);
//echo $set->errorMsg;exit;
//echo $set->query;exit;
while($set->nextRow())
{
	// A.PAKAI_KRAEPELIN_ID, A.X_DATA, A.Y_DATA, A.NILAI
	$arrSoal[$index_data]["KOORDINAT"]= $set->getField("X_DATA")."-".$set->getField("Y_DATA");
	$arrSoal[$index_data]["X_DATA"]= $set->getField("X_DATA");
	$arrSoal[$index_data]["Y_DATA"]= $set->getField("Y_DATA");
	$arrSoal[$index_data]["NILAI"]= $set->getField("NILAI");
	$index_data++;
}
unset($set);
$jumlah_soal= $index_data;
// print_r($arrSoal);exit();

// $final_time_saving= 1000;
// $final_time_saving= "0";
$final_time_saving= $tempMenitSoal;
$hours= floor($final_time_saving / 60);
$minutes= $final_time_saving % 60;
// $minutes= 2;
$second= "00";
// $second= "05";
// echo $minutes;exit();

if($hours > 0)
$tempInfoWaktu= generateZero($hours,2).":".generateZero($minutes,2).":".$second;
else
$tempInfoWaktu= generateZero($minutes,2).":".$second;

// echo $tempInfoWaktu."--";exit();
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<style type="text/css">
	.jwb td{color: #00f;font-size:12px;padding:0 15px 0 0}
	.inputkrp
	{
		width: 10%
	}
</style>

<script type="text/javascript">
	// disable F5
	function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };

	$(document).ready(function(){
		$(document).on("keydown", disableF5);
	});

	var interval= "";
	var totalmelakukanujian= 0;
	totalmelakukanujian= parseInt(totalmelakukanujian);

	$(function(){
		$("#btnsimpan").hide();
	});

	function settime(id)
	{
		$('[id^="reqDataXY"]').prop("disabled", true);
		$('[id^="reqDataXY-'+id+'"]').prop("disabled", false);
		$("#reqMulai-"+id).hide();
		$("#reqStop-"+id).show();
		
		clearInterval(interval);
		clearTimeout(interval);	
		localStorage.setItem("end", null);
		localStorage.clear();
		
		// localStorage.removeItem("end"+id);
		divCounter= "divCounter-"+id;
		var difflog= "";
		var tempmin= "";
		var hoursleft = <?=$hours?>;
		var minutesleft = <?=$minutes?>;
		//var minutesleft = parseFloat('30,5');
		var secondsleft = <?=$second?>; 

		// var hoursleft = 1;
		// var minutesleft = 0;
		// var secondsleft = 0; 
		
		var finishedtext = "Countdown finished!";
		var end;
		if(localStorage.getItem("end")) {
			end = new Date(localStorage.getItem("end"));
		} else {
		   end = new Date();
		   end.setHours(end.getHours()+hoursleft);
		   end.setMinutes(end.getMinutes()+minutesleft);
		   end.setSeconds(end.getSeconds()+secondsleft);
		}
		
		// var counter = [];
		var counter = function () {
			var now = new Date();
		
			var sec_now = now.getSeconds();
			var min_now = now.getMinutes(); 
			var hour_now = now.getHours(); 
			
			var sec_end = end.getSeconds();
			var min_end = end.getMinutes(); 
			var hour_end = end.getHours();

			var date1 = new Date(2000, 0, 1, hour_now,  min_now, sec_now); // 9:00 AM
			var date2 = new Date(2000, 0, 1, hour_end, min_end, sec_end); // 5:00 PM
			if (date2 < date1) {
				date2.setDate(date2.getDate() + 1);
			}

			var diff = date2 - date1;
			// console.log(diff);
			// return false;
			
			var msec = diff;
			var hh = Math.floor(msec / 1000 / 60 / 60);
			msec -= hh * 1000 * 60 * 60;
			var mm = Math.floor(msec / 1000 / 60);
			msec -= mm * 1000 * 60;
			var ss = Math.floor(msec / 1000);
			msec -= ss * 1000;
			
			var sec = ss;
			var min = mm; 
			var hour = hh; 
			
			if (min < 10) {
				min = "0" + min;
			}
			if (sec < 10) { 
				sec = "0" + sec;
			}
			
			if(now >= end) {     
				//alert('a');return false;
				// clearTimeout(interval[id]);
				clearTimeout(interval);
				localStorage.setItem("end", null);
				// console.log("habis-1");
				$('[id^="reqDataXY-'+id+'"]').prop("disabled", true);
				document.getElementById(divCounter).innerHTML = "0:00:00";

				$('[id^="reqStop"]').hide();
				$("#bawahlihat").focus();

				// totalmelakukanujian= totalmelakukanujian+1;

				// console.log("a:"+totalmelakukanujian);

				//document.getElementById(divCounter).innerHTML = finishedtext;
			} else {
				var value = hour + ":" + min + ":" + sec;
				localStorage.setItem("end", end);
				// console.log(id+"--"+value);
				// $("#divCounter"+id).text(value);
				document.getElementById(divCounter).innerHTML = value;
				if(min.toString() == 'NaN')
				{
					// console.log("habis-2");
					localStorage.setItem("waktuberakhir", "00:00");
					// clearTimeout(interval[id]);	
					clearTimeout(interval);	
					localStorage.setItem("end", null);
					// totalmelakukanujian= totalmelakukanujian+1;

					// console.log("b:"+totalmelakukanujian);

					// $('[id^="reqDataXY-'+id+'"]').prop("disabled", true);

				}

				if(value == "00:00:00")
				{
					$('[id^="reqDataXY-'+id+'"]').prop("disabled", true);
					$('[id^="reqStop"]').hide();
					$("#bawahlihat").focus();
				}
			}
		}
		interval = setInterval(counter, 1);
		// clearInterval(interval);
		// var interval = [];
		// interval[id] = setInterval(counter, 1);
	}

	function setSimpan()
	{
		$.messager.confirm('Konfirmasi', "Apakah Anda yakin untuk simpan data ?",function(r){
			if (r){
				$('#ff').submit();

				clearInterval(interval);
				clearTimeout(interval);	
				localStorage.setItem("end", null);
				localStorage.clear();
		
				return true;
			}
		});
	}

	$(function(){
		$('#ff').form({
			url:'../json-ujian/ujian_kraepelin.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				$.messager.alert('Info', data, 'info');
				document.location.href = '?pg=ujian_kraepelin&reqUjianTahapId=<?=$reqUjianTahapId?>';
			}
		});

		// settime(1);
		$('input[id^="reqDataXY"]').bind('keyup paste', function(){
			
			
			
			//alert("haii");
			//$(window).scrollTop($('input[id^="reqDataXY"]').offset().top - 100);
			//$('input[id^="reqDataXY"]').scrollTop();
			this.value = this.value.replace(/[^0-9]/g, '');
			
			 //$('input[id^="reqDataXY"]').animate({ "margin-top": "200px"}, 200);
			 //$('td.soal-nilai').animate({
				// "position": "absolute",
				// "top": 50+"%"
				 //position: absolute,
				 //top: 50+"%",
				 	//"height": 110,
            		//"width": 110
			 //}, 200);
			 
			 //$(".area-sudah").scrollTop();
			 
			//$(window).scrollTop($(".area-sudah").offset().top);
    		//$("input").focus();
			 
			 
		});
		
		/*$('input[id^="reqDataXY-1-3"]').bind('keyup paste', function(){
			// $('.area-sudah').animate({ scrollTop: 850 }, 'slow', function () {
			$('.area-sudah').animate({ scrollTop: 950 }, 'slow', function () {
				//alert("reached top belum");
				//alert($('input.inputkrp').position());
			});
		});

		$('input[id^="reqDataXY-1-7"]').bind('keyup paste', function(){
			$('.area-sudah').animate({ scrollTop: 950 }, 'slow', function () {
				//alert("reached top belum");
			});
		});
		
		$('input[id^="reqDataXY-1-15"]').bind('keyup paste', function(){
			$('.area-sudah').animate({ scrollTop: 350 }, 'slow', function () {
				//alert("reached top belum");
			});
		});
		
		$('input[id^="reqDataXY-1-20"]').bind('keyup paste', function(){
			$('.area-sudah').animate({ scrollTop: 100 }, 'slow', function () {
				alert("ubah");
				//alert("reached top belum");
			});
		});

		$('input[id^="reqDataXY-1-25"]').bind('keyup paste', function(){
			$('.area-sudah').animate({ scrollTop: 0 }, 'slow', function () {
				alert("reached top");
			});
		});*/

		$('div[id^="reqStop"]').click( function(e) {
			$.messager.confirm('Konfirmasi', "Apakah Anda yakin untuk stop ?",function(r){
				if (r){

					// var id= $(this).attr('id');
					// arrTempId= String(id);
					// arrTempId= arrTempId.split('-');
					// x= arrTempId[arrTempId.length-1];
					// console.log(x);

					clearInterval(interval);
					clearTimeout(interval);	
					localStorage.setItem("end", null);
					localStorage.clear();
					
					$('[id^="reqDataXY"]').prop("disabled", true);
					$('[id^="reqStop"]').hide();
					$("#bawahlihat").focus();


					// $("#reqStop-"+id).show();
				}
			});

			
		});
			
		$('a[id^="reqMulai"]').click( function(e) {
			var id= $(this).attr('id');
			arrTempId= String(id);
			arrTempId= arrTempId.split('-');
			x= arrTempId[arrTempId.length-1];

			if(totalmelakukanujian == "<?=$batas_soal?>")
			{
				$(function(){
					$("#btnsimpan").show();
				});
			}
			
			settime(x);

			$("#reqDataXY-"+x+"-1").focus();
			totalmelakukanujian= totalmelakukanujian+1;


			// console.log("a:"+totalmelakukanujian);

			// console.log(x);
		});

		$('input[id^="reqDataXY"]').keyup( function(e) {
			var id= $(this).attr('id');
			arrTempId= String(id);
			arrTempId= arrTempId.split('-');

			y= arrTempId[arrTempId.length-1];
			x= arrTempId[arrTempId.length-2];
			y= parseInt(y) + 1;

			/*var idName = $(this).attr('name');
			var allowTab = true;
			var inputArr = {username:'', email:'', password:'', address:''}
			 // allow or disable the fields in inputArr by changing true / false
     		if(id in inputArr) allowTab = false;
			if(e.keyCode==9 && allowTab==false) e.preventDefault();*/
			// if(e.keyCode==9) e.preventDefault();

			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			// console.log(key);
			if(key == 13 || key == 9) {
				// e.preventDefault();

				$("#reqDataXY-"+x+"-"+y).focus();
				// console.log(x+"-"+y);
				// console.log(id+"-"+x+"-"+y);

				sct= 0
				scl= 0;
				awl= 1000;
				if(y == 4)
				{
					sct= 1000;
					scl= 1;
				}
				// else if(y % 4 == 0 )
				// {
				// 	bagy= y/4;
				// 	sct= 180 * parseInt(bagy);
				// 	sct=parseInt(awl) - parseInt(sct);
				// 	scl= 1;

				// }
				else if(y == 8)
				{
					sct= 800;
					scl= 1;
				}
				else if(y == 12)
				{
					sct= 600;
					scl= 1;
				}
				else if(y == 16)
				{
					sct= 400;
					scl= 1;
				}
				else if(y == 20)
				{
					sct= 200;
					scl= 1;
				}
				else if(y == 25) 
				{
					sct= 0;
					scl= 1;
				}
				// console.log(y+";"+sct);

				/*if(parseInt(y) <= 3) sct= 950;
				else if(parseInt(y) > 3 && parseInt(y) <= 7) sct= 950;
				else if(parseInt(y) > 7 && parseInt(y) <= 15) sct= 350;
				else if(parseInt(y) > 15 && parseInt(y) <= 20) sct= 100;
				else if(y == 25) sct= 0;*/

				if(scl == 1)
				$('.area-sudah').animate({ scrollTop: sct }, 'slow', function () {});

			}
		});

		// $('[id^="reqXYdata"]').prop("disabled", false);
		$('[id^="reqDataXY"]').prop("disabled", true);
		// $('[id^="reqDataXY-1"]').prop("disabled", false);
		// $("#reqDataXY-1-1").focus();
		// $("#reqMulai-1").focus();
		$("#bawahlihat").focus();
		// $("#bawahlihat").css('outline', 0).attr('tabindex', -1).focus(function () {
		// 	console.log('focus');
		// });

		// $("#bawahlihat").focusout(function () {
		// 	console.log('focusout');      
		// });

		$('[id^="reqStop"]').hide();

		$('input[id^="reqDataXY"]').keyup(function() {
			var tempId= $(this).attr('id');
			var tempValue= $(this).val();
			arrTempId= String(tempId);
			arrTempId= arrTempId.split('-');

			y= arrTempId[arrTempId.length-1];
			x= arrTempId[arrTempId.length-2];

			$("#reqXYdataNilai-"+x+"-"+y).val(tempValue);
			// console.log(y);
		});
	});
	
	//$(function(){
		//$(window).scrollTop($("input.inputkrp").offset().top - 300);
		//$("input").focus();
	//});
</script>



<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">
				Ujian Tahap
				<span id="btnsimpan" style="float:right" class="lengkapimodif-data"><a href="#" onclick="setSimpan()">SIMPAN &raquo;</a></span>
			</div>
        </div>
    </div>
    
    <form id="ff" method="post" novalidate>
	<div class="row">
        <div class="col-md-12">
            <div class="area-soal">
                <div class="area-sudah finish">
                	<table style="width: 100%" border="0">
                		<tr>
                			<?
                			// for($x=1; $x <= $x_batas; $x++)
                			for($x=$x_bawah_batas; $x <= $x_batas; $x++)
                			{
                			?>
                			<td>
	                			<div class="waktu">
	                				<!-- <label id="divCounter-<?=$x?>">10:10</label> -->
	                				<div class="waktu-counter" id="divCounter-<?=$x?>"><?=$tempInfoWaktu?></div>
                                    <div class="btn-stop" id="reqStop-<?=$x?>" style="cursor: pointer;">Stop</div>
	                			</div>
                				<div class="area-kolom">
		                		<table style="width: 100%" border="0">
		                		<?
	                			for($y=$y_batas; $y >= 1; $y--)
	                			{
	                				$y_jawab= $y-1;

	                				$koordinat= $x."-".$y;
	                				$arrayKey= $reqSoalNilai= '';
  									$arrayKey= in_array_column($koordinat, "KOORDINAT", $arrSoal);
  									//print_r($arrayKey);exit;
  									if($arrayKey == ''){}
									else
									{
										$index_row= $arrayKey[0];
										$reqSoalNilai= $arrSoal[$index_row]["NILAI"];
									}
	                			?>
		                			<tr>
								        <td class="soal-nilai"><?=$reqSoalNilai?></td>
								        <td> </td>
								    </tr>
							    <?
								    if($y_jawab > 0)
								    {
								    	//$x.$y_jawab
							    ?>
								    <tr class='jwb' id='reqRow-<?=$x."-".$y_jawab?>'>
								        <td> </td>
								        <td>
								        	 <!-- style="margin-top: -17px !important" -->
								        	<div class="area-input-krp">
									        	<input type="hidden" name="reqXdata[]" value="<?=$x?>" />
									        	<input type="hidden" name="reqYdata[]" value="<?=$y_jawab?>" />
									        	<input type="hidden" name="reqXYdataNilai[]" id="reqXYdataNilai-<?=$x."-".$y_jawab?>" />
									        	<input type="text" id="reqDataXY-<?=$x."-".$y_jawab?>" maxlength="1" value="" class="inputkrp" />
								        	</div>
								        </td>
								    </tr>
	                			<?
	                				}
	                			}
	                			?>
							    <!-- <tr>
							        <td>0</td>
							        <td> </td>
							    </tr>
							    <tr class='jwb'>
							        <td> </td>
							        <td><input type="text" name="" value="4" class="inputkrp" /></td>
							    </tr>
							    <tr>
							        <td>0</td>
							        <td> </td>
							    </tr> -->
							    <tr>
								   <td colspan="2" class="area-btn-mulai">
								   		<a id="reqMulai-<?=$x?>" class="btn-mulai" style="cursor: pointer;" ><label>Mulai</label></a>
								   		<?php /*?><a id="reqStop-<?=$x?>" style="cursor: pointer;" ><label>Stop</label></a><?php */?>
								   </td>
								</tr>

								</table>
								</div>
							</td>
							<?
							}
							?>
						</tr>
					</table>
					<div id="bawahlihat" tabindex="-1"></div>
                </div>
            </div>
        </div>

        <input type="hidden" name="reqPakaiKraepelinId" value="<?=$reqPakaiKraepelinId?>" />
        <input type="hidden" name="reqUjianPegawaiDaftarId" value="<?=$reqUjianPegawaiDaftarId?>" />
        <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
        <input type="hidden" name="reqJadwalTesId" value="<?=$reqJadwalTesId?>" />
        <input type="hidden" name="reqFormulaAssesmentId" value="<?=$reqFormulaAssesmentId?>" />
        <input type="hidden" name="reqFormulaEselonId" value="<?=$reqFormulaEselonId?>" />
        <input type="hidden" name="reqUjianId" value="<?=$reqUjianId?>" />
        <input type="hidden" name="reqTipeUjianId" value="<?=$reqTipeUjianId?>" />
        <input type="hidden" name="reqUjianTahapId" value="<?=$reqUjianTahapId?>" />
        
        <div class="area-prev-next">
        	<div class="kembali-home">
        	<span class="ke-home"><a href="?pg=dashboard"><i class="fa fa-home"></i> Kembali ke halaman utama <!--&raquo;--></a></span>
            </div>
        </div>
    
    </div>
	</form>

</div>