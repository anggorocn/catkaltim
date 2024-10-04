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
        <tr>
            <td class="border txtcenter" style="width:30px">NO</td>
            <td class="border txtcenter" style="width:200px">ASPEK PENILAIAN</td>
            <td class="border txtcenter" style="width:80px">Standar Skor</td>
            <td class="border txtcenter" style="width:80px">Skor Penilaian</td>
            <td class="border txtcenter" style="width:80px">Bobot Penilaian</td>
            <td class="border txtcenter" style="width:80px">Standar Skor Akhir</td>
            <td class="border txtcenter" style="width:80px">Skor Individu Akhir</td>
            <td class="border txtcenter" style="width:40px">GAP</td>
            <td class="border txtcenter">Kesimpulan</td>
        </tr>
    </thead>
    <tbody>
    	<tr>
        	<td class="border">1</td>
            <td class="border">test</td>
            <td class="border">-</td>
            <td class="border">-</td>
            <td class="border">-</td>
            <td class="border">-</td>
            <td class="border">-</td>
            <td class="border">-</td>
            <td style="background:#000; color:#FFF;">12312</td>
        </tr>
    </tbody>
</table>