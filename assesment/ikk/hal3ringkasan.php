<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/classes/base-ikk/PenilaianRekomendasi.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");
include_once("../WEB/classes/base/RekapNewAssesment.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");



$statementGrafik=  " AND B.PEGAWAI_ID = ".$reqId." AND B.JADWAL_TES_ID = ".$reqJadwalTesId;
$setGrafik= new Kelautan();
$setGrafik->selectByParamsMonitoringTableTalentPoolJPMGrafik(array(),  -1,-1, $statementGrafik,'', $reqTahun);
$setGrafik->firstRow();
$tempKode= $setGrafik->getField("KODE_KUADRAN");
$tempNama= $setGrafik->getField("NAMA_KUADRAN");
//echo $setGrafik->query;exit;

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

$index_catatan= 0;
$arrNilaiAkhirSaranPengembangan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_saran_pengembangan' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
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

$statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new CetakanPdf();
$set->selectByParamsJadwalFormula($statement);
$set->firstRow();
$reqFormulaId= $set->getField("FORMULA_ID");
$reqTtdAsesor= $set->getField("TTD_ASESOR");
$reqTtdPimpinan= $set->getField("TTD_PIMPINAN");
$reqNipAsesor= $set->getField("NIP_ASESOR");
$reqNipPimpinan= $set->getField("NIP_PIMPINAN");
$TanggalNow= getFormattedDateTime($set->getField('TTD_TANGGAL'), false);

if(!empty($reqJadwalTesId))
{
	$jumlahdetil= 0;
	$setdetil= new RekapNewAssesment();
	$setdetil->selectByParamsFormula(array(), -1, -1, '', $reqJadwalTesId);
	while ($setdetil->nextRow()) 
	{
		$detilrowid= $setdetil->getField("FORMULA_ATRIBUT_ID");
		array_push($aColumns, "DATA-".$detilrowid);
	}
}

$set= new RekapNewAssesment();
$statement= " AND A.PEGAWAI_ID= ".$reqId." AND A.JADWAL_TES_ID = '".$reqJadwalTesId."'";
$set->selectByParamsPenilaian(array(), -1, -1, $statement);
$set->firstRow();
// echo $set->query;exit;
$reqFormulaId= $set->getField("FORMULA_ID");
$tempTanggalTes= getFormattedDate($set->getField("TANGGAL_TES"));
$tempSatkerTes= $set->getField("SATKER_TES");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempTempatTes= $set->getField("TEMPAT");
$tempAspekNama= strtoupper($set->getField("ASPEK_NAMA"));
$tempAspekId= strtoupper($set->getField("ASPEK_ID"));
$tempTanggalTes2= dateToPageCheck($set->getField("TANGGAL_TES"));
$tempTipeTes= $set->getField("TIPE");
$tempTipeFormula= $set->getField("FORMULA");
$tempTipe= $set->getField("TIPE_FORMULA");



$aColumns = array("NOMOR", "NAMA_NIP_BARU", "JABATAN_NAMA");

$arrkolomdata= array(
	array("label"=>"No Peserta", "labeldetil"=>"Standar Kompetensi Jabatan", "rotate"=>"")
	, array("label"=>"NAMA/NIP", "labeldetil"=>"", "rotate"=>"")
	, array("label"=>"JABATAN", "labeldetil"=>"", "rotate"=>"")
);

$totalstandart= 0;
if(!empty($reqJadwalTesId))
{
	$jumlahdetil= 0;
	$setdetil= new RekapNewAssesment();
	$setdetil->selectByParamsJadwalTes(array(), -1, -1, '', $reqJadwalTesId);
	// echo $setdetil->query;exit();
	while ($setdetil->nextRow()) 
	{
		array_push($arrkolomdata,     
			array("label"=>$setdetil->getField("ATRIBUT_NAMA"), "labeldetil"=>$setdetil->getField("NILAI_STANDAR"), "rotate"=>"")
		);

		$detilrowid= $setdetil->getField("FORMULA_ATRIBUT_ID");
		array_push($aColumns, "DATA-".$detilrowid);

		$totalstandart += $setdetil->getField("NILAI_STANDAR");
		$jumlahdetil++;
	}
}
// echo $jumlahdetil;exit();
array_push($arrkolomdata,
	array("label"=>"JUMLAH SKOR", "labeldetil"=>$totalstandart, "rotate"=>"")
	, array("label"=>"JPM", "labeldetil"=>"", "rotate"=>"") 
);
// print_r($arrkolomdata);exit();

