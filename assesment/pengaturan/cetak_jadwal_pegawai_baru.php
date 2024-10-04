<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalTesSimulasiPegawai.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");


/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId = httpFilterRequest("reqId");


$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;

$tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), 'date');
$tempTanggalTesInfo= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=absen_pegawai_".$tempTanggalTesInfo.'-'.$tempFormulaEselon.".xls");


$index_loop= 0;
$arrJadwalAsesor="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set_detil= new JadwalTesSimulasiPegawai();
$set_detil->selectByParamsTanggalCetakPegawai(array(), -1,-1, $statement);

// echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrJadwalAsesor[$index_loop]["SATKER_TES_ID"]= $set_detil->getField("SATKER_TES_ID");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_ID"]= $set_detil->getField("PEGAWAI_ID");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_NAMA"]= $set_detil->getField("PEGAWAI_NAMA");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_NIP"]= $set_detil->getField("PEGAWAI_NIP");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_GOL"]= $set_detil->getField("PEGAWAI_GOL");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_ESELON"]= $set_detil->getField("PEGAWAI_ESELON");
	$arrJadwalAsesor[$index_loop]["PEGAWAI_JAB_STRUKTURAL"]= $set_detil->getField("PEGAWAI_JAB_STRUKTURAL");
	$arrJadwalAsesor[$index_loop]["KETERANGAN_JADWAL"]= $set_detil->getField("KETERANGAN_JADWAL");
	$arrJadwalAsesor[$index_loop]["LAST_UPDATE_DATE"]= $set_detil->getField("LAST_UPDATE_DATE");
	$arrJadwalAsesor[$index_loop]["NO_URUT"]= $set_detil->getField("NO_URUT");
	$index_loop++;
}
// print_r('sadsaaaaa');exit;

$jumlah_asesor= $index_loop;
// print_r($jumlah_asesor);exit;
// echo $set->query;exit();
// print_r('sadsaaaaa1');exit;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<style type="text/css">
		.text{
  mso-number-format:"\@";/*force text*/
}
	</style>
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
            <td colspan="12" style="font-size:13px ;font-weight:bold; text-align: center;">REKAPITULASI ABSEN PEGAWAI</td>	
        </tr>
        <tr>
        </tr>
         <tr>
            <td colspan="12" style="font-size:13px ;font-weight:bold; ">Dicetak oleh sistem assesment <?=date("d-m-Y H:i:s")?></td>	
        </tr>
	</table>
	<br/>
	<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th scope="col">No</th>
				<th scope="col" style="width:25%">
					Nama Pegawai
				</th>
				<th scope="col" style="width:5%">NIP</th>
				<th scope="col" style="width:15%">Gol.Ruang</th>
				<th scope="col" style="width:5%">Eselon</th>
				<th scope="col" style="width:40%">Jabatan</th>
				<th scope="col">Tanggal</th>
			</tr>
	    </thead>
        <tbody>
        	<?
        	$i=1;
        	for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
        	{
        		$tempJadwalAsesorId= $arrJadwalAsesor[$checkbox_index]["JADWAL_PEGAWAI_ID"];
        		$tempPegawaiId= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_ID"];

        		$tempPegawaiSatkerTesId= $arrJadwalAsesor[$checkbox_index]["SATKER_TES_ID"];
        		$tempPegawaiTanggalTes= $arrJadwalAsesor[$checkbox_index]["TANGGAL_TES"];

        		$tempPegawai= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_NAMA"];
        		$tempPegawaiNip= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_NIP"];
        		$tempPegawaiGol= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_GOL"];
        		$tempPegawaiEselon= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_ESELON"];
        		$tempPegawaiJabatan= $arrJadwalAsesor[$checkbox_index]["PEGAWAI_JAB_STRUKTURAL"];
        		$tempUpdateDate= $arrJadwalAsesor[$checkbox_index]["LAST_UPDATE_DATE"];
        		$tempNoUrut= $arrJadwalAsesor[$checkbox_index]["NO_URUT"];
        		?>
            	<tr>
            		<td><?=$tempNoUrut?></td>
                    <td><?=$tempPegawai?></td>
                    <td class="str"><?=$tempPegawaiNip?></td>
                    <td><?=$tempPegawaiGol?></td>
                    <td><?=$tempPegawaiEselon?></td>
                   	<td><?=$tempPegawaiJabatan?></td>
                   	<td  class="text" ><?=$tempUpdateDate?></td>
                </tr>
			<?
			$i++;
            }
            ?>
        </tbody>
    </table>
</body>
</html>