<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalAsesor.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$tempAsesorId= $userLogin->userAsesorId;
$reqTanggalTes= httpFilterGet("reqTanggalTes");

if($tempAsesorId == "")
{
	echo '<script language="javascript">';
	echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
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

//$dateNow= date("d-m-Y");

$index_loop= 0;
$arrAsesor="";
//$statement= " AND DATE_FORMAT(A.TANGGAL_TES, '%d-%m-%Y') = '".$reqTanggalTes."' AND (A.STATUS_PENILAIAN = '' OR A.STATUS_PENILAIAN IS NULL) AND COALESCE(B.JUMLAH_PESERTA,0) > 0 AND A.JADWAL_TES_ID IN (SELECT X.JADWAL_TES_ID FROM jadwal_asesor X WHERE X.ASESOR_ID = ".$tempAsesorId." GROUP BY X.JADWAL_TES_ID) ";
//$statement= " AND DATE_FORMAT(A.TANGGAL_TES, '%d-%m-%Y') = '".$reqTanggalTes."' AND COALESCE(B.JUMLAH_PESERTA,0) > 0 AND A.JADWAL_TES_ID IN (SELECT X.JADWAL_TES_ID FROM jadwal_asesor X WHERE X.ASESOR_ID = ".$tempAsesorId." GROUP BY X.JADWAL_TES_ID) ";
$statement= " AND DATE_FORMAT(A.TANGGAL_TES, '%d-%m-%Y') = '".$reqTanggalTes."' ";
$set= new JadwalAsesor();
$set->selectByParamsPegawaiAsesor(array(), -1,-1, $statement, $tempAsesorId);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrAsesor[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
	$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrAsesor[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrAsesor[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
	$arrAsesor[$index_loop]["BATCH"]= $set->getField("BATCH");
	$arrAsesor[$index_loop]["ACARA"]= $set->getField("ACARA");
	$arrAsesor[$index_loop]["TEMPAT"]= $set->getField("TEMPAT");
	$arrAsesor[$index_loop]["ALAMAT"]= $set->getField("ALAMAT");
	$arrAsesor[$index_loop]["JUMLAH_PESERTA"]= $set->getField("JUMLAH_PESERTA");
	$arrAsesor[$index_loop]["KODE"]= $set->getField("KODE");
	$arrAsesor[$index_loop]["KELOMPOK_RUANGAN_NAMA"]= $set->getField("KELOMPOK_RUANGAN_NAMA");
	$arrAsesor[$index_loop]["TANGGAL_TES"]= dateToPageCheck($set->getField("TANGGAL_TES"));
	$index_loop++;
}
$jumlah_asesor= $index_loop;
//$jumlah_asesor= 0;
?>
<table>
<?
$tempAcara= "";
for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
{
	if($tempAcara == $arrAsesor[$checkbox_index]["ACARA"]){}
	else
	{
?>
	<tr>
		<td>Acara</td>
		<td colspan="4">: <?=$arrAsesor[$checkbox_index]["ACARA"]?></td>
	</tr>
	<tr>
		<td>Tempat</td>
		<td colspan="4">: <?=$arrAsesor[$checkbox_index]["TEMPAT"]?></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td colspan="4">: <?=$arrAsesor[$checkbox_index]["ALAMAT"]?></td>
	</tr>
	<tr>
		<th>Tanggal Tes</th>
		<th>Kode</th>
		<th>Ruang</th>
		<th>Jumlah Peserta</th>
		<th>&nbsp;</th>
	</tr>
<?
	}
?>
	<tr>
		<td><?=$arrAsesor[$checkbox_index]["TANGGAL_TES"]?></td>
		<td><?=$arrAsesor[$checkbox_index]["KODE"]?></td>
		<td><?=$arrAsesor[$checkbox_index]["KELOMPOK_RUANGAN_NAMA"]?></td>
		<td><?=$arrAsesor[$checkbox_index]["JUMLAH_PESERTA"]?></td>
		<td>
        <?
        /*$statement= " AND A.JADWAL_TES_ID = ".$arrAsesor[$checkbox_index]["JADWAL_TES_ID"]." AND B.ASESOR_ID = ".$tempAsesorId." AND B.JADWAL_ASESOR_ID = ".$arrAsesor[$checkbox_index]["JADWAL_ASESOR_ID"];
        $set= new JadwalAsesor();
        $set->selectByParamsPenggalian(array(), -1,-1, $statement);
        //echo $set->query;exit;
		$set->firstRow();
		$tempJadwalTesId= $set->getField("JADWAL_TES_ID");
		unset($set);
		
		if($tempJadwalTesId == ""){}
		else
		{*/
		?>
    	<?
		if($arrAsesor[$checkbox_index]["ASPEK_ID"] == "1")
		{
        ?>
        <a href="kegiatan_potensi.php?reqJadwalTesId=<?=$arrAsesor[$checkbox_index]["JADWAL_TES_ID"]?>&reqJadwalAsesorId=<?=$arrAsesor[$checkbox_index]["JADWAL_ASESOR_ID"]?>">Lihat Data Kegiatan <i class="fa fa-chevron-circle-right"></i></a>
        <?
		}
		else
		{
        ?>
        <a href="kegiatan.php?reqJadwalTesId=<?=$arrAsesor[$checkbox_index]["JADWAL_TES_ID"]?>&reqJadwalAsesorId=<?=$arrAsesor[$checkbox_index]["JADWAL_ASESOR_ID"]?>">Lihat Data Kegiatan <i class="fa fa-chevron-circle-right"></i></a>
        <?
		}
        ?>
        </td>
	</tr>
<?
$tempAcara= $arrAsesor[$checkbox_index]["ACARA"];
}
?>
</table>
<div style="margin:20px">&nbsp;</div>