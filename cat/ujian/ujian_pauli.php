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
include_once("../WEB/classes/base-cat/PauliSoal.php");
include_once("../WEB/classes/base-cat/UjianPauli.php");

date_default_timezone_set('Asia/Jakarta');

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

// $tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiLowonganId= $ujianPegawaiJadwalTesId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

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
// echo $set->query;exit();
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
SELECT 1 FROM cat.PAULI_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
AND A.PAKAI_PAULI_ID = X.PAKAI_PAULI_ID
)";
$statement.= 
"
AND NOT EXISTS
(
SELECT 1
FROM
(
SELECT
JADWAL_TES_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, X_DATA
FROM cat_pegawai.UJIAN_PAULI_".$tempUjianPegawaiLowonganId."
GROUP BY JADWAL_TES_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, X_DATA
) X
WHERE 1=1 AND X.PEGAWAI_ID = ".$tempPegawaiId." AND X.UJIAN_TAHAP_ID = ".$reqUjianTahapId."
AND X.JADWAL_TES_ID = ".$tempUjianPegawaiLowonganId."
AND A.X_DATA = X.X_DATA
)
";
// WHERE NILAI IS NOT NULL
$set= new PauliSoal();
$set->selectByXbarisYbarisParams(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit();
$reqPakaiPauliId= $set->getField("PAKAI_PAULI_ID");
$x_bawah_batas= $set->getField("MIN_X_DATA");
$x_batas= $set->getField("X_DATA");
$checkminxdata= $set->getField("CHECK_MIN_X_DATA");
// echo $x_bawah_batas;exit();

// if($checkminxdata == "1")
$batas_soal= $x_batas= 0;
// else
// 	$batas_soal= $x_batas= 5;

$x_batas+= $x_bawah_batas;
$y_batas= $set->getField("Y_DATA");
// $y_batas= 3;
// echo $x_bawah_batas."-".$x_batas."-".$y_batas;exit();

$statement= " AND A.JADWAL_TES_ID = ".$tempUjianPegawaiLowonganId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new UjianPauli();
$set->selectByTanda($tempUjianPegawaiLowonganId, $statement);
// echo $set->query;exit();
$set->firstRow();
$reqNomor= $set->getField("NOMOR");

if($reqNomor >= 20)
{
	// echo "ASd";exit;
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
		$set->setField("JADWAL_TES_ID", $tempUjianPegawaiLowonganId);
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
$statement= " AND A.PAKAI_PAULI_ID = ".$reqPakaiPauliId;
$statement.= 
"
AND NOT EXISTS
(
SELECT 1
FROM
(
SELECT
JADWAL_TES_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, X_DATA
FROM cat_pegawai.UJIAN_PAULI_".$tempUjianPegawaiLowonganId."
GROUP BY JADWAL_TES_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, X_DATA
) X
WHERE 1=1 AND X.PEGAWAI_ID = ".$tempPegawaiId." AND X.UJIAN_TAHAP_ID = ".$reqUjianTahapId."
AND X.JADWAL_TES_ID = ".$tempUjianPegawaiLowonganId."
AND A.X_DATA = X.X_DATA
)
";
// WHERE NILAI IS NOT NULL

$set= new PauliSoal();
$set->selectByParams(array(), -1, -1, $statement);
//echo $set->errorMsg;exit;
//echo $set->query;exit;
while($set->nextRow())
{
	// A.PAKAI_PAULI_ID, A.X_DATA, A.Y_DATA, A.NILAI
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
/*$arrMenit= explode(".", $tempMenitSoal);
// print_r($arrMenit);exit();
// echo $tempMenitSoal;exit();
$final_time_saving= $tempMenitSoal;
$hours= floor($final_time_saving / 60);
$minutes= $final_time_saving % 60;
// echo $minutes."--";exit();
// $minutes= 2;
// $second= $final_time_saving % 60;
$second= $arrMenit[1];

if($second == "")
$second= "00";*/

$tempInfoWaktuUtama= "01:00:00";
$hours= "1";
$minutes= $second= "0";
// echo $minutes;exit();

$hoursInfo= "0";
$minutesInfo= "3";
// $minutesInfo= "0";
$secondInfo= "0";
// $secondInfo= "5";
// echo generateZero($hoursInfo,2).":".generateZero($minutesInfo,2).":".generateZero($secondInfo,2);exit();

if($hours > 0)
	$tempInfoWaktu= generateZero($hoursInfo,2).":".generateZero($minutesInfo,2).":".generateZero($secondInfo,2);
else
	$tempInfoWaktu= generateZero($minutesInfo,2).":".generateZero($secondInfo,2);
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

	var totalmelakukanujian= interval= intervalUtama= arrCheckXYData= "";
	totalmengerjakan= 0;
	<?
	if($x_bawah_batas == "1")
	{
		?>
		totalmelakukanujian= -1;
		<?
	}
	else
	{
		?>
		totalmelakukanujian= 0;
		<?
	}
	?>

	totalmelakukanujian= parseInt(totalmelakukanujian);

	$(function(){
		$("#btnsimpan").hide();
		// one reset
		// $("#infobutton").text("SIMPAN");
		// $("#btnsimpan").show();

		// localStorage.removeItem('endUtama');
		// localStorage.removeItem('end');
	});

	function settime(id)
	{
		idnext= parseInt("<?=$x_batas?>");
		// console.log(idnext);return false;
		$('[id^="reqDataXY"]').prop("disabled", true);
		$('[id^="reqDataXY-'+idnext+'"]').prop("disabled", false);

		// console.log(id);
		divCounter= "divCounter-"+id;
		var difflog= "";
		var tempmin= "";

		var hoursleft = <?=$hoursInfo?>;
		var minutesleft = <?=$minutesInfo?>;
		var secondsleft = <?=$secondInfo?>; 

		var finishedtext = "Countdown finished!";
		var end;
		// var endlog;
		if(localStorage.getItem("end")) {
			end = new Date(localStorage.getItem("end"));
		} else {
			end = new Date();
			end.setHours(end.getHours()+hoursleft);
			end.setMinutes(end.getMinutes()+minutesleft);
			end.setSeconds(end.getSeconds()+secondsleft);
		}


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
			// var datelog= new Date(2000, 0, 1, hour_log, min_log, sec_log);
			if (date2 < date1) {
				date2.setDate(date2.getDate() + 1);
			}
			var diff = date2 - date1;
			
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
				clearInterval(interval);
				clearTimeout(interval);
				interval= "";
				localStorage.setItem("end", null);
				localStorage.removeItem('end');

				// localStorage.clear();
				document.getElementById(divCounter).innerHTML = "0:00:00";

				// arrCheckXYData= $("#arrCheckXYData").val();
				arrCheckXYData= ambilcheckdata();
				// console.log(arrCheckXYData);
				$("#arrCheckXYData").val(arrCheckXYData);

				reqPakaiPauliId= $("reqPakaiPauliId").val();
				reqUjianPegawaiDaftarId= $("#reqUjianPegawaiDaftarId").val();
				reqPegawaiId= $("#reqPegawaiId").val();
				reqJadwalTesId= $("#reqJadwalTesId").val();
				reqFormulaAssesmentId= $("#reqFormulaAssesmentId").val();
				reqFormulaEselonId= $("#reqFormulaEselonId").val();
				reqUjianId= $("#reqUjianId").val();
				reqTipeUjianId= $("#reqTipeUjianId").val();
				reqUjianTahapId= $("#reqUjianTahapId").val();

				var s_url= "../json-ujian/ujian_pauli_batas.php?reqPakaiPauliId="+reqPakaiPauliId+"&reqUjianPegawaiDaftarId="+reqUjianPegawaiDaftarId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalTesId="+reqJadwalTesId+"&reqFormulaAssesmentId="+reqFormulaAssesmentId+"&reqFormulaEselonId="+reqFormulaEselonId+"&reqUjianId="+reqUjianId+"&reqTipeUjianId="+reqTipeUjianId+"&reqUjianTahapId="+reqUjianTahapId+"&arrCheckXYData="+arrCheckXYData;
				$.ajax({'url': s_url,'success': function(msg) {
					if(msg == ''){}
					else
					{
						if(msg == 20)
						{
							setGoSimpan();
						}
						else
						{
							idnext= parseInt("<?=$x_batas?>");
							// console.log(idnext);return false;

							settime(idnext);
						}
					}
				}});

			} else {
				var value = hour + ":" + min + ":" + sec;
				localStorage.setItem("end", end);
				document.getElementById(divCounter).innerHTML = value;

				if(min.toString() == 'NaN')
				{
					clearInterval(interval);
					clearTimeout(interval);
					
					// localStorage.setItem("endlog", null);
					localStorage.setItem("waktuberakhir", "00:00");
					// clearTimeout(interval);	
					localStorage.setItem("end", null);
					localStorage.removeItem('end');
					interval= "";

					// localStorage.clear();

					// arrCheckXYData= $("#arrCheckXYData").val();
					arrCheckXYData= ambilcheckdata();
					// console.log(arrCheckXYData);
					$("#arrCheckXYData").val(arrCheckXYData);

					reqPakaiPauliId= $("reqPakaiPauliId").val();
					reqUjianPegawaiDaftarId= $("#reqUjianPegawaiDaftarId").val();
					reqPegawaiId= $("#reqPegawaiId").val();
					reqJadwalTesId= $("#reqJadwalTesId").val();
					reqFormulaAssesmentId= $("#reqFormulaAssesmentId").val();
					reqFormulaEselonId= $("#reqFormulaEselonId").val();
					reqUjianId= $("#reqUjianId").val();
					reqTipeUjianId= $("#reqTipeUjianId").val();
					reqUjianTahapId= $("#reqUjianTahapId").val();

					var s_url= "../json-ujian/ujian_pauli_batas.php?reqPakaiPauliId="+reqPakaiPauliId+"&reqUjianPegawaiDaftarId="+reqUjianPegawaiDaftarId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalTesId="+reqJadwalTesId+"&reqFormulaAssesmentId="+reqFormulaAssesmentId+"&reqFormulaEselonId="+reqFormulaEselonId+"&reqUjianId="+reqUjianId+"&reqTipeUjianId="+reqTipeUjianId+"&reqUjianTahapId="+reqUjianTahapId+"&arrCheckXYData="+arrCheckXYData;
					$.ajax({'url': s_url,'success': function(msg) {
						if(msg == ''){}
						else
						{
							if(msg == 20)
							{
								setGoSimpan();
							}
							else
							{
								idnext= parseInt("<?=$x_batas?>");
								// console.log(idnext);return false;

								settime(idnext);
							}

						}
					}});

				}
			}

		}
		interval = setInterval(counter, 1);
	}

	function setGoSimpan()
	{
		$('#ff').submit();
		return true;
	}

	function setcheckdata()
	{
		totalmengerjakan= 0;
		$('input[id^="reqDataXY"]').each(function(){
			if($(this).val() !== "")
			{
				totalmengerjakan= parseInt(totalmengerjakan) + 1;
			}
		});
		// console.log(x+"-"+y);

		return totalmengerjakan;
	}

	function ambilcheckdata()
	{
		// set variable kondisi kalau pas di awal ganti tiga menit
		var x= "<?=$x_bawah_batas?>";
		var y= "1";

		$('input[id^="reqDataXY"]').each(function(){
			if($(this).val() !== "")
			{
				var id= $(this).attr('id');
				arrTempId= String(id);
				arrTempId= arrTempId.split('-');

				y= arrTempId[arrTempId.length-1];
				x= arrTempId[arrTempId.length-2];
				// $("#arrCheckXYData").val(x+"-"+y);
			}
		});
		// console.log(x+"-"+y);

		return x+"-"+y;
	}

	function ambilnextdata()
	{
		// set variable kondisi kalau pas di awal ganti tiga menit
		var x= "<?=$x_bawah_batas?>";
		var y= "1";

		$('input[id^="reqDataXY"]').each(function(){
			var id= $(this).attr('id');
			arrTempId= String(id);
			arrTempId= arrTempId.split('-');

			y= arrTempId[arrTempId.length-1];
			x= arrTempId[arrTempId.length-2];

			if($(this).val() !== ""){}
			else
			{
				// console.log(x+"-"+y);
				return false;
			}

		});

		return x+"-"+y;

	}

	$(function(){
		$('#ff').form({
			url:'../json-ujian/ujian_pauli.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				
				reqPakaiPauliId= $("reqPakaiPauliId").val();
				reqUjianPegawaiDaftarId= $("#reqUjianPegawaiDaftarId").val();
				reqPegawaiId= $("#reqPegawaiId").val();
				reqJadwalTesId= $("#reqJadwalTesId").val();
				reqFormulaAssesmentId= $("#reqFormulaAssesmentId").val();
				reqFormulaEselonId= $("#reqFormulaEselonId").val();
				reqUjianId= $("#reqUjianId").val();
				reqTipeUjianId= $("#reqTipeUjianId").val();
				reqUjianTahapId= $("#reqUjianTahapId").val();

				var s_url= "../json-ujian/ujian_pauli_check.php?reqPakaiPauliId="+reqPakaiPauliId+"&reqUjianPegawaiDaftarId="+reqUjianPegawaiDaftarId+"&reqPegawaiId="+reqPegawaiId+"&reqJadwalTesId="+reqJadwalTesId+"&reqFormulaAssesmentId="+reqFormulaAssesmentId+"&reqFormulaEselonId="+reqFormulaEselonId+"&reqUjianId="+reqUjianId+"&reqTipeUjianId="+reqTipeUjianId+"&reqUjianTahapId="+reqUjianTahapId;
				$.ajax({'url': s_url,'success': function(msg) {
					if(msg == '')
					{
						$.messager.alert('Info', data, 'info');
						document.location.href = '?pg=ujian_pauli&reqUjianTahapId=<?=$reqUjianTahapId?>';
					}
					else
					{
						if(msg == 20)
						{
							document.location.href = '?pg=ujian_pauli&reqUjianTahapId=<?=$reqUjianTahapId?>';
							// var s_url= "../json-ujian/ujian_online_finish.php?reqPegawaiId="+reqPegawaiId+"&reqUjianId="+reqUjianId+"&reqUjianPegawaiDaftarId="+reqUjianPegawaiDaftarId+"&reqTipeUjianId="+reqTipeUjianId+"&reqId=<?=$reqUjianTahapId?>";
							// // console.log(s_url);return false;
							// $.ajax({'url': s_url,'success': function(msg) {
							// 	if(msg == ''){}
							// 	else
							// 	{
							// 		document.location.href = '?pg=ujian_pilihan';
							// 	}
							// }});
						}
						else
						{
							$.messager.alert('Info', data, 'info');
							document.location.href = '?pg=ujian_pauli&reqUjianTahapId=<?=$reqUjianTahapId?>';
						}
						
					}
				}});

				
			}
		});

		$('input[id^="reqDataXY"]').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');

			totalmengerjakan= 0;
			totalmengerjakan= setcheckdata();
			// console.log(totalmengerjakan);

			if(totalmengerjakan == 50)
				setGoSimpan();
			// console.log(totalmengerjakan);
		});

		<?
		if($x_bawah_batas > 1)
		{
			?>
			$(function(){
				var xdata= "<?=$x_bawah_batas?>";
				// console.log("-"+xdata);return false;
				$('[id^="reqSoalNoneRow-'+xdata+'"]').hide();
				$('[id^="reqSoalRow-'+xdata+'"]').show();
				
				settime(xdata);

				$("#reqDataXY-"+xdata+"-1").focus();
			});
			<?
		}
		?>

		$('a[id^="reqMulai"]').click( function(e) {
			var id= $(this).attr('id');
			arrTempId= String(id);
			arrTempId= arrTempId.split('-');
			x= arrTempId[arrTempId.length-1];

			$('[id^="reqSoalNoneRow-'+x+'"]').hide();
			$('[id^="reqSoalRow-'+x+'"]').show();

			if(totalmelakukanujian == "<?=$batas_soal?>")
			{
				$(function(){
					$("#btnsimpan").show();
				});
			}
			
			$("#reqMulai-"+x).hide();
			// $("#reqStop-"+x).show();

			settime(x);

			$("#reqDataXY-"+x+"-1").focus();
			totalmelakukanujian= totalmelakukanujian+1;

			// ganti label button
			$("#infobutton").text("SIMPAN");
			if(parseInt(totalmelakukanujian) > 2)
				$("#infobutton").text("LANJUT");

			// console.log("a:"+totalmelakukanujian);

			// console.log(x);
		});

		$('input[id^="reqDataXY"]').keyup( function(e) {
			var id= $(this).attr('id');
			arrTempId= String(id);
			arrTempId= arrTempId.split('-');

			y= arrTempId[arrTempId.length-1];
			x= arrTempId[arrTempId.length-2];

			// $("#arrCheckXYData").val(x+"-"+y);

			y= parseInt(y) + 1;

			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			// console.log(key);
			if(key == 13 || key == 9) {
				// e.preventDefault();

				arrSelectXYData= ambilnextdata();
				// console.log(arrSelectXYData);
				// $("#reqDataXY-"+x+"-"+y).focus();
				$("#reqDataXY-"+arrSelectXYData).focus();

				// console.log(x+"-"+y);
				// console.log(id+"-"+x+"-"+y);

				sct = parseInt($('.area-sudah').scrollTop()) + parseInt(22);
				// console.log(scl);
				// if(scl == 1)
				$('.area-sudah').animate({ scrollTop: sct }, 'slow', function () {});
			}

		});

		// note disabled to readonly
		$('[id^="reqDataXY"]').prop("disabled", true);
		$("#bawahlihat").focus();

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

			// naik keatas kalau nilai number
			if(isNaN(parseInt(tempValue)) == false)
			{
				// console.log(y);
				y= parseInt(y) + 1;

				// $("#reqDataXY-"+x+"-"+y).prop("disabled", false);
				$("#reqDataXY-"+x+"-"+y).focus();

				sct = parseInt($('.area-sudah').scrollTop()) + parseInt(22);
				// console.log(scl);
				// if(scl == 1)
				$('.area-sudah').animate({ scrollTop: sct }, 'slow', function () {});
			}
		});
	});
	
