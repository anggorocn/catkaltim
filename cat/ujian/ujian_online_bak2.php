<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base/Ujian.php");

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

$tempPegawaiId= $userLogin->pegawaiId;
$tempSystemTanggalNow= date("d-m-Y");

$sOrder= "ORDER BY RANDOM()";
$sOrder= "ORDER BY UP.UJIAN_PEGAWAI_ID";
$index_loop=0;
$arrJumlahSoalPegawai="";
$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
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
	$index_loop++;
}
$tempJumlahSoalPegawai= $index_loop;
unset($set);
//print_r($arrJumlahSoalPegawai);exit;

$sOrder= "ORDER BY RANDOM()";
//$sOrder= "ORDER BY B.UJIAN_BANK_SOAL_ID";
$index_loop=0;
$arrJumlahJawabanSoalPegawai="";
$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
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
	$index_loop++;
}
$tempJumlahJawabanSoalPegawai= $index_loop;
unset($set);

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
		var s_url= "../json-main/ujian_online_count.php?reqUjianId="+tempUjianId+"&reqUjianBankSoalId="+tempUjianBankSoalId+"&reqBankSoalId="+tempBankSoalId+"&reqBankSoalPilihanId="+tempBankSoalPilihanId+"&reqPegawaiId="+tempPegawaiId;
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
				document.location.href= "?pg=ujian_online#"+currentPage;
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
            	Ujian Multiple Choice
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
                            <span class="gambar-soal"><img src="../WEB/images/ujian/gambar-pohon-psikotes.jpeg"></span>
                            
                            <div style="border:1px solid red; float:left; clear:both; width:100%;">
                            <input type="hidden" name="reqBankSoalPilihanId[]" id="reqBankSoalPilihanId<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" value="<?=$tempBankSoalPilihanId?>" />
                        	<span class="nomor"><?=$tempNomor?></span>
                        	<?=$tempPertanyaan?>
                            <?php /*?><div class="list-group">
                              <a href="#" class="list-group-item active">First item</a>
                              <a href="#" class="list-group-item">Second item</a>
                              <a href="#" class="list-group-item">Third item</a>
                            </div>
                            
                            <ol class="list-unstyled">
                              <li type="a">
                                Cras justo odio
                              </li>
                              <li type="a">
                                Dapibus ac facilisis in
                              </li>
                              <li type="a">
                                Morbi leo risus
                              </li>
                            </ol><?php */?>
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
									
									$tempUjianId= $arrJumlahJawabanSoalPegawai[$index_row]["UJIAN_ID"];
									$tempUjianBankSoalId= $arrJumlahJawabanSoalPegawai[$index_row]["UJIAN_BANK_SOAL_ID"];
									$tempBankSoalId= $arrJumlahJawabanSoalPegawai[$index_row]["BANK_SOAL_ID"];
									$tempBankSoalPilihanDetilId= $arrJumlahJawabanSoalPegawai[$index_row]["BANK_SOAL_PILIHAN_ID"];
									
									$tempChecked= setInfoChecked($tempBankSoalPilihanId, $tempBankSoalPilihanDetilId);
							?>
                            	<input type="radio" class="easyui-validatebox" <?=$tempChecked?> name="reqRadio<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>" id="reqRadio<?=$tempNomor?>-<?=$tempUjianId?>-<?=$tempUjianBankSoalId?>-<?=$tempBankSoalId?>-<?=$tempBankSoalPilihanDetilId?>" value="<?=$tempBankSoalPilihanDetilId?>" />
                                <?=$tempJawaban?>
                                <?php /*?>.";".$tempBankSoalPilihanId."=".$tempBankSoalPilihanDetilId<?php */?>
                                <br/>
                            <?
								}
							}
                            ?>
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
