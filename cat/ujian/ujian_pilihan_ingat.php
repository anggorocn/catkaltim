<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/UjianTahapPegawai.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");

include_once("../WEB/classes/base-cat/UjianTahapStatusUjianMulaiIngat.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjianIngat.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/functions/infotipeujian.func.php");

if($userLogin->ujianUid == "")
{
	if($pg == "" || $pg == "home"){}
	else
	{
		echo '<script language="javascript">';
		echo 'top.location.href = "index.php";';
		echo '</script>';
		exit;
	}
}

flush();
ob_flush();

date_default_timezone_set('Asia/Jakarta');

$reqId= httpFilterGet("reqId");
$tempPegawaiId= $userLogin->pegawaiId;
$reqinfoid= $reqId."-ingat-".$tempPegawaiId;
// echo $reqinfoid;exit;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$tempUjianId= $ujianPegawaiUjianId;
// echo $tempUjianId;exit();

$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$ujianPegawaiUjianId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianTahap();
$set->selectByParamsUjianPegawaiTahap(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();
$tempUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$tempTipeUjianId= $set->getField("TIPE_UJIAN_ID");
// $tempTipeUjianId= 27;
// echo $tempTipeUjianId;exit();
$tempParentId= $set->getField("PARENT_ID");
$tempPanjangTipeUjianId= strlen($set->getField("PARENT_ID"));
$tempStatusTipeUjianId= $set->getField("STATUS_TAHAP_UJIAN");
$tempTipeTahap= $set->getField("TIPE");
unset($set);

$tempInfoJudul= "Ujian ".$tempTipeTahap;

// $tempInfoWaktuAwal= "00:00:10";
// $hoursInfo= "0";
// $minutesInfo= "0";
// $secondInfo= "10";

$tempInfoWaktuAwal= "00:03:00";
$hoursInfo= "0";
$minutesInfo= "3";
$secondInfo= "0";
// echo "Dadad"; exit;
// $tempInfoWaktuAwal= "00:03:00";
// $hoursInfo= "0";
// $minutesInfo= "0";
// $secondInfo= "5";

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TIPE_UJIAN_ID = ".$tempTipeUjianId." AND A.UJIAN_ID = ".$tempUjianId;
$set= new UjianTahapStatusUjianMulaiIngat();
$tempCheckDataMulaiIngat= $set->getCountByParams(array(), $statement);
// echo $set->query;exit;
// echo $tempCheckDataMulaiIngat;exit();

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TIPE_UJIAN_ID = ".$tempTipeUjianId." AND A.UJIAN_ID = ".$tempUjianId;
$set= new UjianTahapStatusUjianIngat();
$tempCheckDataIngat= $set->getCountByParams(array(), $statement);
// echo $set->query;exit;
// echo $tempCheckDataIngat;exit();

?>
<!-- Optionally use Animate.css -->
<link rel="stylesheet" href="../WEB/lib-ujian/bootstrap/animate.min.css">
<link rel="stylesheet" href="../WEB/lib-ujian/liquidslider-master/css/liquid-slider.css">

<script src="../WEB/lib-ujian/bootstrap/jquery.min.js"></script>
<script src="../WEB/lib-ujian/bootstrap/jquery.easing.min.js"></script>
<script src="../WEB/lib-ujian/bootstrap/jquery.touchSwipe.min.js"></script>
<script src="../WEB/lib-ujian/liquidslider-master/src/js/jquery.liquid-slider.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>

<script language="javascript">
	var currentRevisiPage= interval= "";

	<?
	if($tempCheckDataMulaiIngat == 0)
	{
	?>
		localStorage.removeItem('end<?=$reqinfoid?>');
		// localStorage.clear();
	<?
	}
	?>

	// localStorage.clear();

	<?
	if($tempUjianReset == "")
	{
		if($tempTipeUjianId == 27){}
		else
		{
	?>
	// clearInterval(interval);
	// clearTimeout(interval);
	// localStorage.setItem("end", null);
	// localStorage.clear();
	<?
		}
	}
	?>

	
	//cut copy paste copas
	// $('input[id^="reqText"]').on("cut copy paste", function (e) {
	// 		e.preventDefault();
	// });

	function settime(id)
	{
		// localStorage.removeItem("end"+id);
		divCounter= "divCounter"+id;
		var difflog= "";
		var tempmin= "";


		var hoursleft = <?=$hoursInfo?>;
		var minutesleft = <?=$minutesInfo?>;
		var secondsleft = <?=$secondInfo?>; 

		var finishedtext = "Countdown finished!";
		var end;
		// var endlog;
		if(localStorage.getItem("end<?=$reqinfoid?>")) {
			end = new Date(localStorage.getItem("end<?=$reqinfoid?>"));
			// endlog = new Date(localStorage.getItem("endlog"));
		} else {
		   end = new Date();
		   end.setHours(end.getHours()+hoursleft);
		   end.setMinutes(end.getMinutes()+minutesleft);
		   end.setSeconds(end.getSeconds()+secondsleft);
		   
		   // endlog= new Date();
		   // endlog.setHours(endlog.getHours()+hourslog);
		   // endlog.setMinutes(endlog.getMinutes()+minuteslog);
		   // endlog.setSeconds(endlog.getSeconds()+secondslog);
		}


		var counter = function () {

			var now = new Date();
			var sec_now = now.getSeconds();
			var min_now = now.getMinutes(); 
			var hour_now = now.getHours(); 
			
			var sec_end = end.getSeconds();
			var min_end = end.getMinutes(); 
			var hour_end = end.getHours();
			
			// var sec_log = endlog.getSeconds();
			// var min_log = endlog.getMinutes(); 
			// var hour_log = endlog.getHours();
			
			var date1 = new Date(2000, 0, 1, hour_now,  min_now, sec_now); // 9:00 AM
			var date2 = new Date(2000, 0, 1, hour_end, min_end, sec_end); // 5:00 PM
			// var datelog= new Date(2000, 0, 1, hour_log, min_log, sec_log);
			if (date2 < date1) {
				date2.setDate(date2.getDate() + 1);
			}
			var diff = date2 - date1;
			
			// difflog= datelog - date2;
			
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
				// localStorage.setItem("endlog", null);

				if(id == "info")
				{
					document.getElementById('divCounter').innerHTML = "00:00";
					clearTimeout(interval);	

					setInfoFinish();
					// console.log("tutup ujian1");

					// var s_url= "../json-ujian/ujian_tahap_status_ujian_ingat.php?reqUjianId=<?=$tempUjianId?>&reqTipeUjianId=<?=$tempTipeUjianId?>";
					// // console.log(s_url);return false;
					// $.ajax({'url': s_url,'success': function(msg) {
					// 	if(msg == '0'){}
					// 	else
					// 	{
					// 		clearInterval(interval);
					// 		clearTimeout(interval);
					// 		localStorage.setItem("end", null);
					// 		localStorage.clear();

					// 		// document.location.href= "?pg=ujian_pilihan";
					// 	}
					// }});
					
				}
				else
				{
					<?
					if($tempCheckDataIngat == "0")
					{
					?>
					clearTimeout(interval);	
					<?
					}
					?>
					setKondisiInfoFinish();
				}

			} else {
				var value = hour + ":" + min + ":" + sec;
				localStorage.setItem("end<?=$reqinfoid?>", end);
				// localStorage.setItem("endlog", endlog);
				document.getElementById('divCounter').innerHTML = value;
				if(min.toString() == 'NaN')
				{
					// localStorage.setItem("endlog", null);

					if(id == "info")
					{

						localStorage.setItem("waktuberakhir", "00:00");
						clearTimeout(interval);	

						setInfoFinish();
						// console.log("tutup ujian2");

						// var s_url= "../json-ujian/ujian_tahap_status_ujian_ingat.php?reqUjianId=<?=$tempUjianId?>&reqTipeUjianId=<?=$tempTipeUjianId?>";
						// // console.log(s_url);return false;
						// $.ajax({'url': s_url,'success': function(msg) {
						// 	if(msg == '0'){}
						// 	else
						// 	{
						// 		localStorage.setItem("waktuberakhir", "00:00");
						// 		clearTimeout(interval);	
						// 		localStorage.setItem("end", null);

						// 		clearInterval(interval);
						// 		clearTimeout(interval);
						// 		localStorage.setItem("end", null);
						// 		localStorage.clear();

						// 		// document.location.href= "?pg=ujian_pilihan";
						// 	}
						// }});

						
					}
					else
					{
						<?
						if($tempCheckDataIngat == "0")
						{
						?>
						clearTimeout(interval);	
						<?
						}
						?>
						setKondisiInfoFinish();
					}
					
				}
			}
		
		}
		interval = setInterval(counter, 1);
	}

	$(function(){
		if("<?=$tempCheckDataMulaiIngat?>" == "0")
		{
			// $("#reqsisawaktu").show();
			// $(".reqInfotable").show();
			// // $(".reqInfoIsi").hide();
			// settime("info");

			var s_url= "../json-ujian/ujian_tahap_status_ujian_ingat_cek.php?reqUjianId=<?=$tempUjianId?>&reqUjianTahapId=<?=$reqId?>";
			// console.log(s_url);return false;
			$.ajax({'url': s_url,'success': function(msg) {
				if(msg == '0')
				{
					$("#reqsisawaktu").show();
					$(".reqInfotable").show();
					$(".reqInfoIsi").hide();
					settime("info");
				}
				else
					setInfoFinish();
					// document.location.href= "?pg=ujian_pilihan";
			}});

		}
		else
			setInfoFinish();


	});
	
