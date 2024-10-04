<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$set = new JadwalTesSimulasiAsesor();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqPenggalianId= httpFilterRequest("reqPenggalianId");
$reqJenisId= httpFilterRequest("reqJenisId");
$reqId= httpFilterRequest("reqId");
$reqRowId= httpFilterRequest("reqRowId");
$reqMode = httpFilterRequest("reqMode");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data jadwal terlebih dahulu.');";	
	echo "window.parent.location.href = 'master_jadwal_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

if($reqMode == "delete")
{
	if($reqPenggalianId == "")
	{
		$set_hapus= new JadwalTesSimulasiAsesor();
		$set_hapus->setField("JADWAL_TES_ID", $reqId);
		$set_hapus->setField("PUKUL_AWAL", $reqRowId);
		$set_hapus->delete();
		unset($set_hapus);
		
		echo '<script language="javascript">';
		echo "parent.frames['mainFrameDetil'].location.href = 'master_jadwal_add_simulasi_asesor.php?reqId=".$reqId."&reqJenisId=".$reqJenisId."';";
		echo '</script>';
	}
	else
	{
		$set_hapus= new JadwalTesSimulasiAsesor();
		$set_hapus->setField("JADWAL_TES_ID", $reqId);
		$set_hapus->setField("PENGGALIAN_ID", $reqPenggalianId);
		$set_hapus->deletePenggalian();
		unset($set_hapus);
		
		echo '<script language="javascript">';
		echo "parent.frames['mainFrameDetil'].location.href = 'master_jadwal_add_simulasi_asesor.php?reqId=".$reqId."&reqJenisId=".$reqJenisId."';";
		echo '</script>';
		
	}
}

/* DATA VIEW */
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
if($reqJenisId == "1")
{
	$statement.= " AND A.PENGGALIAN_ID IS NULL";
	$tempJudul= "Simulasi Acara";
}
elseif($reqJenisId == "2")
{
	$statement.= " AND A.PENGGALIAN_ID IS NOT NULL AND A.PENGGALIAN_ID > 0";
	$tempJudul= "Simulasi Tools";
}

$set->selectByParamsMonitoring(array(), -1, -1, $statement);
//echo $set->query;exit;

if($reqMode == 'simpan')	$alertStatus = "Data Berhasil Tersimpan";
elseif($reqMode == 'error')	$alertStatus = "Data Gagal Tersimpan";
elseif($reqMode == 'hapus')	$alertStatus = "Data Berhasil dihapus";

$statement= " AND JADWAL_TES_ID = ".$reqId;
$set_validasi= new JadwalTesSimulasiAsesor();
$set_validasi->selectByParams(array(), -1,-1, $statement);
$set_validasi->firstRow();
$tempStatusValidasi= $set_validasi->getField("STATUS");
unset($set_validasi);

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
	<div id="header-tna-detil">Jadwal <span><?=$tempJudul?></span></div>
    <div id="konten">
    <table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
        <thead class="altrowstable">
            <tr>
              <th>Nama Simulasi</th>
              <th style="width:30%">Jam</th>
			  <?
			  if($tempStatusValidasi == "1"){}
			  else
			  {
			  ?>
              <th style="width:30px">Aksi</th>
              <?
			  }
              ?>
            </tr>
       </thead>
       <tbody class="example altrowstable" id="alternatecolor"> 
		<? 
		while($set->nextRow())
		{
			$tempRowId= $set->getField("PUKUL_AWAL");
            $tempSimulasiNama= $set->getField("NAMA_SIMULASI");
			$tempJam= $set->getField("PUKUL_AWAL")." s/d ".$set->getField("PUKUL_AKHIR");
			$tempPenggalianId= $set->getField("PENGGALIAN_ID");
			$tempPukulAwal= $set->getField("PUKUL_AWAL");
		?>
        <tr>
        	<td><a href="master_jadwal_add_simulasi_asesor.php?reqRowId=<?=$tempRowId?>&reqJenisId=<?=$reqJenisId?>&reqId=<?=$reqId?>&reqMode=view">link data</a></td>
            <td><?=$tempSimulasiNama?></td>
            <td><?=$tempJam?></td>
            <?
			if($tempStatusValidasi == "1"){}
			else
			{
			?>
            <td><a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = 'master_jadwal_add_simulasi_asesor_monitoring.php?reqRowId=<?=$tempRowId?>&reqJenisId=<?=$reqJenisId?>&reqId=<?=$reqId?>&reqPenggalianId=<?=$tempPenggalianId?>&reqMode=delete' }"><img src="../WEB/images/delete-icon.png"></a></td>
            <?
			}
            ?>
		<? 
		}
		?>
        </tbody>
    </table>
    
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
        	parent.frames['mainFrameDetil'].location.href = $(this).find('td a').attr('href');
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