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

$reqId= httpFilterGet("reqId");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="icon" type="image/ico" href="../WEB/images/favicon.ico" />
<title><?=$nama?> - <?=$nip_baru?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="../WEB/lib/dtree/dtree.css" type="text/css" />
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css">
<link href="../WEB/css/hiddenMenu.css" rel="stylesheet" type="text/css" />
<script src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

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
    
    function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
    {
        //var left = (screen.width/2)-(opWidth/2); 
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;
    
        var left = wLeft + (window.innerWidth / 2) - (opWidth / 2);
        var top = wTop + (window.innerHeight / 2) - (opHeight / 2);	
        
        divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+',top='+top+',resize=1,scrolling=1,midle=1'); return false;
    }
    
    function OptionSet(id, rowid){
        //alert(id+", "+rowid);return false;
        $("iframe#idMainFrameDetil")[0].contentWindow.OptionSet(id, rowid);
    }
    
	function executeOnClick(mode){
        //alert(id+", "+rowid);return false;
        $("iframe#menuFrame")[0].contentWindow.executeOnClick(mode);
    }
	
    function DwinClose()
    {
        divwin.close();
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
		<tr> 
        	<td height="100%" valign="top" class="menu" width="1"> 
                <table width="242" border="0" cellpadding="0" cellspacing="0" height="100%" id="menuFrame">
                    <tr> 
                        <td height="100%"></td>
                        <td valign="top">
                            <? //include "menu-tree-inc.php"; ?>		
                            <!-- MENU -->
                            <iframe src="formula_assesment_add_menu.php?reqId=<?=$reqId?>" name="menuFrame" id="menuFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="3" background="../WEB/images/bg_menu_right.gif" align="right">
                <a href="javascript:displayElement('menuFrame')"><img src="../WEB/images/btn_display_element.gif" title="Buka/Tutup Menu" border="0"></a>
            </td>
			<td valign="top" height="100%" width="100%">
				<div id="kontainer-atas" class="kontainer-atasFull">
					<iframe class="mainframe" id="idMainFrame" name="mainFrame" src="formula_assesment_add_data.php?reqId=<?=$reqId?>" style="width:100%; height:calc(100% - 5px); border:none;"></iframe>
				</div>
				<div id="trdetil" style="overflow:hidden; display:none">
					<button id="atasbawah">Show/Hide</button>
					<iframe class="mainframe" id="idMainFrameDetil" name="mainFrameDetil" src="#" style="width:100%; height:100%;  border:none;"></iframe>
				</div>
			</td>
		</tr>
	</table>
</body>
</html>