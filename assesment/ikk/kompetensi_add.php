<?
/* *******************************************************************************************************
MODUL NAME 			: SIMKeu
FILE NAME 			: index.php
AUTHOR				: Ridwan Rismanto
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: halaman index
***************************************************************************************************** */
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

//LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqId = httpFilterRequest("reqId");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="icon" type="image/ico" href="../WEB/images/favicon.ico" />
<title></title>

<script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script language="javascript">
function setLoad(url, tipe)
{
	mainFrame.location.href=url;
	if(tipe == 1)
		$("#trdetil").hide();
	else
		$("#trdetil").show();
}
</script>
</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="100%" bgcolor="#F0F0F0" style="overflow:hidden">
   	<tr> 
    	<td valign="top" height="100%" width="100%">
            <table cellpadding="0" cellspacing="0"  width="100%" height="100%">
            	<tr height="10%">
                	<td>
                    	<iframe src="kompetensi_add_penilaian_monitoring.php?reqId=<?=$reqId?>" class="mainframe" id="idMainFrame" name="mainFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>
                    </td>
                </tr>
            	<tr height="20%" id="trdetil">
                	<td>
                    	<iframe src="kompetensi_add_penilaian.php?reqId=<?=$reqId?>" class="mainframe" id="idMainFrameDetil" name="mainFrameDetil" width="100%" height="100%" scrolling="no" frameborder="0"></iframe>
                    </td>
                </tr>
            </table>			
		</td>
	</tr>
</table>
</body>
</html>
