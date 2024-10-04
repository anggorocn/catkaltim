<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/Kelautan.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
//include_once("../WEB/functions/getdata.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$pangkat_riwayat= new Kelautan();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqPegawaiId = httpFilterRequest("reqPegawaiId");  
$reqIdOrganisasi = httpFilterGet("reqIdOrganisasi");  
$reqKetOrganisasi = httpFilterGet("reqKetOrganisasi");
/* VARIABLE */
$reqPANGKAT_RIWAYAT_ID = httpFilterRequest("reqPANGKAT_RIWAYAT_ID");
$reqMode = httpFilterRequest("reqMode");

/* DEFAULT VALUES */
$rcBright = "table_list_bright";
$rcDark = "table_list_dark";
$rcI = 0;

if($reqPegawaiId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi identitas pegawai terlebih dahulu.');";	
	echo "window.top.location.href = 'pegawai_edit.php?reqIdOrganisasi=".$reqIdOrganisasi."&reqKetOrganisasi=".$reqKetOrganisasi."';";
	echo '</script>';
}

/* DATA VIEW */
//$pangkat_riwayat->selectByParamsPegawai(array("IDPEG" => $reqPegawaiId)); 
$pangkat_riwayat->selectByParamsMonitoringPangkat(array("A.PEGAWAI_ID" => $reqPegawaiId)); 
//echo $pangkat_riwayat->query;exit;
if($reqMode == 'simpan')	$alertStatus = "Data Berhasil Tersimpan";
elseif($reqMode == 'error')	$alertStatus = "Data Gagal Tersimpan";
elseif($reqMode == 'hapus')	$alertStatus = "Data Berhasil dihapus";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css">
<!-- <script src="popup.js" type="text/javascript"></script> -->
 <script language="JavaScript" src="../jslib/displayElement.js"></script>
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>

<!-- <script type="text/javascript" src="jquery-1.4.2.min.js"></script> -->
<!-- <link type="text/css" href="validate/jquery-ui.datepickerValidate.css" rel="stylesheet" /> -->
<script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
<link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script src="forms/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
  $(function(){
	$("input, textarea, select, button").uniform();
  });
</script>
<link rel="stylesheet" href="forms/css/uniform.default.css" type="text/css" media="screen">
 <style type="text/css" media="screen">
      label {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 3px;
        clear: both;
      }
    </style>
<!-- BEGIN Plugin Code -->
  <!-- END Plugin Code -->
  
  <!-- Popup -->  
<style type="text/css">
/* Remove margins from the 'html' and 'body' tags, and ensure the page takes up full screen height */
html, body {height:100%; margin:0; padding:0;}
/* Set the position and dimensions of the background image. */
#page-background {position:fixed; top:0; left:0; width:100%; height:100%;}
/* Specify the position and layering for the content that needs to appear in front of the background image. Must have a higher z-index value than the background image. Also add some padding to compensate for removing the margin from the 'html' and 'body' tags. */
#content {position:relative; z-index:1;}
/* prepares the background image to full capacity of the viewing area */
#bg {position:fixed; top:0; left:0; width:100%; height:100%;}
/* places the content ontop of the background image */
#content {position:relative; z-index:1;}
</style>

<!-- <link href="styles.css" rel="stylesheet" type="text/css" /> -->
<script language="Javascript">
<? include_once "../jslib/formHandler.php"; ?>

function openPopup(opUrl,opWidth,opHeight)
{
	newWindow = window.open(opUrl, "", "width = " + opWidth + "px, height = " + opHeight + "px, resizable = 1, scrollbars");
	newWindow.focus();
}
</script>

<script language="JavaScript" src="../jslib/displayElement.js"></script>  
<?php /*?><script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script><?php */?>
</head>
<script type="text/javascript">
 
$(document).ready(function(){
 
	$('#page_effect').fadeIn(2000);
 
});
 
