<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Training.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$set = new Training();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqRowId = httpFilterRequest("reqRowId");
$reqMode = httpFilterRequest("reqMode");
$reqId = httpFilterRequest("reqId");  
$reqIdOrganisasi = httpFilterGet("reqIdOrganisasi");  
$reqKetOrganisasi = httpFilterGet("reqKetOrganisasi");  

//echo $reqId;
if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi training terlebih dahulu.');";	
	echo "window.top.location.href = 'master_traning_add.php';";
	echo '</script>';
}
/* DEFAULT VALUES */
$rcBright = "table_list_bright";
$rcDark = "table_list_dark";
$rcI = 0;

/* DATA VIEW */
$set->selectByParamsKompetensi(array(), -1, -1, " AND A.TRAINING_ID = ".$reqId);
//echo $set->query;exit;
if($reqMode == 'simpan')	$alertStatus = "Data Berhasil Tersimpan";
elseif($reqMode == 'error')	$alertStatus = "Data Gagal Tersimpan";
elseif($reqMode == 'hapus')	$alertStatus = "Data Berhasil dihapus";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css">
<script language="JavaScript" src="../jslib/displayElement.js"></script>
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>

<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
<link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
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

<link href="styles.css" rel="stylesheet" type="text/css" />
<script language="Javascript">
<? include_once "../jslib/formHandler.php"; ?>

function openPopup(opUrl,opWidth,opHeight)
{
	newWindow = window.open(opUrl, "", "width = " + opWidth + "px, height = " + opHeight + "px, resizable = 1, scrollbars");
	newWindow.focus();
}
</script>

<script language="JavaScript" src="../jslib/displayElement.js"></script>  
<script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script>
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
</script>

<div id="page_effect" style="display:none;">
<div id="bg"><img src="images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto">
<? if($alertMsg) include_once("../WEB/modules/alertMsg.php");?>	    
    <table class="table_list" cellspacing="1" width="100%" id="link-table">
		<tr>
        	<td></td>
            <td colspan="7">
                <div id="header-tna-detil">Data <span>Kompetensi Training</span></div>
		    </td>			
        </tr>
        <tr>
          <th>Atribut</th>
		  <th>Training</th>
        </tr>
		<? 
		while($set->nextRow())
		{
		?>
        <tr style="background-color:#FFF;cursor:pointer" onmouseover="ChangeColor(this, true);"  onmouseout="ChangeColor(this, false);">
        	<td><a href="master_traning_add_data_kompetensi_atribut_detil.php?reqRowId=<?=$set->getField('KOMPETENSI_TRAINING_ID')?>&reqMode=view&reqId=<?=$reqId?>">link data</a></td>
            <td><?=$set->getField("ATRIBUT_NAMA")?></td>
            <td><?=$set->getField("TRAINING_NAMA")?></td>
        </tr>
		<? 
		}
		?>
    </table>
    
    <script type="text/javascript">
    $(function ()
    {
      // Hide the first cell for JavaScript enabled browsers.
      $('#link-table td:first-child').hide();

      // Apply a class on mouse over and remove it on mouse out.
      $('#link-table tr').hover(function ()
      {
		var tempArray = checkId(String($(this).find('td a').attr('href')));
		if(tempArray == ""){}
		else
        $(this).toggleClass('Highlight');
      });
  
      // Assign a click handler that grabs the URL 
      // from the first cell and redirects the user.
      $('#link-table tr').click(function ()
      {
		var tempArray = checkId(String($(this).find('td a').attr('href')));
		if(tempArray == ""){}
		else
        parent.frames['mainFrameDetil'].location.href = $(this).find('td a').attr('href');
      });
    });
	
	function checkId(value)
	{
		var tempArray="";
		tempArray= value;
		tempArray= tempArray.split('&');
		tempArray= tempArray[2];
		tempArray= tempArray.split('=');
		return tempArray[1];
	}
  </script>
</div>
</div>
<?
if($reqMode == 'simpan' || $reqMode == 'error' || $reqMode == 'hapus'){
	echo '<script language="javascript">';
	echo "$.jGrowl('".$alertStatus."', { position: 'top-right' });";
	echo '</script>';
	//$alertMsg = "Data Berhasil Tersimpan";
}
?>
</body>
</html>
</html>