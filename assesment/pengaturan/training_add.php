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

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterRequest("reqId");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="icon" 
      type="image/ico" 
      href="../WEB/images/favicon.ico" />
<title><?=$nama?> - <?=$nip_baru?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="../WEB/lib/dtree/dtree.css" type="text/css" />
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css">
<link href="../WEB/css/hiddenMenu.css" rel="stylesheet" type="text/css" />
<script src="../WEB/js/jquery.min.js"></script>

<script type='text/javascript'>
	$(window).load(function(){
		$('button#atasbawah').click(function () {
			//$("a").removeClass("menuAktifDynamis");
			$('#kontainer-atas').toggleClass('hidden');
			$('#trdetil').toggleClass('hidden');
		});
	});
	
	function setShowHideMenu(kondisi)
	{
		if(kondisi == 1)//bagi dua
		{
			$("#kontainer-atas").removeClass("kontainer-atasFull");
			$("#kontainer-atas").addClass("kontainer-atasBagi");
		}
		else
		{
			$("#kontainer-atas").removeClass("kontainer-atasBagi");
			$("#kontainer-atas").addClass("kontainer-atasFull");
		}
	}
</script>

<script language="JavaScript" src="../jslib/displayElement.js"></script>
<script language="JavaScript">
function openPopup(opUrl,opWidth,opHeight)
{
	newWindow = window.open(opUrl, "", "width = " + opWidth + "px, height = " + opHeight + "px, resizable = 1, scrollbars, top = 100, left = 150");
	newWindow.focus();
}
</script>

</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="100%" bgcolor="#F0F0F0" style="overflow:hidden">
	<!--<tr id="banner">
    	<td colspan="5">
        <img src="../WEB/images/header.jpg" width="100%">
        </td>
    </tr>-->
   	<tr> 
    	<td height="100%" valign="top" class="menu" width="1"> 
      		<table width="242" border="0" cellpadding="0" cellspacing="0" height="100%" id="menuFrame">
        		<tr> 
		  			<td height="100%"></td>
         			<td valign="top">
		  			<? //include "menu-tree-inc.php"; ?>		
				  	<!-- MENU -->
				  	<iframe src="training_add_menu.php?reqId=<?=$reqId?>" name="menuFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>
		  			</td>
        		</tr>
      		</table>
		</td>
		<td width="3" background="../WEB/images/bg_menu_right.gif" align="right">
			<a href="javascript:displayElement('menuFrame')"><img src="../WEB/images/btn_display_element.gif" title="Buka/Tutup Menu" border="0"></a>
		</td>
    	<td valign="top" height="100%" width="100%">
            <div id="kontainer-atas" class="kontainer-atasFull">
                <iframe class="mainframe" id="idMainFrame" name="mainFrame" src="training_add_data.php?reqId=<?=$reqId?>" style="width:100%; height:calc(100% - 5px); border:none;"></iframe>
            </div>
            
            <div id="trdetil" style="overflow:hidden;">
                <button id="atasbawah">Show/Hide</button>
                <iframe class="mainframe" id="idMainFrameDetil" name="mainFrameDetil" src="" style="width:100%; height: 100%;  border:none;"></iframe>
            </div>
		</td>
	</tr>
</table>
</body>
</html>
