<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base/Ujian.php");
include_once("../WEB/classes/base/UjianTahap.php");

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

$reqId = httpFilterGet("reqId");

$tempPegawaiId= $userLogin->pegawaiId;
$tempSystemTanggalNow= date("d-m-Y");

$sOrder= "ORDER BY RANDOM()";
$sOrder= "ORDER BY UP.UJIAN_PEGAWAI_ID";
$index_loop=0;
$arrJumlahSoalPegawai="";
$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.UJIAN_TAHAP_ID = ".$reqId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$set= new UjianPegawaiDaftar();
$set->selectByParamsSoal(array(), -1,-1, $statement, $sOrder);
//echo $set->query;exit;
while($set->nextRow())
{
	$nomor= $index_loop+1;
	$arrJumlahSoalPegawai[$index_loop]["ID_ROW"]= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID");
	$arrJumlahSoalPegawai[$index_loop]["NOMOR"]= $nomor;
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
$tempJumlahSoalPegawai= $index_loop;
unset($set);
//print_r($arrJumlahSoalPegawai);exit;

$sOrder= "ORDER BY RANDOM()";
//$sOrder= "ORDER BY B.UJIAN_BANK_SOAL_ID";
$index_loop=0;
$arrJumlahJawabanSoalPegawai="";
$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.UJIAN_TAHAP_ID = ".$reqId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$set= new UjianPegawaiDaftar();
$set->selectByParamsJawabanSoal(array(), -1,-1, $statement, $sOrder);
//echo $set->query;exit;
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

$index_data=0;
$arrJumlahTahap="";
$statement= " AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
$set_tahap= new UjianTahap();
$set_tahap->selectByParamsPegawaiTahap(array(), -1,-1, $statement, " ORDER BY C.TIPE_UJIAN_ID ASC");
while($set_tahap->nextRow())
{
	$arrJumlahTahap[$index_data]["UJIAN_TAHAP_ID"]= $set_tahap->getField("UJIAN_TAHAP_ID");
	$arrJumlahTahap[$index_data]["TIPE"]= $set_tahap->getField("TIPE");
	$index_data++;
}
$tempJumlahTahap= $index_data;
unset($set_tahap);

$ujian_tahap = new UjianTahap();
$ujian_tahap->selectByParamsMonitoring(array("A.UJIAN_TAHAP_ID"=>$reqId));
$ujian_tahap->firstRow();
$tempTipeTahap = $ujian_tahap->getField("TIPE");

$statement= " AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
$set= new Ujian();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
$set->firstRow();
$final_time_saving= $set->getField("BATAS_WAKTU_MENIT");
//$final_time_saving= 1;
$hours= floor($final_time_saving / 60);
$minutes= $final_time_saving % 60;
unset($set);
?>
<!-- Optionally use Animate.css -->
<link rel="stylesheet" href="../WEB/lib-ujian/bootstrap/animate.min.css">
<link rel="stylesheet" href="../WEB/lib-ujian/liquidslider-master/css/liquid-slider.css">

<script src="../WEB/lib-ujian/bootstrap/jquery.min.js"></script>
<script src="../WEB/lib-ujian/bootstrap/jquery.easing.min.js"></script>
<script src="../WEB/lib-ujian/bootstrap/jquery.touchSwipe.min.js"></script>
<script src="../WEB/lib-ujian/liquidslider-master/src/js/jquery.liquid-slider.js"></script>
<script language="javascript">
	// untuk clear time
	localStorage.clear();
	
	var hoursleft = <?=$hours?>;
	var minutesleft = <?=$minutes?>;
	var secondsleft = 0; 
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
			clearTimeout(interval);
			localStorage.setItem("end", null);
			document.location.href = '?pg=finish';
			//document.location.href = '?pg=ujian_online';
			//document.getElementById('divCounter').innerHTML = finishedtext;
		} else {
			var value = hour + ":" + min + ":" + sec;
			localStorage.setItem("end", end);
			document.getElementById('divCounter').innerHTML = value;
			if(min.toString() == 'NaN')
			{
				//alert('b');return false;
				localStorage.setItem("waktuberakhir", "00:00");
				clearTimeout(interval);	
				localStorage.setItem("end", null);
				document.location.href = '?pg=finish';
				//document.location.href = '?pg=ujian_online';
			}
		}
	}
	var interval = setInterval(counter, 1);
	
	$(function(){
		$('#main-slider').liquidSlider();
		setSelectedPertanyaan();
		//setSelectedPertanyaan();
		//var url= $("#reqHrefIdNext").attr("href");
		//$("#reqHrefIdNext").attr('href',"#2");
		//alert(url);
		
		//$("#reqIdNext").attr("href", "#3");
		
		$('a[id^="reqHrefNomor"]').click(function(e) {
			var rowid= $(this).attr('id').replace("reqHrefNomor", "");
			$('a[id^="reqHrefNomor"]').removeClass("pilih");
			$("#reqHrefNomor"+rowid).addClass("pilih");
			
			$("#reqIdPrev").hide();
			if(parseInt(rowid) > 1)
			{
				$("#reqIdPrev").show();
			}
			
			setInfoChecked();
		});	
		
		$('input[id^="reqRadio"]').change(function(e) {
			var tempId= $(this).attr('id');
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
			
			$("#reqHrefNomor"+tempNomor).removeClass("sudah");
			$("#reqInfoChecked"+tempNomor).addClass("fa-circle");
			$("#reqInfoChecked"+tempNomor).removeClass("fa-check-circle");
			if(tempBankSoalPilihanId == "" || isNaN(tempBankSoalPilihanId)){}
			else
			{
				$("#reqHrefNomor"+tempNomor).addClass("sudah");
				$("#reqInfoChecked"+tempNomor).removeClass("fa-circle");
				$("#reqInfoChecked"+tempNomor).addClass("fa-check-circle");
			}
			
			setInfoChecked();
		});
		
		//fa-check-circle
		//fa-circle
		//reqInfoChecked
		//reqIdPrev;reqIdNext
	});
	
	function setSimpan()
	{
		
	}
	
	var tempNomor= tempUjianId= tempUjianBankSoalId= tempBankSoalId= tempBankSoalPilihanId= tempPegawaiId= "";
	
	function setInfoChecked()
	{
		var indexrow=0;
		$('input[id^="reqBankSoalPilihanId"]').each(function(){
			var tempId= rowid= tempVal= tempVal1= "";
			tempId= $(this).attr('id');
			tempVal= $(this).val();
			if(tempVal == ""){}
			else
			{
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
				
				tempVal1= $("#reqTempBankSoalPilihanId"+rowid).val();
				if(tempVal1 == tempVal)
				{
					if(tempVal1 == ""){}
					else
					{
						$("#reqRadio"+rowid+"-"+tempVal).prop("checked", true);
						$("#reqRadio"+rowid+"-"+tempVal).attr("checked", true);
						indexrow= parseInt(indexrow) + 1;
					}
				}
				else
				{
					//alert("../json-main/ujian_online.php?reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId);return false;
					var s_url= "../json-main/ujian_online.php?reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId;
					$.ajax({'url': s_url,'success': function(msg) {
						if(msg == ''){}
						else
						{
							$("#reqTempBankSoalPilihanId"+rowid).val(msg);
							indexrow= parseInt(indexrow) + 1;
							//setInfoChecked();
						}
					}});
				}
			}
	   });
	   
	   setSelesai();
	}
	
	function setSelesai()
	{
		//alert("../json-main/ujian_online_count.php?reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId); return false;
		var s_url= "../json-main/ujian_online_count.php?reqId=<?=$reqId?>&reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId;
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
		setInfoChecked();
	}
	
	function setNext()
	{
		var currentPage= getUrlIndex();
		currentPage= parseInt(currentPage) + 1;
		if(isNaN(currentPage))
		{
			currentPage= 2;
		}
		$("#reqHrefIdNext").attr('href',"#"+currentPage);
		setSelectedPertanyaan("1");
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
        	<!-- Adding "responsive" class triggers the magic -->
            <div class="tabbable responsive">
                <ul class="nav nav-tabs">
                	<?
					for($index_loop=0; $index_loop < $tempJumlahTahap; $index_loop++)
					{
						$tempUjianTahap= $arrJumlahTahap[$index_loop]["UJIAN_TAHAP_ID"];
						$tempTipe= $arrJumlahTahap[$index_loop]["TIPE"];
						
						$setClassAktif= "";
						if($tempUjianTahap==$reqId)
						{
							$setClassAktif= "active";
						}
					?>
						<li class=" <?=$setClassAktif?>"><a href="?pg=ujian_online&reqId=<?=$tempUjianTahap?>"><?=$tempTipe?></a></li>
					<?
					}
					?>
                </ul>
            </div>
            
			<div class="area-judul-halaman">
            	Ujian Multiple Choice <?=$tempTipeTahap?>
            	<span style="float:right" class="lengkapimodif-data" id="reqIdSelesai"><a href="?pg=finish">Selesai &raquo;</a></span>
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
                        	<?php /*?><input type="text" name="reqUjianId[]" id="reqUjianId" value="<?=$tempUjianId?>" />
                            <input type="text" name="reqUjianBankSoalId[]" id="reqUjianBankSoalId" value="<?=$tempUjianBankSoalId?>" />
                            <input type="text" name="reqBankSoalId[]" id="reqBankSoalId" value="<?=$tempBankSoalId?>" /><?php */?>
                            <input type="hidden" id="reqTempBankSoalPilihanId<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" value="<?=$tempBankSoalPilihanId?>" />
                            
                            <input type="hidden" name="reqBankSoalPilihanId[]" id="reqBankSoalPilihanId<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" value="<?=$tempBankSoalPilihanId?>" />
                        	<span class="nomor"><?=$tempNomor?></span>
                            <?
							if($tempTipeSoal==1)
							{
                            ?>
                        		<span class="teks"><?=$tempPertanyaan?></span>
                            <?
							}
							else if($tempTipeSoal==2)
							{
                            ?>
                            	<span class="gambar-soal-kiri"><img src="<?=$tempPathGambar.$tempPathSoal?>"></span>
                            <?
							}
                            ?>
                            <?php /*?><div class="area-jawab-image-pilihan-ganda">
                            	<p>
                            	    <label>
                            	      <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_0">
                            	      <span><img src="../WEB/images/ujian/pilih-a.jpg"> </span><br>
                                      <span class="teks">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</span>
                                      </label>
                            	    <br>
                            	    <label>
                            	      <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_1">
                            	      <span><img src="../WEB/images/ujian/pilih-b.jpg"></span><br>
                                      <span class="teks">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat</span>
                                      </label>
                            	    <br>
                            	    <label>
                            	      <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_2">
                            	      <span><img src="../WEB/images/ujian/pilih-c.jpg"></span><br>
                                      <span class="teks">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</span>
                                      </label>
                            	    <br>
                                    <label>
                            	      <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_3">
                            	      <span><img src="../WEB/images/ujian/pilih-d.jpg"></span><br>
                                      <span class="teks">Excepteur sint occaecat cupidatat non proident</span>
                                      </label>
                            	    <br>
                                    <label>
                            	      <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_4">
                            	      <span><img src="../WEB/images/ujian/pilih-e.jpg"></span><br>
                                      <span class="teks">Sed ut perspiciatis unde omnis iste natus error sit voluptatem</span>
                                      </label>
                            	    <br>
                          	    </p>
                            </div><?php */?>
                            
                            <div class="area-jawab-pilihan-ganda">
                            <?
                            $arrayJawaban= '';
                            $arrayJawaban= in_array_column($tempIdRow, "ID_ROW", $arrJumlahJawabanSoalPegawai);
							if($arrayJawaban == ''){}
							else
							{
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
                            	
                            	<input type="radio" class="easyui-validatebox" <?=$tempChecked?> name="reqRadio<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" id="reqRadio<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanDetilId?>" value="<?=$tempBankSoalPilihanDetilId?>" />
                                	   <?
										if($tempTipeSoal==1)
										{
										?>
                                      		<span class="teks"><?=$tempJawaban?></span>
										<?
										}
										else if($tempTipeSoal==2)
										{
										?>
											<span><img src="<?=$tempPathGambar.$tempPathSoal?>"></span>
										<?
										}
										?>
                            	      <?php /*?><span><img src="../WEB/images/ujian/pilih-e.jpg"></span><br><?php */?>
                                <?php /*?>.";".$tempBankSoalPilihanId."=".$tempBankSoalPilihanDetilId<?php */?>
                                <br/>
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
                
            </div>
            
        </div>
        <div class="col-md-3">
        	<div class="area-sudah">
            	
                <?
				for($index_loop=0; $index_loop < $tempJumlahSoalPegawai; $index_loop++)
				{
					$tempNomor= $arrJumlahSoalPegawai[$index_loop]["NOMOR"];
					$tempUjianId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"];
					$tempUjianBankSoalId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"];
					$tempBankSoalId= $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"];
					$tempBankSoalPilihanId= $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_PILIHAN_ID"];
                ?>
                	<div class="item">
                    	<?
						if($tempBankSoalPilihanId == "")
						{
                        ?>
                    	<a href="#<?=$tempNomor?>" id="reqHrefNomor<?=$tempNomor?>"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-circle"></i>  <?=$tempNomor?></a>
                        <?
						}
						else
						{
                        ?>
                        
						<?php /*?><script>
						$("#reqRadio<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanId?>").prop("checked", true);
						$("#reqRadio<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanId?>").attr("checked", true);
						</script><?php */?>
                        
                        <a href="#<?=$tempNomor?>" id="reqHrefNomor<?=$tempNomor?>" class="sudah"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-check-circle"></i>  <?=$tempNomor?></a>
                        <?
						}
                        ?>
                    </div>
                <?
				}
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
            <div class="next-ujian" id="reqIdNext"><a href="#" id="reqHrefIdNext" onclick="setNext()">Soal Selanjutnya <i class="fa fa-chevron-right"></i></a></div>
        </div>
        
    
    </div>
</div>
