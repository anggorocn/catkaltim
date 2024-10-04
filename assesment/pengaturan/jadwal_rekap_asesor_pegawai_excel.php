<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/RekapAsesor.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");

$index_loop=0;
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set= new RekapAsesor();
$set->selectByParamsPenggalianAsesorPegawai(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrPenggalian[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrPenggalian[$index_loop]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
  $arrPenggalian[$index_loop]["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
  $index_loop++;
}
$jumlah_penggalian= $index_loop;
$colspanpenggalian= ($jumlah_penggalian*2) + 1;
// print_r($arrPenggalian);exit();

$checkasesorid= "";
$index_loop=0;
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set= new RekapAsesor();
$set->selectByParamsPenggalianPegawai(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $asesorid= $set->getField("ASESOR_ID");
  // if($checkasesorid == $asesorid){}
  // else
  // {
      // $arrPegawai[$index_loop]["ROWID"]= $set->getField("PEGAWAI_ID")."-".$set->getField("PENGGALIAN_ID")."-".$set->getField("JADWAL_ASESOR_ID");
      $arrPegawai[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
      $arrPegawai[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
      $arrPegawai[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
      $arrPegawai[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
      $arrPegawai[$index_loop]["ASESOR_ID"]= $asesorid;
      $arrPegawai[$index_loop]["NAMA_ASESOR"]= $set->getField("NAMA_ASESOR");
      $arrPegawai[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
      $arrPegawai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
      $index_loop++;
  // }
}
$jumlah_pegawai= $index_loop;
// print_r($arrPegawai);exit();

$index_loop=0;
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set= new RekapAsesor();
$set->selectByParamsNilaiAsesorPegawai(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  // $arrNilaiPegawai[$index_loop]["ROWID"]= $set->getField("PEGAWAI_ID")."-".$set->getField("PENGGALIAN_ID")."-".$set->getField("JADWAL_ASESOR_ID");
  $arrNilaiPegawai[$index_loop]["ROWID"]= $set->getField("PEGAWAI_ID")."-".$set->getField("PENGGALIAN_ID")."-".$set->getField("ASESOR_ID");

  $arrNilaiPegawai[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
  $arrNilaiPegawai[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrNilaiPegawai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
  $arrNilaiPegawai[$index_loop]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");
  $index_loop++;
}
$jumlah_nilai_pegawai= $index_loop;
// print_r($arrPegawai);exit();

$index_loop=0;
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set= new RekapAsesor();
$set->selectByParamsNilaiAsesorPotensiPegawai(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrNilaiPotensiPegawai[$index_loop]["ROWID"]= $set->getField("PEGAWAI_ID")."-".$set->getField("PENGGALIAN_ID")."-".$set->getField("ASESOR_ID");
  $arrNilaiPotensiPegawai[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
  $arrNilaiPotensiPegawai[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrNilaiPotensiPegawai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
  $arrNilaiPotensiPegawai[$index_loop]["ASESOR_ID"]= $set->getField("ASESOR_ID");
  $arrNilaiPotensiPegawai[$index_loop]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");
  $index_loop++;
}
$jumlah_nilai_potensi_pegawai= $index_loop;
// print_r($arrNilaiPotensiPegawai);exit();

$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTesInfo= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
unset($set);

$tempNamaFile= $tempNamaTipe." Tanggal : ".$tempTanggalTesInfo.".xls";

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
            <td colspan="12" style="font-size:13px ;font-weight:bold">Rekap Asesor Penilaian</td>	
        </tr>
</table>
<br/>
    	<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
            <tr>
            	<th rowspan="2">Nama Asesor</th>
            	<?
            	for($index_loop=0; $index_loop < $jumlah_penggalian; $index_loop++)
                {
                	$reqPenggalianId= $arrPenggalian[$index_loop]["PENGGALIAN_ID"];
                	$reqPenggalianNama= $arrPenggalian[$index_loop]["PENGGALIAN_NAMA"];
                	$reqPenggalianKode= $arrPenggalian[$index_loop]["PENGGALIAN_KODE"];
            	?>
            	<th colspan="2" style="text-align: center;"><?=$reqPenggalianKode?></th>
            	<?
            	}
            	?>
            </tr>
            <tr>
            	<?
            	for($index_loop=0; $index_loop < $jumlah_penggalian; $index_loop++)
                {
            	?>
            	<th style="width: 50px; text-align: center;">Dapat</th>
            	<th style="width: 50px; text-align: center;">Progres</th>
            	<?
            	}
            	?>
            </tr>
       </thead>
       <tbody class="example altrowstable" id="alternatecolor"> 
		<?
		$pegawaiid= "";
		for($index_loop=0; $index_loop < $jumlah_pegawai; $index_loop++)
        {
        	// $reqPegawaiRowId= $arrPegawai[$index_loop]["ROWID"];
        	$reqPegawaiId= $arrPegawai[$index_loop]["PEGAWAI_ID"];
        	$reqPegawaiNoUrut= $arrPegawai[$index_loop]["NOMOR_URUT_GENERATE"];
        	$reqPegawaiNama= $arrPegawai[$index_loop]["NAMA_PEGAWAI"];
        	$reqPegawaiNipBaru= $arrPegawai[$index_loop]["NIP_BARU"];
            $reqPegawaiAsesorId= $arrPegawai[$index_loop]["ASESOR_ID"];
        	$reqPegawaiAsesor= $arrPegawai[$index_loop]["NAMA_ASESOR"];
        	$reqPegawaiPenggalianId= $arrPegawai[$index_loop]["PENGGALIAN_ID"];
        	$reqPegawaiJadwalAsesorId= $arrPegawai[$index_loop]["JADWAL_ASESOR_ID"];
		?>
		<?
		if($pegawaiid == $reqPegawaiId)
		{
		?>
		<tr>
            <td><?=$reqPegawaiAsesor?></td>
            <?
            for($index_loop_detil=0; $index_loop_detil < $jumlah_penggalian; $index_loop_detil++)
            {
            	$reqPenggalianId= $arrPenggalian[$index_loop_detil]["PENGGALIAN_ID"];
            	$reqPenggalianNama= $arrPenggalian[$index_loop_detil]["PENGGALIAN_NAMA"];
            	$reqPenggalianKode= $arrPenggalian[$index_loop_detil]["PENGGALIAN_KODE"];

                if($reqPenggalianId == "0")
                {
                    $reqPegawaiRowId= $reqPegawaiId."-".$reqPenggalianId."-".$reqPegawaiAsesorId;
                    $reqNilaiPegawai= "0";
                    $arrayKey= in_array_column($reqPegawaiRowId, "ROWID", $arrNilaiPotensiPegawai);
                    // print_r($arrayKey);exit;
                    if($arrayKey == ''){}
                    else
                    {
                        $index_row= $arrayKey[0];
                        $reqPotensiPegawaiAsesorId= $arrNilaiPotensiPegawai[$index_row]["ASESOR_ID"];
                        $reqNilaiPegawai= $arrNilaiPotensiPegawai[$index_row]["JUMLAH_DATA"];
                    }

                    $tempValuePenggalianInfoId= "Tidak";
                    if($reqPotensiPegawaiAsesorId == $reqPegawaiAsesorId)
                    {
                        $tempValuePenggalianInfoId= "Ya";
                    }

                    if($tempValuePenggalianInfoId == "Ya")
                        $tempValuePenggalianNilaiInfoId= "Belum";
                    else
                        $tempValuePenggalianNilaiInfoId= "-";

                    if($reqNilaiPegawai > 0)
                    {
                        $tempValuePenggalianNilaiInfoId= "Sudah";
                    }

                }
                else
                {
                	$tempValuePenggalianInfoId= "Tidak";
                	if($reqPenggalianId == $reqPegawaiPenggalianId)
                	{
                		$tempValuePenggalianInfoId= "Ya";
                	}

            		$reqPegawaiRowId= $reqPegawaiId."-".$reqPenggalianId."-".$reqPegawaiAsesorId;
                	$reqNilaiPegawai= "0";
                	$arrayKey= in_array_column($reqPegawaiRowId, "ROWID", $arrNilaiPegawai);
                	// print_r($arrayKey);exit;
                    if($arrayKey == ''){}
                    else
                    {
                    	$index_row= $arrayKey[0];
      					$reqNilaiPegawai= $arrNilaiPegawai[$index_row]["JUMLAH_DATA"];
                        $tempValuePenggalianInfoId= "Ya";
                    }

                    if($tempValuePenggalianInfoId == "Ya")
                    	$tempValuePenggalianNilaiInfoId= "Belum";
                    else
                    	$tempValuePenggalianNilaiInfoId= "-";

                	if($reqNilaiPegawai > 0)
                	{
                		$tempValuePenggalianNilaiInfoId= "Sudah";
                	}
                }
            ?>
            <td style="text-align: center;"><?=valuechecked($tempValuePenggalianInfoId, $tempValuePenggalianInfoId)?></td>
            <td style="text-align: center;"><?=valuechecked($tempValuePenggalianNilaiInfoId, $tempValuePenggalianNilaiInfoId)?></td>
            <?
        	}
            ?>
        </tr>
		<?
		}
		else
		{
		?>
		<tr>
            <th colspan="<?=$colspanpenggalian?>"><?=$reqPegawaiNoUrut.". ".$reqPegawaiNama?></th>
        </tr>
		<tr>
            <td><?=$reqPegawaiAsesor?></td>
            <?
            for($index_loop_detil=0; $index_loop_detil < $jumlah_penggalian; $index_loop_detil++)
            {
            	$reqPenggalianId= $arrPenggalian[$index_loop_detil]["PENGGALIAN_ID"];
            	$reqPenggalianNama= $arrPenggalian[$index_loop_detil]["PENGGALIAN_NAMA"];
            	$reqPenggalianKode= $arrPenggalian[$index_loop_detil]["PENGGALIAN_KODE"];

                if($reqPenggalianId == "0")
                {
                    $reqPegawaiRowId= $reqPegawaiId."-".$reqPenggalianId."-".$reqPegawaiAsesorId;
                    $reqPotensiPegawaiAsesorId= $reqNilaiPegawai= "0";
                    $arrayKey= in_array_column($reqPegawaiRowId, "ROWID", $arrNilaiPotensiPegawai);
                    // print_r($arrayKey);exit;
                    if($arrayKey == ''){}
                    else
                    {
                        $index_row= $arrayKey[0];
                        $reqPotensiPegawaiAsesorId= $arrNilaiPotensiPegawai[$index_row]["ASESOR_ID"];
                        $reqNilaiPegawai= $arrNilaiPotensiPegawai[$index_row]["JUMLAH_DATA"];
                    }

                    $tempValuePenggalianInfoId= "Tidak";
                    if($reqPotensiPegawaiAsesorId == $reqPegawaiAsesorId)
                    {
                        $tempValuePenggalianInfoId= "Ya";
                    }

                    if($tempValuePenggalianInfoId == "Ya")
                        $tempValuePenggalianNilaiInfoId= "Belum";
                    else
                        $tempValuePenggalianNilaiInfoId= "-";

                    if($reqNilaiPegawai > 0)
                    {
                        $tempValuePenggalianNilaiInfoId= "Sudah";
                    }

                }
                else
                {
                	$tempValuePenggalianInfoId= "Tidak";
                	if($reqPenggalianId == $reqPegawaiPenggalianId)
                	{
                		$tempValuePenggalianInfoId= "Ya";
                	}

            		$reqPegawaiRowId= $reqPegawaiId."-".$reqPenggalianId."-".$reqPegawaiAsesorId;
                	$reqNilaiPegawai= "0";
                	$arrayKey= in_array_column($reqPegawaiRowId, "ROWID", $arrNilaiPegawai);
                	// print_r($arrayKey);exit;
                    if($arrayKey == ''){}
                    else
                    {
                    	$index_row= $arrayKey[0];
      					$reqNilaiPegawai= $arrNilaiPegawai[$index_row]["JUMLAH_DATA"];
                        $tempValuePenggalianInfoId= "Ya";
                    }

                    if($tempValuePenggalianInfoId == "Ya")
                    	$tempValuePenggalianNilaiInfoId= "Belum";
                    else
                    	$tempValuePenggalianNilaiInfoId= "-";

                	if($reqNilaiPegawai > 0)
                	{
                		$tempValuePenggalianNilaiInfoId= "Sudah";
                	}
                }
            ?>
            <td style="text-align: center;"><?=valuechecked($tempValuePenggalianInfoId, $tempValuePenggalianInfoId)?></td>
            <td style="text-align: center;"><?=valuechecked($tempValuePenggalianNilaiInfoId, $tempValuePenggalianNilaiInfoId)?>
            <?
        	}
            ?>
        </tr>
		<?
		}
		?>
		<? 
		$pegawaiid= $reqPegawaiId;
		}
		?>
        </tbody>
        </table>
</body>
</html>