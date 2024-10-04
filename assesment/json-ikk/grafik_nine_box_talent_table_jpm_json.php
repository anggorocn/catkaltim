<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTahun= httpFilterGet("reqTahun");
$reqEselonId= httpFilterGet("reqEselonId");
$reqId= httpFilterGet("reqId");

if($reqTahun == "")
	$reqTahun= "2016";
	
$set= new Kelautan();

$statement = " AND A.SATKER_ID LIKE '".$reqId."%'";
$statement .= " AND ((CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) + (CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END)) > 0";

if($reqEselonId == "")
	$statement.="";
else
	$statement .= " AND SUBSTRING(A.LAST_ESELON_ID,1,1) = '".$reqEselonId."' ";
	
$field = array("NO", "NAMA", "NAMA_JAB_STRUKTURAL", "SATKER", "JPM", "IKK");
$sOrder = " ORDER BY X.NILAI DESC ";
$set->selectByParamsMonitoringTableTalentPoolPotensiKompetensi(array(), -1, -1, $statement, $sOrder, $reqTahun);
//echo $set->query;exit;
?>

<table>
<thead>
    <tr>
        <th>KUADRAN</th>
        <th>JUMLAH</th>
        <th>KETERANGAN</th>
        <th>DESKRIPSI</th>
    </tr>
</thead>
<tbody>
	<?
    while($set->nextRow())
    {
	?>
    	<tr>
            <td style="font-size: 12px;"><?=$set->getField("KODE_KUADRAN")?></td>
            <td style="font-size: 12px;">
				<?
                if($set->getField("JUMLAH") > 0)
                {
                ?>
                <a href="#" onclick="openInfo('<?=$set->getField("ID_KUADRAN")?>', '<?=$set->getField("KODE_KUADRAN")?>');" style="text-decoration:none"><?=$set->getField("JUMLAH")?> orang</a>
                <?
                }
                else
                {
                ?>
                <?=$set->getField("JUMLAH")?> orang
                <?
                }
                ?>
            </td>
            <td style="font-size: 12px;"><?=$set->getField("NAMA_KUADRAN")?></td>
            <td style="font-size: 10px;"><?=$set->getField("KETERANGAN")?></td>
        </tr>
    <?
    }
	?>
</tbody>
</table>