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
//if ($userLogin->checkUserLogin()) 
//{ 
//	$userLogin->retrieveUserInfo();  
//}

$reqId = httpFilterRequest("reqId");
$reqIdOrganisasi = httpFilterRequest("reqIdOrganisasi");
$reqNIP = httpFilterPost("reqNIP");
$reqNIPBaru = httpFilterPost("reqNIPBaru");
$reqNama = httpFilterPost("reqNama");
$reqMode = httpFilterRequest("reqMode");
$reqSource = httpFilterGet("reqSource");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Destroydrop &raquo; Javascripts &raquo; Tree</title>

<style>
/*#box1 {
width: 580px;
padding: 9px 15px;
background-color: #ED8029;
color: white;
margin-bottom: 20px;
margin-top: 20px;
border-radius: 5px;
}

#box1:hover {
background-color: #A7B526;
}*/

/*#menu-kiri a{ text-decoration:none;}
#menu-kiri a:hover{ text-decoration:none; color:#FFF;}

#box2 {
width: 580px;
border-top:1px solid #ececec;
border-bottom:1px solid #c9c9c9;
padding: 9px 15px;
background-color: #e0e0e0;
color: #000;
text-shadow:1px 1px 1px #FFF;

border-radius: 5px;

-webkit-transition: background-color 1s;
-moz-transition: background-color 1s;
-o-transition: background-color 1s;
-ms-transition: background-color 1s;
transition: background-color 1s;
}

#box2:hover {
background-color: #a5a5a5;
border-top:1px solid #a5a5a5;
border-bottom:1px solid #9a9a9a;
color:#FFF;
text-shadow:1px 1px 1px #000;
} */
</style>

<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css">
<link rel="StyleSheet" href="../WEB/lib/dtree/dtree.css" type="text/css" />
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="button_satker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../jslib/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/dtree/dtree.js"></script>

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

<script type="text/javascript" trdetilsrc="jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function executeOnClick(varItem){
$("a").removeClass("menuAktifDynamis");

<? if($reqMode == 'search'){?>
parent.document.getElementById('FrameFIP').style.display = '';	
parent.document.getElementById('idMainFrame').style.display = 'none';	
<? }?>

if(varItem == 'master_traning_add_data'){
	parent.setShowHideMenu(2);
	$('#master_traning_add_data').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='master_traning_add_data.php?reqId=<?=$reqId?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == 'master_traning_add_data_kompetensi_atribut'){
	parent.setShowHideMenu(1);
	$('#master_traning_add_data_kompetensi_atribut').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='master_traning_add_data_kompetensi_atribut.php?reqId=<?=$reqId?>';
	parent.mainFrameDetil.location.href='master_traning_add_data_kompetensi_atribut_detil.php?reqId=<?=$reqId?>';
	parent.document.getElementById('trdetil').style.display = '';
}

return true;
}
</script> 
<?php /*?><script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script><?php */?>
<script type="text/javascript">
 
$(document).ready(function(){
	$('#page_effect').fadeIn(2000);
	$('#tambmasakerja, #nikah, #bahasa, #riwayatpenugasan, #cuti, #hukuman, #catatanprestasi, #potensidiri, #dp3 ,#penghargaan, #organisasi, #saudara, #anak, #suamiistri, #mertua').click(
			function (e) {
				$('html, body').animate({scrollTop: '1000px'}, 800);
			}
		); 
});
 
</script>

<!-- SDMENU -->
<link rel="stylesheet" type="text/css" href="../WEB/lib/sdmenu/sdmenu.css" />
<script type="text/javascript" src="../WEB/lib/sdmenu/sdmenu.js">
	/***********************************************
	* Slashdot Menu script- By DimX
	* Submitted to Dynamic Drive DHTML code library: http://www.dynamicdrive.com
	* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
	***********************************************/
</script>
<script type="text/javascript">
// <![CDATA[
var myMenu;
window.onload = function() {
	myMenu = new SDMenu("my_menu");
	myMenu.init();
};
// ]]>
</script>

</head>

<!--<body leftmargin="5" rightmargin="0" bottommargin="0" topmargin="0" style="overflow:scroll" >-->
<body>
<div id="page_effect" style="display:none;">
<div id="bg"><img src="../WEB/images/wall-kiri.jpg" width="100%" height="100%" alt=""></div>
<div id="content">

    <!-- SDMENU -->
    <div style="float: left" id="my_menu" class="sdmenu">
      <div> 
      	<span>Info Pegawai</span>
        <a href="#" id="master_traning_add_data" onclick="executeOnClick('master_traning_add_data');"  <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Training</a>
        <a href="#" id="master_traning_add_data_kompetensi_atribut" onclick="executeOnClick('master_traning_add_data_kompetensi_atribut');">Kompetensi Training Atribut</a>	
      </div>
      
    </div>
    <!-- END SDMENU -->
		
</div>
</div>
</body>

</html>