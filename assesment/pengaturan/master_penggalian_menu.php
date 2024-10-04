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

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");

$set= new Kelautan();
$set->selectByParamsMonitoringPegawai(array("A.ID" => $reqPegawaiId));
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
    
    if(varItem == 'penggalian'){
        parent.setShowHideMenu(2);
        $('#penggalian').addClass("menuAktifDynamis");
        parent.mainFrame.location.href='master_penggalian_add_data.php?reqId=<?=$reqId?>&reqMode=<?=$reqMode?>';
    }
    else if(varItem == 'penggalian_detil'){
        parent.setShowHideMenu(2);
        $('#penggalian_detil').addClass("menuAktifDynamis");
		parent.mainFrame.location.href='master_penggalian_add_penilaian.php?reqId=<?=$reqId?>&reqMode=<?=$reqMode?>';
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
    <body>
        <div id="page_effect" style="display:none;">
            <div id="bg"><img src="../WEB/images/wall-kiri.jpg" width="100%" height="100%" alt=""></div>
            <div id="content">
            
                <!-- SDMENU -->
                <div style="float: left" id="my_menu" class="sdmenu">
                  <div> 
                    <span>Menu</span>
                    <a href="#" id="penggalian" onclick="executeOnClick('penggalian');" <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Penggalian</a>
                    <a href="#" id="penggalian_detil" onclick="executeOnClick('penggalian_detil');">Penilaian</a>
                  </div>
                <!-- END SDMENU -->
                    
            </div>
        </div>
    </body>
</html>