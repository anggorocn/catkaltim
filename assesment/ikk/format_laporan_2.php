<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqToleransi= httpFilterGet("reqToleransi");

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
                <td align="center"><span><font face="ArialMT" color="#000000" size="6"></font></span></td>
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
                <td align="center"><span><font face="ArialMT" color="#000000" size="6"></font></span><span><font face="ArialMT" color="#000000" size="6"><b></b></font></span><span><font face="ArialMT" color="#000000" size="6"></font></span></td>
            </tr>
            </table>
        </td>
        <td >&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="21px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="88px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="2" nowrap="true">
            <table width="56px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">Kepada</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="13">&nbsp;</td>
    </tr>
    <tr valign="top">
    <td height="8px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="7" nowrap="true">
            <table width="192px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1"><b>KEMENTRIAN&nbsp;DALAM&nbsp;NEGERI</b></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="8px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="3" nowrap="true">
            <table width="96px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">Untuk&nbsp;Pegawai</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="12">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td colspan="4" nowrap="true">
            <table width="128px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left"><span><font face="Arial" color="#000000" size="1">No</font></span><span><font face="Arial" color="#000000" size="1">.&nbsp;</font></span><span><font face="Arial" color="#000000" size="1">Urut</font></span></td>
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
                <td align="left"><span><font face="Arial" color="#000000" size="2">ccxz</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
    	<td style="padding-top:20px" colspan="2">&nbsp;</td>
        <td style="padding-top:20px; font-weight:bold" colspan="15"><?=$tempPegawaiNama?></td>
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
        <td height="40px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="80px" colspan="2">&nbsp;</td>
        <td class="border txtcenter" height="80px" colspan="6" nowrap="true">
            <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" size="1">KESIMPULAN DENGAN TOLERANSI</font></span></td>
            </tr>
            </table>
        </td>
        <td class="border txtcenter" height="80px" colspan="3" nowrap="true">
            <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" size="1">ASPEK PSIKOLOGI DAN KOMPETENSI MANAGERIAL</font></span></td>
            </tr>
            </table>
        </td>
        <td class="border txtcenter" height="80px" colspan="3" nowrap="true">
            <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" size="2">ASPEK PSIKOLOGI</font></span></td>
            </tr>
            </table>
        </td>
        <td class="border txtcenter" height="80px" colspan="3" nowrap="true">
            <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" size="2">ASPEK PSIKOLOGI</font></span></td>
            </tr>
            </table>
        </td>
        <td height="80px" colspan="2">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td class="border txtcenter" colspan="6" nowrap="true">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" size="1"><?=$reqToleransi?>%</font></span></td>
            </tr>
            </table>
        </td>
        <td class="border txtcenter" colspan="3" nowrap="true" style="background-color:#FFFF0E !important">
            <table width="160px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" size="1"></font></span></td>
            </tr>
            </table>
        </td>
        <td class="border txtcenter" colspan="3" nowrap="true" style="background-color:#3E915B !important">
            <table width="160px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" size="2"></font></span></td>
            </tr>
            </table>
        </td>
        <td class="border txtcenter" colspan="3" nowrap="true" style="background-color:#E24839 !important">
            <table width="160px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" size="2"></font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr valign="top">
        <td height="73px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="9px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="15px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="212px" colspan="17">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td height="51px" colspan="17">&nbsp;</td>
    </tr>
</table>