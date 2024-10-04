<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");

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
	// echo $mode; exit;

	$tempPegawaiId= $userLogin->pegawaiId;
	$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
	$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
	$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
	$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
	$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
	$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

	$tempUjianId= $ujianPegawaiUjianId;
	// echo $tempUjianPegawaiTanggalAwal."--".$tempUjianPegawaiTanggalAkhir;exit();
	// $tempSystemTanggalNow= date("d-m-Y H:i:s");
	// echo dateTimeToPageCheck($tempSystemTanggalNow);exit;

	$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$ujianPegawaiUjianId;
	$set= new UjianPegawaiDaftar();
	$set->selectByParams(array(), -1,-1, $statement);
	$set->firstRow();
	// echo $set->query;exit;
	$tempUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
	// echo $tempUjianPegawaiDaftarId;exit();

	$statement= " AND A.UJIAN_ID = ".$ujianPegawaiUjianId;
	$set= new Ujian();
	$set->selectByParams(array(), -1,-1, $statement);
	$set->firstRow();
	$final_time_saving= $set->getField("BATAS_WAKTU_MENIT");
	$hours= floor($final_time_saving / 60);
	$minutes= $final_time_saving % 60;
	unset($set);

	$statement= " AND A.UJIAN_ID = ".$tempUjianId." AND B.PEGAWAI_ID = ".$tempPegawaiId;
	$set= new UjianTahap();
	$set->selectByParamsPegawaiSelesaiTahap(array(), -1,-1, $statement);
	$set->firstRow();
	// echo $set->query;exit;
	$tempJumlahPegawaiSelesaiTahap= $set->getField("JUMLAH_PEGAWAI_SELESAI_TAHAP");
	unset($set);
	// echo $tempJumlahPegawaiSelesaiTahap;exit();
}
else {

	$tempUjianPegawaiTanggalAwal = date('Y-m-d')." 00:00:00";
	$tempUjianPegawaiTanggalAkhir = date('Y-m-d')." 23:59:59";
	$tempUjianPegawaiDaftarId=1;

}
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
	function nextModul()
	{
		<?if($mode=='simulasi'){?>
			document.location.href= "?pg=tes&mode=<?=$mode?>";
		<?}else{?>
			document.location.href= "?pg=ujian_pilihan";
		<?}?>
	}	

	function kuisioner()
	{
		document.location.href= "?pg=kuisioner";
	}
</script>

<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">PERSIAPAN</div>
        </div>
        
        <div class="col-md-12">
       	  <div class="area-persetujuan">
           	<ul>
           	  		<li>Jadwal Ujian Anda <b><?=getFormattedDateTime($tempUjianPegawaiTanggalAwal)?> s/d <?=getFormattedDateTime($tempUjianPegawaiTanggalAkhir)?></b>.</li>
           	  		<li>Kerjakan Tes dengan tenang dan sungguh-sungguh.</li>
           	  		<li>Jika ada Kendala saat ujian tetap tenang dan silahkan lapor dengan REKAN OBSERVER.</li>
           	  		<li>Jangan lupa berdoa agar tes berjalan lancar. </li>
           	  		<li>Jika Anda sudah siap, tekan Tombol “MULAI UJIAN” untuk memulai TES online.</li>
                    
              </ul>
              
              <?
			  if($tempUjianPegawaiDaftarId == "")
			  {
              ?>
              <div class="alert alert-warning atas-10">
                Belum ada jadwal ujian.
              </div>
              <?
			  }
			  else
			  {
              ?>
              <div class="alert alert-warning atas-10">
              	<?
				if($tempJumlahPegawaiSelesaiTahap > 0 || $tempJumlahPegawaiSelesaiTahap == "")
				{
				?>
                Peringatan : Apabila anda telah siap melaksanakan ujian klik tombol "Mulai Ujian".  
                <?
				}
				else
				{
                ?>
                Anda Telah mengikuti Ujian
                <?
				}
                ?>
              </div>
                
                <?
				if($tempJumlahPegawaiSelesaiTahap > 0 || $tempJumlahPegawaiSelesaiTahap == "")
				{
                ?>
                <div class="ikut"><a href="#" onclick="nextModul()">Mulai Ujian &raquo;</a></div>
                <!-- <div class="ikut"><a href="#" onclick="kuisioner()">Isian Kuisioner  &raquo;</a></div> -->
                <?
				}
                ?>
  			  <?
			  }
              ?>
        	</div>
            
        </div>
        
    	<div class="area-prev-next">
            <div class="prev"><a href="?pg=form_persetujuan&mode=<?=$mode?>"><i class="fa fa-chevron-left"><span style="font-size: 20pt;"> Sebelumnya</span></i></a></div>
            <?
			if($tempUjianPegawaiDaftarId == ""){}
			else
			{
				if($tempJumlahPegawaiSelesaiTahap > 0 || $tempJumlahPegawaiSelesaiTahap == "")
				{
			?>
            <div class="next"><a href="#" onclick="nextModul()"><span style="font-size: 20pt;">Selanjutnya</span> <i class="fa fa-chevron-right"></i></a></div>
            <?
				}
			}
            ?>
        </div>
    
    </div>
</div>