</script>

<div class="container utama">
	<div class="row">
		 <!-- style="display: none;" -->
    	<div class="col-md-12" id="reqsisawaktu">
            <div class="area-sisa-waktu">
                <div class="judul"><i class="fa fa-clock-o"></i> Sisa Waktu :</div>
                <div class="waktu">
                    <div id="divCounter"></div>
                </div>
            </div>
        </div>
    	
        <div class="col-md-9 area-soal-wrapper">

        	<div class="area-soal reqInfotable">

        		<table style="width: 100%" border="0" id='reqText'>
        			<tr>
        				<td>
        					Di sediakan waktu 3 menit untuk menghapalkan kata-kata di bawah ini
        					<table style="width: 100%; " border="0">
        						<tr>
        							<td style="padding: 11px; vertical-align: top; ">BUNGA</td>
        							<td style="padding: 11px; vertical-align: top; ">:</td>
        							<td style="padding: 11px; vertical-align: top; ">Soka	Larat	Flamboyan	Yasmin	Dahlia</td>
        						</tr>
        						<tr>
        							<td style="padding: 11px; vertical-align: top; ">PERKAKAS</td>
        							<td style="padding: 11px; vertical-align: top; ">:</td>
        							<td style="padding: 11px; vertical-align: top; ">Wajan	Jarum	Kikir	Cangkul	Palu</td>
        						</tr>
        						<tr>
        							<td style="padding: 11px; vertical-align: top; ">BURUNG</td>
        							<td style="padding: 11px; vertical-align: top; ">:</td>
        							<td style="padding: 11px; vertical-align: top; ">Elang	Itik	Walet	Tekukur	Nuri</td>
        						</tr>
        						<tr>
        							<td style="padding: 11px; vertical-align: top; ">KESENIAN</td>
        							<td style="padding: 11px; vertical-align: top; ">:</td>
        							<td style="padding: 11px; vertical-align: top; ">Quintet	Arca	Opera	Gamelan	Ukiran</td>
        						</tr>
        						<tr>
        							<td style="padding: 11px; vertical-align: top; ">BINATANG</td>
        							<td style="padding: 11px; vertical-align: top; ">:</td>
        							<td style="padding: 11px; vertical-align: top; ">Musang	Rusa	Beruang	Zebra	Harimau</td>
        						</tr>
        					</table>
        					<br/><br/><br/><br/>
        				</td>
        			</tr>
        		</table>

        	</div>
        
        </div>
        
	</div>
        
    
    </div>
