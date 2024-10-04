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
include_once("../WEB/classes/base/FormulaAssesmentUjianTahap.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqId= httpFilterRequest("reqId");
$reqMode= httpFilterRequest("reqMode");

$arrLevel="";
$index_arr= 0;
$set= new FormulaEselon();
$statement= " AND B.FORMULA_ID = '".$reqId."'";
// $statement.= " AND COALESCE(B.PROSEN_POTENSI,0) > 0";
$set->selectByParamsMonitoring(array(), -1,-1, $reqId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrLevel[$index_arr]["FORMULA_ESELON_ID"] = $set->getField("FORMULA_ESELON_ID");
	$arrLevel[$index_arr]["NAMA_ESELON"] = $set->getField("NAMA_ESELON");
    $arrLevel[$index_arr]["tahun"] = $set->getField("tahun");
	$index_arr++;
}
unset($set);
$jumlah_level= $index_arr;

$jumlahtahapan= 0;
if(!empty($reqId))
{
    $setdetil= new FormulaAssesmentUjianTahap();
    $jumlahtahapan= $setdetil->getCountByParams(array(), " AND FORMULA_ASSESMENT_ID = '".$reqId."'");
    // echo $setdetil->query;exit;
}
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
				parent.mainFrame.location.href='master_jadwal_add_data.php?reqId=<?=$reqId?>&reqMode=<?=$reqMode?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
			else if(varItem == 'data'){
				parent.setShowHideMenu(2);
				$('#data').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='formula_assesment_add_data.php?reqId=<?=$reqId?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
			else if(varItem == 'formula_eselon'){
				parent.setShowHideMenu(2);
				$('#formula_eselon').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='formula_assesment_add_eselon.php?reqId=<?=$reqId?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
            else if(varItem == 'formula_ujian_tahap'){
                parent.setShowHideMenu(2);
                $('#formula_ujian_tahap').addClass("menuAktifDynamis");
                parent.mainFrame.location.href='formula_assesment_add_ujian_tahap.php?reqId=<?=$reqId?>';
                parent.document.getElementById('trdetil').style.display = 'none';
            }
            else if(varItem == 'formula_assesment_add_ujian_tahap_urutan'){
                parent.setShowHideMenu(2);
                $('#formula_assesment_add_ujian_tahap_urutan').addClass("menuAktifDynamis");
                parent.mainFrame.location.href='formula_assesment_add_ujian_tahap_urutan.php?reqId=<?=$reqId?>';
                parent.document.getElementById('trdetil').style.display = 'none';
            }
            
			<?
			for($checkbox_index=0;$checkbox_index < $jumlah_level;$checkbox_index++)
			{
				$tempLevelId= $arrLevel[$checkbox_index]["FORMULA_ESELON_ID"];
				$tempLevel= $arrLevel[$checkbox_index]["NAMA_ESELON"];
			?>
			else if(varItem == 'formula_atribut_<?=$tempLevelId?>'){
				parent.setShowHideMenu(2);
				$('#formula_atribut_<?=$tempLevelId?>').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='formula_assesment_add_atribut.php?reqId=<?=$reqId?>&reqRowId=<?=$tempLevelId?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
            else if(varItem == 'formula_atribut_urut_<?=$tempLevelId?>'){
                parent.setShowHideMenu(2);
                $('#formula_atribut_urut_<?=$tempLevelId?>').addClass("menuAktifDynamis");
                parent.mainFrame.location.href='formula_assesment_add_atribut_urutan.php?reqId=<?=$reqId?>&reqRowId=<?=$tempLevelId?>';
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
                <span>Formula</span>
                <a href="#" id="data" onclick="executeOnClick('data');" class="menuAktifDynamis">Data</a>
                <?
                if(!empty($reqId))
                {
                ?>
                    <a href="#" id="formula_eselon" onclick="executeOnClick('formula_eselon');">Eselon</a>
                    <?
    				for($checkbox_index=0;$checkbox_index < $jumlah_level;$checkbox_index++)
    				{
    					$tempLevelId= $arrLevel[$checkbox_index]["FORMULA_ESELON_ID"];
    					$tempLevel= $arrLevel[$checkbox_index]["NAMA_ESELON"];
                        $tempTahun= $arrLevel[$checkbox_index]["tahun"];
    				?>
                    <a href="#" id="formula_atribut_<?=$tempLevelId?>" onclick="executeOnClick('formula_atribut_<?=$tempLevelId?>');">Atribut <?=$tempLevel?></a>
                    <?if($tempTahun>2022){?>
                    <a href="#" id="formula_atribut_urut_<?=$tempLevelId?>" onclick="executeOnClick('formula_atribut_urut_<?=$tempLevelId?>');">Urutan Atribut <?=$tempLevel?></a>

                    <?
                        }
    				}
                    ?>
                    <a href="#" id="formula_ujian_tahap" onclick="executeOnClick('formula_ujian_tahap');">Simulasi Soal</a>

                    <?
                    if($jumlahtahapan > 0)
                    {
                    ?>
                    <a href="#" id="formula_assesment_add_ujian_tahap_urutan" onclick="executeOnClick('formula_assesment_add_ujian_tahap_urutan');">Urutan Soal</a>
                    <?
                    }
                    ?>
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