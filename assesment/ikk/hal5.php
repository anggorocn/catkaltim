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

$set= new PenilaianRekomendasi();
$statement= " AND A.PEGAWAI_ID= ".$reqId." AND JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempSaranPengembangan= $set->getField("KETERANGAN");

$set= new JadwalPegawaiDetil();
$statement= " AND A.PEGAWAI_ID= ".$reqId." AND JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsPenilaianLain(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempCatatanStrength= $set->getField("CATATAN_STRENGTH");
$tempCatatanWeaknes= $set->getField("CATATAN_WEAKNES");
$tempCatatanKesimpulan= $set->getField("KESIMPULAN");
$tempCatatanPengembangan= $set->getField("SARAN_PENGEMBANGAN");
$tempCatatanPenempatan= $set->getField("SARAN_PENEMPATAN");

$statement= "  AND P.PEGAWAI_ID = ".$reqId." AND P.JADWAL_TES_ID = ".$reqJadwalTesId;
$index_loop= 0;
$arrPenggalian="";
$set= new CetakanPdf();
$set->selectByParamsPenggalianAtribut(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrPenggalian[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrPenggalian[$index_loop]["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
	$arrPenggalian[$index_loop]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
	$index_loop++;
}
$jumlah_penggalian= $index_loop;
$colspanpenggalian= $jumlah_penggalian;

$statement= "  AND P.PEGAWAI_ID = ".$reqId." AND P.JADWAL_TES_ID = ".$reqJadwalTesId;
$index_loop= 0;
$arrFormulaPenggalian="";
$set= new CetakanPdf();
$set->selectByParamsFormulaPenggalian(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrFormulaPenggalian[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrFormulaPenggalian[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrFormulaPenggalian[$index_loop]["ROWID"]= $set->getField("ATRIBUT_ID")."-".$set->getField("PENGGALIAN_ID");
	$index_loop++;
}
$jumlah_formula_penggalian= $index_loop;

$set= new CetakanPdf();
$statement= " AND A.PEGAWAI_ID= ".$reqId." AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'";
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

$statement= "  AND TO_CHAR(P.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND P.PEGAWAI_ID = ".$reqId." AND P.JADWAL_TES_ID = ".$reqJadwalTesId;
$statementgroup= "  AND A.ASPEK_ID = '2'";
$index_loop= 0;
$arrAtribut="";
$set= new CetakanPdf();
$set->selectByParamsAtributPegawaiPenilaian(array(), -1,-1, $statement, $statementgroup);
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
	$arrAtribut[$index_loop]["LEVEL"]= $set->getField("LEVEL");
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
	// $nilaiIndividu = $nilaiIndividu + $set->getField("INDIVIDU_RATING");
	// $nilaiStandar = $nilaiStandar + $set->getField("STANDAR_RATING");
	$index_loop++;
}
$jumlah_penilaian_atribut= $index_loop;

$statement= "  AND A.JADWAL_TES_ID = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$reqId;  
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
$jpm = $setJPM->getField("JPM");

if ($jpm > 100)
	$jpm = 100;

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


function setnumberdua($val)
{
	return number_format(round($val,2), 2, '.', '');
}

// ambil data penilaian terhadap peserta berdasarkan penggalian CBI asesor
$index_loop= 0;
$arrPegawaiNilai="";
$set= new CetakanPdf();
$statement= "and c1.penggalian_id=21 AND F.ASPEK_ID = 2 AND A.PEGAWAI_ID = ".$reqId." AND C.JADWAL_TES_ID = ".$reqJadwalTesId;
$statement.= " AND EXISTS (SELECT 1 FROM atribut_penggalian X WHERE D.FORMULA_ATRIBUT_ID = X.FORMULA_ATRIBUT_ID AND A.PENGGALIAN_ID = X.PENGGALIAN_ID)";
// $statement.= " AND F.ATRIBUT_ID = '1001'";
$set->selectByParamsDeskripsiIndividuAtribut(array(), -1,-1, $statement);
// echo $set->query;exit;
$chekDouble= "";
while($set->nextRow())
{
  $datachekDouble= $set->getField("ATRIBUT_ID")."-".$set->getField("NAMA_INDIKATOR"); 

  if($chekDouble == $datachekDouble)
  	continue;

  $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ID"]= $set->getField("JADWAL_PEGAWAI_DETIL_ID");
  $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_ID"]= $set->getField("JADWAL_PEGAWAI_ID");
  $arrPegawaiNilai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
  $arrPegawaiNilai[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrPegawaiNilai[$index_loop]["PENGGALIAN_ATRIBUT"]= $set->getField("PENGGALIAN_ID")."-".$set->getField("ATRIBUT_ID");
  $arrPegawaiNilai[$index_loop]["FORM_PERMEN_ID"]= $set->getField("FORM_PERMEN_ID");
  $arrPegawaiNilai[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
  $arrPegawaiNilai[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
  $arrPegawaiNilai[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
  $arrPegawaiNilai[$index_loop]["PEGAWAI_INDIKATOR_ID"]= $set->getField("PEGAWAI_INDIKATOR_ID");
  $arrPegawaiNilai[$index_loop]["PEGAWAI_LEVEL_ID"]= $set->getField("PEGAWAI_LEVEL_ID");
  $arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"]= $set->getField("PEGAWAI_KETERANGAN");
  $arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
  $arrPegawaiNilai[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
  $arrPegawaiNilai[$index_loop]["JUMLAH_LEVEL"]= $set->getField("JUMLAH_LEVEL");

  $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ATRIBUT_ID"]= $set->getField("JADWAL_PEGAWAI_DETIL_ATRIBUT_ID");
  $arrPegawaiNilai[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
  $arrPegawaiNilai[$index_loop]["NILAI"]= $set->getField("NILAI");
  $arrPegawaiNilai[$index_loop]["GAP"]= $set->getField("GAP");
  $arrPegawaiNilai[$index_loop]["CATATAN"]= $set->getField("CATATAN");

  $chekDouble= $set->getField("ATRIBUT_ID")."-".$set->getField("NAMA_INDIKATOR");

  $index_loop++;
}
$jumlah_pegawai_nilai= $index_loop;

$index_loop= 0;
$arrPenilaian="";
$set= new CetakanPdf();
$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsDeskripsiIndividuPenilaianAtribut(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrPenilaian[$index_loop]["PENILAIAN_DETIL_ID"]= $set->getField("PENILAIAN_DETIL_ID");
  $arrPenilaian[$index_loop]["ATRIBUT_GROUP"]= $set->getField("ATRIBUT_GROUP");
  $arrPenilaian[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
  $arrPenilaian[$index_loop]["LEVEL"]= $set->getField("LEVEL");
  $arrPenilaian[$index_loop]["LEVEL_KETERANGAN"]= $set->getField("LEVEL_KETERANGAN");
  $arrPenilaian[$index_loop]["NAMA"]= $set->getField("NAMA");
  $arrPenilaian[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
  $arrPenilaian[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
  $arrPenilaian[$index_loop]["ATRIBUT_KETERANGAN"]= $set->getField("ATRIBUT_KETERANGAN");
  $arrPenilaian[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
  $arrPenilaian[$index_loop]["NILAI"]= $set->getField("NILAI");
  $arrPenilaian[$index_loop]["GAP"]= $set->getField("GAP");
  $arrPenilaian[$index_loop]["CATATAN"]= $set->getField("CATATAN");
  $bukti= $set->getField("BUKTI");
  if($bukti == "<span style=")
  $arrPenilaian[$index_loop]["BUKTI"]= "";
  else
  $arrPenilaian[$index_loop]["BUKTI"]= $bukti;

  $index_loop++;
}
$jumlah_penilaian= $index_loop;

?>
<!DOCTYPE html>
<html>

<body>
	<div class="container">

		<p style="font-size: 14pt" ><strong>IV.	ASPEK KOMPETENSI</strong></p>		
		<table style="font-size: 10pt; border-collapse: collapse;" class="bold-border table-border center" width="100%">
			<tr style="background: #febde6">
				<th rowspan="2">No</th>
				<th rowspan="2">Aspek Kompetensi</th>
				<th colspan="6">Rating</th>
				<th rowspan="2">Level</th>
				<th colspan="<?=$colspanpenggalian?>">Alat ukur yang digunakan</th>
			</tr>
			<tr style="background: #febde6">
				<th style="width: 30px">0</th>
				<th style="width: 30px">1</th>
				<th style="width: 30px">2</th>
				<th style="width: 30px">3</th>
				<th style="width: 30px">4</th>
				<th style="width: 30px">5</th>
				<?
                for($checkbox_index=0;$checkbox_index < $jumlah_penggalian;$checkbox_index++)
                {
                    $index_loop= $checkbox_index;
					$arrPenggalian[$index_loop]["PENGGALIAN_ID"];
					$tempPenggalianKode= $arrPenggalian[$index_loop]["PENGGALIAN_KODE"];
					$arrPenggalian[$index_loop]["PENGGALIAN_NAMA"];
				?>
				<th style="width:60px"><?=$tempPenggalianKode?></th>
                <?
				}
                ?>
			</tr>
            <?
			$tempGroupAspekId= $tempGroupParentAtributId= "";
			for($checkbox_index=0;$checkbox_index < $jumlah_atribut;$checkbox_index++)
			{
				$index_loop= $checkbox_index;
				$tempAtributId= $arrAtribut[$index_loop]["ATRIBUT_ID"];
				$tempAtributIdParent= $arrAtribut[$index_loop]["ATRIBUT_ID_PARENT"];
				$tempAtributAspekId= $arrAtribut[$index_loop]["ASPEK_ID"];
				$tempAtributAspekNama= $arrAtribut[$index_loop]["ASPEK_NAMA"];
				$tempAtributNama= $arrAtribut[$index_loop]["ATRIBUT_NAMA"];
				$tempAtributNilaiStandar= $arrAtribut[$index_loop]["NILAI_STANDAR"];
				$tempAtributNilai= $arrAtribut[$index_loop]["NILAI"];
				$tempAtributKesimpulan= $arrAtribut[$index_loop]["KESIMPULAN"];
				$tempLevel= $arrAtribut[$index_loop]["LEVEL"];
				
				// kondisi khusus karena salah data
				if($tempAtributId == "02")
					continue;

				if($tempGroupAspekId == $tempAtributAspekId)
				{
					if($tempAtributIdParent == "0")
					$tempNoAtributParent++;
				}
                else
                {
					$tempNoAtributParent=1;
				}
			?>
            	<?
				if($tempAtributIdParent == "0")
				{
					if($tempGroupParentAtributId == $tempAtributId)
					{
						
					}
					else
					{
						$tempNoAtribut=1;
						$colheader= 8+$colspanpenggalian;
                ?>
                    <tr class="bold-border">
                        <td><b><?=romanicNumber($tempNoAtributParent)?></b></td>
                        <td colspan="<?=$colheader?>" class="left"><b><?=$tempAtributNama?></b></td>
                    </tr>
                <?
					}
				}
				else
				{
					$valueNilai= explode(".",$tempAtributNilai);
					if($valueNilai[1]=='0'){
						$arrChecked= radioPenilaian($valueNilai[0], "√");
					}
					else{
						$arrChecked= radioPenilaian($valueNilai[0]+1, "√");
					}
					$arrStyleClassChecked= radioPenilaian($tempAtributNilaiStandar, "grey");

					// $tempAtributNilai= 2;
					$csswarnaketerangan= "";
					$csswarnaketerangan= "background-color: ".warnanilai($tempAtributNilai);

                ?>
                    <tr>
                        <td><?=$tempNoAtribut?></td>
                        <td class="left"><?=$tempAtributNama?></td>
                        <td class="<?=$arrStyleClassChecked[0]?>"><?=$arrChecked[0]?></td>
                        <td class="<?=$arrStyleClassChecked[1]?>"><?=$arrChecked[1]?></td>
                        <td class="<?=$arrStyleClassChecked[2]?>"><?=$arrChecked[2]?></td>
                        <td class="<?=$arrStyleClassChecked[3]?>"><?=$arrChecked[3]?></td>
                        <td class="<?=$arrStyleClassChecked[4]?>"><?=$arrChecked[4]?></td>
                        <td class="<?=$arrStyleClassChecked[5]?>"><?=$arrChecked[5]?></td>
                        <td class="center"><?=$tempLevel?></td>
                        <?
						for($index_detil=0;$index_detil < $jumlah_penggalian;$index_detil++)
						{
							$index_loop= $index_detil;
							$tempPenggalianKode= $arrPenggalian[$index_loop]["PENGGALIAN_KODE"];
							$arrPenggalian[$index_loop]["PENGGALIAN_NAMA"];
							$tempCariFormulaPenggalian= $tempAtributId."-".$arrPenggalian[$index_loop]["PENGGALIAN_ID"];
							$arrayKey="";
							$arrayKey= in_array_column($tempCariFormulaPenggalian, "ROWID", $arrFormulaPenggalian);
							if(empty($arrayKey))
							{
						?>
	                    	<td class="grey"></td>
	                	<?
							}
							else
							{
						?>
	                    	<td>√</td>
	                    <?
							}
						}
	                    ?>
                    </tr>
                <?
				$tempNoAtribut++;
				}
                ?>
            <?
			$tempGroupAspekId= $tempAtributAspekId;
			$tempGroupParentAtributId= $tempAtributId;
			}
            ?>
		</table>
		<br>

		<table style="font-size: 10pt; width: 100%; margin: auto">
			<tr>
				<td>Keterangan : </td>				
			</tr>
			<tr>
				<td style="width:100px">√</td>
				<td style="width:50px">:</td>
				<td>Hasil Penilaian</b></td>
			</tr>
			<tr>
				<td class="grey" style="width:100px"></td>
				<td style="width:50px">:</td>
				<td>Standar Rating</b></td>
			</tr>
			<!-- <tr>
				<td class="v-top">Jabatan</td>
				<td class="v-top">:</td>
				<td><?=$tempJabatanTes?></td>
			</tr> -->
		</table>

		<div style="margin-top: 60px">
			<table style="font-size: 14pt; border-collapse: collapse; font-weight: bold;" width="100%">
				<tr>
					<td style="width: 7%">V.</td>	
					<td>DESKRIPSI ASPEK KOMPETENSI</td>	
				</tr>
			</table>
			<div style="margin-left: 5%; border: 0px solid black; ">
				<?
				$nourut= 0;
				$nourutlevel= 0;
				$tempAtributCheckId= "";
				$indexTr= 1;
				for($index_loop=0; $index_loop < $jumlah_pegawai_nilai; $index_loop++)
				{
					$kompetensiJadwalPegawaiDetilId= $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ID"];
					$kompetensiPenggalianAtribut= $arrPegawaiNilai[$index_loop]["PENGGALIAN_ATRIBUT"];
					$kompetensiAtributId= $arrPegawaiNilai[$index_loop]["ATRIBUT_ID"];
					$kompetensiJumlahLevel= $arrPegawaiNilai[$index_loop]["JUMLAH_LEVEL"];
					$kompetensiAtributNama= $arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"];
					$kompetensiNamaIndikator= $arrPegawaiNilai[$index_loop]["NAMA_INDIKATOR"];
					$kompetensiJadwalPegawaiDetilAtributId= $arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ATRIBUT_ID"];
				?>
				<?
				// kalau atribut sama tidak dilakukan
				if($tempAtributCheckId == $kompetensiAtributId)
				{
					$indexTr++;
				}
				else
				{
					$nourut++;
					$tempnourut= $nourut - 1;

					$tempPenilaianLevel= "";
					$arrayKey= in_array_column($kompetensiAtributId, "ATRIBUT_ID", $arrPenilaian);
					// print_r($arrayKey);exit;
					if($arrayKey == ''){}
					else
					{
						$index_row= $arrayKey[0];
						$tempPenilaianLevel= $arrPenilaian[$index_row]["LEVEL"];
						$tempPenilaianLevelKeterangan= $arrPenilaian[$index_row]["LEVEL_KETERANGAN"];
						$tempPenilaianAtributKeterangan= $arrPenilaian[$index_row]["ATRIBUT_KETERANGAN"];
						$tempPenilaianNilaiStandar= $arrPenilaian[$index_row]["NILAI_STANDAR"];
						$tempPenilaianNilai= $arrPenilaian[$index_row]["NILAI"];
						$tempPenilaianGap= $arrPenilaian[$index_row]["GAP"];
						$tempPenilaianSaranPengembang= $arrPenilaian[$index_row]["BUKTI"];
						// $tempPenilaianDeskripsi= $arrPenilaian[$index_row]["BUKTI"];
						$tempPenilaianDeskripsi= $arrPenilaian[$index_row]["CATATAN"];
					}
				?>

				<?
				// kondisi kalau page 1 hnnya 1 atribut, selnjurnya 2 atribut untuk ganti page
				$checkMunculBreak= "";

				if($tempnourut % 2 == 0){}
				else
				{
					if($tempnourut == 0){}
					else
					$checkMunculBreak= "1";
				}

				?>

				<?
				if($checkMunculBreak == "1")
				{
				?>
				<!-- <pagebreak /> -->
				<?
				}
				?>

				<!-- <table style="margin-left: 1%; font-size: 10pt; margin-top: 5%; border-collapse: collapse;" width="100%"> -->
				<table style="font-size: 13pt; margin-left: 1%; margin-top: 1%; border-collapse: collapse;" width="100%">
					<tr> 
						
						<td style="width: 7%"><?=romanicNumber($nourut)?>.</td>
						<td><?=$kompetensiAtributNama?></td>
						<td style="width: 10%">Level</td>
						<td style="width: 2%">:</td>	
						<td style="width: 5%"><?=$tempPenilaianLevel?></td>
						<td style="width: 1%"></td>
					</tr>
				</table>
				<table style="border: 1px solid black; margin-left: 5%; margin-right: 5%; margin-top: 1%; width: 100%;">
					<tr>
						<td>
							Definisi Level
						</td>
					</tr>
					<tr>
						<td  style="font-size: 10pt;text-align: justify;">
							<?=rscript($tempPenilaianLevelKeterangan)?>
						</td>
					</tr>
				</table>
				<?
				}
				?>

				<?
				// kalau atribut sama tidak dilakukan
				if($tempAtributCheckId == $kompetensiAtributId){}
				else
				{
				?>
				<table style="margin-left: 7%; margin-right: 6%; margin-top: 1%; width: 100%;">
					<tr>
						<td colspan="3">Indikator Perilaku</td>
					</tr>
					<?
					
					$nourutlevel++;
                	$stylekompetensiJadwalPegawaiDetilId= "";
                	if($kompetensiJadwalPegawaiDetilId == ""){}
                	else
                	$stylekompetensiJadwalPegawaiDetilId= "font-weight: bold";

					?>
					<tr>
						<td style="width: 4%; vertical-align: top"><?=strtolower(getColoms($nourutlevel))?></td>
						<td style="font-size: 10pt;text-align: justify;<?=$stylekompetensiJadwalPegawaiDetilId?>"><?=$kompetensiNamaIndikator?></td>
						<td style="width: 4%; vertical-align: top; border: 1px solid black; text-align: center; "><?=valuechecked($kompetensiJadwalPegawaiDetilId)?></td>
					</tr>
				<?
				}
				?>
					<?
					if($tempAtributCheckId == $kompetensiAtributId && $indexTr <= $kompetensiJumlahLevel)
                    {
                    	$nourutlevel++;

                    	$stylekompetensiJadwalPegawaiDetilId= "";
                    	if($kompetensiJadwalPegawaiDetilId == ""){}
                    	else
                    	$stylekompetensiJadwalPegawaiDetilId= "font-weight: bold";
                    ?>
					<tr>
						<td style="width: 4%; vertical-align: top"><?=strtolower(getColoms($nourutlevel))?></td>
						<td style="font-size: 10pt;text-align: justify;<?=$stylekompetensiJadwalPegawaiDetilId?>"><?=$kompetensiNamaIndikator?></td>
						<td style="width: 4%; vertical-align: top; border: 1px solid black; text-align: center; "><?=valuechecked($kompetensiJadwalPegawaiDetilId)?></td>
					</tr>
					<?
					}
					?>
				<?
				if($indexTr == $kompetensiJumlahLevel)
				{
				?>
				</table>
				<?
				}
				?>

				<?
				// kalau atribut sama tidak dilakukan
				if($tempAtributCheckId == $kompetensiAtributId && $indexTr == $kompetensiJumlahLevel)
				{
				?>				

				<table style="border-spacing: 0px; margin-left: 5%; margin-right: 5%; margin-top: 1%; width: 100%;">
					<tr>
						<td style="text-align: center; border: 1px solid black; width: 10%">Nilai Standar</td>
						<td style="width: 10%"></td>
						<td style="text-align: center; border: 1px solid black; width: 10%">Nilai Individu</td>
						<td style="width: 10%"></td>
						<td style="text-align: center; border: 1px solid black; width: 10%">Kesenjangan</td>
						<td style="width: 10%"></td>
					</tr>
					<?
					$csswarnaketerangan= "";
					// $csswarnaketerangan= "background-color: ".warnanilai($tempPenilaianNilai);
					if($tempPenilaianNilaiStandar>$tempPenilaianNilai){
						$csswarnaketerangan= "background-color: yellow";
					}
					else if ($tempPenilaianNilaiStandar==$tempPenilaianNilai){
						$csswarnaketerangan= "background-color: green";
					}
					else{
						$csswarnaketerangan= "background-color: blue";
					}
					?>
					<tr>
						<td style="border: 1px solid black; text-align: center;"><?=$tempPenilaianNilaiStandar?></td>
						<td style="width: 10%"></td>
						<td style="border: 1px solid black; text-align: center; <?=$csswarnaketerangan?>"><?=$tempPenilaianNilai?></td>
						<td style="width: 10%"></td>
						<td style="border: 1px solid black; text-align: center;"><?=$tempPenilaianGap?></td>
						<td style="width: 10%"></td>
					</tr>
				</table>
				<table style="margin-left: 5%; margin-right: 5%; margin-top: 1%; width: 100%;">
					<tr>
						<td>Deskripsi Perilaku</td>
					</tr>
					<tr>
						<!-- tempPenilaianAtributKeterangan -->
						<td style="font-size: 10pt;text-align: justify; border: 1px solid black; text-align: justify; ">
							<?=rscript($tempPenilaianDeskripsi)?>
						</td>
					</tr>
				</table>
				<?

				// reset kondisi
				$nourutlevel= 0;
				$indexTr= 1;

				// kalau ada gap maka muncul
				// if($tempPenilaianGap != 0)
				if($tempPenilaianGap < 0)
				{
				?>
				<table style="margin-left: 5%; margin-right: 5%; margin-top: 1%; margin-bottom: 2%; width: 100%;">
					<tr>
						<td>Saran Pengembangan</td>
					</tr>
					<tr>
						<td style="font-size: 10pt;text-align: justify;border: 1px solid black; ">
							<?=rscript($tempPenilaianSaranPengembang)?>
						</td>
					</tr>
				</table>
				<?
				}
				?>
				<table style="margin: 1%; width: 100%;">
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
				<?
				}
				?>

				<?
				$tempAtributCheckId= $kompetensiAtributId;
				}
				?>
			</div>
		
 
		</div>

	
      
		<!-- loop end -->
	</div>
</body>
</html>