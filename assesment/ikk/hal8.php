<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
// echo $reqJadwalTesId;exit();

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

// ambil data penilaian terhadap peserta berdasarkan penggalian CBI asesor
$index_loop= 0;
$arrPegawaiNilai="";
$set= new CetakanPdf();

$statementgroup= "";
$statement= "  AND TO_CHAR(P.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND P.PEGAWAI_ID = ".$reqId;
$index_loop= 0;
$arrPenilaianAtribut="";
$set= new CetakanPdf();
$set->selectByParamsAtributPegawaiPotensiDeskripsiPenilaian(array(), -1,-1, $statement, $statementgroup);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrPegawaiNilai[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
  $arrPegawaiNilai[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
  $arrPegawaiNilai[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
  $arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
  $arrPegawaiNilai[$index_loop]["STANDAR_RATING"]= $set->getField("STANDAR_RATING");
  $arrPegawaiNilai[$index_loop]["INDIVIDU_RATING"]= $set->getField("INDIVIDU_RATING");
  $arrPegawaiNilai[$index_loop]["GAP"]= $set->getField("GAP");
  $arrPegawaiNilai[$index_loop]["BUKTI"]= $set->getField("BUKTI");
  $arrPegawaiNilai[$index_loop]["CATATAN"]= $set->getField("CATATAN");

  $index_loop++;
}
$jumlah_pegawai_nilai= $index_loop;
// print_r($arrPegawaiNilai);exit;

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
		
		<!-- <p style="font-size: 15pt; text-align: center;">DESKRIPSI INDIVIDU</p>


		<table style="font-size: 10pt; width: 100%; margin: auto">
			<tr>
				<td style="width:150px">Nama</td>
				<td style="width:50px">:</td>
				<td><b><?=$tempPegawaiNama?></b></td>
			</tr>
			<tr>
				<td>NIP.</td>
				<td>:</td>
				<td><?=$tempPegawaiNip?></td>
			</tr>
			<tr>
				<td>Tanggal Lahir</td>
				<td>:</td>
				<td><?=$tempPegawaiTanggalLahir?></td>
			</tr>
			<tr>
				<td>Pangkat/Gol. Ruang</td>
				<td>:</td>
				<td><?=$tempPegawaiGolPangkat?> (<?=$tempPegawaiGol?>)</td>
			</tr>
			<tr>
				<td class="v-top">Jabatan</td>
				<td class="v-top">:</td>
				<td><?=$tempJabatanTes?></td>
			</tr>
		</table>	 -->

		<div style="margin-top: 0px">
			<table style="font-size: 10pt; border-collapse: collapse; font-weight: bold;" width="100%">
				<tr>
					<td style="width: 7%">B.</td>	
					<td>ASPEK POTENSI</td>	
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
					$potensiAtributId= $arrPegawaiNilai[$index_loop]["ATRIBUT_ID"];
					$potensiAtributNama= $arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"];
					$potensiStandarRating= $arrPegawaiNilai[$index_loop]["STANDAR_RATING"];
					$potensiIndividuRating= $arrPegawaiNilai[$index_loop]["INDIVIDU_RATING"];
					$potensiGap= $arrPegawaiNilai[$index_loop]["GAP"];
					$potensiDeskripsi= $arrPegawaiNilai[$index_loop]["BUKTI"];
					$potensiSaran= $arrPegawaiNilai[$index_loop]["CATATAN"];
					$nourut++;
				?>

				<!-- <?
				// kondisi kalau page 1 hnnya 1 atribut, selnjurnya 2 atribut untuk ganti page
				$checkMunculBreak= "";
				if($nourut == "2")
				{
					$checkMunculBreak= "1";
				}
				else
				{
					$checkMunculBreak= "";
					if($nourut % 2 == 1){}
					else
					$checkMunculBreak= "1";
				}
				?>
				<?
				if($checkMunculBreak == "1")
				{
				?>
				<pagebreak />
				<?
				}
				?> -->

				<table style="margin-left: 1%; font-size: 10pt; margin-top: 3%; border-collapse: collapse;" width="100%">
					<tr>
						<td style="width: 6%"><?=romanicNumber($nourut)?>.</td>
						<td><?=$potensiAtributNama?></td>
						<td style="width: 10%"></td>
						<td style="width: 2%"></td>	
						<td style="width: 5%"></td>
						<td style="width: 1%"></td>
					</tr>
				</table>
				<table style="margin-left: 5%; margin-right: 5%; margin-top: 3%; width: 100%;">
					<tr>
						<td>Deskripsi Perilaku</td>
					</tr>
					<tr>
						<td style="border: 1px solid black; text-align: justify; ">
							<?=$potensiDeskripsi?>
						</td>
					</tr>
				</table>
				<table style="border-spacing: 0px; margin-left: 5%; margin-right: 5%; margin-top: 3%; width: 100%;">
					<?
					$csswarnaketerangan= "";
					$csswarnaketerangan= "background-color: ".warnanilai($potensiIndividuRating);
					// if($tempPenilaianNilai == "1")
					// $csswarnaketerangan= "background-color: #FF69B4";
					// elseif($tempPenilaianNilai == "2")
					// $csswarnaketerangan= "background-color: #FF0000";
					// elseif($tempPenilaianNilai == "3")
					// $csswarnaketerangan= "background-color: #FFFF00";
					// elseif($tempPenilaianNilai == "4")
					// $csswarnaketerangan= "background-color: #ADFF2F";
					// elseif($tempPenilaianNilai == "5")
					// $csswarnaketerangan= "background-color: #87CEFA";
					?>
					<tr>
						<td style="text-align: center; border: 1px solid black; width: 10%">Nilai Standar</td>
						<td style="width: 10%"></td>
						<td style="text-align: center; border: 1px solid black; width: 10%">Nilai Individu</td>
						<td style="width: 10%"></td>
						<td style="text-align: center; border: 1px solid black; width: 10%">Kesenjangan</td>
						<td style="width: 10%"></td>
					</tr>
					<tr>
						<td style="border: 1px solid black; text-align: center;"><?=$potensiStandarRating?></td>
						<td style="width: 10%"></td>
						<td style="border: 1px solid black; text-align: center; <?=$csswarnaketerangan?>"><?=setnumberdua($potensiIndividuRating)?></td>
						<td style="width: 10%"></td>
						<td style="border: 1px solid black; text-align: center;"><?=setnumberdua($potensiGap)?></td>
						<td style="width: 10%"></td>
					</tr>
				</table>
				<?
				// kalau ada gap maka muncul
				if($potensiGap != 0)
				{
				?>
				<table style="margin-left: 5%; margin-right: 5%; margin-top: 3%; margin-bottom: 2%; width: 100%;">
					<tr>
						<td>Saran Pengembang</td>
					</tr>
					<tr>
						<td style="border: 1px solid black; ">
							<?=$potensiSaran?>
						</td>
					</tr>
				</table>
				<?
				}
				?>
				<!-- <table style="margin: 1%; width: 100%;">
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table> -->

				<?
				}
				?>
			</div>

			<!--

				<tr>
					<td style="width: 150px"></td>	
					<td style="width: 150px"></td>	
					<td style="width: 150px">Jakarta,........<?=$reqTahun?> </td>	
				</tr>
				<tr>
					<td style="width: 150px"></td>	
					<td style="width: 150px"></td>	
					<td style="width: 150px">Assessor, </td>	
				</tr>
				<tr>
					<td style="width: 150px"></td>	
					<td style="width: 150px"></td>	
					<td>(.......)</td>	
				</tr>
			</table> -->
		</div>

		<!-- <div class="footer">
			<p>Pusat Penilaian Kementerian Dalam Negeri</p>
		</div> -->
	</div>
</body>
</html>