<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base/JadwalPegawai.php");

$reqId= httpFilterRequest("reqId");
$reqTahun= httpFilterRequest('reqTahun');

$statement= " AND A.PEGAWAI_ID = ".$reqId." AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'";
$set= new Penilaian();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
$tempInfoEselon= romanicNumber($set->getField("ESELON"));
unset($set);

//set order bayar
$arrDetil="";
$index_detil= 0;
$set= new Penilaian();
$statement= " AND B.PEGAWAI_ID = ".$reqId." AND B.TAHUN = '".$reqTahun."' AND B.ASPEK_ID = '2'";
$set->selectByParamsPenilaianAtributPegawai(array(), -1, -1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDetil[$index_detil]["PENILAIAN_ID"] = $set->getField("PENILAIAN_ID");
	$arrDetil[$index_detil]["ATRIBUT_ID"] = $set->getField("ATRIBUT_ID");
	$arrDetil[$index_detil]["ATRIBUT_NAMA"] = $set->getField("ATRIBUT_NAMA");
	$arrDetil[$index_detil]["ASPEK_NAMA"] = $set->getField("ASPEK_NAMA");
	$arrDetil[$index_detil]["ASPEK_ID"] = $set->getField("ASPEK_ID");
	$arrDetil[$index_detil]["NILAI_STANDAR"] = $set->getField("NILAI_STANDAR");
	$index_detil++;
}
$jumlah_atribut= $index_detil;
//print_r($arrDetil);exit;

$arrPenggalian="";
$index_detil= 0;
$set= new JadwalPegawai();
$statement= " AND A.PEGAWAI_ID = ".$reqId." AND YEAR(C.TANGGAL_TES) = '".$reqTahun."'";
$set->selectByParamsJadwalPegawaiPenggalian(array(), -1, -1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrPenggalian[$index_detil]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrPenggalian[$index_detil]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrPenggalian[$index_detil]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
	$arrPenggalian[$index_detil]["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
	$index_detil++;
}
$jumlah_penggalian= $index_detil;
//print_r($arrPenggalian);exit;

$arrPenggalianDetil="";
$index_detil= 0;
$set= new JadwalPegawai();
$statement= " AND A.PEGAWAI_ID = ".$reqId." AND YEAR(C.TANGGAL_TES) = '".$reqTahun."'";
$set->selectByParamsJadwalPegawaiPenggalianDetil(array(), -1, -1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrPenggalianDetil[$index_detil]["ID"]= $set->getField("PENGGALIAN_ID")."-".$set->getField("ATRIBUT_ID");
	$arrPenggalianDetil[$index_detil]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrPenggalianDetil[$index_detil]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrPenggalianDetil[$index_detil]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
	$arrPenggalianDetil[$index_detil]["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
	$arrPenggalianDetil[$index_detil]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$index_detil++;
}
$jumlah_penggalian_detil= $index_detil;

$tempJumlahColSpan= 1 + $jumlah_penggalian;
?>
<div class="detil">
<div style="height:100px;">&nbsp;</div>
<div style="width:100%; text-align:center; font-weight:600; font-size: 32px;">TABEL KEGIATAN UNTUK MENGUKUR KOMPETENSI (ESELON <?=$tempInfoEselon?>)</div><br />
<table style="width:100%" class="bordertable">
	<thead>
        <tr style="background-color:#C1D5E0">
            <td rowspan="2" class="kananborder txtcenter" style="width:30px">NO</td>
            <td rowspan="2" class="kananborder txtcenter" style="width:100px">AKTIVITAS / KOMPETENSI</td>
            <td colspan="<?=$tempJumlahColSpan?>" class="txtcenter" >METODE PENGAMBILAN DATA</td>
        </tr>
        <tr style="background-color:#C1D5E0">
        	<?php /*?><td style="width:50px" class="txtcenter atasborder bawahborder kananborder"><svg width='20' height='100'><text transform='rotate(90,0,0)'>Standar Level Kompetensi</text></svg></td><?php */?>
            <td style="width:50px" class="txtcenter atasborder bawahborder kananborder">Standar Level Kompetensi</td>
            <?
			for($index_detil=0; $index_detil < $jumlah_penggalian; $index_detil++)
			{
				$tempPenggalianNama= $arrPenggalian[$index_detil]["PENGGALIAN_NAMA"];
				$tempPenggalianKode= $arrPenggalian[$index_detil]["PENGGALIAN_KODE"];
            ?>
            <?php /*?><td style="width:50px" class="txtcenter atasborder bawahborder kananborder"><svg width='20' height='100'><text transform='rotate(90,0,0)'><?=$tempPenggalianNama."<br/>(".$tempPenggalianKode.")"?></text></svg></td><?php */?>
            <td style="width:50px" class="txtcenter atasborder bawahborder kananborder"><?=$tempPenggalianNama."<br/>(".$tempPenggalianKode.")"?></td>
            <?
			}
            ?>
        </tr>
    </thead>
    <tbody>
        <tr>
        	<td colspan="<?=$tempJumlahColSpan+2?>" class="atasborder bawahborder">&nbsp;</td>
        </tr>
        <?
		$nomorGroup= 1;
		$tempAspekKondisi= "";
		for($index_detil=0; $index_detil < $jumlah_atribut; $index_detil++)
		{
			$tempAtributId= $arrDetil[$index_detil]["ATRIBUT_ID"];
			$tempAtributNama= $arrDetil[$index_detil]["ATRIBUT_NAMA"];
			$tempAspekNama= $arrDetil[$index_detil]["ASPEK_NAMA"];
			$tempAspekId= $arrDetil[$index_detil]["ASPEK_ID"];
			$tempNilaiStandar= $arrDetil[$index_detil]["NILAI_STANDAR"];
        ?>
        <?
		if($tempAspekKondisi == $tempAspekNama){}
		else
		{
        ?>
        <tr style="background-color:#DEE0F0">
        	<td style="padding-top:20px; padding-bottom:20px" class="txtcenter atasborder bawahborder"><?=getColoms($nomorGroup)?></td>
			<td class="atasborder bawahborder kiriborder" colspan="<?=$tempJumlahColSpan+1?>"><?=$tempAspekNama?></td>
        </tr>
        <?
		$nomorGroup++;
		$nomor= 1;
		}
        ?>
        <tr>
        	<td style="padding-top:20px; padding-bottom:20px"></td>
        	<td class="kiriborder"><?=$nomor?>. <?=$tempAtributNama?></td>
            <td class="txtcenter kiriborder"><?=$tempNilaiStandar?></td>
            <?
			for($index_penggalian=0; $index_penggalian < $jumlah_penggalian; $index_penggalian++)
			{
				$tempRowId= $arrPenggalian[$index_penggalian]["PENGGALIAN_ID"]."-".$tempAtributId;
				$arrayPenggalianAtribut= '';
				$arrayPenggalianAtribut= in_array_column($tempRowId, "ID", $arrPenggalianDetil);
				if($arrayPenggalianAtribut== '')
				{
            ?>
            <td class="txtcenter kiriborder">-</td>
            <?
				}
				else
				{
            ?>
            <td class="txtcenter kiriborder">v</td>
            <?
				}
            ?>
            <?
			}
            ?>
        </tr>
        <?
		$nomor++;
		$tempAspekKondisi= $tempAspekNama;
		}
        ?>
        <tr>
        	<td colspan="<?=$tempJumlahColSpan+2?>" class="atasborder bawahborder">&nbsp;</td>
        </tr>
    </tbody>
</table>
</div>