</div>

<link rel="stylesheet" type="text/css" href="../WEB/css-ujian/gayainfo.css">

<script type="text/javascript">

//cut copy paste copas

$('#reqText').on("cut copy paste", function (e) {
		e.preventDefault();
});

function setBacaTipeUjian(tipeujianid)
{
	// var win = $.messager.progress({title:'Proses Data',msg:'Proses data...'});
	var s_url= "../json-ujian/ujian_tahap_status_ujian_petunjuk.php?reqUjianId=<?=$tempUjianId?>&reqTipeUjianId="+tipeujianid;
	// console.log(s_url);return false;
	$.ajax({'url': s_url,'success': function(msg) {
		if(msg == '0')
		{
			$.messager.alert('Error', "Data gagal disimpan.", 'error');	
			$.messager.progress('close'); 
		}
		else
		{
			$.messager.progress('close'); 
			$.messager.alert('Informasi', "Silahkan melakukan Ujian.", 'info');	

			// habis baca langsung ujian
			var s_url= "../json-ujian/ujian_tahap_status_ujian_petunjuk_cek_tipe_ujian.php?reqUjianId=<?=$tempUjianId?>&reqTipeUjianId="+tipeujianid;
			// console.log(s_url);return false;
			$.ajax({'url': s_url,'success': function(msg) {
			if(msg == '0')
			{
				$.messager.alert('Info', "Harap membaca instruksi terlebih dahulu, sebelum ujian(klik OK)", 'info');
			}
			else
			{
				// localStorage.clear();
				document.location.href= "?pg=ujian_online&reqId="+msg;
			}
		}});

		}
	}});
}

