<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanDataPdf.php");
include_once("../WEB/classes/base-diklat/Peserta.php");


$reqId= httpFilterGet("reqId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$urlfoto= '../../portal/uploads/foto/'.$reqPegawaiId.'/';


$CetakanDataPdf= new CetakanDataPdf();
$CetakanDataPdf->selectByParamsDataPribadi(array(), -1,-1, " AND A.PEGAWAI_ID = ".$reqPegawaiId);
// echo $CetakanDataPdf->query;exit;
$CetakanDataPdf->firstRow();
$tempNIP= $CetakanDataPdf->getField('NIP');
$tempKtp= $CetakanDataPdf->getField('KTP');
$tempNama= $CetakanDataPdf->getField('NAMA');
$tempTempatLahir= $CetakanDataPdf->getField('TEMPAT_LAHIR');
$tempTanggalLahir= datetimeToPage($CetakanDataPdf->getField('TANGGAL_LAHIR'), "date");
$tempJenisKelamin= $CetakanDataPdf->getField('JENIS_KELAMIN');
$tempStatusKawinInfo= $CetakanDataPdf->getField('STATUS_KAWIN_INFO');
$tempAlamat= $CetakanDataPdf->getField('ALAMAT');
$tempStatusPegawaiInfo= $CetakanDataPdf->getField('STATUS_PEGAWAI_INFO');
$tempTempatKerja= $CetakanDataPdf->getField('TEMPAT_KERJA');
$tempAlamatTempatKerja= $CetakanDataPdf->getField('ALAMAT_TEMPAT_KERJA');
$tempAgama= $CetakanDataPdf->getField('AGAMA');
$tempEmail= $CetakanDataPdf->getField('EMAIL');
$tempSosmed= $CetakanDataPdf->getField('SOSIAL_MEDIA');
$tempHp= $CetakanDataPdf->getField('ALAMAT_RUMAH_TELP');
$tempAuto= $CetakanDataPdf->getField('AUTO_ANAMNESA');
$tempJabatan= $CetakanDataPdf->getField('JABATAN');
$tempPegawaiFoto= $urlfoto.$tempNIP.".png";

$reqMEselonId= $CetakanDataPdf->getField("LAST_PANGKAT_ID");
$reqPegawaiAtasanLangsungNama= $CetakanDataPdf->getField("LAST_ATASAN_LANGSUNG_NAMA");
$reqPegawaiAtasanLangsungJabatan= $CetakanDataPdf->getField("LAST_ATASAN_LANGSUNG_JABATAN");


$pangkat_new= new CetakanDataPdf();
$pangkat_new->selectByParamsPangkat();

?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="UTF-8">
	<link rel="stylesheet" href="../WEB/css/cetaknew.css" type="text/css">
</head>
<body>
	<div class="container">

		<!-- <div style="text-align: center; width: 170px;margin-left: 400px">
			<p style="padding: 5px; color: red; border: 2px solid red; font-size: 15pt">Untuk Dokumen</p>
		</div> -->
		
		<p style="font-size: 14pt" align="center"><strong>Data Pribadi Peserta</strong></p> 

		<br>
		<?
		if (file_exists($tempPegawaiFoto)) 
		{
		?>
		<div class="gallery">
			<a target="_blank" href="<?=$tempPegawaiFoto?>">
				<img src="<?=$tempPegawaiFoto?>" alt="Cinque Terre" width="400" height="300">
			</a>
		</div>
		<?
		}
		else
		{	
		?>
		
		<table style="width:100%;">
		  <tr>
		    <td align="center">
		     <img src="../../assesment/web/images/nofoto.png"  width="160" height="120" align="center"> 
		    </td>
		   </tr>
		   <tr>
		    <td align="center">
		    	Belum Upload Foto
		    </td>
		   </tr>
		</table>
		<?
		}
		?>
		<h2>I. Biodata</h2>
		<table style="font-size: 10pt">

			<tr style="border: 3px">
				<td>NAMA</td>
				<td style="width:20px">:</td>
				<td><?=$tempNama?></td>
			</tr>
			
			<tr style="border: 3px">
				<td>TEMPAT/TANGGAL LAHIR</td>
				<td style="width:20px">:</td>
				<td><?=$tempTempatLahir.' / '.$tempTanggalLahir?></td>
			</tr>

			<tr style="border: 3px">
				<td>JENIS KELAMIN</td>
				<td style="width:20px">:</td>
				<td><?=$tempJenisKelamin?></td>
			</tr>

			<tr style="border: 3px">
				<td>ALAMAT</td>
				<td style="width:20px">:</td>
				<td><?=$tempAlamat?></td>
			</tr>

			<tr style="border: 3px">
				<td>NO HP</td>
				<td style="width:20px">:</td>
				<td><?=$tempHp?></td>
			</tr>
			
			<tr style="border: 3px">
				<td>ALAMAT EMAIL</td>
				<td style="width:20px">:</td>
				<td><?=$tempEmail?></td>
			</tr>
			
			<tr style="border: 3px">
				<td>AGAMA</td>
				<td style="width:20px">:</td>
				<td><?=$tempAgama?></td>
			</tr>
			
			<tr style="border: 3px">
				<td>STATUS PERKAWINAN</td>
				<td style="width:20px">:</td>
				<td><?=$tempStatusKawinInfo?></td>
			</tr>
			
			<tr style="border: 3px">
				<td>NIP</td>
				
				<td style="width:20px">:</td>
				<td><?=$tempNIP?></td>
			</tr>

			<tr style="border: 3px">
				<td>NO KTP</td>
				<td style="width:20px">:</td>
				<td><?=$tempKtp?></td>					
			</tr>

			<tr style="border: 3px">
				<td>Eselon & Golongan</td>
				<td style="width:20px">:</td>
				<?
                while($pangkat_new->nextRow())
                {
                     $infoid= $pangkat_new->getField("PANGKAT_ID");
                     $infonama= $pangkat_new->getField("NAMA")." (".$pangkat_new->getField("KODE").")";
                     if($infoid==$reqMEselonId){
	                     ?>
	                     <td><?=$infonama?></td>
	                     <?
	                 }
             	}
             ?>
			</tr>

			<tr style="border: 3px">
				<td>Nama Atasan</td>
				<td style="width:20px">:</td>
				<td><?=$reqPegawaiAtasanLangsungNama?></td>					
			</tr>

			<tr style="border: 3px">
				<td>Jabatan Atasan</td>
				<td style="width:20px">:</td>
				<td><?=$reqPegawaiAtasanLangsungJabatan?></td>					
			</tr>
		</table>

		<h2>II. Keluarga</h2>
		<!-- <table style="width: 100%;">
            <?php
            $reqViewSaudara = new CetakanDataPdf();
            $reqViewSaudara->selectByParamsSaudara(array("PEGAWAI_ID" => (int)$reqPegawaiId));
            $x=1;
            $reqNameSpareparttanda= array();
            while ($reqViewSaudara->nextRow()) {
                $reqKeluargaSaudara = $reqViewSaudara->getField("NAMA");
                $reqKeluargaTempat = $reqViewSaudara->getField("TEMPAT");
                $reqKeluargaTgllahir = $reqViewSaudara->getField("TGL_LAHIR");
                $reqKeluargaPendidikan = $reqViewSaudara->getField("PENDIDIKAN");
                $reqKeluargaPekerjaan = $reqViewSaudara->getField("PEKERJAAN");
                $reqKeluargaSaudaraJenisKelamin = $reqViewSaudara->getField("JENIS_KELAMIN");
                $reqKeluargaStatus = $reqViewSaudara->getField("STATUS");
                $reqPendidikanKeluarga = $reqViewSaudara->getField("nama_pendidikan");
                ?>
                <tr>
                	<td><b>Anggota Keluarga <?=$x?></b></td>
                </tr>
                <tr>
                	<td style="width:30%">Nama</td>
					<td style="width:20px">:</td>
                    <td><?= $reqKeluargaSaudara ?></td>
                </tr>
                <tr>
                	<td>Status</td>
					<td style="width:20px">:</td>
                    <td>
                    	<? 
                    	if($reqKeluargaStatus == "ayah") {echo "Ayah";} 
                    	else if($reqKeluargaStatus == "ibu") {echo "Ibu";}
                        else if($reqKeluargaStatus == "anak") {echo "Anak";}
                        else if($reqKeluargaStatus == "suami") {echo "Suami";}
                        else if($reqKeluargaStatus == "istri") {echo "Istri";}
							?>
                    </td>
                </tr>
                 <tr>
                	<td>Tempat / Tanggal Lahir</td>
					<td style="width:20px">:</td>
                    <td>
                    	 <?=$reqKeluargaTempat?>/<?=$reqKeluargaTgllahir?>
                    </td>
                </tr>
                <tr>
                	<td>Jenis Kelamin</td>
					<td style="width:20px">:</td>
                    <td>
                    	<? if($reqKeluargaSaudaraJenisKelamin == "L") {?>Laki-laki
                    <? } if($reqKeluargaSaudaraJenisKelamin == "P") {?>Perempuan<?}?>
                    </td>
                </tr> 
                <tr>
                	<td>Pendidikan</td>
					<td style="width:20px">:</td>
                    <td>
                    	<?=$reqPendidikanKeluarga?>
                    </td>
                </tr>
                <tr>
                	<td>Pekerjaan</td>
					<td style="width:20px">:</td>
                    <td>
                    	<?=$reqKeluargaPekerjaan?>
                    </td>
                </tr>
                <tr>
                	<td><br></td>
                </tr>
            <?
            $x++; 
        	} ?>
        </table> -->
		<table style="border: 1px solid black; width: 100%;border-collapse: collapse;font-size: 10pt">
            <tr>
                <th style="border: 1px solid black; width: 20%;">Nama</th>
                <th style="border: 1px solid black; width: 15%;">Status</th>
                <th style="border: 1px solid black; width: 15%;">Jenis Kelamin</th>
                <th style="border: 1px solid black; width: 20%;">Tempat/Tgl Lahir</th>
                <th style="border: 1px solid black; width: 15%;">Pendidikan</th>
                <th style="border: 1px solid black; width: 15%;">Pekerjaan</th>
            </tr> 
            <?php
            $reqViewSaudara = new CetakanDataPdf();
            $reqViewSaudara->selectByParamsSaudara(array("PEGAWAI_ID" => (int)$reqPegawaiId));
            $x=0;
            $reqNameSpareparttanda= array();
            while ($reqViewSaudara->nextRow()) {
                $reqKeluargaSaudara = $reqViewSaudara->getField("NAMA");
                $reqKeluargaTempat = $reqViewSaudara->getField("TEMPAT");
                $reqKeluargaTgllahir = $reqViewSaudara->getField("TGL_LAHIR");
                $reqKeluargaPendidikan = $reqViewSaudara->getField("PENDIDIKAN");
                $reqKeluargaPekerjaan = $reqViewSaudara->getField("PEKERJAAN");
                $reqKeluargaSaudaraJenisKelamin = $reqViewSaudara->getField("JENIS_KELAMIN");
                $reqKeluargaStatus = $reqViewSaudara->getField("STATUS");
                $reqPendidikanKeluarga = $reqViewSaudara->getField("nama_pendidikan");
                ?>
                <tr>
                    <td style="border: 1px solid black">
                        <?= $reqKeluargaSaudara ?>
                    </td>
                    <td style="border: 1px solid black">
                    	<? 
                    	if($reqKeluargaStatus == "ayah") {echo "Ayah";} 
                    	else if($reqKeluargaStatus == "ibu") {echo "Ibu";}
                        else if($reqKeluargaStatus == "anak") {echo "Anak";}
                        else if($reqKeluargaStatus == "suami") {echo "Suami";}
                        else if($reqKeluargaStatus == "istri") {echo "Istri";}
							?>
                    </td>
                    <td style="border: 1px solid black" align="center">
                        <? if($reqKeluargaSaudaraJenisKelamin == "L") {?>Laki-laki
                    <? } if($reqKeluargaSaudaraJenisKelamin == "P") {?>Perempuan<?}?>
                    </td>
                    <td style="border: 1px solid black" align="center">
                       <?=$reqKeluargaTempat?>/<?=$reqKeluargaTgllahir?>
                    </td>
                    <td style="border: 1px solid black" align="center">
		                <?=$reqPendidikanKeluarga?>
		            </td>
		            <td style="border: 1px solid black">
		                <?=$reqKeluargaPekerjaan?>
		            </td>				
                </tr>
            <? } ?>
        </table>

        <h2>III. Pendidikan</h2>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><h3>Riwayat Pendidikan Formal</h3></td>
				<td style="width:20px">:</td>
				<td>
				</td>	
			</tr>				
		</table>
        <?php
        $reqViewSaudara = new CetakanDataPdf();
        $statementPendidikanFormal= "AND a.PENDIDIKAN_ID IS NOT NULL";
        $reqViewSaudara->selectByParamsPendidikanRiwayatFormal(array("PEGAWAI_ID" => (int)$reqPegawaiId),-1,-1,$statementPendidikanFormal);
        $x=0;
        $reqNameSpareparttanda= array();
        while ($reqViewSaudara->nextRow()) {
            $reqPendidikan = $reqViewSaudara->getField("nama_pendidikan");
            $reqNamaPendidikan = $reqViewSaudara->getField("NAMA_SEKOLAH");
            $reqJurusanPendidikan = $reqViewSaudara->getField("JURUSAN");
            $reqTempatPendidikan = $reqViewSaudara->getField("Tempat");
            $reqTahunAwalPendidikan = $reqViewSaudara->getField("TAHUN_AWAL");
            $reqTahunAkhirPendidikan = $reqViewSaudara->getField("TAHUN_AKHIR");
            $reqKeluargaStatus = $reqViewSaudara->getField("STATUS");
            $reqPendidikanKeluarga = $reqViewSaudara->getField("nama_pendidikan");
            $reqKeteranganPendidikan = $reqViewSaudara->getField("KETERANGAN");
            ?>
            <table>
                <tr>
                	<td>Jenjang Pendidikan <?= $reqPendidikan ?></td>
                </tr>
            </table>
            <table style="width: 100%;">
                <tr>
                	<td>Nama Instansi</td>
					<td style="width:20px">:</td>
                    <td>
                    	<?= $reqNamaPendidikan ?>
                    </td>
                </tr>
                 <tr>
                	<td>Jurusan</td>
					<td style="width:20px">:</td>
                    <td>
                    	 <?= $reqJurusanPendidikan ?>
                    </td>
                </tr>
                <tr>
                	<td>Alamat</td>
					<td style="width:20px">:</td>
                    <td>
                    	 <?=$reqTempatPendidikan?>
                    </td>
                </tr> 
                <tr>
                	<td>Tahun</td>
					<td style="width:20px">:</td>
                    <td>
                    	<?=$reqTahunAwalPendidikan?> - <?=$reqTahunAkhirPendidikan?>
                    </td>
                </tr>
                <tr>
                	<td>Keterangan</td>
					<td style="width:20px">:</td>
                    <td>
                    	<?=$reqKeteranganPendidikan?>
                    </td>
                </tr>
                <tr>
                	<td><br></td>
                </tr>
            </table>
        <? } ?>	
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><h3>Riwayat Pendidikan Non Formal</h3></td>
				<td style="width:20px">:</td>
				<td>
				</td>	
			</tr>				
		</table>
		 <?php
        $reqViewSaudara = new CetakanDataPdf();
        $statementPendidikanFormal= "AND a.PENDIDIKAN_ID IS NULL";
        $reqViewSaudara->selectByParamsPendidikanRiwayatFormal(array("PEGAWAI_ID" => (int)$reqPegawaiId),-1,-1,$statementPendidikanFormal);
        $x=1;
        $reqNameSpareparttanda= array();
        while ($reqViewSaudara->nextRow()) {
            $reqPendidikan = $reqViewSaudara->getField("nama_pendidikan");
            $reqJenisPelatihan = $reqViewSaudara->getField("Jenis_pelatihan");
            $reqJurusanPendidikan = $reqViewSaudara->getField("JURUSAN");
            $reqTempatPendidikan = $reqViewSaudara->getField("Tempat");
            $reqTahunPendidikan = $reqViewSaudara->getField("TAHUN");
            $reqKeluargaStatus = $reqViewSaudara->getField("STATUS");
            $reqPendidikanKeluarga = $reqViewSaudara->getField("nama_pendidikan");
            $reqKeteranganPendidikan = $reqViewSaudara->getField("KETERANGAN");
            ?>
            <table>
                <tr>
                	<td>Pendidikan Non Formal <?=$x?></td>
                </tr>
            </table>
            <table style="width: 100%;">
                <tr>
                	<td>Jenis Pelatihan</td>
					<td style="width:20px">:</td>
                    <td>
                    	<?= $reqJenisPelatihan ?>
                    </td>
                </tr>
                <tr>
                	<td>Instansi</td>
					<td style="width:20px">:</td>
                    <td>
                    	 <?=$reqTempatPendidikan?>
                    </td>
                </tr> 
                <tr>
                	<td>Tahun</td>
					<td style="width:20px">:</td>
                    <td>
                    	<?=$reqTahunPendidikan?>
                    </td>
                </tr>
                <tr>
                	<td>Keterangan</td>
					<td style="width:20px">:</td>
                    <td>
                    	<?=$reqKeteranganPendidikan?>
                    </td>
                </tr>
                <tr>
                	<td><br></td>
                </tr>
            </table>
        <? $x++; } ?>		
        
        <h2>III. Riwayat Pekerjaan</h2> 
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>Riwayat Pekerjaan</b></td>
				<td style="width:20px">:</td>
				<td>
				</td>	
			</tr>				
		</table>
		<table style="border: 1px solid black; width: 100%;border-collapse: collapse;font-size: 10pt">
            <tr>
                <th style="border: 1px solid black;width: 40%;">Jabatan</th>
                <th style="border: 1px solid black;width: 40%;">Unit</th>
                <th style="border: 1px solid black;width: 20%;">Tahun</th>
            </tr>
            <?php
            $reqViewSaudara = new CetakanDataPdf();
            $reqViewSaudara->selectByParamsPendidikanRiwayatJabatan(array("PEGAWAI_ID" => (int)$reqPegawaiId));
            $x=0;
            $reqNameSpareparttanda= array();
            while ($reqViewSaudara->nextRow()) {
                $reqJabatan = $reqViewSaudara->getField("JABATAN");
                $reqJabatanUnit = $reqViewSaudara->getField("UNIT_KERJA");
                $reqJabatanTahunAwal = $reqViewSaudara->getField("TAHUN_AWAL");
                $reqJabatanTahunAkhir = $reqViewSaudara->getField("TAHUN_AKHIR");
                ?>
                <tr>
                    <td style="border: 1px solid black">
                        <?= $reqJabatan ?>
                    </td> 
                    <td style="border: 1px solid black">
                        <?= $reqJabatanUnit ?>
                    </td> 
                    <td style="border: 1px solid black" align="center">
                        <?= $reqJabatanTahunAwal ?> - <?= $reqJabatanTahunAkhir ?>
                    </td> 	
                </tr>
            <? } ?>
        </table>
        <br>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>Uraian Tugas</b></td>
				<td style="width:10px">:</td>
				<td>
				</td>	
			</tr>				
		</table>
		<table style=" width: 100%;">  
            <?php
            $reqViewSaudara = new CetakanDataPdf();
            $reqViewSaudara->selectByParamsPendidikanUraianTugas(array("PEGAWAI_ID" => (int)$reqPegawaiId));
            $x=1;
            while ($reqViewSaudara->nextRow()) {
                $reqUraianTugas = $reqViewSaudara->getField("KETERANGAN");
                $reqKeluargaTempat = $reqViewSaudara->getField("TEMPAT");
                $reqKeluargaTgllahir = $reqViewSaudara->getField("TGL_LAHIR");
                $reqKeluargaPendidikan = $reqViewSaudara->getField("PENDIDIKAN");
                $reqKeluargaPekerjaan = $reqViewSaudara->getField("PEKERJAAN");
                $reqKeluargaSaudaraJenisKelamin = $reqViewSaudara->getField("JENIS_KELAMIN");
                $reqKeluargaStatus = $reqViewSaudara->getField("STATUS");
                $reqPendidikanKeluarga = $reqViewSaudara->getField("nama_pendidikan");
                ?>
                <tr>
                    <td style="width:10px">
                        <?= $x ?>.
                    </td>
                    <td>
                        <?= $reqUraianTugas ?>
                    </td>
                </tr>
            <? $x++;} ?>
        </table>

        <h2> IV. Data Pekerjaan</h2>
        <?php
        $reqViewSaudara = new CetakanDataPdf();
        $reqViewSaudara->selectByParamsDataPekerjaan(array("PEGAWAI_ID" => (int)$reqPegawaiId));
        $reqViewSaudara->firstRow();
        $reqJawab1 = $reqViewSaudara->getField("Gambaran");
        $reqJawab2 = $reqViewSaudara->getField("URAIAN");
        $reqJawab3 = $reqViewSaudara->getField("TANGGUNG_JAWAB");
        ?>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>1. Untuk memperoleh gambaran lebih jelas mengenai posisi jabatan Anda di dalam struktur organisasi, tolong Anda gambarkan di bawah ini struktur organisasi tempat Anda bekerja, dan dimana posisi jabatan Anda</b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab1 ?>
            </td>
        </tr>
        </table>

        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>2. Apa saja tanggungjawab Anda pada pekerjaan/jabatan Anda sekarang?</b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab3 ?>
            </td>
        </tr>
        </table>
    
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>3. Uraikan secara terperinci apa saja yang Anda lakukan selama ini dalam rangka menunaikan tiap-tiap tanggungjawab di atas</b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab2 ?>
            </td>
        </tr>
        </table>

        <h2>V. Data Pekerjaan</h2>
        <?php
        $reqViewSaudara = new CetakanDataPdf();
        $reqViewSaudara->selectByParamsKondisiKerja(array("PEGAWAI_ID" => (int)$reqPegawaiId));
        $reqViewSaudara->firstRow();
        if ($reqViewSaudara->getField("BAIK_ID")==1){
        	$reqJawab1 = "Baik";
        }
        else if($reqViewSaudara->getField("CUKUP_ID")==1){
        	$reqJawab1 = "Cukup Baik";
        }
        else if ($reqViewSaudara->getField("PERLU_ID")==1){
        	$reqJawab1 = "Perlu Perbaikan";
        }
        $reqJawab2 = $reqViewSaudara->getField("KONDISI");
        $reqJawab3 = $reqViewSaudara->getField("ASPEK");
        ?>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>1.Bagaimanakah kondisi kerja Anda (tempat, suasana, tugas) saat ini ?</b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab1 ?>
            </td>
        </tr>
        </table>

        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>2. Ceritakan kondisi yang Anda maksud, dan usulan perbaikan yang perlu dilakukan : </b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab3 ?>
            </td>
        </tr>
        </table>
    
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>3. Ada beberapa aspek / situasi / kondisi yang membuat Anda dapat optimal dalam bekerja. Jelaskan aspek apa saja yang dapat mendukung optimalisasi Anda dalam bekerja ?</b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab2 ?>
            </td>
        </tr>
        </table>

       	<h2>VI. Kondisi Kerja</h2>
        <?php
        $reqViewSaudara = new CetakanDataPdf();
        $reqViewSaudara->selectByParamsKondisiKerja(array("PEGAWAI_ID" => (int)$reqPegawaiId));
        $reqViewSaudara->firstRow();
        if ($reqViewSaudara->getField("BAIK_ID")==1){
        	$reqJawab1 = "Baik";
        }
        else if($reqViewSaudara->getField("CUKUP_ID")==1){
        	$reqJawab1 = "Cukup Baik";
        }
        else if ($reqViewSaudara->getField("PERLU_ID")==1){
        	$reqJawab1 = "Perlu Perbaikan";
        }
        $reqJawab2 = $reqViewSaudara->getField("KONDISI");
        $reqJawab3 = $reqViewSaudara->getField("ASPEK");
        ?>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>1.Bagaimanakah kondisi kerja Anda (tempat, suasana, tugas) saat ini ?</b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab1 ?>
            </td>
        </tr>
        </table>

        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>2. Ceritakan kondisi yang Anda maksud, dan usulan perbaikan yang perlu dilakukan : </b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab3 ?>
            </td>
        </tr>
        </table>
    
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>3. Ada beberapa aspek / situasi / kondisi yang membuat Anda dapat optimal dalam bekerja. Jelaskan aspek apa saja yang dapat mendukung optimalisasi Anda dalam bekerja ?</b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab2 ?>
            </td>
        </tr>
        </table>

        <h2>VII. Minat dan Harapan</h2>
        <?php
        $reqViewSaudara = new CetakanDataPdf();
        $reqViewSaudara->selectByParamsMinatdanHarapan(array("PEGAWAI_ID" => (int)$reqPegawaiId));
        $reqViewSaudara->firstRow();
        $reqJawab1 = $reqViewSaudara->getField("SUKAI");
        $reqJawab2 = $reqViewSaudara->getField("Tidak_sukai");
        ?>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>1. Apakah yang Anda sukai dari pekerjaan / jabatan Anda sekarang ? ( kondisi, tugas-tugas, dsb ) Mengapa ? </b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab1 ?>
            </td>
        </tr>
        </table>

        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>2. Ada beberapa aspek / situasi / kondisi yang membuat Anda dapat optimal dalam bekerja. Jelaskan aspek apa saja yang dapat mendukung optimalisasi Anda dalam bekerja ? </b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab2 ?>
            </td>
        </tr>
        </table>

         <h2>VIII. Kekuatan dan Kelemahan</h2>
        <?php
        $reqViewSaudara = new CetakanDataPdf();
        $reqViewSaudara->selectByParamsKekuatanKelemahan(array("PEGAWAI_ID" => (int)$reqPegawaiId));
        $reqViewSaudara->firstRow();
        $reqJawab1 = $reqViewSaudara->getField("KEKUATAN");
        $reqJawab2 = $reqViewSaudara->getField("KELEMAHAN");
        ?>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>1. Apakah yang menjadi kekuatan ( Strong Point ) Anda ? </b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab1 ?>
            </td>
        </tr>
        </table>

        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>2. Apakah yang menjadi kelemahan ( Weak Point ) Anda ? </b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td>
                Jawaban :
            </td>
            <td>
                <?= $reqJawab2 ?>
            </td>
        </tr>
        </table>
    </div>
</body>
</html>