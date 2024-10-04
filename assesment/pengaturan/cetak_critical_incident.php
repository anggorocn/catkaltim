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
// $tempPegawaiFoto= $urlfoto.$tempNIP.".png";
$tempPegawaiFoto= str_replace("..","../..",$CetakanDataPdf->getField('FOTO_LINK'));

$reqMEselonId= $CetakanDataPdf->getField("LAST_PANGKAT_ID");
$reqPegawaiAtasanLangsungNama= $CetakanDataPdf->getField("LAST_ATASAN_LANGSUNG_NAMA");
$reqPegawaiAtasanLangsungJabatan= $CetakanDataPdf->getField("LAST_ATASAN_LANGSUNG_JABATAN");


$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_HEADER_ID";
$index_loop=0;
$arrJumlahSoalHeaderPegawai=array();
$set= new CetakanDataPdf();
$set->selectByParamsSoalCriticalHeader(array(), -1,-1, $statement, $sOrder);
while($set->nextRow())
{
    $arrJumlahSoalHeaderPegawai[$index_loop]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahSoalHeaderPegawai[$index_loop]["NAMA"]= $set->getField("NAMA");
    $arrJumlahSoalHeaderPegawai[$index_loop]["TOPIK"]= $set->getField("TOPIK");
    $arrJumlahSoalHeaderPegawai[$index_loop]["TANGGAL"]= $set->getField("TANGGAL");
    // $arrJumlahSoalPegawai[$index_loop]["NAMA"]= $set->getField("NAMA");

    $index_loop++;
}
$tempJumlahSoalHeaderPegawai= $index_loop;
// echo $tempJumlahSoalHeaderPegawai; exit;
unset($set);

$sOrder= "ORDER BY A.FORMULIR_JAWABAN_CRITICAL_HEADER_ID";
$index_loop=0;
$arrJumlahJawabanHeaderPegawai=array();
// $statement= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 2 ";
$statement= " AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID < 3 AND A.PEGAWAI_ID = ".$reqPegawaiId." ";


$set= new CetakanDataPdf();
$set->selectByParamsJawabanCriticalHeader(array(), -1,-1, $statement, $sOrder);

while($set->nextRow())
{
    $arrJumlahJawabanHeaderPegawai[$index_loop]["FORMULIR_JAWABAN_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_JAWABAN_CRITICAL_HEADER_ID");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["TOPIK"]= $set->getField("TOPIK");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["TANGGAL"]= $set->getField("TANGGAL");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["BULAN"]= $set->getField("BULAN");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["TAHUN"]= $set->getField("TAHUN");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["SAMPAI"]= $set->getField("SAMPAI");


    // $arrJumlahSoalPegawai[$index_loop]["NAMA"]= $set->getField("NAMA");

    $index_loop++;
}
$tempJumlahJawabanHeaderPegawai= $index_loop;
unset($set);

$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID";
$index_check=0;
$arrJumlahSoal1Pegawai=array();
$statement= " AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID = 1 AND B.PEGAWAI_ID = ".$reqPegawaiId;
$set= new CetakanDataPdf();
$set->selectByParamsJawabanCritical(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahSoal1Pegawai[$index_check]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahSoal1Pegawai[$index_check]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID");
    $arrJumlahSoal1Pegawai[$index_check]["NAMA"]= $set->getField("NAMA");

    $index_check++;
}
$tempJumlahSoal1Pegawai= $index_check;
// print_r($tempJumlahSoal1Pegawai);exit;
unset($set);

$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID";
$index_check=0;
$arrJumlahJawaban1Pegawai=array();
$statement= " AND B.PEGAWAI_ID = ".$reqPegawaiId." AND B.FORMULIR_SOAL_CRITICAL_HEADER_ID = 1 ";
$set= new CetakanDataPdf();
$set->selectByParamsJawabanCritical(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahJawaban1Pegawai[$index_check]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahJawaban1Pegawai[$index_check]["JAWABAN"]= $set->getField("JAWABAN");

    $index_check++;
}
$tempJumlahJawaban1Pegawai= $index_check;
// print_r($tempJumlahJawabanPegawai);exit;
unset($set);


$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID";
$index_check=0;
$arrJumlahSoalNewPegawai=array();
$statement= " AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID = 2 AND B.PEGAWAI_ID = ".$reqPegawaiId;
$set= new CetakanDataPdf();
$set->selectByParamsJawabanCritical(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahSoalNewPegawai[$index_check]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahSoalNewPegawai[$index_check]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID");
    $arrJumlahSoalNewPegawai[$index_check]["NAMA"]= $set->getField("NAMA");

    $index_check++;
}
$tempJumlahSoalNewPegawai= $index_check;

