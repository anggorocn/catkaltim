<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base/JadwalPegawaiDetilKomentar.php");

/* create objects */
//$set = new Asesor();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$pegawai = new Kelautan();
$pegawai->selectByParamsMonitoringPegawai(array("A.PEGAWAI_ID" => $reqPegawaiId));
$pegawai->firstRow();
$tempNama= $pegawai->getField("NAMA");
$tempJabatanSaatIni= $pegawai->getField("NAMA_JAB_STRUKTURAL");
$tempUnitKerjaSaatIni= $pegawai->getField("SATKER");

$index_loop= 0;
$arrDataAtribut="";
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND C.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new JadwalPegawaiDetilKomentar();
$set->selectByParamsAsesorPenilaianAtribut(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrDataAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$index_loop++;
}
$jumlah_pegawai_atribut= $index_loop;
//print_r($arrDataAtribut);exit;

$index_loop= 0;
$arrDataAsesor="";
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new JadwalPegawaiDetilKomentar();
$set->selectByParamsAsesorPenilaianAtributKomentar(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAsesor[$index_loop]["ID"]= $set->getField("LEVEL_ID")."-".$set->getField("INDIKATOR_ID")."-".$set->getField("JADWAL_TES_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("PEGAWAI_ID")."-".$set->getField("ASESOR_KOMENTAR_ID");
	$arrDataAsesor[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
	$arrDataAsesor[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
	$arrDataAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrDataAsesor[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrDataAsesor[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrDataAsesor[$index_loop]["ASESOR_KOMENTAR_ID"]= $set->getField("ASESOR_KOMENTAR_ID");
	$arrDataAsesor[$index_loop]["ASESOR_KOMENTAR_NAMA"]= $set->getField("ASESOR_KOMENTAR_NAMA");
	$index_loop++;
}
$jumlah_asesor= $index_loop;

$index_loop= 0;
$arrDataAsesorKomentar="";
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new JadwalPegawaiDetilKomentar();
$set->selectByParamsAsesorPenilaianAtributKomentarDetil(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAsesorKomentar[$index_loop]["ID"]= $set->getField("LEVEL_ID")."-".$set->getField("INDIKATOR_ID")."-".$set->getField("JADWAL_TES_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("PEGAWAI_ID")."-".$set->getField("ASESOR_ID");
	$arrDataAsesorKomentar[$index_loop]["JADWAL_PEGAWAI_DETIL_KOMENTAR_ID"]= $set->getField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID");
	$arrDataAsesorKomentar[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
	$arrDataAsesorKomentar[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
	$arrDataAsesorKomentar[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrDataAsesorKomentar[$index_loop]["KETERANGAN"]= $set->getField("KETERANGAN");
	$arrDataAsesorKomentar[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrDataAsesorKomentar[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrDataAsesorKomentar[$index_loop]["ASESOR_ID"]= $set->getField("ASESOR_ID");
	$arrDataAsesorKomentar[$index_loop]["ASESOR_DIKOMENTAR"]= $set->getField("ASESOR_DIKOMENTAR");
	$arrDataAsesorKomentar[$index_loop]["ASESOR_KOMENTAR_ID"]= $set->getField("ASESOR_KOMENTAR_ID");
	$arrDataAsesorKomentar[$index_loop]["ASESOR_KOMENTAR_NAMA"]= $set->getField("ASESOR_KOMENTAR_NAMA");
	$index_loop++;
}
$jumlah_asesor_komentar= $index_loop;
//print_r($arrDataAsesorKomentar);exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
	<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../WEB/js/globalfunction.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script>
    
    <style>
		/* UNTUK TABLE GRADIENT STYLE*/
		.gradient-style th {
		font-size: 12px;
		font-weight:400;
		background:#b9c9fe url(images/gradhead.png) repeat-x;
		border-top:2px solid #d3ddff;
		border-bottom:1px solid #fff;
		color:#039;
		padding:8px;
		}
		
		.gradient-style td {
		font-size: 12px;
		border-bottom:1px solid #fff;
		color:#669;
		border-top:1px solid #fff;
		background:#e8edff url(images/gradback.png) repeat-x;
		padding:8px;
		}
		
		.gradient-style tfoot tr td {
		background:#e8edff;
		font-size: 14px;
		color:#99c;
		}
		
		.gradient-style tbody tr:hover td {
		background:#d0dafd url(images/gradhover.png) repeat-x;
		color:#339;
		}
		
		.gradient-style {
		font-family: 'Open SansRegular';
		font-size: 14px;
		width:480px;
		text-align:left;
		border-collapse:collapse;
		margin:0px 0px 0px 10px;
		}
	</style>
    
	<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
</head>
<body class="bg-form" style="overflow-x:scroll;">
	<div id="header-tna-detil">Assesment <span>Meeting</span></div>
    <div id="konten">
    <table class="gradient-style" style="width:100%; margin-left:-1px">
        <thead class="altrowstable">
        	<tr class="terang">
                <td width="10%">Nama</td>
                <td width="2%">:</td>
                <td>
                	<?=$tempNama?>
                </td>
            </tr>
            <tr class="gelap">
                <td>Jabatan</td>
                <td>:</td>
                <td>
                	<?=$tempJabatanSaatIni?>
                </td>
            </tr>
       		<tr class="terang">
                <td>Unit Kerja</td>
                <td>:</td>
                <td>
                	<?=$tempUnitKerjaSaatIni?>
                </td>
            </tr>
        </thead>
    </table>
    <?
	for($index_atribut=0;$index_atribut < $jumlah_pegawai_atribut;$index_atribut++)
	{
		$tempAtributId= $arrDataAtribut[$index_atribut]["ATRIBUT_ID"];
		$tempAtributNama= $arrDataAtribut[$index_atribut]["ATRIBUT_NAMA"];
	?>
    <table class="gradient-style" style="width:100%; margin-left:-1px">
        <thead class="altrowstable">
        <tr>
        	<th style="width:25%">Atribut</th>
            <th>Asessment Komentar</th>
        </tr>
        </thead>
        <tbody>
       	<tr>
        	<td style="vertical-align:middle"><?=$tempAtributNama?></td>
            <td>
    			<?
				$arrayKey= '';
				$arrayKey= in_array_column($tempAtributId, "ATRIBUT_ID", $arrDataAsesor);
				//print_r($arrayKey);exit;
				if($arrayKey == ''){}
				else
				{
					for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
					{
						$index_row= $arrayKey[$index_detil];
						$tempIdAsesor= $arrDataAsesor[$index_row]["ID"];
						$arrDataAsesor[$index_row]["LEVEL_ID"];
						$arrDataAsesor[$index_row]["INDIKATOR_ID"];
						$arrDataAsesor[$index_row]["JADWAL_TES_ID"];
						$arrDataAsesor[$index_row]["ATRIBUT_ID"];
						$arrDataAsesor[$index_row]["PEGAWAI_ID"];
						$arrDataAsesor[$index_row]["ASESOR_KOMENTAR_ID"];
						$tempNamaAsesorKomentar= $arrDataAsesor[$index_row]["ASESOR_KOMENTAR_NAMA"];
	
				?>
            	<table class="gradient-style" style="width:100%; margin-left:-10px; margin-top:-5px">
                	<tr>
                    	<td style="width:25%; border:none !important;"><?=$tempNamaAsesorKomentar?></td>
                    	<td style="border:none !important;">
                        <?
						$arrayKeyDetil= '';
						$arrayKeyDetil= in_array_column($tempIdAsesor, "ID", $arrDataAsesorKomentar);
						//print_r($arrayKeyDetil);exit;
						if($arrayKeyDetil == ''){}
						else
						{
							for($index_detil_komentar=0; $index_detil_komentar < count($arrayKeyDetil); $index_detil_komentar++)
							{
								$index_row_detil= $arrayKey[$index_detil_komentar];
								$arrDataAsesorKomentar[$index_row_detil]["ID"];
								$tempDetilId= $arrDataAsesorKomentar[$index_row_detil]["JADWAL_PEGAWAI_DETIL_KOMENTAR_ID"];
								$arrDataAsesorKomentar[$index_row_detil]["LEVEL_ID"];
								$arrDataAsesorKomentar[$index_row_detil]["INDIKATOR_ID"];
								$arrDataAsesorKomentar[$index_row_detil]["JADWAL_TES_ID"];
								$tempDetilKeterangan= $arrDataAsesorKomentar[$index_row_detil]["KETERANGAN"];
								$arrDataAsesorKomentar[$index_row_detil]["ATRIBUT_ID"];
								$arrDataAsesorKomentar[$index_row_detil]["PEGAWAI_ID"];
								$tempDetil= $arrDataAsesorKomentar[$index_row_detil]["ASESOR_ID"];
								$tempDetilDikomentar= $arrDataAsesorKomentar[$index_row_detil]["ASESOR_DIKOMENTAR"];
								$tempDetil= $arrDataAsesorKomentar[$index_row_detil]["ASESOR_KOMENTAR_ID"];
								$tempDetilKomentar= $arrDataAsesorKomentar[$index_row_detil]["ASESOR_KOMENTAR_NAMA"];
								
								$image_komentar= "icon_centang";
								if($tempDetilId == ""){}
								else
								{
									if($tempDetilKeterangan == "")
									{
										$tempDetilKeterangan= "";
									}
									else
									{
										$tempDetilKeterangan= ", ".$tempDetilKeterangan;
										$image_komentar= "icon_cross";
									}
								}
														
						?>
                        	<img src="../WEB/images/<?=$image_komentar?>.png" width="15" height="15">
							<?="Terhadap ".$tempDetilDikomentar.$tempDetilKeterangan?><br/>
						<?
							}
						}
						?>
                        </td>
                    </tr>
                </table>
                <?
					}
				}
                ?>
            </td>
        </tr>
        </tbody>
    </table>
    <?
	}
    ?>
    <?php /*?><table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
        <thead class="altrowstable">
            <tr>
              <th style="width:50px">Kode</th>
              <th style="width:300px">Metode</th>
              <th style="width:150px">Tanggal Tes</th>
            </tr>
       </thead>
       <tbody class="example altrowstable" id="alternatecolor"> 
		<? 
		for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
		{
			$tempAsesorId= $arrAsesor[$checkbox_index]["ASESOR_ID"];
			$arrAsesor[$checkbox_index]["NAMA_ASESI"];
			$tempKode= $arrAsesor[$checkbox_index]["KODE"];
			$tempMetode= $arrAsesor[$checkbox_index]["METODE"];
			$tempTanggalTes= $arrAsesor[$checkbox_index]["TANGGAL_TES"];
			$tempPenggalianId= $arrAsesor[$checkbox_index]["PENGGALIAN_ID"];
			$tempJadwalTesId= $arrAsesor[$checkbox_index]["JADWAL_TES_ID"];
		?>
        <tr>
        	<td><a href="histori_asesor_add_detil.php?reqAsesorId=<?=$tempAsesorId?>&reqTanggalTes=<?=$tempTanggalTes?>&reqJadwalTesId=<?=$tempJadwalTesId?>&reqPenggalianId=<?=$tempPenggalianId?>">link data</a></td>
            <td><?=$tempKode?></td>
            <td><?=$tempMetode?></td>
            <td><?=dateToPageCheck($tempTanggalTes)?></td>
		<? 
		}
		?>
        </tbody>
    </table><?php */?>
    
    <script type="text/javascript">
    $(function ()
    {
      // Hide the first cell for JavaScript enabled browsers.
      $('#link-table td:first-child').hide();

      // Apply a class on mouse over and remove it on mouse out.
      $('#link-table tr').hover(function ()
      {
        $(this).toggleClass('Highlight');
      });
  
      // Assign a click handler that grabs the URL 
      // from the first cell and redirects the user.
      $('#link-table tr').click(function ()
      {
		var id= $(this).find('td a').attr('href');
		if(typeof id == "undefined" || id == ''){}
		else
		{
			parent.setShowHideMenu(1);
        	parent.frames['mainFrameDetilPop'].location.href = $(this).find('td a').attr('href');
		}
      });
    });
  </script>
</div>
</div>
<?
if($reqMode == 'simpan' || $reqMode == 'error' || $reqMode == 'hapus'){
	echo '<script language="javascript">';
	echo "$.jGrowl('".$alertStatus."');";
	echo '</script>';
	//$alertMsg = "Data Berhasil Tersimpan";
}
?>
</body>
</html>
</html>