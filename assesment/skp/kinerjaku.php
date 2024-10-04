<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

$tempTahun= "2015";
$tempBulan= "12";

$reqId= httpFilterGet("reqId");
$reqBulan= httpFilterGet("reqBulan");
$reqTahun= httpFilterGet("reqTahun");

$arrBulan= setBulanLoop();
$arrTahun= setTahunLoop(1,1);

if($reqBulan == "")
	$tempBulanAktif= date("n");
else
	$tempBulanAktif= $reqBulan;
	
if($reqTahun == "")
	$tempTahunAktif= date("Y");
else
	$tempTahunAktif= $reqTahun;

$statement= " AND A.kode_unker = '".$reqId."'";
$set= new Kelautan();
$set->selectByParamsPejabatSatuanKerja(array(), -1, -1, $statement);
$set->firstRow();
$tempPejabatNama= $set->getField("NAMA");
$tempPejabatUnker= $set->getField("NAMA_UNKER");
$tempPejabatEselon= $set->getField("KODE_ESELON");
unset($set);
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
    
    <script type="text/javascript">
    $(function(){
		$("#reqBulan,#reqTahun").change(function() { 
		  var reqBulan= reqTahun= "";
		  
		  reqBulan= $("#reqBulan").val();
		  reqTahun= $("#reqTahun").val();
		  
		  document.location.href= "kinerjaku.php?reqId=<?=$reqId?>&reqBulan="+reqBulan+"&reqTahun="+reqTahun;
		});
	});
	</script>
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="100%" bgcolor="#F0F0F0" style="overflow:hidden">
   	<tr> 
    	<td height="100px" valign="top" colspan="2" width="100%"> 
        	<table class="gradient-style" style="width:100%; position:relative">
            	<tr>
                	<th colspan="3">
                    <div id="header-tna-detil">
                    SKP Berbasis BSC 
                    <span>
                    Bulan : 
                    <select name="reqBulan" id="reqBulan">
                        <?
                        for($bulan=0;$bulan < count($arrBulan);$bulan++)
                        {
                        ?>
                        <option value="<?=(int)$arrBulan[$bulan]?>" <? if($tempBulanAktif == $arrBulan[$bulan]) echo "selected";?>><?=getNameMonth(generateZero($arrBulan[$bulan],2))?></option>
                        <?
                        }
                        ?>
                     </select>
                     <select name="reqTahun" id="reqTahun">
                        <?
                        for($tahun=0;$tahun < count($arrTahun);$tahun++)
                        {
                        ?>
                        <option value="<?=$arrTahun[$tahun]?>" <? if($tempTahunAktif == $arrTahun[$tahun]) echo "selected";?>><?=$arrTahun[$tahun]?></option>
                        <?
                        }
                        ?>
                     </select>
                    </span>
                    </div>
                    </th>
                </tr>
                <tr>
                    <th>Unit Kerja</th>
                    <th>Eselon</th>
                    <th>Nama Pejabat</th>
                </tr>
                <tr>
                    <td><?=$tempPejabatUnker?></td>
                    <td><?=$tempPejabatEselon?></td>
                    <td><?=$tempPejabatNama?></td>
                </tr>
            </table>
		</td>
	</tr>
    <?
	if($reqId == ""){}
	else
	{
    ?>
    <tr> 
    	<td height="100%" valign="top" class="menu" width="50%"> 
      		<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%" id="menuFrame">
        		<tr> 
		  			<td height="100%"></td>
         			<td valign="top">
				  		<iframe src="kinerjaku_skp.php?reqId=<?=$reqId?>&reqBulan=<?=$tempBulan?>&reqTahun=<?=$tempTahun?>" name="menuFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>		  
		  			</td>
        		</tr>
      		</table>
		</td>
    	<td valign="top" height="100%" width="50%">
            <table cellpadding="0" cellspacing="0"  width="100%" height="100%">
            	<tr height="10%">
                	<td>
                    	<iframe src="kinerjaku_bsc.php?reqId=<?=$reqId?>&reqBulan=<?=$tempBulan?>&reqTahun=<?=$tempTahun?>" class="mainframe" id="idMainFrame" name="mainFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>
                    </td>
                </tr>
            </table>			
		</td>
	</tr>
    <?
	}
    ?>
</table>
</body>
</html>