<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/classes/base/FormulaAssesmentUjianTahap.php");
include_once("../WEB/classes/base-ikk/PenilaianRekomendasi.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");


$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

$set= new JadwalPegawai();
$set->selectByParamsLookupJadwalPegawai(array(), -1, -1, $statement, $reqJadwalTesId);
$set->firstRow();
// echo $set->query;exit;
$tempPesertaNomorUrut= $set->getField("NOMOR_URUT_GENERATE");
$tempBulanAngka = getMonth($set->getField("TANGGAL_TES"));
$tempTahun = getYear($set->getField("TANGGAL_TES"));
// echo $tempPesertaNomorUrut; exit;
$tempBulanRomawi = getRomawiMonth((int)$tempBulanAngka); 

$statement= " AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new CetakanPdf();
$set->selectByParamsJadwalFormula($statement);
$set->firstRow();
$reqFormulaId= $set->getField("FORMULA_ID");
// echo $reqFormulaId;exit();

//penggalian
$statement= " 
AND B.PARENT_ID = '0'
AND B.KATEGORI = '1'
AND EXISTS
(
	SELECT 1
	FROM
	(
	SELECT B.FORMULA_ID
	FROM jadwal_tes A
	INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
	WHERE A.JADWAL_TES_ID = ".$reqJadwalTesId."
	) X WHERE A.FORMULA_ASSESMENT_ID = X.FORMULA_ID
)";
$infoformula= "";
$set= new FormulaAssesmentUjianTahap();
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	if(empty($infoformula))
		$infoformula= $set->getField("TIPE");
	else
		$infoformula.= "<br/>".$set->getField("TIPE");
}
// echo $infoformula;exit();

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

$statement= "  AND TO_CHAR(P.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND P.PEGAWAI_ID = ".$reqId;
$statementgroup= "  AND A.ASPEK_ID = '1'";
$index_loop= 0;
$arrAtribut="";
$sOrder='ORDER BY ur.urut ASC, atribut_id ASC';
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
	$arrAtribut[$index_loop]["CATATAN"]= $set->getField("CATATAN");
	$index_loop++;
}
$jumlah_atribut= $index_loop;

// echo "Asdas";
// echo $jumlah_atribut;exit;

