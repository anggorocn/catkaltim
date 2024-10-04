<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");
include_once("../WEB/classes/base/RekapAsesor.php");


/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data jadwal terlebih dahulu.');";	
	echo "window.parent.location.href = 'master_jadwal_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

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

$index_loop=0;
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set= new RekapAsesor();
$set->selectByParamsPenggalianPegawai(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  // $arrPegawai[$index_loop]["ROWID"]= $set->getField("PEGAWAI_ID")."-".$set->getField("PENGGALIAN_ID")."-".$set->getField("JADWAL_ASESOR_ID");
  $arrPegawai[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
  $arrPegawai[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
  $arrPegawai[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
  $arrPegawai[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
  $arrPegawai[$index_loop]["NAMA_ASESOR"]= $set->getField("NAMA_ASESOR");
  $arrPegawai[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrPegawai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
  $index_loop++;
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
  $arrNilaiPegawai[$index_loop]["ROWID"]= $set->getField("PEGAWAI_ID")."-".$set->getField("PENGGALIAN_ID")."-".$set->getField("JADWAL_ASESOR_ID");
  $arrNilaiPegawai[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
  $arrNilaiPegawai[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrNilaiPegawai[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
  $arrNilaiPegawai[$index_loop]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");
  $index_loop++;
}
$jumlah_nilai_pegawai= $index_loop;
// print_r($arrPegawai);exit();

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
	<div id="header-tna-detil">Rekap Asesor <span>Penilaian</span></div>
    <div id="konten">
    <input type="button" onclick="setcetak()" value="Cetak" />

    <table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
        <thead class="altrowstable">
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

            	$tempValuePenggalianInfoId= "Tidak";
            	if($reqPenggalianId == $reqPegawaiPenggalianId)
            	{
            		$tempValuePenggalianInfoId= "Ya";
            	}

        		$reqPegawaiRowId= $reqPegawaiId."-".$reqPenggalianId."-".$reqPegawaiJadwalAsesorId;
            	$reqNilaiPegawai= "0";
            	$arrayKey= in_array_column($reqPegawaiRowId, "ROWID", $arrNilaiPegawai);
            	// print_r($arrayKey);exit;
                if($arrayKey == ''){}
                else
                {
                	$index_row= $arrayKey[0];
  					$reqNilaiPegawai= $arrNilaiPegawai[$index_row]["JUMLAH_DATA"];
                }

                if($tempValuePenggalianInfoId == "Ya")
                	$tempValuePenggalianNilaiInfoId= "Belum";
                else
                	$tempValuePenggalianNilaiInfoId= "-";

            	if($reqNilaiPegawai > 0)
            	{
            		$tempValuePenggalianNilaiInfoId= "Sudah";
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
            <!-- <th colspan="<?=$colspanpenggalian?>"><?=$reqPegawaiNoUrut.". ".$reqPegawaiNama."-".$reqPegawaiId?></th> -->
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

            	$tempValuePenggalianInfoId= "Tidak";
            	if($reqPenggalianId == $reqPegawaiPenggalianId)
            	{
            		$tempValuePenggalianInfoId= "Ya";
            	}

        		$reqPegawaiRowId= $reqPegawaiId."-".$reqPenggalianId."-".$reqPegawaiJadwalAsesorId;
            	$reqNilaiPegawai= "0";
            	$arrayKey= in_array_column($reqPegawaiRowId, "ROWID", $arrNilaiPegawai);
            	// print_r($arrayKey);exit;
                if($arrayKey == ''){}
                else
                {
                	$index_row= $arrayKey[0];
  					$reqNilaiPegawai= $arrNilaiPegawai[$index_row]["JUMLAH_DATA"];
                }

                if($tempValuePenggalianInfoId == "Ya")
                	$tempValuePenggalianNilaiInfoId= "Belum";
                else
                	$tempValuePenggalianNilaiInfoId= "-";

            	if($reqNilaiPegawai > 0)
            	{
            		$tempValuePenggalianNilaiInfoId= "Sudah";
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
    
    <script type="text/javascript">
    function setcetak()
	{
		opUrl= "jadwal_rekap_asesor_pegawai_excel.php?reqId=<?=$reqId?>";
		newWindow = window.open(opUrl, 'Cetak');
		newWindow.focus();
	}

    $(function ()
    {
      /*$('#link-table td:first-child').hide();
      $('#link-table tr').hover(function ()
      {
        $(this).toggleClass('Highlight');
      });
  
      $('#link-table tr').click(function ()
      {
		var id= $(this).find('td a').attr('href');
		if(typeof id == "undefined" || id == ''){}
		else
		{
			parent.setShowHideMenu(1);
        	parent.frames['mainFrameDetil'].location.href = $(this).find('td a').attr('href');
		}
      });*/
    });
  </script>
</div>
</div>
</body>
</html>
</html>