</script>

<style type="text/css">
.container.utama.ujianinfo
{
	width: calc(20%) !important;
}
</style>

<!-- style="width: calc(20%) !important;" -->

<div class="container utama">
	<!-- <div class="container utama ujianinfo"> -->
		<div class="row">

			<div class="col-md-12" id="reqsisawaktu" style="display: none;">
				<div class="area-sisa-waktu">
					<div class="judul"><i class="fa fa-clock-o"></i> Sisa Waktu :</div>
					<div class="waktu">
						<div id="divCounter-info"><?=$tempInfoWaktuUtama?></div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="area-judul-halaman">
					Tes Pauli
					<span id="btnsimpan" style="display: none; float:right" class="lengkapimodif-data"><a href="#" onclick="setSimpan()"><label id="infobutton"></label> &raquo;</a></span>
				</div>
			</div>
		</div>

		<form id="ff" method="post" novalidate>
			<div class="row">
				<div class="col-md-12">
					<div class="area-soal ujian-pauli">
						<div class="area-sudah finish">
							<div id="bawahlihat" tabindex="-1"></div>
							<table style="width: 100%" border="0">
								<tr>
									<?
									for($x=$x_bawah_batas; $x <= $x_batas; $x++)
                					// for($x=0; $x <= -1; $x++)
									{
									?>
									<td>
										<!-- style="display: none;" -->
										<div class="waktu">
											<!-- <div class="waktu"> -->
												<!-- <label id="divCounter-<?=$x?>">10:10</label> -->
												<div class="waktu-counter" id="divCounter-<?=$x?>"><?=$tempInfoWaktu?></div>
												<div class="btn-stop" id="reqStop-<?=$x?>" style="cursor: pointer;">Stop</div>
											</div>
											<div class="area-kolom">
												<table style="width: 100%" border="0">
													<tr>
														<td colspan="2" class="area-btn-mulai">
															<?
															if($x == $x_bawah_batas && $x == 1)
															{
																?>
																<a id="reqMulai-<?=$x?>" class="btn-mulai" style="cursor: pointer;" ><label>Mulai</label></a>
																<?php /*?><a id="reqStop-<?=$x?>" style="cursor: pointer;" ><label>Stop</label></a><?php */?>
																<?
															}
															?>
														</td>
													</tr>
													<?
													for($y=1; $y <= $y_batas; $y++)
                									// for($y=1; $y <= 1; $y++)
													{
														$y_jawab= $y;

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
															<td class="soal-nilai"><span id='reqSoalNoneRow-<?=$x."-".$y_jawab?>'>-</span><span style="display: none;" id='reqSoalRow-<?=$x."-".$y_jawab?>'><?=$reqSoalNilai?></span></td>
															<td> </td>
														</tr>
														<?
														if($y_jawab < $y_batas)
														{
												    	// $tempCoba= $x;
												    	//$x.$y_jawab
														?>
															<tr class='jwb' id='reqRow-<?=$x."-".$y_jawab?>'>
																<td> </td>
																<td>
																	<!-- style="margin-top: -17px !important" -->
																	<div class="area-input-krp">
																		<input type="hidden" name="reqXdata[]" value="<?=$x?>" />
																		<input type="hidden" name="reqYdata[]" value="<?=$y_jawab?>" />
																		<input type="hidden" name="reqXYdataNilai[]" id="reqXYdataNilai-<?=$x."-".$y_jawab?>" value="<?=$tempCoba?>" />
																		<input type="text" id="reqDataXY-<?=$x."-".$y_jawab?>" maxlength="1" value="" class="inputkrp" autocomplete="off" />
																	</div>
																</td>
															</tr>
														<?
														}
													}
													?>
												</table>
												</div>
											</td>
										<?
										}
										?>
									</tr>
								</table>

								</div>
							</div>
						</div>

						<input type="hidden" id="arrCheckXYData" name="arrCheckXYData" value="" />
						<input type="hidden" id="reqPakaiPauliId" name="reqPakaiPauliId" value="<?=$reqPakaiPauliId?>" />
						<input type="hidden" id="reqUjianPegawaiDaftarId" value="<?=$reqUjianPegawaiDaftarId?>" />
						<input type="hidden" id="reqPegawaiId" value="<?=$reqPegawaiId?>" />
						<input type="hidden" id="reqJadwalTesId" value="<?=$reqJadwalTesId?>" />
						<input type="hidden" id="reqFormulaAssesmentId" value="<?=$reqFormulaAssesmentId?>" />
						<input type="hidden" id="reqFormulaEselonId" value="<?=$reqFormulaEselonId?>" />
						<input type="hidden" id="reqUjianId" value="<?=$reqUjianId?>" />
						<input type="hidden" id="reqTipeUjianId" value="<?=$reqTipeUjianId?>" />
						<input type="hidden" id="reqUjianTahapId" value="<?=$reqUjianTahapId?>" />

						<div class="area-prev-next">
							<div class="kembali-home">
								<span style="display: none;" class="ke-home"><a href="?pg=dashboard"><i class="fa fa-home"></i> Kembali ke halaman utama <!--&raquo;--></a></span>
							</div>
						</div>

					</div>
				</form>

			</div>