$statementgroup= "";
$statement= "  AND TO_CHAR(P.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND P.PEGAWAI_ID = ".$reqId;
$index_loop= 0;
$arrPenilaianAtribut="";
$set= new CetakanPdf();
$set->selectByParamsAtributPegawaiPotensiPenilaian(array(), -1,-1, $statement, $statementgroup);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrPenilaianAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrPenilaianAtribut[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
	$arrPenilaianAtribut[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
	$arrPenilaianAtribut[$index_loop]["ATRIBUT_BOBOT"]= $set->getField("ATRIBUT_BOBOT");
	$arrPenilaianAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrPenilaianAtribut[$index_loop]["STANDAR_RATING"]= $set->getField("STANDAR_RATING");
	$arrPenilaianAtribut[$index_loop]["STANDAR_SKOR"]= $set->getField("STANDAR_SKOR");
	$arrPenilaianAtribut[$index_loop]["INDIVIDU_RATING"]= round($set->getField("INDIVIDU_RATING"),2);
	$arrPenilaianAtribut[$index_loop]["INDIVIDU_SKOR"]= round($set->getField("INDIVIDU_SKOR"),2);
	$arrPenilaianAtribut[$index_loop]["GAP"]= round($set->getField("GAP"),2);
	$index_loop++;
}
$jumlah_penilaian_atribut= $index_loop;

$set= new JadwalPegawaiDetil();
$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsPenilaianAsesor($statement);
// echo $set->query;exit;
$set->firstRow();
$tempProfilKepribadian= $set->getField("PROFIL_KEPRIBADIAN");
$tempKesesuaian= $set->getField("KESESUAIAN_RUMPUN");

$index_catatan= 0;
$arrPenilaianPotensiProfilKepribadian=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kepribadian' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiProfilKepribadian[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPenilaianPotensiProfilKepribadian[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahPenilaianPotensiProfilKepribadian= $index_catatan;




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
		<p style="font-size: 14pt" ><strong>LAMPIRAN DATA PENDUKUNG  LAPORAN ASSESSOR</strong></p>
		<p style="font-size: 14pt" align="center"><strong>HASIL PENILAIAN POTENSI</strong></p>
		<p style="font-size: 14pt" ><strong>I. IDENTITAS PRIBADI</strong></p>	
			<table style="font-size: 10pt">
				<tr style="border: 3px">
					<td>Nomor</td>
					<td style="width:20px">:</td>
					<td><?=$tempPesertaNomorUrut.'/UPTD/'.$tempBulanRomawi.'/'.$tempTahun?></td>
					<td></td>
					<td>Jabatan</td>
					<td style="width:20px">:</td>
					<td><?=$tempJabatanTes?></td>
				</tr>
				<tr style="border: 3px">
					<td>Nama</td>
					<td style="width:20px">:</td>
					<td><?=$tempPegawaiNama?></td>
					<td></td>
					<td>Unit Kerja</td>
					<td style="width:20px">:</td>
					<td><?=$tempUnitKerjaSaatIni?></td>
				</tr>
				<tr style="border: 3px">
					<td>NIP.</td>
					<td style="width:20px">:</td>
					<td><?=$tempPegawaiNip?></td>
				</tr> 
			</table>	

		<!-- <div class="footer">
			<p>Pusat Penilaian Kementerian Dalam Negeri</p>
		</div> -->
		<p style="font-size: 14pt" ><strong>II.	PROFIL POTENSI</strong></p>	
		<table style="font-size: 10pt; border-collapse: collapse;" class="bold-border table-border center" width="100%">
			<tr style="background: #febde6">
				<th rowspan="2">No</th>
				<th rowspan="2">Aspek Potensi</th>
				<th colspan="5">Skala Penilaian</th>
				<th rowspan="2" style="width: 200px">Alat Tes Yang Digunakan</th>
			</tr>
			<tr style="background: #febde6">
				<th style="width: 30px">KS</th>
				<th style="width: 30px">K</th>
				<th style="width: 30px">C</th>
				<th style="width: 30px">B</th>
				<th style="width: 30px">BS</th>
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
				$tempAtributKesimpulan= $HasilTools;
				
				// echo $tempAtributKesimpulan;

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
                ?>
                    <tr class="bold-border">
                        <td><b><?=romanicNumber($tempNoAtributParent)?></b></td>
                        <!-- tambahan info ujian -->
                        <td colspan="6" class="left"><b><?=$tempAtributNama?></b></td>
                        <?
                        if($tempNoAtributParent == "1")
                        {
                        	$inforowformula= $jumlah_atribut;
                        ?>
                        <td rowspan="<?=$inforowformula?>" style="vertical-align: middle;"><?=$infoformula?></td>
                        <?
                        }
                        ?>
                    </tr>
                <?
					}
				}
				else
				{
					$arrChecked= radioPenilaian($tempAtributNilai, "√");
					$arrStyleClassChecked= radioPenilaian($tempAtributNilaiStandar-1, "grey");

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
				<td style="width:100px">KS</td>
				<td style="width:50px">:</td>
				<td>Kurang Sekali</td>
			</tr>
			<tr>
				<td style="width:100px">K</td>
				<td style="width:50px">:</td>
				<td>Kurang</td>
			</tr>
			<tr>
				<td style="width:100px">C</td>
				<td style="width:50px">:</td>
				<td>Cukup</b></td>
			</tr>
			<tr>
				<td style="width:100px">B</td>
				<td style="width:50px">:</td>
				<td>Baik</b></td>
			</tr>
			<tr>
				<td style="width:100px">BS</td>
				<td style="width:50px">:</td>
				<td>Baik Sekali</b></td>
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

		<pagebreak />
		<p style="font-size: 14pt" ><strong>III. DESKRIPSI ASPEK POTENSI</strong></p>

		<!-- $arrAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
		$arrAtribut[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
		$arrAtribut[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
		$arrAtribut[$index_loop]["ASPEK_NAMA"]= $set->getField("ASPEK_NAMA");
		$arrAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
		$arrAtribut[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
		$arrAtribut[$index_loop]["NILAI"]= $set->getField("NILAI");
		$arrAtribut[$index_loop]["KESIMPULAN"]= $set->getField("KESIMPULAN");
		$arrAtribut[$index_loop]["CATATAN"]= $set->getField("CATATAN"); -->
		<?
		$nomorparent= 1;
		$parentid= "";
		$arrinfoskalapenilaian= infoskalapenilaian();
		for($i=0; $i<$jumlah_atribut; $i++)
		{
			$infodataparentid= $arrAtribut[$i]["ATRIBUT_ID_PARENT"];
			$infodataatributnama= $arrAtribut[$i]["ATRIBUT_NAMA"];
			$infodatanilai= $arrAtribut[$i]["NILAI"];
			$infodatacatatan= $arrAtribut[$i]["CATATAN"];

			if(empty($infodatanilai))
				$infodatanilai= "-";
			else
			{
				$infodatanilai= $arrinfoskalapenilaian[$infodatanilai]["nama"];
			}

			$arrChecked= radioPenilaian($tempAtributNilai, "√");
			if($infodataparentid == "0")
			{
				if($nomorparent > 1)
					echo "<br/>";
		?>
			<?=$nomorparent?>.	<?=$infodataatributnama?><br>			 
		<?
			$nomorparent++;
			$nomorchild= 0;
			}
			else
			{
		?>
			<br>
			<!-- start loop -->
			<?=strtolower(toAlpha($nomorchild))?>.	<?=$infodataatributnama?> : <?=$infodatanilai?>
			<table  style="font-size: 10pt; border-collapse: collapse;" class="bold-border table-border" width="100%">
				<tr>
					<td  style="font-size: 10pt; text-align: justify"><?=rscript($infodatacatatan)?> <br></td>
				</tr>
			</table>
			<!-- end loop -->
		<?
			$nomorchild++;
			}
		?>
		<?
			$parentid= $arrAtribut[$i]["ATRIBUT_ID_PARENT"];
		}
		?>

		<!-- 1.	Kapabilitas Berpikir<br>
		Dari hasil tes psikologi bahwa <?=$tempPegawaiNama?> rata-rata  memiliki kapabilitas berpikir …..(keterangan) dengan penjelasan sebagai berikut :
		<br>
		<br> -->
		<!-- start loop -->
		<br>
		<br>
		<?=$nomorparent?>. PROFIL KEPRIBADIAN<br> 
		<br>
		<table  style="font-size: 10pt; border-collapse: collapse;" class="bold-border table-border" width="100%">
			<tr>
				<td style="font-size: 10pt; text-align: justify;">
				<? 
				for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiProfilKepribadian; $index_catatan++)
				{
					$reqinfocatatan= $arrPenilaianPotensiProfilKepribadian[$index_catatan]["KETERANGAN"];
					$reqinfourut= $arrPenilaianPotensiProfilKepribadian[$index_catatan]["NO_URUT"];
					
					if($jumlahPenilaianPotensiProfilKepribadian == 1)
					{
						$reqinfourut= "";
					}
					else
					{
						$reqinfourut= $reqinfourut.". ";
					}

					?>
					<?=$reqinfocatatan?>
					<br>
					<?
				}
				?>
				<!-- <?=$tempProfilKepribadian?> -->
				</td>

			</tr>
		</table>
<!-- 
		<br>
		<?=$nomorparent+1?>. Kesesuaian Rumpun Pekerjaan<br>
		<br>
		<br>
		<table  style="font-size: 10pt; border-collapse: collapse;" class="bold-border table-border" width="100%">
			<tr>
				<td><?=$tempKesesuaian?> <br><br><br><br><br><br></td>
			</tr>
		</table> -->
		<!-- end loop -->
	</div>
</body>
</html>