$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID";
$index_check=0;
$arrJumlahJawabanPegawai=array();
$statement= " AND B.PEGAWAI_ID = ".$reqPegawaiId." AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID = 2 ";
$set= new CetakanDataPdf();
$set->selectByParamsJawabanCritical(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahJawabanPegawai[$index_check]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
     $arrJumlahJawabanPegawai[$index_check]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID");
    $arrJumlahJawabanPegawai[$index_check]["JAWABAN"]= $set->getField("JAWABAN");

    $index_check++;
}
$tempJumlahJawabanPegawai= $index_check;
unset($set);


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
		
		<p style="font-size: 14pt" align="center"><strong>Critical Incident Peserta</strong></p> 

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

			<h2>II. Critical Incident</h2>
			<table style="width: 100%">
             <?
            $no = 1;
            for($index_detil=0; $index_detil < $tempJumlahSoalHeaderPegawai/2; $index_detil++)
            {
              $reqSoal= $arrJumlahSoalHeaderPegawai[$index_detil]["NAMA"];
              $reqSoalId= $arrJumlahSoalHeaderPegawai[$index_detil]["FORMULIR_SOAL_CRITICAL_HEADER_ID"];
              $reqTopik= $arrJumlahJawabanHeaderPegawai[$index_detil]["TOPIK"];
              $reqTanggal= $arrJumlahJawabanHeaderPegawai[$index_detil]["TANGGAL"];
              $reqBulan= $arrJumlahJawabanHeaderPegawai[$index_detil]["BULAN"];
              $reqTahun= $arrJumlahJawabanHeaderPegawai[$index_detil]["TAHUN"];
              $reqSampai= $arrJumlahJawabanHeaderPegawai[$index_detil]["SAMPAI"];

            ?>
             <tr>
                <td colspan="2" style="vertical-align: middle !important;text-align: justify;"><?=$reqSoal?>
                </td>
            </tr>

            <tr>
                <td style="width:5%"></td>
                <td style="vertical-align: middle !important;"><label><b>Topik kejadian</b></label> : <?=$reqTopik?> 
                </td>
            </tr>

            <tr>
                <td style="width:5%"></td>
                <td style="vertical-align: middle !important;"><label><b>Waktu kejadian</b></label> (seingatnya) :
                  <?=$reqTanggal?>-<?=$reqBulan?>-<?=$reqTahun?>  Sampai <?=$reqSampai?>   
                </td>
            </tr>
             <?
                if ( $reqSoalId == 1)
                {
                ?>
                    <? 
                   for($index_tes=0; $index_tes < $tempJumlahSoal1Pegawai; $index_tes++)
                    {
                         $reqJawabanTambahan= $arrJumlahJawaban1Pegawai[$index_tes]["JAWABAN"];
                         $reqSoalNew= $arrJumlahSoal1Pegawai[$index_tes]["NAMA"];
                         $reqSoalJawabanId= $arrJumlahSoal1Pegawai[$index_tes]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"];
                         $reqSoalHeaderId= $reqSoalId;
                        // var_dump($reqSoalJawabanId);
                        ?>
                    <tr>
                        <td style="width:5%"></td>
                        <td style="vertical-align: middle !important;">- <?=$reqSoalNew?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:5%"></td>
                        <td style="text-align:justify;">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">
                                        <b>Jawaban</b> : 
                                    </td>
                                    <td>
                                        <?=$reqJawabanTambahan?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?
                    }
                    ?>
                 <?
                 }
                 else
                 {
                ?>
                    <? 
                   for($index_tes=0; $index_tes < $tempJumlahSoalNewPegawai; $index_tes++)
                    {
                        $reqJawabanTambahan= $arrJumlahJawabanPegawai[$index_tes]["JAWABAN"];
                         $reqSoalNew= $arrJumlahSoalNewPegawai[$index_tes]["NAMA"];
                         $reqSoalJawabanId= $arrJumlahSoalNewPegawai[$index_tes]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"];
                          $reqSoalHeaderId= $reqSoalId;
                        ?>
                    <tr>
                        <td style="width:5%"></td>
                        <td style="vertical-align: middle !important;">- <?=$reqSoalNew?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:5%"></td>
                        <td style="text-align:justify;">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">
                                        <b>Jawaban</b> : 
                                    </td>
                                    <td>
                                        <?=$reqJawabanTambahan?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?
                    }
                   
                    ?>
                <?
                }
                ?>
            <?
            $no++;
            ?>
            <tr>
                <td><br></td>
            </tr>
            <?
            }
            ?>

           </table>
	</div>
</body>
</html>