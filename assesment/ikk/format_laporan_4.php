<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");

$statement= " AND A.PEGAWAI_ID= ".$reqId." AND YEAR(A1.TANGGAL_TES) = '".$reqTahun."'";
$set = new Penilaian();
$set->selectByParamsPenilaianGroupTools(array(), -1, -1, $statement);
//$set->firstRow();
//echo $set->query;exit;
?>
<br/><br/><br/><br/><br/>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
    	<td height="135px" colspan="5">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td class="txtcenter" colspan="3" nowrap="true">
            <table width="672px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" style="font-size:28px"><b>TINJAUAN&nbsp;UMUM&nbsp;ALAT&nbsp;UKUR&nbsp;YANG&nbsp;DIGUNAKAN</b></font></span></td>
            </tr>
            </table>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr valign="top">
    	<td height="35px" colspan="5">&nbsp;</td>
    </tr>
</table>
<div class="setbordershadow">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
        <td width="6.250%"></td>
        <td width="19.792%"></td>
        <td width="45.833%"></td>
        <td width="21.875%"></td>
        <td width="6.250%"></td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td class="txtcenter" nowrap="true">
            <table width="672px" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><span><font face="Arial" color="#000000" style="font-size:30px">Kandidat&nbsp;terlibat&nbsp;dalam&nbsp;beberapa&nbsp;aktivitas,&nbsp;yaitu&nbsp;</font></span><span><font face="Arial" color="#000000" size="3">:</font></span></td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    
    <?
	while($set->nextRow())
	{
	?>
    <tr valign="top">
    	<td height="24px" colspan="5">&nbsp;</td>
    </tr>
    <tr valign="top">
        <td colspan="2">&nbsp;</td>
        <td class="txtcenter" nowrap="true">
            <table width="672px" border="0" cellpadding="0" cellspacing="0">
            <tr>G., G.
                <td>
                <span>
                <font face="Arial" color="#000000" style="font-size:30px">
				<?=$set->getField("NAMA")?>
                <?
				if($set->getField("KODE") == ""){}
				else
				{
                ?>
                <?=" (".$set->getField("KODE")."}"?>
                <?
				}
                ?>
                </font>
                </span>
                </td>
            </tr>
            </table>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <?
	}
	?>
    
    <?php /*?><tr valign="top">
    	<td height="95px" colspan="5">&nbsp;</td>
    </tr>
    <tr valign="top">
    	<td height="15px" colspan="5">&nbsp;</td>
    </tr>
    <tr valign="top">
    	<td height="15px" colspan="5">&nbsp;</td>
    </tr>
    <tr valign="top">
    	<td height="361px" colspan="5">&nbsp;</td>
    </tr>
    <tr valign="top">
    	<td height="44px" colspan="5">&nbsp;</td>
    </tr><?php */?>
</table>
</div>