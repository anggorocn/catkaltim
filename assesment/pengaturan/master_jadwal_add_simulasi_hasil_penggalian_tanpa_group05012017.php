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
	$statement.= " AND A.PENGGALIAN_ID IS NOT NULL AND (A.STATUS_GROUP IS NULL OR A.STATUS_GROUP = '')";
}
elseif($reqJenisId == "3")
{
	$statement.= " AND A.PENGGALIAN_ID IS NOT NULL AND A.STATUS_GROUP = '1'";
}
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
//echo $set->query;exit;

$index_loop=0;
$arrJadwalAsesor="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set_detil= new JadwalAsesor();
$set_detil->selectByParamsMonitoring(array(), -1,-1, $statement, "ORDER BY B.PUKUL1 ASC, F.NAMA");
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrJadwalAsesor[$index_loop]["JADWAL_ASESOR_ID"]= $set_detil->getField("JADWAL_ASESOR_ID");
	$arrJadwalAsesor[$index_loop]["ASESOR_ID"]= $set_detil->getField("ASESOR_ID");
	$arrJadwalAsesor[$index_loop]["ASESOR_NAMA"]= $set_detil->getField("ASESOR_NAMA");
	$arrJadwalAsesor[$index_loop]["PUKUL1"]= $set_detil->getField("PUKUL1");
	$arrJadwalAsesor[$index_loop]["PUKUL2"]= $set_detil->getField("PUKUL2");
	$arrJadwalAsesor[$index_loop]["KELOMPOK"]= $set_detil->getField("KELOMPOK");
	$arrJadwalAsesor[$index_loop]["RUANG"]= $set_detil->getField("RUANG");
	$arrJadwalAsesor[$index_loop]["JADWAL_KELOMPOK_RUANGAN_ID"]= $set_detil->getField("JADWAL_KELOMPOK_RUANGAN_ID");
	$arrJadwalAsesor[$index_loop]["KELOMPOK_RUANGAN_NAMA"]= $set_detil->getField("KELOMPOK_RUANGAN_NAMA");
	$arrJadwalAsesor[$index_loop]["KETERANGAN_JADWAL"]= $set_detil->getField("KETERANGAN_JADWAL");
	$arrJadwalAsesor[$index_loop]["TOTAL_JAM_ASESOR"]= $set_detil->getField("TOTAL_JAM_ASESOR");
	$index_loop++;
}
//print_r($arrJadwalAsesor);

$index_loop=0;
$arrJadwalPegawai="";
$statement= " AND A.ID_JADWAL = ".$reqId;
$set_detil= new JadwalPegawai();
$set_detil->selectByParamsPegawai(array(), -1,-1, $statement);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrJadwalPegawai[$index_loop]["JADWAL_ASESOR_ID"]= $set_detil->getField("JADWAL_ASESOR_ID");
	$arrJadwalPegawai[$index_loop]["SATKER_TES_ID"]= $set_detil->getField("SATKER_TES_ID");
	$arrJadwalPegawai[$index_loop]["TANGGAL_TES"]= dateToPageCheck($set_detil->getField("TANGGAL_TES"));
	
	$arrJadwalPegawai[$index_loop]["JADWAL_PEGAWAI_ID"]= $set_detil->getField("JADWAL_PEGAWAI_ID");
	$arrJadwalPegawai[$index_loop]["PEGAWAI_ID"]= $set_detil->getField("PEGAWAI_ID");
	$arrJadwalPegawai[$index_loop]["PEGAWAI_NAMA"]= $set_detil->getField("PEGAWAI_NAMA");
	$arrJadwalPegawai[$index_loop]["PEGAWAI_NIP"]= $set_detil->getField("PEGAWAI_NIP");
	$arrJadwalPegawai[$index_loop]["PEGAWAI_GOL"]= $set_detil->getField("PEGAWAI_GOL");
	$arrJadwalPegawai[$index_loop]["PEGAWAI_ESELON"]= $set_detil->getField("PEGAWAI_ESELON");
	$arrJadwalPegawai[$index_loop]["PEGAWAI_JAB_STRUKTURAL"]= $set_detil->getField("PEGAWAI_JAB_STRUKTURAL");
	$arrJadwalPegawai[$index_loop]["KETERANGAN_JADWAL"]= $set_detil->getField("KETERANGAN_JADWAL");
	$index_loop++;
}
//print_r($arrJadwalPegawai);exit;
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
		$tempJamAwal= $set->getField("PUKUL_AWAL");
    ?>
    <tr>
        <td><?=$tempJam?></td>
        <td><?=$tempSimulasiNama?></td>
    </tr>
    <?
	$arrayKey= '';
	$arrayKey= in_array_column($tempJamAwal, "PUKUL1", $arrJadwalAsesor);
	//print_r($arrayKey);exit;
	if($arrayKey == ''){}
	else
	{
	?>
    <tr>
        <th colspan="2">
            <table class="gradient-style" style="width:100%; margin-left:-1px">
            <thead class="altrowstable">
            <tr>
                <td scope="col" style="width:25%;" class="modifth">Nama Asesor</td>
                <td scope="col" style="text-align:center; width:15%">Total Jam dalam Hari ini</td>
        		<td scope="col" style="text-align:center; width:20%">Kelompok & Ruangan</td>
                <td scope="col" style="text-align:center;">Pegawai</td>
            </tr>
            </thead>
            <tbody>
            <?
            for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
            {
				$index_row= $arrayKey[$index_detil];
				$tempJadwalAsesorId= $arrJadwalAsesor[$index_row]["JADWAL_ASESOR_ID"];
				$tempAsesor= $arrJadwalAsesor[$index_row]["ASESOR_NAMA"];
				$tempTotalJamAsesor= getTimeIndo($arrJadwalAsesor[$index_row]["TOTAL_JAM_ASESOR"]);
				$styleCss= "";
				if(getTimeJam($arrJadwalAsesor[$checkbox_index]["TOTAL_JAM_ASESOR"]) >= 5)
				$styleCss= "color:#F33";
				$tempJadwalKelompokRuanganNama= $arrJadwalAsesor[$index_row]["KELOMPOK_RUANGAN_NAMA"];
				
			?>
            <tr>
        		<td><?=$tempAsesor?></td>
                <td><label style=" <?=$styleCss?>" id="reqTotalJamAsesor<?=$checkbox_index?>"><?=$tempTotalJamAsesor?></label></td>
                <td><?=$tempJadwalKelompokRuanganNama?></td>
                <td>
                <?
                	$arrayPegawaiKey= '';
                    $arrayPegawaiKey= in_array_column($tempJadwalAsesorId, "JADWAL_ASESOR_ID", $arrJadwalPegawai);
                    //print_r($arrayPegawaiKey);exit;
                    if($arrayPegawaiKey == '')
					{
				?>
                Tidak ada
                <?
					}
                    else
                    {
						for($index_detil_pegawai=0; $index_detil_pegawai < count($arrayPegawaiKey); $index_detil_pegawai++)
						{
							$index_row= $arrayPegawaiKey[$index_detil_pegawai];
							$tempPegawaiNama= $arrJadwalPegawai[$index_row]["PEGAWAI_NAMA"];
							if($index_detil_pegawai == 0){}
							else
							echo "<br/>";
							echo $tempPegawaiNama;
						}
					}
				?>
                </td>
            </tr>
            <?
			}
            ?>
            </tbody>
            </table>
		</th>
    </tr>
    <?
	}
	
	}
    ?>
    </tbody>
</table>