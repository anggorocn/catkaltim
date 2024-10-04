<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
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
$reqNo= httpFilterRequest("reqNo");
$reqJenisId= httpFilterRequest("reqJenisId");
$reqId= httpFilterRequest("reqId");
$reqRowId= httpFilterRequest("reqRowId");
$reqPukulAwal= httpFilterRequest("reqPukulAwal");
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
$statement.= " AND A.PUKUL_AWAL = '".$reqPukulAwal."'";
if($reqJenisId == "1")
{
	$statement.= " AND A.PENGGALIAN_ID IS NULL";
}
elseif($reqJenisId == "2")
{
	$statement.= " AND A.PENGGALIAN_ID IS NOT NULL AND (A.STATUS_GROUP IS NULL OR A.STATUS_GROUP = '')";
}
elseif($reqJenisId == "3")
{
	$statement.= " AND A.PENGGALIAN_ID IS NOT NULL AND A.STATUS_GROUP = '1'";
}

$set->selectByParamsMonitoring(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempRowId= $set->getField("PUKUL_AWAL");
$tempSimulasiNama= $set->getField("NAMA_SIMULASI");
$tempJam= $set->getField("PUKUL_AWAL")." s/d ".$set->getField("PUKUL_AKHIR");
$tempJamAwal= $set->getField("PUKUL_AWAL");
$tempKelompokJumlah= $set->getField("KELOMPOK_JUMLAH");
$tempPenggalianId= $set->getField("PENGGALIAN_ID");

$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND B.PENGGALIAN_ID = ".$tempPenggalianId;
$set= new JadwalTesSimulasiAsesor();
$set->selectByParamsJumlahRowAsesorKelompok($statement);
//echo $set->query;exit;
$set->firstRow();
$tempJumlahRowKelompok= $set->getField("JUMLAH_DATA");

$statement= " AND A.ID_JADWAL = ".$reqId." AND D.PENGGALIAN_ID = ".$tempPenggalianId;
$set= new JadwalTesSimulasiAsesor();
$set->selectByParamsJumlahRowAsesorPegawaiKelompok($statement);
//echo $set->query;exit;
$set->firstRow();
$tempJumlahRowPegawaiKelompok= $set->getField("JUMLAH_DATA");

$index_loop=0;
$arrAsesorDalamKelompok="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND B.PENGGALIAN_ID = ".$tempPenggalianId;
$set_detil= new JadwalTesSimulasiAsesor();
$set_detil->selectByParamsAsesorDalamKelompok($statement);
//echo $set_detil->query;exit;
$index_kelompok=0;
$tempKelompoInfoKondisi= "";
while($set_detil->nextRow())
{
	$tempKelompoInfo= $set_detil->getField("KELOMPOK_INFO");
	
	if($tempKelompoInfoKondisi == $tempKelompoInfo)
	{
		$index_kelompok++;
	}
	else
	{
		$index_kelompok=0;
	}
	
	$arrAsesorDalamKelompok[$index_loop]["KELOMPOK_INFO_ID"]= $tempKelompoInfo."-".$index_kelompok;
	$arrAsesorDalamKelompok[$index_loop]["KELOMPOK_INFO"]= $tempKelompoInfo;
	$arrAsesorDalamKelompok[$index_loop]["NAMA_ASESOR"]= $set_detil->getField("NAMA_ASESOR");
	$index_loop++;
	$tempKelompoInfoKondisi= $tempKelompoInfo;
}
//print_r($arrAsesorDalamKelompok);exit;

$index_loop=0;
$arrPegawaiDalamKelompok="";
$statement= " AND A.ID_JADWAL = ".$reqId." AND D.PENGGALIAN_ID = ".$tempPenggalianId;
$set_detil= new JadwalTesSimulasiAsesor();
$set_detil->selectByParamsPegawaiDalamKelompok($statement);
//echo $set_detil->query;exit;
$index_kelompok=0;
$tempKelompoInfoKondisi= "";
while($set_detil->nextRow())
{
	$tempKelompoInfo= $set_detil->getField("KELOMPOK_INFO");
	
	if($tempKelompoInfoKondisi == $tempKelompoInfo)
	{
		$index_kelompok++;
	}
	else
	{
		$index_kelompok=0;
	}
	
	$arrPegawaiDalamKelompok[$index_loop]["KELOMPOK_INFO_ID"]= $tempKelompoInfo."-".$index_kelompok;
	$arrPegawaiDalamKelompok[$index_loop]["KELOMPOK_INFO"]= $tempKelompoInfo;
	$arrPegawaiDalamKelompok[$index_loop]["PEGAWAI_NAMA"]= $set_detil->getField("PEGAWAI_NAMA");
	$index_loop++;
	$tempKelompoInfoKondisi= $tempKelompoInfo;
}
//print_r($arrPegawaiDalamKelompok);exit;
?>
<tr>
    <td style="text-align:center"><?=$reqNo?></td>
    <td style="text-align:center"><?=$tempJam?></td>
    <?
	if($tempKelompokJumlah == "Tidak Ada"){}
	else
	{
		$tempRowTanggal= $tempKelompokJumlah + $tempJumlahRowPegawaiKelompok;
    ?>
    <td>
        <table style="width:100%; border:none !important">
        <tr>
            <td colspan="<?=$tempRowTanggal?>" style="text-align:center; background-color:#CCC; border:none !important"><?=$tempSimulasiNama?></td>
        </tr>
        <tr>
        	<?
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
            ?>
            <td style="text-align:center; background-color:#09F; color:#FFF; border:none !important">Kel. <?=$tempNo?></td>
            <?
			}
            ?>
        </tr>
        <tr>
        	<?
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
            ?>
            <td style="text-align:center; border:none !important">Ruang <?=$tempNo?></td>
            <?
			}
            ?>
        </tr>
        <?
		// buat asesor dalam kelompok
		for($x=0; $x < $tempJumlahRowKelompok; $x++)
		{
        ?>
        <tr>
        	<?
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
				$tempNamaKelompoAsesor= "Kel. ".$tempNo."-".$x;
				
				$arrayAsesorKelompokKey= '';
				$arrayAsesorKelompokKey= in_array_column($tempNamaKelompoAsesor, "KELOMPOK_INFO_ID", $arrAsesorDalamKelompok);
				
				if($arrayAsesorKelompokKey == '')
				{
			?>
            <td style="text-align:center; background-color:#6F6; border:none !important"></td>
            <?
				}
				else
				{
					for($index_detil_asesor_kelompok=0; $index_detil_asesor_kelompok < count($arrayAsesorKelompokKey); $index_detil_asesor_kelompok++)
					{
						$index_row= $arrayAsesorKelompokKey[$index_detil_asesor_kelompok];
						$tempNamaAsesorDalamKelompok= $arrAsesorDalamKelompok[$index_row]["NAMA_ASESOR"];
			?>
            <td style="text-align:center; background-color:#6F6; border:none !important"><?=$tempNamaAsesorDalamKelompok?></td>
            <?
					}
				}
			}
			?>
        </tr>
        <?
		}
        ?>
        <?
		// buat pegawai dalam kelompok
		for($x=0; $x < $tempJumlahRowPegawaiKelompok; $x++)
		{
        ?>
        <tr>
        	<?
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
				$tempNamaKelompoAsesor= "Kel. ".$tempNo."-".$x;
				
				$arrayPegawaiKelompokKey= '';
				$arrayPegawaiKelompokKey= in_array_column($tempNamaKelompoAsesor, "KELOMPOK_INFO_ID", $arrPegawaiDalamKelompok);
				
				if($arrayPegawaiKelompokKey == '')
				{
			?>
            <td style="text-align:center; background-color:#F66; border:none !important"></td>
            <?
				}
				else
				{
					for($index_detil_asesor_kelompok=0; $index_detil_asesor_kelompok < count($arrayPegawaiKelompokKey); $index_detil_asesor_kelompok++)
					{
						$index_row= $arrayPegawaiKelompokKey[$index_detil_asesor_kelompok];
						$tempNamaPegawaiDalamKelompok= $arrPegawaiDalamKelompok[$index_row]["PEGAWAI_NAMA"];
			?>
            <td style="border:none !important"><?=$tempNamaPegawaiDalamKelompok?></td>
            <?
					}
				}
			}
			?>
        <?
		}
        ?>
        </table>
    </td>
    <?
	}
    ?>
</tr>