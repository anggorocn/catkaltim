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
include_once("../WEB/classes/base/FormulaEselon.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqId= httpFilterRequest("reqId");
$reqMode= httpFilterRequest("reqMode");
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
			
			if(varItem == 'data'){
				parent.setShowHideMenu(2);
				$('#data').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='formula_jabatan_target_add_data.php?reqId=<?=$reqId?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
			else if(varItem == 'formula_jabatan_target_add_list'){
				parent.setShowHideMenu(2);
				$('#formula_jabatan_target_add_list').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='formula_jabatan_target_add_list.php?reqId=<?=$reqId?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
			return true;
		}
    </script> 

    <!-- SDMENU -->
    <link rel="stylesheet" type="text/css" href="../WEB/lib/sdmenu/sdmenu.css" />
    <script type="text/javascript" src="../WEB/lib/sdmenu/sdmenu.js"></script>
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
                <span>Jabatan Target</span>
                <a href="#" id="data" onclick="executeOnClick('data');" class="menuAktifDynamis">Data</a>
                <?
                if(!empty($reqId))
                {
                ?>
                <a href="#" id="formula_jabatan_target_add_list" onclick="executeOnClick('formula_jabatan_target_add_list');">List Pegawai</a>
                <?
                }
                ?>
              </div>
            <!-- END SDMENU -->
            </div>
            
        </div>
    </body>
<script type="text/javascript">
<?
if($reqMode == ""){}
else
{
?>
$("a").removeClass("menuAktifDynamis");
executeOnClick('<?=$reqMode?>');
<?
}
?>
</script>
</html>