array_push($aColumns, "TOTAL_STANDART");
array_push($aColumns, "JPM_TOTAL"); 
// print_r($aColumns);exit();



$statement= " AND B.PEGAWAI_ID= ".$reqId;
$set= new RekapNewAssesment();
$sOrder= "ORDER BY COALESCE(B.JPM,0) DESC";
$set->selectByParamsNewRekap(array(), -1, -1, $statement, $reqFormulaId, $sOrder);
// echo $set->query;exit;



$statement= "  AND A.JADWAL_TES_ID = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$reqId; 
$statementgroup= "";
$index_loop= 0; 
$jpm=0;
$arrPenilaianAtributJPM="";
$setJPM= new CetakanPdf();
$setJPM->selectByParamsPenilaianJpmAkhir(array(), -1,-1, $statement, $statementgroup);
//echo $set->query;exit;
$setJPM->firstRow(); 
$jumlah_penilaian_atribut= $index_loop;

$jpm = $setJPM->getField("JPM");

if ($jpm > 100)
	$jpm = 100;

//perhitungan
//echo 
if($tempTipe == '1')
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
elseif($tempTipe == '2')
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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../WEB/css/cetaknew.css" type="text/css"> 
</head>

<body>
	<div class="container">
		<p style="font-size: 10pt" >Rekapitulasi Hasil Penilaian Kompetensi</p>
		<p style="font-size: 10pt" ><?=$tempTipeTes?></p>
		<p style="font-size: 10pt" ><?=$tempTempatTes?></p>
		<p style="font-size: 10pt" ><?=$tempTanggalTes?></p> 
		<p style="font-size: 14pt" ><strong>PROFIL KOMPETENSI</strong></p>		
		<table style="font-size: 10pt; border-collapse: collapse;" class="bold-border table-border center" width="100%">
			<tr style="background: #febde6">
	        	<?
	        	$mulaicolom= 2;
	        	$rowspan= 1;
	        	$batas= -1;
	        	if(!empty($reqJadwalTesId) && $jumlahdetil > 0)
				{
					$rowspan= 2;
	        		// $batas= count($arrkolomdata) - $jumlahdetil;
	        		$batas= $jumlahdetil + $mulaicolom;
	        	}

				for($col=0; $col<count($arrkolomdata); $col++)
				{
					$infostyle= "";
					if(!empty($arrkolomdata[$col]['rotate']))
					{
						$infostyle= "text-rotate: 90";
					}

					if($col <= $mulaicolom)
					{
				?>
					<th rowspan="<?=$rowspan?>" style="<?=$infostyle?>"><?=$arrkolomdata[$col]['label']?></th>
				<?
					}
					elseif($col > $mulaicolom && $col <= $batas)
					// elseif($col <= $batas)
					{
						if($col == $batas)
						{
				?>
					<th style="text-align: center;" colspan="<?=$jumlahdetil?>">Standar Kompetensi</th>
				<?
						}
					}
					else
					{
				?>
					<th rowspan="<?=$rowspan?>" style="<?=$infostyle?>"><?=$arrkolomdata[$col]['label']?></th>
				<?
					}
				}
				?>
	        </tr>

	        <?
	        if(!empty($reqJadwalTesId) && $jumlahdetil > 0)
	        {
	        ?>
	        <tr style="background: #febde6">
	        	<?
	        	for($col=0; $col<count($arrkolomdata); $col++)
				{
					if($col > $mulaicolom && $col <= $batas)
					{
				?>
					<th><?=$arrkolomdata[$col]['label']?></th>
				<?
					}
				}
				?>
	        </tr>
	        <?
	    	}
	        ?>

	        <tr class="bold-border">
	        	<?
	        	$mulaicolom= 2;
	        	for($col=0; $col<count($arrkolomdata); $col++)
	        	{
	        		if($col <= $mulaicolom)
	        		{
		        		if($col == 0)
		        		{
	        	?>
	        		<td colspan="<?=$mulaicolom+1?>" class="center" style="text-align: center;font-size: 14pt;"><b>Standar Kompetensi Jabatan</b></td>
	        	<?
	        			}
	        		}
	        		else
	        		{
	        	?>
	        		<td><?=$arrkolomdata[$col]['labeldetil']?></td>
	        	<?
	        		}
	        	?>
	        	<?
	        	}
	        	?>
			</tr>

			<tr class="bold-border">
	        	<?
	        	$mulaicolom= 2;
	        	for($col=0; $col<count($arrkolomdata); $col++)
	        	{
	        	?>
	        		<td></td>
	        	<?
	        	}
	        	?>
			</tr>

			<?
			$totalstandar= 0;
			$searchword= "DATA-";
			while($set->nextRow())
			{
			?>
			<tr>
				<?
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					$infodata= "";
					if(preg_match("/\b$searchword\b/i", $aColumns[$i]))
					{
						$fielddata= str_replace($searchword, "", $aColumns[$i]);

						$statementdetil= " AND A.PEGAWAI_ID = ".$set->getField("PEGAWAI_ID")."  AND A.FORMULA_ATRIBUT_ID = ".$fielddata;
						$setdetil= new RekapNewAssesment();
						$setdetil->selectByParamsPenilaianDetilCetakan(array(), -1, -1, $statementdetil, $reqJadwalTesId);
						$setdetil->firstRow();
						// echo $setdetil->query;exit();
			        	$infodata= $setdetil->getField("NILAI");
			        	$totalstandar+= $setdetil->getField("NILAI");
			        	unset($setdetil);
			    	}
			    	elseif(trim($aColumns[$i]) == "NAMA_NIP_BARU")
			    	{
			    		$infodata= $set->getField("NAMA")."/<br/>".$set->getField("NIP_BARU");
			    	}
			    	elseif(trim($aColumns[$i]) == "TOTAL_STANDART")
			    	{
			    		$infodata= $totalstandar;
			    		$totalstandar= 0;
			    	}
			    	elseif(trim($aColumns[$i]) == "NOMOR")
			    	{
			    		$statementdetil= " AND A.PEGAWAI_ID = ".$set->getField("PEGAWAI_ID")." AND JADWAL_AWAL_TES_SIMULASI_ID = ".$reqJadwalTesId;
						$setdetil= new RekapNewAssesment();
						$setdetil->selectByParamsNomorJadwalTes(array(), -1, -1, $statementdetil);
						$setdetil->firstRow();
						// echo $setdetil->query;exit();
			        	$infodata= $setdetil->getField("NOMOR_URUT_GENERATE");
			        	unset($setdetil);
			    	}
			    	// elseif(trim($aColumns[$i]) == "KELEBIHAN")
			    	// {
			    	// 	$infodata= $tempCatatanKekuatan;
			    	// }
			    	// elseif(trim($aColumns[$i]) == "AREA_PENGEMBANGAN")
			    	// {
			    	// 	$infodata= $tempCatatanPengembangan;
			    	// }
			    	else
			    		$infodata= $set->getField(trim($aColumns[$i]));
				?>
					<td style="text-align: center;font-size: 12pt;"><?=$infodata?></td>
				<?
				}
				?>
			</tr>
			<?
			}
			$jumlahdetil = $jumlahdetil+2;
			?>
			<tr>
				<th style="text-align: center;font-size: 14pt;" colspan="3">Kategori</th> 
				<th style="text-align: justify;font-size: 12pt;" colspan="<?=$jumlahdetil?>"><?=$set->getField("NAMA_KUADRAN")?></th> 
			</tr>
			<tr>
				<th style="text-align: center;font-size: 14pt;" colspan="3">Kelebihan</th> 			 
		 
				<th style="text-align: justify;font-size: 12pt;" colspan="<?=$jumlahdetil?>">
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
				<p  style="font-size: 12pt;text-align: justify;margin-left: 1%"><?=$reqinfocatatan?></p>
				<?
				}
				?>	
			</th>
			</tr>
			 <tr>
				<th style="text-align: center;font-size: 14pt;" colspan="3">Saran Pengembangan</th> 				 
				<th style="text-align: justify;font-size: 12pt;" colspan="<?=$jumlahdetil?>"> 

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
				?><p  style="font-size: 12pt;text-align: justify;margin-left: 1%"><?=$reqinfocatatan?></p>
				<?
				}
				?>
				</th>
			</tr>
		</table> 

		<table style="font-size: 10pt; width: 100%; margin: auto">
			<tr>
				<td style="text-align: justify">Berdasarkan profil dan uraian di atas, maka Saudara <?=$tempPegawaiNama?>  berada pada kategori : <?=$HasilKonversi?> 
				<tr>
					<td style="text-align: justify;">Rekomendasi yang disarankan :</td>	
				</tr> 
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
					<tr>
						<td style="text-align: justify;"><?=$reqinfocatatan?></td>	
					</tr>
				<?
				}
				?>
				</td>
		
			</tr>
		</table>

		<pagebreak />
   	 
		<?
		$talentkuadran= "../upload/svg/kuadran_".$reqId."_".$reqJadwalTesId.".svg";
		$talentkompetensi= "../upload/svg/kompetensi_".$reqId."_".$reqJadwalTesId.".svg";
		$talentpotensi= "../upload/svg/potensi_".$reqId."_".$reqJadwalTesId.".svg";
		if(file_exists($talentkuadran) || file_exists($talentkompetensi) || file_exists($talentpotensi))
		{
		?>  
			<p style="font-size: 12pt" ><strong>POSISI QUADRAN TALENT POOL SAAT INI  :</strong></p>		
			<table style="font-size: 10pt; width: 100%; margin: auto">			 
				<?
				if(file_exists($talentkuadran))
				{
				?>
				<tr>
					<td style="font-size: 10pt; text-align: center;">
						Posisi kuadran : <?=$tempKode?>  (<?=$tempNama?>)
						<img src="<?=$talentkuadran?>" style="width: 60%">
					</td>
				</tr>
			</table>			
				<?
				}
				if(file_exists($talentkompetensi))
				{
				?>
			<p style="font-size: 12pt" ><strong>GRAFIK GAMBARAN KOMPETENSI SAAT INI  :</strong></p>	 
			<table style="font-size: 10pt; width: 100%; margin: auto"> 				 
				<tr>					
					<td style="font-size: 10pt; text-align: center;">
						<img src="<?=$talentkompetensi?>" style="width: 60%">
					</td>
				</tr>
			</table>
				<?
				}
				if(file_exists($talentpotensi))
				{
				?> 
			<p style="font-size: 12pt" ><strong>GRAFIK GAMBARAN POTENSI SAAT INI  :</strong></p>	
			<table style="font-size: 10pt; width: 100%; margin: auto">  
				<tr>
					<td style="font-size: 10pt; text-align: center;"> 
						<img src="<?=$talentpotensi?>" style="width: 60%">
					</td>
				</tr>
			</table>
				<?
				}
				?> 
			
		<?
		}
		?>

	<table style="font-size: 10pt; width: 100%; margin: auto ;text-align: right; padding-top: 30px">
			<tr>
				<td align="center"></td>
				<td  style="width: 50%;" align="center">Samarinda, <?=$TanggalNow?></td>
			</tr>
			<tr>
				<td align="center"></td>
				<td align="center">Pimpinan Penyelenggara Penilaian Kompetensi </td>
			</tr>
			<tr>
				<td align="center" style="height: 80px"></td>
				<td align="center"></td>
			</tr> 
			<tr>
				<td align="center"></td>
				<td align="center"><?=$reqTtdPimpinan?></td>
			</tr>
			<tr>
				<td align="center"></td>
				<td align="center"><?=$reqNipPimpinan?></td>
			</tr>
			
		</table>	
	</div>


	
</body>
</html>