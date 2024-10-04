<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/PenilaianLhkpn.php");

$tugas_belajar_add_semester = new PenilaianLhkpn();

$reqId = httpFilterGet("reqId");
$reqDeleteId = httpFilterGet("reqDeleteId");
$reqMode = httpFilterGet("reqMode");
$reqPegawaiId = httpFilterGet("reqPegawaiId");

/*if($reqId == "")
{
	echo '<script language="javascript">';
	echo 'alert("Isi data pegawai terlebih dahulu.");';	
	echo 'window.parent.location.href = "pegawai_add.php";';
	echo '</script>';
	exit();
}*/
if($reqMode == "delete")
{
	$tugas_belajar_add_semester->setField("PENILAIAN_LHKPN_ID", $reqDeleteId);
	$tugas_belajar_add_semester->delete();	

	echo '<script language="javascript">';
	echo 'window.parent.frames["mainFrameDetilPop"].location.reload();';
	echo '</script>';
		
}

$tugas_belajar_add_semester->selectByParams(array("PEGAWAI_ID" => $reqPegawaiId));
//echo $tugas_belajar_add_semester->query;exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>jQuery treeTable Plugin Documentation</title>
    <link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css">
    <script language="JavaScript" src="../jslib/displayElement.js"></script>
    <link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
    <script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
    <script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>
    
    <script type="text/javascript" src="../silat/jquery-1.4.2.min.js"></script>
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
    <script language="javascript">
	function openFile(id)
	{
		newWindow = window.open('../json/download.php?reqMode=jabatan&reqId='+id, 'Download');
		newWindow.focus();
	}
	</script>
    
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
</head>

<body>
<div id="bg"><img src="images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto">
    <table id="gradient-style" style="width:100%">
    <tr>
    	<td></td>
        <td colspan="7">
            <div id="header-tna-detil">Data <span>LHKPN / LHKSN</span></div>
        </td>			
    </tr>
    <tr>
        <?php /*?><th>Id</th><?php */?>
        <th>Tipe</th>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Action</th>
    </tr>
    <?
    while($tugas_belajar_add_semester->nextRow())
	{
	?>
        	<tr style="background-color:#FFF;cursor:pointer" onmouseover="ChangeColor(this, true);"  onmouseout="ChangeColor(this, false);">
        	<td><a href="penilaian_lhkpn_add_data.php?reqPegawaiId=<?=$reqPegawaiId?>&reqRowId=<?=$tugas_belajar_add_semester->getField("PENILAIAN_LHKPN_ID")?>">link data</a></td>
            <td align="center"><?=getFormattedDate($tugas_belajar_add_semester->getField("NAMA_TIPE"))?></td>
            <td align="center"><?=getFormattedDate($tugas_belajar_add_semester->getField("TANGGAL_LAPOR"))?></td>
            <td align="center"><?=$tugas_belajar_add_semester->getField("KETERANGAN")?></td>
            <td align="center">
            <a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = 'penilaian_lhkpn_monitoring.php?reqMode=&reqDeleteId=<?=$tugas_belajar_add_semester->getField("PENILAIAN_LHKPN_ID")?>' }"><img src="../WEB/images/delete-icon.png"></a>
            </td>
        </tr>    
    <?
	}
	?>    
    </table>
	<script type="text/javascript">
    $(function ()
    {
      // Hide the first cell for JavaScript enabled browsers.
      $('#gradient-style td:first-child').hide();

      // Apply a class on mouse over and remove it on mouse out.
      $('#gradient-style tr').hover(function ()
      {
		var tempArray = checkId(String($(this).find('td a').attr('href')));
		if(tempArray == ""){}
		else
        $(this).toggleClass('Highlight');
      });
  
      // Assign a click handler that grabs the URL 
      // from the first cell and redirects the user.
      $('#gradient-style tr').click(function ()
      {
		//alert(checkId(String($(this).find('td a').attr('href'))));
		var tempArray = checkId(String($(this).find('td a').attr('href')));
		//alert(tempArray);
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
		tempArray= tempArray[0];
		tempArray= tempArray.split('=');
		return tempArray[1];
	}
  </script>
</div>
</body>
</html>