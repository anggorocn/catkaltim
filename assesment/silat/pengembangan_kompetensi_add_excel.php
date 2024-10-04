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

<?php 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PegawaiHcdp.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqFormulaId= httpFilterGet("reqFormulaId");

if(empty($reqFormulaId)) $reqFormulaId= -1;

$statement= "";

if(!empty($reqFormulaId))
$statement.= " AND W1.FORMULA_ID = ".$reqFormulaId;

if(!empty($reqId))
$statement.= " AND A.PEGAWAI_ID = ".$reqId;

$arrrealisasi= $arrpegawai= [];
$set= new PegawaiHcdp();
$set->selectcetak(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$vpegawaiid= $set->getField("PEGAWAI_ID");

	$infocarikey= $vpegawaiid;
	$arrcheck= in_array_column($infocarikey, "PEGAWAI_ID", $arrpegawai);
	// print_r($arrcheck);exit;
	if(empty($arrcheck))
	{
		$arrdata= [];
		$arrdata["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
		$arrdata["NIP_BARU"]= $set->getField("NIP_BARU");
		$arrdata["NAMA"]= $set->getField("NAMA");
		array_push($arrpegawai, $arrdata);
	}
	
	$arrdata= [];
	$arrdata["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrdata["NIP_BARU"]= $set->getField("NIP_BARU");
	$arrdata["NAMA"]= $set->getField("NAMA");
	$arrdata["ASPEK_ID"]= $set->getField("ASPEK_ID");
	$arrdata["ASPEK_NAMA"]= $set->getField("ASPEK_NAMA");
	$arrdata["PELATIHAN_NAMA"]= $set->getField("PELATIHAN_NAMA");
	$arrdata["RM_PELATIHAN_NAMA"]= $set->getField("RM_PELATIHAN_NAMA");
	$arrdata["BIAYA"]= $set->getField("BIAYA");
	$arrdata["WAKTU_PELAKSANA"]= $set->getField("WAKTU_PELAKSANA");
	$arrdata["PENYELENGGARA"]= $set->getField("PENYELENGGARA");
	$arrdata["SUMBER_DANA"]= $set->getField("SUMBER_DANA");
	$arrdata["MATERI_PENGEMBANGAN"]= $set->getField("MATERI_PENGEMBANGAN");
	$arrdata["JP"]= $set->getField("JP");
	$arrdata["STATUS"]= $set->getField("STATUS");
	$arrdata["ALASAN_PENGAJUAN"]= $set->getField("ALASAN_PENGAJUAN");
	array_push($arrrealisasi, $arrdata);
}
// print_r($arrpegawai);exit;
// print_r($arrrealisasi);exit;

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=rekap_hcdp.xls");

$arrinfo= array(
	array("label"=>"Nama Pegawai", "FIELD"=>"NAMA")
	, array("label"=>"NIP", "FIELD"=>"NIP_BARU")
	, array("label"=>"Jenis Kompetensi", "FIELD"=>"ASPEK_NAMA")
	, array("label"=>"Bangkom", "FIELD"=>"PELATIHAN_NAMA")
	, array("label"=>"Rumpun Pengembangan", "FIELD"=>"RM_PELATIHAN_NAMA")
	, array("label"=>"Biaya", "FIELD"=>"BIAYA")
	, array("label"=>"Waktu Pelaksanaan", "FIELD"=>"WAKTU_PELAKSANA")
	, array("label"=>"Penyelenggara", "FIELD"=>"PENYELENGGARA")
	, array("label"=>"Sumber Dana", "FIELD"=>"SUMBER_DANA")
	, array("label"=>"Materi Pengembangan", "FIELD"=>"MATERI_PENGEMBANGAN")
	, array("label"=>"JP", "FIELD"=>"JP")
	, array("label"=>"Status", "FIELD"=>"STATUS")
	, array("label"=>"Alasan Pengajuan", "FIELD"=>"ALASAN_PENGAJUAN")
);

$jumlahheader= count($arrinfo);
?>

<table border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<td colspan="<?=$jumlahheader?>" style="border:none">
				<center>
					<img src="https://simace.kaltimbkd.info/assesment/WEB/images/logo-judul.png" >
					PEMERINTAH PROVINSI KALIMANTAN TIMUR<br>
					<b>BADAN KEPEGAWAIAN DAERAH<br>
					UPTD. PENILAIAN KOMPETENSI PEGAWAI</b>
				</center>
			</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<td colspan="<?=$jumlahheader?>" style="border:none">
				<center><b>Rekap HCDP Individual dilingkungan Pemerintah Provinsi Kalimantan Timur</b></center>
			</td>
		</tr>
		<tr>
			<?
			foreach ($arrinfo as $key => $value)
			{
				$infotext= $value["label"];
			?>
			<th style='background-color: lightyellow;'><?=$infotext?></th>
			<?
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?
		foreach ($arrrealisasi as $key => $value)
		{
			$vpegawaiid= $value["PEGAWAI_ID"];
		?>
		<tr>
			<?
			foreach ($arrinfo as $k => $v)
			{
				$infotext= $arrrealisasi[$key][$v["FIELD"]];
			?>
				<th class="str" style='background-color: lightyellow;'><?=$infotext?></th>
			<?
			}
			?>
		</tr>
		<?
		}
		?>
	</tbody>
</table>