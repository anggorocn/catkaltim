<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/kode.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Skp.php");

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/

$reqId= httpFilterGet("reqId");
$reqBulan= httpFilterGet("reqBulan");
$reqBulan= (int)$reqBulan;
$reqTahun= httpFilterGet("reqTahun");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$set= new Skp();
$set->selectByParamsPenilaianSkp(array(), -1,-1, " and year(e.tgl_start) = ".$reqTahun." and d.kode_unker = '".$reqId."' and b.triwulan in(".$reqBulan.") ");
//echo $set->query;exit;
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
</head>

<body>
<div id="bg"><img src="images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto">
    <table class="gradient-style" style="width:100%">
    <tr>
        <td colspan="5">
            <div id="header-tna-detil">Data <span>SKP</span></div>
        </td>			
    </tr>
    <tr>
        <th>Uraian Tugas</th>
        <th>Satuan</th>
        <th>Target</th>
        <th>Realisasi</th>
        <th>Nilai</th>
    </tr>
    <?
	while($set->nextRow())
	{
    ?>
    <tr>
    	<td><?=$set->getField("nama_iku")?></td>
        <td><?=$set->getField("t_sat_o")?></td>
        <td><?=$set->getField("t_ko")?></td>
        <td><?=$set->getField("realisasi")?></td>
        <td><?=$set->getField("nilai")?></td>
    </tr>
    <?
	}
    ?>
    </table>
</div>
</body>
</html>