<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanDataPdf.php");
include_once("../WEB/classes/base-diklat/Peserta.php");
include_once("../WEB/classes/base-cat/Kuisioner.php");


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

		<!-- <div style="text-align: center; width: 170px;margin-left: 400px">
			<p style="padding: 5px; color: red; border: 2px solid red; font-size: 15pt">Untuk Dokumen</p>
		</div> -->
		
		<p style="font-size: 14pt" align="center"><strong>Kuisioner Peserta</strong></p> 

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
                    <?=$dataApi['jabatan']?>
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

		<h2>II. KUISIONER</h2>
		<table width="100%">
            <?php
            $reqTipeView = new Kuisioner();
            $reqTipeView->selectByParamsTipe(array());
            // echo $reqViewSaudara->query ;exit;
            $x=1;
            while ($reqTipeView->nextRow()) {
                ?>
                
                <?
                $reqTipeNama = $reqTipeView->getField("NAMA");
                $reqTipeId = $reqTipeView->getField("KUISIONER_TIPE_ID");
                ?>
                <tr>
                    <td><b><?=romanicNumber($x)?>.</b></td>
                    <td><b><?=$reqTipeNama?></b></td>
                </tr>
                <?
                $reqSoalView = new Kuisioner();
                $reqSoalView->selectByParamsSoal(array('KUISIONER_TIPE_ID'=>$reqTipeId));
                $reqJawabanView = new Kuisioner();
               
                $xx=1;
                ?>
                <tr>
                    <td></td>
                    <td>
                        <table><?
                while ($reqSoalView->nextRow()) {
                    $reqPertanyaan = $reqSoalView->getField("PERTANYAAN");
                    $reqPertanyaanId = $reqSoalView->getField("KUISIONER_ID");

                    $reqJawabanView->selectByParamsJawaban(array('a.KUISIONER_pertanyaan_ID'=>$reqPertanyaanId,'a.PEGAWAI_ID'=>$reqPegawaiId,'a.UJIAN_ID'=>$reqId));
                    // echo $reqJawabanView->query; exit;
                    $reqJawabanView->firstRow();
                    $reqJawaban = $reqJawabanView->getField("jawaban");
                    $reqJawabancekDetil = $reqJawabanView->getField("add_detil");
                    $reqJawabanDetil = $reqJawabanView->getField("KUISIONER_DETIL");

                ?>
                <tr>
                    <td style="vertical-align: top"><?=$xx?>.</td>
                    <td><?=$reqPertanyaan?></td>
                </tr> 

                <tr>
                    <td></td>
                    <td><b>Jawaban</b> : <?=$reqJawaban?></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                    <?if ($reqJawabancekDetil==1){?><b>Alasan</b> :<?= $reqJawabanDetil?> <?}?>
                    </td>
                </tr>               
                <?
                $xx++;
                }
                ?>
                        </table>
                    </td>
                </tr> <?
            $x++; 
            } ?>
        </table>
    </div>
</body>
</html>