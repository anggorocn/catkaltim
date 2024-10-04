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
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqId= httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");

$set= new Kelautan();
$set->selectByParamsMonitoringPegawai(array("A.PEGAWAI_ID" => $reqId));
// echo $set->query; exit;
$set->firstRow();
$tempNama=$set->getField('NAMA');
$tempNipLama=$set->getField('NIP_LAMA');
$tempNipBaru=$set->getField('NIP_BARU');
$tempPangkatTerkahir= $set->getField('NAMA_GOL');
$tempJabatanTerkahir= $set->getField('NAMA_JAB_STRUKTURAL');

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

if(varItem == 'training_add_data'){
	parent.setShowHideMenu(2);
	$('#training_add_data').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='training_add_data.php?reqId=<?=$reqId?>';
	parent.document.getElementById('trdetil').style.display = 'none';	
}
else if(varItem == 'training_add_kompetensi'){
	parent.setShowHideMenu(2);
	$('#training_add_kompetensi').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='training_add_kompetensi.php?reqId=<?=$reqId?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
return true;
}
</script> 
<?php /*?><script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script><?php */?>
<script type="text/javascript">
 
$(document).ready(function(){
	$('#page_effect').fadeIn(2000);
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
    <div>
        <div style="margin-top:5px; width:230px; margin-left:5px; float:left; position:relative; text-align:left;">
            <div style="float:left; position:relative; width:170px;"> 
            	<div style="color:#000; font-size:14px; text-shadow:1px 1px 1px #FFF;"><?=$tempNama?></div>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
    <div style="float: left" id="my_menu" class="sdmenu">
      <div> 
      	<span>Data</span>
        <a href="#" id="training_add_data" onclick="executeOnClick('training_add_data');" class="menuAktifDynamis">Training</a>
        <a href="#" id="training_add_kompetensi" onclick="executeOnClick('training_add_kompetensi');">Kompetensi</a>	
      </div>
    </div>
    <!-- END SDMENU -->
		
</div>
</div>
</body>

</html>