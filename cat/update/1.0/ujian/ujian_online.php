<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/UjianTahapPegawai.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");

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

$reqId= httpFilterGet("reqId");

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

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
$tempPanjangTipeUjianId= strlen($set->getField("PARENT_ID"));
$tempStatusTipeUjianId= $set->getField("STATUS_TAHAP_UJIAN");
$tempTipeTahap= $set->getField("TIPE");
unset($set);
// echo $tempUjianPegawaiDaftarId;exit();

if($tempStatusTipeUjianId == "1")
{
	echo '<script language="javascript">';
	echo 'top.location.href = "?pg=ujian_pilihan";';
	echo '</script>';
	exit;
}

//kl 7 maka papikostik
if($tempTipeUjianId == "7")
{
	$sOrder= "ORDER BY A.UJIAN_ID, B.BANK_SOAL_ID, UP.UJIAN_PEGAWAI_ID";
	
	$index_loop=0;
	$arrJumlahSoalPegawai="";

	$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ".$reqId." AND A.UJIAN_ID = ".$tempUjianId;
    $statementujian= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND A.UJIAN_TAHAP_ID = ".$reqId;
	$set= new UjianPegawaiDaftar();
	$set->selectByParamsSoalPapi(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement, $statementujian, $sOrder);
	// echo $set->query;exit;
	$tempValueRowId= "";
	while($set->nextRow())
	{
		if($set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID") == $tempValueRowId){}
		else
		{
			$nomor= $index_loop+1;
			$arrJumlahSoalPegawai[$index_loop]["ID_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
			$arrJumlahSoalPegawai[$index_loop]["NOMOR"]= $nomor;
			$arrJumlahSoalPegawai[$index_loop]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");
			$arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"]= $set->getField("UJIAN_ID");
			$arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set->getField("UJIAN_BANK_SOAL_ID");
			$arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
			$arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"]= $set->getField("BANK_SOAL_PILIHAN_ID");
			$arrJumlahSoalPegawai[$index_loop]["KEMAMPUAN"]= $set->getField("KEMAMPUAN");
			$arrJumlahSoalPegawai[$index_loop]["KATEGORI"]= $set->getField("KATEGORI");
			$arrJumlahSoalPegawai[$index_loop]["PERTANYAAN"]= $set->getField("PERTANYAAN");
			$arrJumlahSoalPegawai[$index_loop]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
			$arrJumlahSoalPegawai[$index_loop]["PATH_GAMBAR"]= $set->getField("PATH_GAMBAR");
			$arrJumlahSoalPegawai[$index_loop]["PATH_SOAL"]= $set->getField("PATH_SOAL");
		
			$index_loop++;
		}
		$tempValueRowId= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
		
	}
	$tempJumlahSoalPegawai= $index_loop;
	unset($set);
	// print_r($arrJumlahSoalPegawai);exit;
	
	$sOrder= "ORDER BY A.UJIAN_ID, B.BANK_SOAL_ID";
	$index_loop=0;
	$arrJumlahJawabanSoalPegawai="";

	$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ".$reqId." AND COALESCE(NULLIF(D.JAWABAN, ''), NULL) IS NOT NULL AND A.UJIAN_ID = ".$tempUjianId;

    $set= new UjianPegawaiDaftar();
	$set->selectByParamsJawabanSoalPapi(array(), -1,-1, $statement, $sOrder);
	// echo $set->query;exit;
	while($set->nextRow())
	{
		$arrJumlahJawabanSoalPegawai[$index_loop]["ID_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["ID_VAL_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("UJIAN_BANK_SOAL_ID")."-".$set->getField("BANK_SOAL_ID")."-".$set->getField("BANK_SOAL_PILIHAN_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["UJIAN_ID"]= $set->getField("UJIAN_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set->getField("UJIAN_BANK_SOAL_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"]= $set->getField("BANK_SOAL_PILIHAN_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["JAWABAN"]= $set->getField("JAWABAN");
		$arrJumlahJawabanSoalPegawai[$index_loop]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
		$arrJumlahJawabanSoalPegawai[$index_loop]["PATH_GAMBAR"]= $set->getField("PATH_GAMBAR");
		$arrJumlahJawabanSoalPegawai[$index_loop]["PATH_SOAL"]= $set->getField("PATH_SOAL");
		$index_loop++;
	}
	$tempJumlahJawabanSoalPegawai= $index_loop;
	unset($set);
	// print_r($arrJumlahJawabanSoalPegawai);exit();
}
else
{
	$sOrder= "ORDER BY URUT, RANDOM()";
	if($tempPanjangTipeUjianId == 2)
	$sOrder= "ORDER BY UP.URUT, A.UJIAN_ID, B.BANK_SOAL_ID, UP.UJIAN_PEGAWAI_ID";
	
	$index_loop=0;
	$arrJumlahSoalPegawai="";

	$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ".$reqId." AND A.UJIAN_ID = ".$tempUjianId;
    $statementujian= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND A.UJIAN_TAHAP_ID = ".$reqId;
	$set= new UjianPegawaiDaftar();
	$set->selectByParamsSoalRevisi(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement, $statementujian, $sOrder);
	// echo $set->query;exit;
	$tempValueRowId= "";
	while($set->nextRow())
	{
		if($set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID") == $tempValueRowId){}
		else
		{
			$nomor= $index_loop+1;
			$arrJumlahSoalPegawai[$index_loop]["ID_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
			$arrJumlahSoalPegawai[$index_loop]["NOMOR"]= $nomor;
			$arrJumlahSoalPegawai[$index_loop]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");
			$arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"]= $set->getField("UJIAN_ID");
			$arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set->getField("UJIAN_BANK_SOAL_ID");
			$arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
			$arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"]= $set->getField("BANK_SOAL_PILIHAN_ID");
			$arrJumlahSoalPegawai[$index_loop]["KEMAMPUAN"]= $set->getField("KEMAMPUAN");
			$arrJumlahSoalPegawai[$index_loop]["KATEGORI"]= $set->getField("KATEGORI");
			$arrJumlahSoalPegawai[$index_loop]["PERTANYAAN"]= $set->getField("PERTANYAAN");
			$arrJumlahSoalPegawai[$index_loop]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
			$arrJumlahSoalPegawai[$index_loop]["PATH_GAMBAR"]= $set->getField("PATH_GAMBAR");
			$arrJumlahSoalPegawai[$index_loop]["PATH_SOAL"]= $set->getField("PATH_SOAL");
		
			$index_loop++;
		}
		$tempValueRowId= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
		
	}
	$tempJumlahSoalPegawai= $index_loop;
	unset($set);
	// print_r($arrJumlahSoalPegawai);exit;
	
	$sOrder= "ORDER BY RANDOM()";
	//$sOrder= "ORDER BY B.UJIAN_BANK_SOAL_ID";
	$index_loop=0;
	$arrJumlahJawabanSoalPegawai="";

	$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ".$reqId." AND COALESCE(NULLIF(D.JAWABAN, ''), NULL) IS NOT NULL AND A.UJIAN_ID = ".$tempUjianId;

    $set= new UjianPegawaiDaftar();
	$set->selectByParamsJawabanSoal(array(), -1,-1, $statement, $sOrder);
	// echo $set->query;exit;
	while($set->nextRow())
	{
		$arrJumlahJawabanSoalPegawai[$index_loop]["ID_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["ID_VAL_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("UJIAN_BANK_SOAL_ID")."-".$set->getField("BANK_SOAL_ID")."-".$set->getField("BANK_SOAL_PILIHAN_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["UJIAN_ID"]= $set->getField("UJIAN_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set->getField("UJIAN_BANK_SOAL_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"]= $set->getField("BANK_SOAL_PILIHAN_ID");
		$arrJumlahJawabanSoalPegawai[$index_loop]["JAWABAN"]= $set->getField("JAWABAN");
		$arrJumlahJawabanSoalPegawai[$index_loop]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
		$arrJumlahJawabanSoalPegawai[$index_loop]["PATH_GAMBAR"]= $set->getField("PATH_GAMBAR");
		$arrJumlahJawabanSoalPegawai[$index_loop]["PATH_SOAL"]= $set->getField("PATH_SOAL");
		$index_loop++;
	}
	$tempJumlahJawabanSoalPegawai= $index_loop;
	unset($set);
	// print_r($arrJumlahJawabanSoalPegawai);exit();
}

//kalau data 0 maka simpan data ke pegawai log
$statement= " AND UJIAN_TAHAP_ID = ".$reqId." AND UJIAN_ID = ".$tempUjianId." AND PEGAWAI_ID = ".$tempPegawaiId." AND TIPE_UJIAN_ID = ".$tempTipeUjianId;
$set= new UjianTahapPegawai();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit;
$tempLogWaktu= $set->getField("LOG_WAKTU");
$tempLogUjianPegawaiTahapMenit=$tempLogWaktu;
//$jumlahDataPegawaiUjianTahap= $set->getCountByParams(array(), $statement);
unset($set);

if($tempLogWaktu == "")
{
	$tempOneSystemTanggalNow= date("d-m-Y H:i:s");
	// TO_TIMESTAMP('".$_date." ".$arrDateTime[1]."', 'YYYY-MM-DD HH24:MI:SS')

	$set= new UjianTahapPegawai();
	$set->setField("JADWAL_TES_ID", $ujianPegawaiJadwalTesId);
	$set->setField("FORMULA_ASSESMENT_ID", $ujianPegawaiFormulaAssesmentId);
	$set->setField("FORMULA_ESELON_ID", $ujianPegawaiFormulaEselonId);
	$set->setField("UJIAN_TAHAP_ID", $reqId);
	$set->setField("UJIAN_PEGAWAI_DAFTAR_ID", $tempUjianPegawaiDaftarId);
	$set->setField("UJIAN_ID", $tempUjianId);
	$set->setField("PEGAWAI_ID", $tempPegawaiId);
	$set->setField("TIPE_UJIAN_ID", $tempTipeUjianId);
	$set->setField("WAKTU_UJIAN", dateTimeToDBCheck($tempOneSystemTanggalNow));
	// $set->setField("WAKTU_UJIAN", "NOW()");
	$set->insert();
	// echo $set->query;exit;
	unset($set);
}
else
{
	$set= new UjianTahapPegawai();
	$set->setField("UJIAN_TAHAP_ID", $reqId);
	$set->setField("UJIAN_ID", $tempUjianId);
	$set->setField("PEGAWAI_ID", $tempPegawaiId);
	$set->setField("TIPE_UJIAN_ID", $tempTipeUjianId);
	$set->setField("WAKTU_UJIAN_LOG", "NOW()");
	// $set->update();
	unset($set);
}

$statement= " AND A.UJIAN_ID = ".$ujianPegawaiUjianId;
$set= new Ujian();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
$tempValUjianId= $tempUjianId;
// echo $tempValUjianId;exit();
$final_time_saving= $set->getField("BATAS_WAKTU_MENIT");
$hours= floor($final_time_saving / 60);
$minutes= $final_time_saving % 60;
unset($set);
// echo $final_time_saving;exit();

$statement= " AND COALESCE(B.MENIT_SOAL,0) > 0 AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianTahap();
$set->selectByParamsUjianPegawaiTahap(array(), -1,-1, $statement, "ORDER BY ID");
$set->firstRow();
// echo $set->query;exit;
$tempMenitSoal= explode(".",$set->getField("MENIT_SOAL"));
// echo $tempMenitSoal[0];exit();
// echo $tempLogWaktu;exit();
// echo print_r($tempMenitSoal);exit();
$tempParent= $set->getField("LENGTH_PARENT");
$tempParent= "1";

// $tempUjianReset = $set->getField("UJIAN_PEGAWAI_RESET");
$final_time_saving= $tempMenitSoal[0] - $tempLogWaktu;
$final_time_saving_log= $tempMenitSoal[0];
$second= $tempMenitSoal[1];

if(strlen($second) == 1)
$second= $second."0";
else
$second= "00";

// echo $final_time_saving;exit;
// $final_time_saving= 1000;

//kl 7 maka papikostik
// if($tempTipeUjianId == "7")
// {
// 	$final_time_saving= 90;
// }
// else
// 	$final_time_saving= 30;

// $final_time_saving= 1;
$hours= floor($final_time_saving / 60);
$minutes= $final_time_saving % 60;

$hoursLog= floor($final_time_saving_log / 60);
$minutesLog= $final_time_saving_log % 60;

// echo $hours.":".$minutes.":".$second.">".$final_time_saving;exit;
unset($set);
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
	// untuk clear time
	// localStorage.clear();
	<?
	if($tempUjianReset == "")
	{
	?>
	// localStorage.clear();
	<?
	}
	?>
	var difflog= "";
	var tempLogUjianPegawaiTahapMenit= "";
	var tempLogUjianPegawaiTahapMenit= "<?=$tempLogUjianPegawaiTahapMenit?>";
	//tempLogUjianPegawaiTahapMenit= parseInt(tempLogUjianPegawaiTahapMenit) - 1;
	var tempmin= "";
	var hoursleft = <?=$hours?>;
	var minutesleft = <?=$minutes?>;
	//var minutesleft = parseFloat('30,5');
	var secondsleft = <?=$second?>; 
	
	var hourslog= <?=$hoursLog?>;
	var minuteslog= <?=$minutesLog?>;
	var secondslog= <?=$second?>; 
	
	var finishedtext = "Countdown finished!";
	var end;
	var endlog;
	if(localStorage.getItem("end")) {
		end = new Date(localStorage.getItem("end"));
		endlog = new Date(localStorage.getItem("endlog"));
	} else {
	   end = new Date();
	   end.setHours(end.getHours()+hoursleft);
	   end.setMinutes(end.getMinutes()+minutesleft);
	   end.setSeconds(end.getSeconds()+secondsleft);
	   
	   endlog= new Date();
	   endlog.setHours(endlog.getHours()+hourslog);
	   endlog.setMinutes(endlog.getMinutes()+minuteslog);
	   endlog.setSeconds(endlog.getSeconds()+secondslog);
	}
		
	var counter = function () {
		var now = new Date();
	
		var sec_now = now.getSeconds();
		var min_now = now.getMinutes(); 
		var hour_now = now.getHours(); 
		
		var sec_end = end.getSeconds();
		var min_end = end.getMinutes(); 
		var hour_end = end.getHours();
		
		var sec_log = endlog.getSeconds();
		var min_log = endlog.getMinutes(); 
		var hour_log = endlog.getHours();
		
		//alert(min_now+"-"+min_end);
		var date1 = new Date(2000, 0, 1, hour_now,  min_now, sec_now); // 9:00 AM
		var date2 = new Date(2000, 0, 1, hour_end, min_end, sec_end); // 5:00 PM
		var datelog= new Date(2000, 0, 1, hour_log, min_log, sec_log);
		if (date2 < date1) {
			date2.setDate(date2.getDate() + 1);
		}
		var diff = date2 - date1;
		//var difflog= (date2 - date1) - (datelog - date2);
		
		//if(difflog == "")
		difflog= datelog - date2;
		//else
		//difflog= date2 - date1;
		//var difflog= datelog - diff;
		
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
		
		var mseclog= difflog;
		var hhlog = Math.floor(mseclog / 1000 / 60 / 60);
		mseclog -= hhlog * 1000 * 60 * 60;
		var mmlog = Math.floor(mseclog / 1000 / 60);
		mseclog -= mmlog * 1000 * 60;
		var sslog = Math.floor(mseclog / 1000);
		mseclog -= sslog * 1000;
		
		var seclog = sslog;
		var minlog = mmlog; 
		var hourlog = 24 + hhlog;
		tempValLog= (parseInt(hourlog) * 60) + parseInt(minlog);
		//tempValLog= parseInt(minlog);
		
		if(tempLogUjianPegawaiTahapMenit == "")
		tempLogUjianPegawaiTahapMenit= tempValLog;
		
		//alert(date2+" == "+date1+" == "+hour+" == "+min+" == "+sec);
		//alert(date2+" == "+datelog);
		//alert(minlog);
		//alert(hourlog+":"+minlog+":"+tempValLog);
		if (min < 10) {
			min = "0" + min;
		}
		if (sec < 10) { 
			sec = "0" + sec;
		}
		
		// if(parseInt(tempmin) == parseInt(min)){}
		// else
		// {
		// 	tempLogUjianPegawaiTahapMenit= parseInt(tempLogUjianPegawaiTahapMenit) + 1;
		// 	//alert(tempLogUjianPegawaiTahapMenit+";"+tempmin+"= "+min);
		// 	tempmin= min;
		// 	var s_url= "../json-ujian/ujian_online_tahap_pegawai_log.php?reqUjianTahapId=<?=$reqId?>&reqUjianId=<?=$tempUjianId?>&reqPegawaiId=<?=$tempPegawaiId?>&reqTipeUjianId=<?=$tempTipeUjianId?>&reqLogWaktu="+tempLogUjianPegawaiTahapMenit;
		// 	//alert(s_url);return false;
		// 	$.ajax({'url': s_url,'success': function(msg) {
		// 		if(msg == ''){}
		// 		else
		// 		{
		// 		}
		// 	}});
		// 	//tempLogUjianPegawaiTahapMenit= parseInt(tempLogUjianPegawaiTahapMenit) + 1;
		// }
		
		if(now >= end) {     
			//alert('a');return false;
			clearTimeout(interval);
			localStorage.setItem("end", null);
			localStorage.setItem("endlog", null);
			var s_url= "../json-ujian/ujian_online_finish.php?reqPegawaiId=<?=$tempPegawaiId?>&reqUjianId=<?=$tempValUjianId?>&reqId=<?=$reqId?>";
			$.ajax({'url': s_url,'success': function(msg) {
				if(msg == ''){}
				else
				{
					document.location.href = '?pg=finish&reqId=<?=$reqId?>';
				}
			}});
			//document.location.href = '?pg=ujian_online';
			//document.getElementById('divCounter').innerHTML = finishedtext;
		} else {
			var value = hour + ":" + min + ":" + sec;
			localStorage.setItem("end", end);
			localStorage.setItem("endlog", endlog);
			document.getElementById('divCounter').innerHTML = value;
			if(min.toString() == 'NaN')
			{
				//alert('b');return false;
				localStorage.setItem("waktuberakhir", "00:00");
				clearTimeout(interval);	
				localStorage.setItem("end", null);
				localStorage.setItem("endlog", null);
				var s_url= "../json-ujian/ujian_online_finish.php?reqPegawaiId=<?=$tempPegawaiId?>&reqUjianId=<?=$tempValUjianId?>&reqId=<?=$reqId?>";
				$.ajax({'url': s_url,'success': function(msg) {
					if(msg == ''){}
					else
					{
						document.location.href = '?pg=finish&reqId=<?=$reqId?>';
					}
				}});
				//document.location.href = '?pg=ujian_online';
			}
		}
	}
	var interval = setInterval(counter, 1);
	
	$(function(){
		setSelesai();
		$('#main-slider').liquidSlider();
		setSelectedPertanyaan();
		//setSelectedPertanyaan();
		//var url= $("#reqHrefIdNext").attr("href");
		//$("#reqHrefIdNext").attr('href',"#2");
		//alert(url);
		
		//$("#reqIdNext").attr("href", "#3");
		
		$('a[id^="reqHrefNomor"]').click(function(e) {
			<?
			if($tempParent==1)
			{
			?>
			var rowid= $(this).attr('id').replace("reqHrefNomor", "");
			$('a[id^="reqHrefNomor"]').removeClass("pilih");
			$("#reqHrefNomor"+rowid).addClass("pilih");
			
			$("#reqIdPrev").hide();
			if(parseInt(rowid) > 1)
			{
				$("#reqIdPrev").show();
			}
			
			///setInfoChecked("1", "");
			<?
			}
			?>
		});	
		
		$('input[id^="reqRadio"]').change(function(e) {
			var tempId= $(this).attr('id');
			//alert(tempId);
			var tempValId= $(this).val();
			tempId= tempId.split('reqRadio');
			tempId= tempId[1].split('-');
			var tempNomor= tempUjianId= tempUjianBankSoalId= tempBankSoalId= tempBankSoalPilihanId= "";
			tempNomor= tempId[0];
			tempUjianId= tempId[1];
			tempUjianBankSoalId= tempId[2];
			tempBankSoalId= tempId[3];
			tempBankSoalPilihanId= tempId[4];
			$("#reqBankSoalPilihanId"+tempNomor+"-"+tempUjianId+"-"+tempUjianBankSoalId+"-"+tempBankSoalId).val(tempBankSoalPilihanId);
			//alert("#reqBankSoalPilihanId"+tempNomor+"-"+tempUjianId+"-"+tempUjianBankSoalId+"-"+tempBankSoalId+"---"+tempBankSoalPilihanId);
			/*$("#reqHrefNomor"+tempNomor).removeClass("sudah");
			$("#reqInfoChecked"+tempNomor).addClass("fa-circle");
			$("#reqInfoChecked"+tempNomor).removeClass("fa-check-circle");
			if(tempBankSoalPilihanId == "" || isNaN(tempBankSoalPilihanId)){}
			else
			{
				$("#reqHrefNomor"+tempNomor).addClass("sudah");
				$("#reqInfoChecked"+tempNomor).removeClass("fa-circle");
				$("#reqInfoChecked"+tempNomor).addClass("fa-check-circle");
			}*/
			
			setInfoChecked("2", tempNomor);
		});
		
		//fa-check-circle
		//fa-circle
		//reqInfoChecked
		//reqIdPrev;reqIdNext
	});
	
	function setFinish()
	{
		var s_url= "../json-ujian/ujian_online_finish.php?reqPegawaiId=<?=$tempPegawaiId?>&reqUjianId=<?=$tempValUjianId?>&reqId=<?=$reqId?>";
		$.ajax({'url': s_url,'success': function(msg) {
			if(msg == ''){}
			else
			{
				document.location.href = '?pg=finish&reqId=<?=$reqId?>';
			}
		}});
	}
	
	function setSimpan()
	{
		
	}
	
	var tempNomor= tempUjianId= tempUjianBankSoalId= tempBankSoalId= tempBankSoalPilihanId= tempPegawaiId= "";
	
	function setInfoChecked(valStatus, valNomor)
	{
		var indexrow=0;
		//$('input[id^="reqBankSoalPilihanId"]').each(function(){
		$('input[id^="reqBankSoalPilihanId"]').each(function(){
			var tempId= rowid= tempVal= tempVal1= "";
			tempId= $(this).attr('id');
			tempVal= $(this).val();
			
			//alert(tempVal);
			
			tempId= tempId.replace("reqBankSoalPilihanId", "");
			rowid= tempId;
			tempId= tempId.split('-');
			var tempNomor= tempUjianId= tempUjianBankSoalId= tempBankSoalId= tempBankSoalPilihanId= tempPegawaiId= "";
			tempNomor= tempId[0];
			tempUjianId= tempId[1];
			tempUjianBankSoalId= tempId[2];
			tempBankSoalId= tempId[3];
			tempBankSoalPilihanId= tempVal;
			tempPegawaiId= "<?=$tempPegawaiId?>";
			var idType= $('input[id^="reqRadio'+rowid+'"]').attr("type");
			
			
			if(tempNomor == valNomor){}
			else
			return;
			
			//alert(tempNomor+" == "+valNomor);
			
			//alert(idType);
			if(tempVal == "" && idType == "radio"){}
			else
			{
				var tempPilihRowId= valNomor+"-"+tempUjianId+"-"+tempUjianBankSoalId+"-"+tempBankSoalId;
				if(valStatus == "1")
				{
					//var idType= $("#reqRadio"+tempPilihRowId+"-"+tempBankSoalPilihanId).attr("type");
					var idType= $('input[id^="reqRadio'+rowid+'"]').attr("type");
					//alert(idType);
					//alert("#reqRadio"+rowid);
					//14-1-97-151-755
					if(idType == "checkbox")
					{
						$('input[id^="reqRadio'+rowid+'"]').removeAttr('checked');
						//if(tempBankSoalId == 21)
						//{
							var tempRowCheckedId= rowid;
							//alert(tempRowCheckedId);
							var s_url= "../json-ujian/ujian_online_cheklist.php?reqId=<?=$reqId?>&reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId;
							var request = $.get(s_url);
							request.done(function(dataJson)
							{
								var data= JSON.parse(dataJson);
								for(i=0;i<data.arrID.length; i++)
								{
									valOptionId= data.arrID[i];
									//alert("#reqRadio"+rowid+"-"+valOptionId);
									$("#reqRadio"+tempRowCheckedId+"-"+valOptionId).prop("checked", true);
									$("#reqRadio"+tempRowCheckedId+"-"+valOptionId).attr("checked", true);
									
									if(valOptionId == ""){}
									else
									{
										$("#reqHrefNomor"+tempNomor).addClass("sudah");
										$("#reqInfoChecked"+tempNomor).removeClass("fa-circle");
										$("#reqInfoChecked"+tempNomor).addClass("fa-check-circle");
									}
								}
							});
							
							//$("#reqRadio13-1-15-21-117").prop("checked", true);
							//$("#reqRadio13-1-15-21-117").attr("checked", true);
							//$("#reqRadio13-1-15-21-114").prop("checked", true);
							//$("#reqRadio13-1-15-21-114").attr("checked", true);
						//}
						/*$('input[id^="reqRadio'+rowid+'"]').removeAttr('checked');
						
						$("#reqRadio"+rowid+"-"+tempVal).prop("checked", true);
						$("#reqRadio"+rowid+"-"+tempVal).attr("checked", true);*/
					}
					else
					{
						tempVal1= $("#reqTempBankSoalPilihanId"+rowid).val();
						//alert("a-"+$("#reqTempBankSoalPilihanId"+rowid).val()+"--"+$("#reqBankSoalPilihanId"+rowid).val());
						if(tempVal1 == tempVal)
						{
							$("#reqRadio"+rowid+"-"+tempVal).prop("checked", true);
							$("#reqRadio"+rowid+"-"+tempVal).attr("checked", true);
						}
					}
				}
				else if(valStatus == "2")
				{
					if(valNomor == tempNomor)
					{
						tempVal1= $("#reqTempBankSoalPilihanId"+rowid).val();
						//alert(tempVal1+" == "+tempVal);
						if(tempVal1 == tempVal)
						{
							if(tempVal1 == ""){}
							else
							{
								//alert('a');
								$("#reqRadio"+rowid+"-"+tempVal).prop("checked", true);
								$("#reqRadio"+rowid+"-"+tempVal).attr("checked", true);
								indexrow= parseInt(indexrow) + 1;
								
								var idType= $('input[id^="reqRadio'+rowid+'"]').attr("type");
								if(idType == "checkbox")
								{
									if($("#reqRadio"+tempPilihRowId+"-"+tempBankSoalPilihanId).prop("checked") == true)
									{
										//alert($("#reqRadio"+tempPilihRowId+"-"+tempBankSoalPilihanId).prop("checked"));return false;
										
										var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
										var s_url= "../json-ujian/ujian_online.php?ss=1&reqId=<?=$reqId?>&reqMode=multi&reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId;
										$.ajax({'url': s_url,'success': function(msg) {
											if(msg == '')
											{
												$.messager.alert('Info', "Data gagal disimpan", 'info');
												$.messager.progress('close');
											}
											else
											{
												$.messager.progress('close');
												//alert(rowid);
												$("#reqRadio"+tempPilihRowId+"-"+msg).prop("checked", false);
												$("#reqRadio"+tempPilihRowId+"-"+msg).attr("checked", false);
												$("#reqTempBankSoalPilihanId"+tempPilihRowId).val(msg);
												$("#reqBankSoalPilihanId"+tempPilihRowId).val(msg);
												//indexrow= parseInt(indexrow) + 1;
												
												var jumlahChecked= $('input[id^="reqRadio'+tempPilihRowId+'"]:checked').length;
												if(jumlahChecked == 0)
												{
													$("#reqHrefNomor"+tempNomor).removeClass("sudah");
													$("#reqInfoChecked"+tempNomor).addClass("fa-circle");
													$("#reqInfoChecked"+tempNomor).removeClass("fa-check-circle");
													
													$("#reqTempBankSoalPilihanId"+tempPilihRowId).val("");
													$("#reqBankSoalPilihanId"+tempPilihRowId).val("");
												}
												
												setSelesai();
												//setInfoChecked();
											}
										}});
										//alert(s_url);
										
									}
									
								}
							}
						}
						else
						{
							var idType= $('input[id^="reqRadio'+tempPilihRowId+'"]').attr("type");
							//var idType= $("#reqRadio"+tempPilihRowId+"-"+tempBankSoalPilihanId).attr("type");
							if(idType == "checkbox")
							{	
								//$('input[id^="reqRadio'+tempPilihRowId+'"]').removeAttr('checked');
								//alert("#reqRadio"+tempPilihRowId+"-"+tempBankSoalPilihanId);
								if($("#reqRadio"+tempPilihRowId+"-"+tempBankSoalPilihanId).prop("checked") == true)
								{
									var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
									var s_url= "../json-ujian/ujian_online.php?ss=2&reqId=<?=$reqId?>&reqMode=multi&reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId;
									$.ajax({'url': s_url,'success': function(msg) {
										if(msg == '')
										{
											$.messager.alert('Info', "Data gagal disimpan", 'info');
											$.messager.progress('close');
										}
										else
										{
											$.messager.progress('close');
											//alert(rowid);
											$("#reqRadio"+tempPilihRowId+"-"+msg).prop("checked", true);
											$("#reqRadio"+tempPilihRowId+"-"+msg).attr("checked", true);
											//$("#reqTempBankSoalPilihanId"+tempPilihRowId).val(msg);
											//$("#reqBankSoalPilihanId"+tempPilihRowId).val(msg);
											indexrow= parseInt(indexrow) + 1;
											//setInfoChecked();
											
											$("#reqHrefNomor"+tempNomor).addClass("sudah");
											$("#reqInfoChecked"+tempNomor).removeClass("fa-circle");
											$("#reqInfoChecked"+tempNomor).addClass("fa-check-circle");
											
											setSelesai();
										}
									}});
									//alert(idType);
									//alert(tempPilihRowId+"-"+tempBankSoalPilihanId+";"+tempPegawaiId);
									//return false;
								}
								else
								{
									//alert(tempBankSoalPilihanId);
									var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
									var s_url= "../json-ujian/ujian_online.php?ss=3&reqId=<?=$reqId?>&reqMode=multi&reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId;
									$.ajax({'url': s_url,'success': function(msg) {
										if(msg == '')
										{
											$.messager.alert('Info', "Data gagal disimpan", 'info');
											$.messager.progress('close');
										}
										else
										{
											$.messager.progress('close');
											//alert(rowid);
											$("#reqRadio"+tempPilihRowId+"-"+msg).prop("checked", false);
											$("#reqRadio"+tempPilihRowId+"-"+msg).attr("checked", false);
											//$("#reqTempBankSoalPilihanId"+tempPilihRowId).val(msg);
											//$("#reqBankSoalPilihanId"+tempPilihRowId).val(msg);
											
											var jumlahChecked= $('input[id^="reqRadio'+tempPilihRowId+'"]:checked').length;
											if(jumlahChecked == 0)
											{
												$("#reqHrefNomor"+tempNomor).removeClass("sudah");
												$("#reqInfoChecked"+tempNomor).addClass("fa-circle");
												$("#reqInfoChecked"+tempNomor).removeClass("fa-check-circle");
												
												$("#reqTempBankSoalPilihanId"+tempPilihRowId).val("");
												$("#reqBankSoalPilihanId"+tempPilihRowId).val("");
											}
											
											//indexrow= parseInt(indexrow) + 1;

											//setInfoChecked();
											
											setSelesai();
										}
									}});
								}
								
							}
							else
							{
								$("#reqHrefNomor"+tempNomor).removeClass("sudah");
								$("#reqInfoChecked"+tempNomor).addClass("fa-circle");
								$("#reqInfoChecked"+tempNomor).removeClass("fa-check-circle");
			
								$('input[id^="reqRadio'+tempPilihRowId+'"]').prop("checked", false);
								$('input[id^="reqRadio'+tempPilihRowId+'"]').attr("checked", false);
								//alert('--'+tempBankSoalPilihanId);
								//alert("../json-ujian/ujian_online.php?reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId);return false;
								var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
								var s_url= "../json-ujian/ujian_online.php?reqUjianId="+tempUjianId+"&reqId=<?=$reqId?>&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId;
								$.ajax({'url': s_url,'success': function(msg) {
									if(msg == '')
									{
										$.messager.alert('Info', "Data gagal disimpan", 'info');
										$.messager.progress('close');
										$('input[id^="reqRadio'+tempPilihRowId+'"]').prop("checked", false);
										$('input[id^="reqRadio'+tempPilihRowId+'"]').attr("checked", false);
									}
									else
									{
										//$.messager.alert('Info', "Data berhasil disimpan", 'info');
										$.messager.progress('close');
										//alert(msg+"-"+tempPilihRowId);
										tempBankSoalPilihanId= msg;
										//alert("b-"+$("#reqTempBankSoalPilihanId"+tempPilihRowId).val()+"--"+$("#reqBankSoalPilihanId"+tempPilihRowId).val());
										$("#reqBankSoalPilihanId"+tempPilihRowId+", #reqTempBankSoalPilihanId"+tempPilihRowId).val(msg);
										//$("#reqBankSoalPilihanId"+tempPilihRowId).val(msg);
										//alert("c-"+$("#reqBankSoalPilihanId"+tempPilihRowId).val()+"--"+$("#reqTempBankSoalPilihanId"+tempPilihRowId).val());
										$("#reqHrefNomor"+tempNomor).addClass("sudah");
										$("#reqInfoChecked"+tempNomor).removeClass("fa-circle");
										$("#reqInfoChecked"+tempNomor).addClass("fa-check-circle");
										$("#reqRadio"+tempPilihRowId+"-"+msg).prop("checked", true);
										$("#reqRadio"+tempPilihRowId+"-"+msg).attr("checked", true);
										
										indexrow= parseInt(indexrow) + 1;
										//setInfoChecked();
										setSelesai();
									}
									
								}});
							}
							
						}
					}
				}
				
			}
	   });
	   
	   //setSelesai();
	}
	
	function setSelesai()
	{
		//alert("../json-ujian/ujian_online_count.php?reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId); return false;
		var s_url= "../json-ujian/ujian_online_count.php?reqId=<?=$reqId?>&reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId=<?=$tempPegawaiId?>";
		$.ajax({'url': s_url,'success': function(msg) {
			if(msg == ''){}
			else
			{
				$("#reqIdNext, #reqIdPrev").show();
				$("#reqIdSelesai").hide();
				var jumlahsoal= parseInt("<?=$tempJumlahSoalPegawai?>");
				indexrow= msg;
				//alert(jumlahsoal+" == "+indexrow);
				if(jumlahsoal == indexrow)
				{
					//alert('s');
					//$("#reqIdNext, #reqIdPrev").hide();
					$("#reqIdSelesai").show();
				}
				//else
				//{
					$('a[id^="reqHrefNomor"]').removeClass("pilih");
					var currentPage= getUrlIndex();
					currentPage= parseInt(currentPage);
					$("#reqHrefNomor"+currentPage).addClass("pilih");
					//alert(currentPage);
					
					if(currentPage >= jumlahsoal)
					$("#reqIdNext").hide();
					
					$("#reqIdPrev").hide();
					if(parseInt(currentPage) > 1)
					{
						$("#reqIdPrev").show();
					}
				//}
			}
		}});
	}
	
	function getUrlIndex()
	{
		//jquery
		$(location).attr('href');
	
		//pure javascript
		var pathname = window.location.pathname;
		
		// to show it in an alert window
		var currentPage= String(window.location);
		currentPage= currentPage.split('#');
		currentPage= parseInt(currentPage[1]);
		
		if(isNaN(currentPage))
		{
			currentPage= 1;
		}
			
		return currentPage;
	}
	
	function setSelectedPertanyaan(mode)
	{
		$('a[id^="reqHrefNomor"]').removeClass("pilih");
		var currentPage= getUrlIndex();
		if(mode == "1")
		currentPage= parseInt(currentPage) + 1;
		else if(mode == "2")
		currentPage= parseInt(currentPage) - 1;
		else
		currentPage= parseInt(currentPage);
		
		$("#reqHrefNomor"+currentPage).addClass("pilih");
		
		$("#reqIdPrev").hide();
		if(parseInt(currentPage) > 1)
		{
			$("#reqIdPrev").show();
		}
		
		$("#reqIdNext").show();
		var jumlahsoal= parseInt("<?=$tempJumlahSoalPegawai?>");
		
		if(currentPage > jumlahsoal)
		currentPage= jumlahsoal;
		
		//alert(jumlahsoal);
		//alert(jumlahsoal+" == "+currentPage);
		if(jumlahsoal == currentPage)
		{
			$("#reqIdNext").hide();
		}
		
		if(mode == "" || isNaN(mode))
		{
			//jquery
			$(location).attr('href');
		
			//pure javascript
			var pathname = window.location.pathname;
			
			// to show it in an alert window
			var currentPage= String(window.location);
			currentPage= currentPage.split('#');
			currentPage= parseInt(currentPage[1]);
			
			if(isNaN(currentPage)){}
			else
			{
				document.location.href= "?pg=ujian_online&reqId=<?=$reqId?>#"+currentPage;
				//document.location.href= "?pg=ujian_online";
			}
		
			//
			//var currentPage= getUrlIndex();
			/*$('input[id^="reqBankSoalPilihanId"]').each(function(){
				var tempId= rowid= tempVal= tempVal1= "";
				tempId= $(this).attr('id');
				tempVal= $(this).val();
				if(tempVal == ""){}
				else
				{
					tempId= tempId.replace("reqBankSoalPilihanId", "");
					rowid= tempId;
					tempId= tempId.split('-');
					var tempNomor= "";
					tempNomor= tempId[0];
					//if(currentPage == tempNomor)
					//{
						//alert(currentPage+"--"+tempNomor);return false;
						$("#reqRadio"+rowid+"-"+tempVal).prop("checked", true);
						$("#reqRadio"+rowid+"-"+tempVal).attr("checked", true);
						return false;
					//}
				}
		   });*/
		}
		//else
		
		var tempcurrentpage= currentPage;
		if(mode == "1")
		tempcurrentpage= currentPage - 1;
		
		if(isNaN(tempcurrentpage) || tempcurrentpage <= 0)
		{
			tempcurrentpage= 1;
		}
		//alert(currentPage+"-"+tempcurrentpage);return false;
		
		//setInfoChecked("1", "");
		setInfoChecked("1", tempcurrentpage);
		//setInfoChecked();
	}
	
	function setNext()
	{
		var currentPage= getUrlIndex();
		currentPage= parseInt(currentPage) + 1;
		if(isNaN(currentPage))
		{
			currentPage= 2;
		}
		
		<?
		if($tempParent==1)
		{
		?>
		$("#reqHrefIdNext").attr('href',"#"+currentPage);
		//alert(currentPage);
		setSelectedPertanyaan("1");
		<?
		}
		else
		{
		?>
		tempSoalNomor= currentPage - 1;
		var tempNomorUrutan= 0;
		
		var win = $.messager.progress({title:'soal selanjutnya', msg:'Proses soal selanjutnya...'});
		var s_url= "../json-ujian/ujian_online_get_urut.php?reqUjianTahapId=<?=$reqId?>&reqUjianId=<?=$tempUjianId?>&reqPegawaiId=<?=$tempPegawaiId?>";
		$.ajax({'url': s_url,'success': function(msg) {
			if(msg == ''){}
			else
			{
				$('[href="javascript: void(0)"]').remove();
				tempNomorUrutan= msg;
				//alert(tempNomorUrutan+"--"+tempSoalNomor);
				if(parseInt(tempNomorUrutan) >= parseInt(tempSoalNomor))
				//if(parseInt(tempNomorUrutan) == parseInt(tempSoalNomor))
				{
					/*if(parseInt(tempNomorUrutan) > parseInt(tempSoalNomor))
					{
						alert('a'+currentPage);
						$("#reqHrefIdNext").attr('href',"#"+tempNomorUrutan);
					}
					else
					{
						alert('b'+tempNomorUrutan);
						$("#reqHrefIdNext").attr('href',"#"+currentPage);
					}*/
					
					//alert('a'+currentPage);
					//$('[href="javascript: void(0)"]').remove();
					//$("#reqHrefIdNext").attr('href',"#"+currentPage);
					//$("#reqHrefIdNext").prop('href',"#"+currentPage);
					$(location).attr('href', "#"+currentPage);
					setSelectedPertanyaan("1");
				}
				else
				{
					//alert('b'+msg+";"+tempSoalNomor);
					$("#reqHrefIdNext").attr('href',"javascript:void(0);");
				}
			}
			$.messager.progress('close');
		}});
		
		//setSelectedPertanyaan("1");
		<?
		}
		?>
	}
	
	function setPrev()
	{
		var currentPage= getUrlIndex();
		currentPage= parseInt(currentPage) - 1;
		if(isNaN(currentPage))
		{
			currentPage= 1;
		}
		$("#reqHrefIdPrev").attr('href',"#"+currentPage);
		setSelectedPertanyaan("2");
	}
</script>
<style>

  /* a few styles for the default page to make it presentable */
  .tabbable {
      margin-bottom: 18px;
  }
  .tab-content {
      padding: 15px; 
      border-bottom: 1px solid #ddd;
	  display:inline-block;
	  width:100%;
  }

  /* tab styles for small screen */
  @media (max-width: 767px) {
      
      .tabbable.responsive .nav-tabs {
          font-size: 16px;
      }
      .tabbable.responsive .nav-tabs ul {
          margin: 0;
      }
      .tabbable.responsive .nav-tabs li {
          /* box-sizing seems like the cleanest way to make sure width includes padding */
          -webkit-box-sizing: border-box;
             -moz-box-sizing: border-box; 
              -ms-box-sizing: border-box;
               -o-box-sizing: border-box;
                  box-sizing: border-box; 
          display: inline-block; 
          width: 100%; 
          height: 44px;
          line-height: 44px; 
          padding: 0 15px;
          border: 1px solid #ddd;
          overflow: hidden;
      }
      .tabbable.responsive .nav-tabs > li > a {
          border-style: none;
          display: inline-block;
          margin: 0;
          padding: 0;
      }
      /* include hover and active styling for links to override bootstrap defaults */
      .tabbable.responsive .nav-tabs > li > a:hover {
          border-style: none; 
          background-color: transparent;}
      .tabbable.responsive .nav-tabs > li > a:active,
      .tabbable.responsive .nav-tabs > .active > a,
      .tabbable.responsive .nav-tabs > .active > a:hover {
          border-style: none;
      }
  }

  /* sample styles for the tab controls on small screens  - start with left control and override for right */
  .tabbable.responsive .nav-tabs > li > a.tab-control,
  .tabbable.responsive .nav-tabs > li > span.tab-control-spacer {
      float: left;
      width: 36px;
      height: 36px;
      margin-top: 4px;
      font-size: 56px;
      font-weight: 100;
      line-height: 26px;
      color: #fff;
      text-align: center;
      background: #444;
      -webkit-border-radius: 18px;
         -moz-border-radius: 18px;
              border-radius: 18px;
      }
  .tabbable.responsive .nav-tabs > li > a.tab-control.right,
  .tabbable.responsive .nav-tabs > li > span.tab-control-spacer.right {
      float: right;
  }
  .tabbable.responsive .nav-tabs > li > a.tab-control:hover {
      color: #fff;
      background: #444;
  }
  .tabbable.responsive .nav-tabs > li > span.tab-control-spacer {
      line-height: 28px;
      color: transparent;
      background: transparent;
  }

</style>


<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
            <div class="area-sisa-waktu">
                <div class="judul"><i class="fa fa-clock-o"></i> Sisa Waktu :</div>
                <div class="waktu">
                    <div id="divCounter"></div>
                </div>
            </div>
        </div>
    	
    	<div class="col-md-12">
			<div class="area-judul-halaman">
            	Ujian <?=$tempTipeTahap?>
            	<span style="float:right" class="lengkapimodif-data" id="reqIdSelesai"><a href="#" onclick="setFinish()">Selesai &raquo;</a></span>
            </div>
        </div>
        
        <div class="col-md-9">
        
            <div class="area-soal">
            
                <div id="main-slider" class="liquid-slider">
                	<?
					for($index_loop=0; $index_loop < $tempJumlahSoalPegawai; $index_loop++)
					{
						$tempIdRow= $arrJumlahSoalPegawai[$index_loop]["ID_ROW"];
						$tempNomor= $arrJumlahSoalPegawai[$index_loop]["NOMOR"];
						$tempPertanyaan= $arrJumlahSoalPegawai[$index_loop]["PERTANYAAN"];
						$tempTipeSoal= $arrJumlahSoalPegawai[$index_loop]["TIPE_SOAL"];
						$tempPathSoal= $arrJumlahSoalPegawai[$index_loop]["PATH_SOAL"];
						$tempPathGambar= $arrJumlahSoalPegawai[$index_loop]["PATH_GAMBAR"];
						
						$tempUjianId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"];
						$tempUjianBankSoalId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"];
						$tempBankSoalId= $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"];
						$tempBankSoalPilihanId= $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"];
						
					?>
                    	<div id="<?=$tempNomor?>" class="area-soal-item">
                            <input type="hidden" id="reqTempBankSoalPilihanId<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" value="<?=$tempBankSoalPilihanId?>" />
                            <input type="hidden" name="reqBankSoalPilihanId[]" id="reqBankSoalPilihanId<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" value="<?=$tempBankSoalPilihanId?>" />
                        	<span class="nomor"><?=$tempNomor?></span>
                            <?php /*?><?=$tempIdRow." == ".$tempValueRowId?><?php */?>
                            <?
							if($tempTipeSoal==1)
							{
                            ?>
                        		<span class="pertanyaan"><?=$tempPertanyaan?></span>
                            <?
							}
							else if($tempTipeSoal==2)
							{
                            ?>
                            	<span class="gambar-soal-kiri">
                                <?
								if(file_exists($tempPathGambar.$tempPathSoal))
								{
                                ?>
                                <img src="<?=$tempPathGambar.$tempPathSoal?>">
                                <?
								}
                                ?>
                                </span>
                            <?
							}
							else if($tempTipeSoal==5)
							{
                            ?>
                            	<span class="gambar-soal-kiri">
                                <?
								if(file_exists($tempPathGambar.$tempPathSoal))
								{
                                ?>
                                <img src="<?=$tempPathGambar.$tempPathSoal?>">
                                <?
								}
                                ?>
                                </span>
                                <span class="teks"><?=$tempPertanyaan?></span>
                            <?
							}
                            ?>
                            
                            <div class="area-jawab-pilihan-ganda">
                            <?
                            $arrayJawaban= '';
                            $arrayJawaban= in_array_column($tempIdRow, "ID_ROW", $arrJumlahJawabanSoalPegawai);
							if($arrayJawaban == ''){}
							else
							{
								// echo count($arrayJawaban);
								for($index_detil=0; $index_detil < count($arrayJawaban); $index_detil++)
								{
									$index_row= $arrayJawaban[$index_detil];
									$tempJawaban= $arrJumlahJawabanSoalPegawai[$index_row]["JAWABAN"];
									$tempTipeSoal= $arrJumlahJawabanSoalPegawai[$index_row]["TIPE_SOAL"];
									$tempPathSoal= $arrJumlahJawabanSoalPegawai[$index_row]["PATH_SOAL"];
									$tempPathGambar= $arrJumlahJawabanSoalPegawai[$index_row]["PATH_GAMBAR"];
									
									$tempUjianId= $arrJumlahJawabanSoalPegawai[$index_row]["UJIAN_ID"];
									$tempUjianBankSoalId= $arrJumlahJawabanSoalPegawai[$index_row]["UJIAN_BANK_SOAL_ID"];
									$tempBankSoalId= $arrJumlahJawabanSoalPegawai[$index_row]["BANK_SOAL_ID"];
									$tempBankSoalPilihanDetilId= $arrJumlahJawabanSoalPegawai[$index_row]["BANK_SOAL_PILIHAN_ID"];
									
									$tempChecked= setInfoChecked($tempBankSoalPilihanId, $tempBankSoalPilihanDetilId);
							?>
                            	
                                <?
								if($tempTipeSoal==1 || $tempTipeSoal==5)
								{
									if($tempJawaban == "")
									continue;
								?>
                                    <input type="radio" class="easyui-validatebox" <?=$tempChecked?> name="reqRadio<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" id="reqRadio<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanDetilId?>" value="<?=$tempBankSoalPilihanDetilId?>" />
                                    <span class="teks"><?=$tempJawaban?></span>
                                    <br/>
                                <?
								}
								else if($tempTipeSoal==2)
								{
                                ?>
                                    <div class="gambar-kecil">
                                    	<?php /*?><? echo $tempNomor."-".$tempUjianId."-".$tempUjianBankSoalId."-".$tempBankSoalId."-".$tempBankSoalPilihanDetilId;?><?php */?>
                                        <input type="radio" class="easyui-validatebox" <?=$tempChecked?> name="reqRadio<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" id="reqRadio<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanDetilId?>" value="<?=$tempBankSoalPilihanDetilId?>" />
                                        <span>
										<?
										//echo $tempPathGambar.$tempPathSoal;
										if($tempPathSoal == ""){}
										else
										{
                                        if(file_exists($tempPathGambar.$tempPathSoal))
										{
										?>
                                        <img src="<?=$tempPathGambar.$tempPathSoal?>" width="55" height="53" />
										<?
										}
										}
                                        ?>
										</span>
                                    </div>
                                <?
								}
								else if($tempTipeSoal==3)
								{
                                ?>
                                	<div class="gambar-kecil">
                                    	<?php /*?><?="reqRadio".$tempNomor."-".$tempUjianId."-".$tempUjianBankSoalId."-".$tempBankSoalId."-".$tempBankSoalPilihanDetilId;?><?php */?>
                                        <input type="checkbox" class="easyui-validatebox" <?php /*?><?=$tempChecked?><?php */?> name="reqRadio<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" id="reqRadio<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanDetilId?>" value="<?=$tempBankSoalPilihanDetilId?>" />
                                        <?php /*?><?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanDetilId?><?php */?>
                                        <?php /*?>14-1-97-151-755<?php */?>
                                        <span>
										<?
										if($tempPathSoal == ""){}
										else
										{
                                        if(file_exists($tempPathGambar.$tempPathSoal))
										{
										?>
                                        <img src="<?=$tempPathGambar.$tempPathSoal?>">
										<?
										}
										}
                                        ?>
										</span>
                                    </div>
								<?
								}
								elseif($tempTipeSoal==7)
								{
									if($tempJawaban == "")
									continue;
								?>
                                    <input type="radio" class="easyui-validatebox" <?=$tempChecked?> name="reqRadio<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" id="reqRadio<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanDetilId?>" value="<?=$tempBankSoalPilihanDetilId?>" />
                                    <span class="teks"><?=$tempJawaban?></span>
                                    <br/>
                                <?
								}
                                ?>
                                
                            <?
								}
							}
                            ?>
                            </div>
                        </div>
					<?
					}
					$tempNomor= $tempNomor+1;
					?>
                    <div id="<?=$tempNomor?>" class="area-soal-item"></div>
				</div>
                
                <?php /*?><div class="soal-bergambar">
                    <div class="gambar"><img src="../WEB/images/bg-login.jpg"></div>
                    <div class="keterangan">
                        <div class="soal">1). Roda yang manakah yang akan membuat putaran yang paling banyak dalam tiap menit?</div>	
                        <div class="jawaban">
                              <p>
                                <label>
                                  <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_0">
                                  Roda A</label>
                                <br>
                                <label>
                                  <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_1">
                                  Roda B</label>
                                <br>
                                <label>
                                  <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_2">
                                  Roda C</label>
                                <br>
                                <label>
                                  <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_3">
                                  Roda D</label>
                                <br>
                            </p>
                        </div>
                    </div>
                </div><?php */?>

                
            </div>
            
        </div>
        <div class="col-md-3">
        	<div class="area-sudah">
                <?
				//if($tempParent == 1)
				//{
					for($index_loop=0; $index_loop < $tempJumlahSoalPegawai; $index_loop++)
					{
						$tempNomor= $arrJumlahSoalPegawai[$index_loop]["NOMOR"];
						$tempUjianId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"];
						$tempUjianBankSoalId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"];
						$tempBankSoalId= $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"];
						$tempBankSoalPilihanId= $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"];
						$tempJumlahData= $arrJumlahSoalPegawai[$index_loop]["JUMLAH_DATA"];
                ?>
                	<div class="item">
                    	<?
						if($tempParent==1)
						{
                        ?>
							<?
                            if($tempJumlahData == "0")
                            {
                            ?>
                            <a href="#<?=$tempNomor?>" id="reqHrefNomor<?=$tempNomor?>"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-circle"></i>  <?=$tempNomor?></a>
                            <?
                            }
                            else
                            {
                            ?>                        
                            <a href="#<?=$tempNomor?>" id="reqHrefNomor<?=$tempNomor?>" class="sudah"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-check-circle"></i>  <?=$tempNomor?></a>
                        <?
							}
						}
						else
						{
                        ?>
                        	<?
                            if($tempJumlahData == "0")
                            {
                            ?>
                            <a href="javascript:void(0);" id="reqHrefNomor<?=$tempNomor?>"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-circle"></i>  <?=$tempNomor?></a>
                            <?
                            }
                            else
                            {
                            ?>                        
                            <a href="javascript:void(0);" id="reqHrefNomor<?=$tempNomor?>" class="sudah"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-check-circle"></i>  <?=$tempNomor?></a>
                            <?
							}
                            ?>
                        <?
						}
                        ?>
                    </div>
                <?
					}
				//}
                ?>
                <?php /*?><div class="item"><a href="#1" class="sudah"><i class="fa fa-check-circle"></i> 1</a></div>
            	<div class="item"><a href="#2"><i class="fa fa-circle"></i> 2</a></div><?php */?>
                
            </div>
            
        </div>
	</div>
        
        <!--
        <div class="col-md-12">
        	<br>
        	
        </div>
        -->
        
    	<div class="area-prev-next-ujian">
        	<?php /*?><div class="selesai" style="display:none" id="reqIdSelesai"><a href="?pg=finish">Selesai &raquo;</a></div><?php */?>
            <div class="prev-ujian" style="display:none" id="reqIdPrev"><a href="#" id="reqHrefIdPrev" onclick="setPrev()"><i class="fa fa-chevron-left"></i> Soal Sebelumnya</a></div>
            <div class="next-ujian" id="reqIdNext"><a href="javascript:void(0);" id="reqHrefIdNext" onclick="setNext()">Soal Selanjutnya <i class="fa fa-chevron-right"></i></a></div>
        </div>
        
    
    </div>
</div>
