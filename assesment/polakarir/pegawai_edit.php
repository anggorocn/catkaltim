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
$reqConfirm = httpFilterRequest("reqConfirm");
$reqIdOrganisasi = httpFilterRequest("reqIdOrganisasi");
header("Cache-Control: must-revalidate");

//echo $userLogin->userGroupId.'-adas';
/*if($userLogin->userGroupId == 2)
{
	echo '<script language="javascript">';
	echo "alert('Anda Tidak Berhak, login sebagai admin.');";	
	echo 'window.close();';
	echo '</script>';
	exit();
}*/

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
<script language="JavaScript" src="../jslib/displayElement.js"></script>
<script language="JavaScript">
function openPopup(opUrl,opWidth,opHeight)
{
	newWindow = window.open(opUrl, "", "width = " + opWidth + "px, height = " + opHeight + "px, resizable = 1, scrollbars, top = 100, left = 150");
	newWindow.focus();
}
</script>

<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>
<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>

<script type="text/javascript">
function iecompattest(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
{
	//var left = (screen.width/2)-(opWidth/2); 
	var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
	var wTop = window.screenTop ? window.screenTop : window.screenY;

	var left = wLeft + (window.innerWidth / 2) - (opWidth / 2);
	var top = wTop + (window.innerHeight / 2) - (opHeight / 2);	
	
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+',top='+top+',resize=1,scrolling=1,midle=1'); return false;
}

function OpenDHTMLDetil(opAddress, opCaption, opWidth, opHeight)
{

	var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
	var top = iecompattest().scrollTop + 10; //(screen.width/2)-(opWidth/2);
	
	opWidth = iecompattest().clientWidth - 5;
	opHeight = iecompattest().clientHeight - (iecompattest().clientHeight/3);
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
}

function OptionSetMode(id){
	$("iframe#idMainFrameDetil")[0].contentWindow.OptionSetMode(id);
}
</script>

</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">

<script language=JavaScript>
<!--

//Disable right mouse click Script
//By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
//For full source code, visit http://www.dynamicdrive.com

var message="Function Disabled!";

///////////////////////////////////
function clickIE4(){
if (event.button==2){
alert(message);
return false;
}
}

function clickNS4(e){
if (document.layers||document.getElementById&&!document.all){
if (e.which==2||e.which==3){
alert(message);
return false;
}
}
}

if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}

document.oncontextmenu=new Function("return false")

// --> 
</script>

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
				  	<iframe src="pegawai_menu_edit.php?reqPegawaiId=<?=$reqId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>" name="menuFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>		  
		  			</td>
        		</tr>
      		</table>
		</td>
		<td width="3" background="../WEB/images/bg_menu_right.gif" align="right">
			<a href="javascript:displayElement('menuFrame')"><img src="../WEB/images/btn_display_element.gif" title="Buka/Tutup Menu" border="0"></a>
		</td>
    	<td valign="top" height="100%" width="100%">
            <table cellpadding="0" cellspacing="0"  width="100%" height="100%">
            	<tr height="10%">
                	<td><iframe src="../silat/identitas_edit.php?reqPegawaiId=<?=$reqId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>&reqConfirm=<?=$reqConfirm?>" class="mainframe" id="idMainFrame" name="mainFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe></td>
                </tr>
            	<tr height="20%" id="trdetil" style="display:none">
                	<td><iframe src="" class="mainframe" id="idMainFrame" name="mainFrameDetil" width="100%" height="100%" scrolling="no" frameborder="0"></iframe></td>
                </tr>
            </table>			
		</td>
	</tr>
</table>
</body>
</html>
