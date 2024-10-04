<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/RekapSehat.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");

// if ($userLogin->checkUserLogin()) 
// { 
// 	$userLogin->retrieveUserInfo();
// }

$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");
// echo $reqRowId;exit;
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
// echo $set->query;exit;
$tempTanggalTesInfo= getFormattedDateTime($set->getField('TANGGAL_TES'), false);

$statement= " AND TIPE_UJIAN_ID = ".$reqTipeUjianId;
$set= new TipeUjian();
$set->selectByParams(array(), -1,-1, $statement);
// echo $set->query;exit;
$set->firstRow();
$tempNamaTipe= $set->getField("TIPE");
unset($set);

if($reqTipeUjianId == "49")
{
	$arrData= array("NIP_BARU", "NAMA_PEGAWAI", "NILAI_R", "NILAI_I", "NILAI_A", "NILAI_S", "NILAI_E", "NILAI_C", "HASIL");
  $aColumns= array("NIP", "Nama", "NILAI R", "NILAI I", "NILAI A", "NILAI S", "NILAI E", "NILAI C", "HASIL");

}


// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
$sOrder= "order by JA.NOMOR_URUT asc";

$set = new RekapSehat();


if($reqTipeUjianId == "49")
{
	$statement= " AND B.JADWAL_TES_ID = ".$reqId ;
	$statementDetil= " AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId ;

	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.EMAIL) LIKE '%".strtoupper($_GET['sSearch'])."%')";

	// $sOrder= " ORDER BY NOMOR_URUT_GENERATE";
	$set->selectByParamsMonitoringHolland(array(), -1, -1, $reqId, $statement.$searchJson, $statementDetil, $sOrder);
}

if(empty($reqRowId))
	$tempNamaFile= $tempNamaTipe." Tanggal : ".$tempTanggalTesInfo.".xls";
else
{
	$p= new RekapSehat();
	$p->selectByParamsInfoPegawai(array(), -1,-1, " AND B.PEGAWAI_ID = ".$reqRowId);
	$p->firstRow();
	$infopegawainama= $p->getField("NAMA_PEGAWAI");
	unset($p);

	$tempNamaFile= $infopegawainama.".xls";
}
// echo $tempNamaFile;exit();

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"".$tempNamaFile."\"");
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<style>
	body, table{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif
	}
	th {
		text-align:center;
		font-weight: bold;
	}
	td {
		vertical-align: top;
  		text-align: left;
	}
	.str{
	  mso-number-format:"\@";/*force text*/
	}
	</style>
<table style="width:100%">
	<tr>
		<td colspan="12" style="font-size:13px ;font-weight:bold">Hasil <?=$tempNamaTipe?></td>
	</tr>
</table>
<br/>
<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
	<tr>
    <th style="text-align:center">No</th>
	<?
	if($reqTipeUjianId == "49"){?>
    <?
    for($i=0; $i < count($arrData); $i++)
    {
    ?>
       <th style="text-align:center"><?=$aColumns[$i]?></th>
    <?
    }
    $no = 1;  
  	while($set->nextRow())
  	{                 
		?>
    	<tr>
        <td><?=$no?></td>
    		<?
        for($i=0; $i < count($arrData); $i++)
        {
        ?>
           <td><?=$set->getField($arrData[$i])?></td>
        <?
        }
        ?>
    	</tr>
  	<?
  	$no++;
  	}
  }?>
</table>
</body>
</html>
