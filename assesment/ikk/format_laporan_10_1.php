<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");

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

$statement= " AND A.ASPEK_ID = 1 AND A.PEGAWAI_ID= ".$reqId." AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'";
$set = new Penilaian();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqRowId= $set->getField("PENILAIAN_ID");

$statement= " AND A.ATRIBUT_ID_PARENT != '0'";
$index_psikologi=0;
$set_penilaian= new PenilaianDetil();
$set_penilaian->selectByParamsMonitoringPenilaian(array(), -1, -1, $statement, $reqRowId);
//echo $set_penilaian->query;exit;
while($set_penilaian->nextRow())
{
	$arrAtributPenilaian[$index_psikologi]["NAMA"] = $set_penilaian->getField("NAMA");
	$arrAtributPenilaian[$index_psikologi]["NILAI_STANDAR"] = $set_penilaian->getField("NILAI_STANDAR");
	$arrAtributPenilaian[$index_psikologi]["BOBOT"] = $set_penilaian->getField("BOBOT");
	$arrAtributPenilaian[$index_psikologi]["ATRIBUT_ID"] = $set_penilaian->getField("ATRIBUT_ID");
	$arrAtributPenilaian[$index_psikologi]["ATRIBUT_ID_PARENT"] = $set_penilaian->getField("ATRIBUT_ID_PARENT");
	$arrAtributPenilaian[$index_psikologi]["ATRIBUT_GROUP"] = $set_penilaian->getField("ATRIBUT_GROUP");
	$arrAtributPenilaian[$index_psikologi]["PENILAIAN_DETIL_ID"] = $set_penilaian->getField("PENILAIAN_DETIL_ID");
	$arrAtributPenilaian[$index_psikologi]["PENILAIAN_ID"] = $set_penilaian->getField("PENILAIAN_ID");
	$arrAtributPenilaian[$index_psikologi]["NILAI"] = $set_penilaian->getField("NILAI");
	$arrAtributPenilaian[$index_psikologi]["GAP"] = $set_penilaian->getField("GAP");
	$arrAtributPenilaian[$index_psikologi]["JUMLAH_PENILAIAN_DETIL"] = 10;
	$arrAtributPenilaian[$index_psikologi]["LEVEL_KETERANGAN"] = $set_penilaian->getField("LEVEL_KETERANGAN");
	
	$tempNilaiStandar= $arrAtributPenilaian[$index_psikologi]["NILAI_STANDAR"];
	$tempAtributId= $arrAtributPenilaian[$index_psikologi]["ATRIBUT_ID"];
	$tempAtributIdParent= $arrAtributPenilaian[$index_psikologi]["ATRIBUT_ID_PARENT"];
	$tempAtributGroup= $arrAtributPenilaian[$index_psikologi]["ATRIBUT_GROUP"];
	$tempNilai= $arrAtributPenilaian[$index_psikologi]["NILAI"];
	$tempGap= $arrAtributPenilaian[$index_psikologi]["GAP"];
	
	if($tempAtributIdParent == 0){}
	else
	{
		if($tempNilaiStandar == ""){}
		else
		$tempJpm= round($tempNilai/$tempNilaiStandar,2);
		
		//echo $tempJpm."<br/>";
		//kolom IKK (jika gap <= 0, ikk-> 1-jpm) (jika gap >0, ikk = jpm)
		if($tempGap <= 0)
			$tempIkk= 1 - $tempJpm;
		elseif($tempGap > 0)
			$tempIkk= 0;
		else//if($tempGap > 0)
			$tempIkk= $tempJpm;
		//echo $tempIkk."<br/>";
		//- total JPM (total jpm / total atribut) -> ditaruh di pojok kanan atas
		//- total IKK (total ikk / total atribut)  -> ditaruh di pojok kanan atas
		
		if($tempGap <= 0)
			$tempIkkLama= 1 - $tempJpm;
		else//if($tempGap > 0)
			$tempIkkLama= $tempJpm;

		if($tempIkkLama < 1)
			$tempKeteranganIkk= "dibawah standar";
		elseif($tempIkkLama == 0)
			$tempKeteranganIkk= "sesuai standar";
		else
		{
			$tempSuperior= $tempJpm-$tempGap;
			$tempKeteranganIkk= "superior lebih ".$tempSuperior." %";
		}
												
		$tempTotalIkk+= $tempIkk;
		$tempTotalJpm+= $tempJpm;
	}
	
	$arrAtributPenilaian[$index_psikologi]["KETERANGAN_IKK"] = $tempKeteranganIkk;
	$index_psikologi++;
}
unset($set_penilaian);
//print_r($arrAtributPenilaian);exit;
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
                <td align="center"><span><font face="ArialMT" color="#000000" size="6"><b>RINGKASAN KOMPETENSI POTENSI</b></font></span></td>
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
<table style="width:100%;">
	<thead>
    	<tr style="background-color:#C7DBE4">
        	<td class="border txtcenter" rowspan="2" style="width:8%;">NO</td>
        	<td class="border txtcenter" rowspan="2" style="width:30%;">AKTIFITAS/POTENSI</td>
            <td class="border txtcenter" colspan="5">RATING</td>
        	<td class="border txtcenter" rowspan="2" style="width:30%;">Kesimpulan</td>
        </tr>
        <tr style="background-color:#C7DBE4">
        	<td class="border txtcenter">1</td>
        	<td class="border txtcenter">2</td>
        	<td class="border txtcenter">3</td>
        	<td class="border txtcenter">4</td>
        	<td class="border txtcenter">5</td>
        </tr>
    </thead>
    <tbody>
    	<?
        $index_atribut_parent= 0;
        for($checkbox_index=0; $checkbox_index < $index_psikologi; $checkbox_index++)
        {
            $tempNama= $arrAtributPenilaian[$checkbox_index]["NAMA"];
            $tempNilaiStandar= $arrAtributPenilaian[$checkbox_index]["NILAI_STANDAR"];
            $tempBobot= $arrAtributPenilaian[$checkbox_index]["BOBOT"];
            $tempAtributId= $arrAtributPenilaian[$checkbox_index]["ATRIBUT_ID"];
            $tempAtributIdParent= $arrAtributPenilaian[$checkbox_index]["ATRIBUT_ID_PARENT"];
            $tempAtributGroup= $arrAtributPenilaian[$checkbox_index]["ATRIBUT_GROUP"];
            $tempPenilaianDetilId= $arrAtributPenilaian[$checkbox_index]["PENILAIAN_DETIL_ID"];
            $tempPenilaianId= $arrAtributPenilaian[$checkbox_index]["PENILAIAN_ID"];
            $tempNilai= $arrAtributPenilaian[$checkbox_index]["NILAI"];
            $tempGap= $arrAtributPenilaian[$checkbox_index]["GAP"];
            $tempJumlahPenilaianDetil= $arrAtributPenilaian[$checkbox_index]["JUMLAH_PENILAIAN_DETIL"];
            $tempLevelKeterangan= $arrAtributPenilaian[$checkbox_index]["LEVEL_KETERANGAN"];
            
            $index_atribut_parent++;
			$arrChecked= radioPenilaianInfo($tempNilai);
			$tempKeteranganDetil= radioKeteranganPenilaianInfo($tempNilai);
			$arrBackGround= radioBackgroundPenilaianInfo($tempNilai);
        ?>
    	<tr>
        	<td class="border txtcenter"><?=$index_atribut_parent?></td>
        	<td class="border"><?=$tempNama?></td>
            <td <? if($arrBackGround[0] == ""){} else{?> style="background-color:#<?=$arrBackGround[0]?>"<? }?> class="border txtcenter"><?=$arrChecked[0]?></td>
            <td <? if($arrBackGround[1] == ""){} else{?> style="background-color:#<?=$arrBackGround[1]?>"<? }?> class="border txtcenter"><?=$arrChecked[1]?></td>
            <td <? if($arrBackGround[2] == ""){} else{?> style="background-color:#<?=$arrBackGround[2]?>"<? }?> class="border txtcenter"><?=$arrChecked[2]?></td>
            <td <? if($arrBackGround[3] == ""){} else{?> style="background-color:#<?=$arrBackGround[3]?>"<? }?> class="border txtcenter"><?=$arrChecked[3]?></td>
            <td <? if($arrBackGround[4] == ""){} else{?> style="background-color:#<?=$arrBackGround[4]?>"<? }?> class="border txtcenter"><?=$arrChecked[4]?></td>
        	<td class="border txtcenter"><?=$tempKeteranganDetil?></td>
        </tr>
        <?
		}
        ?>
        <tr>
        	<td style="padding-top:10px; padding-bottom:10px; border:none !important;" class="txtcenter">Note :</td>
        	<td style="padding-top:10px; padding-bottom:10px; border:none !important" colspan="7"></td>
        </tr>
        <tr>
        	<td style="border:none !important;  background-color:#898D8C;" class="txtcenter"></td>
        	<td style="border:none !important" colspan="7">Standar</td>
        </tr>
        <tr>
        	<td style="padding-top:2px; padding-bottom:2px; border:none !important;" colspan="8">&nbsp;</td>
        </tr>
        <tr>
        	<td style="border:none !important;  background-color:#ECB2B1;" class="txtcenter">1</td>
        	<td style="border:none !important" colspan="7">Belum Kompeten</td>
        </tr>
        <tr>
        	<td style="padding-top:2px; padding-bottom:2px; border:none !important;" colspan="8">&nbsp;</td>
        </tr>
        <tr>
        	<td style="border:none !important;  background-color:#DA392C;" class="txtcenter">2</td>
        	<td style="border:none !important" colspan="7">Hampir Kompeten</td>
        </tr>
        <tr>
        	<td style="padding-top:2px; padding-bottom:2px; border:none !important;" colspan="8">&nbsp;</td>
        </tr>
        <tr>
        	<td style="border:none !important;  background-color:#FFFE0B;" class="txtcenter">3</td>
        	<td style="border:none !important" colspan="7">Cukup Kompeten</td>
        </tr>
        <tr>
        	<td style="padding-top:2px; padding-bottom:2px; border:none !important;" colspan="8">&nbsp;</td>
        </tr>
        <tr>
        	<td style="border:none !important;  background-color:#4C9D65;" class="txtcenter">4</td>
        	<td style="border:none !important" colspan="7">Kompeten</td>
        </tr>
        <tr>
        	<td style="padding-top:2px; padding-bottom:2px; border:none !important;" colspan="8">&nbsp;</td>
        </tr>
        <tr>
        	<td style="border:none !important;  background-color:#4565A7;" class="txtcenter">5</td>
        	<td style="border:none !important" colspan="7">Sangat Kompeten</td>
        </tr>
    </tbody>
</table>
</body>
</html>