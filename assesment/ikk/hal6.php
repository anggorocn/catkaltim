<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/classes/base-ikk/PenilaianRekomendasi.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

$statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new CetakanPdf();
$set->selectByParamsJadwalFormula($statement);
$set->firstRow();
$reqFormulaId= $set->getField("FORMULA_ID");
$reqTtdAsesor= $set->getField("TTD_ASESOR");
$reqTtdPimpinan= $set->getField("TTD_PIMPINAN");
$reqNipAsesor= $set->getField("NIP_ASESOR");
$reqNipPimpinan= $set->getField("NIP_PIMPINAN");
//$TanggalNow = getFormattedDate(date("Y-m-d"));
$TanggalNow= getFormattedDateTime($set->getField('TTD_TANGGAL'), false);
// echo $reqFormulaId;exit();

// $set= new JadwalPegawaiDetil();
// $statement= " AND A.PEGAWAI_ID= ".$reqId." AND JADWAL_TES_ID = ".$reqJadwalTesId;
// $set->selectByParamsPenilaianLain(array(), -1, -1, $statement);
// $set->firstRow();
// //echo $set->query;exit;
// $tempCatatanStrength= $set->getField("CATATAN_STRENGTH");
// $tempCatatanWeaknes= $set->getField("CATATAN_WEAKNES");
// $tempCatatanKesimpulan= $set->getField("KESIMPULAN");
// $tempCatatanPengembangan= $set->getField("SARAN_PENGEMBANGAN");
// $tempCatatanPenempatan= $set->getField("SARAN_PENEMPATAN");

$index_catatan= 0;
$arrPotensiStrength=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kekuatan' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPotensiStrength[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPotensiStrength[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");

  $index_catatan++;
}
$jumlahPotensiStrength= $index_catatan;


