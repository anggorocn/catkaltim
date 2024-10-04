<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
//include_once("../WEB/classes/base-ikk/PenilaianRekomendasi.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");


$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
// echo $reqJadwalTesId;exit();

// $statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
// $set= new PenilaianRekomendasi();
// $set->selectByParams(array(), -1,-1, $statement);
// // echo $set->query;exit();
// $set->firstRow();
// $reqNilaiAkhirSaranPengembangan= $set->getField("KETERANGAN");
$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new JadwalPegawaiDetil();
$set->selectByParamsPotensiText(array(), -1,-1, $statement);
// echo $set->query;exit();
$set->firstRow();
$reqStrength= $set->getField("CATATAN_STRENGTH");
$reqWeakness= $set->getField("CATATAN_WEAKNES");
$reqKesimpulan= $set->getField("KESIMPULAN");
$reqSaranPengembangan= $set->getField("SARAN_PENGEMBANGAN");
$reqSaranPenempatan= $set->getField("SARAN_PENEMPATAN");
unset($set);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../WEB/css/cetaknew.css" type="text/css">
</head>
<body>
	<div class="container">
		
		<div style="margin-top: 0px">
			<table style="font-size: 10pt; border-collapse: collapse; font-weight: bold;" width="100%">
				<tr>
					<td style="width: 7%">C.</td>
					<td>STRENGTH</td>	
				</tr>
			</table>
			<div style="margin-left: 5%; border: 0px solid black; ">
				<table style="margin-left: 1%; font-size: 10pt; margin-top: 3%; border-collapse: collapse;" width="100%">
					<tr>
						<td style="border: 1px solid black; text-align: justify; ">
							<?=$reqStrength?>
						</td>
					</tr>
				</table>
			</div>
			<br>
			<table style="font-size: 10pt; border-collapse: collapse; font-weight: bold;" width="100%">
				<tr>
					<td style="width: 7%">D.</td>
					<td>WEAKNESS</td>	
				</tr>
			</table>
			<div style="margin-left: 5%; border: 0px solid black; ">
				<table style="margin-left: 1%; font-size: 10pt; margin-top: 3%; border-collapse: collapse;" width="100%">
					<tr>
						<td style="border: 1px solid black; text-align: justify; ">
							<?=$reqWeakness?>
						</td>
					</tr>
				</table>
			</div>
			<br>
			<table style="font-size: 10pt; border-collapse: collapse; font-weight: bold;" width="100%">
				<tr>
					<td style="width: 7%">E.</td>
					<td>KESIMPULAN</td>	
				</tr>
			</table>
			<div style="margin-left: 5%; border: 0px solid black; ">
				<table style="margin-left: 1%; font-size: 10pt; margin-top: 3%; border-collapse: collapse;" width="100%">
					<tr>
						<td style="border: 1px solid black; text-align: justify; ">
							<?=$reqKesimpulan?>
						</td>
					</tr>
				</table>
			</div>
			<br>
			<table style="font-size: 10pt; border-collapse: collapse; font-weight: bold;" width="100%">
				<tr>
					<td style="width: 7%">F.</td>
					<td>SARAN PENGEMBANGAN</td>	
				</tr>
			</table>
			<div style="margin-left: 5%; border: 0px solid black; ">
				<table style="margin-left: 1%; font-size: 10pt; margin-top: 3%; border-collapse: collapse;" width="100%">
					<tr>
						<td style="border: 1px solid black; text-align: justify; ">
							<?=$reqSaranPengembangan?>
						</td>
					</tr>
				</table>
			</div>
			<br>
			<table style="font-size: 10pt; border-collapse: collapse; font-weight: bold;" width="100%">
				<tr>
					<td style="width: 7%">G.</td>
					<td>SARAN PENEMPATAN</td>	
				</tr>
			</table>
			<div style="margin-left: 5%; border: 0px solid black; ">
				<table style="margin-left: 1%; font-size: 10pt; margin-top: 3%; border-collapse: collapse;" width="100%">
					<tr>
						<td style="border: 1px solid black; text-align: justify; ">
							<?=$reqSaranPenempatan?>
						</td>
					</tr>
				</table>
			</div>
			<br>
			

		</div>

	</div>
</body>
</html>