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
include_once("../WEB/classes/base/LevelAtribut.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqAtributId= httpFilterRequest("reqAtributId");

$arrLevel="";
$index_arr= 0;
$set= new LevelAtribut();
$statement= " AND A.ATRIBUT_ID = '".$reqAtributId."'";
// kondisi aktif permen
$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM permen WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";

// $statement.= " AND EXISTS 
// (
//   SELECT 1 
//   FROM 
//   (
//     SELECT ATRIBUT_ID ATRIBUT_AKTIF 
//     FROM ATRIBUT 
//     WHERE 1=1 
//     AND 
//     EXISTS 
//     ( 
//       SELECT 1 FROM 
//       ( 
//       SELECT PERMEN_ID AKTIF_PERMENT FROM PERMEN WHERE STATUS = '1' 
//       ) X 
//       WHERE AKTIF_PERMENT = PERMEN_ID
//     )
//   ) X
//   WHERE ATRIBUT_AKTIF = ATRIBUT_ID
// )
// ";

$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrLevel[$index_arr]["LEVEL_ID"] = $set->getField("LEVEL_ID");
	$arrLevel[$index_arr]["LEVEL"] = $set->getField("LEVEL");
	$index_arr++;
}
unset($set);
$jumlah_level= $index_arr;

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

			if(varItem == 'jadwal'){
				parent.setShowHideMenu(2);
				$('#jadwal').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='master_jadwal_add_data.php?reqAtributId=<?=$reqAtributId?>&reqMode=<?=$reqMode?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
			else if(varItem == 'level_atribut'){
				parent.setShowHideMenu(1);
				$('#level_atribut').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='indikator_penilaian_add_level_atribut_monitoring.php?reqAtributId=<?=$reqAtributId?>';
				parent.mainFrameDetil.location.href='indikator_penilaian_add_level_atribut.php?reqAtributId=<?=$reqAtributId?>';
				parent.document.getElementById('trdetil').style.display = '';
			}
			<?
			for($checkbox_index=0;$checkbox_index < $jumlah_level;$checkbox_index++)
			{
				$tempLevelId= $arrLevel[$checkbox_index]["LEVEL_ID"];
				$tempLevel= $arrLevel[$checkbox_index]["LEVEL"];
			?>
			else if(varItem == 'level_<?=$tempLevel?>'){
				parent.setShowHideMenu(2);
				$('#level_<?=$tempLevel?>').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='indikator_penilaian_add_indikator_penilaian.php?reqAtributId=<?=$reqAtributId?>&reqRowId=<?=$tempLevelId?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
			<?
			}
			?>
			return true;
		}
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
        <div id="bg"><img src="../WEB/images/wall-kiri.jpg" width="100%" height="100%" alt=""></div>
        <div id="content">
        
            <!-- SDMENU -->
            <div style="float: left" id="my_menu" class="sdmenu">
              <div> 
                <span>Indikator Penilaian</span>
                <a href="#" id="level_atribut" onclick="executeOnClick('level_atribut');" class="menuAktifDynamis">Level Atribut</a>
                <?
				for($checkbox_index=0;$checkbox_index < $jumlah_level;$checkbox_index++)
				{
					$tempLevelId= $arrLevel[$checkbox_index]["LEVEL_ID"];
					$tempLevel= $arrLevel[$checkbox_index]["LEVEL"];
				?>
                <a href="#" id="level_<?=$tempLevel?>" onclick="executeOnClick('level_<?=$tempLevel?>');">Level <?=$tempLevel?></a>
                <?
				}
                ?>
              </div>
            <!-- END SDMENU -->
            </div>
            
        </div>
    </body>
</html>