$index_catatan= 0;
$arrNilaiAkhirSaranPengembangan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'area_pengembangan' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrNilaiAkhirSaranPengembangan[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahNilaiAkhirSaranPengembangan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiKesimpulan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_rekomendasi' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiKesimpulan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPenilaianPotensiKesimpulan[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahPenilaianPotensiKesimpulan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiSaranPengembangan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_saran_pengembangan' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPenilaianPotensiSaranPengembangan[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahPenilaianPotensiSaranPengembangan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiSaranPenempatan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_saran_penempatan' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiSaranPenempatan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPenilaianPotensiSaranPenempatan[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahPenilaianPotensiSaranPenempatan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiProfilKompetensi=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kompetensi' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPenilaianPotensiProfilKompetensi[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahPenilaianPotensiProfilKompetensi= $index_catatan;

$set= new CetakanPdf();
$statement= " AND A.PEGAWAI_ID= ".$reqId." AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsPenilaian(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDate($set->getField("TANGGAL_TES"));
$tempSatkerTes= $set->getField("SATKER_TES");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempAspekNama= strtoupper($set->getField("ASPEK_NAMA"));
$tempAspekId= strtoupper($set->getField("ASPEK_ID"));
$tempTanggalTes= dateToPageCheck($set->getField("TANGGAL_TES"));
$tempTipeTes= $set->getField("TIPE_FORMULA");

$statement= " AND A.PEGAWAI_ID= ".$reqId;
$set= new CetakanPdf();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempPegawaiNama= $set->getField("NAMA");
$tempPegawaiNip= $set->getField("NIP_BARU");
$tempPegawaiTanggalLahir= dateToPageCheck($set->getField("TGL_LAHIR"),"/");
$tempPegawaiJabatanSaatIni= $set->getField("NAMA_JAB_STRUKTURAL");
$tempPegawaiGol= $set->getField("NAMA_GOL");
$tempPegawaiGolPangkat= $set->getField("NAMA_PANGKAT");
$tempUnitKerjaSaatIni= $set->getField("SATKER");
unset($set);

$sOrder= "ORDER BY A.ATRIBUT_ID ASC";
if($reqFormulaId == "8")
{
	$sOrder= "ORDER BY CASE WHEN A.ATRIBUT_ID = '0407' THEN '0401'
	WHEN A.ATRIBUT_ID = '0401' THEN '0402'
	WHEN A.ATRIBUT_ID = '0410' THEN '0403'
	WHEN A.ATRIBUT_ID = '0406' THEN '0404'
	WHEN A.ATRIBUT_ID = '0402' THEN '0405'
	WHEN A.ATRIBUT_ID = '0606' THEN '0601'
	WHEN A.ATRIBUT_ID = '0607' THEN '0602'
	WHEN A.ATRIBUT_ID = '0608' THEN '0603'
	WHEN A.ATRIBUT_ID = '0610' THEN '0604'
	WHEN A.ATRIBUT_ID = '0609' THEN '0605'
	ELSE A.ATRIBUT_ID
	END ASC";
}

$statement= "  AND TO_CHAR(P.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND P.PEGAWAI_ID = ".$reqId." AND P.JADWAL_TES_ID = ".$reqJadwalTesId;
$statementgroup= "  AND A.ASPEK_ID = '1'";
$index_loop= 0;
$arrAtribut="";
$set= new CetakanPdf();
$set->selectByParamsAtributPegawaiPenilaian(array(), -1,-1, $statement, $statementgroup, $sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrAtribut[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
	$arrAtribut[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
	$arrAtribut[$index_loop]["ASPEK_NAMA"]= $set->getField("ASPEK_NAMA");
	$arrAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrAtribut[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
	$arrAtribut[$index_loop]["NILAI"]= $set->getField("NILAI");
	$arrAtribut[$index_loop]["KESIMPULAN"]= $set->getField("KESIMPULAN");
	$index_loop++;
}
$jumlah_atribut= $index_loop;

$statement= "  AND TO_CHAR(P.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND P.PEGAWAI_ID = ".$reqId." AND P.JADWAL_TES_ID = ".$reqJadwalTesId;
$statementgroup= "  AND A.ASPEK_ID = '2'";
$index_loop= 0;
$nilaiIndividu=0;
$nilaiStandar=0;
$jpm=0;
$arrPenilaianAtribut="";
$set= new CetakanPdf();
$set->selectByParamsAtributPegawaiKompetensiPenilaian(array(), -1,-1, $statement, $statementgroup);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrPenilaianAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrPenilaianAtribut[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
	$arrPenilaianAtribut[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
	$arrPenilaianAtribut[$index_loop]["ATRIBUT_BOBOT"]= $set->getField("ATRIBUT_BOBOT");
	$arrPenilaianAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrPenilaianAtribut[$index_loop]["STANDAR_RATING"]= $set->getField("STANDAR_RATING");
	$arrPenilaianAtribut[$index_loop]["STANDAR_SKOR"]= $set->getField("STANDAR_SKOR");
	$arrPenilaianAtribut[$index_loop]["INDIVIDU_RATING"]= $set->getField("INDIVIDU_RATING");
	$arrPenilaianAtribut[$index_loop]["INDIVIDU_SKOR"]= $set->getField("INDIVIDU_SKOR");
	$arrPenilaianAtribut[$index_loop]["GAP"]= $set->getField("GAP"); 
	$index_loop++;
}
$jumlah_penilaian_atribut= $index_loop;

// $statement= "  AND A.JADWAL_TES_ID = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$reqId;  
$statement= "  AND A.JADWAL_TES_ID = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$reqId." and a.aspek_id=2";  
$statementgroup= "";
$set= new CetakanPdf();
$set->selectByParamsSumPenilaian(array(), -1,-1, $statement, $statementgroup);
$set->firstRow();
//echo $set->query;exit; 
$nilaiIndividu =  $set->getField("INDIVIDU_RATING");
$nilaiStandar =  $set->getField("STANDAR_RATING");

$statement= "  AND A.JADWAL_TES_ID = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$reqId; 
$statementgroup= "";
$index_loop= 0; 
$jpm=0;
$arrPenilaianAtributJPM="";
$setJPM= new CetakanPdf();
$setJPM->selectByParamsPenilaianJpmAkhir(array(), -1,-1, $statement, $statementgroup);
//echo $set->query;exit;
$setJPM->firstRow(); 
$jpm = $setJPM->getField("KOMPETEN_JPM");

// if ($jpm > 100)
// 	$jpm = 100;

//perhitungan
//echo 
if($tempTipeTes == '1')
{
	if ($jpm >= 80)
		$HasilKonversi = 'MS = Memenuhi Syarat.';
	elseif ($jpm >= 68 && $jpm < 80)
		$HasilKonversi = 'MMS = Masih Memenuhi Syarat.';
	elseif ($jpm < 68)
		$HasilKonversi = 'KMS = Kurang Memenuhi Syarat.';
	else
		$HasilKonversi = '-'; 
}
elseif($tempTipeTes == '2')
{
	if ($jpm >= 90)
		$HasilKonversi = 'O = Optimal.';
	elseif ($jpm >= 78 && $jpm < 90)
		$HasilKonversi = 'CO = Cukup Optimal.';
	elseif ($jpm < 78)
		$HasilKonversi = 'KO = Kurang Optimal.';
	else
		$HasilKonversi = '-'; 
}
else
	$HasilKonversi = '-'; 


$set= new JadwalPegawaiDetil();
$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsPenilaianAsesor($statement);
// echo $set->query;exit;
$set->firstRow();
$tempProfilKompetensi= $set->getField("RINGKASAN_PROFIL_KOMPETENSI");



function setnumberdua($val)
{
	return number_format(round($val,2), 2, '.', '');
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../WEB/css/cetaknew.css" type="text/css">
</head>

<body>
	<div class="container">

	<p style="font-size: 14pt" ><strong>RINGKASAN PROFIL KOMPETENSI</strong></p> 
		<p style="font-size: 10pt;text-align: justify;">Berdasarkan  hasil	penilaian kompetensi, menunjukan bahwa nilai total kompetensi Saudara <?=$tempPegawaiNama?> adalah <?=$nilaiIndividu?> dari total <?=$nilaiStandar?> atau setara dengan <?=$jpm?>% Job Person Match (JPM).</p>	
		<? 
		for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiProfilKompetensi; $index_catatan++)
		{
			$reqinfocatatan= $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"];
			$reqinfourut= $arrPenilaianPotensiProfilKompetensi[$index_catatan]["NO_URUT"];

			if($jumlahPenilaianPotensiProfilKompetensi == 1)
			{
				$reqinfourut= "";
			}
			else
			{
				$reqinfourut= $reqinfourut.".&nbsp; ";
			}

		?>
		 <p  style="font-size: 10pt;text-align: justify;"><?=$reqinfocatatan?></p>
		<?
		}
		?>
		

	<p style="font-size: 14pt" ><strong>VII. KEKUATAN DAN AREA PENGEMBANGAN</strong></p>

	1.	Kekuatan<br>
		<p style="font-size: 10pt;text-align: justify;">Berdasarkan hasil penilaian kompetensi yang dilakukan, maka yang menjadi kekuatannya adalah sebagai berikut:</p>
		<? 
		for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
		{
			$reqinfocatatan= $arrPotensiStrength[$index_catatan]["KETERANGAN"];
			$reqinfourut= $arrPotensiStrength[$index_catatan]["NO_URUT"];

			if($jumlahPotensiStrength == 1)
			{
				$reqinfourut= "";
			}
			else
			{
				$reqinfourut= $reqinfourut.".&nbsp; ";
			}
		?>
		<p  style="font-size: 10pt;text-align: justify;"><?=str_replace("text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;","display:none",$reqinfocatatan);?></p>
		<?
		}
		?>

	2.	Area Pengembangan<br>
		<p style=" font-size: 10pt;text-align: justify;">Berdasarkan hasil penilaian kompetensi yang dilakukan, maka yang menjadi area pengembangannya adalah sebagai berikut:
		<!-- <?=$tempCatatanPengembangan?><br></p> -->
		<br></p>
		<? 
		for($index_catatan=0; $index_catatan<$jumlahNilaiAkhirSaranPengembangan; $index_catatan++)
		{
			$reqinfocatatan= $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"];
			$reqinfourut= $arrNilaiAkhirSaranPengembangan[$index_catatan]["NO_URUT"];

			if($jumlahNilaiAkhirSaranPengembangan == 1)
			{
				$reqinfourut= "";
			}
			else
			{
				$reqinfourut= $reqinfourut.".&nbsp; ";
			}
		?>
		<b>Catatan:</b>
		<p  style="font-size: 10pt;text-align: justify;"><?=str_replace("text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;","display:none",$reqinfocatatan);?></p>
		<?
		}
		?>

		<p style="font-size: 14pt" ><strong>VIII.	SARAN PENGEMBANGAN</strong></p>
		<!-- <p style="font-size: 10pt;text-align: justify;"><?=$tempCatatanPengembangan?></p> -->
		<? 
		for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPengembangan; $index_catatan++)
		{
			$reqinfocatatan= $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"];
			$reqinfourut= $arrPenilaianPotensiSaranPengembangan[$index_catatan]["NO_URUT"];

			if($jumlahPenilaianPotensiSaranPengembangan == 1)
			{
				$reqinfourut= "";
			}
			else
			{
				$reqinfourut= $reqinfourut.".&nbsp; ";
			}
			if($reqinfocatatan!=''){
				?>
				<p  style="font-size: 10pt;text-align: justify;"><?=$reqinfocatatan?></p>
				<?
			}
		}
		?>

		<p style="font-size: 14pt" ><strong>IX.	REKOMENDASI</strong></p>
		<p style=" font-size: 10pt;text-align: justify;">Berdasarkan profil dan uraian di atas, maka Saudara <?=$tempPegawaiNama?> berada pada kategori :  
			<?=$HasilKonversi?> </p> <!-- 
		<p style="font-size: 10pt;text-align: justify;">Rekomendasi yang disarankan :</p> -->
		<? 
		for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiKesimpulan; $index_catatan++)
		{
			$reqinfocatatan= $arrPenilaianPotensiKesimpulan[$index_catatan]["KETERANGAN"];
			$reqinfourut= $arrPenilaianPotensiKesimpulan[$index_catatan]["NO_URUT"];

			if($jumlahPenilaianPotensiKesimpulan == 1)
			{
				$reqinfourut= "";
			}
			else
			{
				$reqinfourut= $reqinfourut.".&nbsp; ";
			}
		?>
		<p style="font-size: 10pt;text-align: justify;"><?=$reqinfocatatan?></p>
		<?
		}
		?>
		
		<!-- <pagebreak /> -->
		
		<p style="font-size: 14pt" ><strong>X.	SARAN PENEMPATAN</strong></p>
		<!-- <p style=" text-align: justify"><?=$tempCatatanPenempatan?></p> -->
		<? 
		for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPenempatan; $index_catatan++)
		{
			$reqinfocatatan= $arrPenilaianPotensiSaranPenempatan[$index_catatan]["KETERANGAN"];
			$reqinfourut= $arrPenilaianPotensiSaranPenempatan[$index_catatan]["NO_URUT"];
			if($jumlahPenilaianPotensiSaranPenempatan == 1)
			{
				$reqinfourut= "";
			}
			else
			{
				$reqinfourut= $reqinfourut.".&nbsp; ";
			}
		?>
		<p  style="font-size: 10pt;text-align: justify;"><?=$reqinfocatatan?></p>
		<?
		}
		?>
		<br>
		<br>
		<table style="font-size: 10pt; width: 100%; padding-left: 25px">
			<tr>
				<td align="left"></td>
				<td align="right">Samarinda,	<?=$TanggalNow?></td>
			</tr>
			<tr>
				<td align="left">Administrator Kegiatan</td>
				<td align="right">Pimpinan Penyelenggara </td>
			</tr>
			<tr>
				<td align="left"></td>
				<td align="right">Penilaian Kompetensi</td>
			</tr>
			<tr>
				<td align="left" style="height: 80px"></td>
				<td align="right"></td>
			</tr>
			<tr>
				<td align="left"><?=$reqTtdAsesor?></td>
				<td align="right"><?=$reqTtdPimpinan?></td>
			</tr>
			<tr>
				<td align="left"><?=$reqNipAsesor?></td>
				<td align="right"><?=$reqNipPimpinan?></td>
			</tr>
			<!-- <tr>
				<td class="v-top">Jabatan</td>
				<td class="v-top">:</td>
				<td><?=$tempJabatanTes?></td>
			</tr> -->
		</table>

	</div>
</body>
</html>