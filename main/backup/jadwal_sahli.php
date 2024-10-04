<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Jadwal.php");
include_once("../WEB/classes/base/JadwalDetil.php");

$jadwal= new Jadwal();
$jadwal->selectByParamsSahli(array(),-1,-1, "", "ORDER BY JADWAL_ID ASC");
?>
<div class="col-lg-8">
  <div id="judul-halaman">Jadwal Kegiatan</div>
    <div>
		<table class="jadwal">
        	<tr>
            	<th colspan="2">KEGIATAN</th>
            	<th class="jadwal" style="width:400px">JADWAL</th>
            	<!--<th>KETERANGAN</th>-->
            </tr>
            <?
			while($jadwal->nextRow())
			{
				$tempJadwalId= $jadwal->getField("JADWAL_ID");
				$tempJadwalNama= $jadwal->getField("NAMA");
			/*	$tempJadwalKeterangan= $jadwal->getField("KETERANGAN");*/
				
				$arrJadwalDetil="";
				$index_jadwal_detil= 0;
				$set= new JadwalDetil();
				
				$set->selectByParamsSahli(array("JADWAL_ID"=>$tempJadwalId),-1,-1);
				while($set->nextRow())
				{
					$arrJadwalDetil[$index_jadwal_detil]["NAMA"] = $set->getField("NAMA");
					$arrJadwalDetil[$index_jadwal_detil]["TANGGAL_MULAI"] = $set->getField("TANGGAL_MULAI");
					$arrJadwalDetil[$index_jadwal_detil]["TANGGAL_SAMPAI"] = $set->getField("TANGGAL_SAMPAI");
					$index_jadwal_detil++;
				}
				
				if($index_jadwal_detil > 0)
				{
					$tempJadwalDetilNama= $arrJadwalDetil[0]["NAMA"];
					$tempJadwalDetilTanggalMulai= getFormattedDateCheck($arrJadwalDetil[0]["TANGGAL_MULAI"]);
					$tempJadwalDetilTanggalSampai= getFormattedDateCheck($arrJadwalDetil[0]["TANGGAL_SAMPAI"]);
					
					if($tempJadwalDetilTanggalSampai == "")
						$tempJadwalInfo= $tempJadwalDetilTanggalMulai;
					else
						$tempJadwalInfo= $tempJadwalDetilTanggalMulai." - ".$tempJadwalDetilTanggalSampai;
						
				}
				unset($set);
            ?>
        	<tr>
            	<td rowspan="<?=$index_jadwal_detil?>" class="tahap" style="width:100px"><?=$tempJadwalNama?></td>
            	<td class="isi" style="width:500px"><?=$tempJadwalDetilNama?></td>
            	<td><?=$tempJadwalInfo?></td>
            	 
            </tr>
            <?
				for($checkbox_index=1;$checkbox_index<count($arrJadwalDetil);$checkbox_index++)
				{
					$tempJadwalDetilNama= $arrJadwalDetil[$checkbox_index]["NAMA"];
					$tempJadwalDetilTanggalMulai= getFormattedDateCheck($arrJadwalDetil[$checkbox_index]["TANGGAL_MULAI"]);
					$tempJadwalDetilTanggalSampai= getFormattedDateCheck($arrJadwalDetil[$checkbox_index]["TANGGAL_SAMPAI"]);
					
					if($tempJadwalDetilTanggalSampai == "")
						$tempJadwalInfo= $tempJadwalDetilTanggalMulai;
					else
						$tempJadwalInfo= $tempJadwalDetilTanggalMulai." - ".$tempJadwalDetilTanggalSampai;
			?>
                <tr <?php /*?>class="baris"<?php */?>>
                    <td><?=$tempJadwalDetilNama?></td>
                    <td><?=$tempJadwalInfo?></td>
                </tr>
            <?
				}
			}
            ?>
       	</table>
    </div>
</div>
