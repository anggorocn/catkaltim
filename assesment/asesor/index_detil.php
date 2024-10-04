<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/JadwalPenggalianPegawai.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalTesSimulasiPegawai.php");
include_once("../../WEB/classes/base-diklat/Peserta.php");

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$tempAsesorId= $userLogin->userAsesorId;
$reqTanggalTes= httpFilterGet("reqTanggalTes");
$reqTanggalTespecah=explode('-', $reqTanggalTes);
// echo $tempAsesorId;exit;

if(strlen($reqTanggalTespecah[0])!=2){
	$reqTanggalTespecah[0]='0'.$reqTanggalTespecah[0];
}

if(strlen($reqTanggalTespecah[1])!=2){
	$reqTanggalTespecah[1]='0'.$reqTanggalTespecah[1];
}
// print_r($reqTanggalTespecah);

$reqTanggalTes= $reqTanggalTespecah[0].'-'.$reqTanggalTespecah[1].'-'.$reqTanggalTespecah[2];


$reqMode=httpFilterGet("reqMode");
// echo $tempAsesorId;exit();
// if($tempAsesorId == "")
// {
// 	echo '<script language="javascript">';
// 	echo 'alert("anda tidak memiliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
// 	echo 'top.location.href = "../main/login.php";';
// 	echo '</script>';		
// 	exit;
// }



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
	
	$statement= " AND JA.JADWAL_TES_ID = ".$reqJadwalTesId." ";
	$set= new JadwalAsesor();
	
	$set->selectByParamsDataAsesorPegawaiSuper($statement, $tempAsesorId);
 // echo $set->query;exit;
	
	while($set->nextRow())
	{

		$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
		$arrAsesor[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
		$arrAsesor[$index_loop]["ACARA"]= $setJadwal->getField("ACARA");
		$arrAsesor[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
		$arrAsesor[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
		$arrAsesor[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
		$arrAsesor[$index_loop]["ESELON"]= $set->getField("last_eselon_id");
		$arrAsesor[$index_loop]["ASESOR_ID"]= $set->getField("asesor_id");

		$statementTugas= " and A.ASESOR_ID = ".$tempAsesorId." and JA.JADWAL_TES_ID = ".$reqJadwalTesId." and b.pegawai_id=".$set->getField("PEGAWAI_ID");
		$setTugas= new JadwalAsesor();
		$setTugas->selectByParamsTugas($statementTugas);	
		// echo $setTugas->query; exit;
		$tugas="";
		while($setTugas->nextRow())
		{
			if($tugas==''){
				$tandabaca="";
			}
			else{
				$tandabaca=", ";
			}
			if($setTugas->getField("kode")=='CBI'){
				$tugas=$tugas.$tandabaca.$setTugas->getField("kode").", NILAI AKHIR, KESIMPULAN, KOMPETENSI";
			}
			else{
				$tugas=$tugas.$tandabaca.$setTugas->getField("kode");
			}
		}
		$arrAsesor[$index_loop]["TUGAS"]= $tugas;

		$index_loop++;
		$jumlah_asesor= $index_loop;
	}
	
}
// print_r($arrAsesor);exit;

$reqArrJadwalTesId= "";
$statement= " AND TO_CHAR(TANGGAL_TES, 'DD-MM-YYYY') = '".$reqTanggalTes."'";
$set= new JadwalTes();
$set->selectByParams(array(),-1,-1, $statement);
// echo $set->query;exit;
// $set->firstRow();
// $reqJadwalTesId= $set->getField("JADWAL_TES_ID");
while($set->nextRow())
{
	$separator= "";
	if($reqArrJadwalTesId == ""){}
	else
	$separator= ",";

	$reqArrJadwalTesId.= $separator.$set->getField("JADWAL_TES_ID");
}

$statement= " AND A.JADWAL_TES_ID IN (".$reqArrJadwalTesId.")";
$set= new JadwalPenggalianPegawai();
$set->selectbyparamspenggalian(array(), -1, -1, $statement);
// echo $set->query;exit;
$arrpenggalian=[];
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrdata["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
	$arrdata["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
	array_push($arrpenggalian, $arrdata);
}
$jumlahpenggalian= count($arrpenggalian);
// print_r($arrpenggalian);exit;

$statement= " AND A.JADWAL_TES_ID IN (".$reqArrJadwalTesId.")";
$set= new JadwalPenggalianPegawai();
$set->selectbyparamsNew(array(), -1, -1, $statement);
// echo $set->query;exit;
$arrpegawaipenggalian=[];
while($set->nextRow())
{
	$jadwaltesid= $set->getField("JADWAL_TES_ID");
	$penggalianid= $set->getField("PENGGALIAN_ID");
	$pegawaiid= $set->getField("PEGAWAI_ID");
	$arrdata= [];
	$arrdata["KEY"]= $jadwaltesid."-".$penggalianid."-".$pegawaiid;
	$arrdata["JADWAL_TES_ID"]= $jadwaltesid;
	$arrdata["PENGGALIAN_ID"]= $penggalianid;
	$arrdata["PEGAWAI_ID"]= $pegawaiid;
	if($set->getField("total_terjawab")==$set->getField("patokan")){
		$arrdata["STATUS"]= '1';
	}
	else if($set->getField("total_terjawab")=='0'){
		$arrdata["STATUS"]= '0';
	}
	else{
		$arrdata["STATUS"]= '2';
	}

	array_push($arrpegawaipenggalian, $arrdata);
}


$statement= " AND b.JADWAL_TES_ID IN (".$reqArrJadwalTesId.")";
$set= new JadwalPenggalianPegawai();
$set->selectbyparamsPsikotest(array(), -1, -1, $statement);
// echo $set->query; exit;
while($set->nextRow())
{
	$jadwaltesid= $set->getField("JADWAL_TES_ID");
	$penggalianid= $set->getField("PENGGALIAN_ID");
	$pegawaiid= $set->getField("PEGAWAI_ID");
	$arrdata= [];
	$arrdata["KEY"]= $jadwaltesid."-".$penggalianid."-".$pegawaiid;
	$arrdata["JADWAL_TES_ID"]= $jadwaltesid;
	$arrdata["PENGGALIAN_ID"]= $penggalianid;
	$arrdata["PEGAWAI_ID"]= $pegawaiid;
	if($set->getField("total_terjawab")==$set->getField("patokan")){
		$arrdata["STATUS"]= '1';
	}
	else if($set->getField("total_terjawab")=='0'){
		$arrdata["STATUS"]= '0';
	}
	else{
		$arrdata["STATUS"]= '2';
	}

	array_push($arrpegawaipenggalian, $arrdata);
}
// print_r($arrpegawaipenggalian);exit;

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

<style type="text/css">
	/* Style the tab */
	.tab {
	  overflow: hidden;
/*	  border: 1px solid #ccc;*/
	  background-color: white;
	}

	/* Style the buttons inside the tab */
	.tab button {
	  background-color: white;
	  float: left;
	  border: none;
	  outline: none;
	  cursor: pointer;
	  padding: 5px 16px;
	  transition: 0.3s;
	  font-size: 17px;
		border-bottom: solid gray 5px ;
	}

	/* Change background color of buttons on hover */
	.tab button:hover {
		border-bottom: solid yellow 5px ;
	}

	/* Create an active/current tablink class */
	.tab button.active {
		border-bottom: solid green 5px ;
/*	  background-color: #ccc;*/
	}

	/* Style the tab content */
	.tabcontent {
	  display: none;
	  padding: 6px 12px;
/*	  border: 1px solid #ccc;*/
	  border-top: none;
	}
</style>
<div class="tab">
  <button style="margin-left: 15px;" class="tablinks active" onclick="openCity(event, 'London')">Penilaian</button>
  <button style="margin-left: 15px;" class="tablinks" onclick="openCity(event, 'Paris')">DRH</button>
</div>

<div id="London" class="tabcontent" style="display: block;background-color: white;">
	<table class="profil">
		<tr>
			<th rowspan="2">Acara</th>
			<th rowspan="2">No Urut</th>
			<th rowspan="2">Nip</th>
			<th rowspan="2">Nama Peserta</th>
			<th colspan="<?=$jumlahpenggalian?>" style="text-align: center;">Penggalian</th>
			<th rowspan="2">Detil</th>
		</tr>
		<tr>
			<?
			foreach ($arrpenggalian as $key => $value)
			{
				$infolabel= $value["PENGGALIAN_KODE"];
			?>
			<th style="text-align: center;"><?=$infolabel?><br><button onclick="downloadCetakanRekap('<?=$infolabel?>')" style="background: url('../WEB/images/down_icon.png');height: 15px;width: 15px;border: none" ></button> </th>
			<?
			}
			?>
		</tr>
		<?
		$cekUJian='';
		$cekNIP='';
		for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
		{
			$reqJadwalTesId= $arrAsesor[$checkbox_index]["JADWAL_TES_ID"];
			$reqPegawaiId= $arrAsesor[$checkbox_index]["PEGAWAI_ID"];
			$reqAsesorId= $arrAsesor[$checkbox_index]["ASESOR_ID"];
			$muncul='';
			
			if($reqMode==''){
				if(($cekUJian!=$reqJadwalTesId||$cekNIP!=$reqPegawaiId) && $reqAsesorId==$tempAsesorId){
					$muncul=1;
				}
			}
			else{
				if(($cekUJian!=$reqJadwalTesId||$cekNIP!=$reqPegawaiId)){
					$muncul=1;
				}
			}
			if($muncul==1){
				?>
					<tr>
						<!-- <?=$tempAsesorId?>-<?=$reqAsesorId?> -->
						<td><?=$arrAsesor[$checkbox_index]["ACARA"]?></td>
						<td><?=$arrAsesor[$checkbox_index]["NOMOR_URUT_GENERATE"]?></td>
						<td><?=$arrAsesor[$checkbox_index]["NIP_BARU"]?></td>
						<td><?=$arrAsesor[$checkbox_index]["NAMA_PEGAWAI"]?> <?if($tempAsesorId!=''){?>( <?=$arrAsesor[$checkbox_index]["TUGAS"]?> )<?}?></td>
						<?
						foreach ($arrpenggalian as $key => $value)
						{
							$infocari= $reqJadwalTesId."-".$value["PENGGALIAN_ID"]."-".$reqPegawaiId;
							$arraycari= in_array_column($infocari, "KEY", $arrpegawaipenggalian);
							// var_dump($infocari);
							$infostatus= "";
							if(!empty($arraycari))
							{
								$infostatus= $arrpegawaipenggalian[$arraycari[0]]["STATUS"];
								
							}
							// echo $infostatus;exit;

							$warna= '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>';
							if($infostatus == "1")
								$warna= '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>';
							else if($infostatus == "2")
								$warna= '<i class="fa fa-exclamation" aria-hidden="true" style="color:orange"></i>';

						?>
						<!-- <td style="background-color: <?=$warna?> !important"><?=$infocari?></td> -->
						<td style="text-align:center;"><?=$warna?></td>
						<?
						}
						?>
						<td style="width:15%">
				        <a href="penilaian_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>&reqTanggalTes=<?=$reqTanggalTes?>&reqMode=<?=$reqMode?>">Penilaian <i class="fa fa-chevron-circle-right"></i></a>
				        </td>
					</tr>
				<?
				$cekUJian=$reqJadwalTesId;
				$cekNIP=$reqPegawaiId;
			}
		}
		?>
	</table>
</div>

<div id="Paris" class="tabcontent" style="background-color: white;">
  <table class="profil" style="text-align:center;">
		<tr>
			<tr >
				<th scope="col" style="text-align:center;">Ujian</th>
				<th scope="col" style="text-align:center;">No</th>
			 	<th scope="col" style="text-align:center;">NIP</th>
				<th scope="col" style="text-align:center;">Nama Pegawai</th>
        <th scope="col" style="text-align:center;">DRH</th>
        <th scope="col" style="text-align:center;">CI</th>
        <th scope="col" style="text-align:center;">Q-Inta</th>
        <th scope="col" style="text-align:center;">Q-Kom</th>
        <!-- <th scope="col" >Portfolio</th> -->
        <th scope="col" style="text-align:center;">File</th>
        <th scope="col" style="text-align:center;">Kuisioner</th>
		</tr>
		<?
		for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
		{
			$reqJadwalTesId= $arrAsesor[$checkbox_index]["JADWAL_TES_ID"];
			$reqPegawaiId= $arrAsesor[$checkbox_index]["PEGAWAI_ID"];
			$tempFile2x= $arrJadwalAsesor[$checkbox_index]["LINK_FILE2"];
			$reqAsesorId= $arrAsesor[$checkbox_index]["ASESOR_ID"];
			$muncul='';
			$peserta= new Peserta();
			$peserta->selectByParamsDataPribadi(array(), -1,-1, " AND A.PEGAWAI_ID = ".$reqPegawaiId);
			// echo $peserta->query;exit;
			$peserta->firstRow();
			$infoeselonid= $peserta->getField("LAST_ESELON_ID");
			$reqJenjangJabatan= $peserta->getField("Jenjang_jabatan");
			$reqStatusPegawaiId= $peserta->getField("STATUS_PEGAWAI_ID");
			if($reqJenjangJabatan=='administrator'){
		    $totalQInta=6;
			}
			else if($reqJenjangJabatan=='pengawas'){
			    $totalQInta=5;
			}
			else{
			    $totalQInta=4;
			}
			
			if(($cekUJian!=$reqJadwalTesId||$cekNIP!=$reqPegawaiId)){
			
			?>
			<tr>
				<td style="width:25%;text-align: left;"><?=$arrAsesor[$checkbox_index]["ACARA"]?></td>
				<td><?=$arrAsesor[$checkbox_index]["NOMOR_URUT_GENERATE"]?></td>
				<td><?=$arrAsesor[$checkbox_index]["NIP_BARU"]?></td>
				<td style="width:25%;text-align: left;"><?=$arrAsesor[$checkbox_index]["NAMA_PEGAWAI"]?></td>
				<?

				$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId."";
				$drh= new JadwalTes();
				$drh->selectByParamsCekDrh(array(), -1,-1, $statement);
		                        // echo $drh->query;exit; 
				$drh->firstRow();
				$atasan=$drh->getField("ROWATASAN");
				$saudara=$drh->getField("ROWSAUDARA");
				$riwayatpendidikan=$drh->getField("ROWRIPEND");
				$pendidikannon=$drh->getField("ROWRIPENDNON");
				$riwayatjabatan=$drh->getField("ROWRIJAB");
				$bidangpek=$drh->getField("ROWBIDANGPEK");
				$datapek=$drh->getField("ROWDATAPEK");
				$kondisikerja=$drh->getField("ROWKONKERJA");
				$minharap=$drh->getField("ROWMINHARAP");
				$kekuatan=$drh->getField("ROWKEKKEL");

				if($atasan == 1 && $bidangpek > 0  && $datapek > 0 && $kondisikerja > 0 && $minharap > 0 && $kekuatan > 0)
				{
					$imgdrh = "down_icon";
				}
				else
				{
					$imgdrh = "gambardurung";
				}
				$sudah = new JadwalTes();
				$statementsoal= " AND B.PEGAWAI_ID = ".$reqPegawaiId."";
				$statementjawaban= " AND A.PEGAWAI_ID = ".$reqPegawaiId."";
				$statementqinta= " AND C.PEGAWAI_ID = ".$reqPegawaiId." AND C.TIPE_FORMULIR_ID = 2";
				$statementeselon= " AND C.PEGAWAI_ID = ".$reqPegawaiId." AND C.TIPE_FORMULIR_ID = 3";
				$statementpelaksana= " AND C.PEGAWAI_ID = ".$reqPegawaiId." AND C.TIPE_FORMULIR_ID = 4";

				$countcriticalsoal = $sudah->getCountByParamsCriticalSoal(array(), $statementsoal);
				$countcriticaljawaban = $sudah->getCountByParamsCriticalJawaban(array(), $statementjawaban);
				$countqinta = $sudah->getCountByParamsQInta(array(), $statementqinta);
				$counteselon = $sudah->getCountByParamsQInta(array(), $statementeselon);
				$countpel = $sudah->getCountByParamsQInta(array(), $statementpelaksana);

				$statementpelaksana= " AND C.PEGAWAI_ID = ".$tempPegawaiId." AND C.TIPE_FORMULIR_ID = 4";
				$countpel = $sudah->getCountByParamsQInta(array(), $statementpelaksana);

				if($countpel == 11)
					$imgpel= "down_icon";
				else
					$imgpel = "gambardurung";

				// print_r($countcriticaljawaban);

				if($countcriticalsoal == 12 && $countcriticaljawaban == 2 )
					$img = "down_icon";
				else
					$img = "gambardurung";
				if($countqinta == $totalQInta )
					$imginta = "down_icon";
				else
					$imginta = "gambardurung";
				if($counteselon == 19)
					$imgeselon= "down_icon";
				else
					$imgeselon = "gambardurung";
				if($countpel == 11)
					$imgpel= "down_icon";
				else
					$imgpel = "gambardurung";
				?>

				<td>
        	<?if($imgdrh == "down_icon")
					{
					?>
	        	<img  src="../WEB/images/<?=$imgdrh?>.png" onClick="CetakData(<?=$reqPegawaiId?>)" style="cursor:pointer;text-align: center" class="gambar" />
        	<?
        	}
        	else
        	{
        	?>
        		<img  src="../WEB/images/<?=$imgdrh?>.png" style="cursor:pointer;text-align: center" class="gambar" />
        	<?
        	}
        	?>
	      </td>

	      <td>
	       	<?if($img == "down_icon")
					{
					?>
	        	<img  src="../WEB/images/<?=$img?>.png" onClick="CetakDataPeserta(<?=$reqPegawaiId?>,'CI')" style="cursor:pointer;text-align: center" class="gambar" />
	       	<?
	       	}
        	else
        	{
        	?>
        		<img  src="../WEB/images/<?=$img?>.png" style="cursor:pointer;text-align: center" class="gambar" />
        	<?
        	}
        	?>
        </td>

        <td>
	        <?if($imginta == "down_icon")
					{
					?>
	        	<img  src="../WEB/images/<?=$imginta?>.png" onClick="CetakDataPeserta(<?=$reqPegawaiId?>,'QI')" style="cursor:pointer;text-align: center" class="gambar" />
        	<?
        	}
        	else
        	{
        	?>
        		<img  src="../WEB/images/<?=$imginta?>.png" style="cursor:pointer;text-align: center" class="gambar" />
        	<?
        	}
        	?>
        </td>
 	      <?if ($arrAsesor[$checkbox_index]["ESELON"]!=99){?>
	        <td>
		        <?if($imgeselon == "down_icon")
						{
						?>
	        		<img  src="../WEB/images/<?=$imgeselon?>.png" onClick="CetakDataPeserta(<?=$reqPegawaiId?>,'QK_Eselon')" style="cursor:pointer;text-align: center" class="gambar" />
	        	<?
	        	}
	        	else
	        	{
	        	?>
	        		<img  src="../WEB/images/<?=$imgeselon?>.png" style="cursor:pointer;text-align: center" class="gambar" />
	        	<?
	        	}
	        	?>
	        </td>
        <?}
        else
        {
				?>
        	<td>
        		<?if($imgpel == "down_icon")
        		{
        			?>
        			<img  src="../WEB/images/<?=$imgpel?>.png" onClick="CetakDataPeserta(<?=$reqPegawaiId?>,'QK_Pelaksana')" style="cursor:pointer;text-align: center" class="gambar" />
        			<?
        		}
        		else
        		{
        			?>
        			<img  src="../WEB/images/<?=$imgpel?>.png" style="cursor:pointer;text-align: center" class="gambar" />
        			<?
        		}
        		?>
        	</td>
        <?}
	        
        if($tempFile2x !== "")
        {
        ?>
        	<td><center><a target="_blank">File Pendukung</a></center></td>
        <?
    		}
        else
        {
        ?>
        	<td><center><a>-</a></center></td>	
        <?
	    	}
	    	?>
	    	<td><img  src="../WEB/images/down_icon.png" onClick="CetakKuisioner(<?=$reqPegawaiId?>)" style="cursor:pointer;text-align: center" class="gambar" /></td>
			</tr>
		<?
				$cekUJian=$reqJadwalTesId;
				$cekNIP=$reqPegawaiId;
			}
		}
		?>
	</table>
</div>


<div style="margin:20px">&nbsp;</div>

<script type="text/javascript">
function downloadCetakanRekap(val){
	// console.log('xxxxx'); return false;
  window.open('cetak_penilaian_rekap.php?reqTipe='+val+'&reqTanggalTes=<?=$reqTanggalTes?>&reqPegawaiId=<?=$reqPegawaiId?>&reqMode=<?=$reqMode?>&tempAsesorId=<?=$tempAsesorId?>', '_blank');
}
</script>

<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

function CetakData(pegawaiId)
	{
		// console.log(pegawaiId);

		var IdPegawai = pegawaiId;
		
		newWindow = window.open('../pengaturan/cetak_data_pribadi_pdf.php?reqPegawaiId='+IdPegawai+'&reqId=<?=$reqId?>', 'Cetak');
		newWindow.focus();
	}

	function CetakKuisioner(pegawaiId)
	{
		// console.log(pegawaiId);

		var IdPegawai = pegawaiId;
		
		newWindow = window.open('../pengaturan/cetak_kuisioner_pdf.php?reqPegawaiId='+IdPegawai+'&reqId=<?=$reqId?>', 'Cetak');
		newWindow.focus();
	}

	function CetakDataPeserta(pegawaiId,mode)
	{
		// console.log(pegawaiId);

		var IdPegawai = pegawaiId;
		var Mode = mode;
		
		newWindow = window.open('../pengaturan/cetak_data_pribadi_pdf.php?reqPegawaiId='+IdPegawai+'&reqId=<?=$reqId?>&reqMode='+Mode, 'Cetak');
		newWindow.focus();
	}
</script>