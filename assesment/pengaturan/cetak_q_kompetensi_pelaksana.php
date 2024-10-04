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
// $tempKtp= $CetakanDataPdf->getField('KTP');
// $tempNama= $CetakanDataPdf->getField('NAMA');
// $tempTempatLahir= $CetakanDataPdf->getField('TEMPAT_LAHIR');
// $tempTanggalLahir= datetimeToPage($CetakanDataPdf->getField('TANGGAL_LAHIR'), "date");
// $tempJenisKelamin= $CetakanDataPdf->getField('JENIS_KELAMIN');
// $tempStatusKawinInfo= $CetakanDataPdf->getField('STATUS_KAWIN_INFO');
// $tempAlamat= $CetakanDataPdf->getField('ALAMAT');
// $tempStatusPegawaiInfo= $CetakanDataPdf->getField('STATUS_PEGAWAI_INFO');
// $tempTempatKerja= $CetakanDataPdf->getField('TEMPAT_KERJA');
// $tempAlamatTempatKerja= $CetakanDataPdf->getField('ALAMAT_TEMPAT_KERJA');
// $tempAgama= $CetakanDataPdf->getField('AGAMA');
// $tempEmail= $CetakanDataPdf->getField('EMAIL');
// $tempSosmed= $CetakanDataPdf->getField('SOSIAL_MEDIA');
// $tempHp= $CetakanDataPdf->getField('ALAMAT_RUMAH_TELP');
// $tempAuto= $CetakanDataPdf->getField('AUTO_ANAMNESA');
// $tempJabatan= $CetakanDataPdf->getField('JABATAN');
// $tempPegawaiFoto= str_replace("..","../..",$CetakanDataPdf->getField('FOTO_LINK'));

// $reqMEselonId= $CetakanDataPdf->getField("LAST_PANGKAT_ID");
// $reqPegawaiAtasanLangsungNama= $CetakanDataPdf->getField("LAST_ATASAN_LANGSUNG_NAMA");
// $reqPegawaiAtasanLangsungJabatan= $CetakanDataPdf->getField("LAST_ATASAN_LANGSUNG_JABATAN");

$sOrder= "ORDER BY A.FORMULIR_SOAL_ID";
$index_loop=0;
$arrJumlahSoalPegawai=array();
// $statement= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 2 ";
$statement= " AND A.TIPE_FORMULIR_ID =4";

$set= new CetakanDataPdf();
$set->selectByParamsSoal(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahSoalPegawai[$index_loop]["FORMULIR_SOAL_ID"]= $set->getField("FORMULIR_SOAL_ID");
    $arrJumlahSoalPegawai[$index_loop]["TIPE_FORMULIR_ID"]= $set->getField("TIPE_FORMULIR_ID");
    $arrJumlahSoalPegawai[$index_loop]["SOAL"]= $set->getField("SOAL");

    $index_loop++;
}
$tempJumlahSoalPegawai= $index_loop;
// print_r($tempJumlahSoalPegawai);exit;
unset($set);

$sOrder= "ORDER BY A.FORMULIR_SOAL_ID";
$index_loop=0;
$arrJumlahJawabanPegawai=array();
$statement= " AND C.PEGAWAI_ID = ".$reqPegawaiId." AND C.TIPE_FORMULIR_ID = 4";

$set= new CetakanDataPdf();
$set->selectByParamsJawaban(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahJawabanPegawai[$index_loop]["FORMULIR_SOAL_ID"]= $set->getField("FORMULIR_SOAL_ID");
    $arrJumlahJawabanPegawai[$index_loop]["TIPE_FORMULIR_ID"]= $set->getField("TIPE_FORMULIR_ID");
    $arrJumlahJawabanPegawai[$index_loop]["SOAL"]= $set->getField("SOAL");
    $arrJumlahJawabanPegawai[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
    $arrJumlahJawabanPegawai[$index_loop]["JAWABAN"]= $set->getField("JAWABAN");
    $index_loop++;
}
$tempJumlahJawabanPegawai= $index_loop;

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);
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
		
		<p style="font-size: 14pt" align="center"><strong>Q Kompetensi (Pelaksana) Peserta</strong></p> 

		<br>
		<?
		if ($data['foto_original'] != "") 
		{
		?>

		<table style="width:100%;">
		  <tr>
		    <td align="center">
		     <img src="<?=$data['foto_original']?>"); alt="Cinque Terre" width="150" height="200"></td>
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
                    <? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?>
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
                    <?=$data['tempat_lahir']?> / <?=dateToDB($data['tgl_lahir'])?>
                </td>
            </tr>
            <tr>        
                <td>Jenis Kelamin</td>
				<td style="width:20px">:</td>
                <td>
					<?=$data['jenis_kelamin']?>
                </td>
            </tr>

            <tr>
                <td>Alamat</td>
				<td style="width:20px">:</td>
                <td>
                    <?=$data['alamat']?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Telepon / HP</td>
				<td style="width:20px">:</td>
                <td>
					<?=$data['no_hape']?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Alamat Email</td>
				<td style="width:20px">:</td>
                <td>
					<?=$data['email']?>
                </td>
            </tr>
            <tr>
                <td>Agama</td>
                <td style="width:20px">:</td>
               <td>
					<?=$data['agama']?>
				</td>
            </tr>  
            <tr>        
                <td>Status Pernikahan</td>
				<td style="width:20px">:</td>
                <td>
                    <?if($data['id_status_nikah']==1){?>Menikah
                    <?} else if($data['id_status_nikah']==2){?>Belum Menikah
                    <?} else if($data['id_status_nikah']==3){?>Janda/Duda
                    <?} else if($data['id_status_nikah']==5){?>Cerai<?}?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Jabatan saat ini</td>
				<td style="width:20px">:</td>
                <td>
                    <?=$data['jabatan']?>
                </td>
            </tr>
            <tr style="border: 3px">
				<td>Eselon & Golongan</td>
				<td style="width:20px">:</td>
				<?
				$pangkat_new= new CetakanDataPdf();
				$pangkat_new->selectByParamsPangkat();
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

			<h2>II. Q Kompetensi (Pelaksana)</h2>

			<table style="width: 100%">
	            <?
	            $no = 1;
	            for($index_detil=0; $index_detil < $tempJumlahSoalPegawai; $index_detil++)
	            {
	                $index_row= $arrJumlahSoalPegawai[$index_detil]["FORMULIR_SOAL_ID"];
	                $reqSoalId= $arrJumlahSoalPegawai[$index_detil]["FORMULIR_SOAL_ID"];
	                $reqSoal= $arrJumlahSoalPegawai[$index_detil]["SOAL"];
	                $reqTipe= $arrJumlahSoalPegawai[$index_detil]["TIPE_FORMULIR_ID"];
	                $reqJawaban= $arrJumlahJawabanPegawai[$index_detil]["JAWABAN"];
	            ?>
	            <tr>
	                <td style="vertical-align: middle !important;"><?=$no?>. <?=$reqSoal?>
	                </td>
	            </tr>
	            <tr>
	                 <td>
	                    <b>Jawaban : </b><?=$reqJawaban?>
	                </td>
	            </tr>
	            <?
	            $no++;
	            }
	            ?>
        	</table>
		
	</div>
</body>
</html>