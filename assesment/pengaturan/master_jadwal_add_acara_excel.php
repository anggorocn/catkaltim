<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalAcara.php");
include_once("../WEB/classes/base/Rekap.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqRowId= httpFilterGet("reqRowId");


$arrInfoData= array("Keterangan");

$index_loop= 0;
$arrData="";
$sOrder= "";
$set = new Rekap();
$statement = " AND A.JADWAL_ACARA_ID = ".$reqRowId;
$set->selectByParamsJadwalAcara(array(), -1, -1, $statement.$searchJson);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrData[$index_loop]["TANGGAL_TES"]= getFormattedDateTime($set->getField("TANGGAL_TES"), FALSE);
	$arrData[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
	$arrData[$index_loop]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
	$arrData[$index_loop]["PUKUL1"]= $set->getField("PUKUL1");
	$arrData[$index_loop]["PUKUL2"]= $set->getField("PUKUL2");
	$arrData[$index_loop]["ASESOR_ID"]= $set->getField("ASESOR_ID");
	$arrData[$index_loop]["ASESOR_NAMA"]= $set->getField("ASESOR_NAMA");
	$arrData[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrData[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
	$arrData[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
	$arrData[$index_loop]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");

	$index_loop++;
}
$jumlah_data= $index_loop;

$tempInfoLabel= $arrData[0]["PENGGALIAN_NAMA"].", ".$arrData[0]["PUKUL1"]." s/d ".$arrData[0]["PUKUL2"];
$tempNamaFile= $arrData[0]["PENGGALIAN_NAMA"]." Tanggal : ".$arrData[0]["TANGGAL_TES"].", ".$arrData[0]["PUKUL1"]." s/d ".$arrData[0]["PUKUL2"].".xls";
// echo $tempNamaFile;exit();
// echo $tempInfoLabel;exit();

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"".$tempNamaFile."\"");
// header("Content-Disposition: attachment; filename=master_jadwal_add_acara_excel.xls");
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
        <td colspan="12" style="font-size:13px ;font-weight:bold">Jadwal Acara : <?=$tempInfoLabel?></td>	
    </tr>
</table>
<br/>
<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
        	<?
            for($i=0; $i < count($arrInfoData); $i++)
            {
            	/*$width= "10";
            	if($i == 0)
            		$width= "100";
            	elseif($i == 1)
            		$width= "250";*/
            ?>
            	<th colspan="2" width="<?=$width?>px"><?=$arrInfoData[$i]?></th>
            <?
            }
            ?>
        </tr>
    </thead>
    <tbody>
    	<?
    	$checkasesor= $checkpegawai= "";
		for($index_loop=0; $index_loop < $jumlah_data;$index_loop++)
		{
			$tempNomorUrutGenerate= $arrData[$index_loop]["NOMOR_URUT_GENERATE"];
			$tempAsesorId= $arrData[$index_loop]["ASESOR_ID"];
			$tempAsesorNama= $arrData[$index_loop]["ASESOR_NAMA"];
			$tempPegawaiId= $arrData[$index_loop]["PEGAWAI_ID"];
			$tempPegawaiNama= $arrData[$index_loop]["NAMA_PEGAWAI"];
			$tempPegawaiNip= $arrData[$index_loop]["NIP_BARU"];
			$tempJumlahData= $arrData[$index_loop]["JUMLAH_DATA"];

		?>
			<?
			if($checkasesor == $tempAsesorId)
			{
			?>
			<tr>
	            <td class="str"><?=$tempNomorUrutGenerate?>. <?=$tempPegawaiNama?></td>
	        </tr>
			<?
			}
			else
			{
			?>
	    	<tr>
	            <td class="str" rowspan="<?=$tempJumlahData?>"><?=$tempAsesorNama?></td>
	            <td class="str"><?=$tempNomorUrutGenerate?>. <?=$tempPegawaiNama?></td>
	        </tr>
	        <?
	    	}
	        ?>
        <?
    	$checkasesor= $tempAsesorId;
    	}
        ?>

    	<!-- <tr>
            <td class="str" rowspan="2">tes a</td>
            <td class="str">data 1</td>
        </tr>
        <tr>
            <td class="str">data 2</td>
        </tr> -->
    </tbody>
</table>
</body>
</html>