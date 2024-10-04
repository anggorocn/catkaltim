<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/Ujian.php");

if ($mode!='simulasi'){

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
}
$tempSystemTanggalNow= date("d-m-Y");

/*$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$set= new UjianPegawaiDaftar();
$set->selectByParamsJawabanSoal(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$tempStatusSetuju= $set->getField("STATUS_SETUJU");*/
//echo $tempStatusSetuju;exit;
/*if($tempUjianPegawaiDaftarId == "")
{
	echo '<script language="javascript">';
	echo 'top.location.href = "index.php";';
	echo '</script>';
	exit;
}*/

/*$statement= " AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
$statement.= " AND A.UJIAN_ID IN( SELECT A.UJIAN_ID FROM cat.UJIAN A INNER JOIN cat.UJIAN_TAHAP B ON A.UJIAN_ID = B.UJIAN_ID WHERE TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI GROUP BY A.UJIAN_ID)";
$set= new Ujian();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
$set->firstRow();
$tempUjianId= $set->getField("UJIAN_ID");
$final_time_saving= $set->getField("BATAS_WAKTU_MENIT");
//$final_time_saving= 1;
$hours= floor($final_time_saving / 60);
$minutes= $final_time_saving % 60;
unset($set);

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId;
$set= new UjianPegawaiDaftar();
$set->selectByParamsUjianPegawaiHasil(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempJumlahSoal= $set->getField("JUMLAH_SOAL");

$i_data= 0;*/
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
	<?if ($mode!='simulasi'){?>
	$(function(){
		<?
		if($tempStatusSetuju == "1")
		{
		?>
		$("#reqInfo<?=$i_data?>").prop("checked", true);
		$("#reqInfo<?=$i_data?>").attr("checked", true);
		$("#reqStatusSetuju").val("<?=$tempStatusSetuju?>");
		setSimpan();
		<?
		}
		else
		{
		?>
		setSimpan();
		<?
		}
		?>
		$('input[id^="reqInfo"]').click(function() {
			setSimpan();
		});
	
	});
	<?}?>
	
	function nextModul()
	{
		var tempUjianPegawaiDaftarId= tempPegawaiId= reqStatusSetuju= "";
		
		tempUjianPegawaiDaftarId= "<?=$tempUjianPegawaiDaftarId?>";
		tempPegawaiId= "<?=$tempPegawaiId?>";
		reqStatusSetuju= $("#reqStatusSetuju").val();
		
		<?php /*?>if(reqStatusSetuju == "1")
		{
			var s_url= "../json-main/form_persetujuan.php?reqUjianPegawaiDaftarId="+tempUjianPegawaiDaftarId+"&reqPegawaiId="+tempPegawaiId;
			$.ajax({'url': s_url,'success': function(msg) {
				if(msg == ''){}
				else
				{
					//return false;
					document.location.href= "?pg=ujian_mukadimah";
				}
			}});
		}
		else
		{
			$.messager.alert('Info', "Isi checkbox terlebih dahulu", 'info');
		}<?php */?>
		document.location.href= "?pg=ujian_mukadimah&mode=<?=$mode?>";
	}
	
	function setSimpan()
	{
		var reqJumlahInfo= "";
		reqJumlahInfo= 0;
		$('input[id^="reqInfo"]').each(function(){
			var id= $(this).attr('id');
			id= id.replace("reqInfo", "")
			
			if($(this).prop('checked'))
			{
				reqJumlahInfo= parseInt(reqJumlahInfo) + 1;
			}
	   });
		
		$("#reqJumlahInfo").val(reqJumlahInfo);
		var jumlahData= 1;
		
		if(reqJumlahInfo == jumlahData)
		{
			$("#reqSimpanInfo").show();
			$("#reqSimpan").show();
		}
		else
		{
			$("#reqSimpanInfo").hide();
			$("#reqSimpan").hide();
		}
		$("#reqStatusSetuju").val(reqJumlahInfo);
	}
</script>

