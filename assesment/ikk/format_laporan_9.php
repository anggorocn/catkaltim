<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");

$statement= " AND A.PEGAWAI_ID= ".$reqId;
$set = new Penilaian();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDate($set->getField("TANGGAL_TES"));
$tempSatkerTes= $set->getField("SATKER_TES");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempAspekNama= strtoupper($set->getField("ASPEK_NAMA"));
$tempAspekId= strtoupper($set->getField("ASPEK_ID"));

$statement= " AND A.PEGAWAI_ID= ".$reqId;
$set= new Kelautan();
$set->selectByParamsMonitoringPegawai(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempPegawaiNama= $set->getField("NAMA");
$tempPegawaiNip= $set->getField("NIP_BARU");
$tempPegawaiJabatanSaatIni= $set->getField("NAMA_JAB_STRUKTURAL");
$tempPegawaiGol= $set->getField("NAMA_GOL");
$tempUnitKerjaSaatIni= $set->getField("SATKER");
unset($set);

$arrDetil="";
$index_detil= 0;
$set= new Penilaian();
$statement= " AND B.PEGAWAI_ID = ".$reqId." AND year(p.TANGGAL_TES) = '".$reqTahun."'";
$set->selectByParamsPenilaianAtributPegawaiHasil(array(), -1, -1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDetil[$index_detil]["PENILAIAN_ID"] = $set->getField("PENILAIAN_ID");
	$arrDetil[$index_detil]["ATRIBUT_ID"] = $set->getField("ATRIBUT_ID");
	$arrDetil[$index_detil]["ATRIBUT_NAMA"] = $set->getField("ATRIBUT_NAMA");
	$arrDetil[$index_detil]["ASPEK_NAMA"] = $set->getField("ASPEK_NAMA");
	$arrDetil[$index_detil]["ASPEK_ID"] = $set->getField("ASPEK_ID");
	$arrDetil[$index_detil]["NILAI_STANDAR"] = $set->getField("NILAI_STANDAR");
	$arrDetil[$index_detil]["NILAI"] = $set->getField("NILAI");
	$arrDetil[$index_detil]["GAP"] = $set->getField("GAP");
	$arrDetil[$index_detil]["KETERANGAN"] = $set->getField("KETERANGAN");
	$index_detil++;
}
$jumlah_atribut= $index_detil;
//print_r($arrDetil);exit;

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
        <td width="9.375%"></td>
        <td width="5.208%"></td>
        <td width="1.042%"></td>
        <td width="6.250%"></td>
        <td width="5.208%"></td>
        <td width="4.167%"></td>
        <td width="2.083%"></td>
        <td width="5.208%"></td>
        <td width="1.042%"></td>
        <td width="6.250%"></td>
        <td width="11.458%"></td>
        <td width="1.042%"></td>
        <td width="2.083%"></td>
        <td width="10.417%"></td>
        <td width="17.708%"></td>
        <td width="4.167%"></td>
        <td width="7.292%"></td>
    </tr>
    <tr valign="top">
        <td height="8px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="10">&nbsp;</td>
        <td colspan="3" nowrap="true">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
                <td></td>
            </tr>
            </table>
        </td>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="8px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td >&nbsp;</td>
        <td colspan="15" nowrap="true">
            <table width="640px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="ArialMT" color="#000000" size="6"><b>&nbsp;</b></font></span></td>
            </tr>
            </table>
        </td>
        <td >&nbsp;</td>
    </tr>
    <tr valign="top">
        <td >&nbsp;</td>
        <td colspan="15" nowrap="true">
            <table width="640px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="ArialMT" color="#000000" size="6"><b>RINGKASAN HASIL ASSESSMEN</b></font></span></td>
            </tr>
            </table>
        </td>
        <td >&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="21px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="24px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="4" nowrap="true">
            <table width="128px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">NIP</font></span></td>
            </tr>
            </table>
        </td>
        <td nowrap="true">
            <table width="16px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="Arial" color="#000000" size="1">:</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="8" nowrap="true">
            <table width="424px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="2"><?=$tempPegawaiNip?></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="5px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="4" nowrap="true">
            <table width="128px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">Nama</font></span></td>
            </tr>
            </table>
        </td>
        <td nowrap="true">
            <table width="16px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="Arial" color="#000000" size="1">:</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="8" nowrap="true">
            <table width="424px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="2"><?=$tempPegawaiNama?></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="5px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="4" nowrap="true">
            <table width="128px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">Jabatan Saat Ini</font></span></td>
            </tr>
            </table>
        </td>
        <td nowrap="true">
            <table width="16px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="Arial" color="#000000" size="1">:</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="8" nowrap="true">
            <table width="424px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="2"><?=$tempJabatanTes?></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="5px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="4" nowrap="true">
            <table width="128px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">Pangkat/Golongan</font></span></td>
            </tr>
            </table>
        </td>
        <td nowrap="true">
            <table width="16px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="Arial" color="#000000" size="1">:</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="8" nowrap="true">
            <table width="424px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="2"><?=$tempPegawaiGol?></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="5px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="4" nowrap="true">
            <table width="128px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">Biro/Bagian</font></span></td>
            </tr>
            </table>
        </td>
        <td nowrap="true">
            <table width="16px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="Arial" color="#000000" size="1">:</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="8" nowrap="true">
            <table width="424px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="2"><?= str_replace("Kepala", "",$tempPegawaiJabatanSaatIni)?></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="5px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="4" nowrap="true">
            <table width="128px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">Standart Penilaian</font></span></td>
            </tr>
            </table>
        </td>
        <td nowrap="true">
            <table width="16px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="Arial" color="#000000" size="1">:</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="8" nowrap="true">
            <table width="424px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="2"><?=$tempPegawaiEselon?></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="5px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="4" nowrap="true">
            <table width="128px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">Tanggal Tes</font></span></td>
            </tr>
            </table>
        </td>
        <td nowrap="true">
            <table width="16px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><span><font face="Arial" color="#000000" size="1">:</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="8" nowrap="true">
            <table width="424px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="2"><?=getFormattedDate($tempTanggalTes)?></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="40px" colspan="17">&nbsp;</td>
    </tr>
</table>
<br />
<table style="width:100%;" class="bordertable">
	<thead>
    	<tr style="background-color:#C7DBE4">
        	<td class="border txtcenter" colspan="6" style="font-style:italic;">ASPEK PSIKOLOGI</td>
        </tr>
    	<tr style="background-color:#C7DBE4">
        	<td class="border txtcenter" rowspan="2" style="width:5%;">No.</td>
        	<td class="border txtcenter" rowspan="2" style="width:30%;">Atribut</td>
            <td class="border txtcenter">Standard</td>
            <td class="border txtcenter">Individu</td>
            <td class="border txtcenter">Gap</td>
        	<td class="border txtcenter" rowspan="2" style="width:20%;">Kesimpulan/Conclusion</td>
        </tr>
        <tr style="background-color:#C7DBE4">
        	<td class="border txtcenter" style="width:10%">Rating</td>
        	<td class="border txtcenter" style="width:10%">Rating</td>
        	<td class="border txtcenter" style="width:10%">Rating</td>
        </tr>
    </thead>
    <tbody>
    <?
	$arrayValKey= '';
	$arrayValKey= in_array_column("1", "ASPEK_ID", $arrDetil);
	if($arrayValKey == ''){}
	else
	{
		$tempNilaiStandartJumlah= $tempNilaiJumlah= $tempGapJumlah= 0;
		for($index_val=0; $index_val < count($arrayValKey); $index_val++)
		{
			$nomor= $index_val + 1;
			$index_row= $arrayValKey[$index_val];
			$tempAtributNama= $arrDetil[$index_row]["ATRIBUT_NAMA"];
			$tempNilaiStandart= $arrDetil[$index_row]["NILAI_STANDAR"];
			$tempNilai= $arrDetil[$index_row]["NILAI"];
			$tempGap= $arrDetil[$index_row]["GAP"];
			$tempKeterangan= $arrDetil[$index_row]["KETERANGAN"];
			
			$tempNilaiStandartJumlah+= $tempNilaiStandart;
			$tempNilaiJumlah+= $tempNilai;
			$tempGapJumlah+= $tempGap;
    ?>
    	<tr>
        	<td class="border txtcenter"><?=romanicNumber($nomor)?></td>
        	<td class="border txtcenter"><?=$tempAtributNama?></td>
        	<td class="border txtcenter"><?=$tempNilaiStandart?></td>
        	<td class="border txtcenter"><?=$tempNilai?></td>
        	<td class="border txtcenter"><?=$tempGap?></td>
        	<td class="border txtcenter"><?=$tempKeterangan?></td>
        </tr>
    <?
		}
	}
	
	if($tempGapJumlah > 0)
		$tempKeteranganJumlah= 'Di Atas Standar / Above Expection';
	elseif($tempGapJumlah < 0)
		$tempKeteranganJumlah= 'Kurang Memenuhi Standar / Below Requirement';
	else
		$tempKeteranganJumlah= 'Memenuhi Standar / Meet Requirement';
				
    ?>
    </tbody>
    <tfoot>
    	<tr>
        	<td style="background-color:#C7DBE4" class="border txtcenter" colspan="2">Total Rating</td>
            <td style="background-color:#C7DBE4" class="border txtcenter"><?=$tempNilaiStandartJumlah?></td>
        	<td style="background-color:#C7DBE4" class="border txtcenter"><?=$tempNilaiJumlah?></td>
        	<td style="background-color:#C7DBE4" class="border txtcenter"><?=$tempGapJumlah?></td>
        	<td class="border txtcenter"><?=$tempKeteranganJumlah?></td>
        </tr>
    </tfoot>
</table>
<br />
<table style="width:100%;" class="bordertable">
	<thead>
    	<tr style="background-color:#C7DBE4">
        	<td class="border txtcenter" colspan="6" style="font-style:italic;">ASPEK KOMPETENSI</td>
        </tr>
    	<tr style="background-color:#C7DBE4">
        	<td class="border txtcenter" rowspan="2" style="width:5%;">No.</td>
        	<td class="border txtcenter" rowspan="2" style="width:30%;">Atribut</td>
            <td class="border txtcenter">Standard</td>
            <td class="border txtcenter">Individu</td>
            <td class="border txtcenter">Gap</td>
        	<td class="border txtcenter" rowspan="2" style="width:20%;">Kesimpulan/Conclusion</td>
        </tr>
        <tr style="background-color:#C7DBE4">
        	<td class="border txtcenter" style="width:10%">Rating</td>
        	<td class="border txtcenter" style="width:10%">Rating</td>
        	<td class="border txtcenter" style="width:10%">Rating</td>
        </tr>
    </thead>
    <tbody>
    <?
	$arrayValKey= '';
	$arrayValKey= in_array_column("2", "ASPEK_ID", $arrDetil);
	if($arrayValKey == ''){}
	else
	{
		$tempNilaiStandartJumlah= $tempNilaiJumlah= $tempGapJumlah= 0;
		for($index_val=0; $index_val < count($arrayValKey); $index_val++)
		{
			$nomor= $index_val + 1;
			$index_row= $arrayValKey[$index_val];
			$tempAtributNama= $arrDetil[$index_row]["ATRIBUT_NAMA"];
			$tempNilaiStandart= $arrDetil[$index_row]["NILAI_STANDAR"];
			$tempNilai= $arrDetil[$index_row]["NILAI"];
			$tempGap= $arrDetil[$index_row]["GAP"];
			$tempKeterangan= $arrDetil[$index_row]["KETERANGAN"];
			
			$tempNilaiStandartJumlah+= $tempNilaiStandart;
			$tempNilaiJumlah+= $tempNilai;
			$tempGapJumlah+= $tempGap;
    ?>
    	<tr>
        	<td class="border txtcenter"><?=romanicNumber($nomor)?></td>
        	<td class="border txtcenter"><?=$tempAtributNama?></td>
        	<td class="border txtcenter"><?=$tempNilaiStandart?></td>
        	<td class="border txtcenter"><?=$tempNilai?></td>
        	<td class="border txtcenter"><?=$tempGap?></td>
        	<td class="border txtcenter"><?=$tempKeterangan?></td>
        </tr>
    <?
		}
	}
	
	if($tempGapJumlah > 0)
		$tempKeteranganJumlah= 'Di Atas Standar / Above Expection';
	elseif($tempGapJumlah < 0)
		$tempKeteranganJumlah= 'Kurang Memenuhi Standar / Below Requirement';
	else
		$tempKeteranganJumlah= 'Memenuhi Standar / Meet Requirement';
				
    ?>
    </tbody>
    <tfoot>
    	<tr>
        	<td style="background-color:#C7DBE4" class="border txtcenter" colspan="2">Total Rating</td>
            <td style="background-color:#C7DBE4" class="border txtcenter"><?=$tempNilaiStandartJumlah?></td>
        	<td style="background-color:#C7DBE4" class="border txtcenter"><?=$tempNilaiJumlah?></td>
        	<td style="background-color:#C7DBE4" class="border txtcenter"><?=$tempGapJumlah?></td>
        	<td class="border txtcenter"><?=$tempKeteranganJumlah?></td>
        </tr>
    </tfoot>
</table>