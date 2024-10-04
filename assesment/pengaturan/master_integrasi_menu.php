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
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqJenis = httpFilterRequest("reqJenis");

$set= new Kelautan();
$set->selectByParamsMonitoring2(array('A.PEGAWAI_ID'=>$reqId),-1,-1);
$set->firstRow();$tempNama=$set->getField('NAMA');
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
			parent.setShowHideMenu(2);
			$('#jadwal').addClass("menuAktifDynamis");
			parent.mainFrame.location.href='master_integrasi_data.php?reqId=<?=$reqId?>&reqMode='+varItem;
			parent.document.getElementById('trdetil').style.display = 'none';
			return true;
		}
		
		function hidenJadwalMenu()
		{
			//$('#simulasi_pegawai, #simulasi_asesor_tools, #simulasi_asesor_acara, #master_jadwal_add_jadwal_acara_potensi').hide();
			$('#simulasi_asesor_tools, #simulasi_asesor_acara, #master_jadwal_add_jadwal_acara_potensi').hide();
		}
		
		function showJadwalMenu()
		{
			$('#acara, #kelompok_ruang, #asesor, #pegawai,#jadwal_asesor_potensi,#jadwal_asesor_potensi_pegawai').hide();
		}
    </script> 
    <script type="text/javascript">
        $(document).ready(function(){
			hidenJadwalMenu();
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
                <div>
                    <div style="margin-top:5px; width:230px; margin-left:5px; float:left; position:relative; text-align:left;">
                        <?php /*?><div style="border:2px solid #FFF; float:left; margin-right:4px; height:66px; width:50px; -webkit-box-shadow: 0 8px 6px -6px black; -moz-box-shadow: 0 8px 6px -6px black; box-shadow: 0 8px 6px -6px black; ">
                            <img src="image_script.php?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=pegawai" width="50" height="66">
                        </div><?php */?>

                        <div style="float:left; position:relative; width:170px;"> 
                            <div style="color:#000; font-size:14px; text-shadow:1px 1px 1px #FFF;"><?=$tempNama?></div>
                            <div style="color:#000; font-size:12px; text-shadow:1px 1px 1px #FFF; line-height:20px;"><strong>Nip : </strong><?=$tempNipBaru?> </div>
                        </div>

                    </div>
                    <div style="clear:both"></div>
                    <div style="color:#000; text-align:center; margin-left:5px; margin-top:10px; font-size:12px; line-height:34px; text-shadow:1px 1px 1px #FFF; border-top:1px dashed #FFF; ">
                    <!--<img src="../WEB/images/chair.png" />&nbsp;-->
                    -&nbsp;<?=$tempPangkatTerkahir?> / <?=$tempJabatanTerkahir?>&nbsp;-
                    </div>
                </div>

                <div style="float: left" id="my_menu" class="sdmenu">
                	<div>
                    <span>Data Riwayat</span>
                    <a href="#" id="riwayat_skp" onclick="executeOnClick('riwayat_skp');" <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Riwayat SKP</a>
                    <a href="#" id="riwayat_penghargaan" onclick="executeOnClick('riwayat_penghargaan');" <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Riwayat Penghargaan</a>
                    <a href="#" id="riwayat_pendidikan" onclick="executeOnClick('riwayat_pendidikan');" <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Riwayat Pendidikan</a>
                    <a href="#" id="riwayat_hukdis" onclick="executeOnClick('riwayat_hukdis');" <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Riwayat Hukdis</a>
                  </div>
                <!-- END SDMENU -->
            	</div>
                    
            </div>
        </div>
    </body>
</html>