<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">Instruksi Umum</div>
        </div>
        
        <div class="col-md-12">
       	  <div class="area-persetujuan">
          	<input type="hidden" name="reqJumlahInfo" id="reqJumlahInfo" value="0" />
           <!-- -->
              <table>
              	<tr>
              		<td style="padding-bottom: 10px;" width="30px"  style="padding-bottom: 10px;">1.</td>
              		<td style="padding-bottom: 10px;">Anda akan mengikuti tes Penilaian Potensi dan Kompetensi berbasis daring (online). Waktu Tes tidak dapat dihentikan.</li>
	                <?
					if(generateZero($hours, 2) == "00"){}
					else
					{
	                echo generateZero($hours, 2)." Jam";
					}
	                ?>
	                <?
					if(generateZero($minutes, 2) == "00"){}
					else
					{
	                echo generateZero($minutes, 2)." Menit";
					}
	                ?></td>
              	</tr>
              	<tr>
              		<td style="padding-bottom: 10px;">2.</td>
              		<td style="padding-bottom: 10px;">Peserta harus menjaga kerahasiaan selama proses tes Penilaian Potensi dan Kompetensi.</td>
              	</tr>
              	<tr>
              		<td style="padding-bottom: 10px; vertical-align:top;">3.</td>
              		<td style="padding-bottom: 10px;">Pada tes ini, Anda akan menghadapi serangkaian persoalan. Persoalan-persoalan tersebut dapat berupa kalimat, gambar maupun hitungan angka. Agar dapat menjawab persoalan dengan baik, perhatikan dengan sungguh-sungguh instruksi yang diberikan.</td>
              	</tr>
              	<tr>
              		<td style="padding-bottom: 10px;">4.</td>
              		<td style="padding-bottom: 10px;">Setiap tes memiliki waktu, instruksi dan cara pengerjaan yang berbeda-beda. Mohon perhatikan instruksi yang diberikan.</td>
              	</tr>
              	<tr>
              		<td style="padding-bottom: 10px;">5.</td>
              		<td style="padding-bottom: 10px;">TES akan dimulai dan diakhiri bersama-sama.</td>
              	</tr>
              	<tr>
              		<td style="padding-bottom: 10px;">6.</td>
              		<td style="padding-bottom: 10px;">Jika waktu tes masih tersisa, maka :</td>
              	</tr>
              	<tr>
              		<td style="padding-bottom: 10px;"></td>
              		<td style="padding-bottom: 10px;">- Anda dapat merubah jawaban jika diperlukan.</td>
              	</tr>
              	<tr>
              		<td style="padding-bottom: 10px;"></td>
              		<td style="padding-bottom: 10px;">- Periksalah kembali jawaban yang sudah anda buat.</td>
              	</tr>
              	<tr>
              		<td style="padding-bottom: 10px;">7.</td>
              		<td style="padding-bottom: 10px;">Jika waktu tes telah selesai, Anda tidak dapat melanjutkan pengerjaan soal tes.</td>
              	</tr>
              </table>
              
              <?php /*?><input type="hidden" id="reqStatusSetuju" value="<?=$tempStatusSetuju?>" />
              <input type="checkbox" name="reqInfo<?=$i_data?>" value="1" id="reqInfo<?=$i_data?>" /> 	
              <label for="checkbox">Dengan klik tombol SETUJU, Saya menyatakan telah membaca dan memahami seluruh petunjuk serta mengijinkan Panitia Sertifikasi Online untuk menggunakan data administrasi dalam proses Sertifikasi PKB/PLKB Online di Angkasa Pura Supports </label>
              <div id="reqSimpan" style="display:none" class="setuju"><a href="#" onclick="nextModul()">Setuju</a></div><?php */?>
        	</div>
            
        </div>
        
    	<div class="area-prev-next">
<!--             <div class="prev"><a href="?pg=dashboard"><i class="fa fa-chevron-left"></i></a></div>
 -->         <div class="prev"><a href="?pg=dashboard&mode=<?=$mode?>"><i class="fa fa-chevron-left"><span style="font-size: 20pt;"> Sebelumnya</span></i></a></div>

            <div class="next"><a href="#" onclick="nextModul()"><span style="font-size: 20pt;">Selanjutnya</span> <i class="fa fa-chevron-right"></i></a></div>
        </div>
    
    </div>
</div>
