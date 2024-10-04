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

$reqPegawaiId = httpFilterRequest("reqPegawaiId");
$reqIdOrganisasi = httpFilterRequest("reqIdOrganisasi");
$reqNIP = httpFilterPost("reqNIP");
$reqNIPBaru = httpFilterPost("reqNIPBaru");
$reqNama = httpFilterPost("reqNama");
$reqMode = httpFilterRequest("reqMode");
$reqSource = httpFilterGet("reqSource");
$reqFormulaId = httpFilterGet("reqFormulaId");
$readonly = httpFilterGet("readonly");

$reqId = httpFilterGet("reqId");




$set= new Kelautan();
$set->selectByParamsMonitoring2(array('A.PEGAWAI_ID'=>$reqPegawaiId),-1,-1);
$set->firstRow();
//echo $set->query;exit;
//echo $set->errorMsg;exit;
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

if(varItem == 'pengembangan'){
	parent.setShowHideMenu(2);
	$('#pengembangan').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='pengembangan_kompetensi_info<?=$reqSource?>.php?reqId=<?=$reqPegawaiId?>&reqFormulaId=<?=$reqFormulaId?>';
	//parent.mainFrameDetil.location.href='identitas_detil.php';
	parent.document.getElementById('trdetil').style.display = 'none';	
}
else if(varItem == 'jenis'){
	parent.setShowHideMenu(2);
	$('#jenis').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='pengembangan_kompetensi_add<?=$reqSource?>.php?reqId=<?=$reqPegawaiId?>&reqFormulaId=<?=$reqFormulaId?>';
	//parent.mainFrameDetil.location.href='identitas_detil.php';
	parent.document.getElementById('trdetil').style.display = 'none';	
}
else if(varItem == 'jenisread'){
	parent.setShowHideMenu(2);
	$('#jenisread').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='pengembangan_kompetensi_add<?=$reqSource?>.php?reqId=<?=$reqPegawaiId?>&reqFormulaId=<?=$reqFormulaId?>&readonly=<?=$readonly?>';
	//parent.mainFrameDetil.location.href='identitas_detil.php';
	parent.document.getElementById('trdetil').style.display = 'none';	
}

else if(varItem == 'realisasi'){
    parent.setShowHideMenu(2);
    $('#realisasi').addClass("menuAktifDynamis");
    parent.mainFrame.location.href='pengembangan_kompetensi_add_realisasi<?=$reqSource?>.php?reqId=<?=$reqPegawaiId?>&reqFormulaId=<?=$reqFormulaId?>';
    //parent.mainFrameDetil.location.href='identitas_detil.php';
    parent.document.getElementById('trdetil').style.display = 'none';   
}
else if(varItem == 'realisasiread'){
    parent.setShowHideMenu(2);
    $('#realisasiread').addClass("menuAktifDynamis");
    parent.mainFrame.location.href='pengembangan_kompetensi_add_realisasi<?=$reqSource?>.php?reqId=<?=$reqPegawaiId?>&reqFormulaId=<?=$reqFormulaId?>&readonly=<?=$readonly?>';
    //parent.mainFrameDetil.location.href='identitas_detil.php';
    parent.document.getElementById('trdetil').style.display = 'none';   
}

return true;
}
</script> 
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
    <div>
        <div style="margin-top:5px; width:230px; margin-left:5px; float:left; position:relative; text-align:left;">

            <div style="float:left; position:relative; width:170px;"> 
            	<div style="color:#000; font-size:14px; text-shadow:1px 1px 1px #FFF;"><?=$tempNama?></div>
                <div style="color:#000; font-size:12px; text-shadow:1px 1px 1px #FFF; line-height:20px;"><strong>Nip : </strong><?=$tempNipBaru?> </div>
            </div>

        </div>
        <div style="clear:both"></div>
        <div style="color:#000; text-align:center; margin-left:5px; margin-top:10px; font-size:12px; line-height:34px; text-shadow:1px 1px 1px #FFF; border-top:1px dashed #FFF; ">
        -&nbsp;<?=$tempPangkatTerkahir?> / <?=$tempJabatanTerkahir?>&nbsp;-
        </div>
    </div>
    <div style="float: left" id="my_menu" class="sdmenu">
      <div> 
      	<span>Pengembangan Kompetensi</span>
        <a href="#" id="pengembangan" onclick="executeOnClick('pengembangan');"  <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Hasil Assesment</a>
        <?
        if ($readonly == 1)
        {
        ?> 
           	<a href="#" id="jenisread" onclick="executeOnClick('jenisread');">Analisis Program Pengembangan</a>	
            <!-- <a href="#" id="realisasiread" onclick="executeOnClick('realisasiread');">Realisasi Program Pengembangan</a>  -->
        <?	
        }
        else
        {
        ?>
        	 <a href="#" id="jenis" onclick="executeOnClick('jenis');">Analisis Program Pengembangan</a>
            <!-- <a href="#" id="realisasi" onclick="executeOnClick('realisasi');">Realisasi Program Pengembangan</a>  -->
       	<?
        }
        ?>
     
      </div>

    </div>
    <!-- END SDMENU -->
		
</div>
</div>
</body>

</html>