<?
if($tempCheckDataIngat == "1")
{
?>
	// $(".reqInfotable").hide();
<?
}
?>

function setKondisiInfoFinish()
{
	<?
	if($tempCheckDataIngat == "0")
	{
		// set mulai pernah ingat
		$statement= " AND B.TIPE_UJIAN_ID = ".$tempTipeUjianId." AND A.UJIAN_ID = ".$tempUjianId." AND A.PEGAWAI_ID = ".$tempPegawaiId;
		$set= new UjianTahapStatusUjianIngat();
		$set->setField("LAST_CREATE_USER", $userLogin->nama);
		$set->setField("LAST_CREATE_DATE", "NOW()");
		if($set->insertQueryModif($statement))
		{
	?>
		document.location.href= "?pg=ujian_pilihan_ingat&reqId=<?=$reqId?>";
	<?
		}
	?>
		// var s_url= "../json-ujian/ujian_tahap_status_ujian_ingat_cek.php?reqUjianId=<?=$tempUjianId?>&reqUjianTahapId=<?=$reqId?>";
		// // console.log(s_url);return false;
		// $.ajax({'url': s_url,'success': function(msg) {
		// 	if(msg == '0')
		// 	{
				// var s_url= "../json-ujian/ujian_tahap_status_ujian_ingat.php?reqUjianId=<?=$tempUjianId?>&reqTipeUjianId=<?=$tempTipeUjianId?>";
				// // console.log(s_url);return false;
				// $.ajax({'url': s_url,'success': function(msg) {
				// 	document.location.href= "?pg=ujian_pilihan_ingat&reqId=<?=$reqId?>";
				// }});
		// 	}
		// }});
	<?
	}
	else
	{
	?>

		$(function(){
			$('#infoselesaiujian').firstVisitPopup({
				showAgainSelector: '#show-message'
			});
		});
		// $(".reqInfotable").hide();

	<?
	}
	?>
}

function setInfoFinish()
{
	settime("");
}

</script>

<?
// set mulai pernah ingat
$statement= " AND B.TIPE_UJIAN_ID = ".$tempTipeUjianId." AND A.UJIAN_ID = ".$tempUjianId." AND A.PEGAWAI_ID = ".$tempPegawaiId;
$set= new UjianTahapStatusUjianMulaiIngat();
$set->setField("LAST_CREATE_USER", $userLogin->nama);
$set->setField("LAST_CREATE_DATE", "NOW()");
if($set->insertQueryModif($statement)){}

$set= new TipeUjian();
$set->selectByParams(array(), -1,-1, " AND TIPE_UJIAN_ID = 27");
// echo $set->errorMsg;exit();
// echo $set->query;exit();
while($set->nextRow())
{
?>
<div class="my-welcome-message" id="infoselesaiujian">
    <div class="konten-welcome" style="height:100%;">
    <div class="row" style="height:100%;">
    	<div class="col-md-12" style="height:100%;">
        	<div class="area-judul-halaman">Instruksi Pengerjaan Soal Ujian</div>
        	<div class="area-tatacara" style="height:calc(100% - 60px); overflow:auto; padding:0 0;"> 
                <ul style="list-style:none !important; list-style-type:none !important;">
                    <li>
                    	<?=setinfo($set->getField("TIPE_UJIAN_ID"))?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </div>
</div>
<?
}
?>

<script src="../WEB/lib/first-visit-popup-master/jquery.firstVisitPopup.js"></script>