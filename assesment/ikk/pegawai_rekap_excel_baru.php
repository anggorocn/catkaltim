<style>
	body, table{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif
	}
	th {
		text-align:center;
		font-weight: bold;
	}
	td {
		vertical-align: top;
  		text-align: left;
	}
	.str{
	  mso-number-format:"\@";/*force text*/
	}
</style>

<?php 
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/RekapAssesment.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=pegawai_rekap_excel.xls");

$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqKeterangan = httpFilterRequest("reqKeterangan");
$reqId = httpFilterRequest("reqId");
$reqCari = httpFilterRequest("reqCari");
$reqSearch = httpFilterGet("reqSearch");

$arrkolomdata1=array();
$arrkolomdata2=array();

$totalstandart= 0;
if(!empty($reqJadwalTesId))
{
	$jumlahdetil= 0;
	$setdetil= new RekapAssesment();
	$setdetil->selectByParamsFormula(array(), -1, -1, '', $reqJadwalTesId);
	// echo $setdetil->query;exit();
	$cekAtribut='';
	while ($setdetil->nextRow()) 
	{
		// array_push($arrkolomdata,     
		// 	array("label"=>$setdetil->getField("ATRIBUT_NAMA")."<br/>".$setdetil->getField("NILAI_STANDAR"), "width"=>"100px")
		// );
		if($cekAtribut!=$setdetil->getField("ATRIBUT_NAMA")){
			if($setdetil->getField("aspek_id")==1){
				array_push($arrkolomdata1,     
					array("label"=>$setdetil->getField("ATRIBUT_NAMA"), "width"=>"100px", "aspek"=>$setdetil->getField("aspek_id"), "atribut"=>$setdetil->getField("atribut_id"))
				);
			}
			else{
				array_push($arrkolomdata2,     
					array("label"=>$setdetil->getField("ATRIBUT_NAMA"), "width"=>"100px", "aspek"=>$setdetil->getField("aspek_id"), "atribut"=>$setdetil->getField("atribut_id"))
				);
			}
			$cekAtribut=$setdetil->getField("ATRIBUT_NAMA");
		}
		$totalstandart += $setdetil->getField("NILAI_STANDAR");
		$jumlahdetil++;
	}
}

$arrPenilaian=array();
$setPenilaian= new RekapAssesment();
$setPenilaian->selectByParamsPenilaianNew(array(), -1, -1, '', $reqJadwalTesId);
// echo $setPenilaian->query; exit;
while ($setPenilaian->nextRow()) 
{
	array_push($arrPenilaian,     
		array(
			"pegawaiid"=>$setPenilaian->getField("pegawai_id"),
			"aspekid"=>$setPenilaian->getField("aspek_id"),
			"atributid"=>$setPenilaian->getField("atribut_id"),
			"nilai"=>$setPenilaian->getField("nilai"),
			"gap"=>$setPenilaian->getField("gap")
		)
	);
}
// print_r($arrPenilaian); exit;

