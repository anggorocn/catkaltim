<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/classes/base/JadwalPegawai.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

$statement= " AND A.PEGAWAI_ID = ".$reqId;
$statement.= " AND EXISTS (SELECT 1 FROM jadwal_tes_simulasi_pegawai X WHERE 1=1 AND X.JADWAL_TES_ID = ".$reqJadwalTesId." AND X.PEGAWAI_ID = A.PEGAWAI_ID)";

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

$set= new CetakanPdf();
$statement= " AND A.PEGAWAI_ID= ".$reqId." AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND A.JADWAL_TES_ID = ".$reqJadwalTesId."";
$set->selectByParamsPenilaian(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDate($set->getField("TANGGAL_TES"));
$tempSatkerTes= $set->getField("SATKER_TES");
$tempTempatTes= $set->getField("TEMPAT");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempAspekNama= strtoupper($set->getField("ASPEK_NAMA"));
$tempAspekId= strtoupper($set->getField("ASPEK_ID"));
$tempTanggalTes2= dateToPageCheck($set->getField("TANGGAL_TES"));
$tempTipeTes= $set->getField("TIPE");
$tempTipeFormula= $set->getField("FORMULA");

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
 //echo $set->query;exit;
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

		<div class="center">
			<img src="../WEB/images/logo-nobg.png" style="width: 80px; margin-right: 500px">  
		</div>

		<div style="text-align: center; width: 170px;margin-left: 400px">
			<p style="padding: 5px; color: red; border: 2px solid red;">UNTUK ASSESOR</p>
		</div>
		
		<!-- <p ><strong>Laporan Individual Hasil Penilaian Kompetensi<br><?=$tempTipeFormula?></strong></p>  -->
		<div class="center">

			<p ><strong>Hasil Penilaian Kompetensi 
				<br><?=$tempTipeTes?>
				<br><?=$tempTempatTes?>
				<br>Tanggal penyelenggaraan <?=$tempTanggalTes?></strong></p>
		</div> 
		<br>
		<table width="100%">
			<tr>
				<td width="5%"><strong>I.</strong></td>
				<td><strong>IDENTITAS PRIBADI</strong></td>
			</tr>
		</table>
			<table style="border-color: white;">
				<tr>
					<td>Nomor</td>
					<td style="width:20px">:</td>
					<td><?=$tempPesertaNomorUrut.'/UPTD/'.$tempBulanRomawi.'/'.$tempTahun?></td>
				</tr>
				<tr>
					<td>Jabatan</td>
					<td style="width:20px">:</td>
					<td><?=$tempJabatanTes?></td>
				</tr>
				<tr>
					<td>Nama</td>
					<td style="width:20px">:</td>
					<td><?=$tempPegawaiNama?></td>
				</tr>
				<tr>
					<td>Unit Kerja</td>
					<td style="width:20px">:</td>
					<td><?=$tempUnitKerjaSaatIni?></td>
				</tr>
				<tr>
					<td>NIP.</td>
					<td style="width:20px">:</td>
					<td><?=$tempPegawaiNip?></td>
				</tr> 
			</table>	

		<!-- <div class="footer">
			<p>Pusat Penilaian Kementerian Dalam Negeri</p>
		</div> -->
		<br>
		<table width="100%">
			<tr>
				<td width="5%"><strong>II.</strong></td>
				<td><strong>PROFIL POTENSI</strong></td>
			</tr>
		</table>
		<table style=" border-collapse: collapse;" class="bold-border table-border center" width="100%">
			<tr style="background: #febde6">
				<th rowspan="2">NO</th>
				<th rowspan="2">ASPEK POTENSI</th>
				<th colspan="5">SKALA PENILAIAN</th>
				<th rowspan="2" style="width: 200px">URAIAN POTENSI</th>
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
				$tempAtributKesimpulan= $arrAtribut[$index_loop]["CATATAN"];
				
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
                        <td colspan="7" class="left"><b><?=$tempAtributNama?></b></td>
                    </tr>
                <?
					}
				}
				else
				{
					$arrChecked= radioPenilaian($tempAtributNilai, "√");
					$arrStyleClassChecked= radioPenilaian($tempAtributNilaiStandar, "grey");

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
                        <td class="left" style=" text-align: justify"><?=rscript($tempAtributKesimpulan)?></td>
                        <!-- <td style="<?=$csswarnaketerangan?>"><?=$tempAtributKesimpulan?></td> -->
                        <!-- FF69B4, FF0000, FFFF00, ADFF2F, 87CEFA -->
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
		Keterangan : 
		<table style=" border-collapse: collapse;width: 100%; margin: auto">
			<tr>
				<td></td>				
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
		</table>	
	</div>
</body>
</html>