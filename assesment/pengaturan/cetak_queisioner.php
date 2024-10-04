<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/RekapSehat.php");
include_once("../WEB/classes/base-cat/kuisioner.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=cetak_quisioner.xls");
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
</head>
<body>
	<table style="width:500%;border-collapse: collapse;">
		<thead>
			<tr>
				<th style="border:solid black">No Peserta</th>
				<th style="border:solid black">Nama</th>
				<th style="border:solid black">Nip</th>
				<th style="border:solid black">Pendidikan Terakhir</th>
				<th style="border:solid black">Jenis Kelamin</th>
				<th style="border:solid black">Usia</th>
				<th style="border:solid black">Sebagai</th>
				<?
				$kuisionerHead = new kuisioner();		
				$kuisionerHead->selectByParamsSoal(array());
				$totalsoal=0;
				while($kuisionerHead->nextRow()){
				$totalsoal++;
				?>
				<th style="border:solid black"><?=$kuisionerHead->getField('pertanyaan')?></th>
				<?}?>
			</tr>
		</thead>
		<tbody>
			<?
			$kuisionerPeserta = new kuisioner();		
			$kuisionerPeserta->selectByParamsDataPeserta(array('jadwal_tes_id' => $reqId));
			while($kuisionerPeserta->nextRow()){
				$birthDate = new DateTime($kuisionerPeserta->getField('tgl_lahir'));
				$today = new DateTime(date());
				if ($birthDate > $today) { 
				    exit("0 tahun 0 bulan 0 hari");
				}
				$y = $today->diff($birthDate)->y;
				$m = $today->diff($birthDate)->m;
				$d = $today->diff($birthDate)->d;
				$kuisionerJawaban = new kuisioner();		
				$kuisionerJawaban->selectByParamsJawaban(array('ujian_id' => $reqId,'pegawai_id' => $kuisionerPeserta->getField('pegawai_id')));
				unset($jawaban);
				while($kuisionerJawaban->nextRow()){
					$jawaban[]=$kuisionerJawaban->getField('jawaban');
				}
				// print_r($jawaban);exit;
			?>
			<tr>
				<td style="border:solid black"><?=$kuisionerPeserta->getField('no_urut')?></td>
				<td style="border:solid black"><?=$kuisionerPeserta->getField('nama_peserta')?></td>
				<td style="border:solid black">'<?=$kuisionerPeserta->getField('nip_baru')?></td>
				<td style="border:solid black"><?=$kuisionerPeserta->getField('nama_pendidikan')?></td>
				<td style="border:solid black"><?=$kuisionerPeserta->getField('jenis_kelamin')?></td>
				<td style="border:solid black"><?=$y?> tahun <?=$m?> bulan</td>
				<td style="border:solid black">Peserta</td>
				<?for($i=0;$i<$totalsoal;$i++){?>
					<td style="border:solid black"><?=$jawaban[$i]?></td>
				<?}?>
				<td style="border:solid black"></td>
			</tr>
			<?}?>
		</tbody>
	</table>
</body>