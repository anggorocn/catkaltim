<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalAsesor.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$tempAsesorId= $userLogin->userAsesorId;
$reqTanggalTes= httpFilterGet("reqTanggalTes");
// echo $tempAsesorId;exit();
if($tempAsesorId == "")
{
	echo '<script language="javascript">';
	echo 'alert("anda tidak memiliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
	echo 'top.location.href = "../main/login.php";';
	echo '</script>';		
	exit;
}



$set= new Asesor();
$set->selectByParams(array(), -1,-1, " AND A.ASESOR_ID = ".$tempAsesorId);
$set->firstRow();
$tempAsesorNama= $set->getField("NAMA");
unset($set);

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");
$arrAsesor="";

$statement= " AND TO_CHAR(TANGGAL_TES, 'DD-MM-YYYY') = '".$reqTanggalTes."'";
$setJadwal= new JadwalTes();
$setJadwal->selectByParams(array(), -1,-1, $statement);
 // echo $setJadwal->query;exit;
$index_loop= 0;
while($setJadwal->nextRow())
{
	$reqJadwalTesId= $setJadwal->getField("JADWAL_TES_ID");

	//$dateNow= date("d-m-Y");
	
	
	$statement= " AND JA.JADWAL_TES_ID = ".$reqJadwalTesId." ";
	$set= new JadwalAsesor();
	$set->selectByParamsDataAsesorPegawai($statement, $tempAsesorId);
	// echo $set->query;
	
	while($set->nextRow())
	{

		$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
		$arrAsesor[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
		$arrAsesor[$index_loop]["ACARA"]= $setJadwal->getField("ACARA");
		$arrAsesor[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
		$arrAsesor[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
		$arrAsesor[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
		$index_loop++;
		$jumlah_asesor= $index_loop;
	}
	
}

// print_r($jumlah_asesor);exit;
// $set->firstRow();
// $reqJadwalTesId= $set->getField("JADWAL_TES_ID");

// //$dateNow= date("d-m-Y");
// $index_loop= 0;
// $arrAsesor="";
// $statement= " AND JA.JADWAL_TES_ID = ".$reqJadwalTesId;
// $set= new JadwalAsesor();
// $set->selectByParamsDataAsesorPegawai($statement, $tempAsesorId);
// // echo $set->query;exit;
// while($set->nextRow())
// {
// 	$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
// 	$arrAsesor[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
// 	$arrAsesor[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
// 	$arrAsesor[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
// 	$arrAsesor[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
// 	$index_loop++;
// }
// $jumlah_asesor= $index_loop;
//$jumlah_asesor= 0;
?>
<table class="profil">
<tr>
	<th>Acara</th>
	<th>No Urut</th>
	<th>Nip</th>
	<th>Nama Peserta</th>
	<th></th>
</tr>
<?
for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
{
	$reqJadwalTesId= $arrAsesor[$checkbox_index]["JADWAL_TES_ID"];
	$reqPegawaiId= $arrAsesor[$checkbox_index]["PEGAWAI_ID"];
?>
	<tr>
		<td><?=$arrAsesor[$checkbox_index]["ACARA"]?></td>
		<td><?=$arrAsesor[$checkbox_index]["NOMOR_URUT_GENERATE"]?></td>
		<td><?=$arrAsesor[$checkbox_index]["NIP_BARU"]?></td>
		<td><?=$arrAsesor[$checkbox_index]["NAMA_PEGAWAI"]?></td>
		<td>
        <a href="penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>">Penilaian <i class="fa fa-chevron-circle-right"></i></a>
        </td>
	</tr>
<?
}
?>
</table>
<div style="margin:20px">&nbsp;</div>