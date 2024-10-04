<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Grafik.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTahun= httpFilterGet("reqTahun");
$reqEselonId= httpFilterGet("reqEselonId");
$reqId= httpFilterGet("reqId");
$reqKdOrganisasiId= httpFilterGet("reqKdOrganisasiId");
$reqJenisJabatan= httpFilterGet("reqJenisJabatan");
$reqPencarian= httpFilterGet("reqPencarian");
$reqFormulaId= httpFilterGet("reqFormulaId");


if($reqTahun == "")
	$reqTahun= "2015";
	
$set= new Grafik();
$statement = " AND A.SATKER_ID LIKE '".$reqId."%'";

// if($reqJenisJabatan == ""){}
// else
// {
//     if($reqEselonId == "")
//     {
//         $statement .= " AND ".jenisjabatanDb($reqJenisJabatan, "A.");
//     }
// }

// if($reqKdOrganisasiId == ""){}
// else
// {
//     $statement .= " AND A.KD_SATUAN_ORGANISASI = '".$reqKdOrganisasiId."'";
// }

// if($reqEselonId == ""){}
// else
// {
//     $statement .= " AND ".jejangjabatanDb($reqEselonId, "A.");
// }

if($reqPencarian == ""){}
else
{
    $statement.= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%') ";
}

if($reqFormulaId == "")
    $statement.="";
else
    $statement .= " AND X.FORMULA_ID = '".$reqFormulaId."' ";
	
$field = array("NO", "NAMA", "NAMA_JAB_STRUKTURAL", "SATKER", "JPM", "IKK");
$sOrder = " ORDER BY ORDER_KUADRAN";
$set->selectByParamsMonitoringTableTalentPoolSkpJPMPegawai(array(), -1, -1, $statement, $sOrder, $reqTahun);
 // echo $set->query;exit;
?>

<table>
<thead>
    <tr>
        <th style="font-size: 10px;">KUADRAN</th>
        <th style="font-size: 10px;">JUMLAH</th>
        <th style="font-size: 10px;">KETERANGAN</th> 
        <th style="font-size: 10px;">REKOMENDASI</th> 
    </tr>
</thead>
<tbody>
	<?
    while($set->nextRow())
    {
	?>
    	<tr>
            <td style="font-size: 12px; vertical-align: top;"><?=$set->getField("KODE_KUADRAN")?></td>
            <td style="font-size: 12px; vertical-align: top;">
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
            <td style="font-size: 12px; vertical-align: top;"><?=$set->getField("NAMA_KUADRAN")?></td> 
            <td style="font-size: 12px; vertical-align: top; text-align: left;"><?=$set->getField("REKOMENDASI_KUADRAN")?></td> 
        </tr>
    <?
    }
	?>
</tbody>
</table>