?>
<table border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr></tr>
		<tr>
			<td colspan=<?=(count($arrkolomdata2)*4)+12?> style="border:none">
				<center>
					<img src="https://simace.kaltimbkd.info/assesment/WEB/images/logo-judul.png" >
					PEMERINTAH PROVINSI KALIMANTAN TIMUR<br>
					<b>BADAN KEPEGAWAIAN DAERAH<br>
					UPTD. PENILAIAN KOMPETENSI PEGAWAI</b>
				</center>
			</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<td colspan=<?=(count($arrkolomdata2)*4)+12?> style="border:none"><center><b>Rekapitulasi Data</b></center></td>
		</tr>
		<tr>
			<td colspan=<?=(count($arrkolomdata2)*4)+12?> style="border:none"><center><b>HASIL PENILAIAN POTENSI DAN KOMPETENSI</b></center></td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<th colspan="2" style="border:none">Nama Ujian</th>
		</tr>
		<tr>
			<th colspan="2" style="border:none">Tanggal Ujian</th>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<th rowspan="2" style='background-color: lightyellow;'>No</th>
			<th rowspan="2" style="width:200px;background-color: lightyellow;"><center>Nama</center></th>
			<th rowspan="2" style="width:200px;background-color: lightyellow;"><center>NIP</center></th>
			<th rowspan="2" style="background-color: lightyellow;"><center>Jabatan</center></th>
			<th colspan="<?=count($arrkolomdata2)?>" style='background-color: skyblue;'><center>Kompetensi</center></th>
			<th rowspan="2" style='background-color: skyblue;'><center>JPM Kompetensi</center></th>
			<th rowspan="2" style='background-color: skyblue;'><center>Rekom Kompetensi</center></th>
			<!-- <th rowspan="2">Keterangan</th> -->
			<th colspan="<?=count($arrkolomdata2)?>" style='background-color: skyblue;'><center>Gap Kompetensi</center></th>
			<?if (count($arrkolomdata1)!=0){?>
			<th colspan="<?=count($arrkolomdata1)?>" style='background-color: orange;'><center>Potensi</center></th>
			<th rowspan="2" style='background-color: orange;'><center>JPM Potensi</center></th>
			<th rowspan="2" style='background-color: orange;'><center>Rekom Potensi</center></th>
			<th colspan="<?=count($arrkolomdata1)?>" style='background-color: orange;'><center>Gap Potensi</center></th>
			<?}?>
			<td rowspan="2"><center>Kekuatan</center></td>
			<td rowspan="2"><center>Area Pengebangan</center></td>
			<td rowspan="2"><center>Saran Pengembangan</center></td>
			<td rowspan="2"><center>Nine Box<center></td>
		</tr>
		<tr>
			<?
			for ($i=0; $i<count($arrkolomdata2); $i++){?>
				<th style='width:100px;background-color: skyblue;'><center><?=$arrkolomdata2[$i]['label']?></center></th>
			<?}?>
			<?
			for ($i=0; $i<count($arrkolomdata2); $i++){?>
				<th style='width:100px;background-color: skyblue;'><center><?=$arrkolomdata2[$i]['label']?></center></th>
			<?}?>
			<?
			for ($i=0; $i<count($arrkolomdata1); $i++){?>
				<th style="width:100px;background-color: orange;"><center><?=$arrkolomdata1[$i]['label']?></center></th>
			<?}?>
			<?
			for ($i=0; $i<count($arrkolomdata1); $i++){?>
				<th style="width:100px;background-color: orange;"><center><?=$arrkolomdata1[$i]['label']?></center></th>
			<?}?>
		</tr>
	</thead>
	<tbody>
		<?
		$Identitas= new RekapAssesment();
		$Identitas->selectByParamsNewRekap(array(), -1, -1, '', $reqJadwalTesId);
		// echo $Identitas->query;exit();
		$cekAtribut='';
		$nomor=1;
		while ($Identitas->nextRow()) 
		{
			$pegawai_id=$Identitas->getField("pegawai_id");
		?>
		<tr>
			<td><?=$nomor?></td>
			<td><?=$Identitas->getField("NAMA")?></td>
			<td>'<?=$Identitas->getField("NIP_BARU")?></td>
			<td><?=$Identitas->getField("JABATAN_NAMA")?></td>
			<?
			$countNilai=0;
			for ($i=0; $i<count($arrkolomdata2); $i++){
				$keys1 = array_keys(array_column($arrPenilaian, 'atributid'), $arrkolomdata2[$i]['atribut']);
				$keys2 = array_keys(array_column($arrPenilaian, 'pegawaiid'), $pegawai_id);
				$val=array_intersect($keys1,$keys2);
				$val=max($val);
				$countNilai=$arrPenilaian[$val]['nilai']+$countNilai;
				?>
				<td><center><?=$arrPenilaian[$val]['nilai']?></center></td>
			<?}
			$statement= "  AND c.formula_id = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$pegawai_id; 
					$statementgroup= ""; 
					$jpm=0;
					$arrPenilaianAtributJPM="";
					$setJPM= new CetakanPdf();
					$setJPM->selectByParamsPenilaianJpmAkhir(array(), -1,-1, $statement, $statementgroup);
					 $setJPM->firstRow(); 
					$jpm = $setJPM->getField("KOMPETEN_JPM");


					//perhitungan
					if ($jpm >= 90)
						$HasilKonversi = 'O = Optimal.';
					elseif ($jpm >= 78 && $jpm < 90)
						$HasilKonversi = 'CO = Cukup Optimal.';
					elseif ($jpm < 78)
						$HasilKonversi = 'KO = Kurang Optimal.';
					else
						$HasilKonversi = '-'; 
					?>

					<td><?=$jpm?></td>
					<td><?=$HasilKonversi?></td>
			<?
			for ($i=0; $i<count($arrkolomdata2); $i++){
				$keys1 = array_keys(array_column($arrPenilaian, 'atributid'), $arrkolomdata2[$i]['atribut']);
				$keys2 = array_keys(array_column($arrPenilaian, 'pegawaiid'), $pegawai_id);
				$val=array_intersect($keys1,$keys2);
				$val=max($val);
				?>
				<td><center><?=$arrPenilaian[$val]['gap']?></center></td>
			<?}
			if (count($arrkolomdata1)!=0){
				$countNilai=0;
				for ($i=0; $i<count($arrkolomdata1); $i++){
					$keys1 = array_keys(array_column($arrPenilaian, 'atributid'), $arrkolomdata1[$i]['atribut']);
					$keys2 = array_keys(array_column($arrPenilaian, 'pegawaiid'), $pegawai_id);
					$val=array_intersect($keys1,$keys2);
					$val=max($val);
					$countNilai=$arrPenilaian[$val]['nilai']+$countNilai;
					?>
					<td><center><?=$arrPenilaian[$val]['nilai']?></center></td>
				<?}
					$statement= "  AND c.formula_id = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$pegawai_id; 
					$statementgroup= ""; 
					$jpm=0;
					$arrPenilaianAtributJPM="";
					$setJPM= new CetakanPdf();
					$setJPM->selectByParamsPenilaianJpmAkhir(array(), -1,-1, $statement, $statementgroup);
					// echo $setJPM->query;exit;
					 $setJPM->firstRow(); 
					$jpm = $setJPM->getField("PSIKOLOGI_JPM");

					//perhitungan
					if ($jpm >= 90)
						$HasilKonversi = 'O = Optimal.';
					elseif ($jpm >= 78 && $jpm < 90)
						$HasilKonversi = 'CO = Cukup Optimal.';
					elseif ($jpm < 78)
						$HasilKonversi = 'KO = Kurang Optimal.';
					else
						$HasilKonversi = '-'; 
					?>

					<td><?=$jpm?></td>
					<td><?=$HasilKonversi?></td>
				<?
				for ($i=0; $i<count($arrkolomdata1); $i++){
					$keys1 = array_keys(array_column($arrPenilaian, 'atributid'), $arrkolomdata1[$i]['atribut']);
					$keys2 = array_keys(array_column($arrPenilaian, 'pegawaiid'), $pegawai_id);
					$val=array_intersect($keys1,$keys2);
					$val=max($val);
					?>
					<td><center><?=$arrPenilaian[$val]['gap']?></center></td>
				<?}
			}?>
			<td>
				<?
				$index_catatan= 0;
				$arrPotensiStrength=array();
				$set_catatan= new RekapAssesment();
				$statement_catatan= " AND A.TIPE = 'profil_kekuatan' AND A.PEGAWAI_ID = ".$pegawai_id." AND c.formula_id = ".$reqJadwalTesId;
				$set_catatan->selectByParamsPenilaianHasil(array(), -1,-1, $statement_catatan);
				// echo $set_catatan->query;
				$reqinfocatatan='';
				while($set_catatan->nextRow())
				{
				  $arrPotensiStrength[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
				  $arrPotensiStrength[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
				  $index_catatan++;
				}
				$jumlahPotensiStrength= $index_catatan;

				for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
				{
					$reqinfocatatan=$reqinfocatatan." ".str_replace("<br>",".",$arrPotensiStrength[$index_catatan]["KETERANGAN"]);
				?>
				<?
				}
				?>
				<?=str_replace("<br>",".",$reqinfocatatan);?>
			</td>
			<td>
				<?
				$index_catatan= 0;
				$arrPotensiStrength=array();
				$set_catatan= new RekapAssesment();
				$statement_catatan= " AND A.TIPE = 'area_pengembangan' AND A.PEGAWAI_ID = ".$pegawai_id." AND c.formula_id = ".$reqJadwalTesId;
				$set_catatan->selectByParamsPenilaianHasil(array(), -1,-1, $statement_catatan);
				// echo $set_catatan->query;exit;
				$reqinfocatatan='';
				while($set_catatan->nextRow())
				{
				  $arrPotensiStrength[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
				  $arrPotensiStrength[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
				  $index_catatan++;
				}
				$jumlahPotensiStrength= $index_catatan;

				for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
				{
					$reqinfocatatan=$reqinfocatatan." ".str_replace("<br>",".",$arrPotensiStrength[$index_catatan]["KETERANGAN"]);
				?>
				<?
				}
				?>
				<?=str_replace("<br>",".",$reqinfocatatan);?>
			</td>
			<td>
				<?
				$index_catatan= 0;
				$arrPotensiStrength=array();
				$set_catatan= new RekapAssesment();
				$statement_catatan= " AND A.TIPE = 'profil_saran_pengembangan' AND A.PEGAWAI_ID = ".$pegawai_id." AND c.formula_id = ".$reqJadwalTesId;
				$set_catatan->selectByParamsPenilaianHasil(array(), -1,-1, $statement_catatan);
				$reqinfocatatan='';
				// echo $set_catatan->query;exit;
				while($set_catatan->nextRow())
				{
				  $arrPotensiStrength[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
				  $arrPotensiStrength[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
				  $index_catatan++;
				}
				$jumlahPotensiStrength= $index_catatan;

				for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
				{
					$reqinfocatatan=$reqinfocatatan." ".str_replace("<br>",".",$arrPotensiStrength[$index_catatan]["KETERANGAN"]);
				?>
				<?
				}
				?>
				<?=str_replace("<br>",".",$reqinfocatatan);?>

			</td>
			<td>
				<?				
				$setjpm= new RekapAssesment();
				$setjpm->selectByParamsPenilaianjpm(array(), -1, -1, 'and aspek_id = 1 and pegawai_id='. $pegawai_id, $reqJadwalTesId);
				$setjpm->firstRow();
				$jpm_potensi=$setjpm->getField("KUADRAN_PEGAWAI");

				$setjpm= new RekapAssesment();
				$setjpm->selectByParamsPenilaianjpm(array(), -1, -1, 'and aspek_id = 2 and pegawai_id='. $pegawai_id, $reqJadwalTesId);
				$setjpm->firstRow();
				$jpm_kompetensi=$setjpm->getField("KUADRAN_PEGAWAI");
				
				$jpm=$jpm_potensi.$jpm_kompetensi;
				
				if($jpm==11){
					$kuadran=1;
					$kuadran_penjelasan='Tingkatkan Kompetensi';
				}
				else if($jpm==12){
					$kuadran=2;
					$kuadran_penjelasan='Tingkatkan Peran Saat Ini';
				}
				else if($jpm==21){
					$kuadran=3;
					$kuadran_penjelasan='Tingkatkan Peran Saat Ini';
				}
				else if($jpm==13){
					$kuadran=4;
					$kuadran_penjelasan='Tingkatkan Peran Saat Ini';
				}
				else if($jpm==22){
					$kuadran=5;
					$kuadran_penjelasan='Siap Untuk Peran Masa Depan Dengan Pengembangan';
				}
				else if($jpm==31){
					$kuadran=6;
					$kuadran_penjelasan='Pertimbangkan (Mutasi)';
				}
				else if($jpm==23){
					$kuadran=7;
					$kuadran_penjelasan='Siap Untuk Peran Masa Depan Dengan Pengembangan';
				}
				else if($jpm==32){
					$kuadran=8;
					$kuadran_penjelasan='Siap Untuk Peran Masa Depan Dengan Pengembangan';
				}
				else if($jpm==33){
					$kuadran=9;
					$kuadran_penjelasan='Siap Untuk Peran Di Masa Depan';
				}	
				?>
				Kuadran <?=$kuadran?> (<?=$kuadran_penjelasan?>)
			</td>
		</tr>
		<?
		$nomor++;
	}?>
	</tbody>
</table>
</body>
</html>