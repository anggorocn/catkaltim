<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$set = new JadwalTesSimulasiAsesor();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqJenisId= httpFilterRequest("reqJenisId");
$reqId= httpFilterRequest("reqId");
$reqRowId= httpFilterRequest("reqRowId");
$reqMode = httpFilterRequest("reqMode");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data jadwal terlebih dahulu.');";	
	echo "window.parent.location.href = 'master_jadwal_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

/* DATA VIEW */
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
if($reqJenisId == "1")
{
	$statement.= " AND A.PENGGALIAN_ID IS NULL";
}
elseif($reqJenisId == "2")
{
	$statement.= " AND A.PENGGALIAN_ID IS NOT NULL";
}
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
?>
<table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
    <thead class="altrowstable">
        <tr>
          <th style="width:30%">Waktu</th>
          <th>Nama</th>
        </tr>
   </thead>
   <tbody class="example altrowstable" id="alternatecolor"> 
    <? 
    while($set->nextRow())
    {
        $tempRowId= $set->getField("PUKUL_AWAL");
        $tempSimulasiNama= $set->getField("NAMA_SIMULASI");
        $tempJam= $set->getField("PUKUL_AWAL")." s/d ".$set->getField("PUKUL_AKHIR");
    ?>
    <tr>
        <td><?=$tempJam?></td>
        <td><?=$tempSimulasiNama?></td>
    </tr>
    <?
    }
    ?>
    </tbody>
</table>