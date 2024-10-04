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
// $tempPegawaiFoto= "../../assesment/".$CetakanDataPdf->getField('FOTO_LINK');
$tempPegawaiFoto= str_replace("..","../..",$CetakanDataPdf->getField('FOTO_LINK'));
// $tempPegawaiFoto= $urlfoto.$tempNIP.".png";
// echo$tempPegawaiFoto; exit;



$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApi = json_decode(file_get_contents($url), true);

$urlPasangan = 'https://api-simpeg.kaltimbkd.info//pns/data-suami-istri/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApiPasangan = json_decode(file_get_contents($urlPasangan), true);

$urlAnak = 'https://api-simpeg.kaltimbkd.info//pns/data-anak/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApiAnak = json_decode(file_get_contents($urlAnak), true);

$urlPendidikan = 'https://api-simpeg.kaltimbkd.info//pns/riwayat-pendidikan/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApiPendidikan = json_decode(file_get_contents($urlPendidikan), true);
// print_r($dataApiAnak); exit;

$url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-jabatan/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApiJabatan = json_decode(file_get_contents($url), true);

$url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-diklat-teknis/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataDiklatTeknik = json_decode(file_get_contents($url), true);

$reqMEselonId= $CetakanDataPdf->getField("LAST_PANGKAT_ID");
$reqPegawaiAtasanLangsungNama= $CetakanDataPdf->getField("LAST_ATASAN_LANGSUNG_NAMA");
$reqPegawaiAtasanLangsungJabatan= $CetakanDataPdf->getField("LAST_ATASAN_LANGSUNG_JABATAN");


$pangkat_new= new CetakanDataPdf();
$pangkat_new->selectByParamsPangkat();

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApi = json_decode(file_get_contents($url), true);
// echo $url; exit;

?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="UTF-8">
	<link rel="stylesheet" href="../WEB/css/cetaknew.css" type="text/css">
