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

$statement= " AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
$set= new Ujian();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
//echo $set->query;exit;
$set->firstRow();
$final_time_saving= $set->getField("BATAS_WAKTU_MENIT");
//$final_time_saving= 1;
$hours= floor($final_time_saving / 60);
$minutes= $final_time_saving % 60;
unset($set);
?>

<script language="javascript">
	// untuk clear time
	//localStorage.clear();
	
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
			$("#reqStatusWaktu").hide();
			//document.getElementById('divCounter').innerHTML = finishedtext;
		} else {
			var value = hour + ":" + min + ":" + sec;
			localStorage.setItem("end", end);
			document.getElementById('divCounter').innerHTML = value;
			if(min.toString() == 'NaN')
			{
				$("#reqStatusWaktu").hide();
				//alert('b');return false;
				localStorage.setItem("waktuberakhir", "00:00");
				clearTimeout(interval);	
				localStorage.setItem("end", null);
			}
		}
	}
	var interval = setInterval(counter, 1);
</script>

<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
        	
        	<div class="area-sisa-waktu" id="reqStatusWaktu" style="clear:both; ">
            	<div class="judul"><i class="fa fa-clock-o"></i> 
                    Sisa Waktu :
                </div>
                <div class="waktu">
                	<div id="divCounter"></div>
                </div>
            </div>
            
            <div class="area-soal" style="border:2px solid red;">
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
						<div class="itemlookup">
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
							<a href="#<?=$tempNomor?>" id="reqHrefNomor<?=$tempNomor?>" class="sudah"><i id="reqInfoChecked<?=$tempNomor?>" class="fa fa-check-circle"></i>  <?=$tempNomor?></a>
							<?
							}
							?>
						</div>
					<?
					}
					?>
                
                    <?php /*?><?
                    for($i=0; $i < 50; $i++)
                    {
                        $nomor= $i+1;
                    ?>
                    <div class="itemlookup"><a href="#<?=$nomor?>" id="reqHrefNomor1" class="sudah"><i id="reqInfoChecked<?=$nomor?>" class="fa fa-check-circle"></i>  <?=$nomor?></a></div>
                    <?
                    }
                    ?><?php */?>
                </div>
            </div>
            
            <div class="area-finish">
		        Anda telah selesai<br>mengikuti ujian online
            </div>
            
           <?php /*?> <div class="area-pengumuman">
            	<div class="judul">Jadwal Pengumuman</div>
                <div class="data">
                	<ul>
                    	<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                        <li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
                        <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</li>
                        <li>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                    </ul>
                </div>
            </div><?php */?>
            
        </div>
        
    	<div class="area-prev-next">
            <div class="kembali-home">
            	<span class="lengkapimodif-data" id="reqIdSelesai" style="float:right; margin-left:10px;"><a href="?pg=ujian_online" style="background:#e9682e;">Kembali ke ujian &raquo;</a></span>
            	<a href="?pg=dashboard"><i class="fa fa-home"></i> Kembali ke halaman utama <!--&raquo;--></a>
            </div>
        </div>
    
    </div>
</div>
