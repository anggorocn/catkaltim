<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Atribut.php");
include_once("../WEB/classes/base/LevelAtribut.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqAtributId= httpFilterRequest("reqAtributId");
$reqMode = httpFilterRequest("reqMode");

if($reqAtributId == "")
{
	exit;
}

$statement= " AND A.ATRIBUT_ID = '".$reqAtributId."'";
// kondisi aktif permen
$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM permen WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

$set_atribut= new Atribut();
$set_atribut->selectByParams(array(), -1,-1, $statement);
$set_atribut->firstRow();
$tempAtributNama= $set_atribut->getField("NAMA");
unset($set_atribut);

/* DATA VIEW */
$set= new LevelAtribut();
$statement= " AND A.ATRIBUT_ID = '".$reqAtributId."'";
// kondisi aktif permen
$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM permen WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;

if($reqMode == 'simpan')	$alertStatus = "Data Berhasil Tersimpan";
elseif($reqMode == 'error')	$alertStatus = "Data Gagal Tersimpan";
elseif($reqMode == 'hapus')	$alertStatus = "Data Berhasil dihapus";
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

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
    
</head>
<body class="bg-form" style="overflow-x:scroll;">
	<div class="judul_atas">Level Atribut <span>&gt;&nbsp;<?=$tempAtributNama?></span></div>
    <div id="konten">
    <table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
        <thead class="altrowstable">
            <tr>
              <th style="width:50px">Level</th>
              <th>Keterangan</th>
            </tr>
       </thead>
       <tbody class="example altrowstable" id="alternatecolor"> 
		<? 
		while($set->nextRow())
		{
			$tempRowId= $set->getField("LEVEL_ID");
			$tempLevel= $set->getField("LEVEL");
			$tempKeterangan= $set->getField("KETERANGAN");
		?>
        <tr>
        	<td><a href="indikator_penilaian_add_level_atribut.php?reqRowId=<?=$tempRowId?>&reqAtributId=<?=$reqAtributId?>&reqMode=view">link data</a></td>
            <td><?=$tempLevel?></td>
            <td><?=$tempKeterangan?></td>
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