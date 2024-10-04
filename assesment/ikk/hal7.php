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
$statement= " AND F.ASPEK_ID = 2 AND A.PEGAWAI_ID = ".$reqId." AND C.JADWAL_TES_ID = ".$reqJadwalTesId;
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
// print_r($arrPegawaiNilai);exit;

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
// print_r($arrPenilaian);exit;

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
		
		<p style="font-size: 15pt; text-align: center;">DESKRIPSI INDIVIDU</p>


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
			<!-- <tr>
				<td class="v-top">Jabatan</td>
				<td class="v-top">:</td>
				<td><?=$tempJabatanTes?></td>
			</tr> -->
		</table>	

		<div style="margin-top: 60px">
			<table style="font-size: 14pt; border-collapse: collapse; font-weight: bold;" width="100%">
				<tr>
					<td style="width: 7%">A.</td>	
					<td>ASPEK KOMPETENSI</td>	
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
						$tempPenilaianSaranPengembang= $arrPenilaian[$index_row]["CATATAN"];
						$tempPenilaianDeskripsi= $arrPenilaian[$index_row]["BUKTI"];

						/*$arrPenilaian[$index_loop]["PENILAIAN_DETIL_ID"]= $set->getField("PENILAIAN_DETIL_ID");
						$arrPenilaian[$index_loop]["ATRIBUT_GROUP"]= $set->getField("ATRIBUT_GROUP");
						$arrPenilaian[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
						
						$arrPenilaian[$index_loop]["NAMA"]= $set->getField("NAMA");
						$arrPenilaian[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
						$arrPenilaian[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");*/
					}
				?>

				<?
				// kondisi kalau page 1 hnnya 1 atribut, selnjurnya 2 atribut untuk ganti page
				$checkMunculBreak= "";
				/*if($nourut == "2")
				{
					$checkMunculBreak= "1";
				}
				else
				{
					$checkMunculBreak= "";
					if($nourut % 2 == 1){}
					else
					$checkMunculBreak= "1";
				}*/

				if($tempnourut % 2 == 1){}
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
				<pagebreak />
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
							Definisi Umum
							<!-- <?=$kompetensiAtributNama?> Level <?=$tempPenilaianLevel?> -->
						</td>
					</tr>
					<tr>
						<td>
							<?=$tempPenilaianLevelKeterangan?>
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
						<td style="<?=$stylekompetensiJadwalPegawaiDetilId?>"><?=$kompetensiNamaIndikator?></td>
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
						<td style="<?=$stylekompetensiJadwalPegawaiDetilId?>"><?=$kompetensiNamaIndikator?></td>
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
				<table style="margin-left: 5%; margin-right: 5%; margin-top: 1%; width: 100%;">
					<tr>
						<td>Deskripsi Perilaku</td>
					</tr>
					<tr>
						<!-- tempPenilaianAtributKeterangan -->
						<td style="border: 1px solid black; text-align: justify; ">
							<?=$tempPenilaianDeskripsi?>
						</td>
					</tr>
				</table>
				<!-- <table style="border-spacing: 0px; margin-left: 5%; margin-right: 5%; margin-top: 5%; width: 100%;"> -->
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
					$csswarnaketerangan= "background-color: ".warnanilai($tempPenilaianNilai);
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
						<td style="border: 1px solid black; text-align: center;"><?=$tempPenilaianNilaiStandar?></td>
						<td style="width: 10%"></td>
						<td style="border: 1px solid black; text-align: center; <?=$csswarnaketerangan?>"><?=$tempPenilaianNilai?></td>
						<td style="width: 10%"></td>
						<td style="border: 1px solid black; text-align: center;"><?=$tempPenilaianGap?></td>
						<td style="width: 10%"></td>
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
						<td style="border: 1px solid black; ">
							<?=$tempPenilaianSaranPengembang?>
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

				<?
				$tempAtributCheckId= $kompetensiAtributId;
				}
				?>
			</div>

			<!-- <tr>
								<td colspan="2"></td>
								<td colspan="3">
									
								</td>
							</tr>
							
							<tr>
								<td colspan="2"></td>
								<td>1</td>
								<td>Indikator Perilaku 1</td>
								<td>V</td>
							</tr> -->
							<!-- <tr>
								<td colspan="2"></td>
								<td style="padding-top: 200px" colspan="3">
									<table style="width: 100%;">
										<tr>
											<td style="width: 1%">a.</td>
											<td>asdsad</td>
											<td style="width: 1%">V</td>
										</tr>
									</table>
								</td>
							</tr> -->

				<!-- <tr>
					<td style="width: 150px">Definisi umum Integritas Level 1</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">Indikator Perilaku	</td>	
				</tr>
				<tr>
					<td style="width: 150px">a. Indikator Perilaku 1</td>	
				</tr>
				<tr>
					<td  style="width: 150px">b. Indikator Perilaku 2</td>	
				</tr>
				<tr>
					<td style="width: 150px">c. Indikator Perilaku 3</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">Deskripsi Perilaku	</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">Nilai Standar</td>	
					<td style="width: 150px">Nilai Individu</td>
					<td style="width: 150px">Nilai Kesenjangan</td>		
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">Saran Pengembangan	</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">*) kalo ada kesenjangan baru muncul</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">B.	ASPEK POTENSI</td>	
				</tr>
				<tr>
					<td style="width: 150px">1 POTENSI KECERDASAN</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">Deskripsi Perilaku</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">Nilai Standar</td>	
					<td style="width: 150px">Nilai Individu</td>
					<td style="width: 150px">Nilai Kesenjangan</td>		
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">Saran Pengembangan	</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>
				<tr>
					<td style="width: 150px">*) kalo ada kesenjangan baru muncul</td>	
				</tr>
				<tr>
					<td><br></td>	
				</tr>

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