</head>
<body>
	<div class="container">
		<p style="font-size: 14pt" align="center"><strong>Data Pribadi Peserta</strong></p> 
		<br>
		<?
		if ($dataApi['foto_original'] != "") 
		{
		?>
		<table style="width:100%;">
		  <tr>
		    <td align="center">
		     <img src="<?=$dataApi['foto_original']?>"); alt="Cinque Terre" width="150" height="200"></td>
		   </tr>
		</table>
		<?
		}
		else
		{	
		?>
		<table style="width:100%;">
		  <tr>
		    <td align="center">
		     <img src="../../assesment/uploads/nofoto.png"  width="160" height="120" align="center"> 
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
		<table style="width: 100%">
            <tr>
                <td>Nama Lengkap</td>
				<td style="width:20px">:</td>
                <td>
                    <? if($dataApi['glr_depan']=='-'){ } else{ echo $dataApi['glr_depan']; }?> <?=$dataApi['nama']?> <? if($dataApi['glr_belakang']=='-'){ } else{ echo $dataApi['glr_belakang']; }?>
                </td>
            </tr>
           	<tr style="border: 3px">
				<td>NIP</td>
				
				<td style="width:20px">:</td>
				<td><?=$tempNIP?></td>
			</tr>
            <tr>
                <td>Tempat / Tanggal Lahir</td>
				<td style="width:20px">:</td>
                <td>
                    <?=$dataApi['tempat_lahir']?> / <?=dateToDB($dataApi['tgl_lahir'])?>
                </td>
            </tr>
            <tr>        
                <td>Jenis Kelamin</td>
				<td style="width:20px">:</td>
                <td>
					<?=$dataApi['jenis_kelamin']?>
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
				<td style="width:20px">:</td>
                <td>
                    <?=$dataApi['alamat']?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Telepon / HP</td>
				<td style="width:20px">:</td>
                <td>
					<?=$dataApi['no_hape']?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Alamat Email</td>
				<td style="width:20px">:</td>
                <td>
					<?=$dataApi['email']?>
                </td>
            </tr>
            <tr>
                <td>Agama</td>
                <td style="width:20px">:</td>
               <td>
					<?=$dataApi['agama']?>
				</td>
            </tr>  
            <tr>        
                <td>Status Pernikahan</td>
				<td style="width:20px">:</td>
                <td>
                    <?if($dataApi['id_status_nikah']==1){?>Menikah
                    <?} else if($dataApi['id_status_nikah']==2){?>Belum Menikah
                    <?} else if($dataApi['id_status_nikah']==3){?>Janda/Duda
                    <?} else if($dataApi['id_status_nikah']==5){?>Cerai<?}?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Jabatan saat ini</td>
				<td style="width:20px">:</td>
                <td>
                   <?
                    if($dataApiJabatan[0]['jabatan']!='')
                    {
                    ?>
                    <?=$dataApiJabatan[0]['jabatan']?>
                    <?
                    }
                    else
                    {
                    ?>
                    <?=$tempJabatan?>
                    <?
                    }
                    ?>
                </td>
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
            <tr>
                <td style="vertical-align: middle !important;"> Nama Atasan Langsung</td>
				<td style="width:20px">:</td>
                <td>
                    <?=$reqPegawaiAtasanLangsungNama?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Jabatan Atasan Langsung</td>
				<td style="width:20px">:</td>
                <td>
                    <?=$reqPegawaiAtasanLangsungJabatan?>
                </td>
            </tr>
        </table>  

		<h2>II. Keluarga</h2>
		<table style="border: 1px solid black; width: 100%;border-collapse: collapse;font-size: 10pt">
            <tr>
                <th style="border: 1px solid black; width: 10%;">Status</th>
                <th style="border: 1px solid black; width: 15%;">Nama</th>
                <th style="border: 1px solid black; width: 15%;">Jenis Kelamin</th>
                <th style="border: 1px solid black; width: 30%;">Tempat/Tgl Lahir</th>
                <th style="border: 1px solid black; width: 15%;">Pendidikan</th>
                <th style="border: 1px solid black; width: 15%;">Pekerjaan</th>
            </tr> 
            <tr>
                <td style="border: 1px solid black">
                 Pasangan
                </td>
                <td style="border: 1px solid black">
                    <?=$dataApiPasangan['0']['nama_pasutri'] ?>    
                </td>
                <td style="border: 1px solid black">
                    <?=$dataApiPasangan['0']['jenis_kelamin'] ?>
                </td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>

            <?
            for($i=0;$i<count($dataApiAnak);$i++){?>
            <tr>
                <td style="border: 1px solid black">
                    Anak
                </td>
                <td style="border: 1px solid black">
                    <?= $dataApiAnak[$i]['nama'] ?>
                </td>
                <td style="border: 1px solid black">
                    <?=$dataApiAnak[$i]['jenis_kelamin']?>
                </td>
                <td style="border: 1px solid black">
                    <table>
                        <tr>
                            <td> <?=$dataApiAnak[$i]['tempat_lahir_anak']?> / </td>
                            <td> <?=$dataApiAnak[$i]['tgl_lahir_anak']?> </td>
                        </tr>
                    </table>
                </td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>
            <?}?>
            <?php
            $reqViewSaudara = new CetakanDataPdf();
            $reqViewSaudara->selectByParamsSaudara(array("PEGAWAI_ID" => (int)$reqPegawaiId));
            // echo $reqViewSaudara->query ;exit;
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
                    	<?=$reqKeluargaStatus;?>
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
			<tr style="border: 3px; margin-bottom:20px">
				<td colspan="4"><h3>Riwayat Pendidikan Formal</h3></td>	
			</tr>				
            <tr>
                <td><br></td>
            </tr>
        <?for($i=0; $i<count($dataApiPendidikan); $i++){?>            
            <tr>
                <td style="width:5%"></td>
                <td colspan="3"><b>Jenjang Pendidikan <?=$dataApiPendidikan[$i]['singkat']?></b></td>
            </tr>
            <tr>
                <td style="width:5%"></td>
                <td>Nama Instansi</td>
                <td style="width:20px">:</td>
                <td><?=$dataApiPendidikan[$i]['nama_sekolah']?></td>
            </tr>
             <tr>
                <td style="width:5%"></td>
                <td>Jurusan</td>
                <td style="width:20px">:</td>
                <td><?=$dataApiPendidikan[$i]['jurusan']?></td>
            </tr>
            <tr>
                <td style="width:5%"></td>
                <td>Alamat</td>
                <td style="width:20px">:</td>
                <td><?=$dataApiPendidikan[$i]['alamat_pendidikan']?></td>
            </tr> 
            <tr>
                <td style="width:5%"></td>
                <td>Tahun</td>
                <td style="width:20px">:</td>
                <td><?=$reqTahunAwalPendidikan?> s/d <?=$dataApiPendidikan[$i]['tgl_lulus']?></td>
            </tr>
            <tr>
                <td style="width:5%"></td>
                <td>Keterangan</td>
                <td style="width:20px">:</td>
                <td>
                    <?=$reqKeteranganPendidikan?>
                </td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
        <?}

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
            <tr>
                <td style="width:5%"></td>
            	<td colspan="3"><b>Jenjang Pendidikan <?= $reqPendidikan ?></b></td>
            </tr>
            <tr>
                <td style="width:5%"></td>
            	<td>Nama Instansi</td>
				<td style="width:20px">:</td>
                <td>
                	<?= $reqNamaPendidikan ?>
                </td>
            </tr>
            <tr>
                <td style="width:5%"></td>
            	<td>Jurusan</td>
				<td style="width:20px">:</td>
                <td>
                	 <?= $reqJurusanPendidikan ?>
                </td>
            </tr>
            <tr>
                <td style="width:5%"></td>
            	<td>Alamat</td>
				<td style="width:20px">:</td>
                <td>
                	 <?=$reqTempatPendidikan?>
                </td>
            </tr> 
            <tr>
                <td style="width:5%"></td>
            	<td>Tahun</td>
				<td style="width:20px">:</td>
                <td>
                	<?=$reqTahunAwalPendidikan?> - <?=$reqTahunAkhirPendidikan?>
                </td>
            </tr>
            <tr>
                <td style="width:5%"></td>
            	<td>Keterangan</td>
				<td style="width:20px">:</td>
                <td>
                	<?=$reqKeteranganPendidikan?>
                </td>
            </tr>
            <tr>
            	<td><br></td>
            </tr>
        <? } ?>	
        </table>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td colspan="4"><h3>Riwayat Pendidikan Non Formal</h3></td>	
			</tr>						
        </table>
        <table style="border: 1px solid black; width: 100%;border-collapse: collapse;font-size: 10pt">
            <tr>
                <th style="border: 1px solid black;width: 5%;text-align: center">No</th>
                <th style="border: 1px solid black;width: 18%;text-align: center">Jenis</th>
                <th style="border: 1px solid black;width: 25%;text-align: center">Nama</th>
                <th style="border: 1px solid black;width: 20%;text-align: center">Penyelenggara</th>
                <th style="border: 1px solid black;width: 15%;text-align: center">Tempat</th>
                <th style="border: 1px solid black;width: 20%;text-align: center">Tanggal</th>
                <th style="border: 1px solid black;width: 7%;text-align: center">Total Jam</th>
            </tr>
            <?
            $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-diklat-struktural/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
            $dataDiklatStruktural = json_decode(file_get_contents($url), true);
            for($i=0; $i<count($dataDiklatStruktural['data']);$i++){?>
                <tr>
                    <td style="border: 1px solid black;"><?=$i+1?></td>
                    <td style="border: 1px solid black;">Diklat Struktural</td>
                    <td style="border: 1px solid black;"><?=$dataDiklatStruktural['data'][$i]['jenis_diklat_struktural']?></td>
                    <td style="border: 1px solid black;"><?=$dataDiklatStruktural['data'][$i]['penyelenggara']?></td>
                    <td style="border: 1px solid black;"><?=$dataDiklatStruktural['data'][$i]['tempat_diklat']?></td>
                    <td style="border: 1px solid black;"><center><?=$dataDiklatStruktural['data'][$i]['tgl_mulai']?> s/d <?=$dataDiklatStruktural['data'][$i]['tgl_selesai']?></center></td>
                    <td style="border: 1px solid black;"><center><?=$dataDiklatStruktural['data'][$i]['jumlah_jam']?></center></td>
                </tr>
            <?}
            $startnomer=$startnomer+count($dataDiklatStruktural['data']);
            $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-diklat-fungsional/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
            $dataDiklatFungsonal = json_decode(file_get_contents($url), true);
            for($i=0; $i<count($dataDiklatFungsonal['data']);$i++){?>
                <tr>
                    <td style="border: 1px solid black;"><?=$startnomer+$i+1?></td>
                    <td style="border: 1px solid black;">Diklat fungsional</td>
                    <td style="border: 1px solid black;"><?=$dataDiklatFungsonal['data'][$i]['jenis_diklat_fungsional']?></td>
                    <td style="border: 1px solid black;"><?=$dataDiklatFungsonal['data'][$i]['penyelenggara']?></td>
                    <td style="border: 1px solid black;"><?=$dataDiklatFungsonal['data'][$i]['tempat_diklat']?></td>
                    <td style="border: 1px solid black;"><center><?=$dataDiklatFungsonal['data'][$i]['tgl_mulai']?> s/d <?=$dataDiklatFungsonal['data'][$i]['tgl_selesai']?></center></td>
                    <td style="border: 1px solid black;"><center><?=$dataDiklatFungsonal['data'][$i]['jumlah_jam']?></center></td>
                </tr>
            <?}
            $startnomer=$startnomer+count($dataDiklatFungsonal['data']);
            
            for($i=0; $i<count($dataDiklatTeknik['data']);$i++){?>
                <tr>
                    <td style="border: 1px solid black;"><?=$startnomer+$i+1?></td>
                    <td style="border: 1px solid black;">Diklat Teknis</td>
                    <td style="border: 1px solid black;"><?=$dataDiklatTeknik['data'][$i]['nama_diklat']?></td>
                    <td style="border: 1px solid black;"><?=$dataDiklatTeknik['data'][$i]['penyelenggara']?></td>
                    <td style="border: 1px solid black;"><?=$dataDiklatTeknik['data'][$i]['tempat_diklat']?></td>
                    <td style="border: 1px solid black;"><center><?=$dataDiklatTeknik['data'][$i]['tgl_mulai']?> s/d <?=$dataDiklatTeknik['data'][$i]['tgl_selesai']?></center></td>
                    <td style="border: 1px solid black;"><center><?=$dataDiklatTeknik['data'][$i]['jumlah_jam']?></center></td>
                </tr>
            <?}
            $startnomer=$startnomer+count($dataDiklatTeknik['data']);
            $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-seminar/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
            $dataDiklatSeminar = json_decode(file_get_contents($url), true);
            for($i=0; $i<count($dataDiklatSeminar['data']);$i++){?>
                <tr>
                    <td style="border: 1px solid black;"><?=$startnomer+$i+1?></td>
                    <td style="border: 1px solid black;">Seminar</td>
                    <td style="border: 1px solid black;"><?=$dataDiklatSeminar['data'][$i]['nama_seminar']?></td>
                    <td style="border: 1px solid black;"><?=$dataDiklatSeminar['data'][$i]['penyelenggara']?></td>
                    <td style="border: 1px solid black;"><?=$dataDiklatSeminar['data'][$i]['tempat_seminar']?></td>
                    <td style="border: 1px solid black;"><center><?=$dataDiklatSeminar['data'][$i]['tgl_mulai']?> s/d <?=$dataDiklatSeminar['data'][$i]['tgl_selesai']?></center></td>
                    <td style="border: 1px solid black;"><center><?=$dataDiklatSeminar['data'][$i]['jumlah_jam']?></center></td>
                </tr>
            <?}
            ?>
        </table>
        
        <h2>IV. Riwayat Jabatan</h2> 
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>Riwayat Jabatan</b></td>
				<td style="width:20px">:</td>
				<td>
				</td>	
			</tr>				
		</table>
		<table style="border: 1px solid black; width: 100%;border-collapse: collapse;font-size: 10pt">
            <tr>
                <th style="border: 1px solid black;width: 5%;text-align: center">No</th>
                <th style="border: 1px solid black;width: 30%;text-align: center">Jabatan</th>
                <th style="border: 1px solid black;width: 20%;text-align: center">Tanggal Pelantikan</th>
                <th style="border: 1px solid black;width: 30%;text-align: center">Penandatangan</th>
                <th style="border: 1px solid black;width: 15%;text-align: center">Instansi</th>
            </tr>
            <?
            $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-jabatan/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
            $dataApiJabatan = json_decode(file_get_contents($url), true);
            for($i=0; $i<count($dataApiJabatan);$i++){?>
                <tr>
                    <td style="border: 1px solid black;"><?=$i+1?></td>
                    <td style="border: 1px solid black;"><?=$dataApiJabatan[$i]['jabatan']?></td>
                    <td style="border: 1px solid black;text-align: center;"><?=$dataApiJabatan[$i]['tgl_pelantikan']?></td>
                    <td style="border: 1px solid black;"><?=$dataApiJabatan[$i]['penandatangan']?></td>
                    <td style="border: 1px solid black;"><?=$dataApiJabatan[$i]['lokasi']?></td>
                </tr>
            <?}?>
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
            $reqViewSaudara->selectByParamsPendidikanUraianTugas(array("PEGAWAI_ID" => (int)$reqPegawaiId),-1,-1," AND A.KETERANGAN <> '' ");
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

        <h2> V. Data Pekerjaan</h2>
        <?php
        $reqViewSaudara = new CetakanDataPdf();
        $reqViewSaudara->selectByParamsDataPekerjaan(array("PEGAWAI_ID" => (int)$reqPegawaiId));
        $reqViewSaudara->firstRow();
        $reqJawab1 = $reqViewSaudara->getField("Gambaran");
        $reqJawab2 = $reqViewSaudara->getField("URAIkAN");
        $reqJawab3 = $reqViewSaudara->getField("TANGGUNG_JAWAB");
        ?>
        <table style="font-size: 10pt">
			<tr style="border: 3px">
				<td><b>1. Untuk memperoleh gambaran lebih jelas mengenai posisi jabatan Anda di dalam struktur organisasi, tolong jelaskan posisi jabatan anda sesuai struktur organisasi di tempat Anda bekerja.</b></td>
			</tr>				
		</table>
		<table>
        <tr>
            <td style="vertical-align: text-top;">
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
            <td style="vertical-align: text-top;">
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
            <td style="vertical-align: text-top;">
                Jawaban :
            </td>
            <td>
                <?= $reqJawab2 ?>
            </td>
        </tr>
        </table>

        <h2>VI. Kondisi Pekerjaan</h2>
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