</script>
<body>
<? if($alertMsg) include_once("../WEB/modules/alertMsg.php");?>
<script type="text/javascript">
    function ChangeColor(tableRow, highLight)
    {
    if (highLight)
    {
      tableRow.style.backgroundColor = '#dcfac9';
    }
    else
    {
      tableRow.style.backgroundColor = 'white';
    }
  }

  function DoNav(theUrl)
  {
  parent.frames['mainFrameDetil'].location.href = theUrl;
  }
  </script>
  
<div id="page_effect" style="display:none;">
<!-- <div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div> -->
<div id="content" style="height:auto">
<? if($alertMsg) include_once("../WEB/modules/alertMsg.php");?>	    
    <!--<table class="table_list" cellspacing="1" width="100%">-->
    <table class="table_list" cellspacing="1" width="100%" id="link-table">
		<tr>
        	<td></td>
            <td colspan="12">
                <div id="header-tna-detil">Riwayat <span>Pangkat</span></div>			                    
		    </td>			
        </tr>
        
        <tr>
          <td><a href="pangkat_detil.php?reqPegawaiId=<?=$reqPegawaiId?>">link data</a></td>
          <th>Golongan</th>
		  <th style="display:none">Pangkat</th>
		  <th>TMT</th>
		  <th>MK. Tahun</th>
          <th>MK. Bulan</th>
          <th style="display:none">Jenis Naik Pangkat</th>          
          <th style="display:none">Tgl SK</th>
		  <th style="display:none">No SK</th> 
        </tr>
		<? while($pangkat_riwayat->nextRow()){?>
        <?php /*?><tr style="background-color:#FFF; cursor:pointer" onmouseover="ChangeColor(this, true);"  onmouseout="ChangeColor(this, false);"  onclick="DoNav('pangkat_detil.php?reqPegawaiId=<?=$pangkat_riwayat->getField('PEGAWAI_ID')?>&reqPANGKAT_RIWAYAT_ID=<?=$pangkat_riwayat->getField('PANGKAT_RIWAYAT_ID')?>&reqMode=view');"><?php */?>
        <tr style="background-color:#FFF;cursor:pointer" onmouseover="ChangeColor(this, true);"  onmouseout="ChangeColor(this, false);">
        	<td><a href="pangkat_detil.php?reqPegawaiId=<?=$pangkat_riwayat->getField('PEGAWAI_ID')?>&reqPANGKAT_RIWAYAT_ID=<?=$pangkat_riwayat->getField('PANGKAT_RIWAYAT_ID')?>&reqMode=view">link data</a></td>
            <td><?=$pangkat_riwayat->getField('PANGKAT_NAMA')?></td>
            <td style="display:none"><?=$pangkat_riwayat->getField('PANGKAT')?></td>
            <td><?=dateToPageCheck($pangkat_riwayat->getField('TMT_PANGKAT'))?></td>
            <td><?=$pangkat_riwayat->getField('MASA_KERJA_TAHUN')?></td>
            <td><?=$pangkat_riwayat->getField('MASA_KERJA_BULAN')?></td>            
            <td style="display:none"><?=$pangkat_riwayat->getField('NMJENIS')?></td>
            <td style="display:none"><?=datetimeToPage($pangkat_riwayat->getField('TANGGAL_SK'),"date")?></td>
            <td style="display:none"><?=$pangkat_riwayat->getField('NO_SK')?></td> 
        </tr>
		<? }?>
    </table>    
    <script type="text/javascript">
    $(function ()
    {
      // Hide the first cell for JavaScript enabled browsers.
      $('#link-table td:first-child').hide();

      // Apply a class on mouse over and remove it on mouse out.
      $('#link-table tr').hover(function ()
      {
        //$(this).toggleClass('Highlight');
      });
  
      // Assign a click handler that grabs the URL 
      // from the first cell and redirects the user.
      $('#link-table tr').click(function ()
      {
		var tempArray="";
		tempArray= String($(this).find('td a').attr('href'));
		tempArray= tempArray.split('&');
		tempArray= tempArray[2];
		tempArray= tempArray.split('=');
		
		if(tempArray[1] == ""){}
		else
        parent.frames['mainFrameDetil'].location.href = $(this).find('td a').attr('href');
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