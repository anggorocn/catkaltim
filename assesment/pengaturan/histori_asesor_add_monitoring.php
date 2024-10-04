<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$set = new Asesor();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");

/* DATA VIEW */

//echo $set->query;exit;
$statement= " AND A.ASESOR_ID = ".$reqId;
$set= new Asesor();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$tempNamaAsesor= $set->getField("NAMA");

$index_loop= 0;
$arrAsesor="";
$statement= " AND C.ASESOR_ID = ".$reqId;
$set= new Asesor();
$set->selectByParamsHistori(array(), -1, -1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrAsesor[$index_loop]["ASESOR_ID"]= $set->getField("ASESOR_ID");
	$arrAsesor[$index_loop]["NAMA_ASESI"]= $set->getField("NAMA_ASESI");
	$arrAsesor[$index_loop]["KODE"]= $set->getField("KODE");
	$arrAsesor[$index_loop]["METODE"]= $set->getField("METODE");
	$arrAsesor[$index_loop]["TANGGAL_TES"]= $set->getField("TANGGAL_TES");
	$arrAsesor[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$index_loop++;
}
$jumlah_asesor= $index_loop;
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
	<div id="header-tna-detil">Histori Asesor <span><?=$tempNamaAsesor?></span></div>
    <div id="konten">
    <table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
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